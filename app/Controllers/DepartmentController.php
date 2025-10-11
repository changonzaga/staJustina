<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DepartmentModel;

class DepartmentController extends BaseController
{
    public function index()
    {
        $model = new DepartmentModel();
        $departments = $model->orderBy('id', 'DESC')->findAll();

        // Load teachers for head selection with more details
        $db = \Config\Database::connect();
        $columns = $db->getFieldNames('teachers');
        
        // Debug: Log the actual columns that exist
        log_message('info', 'Teachers table columns: ' . json_encode($columns));
        
        // Check what columns actually exist and build query accordingly
        $hasNameField = in_array('name', $columns);
        $hasStatusField = in_array('status', $columns);
        $hasSubjectsField = in_array('subjects', $columns);
        
        // Build select fields based on what actually exists
        $selectFields = "id";
        if ($hasNameField) $selectFields .= ", name";
        if ($hasSubjectsField) $selectFields .= ", subjects";
        if ($hasStatusField) $selectFields .= ", status";
        
        // If no name field, try alternative fields
        if (!$hasNameField) {
            if (in_array('first_name', $columns)) $selectFields .= ", first_name";
            if (in_array('last_name', $columns)) $selectFields .= ", last_name";
            if (in_array('full_name', $columns)) $selectFields .= ", full_name";
        }
        
        $whereClause = $hasStatusField ? ['status' => 'Active'] : ['1=1' => '1'];
        
        $teachers = $db->table('teachers')
            ->select($selectFields, false)
            ->where($whereClause)
            ->orderBy($hasNameField ? 'name' : 'id', 'ASC')
            ->get()
            ->getResultArray();

        return view('backend/admin/department/department', [
            'pageTitle' => 'Department Management',
            'departments' => $departments,
            'teachers' => $teachers,
        ]);
    }

    public function getTeachers()
    {
        try {
            $search = $this->request->getGet('search');
            log_message('info', 'getTeachers called with search: ' . ($search ?? 'empty'));
            
            if (empty($search) || strlen(trim($search)) < 2) {
                log_message('info', 'Search term too short or empty');
                return $this->response->setJSON(['results' => [], 'pagination' => ['more' => false]]);
            }
            
            $db = \Config\Database::connect();
            $columns = $db->getFieldNames('teachers');
            
            // Debug: Log the actual columns that exist
            log_message('info', 'getTeachers - Teachers table columns: ' . json_encode($columns));
            
            // Check what columns actually exist
            $hasNameField = in_array('name', $columns);
            $hasStatusField = in_array('status', $columns);
            $hasSubjectsField = in_array('subjects', $columns);
            $hasFirstNameField = in_array('first_name', $columns);
            $hasLastNameField = in_array('last_name', $columns);
            
            // Build select fields based on what actually exists
            $selectFields = "id";
            if ($hasNameField) $selectFields .= ", name";
            if ($hasSubjectsField) $selectFields .= ", subjects";
            if ($hasStatusField) $selectFields .= ", status";
            if ($hasFirstNameField) $selectFields .= ", first_name";
            if ($hasLastNameField) $selectFields .= ", last_name";
            
            $query = $db->table('teachers')
                ->select($selectFields, false);
            
            // Only filter by status if the field exists
            if ($hasStatusField) {
                $query->where('status', 'Active');
            }
            
            // Build search conditions based on available fields
            $query->groupStart();
            if ($hasNameField) {
                $query->like('name', trim($search));
            }
            if ($hasFirstNameField) {
                if ($hasNameField) $query->orLike('first_name', trim($search));
                else $query->like('first_name', trim($search));
            }
            if ($hasLastNameField) {
                if ($hasNameField || $hasFirstNameField) $query->orLike('last_name', trim($search));
                else $query->like('last_name', trim($search));
            }
            if ($hasSubjectsField) {
                if ($hasNameField || $hasFirstNameField || $hasLastNameField) $query->orLike('subjects', trim($search));
                else $query->like('subjects', trim($search));
            }
            $query->groupEnd();
            
            // Order by available name field
            $orderField = $hasNameField ? 'name' : ($hasFirstNameField ? 'first_name' : 'id');
            $query->orderBy($orderField, 'ASC')->limit(50);
            
            $teachers = $query->get()->getResultArray();
            log_message('info', 'Found ' . count($teachers) . ' teachers for search: ' . $search);
            
            $results = [];
            foreach ($teachers as $teacher) {
                // Build name from available fields
                $name = 'Unknown Teacher';
                if ($hasNameField && !empty($teacher['name'])) {
                    $name = $teacher['name'];
                } elseif ($hasFirstNameField && $hasLastNameField) {
                    $firstName = $teacher['first_name'] ?? '';
                    $lastName = $teacher['last_name'] ?? '';
                    $name = trim($firstName . ' ' . $lastName);
                } elseif ($hasFirstNameField) {
                    $name = $teacher['first_name'] ?? 'Unknown';
                } elseif ($hasLastNameField) {
                    $name = $teacher['last_name'] ?? 'Unknown';
                }
                
                $subjects = $hasSubjectsField ? ($teacher['subjects'] ?? 'General') : 'Teacher';
                $status = $hasStatusField ? ($teacher['status'] ?? 'Active') : 'Active';
                
                $results[] = [
                    'id' => (int)$teacher['id'],
                    'text' => $name . ' (' . $subjects . ' - ' . $status . ')',
                    'full_name' => $name,
                    'position' => $subjects,
                    'status' => $status
                ];
            }
            
            $response = [
                'results' => $results,
                'pagination' => ['more' => false]
            ];
            log_message('info', 'Returning JSON: ' . json_encode($response));
            
            return $this->response->setJSON($response);
            
        } catch (\Exception $e) {
            $errorMsg = 'Error in getTeachers: ' . $e->getMessage();
            log_message('error', $errorMsg);
            return $this->response->setJSON([
                'results' => [],
                'pagination' => ['more' => false],
                'error' => $errorMsg
            ])->setStatusCode(500);
        }
    }

