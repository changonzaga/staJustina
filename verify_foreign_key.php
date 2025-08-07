<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=stajustina_db', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Query to get foreign key information
    $query = "SELECT
                TABLE_NAME,
                COLUMN_NAME,
                CONSTRAINT_NAME,
                REFERENCED_TABLE_NAME,
                REFERENCED_COLUMN_NAME
              FROM
                INFORMATION_SCHEMA.KEY_COLUMN_USAGE
              WHERE
                TABLE_SCHEMA = 'stajustina_db' AND
                TABLE_NAME = 'student' AND
                REFERENCED_TABLE_NAME IS NOT NULL";
    
    $result = $db->query($query);
    
    echo "Foreign Key Constraints for the student table:\n";
    echo "------------------------------------------\n";
    
    $found = false;
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $found = true;
        echo "Constraint Name: {$row['CONSTRAINT_NAME']}\n";
        echo "Column: {$row['COLUMN_NAME']}\n";
        echo "References: {$row['REFERENCED_TABLE_NAME']}({$row['REFERENCED_COLUMN_NAME']})\n";
        echo "------------------------------------------\n";
    }
    
    if (!$found) {
        echo "No foreign key constraints found for the student table.\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>