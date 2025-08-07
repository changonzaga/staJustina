<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ParentModel;
use App\Libraries\Hash;

class ParentController extends BaseController
{
    protected $parentModel;
    
    public function __construct()
    {
        $this->parentModel = new ParentModel();
    }
    
    public function index()
    {
        $data['parents'] = $this->parentModel->findAll();
        return view('backend/admin/parents/parent', $data);
    }
    
    public function create()
    {
        return view('backend/admin/parents/create');
    }
    
    public function store()
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|valid_email|is_unique[parent.email]',
            'password' => 'required|min_length[6]',
            'contact' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => Hash::make($this->request->getPost('password')),
            'contact' => $this->request->getPost('contact')
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
        
        return redirect()->to('admin/parent')->with('success', 'Parent added successfully');
    }
    
    public function edit($id)
    {
        $data['parent'] = $this->parentModel->find($id);
        
        if (empty($data['parent'])) {
            return redirect()->to('admin/parent')->with('error', 'Parent not found');
        }
        
        return view('backend/admin/parents/edit', $data);
    }
    
    public function update($id)
    {
        $parent = $this->parentModel->find($id);
        
        if (empty($parent)) {
            // Check if it's an AJAX request
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Parent not found'
                ]);
            }
            return redirect()->to('admin/parent')->with('error', 'Parent not found');
        }
        
        $rules = [
            'name' => 'required',
            'contact' => 'required'
        ];
        
        // Only validate email uniqueness if it has changed
        if ($parent['email'] != $this->request->getPost('email')) {
            $rules['email'] = 'required|valid_email|is_unique[parent.email]';
        } else {
            $rules['email'] = 'required|valid_email';
        }
        
        // Password is optional during update
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'min_length[6]';
        }
        
        if (!$this->validate($rules)) {
            // Check if it's an AJAX request
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'contact' => $this->request->getPost('contact')
        ];
        
        // Update password only if provided
        if (!empty($password)) {
            $data['password'] = Hash::make($password);
        }
        
        // Handle profile picture upload
        $profilePicture = $this->request->getFile('profile_picture');
        if ($profilePicture && $profilePicture->isValid() && !$profilePicture->hasMoved()) {
            // Delete old profile picture if exists
            if (!empty($parent['profile_picture']) && file_exists(ROOTPATH . 'public/uploads/parents/' . $parent['profile_picture'])) {
                unlink(ROOTPATH . 'public/uploads/parents/' . $parent['profile_picture']);
            }
            
            $newName = $profilePicture->getRandomName();
            $profilePicture->move(ROOTPATH . 'public/uploads/parents', $newName);
            $data['profile_picture'] = $newName;
        }
        
        // Handle cropped image if provided
        $croppedImageData = $this->request->getPost('cropped_image_data');
        if ($croppedImageData) {
            // Delete old profile picture if exists
            if (!empty($parent['profile_picture']) && file_exists(ROOTPATH . 'public/uploads/parents/' . $parent['profile_picture'])) {
                unlink(ROOTPATH . 'public/uploads/parents/' . $parent['profile_picture']);
            }
            
            $croppedImageData = str_replace('data:image/jpeg;base64,', '', $croppedImageData);
            $croppedImageData = str_replace(' ', '+', $croppedImageData);
            $decodedImage = base64_decode($croppedImageData);
            
            if ($decodedImage !== false) {
                $newName = uniqid() . '.jpg';
                file_put_contents(ROOTPATH . 'public/uploads/parents/' . $newName, $decodedImage);
                $data['profile_picture'] = $newName;
            }
        }
        
        $updateResult = $this->parentModel->update($id, $data);
        
        // Check if it's an AJAX request
        if ($this->request->isAJAX()) {
            if ($updateResult) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Parent updated successfully',
                    'redirect' => site_url('admin/parent')
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update parent information'
                ]);
            }
        }
        
        return redirect()->to('admin/parent')->with('success', 'Parent updated successfully');
    }
    
    public function view($id)
    {
        $data['parent'] = $this->parentModel->find($id);
        
        if (empty($data['parent'])) {
            return redirect()->to('admin/parent')->with('error', 'Parent not found');
        }
        
        return view('backend/admin/parents/parent_profile', $data);
    }
    
    public function delete($id)
    {
        $parent = $this->parentModel->find($id);
        
        if (empty($parent)) {
            return redirect()->to('admin/parent')->with('error', 'Parent not found');
        }
        
        // Delete profile picture if exists
        if (!empty($parent['profile_picture']) && file_exists(ROOTPATH . 'public/uploads/parents/' . $parent['profile_picture'])) {
            unlink(ROOTPATH . 'public/uploads/parents/' . $parent['profile_picture']);
        }
        
        $this->parentModel->delete($id);
        
        return redirect()->to('admin/parent')->with('success', 'Parent deleted successfully');
    }
}