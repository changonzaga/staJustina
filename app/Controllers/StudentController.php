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
        
        // Use StudentModel to fetch normalized complete profile
        $model = new \App\Models\StudentModel();
        $student = $model->getStudentCompleteProfile($id);
        
        if (!$student) {
            return redirect()->to('admin/student')->with('error', 'Student not found.');
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
}
