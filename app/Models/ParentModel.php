<?php

namespace App\Models;

use CodeIgniter\Model;

class ParentModel extends Model
{
    protected $table = 'parents';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'first_name',
        'middle_name',
        'last_name',
        'contact_number',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'first_name' => 'required|max_length[100]',
        'last_name' => 'required|max_length[100]',
        'contact_number' => 'permit_empty|max_length[20]',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Find existing parent by name and contact
     */
    public function findExistingParent($firstName, $lastName, $contactNumber = null)
    {
        $builder = $this->builder();
        $builder->where('LOWER(TRIM(first_name))', strtolower(trim($firstName)))
                ->where('LOWER(TRIM(last_name))', strtolower(trim($lastName)));
        
        if ($contactNumber) {
            $builder->where('TRIM(contact_number)', trim($contactNumber));
        }
        
        return $builder->get()->getRowArray();
    }

    /**
     * Create or get existing parent
     */
    public function createOrGetParent($parentData)
    {
        // Check if parent already exists
        $existingParent = $this->findExistingParent(
            $parentData['first_name'],
            $parentData['last_name'],
            $parentData['contact_number'] ?? null
        );

        if ($existingParent) {
            // Update existing parent with any new information
            $updateData = array_filter($parentData, function($value) {
                return !empty($value);
            });
            
            if (!empty($updateData)) {
                $this->update($existingParent['id'], $updateData);
            }
            
            return $existingParent['id'];
        }

        // Create new parent
        $parentId = $this->insert($parentData);
        return $parentId;
    }

    /**
     * Get parents by student ID
     */
    public function getParentsByStudentId($studentId)
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                p.*,
                spr.relationship_type,
                spr.is_primary_contact,
                spr.is_emergency_contact,
                spa.house_number,
                spa.street,
                spa.barangay,
                spa.municipality,
                spa.province,
                spa.zip_code
            FROM parents p
            JOIN student_parent_relationships spr ON p.id = spr.parent_id
            LEFT JOIN student_parent_address spa ON spr.student_id = spa.student_id AND BINARY spa.parent_type = BINARY spr.relationship_type
            WHERE spr.student_id = ?
            ORDER BY spr.relationship_type
        ", [$studentId]);

