<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table = 'students'; // Fixed to use correct table name
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'account_number',
        'lrn',
        'enrollment_id',
        'student_status',
        'enrollment_date',
        'grade_level',
        'section',
        'academic_year'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime'; // for consistency (optional)

    protected $validationRules = [
        // Temporarily disabled all validation rules for testing
        /*
        'name' => 'required|min_length[2]|max_length[100]',
        'lrn' => 'required|min_length[12]|max_length[20]|is_unique[students.lrn,id,{id}]',
        'date_of_birth' => 'permit_empty|valid_date',
        'grade_level' => 'required|max_length[20]',
        'section' => 'required|max_length[50]',
        'gender' => 'required|in_list[Male,Female,Other]',
        'age' => 'permit_empty|integer|greater_than[0]|less_than[100]',
        'citizenship' => 'permit_empty|max_length[50]',
        'religion' => 'permit_empty|max_length[50]',
        'enrollment_status' => 'required|in_list[new,transferee,continuing]',
        'school_assigned' => 'permit_empty|max_length[255]',
        'school_id' => 'permit_empty|max_length[50]',
        'date_of_enrollment' => 'permit_empty|valid_date',
        'address' => 'permit_empty',
        'residential_address' => 'permit_empty',
        'guardian' => 'permit_empty|max_length[100]',
        'contact' => 'permit_empty|max_length[20]',
        'parent_guardian_name' => 'permit_empty|max_length[200]',
        'parent_guardian_contact' => 'permit_empty|max_length[50]',
        'parent_guardian_email' => 'permit_empty|valid_email|max_length[100]',
        'emergency_contact_name' => 'permit_empty|max_length[200]',
        'emergency_contact_number' => 'permit_empty|max_length[50]',
        'birth_certificate_number' => 'permit_empty|max_length[100]|is_unique[students.birth_certificate_number,id,{id}]',
        'student_status' => 'permit_empty|in_list[active,inactive,graduated,transferred,dropped]',
        'teacher_id' => 'permit_empty|integer',
        'parent_id' => 'permit_empty|integer'
        */
    ];

    protected $validationMessages = [
        // Temporarily disabled all validation messages for testing
        /*
        'name' => [
            'required' => 'Student name is required.',
            'min_length' => 'Student name must be at least 2 characters long.',
            'max_length' => 'Student name cannot exceed 100 characters.'
        ],
        'lrn' => [
            'required' => 'Learner Reference Number (LRN) is required.',
            'min_length' => 'LRN must be at least 12 characters long.',
            'max_length' => 'LRN cannot exceed 20 characters.',
            'is_unique' => 'This LRN is already registered in the system.'
        ],
        'grade_level' => [
            'required' => 'Grade level is required.'
        ],
        'section' => [
            'required' => 'Section is required.'
        ],
        'gender' => [
            'required' => 'Gender is required.',
            'in_list' => 'Please select a valid gender option.'
        ],
        'enrollment_status' => [
            'required' => 'Enrollment status is required.',
            'in_list' => 'Please select a valid enrollment status.'
        ],
        'parent_guardian_email' => [
            'valid_email' => 'Please enter a valid email address for parent/guardian.'
        ],
        'birth_certificate_number' => [
            'is_unique' => 'This birth certificate number is already registered.'
        ]
        */
    ];



    /**
     * Get student by LRN.
     */
    public function getByLRN($lrn)
    {
        return $this->where('lrn', $lrn)->first();
    }

    /**
     * Get students by section.
     */
    public function getBySection($section)
    {
        return $this->where('section', $section)->findAll();
    }

    /**
     * Get students by grade level.
     */
    public function getByGradeLevel($gradeLevel)
    {
        return $this->where('grade_level', $gradeLevel)->findAll();
    }

    /**
     * Get students by enrollment status.
     */
    public function getByEnrollmentStatus($status)
    {
        return $this->where('enrollment_status', $status)->findAll();
    }

    /**
     * Get students by student status.
     */
    public function getByStudentStatus($status)
    {
        return $this->where('student_status', $status)->findAll();
    }

    /**
     * Get active students only.
     */
    public function getActiveStudents()
    {
        return $this->where('student_status', 'active')->findAll();
    }

    /**
     * Get students with special education needs.
     */
    public function getStudentsWithSpecialNeeds()
    {
        return $this->where('special_education_needs IS NOT NULL')
                   ->where('special_education_needs !=', '')
                   ->findAll();
    }

    /**
     * Get transferee students.
     */
    public function getTransfereeStudents()
    {
        return $this->where('enrollment_status', 'transferee')->findAll();
    }

    /**
     * Get students by school assigned.
     */
    public function getBySchoolAssigned($schoolName)
    {
        return $this->where('school_assigned', $schoolName)->findAll();
    }

    /**
     * Get student with complete profile information.
     */
    public function getStudentCompleteProfile($id)
    {
        $db = \Config\Database::connect();

        $row = $db->table('students s')
            ->select('s.*, 
                     spi.first_name, spi.middle_name, spi.last_name, spi.gender, spi.date_of_birth, spi.profile_picture as profile_picture,
                     CONCAT(spi.first_name, " ", COALESCE(spi.middle_name, ""), " ", spi.last_name) as name,
                     TIMESTAMPDIFF(YEAR, spi.date_of_birth, CURDATE()) as age,
                     CONCAT(COALESCE(sa.house_no, \'\'), " ", COALESCE(sa.street, \'\'), ", ", sa.barangay, ", ", sa.municipality, ", ", sa.province) as address,
                     CONCAT(COALESCE(p.first_name, \'\'), " ", COALESCE(p.last_name, \'\')) as guardian,
                     p.contact_number as contact,
                     "Not Assigned" as teacher_name,
                     CONCAT(p.first_name, " ", COALESCE(p.middle_name, ""), " ", p.last_name) as parent_name,
                     spi.student_email as parent_email,
                     p.contact_number as parent_contact')
            ->join('student_personal_info spi', 's.id = spi.student_id', 'left')
            ->join('student_parent_relationships spr', 's.id = spr.student_id AND spr.is_primary_contact = 1', 'left')
            ->join('parents p', 'spr.parent_id = p.id', 'left')
            ->join('student_address sa', 's.id = sa.student_id AND sa.address_type = "current"', 'left')
            // Removed class/teacher joins due to schema differences
            ->where('s.id', $id)
            ->get()
            ->getRowArray();

        return $row;
    }

    /**
     * Search students by multiple criteria.
     */
    public function searchStudents($searchTerm, $filters = [])
    {
        $builder = $this->builder();
        
        if (!empty($searchTerm)) {
            $builder->groupStart()
                   ->like('name', $searchTerm)
                   ->orLike('lrn', $searchTerm)
                   ->orLike('parent_guardian_name', $searchTerm)
                   ->groupEnd();
        }
        
        if (!empty($filters['grade_level'])) {
            $builder->where('grade_level', $filters['grade_level']);
        }
        
        if (!empty($filters['section'])) {
            $builder->where('section', $filters['section']);
        }
        
        if (!empty($filters['enrollment_status'])) {
            $builder->where('enrollment_status', $filters['enrollment_status']);
        }
        
        if (!empty($filters['student_status'])) {
            $builder->where('student_status', $filters['student_status']);
        }
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get enrollment statistics.
     */
    public function getEnrollmentStats()
    {
        $stats = [];
        
        // Count by enrollment status
        $stats['by_enrollment_status'] = $this->select('enrollment_status, COUNT(*) as count')
                                             ->groupBy('enrollment_status')
                                             ->findAll();
        
        // Count by grade level
        $stats['by_grade_level'] = $this->select('grade_level, COUNT(*) as count')
                                       ->groupBy('grade_level')
                                       ->findAll();
        
        // Count by student status
        $stats['by_student_status'] = $this->select('student_status, COUNT(*) as count')
                                          ->groupBy('student_status')
                                          ->findAll();
        
        return $stats;
    }

    /**
     * Get students with their related information from other tables
     * Updated to use normalized parent structure instead of deprecated student_family_info
     */
    public function getStudentsWithRelations()
    {
        $db = \Config\Database::connect();
        
        return $db->table('students s')
            ->select('s.*, 
                     spi.first_name, spi.middle_name, spi.last_name, spi.gender, spi.date_of_birth, spi.place_of_birth, spi.profile_picture as profile_picture,
                     p.first_name as guardian_first_name, p.last_name as guardian_last_name, p.contact_number as guardian_contact,
                     sa.barangay, sa.municipality, sa.province,
                     "Not Assigned" as teacher_name,
                     CONCAT(spi.first_name, " ", COALESCE(spi.middle_name, ""), " ", spi.last_name) as name,
                     TIMESTAMPDIFF(YEAR, spi.date_of_birth, CURDATE()) as age,
                     CONCAT(COALESCE(p.first_name, ""), " ", COALESCE(p.last_name, "")) as guardian,
                     p.contact_number as contact,
                     CONCAT(COALESCE(sa.house_no, ""), " ", COALESCE(sa.street, ""), ", ", sa.barangay, ", ", sa.municipality, ", ", sa.province) as address,
                     DATE_FORMAT(spi.date_of_birth, "%M %d, %Y") as formatted_birth_date,
                     DATE_FORMAT(COALESCE(s.enrollment_date, s.created_at), "%M %d, %Y") as formatted_enrollment_date,
                     spr.relationship_type as guardian_relationship')
            ->join('student_personal_info spi', 's.id = spi.student_id', 'left')
            ->join('student_parent_relationships spr', 's.id = spr.student_id AND spr.is_primary_contact = 1', 'left')
            ->join('parents p', 'spr.parent_id = p.id', 'left')
            ->join('student_address sa', 's.id = sa.student_id AND sa.address_type = "current"', 'left')
            // Removed class/teacher joins due to schema differences
            ->groupBy('s.id')
            ->orderBy('s.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }
}
