<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\StudentModel;

class StudentController extends BaseController
{
    public function index()
    {
        return view('backend/admin/students/index');
    }
    
    public function profile($id = null)
    {
        if (!$id) {
            return redirect()->to('admin/student')->with('error', 'Student ID is required.');
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
            return redirect()->to('admin/student')->with('error', 'Student not found.');
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
}
