<?php

/**
 * Parent Data Normalization Migration Script
 * 
 * This script migrates parent data from the denormalized student_family_info table
 * to a normalized structure with separate tables (parents, 
 * student_parent_relationships) while consolidating
 * duplicate parent records.
 * 
 * Tables created:
 * - parents: Stores unique parent information
 * - student_parent_relationships: Links students to their parents
 * 
 * Usage: php migrate_to_normalized_parents.php [--dry-run] [--verbose]
 */

require_once __DIR__ . '/../vendor/autoload.php';

class ParentNormalizationMigration
{
    private $db;
    private $migrationLog = [];
    private $dryRun = false; // Set to true for testing without actual changes

    public function __construct($dryRun = false)
    {
        $this->dryRun = $dryRun;
        
        // Database connection
        $config = [
            'hostname' => 'localhost',
            'username' => 'root',
            'password' => '',
            'database' => 'stajustina_db',
            'charset'  => 'utf8mb4'
        ];

        try {
            $this->db = new PDO(
                "mysql:host={$config['hostname']};dbname={$config['database']};charset={$config['charset']}",
                $config['username'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
            
            $this->log("Database connection established successfully");
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function migrate()
    {
        $this->log("=== STARTING PARENT NORMALIZATION MIGRATION ===");
        $this->log("Dry run mode: " . ($this->dryRun ? 'YES' : 'NO'));

        try {
            if (!$this->dryRun) {
                $this->db->beginTransaction();
            }

            // Step 1: Create new normalized tables
            $this->createNormalizedTables();

            // Step 2: Migrate existing parent data
            $this->migrateExistingParents();

            // Step 3: Consolidate duplicates
            $this->consolidateDuplicates();

            // Step 4: Create parent addresses from existing data
            $this->migrateParentAddresses();

            // Step 5: Verify migration
            $this->verifyMigration();

            if (!$this->dryRun) {
                $this->db->commit();
                $this->log("Migration completed successfully!");
            } else {
                $this->log("Dry run completed - no changes made to database");
            }

            $this->printSummary();

        } catch (Exception $e) {
            if (!$this->dryRun) {
                $this->db->rollback();
            }
            $this->log("ERROR: Migration failed - " . $e->getMessage());
            throw $e;
        }
    }

    private function createNormalizedTables()
    {
        $this->log("Creating normalized parent tables...");

        $tables = [
            'parents' => "
                CREATE TABLE IF NOT EXISTS parents (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    first_name VARCHAR(100) NOT NULL,
                    middle_name VARCHAR(100),
                    last_name VARCHAR(100) NOT NULL,
                    contact_number VARCHAR(20),
                    email VARCHAR(255),
                    occupation VARCHAR(100),
                    employer VARCHAR(100),
                    monthly_income DECIMAL(10,2),
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    INDEX idx_parent_name (first_name, last_name),
                    INDEX idx_parent_contact (contact_number)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ",
            
            'student_parent_relationships' => "
                CREATE TABLE IF NOT EXISTS student_parent_relationships (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    student_id INT NOT NULL,
                    parent_id INT NOT NULL,
                    relationship_type ENUM('Father', 'Mother', 'Guardian', 'Other') NOT NULL,
                    is_primary_contact BOOLEAN DEFAULT FALSE,
                    is_emergency_contact BOOLEAN DEFAULT FALSE,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
                    FOREIGN KEY (parent_id) REFERENCES parents(id) ON DELETE CASCADE,
                    UNIQUE KEY unique_student_parent_relationship (student_id, parent_id, relationship_type),
                    INDEX idx_student_parent (student_id, parent_id),
                    INDEX idx_parent_student (parent_id, student_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            "
        ];

        foreach ($tables as $tableName => $sql) {
            if (!$this->dryRun) {
                $this->db->exec($sql);
            }
            $this->log("Created table: $tableName");
        }
    }

    private function migrateExistingParents()
    {
        $this->log("Migrating existing parent data from student_family_info...");

        // Get all existing parent records
        $stmt = $this->db->query("
            SELECT DISTINCT 
                first_name, 
                middle_name, 
                last_name, 
                contact_number,
                relationship_type,
                COUNT(*) as occurrence_count,
                GROUP_CONCAT(DISTINCT student_id) as student_ids
            FROM student_family_info 
            WHERE first_name IS NOT NULL 
            AND last_name IS NOT NULL
            GROUP BY first_name, middle_name, last_name, contact_number, relationship_type
            ORDER BY occurrence_count DESC
        ");

        $parentRecords = $stmt->fetchAll();
        $this->log("Found " . count($parentRecords) . " unique parent combinations");

        $migratedCount = 0;
        $parentIdMap = [];

        foreach ($parentRecords as $record) {
            // Create or find existing parent
            $parentKey = $this->generateParentKey($record);
            
            if (!isset($parentIdMap[$parentKey])) {
                $parentId = $this->createParentRecord($record);
                $parentIdMap[$parentKey] = $parentId;
                $migratedCount++;
            } else {
                $parentId = $parentIdMap[$parentKey];
            }

            // Create student-parent relationships
            $studentIds = explode(',', $record['student_ids']);
            foreach ($studentIds as $studentId) {
                $this->createStudentParentRelationship($studentId, $parentId, $record['relationship_type']);
            }
        }

        $this->log("Migrated $migratedCount unique parents with relationships");
    }

    private function createParentRecord($record)
    {
        if ($this->dryRun) {
            return rand(1000, 9999); // Mock ID for dry run
        }

        $stmt = $this->db->prepare("
            INSERT INTO parents (first_name, middle_name, last_name, contact_number)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            trim($record['first_name']),
            !empty($record['middle_name']) ? trim($record['middle_name']) : null,
            trim($record['last_name']),
            !empty($record['contact_number']) ? trim($record['contact_number']) : null
        ]);

        return $this->db->lastInsertId();
    }

    private function createStudentParentRelationship($studentId, $parentId, $relationshipType)
    {
        if ($this->dryRun) {
            return;
        }

        $stmt = $this->db->prepare("
            INSERT IGNORE INTO student_parent_relationships 
            (student_id, parent_id, relationship_type, is_primary_contact)
            VALUES (?, ?, ?, ?)
        ");

        // Set first parent as primary contact
        $isPrimary = $relationshipType === 'father' || $relationshipType === 'mother';

        $stmt->execute([$studentId, $parentId, $relationshipType, $isPrimary]);
    }

    private function consolidateDuplicates()
    {
        $this->log("Consolidating duplicate parent records...");

        if ($this->dryRun) {
            $this->log("Skipping duplicate consolidation in dry run mode");
            return;
        }

        // Find potential duplicates based on name and contact
        $stmt = $this->db->query("
            SELECT 
                p1.id as parent1_id,
                p2.id as parent2_id,
                p1.first_name,
                p1.last_name,
                p1.contact_number
            FROM parents p1
            JOIN parents p2 ON (
                p1.first_name = p2.first_name 
                AND p1.last_name = p2.last_name 
                AND p1.id < p2.id
                AND (
                    p1.contact_number = p2.contact_number 
                    OR (p1.contact_number IS NULL AND p2.contact_number IS NULL)
                )
            )
        ");

        $duplicates = $stmt->fetchAll();
        $consolidatedCount = 0;

        foreach ($duplicates as $duplicate) {
            $this->mergeDuplicateParents($duplicate['parent1_id'], $duplicate['parent2_id']);
            $consolidatedCount++;
        }

        $this->log("Consolidated $consolidatedCount duplicate parent records");
    }

    private function mergeDuplicateParents($keepParentId, $mergeParentId)
    {
        // Update relationships to point to the kept parent
        $this->db->prepare("
            UPDATE student_parent_relationships 
            SET parent_id = ? 
            WHERE parent_id = ?
            AND NOT EXISTS (
                SELECT 1 FROM student_parent_relationships spr2 
                WHERE spr2.parent_id = ? 
                AND spr2.student_id = student_parent_relationships.student_id 
                AND spr2.relationship_type = student_parent_relationships.relationship_type
            )
        ")->execute([$keepParentId, $mergeParentId, $keepParentId]);

        // Delete duplicate relationships
        $this->db->prepare("DELETE FROM student_parent_relationships WHERE parent_id = ?")
                 ->execute([$mergeParentId]);

        // Delete the merged parent
        $this->db->prepare("DELETE FROM parents WHERE id = ?")
                 ->execute([$mergeParentId]);
    }

    /**
     * Migrate parent address information
     * Note: Using existing student_parent_address table instead of creating parent_addresses
     */
    private function migrateParentAddresses()
    {
        $this->log("Skipping parent address migration - using existing student_parent_address table");
        
        // The address information is already handled by the existing student_parent_address table
        // which is linked to students and parent types, providing the necessary address data
        // without requiring a separate parent_addresses table.
        
        return;
    }

    private function verifyMigration()
    {
        $this->log("Verifying migration results...");

        // Count records in each table
        $counts = [];
        $tables = ['parents', 'student_parent_relationships'];

        foreach ($tables as $table) {
            $stmt = $this->db->query("SELECT COUNT(*) as count FROM $table");
            $result = $stmt->fetch();
            $counts[$table] = $result['count'];
        }

        $this->log("Migration verification:");
        foreach ($counts as $table => $count) {
            $this->log("  - $table: $count records");
        }

        // Check for orphaned relationships
        $stmt = $this->db->query("
            SELECT COUNT(*) as count 
            FROM student_parent_relationships spr
            LEFT JOIN parents p ON spr.parent_id = p.id
            WHERE p.id IS NULL
        ");
        $orphaned = $stmt->fetch()['count'];

        if ($orphaned > 0) {
            $this->log("WARNING: Found $orphaned orphaned relationships");
        } else {
            $this->log("✓ No orphaned relationships found");
        }
    }

    private function generateParentKey($record)
    {
        return strtolower(trim($record['first_name'])) . '|' . 
               strtolower(trim($record['last_name'])) . '|' . 
               trim($record['contact_number'] ?? '');
    }

    private function log($message)
    {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[$timestamp] $message";
        $this->migrationLog[] = $logEntry;
        echo $logEntry . "\n";
    }

    private function printSummary()
    {
        $this->log("\n=== MIGRATION SUMMARY ===");
        
        if (!$this->dryRun) {
            // Get final counts
            $parentCount = $this->db->query("SELECT COUNT(*) FROM parents")->fetchColumn();
            $relationshipCount = $this->db->query("SELECT COUNT(*) FROM student_parent_relationships")->fetchColumn();
            $this->log("Final record counts:");
            $this->log("  - Parents: $parentCount");
            $this->log("  - Student-Parent Relationships: $relationshipCount");

            // Parents with multiple children
            $stmt = $this->db->query("
                SELECT COUNT(*) as count 
                FROM parents p
                JOIN student_parent_relationships spr ON p.id = spr.parent_id
                GROUP BY p.id
                HAVING COUNT(DISTINCT spr.student_id) > 1
            ");
            $multipleChildrenCount = $stmt->rowCount();
            $this->log("  - Parents with multiple children: $multipleChildrenCount");
        }

        $this->log("\nMigration completed successfully!");
        $this->log("Log entries: " . count($this->migrationLog));
    }
}

// Command line execution
if (php_sapi_name() === 'cli') {
    $dryRun = in_array('--dry-run', $argv);
    
    echo "Parent Normalization Migration\n";
    echo "=============================\n\n";
    
    if ($dryRun) {
        echo "Running in DRY RUN mode - no changes will be made\n\n";
    }
    
    try {
        $migration = new ParentNormalizationMigration($dryRun);
        $migration->migrate();
    } catch (Exception $e) {
        echo "Migration failed: " . $e->getMessage() . "\n";
        exit(1);
    }
}