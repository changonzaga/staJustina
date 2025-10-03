<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('stylesheets') ?>
<!-- Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="/backend/src/plugins/sweetalert2/sweetalert2.css">
<style>
    /* Custom styles for profile upload container*/
    .profile-upload-container {
        border: 2px dashed #ccc;
        border-radius: 10px;
        padding: 20px;
        cursor: pointer;
        transition: border-color 0.3s;
    }

    .profile-upload-container:hover {
        border-color: #007bff;
    }

    /* Enhanced profile upload area styles (match Edit page) */
    .profile-upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8f9fa;
        position: relative;
        pointer-events: auto;
    }
    .profile-upload-area.dragging {
        border-color: #0d6efd;
        background: #eaf3ff;
        box-shadow: inset 0 0 0 2px rgba(13,110,253,.25);
    }
    .remove-file-btn {
        background: transparent;
        border: none;
        color: #dc3545;
        font-size: 18px;
        font-weight: 700;
        line-height: 1;
        cursor: pointer;
        padding: 4px 8px;
    }
    .remove-file-btn:hover, .remove-file-btn:focus {
        color: #b02a37;
        outline: none;
        text-decoration: none;
    }

    .upload-content {
        text-align: center;
        color: #777;
    }

    .upload-icon {
        font-size: 40px;
        margin-bottom: 10px;
        color: #007bff;
    }

    .profile-preview img {
        border-radius: 50%;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
    }
/* Custom styles for image cropper */
    .img-container {
        overflow: hidden;
        position: relative;
        height: clamp(320px, 60vh, 800px);
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .cropper-container {
        width: 100% !important;
        height: 100% !important;
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
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
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

    /* LRN boxes always stay blue */
    .lrn-box.is-invalid {
        border-color: #007bff !important;
    }

    .lrn-box.is-invalid:focus {
        outline: none !important;
        border-color: #007bff !important;
    }

    /* Review step styles */
    .font-weight-medium {
        font-weight: 500;
    }

    #review-profile-picture-container {
        text-align: center;
    }

    /* LRN Boxes Styling */
    .lrn-boxes {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
    }

    .lrn-box {
        width: 40px;
        height: 40px;
        text-align: center;
        border: 2px solid #007bff;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
        background-color: #f8f9ff;
        color: #000000;
    }

    .lrn-box:focus {
        outline: none;
        border-color: #007bff;
        background-color: #f8f9ff;
    }

    /* LRN boxes always stay blue - no validation colors */

    @media (max-width: 576px) {
        .lrn-box {
            width: 35px;
            height: 35px;
            font-size: 14px;
        }

        .lrn-boxes {
            gap: 4px;
        }
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

<!-- 
=== VALIDATION TESTING MODE ===
ALL VALIDATION IS CURRENTLY DISABLED FOR TESTING PURPOSES

To RE-ENABLE validation, follow these steps:
1. Set DISABLE_ALL_VALIDATION = false (around line 554)
2. Uncomment the validation code blocks marked with "TEMPORARILY DISABLED FOR TESTING"
3. Remove the "novalidate" attribute from the form tag (line 1360)
4. Restore the data-required attributes in dynamic field functions

Key areas to restore:
- Form submission validation (around line 3015)
- Step navigation validation (around line 651)
- Individual field validation functions (search for "DISABLED FOR TESTING")
- LRN input validation (around line 2692)
- Dynamic field required attributes (around line 2553, 2571, 2843)

=== END TESTING MODE INFO ===
-->

<!-- Inline cropper logic copied from Edit page for consistency -->
<script>
// Global variables for image cropper
let cropper;
let imageElement;

// Function to update data inputs
function updateCropBoxData(e) {
    const data = e.detail;
    const dataXEl = document.getElementById('dataX');
    const dataYEl = document.getElementById('dataY');
    const dataWidthEl = document.getElementById('dataWidth');
    const dataHeightEl = document.getElementById('dataHeight');
    const dataRotateEl = document.getElementById('dataRotate');
    const dataScaleXEl = document.getElementById('dataScaleX');
    if (dataXEl) dataXEl.value = Math.round(data.x);
    if (dataYEl) dataYEl.value = Math.round(data.y);
    if (dataWidthEl) dataWidthEl.value = Math.round(data.width);
    if (dataHeightEl) dataHeightEl.value = Math.round(data.height);
    if (dataRotateEl) dataRotateEl.value = (typeof data.rotate !== 'undefined' ? data.rotate : '');
    if (dataScaleXEl) dataScaleXEl.value = (typeof data.scaleX !== 'undefined' ? data.scaleX : '');
}

// Load image and show inline cropper
function loadImageForCropping(event) {
    const file = event.target.files[0];
    if (!file) return;
    if (!file.type.match('image.*')) {
        alert('Please select an image file');
        return;
    }
    const reader = new FileReader();
    reader.onload = function(e) {
        imageElement = document.getElementById('image-to-crop');
        imageElement.src = e.target.result;
        resizeCropperContainer();
        document.getElementById('image-cropper-container').style.display = 'block';
        const uploadAreaEl = document.getElementById('profileUploadArea');
        if (uploadAreaEl) uploadAreaEl.style.display = 'none';
        const previewContainer = document.getElementById('cropped-preview-container');
        if (previewContainer) previewContainer.style.display = 'none';
        imageElement.onload = function() {
            if (cropper) { cropper.destroy(); }
            cropper = new Cropper(imageElement, {
                aspectRatio: 1,
                viewMode: 2,
                autoCropArea: 1,
                responsive: true,
                guides: true,
                center: true,
                dragMode: 'move',
                zoomable: true,
                zoomOnWheel: true,
                cropBoxMovable: true,
                cropBoxResizable: true,
                ready: function() {
                    window.dispatchEvent(new Event('resize'));
                    const cropBoxData = cropper.getCropBoxData();
                    const dataXEl = document.getElementById('dataX');
                    const dataYEl = document.getElementById('dataY');
                    const dataWidthEl = document.getElementById('dataWidth');
                    const dataHeightEl = document.getElementById('dataHeight');
                    const dataRotateEl = document.getElementById('dataRotate');
                    const dataScaleXEl = document.getElementById('dataScaleX');
                    if (dataXEl) dataXEl.value = Math.round(cropBoxData.left);
                    if (dataYEl) dataYEl.value = Math.round(cropBoxData.top);
                    if (dataWidthEl) dataWidthEl.value = Math.round(cropBoxData.width);
                    if (dataHeightEl) dataHeightEl.value = Math.round(cropBoxData.height);
                    if (dataRotateEl) dataRotateEl.value = 0;
                    if (dataScaleXEl) dataScaleXEl.value = 1;
                },
                crop: updateCropBoxData,
                toggleDragModeOnDblclick: true
            });
        };
    };
    reader.readAsDataURL(file);
}

// Dynamically size the cropper container
function resizeCropperContainer() {
    const container = document.querySelector('#image-cropper-container .img-container');
    if (!container) return;
    const vh = Math.max(document.documentElement.clientHeight || 0, window.innerHeight || 0);
    const target = Math.max(320, Math.min(Math.round(vh * 0.6), 800));
    container.style.height = target + 'px';
}
window.addEventListener('resize', resizeCropperContainer);
</script>

<!-- Parent/Guardian address copy functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function setupAddressCopy(checkboxId, fieldsContainerId, mapping) {
            const checkbox = document.getElementById(checkboxId);
            const fieldsContainer = document.getElementById(fieldsContainerId);

            const source = {
                house: document.getElementById('current_house_no'),
                barangay: document.getElementById('current_barangay'),
                city: document.getElementById('current_municipality'),
                province: document.getElementById('current_province'),
                postal: document.getElementById('current_zip_code'),
            };

            const target = {
                house: document.getElementById(mapping.house),
                barangay: document.getElementById(mapping.barangay),
                city: document.getElementById(mapping.city),
                province: document.getElementById(mapping.province),
                postal: document.getElementById(mapping.postal),
            };

            function copy() {
                Object.keys(target).forEach(k => {
                    if (target[k] && source[k]) {
                        target[k].value = source[k].value || '';
                    }
                });
            }

            function setReadonly(readonly) {
                Object.values(target).forEach(el => {
                    if (!el) return;
                    el.readOnly = readonly;
                    if (!readonly) el.removeAttribute('readonly');
                });
            }

            function toggleFields(hide) {
                if (fieldsContainer) {
                    fieldsContainer.style.display = hide ? 'none' : 'block';
                }
            }

            if (checkbox) {
                checkbox.addEventListener('change', function() {
                    if (checkbox.checked) {
                        copy();
                        setReadonly(true);
                        toggleFields(true);
                    } else {
                        setReadonly(false);
                        toggleFields(false);
                        Object.values(target).forEach(el => { if (el) el.value = ''; });
                    }
                });

                // Keep values synced while checked
                Object.values(source).forEach(el => {
                    if (!el) return;
                    el.addEventListener('input', function() {
                        if (checkbox.checked) copy();
                    });
                });
            }
        }

        // Initialize for Parent and Guardian
        setupAddressCopy('parent_same_address', 'parent_address_fields', {
            house: 'parent_house_no',
            barangay: 'parent_barangay',
            city: 'parent_municipality',
            province: 'parent_province',
            postal: 'parent_zip_code',
        });

        setupAddressCopy('guardian_same_address', 'guardian_address_fields', {
            house: 'guardian_house_no',
            barangay: 'guardian_barangay',
            city: 'guardian_municipality',
            province: 'guardian_province',
            postal: 'guardian_zip_code',
        });
    });
