    <?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('stylesheets') ?>
<!-- Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<style>
    /* Custom styles for image cropper */
    .img-container {
        overflow: hidden;
        position: relative;
        height: 400px;
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .cropper-container {
        max-height: 400px;
    }
    .cropper-control-panel {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 15px;
    }
    .aspect-ratio-buttons {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }
    .aspect-ratio-buttons .btn {
        flex: 1;
    }
    /* Form Wizard Styles */
    .form-wizard-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
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
        line-height: 50px;
        border-radius: 50%;
        background: #e9ecef;
        color: #6c757d;
        font-size: 18px;
        font-weight: 600;
        margin: 0 auto 10px;
        position: relative;
        z-index: 5;
        transition: all 0.3s ease;
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
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .wizard-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
    }
    
    /* Form validation styles */
    .is-invalid {
        border-color: #dc3545 !important;
    }
    
    /* Review step styles */
    .font-weight-medium {
        font-weight: 500;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Cropper.js Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    // Global variables
    let cropper;
    let imageElement;
    let currentStep = 1;
    const totalSteps = 3;
    
    // Function to load image for cropping
    function loadImageForCropping(event) {
        const file = event.target.files[0];
        if (!file) return;
        
        // Check if file is an image
        if (!file.type.match('image.*')) {
            alert('Please select an image file');
            return;
        }
        
        // Create a FileReader to read the image
        const reader = new FileReader();
        reader.onload = function(e) {
            // Get the image element
            imageElement = document.getElementById('image-to-crop');
            
            // Set the source of the image
            imageElement.src = e.target.result;
            
            // Show the cropper container
            document.getElementById('image-cropper-container').style.display = 'block';
            
            // Initialize cropper after image is loaded
            imageElement.onload = function() {
                // Destroy previous cropper if exists
                if (cropper) {
                    cropper.destroy();
                }
                
                // Initialize cropper
                cropper = new Cropper(imageElement, {
                    aspectRatio: 1, // Square aspect ratio for profile picture
                    viewMode: 1,
                    guides: true,
                    center: true,
                    dragMode: 'move',
                    zoomable: true,
                    zoomOnWheel: true,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: true
                });
            };
        };
        
        // Read the image file as a data URL
        reader.readAsDataURL(file);
    }
    
    // Function to crop and preview image
    function cropImage() {
        if (!cropper) return;
        
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
        
        // Populate review data when navigating to step 3
        if (stepNumber === 3) {
            populateReviewData();
        }
    }
    
    // Function to update navigation buttons
    function updateNavigationButtons() {
        document.querySelectorAll('.wizard-btn-prev, .wizard-btn-next, .wizard-btn-submit').forEach(btn => {
            btn.style.display = 'none';
        });
        
        if (currentStep > 1) {
            document.querySelectorAll('.wizard-btn-prev').forEach(btn => {
                btn.style.display = 'inline-block';
            });
        }
        
        if (currentStep < totalSteps) {
            document.querySelectorAll('.wizard-btn-next').forEach(btn => {
                btn.style.display = 'inline-block';
            });
        }
        
        if (currentStep === totalSteps) {
            document.querySelectorAll('.wizard-btn-submit').forEach(btn => {
                btn.style.display = 'inline-block';
            });
        }
    }
    
    // Function to populate review data
    function populateReviewData() {
        // Basic Information
        document.getElementById('review-first-name').textContent = document.getElementById('first_name').value || 'Not provided';
        document.getElementById('review-middle-name').textContent = document.getElementById('middle_name').value || 'Not provided';
        document.getElementById('review-last-name').textContent = document.getElementById('last_name').value || 'Not provided';
        document.getElementById('review-email').textContent = document.getElementById('email').value || 'Not provided';
        document.getElementById('review-employee-id').textContent = document.getElementById('employee_id').value || 'Not provided';
        
        // Personal Information
         document.getElementById('review-date-of-birth').textContent = document.getElementById('date_of_birth').value || 'Not provided';
         document.getElementById('review-age').textContent = document.getElementById('age').value || 'Not provided';
         document.getElementById('review-gender').textContent = document.getElementById('gender').value || 'Not provided';
         document.getElementById('review-contact-number').textContent = document.getElementById('contact_number').value || 'Not provided';
         document.getElementById('review-nationality').textContent = document.getElementById('nationality').value || 'Filipino';
        
        // Professional Information
        document.getElementById('review-position').textContent = document.getElementById('position').value || 'Not provided';
        document.getElementById('review-educational-attainment').textContent = document.getElementById('educational_attainment').value || 'Not provided';
        document.getElementById('review-prc-license').textContent = document.getElementById('prc_license_number').value || 'Not provided';
        document.getElementById('review-eligibility-status').textContent = document.getElementById('eligibility_status').value || 'Not provided';
        
        // Dropdown values
        const civilStatusSelect = document.getElementById('civil_status_id');
        const civilStatusText = civilStatusSelect.options[civilStatusSelect.selectedIndex]?.text || 'Not selected';
        document.getElementById('review-civil-status').textContent = civilStatusText;
        
        const employmentStatusSelect = document.getElementById('employment_status_id');
        const employmentStatusText = employmentStatusSelect.options[employmentStatusSelect.selectedIndex]?.text || 'Not selected';
        document.getElementById('review-employment-status').textContent = employmentStatusText;
        
         // Account status is automatically set to Active
         document.getElementById('review-account-status').textContent = 'Active';
         
         // Display profile picture if available
         const croppedImageData = document.getElementById('cropped_image_data').value;
         const reviewProfilePicture = document.getElementById('review-profile-picture');
         
         if (croppedImageData && croppedImageData.trim() !== '') {
             reviewProfilePicture.src = croppedImageData;
             reviewProfilePicture.style.display = 'block';
         } else {
             reviewProfilePicture.style.display = 'none';
         }
     }
    
    // Address checkbox functionality with animation
    function togglePermanentAddress() {
        const checkbox = document.getElementById('same_as_residential');
        const permanentSection = document.getElementById('permanent_address_section');
        
        if (checkbox.checked) {
            // Hide permanent address fields with animation
            permanentSection.style.transition = 'opacity 0.3s ease';
            permanentSection.style.opacity = '0';
            
            setTimeout(function() {
                permanentSection.style.display = 'none';
                
                // Copy residential address to permanent address fields
                copyResidentialToPermanent();
            }, 300);
        } else {
            // Show permanent address fields with animation
            permanentSection.style.display = 'block';
            permanentSection.style.opacity = '0';
            
            setTimeout(function() {
                permanentSection.style.opacity = '1';
            }, 10);
        }
    }
    
    // Function to copy residential address to permanent address
    function copyResidentialToPermanent() {
        const residentialFields = {
            'residential_street_address': 'permanent_street_address',
            'residential_barangay': 'permanent_barangay',
            'residential_city': 'permanent_city',
            'residential_province': 'permanent_province',
            'residential_postal_code': 'permanent_postal_code'
        };
        
        for (const [residential, permanent] of Object.entries(residentialFields)) {
            const residentialField = document.getElementById(residential);
            const permanentField = document.getElementById(permanent);
            
            if (residentialField && permanentField) {
                permanentField.value = residentialField.value;
            }
        }
    }
    
    // Specialization management
    let specializationIndex = 1;
    
    function addSpecialization() {
        const container = document.getElementById('specializations-container');
        const newSpecialization = document.createElement('div');
        newSpecialization.className = 'specialization-item border rounded p-3 mb-3';
        newSpecialization.setAttribute('data-index', specializationIndex);
        
        newSpecialization.innerHTML = `
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="font-weight-bold">Subject <span class="text-danger">*</span></label>
                    <select class="form-control" name="specializations[${specializationIndex}][subject_id]" required>
                        <option value="">Select Subject</option>
                        <?php if (isset($subjects) && !empty($subjects)): ?>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?= $subject['id'] ?>"><?= esc($subject['subject_name']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="font-weight-bold">Proficiency Level <span class="text-danger">*</span></label>
                    <select class="form-control" name="specializations[${specializationIndex}][proficiency_level]" required>
                        <option value="">Select Level</option>
                        <option value="Basic">Basic</option>
                        <option value="Intermediate" selected>Intermediate</option>
                        <option value="Advanced">Advanced</option>
                        <option value="Expert">Expert</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="font-weight-bold">Years Experience</label>
                    <input type="number" class="form-control" name="specializations[${specializationIndex}][years_experience]" 
                           min="0" max="50" placeholder="0">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="font-weight-bold">Primary</label>
                    <div class="form-check mt-2">
                        <input class="form-check-input primary-specialization" type="radio" 
                               name="primary_specialization" value="${specializationIndex}" id="primary_${specializationIndex}">
                        <label class="form-check-label" for="primary_${specializationIndex}">
                            Primary
                        </label>
                    </div>
                </div>
                <div class="col-md-1 mb-3 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-specialization">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        
        container.appendChild(newSpecialization);
        specializationIndex++;
        updateRemoveButtons();
    }
    
    function removeSpecialization(button) {
        const specializationItem = button.closest('.specialization-item');
        specializationItem.remove();
        updateRemoveButtons();
    }
    
    function updateRemoveButtons() {
        const items = document.querySelectorAll('.specialization-item');
        const removeButtons = document.querySelectorAll('.remove-specialization');
        
        removeButtons.forEach((button, index) => {
            if (items.length > 1) {
                button.style.display = 'block';
            } else {
                button.style.display = 'none';
            }
        });
    }
    
    // When document is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize wizard
        goToStep(1);
        
        // Next button click handler
        document.querySelectorAll('.wizard-btn-next').forEach(btn => {
            btn.addEventListener('click', function() {
                goToStep(currentStep + 1);
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
        
        // Address checkbox event listener
        const sameAsResidentialCheckbox = document.getElementById('same_as_residential');
        if (sameAsResidentialCheckbox) {
            sameAsResidentialCheckbox.addEventListener('change', togglePermanentAddress);
        }
        
        // Specialization management event listeners
        const addSpecializationBtn = document.getElementById('add-specialization');
        if (addSpecializationBtn) {
            addSpecializationBtn.addEventListener('click', addSpecialization);
        }
        
        // Remove specialization event delegation
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-specialization')) {
                removeSpecialization(e.target.closest('.remove-specialization'));
            }
        });
        
        // Initialize remove buttons visibility
        updateRemoveButtons();
        
        // Add form submit validation with detailed debugging
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                console.log('=== FORM SUBMISSION DEBUG ===');
                console.log('Form submit event triggered');
                console.log('Current step:', currentStep);
                console.log('Total steps:', totalSteps);
                console.log('Form action:', form.action);
                console.log('Form method:', form.method);
                
                // Check if we're on the final step
                if (currentStep !== totalSteps) {
                    console.log('ERROR: Not on final step, preventing submission');
                    console.log('Expected step:', totalSteps, 'Current step:', currentStep);
                    e.preventDefault();
                    return false;
                }
                
                // Check for required fields and HTML5 validation
                const requiredFields = form.querySelectorAll('[required]');
                let emptyFields = [];
                let invalidFields = [];
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        emptyFields.push(field.name || field.id);
                    } else if (!field.checkValidity()) {
                        invalidFields.push(field.name || field.id + ' (invalid format)');
                    }
                });
                
                if (emptyFields.length > 0) {
                    console.log('ERROR: Empty required fields:', emptyFields);
                    alert('Please fill in all required fields: ' + emptyFields.join(', '));
                    // Focus on first empty field
                    const firstEmptyField = form.querySelector('[name="' + emptyFields[0] + '"]');
                    if (firstEmptyField) firstEmptyField.focus();
                    e.preventDefault();
                    return false;
                }
                
                if (invalidFields.length > 0) {
                    console.log('ERROR: Invalid field formats:', invalidFields);
                    alert('Please check the format of these fields: ' + invalidFields.join(', '));
                    e.preventDefault();
                    return false;
                }
                
                console.log('SUCCESS: Form validation passed, submitting...');
                console.log('=== END DEBUG ===');
            });
        }
        
        // Add click handler to submit button for additional debugging
        const submitButton = document.querySelector('.wizard-btn-submit');
        if (submitButton) {
            submitButton.addEventListener('click', function(e) {
                console.log('=== SUBMIT BUTTON CLICKED ===');
                console.log('Button type:', this.type);
                console.log('Current step when clicked:', currentStep);
                console.log('Button visible:', this.style.display !== 'none');
            });
        }
    });
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Add New Teacher</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= route_to('admin.home') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('/admin/teacher') ?>">Teachers</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add New</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Error Messages -->
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4">Add New Teacher</h4>
        <p class="mb-0">Fill in the teacher's details using the step-by-step wizard below.</p>
    </div>
    <div class="p-4">
        <?php if (isset($validation) && $validation->getErrors()): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($validation->getErrors() as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Form Wizard Steps -->
        <div class="form-wizard-steps">
            <div class="wizard-step active" data-step="1">
                <div class="step-number">1</div>
                <div class="step-title">Teacher Information</div>
            </div>
            <div class="wizard-step" data-step="2">
                <div class="step-number">2</div>
                <div class="step-title">Profile Picture</div>
            </div>
            <div class="wizard-step" data-step="3">
                <div class="step-number">3</div>
                <div class="step-title">Review</div>
            </div>
        </div>

        <form action="<?= base_url('/admin/teacher/store') ?>" method="POST" enctype="multipart/form-data" novalidate>
            <?= csrf_field() ?>

            <!-- Step 1: Teacher Information -->
            <div id="step-1" class="form-wizard-content active">
                <!-- Personal Information Section -->
                <div class="row mb-5">
                    <div class="col-12"><h5 class="text-primary border-bottom pb-2 mb-4">Personal Information</h5></div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="first_name" class="font-weight-bold">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('first_name') ? 'is-invalid' : '' ?>" 
                               id="first_name" name="first_name" value="<?= old('first_name') ?>" required>
                        <?php if (isset($validation) && $validation->hasError('first_name')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('first_name') ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="middle_name" class="font-weight-bold">Middle Name</label>
                        <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?= old('middle_name') ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="last_name" class="font-weight-bold">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('last_name') ? 'is-invalid' : '' ?>" 
                               id="last_name" name="last_name" value="<?= old('last_name') ?>" required>
                        <?php if (isset($validation) && $validation->hasError('last_name')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('last_name') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="email" class="font-weight-bold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" 
                               id="email" name="email" value="<?= old('email') ?>" required>
                        <small class="form-text text-muted">This will be used for login</small>
                        <?php if (isset($validation) && $validation->hasError('email')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="employee_id" class="font-weight-bold">Employee ID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('employee_id') ? 'is-invalid' : '' ?>" 
                               id="employee_id" name="employee_id" value="<?= old('employee_id') ?>" required placeholder="DepEd Employee ID">
                        <small class="form-text text-muted">Enter the DepEd-provided Employee ID</small>
                        <?php if (isset($validation) && $validation->hasError('employee_id')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('employee_id') ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="contact_number" class="font-weight-bold">Contact Number</label>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('contact_number') ? 'is-invalid' : '' ?>" 
                               id="contact_number" name="contact_number" value="<?= old('contact_number') ?>" placeholder="09XXXXXXXXX">
                        <?php if (isset($validation) && $validation->hasError('contact_number')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('contact_number') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="date_of_birth" class="font-weight-bold">Date of Birth</label>
                        <input type="date" class="form-control <?= isset($validation) && $validation->hasError('date_of_birth') ? 'is-invalid' : '' ?>" 
                               id="date_of_birth" name="date_of_birth" value="<?= old('date_of_birth') ?>">
                        <?php if (isset($validation) && $validation->hasError('date_of_birth')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('date_of_birth') ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="age" class="font-weight-bold">Age</label>
                        <input type="number" class="form-control <?= isset($validation) && $validation->hasError('age') ? 'is-invalid' : '' ?>" 
                               id="age" name="age" value="<?= old('age') ?>" placeholder="Age (10-100)">
                        <small class="form-text text-muted">Leave empty if unknown (minimum 10 years)</small>
                        <?php if (isset($validation) && $validation->hasError('age')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('age') ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="gender" class="font-weight-bold">Gender <span class="text-danger">*</span></label>
                        <select class="form-control <?= isset($validation) && $validation->hasError('gender') ? 'is-invalid' : '' ?>" 
                                id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male" <?= old('gender') == 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= old('gender') == 'Female' ? 'selected' : '' ?>>Female</option>
                            <option value="Other" <?= old('gender') == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('gender')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('gender') ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="civil_status_id" class="font-weight-bold">Civil Status</label>
                        <select class="form-control <?= isset($validation) && $validation->hasError('civil_status_id') ? 'is-invalid' : '' ?>" 
                                id="civil_status_id" name="civil_status_id">
                            <option value="">Select Civil Status</option>
                            <?php if (isset($civil_statuses) && !empty($civil_statuses)): ?>
                                <?php foreach ($civil_statuses as $status): ?>
                                    <option value="<?= $status['id'] ?>" <?= old('civil_status_id') == $status['id'] ? 'selected' : '' ?>>
                                        <?= esc($status['status']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('civil_status_id')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('civil_status_id') ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="nationality" class="font-weight-bold">Nationality</label>
                        <input type="text" class="form-control" id="nationality" name="nationality" 
                               value="<?= old('nationality', 'Filipino') ?>" placeholder="Filipino">
                    </div>
                </div>
                
                <!-- Professional Information Section -->
                <div class="row mb-5">
                    <div class="col-12"><h5 class="text-primary border-bottom pb-2 mb-4">Professional Information</h5></div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="position" class="font-weight-bold">Position</label>
                        <input type="text" class="form-control" id="position" name="position" 
                               value="<?= old('position') ?>" placeholder="e.g., Senior High School Teacher">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="employment_status_id" class="font-weight-bold">Employment Status</label>
                        <select class="form-control <?= isset($validation) && $validation->hasError('employment_status_id') ? 'is-invalid' : '' ?>" 
                                id="employment_status_id" name="employment_status_id">
                            <option value="">Select Employment Status</option>
                            <?php if (isset($employment_statuses) && !empty($employment_statuses)): ?>
                                <?php foreach ($employment_statuses as $status): ?>
                                    <option value="<?= $status['id'] ?>" <?= old('employment_status_id') == $status['id'] ? 'selected' : '' ?>>
                                        <?= esc($status['status']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('employment_status_id')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('employment_status_id') ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="educational_attainment" class="font-weight-bold">Educational Attainment</label>
                        <input type="text" class="form-control" id="educational_attainment" name="educational_attainment" 
                               value="<?= old('educational_attainment') ?>" placeholder="e.g., Bachelor of Science in Education">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="prc_license_number" class="font-weight-bold">PRC License Number</label>
                        <input type="text" class="form-control" id="prc_license_number" name="prc_license_number" 
                               value="<?= old('prc_license_number') ?>" placeholder="Professional License Number">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="eligibility_status" class="font-weight-bold">Eligibility Status</label>
                        <input type="text" class="form-control" id="eligibility_status" name="eligibility_status" 
                               value="<?= old('eligibility_status') ?>" placeholder="e.g., LET Passer, Civil Service Eligible">
                    </div>

                </div>
                
                <!-- Address Information Section -->
                <div class="row mb-5">
                    <div class="col-12"><h5 class="text-primary border-bottom pb-2 mb-4">Address Information</h5></div>
                    
                    <!-- Residential Address -->
                    <div class="col-12 mb-4">
                        <h6 class="text-secondary mb-3">Residential Address</h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="residential_street_address" class="font-weight-bold">Street Address <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="residential_street_address" name="residential_street_address" 
                                         rows="2" placeholder="House/Unit Number, Street Name" required><?= old('residential_street_address') ?></textarea>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="residential_barangay" class="font-weight-bold">Barangay</label>
                                <input type="text" class="form-control" id="residential_barangay" name="residential_barangay" 
                                       value="<?= old('residential_barangay') ?>" placeholder="Barangay">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="residential_city" class="font-weight-bold">City/Municipality</label>
                                <input type="text" class="form-control" id="residential_city" name="residential_city" 
                                       value="<?= old('residential_city') ?>" placeholder="City/Municipality">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="residential_province" class="font-weight-bold">Province</label>
                                <input type="text" class="form-control" id="residential_province" name="residential_province" 
                                       value="<?= old('residential_province') ?>" placeholder="Province">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="residential_postal_code" class="font-weight-bold">Postal Code</label>
                                <input type="text" class="form-control" id="residential_postal_code" name="residential_postal_code" 
                                       value="<?= old('residential_postal_code') ?>" placeholder="Postal Code">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Permanent Address -->
                    <div class="col-12 mb-3">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="same_as_residential" name="same_as_residential" 
                                   <?= old('same_as_residential') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="same_as_residential">
                                Permanent address is the same as residential address
                            </label>
                        </div>
                        
                        <div id="permanent_address_section" style="<?= old('same_as_residential') ? 'display: none;' : '' ?>">
                            <h6 class="text-secondary mb-3">Permanent Address</h6>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="permanent_street_address" class="font-weight-bold">Street Address</label>
                                    <textarea class="form-control" id="permanent_street_address" name="permanent_street_address" 
                                             rows="2" placeholder="House/Unit Number, Street Name"><?= old('permanent_street_address') ?></textarea>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="permanent_barangay" class="font-weight-bold">Barangay</label>
                                    <input type="text" class="form-control" id="permanent_barangay" name="permanent_barangay" 
                                           value="<?= old('permanent_barangay') ?>" placeholder="Barangay">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="permanent_city" class="font-weight-bold">City/Municipality</label>
                                    <input type="text" class="form-control" id="permanent_city" name="permanent_city" 
                                           value="<?= old('permanent_city') ?>" placeholder="City/Municipality">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="permanent_province" class="font-weight-bold">Province</label>
                                    <input type="text" class="form-control" id="permanent_province" name="permanent_province" 
                                           value="<?= old('permanent_province') ?>" placeholder="Province">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="permanent_postal_code" class="font-weight-bold">Postal Code</label>
                                    <input type="text" class="form-control" id="permanent_postal_code" name="permanent_postal_code" 
                                           value="<?= old('permanent_postal_code') ?>" placeholder="Postal Code">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Specialization Information Section -->
                <div class="row mb-5">
                    <div class="col-12"><h5 class="text-primary border-bottom pb-2 mb-4">Subject Specializations</h5></div>
                    
                    <div class="col-12 mb-3">
                        <p class="text-muted">Add the subjects this teacher specializes in. You can add multiple specializations.</p>
                        
                        <div id="specializations-container">
                            <div class="specialization-item border rounded p-3 mb-3" data-index="0">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="font-weight-bold">Subject <span class="text-danger">*</span></label>
                                        <select class="form-control" name="specializations[0][subject_id]" required>
                                            <option value="">Select Subject</option>
                                            <?php if (isset($subjects) && !empty($subjects)): ?>
                                                <?php foreach ($subjects as $subject): ?>
                                                    <option value="<?= $subject['id'] ?>"><?= esc($subject['subject_name']) ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="font-weight-bold">Proficiency Level <span class="text-danger">*</span></label>
                                        <select class="form-control" name="specializations[0][proficiency_level]" required>
                                            <option value="">Select Level</option>
                                            <option value="Basic">Basic</option>
                                            <option value="Intermediate" selected>Intermediate</option>
                                            <option value="Advanced">Advanced</option>
                                            <option value="Expert">Expert</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="font-weight-bold">Years Experience</label>
                                        <input type="number" class="form-control" name="specializations[0][years_experience]" 
                                               min="0" max="50" placeholder="0">
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="font-weight-bold">Primary</label>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input primary-specialization" type="radio" 
                                                   name="primary_specialization" value="0" id="primary_0">
                                            <label class="form-check-label" for="primary_0">
                                                Primary
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-1 mb-3 d-flex align-items-end">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-specialization" 
                                                style="display: none;">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-specialization">
                            <i class="fa fa-plus mr-2"></i> Add Another Specialization
                        </button>

                    </div>
                </div>
                
                <!-- Step 1 Navigation -->
                <div class="wizard-buttons mt-4 d-flex justify-content-end">
                    <a href="<?= site_url('/admin/teacher') ?>" class="btn btn-outline-secondary px-4 mr-2">
                        <i class="fa fa-arrow-left mr-2"></i> Cancel
                    </a>
                    <button type="button" class="btn btn-primary wizard-btn-next px-4">
                        Next <i class="fa fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- Step 2: Profile Picture -->
             <div id="step-2" class="form-wizard-content">
                 <div class="row">
                     <div class="col-12 mb-4">
                         <h5 class="text-primary border-bottom pb-2 mb-4">Profile Picture</h5>
                         <p class="text-muted">Upload a profile picture for the teacher (optional). You can crop and adjust the image as needed.</p>
                     </div>
                     <div class="col-md-12 mb-3">
                         <label for="profile_picture" class="font-weight-bold">Select Image</label>
                         <input type="file" id="profile_picture" name="profile_picture" class="form-control-file mt-2" accept="image/*" onchange="loadImageForCropping(event)">
                         <small class="form-text text-muted mt-1">Recommended size: 300x300 pixels, maximum file size: 2MB</small>
                         <?php if (isset($validation) && $validation->hasError('profile_picture')): ?>
                             <div class="invalid-feedback"><?= $validation->getError('profile_picture') ?></div>
                         <?php endif; ?>
                     </div>
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
                                 <div class="aspect-ratio-buttons">
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
                 
                 <!-- Step 2 Navigation -->
                 <div class="wizard-buttons mt-4 d-flex justify-content-end">
                     <button type="button" class="btn btn-outline-secondary wizard-btn-prev px-4 mr-2">
                         <i class="fa fa-arrow-left mr-2"></i> Previous
                     </button>
                     <button type="button" class="btn btn-primary wizard-btn-next px-4">
                         Next <i class="fa fa-arrow-right ml-2"></i>
                     </button>
                 </div>
             </div>

            <!-- Step 3: Review -->
            <div id="step-3" class="form-wizard-content">
                <div class="row mb-5">
                    <div class="col-12"><h5 class="text-primary border-bottom pb-2 mb-4">Review Information</h5></div>
                    <div class="col-12">
                        <p class="text-muted mb-4">Please review all the information below before submitting.</p>
                        
                        <!-- Profile Picture Review -->
                        <div class="row mb-4">
                            <div class="col-12 d-flex justify-content-center">
                                <div id="review-profile-picture-container">
                                    <img id="review-profile-picture" src="" alt="Profile Picture" 
                                         style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 3px solid #dee2e6; display: none;">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Basic Information Review -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Personal Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>First Name:</strong> <span id="review-first-name">-</span></p>
                                        <p><strong>Middle Name:</strong> <span id="review-middle-name">-</span></p>
                                        <p><strong>Last Name:</strong> <span id="review-last-name">-</span></p>
                                        <p><strong>Email:</strong> <span id="review-email">-</span></p>
                                        <p><strong>Employee ID:</strong> <span id="review-employee-id">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Date of Birth:</strong> <span id="review-date-of-birth">-</span></p>
                                        <p><strong>Age:</strong> <span id="review-age">-</span></p>
                                        <p><strong>Gender:</strong> <span id="review-gender">-</span></p>
                                        <p><strong>Contact Number:</strong> <span id="review-contact-number">-</span></p>
                                        <p><strong>Civil Status:</strong> <span id="review-civil-status">-</span></p>
                                        <p><strong>Nationality:</strong> <span id="review-nationality">-</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Professional Information Review -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Professional Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Position:</strong> <span id="review-position">-</span></p>
                                        <p><strong>Employment Status:</strong> <span id="review-employment-status">-</span></p>
                                        <p><strong>Educational Attainment:</strong> <span id="review-educational-attainment">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>PRC License Number:</strong> <span id="review-prc-license">-</span></p>
                                        <p><strong>Eligibility Status:</strong> <span id="review-eligibility-status">-</span></p>
                                        <p><strong>Account Status:</strong> <span id="review-account-status">-</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Auto-generated Information Notice -->
                        <div class="alert alert-info">
                            <h6><i class="icon-copy dw dw-info"></i> Auto-generated Information</h6>
                            <ul class="mb-0">
    <li><strong>Account Number:</strong> Will be automatically generated in format TCHYYYY0001</li>
                                <li><strong>Password:</strong> A secure password will be generated and displayed after creation</li>
                                <li><strong>User Account:</strong> Login credentials will be automatically created for the teacher</li>
                            </ul>
                        </div>
                        
                        <!-- Email Notification Notice -->
                        <div class="alert alert-success">
                            <h6><i class="icon-copy dw dw-email"></i> Automatic Email Notification</h6>
                            <ul class="mb-0">
                                <li><strong>Welcome Email:</strong> Login credentials will be automatically sent to the teacher's email address</li>
                                <li><strong>Email Contents:</strong> Account number, password, and login instructions</li>
                                <li><strong>Security:</strong> Teacher will be advised to change password after first login</li>
                                <li><strong>Backup:</strong> Credentials will also be displayed to you for manual sharing if email fails</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Step 3 Navigation -->
                    <div class="wizard-buttons mt-4 d-flex justify-content-end">
                        <button type="button" class="btn btn-outline-secondary wizard-btn-prev px-4 mr-2">
                            <i class="fa fa-arrow-left mr-2"></i> Previous
                        </button>
                        <button type="submit" class="btn btn-success wizard-btn-submit px-4">
                            <i class="icon-copy dw dw-check mr-2"></i> Create Teacher
                        </button>
                    </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
