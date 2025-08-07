<?php

namespace App\Controllers\Backend\Pages;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use App\Models\ParentModel;

class StudentsController extends BaseController
{
    public function index()
    {
        $model = new StudentModel();
        $data['students'] = $model->findAll();
        return view('backend/admin/students/index', $data);
    }

    public function create()
    {
        $teacherModel = new TeacherModel();
        $parentModel = new ParentModel();

        $data = [
            'teachers' => $teacherModel->findAll(),
            'parents' => $parentModel->findAll(),
        ];
        return view('backend/admin/students/create', $data);
    }

    public function store()
    {
        try {
            $db = \Config\Database::connect();
            $validation = \Config\Services::validation();
            $model = new StudentModel();
            $isAjax = $this->request->isAJAX();

            // Validate form input
            $validation->setRules([
                'name' => 'required|min_length[3]|is_unique[student.name]',
                'lrn' => 'required|min_length[12]|is_unique[student.lrn]|numeric',
                'grade_level' => 'required',
                'section' => 'required',
                'gender' => 'required|in_list[Male,Female,Other]',
                'age' => 'required|numeric|greater_than[0]',
                'guardian' => 'permit_empty|string',
                'contact' => 'permit_empty|string',
                'address' => 'permit_empty|string',
                'profile_picture' => 'permit_empty|if_exist|uploaded[profile_picture]|max_size[profile_picture,2048]|is_image[profile_picture]|mime_in[profile_picture,image/jpg,image/jpeg,image/png]',
                'cropped_image' => 'permit_empty',
            ]);
            
            // Custom validation messages
            $validation->setRules([
                'name' => [
                    'rules' => 'required|min_length[3]|is_unique[student.name]',
                    'errors' => [
                        'is_unique' => 'A student with the same name already exists.'
                    ]
                ],
                'lrn' => [
                    'rules' => 'required|exact_length[12]|is_unique[student.lrn]|numeric',
                    'errors' => [
                        'is_unique' => 'This LRN already exists in the system.',
                        'numeric' => 'LRN must contain only numeric values.',
                        'exact_length' => 'LRN must be exactly 12 digits'
                    ]
                ]
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                $errors = $validation->getErrors();
                log_message('error', 'Validation failed: ' . json_encode($errors));
                
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $errors
                    ]);
                }
                return redirect()->back()->withInput()->with('errors', $errors);
            }

