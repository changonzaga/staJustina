<?php

namespace App\Controllers;

use App\Models\SectionModel;
use CodeIgniter\HTTP\ResponseInterface;

class SectionController extends BaseController
{
    protected $sectionModel;
    
    public function __construct()
    {
        $this->sectionModel = new SectionModel();
        helper(['form', 'url']);
    }
    
    /**
     * Display all sections
     */
    public function index()
    {
        try {
            // Get sections with grade and adviser information
            $sections = $this->sectionModel->getAllSectionsWithDetails();
            
            // Get all grades for dropdown
            $gradeModel = new \App\Models\GradeModel();
            $grades = $gradeModel->getAllGrades();
            
            // Get all teachers for dropdown
            $teacherModel = new \App\Models\TeacherModel();
            $teachers = $teacherModel->getActiveTeachers();

            $data = [
                'sections' => $sections,
                'grades' => $grades,
                'teachers' => $teachers,
                'pageTitle' => 'Sections Management'
            ];
            
            return view('backend/admin/sections/section', $data);
            
        } catch (\Exception $e) {
            // Handle any database errors gracefully
            $data = [
                'pageTitle' => 'Sections Management',
                'sections' => [],
                'grades' => [],
                'teachers' => [],
                'error' => 'Database error: ' . $e->getMessage()
            ];
            return view('backend/admin/sections/section', $data);
        }
    }
    
    /**
     * Get all sections (AJAX)
     */
    public function getSections()
    {
        // Check if it's AJAX request or has proper headers
        if (!$this->request->isAJAX() && !$this->request->hasHeader('X-Requested-With')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        try {
            $sections = $this->sectionModel->getAllSectionsWithDetails();
            return $this->response->setJSON([
                'success' => true,
                'data' => $sections
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to fetch sections: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get single section details (AJAX)
     */
    public function getSection($id)
    {
        // Check if it's AJAX request or has proper headers
        if (!$this->request->isAJAX() && !$this->request->hasHeader('X-Requested-With')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        try {
            $section = $this->sectionModel->getSectionWithDetails($id);
            
            if (!$section) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Section not found'
                ]);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $section
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to fetch section: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Store new section (AJAX)
     */
    public function store()
    {
        // Check if it's AJAX request or has proper headers
        if (!$this->request->isAJAX() && !$this->request->hasHeader('X-Requested-With')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        // Validation rules
        $rules = [
            'grade_id' => 'required|integer',
            'section_name' => 'required|min_length[2]|max_length[100]',
            'capacity' => 'required|integer|greater_than[0]|less_than[100]',
            'school_year' => 'required|max_length[10]',
            'adviser_id' => 'permit_empty|integer'
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
                'grade_id' => $this->request->getPost('grade_id'),
                'section_name' => $this->request->getPost('section_name'),
                'capacity' => $this->request->getPost('capacity'),
                'school_year' => $this->request->getPost('school_year'),
                'adviser_id' => $this->request->getPost('adviser_id') ?: null
            ];
            
            // Check if section already exists
            if ($this->sectionModel->sectionExists($data['grade_id'], $data['section_name'], $data['school_year'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Section already exists for this grade and school year'
                ]);
            }
            
            $sectionId = $this->sectionModel->insert($data);
            
            if ($sectionId) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Section created successfully',
                    'data' => ['id' => $sectionId]
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create section'
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
     * Update section (AJAX)
     */
    public function update($id)
    {
        // Check if it's AJAX request or has proper headers
        if (!$this->request->isAJAX() && !$this->request->hasHeader('X-Requested-With')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        // Validation rules
        $rules = [
            'grade_id' => 'required|integer',
            'section_name' => 'required|min_length[2]|max_length[100]',
            'capacity' => 'required|integer|greater_than[0]|less_than[100]',
            'school_year' => 'required|max_length[10]',
            'adviser_id' => 'permit_empty|integer'
        ];
        
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }
        
        try {
            $section = $this->sectionModel->find($id);
            
            if (!$section) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Section not found'
                ]);
            }
            
            $data = [
                'grade_id' => $this->request->getPost('grade_id'),
                'section_name' => $this->request->getPost('section_name'),
                'capacity' => $this->request->getPost('capacity'),
                'school_year' => $this->request->getPost('school_year'),
                'adviser_id' => $this->request->getPost('adviser_id') ?: null
            ];
            
            // Check if section already exists (excluding current section)
            if ($this->sectionModel->sectionExists($data['grade_id'], $data['section_name'], $data['school_year'], $id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Section already exists for this grade and school year'
                ]);
            }
            
            $updated = $this->sectionModel->update($id, $data);
            
            if ($updated) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Section updated successfully'
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
     * Delete section (AJAX)
     */
    public function delete($id)
    {
        // Check if it's AJAX request or has proper headers
        if (!$this->request->isAJAX() && !$this->request->hasHeader('X-Requested-With')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        try {
            $section = $this->sectionModel->find($id);
            
            if (!$section) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Section not found'
                ]);
            }
            
            $deleted = $this->sectionModel->delete($id);
            
            if ($deleted) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Section deleted successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete section'
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
     * Get dropdown data for forms (AJAX)
     */
    public function getDropdowns()
    {
        // Check if it's AJAX request or has proper headers
        if (!$this->request->isAJAX() && !$this->request->hasHeader('X-Requested-With')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        try {
            $data = $this->sectionModel->getDropdowns();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to fetch dropdown data: ' . $e->getMessage()
            ]);
        }
    }
}
