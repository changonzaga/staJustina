<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=stajustina_db', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Step 1: Drop the existing foreign key constraint
    echo "Step 1: Dropping existing foreign key constraint...\n";
    $db->exec("ALTER TABLE `student` DROP FOREIGN KEY `student_ibfk_1`");
    
    // Step 2: Add the new foreign key constraint referencing the teachers table
    echo "Step 2: Adding new foreign key constraint...\n";
    $db->exec("ALTER TABLE `student` ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL");
    
    echo "Success! Foreign key constraint has been updated.\n";
    echo "The student table now references the 'teachers' table instead of the 'teacher' table.\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "The operation failed. No changes were made to the database.\n";
}
?>