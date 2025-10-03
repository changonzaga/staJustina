<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Libraries\CIAuth;
use App\Libraries\Hash;
use App\Models\User;
use App\Models\TeacherAuth;
use App\Models\OAuthLog;
use App\Models\PasswordResetToken;
use App\Helpers\GoogleOAuth;

class CentralAuthController extends BaseController
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

    /**
     * Display centralized login form
     */
    public function loginForm()
    {
        $data = [
            'pageTitle' => 'Login',
            'validation' => null
        ];
        return view('backend/pages/auth/login', $data);
    }

    /**
     * Handle centralized login for both admin and teacher
     */
    public function loginHandler()
    {
        $loginId = $this->request->getVar('login_id');
        $isEmail = filter_var($loginId, FILTER_VALIDATE_EMAIL);
        
        // Determine field type and validate login rules based on credential type
        if ($isEmail) {
            // Email login - only allowed for admins
            $fieldType = 'email';
            $allowedRoles = ['admin'];
        } else {
            // Account number login - allowed for teachers and students
            $fieldType = 'account_no';
            $allowedRoles = ['teacher', 'student'];
        }
        
        $validation = $this->validate([
            'login_id' => [
                'rules' => 'required|min_length[4]|max_length[100]',
                'errors' => [
                    'required' => 'Login credential is required',
                    'min_length' => 'Minimum length is 4 characters',
                    'max_length' => 'Maximum length is 100 characters'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[4]|max_length[45]',
                'errors' => [
                    'required' => 'Password is required',
                    'min_length' => 'Minimum length is 4 characters',
                    'max_length' => 'Maximum length is 45 characters'
                ]
            ]
        ]);

        if (!$validation) {
            return view('backend/pages/auth/login', [
                'pageTitle' => 'Login',
                'validation' => $this->validator
            ]);
        }

        $password = $this->request->getVar('password');
        
        // Try to authenticate user with role-based restrictions
        $userInfo = $this->authenticateUser($loginId, $fieldType, $password, $allowedRoles);
        
        if (!$userInfo) {
            return redirect()->back()
                ->with('fail', 'Invalid credentials or account not found')
                ->withInput();
        }

        // Set authentication session
        CIAuth::setCIAuth($userInfo);
        
        // Redirect based on user role
        return $this->redirectBasedOnRole($userInfo);
    }

    /**
     * Authenticate user from multiple sources - CENTRALIZED through users table with role restrictions
     */
    private function authenticateUser($loginId, $fieldType, $password, $allowedRoles = [])
    {
        log_message('debug', '=== CENTRALIZED AUTHENTICATION DEBUG START ===');
        log_message('debug', 'Login ID: ' . $loginId);
        log_message('debug', 'Field Type: ' . $fieldType);
        log_message('debug', 'Password Length: ' . strlen($password));
        log_message('debug', 'Allowed Roles: ' . implode(', ', $allowedRoles));
        
        $user = new User();
        
        // CENTRALIZED LOGIN: Always check users table first for all roles
        $userInfo = $user->where($fieldType, $loginId)
                         ->where('status', 'active')
                         ->first();
        
        log_message('debug', 'User found in users table: ' . ($userInfo ? 'YES' : 'NO'));
        if ($userInfo) {
            log_message('debug', 'User role: ' . $userInfo['role']);
            log_message('debug', 'User email: ' . $userInfo['email']);
            
            // Check if user role is allowed for this login method
            if (!empty($allowedRoles) && !in_array($userInfo['role'], $allowedRoles)) {
                log_message('debug', 'Role not allowed for this login method. User role: ' . $userInfo['role'] . ', Allowed: ' . implode(', ', $allowedRoles));
                return null;
            }
        }
        
        if ($userInfo) {
            $authenticated = false;
            
            // Handle authentication based on role from users table
            switch ($userInfo['role']) {
                case 'admin':
                    log_message('debug', 'Authenticating admin via users table...');
                    if (Hash::check($password, $userInfo['password'])) {
                        log_message('debug', 'Admin password verified successfully');
                        $authenticated = true;
                    } else {
                        log_message('debug', 'Admin password verification failed');
                    }
                    break;
                    
                case 'teacher':
                    log_message('debug', 'Authenticating teacher via users table...');
                    // For teachers, verify against teacher_auth table but use users table as primary
                    $teacherAuthModel = new TeacherAuth();
                    $teacherAuth = $teacherAuthModel->findByEmail($userInfo['email']);
                    
                    log_message('debug', 'Teacher auth found: ' . ($teacherAuth ? 'YES' : 'NO'));
                    if ($teacherAuth && $teacherAuth['password'] && 
                        $teacherAuthModel->verifyPassword($password, $teacherAuth['password'])) {
                        
                        log_message('debug', 'Teacher password verified successfully');
                        $teacherAuthModel->updateLastLogin($teacherAuth['id']);
                        $authenticated = true;
                    } else {
                        log_message('debug', 'Teacher password verification failed');
                    }
                    break;
                    
                case 'student':
                    log_message('debug', 'Authenticating student via users table...');
                    // For students, verify against student_auth table but use users table as primary
                    $studentAuth = new \App\Models\StudentAuth();
                    
                    // Students must login with account number only, find by account number
                    if ($fieldType !== 'account_no') {
                        log_message('debug', 'Student attempted login with email - not allowed');
                        return null;
                    }
                    
                    $studentInfo = $studentAuth->where('account_number', $loginId)
                                              ->where('is_active', 1)
                                              ->first();
                    
                    log_message('debug', 'Student auth found: ' . ($studentInfo ? 'YES' : 'NO'));
                    if ($studentInfo) {
                        // Check if account is locked
                        if ($studentAuth->isAccountLocked($studentInfo)) {
                            log_message('debug', 'Student account is locked');
                            return null;
                        }
                        
                        // Check if still using temporary password (password_changed_at is NULL)
                        $isTemporaryPassword = is_null($studentInfo['password_changed_at']);
                        log_message('debug', 'Using temporary password: ' . ($isTemporaryPassword ? 'YES' : 'NO'));
                        
                        // Verify password
                        if ($studentAuth->verifyPassword($password, $studentInfo['password_hash'])) {
                            log_message('debug', 'Student password verified successfully');
                            $studentAuth->updateLastLogin($studentInfo['id']);
                            $authenticated = true;
                            
                            // Add student-specific data to user info
                            $userInfo['account_no'] = $studentInfo['account_number'];
                            $userInfo['student_id'] = $studentInfo['student_id'];
                            $userInfo['auth_type'] = 'student';
                            $userInfo['is_temporary_password'] = $isTemporaryPassword;
                            
                            // If using temporary password, add flag for password change requirement
                            if ($isTemporaryPassword) {
                                log_message('debug', 'Student must change temporary password');
                                $userInfo['must_change_password'] = true;
                            }
                        } else {
                            log_message('debug', 'Student password verification failed');
                            $studentAuth->incrementFailedAttempts($studentInfo['id']);
                        }
                    }
                    break;
                    
                default:
                    log_message('debug', 'Unknown role: ' . $userInfo['role']);
                    break;
            }
            
            if ($authenticated) {
                // Update last login in users table for all roles
                $user->update($userInfo['id'], [
                    'last_login_at' => date('Y-m-d H:i:s')
                ]);
                
                log_message('debug', 'Centralized authentication successful for role: ' . $userInfo['role']);
                return $userInfo;
            }
        }
        
        // CENTRALIZED LOGIN: If user not found in users table, authentication fails
        // This ensures all authentication goes through the centralized users table
        log_message('debug', 'User not found in users table - centralized authentication failed');
        log_message('debug', '=== CENTRALIZED AUTHENTICATION DEBUG END ===');
        return null;
    }

    /**
     * Redirect user based on their role
     */
    private function redirectBasedOnRole($userInfo)
    {
        switch ($userInfo['role']) {
            case 'admin':
                return redirect()->to('/admin/home')
                    ->with('success', 'Welcome back, Admin!');
            case 'teacher':
                return redirect()->to('/teacher/dashboard')
                    ->with('success', 'Welcome back, Teacher!');
            case 'student':
                return redirect()->to('/student/dashboard')
                    ->with('success', 'Welcome to the student portal!');
            default:
                return redirect()->to('/login')
                    ->with('fail', 'Invalid user role');
        }
    }

    /**
     * Handle Google OAuth login
     */
    public function googleLogin()
    {
        return redirect()->to($this->googleOAuth->getAuthUrl());
    }

    /**
     * Handle Google OAuth callback
     */
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
                return redirect()->to('/login')
                    ->with('fail', 'Google authentication failed');
            }

            $userData = $this->googleOAuth->getUserInfo($token);
            if (!$userData) {
                log_message('error', 'Failed to get user information from Google');
                $this->oauthLog->logAuthAttempt([
                    'email' => 'unknown',
                    'name' => 'unknown',
                    'status' => 'failed',
                    'error_message' => 'Failed to get user information from Google'
                ]);
                return redirect()->to('/login')
                    ->with('fail', 'Failed to get user information');
            }

            // Check if the email domain is allowed
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
                return redirect()->to('/login')
                    ->with('fail', 'Your email domain is not authorized. Please use your institutional email address.');
            }

            // Check if user exists in database
            $user = new User();
            $existingUser = $user->where('email', $userData['email'])->first();

            if ($existingUser) {
                // Update existing user's Google-specific information
                $user->update($existingUser['id'], [
                    'picture' => $userData['picture'],
                    'auth_type' => 'google',
                    'last_login_at' => date('Y-m-d H:i:s')
                ]);
                $userInfo = $existingUser;
            } else {
                // Check if this should be a teacher (based on domain or other criteria)
                $role = $this->determineUserRole($userData['email']);
                
                if ($role === 'teacher') {
                    // Create teacher record
                    $teacherModel = new \App\Models\TeacherModel();
                    $teacherId = $teacherModel->createFromGoogleAuth($userData);
                    
                    if (!$teacherId) {
                        throw new \Exception('Failed to create teacher record');
                    }
                    
                    // Get the created user info
                    $userInfo = $user->where('email', $userData['email'])->first();
                } else {
                    // Create admin user
                    $userId = $user->insert([
                        'name' => $userData['name'],
                        'account_no' => explode('@', $userData['email'])[0],
                        'email' => $userData['email'],
                        'picture' => $userData['picture'],
                        'auth_type' => 'google',
                        'role' => 'admin',
                        'permissions' => json_encode(["manage_students", "manage_teachers", "manage_parents", "manage_classes", "manage_subjects", "manage_enrollment", "view_logs", "manage_events", "manage_users"]),
                        'status' => 'active'
                    ]);
                    $userInfo = $user->find($userId);
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
            
            // Redirect based on role
            return $this->redirectBasedOnRole($userInfo);

        } catch (\Exception $e) {
            log_message('error', 'Google OAuth error: ' . $e->getMessage());
            $this->oauthLog->logAuthAttempt([
                'email' => isset($userData['email']) ? $userData['email'] : 'unknown',
                'name' => isset($userData['name']) ? $userData['name'] : 'unknown',
                'status' => 'failed',
                'error_message' => 'Google OAuth error: ' . $e->getMessage()
            ]);
            return redirect()->to('/login')
                ->with('fail', 'An error occurred during Google authentication');
        }
    }

    /**
     * Determine user role based on email domain or other criteria
     */
    private function determineUserRole($email)
    {
        $domain = substr(strrchr($email, "@"), 1);
        
        // Define admin domains (you can customize this)
        $adminDomains = ['cspc.edu.ph', 'my.cspc.edu.ph', 'admin.stajustina.edu.ph'];
        
        // Check if it's an admin domain
        foreach ($adminDomains as $adminDomain) {
            if (str_ends_with($domain, $adminDomain)) {
                return 'admin';
            }
        }
        
        // Default to teacher for other allowed domains
        return 'teacher';
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        CIAuth::forget();
        return redirect()->to('/login')
            ->with('success', 'You have been logged out successfully');
    }

    /**
     * Display forgot password form
     */
    public function forgotForm()
    {
        $data = [
            'pageTitle' => 'Forgot Password',
            'validation' => null
        ];
        return view('backend/pages/auth/forgot-password', $data);
    }

    /**
     * Send password reset link
     */
    public function sendPasswordResetLink()
    {
        $validation = $this->validate([
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email is required',
                    'valid_email' => 'Please provide a valid email address'
                ]
            ]
        ]);

        if (!$validation) {
            return view('backend/pages/auth/forgot-password', [
                'pageTitle' => 'Forgot Password',
                'validation' => $this->validator
            ]);
        }

        $email = $this->request->getVar('email');
        $user = new User();
        $userInfo = $user->where('email', $email)->first();

        if (!$userInfo) {
            return redirect()->back()
                ->with('fail', 'Email not found in our records')
                ->withInput();
        }

        // Generate password reset token
        $token = bin2hex(random_bytes(32));
        $passwordResetToken = new PasswordResetToken();
        
        // Delete any existing tokens for this email
        $passwordResetToken->where('email', $email)->delete();
        
        // Insert new token
        $passwordResetToken->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Send email (implement your email sending logic here)
        $resetLink = base_url('password/reset/' . $token);
        
        // For now, just return success message
        return redirect()->back()
            ->with('success', 'Password reset link has been sent to your email address');
    }

    /**
     * Reset password form
     */
    public function resetPassword($token)
    {
        $passwordResetToken = new PasswordResetToken();
        $tokenData = $passwordResetToken->where('token', $token)
                                      ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-1 hour')))
                                      ->first();

        if (!$tokenData) {
            return redirect()->to('/login')
                ->with('fail', 'Invalid or expired reset token');
        }

        $data = [
            'pageTitle' => 'Reset Password',
            'token' => $token,
            'validation' => null
        ];
        
        return view('backend/pages/auth/reset-password', $data);
    }
}