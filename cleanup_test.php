<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=stajustina_db', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get the last inserted student (our test student)
    $query = "SELECT id, name FROM student ORDER BY id DESC LIMIT 1";
    $result = $db->query($query);
    $student = $result->fetch(PDO::FETCH_ASSOC);
    
    if ($student) {
        echo "Found test student: {$student['name']} (ID: {$student['id']})\n";
        
        // Delete the test student
        $delete = $db->prepare("DELETE FROM student WHERE id = ?");
        $delete->execute([$student['id']]);
        
        echo "Test student has been removed.\n";
    } else {
        echo "No students found to clean up.\n";
    }
    
    echo "Cleanup completed.\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>