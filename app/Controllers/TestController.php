<?php

namespace App\Controllers;

class TestController extends BaseController
{
    public function dbConnection()
    {
        echo "<h2>Database Connection Test</h2>";
        
        // First, let's check the database configuration
        $config = config('Database');
        echo "<h3>Database Configuration:</h3>";
        echo "<p><strong>Hostname:</strong> " . $config->default['hostname'] . "</p>";
        echo "<p><strong>Database:</strong> " . $config->default['database'] . "</p>";
        echo "<p><strong>Username:</strong> " . $config->default['username'] . "</p>";
        echo "<p><strong>Password:</strong> " . (empty($config->default['password']) ? 'NOT SET' : '***HIDDEN***') . "</p>";
        echo "<p><strong>Port:</strong> " . $config->default['port'] . "</p>";
        echo "<p><strong>Driver:</strong> " . $config->default['DBDriver'] . "</p>";
        echo "<hr>";
        
        try {
            echo "<h3>Connection Test:</h3>";
            
            // Check if MySQL extension is loaded
            if (!extension_loaded('mysqli')) {
                echo "<p style='color: red;'>✗ MySQLi extension is not loaded!</p>";
                echo "<p>Please install php-mysqli extension</p>";
                return;
            } else {
                echo "<p style='color: green;'>✓ MySQLi extension is loaded</p>";
            }
            
            // Test basic connectivity first
            echo "<h4>Testing basic MySQL connection:</h4>";
            $hostname = $config->default['hostname'];
            $username = $config->default['username'];
            $password = $config->default['password'];
            $database = $config->default['database'];
            $port = $config->default['port'];
            
            $mysqli = new \mysqli($hostname, $username, $password, $database, $port);
            
            if ($mysqli->connect_error) {
                echo "<p style='color: red;'>✗ MySQL Connection Error: " . $mysqli->connect_error . "</p>";
                echo "<p style='color: red;'>Error Number: " . $mysqli->connect_errno . "</p>";
                
                // Common error codes and solutions
                switch ($mysqli->connect_errno) {
                    case 1045:
                        echo "<p style='color: orange;'>This usually means wrong username/password</p>";
                        break;
                    case 2002:
                        echo "<p style='color: orange;'>This usually means MySQL server is not running or wrong hostname</p>";
                        break;
                    case 1049:
                        echo "<p style='color: orange;'>This usually means the database doesn't exist</p>";
                        break;
                    default:
                        echo "<p style='color: orange;'>Check your MySQL server status and credentials</p>";
                }
                
            } else {
                echo "<p style='color: green;'>✓ Basic MySQL connection successful!</p>";
                echo "<p><strong>Server Info:</strong> " . $mysqli->server_info . "</p>";
                $mysqli->close();
            }
            
            echo "<h4>Testing CodeIgniter Database Connection:</h4>";
            $db = \Config\Database::connect();
            
            // Force connection attempt
            $db->initialize();
            
            // More detailed connection check
            if ($db->connID) {
                echo "<p style='color: green;'>✓ CodeIgniter Database connected successfully!</p>";
                echo "<p><strong>Database:</strong> " . $db->getDatabase() . "</p>";
                echo "<p><strong>Platform:</strong> " . $db->getPlatform() . "</p>";
                echo "<p><strong>Version:</strong> " . $db->getVersion() . "</p>";
                
                // Test a simple query
                $query = $db->query('SELECT 1 as test');
                $result = $query->getRow();
                echo "<p style='color: green;'>✓ Test query successful: " . $result->test . "</p>";
                
                // Test table count (if you have tables)
                $tables = $db->listTables();
                echo "<p><strong>Number of tables:</strong> " . count($tables) . "</p>";
                
                if (!empty($tables)) {
                    echo "<p><strong>Tables:</strong> " . implode(', ', $tables) . "</p>";
                }
                
            } else {
                echo "<p style='color: red;'>✗ CodeIgniter Database connection failed!</p>";
                echo "<p style='color: red;'>Connection ID is null or false</p>";
                
                // Try to get more error info
                $error = $db->error();
                if (!empty($error)) {
                    echo "<p style='color: red;'>Database Error: " . print_r($error, true) . "</p>";
                }
            }
            
        } catch (\Exception $e) {
            echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
            echo "<p style='color: red;'>Error Code: " . $e->getCode() . "</p>";
            echo "<p style='color: red;'>File: " . $e->getFile() . " Line: " . $e->getLine() . "</p>";
        }
        
        echo "<hr>";
        echo "<p><a href='" . base_url() . "'>← Back to Home</a></p>";
    }
}