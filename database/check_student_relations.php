<?php
// =====================================================
// STUDENT TABLE RELATIONSHIPS ANALYSIS
// Check all student-related table relations after cleanup
// =====================================================

$host = 'localhost';
$dbname = 'stajustina_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== STUDENT TABLE RELATIONSHIPS ANALYSIS ===\n\n";
    
    // Step 1: Identify all student-related tables
    echo "--- STEP 1: STUDENT-RELATED TABLES ---\n";
    $studentTables = [
        'students',
        'student_personal_info',
        'student_family_info', 
        'student_address',
        'student_emergency_contacts',
        'student_auth',
        'student_notifications'
    ];
    
    $existingTables = [];
    foreach ($studentTables as $table) {
        $check = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($check->rowCount() > 0) {
            $existingTables[] = $table;
            echo "✅ $table - EXISTS\n";
        } else {
            echo "❌ $table - MISSING\n";
        }
    }
    
    echo "\n📊 Found " . count($existingTables) . " of " . count($studentTables) . " student tables\n";
    
    // Step 2: Check foreign key relationships (simplified)
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
    }
    
    // Step 5: Check for missing expected relationships
    echo "\n--- STEP 5: EXPECTED RELATIONSHIPS VALIDATION ---\n";
    $expectedRelationships = [
        'student_personal_info' => ['student_id' => 'students.id'],
        'student_family_info' => ['student_id' => 'students.id'],
        'student_address' => ['student_id' => 'students.id'],
        'student_emergency_contacts' => ['student_id' => 'students.id'],
        'student_auth' => ['student_id' => 'students.id'],
        'student_notifications' => ['student_id' => 'students.id']
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
    if (in_array('students', $existingTables)) {
        $studentCount = $pdo->query("SELECT COUNT(*) as count FROM students")->fetch()['count'];
        echo "📊 Base students table: $studentCount records\n";
        
        foreach (['student_personal_info', 'student_family_info', 'student_address', 'student_auth'] as $relatedTable) {
            if (in_array($relatedTable, $existingTables)) {
                $relatedCount = $pdo->query("SELECT COUNT(DISTINCT student_id) as count FROM `$relatedTable`")->fetch()['count'];
                $percentage = $studentCount > 0 ? round(($relatedCount / $studentCount) * 100, 1) : 0;
                echo "   - $relatedTable: $relatedCount students ($percentage%)\n";
            }
        }
    }
    
    // Step 7: Check for removed columns impact
    echo "\n--- STEP 7: CLEANUP IMPACT ANALYSIS ---\n";
    echo "Checking if removed columns are still referenced anywhere...\n";
    
    $removedColumns = [
        'student_personal_info' => ['blood_type'],
        'student_family_info' => ['employer', 'work_address', 'monthly_income', 'educational_attainment', 'facebook_account', 'other_social_media'],
        'student_address' => ['landmark', 'coordinates_lat', 'coordinates_lng']
    ];
    
    foreach ($removedColumns as $table => $columns) {
        if (in_array($table, $existingTables)) {
            echo "\n📋 Checking $table for removed columns:\n";
            $tableStructure = $pdo->query("DESCRIBE `$table`")->fetchAll(PDO::FETCH_COLUMN);
            
            foreach ($columns as $removedColumn) {
                if (in_array($removedColumn, $tableStructure)) {
                    echo "   ❌ Column '$removedColumn' still exists (cleanup incomplete)\n";
                } else {
                    echo "   ✅ Column '$removedColumn' successfully removed\n";
                }
            }
        }
    }
    
    // Step 8: Summary and recommendations
    echo "\n--- STEP 8: ANALYSIS SUMMARY ---\n";
    echo "\n📊 STATISTICS:\n";
    echo "   - Student tables found: " . count($existingTables) . "/" . count($studentTables) . "\n";
    echo "   - Foreign key relationships: " . count($allForeignKeys) . "\n";
    echo "   - Referential integrity issues: " . count($integrityIssues) . "\n";
    
    if (empty($integrityIssues)) {
        echo "\n✅ OVERALL STATUS: HEALTHY\n";
        echo "   All foreign key relationships are intact\n";
        echo "   No referential integrity issues found\n";
        echo "   Database cleanup did not break relationships\n";
    } else {
        echo "\n⚠️  OVERALL STATUS: ISSUES DETECTED\n";
        echo "   Referential integrity problems found\n";
        echo "   Manual intervention may be required\n";
        
        echo "\n🔧 RECOMMENDED ACTIONS:\n";
        foreach ($integrityIssues as $issue) {
            echo "   - Fix orphaned records in {$issue['table']}.{$issue['column']}\n";
        }
    }
    
    echo "\n=== ANALYSIS COMPLETE ===\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ General Error: " . $e->getMessage() . "\n";
}

echo "\n=== SCRIPT EXECUTION COMPLETE ===\n";
?>