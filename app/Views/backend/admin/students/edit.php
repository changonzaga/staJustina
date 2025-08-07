
<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Edit Student</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= site_url('admin/student') ?>">Students</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Edit Student
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="pd-20 card-box mb-30">
    <div class="clearfix mb-20">
        <div class="pull-left">
            <h4 class="text-blue h4">Edit Student Information</h4>
            <p class="mb-30">Update student details below</p>
        </div>
    </div>
    <div class="wizard-content">
        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger">
                <?= session('error') ?>
            </div>
        <?php endif; ?>
        
        <?php if (session()->has('success')): ?>
            <div class="alert alert-success">
                <?= session('success') ?>
            </div>
        <?php endif; ?>
        <form action="<?= route_to('admin.student.update', $student['id']) ?>" method="post" enctype="multipart/form-data" id="updateStudentForm">
            <?= csrf_field() ?>
            
            <div class="tab">
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#student_info" role="tab" aria-selected="true">Student Information</a>
                    </li>
                    <li class="nav-item">   
                        <a class="nav-link" data-toggle="tab" href="#profile_pic" role="tab" aria-selected="false">Profile Picture</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="student_info" role="tabpanel">
                        <div class="pd-20">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>LRN</label>
                        <input type="text" name="lrn" class="form-control" value="<?= $student['lrn'] ?>" required pattern="[0-9]{12}" title="LRN must contain exactly 12 numeric digits">
                        <div class="invalid-feedback">LRN must contain exactly 12 numeric digits</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" value="<?= $student['name'] ?>" required>
                        <div class="invalid-feedback">Please enter a valid name</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender" class="form-control" required>
                            <option value="">Select Gender</option>
                            <option value="Male" <?= $student['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= $student['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Age</label>
                        <input type="number" name="age" class="form-control" value="<?= $student['age'] ?>" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Grade Level</label>
                        <select name="grade_level" class="form-control" required>
                            <option value="">Select Grade Level</option>
                            <?php for($i = 7; $i <= 12; $i++): ?>
                                <option value="<?= $i ?>" <?= $student['grade_level'] == $i ? 'selected' : '' ?>>Grade <?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Section</label>
                        <input type="text" name="section" class="form-control" value="<?= $student['section'] ?>" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Guardian</label>
                        <input type="text" name="guardian" class="form-control" value="<?= $student['guardian'] ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="text" name="contact" class="form-control" value="<?= $student['contact'] ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-control"><?= $student['address'] ?></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Assign Teacher</label>
                        <select name="teacher_id" class="form-control custom-select2">
                            <option value="">Select Teacher</option>
                            <?php foreach ($teachers as $teacher): ?>
                                <option value="<?= $teacher['id'] ?>" <?= $student['teacher_id'] == $teacher['id'] ? 'selected' : '' ?>>
                                    <?= $teacher['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Assign Parent</label>
                        <select name="parent_id" class="form-control custom-select2">
                            <option value="">Select Parent</option>
                            <?php foreach ($parents as $parent): ?>
                                <option value="<?= $parent['id'] ?>" <?= $student['parent_id'] == $parent['id'] ? 'selected' : '' ?>>
                                    <?= $parent['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Empty space for future field if needed -->
                </div>
            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile_pic" role="tabpanel">
                        <div class="pd-20">
                            <div class="form-group text-center">
                                <?php if (!empty($student['profile_picture'])): ?>
                                    <div class="mb-4">
                                        <h6 class="text-muted">Current Image:</h6>
                                        <img src="<?= base_url('Uploads/students/' . $student['profile_picture']) ?>" 
                                             alt="Profile" class="mt-2" style="width: 180px; height: 180px; border-radius: 50%; object-fit: cover;">
                                    </div>
                                <?php endif; ?>
                                
                                <label>Profile Picture</label>
                                <div class="text-center mb-3">
                                    <input type="file" id="profile_picture" name="profile_picture" class="form-control-file mx-auto" style="max-width: 300px;" accept="image/*" onchange="loadImageForCropping(event)">
                                </div>
                                
                                <!-- Image Cropper Container (Hidden by default) -->
                                <div id="image-cropper-container" class="mt-3" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="img-container mb-3" style="max-height: 500px;">
                                                <img id="image-to-crop" src="" alt="Image to crop" style="max-width: 100%;">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-header bg-light">
                                                    <h6 class="mb-0">Image Controls</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group row mb-2">
                                                        <label class="col-sm-3 col-form-label">X</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="dataX" placeholder="x" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row mb-2">
                                                        <label class="col-sm-3 col-form-label">Y</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="dataY" placeholder="y" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row mb-2">
                                                        <label class="col-sm-3 col-form-label">Width</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="dataWidth" placeholder="width" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row mb-2">
                                                        <label class="col-sm-3 col-form-label">Height</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="dataHeight" placeholder="height" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row mb-2">
                                                        <label class="col-sm-3 col-form-label">Rotate</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="dataRotate" placeholder="rotate" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row mb-2">
                                                        <label class="col-sm-3 col-form-label">Scale</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="dataScaleX" placeholder="scaleX" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row mb-3">
                                                        <label class="col-sm-3 col-form-label">Aspect</label>
                                                        <div class="col-sm-9">
                                                            <div class="btn-group btn-group-sm" role="group">
                                                                <button type="button" class="btn btn-outline-secondary" id="aspectRatio1to1">1:1</button>
                                                                <button type="button" class="btn btn-outline-secondary" id="aspectRatio4to3">4:3</button>
                                                                <button type="button" class="btn btn-outline-secondary" id="aspectRatioFree">Free</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <button type="button" class="btn btn-primary btn-sm" id="crop-image">Crop Image</button>
                                                        <button type="button" class="btn btn-secondary btn-sm" id="cancel-crop">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Preview of cropped image -->
                                            <div id="cropped-image-preview" class="mt-3 text-center" style="display: none;">
                                                <h6 class="text-muted">Preview:</h6>
                                                <img id="cropped-preview" src="" alt="Cropped preview" style="width: 180px; height: 180px; border-radius: 50%; object-fit: cover;">
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="cropped_image_data" name="cropped_image_data">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group mt-4 text-right">
                <a href="<?= site_url('admin/student') ?>" class="btn btn-outline-secondary mr-2">Cancel</a>
                <button type="submit" class="btn btn-primary" id="updateStudentBtn">Update Student</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('stylesheets') ?>
<!-- Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<style>
    /* Custom styles for image cropper */
    .img-container {
        overflow: hidden;
        position: relative;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
    }
    
    /* Style for aspect ratio buttons */
    .btn-group .btn.active {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }
    
    /* Style for data inputs */
    #dataX, #dataY, #dataWidth, #dataHeight, #dataRotate, #dataScaleX {
        background-color: #f8f9fa;
    }
    
    /* Make the cropper container more prominent */
    #image-cropper-container {
        background-color: #ffffff;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .cropper-container {
        max-height: 400px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
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
                <button type="button" class="btn px-4 py-2 font-weight-bold" id="alertModalButton" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Bootstrap Alert implementation -->
<script>
function simpleAlert(title, message, type, redirectUrl = null) {
    console.log('simpleAlert called with:', { title, message, type, redirectUrl });
    
    // Get the modal element
    const modal = $('#alertModal');
    
    // Set the title and message
    modal.find('#alertModalLabel').text(title);
    
    // Check if message contains HTML
    if (message.includes('<') && message.includes('>')) {
        // For HTML content, use html() and add custom styling for better readability
        modal.find('.modal-body p').html(message).css({
            'text-align': 'left',
            'max-height': '300px',
            'overflow-y': 'auto'
        });
    } else {
        modal.find('.modal-body p').text(message).css({
            'text-align': 'center',
            'max-height': 'none',
            'overflow-y': 'visible'
        });
    }
    
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
    
    // Ensure the button has the data-dismiss attribute
    button.attr('data-dismiss', 'modal');
    
    // Unbind previous click events to prevent multiple bindings
    button.off('click');
    modal.off('hidden.bs.modal');
    
    // Handle modal hidden event
    modal.on('hidden.bs.modal', function() {
        console.log('Alert modal hidden');
        
        // Only redirect if it's a success message and redirectUrl is provided
        if (type === 'success' && redirectUrl) {
            console.log('Redirecting to:', redirectUrl);
            window.location.href = redirectUrl;
        }
        
        // Clean up event handlers
        button.off('click');
        modal.off('hidden.bs.modal');
    });
    
    // Show the modal
    modal.modal('show');
}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
// Global variables for image cropper
let cropper;
let imageElement;

// Function to update data inputs
function updateCropBoxData(e) {
    const data = e.detail;
    $('#dataX').val(Math.round(data.x));
    $('#dataY').val(Math.round(data.y));
    $('#dataWidth').val(Math.round(data.width));
    $('#dataHeight').val(Math.round(data.height));
    $('#dataRotate').val(typeof data.rotate !== 'undefined' ? data.rotate : '');
    $('#dataScaleX').val(typeof data.scaleX !== 'undefined' ? data.scaleX : '');
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
                ready: function() {
                    // Update data inputs when cropper is ready
                    const cropBoxData = cropper.getCropBoxData();
                    const canvasData = cropper.getCanvasData();
                    $('#dataX').val(Math.round(cropBoxData.left));
                    $('#dataY').val(Math.round(cropBoxData.top));
                    $('#dataWidth').val(Math.round(cropBoxData.width));
                    $('#dataHeight').val(Math.round(cropBoxData.height));
                    $('#dataRotate').val(0);
                    $('#dataScaleX').val(1);
                },
                crop: updateCropBoxData, // Update data inputs when crop box changes
                toggleDragModeOnDblclick: true // Toggle drag mode between "crop" and "move" when double click on the cropper
            });
        };
    };
    
    // Read the image file as a data URL
    reader.readAsDataURL(file);
}

