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

// Debug test routes
$routes->get('debug/test-approval', 'DebugController::testApproval');

// Email workflow test routes
$routes->get('test-email-workflow', 'TestController::testEmailWorkflow');
$routes->get('simulate-approval', 'TestController::simulateApproval');

// Email test route
$routes->get('send-test-email', 'MailTest::index');
$routes->get('simple-email-test', 'SimpleEmailTestController::index');
$routes->get('test-enrollment-template', 'EmailTemplateTestController::testEnrollmentTemplate');
$routes->get('test-enrollment-email/(:num)', 'EmailTestController::testEnrollmentEmail/$1');
$routes->get('test-enrollment-email', 'EmailTestController::testEnrollmentEmail');

// Public enrollment routes
$routes->get('enrollment', 'EnrollmentController::index', ['as' => 'public.enrollment']);
$routes->get('enrollment/manual', 'EnrollmentController::manual', ['as' => 'public.enrollment.manual']);
$routes->get('enrollment/ocr', 'EnrollmentController::ocr', ['as' => 'public.enrollment.ocr']);
$routes->get('enrollment/test', 'EnrollmentController::test', ['as' => 'enrollment.test']);
$routes->post('enrollment/store', 'EnrollmentController::store', ['as' => 'enrollment.store']);
$routes->post('enrollment/store-ocr', 'EnrollmentController::storeOcr', ['as' => 'enrollment.store.ocr']);
$routes->get('enrollment/status/(:segment)', 'EnrollmentController::status/$1', ['as' => 'enrollment.status']);

// File serving route for uploads with proper content type detection
$routes->get('uploads/(.+)', 'FileController::serve/$1', ['as' => 'file.serve']);

// Temporary: Admin enrollment details route without auth for testing
$routes->get('admin/enrollment/details/(:num)', 'AdminController::getEnrollmentDetails/$1', ['as' => 'admin.enrollment.details.test']);