</script>
<script>
// Profile upload initializer and helpers (match Edit page)
function initializeProfileUpload() {
    const uploadArea = document.getElementById('profileUploadArea');
    const fileInput = document.getElementById('profile_picture');
    const fileInfo = document.getElementById('fileSelectedInfo');
    const fileNameEl = document.getElementById('selectedFileName');
    const fileSizeEl = document.getElementById('selectedFileSize');
    const croppedPreviewContainer = document.getElementById('cropped-preview-container');
    const cropperContainer = document.getElementById('image-cropper-container');
    const croppedImageData = document.getElementById('cropped_image_data');

    if (!uploadArea || !fileInput) return;

    uploadArea.addEventListener('click', () => fileInput.click());
    ['dragenter','dragover'].forEach(ev => uploadArea.addEventListener(ev, (e) => {
        e.preventDefault(); e.stopPropagation(); uploadArea.classList.add('dragging');
    }));
    ['dragleave','drop'].forEach(ev => uploadArea.addEventListener(ev, (e) => {
        e.preventDefault(); e.stopPropagation(); uploadArea.classList.remove('dragging');
    }));
    uploadArea.addEventListener('drop', (e) => {
        if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]) {
            fileInput.files = e.dataTransfer.files;
            const event = new Event('change');
            fileInput.dispatchEvent(event);
        }
    });

    fileInput.addEventListener('change', (event) => {
        const file = fileInput.files && fileInput.files[0];
        if (!file) { fileInfo && (fileInfo.style.display = 'none'); return; }
        fileNameEl && (fileNameEl.textContent = file.name);
        fileSizeEl && (fileSizeEl.textContent = `${(file.size/1024).toFixed(1)} KB`);
        fileInfo && (fileInfo.style.display = 'block');
        loadImageForCropping(event);
    });

    window.cropImage = function() {
        if (!cropper) return;
        const canvas = cropper.getCroppedCanvas({ width: 400, height: 400 });
        const dataUrl = canvas.toDataURL('image/jpeg');
        const preview = document.getElementById('cropped-image-preview');
        if (preview) { preview.src = dataUrl; preview.style.display = 'inline-block'; }
        if (croppedPreviewContainer) { croppedPreviewContainer.style.display = 'block'; }
        if (cropperContainer) { cropperContainer.style.display = 'none'; }
        if (croppedImageData) { croppedImageData.value = dataUrl; }
    };

    window.cancelCrop = function() {
        if (cropper) { cropper.destroy(); cropper = null; }
        if (cropperContainer) { cropperContainer.style.display = 'none'; }
        if (croppedPreviewContainer) { croppedPreviewContainer.style.display = 'none'; }
        const preview = document.getElementById('cropped-image-preview');
        if (preview) { preview.src = ''; }
        if (fileInput) { fileInput.value = ''; }
        if (croppedImageData) { croppedImageData.value = ''; }
        if (fileInfo) { fileInfo.style.display = 'none'; }
        if (uploadArea) { uploadArea.style.display = 'block'; }
    };

    window.removeSelectedFile = function() {
        if (fileInput) fileInput.value = '';
        if (fileInfo) fileInfo.style.display = 'none';
        cancelCrop();
    };
}

document.addEventListener('DOMContentLoaded', function () {
  try { initializeProfileUpload(); } catch (err) { console.error('Profile upload init failed:', err); }
});
</script>

