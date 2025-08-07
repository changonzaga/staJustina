<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Add New Student</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= site_url('backend/pages/students') ?>">Students</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Add Student
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4">Student Information</h4>
    </div>
    <div class="pb-20">
        <form id="studentForm" action="<?= site_url('backend/pages/students/store') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>LRN (Learner Reference Number)</label>
                        <input type="text" name="lrn" class="form-control" placeholder="Enter LRN" required pattern="[0-9]+" title="LRN must contain only numbers">
                        <div class="invalid-feedback">LRN must contain only numeric values</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender" class="form-control" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Age</label>
                        <input type="number" name="age" class="form-control" placeholder="Enter age" min="1" max="100" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Grade Level</label>
                        <input type="text" name="grade_level" class="form-control" placeholder="Enter grade level (e.g., 6)" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Section</label>
                        <input type="text" name="section" class="form-control" placeholder="Enter section (e.g., Thor)" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-control" placeholder="Enter address"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Guardian Name</label>
                        <input type="text" name="guardian" class="form-control" placeholder="Enter guardian name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="text" name="contact" class="form-control" placeholder="Enter contact number">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Assign Teacher</label>
                        <select name="teacher_id" class="form-control">
                            <option value="">Select Teacher (Optional)</option>
                            <?php if (isset($teachers) && !empty($teachers)): ?>
                                <?php foreach ($teachers as $teacher): ?>
                                    <option value="<?= esc($teacher['id']) ?>"><?= esc($teacher['name']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Assign Parent</label>
                        <select name="parent_id" class="form-control">
                            <option value="">Select Parent (Optional)</option>
                            <?php if (isset($parents) && !empty($parents)): ?>
                                <?php foreach ($parents as $parent): ?>
                                    <option value="<?= esc($parent['id']) ?>"><?= esc($parent['name']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Profile Picture</label>
                        <input type="file" name="profile_picture" class="form-control-file" accept="image/*">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Save Student</button>
                <a href="<?= site_url('backend/pages/students') ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<!-- Alert Container -->
<div class="alert-container position-fixed top-0 end-0 p-3" style="z-index: 1050; right: 0; top: 10px;">
    <div id="liveAlert" class="alert alert-success alert-dismissible fade" role="alert">
        <span class="alert-message">Success!</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>

<script>
$(document).ready(function() {
    // Function to show animated alerts
    function showAlert(message, type) {
        // Set the alert type and message
        const alertElement = $('#liveAlert');
        alertElement.removeClass('alert-success alert-danger alert-warning alert-info')
                    .addClass('alert-' + type)
                    .find('.alert-message')
                    .html(message);
        
        // Show the alert with animation
        alertElement.addClass('show');
        
        // Auto-hide after 5 seconds for success alerts
        if (type === 'success') {
            setTimeout(function() {
                alertElement.removeClass('show');
            }, 5000);
        }
    }
    
    // Handle form submission
    $('#studentForm').on('submit', function(e) {
        e.preventDefault();
        
        // Basic validation
        let isValid = true;
        const requiredFields = ['name', 'lrn', 'gender', 'age', 'grade_level', 'section'];
        
        requiredFields.forEach(function(field) {
            const input = $('[name="' + field + '"]');
            if (!input.val().trim()) {
                isValid = false;
                input.addClass('is-invalid');
            } else {
                input.removeClass('is-invalid');
            }
        });
        
        // LRN numeric validation
        const lrnInput = $('[name="lrn"]');
        const lrnValue = lrnInput.val().trim();
        if (lrnValue && !/^\d+$/.test(lrnValue)) {
            isValid = false;
            lrnInput.addClass('is-invalid');
            lrnInput.siblings('.invalid-feedback').text('LRN must contain only numeric values');
            showAlert('<strong>Error!</strong> LRN must contain only numeric values.', 'danger');
            return false;
        }
        
        if (!isValid) {
            showAlert('Please fill in all required fields.', 'danger');
            return false;
        }
        
        // Prepare form data
        const formData = new FormData(this);
        
        // Add CSRF token
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        
        // Submit form via AJAX
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Show success message
                    showAlert('<strong>Success!</strong> ' + response.message, 'success');
                    
                    // Redirect after a short delay
                    setTimeout(function() {
                        window.location.href = response.redirect || '<?= site_url("backend/pages/students") ?>';
                    }, 2000);
                } else {
                    // Show error message
                    let errorMessage = response.message || 'Failed to add the student. Please try again.';
                    
                    // If we have validation errors, format them
                    if (response.errors) {
                        errorMessage += '<ul class="mt-2 mb-0">';
                        for (const field in response.errors) {
                            errorMessage += '<li>' + response.errors[field] + '</li>';
                            // Highlight the invalid field
                            $('[name="' + field + '"]').addClass('is-invalid');
                        }
                        errorMessage += '</ul>';
                    }
                    
                    showAlert('<strong>Error!</strong> ' + errorMessage, 'danger');
                }
            },
            error: function(xhr, status, error) {
                // Handle AJAX errors
                let errorMessage = 'An error occurred while processing your request.';
                
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.message) {
                        errorMessage = response.message;
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                }
                
                showAlert('<strong>Error!</strong> ' + errorMessage, 'danger');
            }
        });
    });
    
    // Clear validation styling when input changes
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
});
</script>

<?= $this->endSection() ?>
