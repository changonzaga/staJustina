<?php
namespace App\Controllers\Backend\Pages;

use App\Controllers\BaseController;

class Students extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // See previous `index` controller snippet
    }

    public function get_by_section($section)
    {
        // See previous `get_by_section` controller snippet
    }

    public function create()
    {
        $teachers = $this->db->table('teacher')->select('id, name')->get()->getResultArray();
        $parents = $this->db->table('parent')->select('id, name')->get()->getResultArray();
        return view('backend/admin/students/create', [
            'teachers' => $teachers,
            'parents' => $parents
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost([
            'lrn', 'name', 'date_of_birth', 'gender', 'age', 'grade_level', 'section',
            'citizenship', 'religion', 'enrollment_status', 'school_assigned', 'school_id',
            'date_of_enrollment', 'address', 'residential_address', 'guardian', 'contact',
            'parent_guardian_name', 'parent_guardian_contact', 'parent_guardian_email',
            'emergency_contact_name', 'emergency_contact_number', 'special_education_needs',
            'health_conditions', 'previous_school_attended', 'previous_school_address',
            'birth_certificate_number', 'student_status', 'remarks', 'teacher_id', 'parent_id'
        ]);
        $file = $this->request->getFile('profile_picture');

        if ($file && $file->isValid()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/Uploads/students', $newName);
            $data['profile_picture'] = $newName;
        }

        // Validate data (e.g., unique LRN)
        if ($this->db->table('students')->where('lrn', $data['lrn'])->countAllResults() > 0) {
            return redirect()->back()->withInput()->with('error', 'LRN already exists.');
        }

        $this->db->table('students')->insert($data);
        return redirect()->to('backend/admin/students')->with('success', 'Student added successfully.');
    }

    public function edit($id)
    {
        $student = $this->db->table('students')->where('id', $id)->get()->getRowArray();
        $teachers = $this->db->table('teacher')->select('id, name')->get()->getResultArray();
        $parents = $this->db->table('parent')->select('id, name')->get()->getResultArray();
        
        if (!$student) {
            return redirect()->to('backend/admin/students')->with('error', 'Student not found.');
        }

        return view('backend/admin/students/edit', [
            'student' => $student,
            'teachers' => $teachers,
            'parents' => $parents
        ]);
    }

    public function update($id)
    {
        $data = $this->request->getPost([
            'lrn', 'name', 'date_of_birth', 'gender', 'age', 'grade_level', 'section',
            'citizenship', 'religion', 'enrollment_status', 'school_assigned', 'school_id',
            'date_of_enrollment', 'address', 'residential_address', 'guardian', 'contact',
            'parent_guardian_name', 'parent_guardian_contact', 'parent_guardian_email',
            'emergency_contact_name', 'emergency_contact_number', 'special_education_needs',
            'health_conditions', 'previous_school_attended', 'previous_school_address',
            'birth_certificate_number', 'student_status', 'remarks', 'teacher_id', 'parent_id'
        ]);
        $file = $this->request->getFile('profile_picture');

        if ($file && $file->isValid()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/Uploads/students', $newName);
            $data['profile_picture'] = $newName;
        }

        // Validate LRN uniqueness (excluding current student)
        $existing = $this->db->table('students')->where('lrn', $data['lrn'])->where('id !=', $id)->countAllResults();
        if ($existing > 0) {
            return redirect()->back()->withInput()->with('error', 'LRN already exists.');
        }

        $this->db->table('students')->where('id', $id)->update($data);
        return redirect()->to('backend/admin/students')->with('success', 'Student updated successfully.');
    }

    public function profile($id)
    {
        // Use StudentModel to fetch normalized complete profile
        $model = new \App\Models\StudentModel();
        $student = $model->getStudentCompleteProfile($id);

        if (!$student) {
            return redirect()->to('backend/admin/students')->with('error', 'Student not found.');
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
    
    public function delete($id)
    {
        // Use transaction to ensure full cascade deletion and consistency
        $db = \Config\Database::connect();
        
        // Check if student exists and fetch core record
        $student = $db->table('students')->where('id', $id)->get()->getRowArray();
        if (!$student) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Student not found.'
            ]);
        }

        // Helper: table existence and users field detection
        $tables = $db->listTables();
        $hasTable = function(string $name) use ($tables) {
            return in_array($name, $tables);
        };

        // Begin transaction
        $db->transStart();

        try {
            // Remove student profile picture (from personal info if applicable)
            $profileFile = null;
            if (!empty($student['profile_picture'])) {
                $profileFile = $student['profile_picture'];
            } elseif ($hasTable('student_personal_info')) {
                $spi = $db->table('student_personal_info')->select('profile_picture')->where('student_id', $id)->get()->getRowArray();
                $profileFile = $spi['profile_picture'] ?? null;
            }
            if (!empty($profileFile)) {
                $profilePath = ROOTPATH . 'public/Uploads/students/' . $profileFile;
                if (is_file($profilePath)) {
                    @unlink($profilePath);
                }
            }

            // Attendance records
            if ($hasTable('attendance')) {
                $db->table('attendance')->where('student_id', $id)->delete();
            }

            // Emergency contacts
            if ($hasTable('student_emergency_contacts')) {
                $db->table('student_emergency_contacts')->where('student_id', $id)->delete();
            }

            // Special categories
            if ($hasTable('student_special_categories')) {
                $db->table('student_special_categories')->where('student_id', $id)->delete();
            }

            // Users cleanup: delete user records linked to this student via multiple strategies
            if ($hasTable('users')) {
                // Collect linkage values
                $accountNo = $student['account_number'] ?? null;
                $emails = [];
                if ($hasTable('student_personal_info')) {
                    $spiEmailRow = $db->table('student_personal_info')->select('student_email')->where('student_id', $id)->get()->getRowArray();
                    if (!empty($spiEmailRow['student_email'])) { $emails[] = $spiEmailRow['student_email']; }
                }
                if ($hasTable('student_auth')) {
                    $saRow = $db->table('student_auth')->select('email, account_number')->where('student_id', $id)->get()->getRowArray();
                    if (!empty($saRow['email'])) { $emails[] = $saRow['email']; }
                    if (empty($accountNo) && !empty($saRow['account_number'])) { $accountNo = $saRow['account_number']; }
                }
                $emails = array_values(array_unique(array_filter($emails)));

                // Detect users fields safely
                $userFields = [];
                try {
                    $fieldData = $db->getFieldData('users');
                    if (is_array($fieldData)) {
                        foreach ($fieldData as $f) { if (isset($f->name)) { $userFields[] = $f->name; } }
                    }
                } catch (\Throwable $e) {
                    log_message('debug', 'Could not introspect users table fields: ' . $e->getMessage());
                }

                $hasStudentIdField = in_array('student_id', $userFields);
                $hasAccountNoField = in_array('account_no', $userFields);
                $hasAccountNumberField = in_array('account_number', $userFields);
                $hasEmailField = in_array('email', $userFields);
                $hasRoleField = in_array('role', $userFields);

                // Strict deletion to avoid broad deletes: prefer exact account_number/account_no
                if (!empty($accountNo)) {
                    if ($hasAccountNoField) {
                        $db->table('users')->where('account_no', $accountNo)->delete();
                    } elseif ($hasAccountNumberField) {
                        $db->table('users')->where('account_number', $accountNo)->delete();
                    } else {
                        log_message('warning', 'users cleanup skipped: no account_number/account_no columns while deleting student ' . $id);
                    }
                } else {
                    // If no account number was found, try student_id only (strict match)
                    if ($hasStudentIdField) {
                        $db->table('users')->where('student_id', $id)->delete();
                    } else {
                        log_message('debug', 'No users deletion keys available for student ' . $id);
                    }
                }
            }

            // Student authentication (after users cleanup to allow using auth email/account_no for matching)
            if ($hasTable('student_auth')) {
                $db->table('student_auth')->where('student_id', $id)->delete();
            }

            // Parent relationships and addresses
            $parentIds = [];
            if ($hasTable('student_parent_relationships')) {
                $rels = $db->table('student_parent_relationships')->select('parent_id')->where('student_id', $id)->get()->getResultArray();
                foreach ($rels as $r) { if (!empty($r['parent_id'])) { $parentIds[] = (int)$r['parent_id']; } }
                $db->table('student_parent_relationships')->where('student_id', $id)->delete();
            }
            if ($hasTable('student_parent_address')) {
                $db->table('student_parent_address')->where('student_id', $id)->delete();
            }
            // Remove orphan parent rows (no remaining relationships)
            if (!empty($parentIds) && $hasTable('parents') && $hasTable('student_parent_relationships')) {
                foreach (array_unique($parentIds) as $pid) {
                    $remaining = $db->table('student_parent_relationships')->where('parent_id', $pid)->countAllResults();
                    if ($remaining === 0) {
                        $db->table('parents')->where('id', $pid)->delete();
                    }
                }
            }

            // SHS details stored per student
            if ($hasTable('student_shs_details')) {
                $db->table('student_shs_details')->where('student_id', $id)->delete();
            }

            // Academic history
            if ($hasTable('student_academic_history')) {
                $db->table('student_academic_history')->where('student_id', $id)->delete();
            }

            // Addresses (support both singular and plural table names)
            if ($hasTable('student_address')) {
                $db->table('student_address')->where('student_id', $id)->delete();
            }
            if ($hasTable('student_addresses')) {
                $db->table('student_addresses')->where('student_id', $id)->delete();
            }

            // Disabilities temp
            if ($hasTable('student_disabilities_temp')) {
                $db->table('student_disabilities_temp')->where('student_id', $id)->delete();
            }

            // Personal info
            if ($hasTable('student_personal_info')) {
                $db->table('student_personal_info')->where('student_id', $id)->delete();
            }

            // Finally, delete the student row
            $db->table('students')->where('id', $id)->delete();

            // Commit
            $db->transComplete();
            if ($db->transStatus() === false) {
                $dbError = $db->error();
                log_message('error', 'Student cascade deletion failed: [' . ($dbError['code'] ?? 'n/a') . '] ' . ($dbError['message'] ?? 'unknown error'));
                return $this->response->setJSON(['success' => false, 'message' => 'Student deletion failed. Please try again.']);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Student and all related records deleted successfully.',
                'redirect' => site_url('backend/admin/students')
            ]);
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Exception during student deletion: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unexpected error during deletion.'
            ]);
        }
    }
}