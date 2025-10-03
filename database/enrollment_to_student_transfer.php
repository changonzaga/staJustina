<?php
// =====================================================
// ENROLLMENT TO STUDENT TRANSFER SCRIPT
// Handles approved enrollment applications and creates
// student records with automatic account generation
// =====================================================

// Database connection
$host = 'localhost';
$dbname = 'stajustina_db';
$username = 'root';
$password = '';

class EnrollmentToStudentTransfer {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Generate unique student account number
     */
    private function generateStudentAccountNumber() {
        $year = date('y');
        
        // Get the next sequence number
        $stmt = $this->pdo->query("
            SELECT COALESCE(MAX(CAST(SUBSTRING(student_account_number, 4) AS UNSIGNED)), 0) + 1 as next_seq
            FROM students 
            WHERE student_account_number LIKE 'STA{$year}%'
        ");
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $sequence = str_pad($result['next_seq'], 6, '0', STR_PAD_LEFT);
        
        return "STA{$year}{$sequence}";
    }
    
    /**
     * Generate secure temporary password
     */
    private function generateTempPassword($length = 12) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $password;
    }
    
    /**
     * Transfer approved enrollment to student system
     */
    public function transferEnrollment($enrollmentId) {
        try {
            $this->pdo->beginTransaction();
            
            // Get enrollment data
            $enrollmentData = $this->getEnrollmentData($enrollmentId);
            if (!$enrollmentData) {
                throw new Exception("Enrollment record not found: $enrollmentId");
            }
            
            // Check if already transferred
            $existingStudent = $this->pdo->prepare("
                SELECT id FROM students WHERE enrollment_id = ?
            ");
            $existingStudent->execute([$enrollmentId]);
            
            if ($existingStudent->rowCount() > 0) {
                throw new Exception("Enrollment already transferred to student system");
            }
            
            // Generate account details
            $accountNumber = $this->generateStudentAccountNumber();
            $tempPassword = $this->generateTempPassword();
            $passwordHash = password_hash($tempPassword, PASSWORD_DEFAULT);
            
            // Create core student record
            $studentId = $this->createStudentRecord($enrollmentData, $accountNumber, $enrollmentId);
            
            // Create authentication record
            $this->createStudentAuth($studentId, $enrollmentData, $accountNumber, $passwordHash, $tempPassword);
            
            // Create personal info record
            $this->createStudentPersonalInfo($studentId, $enrollmentData);
            
            // Process parent information using new parent-centric system
            $this->processParentInformation($studentId, $enrollmentId);
            
            // Create address record
            $this->createStudentAddress($studentId, $enrollmentData);
            
            // Create emergency contacts if available
            $this->createEmergencyContacts($studentId, $enrollmentData);
            
            // Create notification records
            $this->createNotificationRecords($studentId, $enrollmentData, $accountNumber, $tempPassword);
            
            // Update enrollment status
            $this->updateEnrollmentStatus($enrollmentId, 'transferred');
            
            $this->pdo->commit();
            
            return [
                'success' => true,
                'student_id' => $studentId,
                'account_number' => $accountNumber,
                'temp_password' => $tempPassword,
                'message' => 'Student record created successfully'
            ];
            
        } catch (Exception $e) {
            $this->pdo->rollback();
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Get enrollment data from multiple tables
     */
    private function getEnrollmentData($enrollmentId) {
        $stmt = $this->pdo->prepare("
            SELECT 
                e.*,
                epi.student_name,
                epi.student_email,
                epi.student_contact,
                epi.date_of_birth,
                epi.gender,
                epi.parent_name,
                epi.parent_contact,
                epi.parent_email,
                eaf.barangay,
                eaf.municipality_city,
                eaf.province,
                eaf.region
            FROM enrollments e
            LEFT JOIN enrollment_personal_info epi ON e.id = epi.enrollment_id
            LEFT JOIN enrollment_address_final eaf ON e.id = eaf.enrollment_id
            WHERE e.id = ? AND e.enrollment_status = 'approved'
        ");
        
        $stmt->execute([$enrollmentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Create core student record
     */
    private function createStudentRecord($data, $accountNumber, $enrollmentId) {
        $stmt = $this->pdo->prepare("
            INSERT INTO students (
                student_account_number, lrn, enrollment_id, grade_level, 
                academic_year, enrollment_date, student_status
            ) VALUES (?, ?, ?, ?, ?, CURDATE(), 'active')
        ");
        
        $stmt->execute([
            $accountNumber,
            $data['lrn'],
            $enrollmentId,
            $data['grade_level'],
            date('Y') . '-' . (date('Y') + 1)
        ]);
        
        return $this->pdo->lastInsertId();
    }
    
    /**
     * Create student authentication record
     */
    private function createStudentAuth($studentId, $data, $accountNumber, $passwordHash, $tempPassword) {
        $stmt = $this->pdo->prepare("
            INSERT INTO student_auth (
                student_id, username, email, password_hash, temp_password
            ) VALUES (?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $studentId,
            $accountNumber, // Use account number as username
            $data['student_email'],
            $passwordHash,
            $tempPassword
        ]);
    }
    
    /**
     * Create student personal information record
     */
    private function createStudentPersonalInfo($studentId, $data) {
        // Parse full name
        $nameParts = explode(' ', trim($data['student_name']));
        $firstName = $nameParts[0] ?? '';
        $lastName = end($nameParts) ?? '';
        $middleName = count($nameParts) > 2 ? implode(' ', array_slice($nameParts, 1, -1)) : null;
        
        $stmt = $this->pdo->prepare("
            INSERT INTO student_personal_info (
                student_id, first_name, middle_name, last_name, 
                date_of_birth, gender, nationality, citizenship
            ) VALUES (?, ?, ?, ?, ?, ?, 'Filipino', 'Filipino')
        ");
        
        $stmt->execute([
            $studentId,
            $firstName,
            $middleName,
            $lastName,
            $data['date_of_birth'],
            $data['gender']
        ]);
    }
    
    /**
     * Process parent information using the new parent-centric system
     */
    private function processParentInformation($studentId, $enrollmentId) {
        // Load CodeIgniter's ParentManager library
        require_once APPPATH . 'Libraries/ParentManager.php';
        
        $parentManager = new \App\Libraries\ParentManager();
        $result = $parentManager->processEnrollmentParents($enrollmentId, $studentId);
        
        if (!$result['success']) {
            throw new Exception('Failed to process parent information: ' . $result['error']);
        }
        
        return true;
    }

    /**
     * Legacy method - kept for backward compatibility but no longer used
     * @deprecated Use processParentInformation() instead
     */
    private function createStudentFamilyInfo($studentId, $data) {
        // This method is deprecated - parent processing is now handled by ParentManager
        // Return without doing anything to avoid duplicate data
        return true;
    }
    
    /**
     * Create student address record
     */
    private function createStudentAddress($studentId, $data) {
        if (empty($data['barangay'])) return;
        
        $stmt = $this->pdo->prepare("
            INSERT INTO student_address (
                student_id, address_type, is_primary, barangay,
                municipality_city, province, region, country
            ) VALUES (?, 'current', TRUE, ?, ?, ?, ?, 'Philippines')
        ");
        
        $stmt->execute([
            $studentId,
            $data['barangay'],
            $data['municipality_city'],
            $data['province'],
            $data['region']
        ]);
    }
    
    /**
     * Create emergency contacts
     */
    private function createEmergencyContacts($studentId, $data) {
        if (empty($data['parent_name']) || empty($data['parent_contact'])) return;
        
        // Use parent as primary emergency contact
        $parentParts = explode(' ', trim($data['parent_name']));
        $parentFirstName = $parentParts[0] ?? '';
        $parentLastName = end($parentParts) ?? '';
        
        $stmt = $this->pdo->prepare("
            INSERT INTO student_emergency_contacts (
                student_id, priority_order, relationship, first_name, last_name,
                phone_primary, email, is_family_member, authorized_pickup
            ) VALUES (?, 1, 'Parent/Guardian', ?, ?, ?, ?, TRUE, TRUE)
        ");
        
        $stmt->execute([
            $studentId,
            $parentFirstName,
            $parentLastName,
            $data['parent_contact'],
            $data['parent_email']
        ]);
    }
    
    /**
     * Create notification records for email and SMS
     */
    private function createNotificationRecords($studentId, $data, $accountNumber, $tempPassword) {
        // Email notification
        if (!empty($data['student_email'])) {
            $emailMessage = $this->generateEmailMessage($data['student_name'], $accountNumber, $tempPassword);
            
            $stmt = $this->pdo->prepare("
                INSERT INTO student_notifications (
                    student_id, notification_type, delivery_method,
                    recipient_email, subject, message, priority
                ) VALUES (?, 'account_created', 'email', ?, ?, ?, 'high')
            ");
            
            $stmt->execute([
                $studentId,
                $data['student_email'],
                'Welcome to Sta. Justina National High School - Account Created',
                $emailMessage
            ]);
        }
        
        // SMS notification
        if (!empty($data['student_contact'])) {
            $smsMessage = $this->generateSMSMessage($data['student_name'], $accountNumber, $tempPassword);
            
            $stmt = $this->pdo->prepare("
                INSERT INTO student_notifications (
                    student_id, notification_type, delivery_method,
                    recipient_phone, message, priority
                ) VALUES (?, 'account_created', 'sms', ?, ?, 'high')
            ");
            
            $stmt->execute([
                $studentId,
                $data['student_contact'],
                $smsMessage
            ]);
        }
    }
    
    /**
     * Generate email message
     */
    private function generateEmailMessage($studentName, $accountNumber, $tempPassword) {
        return "
Dear $studentName,

Congratulations! Your enrollment application has been approved and your student account has been created.

Your Account Details:
- Account Number: $accountNumber
- Username: $accountNumber
- Temporary Password: $tempPassword

Please log in to the student portal using these credentials and change your password immediately.

Student Portal: [PORTAL_URL]

Welcome to Sta. Justina National High School!

Best regards,
Sta. Justina National High School
Admissions Office
        ";
    }
    
    /**
     * Generate SMS message
     */
    private function generateSMSMessage($studentName, $accountNumber, $tempPassword) {
        return "Hi $studentName! Your enrollment is approved. Account: $accountNumber, Password: $tempPassword. Please login and change password. Welcome to Sta. Justina NHS!";
    }
    
    /**
     * Update enrollment status
     */
    private function updateEnrollmentStatus($enrollmentId, $status) {
        $stmt = $this->pdo->prepare("
            UPDATE enrollments 
            SET enrollment_status = ?, updated_at = NOW() 
            WHERE id = ?
        ");
        
        $stmt->execute([$status, $enrollmentId]);
    }
    
    /**
     * Get pending notifications for sending
     */
    public function getPendingNotifications($limit = 10) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM student_notifications 
            WHERE status = 'pending' 
            ORDER BY priority DESC, created_at ASC 
            LIMIT ?
        ");
        
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Update notification status
     */
    public function updateNotificationStatus($notificationId, $status, $errorMessage = null) {
        $stmt = $this->pdo->prepare("
            UPDATE student_notifications 
            SET status = ?, error_message = ?, updated_at = NOW()
            WHERE id = ?
        ");
        
        $stmt->execute([$status, $errorMessage, $notificationId]);
    }
}

// =====================================================
// USAGE EXAMPLE AND TESTING
// =====================================================

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $transfer = new EnrollmentToStudentTransfer($pdo);
    
    echo "=== ENROLLMENT TO STUDENT TRANSFER SYSTEM ===\n\n";
    
    // Check for approved enrollments
    $approvedEnrollments = $pdo->query("
        SELECT e.id, epi.student_name, e.grade_level, e.enrollment_status
        FROM enrollments e
        LEFT JOIN enrollment_personal_info epi ON e.id = epi.enrollment_id
        WHERE e.enrollment_status = 'approved'
        AND e.id NOT IN (SELECT enrollment_id FROM students WHERE enrollment_id IS NOT NULL)
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($approvedEnrollments)) {
        echo "📋 No approved enrollments found for transfer.\n";
        echo "\n--- CREATING TEST ENROLLMENT FOR DEMONSTRATION ---\n";
        
        // Create a test enrollment for demonstration
        $testEnrollmentId = $this->createTestEnrollment($pdo);
        if ($testEnrollmentId) {
            echo "✅ Test enrollment created with ID: $testEnrollmentId\n";
            
            // Transfer the test enrollment
            $result = $transfer->transferEnrollment($testEnrollmentId);
            
            if ($result['success']) {
                echo "\n🎉 SUCCESS: Test enrollment transferred successfully!\n";
                echo "📋 Student ID: {$result['student_id']}\n";
                echo "📋 Account Number: {$result['account_number']}\n";
                echo "📋 Temporary Password: {$result['temp_password']}\n";
            } else {
                echo "\n❌ ERROR: {$result['error']}\n";
            }
        }
    } else {
        echo "📋 Found " . count($approvedEnrollments) . " approved enrollments ready for transfer:\n\n";
        
        foreach ($approvedEnrollments as $enrollment) {
            echo "- ID: {$enrollment['id']}, Student: {$enrollment['student_name']}, Grade: {$enrollment['grade_level']}\n";
        }
        
        echo "\n--- TRANSFERRING FIRST ENROLLMENT ---\n";
        $result = $transfer->transferEnrollment($approvedEnrollments[0]['id']);
        
        if ($result['success']) {
            echo "🎉 SUCCESS: Enrollment transferred successfully!\n";
            echo "📋 Student ID: {$result['student_id']}\n";
            echo "📋 Account Number: {$result['account_number']}\n";
            echo "📋 Temporary Password: {$result['temp_password']}\n";
        } else {
            echo "❌ ERROR: {$result['error']}\n";
        }
    }
    
    // Check pending notifications
    echo "\n--- CHECKING PENDING NOTIFICATIONS ---\n";
    $pendingNotifications = $transfer->getPendingNotifications();
    
    if (!empty($pendingNotifications)) {
        echo "📧 Found " . count($pendingNotifications) . " pending notifications:\n";
        foreach ($pendingNotifications as $notification) {
            echo "- Type: {$notification['notification_type']}, Method: {$notification['delivery_method']}\n";
        }
    } else {
        echo "📧 No pending notifications found.\n";
    }
    
    echo "\n=== TRANSFER SYSTEM READY ===\n";
    echo "\n--- INTEGRATION POINTS ---\n";
    echo "1. Call transferEnrollment(\$enrollmentId) when admin approves enrollment\n";
    echo "2. Set up cron job to process pending notifications\n";
    echo "3. Integrate with email service (Gmail API/SMTP)\n";
    echo "4. Integrate with SMS service (Twilio/local SMS API)\n";
    echo "5. Create admin interface for managing transfers\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ General Error: " . $e->getMessage() . "\n";
}

/**
 * Create test enrollment for demonstration
 */
function createTestEnrollment($pdo) {
    try {
        // Insert test enrollment
        $stmt = $pdo->prepare("
            INSERT INTO enrollments (lrn, grade_level, enrollment_status, enrollment_date)
            VALUES ('999888777666', 'Grade 7', 'approved', CURDATE())
        ");
        $stmt->execute();
        $enrollmentId = $pdo->lastInsertId();
        
        // Insert personal info
        $stmt = $pdo->prepare("
            INSERT INTO enrollment_personal_info (
                enrollment_id, student_name, student_email, student_contact,
                date_of_birth, gender, parent_name, parent_contact, parent_email
            ) VALUES (?, 'Test Student', 'test.student@example.com', '09123456789',
                     '2010-05-15', 'Male', 'Test Parent', '09987654321', 'test.parent@example.com')
        ");
        $stmt->execute([$enrollmentId]);
        
        // Insert address
        $stmt = $pdo->prepare("
            INSERT INTO enrollment_address_final (
                enrollment_id, barangay, municipality_city, province, region
            ) VALUES (?, 'Test Barangay', 'Test City', 'Test Province', 'Test Region')
        ");
        $stmt->execute([$enrollmentId]);
        
        return $enrollmentId;
        
    } catch (Exception $e) {
        echo "❌ Error creating test enrollment: " . $e->getMessage() . "\n";
        return null;
    }
}

echo "\n=== SCRIPT EXECUTION COMPLETE ===\n";
?>