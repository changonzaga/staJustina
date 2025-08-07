<?php
$host = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'stajustina_db';

$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
    die('Connection failed: ' . mysqli_connect_error());
} else {
    echo 'Database connection successful!';
    echo '<br>Database: ' . $database;
}

mysqli_close($connection);
?>