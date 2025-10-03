<?php
$mysqli = new mysqli('localhost', 'root', '', 'stajustina_db');
$result = $mysqli->query("UPDATE enrollments SET enrollment_status = 'pending' WHERE id = 120");
echo $result ? "Enrollment 120 reset to pending status\n" : "Failed to reset enrollment 120\n";
$mysqli->close();
?>