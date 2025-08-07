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
            if (stepNumber === 2 && !validateTeacherInfoStep()) {
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
    
    // Function to validate teacher information step
    function validateTeacherInfoStep() {
        let isValid = true;
        const requiredFields = ['account_no', 'name', 'subjects', 'gender', 'age', 'status'];
        
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
            const accountNoInput = document.getElementById('account_no');
            const nameInput = document.getElementById('name');
            const subjectsInput = document.getElementById('subjects');
            const genderInput = document.getElementById('gender');
            const ageInput = document.getElementById('age');
            const studentCountInput = document.getElementById('student_count');
            const statusInput = document.getElementById('status');
            
            // Set review values
            document.getElementById('review-account-no').textContent = accountNoInput ? accountNoInput.value : 'Not provided';
            document.getElementById('review-name').textContent = nameInput ? nameInput.value : 'Not provided';
            document.getElementById('review-subjects').textContent = subjectsInput ? subjectsInput.value : 'Not provided';
            document.getElementById('review-gender').textContent = genderInput ? genderInput.options[genderInput.selectedIndex].text : 'Not provided';
            document.getElementById('review-age').textContent = ageInput ? ageInput.value : 'Not provided';
            document.getElementById('review-student-count').textContent = studentCountInput ? studentCountInput.value : '0';
            document.getElementById('review-status').textContent = statusInput ? statusInput.options[statusInput.selectedIndex].text : 'Not provided';
            
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
            if (!validateTeacherInfoStep()) {
                e.preventDefault();
                goToStep(1);
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
                <h4>Add New Teacher</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= route_to('admin.home') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('admin/teacher') ?>">Teachers</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Teacher</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4">Add New Teacher</h4>
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

        <form action="<?= site_url('admin/teacher/store') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- Step 1: Teacher Information -->
            <div id="step-1" class="form-wizard-content active">
                <!-- Basic Information Section -->
                <div class="row mb-5">
                    <div class="col-12"><h5 class="text-primary border-bottom pb-2 mb-4">Basic Information</h5></div>
                    <div class="col-md-6 mb-3">
                        <label for="account_no" class="font-weight-bold">Account No. <span class="text-danger">*</span></label>
                        <input type="text" id="account_no" name="account_no" value="<?= old('account_no') ?>" class="form-control mt-2" required>
                        <div class="invalid-feedback">Please enter a valid account number.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="name" class="font-weight-bold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" value="<?= old('name') ?>" class="form-control mt-2" required>
                        <div class="invalid-feedback">Please enter a valid name.</div>
                    </div>
                </div>

                <!-- Personal Details -->
                <div class="row mb-5">
                    <div class="col-12"><h5 class="text-primary border-bottom pb-2 mb-4">Personal Details</h5></div>
                    <div class="col-md-6 mb-3">
                        <label for="gender" class="font-weight-bold">Gender <span class="text-danger">*</span></label>
                        <select id="gender" name="gender" class="form-control mt-2" required>
                            <option value="">Select Gender</option>
                            <option value="Male" <?= old('gender') == 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= old('gender') == 'Female' ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="age" class="font-weight-bold">Age <span class="text-danger">*</span></label>
                        <input type="number" id="age" name="age" value="<?= old('age') ?>" class="form-control mt-2" min="18" max="100" required>
                    </div>
                </div>

                <!-- Teaching Information -->
                <div class="row mb-5">
                    <div class="col-12"><h5 class="text-primary border-bottom pb-2 mb-4">Teaching Information</h5></div>
                    <div class="col-md-6 mb-3">
                        <label for="subjects" class="font-weight-bold">Subjects <span class="text-danger">*</span></label>
                        <input type="text" id="subjects" name="subjects" value="<?= old('subjects') ?>" class="form-control mt-2" placeholder="e.g. Math, Science, English" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="student_count" class="font-weight-bold">Student Count</label>
                        <input type="number" id="student_count" name="student_count" value="<?= old('student_count', 0) ?>" class="form-control mt-2" min="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status" class="font-weight-bold">Status <span class="text-danger">*</span></label>
                        <select id="status" name="status" class="form-control mt-2" required>
                            <option value="">Select Status</option>
                            <option value="Active" <?= old('status') == 'Active' ? 'selected' : '' ?>>Active</option>
                            <option value="Inactive" <?= old('status') == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                            <option value="On Leave" <?= old('status') == 'On Leave' ? 'selected' : '' ?>>On Leave</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Step 2: Profile Picture -->
            <div id="step-2" class="form-wizard-content">
                <div class="row">
                    <div class="col-12 mb-4">
                        <h5 class="text-primary border-bottom pb-2 mb-4">Profile Picture</h5>
                        <p class="text-muted">Upload a profile picture for the teacher. This is optional but recommended.</p>
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
                                <h5 class="card-title mb-4">Teacher Details</h5>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-medium">Account No.:</div>
                                    <div class="col-md-8" id="review-account-no"></div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-medium">Name:</div>
                                    <div class="col-md-8" id="review-name"></div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-medium">Subjects:</div>
                                    <div class="col-md-8" id="review-subjects"></div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-medium">Gender:</div>
                                    <div class="col-md-8" id="review-gender"></div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-medium">Age:</div>
                                    <div class="col-md-8" id="review-age"></div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-medium">Student Count:</div>
                                    <div class="col-md-8" id="review-student-count"></div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-medium">Status:</div>
                                    <div class="col-md-8" id="review-status"></div>
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
                    <button type="button" class="btn btn-primary wizard-btn-next">Next</button>
                    <button type="submit" class="btn btn-success wizard-btn-submit">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
