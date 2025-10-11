<?php

namespace App\Models;

use CodeIgniter\Model;

class AnnouncementModel extends Model
{
    protected $table = 'announcements';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'title',
        'content',
        'sender_id',
        'sender_type',
        'audience_type',
        'priority',
        'status',
        'publish_date',
        'expiry_date',
        'is_scheduled',
        'is_draft',
        'target_groups',
        'target_users',
        'attachments',
        'view_count',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'title' => 'required|max_length[255]',
        'content' => 'required',
        'sender_id' => 'required|integer',
        'sender_type' => 'required|in_list[admin,teacher]',
        'audience_type' => 'required|in_list[all,students,teachers,parents,specific_groups,specific_users]',
        'priority' => 'in_list[normal,high,urgent]',
        'status' => 'in_list[draft,pending,published,declined,expired]'
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Announcement title is required',
            'max_length' => 'Title must not exceed 255 characters'
        ],
        'content' => [
            'required' => 'Announcement content is required'
        ],
        'sender_id' => [
            'required' => 'Sender ID is required',
            'integer' => 'Sender ID must be a valid integer'
        ],
        'sender_type' => [
            'required' => 'Sender type is required',
            'in_list' => 'Sender type must be admin or teacher'
        ],
        'audience_type' => [
            'required' => 'Audience type is required',
            'in_list' => 'Audience type must be one of: all, students, teachers, parents, specific_groups, specific_users'
        ]
    ];

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
     * Get announcements for students (created by teachers or admins)
     * 
     * @param string $audience Audience filter (all, students, teachers, parents)
     * @param int $limit Number of announcements to retrieve
     * @return array
     */
    public function getAnnouncementsForStudents($audience = 'students', $limit = 10)
    {
        $builder = $this->db->table($this->table . ' a');
        $builder->select('a.*, 
                        COALESCE(CONCAT(t.first_name, " ", t.last_name), u.name) as teacher_name,
                        COALESCE(t.contact_number, u.email) as teacher_email');
        $builder->join('teachers t', 'a.sender_id = t.id AND a.sender_type = "teacher"', 'left');
        $builder->join('users u', 'a.sender_id = u.id AND a.sender_type = "admin"', 'left');
        $builder->where('a.audience_type', $audience);
        $builder->orWhere('a.audience_type', 'all');
        $builder->where('a.status', 'published');
        $builder->orderBy('a.created_at', 'DESC');
        $builder->limit($limit);
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get all announcements for students (including All and Students audience)
     * 
     * @param int $limit Number of announcements to retrieve
     * @return array
     */
    public function getAllAnnouncementsForStudents($limit = 20)
    {
        $builder = $this->db->table($this->table . ' a');
        $builder->select('a.*, 
                        COALESCE(CONCAT(t.first_name, " ", t.last_name), u.name) as teacher_name,
                        COALESCE(t.contact_number, u.email) as teacher_email');
        $builder->join('teachers t', 'a.sender_id = t.id AND a.sender_type = "teacher"', 'left');
        $builder->join('users u', 'a.sender_id = u.id AND a.sender_type = "admin"', 'left');
        
        // Filter for students (all or students audience)
        $builder->where('(a.audience_type = "all" OR a.audience_type = "students")');
        $builder->where('a.status', 'published');
        
        // Only show announcements that are published or scheduled for past
        $builder->where('(a.publish_date IS NULL OR a.publish_date <= NOW())');
        
        // Only show announcements that have not expired
        $builder->groupStart()
                    ->where('a.expiry_date IS NULL')
                    ->orWhere('a.expiry_date >= NOW()')
                ->groupEnd();
        
        $builder->orderBy('a.created_at', 'DESC');
        $builder->limit($limit);
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get announcement by ID with teacher information
     * 
     * @param int $id Announcement ID
     * @return array|null
     */
    public function getAnnouncementWithTeacher($id)
    {
        $builder = $this->db->table($this->table . ' a');
        $builder->select('a.*, 
                        COALESCE(CONCAT(t.first_name, " ", t.last_name), u.name) as teacher_name,
                        COALESCE(t.contact_number, u.email) as teacher_email');
        $builder->join('teachers t', 'a.sender_id = t.id AND a.sender_type = "teacher"', 'left');
        $builder->join('users u', 'a.sender_id = u.id AND a.sender_type = "admin"', 'left');
        $builder->where('a.id', $id);
        
        return $builder->get()->getRowArray();
    }

    /**
     * Get announcements for students with search and date filters
     * 
     * @param string $search Search term for title/content
     * @param string $dateFrom Start date (Y-m-d format)
     * @param string $dateTo End date (Y-m-d format)
     * @param int $limit Number of announcements to retrieve
     * @return array
     */
    public function getFilteredAnnouncementsForStudents($search = '', $dateFrom = '', $dateTo = '', $limit = 20)
    {
        $builder = $this->db->table($this->table . ' a');
        $builder->select('a.*, 
                        COALESCE(CONCAT(t.first_name, " ", t.last_name), u.name) as teacher_name,
                        COALESCE(t.contact_number, u.email) as teacher_email');
        $builder->join('teachers t', 'a.sender_id = t.id AND a.sender_type = "teacher"', 'left');
        $builder->join('users u', 'a.sender_id = u.id AND a.sender_type = "admin"', 'left');
        
        // Filter for students (all or students audience)
        $builder->where('(a.audience_type = "all" OR a.audience_type = "students")');
        $builder->where('a.status', 'published');
        
        // Only show announcements that are published or scheduled for past
        $builder->where('(a.publish_date IS NULL OR a.publish_date <= NOW())');
        
        // Only show announcements that have not expired
        $builder->groupStart()
                    ->where('a.expiry_date IS NULL')
                    ->orWhere('a.expiry_date >= NOW()')
                ->groupEnd();
        
        // Search filter
        if (!empty($search)) {
            $builder->groupStart()
                        ->like('a.title', $search)
                        ->orLike('a.content', $search)
                    ->groupEnd();
        }
        
        // Date range filter
        if (!empty($dateFrom)) {
            $builder->where('DATE(a.created_at) >=', $dateFrom);
        }
        if (!empty($dateTo)) {
            $builder->where('DATE(a.created_at) <=', $dateTo);
        }
        
        $builder->orderBy('a.created_at', 'DESC');
        $builder->limit($limit);
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get announcement statistics for dashboard
     * 
     * @return array
     */
    public function getAnnouncementStats()
    {
        $builder = $this->db->table($this->table . ' a');
        $builder->select('
            COUNT(*) as total_announcements,
            SUM(CASE WHEN a.status = "published" THEN 1 ELSE 0 END) as published_count,
            SUM(CASE WHEN a.audience_type = "all" THEN 1 ELSE 0 END) as all_audience_count,
            SUM(CASE WHEN a.audience_type = "students" THEN 1 ELSE 0 END) as students_audience_count
        ');
        $builder->where('(a.audience_type = "all" OR a.audience_type = "students")');
        
        return $builder->get()->getRowArray();
    }

    /**
     * Create a new announcement
     * 
     * @param array $data Announcement data
     * @return int|false Announcement ID on success, false on failure
     */
    public function createAnnouncement($data)
    {
        // Validate required fields
        if (empty($data['title']) || empty($data['content']) || empty($data['sender_id'])) {
            return false;
        }

        // Set default values
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        // Insert the announcement
        return $this->insert($data);
    }

    /**
     * Get announcement by ID
     * 
     * @param int $id Announcement ID
     * @return array|null
     */
    public function getAnnouncementById($id)
    {
        return $this->find($id);
    }

    /**
     * Get announcements by audience type for admin display
     * 
     * @param string $audience Audience type (all, students, teachers, parents)
     * @param int $limit Number of announcements to retrieve
     * @return array
     */
    public function getAnnouncementsByAudience($audience = 'all', $limit = 10)
    {
        $builder = $this->db->table($this->table . ' a');
        $builder->select('a.*, 
                        COALESCE(CONCAT(t.first_name, " ", t.last_name), u.name) as sender_name,
                        COALESCE(t.contact_number, u.email) as sender_email');
        $builder->join('teachers t', 'a.sender_id = t.id AND a.sender_type = "teacher"', 'left');
        $builder->join('users u', 'a.sender_id = u.id AND a.sender_type = "admin"', 'left');
        
        // Filter by audience if not 'all'
        if ($audience !== 'all') {
            $builder->where('a.audience_type', $audience);
        }
        // Keep query lenient to support varying schemas; filter by audience only
        $builder->orderBy('a.created_at', 'DESC');
        $builder->limit($limit);
        
        return $builder->get()->getResultArray();
    }

    /**
     * Admin feed: return latest announcements regardless of status/window (excluding soft-deleted)
     */
    public function getLatestForAdmin($limit = 50)
    {
        return $this->db->table($this->table . ' a')
            ->select('a.*, COALESCE(CONCAT(t.first_name, " ", t.last_name), u.name) as sender_name')
            ->join('teachers t', 'a.sender_id = t.id AND a.sender_type = "teacher"', 'left')
            ->join('users u', 'a.sender_id = u.id AND a.sender_type = "admin"', 'left')
            ->where('a.deleted_at IS NULL')
            ->orderBy('COALESCE(a.publish_date, a.created_at)', 'DESC', false)
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Return published announcements within publish/expiry window for admin.
     */
    public function getPublishedForAdmin($limit = 50)
    {
        $builder = $this->db->table($this->table . ' a');
        $builder->select('a.*, COALESCE(CONCAT(t.first_name, " ", t.last_name), u.name) as sender_name')
            ->join('teachers t', 'a.sender_id = t.id AND a.sender_type = "teacher"', 'left')
            ->join('users u', 'a.sender_id = u.id AND a.sender_type = "admin"', 'left')
            ->where('a.deleted_at IS NULL');

        // Status published
        $builder->where('a.status', 'published');
        // Publish date past or null
        $builder->groupStart()
            ->where('a.publish_date IS NULL')
            ->orWhere('a.publish_date <= NOW()')
        ->groupEnd();
        // Not expired
        $builder->groupStart()
            ->where('a.expiry_date IS NULL')
            ->orWhere('a.expiry_date >= NOW()')
        ->groupEnd();

        return $builder->orderBy('COALESCE(a.publish_date, a.created_at)', 'DESC', false)
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Retrieve published announcements within publish/expiry window.
     * Optionally filter by audience and targeted users/groups.
     */
    public function getPublishedAnnouncements($audienceType = 'all', $userId = null, $limit = 100)
    {
        $builder = $this->db->table($this->table . ' a');
        $builder->select('a.*, COALESCE(CONCAT(t.first_name, " ", t.last_name), u.name) as sender_name')
            ->join('teachers t', 'a.sender_id = t.id AND a.sender_type = "teacher"', 'left')
            ->join('users u', 'a.sender_id = u.id AND a.sender_type = "admin"', 'left')
            ->where('a.deleted_at IS NULL')
            ->where('a.status', 'published')
            ->groupStart()
                ->where('a.publish_date IS NULL')
                ->orWhere('a.publish_date <= NOW()')
            ->groupEnd()
            ->groupStart()
                ->where('a.expiry_date IS NULL')
                ->orWhere('a.expiry_date > NOW()')
            ->groupEnd();

        $aud = strtolower((string) $audienceType);
        if (!empty($aud) && $aud !== 'all') {
            $builder->groupStart()
                ->where('LOWER(a.audience_type)', $aud)
                // Optional targeting via JSON/text columns if present
                ->orLike('a.target_groups', $aud)
                ->groupEnd();
        }

        if (!empty($userId)) {
            // If target_users contains this user id (simple LIKE for CSV/JSON storage)
            $builder->groupStart()
                ->orLike('a.target_users', '"' . $userId . '"')
                ->orLike('a.target_users', ',' . $userId . ',')
                ->orLike('a.target_users', $userId)
            ->groupEnd();
        }

        return $builder->orderBy('COALESCE(a.publish_date, a.created_at)', 'DESC', false)
            ->limit($limit)
            ->get()
            ->getResultArray();
    }
}
