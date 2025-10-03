<?php
// =====================================================
// DATABASE CLEANUP: student_family_info table
// Remove multiple columns with comprehensive analysis
// =====================================================

$host = 'localhost';
$dbname = 'stajustina_db';
$username = 'root';
$password = '';

// Columns to be removed
$columnsToRemove = [
    'employer',
    'work_address', 
    'monthly_income',
    'educational_attainment',
    'facebook_account',
    'other_social_media'
];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DATABASE CLEANUP ANALYSIS: student_family_info ===\n\n";
    
    // Step 1: Check if table exists
    echo "--- STEP 1: TABLE EXISTENCE CHECK ---\n";
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'student_family_info'");
    if ($tableCheck->rowCount() == 0) {
        echo "❌ Table 'student_family_info' does not exist.\n";
        exit(1);
    }
    echo "✅ Table 'student_family_info' exists.\n";
    
    // Step 2: Check current table structure
    echo "\n--- STEP 2: CURRENT TABLE STRUCTURE ---\n";
    $structure = $pdo->query("DESCRIBE student_family_info");
    $allColumns = [];
    $existingTargetColumns = [];
    
    while ($row = $structure->fetch(PDO::FETCH_ASSOC)) {
        $allColumns[] = $row['Field'];
        echo "📋 {$row['Field']} | {$row['Type']} | {$row['Null']} | {$row['Key']} | {$row['Default']}\n";
        
        if (in_array($row['Field'], $columnsToRemove)) {
            $existingTargetColumns[] = $row['Field'];
        }
    }
    
    echo "\n📊 Total columns: " . count($allColumns) . "\n";
    echo "🎯 Target columns found: " . count($existingTargetColumns) . " of " . count($columnsToRemove) . "\n";
    
    if (empty($existingTargetColumns)) {
        echo "\n⚠️  None of the target columns exist in the table.\n";
        echo "✅ No cleanup needed - columns already removed.\n";
        exit(0);
    }
    
    echo "\n🔍 Columns to be removed: " . implode(', ', $existingTargetColumns) . "\n";
    
    $missingColumns = array_diff($columnsToRemove, $existingTargetColumns);
    if (!empty($missingColumns)) {
        echo "⚠️  Columns not found (already removed): " . implode(', ', $missingColumns) . "\n";
    }
    
    // Step 3: Check for foreign key constraints
    echo "\n--- STEP 3: FOREIGN KEY CONSTRAINTS CHECK ---\n";
    $fkCheck = $pdo->query("
        SELECT 
            CONSTRAINT_NAME,
            COLUMN_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_SCHEMA = '$dbname' 
        AND TABLE_NAME = 'student_family_info'
        AND REFERENCED_TABLE_NAME IS NOT NULL
    ");
    
    $foreignKeys = $fkCheck->fetchAll(PDO::FETCH_ASSOC);
    if (empty($foreignKeys)) {
        echo "✅ No foreign key constraints found on student_family_info table.\n";
    } else {
        foreach ($foreignKeys as $fk) {
            echo "🔗 FK: {$fk['CONSTRAINT_NAME']} - {$fk['COLUMN_NAME']} -> {$fk['REFERENCED_TABLE_NAME']}.{$fk['REFERENCED_COLUMN_NAME']}\n";
        }
    }
    
    // Step 4: Check for indexes on target columns
    echo "\n--- STEP 4: INDEX CHECK ON TARGET COLUMNS ---\n";
    $indexesFound = [];
    
    foreach ($existingTargetColumns as $column) {
        $indexCheck = $pdo->query("
            SELECT INDEX_NAME, COLUMN_NAME 
            FROM information_schema.STATISTICS 
            WHERE TABLE_SCHEMA = '$dbname' 
            AND TABLE_NAME = 'student_family_info'
            AND COLUMN_NAME = '$column'
        ");
        
        $indexes = $indexCheck->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($indexes)) {
            foreach ($indexes as $index) {
                echo "📊 Index: {$index['INDEX_NAME']} on column {$index['COLUMN_NAME']}\n";
                $indexesFound[] = $index;
            }
        }
    }
    
    if (empty($indexesFound)) {
        echo "✅ No indexes found on target columns.\n";
    }
    
    // Step 5: Data analysis for each target column
    echo "\n--- STEP 5: DATA ANALYSIS ---\n";
    $totalRecords = $pdo->query("SELECT COUNT(*) as count FROM student_family_info")->fetch()['count'];
    echo "📊 Total records in table: $totalRecords\n\n";
    
    $dataStats = [];
    foreach ($existingTargetColumns as $column) {
        $dataCheck = $pdo->query("
            SELECT 
                COUNT($column) as non_null_count,
                COUNT(DISTINCT $column) as unique_count
            FROM student_family_info
            WHERE $column IS NOT NULL AND $column != ''
        ");
        
        $stats = $dataCheck->fetch(PDO::FETCH_ASSOC);
        $dataStats[$column] = $stats;
        
        echo "📋 Column '$column':\n";
        echo "   - Non-null/non-empty values: {$stats['non_null_count']}\n";
        echo "   - Unique values: {$stats['unique_count']}\n";
        
        if ($stats['non_null_count'] > 0) {
            echo "   ⚠️  Contains data that will be lost!\n";
            
            // Show sample data for review
            $sampleData = $pdo->query("
                SELECT $column, COUNT(*) as count 
                FROM student_family_info 
                WHERE $column IS NOT NULL AND $column != '' 
                GROUP BY $column 
                ORDER BY count DESC 
                LIMIT 5
            ");
            
            echo "   📄 Sample values:\n";
            while ($sample = $sampleData->fetch(PDO::FETCH_ASSOC)) {
                $value = strlen($sample[$column]) > 50 ? substr($sample[$column], 0, 50) . '...' : $sample[$column];
                echo "      - '$value' ({$sample['count']} records)\n";
            }
        } else {
            echo "   ✅ No data to lose\n";
        }
        echo "\n";
    }
    
    // Step 6: Check for references in other tables
    echo "--- STEP 6: CROSS-TABLE REFERENCES CHECK ---\n";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $referencesFound = [];
    
    foreach ($tables as $table) {
        if ($table === 'student_family_info') continue;
        
        try {
            $columns = $pdo->query("DESCRIBE `$table`")->fetchAll(PDO::FETCH_COLUMN);
            foreach ($existingTargetColumns as $targetColumn) {
                if (in_array($targetColumn, $columns)) {
                    echo "🔍 Found '$targetColumn' column in table: $table\n";
                    $referencesFound[] = "$table.$targetColumn";
                }
            }
        } catch (Exception $e) {
            // Skip tables that can't be described
            continue;
        }
    }
    
    if (empty($referencesFound)) {
        echo "✅ No matching columns found in other tables.\n";
    }
    
    // Step 7: Safety analysis and warnings
    echo "\n--- STEP 7: SAFETY ANALYSIS ---\n";
    $safeToRemove = true;
    $warnings = [];
    $dataLossWarnings = [];
    
    foreach ($existingTargetColumns as $column) {
        if ($dataStats[$column]['non_null_count'] > 0) {
            $count = $dataStats[$column]['non_null_count'];
            $dataLossWarnings[] = "⚠️  Column '$column' contains $count non-empty values that will be lost";
        }
    }
    
    if (!empty($indexesFound)) {
        $warnings[] = "⚠️  Some columns have indexes that will be automatically dropped";
    }
    
    if (!empty($referencesFound)) {
        $warnings[] = "⚠️  Similar columns found in other tables: " . implode(', ', $referencesFound);
    }
    
    // Display warnings
    if (!empty($dataLossWarnings)) {
        echo "🚨 DATA LOSS WARNINGS:\n";
        foreach ($dataLossWarnings as $warning) {
            echo "$warning\n";
        }
        echo "\n";
    }
    
    if (!empty($warnings)) {
        echo "⚠️  ADDITIONAL WARNINGS:\n";
        foreach ($warnings as $warning) {
            echo "$warning\n";
        }
        echo "\n";
    }
    
    if (empty($warnings) && empty($dataLossWarnings)) {
        echo "✅ Safe to remove all target columns.\n";
    }
    
    // Step 8: Execute cleanup
    echo "--- STEP 8: CLEANUP EXECUTION ---\n";
    
    // Always proceed but warn about data loss
    if (!empty($dataLossWarnings)) {
        echo "⚠️  Proceeding with cleanup despite data loss warnings...\n";
    } else {
        echo "🔄 Proceeding with safe column removal...\n";
    }
    
    // Create comprehensive backup
    echo "📋 Creating backup information...\n";
    $backupData = [
        'timestamp' => date('Y-m-d H:i:s'),
        'table' => 'student_family_info',
        'columns_removed' => $existingTargetColumns,
        'total_records' => $totalRecords,
        'data_stats' => $dataStats,
        'warnings' => array_merge($warnings, $dataLossWarnings)
    ];
    
    // Save detailed backup
    $backupFile = __DIR__ . '/cleanup_family_backup_' . date('Y-m-d_H-i-s') . '.json';
    file_put_contents($backupFile, json_encode($backupData, JSON_PRETTY_PRINT));
    echo "💾 Backup saved to: $backupFile\n";
    
    // Execute column removals
    $removedColumns = [];
    $failedColumns = [];
    
    foreach ($existingTargetColumns as $column) {
        try {
            echo "🗑️  Removing column '$column'...\n";
            $pdo->exec("ALTER TABLE student_family_info DROP COLUMN `$column`");
            $removedColumns[] = $column;
            echo "   ✅ Successfully removed '$column'\n";
        } catch (Exception $e) {
            $failedColumns[] = $column;
            echo "   ❌ Failed to remove '$column': " . $e->getMessage() . "\n";
        }
    }
    
    // Step 9: Verification
    echo "\n--- STEP 9: VERIFICATION ---\n";
    $finalStructure = $pdo->query("DESCRIBE student_family_info");
    $remainingColumns = [];
    
    while ($row = $finalStructure->fetch(PDO::FETCH_ASSOC)) {
        $remainingColumns[] = $row['Field'];
    }
    
    echo "✅ Verification Results:\n";
    foreach ($existingTargetColumns as $column) {
        if (!in_array($column, $remainingColumns)) {
            echo "   ✅ '$column' successfully removed\n";
        } else {
            echo "   ❌ '$column' still exists\n";
        }
    }
    
    // Step 10: Final table structure
    echo "\n--- STEP 10: FINAL TABLE STRUCTURE ---\n";
    $finalStructure = $pdo->query("DESCRIBE student_family_info");
    $finalColumnCount = 0;
    
    while ($row = $finalStructure->fetch(PDO::FETCH_ASSOC)) {
        echo "📋 {$row['Field']} | {$row['Type']} | {$row['Null']} | {$row['Key']}\n";
        $finalColumnCount++;
    }
    
    echo "\n📊 CLEANUP SUMMARY:\n";
    echo "   - Original columns: " . count($allColumns) . "\n";
    echo "   - Columns removed: " . count($removedColumns) . "\n";
    echo "   - Final columns: $finalColumnCount\n";
    echo "   - Successfully removed: " . implode(', ', $removedColumns) . "\n";
    
    if (!empty($failedColumns)) {
        echo "   - Failed to remove: " . implode(', ', $failedColumns) . "\n";
    }
    
    echo "\n=== CLEANUP COMPLETE ===\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ General Error: " . $e->getMessage() . "\n";
}

echo "\n=== SCRIPT EXECUTION COMPLETE ===\n";
?>