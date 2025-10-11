<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'enrollment_number',
        'student_id',
        'school_year',
        'grade_level',
        'enrollment_type',
        'enrollment_status',
        'approved_by',
        'approved_at',
        'declined_reason',
        'created_at',
        'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    protected $callbacks = [
        'beforeInsert' => ['generateEnrollmentNumber']
    ];

    /**
     * Generate enrollment number before insert
     */
    protected function generateEnrollmentNumber(array $data)
    {
        if (!isset($data['data']['enrollment_number'])) {
            $data['data']['enrollment_number'] = $this->createEnrollmentNumber();
        }
        return $data;
    }

    /**
     * Create unique enrollment number with race condition protection
     */
    public function createEnrollmentNumber()
    {
        $db = \Config\Database::connect();
        $maxAttempts = 10;
        $attempt = 0;
        
        while ($attempt < $maxAttempts) {
            $date = date('Ymd');
            $prefix = 'ENR-' . $date . '-';
            
            // Use database-level locking to prevent race conditions
            $db->query('LOCK TABLES enrollments READ');
            
            try {
                // Get the last enrollment number for today
                $result = $db->query(
                    "SELECT enrollment_number FROM enrollments " .
                    "WHERE enrollment_number LIKE '{$prefix}%' " .
                    "ORDER BY enrollment_number DESC LIMIT 1"
                );
                
                $lastEnrollment = $result->getRow();
                
                if ($lastEnrollment && $lastEnrollment->enrollment_number) {
                    // Extract the sequence number and increment
                    $lastNumber = substr($lastEnrollment->enrollment_number, -4);
                    $nextNumber = str_pad((int)$lastNumber + 1, 4, '0', STR_PAD_LEFT);
                } else {
                    // First enrollment for today
                    $nextNumber = '0001';
                }
                
                $enrollmentNumber = $prefix . $nextNumber;
                
                // Unlock tables
                $db->query('UNLOCK TABLES');
                
                // Verify uniqueness (double-check)
                $existingCheck = $db->query(
                    "SELECT id FROM enrollments WHERE enrollment_number = '{$enrollmentNumber}'"
                )->getRow();
                
                if (!$existingCheck) {
                    return $enrollmentNumber;
                }
                
            } catch (\Exception $e) {
                // Unlock tables in case of error
                $db->query('UNLOCK TABLES');
                throw $e;
            }
            
            $attempt++;
            // Small delay before retry
            usleep(100000); // 0.1 seconds
        }
        
        // Fallback: use timestamp-based unique number
        return 'ENR-' . date('Ymd') . '-' . str_pad(date('His'), 4, '0', STR_PAD_LEFT);
    }

    /**
     * Submit new enrollment with normalized data structure
     */
    public function submitEnrollment($formData, $documents = [])
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Validate required fields first
            $validation = $this->validateRequiredFields($formData);
            if (!$validation['valid']) {
                $db->transRollback();
                return ['success' => false, 'message' => 'Validation failed: ' . implode(', ', $validation['errors'])];
            }
            
            // Check for duplicate LRN
            if ($this->checkDuplicateLRN($formData)) {
                $db->transRollback();
                return ['success' => false, 'message' => 'LRN already exists in the system'];
            }

            // 1. Insert main enrollment record with auto-generated enrollment number
            $enrollmentData = [
                'enrollment_number' => $this->createEnrollmentNumber(), // Explicitly generate enrollment number
                'school_year' => $formData['school_year'] ?? date('Y') . '-' . (date('Y') + 1),
                'grade_level' => $formData['grade_level'],
                'section' => '', // Will be assigned later
                'enrollment_type' => $formData['enrollment_type'] ?? 'new',
                'enrollment_status' => 'pending'
            ];
            
            $enrollmentId = $this->insert($enrollmentData);
            if (!$enrollmentId) {
                throw new \Exception('Failed to create enrollment record');
            }

            // 2. Insert personal information
            try {
                log_message('info', 'Starting personal info insert for enrollment ID: ' . $enrollmentId);
                $result = $this->insertPersonalInfo($enrollmentId, $formData);
                log_message('info', 'Personal info insert result: ' . ($result ? 'SUCCESS' : 'FAILED'));
                if (!$result) {
                    throw new \Exception('Personal info insert returned false');
                }
            } catch (\Exception $e) {
                log_message('error', 'Personal info insert exception: ' . $e->getMessage());
                throw new \Exception('Failed to insert personal information: ' . $e->getMessage());
            }

            // 3. Insert family information
            try {
                log_message('info', 'Starting family info insert for enrollment ID: ' . $enrollmentId);
                $result = $this->insertFamilyInfo($enrollmentId, $formData);
                log_message('info', 'Family info insert result: ' . ($result ? 'SUCCESS' : 'FAILED'));
                if (!$result) {
                    throw new \Exception('Family info insert returned false');
                }
            } catch (\Exception $e) {
                log_message('error', 'Family info insert exception: ' . $e->getMessage());
                throw new \Exception('Failed to insert family information: ' . $e->getMessage());
            }

            // 3.5. Insert address information
            try {
                log_message('info', 'Starting address info insert for enrollment ID: ' . $enrollmentId);
                $result = $this->insertAddressInfo($enrollmentId, $formData);
                log_message('info', 'Address info insert result: ' . ($result ? 'SUCCESS' : 'FAILED'));
            } catch (\Exception $e) {
                // Log the error but don't fail the entire enrollment
                log_message('error', 'Failed to insert address information for enrollment ID ' . $enrollmentId . ': ' . $e->getMessage());
                // Continue without failing - address info is supplementary
            }

            // 3.6. Insert parent/guardian address information
            try {
                log_message('info', 'Starting parent address info insert for enrollment ID: ' . $enrollmentId);
                $result = $this->insertParentAddressInfo($enrollmentId, $formData);
                log_message('info', 'Parent address info insert result: ' . ($result ? 'SUCCESS' : 'FAILED'));
            } catch (\Exception $e) {
                // Log the error but don't fail the entire enrollment
                log_message('error', 'Failed to insert parent address information for enrollment ID ' . $enrollmentId . ': ' . $e->getMessage());
                // Continue without failing - parent address info is supplementary
            }

            // 4. Insert academic history
            try {
                log_message('info', 'Starting academic history insert for enrollment ID: ' . $enrollmentId);
                $result = $this->insertAcademicHistory($enrollmentId, $formData);
                log_message('info', 'Academic history insert result: ' . ($result ? 'SUCCESS' : 'FAILED'));
                if (!$result) {
                    throw new \Exception('Academic history insert returned false');
                }
            } catch (\Exception $e) {
                log_message('error', 'Academic history insert exception: ' . $e->getMessage());
                throw new \Exception('Failed to insert academic history: ' . $e->getMessage());
            }

            // 5. Insert SHS details (if applicable)
            if (in_array($formData['grade_level'], ['Grade 11', 'Grade 12'])) {
                try {
                    $this->insertSHSDetails($enrollmentId, $formData);
                } catch (\Exception $e) {
                    throw new \Exception('Failed to insert SHS details: ' . $e->getMessage());
                }
            }

            // 6. Insert disability information (if applicable)
            if (isset($formData['has_disability']) && $formData['has_disability'] === 'Yes') {
                try {
                    // Check for disability table (try both expected name and actual temporary name)
                    $db = \Config\Database::connect();
                    $disabilityTableName = null;
                    
                    // First try the expected table name
                    if ($db->query("SHOW TABLES LIKE 'enrollment_disabilities'")->getNumRows() > 0) {
                        $disabilityTableName = 'enrollment_disabilities';
                    } else {
                        // Try the temporary table name
                        $tempTables = $db->query("SHOW TABLES LIKE 'enrollment_disabilities_temp'")->getResult();
            if (!empty($tempTables)) {
                $disabilityTableName = 'enrollment_disabilities_temp';
            } else {
                $tempTables = $db->query("SHOW TABLES LIKE 'enrollment_disabilities_temp_%'")->getResult();
                if (!empty($tempTables)) {
                    $disabilityTableName = array_values((array)$tempTables[0])[0];
                }
            }
                    }
                    
                    if ($disabilityTableName) {
                        $this->insertDisabilityInfo($enrollmentId, $formData, $disabilityTableName);
                        log_message('info', 'Using disability table: ' . $disabilityTableName . ' for enrollment ID: ' . $enrollmentId);
                    } else {
                        // Log that disability info was skipped due to missing table
                        log_message('warning', 'Disability information skipped - enrollment_disabilities table not found. Enrollment ID: ' . $enrollmentId);
                        // Continue without failing the entire enrollment
                    }
                } catch (\Exception $e) {
                    // Log the error but don't fail the entire enrollment
                    log_message('error', 'Failed to insert disability information for enrollment ID ' . $enrollmentId . ': ' . $e->getMessage());
                    // Continue without failing - disability info is not critical for enrollment completion
                }
            }

            // 7. Insert emergency contacts (if any parent/guardian is selected)
            try {
                $this->insertEmergencyContacts($enrollmentId, $formData);
            } catch (\Exception $e) {
                // Log the error but don't fail the entire enrollment
                log_message('error', 'Failed to insert emergency contacts for enrollment ID ' . $enrollmentId . ': ' . $e->getMessage());
                // Continue without failing - emergency contacts are supplementary
            }

            // 8. Insert documents
            if (!empty($documents)) {
                try {
                    $this->insertDocuments($enrollmentId, $documents);
                } catch (\Exception $e) {
                    throw new \Exception('Failed to insert documents: ' . $e->getMessage());
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                // Get detailed transaction error information
                $error = $db->error();
                $errorMessage = 'Transaction failed';
                if (!empty($error['message'])) {
                    $errorMessage .= ': ' . $error['message'];
                }
                throw new \Exception($errorMessage);
            }

            $enrollment = $this->find($enrollmentId);
            return [
                'success' => true,
                'enrollment_id' => $enrollmentId,
                'enrollment_number' => $enrollment['enrollment_number'],
                'message' => 'Enrollment submitted successfully'
            ];

        } catch (\Exception $e) {
            $db->transRollback();
            
            // Get database error details
            $dbError = $db->error();
            
            // Enhanced error logging with more details
            $errorDetails = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'form_data_keys' => array_keys($formData),
                'lrn' => $this->formatLRN($formData),
                'db_error_code' => $dbError['code'] ?? null,
                'db_error_message' => $dbError['message'] ?? null,
                'transaction_status' => $db->transStatus(),
                'last_query' => $db->getLastQuery()
            ];
            
            // Force log creation
            if (!is_dir(WRITEPATH . 'logs')) {
                mkdir(WRITEPATH . 'logs', 0755, true);
            }
            
            $logFile = WRITEPATH . 'logs/enrollment_errors_' . date('Y-m-d') . '.log';
            file_put_contents($logFile, date('Y-m-d H:i:s') . ' - Enrollment Error: ' . json_encode($errorDetails, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND | LOCK_EX);
            
            log_message('error', 'Enrollment submission failed: ' . json_encode($errorDetails));
            
            // Return more specific error message
            $userMessage = 'Failed to submit enrollment';
            
            // Check for specific error types
            if (strpos($e->getMessage(), 'cannot be null') !== false || strpos($e->getMessage(), 'Column') !== false) {
                $userMessage .= ': Missing required information. Please ensure all required fields are filled.';
            } elseif (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $userMessage .= ': This LRN is already registered in the system.';
            } elseif (strpos($e->getMessage(), 'Data too long') !== false) {
                $userMessage .= ': Some information is too long. Please shorten your entries.';
            } elseif (strpos($e->getMessage(), 'Table') !== false && strpos($e->getMessage(), 'doesn\'t exist') !== false) {
                $userMessage .= ': Database table missing. Please contact administrator.';
            } elseif (strpos($e->getMessage(), 'Foreign key constraint') !== false) {
                $userMessage .= ': Database relationship error. Please contact administrator.';
            } elseif (!empty($dbError['message'])) {
                $userMessage .= ': Database error - ' . $dbError['message'];
            } else {
                $userMessage .= ': ' . $e->getMessage();
            }
            
            return [
                'success' => false,
                'message' => $userMessage,
                'error_code' => $dbError['code'] ?? 'UNKNOWN',
                'debug_info' => $errorDetails // Include debug info for development
            ];
        }
    }

    /**
     * Insert personal information
     */
    private function insertPersonalInfo($enrollmentId, $formData)
    {
        // Handle profile picture upload if provided
        $profilePicturePath = null;
        
        // Check for cropped image data first (from the image cropper)
        if (!empty($formData['cropped_image_data'])) {
            $profilePicturePath = $this->handleCroppedImageUpload($formData['cropped_image_data'], $enrollmentId);
        }
        // Fallback to regular file upload if no cropped data
        elseif (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $profilePicturePath = $this->handleProfilePictureUpload($_FILES['profile_picture'], $enrollmentId);
        }

        $personalInfoData = [
            'enrollment_id' => $enrollmentId,
            'lrn' => $this->formatLRN($formData),
            'birth_certificate_number' => $formData['birth_certificate_number'] ?? null,
            'last_name' => $formData['last_name'],
            'first_name' => $formData['first_name'],
            'middle_name' => $formData['middle_name'] ?? null,
            'extension_name' => $formData['extension_name'] ?? null,
            'date_of_birth' => $formData['date_of_birth'],
            'place_of_birth' => $formData['place_of_birth'] ?? null,
            'gender' => $formData['gender'],
            'age' => $formData['age'],
            'nationality' => $formData['nationality'] ?? ($formData['citizenship'] ?? null),
            'mother_tongue' => $formData['mother_tongue'] ?? null,
            'student_email' => $formData['student_email'] ?? null,
            'student_contact' => $formData['student_contact'] ?? null,
            'indigenous_people' => $formData['indigenous_people'] ?? 'No',
            'indigenous_community' => $formData['indigenous_community'] ?? null,
            'fourps_beneficiary' => $formData['fourps_beneficiary'] ?? 'No',
            'fourps_household_id' => $formData['fourps_household_id'] ?? null,
            'profile_picture' => $profilePicturePath
        ];

        $db = \Config\Database::connect();
        return $db->table('enrollment_personal_info')->insert($personalInfoData);
    }

    /**
     * Insert family information
     */
    private function insertFamilyInfo($enrollmentId, $formData)
    {
        $db = \Config\Database::connect();
        $familyTable = $db->table('enrollment_family_info');

        // Father information
        if (!empty($formData['father_first_name']) || !empty($formData['father_last_name'])) {
            $fatherData = [
                'enrollment_id' => $enrollmentId,
                'relationship_type' => 'father',
                'first_name' => $formData['father_first_name'] ?? null,
                'middle_name' => $formData['father_middle_name'] ?? null,
                'last_name' => $formData['father_last_name'] ?? null,
                'contact_number' => $formData['father_contact'] ?? null
            ];
            $familyTable->insert($fatherData);
        }

        // Mother information
        if (!empty($formData['mother_first_name']) || !empty($formData['mother_last_name'])) {
            $motherData = [
                'enrollment_id' => $enrollmentId,
                'relationship_type' => 'mother',
                'first_name' => $formData['mother_first_name'] ?? null,
                'middle_name' => $formData['mother_middle_name'] ?? null,
                'last_name' => $formData['mother_last_name'] ?? null,
                'contact_number' => $formData['mother_contact'] ?? null
            ];
            $familyTable->insert($motherData);
        }

        // Guardian information
        if (!empty($formData['guardian_first_name']) || !empty($formData['guardian_last_name'])) {
            $guardianData = [
                'enrollment_id' => $enrollmentId,
                'relationship_type' => 'guardian',
                'first_name' => $formData['guardian_first_name'] ?? null,
                'middle_name' => $formData['guardian_middle_name'] ?? null,
                'last_name' => $formData['guardian_last_name'] ?? null,
                'contact_number' => $formData['guardian_contact'] ?? null
            ];
            $familyTable->insert($guardianData);
        }

        return true;
    }

    /**
     * Insert address information
     */
    private function insertAddressInfo($enrollmentId, $formData)
    {
        $db = \Config\Database::connect();
        
        // Force use of the finalized address table
        $addressTableName = 'enrollment_address_final';
        $addressTable = $db->table($addressTableName);
        log_message('info', 'Using address table: ' . $addressTableName . ' for enrollment ID: ' . $enrollmentId);
        
        // Insert current address
        if (!empty($formData['current_barangay']) || !empty($formData['current_municipality'])) {
            $currentAddress = [
                'enrollment_id' => $enrollmentId,
                'address_type' => 'current',
                'house_no' => $formData['current_house_no'] ?? null,
                'street' => $formData['current_street'] ?? null,
                'barangay' => $formData['current_barangay'] ?? null,
                'municipality' => $formData['current_municipality'] ?? null,
                'province' => $formData['current_province'] ?? null,
                'country' => $formData['current_country'] ?? 'Philippines',
                'zip_code' => $formData['current_zip_code'] ?? null,
                'is_same_as_current' => 0
            ];
            
            $addressTable->insert($currentAddress);
        }
        
        // Insert permanent address
        if (isset($formData['same_as_current']) && $formData['same_as_current'] === 'on') {
            // Use current address data for permanent address
            $permanentAddress = [
                'enrollment_id' => $enrollmentId,
                'address_type' => 'permanent',
                'house_no' => $formData['current_house_no'] ?? null,
                'street' => $formData['current_street'] ?? null,
                'barangay' => $formData['current_barangay'] ?? null,
                'municipality' => $formData['current_municipality'] ?? null,
                'province' => $formData['current_province'] ?? null,
                'country' => $formData['current_country'] ?? 'Philippines',
                'zip_code' => $formData['current_zip_code'] ?? null,
                'is_same_as_current' => 1
            ];
        } else {
            // Use separate permanent address data
            $permanentAddress = [
                'enrollment_id' => $enrollmentId,
                'address_type' => 'permanent',
                'house_no' => $formData['permanent_house_street'] ?? null,
                'street' => $formData['permanent_street_name'] ?? null,
                'barangay' => $formData['permanent_barangay'] ?? null,
                'municipality' => $formData['permanent_municipality'] ?? null,
                'province' => $formData['permanent_province'] ?? null,
                'country' => $formData['permanent_country'] ?? 'Philippines',
                'zip_code' => $formData['permanent_zip_code'] ?? null,
                'is_same_as_current' => 0
            ];
        }
        
        // Insert permanent address if we have at least barangay or municipality
        if (!empty($permanentAddress['barangay']) || !empty($permanentAddress['municipality'])) {
            $addressTable->insert($permanentAddress);
        }
        
        return true;
    }
    
    /**
     * Insert parent/guardian address information
     */
    private function insertParentAddressInfo($enrollmentId, $formData)
    {
        $db = \Config\Database::connect();
        
        // Check if enrollment_parent_address table exists
        if ($db->query("SHOW TABLES LIKE 'enrollment_parent_address'")->getNumRows() === 0) {
            log_message('warning', 'Parent address information skipped - enrollment_parent_address table not found. Enrollment ID: ' . $enrollmentId);
            return true; // Continue without failing
        }
        
        $parentAddressTable = $db->table('enrollment_parent_address');
        log_message('info', 'Using enrollment_parent_address table for enrollment ID: ' . $enrollmentId);
        
        // Define parent types to process
        $parentTypes = ['father', 'mother', 'guardian'];
        
        foreach ($parentTypes as $parentType) {
            // Check if this parent type has address data
            $hasAddressData = false;
            $addressFields = ['house_no', 'street', 'barangay', 'municipality', 'province', 'country', 'zip_code'];
            
            foreach ($addressFields as $field) {
                if (!empty($formData[$parentType . '_' . $field])) {
                    $hasAddressData = true;
                    break;
                }
            }
            
            // Skip if no address data for this parent type
            if (!$hasAddressData && empty($formData[$parentType . '_same_address'])) {
                continue;
            }
            
            // Determine if using student's address
            $isSameAsStudent = isset($formData[$parentType . '_same_address']) && 
                              $formData[$parentType . '_same_address'] === 'on';
            
            if ($isSameAsStudent) {
                // Use student's current address
                $parentAddress = [
                    'enrollment_id' => $enrollmentId,
                    'parent_type' => $parentType,
                    'house_number' => $formData['current_house_no'] ?? null,
                    'street' => $formData['current_street'] ?? null,
                    'barangay' => $formData['current_barangay'] ?? null,
                    'municipality' => $formData['current_municipality'] ?? null,
                    'province' => $formData['current_province'] ?? null,
                    'zip_code' => $formData['current_zip_code'] ?? null,
                    'is_same_as_student' => 1
                ];
            } else {
                // Use parent's specific address
                $parentAddress = [
                    'enrollment_id' => $enrollmentId,
                    'parent_type' => $parentType,
                    'house_number' => $formData[$parentType . '_house_no'] ?? null,
                    'street' => $formData[$parentType . '_street'] ?? null,
                    'barangay' => $formData[$parentType . '_barangay'] ?? null,
                    'municipality' => $formData[$parentType . '_municipality'] ?? null,
                    'province' => $formData[$parentType . '_province'] ?? null,
                    'zip_code' => $formData[$parentType . '_zip_code'] ?? null,
                    'is_same_as_student' => 0
                ];
            }
            
            // Insert parent address if we have at least barangay or municipality
            if (!empty($parentAddress['barangay']) || !empty($parentAddress['municipality'])) {
                $parentAddressTable->insert($parentAddress);
                log_message('info', 'Inserted ' . $parentType . ' address for enrollment ID: ' . $enrollmentId);
            }
        }
        
        return true;
    }
    
    /**
     * Insert academic history
     */
    private function insertAcademicHistory($enrollmentId, $formData)
    {
        // Helper function to convert empty strings to null
        $nullIfEmpty = function($value) {
            return (isset($value) && $value !== '') ? $value : null;
        };
        
        $academicData = [
            'enrollment_id' => $enrollmentId,
            'previous_gwa' => $nullIfEmpty($formData['previous_gwa'] ?? null),
            'performance_level' => $nullIfEmpty($formData['performance_level'] ?? null),
            'last_grade_completed' => $nullIfEmpty($formData['last_grade_completed'] ?? null),
            'last_school_year' => $nullIfEmpty($formData['last_school_year'] ?? null),
            'last_school_attended' => $nullIfEmpty($formData['last_school_attended'] ?? null),
            'school_id' => $this->formatSchoolId($formData)
            // Note: semester, track, strand are handled in SHS details table
        ];

        $db = \Config\Database::connect();
        
        // Check for academic history table (try multiple possible names)
        $academicTableName = null;
        $possibleTables = ['enrollment_academic_history_new', 'enrollment_academic_history', 'enrollment_academic_info'];
        
        foreach ($possibleTables as $tableName) {
            if ($db->query("SHOW TABLES LIKE '{$tableName}'")->getNumRows() > 0) {
                $academicTableName = $tableName;
                break;
            }
        }
        
        if (!$academicTableName) {
            // Log that academic history was skipped due to missing table
            log_message('warning', 'Academic history information skipped - no academic history table found. Enrollment ID: ' . $enrollmentId);
            return true; // Continue without failing - academic history is not critical for enrollment completion
        }
        
        log_message('info', 'Using academic history table: ' . $academicTableName . ' for enrollment ID: ' . $enrollmentId);
        log_message('info', 'Academic history data: ' . json_encode($academicData));
        
        $result = $db->table($academicTableName)->insert($academicData);
        
        if (!$result) {
            $error = $db->error();
            log_message('error', 'Academic history insert failed. Error: ' . json_encode($error));
            log_message('error', 'Last query: ' . $db->getLastQuery());
        } else {
            log_message('info', 'Academic history insert successful');
        }
        
        return $result;
    }

    /**
     * Insert SHS details
     */
    private function insertSHSDetails($enrollmentId, $formData)
    {
        $shsData = [
            'enrollment_id' => $enrollmentId,
            'track' => $formData['track'] ?? null,
            'strand' => $formData['strand'] ?? null,
            'specialization' => $formData['specialization'] ?? null,
            'semester' => $formData['semester'] ?? null
            // Note: career_pathway, subject_preferences, prerequisites_met columns don't exist in current table
        ];

        $db = \Config\Database::connect();
        return $db->table('enrollment_shs_details')->insert($shsData);
    }

    /**
     * Insert disability information
     */
    private function insertDisabilityInfo($enrollmentId, $formData, $tableName = 'enrollment_disabilities')
    {
        $db = \Config\Database::connect();
        $disabilityTable = $db->table($tableName);

        if (isset($formData['disability_types']) && is_array($formData['disability_types'])) {
            foreach ($formData['disability_types'] as $disabilityType) {
                $disabilityData = [
                    'enrollment_id' => $enrollmentId,
                    'has_disability' => 'Yes',
                    'disability_type' => $disabilityType
                ];
                $disabilityTable->insert($disabilityData);
            }
        } else {
            // Single disability entry
            $disabilityData = [
                'enrollment_id' => $enrollmentId,
                'has_disability' => 'Yes',
                'disability_type' => $formData['disability_types'] ?? null
            ];
            $disabilityTable->insert($disabilityData);
        }

        return true;
    }

    /**
     * Insert documents
     */
    private function insertDocuments($enrollmentId, $documents)
    {
        $db = \Config\Database::connect();
        $documentsTable = $db->table('enrollment_docs');

        foreach ($documents as $document) {
            $documentData = [
                'enrollment_id' => $enrollmentId,
                'document_type' => $document['type'] ?? 'Unknown',
                'file_path' => $document['path'] ?? null,
                'uploaded_at' => date('Y-m-d H:i:s')
            ];
            $documentsTable->insert($documentData);
        }

        return true;
    }

    /**
     * Format LRN from form data
     */
    private function formatLRN($formData)
    {
        $lrn = '';
        for ($i = 0; $i < 12; $i++) {
            $lrn .= $formData["lrn_digit_{$i}"] ?? '0';
        }
        return $lrn;
    }

    /**
     * Format School ID from form data
     */
    private function formatSchoolId($formData)
    {
        $schoolId = '';
        $hasNonZeroDigit = false;
        
        for ($i = 0; $i < 6; $i++) {
            $digit = $formData["school_id_digit_{$i}"] ?? '';
            // If digit is empty, treat as '0'
            if ($digit === '') {
                $digit = '0';
            }
            $schoolId .= $digit;
            
            // Check if we have any non-zero digit
            if ($digit !== '0') {
                $hasNonZeroDigit = true;
            }
        }
        
        // Return null if all digits are zeros or empty (meaning no school ID provided)
        return $hasNonZeroDigit ? $schoolId : null;
    }

    /**
     * Validate required fields
     */
    private function validateRequiredFields($formData)
    {
        $errors = [];
        
        // Required personal information fields
        $requiredFields = [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'grade_level' => 'Grade Level',
            'gender' => 'Gender',
            'date_of_birth' => 'Birth Date',
            'age' => 'Age'
        ];
        
        foreach ($requiredFields as $field => $label) {
            if (empty($formData[$field])) {
                $errors[] = $label . ' is required';
            }
        }
        
        // Validate LRN completeness
        $lrnComplete = true;
        for ($i = 0; $i < 12; $i++) {
            if (!isset($formData["lrn_digit_{$i}"]) || $formData["lrn_digit_{$i}"] === '' || $formData["lrn_digit_{$i}"] === null) {
                $lrnComplete = false;
                break;
            }
        }
        
        if (!$lrnComplete) {
            $errors[] = 'Complete 12-digit LRN is required';
        }
        
        // Validate date format
        if (!empty($formData['date_of_birth'])) {
            $date = \DateTime::createFromFormat('Y-m-d', $formData['date_of_birth']);
            if (!$date || $date->format('Y-m-d') !== $formData['date_of_birth']) {
                $errors[] = 'Birth date must be in YYYY-MM-DD format';
            }
        }
        
        // Validate age is numeric
        if (!empty($formData['age']) && !is_numeric($formData['age'])) {
            $errors[] = 'Age must be a number';
        }
        
        // Validate gender enum
        if (!empty($formData['gender']) && !in_array($formData['gender'], ['Male', 'Female'])) {
            $errors[] = 'Gender must be Male or Female';
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    /**
     * Check for duplicate LRN
     */
    public function checkDuplicateLRN($formData)
    {
        $lrn = $this->formatLRN($formData);
        
        // Check in enrollment_personal_info table
        $db = \Config\Database::connect();
        $enrollmentExists = $db->table('enrollment_personal_info')
                              ->join('enrollments', 'enrollments.id = enrollment_personal_info.enrollment_id')
                              ->where('enrollment_personal_info.lrn', $lrn)
                              ->where('enrollments.enrollment_status !=', 'declined')
                              ->get()
                              ->getRow();
        
        // Check in student table
        $studentExists = $db->table('students')
                           ->where('lrn', $lrn)
                           ->get()
                           ->getRow();
        
        return $enrollmentExists || $studentExists;
    }

    /**
     * Get enrollment with all related data
     */
    public function getEnrollmentWithDetails($enrollmentId)
    {
        $db = \Config\Database::connect();
        
        // Get main enrollment data
        $enrollment = $this->find($enrollmentId);
        if (!$enrollment) {
            return null;
        }

        // Get personal info
        $enrollment['personal_info'] = $db->table('enrollment_personal_info')
                                         ->where('enrollment_id', $enrollmentId)
                                         ->get()
                                         ->getRowArray();

        // Get family info
        $enrollment['family_info'] = $db->table('enrollment_family_info')
                                       ->where('enrollment_id', $enrollmentId)
                                       ->get()
                                       ->getResultArray();

        // Get academic history (try multiple possible table names)
        $academicTableName = null;
        $possibleTables = ['enrollment_academic_history_new', 'enrollment_academic_history', 'enrollment_academic_info'];
        
        foreach ($possibleTables as $tableName) {
            if ($db->query("SHOW TABLES LIKE '{$tableName}'")->getNumRows() > 0) {
                $academicTableName = $tableName;
                break;
            }
        }
        
        if ($academicTableName) {
            $enrollment['academic_history'] = $db->table($academicTableName)
                                                ->where('enrollment_id', $enrollmentId)
                                                ->get()
                                                ->getRowArray();
        } else {
            $enrollment['academic_history'] = null;
        }

        // Get SHS details
        $enrollment['shs_details'] = $db->table('enrollment_shs_details')
                                       ->where('enrollment_id', $enrollmentId)
                                       ->get()
                                       ->getRowArray();

        // Get disabilities
        $enrollment['disabilities'] = $db->table('enrollment_disabilities_temp')
                                        ->where('enrollment_id', $enrollmentId)
                                        ->get()
                                        ->getResultArray();

        // Get address info from the finalized table only
        $addressTableName = 'enrollment_address_final';
        $enrollment['address_info'] = $db->table($addressTableName)
                                            ->where('enrollment_id', $enrollmentId)
                                            ->get()
                                            ->getResultArray();

        // Get documents
        $enrollment['documents'] = $db->table('enrollment_docs')
                                     ->where('enrollment_id', $enrollmentId)
                                     ->get()
                                     ->getResultArray();

        return $enrollment;
    }

    /**
     * Get enrollments with student details
     */
    public function getEnrollmentsWithStudentDetails($status = null)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('enrollments e')
                     ->select('e.id, e.enrollment_number, e.grade_level, e.enrollment_type, e.enrollment_status, e.created_at as enrollment_date,
                              epi.first_name, epi.middle_name, epi.last_name, epi.lrn,
                              epi.student_email, epi.student_contact, epi.profile_picture,
                              CONCAT(COALESCE(f_father.first_name, ""), " ", COALESCE(f_father.last_name, "")) as father_name,
                              CONCAT(COALESCE(f_mother.first_name, ""), " ", COALESCE(f_mother.last_name, "")) as mother_name,
                              COALESCE(f_father.contact_number, f_mother.contact_number) as parent_contact')
                     ->join('enrollment_personal_info epi', 'e.id = epi.enrollment_id', 'left')
                     ->join('enrollment_family_info f_father', 'e.id = f_father.enrollment_id AND f_father.relationship_type = "father"', 'left')
                     ->join('enrollment_family_info f_mother', 'e.id = f_mother.enrollment_id AND f_mother.relationship_type = "mother"', 'left')
                     ->orderBy('e.created_at', 'DESC');
        
        if ($status) {
            $builder->where('e.enrollment_status', $status);
        }
        
        $enrollments = $builder->get()->getResultArray();
        
        // Format the data for the view
        foreach ($enrollments as &$enrollment) {
            $enrollment['student_name'] = trim(($enrollment['first_name'] ?? '') . ' ' . 
                                             ($enrollment['middle_name'] ? $enrollment['middle_name'] . ' ' : '') . 
                                             ($enrollment['last_name'] ?? ''));
            $enrollment['email'] = $enrollment['student_email'] ?? '';
            $enrollment['parent_name'] = !empty($enrollment['father_name']) && trim($enrollment['father_name']) !== '' 
                                       ? trim($enrollment['father_name']) 
                                       : trim($enrollment['mother_name'] ?? '');
            $enrollment['contact'] = $enrollment['parent_contact'] ?? $enrollment['student_contact'] ?? '';
        }
        
        return $enrollments;
    }

    /**
     * Get enrollment statistics
     */
    public function getEnrollmentStats()
    {
        $stats = [];
        
        // Total enrollments
        $stats['total'] = $this->countAll();
        
        // Count by status
        $stats['by_status'] = $this->select('enrollment_status, COUNT(*) as count')
                                  ->groupBy('enrollment_status')
                                  ->findAll();
        
        // Count by grade level
        $stats['by_grade'] = $this->select('grade_level, COUNT(*) as count')
                                 ->groupBy('grade_level')
                                 ->findAll();
        
        // Count by enrollment type
        $stats['by_type'] = $this->select('enrollment_type, COUNT(*) as count')
                                ->groupBy('enrollment_type')
                                ->findAll();
        
        // Recent enrollments (last 30 days)
        $stats['recent_count'] = $this->where('created_at >=', date('Y-m-d', strtotime('-30 days')))
                                     ->countAllResults();
        
        return $stats;
    }

    /**
     * Approve enrollment (updated for new student management system)
     */
    public function approveEnrollment($enrollmentId, $approvedBy)
    {
        try {
            // Only update enrollment status - student creation is handled by AdminController
            $result = $this->update($enrollmentId, [
                'enrollment_status' => 'enrolled',
                'approved_by' => $approvedBy,
                'approved_at' => date('Y-m-d H:i:s')
            ]);

            return $result;

        } catch (\Exception $e) {
            log_message('error', 'Enrollment approval failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create student record from enrollment data
     */
    private function createStudentFromEnrollment($enrollment)
    {
        $studentModel = new StudentModel();
        $personalInfo = $enrollment['personal_info'];
        
        $studentData = [
            'enrollment_number' => $enrollment['enrollment_number'],
            'name' => trim(($personalInfo['first_name'] ?? '') . ' ' . ($personalInfo['middle_name'] ?? '') . ' ' . ($personalInfo['last_name'] ?? '')),
            'first_name' => $personalInfo['first_name'] ?? '',
            'middle_name' => $personalInfo['middle_name'] ?? '',
            'last_name' => $personalInfo['last_name'] ?? '',
            'extension_name' => $personalInfo['extension_name'] ?? null,
            'lrn' => $personalInfo['lrn'],
            'grade_level' => $enrollment['grade_level'],
            'gender' => $personalInfo['gender'] ?? '',
            'age' => $personalInfo['age'] ?? null,
            'date_of_birth' => $personalInfo['date_of_birth'] ?? null,
            'mother_tongue' => $personalInfo['mother_tongue'] ?? null,
            'citizenship' => 'Filipino',
            'enrollment_status' => 'new',
            'student_status' => 'active'
        ];

        $studentId = $studentModel->insert($studentData);
        
        // Update enrollment with student_id
        $this->update($enrollment['id'], ['student_id' => $studentId]);
        
        return $studentId;
    }
    
    /**
     * Insert emergency contacts from parent/guardian information
     */
    private function insertEmergencyContacts($enrollmentId, $formData)
    {
        $db = \Config\Database::connect();
        
        // Check which parent/guardian is selected as emergency contact (radio button)
        if (!isset($formData['emergency_contact']) || empty($formData['emergency_contact'])) {
            // No emergency contact selected, which is fine
            log_message('info', 'No emergency contact selected for enrollment ID: ' . $enrollmentId);
            return true;
        }
        
        $selectedContact = $formData['emergency_contact']; // 'father', 'mother', or 'guardian'
        $emergencyContact = null;
        
        // Build emergency contact data based on selection
        switch ($selectedContact) {
            case 'father':
                $fatherName = trim(($formData['father_first_name'] ?? '') . ' ' . ($formData['father_last_name'] ?? ''));
                if (!empty($fatherName) && !empty($formData['father_contact'])) {
                    $emergencyContact = [
                        'enrollment_id' => $enrollmentId,
                        'emergency_contact_name' => $fatherName,
                        'emergency_contact_phone' => $formData['father_contact'],
                        'emergency_contact_relationship' => 'Father',
                        'is_primary_contact' => 1, // Single contact is always primary
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                }
                break;
                
            case 'mother':
                $motherName = trim(($formData['mother_first_name'] ?? '') . ' ' . ($formData['mother_last_name'] ?? ''));
                if (!empty($motherName) && !empty($formData['mother_contact'])) {
                    $emergencyContact = [
                        'enrollment_id' => $enrollmentId,
                        'emergency_contact_name' => $motherName,
                        'emergency_contact_phone' => $formData['mother_contact'],
                        'emergency_contact_relationship' => 'Mother',
                        'is_primary_contact' => 1, // Single contact is always primary
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                }
                break;
                
            case 'guardian':
                $guardianName = trim(($formData['guardian_first_name'] ?? '') . ' ' . ($formData['guardian_last_name'] ?? ''));
                if (!empty($guardianName) && !empty($formData['guardian_contact'])) {
                    $emergencyContact = [
                        'enrollment_id' => $enrollmentId,
                        'emergency_contact_name' => $guardianName,
                        'emergency_contact_phone' => $formData['guardian_contact'],
                        'emergency_contact_relationship' => 'Guardian',
                        'is_primary_contact' => 1, // Single contact is always primary
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                }
                break;
        }
        
        // Insert emergency contact if valid data exists
        if ($emergencyContact) {
            $builder = $db->table('enrollment_emergency_contact');
            $result = $builder->insert($emergencyContact);
            
            if (!$result) {
                throw new \Exception('Failed to insert emergency contact');
            }
            
            log_message('info', 'Inserted emergency contact (' . $selectedContact . ') for enrollment ID: ' . $enrollmentId);
        } else {
            log_message('warning', 'Selected emergency contact (' . $selectedContact . ') has incomplete information for enrollment ID: ' . $enrollmentId);
        }
        
        return true;
    }
    
    /**
     * Decline enrollment
     */
    public function declineEnrollment($enrollmentId, $declinedBy, $reason)
    {
        try {
            // Update enrollment status to declined
            $result = $this->update($enrollmentId, [
                'enrollment_status' => 'declined',
                'approved_by' => $declinedBy,
                'declined_reason' => $reason,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return $result;

        } catch (\Exception $e) {
            log_message('error', 'Enrollment decline failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Handle cropped image data upload
     */
    private function handleCroppedImageUpload($croppedImageData, $enrollmentId)
    {
        try {
            // Validate base64 data
            if (empty($croppedImageData) || !preg_match('/^data:image\/(\w+);base64,/', $croppedImageData)) {
                throw new \Exception('Invalid cropped image data format.');
            }

            // Extract image data and type
            preg_match('/^data:image\/(\w+);base64,/', $croppedImageData, $matches);
            $imageType = $matches[1];
            $imageData = substr($croppedImageData, strpos($croppedImageData, ',') + 1);
            $imageData = base64_decode($imageData);

            if ($imageData === false) {
                throw new \Exception('Failed to decode base64 image data.');
            }

            // Validate image type
            $allowedTypes = ['jpeg', 'jpg', 'png', 'gif'];
            if (!in_array(strtolower($imageType), $allowedTypes)) {
                throw new \Exception('Invalid image type. Only JPG, PNG, and GIF files are allowed.');
            }

            // Create upload directory if it doesn't exist
            $uploadDir = FCPATH . 'uploads/profile_pictures/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Generate unique filename
            $fileName = 'profile_' . $enrollmentId . '_' . time() . '.' . $imageType;
            $filePath = $uploadDir . $fileName;

            // Save image data to file
            if (file_put_contents($filePath, $imageData)) {
                // Return relative path for database storage
                return 'uploads/profile_pictures/' . $fileName;
            } else {
                throw new \Exception('Failed to save cropped image file.');
            }

        } catch (\Exception $e) {
            log_message('error', 'Cropped image upload failed: ' . $e->getMessage());
            return null; // Return null on failure, don't break enrollment process
        }
    }

    /**
     * Handle profile picture upload
     */
    private function handleProfilePictureUpload($file, $enrollmentId)
    {
        try {
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $fileType = strtolower($file['type']);
            
            if (!in_array($fileType, $allowedTypes)) {
                throw new \Exception('Invalid file type. Only JPG, PNG, and GIF files are allowed.');
            }

            // Validate file size (2MB max)
            if ($file['size'] > 2 * 1024 * 1024) {
                throw new \Exception('File size must be less than 2MB.');
            }

            // Create upload directory if it doesn't exist
            $uploadDir = FCPATH . 'uploads/profile_pictures/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Generate unique filename
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = 'profile_' . $enrollmentId . '_' . time() . '.' . $fileExtension;
            $filePath = $uploadDir . $fileName;

            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                // Return relative path for database storage
                return 'uploads/profile_pictures/' . $fileName;
            } else {
                throw new \Exception('Failed to move uploaded file.');
            }

        } catch (\Exception $e) {
            log_message('error', 'Profile picture upload failed: ' . $e->getMessage());
            return null; // Return null on failure, don't break enrollment process
        }
    }
}