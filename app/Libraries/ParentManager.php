<?php

namespace App\Libraries;

use App\Models\ParentModel;
use CodeIgniter\Database\ConnectionInterface;

class ParentManager
{
    protected $db;
    protected $parentModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->parentModel = new ParentModel();
    }

    /**
     * Process enrollment family info and create/link parents
     * This prevents duplicate parent creation during enrollment approval
     */
    public function processEnrollmentParents($enrollmentId, $studentId)
    {
        log_message('info', "Processing enrollment parents for enrollment ID: $enrollmentId, student ID: $studentId");
        
        try {
            $this->db->transStart();

            // Get enrollment family info
            $enrollmentParents = $this->getEnrollmentFamilyInfo($enrollmentId);
            
            if (empty($enrollmentParents)) {
                log_message('info', "No enrollment family info found for enrollment ID: $enrollmentId");
                $this->db->transComplete();
                return ['success' => true, 'message' => 'No parent data to process'];
            }

            $processedParents = [];
            
            foreach ($enrollmentParents as $enrollmentParent) {
                $result = $this->processSingleParent($enrollmentParent, $studentId);
                if ($result['success']) {
                    $processedParents[] = $result;
                } else {
                    log_message('error', "Failed to process parent: " . $result['message']);
                    $this->db->transRollback();
                    return $result;
                }
            }

            // Process enrollment parent addresses
            $this->processEnrollmentParentAddresses($enrollmentId, $processedParents);

            // Process emergency contact information
            $this->processEmergencyContactInfo($enrollmentId, $studentId, $processedParents);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                log_message('error', "Transaction failed for enrollment parent processing: $enrollmentId");
                return ['success' => false, 'message' => 'Database transaction failed'];
            }

            log_message('info', "Successfully processed " . count($processedParents) . " parents for student ID: $studentId");
            
            return [
                'success' => true, 
                'message' => 'Parents processed successfully',
                'processed_parents' => $processedParents
            ];

        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', "Exception in processEnrollmentParents: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error processing parents: ' . $e->getMessage()];
        }
    }

    /**
     * Process a single parent from enrollment data
     */
    private function processSingleParent($enrollmentParent, $studentId)
    {
        // Skip if no name provided
        if (empty($enrollmentParent->first_name) || empty($enrollmentParent->last_name)) {
            return ['success' => true, 'message' => 'Skipped parent with incomplete name'];
        }

        // Prepare parent data
        $parentData = [
            'first_name' => trim($enrollmentParent->first_name),
            'middle_name' => !empty($enrollmentParent->middle_name) ? trim($enrollmentParent->middle_name) : null,
            'last_name' => trim($enrollmentParent->last_name),
            'contact_number' => !empty($enrollmentParent->contact_number) ? trim($enrollmentParent->contact_number) : null
        ];

        // Create or get existing parent
        $parentId = $this->parentModel->createOrGetParent($parentData);
        
        if (!$parentId) {
            return ['success' => false, 'message' => 'Failed to create/get parent record'];
        }

        // Create parent-student relationship
        $relationshipCreated = $this->parentModel->createParentStudentRelationship(
            $studentId, 
            $parentId, 
            $enrollmentParent->relationship_type,
            false, // is_primary_contact - can be set later
            false  // is_emergency_contact - can be set later
        );

        if (!$relationshipCreated) {
            return ['success' => false, 'message' => 'Failed to create parent-student relationship'];
        }

        // Check if relationship already exists for this student and relationship type
        $existingRelationshipQuery = $this->db->query("
            SELECT id FROM student_parent_relationships 
            WHERE student_id = ? AND parent_id = ? AND relationship_type = ?
        ", [$studentId, $parentId, $enrollmentParent->relationship_type]);

        if ($existingRelationshipQuery && $existingRelationshipQuery->getNumRows() === 0) {
            log_message('info', "Parent-student relationship created successfully for student ID: $studentId, parent ID: $parentId as {$enrollmentParent->relationship_type}");
        } else {
            log_message('info', "Parent-student relationship already exists for student ID: $studentId, parent ID: $parentId as {$enrollmentParent->relationship_type}");
        }

        log_message('info', "Successfully processed parent ID: $parentId for student ID: $studentId as {$enrollmentParent->relationship_type}");

        return [
            'success' => true,
            'parent_id' => $parentId,
            'relationship_type' => $enrollmentParent->relationship_type,
            'parent_data' => $parentData,
            'student_id' => $studentId  // Add student_id to the return data
        ];
    }

    /**
     * Get enrollment family info
     */
    private function getEnrollmentFamilyInfo($enrollmentId)
    {
        $query = $this->db->query("
            SELECT * FROM enrollment_family_info 
            WHERE enrollment_id = ?
            ORDER BY relationship_type
        ", [$enrollmentId]);

        return $query->getResult();
    }

    /**
     * Process enrollment parent addresses and transfer them to student_parent_address using parent_id
     */
    private function processEnrollmentParentAddresses($enrollmentId, $processedParents)
    {
        try {
            log_message('info', "Starting parent address processing for enrollment ID: $enrollmentId");
            
            // Check if enrollment_parent_address table exists
            $tableExistsQuery = $this->db->query("SHOW TABLES LIKE 'enrollment_parent_address'");
            if ($tableExistsQuery->getNumRows() === 0) {
                log_message('info', "enrollment_parent_address table not found, skipping address transfer");
                return true;
            }

            // Create a mapping of parent_type to parent_id from processed parents
            $parentTypeToIdMap = [];
            foreach ($processedParents as $parent) {
                if (isset($parent['parent_id']) && isset($parent['relationship_type'])) {
                    $parentTypeToIdMap[$parent['relationship_type']] = $parent['parent_id'];
                }
            }

            if (empty($parentTypeToIdMap)) {
                log_message('info', "No parent IDs found in processed parents, skipping address transfer");
                return true;
            }

            // Get parent addresses from enrollment
            $addressQuery = $this->db->query("
                SELECT * FROM enrollment_parent_address 
                WHERE enrollment_id = ?
                ORDER BY parent_type
            ", [$enrollmentId]);

            if ($addressQuery->getNumRows() === 0) {
                log_message('info', "No parent addresses found for enrollment ID: $enrollmentId");
                return true;
            }

            $addressRecords = $addressQuery->getResult();
            log_message('info', "Found " . count($addressRecords) . " parent address records for enrollment ID: $enrollmentId");

            // Transfer each address record using parent_id
            foreach ($addressRecords as $address) {
                // Find the corresponding parent_id for this parent_type
                $parentId = $parentTypeToIdMap[$address->parent_type] ?? null;
                
                if (!$parentId) {
                    log_message('warning', "No parent ID found for parent type: {$address->parent_type}, skipping address");
                    continue;
                }

                // Check if address already exists for this parent
                $existingQuery = $this->db->query("SELECT id FROM student_parent_address WHERE parent_id = ?", [$parentId]);
                if ($existingQuery->getNumRows() > 0) {
                    log_message('info', "Address already exists for parent ID: $parentId, skipping");
                    continue;
                }

                // Get student_id from processed parents
                $studentId = null;
                foreach ($processedParents as $parent) {
                    if (isset($parent['student_id'])) {
                        $studentId = $parent['student_id'];
                        break;
                    }
                }

                if (!$studentId) {
                    log_message('error', "No student ID found in processed parents for address transfer");
                    continue;
                }

                $addressData = [
                    'student_id' => $studentId,
                    'parent_id' => $parentId,
                    'parent_type' => $address->parent_type,
                    'is_same_as_student' => $address->is_same_as_student ?? 0,
                    'house_number' => $address->house_number,
                    'street' => $address->street,
                    'barangay' => $address->barangay,
                    'municipality' => $address->municipality,
                    'province' => $address->province,
                    'zip_code' => $address->zip_code,
                    'migration_status' => 'migrated_from_enrollment',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $insertResult = $this->db->table('student_parent_address')->insert($addressData);
                
                if (!$insertResult) {
                    $error = $this->db->error();
                    log_message('error', "Failed to insert parent address for parent ID: $parentId, parent type: {$address->parent_type} - " . json_encode($error));
                    return false;
                }

                log_message('info', "Successfully transferred {$address->parent_type} address for parent ID: $parentId");
            }

            log_message('info', "Parent address processing completed successfully for enrollment ID: $enrollmentId");
            return true;

        } catch (\Exception $e) {
            log_message('error', "Exception in processEnrollmentParentAddresses: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Find potential duplicate parents in the system
     */
    public function findPotentialDuplicates($limit = 50)
    {
        $query = $this->db->query("
            SELECT 
                p1.id as parent1_id,
                p1.first_name as parent1_first_name,
                p1.last_name as parent1_last_name,
                p1.contact_number as parent1_contact,
                p2.id as parent2_id,
                p2.first_name as parent2_first_name,
                p2.last_name as parent2_last_name,
                p2.contact_number as parent2_contact,
                COUNT(DISTINCT spr1.student_id) as parent1_children,
                COUNT(DISTINCT spr2.student_id) as parent2_children
            FROM parents p1
            JOIN parents p2 ON (
                p1.first_name = p2.first_name 
                AND p1.last_name = p2.last_name 
                AND p1.id < p2.id
                AND (
                    p1.contact_number = p2.contact_number 
                    OR p1.contact_number IS NULL 
                    OR p2.contact_number IS NULL
                )
            )
            LEFT JOIN student_parent_relationships spr1 ON p1.id = spr1.parent_id
            LEFT JOIN student_parent_relationships spr2 ON p2.id = spr2.parent_id
            GROUP BY p1.id, p2.id
            ORDER BY parent1_children + parent2_children DESC
            LIMIT ?
        ", [$limit]);

        return $query->getResultArray();
    }

    /**
     * Merge duplicate parent records
     */
    public function mergeDuplicateParents($keepParentId, $mergeParentId)
    {
        try {
            $this->db->transStart();

            // Update all student relationships to point to the kept parent
            $this->db->query("
                UPDATE student_parent_relationships 
                SET parent_id = ? 
                WHERE parent_id = ?
                AND NOT EXISTS (
                    SELECT 1 FROM student_parent_relationships spr2 
                    WHERE spr2.parent_id = ? 
                    AND spr2.student_id = student_parent_relationships.student_id 
                    AND spr2.relationship_type = student_parent_relationships.relationship_type
                )
            ", [$keepParentId, $mergeParentId, $keepParentId]);

            // Update student parent addresses - since addresses are tied to student-parent relationships
            // We need to update based on the new parent_id in the relationship context
            $this->db->query("
                UPDATE student_parent_address spa
                JOIN student_parent_relationships spr ON spa.student_id = spr.student_id AND spa.parent_type = spr.relationship_type
                SET spa.parent_id = ? 
                WHERE spr.parent_id = ?
                AND NOT EXISTS (
                    SELECT 1 FROM student_parent_address spa2 
                    JOIN student_parent_relationships spr2 ON spa2.student_id = spr2.student_id AND spa2.parent_type = spr2.relationship_type
                    WHERE spr2.parent_id = ? 
                    AND spa2.student_id = spa.student_id
                    AND spa2.parent_type = spa.parent_type
                )
            ", [$keepParentId, $mergeParentId, $keepParentId]);

            // Delete duplicate relationships
            $this->db->query("DELETE FROM student_parent_relationships WHERE parent_id = ?", [$mergeParentId]);

            // Delete the merged parent record
            $this->db->query("DELETE FROM parents WHERE id = ?", [$mergeParentId]);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return ['success' => false, 'message' => 'Transaction failed during merge'];
            }

            log_message('info', "Successfully merged parent $mergeParentId into parent $keepParentId");
            
            return ['success' => true, 'message' => 'Parents merged successfully'];

        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', "Exception in mergeDuplicateParents: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error merging parents: ' . $e->getMessage()];
        }
    }

    /**
     * Get emergency contacts for a student using parent relationships
     */
    public function getEmergencyContacts($studentId)
    {
        $query = $this->db->query("
            SELECT 
                p.id as parent_id,
                p.first_name,
                p.middle_name,
                p.last_name,
                p.contact_number,
                spr.relationship_type,
                spr.is_primary_contact,
                spr.is_emergency_contact,
                CONCAT(p.first_name, ' ', COALESCE(p.middle_name, ''), ' ', p.last_name) as full_name
            FROM parents p
            JOIN student_parent_relationships spr ON p.id = spr.parent_id
            WHERE spr.student_id = ? AND spr.is_emergency_contact = 1
            ORDER BY spr.is_primary_contact DESC, spr.relationship_type
        ", [$studentId]);

        return $query->getResultArray();
    }

    /**
     * Set emergency contact status for a parent-student relationship
     */
    public function setEmergencyContact($studentId, $parentId, $isEmergency = true, $isPrimary = false)
    {
        try {
            // If setting as primary emergency contact, remove primary status from others
            if ($isPrimary && $isEmergency) {
                $this->db->query("
                    UPDATE student_parent_relationships 
                    SET is_primary_contact = 0 
                    WHERE student_id = ? AND is_emergency_contact = 1
                ", [$studentId]);
            }

            // Update the specific relationship
            $result = $this->db->query("
                UPDATE student_parent_relationships 
                SET is_emergency_contact = ?, is_primary_contact = ?
                WHERE student_id = ? AND parent_id = ?
            ", [$isEmergency ? 1 : 0, $isPrimary ? 1 : 0, $studentId, $parentId]);

            if ($result) {
                log_message('info', "Updated emergency contact status for student $studentId, parent $parentId");
                return ['success' => true, 'message' => 'Emergency contact status updated successfully'];
            } else {
                return ['success' => false, 'message' => 'Failed to update emergency contact status'];
            }

        } catch (\Exception $e) {
            log_message('error', "Exception in setEmergencyContact: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error updating emergency contact: ' . $e->getMessage()];
        }
    }

    /**
     * Get primary emergency contact for a student
     */
    public function getPrimaryEmergencyContact($studentId)
    {
        $query = $this->db->query("
            SELECT 
                p.id as parent_id,
                p.first_name,
                p.middle_name,
                p.last_name,
                p.contact_number,
                spr.relationship_type,
                CONCAT(p.first_name, ' ', COALESCE(p.middle_name, ''), ' ', p.last_name) as full_name
            FROM parents p
            JOIN student_parent_relationships spr ON p.id = spr.parent_id
            WHERE spr.student_id = ? AND spr.is_emergency_contact = 1 AND spr.is_primary_contact = 1
            LIMIT 1
        ", [$studentId]);

        return $query->getRowArray();
    }

    /**
     * Add emergency contact from existing parent or create new one
     */
    public function addEmergencyContact($studentId, $parentData, $relationshipType, $isPrimary = false)
    {
        try {
            $this->db->transStart();

            // Create or get existing parent
            $parentId = $this->parentModel->createOrGetParent($parentData);
            
            if (!$parentId) {
                $this->db->transRollback();
                return ['success' => false, 'message' => 'Failed to create/get parent record'];
            }

            // Create parent-student relationship with emergency contact flag
            $relationshipCreated = $this->parentModel->createParentStudentRelationship(
                $studentId, 
                $parentId, 
                $relationshipType,
                $isPrimary, // is_primary_contact
                true        // is_emergency_contact
            );

            if (!$relationshipCreated) {
                $this->db->transRollback();
                return ['success' => false, 'message' => 'Failed to create parent-student relationship'];
            }

            // If this is set as primary, remove primary status from others
            if ($isPrimary) {
                $this->db->query("
                    UPDATE student_parent_relationships 
                    SET is_primary_contact = 0 
                    WHERE student_id = ? AND parent_id != ? AND is_emergency_contact = 1
                ", [$studentId, $parentId]);
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return ['success' => false, 'message' => 'Database transaction failed'];
            }

            log_message('info', "Successfully added emergency contact for student $studentId");
            
            return [
                'success' => true, 
                'message' => 'Emergency contact added successfully',
                'parent_id' => $parentId
            ];

        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', "Exception in addEmergencyContact: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error adding emergency contact: ' . $e->getMessage()];
        }
    }

    /**
     * Remove emergency contact status from a parent-student relationship
     */
    public function removeEmergencyContact($studentId, $parentId)
    {
        try {
            $result = $this->db->query("
                UPDATE student_parent_relationships 
                SET is_emergency_contact = 0, is_primary_contact = 0
                WHERE student_id = ? AND parent_id = ?
            ", [$studentId, $parentId]);

            if ($result) {
                log_message('info', "Removed emergency contact status for student $studentId, parent $parentId");
                return ['success' => true, 'message' => 'Emergency contact removed successfully'];
            } else {
                return ['success' => false, 'message' => 'Failed to remove emergency contact status'];
            }

        } catch (\Exception $e) {
            log_message('error', "Exception in removeEmergencyContact: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error removing emergency contact: ' . $e->getMessage()];
        }
    }

    /**
     * Get parent statistics and insights
     */
    public function getParentInsights()
    {
        $insights = [];

        // Parents with multiple children
        $multipleChildren = $this->db->query("
            SELECT 
                p.id,
                CONCAT(p.first_name, ' ', p.last_name) as parent_name,
                p.contact_number,
                COUNT(DISTINCT spr.student_id) as children_count,
                GROUP_CONCAT(
                    CONCAT(spi.first_name, ' ', spi.last_name) 
                    ORDER BY spi.first_name 
                    SEPARATOR ', '
                ) as children_names
            FROM parents p
            JOIN student_parent_relationships spr ON p.id = spr.parent_id
            JOIN student_personal_info spi ON spr.student_id = spi.student_id
            GROUP BY p.id
            HAVING children_count > 1
            ORDER BY children_count DESC, parent_name
        ");

        $insights['parents_with_multiple_children'] = $multipleChildren->getResultArray();

        // Potential duplicates
        $insights['potential_duplicates'] = $this->findPotentialDuplicates(20);

        // Statistics
        $insights['statistics'] = $this->parentModel->getParentStatistics();

        return $insights;
    }

    /**
     * Process emergency contact information from enrollment data
     */
    private function processEmergencyContactInfo($enrollmentId, $studentId, $processedParents)
    {
        log_message('info', "Processing emergency contact info for enrollment ID: $enrollmentId, student ID: $studentId");
        
        try {
            // Get emergency contact data from enrollment_emergency_contact table
            $emergencyContactQuery = $this->db->query("
                SELECT * FROM enrollment_emergency_contact 
                WHERE enrollment_id = ?
            ", [$enrollmentId]);
            
            $emergencyContacts = $emergencyContactQuery->getResult();
            
            if (empty($emergencyContacts)) {
                log_message('info', "No emergency contact data found for enrollment ID: $enrollmentId");
                return;
            }
            
            foreach ($emergencyContacts as $emergencyContact) {
                // Find matching parent from processed parents based on relationship type
                $matchingParent = $this->findMatchingParentByRelationship(
                    $processedParents, 
                    $emergencyContact->emergency_contact_relationship
                );
                
                if ($matchingParent) {
                    // Set emergency contact flags
                    $this->setEmergencyContact(
                        $studentId, 
                        $matchingParent['parent_id'], 
                        true, // is_emergency_contact
                        $emergencyContact->is_primary_contact == 1 // is_primary_contact
                    );
                    
                    log_message('info', "Set emergency contact flags for parent ID: {$matchingParent['parent_id']}, relationship: {$emergencyContact->emergency_contact_relationship}, primary: " . ($emergencyContact->is_primary_contact ? 'Yes' : 'No'));
                } else {
                    log_message('warning', "No matching parent found for emergency contact relationship: {$emergencyContact->emergency_contact_relationship}");
                }
            }
            
        } catch (\Exception $e) {
            log_message('error', "Exception in processEmergencyContactInfo: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Find matching parent from processed parents by relationship type
     */
    private function findMatchingParentByRelationship($processedParents, $relationshipType)
    {
        $relationshipType = strtolower(trim($relationshipType));
        
        foreach ($processedParents as $parent) {
            if (strtolower(trim($parent['relationship_type'])) === $relationshipType) {
                return $parent;
            }
        }
        
        return null;
    }
}