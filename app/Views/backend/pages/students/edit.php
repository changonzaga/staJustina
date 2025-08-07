
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
                        <a href="<?= site_url('backend/pages/students') ?>">Students</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Edit Student
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4">Edit Student Information</h4>
    </div>
    <div class="pb-20">
        <form id="studentForm" action="<?= site_url('backend/pages/students/update/' . $student['id']) ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="PUT">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>LRN (Learner Reference Number)</label>
                        <input type="text" name="lrn" class="form-control" value="<?= esc($student['lrn']) ?>" required pattern="^\d{12}$" title="LRN must be exactly 12 digits and contain numbers only">
                        <div class="invalid-feedback">LRN must be exactly 12 digits and contain numbers only</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" value="<?= esc($student['name']) ?>" required pattern="^[^0-9]+$" title="Name must not contain any numbers">
                        <div class="invalid-feedback">Student name must not contain any numbers</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender" class="form-control" required>
                            <option value="Male" <?= $student['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= $student['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                            <option value="Other" <?= $student['gender'] === 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Age</label>
                        <input type="number" name="age" class="form-control" value="<?= esc($student['age']) ?>" min="1" max="100" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Grade Level</label>
                        <input type="text" name="grade_level" class="form-control" value="<?= esc($student['grade_level']) ?>" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Section</label>
                        <input type="text" name="section" class="form-control" value="<?= esc($student['section']) ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-control"><?= esc($student['address']) ?></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Guardian Name</label>
                        <input type="text" name="guardian" class="form-control" value="<?= esc($student['guardian']) ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="text" name="contact" class="form-control" value="<?= esc($student['contact']) ?>">
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
                                    <option value="<?= esc($teacher['id']) ?>" <?= $student['teacher_id'] == $teacher['id'] ? 'selected' : '' ?>>
                                        <?= esc($teacher['name']) ?>
                                    </option>
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
                                    <option value="<?= esc($parent['id']) ?>" <?= $student['parent_id'] == $parent['id'] ? 'selected' : '' ?>>
                                        <?= esc($parent['name']) ?>
                                    </option>
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
                        <?php if (!empty($student['profile_picture'])): ?>
                            <img src="<?= base_url('Uploads/students/' . $student['profile_picture']) ?>" 
                                 alt="Profile" class="mt-2" style="width: 100px; height: 100px; border-radius: 50%;">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update Student</button>
                <a href="<?= site_url('backend/pages/students') ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<!-- Alert Container -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 5">
    <div id="liveAlert" class="alert alert-success alert-dismissible fade hide" role="alert">
        <span class="message">Success!</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>

<script>
    // Function to show animated alerts
    function showAlert(message, type = 'success') {
        const alertPlaceholder = document.getElementById('liveAlert');
        alertPlaceholder.querySelector('.message').textContent = message;
        
        // Remove existing alert classes and add the new one
        alertPlaceholder.classList.remove('alert-success', 'alert-danger', 'alert-warning', 'alert-info');
        alertPlaceholder.classList.add(`alert-${type}`);
        
        // Show the alert
        alertPlaceholder.classList.remove('hide');
        alertPlaceholder.classList.add('show');
        
        // Auto-hide success messages after 3 seconds
        if (type === 'success') {
            setTimeout(() => {
                alertPlaceholder.classList.remove('show');
                alertPlaceholder.classList.add('hide');
            }, 3000);
        }
    }

    // AJAX form submission
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('studentForm');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic client-side validation
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
                
                // Validate LRN is exactly 12 digits
                if (field.name === 'lrn' && field.value.trim() && !/^\d{12}$/.test(field.value.trim())) {
                    field.classList.add('is-invalid');
                    const feedbackDiv = field.nextElementSibling;
                    if (feedbackDiv && feedbackDiv.classList.contains('invalid-feedback')) {
                        feedbackDiv.textContent = 'LRN must be exactly 12 digits and contain numbers only';
                    }
                    isValid = false;
                }
                
                // Validate name fields don't contain numbers
                if (field.name === 'name' && field.value.trim() && /\d/.test(field.value.trim())) {
                    field.classList.add('is-invalid');
                    const feedbackDiv = field.nextElementSibling;
                    if (feedbackDiv && feedbackDiv.classList.contains('invalid-feedback')) {
                        feedbackDiv.textContent = 'Student name must not contain any numbers';
                    }
                    isValid = false;
                }
            });
            
            if (!isValid) {
                showAlert('Please fill in all required fields correctly.', 'danger');
                return;
            }
            
            // Create FormData object
            const formData = new FormData(form);
            
            // Add CSRF token
            const csrfToken = document.querySelector('input[name="<?= csrf_token() ?>"]').value;
            formData.append('<?= csrf_token() ?>', csrfToken);
            
            // Send AJAX request
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showAlert(data.message, 'success');
                    
                    // Redirect after a delay
                    setTimeout(() => {
                        window.location.href = data.redirect || '<?= site_url('backend/pages/students') ?>';
                    }, 1500);
                } else {
                    // Show error message
                    let errorMessage = data.message || 'An error occurred while updating the student.';
                    
                    // Format validation errors if available
                    if (data.errors) {
                        const errorList = Object.entries(data.errors)
                            .map(([field, message]) => {
                                // Highlight invalid fields
                                const inputField = form.querySelector(`[name="${field}"]`);
                                if (inputField) {
                                    inputField.classList.add('is-invalid');
                                    
                                    // If there's a corresponding invalid-feedback div, update its text
                                    const feedbackDiv = inputField.nextElementSibling;
                                    if (feedbackDiv && feedbackDiv.classList.contains('invalid-feedback')) {
                                        feedbackDiv.textContent = message;
                                    }
                                }
                                
                                return `<li>${message}</li>`;
                            }).join('');
                        
                        errorMessage += `<ul class="mt-2 mb-0">${errorList}</ul>`;
                    }
                    
                    showAlert(errorMessage, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An unexpected error occurred. Please try again.', 'danger');
            });
        });
        
        // Clear validation styling when input changes
        form.querySelectorAll('input, select, textarea').forEach(element => {
            element.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
        });
    });
</script>

<?= $this->endSection() ?>
