<?php

// Load the environment
require 'app/Config/Paths.php';
$paths = new \Config\Paths();
require rtrim($paths->systemDirectory, '\/') . '/bootstrap.php';

// Get database configuration
$config = new \Config\Database();
$default = $config->default;

// Display configuration (without password)
echo "<h2>Database Configuration</h2>";
echo "<pre>";
echo "Hostname: {$default['hostname']}\n";
echo "Username: {$default['username']}\n";
echo "Database: {$default['database']}\n";
echo "Driver: {$default['DBDriver']}\n";
echo "Port: {$default['port']}\n";
echo "</pre>";

// Test direct connection
echo "<h2>Testing Direct Connection</h2>";
try {
    $mysqli = new \mysqli(
        $default['hostname'],
        $default['username'],
        $default['password'],
        $default['database'],
        $default['port']
    );
    
    if ($mysqli->connect_error) {
        echo "<p style='color:red'>Direct connection failed: {$mysqli->connect_error}</p>";
    } else {
        echo "<p style='color:green'>Direct connection successful!</p>";
        
        // Test a simple query
        $result = $mysqli->query("SHOW TABLES");
        if ($result) {
            echo "<h3>Tables in database:</h3>";
            echo "<ul>";
            while ($row = $result->fetch_row()) {
                echo "<li>{$row[0]}</li>";
            }
            echo "</ul>";
            $result->free();
        }
        
        $mysqli->close();
    }
} catch (\Exception $e) {
    echo "<p style='color:red'>Exception: {$e->getMessage()}</p>";
}

// Test CodeIgniter connection
echo "<h2>Testing CodeIgniter Connection</h2>";
try {
    $db = \Config\Database::connect();
    if ($db->connID) {
        echo "<p style='color:green'>CodeIgniter connection successful!</p>";
        
        // Test a simple query
        $tables = $db->listTables();
        echo "<h3>Tables via CodeIgniter:</h3>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>{$table}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color:red'>CodeIgniter connection failed!</p>";
    }
} catch (\Exception $e) {
    echo "<p style='color:red'>CodeIgniter Exception: {$e->getMessage()}</p>";
}