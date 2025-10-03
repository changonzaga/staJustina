<?php
// Verify student table removal

$host = 'localhost';
$dbname = 'stajustina_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== VERIFICATION OF STUDENT TABLE REMOVAL ===\n\n";
    
    // Get all tables
    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Current tables in database:\n";
    foreach($tables as $table) {
        echo "- $table\n";
    }
    
    echo "\nStudent table exists: " . (in_array('student', $tables) ? 'YES ❌' : 'NO ✅') . "\n";
    
    // Check for any backup tables created
    $backupTables = array_filter($tables, function($table) {
        return strpos($table, 'student_backup_') === 0;
    });
    
    if (!empty($backupTables)) {
        echo "\nBackup tables found:\n";
        foreach($backupTables as $backup) {
            echo "- $backup\n";
        }
    }
    
    echo "\n=== VERIFICATION COMPLETE ===\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
}
?>