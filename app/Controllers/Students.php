<?php
namespace App\Controllers\Backend\Pages;

use App\Controllers\BaseController;

class Students extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // See previous `index` controller snippet
    }

    public function get_by_section($section)
    {
        // See previous `get_by_section` controller snippet
    }

    public function create()
    {
        $teachers = $this->db->table('teacher')->select('id, name')->get()->getResultArray();
        $parents = $this->db->table('parent')->select('id, name')->get()->getResultArray();
        return view('backend/admin/students/create', [
            'teachers' => $teachers,
            'parents' => $parents
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost([
            'lrn', 'name', 'date_of_birth', 'gender', 'age', 'grade_level', 'section',
            'citizenship', 'religion', 'enrollment_status', 'school_assigned', 'school_id',
            'date_of_enrollment', 'address', 'residential_address', 'guardian', 'contact',
            'parent_guardian_name', 'parent_guardian_contact', 'parent_guardian_email',
            'emergency_contact_name', 'emergency_contact_number', 'special_education_needs',
            'health_conditions', 'previous_school_attended', 'previous_school_address',
            'birth_certificate_number', 'student_status', 'remarks', 'teacher_id', 'parent_id'
        ]);
        $file = $this->request->getFile('profile_picture');

        if ($file && $file->isValid()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/Uploads/students', $newName);
            $data['profile_picture'] = $newName;
        }

        // Validate data (e.g., unique LRN)
        if ($this->db->table('students')->where('lrn', $data['lrn'])->countAllResults() > 0) {
            return redirect()->back()->withInput()->with('error', 'LRN already exists.');
        }

        $this->db->table('students')->insert($data);
        return redirect()->to('backend/admin/students')->with('success', 'Student added successfully.');
    }

    public function edit($id)
    {
        $student = $this->db->table('students')->where('id', $id)->get()->getRowArray();
        $teachers = $this->db->table('teacher')->select('id, name')->get()->getResultArray();
        $parents = $this->db->table('parent')->select('id, name')->get()->getResultArray();
        
        if (!$student) {
            return redirect()->to('backend/admin/students')->with('error', 'Student not found.');
        }

        return view('backend/admin/students/edit', [
            'student' => $student,
            'teachers' => $teachers,
            'parents' => $parents
        ]);
    }

    public function update($id)
    {
        $data = $this->request->getPost([
            'lrn', 'name', 'date_of_birth', 'gender', 'age', 'grade_level', 'section',
            'citizenship', 'religion', 'enrollment_status', 'school_assigned', 'school_id',
            'date_of_enrollment', 'address', 'residential_address', 'guardian', 'contact',
            'parent_guardian_name', 'parent_guardian_contact', 'parent_guardian_email',
            'emergency_contact_name', 'emergency_contact_number', 'special_education_needs',
            'health_conditions', 'previous_school_attended', 'previous_school_address',
            'birth_certificate_number', 'student_status', 'remarks', 'teacher_id', 'parent_id'
        ]);
        $file = $this->request->getFile('profile_picture');

        if ($file && $file->isValid()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/Uploads/students', $newName);
            $data['profile_picture'] = $newName;
        }

        // Validate LRN uniqueness (excluding current student)
        $existing = $this->db->table('students')->where('lrn', $data['lrn'])->where('id !=', $id)->countAllResults();
        if ($existing > 0) {
            return redirect()->back()->withInput()->with('error', 'LRN already exists.');
        }

        $this->db->table('students')->where('id', $id)->update($data);
        return redirect()->to('backend/admin/students')->with('success', 'Student updated successfully.');
    }

    public function profile($id)
    {
        // Use StudentModel to fetch normalized complete profile
        $model = new \App\Models\StudentModel();
        $student = $model->getStudentCompleteProfile($id);

        if (!$student) {
            return redirect()->to('backend/admin/students')->with('error', 'Student not found.');
        }

        // Get recent attendance records (last 5)
        $db = \Config\Database::connect();
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
    
    public function delete($id)
    {
        // Check if student exists
        $student = $this->db->table('students')->where('id', $id)->get()->getRowArray();
        
        if (!$student) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Student not found.'
            ]);
        }
        
        // Delete student's profile picture if exists
        if (!empty($student['profile_picture'])) {
            $profilePath = ROOTPATH . 'public/Uploads/students/' . $student['profile_picture'];
            if (file_exists($profilePath)) {
                unlink($profilePath);
            }
        }
        
        // Delete student record
        $this->db->table('students')->where('id', $id)->delete();
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Student deleted successfully.',
            'redirect' => site_url('backend/admin/students')
        ]);
    }
}