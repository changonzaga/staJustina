<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', static function () {
    return redirect()->to('landing-page');
});

// Add this route to serve the landing page view
$routes->get('landing-page', function() {
    return view('landing-page');
});

//db connection test
$routes->get('test_db', 'TestController::dbConnection');

$routes->group('admin', static function ($routes){

    $routes->group('', ['filter'=>'cifilter:auth'], static function ($routes){
        // Admin dashboard and core routes
        $routes->get('home', 'AdminController::index', ['as' => 'admin.home']);
        $routes->get('logout', 'AdminController::logoutHandler', ['as' => 'admin.logout']);
        $routes->get('profile', 'AdminController::profile', ['as' => 'admin.profile']);
        $routes->get('teacher', 'AdminController::teacher', ['as' => 'admin.teacher']);
        $routes->get('parent', 'AdminController::parent', ['as' => 'admin.parent']);
        $routes->get('announcement', 'AdminController::announcement', ['as' => 'admin.announcement']);
        $routes->get('event', 'AdminController::event', ['as' => 'admin.event']); 
        $routes->get('users', 'AdminController::users', ['as' => 'admin.users']);
        $routes->get('student', 'AdminController::student', ['as' => 'admin.student']);
        
        // Settings routes
        $routes->get('settings/general', function() {
            return view('backend/admin/settings/general-settings', ['pageTitle' => 'General Settings']);
        }, ['as' => 'admin.settings.general']);

        // Students Management Routes - Fixed and consolidated
        $routes->group('students', ['namespace' => 'App\Controllers\Backend\Pages'], function($routes) {
            $routes->get('/', 'StudentsController::index', ['as' => 'admin.students.index']);
            $routes->get('create', 'StudentsController::create', ['as' => 'admin.students.create']);
            $routes->post('store', 'StudentsController::store', ['as' => 'admin.students.store']);
            $routes->get('edit/(:num)', 'StudentsController::edit/$1', ['as' => 'admin.students.edit']);
            $routes->post('update/(:num)', 'StudentsController::update/$1', ['as' => 'admin.students.update']);
            $routes->post('delete/(:num)', 'StudentsController::delete/$1', ['as' => 'admin.students.delete']);
            $routes->get('profile/(:num)', 'StudentsController::profile/$1', ['as' => 'admin.students.profile']);
            $routes->get('attendance/(:num)', 'StudentsController::attendance/$1', ['as' => 'admin.students.attendance']);
            $routes->get('grades/(:num)', 'StudentsController::grades/$1', ['as' => 'admin.students.grades']);
            $routes->get('parent/(:num)', 'StudentsController::parent/$1', ['as' => 'admin.students.parent']);
            $routes->get('export_pdf', 'StudentsController::exportPdf', ['as' => 'admin.students.export_pdf']);
            $routes->get('get_by_section/(:segment)', 'StudentsController::getBySection/$1', ['as' => 'admin.students.get_by_section']);
            $routes->post('bulk_delete', 'StudentsController::bulkDelete', ['as' => 'admin.students.bulk_delete']);
        });
        
        // Student routes with enhanced view
        $routes->get('student/profile/(:num)', 'Backend\\StudentController::profile/$1', ['as' => 'admin.student.profile']);
        $routes->get('student/create', 'Backend\\Pages\\StudentsController::create', ['as' => 'admin.student.create']);
        $routes->post('student/store', 'Backend\\Pages\\StudentsController::store', ['as' => 'admin.student.store']);
        $routes->get('student/edit/(:num)', 'Backend\\Pages\\StudentsController::edit/$1', ['as' => 'admin.student.edit']);
        $routes->post('student/update/(:num)', 'Backend\\Pages\\StudentsController::update/$1', ['as' => 'admin.student.update']);
        $routes->get('student/delete/(:num)', 'Backend\\Pages\\StudentsController::delete/$1', ['as' => 'admin.student.delete']);
        $routes->get('student/attendance/(:num)', 'Backend\\Pages\\StudentsController::attendance/$1', ['as' => 'admin.student.attendance']);
        $routes->get('student/grades/(:num)', 'Backend\\Pages\\StudentsController::grades/$1', ['as' => 'admin.student.grades']);
        $routes->get('student/parent/(:num)', 'Backend\\Pages\\StudentsController::parent/$1', ['as' => 'admin.student.parent']);
        
        // Teacher routes
        $routes->get('teacher/create', 'TeacherController::create', ['as' => 'admin.teacher.create']);
        $routes->post('teacher/store', 'TeacherController::store', ['as' => 'admin.teacher.store']);
        $routes->get('teacher/edit/(:num)', 'TeacherController::edit/$1', ['as' => 'admin.teacher.edit']);
        $routes->post('teacher/update/(:num)', 'TeacherController::update/$1', ['as' => 'admin.teacher.update']);
        $routes->post('teacher/delete/(:num)', 'TeacherController::delete/$1', ['as' => 'admin.teacher.delete']);
        $routes->get('teacher/view/(:num)', 'TeacherController::view/$1', ['as' => 'admin.teacher.view']);
        
        // Parent routes
        $routes->get('parent/create', 'ParentController::create', ['as' => 'admin.parent.create']);
        $routes->post('parent/store', 'ParentController::store', ['as' => 'admin.parent.store']);
        $routes->get('parent/edit/(:num)', 'ParentController::edit/$1', ['as' => 'admin.parent.edit']);
        $routes->post('parent/update/(:num)', 'ParentController::update/$1', ['as' => 'admin.parent.update']);
        $routes->post('parent/delete/(:num)', 'ParentController::delete/$1', ['as' => 'admin.parent.delete']);
        $routes->get('parent/view/(:num)', 'ParentController::view/$1', ['as' => 'admin.parent.view']);
    });
    
    $routes->group('', ['filter'=>'cifilter:guest'], static function ($routes){
        $routes->get('login', 'AuthController::loginForm', ['as' => 'admin.login.form']);
        $routes->post('login', 'AuthController::loginHandler', ['as' => 'admin.login.handler']);
        $routes->get('forgot-password', 'AuthController::forgotForm', ['as' => 'admin.forgot.form']);
        $routes->post('send-password-reset-link', 'AuthController::sendPasswordResetLink', ['as' => 'send_password_reset_link']);
        $routes->get('password/reset/(:any)', 'AuthController::resetPassword/$1', ['as' => 'admin.reset-password']);
    });
});

