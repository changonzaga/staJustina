<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('stylesheets') ?>
<!-- Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="/backend/src/plugins/sweetalert2/sweetalert2.css">
<style>
    /* Custom styles for image cropper */
    .img-container {
        overflow: hidden;
        position: relative;
        height: 500px;
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .cropper-container {
        max-height: 500px;
    }
    .cropper-control-panel {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 15px;
    }
    .cropper-data-group {
        margin-bottom: 15px;
    }
    .cropper-data-group label {
        font-size: 0.8rem;
        color: #666;
        margin-bottom: 2px;
    }
    .cropper-data-input {
        width: 100%;
        margin-bottom: 8px;
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
    
    #review-profile-picture-container {
        text-align: center;
    }
    
    #review-profile-picture {
        border: 1px solid #dee2e6;
        padding: 3px;
        background-color: #fff;
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
    
    // Function to update crop box data inputs
    function updateCropBoxData() {
        if (!cropper) return;
        
        const data = cropper.getData();
        document.getElementById('data-x').value = Math.round(data.x);
        document.getElementById('data-y').value = Math.round(data.y);
        document.getElementById('data-width').value = Math.round(data.width);
        document.getElementById('data-height').value = Math.round(data.height);
        document.getElementById('data-rotate').value = Math.round(data.rotate);
        document.getElementById('data-scale-x').value = data.scaleX.toFixed(2);
        document.getElementById('data-scale-y').value = data.scaleY.toFixed(2);
    }
    
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
            
            // Hide the preview if it was shown before
            document.getElementById('cropped-image-preview').style.display = 'none';
            
            // Initialize cropper after image is loaded
            imageElement.onload = function() {
                // Destroy previous cropper if exists
                if (cropper) {
                    cropper.destroy();
                }
                
                // Initialize cropper
                cropper = new Cropper(imageElement, {
                    aspectRatio: 1, // Square aspect ratio for profile picture
                    viewMode: 1,     // Restrict the crop box to not exceed the size of the canvas
                    guides: true,    // Show the dashed lines for guiding
                    center: true,    // Show the center indicator for guiding
                    dragMode: 'move',// Define the dragging mode of the cropper
                    zoomable: true,  // Enable to zoom the image
                    zoomOnWheel: true,// Enable to zoom the image by wheeling mouse
                    cropBoxMovable: true,// Enable to move the crop box
                    cropBoxResizable: true,// Enable to resize the crop box
                    toggleDragModeOnDblclick: true,// Toggle drag mode between "crop" and "move" when double click on the cropper
                    ready: updateCropBoxData,
                    crop: updateCropBoxData
                });
            };
        };
        
        // Read the image file as a data URL
        reader.readAsDataURL(file);
    }
    
    // Function to navigate to a specific step
    function goToStep(stepNumber) {
        // Force step number to be an integer
        stepNumber = parseInt(stepNumber, 10);
        
        // Validate step number
        if (stepNumber < 1 || stepNumber > totalSteps) {
            return;
        }
        
        // Validate form fields if moving forward
        if (stepNumber > currentStep) {
            if (stepNumber === 2 && !validateParentInfoStep()) {
                return;
            }
            
            if (stepNumber === 3 && !validateProfilePictureStep()) {
                return;
            }
        }
        
        // Hide all steps
        document.querySelectorAll('.form-wizard-content').forEach(step => {
            step.classList.remove('active');
        });
        
        // Show the target step
        document.getElementById(`step-${stepNumber}`).classList.add('active');
        
        // Update step indicators
        document.querySelectorAll('.wizard-step').forEach(step => {
            step.classList.remove('active', 'completed');
        });
        
        // Mark completed steps
        for (let i = 1; i < stepNumber; i++) {
            document.querySelector(`.wizard-step[data-step="${i}"]`).classList.add('completed');
        }
        
        // Mark current step as active
        document.querySelector(`.wizard-step[data-step="${stepNumber}"]`).classList.add('active');
        
        // Update current step
        currentStep = stepNumber;
        
        // If navigating to review step, populate review data
        if (stepNumber === 3) {
            // Use setTimeout to ensure the DOM is updated before populating review data
            setTimeout(() => {
                populateReviewData();
            }, 50);
        }
        
        // Show/hide navigation buttons based on current step
        updateNavigationButtons();
    }
    
    // Function to validate parent information step
    function validateParentInfoStep() {
        let isValid = true;
        const requiredFields = ['first_name', 'last_name', 'relationship_type', 'student_id', 'contact_number'];
        
        requiredFields.forEach(field => {
            const input = document.getElementById(field);
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            alert('Please fill in all required fields correctly before proceeding.');
        }
        
        return isValid;
    }
    
    // Function to validate profile picture step
    function validateProfilePictureStep() {
        // Profile picture is optional, so always return true
        return true;
    }
    
    // Function to update navigation buttons
    function updateNavigationButtons() {
        // Hide all navigation buttons first
        document.querySelectorAll('.wizard-btn-prev, .wizard-btn-next, .wizard-btn-submit').forEach(btn => {
            btn.style.display = 'none';
        });
        
        // Show appropriate buttons based on current step
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
        try {
            // Force focus on a form field to ensure all values are committed
            document.body.focus();
            
            // Get form values
            const nameInput = document.getElementById('name');
            const contactInput = document.getElementById('contact');
            
            // Set review values
            document.getElementById('review-name').textContent = nameInput ? nameInput.value : 'Not provided';
            document.getElementById('review-contact').textContent = contactInput ? contactInput.value : 'Not provided';
            
            // Check if profile picture is provided
            const croppedImageData = document.getElementById('cropped_image_data').value;
            if (croppedImageData) {
                document.getElementById('review-profile-picture').src = croppedImageData;
                document.getElementById('review-profile-picture').style.display = 'block';
                document.querySelector('#review-profile-picture-container p.text-muted').style.display = 'none';
            } else {
                document.getElementById('review-profile-picture').style.display = 'none';
                document.querySelector('#review-profile-picture-container p.text-muted').style.display = 'block';
            }
        } catch (error) {
            console.error('Error populating review data:', error);
        }
    }
    
    // Document ready function
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the form wizard
        goToStep(1);
        
        // Add event listeners for navigation buttons
        document.querySelectorAll('.wizard-btn-next').forEach(btn => {
            btn.addEventListener('click', function() {
                goToStep(currentStep + 1);
            });
        });
        
        document.querySelectorAll('.wizard-btn-prev').forEach(btn => {
            btn.addEventListener('click', function() {
                goToStep(currentStep - 1);
            });
        });
        
        // Add event listener for password confirmation
        document.getElementById('password_confirm').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
        
        // Add event listener for profile picture input
        document.getElementById('profile_picture').addEventListener('change', loadImageForCropping);
        
        // Add event listeners for cropper control buttons
        document.getElementById('zoom-in').addEventListener('click', function() {
            if (!cropper) return;
            cropper.zoom(0.1);
        });
        
        document.getElementById('zoom-out').addEventListener('click', function() {
            if (!cropper) return;
            cropper.zoom(-0.1);
        });
        
        document.getElementById('rotate-left').addEventListener('click', function() {
            if (!cropper) return;
            cropper.rotate(-45);
        });
        
        document.getElementById('rotate-right').addEventListener('click', function() {
            if (!cropper) return;
            cropper.rotate(45);
        });
        
        document.getElementById('flip-horizontal').addEventListener('click', function() {
            if (!cropper) return;
            cropper.scaleX(-cropper.getData().scaleX || -1);
        });
        
        document.getElementById('flip-vertical').addEventListener('click', function() {
            if (!cropper) return;
            cropper.scaleY(-cropper.getData().scaleY || -1);
        });
        
        document.getElementById('reset-crop').addEventListener('click', function() {
            if (!cropper) return;
            cropper.reset();
        });
        
        document.getElementById('aspect-1-1').addEventListener('click', function() {
            if (!cropper) return;
            cropper.setAspectRatio(1);
        });
        
        document.getElementById('aspect-4-3').addEventListener('click', function() {
            if (!cropper) return;
            cropper.setAspectRatio(4/3);
        });
        
        document.getElementById('aspect-16-9').addEventListener('click', function() {
            if (!cropper) return;
            cropper.setAspectRatio(16/9);
        });
        
        document.getElementById('aspect-free').addEventListener('click', function() {
            if (!cropper) return;
            cropper.setAspectRatio(NaN);
        });
        
        // Crop button click event
        document.getElementById('crop-image').addEventListener('click', function() {
            if (!cropper) {
                return;
            }
            
            // Get the cropped canvas
            const canvas = cropper.getCroppedCanvas({
                width: 300,  // Output image width
                height: 300, // Output image height
                minWidth: 100,
                minHeight: 100,
                maxWidth: 1000,
                maxHeight: 1000,
                fillColor: '#fff',
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });
            
            if (!canvas) {
                return;
            }
            
            // Convert canvas to data URL
            const croppedImageData = canvas.toDataURL('image/jpeg', 0.8);
            
            // Set the value of the hidden input
            document.getElementById('cropped_image_data').value = croppedImageData;
            
            // Show the preview
            document.getElementById('cropped-image-preview').style.display = 'block';
            document.getElementById('cropped-preview').src = croppedImageData;
            
            // Also update the review picture if we're already on step 3
            if (currentStep === 3) {
                const reviewPicture = document.getElementById('review-profile-picture');
                if (reviewPicture) {
                    reviewPicture.src = croppedImageData;
                    reviewPicture.style.display = 'block';
                    
                    const noImageText = document.querySelector('#review-profile-picture-container p.text-muted');
                    if (noImageText) {
                        noImageText.style.display = 'none';
                    }
                }
            }
            
            // Hide the cropper
            document.getElementById('image-cropper-container').style.display = 'none';
        });
        
        // Cancel button click event
        document.getElementById('cancel-crop').addEventListener('click', function() {
            // Hide the cropper
            document.getElementById('image-cropper-container').style.display = 'none';
            
            // Clear the file input
            document.getElementById('profile_picture').value = '';
            
            // Destroy the cropper
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            
            // Hide the preview if it was shown
            document.getElementById('cropped-image-preview').style.display = 'none';
            
            // Clear data inputs
            document.getElementById('data-x').value = '';
            document.getElementById('data-y').value = '';
            document.getElementById('data-width').value = '';
            document.getElementById('data-height').value = '';
            document.getElementById('data-rotate').value = '';
            document.getElementById('data-scale-x').value = '';
            document.getElementById('data-scale-y').value = '';
        });
        
        // Form validation on submit
        document.querySelector('form').addEventListener('submit', function(e) {
            // Final validation before submission
            if (!validateParentInfoStep()) {
                e.preventDefault();
                goToStep(1);
                alert('Please complete all required parent information fields before submitting. Make sure passwords match and are at least 6 characters long.');
            }
        });
    });

