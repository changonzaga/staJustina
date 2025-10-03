<?php
// Check student table dependencies before deletion

// Database connection
$host = 'localhost';
$dbname = 'stajustina_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== CHECKING STUDENT TABLE DEPENDENCIES ===\n\n";
    
    // 1. Check if student table exists
    $checkTable = $pdo->query("SHOW TABLES LIKE 'student'");
    if ($checkTable->rowCount() == 0) {
        echo "❌ Student table does not exist in the database.\n";
        exit;
    }
    echo "✅ Student table exists.\n\n";
    
    // 2. Check foreign key constraints referencing student table
    echo "--- FOREIGN KEY CONSTRAINTS REFERENCING STUDENT TABLE ---\n";
    $foreignKeys = $pdo->query("
        SELECT 
            TABLE_NAME,
            COLUMN_NAME,
            CONSTRAINT_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM 
            INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
        WHERE 
            REFERENCED_TABLE_SCHEMA = '$dbname' 
            AND REFERENCED_TABLE_NAME = 'student'
    ");
    
    $fkCount = 0;
    while ($fk = $foreignKeys->fetch(PDO::FETCH_ASSOC)) {
        $fkCount++;
        echo "⚠️  Table: {$fk['TABLE_NAME']} -> Column: {$fk['COLUMN_NAME']} -> References: student.{$fk['REFERENCED_COLUMN_NAME']}\n";
        echo "   Constraint: {$fk['CONSTRAINT_NAME']}\n";
    }
    
    if ($fkCount == 0) {
        echo "✅ No foreign key constraints found referencing student table.\n";
    }
    echo "\n";
    
    // 3. Check foreign keys FROM student table to other tables
    echo "--- FOREIGN KEY CONSTRAINTS FROM STUDENT TABLE ---\n";
    $studentForeignKeys = $pdo->query("
        SELECT 
            TABLE_NAME,
            COLUMN_NAME,
            CONSTRAINT_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM 
            INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
        WHERE 
            TABLE_SCHEMA = '$dbname' 
            AND TABLE_NAME = 'student'
            AND REFERENCED_TABLE_NAME IS NOT NULL
    ");
    
    $studentFkCount = 0;
    while ($sfk = $studentForeignKeys->fetch(PDO::FETCH_ASSOC)) {
        $studentFkCount++;
        echo "📋 Column: {$sfk['COLUMN_NAME']} -> References: {$sfk['REFERENCED_TABLE_NAME']}.{$sfk['REFERENCED_COLUMN_NAME']}\n";
        echo "   Constraint: {$sfk['CONSTRAINT_NAME']}\n";
    }
    
    if ($studentFkCount == 0) {
        echo "✅ No foreign key constraints found from student table.\n";
    }
    echo "\n";
    
    // 4. Check student table structure
    echo "--- STUDENT TABLE STRUCTURE ---\n";
    $structure = $pdo->query("DESCRIBE student");
    while ($column = $structure->fetch(PDO::FETCH_ASSOC)) {
        echo "📄 {$column['Field']} | {$column['Type']} | {$column['Null']} | {$column['Key']} | {$column['Default']} | {$column['Extra']}\n";
    }
    echo "\n";
    
    // 5. Check data count in student table
    $dataCount = $pdo->query("SELECT COUNT(*) as count FROM student")->fetch();
    echo "--- DATA IN STUDENT TABLE ---\n";
    echo "📊 Total records: {$dataCount['count']}\n\n";
    
    // 6. Check for potential references in other tables (by naming convention)
    echo "--- POTENTIAL REFERENCES BY NAMING CONVENTION ---\n";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tables as $table) {
        if ($table == 'student') continue;
        
        // Check for columns that might reference student table
        $columns = $pdo->query("DESCRIBE `$table`")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($columns as $column) {
            $colName = strtolower($column['Field']);
            if (strpos($colName, 'student') !== false) {
                echo "🔍 Table: $table -> Column: {$column['Field']} (potential student reference)\n";
            }
        }
    }
    echo "\n";
    
    // 7. Generate removal recommendations
    echo "=== REMOVAL RECOMMENDATIONS ===\n";
    
    if ($fkCount > 0) {
        echo "⚠️  WARNING: Foreign key constraints exist that reference the student table.\n";
        echo "   You must drop these constraints first before removing the student table.\n";
        echo "   Or consider updating the referencing tables to handle the missing references.\n\n";
    }
    
    if ($dataCount['count'] > 0) {
        echo "⚠️  WARNING: Student table contains {$dataCount['count']} records.\n";
        echo "   Consider backing up this data before deletion.\n\n";
    }
    
    if ($fkCount == 0 && $dataCount['count'] == 0) {
        echo "✅ Safe to remove: No foreign key constraints and no data in student table.\n";
    }
    
    echo "\n=== ANALYSIS COMPLETE ===\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
}
?>