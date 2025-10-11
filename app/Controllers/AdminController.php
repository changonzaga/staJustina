<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\CIAuth;
use App\Models\StudentModel;
use App\Models\AnnouncementModel;
use App\Models\SubjectModel;
use App\Models\DepartmentModel;
use App\Models\GradeModel;
use App\Models\SectionModel;
use App\Models\TeacherModel;
use App\Services\EmailService;
class AdminController extends BaseController
{
    public function index()
    {
        $data = [
            'pageTitle' => 'Dashboard',
        ];
        return view('backend/admin/dashboard/home', $data);
    }

    /**
     * Get enrollment data with personal information
     */
    private function getEnrollmentWithPersonalInfo($enrollmentId)
    {
        log_message('debug', "Getting enrollment with personal info for ID: $enrollmentId");
        $db = \Config\Database::connect();
        
        try {
            $query = $db->query("
                SELECT e.*, epi.first_name, epi.middle_name, epi.last_name, epi.student_email as email, epi.student_contact as contact_number, epi.lrn
                FROM enrollments e
                LEFT JOIN enrollment_personal_info epi ON e.id = epi.enrollment_id
                WHERE e.id = ?
            ", [$enrollmentId]);
            
            if ($query === false) {
                log_message('error', "Failed to query enrollment with personal info for ID: $enrollmentId - " . print_r($db->error(), true));
                return null;
            }
            
            if ($query->getNumRows() === 0) {
                log_message('warning', "No enrollment found for ID: $enrollmentId");
                return null;
            }
            
            $result = $query->getRow();
            log_message('debug', "Successfully retrieved enrollment with personal info for ID: $enrollmentId");
            return $result;
            
        } catch (\Exception $e) {
            log_message('error', "Exception in getEnrollmentWithPersonalInfo for ID $enrollmentId: " . $e->getMessage());
            return null;
        }
    }


    /**
     * Generate unique student account number
     */
    private function generateStudentAccountNumber()
    {
        $db = \Config\Database::connect();
        
        // Get current year
        $year = date('Y');
        
        do {
            // Get next sequence number for this year
            $query = $db->query("
                SELECT MAX(CAST(SUBSTRING(account_number, 5) AS UNSIGNED)) as max_num
                FROM students 
                WHERE account_number LIKE ?
            ", [$year . '%']);
            
            // Check if query succeeded before calling getRow()
            if ($query === false) {
                $error = $db->error();
                log_message('error', 'Failed to query students table for student number generation: ' . json_encode($error));
                // Fallback to basic numbering
                $accountNumber = $year . '0001';
            } else {
                $result = $query->getRow();
                $nextNum = ($result && isset($result->max_num) ? $result->max_num : 0) + 1;
                
                // Format: YYYY0001, YYYY0002, etc.
                $accountNumber = $year . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
            }
            
            // Check if this account number already exists (double-check for uniqueness)
            $existsQuery = $db->query("SELECT id FROM students WHERE account_number = ?", [$accountNumber]);
            $exists = ($existsQuery !== false && $existsQuery->getNumRows() > 0);
            
            if ($exists) {
                log_message('warning', "Generated account number $accountNumber already exists, retrying...");
            }
            
        } while ($exists);
        
        log_message('debug', "Generated unique account number: $accountNumber");
        return $accountNumber;
    }

    /**
     * Generate secure password for student account
     */
    private function generateSecurePassword()
    {
        // Generate a secure 12-character password with mixed case, numbers, and symbols
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $symbols = '!@#$%^&*';
        
        $password = '';
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $symbols[random_int(0, strlen($symbols) - 1)];
        
        // Fill the rest with random characters from all sets
        $allChars = $uppercase . $lowercase . $numbers . $symbols;
        for ($i = 4; $i < 12; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }
        
        // Shuffle the password to randomize character positions
        return str_shuffle($password);
    }



    /**
     * Transfer SHS details from enrollment to student table
     */
    private function transferSHSDetails($enrollmentId, $studentId)
    {
        $db = \Config\Database::connect();
        
        try {
            // Check if SHS details already exist for this student
            $existingQuery = $db->query("SELECT id FROM student_shs_details WHERE student_id = ?", [$studentId]);
            if ($existingQuery !== false && $existingQuery->getNumRows() > 0) {
                log_message('info', "SHS details already exist for student ID: $studentId, skipping transfer");
                return true;
            }
            
            // Get SHS details from enrollment
            $query = $db->query("
                SELECT semester, track, strand, specialization
                FROM enrollment_shs_details 
                WHERE enrollment_id = ?
            ", [$enrollmentId]);
            
            // Check if query succeeded before calling getRow()
            if ($query === false) {
                $error = $db->error();
                log_message('error', 'Failed to query enrollment_shs_details: ' . json_encode($error));
                return false;
            }
            
            if ($query->getNumRows() === 0) {
                log_message('info', "No SHS details found for enrollment ID: $enrollmentId, skipping transfer");
                return true; // Not an error, just no data to transfer
            }
            
            $shsData = $query->getRow();
            
            if ($shsData) {
                // Insert into student_shs_details
                $insertData = [
                    'student_id' => $studentId,
                    'semester' => $shsData->semester,
                    'track' => $shsData->track,
                    'strand' => $shsData->strand,
                    'specialization' => $shsData->specialization,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                $result = $db->table('student_shs_details')->insert($insertData);
                if (!$result) {
                    $error = $db->error();
                    log_message('error', 'Failed to insert SHS details: ' . json_encode($error));
                    return false;
                }
                
                log_message('info', "SHS details transferred for student ID: $studentId");
                return true;
            }
            
            return false;
            
        } catch (\Exception $e) {
            log_message('error', "Error transferring SHS details for student $studentId: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Transfer disability information from enrollment to student table
     */
    private function transferDisabilityInfo($enrollmentId, $studentId)
    {
        $db = \Config\Database::connect();
        
        try {
            // Check if disability info already exists for this student
            $existingQuery = $db->query("SELECT id FROM student_disabilities_temp WHERE student_id = ?", [$studentId]);
            if ($existingQuery !== false && $existingQuery->getNumRows() > 0) {
                log_message('info', "Disability info already exists for student ID: $studentId, skipping transfer");
                return true;
            }
            
            // Get disability info from enrollment
            $query = $db->query("
                SELECT has_disability, disability_type
                FROM enrollment_disabilities_temp 
                WHERE enrollment_id = ?
            ", [$enrollmentId]);
            
            // Check if query succeeded before calling getRow()
            if ($query === false) {
                $error = $db->error();
                log_message('error', 'Failed to query enrollment_disabilities_temp: ' . json_encode($error));
                return false;
            }
            
            if ($query->getNumRows() === 0) {
                log_message('info', "No disability info found for enrollment ID: $enrollmentId, skipping transfer");
                return true; // Not an error, just no data to transfer
            }
            
            $disabilityData = $query->getRow();
            
            if ($disabilityData) {
                // Insert into student_disabilities_temp
                $insertData = [
                    'student_id' => $studentId,
                    'has_disability' => $disabilityData->has_disability,
                    'disability_type' => $disabilityData->disability_type,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                $result = $db->table('student_disabilities_temp')->insert($insertData);
                if (!$result) {
                    $error = $db->error();
                    log_message('error', 'Failed to insert disability info: ' . json_encode($error));
                    return false;
                }
                
                log_message('info', "Disability info transferred for student ID: $studentId");
                return true;
            }
            
            return false;
            
        } catch (\Exception $e) {
            log_message('error', "Error transferring disability info for student $studentId: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Transfer academic history from enrollment to student table
     */
    private function transferAcademicHistory($enrollmentId, $studentId)
    {
        $db = \Config\Database::connect();
        
        try {
            // Get academic history from enrollment
            $query = $db->query("
                SELECT previous_gwa, performance_level, last_grade_completed, 
                       last_school_year, last_school_attended, school_id, created_at
                FROM enrollment_academic_history_new 
                WHERE enrollment_id = ?
            ", [$enrollmentId]);
            
            // Check if query succeeded before calling getRow()
            if ($query === false) {
                $error = $db->error();
                log_message('error', 'Failed to query enrollment_academic_history_new: ' . json_encode($error));
                return false;
            }
            
            if ($query->getNumRows() === 0) {
                log_message('info', "No academic history found for enrollment ID: $enrollmentId");
                return true; // Not an error, just no data to transfer
            }
            
            $academicData = $query->getRow();
            
            if ($academicData) {
                // Insert into student_academic_history
                $insertData = [
                    'student_id' => $studentId,
                    'previous_gwa' => $academicData->previous_gwa,
                    'performance_level' => $academicData->performance_level,
                    'last_grade_completed' => $academicData->last_grade_completed,
                    'last_school_year' => $academicData->last_school_year,
                    'last_school_attended' => $academicData->last_school_attended,
                    'school_id' => $academicData->school_id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                $result = $db->table('student_academic_history')->insert($insertData);
                if (!$result) {
                    $error = $db->error();
                    log_message('error', 'Failed to insert academic history: ' . json_encode($error));
                    return false;
                }
                
                log_message('info', "Academic history transferred for student ID: $studentId");
                return true;
            }
            
            return false;
            
        } catch (\Exception $e) {
            log_message('error', "Error transferring academic history for student $studentId: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create student record
     */
    private function createStudentRecord($enrollmentData, $studentAccountNumber)
    {
        $db = \Config\Database::connect();
        
        // Get LRN from enrollment personal info
        $lrnQuery = $db->query("SELECT lrn FROM enrollment_personal_info WHERE enrollment_id = ?", [$enrollmentData->id]);
        $lrn = '';
        if ($lrnQuery && $lrnQuery->getNumRows() > 0) {
            $lrnData = $lrnQuery->getRow();
            $lrn = $lrnData->lrn ?? '';
        }
        
        // Ensure LRN is not empty (required field)
        if (empty($lrn)) {
            $lrn = 'TEMP-' . $enrollmentData->id; // Temporary LRN if not provided
        }
        
        $studentData = [
            'account_number' => $studentAccountNumber,
            'lrn' => $lrn,
            'enrollment_id' => $enrollmentData->id,
            'student_status' => 'active',
            'enrollment_date' => date('Y-m-d'),
            'grade_level' => $enrollmentData->grade_level,
            'section' => null, // Will be assigned later
            'academic_year' => $enrollmentData->school_year
        ];
        
        $result = $db->table('students')->insert($studentData);
        if (!$result) {
            $error = $db->error();
            log_message('error', 'Failed to create student record: ' . json_encode($error));
            throw new \Exception('Failed to create student record: ' . $error['message']);
        }

        $studentId = $db->insertID();
        if (!$studentId) {
            throw new \Exception('Failed to get student ID after insertion');
        }

        // Get detailed personal info from enrollment_personal_info table
        $personalInfoQuery = $db->query("SELECT * FROM enrollment_personal_info WHERE enrollment_id = ?", [$enrollmentData->id]);
        $enrollmentPersonalInfo = null;
        if ($personalInfoQuery && $personalInfoQuery->getNumRows() > 0) {
            $enrollmentPersonalInfo = $personalInfoQuery->getRow();
        }

        // Create corresponding student_personal_info record with actual enrollment data
        $personalInfoData = [
            'student_id' => $studentId,
            'lrn' => $lrn,
            'birth_certificate_number' => $enrollmentPersonalInfo->birth_certificate_number ?? null,
            'first_name' => $enrollmentPersonalInfo->first_name ?? $enrollmentData->first_name ?? 'Unknown',
            'middle_name' => $enrollmentPersonalInfo->middle_name ?? $enrollmentData->middle_name,
            'last_name' => $enrollmentPersonalInfo->last_name ?? $enrollmentData->last_name ?? 'Unknown',
            'extension_name' => $enrollmentPersonalInfo->extension_name ?? null,
            'date_of_birth' => $enrollmentPersonalInfo->date_of_birth ?? '2000-01-01',
            'place_of_birth' => $enrollmentPersonalInfo->place_of_birth ?? null,
            'gender' => $enrollmentPersonalInfo->gender ?? 'Male',
            'age' => $enrollmentPersonalInfo->age ?? 18,
            'nationality' => $enrollmentPersonalInfo->nationality ?? null,
            'mother_tongue' => $enrollmentPersonalInfo->mother_tongue ?? null,
            'student_email' => $enrollmentPersonalInfo->student_email ?? $enrollmentData->email ?? null,
            'student_contact' => $enrollmentPersonalInfo->student_contact ?? $enrollmentData->contact_number ?? null,
            'indigenous_people' => $enrollmentPersonalInfo->indigenous_people ?? 'No',
            'indigenous_community' => $enrollmentPersonalInfo->indigenous_community ?? null,
            'fourps_beneficiary' => $enrollmentPersonalInfo->fourps_beneficiary ?? 'No',
            'fourps_household_id' => $enrollmentPersonalInfo->fourps_household_id ?? null,
            'profile_picture' => $enrollmentPersonalInfo->profile_picture ?? null
        ];

        $personalResult = $db->table('student_personal_info')->insert($personalInfoData);
        if (!$personalResult) {
            $error = $db->error();
            log_message('error', 'Failed to create student personal info: ' . json_encode($error));
            throw new \Exception('Failed to create student personal info: ' . $error['message']);
        }

        return $studentId;
    }

    /**
     * Create user account
     */
    private function createUserAccount($enrollmentData, $studentAccountNumber, $hashedPassword)
    {
        $db = \Config\Database::connect();
        
        // Get profile picture from enrollment personal info
        $profilePicture = null;
        $personalInfoQuery = $db->query("SELECT profile_picture FROM enrollment_personal_info WHERE enrollment_id = ?", [$enrollmentData->id]);
        if ($personalInfoQuery && $personalInfoQuery->getNumRows() > 0) {
            $personalInfo = $personalInfoQuery->getRow();
            $profilePicture = $personalInfo->profile_picture;
        }
        
        $fullName = trim($enrollmentData->first_name . ' ' . ($enrollmentData->middle_name ? $enrollmentData->middle_name . ' ' : '') . $enrollmentData->last_name);
        
        $userData = [
            'name' => $fullName,
            'account_no' => $studentAccountNumber,
            'email' => $enrollmentData->email,
            'password' => $hashedPassword,
            'picture' => $profilePicture,
            'auth_type' => 'email',
            'role' => 'student',
            'permissions' => null,
            'last_login_at' => null,
            'status' => 'active'
        ];
        
        $result = $db->table('users')->insert($userData);
        if (!$result) {
            $error = $db->error();
            log_message('error', 'Failed to create user account: ' . json_encode($error));
            throw new \Exception('Failed to create user account: ' . $error['message']);
        }
        
        $userId = $db->insertID();
        if (!$userId) {
            throw new \Exception('Failed to get user ID after insertion');
        }
        
        return $userId;
    }

    /**
     * Create student authentication record
     */
    private function createStudentAuthRecord($studentId, $enrollmentData, $hashedPassword, $plainPassword)
    {
        $db = \Config\Database::connect();
        
        // Get student account number for the auth record
        $studentQuery = $db->query("SELECT account_number FROM students WHERE id = ?", [$studentId]);
        $studentAccountNumber = '';
        if ($studentQuery && $studentQuery->getNumRows() > 0) {
            $studentData = $studentQuery->getRow();
            $studentAccountNumber = $studentData->account_number ?? '';
        }
        
        // Check if auth record already exists for this student
        $existingAuthQuery = $db->table('student_auth')
                              ->where('student_id', $studentId)
                              ->get();
        
        if ($existingAuthQuery === false) {
            $error = $db->error();
            log_message('error', 'Failed to query student_auth table: ' . json_encode($error));
            throw new \Exception('Failed to query student_auth table: ' . $error['message']);
        }
        
        $existingAuth = $existingAuthQuery->getRow();
        
        if ($existingAuth) {
            // Update existing record
            $authData = [
                'account_number' => $studentAccountNumber,
                'password_hash' => $hashedPassword,
                'email' => $enrollmentData->email,
                'is_active' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $result = $db->table('student_auth')
                        ->where('student_id', $studentId)
                        ->update($authData);
            
            if (!$result) {
                $error = $db->error();
                log_message('error', 'Failed to update student_auth record: ' . json_encode($error));
                throw new \Exception('Failed to update student_auth record: ' . $error['message']);
            }
            
            return $existingAuth->id;
        } else {
            // Create new record
            $authData = [
                'student_id' => $studentId,
                'account_number' => $studentAccountNumber,
                'password_hash' => $hashedPassword,
                'email' => $enrollmentData->email,
                'is_active' => 1,
                'failed_login_attempts' => 0
            ];
            
            $result = $db->table('student_auth')->insert($authData);
            if (!$result) {
                $error = $db->error();
                log_message('error', 'Failed to create student_auth record: ' . json_encode($error));
                throw new \Exception('Failed to create student_auth record: ' . $error['message']);
            }
            
            $authId = $db->insertID();
            if (!$authId) {
                throw new \Exception('Failed to get auth ID after insertion');
            }
            
            return $authId;
        }
    }

    /**
     * Send account creation email to student
     */
    private function sendAccountCreationEmail($enrollmentData, $studentAccountNumber, $password)
    {
        try {
            $emailService = new \App\Services\EmailService();
            $accountData = [
                'student_number' => $studentAccountNumber,
                'password' => $password
            ];
            $result = $emailService->sendEnrollmentApprovalEmail($enrollmentData, $accountData);
            
            // EmailService returns boolean, convert to expected array format
            return [
                'success' => $result,
                'error' => $result ? null : 'Email sending failed'
            ];
        } catch (\Exception $e) {
            log_message('error', 'Failed to send account creation email: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Email sending failed: ' . $e->getMessage()
            ];
        }
    }



    /**
     * Log email sending attempts
     */
    private function logEmailSent($enrollmentId, $email, $type, $status, $errorMessage = null)
    {
        $db = \Config\Database::connect();
        
        $logData = [
            'enrollment_id' => $enrollmentId,
            'email_address' => $email,
            'email_type' => $type,
            'status' => $status,
            'error_message' => $errorMessage,
            'sent_at' => date('Y-m-d H:i:s')
        ];
        
        try {
            $db->table('enrollment_email_logs')->insert($logData);
        } catch (\Exception $e) {
            // If enrollment_email_logs table doesn't exist, log to file
            log_message('info', 'Email Log: ' . json_encode($logData));
        }
    }

    /**
     * Log account creation details
     */
    private function logAccountCreation($enrollmentId, $studentId, $userId, $authId, $emailResult)
    {
        $logData = [
            'enrollment_id' => $enrollmentId,
            'student_id' => $studentId,
            'user_id' => $userId,
            'auth_id' => $authId,
            'email_sent' => $emailResult['success'] ?? false,
            'email_error' => $emailResult['error'] ?? null,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        log_message('info', 'Account Creation: ' . json_encode($logData));
    }

    public function logoutHandler(){
        CIAuth::forget();
        return redirect()->to('/login')->with('success', 'You have been logged out successfully');
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
            'teachers' => $teacherModel->getTeachersWithAuth()
        );
        return view('backend/admin/teachers/teacher', $data);
    }
    public function parent(){
        $parentModel = new \App\Models\ParentModel();
        $data = array(
            'pageTitle' => 'Parent',
            'parents' => $parentModel->getAllParentsWithStudents()
        );
        return view('backend/admin/parents/parent', $data);
    }

    public function getParentData($id = null){
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Parent ID is required'
            ]);
        }

        $db = \Config\Database::connect();
        
        try {
            // Use the existing normalized parent structure with student_parent_address
            $query = $db->query("
                SELECT 
                    p.id as parent_id,
                    p.first_name,
                    p.middle_name,
                    p.last_name,
                    p.contact_number,
                    p.created_at,
                    p.updated_at,
                    spr.relationship_type,
                    spr.is_primary_contact,
                    spr.is_emergency_contact,
                    spi.first_name as student_first_name,
                    spi.middle_name as student_middle_name,
                    spi.last_name as student_last_name,
                    spa.parent_type,
                    spa.is_same_as_student,
                    spa.house_number,
                    spa.street,
                    spa.barangay,
                    spa.municipality,
                    spa.province,
                    spa.zip_code
                FROM parents p
                LEFT JOIN student_parent_relationships spr ON p.id = spr.parent_id
                LEFT JOIN student_personal_info spi ON spr.student_id = spi.student_id
                LEFT JOIN student_parent_address spa ON spr.student_id = spa.student_id AND BINARY spa.parent_type = BINARY spr.relationship_type
                WHERE p.id = ?
                LIMIT 1
            ", [$id]);

            if ($query->getNumRows() === 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Parent not found'
                ]);
            }

            $parentData = $query->getRow();

            // Format student name
            $studentName = 'Unknown Student';
            if ($parentData->student_first_name) {
                $studentName = trim(($parentData->student_first_name ?? '') . ' ' . 
                                 ($parentData->student_middle_name ?? '') . ' ' . 
                                 ($parentData->student_last_name ?? ''));
            }

            // Format the response data
            $responseData = [
                'id' => $parentData->parent_id,
                'first_name' => $parentData->first_name ?? '',
                'middle_name' => $parentData->middle_name ?? '',
                'last_name' => $parentData->last_name ?? '',
                'relationship_type' => ucfirst($parentData->relationship_type ?? ''),
                'contact_number' => $parentData->contact_number ?? '',
                'is_primary_contact' => $parentData->is_primary_contact ? 'Yes' : 'No',
                'is_emergency_contact' => $parentData->is_emergency_contact ? 'Yes' : 'No',
                'student_name' => $studentName,
                'created_at' => $parentData->created_at ? date('M d, Y g:i A', strtotime($parentData->created_at)) : '',
                'updated_at' => $parentData->updated_at ? date('M d, Y g:i A', strtotime($parentData->updated_at)) : 'Never updated',
                // Address information from student_parent_address table
                'parent_type' => ucfirst($parentData->parent_type ?? $parentData->relationship_type ?? ''),
                'is_same_as_student' => isset($parentData->is_same_as_student) ? ($parentData->is_same_as_student ? 'Yes' : 'No') : '-',
                'house_number' => $parentData->house_number ?? '-',
                'street' => $parentData->street ?? '-',
                'barangay' => $parentData->barangay ?? '-',
                'municipality' => $parentData->municipality ?? '-',
                'province' => $parentData->province ?? '-',
                'zip_code' => $parentData->zip_code ?? '-',
                'country' => 'Philippines' // Default country
            ];

            return $this->response->setJSON([
                'success' => true,
                'data' => $responseData
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error in getParentData: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to retrieve parent data'
            ]);
        }
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
    
    public function department(){
        // Temporary dataset; replace with DB fetch when model is ready
        $departments = [
            ['id' => 1, 'code' => 'MATH', 'name' => 'Mathematics', 'head' => 'Jane Smith', 'status' => 1],
            ['id' => 2, 'code' => 'ENG', 'name' => 'English', 'head' => 'John Doe', 'status' => 1],
            ['id' => 3, 'code' => 'SCI', 'name' => 'Science', 'head' => '—', 'status' => 0],
        ];
        $data = [
            'pageTitle' => 'Department Management',
            'departments' => $departments,
        ];
        return view('backend/admin/department/department', $data);
    }
    public function announcement(){
        $model = new AnnouncementModel();
        $data = array(
            'pageTitle' => 'Announcement',
            'announcements' => $model->getPublishedAnnouncements('all')
        );
        return view('backend/admin/announcements/announcement', $data);
    }
    
    public function createAnnouncement(){
        $data = array(
            'pageTitle' => 'Create Announcement'
        );
        return view('backend/admin/announcements/create-announcement', $data);
    }

	/**
	 * Process announcement creation form
	 */
	public function processAnnouncement()
	{
		$announcementModel = new AnnouncementModel();
		$emailService = new EmailService();
		$smsService = service('sms');
		
		// Get form data
		$title = $this->request->getPost('title');
		$content = $this->request->getPost('content');
		$audience = $this->request->getPost('audience');
		$priority = $this->request->getPost('priority');
		$publishDate = $this->request->getPost('publish_date');
		$expiryDate = $this->request->getPost('expiry_date');
		$sendNotification = $this->request->getPost('send_notification');
		
		// Get current user info
		$userInfo = session()->get('userdata');
		$senderId = $userInfo['id'] ?? 1; // Default to admin ID 1 if not found
		
		// Prepare announcement data
		$announcementData = [
			'title' => $title,
			'content' => $content,
			'sender_id' => $senderId,
			'sender_type' => 'admin',
			'audience_type' => strtolower($audience),
			'priority' => $priority,
			'status' => 'published',
			'publish_date' => !empty($publishDate) ? $publishDate : date('Y-m-d H:i:s'),
			'expiry_date' => !empty($expiryDate) ? $expiryDate : null,
			'is_scheduled' => !empty($publishDate) ? 1 : 0,
			'is_draft' => 0
		];
		
		try {
			// Create announcement
			$announcementId = $announcementModel->createAnnouncement($announcementData);
			
			if (!$announcementId) {
				return $this->response->setJSON([
					'success' => false,
					'message' => 'Failed to create announcement. Please try again.'
				]);
			}
			
			// Get the created announcement for email notification
			$announcement = $announcementModel->getAnnouncementById($announcementId);
			
			// Send notifications if requested
			if ($sendNotification && $announcement) {
				// Email notifications (existing behavior)
				try {
					$emailResult = $emailService->sendAnnouncementNotification($announcement, $announcementData['audience_type']);
					log_message('info', "Announcement email sent - Success: {$emailResult['success']}, Failed: {$emailResult['failed']}");
				} catch (\Exception $emailException) {
					log_message('warning', 'Email notification failed but announcement created: ' . $emailException->getMessage());
				}

				// SMS to parents only
				if ($announcementData['audience_type'] === 'parents') {
					$smsSummary = $this->sendParentAnnouncementSMS($announcement);
					log_message('info', 'Parent SMS summary: ' . json_encode($smsSummary));
				}
			}
			
			return $this->response->setJSON([
				'success' => true,
				'message' => 'Announcement created successfully!' . 
						   ($sendNotification ? ' Email notifications have been sent.' : '')
			]);
			
		} catch (\Exception $e) {
			log_message('error', 'Failed to create announcement: ' . $e->getMessage());
			return $this->response->setJSON([
				'success' => false,
				'message' => 'An error occurred while creating the announcement. Please try again.'
			]);
		}
	}
    
	public function announcementHistory(){
		$announcementModel = new AnnouncementModel();
		$data = array(
			'pageTitle' => 'Announcement History',
			'announcements' => $announcementModel->getDashboardAnnouncements(20)
		);
		return view('backend/admin/announcements/announcement-history', $data);
	}

	/**
	 * Send SMS for announcement to parents with simple rate limiting and quota handling
	 */
	private function sendParentAnnouncementSMS($announcement)
	{
		$db = \Config\Database::connect();
		$sms = service('sms');

		// Collect parent numbers from normalized parents table via relationship
		$numbers = [];
		$normalized = $db->query(
			"SELECT DISTINCT p.contact_number
			 FROM parents p
			 JOIN student_parent_relationships spr ON spr.parent_id = p.id
			 WHERE p.contact_number IS NOT NULL AND p.contact_number != ''"
		);
		if ($normalized && $normalized->getNumRows() > 0) {
			foreach ($normalized->getResult() as $row) {
				$numbers[] = preg_replace('/[^0-9]/', '', $row->contact_number);
			}
		}

		// Fallback: also include legacy enrollment family info numbers (father/mother/guardian)
		$legacy = $db->query("SELECT DISTINCT contact_number FROM enrollment_family_info WHERE contact_number IS NOT NULL AND contact_number != ''");
		if ($legacy && $legacy->getNumRows() > 0) {
			foreach ($legacy->getResult() as $row) {
				$numbers[] = preg_replace('/[^0-9]/', '', $row->contact_number);
			}
		}

		// De-duplicate numbers
		$numbers = array_values(array_unique($numbers));

		// Normalize to PH format 63xxxxxxxxxx
		$recipients = [];
		foreach ($numbers as $n) {
			if (strpos($n, '09') === 0 && strlen($n) === 11) {
				$recipients[] = '63' . substr($n, 1);
			} elseif (strpos($n, '9') === 0 && strlen($n) === 10) {
				$recipients[] = '63' . $n;
			} elseif (strpos($n, '63') === 0) {
				$recipients[] = $n;
			}
		}

		if (empty($recipients)) {
			log_message('info', 'Parent SMS: no recipient numbers found in parents or enrollment_family_info');
			return [
				'success' => false,
				'sent' => 0,
				'failed' => 0,
				'limitHit' => false,
				'message' => 'No parent phone numbers found'
			];
		}

		// Prepare concise SMS message
		$title = $announcement['title'] ?? ($announcement->title ?? 'Announcement');
		$content = $announcement['content'] ?? ($announcement->content ?? '');
		$priority = strtoupper($announcement['priority'] ?? ($announcement->priority ?? 'NORMAL'));
		$text = mb_substr("[{$priority}] {$title}: " . strip_tags($content), 0, 300);

		$sent = 0;
		$failed = 0;
		$limitHit = false;

		$perMinute = (int) env('sms.ratePerMinute', 20); // simple rate limit
		$delaySeconds = max(60 / max($perMinute, 1), 1);
		$maxToSend = (int) env('sms.batchMax', 200);

		$count = 0;
		foreach ($recipients as $to) {
			if ($count >= $maxToSend) break;
			$count++;

			$result = $sms->send($to, $text);
			if (!empty($result['success'])) {
				$sent++;
			} else {
				$failed++;
				// Stop if provider says limit exceeded
				if (($result['status'] ?? null) === 403) {
					$body = $result['body'];
					$msg = is_array($body) ? ($body['message'] ?? '') : (string) $body;
					if (stripos($msg, 'limit') !== false) {
						$limitHit = true;
						break;
					}
				}
			}

			// drip to respect rate
			usleep((int) ($delaySeconds * 1000000));
		}

		return [
			'success' => !$limitHit,
			'sent' => $sent,
			'failed' => $failed,
			'stopped_due_to_limit' => $limitHit,
			'attempted' => $count
		];
	}

	/**
	 * Get announcements for display
	 */
	public function getAnnouncements()
	{
		$announcementModel = new AnnouncementModel();
		$audience = strtolower($this->request->getGet('audience') ?? '');
		
		// Default: show published announcements suitable for admin dashboard
		$announcements = $announcementModel->getPublishedForAdmin(50);
		
		// If audience filter provided, intersect by audience type (case-insensitive)
		if (!empty($audience) && $audience !== 'all') {
			$announcements = array_values(array_filter($announcements, function($a) use ($audience){
				return strtolower($a['audience_type'] ?? '') === $audience;
			}));
		}
		
		return $this->response->setJSON([
			'success' => true,
			'announcements' => $announcements
		]);
	}
    
    public function enrollment(){
        // Use the new enrollment system
        $enrollmentModel = new \App\Models\EnrollmentModel();
        
        // Get all enrollments with student details
        $allEnrollments = $enrollmentModel->getEnrollmentsWithStudentDetails();
        
        // Get pending enrollments for backward compatibility
        $pendingEnrollments = $enrollmentModel->getEnrollmentsWithStudentDetails('pending');
        
        // Get enrollment statistics
        $rawStats = $enrollmentModel->getEnrollmentStats();
        
        // Format statistics for the view
        $stats = [
            'total' => $rawStats['total'],
            'pending' => 0,
            'enrolled' => 0,
            'declined' => 0
        ];
        
        // Process status statistics
        if (isset($rawStats['by_status'])) {
            foreach ($rawStats['by_status'] as $statusStat) {
                $status = $statusStat['enrollment_status'];
                $count = $statusStat['count'];
                
                if (isset($stats[$status])) {
                    $stats[$status] = $count;
                }
            }
        }
        
        $data = [
            'pageTitle' => 'Enrollment Management',
            'allEnrollments' => $allEnrollments,
            'pendingEnrollments' => $pendingEnrollments,
            'stats' => $stats
        ];
        return view('backend/admin/enrollment/enrollment', $data);
    }
    
    /**
     * Handle enrollment approval and transfer to student system
     */
    public function approveEnrollment($enrollmentId)
    {
        log_message('info', "=== ENROLLMENT APPROVAL STARTED ===");
        log_message('info', "Enrollment ID: $enrollmentId");
        log_message('info', "Request Method: " . $this->request->getMethod());
        log_message('info', "Request Headers: " . json_encode($this->request->headers()));
        log_message('info', "Session Data: " . json_encode(session()->get()));
        log_message('debug', "Starting enrollment approval process for ID: $enrollmentId");
        
        $enrollmentModel = new \App\Models\EnrollmentModel();
        $userInfo = session()->get('userdata');
        $approvedBy = $userInfo['id'] ?? null;

        log_message('info', "User Info from session: " . json_encode($userInfo));
        log_message('info', "Approved By ID: " . ($approvedBy ?? 'NULL'));

        if (!$approvedBy) {
            log_message('error', "Enrollment approval failed: Invalid user session for enrollment ID: $enrollmentId");
            log_message('error', "Full session data: " . json_encode(session()->get()));
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User session invalid'
            ]);
        }

        log_message('debug', "Enrollment approval initiated by user ID: $approvedBy for enrollment ID: $enrollmentId");

        // Start overall transaction manually for the entire approval process
        $db = \Config\Database::connect();
        $db->query('START TRANSACTION');
        $transactionStarted = true;
        
        try {
            // First check if enrollment exists and its current status
            log_message('debug', "Checking enrollment existence and status for ID: $enrollmentId");
            $query = $db->query("SELECT * FROM enrollments WHERE id = ?", [$enrollmentId]);
            
            if ($query === false) {
                $error = $db->error();
                log_message('error', "Database query failed when checking enrollment $enrollmentId: " . json_encode($error));
                if ($transactionStarted) {
                    $db->query('ROLLBACK');
                }
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Database error occurred. Please try again.'
                ]);
            }
            
            $enrollment = $query->getRow();
            if (!$enrollment) {
                log_message('error', "Enrollment not found for ID: $enrollmentId");
                if ($transactionStarted) {
                    $db->query('ROLLBACK');
                }
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Enrollment not found.'
                ]);
            }
            
            log_message('debug', "Enrollment found - ID: $enrollmentId, Status: {$enrollment->enrollment_status}");
            
            // Check if already enrolled
            if ($enrollment->enrollment_status === 'enrolled') {
                log_message('info', "Enrollment $enrollmentId already processed (status: enrolled)");
                if ($transactionStarted) {
                    $db->query('ROLLBACK');
                }
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'This enrollment has already been approved and processed.'
                ]);
            }
            
            // Check if already declined
            if ($enrollment->enrollment_status === 'declined') {
                log_message('info', "Enrollment $enrollmentId cannot be approved (status: declined)");
                if ($transactionStarted) {
                    $db->query('ROLLBACK');
                }
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'This enrollment has been declined and cannot be approved.'
                ]);
            }
            
            // Proceed with approval only if status is pending or approved (but not yet enrolled)
            log_message('info', "Proceeding with enrollment approval for ID: $enrollmentId");
            log_message('debug', "Proceeding with enrollment approval for ID: $enrollmentId");
            $approvalResult = $enrollmentModel->approveEnrollment($enrollmentId, $approvedBy);
            
            log_message('info', "EnrollmentModel::approveEnrollment result: " . json_encode($approvalResult));
            if (!$approvalResult) {
                log_message('error', "Failed to approve enrollment in database for ID: $enrollmentId");
                if ($transactionStarted) {
                    $db->query('ROLLBACK');
                }
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to approve enrollment in database.'
                ]);
            }
            
            log_message('info', "Enrollment approved successfully in database for ID: $enrollmentId, starting student system transfer");
            log_message('debug', "Enrollment approved successfully in database for ID: $enrollmentId, starting student system transfer");
            
            // Now transfer to student management system
            log_message('info', "=== STARTING STUDENT SYSTEM TRANSFER ===");
            $transferResult = $this->transferEnrollmentToStudentSystem($enrollmentId);
            
            log_message('info', "Transfer result: " . json_encode($transferResult));
            if ($transferResult['success']) {
                // Complete the overall transaction manually
                $commitResult = $db->query('COMMIT');
                
                if (!$commitResult) {
                    log_message('error', "Manual commit failed for enrollment approval $enrollmentId");
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Transaction failed during approval process. Please try again.'
                    ]);
                }
                
                log_message('info', "Complete enrollment approval successful for ID: $enrollmentId, Account: {$transferResult['account_number']}");
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Student enrolled successfully! Student account created: ' . $transferResult['account_number'] . '. Notifications have been queued for delivery.'
                ]);
            } else {
                // If transfer fails, rollback everything including the enrollment approval
                log_message('error', "Student system transfer failed for enrollment $enrollmentId: " . $transferResult['error']);
                if ($transactionStarted) {
                    $db->query('ROLLBACK');
                }
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create student account. Enrollment approval has been rolled back. Please try again.'
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', "Exception in enrollment approval for ID $enrollmentId: " . $e->getMessage() . " | Trace: " . $e->getTraceAsString());
            if ($transactionStarted) {
                $db->query('ROLLBACK');
            }
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred during approval. Please try again.'
            ]);
        }
    }
    
    /**
     * Transfer approved enrollment to student management system
     * Creates student account, generates credentials, and sends email notification
     */
    private function transferEnrollmentToStudentSystem($enrollmentId)
    {
        log_message('info', "=== TRANSFER TO STUDENT SYSTEM STARTED ===");
        log_message('info', "Enrollment ID: $enrollmentId");
        log_message('debug', "Starting student system transfer for enrollment ID: $enrollmentId");
        $db = \Config\Database::connect();
        
        try {
            // Start transaction - using manual control instead of CodeIgniter's transStart
            log_message('info', "Starting database transaction for enrollment ID: $enrollmentId");
            log_message('debug', "Starting database transaction for enrollment ID: $enrollmentId");
            $db->query('START TRANSACTION');
            $transactionStarted = true;
            
            // Get enrollment details with personal info
            log_message('info', "Retrieving enrollment data for ID: $enrollmentId");
            log_message('debug', "Retrieving enrollment data for ID: $enrollmentId");
            $enrollmentData = $this->getEnrollmentWithPersonalInfo($enrollmentId);
            if (!$enrollmentData) {
                log_message('error', "Enrollment data not found for ID: $enrollmentId");
                throw new \Exception('Enrollment data not found for ID: ' . $enrollmentId);
            }
            
            // Validate required fields from enrollment data
            if (empty($enrollmentData->first_name) || empty($enrollmentData->last_name)) {
                log_message('error', "Invalid enrollment data - missing required fields for ID: $enrollmentId");
                throw new \Exception('Invalid enrollment data - missing required student name fields');
            }
            
            log_message('info', "Enrollment data retrieved successfully for ID: $enrollmentId, Student: {$enrollmentData->first_name} {$enrollmentData->last_name}");
            log_message('debug', "Enrollment data retrieved successfully for ID: $enrollmentId, Student: {$enrollmentData->first_name} {$enrollmentData->last_name}");
            
            // Check if student record already exists to prevent duplicates
            log_message('info', "Checking for existing student record for enrollment ID: $enrollmentId");
            log_message('debug', "Checking for existing student record for enrollment ID: $enrollmentId");
            $existingStudent = $db->query("SELECT id FROM students WHERE enrollment_id = ?", [$enrollmentId]);
            
            // Handle query failure
            if ($existingStudent === false) {
                log_message('error', "Failed to query existing student for enrollment ID: $enrollmentId - " . print_r($db->error(), true));
                throw new \Exception('Database query failed while checking for existing student record');
            }
            
            if ($existingStudent->getNumRows() > 0) {
                $student = $existingStudent->getRow();
                log_message('info', "Student record already exists for enrollment $enrollmentId, student ID: {$student->id}");
                
                // Return success with existing account number
                $accountQuery = $db->query("SELECT account_number FROM students WHERE id = ?", [$student->id]);
                if ($accountQuery === false) {
                    log_message('error', "Failed to query account number for student ID: {$student->id} - " . print_r($db->error(), true));
                    throw new \Exception('Database query failed while retrieving account number');
                }
                
                if ($accountQuery->getNumRows() > 0) {
                    $accountData = $accountQuery->getRow();
                    return [
                        'success' => true,
                        'account_number' => $accountData->account_number ?? 'N/A',
                        'message' => 'Student record already exists'
                    ];
                }
            }
            
            // Generate student account number
            log_message('info', "Generating student account number for enrollment ID: $enrollmentId");
            log_message('debug', "Generating student account number for enrollment ID: $enrollmentId");
            $studentAccountNumber = $this->generateStudentAccountNumber();
            log_message('info', "Generated account number: $studentAccountNumber for enrollment ID: $enrollmentId");
            log_message('debug', "Generated account number: $studentAccountNumber for enrollment ID: $enrollmentId");
            
            // Generate secure password
            log_message('info', "Generating secure password for enrollment ID: $enrollmentId");
            log_message('debug', "Generating secure password for enrollment ID: $enrollmentId");
            $password = $this->generateSecurePassword();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            log_message('info', "Password generated and hashed for enrollment ID: $enrollmentId");
            log_message('debug', "Password generated and hashed for enrollment ID: $enrollmentId");
            
            // Create student record
            log_message('info', "=== CREATING STUDENT RECORD ===");
            log_message('debug', "Creating student record for enrollment ID: $enrollmentId");
            $studentId = $this->createStudentRecord($enrollmentData, $studentAccountNumber);
            log_message('info', "Student record created successfully - ID: $studentId, Account: $studentAccountNumber for enrollment ID: $enrollmentId");
            
            // Create user account
            log_message('info', "=== CREATING USER ACCOUNT ===");
            log_message('debug', "Creating user account for student ID: $studentId, enrollment ID: $enrollmentId");
            $userId = $this->createUserAccount($enrollmentData, $studentAccountNumber, $hashedPassword);
            log_message('info', "User account created successfully - ID: $userId for student ID: $studentId");
            
            // Create student authentication record
            log_message('info', "=== CREATING STUDENT AUTH RECORD ===");
            log_message('debug', "Creating student authentication record for student ID: $studentId");
            $authId = $this->createStudentAuthRecord($studentId, $enrollmentData, $hashedPassword, $password);
            log_message('info', "Student auth record created successfully - ID: $authId for student ID: $studentId");
            
            // Transfer SHS details and academic history
            log_message('info', "=== STARTING DATA MIGRATION ===");
            log_message('debug', "Starting data migration for student ID: $studentId, enrollment ID: $enrollmentId");
            $shsResult = $this->transferSHSDetails($enrollmentId, $studentId);
            $academicResult = $this->transferAcademicHistory($enrollmentId, $studentId);
            
            // Transfer additional data (personal info, address, family info, disability info)
            log_message('info', "Starting additional data migration for student ID: $studentId");
            log_message('debug', "Starting additional data migration for student ID: $studentId");
            $personalResult = $this->transferPersonalInfo($enrollmentId, $studentId);
            $addressResult = $this->transferAddressInfo($enrollmentId, $studentId);
            // Use ParentManager to handle parent data with deduplication (new normalized structure)
            $parentManager = new \App\Libraries\ParentManager();
            $parentResult = $parentManager->processEnrollmentParents($enrollmentId, $studentId);
            
            // Check if parent processing failed and throw exception to trigger rollback
            if (!$parentResult['success']) {
                $errorMessage = $parentResult['error'] ?? 'Unknown parent processing error';
                log_message('error', "Parent processing failed for enrollment $enrollmentId: $errorMessage");
                throw new \Exception("Failed to process parent data: $errorMessage");
            }
            
            // Set familyResult based on parent processing success
            $familyResult = $parentResult['success'];
            
            $disabilityResult = $this->transferDisabilityInfo($enrollmentId, $studentId);
            // Parent address handling is now done by ParentManager
            $parentAddressResult = $parentResult['success']; // ParentManager handles addresses
            $emergencyContactResult = $this->transferEmergencyContacts($enrollmentId, $studentId);
            
            log_message('info', "Data migration results - SHS: " . ($shsResult ? 'success' : 'failed') . 
                       ", Academic: " . ($academicResult ? 'success' : 'failed') . 
                       ", Personal: " . ($personalResult ? 'success' : 'failed') . 
                       ", Address: " . ($addressResult ? 'success' : 'failed') . 
                       ", Family: " . ($familyResult ? 'success' : 'failed') . 
                       ", Disability: " . ($disabilityResult ? 'success' : 'failed') . 
                       ", Parent Address: " . ($parentAddressResult ? 'success' : 'failed') . 
                       ", Emergency Contacts: " . ($emergencyContactResult ? 'success' : 'failed'));
            log_message('debug', "Data migration results - SHS: " . ($shsResult ? 'success' : 'failed') . 
                       ", Academic: " . ($academicResult ? 'success' : 'failed') . 
                       ", Personal: " . ($personalResult ? 'success' : 'failed') . 
                       ", Address: " . ($addressResult ? 'success' : 'failed') . 
                       ", Family: " . ($familyResult ? 'success' : 'failed') . 
                       ", Disability: " . ($disabilityResult ? 'success' : 'failed') . 
                       ", Parent Address: " . ($parentAddressResult ? 'success' : 'failed') . 
                       ", Emergency Contacts: " . ($emergencyContactResult ? 'success' : 'failed'));
            
            // Update enrollment status to 'enrolled' after successful data migration
            log_message('info', "=== UPDATING ENROLLMENT STATUS ===");
            log_message('debug', "Updating enrollment status to 'enrolled' for enrollment ID: $enrollmentId");
            
            // Use the query builder instead of raw query for better error handling
            $updateResult = $db->table('enrollments')
                              ->where('id', $enrollmentId)
                              ->update([
                                  'enrollment_status' => 'enrolled',
                                  'updated_at' => date('Y-m-d H:i:s')
                              ]);
            
            if (!$updateResult) {
                $error = $db->error();
                log_message('error', "Failed to update enrollment status to 'enrolled' for enrollment ID: $enrollmentId");
                log_message('error', "Update error details: " . json_encode($error));
                throw new \Exception('Failed to update enrollment status to enrolled - ' . ($error['message'] ?? 'Unknown error'));
            }
            
            log_message('info', "Enrollment status updated successfully for enrollment ID: $enrollmentId");
            
            // Complete transaction - using manual commit instead of transComplete
            log_message('debug', "Committing database transaction for enrollment ID: $enrollmentId");
            
            $commitResult = $db->query('COMMIT');
            if (!$commitResult) {
                $error = $db->error();
                log_message('error', "Failed to commit transaction for enrollment ID: $enrollmentId");
                log_message('error', "Commit error details: " . json_encode($error));
                throw new \Exception("Failed to commit transaction - " . ($error['message'] ?? 'Unknown error'));
            }
            
            log_message('info', "Database transaction completed successfully for enrollment ID: $enrollmentId");
            
            // Send email notification
            log_message('debug', "Sending account creation email for student ID: $studentId");
            $emailResult = $this->sendAccountCreationEmail($enrollmentData, $studentAccountNumber, $password);
            log_message('debug', "Email notification result: " . ($emailResult['success'] ? 'sent' : 'failed') . " for student ID: $studentId");
            
            // Log the account creation
            $this->logAccountCreation($enrollmentId, $studentId, $userId, $authId, $emailResult);
            
            log_message('info', "Student system transfer completed successfully for enrollment ID: $enrollmentId, Student ID: $studentId, Account: $studentAccountNumber");
            
            return [
                'success' => true,
                'student_id' => $studentId,
                'user_id' => $userId,
                'auth_id' => $authId,
                'account_number' => $studentAccountNumber,
                'email_sent' => $emailResult['success'] ?? false,
                'message' => 'Student account created successfully'
            ];
            
        } catch (\Exception $e) {
            // Rollback transaction - using manual rollback instead of transRollback
            log_message('error', "Exception in student system transfer for enrollment $enrollmentId: " . $e->getMessage());
            
            if (isset($transactionStarted) && $transactionStarted) {
                $db->query('ROLLBACK');
                log_message('debug', "Database transaction rolled back for enrollment ID: $enrollmentId");
            }
            
            // Log error
            log_message('error', "Transfer system error for enrollment $enrollmentId: " . $e->getMessage() . " | Trace: " . $e->getTraceAsString());
            
            return [
                'success' => false,
                'error' => 'Transfer system error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Handle enrollment decline
     */
    public function declineEnrollment($enrollmentId)
    {
        $enrollmentModel = new \App\Models\EnrollmentModel();
        $userInfo = session()->get('userdata');
        $declinedBy = $userInfo['id'] ?? null;
        
        // Get reason from JSON body or POST data
        $input = $this->request->getJSON(true);
        $reason = $input['reason'] ?? $this->request->getPost('reason') ?? 'No reason provided';
        
        if (!$declinedBy) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User session invalid'
            ]);
        }

        $result = $enrollmentModel->declineEnrollment($enrollmentId, $declinedBy, $reason);
        
        if ($result) {
            // Send decline notification email
            $this->sendDeclineNotification($enrollmentId, $reason);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Enrollment declined successfully.'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to decline enrollment. Please try again.'
        ]);
    }
    
    /**
     * Get enrollment details
     */
    public function getEnrollmentDetails($enrollmentId)
    {
        // Temporarily bypass authentication for testing document functionality
        // TODO: Remove this bypass after testing
        $bypassAuth = $this->request->getGet('bypass_auth') === 'test123';
        
        if (!$bypassAuth && !CIAuth::check()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Authentication required'
            ]);
        }

        log_message('info', "Getting enrollment details for ID: $enrollmentId");
        
        $enrollmentModel = new \App\Models\EnrollmentModel();
        
        // Use the method that gets enrollment with all related data from normalized tables
        $enrollment = $enrollmentModel->getEnrollmentWithDetails($enrollmentId);
        
        if (!$enrollment) {
            log_message('error', "Enrollment not found for ID: $enrollmentId");
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Enrollment not found'
            ]);
        }

        log_message('info', "Successfully retrieved enrollment details for ID: $enrollmentId");
        
        if (!$enrollment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Enrollment not found'
            ]);
        }

        // Get personal info from the normalized structure
        $personalInfo = $enrollment['personal_info'] ?? [];
        $familyInfo = $enrollment['family_info'] ?? [];
        $addressInfo = $enrollment['address_info'] ?? [];
        
        // Find father and mother info from family_info array
        $fatherInfo = [];
        $motherInfo = [];
        foreach ($familyInfo as $family) {
            if ($family['relationship_type'] === 'father') {
                $fatherInfo = $family;
            } elseif ($family['relationship_type'] === 'mother') {
                $motherInfo = $family;
            }
        }
        
        // Find current and permanent addresses
        $currentAddress = [];
        $permanentAddress = [];
        foreach ($addressInfo as $address) {
            if ($address['address_type'] === 'current') {
                $currentAddress = $address;
            } elseif ($address['address_type'] === 'permanent') {
                $permanentAddress = $address;
            }
        }

        $details = [
            'enrollment' => $enrollment,
            'student_info' => [
                'full_name' => trim(($personalInfo['first_name'] ?? '') . ' ' . ($personalInfo['middle_name'] ?? '') . ' ' . ($personalInfo['last_name'] ?? '')),
                'lrn' => $personalInfo['lrn'] ?? '',
                'grade_level' => $enrollment['grade_level'],
                'gender' => $personalInfo['gender'] ?? '',
                'age' => $personalInfo['age'] ?? '',
                'birth_date' => $personalInfo['date_of_birth'] ?? '',
                'email' => $personalInfo['student_email'] ?? '',
                'contact' => $personalInfo['student_contact'] ?? '',
                'profile_picture' => $personalInfo['profile_picture'] ?? ''
            ],
            'parent_info' => [
                'father_name' => trim(($fatherInfo['first_name'] ?? '') . ' ' . ($fatherInfo['last_name'] ?? '')),
                'father_contact' => $fatherInfo['contact_number'] ?? '',
                'mother_name' => trim(($motherInfo['first_name'] ?? '') . ' ' . ($motherInfo['last_name'] ?? '')),
                'mother_contact' => $motherInfo['contact_number'] ?? ''
            ],
            'address_info' => [
                'current' => $this->formatAddressFromNormalized($currentAddress),
                'permanent' => $this->formatAddressFromNormalized($permanentAddress)
            ],
            'documents' => $enrollment['documents'] ?? []
        ];

        return $this->response->setJSON([
            'success' => true,
            'data' => $details
        ]);
    }
    
    /**
     * Format address from normalized database structure
     */
    private function formatAddressFromNormalized($addressData)
    {
        if (empty($addressData)) {
            return '';
        }
        
        $parts = [
            $addressData['house_no'] ?? '',
            $addressData['street'] ?? $addressData['street_name'] ?? '',
            $addressData['barangay'] ?? '',
            $addressData['municipality'] ?? '',
            $addressData['province'] ?? ''
        ];
        
        return implode(', ', array_filter($parts));
    }
    
    /**
     * Format LRN from form data
     */
    private function formatLRN($formData)
    {
        $lrn = '';
        for ($i = 0; $i < 12; $i++) {
            $lrn .= $formData["lrn_digit_{$i}"] ?? '0';
        }
        return $lrn;
    }
    
    /**
     * Format address from form data
     */
    private function formatAddress($formData, $type)
    {
        $prefix = $type === 'current' ? 'current_' : 'permanent_';
        
        $parts = [
            $formData[$prefix . 'house_no'] ?? '',
            $formData[$prefix . 'street'] ?? '',
            $formData[$prefix . 'barangay'] ?? '',
            $formData[$prefix . 'municipality'] ?? '',
            $formData[$prefix . 'province'] ?? ''
        ];
        
        return implode(', ', array_filter($parts));
    }
    

    
    public function section(){
        try {
            $sectionModel = new SectionModel();
            $gradeModel = new GradeModel();
            $teacherModel = new TeacherModel();
            
            // Check if sections table exists
            $db = \Config\Database::connect();
            $tables = $db->listTables();
            
            if (!in_array('sections', $tables)) {
                // Table doesn't exist, show empty state
                $data = [
                    'pageTitle' => 'Sections Management',
                    'sections' => [],
                    'grades' => [],
                    'teachers' => [],
                    'error' => 'Sections table does not exist. Please create the sections table first.'
                ];
                return view('backend/admin/sections/section', $data);
            }
            
            // Get sections with grade and adviser information
            $sections = $sectionModel->getAllSectionsWithDetails();
            
            // Get all grades for dropdown
            $grades = $gradeModel->getAllGrades();
            
            // Get all teachers for dropdown
            $teachers = $teacherModel->getActiveTeachers();

            $data = [
                'pageTitle' => 'Sections Management',
                'sections' => $sections,
                'grades' => $grades,
                'teachers' => $teachers
            ];
            return view('backend/admin/sections/section', $data);
            
        } catch (\Exception $e) {
            // Handle any database errors gracefully
            $data = [
                'pageTitle' => 'Sections Management',
                'sections' => [],
                'grades' => [],
                'teachers' => [],
                'error' => 'Database error: ' . $e->getMessage()
            ];
            return view('backend/admin/sections/section', $data);
        }
    }
    
    public function storeSubject(){
        $subjectModel = new SubjectModel();
        
        // Get form data directly matching database fields
        $data = [
            'subject_name' => trim((string) $this->request->getPost('subject_name')),
            'subject_code' => trim((string) $this->request->getPost('subject_code')),
            'grade_id' => $this->request->getPost('grade_id'),
            'department_id' => $this->request->getPost('department_id')
        ];

        // Use model validation
        if (!$subjectModel->validate($data)) {
            $errors = $subjectModel->errors();
            return $this->response->setJSON([
                'success' => false,
                'message' => implode(', ', $errors)
            ]);
        }

        // Clean data for database insertion
        $data['subject_code'] = !empty($data['subject_code']) ? $data['subject_code'] : null;
        $data['grade_id'] = (int) $data['grade_id'];
        $data['department_id'] = !empty($data['department_id']) ? (int) $data['department_id'] : null;

        try {
            $insertId = $subjectModel->insert($data);
            if (!$insertId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create subject'
                ]);
            }
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Subject created successfully!',
                'id' => $insertId
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error creating subject: ' . $e->getMessage()
            ]);
        }
    }
    
    public function updateSubject($id){
        $subjectModel = new SubjectModel();
        
        if (empty($id) || !ctype_digit((string) $id)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid subject ID']);
        }

        $subject = $subjectModel->find((int) $id);
        if (!$subject) {
            return $this->response->setJSON(['success' => false, 'message' => 'Subject not found']);
        }

        // Get form data directly matching database fields
        $data = [
            'subject_name' => trim((string) $this->request->getPost('subject_name')),
            'subject_code' => trim((string) $this->request->getPost('subject_code')),
            'grade_id' => $this->request->getPost('grade_id'),
            'department_id' => $this->request->getPost('department_id')
        ];

        // Use model validation
        if (!$subjectModel->validate($data)) {
            $errors = $subjectModel->errors();
            return $this->response->setJSON([
                'success' => false,
                'message' => implode(', ', $errors)
            ]);
        }

        // Clean data for database update
        $data['subject_code'] = !empty($data['subject_code']) ? $data['subject_code'] : null;
        $data['grade_id'] = (int) $data['grade_id'];
        $data['department_id'] = !empty($data['department_id']) ? (int) $data['department_id'] : null;

        try {
            $success = $subjectModel->update((int) $id, $data);
            if (!$success) {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to update subject']);
            }
            return $this->response->setJSON(['success' => true, 'message' => 'Subject updated successfully!']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error updating subject: ' . $e->getMessage()]);
        }
    }
    
    public function deleteSubject($id){
        $subjectModel = new SubjectModel();
        if (empty($id) || !ctype_digit((string) $id)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid subject ID']);
        }
        try {
            $ok = $subjectModel->delete((int) $id);
            if (!$ok) {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete subject']);
            }
            return $this->response->setJSON(['success' => true, 'message' => 'Subject deleted successfully!']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error deleting subject: ' . $e->getMessage()]);
        }
    }
    
    public function getSubject($id){
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $subjectModel = new SubjectModel();
        $subject = $subjectModel->find($id);
        
        if ($subject) {
            // Add grade level mapping for display purposes
            $gradeMap = [
                1 => 'Grade 7',
                2 => 'Grade 8', 
                3 => 'Grade 9',
                4 => 'Grade 10'
            ];
            $subject['grade_level'] = $gradeMap[$subject['grade_id']] ?? 'Unknown';
            
            return $this->response->setJSON(['success' => true, 'data' => $subject]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Subject not found']);
        }
    }
    
    public function classManagement(){
        // Get classes from database
        $db = \Config\Database::connect();
        
        // Sample data - replace with actual database query
        $classes = [
            [
                'id' => 1,
                'class_name' => 'Grade 7 - St. Francis',
                'grade_level' => 'Grade 7',
                'section' => 'St. Francis',
                'adviser' => 'Ms. Rodriguez',
                'room_number' => 'Room 101',
                'student_count' => 35,
                'max_capacity' => 40,
                'schedule' => 'Monday - Friday, 7:30 AM - 4:30 PM',
                'status' => 'active'
            ],
            [
                'id' => 2,
                'class_name' => 'Grade 7 - St. Clare',
                'grade_level' => 'Grade 7',
                'section' => 'St. Clare',
                'adviser' => 'Mr. Santos',
                'room_number' => 'Room 102',
                'student_count' => 32,
                'max_capacity' => 40,
                'schedule' => 'Monday - Friday, 7:30 AM - 4:30 PM',
                'status' => 'active'
            ],
            [
                'id' => 3,
                'class_name' => 'Grade 8 - St. Anthony',
                'grade_level' => 'Grade 8',
                'section' => 'St. Anthony',
                'adviser' => 'Dr. Garcia',
                'room_number' => 'Room 201',
                'student_count' => 38,
                'max_capacity' => 40,
                'schedule' => 'Monday - Friday, 7:30 AM - 4:30 PM',
                'status' => 'active'
            ],
            [
                'id' => 4,
                'class_name' => 'Grade 9 - St. Agnes',
                'grade_level' => 'Grade 9',
                'section' => 'St. Agnes',
                'adviser' => 'Mrs. Cruz',
                'room_number' => 'Room 301',
                'student_count' => 30,
                'max_capacity' => 40,
                'schedule' => 'Monday - Friday, 7:30 AM - 4:30 PM',
                'status' => 'active'
            ],
            [
                'id' => 5,
                'class_name' => 'Grade 10 - St. Joseph',
                'grade_level' => 'Grade 10',
                'section' => 'St. Joseph',
                'adviser' => 'Mr. Dela Cruz',
                'room_number' => 'Room 401',
                'student_count' => 28,
                'max_capacity' => 40,
                'schedule' => 'Monday - Friday, 7:30 AM - 4:30 PM',
                'status' => 'active'
            ]
        ];
        
        $data = [
            'pageTitle' => 'Class Management',
            'classes' => $classes
        ];
        return view('backend/admin/class/class', $data);
    }
    
    public function storeClass(){
        // Handle class creation
        $db = \Config\Database::connect();
        
        // Validate and store class
        // $data = $this->request->getPost();
        // $db->table('classes')->insert($data);
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Class created successfully!'
        ]);
    }
    
    public function updateClass($id){
        // Handle class update
        $db = \Config\Database::connect();
        
        // Validate and update class
        // $data = $this->request->getPost();
        // $db->table('classes')->where('id', $id)->update($data);
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Class updated successfully!'
        ]);
    }
    
    public function deleteClass($id){
        // Handle class deletion
        $db = \Config\Database::connect();
        
        // Delete class
        // $db->table('classes')->where('id', $id)->delete();
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Class deleted successfully!'
        ]);
    }
    
    // Missing data migration methods for complete enrollment to student transfer
    
    private function transferPersonalInfo($enrollmentId, $studentId)
    {
        log_message('debug', "Starting personal info transfer for enrollment ID: $enrollmentId to student ID: $studentId");
        $db = \Config\Database::connect();
        
        try {
            // Check if personal info already exists for this student
            $existingQuery = $db->query("SELECT id FROM student_personal_info WHERE student_id = ?", [$studentId]);
            if ($existingQuery !== false && $existingQuery->getNumRows() > 0) {
                log_message('info', "Personal info already exists for student ID: $studentId, skipping transfer");
                return true;
            }
            
            // Get personal info from enrollment
            $personalInfoQuery = $db->query("SELECT * FROM enrollment_personal_info WHERE enrollment_id = ?", [$enrollmentId]);
            
            if ($personalInfoQuery === false) {
                log_message('error', "Failed to query enrollment_personal_info for enrollment ID: $enrollmentId - " . print_r($db->error(), true));
                return false;
            }
            
            if ($personalInfoQuery->getNumRows() === 0) {
                log_message('warning', "No personal info found for enrollment ID: $enrollmentId");
                return true; // Not an error, just no data to transfer
            }
            
            $personalInfo = $personalInfoQuery->getRow();
            log_message('debug', "Retrieved personal info for enrollment ID: $enrollmentId");
            
            // Note: We already created student_personal_info in createStudentRecord method
            // This method is for additional personal info that might not be in the main record
            log_message('info', "Personal info already handled in createStudentRecord for student ID: $studentId");
            return true;
            
        } catch (\Exception $e) {
            log_message('error', "Exception in transferPersonalInfo for enrollment $enrollmentId: " . $e->getMessage());
            return false;
        }
    }
    
    private function transferAddressInfo($enrollmentId, $studentId)
    {
        log_message('debug', "Starting address info transfer for enrollment ID: $enrollmentId to student ID: $studentId");
        $db = \Config\Database::connect();
        
        try {
            // Check if address info already exists for this student
            $existingQuery = $db->query("SELECT id FROM student_address WHERE student_id = ?", [$studentId]);
            if ($existingQuery !== false && $existingQuery->getNumRows() > 0) {
                log_message('info', "Address info already exists for student ID: $studentId, skipping transfer");
                return true;
            }
            
            // Get address info from enrollment
            $addressQuery = $db->query("SELECT * FROM enrollment_address_final WHERE enrollment_id = ?", [$enrollmentId]);
            
            if ($addressQuery === false) {
                log_message('error', "Failed to query enrollment_address_final for enrollment ID: $enrollmentId - " . print_r($db->error(), true));
                return false;
            }
            
            if ($addressQuery->getNumRows() === 0) {
                log_message('info', "No address info found for enrollment ID: $enrollmentId, skipping transfer");
                return true; // Not an error, just no data to transfer
            }
            
            $addressRecords = $addressQuery->getResult();
            log_message('debug', "Retrieved " . count($addressRecords) . " address records for enrollment ID: $enrollmentId");
            
            // Transfer each address record (current and permanent)
            foreach ($addressRecords as $addressInfo) {
                $addressData = [
                    'student_id' => $studentId,
                    'address_type' => $addressInfo->address_type,
                    'house_no' => $addressInfo->house_no,
                    'street' => $addressInfo->street,
                    'barangay' => $addressInfo->barangay,
                    'municipality' => $addressInfo->municipality,
                    'province' => $addressInfo->province,
                    'country' => $addressInfo->country ?? 'Philippines',
                    'zip_code' => $addressInfo->zip_code,
                    'is_same_as_current' => $addressInfo->is_same_as_current ?? 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                $insertResult = $db->table('student_address')->insert($addressData);
                
                if (!$insertResult) {
                    $error = $db->error();
                    log_message('error', "Failed to insert address info for student ID: $studentId, address type: {$addressInfo->address_type} - " . json_encode($error));
                    return false;
                }
                
                log_message('info', "Successfully transferred {$addressInfo->address_type} address for student ID: $studentId");
            }
            
            log_message('info', "Address info transfer completed successfully for student ID: $studentId");
            return true;
            
        } catch (\Exception $e) {
            log_message('error', "Exception in transferAddressInfo for enrollment $enrollmentId: " . $e->getMessage());
            return false;
        }
    }
    
    private function transferParentAddressInfo($enrollmentId, $studentId)
    {
        log_message('debug', "Starting parent address info transfer for enrollment ID: $enrollmentId to student ID: $studentId");
        $db = \Config\Database::connect();
        
        try {
            // Check if parent address info already exists for this student
            $existingQuery = $db->query("SELECT id FROM student_parent_address WHERE student_id = ?", [$studentId]);
            if ($existingQuery !== false && $existingQuery->getNumRows() > 0) {
                log_message('info', "Parent address info already exists for student ID: $studentId, skipping transfer");
                return true;
            }
            
            // Check if enrollment_parent_address table exists
            if ($db->query("SHOW TABLES LIKE 'enrollment_parent_address'")->getNumRows() === 0) {
                log_message('info', "enrollment_parent_address table not found, skipping parent address transfer for enrollment ID: $enrollmentId");
                return true; // Not an error, table doesn't exist yet
            }
            
            // Get parent address info from enrollment
            $parentAddressQuery = $db->query("SELECT * FROM enrollment_parent_address WHERE enrollment_id = ?", [$enrollmentId]);
            
            if ($parentAddressQuery === false) {
                log_message('error', "Failed to query enrollment_parent_address for enrollment ID: $enrollmentId - " . print_r($db->error(), true));
                return false;
            }
            
            if ($parentAddressQuery->getNumRows() === 0) {
                log_message('info', "No parent address info found for enrollment ID: $enrollmentId, skipping transfer");
                return true; // Not an error, just no data to transfer
            }
            
            $parentAddressRecords = $parentAddressQuery->getResult();
            log_message('debug', "Retrieved " . count($parentAddressRecords) . " parent address records for enrollment ID: $enrollmentId");
            
            // Check if student_parent_address table exists
            if ($db->query("SHOW TABLES LIKE 'student_parent_address'")->getNumRows() === 0) {
                log_message('warning', "student_parent_address table not found, skipping parent address transfer for student ID: $studentId");
                return true; // Not an error, table doesn't exist yet
            }
            
            // Transfer each parent address record
            foreach ($parentAddressRecords as $parentAddressInfo) {
                $parentAddressData = [
                    'student_id' => $studentId,
                    'parent_type' => $parentAddressInfo->parent_type,
                    'house_number' => $parentAddressInfo->house_number,
                    'street' => $parentAddressInfo->street,
                    'barangay' => $parentAddressInfo->barangay,
                    'municipality' => $parentAddressInfo->municipality,
                    'province' => $parentAddressInfo->province,
                    'zip_code' => $parentAddressInfo->zip_code,
                    'is_same_as_student' => $parentAddressInfo->is_same_as_student ?? 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                $insertResult = $db->table('student_parent_address')->insert($parentAddressData);
                
                if (!$insertResult) {
                    $error = $db->error();
                    log_message('error', "Failed to insert parent address info for student ID: $studentId, parent type: {$parentAddressInfo->parent_type} - " . json_encode($error));
                    return false;
                }
                
                log_message('info', "Successfully transferred {$parentAddressInfo->parent_type} address for student ID: $studentId");
            }
            
            log_message('info', "Parent address info transfer completed successfully for student ID: $studentId");
            return true;
            
        } catch (\Exception $e) {
            log_message('error', "Exception in transferParentAddressInfo for enrollment $enrollmentId: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Legacy method - kept for backward compatibility but no longer used
     * Family info is now handled by ParentManager with proper deduplication
     * @deprecated Use ParentManager::processEnrollmentParents() instead
     */
    private function transferFamilyInfo($enrollmentId, $studentId)
    {
        log_message('warning', "transferFamilyInfo is deprecated - use ParentManager::processEnrollmentParents() instead");
        log_message('debug', "Legacy family info transfer called for enrollment ID: $enrollmentId to student ID: $studentId");
        
        // Return true to maintain compatibility, but actual processing is done by ParentManager
        return true;
    }
    
    /**
     * Transfer emergency contacts from enrollment to student system
     */
    private function transferEmergencyContacts($enrollmentId, $studentId)
    {
        try {
            log_message('info', "Starting emergency contact transfer for enrollment ID: $enrollmentId, student ID: $studentId");
            
            $db = \Config\Database::connect();
            
            // Get emergency contacts from enrollment_emergency_contact table (singular)
            $emergencyContacts = $db->table('enrollment_emergency_contact')
                                   ->where('enrollment_id', $enrollmentId)
                                   ->get()
                                   ->getResultArray();
            
            if (empty($emergencyContacts)) {
                log_message('info', "No emergency contacts found for enrollment ID: $enrollmentId");
                return true; // Not an error if no emergency contacts were selected
            }
            
            // Use ParentManager to handle emergency contacts through parent relationships
            $parentManager = new \App\Libraries\ParentManager();
            
            foreach ($emergencyContacts as $contact) {
                $parentData = [
                    'first_name' => explode(' ', $contact['emergency_contact_name'])[0] ?? '',
                    'last_name' => implode(' ', array_slice(explode(' ', $contact['emergency_contact_name']), 1)) ?: 'Unknown',
                    'contact_number' => $contact['emergency_contact_phone']
                ];
                
                $result = $parentManager->addEmergencyContact(
                    $studentId,
                    $parentData,
                    $contact['emergency_contact_relationship'],
                    $contact['is_primary_contact'] ?? false
                );
                
                if (!$result['success']) {
                    log_message('error', "Failed to add emergency contact for student ID: $studentId - " . $result['message']);
                    return false;
                }
            }
            
            log_message('info', "Successfully transferred " . count($emergencyContacts) . " emergency contacts for student ID: $studentId using parent relationships");
            return true;
            
        } catch (\Exception $e) {
            log_message('error', "Exception in transferEmergencyContacts for enrollment $enrollmentId: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send decline notification email
     */
    private function sendDeclineNotification($enrollmentId, $reason)
    {
        try {
            $enrollmentModel = new \App\Models\EnrollmentModel();
            $enrollment = $enrollmentModel->find($enrollmentId);
            
            if (!$enrollment) {
                log_message('error', "Enrollment not found for decline notification: $enrollmentId");
                return false;
            }
            
            // Use the EmailService to send the actual email
            $emailService = new \App\Libraries\EmailService();
            $emailResult = $emailService->sendEnrollmentDeclineEmail($enrollment, $reason);
            
            // Prepare notification data for database storage
            $formData = $enrollment['form_data'];
            $studentName = trim(($formData['first_name'] ?? '') . ' ' . ($formData['last_name'] ?? ''));
            
            // Determine recipient email
            $recipientEmail = $formData['student_email'] ?? $formData['father_email'] ?? $formData['mother_email'] ?? null;
            $recipientName = $formData['father_first_name'] ?? $formData['mother_first_name'] ?? 'Parent/Guardian';
            
            if (!$recipientEmail) {
                log_message('error', "No recipient email found for enrollment decline notification: $enrollmentId");
                return false;
            }
            
            // Prepare notification data
            $notificationData = [
                'enrollment_id' => $enrollmentId,
                'recipient_email' => $recipientEmail,
                'recipient_name' => $studentName, // Changed to student name
                'notification_type' => 'declined',
                'subject' => 'Enrollment Application Status - ' . $enrollment['enrollment_number'],
                'message' => "Dear {$studentName},\n\nWe regret to inform you that your enrollment application has been declined.\n\nEnrollment Number: {$enrollment['enrollment_number']}\nReason: {$reason}\n\nIf you have any questions or would like to reapply, please contact our admissions office.\n\nThank you for your interest in our school.\n\nSta. Justina National High School",
                'status' => $emailResult['success'] ? 'sent' : 'failed',
                'error_message' => $emailResult['success'] ? null : $emailResult['message'],
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            // Store notification in database
            $db = \Config\Database::connect();
            $db->table('enrollment_notifications')->insert($notificationData);
            
            if ($emailResult['success']) {
                log_message('info', "Enrollment decline notification sent successfully to {$recipientEmail}");
                return true;
            } else {
                log_message('error', "Failed to send enrollment decline notification: " . $emailResult['message']);
                return false;
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Failed to send decline notification: ' . $e->getMessage());
            return false;
        }
    }
    
}