            // Check table existence
            if (!in_array($model->table, $db->listTables())) {
                $errorMsg = "Table '{$model->table}' does not exist. Please contact admin.";
                log_message('error', $errorMsg);
                
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => $errorMsg
                    ]);
                }
                return redirect()->back()->withInput()->with('error', $errorMsg);
            }

            // Prepare data
            $data = [
                'name' => trim($this->request->getPost('name')),
                'lrn' => trim($this->request->getPost('lrn')),
                'grade_level' => trim($this->request->getPost('grade_level')),
                'section' => trim($this->request->getPost('section')),
                'gender' => $this->request->getPost('gender'),
                'age' => (int)$this->request->getPost('age'),
                'guardian' => trim($this->request->getPost('guardian')) ?: null,
                'contact' => trim($this->request->getPost('contact')) ?: null,
                'address' => trim($this->request->getPost('address')) ?: null,
                'teacher_id' => $this->request->getPost('teacher_id') && is_numeric($this->request->getPost('teacher_id')) ? $this->request->getPost('teacher_id') : null,
                'parent_id' => $this->request->getPost('parent_id') && is_numeric($this->request->getPost('parent_id')) ? $this->request->getPost('parent_id') : null,
            ];

            // Handle cropped image upload
            $croppedImage = $this->request->getPost('cropped_image');
            if (!empty($croppedImage)) {
                // The cropped image is a base64 encoded string
                $uploadPath = FCPATH . 'uploads/students';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Remove the data URL prefix and decode
                $base64Image = preg_replace('#^data:image/\w+;base64,#i', '', $croppedImage);
                $imageData = base64_decode($base64Image);
                
                // Generate a unique filename
                $newFileName = uniqid() . '.png';
                $filePath = $uploadPath . '/' . $newFileName;
                
                // Save the image
                if (file_put_contents($filePath, $imageData)) {
                    $data['profile_picture'] = $newFileName;
                }
            } else {
                // Fallback to regular file upload if no cropped image
                $profilePicture = $this->request->getFile('profile_picture');
                if ($profilePicture && $profilePicture->isValid() && !$profilePicture->hasMoved()) {
                    $uploadPath = FCPATH . 'uploads/students';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }

                    $newFileName = $profilePicture->getRandomName();
                    if ($profilePicture->move($uploadPath, $newFileName)) {
                        $data['profile_picture'] = $newFileName;
                    }
                }
            }
            // Log the data before insertion
            log_message('debug', 'Final data to insert: ' . json_encode($data));
            //Insert data into the database
            $insertId = $model->insert($data);

            if (!$insertId) {
                $errors = $model->errors();
                $errorMsg = 'Failed to create student. ' . json_encode($errors);
                log_message('error', 'Insert failed: ' . $errorMsg);
                
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => $errorMsg,
                        'errors' => $errors
                    ]);
                }
                return redirect()->back()->withInput()->with('error', $errorMsg);
            }

            log_message('info', 'Student inserted successfully. ID: ' . $insertId);
            
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Student successfully added!',
                    'redirect' => site_url('admin/student')
                ]);
            }
            return redirect()->to(route_to('admin.students.index'))->with('success', 'Student successfully added!');

        } catch (\Exception $e) {
            $errorMsg = 'An error occurred: ' . $e->getMessage();
            log_message('error', 'Exception during student creation: ' . $errorMsg);
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $errorMsg
                ]);
            }
            return redirect()->back()->withInput()->with('error', $errorMsg);
        }
    }

    public function edit($id = null)
    {
        if (!$id) {
            return redirect()->to(route_to('admin.students.index'))->with('error', 'Student ID is required.');
        }

        $model = new StudentModel();
        $student = $model->find($id);

        if (!$student) {
            return redirect()->to(route_to('admin.students.index'))->with('error', 'Student not found.');
        }

        $teacherModel = new TeacherModel();
        $parentModel = new ParentModel();

        $data = [
            'student' => $student,
            'teachers' => $teacherModel->findAll(),
            'parents' => $parentModel->findAll(),
        ];

        return view('backend/admin/students/edit', $data);
    }

    public function profile($id = null)
    {
        if (!$id) {
            return redirect()->to(route_to('admin.students.index'))->with('error', 'Student ID is required.');
        }
        
        $db = \Config\Database::connect();
        
        // Get student data with teacher and parent information
        $student = $db->table('student s')
            ->select('s.*, t.name as teacher_name, p.name as parent_name')
            ->join('teacher t', 's.teacher_id = t.id', 'left')
            ->join('parent p', 's.parent_id = p.id', 'left')
            ->where('s.id', $id)
            ->get()->getRowArray();
        
        if (!$student) {
            return redirect()->to(route_to('admin.students.index'))->with('error', 'Student not found.');
        }
        
        // Get recent attendance records (last 5)
        $attendance = $db->table('attendance')
            ->where('student_id', $id)
            ->orderBy('date', 'DESC')
            ->limit(5)
            ->get()->getResultArray();
        
        $data = [
            'student' => $student,
            'attendance' => $attendance
        ];
        
        return view('backend/admin/students/student_profile', $data);
    }

    public function update($id = null)
    {
        try {
            if (!$id) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Student ID is required.']);
                }
                return redirect()->to(route_to('admin.students.index'))->with('error', 'Student ID is required.');
            }

            $model = new StudentModel();
            $student = $model->find($id);

            if (!$student) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Student not found.']);
                }
                return redirect()->to(route_to('admin.students.index'))->with('error', 'Student not found.');
            }

            // Validation rules
            $validation = \Config\Services::validation();
            $validation->setRules([
                'name' => 'required|min_length[3]|max_length[255]|regex_match[/^[^0-9]+$/]',
                'lrn' => 'required|exact_length[12]|numeric',
                'grade_level' => 'required',
                'section' => 'required',
                'gender' => 'required|in_list[Male,Female,Other]',
                'age' => 'required|numeric|greater_than[0]',
                'profile_picture' => 'permit_empty|is_image[profile_picture]|max_size[profile_picture,2048]',
                'cropped_image' => 'permit_empty',
            ], [
                'lrn' => [
                    'numeric' => 'LRN must contain only numeric values',
                    'exact_length' => 'LRN must be exactly 12 digits'
                ],
                'name' => [
                    'required' => 'Student name is required',
                    'regex_match' => 'Student name must not contain any numbers'
                ]
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Validation failed', 'errors' => $validation->getErrors()]);
                }
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            // Prepare data
            $data = [
                'name' => trim($this->request->getPost('name')),
                'lrn' => trim($this->request->getPost('lrn')),
                'grade_level' => trim($this->request->getPost('grade_level')),
                'section' => trim($this->request->getPost('section')),
                'gender' => $this->request->getPost('gender'),
                'age' => (int)$this->request->getPost('age'),
                'guardian' => trim($this->request->getPost('guardian')) ?: null,
                'contact' => trim($this->request->getPost('contact')) ?: null,
                'address' => trim($this->request->getPost('address')) ?: null,
                'teacher_id' => $this->request->getPost('teacher_id') && is_numeric($this->request->getPost('teacher_id')) ? $this->request->getPost('teacher_id') : null,
                'parent_id' => $this->request->getPost('parent_id') && is_numeric($this->request->getPost('parent_id')) ? $this->request->getPost('parent_id') : null,
            ];

            // Handle cropped image upload
            $croppedImage = $this->request->getPost('cropped_image');
            if (!empty($croppedImage)) {
                // The cropped image is a base64 encoded string
                $uploadPath = FCPATH . 'uploads/students';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Delete old profile picture if exists
                if (!empty($student['profile_picture']) && file_exists($uploadPath . '/' . $student['profile_picture'])) {
                    unlink($uploadPath . '/' . $student['profile_picture']);
                }
                
                // Remove the data URL prefix and decode
                $base64Image = preg_replace('#^data:image/\w+;base64,#i', '', $croppedImage);
                $imageData = base64_decode($base64Image);
                
                // Generate a unique filename
                $newFileName = uniqid() . '.png';
                $filePath = $uploadPath . '/' . $newFileName;
                
                // Save the image
                if (file_put_contents($filePath, $imageData)) {
                    $data['profile_picture'] = $newFileName;
                }
            } else {
                // Fallback to regular file upload if no cropped image
                $profilePicture = $this->request->getFile('profile_picture');
                if ($profilePicture && $profilePicture->isValid() && !$profilePicture->hasMoved()) {
                    $uploadPath = FCPATH . 'uploads/students';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }

                    // Delete old profile picture if exists
                    if (!empty($student['profile_picture']) && file_exists($uploadPath . '/' . $student['profile_picture'])) {
                        unlink($uploadPath . '/' . $student['profile_picture']);
                    }

                    $newFileName = $profilePicture->getRandomName();
                    if ($profilePicture->move($uploadPath, $newFileName)) {
                        $data['profile_picture'] = $newFileName;
                    }
                }
            }

            // Update data in the database
            $result = $model->update($id, $data);

            if (!$result) {
                log_message('error', 'Update failed: ' . json_encode($model->errors()));
                if ($this->request->isAJAX()) {
                    log_message('debug', 'Sending error response for AJAX request: ' . json_encode(['success' => false, 'message' => 'Failed to update student.', 'errors' => $model->errors()]));
                    return $this->response->setJSON(['success' => false, 'message' => 'Failed to update student.', 'errors' => $model->errors()]);
                }
                return redirect()->back()->withInput()->with('error', 'Failed to update student.');
            }

            log_message('info', 'Student updated successfully. ID: ' . $id);
            if ($this->request->isAJAX()) {
                // Ensure proper JSON response with success flag
                $response = [
                    'success' => true,
                    'message' => 'Student successfully updated!',
                    'redirect' => site_url('admin/student')
                ];
                log_message('debug', 'Sending success response: ' . json_encode($response));
                return $this->response->setJSON($response);
            }
            return redirect()->to(site_url('admin/student'))->with('success', 'Student successfully updated!');

        } catch (\Exception $e) {
            log_message('error', 'Exception during student update: ' . $e->getMessage());
            log_message('error', 'Exception trace: ' . $e->getTraceAsString());
            if ($this->request->isAJAX()) {
                $errorResponse = ['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()];
                log_message('debug', 'Sending exception response for AJAX request: ' . json_encode($errorResponse));
                return $this->response->setJSON($errorResponse);
            }
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function delete($id = null)
    {
        try {
            if (!$id) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Student ID is required.'
                    ]);
                }
                return redirect()->to(route_to('admin.students.index'))->with('error', 'Student ID is required.');
            }

            $model = new StudentModel();
            $student = $model->find($id);

            if (!$student) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Student not found.'
                    ]);
                }
                return redirect()->to(route_to('admin.students.index'))->with('error', 'Student not found.');
            }

            // Delete profile picture if exists
            if (!empty($student['profile_picture'])) {
                $uploadPath = FCPATH . 'Uploads/students';
                $filePath = $uploadPath . '/' . $student['profile_picture'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // Delete student record
            if (!$model->delete($id)) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to delete student.'
                    ]);
                }
                return redirect()->to(route_to('admin.students.index'))->with('error', 'Failed to delete student.');
            }

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Student successfully deleted!',
                    'redirect' => site_url('admin/student')
                ]);
            }
            return redirect()->to(route_to('admin.students.index'))->with('success', 'Student successfully deleted!');
        } catch (\Exception $e) {
            log_message('error', 'Exception during student deletion: ' . $e->getMessage());
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'An error occurred: ' . $e->getMessage()
                ]);
            }
            return redirect()->to(route_to('admin.students.index'))->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
