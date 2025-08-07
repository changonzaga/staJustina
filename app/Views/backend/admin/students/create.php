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
        console.log('goToStep called with step:', stepNumber);
        console.log('Current step before navigation:', currentStep);
        console.log('Function caller:', new Error().stack);
        
        // Force step number to be an integer
        stepNumber = parseInt(stepNumber, 10);
        console.log('Parsed step number:', stepNumber);
        
        // Validate step number
        if (stepNumber < 1 || stepNumber > totalSteps) {
            return;
        }
        
        // Validate form fields if moving forward
        if (stepNumber > currentStep) {
            if (stepNumber === 2 && !validateStudentInfoStep()) {
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
            console.log('Calling populateReviewData for step 3');
            // Use setTimeout to ensure the DOM is updated before populating review data
            setTimeout(() => {
                console.log('DOM should be updated now, calling populateReviewData');
                populateReviewData();
            }, 50);
        }
        
        // Show/hide navigation buttons based on current step
        updateNavigationButtons();
        
        // If going to review step, populate review data
        if (stepNumber === 3) {
            populateReviewData();
        }
    }
    
    // Function to validate student information step
    function validateStudentInfoStep() {
        let isValid = true;
        const requiredFields = ['lrn', 'name', 'gender', 'age', 'grade_level', 'section'];
        
        requiredFields.forEach(field => {
            const input = document.getElementById(field);
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });
        
        // Additional validation for LRN (exactly 12 numeric digits)
        const lrnInput = document.getElementById('lrn');
        if (lrnInput && lrnInput.value.trim() && !/^\d{12}$/.test(lrnInput.value.trim())) {
            isValid = false;
            lrnInput.classList.add('is-invalid');
            // Find and show the feedback div
            const feedbackDiv = lrnInput.nextElementSibling;
            if (feedbackDiv && feedbackDiv.classList.contains('invalid-feedback')) {
                feedbackDiv.style.display = 'block';
            }
        }
        
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
        console.log('Populating review data...');
        
        try {
            // Force focus on a form field to ensure all values are committed
            document.body.focus();
            
            // Get form values with additional error handling
            const lrnInput = document.getElementById('lrn');
            const nameInput = document.getElementById('name');
            const genderInput = document.getElementById('gender');
            const ageInput = document.getElementById('age');
            const gradeLevelInput = document.getElementById('grade_level');
            const sectionInput = document.getElementById('section');
            const addressInput = document.getElementById('address');
            const guardianInput = document.getElementById('guardian');
            const contactInput = document.getElementById('contact');
            const teacherInput = document.getElementById('teacher_id');
            const parentInput = document.getElementById('parent_id');
            
            // Log input elements and their values
            console.log('Input elements found:', {
                lrnInput: !!lrnInput,
                nameInput: !!nameInput,
                genderInput: !!genderInput,
                ageInput: !!ageInput,
                gradeLevelInput: !!gradeLevelInput,
                sectionInput: !!sectionInput,
                addressInput: !!addressInput,
                guardianInput: !!guardianInput,
                contactInput: !!contactInput,
                teacherInput: !!teacherInput,
                parentInput: !!parentInput
            });
            
            // Get values with fallbacks
            const lrnValue = lrnInput ? lrnInput.value : '';
            const nameValue = nameInput ? nameInput.value : '';
            const genderValue = genderInput ? genderInput.value : '';
            const ageValue = ageInput ? ageInput.value : '';
            const gradeLevelValue = gradeLevelInput ? gradeLevelInput.value : '';
            const sectionValue = sectionInput ? sectionInput.value : '';
            const addressValue = addressInput ? addressInput.value : '';
            const guardianValue = guardianInput ? guardianInput.value : '';
            const contactValue = contactInput ? contactInput.value : '';
            
            // Directly check if values are empty and log a warning
            if (!lrnValue) console.warn('LRN value is empty!');
            if (!nameValue) console.warn('Name value is empty!');
            if (!genderValue) console.warn('Gender value is empty!');
            if (!ageValue) console.warn('Age value is empty!');
            if (!gradeLevelValue) console.warn('Grade Level value is empty!');
            if (!sectionValue) console.warn('Section value is empty!');
            
            // Log the actual values
            console.log('Form values:', {
                lrn: lrnValue,
                name: nameValue,
                gender: genderValue,
                age: ageValue,
                gradeLevel: gradeLevelValue,
                section: sectionValue,
                address: addressValue,
                guardian: guardianValue,
                contact: contactValue
            });
            
            // Make sure step 3 is visible before trying to populate it
            const step3 = document.getElementById('step-3');
            if (!step3) {
                console.error('Step 3 element not found');
                return;
            }
            
            if (!step3.classList.contains('active')) {
                console.log('Step 3 is not active yet, making it active');
                // Hide all steps
                document.querySelectorAll('.form-wizard-content').forEach(step => {
                    step.classList.remove('active');
                });
                // Show step 3
                step3.classList.add('active');
            }
            
            // Get review elements
            const reviewLrn = document.getElementById('review-lrn');
            const reviewName = document.getElementById('review-name');
            const reviewGender = document.getElementById('review-gender');
            const reviewAge = document.getElementById('review-age');
            const reviewGradeLevel = document.getElementById('review-grade-level');
            const reviewSection = document.getElementById('review-section');
            const reviewTeacher = document.getElementById('review-teacher');
            const reviewParent = document.getElementById('review-parent');
            const reviewAddress = document.getElementById('review-address');
            const reviewGuardian = document.getElementById('review-guardian');
            const reviewContact = document.getElementById('review-contact');
            
            console.log('Review elements exist:', {
                reviewLrn: !!reviewLrn,
                reviewName: !!reviewName,
                reviewGender: !!reviewGender,
                reviewAge: !!reviewAge,
                reviewGradeLevel: !!reviewGradeLevel,
                reviewSection: !!reviewSection,
                reviewTeacher: !!reviewTeacher,
                reviewParent: !!reviewParent,
                reviewAddress: !!reviewAddress,
                reviewGuardian: !!reviewGuardian,
                reviewContact: !!reviewContact
            });
            
            // Basic information - with additional logging
            if (reviewLrn) {
                reviewLrn.textContent = lrnValue || 'Not provided';
                console.log('Set review LRN to:', reviewLrn.textContent);
            }
            
            if (reviewName) {
                reviewName.textContent = nameValue || 'Not provided';
                console.log('Set review Name to:', reviewName.textContent);
            }
            
            if (reviewGender) {
                reviewGender.textContent = genderValue || 'Not provided';
                console.log('Set review Gender to:', reviewGender.textContent);
            }
            
            if (reviewAge) {
                reviewAge.textContent = ageValue || 'Not provided';
                console.log('Set review Age to:', reviewAge.textContent);
            }
            
            // Academic information
            if (reviewGradeLevel) {
                reviewGradeLevel.textContent = gradeLevelValue || 'Not provided';
                console.log('Set review Grade Level to:', reviewGradeLevel.textContent);
            }
            
            if (reviewSection) {
                reviewSection.textContent = sectionValue || 'Not provided';
                console.log('Set review Section to:', reviewSection.textContent);
            }
            
            // Teacher and parent
            if (reviewTeacher && teacherInput) {
                reviewTeacher.textContent = teacherInput.selectedIndex > 0 ? 
                    teacherInput.options[teacherInput.selectedIndex].text : 'Not assigned';
                console.log('Set review Teacher to:', reviewTeacher.textContent);
            } else {
                console.error('Review teacher element or teacher input not found');
                console.log('reviewTeacher exists:', !!reviewTeacher);
                console.log('teacherInput exists:', !!teacherInput);
            }
            
            if (reviewParent && parentInput) {
                reviewParent.textContent = parentInput.selectedIndex > 0 ? 
                    parentInput.options[parentInput.selectedIndex].text : 'Not assigned';
                console.log('Set review Parent to:', reviewParent.textContent);
            } else {
                console.error('Review parent element or parent input not found');
                console.log('reviewParent exists:', !!reviewParent);
                console.log('parentInput exists:', !!parentInput);
            }
            
            // Contact information
            if (reviewAddress) {
                reviewAddress.textContent = addressValue || 'Not provided';
                console.log('Set review Address to:', reviewAddress.textContent);
            }
            
            if (reviewGuardian) {
                reviewGuardian.textContent = guardianValue || 'Not provided';
                console.log('Set review Guardian to:', reviewGuardian.textContent);
            }
            
            if (reviewContact) {
                reviewContact.textContent = contactValue || 'Not provided';
                console.log('Set review Contact to:', reviewContact.textContent);
            }
            
            // Profile picture
            const croppedImageData = document.getElementById('cropped_image_data').value;
            console.log('Cropped image data in populateReviewData:', croppedImageData ? 'Available' : 'Not available');
            
            // Also check the preview image as a fallback
            const previewImage = document.getElementById('cropped-preview');
            const previewSrc = previewImage ? previewImage.src : '';
            console.log('Preview image src:', previewSrc ? 'Available' : 'Not available');
            
            // Use either the cropped data or the preview image
            const imageSource = croppedImageData || (previewSrc && previewSrc !== 'data:,' ? previewSrc : '');
            
            if (imageSource) {
                console.log('Using image source for review');
                const reviewPicture = document.getElementById('review-profile-picture');
                if (reviewPicture) {
                    reviewPicture.src = imageSource;
                    reviewPicture.style.display = 'block';
                    console.log('Set review picture src and made it visible');
                } else {
                    console.error('Review profile picture element not found');
                }
                
                const noImageText = document.querySelector('#review-profile-picture-container p.text-muted');
                if (noImageText) {
                    noImageText.style.display = 'none';
                    console.log('Hidden no image text');
                } else {
                    console.error('No image text element not found');
                }
            } else {
                console.log('No image source available for review');
                const reviewPicture = document.getElementById('review-profile-picture');
                if (reviewPicture) {
                    reviewPicture.style.display = 'none';
                    console.log('Hidden review picture');
                } else {
                    console.error('Review profile picture element not found');
                }
                
                const noImageText = document.querySelector('#review-profile-picture-container p.text-muted');
                if (noImageText) {
                    noImageText.style.display = 'block';
                    console.log('Shown no image text');
                } else {
                    console.error('No image text element not found');
                }
            }
            console.log('Review data populated successfully');
        } catch (error) {
            console.error('Error populating review data:', error);
        }
    }
    
    // Function to navigate to a specific step
    function goToStep(stepNumber) {
        console.log('Navigating to step:', stepNumber);
        
        // Validate step number
        if (stepNumber < 1 || stepNumber > totalSteps) {
            return;
        }
        
        // Validate form fields if moving forward
        if (stepNumber > currentStep) {
            if (stepNumber === 2 && !validateStudentInfoStep()) {
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
            console.log('Calling populateReviewData for step 3');
            populateReviewData();
        }
        
        // Show/hide navigation buttons based on current step
        updateNavigationButtons();
    }
    
    // Function to validate student information step
    function validateStudentInfoStep() {
        let isValid = true;
        const requiredFields = ['lrn', 'name', 'gender', 'age', 'grade_level', 'section'];
        
        requiredFields.forEach(field => {
            const input = document.getElementById(field);
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });
        
        // Additional validation for LRN - must be exactly 12 digits
        const lrnInput = document.getElementById('lrn');
        if (lrnInput.value.trim() && !/^\d{12}$/.test(lrnInput.value.trim())) {
            lrnInput.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            alert('Please fill in all required fields before proceeding. LRN must contain exactly 12 numeric digits.');
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
    
    // When document is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize wizard
        goToStep(1);
        
        // Next button click handler
        document.querySelectorAll('.wizard-btn-next').forEach(btn => {
            btn.addEventListener('click', function() {
                console.log('Next button clicked, current step:', currentStep);
                
                const nextStep = currentStep + 1;
                
                // If we're on step 2 and moving to step 3, ensure we populate review data
                if (currentStep === 2) {
                    console.log('Moving from step 2 to step 3, will populate review data');
                    // Debug form values before going to step 3
                    console.log('Form values before going to step 3:');
                    console.log('LRN:', document.getElementById('lrn').value);
                    console.log('Name:', document.getElementById('name').value);
                    console.log('Gender:', document.getElementById('gender').value);
                    console.log('Age:', document.getElementById('age').value);
                    console.log('Grade Level:', document.getElementById('grade_level').value);
                    console.log('Section:', document.getElementById('section').value);
                }
                
                goToStep(nextStep);
                
                // Debug after goToStep
                if (nextStep === 3) {
                    // Use setTimeout to ensure this runs after goToStep has completed
                    setTimeout(() => {
                        console.log('Now on step 3, checking if review data was populated:');
                        console.log('Review LRN:', document.getElementById('review-lrn').textContent);
                        console.log('Review Name:', document.getElementById('review-name').textContent);
                        
                        // If review data is not populated, try calling populateReviewData again
                        if (!document.getElementById('review-lrn').textContent || 
                            document.getElementById('review-lrn').textContent === 'Not provided') {
                            console.log('Review data not populated, calling populateReviewData again');
                            populateReviewData();
                        }
                    }, 100);
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
        
        // Aspect ratio button handlers
        document.getElementById('aspect-1-1').addEventListener('click', function() {
            if (!cropper) return;
            cropper.setAspectRatio(1);
        });
        
        document.getElementById('aspect-4-3').addEventListener('click', function() {
            if (!cropper) return;
            cropper.setAspectRatio(4/3);
        });
        
        document.getElementById('aspect-free').addEventListener('click', function() {
            if (!cropper) return;
            cropper.setAspectRatio(NaN);
        });
        
        // Crop button click event
        document.getElementById('crop-image').addEventListener('click', function() {
            console.log('Crop button clicked');
            if (!cropper) {
                console.error('Cropper not initialized');
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
                console.error('Failed to get cropped canvas');
                return;
            }
            
            // Convert canvas to data URL
            const croppedImageData = canvas.toDataURL('image/jpeg', 0.8);
            console.log('Cropped image data generated:', croppedImageData ? 'Success' : 'Failed');
            
            // Set the value of the hidden input
            document.getElementById('cropped_image_data').value = croppedImageData;
            console.log('Cropped image data set to hidden input');
            
            // Show the preview
            document.getElementById('cropped-image-preview').style.display = 'block';
            document.getElementById('cropped-preview').src = croppedImageData;
            console.log('Preview updated with cropped image');
            
            // Also update the review picture if we're already on step 3
            if (currentStep === 3) {
                console.log('Already on review step, updating review picture directly');
                const reviewPicture = document.getElementById('review-profile-picture');
                if (reviewPicture) {
                    reviewPicture.src = croppedImageData;
                    reviewPicture.style.display = 'block';
                    console.log('Updated review profile picture directly');
                    
                    const noImageText = document.querySelector('#review-profile-picture-container p.text-muted');
                    if (noImageText) {
                        noImageText.style.display = 'none';
                        console.log('Hidden no image text in review');
                    } else {
                        console.error('No image text element not found in review');
                    }
                } else {
                    console.error('Review profile picture element not found');
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
            if (!validateStudentInfoStep()) {
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
                <h4>Add New Student</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= route_to('admin.home') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('admin/student') ?>">Students</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Student</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4">Add New Student</h4>
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
                <div class="step-title">Student Information</div>
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

        <form action="<?= site_url('admin/student/store') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- Step 1: Student Information -->
            <div id="step-1" class="form-wizard-content active">
                <!-- Basic Information Section -->
                <div class="row mb-5">
                    <div class="col-12"><h5 class="text-primary border-bottom pb-2 mb-4">Basic Information</h5></div>
                    <div class="col-md-6 mb-3">
                        <label for="lrn" class="font-weight-bold">LRN <span class="text-danger">*</span></label>
                        <input type="text" id="lrn" name="lrn" value="<?= old('lrn') ?>" class="form-control mt-2" pattern="[0-9]{12}" required>
                        <div class="invalid-feedback">LRN must contain exactly 12 numeric digits.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="name" class="font-weight-bold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" value="<?= old('name') ?>" class="form-control mt-2" required>
                        <div class="invalid-feedback">Please enter a valid name. A student with this name may already exist.</div>
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
                            <option value="Other" <?= old('gender') == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="age" class="font-weight-bold">Age <span class="text-danger">*</span></label>
                        <input type="number" id="age" name="age" value="<?= old('age') ?>" class="form-control mt-2" min="1" max="100" required>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="row mb-5">
                    <div class="col-12"><h5 class="text-primary border-bottom pb-2 mb-4">Academic Information</h5></div>
                    <div class="col-md-6 mb-3">
                        <label for="grade_level" class="font-weight-bold">Grade Level <span class="text-danger">*</span></label>
                        <select id="grade_level" name="grade_level" class="form-control mt-2" required>
                            <option value="">Select Grade Level</option>
                            <?php for($i = 7; $i <= 12; $i++): ?>
                                <option value="<?= $i ?>" <?= old('grade_level') == $i ? 'selected' : '' ?>>Grade <?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="section" class="font-weight-bold">Section <span class="text-danger">*</span></label>
                        <input type="text" id="section" name="section" value="<?= old('section') ?>" class="form-control mt-2" required>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="row mb-5">
                    <div class="col-12"><h5 class="text-primary border-bottom pb-2 mb-4">Contact Information</h5></div>
                    <div class="col-12 mb-3">
                        <label for="address" class="font-weight-bold">Address</label>
                        <textarea id="address" name="address" class="form-control mt-2" rows="3"><?= old('address') ?></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="guardian" class="font-weight-bold">Guardian Name</label>
                        <input type="text" id="guardian" name="guardian" value="<?= old('guardian') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="contact" class="font-weight-bold">Contact Number</label>
                        <input type="text" id="contact" name="contact" value="<?= old('contact') ?>" class="form-control mt-2">
                    </div>
                </div>

                <!-- Assignments -->
                <div class="row mb-5">
                    <div class="col-12"><h5 class="text-primary border-bottom pb-2 mb-4">Assignments</h5></div>
                    <div class="col-md-6 mb-3">
                        <label for="teacher_id" class="font-weight-bold">Assign Teacher</label>
                        <select id="teacher_id" name="teacher_id" class="form-control mt-2">
                            <option value="">Select Teacher (Optional)</option>
                            <?php if (isset($teachers) && !empty($teachers)): ?>
                                <?php foreach ($teachers as $teacher): ?>
                                    <option value="<?= esc($teacher['id']) ?>" <?= old('teacher_id') == $teacher['id'] ? 'selected' : '' ?>>
                                        <?= esc($teacher['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="parent_id" class="font-weight-bold">Assign Parent</label>
                        <select id="parent_id" name="parent_id" class="form-control mt-2">
                            <option value="">Select Parent (Optional)</option>
                            <?php if (isset($parents) && !empty($parents)): ?>
                                <?php foreach ($parents as $parent): ?>
                                    <option value="<?= esc($parent['id']) ?>" <?= old('parent_id') == $parent['id'] ? 'selected' : '' ?>>
                                        <?= esc($parent['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <!-- Step 1 Navigation -->
                <div class="wizard-buttons mt-4 d-flex justify-content-end">
                    <a href="<?= site_url('admin/student') ?>" class="btn btn-outline-secondary px-4 mr-2">
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
                        <p class="text-muted">Upload a profile picture for the student (optional). You can crop and adjust the image as needed.</p>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="profile_picture" class="font-weight-bold">Select Image</label>
                        <input type="file" id="profile_picture" name="profile_picture" class="form-control-file mt-2" accept="image/*" onchange="loadImageForCropping(event)">
                        <small class="form-text text-muted mt-1">Recommended size: 300x300 pixels, maximum file size: 2MB</small>
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
                                <div class="cropper-data-group">
                                    <label>Aspect Ratio</label>
                                    <div class="aspect-ratio-buttons">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="aspect-1-1">1:1</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="aspect-4-3">4:3</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="aspect-free">Free</button>
                                    </div>
                                </div>
                                
                                <!-- Crop Box Data -->
                                <div class="row">
                                    <div class="col-6 cropper-data-group">
                                        <label for="data-x">X (px)</label>
                                        <input type="text" class="form-control form-control-sm cropper-data-input" id="data-x" placeholder="x">
                                    </div>
                                    <div class="col-6 cropper-data-group">
                                        <label for="data-y">Y (px)</label>
                                        <input type="text" class="form-control form-control-sm cropper-data-input" id="data-y" placeholder="y">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-6 cropper-data-group">
                                        <label for="data-width">Width (px)</label>
                                        <input type="text" class="form-control form-control-sm cropper-data-input" id="data-width" placeholder="width">
                                    </div>
                                    <div class="col-6 cropper-data-group">
                                        <label for="data-height">Height (px)</label>
                                        <input type="text" class="form-control form-control-sm cropper-data-input" id="data-height" placeholder="height">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12 cropper-data-group">
                                        <label for="data-rotate">Rotate (deg)</label>
                                        <input type="text" class="form-control form-control-sm cropper-data-input" id="data-rotate" placeholder="rotate">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-6 cropper-data-group">
                                        <label for="data-scale-x">Scale X</label>
                                        <input type="text" class="form-control form-control-sm cropper-data-input" id="data-scale-x" placeholder="scaleX">
                                    </div>
                                    <div class="col-6 cropper-data-group">
                                        <label for="data-scale-y">Scale Y</label>
                                        <input type="text" class="form-control form-control-sm cropper-data-input" id="data-scale-y" placeholder="scaleY">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3 mt-3">
                        <button type="button" class="btn btn-primary" id="crop-image">Crop Image</button>
                        <button type="button" class="btn btn-secondary" id="cancel-crop">Cancel</button>
                    </div>
                    <input type="hidden" id="cropped_image_data" name="cropped_image_data">
                </div>
                
                <!-- Preview of cropped image -->
                <div id="cropped-image-preview" class="mt-3" style="display: none;">
                    <h6 class="text-muted">Preview:</h6>
                    <img id="cropped-preview" src="" alt="Cropped preview" style="max-width: 150px; max-height: 150px; border-radius: 50%;">
                </div>
                
                <!-- Step 2 Navigation -->
                <div class="wizard-buttons mt-4 d-flex justify-content-end">
                    <a href="<?= site_url('admin/student') ?>" class="btn btn-outline-secondary px-4 mr-2">
                        <i class="fa fa-arrow-left mr-2"></i> Cancel
                    </a>
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
                <div class="row">
                    <div class="col-12 mb-4">
                        <h5 class="text-primary border-bottom pb-2 mb-4">Review Student Information</h5>
                        <p class="text-muted">Please review the student information before submitting. If you need to make changes, use the Previous button to go back.</p>
                    </div>
                </div>
                
                <div class="row d-flex">
                    <div class="col-md-3 d-flex" id="review-profile-picture-container">
                        <div class="card shadow-sm p-3 w-100">
                            <h6 class="font-weight-bold mb-3 border-bottom pb-2 text-center">Profile Picture</h6>
                            <div class="d-flex flex-column justify-content-start align-items-center flex-grow-1 pt-3">
                                <img id="review-profile-picture" src="" alt="Student profile picture" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; display: none;">
                                <p class="text-muted mt-3 text-center">No profile picture uploaded</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-9 d-flex flex-column">
                        <div class="card shadow-sm p-4 mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold border-bottom pb-2 mb-3">Basic Information</h6>
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr>
                                            <td class="text-muted font-weight-medium" style="width: 130px;">LRN:</td>
                                            <td id="review-lrn" class="font-weight-medium"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted font-weight-medium">Name:</td>
                                            <td id="review-name" class="font-weight-medium"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted font-weight-medium">Gender:</td>
                                            <td id="review-gender" class="font-weight-medium"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted font-weight-medium">Age:</td>
                                            <td id="review-age" class="font-weight-medium"></td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold border-bottom pb-2 mb-3">Academic Information</h6>
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr>
                                            <td class="text-muted font-weight-medium" style="width: 130px;">Grade Level:</td>
                                            <td id="review-grade-level" class="font-weight-medium"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted font-weight-medium">Section:</td>
                                            <td id="review-section" class="font-weight-medium"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted font-weight-medium">Teacher:</td>
                                            <td id="review-teacher" class="font-weight-medium"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted font-weight-medium">Parent:</td>
                                            <td id="review-parent" class="font-weight-medium"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card shadow-sm p-4 flex-grow-1">
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="font-weight-bold border-bottom pb-2 mb-3">Contact Information</h6>
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr>
                                            <td class="text-muted font-weight-medium" style="width: 130px;">Address:</td>
                                            <td id="review-address" class="font-weight-medium"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted font-weight-medium">Guardian:</td>
                                            <td id="review-guardian" class="font-weight-medium"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted font-weight-medium">Contact:</td>
                                            <td id="review-contact" class="font-weight-medium"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Step 3 Navigation -->
                <div class="wizard-buttons mt-4 d-flex justify-content-end">
                    <a href="<?= site_url('admin/student') ?>" class="btn btn-outline-secondary px-4 mr-2">
                        <i class="fa fa-arrow-left mr-2"></i> Cancel
                    </a>
                    <button type="button" class="btn btn-outline-secondary wizard-btn-prev px-4 mr-2">
                        <i class="fa fa-arrow-left mr-2"></i> Previous
                    </button>
                    <button type="submit" class="btn btn-success wizard-btn-submit px-4">
                        <i class="fa fa-save mr-2"></i> Save Student
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap Modal Alert HTML -->
<div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="d-flex align-items-center justify-content-center mb-4 mx-auto rounded-circle text-white" style="width: 70px; height: 70px;">
                    <i class="fa fa-3x"></i>
                </div>
                <h5 class="font-weight-bold mb-3" id="alertModalLabel"></h5>
                <p class="mb-4 text-muted"></p>
                <button type="button" class="btn px-4 py-2 font-weight-bold" id="alertModalButton">OK</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Cropper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="/backend/src/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="/backend/src/plugins/sweetalert2/sweet-alert.init.js"></script>

<!-- Enhanced Bootstrap Alert implementation -->
<script>
// Global variable to store the step to navigate to after modal is closed
let pendingNavigation = null;

// Function to handle navigation after modal is closed
function navigateAfterModal() {
    console.log('navigateAfterModal called, pendingNavigation:', pendingNavigation);
    if (pendingNavigation !== null) {
        const step = pendingNavigation;
        pendingNavigation = null; // Reset pending navigation
        console.log('Executing pending navigation to step:', step);
        goToStep(step);
    }
}

function simpleAlert(title, message, type) {
    // Check if goToStep function is defined
    console.log('goToStep function exists:', typeof goToStep === 'function');
    if (typeof goToStep !== 'function') {
        console.error('goToStep function is not defined!');
    }
    // Get the modal element
    const modal = $('#alertModal');
    
    // Set the title and message
    modal.find('#alertModalLabel').text(title);
    modal.find('.modal-body p').text(message);
    
    // Set button class based on type
    const button = modal.find('#alertModalButton');
    button.removeClass('btn-primary btn-success btn-danger btn-info btn-warning');
    
    // Set icon container background and icon class based on type
    const iconContainer = modal.find('.modal-body .d-flex');
    iconContainer.removeClass('bg-primary bg-success bg-danger bg-info bg-warning');
    const iconElement = iconContainer.find('i.fa');
    iconElement.removeClass('fa-check fa-times fa-info-circle fa-exclamation-triangle');
    
    // Add border-top based on alert type
    const modalContent = modal.find('.modal-content');
    modalContent.removeClass('border-top border-success border-danger border-info border-warning');
    modalContent.addClass('border-top');
    
    if (type === 'success') {
        iconContainer.addClass('bg-success');
        iconElement.addClass('fa-check');
        button.addClass('btn-success');
        modalContent.addClass('border-success');
    } else if (type === 'error') {
        iconContainer.addClass('bg-danger');
        iconElement.addClass('fa-times');
        button.addClass('btn-danger');
        modalContent.addClass('border-danger');
    } else if (type === 'warning') {
        iconContainer.addClass('bg-warning');
        iconElement.addClass('fa-exclamation-triangle');
        button.addClass('btn-warning');
        modalContent.addClass('border-warning');
    } else {
        iconContainer.addClass('bg-info');
        iconElement.addClass('fa-info-circle');
        button.addClass('btn-info');
        modalContent.addClass('border-info');
    }
    
    // Ensure any existing modal is properly hidden first
    $('.modal').modal('hide');
    
    // Show the modal
    console.log('Showing modal with title:', title, 'and message:', message);
    modal.modal({
        show: true,
        backdrop: 'static',
        keyboard: false
    });
    
    // Return promise-like object
    return {
        then: function(callback) {
            // Handle button click - using direct event handler
            button.off('click');
            button.on('click', function() {
                console.log('Button click handler executed');
                console.log('Alert button clicked, type:', type);
                console.log('Message content:', message);
                
                // Clear validation errors when OK button is clicked
                if (type === 'error' || type === 'warning') {
                    console.log('Error or warning alert detected');
                    $('input, select').removeClass('is-invalid');
                    $('.invalid-feedback').hide();
                    
                    // Set pending navigation based on error message
                    const lowerMessage = message.toLowerCase();
                    console.log('Lowercase message:', lowerMessage);
                    
                    if (lowerMessage.includes('lrn') || 
                        lowerMessage.includes('name') || 
                        lowerMessage.includes('gender') || 
                        lowerMessage.includes('age') || 
                        lowerMessage.includes('grade_level') || 
                        lowerMessage.includes('section') || 
                        lowerMessage.includes('already exists')) {
                        // These fields are in Step 1
                        console.log('Setting pending navigation to Step 1');
                        pendingNavigation = 1;
                    } else if (lowerMessage.includes('profile picture') || lowerMessage.includes('image')) {
                        // These fields are in Step 2
                        console.log('Setting pending navigation to Step 2');
                        pendingNavigation = 2;
                    }
                }
                
                // Hide the modal
                console.log('Hiding modal after button click');
                modal.modal('hide');
                
                // Execute callback after modal is hidden
                console.log('Executing callback with isConfirmed: true');
                callback({isConfirmed: true});
            });
            
            // Handle modal hidden event
            modal.off('hidden.bs.modal').on('hidden.bs.modal', function() {
                console.log('Modal hidden event triggered');
                
                // Clean up event handlers
                console.log('Cleaning up event handlers');
                button.off('click');
                modal.off('hidden.bs.modal');
                
                // Remove modal backdrop if it's still present
                console.log('Removing modal backdrop');
                $('.modal-backdrop').remove();
                
                // Remove modal-open class from body
                console.log('Removing modal-open class from body');
                $('body').removeClass('modal-open').css('padding-right', '');
                
                // Execute pending navigation if any
                console.log('Checking for pending navigation');
                setTimeout(navigateAfterModal, 100);
            });
            
            // Make sure the modal is visible
            console.log('Ensuring modal is visible in then function');
            setTimeout(function() {
                if (!$('.modal.show').length) {
                    console.log('Modal not showing, forcing show');
                    modal.modal('show');
                } else {
                    console.log('Modal is already visible');
                }
            }, 100);
            
            return this;
        }
    };
}
</script>

<!-- Direct button click handler -->
<script>
// Add a direct event handler to the alert modal button
$(document).on('click', '#alertModalButton', function() {
    console.log('Direct button click handler triggered');
    
    // Get the modal title and message
    const title = $('#alertModalLabel').text();
    const message = $('#alertModal .modal-body p').text();
    console.log('Modal title:', title, 'message:', message);
    
    // Check if this is a validation error alert
    if (title.includes('Error') || title.includes('Warning')) {
        const lowerMessage = message.toLowerCase();
        console.log('Processing error message:', lowerMessage);
        
        // Determine which step to navigate to
        let targetStep = null;
        if (lowerMessage.includes('lrn') || 
            lowerMessage.includes('name') || 
            lowerMessage.includes('gender') || 
            lowerMessage.includes('age') || 
            lowerMessage.includes('grade_level') || 
            lowerMessage.includes('section') || 
            lowerMessage.includes('already exists')) {
            targetStep = 1;
        } else if (lowerMessage.includes('profile picture') || lowerMessage.includes('image')) {
            targetStep = 2;
        }
        
        if (targetStep !== null) {
            console.log('Will navigate to step:', targetStep);
            // Store the target step for navigation after modal is closed
            window.setTimeout(function() {
                console.log('Executing delayed navigation to step:', targetStep);
                goToStep(targetStep);
            }, 500);
        }
    }
});
</script>

<!-- Form submission handling -->
<script>
$(document).ready(function() {
    // Form submission
    $('form').on('submit', function(e) {
        e.preventDefault();
        
        // Validate all required fields
        let isValid = true;
        const requiredFields = ['lrn', 'name', 'gender', 'age', 'grade_level', 'section'];
        
        requiredFields.forEach(field => {
            const input = document.getElementById(field);
            if (!input || !input.value.trim()) {
                isValid = false;
            }
        });
        
        // Validate LRN format - must be exactly 12 digits
        const lrnInput = document.getElementById('lrn');
        if (lrnInput && lrnInput.value.trim() && !/^\d{12}$/.test(lrnInput.value.trim())) {
            isValid = false;
            lrnInput.classList.add('is-invalid');
        }
        
        if (!isValid) {
            // Show warning message for validation errors
            simpleAlert('Warning', 'Please complete all required fields. LRN must contain exactly 12 numeric digits.', 'warning')
            .then(() => {
                // Stay on the same page
            });
            return;
        }
        
        // Get form data
        const form = $(this);
        const formData = new FormData(this);
        
        // Add CSRF token
        const csrfName = '<?= csrf_token() ?>';
        const csrfHash = '<?= csrf_hash() ?>';
        formData.append(csrfName, csrfHash);
        
        // Submit the form
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response && response.success) {
                    // Show success message
                    simpleAlert('Success', 'Student has been successfully added.', 'success')
                    .then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to student list
                            window.location.href = '<?= site_url("admin/student") ?>';
                        }
                    });
                } else {
                    // Reset validation states
                    $('input, select').removeClass('is-invalid');
                    $('.invalid-feedback').hide();
                    
                    // Handle validation errors
                    if (response.errors) {
                        // Highlight invalid fields
                        for (const field in response.errors) {
                            const inputField = $('#' + field);
                            if (inputField.length) {
                                inputField.addClass('is-invalid');
                                // Find and show the feedback div
                                const feedbackDiv = inputField.siblings('.invalid-feedback');
                                if (feedbackDiv.length) {
                                    feedbackDiv.text(response.errors[field]).show();
                                }
                            }
                        }
                        
                        // Show error message
                        let errorMessage = 'Please correct the following errors:';
                        for (const field in response.errors) {
                            errorMessage += '\n- ' + response.errors[field];
                        }
                        
                        // Determine which step to navigate to based on error fields
                        let targetStep = 1; // Default to step 1
                        
                        // Check if any of the errors are related to profile picture (step 2)
                        const profilePictureError = Object.values(response.errors).some(error => 
                            error.toLowerCase().includes('profile picture') || 
                            error.toLowerCase().includes('image')
                        );
                        
                        if (profilePictureError) {
                            targetStep = 2;
                        }
                        
                        console.log('Will navigate to step:', targetStep, 'after showing alert');
                        
                        // Show the alert and then navigate
                        simpleAlert('Validation Error', errorMessage, 'error')
                        .then(() => {
                            // Navigate to the appropriate step after the alert is closed
                            console.log('Alert closed, navigating to step:', targetStep);
                            setTimeout(() => goToStep(targetStep), 100);
                        });
                    } else {
                        // Show general error message
                        simpleAlert('Error', response.message || 'Failed to add the student. Please try again.', 'error');
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                console.log('XHR:', xhr.responseText);
                
                // Reset validation states
                $('input, select').removeClass('is-invalid');
                $('.invalid-feedback').hide();
                
                // Parse response if possible
                let errorMessage = 'Failed to add the student. Please try again.';
                try {
                    if (xhr.responseText) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMessage = response.message;
                        } else if (response.errors) {
                            // Format validation errors and highlight fields
                            errorMessage = 'Validation errors: ';
                            for (const field in response.errors) {
                                errorMessage += '\n- ' + response.errors[field];
                                
                                // Highlight invalid field
                                const inputField = $('#' + field);
                                if (inputField.length) {
                                    inputField.addClass('is-invalid');
                                    // Find and show the feedback div
                                    const feedbackDiv = inputField.siblings('.invalid-feedback');
                                    if (feedbackDiv.length) {
                                        feedbackDiv.text(response.errors[field]).show();
                                    }
                                }
                            }
                        }
                    }
                } catch (e) {
                    console.error('Failed to parse error response:', e);
                }
                
                // Show error message
                simpleAlert('Error', errorMessage, 'error');
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
