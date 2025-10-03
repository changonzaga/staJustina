<?php
// =====================================================
// ENROLLMENT TO STUDENT TRANSFER SCRIPT (FIXED)
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
                throw new Exception("Enrollment record not found or not approved: $enrollmentId");
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
            $this->updateEnrollmentStatus($enrollmentId, 'enrolled');
            
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
                epi.first_name,
                epi.middle_name,
                epi.last_name,
                epi.extension_name,
                epi.lrn,
                epi.student_email,
                epi.student_contact,
                epi.date_of_birth,
                epi.gender,
                epi.mother_tongue,
                eaf.house_no,
                eaf.street,
                eaf.barangay,
                eaf.municipality,
                eaf.province,
                eaf.country,
                eaf.zip_code
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
            $data['school_year'] ?? (date('Y') . '-' . (date('Y') + 1))
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
        $stmt = $this->pdo->prepare("
            INSERT INTO student_personal_info (
                student_id, first_name, middle_name, last_name, suffix,
                date_of_birth, gender, nationality, citizenship
            ) VALUES (?, ?, ?, ?, ?, ?, ?, 'Filipino', 'Filipino')
        ");
        
        $stmt->execute([
            $studentId,
            $data['first_name'],
            $data['middle_name'],
            $data['last_name'],
            $data['extension_name'],
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
                student_id, address_type, is_primary, house_number, street,
                barangay, municipality_city, province, country, postal_code
            ) VALUES (?, 'current', TRUE, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $studentId,
            $data['house_no'],
            $data['street'],
            $data['barangay'],
            $data['municipality'],
            $data['province'],
            $data['country'] ?? 'Philippines',
            $data['zip_code']
        ]);
    }
    
    /**
     * Create emergency contacts (placeholder)
     */
    private function createEmergencyContacts($studentId, $data) {
        // Create placeholder emergency contact
        $stmt = $this->pdo->prepare("
            INSERT INTO student_emergency_contacts (
                student_id, priority_order, relationship, first_name, last_name,
                phone_primary, is_family_member, authorized_pickup
            ) VALUES (?, 1, 'Parent/Guardian', 'To Be Updated', 'To Be Updated', 'N/A', TRUE, TRUE)
        ");
        
        $stmt->execute([$studentId]);
    }
    
    /**
     * Create notification records for email and SMS
     */
    private function createNotificationRecords($studentId, $data, $accountNumber, $tempPassword) {
        $fullName = trim($data['first_name'] . ' ' . ($data['middle_name'] ?? '') . ' ' . $data['last_name']);
        
        // Email notification
        if (!empty($data['student_email'])) {
            $emailMessage = $this->generateEmailMessage($fullName, $accountNumber, $tempPassword);
            
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
            $smsMessage = $this->generateSMSMessage($fullName, $accountNumber, $tempPassword);
            
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
    
    /**
     * Approve enrollment and transfer to student system
     */
    public function approveAndTransferEnrollment($enrollmentId) {
        try {
            // First approve the enrollment
            $stmt = $this->pdo->prepare("
                UPDATE enrollments 
                SET enrollment_status = 'approved', approved_at = NOW() 
                WHERE id = ? AND enrollment_status = 'pending'
            ");
            
            $stmt->execute([$enrollmentId]);
            
            if ($stmt->rowCount() == 0) {
                throw new Exception("Enrollment not found or already processed");
            }
            
            // Then transfer to student system
            return $this->transferEnrollment($enrollmentId);
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}

// =====================================================
// USAGE EXAMPLE AND TESTING
// Only run when executed directly, not when included
// =====================================================

if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $transfer = new EnrollmentToStudentTransfer($pdo);
    
    echo "=== ENROLLMENT TO STUDENT TRANSFER SYSTEM (FIXED) ===\n\n";
    
    // Check for pending enrollments that can be approved
    $pendingEnrollments = $pdo->query("
        SELECT e.id, epi.first_name, epi.last_name, e.grade_level, e.enrollment_status
        FROM enrollments e
        LEFT JOIN enrollment_personal_info epi ON e.id = epi.enrollment_id
        WHERE e.enrollment_status = 'pending'
        LIMIT 3
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($pendingEnrollments)) {
        echo "📋 Found " . count($pendingEnrollments) . " pending enrollments:\n\n";
        
        foreach ($pendingEnrollments as $enrollment) {
            $fullName = trim($enrollment['first_name'] . ' ' . $enrollment['last_name']);
            echo "- ID: {$enrollment['id']}, Student: $fullName, Grade: {$enrollment['grade_level']}\n";
        }
        
        echo "\n--- APPROVING AND TRANSFERRING FIRST ENROLLMENT ---\n";
        $result = $transfer->approveAndTransferEnrollment($pendingEnrollments[0]['id']);
        
        if ($result['success']) {
            echo "🎉 SUCCESS: Enrollment approved and transferred successfully!\n";
            echo "📋 Student ID: {$result['student_id']}\n";
            echo "📋 Account Number: {$result['account_number']}\n";
            echo "📋 Temporary Password: {$result['temp_password']}\n";
        } else {
            echo "❌ ERROR: {$result['error']}\n";
        }
    } else {
        echo "📋 No pending enrollments found.\n";
        
        // Check for approved enrollments
        $approvedEnrollments = $pdo->query("
            SELECT e.id, epi.first_name, epi.last_name, e.grade_level, e.enrollment_status
            FROM enrollments e
            LEFT JOIN enrollment_personal_info epi ON e.id = epi.enrollment_id
            WHERE e.enrollment_status = 'approved'
            AND e.id NOT IN (SELECT COALESCE(enrollment_id, 0) FROM students WHERE enrollment_id IS NOT NULL)
            LIMIT 3
        ")->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($approvedEnrollments)) {
            echo "📋 Found " . count($approvedEnrollments) . " approved enrollments ready for transfer:\n\n";
            
            foreach ($approvedEnrollments as $enrollment) {
                $fullName = trim($enrollment['first_name'] . ' ' . $enrollment['last_name']);
                echo "- ID: {$enrollment['id']}, Student: $fullName, Grade: {$enrollment['grade_level']}\n";
            }
            
            echo "\n--- TRANSFERRING FIRST APPROVED ENROLLMENT ---\n";
            $result = $transfer->transferEnrollment($approvedEnrollments[0]['id']);
            
            if ($result['success']) {
                echo "🎉 SUCCESS: Enrollment transferred successfully!\n";
                echo "📋 Student ID: {$result['student_id']}\n";
                echo "📋 Account Number: {$result['account_number']}\n";
                echo "📋 Temporary Password: {$result['temp_password']}\n";
            } else {
                echo "❌ ERROR: {$result['error']}\n";
            }
        } else {
            echo "📋 No approved enrollments found for transfer.\n";
        }
    }
    
    // Check pending notifications
    echo "\n--- CHECKING PENDING NOTIFICATIONS ---\n";
    $pendingNotifications = $transfer->getPendingNotifications();
    
    if (!empty($pendingNotifications)) {
        echo "📧 Found " . count($pendingNotifications) . " pending notifications:\n";
        foreach ($pendingNotifications as $notification) {
            echo "- Type: {$notification['notification_type']}, Method: {$notification['delivery_method']}";
            if ($notification['delivery_method'] == 'email') {
                echo ", To: {$notification['recipient_email']}";
            } else {
                echo ", To: {$notification['recipient_phone']}";
            }
            echo "\n";
        }
    } else {
        echo "📧 No pending notifications found.\n";
    }
    
    // Show created students
    echo "\n--- CREATED STUDENTS ---\n";
    $students = $pdo->query("
        SELECT s.*, spi.first_name, spi.last_name, sa.email
        FROM students s
        LEFT JOIN student_personal_info spi ON s.id = spi.student_id
        LEFT JOIN student_auth sa ON s.id = sa.student_id
        ORDER BY s.created_at DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($students)) {
        echo "👥 Found " . count($students) . " students in the system:\n";
        foreach ($students as $student) {
            $fullName = trim($student['first_name'] . ' ' . $student['last_name']);
            echo "- Account: {$student['student_account_number']}, Name: $fullName, Email: {$student['email']}\n";
        }
    } else {
        echo "👥 No students found in the system.\n";
    }
    
    echo "\n=== TRANSFER SYSTEM READY ===\n";
    echo "\n--- INTEGRATION POINTS ---\n";
    echo "1. Call approveAndTransferEnrollment(\$enrollmentId) when admin approves enrollment\n";
    echo "2. Set up cron job to process pending notifications\n";
    echo "3. Integrate with email service (Gmail API/SMTP)\n";
    echo "4. Integrate with SMS service (Twilio/local SMS API)\n";
    echo "5. Create admin interface for managing transfers\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ General Error: " . $e->getMessage() . "\n";
}

echo "\n=== SCRIPT EXECUTION COMPLETE ===\n";
}
?>