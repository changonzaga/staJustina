<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Complete Your Profile</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= route_to('teacher.dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Complete Profile</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<?php if (session()->has('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= session('success') ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>

<?php if (session()->has('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= session('error') ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>

<?php if (session()->has('info')): ?>
<div class="alert alert-info alert-dismissible fade show" role="alert">
    <?= session('info') ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>

<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4">Complete Your Teacher Profile</h4>
        <p class="text-muted">Please fill in the required information to complete your profile and access all features.</p>
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

        <form action="<?= site_url('teacher/profile/complete') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <!-- Personal Information Section -->
            <div class="row mb-5">
                <div class="col-12"><h5 class="text-primary border-bottom pb-2 mb-4">Personal Information</h5></div>
                
                <div class="col-md-4 mb-3">
                    <label for="first_name" class="font-weight-bold">First Name <span class="text-danger">*</span></label>
                    <input type="text" id="first_name" name="first_name" value="<?= old('first_name', $teacher['first_name'] ?? '') ?>" class="form-control mt-2" required>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="middle_name" class="font-weight-bold">Middle Name</label>
                    <input type="text" id="middle_name" name="middle_name" value="<?= old('middle_name', $teacher['middle_name'] ?? '') ?>" class="form-control mt-2">
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="last_name" class="font-weight-bold">Last Name <span class="text-danger">*</span></label>
                    <input type="text" id="last_name" name="last_name" value="<?= old('last_name', $teacher['last_name'] ?? '') ?>" class="form-control mt-2" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="date_of_birth" class="font-weight-bold">Date of Birth <span class="text-danger">*</span></label>
                    <input type="date" id="date_of_birth" name="date_of_birth" value="<?= old('date_of_birth', $teacher['date_of_birth'] ?? '') ?>" class="form-control mt-2" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="gender" class="font-weight-bold">Gender <span class="text-danger">*</span></label>
                    <select id="gender" name="gender" class="form-control mt-2" required>
                        <option value="">Select Gender</option>
                        <option value="Male" <?= old('gender', $teacher['gender'] ?? '') == 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= old('gender', $teacher['gender'] ?? '') == 'Female' ? 'selected' : '' ?>>Female</option>
                        <option value="Other" <?= old('gender', $teacher['gender'] ?? '') == 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="contact_number" class="font-weight-bold">Contact Number <span class="text-danger">*</span></label>
                    <input type="tel" id="contact_number" name="contact_number" value="<?= old('contact_number', $teacher['contact_number'] ?? '') ?>" class="form-control mt-2" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="civil_status" class="font-weight-bold">Civil Status</label>
                    <select id="civil_status" name="civil_status" class="form-control mt-2">
                        <option value="">Select Civil Status</option>
                        <option value="Single" <?= old('civil_status', $teacher['civil_status'] ?? '') == 'Single' ? 'selected' : '' ?>>Single</option>
                        <option value="Married" <?= old('civil_status', $teacher['civil_status'] ?? '') == 'Married' ? 'selected' : '' ?>>Married</option>
                        <option value="Divorced" <?= old('civil_status', $teacher['civil_status'] ?? '') == 'Divorced' ? 'selected' : '' ?>>Divorced</option>
                        <option value="Widowed" <?= old('civil_status', $teacher['civil_status'] ?? '') == 'Widowed' ? 'selected' : '' ?>>Widowed</option>
                    </select>
                </div>
            </div>
            
            <!-- Professional Information Section -->
            <div class="row mb-5">
                <div class="col-12"><h5 class="text-primary border-bottom pb-2 mb-4">Professional Information</h5></div>
                
                <div class="col-md-6 mb-3">
                    <label for="employee_id" class="font-weight-bold">Employee ID (DepEd ID or Gov ID) <span class="text-danger">*</span></label>
                    <input type="text" id="employee_id" name="employee_id" value="<?= old('employee_id', $teacher['employee_id'] ?? '') ?>" class="form-control mt-2" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="position" class="font-weight-bold">Position <span class="text-danger">*</span></label>
                    <input type="text" id="position" name="position" value="<?= old('position', $teacher['position'] ?? '') ?>" class="form-control mt-2" placeholder="e.g., Teacher I, Teacher II, Master Teacher" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="specialization" class="font-weight-bold">Specialization/Subject <span class="text-danger">*</span></label>
                    <input type="text" id="specialization" name="specialization" value="<?= old('specialization', $teacher['specialization'] ?? '') ?>" class="form-control mt-2" placeholder="e.g., Mathematics, English, Science" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="employment_status" class="font-weight-bold">Employment Status</label>
                    <select id="employment_status" name="employment_status" class="form-control mt-2">
                        <option value="">Select Employment Status</option>
                        <option value="Regular" <?= old('employment_status', $teacher['employment_status'] ?? '') == 'Regular' ? 'selected' : '' ?>>Regular</option>
                        <option value="Contractual" <?= old('employment_status', $teacher['employment_status'] ?? '') == 'Contractual' ? 'selected' : '' ?>>Contractual</option>
                        <option value="Substitute" <?= old('employment_status', $teacher['employment_status'] ?? '') == 'Substitute' ? 'selected' : '' ?>>Substitute</option>
                        <option value="Part-time" <?= old('employment_status', $teacher['employment_status'] ?? '') == 'Part-time' ? 'selected' : '' ?>>Part-time</option>
                    </select>
                </div>
                
                <div class="col-md-12 mb-3">
                    <label for="educational_attainment" class="font-weight-bold">Educational Attainment</label>
                    <textarea id="educational_attainment" name="educational_attainment" class="form-control mt-2" rows="3" placeholder="e.g., Bachelor of Elementary Education, Master in Teaching"><?= old('educational_attainment', $teacher['educational_attainment'] ?? '') ?></textarea>
                </div>
                
                <div class="col-md-12 mb-3">
                    <label for="teaching_assignment" class="font-weight-bold">Teaching Assignment</label>
                    <textarea id="teaching_assignment" name="teaching_assignment" class="form-control mt-2" rows="3" placeholder="e.g., Grade 7 Mathematics, Grade 8 Science"><?= old('teaching_assignment', $teacher['teaching_assignment'] ?? '') ?></textarea>
                </div>
            </div>
            
            <!-- Additional Information Section -->
            <div class="row mb-5">
                <div class="col-12"><h5 class="text-primary border-bottom pb-2 mb-4">Additional Information</h5></div>
                
                <div class="col-md-6 mb-3">
                    <label for="school_assigned" class="font-weight-bold">School Assigned</label>
                    <input type="text" id="school_assigned" name="school_assigned" value="<?= old('school_assigned', $teacher['school_assigned'] ?? '') ?>" class="form-control mt-2">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="prc_license_number" class="font-weight-bold">PRC License Number</label>
                    <input type="text" id="prc_license_number" name="prc_license_number" value="<?= old('prc_license_number', $teacher['prc_license_number'] ?? '') ?>" class="form-control mt-2">
                </div>
                
                <div class="col-md-12 mb-3">
                    <label for="residential_address" class="font-weight-bold">Residential Address</label>
                    <textarea id="residential_address" name="residential_address" class="form-control mt-2" rows="3"><?= old('residential_address', $teacher['residential_address'] ?? '') ?></textarea>
                </div>
                
                <div class="col-md-12 mb-3">
                    <label for="emergency_contact" class="font-weight-bold">Emergency Contact</label>
                    <textarea id="emergency_contact" name="emergency_contact" class="form-control mt-2" rows="3" placeholder="Name, Relationship, Contact Number"><?= old('emergency_contact', $teacher['emergency_contact'] ?? '') ?></textarea>
                </div>
            </div>
            
            <!-- Profile Picture Section -->
            <div class="row mb-5">
                <div class="col-12"><h5 class="text-primary border-bottom pb-2 mb-4">Profile Picture</h5></div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="profile_picture" class="font-weight-bold">Update Profile Picture</label>
                        <input type="file" id="profile_picture" name="profile_picture" class="form-control-file mt-2" accept="image/*">
                        <small class="form-text text-muted">Leave empty to keep current picture. Max file size: 2MB.</small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <?php if (!empty($teacher['profile_picture'])): ?>
                        <div class="current-picture">
                            <label class="font-weight-bold">Current Picture:</label>
                            <div class="mt-2">
                                <img src="<?= base_url('uploads/teachers/' . $teacher['profile_picture']) ?>" alt="Current Profile" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <a href="<?= site_url('teacher/dashboard') ?>" class="btn btn-secondary">
                            <i class="icon-copy bi bi-arrow-left"></i> Skip for Now
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="icon-copy bi bi-check-circle"></i> Complete Profile
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const requiredFields = form.querySelectorAll('[required]');
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
    
    // Remove invalid class on input
    requiredFields.forEach(field => {
        field.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
            }
        });
    });
});
</script>

<style>
.is-invalid {
    border-color: #dc3545 !important;
}

.text-primary {
    color: #007bff !important;
}

.border-bottom {
    border-bottom: 2px solid #007bff !important;
}

.card-box {
    background: #fff;
    padding: 0;
    border-radius: 4px;
    margin-bottom: 30px;
    box-shadow: 0 0 13px 0 rgba(82,63,105,.05);
}

.alert {
    border: none;
    border-radius: 4px;
}

.btn {
    border-radius: 4px;
    padding: 10px 20px;
}

.form-control {
    border-radius: 4px;
    border: 1px solid #e0e6ed;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}
</style>

<?= $this->endSection() ?>