$routes->group('admin', static function ($routes){

    $routes->group('', ['filter'=>'cifilter:auth'], static function ($routes){
        // Admin dashboard and core routes
        $routes->get('home', 'AdminController::index', ['as' => 'admin.home']);
        $routes->get('logout', 'AdminController::logoutHandler', ['as' => 'admin.logout']);
        $routes->get('profile', 'AdminController::profile', ['as' => 'admin.profile']);
        $routes->get('teacher', 'AdminController::teacher', ['as' => 'admin.teacher']);
        $routes->get('parent', 'AdminController::parent', ['as' => 'admin.parent']);
        $routes->get('announcement', 'AdminController::announcement', ['as' => 'admin.announcement']);
        $routes->get('announcements', 'AdminController::announcement', ['as' => 'admin.announcements']);
        $routes->get('announcements/create-announcement', 'AdminController::createAnnouncement', ['as' => 'admin.announcements.create']);
        $routes->get('announcements/history', 'AdminController::announcementHistory', ['as' => 'admin.announcements.history']);
        // Announcement processing routes
        $routes->post('processAnnouncement', 'AdminController::processAnnouncement', ['as' => 'admin.processAnnouncement']);
        $routes->get('getAnnouncements', 'AdminController::getAnnouncements', ['as' => 'admin.getAnnouncements']);
        $routes->get('event', 'AdminController::event', ['as' => 'admin.event']); 
        $routes->get('users', 'AdminController::users', ['as' => 'admin.users']);
        $routes->get('student', 'AdminController::student', ['as' => 'admin.student']);
        
        // OAuth Logs routes
        $routes->get('oauth/logs', 'OAuthController::logs', ['as' => 'admin.oauth.logs']);
        $routes->post('oauth/logs/clear', 'OAuthController::clearLogs', ['as' => 'admin.oauth.logs.clear']);
        $routes->get('oauth/logs/failed', 'OAuthController::getFailedAttempts', ['as' => 'admin.oauth.logs.failed']);
        $routes->get('oauth/logs/by-email', 'OAuthController::getAttemptsByEmail', ['as' => 'admin.oauth.logs.by-email']);
        
        // Enrollment routes
        $routes->get('enrollment', 'AdminController::enrollment', ['as' => 'admin.enrollment']);
        // $routes->get('enrollment/details/(:num)', 'AdminController::getEnrollmentDetails/$1', ['as' => 'admin.enrollment.details']);
        $routes->post('enrollment/approve/(:num)', 'AdminController::approveEnrollment/$1', ['as' => 'admin.enrollment.approve']);
        $routes->post('enrollment/decline/(:num)', 'AdminController::declineEnrollment/$1', ['as' => 'admin.enrollment.decline']);
        $routes->get('enrollment/manage', 'EnrollmentController::manage', ['as' => 'admin.enrollment.manage']);
        $routes->get('enrollment/reports', 'EnrollmentController::reports', ['as' => 'admin.enrollment.reports']);
        
        // Subjects routes
        $routes->get('subjects', 'AdminController::subjects', ['as' => 'admin.subjects']);
        $routes->get('subjects/get/(:num)', 'AdminController::getSubject/$1', ['as' => 'admin.subjects.get']);
        $routes->post('subjects/store', 'AdminController::storeSubject', ['as' => 'admin.subjects.store']);
        $routes->post('subjects/update/(:num)', 'AdminController::updateSubject/$1', ['as' => 'admin.subjects.update']);
        $routes->post('subjects/delete/(:num)', 'AdminController::deleteSubject/$1', ['as' => 'admin.subjects.delete']);
        
        // Department routes
        $routes->get('department', 'DepartmentController::index', ['as' => 'admin.department']);
        $routes->get('department/teachers', 'DepartmentController::getTeachers', ['as' => 'admin.department.teachers']);
        $routes->post('department/store', 'DepartmentController::store', ['as' => 'admin.department.store']);
        $routes->get('department/show/(:num)', 'DepartmentController::show/$1', ['as' => 'admin.department.show']);
        $routes->post('department/update/(:num)', 'DepartmentController::update/$1', ['as' => 'admin.department.update']);
        $routes->post('department/delete/(:num)', 'DepartmentController::delete/$1', ['as' => 'admin.department.delete']);
        
        // Section routes
        $routes->get('section', 'SectionController::index', ['as' => 'admin.section']);
        $routes->get('section/get-sections', 'SectionController::getSections');
        $routes->get('section/get/(:num)', 'SectionController::getSection/$1');
        $routes->post('section/store', 'SectionController::store');
        $routes->post('section/update/(:num)', 'SectionController::update/$1');
        $routes->post('section/delete/(:num)', 'SectionController::delete/$1');
        $routes->get('section/get-dropdowns', 'SectionController::getDropdowns');
        
        // Class routes
        $routes->get('classes', 'ClassController::index', ['as' => 'admin.classes']);
        $routes->get('class', 'ClassController::index', ['as' => 'admin.class']); // Alias for navigation
        
        // AJAX endpoints
        $routes->get('class/get-classes', 'ClassController::getClasses');
        $routes->get('class/get/(:num)', 'ClassController::getClass/$1');
        $routes->post('class/store', 'ClassController::store');
        $routes->post('class/update/(:num)', 'ClassController::update/$1');
        $routes->post('class/delete/(:num)', 'ClassController::delete/$1');
        $routes->get('class/students/(:num)', 'ClassController::getStudents/$1');
        $routes->get('class/get-dropdowns', 'ClassController::getDropdowns');
        
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
        $routes->get('student/profile/(:num)', 'Backend\StudentController::profile/$1', ['as' => 'admin.student.profile']);
        $routes->get('student/create', 'Backend\Pages\StudentsController::create', ['as' => 'admin.student.create']);
        $routes->post('student/store', 'Backend\Pages\StudentsController::store', ['as' => 'admin.student.store']);
        $routes->get('student/edit/(:num)', 'Backend\Pages\StudentsController::edit/$1', ['as' => 'admin.student.edit']);
        $routes->post('student/update/(:num)', 'Backend\Pages\StudentsController::update/$1', ['as' => 'admin.student.update']);
        // $routes->get('student/delete/(:num)', 'Backend\Pages\StudentsController::delete/$1', ['as' => 'admin.student.delete']); // removed: unsafe GET delete route
        $routes->get('student/attendance/(:num)', 'Backend\Pages\StudentsController::attendance/$1', ['as' => 'admin.student.attendance']);
        $routes->get('student/grades/(:num)', 'Backend\Pages\StudentsController::grades/$1', ['as' => 'admin.student.grades']);
        $routes->get('student/parent/(:num)', 'Backend\Pages\StudentsController::parent/$1', ['as' => 'admin.student.parent']);
        
        // Teacher routes
        $routes->get('teacher', 'TeacherController::index', ['as' => 'admin.teacher.index']);
        $routes->get('teacher/create', 'TeacherController::create', ['as' => 'admin.teacher.create']);
        $routes->post('teacher/store', 'TeacherController::store', ['as' => 'admin.teacher.store']);
        $routes->get('teacher/edit/(:num)', 'TeacherController::edit/$1', ['as' => 'admin.teacher.edit']);
        $routes->post('teacher/update/(:num)', 'TeacherController::update/$1', ['as' => 'admin.teacher.update']);
        $routes->post('teacher/delete/(:num)', 'TeacherController::delete/$1', ['as' => 'admin.teacher.delete']);
        $routes->get('teacher/show/(:num)', 'TeacherController::show/$1', ['as' => 'admin.teacher.show']);
        
        // Parent routes
        $routes->get('parent', 'ParentController::index', ['as' => 'admin.parent.index']);
        $routes->get('parent/create', 'ParentController::create', ['as' => 'admin.parent.create']);
        $routes->post('parent/store', 'ParentController::store', ['as' => 'admin.parent.store']);
        $routes->get('parent/edit/(:num)', 'ParentController::edit/$1', ['as' => 'admin.parent.edit']);
        $routes->post('parent/update/(:num)', 'ParentController::update/$1', ['as' => 'admin.parent.update']);
        $routes->post('parent/delete/(:num)', 'ParentController::delete/$1', ['as' => 'admin.parent.delete']);
        $routes->get('parent/view/(:num)', 'ParentController::view/$1', ['as' => 'admin.parent.view']);
        $routes->get('parent/data/(:num)', 'ParentController::data/$1', ['as' => 'admin.parent.data']);
    });
    
    // Legacy admin routes - redirect to centralized login
    $routes->group('', ['filter'=>'cifilter:guest'], static function ($routes){
        $routes->get('login', function() { return redirect()->to('/login'); });
        $routes->post('login', function() { return redirect()->to('/login'); });
        $routes->get('forgot-password', function() { return redirect()->to('/forgot-password'); });
        $routes->get('google', 'CentralAuthController::googleLogin');
        $routes->get('google/callback', 'CentralAuthController::googleCallback');
    });
});

