<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Libraries\CIAuth;

class Student extends BaseController
{
    public function login()
    {
        return view('login');
    }
    
    public function loginHandler()
    {
        // This method is empty as we've removed functionality
        // In a real application, this would handle the login process
    }
    
    public function dashboard()
    {
        // Load the student dashboard view
        return view('backend/student/dashboard/home');
    }
    
    public function classTimetable()
    {
        // Load the class timetable view
        return view('backend/student/classes/timetable');
    }
    
    public function classMaterials()
    {
        // Load the class materials view
        return view('backend/student/classes/materials');
    }
    
    public function reportCard()
    {
        // Load the report card view
        return view('backend/student/classes/report-card');
    }
    
    public function classAttendance()
    {
        // Load the class attendance view
        return view('backend/student/classes/attendance');
    }
    
    public function announcements()
    {
        // Load the announcements view
        return view('backend/student/announcements/announcement');
    }
    
    public function events()
    {
        // Load the events view
        return view('backend/student/events/event');
    }
    
    public function profile()
    {
        // Load the profile view
        return view('backend/student/profile/profile');
    }
    
    public function exams()
    {
        // Load the exam schedule view
        return view('backend/student/exams/exam-schedule');
    }
    
    public function logoutHandler()
    {
        // Use the same CIAuth library that AdminController uses
        CIAuth::forget();
        return redirect()->route('student.login')->with('fail', 'You are logged out');
    }
}