$(document).ready(function() {
    // Initialize select2 for dropdown fields
    $('.custom-select2').select2();
    
    // Aspect ratio button handlers
    $('#aspectRatio1to1').on('click', function() {
        if (!cropper) return;
        cropper.setAspectRatio(1);
        $(this).addClass('active').siblings().removeClass('active');
    }).addClass('active'); // Default active
    
    $('#aspectRatio4to3').on('click', function() {
        if (!cropper) return;
        cropper.setAspectRatio(4/3);
        $(this).addClass('active').siblings().removeClass('active');
    });
    
    $('#aspectRatioFree').on('click', function() {
        if (!cropper) return;
        cropper.setAspectRatio(NaN); // Free aspect ratio
        $(this).addClass('active').siblings().removeClass('active');
    });
    
    // Crop button click event
    $('#crop-image').on('click', function() {
        if (!cropper) return;
        
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
        
        // Convert canvas to data URL
        const croppedImageData = canvas.toDataURL('image/jpeg', 0.8);
        
        // Set the value of the hidden input
        $('#cropped_image_data').val(croppedImageData);
        
        // Show the preview
        $('#cropped-image-preview').show();
        $('#cropped-preview').attr('src', croppedImageData);
        
        // Hide the cropper
        $('#image-cropper-container').hide();
    });
    
    // Cancel button click event
    $('#cancel-crop').on('click', function() {
        // Hide the cropper
        $('#image-cropper-container').hide();
        
        // Clear the file input
        $('#profile_picture').val('');
        
        // Destroy the cropper
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        
        // Clear data inputs
        $('#dataX, #dataY, #dataWidth, #dataHeight, #dataRotate, #dataScaleX').val('');
        
        // Hide the preview if it was shown
        $('#cropped-image-preview').hide();
    });
    
    // Form submission is handled by the AJAX code below
    
    // Form submission with SweetAlert
    $('#updateStudentForm').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        
        // Basic client-side validation
        var isValid = true;
        
        // Validate LRN is numeric and exactly 12 digits
        var lrnField = form.find('input[name="lrn"]');
        var lrnValue = lrnField.val().trim();
        if (lrnValue && !/^\d{12}$/.test(lrnValue)) {
            lrnField.addClass('is-invalid');
            isValid = false;
        } else {
            lrnField.removeClass('is-invalid');
        }
        
        // Check required fields
        form.find('[required]').each(function() {
            if (!$(this).val().trim()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            // Create a formatted message for client-side validation errors
            let formattedMessage = '<div class="alert alert-danger p-3 mb-0">' +
                                   '<h6 class="font-weight-bold">Please correct the following errors:</h6>' +
                                   '<ul class="mb-0">';
            
            // Check for LRN validation error
            if (lrnField.hasClass('is-invalid')) {
                formattedMessage += '<li><strong>LRN</strong>: Must contain exactly 12 numeric digits</li>';
            }
            
            // Check for empty required fields
            let emptyFields = [];
            form.find('[required].is-invalid').each(function() {
                const fieldName = $(this).attr('name') || $(this).attr('id') || 'Field';
                emptyFields.push('<li><strong>' + fieldName.charAt(0).toUpperCase() + fieldName.slice(1) + '</strong>: This field is required</li>');
            });
            
            formattedMessage += emptyFields.join('') + '</ul></div>';
            
            // Show validation error without redirect
            simpleAlert('Validation Error', formattedMessage, 'error');
            
            // Scroll to the first invalid field
            const firstInvalidField = $('.is-invalid').first();
            if (firstInvalidField.length) {
                $('html, body').animate({
                    scrollTop: firstInvalidField.offset().top - 100
                }, 500);
            }
            
            return;
        }
        
        // Get CSRF token
        var csrfName = '<?= csrf_token() ?>';
        var csrfHash = '<?= csrf_hash() ?>';
        
        // Create FormData and append CSRF token
        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);
        
        // Submit the form
        console.log('Form submission started');
        var formAction = form.attr('action');
        console.log('Form action:', formAction);
        
        // Debug: Check if SweetAlert is available
        console.log('SweetAlert available:', typeof Swal !== 'undefined');
        
        $.ajax({
            url: formAction,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json', // Explicitly request JSON response
            success: function(response) {
                console.log('Response received:', response); // Debug log
                console.log('Response type:', typeof response);
                
                // If response is a string, try to parse it
                if (typeof response === 'string') {
                    try {
                        response = JSON.parse(response);
                        console.log('Parsed string response:', response);
                    } catch (e) {
                        console.error('Failed to parse response string:', e);
                    }
                }
                
                // Check if response indicates success
                if (response && response.success) {
                    console.log('Success response received, showing alert');
                    // Show success message with redirect URL
                    const redirectUrl = response.redirect || '<?= site_url("admin/student") ?>';
                    console.log('Will redirect to:', redirectUrl);
                    simpleAlert('Done Updating', response.message || 'Student information has been updated successfully!', 'success', redirectUrl);
                } else {
                    // Show error message if response indicates failure
                    let errorMessage = response.message || 'There was an error updating the student information.';
                    
                    // Check if there are validation errors in the response
                    if (response.errors) {
                        // Format validation errors with HTML
                        let formattedMessage = '<div class="alert alert-danger p-3 mb-0">' +
                                              '<h6 class="font-weight-bold">Please correct the following errors:</h6>' +
                                              '<ul class="mb-0">';
                        
                        // Clear any previous validation errors
                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').remove();
                        
                        // Highlight invalid fields
                        for (const field in response.errors) {
                            formattedMessage += '<li><strong>' + field + '</strong>: ' + response.errors[field] + '</li>';
                            
                            // Add error class to invalid fields
                            const fieldElement = $('#' + field);
                            if (fieldElement.length) {
                                fieldElement.addClass('is-invalid');
                                // Add error feedback if not exists
                                if (fieldElement.next('.invalid-feedback').length === 0) {
                                    fieldElement.after('<div class="invalid-feedback">' + response.errors[field] + '</div>');
                                } else {
                                    fieldElement.next('.invalid-feedback').text(response.errors[field]);
                                }
                            }
                        }
                        
                        formattedMessage += '</ul></div>';
                        errorMessage = formattedMessage;
                        
                        // Scroll to the first invalid field
                        const firstInvalidField = $('.is-invalid').first();
                        if (firstInvalidField.length) {
                            $('html, body').animate({
                                scrollTop: firstInvalidField.offset().top - 100
                            }, 500);
                        }
                    }
                    
                    // Show error message without redirect
                    simpleAlert('Validation Error', errorMessage, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.log('Error status:', status);
                console.log('Error details:', error);
                console.log('XHR response:', xhr.responseText);
                
                // Parse response if possible
                let errorMessage = 'There was an error updating the student information.';
                
                try {
                    if (xhr.responseText) {
                        const response = JSON.parse(xhr.responseText);
                        
                        if (response.errors) {
                            // Format validation errors with HTML
                            let formattedMessage = '<div class="alert alert-danger p-3 mb-0">' +
                                               '<h6 class="font-weight-bold">Please correct the following errors:</h6>' +
                                               '<ul class="mb-0">';
                            
                            // Clear any previous validation errors
                            $('.is-invalid').removeClass('is-invalid');
                            $('.invalid-feedback').remove();
                            
                            // Highlight invalid fields
                            for (const field in response.errors) {
                                formattedMessage += '<li><strong>' + field + '</strong>: ' + response.errors[field] + '</li>';
                                
                                // Add error class to invalid fields
                                const fieldElement = $('#' + field);
                                if (fieldElement.length) {
                                    fieldElement.addClass('is-invalid');
                                    // Add error feedback if not exists
                                    if (fieldElement.next('.invalid-feedback').length === 0) {
                                        fieldElement.after('<div class="invalid-feedback">' + response.errors[field] + '</div>');
                                    } else {
                                        fieldElement.next('.invalid-feedback').text(response.errors[field]);
                                    }
                                }
                            }
                            
                            formattedMessage += '</ul></div>';
                            errorMessage = formattedMessage;
                            
                            // Scroll to the first invalid field
                            const firstInvalidField = $('.is-invalid').first();
                            if (firstInvalidField.length) {
                                $('html, body').animate({
                                    scrollTop: firstInvalidField.offset().top - 100
                                }, 500);
                            }
                        } else if (response.message) {
                            errorMessage = response.message;
                        }
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                }
                
                // Show error message without redirect URL
                simpleAlert('Error', errorMessage, 'error');
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
