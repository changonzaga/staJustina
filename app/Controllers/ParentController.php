<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ParentModel;
use App\Models\StudentModel;
use App\Models\StudentParentAddressModel;
use App\Models\StudentParentRelationshipModel;

class ParentController extends BaseController
{
    protected $parentModel;
    protected $studentModel;
    protected $relationshipModel;
    protected $addressModel;
    
    public function __construct()
    {
        $this->parentModel = new ParentModel();
        $this->studentModel = new StudentModel();
        $this->relationshipModel = new StudentParentRelationshipModel();
        $this->addressModel = new StudentParentAddressModel();
    }
    
    public function index()
    {
        // Use direct mysqli connection
        $mysqli = new \mysqli('localhost', 'root', '', 'stajustina_db');
        
        if ($mysqli->connect_error) {
            return view('backend/admin/parents/parent', ['parents' => []]);
        }
        
        $stmt = $mysqli->prepare("
            SELECT 
                p.*,
                spr.relationship_type,
                spr.student_id,
                spr.is_primary_contact,
                spr.is_emergency_contact,
                spi.first_name as student_first_name,
                spi.last_name as student_last_name
            FROM parents p
            LEFT JOIN student_parent_relationships spr ON p.id = spr.parent_id
            LEFT JOIN student_personal_info spi ON spr.student_id = spi.student_id
            ORDER BY p.created_at DESC
        ");
        
        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();
            $data['parents'] = [];
            while ($row = $result->fetch_assoc()) {
                $data['parents'][] = $row;
            }
            $stmt->close();
        } else {
            $data['parents'] = [];
        }
        
        $mysqli->close();
        return view('backend/admin/parents/parent', $data);
    }
    
    public function create()
    {
        // Use direct mysqli connection
        $mysqli = new \mysqli('localhost', 'root', '', 'stajustina_db');
        
        if ($mysqli->connect_error) {
            return view('backend/admin/parents/create', ['students' => []]);
        }
        
        $stmt = $mysqli->prepare("
            SELECT s.id, spi.first_name, spi.last_name
            FROM students s
            LEFT JOIN student_personal_info spi ON s.id = spi.student_id
            ORDER BY spi.first_name ASC
        ");
        
        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();
            $data['students'] = [];
            while ($row = $result->fetch_assoc()) {
                $data['students'][] = $row;
            }
            $stmt->close();
        } else {
            $data['students'] = [];
        }
        
        $mysqli->close();
        return view('backend/admin/parents/create', $data);
    }
    
