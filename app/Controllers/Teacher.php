<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use App\Models\AttendanceModel;

class Teacher extends BaseController
{
    protected $studentModel;
    protected $teacherModel;
    protected $attendanceModel;
    
    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->teacherModel = new TeacherModel();
        $this->attendanceModel = new AttendanceModel();
    }
    
    public function dashboard()
    {
        $data = [
            'pageTitle' => 'Teacher Dashboard'
        ];
        return view('backend/teacher/dashboard/home', $data);
    }
    
    public function classroom()
    {
        $data = [
            'pageTitle' => 'Classroom'
        ];
        return view('backend/teacher/classroom/classroom', $data);
    }
    
    public function examSchedule()
    {
        $data = [
            'pageTitle' => 'Exam Schedule'
        ];
        return view('backend/teacher/exams/exam-schedule', $data);
    }
    
    public function announcements()
    {
        $data = [
            'pageTitle' => 'Announcements'
        ];
        return view('backend/teacher/announcements/announcement', $data);
    }
    
    public function events()
    {
        $data = [
            'pageTitle' => 'Events'
        ];
        return view('backend/teacher/events/event', $data);
    }
    
    public function attendanceRecords()
    {
        $data = [
            'pageTitle' => 'Attendance Records'
        ];
        return view('backend/teacher/manage-students/attendance-records', $data);
    }
    
    public function reportCards()
    {
        $data = [
            'pageTitle' => 'Report Cards'
        ];
        return view('backend/teacher/manage-students/report-cards', $data);
    }
    
    public function profile()
    {
        $data = [
            'pageTitle' => 'Teacher Profile'
        ];
        return view('backend/teacher/profile/profile', $data);
    }
    
    public function login()
    {
        return view('login');
    }
    
    public function loginHandler()
    {
        // Teacher login logic would go here
        // For now, redirect to dashboard
        return redirect()->to('teacher/dashboard');
    }
    
    public function logout()
    {
        // Teacher logout logic would go here
        return redirect()->to('teacher/login');
    }
}