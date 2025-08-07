<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TeacherModel;

class TeacherController extends BaseController
{
    protected $teacherModel;
    
    public function __construct()
    {
        $this->teacherModel = new TeacherModel();
    }
    
    public function index()
    {
        $data['teachers'] = $this->teacherModel->findAll();
        return view('backend/admin/teachers/teacher', $data);
    }
    
    public function dashboard()
    {
        // Load the teacher dashboard view
        $data = [
            'pageTitle' => 'Teacher Dashboard'
        ];
        return view('backend/teacher/dashboard/home', $data);
    }
    
    public function create()
    {
        return view('backend/admin/teachers/create');
    }
    
    public function store()
    {
        $rules = [
            'account_no' => 'required|is_unique[teachers.account_no]',
            'name' => 'required',
            'subjects' => 'required',
            'gender' => 'required',
            'age' => 'required|numeric',
            'status' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'account_no' => $this->request->getPost('account_no'),
            'name' => $this->request->getPost('name'),
            'subjects' => $this->request->getPost('subjects'),
            'gender' => $this->request->getPost('gender'),
            'age' => $this->request->getPost('age'),
            'student_count' => $this->request->getPost('student_count') ?? 0,
            'status' => $this->request->getPost('status'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Handle profile picture upload
        $profilePicture = $this->request->getFile('profile_picture');
        if ($profilePicture && $profilePicture->isValid() && !$profilePicture->hasMoved()) {
            $newName = $profilePicture->getRandomName();
            $profilePicture->move(ROOTPATH . 'public/uploads/teachers', $newName);
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
                file_put_contents(ROOTPATH . 'public/uploads/teachers/' . $newName, $decodedImage);
                $data['profile_picture'] = $newName;
            }
        }
        
        $this->teacherModel->insert($data);
        
        return redirect()->to('admin/teacher')->with('success', 'Teacher added successfully');
    }
    
    public function edit($id)
    {
        $data['teacher'] = $this->teacherModel->find($id);
        
        if (empty($data['teacher'])) {
            return redirect()->to('admin/teacher')->with('error', 'Teacher not found');
        }
        
        return view('backend/admin/teachers/edit', $data);
    }
    
    public function update($id)
    {
        $teacher = $this->teacherModel->find($id);
        
        if (empty($teacher)) {
            // Check if it's an AJAX request
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Teacher not found'
                ]);
            }
            return redirect()->to('admin/teacher')->with('error', 'Teacher not found');
        }
        
        $rules = [
            'name' => 'required',
            'subjects' => 'required',
            'gender' => 'required',
            'age' => 'required|numeric',
            'status' => 'required'
        ];
        
        // Only validate account_no uniqueness if it has changed
        if ($teacher['account_no'] != $this->request->getPost('account_no')) {
            $rules['account_no'] = 'required|is_unique[teachers.account_no]';
        } else {
            $rules['account_no'] = 'required';
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
            'account_no' => $this->request->getPost('account_no'),
            'name' => $this->request->getPost('name'),
            'subjects' => $this->request->getPost('subjects'),
            'gender' => $this->request->getPost('gender'),
            'age' => $this->request->getPost('age'),
            'student_count' => $this->request->getPost('student_count') ?? $teacher['student_count'],
            'status' => $this->request->getPost('status')
        ];
        
        // Handle profile picture upload
        $profilePicture = $this->request->getFile('profile_picture');
        if ($profilePicture && $profilePicture->isValid() && !$profilePicture->hasMoved()) {
            // Delete old profile picture if exists
            if (!empty($teacher['profile_picture']) && file_exists(ROOTPATH . 'public/uploads/teachers/' . $teacher['profile_picture'])) {
                unlink(ROOTPATH . 'public/uploads/teachers/' . $teacher['profile_picture']);
            }
            
            $newName = $profilePicture->getRandomName();
            $profilePicture->move(ROOTPATH . 'public/uploads/teachers', $newName);
            $data['profile_picture'] = $newName;
        }
        
        // Handle cropped image if provided
        $croppedImageData = $this->request->getPost('cropped_image_data');
        if ($croppedImageData) {
            // Delete old profile picture if exists
            if (!empty($teacher['profile_picture']) && file_exists(ROOTPATH . 'public/uploads/teachers/' . $teacher['profile_picture'])) {
                unlink(ROOTPATH . 'public/uploads/teachers/' . $teacher['profile_picture']);
            }
            
            $croppedImageData = str_replace('data:image/jpeg;base64,', '', $croppedImageData);
            $croppedImageData = str_replace(' ', '+', $croppedImageData);
            $decodedImage = base64_decode($croppedImageData);
            
            if ($decodedImage !== false) {
                $newName = uniqid() . '.jpg';
                file_put_contents(ROOTPATH . 'public/uploads/teachers/' . $newName, $decodedImage);
                $data['profile_picture'] = $newName;
            }
        }
        
        $updateResult = $this->teacherModel->update($id, $data);
        
        // Check if it's an AJAX request
        if ($this->request->isAJAX()) {
            if ($updateResult) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Teacher updated successfully',
                    'redirect' => site_url('admin/teacher')
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update teacher information'
                ]);
            }
        }
        
        return redirect()->to('admin/teacher')->with('success', 'Teacher updated successfully');
    }
    
    public function view($id)
    {
        $data['teacher'] = $this->teacherModel->find($id);
        
        if (empty($data['teacher'])) {
            return redirect()->to('admin/teacher')->with('error', 'Teacher not found');
        }
        
        return view('backend/admin/teachers/teacher_profile', $data);
    }
    
    public function delete($id)
    {
        $teacher = $this->teacherModel->find($id);
        
        if (empty($teacher)) {
            return redirect()->to('admin/teacher')->with('error', 'Teacher not found');
        }
        
        // Delete profile picture if exists
        if (!empty($teacher['profile_picture']) && file_exists(ROOTPATH . 'public/uploads/teachers/' . $teacher['profile_picture'])) {
            unlink(ROOTPATH . 'public/uploads/teachers/' . $teacher['profile_picture']);
        }
        
        $this->teacherModel->delete($id);
        
        return redirect()->to('admin/teacher')->with('success', 'Teacher deleted successfully');
    }
}