    public function store()
    {
        // Debug: Log all POST data
        error_log('Parent creation POST data: ' . json_encode($this->request->getPost()));
        
        // Normalize inputs and coerce relationship to DB-supported ENUM
        $relationshipInput = trim((string) $this->request->getPost('relationship_type'));
        $relationshipNormalized = strtolower($relationshipInput);
        $supportedEnum = ['father','mother','guardian'];
        if (!in_array($relationshipNormalized, $supportedEnum, true)) {
            // Map any unsupported relationship types to 'guardian' to satisfy ENUM constraint
            $relationshipNormalized = 'guardian';
        }
        
        error_log('Normalized relationship type: ' . $relationshipNormalized);

        $rules = [
            'student_id' => 'required|integer',
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_number' => 'required',
            'relationship_type' => 'required',
            'is_primary_contact' => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Use direct mysqli connection for the entire operation to avoid transaction conflicts
        $mysqli = new \mysqli('localhost', 'root', '', 'stajustina_db');
        
        if ($mysqli->connect_error) {
            return redirect()->back()->withInput()->with('errors', ['parent' => 'Database connection failed.']);
        }
        
        // Start transaction
        $mysqli->autocommit(false);
        
        try {
            // Prepare parent data
            $parentData = [
                'first_name' => trim((string) $this->request->getPost('first_name')),
                'middle_name' => trim((string) $this->request->getPost('middle_name')) ?: null,
                'last_name' => trim((string) $this->request->getPost('last_name')),
                'contact_number' => trim((string) $this->request->getPost('contact_number')),
            ];

            // Insert parent using direct SQL
            $stmt = $mysqli->prepare("INSERT INTO parents (first_name, middle_name, last_name, contact_number) VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                throw new \Exception('Failed to prepare parent insert: ' . $mysqli->error);
            }
            
            $stmt->bind_param('ssss', 
                $parentData['first_name'],
                $parentData['middle_name'],
                $parentData['last_name'],
                $parentData['contact_number']
            );
            
            if (!$stmt->execute()) {
                throw new \Exception('Failed to insert parent: ' . $stmt->error);
            }
            
            $parentId = $mysqli->insert_id;
            $stmt->close();
            
            if (!$parentId) {
                throw new \Exception('Failed to get parent ID');
            }

            $studentId = (int) $this->request->getPost('student_id');

            // Validate student exists
            if (!$studentId || $studentId <= 0) {
                throw new \Exception('Invalid student selected.');
            }

            // Verify student exists in database
            $stmt = $mysqli->prepare("SELECT id FROM students WHERE id = ?");
            if (!$stmt) {
                throw new \Exception('Failed to prepare student check: ' . $mysqli->error);
            }
            
            $stmt->bind_param('i', $studentId);
            $stmt->execute();
            $result = $stmt->get_result();
            $studentExists = $result->num_rows > 0;
            $stmt->close();
            
            if (!$studentExists) {
                throw new \Exception('Selected student does not exist.');
            }

            // Create relationship record
            $isPrimaryContact = (int) ($this->request->getPost('is_primary_contact') === '1');
            $isEmergencyContact = (int) ($this->request->getPost('is_emergency_contact') === '1');
            
            // If primary contact is Yes, automatically set emergency contact to Yes
            if ($isPrimaryContact === 1) {
                $isEmergencyContact = 1;
            }
            
            // If emergency contact is not set (readonly field), use the primary contact value
            if ($isPrimaryContact === 1) {
                $isEmergencyContact = 1;
            }
            
            // Check if relationship already exists
            $stmt = $mysqli->prepare("SELECT id FROM student_parent_relationships WHERE student_id = ? AND parent_id = ? AND relationship_type = ?");
            if (!$stmt) {
                throw new \Exception('Failed to prepare relationship check: ' . $mysqli->error);
            }
            
            $stmt->bind_param('iis', $studentId, $parentId, $relationshipNormalized);
            $stmt->execute();
            $result = $stmt->get_result();
            $existingRelationship = $result->num_rows > 0;
            $stmt->close();
            
            if ($existingRelationship) {
                throw new \Exception('This parent relationship already exists for the selected student.');
            }
            
            // Insert relationship
            $stmt = $mysqli->prepare("INSERT INTO student_parent_relationships (student_id, parent_id, relationship_type, is_primary_contact, is_emergency_contact) VALUES (?, ?, ?, ?, ?)");
            if (!$stmt) {
                throw new \Exception('Failed to prepare relationship insert: ' . $mysqli->error);
            }
            
            $stmt->bind_param('iisii', 
                $studentId,
                $parentId, 
                $relationshipNormalized,
                $isPrimaryContact,
                $isEmergencyContact
            );
            
            if (!$stmt->execute()) {
                throw new \Exception('Failed to insert relationship: ' . $stmt->error);
            }
            
            $relationshipId = $mysqli->insert_id;
            $stmt->close();
            
            // Insert parent address if provided
            $isSameAsStudent = (int) ($this->request->getPost('is_same_as_student') === '1');
            $houseNumber = trim((string) $this->request->getPost('house_number')) ?: null;
            $street = trim((string) $this->request->getPost('street')) ?: null;
            $barangay = trim((string) $this->request->getPost('barangay')) ?: null;
            $municipality = trim((string) $this->request->getPost('municipality')) ?: null;
            $province = trim((string) $this->request->getPost('province')) ?: null;
            $zipCode = trim((string) $this->request->getPost('zip_code')) ?: null;
            
            // Only insert address if at least one field is provided or if it's marked as same as student
            if ($isSameAsStudent || $houseNumber || $street || $barangay || $municipality || $province || $zipCode) {
                $stmt = $mysqli->prepare("INSERT INTO student_parent_address (student_id, parent_id, parent_type, is_same_as_student, house_number, street, barangay, municipality, province, zip_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                if (!$stmt) {
                    throw new \Exception('Failed to prepare address insert: ' . $mysqli->error);
                }
                
                $stmt->bind_param('iisissssss', 
                    $studentId,
                    $parentId,
                    $relationshipNormalized,
                    $isSameAsStudent,
                    $houseNumber,
                    $street,
                    $barangay,
                    $municipality,
                    $province,
                    $zipCode
                );
                
                if (!$stmt->execute()) {
                    throw new \Exception('Failed to insert address: ' . $stmt->error);
                }
                
                $stmt->close();
            }
            
            // Commit the transaction
            $mysqli->commit();
            $mysqli->close();
            
            // Success - redirect with success message
            return redirect()->to(route_to('admin.parent'))->with('success', 'Parent created successfully.');
            
        } catch (\Exception $e) {
            error_log('Exception during parent creation: ' . $e->getMessage());
            $mysqli->rollback();
            $mysqli->close();
            return redirect()->back()->withInput()->with('errors', ['parent' => 'Failed to create parent. Error: ' . $e->getMessage()]);
        }
    }
    
    public function edit($id)
    {
        // Get parent data with relationship information and address data using direct mysqli
        $mysqli = new \mysqli('localhost', 'root', '', 'stajustina_db');
        
        if ($mysqli->connect_error) {
            return redirect()->to('admin/parent')->with('error', 'Database connection failed.');
        }
        
        $stmt = $mysqli->prepare("
            SELECT 
                p.*,
                spr.relationship_type,
                spr.student_id,
                spr.is_primary_contact,
                spr.is_emergency_contact,
                spa.house_number,
                spa.street,
                spa.barangay,
                spa.municipality,
                spa.province,
                spa.zip_code,
                spa.is_same_as_student
            FROM parents p
            LEFT JOIN student_parent_relationships spr ON p.id = spr.parent_id
            LEFT JOIN student_parent_address spa ON p.id = spa.parent_id
            WHERE p.id = ?
            GROUP BY p.id
            LIMIT 1
        ");
        
        if (!$stmt) {
            $mysqli->close();
            return redirect()->to('admin/parent')->with('error', 'Database query failed.');
        }
        
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data['parent'] = $result->fetch_assoc();
        $stmt->close();
        
        // Get students using direct mysqli
        $stmt = $mysqli->prepare("
            SELECT s.id, spi.first_name, spi.last_name
            FROM students s
            LEFT JOIN student_personal_info spi ON s.id = spi.student_id
            ORDER BY spi.first_name ASC
        ");
        
        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();
            $data['students'] = [];
            while ($row = $result->fetch_assoc()) {
                $data['students'][] = $row;
            }
            $stmt->close();
        }
        
        $mysqli->close();
        
        if (empty($data['parent'])) {
            return redirect()->to('admin/parent')->with('error', 'Parent/Guardian not found');
        }
        
        return view('backend/admin/parents/edit', $data);
    }
    
    public function update($id)
    {
        error_log('=== PARENT UPDATE START ===');
        error_log('Parent ID: ' . $id);
        error_log('POST data: ' . json_encode($this->request->getPost()));
        
        // Use direct mysqli connection
        $mysqli = new \mysqli('localhost', 'root', '', 'stajustina_db');
        
        if ($mysqli->connect_error) {
            return redirect()->to('admin/parent')->with('error', 'Database connection failed.');
        }
        
        try {
            // Check if parent exists
            $stmt = $mysqli->prepare("SELECT id FROM parents WHERE id = ?");
            if (!$stmt) {
                throw new \Exception('Failed to prepare parent check: ' . $mysqli->error);
            }
            
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $parentExists = $result->num_rows > 0;
            $stmt->close();
            
            if (!$parentExists) {
                throw new \Exception('Parent not found for ID: ' . $id);
            }

            $rules = [
                'first_name' => 'required',
                'last_name' => 'required',
                'contact_number' => 'required',
                'relationship_type' => 'required'
            ];

            if (!$this->validate($rules)) {
                throw new \Exception('Validation failed: ' . json_encode($this->validator->getErrors()));
            }

            $data = [
                'first_name' => $this->request->getPost('first_name'),
                'middle_name' => $this->request->getPost('middle_name'),
                'last_name' => $this->request->getPost('last_name'),
                'contact_number' => $this->request->getPost('contact_number')
            ];

            // Update parent data using direct SQL
            $stmt = $mysqli->prepare("UPDATE parents SET first_name = ?, middle_name = ?, last_name = ?, contact_number = ? WHERE id = ?");
            if (!$stmt) {
                throw new \Exception('Failed to prepare parent update: ' . $mysqli->error);
            }
            
            $stmt->bind_param('ssssi', 
                $data['first_name'],
                $data['middle_name'],
                $data['last_name'],
                $data['contact_number'],
                $id
            );
            
            if (!$stmt->execute()) {
                throw new \Exception('Failed to update parent: ' . $stmt->error);
            }
            $stmt->close();
            
            // Handle primary and emergency contact logic
            $isPrimaryContact = (int) ($this->request->getPost('is_primary_contact') === '1');
            $isEmergencyContact = (int) ($this->request->getPost('is_emergency_contact') === '1');
            
            // If primary contact is Yes, automatically set emergency contact to Yes
            if ($isPrimaryContact === 1) {
                $isEmergencyContact = 1;
            }
            
            $relationshipType = $this->request->getPost('relationship_type');
            
            // Update relationship using direct SQL
            $stmt = $mysqli->prepare("UPDATE student_parent_relationships SET relationship_type = ?, is_primary_contact = ?, is_emergency_contact = ?, updated_at = NOW() WHERE parent_id = ?");
            if (!$stmt) {
                throw new \Exception('Failed to prepare relationship update: ' . $mysqli->error);
            }
            
            $stmt->bind_param('siii', 
                $relationshipType,
                $isPrimaryContact,
                $isEmergencyContact,
                $id
            );
            
            if (!$stmt->execute()) {
                throw new \Exception('Failed to update relationship: ' . $stmt->error);
            }
            $stmt->close();
            
            // Handle address data using direct SQL
            $isSameAsStudent = (int) ($this->request->getPost('is_same_as_student') ? 1 : 0);
            
            // Check if address exists
            $stmt = $mysqli->prepare("SELECT id FROM student_parent_address WHERE parent_id = ?");
            if (!$stmt) {
                throw new \Exception('Failed to prepare address check: ' . $mysqli->error);
            }
            
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $existingAddress = $result->num_rows > 0;
            $stmt->close();
            
            if ($existingAddress) {
                // Update existing address
                $stmt = $mysqli->prepare("UPDATE student_parent_address SET is_same_as_student = ?, house_number = ?, street = ?, barangay = ?, municipality = ?, province = ?, zip_code = ?, updated_at = NOW() WHERE parent_id = ?");
                if (!$stmt) {
                    throw new \Exception('Failed to prepare address update: ' . $mysqli->error);
                }
                
                $houseNumber = $isSameAsStudent ? null : (trim((string) $this->request->getPost('house_number')) ?: null);
                $street = $isSameAsStudent ? null : (trim((string) $this->request->getPost('street')) ?: null);
                $barangay = $isSameAsStudent ? null : (trim((string) $this->request->getPost('barangay')) ?: null);
                $municipality = $isSameAsStudent ? null : (trim((string) $this->request->getPost('municipality')) ?: null);
                $province = $isSameAsStudent ? null : (trim((string) $this->request->getPost('province')) ?: null);
                $zipCode = $isSameAsStudent ? null : (trim((string) $this->request->getPost('zip_code')) ?: null);
                
                $stmt->bind_param('issssssi', 
                    $isSameAsStudent,
                    $houseNumber,
                    $street,
                    $barangay,
                    $municipality,
                    $province,
                    $zipCode,
                    $id
                );
                
                if (!$stmt->execute()) {
                    throw new \Exception('Failed to update address: ' . $stmt->error);
                }
                $stmt->close();
            } else {
                // Insert new address
                $stmt = $mysqli->prepare("INSERT INTO student_parent_address (student_id, parent_id, parent_type, is_same_as_student, house_number, street, barangay, municipality, province, zip_code, migration_status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'new', NOW(), NOW())");
                if (!$stmt) {
                    throw new \Exception('Failed to prepare address insert: ' . $mysqli->error);
                }
                
                // Get student_id from the relationship
                $stmtCheck = $mysqli->prepare("SELECT student_id FROM student_parent_relationships WHERE parent_id = ? LIMIT 1");
                $stmtCheck->bind_param('i', $id);
                $stmtCheck->execute();
                $result = $stmtCheck->get_result();
                $studentId = $result->fetch_assoc()['student_id'];
                $stmtCheck->close();
                
                $houseNumber = $isSameAsStudent ? null : (trim((string) $this->request->getPost('house_number')) ?: null);
                $street = $isSameAsStudent ? null : (trim((string) $this->request->getPost('street')) ?: null);
                $barangay = $isSameAsStudent ? null : (trim((string) $this->request->getPost('barangay')) ?: null);
                $municipality = $isSameAsStudent ? null : (trim((string) $this->request->getPost('municipality')) ?: null);
                $province = $isSameAsStudent ? null : (trim((string) $this->request->getPost('province')) ?: null);
                $zipCode = $isSameAsStudent ? null : (trim((string) $this->request->getPost('zip_code')) ?: null);
                
                $stmt->bind_param('iisissssss', 
                    $studentId,
                    $id,
                    $relationshipType,
                    $isSameAsStudent,
                    $houseNumber,
                    $street,
                    $barangay,
                    $municipality,
                    $province,
                    $zipCode
                );
                
                if (!$stmt->execute()) {
                    throw new \Exception('Failed to insert address: ' . $stmt->error);
                }
                $stmt->close();
            }
            
            $mysqli->close();
            return redirect()->to('admin/parent')->with('success', 'Parent/Guardian updated successfully');
            
        } catch (\Exception $e) {
            error_log('Exception during parent update: ' . $e->getMessage());
            $mysqli->close();
            return redirect()->back()->withInput()->with('errors', ['parent' => 'Failed to update parent. Error: ' . $e->getMessage()]);
        }
    }
    
    public function view($id)
    {
        // Use direct mysqli connection
        $mysqli = new \mysqli('localhost', 'root', '', 'stajustina_db');
        
        if ($mysqli->connect_error) {
            return redirect()->to('admin/parent')->with('error', 'Database connection failed.');
        }
        
        // Get parent data with relationship and address information
        $stmt = $mysqli->prepare("
            SELECT 
                p.*,
                spr.relationship_type,
                spr.student_id,
                spr.is_primary_contact,
                spr.is_emergency_contact,
                spa.house_number,
                spa.street,
                spa.barangay,
                spa.municipality,
                spa.province,
                spa.zip_code,
                spa.is_same_as_student
            FROM parents p
            LEFT JOIN student_parent_relationships spr ON p.id = spr.parent_id
            LEFT JOIN student_parent_address spa ON p.id = spa.parent_id
            WHERE p.id = ?
            GROUP BY p.id
            LIMIT 1
        ");
        
        if (!$stmt) {
            $mysqli->close();
            return redirect()->to('admin/parent')->with('error', 'Database query failed.');
        }
        
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data['parent'] = $result->fetch_assoc();
        $stmt->close();
        
        if (empty($data['parent'])) {
            $mysqli->close();
            return redirect()->to('admin/parent')->with('error', 'Parent/Guardian not found');
        }
        
        // Get student information
        $stmt = $mysqli->prepare("SELECT * FROM students WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param('i', $data['parent']['student_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $data['student'] = $result->fetch_assoc();
            $stmt->close();
        }
        
        $mysqli->close();
        
        return view('backend/admin/parents/parent_profile', $data);
    }
    
    public function data($id)
    {
        // Use direct mysqli connection
        $mysqli = new \mysqli('localhost', 'root', '', 'stajustina_db');
        
        if ($mysqli->connect_error) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Database connection failed'
            ]);
        }
        
        // Get parent data with relationship and address information
        $stmt = $mysqli->prepare("
            SELECT 
                p.*,
                spr.relationship_type,
                spr.student_id,
                spr.is_primary_contact,
                spr.is_emergency_contact,
                spa.house_number,
                spa.street,
                spa.barangay,
                spa.municipality,
                spa.province,
                spa.zip_code,
                spa.is_same_as_student,
                spa.created_at as address_created_at,
                spa.updated_at as address_updated_at,
                spi.first_name as student_first_name,
                spi.last_name as student_last_name
            FROM parents p
            LEFT JOIN student_parent_relationships spr ON p.id = spr.parent_id
            LEFT JOIN student_parent_address spa ON p.id = spa.parent_id
            LEFT JOIN student_personal_info spi ON spr.student_id = spi.student_id
            WHERE p.id = ?
            GROUP BY p.id
            LIMIT 1
        ");
        
        if (!$stmt) {
            $mysqli->close();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Database query failed'
            ]);
        }
        
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $parent = $result->fetch_assoc();
        $stmt->close();
        $mysqli->close();
        
