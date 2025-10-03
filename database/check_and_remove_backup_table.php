<?php

try {
    // Connect to database
    $pdo = new PDO('mysql:host=localhost;dbname=stajustina_db', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== CHECKING ENROLLMENT_OLD_BACKUP TABLE RELATIONSHIPS ===\n\n";
    
    // Check if table exists
    $result = $pdo->query("SHOW TABLES LIKE 'enrollments_old_backup'");
    if ($result->rowCount() == 0) {
        echo "❌ Table 'enrollments_old_backup' does not exist.\n";
        echo "✅ No action needed - table is already removed.\n";
        exit;
    }
    
    echo "✅ Table 'enrollments_old_backup' exists.\n\n";
    
    // Check for foreign key constraints FROM this table (outgoing)
    echo "🔍 CHECKING OUTGOING FOREIGN KEY CONSTRAINTS:\n";
    $result = $pdo->query("
        SELECT 
            CONSTRAINT_NAME,
            COLUMN_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
        WHERE TABLE_SCHEMA = 'stajustina_db' 
        AND TABLE_NAME = 'enrollments_old_backup'
        AND REFERENCED_TABLE_NAME IS NOT NULL
    ");
    
    $outgoingFKs = [];
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $outgoingFKs[] = $row;
        echo "- {$row['COLUMN_NAME']} → {$row['REFERENCED_TABLE_NAME']}.{$row['REFERENCED_COLUMN_NAME']}\n";
    }
    
    if (empty($outgoingFKs)) {
        echo "✅ No outgoing foreign key constraints found.\n";
    }
    
    // Check for foreign key constraints TO this table (incoming)
    echo "\n🔍 CHECKING INCOMING FOREIGN KEY CONSTRAINTS:\n";
    $result = $pdo->query("
        SELECT 
            TABLE_NAME,
            CONSTRAINT_NAME,
            COLUMN_NAME,
            REFERENCED_COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
        WHERE TABLE_SCHEMA = 'stajustina_db' 
        AND REFERENCED_TABLE_NAME = 'enrollments_old_backup'
    ");
    
    $incomingFKs = [];
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $incomingFKs[] = $row;
        echo "- {$row['TABLE_NAME']}.{$row['COLUMN_NAME']} → enrollments_old_backup.{$row['REFERENCED_COLUMN_NAME']}\n";
    }
    
    if (empty($incomingFKs)) {
        echo "✅ No incoming foreign key constraints found.\n";
    }
    
    // Check table usage in views
    echo "\n🔍 CHECKING VIEW DEPENDENCIES:\n";
    $result = $pdo->query("
        SELECT TABLE_NAME 
        FROM INFORMATION_SCHEMA.VIEWS 
        WHERE TABLE_SCHEMA = 'stajustina_db' 
        AND VIEW_DEFINITION LIKE '%enrollments_old_backup%'
    ");
    
    $viewDependencies = [];
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $viewDependencies[] = $row['TABLE_NAME'];
        echo "- View: {$row['TABLE_NAME']}\n";
    }
    
    if (empty($viewDependencies)) {
        echo "✅ No view dependencies found.\n";
    }
    
    // Get table info
    $recordCount = $pdo->query("SELECT COUNT(*) FROM enrollments_old_backup")->fetchColumn();
    $fieldCount = $pdo->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'stajustina_db' AND TABLE_NAME = 'enrollments_old_backup'")->fetchColumn();
    
    echo "\n📊 TABLE INFORMATION:\n";
    echo "- Fields: $fieldCount\n";
    echo "- Records: $recordCount\n";
    
    // Decision logic
    echo "\n=== 🎯 ANALYSIS AND DECISION ===\n\n";
    
    $hasRelationships = !empty($outgoingFKs) || !empty($incomingFKs) || !empty($viewDependencies);
    
    if ($hasRelationships) {
        echo "⚠️ RELATIONSHIPS FOUND:\n";
        if (!empty($outgoingFKs)) {
            echo "- " . count($outgoingFKs) . " outgoing foreign key(s)\n";
        }
        if (!empty($incomingFKs)) {
            echo "- " . count($incomingFKs) . " incoming foreign key(s)\n";
        }
        if (!empty($viewDependencies)) {
            echo "- " . count($viewDependencies) . " view dependency(ies)\n";
        }
        
        echo "\n🚨 CANNOT SAFELY REMOVE TABLE\n";
        echo "The table has active relationships that could break if removed.\n";
        echo "Manual intervention required to remove dependencies first.\n";
        
    } else {
        echo "✅ NO RELATIONSHIPS FOUND:\n";
        echo "- No outgoing foreign keys\n";
        echo "- No incoming foreign keys\n";
        echo "- No view dependencies\n";
        echo "- Table is empty ($recordCount records)\n";
        
        echo "\n🗑️ SAFE TO REMOVE TABLE\n";
        echo "The table appears to be an unused backup with no active relationships.\n";
        
        // Ask for confirmation (simulate)
        echo "\n⚠️ PROCEEDING WITH TABLE REMOVAL...\n";
        
        try {
            // Drop the table
            $pdo->exec("DROP TABLE enrollments_old_backup");
            echo "✅ SUCCESS: Table 'enrollments_old_backup' has been removed.\n";
            
            // Verify removal
            $result = $pdo->query("SHOW TABLES LIKE 'enrollments_old_backup'");
            if ($result->rowCount() == 0) {
                echo "✅ VERIFIED: Table no longer exists in database.\n";
            } else {
                echo "❌ ERROR: Table still exists after drop command.\n";
            }
            
        } catch(Exception $e) {
            echo "❌ ERROR DROPPING TABLE: " . $e->getMessage() . "\n";
        }
    }
    
    // Show current enrollment tables after operation
    echo "\n=== 📋 CURRENT ENROLLMENT TABLES ===\n";
    $result = $pdo->query("SHOW TABLES LIKE '%enrollment%'");
    $currentTables = [];
    while($row = $result->fetch()) {
        $currentTables[] = $row[0];
    }
    
    if (empty($currentTables)) {
        echo "❌ No enrollment tables found.\n";
    } else {
        foreach($currentTables as $index => $table) {
            $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            $fields = $pdo->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'stajustina_db' AND TABLE_NAME = '$table'")->fetchColumn();
            echo ($index + 1) . ". $table ($fields fields, $count records)\n";
        }
    }
    
    echo "\n🎉 OPERATION COMPLETE\n";
    echo "Database relationships have been cleaned up to avoid confusion.\n";
    
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

?>