<?php
// =====================================================
// ENROLLMENT TABLE RELATIONSHIPS ANALYSIS
// Check all enrollment-related table relations and dependencies
// =====================================================

$host = 'localhost';
$dbname = 'stajustina_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== ENROLLMENT TABLE RELATIONSHIPS ANALYSIS ===\n\n";
    
    // Step 1: Identify all enrollment-related tables
    echo "--- STEP 1: ENROLLMENT-RELATED TABLES ---\n";
    $enrollmentTables = [
        'enrollments',
        'enrollment_personal_info',
        'enrollment_address_final',
        'enrollment_family_info',
        'enrollment_emergency_contacts',
        'enrollment_documents',
        'enrollment_academic_info'
    ];
    
    $existingTables = [];
    foreach ($enrollmentTables as $table) {
        $check = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($check->rowCount() > 0) {
            $existingTables[] = $table;
            echo "✅ $table - EXISTS\n";
        } else {
            echo "❌ $table - MISSING\n";
        }
    }
    
    // Also check for any other enrollment-related tables
    echo "\n🔍 Searching for additional enrollment tables...\n";
    $allTables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $additionalEnrollmentTables = [];
    
    foreach ($allTables as $table) {
        if (strpos($table, 'enrollment') !== false && !in_array($table, $enrollmentTables)) {
            $additionalEnrollmentTables[] = $table;
            $existingTables[] = $table;
            echo "🔍 Found additional: $table\n";
        }
    }
    
    echo "\n📊 Found " . count($existingTables) . " enrollment-related tables\n";
    
    // Step 2: Check foreign key relationships
    echo "\n--- STEP 2: FOREIGN KEY RELATIONSHIPS ---\n";
    $allForeignKeys = [];
    
    foreach ($existingTables as $table) {
        $fkQuery = $pdo->query("
            SELECT 
                TABLE_NAME,
                CONSTRAINT_NAME,
                COLUMN_NAME,
                REFERENCED_TABLE_NAME,
                REFERENCED_COLUMN_NAME
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = '$dbname' 
            AND TABLE_NAME = '$table'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        
        $foreignKeys = $fkQuery->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($foreignKeys)) {
            echo "\n🔗 Table: $table\n";
            foreach ($foreignKeys as $fk) {
                echo "   - {$fk['COLUMN_NAME']} -> {$fk['REFERENCED_TABLE_NAME']}.{$fk['REFERENCED_COLUMN_NAME']}\n";
                echo "     Constraint: {$fk['CONSTRAINT_NAME']}\n";
                $allForeignKeys[] = $fk;
            }
        } else {
            echo "\n⚠️  Table: $table - No foreign keys found\n";
        }
    }
    
    // Step 3: Check referential integrity
    echo "\n--- STEP 3: REFERENTIAL INTEGRITY CHECK ---\n";
    $integrityIssues = [];
    
    foreach ($allForeignKeys as $fk) {
        $table = $fk['TABLE_NAME'];
        $column = $fk['COLUMN_NAME'];
        $refTable = $fk['REFERENCED_TABLE_NAME'];
        $refColumn = $fk['REFERENCED_COLUMN_NAME'];
        
        // Find orphaned records
        try {
            $orphanQuery = $pdo->query("
                SELECT COUNT(*) as orphan_count
                FROM `$table` t
                LEFT JOIN `$refTable` r ON t.`$column` = r.`$refColumn`
                WHERE t.`$column` IS NOT NULL 
                AND r.`$refColumn` IS NULL
            ");
            
            $orphanCount = $orphanQuery->fetch()['orphan_count'];
            
            if ($orphanCount > 0) {
                echo "❌ INTEGRITY ISSUE: $table.$column -> $refTable.$refColumn\n";
                echo "   Found $orphanCount orphaned records\n";
                $integrityIssues[] = [
                    'table' => $table,
                    'column' => $column,
                    'ref_table' => $refTable,
                    'ref_column' => $refColumn,
                    'orphan_count' => $orphanCount
                ];
            } else {
                echo "✅ INTEGRITY OK: $table.$column -> $refTable.$refColumn\n";
            }
        } catch (Exception $e) {
            echo "⚠️  Could not check: $table.$column -> $refTable.$refColumn\n";
            echo "   Error: " . $e->getMessage() . "\n";
        }
    }
    
    // Step 4: Check table structures and record counts
    echo "\n--- STEP 4: TABLE STRUCTURE & DATA ANALYSIS ---\n";
    foreach ($existingTables as $table) {
        echo "\n📋 Table: $table\n";
        
        // Get column count
        $structureQuery = $pdo->query("DESCRIBE `$table`");
        $columns = $structureQuery->fetchAll(PDO::FETCH_ASSOC);
        echo "   Columns: " . count($columns) . "\n";
        
        // Get record count
        $countQuery = $pdo->query("SELECT COUNT(*) as record_count FROM `$table`");
        $recordCount = $countQuery->fetch()['record_count'];
        echo "   Records: $recordCount\n";
        
        // Check for primary key
        $hasPrimaryKey = false;
        foreach ($columns as $column) {
            if ($column['Key'] === 'PRI') {
                $hasPrimaryKey = true;
                echo "   Primary Key: {$column['Field']}\n";
                break;
            }
        }
        
        if (!$hasPrimaryKey) {
            echo "   ⚠️  No primary key found\n";
        }
        
        // Check enrollment status distribution if applicable
        if ($table === 'enrollments') {
            echo "   📊 Enrollment Status Distribution:\n";
            $statusQuery = $pdo->query("
                SELECT enrollment_status, COUNT(*) as count 
                FROM enrollments 
                GROUP BY enrollment_status 
                ORDER BY count DESC
            ");
            
            while ($status = $statusQuery->fetch()) {
                echo "      - {$status['enrollment_status']}: {$status['count']} records\n";
            }
        }
    }
    
    // Step 5: Check for expected relationships
    echo "\n--- STEP 5: EXPECTED RELATIONSHIPS VALIDATION ---\n";
    $expectedRelationships = [
        'enrollment_personal_info' => ['enrollment_id' => 'enrollments.id'],
        'enrollment_address_final' => ['enrollment_id' => 'enrollments.id'],
        'enrollment_family_info' => ['enrollment_id' => 'enrollments.id'],
        'enrollment_emergency_contacts' => ['enrollment_id' => 'enrollments.id'],
        'enrollment_documents' => ['enrollment_id' => 'enrollments.id'],
        'enrollment_academic_info' => ['enrollment_id' => 'enrollments.id']
    ];
    
    foreach ($expectedRelationships as $table => $relationships) {
        if (!in_array($table, $existingTables)) {
            echo "⚠️  Expected table '$table' not found\n";
            continue;
        }
        
        foreach ($relationships as $column => $expectedRef) {
            $found = false;
            foreach ($allForeignKeys as $fk) {
                if ($fk['TABLE_NAME'] === $table && 
                    $fk['COLUMN_NAME'] === $column && 
                    $fk['REFERENCED_TABLE_NAME'] . '.' . $fk['REFERENCED_COLUMN_NAME'] === $expectedRef) {
                    $found = true;
                    break;
                }
            }
            
            if ($found) {
                echo "✅ Expected FK: $table.$column -> $expectedRef\n";
            } else {
                echo "❌ Missing FK: $table.$column -> $expectedRef\n";
            }
        }
    }
    
    // Step 6: Test data consistency
    echo "\n--- STEP 6: DATA CONSISTENCY TEST ---\n";
    if (in_array('enrollments', $existingTables)) {
        $enrollmentCount = $pdo->query("SELECT COUNT(*) as count FROM enrollments")->fetch()['count'];
        echo "📊 Base enrollments table: $enrollmentCount records\n";
        
        foreach (['enrollment_personal_info', 'enrollment_address_final', 'enrollment_family_info'] as $relatedTable) {
            if (in_array($relatedTable, $existingTables)) {
                $relatedCount = $pdo->query("SELECT COUNT(DISTINCT enrollment_id) as count FROM `$relatedTable`")->fetch()['count'];
                $percentage = $enrollmentCount > 0 ? round(($relatedCount / $enrollmentCount) * 100, 1) : 0;
                echo "   - $relatedTable: $relatedCount enrollments ($percentage%)\n";
            }
        }
    }
    
    // Step 7: Check enrollment to student integration
    echo "\n--- STEP 7: ENROLLMENT-STUDENT INTEGRATION ---\n";
    if (in_array('enrollments', $existingTables)) {
        // Check if enrollments reference students
        $enrollmentStudentFK = false;
        foreach ($allForeignKeys as $fk) {
            if ($fk['TABLE_NAME'] === 'enrollments' && $fk['REFERENCED_TABLE_NAME'] === 'students') {
                $enrollmentStudentFK = true;
                echo "✅ Enrollments linked to students via {$fk['COLUMN_NAME']}\n";
                break;
            }
        }
        
        if (!$enrollmentStudentFK) {
            echo "⚠️  No direct foreign key from enrollments to students\n";
        }
        
        // Check approved enrollments vs student records
        $approvedEnrollments = $pdo->query("
            SELECT COUNT(*) as count 
            FROM enrollments 
            WHERE enrollment_status = 'approved'
        ")->fetch()['count'];
        
        $studentRecords = 0;
        if (in_array('students', $allTables)) {
            $studentRecords = $pdo->query("SELECT COUNT(*) as count FROM students")->fetch()['count'];
        }
        
        echo "📊 Approved enrollments: $approvedEnrollments\n";
        echo "📊 Student records: $studentRecords\n";
        
        if ($approvedEnrollments > $studentRecords) {
            echo "⚠️  More approved enrollments than student records\n";
        } else if ($studentRecords > $approvedEnrollments) {
            echo "ℹ️  More student records than approved enrollments (may include manual entries)\n";
        } else {
            echo "✅ Approved enrollments match student records\n";
        }
    }
    
    // Step 8: Check enrollment workflow status
    echo "\n--- STEP 8: ENROLLMENT WORKFLOW ANALYSIS ---\n";
    if (in_array('enrollments', $existingTables)) {
        echo "📊 Enrollment Workflow Status:\n";
        
        // Check pending enrollments
        $pendingCount = $pdo->query("
            SELECT COUNT(*) as count 
            FROM enrollments 
            WHERE enrollment_status = 'pending'
        ")->fetch()['count'];
        
        // Check approved enrollments
        $approvedCount = $pdo->query("
            SELECT COUNT(*) as count 
            FROM enrollments 
            WHERE enrollment_status = 'approved'
        ")->fetch()['count'];
        
        // Check declined enrollments
        $declinedCount = $pdo->query("
            SELECT COUNT(*) as count 
            FROM enrollments 
            WHERE enrollment_status = 'declined'
        ")->fetch()['count'];
        
        echo "   - Pending: $pendingCount enrollments\n";
        echo "   - Approved: $approvedCount enrollments\n";
        echo "   - Declined: $declinedCount enrollments\n";
        
        $totalProcessed = $approvedCount + $declinedCount;
        $totalEnrollments = $pendingCount + $totalProcessed;
        
        if ($totalEnrollments > 0) {
            $processedPercentage = round(($totalProcessed / $totalEnrollments) * 100, 1);
            echo "   - Processing Rate: $processedPercentage% ($totalProcessed/$totalEnrollments)\n";
        }
    }
    
    // Step 9: Summary and recommendations
    echo "\n--- STEP 9: ANALYSIS SUMMARY ---\n";
    echo "\n📊 STATISTICS:\n";
    echo "   - Enrollment tables found: " . count($existingTables) . "\n";
    echo "   - Foreign key relationships: " . count($allForeignKeys) . "\n";
    echo "   - Referential integrity issues: " . count($integrityIssues) . "\n";
    
    if (empty($integrityIssues)) {
        echo "\n✅ OVERALL STATUS: HEALTHY\n";
        echo "   All foreign key relationships are intact\n";
        echo "   No referential integrity issues found\n";
        echo "   Enrollment system is functioning properly\n";
    } else {
        echo "\n⚠️  OVERALL STATUS: ISSUES DETECTED\n";
        echo "   Referential integrity problems found\n";
        echo "   Manual intervention may be required\n";
        
        echo "\n🔧 RECOMMENDED ACTIONS:\n";
        foreach ($integrityIssues as $issue) {
            echo "   - Fix orphaned records in {$issue['table']}.{$issue['column']}\n";
        }
    }
    
    // Step 10: Integration health check
    echo "\n--- STEP 10: INTEGRATION HEALTH CHECK ---\n";
    echo "Checking enrollment system integration points...\n";
    
    // Check if enrollment approval system is working
    if (in_array('enrollments', $existingTables) && in_array('students', $allTables)) {
        echo "✅ Enrollment-to-Student integration available\n";
    } else {
        echo "⚠️  Enrollment-to-Student integration may have issues\n";
    }
    
    // Check notification system
    if (in_array('student_notifications', $allTables)) {
        $notificationCount = $pdo->query("SELECT COUNT(*) as count FROM student_notifications")->fetch()['count'];
        echo "✅ Notification system active ($notificationCount notifications)\n";
    } else {
        echo "⚠️  Notification system not found\n";
    }
    
    echo "\n=== ANALYSIS COMPLETE ===\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ General Error: " . $e->getMessage() . "\n";
}

echo "\n=== SCRIPT EXECUTION COMPLETE ===\n";
?>