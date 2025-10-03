<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Libraries\CIAuth;
use App\Libraries\Hash;
use App\Models\User;
use App\Models\OAuthLog;
use App\Models\PasswordResetToken;
use App\Helpers\GoogleOAuth;
use Carbon\Carbon;

class AuthController extends BaseController
{
    protected $helpers = ['url', 'form', 'CIMail'];
    protected $googleOAuth;
    protected $oauthLog;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->googleOAuth = new GoogleOAuth();
        $this->oauthLog = new OAuthLog();
    }

    public function loginForm()
    {
        $data = [
            'pageTitle' => 'Login',
            'validation' => null
        ];
        return view('backend/pages/auth/login', $data);
    }

    public function loginHandler()
    {
        $fieldType = filter_var($this->request->getVar('login_id'), FILTER_VALIDATE_EMAIL) ? 'email' : 'account_no';
        $validation = $this->validate([
            'login_id'=>[
                'rules'=>'required|min_length[4]|max_length[100]',
                'errors'=>[
                    'required'=>'Email or Account Number is required',
                    'min_length'=>'Minimum length is 4 characters',
                    'max_length'=>'Maximum length is 100 characters'
                ]
            ],
            'password'=>[
                'rules'=>'required|min_length[4]|max_length[45]',
                'errors'=>[
                    'required'=>'Password is required',
                    'min_length'=>'Minimum length is 4 characters',
                    'max_length'=>'Maximum length is 45 characters'
                ]
            ]
        ]);

        if (!$validation) {
            return view('backend/pages/auth/login', [
                'pageTitle'=>'Login',
                'validation' => $this->validator
            ]);
        } else {
            $loginId = $this->request->getVar('login_id');
            $password = $this->request->getVar('password');
            
            // First, try to authenticate as admin/teacher (existing logic)
            $user = new User();
            $userInfo = $user->where($fieldType, $loginId)->first();
            
            if ($userInfo && Hash::check($password, $userInfo['password'])) {
                CIAuth::setCIAuth($userInfo);
                return redirect()->to('/admin/home');
            }
            
            // If admin/teacher authentication fails, try student authentication
            $studentAuth = new \App\Models\StudentAuth();
            $studentInfo = $studentAuth->findByLoginId($loginId);
            
            if (!$studentInfo) {
                return redirect()->route('admin.login.form')->with('fail', 'Invalid login credentials')->withInput();
            }
            
            // Check if account is locked
            if ($studentAuth->isAccountLocked($studentInfo)) {
                return redirect()->route('admin.login.form')->with('fail', 'Account is temporarily locked due to multiple failed login attempts. Please try again later.')->withInput();
            }
            
            // Verify password
            if (!$studentAuth->verifyPassword($password, $studentInfo['password_hash'])) {
                // Increment failed attempts
                $studentAuth->incrementFailedAttempts($studentInfo['id']);
                return redirect()->route('admin.login.form')->with('fail', 'Invalid login credentials')->withInput();
            }
            
            // Update last login
            $studentAuth->updateLastLogin($studentInfo['id']);
            
            // Get complete student information for session
            $completeStudentInfo = $studentAuth->getStudentWithInfo($studentInfo['id']);
            
            // Create user-like session data for students
            $studentSessionData = [
                'id' => $studentInfo['id'],
                'name' => trim(($completeStudentInfo['first_name'] ?? '') . ' ' . ($completeStudentInfo['last_name'] ?? '')),
                'email' => $studentInfo['email'],
                'account_no' => $studentInfo['account_number'],
                'role' => 'student',
                'student_id' => $studentInfo['student_id'],
                'auth_type' => 'student'
            ];
            
            CIAuth::setCIAuth($studentSessionData);
            
            // Redirect students to a student dashboard (you may need to create this)
            return redirect()->to('/student/dashboard')->with('success', 'Welcome to the student portal!');
        }      
    }

    public function googleLogin()
    {
        return redirect()->to($this->googleOAuth->getAuthUrl());
    }

    public function googleCallback()
    {
        try {
            log_message('debug', 'Starting Google OAuth callback process');
            
            $token = $this->googleOAuth->handleCallback();
            if (!$token) {
                log_message('error', 'Failed to obtain Google OAuth token');
                $this->oauthLog->logAuthAttempt([
                    'email' => 'unknown',
                    'name' => 'unknown',
                    'status' => 'failed',
                    'error_message' => 'Failed to obtain Google OAuth token'
                ]);
                return redirect()->to('admin/login')
                    ->with('fail', 'Google authentication failed');
            }
            log_message('debug', 'Successfully obtained Google OAuth token');

            $userData = $this->googleOAuth->getUserInfo($token);
            if (!$userData) {
                log_message('error', 'Failed to get user information from Google');
                $this->oauthLog->logAuthAttempt([
                    'email' => 'unknown',
                    'name' => 'unknown',
                    'status' => 'failed',
                    'error_message' => 'Failed to get user information from Google'
                ]);
                return redirect()->to('admin/login')
                    ->with('fail', 'Failed to get user information');
            }
            log_message('debug', 'Retrieved user data from Google: ' . json_encode($userData));

            // Check if the email domain is allowed
            log_message('debug', '--------------------');
            log_message('debug', 'Email Domain Authorization Check:');
            log_message('debug', 'Full Email: ' . $userData['email']);
            log_message('debug', 'Domain: ' . substr(strrchr($userData['email'], "@"), 1));
            
            if (!$this->googleOAuth->isAllowedDomain($userData['email'])) {
                $domain = substr(strrchr($userData['email'], "@"), 1);
                log_message('warning', 'Unauthorized email domain: ' . $domain);
                $this->oauthLog->logAuthAttempt([
                    'email' => $userData['email'],
                    'name' => $userData['name'],
                    'picture' => $userData['picture'],
                    'status' => 'failed',
                    'error_message' => 'Unauthorized email domain: ' . $domain
                ]);
                return redirect()->to('admin/login')
                    ->with('fail', 'Your email domain is not authorized. Please use your institutional email address ending with cspc.edu.ph or stajustina.edu.ph (subdomains like my.cspc.edu.ph are also accepted).');
            }

            // Check if user exists in database
            $user = new User();
            $existingUser = $user->where('email', $userData['email'])->first();

            if (!$existingUser) {
                // Create new user
                $userId = $user->insert([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'account_no' => explode('@', $userData['email'])[0], // Use email prefix as account number
                    'picture' => $userData['picture'],
                    'auth_type' => 'google',
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                $userInfo = $user->find($userId);
            } else {
                // Update existing user's Google-specific information
                $user->update($existingUser['id'], [
                    'picture' => $userData['picture'],
                    'auth_type' => 'google',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                $userInfo = $existingUser;
            }

            // Log successful authentication
            $this->oauthLog->logAuthAttempt([
                'user_id' => $userInfo['id'],
                'email' => $userData['email'],
                'name' => $userData['name'],
                'picture' => $userData['picture'],
                'status' => 'success'
            ]);

            // Set auth session
            CIAuth::setCIAuth($userInfo);
            return redirect()->to('/admin/home')
                ->with('success', 'Successfully logged in with Google');

        } catch (\Exception $e) {
            log_message('error', 'Google OAuth error: ' . $e->getMessage());
            $this->oauthLog->logAuthAttempt([
                'email' => isset($userData['email']) ? $userData['email'] : 'unknown',
                'name' => isset($userData['name']) ? $userData['name'] : 'unknown',
                'status' => 'failed',
                'error_message' => 'Google OAuth error: ' . $e->getMessage()
            ]);
            return redirect()->to('admin/login')
                ->with('fail', 'An error occurred during Google authentication');
        }
    }
    
    /**
     * Handle Google OAuth callback for teachers
     */
    public function googleTeacherCallback()
    {
        try {
            $googleOAuth = new GoogleOAuth();
            $userData = $googleOAuth->handleCallback();
            
            if (!$userData) {
                return redirect()->to('/login')
                    ->with('fail', 'Failed to authenticate with Google');
            }
            
            $user = new User();
            $teacherModel = new \App\Models\TeacherModel();
            $teacherAuthModel = new \App\Models\TeacherAuth();
            
            // Check if teacher authentication exists
            $teacherAuth = $teacherAuthModel->findByEmail($userData['email']);
            
            if ($teacherAuth) {
                // Existing teacher - log them in
                $teacher = $teacherModel->find($teacherAuth['teacher_id']);
                
                // Update teacher profile picture if needed
                if ($teacher && empty($teacher['profile_picture'])) {
                    $teacherModel->update($teacher['id'], [
                        'profile_picture' => $userData['picture']
                    ]);
                }
                
                // Update authentication record
                $teacherAuthModel->update($teacherAuth['id'], [
                    'auth_type' => 'google',
                    'last_login_at' => date('Y-m-d H:i:s')
                ]);
                
                // Get user info
                $userInfo = $user->where('email', $userData['email'])
                                ->where('role', 'teacher')
                                ->first();
                
                if ($userInfo) {
                    $user->update($userInfo['id'], [
                        'picture' => $userData['picture'],
                        'auth_type' => 'google',
                        'last_login_at' => date('Y-m-d H:i:s')
                    ]);
                } else {
                    // Create user record if it doesn't exist
                    $teacherModel->syncWithUsers($teacher['id'], $userData['email'], null, 'google');
                    $userInfo = $user->where('email', $userData['email'])->first();
                }
            } else {
                // New teacher - auto-register
                $teacherId = $teacherModel->createFromGoogleAuth($userData);
                
                if (!$teacherId) {
                    throw new \Exception('Failed to create teacher record');
                }
                
                // Sync with users table
                $teacherModel->syncWithUsers($teacherId, $userData['email'], null, 'google');
                
                // Get the created user info
                $userInfo = $user->where('email', $userData['email'])->first();
                
                if (!$userInfo) {
                    throw new \Exception('Failed to create user record');
                }
            }
            
            // Log successful authentication
            $this->oauthLog->logAuthAttempt([
                'user_id' => $userInfo['id'],
                'email' => $userData['email'],
                'name' => $userData['name'],
                'picture' => $userData['picture'],
                'status' => 'success'
            ]);
            
            // Set auth session
            CIAuth::setCIAuth($userInfo);
            
            // Check if profile is complete
            $teacher = $teacherModel->getByEmail($userData['email']);
            if ($teacher && !$teacherModel->isProfileComplete($teacher['id'])) {
                return redirect()->to('/teacher/profile/complete')
                    ->with('info', 'Please complete your profile to access all features');
            }
            
            return redirect()->to('/teacher/dashboard')
                ->with('success', 'Successfully logged in with Google');
                
        } catch (\Exception $e) {
            log_message('error', 'Google Teacher OAuth error: ' . $e->getMessage());
            $this->oauthLog->logAuthAttempt([
                'email' => isset($userData['email']) ? $userData['email'] : 'unknown',
                'name' => isset($userData['name']) ? $userData['name'] : 'unknown',
                'status' => 'failed',
                'error_message' => 'Google Teacher OAuth error: ' . $e->getMessage()
            ]);
            return redirect()->to('/login')
                ->with('fail', 'An error occurred during Google authentication');
        }
    }
    
    /**
     * Handle teacher login with email/password
     */
    public function teacherLogin()
    {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ]);
        
        if (!$validation->withRequest($request)->run()) {
            return redirect()->back()
                ->withInput()
                ->with('fail', 'Please provide valid email and password');
        }
        
        $email = $request->getPost('email');
        $password = $request->getPost('password');
        
        $user = new User();
        $teacherModel = new \App\Models\TeacherModel();
        $teacherAuthModel = new \App\Models\TeacherAuth();
        
        // Check if teacher authentication exists
        $teacherAuth = $teacherAuthModel->findByEmail($email);
        
        if (!$teacherAuth) {
            return redirect()->back()
                ->withInput()
                ->with('fail', 'Invalid email or password');
        }
        
        // Verify password
        if (!$teacherAuth['password'] || !$teacherAuthModel->verifyPassword($password, $teacherAuth['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('fail', 'Invalid email or password');
        }
        
        // Get user info
        $userInfo = $user->where('email', $email)
                        ->where('role', 'teacher')
                        ->where('status', 'active')
                        ->first();
        
        if (!$userInfo) {
            return redirect()->back()
                ->withInput()
                ->with('fail', 'Account not found or inactive');
        }
        
        // Update last login in both tables
        $user->update($userInfo['id'], [
            'last_login_at' => date('Y-m-d H:i:s')
        ]);
        
        $teacherAuthModel->updateLastLogin($teacherAuth['id']);
        
        // Set auth session
        CIAuth::setCIAuth($userInfo);
        
        // Check if profile is complete
        $teacher = $teacherModel->find($teacherAuth['teacher_id']);
        if ($teacher && !$teacherModel->isProfileComplete($teacher['id'])) {
            return redirect()->to('/teacher/profile/complete')
                ->with('info', 'Please complete your profile to access all features');
        }
        
        return redirect()->to('/teacher/dashboard')
            ->with('success', 'Welcome back!');
    }
    
    /**
     * Handle teacher logout
     */
    public function teacherLogout()
    {
        CIAuth::forget();
        return redirect()->to('/login')
            ->with('success', 'You have been logged out successfully');
    }
}
