<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ParentModel;
use App\Models\StudentModel;

class ParentController extends BaseController
{
    protected $parentModel;
    protected $studentModel;
    
    public function __construct()
    {
        $this->parentModel = new ParentModel();
        $this->studentModel = new StudentModel();
    }
    
    public function index()
    {
        $data['parents'] = $this->parentModel->getAllParentsWithStudents();
        return view('backend/admin/parents/parent', $data);
    }
    
    public function create()
    {
        $data['students'] = $this->studentModel->findAll();
        return view('backend/admin/parents/create', $data);
    }
    
    public function store()
    {
        $rules = [
            'student_id' => 'required|integer',
            'relationship_type' => 'required|in_list[father,mother,guardian]',
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_number' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'student_id' => $this->request->getPost('student_id'),
            'relationship_type' => $this->request->getPost('relationship_type'),
            'first_name' => $this->request->getPost('first_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'last_name' => $this->request->getPost('last_name'),
            'suffix' => $this->request->getPost('suffix'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'occupation' => $this->request->getPost('occupation'),
            'employer' => $this->request->getPost('employer'),
            'work_address' => $this->request->getPost('work_address'),
            'monthly_income' => $this->request->getPost('monthly_income'),
            'educational_attainment' => $this->request->getPost('educational_attainment'),
            'contact_number' => $this->request->getPost('contact_number'),
            'phone_secondary' => $this->request->getPost('phone_secondary'),
            'email' => $this->request->getPost('email'),
            // Note: Emergency contact fields removed - now handled via parent relationships
            'is_primary_contact' => $this->request->getPost('is_primary_contact') ? true : false,
            'living_with_student' => $this->request->getPost('living_with_student') ? true : false,
            'custody_rights' => $this->request->getPost('custody_rights') ? true : false,
            'authorized_pickup' => $this->request->getPost('authorized_pickup') ? true : false,
            'address' => $this->request->getPost('address'),
            'facebook_account' => $this->request->getPost('facebook_account'),
            'other_social_media' => $this->request->getPost('other_social_media')
        ];
        
        // Handle profile picture upload
        $profilePicture = $this->request->getFile('profile_picture');
        if ($profilePicture && $profilePicture->isValid() && !$profilePicture->hasMoved()) {
            $newName = $profilePicture->getRandomName();
            $profilePicture->move(ROOTPATH . 'public/uploads/parents', $newName);
            $data['profile_picture'] = $newName;
        }
        
        // Handle cropped image if provided
        $croppedImageData = $this->request->getPost('cropped_image_data');
        if ($croppedImageData) {
            $croppedImageData = str_replace('data:image/jpeg;base64,', '', $croppedImageData);
            $croppedImageData = str_replace(' ', '+', $croppedImageData);
            $decodedImage = base64_decode($croppedImageData);
            
            if ($decodedImage !== false) {
                $newName = uniqid() . '.jpg';
                file_put_contents(ROOTPATH . 'public/uploads/parents/' . $newName, $decodedImage);
                $data['profile_picture'] = $newName;
            }
        }
        
        $this->parentModel->insert($data);
        
        return redirect()->to('admin/parent')->with('success', 'Parent/Guardian added successfully');
    }
    
    public function edit($id)
    {
        // Get parent data with relationship information and address data
        $db = \Config\Database::connect();
        $query = $db->query("
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
        ", [$id]);
        
        $data['parent'] = $query->getRowArray();
        $data['students'] = $this->studentModel->findAll();
        
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
        
        $parent = $this->parentModel->find($id);
        
        if (empty($parent)) {
            error_log('Parent not found for ID: ' . $id);
            return redirect()->to('admin/parent')->with('error', 'Parent/Guardian not found');
        }

        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_number' => 'required',
            'relationship_type' => 'required'
        ];

        if (!$this->validate($rules)) {
            error_log('Validation failed: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'last_name' => $this->request->getPost('last_name'),
            'contact_number' => $this->request->getPost('contact_number')
        ];

        // Update parent data in parents table
        error_log('Updating parent ID: ' . $id . ' with data: ' . json_encode($data));
        $parentUpdateResult = $this->parentModel->update($id, $data);
        error_log('Parent update result: ' . ($parentUpdateResult ? 'success' : 'failed'));

        // Update relationship data in student_parent_relationships table
        $db = \Config\Database::connect();
        $relationshipData = [
            'relationship_type' => $this->request->getPost('relationship_type'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        error_log('Updating relationship for parent ID: ' . $id . ' with data: ' . json_encode($relationshipData));
        $relationshipUpdateResult = $db->table('student_parent_relationships')
           ->where('parent_id', $id)
           ->update($relationshipData);
        error_log('Relationship update result: ' . ($relationshipUpdateResult ? 'success' : 'failed'));

        // Handle address data separately in student_parent_address table
        $addressData = [
            'house_number' => $this->request->getPost('house_number'),
            'street' => $this->request->getPost('street'),
            'barangay' => $this->request->getPost('barangay'),
            'municipality' => $this->request->getPost('municipality'),
            'province' => $this->request->getPost('province'),
            'zip_code' => $this->request->getPost('zip_code'),
            'is_same_as_student' => $this->request->getPost('is_same_as_student') ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        error_log('Address data to update: ' . json_encode($addressData));

        // Update or insert address data
        $existingAddress = $db->table('student_parent_address')
                             ->where('parent_id', $id)
                             ->get()
                             ->getRowArray();

        error_log('Existing address found: ' . ($existingAddress ? 'yes' : 'no'));

        if ($existingAddress) {
            // Update existing address record
            $addressUpdateResult = $db->table('student_parent_address')
               ->where('parent_id', $id)
               ->update($addressData);
            error_log('Address update result: ' . ($addressUpdateResult ? 'success' : 'failed'));
        } else {
            // Insert new address record
            $addressData['parent_id'] = $id;
            $addressData['created_at'] = date('Y-m-d H:i:s');
            $addressInsertResult = $db->table('student_parent_address')->insert($addressData);
            error_log('Address insert result: ' . ($addressInsertResult ? 'success' : 'failed'));
        }

        error_log('=== PARENT UPDATE END ===');
        return redirect()->to('admin/parent')->with('success', 'Parent/Guardian updated successfully');
    }
    
    public function view($id)
    {
        $data['parent'] = $this->parentModel->find($id);
        
        if (empty($data['parent'])) {
            return redirect()->to('admin/parent')->with('error', 'Parent/Guardian not found');
        }
        
        // Get student information
        $data['student'] = $this->studentModel->find($data['parent']['student_id']);
        
        return view('backend/admin/parents/parent_profile', $data);
    }
    
    public function delete($id)
    {
        $parent = $this->parentModel->find($id);
        
        if (empty($parent)) {
            return redirect()->to('admin/parent')->with('error', 'Parent/Guardian not found');
        }
        
        // Delete profile picture if exists
        if (!empty($parent['profile_picture']) && file_exists(ROOTPATH . 'public/uploads/parents/' . $parent['profile_picture'])) {
            unlink(ROOTPATH . 'public/uploads/parents/' . $parent['profile_picture']);
        }
        
        $this->parentModel->delete($id);
        
        return redirect()->to('admin/parent')->with('success', 'Parent/Guardian deleted successfully');
    }
}