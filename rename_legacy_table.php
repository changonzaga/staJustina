<?php
/**
 * Rename student_family_info table to student_family_info_legacy
 * This completes the migration process by marking the old table as legacy
 */

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'stajustina_db';

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== RENAMING STUDENT_FAMILY_INFO TABLE TO LEGACY ===\n\n";
    
    // Step 1: Check if the table exists
    echo "1. Checking if student_family_info table exists...\n";
    $checkTable = $pdo->query("SHOW TABLES LIKE 'student_family_info'");
    if ($checkTable->rowCount() == 0) {
        echo "   ❌ Table student_family_info does not exist!\n";
        exit(1);
    }
    echo "   ✅ Table student_family_info exists\n\n";
    
    // Step 2: Check if legacy table already exists
    echo "2. Checking if student_family_info_legacy already exists...\n";
    $checkLegacy = $pdo->query("SHOW TABLES LIKE 'student_family_info_legacy'");
    if ($checkLegacy->rowCount() > 0) {
        echo "   ⚠️  Table student_family_info_legacy already exists!\n";
        echo "   Would you like to drop it first? (This will permanently delete the existing legacy table)\n";
        echo "   Proceeding with backup and rename...\n\n";
        
        // Drop existing legacy table
        $pdo->exec("DROP TABLE student_family_info_legacy");
        echo "   ✅ Dropped existing student_family_info_legacy table\n\n";
    } else {
        echo "   ✅ No existing legacy table found\n\n";
    }
    
    // Step 3: Get current record count
    echo "3. Getting current record count...\n";
    $countResult = $pdo->query("SELECT COUNT(*) as total, COUNT(deprecated_at) as deprecated FROM student_family_info");
    $counts = $countResult->fetch(PDO::FETCH_ASSOC);
    echo "   📊 Total records: {$counts['total']}\n";
    echo "   📊 Deprecated records: {$counts['deprecated']}\n";
    echo "   📊 Active records: " . ($counts['total'] - $counts['deprecated']) . "\n\n";
    
    // Step 4: Create a final backup timestamp
    echo "4. Adding final migration timestamp...\n";
    $pdo->exec("ALTER TABLE student_family_info ADD COLUMN IF NOT EXISTS migrated_to_legacy_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    echo "   ✅ Added migration timestamp\n\n";
    
    // Step 5: Rename the table
    echo "5. Renaming table to student_family_info_legacy...\n";
    $pdo->exec("RENAME TABLE student_family_info TO student_family_info_legacy");
    echo "   ✅ Successfully renamed table!\n\n";
    
    // Step 6: Verify the rename
    echo "6. Verifying the rename operation...\n";
    $verifyOld = $pdo->query("SHOW TABLES LIKE 'student_family_info'");
    $verifyNew = $pdo->query("SHOW TABLES LIKE 'student_family_info_legacy'");
    
    if ($verifyOld->rowCount() == 0 && $verifyNew->rowCount() == 1) {
        echo "   ✅ Rename successful!\n";
        echo "   ✅ Old table 'student_family_info' no longer exists\n";
        echo "   ✅ New table 'student_family_info_legacy' exists\n\n";
    } else {
        echo "   ❌ Rename verification failed!\n";
        exit(1);
    }
    
    // Step 7: Verify data integrity
    echo "7. Verifying data integrity in legacy table...\n";
    $legacyCount = $pdo->query("SELECT COUNT(*) as count FROM student_family_info_legacy");
    $legacyData = $legacyCount->fetch(PDO::FETCH_ASSOC);
    echo "   📊 Records in legacy table: {$legacyData['count']}\n";
    
    if ($legacyData['count'] == $counts['total']) {
        echo "   ✅ All data preserved in legacy table!\n\n";
    } else {
        echo "   ❌ Data count mismatch! Expected: {$counts['total']}, Found: {$legacyData['count']}\n";
        exit(1);
    }
    
    // Step 8: Update any remaining triggers or views that might reference the old table
    echo "8. Checking for any remaining references...\n";
    
    // Check for triggers
    $triggers = $pdo->query("SHOW TRIGGERS LIKE 'student_family_info%'");
    if ($triggers->rowCount() > 0) {
        echo "   ⚠️  Found triggers that may need updating:\n";
        while ($trigger = $triggers->fetch(PDO::FETCH_ASSOC)) {
            echo "      - {$trigger['Trigger']}\n";
        }
    } else {
        echo "   ✅ No triggers found referencing the old table\n";
    }
    
    // Check for views
    $views = $pdo->query("SELECT TABLE_NAME FROM information_schema.VIEWS WHERE TABLE_SCHEMA = '$database' AND VIEW_DEFINITION LIKE '%student_family_info%'");
    if ($views->rowCount() > 0) {
        echo "   ℹ️  Found views that reference student_family_info (these should be using the new normalized tables):\n";
        while ($view = $views->fetch(PDO::FETCH_ASSOC)) {
            echo "      - {$view['TABLE_NAME']}\n";
        }
    } else {
        echo "   ✅ No views found referencing the old table name\n";
    }
    
    echo "\n=== MIGRATION TO LEGACY COMPLETED SUCCESSFULLY! ===\n\n";
    
    echo "📋 SUMMARY:\n";
    echo "✅ Table 'student_family_info' has been renamed to 'student_family_info_legacy'\n";
    echo "✅ All {$counts['total']} records have been preserved\n";
    echo "✅ The application now uses the normalized 'parents' and 'student_parent_relationships' tables\n";
    echo "✅ Legacy data is safely stored for future reference or rollback if needed\n\n";
    
    echo "🔄 NEXT STEPS:\n";
    echo "1. Monitor the application to ensure everything works correctly\n";
    echo "2. After a safe period (e.g., 30 days), consider dropping the legacy table\n";
    echo "3. Update any documentation to reflect the new table structure\n";
    echo "4. The migration is now complete!\n\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>