<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\CIAuth;
use App\Libraries\Hash;
use App\Models\User;
use App\Models\PasswordResetToken;
use Carbon\Carbon;


class AuthController extends BaseController
{
    protected $helpers = ['url','form', 'CIMail'];
    public function loginForm()
    {
        $data = [
            'pageTitle' => 'Login',
            'validation' => null,
        ];
        return view('backend/pages/auth/login', $data);
    }

    public function loginHandler(){
        $fieldType = filter_var($this->request->getVar('login_id'), FILTER_VALIDATE_EMAIL)? 'email' : 'username';

        if ($fieldType == 'email') {
            $isValid = $this->validate([
                'login_id' => [
                    'rules' => 'required|valid_email|is_not_unique[users.email]',
                    'errors' => [
                        'required' => 'Email is required',
                        'valid_email' => 'Email is not valid',
                        'is_not_unique' => 'Email is not exists'
                    ]
                ],
                'password' => [
                    'rules'=>'required|min_length[8]|max_length[45]',
                    'errors'=>[
                        'required'=>'Password is required.',
                        'min_length'=>'Password must have at least 8 characters.',
                        'max_length'=>'Password must have at most 45 characters.'
                    ]
                ]
                    ]);
    }else{
        $isValid = $this->validate([
            'login_id' => [
                'rules' => 'required|is_not_unique[users.username]',
                'errors' => [
                    'required' => 'Username is required',
                    'is_not_unique' => 'Username is not exists'
                ]
            ],
            'password' => [
                'rules'=>'required|min_length[8]|max_length[45]',
                'errors'=>[
                    'required'=>'Password is required.',
                    'min_length'=>'Password must have at least 8 characters.',
                    'max_length'=>'Password must have at most 45 characters.'
                ]
            ]
                ]);
            }
                if(!$isValid){
                    return view ('backend/pages/auth/login',[
                        'pageTitle'=>'Login',
                        'validation' => $this->validator
                    ]);
                }else{
                    $user = new User();
                    $userInfo = $user->where($fieldType, $this->request->getVar('login_id'))->first(); 
                    $check_password = Hash::check($this->request->getVar('password'), $userInfo['password']);

                    if (!$check_password){
                        return redirect()->route('admin.login.form')->with('fail', 'Password is incorrect')->withInput();
                    }else{
                        CIAuth::setCIAuth($userInfo);
                        return redirect()->to('/admin/home');
                    }
                }      
            }

            public function forgotForm (){
                $data = array(
                    'pageTitle' => 'Forgot password',
                    'validation' => null,
                );

                return view('/backend/pages/auth/forgot', $data);
            }

            public function sendPasswordResetLink (){
                $isValid = $this->validate([
                    'email' => [
                        'rules' => 'required|valid_email|is_not_unique[users.email]',
                        'errors' => [
                            'required' => 'Email is required',
                            'valid_email' => 'Email is not valid',
                            'is_not_unique' => 'Email is not exists',

                        ],
                    ]
                ]);

                if (!$isValid) {
                    return view('backend/pages/auth/forgot', [
                        'pageTitle' => 'Forgot password',
                        'validation' => $this->validator,
                    ]);
                }else{
                    //get user admin information
                    $user = new User();
                    $user_info = $user->asObject()->where('email', $this->request->getVar('email'))->first();

                    //generated token
                    $token = bin2hex(openssl_random_pseudo_bytes(65));

                    //get reset password token
                    $password_reset_token = new PasswordResetToken();
                    $isOldTokenExists = $password_reset_token->asObject()->where('email',$user_info->email)->first(); 

                    if ($isOldTokenExists){
                        //update existing token
                        $password_reset_token->where('email', $user_info->email)
                                            ->set(['token'=>$token,'created_at'=>Carbon::now()])
                                            ->update();
                    }else{
                        $password_reset_token->insert([
                            'email' => $user_info->email,
                            'token' => $token,
                            'created_at' => Carbon::now()

                        ]);
                    }
                    //create action link
                    $actionLink = route_to('admin.reset-password', $token);

                    $mail_data = array(
                        'actionLink' => $actionLink,
                        'user' => $user_info
                    );

                    // $view = \Config\Services::render();
                    // $mail_body = $view->setVar('mail_data', $mail_data)->render('email-templates/forgot-email-template');

                    $view = \Config\Services::renderer();
                    $mail_body = $view->setVar('mail_data', $mail_data)->render('email-templates/forgot-email-template');


                    
                    $mailConfig = array(
                        'mail_from_email' =>  env('EMAIL_FROM_ADDRESS'),
                        'mail_from_name' =>  env('EMAIL_FROM_NAME'),
                        'mail_recipient_email' =>  $user_info->email,
                        'mail_recipient_name' =>  $user_info->name,
                        'mail_subject' =>  'Reset Password',
                        'mail_body' =>  $mail_body
                
                    );
                    //send email
                    if (sendEmail($mailConfig)) {
                        return redirect()->route('admin.forgot.form')->with('success', 'Please check your email for the reset password link');
                    } else {
                        return redirect()->route('admin.forgot.form')->with('fail', 'Something went wrong. Please try again later.');
                    }
                    
                }
            }

    public function studentLogin()
    {
        $fieldType = filter_var($this->request->getVar('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        if ($fieldType == 'email') {
            $isValid = $this->validate([
                'username' => [
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'required' => 'Email is required',
                        'valid_email' => 'Email is not valid'
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[6]',
                    'errors' => [
                        'required' => 'Password is required.',
                        'min_length' => 'Password must have at least 6 characters.'
                    ]
                ]
            ]);
        } else {
            $isValid = $this->validate([
                'username' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Username is required'
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[6]',
                    'errors' => [
                        'required' => 'Password is required.',
                        'min_length' => 'Password must have at least 6 characters.'
                    ]
                ]
            ]);
        }
        
        if (!$isValid) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        // For demo purposes, redirect to student dashboard
        // In a real application, you would validate credentials against student database
        return redirect()->to('/student/dashboard');
    }
    
    public function teacherLogin()
    {
        $fieldType = filter_var($this->request->getVar('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        if ($fieldType == 'email') {
            $isValid = $this->validate([
                'username' => [
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'required' => 'Email is required',
                        'valid_email' => 'Email is not valid'
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[6]',
                    'errors' => [
                        'required' => 'Password is required.',
                        'min_length' => 'Password must have at least 6 characters.'
                    ]
                ]
            ]);
        } else {
            $isValid = $this->validate([
                'username' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Username is required'
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[6]',
                    'errors' => [
                        'required' => 'Password is required.',
                        'min_length' => 'Password must have at least 6 characters.'
                    ]
                ]
            ]);
        }
        
        if (!$isValid) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        // For demo purposes, redirect to teacher dashboard
        // In a real application, you would validate credentials against teacher database
        return redirect()->to('/teacher/dashboard');
    }
}