// Centralized login for students and teachers
$routes->get('login', function () {
    return view('login');
});

// Authentication routes
$routes->group('auth', function($routes) {
    $routes->post('student-login', 'AuthController::studentLogin');
    $routes->post('teacher-login', 'AuthController::teacherLogin');
});

// Teacher routes
$routes->group('teacher', function($routes) {
    $routes->get('login', 'Teacher::login');
    $routes->post('login', 'Teacher::loginHandler');
    $routes->get('logout', 'Teacher::logout');
    $routes->get('dashboard', 'Teacher::dashboard'); // Teacher dashboard/home
    $routes->get('home', 'Teacher::dashboard'); // Alternative route for home
    $routes->get('classroom', 'Teacher::classroom');
    $routes->get('exams', 'Teacher::examSchedule');
    $routes->get('exam-schedule', 'Teacher::examSchedule'); // Alternative route
    $routes->get('announcements', 'Teacher::announcements');
    $routes->get('events', 'Teacher::events');
    $routes->get('profile', 'Teacher::profile');

    // Teacher Submenu Routes for Manage Students
    $routes->get('manage-students/attendance-records', 'Teacher::attendanceRecords');
    $routes->get('manage-students/attendance', 'Teacher::attendanceRecords'); // Alternative route
    $routes->get('manage-students/report-cards', 'Teacher::reportCards');
    $routes->get('manage-students/grades', 'Teacher::reportCards'); // Alternative route
});

// Student routes (public-facing)
$routes->group('student', function($routes) {
    $routes->get('login', 'Student::login', ['as' => 'student.login']);
    $routes->post('login', 'Student::loginHandler');
    $routes->get('dashboard', 'Student::dashboard');
    $routes->get('logout', 'Student::logoutHandler', ['as' => 'student.logout']);
    
    // Classes submenu routes
    $routes->get('classes/timetable', 'Student::classTimetable');
    $routes->get('classes/materials', 'Student::classMaterials');
    $routes->get('classes/report-card', 'Student::reportCard');
    $routes->get('classes/attendance', 'Student::classAttendance');
    
    // Announcements, Events, Profile, and Exams routes
    $routes->get('announcements', 'Student::announcements');
    $routes->get('events', 'Student::events');
    $routes->get('profile', 'Student::profile');
    $routes->get('exams', 'Student::exams');
});

// Debug route removed - now using proper controller routing

// Backend Admin Routes (if you need backend/admin URLs)
$routes->group('backend/admin', ['filter'=>'cifilter:auth'], static function ($routes){
    // Students Management Routes for backend/admin path
    $routes->group('students', ['namespace' => 'App\Controllers\Backend\Pages'], function($routes) {
        $routes->get('/', 'StudentsController::index');
        $routes->get('create', 'StudentsController::create');
        $routes->post('store', 'StudentsController::store');
        $routes->get('edit/(:num)', 'StudentsController::edit/$1');
        $routes->post('update/(:num)', 'StudentsController::update/$1');
        $routes->post('delete/(:num)', 'StudentsController::delete/$1');
        $routes->get('profile/(:num)', 'StudentsController::profile/$1');
        $routes->get('attendance/(:num)', 'StudentsController::attendance/$1');
        $routes->get('grades/(:num)', 'StudentsController::grades/$1');
        $routes->get('parent/(:num)', 'StudentsController::parent/$1');
        $routes->get('export_pdf', 'StudentsController::exportPdf');
        $routes->get('get_by_section/(:segment)', 'StudentsController::getBySection/$1');
        $routes->post('bulk_delete', 'StudentsController::bulkDelete');
    });
});

// Public Students Routes (if needed for API or public access)
$routes->group('students', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'StudentController::index');
    $routes->get('view/(:num)', 'StudentController::view/$1');
});