<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EnrollmentModel;
use App\Libraries\CIAuth;
use App\Models\User;

class DebugController extends BaseController
{
    public function testApproval()
    {
        // Set content type to plain text for better readability
        $this->response->setContentType('text/plain');
        
        echo "=== ENROLLMENT APPROVAL DEBUG TEST ===\n\n";
        
        try {
            $db = \Config\Database::connect();
            
            // 1. Create a test enrollment
            echo "1. Creating test enrollment...\n";
            
            $enrollmentModel = new EnrollmentModel();
            
            // Create test enrollment (manually set enrollment_number to avoid callback issues)
            $enrollmentData = [
                'enrollment_number' => 'TEST-' . date('YmdHis') . '-' . rand(1000, 9999),
                'school_year' => '2024-2025',
                'grade_level' => 'Grade 7',
                'enrollment_type' => 'new',
                'enrollment_status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $enrollmentId = $enrollmentModel->insert($enrollmentData);
            
            if (!$enrollmentId) {
                throw new \Exception("Failed to create test enrollment");
            }
            
            echo "   ✓ Test enrollment created with ID: $enrollmentId\n";
            
            // 2. Create test personal info
            echo "2. Creating test personal info...\n";
            
            $personalData = [
                'enrollment_id' => $enrollmentId,
                'lrn' => 'TEST' . date('YmdHis'),
                'first_name' => 'Test',
                'middle_name' => 'Debug',
                'last_name' => 'Student',
                'gender' => 'Male',
                'date_of_birth' => '2010-01-01',
                'place_of_birth' => 'Test City',
                'age' => 14,
                'mother_tongue' => 'Filipino',
                'student_email' => 'test@example.com',
                'student_contact' => '09123456789',
                'indigenous_people' => 'No',
                'fourps_beneficiary' => 'No'
            ];
            
            $personalResult = $db->table('enrollment_personal_info')->insert($personalData);
            
            if (!$personalResult) {
                throw new \Exception("Failed to create test personal info");
            }
            
            echo "   ✓ Test personal info created\n";
            
            // 3. Create test family info
            echo "3. Creating test family info...\n";
            
            $familyData = [
                [
                    'enrollment_id' => $enrollmentId,
                    'relationship_type' => 'father',
                    'first_name' => 'Test',
                    'middle_name' => 'Father',
                    'last_name' => 'Parent',
                    'contact_number' => '09123456788'
                ],
                [
                    'enrollment_id' => $enrollmentId,
                    'relationship_type' => 'mother',
                    'first_name' => 'Test',
                    'middle_name' => 'Mother',
                    'last_name' => 'Parent',
                    'contact_number' => '09123456787'
                ]
            ];
            
            foreach ($familyData as $family) {
                $familyResult = $db->table('enrollment_family_info')->insert($family);
                if (!$familyResult) {
                    throw new \Exception("Failed to create test family info");
                }
            }
            
            echo "   ✓ Test family info created\n";
            
            // 4. Create test address info
            echo "4. Creating test address info...\n";
            
            $addressData = [
                'enrollment_id' => $enrollmentId,
                'parent_type' => 'father',
                'is_same_as_student' => 0,
                'house_number' => '123',
                'street' => 'Test Street',
                'barangay' => 'Test Barangay',
                'municipality' => 'Test City',
                'province' => 'Test Province',
                'zip_code' => '1234',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $addressResult = $db->table('enrollment_parent_address')->insert($addressData);
            
            if (!$addressResult) {
                throw new \Exception("Failed to create test address info");
            }
            
            echo "   ✓ Test address info created\n";
            
            // 5. Set up authentication for testing
            echo "5. Setting up authentication...\n";
            
            $userModel = new User();
            $adminUser = $userModel->where('role', 'admin')->first();
            
            if (!$adminUser) {
                throw new \Exception("No admin user found for testing");
            }
            
            // Temporarily set authentication
            CIAuth::setCIAuth($adminUser);
            echo "   ✓ Authentication set for admin user: " . $adminUser['name'] . "\n";
            
            // 6. Test the approval process
            echo "6. Testing enrollment approval...\n";
            
            $adminController = new \App\Controllers\AdminController();
            
            // Set up a mock request to avoid getMethod() error
            $request = \Config\Services::request();
            $adminController->initController($request, $this->response, \Config\Services::logger());
            
            // Capture any output from the approval process
            ob_start();
            
            try {
                $result = $adminController->approveEnrollment($enrollmentId);
                $output = ob_get_clean();
                
                echo "   Approval process completed\n";
                echo "   Output captured: " . strlen($output) . " characters\n";
                
                if ($output) {
                    echo "   Output content: " . substr($output, 0, 500) . "\n";
                }
                
            } catch (\Exception $e) {
                ob_end_clean();
                echo "   ❌ Approval process failed with exception: " . $e->getMessage() . "\n";
                echo "   Stack trace:\n" . $e->getTraceAsString() . "\n";
            }
            
            // 7. Check the results
            echo "7. Checking results...\n";
            
            // Check enrollment status
            $updatedEnrollment = $enrollmentModel->find($enrollmentId);
            echo "   Enrollment status: " . ($updatedEnrollment['enrollment_status'] ?? 'unknown') . "\n";
            
            // Check if student record was created
            $studentQuery = $db->query("SELECT * FROM students WHERE enrollment_id = ?", [$enrollmentId]);
            $student = $studentQuery->getRowArray();
            
            if ($student) {
                echo "   ✓ Student record created with ID: " . $student['id'] . "\n";
                echo "   Student account number: " . ($student['account_number'] ?? 'not set') . "\n";
                
                // Check related records
                $personalInfoQuery = $db->query("SELECT * FROM student_personal_info WHERE student_id = ?", [$student['id']]);
                $personalInfo = $personalInfoQuery->getRowArray();
                echo "   Personal info transferred: " . ($personalInfo ? 'Yes' : 'No') . "\n";
                
                $authQuery = $db->query("SELECT * FROM student_auth WHERE student_id = ?", [$student['id']]);
                $auth = $authQuery->getRowArray();
                echo "   Authentication record created: " . ($auth ? 'Yes' : 'No') . "\n";
                
                // Check for user record using account_number from students table
                $userQuery = $db->query("SELECT * FROM users WHERE account_no = ?", [$student['account_number']]);
                $user = $userQuery->getRowArray();
                echo "   User record created: " . ($user ? 'Yes' : 'No') . "\n";
                
            } else {
                echo "   ❌ No student record found\n";
            }
            
            // 8. Clean up
            echo "8. Cleaning up test data...\n";
            
            if (isset($student['id'])) {
                // Clean up student-related records
                $db->query("DELETE FROM student_personal_info WHERE student_id = ?", [$student['id']]);
                $db->query("DELETE FROM student_auth WHERE student_id = ?", [$student['id']]);
                $db->query("DELETE FROM students WHERE id = ?", [$student['id']]);
                if (isset($student['user_id'])) {
                    $db->query("DELETE FROM users WHERE id = ?", [$student['user_id']]);
                }
            }
            
            // Clean up enrollment records
            $db->query("DELETE FROM enrollment_parent_address WHERE enrollment_id = ?", [$enrollmentId]);
            $db->query("DELETE FROM enrollment_family_info WHERE enrollment_id = ?", [$enrollmentId]);
            $db->query("DELETE FROM enrollment_personal_info WHERE enrollment_id = ?", [$enrollmentId]);
            $db->query("DELETE FROM enrollments WHERE id = ?", [$enrollmentId]);
            
            echo "   ✓ Test data cleaned up\n";
            
            // Clear authentication
            CIAuth::forget();
            
            echo "\n=== TEST COMPLETED ===\n";
            
        } catch (\Exception $e) {
            echo "\n❌ TEST FAILED: " . $e->getMessage() . "\n";
            echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
        }
    }
}