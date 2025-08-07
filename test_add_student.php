<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=stajustina_db', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // First, check if there are any teachers in the 'teachers' table
    $teacherQuery = "SELECT id, name FROM teachers LIMIT 1";
    $teacherResult = $db->query($teacherQuery);
    $teacher = $teacherResult->fetch(PDO::FETCH_ASSOC);
    
    if (!$teacher) {
        echo "No teachers found in the 'teachers' table. Let's create one for testing.\n";
        
        // Insert a test teacher
        $insertTeacher = $db->prepare("INSERT INTO teachers (account_no, name, subjects, gender, age, status) VALUES (?, ?, ?, ?, ?, ?)");
        $insertTeacher->execute(['T12345', 'Test Teacher', 'Math, Science', 'Male', 35, 'Active']);
        
        $teacherId = $db->lastInsertId();
        echo "Created test teacher with ID: {$teacherId}\n";
    } else {
        $teacherId = $teacher['id'];
        echo "Found existing teacher: {$teacher['name']} (ID: {$teacherId})\n";
    }
    
    // Now try to insert a student with this teacher_id
    echo "Attempting to add a test student with teacher_id = {$teacherId}...\n";
    
    // Generate a unique LRN
    $timestamp = time();
    $lrn = substr("123456{$timestamp}", 0, 12);
    
    $insertStudent = $db->prepare("INSERT INTO student (lrn, name, gender, age, grade_level, section, teacher_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $insertStudent->execute([$lrn, 'Test Student', 'Male', 15, 'Grade 10', 'Section A', $teacherId]);
    
    $studentId = $db->lastInsertId();
    echo "Success! Student added with ID: {$studentId}\n";
    echo "This confirms that the foreign key constraint is working correctly.\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>