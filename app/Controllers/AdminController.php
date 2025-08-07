<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\CIAuth;
use App\Models\StudentModel;
class AdminController extends BaseController
{
    public function index()
    {
        $data = [
            'pageTitle' => 'Dashboard',
        ];
        return view('backend/admin/dashboard/home', $data);
    }

    public function logoutHandler(){
        CIAuth::forget();
        return redirect()->route('admin.login.form')->with('fail', 'You are logged out');

    }

    public function profile(){
        $data = array(
            'pageTitle' => 'Profile'
        );
        return view('backend/admin/profile/profile', $data);
    }
    public function student(){
        $model = new StudentModel();
        $data['students'] = $model->getStudentsWithRelations();
        $data['sections'] = $model->distinct()
                               ->select('section')
                               ->orderBy('section', 'asc')
                               ->findColumn('section');
        $data['grade_levels'] = $model->distinct()
                                  ->select('grade_level')
                                  ->orderBy('grade_level', 'asc')
                                  ->findColumn('grade_level');

        return view('backend/admin/students/student', $data);
    }
    public function teacher(){
        $teacherModel = new \App\Models\TeacherModel();
        $data = array(
            'pageTitle' => 'Teacher',
            'teachers' => $teacherModel->findAll()
        );
        return view('backend/admin/teachers/teacher', $data);
    }
    public function parent(){
        $parentModel = new \App\Models\ParentModel();
        $data = array(
            'pageTitle' => 'Parent',
            'parents' => $parentModel->findAll()
        );
        return view('backend/admin/parents/parent', $data);
    }
    public function event(){
        $data = array(
            'pageTitle' => 'Event'
        );
        return view('backend/admin/events/event', $data);
    }
    public function users(){
        $data = array(
            'pageTitle' => 'Users'
        );
        return view('backend/admin/users/users', $data);
    }
    public function announcement(){
        $data = array(
            'pageTitle' => 'Announcement'
        );
        return view('backend/admin/announcements/announcement', $data);
    }
    
}
