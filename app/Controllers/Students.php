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
            'lrn', 'name', 'gender', 'age', 'grade_level', 'section',
            'address', 'guardian', 'contact', 'teacher_id', 'parent_id'
        ]);
        $file = $this->request->getFile('profile_picture');

        if ($file && $file->isValid()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/Uploads/students', $newName);
            $data['profile_picture'] = $newName;
        }

        // Validate data (e.g., unique LRN)
        if ($this->db->table('student')->where('lrn', $data['lrn'])->countAllResults() > 0) {
            return redirect()->back()->withInput()->with('error', 'LRN already exists.');
        }

        $this->db->table('student')->insert($data);
        return redirect()->to('backend/admin/students')->with('success', 'Student added successfully.');
    }

    public function edit($id)
    {
        $student = $this->db->table('student')->where('id', $id)->get()->getRowArray();
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
            'lrn', 'name', 'gender', 'age', 'grade_level', 'section',
            'address', 'guardian', 'contact', 'teacher_id', 'parent_id'
        ]);
        $file = $this->request->getFile('profile_picture');

        if ($file && $file->isValid()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/Uploads/students', $newName);
            $data['profile_picture'] = $newName;
        }

        // Validate LRN uniqueness (excluding current student)
        $existing = $this->db->table('student')->where('lrn', $data['lrn'])->where('id !=', $id)->countAllResults();
        if ($existing > 0) {
            return redirect()->back()->withInput()->with('error', 'LRN already exists.');
        }

        $this->db->table('student')->where('id', $id)->update($data);
        return redirect()->to('backend/admin/students')->with('success', 'Student updated successfully.');
    }

    public function profile($id)
    {
        $student = $this->db->table('student s')
            ->select('s.*, t.name as teacher_name, p.name as parent_name')
            ->join('teacher t', 's.teacher_id = t.id', 'left')
            ->join('parent p', 's.parent_id = p.id', 'left')
            ->where('s.id', $id)
            ->get()->getRowArray();

        if (!$student) {
            return redirect()->to('backend/admin/students')->with('error', 'Student not found.');
        }

        return view('backend/admin/students/view', ['student' => $student]);
    }
    
    public function delete($id)
    {
        // Check if student exists
        $student = $this->db->table('student')->where('id', $id)->get()->getRowArray();
        
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
        $this->db->table('student')->where('id', $id)->delete();
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Student deleted successfully.',
            'redirect' => site_url('backend/admin/students')
        ]);
    }
}