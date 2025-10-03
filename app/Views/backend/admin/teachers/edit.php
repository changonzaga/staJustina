
<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Edit Teacher</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= route_to('admin.home') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('/admin/teacher') ?>">Teachers</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Teacher</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-30">
        <div class="card-box">
            <div class="card-header bg-white">
                <h5 class="card-title text-primary">Edit Teacher Information</h5>
                <p class="card-text text-secondary">Update teacher's details below. Account number cannot be changed.</p>
            </div>  
            <div class="card-body">
                <form action="<?= site_url('/admin/teacher/update/' . $teacher['id']) ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <!-- Account Information -->
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-primary mb-3"><i class="icon-copy dw dw-id-card"></i> Account Information</h6>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="account_no">Account Number</label>
                                <input type="text" class="form-control" id="account_no" name="account_no" 
                                       value="<?= esc($teacher['account_no']) ?>" readonly>
                                <small class="form-text text-muted">Account number cannot be changed</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="employee_id">Employee ID <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('employee_id') ? 'is-invalid' : '' ?>" 
                                       id="employee_id" name="employee_id" value="<?= old('employee_id', $teacher['employee_id']) ?>" required>
                                <?php if (isset($validation) && $validation->hasError('employee_id')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('employee_id') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" 
                                       id="email" name="email" value="<?= old('email', $teacher['email']) ?>" required>
                                <small class="form-text text-muted">Used for login and notifications</small>
                                <?php if (isset($validation) && $validation->hasError('email')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Account Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="Active" <?= old('status', $teacher['status']) == 'Active' ? 'selected' : '' ?>>Active</option>
                                    <option value="Inactive" <?= old('status', $teacher['status']) == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                                    <option value="Suspended" <?= old('status', $teacher['status']) == 'Suspended' ? 'selected' : '' ?>>Suspended</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Personal Information -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="text-primary mb-3"><i class="icon-copy dw dw-user1"></i> Personal Information</h6>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="first_name">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('first_name') ? 'is-invalid' : '' ?>" 
                                       id="first_name" name="first_name" value="<?= old('first_name', $teacher['first_name']) ?>" required>
                                <?php if (isset($validation) && $validation->hasError('first_name')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('first_name') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="middle_name">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name" 
                                       value="<?= old('middle_name', $teacher['middle_name']) ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('last_name') ? 'is-invalid' : '' ?>" 
                                       id="last_name" name="last_name" value="<?= old('last_name', $teacher['last_name']) ?>" required>
                                <?php if (isset($validation) && $validation->hasError('last_name')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('last_name') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_of_birth">Date of Birth</label>
                                <input type="date" class="form-control <?= isset($validation) && $validation->hasError('date_of_birth') ? 'is-invalid' : '' ?>" 
                                       id="date_of_birth" name="date_of_birth" value="<?= old('date_of_birth', $teacher['date_of_birth']) ?>">
                                <?php if (isset($validation) && $validation->hasError('date_of_birth')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('date_of_birth') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="age">Age</label>
                                <input type="number" class="form-control <?= isset($validation) && $validation->hasError('age') ? 'is-invalid' : '' ?>" 
                                       id="age" name="age" value="<?= old('age', $teacher['age']) ?>" placeholder="Age (10-100)">
                                <small class="form-text text-muted">Leave empty if unknown (minimum 10 years)</small>
                                <?php if (isset($validation) && $validation->hasError('age')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('age') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="gender">Gender <span class="text-danger">*</span></label>
                                <select class="form-control <?= isset($validation) && $validation->hasError('gender') ? 'is-invalid' : '' ?>" 
                                        id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male" <?= old('gender', $teacher['gender']) == 'Male' ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= old('gender', $teacher['gender']) == 'Female' ? 'selected' : '' ?>>Female</option>
                                    <option value="Other" <?= old('gender', $teacher['gender']) == 'Other' ? 'selected' : '' ?>>Other</option>
                                </select>
                                <?php if (isset($validation) && $validation->hasError('gender')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('gender') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="contact_number">Contact Number</label>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('contact_number') ? 'is-invalid' : '' ?>" 
                                       id="contact_number" name="contact_number" value="<?= old('contact_number', $teacher['contact_number']) ?>" 
                                       placeholder="09XXXXXXXXX">
                                <?php if (isset($validation) && $validation->hasError('contact_number')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('contact_number') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="civil_status_id">Civil Status</label>
                                <select class="form-control <?= isset($validation) && $validation->hasError('civil_status_id') ? 'is-invalid' : '' ?>" 
                                        id="civil_status_id" name="civil_status_id">
                                    <option value="">Select Civil Status</option>
                                    <?php if (isset($civil_statuses) && !empty($civil_statuses)): ?>
                                        <?php foreach ($civil_statuses as $status): ?>
                                            <option value="<?= $status['id'] ?>" <?= old('civil_status_id', $teacher['civil_status_id']) == $status['id'] ? 'selected' : '' ?>>
                                                <?= esc($status['status']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php if (isset($validation) && $validation->hasError('civil_status_id')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('civil_status_id') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nationality">Nationality</label>
                                <input type="text" class="form-control" id="nationality" name="nationality" 
                                       value="<?= old('nationality', $teacher['nationality']) ?>" placeholder="Filipino">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="profile_picture">Profile Picture</label>
                                <input type="file" class="form-control-file <?= isset($validation) && $validation->hasError('profile_picture') ? 'is-invalid' : '' ?>" 
                                       id="profile_picture" name="profile_picture" accept="image/*">
                                <small class="form-text text-muted">Upload a new profile picture (Max: 2MB, JPG/PNG)</small>
                                <?php if (isset($validation) && $validation->hasError('profile_picture')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('profile_picture') ?></div>
                                <?php endif; ?>
                                <?php if (!empty($teacher['profile_picture'])): ?>
                                    <div class="mt-2">
                                        <img src="<?= base_url('uploads/teachers/' . $teacher['profile_picture']) ?>" 
                                             alt="Current Profile" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                        <small class="d-block text-muted">Current profile picture</small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Professional Information -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="text-primary mb-3"><i class="icon-copy dw dw-briefcase"></i> Professional Information</h6>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="position">Position</label>
                                <input type="text" class="form-control" id="position" name="position" 
                                       value="<?= old('position', $teacher['position']) ?>" placeholder="e.g., Senior High School Teacher">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="employment_status_id">Employment Status</label>
                                <select class="form-control <?= isset($validation) && $validation->hasError('employment_status_id') ? 'is-invalid' : '' ?>" 
                                        id="employment_status_id" name="employment_status_id">
                                    <option value="">Select Employment Status</option>
                                    <?php if (isset($employment_statuses) && !empty($employment_statuses)): ?>
                                        <?php foreach ($employment_statuses as $status): ?>
                                            <option value="<?= $status['id'] ?>" <?= old('employment_status_id', $teacher['employment_status_id']) == $status['id'] ? 'selected' : '' ?>>
                                                <?= esc($status['status']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php if (isset($validation) && $validation->hasError('employment_status_id')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('employment_status_id') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="educational_attainment">Educational Attainment</label>
                                <input type="text" class="form-control" id="educational_attainment" name="educational_attainment" 
                                       value="<?= old('educational_attainment', $teacher['educational_attainment']) ?>" 
                                       placeholder="e.g., Bachelor of Science in Education">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="prc_license_number">PRC License Number</label>
                                <input type="text" class="form-control" id="prc_license_number" name="prc_license_number" 
                                       value="<?= old('prc_license_number', $teacher['prc_license_number']) ?>" 
                                       placeholder="Professional License Number">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="eligibility_status">Eligibility Status</label>
                                <input type="text" class="form-control" id="eligibility_status" name="eligibility_status" 
                                       value="<?= old('eligibility_status', $teacher['eligibility_status']) ?>" 
                                       placeholder="e.g., LET Passer, Civil Service Eligible">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Address Information Section -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="text-primary mb-3"><i class="icon-copy dw dw-home"></i> Address Information</h6>
                        </div>
                    </div>
                    
                    <!-- Residential Address -->
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-secondary mb-3">Residential Address</h6>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                 <label for="residential_street_address">Street Address</label>
                                 <textarea class="form-control" id="residential_street_address" name="residential_street_address" 
                                          rows="2" placeholder="House/Unit Number, Street Name"><?= old('residential_street_address', $teacher_addresses['residential']['street_address'] ?? '') ?></textarea>
                             </div>
                         </div>
                         <div class="col-md-3">
                             <div class="form-group">
                                 <label for="residential_barangay">Barangay</label>
                                 <input type="text" class="form-control" id="residential_barangay" name="residential_barangay" 
                                        value="<?= old('residential_barangay', $teacher_addresses['residential']['barangay'] ?? '') ?>" placeholder="Barangay">
                             </div>
                         </div>
                         <div class="col-md-3">
                             <div class="form-group">
                                 <label for="residential_city">City/Municipality</label>
                                 <input type="text" class="form-control" id="residential_city" name="residential_city" 
                                        value="<?= old('residential_city', $teacher_addresses['residential']['city'] ?? '') ?>" placeholder="City/Municipality">
                             </div>
                         </div>
                         <div class="col-md-3">
                             <div class="form-group">
                                 <label for="residential_province">Province</label>
                                 <input type="text" class="form-control" id="residential_province" name="residential_province" 
                                        value="<?= old('residential_province', $teacher_addresses['residential']['province'] ?? '') ?>" placeholder="Province">
                             </div>
                         </div>
                         <div class="col-md-3">
                             <div class="form-group">
                                 <label for="residential_postal_code">Postal Code</label>
                                 <input type="text" class="form-control" id="residential_postal_code" name="residential_postal_code" 
                                        value="<?= old('residential_postal_code', $teacher_addresses['residential']['postal_code'] ?? '') ?>" placeholder="Postal Code">
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
                        </div>
                    </div>
                    
                    <div class="row" id="permanent_address_fields" style="<?= old('same_as_residential') ? 'display: none;' : '' ?>">
                        <div class="col-md-12">
                             <div class="form-group">
                                 <label for="permanent_street_address">Street Address</label>
                                 <textarea class="form-control" id="permanent_street_address" name="permanent_street_address" 
                                          rows="2" placeholder="House/Unit Number, Street Name"><?= old('permanent_street_address', $teacher_addresses['permanent']['street_address'] ?? '') ?></textarea>
                             </div>
                         </div>
                         <div class="col-md-3">
                             <div class="form-group">
                                 <label for="permanent_barangay">Barangay</label>
                                 <input type="text" class="form-control" id="permanent_barangay" name="permanent_barangay" 
                                        value="<?= old('permanent_barangay', $teacher_addresses['permanent']['barangay'] ?? '') ?>" placeholder="Barangay">
                             </div>
                         </div>
                         <div class="col-md-3">
                             <div class="form-group">
                                 <label for="permanent_city">City/Municipality</label>
                                 <input type="text" class="form-control" id="permanent_city" name="permanent_city" 
                                        value="<?= old('permanent_city', $teacher_addresses['permanent']['city'] ?? '') ?>" placeholder="City/Municipality">
                             </div>
                         </div>
                         <div class="col-md-3">
                             <div class="form-group">
                                 <label for="permanent_province">Province</label>
                                 <input type="text" class="form-control" id="permanent_province" name="permanent_province" 
                                        value="<?= old('permanent_province', $teacher_addresses['permanent']['province'] ?? '') ?>" placeholder="Province">
                             </div>
                         </div>
                         <div class="col-md-3">
                             <div class="form-group">
                                 <label for="permanent_postal_code">Postal Code</label>
                                 <input type="text" class="form-control" id="permanent_postal_code" name="permanent_postal_code" 
                                        value="<?= old('permanent_postal_code', $teacher_addresses['permanent']['postal_code'] ?? '') ?>" placeholder="Postal Code">
                             </div>
                         </div>
                    </div>
                    
                    <!-- Specializations Section -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="text-primary mb-3"><i class="icon-copy dw dw-book"></i> Subject Specializations</h6>
                            <p class="text-muted">Add the subjects this teacher specializes in. You can add multiple specializations.</p>
                        </div>
                        <div class="col-md-12">
                             <div id="specializations-container">
                                 <?php if (!empty($teacher_specializations)): ?>
                                     <?php foreach ($teacher_specializations as $index => $specialization): ?>
                                         <div class="specialization-item border rounded p-3 mb-3" data-index="<?= $index ?>">
                                             <div class="row">
                                                 <div class="col-md-4">
                                                     <label class="font-weight-bold">Subject <?= $specialization['is_primary'] ? '<span class="text-secondary ml-1 small">Primary</span>' : '' ?></label>
                                                     <select class="form-control" name="specializations[<?= $index ?>][subject_id]">
                                                         <option value="">Select Subject</option>
                                                         <?php if (isset($subjects) && !empty($subjects)): ?>
                                                             <?php foreach ($subjects as $subject): ?>
                                                                 <option value="<?= $subject['id'] ?>" <?= $subject['id'] == $specialization['subject_id'] ? 'selected' : '' ?>>
                                                                     <?= esc($subject['subject_name']) ?>
                                                                 </option>
                                                             <?php endforeach; ?>
                                                         <?php endif; ?>
                                                     </select>
                                                 </div>
                                                 <div class="col-md-3">
                                                     <label class="font-weight-bold">Proficiency Level</label>
                                                     <select class="form-control" name="specializations[<?= $index ?>][proficiency_level]">
                                                         <option value="">Select Level</option>
                                                         <option value="Basic" <?= $specialization['proficiency_level'] == 'Basic' ? 'selected' : '' ?>>Basic</option>
                                                         <option value="Intermediate" <?= $specialization['proficiency_level'] == 'Intermediate' ? 'selected' : '' ?>>Intermediate</option>
                                                         <option value="Advanced" <?= $specialization['proficiency_level'] == 'Advanced' ? 'selected' : '' ?>>Advanced</option>
                                                         <option value="Expert" <?= $specialization['proficiency_level'] == 'Expert' ? 'selected' : '' ?>>Expert</option>
                                                     </select>
                                                 </div>
                                                 <div class="col-md-3">
                                                     <label class="font-weight-bold">Years Experience</label>
                                                     <input type="number" class="form-control" name="specializations[<?= $index ?>][years_experience]" 
                                                            min="0" max="50" value="<?= $specialization['years_experience'] ?? 0 ?>" placeholder="0">
                                                 </div>
                                                 <div class="col-md-1">
                                     <label class="font-weight-bold">Remove</label><br>
                                     <button type="button" class="btn btn-outline-danger btn-sm remove-specialization" 
                                             style="<?= $index == 0 ? 'display: none;' : '' ?>">
                                         <i class="fa fa-trash"></i>
                                     </button>
                                 </div>
                                             </div>
                                         </div>
                                     <?php endforeach; ?>
                                 <?php else: ?>
                                     <div class="specialization-item border rounded p-3 mb-3" data-index="0">
                                         <div class="row">
                                             <div class="col-md-4">
                                                 <label class="font-weight-bold">Subject</label>
                                                 <select class="form-control" name="specializations[0][subject_id]">
                                                     <option value="">Select Subject</option>
                                                     <?php if (isset($subjects) && !empty($subjects)): ?>
                                                         <?php foreach ($subjects as $subject): ?>
                                                             <option value="<?= $subject['id'] ?>"><?= esc($subject['subject_name']) ?></option>
                                                         <?php endforeach; ?>
                                                     <?php endif; ?>
                                                 </select>
                                             </div>
                                             <div class="col-md-3">
                                                 <label class="font-weight-bold">Proficiency Level</label>
                                                 <select class="form-control" name="specializations[0][proficiency_level]">
                                                     <option value="">Select Level</option>
                                                     <option value="Basic">Basic</option>
                                                     <option value="Intermediate">Intermediate</option>
                                                     <option value="Advanced">Advanced</option>
                                                     <option value="Expert">Expert</option>
                                                 </select>
                                             </div>
                                             <div class="col-md-3">
                                                 <label class="font-weight-bold">Years Experience</label>
                                                 <input type="number" class="form-control" name="specializations[0][years_experience]" 
                                                        min="0" max="50" placeholder="0">
                                             </div>
                                             <div class="col-md-1">
                                 <label class="font-weight-bold">Remove</label><br>
                                 <button type="button" class="btn btn-outline-danger btn-sm remove-specialization" 
                                         style="display: none;">
                                     <i class="fa fa-trash"></i>
                                 </button>
                             </div>
                                         </div>
                                     </div>
                                 <?php endif; ?>
                             </div>
                             
                             <button type="button" class="btn btn-outline-primary btn-sm" id="add-specialization">
                                 <i class="fa fa-plus mr-2"></i> Add Another Specialization
                             </button>
                         </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="row mt-4">
                        <div class="col-md-12 text-right">
                            <div class="form-group mb-0">
                                <a href="<?= site_url('/admin/teacher') ?>" class="btn btn-secondary mr-2">
                                    <i class="icon-copy dw dw-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="icon-copy dw dw-check"></i> Update Teacher
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.style.display = 'none';
            }, 500);
        });
    }, 5000);
    
    // Handle "same as residential" checkbox
    const sameAsResidentialCheckbox = document.getElementById('same_as_residential');
    const permanentAddressSection = document.getElementById('permanent_address_section');
    const permanentAddressFields = document.getElementById('permanent_address_fields');
    
    if (sameAsResidentialCheckbox) {
        sameAsResidentialCheckbox.addEventListener('change', function() {
            if (this.checked) {
                // Hide permanent address fields with animation
                permanentAddressSection.style.transition = 'opacity 0.3s ease';
                permanentAddressFields.style.transition = 'opacity 0.3s ease';
                permanentAddressSection.style.opacity = '0';
                permanentAddressFields.style.opacity = '0';
                
                setTimeout(function() {
                    permanentAddressSection.style.display = 'none';
                    permanentAddressFields.style.display = 'none';
                    
                    // Copy residential address values to permanent address fields
                    copyResidentialToPermanent();
                }, 300);
            } else {
                // Show permanent address fields with animation
                permanentAddressSection.style.display = 'block';
                permanentAddressFields.style.display = 'flex';
                permanentAddressSection.style.opacity = '0';
                permanentAddressFields.style.opacity = '0';
                
                setTimeout(function() {
                    permanentAddressSection.style.opacity = '1';
                    permanentAddressFields.style.opacity = '1';
                }, 10);
            }
        });
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
    let specializationIndex = <?= !empty($teacher_specializations) ? count($teacher_specializations) : 1 ?>;
    
    // Add specialization functionality
    const addSpecializationBtn = document.getElementById('add-specialization');
    if (addSpecializationBtn) {
        addSpecializationBtn.addEventListener('click', function() {
            const container = document.getElementById('specializations-container');
            const newSpecialization = document.createElement('div');
            newSpecialization.className = 'specialization-item border rounded p-3 mb-3';
            newSpecialization.setAttribute('data-index', specializationIndex);
            
            newSpecialization.innerHTML = `
                <div class="row">
                    <div class="col-md-4">
                        <label class="font-weight-bold">Subject</label>
                        <select class="form-control" name="specializations[${specializationIndex}][subject_id]">
                            <option value="">Select Subject</option>
                            <?php if (isset($subjects) && !empty($subjects)): ?>
                                <?php foreach ($subjects as $subject): ?>
                                    <option value="<?= $subject['id'] ?>"><?= esc($subject['subject_name']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="font-weight-bold">Proficiency Level</label>
                        <select class="form-control" name="specializations[${specializationIndex}][proficiency_level]">
                            <option value="">Select Level</option>
                            <option value="Basic">Basic</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                            <option value="Expert">Expert</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="font-weight-bold">Years Experience</label>
                        <input type="number" class="form-control" name="specializations[${specializationIndex}][years_experience]" 
                               min="0" max="50" placeholder="0">
                    </div>
                    <div class="col-md-1">
                        <label class="font-weight-bold">Remove</label><br>
                        <button type="button" class="btn btn-outline-danger btn-sm remove-specialization">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            
            container.appendChild(newSpecialization);
            specializationIndex++;
            updateRemoveButtons();
        });
    }
    
    // Remove specialization functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-specialization')) {
            const specializationItem = e.target.closest('.specialization-item');
            if (specializationItem) {
                specializationItem.remove();
                updateRemoveButtons();
            }
        }
    });
    
    // Update remove buttons visibility
    function updateRemoveButtons() {
        const specializationItems = document.querySelectorAll('.specialization-item');
        specializationItems.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-specialization');
            if (removeBtn) {
                removeBtn.style.display = index === 0 && specializationItems.length === 1 ? 'none' : 'inline-block';
            }
        });
    }
    
    // Initialize remove buttons on page load
    updateRemoveButtons();
});
</script>

<?= $this->endSection() ?>
