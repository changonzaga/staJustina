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

            // Validation rules and messages (consolidated)
            $validationRules = [
                'lrn' => 'required|exact_length[12]|is_unique[students.lrn]|numeric',
                'grade_level' => 'required',
                'section' => 'required',
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
                    'numeric' => 'LRN must contain only numeric values.',
                    'exact_length' => 'LRN must be exactly 12 digits'
                ]
            ];

            $validation->setRules($validationRules, $validationMessages);

            if (!$validation->withRequest($this->request)->run()) {
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

            // Prepare data
            $data = [
                'account_number' => $this->generateAccountNumber(),
                'lrn' => trim($this->request->getPost('lrn')),
                'enrollment_date' => date('Y-m-d'),
                'grade_level' => trim($this->request->getPost('grade_level')),
                'section' => trim($this->request->getPost('section')),
                'academic_year' => date('Y'),
                'student_status' => 'active',
                // Form fields
                'name' => trim($this->request->getPost('name')),
                'date_of_birth' => $this->request->getPost('date_of_birth') ?: null,
                'gender' => $this->request->getPost('gender'),
                'age' => (int)$this->request->getPost('age'),
                'citizenship' => trim($this->request->getPost('citizenship')) ?: 'Filipino',
                'religion' => trim($this->request->getPost('religion')) ?: null,
                'enrollment_status' => $this->request->getPost('enrollment_status') ?: 'new',
                'school_assigned' => trim($this->request->getPost('school_assigned')) ?: null,
                'school_id' => trim($this->request->getPost('school_id')) ?: null,
                'date_of_enrollment' => $this->request->getPost('date_of_enrollment') ?: date('Y-m-d'),
                'address' => trim($this->request->getPost('address')) ?: null,
                'residential_address' => trim($this->request->getPost('residential_address')) ?: null,
                'guardian' => trim($this->request->getPost('guardian')) ?: null,
                'contact' => trim($this->request->getPost('contact')) ?: null,
                'parent_guardian_name' => trim($this->request->getPost('parent_guardian_name')) ?: null,
                'parent_guardian_contact' => trim($this->request->getPost('parent_guardian_contact')) ?: null,
                'parent_guardian_email' => trim($this->request->getPost('parent_guardian_email')) ?: null,
                'emergency_contact_name' => trim($this->request->getPost('emergency_contact_name')) ?: null,
                'emergency_contact_number' => trim($this->request->getPost('emergency_contact_number')) ?: null,
                'special_education_needs' => trim($this->request->getPost('special_education_needs')) ?: null,
                'health_conditions' => trim($this->request->getPost('health_conditions')) ?: null,
                'previous_school_attended' => trim($this->request->getPost('previous_school_attended')) ?: null,
                'previous_school_address' => trim($this->request->getPost('previous_school_address')) ?: null,
                'birth_certificate_number' => trim($this->request->getPost('birth_certificate_number')) ?: null,
                'remarks' => trim($this->request->getPost('remarks')) ?: null,
                'teacher_id' => $this->request->getPost('teacher_id') && is_numeric($this->request->getPost('teacher_id')) ? $this->request->getPost('teacher_id') : null,
                'parent_id' => $this->request->getPost('parent_id') && is_numeric($this->request->getPost('parent_id')) ? $this->request->getPost('parent_id') : null,
                // Additional form fields
                'permanent_house_no' => trim($this->request->getPost('permanent_house_no')) ?: null,
                'permanent_barangay' => trim($this->request->getPost('permanent_barangay')) ?: null,
                'permanent_municipality' => trim($this->request->getPost('permanent_municipality')) ?: null,
                'permanent_province' => trim($this->request->getPost('permanent_province')) ?: null,
                'permanent_zip_code' => trim($this->request->getPost('permanent_zip_code')) ?: null,
                'father_last_name' => trim($this->request->getPost('father_last_name')) ?: null,
                'father_first_name' => trim($this->request->getPost('father_first_name')) ?: null,
                'father_middle_name' => trim($this->request->getPost('father_middle_name')) ?: null,
                'father_contact' => trim($this->request->getPost('father_contact')) ?: null,
                'father_occupation' => trim($this->request->getPost('father_occupation')) ?: null,
                'mother_last_name' => trim($this->request->getPost('mother_last_name')) ?: null,
                'mother_first_name' => trim($this->request->getPost('mother_first_name')) ?: null,
                'mother_middle_name' => trim($this->request->getPost('mother_middle_name')) ?: null,
                'mother_contact' => trim($this->request->getPost('mother_contact')) ?: null,
                'mother_occupation' => trim($this->request->getPost('mother_occupation')) ?: null,
                'guardian_last_name' => trim($this->request->getPost('guardian_last_name')) ?: null,
                'guardian_first_name' => trim($this->request->getPost('guardian_first_name')) ?: null,
                'guardian_middle_name' => trim($this->request->getPost('guardian_middle_name')) ?: null,
                'guardian_contact_number' => trim($this->request->getPost('guardian_contact_number')) ?: null,
                'has_disability' => $this->request->getPost('has_disability') ?: 'No',
                'disability_types' => $this->request->getPost('disability_types') ? implode(',', $this->request->getPost('disability_types')) : null,
            ];

            // Extract emergency contact data before updating student
            $emergencyContactName = $data['emergency_contact_name'] ?? null;
            $emergencyContactNumber = $data['emergency_contact_number'] ?? null;
            
            // Remove emergency contact fields from student data as they'll be handled via parent relationships
            unset($data['emergency_contact_name'], $data['emergency_contact_number']);

            // Handle cropped image upload
            $croppedImage = $this->request->getPost('cropped_image');
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
                    $data['profile_picture'] = $newFileName;
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
                        $data['profile_picture'] = $newFileName;
                    }
                }
            }
            // Log the data before insertion
            log_message('debug', 'Final data to insert: ' . json_encode($data));
            
            // Extract emergency contact data before inserting student
            $emergencyContactName = $data['emergency_contact_name'] ?? null;
            $emergencyContactNumber = $data['emergency_contact_number'] ?? null;
            
            // Remove emergency contact fields from student data as they'll be handled via parent relationships
            unset($data['emergency_contact_name'], $data['emergency_contact_number']);
            
            //Insert data into the database
            $insertId = $model->insert($data);

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

            // Handle emergency contact via ParentManager if provided
            if (!empty($emergencyContactName) && !empty($emergencyContactNumber)) {
                $parentManager = new \App\Libraries\ParentManager();
                
                $parentData = [
                    'first_name' => explode(' ', $emergencyContactName)[0] ?? '',
                    'last_name' => implode(' ', array_slice(explode(' ', $emergencyContactName), 1)) ?: 'Unknown',
                    'contact_number' => $emergencyContactNumber
                ];
                
                $result = $parentManager->addEmergencyContact(
                    $insertId,
                    $parentData,
                    'Emergency Contact',
                    true // Set as primary emergency contact
                );
                
                if (!$result['success']) {
                    log_message('warning', "Failed to add emergency contact for student ID: $insertId - " . $result['message']);
                }
            }

            log_message('info', 'Student inserted successfully. ID: ' . $insertId);
            
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
                'barangay' => trim($this->request->getPost('barangay')) ?: null,
                'municipality' => trim($this->request->getPost('municipality')) ?: null,
                'province' => trim($this->request->getPost('province')) ?: null,
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
                    'barangay' => trim($this->request->getPost('permanent_barangay')) ?: null,
                    'municipality' => trim($this->request->getPost('permanent_municipality')) ?: null,
                    'province' => trim($this->request->getPost('permanent_province')) ?: null,
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

            // Handle profile picture upload
            $croppedImage = $this->request->getPost('cropped_image');
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

            // Delete profile picture if exists
            if (!empty($student['profile_picture'])) {
                $uploadPath = FCPATH . 'Uploads/students';
                $filePath = $uploadPath . '/' . $student['profile_picture'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // Delete student record
            if (!$model->delete($id)) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to delete student.'
                    ]);
                }
                return redirect()->to(route_to('admin.students.index'))->with('error', 'Failed to delete student.');
            }

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Student successfully deleted!',
                    'redirect' => site_url('admin/student')
                ]);
            }
            return redirect()->to(route_to('admin.students.index'))->with('success', 'Student successfully deleted!');
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
        $model = new StudentModel();
        do {
            $accountNumber = 'STU' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while ($model->where('account_number', $accountNumber)->first());
        
        return $accountNumber;
    }
}
