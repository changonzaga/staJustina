<?php

namespace App\Models;

use CodeIgniter\Model;

class TeacherAddressModel extends Model
{
    protected $table = 'teacher_addresses';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'teacher_id',
        'address_type',
        'street_address',
        'barangay',
        'city',
        'province',
        'postal_code',
        'country',
        'is_current'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';
    
    // Validation rules
    protected $validationRules = [
        'teacher_id' => 'required|integer',
        'address_type' => 'required|in_list[residential,permanent,mailing]',
        'street_address' => 'required',
        'barangay' => 'permit_empty|max_length[100]',
        'city' => 'permit_empty|max_length[100]',
        'province' => 'permit_empty|max_length[100]',
        'postal_code' => 'permit_empty|max_length[10]',
        'country' => 'permit_empty|max_length[100]',
        'is_current' => 'permit_empty|in_list[0,1]'
    ];
    
    protected $validationMessages = [
        'teacher_id' => [
            'required' => 'Teacher ID is required',
            'integer' => 'Teacher ID must be a valid number'
        ],
        'address_type' => [
            'required' => 'Address type is required',
            'in_list' => 'Address type must be residential, permanent, or mailing'
        ],
        'street_address' => [
            'required' => 'Street address is required'
        ]
    ];
    
    /**
     * Get addresses by teacher ID
     */
    public function getByTeacherId($teacherId)
    {
        return $this->where('teacher_id', $teacherId)
                   ->where('is_current', 1)
                   ->orderBy('address_type', 'ASC')
                   ->findAll();
    }
    
    /**
     * Get address by teacher ID and type
     */
    public function getByTeacherAndType($teacherId, $addressType)
    {
        return $this->where('teacher_id', $teacherId)
                   ->where('address_type', $addressType)
                   ->where('is_current', 1)
                   ->first();
    }
    
    /**
     * Get residential address
     */
    public function getResidentialAddress($teacherId)
    {
        return $this->getByTeacherAndType($teacherId, 'residential');
    }
    
    /**
     * Get permanent address
     */
    public function getPermanentAddress($teacherId)
    {
        return $this->getByTeacherAndType($teacherId, 'permanent');
    }
    
    /**
     * Get mailing address
     */
    public function getMailingAddress($teacherId)
    {
        return $this->getByTeacherAndType($teacherId, 'mailing');
    }
    
    /**
     * Create or update address
     */
    public function createOrUpdateAddress($teacherId, $addressType, $addressData)
    {
        // Check if address already exists
        $existingAddress = $this->getByTeacherAndType($teacherId, $addressType);
        
        $data = array_merge($addressData, [
            'teacher_id' => $teacherId,
            'address_type' => $addressType,
            'is_current' => 1
        ]);
        
        if ($existingAddress) {
            return $this->update($existingAddress['id'], $data);
        } else {
            return $this->insert($data);
        }
    }
    
    /**
     * Get formatted address string
     */
    public function getFormattedAddress($addressId)
    {
        $address = $this->find($addressId);
        if (!$address) return '';
        
        $parts = [];
        
        if (!empty($address['street_address'])) {
            $parts[] = $address['street_address'];
        }
        
        if (!empty($address['barangay'])) {
            $parts[] = $address['barangay'];
        }
        
        if (!empty($address['city'])) {
            $parts[] = $address['city'];
        }
        
        if (!empty($address['province'])) {
            $parts[] = $address['province'];
        }
        
        if (!empty($address['postal_code'])) {
            $parts[] = $address['postal_code'];
        }
        
        if (!empty($address['country']) && $address['country'] !== 'Philippines') {
            $parts[] = $address['country'];
        }
        
        return implode(', ', $parts);
    }
    
    /**
     * Deactivate old addresses when updating
     */
    public function deactivateOldAddresses($teacherId, $addressType, $excludeId = null)
    {
        $builder = $this->where('teacher_id', $teacherId)
                       ->where('address_type', $addressType);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->set('is_current', 0)->update();
    }
}