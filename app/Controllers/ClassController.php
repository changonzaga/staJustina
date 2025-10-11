<?php

namespace App\Controllers;

use App\Models\ClassModel;
use CodeIgniter\HTTP\ResponseInterface;

class ClassController extends BaseController
{
    protected $classModel;
    
    public function __construct()
    {
        $this->classModel = new ClassModel();
        helper(['form', 'url']);
    }
    
    /**
     * Display all classes
     */
    public function index()
    {
        $data = [
            'classes' => $this->classModel->getAllClassesWithDetails(),
            'pageTitle' => 'Manage Classes'
        ];
        
        return view('backend/admin/class/class', $data);
    }
    
    /**
     * Get all classes (AJAX)
     */
    public function getClasses()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        try {
            $classes = $this->classModel->getAllClassesWithDetails();
            return $this->response->setJSON([
                'success' => true,
                'data' => $classes
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to fetch classes: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get single class details (AJAX)
     */
    public function getClass($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        try {
            $class = $this->classModel->getClassWithDetails($id);
            
            if (!$class) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Class not found'
                ]);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $class
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to fetch class: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Store new class (AJAX)
     */
    public function store()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        // Validation rules
        $rules = [
            'class_name' => 'required|min_length[3]|max_length[100]',
            'grade_id' => 'required|integer',
            'section_id' => 'required|integer'
        ];
        
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }
        
        try {
            // Log the POST data for debugging
            log_message('debug', 'Class creation POST data: ' . json_encode($this->request->getPost()));
            
            // Generate unique class code
            $classCode = $this->classModel->generateClassCode();
            
            $data = [
                'class_code' => $classCode,
                'class_name' => $this->request->getPost('class_name'),
                'grade_id' => (int)$this->request->getPost('grade_id'),
                'section_id' => (int)$this->request->getPost('section_id'),
                'teacher_id' => $this->request->getPost('teacher_id') ? (int)$this->request->getPost('teacher_id') : null,
                'academic_period_id' => $this->request->getPost('academic_period_id') ? (int)$this->request->getPost('academic_period_id') : null,
                'strand_id' => $this->request->getPost('strand_id') ? (int)$this->request->getPost('strand_id') : null
            ];
            
            // Log the data being inserted
            log_message('debug', 'Class data to insert: ' . json_encode($data));
            
            $insertId = $this->classModel->insert($data);
            
            if ($insertId) {
                log_message('info', 'Class created successfully with ID: ' . $insertId);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Class added successfully',
                    'data' => ['id' => $insertId, 'class_code' => $classCode]
                ]);
            } else {
                $errors = $this->classModel->errors();
                log_message('error', 'Failed to add class. Errors: ' . json_encode($errors));
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to add class: ' . implode(', ', $errors)
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception during class creation: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Update class (AJAX)
     */
    public function update($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        // Check if class exists
        $existingClass = $this->classModel->find($id);
        if (!$existingClass) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Class not found'
            ]);
        }
        
        // Validation rules
        $rules = [
            'class_name' => 'required|min_length[3]|max_length[100]',
            'grade_id' => 'required|integer',
            'section_id' => 'required|integer'
        ];
        
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }
        
        try {
            $data = [
                'class_name' => $this->request->getPost('class_name'),
                'grade_id' => $this->request->getPost('grade_id'),
                'section_id' => $this->request->getPost('section_id'),
                'teacher_id' => $this->request->getPost('teacher_id') ?: null,
                'academic_period_id' => $this->request->getPost('academic_period_id') ?: null,
                'strand_id' => $this->request->getPost('strand_id') ?: null
            ];
            
            $updated = $this->classModel->update($id, $data);
            
            if ($updated) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Class updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No changes made or update failed'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Delete class (AJAX)
     */
    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        try {
            $class = $this->classModel->find($id);
            
            if (!$class) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Class not found'
                ]);
            }
            
            // Check if class has students (optional safety check)
            $hasStudents = $this->classModel->hasEnrolledStudents($id);
            if ($hasStudents) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Cannot delete class with enrolled students'
                ]);
            }
            
            $deleted = $this->classModel->delete($id);
            
            if ($deleted) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Class deleted successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete class'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get students for a specific class (AJAX)
     */
    public function getStudents($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        try {
            $students = $this->classModel->getClassStudents($id);
            $class = $this->classModel->getClassWithDetails($id);
            
            if (!$class) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Class not found'
                ]);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'class' => $class,
                    'students' => $students
                ]
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to fetch students: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get dropdown data for forms (AJAX)
     */
    public function getDropdowns()
    {
        if (!$this->request->isAJAX()) {
            log_message('error', 'getDropdowns: Non-AJAX request received');
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        try {
            log_message('debug', 'getDropdowns: Building dropdowns with column aliasing');
            $db = \Config\Database::connect();

            // Grades: alias to grade_name
            $gradeColumns = $db->getFieldNames('grades');
            $gradeNameCol = in_array('grade_name', $gradeColumns) ? 'grade_name' : (in_array('grade_level', $gradeColumns) ? 'grade_level' : null);
            $grades = [];
            if ($gradeNameCol !== null) {
                $grades = $db->table('grades')
                    ->select("id, {$gradeNameCol} AS grade_name", false)
                    ->orderBy($gradeNameCol, 'ASC')
                    ->get()
                    ->getResultArray();
            }

            // Sections: alias to section_name
            $sectionColumns = $db->getFieldNames('sections');
            $sectionNameCol = in_array('section_name', $sectionColumns) ? 'section_name' : (in_array('name', $sectionColumns) ? 'name' : null);
            $sections = [];
            if ($sectionNameCol !== null) {
                $sections = $db->table('sections')
                    ->select("id, {$sectionNameCol} AS section_name", false)
                    ->orderBy($sectionNameCol, 'ASC')
                    ->get()
                    ->getResultArray();
            }

            // Teachers: use helper that returns id and full_name
            $teacherModel = new \App\Models\TeacherModel();
            $teachers = $teacherModel->getActiveTeachers();

            log_message('debug', 'getDropdowns counts => grades: ' . count($grades) . ', sections: ' . count($sections) . ', teachers: ' . count($teachers));

            $data = [
                'grades' => $grades,
                'sections' => $sections,
                'teachers' => $teachers
            ];

            return $this->response->setJSON([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            log_message('error', 'getDropdowns error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to fetch dropdown data: ' . $e->getMessage()
            ]);
        }
    }
}