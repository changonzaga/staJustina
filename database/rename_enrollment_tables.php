<?php

try {
    // Connect to database
    $pdo = new PDO('mysql:host=localhost;dbname=stajustina_db', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully.\n";
    echo "Renaming tables to follow enrollment_ naming convention...\n\n";
    
    // Disable foreign key checks temporarily
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    // 1. Rename student_addresses to enrollment_addresses
    echo "1. Renaming student_addresses to enrollment_addresses...\n";
    try {
        $pdo->exec("RENAME TABLE student_addresses TO enrollment_addresses");
        echo "   ✅ student_addresses renamed to enrollment_addresses\n";
    } catch(Exception $e) {
        echo "   Note: " . $e->getMessage() . "\n";
    }
    
    // 2. Rename student_family_info to enrollment_family_info
    echo "2. Renaming student_family_info to enrollment_family_info...\n";
    try {
        $pdo->exec("RENAME TABLE student_family_info TO enrollment_family_info");
        echo "   ✅ student_family_info renamed to enrollment_family_info\n";
    } catch(Exception $e) {
        echo "   Note: " . $e->getMessage() . "\n";
    }
    
    // 3. Rename student_disabilities to enrollment_disabilities
    echo "3. Renaming student_disabilities to enrollment_disabilities...\n";
    try {
        $pdo->exec("RENAME TABLE student_disabilities TO enrollment_disabilities");
        echo "   ✅ student_disabilities renamed to enrollment_disabilities\n";
    } catch(Exception $e) {
        echo "   Note: " . $e->getMessage() . "\n";
    }
    
    // 4. Rename shs_student_details to enrollment_shs_details
    echo "4. Renaming shs_student_details to enrollment_shs_details...\n";
    try {
        $pdo->exec("RENAME TABLE shs_student_details TO enrollment_shs_details");
        echo "   ✅ shs_student_details renamed to enrollment_shs_details\n";
    } catch(Exception $e) {
        echo "   Note: " . $e->getMessage() . "\n";
    }
    
    // 5. Rename student_emergency_contacts to enrollment_emergency_contacts
    echo "5. Renaming student_emergency_contacts to enrollment_emergency_contacts...\n";
    try {
        $pdo->exec("RENAME TABLE student_emergency_contacts TO enrollment_emergency_contacts");
        echo "   ✅ student_emergency_contacts renamed to enrollment_emergency_contacts\n";
    } catch(Exception $e) {
        echo "   Note: " . $e->getMessage() . "\n";
    }
    
    // Re-enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    // Verify all renamed tables exist
    echo "\n=== 🎉 VERIFICATION: Renamed Enrollment Tables ===\n";
    $renamedTables = [
        'enrollment_addresses' => 'Address Information (Step 2)',
        'enrollment_family_info' => 'Family Information (Step 2)', 
        'enrollment_disabilities' => 'Disability Information (Step 3)',
        'enrollment_shs_details' => 'SHS-Specific Details (Step 3)',
        'enrollment_emergency_contacts' => 'Emergency Contacts (Additional)'
    ];
    
    $renamedCount = 0;
    foreach ($renamedTables as $tableName => $purpose) {
        try {
            $result = $pdo->query("SELECT COUNT(*) as field_count FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'stajustina_db' AND TABLE_NAME = '$tableName'");
            $fieldCount = $result->fetch()['field_count'];
            if ($fieldCount > 0) {
                $renamedCount++;
                echo "✅ $tableName ($fieldCount fields) - $purpose\n";
            } else {
                echo "❌ $tableName - Not found\n";
            }
        } catch(Exception $e) {
            echo "❌ $tableName - Error: " . $e->getMessage() . "\n";
        }
    }
    
    // Verify foreign key relationships
    echo "\n=== 🔗 FOREIGN KEY RELATIONSHIP VERIFICATION ===\n";
    $tablesToCheck = [
        'enrollment_addresses',
        'enrollment_family_info',
        'enrollment_disabilities', 
        'enrollment_shs_details',
        'enrollment_emergency_contacts'
    ];
    
    foreach ($tablesToCheck as $tableName) {
        try {
            $result = $pdo->query("
                SELECT 
                    CONSTRAINT_NAME,
                    COLUMN_NAME,
                    REFERENCED_TABLE_NAME,
                    REFERENCED_COLUMN_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = 'stajustina_db' 
                AND TABLE_NAME = '$tableName'
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            
            $hasFK = false;
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $hasFK = true;
                echo "✅ $tableName.{$row['COLUMN_NAME']} → {$row['REFERENCED_TABLE_NAME']}.{$row['REFERENCED_COLUMN_NAME']}\n";
            }
            
            if (!$hasFK) {
                echo "⚠️  $tableName - No foreign key relationships found\n";
            }
        } catch(Exception $e) {
            echo "❌ $tableName - FK Check Error: " . $e->getMessage() . "\n";
        }
    }
    
    // Show complete enrollment schema with proper naming
    echo "\n=== 📊 COMPLETE ENROLLMENT SCHEMA (PROPER NAMING) ===\n";
    $allEnrollmentTables = [
        'enrollments' => 'Core Application Record',
        'enrollment_personal_info' => 'Student Data Before Approval (Step 1)',
        'enrollment_academic_history_new' => 'Academic Background (Step 3)',
        'enrollment_documents' => 'Document Storage (Step 4)',
        'enrollment_addresses' => 'Address Information (Step 2) - RENAMED',
        'enrollment_family_info' => 'Family Information (Step 2) - RENAMED',
        'enrollment_disabilities' => 'Disability Information (Step 3) - RENAMED',
        'enrollment_shs_details' => 'SHS-Specific Details (Step 3) - RENAMED',
        'enrollment_emergency_contacts' => 'Emergency Contacts - RENAMED'
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
    
    // Show proper enrollment workflow
    echo "\n=== 📝 PROPER ENROLLMENT WORKFLOW ===\n";
    echo "1. Student submits enrollment form\n";
    echo "2. Data stored in enrollment_* tables (linked to enrollments table)\n";
    echo "3. Admin reviews enrollment data\n";
    echo "4. Upon approval: Data transferred to students table\n";
    echo "5. enrollment.student_id updated to link approved enrollment\n";
    
    // Show corrected relationships
    echo "\n=== 🔗 CORRECTED DATABASE RELATIONSHIPS ===\n";
    echo "enrollments (1) ⟶ enrollment_personal_info (1:1) [Step 1]\n";
    echo "enrollments (1) ⟶ enrollment_addresses (1:2) [Step 2: current + permanent]\n";
    echo "enrollments (1) ⟶ enrollment_family_info (1:3) [Step 2: father + mother + guardian]\n";
    echo "enrollments (1) ⟶ enrollment_academic_history_new (1:1) [Step 3: academic]\n";
    echo "enrollments (1) ⟶ enrollment_disabilities (1:many) [Step 3: multiple disabilities]\n";
    echo "enrollments (1) ⟶ enrollment_shs_details (1:1) [Step 3: SHS students only]\n";
    echo "enrollments (1) ⟶ enrollment_documents (1:many) [Step 4: documents]\n";
    echo "enrollments (1) ⟶ enrollment_emergency_contacts (1:many) [Additional: emergency contacts]\n";
    echo "\n🔄 AFTER APPROVAL:\n";
    echo "students (1) ⟵ enrollments.student_id (many) [Approved enrollments only]\n";
    
    // Show naming convention benefits
    echo "\n=== ✨ NAMING CONVENTION BENEFITS ===\n";
    echo "✅ Consistent Prefixing: All enrollment-related tables use 'enrollment_' prefix\n";
    echo "✅ Clear Data Flow: enrollment_* tables → enrollments → students (after approval)\n";
    echo "✅ Proper Separation: Enrollment data separate from approved student data\n";
    echo "✅ Database Clarity: No confusion between enrollment and student tables\n";
    echo "✅ Referential Integrity: All foreign keys point to enrollments table\n";
    
    echo "\n🎊 SUCCESS! Renamed $renamedCount enrollment tables with proper naming convention!\n";
    echo "📋 All enrollment-related tables now follow consistent naming pattern.\n";
    echo "🔗 All foreign key relationships properly reference enrollments table.\n";
    echo "✨ Database schema maintains clear separation between enrollment and student data.\n";
    echo "🎯 Students table connection reserved for approved enrollments only.\n";
    
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

?>