// Centralized login for all users (admin, teacher, student)
$routes->get('login', 'CentralAuthController::loginForm', ['as' => 'central.login.form']);
$routes->post('login', 'CentralAuthController::loginHandler', ['as' => 'central.login.handler']);
$routes->get('logout', 'CentralAuthController::logout', ['as' => 'central.logout']);

// Google OAuth routes for centralized authentication
$routes->get('google', 'CentralAuthController::googleLogin', ['as' => 'central.google.login']);
$routes->get('google/callback', 'CentralAuthController::googleCallback', ['as' => 'central.google.callback']);

// Forgot password routes (will be handled by CentralAuthController)
$routes->get('forgot-password', 'CentralAuthController::forgotForm', ['as' => 'central.forgot.form']);
$routes->post('send-password-reset-link', 'CentralAuthController::sendPasswordResetLink', ['as' => 'central.send_password_reset_link']);

// Legacy authentication routes - redirect to centralized system
$routes->group('auth', function($routes) {
    $routes->post('student-login', function() { return redirect()->to('/login'); });
    $routes->post('teacher-login', function() { return redirect()->to('/login'); });
    $routes->get('google-teacher-callback', function() { return redirect()->to('/google/callback'); });
    $routes->post('teacher-logout', function() { return redirect()->to('/logout'); });
});

// Teacher routes
$routes->group('teacher', function($routes) {
    $routes->get('login', 'Teacher::login');
    $routes->post('login', 'Teacher::loginHandler');
    $routes->get('logout', 'Teacher::logout');
    $routes->get('dashboard', 'TeacherController::dashboard'); // Teacher dashboard/home
    $routes->get('home', 'TeacherController::dashboard'); // Alternative route for home
    $routes->get('classroom', 'Teacher::classroom');
    $routes->get('exams', 'Teacher::examSchedule');
    $routes->get('exam-schedule', 'Teacher::examSchedule'); // Alternative route
    $routes->get('announcements', 'Teacher::announcements');
    $routes->get('events', 'Teacher::events');
    $routes->get('profile', 'Teacher::profile');
    
    // Profile completion routes
    $routes->get('profile/complete', 'TeacherController::profileComplete');
    $routes->post('profile/complete', 'TeacherController::profileCompleteHandler');

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

// Backend Admin Routes (if you need backend/admin URLs)
$routes->group('backend/admin', ['filter'=>'cifilter:auth'], static function ($routes){
    // Students Management Routes for backend/admin path
    $routes->group('students', ['namespace' => 'App\Controllers\Backend\Pages'], function($routes) {
        $routes->get('/', 'StudentsController::index');
        $routes->get('create', 'StudentsController::create');
        $routes->post('store', 'StudentsController::store');
        $routes->get('edit/(:num)', 'StudentsController::edit/$1');
        $routes->post('update/(:num)', 'StudentsController::update/$1');
        // Duplicate delete route removed to keep only admin/students/delete
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