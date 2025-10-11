<?php

namespace App\Controllers\Backend\Pages;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use App\Models\ParentModel;

class StudentsController extends BaseController
{
    public function index()
    {
        $model = new StudentModel();
        $data['students'] = $model->findAll();
        return view('backend/admin/students/index', $data);
    }

    public function create()
    {
        $teacherModel = new TeacherModel();
        $parentModel = new ParentModel();

        $data = [
            'teachers' => $teacherModel->findAll(),
            'parents' => $parentModel->getStudentsWithParents(),
        ];
        return view('backend/admin/students/create', $data);
    }

    public function store()
    {
        try {
            $db = \Config\Database::connect();
            $validation = \Config\Services::validation();
            $model = new StudentModel();
            $isAjax = $this->request->isAJAX();

            // Debug: log full POST payload to trace missing fields from form
            log_message('debug', 'StudentsController::store payload: ' . print_r($this->request->getPost(), true));

            // Validation rules and messages (consolidated)
            $validationRules = [
                // Ensure LRN is trimmed and exactly 12 digits
                'lrn' => 'required|trim|regex_match[/^\d{12}$/]|is_unique[students.lrn]',
                'grade_level' => 'required',
                'section' => 'permit_empty',
                'gender' => 'required|in_list[Male,Female,Other]',
                'age' => 'required|numeric|greater_than[0]',
                'guardian' => 'permit_empty|string',
                'contact' => 'permit_empty|string',
                'address' => 'permit_empty|string',
                'profile_picture' => 'permit_empty|if_exist|uploaded[profile_picture]|max_size[profile_picture,2048]|is_image[profile_picture]|mime_in[profile_picture,image/jpg,image/jpeg,image/png]',
                'cropped_image' => 'permit_empty',
            ];

            $validationMessages = [
                'lrn' => [
                    'is_unique' => 'This LRN already exists in the system.',
                    'regex_match' => 'LRN must be exactly 12 digits'
                ]
            ];

            // Conditionally require Returning Learner / Transfer fields when visible
            $studentType = $this->request->getPost('student_type');
            $enrollmentType = $this->request->getPost('enrollment_type');
            $requireReturning = ($studentType === 'Returning (Balik-Aral)') || ($enrollmentType === 'Transfer Enrollment');

            if ($requireReturning) {
                $validationRules = array_merge($validationRules, [
                    'last_grade_completed' => 'required|integer|greater_than[0]',
                    'last_school_year' => 'required',
                    'last_school_attended' => 'required',
                ]);
            }

            // Conditionally require SHS details for Grade 11/12 when fields are visible
            $gradeLevel = trim($this->request->getPost('grade_level'));
            if (in_array($gradeLevel, ['11', '12', 'Grade 11', 'Grade 12'])) {
                $validationRules = array_merge($validationRules, [
                    'semester' => 'permit_empty|in_list[1st,2nd]',
                    'track' => 'permit_empty|string',
                    'strand' => 'permit_empty|string',
                ]);
            }

            $validation->setRules($validationRules, $validationMessages);

            // Normalize LRN in POST data: strip non-digits, or reconstruct from digit boxes
            $postData = $this->request->getPost();
            $lrnRaw = isset($postData['lrn']) ? trim($postData['lrn']) : '';
            $lrnSanitized = preg_replace('/\D/', '', $lrnRaw);
            if (empty($lrnSanitized) || strlen($lrnSanitized) !== 12) {
                $composed = '';
                for ($i = 0; $i < 12; $i++) {
                    $key = 'lrn_digit_' . $i;
                    if (isset($postData[$key])) {
                        $composed .= preg_replace('/\D/', '', $postData[$key]);
                    }
                }
                if (strlen($composed) === 12) {
                    $lrnSanitized = $composed;
                }
            }
            $postData['lrn'] = $lrnSanitized ?? $lrnRaw;

            if (!$validation->run($postData)) {
                $errors = $validation->getErrors();
                log_message('error', 'Validation failed: ' . json_encode($errors));
                
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $errors
                    ]);
                }
                return redirect()->back()->withInput()->with('errors', $errors);
            }

            // Check table existence
            if (!in_array($model->table, $db->listTables())) {
                $errorMsg = "Table '{$model->table}' does not exist. Please contact admin.";
                log_message('error', $errorMsg);
                
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => $errorMsg
                    ]);
                }
                return redirect()->back()->withInput()->with('error', $errorMsg);
            }

            // Prepare data for each normalized table
            $studentsData = [
                'account_number' => $this->generateAccountNumber(),
                // Use normalized LRN from validated post data
                'lrn' => $postData['lrn'],
                'enrollment_date' => date('Y-m-d'),
                'grade_level' => trim($this->request->getPost('grade_level')),
                'section' => trim($this->request->getPost('section')),
                'academic_year' => date('Y'),
                'student_status' => 'active',
            ];

            $personalInfoData = [
                'last_name' => trim($this->request->getPost('last_name')) ?: null,
                'first_name' => trim($this->request->getPost('first_name')) ?: null,
                'middle_name' => trim($this->request->getPost('middle_name')) ?: null,
                'extension_name' => trim($this->request->getPost('extension_name')) ?: null,
                // Map PSA Birth Certificate No. from either psa_birth_cert_no or birth_certificate_number
                'birth_certificate_number' => (trim($this->request->getPost('psa_birth_cert_no')) ?: trim($this->request->getPost('birth_certificate_number')) ?: null),
                'date_of_birth' => $this->request->getPost('date_of_birth') ?: null,
                'place_of_birth' => trim($this->request->getPost('place_of_birth')) ?: null,
                'gender' => $this->request->getPost('gender'),
                'age' => (int)$this->request->getPost('age'),
                'mother_tongue' => trim($this->request->getPost('mother_tongue')) ?: null,
                // Accept either 'student_email' or generic 'email' from the form
                'student_email' => (trim($this->request->getPost('student_email')) ?: (trim($this->request->getPost('email')) ?: null)),
                // Accept student contact from multiple possible field names in create/edit forms
                'student_contact' => (trim($this->request->getPost('student_contact'))
                    ?: (trim($this->request->getPost('contact_number')) ?: (trim($this->request->getPost('contact')) ?: null))),
                'indigenous_people' => $this->request->getPost('indigenous_people') ?: 'No',
                'indigenous_community' => trim($this->request->getPost('indigenous_community')) ?: null,
                'fourps_beneficiary' => $this->request->getPost('fourps_beneficiary') ?: 'No',
                'fourps_household_id' => trim($this->request->getPost('fourps_household_id')) ?: null,
                // Also store LRN in personal info when present
                'lrn' => $postData['lrn'] ?? null,
            ];

            // Current address: support both plain and `current_` prefixed field names from the form
            $addressData = [
                'house_no' => (trim($this->request->getPost('house_no')) ?: trim($this->request->getPost('current_house_no')) ?: null),
                // Fallback to combined House No./Street input if separate street is not provided
                'street' => (trim($this->request->getPost('street'))
                    ?: (trim($this->request->getPost('current_street'))
                        ?: (trim($this->request->getPost('current_house_no')) ?: trim($this->request->getPost('house_no')) ?: null))),
                // Ensure non-null for required address columns (barangay, municipality, province)
                'barangay' => (trim($this->request->getPost('barangay')) ?: trim($this->request->getPost('current_barangay')) ?: ''),
                'municipality' => (trim($this->request->getPost('municipality')) ?: trim($this->request->getPost('current_municipality')) ?: ''),
                'province' => (trim($this->request->getPost('province')) ?: trim($this->request->getPost('current_province')) ?: ''),
                'country' => (trim($this->request->getPost('country')) ?: trim($this->request->getPost('current_country')) ?: 'Philippines'),
                'zip_code' => (trim($this->request->getPost('zip_code')) ?: trim($this->request->getPost('current_zip_code')) ?: null),
                'address_type' => 'current',
            ];

            $sameAsCurrent = $this->request->getPost('same_as_current');
            if ($sameAsCurrent === 'on') {
                $permanentData = $addressData;
                $permanentData['address_type'] = 'permanent';
                $permanentData['is_same_as_current'] = 1;
            } else {
                $permanentData = [
                    'house_no' => trim($this->request->getPost('permanent_house_no')) ?: null,
                    // Support alternate permanent street field names
                    'street' => (trim($this->request->getPost('permanent_street'))
                        ?: (trim($this->request->getPost('permanent_street_name'))
                            ?: (trim($this->request->getPost('permanent_house_street'))
                                ?: (trim($this->request->getPost('permanent_house_no')) ?: null)))),
                    'barangay' => trim($this->request->getPost('permanent_barangay')) ?: '',
                    'municipality' => trim($this->request->getPost('permanent_municipality')) ?: '',
                    'province' => trim($this->request->getPost('permanent_province')) ?: '',
                    'country' => trim($this->request->getPost('permanent_country')) ?: 'Philippines',
                    'zip_code' => trim($this->request->getPost('permanent_zip_code')) ?: null,
                    'address_type' => 'permanent',
                    'is_same_as_current' => 0,
                ];
            }

            $academicHistoryData = [
                'previous_gwa' => $this->request->getPost('previous_gwa') ? (float)$this->request->getPost('previous_gwa') : null,
                // Accept performance level from visible select or hidden field
                'performance_level' => (trim($this->request->getPost('performance_level'))
                    ?: (trim($this->request->getPost('performance_level_hidden')) ?: null)),
                'last_grade_completed' => $this->request->getPost('last_grade_completed') ? (int)$this->request->getPost('last_grade_completed') : null,
                'last_school_year' => trim($this->request->getPost('last_school_year')) ?: null,
                'last_school_attended' => trim($this->request->getPost('last_school_attended')) ?: null,
                // school_id will be built from digit boxes below
                'school_id' => null,
            ];

            // Concatenate school_id_digit_* inputs into a single school_id value
            $schoolIdDigits = '';
            for ($i = 0; $i < 7; $i++) {
                $d = $this->request->getPost('school_id_digit_' . $i);
                if ($d !== null && $d !== '') {
                    $schoolIdDigits .= trim($d);
                }
            }
            if ($schoolIdDigits !== '') {
                $academicHistoryData['school_id'] = $schoolIdDigits;
            }

            // Extract emergency contact data
            $emergencyContactName = trim($this->request->getPost('emergency_contact_name')) ?: null;
            $emergencyContactNumber = trim($this->request->getPost('emergency_contact_number')) ?: null;

            // Handle cropped image upload (support both 'cropped_image' and 'cropped_image_data')
            $croppedImage = $this->request->getPost('cropped_image') ?: $this->request->getPost('cropped_image_data');
            if (!empty($croppedImage)) {
                // The cropped image is a base64 encoded string
                $uploadPath = FCPATH . 'uploads/students';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Remove the data URL prefix and decode
                $base64Image = preg_replace('#^data:image/\w+;base64,#i', '', $croppedImage);
                $imageData = base64_decode($base64Image);
                
                // Generate a unique filename
                $newFileName = uniqid() . '.png';
                $filePath = $uploadPath . '/' . $newFileName;
                
                // Save the image
                if (file_put_contents($filePath, $imageData)) {
                    $personalInfoData['profile_picture'] = $newFileName;
                }
            } else {
                // Fallback to regular file upload if no cropped image
                $profilePicture = $this->request->getFile('profile_picture');
                if ($profilePicture && $profilePicture->isValid() && !$profilePicture->hasMoved()) {
                    $uploadPath = FCPATH . 'uploads/students';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }

                    $newFileName = $profilePicture->getRandomName();
                    if ($profilePicture->move($uploadPath, $newFileName)) {
                        $personalInfoData['profile_picture'] = $newFileName;
                    }
                }
            }
            // Begin transaction and insert into normalized tables
            $db->transStart();

            // Insert primary student record
            $insertId = $model->insert($studentsData);

            if (!$insertId) {
                $errors = $model->errors();
                $errorMsg = 'Failed to create student. ' . json_encode($errors);
                log_message('error', 'Insert failed: ' . $errorMsg);
                
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => $errorMsg,
                        'errors' => $errors
                    ]);
                }
                return redirect()->back()->withInput()->with('error', $errorMsg);
            }

            // Helper to check DB errors and rollback early with detailed message
            $rollbackOnError = function(string $context) use ($db, $isAjax) {
                $dbError = $db->error();
                if (!empty($dbError['code'])) {
                    $msg = "Database error during {$context}: [{$dbError['code']}] {$dbError['message']}";
                    log_message('error', $msg);
                    $db->transRollback();
                    if ($isAjax) {
                        return \Config\Services::response()->setJSON(['success' => false, 'message' => $msg]);
                    }
                    return redirect()->back()->withInput()->with('error', $msg);
                }
                return null;
            };

            // Check related tables exist to avoid transaction failure on missing tables
            $tables = $db->listTables();
            $hasPersonalInfo = in_array('student_personal_info', $tables);
            $hasAddress = in_array('student_address', $tables);
            $hasAcademicHistory = in_array('student_academic_history', $tables);
            $hasStudentAuth = in_array('student_auth', $tables);
            $hasUsers = in_array('users', $tables);
            $hasStudentDisabilitiesTemp = in_array('student_disabilities_temp', $tables);
            $hasStudentParentAddress = in_array('student_parent_address', $tables);
            $hasShsDetails = in_array('student_shs_details', $tables);

            // Insert student personal info (if table exists)
            if ($hasPersonalInfo) {
                $personalInfoData['student_id'] = $insertId;
                // Debug: capture data payload and full POST before insert
                log_message('debug', 'Data for student_personal_info insert: ' . print_r($personalInfoData, true));
                log_message('debug', 'POST before student_personal_info insert: ' . print_r($this->request->getPost(), true));
                $spiModel = new \App\Models\StudentPersonalInfoModel();
                $spiModel->insert($personalInfoData);
                $res = $rollbackOnError('student_personal_info insert');
                if ($res !== null) { return $res; }
            } else {
                log_message('warning', "student_personal_info table not found; skipping personal info insert for student {$insertId}");
            }

            // Insert student disabilities temp records (if table exists)
            if ($hasStudentDisabilitiesTemp) {
                $hasDisability = $this->request->getPost('has_disability'); // 'Yes' or 'No'
                $disabilityTypes = $this->request->getPost('disability_types');
                if (!is_array($disabilityTypes)) {
                    $disabilityTypes = $disabilityTypes ? [$disabilityTypes] : [];
                }

                if ($hasDisability === 'Yes' && !empty($disabilityTypes)) {
                    foreach ($disabilityTypes as $dtype) {
                        $db->table('student_disabilities_temp')->insert([
                            'student_id' => $insertId,
                            'has_disability' => 'Yes',
                            'disability_type' => trim($dtype) ?: null,
                        ]);
                        $res = $rollbackOnError('student_disabilities_temp insert');
                        if ($res !== null) { return $res; }
                    }
                } else {
                    // Record explicit No or empty selection to indicate status captured
                    $db->table('student_disabilities_temp')->insert([
                        'student_id' => $insertId,
                        'has_disability' => ($hasDisability === 'Yes' ? 'Yes' : 'No'),
                        'disability_type' => null,
                    ]);
                    $res = $rollbackOnError('student_disabilities_temp insert');
                    if ($res !== null) { return $res; }
                }
            } else {
                log_message('warning', "student_disabilities_temp table not found; skipping disability insert for student {$insertId}");
            }

            // Insert current address (if table exists)
            if ($hasAddress) {
                $addressData['student_id'] = $insertId;
                // Debug: capture full POST payload before insert
                log_message('debug', 'POST before student_address current insert: ' . print_r($this->request->getPost(), true));
                $db->table('student_address')->insert($addressData);
                $res = $rollbackOnError('student_address current insert');
                if ($res !== null) { return $res; }

                // Insert permanent address when provided or same-as-current
                $hasPermanentData = ($sameAsCurrent === 'on') ||
                    (!empty($permanentData['house_no']) || !empty($permanentData['street']) || !empty($permanentData['barangay']) ||
                     !empty($permanentData['municipality']) || !empty($permanentData['province']) || !empty($permanentData['zip_code']));
                if ($hasPermanentData) {
                    $permanentData['student_id'] = $insertId;
                    // Debug: capture full POST payload before insert
                    log_message('debug', 'POST before student_address permanent insert: ' . print_r($this->request->getPost(), true));
                    $db->table('student_address')->insert($permanentData);
                    $res = $rollbackOnError('student_address permanent insert');
                    if ($res !== null) { return $res; }
                }
            } else {
                log_message('warning', "student_address table not found; skipping address inserts for student {$insertId}");
            }

            // Insert academic history (if table exists)
            if ($hasAcademicHistory) {
                $academicHistoryData['student_id'] = $insertId;
                // Debug: capture data payload and full POST before insert
                log_message('debug', 'Data for student_academic_history insert: ' . print_r($academicHistoryData, true));
                log_message('debug', 'POST before student_academic_history insert: ' . print_r($this->request->getPost(), true));
                $academicModel = new \App\Models\StudentAcademicHistoryModel();
                $academicModel->insert($academicHistoryData);
                $res = $rollbackOnError('student_academic_history insert');
                if ($res !== null) { return $res; }
            } else {
                log_message('warning', "student_academic_history table not found; skipping academic history insert for student {$insertId}");
            }

            // Insert SHS details (if table exists and applicable)
            if ($hasShsDetails) {
                // Collect SHS values from form; these may be optional based on grade level
                $shsSemester = $this->request->getPost('semester');
                $shsTrack = trim($this->request->getPost('track')) ?: null;
                $shsStrand = trim($this->request->getPost('strand')) ?: null;
                $shsSpecialization = trim($this->request->getPost('specialization')) ?: null; // optional if present

                // Only insert if at least one meaningful SHS field is provided or grade level is SHS
                if ($shsSemester || $shsTrack || $shsStrand || in_array($gradeLevel, ['11', '12', 'Grade 11', 'Grade 12'])) {
                    $shsData = [
                        'student_id' => $insertId,
                        'semester' => ($shsSemester === '1st' || $shsSemester === '2nd') ? $shsSemester : null,
                        'track' => $shsTrack,
                        'strand' => $shsStrand,
                        'specialization' => $shsSpecialization,
                    ];
                    // Debug: capture full POST payload before insert
                    log_message('debug', 'POST before student_shs_details insert: ' . print_r($this->request->getPost(), true));
                    $shsModel = new \App\Models\StudentShsDetailsModel();
                    // If an SHS record already exists for this student, update it; otherwise insert
                    $existing = $db->table('student_shs_details')->where('student_id', $insertId)->get()->getRowArray();
                    if ($existing) {
                        $db->table('student_shs_details')->where('id', $existing['id'])->update($shsData);
                        $res = $rollbackOnError('student_shs_details update');
                        if ($res !== null) { return $res; }
                    } else {
                        $shsModel->insert($shsData);
                        $res = $rollbackOnError('student_shs_details insert');
                        if ($res !== null) { return $res; }
                    }
                }
            } else {
                log_message('warning', "student_shs_details table not found; skipping SHS details insert for student {$insertId}");
            }

            // Create parent/guardian records and relationships if provided
            $parentModel = new \App\Models\ParentModel();
            $hasParents = in_array('parents', $tables);
            $hasParentRelationships = in_array('student_parent_relationships', $tables);
            if (!$hasParents || !$hasParentRelationships) {
                log_message('warning', 'Skipping parent/guardian creation; required tables missing: ' .
                    (!$hasParents ? 'parents ' : '') .
                    (!$hasParentRelationships ? 'student_parent_relationships' : ''));
            }

            // Track created parent IDs for address linkage
            $fatherId = null;
            $motherId = null;
            $guardianId = null;

            // Father
            $fatherLast = trim($this->request->getPost('father_last_name')) ?: null;
            $fatherFirst = trim($this->request->getPost('father_first_name')) ?: null;
            if ($fatherLast || $fatherFirst) {
                if ($hasParents && $hasParentRelationships) {
                    $fatherId = $parentModel->createOrGetParent([
                        'first_name' => $fatherFirst ?? '',
                        'middle_name' => trim($this->request->getPost('father_middle_name')) ?: null,
                        'last_name' => $fatherLast ?? 'Unknown',
                        'contact_number' => trim($this->request->getPost('father_contact')) ?: null,
                    ]);
                    if ($fatherId) {
                        $parentModel->createParentStudentRelationship($insertId, $fatherId, 'father', false, false);
                    }
                } else {
                    log_message('warning', "Parent tables missing; skipping father relationship for student {$insertId}");
                }
            }

            // Mother
            $motherLast = trim($this->request->getPost('mother_last_name')) ?: null;
            $motherFirst = trim($this->request->getPost('mother_first_name')) ?: null;
            if ($motherLast || $motherFirst) {
                if ($hasParents && $hasParentRelationships) {
                    $motherId = $parentModel->createOrGetParent([
                        'first_name' => $motherFirst ?? '',
                        'middle_name' => trim($this->request->getPost('mother_middle_name')) ?: null,
                        'last_name' => $motherLast ?? 'Unknown',
                        'contact_number' => trim($this->request->getPost('mother_contact')) ?: null,
                    ]);
                    if ($motherId) {
                        $parentModel->createParentStudentRelationship($insertId, $motherId, 'mother', false, false);
                    }
                } else {
                    log_message('warning', "Parent tables missing; skipping mother relationship for student {$insertId}");
                }
            }

            // Guardian
            $guardianLast = trim($this->request->getPost('guardian_last_name')) ?: null;
            $guardianFirst = trim($this->request->getPost('guardian_first_name')) ?: null;
            if ($guardianLast || $guardianFirst) {
                if ($hasParents && $hasParentRelationships) {
                    $guardianId = $parentModel->createOrGetParent([
                        'first_name' => $guardianFirst ?? '',
                        'middle_name' => trim($this->request->getPost('guardian_middle_name')) ?: null,
                        'last_name' => $guardianLast ?? 'Unknown',
                        // Align with enrollment and admin create form; support both field names
                        'contact_number' => trim($this->request->getPost('guardian_contact') ?? $this->request->getPost('guardian_contact_number')) ?: null,
                    ]);
                    if ($guardianId) {
                        // Do not set primary/emergency by default; will be set based on submitted radio selections below
                        $parentModel->createParentStudentRelationship($insertId, $guardianId, 'guardian', false, false);
                    }
                } else {
                    log_message('warning', "Parent tables missing; skipping guardian relationship for student {$insertId}");
                }
            }

            // Process Emergency Contact selection (used as Primary Contact too)
            $emergencyChoice = trim($this->request->getPost('emergency_contact') ?? '');

            if (!$emergencyChoice) {
                log_message('error', "Missing emergency contact selection for student {$insertId}");
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Please select an Emergency Contact. This will also serve as the Primary Contact.');
            }

            $choiceToId = [
                'father'   => $fatherId,
                'mother'   => $motherId,
                'guardian' => $guardianId,
            ];

            $selectedParentId = $choiceToId[$emergencyChoice] ?? null;
            if (!$selectedParentId) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Selected contact does not have a corresponding parent/guardian record.');
            }

            // Reset flags for all relationships of this student; set both flags on the selected contact
            if ($hasParentRelationships) {
                $db->table('student_parent_relationships')
                    ->where('student_id', $insertId)
                    ->update(['is_primary_contact' => 0, 'is_emergency_contact' => 0]);

                $db->table('student_parent_relationships')
                    ->where('student_id', $insertId)
                    ->where('parent_id', $selectedParentId)
                    ->update(['is_primary_contact' => 1, 'is_emergency_contact' => 1]);
            }

            // Insert parent/guardian address records (if table exists)
            if ($hasStudentParentAddress) {
                $spaModel = new \App\Models\StudentParentAddressModel();
                // Parent's Address section (applies to both father and mother)
                $parentSame = $this->request->getPost('parent_same_address') === 'on';
                $baseParentAddress = [
                    'is_same_as_student' => $parentSame ? 1 : 0,
                    'house_number' => $parentSame ? ($addressData['house_no'] ?? null) : (trim($this->request->getPost('parent_house_no')) ?: null),
                    // Fallback to combined House No./Street field when separate street not present
                    'street' => $parentSame ? ($addressData['street'] ?? null) : ((trim($this->request->getPost('parent_street')) ?: (trim($this->request->getPost('parent_house_no')) ?: null))),
                    'barangay' => $parentSame ? ($addressData['barangay'] ?? null) : (trim($this->request->getPost('parent_barangay')) ?: null),
                    'municipality' => $parentSame ? ($addressData['municipality'] ?? null) : (trim($this->request->getPost('parent_municipality')) ?: null),
                    'province' => $parentSame ? ($addressData['province'] ?? null) : (trim($this->request->getPost('parent_province')) ?: null),
                    'zip_code' => $parentSame ? ($addressData['zip_code'] ?? null) : (trim($this->request->getPost('parent_zip_code')) ?: null),
                ];
                $hasParentAddr = array_filter([
                    $baseParentAddress['house_number'],
                    $baseParentAddress['street'],
                    $baseParentAddress['barangay'],
                    $baseParentAddress['municipality'],
                    $baseParentAddress['province'],
                    $baseParentAddress['zip_code'],
                ]);
                if ($parentSame || $hasParentAddr) {
                    if ($fatherId) {
                        $fatherAddress = array_merge($baseParentAddress, [
                            'student_id' => $insertId,
                            'parent_type' => 'father',
                            'parent_id' => $fatherId,
                        ]);
                        log_message('debug', 'Data for student_parent_address father insert: ' . print_r($fatherAddress, true));
                        log_message('debug', 'POST before student_parent_address father insert: ' . print_r($this->request->getPost(), true));
                        $spaModel->insert($fatherAddress);
                        $res = $rollbackOnError('student_parent_address father insert');
                        if ($res !== null) { return $res; }
                    }
                    if ($motherId) {
                        $motherAddress = array_merge($baseParentAddress, [
                            'student_id' => $insertId,
                            'parent_type' => 'mother',
                            'parent_id' => $motherId,
                        ]);
                        log_message('debug', 'Data for student_parent_address mother insert (from parent address): ' . print_r($motherAddress, true));
                        log_message('debug', 'POST before student_parent_address mother insert (from parent address): ' . print_r($this->request->getPost(), true));
                        $spaModel->insert($motherAddress);
                        $res = $rollbackOnError('student_parent_address mother insert');
                        if ($res !== null) { return $res; }
                    }
                    if (!$fatherId && !$motherId) {
                        $genericParentAddress = array_merge($baseParentAddress, [
                            'student_id' => $insertId,
                            'parent_type' => 'parent',
                            'parent_id' => null,
                        ]);
                        log_message('debug', 'Data for student_parent_address generic parent insert: ' . print_r($genericParentAddress, true));
                        log_message('debug', 'POST before student_parent_address generic parent insert: ' . print_r($this->request->getPost(), true));
                        $spaModel->insert($genericParentAddress);
                        $res = $rollbackOnError('student_parent_address parent insert');
                        if ($res !== null) { return $res; }
                    }
                }
                
                // Guardian address section
                $guardianSame = $this->request->getPost('guardian_same_address') === 'on';
                $guardianAddress = [
                    'student_id' => $insertId,
                    'parent_type' => 'guardian',
                    'parent_id' => $guardianId,
                    'is_same_as_student' => $guardianSame ? 1 : 0,
                    'house_number' => $guardianSame ? ($addressData['house_no'] ?? null) : (trim($this->request->getPost('guardian_house_no')) ?: null),
                    'street' => $guardianSame ? ($addressData['street'] ?? null) : ((trim($this->request->getPost('guardian_street')) ?: (trim($this->request->getPost('guardian_house_no')) ?: null))),
                    'barangay' => $guardianSame ? ($addressData['barangay'] ?? null) : (trim($this->request->getPost('guardian_barangay')) ?: null),
                    'municipality' => $guardianSame ? ($addressData['municipality'] ?? null) : (trim($this->request->getPost('guardian_municipality')) ?: null),
                    'province' => $guardianSame ? ($addressData['province'] ?? null) : (trim($this->request->getPost('guardian_province')) ?: null),
                    'zip_code' => $guardianSame ? ($addressData['zip_code'] ?? null) : (trim($this->request->getPost('guardian_zip_code')) ?: null),
                ];
                if ($guardianSame || array_filter([$guardianAddress['house_number'], $guardianAddress['street'], $guardianAddress['barangay'], $guardianAddress['municipality'], $guardianAddress['province'], $guardianAddress['zip_code']])) {
                    // Debug: capture data payload and full POST before insert
                    log_message('debug', 'Data for student_parent_address guardian insert: ' . print_r($guardianAddress, true));
                    log_message('debug', 'POST before student_parent_address guardian insert: ' . print_r($this->request->getPost(), true));
                    $spaModel->insert($guardianAddress);
                    $res = $rollbackOnError('student_parent_address guardian insert');
                    if ($res !== null) { return $res; }
                }
            } else {
                log_message('warning', "student_parent_address table not found; skipping parent/guardian address inserts for student {$insertId}");
            }

            // Insert student authentication record (if table exists)
            if ($hasStudentAuth) {
                $studentEmail = $personalInfoData['student_email'] ?? null;
                $accountNumber = $studentsData['account_number'] ?? null;
                // Use provided student_password if present, otherwise generate a secure one
                $postedPassword = trim($this->request->getPost('student_password')) ?: null;
                $passwordWasGenerated = false;
                if ($postedPassword) {
                    $plainPassword = $postedPassword;
                } else {
                    try {
                        $plainPassword = bin2hex(random_bytes(4)); // 8-char hex, ~32 bits entropy
                    } catch (\Throwable $e) {
                        $plainPassword = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789'), 0, 8);
                    }
                    $passwordWasGenerated = true;
                }
                $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);

                $authData = [
                    'student_id' => $insertId,
                    'account_number' => $accountNumber,
                    'password_hash' => $hashedPassword,
                    'email' => $studentEmail,
                    'is_active' => 1,
                    'failed_login_attempts' => 0,
                ];
                // Debug: capture full POST payload before insert
                log_message('debug', 'POST before student_auth insert: ' . print_r($this->request->getPost(), true));
                $db->table('student_auth')->insert($authData);
                $res = $rollbackOnError('student_auth insert');
                if ($res !== null) { return $res; }
            } else {
                log_message('warning', "student_auth table not found; skipping student auth insert for student {$insertId}");
            }

            // Upsert into users table for unified login (if table exists)
            if ($hasUsers) {
                $userModel = new \App\Models\User();
                $fullName = trim(($personalInfoData['first_name'] ?? '') . ' ' . ($personalInfoData['middle_name'] ?? '') . ' ' . ($personalInfoData['last_name'] ?? ''));

                // Accept either 'student_email' or generic 'email' from the form
                $postedEmail = trim($this->request->getPost('student_email') ?? $this->request->getPost('email') ?? '');
                if (empty($personalInfoData['student_email']) && $postedEmail !== '') {
                    // Keep runtime data consistent in case other code paths reference it
                    $personalInfoData['student_email'] = $postedEmail;
                }

                // Ensure we always have a valid email to avoid NOT NULL constraint issues
                if ($postedEmail === '') {
                    $postedEmail = 'student-' . ($studentsData['account_number'] ?? uniqid('acct-')) . '@local.invalid';
                }

                // Detect if users table supports student_id linkage
                $userFields = [];
                try {
                    $fieldData = $db->getFieldData('users');
                    if (is_array($fieldData)) {
                        foreach ($fieldData as $f) {
                            if (isset($f->name)) { $userFields[] = $f->name; }
                        }
                    }
                } catch (\Throwable $e) {
                    log_message('debug', 'Could not introspect users table fields: ' . $e->getMessage());
                }

                $userData = [
                    'name' => $fullName ?: null,
                    'account_no' => $studentsData['account_number'] ?? null,
                    'email' => $postedEmail,
                    'password' => isset($hashedPassword) ? $hashedPassword : password_hash('12345678', PASSWORD_BCRYPT),
                    'picture' => $personalInfoData['profile_picture'] ?? null,
                    'auth_type' => 'student',
                    'role' => 'student',
                    'status' => 'active',
                ];
                if (in_array('student_id', $userFields)) {
                    $userData['student_id'] = $insertId;
                }

                // Prefer matching existing record by student_id if supported, else by account_no
                $existingUser = null;
                if (in_array('student_id', $userFields)) {
                    $existingUser = $userModel->where('student_id', $insertId)->first();
                } elseif (!empty($userData['account_no'])) {
                    $existingUser = $userModel->where('account_no', $userData['account_no'])->first();
                } else {
                    $existingUser = $userModel->where('email', $userData['email'])->first();
                }

                if ($existingUser) {
                    $userModel->update($existingUser['id'], $userData);
                    $res = $rollbackOnError('users update');
                    if ($res !== null) { return $res; }
                } else {
                    $userModel->insert($userData);
                    $res = $rollbackOnError('users insert');
                    if ($res !== null) { return $res; }
                }
            } else {
                log_message('warning', "users table not found; skipping user account sync for student {$insertId}");
            }

            // Complete transaction
            $db->transComplete();
            if ($db->transStatus() === false) {
                $dbError = $db->error();
                $errorMsg = 'Failed to create student with related records (transaction error).';
                if (!empty($dbError['code'])) {
                    $errorMsg .= " [{$dbError['code']}] {$dbError['message']}";
                }
                log_message('error', $errorMsg);
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => $errorMsg,
                    ]);
                }
                return redirect()->back()->withInput()->with('error', $errorMsg);
            }

            // Surface generated password to admins via tempdata and trigger email hook
            if (isset($passwordWasGenerated) && $passwordWasGenerated) {
                try {
                    $session = session();
                    $session->setTempdata('student_generated_password', $plainPassword, 300); // 5 minutes
                    $session->setTempdata('student_generated_account_no', $studentsData['account_number'] ?? null, 300);
                    log_message('info', "Generated temp password surfaced to admin for student {$insertId} (expires in 5 minutes)");

                    // Optional email notification hook (configure EmailService accordingly)
                    $recipientEmail = $personalInfoData['student_email'] ?? null;
                    if ($recipientEmail) {
                        if (class_exists('\\App\\Libraries\\EmailService')) {
                            $emailService = new \App\Libraries\EmailService();
                            if (method_exists($emailService, 'sendStudentWelcomeEmail')) {
                                $emailService->sendStudentWelcomeEmail(
                                    [
                                        'first_name' => $personalInfoData['first_name'] ?? '',
                                        'last_name' => $personalInfoData['last_name'] ?? '',
                                        'email' => $recipientEmail,
                                    ],
                                    [
                                        'account_no' => $studentsData['account_number'] ?? '',
                                        'password' => $plainPassword,
                                    ]
                                );
                            } else {
                                log_message('info', 'Email hook ready: implement EmailService::sendStudentWelcomeEmail to notify students.');
                            }
                        } else {
                            log_message('info', 'Email hook placeholder: EmailService not found. Configure email service to notify students with credentials.');
                        }
                    }
                } catch (\Throwable $e) {
                    log_message('error', 'Failed to surface/generated password or send email: ' . $e->getMessage());
                }
            }

            // Set primary and emergency contact based on enrollment form radio buttons
            if ($hasParentRelationships) {
                $primarySelection = trim($this->request->getPost('primary_contact') ?? '');
                $emergencySelection = trim($this->request->getPost('emergency_contact') ?? '');

                // Enforce only one primary and one emergency contact per student
                $db->table('student_parent_relationships')
                   ->where('student_id', $insertId)
                   ->update(['is_primary_contact' => 0, 'is_emergency_contact' => 0]);
                $res = $rollbackOnError('reset parent relationship flags');
                if ($res !== null) { return $res; }

                // Helper to set a flag on the selected relationship type if it exists
                $applyFlag = function(string $relationshipType, string $flagField) use ($db, $insertId) {
                    if ($relationshipType === '') { return; }
                    $db->table('student_parent_relationships')
                       ->where('student_id', $insertId)
                       ->where('relationship_type', $relationshipType)
                       ->set($flagField, 1)
                       ->update();
                };

                // Determine final primary: prefer explicit selection; else default to emergency selection
                $primaryFinal = $primarySelection !== '' ? $primarySelection : ($emergencySelection !== '' ? $emergencySelection : '');
                if ($primaryFinal !== '') {
                    $applyFlag($primaryFinal, 'is_primary_contact');
                }

                // Apply emergency contact selection when provided
                if ($emergencySelection !== '') {
                    $applyFlag($emergencySelection, 'is_emergency_contact');
                }
            }

            log_message('info', 'Student inserted successfully with related records. ID: ' . $insertId);
            
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Student successfully added!',
                    'redirect' => site_url('admin/student')
                ]);
            }
            return redirect()->to(route_to('admin.students.index'))->with('success', 'Student successfully added!');

        } catch (\Exception $e) {
            $errorMsg = 'An error occurred: ' . $e->getMessage();
            log_message('error', 'Exception during student creation: ' . $errorMsg);
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $errorMsg
                ]);
            }
            return redirect()->back()->withInput()->with('error', $errorMsg);
        }
    }

    public function edit($id = null)
    {
        if (!$id) {
            return redirect()->to(route_to('admin.students.index'))->with('error', 'Student ID is required.');
        }

        $db = \Config\Database::connect();
        
        // Get comprehensive student data from all related tables
        $query = $db->table('students s')
            ->select('s.*, 
                     spi.first_name, spi.middle_name, spi.last_name, spi.extension_name, 
                     spi.birth_certificate_number, spi.date_of_birth, spi.place_of_birth, 
                     spi.gender, spi.age, spi.mother_tongue, spi.student_email, spi.student_contact, 
                     spi.indigenous_people, spi.indigenous_community, spi.fourps_beneficiary, 
                     spi.fourps_household_id, spi.profile_picture,
                     sa.house_no, sa.street, sa.barangay, sa.municipality, sa.province, 
                     sa.country, sa.zip_code, sa.address_type,
                     sah.previous_gwa, sah.performance_level, sah.last_grade_completed, 
                     sah.last_school_year, sah.last_school_attended, sah.school_id')
            ->join('student_personal_info spi', 's.id = spi.student_id', 'left')
            ->join('student_address sa', 's.id = sa.student_id AND sa.address_type = "current"', 'left')
            ->join('student_academic_history sah', 's.id = sah.student_id', 'left')
            ->where('s.id', $id)
            ->get();

        if ($query->getNumRows() === 0) {
            return redirect()->to(route_to('admin.students.index'))->with('error', 'Student not found.');
        }

        $student = $query->getRowArray();
        
        // Create a full name for compatibility
        $student['name'] = trim(($student['first_name'] ?? '') . ' ' . 
                                ($student['middle_name'] ?? '') . ' ' . 
                                ($student['last_name'] ?? ''));

        // Handle legacy field mapping for backward compatibility
        if (!isset($student['contact']) && isset($student['student_contact'])) {
            $student['contact'] = $student['student_contact'];
        }

        $teacherModel = new TeacherModel();
        $parentModel = new ParentModel();

        // Get teachers with concatenated name
        $teachers = $teacherModel->select("id, CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', last_name) as name, first_name, middle_name, last_name")
                                ->findAll();

        // Get parents with concatenated name
        $parents = $parentModel->select("id, CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', last_name) as name, first_name, middle_name, last_name")
                               ->findAll();

        $data = [
            'student' => $student,
            'teachers' => $teachers,
            'parents' => $parents,
        ];

        return view('backend/admin/students/edit', $data);
    }

    public function profile($id = null)
    {
        if (!$id) {
            return redirect()->to(route_to('admin.students.index'))->with('error', 'Student ID is required.');
        }
        
        // Use StudentModel to fetch normalized complete profile
        $model = new \App\Models\StudentModel();
        $student = $model->getStudentCompleteProfile($id);
        
        if (!$student) {
            return redirect()->to(route_to('admin.students.index'))->with('error', 'Student not found.');
        }
        
        // Get recent attendance records (last 5)
        $db = \Config\Database::connect();
        $attendance = $db->table('attendance')
            ->where('student_id', $id)
            ->orderBy('date', 'DESC')
            ->limit(5)
            ->get()->getResultArray();
        
        $data = [
            'student' => $student,
            'attendance' => $attendance
        ];
        
        return view('backend/admin/students/student_profile', $data);
    }

    public function update($id = null)
    {
        try {
            if (!$id) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Student ID is required.']);
                }
                return redirect()->to(route_to('admin.students.index'))->with('error', 'Student ID is required.');
            }

            $model = new StudentModel();
            $student = $model->find($id);

            if (!$student) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Student not found.']);
                }
                return redirect()->to(route_to('admin.students.index'))->with('error', 'Student not found.');
            }

            // Validation rules - TEMPORARILY DISABLED FOR TESTING
            /*
            $validation = \Config\Services::validation();
            
            // Get current LRN to check if it's being changed
            $currentLRN = $student['lrn'];
            $newLRN = trim($this->request->getPost('lrn'));
            
            // Set validation rules
            $validationRules = [
                'first_name' => 'required|min_length[2]|max_length[100]',
                'last_name' => 'required|min_length[2]|max_length[100]',
                'grade_level' => 'required',
                'section' => 'required',
                'gender' => 'required|in_list[Male,Female,Other]',
                'age' => 'required|numeric|greater_than[0]',
                'profile_picture' => 'permit_empty|is_image[profile_picture]|max_size[profile_picture,2048]',
                'cropped_image' => 'permit_empty',
            ];
            
            // Only add LRN validation if the LRN is being changed
            if ($newLRN !== $currentLRN) {
                $validationRules['lrn'] = 'required|exact_length[12]|numeric|is_unique[students.lrn]';
            }
            
            $validation->setRules($validationRules, [
                'lrn' => [
                    'numeric' => 'LRN must contain only numeric values',
                    'exact_length' => 'LRN must be exactly 12 digits',
                    'is_unique' => 'This LRN is already registered in the system.'
                ],
                'first_name' => [
                    'required' => 'First name is required',
                    'min_length' => 'First name must be at least 2 characters'
                ],
                'last_name' => [
                    'required' => 'Last name is required',
                    'min_length' => 'Last name must be at least 2 characters'
                ],
                'grade_level' => [
                    'required' => 'Grade level is required.'
                ],
                'section' => [
                    'required' => 'Section is required.'
                ]
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Validation failed', 'errors' => $validation->getErrors()]);
                }
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }
            */

            // Prepare data for students table
            $studentsData = [
                'account_number' => trim($this->request->getPost('account_number')) ?: null,
                'lrn' => trim($this->request->getPost('lrn')),
                'grade_level' => trim($this->request->getPost('grade_level')),
                'section' => trim($this->request->getPost('section')),
                'academic_year' => trim($this->request->getPost('academic_year')) ?: null,
                'student_status' => $this->request->getPost('student_status') ?: 'active',
            ];

            // Only update enrollment_date if explicitly provided to avoid clearing it unintentionally
            $enrollmentDate = $this->request->getPost('enrollment_date');
            if (!empty($enrollmentDate)) {
                $studentsData['enrollment_date'] = $enrollmentDate;
            }

            // Prepare data for student_personal_info table
            $personalInfoData = [
                'last_name' => trim($this->request->getPost('last_name')) ?: null,
                'first_name' => trim($this->request->getPost('first_name')) ?: null,
                'middle_name' => trim($this->request->getPost('middle_name')) ?: null,
                'extension_name' => trim($this->request->getPost('extension_name')) ?: null,
                'birth_certificate_number' => trim($this->request->getPost('birth_certificate_number')) ?: null,
                'date_of_birth' => $this->request->getPost('date_of_birth') ?: null,
                'place_of_birth' => trim($this->request->getPost('place_of_birth')) ?: null,
                'gender' => $this->request->getPost('gender'),
                'age' => (int)$this->request->getPost('age'),
                'mother_tongue' => trim($this->request->getPost('mother_tongue')) ?: null,
                'student_email' => trim($this->request->getPost('student_email')) ?: null,
                'student_contact' => trim($this->request->getPost('student_contact')) ?: null,
                'indigenous_people' => $this->request->getPost('indigenous_people') ?: 'No',
                'indigenous_community' => trim($this->request->getPost('indigenous_community')) ?: null,
                'fourps_beneficiary' => $this->request->getPost('fourps_beneficiary') ?: 'No',
                'fourps_household_id' => trim($this->request->getPost('fourps_household_id')) ?: null,
            ];

            // Prepare data for student_address table
            $addressData = [
                'house_no' => trim($this->request->getPost('house_no')) ?: null,
                'street' => trim($this->request->getPost('street')) ?: null,
                'barangay' => trim($this->request->getPost('barangay')) ?: '',
                'municipality' => trim($this->request->getPost('municipality')) ?: '',
                'province' => trim($this->request->getPost('province')) ?: '',
                'country' => trim($this->request->getPost('country')) ?: 'Philippines',
                'zip_code' => trim($this->request->getPost('zip_code')) ?: null,
                'address_type' => 'current', // Set default address type
            ];
            
            // Determine permanent address data based on checkbox
            $sameAsCurrent = $this->request->getPost('same_as_current');
            if ($sameAsCurrent === 'on') {
                // Copy current address to permanent
                $permanentData = $addressData;
                $permanentData['address_type'] = 'permanent';
            } else {
                // Use manually entered permanent address fields
                $permanentData = [
                    'house_no' => trim($this->request->getPost('permanent_house_no')) ?: null,
                    'street' => trim($this->request->getPost('permanent_street')) ?: null,
                    'barangay' => trim($this->request->getPost('permanent_barangay')) ?: '',
                    'municipality' => trim($this->request->getPost('permanent_municipality')) ?: '',
                    'province' => trim($this->request->getPost('permanent_province')) ?: '',
                    'country' => trim($this->request->getPost('permanent_country')) ?: 'Philippines',
                    'zip_code' => trim($this->request->getPost('permanent_zip_code')) ?: null,
                    'address_type' => 'permanent',
                ];
            }

            // Prepare data for student_academic_history table
            $academicHistoryData = [
                'previous_gwa' => $this->request->getPost('previous_gwa') ? (float)$this->request->getPost('previous_gwa') : null,
                'performance_level' => trim($this->request->getPost('performance_level')) ?: null,
                'last_grade_completed' => $this->request->getPost('last_grade_completed') ? (int)$this->request->getPost('last_grade_completed') : null,
                'last_school_year' => trim($this->request->getPost('last_school_year')) ?: null,
                'last_school_attended' => trim($this->request->getPost('last_school_attended')) ?: null,
                'school_id' => trim($this->request->getPost('school_id')) ?: null,
            ];

            // Handle profile picture upload (support both 'cropped_image' and 'cropped_image_data')
            $croppedImage = $this->request->getPost('cropped_image') ?: $this->request->getPost('cropped_image_data');
            if (!empty($croppedImage)) {
                // The cropped image is a base64 encoded string
                $uploadPath = FCPATH . 'uploads/students';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Delete old profile picture if exists
                if (!empty($student['profile_picture']) && file_exists($uploadPath . '/' . $student['profile_picture'])) {
                    unlink($uploadPath . '/' . $student['profile_picture']);
                }
                
                // Remove the data URL prefix and decode
                $base64Image = preg_replace('#^data:image/\w+;base64,#i', '', $croppedImage);
                $imageData = base64_decode($base64Image);
                
                // Generate a unique filename
                $newFileName = uniqid() . '.png';
                $filePath = $uploadPath . '/' . $newFileName;
                
                // Save the image
                if (file_put_contents($filePath, $imageData)) {
                    $personalInfoData['profile_picture'] = $newFileName;
                }
            } else {
                // Fallback to regular file upload if no cropped image
                $profilePicture = $this->request->getFile('profile_picture');
                if ($profilePicture && $profilePicture->isValid() && !$profilePicture->hasMoved()) {
                    $uploadPath = FCPATH . 'uploads/students';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }

                    // Delete old profile picture if exists
                    if (!empty($student['profile_picture']) && file_exists($uploadPath . '/' . $student['profile_picture'])) {
                        unlink($uploadPath . '/' . $student['profile_picture']);
                    }

                    $newFileName = $profilePicture->getRandomName();
                    if ($profilePicture->move($uploadPath, $newFileName)) {
                        $personalInfoData['profile_picture'] = $newFileName;
                    }
                }
            }

            // Use database transactions to ensure data consistency
            $db = \Config\Database::connect();
            $db->transStart();

            try {
                // Update students table
                $result = $model->update($id, $studentsData);
                
                if (!$result) {
                    throw new \Exception('Failed to update students table: ' . json_encode($model->errors()));
                }

                // Update student_personal_info table
                $personalInfoResult = $db->table('student_personal_info')
                    ->where('student_id', $id)
                    ->update($personalInfoData);

                if (!$personalInfoResult) {
                    throw new \Exception('Failed to update student_personal_info table');
                }

                // Update or insert student_address table
                $existingAddress = $db->table('student_address')
                    ->where('student_id', $id)
                    ->where('address_type', 'current')
                    ->get()
                    ->getRowArray();

                if ($existingAddress) {
                    $addressResult = $db->table('student_address')
                        ->where('student_id', $id)
                        ->where('address_type', 'current')
                        ->update($addressData);
                } else {
                    $addressData['student_id'] = $id;
                    $addressResult = $db->table('student_address')
                        ->insert($addressData);
                }

                if (!$addressResult) {
                    throw new \Exception('Failed to update student_address table');
                }

                // Upsert permanent address row
                $existingPermanent = $db->table('student_address')
                    ->where('student_id', $id)
                    ->where('address_type', 'permanent')
                    ->get()
                    ->getRowArray();

                // Determine if we have any permanent data to save (when not same as current)
                $hasPermanentData = ($sameAsCurrent === 'on') ||
                    (!empty($permanentData['house_no']) || !empty($permanentData['street']) || !empty($permanentData['barangay']) ||
                     !empty($permanentData['municipality']) || !empty($permanentData['province']) || !empty($permanentData['zip_code']));

                if ($hasPermanentData) {
                    if ($existingPermanent) {
                        $permResult = $db->table('student_address')
                            ->where('student_id', $id)
                            ->where('address_type', 'permanent')
                            ->update($permanentData);
                    } else {
                        $permanentData['student_id'] = $id;
                        $permResult = $db->table('student_address')
                            ->insert($permanentData);
                    }

                    if (!$permResult) {
                        throw new \Exception('Failed to update permanent student_address');
                    }
                } else if ($existingPermanent) {
                    // No permanent data provided and checkbox not checked; remove existing permanent address
                    $db->table('student_address')
                        ->where('student_id', $id)
                        ->where('address_type', 'permanent')
                        ->delete();
                }

                // Update or insert student_academic_history table
                $existingHistory = $db->table('student_academic_history')
                    ->where('student_id', $id)
                    ->get()
                    ->getRowArray();

                if ($existingHistory) {
                    $historyResult = $db->table('student_academic_history')
                        ->where('student_id', $id)
                        ->update($academicHistoryData);
                } else {
                    $academicHistoryData['student_id'] = $id;
                    $historyResult = $db->table('student_academic_history')
                        ->insert($academicHistoryData);
                }

                if (!$historyResult) {
                    throw new \Exception('Failed to update student_academic_history table');
                }

                $db->transComplete();

                if ($db->transStatus() === false) {
                    throw new \Exception('Transaction failed');
                }

            } catch (\Exception $e) {
                $db->transRollback();
                throw $e;
            }

            // Handle emergency contact via ParentManager if provided
            if (!empty($emergencyContactName) && !empty($emergencyContactNumber)) {
                $parentManager = new \App\Libraries\ParentManager();
                
                // First, remove existing emergency contacts for this student
                $existingContacts = $parentManager->getEmergencyContacts($id);
                foreach ($existingContacts as $contact) {
                    $parentManager->removeEmergencyContact($id, $contact['parent_id']);
                }
                
                // Add the new emergency contact
                $parentData = [
                    'first_name' => explode(' ', $emergencyContactName)[0] ?? '',
                    'last_name' => implode(' ', array_slice(explode(' ', $emergencyContactName), 1)) ?: 'Unknown',
                    'contact_number' => $emergencyContactNumber
                ];
                
                $result = $parentManager->addEmergencyContact(
                    $id,
                    $parentData,
                    'Emergency Contact',
                    true // Set as primary emergency contact
                );
                
                if (!$result['success']) {
                    log_message('warning', "Failed to update emergency contact for student ID: $id - " . $result['message']);
                }
            }

            log_message('info', 'Student updated successfully. ID: ' . $id);
            if ($this->request->isAJAX()) {
                // Ensure proper JSON response with success flag
                $response = [
                    'success' => true,
                    'message' => 'Student successfully updated!',
                    'redirect' => site_url('admin/student')
                ];
                log_message('debug', 'Sending success response: ' . json_encode($response));
                return $this->response->setJSON($response);
            }
            return redirect()->to(site_url('admin/student'))->with('success', 'Student successfully updated!');

        } catch (\Exception $e) {
            log_message('error', 'Exception during student update: ' . $e->getMessage());
            log_message('error', 'Exception trace: ' . $e->getTraceAsString());
            if ($this->request->isAJAX()) {
                $errorResponse = ['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()];
                log_message('debug', 'Sending exception response for AJAX request: ' . json_encode($errorResponse));
                return $this->response->setJSON($errorResponse);
            }
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function delete($id = null)
    {
        // Transactional cascade deletion including users cleanup via account_number
        try {
            if (!$id) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Student ID is required.'
                    ]);
                }
                return redirect()->to(route_to('admin.students.index'))->with('error', 'Student ID is required.');
            }

            $model = new StudentModel();
            $student = $model->find($id);

            if (!$student) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Student not found.'
                    ]);
                }
                return redirect()->to(route_to('admin.students.index'))->with('error', 'Student not found.');
            }

            $db = \Config\Database::connect();
            $tables = $db->listTables();
            $hasTable = function(string $name) use ($tables) { return in_array($name, $tables); };

            $db->transStart();

            // Delete profile picture if exists
            if (!empty($student['profile_picture'])) {
                $uploadPath = FCPATH . 'Uploads/students';
                $filePath = $uploadPath . '/' . $student['profile_picture'];
                if (is_file($filePath)) { @unlink($filePath); }
            }

            // Gather linkage data
            $accountNumber = $student['account_number'] ?? null;
            $authEmail = null;
            if ($hasTable('student_auth')) {
                $saRow = $db->table('student_auth')->select('email, account_number')->where('student_id', $id)->get()->getRowArray();
                if ($saRow) {
                    $authEmail = $saRow['email'] ?? null;
                    if (empty($accountNumber) && !empty($saRow['account_number'])) { $accountNumber = $saRow['account_number']; }
                }
            }

            // Users cleanup: strict match on account_number to avoid broad deletes
            if ($hasTable('users') && !empty($accountNumber)) {
                $userFields = [];
                try {
                    $fieldData = $db->getFieldData('users');
                    if (is_array($fieldData)) { foreach ($fieldData as $f) { if (isset($f->name)) { $userFields[] = $f->name; } } }
                } catch (\Throwable $e) {
                    log_message('debug', 'Could not introspect users table fields: ' . $e->getMessage());
                }

                if (in_array('account_number', $userFields)) {
                    $db->table('users')->where('account_number', $accountNumber)->delete();
                } elseif (in_array('account_no', $userFields)) {
                    $db->table('users')->where('account_no', $accountNumber)->delete();
                } else {
                    log_message('warning', "users cleanup skipped: no account_number/account_no columns found while deleting student {$id}");
                }
            }

            // Delete student_auth
            if ($hasTable('student_auth')) {
                $db->table('student_auth')->where('student_id', $id)->delete();
            }

            // Delete student record
            $db->table('students')->where('id', $id)->delete();

            $db->transComplete();
            if ($db->transStatus() === false) {
                $dbError = $db->error();
                $message = 'Failed to delete student. [' . ($dbError['code'] ?? 'n/a') . '] ' . ($dbError['message'] ?? 'Unknown error');
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => $message]);
                }
                return redirect()->to(route_to('admin.students.index'))->with('error', $message);
            }

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Student and linked user deleted successfully!',
                    'redirect' => site_url('admin/student')
                ]);
            }
            return redirect()->to(route_to('admin.students.index'))->with('success', 'Student and linked user deleted successfully!');
        } catch (\Exception $e) {
            log_message('error', 'Exception during student deletion: ' . $e->getMessage());
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'An error occurred: ' . $e->getMessage()
                ]);
            }
            return redirect()->to(route_to('admin.students.index'))->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    private function generateAccountNumber()
    {
        // Generate a unique, sequential account number in the format YYYY####
        $db = \Config\Database::connect();
        $year = date('Y');

        do {
            // Compute max sequence for current year considering both legacy 'STUYYYY####' and new 'YYYY####' formats
            $maxNoPrefix = 0;
            $maxWithPrefix = 0;

            $queryNoPrefix = $db->query(
                "SELECT MAX(CAST(SUBSTRING(account_number, 5) AS UNSIGNED)) AS max_num FROM students WHERE account_number LIKE ?",
                [$year . '%']
            );
            if ($queryNoPrefix !== false && $queryNoPrefix->getNumRows() > 0) {
                $row = $queryNoPrefix->getRow();
                $maxNoPrefix = isset($row->max_num) ? (int)$row->max_num : 0;
            }

            // Legacy format support: 'STUYYYY####' -> numbers start at position 9
            $queryWithPrefix = $db->query(
                "SELECT MAX(CAST(SUBSTRING(account_number, 9) AS UNSIGNED)) AS max_num FROM students WHERE account_number LIKE ?",
                ['STU' . $year . '%']
            );
            if ($queryWithPrefix !== false && $queryWithPrefix->getNumRows() > 0) {
                $row2 = $queryWithPrefix->getRow();
                $maxWithPrefix = isset($row2->max_num) ? (int)$row2->max_num : 0;
            }

            $nextNum = max($maxNoPrefix, $maxWithPrefix) + 1;
            $accountNumber = $year . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

            // Double-check uniqueness
            $existsQuery = $db->query("SELECT id FROM students WHERE account_number = ?", [$accountNumber]);
            $exists = ($existsQuery !== false && $existsQuery->getNumRows() > 0);
        } while ($exists);

        return $accountNumber;
    }
}
