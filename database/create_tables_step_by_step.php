<?php
// =====================================================
// CREATE STUDENT TABLES STEP BY STEP
// Execute each table creation individually
// =====================================================

// Database connection
$host = 'localhost';
$dbname = 'stajustina_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== CREATING STUDENT MANAGEMENT TABLES STEP BY STEP ===\n\n";
    
    // Step 1: Drop existing tables
    echo "--- STEP 1: DROPPING EXISTING TABLES ---\n";
    $dropTables = [
        'student_notifications',
        'student_emergency_contacts',
        'student_address',
        'student_family_info',
        'student_personal_info',
        'student_auth',
        'students'
    ];
    
    foreach ($dropTables as $table) {
        try {
            $pdo->exec("DROP TABLE IF EXISTS `$table`");
            echo "✅ Dropped table: $table\n";
        } catch (PDOException $e) {
            echo "⚠️  Could not drop table $table: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n--- STEP 2: CREATING CORE STUDENTS TABLE ---\n";
    $studentsTable = "
        CREATE TABLE students (
            id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            student_account_number VARCHAR(20) NOT NULL UNIQUE COMMENT 'Auto-generated unique account number',
            lrn VARCHAR(12) NOT NULL UNIQUE COMMENT 'Learner Reference Number',
            enrollment_id INT(11) UNSIGNED NULL COMMENT 'Reference to original enrollment record',
            student_status ENUM('active', 'inactive', 'suspended', 'graduated', 'transferred', 'dropped') NOT NULL DEFAULT 'active',
            enrollment_date DATE NOT NULL COMMENT 'Date when student was enrolled/approved',
            grade_level VARCHAR(50) NOT NULL,
            section VARCHAR(50) NULL,
            academic_year VARCHAR(20) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            PRIMARY KEY (id),
            INDEX idx_student_account (student_account_number),
            INDEX idx_lrn (lrn),
            INDEX idx_status (student_status),
            INDEX idx_enrollment_id (enrollment_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Core student records with unique identifiers'
    ";
    
    try {
        $pdo->exec($studentsTable);
        echo "✅ Created students table successfully\n";
    } catch (PDOException $e) {
        echo "❌ Failed to create students table: " . $e->getMessage() . "\n";
        throw $e;
    }
    
    echo "\n--- STEP 3: CREATING STUDENT_AUTH TABLE ---\n";
    $authTable = "
        CREATE TABLE student_auth (
            id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            student_id INT(11) UNSIGNED NOT NULL,
            username VARCHAR(100) NOT NULL UNIQUE COMMENT 'Login username (usually email or account number)',
            email VARCHAR(100) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL COMMENT 'Hashed password',
            password_salt VARCHAR(100) NULL COMMENT 'Password salt for additional security',
            temp_password VARCHAR(100) NULL COMMENT 'Temporary password for first login',
            password_reset_token VARCHAR(255) NULL,
            password_reset_expires DATETIME NULL,
            last_login DATETIME NULL,
            login_attempts INT(3) DEFAULT 0,
            account_locked_until DATETIME NULL,
            email_verified BOOLEAN DEFAULT FALSE,
            email_verification_token VARCHAR(255) NULL,
            two_factor_enabled BOOLEAN DEFAULT FALSE,
            two_factor_secret VARCHAR(100) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            PRIMARY KEY (id),
            FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE ON UPDATE CASCADE,
            UNIQUE KEY unique_student_auth (student_id),
            INDEX idx_username (username),
            INDEX idx_email (email),
            INDEX idx_password_reset (password_reset_token)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Student authentication and login credentials'
    ";
    
    try {
        $pdo->exec($authTable);
        echo "✅ Created student_auth table successfully\n";
    } catch (PDOException $e) {
        echo "❌ Failed to create student_auth table: " . $e->getMessage() . "\n";
        throw $e;
    }
    
    echo "\n--- STEP 4: CREATING STUDENT_PERSONAL_INFO TABLE ---\n";
    $personalTable = "
        CREATE TABLE student_personal_info (
            id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            student_id INT(11) UNSIGNED NOT NULL,
            first_name VARCHAR(100) NOT NULL,
            middle_name VARCHAR(100) NULL,
            last_name VARCHAR(100) NOT NULL,
            suffix VARCHAR(20) NULL COMMENT 'Jr., Sr., III, etc.',
            preferred_name VARCHAR(100) NULL,
            date_of_birth DATE NOT NULL,
            place_of_birth VARCHAR(200) NULL,
            gender ENUM('Male', 'Female', 'Other', 'Prefer not to say') NOT NULL,
            civil_status ENUM('Single', 'Married', 'Widowed', 'Separated', 'Divorced') DEFAULT 'Single',
            nationality VARCHAR(100) DEFAULT 'Filipino',
            citizenship VARCHAR(100) DEFAULT 'Filipino',
            religion VARCHAR(100) NULL,
            blood_type ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-') NULL,
            height_cm DECIMAL(5,2) NULL COMMENT 'Height in centimeters',
            weight_kg DECIMAL(5,2) NULL COMMENT 'Weight in kilograms',
            profile_picture VARCHAR(255) NULL,
            special_needs TEXT NULL COMMENT 'Special educational needs or disabilities',
            medical_conditions TEXT NULL COMMENT 'Known medical conditions or allergies',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            PRIMARY KEY (id),
            FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE ON UPDATE CASCADE,
            UNIQUE KEY unique_student_personal (student_id),
            INDEX idx_full_name (last_name, first_name),
            INDEX idx_birth_date (date_of_birth),
            INDEX idx_gender (gender)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Student personal and demographic information'
    ";
    
    try {
        $pdo->exec($personalTable);
        echo "✅ Created student_personal_info table successfully\n";
    } catch (PDOException $e) {
        echo "❌ Failed to create student_personal_info table: " . $e->getMessage() . "\n";
        throw $e;
    }
    
    echo "\n--- STEP 5: CREATING STUDENT_FAMILY_INFO TABLE ---\n";
    $familyTable = "
        CREATE TABLE student_family_info (
            id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            student_id INT(11) UNSIGNED NOT NULL,
            relationship_type ENUM('Father', 'Mother', 'Guardian', 'Stepfather', 'Stepmother', 'Grandparent', 'Sibling', 'Other') NOT NULL,
            is_primary_contact BOOLEAN DEFAULT FALSE COMMENT 'Primary contact for school communications',
            title VARCHAR(20) NULL COMMENT 'Mr., Mrs., Ms., Dr., etc.',
            first_name VARCHAR(100) NOT NULL,
            middle_name VARCHAR(100) NULL,
            last_name VARCHAR(100) NOT NULL,
            suffix VARCHAR(20) NULL,
            date_of_birth DATE NULL,
            occupation VARCHAR(200) NULL,
            employer VARCHAR(200) NULL,
            work_address TEXT NULL,
            monthly_income DECIMAL(12,2) NULL,
            educational_attainment VARCHAR(100) NULL,
            phone_primary VARCHAR(20) NOT NULL,
            phone_secondary VARCHAR(20) NULL,
            email VARCHAR(100) NULL,
            facebook_account VARCHAR(200) NULL,
            other_social_media TEXT NULL,
            living_with_student BOOLEAN DEFAULT TRUE,
            custody_rights BOOLEAN DEFAULT TRUE COMMENT 'Has legal custody/guardianship rights',
            authorized_pickup BOOLEAN DEFAULT TRUE COMMENT 'Authorized to pick up student',
            emergency_contact BOOLEAN DEFAULT TRUE COMMENT 'Can be contacted in emergencies',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            PRIMARY KEY (id),
            FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE ON UPDATE CASCADE,
            INDEX idx_student_family (student_id),
            INDEX idx_relationship (relationship_type),
            INDEX idx_primary_contact (is_primary_contact),
            INDEX idx_phone (phone_primary)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Student family members and guardian information'
    ";
    
    try {
        $pdo->exec($familyTable);
        echo "✅ Created student_family_info table successfully\n";
    } catch (PDOException $e) {
        echo "❌ Failed to create student_family_info table: " . $e->getMessage() . "\n";
        throw $e;
    }
    
    echo "\n--- STEP 6: CREATING STUDENT_ADDRESS TABLE ---\n";
    $addressTable = "
        CREATE TABLE student_address (
            id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            student_id INT(11) UNSIGNED NOT NULL,
            address_type ENUM('current', 'permanent', 'mailing', 'emergency') NOT NULL,
            is_primary BOOLEAN DEFAULT FALSE COMMENT 'Primary address for official correspondence',
            house_number VARCHAR(50) NULL,
            street VARCHAR(200) NULL,
            subdivision_village VARCHAR(200) NULL,
            barangay VARCHAR(200) NOT NULL,
            municipality_city VARCHAR(200) NOT NULL,
            province VARCHAR(200) NOT NULL,
            region VARCHAR(200) NOT NULL,
            postal_code VARCHAR(10) NULL,
            country VARCHAR(100) DEFAULT 'Philippines',
            landmark VARCHAR(500) NULL COMMENT 'Nearby landmarks for easier location',
            coordinates_lat DECIMAL(10, 8) NULL COMMENT 'GPS latitude',
            coordinates_lng DECIMAL(11, 8) NULL COMMENT 'GPS longitude',
            same_as_permanent BOOLEAN DEFAULT FALSE COMMENT 'Same as permanent address',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            PRIMARY KEY (id),
            FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE ON UPDATE CASCADE,
            INDEX idx_student_address (student_id),
            INDEX idx_address_type (address_type),
            INDEX idx_location (municipality_city, province),
            INDEX idx_primary (is_primary)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Student address information (current, permanent, etc.)'
    ";
    
    try {
        $pdo->exec($addressTable);
        echo "✅ Created student_address table successfully\n";
    } catch (PDOException $e) {
        echo "❌ Failed to create student_address table: " . $e->getMessage() . "\n";
        throw $e;
    }
    
    echo "\n--- STEP 7: CREATING STUDENT_EMERGENCY_CONTACTS TABLE ---\n";
    $emergencyTable = "
        CREATE TABLE student_emergency_contacts (
            id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            student_id INT(11) UNSIGNED NOT NULL,
            priority_order INT(2) NOT NULL DEFAULT 1 COMMENT 'Contact priority (1=first, 2=second, etc.)',
            relationship VARCHAR(100) NOT NULL COMMENT 'Relationship to student',
            first_name VARCHAR(100) NOT NULL,
            middle_name VARCHAR(100) NULL,
            last_name VARCHAR(100) NOT NULL,
            phone_primary VARCHAR(20) NOT NULL,
            phone_secondary VARCHAR(20) NULL,
            email VARCHAR(100) NULL,
            address TEXT NULL,
            workplace VARCHAR(200) NULL,
            work_phone VARCHAR(20) NULL,
            notes TEXT NULL COMMENT 'Additional notes about this contact',
            is_family_member BOOLEAN DEFAULT FALSE COMMENT 'Is this person also in family_info table',
            authorized_pickup BOOLEAN DEFAULT FALSE COMMENT 'Authorized to pick up student',
            medical_authorization BOOLEAN DEFAULT FALSE COMMENT 'Can authorize medical treatment',
            active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            PRIMARY KEY (id),
            FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE ON UPDATE CASCADE,
            INDEX idx_student_emergency (student_id),
            INDEX idx_priority (priority_order),
            INDEX idx_phone (phone_primary),
            INDEX idx_active (active)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Emergency contact persons for students'
    ";
    
    try {
        $pdo->exec($emergencyTable);
        echo "✅ Created student_emergency_contacts table successfully\n";
    } catch (PDOException $e) {
        echo "❌ Failed to create student_emergency_contacts table: " . $e->getMessage() . "\n";
        throw $e;
    }
    
    echo "\n--- STEP 8: CREATING STUDENT_NOTIFICATIONS TABLE ---\n";
    $notificationsTable = "
        CREATE TABLE student_notifications (
            id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            student_id INT(11) UNSIGNED NOT NULL,
            notification_type ENUM('enrollment_approval', 'account_created', 'password_reset', 'grade_update', 'attendance_alert', 'general_announcement', 'payment_reminder', 'other') NOT NULL,
            delivery_method ENUM('email', 'sms', 'both') NOT NULL,
            recipient_email VARCHAR(100) NULL,
            recipient_phone VARCHAR(20) NULL,
            subject VARCHAR(500) NULL,
            message TEXT NOT NULL,
            status ENUM('pending', 'sent', 'delivered', 'failed', 'bounced') DEFAULT 'pending',
            sent_at DATETIME NULL,
            delivered_at DATETIME NULL,
            error_message TEXT NULL,
            retry_count INT(2) DEFAULT 0,
            max_retries INT(2) DEFAULT 3,
            priority ENUM('low', 'normal', 'high', 'urgent') DEFAULT 'normal',
            reference_id VARCHAR(100) NULL COMMENT 'External reference (email ID, SMS ID, etc.)',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            PRIMARY KEY (id),
            FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE ON UPDATE CASCADE,
            INDEX idx_student_notifications (student_id),
            INDEX idx_type (notification_type),
            INDEX idx_status (status),
            INDEX idx_delivery_method (delivery_method),
            INDEX idx_sent_date (sent_at),
            INDEX idx_priority (priority)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Student notification history and delivery tracking'
    ";
    
    try {
        $pdo->exec($notificationsTable);
        echo "✅ Created student_notifications table successfully\n";
    } catch (PDOException $e) {
        echo "❌ Failed to create student_notifications table: " . $e->getMessage() . "\n";
        throw $e;
    }
    
    // Verify all tables were created
    echo "\n--- STEP 9: VERIFYING ALL TABLES ---\n";
    $expectedTables = [
        'students',
        'student_auth',
        'student_personal_info',
        'student_family_info',
        'student_address',
        'student_emergency_contacts',
        'student_notifications'
    ];
    
    $allTablesCreated = true;
    foreach ($expectedTables as $table) {
        $result = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($result->rowCount() > 0) {
            echo "✅ Table '$table' verified\n";
        } else {
            echo "❌ Table '$table' missing\n";
            $allTablesCreated = false;
        }
    }
    
    if ($allTablesCreated) {
        echo "\n🎉 SUCCESS: All student management tables created successfully!\n";
        echo "\n--- NEXT STEPS ---\n";
        echo "1. Create enrollment-to-student transfer script\n";
        echo "2. Implement automatic account creation functionality\n";
        echo "3. Set up email and SMS notification services\n";
        echo "4. Create student management interface\n";
        echo "5. Test the complete enrollment workflow\n";
    } else {
        echo "\n❌ Some tables were not created successfully.\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ General Error: " . $e->getMessage() . "\n";
}

echo "\n=== SCRIPT EXECUTION COMPLETE ===\n";
?>