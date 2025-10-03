<?php

try {
    // Connect to database
    $pdo = new PDO('mysql:host=localhost;dbname=stajustina_db', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully.\n";
    echo "Force creating missing enrollment data tables...\n\n";
    
    // Disable foreign key checks temporarily
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    // Drop existing problematic tables if they exist
    $tablesToDrop = [
        'enrollment_addresses',
        'enrollment_family_info',
        'enrollment_disabilities', 
        'shs_enrollment_details',
        'enrollment_emergency_contacts'
    ];
    
    foreach ($tablesToDrop as $table) {
        try {
            $pdo->exec("DROP TABLE IF EXISTS $table");
            echo "Dropped existing $table table.\n";
        } catch(Exception $e) {
            echo "Note: $table - " . $e->getMessage() . "\n";
        }
    }
    
    // Re-enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    // 1. Create enrollment_addresses table (Step 2 - Address Information)
    echo "\n1. Creating enrollment_addresses table...\n";
    $sql_addresses = "
    CREATE TABLE enrollment_addresses (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        enrollment_id INT(11) UNSIGNED NOT NULL,
        address_type ENUM('current', 'permanent') NOT NULL,
        house_no VARCHAR(50) NULL,
        street VARCHAR(255) NULL,
        barangay VARCHAR(100) NOT NULL,
        municipality VARCHAR(100) NOT NULL,
        province VARCHAR(100) NOT NULL,
        country VARCHAR(100) DEFAULT 'Philippines',
        zip_code VARCHAR(10) NULL,
        is_same_as_current BOOLEAN DEFAULT FALSE COMMENT 'For permanent address same as current',
        
        CONSTRAINT fk_addresses_enrollment FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE ON UPDATE CASCADE,
        UNIQUE KEY uk_enrollment_address_type (enrollment_id, address_type),
        INDEX idx_enrollment_id (enrollment_id),
        INDEX idx_address_type (address_type)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ";
    
    $pdo->exec($sql_addresses);
    echo "   ✅ enrollment_addresses created successfully.\n";
    
    // 2. Create enrollment_family_info table (Step 2 - Family Information)
    echo "2. Creating enrollment_family_info table...\n";
    $sql_family = "
    CREATE TABLE enrollment_family_info (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        enrollment_id INT(11) UNSIGNED NOT NULL,
        relationship_type ENUM('father', 'mother', 'guardian') NOT NULL,
        first_name VARCHAR(100) NULL,
        middle_name VARCHAR(100) NULL,
        last_name VARCHAR(100) NULL,
        contact_number VARCHAR(20) NULL,
        
        CONSTRAINT fk_family_enrollment FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE ON UPDATE CASCADE,
        UNIQUE KEY uk_enrollment_relationship (enrollment_id, relationship_type),
        INDEX idx_enrollment_id (enrollment_id),
        INDEX idx_relationship_type (relationship_type)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ";
    
    $pdo->exec($sql_family);
    echo "   ✅ enrollment_family_info created successfully.\n";
    
    // 3. Create enrollment_disabilities table (Step 3 - Disability Information)
    echo "3. Creating enrollment_disabilities table...\n";
    $sql_disabilities = "
    CREATE TABLE enrollment_disabilities (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        enrollment_id INT(11) UNSIGNED NOT NULL,
        has_disability ENUM('Yes', 'No') DEFAULT 'No',
        disability_type VARCHAR(100) NULL COMMENT 'Visual Impairment, Hearing Impairment, Learning Disability, etc.',
        
        CONSTRAINT fk_disabilities_enrollment FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE ON UPDATE CASCADE,
        INDEX idx_enrollment_id (enrollment_id),
        INDEX idx_disability_type (disability_type)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ";
    
    $pdo->exec($sql_disabilities);
    echo "   ✅ enrollment_disabilities created successfully.\n";
    
    // 4. Create shs_enrollment_details table (SHS-Specific Requirements)
    echo "4. Creating shs_enrollment_details table...\n";
    $sql_shs = "
    CREATE TABLE shs_enrollment_details (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        enrollment_id INT(11) UNSIGNED NOT NULL,
        track VARCHAR(50) NOT NULL COMMENT 'Academic, TVL, Sports, Arts',
        strand VARCHAR(50) NOT NULL COMMENT 'STEM, ABM, HUMSS, etc.',
        specialization VARCHAR(100) NULL COMMENT 'Track-specific specialization',
        career_pathway VARCHAR(100) NULL COMMENT 'Intended career direction',
        subject_preferences JSON NULL COMMENT 'Preferred elective subjects',
        prerequisites_met BOOLEAN DEFAULT FALSE COMMENT 'Whether student meets track/strand prerequisites',
        semester ENUM('1st', '2nd') NULL COMMENT 'Current semester for SHS',
        
        CONSTRAINT fk_shs_enrollment FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE ON UPDATE CASCADE,
        INDEX idx_enrollment_id (enrollment_id),
        INDEX idx_track_strand (track, strand)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ";
    
    $pdo->exec($sql_shs);
    echo "   ✅ shs_enrollment_details created successfully.\n";
    
    // 5. Create enrollment_emergency_contacts table (Additional Safety Feature)
    echo "5. Creating enrollment_emergency_contacts table...\n";
    $sql_emergency = "
    CREATE TABLE enrollment_emergency_contacts (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        enrollment_id INT(11) UNSIGNED NOT NULL,
        contact_name VARCHAR(150) NOT NULL,
        relationship VARCHAR(50) NOT NULL COMMENT 'Relationship to student',
        contact_number VARCHAR(20) NOT NULL,
        is_primary BOOLEAN DEFAULT FALSE COMMENT 'Primary emergency contact',
        
        CONSTRAINT fk_emergency_enrollment FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE ON UPDATE CASCADE,
        INDEX idx_enrollment_id (enrollment_id),
        INDEX idx_is_primary (is_primary)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ";
    
    $pdo->exec($sql_emergency);
    echo "   ✅ enrollment_emergency_contacts created successfully.\n";
    
    // Verify all tables were created
    echo "\n=== 🎉 VERIFICATION: Missing Enrollment Tables Created ===\n";
    $newTables = [
        'enrollment_addresses' => 'Address Information (Step 2)',
        'enrollment_family_info' => 'Family Information (Step 2)', 
        'enrollment_disabilities' => 'Disability Information (Step 3)',
        'shs_enrollment_details' => 'SHS-Specific Details (Step 3)',
        'enrollment_emergency_contacts' => 'Emergency Contacts (Additional)'
    ];
    
    $createdCount = 0;
    foreach ($newTables as $tableName => $purpose) {
        try {
            $result = $pdo->query("SELECT COUNT(*) as field_count FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'stajustina_db' AND TABLE_NAME = '$tableName'");
            $fieldCount = $result->fetch()['field_count'];
            if ($fieldCount > 0) {
                $createdCount++;
                echo "✅ $tableName ($fieldCount fields) - $purpose\n";
            } else {
                echo "❌ $tableName - Not found\n";
            }
        } catch(Exception $e) {
            echo "❌ $tableName - Error: " . $e->getMessage() . "\n";
        }
    }
    
    // Show sample table structures
    echo "\n=== 📋 SAMPLE TABLE STRUCTURES ===\n";
    
    // Show enrollment_addresses structure
    echo "\n📍 enrollment_addresses (Address Information):\n";
    try {
        $result = $pdo->query("DESCRIBE enrollment_addresses");
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "   - " . $row['Field'] . " (" . $row['Type'] . ")\n";
        }
    } catch(Exception $e) {
        echo "   Error: " . $e->getMessage() . "\n";
    }
    
    // Show enrollment_family_info structure
    echo "\n👨‍👩‍👧‍👦 enrollment_family_info (Family Information):\n";
    try {
        $result = $pdo->query("DESCRIBE enrollment_family_info");
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "   - " . $row['Field'] . " (" . $row['Type'] . ")\n";
        }
    } catch(Exception $e) {
        echo "   Error: " . $e->getMessage() . "\n";
    }
    
    // Show shs_enrollment_details structure
    echo "\n🏫 shs_enrollment_details (SHS-Specific):\n";
    try {
        $result = $pdo->query("DESCRIBE shs_enrollment_details");
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "   - " . $row['Field'] . " (" . $row['Type'] . ")\n";
        }
    } catch(Exception $e) {
        echo "   Error: " . $e->getMessage() . "\n";
    }
    
    // Show complete enrollment schema
    echo "\n=== 📊 COMPLETE NORMALIZED ENROLLMENT SCHEMA ===\n";
    $allEnrollmentTables = [
        'enrollments' => 'Core Application Record',
        'enrollment_personal_info' => 'Student Data Before Approval',
        'enrollment_academic_history_new' => 'Academic Background',
        'enrollment_documents' => 'Document Storage',
        'enrollment_addresses' => 'Address Information (NEW)',
        'enrollment_family_info' => 'Family Information (NEW)',
        'enrollment_disabilities' => 'Disability Information (NEW)',
        'shs_enrollment_details' => 'SHS-Specific Details (NEW)',
        'enrollment_emergency_contacts' => 'Emergency Contacts (NEW)'
    ];
    
    $totalTables = 0;
    foreach ($allEnrollmentTables as $tableName => $purpose) {
        try {
            $result = $pdo->query("SELECT COUNT(*) as field_count FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'stajustina_db' AND TABLE_NAME = '$tableName'");
            $fieldCount = $result->fetch()['field_count'];
            if ($fieldCount > 0) {
                $totalTables++;
                echo "$totalTables. ✅ $tableName ($fieldCount fields) - $purpose\n";
            }
        } catch(Exception $e) {
            // Table doesn't exist, skip
        }
    }
    
    // Show relationships
    echo "\n=== 🔗 DATABASE RELATIONSHIPS ===\n";
    echo "enrollments (1) ⟶ enrollment_personal_info (1:1)\n";
    echo "enrollments (1) ⟶ enrollment_academic_history_new (1:1)\n";
    echo "enrollments (1) ⟶ enrollment_documents (1:many)\n";
    echo "enrollments (1) ⟶ enrollment_addresses (1:2) [current + permanent]\n";
    echo "enrollments (1) ⟶ enrollment_family_info (1:3) [father + mother + guardian]\n";
    echo "enrollments (1) ⟶ enrollment_disabilities (1:many) [multiple disabilities]\n";
    echo "enrollments (1) ⟶ shs_enrollment_details (1:1) [SHS students only]\n";
    echo "enrollments (1) ⟶ enrollment_emergency_contacts (1:many) [emergency contacts]\n";
    
    echo "\n🎊 SUCCESS! Created $createdCount out of 5 missing enrollment data tables!\n";
    echo "📋 Form Steps 2 & 3 data can now be properly captured and normalized.\n";
    echo "🏫 Senior High School (SHS) requirements fully supported.\n";
    echo "🔗 All foreign key relationships established for data integrity.\n";
    echo "✨ Database schema now covers complete multi-step enrollment form!\n";
    
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

?>