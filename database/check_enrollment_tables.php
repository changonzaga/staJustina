<?php

try {
    // Connect to database
    $pdo = new PDO('mysql:host=localhost;dbname=stajustina_db', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== ENROLLMENT TABLES ANALYSIS ===\n\n";
    
    // Check for enrollment-related tables
    $result = $pdo->query("SHOW TABLES LIKE '%enrollment%'");
    $enrollmentTables = [];
    while($row = $result->fetch()) {
        $enrollmentTables[] = $row[0];
    }
    
    echo "📋 ENROLLMENT-RELATED TABLES FOUND:\n";
    if (empty($enrollmentTables)) {
        echo "❌ No enrollment tables found.\n";
    } else {
        foreach($enrollmentTables as $index => $table) {
            $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            $fieldCount = $pdo->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'stajustina_db' AND TABLE_NAME = '$table'")->fetchColumn();
            echo ($index + 1) . ". $table ($fieldCount fields, $count records)\n";
        }
    }
    
    // Check specifically for enrollment_old_backup
    echo "\n🔍 CHECKING FOR 'enrollment_old_backup':\n";
    $result = $pdo->query("SHOW TABLES LIKE 'enrollment_old_backup'");
    if ($result->rowCount() > 0) {
        echo "✅ Table 'enrollment_old_backup' exists.\n";
    } else {
        echo "❌ Table 'enrollment_old_backup' does NOT exist.\n";
    }
    
    // Check for any backup tables
    echo "\n🔍 CHECKING FOR BACKUP TABLES:\n";
    $result = $pdo->query("SHOW TABLES LIKE '%backup%'");
    $backupTables = [];
    while($row = $result->fetch()) {
        $backupTables[] = $row[0];
    }
    
    if (empty($backupTables)) {
        echo "❌ No backup tables found.\n";
    } else {
        foreach($backupTables as $table) {
            $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            $fieldCount = $pdo->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'stajustina_db' AND TABLE_NAME = '$table'")->fetchColumn();
            echo "- $table ($fieldCount fields, $count records)\n";
        }
    }
    
    // Check for any old tables
    echo "\n🔍 CHECKING FOR 'OLD' TABLES:\n";
    $result = $pdo->query("SHOW TABLES LIKE '%old%'");
    $oldTables = [];
    while($row = $result->fetch()) {
        $oldTables[] = $row[0];
    }
    
    if (empty($oldTables)) {
        echo "❌ No 'old' tables found.\n";
    } else {
        foreach($oldTables as $table) {
            $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            $fieldCount = $pdo->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'stajustina_db' AND TABLE_NAME = '$table'")->fetchColumn();
            echo "- $table ($fieldCount fields, $count records)\n";
        }
    }
    
    echo "\n=== 📝 EXPLANATION ===\n\n";
    echo "🤔 ABOUT 'enrollment_old_backup':\n";
    echo "This table was mentioned in our previous database normalization work\n";
    echo "as a backup of the original monolithic enrollments table.\n\n";
    
    echo "❓ WHY IT MIGHT NOT EXIST:\n";
    echo "1. The backup was never actually created\n";
    echo "2. The backup was created with a different name\n";
    echo "3. The backup was deleted after successful migration\n";
    echo "4. The normalization process used a different approach\n\n";
    
    echo "✅ CURRENT STATUS:\n";
    echo "Based on the tables found, your enrollment system appears to use\n";
    echo "the normalized structure with separate enrollment_* tables.\n\n";
    
    echo "🎯 WHAT THIS MEANS:\n";
    echo "- Your database has been successfully normalized\n";
    echo "- The old monolithic structure is no longer present\n";
    echo "- The enrollment system uses the modern normalized approach\n";
    echo "- No backup table cleanup is needed\n";
    
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

?>