<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $table = 'attendance';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'student_id',
        'date',
        'status',
        'remarks'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;

    protected $validationRules = [
        'student_id' => 'required|integer',
        'date' => 'required|valid_date',
        'status' => 'required|in_list[Present,Absent,Late,Excused]',
    ];

    protected $validationMessages = [
        'student_id' => [
            'required' => 'Student is required',
            'integer' => 'Invalid student selected'
        ],
        'date' => [
            'required' => 'Date is required',
            'valid_date' => 'Please enter a valid date'
        ],
        'status' => [
            'required' => 'Attendance status is required',
            'in_list' => 'Please select a valid attendance status'
        ]
    ];

    /**
     * Get today's attendance count by status
     */
    public function getTodayAttendanceCount($status = null)
    {
        $builder = $this->where('date', date('Y-m-d'));
        
        if ($status) {
            $builder->where('status', $status);
        }
        
        return $builder->countAllResults();
    }

    /**
     * Get attendance summary for a student
     */
    public function getAttendanceSummary($studentId, $dateRange = 30)
    {
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT 
                status, 
                COUNT(*) as count,
                (COUNT(*) * 100.0 / (
                    SELECT COUNT(*) 
                    FROM attendance 
                    WHERE student_id = ? 
                    AND date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                )) as percentage
            FROM attendance 
            WHERE student_id = ? 
            AND date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
            GROUP BY status
        ", [$studentId, $dateRange, $studentId, $dateRange]);

        $result = $query->getResultArray();

        // Format the result
        $summary = [
            'Present' => ['count' => 0, 'percentage' => 0],
            'Absent' => ['count' => 0, 'percentage' => 0],
            'Late' => ['count' => 0, 'percentage' => 0],
            'Excused' => ['count' => 0, 'percentage' => 0]
        ];

        foreach ($result as $row) {
            $summary[$row['status']] = [
                'count' => $row['count'],
                'percentage' => round($row['percentage'], 2)
            ];
        }

        return $summary;
    }

    /**
     * Get recent attendance for a student
     */
    public function getRecentAttendance($studentId, $days = 30)
    {
        return $this->where('student_id', $studentId)
                   ->where('date >=', date('Y-m-d', strtotime("-{$days} days")))
                   ->orderBy('date', 'DESC')
                   ->findAll();
    }

    /**
     * Get attendance by date range
     */
    public function getAttendanceByDateRange($startDate, $endDate, $studentId = null)
    {
        $builder = $this->where('date >=', $startDate)
                       ->where('date <=', $endDate);
        
        if ($studentId) {
            $builder->where('student_id', $studentId);
        }
        
        return $builder->orderBy('date', 'DESC')
                      ->orderBy('student_id', 'ASC')
                      ->findAll();
    }

    /**
     * Mark attendance for multiple students
     */
    public function markBulkAttendance($attendanceData)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            foreach ($attendanceData as $attendance) {
                // Check if attendance already exists for this student and date
                $existing = $this->where('student_id', $attendance['student_id'])
                               ->where('date', $attendance['date'])
                               ->first();

                if ($existing) {
                    // Update existing record
                    $this->update($existing['id'], $attendance);
                } else {
                    // Insert new record
                    $this->insert($attendance);
                }
            }

            $db->transComplete();
            return $db->transStatus();
        } catch (\Exception $e) {
            $db->transRollback();
            return false;
        }
    }

    /**
     * Get attendance statistics for a class/group
     */
    public function getClassAttendanceStats($studentIds, $dateRange = 30)
    {
        if (empty($studentIds)) {
            return [];
        }

        $studentIdsList = implode(',', array_map('intval', $studentIds));
        
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT 
                student_id,
                status,
                COUNT(*) as count,
                (COUNT(*) * 100.0 / (
                    SELECT COUNT(*) 
                    FROM attendance 
                    WHERE student_id = a.student_id 
                    AND date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                )) as percentage
            FROM attendance a
            WHERE student_id IN ($studentIdsList)
            AND date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
            GROUP BY student_id, status
            ORDER BY student_id, status
        ", [$dateRange, $dateRange]);

        return $query->getResultArray();
    }

    /**
     * Get attendance trend over time
     */
    public function getAttendanceTrend($studentId = null, $days = 30)
    {
        $builder = $this->select('date, status, COUNT(*) as count')
                       ->where('date >=', date('Y-m-d', strtotime("-{$days} days")))
                       ->groupBy(['date', 'status'])
                       ->orderBy('date', 'ASC');

        if ($studentId) {
            $builder->where('student_id', $studentId);
        }

        return $builder->findAll();
    }

    /**
     * Check if attendance exists for student on specific date
     */
    public function attendanceExists($studentId, $date)
    {
        return $this->where('student_id', $studentId)
                   ->where('date', $date)
                   ->first() !== null;
    }

    /**
     * Get students with perfect attendance
     */
    public function getPerfectAttendanceStudents($dateRange = 30)
    {
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT student_id, COUNT(*) as total_days
            FROM attendance 
            WHERE date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
            AND status = 'Present'
            GROUP BY student_id
            HAVING total_days = (
                SELECT COUNT(DISTINCT date) 
                FROM attendance 
                WHERE date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
            )
        ", [$dateRange, $dateRange]);

        return $query->getResultArray();
    }

    /**
     * Get absent students for a specific date
     */
    public function getAbsentStudents($date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }

        return $this->where('date', $date)
                   ->where('status', 'Absent')
                   ->findAll();
    }

    /**
     * Calculate attendance percentage for a student
     */
    public function getAttendancePercentage($studentId, $dateRange = 30)
    {
        $totalDays = $this->where('student_id', $studentId)
                         ->where('date >=', date('Y-m-d', strtotime("-{$dateRange} days")))
                         ->countAllResults();

        if ($totalDays == 0) {
            return 0;
        }

        $presentDays = $this->where('student_id', $studentId)
                           ->where('date >=', date('Y-m-d', strtotime("-{$dateRange} days")))
                           ->where('status', 'Present')
                           ->countAllResults();

        return round(($presentDays / $totalDays) * 100, 2);
    }
}