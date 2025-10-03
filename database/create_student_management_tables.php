<?php
// =====================================================
// STUDENT MANAGEMENT TABLES CREATION SCRIPT
// Execute the normalized student management schema
// =====================================================

// Database connection
$host = 'localhost';
$dbname = 'stajustina_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== STUDENT MANAGEMENT SCHEMA CREATION ===\n\n";
    
    // Read the SQL schema file
    $sqlFile = __DIR__ . '/create_student_management_schema.sql';
    if (!file_exists($sqlFile)) {
        throw new Exception("SQL schema file not found: $sqlFile");
    }
    
    $sql = file_get_contents($sqlFile);
    if ($sql === false) {
        throw new Exception("Failed to read SQL schema file");
    }
    
    echo "✅ SQL schema file loaded successfully\n";
    
    // Split SQL into individual statements
    $statements = array_filter(
        array_map('trim', 
            preg_split('/;\s*$/m', $sql)
        ),
        function($stmt) {
            return !empty($stmt) && 
                   !preg_match('/^\s*--/', $stmt) && 
                   !preg_match('/^\s*\/\*/', $stmt);
        }
    );
    
    echo "📋 Found " . count($statements) . " SQL statements to execute\n\n";
    
    // Execute each statement
    $successCount = 0;
    $errorCount = 0;
    
    foreach ($statements as $index => $statement) {
        try {
            // Skip empty statements and comments
            if (empty(trim($statement))) continue;
            
            // Extract statement type for better logging
            $statementType = 'UNKNOWN';
            if (preg_match('/^\s*(CREATE|DROP|ALTER|INSERT|DELIMITER)\s+([A-Z]+)?\s*([a-zA-Z_][a-zA-Z0-9_]*)?/i', $statement, $matches)) {
                $statementType = strtoupper($matches[1]);
                if (isset($matches[2])) {
                    $statementType .= ' ' . strtoupper($matches[2]);
                }
                if (isset($matches[3])) {
                    $statementType .= ' ' . $matches[3];
                }
            }
            
            $pdo->exec($statement);
            echo "✅ [" . ($index + 1) . "] $statementType - Success\n";
            $successCount++;
            
        } catch (PDOException $e) {
            echo "❌ [" . ($index + 1) . "] $statementType - Error: " . $e->getMessage() . "\n";
            $errorCount++;
            
            // Continue with other statements unless it's a critical error
            if (strpos($e->getMessage(), 'already exists') === false) {
                // Log the problematic statement for debugging
                echo "   Statement: " . substr($statement, 0, 100) . "...\n";
            }
        }
    }
    
    echo "\n=== EXECUTION SUMMARY ===\n";
    echo "✅ Successful statements: $successCount\n";
    echo "❌ Failed statements: $errorCount\n";
    
    // Verify table creation
    echo "\n--- VERIFYING CREATED TABLES ---\n";
    $expectedTables = [
        'students',
        'student_auth',
        'student_personal_info',
        'student_family_info',
        'student_address',
        'student_emergency_contacts',
        'student_notifications'
    ];
    
    $createdTables = [];
    $missingTables = [];
    
    foreach ($expectedTables as $table) {
        try {
            $result = $pdo->query("SHOW TABLES LIKE '$table'");
            if ($result->rowCount() > 0) {
                $createdTables[] = $table;
                echo "✅ Table '$table' created successfully\n";
            } else {
                $missingTables[] = $table;
                echo "❌ Table '$table' not found\n";
            }
        } catch (PDOException $e) {
            $missingTables[] = $table;
            echo "❌ Error checking table '$table': " . $e->getMessage() . "\n";
        }
    }
    
    // Check views
    echo "\n--- VERIFYING CREATED VIEWS ---\n";
    $expectedViews = [
        'view_student_complete',
        'view_active_students_contacts'
    ];
    
    foreach ($expectedViews as $view) {
        try {
            $result = $pdo->query("SHOW FULL TABLES WHERE Table_type = 'VIEW' AND Tables_in_$dbname = '$view'");
            if ($result->rowCount() > 0) {
                echo "✅ View '$view' created successfully\n";
            } else {
                echo "❌ View '$view' not found\n";
            }
        } catch (PDOException $e) {
            echo "❌ Error checking view '$view': " . $e->getMessage() . "\n";
        }
    }
    
    // Check functions
    echo "\n--- VERIFYING CREATED FUNCTIONS ---\n";
    try {
        $result = $pdo->query("SHOW FUNCTION STATUS WHERE Db = '$dbname' AND Name = 'generate_student_account_number'");
        if ($result->rowCount() > 0) {
            echo "✅ Function 'generate_student_account_number' created successfully\n";
        } else {
            echo "❌ Function 'generate_student_account_number' not found\n";
        }
    } catch (PDOException $e) {
        echo "❌ Error checking function: " . $e->getMessage() . "\n";
    }
    
    // Test the schema with sample data
    echo "\n--- TESTING SCHEMA WITH SAMPLE DATA ---\n";
    
    if (count($createdTables) >= 6) { // Most tables created
        try {
            // Test student account number generation
            $testAccountNumber = $pdo->query("SELECT generate_student_account_number() as account_number")->fetch();
            if ($testAccountNumber) {
                echo "✅ Account number generation test: {$testAccountNumber['account_number']}\n";
            }
            
            // Test basic insert (will be rolled back)
            $pdo->beginTransaction();
            
            $pdo->exec("
                INSERT INTO students (lrn, grade_level, academic_year) 
                VALUES ('123456789012', 'Grade 7', '2024-2025')
            ");
            
            $studentId = $pdo->lastInsertId();
            
            $pdo->exec("
                INSERT INTO student_personal_info (student_id, first_name, last_name, date_of_birth, gender) 
                VALUES ($studentId, 'Test', 'Student', '2010-01-01', 'Male')
            ");
            
            $pdo->exec("
                INSERT INTO student_auth (student_id, username, email, password_hash) 
                VALUES ($studentId, 'test.student', 'test@example.com', 'hashed_password')
            ");
            
            echo "✅ Sample data insertion test passed\n";
            
            // Rollback test data
            $pdo->rollback();
            echo "✅ Test data rolled back successfully\n";
            
        } catch (PDOException $e) {
            $pdo->rollback();
            echo "❌ Sample data test failed: " . $e->getMessage() . "\n";
        }
    }
    
    // Final status
    echo "\n=== SCHEMA CREATION COMPLETE ===\n";
    
    if (count($createdTables) == count($expectedTables)) {
        echo "🎉 SUCCESS: All student management tables created successfully!\n";
        echo "📋 Created tables: " . implode(', ', $createdTables) . "\n";
        echo "\n✨ The student management system is ready for integration with the enrollment system.\n";
        
        // Display next steps
        echo "\n--- NEXT STEPS ---\n";
        echo "1. Create the enrollment-to-student transfer script\n";
        echo "2. Implement automatic account creation functionality\n";
        echo "3. Set up email and SMS notification services\n";
        echo "4. Create student management interface\n";
        echo "5. Test the complete enrollment workflow\n";
        
    } else {
        echo "⚠️  WARNING: Some tables were not created successfully\n";
        if (!empty($missingTables)) {
            echo "❌ Missing tables: " . implode(', ', $missingTables) . "\n";
        }
        echo "\n🔧 Please review the errors above and re-run the script if needed.\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
    echo "\n🔧 Please check your database connection and try again.\n";
} catch (Exception $e) {
    echo "❌ General Error: " . $e->getMessage() . "\n";
}

echo "\n=== SCRIPT EXECUTION COMPLETE ===\n";
?>