        if (empty($parent)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Parent/Guardian not found'
            ]);
        }
        
        // Format the data for the modal
        $formattedData = [
            'id' => $parent['id'],
            'first_name' => $parent['first_name'],
            'middle_name' => $parent['middle_name'],
            'last_name' => $parent['last_name'],
            'contact_number' => $parent['contact_number'],
            'relationship_type' => ucfirst($parent['relationship_type']),
            'parent_type' => ucfirst($parent['relationship_type']),
            'student_name' => trim($parent['student_first_name'] . ' ' . $parent['student_last_name']),
            'is_same_as_student' => $parent['is_same_as_student'] ? 'Yes' : 'No',
            'house_number' => $parent['house_number'],
            'street' => $parent['street'],
            'barangay' => $parent['barangay'],
            'municipality' => $parent['municipality'],
            'province' => $parent['province'],
            'zip_code' => $parent['zip_code'],
            'created_at' => $parent['created_at'] ? date('M d, Y H:i:s', strtotime($parent['created_at'])) : null,
            'updated_at' => $parent['updated_at'] ? date('M d, Y H:i:s', strtotime($parent['updated_at'])) : null
        ];
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $formattedData
        ]);
    }
    
    public function delete($id)
    {
        // Debug: Log delete attempt
        error_log('Delete parent attempt - ID: ' . $id);
        error_log('Request method: ' . $this->request->getMethod());
        error_log('POST data: ' . json_encode($this->request->getPost()));
        
        // Use direct mysqli connection
        $mysqli = new \mysqli('localhost', 'root', '', 'stajustina_db');
        
        if ($mysqli->connect_error) {
            error_log('Database connection failed: ' . $mysqli->connect_error);
            return redirect()->to('admin/parent')->with('error', 'Database connection failed.');
        }
        
        try {
            // Validate ID
            if (!is_numeric($id) || $id <= 0) {
                throw new \Exception('Invalid parent ID');
            }
            
            // Check if parent exists
                $stmt = $mysqli->prepare("SELECT id, first_name, last_name FROM parents WHERE id = ?");
            if (!$stmt) {
                throw new \Exception('Failed to prepare parent check: ' . $mysqli->error);
            }
            
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $parent = $result->fetch_assoc();
            $stmt->close();
            
            if (empty($parent)) {
                throw new \Exception('Parent/Guardian not found');
            }
            
            error_log('Deleting parent: ' . $parent['first_name'] . ' ' . $parent['last_name'] . ' (ID: ' . $id . ')');
            
            // Check if this parent is the only primary contact for any student
            $stmt = $mysqli->prepare("
                SELECT spr.student_id, COUNT(*) as total_primary_contacts
                FROM student_parent_relationships spr 
                WHERE spr.student_id IN (
                    SELECT student_id FROM student_parent_relationships WHERE parent_id = ? AND is_primary_contact = 1
                ) AND spr.is_primary_contact = 1
                GROUP BY spr.student_id
                HAVING total_primary_contacts <= 1
            ");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $protectedStudents = [];
            while ($row = $result->fetch_assoc()) {
                $protectedStudents[] = $row['student_id'];
            }
            $stmt->close();
            
            if (!empty($protectedStudents)) {
                throw new \Exception('Cannot delete parent: This parent is the only primary contact for student(s) with ID(s): ' . implode(', ', $protectedStudents) . '. Please assign another primary contact first.');
            }
            
            // Start transaction
            $mysqli->autocommit(false);
            
            // Delete from student_parent_address table
            $stmt = $mysqli->prepare("DELETE FROM student_parent_address WHERE parent_id = ?");
            if (!$stmt) {
                throw new \Exception('Failed to prepare address delete: ' . $mysqli->error);
            }
            $stmt->bind_param('i', $id);
            if (!$stmt->execute()) {
                throw new \Exception('Failed to delete address: ' . $stmt->error);
            }
            $stmt->close();
            
            // Delete from student_parent_relationships table
            $stmt = $mysqli->prepare("DELETE FROM student_parent_relationships WHERE parent_id = ?");
            if (!$stmt) {
                throw new \Exception('Failed to prepare relationship delete: ' . $mysqli->error);
            }
            $stmt->bind_param('i', $id);
            if (!$stmt->execute()) {
                throw new \Exception('Failed to delete relationship: ' . $stmt->error);
            }
            $stmt->close();
            
            // No profile picture stored in normalized parents schema; nothing to delete here
            
            // Delete from parents table
            $stmt = $mysqli->prepare("DELETE FROM parents WHERE id = ?");
            if (!$stmt) {
                throw new \Exception('Failed to prepare parent delete: ' . $mysqli->error);
            }
            $stmt->bind_param('i', $id);
            if (!$stmt->execute()) {
                throw new \Exception('Failed to delete parent: ' . $stmt->error);
            }
            $stmt->close();
            
            // Commit transaction
            $mysqli->commit();
            $mysqli->close();
            
            error_log('Parent deleted successfully - ID: ' . $id);
            
            // Check if this is an AJAX request
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Parent/Guardian and all related data deleted successfully'
                ]);
            }
            
            return redirect()->to('admin/parent')->with('success', 'Parent/Guardian and all related data deleted successfully');
            
        } catch (\Exception $e) {
            error_log('Error deleting parent - ID: ' . $id . ', Error: ' . $e->getMessage());
            $mysqli->rollback();
            $mysqli->close();
            
            // Check if this is an AJAX request
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error deleting parent: ' . $e->getMessage()
                ])->setStatusCode(500);
            }
            
            return redirect()->to('admin/parent')->with('error', 'Error deleting parent: ' . $e->getMessage());
        }
    }
}