<?php
/**
 * Enrollment Selection Page
 * Allows users to choose between Manual and OCR enrollment methods
 * File: app/Views/backend/student/enrollment/enrollment_selection.php
 */
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Enrollment - Sta Justina National High School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/backend/vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="/backend/vendors/styles/style.css" />
    <link href="<?= base_url('backend/src/css/enrollment-buttons.css') ?>" rel="stylesheet">
    <link rel="icon" type="image/png" href="/backend/vendors/images/logo-login-removebg-preview.png">
    <style>
        .enrollment-card {
            transition: all 0.3s ease;
            border: 2px solid #e9ecef;
            height: 100%;
        }
        
        .enrollment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-color: #007bff;
        }
        
        .enrollment-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #007bff;
        }
        
        .enrollment-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.8rem;
            color: #2c3e50;
        }
        
        .enrollment-description {
            color: #6c757d;
            margin-bottom: 1.5rem;
            line-height: 1.5;
            font-size: 0.9rem;
        }
        
        .enrollment-features {
            list-style: none;
            padding: 0;
            margin-bottom: 1.5rem;
        }
        
        .enrollment-features li {
            padding: 0.3rem 0;
            color: #495057;
            font-size: 0.85rem;
        }
        
        .enrollment-features i {
            color: #28a745;
            margin-right: 0.5rem;
        }
        
        .btn-enrollment {
            padding: 10px 25px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .page-header {
            margin-bottom: 2rem;
            padding: 1rem 0;
        }
        
        .school-info h4 {
            text-align: center;
        }
        
        .status-check {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 3rem;
        }
        
        @media (max-width: 768px) {
            .enrollment-icon {
                font-size: 3rem;
            }
            
            .enrollment-title {
                font-size: 1.3rem;
            }
        }
    </style>
</head>

<body class="bg-light">
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

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
        <!-- Enrollment Status Check -->
        <div class="status-check">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-2"><i class="fas fa-search text-primary me-2"></i>Check Your Enrollment Status</h5>
                    <p class="mb-0 text-muted">Already submitted an application? Enter your enrollment number to check the status.</p>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control" id="enrollmentNumber" placeholder="ENR-YYYYMMDD-XXXX" maxlength="17" style="text-transform: uppercase;">
                        <button class="btn btn-primary" type="button" id="checkStatusBtn" onclick="checkStatus()">
                            <i class="fas fa-search"></i>
                            <span id="checkBtnText" class="ms-2">Check</span>
                        </button>
                    </div>
                    <div id="enrollmentNumberFeedback" class="invalid-feedback" style="display: none;"></div>
                </div>
            </div>
        </div>

        <!-- Enrollment Methods -->
        <div class="row g-4 justify-content-center">
            <!-- Manual Enrollment -->
            <div class="col-lg-5 col-md-6">
                <div class="card enrollment-card h-100">
                    <div class="card-body text-center p-3">
                        <div class="enrollment-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <h3 class="enrollment-title">Manual Enrollment</h3>
                        <p class="enrollment-description">
                            Fill out the enrollment form manually by entering all required information step by step.
                        </p>
                        
                        <ul class="enrollment-features text-start">
                            <li><i class="fas fa-check"></i> Step-by-step guided process</li>
                            <li><i class="fas fa-check"></i> Complete control over data entry</li>
                            <li><i class="fas fa-check"></i> Suitable for all document types</li>
                            <li><i class="fas fa-check"></i> Works on any device</li>
                            <li><i class="fas fa-check"></i> No document scanning required</li>
                        </ul>
                        
                        <div class="mt-auto">
                            <a href="<?= base_url('enrollment/manual') ?>" class="btn btn-primary btn-enrollment">
                                <i class="fas fa-arrow-right me-2"></i>Start Manual Enrollment
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- OCR Enrollment -->
            <div class="col-lg-5 col-md-6">
                <div class="card enrollment-card h-100">
                    <div class="card-body text-center p-3">
                        <div class="enrollment-icon">
                            <i class="fas fa-camera"></i>
                        </div>
                        <h3 class="enrollment-title">OCR Enrollment</h3>
                        <p class="enrollment-description">
                            Upload documents and let our system automatically extract information using OCR technology.
                        </p>
                        
                        <ul class="enrollment-features text-start">
                            <li><i class="fas fa-check"></i> Automatic data extraction</li>
                            <li><i class="fas fa-check"></i> Faster enrollment process</li>
                            <li><i class="fas fa-check"></i> Reduces manual typing errors</li>
                            <li><i class="fas fa-check"></i> Smart document recognition</li>
                            <li><i class="fas fa-check"></i> Review and edit extracted data</li>
                        </ul>
                        
                        <div class="mt-auto">
                            <a href="<?= base_url('enrollment/ocr') ?>" class="btn btn-success btn-enrollment">
                                <i class="fas fa-upload me-2"></i>Start OCR Enrollment
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Information Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="text-center mb-4"><i class="text-info me-2"></i>Important Information</h4>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary"><i class="me-2"></i>Required Documents</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-chevron-right text-muted me-2"></i>PSA Birth Certificate</li>
                                    <li><i class="fas fa-chevron-right text-muted me-2"></i>Report Card (if transferring)</li>
                                    <li><i class="fas fa-chevron-right text-muted me-2"></i>Good Moral Certificate (if transferring)</li>
                                    <li><i class="fas fa-chevron-right text-muted me-2"></i>Parent/Guardian ID</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary"><i class="me-2"></i>Enrollment Process</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-chevron-right text-muted me-2"></i>Submit your application online</li>
                                    <li><i class="fas fa-chevron-right text-muted me-2"></i>Wait for admin review and approval</li>
                                    <li><i class="fas fa-chevron-right text-muted me-2"></i>Receive email notification with account details</li>
                                    <li><i class="fas fa-chevron-right text-muted me-2"></i>Complete enrollment at school</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-4" role="alert">
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Tip:</strong> Make sure all information is accurate and complete before submitting. 
                            You will receive an email notification once your application is reviewed.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Enrollment Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="statusContent">
                    <!-- Status content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global variables
        let statusModal;
        let retryCount = 0;
        const maxRetries = 3;
        
        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            const enrollmentInput = document.getElementById('enrollmentNumber');
            const checkBtn = document.getElementById('checkStatusBtn');
            const feedback = document.getElementById('enrollmentNumberFeedback');
            
            // Initialize modal
            statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
            
            // Input formatting and validation
            enrollmentInput.addEventListener('input', function(e) {
                let value = e.target.value.toUpperCase().replace(/[^A-Z0-9-]/g, '');
                
                // Format as ENR-YYYYMMDD-XXXX
                if (value.length > 3 && !value.startsWith('ENR-')) {
                    if (value.startsWith('ENR')) {
                        value = 'ENR-' + value.substring(3);
                    }
                }
                
                if (value.length > 12 && value.charAt(12) !== '-') {
                    value = value.substring(0, 12) + '-' + value.substring(12);
                }
                
                e.target.value = value;
                validateEnrollmentNumber(value);
            });
            
            // Real-time validation
            enrollmentInput.addEventListener('blur', function(e) {
                validateEnrollmentNumber(e.target.value);
            });
            
            // Enter key support
            enrollmentInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    checkStatus();
                }
            });
            
            // Focus management for accessibility
            enrollmentInput.addEventListener('focus', function() {
                clearValidationState();
            });
        });
        
        function validateEnrollmentNumber(value) {
            const input = document.getElementById('enrollmentNumber');
            const feedback = document.getElementById('enrollmentNumberFeedback');
            const checkBtn = document.getElementById('checkStatusBtn');
            
            // Clear previous validation
            input.classList.remove('is-valid', 'is-invalid');
            feedback.style.display = 'none';
            
            if (!value) {
                return false;
            }
            
            // Check format: ENR-YYYYMMDD-XXXX
            const pattern = /^ENR-\d{8}-\d{4}$/;
            
            if (!pattern.test(value)) {
                input.classList.add('is-invalid');
                feedback.textContent = 'Please enter a valid enrollment number (ENR-YYYYMMDD-XXXX)';
                feedback.style.display = 'block';
                checkBtn.disabled = true;
                return false;
            }
            
            // Validate date part
            const datePart = value.substring(4, 12);
            const year = parseInt(datePart.substring(0, 4));
            const month = parseInt(datePart.substring(4, 6));
            const day = parseInt(datePart.substring(6, 8));
            
            const currentYear = new Date().getFullYear();
            
            if (year < 2020 || year > currentYear + 1) {
                input.classList.add('is-invalid');
                feedback.textContent = 'Invalid year in enrollment number';
                feedback.style.display = 'block';
                checkBtn.disabled = true;
                return false;
            }
            
            if (month < 1 || month > 12) {
                input.classList.add('is-invalid');
                feedback.textContent = 'Invalid month in enrollment number';
                feedback.style.display = 'block';
                checkBtn.disabled = true;
                return false;
            }
            
            if (day < 1 || day > 31) {
                input.classList.add('is-invalid');
                feedback.textContent = 'Invalid day in enrollment number';
                feedback.style.display = 'block';
                checkBtn.disabled = true;
                return false;
            }
            
            // Valid format
            input.classList.add('is-valid');
            checkBtn.disabled = false;
            return true;
        }
        
        function clearValidationState() {
            const input = document.getElementById('enrollmentNumber');
            const feedback = document.getElementById('enrollmentNumberFeedback');
            
            input.classList.remove('is-valid', 'is-invalid');
            feedback.style.display = 'none';
        }
        
        function setButtonLoading(loading) {
            const checkBtn = document.getElementById('checkStatusBtn');
            const btnText = document.getElementById('checkBtnText');
            const btnIcon = checkBtn.querySelector('i');
            
            if (loading) {
                checkBtn.disabled = true;
                btnIcon.className = 'fas fa-spinner fa-spin';
                btnText.textContent = 'Checking...';
            } else {
                checkBtn.disabled = false;
                btnIcon.className = 'fas fa-search';
                btnText.textContent = 'Check';
            }
        }
        
        function checkStatus() {
            const enrollmentNumber = document.getElementById('enrollmentNumber').value.trim();
            
            // Validate input
            if (!validateEnrollmentNumber(enrollmentNumber)) {
                return;
            }
            
            // Reset retry count for new request
            retryCount = 0;
            
            // Set loading state
            setButtonLoading(true);
            
            // Perform the status check
            performStatusCheck(enrollmentNumber);
        }
        
        function performStatusCheck(enrollmentNumber) {
            const statusContent = document.getElementById('statusContent');
            
            // Show loading in modal
            statusContent.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status" aria-label="Loading">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 mb-0">Checking enrollment status...</p>
                    <small class="text-muted">Please wait while we retrieve your information</small>
                </div>
            `;
            
            // Show modal
            statusModal.show();
            
            // Fetch status with timeout
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 15000); // 15 second timeout
            
            fetch(`<?= base_url('enrollment/status/') ?>${enrollmentNumber}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                signal: controller.signal
            })
            .then(response => {
                clearTimeout(timeoutId);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                return response.json();
            })
            .then(data => {
                setButtonLoading(false);
                displayStatusResult(data, enrollmentNumber);
            })
            .catch(error => {
                clearTimeout(timeoutId);
                setButtonLoading(false);
                handleStatusError(error, enrollmentNumber);
            });
        }
        
        function displayStatusResult(data, enrollmentNumber) {
            const statusContent = document.getElementById('statusContent');
            
            if (data.success && data.data) {
                const enrollment = data.data;
                displaySuccessResult(enrollment);
            } else {
                displayNotFoundResult(enrollmentNumber);
            }
        }
        
        function displaySuccessResult(enrollment) {
            const statusContent = document.getElementById('statusContent');
            let statusBadge = '';
            let statusMessage = '';
            let statusIcon = '';
            let additionalInfo = '';
            
            // Debug logging
            console.log('Enrollment data:', enrollment);
            console.log('Status value:', enrollment.status);
            console.log('Status type:', typeof enrollment.status);
            
            switch (enrollment.status) {
                case 'pending':
                    statusBadge = '<span class="badge bg-warning text-dark fs-6"><i class="fas fa-clock me-1"></i>Pending Review</span>';
                    statusMessage = 'Your application is currently under review. You will be notified once a decision is made.';
                    statusIcon = '<i class="fas fa-hourglass-half text-warning" style="font-size: 3rem;"></i>';
                    additionalInfo = '<div class="alert alert-info mt-3"><i class="fas fa-info-circle me-2"></i>Review typically takes 3-5 business days.</div>';
                    break;
                case 'approved':
                    statusBadge = '<span class="badge bg-success fs-6"><i class="fas fa-check-circle me-1"></i>Approved</span>';
                    statusMessage = 'Congratulations! Your enrollment has been approved. Check your email for account details.';
                    statusIcon = '<i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>';
                    additionalInfo = '<div class="alert alert-success mt-3"><i class="fas fa-envelope me-2"></i>Login credentials have been sent to your email address.</div>';
                    break;
                case 'declined':
                    statusBadge = '<span class="badge bg-danger fs-6"><i class="fas fa-times-circle me-1"></i>Declined</span>';
                    statusMessage = `Your application was declined.`;
                    statusIcon = '<i class="fas fa-times-circle text-danger" style="font-size: 3rem;"></i>';
                    if (enrollment.declined_reason) {
                        additionalInfo = `<div class="alert alert-warning mt-3"><strong>Reason:</strong> ${enrollment.declined_reason}</div>`;
                    }
                    break;
                case 'enrolled':
                    statusBadge = '<span class="badge bg-primary fs-6"><i class="fas fa-graduation-cap me-1"></i>Enrolled</span>';
                    statusMessage = 'You are successfully enrolled! Welcome to our school.';
                    statusIcon = '<i class="fas fa-graduation-cap text-primary" style="font-size: 3rem;"></i>';
                    additionalInfo = '<div class="alert alert-success mt-3"><i class="fas fa-school me-2"></i>You can now access your student portal and class materials.</div>';
                    break;
                default:
                    statusBadge = '<span class="badge bg-secondary fs-6"><i class="fas fa-question-circle me-1"></i>Unknown</span>';
                    statusMessage = 'Status information is not available.';
                    statusIcon = '<i class="fas fa-question-circle text-secondary" style="font-size: 3rem;"></i>';
            }
            
            const submissionDate = new Date(enrollment.submission_date);
            const approvedDate = enrollment.approved_at ? new Date(enrollment.approved_at) : null;
            
            statusContent.innerHTML = `
                <div class="text-center">
                    ${statusIcon}
                    <h4 class="mt-3 mb-2">${enrollment.student_name || 'Student'}</h4>
                    <p class="text-muted mb-3">Enrollment Number: <strong class="text-primary">${enrollment.enrollment_number}</strong></p>
                    <div class="mb-4">${statusBadge}</div>
                    <p class="lead">${statusMessage}</p>
                    ${additionalInfo}
                    
                    <div class="row mt-4 text-start">
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body p-3">
                                    <h6 class="card-title mb-2"><i class="fas fa-graduation-cap text-primary me-2"></i>Grade Level</h6>
                                    <p class="card-text mb-0">${enrollment.grade_level || 'Not specified'}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body p-3">
                                    <h6 class="card-title mb-2"><i class="fas fa-calendar text-primary me-2"></i>Submitted</h6>
                                    <p class="card-text mb-0">${submissionDate.toLocaleDateString('en-US', { 
                                        year: 'numeric', 
                                        month: 'long', 
                                        day: 'numeric' 
                                    })}</p>
                                </div>
                            </div>
                        </div>
                        ${approvedDate ? `
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-2"><i class="fas fa-check text-success me-2"></i>Approved Date</h6>
                                        <p class="card-text mb-0">${approvedDate.toLocaleDateString('en-US', { 
                                            year: 'numeric', 
                                            month: 'long', 
                                            day: 'numeric' 
                                        })}</p>
                                    </div>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                </div>
            `;
        }
        
        function displayNotFoundResult(enrollmentNumber) {
            const statusContent = document.getElementById('statusContent');
            
            statusContent.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-search text-warning" style="font-size: 3rem;"></i>
                    <h4 class="mt-3 mb-3">Enrollment Not Found</h4>
                    <p class="text-muted mb-3">No enrollment record found with the number:</p>
                    <p class="h5 text-primary mb-4"><strong>${enrollmentNumber}</strong></p>
                    
                    <div class="alert alert-warning text-start">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Please verify:</h6>
                        <ul class="mb-0">
                            <li>The enrollment number is correct</li>
                            <li>The format is ENR-YYYYMMDD-XXXX</li>
                            <li>You have submitted an application</li>
                        </ul>
                    </div>
                    
                    <div class="mt-4">
                        <button type="button" class="btn btn-outline-primary me-2" onclick="retryStatusCheck('${enrollmentNumber}')">
                            <i class="fas fa-redo me-2"></i>Try Again
                        </button>
                        <button type="button" class="btn btn-primary" onclick="clearAndClose()">
                            <i class="fas fa-edit me-2"></i>Enter Different Number
                        </button>
                    </div>
                </div>
            `;
        }
        
        function handleStatusError(error, enrollmentNumber) {
            const statusContent = document.getElementById('statusContent');
            let errorMessage = 'An unexpected error occurred while checking the status.';
            let errorDetails = '';
            
            if (error.name === 'AbortError') {
                errorMessage = 'The request timed out. Please check your internet connection.';
                errorDetails = 'The server took too long to respond.';
            } else if (error.message.includes('HTTP 404')) {
                errorMessage = 'The enrollment service is temporarily unavailable.';
                errorDetails = 'Please try again in a few minutes.';
            } else if (error.message.includes('HTTP 500')) {
                errorMessage = 'Server error occurred while processing your request.';
                errorDetails = 'Our technical team has been notified.';
            } else if (!navigator.onLine) {
                errorMessage = 'No internet connection detected.';
                errorDetails = 'Please check your network connection and try again.';
            }
            
            statusContent.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-circle text-danger" style="font-size: 3rem;"></i>
                    <h4 class="mt-3 mb-3">Connection Error</h4>
                    <p class="text-muted mb-2">${errorMessage}</p>
                    ${errorDetails ? `<p class="small text-muted mb-4">${errorDetails}</p>` : ''}
                    
                    <div class="alert alert-danger text-start">
                        <h6><i class="fas fa-tools me-2"></i>Troubleshooting:</h6>
                        <ul class="mb-0">
                            <li>Check your internet connection</li>
                            <li>Refresh the page and try again</li>
                            <li>Contact support if the problem persists</li>
                        </ul>
                    </div>
                    
                    <div class="mt-4">
                        ${retryCount < maxRetries ? `
                            <button type="button" class="btn btn-primary me-2" onclick="retryStatusCheck('${enrollmentNumber}')">
                                <i class="fas fa-redo me-2"></i>Retry (${maxRetries - retryCount} attempts left)
                            </button>
                        ` : ''}
                        <button type="button" class="btn btn-outline-secondary" onclick="clearAndClose()">
                            <i class="fas fa-times me-2"></i>Close
                        </button>
                    </div>
                </div>
            `;
        }
        
        function retryStatusCheck(enrollmentNumber) {
            if (retryCount < maxRetries) {
                retryCount++;
                performStatusCheck(enrollmentNumber);
            }
        }
        
        function clearAndClose() {
            statusModal.hide();
            document.getElementById('enrollmentNumber').value = '';
            document.getElementById('enrollmentNumber').focus();
            clearValidationState();
            retryCount = 0;
        }
        
        // Accessibility: Focus management
        document.getElementById('statusModal').addEventListener('shown.bs.modal', function() {
            // Focus the modal for screen readers
            this.focus();
        });
        
        document.getElementById('statusModal').addEventListener('hidden.bs.modal', function() {
            // Return focus to the input field
            document.getElementById('enrollmentNumber').focus();
        });
    </script>
            </div>
        </div>
    </div>
</body>

</html>