<script>
    // Wait for jQuery to be available
    function waitForJQuery(callback) {
        if (typeof $ !== 'undefined') {
            callback();
        } else {
            setTimeout(function() {
                waitForJQuery(callback);
            }, 100);
        }
    }

    // Global variables (cropper and imageElement are already declared earlier)
    let currentStep = 1;
    const totalSteps = 4;
    
    // TESTING MODE FLAG - Set to false to re-enable all validation
    const DISABLE_ALL_VALIDATION = true;

    // Function to update crop box data inputs (null-safe; supports camelCase and dash IDs)
    function updateCropBoxData(evt) {
        if (!cropper) return;

        const data = (evt && evt.detail) ? evt.detail : cropper.getData();
        const dataX = document.getElementById('dataX') || document.getElementById('data-x');
        const dataY = document.getElementById('dataY') || document.getElementById('data-y');
        const dataWidth = document.getElementById('dataWidth') || document.getElementById('data-width');
        const dataHeight = document.getElementById('dataHeight') || document.getElementById('data-height');
        const dataRotate = document.getElementById('dataRotate') || document.getElementById('data-rotate');
        const dataScaleX = document.getElementById('dataScaleX') || document.getElementById('data-scale-x');
        const dataScaleY = document.getElementById('dataScaleY') || document.getElementById('data-scale-y');

        if (dataX) dataX.value = Math.round(data.x);
        if (dataY) dataY.value = Math.round(data.y);
        if (dataWidth) dataWidth.value = Math.round(data.width);
        if (dataHeight) dataHeight.value = Math.round(data.height);
        if (dataRotate) dataRotate.value = (typeof data.rotate !== 'undefined' ? Math.round(data.rotate) : '');
        if (dataScaleX) dataScaleX.value = (typeof data.scaleX !== 'undefined' ? Number(data.scaleX).toFixed(2) : '');
        if (dataScaleY) dataScaleY.value = (typeof data.scaleY !== 'undefined' ? Number(data.scaleY).toFixed(2) : '');
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

            // Hide the upload area and any previous preview to focus on cropping
            const uploadAreaEl = document.getElementById('profileUploadArea');
            if (uploadAreaEl) uploadAreaEl.style.display = 'none';
            const previewContainerEl = document.getElementById('cropped-preview-container');
            if (previewContainerEl) previewContainerEl.style.display = 'none';

            // Hide the legacy preview image if it was shown before
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
                    viewMode: 1, // Restrict the crop box to not exceed the size of the canvas
                    guides: true, // Show the dashed lines for guiding
                    center: true, // Show the center indicator for guiding
                    dragMode: 'move', // Define the dragging mode of the cropper
                    zoomable: true, // Enable to zoom the image
                    zoomOnWheel: true, // Enable to zoom the image by wheeling mouse
                    cropBoxMovable: true, // Enable to move the crop box
                    cropBoxResizable: true, // Enable to resize the crop box
                    toggleDragModeOnDblclick: true, // Toggle drag mode between "crop" and "move" when double click on the cropper
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

        // TEMPORARILY DISABLED FOR TESTING - Step validation when navigating
        /*
        // Validate form fields if moving forward - TEMPORARILY DISABLED
        if (stepNumber > currentStep) {
            if (stepNumber === 2 && !validateStudentInfoStep()) {
                return;
            }
            
            if (stepNumber === 3 && !validateAddressFamilyStep()) {
                return;
            }
            
            if (stepNumber === 4 && !validateAcademicSpecialNeedsStep()) {
                return;
            }
        }
        */

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
        if (stepNumber === 4) {
            console.log('Calling populateReviewData for step 4');
            // Use setTimeout to ensure the DOM is updated before populating review data
            setTimeout(() => {
                console.log('DOM should be updated now, calling populateReviewData');
                populateReviewData();
            }, 50);
        }

        // Show/hide navigation buttons based on current step
        updateNavigationButtons();
    }

    // TEMPORARILY DISABLED FOR TESTING - Student info validation function
    function validateStudentInfoStep() {
        // Check global testing flag
        if (DISABLE_ALL_VALIDATION) {
            return true;
        }
        // VALIDATION TEMPORARILY DISABLED FOR TESTING - Always return true
        return true;
        /*
        let isValid = true;
        // TEMPORARILY REMOVED: 'section' from required fields
        const requiredFields = ['lrn', 'name', 'gender', 'age', 'grade_level'];
        
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
        */
    }

    // Function to validate profile picture step
    function validateProfilePictureStep() {
        // Profile picture is optional, so always return true
        return true;
    }

    // TEMPORARILY DISABLED FOR TESTING - Address and family validation function  
    function validateAddressFamilyStep() {
        // Check global testing flag
        if (DISABLE_ALL_VALIDATION) {
            return true;
        }
        // VALIDATION TEMPORARILY DISABLED FOR TESTING - Always return true
        return true;
        /*
        let isValid = true;
        const requiredFields = ['address', 'guardian', 'contact'];
        
        requiredFields.forEach(field => {
            const input = document.getElementById(field);
            if (input && !input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else if (input) {
                input.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            alert('Please fill in all required address and family information before proceeding.');
        }
        
        return isValid;
        */
    }

    // Function to validate academic and special needs step
    function validateAcademicSpecialNeedsStep() {
        // This step contains profile picture which is optional
        // Add any other validation as needed
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
            // Profile Picture
            const croppedImageData = document.getElementById('cropped_image_data')?.value || '';
            const reviewPicture = document.getElementById('review-profile-picture');
            const noImageText = document.querySelector('#review-profile-picture-container p.text-muted');
            if (reviewPicture) {
                if (croppedImageData) {
                    reviewPicture.src = croppedImageData;
                    reviewPicture.style.display = 'inline-block';
                    if (noImageText) noImageText.style.display = 'none';
                } else {
                    reviewPicture.style.display = 'none';
                    if (noImageText) noImageText.style.display = 'block';
                }
            }

            // Student Personal Information
            const firstName = document.getElementById('first_name')?.value || '';
            const middleName = document.getElementById('middle_name')?.value || '';
            const lastName = document.getElementById('last_name')?.value || '';
            const fullName = `${firstName} ${middleName} ${lastName}`.trim();

            const reviewStudentName = document.getElementById('review-student-name');
            if (reviewStudentName) reviewStudentName.textContent = fullName || 'Not provided';
            
            const reviewBirthDate = document.getElementById('review-birth-date');
            if (reviewBirthDate) reviewBirthDate.textContent = document.getElementById('date_of_birth')?.value || 'Not provided';
            
            const reviewPlaceOfBirth = document.getElementById('review-place-of-birth');
            if (reviewPlaceOfBirth) reviewPlaceOfBirth.textContent = document.getElementById('place_of_birth')?.value || 'Not provided';
            
            const reviewAge = document.getElementById('review-age');
            if (reviewAge) reviewAge.textContent = document.getElementById('age')?.value || 'Not provided';
            
            const reviewGender = document.getElementById('review-gender');
            if (reviewGender) reviewGender.textContent = document.getElementById('gender')?.value || 'Not provided';

            // Academic Information
            const reviewLrn = document.getElementById('review-lrn');
            if (reviewLrn) reviewLrn.textContent = document.getElementById('lrn')?.value || 'Not provided';

            const gradeLevelSelect = document.getElementById('grade_level');
            const reviewGradeLevel = document.getElementById('review-grade-level');
            if (reviewGradeLevel) {
                reviewGradeLevel.textContent = gradeLevelSelect?.selectedIndex > 0 ?
                    gradeLevelSelect.options[gradeLevelSelect.selectedIndex].text : 'Not provided';
            }

            // TEMPORARILY COMMENTED OUT - Section field review population
            /*
            const sectionSelect = document.getElementById('section');
            const reviewSection = document.getElementById('review-section');
            if (reviewSection) {
                reviewSection.textContent = sectionSelect?.value || 'Not provided';
            }
            */

            const reviewSchoolYear = document.getElementById('review-school-year');
            if (reviewSchoolYear) reviewSchoolYear.textContent = document.getElementById('school_year')?.value || 'Not provided';

            // Handle Student Type (now radio buttons, not select)
            const studentTypeRadios = document.getElementsByName('student_type');
            const reviewStudentType = document.getElementById('review-student-type');
            if (reviewStudentType) {
                let selectedStudentType = 'Not provided';
                for (const radio of studentTypeRadios) {
                    if (radio.checked) {
                        selectedStudentType = radio.value;
                        break;
                    }
                }
                reviewStudentType.textContent = selectedStudentType;
            }

            const reviewGeneralAverage = document.getElementById('review-general-average');
            if (reviewGeneralAverage) reviewGeneralAverage.textContent = document.getElementById('general_average')?.value || 'Not provided';
            
            const reviewConductGrade = document.getElementById('review-conduct-grade');
            if (reviewConductGrade) reviewConductGrade.textContent = document.getElementById('conduct_grade')?.value || 'Not provided';

            // Current Address
            const reviewCurrentHouseNo = document.getElementById('review-current-house-no');
            if (reviewCurrentHouseNo) reviewCurrentHouseNo.textContent = document.getElementById('current_house_no')?.value || 'Not provided';
            
            const reviewCurrentStreet = document.getElementById('review-current-street');
            if (reviewCurrentStreet) reviewCurrentStreet.textContent = document.getElementById('current_street')?.value || 'Not provided';
            
            const reviewCurrentBarangay = document.getElementById('review-current-barangay');
            if (reviewCurrentBarangay) reviewCurrentBarangay.textContent = document.getElementById('current_barangay')?.value || 'Not provided';
            
            const reviewCurrentMunicipality = document.getElementById('review-current-municipality');
            if (reviewCurrentMunicipality) reviewCurrentMunicipality.textContent = document.getElementById('current_municipality')?.value || 'Not provided';
            
            const reviewCurrentProvince = document.getElementById('review-current-province');
            if (reviewCurrentProvince) reviewCurrentProvince.textContent = document.getElementById('current_province')?.value || 'Not provided';
            
            const reviewCurrentCountry = document.getElementById('review-current-country');
            if (reviewCurrentCountry) reviewCurrentCountry.textContent = document.getElementById('current_country')?.value || 'Not provided';
            
            const reviewCurrentZip = document.getElementById('review-current-zip');
            if (reviewCurrentZip) reviewCurrentZip.textContent = document.getElementById('current_zip_code')?.value || 'Not provided';

            // Permanent Address
            const reviewPermanentHouseStreet = document.getElementById('review-permanent-house-street');
            if (reviewPermanentHouseStreet) reviewPermanentHouseStreet.textContent = document.getElementById('permanent_house_no')?.value || 'Not provided';
            
            const reviewPermanentStreetName = document.getElementById('review-permanent-street-name');
            if (reviewPermanentStreetName) reviewPermanentStreetName.textContent = document.getElementById('permanent_street_name')?.value || 'Not provided';
            
            const reviewPermanentBarangay = document.getElementById('review-permanent-barangay');
            if (reviewPermanentBarangay) reviewPermanentBarangay.textContent = document.getElementById('permanent_barangay')?.value || 'Not provided';
            
            const reviewPermanentMunicipality = document.getElementById('review-permanent-municipality');
            if (reviewPermanentMunicipality) reviewPermanentMunicipality.textContent = document.getElementById('permanent_municipality')?.value || 'Not provided';
            
            const reviewPermanentProvince = document.getElementById('review-permanent-province');
            if (reviewPermanentProvince) reviewPermanentProvince.textContent = document.getElementById('permanent_province')?.value || 'Not provided';
            
            const reviewPermanentCountry = document.getElementById('review-permanent-country');
            if (reviewPermanentCountry) reviewPermanentCountry.textContent = document.getElementById('permanent_country')?.value || 'Not provided';
            
            const reviewPermanentZip = document.getElementById('review-permanent-zip');
            if (reviewPermanentZip) reviewPermanentZip.textContent = document.getElementById('permanent_zip_code')?.value || 'Not provided';

            // Parent/Guardian Information
            const fatherFirstName = document.getElementById('father_first_name')?.value || '';
            const fatherMiddleName = document.getElementById('father_middle_name')?.value || '';
            const fatherLastName = document.getElementById('father_last_name')?.value || '';
            const fatherFullName = `${fatherFirstName} ${fatherMiddleName} ${fatherLastName}`.trim();
            
            const reviewFatherName = document.getElementById('review-father-name');
            if (reviewFatherName) reviewFatherName.textContent = fatherFullName || 'Not provided';
            
            const reviewFatherContact = document.getElementById('review-father-contact');
            if (reviewFatherContact) reviewFatherContact.textContent = document.getElementById('father_contact')?.value || 'Not provided';

            const motherFirstName = document.getElementById('mother_first_name')?.value || '';
            const motherMiddleName = document.getElementById('mother_middle_name')?.value || '';
            const motherLastName = document.getElementById('mother_last_name')?.value || '';
            const motherFullName = `${motherFirstName} ${motherMiddleName} ${motherLastName}`.trim();
            
            const reviewMotherName = document.getElementById('review-mother-name');
            if (reviewMotherName) reviewMotherName.textContent = motherFullName || 'Not provided';
            
            const reviewMotherContact = document.getElementById('review-mother-contact');
            if (reviewMotherContact) reviewMotherContact.textContent = document.getElementById('mother_contact')?.value || 'Not provided';

            const guardianFirstName = document.getElementById('guardian_first_name')?.value || '';
            const guardianMiddleName = document.getElementById('guardian_middle_name')?.value || '';
            const guardianLastName = document.getElementById('guardian_last_name')?.value || '';
            const guardianFullName = `${guardianFirstName} ${guardianMiddleName} ${guardianLastName}`.trim();
            
            const reviewGuardianName = document.getElementById('review-guardian-name');
            if (reviewGuardianName) reviewGuardianName.textContent = guardianFullName || 'Not provided';
            
            const reviewGuardianContact = document.getElementById('review-guardian-contact');
            if (reviewGuardianContact) reviewGuardianContact.textContent = document.getElementById('guardian_contact_number')?.value || 'Not provided';

            // Parent/Guardian Address Information
            const reviewParentHouseNo = document.getElementById('review-parent-house-no');
            if (reviewParentHouseNo) reviewParentHouseNo.textContent = document.getElementById('parent_house_no')?.value || 'Not provided';
            
            const reviewParentBarangay = document.getElementById('review-parent-barangay');
            if (reviewParentBarangay) reviewParentBarangay.textContent = document.getElementById('parent_barangay')?.value || 'Not provided';
            
            const reviewParentMunicipality = document.getElementById('review-parent-municipality');
            if (reviewParentMunicipality) reviewParentMunicipality.textContent = document.getElementById('parent_municipality')?.value || 'Not provided';
            
            const reviewParentProvince = document.getElementById('review-parent-province');
            if (reviewParentProvince) reviewParentProvince.textContent = document.getElementById('parent_province')?.value || 'Not provided';
            
            const reviewParentZip = document.getElementById('review-parent-zip');
            if (reviewParentZip) reviewParentZip.textContent = document.getElementById('parent_zip_code')?.value || 'Not provided';

            const reviewGuardianHouseNo = document.getElementById('review-guardian-house-no');
            if (reviewGuardianHouseNo) reviewGuardianHouseNo.textContent = document.getElementById('guardian_house_no')?.value || 'Not provided';
            
            const reviewGuardianBarangay = document.getElementById('review-guardian-barangay');
            if (reviewGuardianBarangay) reviewGuardianBarangay.textContent = document.getElementById('guardian_barangay')?.value || 'Not provided';
            
            const reviewGuardianMunicipality = document.getElementById('review-guardian-municipality');
            if (reviewGuardianMunicipality) reviewGuardianMunicipality.textContent = document.getElementById('guardian_municipality')?.value || 'Not provided';
            
            const reviewGuardianProvince = document.getElementById('review-guardian-province');
            if (reviewGuardianProvince) reviewGuardianProvince.textContent = document.getElementById('guardian_province')?.value || 'Not provided';
            
            const reviewGuardianZip = document.getElementById('review-guardian-zip');
            if (reviewGuardianZip) reviewGuardianZip.textContent = document.getElementById('guardian_zip_code')?.value || 'Not provided';

            // Special Programs & Benefits
            const indigenousPeople = document.getElementById('is_indigenous')?.value === 'Yes' ? 'Yes' : 'No';
            const reviewIndigenousPeople = document.getElementById('review-indigenous-people');
            if (reviewIndigenousPeople) reviewIndigenousPeople.textContent = indigenousPeople;
            
            const reviewIndigenousGroup = document.getElementById('review-indigenous-community');
            if (reviewIndigenousGroup) reviewIndigenousGroup.textContent = document.getElementById('indigenous_group')?.value || 'Not provided';

            const fourpsBeneficiary = document.getElementById('is_4ps_beneficiary')?.value === 'Yes' ? 'Yes' : 'No';
            const reviewFourpsBeneficiary = document.getElementById('review-fourps-beneficiary');
            if (reviewFourpsBeneficiary) reviewFourpsBeneficiary.textContent = fourpsBeneficiary;
            
            const reviewFourpsHouseholdId = document.getElementById('review-fourps-household-id');
            if (reviewFourpsHouseholdId) reviewFourpsHouseholdId.textContent = document.getElementById('household_id')?.value || 'Not provided';

            // Special Needs & Disabilities
            const hasDisabilityRadios = document.getElementsByName('has_disability');
            let hasDisability = 'No';
            for (const radio of hasDisabilityRadios) {
                if (radio.checked) {
                    hasDisability = radio.value;
                    break;
                }
            }
            
            const reviewHasDisability = document.getElementById('review-has-disability');
            if (reviewHasDisability) reviewHasDisability.textContent = hasDisability;
            
            // Get selected disability types
            const disabilityCheckboxes = document.querySelectorAll('input[name="disability_types[]"]:checked');
            const disabilityTypes = Array.from(disabilityCheckboxes).map(cb => cb.value).join(', ');
            
            const reviewDisabilityType = document.getElementById('review-disability-type');
            if (reviewDisabilityType) reviewDisabilityType.textContent = disabilityTypes || 'Not provided';
            
            const reviewAssistiveDevice = document.getElementById('review-assistive-device');
            if (reviewAssistiveDevice) reviewAssistiveDevice.textContent = document.getElementById('assistive_device')?.value || 'Not provided';

            console.log('Review data populated successfully');
        } catch (error) {
            console.error('Error populating review data:', error);
        }
    }

    // Duplicate goToStep function removed - using the main one defined earlier

    // TEMPORARILY DISABLED FOR TESTING - Duplicate student info validation function
    function validateStudentInfoStep() {
        // VALIDATION TEMPORARILY DISABLED FOR TESTING - Always return true
        return true;
        /*
        let isValid = true;
        // TEMPORARILY REMOVED: 'section' from required fields
        const requiredFields = ['lrn', 'name', 'gender', 'age', 'grade_level'];
        
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
        */
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
                    // TEMPORARILY COMMENTED OUT - Section debug logging
                    // console.log('Section:', document.getElementById('section').value);
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
        const aspect11El = document.getElementById('aspect-1-1'); if (aspect11El) aspect11El.addEventListener('click', function() {
            if (!cropper) return;
            cropper.setAspectRatio(1);
        });

        const aspect43El = document.getElementById('aspect-4-3'); if (aspect43El) aspect43El.addEventListener('click', function() {
            if (!cropper) return;
            cropper.setAspectRatio(4 / 3);
        });

        const aspectFreeEl = document.getElementById('aspect-free'); if (aspectFreeEl) aspectFreeEl.addEventListener('click', function() {
            if (!cropper) return;
            cropper.setAspectRatio(NaN);
        });

        // Crop button click event
        const legacyCropBtn = document.getElementById('crop-image'); if (legacyCropBtn) legacyCropBtn.addEventListener('click', function() {
            console.log('Crop button clicked');
            if (!cropper) {
                console.error('Cropper not initialized');
                return;
            }

            // Get the cropped canvas
            const canvas = cropper.getCroppedCanvas({
                width: 300, // Output image width
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

        // Hide the cropper container if present
        const cropperContainerEl = document.getElementById('image-cropper-container');
        if (cropperContainerEl) {
            cropperContainerEl.style.display = 'none';
        }
        });

        // Cancel button click event (guarded to avoid errors if element is absent)
        const cancelCropEl = document.getElementById('cancel-crop');
        if (cancelCropEl) {
        cancelCropEl.addEventListener('click', function() {
            // Hide the cropper
            document.getElementById('image-cropper-container').style.display = 'none';

            // Clear the file input
            const legacyFileInput = document.getElementById('profile_picture');
            if (legacyFileInput) {
                legacyFileInput.value = '';
            }

            // Destroy the cropper
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }

            // Hide the preview if it was shown
            const legacyPreview = document.getElementById('cropped-image-preview');
            if (legacyPreview) {
                legacyPreview.style.display = 'none';
            }

            // Clear data inputs (supports both camelCase and dash IDs)
            const idsToClear = ['dataX','dataY','dataWidth','dataHeight','dataRotate','dataScaleX','dataScaleY','data-x','data-y','data-width','data-height','data-rotate','data-scale-x','data-scale-y'];
            idsToClear.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
        });
        }

        // TEMPORARILY DISABLED FOR TESTING - Form validation on submit
        /*
        // Form validation on submit - TEMPORARILY DISABLED
        document.querySelector('form').addEventListener('submit', function(e) {
            // Final validation before submission - TEMPORARILY DISABLED FOR TESTING
            if (!validateStudentInfoStep()) {
                e.preventDefault();
                goToStep(1);
            }
        });
        */
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
                <div class="step-title">Address & Family</div>
            </div>
            <div class="wizard-step" data-step="3">
                <div class="step-number">3</div>
                <div class="step-title">Academic & Special Needs</div>
            </div>
            <div class="wizard-step" data-step="4">
                <div class="step-number">4</div>
                <div class="step-title">Review & Submit</div>
            </div>
        </div>

        <form action="<?= site_url('admin/student/store') ?>" method="POST" enctype="multipart/form-data" novalidate>
            <?= csrf_field() ?>

            <!-- Step 1: Student Information -->
            <div id="step-1" class="form-wizard-content active">
                <!-- Instructions -->
                <div class="alert alert-info mb-4">
                    <h6><i class="fas fa-info-circle"></i> Instructions</h6>
                    <p class="mb-0">Please fill out all required information accurately and completely. This form will create a new student record in the system.</p>
                </div>

                <!-- Basic Information Section -->
                <div class="row mb-5">
                    <div class="col-12">
                        <h5 class="text-primary border-bottom pb-2 mb-4">Basic Information</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="school_year" class="font-weight-bold">School Year <span class="text-danger">*</span></label>
                        <input type="text" id="school_year" name="school_year" value="<?= old('school_year', date('Y') . '-' . (date('Y') + 1)) ?>" class="form-control mt-2">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Student Type <span class="text-danger">*</span></label>
                        <div class="mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="student_type" id="student_type_new" value="New Learner (With LRN)" <?= old('student_type') == 'New Learner (With LRN)' ? 'checked' : '' ?> onchange="toggleStudentTypeFields()">
                                <label class="form-check-label" for="student_type_new">New Learner (With LRN)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="student_type" id="student_type_returning" value="Returning (Balik-Aral)" <?= old('student_type') == 'Returning (Balik-Aral)' ? 'checked' : '' ?> onchange="toggleStudentTypeFields()">
                                <label class="form-check-label" for="student_type_returning">Returning (Balik-Aral)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="student_type" id="student_type_transfer" value="Transfer Enrollment" <?= old('student_type') == 'Transfer Enrollment' ? 'checked' : '' ?> onchange="toggleStudentTypeFields()">
                                <label class="form-check-label" for="student_type_transfer">Transfer Enrollment</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold" for="lrn_digit_0">Learner Reference No. (LRN) <span class="text-danger">*</span></label>
                        <div class="lrn-boxes mb-2">
                            <input type="text" name="lrn_digit_0" id="lrn_digit_0" class="lrn-box" maxlength="1" pattern="[0-9]" data-required="true">
                            <input type="text" name="lrn_digit_1" class="lrn-box" maxlength="1" pattern="[0-9]" data-required="true">
                            <input type="text" name="lrn_digit_2" class="lrn-box" maxlength="1" pattern="[0-9]" data-required="true">
                            <input type="text" name="lrn_digit_3" class="lrn-box" maxlength="1" pattern="[0-9]" data-required="true">
                            <input type="text" name="lrn_digit_4" class="lrn-box" maxlength="1" pattern="[0-9]" data-required="true">
                            <input type="text" name="lrn_digit_5" class="lrn-box" maxlength="1" pattern="[0-9]" data-required="true">
                            <input type="text" name="lrn_digit_6" class="lrn-box" maxlength="1" pattern="[0-9]" data-required="true">
                            <input type="text" name="lrn_digit_7" class="lrn-box" maxlength="1" pattern="[0-9]" data-required="true">
                            <input type="text" name="lrn_digit_8" class="lrn-box" maxlength="1" pattern="[0-9]" data-required="true">
                            <input type="text" name="lrn_digit_9" class="lrn-box" maxlength="1" pattern="[0-9]" data-required="true">
                            <input type="text" name="lrn_digit_10" class="lrn-box" maxlength="1" pattern="[0-9]" data-required="true">
                            <input type="text" name="lrn_digit_11" class="lrn-box" maxlength="1" pattern="[0-9]" data-required="true">
                        </div>
                        <small class="form-text text-muted">12-digit Learner Reference Number</small>
                        <!-- Hidden field to store combined LRN -->
                        <input type="hidden" id="lrn" name="lrn" value="<?= old('lrn') ?>">
                    </div>
                    <div class="col-md-6 mb-3" id="psa_birth_cert_container">
                        <label for="psa_birth_cert_no" class="font-weight-bold">PSA Birth Certificate No.</label>
                        <input type="text" id="psa_birth_cert_no" name="psa_birth_cert_no" value="<?= old('psa_birth_cert_no') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="font-weight-bold">Last Name <span class="text-danger">*</span></label>
                        <input type="text" id="last_name" name="last_name" value="<?= old('last_name') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="first_name" class="font-weight-bold">First Name <span class="text-danger">*</span></label>
                        <input type="text" id="first_name" name="first_name" value="<?= old('first_name') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="middle_name" class="font-weight-bold">Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name" value="<?= old('middle_name') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="extension_name" class="font-weight-bold">Extension Name (Jr., Sr., III, etc.)</label>
                        <input type="text" id="extension_name" name="extension_name" value="<?= old('extension_name') ?>" class="form-control mt-2">
                    </div>
                    <!-- Keep the full name field for backward compatibility -->
                    <div class="col-md-12 mb-3" style="display: none;">
                        <input type="text" id="name" name="name" value="<?= old('name') ?>" class="form-control">
                    </div>
                </div>

                <!-- Additional Information for Returning/Transfer Students -->
                <div class="row mb-5" id="student_type_additional_fields" style="display: none;">
                    <div class="col-12">
                        <h6 class="text-secondary mb-3">Previous School Information</h6>
                    </div>
                    <div class="col-md-4 mb-3" id="last_school_container" style="display: none;">
                        <label for="last_school_attended" class="font-weight-bold">Last School Attended</label>
                        <input type="text" id="last_school_attended" name="last_school_attended" value="<?= old('last_school_attended') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-md-4 mb-3" id="last_grade_container" style="display: none;">
                        <label for="last_grade_completed" class="font-weight-bold">Last Grade Completed</label>
                        <select id="last_grade_completed" name="last_grade_completed" class="form-control mt-2">
                            <option value="">Select Grade</option>
                            <?php for ($i = 6; $i <= 12; $i++): ?>
                                <option value="<?= $i ?>" <?= old('last_grade_completed') == $i ? 'selected' : '' ?>>Grade <?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3" id="school_year_container" style="display: none;">
                        <label for="last_school_year" class="font-weight-bold">School Year Completed</label>
                        <input type="text" id="last_school_year" name="last_school_year" value="<?= old('last_school_year') ?>" class="form-control mt-2" placeholder="e.g., 2022-2023">
                    </div>
                </div>

                <!-- Personal Details -->
                <div class="row mb-5">
                    <div class="col-12">
                        <h5 class="text-primary border-bottom pb-2 mb-4">Personal Details</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="date_of_birth" class="font-weight-bold">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="<?= old('date_of_birth') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="place_of_birth" class="font-weight-bold">Place of Birth</label>
                        <input type="text" id="place_of_birth" name="place_of_birth" value="<?= old('place_of_birth') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="gender" class="font-weight-bold">Gender <span class="text-danger">*</span></label>
                        <select id="gender" name="gender" class="form-control mt-2">
                            <option value="">Select Gender</option>
                            <option value="Male" <?= old('gender') == 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= old('gender') == 'Female' ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="age" class="font-weight-bold">Age <span class="text-danger">*</span></label>
                        <input type="number" id="age" name="age" value="<?= old('age') ?>" class="form-control mt-2" min="1" max="100">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="mother_tongue" class="font-weight-bold">Mother Tongue</label>
                        <input type="text" id="mother_tongue" name="mother_tongue" value="<?= old('mother_tongue') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="email" class="font-weight-bold">Email Address</label>
                        <input type="email" id="email" name="email" value="<?= old('email') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="phone_number" class="font-weight-bold">Phone Number</label>
                        <input type="text" id="phone_number" name="phone_number" value="<?= old('phone_number') ?>" class="form-control mt-2">
                    </div>

                </div>

                <!-- Profile Picture Upload Section (Moved from Step 2) -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="text-center">
                            <div class="profile-picture-section">
                                <!-- Enhanced File Upload Area (consistent with Edit) -->
                                <div class="profile-upload-container">
                                    <div class="profile-upload-area" id="profileUploadArea">
                                        <div class="upload-content">
                                            <div class="upload-icon"><i class="fas fa-camera"></i></div>
                                            <h6 class="upload-title">Upload Profile Picture</h6>
                                            <p class="upload-description">Drag and drop your image here or click to browse</p>
                                            <div class="upload-formats">
                                                <span class="format-badge">JPG</span>
                                                <span class="format-badge">PNG</span>
                                                <span class="format-badge">GIF</span>
                                            </div>
                                            <p class="upload-size-info">Maximum file size: 2MB</p>
                                        </div>
                                    </div>
                                    <!-- Hidden file input used by drag-and-drop and click-to-upload -->
                                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*" style="display:none" onchange="loadImageForCropping(event)">
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
                                            <button type="button" class="remove-file-btn" onclick="removeSelectedFile()" aria-label="Remove file">×</button>
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
                                            <div class="aspect-ratio-buttons mb-3">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="cropper && cropper.setAspectRatio(1)">1:1</button>
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="cropper && cropper.setAspectRatio(4/3)">4:3</button>
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="cropper && cropper.setAspectRatio(NaN)">Free</button>
                                            </div>
                                            <div class="mb-3">
                                                <button type="button" class="btn btn-success btn-block" onclick="cropImage()">Crop & Preview</button>
                                                <button type="button" class="btn btn-secondary btn-block" onclick="cancelCrop()">Cancel</button>
                                            </div>
                                            <div class="mb-2">
                                                <label class="small">Zoom:</label>
                                                <div class="btn-group btn-group-sm d-flex" role="group">
                                                    <button type="button" class="btn btn-outline-secondary" onclick="cropper && cropper.zoom(-0.1)">-</button>
                                                    <button type="button" class="btn btn-outline-secondary" onclick="cropper && cropper.zoom(0.1)">+</button>
                                                </div>
                                            </div>
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

                

                <!-- Indigenous & Special Programs -->
                <div class="row mb-5">
                    <div class="col-12">
                        <h5 class="text-primary border-bottom pb-2 mb-4">Indigenous & Special Programs</h5>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="is_indigenous" class="font-weight-bold">Indigenous People (IP)</label>
                        <select id="is_indigenous" name="is_indigenous" class="form-control mt-2">
                            <option value="No" <?= old('is_indigenous') == 'No' ? 'selected' : '' ?>>No</option>
                            <option value="Yes" <?= old('is_indigenous') == 'Yes' ? 'selected' : '' ?>>Yes</option>
                        </select>
                    </div>
                    <div class="col-12 mb-3" id="indigenous_group_container" style="display: none;">
                        <label for="indigenous_group" class="font-weight-bold">Indigenous Group</label>
                        <input type="text" id="indigenous_group" name="indigenous_group" value="<?= old('indigenous_group') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="is_4ps_beneficiary" class="font-weight-bold">4Ps Beneficiary</label>
                        <select id="is_4ps_beneficiary" name="is_4ps_beneficiary" class="form-control mt-2">
                            <option value="No" <?= old('is_4ps_beneficiary') == 'No' ? 'selected' : '' ?>>No</option>
                            <option value="Yes" <?= old('is_4ps_beneficiary') == 'Yes' ? 'selected' : '' ?>>Yes</option>
                        </select>
                    </div>
                    <div class="col-12 mb-3" id="household_id_container" style="display: none;">
                        <label for="household_id" class="font-weight-bold">4Ps Household ID</label>
                        <input type="text" id="household_id" name="household_id" value="<?= old('household_id') ?>" class="form-control mt-2">
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="row mb-5">
                    <div class="col-12">
                        <h5 class="text-primary border-bottom pb-2 mb-4">Academic Information</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="grade_level" class="font-weight-bold">Grade Level <span class="text-danger">*</span></label>
                        <select id="grade_level" name="grade_level" class="form-control mt-2" data-required="true">
                            <option value="">Select Grade Level</option>
                            <?php for ($i = 7; $i <= 12; $i++): ?>
                                <option value="<?= $i ?>" <?= old('grade_level') == $i ? 'selected' : '' ?>>Grade <?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <!-- TEMPORARILY COMMENTED OUT - Section field and container -->
                    <!--
                    <div class="col-md-6 mb-3">
                        <label for="section" class="font-weight-bold">Section <span class="text-danger">*</span></label>
                        <input type="text" id="section" name="section" value="<?= old('section') ?>" class="form-control mt-2" data-required="true">
                        <div class="invalid-feedback">The section field is required.</div>
                    </div>
                    -->
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

            <!-- Step 2: Address & Family -->
            <div id="step-2" class="form-wizard-content">
                <!-- Current Address -->
                <div class="row mb-5">
                    <div class="col-12">
                        <h5 class="text-primary border-bottom pb-2 mb-4">Student Address</h5>
                    </div>
                    <div class="col-12">
                        <h6 class="text-secondary mb-4">Current Address</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="current_house_no" class="font-weight-bold">House No./Street</label>
                        <input type="text" id="current_house_no" name="current_house_no" value="<?= old('current_house_no') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="current_barangay" class="font-weight-bold">Barangay</label>
                        <input type="text" id="current_barangay" name="current_barangay" value="<?= old('current_barangay') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="current_municipality" class="font-weight-bold">Municipality/City</label>
                        <input type="text" id="current_municipality" name="current_municipality" value="<?= old('current_municipality') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="current_province" class="font-weight-bold">Province</label>
                        <input type="text" id="current_province" name="current_province" value="<?= old('current_province') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="current_zip_code" class="font-weight-bold">ZIP Code</label>
                        <input type="text" id="current_zip_code" name="current_zip_code" value="<?= old('current_zip_code') ?>" class="form-control mt-2">
                    </div>
                </div>

                <!-- Permanent Address -->
                <div class="row mb-5">
                    <div class="col-12">
                        <h6 class="text-secondary mb-4">Permanent Address</h6>
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="same_as_current" name="same_as_current" onchange="togglePermanentAddress()">
                                    <label class="form-check-label" for="same_as_current">
                                        Same as current address
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="permanent_address_fields" class="col-12">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="permanent_house_no" class="font-weight-bold">House No./Street</label>
                                <input type="text" id="permanent_house_no" name="permanent_house_no" value="<?= old('permanent_house_no') ?>" class="form-control mt-2">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="permanent_barangay" class="font-weight-bold">Barangay</label>
                                <input type="text" id="permanent_barangay" name="permanent_barangay" value="<?= old('permanent_barangay') ?>" class="form-control mt-2">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="permanent_municipality" class="font-weight-bold">Municipality/City</label>
                                <input type="text" id="permanent_municipality" name="permanent_municipality" value="<?= old('permanent_municipality') ?>" class="form-control mt-2">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="permanent_province" class="font-weight-bold">Province</label>
                                <input type="text" id="permanent_province" name="permanent_province" value="<?= old('permanent_province') ?>" class="form-control mt-2">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="permanent_zip_code" class="font-weight-bold">ZIP Code</label>
                                <input type="text" id="permanent_zip_code" name="permanent_zip_code" value="<?= old('permanent_zip_code') ?>" class="form-control mt-2">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Parents Information Header -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-primary border-bottom pb-2 mb-4">Parents Information</h5>
                    </div>
                </div>

                <!-- Father's Information -->
                <div class="row mb-5">
                    <div class="col-12">
                        <h6 class="text-primary pb-2 mb-4">Father's Information</h6>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="father_last_name" class="font-weight-bold">Last Name</label>
                        <input type="text" id="father_last_name" name="father_last_name" value="<?= old('father_last_name') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="father_first_name" class="font-weight-bold">First Name</label>
                        <input type="text" id="father_first_name" name="father_first_name" value="<?= old('father_first_name') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="father_middle_name" class="font-weight-bold">Middle Name</label>
                        <input type="text" id="father_middle_name" name="father_middle_name" value="<?= old('father_middle_name') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="father_contact" class="font-weight-bold">Contact Number</label>
                        <input type="text" id="father_contact" name="father_contact" value="<?= old('father_contact') ?>" class="form-control mt-2">
                    </div>
                </div>

                <!-- Mother's Information -->
                <div class="row mb-5">
                    <div class="col-12">
                        <h6 class="text-primary pb-2 mb-4">Mother's Information</h6>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="mother_last_name" class="font-weight-bold">Last Name</label>
                        <input type="text" id="mother_last_name" name="mother_last_name" value="<?= old('mother_last_name') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="mother_first_name" class="font-weight-bold">First Name</label>
                        <input type="text" id="mother_first_name" name="mother_first_name" value="<?= old('mother_first_name') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="mother_middle_name" class="font-weight-bold">Middle Name</label>
                        <input type="text" id="mother_middle_name" name="mother_middle_name" value="<?= old('mother_middle_name') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="mother_contact" class="font-weight-bold">Contact Number</label>
                        <input type="text" id="mother_contact" name="mother_contact" value="<?= old('mother_contact') ?>" class="form-control mt-2">
                    </div>
                </div>

                <!-- Legal Guardian Information -->
                <div class="row mb-5">
                    <div class="col-12">
                        <h6 class="text-primary pb-2 mb-4">Guardian Information</h6>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="guardian_last_name" class="font-weight-bold">Last Name</label>
                        <input type="text" id="guardian_last_name" name="guardian_last_name" value="<?= old('guardian_last_name') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="guardian_first_name" class="font-weight-bold">First Name</label>
                        <input type="text" id="guardian_first_name" name="guardian_first_name" value="<?= old('guardian_first_name') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="guardian_middle_name" class="font-weight-bold">Middle Name</label>
                        <input type="text" id="guardian_middle_name" name="guardian_middle_name" value="<?= old('guardian_middle_name') ?>" class="form-control mt-2">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="guardian_contact_number" class="font-weight-bold">Contact Number</label>
                        <input type="text" id="guardian_contact_number" name="guardian_contact_number" value="<?= old('guardian_contact_number') ?>" class="form-control mt-2">
                    </div>
                </div>

                <!-- Parent/Guardian Address Information -->
                <div class="row mb-5">
                    <div class="col-12">
                        <h5 class="text-primary border-bottom pb-2 mb-4">Parent/Guardian Address Information</h5>
                    </div>

                    <!-- Parent's Address -->
                    <div class="col-12 mb-4">
                        <h6 class="text-secondary mb-3">Parent's Address</h6>
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" name="parent_same_address" id="parent_same_address" class="form-check-input">
                                    <label for="parent_same_address" class="form-check-label">Same as Student's Current Address</label>
                                </div>
                            </div>
                        </div>
                        <div id="parent_address_fields" class="address-fields">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="parent_house_no" class="font-weight-bold">House No./Street</label>
                                    <input type="text" id="parent_house_no" name="parent_house_no" value="<?= old('parent_house_no') ?>" class="form-control mt-2">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="parent_barangay" class="font-weight-bold">Barangay</label>
                                    <input type="text" id="parent_barangay" name="parent_barangay" value="<?= old('parent_barangay') ?>" class="form-control mt-2">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="parent_municipality" class="font-weight-bold">Municipality/City</label>
                                    <input type="text" id="parent_municipality" name="parent_municipality" value="<?= old('parent_municipality') ?>" class="form-control mt-2">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="parent_province" class="font-weight-bold">Province</label>
                                    <input type="text" id="parent_province" name="parent_province" value="<?= old('parent_province') ?>" class="form-control mt-2">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="parent_zip_code" class="font-weight-bold">ZIP Code</label>
                                    <input type="text" id="parent_zip_code" name="parent_zip_code" value="<?= old('parent_zip_code') ?>" class="form-control mt-2">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Guardian's Address -->
                    <div class="col-12 mb-4">
                        <h6 class="text-secondary mb-3">Guardian's Address</h6>
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" name="guardian_same_address" id="guardian_same_address" class="form-check-input">
                                    <label for="guardian_same_address" class="form-check-label">Same as Student's Current Address</label>
                                </div>
                            </div>
                        </div>
                        <div id="guardian_address_fields" class="address-fields">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="guardian_house_no" class="font-weight-bold">House No./Street</label>
                                    <input type="text" id="guardian_house_no" name="guardian_house_no" value="<?= old('guardian_house_no') ?>" class="form-control mt-2">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="guardian_barangay" class="font-weight-bold">Barangay</label>
                                    <input type="text" id="guardian_barangay" name="guardian_barangay" value="<?= old('guardian_barangay') ?>" class="form-control mt-2">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="guardian_municipality" class="font-weight-bold">Municipality/City</label>
                                    <input type="text" id="guardian_municipality" name="guardian_municipality" value="<?= old('guardian_municipality') ?>" class="form-control mt-2">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="guardian_province" class="font-weight-bold">Province</label>
                                    <input type="text" id="guardian_province" name="guardian_province" value="<?= old('guardian_province') ?>" class="form-control mt-2">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="guardian_zip_code" class="font-weight-bold">ZIP Code</label>
                                    <input type="text" id="guardian_zip_code" name="guardian_zip_code" value="<?= old('guardian_zip_code') ?>" class="form-control mt-2">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Legacy fields for backward compatibility -->
                <div style="display: none;">
                    <textarea id="address" name="address" class="form-control"><?= old('address') ?></textarea>
                    <input type="text" id="guardian" name="guardian" value="<?= old('guardian') ?>" class="form-control">
                    <input type="text" id="contact" name="contact" value="<?= old('contact') ?>" class="form-control">
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

            <!-- Step 3: Academic & Special Needs -->
            <div id="step-3" class="form-wizard-content">
                <!-- Disability Information -->
                <div class="row mb-5">
                    <div class="col-12">
                        <h5 class="text-primary border-bottom pb-2 mb-4">Disability Information</h5>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="font-weight-bold">Does the student have any disability?</label>
                        <div class="mt-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="has_disability" id="has_disability_no" value="No" <?= old('has_disability', 'No') == 'No' ? 'checked' : '' ?> onchange="toggleDisabilityCheckboxes()">
                                <label class="form-check-label" for="has_disability_no">No</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="has_disability" id="has_disability_yes" value="Yes" <?= old('has_disability') == 'Yes' ? 'checked' : '' ?> onchange="toggleDisabilityCheckboxes()">
                                <label class="form-check-label" for="has_disability_yes">Yes</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-3" id="disability_types_container" style="display: none;">
                        <label class="font-weight-bold">Type of Disability (Select all that apply)</label>
                        <div class="mt-2 row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="disability_types[]" id="visual_impairment" value="Visual Impairment" <?= in_array('Visual Impairment', old('disability_types', [])) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="visual_impairment">Visual Impairment</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="disability_types[]" id="hearing_impairment" value="Hearing Impairment" <?= in_array('Hearing Impairment', old('disability_types', [])) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="hearing_impairment">Hearing Impairment</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="disability_types[]" id="learning_disability" value="Learning Disability" <?= in_array('Learning Disability', old('disability_types', [])) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="learning_disability">Learning Disability</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="disability_types[]" id="intellectual_disability" value="Intellectual Disability" <?= in_array('Intellectual Disability', old('disability_types', [])) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="intellectual_disability">Intellectual Disability</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="disability_types[]" id="blind" value="Blind" <?= in_array('Blind', old('disability_types', [])) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="blind">Blind</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="disability_types[]" id="autism_spectrum" value="Autism Spectrum Disorder" <?= in_array('Autism Spectrum Disorder', old('disability_types', [])) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="autism_spectrum">Autism Spectrum Disorder</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="disability_types[]" id="emotional_behavioral" value="Emotional-Behavioral Disorder" <?= in_array('Emotional-Behavioral Disorder', old('disability_types', [])) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="emotional_behavioral">Emotional-Behavioral Disorder</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="disability_types[]" id="orthopedic_physical" value="Orthopedic/Physical Handicap" <?= in_array('Orthopedic/Physical Handicap', old('disability_types', [])) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="orthopedic_physical">Orthopedic/Physical Handicap</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="disability_types[]" id="multiple_disorder" value="Multiple Disorder" <?= in_array('Multiple Disorder', old('disability_types', [])) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="multiple_disorder">Multiple Disorder</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="disability_types[]" id="speech_language" value="Speech/Language Disorder" <?= in_array('Speech/Language Disorder', old('disability_types', [])) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="speech_language">Speech/Language Disorder</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="disability_types[]" id="cerebral_palsy" value="Cerebral Palsy" <?= in_array('Cerebral Palsy', old('disability_types', [])) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="cerebral_palsy">Cerebral Palsy</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="disability_types[]" id="special_health" value="Special Health Problem/Chronic Disease" <?= in_array('Special Health Problem/Chronic Disease', old('disability_types', [])) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="special_health">Special Health Problem/Chronic Disease</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="disability_types[]" id="cancer" value="Cancer" <?= in_array('Cancer', old('disability_types', [])) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="cancer">Cancer</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Performance -->
                <div class="row mb-5">
                    <div class="col-12">
                        <h5 class="text-primary border-bottom pb-2 mb-4">Academic Performance</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="general_average" class="font-weight-bold">General Average (Previous Grade)</label>
                        <input type="number" id="general_average" name="general_average" value="<?= old('general_average') ?>" class="form-control mt-2" min="65" max="100" step="0.01" oninput="generateConductGrade()">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Academic Performance Level <span class="text-muted">(Auto-calculated)</span></label>
                        <input type="text" id="conduct_grade" name="conduct_grade" class="form-control" readonly placeholder="Will be determined based on General Average">
                        <small class="form-text text-muted">Conduct grade will be automatically determined based on academic performance.</small>
                    </div>
                </div>

                <!-- Senior High School Information (for Grades 11-12) -->
                <div class="row mb-5" id="shs_info_container" style="display: none;">
                    <div class="col-12">
                        <h5 class="text-primary border-bottom pb-2 mb-4">Senior High School Information</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="track" class="font-weight-bold">Track</label>
                        <select id="track" name="track" class="form-control mt-2" onchange="updateStrandOptions()">
                            <option value="">Select Track</option>
                            <option value="Academic Track" <?= old('track') == 'Academic Track' ? 'selected' : '' ?>>Academic Track</option>
                            <option value="Technical-Vocational-Livelihood (TVL)" <?= old('track') == 'Technical-Vocational-Livelihood (TVL)' ? 'selected' : '' ?>>Technical-Vocational-Livelihood (TVL)</option>
                            <option value="Sports Track" <?= old('track') == 'Sports Track' ? 'selected' : '' ?>>Sports Track</option>
                            <option value="Arts and Design Track" <?= old('track') == 'Arts and Design Track' ? 'selected' : '' ?>>Arts and Design Track</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="strand" class="font-weight-bold">Strand</label>
                        <select id="strand" name="strand" class="form-control mt-2">
                            <option value="">Select Strand</option>
                            <!-- Options will be populated by JavaScript based on track selection -->
                        </select>
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
                    <button type="button" class="btn btn-primary wizard-btn-next px-4">
                        Next <i class="fa fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- Step 2: Profile Picture (Removed - moved to Step 1) -->

            <!-- Step 4: Review & Submit -->
            <div id="step-4" class="form-wizard-content">
                <div class="section-header">Review Your Information</div>

                <div class="alert alert-warning mb-4">
                    <h6><i class="fas fa-exclamation-triangle"></i> Please Review Carefully</h6>
                    <p class="mb-0">Please review all information below before submitting. Make sure all details are correct as this will be used for official school records.</p>
                </div>

                <!-- Profile Picture Preview (Top Center) -->
                <div id="review-profile-picture-container" class="mb-4 text-center">
                    <img id="review-profile-picture" alt="Profile Picture" class="rounded-circle" style="width: 140px; height: 140px; object-fit: cover; display: none;" />
                    <p class="text-muted" style="margin-top: 8px;">No profile picture uploaded.</p>
                </div>

                <!-- Review Summary Cards -->
                <div class="row mb-4">
                    <!-- Student Personal Information -->
                    <div class="col-md-6 mb-3 d-flex">
                        <div class="card w-100">
                            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-dark">Student Personal Information</h6>
                                <a href="#" class="text-primary text-decoration-none" onclick="goToStep(1)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <p><strong>Full Name:</strong> <span id="review-student-name">-</span></p>
                                <p><strong>Birth Date:</strong> <span id="review-birth-date">-</span></p>
                                <p><strong>Place of Birth:</strong> <span id="review-place-of-birth">-</span></p>
                                <p><strong>Age:</strong> <span id="review-age">-</span></p>
                                <p class="mb-0"><strong>Gender:</strong> <span id="review-gender">-</span></p>
                                <div class="flex-grow-1"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="col-md-6 mb-3 d-flex">
                        <div class="card w-100">
                            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-dark">Academic Information</h6>
                                <a href="#" class="text-primary text-decoration-none" onclick="goToStep(1)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                            </div>
                            <div class="card-body">
                                <p><strong>LRN:</strong> <span id="review-lrn">-</span></p>
                                <p><strong>Grade Level:</strong> <span id="review-grade-level">-</span></p>
                                <!-- TEMPORARILY COMMENTED OUT - Section field in review -->
                                <!-- <p><strong>Section:</strong> <span id="review-section">-</span></p> -->
                                <p><strong>School Year:</strong> <span id="review-school-year">-</span></p>
                                <p><strong>Student Type:</strong> <span id="review-student-type">-</span></p>
                                <p><strong>General Average:</strong> <span id="review-general-average">-</span></p>
                                <p class="mb-0"><strong>Conduct Grade:</strong> <span id="review-conduct-grade">-</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3 d-flex">
                        <div class="card w-100">
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

                    <div class="col-md-6 mb-3 d-flex">
                        <div class="card w-100">
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
                    <div class="col-md-4 mb-3 d-flex">
                        <div class="card w-100">
                            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-dark">Father's Information</h6>
                                <a href="#" class="text-primary text-decoration-none" onclick="goToStep(2)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                            </div>
                            <div class="card-body">
                                <p><strong>Name:</strong> <span id="review-father-name">-</span></p>
                                <p class="mb-0"><strong>Contact:</strong> <span id="review-father-contact">-</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3 d-flex">
                        <div class="card w-100">
                            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-dark">Mother's Information</h6>
                                <a href="#" class="text-primary text-decoration-none" onclick="goToStep(2)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                            </div>
                            <div class="card-body">
                                <p><strong>Name:</strong> <span id="review-mother-name">-</span></p>
                                <p class="mb-0"><strong>Contact:</strong> <span id="review-mother-contact">-</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3 d-flex">
                        <div class="card w-100">
                            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-dark">Guardian's Information</h6>
                                <a href="#" class="text-primary text-decoration-none" onclick="goToStep(2)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                            </div>
                            <div class="card-body">
                                <p><strong>Name:</strong> <span id="review-guardian-name">-</span></p>
                                <p class="mb-0"><strong>Contact:</strong> <span id="review-guardian-contact">-</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Parent/Guardian Address Summary -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3 d-flex">
                        <div class="card w-100">
                            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-dark">Parent's Address</h6>
                                <a href="#" class="text-primary text-decoration-none" onclick="goToStep(2)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                            </div>
                            <div class="card-body">
                                <p><strong>House No./Street:</strong> <span id="review-parent-house-no">-</span></p>
                                <p><strong>Barangay:</strong> <span id="review-parent-barangay">-</span></p>
                                <p><strong>Municipality/City:</strong> <span id="review-parent-municipality">-</span></p>
                                <p><strong>Province:</strong> <span id="review-parent-province">-</span></p>
                                <p><strong>Zip Code:</strong> <span id="review-parent-zip">-</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3 d-flex">
                        <div class="card w-100">
                            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-dark">Guardian's Address</h6>
                                <a href="#" class="text-primary text-decoration-none" onclick="goToStep(2)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                            </div>
                            <div class="card-body">
                                <p><strong>House No./Street:</strong> <span id="review-guardian-house-no">-</span></p>
                                <p><strong>Barangay:</strong> <span id="review-guardian-barangay">-</span></p>
                                <p><strong>Municipality/City:</strong> <span id="review-guardian-municipality">-</span></p>
                                <p><strong>Province:</strong> <span id="review-guardian-province">-</span></p>
                                <p><strong>Zip Code:</strong> <span id="review-guardian-zip">-</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3 d-flex">
                        <div class="card w-100">
                            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-dark">Special Programs & Benefits</h6>
                                <a href="#" class="text-primary text-decoration-none" onclick="goToStep(3)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                            </div>
                            <div class="card-body">
                                <p><strong>Indigenous Peoples:</strong> <span id="review-indigenous-people">-</span></p>
                                <p><strong>IP Community:</strong> <span id="review-indigenous-community">-</span></p>
                                <p><strong>4Ps Beneficiary:</strong> <span id="review-fourps-beneficiary">-</span></p>
                                <p><strong>4Ps Household ID:</strong> <span id="review-fourps-household-id">-</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3 d-flex">
                        <div class="card w-100">
                            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-dark">Special Needs & Disabilities</h6>
                                <a href="#" class="text-primary text-decoration-none" onclick="goToStep(3)" style="font-size: 0.875rem;"><i class="fas fa-edit me-1"></i>Edit</a>
                            </div>
                            <div class="card-body">
                                <p><strong>Has Disability:</strong> <span id="review-has-disability">-</span></p>
                                <p><strong>Disability Type:</strong> <span id="review-disability-type">-</span></p>
                                <p><strong>Assistive Device:</strong> <span id="review-assistive-device">-</span></p>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Step 4 Navigation -->
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
<script src="/backend/src/plugins/sweetalert2/sweetalert2.all.js"></script>
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
                            // lowerMessage.includes('section') ||
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
                    callback({
                        isConfirmed: true
                    });
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
                // lowerMessage.includes('section') ||
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

<!-- Dynamic field handling functions -->
<script>
    // TEMPORARILY DISABLED FOR TESTING - Indigenous People validation (keeping UI functionality)
    function toggleIndigenousDetails() {
        const select = document.getElementById('is_indigenous');
        const detailsDiv = document.getElementById('indigenous_group_container');

        if (select && detailsDiv) {
            if (select.value === 'Yes') {
                detailsDiv.style.display = 'block';
                // DISABLED FOR TESTING - Don't set required attribute or data-required
                // document.getElementById('indigenous_group').setAttribute('data-required', 'true');
            } else {
                detailsDiv.style.display = 'none';
                // document.getElementById('indigenous_group').removeAttribute('data-required');
                document.getElementById('indigenous_group').value = '';
            }
        }
    }

    // TEMPORARILY DISABLED FOR TESTING - 4Ps Beneficiary validation (keeping UI functionality)
    function toggle4PsDetails() {
        const select = document.getElementById('is_4ps_beneficiary');
        const detailsDiv = document.getElementById('household_id_container');

        if (select && detailsDiv) {
            if (select.value === 'Yes') {
                detailsDiv.style.display = 'block';
                // DISABLED FOR TESTING - Don't set required attribute or data-required
                // document.getElementById('household_id').setAttribute('data-required', 'true');
            } else {
                detailsDiv.style.display = 'none';
                // document.getElementById('household_id').removeAttribute('data-required');
                document.getElementById('household_id').value = '';
            }
        }
    }

    // Same as current address functionality - Enhanced version with hide/show
    document.addEventListener('DOMContentLoaded', function() {
        const cb = document.getElementById('same_as_current');
        const permanentDiv = document.getElementById('permanent_address_fields');
        const cur = {
            house: document.getElementById('current_house_no'),
            barangay: document.getElementById('current_barangay'),
            city: document.getElementById('current_municipality'),
            province: document.getElementById('current_province'),
            postal: document.getElementById('current_zip_code'),
        };
        const perm = {
            house: document.getElementById('permanent_house_no'),
            barangay: document.getElementById('permanent_barangay'),
            city: document.getElementById('permanent_municipality'),
            province: document.getElementById('permanent_province'),
            postal: document.getElementById('permanent_zip_code'),
        };

        function copyValues() {
            Object.keys(cur).forEach(k => {
                if (cur[k] && perm[k]) perm[k].value = cur[k].value || '';
            });
        }

        function setReadonly(readonly) {
            Object.values(perm).forEach(el => {
                if (!el) return;
                el.readOnly = readonly;
                if (!readonly) el.removeAttribute('readonly');
            });
        }

        function togglePermanentFields(hide) {
            if (permanentDiv) {
                permanentDiv.style.display = hide ? 'none' : 'block';
            }
        }

        if (cb) {
            cb.addEventListener('change', function() {
                if (cb.checked) {
                    // Copy values first, then hide the fields
                    copyValues();
                    setReadonly(true);
                    togglePermanentFields(true);
                } else {
                    // Show fields first, then make them editable
                    togglePermanentFields(false);
                    setReadonly(false);
                }
            });

            // Keep values in sync while checked (even though fields are hidden)
            Object.values(cur).forEach(el => {
                if (!el) return;
                el.addEventListener('input', function() {
                    if (cb.checked) copyValues();
                });
            });
        } else {
            console.warn('same_as_current checkbox not found');
        }
    });

    // Legacy functions for backward compatibility
    function togglePermanentAddress() {
        // This function is now handled by the enhanced DOMContentLoaded implementation above
        // Keeping for any external references
    }

    function copyCurrentToPermanent() {
        // This function is now handled by the enhanced copyValues function above
        // Keeping for any external references
    }

    function clearPermanentAddress() {
        // This function is now handled by the enhanced setReadonly function above
        // Keeping for any external references
    }

    // Handle enrollment type change
    function toggleEnrollmentFields() {
        // PSA Birth Certificate field is now always visible
        // This function can be kept for future use if needed
    }

    // Handle LRN digit input boxes
    function setupLRNBoxes() {
        const lrnBoxes = document.querySelectorAll('.lrn-box');
        const hiddenLRNField = document.getElementById('lrn');

        lrnBoxes.forEach((box, index) => {
            // DISABLED FOR TESTING - Validation feedback classes
            /*
            // Add touched class on first interaction
            box.addEventListener('focus', function() {
                this.classList.add('touched');
            });

            box.addEventListener('blur', function() {
                this.classList.add('touched');
            });
            */

            // Auto-focus next box on input (keeping functionality, disabling validation)
            box.addEventListener('input', function(e) {
                // DISABLED FOR TESTING - Validation feedback
                // this.classList.add('touched');
                const value = e.target.value;

                // DISABLED FOR TESTING - Input validation
                /*
                // Only allow numeric input
                if (!/^[0-9]$/.test(value)) {
                    e.target.value = '';
                    return;
                }
                */

                // Move to next box if current is filled
                if (value && index < lrnBoxes.length - 1) {
                    lrnBoxes[index + 1].focus();
                }

                // Update hidden LRN field
                updateLRNField();
            });

            // Handle backspace to move to previous box
            box.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    lrnBoxes[index - 1].focus();
                }
            });

            // Handle paste event
            box.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text');

                // Only process if pasted data contains only digits
                if (/^\d+$/.test(pastedData)) {
                    const digits = pastedData.split('');

                    // Fill boxes starting from current position
                    for (let i = 0; i < digits.length && (index + i) < lrnBoxes.length; i++) {
                        lrnBoxes[index + i].value = digits[i];
                    }

                    // Focus on the next empty box or last filled box
                    const nextIndex = Math.min(index + digits.length, lrnBoxes.length - 1);
                    lrnBoxes[nextIndex].focus();

                    updateLRNField();
                }
            });
        });

        // Function to update hidden LRN field
        function updateLRNField() {
            let lrnValue = '';
            lrnBoxes.forEach(box => {
                lrnValue += box.value || '';
            });
            if (hiddenLRNField) {
                hiddenLRNField.value = lrnValue;
            }
        }

        // Initialize LRN boxes with existing value if any
        if (hiddenLRNField && hiddenLRNField.value) {
            const existingLRN = hiddenLRNField.value;
            for (let i = 0; i < existingLRN.length && i < lrnBoxes.length; i++) {
                lrnBoxes[i].value = existingLRN[i];
            }
        }
    }

    // Handle disability radio buttons
    function toggleDisabilityCheckboxes() {
        const yesRadio = document.getElementById('has_disability_yes');
        const typesContainer = document.getElementById('disability_types_container');

        if (yesRadio && typesContainer) {
            if (yesRadio.checked) {
                typesContainer.style.display = 'block';
            } else {
                typesContainer.style.display = 'none';
                // Uncheck all disability type checkboxes when "No" is selected
                const checkboxes = typesContainer.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            }
        }
    }

    // Generate conduct grade based on general average
    function generateConductGrade() {
        const generalAverageInput = document.getElementById('general_average');
        const conductGradeInput = document.getElementById('conduct_grade');

        if (generalAverageInput && conductGradeInput) {
            const average = parseFloat(generalAverageInput.value);
            let conductGrade = '';

            if (isNaN(average) || generalAverageInput.value === '') {
                conductGrade = '';
            } else if (average >= 95) {
                conductGrade = 'Excellent';
            } else if (average >= 90) {
                conductGrade = 'Very Good';
            } else if (average >= 85) {
                conductGrade = 'Good';
            } else if (average >= 80) {
                conductGrade = 'Fair';
            } else if (average >= 65) {
                conductGrade = 'Poor';
            } else {
                conductGrade = '';
            }

            // Set the conduct grade value
            conductGradeInput.value = conductGrade;
        }
    }

    // Handle student type change
    function toggleStudentTypeFields() {
        // Get the selected radio button value
        const studentTypeRadios = document.getElementsByName('student_type');
        let selectedValue = null;
        
        for (const radio of studentTypeRadios) {
            if (radio.checked) {
                selectedValue = radio.value;
                break;
            }
        }

        // Get the containers
        const additionalFieldsContainer = document.getElementById('student_type_additional_fields');
        const lastSchoolContainer = document.getElementById('last_school_container');
        const lastGradeContainer = document.getElementById('last_grade_container');
        const schoolYearContainer = document.getElementById('school_year_container');

        // Hide all containers first
        if (additionalFieldsContainer) additionalFieldsContainer.style.display = 'none';
        if (lastSchoolContainer) lastSchoolContainer.style.display = 'none';
        if (lastGradeContainer) lastGradeContainer.style.display = 'none';
        if (schoolYearContainer) schoolYearContainer.style.display = 'none';

        // Clear required attributes
        const lastSchoolField = document.getElementById('last_school_attended');
        const lastGradeField = document.getElementById('last_grade_completed');
        const schoolYearField = document.getElementById('last_school_year');
        
        if (lastSchoolField) lastSchoolField.removeAttribute('data-required');
        if (lastGradeField) lastGradeField.removeAttribute('data-required');
        if (schoolYearField) schoolYearField.removeAttribute('data-required');

        // Show relevant containers based on selection
        if (selectedValue === 'Returning (Balik-Aral)' || selectedValue === 'Transfer Enrollment') {
            if (additionalFieldsContainer) additionalFieldsContainer.style.display = 'block';
            if (lastSchoolContainer) lastSchoolContainer.style.display = 'block';
            if (lastGradeContainer) lastGradeContainer.style.display = 'block';
            if (schoolYearContainer) schoolYearContainer.style.display = 'block';
            
            // DISABLED FOR TESTING - Don't set any validation attributes
            // if (lastSchoolField) lastSchoolField.setAttribute('data-required', 'true');
            // if (lastGradeField) lastGradeField.setAttribute('data-required', 'true');
        }
    }

    // Keep the old function name for backward compatibility
    function toggleStudentTypeDetails() {
        toggleStudentTypeFields();
    }
