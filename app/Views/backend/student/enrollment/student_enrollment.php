<?php

/**
 * DepEd Basic Education Enrollment Form
 * Based on official DepEd enrollment form layout
 * File: app/Views/backend/student/enrollment/student_enrollment.php
 */
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual Enrollment - Sta Justina National High School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/backend/vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="/backend/vendors/styles/style.css" />
    <link href="<?= base_url('backend/src/css/enrollment-buttons.css') ?>" rel="stylesheet">
    <link rel="icon" type="image/png" href="/backend/vendors/images/logo-login-removebg-preview.png">
    <style>
        /* Form Wizard Styles */
        .form-wizard-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .form-wizard-steps::before {
            content: "";
            position: absolute;
            top: 24px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e9ecef;
            z-index: 0;
        }

        .wizard-step {
            flex: 1;
            text-align: center;
            position: relative;
            z-index: 1;
        }
        
        /* File Upload Styles */
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .upload-area:hover, .upload-area.dragover {
            border-color: #007bff;
            background: #e3f2fd;
        }
        
        .upload-icon {
            font-size: 3rem;
            color: #6c757d;
            margin-bottom: 15px;
        }
        
        .upload-text {
            font-size: 1.1rem;
            font-weight: 500;
            color: #495057;
            margin-bottom: 5px;
        }
        
        .upload-hint {
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .document-preview {
            margin-top: 20px;
        }
        
        .document-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 10px;
            background: white;
        }
        
        .document-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 15px;
        }
        
        .document-info {
            flex: 1;
        }
        
        .document-name {
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .document-status {
            font-size: 0.875rem;
            color: #6c757d;
        }
        
        .remove-document {
            background: none;
            border: none;
            color: #dc3545;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 5px;
        }
        
        .remove-document:hover {
            color: #c82333;
        }
        
        /* Document Upload Section Styles */
        .document-upload-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            height: 100%;
        }
        
        .document-upload-section h6 {
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        
        .document-upload-section .upload-area {
            min-height: 120px;
            margin-bottom: 10px;
        }
        
        .document-upload-section .upload-text {
            font-size: 1rem;
        }
        
        .document-upload-section .upload-hint {
            font-size: 0.85rem;
        }
        
        .document-upload-section .document-preview {
            min-height: 60px;
        }

        .step-number {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            font-size: 18px;
            font-weight: 600;
            margin: 0 auto 10px;
            position: relative;
            z-index: 5;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .step-title {
            font-size: 14px;
            font-weight: 500;
            color: #6c757d;
            margin-bottom: 5px;
            transition: all 0.3s ease;
        }

        .wizard-step.active .step-number {
            background: #2c9aff;
            color: #fff;
        }

        .wizard-step.active .step-title {
            color: #2c9aff;
            font-weight: 600;
        }

        .wizard-step.completed .step-number {
            background: #28a745;
            color: #fff;
        }

        .wizard-step.completed .step-title {
            color: #28a745;
        }

        .form-wizard-content {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .form-wizard-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Wizard buttons now handled by global CSS */

        /* Form validation styles */
        .is-invalid {
            border-color: #dc3545 !important;
        }

        /* LRN Boxes */
        .lrn-boxes {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .lrn-box {
            width: 35px;
            height: 35px;
            border: 2px solid #007bff;
            text-align: center;
            line-height: 31px;
            font-weight: bold;
            border-radius: 4px;
            font-size: 16px;
        }

        /* Document Upload Area */
        .upload-area {
            border: 3px dashed #dee2e6;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            background: #f8f9fa;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .upload-area:hover {
            border-color: #007bff;
            background: #e7f3ff;
        }

        .upload-area.dragover {
            border-color: #28a745;
            background: #d4edda;
        }

        .upload-icon {
            font-size: 3rem;
            color: #6c757d;
            margin-bottom: 20px;
        }

        .upload-text {
            font-size: 1.1rem;
            color: #495057;
            margin-bottom: 10px;
        }

        .upload-hint {
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* Document Preview */
        .document-preview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .document-item {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            position: relative;
        }

        .document-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            background: #f1f5f9;
        }

        .document-info {
            padding: 15px;
        }

        .document-name {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .document-status {
            font-size: 0.8rem;
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
        }

        .status-processing {
            background: #fef3c7;
            color: #92400e;
        }

        .status-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .remove-document {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(239, 68, 68, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Enhanced Profile Picture Upload Styles */
        .profile-upload-container {
            max-width: 500px;
            margin: 0 auto;
        }

        .profile-upload-area {
            position: relative;
            border: 3px dashed #dee2e6;
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            cursor: pointer;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .profile-upload-area::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }

        .profile-upload-area:hover {
            border-color: #007bff;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.15);
        }

        .profile-upload-area:hover::before {
            transform: translateX(100%);
        }

        .profile-upload-area.dragover {
            border-color: #28a745;
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            transform: scale(1.02);
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.2);
        }

        .profile-file-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 2;
        }

        .upload-content {
            position: relative;
            z-index: 1;
            pointer-events: none;
        }

        .upload-icon {
            font-size: 3.5rem;
            color: #6c757d;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .profile-upload-area:hover .upload-icon {
            color: #007bff;
            transform: scale(1.1);
        }

        .upload-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            transition: color 0.3s ease;
        }

        .profile-upload-area:hover .upload-title {
            color: #007bff;
        }

        .upload-description {
            font-size: 0.95rem;
            color: #6c757d;
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .upload-formats {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 10px;
        }

        .format-badge {
            background: #e9ecef;
            color: #495057;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .profile-upload-area:hover .format-badge {
            background: #007bff;
            color: white;
            transform: translateY(-1px);
        }

        .upload-size-info {
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 0;
        }

        .upload-progress {
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .progress-text {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 0;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .file-selected-info {
            margin-top: 20px;
        }

        .selected-file-card {
            display: flex;
            align-items: center;
            padding: 15px;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .file-details {
            flex: 1;
            margin: 0;
        }

        .file-name {
            font-weight: 600;
            color: #495057;
            margin-bottom: 2px;
            font-size: 0.9rem;
        }

        .file-size {
            color: #6c757d;
            font-size: 0.8rem;
            margin-bottom: 0;
        }

        .remove-file-btn {
            background: transparent;
            color: #dc3545;
            border: none;
            border-radius: 0;
            width: auto;
            height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 4px;
            font-size: 1.2rem;
        }

        .remove-file-btn:hover {
            color: #c82333;
            transform: scale(1.1);
        }

        /* Responsive adjustments for profile upload */
        @media (max-width: 768px) {
            .profile-upload-area {
                padding: 30px 15px;
            }

            .upload-icon {
                font-size: 2.5rem;
            }

            .upload-title {
                font-size: 1.1rem;
            }

            .upload-description {
                font-size: 0.9rem;
            }

            .selected-file-card {
                padding: 12px;
                flex-direction: row;
                align-items: center;
            }

            .file-details {
                flex: 1;
                min-width: 0; /* Allow text to truncate */
            }

            .file-name {
                font-size: 0.85rem;
                word-break: break-word;
                overflow-wrap: break-word;
                line-height: 1.3;
            }

            .file-size {
                font-size: 0.75rem;
            }

            .remove-file-btn {
                width: auto;
                height: auto;
                font-size: 1rem;
                flex-shrink: 0;
                margin-left: 8px;
                padding: 3px;
            }
        }

        @media (max-width: 480px) {
            .selected-file-card {
                padding: 10px;
            }

            .file-name {
                font-size: 0.8rem;
                max-width: 180px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .file-size {
                font-size: 0.7rem;
            }

            .remove-file-btn {
                width: auto;
                height: auto;
                font-size: 0.9rem;
                padding: 2px;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-wizard-steps {
                flex-direction: column;
                gap: 15px;
                margin-bottom: 30px;
            }

            .form-wizard-steps::before {
                display: none;
            }

            .wizard-step {
                display: flex;
                align-items: center;
                text-align: left;
                background: white;
                padding: 15px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                border: 1px solid #e9ecef;
            }

            .step-number {
                margin: 0 15px 0 0;
                width: 40px;
                height: 40px;
                line-height: 40px;
                font-size: 16px;
            }

            .step-title {
                margin-bottom: 0;
                font-size: 14px;
            }

            .form-wizard-content {
                padding: 15px;
            }

            /* Responsive wizard buttons handled by global CSS */

            .lrn-boxes {
                justify-content: center;
            }

            .lrn-box {
                width: 30px;
                height: 30px;
                line-height: 26px;
                font-size: 14px;
            }
        }

        @media (max-width: 576px) {
            .form-wizard-steps {
                gap: 10px;
            }

            .wizard-step {
                padding: 12px;
            }

            .step-number {
                width: 35px;
                height: 35px;
                line-height: 35px;
                font-size: 14px;
                margin-right: 12px;
            }

            .step-title {
                font-size: 13px;
            }

            .card-body {
                padding: 15px;
            }

            .lrn-box {
                width: 28px;
                height: 28px;
                line-height: 24px;
                font-size: 13px;
            }
        }
    </style>
</head>

<body class="bg-light">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Login Header -->
                <div class="login-header box-shadow mb-4" style="margin-left: -15px; margin-right: -15px; padding-left: 15px; padding-right: 15px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="brand-logo d-flex align-items-center">
                            <a href="<?= base_url('enrollment') ?>" class="me-3">
                                <img src="/backend/vendors/images/logo-login.png" alt="" style="width: 60px;" />
                            </a>
                            <div class="school-info">
                                <h4 class="mb-0 text-black font-weight-bold">STA. JUSTINA HIGH SCHOOL</h4>
                                <p class="mb-0 text-muted small">Manual Enrollment Form</p>
                            </div>
                        </div>
                        <div class="login-menu">
                            <!-- Navigation menu removed -->
                        </div>
                    </div>
                </div>

                <!-- Form Container -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <!-- Form Wizard Steps -->
                        <div class="form-wizard-steps">
                            <div class="wizard-step active" data-step="1">
                                <div class="step-number">1</div>
                                <div class="step-title">Student Information</div>
                            </div>
                            <div class="wizard-step" data-step="2">
                                <div class="step-number">2</div>
                                <div class="step-title">Address & Family</div>
                            </div>
                            <div class="wizard-step" data-step="3">
                                <div class="step-number">3</div>
                                <div class="step-title">Academic & Special Needs</div>
                            </div>
                            <div class="wizard-step" data-step="4">
                                <div class="step-number">4</div>
                                <div class="step-title">Document Upload</div>
                            </div>
                            <div class="wizard-step" data-step="5">
                                <div class="step-number">5</div>
                                <div class="step-title">Review & Submit</div>
                            </div>
                        </div>

                        <form id="enrollmentForm" method="POST" action="<?= base_url('enrollment/store') ?>" enctype="multipart/form-data" novalidate>
                            <?= csrf_field() ?>

                            <!-- Step 1: Student Information -->
                            <div id="step-1" class="form-wizard-content active">
                                <!-- Instructions -->
                                <div class="alert alert-info mb-4">
                                    <h6><i class="fas fa-info-circle"></i> Instructions</h6>
                                    <p class="mb-0">Please fill out all required information accurately and completely. Submit the accomplished form to the Person-in-Charge/Registrar/Class Adviser for processing.</p>
                                </div>

                                <!-- Basic Enrollment Information -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="text-primary border-bottom pb-2 mb-3">Basic Enrollment Information</h6>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">School Year <span class="text-danger">*</span></label>
                                        <input type="text" name="school_year" class="form-control" value="<?= date('Y') . '-' . (date('Y') + 1) ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Grade Level to Enroll <span class="text-danger">*</span></label>
                                        <select name="grade_level" class="form-control" required>
                                            <option value="">Select Grade</option>
                                            <option value="Grade 7">Grade 7</option>
                                            <option value="Grade 8">Grade 8</option>
                                            <option value="Grade 9">Grade 9</option>
                                            <option value="Grade 10">Grade 10</option>
                                            <option value="Grade 11">Grade 11</option>
                                            <option value="Grade 12">Grade 12</option>
                                        </select>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label fw-bold">Enrollment Type <span class="text-danger">*</span></label>
                                        <div class="d-flex gap-4 mt-2 flex-wrap">
                                            <div class="form-check">
                                                <input type="radio" name="enrollment_type" value="new" id="new_learner" class="form-check-input" required>
                                                <label for="new_learner" class="form-check-label">New Learner (With LRN)</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" name="enrollment_type" value="returning" id="returning_learner" class="form-check-input" required>
                                                <label for="returning_learner" class="form-check-label">Returning (Balik-Aral)</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" name="enrollment_type" value="transfer" id="transfer_learner" class="form-check-input" required>
                                                <label for="transfer_learner" class="form-check-label">Transfer Enrollment</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Student Personal Information -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="text-primary border-bottom pb-2 mb-3">Student Personal Information</h6>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">PSA Birth Certificate No.</label>
                                        <input type="text" name="birth_certificate_number" class="form-control" placeholder="If available upon registration">
                                        <small class="form-text text-muted">Optional - if available</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Learner Reference No. (LRN) <span class="text-danger">*</span></label>
                                        <div class="lrn-boxes mb-2">
                                            <?php for ($i = 0; $i < 12; $i++): ?>
                                                <input type="text" name="lrn_digit_<?= $i ?>" class="lrn-box" maxlength="1" pattern="[0-9]" required>
                                            <?php endfor; ?>
                                        </div>
                                        <small class="form-text text-muted">12-digit Learner Reference Number</small>
                                    </div>
                                </div>

                                <!-- Name Fields -->
                                <div class="row mb-4">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Last Name <span class="required">*</span></label>
                                        <input type="text" name="last_name" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">First Name <span class="required">*</span></label>
                                        <input type="text" name="first_name" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Middle Name</label>
                                        <input type="text" name="middle_name" class="form-control">
                                    </div>
                                </div>

                                <!-- Extension Name and Birth Details -->
                                <div class="row mb-4">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Extension Name</label>
                                        <input type="text" name="extension_name" class="form-control" placeholder="Jr., III, etc.">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Birthdate <span class="required">*</span></label>
                                        <input type="date" name="date_of_birth" class="form-control" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Place of Birth</label>
                                        <input type="text" name="place_of_birth" class="form-control" placeholder="City, Province">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-bold">Sex <span class="text-danger">*</span></label>
                                        <div class="d-flex gap-3 mt-2">
                                            <div class="form-check">
                                                <input type="radio" name="gender" value="Male" id="male" class="form-check-input" required>
                                                <label for="male" class="form-check-label">Male</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" name="gender" value="Female" id="female" class="form-check-input" required>
                                                <label for="female" class="form-check-label">Female</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Age <span class="required">*</span></label>
                                        <input type="number" name="age" class="form-control" min="3" max="25" required>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mother Tongue</label>
                                        <input type="text" name="mother_tongue" class="form-control" placeholder="Primary language spoken at home">
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="text-primary mb-3">Contact Information</h6>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" name="student_email" class="form-control" placeholder="student@gmail.com">
                                        <small class="form-text text-muted">Optional - if available</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Contact Number</label>
                                        <input type="tel" name="student_contact" class="form-control" placeholder="09XX-XXX-XXXX">
                                        <small class="form-text text-muted">Student's mobile number (if available)</small>
                                    </div>
                                </div>



                                <!-- Indigenous Peoples -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <label class="form-label">Belonging to any Indigenous Peoples (IP) Community?</label>
                                        <div class="checkbox-group mb-3">
                                            <div class="checkbox-item">
                                                <input type="radio" name="indigenous_people" value="Yes" id="ip_yes">
                                                <label for="ip_yes">Yes</label>
                                            </div>
                                            <div class="checkbox-item">
                                                <input type="radio" name="indigenous_people" value="No" id="ip_no">
                                                <label for="ip_no">No</label>
                                            </div>
                                        </div>
                                        <div id="ip_community_field" style="display: none;">
                                            <input type="text" name="indigenous_community" class="form-control" placeholder="Please specify the community">
                                        </div>
                                    </div>
                                </div>

                                <!-- 4Ps Beneficiary -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <label class="form-label">Is your family a beneficiary of 4Ps?</label>
                                        <div class="checkbox-group mb-3">
                                            <div class="checkbox-item">
                                                <input type="radio" name="fourps_beneficiary" value="Yes" id="fourps_yes">
                                                <label for="fourps_yes">Yes</label>
                                            </div>
                                            <div class="checkbox-item">
                                                <input type="radio" name="fourps_beneficiary" value="No" id="fourps_no">
                                                <label for="fourps_no">No</label>
                                            </div>
                                        </div>
                                        <div id="fourps_id_field" style="display: none;">
                                            <input type="text" name="fourps_household_id" class="form-control" placeholder="4Ps Household ID Number">
                                        </div>
                                    </div>
                                </div>

                                <!-- Profile Picture Upload Section -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="text-center">
                                            <h6 class="text-primary section-header mb-3">Profile Picture (Optional)</h6>
                                            <p class="text-muted mb-3">Upload a profile picture for your student record. You can crop and adjust the image as needed.</p>
                                            
                                            <div class="profile-picture-section">
                                                <!-- Enhanced File Upload Area -->
                                                <div class="profile-upload-container">
                                                    <div class="profile-upload-area" id="profileUploadArea">
                                                        <div class="upload-content">
                                                            <div class="upload-icon">
                                                                <i class="fas fa-camera"></i>
                                                            </div>
                                                            <h6 class="upload-title">Upload Profile Picture</h6>
                                                            <p class="upload-description">Drag and drop your image here or click to browse</p>
                                                            <div class="upload-formats">
                                                                <span class="format-badge">JPG</span>
                                                                <span class="format-badge">PNG</span>
                                                                <span class="format-badge">GIF</span>
                                                            </div>
                                                            <p class="upload-size-info">Maximum file size: 2MB</p>
                                                        </div>
                                                        <input type="file" id="profile_picture" name="profile_picture" class="profile-file-input" accept="image/*" onchange="loadImageForCropping(event)">
                                                    </div>
                                                    
                                                    <!-- Upload Progress (Hidden by default) -->
                                                    <div class="upload-progress" id="uploadProgress" style="display: none;">
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                                                        </div>
                                                        <p class="progress-text">Uploading image...</p>
                                                    </div>
                                                    
                                                    <!-- File Selected Info (Hidden by default) -->
                                                    <div class="file-selected-info" id="fileSelectedInfo" style="display: none;">
                                                        <div class="selected-file-card">
                                                            <div class="file-details">
                                                                <p class="file-name" id="selectedFileName"></p>
                                                                <p class="file-size" id="selectedFileSize"></p>
                                                            </div>
                                                            <button type="button" class="remove-file-btn" onclick="removeSelectedFile()">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <?php if (isset($validation) && $validation->hasError('profile_picture')): ?>
                                                    <div class="invalid-feedback d-block mt-2"><?= $validation->getError('profile_picture') ?></div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Image Cropper Container (Hidden by default) -->
                                            <div id="image-cropper-container" class="mt-3" style="display: none;">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="img-container mb-3">
                                                            <img id="image-to-crop" src="" alt="Image to crop" style="max-width: 100%;">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="cropper-control-panel">
                                                            <h6 class="mb-3">Cropper Controls</h6>
                                                            
                                                            <!-- Aspect Ratio Buttons -->
                                                            <div class="aspect-ratio-buttons mb-3">
                                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="cropper && cropper.setAspectRatio(1)">1:1</button>
                                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="cropper && cropper.setAspectRatio(4/3)">4:3</button>
                                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="cropper && cropper.setAspectRatio(NaN)">Free</button>
                                                            </div>
                                                            
                                                            <!-- Action Buttons -->
                                                            <div class="mb-3">
                                                                <button type="button" class="btn btn-success btn-block" onclick="cropImage()">Crop & Preview</button>
                                                                <button type="button" class="btn btn-secondary btn-block" onclick="cancelCrop()">Cancel</button>
                                                            </div>
                                                            
                                                            <!-- Zoom Controls -->
                                                            <div class="mb-2">
                                                                <label class="small">Zoom:</label>
                                                                <div class="btn-group btn-group-sm d-flex" role="group">
                                                                    <button type="button" class="btn btn-outline-secondary" onclick="cropper && cropper.zoom(-0.1)">-</button>
                                                                    <button type="button" class="btn btn-outline-secondary" onclick="cropper && cropper.zoom(0.1)">+</button>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Rotate Controls -->
                                                            <div class="mb-2">
                                                                <label class="small">Rotate:</label>
                                                                <div class="btn-group btn-group-sm d-flex" role="group">
                                                                    <button type="button" class="btn btn-outline-secondary" onclick="cropper && cropper.rotate(-90)">↺</button>
                                                                    <button type="button" class="btn btn-outline-secondary" onclick="cropper && cropper.rotate(90)">↻</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Cropped Image Preview -->
                                            <div id="cropped-preview-container" class="mt-3" style="display: none;">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h6>Preview:</h6>
                                                        <div class="text-center">
                                                            <img id="cropped-image-preview" src="" alt="Cropped preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="document.getElementById('image-cropper-container').style.display = 'block'; document.getElementById('cropped-preview-container').style.display = 'none';">Edit Again</button>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Hidden input for cropped image data -->
                                            <input type="hidden" id="cropped_image_data" name="cropped_image_data" value="">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Step 2: Address & Family Information -->
                            <div id="step-2" class="form-wizard-content">
                                <!-- Current Address -->
                                <h6 class="text-primary section-header mb-3">Current Address</h6>
                                <div class="address-section">
                                    <div class="row mb-3">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">House No. <span class="required">*</span></label>
                                            <input type="text" name="current_house_no" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Lot/Street Name <span class="required">*</span></label>
                                            <input type="text" name="current_street" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Barangay <span class="required">*</span></label>
                                            <input type="text" name="current_barangay" class="form-control" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Municipality/City <span class="required">*</span></label>
                                            <input type="text" name="current_municipality" class="form-control" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Province <span class="required">*</span></label>
                                            <input type="text" name="current_province" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Country</label>
                                            <input type="text" name="current_country" class="form-control" value="Philippines">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Zip Code</label>
                                            <input type="text" name="current_zip_code" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <!-- Permanent Address -->
                                <h6 class="text-primary section-header mb-3">Permanent Address</h6>
                                <div class="address-section">
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input type="checkbox" name="same_as_current" id="same_address" class="form-check-input">
                                                <label for="same_address" class="form-check-label">Same as Current Address</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="permanent_address_fields">
                                        <div class="row mb-3">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">House No./Street</label>
                                                <input type="text" name="permanent_house_street" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Street Name</label>
                                                <input type="text" name="permanent_street_name" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Barangay</label>
                                                <input type="text" name="permanent_barangay" class="form-control">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Municipality/City</label>
                                                <input type="text" name="permanent_municipality" class="form-control">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Province</label>
                                                <input type="text" name="permanent_province" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Country</label>
                                                <input type="text" name="permanent_country" class="form-control" value="Philippines">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Zip Code</label>
                                                <input type="text" name="permanent_zip_code" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Parent/Guardian Information -->
                                <div class="section-header">Parent/Guardian Information</div>

                                <!-- Father's Information -->
                                <div class="mb-4">
                                    <h6 class="text-primary mb-3">Father's Information</h6>
                                    <div class="row mb-3">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" name="father_last_name" class="form-control">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">First Name</label>
                                            <input type="text" name="father_first_name" class="form-control">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Middle Name</label>
                                            <input type="text" name="father_middle_name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Contact Number</label>
                                            <input type="tel" name="father_contact" class="form-control" placeholder="09XXXXXXXXX">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check mt-4">
                                                <input type="radio" name="emergency_contact" value="father" id="father_emergency_contact" class="form-check-input">
                                                <label for="father_emergency_contact" class="form-check-label">
                                                    <i class="fas fa-phone-alt text-danger me-1"></i>Select as Emergency Contact
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mother's Information -->
                                <div class="mb-4">
                                    <h6 class="text-primary mb-3">Mother's Information (Maiden Name)</h6>
                                    <div class="row mb-3">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" name="mother_last_name" class="form-control">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">First Name</label>
                                            <input type="text" name="mother_first_name" class="form-control">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Middle Name</label>
                                            <input type="text" name="mother_middle_name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Contact Number</label>
                                            <input type="tel" name="mother_contact" class="form-control" placeholder="09XXXXXXXXX">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check mt-4">
                                                <input type="radio" name="emergency_contact" value="mother" id="mother_emergency_contact" class="form-check-input">
                                                <label for="mother_emergency_contact" class="form-check-label">
                                                    <i class="fas fa-phone-alt text-danger me-1"></i>Select as Emergency Contact
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Legal Guardian Information -->
                                <div class="mb-4" id="guardian_section">
                                    <h6 class="text-primary mb-3">Guardian's Information</h6>
                                    <div class="row mb-3">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Last Name <span class="required">*</span></label>
                                            <input type="text" name="guardian_last_name" class="form-control">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">First Name <span class="required">*</span></label>
                                            <input type="text" name="guardian_first_name" class="form-control">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Middle Name</label>
                                            <input type="text" name="guardian_middle_name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row mb-3">

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Contact Number <span class="required">*</span></label>
                                            <input type="tel" name="guardian_contact" class="form-control" placeholder="09XXXXXXXXX">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-check mt-4">
                                                <input type="radio" name="emergency_contact" value="guardian" id="guardian_emergency_contact" class="form-check-input">
                                                <label for="guardian_emergency_contact" class="form-check-label">
                                                    <i class="fas fa-phone-alt text-danger me-1"></i>Select as Emergency Contact
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Parent/Guardian Address Information -->
                                <div class="mb-4">
                                    <h6 class="text-primary mb-3">Parent/Guardian Address Information</h6>
                                    
                                    <!-- Father's Address -->
                                    <div class="mb-4">
                                        <h6 class="text-secondary mb-3">Father's Address</h6>
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <div class="form-check">
                                                    <input type="checkbox" name="father_same_address" id="father_same_address" class="form-check-input same-address-checkbox" data-target="father">
                                                    <label for="father_same_address" class="form-check-label">Same as Student's Current Address</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="father_address_fields" class="address-fields">
                                            <div class="row mb-3">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">House No.</label>
                                                    <input type="text" name="father_house_no" class="form-control address-field" data-parent="father">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Street</label>
                                                    <input type="text" name="father_street" class="form-control address-field" data-parent="father">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Barangay</label>
                                                    <input type="text" name="father_barangay" class="form-control address-field" data-parent="father">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Municipality/City</label>
                                                    <input type="text" name="father_municipality" class="form-control address-field" data-parent="father">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Province</label>
                                                    <input type="text" name="father_province" class="form-control address-field" data-parent="father">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Country</label>
                                                    <input type="text" name="father_country" class="form-control address-field" value="Philippines" data-parent="father">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Zip Code</label>
                                                    <input type="text" name="father_zip_code" class="form-control address-field" data-parent="father">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mother's Address -->
                                    <div class="mb-4">
                                        <h6 class="text-secondary mb-3">Mother's Address</h6>
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <div class="form-check">
                                                    <input type="checkbox" name="mother_same_address" id="mother_same_address" class="form-check-input same-address-checkbox" data-target="mother">
                                                    <label for="mother_same_address" class="form-check-label">Same as Student's Current Address</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="mother_address_fields" class="address-fields">
                                            <div class="row mb-3">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">House No.</label>
                                                    <input type="text" name="mother_house_no" class="form-control address-field" data-parent="mother">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Street</label>
                                                    <input type="text" name="mother_street" class="form-control address-field" data-parent="mother">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Barangay</label>
                                                    <input type="text" name="mother_barangay" class="form-control address-field" data-parent="mother">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Municipality/City</label>
                                                    <input type="text" name="mother_municipality" class="form-control address-field" data-parent="mother">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Province</label>
                                                    <input type="text" name="mother_province" class="form-control address-field" data-parent="mother">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Country</label>
                                                    <input type="text" name="mother_country" class="form-control address-field" value="Philippines" data-parent="mother">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Zip Code</label>
                                                    <input type="text" name="mother_zip_code" class="form-control address-field" data-parent="mother">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Guardian Address Section -->
                                    <div class="mt-4">
                                        <h6 class="text-secondary mb-3">Guardian's Address</h6>
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <div class="form-check">
                                                    <input type="checkbox" name="guardian_same_address" id="guardian_same_address" class="form-check-input same-address-checkbox" data-target="guardian">
                                                    <label for="guardian_same_address" class="form-check-label">Same as Student's Current Address</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="guardian_address_fields" class="address-fields">
                                            <div class="row mb-3">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">House No. <span class="required">*</span></label>
                                                    <input type="text" name="guardian_house_no" class="form-control address-field" data-parent="guardian">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Street <span class="required">*</span></label>
                                                    <input type="text" name="guardian_street" class="form-control address-field" data-parent="guardian">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Barangay <span class="required">*</span></label>
                                                    <input type="text" name="guardian_barangay" class="form-control address-field" data-parent="guardian">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Municipality/City <span class="required">*</span></label>
                                                    <input type="text" name="guardian_municipality" class="form-control address-field" data-parent="guardian">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Province <span class="required">*</span></label>
                                                    <input type="text" name="guardian_province" class="form-control address-field" data-parent="guardian">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Country</label>
                                                    <input type="text" name="guardian_country" class="form-control address-field" value="Philippines" data-parent="guardian">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Zip Code</label>
                                                    <input type="text" name="guardian_zip_code" class="form-control address-field" data-parent="guardian">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Step 3: Academic & Special Needs -->
                            <div id="step-3" class="form-wizard-content">
                                <!-- Special Needs Assessment -->
                                <h6 class="text-primary section-header mb-3">Special Needs Assessment</h6>
                                <div class="disability-section">
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <label class="form-label">Is the child a Learner with Disability?</label>
                                            <div class="checkbox-group mb-3">
                                                <div class="checkbox-item">
                                                    <input type="radio" name="has_disability" value="Yes" id="disability_yes">
                                                    <label for="disability_yes">Yes</label>
                                                </div>
                                                <div class="checkbox-item">
                                                    <input type="radio" name="has_disability" value="No" id="disability_no">
                                                    <label for="disability_no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="disability_details" style="display: none;">
                                        <label class="form-label mb-3">If Yes, specify the type of disability:</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="checkbox-group">
                                                    <div class="checkbox-item">
                                                        <input type="checkbox" name="disability_types[]" value="Visual Impairment" id="visual">
                                                        <label for="visual">Visual Impairment</label>
                                                    </div>
                                                    <div class="checkbox-item">
                                                        <input type="checkbox" name="disability_types[]" value="Hearing Impairment" id="hearing">
                                                        <label for="hearing">Hearing Impairment</label>
                                                    </div>
                                                    <div class="checkbox-item">
                                                        <input type="checkbox" name="disability_types[]" value="Learning Disability" id="learning">
                                                        <label for="learning">Learning Disability</label>
                                                    </div>
                                                    <div class="checkbox-item">
                                                        <input type="checkbox" name="disability_types[]" value="Intellectual Disability" id="intellectual">
                                                        <label for="intellectual">Intellectual Disability</label>
                                                    </div>
                                                    <div class="checkbox-item">
                                                        <input type="checkbox" name="disability_types[]" value="Blind" id="blind">
                                                        <label for="blind">Blind</label>
                                                    </div>
                                                    <div class="checkbox-item">
                                                        <input type="checkbox" name="disability_types[]" value="Autism Spectrum Disorder" id="autism">
                                                        <label for="autism">Autism Spectrum Disorder</label>
                                                    </div>
                                                    <div class="checkbox-item">
                                                        <input type="checkbox" name="disability_types[]" value="Emotional-Behavioral Disorder" id="emotional">
                                                        <label for="emotional">Emotional-Behavioral Disorder</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="checkbox-group">
                                                    <div class="checkbox-item">
                                                        <input type="checkbox" name="disability_types[]" value="Orthopedic/Physical Handicap" id="orthopedic">
                                                        <label for="orthopedic">Orthopedic/Physical Handicap</label>
                                                    </div>
                                                    <div class="checkbox-item">
                                                        <input type="checkbox" name="disability_types[]" value="Multiple Disorder" id="multiple">
                                                        <label for="multiple">Multiple Disorder</label>
                                                    </div>
                                                    <div class="checkbox-item">
                                                        <input type="checkbox" name="disability_types[]" value="Speech/Language Disorder" id="speech">
                                                        <label for="speech">Speech/Language Disorder</label>
                                                    </div>
                                                    <div class="checkbox-item">
                                                        <input type="checkbox" name="disability_types[]" value="Cerebral Palsy" id="cerebral">
                                                        <label for="cerebral">Cerebral Palsy</label>
                                                    </div>
                                                    <div class="checkbox-item">
                                                        <input type="checkbox" name="disability_types[]" value="Special Health Problem/Chronic Disease" id="chronic">
                                                        <label for="chronic">Special Health Problem/Chronic Disease</label>
                                                    </div>
                                                    <div class="checkbox-item">
                                                        <input type="checkbox" name="disability_types[]" value="Cancer" id="cancer">
                                                        <label for="cancer">Cancer</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Academic Performance Section -->
                                <h6 class="text-primary section-header mb-3">Academic Performance</h6>
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Previous General Weighted Average (GWA) <span class="text-muted">(Temporarily Optional)</span></label>
                                        <input type="number" name="previous_gwa" class="form-control" step="0.01" min="65.00" max="100.00" placeholder="e.g., 85.50">
                                        <small class="form-text text-muted">Enter GWA from previous grade level (65.00 - 100.00). Used for heterogeneous class grouping per DepEd policy.</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Academic Performance Level <span class="text-muted">(Auto-calculated)</span></label>
                                        <input type="text" name="performance_level" class="form-control" readonly placeholder="Will be determined based on GWA">
                                        <small class="form-text text-muted">Performance level will be automatically determined for class assignment purposes.</small>
                                    </div>
                                </div>

                                <!-- For Returning Learner Section -->
                                <div id="returning-transfer-section" style="display: none;">
                                    <h6 class="text-primary section-header mb-3">For Returning Learning (Balik-Aral) and Transfer Students</h6>
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Last Grade Level Completed</label>
                                            <input type="text" name="last_grade_completed" class="form-control">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Last School Year Completed</label>
                                            <input type="text" name="last_school_year" class="form-control">
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-8 mb-3">
                                            <label class="form-label">Last School Attended</label>
                                            <input type="text" name="last_school_attended" class="form-control">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">School ID</label>
                                            <div class="lrn-boxes">
                                                <?php for ($i = 0; $i < 7; $i++): ?>
                                                    <input type="text" name="school_id_digit_<?= $i ?>" class="lrn-box" maxlength="1">
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- For Senior High School -->
                                <div id="shs-section" style="display: none;">
                                    <h6 class="text-primary section-header md-5">For Senior High School Students</h6>
                                    <div class="row mb-4">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Semester</label>
                                            <div class="checkbox-group">
                                                <div class="checkbox-item">
                                                    <input type="radio" name="semester" value="1st" id="sem1">
                                                    <label for="sem1">1st Semester</label>
                                                </div>
                                                <div class="checkbox-item">
                                                    <input type="radio" name="semester" value="2nd" id="sem2">
                                                    <label for="sem2">2nd Semester</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Track</label>
                                            <input type="text" name="track" class="form-control" placeholder="e.g., Academic, TVL, Sports, Arts">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Strand</label>
                                            <input type="text" name="strand" class="form-control" placeholder="e.g., STEM, ABM, HUMSS">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 4: Document Upload -->
                            <div id="step-4" class="form-wizard-content">
                                <h3 class="mb-4">Upload Required Documents</h3>
                                <p class="text-muted mb-4">Please upload clear images or scans of each required document separately. Each document should be uploaded in its designated area below.</p>

                                <!-- Document Upload Tabs -->
                                <div class="col-lg-12 col-md-12 col-sm-12 mb-30">
                                    <div class="pd-20 card-box">
                                        <h5 class="h4 text-blue mb-20">Document Categories</h5>
                                        <div class="tab">
                                            <ul class="nav nav-tabs customtab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-bs-toggle="tab" href="#personal-docs" role="tab" aria-selected="true">Personal Documents</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-bs-toggle="tab" href="#academic-records" role="tab" aria-selected="false">Academic Records</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-bs-toggle="tab" href="#additional-docs" role="tab" aria-selected="false">Additional Documents</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <!-- Personal Documents Tab -->
                                                <div class="tab-pane fade show active" id="personal-docs" role="tabpanel">
                                                    <div class="pd-20">
                                                        <div class="row">
                                                            <!-- Birth Certificate Upload -->
                                                            <div class="col-md-6 mb-4">
                                                                <div class="document-upload-section">
                                                                    <h6 class="mb-3"><i class="fas fa-certificate text-primary mr-2"></i>Birth Certificate (PSA) <span class="text-danger">*</span></h6>
                                                                    <div class="upload-area" id="birthCertUploadArea" data-doc-type="birth_certificate">
                                                                        <div class="upload-icon">
                                                                            <i class="fas fa-cloud-upload-alt"></i>
                                                                        </div>
                                                                        <div class="upload-text">Upload Birth Certificate</div>
                                                                        <div class="upload-hint">Click to browse or drag & drop (JPG, PNG, PDF)</div>
                                                                        <input type="file" id="birthCertInput" accept="image/*,.pdf" style="display: none;">
                                                                    </div>
                                                                    <div class="document-preview" id="birthCertPreview"></div>
                                                                </div>
                                                            </div>
                                                            <!-- Parent/Guardian ID Upload -->
                                                            <div class="col-md-6 mb-4">
                                                                <div class="document-upload-section">
                                                                    <h6 class="mb-3"><i class="fas fa-id-card text-info mr-2"></i>Parent/Guardian ID <span class="text-danger">*</span></h6>
                                                                    <div class="upload-area" id="parentIdUploadArea" data-doc-type="parent_id">
                                                                        <div class="upload-icon">
                                                                            <i class="fas fa-cloud-upload-alt"></i>
                                                                        </div>
                                                                        <div class="upload-text">Upload Parent/Guardian ID</div>
                                                                        <div class="upload-hint">Click to browse or drag & drop (JPG, PNG, PDF)</div>
                                                                        <input type="file" id="parentIdInput" accept="image/*,.pdf" style="display: none;">
                                                                    </div>
                                                                    <div class="document-preview" id="parentIdPreview"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Academic Records Tab -->
                                                <div class="tab-pane fade" id="academic-records" role="tabpanel">
                                                    <div class="pd-20">
                                                        <div class="row">
                                                            <!-- Report Card Upload -->
                                                            <div class="col-md-12 mb-4">
                                                                <div class="document-upload-section">
                                                                    <h6 class="mb-3"><i class="fas fa-file-alt text-success mr-2"></i>Report Card <span class="text-muted">(if transferring)</span></h6>
                                                                    <div class="upload-area" id="reportCardUploadArea" data-doc-type="report_card">
                                                                        <div class="upload-icon">
                                                                            <i class="fas fa-cloud-upload-alt"></i>
                                                                        </div>
                                                                        <div class="upload-text">Upload Report Card</div>
                                                                        <div class="upload-hint">Click to browse or drag & drop (JPG, PNG, PDF)</div>
                                                                        <input type="file" id="reportCardInput" accept="image/*,.pdf" style="display: none;">
                                                                    </div>
                                                                    <div class="document-preview" id="reportCardPreview"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Additional Documents Tab -->
                                                <div class="tab-pane fade" id="additional-docs" role="tabpanel">
                                                    <div class="pd-20">
                                                        <div class="row">
                                                            <!-- Good Moral Certificate Upload -->
                                                            <div class="col-md-12 mb-4">
                                                                <div class="document-upload-section">
                                                                    <h6 class="mb-3"><i class="fas fa-award text-warning mr-2"></i>Good Moral Certificate <span class="text-muted">(if transferring)</span></h6>
                                                                    <div class="upload-area" id="goodMoralUploadArea" data-doc-type="good_moral">
                                                                        <div class="upload-icon">
                                                                            <i class="fas fa-cloud-upload-alt"></i>
                                                                        </div>
                                                                        <div class="upload-text">Upload Good Moral Certificate</div>
                                                                        <div class="upload-hint">Click to browse or drag & drop (JPG, PNG, PDF)</div>
                                                                        <input type="file" id="goodMoralInput" accept="image/*,.pdf" style="display: none;">
                                                                    </div>
                                                                    <div class="document-preview" id="goodMoralPreview"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Upload Summary -->
                                <div class="mt-4">
                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-info-circle mr-2"></i>Upload Summary</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <ul class="list-unstyled mb-0">
                                                    <li id="birthCertStatus"><i class="fas fa-square text-muted mr-2"></i> Birth Certificate (PSA)</li>
                                                    <li id="reportCardStatus"><i class="fas fa-square text-muted mr-2"></i> Report Card</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <ul class="list-unstyled mb-0">
                                                    <li id="goodMoralStatus"><i class="fas fa-square text-muted mr-2"></i> Good Moral Certificate</li>
                                                    <li id="parentIdStatus"><i class="fas fa-square text-muted mr-2"></i> Parent/Guardian ID</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 5: Review & Submit -->
                            <div id="step-5" class="form-wizard-content">
                                <div class="section-header">Review Your Information</div>

                                <div class="alert alert-warning mb-4">
                                    <h6><i class="fas fa-exclamation-triangle"></i> Please Review Carefully</h6>
                                    <p class="mb-0">Please review all information below before submitting. Make sure all details are correct as this will be used for official school records.</p>
                                </div>

                                <!-- Profile Picture Review - Top Center -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 text-dark">Profile Picture</h6>
                                                <a href="#" class="text-primary text-decoration-none" onclick="goToStep(1)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                                            </div>
                                            <div class="card-body text-center">
                                                <div id="review-profile-picture-container">
                                                    <div id="review-profile-placeholder" class="text-muted">
                                                        <i class="fas fa-user-circle fa-4x mb-3" style="color: #dee2e6;"></i>
                                                        <p class="mb-0">No profile picture uploaded</p>
                                                    </div>
                                                    <div id="review-profile-preview-container" style="display: none;">
                                                        <img id="review-profile-preview" src="" alt="Profile Picture" class="img-fluid rounded-circle shadow" style="width: 150px; height: 150px; object-fit: cover;">
                                                        <p class="mt-3 mb-0 text-success"><i class="fas fa-check-circle"></i> Profile picture uploaded successfully</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Review Summary Cards -->
                                <div class="row mb-4">
                                    <!-- Student Personal Information -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 text-dark">Student Personal Information</h6>
                                                <a href="#" class="text-primary text-decoration-none" onclick="goToStep(1)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>Full Name:</strong> <span id="review-student-name">-</span></p>
                                                <p><strong>Extension Name:</strong> <span id="review-extension-name">-</span></p>
                                                <p><strong>Birth Date:</strong> <span id="review-birth-date">-</span></p>
                                                <p><strong>Place of Birth:</strong> <span id="review-place-of-birth">-</span></p>
                                                <p><strong>Age:</strong> <span id="review-age">-</span></p>
                                                <p><strong>Gender:</strong> <span id="review-gender">-</span></p>
                                                <p><strong>Birth Certificate No.:</strong> <span id="review-birth-cert-no">-</span></p>
                                                <p><strong>Mother Tongue:</strong> <span id="review-mother-tongue">-</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Enrollment Information -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 text-dark">Enrollment Information</h6>
                                                <a href="#" class="text-primary text-decoration-none" onclick="goToStep(1)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>LRN:</strong> <span id="review-lrn">-</span></p>
                                                <p><strong>Grade Level:</strong> <span id="review-grade-level">-</span></p>
                                                <p><strong>School Year:</strong> <span id="review-school-year">-</span></p>
                                                <p><strong>Enrollment Type:</strong> <span id="review-enrollment-type">-</span></p>
                                                <p><strong>Previous GWA:</strong> <span id="review-previous-gwa">-</span></p>
                                                <p><strong>Performance Level:</strong> <span id="review-performance-level">-</span></p>
                                                <p><strong>Student Email:</strong> <span id="review-student-email">-</span></p>
                                                <p><strong>Student Contact:</strong> <span id="review-student-contact">-</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Address Information -->
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 text-dark">Current Address</h6>
                                                <a href="#" class="text-primary text-decoration-none" onclick="goToStep(2)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>House No.:</strong> <span id="review-current-house-no">-</span></p>
                                                <p><strong>Street:</strong> <span id="review-current-street">-</span></p>
                                                <p><strong>Barangay:</strong> <span id="review-current-barangay">-</span></p>
                                                <p><strong>Municipality/City:</strong> <span id="review-current-municipality">-</span></p>
                                                <p><strong>Province:</strong> <span id="review-current-province">-</span></p>
                                                <p><strong>Country:</strong> <span id="review-current-country">-</span></p>
                                                <p><strong>Zip Code:</strong> <span id="review-current-zip">-</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 text-dark">Permanent Address</h6>
                                                <a href="#" class="text-primary text-decoration-none" onclick="goToStep(2)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>House No./Street:</strong> <span id="review-permanent-house-street">-</span></p>
                                                <p><strong>Street Name:</strong> <span id="review-permanent-street-name">-</span></p>
                                                <p><strong>Barangay:</strong> <span id="review-permanent-barangay">-</span></p>
                                                <p><strong>Municipality/City:</strong> <span id="review-permanent-municipality">-</span></p>
                                                <p><strong>Province:</strong> <span id="review-permanent-province">-</span></p>
                                                <p><strong>Country:</strong> <span id="review-permanent-country">-</span></p>
                                                <p><strong>Zip Code:</strong> <span id="review-permanent-zip">-</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Parent/Guardian Information -->
                                <div class="row mb-4">
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 text-dark">Father's Information</h6>
                                                <a href="#" class="text-primary text-decoration-none" onclick="goToStep(2)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>Name:</strong> <span id="review-father-name">-</span></p>
                                                <p><strong>Contact:</strong> <span id="review-father-contact">-</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 text-dark">Mother's Information</h6>
                                                <a href="#" class="text-primary text-decoration-none" onclick="goToStep(2)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>Name:</strong> <span id="review-mother-name">-</span></p>
                                                <p><strong>Contact:</strong> <span id="review-mother-contact">-</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 text-dark">Guardian's Information</h6>
                                                <a href="#" class="text-primary text-decoration-none" onclick="goToStep(2)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>Name:</strong> <span id="review-guardian-name">-</span></p>
                                                <p><strong>Contact:</strong> <span id="review-guardian-contact">-</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Additional Information -->
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 text-dark">Special Programs & Benefits</h6>
                                                <a href="#" class="text-primary text-decoration-none" onclick="goToStep(1)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>Indigenous Peoples:</strong> <span id="review-indigenous-people">-</span></p>
                                                <p><strong>IP Community:</strong> <span id="review-indigenous-community">-</span></p>
                                                <p><strong>4Ps Beneficiary:</strong> <span id="review-fourps-beneficiary">-</span></p>
                                                <p><strong>4Ps Household ID:</strong> <span id="review-fourps-household-id">-</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 text-dark">Documents Uploaded</h6>
                                                <a href="#" class="text-primary text-decoration-none" onclick="goToStep(4)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>Total Documents:</strong> <span id="review-document-count">0</span></p>
                                                <p><strong>Upload Status:</strong> <span id="review-upload-status">No documents uploaded</span></p>
                                                <div id="review-document-list"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Certification -->
                                <div class="section-header">Certification & Agreement</div>
                                <div style="border: 1px solid #dee2e6; padding: 20px; margin: 20px 0; border-radius: 8px; background-color: #f8f9fa;">
                                    <p style="text-align: justify; margin-bottom: 20px; line-height: 1.6;">
                                        I hereby certify that the above information given are true and correct to the best of my knowledge and I allow the
                                        Department of Education to use my child's details to create and/or update his/her learner profile in the Learner Information System.
                                        The information herein shall be treated as confidential in compliance with the Data Privacy Act of 2012.
                                    </p>

                                    <!-- Agreement Checkbox -->
                                    <div class="form-check mb-4">
                                        <input type="checkbox" class="form-check-input" id="certificationAgreement" name="certification_agreement" required style="border: 2px solid #000; outline: 2px solid #000;">
                                        <label class="form-check-label" for="certificationAgreement">
                                            <strong>I agree to the certification and data privacy terms stated above</strong> <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                        <div class="signature-box">
                                            <div class="signature-line"></div>
                                            <label>Date</label>
                                        </div>
                                    </div>
                                </div>



                                <!-- Additional Actions -->
                                <div class="text-center mt-4 no-print">
                                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="window.print()">
                                            <i class="fas fa-print me-2"></i> Print Form
                                        </button>
                                        <button type="button" class="btn btn-outline-success btn-sm" onclick="downloadPDF()">
                                            <i class="fas fa-file-pdf me-2"></i> Download PDF
                                        </button>
                                        <button type="reset" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-undo me-2"></i> Reset Form
                                        </button>
                                        <a href="<?= base_url('') ?>" class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-home me-2"></i> Back to Home
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Navigation -->
                            <div class="wizard-buttons">
                                <a href="<?= base_url('enrollment') ?>" class="wizard-btn wizard-btn-cancel">
                                    <i class="fas fa-times mr-2"></i> Cancel
                                </a>
                                <button type="button" class="wizard-btn wizard-btn-prev" id="prevBtn" style="display: none;">
                                    <i class="fas fa-arrow-left mr-2"></i> Previous
                                </button>
                                <button type="button" class="wizard-btn wizard-btn-next" id="nextBtn">
                                    Next<i class="fas fa-arrow-right ml-2"></i>
                                </button>
                                <button type="submit" class="wizard-btn wizard-btn-submit" id="submitBtn" style="display: none;">
                                    <i class="fas fa-paper-plane mr-2"></i> Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <!-- Cropper.js JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script>
        // Global variables
        let currentStep = 1;
        const totalSteps = 5;
        let uploadedDocuments = [];
        let cropper = null; // Global cropper instance

        // Function to navigate to a specific step
        function goToStep(stepNumber) {
            stepNumber = parseInt(stepNumber, 10);

            if (stepNumber < 1 || stepNumber > totalSteps) {
                return;
            }

            // Update current step
            currentStep = stepNumber;

            // Update step indicators
            document.querySelectorAll('.wizard-step').forEach((step, index) => {
                const stepNum = index + 1;
                step.classList.remove('active', 'completed');

                if (stepNum === currentStep) {
                    step.classList.add('active');
                } else if (stepNum < currentStep) {
                    step.classList.add('completed');
                }
            });

            // Update content visibility
            document.querySelectorAll('.form-wizard-content').forEach((content, index) => {
                content.classList.remove('active');
                if (index + 1 === currentStep) {
                    content.classList.add('active');
                }
            });

            // Update navigation buttons
            updateNavigationButtons();

            // Populate review data when navigating to step 5
            if (stepNumber === 5) {
                populateReviewData();
            }
        }

        // Function to update navigation buttons
        function updateNavigationButtons() {
            // Hide all buttons first
            document.querySelectorAll('.wizard-btn-cancel, .wizard-btn-prev, .wizard-btn-next, .wizard-btn-submit').forEach(btn => {
                btn.style.display = 'none';
            });

            // Step 1: Show Cancel + Next
            if (currentStep === 1) {
                document.querySelectorAll('.wizard-btn-cancel').forEach(btn => {
                    btn.style.display = 'inline-block';
                });
                document.querySelectorAll('.wizard-btn-next').forEach(btn => {
                    btn.style.display = 'inline-block';
                });
            }
            // Intermediate steps (2 to totalSteps-1): Show Previous + Next
            else if (currentStep > 1 && currentStep < totalSteps) {
                document.querySelectorAll('.wizard-btn-prev').forEach(btn => {
                    btn.style.display = 'inline-block';
                });
                document.querySelectorAll('.wizard-btn-next').forEach(btn => {
                    btn.style.display = 'inline-block';
                });
            }
            // Final step: Show Previous + Submit
            else if (currentStep === totalSteps) {
                document.querySelectorAll('.wizard-btn-prev').forEach(btn => {
                    btn.style.display = 'inline-block';
                });
                document.querySelectorAll('.wizard-btn-submit').forEach(btn => {
                    btn.style.display = 'inline-block';
                });
            }
        }

        // Function to show/hide returning/transfer section
        function toggleReturningTransferSection() {
            const returningRadio = document.getElementById('returning_learner');
            const transferRadio = document.getElementById('transfer_learner');
            const section = document.getElementById('returning-transfer-section');
            
            if (returningRadio && transferRadio && section) {
                if (returningRadio.checked || transferRadio.checked) {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            }
        }

        // Function to show/hide SHS section based on grade level
        function toggleSHSSection() {
            const gradeSelect = document.querySelector('select[name="grade_level"]');
            const shsSection = document.getElementById('shs-section');
            
            if (gradeSelect && shsSection) {
                const selectedGrade = gradeSelect.value;
                if (selectedGrade === 'Grade 11' || selectedGrade === 'Grade 12') {
                    shsSection.style.display = 'block';
                } else {
                    shsSection.style.display = 'none';
                }
            }
        }

        // When document is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize wizard
            goToStep(1);
            
            // Initialize profile picture upload
            initializeProfileUpload();

            // Handle enrollment type change
            document.querySelectorAll('input[name="enrollment_type"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    console.log('Enrollment type changed to:', this.value);
                    toggleReturningTransferSection();
                });
            });

            // Handle grade level change
            const gradeSelect = document.querySelector('select[name="grade_level"]');
            if (gradeSelect) {
                gradeSelect.addEventListener('change', function() {
                    console.log('Grade level changed to:', this.value);
                    toggleSHSSection();
                });
            }

            // Next button click handler
            document.querySelectorAll('.wizard-btn-next').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (validateCurrentStep()) {
                        goToStep(currentStep + 1);
                    }
                });
            });

            // Previous button click handler
            document.querySelectorAll('.wizard-btn-prev').forEach(btn => {
                btn.addEventListener('click', function() {
                    goToStep(currentStep - 1);
                });
            });

            // Step indicator click handler
            document.querySelectorAll('.wizard-step').forEach(step => {
                step.addEventListener('click', function() {
                    const stepNumber = parseInt(this.getAttribute('data-step'));
                    goToStep(stepNumber);
                });
            });

            // LRN input handling
            setupLRNInputs();

            // Conditional field displays
            setupConditionalFields();

            // Address copying
            setupAddressCopy();

            // Auto-calculate age
            setupAgeCalculation();

            // GWA calculation and validation
            setupGWACalculation();

            // File upload functionality
            setupFileUpload();

            // Profile picture preview
            setupProfilePicturePreview();

            // Contact number formatting
            setupContactFormatting();

            // Form submission
            setupFormSubmission();
        });

        // Function to validate current step - TEMPORARILY DISABLED FOR TESTING
        function validateCurrentStep() {
            // Validation temporarily disabled
            return true;

            /*
            const currentStepElement = document.getElementById(`step-${currentStep}`);
            const requiredFields = currentStepElement.querySelectorAll('[required]');
            let isValid = true;
            let firstInvalidField = null;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    if (!firstInvalidField) firstInvalidField = field;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            // Special validation for LRN
            if (currentStep === 1) {
                const lrnInputs = document.querySelectorAll('[name^="lrn_digit_"]');
                let lrnComplete = true;
                lrnInputs.forEach(input => {
                    if (!input.value) {
                        lrnComplete = false;
                        input.style.borderColor = '#dc3545';
                    } else {
                        input.style.borderColor = '';
                    }
                });
                
                if (!lrnComplete) {
                    isValid = false;
                    alert('Please complete the Learner Reference Number (LRN).');
                    return false;
                }
            }
            
            if (!isValid) {
                alert('Please fill in all required fields before proceeding.');
                if (firstInvalidField) {
                    firstInvalidField.focus();
                }
            }
            
            return isValid;
            */
        }
        // Duplicate function removed - using the main goToStep function above

        // Validate current step - TEMPORARILY DISABLED FOR TESTING
        function validateCurrentStep() {
            // Validation temporarily disabled
            return true;

            /*
            const currentStepElement = document.getElementById(`step-${currentStep}`);
            const requiredFields = currentStepElement.querySelectorAll('[required]');
            let isValid = true;
            let firstInvalidField = null;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    if (!firstInvalidField) firstInvalidField = field;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            // Special validation for LRN
            if (currentStep === 1) {
                const lrnInputs = document.querySelectorAll('[name^="lrn_digit_"]');
                let lrnComplete = true;
                lrnInputs.forEach(input => {
                    if (!input.value) {
                        lrnComplete = false;
                        input.style.borderColor = '#dc3545';
                    } else {
                        input.style.borderColor = '';
                    }
                });
                
                if (!lrnComplete) {
                    isValid = false;
                    alert('Please complete the Learner Reference Number (LRN).');
                    return false;
                }
            }
            
            if (!isValid) {
                alert('Please fill in all required fields before proceeding.');
                if (firstInvalidField) {
                    firstInvalidField.focus();
                }
            }
            
            return isValid;
            */
        }

        // Validate steps up to a certain point
        function validateStepsUpTo(stepNumber) {
            for (let i = 1; i <= stepNumber; i++) {
                const stepElement = document.getElementById(`step-${i}`);
                const requiredFields = stepElement.querySelectorAll('[required]');

                for (let field of requiredFields) {
                    if (!field.value.trim()) {
                        return false;
                    }
                }
            }
            return true;
        }

        // Setup LRN inputs
        function setupLRNInputs() {
            const lrnInputs = document.querySelectorAll('[name^="lrn_digit_"]');
            lrnInputs.forEach((input, index) => {
                input.addEventListener('input', function() {
                    if (this.value.length === 1 && index < lrnInputs.length - 1) {
                        lrnInputs[index + 1].focus();
                    }
                });

                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && this.value === '' && index > 0) {
                        lrnInputs[index - 1].focus();
                    }
                });
            });
        }

        // Setup conditional field displays
        function setupConditionalFields() {
            // Indigenous People field
            document.querySelectorAll('[name="indigenous_people"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const field = document.getElementById('ip_community_field');
                    field.style.display = this.value === 'Yes' ? 'block' : 'none';
                });
            });

            // 4Ps field
            document.querySelectorAll('[name="fourps_beneficiary"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const field = document.getElementById('fourps_id_field');
                    field.style.display = this.value === 'Yes' ? 'block' : 'none';
                });
            });

            // Disability details
            document.querySelectorAll('[name="has_disability"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const details = document.getElementById('disability_details');
                    details.style.display = this.value === 'Yes' ? 'block' : 'none';
                });
            });
        }

        // Setup address copying
        function setupAddressCopy() {
            const checkbox = document.getElementById('same_address');
            if (checkbox) {
                checkbox.addEventListener('change', function() {
                    const permanentFields = document.getElementById('permanent_address_fields');

                    if (this.checked) {
                        // Copy current address values
                        const currentHouse = document.querySelector('[name="current_house_no"]').value;
                        const currentStreet = document.querySelector('[name="current_street"]').value;

                        document.querySelector('[name="permanent_house_street"]').value = currentHouse + ' ' + currentStreet;
                        document.querySelector('[name="permanent_barangay"]').value = document.querySelector('[name="current_barangay"]').value;
                        document.querySelector('[name="permanent_municipality"]').value = document.querySelector('[name="current_municipality"]').value;
                        document.querySelector('[name="permanent_province"]').value = document.querySelector('[name="current_province"]').value;
                        document.querySelector('[name="permanent_country"]').value = document.querySelector('[name="current_country"]').value;
                        document.querySelector('[name="permanent_zip_code"]').value = document.querySelector('[name="current_zip_code"]').value;

                        permanentFields.style.display = 'none';
                    } else {
                        permanentFields.style.display = 'block';
                    }
                });
            }

            // Setup parent/guardian address copying
            setupParentAddressCopy();
        }

        // Setup parent/guardian address copying functionality
        function setupParentAddressCopy() {
            const parentTypes = ['father', 'mother', 'guardian'];
            
            parentTypes.forEach(parentType => {
                const checkbox = document.getElementById(`${parentType}_same_address`);
                if (checkbox) {
                    checkbox.addEventListener('change', function() {
                        const addressFields = document.getElementById(`${parentType}_address_fields`);
                        
                        if (this.checked) {
                            copyStudentAddressToParent(parentType);
                            // Hide the address fields when "Same as Student's Current Address" is checked
                            if (addressFields) {
                                addressFields.style.display = 'none';
                            }
                        } else {
                            clearParentAddress(parentType);
                            // Show the address fields when checkbox is unchecked
                            if (addressFields) {
                                addressFields.style.display = 'block';
                            }
                        }
                    });
                }
            });
        }

        // Copy student's current address to parent/guardian
        function copyStudentAddressToParent(parentType) {
            const studentFields = {
                house_no: document.querySelector('[name="current_house_no"]')?.value || '',
                street: document.querySelector('[name="current_street"]')?.value || '',
                barangay: document.querySelector('[name="current_barangay"]')?.value || '',
                municipality: document.querySelector('[name="current_municipality"]')?.value || '',
                province: document.querySelector('[name="current_province"]')?.value || '',
                country: document.querySelector('[name="current_country"]')?.value || '',
                zip_code: document.querySelector('[name="current_zip_code"]')?.value || ''
            };

            // Copy values to parent fields
            Object.keys(studentFields).forEach(field => {
                const parentField = document.querySelector(`[name="${parentType}_${field}"]`);
                if (parentField) {
                    parentField.value = studentFields[field];
                }
            });
        }

        // Clear parent/guardian address fields
        function clearParentAddress(parentType) {
            const fields = ['house_no', 'street', 'barangay', 'municipality', 'province', 'country', 'zip_code'];
            
            fields.forEach(field => {
                const parentField = document.querySelector(`[name="${parentType}_${field}"]`);
                if (parentField && field !== 'country') { // Keep Philippines as default for country
                    parentField.value = '';
                }
            });
        }

        // File Upload Setup
        function setupFileUpload() {
            // Setup individual upload areas
            const uploadAreas = [
                { area: 'birthCertUploadArea', input: 'birthCertInput', type: 'birth_certificate' },
                { area: 'reportCardUploadArea', input: 'reportCardInput', type: 'report_card' },
                { area: 'goodMoralUploadArea', input: 'goodMoralInput', type: 'good_moral' },
                { area: 'parentIdUploadArea', input: 'parentIdInput', type: 'parent_id' }
            ];

            uploadAreas.forEach(config => {
                const uploadArea = document.getElementById(config.area);
                const fileInput = document.getElementById(config.input);

                if (uploadArea && fileInput) {
                    // Click to upload
                    uploadArea.addEventListener('click', () => fileInput.click());

                    // Drag and drop events
                    uploadArea.addEventListener('dragover', (e) => {
                        e.preventDefault();
                        uploadArea.classList.add('dragover');
                    });

                    uploadArea.addEventListener('dragleave', () => {
                        uploadArea.classList.remove('dragover');
                    });

                    uploadArea.addEventListener('drop', (e) => {
                        e.preventDefault();
                        uploadArea.classList.remove('dragover');
                        handleSingleFile(e.dataTransfer.files[0], config.type);
                    });

                    // File input change
                    fileInput.addEventListener('change', (e) => {
                        if (e.target.files[0]) {
                            handleSingleFile(e.target.files[0], config.type);
                        }
                    });
                }
            });
        }

        // Handle Single File Upload
        function handleSingleFile(file, docType) {
            if (!file) return;
            
            if (file.type.startsWith('image/') || file.type === 'application/pdf') {
                // Remove existing document of this type
                uploadedDocuments = uploadedDocuments.filter(doc => doc.type !== docType);
                
                const documentId = Date.now() + Math.random();
                const document = {
                    id: documentId,
                    file: file,
                    name: file.name,
                    type: docType,
                    status: 'processing'
                };

                uploadedDocuments.push(document);
                displaySingleDocument(document);
                processSingleDocument(document);
            } else {
                alert('Please upload only image files (JPG, PNG) or PDF files.');
            }
        }

        // Display Single Document Preview
        function displaySingleDocument(docObj) {
            const previewId = getPreviewId(docObj.type);
            const preview = document.getElementById(previewId);
            if (!preview) return;
            
            // Clear existing preview for this document type
            preview.innerHTML = '';
            
            const documentDiv = document.createElement('div');
            documentDiv.className = 'document-item';
            documentDiv.id = `doc-${docObj.id}`;
            documentDiv.style.cssText = `
                display: flex;
                align-items: center;
                padding: 10px;
                border: 1px solid #dee2e6;
                border-radius: 6px;
                margin-top: 10px;
                background: white;
            `;

            let imagePreview = '';
            if (docObj.file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = documentDiv.querySelector('.document-image');
                    if (img) img.src = e.target.result;
                };
                reader.readAsDataURL(docObj.file);
                imagePreview = '<img class="document-image" src="" alt="Document preview" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; margin-right: 10px;">';
            } else {
                imagePreview = '<div class="document-image" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border-radius: 4px; margin-right: 10px;"><i class="fas fa-file-pdf fa-lg text-danger"></i></div>';
            }

            documentDiv.innerHTML = `
                ${imagePreview}
                <div class="document-info" style="flex: 1;">
                    <div class="document-name" style="font-weight: 500; margin-bottom: 3px; font-size: 0.9rem;">${docObj.name}</div>
                    <span class="document-status status-processing" style="font-size: 0.8rem; color: #6c757d;">Processing...</span>
                </div>
                <button type="button" class="remove-document" onclick="removeSingleDocument('${docObj.id}', '${docObj.type}')" style="background: none; border: none; color: #dc3545; font-size: 1rem; cursor: pointer; padding: 3px;">
                    <i class="fas fa-times"></i>
                </button>
            `;

            preview.appendChild(documentDiv);
            
            // Update document status
            updateDocumentStatus(docObj.type, 'uploaded');
        }
        
        // Get preview container ID based on document type
        function getPreviewId(docType) {
            const previewMap = {
                'birth_certificate': 'birthCertPreview',
                'report_card': 'reportCardPreview',
                'good_moral': 'goodMoralPreview',
                'parent_id': 'parentIdPreview'
            };
            return previewMap[docType] || 'documentPreview';
        }

        // Process Single Document (Simulate processing)
        function processSingleDocument(docObj) {
            setTimeout(() => {
                const docElement = document.getElementById(`doc-${docObj.id}`);
                if (docElement) {
                    const statusElement = docElement.querySelector('.document-status');
                    statusElement.textContent = 'Completed';
                    statusElement.style.color = '#28a745';

                    const docIndex = uploadedDocuments.findIndex(d => d.id === docObj.id);
                    if (docIndex !== -1) {
                        uploadedDocuments[docIndex].status = 'completed';
                    }
                    
                    // Update document status
                    updateDocumentStatus(docObj.type, 'completed');
                }
            }, 2000);
        }
        
        // Update document status in summary
        function updateDocumentStatus(docType, status) {
            const statusMap = {
                'birth_certificate': 'birthCertStatus',
                'report_card': 'reportCardStatus',
                'good_moral': 'goodMoralStatus',
                'parent_id': 'parentIdStatus'
            };
            
            const statusElement = document.getElementById(statusMap[docType]);
            if (statusElement) {
                const icon = statusElement.querySelector('i');
                if (status === 'uploaded') {
                    icon.className = 'fas fa-clock text-warning mr-2';
                } else if (status === 'completed') {
                    icon.className = 'fas fa-check-square text-success mr-2';
                } else {
                    icon.className = 'fas fa-square text-muted mr-2';
                }
            }
        }

        // Remove Single Document
        function removeSingleDocument(documentId, docType) {
            const docElement = document.getElementById(`doc-${documentId}`);
            if (docElement) {
                docElement.remove();
            }
            uploadedDocuments = uploadedDocuments.filter(doc => doc.id !== documentId);
            
            // Reset status to not uploaded
            updateDocumentStatus(docType, 'not_uploaded');
        }

        // Setup GWA calculation and validation
        function setupGWACalculation() {
            const gwaInput = document.querySelector('[name="previous_gwa"]');
            const performanceLevelInput = document.querySelector('[name="performance_level"]');
            
            if (gwaInput && performanceLevelInput) {
                gwaInput.addEventListener('input', function() {
                    const gwa = parseFloat(this.value);
                    let performanceLevel = '';
                    
                    if (isNaN(gwa) || this.value === '') {
                        performanceLevel = '';
                    } else if (gwa >= 90.00) {
                        performanceLevel = 'Outstanding';
                    } else if (gwa >= 85.00) {
                        performanceLevel = 'Very Satisfactory';
                    } else if (gwa >= 80.00) {
                        performanceLevel = 'Satisfactory';
                    } else if (gwa >= 75.00) {
                        performanceLevel = 'Fairly Satisfactory';
                    } else if (gwa >= 65.00) {
                        performanceLevel = 'Did Not Meet Expectations';
                    } else {
                        performanceLevel = 'Invalid GWA';
                    }
                    
                    performanceLevelInput.value = performanceLevel;
                });
                
                // Validate GWA input
                gwaInput.addEventListener('blur', function() {
                    // Temporarily commented out for testing
                    /*
                    if (this.value === '' || this.value === null) {
                        alert('Previous GWA is required. Please enter a value between 65.00 and 100.00.');
                        this.focus();
                        return;
                    }
                    */
                    const gwa = parseFloat(this.value);
                    if (!isNaN(gwa) && (gwa < 65.00 || gwa > 100.00)) {
                        alert('GWA must be between 65.00 and 100.00');
                        this.focus();
                    }
                });
            }
        }

        // Setup age calculation
        function setupAgeCalculation() {
            const birthDateInput = document.querySelector('[name="date_of_birth"]');
            if (birthDateInput) {
                birthDateInput.addEventListener('change', function() {
                    const birthDate = new Date(this.value);
                    const today = new Date();
                    let age = today.getFullYear() - birthDate.getFullYear();
                    const monthDiff = today.getMonth() - birthDate.getMonth();

                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }

                    document.querySelector('[name="age"]').value = age;
                });
            }
        }

        // Setup profile picture preview
        function setupProfilePicturePreview() {
            const profilePictureInput = document.querySelector('input[name="profile_picture"]');
            if (profilePictureInput) {
                profilePictureInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    const previewDiv = document.getElementById('profile_picture_preview');
                    const previewImage = document.getElementById('preview_image');
                    
                    if (file) {
                        // Validate file type
                        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                        if (!allowedTypes.includes(file.type)) {
                            alert('Please select a valid image file (JPG, PNG, GIF)');
                            this.value = '';
                            previewDiv.style.display = 'none';
                            return;
                        }
                        
                        // Validate file size (2MB max)
                        if (file.size > 2 * 1024 * 1024) {
                            alert('File size must be less than 2MB');
                            this.value = '';
                            previewDiv.style.display = 'none';
                            return;
                        }
                        
                        // Show preview
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImage.src = e.target.result;
                            previewDiv.style.display = 'block';
                            
                            // Update review section if we're on step 5
                            if (currentStep === 5) {
                                updateProfilePictureReview();
                            }
                        };
                        reader.readAsDataURL(file);
                    } else {
                        previewDiv.style.display = 'none';
                        
                        // Update review section if we're on step 5
                        if (currentStep === 5) {
                            updateProfilePictureReview();
                        }
                    }
                });
            }
        }

        // Setup contact number formatting
        function setupContactFormatting() {
            document.querySelectorAll('[type="tel"]').forEach(input => {
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, '');
                    if (this.value.length > 11) {
                        this.value = this.value.slice(0, 11);
                    }
                });
            });
        }

        // Setup form submission
        function setupFormSubmission() {
            document.getElementById('enrollmentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (currentStep !== totalSteps) {
                    alert('Please complete all steps before submitting.');
                    return;
                }
                
                // Check certification agreement
                const certificationAgreement = document.getElementById('certificationAgreement');
                if (!certificationAgreement.checked) {
                    alert('Please agree to the certification and data privacy terms.');
                    return;
                }
                
                // Show loading state
                const submitBtn = document.getElementById('submitBtn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Submitting...';
                submitBtn.disabled = true;
                
                // Prepare form data
                const formData = new FormData(this);
                
                // Add uploaded documents
                uploadedDocuments.forEach((doc, index) => {
                    formData.append(`documents[${index}]`, doc.file);
                });
                
                // Debug: Log form data before submission
                console.log('Form submission started');
                // Force correct action URL
                const actionUrl = '<?= base_url('enrollment/store') ?>';
                console.log('Form action:', actionUrl);
                console.log('Form data entries:');
                for (let [key, value] of formData.entries()) {
                    console.log(key + ':', value);
                }
                
                // Submit form with correct URL
                fetch(actionUrl, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        // Show success message
                        alert(`Enrollment submitted successfully!\n\nEnrollment Number: ${data.enrollment_number}\n\nYou will receive an email notification once your application is reviewed.`);
                        
                        // Redirect to enrollment status page
                        window.location.href = '<?= base_url('enrollment') ?>';
                    } else {
                        // Enhanced error display
                        let errorMessage = 'Enrollment Submission Failed:\n\n';
                        errorMessage += data.message || 'Unknown error occurred';
                        
                        if (data.error_code && data.error_code !== 'UNKNOWN') {
                            errorMessage += '\n\nError Code: ' + data.error_code;
                        }
                        
                        // Show debug info in console for developers
                        if (data.debug_info) {
                            console.error('Detailed error information:', data.debug_info);
                            errorMessage += '\n\nCheck browser console for technical details.';
                        }
                        
                        alert(errorMessage);
                        console.error('Submission error:', data);
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('An error occurred while submitting the enrollment. Check console for details.');
                })
                .finally(() => {
                    // Restore button state
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            });
        }

        // Validate all steps - TEMPORARILY DISABLED FOR TESTING
        function validateAllSteps() {
            // Validation temporarily disabled
            return true;

            /*
            for (let i = 1; i <= totalSteps; i++) {
                const stepElement = document.getElementById(`step-${i}`);
                const requiredFields = stepElement.querySelectorAll('[required]');
                
                for (let field of requiredFields) {
                    if (!field.value.trim()) {
                        goToStep(i);
                        field.focus();
                        return false;
                    }
                }
            }
            return true;
            */
        }

        // Populate review data
        function populateReviewData() {
            console.log('Populating review data...');
            
            // Student name
            const firstName = document.querySelector('[name="first_name"]')?.value || '';
            const middleName = document.querySelector('[name="middle_name"]')?.value || '';
            const lastName = document.querySelector('[name="last_name"]')?.value || '';
            const extension = document.querySelector('[name="extension_name"]')?.value || '';

            let fullName = `${firstName} ${middleName} ${lastName}`.trim();
            if (extension) fullName += ` ${extension}`;
            document.getElementById('review-student-name').textContent = fullName || '-';
            
            // Extension name
            document.getElementById('review-extension-name').textContent = extension || '-';
            
            // Birth date
            document.getElementById('review-birth-date').textContent = document.querySelector('[name="date_of_birth"]')?.value || '-';
            
            // Place of birth
            document.getElementById('review-place-of-birth').textContent = document.querySelector('[name="place_of_birth"]')?.value || '-';
            
            // Birth certificate number
            document.getElementById('review-birth-cert-no').textContent = document.querySelector('[name="birth_certificate_number"]')?.value || '-';
            
            // Mother tongue
            document.getElementById('review-mother-tongue').textContent = document.querySelector('[name="mother_tongue"]')?.value || '-';

            // LRN
            const lrnDigits = [];
            for (let i = 0; i < 12; i++) {
                const digit = document.querySelector(`[name="lrn_digit_${i}"]`)?.value || '0';
                lrnDigits.push(digit);
            }
            document.getElementById('review-lrn').textContent = lrnDigits.join('');

            // Other basic info
            document.getElementById('review-grade-level').textContent = document.querySelector('[name="grade_level"]')?.value || '-';
            document.getElementById('review-school-year').textContent = document.querySelector('[name="school_year"]')?.value || '-';
            document.getElementById('review-age').textContent = document.querySelector('[name="age"]')?.value || '-';
            document.getElementById('review-gender').textContent = document.querySelector('[name="gender"]:checked')?.value || '-';
            
            // Enrollment type
            document.getElementById('review-enrollment-type').textContent = document.querySelector('[name="enrollment_type"]:checked')?.value || '-';
            
            // Previous GWA
            document.getElementById('review-previous-gwa').textContent = document.querySelector('[name="previous_gwa"]')?.value || '-';
            
            // Performance level
            document.getElementById('review-performance-level').textContent = document.querySelector('[name="performance_level"]')?.value || '-';
            
            // Student email and contact
            document.getElementById('review-student-email').textContent = document.querySelector('[name="student_email"]')?.value || '-';
            document.getElementById('review-student-contact').textContent = document.querySelector('[name="student_contact"]')?.value || '-';

            // Current Address
            const houseNo = document.querySelector('[name="current_house_no"]')?.value || '';
            const street = document.querySelector('[name="current_street"]')?.value || '';
            const barangay = document.querySelector('[name="current_barangay"]')?.value || '';
            const municipality = document.querySelector('[name="current_municipality"]')?.value || '';
            const province = document.querySelector('[name="current_province"]')?.value || '';
            const country = document.querySelector('[name="current_country"]')?.value || '';
            const zipCode = document.querySelector('[name="current_zip_code"]')?.value || '';
            
            document.getElementById('review-current-house-no').textContent = houseNo || '-';
            document.getElementById('review-current-street').textContent = street || '-';
            document.getElementById('review-current-barangay').textContent = barangay || '-';
            document.getElementById('review-current-municipality').textContent = municipality || '-';
            document.getElementById('review-current-province').textContent = province || '-';
            document.getElementById('review-current-country').textContent = country || '-';
            document.getElementById('review-current-zip').textContent = zipCode || '-';
            
            // Permanent Address
            const permHouseStreet = document.querySelector('[name="permanent_house_street"]')?.value || '';
            const permStreetName = document.querySelector('[name="permanent_street_name"]')?.value || '';
            const permBarangay = document.querySelector('[name="permanent_barangay"]')?.value || '';
            const permMunicipality = document.querySelector('[name="permanent_municipality"]')?.value || '';
            const permProvince = document.querySelector('[name="permanent_province"]')?.value || '';
            const permCountry = document.querySelector('[name="permanent_country"]')?.value || '';
            const permZipCode = document.querySelector('[name="permanent_zip_code"]')?.value || '';
            
            document.getElementById('review-permanent-house-street').textContent = permHouseStreet || '-';
            document.getElementById('review-permanent-street-name').textContent = permStreetName || '-';
            document.getElementById('review-permanent-barangay').textContent = permBarangay || '-';
            document.getElementById('review-permanent-municipality').textContent = permMunicipality || '-';
            document.getElementById('review-permanent-province').textContent = permProvince || '-';
            document.getElementById('review-permanent-country').textContent = permCountry || '-';
            document.getElementById('review-permanent-zip').textContent = permZipCode || '-';

            // Parent names
            const fatherFirst = document.querySelector('[name="father_first_name"]')?.value || '';
            const fatherLast = document.querySelector('[name="father_last_name"]')?.value || '';
            const fatherContact = document.querySelector('[name="father_contact"]')?.value || '';
            document.getElementById('review-father-name').textContent =
                fatherFirst || fatherLast ? `${fatherFirst} ${fatherLast}`.trim() : '-';
            document.getElementById('review-father-contact').textContent = fatherContact || '-';

            const motherFirst = document.querySelector('[name="mother_first_name"]')?.value || '';
            const motherLast = document.querySelector('[name="mother_last_name"]')?.value || '';
            const motherContact = document.querySelector('[name="mother_contact"]')?.value || '';
            document.getElementById('review-mother-name').textContent =
                motherFirst || motherLast ? `${motherFirst} ${motherLast}`.trim() : '-';
            document.getElementById('review-mother-contact').textContent = motherContact || '-';

            const guardianFirst = document.querySelector('[name="guardian_first_name"]')?.value || '';
            const guardianLast = document.querySelector('[name="guardian_last_name"]')?.value || '';
            const guardianContact = document.querySelector('[name="guardian_contact"]')?.value || '';
            document.getElementById('review-guardian-name').textContent =
                guardianFirst || guardianLast ? `${guardianFirst} ${guardianLast}`.trim() : '-';
            document.getElementById('review-guardian-contact').textContent = guardianContact || '-';
            
            // Special Programs & Benefits
            document.getElementById('review-indigenous-people').textContent = document.querySelector('[name="indigenous_people"]:checked')?.value || '-';
            document.getElementById('review-indigenous-community').textContent = document.querySelector('[name="indigenous_community"]')?.value || '-';
            document.getElementById('review-fourps-beneficiary').textContent = document.querySelector('[name="fourps_beneficiary"]:checked')?.value || '-';
            document.getElementById('review-fourps-household-id').textContent = document.querySelector('[name="fourps_household_id"]')?.value || '-';
            
            // Documents
            document.getElementById('review-document-count').textContent = uploadedDocuments.length;
            const uploadStatus = uploadedDocuments.length > 0 ? `${uploadedDocuments.length} documents uploaded` : 'No documents uploaded';
            document.getElementById('review-upload-status').textContent = uploadStatus;
            
            // Document list
            const docList = document.getElementById('review-document-list');
            docList.innerHTML = '';
            if (uploadedDocuments.length > 0) {
                uploadedDocuments.forEach(doc => {
                    const docItem = document.createElement('div');
                    docItem.innerHTML = `<small class="text-muted">• ${doc.name}</small>`;
                    docList.appendChild(docItem);
                });
            }
            
            // Profile Picture Review
            const profileInput = document.getElementById('profile_picture');
            const croppedImageData = document.getElementById('cropped_image_data');
            const profilePreview = document.getElementById('review-profile-preview');
            const profilePlaceholder = document.getElementById('review-profile-placeholder');
            const profilePreviewContainer = document.getElementById('review-profile-preview-container');
            
            // Check for cropped image data first, then fallback to original file
            if (croppedImageData && croppedImageData.value) {
                // Use cropped image data
                profilePreview.src = croppedImageData.value;
                profilePlaceholder.style.display = 'none';
                profilePreviewContainer.style.display = 'block';
            } else if (profileInput && profileInput.files && profileInput.files[0]) {
                // Fallback to original file if no cropped data
                const reader = new FileReader();
                reader.onload = function(e) {
                    profilePreview.src = e.target.result;
                    profilePlaceholder.style.display = 'none';
                    profilePreviewContainer.style.display = 'block';
                };
                reader.readAsDataURL(profileInput.files[0]);
            } else {
                // Show placeholder
                profilePlaceholder.style.display = 'block';
                profilePreviewContainer.style.display = 'none';
            }
            
            console.log('Review data population completed.');
        }

        // Update Profile Picture Review
        function updateProfilePictureReview() {
            const profileInput = document.getElementById('profile_picture');
            const croppedImageData = document.getElementById('cropped_image_data');
            const profilePreview = document.getElementById('review-profile-preview');
            const profilePlaceholder = document.getElementById('review-profile-placeholder');
            const profilePreviewContainer = document.getElementById('review-profile-preview-container');
            
            // Check for cropped image data first, then fallback to original file
            if (croppedImageData && croppedImageData.value) {
                // Use cropped image data
                profilePreview.src = croppedImageData.value;
                profilePlaceholder.style.display = 'none';
                profilePreviewContainer.style.display = 'block';
            } else if (profileInput && profileInput.files && profileInput.files[0]) {
                // Fallback to original file if no cropped data
                const reader = new FileReader();
                reader.onload = function(e) {
                    profilePreview.src = e.target.result;
                    profilePlaceholder.style.display = 'none';
                    profilePreviewContainer.style.display = 'block';
                };
                reader.readAsDataURL(profileInput.files[0]);
            } else {
                // Show placeholder
                profilePlaceholder.style.display = 'block';
                profilePreviewContainer.style.display = 'none';
            }
        }

        // PDF Download Function
        function downloadPDF() {
            const element = document.querySelector('.card');
            const opt = {
                margin: 0.5,
                filename: 'student_enrollment_form.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2,
                    useCORS: true
                },
                jsPDF: {
                    unit: 'in',
                    format: 'letter',
                    orientation: 'portrait'
                }
            };

            // Hide buttons during PDF generation
            const buttons = document.querySelectorAll('.no-print');
            buttons.forEach(btn => btn.style.display = 'none');

            html2pdf().set(opt).from(element).save().then(() => {
                // Show buttons again after PDF generation
                buttons.forEach(btn => btn.style.display = 'block');
            });
        }

        // Enhanced Profile Picture Upload Functions
        function initializeProfileUpload() {
            const uploadArea = document.getElementById('profileUploadArea');
            const fileInput = document.getElementById('profile_picture');
            
            // Drag and drop event handlers
            uploadArea.addEventListener('dragover', handleDragOver);
            uploadArea.addEventListener('dragenter', handleDragEnter);
            uploadArea.addEventListener('dragleave', handleDragLeave);
            uploadArea.addEventListener('drop', handleDrop);
            
            // Note: Click event listener is already handled by the general upload areas loop above
        }

        function handleDragOver(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function handleDragEnter(e) {
            e.preventDefault();
            e.stopPropagation();
            e.currentTarget.classList.add('dragover');
        }

        function handleDragLeave(e) {
            e.preventDefault();
            e.stopPropagation();
            if (!e.currentTarget.contains(e.relatedTarget)) {
                e.currentTarget.classList.remove('dragover');
            }
        }

        function handleDrop(e) {
            e.preventDefault();
            e.stopPropagation();
            e.currentTarget.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const fileInput = document.getElementById('profile_picture');
                fileInput.files = files;
                
                // Create a synthetic event to trigger the existing loadImageForCropping function
                const event = { target: { files: files } };
                loadImageForCropping(event);
            }
        }

        function removeSelectedFile() {
            const fileInput = document.getElementById('profile_picture');
            const fileSelectedInfo = document.getElementById('fileSelectedInfo');
            const uploadArea = document.getElementById('profileUploadArea');
            
            // Clear the file input
            fileInput.value = '';
            
            // Hide file selected info
            fileSelectedInfo.style.display = 'none';
            
            // Show upload area again
            uploadArea.style.display = 'block';
            
            // Hide cropper if visible
            const cropperContainer = document.getElementById('image-cropper-container');
            if (cropperContainer) {
                cropperContainer.style.display = 'none';
            }
            
            // Destroy cropper instance
            if (typeof cropper !== 'undefined' && cropper) {
                cropper.destroy();
                cropper = null;
            }
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function showFileSelected(file) {
            const uploadArea = document.getElementById('profileUploadArea');
            const fileSelectedInfo = document.getElementById('fileSelectedInfo');
            const fileName = document.getElementById('selectedFileName');
            const fileSize = document.getElementById('selectedFileSize');
            
            // Hide upload area
            uploadArea.style.display = 'none';
            
            // Show file selected info
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            fileSelectedInfo.style.display = 'block';
        }

        // Profile Picture Cropper Functions
        function loadImageForCropping(event) {
            const file = event.target.files[0];
            
            if (!file) {
                return;
            }
            
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                alert('Please select a valid image file (JPG, PNG, or GIF).');
                event.target.value = '';
                removeSelectedFile();
                return;
            }
            
            // Validate file size (2MB max)
            const maxSize = 2 * 1024 * 1024; // 2MB in bytes
            if (file.size > maxSize) {
                alert('File size must be less than 2MB.');
                event.target.value = '';
                removeSelectedFile();
                return;
            }
            
            // Show file selected info
            showFileSelected(file);
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const imageElement = document.getElementById('image-to-crop');
                imageElement.src = e.target.result;
                
                // Show the cropper container
                document.getElementById('image-cropper-container').style.display = 'block';
                
                // Destroy existing cropper if it exists
                if (cropper) {
                    cropper.destroy();
                }
                
                // Initialize new cropper
                cropper = new Cropper(imageElement, {
                    aspectRatio: 1, // Square aspect ratio by default
                    viewMode: 1,
                    autoCropArea: 0.8,
                    responsive: true,
                    restore: false,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false
                });
            };
            
            reader.readAsDataURL(file);
        }
        
        // Function to crop the image and show preview
        function cropImage() {
            if (!cropper) {
                return;
            }
            
            const canvas = cropper.getCroppedCanvas({
                width: 300,
                height: 300
            });
            
            const croppedImageData = canvas.toDataURL('image/jpeg');
            
            // Set the cropped image data to a hidden input
            document.getElementById('cropped_image_data').value = croppedImageData;
            
            // Show preview
            const previewImg = document.getElementById('cropped-image-preview');
            previewImg.src = croppedImageData;
            document.getElementById('cropped-preview-container').style.display = 'block';
            
            // Hide the cropper
            document.getElementById('image-cropper-container').style.display = 'none';
            
            // Update review section if we're on step 5
            if (currentStep === 5) {
                updateProfilePictureReview();
            }
        }
        
        // Function to cancel cropping
        function cancelCrop() {
            // Hide the cropper
            document.getElementById('image-cropper-container').style.display = 'none';
            
            // Clear the file input
            document.getElementById('profile_picture').value = '';
            
            // Destroy the cropper
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            
            // Hide the preview
            document.getElementById('cropped-preview-container').style.display = 'none';
        }
    </script>
</body>

</html>
