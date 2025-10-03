<?php

/**
 * OCR-Based Enrollment Form
 * Step-by-step wizard for document-based enrollment
 * File: app/Views/backend/student/enrollment/ocr_enrollment.php
 */
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OCR Enrollment - Sta Justina National High School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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

        /* Extracted Data Display */
        .extracted-data {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }

        .data-section {
            margin-bottom: 25px;
        }

        .data-section h4 {
            color: #1e293b;
            font-size: 1.1rem;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
        }

        .data-field {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .field-label {
            font-weight: 600;
            color: #475569;
            flex: 1;
        }

        .field-value {
            flex: 2;
            color: #1e293b;
        }

        .field-edit {
            background: none;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            padding: 4px 8px;
            font-size: 0.9rem;
            width: 100%;
        }

        .edit-button {
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 4px 8px;
            font-size: 0.8rem;
            cursor: pointer;
            margin-left: 10px;
        }

        /* Navigation Buttons */
        .wizard-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 30px 40px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }

        .nav-button {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
            color: white;
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-success:hover {
            background: #059669;
            color: white;
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
                <!--Header -->
                <div class="login-header box-shadow mb-4" style="margin-left: -15px; margin-right: -15px; padding-left: 15px; padding-right: 15px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="brand-logo d-flex align-items-center">
                            <a href="<?= base_url('enrollment') ?>" class="me-3">
                                <img src="/backend/vendors/images/logo-login.png" alt="" style="width: 60px;" />
                            </a>
                            <div class="school-info">
                                <h4 class="mb-0 text-black font-weight-bold">STA. JUSTINA HIGH SCHOOL</h4>
                                <p class="mb-0 text-muted small">OCR-Based Enrollment Form</p>
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
                                <div class="step-title">Upload Documents</div>
                            </div>
                            <div class="wizard-step" data-step="2">
                                <div class="step-number">2</div>
                                <div class="step-title">Review & Edit Student Information</div>
                            </div>
                            <div class="wizard-step" data-step="3">
                                <div class="step-number">3</div>
                                <div class="step-title">Review Address & Family</div>
                            </div>
                            <div class="wizard-step" data-step="4">
                                <div class="step-number">4</div>
                                <div class="step-title">Academic Special Needs</div>
                            </div>
                            <div class="wizard-step" data-step="5">
                                <div class="step-number">5</div>
                                <div class="step-title">Verify & Submit</div>
                            </div>
                        </div>

                        <form id="ocrEnrollmentForm" method="POST" action="<?= base_url('enrollment/store-ocr') ?>" enctype="multipart/form-data">
                            <?= csrf_field() ?>

                            <!-- Step 1: Upload Documents -->
                            <div id="step-1" class="form-wizard-content active">
                                <h3 class="mb-4">Upload Required Documents</h3>
                                <p class="text-muted mb-4">Please upload clear images or scans of the following documents. Our system will automatically extract the information.</p>

                                <!-- Upload Area -->
                                <div class="upload-area" id="uploadArea">
                                    <div class="upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div class="upload-text">Drag and drop your documents here</div>
                                    <div class="upload-hint">or click to browse files (JPG, PNG, PDF)</div>
                                    <input type="file" id="documentInput" multiple accept="image/*,.pdf" style="display: none;">
                                </div>

                                <!-- Document Preview -->
                                <div class="document-preview" id="documentPreview"></div>

                                <!-- Required Documents Checklist -->
                                <div class="mt-4">
                                    <h5>Required Documents:</h5>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-square text-muted mr-2"></i> Birth Certificate (PSA)</li>
                                        <li><i class="fas fa-square text-muted mr-2"></i> Report Card (if transferring)</li>
                                        <li><i class="fas fa-square text-muted mr-2"></i> Good Moral Certificate (if transferring)</li>
                                        <li><i class="fas fa-square text-muted mr-2"></i> Parent/Guardian ID</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Step 2: Review & Edit Student Information -->
                            <div id="step-2" class="form-wizard-content">
                                <!-- Instructions -->
                                <div class="alert alert-info mb-4">
                                    <h6><i class="fas fa-info-circle"></i> Review & Edit Student Information</h6>
                                    <p class="mb-0">Please review the automatically extracted information and make corrections if needed. All required fields must be completed.</p>
                                </div>

                                <!-- Basic Enrollment Information -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h5 class="text-primary border-bottom pb-2 mb-3">Basic Enrollment Information</h5>
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
                                        <h5 class="text-primary border-bottom pb-2 mb-3">Student Personal Information</h5>
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
                            </div>

                            <!-- Step 3: Review Address & Family -->
                            <div id="step-3" class="form-wizard-content">
                                <!-- Current Address -->
                                <div class="section-header">Current Address</div>
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
                                <div class="section-header">Permanent Address</div>
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
                                    </div>
                                </div>

                                <!-- Legal Guardian Information -->
                                <div class="mb-4">
                                    <h6 class="text-primary mb-3">Legal Guardian's Information (if applicable)</h6>
                                    <div class="row mb-3">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" name="guardian_last_name" class="form-control">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">First Name</label>
                                            <input type="text" name="guardian_first_name" class="form-control">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Middle Name</label>
                                            <input type="text" name="guardian_middle_name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Contact Number</label>
                                            <input type="tel" name="guardian_contact" class="form-control" placeholder="09XXXXXXXXX">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 4: Academic Special Needs -->
                            <div id="step-4" class="form-wizard-content">
                                <!-- Special Needs Assessment -->
                                <div class="section-header">Special Needs Assessment</div>
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
                                <div class="section-header">Academic Performance</div>
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
                                <div id="returning-transfer-section-ocr" style="display: none;">
                                    <div class="section-header">For Returning Learner (Balik-Aral) and Transfer Students</div>
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
                                <div id="shs-section-ocr" style="display: none;">
                                    <div class="section-header">For Senior High School Students</div>
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

                            <!-- Step 5: Verify & Submit -->
                            <div id="step-5" class="form-wizard-content">
                                <h3 class="mb-4">Final Verification</h3>
                                <p class="text-muted mb-4">Please verify all information is correct before submitting your enrollment.</p>

                                <!-- Summary Cards -->
                                <div class="row">
                                    <!-- Student Personal Information -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-header bg-white text-dark d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">Student Personal Information</h6>
                                                <a href="#" class="text-primary text-decoration-none" onclick="goToStep(2)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>Full Name:</strong> <span id="final-student-name">-</span></p>
                                                <p><strong>Extension Name:</strong> <span id="final-extension-name">-</span></p>
                                                <p><strong>Birth Date:</strong> <span id="final-birth-date">-</span></p>
                                                <p><strong>Age:</strong> <span id="final-age">-</span></p>
                                                <p><strong>Gender:</strong> <span id="final-gender">-</span></p>
                                                <p><strong>Birth Certificate No.:</strong> <span id="final-birth-cert-no">-</span></p>
                                                <p><strong>Mother Tongue:</strong> <span id="final-mother-tongue">-</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Enrollment Information -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-header bg-white text-dark d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">Enrollment Information</h6>
                                                <a href="#" class="text-primary text-decoration-none" onclick="goToStep(2)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>LRN:</strong> <span id="final-lrn">-</span></p>
                                                <p><strong>Grade Level:</strong> <span id="final-grade-level">-</span></p>
                                                <p><strong>School Year:</strong> <span id="final-school-year">-</span></p>
                                                <p><strong>Enrollment Type:</strong> <span id="final-enrollment-type">-</span></p>
                                                <p><strong>Previous GWA:</strong> <span id="final-previous-gwa">-</span></p>
                                                <p><strong>Performance Level:</strong> <span id="final-performance-level">-</span></p>
                                                <p><strong>Student Email:</strong> <span id="final-student-email">-</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Address Information -->
                                <div class="row mb-3">
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-header bg-white text-dark d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">Current Address</h6>
                                                <a href="#" class="text-primary text-decoration-none" onclick="goToStep(3)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>House No.:</strong> <span id="final-current-house-no">-</span></p>
                                                <p><strong>Street:</strong> <span id="final-current-street">-</span></p>
                                                <p><strong>Barangay:</strong> <span id="final-current-barangay">-</span></p>
                                                <p><strong>Municipality/City:</strong> <span id="final-current-municipality">-</span></p>
                                                <p><strong>Province:</strong> <span id="final-current-province">-</span></p>
                                                <p><strong>Country:</strong> <span id="final-current-country">-</span></p>
                                                <p><strong>Zip Code:</strong> <span id="final-current-zip">-</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-header bg-white text-dark d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">Permanent Address</h6>
                                                <a href="#" class="text-primary text-decoration-none" onclick="goToStep(3)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>House No./Street:</strong> <span id="final-permanent-house-street">-</span></p>
                                                <p><strong>Street Name:</strong> <span id="final-permanent-street-name">-</span></p>
                                                <p><strong>Barangay:</strong> <span id="final-permanent-barangay">-</span></p>
                                                <p><strong>Municipality/City:</strong> <span id="final-permanent-municipality">-</span></p>
                                                <p><strong>Province:</strong> <span id="final-permanent-province">-</span></p>
                                                <p><strong>Country:</strong> <span id="final-permanent-country">-</span></p>
                                                <p><strong>Zip Code:</strong> <span id="final-permanent-zip">-</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Parent/Guardian Information -->
                                <div class="row mb-3">
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-header bg-white text-dark d-flex justify-content-between align-items-center">
                                                 <h6 class="mb-0">Father's Information</h6>
                                                 <a href="#" class="text-primary text-decoration-none" onclick="goToStep(3)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                                             </div>
                                            <div class="card-body">
                                                <p><strong>Name:</strong> <span id="final-father-name">-</span></p>
                                                <p><strong>Contact:</strong> <span id="final-father-contact">-</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-header bg-white text-dark d-flex justify-content-between align-items-center">
                                                 <h6 class="mb-0">Mother's Information</h6>
                                                 <a href="#" class="text-primary text-decoration-none" onclick="goToStep(3)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                                             </div>
                                            <div class="card-body">
                                                <p><strong>Name:</strong> <span id="final-mother-name">-</span></p>
                                                <p><strong>Contact:</strong> <span id="final-mother-contact">-</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-header bg-white text-dark d-flex justify-content-between align-items-center">
                                                 <h6 class="mb-0">Guardian's Information</h6>
                                                 <a href="#" class="text-primary text-decoration-none" onclick="goToStep(3)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                                             </div>
                                            <div class="card-body">
                                                <p><strong>Name:</strong> <span id="final-guardian-name">-</span></p>
                                                <p><strong>Contact:</strong> <span id="final-guardian-contact">-</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Additional Information -->
                                <div class="row mb-3">
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-header bg-white text-dark d-flex justify-content-between align-items-center">
                                                 <h6 class="mb-0">Special Programs & Benefits</h6>
                                                 <a href="#" class="text-primary text-decoration-none" onclick="goToStep(2)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                                             </div>
                                            <div class="card-body">
                                                <p><strong>Indigenous Peoples:</strong> <span id="final-indigenous-people">-</span></p>
                                                <p><strong>IP Community:</strong> <span id="final-indigenous-community">-</span></p>
                                                <p><strong>4Ps Beneficiary:</strong> <span id="final-fourps-beneficiary">-</span></p>
                                                <p><strong>4Ps Household ID:</strong> <span id="final-fourps-household-id">-</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-header bg-white text-dark d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">Documents Uploaded</h6>
                                                <a href="#" class="text-primary text-decoration-none" onclick="goToStep(1)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>Total Documents:</strong> <span id="final-document-count">0</span></p>
                                                <p><strong>Upload Status:</strong> <span id="final-upload-status">No documents uploaded</span></p>
                                                <div id="final-document-list"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Certification -->
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle"></i> Data Privacy Notice</h6>
                                    <p class="mb-0">By submitting this form, you consent to the processing of your personal data in accordance with the Data Privacy Act of 2012. All information will be used solely for educational purposes and will be kept confidential.</p>
                                </div>
                            </div>
                     <!-- Navigation -->
                    <div class="wizard-buttons">
                        <a href="<?= base_url('enrollment') ?>" class="wizard-btn wizard-btn-cancel">
                            <i class="fas fa-times mr-2"></i> Cancel
                        </a>
                        <button type="button" class="wizard-btn wizard-btn-prev" id="prevBtn" onclick="changeStep(-1)" style="display: none;">
                            <i class="fas fa-arrow-left mr-2"></i> Previous
                        </button>
                        <button type="button" class="wizard-btn wizard-btn-next" id="nextBtn" onclick="changeStep(1)">
                            Next<i class="fas fa-arrow-right ml-2"></i>
                        </button>
                        <button type="submit" class="wizard-btn wizard-btn-submit" id="submitBtn" style="display: none;">
                            <i class="fas fa-paper-plane mr-2"></i> Submit Enrollment
                        </button>
                    </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 5;
        let uploadedDocuments = [];
        let extractedData = {};

        // Function to show/hide returning/transfer section for OCR form
        function toggleReturningTransferSectionOCR() {
            const returningRadio = document.getElementById('returning_learner');
            const transferRadio = document.getElementById('transfer_learner');
            const section = document.getElementById('returning-transfer-section-ocr');
            
            if (returningRadio && transferRadio && section) {
                if (returningRadio.checked || transferRadio.checked) {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            }
        }

        // Function to show/hide SHS section based on grade level for OCR form
        function toggleSHSSectionOCR() {
            const gradeSelect = document.querySelector('select[name="grade_level"]');
            const shsSection = document.getElementById('shs-section-ocr');
            
            if (gradeSelect && shsSection) {
                const selectedGrade = gradeSelect.value;
                if (selectedGrade === 'Grade 11' || selectedGrade === 'Grade 12') {
                    shsSection.style.display = 'block';
                } else {
                    shsSection.style.display = 'none';
                }
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupFileUpload();
            setupGWACalculation();
            updateStepDisplay();
            
            // Handle enrollment type change
            document.querySelectorAll('input[name="enrollment_type"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    console.log('Enrollment type changed to:', this.value);
                    toggleReturningTransferSectionOCR();
                });
            });

            // Handle grade level change
            const gradeSelect = document.querySelector('select[name="grade_level"]');
            if (gradeSelect) {
                gradeSelect.addEventListener('change', function() {
                    console.log('Grade level changed to:', this.value);
                    toggleSHSSectionOCR();
                });
            }
        });

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

        // File Upload Setup
        function setupFileUpload() {
            const uploadArea = document.getElementById('uploadArea');
            const fileInput = document.getElementById('documentInput');

            uploadArea.addEventListener('click', () => fileInput.click());

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
                handleFiles(e.dataTransfer.files);
            });

            fileInput.addEventListener('change', (e) => {
                handleFiles(e.target.files);
            });
        }

        // Handle File Upload
        function handleFiles(files) {
            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/') || file.type === 'application/pdf') {
                    const documentId = Date.now() + Math.random();
                    const document = {
                        id: documentId,
                        file: file,
                        name: file.name,
                        status: 'processing'
                    };

                    uploadedDocuments.push(document);
                    displayDocument(document);
                    processDocument(document);
                }
            });
        }

        // Display Document Preview
        function displayDocument(document) {
            const preview = document.getElementById('documentPreview');
            const documentDiv = document.createElement('div');
            documentDiv.className = 'document-item';
            documentDiv.id = `doc-${document.id}`;

            let imagePreview = '';
            if (document.file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    documentDiv.querySelector('.document-image').src = e.target.result;
                };
                reader.readAsDataURL(document.file);
                imagePreview = '<img class="document-image" src="" alt="Document preview">';
            } else {
                imagePreview = '<div class="document-image d-flex align-items-center justify-content-center"><i class="fas fa-file-pdf fa-3x text-danger"></i></div>';
            }

            documentDiv.innerHTML = `
        ${imagePreview}
        <div class="document-info">
            <div class="document-name">${document.name}</div>
            <span class="document-status status-processing">Processing...</span>
        </div>
        <button type="button" class="remove-document" onclick="removeDocument('${document.id}')">
            <i class="fas fa-times"></i>
        </button>
    `;

            preview.appendChild(documentDiv);
        }

        // Process Document (Simulate OCR)
        function processDocument(document) {
            // Simulate OCR processing delay
            setTimeout(() => {
                // Simulate extracted data (in real implementation, this would call OCR API)
                const mockData = {
                    'student-name': 'Juan Dela Cruz',
                    'birth-date': '2010-05-15',
                    'gender': 'Male',
                    'lrn': '123456789012',
                    'father-name': 'Pedro Dela Cruz',
                    'mother-name': 'Maria Santos',
                    'contact-number': '09123456789',
                    'address': '123 Main St, Barangay Centro, Manila City'
                };

                // Update extracted data
                Object.assign(extractedData, mockData);

                // Update document status
                const docElement = document.getElementById(`doc-${document.id}`);
                const statusElement = docElement.querySelector('.document-status');
                statusElement.textContent = 'Completed';
                statusElement.className = 'document-status status-completed';

                // Update document in array
                const docIndex = uploadedDocuments.findIndex(d => d.id === document.id);
                if (docIndex !== -1) {
                    uploadedDocuments[docIndex].status = 'completed';
                }

                updateExtractedDataDisplay();
            }, 2000);
        }

        // Remove Document
        function removeDocument(documentId) {
            const docElement = document.getElementById(`doc-${documentId}`);
            if (docElement) {
                docElement.remove();
            }

            uploadedDocuments = uploadedDocuments.filter(doc => doc.id !== documentId);
        }

        // Update Extracted Data Display
        function updateExtractedDataDisplay() {
            Object.keys(extractedData).forEach(key => {
                const element = document.getElementById(key);
                if (element) {
                    element.textContent = extractedData[key] || '-';
                }
            });
        }

        // Edit Field
        function editField(fieldId) {
            const element = document.getElementById(fieldId);
            const currentValue = element.textContent;

            const input = document.createElement('input');
            input.type = 'text';
            input.value = currentValue === '-' ? '' : currentValue;
            input.className = 'field-edit';
            input.onblur = () => saveField(fieldId, input.value);
            input.onkeypress = (e) => {
                if (e.key === 'Enter') {
                    saveField(fieldId, input.value);
                }
            };

            element.parentNode.replaceChild(input, element);
            input.focus();
        }

        // Save Field
        function saveField(fieldId, value) {
            const input = document.querySelector('.field-edit');
            const span = document.createElement('span');
            span.className = 'field-value';
            span.id = fieldId;
            span.textContent = value || '-';

            input.parentNode.replaceChild(span, input);
            extractedData[fieldId] = value;
        }

        // Change Step
        function changeStep(direction) {
            if (direction === 1 && currentStep < totalSteps) {
                // Validation temporarily commented out for testing
                // if (validateStep(currentStep)) {
                    currentStep++;
                    updateStepDisplay();
                // }
            } else if (direction === -1 && currentStep > 1) {
                currentStep--;
                updateStepDisplay();
            }
        }

        // Go to specific step (for edit buttons)
        function goToStep(stepNumber) {
            if (stepNumber >= 1 && stepNumber <= totalSteps) {
                currentStep = stepNumber;
                updateStepDisplay();
            }
        }

        // Validate Step - TEMPORARILY COMMENTED OUT FOR TESTING
        /*
        function validateStep(step) {
            if (step === 1) {
                if (uploadedDocuments.length === 0) {
                    alert('Please upload at least one document.');
                    return false;
                }
                const completedDocs = uploadedDocuments.filter(doc => doc.status === 'completed');
                if (completedDocs.length === 0) {
                    alert('Please wait for document processing to complete.');
                    return false;
                }
            }
            return true;
        }
        */

        // Update Step Display
        function updateStepDisplay() {
            // Update step indicators
            document.querySelectorAll('.wizard-step').forEach((step, index) => {
                step.classList.remove('active', 'completed');
                if (index + 1 === currentStep) {
                    step.classList.add('active');
                } else if (index + 1 < currentStep) {
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

            // Update final review data
            if (currentStep === 3) {
                updateFinalReview();
            }
        }

        // Update Final Review
        function updateFinalReview() {
            // Update from extracted data
            const finalFields = {
                'final-student-name': 'student-name',
                'final-birth-date': 'birth-date',
                'final-gender': 'gender',
                'final-lrn': 'lrn',
                'final-father-name': 'father-name',
                'final-mother-name': 'mother-name',
                'final-contact-number': 'contact-number',
                'final-address': 'address'
            };

            Object.keys(finalFields).forEach(finalKey => {
                const sourceKey = finalFields[finalKey];
                const element = document.getElementById(finalKey);
                if (element) {
                    element.textContent = extractedData[sourceKey] || '-';
                }
            });
            
            // Update from form inputs
            const formFields = {
                'final-extension-name': '[name="extension_name"]',
                'final-age': '[name="age"]',
                'final-birth-cert-no': '[name="birth_certificate_number"]',
                'final-mother-tongue': '[name="mother_tongue"]',
                'final-grade-level': '[name="grade_level"]',
                'final-school-year': '[name="school_year"]',
                'final-enrollment-type': '[name="enrollment_type"]:checked',
                'final-student-email': '[name="student_email"]',
                'final-current-house-no': '[name="current_house_no"]',
                'final-current-street': '[name="current_street"]',
                'final-current-barangay': '[name="current_barangay"]',
                'final-current-municipality': '[name="current_municipality"]',
                'final-current-province': '[name="current_province"]',
                'final-current-country': '[name="current_country"]',
                'final-current-zip': '[name="current_zip_code"]',
                'final-permanent-house-street': '[name="permanent_house_street"]',
                'final-permanent-street-name': '[name="permanent_street_name"]',
                'final-permanent-barangay': '[name="permanent_barangay"]',
                'final-permanent-municipality': '[name="permanent_municipality"]',
                'final-permanent-province': '[name="permanent_province"]',
                'final-permanent-country': '[name="permanent_country"]',
                'final-permanent-zip': '[name="permanent_zip_code"]',
                'final-father-contact': '[name="father_contact"]',
                'final-mother-contact': '[name="mother_contact"]',
                'final-guardian-name': '[name="guardian_last_name"], [name="guardian_first_name"], [name="guardian_middle_name"]',
                'final-guardian-contact': '[name="guardian_contact"]',
                'final-indigenous-people': '[name="indigenous_people"]:checked',
                'final-indigenous-community': '[name="indigenous_community"]',
                'final-fourps-beneficiary': '[name="fourps_beneficiary"]:checked',
                'final-fourps-household-id': '[name="fourps_household_id"]'
            };
            
            Object.keys(formFields).forEach(finalKey => {
                const selector = formFields[finalKey];
                const element = document.getElementById(finalKey);
                if (element) {
                    if (selector.includes(',')) {
                        // Handle multiple fields (like guardian name)
                        const selectors = selector.split(',').map(s => s.trim());
                        const values = selectors.map(s => {
                            const input = document.querySelector(s);
                            return input ? input.value : '';
                        }).filter(v => v).join(' ');
                        element.textContent = values || '-';
                    } else {
                        const input = document.querySelector(selector);
                        if (input) {
                            element.textContent = input.value || '-';
                        }
                    }
                }
            });
            
            // Update GWA and performance level
            const gwaInput = document.querySelector('[name="previous_gwa"]');
            const performanceLevelInput = document.querySelector('[name="performance_level"]');
            
            if (gwaInput) {
                const gwaElement = document.getElementById('final-previous-gwa');
                if (gwaElement) {
                    gwaElement.textContent = gwaInput.value || '-';
                }
            }
            
            if (performanceLevelInput) {
                const performanceElement = document.getElementById('final-performance-level');
                if (performanceElement) {
                    performanceElement.textContent = performanceLevelInput.value || '-';
                }
            }
            
            // Update document information
            const documentCountElement = document.getElementById('final-document-count');
            const uploadStatusElement = document.getElementById('final-upload-status');
            const documentListElement = document.getElementById('final-document-list');
            
            if (documentCountElement) {
                documentCountElement.textContent = uploadedDocuments.length;
            }
            
            if (uploadStatusElement) {
                if (uploadedDocuments.length === 0) {
                    uploadStatusElement.textContent = 'No documents uploaded';
                } else {
                    const completedDocs = uploadedDocuments.filter(doc => doc.status === 'completed');
                    uploadStatusElement.textContent = `${completedDocs.length} of ${uploadedDocuments.length} processed`;
                }
            }
            
            if (documentListElement) {
                documentListElement.innerHTML = '';
                uploadedDocuments.forEach(doc => {
                    const docItem = document.createElement('small');
                    docItem.className = 'd-block text-muted';
                    docItem.textContent = `• ${doc.name} (${doc.status})`;
                    documentListElement.appendChild(docItem);
                });
            }
        }

        // Form Submission
        document.getElementById('ocrEnrollmentForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Show loading state
            const submitBtn = document.querySelector('.wizard-btn-submit');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Submitting...';
            submitBtn.disabled = true;

            // Create form data with extracted information and form inputs
            const formData = new FormData(this);
            
            // Add extracted data
            Object.keys(extractedData).forEach(key => {
                formData.append(key, extractedData[key]);
            });

            // Add uploaded files
            uploadedDocuments.forEach((doc, index) => {
                formData.append(`documents[${index}]`, doc.file);
            });

            // Submit form
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    alert(`OCR Enrollment submitted successfully!\n\nEnrollment Number: ${data.enrollment_number}\n\nYou will receive an email notification once your application is reviewed.`);
                    
                    // Optionally redirect
                    window.location.href = '<?= base_url('enrollment') ?>';
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while submitting the enrollment. Please try again.');
            })
            .finally(() => {
                // Restore button state
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    </script>
</body>

</html>