</script>

<!-- Form submission handling -->
<script>
    // Hydrate hidden full name field from name parts
    function hydrateFullName() {
        const first = (document.getElementById('first_name')?.value || '').trim();
        const middle = (document.getElementById('middle_name')?.value || '').trim();
        const last = (document.getElementById('last_name')?.value || '').trim();
        const ext = (document.getElementById('extension_name')?.value || '').trim();
        const parts = [first, middle, last].filter(Boolean);
        let full = parts.join(' ');
        if (ext) full = full ? (full + ' ' + ext) : ext;
        const hidden = document.getElementById('name');
        if (hidden) hidden.value = full;
    }

    // Compose hidden LRN value from digit boxes
    function hydrateLRN() {
        const boxes = document.querySelectorAll('.lrn-box');
        const hidden = document.getElementById('lrn');
        if (!hidden || boxes.length === 0) return;
        hidden.value = Array.from(boxes).map(b => (b.value || '').trim()).join('');
    }

    // Keep hidden fields updated while typing
    document.addEventListener('DOMContentLoaded', function() {
        ['first_name','middle_name','last_name','extension_name'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', hydrateFullName);
            if (el) el.addEventListener('change', hydrateFullName);
        });
        document.querySelectorAll('.lrn-box').forEach(el => {
            el.addEventListener('input', hydrateLRN);
            el.addEventListener('change', hydrateLRN);
        });
        // Initial hydration on load
        hydrateFullName();
        hydrateLRN();
    });

    waitForJQuery(function() {
        $(document).ready(function() {
            // DISABLED FOR TESTING - Don't even set data-required attributes
            /*
            // CRITICAL: Remove all required attributes on page load to prevent browser validation
            $('input[required], select[required], textarea[required]').each(function() {
                $(this).removeAttr('required').attr('data-required', 'true');
            });
            */
            
            // TESTING MODE: Just remove all required attributes without marking them
            $('input[required], select[required], textarea[required]').each(function() {
                $(this).removeAttr('required');
            });

            // Initialize wizard navigation
            updateNavigationButtons();

            // Initialize dynamic field handlers
            $(document).on('change', '#is_indigenous', toggleIndigenousDetails);
            $(document).on('change', '#is_4ps_beneficiary', toggle4PsDetails);
            // Note: same_as_current is now handled by the enhanced DOMContentLoaded implementation
            $(document).on('change', 'input[name="has_disability"]', toggleDisabilityCheckboxes);
            $(document).on('change', 'input[name="student_type"]', toggleStudentTypeFields);
            $(document).on('change', 'input[name="enrollment_type"]', toggleEnrollmentFields);

            // Initialize LRN boxes functionality
            setupLRNBoxes();

            // Note: Current address field listeners are now handled by the enhanced DOMContentLoaded implementation

            // Wizard step click handlers
            $('.wizard-step').on('click', function() {
                const stepNumber = parseInt($(this).attr('data-step'));
                goToStep(stepNumber);
            });

            // Next button click handler - REMOVED (duplicate of DOMContentLoaded handler)
            // $('.wizard-btn-next').on('click', function() {
            //     if (currentStep < totalSteps) {
            //         goToStep(currentStep + 1);
            //     }
            // });

            // Previous button click handler
            $('.wizard-btn-prev').on('click', function() {
                if (currentStep > 1) {
                    goToStep(currentStep - 1);
                }
            });

            // Form submission
            $('form').on('submit', function(e) {
                e.preventDefault();

                // Ensure hidden fields are up-to-date before serialization
                hydrateFullName();
                hydrateLRN();

                // COMPLETELY DISABLE HTML5 VALIDATION - Remove all required attributes
                $('input[required], select[required], textarea[required]').each(function() {
                    $(this).removeAttr('required');
                });

                // Handle required fields validation for multi-step form
                // TEMPORARILY REMOVED: 'section' from required fields array
                const allRequiredFields = ['grade_level', 'lrn', 'first_name', 'last_name', 'gender', 'age'];
                allRequiredFields.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        // Always remove required attribute to prevent browser validation
                        field.removeAttribute('required');
                        
                        // Check if field is currently visible and focusable
                        const isVisible = field.offsetParent !== null && 
                                         field.style.display !== 'none' && 
                                         field.style.visibility !== 'hidden' &&
                                         !field.disabled;
                        
                        if (isVisible) {
                            // Field is visible, ensure it's enabled
                            field.disabled = false;
                            field.removeAttribute('readonly');
                        }
                        
                        // Log field status for debugging
                        console.log(`Field ${fieldId}: value="${field.value}", visible=${isVisible}, disabled=${field.disabled}, required=${field.hasAttribute('required')}`);
                    }
                });

                // Custom validation for multi-step form
                function validateRequiredFields() {
                    const validationErrors = [];
                    
                    // Validate LRN
                    const lrnField = document.getElementById('lrn');
                    if (!lrnField || !lrnField.value.trim()) {
                        validationErrors.push('LRN is required');
                    } else if (!/^\d{12}$/.test(lrnField.value.trim())) {
                        validationErrors.push('LRN must be exactly 12 digits');
                    }
                    
                    // Validate name fields
                    const firstNameField = document.getElementById('first_name');
                    const lastNameField = document.getElementById('last_name');
                    if (!firstNameField || !firstNameField.value.trim()) {
                        validationErrors.push('First name is required');
                    }
                    if (!lastNameField || !lastNameField.value.trim()) {
                        validationErrors.push('Last name is required');
                    }
                    
                    // Validate other required fields
                    // TEMPORARILY REMOVED: section field from validation
                    const requiredFields = [
                        { id: 'grade_level', name: 'Grade level' },
                        // { id: 'section', name: 'Section' },
                        { id: 'gender', name: 'Gender' },
                        { id: 'age', name: 'Age' }
                    ];
                    
                    requiredFields.forEach(field => {
                        const element = document.getElementById(field.id);
                        if (!element || !element.value.trim()) {
                            validationErrors.push(`${field.name} is required`);
                        }
                    });
                    
                    return validationErrors;
                }
                
                // TEMPORARILY DISABLED FOR TESTING - Custom validation
                /*
                // Perform validation
                const validationErrors = validateRequiredFields();
                if (validationErrors.length > 0) {
                    const errorMessage = 'Please complete all required fields:\n' + validationErrors.join('\n');
                    simpleAlert('Validation Error', errorMessage, 'warning')
                    .then(() => {
                        // Navigate to step 1 where most required fields are located
                        goToStep(1);
                    });
                    return;
                }
                */

                // Get form data
                const form = $(this);
                const formData = new FormData(this);

                // Debug: Log form data to check if section field is included
                console.log('=== FORM SUBMISSION DEBUG ===');
                // TEMPORARILY COMMENTED OUT - Section field debug logging
                // console.log('Section field value:', document.getElementById('section')?.value);
                // console.log('Section field exists:', !!document.getElementById('section'));
                // console.log('Section field disabled:', document.getElementById('section')?.disabled);
                console.log('Form data entries:');
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}: ${value}`);
                }
                console.log('=== END DEBUG ===');

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

                                // Check if any of the errors are related to fields in step 1
                                // TEMPORARILY REMOVED: 'section' from step1 error handling
                                const step1Fields = ['lrn', 'first_name', 'last_name', 'grade_level', 'gender', 'age'];
                                const step1Error = Object.keys(response.errors).some(field => 
                                    step1Fields.includes(field)
                                );

                                // Check if any of the errors are related to profile picture (step 1 now)
                                const profilePictureError = Object.values(response.errors).some(error =>
                                    error.toLowerCase().includes('profile picture') ||
                                    error.toLowerCase().includes('image')
                                );

                                // Check if any of the errors are related to address/family fields (step 2)
                                const step2Fields = ['current_house_no', 'current_barangay', 'father_first_name', 'mother_first_name'];
                                const step2Error = Object.keys(response.errors).some(field => 
                                    step2Fields.includes(field)
                                );

                                if (step2Error) {
                                    targetStep = 2;
                                } else if (step1Error || profilePictureError) {
                                    targetStep = 1;
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
    });
</script>
<?= $this->endSection() ?>