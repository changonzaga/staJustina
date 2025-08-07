<?php
// Database configuration
$host = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'stajustina_db';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Get tables
$sql = "SHOW TABLES";
$result = $conn->query($sql);

echo "<h2>Database Tables in {$database}</h2>";

if ($result->num_rows > 0) {
    echo "<ul>";
    while($row = $result->fetch_row()) {
        echo "<li>{$row[0]}</li>";
        
        // Check table structure
        $tableStructure = $conn->query("DESCRIBE {$row[0]}");
        if ($tableStructure->num_rows > 0) {
            echo "<ul>";
            while($column = $tableStructure->fetch_assoc()) {
                echo "<li>{$column['Field']} - {$column['Type']}</li>";
            }
            echo "</ul>";
        }
    }
    echo "</ul>";
} else {
    echo "<p>No tables found in the database.</p>";
}

$conn->close();
?>