</script>
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Add New Parent</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= route_to('admin.home') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= route_to('admin.parent') ?>">Parents</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Parent</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4">Add New Parent</h4>
    </div>
    <div class="p-4">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Form Wizard Steps -->
        <div class="form-wizard-steps">
            <div class="wizard-step active" data-step="1">
                <div class="step-number">1</div>
                <div class="step-title">Parent Information</div>
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

        <form action="<?= route_to('admin.parent.store') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- Step 1: Parent Information -->
            <div id="step-1" class="form-wizard-content active">
                <!-- Basic Information Section -->
                <div class="row mb-5">
                    <div class="col-12"><h5 class="text-primary border-bottom pb-2 mb-4">Basic Information</h5></div>
                    <div class="col-md-4 mb-3">
                        <label for="first_name" class="font-weight-bold">First Name <span class="text-danger">*</span></label>
                        <input type="text" id="first_name" name="first_name" value="<?= old('first_name') ?>" class="form-control mt-2" required>
                        <div class="invalid-feedback">Please enter a valid first name.</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="middle_name" class="font-weight-bold">Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name" value="<?= old('middle_name') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="last_name" class="font-weight-bold">Last Name <span class="text-danger">*</span></label>
                        <input type="text" id="last_name" name="last_name" value="<?= old('last_name') ?>" class="form-control mt-2" required>
                        <div class="invalid-feedback">Please enter a valid last name.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="suffix" class="font-weight-bold">Suffix</label>
                        <input type="text" id="suffix" name="suffix" value="<?= old('suffix') ?>" class="form-control mt-2" placeholder="Jr., Sr., III, etc.">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="relationship_type" class="font-weight-bold">Relationship to Student <span class="text-danger">*</span></label>
                        <select id="relationship_type" name="relationship_type" class="form-control mt-2" required>
                            <option value="">Select Relationship</option>
                            <option value="Mother" <?= old('relationship_type') == 'Mother' ? 'selected' : '' ?>>Mother</option>
                            <option value="Father" <?= old('relationship_type') == 'Father' ? 'selected' : '' ?>>Father</option>
                            <option value="Guardian" <?= old('relationship_type') == 'Guardian' ? 'selected' : '' ?>>Guardian</option>
                            <option value="Grandmother" <?= old('relationship_type') == 'Grandmother' ? 'selected' : '' ?>>Grandmother</option>
                            <option value="Grandfather" <?= old('relationship_type') == 'Grandfather' ? 'selected' : '' ?>>Grandfather</option>
                            <option value="Aunt" <?= old('relationship_type') == 'Aunt' ? 'selected' : '' ?>>Aunt</option>
                            <option value="Uncle" <?= old('relationship_type') == 'Uncle' ? 'selected' : '' ?>>Uncle</option>
                            <option value="Other" <?= old('relationship_type') == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                        <div class="invalid-feedback">Please select a relationship type.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="student_id" class="font-weight-bold">Student <span class="text-danger">*</span></label>
                        <select id="student_id" name="student_id" class="form-control mt-2" required>
                            <option value="">Select Student</option>
                            <?php if(isset($students) && !empty($students)): ?>
                                <?php foreach($students as $student): ?>
                                    <option value="<?= $student['id'] ?>" <?= old('student_id') == $student['id'] ? 'selected' : '' ?>>
                                        <?= esc($student['first_name'] . ' ' . $student['last_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="invalid-feedback">Please select a student.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="date_of_birth" class="font-weight-bold">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="<?= old('date_of_birth') ?>" class="form-control mt-2">
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="row mb-5">
                    <div class="col-12"><h5 class="text-primary border-bottom pb-2 mb-4">Contact Information</h5></div>
                    <div class="col-md-6 mb-3">
                        <label for="contact_number" class="font-weight-bold">Primary Contact Number <span class="text-danger">*</span></label>
                        <input type="text" id="contact_number" name="contact_number" value="<?= old('contact_number') ?>" class="form-control mt-2" required>
                        <div class="invalid-feedback">Please enter a valid contact number.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone_secondary" class="font-weight-bold">Secondary Contact Number</label>
                        <input type="text" id="phone_secondary" name="phone_secondary" value="<?= old('phone_secondary') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="facebook_account" class="font-weight-bold">Facebook Account</label>
                        <input type="text" id="facebook_account" name="facebook_account" value="<?= old('facebook_account') ?>" class="form-control mt-2" placeholder="Facebook username or URL">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="address" class="font-weight-bold">Home Address</label>
                        <textarea id="address" name="address" class="form-control mt-2" rows="3" placeholder="Enter complete home address"><?= old('address') ?></textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="other_social_media" class="font-weight-bold">Other Social Media</label>
                        <textarea id="other_social_media" name="other_social_media" class="form-control mt-2" rows="2" placeholder="Instagram, Twitter, etc."><?= old('other_social_media') ?></textarea>
                    </div>
                </div>

                <!-- Work & Income Information -->
                <div class="row mb-5">
                    <div class="col-12"><h5 class="text-primary border-bottom pb-2 mb-4">Work & Income Information</h5></div>
                    <div class="col-md-6 mb-3">
                        <label for="occupation" class="font-weight-bold">Occupation</label>
                        <input type="text" id="occupation" name="occupation" value="<?= old('occupation') ?>" class="form-control mt-2" placeholder="Enter occupation">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="employer" class="font-weight-bold">Employer</label>
                        <input type="text" id="employer" name="employer" value="<?= old('employer') ?>" class="form-control mt-2" placeholder="Company/Organization name">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="monthly_income" class="font-weight-bold">Monthly Income</label>
                        <input type="number" id="monthly_income" name="monthly_income" value="<?= old('monthly_income') ?>" class="form-control mt-2" placeholder="0.00" step="0.01">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="educational_attainment" class="font-weight-bold">Educational Attainment</label>
                        <select id="educational_attainment" name="educational_attainment" class="form-control mt-2">
                            <option value="">Select Educational Level</option>
                            <option value="Elementary" <?= old('educational_attainment') == 'Elementary' ? 'selected' : '' ?>>Elementary</option>
                            <option value="High School" <?= old('educational_attainment') == 'High School' ? 'selected' : '' ?>>High School</option>
                            <option value="College" <?= old('educational_attainment') == 'College' ? 'selected' : '' ?>>College</option>
                            <option value="Vocational" <?= old('educational_attainment') == 'Vocational' ? 'selected' : '' ?>>Vocational</option>
                            <option value="Graduate" <?= old('educational_attainment') == 'Graduate' ? 'selected' : '' ?>>Graduate</option>
                            <option value="Post Graduate" <?= old('educational_attainment') == 'Post Graduate' ? 'selected' : '' ?>>Post Graduate</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="work_address" class="font-weight-bold">Work Address</label>
                        <textarea id="work_address" name="work_address" class="form-control mt-2" rows="3" placeholder="Enter work address"><?= old('work_address') ?></textarea>
                    </div>
                </div>

                <!-- Emergency Contact & Relationship Details -->
                <div class="row mb-5">
                    <div class="col-12"><h5 class="text-primary border-bottom pb-2 mb-4">Relationship Details</h5></div>
                    <!-- Note: Emergency contact fields removed - now handled via parent relationships -->
                    <div class="col-md-3 mb-3">
                        <label for="is_primary_contact" class="font-weight-bold">Is Primary Contact?</label>
                        <select id="is_primary_contact" name="is_primary_contact" class="form-control mt-2">
                            <option value="0" <?= old('is_primary_contact', '0') == '0' ? 'selected' : '' ?>>No</option>
                            <option value="1" <?= old('is_primary_contact') == '1' ? 'selected' : '' ?>>Yes</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="living_with_student" class="font-weight-bold">Living with Student?</label>
                        <select id="living_with_student" name="living_with_student" class="form-control mt-2">
                            <option value="0" <?= old('living_with_student', '0') == '0' ? 'selected' : '' ?>>No</option>
                            <option value="1" <?= old('living_with_student') == '1' ? 'selected' : '' ?>>Yes</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="custody_rights" class="font-weight-bold">Has Custody Rights?</label>
                        <select id="custody_rights" name="custody_rights" class="form-control mt-2">
                            <option value="0" <?= old('custody_rights', '0') == '0' ? 'selected' : '' ?>>No</option>
                            <option value="1" <?= old('custody_rights') == '1' ? 'selected' : '' ?>>Yes</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="authorized_pickup" class="font-weight-bold">Authorized for Pickup?</label>
                        <select id="authorized_pickup" name="authorized_pickup" class="form-control mt-2">
                            <option value="0" <?= old('authorized_pickup', '0') == '0' ? 'selected' : '' ?>>No</option>
                            <option value="1" <?= old('authorized_pickup') == '1' ? 'selected' : '' ?>>Yes</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Step 2: Profile Picture -->
            <div id="step-2" class="form-wizard-content">
                <div class="row">
                    <div class="col-12 mb-4">
                        <h5 class="text-primary border-bottom pb-2 mb-4">Profile Picture</h5>
                        <p class="text-muted">Upload a profile picture for the parent. This is optional but recommended.</p>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="profile_picture" class="font-weight-bold">Select Image</label>
                            <input type="file" id="profile_picture" name="profile_picture" class="form-control-file mt-2" accept="image/*">
                            <small class="form-text text-muted">Recommended size: 300x300 pixels. Max file size: 2MB.</small>
                        </div>
                        
                        <!-- Cropped Image Preview -->
                        <div id="cropped-image-preview" style="display: none; margin-top: 20px;">
                            <h6 class="mb-3">Preview:</h6>
                            <img id="cropped-preview" src="" alt="Cropped Preview" style="max-width: 100%; max-height: 300px; border: 1px solid #ddd; border-radius: 4px;">
                        </div>
                    </div>
                    
                    <!-- Image Cropper Container -->
                    <div id="image-cropper-container" class="col-12" style="display: none; margin-top: 30px;">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="img-container">
                                    <img id="image-to-crop" src="" alt="Image to crop">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="cropper-control-panel">
                                    <h6 class="mb-3">Cropper Controls</h6>
                                    
                                    <!-- Zoom Controls -->
                                    <div class="mb-3">
                                        <label class="d-block mb-2">Zoom:</label>
                                        <div class="btn-group w-100">
                                            <button type="button" id="zoom-in" class="btn btn-sm btn-outline-primary"><i class="icon-copy fa fa-search-plus" aria-hidden="true"></i></button>
                                            <button type="button" id="zoom-out" class="btn btn-sm btn-outline-primary"><i class="icon-copy fa fa-search-minus" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                    
                                    <!-- Rotation Controls -->
                                    <div class="mb-3">
                                        <label class="d-block mb-2">Rotate:</label>
                                        <div class="btn-group w-100">
                                            <button type="button" id="rotate-left" class="btn btn-sm btn-outline-primary"><i class="icon-copy fa fa-rotate-left" aria-hidden="true"></i></button>
                                            <button type="button" id="rotate-right" class="btn btn-sm btn-outline-primary"><i class="icon-copy fa fa-rotate-right" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                    
                                    <!-- Flip Controls -->
                                    <div class="mb-3">
                                        <label class="d-block mb-2">Flip:</label>
                                        <div class="btn-group w-100">
                                            <button type="button" id="flip-horizontal" class="btn btn-sm btn-outline-primary"><i class="icon-copy fa fa-arrows-h" aria-hidden="true"></i></button>
                                            <button type="button" id="flip-vertical" class="btn btn-sm btn-outline-primary"><i class="icon-copy fa fa-arrows-v" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                    
                                    <!-- Reset Button -->
                                    <div class="mb-3">
                                        <button type="button" id="reset-crop" class="btn btn-sm btn-outline-secondary w-100">Reset</button>
                                    </div>
                                    
                                    <!-- Aspect Ratio Buttons -->
                                    <div class="mb-3">
                                        <label class="d-block mb-2">Aspect Ratio:</label>
                                        <div class="aspect-ratio-buttons">
                                            <button type="button" id="aspect-1-1" class="btn btn-sm btn-outline-primary">1:1</button>
                                            <button type="button" id="aspect-4-3" class="btn btn-sm btn-outline-primary">4:3</button>
                                            <button type="button" id="aspect-16-9" class="btn btn-sm btn-outline-primary">16:9</button>
                                            <button type="button" id="aspect-free" class="btn btn-sm btn-outline-primary">Free</button>
                                        </div>
                                    </div>
                                    
                                    <!-- Crop Data -->
                                    <div class="cropper-data-group">
                                        <label class="d-block mb-2">Crop Data:</label>
                                        <div class="row">
                                            <div class="col-6">
                                                <label for="data-x">X</label>
                                                <input type="text" class="form-control form-control-sm cropper-data-input" id="data-x" placeholder="x">
                                            </div>
                                            <div class="col-6">
                                                <label for="data-y">Y</label>
                                                <input type="text" class="form-control form-control-sm cropper-data-input" id="data-y" placeholder="y">
                                            </div>
                                            <div class="col-6">
                                                <label for="data-width">Width</label>
                                                <input type="text" class="form-control form-control-sm cropper-data-input" id="data-width" placeholder="width">
                                            </div>
                                            <div class="col-6">
                                                <label for="data-height">Height</label>
                                                <input type="text" class="form-control form-control-sm cropper-data-input" id="data-height" placeholder="height">
                                            </div>
                                            <div class="col-6">
                                                <label for="data-rotate">Rotate</label>
                                                <input type="text" class="form-control form-control-sm cropper-data-input" id="data-rotate" placeholder="rotate">
                                            </div>
                                            <div class="col-6">
                                                <label for="data-scale-x">ScaleX</label>
                                                <input type="text" class="form-control form-control-sm cropper-data-input" id="data-scale-x" placeholder="scaleX">
                                            </div>
                                            <div class="col-6">
                                                <label for="data-scale-y">ScaleY</label>
                                                <input type="text" class="form-control form-control-sm cropper-data-input" id="data-scale-y" placeholder="scaleY">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="mt-4">
                                        <button type="button" id="crop-image" class="btn btn-primary w-100 mb-2">Crop</button>
                                        <button type="button" id="cancel-crop" class="btn btn-outline-secondary w-100">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hidden input for cropped image data -->
                    <input type="hidden" id="cropped_image_data" name="cropped_image_data">
                </div>
            </div>

            <!-- Step 3: Review -->
            <div id="step-3" class="form-wizard-content">
                <div class="row">
                    <div class="col-12 mb-4">
                        <h5 class="text-primary border-bottom pb-2 mb-4">Review Information</h5>
                        <p class="text-muted">Please review the information below before submitting.</p>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Parent Details</h5>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-medium">Name:</div>
                                    <div class="col-md-8" id="review-name"></div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-medium">Contact:</div>
                                    <div class="col-md-8" id="review-contact"></div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-medium">Password:</div>
                                    <div class="col-md-8">********</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body" id="review-profile-picture-container">
                                <h5 class="card-title mb-4">Profile Picture</h5>
                                <img id="review-profile-picture" src="" alt="Profile Picture" class="img-fluid rounded-circle mx-auto d-block" style="max-width: 200px; max-height: 200px; display: none;">
                                <p class="text-muted text-center mt-3">No profile picture provided</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="wizard-buttons">
                <div>
                    <button type="button" class="btn btn-outline-secondary wizard-btn-prev">Previous</button>
                </div>
                <div>
                    <a href="<?= route_to('admin.parent') ?>" class="btn btn-outline-secondary" style="margin-right: 10px;">Cancel</a>
                    <button type="button" class="btn btn-primary wizard-btn-next">Next</button>
                    <button type="submit" class="btn btn-success wizard-btn-submit">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