        return $query->getResultArray();
    }

    /**
     * Get parent by relationship type for a student
     */
    public function getParentByRelationship($studentId, $relationshipType)
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                p.*,
                spr.relationship_type,
                spr.is_primary_contact,
                spr.is_emergency_contact,
                spa.house_number,
                spa.street,
                spa.barangay,
                spa.municipality,
                spa.province,
                spa.zip_code
            FROM parents p
            JOIN student_parent_relationships spr ON p.id = spr.parent_id
            LEFT JOIN student_parent_address spa ON spr.student_id = spa.student_id AND BINARY spa.parent_type = BINARY spr.relationship_type
            WHERE spr.student_id = ? AND spr.relationship_type = ?
            LIMIT 1
        ", [$studentId, $relationshipType]);

        return $query->getRowArray();
    }

    /**
     * Get primary contact parent for a student
     */
    public function getPrimaryContactByStudentId($studentId)
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                p.*,
                spr.relationship_type,
                spr.is_primary_contact,
                spr.is_emergency_contact,
                spa.house_number,
                spa.street,
                spa.barangay,
                spa.municipality,
                spa.province,
                spa.zip_code
            FROM parents p
            JOIN student_parent_relationships spr ON p.id = spr.parent_id
            LEFT JOIN student_parent_address spa ON spr.student_id = spa.student_id AND BINARY spa.parent_type = BINARY spr.relationship_type
            WHERE spr.student_id = ? AND spr.is_primary_contact = 1
            LIMIT 1
        ", [$studentId]);

        return $query->getRowArray();
    }

    /**
     * Get all students for a parent
     */
    public function getStudentsByParentId($parentId)
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                s.*,
                spi.first_name as student_first_name,
                spi.last_name as student_last_name,
                spr.relationship_type
            FROM students s
            JOIN student_personal_info spi ON s.id = spi.student_id
            JOIN student_parent_relationships spr ON s.id = spr.student_id
            WHERE spr.parent_id = ?
            ORDER BY spi.first_name, spi.last_name
        ", [$parentId]);

        return $query->getResultArray();
    }

    /**
     * Create parent-student relationship
     */
    public function createParentStudentRelationship($studentId, $parentId, $relationshipType, $isPrimary = false, $isEmergency = false)
    {
        $db = \Config\Database::connect();
        
        // Check if relationship already exists
        $existing = $db->query("
            SELECT id FROM student_parent_relationships 
            WHERE student_id = ? AND parent_id = ? AND relationship_type = ?
        ", [$studentId, $parentId, $relationshipType]);

        if ($existing->getNumRows() > 0) {
            return true; // Relationship already exists
        }

        $relationshipData = [
            'student_id' => $studentId,
            'parent_id' => $parentId,
            'relationship_type' => $relationshipType,
            'is_primary_contact' => $isPrimary ? 1 : 0,
            'is_emergency_contact' => $isEmergency ? 1 : 0
        ];

        return $db->table('student_parent_relationships')->insert($relationshipData);
    }

    /**
     * Get parent full name
     */
    public function getFullName($parentData)
    {
        $name = $parentData['first_name'];
        if (!empty($parentData['middle_name'])) {
            $name .= ' ' . $parentData['middle_name'];
        }
        $name .= ' ' . $parentData['last_name'];
        return $name;
    }

    /**
     * Get students with their parents (for admin view)
     */
    public function getStudentsWithParents($limit = null, $offset = null)
    {
        $db = \Config\Database::connect();
        
        $limitClause = $limit ? "LIMIT $limit" : '';
        $offsetClause = $offset ? "OFFSET $offset" : '';
        
        $query = $db->query("
            SELECT 
                s.id as student_id,
                spi.first_name as student_first_name,
                spi.last_name as student_last_name,
                s.account_number,
                s.grade_level,
                GROUP_CONCAT(
                    CONCAT(p.first_name, ' ', p.last_name, ' (', spr.relationship_type, ')')
                    SEPARATOR ', '
                ) as parents_info,
                COUNT(DISTINCT spr.parent_id) as parent_count
            FROM students s
            JOIN student_personal_info spi ON s.id = spi.student_id
            LEFT JOIN student_parent_relationships spr ON s.id = spr.student_id
            LEFT JOIN parents p ON spr.parent_id = p.id
            GROUP BY s.id, spi.first_name, spi.last_name, s.account_number, s.grade_level
            ORDER BY spi.first_name, spi.last_name
            $limitClause $offsetClause
        ");

        return $query->getResultArray();
    }

    /**
     * Get all parents with their student information (for parent listing page)
     */
    public function getAllParentsWithStudents($limit = null, $offset = null)
    {
        $db = \Config\Database::connect();
        
        $limitClause = $limit ? "LIMIT $limit" : '';
        $offsetClause = $offset ? "OFFSET $offset" : '';
        
        $query = $db->query("
            SELECT 
                p.id,
                p.first_name,
                p.middle_name,
                p.last_name,
                CONCAT(p.first_name, ' ', COALESCE(p.middle_name, ''), ' ', p.last_name) as full_name,
                p.contact_number,
                p.created_at,
                p.updated_at,
                spr.relationship_type,
                spr.is_primary_contact,
                spr.is_emergency_contact,
                CONCAT(spi.first_name, ' ', COALESCE(spi.middle_name, ''), ' ', spi.last_name) as student_name,
                s.account_number as student_account_number,
                spa.parent_type,
                spa.is_same_as_student,
                spa.house_number,
                spa.street,
                spa.barangay,
                spa.municipality,
                spa.province,
                spa.zip_code
            FROM parents p
            LEFT JOIN student_parent_relationships spr ON p.id = spr.parent_id
            LEFT JOIN students s ON spr.student_id = s.id
            LEFT JOIN student_personal_info spi ON s.id = spi.student_id
            LEFT JOIN student_parent_address spa ON p.id = spa.parent_id
            GROUP BY p.id, spr.id
            ORDER BY p.first_name, p.last_name
            $limitClause $offsetClause
        ");

        return $query->getResultArray();
    }

    /**
     * Search parents by name or contact
     */
    public function searchParents($searchTerm, $limit = 20)
    {
        $builder = $this->builder();
        $builder->groupStart()
                    ->like('first_name', $searchTerm)
                    ->orLike('last_name', $searchTerm)
                    ->orLike('contact_number', $searchTerm)
                ->groupEnd()
                ->limit($limit);
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get parent statistics
     */
    public function getParentStatistics()
    {
        $db = \Config\Database::connect();
        
        $stats = [];
        
        // Total parents
        $stats['total_parents'] = $this->countAll();
        
        // Parents with multiple children
        $multipleChildren = $db->query("
            SELECT COUNT(*) as count
            FROM (
                SELECT parent_id
                FROM student_parent_relationships
                GROUP BY parent_id
                HAVING COUNT(DISTINCT student_id) > 1
            ) as multi_parent
        ");
        $stats['parents_with_multiple_children'] = $multipleChildren->getRow()->count;
        
        // Relationship type distribution
        $relationshipStats = $db->query("
            SELECT relationship_type, COUNT(*) as count
            FROM student_parent_relationships
            GROUP BY relationship_type
        ");
        $stats['relationship_distribution'] = $relationshipStats->getResultArray();
        
        return $stats;
    }
}