    public function store()
    {
        $model = new DepartmentModel();
        $data = [
            'department_name' => trim((string) $this->request->getPost('department_name')),
            'description' => trim((string) $this->request->getPost('description')),
            'head_id' => $this->request->getPost('head_id') !== null && $this->request->getPost('head_id') !== ''
                ? (int) $this->request->getPost('head_id')
                : null,
        ];

        // If head_id provided, verify existence in teachers to avoid FK violation
        if ($data['head_id'] !== null) {
            $db = \Config\Database::connect();
            $exists = $db->table('teachers')->select('id')->where('id', $data['head_id'])->get()->getFirstRow('array');
            if (!$exists) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => ['head_id' => 'Selected head does not exist.']
                ]);
            }
        }

        if (!$model->insert($data)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $model->errors(),
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Department created successfully.',
            'id' => $model->getInsertID(),
        ]);
    }

    public function show($id)
    {
        $model = new DepartmentModel();
        $dept = $model->find((int) $id);
        if (!$dept) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Department not found'
            ])->setStatusCode(404);
        }
        return $this->response->setJSON([
            'success' => true,
            'data' => $dept,
        ]);
    }

    public function update($id)
    {
        $model = new DepartmentModel();
        $payload = [
            'department_name' => trim((string) $this->request->getPost('department_name')),
            'description' => trim((string) $this->request->getPost('description')),
            'head_id' => $this->request->getPost('head_id') !== null && $this->request->getPost('head_id') !== ''
                ? (int) $this->request->getPost('head_id')
                : null,
        ];

        // Validate head_id existence if provided
        if ($payload['head_id'] !== null) {
            $db = \Config\Database::connect();
            $exists = $db->table('teachers')->select('id')->where('id', $payload['head_id'])->get()->getFirstRow('array');
            if (!$exists) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => ['head_id' => 'Selected head does not exist.']
                ]);
            }
        }

        if (!$model->update((int) $id, $payload)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $model->errors(),
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Department updated successfully.'
        ]);
    }

    public function delete($id)
    {
        $model = new DepartmentModel();
        if (!$model->delete((int) $id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete department.'
            ]);
        }
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Department deleted successfully.'
        ]);
    }
}