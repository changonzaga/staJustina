
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
                        <a class="nav-link" data-toggle="tab" href="#address_history" role="tab" aria-selected="false">Address & Academic History</a>
                    </li>
                    <li class="nav-item">   
                        <a class="nav-link" data-toggle="tab" href="#profile_pic" role="tab" aria-selected="false">Profile Picture</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="student_info" role="tabpanel">
                        <div class="pd-20">
                            <!-- Basic Student Information -->
                            <h5 class="text-primary mb-3">Basic Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Account Number</label>
                                        <input type="text" name="account_number" class="form-control" value="<?= $student['account_number'] ?? '' ?>" readonly style="background-color: #f8f9fa; cursor: not-allowed;">
                                        <small class="form-text text-muted">Account number is auto-generated and cannot be modified</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>LRN</label>
                                        <input type="text" name="lrn" class="form-control" value="<?= $student['lrn'] ?>" required pattern="[0-9]{12}" title="LRN must contain exactly 12 numeric digits">
                                        <div class="invalid-feedback">LRN must contain exactly 12 numeric digits</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Personal Information -->
                            <h5 class="text-primary mb-3 mt-4">Personal Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" name="first_name" class="form-control" value="<?= $student['first_name'] ?? '' ?>" required>
                                        <div class="invalid-feedback">Please enter a valid first name</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Middle Name</label>
                                        <input type="text" name="middle_name" class="form-control" value="<?= $student['middle_name'] ?? '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" name="last_name" class="form-control" value="<?= $student['last_name'] ?? '' ?>" required>
                                        <div class="invalid-feedback">Please enter a valid last name</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Extension Name</label>
                                        <input type="text" name="extension_name" class="form-control" value="<?= $student['extension_name'] ?? '' ?>" placeholder="Jr., Sr., III, etc.">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Birth Certificate Number</label>
                                        <input type="text" name="birth_certificate_number" class="form-control" value="<?= $student['birth_certificate_number'] ?? '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date of Birth</label>
                                        <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="<?= $student['date_of_birth'] ?? '' ?>" required>
                                        <script>
                                        document.addEventListener("DOMContentLoaded", function () {
                                            const dobField = document.getElementById("date_of_birth");
                                            const ageField = document.getElementById("age");
                                            function computeAge() {
                                                if (!dobField || !ageField || !dobField.value) return;
                                                const dob = new Date(dobField.value);
                                                const today = new Date();
                                                let age = today.getFullYear() - dob.getFullYear();
                                                const m = today.getMonth() - dob.getMonth();
                                                if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) { age--; }
                                                ageField.value = isFinite(age) ? age : '';
                                            }
                                            if (dobField) {
                                                dobField.addEventListener("change", computeAge);
                                                dobField.addEventListener("input", computeAge);
                                                // Run once on load if value exists
                                                computeAge();
                                            }
                                        });
                                        </script>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Place of Birth</label>
                                        <input type="text" name="place_of_birth" class="form-control" value="<?= $student['place_of_birth'] ?? '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Gender</label>
                                        <select name="gender" class="form-control" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male" <?= ($student['gender'] ?? '') == 'Male' ? 'selected' : '' ?>>Male</option>
                                            <option value="Female" <?= ($student['gender'] ?? '') == 'Female' ? 'selected' : '' ?>>Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Age</label>
                                        <input type="number" name="age" id="age" class="form-control" value="<?= $student['age'] ?? '' ?>" required min="1" max="100">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mother Tongue</label>
                                        <input type="text" name="mother_tongue" class="form-control" value="<?= $student['mother_tongue'] ?? '' ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contact Information -->
                            <h5 class="text-primary mb-3 mt-4">Contact Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Student Email</label>
                                        <input type="email" name="student_email" class="form-control" value="<?= $student['student_email'] ?? '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Student Contact Number</label>
                                        <input type="text" name="student_contact" class="form-control" value="<?= $student['student_contact'] ?? '' ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Academic Information -->
                            <h5 class="text-primary mb-3 mt-4">Academic Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Grade Level</label>
                                        <select name="grade_level" class="form-control" required>
                                            <option value="">Select Grade Level</option>
                                            <?php for($i = 7; $i <= 12; $i++): ?>
                                                <option value="<?= $i ?>" <?= ($student['grade_level'] ?? '') == $i ? 'selected' : '' ?>>Grade <?= $i ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Section</label>
                                        <input type="text" name="section" class="form-control" value="<?= $student['section'] ?? '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Academic Year</label>
                                        <input type="text" name="academic_year" class="form-control" value="<?= $student['academic_year'] ?? '' ?>" placeholder="e.g., 2023-2024" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Student Status</label>
                                        <select name="student_status" class="form-control" required>
                                            <option value="">Select Status</option>
                                            <option value="active" <?= ($student['student_status'] ?? '') == 'active' ? 'selected' : '' ?>>Active</option>
                                            <option value="inactive" <?= ($student['student_status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                            <option value="suspended" <?= ($student['student_status'] ?? '') == 'suspended' ? 'selected' : '' ?>>Suspended</option>
                                            <option value="graduated" <?= ($student['student_status'] ?? '') == 'graduated' ? 'selected' : '' ?>>Graduated</option>
                                            <option value="transferred" <?= ($student['student_status'] ?? '') == 'transferred' ? 'selected' : '' ?>>Transferred</option>
                                            <option value="dropped" <?= ($student['student_status'] ?? '') == 'dropped' ? 'selected' : '' ?>>Dropped</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- Empty space for future field if needed -->
                                </div>
                            </div>
                            
                            <!-- Special Programs -->
                            <h5 class="text-primary mb-3 mt-4">Special Programs & Benefits</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Indigenous People</label>
                                        <select name="indigenous_people" class="form-control" required>
                                            <option value="No" <?= ($student['indigenous_people'] ?? 'No') == 'No' ? 'selected' : '' ?>>No</option>
                                            <option value="Yes" <?= ($student['indigenous_people'] ?? '') == 'Yes' ? 'selected' : '' ?>>Yes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Indigenous Community</label>
                                        <input type="text" name="indigenous_community" class="form-control" value="<?= $student['indigenous_community'] ?? '' ?>" placeholder="If applicable">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>4Ps Beneficiary</label>
                                        <select name="fourps_beneficiary" class="form-control" required>
                                            <option value="No" <?= ($student['fourps_beneficiary'] ?? 'No') == 'No' ? 'selected' : '' ?>>No</option>
                                            <option value="Yes" <?= ($student['fourps_beneficiary'] ?? '') == 'Yes' ? 'selected' : '' ?>>Yes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>4Ps Household ID</label>
                                        <input type="text" name="fourps_household_id" class="form-control" value="<?= $student['fourps_household_id'] ?? '' ?>" placeholder="If applicable">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Assignments -->
                            <h5 class="text-primary mb-3 mt-4">Assignments</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Assign Parent</label>
                                        <select name="parent_id" class="form-control custom-select2">
                                            <option value="">Select Parent</option>
                                            <?php foreach ($parents as $parent): ?>
                                                <option value="<?= $parent['id'] ?>" <?= ($student['parent_id'] ?? '') == $parent['id'] ? 'selected' : '' ?>>
                                                    <?= $parent['name'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="form-text text-muted">This will assign the parent as both primary and emergency contact for the student</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Address & Academic History Tab -->
                    <div class="tab-pane fade" id="address_history" role="tabpanel">
                        <div class="pd-20">
                            <!-- Address Information -->
                            <h5 class="text-primary mb-3">Address Information</h5>
                            <h6 class="text-secondary border-bottom pb-2 mb-4 mt-4">Current Address</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>House Number</label>
                                        <input type="text" name="house_no" id="house_no" class="form-control" value="<?= $student['house_no'] ?? '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Street</label>
                                        <input type="text" name="street" id="street" class="form-control" value="<?= $student['street'] ?? '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Barangay</label>
                                        <input type="text" name="barangay" id="barangay" class="form-control" value="<?= $student['barangay'] ?? '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Municipality</label>
                                        <input type="text" name="municipality" id="municipality" class="form-control" value="<?= $student['municipality'] ?? '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Province</label>
                                        <input type="text" name="province" id="province" class="form-control" value="<?= $student['province'] ?? '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Country</label>
                                        <input type="text" name="country" id="country" class="form-control" value="<?= $student['country'] ?? 'Philippines' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>ZIP Code</label>
                                        <input type="text" name="zip_code" id="zip_code" class="form-control" value="<?= $student['zip_code'] ?? '' ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Permanent Address -->
                            <h6 class="text-secondary border-bottom pb-2 mb-4 mt-4">Permanent Address</h6>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="same_as_current" name="same_as_current" <?= !empty($student['same_as_current']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="same_as_current">Same as Current Address</label>
                            </div>
                            <script>
                            // Enhanced same-as-current behavior for Edit form
                            document.addEventListener('DOMContentLoaded', function() {
                                const cb = document.getElementById('same_as_current');
                                const permanentDiv = document.getElementById('permanent_address_fields');
                                const cur = {
                                    house: document.getElementById('house_no'),
                                    street: document.getElementById('street'),
                                    barangay: document.getElementById('barangay'),
                                    city: document.getElementById('municipality'),
                                    province: document.getElementById('province'),
                                    country: document.getElementById('country'),
                                    postal: document.getElementById('zip_code'),
                                };
                                const perm = {
                                    house: document.getElementById('permanent_house_no'),
                                    street: document.getElementById('permanent_street'),
                                    barangay: document.getElementById('permanent_barangay'),
                                    city: document.getElementById('permanent_municipality'),
                                    province: document.getElementById('permanent_province'),
                                    country: document.getElementById('permanent_country'),
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
                                    // Apply initial state
                                    if (cb.checked) {
                                        copyValues();
                                        setReadonly(true);
                                        togglePermanentFields(true);
                                    } else {
                                        setReadonly(false);
                                        togglePermanentFields(false);
                                    }

                                    cb.addEventListener('change', function() {
                                        if (cb.checked) {
                                            copyValues();
                                            setReadonly(true);
                                            togglePermanentFields(true);
                                        } else {
                                            togglePermanentFields(false);
                                            setReadonly(false);
                                        }
                                    });

                                    // Keep values in sync while checked
                                    Object.values(cur).forEach(el => {
                                        if (!el) return;
                                        el.addEventListener('input', function() {
                                            if (cb.checked) copyValues();
                                        });
                                    });
                                }
                            });
                            </script>
                            
                            <div id="permanent_address_fields" class="col-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>House Number</label>
                                            <input type="text" name="permanent_house_no" id="permanent_house_no" class="form-control" value="<?= $student['permanent_house_no'] ?? '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Street</label>
                                            <input type="text" name="permanent_street" id="permanent_street" class="form-control" value="<?= $student['permanent_street'] ?? '' ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Barangay</label>
                                            <input type="text" name="permanent_barangay" id="permanent_barangay" class="form-control" value="<?= $student['permanent_barangay'] ?? '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Municipality</label>
                                            <input type="text" name="permanent_municipality" id="permanent_municipality" class="form-control" value="<?= $student['permanent_municipality'] ?? '' ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Province</label>
                                            <input type="text" name="permanent_province" id="permanent_province" class="form-control" value="<?= $student['permanent_province'] ?? '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Country</label>
                                            <input type="text" name="permanent_country" id="permanent_country" class="form-control" value="<?= $student['permanent_country'] ?? 'Philippines' ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ZIP Code</label>
                                            <input type="text" name="permanent_zip_code" id="permanent_zip_code" class="form-control" value="<?= $student['permanent_zip_code'] ?? '' ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Academic History -->
                            <h5 class="text-primary mb-3 mt-4">Academic History</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Previous GWA</label>
                                        <input type="number" name="previous_gwa" id="previous_gwa" class="form-control" value="<?= $student['previous_gwa'] ?? '' ?>" step="0.01" min="1" max="100">
                                        <script>
                                        document.addEventListener("DOMContentLoaded", function () {
                                            const gwaField = document.getElementById("previous_gwa");
                                            const perfSelect = document.getElementById("performance_level");
                                            const perfHidden = document.getElementById("performance_level_hidden");
                                            function computePerformance() {
                                                if (!gwaField) return;
                                                const val = parseFloat(gwaField.value);
                                                let performance = "";
                                                if (!isNaN(val)) {
                                                    if (val >= 90) performance = "Outstanding";
                                                    else if (val >= 85) performance = "Very Satisfactory";
                                                    else if (val >= 80) performance = "Satisfactory";
                                                    else if (val >= 75) performance = "Fairly Satisfactory";
                                                    else performance = "Did Not Meet Expectation";
                                                }
                                                if (perfSelect) {
                                                    // Try to select matching option if present
                                                    Array.from(perfSelect.options).forEach(opt => {
                                                        opt.selected = (opt.value === performance);
                                                    });
                                                }
                                                if (perfHidden) perfHidden.value = performance;
                                            }
                                            if (gwaField) {
                                                gwaField.addEventListener("input", computePerformance);
                                                gwaField.addEventListener("change", computePerformance);
                                                computePerformance();
                                            }
                                        });
                                        </script>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Performance Level</label>
                                        <select id="performance_level" class="form-control" disabled>
                                            <option value="">Select Performance Level</option>
                                            <option value="Outstanding" <?= ($student['performance_level'] ?? '') == 'Outstanding' ? 'selected' : '' ?>>Outstanding</option>
                                            <option value="Very Satisfactory" <?= ($student['performance_level'] ?? '') == 'Very Satisfactory' ? 'selected' : '' ?>>Very Satisfactory</option>
                                            <option value="Satisfactory" <?= ($student['performance_level'] ?? '') == 'Satisfactory' ? 'selected' : '' ?>>Satisfactory</option>
                                            <option value="Fairly Satisfactory" <?= ($student['performance_level'] ?? '') == 'Fairly Satisfactory' ? 'selected' : '' ?>>Fairly Satisfactory</option>
                                            <option value="Did Not Meet Expectations" <?= ($student['performance_level'] ?? '') == 'Did Not Meet Expectations' ? 'selected' : '' ?>>Did Not Meet Expectations</option>
                                        </select>
                                        <input type="hidden" name="performance_level" id="performance_level_hidden" value="<?= $student['performance_level'] ?? '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Last Grade Completed</label>
                                        <select name="last_grade_completed" class="form-control">
                                            <option value="">Select Grade</option>
                                            <?php for($i = 7; $i <= 12; $i++): ?>
                                                <option value="<?= $i ?>" <?= ($student['last_grade_completed'] ?? '') == $i ? 'selected' : '' ?>>Grade <?= $i ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Last School Year</label>
                                        <input type="text" name="last_school_year" class="form-control" value="<?= $student['last_school_year'] ?? '' ?>" placeholder="e.g., 2022-2023">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Last School Attended</label>
                                        <input type="text" name="last_school_attended" class="form-control" value="<?= $student['last_school_attended'] ?? '' ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="profile_pic" role="tabpanel">
                        <div class="pd-20">
                            <div class="form-group text-center">
                                
                                
                                <!-- Removed simple upload section; using enrollment-style UI -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="text-center">
                                            <div class="profile-picture-section">
                                                <!-- Enhanced File Upload Area -->
                                                <div class="profile-upload-container">
                                                    <div class="profile-upload-area" id="profileUploadArea">
                                                        <div class="upload-content">
                                                            <div class="upload-icon">
                                                                <i class="fas fa-camera"></i>
                                                            </div>
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
                                                            
                                                            <!-- Aspect Ratio Buttons -->
                                                            <div class="aspect-ratio-buttons mb-3">
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
                                        </div>
                                    </div>
                                </div>

                                
                                <!-- Image Cropper Container (Hidden by default) -->
                                <div id="image-cropper-container-card" class="mt-3" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="img-container mb-3">
                                                <img id="image-to-crop-card" src="" alt="Image to crop" style="max-width: 100%;">
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
                                            
                                            <!-- Preview of cropped image (renamed to avoid ID conflicts) -->
                                            <div id="cropped-image-preview-card" class="mt-3 text-center" style="display: none;">
                                                <h6 class="text-muted">Preview:</h6>
                                                <img id="cropped-preview-card" src="" alt="Cropped preview" style="width: 180px; height: 180px; border-radius: 50%; object-fit: cover;">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Removed duplicate hidden input to avoid ID conflicts -->
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<style>
    .upload-area, .profile-upload-area {
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
    .profile-upload-container { position: relative; }
    /* Custom styles for image cropper */
    .img-container {
        overflow: hidden;
        position: relative;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        width: 100%;
        height: clamp(320px, 60vh, 800px);
    }
    /* Plain red X remove-file button */
    .remove-file-btn {
        background: transparent;
        border: none;
        color: #dc3545; /* Bootstrap danger red */
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
    .img-container img {
        display: block;
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
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
    /* Ensure cropper fills the container responsively */
    #image-cropper-container .cropper-container {
        width: 100% !important;
        height: 100% !important;
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
<?php /* Close content section and open scripts section */ ?>
<?php /* Removed duplicate scripts section start to avoid misrendering */ ?>
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
<!-- Removed legacy/inert simple upload script to prevent confusion and section misplacement -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

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
        
        // Ensure the cropper container has a responsive height
        resizeCropperContainer();
        // Show the cropper container
        document.getElementById('image-cropper-container').style.display = 'block';
        // Hide the upload area while cropping
        const uploadAreaEl = document.getElementById('profileUploadArea');
        if (uploadAreaEl) uploadAreaEl.style.display = 'none';
        
        // Hide the preview if it was shown before
        const previewContainer = document.getElementById('cropped-preview-container');
        if (previewContainer) previewContainer.style.display = 'none';
        
        // Initialize cropper after image is loaded
        imageElement.onload = function() {
            // Destroy previous cropper if exists
            if (cropper) {
                cropper.destroy();
            }
            
            // Initialize cropper
            cropper = new Cropper(imageElement, {
                aspectRatio: 1, // Square aspect ratio for profile picture
                viewMode: 2,     // Keep crop box within canvas and fit image neatly
                autoCropArea: 1, // Maximize initial crop area to the canvas
                responsive: true,
                guides: true,    // Show the dashed lines for guiding
                center: true,    // Show the center indicator for guiding
                dragMode: 'move',// Define the dragging mode of the cropper
                zoomable: true,  // Enable to zoom the image
                zoomOnWheel: true,// Enable to zoom the image by wheeling mouse
                cropBoxMovable: true,// Enable to move the crop box
                cropBoxResizable: true,// Enable to resize the crop box
                ready: function() {
                    // Re-dispatch resize so Cropper recalculates dimensions
                    window.dispatchEvent(new Event('resize'));
                    // Update data inputs when cropper is ready
                    const cropBoxData = cropper.getCropBoxData();
                    const canvasData = cropper.getCanvasData();
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
                crop: updateCropBoxData, // Update data inputs when crop box changes
                toggleDragModeOnDblclick: true // Toggle drag mode between "crop" and "move" when double click on the cropper
            });
        };
    };
    
    // Read the image file as a data URL
    reader.readAsDataURL(file);
}

// Dynamically size the cropper container based on viewport height
function resizeCropperContainer() {
    const container = document.querySelector('#image-cropper-container .img-container');
    if (!container) return;
    const vh = Math.max(document.documentElement.clientHeight || 0, window.innerHeight || 0);
    const target = Math.max(320, Math.min(Math.round(vh * 0.6), 800));
    container.style.height = target + 'px';
}

// Recalculate on window resize (Cropper listens when responsive: true)
window.addEventListener('resize', resizeCropperContainer);

window.$ && $(document).ready(function() {
    // Initialize select2 for dropdown fields
    $('.custom-select2').select2();
    // Cropper control handlers are bound below using vanilla JS to avoid duplicates.
    
    // Form submission is handled by the AJAX code below
    
    // Form submission with SweetAlert
    
    // Age calculation function
    window.calculateAge = function() {
        const dobInput = document.getElementById('date_of_birth');
        const ageInput = document.getElementById('age');
        
        if (dobInput && ageInput && dobInput.value) {
            const dob = new Date(dobInput.value);
            const today = new Date();
            
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            
            ageInput.value = age;
        }
    }
    
    // Performance level calculation function
    window.calculatePerformanceLevel = function() {
        const gwaInput = document.getElementById('previous_gwa');
        const performanceSelect = document.getElementById('performance_level');
        const performanceHidden = document.getElementById('performance_level_hidden');
        
        if (gwaInput && performanceSelect && gwaInput.value) {
            const gwa = parseFloat(gwaInput.value);
            let performanceLevel = '';
            
            if (isNaN(gwa)) {
                performanceLevel = '';
            } else if (gwa >= 90) {
                performanceLevel = 'Outstanding';
            } else if (gwa >= 85) {
                performanceLevel = 'Very Satisfactory';
            } else if (gwa >= 80) {
                performanceLevel = 'Satisfactory';
            } else if (gwa >= 75) {
                performanceLevel = 'Fairly Satisfactory';
            } else {
                performanceLevel = 'Did Not Meet Expectations';
            }
            
            // Clear all options and add the calculated one
            performanceSelect.innerHTML = `<option value="${performanceLevel}" selected>${performanceLevel}</option>`;
            // Sync hidden value for form submission
            if (performanceHidden) {
                performanceHidden.value = performanceLevel;
            }
        }
    }
    
    // Toggle permanent address function
    // Preserve original permanent address to restore on uncheck
    let originalPermanentAddress = null;
    window.togglePermanentAddress = function() {
        const checkbox = document.getElementById('same_as_current');
        const permanentDiv = document.getElementById('permanent_address_fields');
        
        if (checkbox && permanentDiv) {
            if (checkbox.checked) {
                // Store original values before overriding
                if (!originalPermanentAddress) {
                    originalPermanentAddress = {};
                    ['house_no', 'street', 'barangay', 'municipality', 'province', 'country', 'zip_code'].forEach(field => {
                        const permanentField = document.getElementById('permanent_' + field);
                        if (permanentField) {
                            originalPermanentAddress[field] = permanentField.value;
                        }
                    });
                }
                // Copy current address to permanent address
                copyCurrentToPermanent();
                // Hide permanent address fields
                permanentDiv.style.display = 'none';
            } else {
                // Show permanent address fields
                permanentDiv.style.display = 'block';
                // Restore original values if we stored them
                if (originalPermanentAddress) {
                    Object.keys(originalPermanentAddress).forEach(field => {
                        const permanentField = document.getElementById('permanent_' + field);
                        if (permanentField) {
                            permanentField.value = originalPermanentAddress[field];
                        }
                    });
                }
            }
        }
    }
    
    // Copy current address to permanent address
    window.copyCurrentToPermanent = function() {
        const currentFields = ['house_no', 'street', 'barangay', 'municipality', 'province', 'country', 'zip_code'];
        
        currentFields.forEach(field => {
            const currentField = document.getElementById(field);
            const permanentField = document.getElementById('permanent_' + field);
            
            if (currentField && permanentField) {
                permanentField.value = currentField.value;
            }
        });
    }
    
    // Setup address synchronization
    window.setupAddressSync = function() {
        const checkbox = document.getElementById('same_as_current');
        const currentFields = ['house_no', 'street', 'barangay', 'municipality', 'province', 'country', 'zip_code'];
        
        if (checkbox) {
            // Add event listeners to current address fields
            currentFields.forEach(field => {
                const currentField = document.getElementById(field);
                if (currentField) {
                    currentField.addEventListener('input', function() {
                        if (checkbox.checked) {
                            const permanentField = document.getElementById('permanent_' + field);
                            if (permanentField) {
                                permanentField.value = this.value;
                            }
                        }
                    });
                }
            });
        }
    }
    
    // Initialize all functions on page load
    // NOTE: We attach this globally (outside jQuery) so it runs regardless of jQuery presence
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize address sync
        setupAddressSync();

        // Calculate age if date of birth is already filled
        calculateAge();

        // Calculate performance level if GWA is already filled
        calculatePerformanceLevel();
        // Ensure hidden performance value matches select on load
        const perfSelect = document.getElementById('performance_level');
        const perfHidden = document.getElementById('performance_level_hidden');
        if (perfSelect && perfHidden) {
            perfHidden.value = perfSelect.value || perfHidden.value || '';
        }

        // Initialize profile upload
        initializeProfileUpload();

        // Bind live events
        const dobInput = document.getElementById('date_of_birth');
        if (dobInput) {
            dobInput.addEventListener('change', calculateAge);
            dobInput.addEventListener('input', calculateAge);
        }
        const gwaInput = document.getElementById('previous_gwa');
        if (gwaInput) {
            gwaInput.addEventListener('change', calculatePerformanceLevel);
            gwaInput.addEventListener('input', calculatePerformanceLevel);
        }
        const sameAsCheckbox = document.getElementById('same_as_current');
        if (sameAsCheckbox) {
            sameAsCheckbox.addEventListener('change', togglePermanentAddress);
            // Initialize visibility state based on initial checkbox value
            togglePermanentAddress();
        }
    });
    
    // Form submission with SweetAlert (guarded if jQuery is available)
    if (window.$) {
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
    }
});

// Fallback event bindings when jQuery is not available
if (!window.$) {
  // Aspect ratio buttons
  const btn1to1 = document.getElementById('aspectRatio1to1');
  const btn4to3 = document.getElementById('aspectRatio4to3');
  const btnFree = document.getElementById('aspectRatioFree');
  if (btn1to1) {
    btn1to1.classList.add('active');
    btn1to1.addEventListener('click', function() {
      if (!cropper) return;
      cropper.setAspectRatio(1);
      this.classList.add('active');
      [btn4to3, btnFree].forEach(b => b && b.classList.remove('active'));
    });
  }
  if (btn4to3) {
    btn4to3.addEventListener('click', function() {
      if (!cropper) return;
      cropper.setAspectRatio(4/3);
      this.classList.add('active');
      [btn1to1, btnFree].forEach(b => b && b.classList.remove('active'));
    });
  }
  if (btnFree) {
    btnFree.addEventListener('click', function() {
      if (!cropper) return;
      cropper.setAspectRatio(NaN);
      this.classList.add('active');
      [btn1to1, btn4to3].forEach(b => b && b.classList.remove('active'));
    });
  }

  // Crop image button
  const cropBtn = document.getElementById('crop-image');
  if (cropBtn) {
    cropBtn.addEventListener('click', function() {
      if (!cropper) return;
      const canvas = cropper.getCroppedCanvas({
        width: 300,
        height: 300,
        minWidth: 100,
        minHeight: 100,
        maxWidth: 1000,
        maxHeight: 1000,
        fillColor: '#fff',
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
      });
      const croppedImageData = canvas.toDataURL('image/jpeg', 0.8);
      const hiddenInput = document.getElementById('cropped_image_data');
      if (hiddenInput) hiddenInput.value = croppedImageData;
      const previewWrap = document.getElementById('cropped-preview-container');
      const previewImg = document.getElementById('cropped-image-preview');
      if (previewWrap) previewWrap.style.display = 'block';
      if (previewImg) previewImg.src = croppedImageData;
      const cropperContainer = document.getElementById('image-cropper-container');
      if (cropperContainer) cropperContainer.style.display = 'none';
    });
  }

  // Cancel crop button
  const cancelBtn = document.getElementById('cancel-crop');
  if (cancelBtn) {
    cancelBtn.addEventListener('click', function() {
      const cropperContainer = document.getElementById('image-cropper-container');
      if (cropperContainer) cropperContainer.style.display = 'none';
      const fileInput = document.getElementById('profile_picture');
      if (fileInput) fileInput.value = '';
      if (cropper) { cropper.destroy(); cropper = null; }
      ['dataX','dataY','dataWidth','dataHeight','dataRotate','dataScaleX'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
      });
      const previewWrap = document.getElementById('cropped-preview-container');
      if (previewWrap) previewWrap.style.display = 'none';
    });
  }
}
</script>
<script>
// Profile upload initializer and helpers
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

    // Click to open file chooser
    uploadArea.addEventListener('click', () => fileInput.click());

    // Drag & drop support
    ['dragenter','dragover'].forEach(ev => uploadArea.addEventListener(ev, (e) => {
        e.preventDefault();
        e.stopPropagation();
        uploadArea.classList.add('dragging');
    }));
    ['dragleave','drop'].forEach(ev => uploadArea.addEventListener(ev, (e) => {
        e.preventDefault();
        e.stopPropagation();
        uploadArea.classList.remove('dragging');
    }));
    uploadArea.addEventListener('drop', (e) => {
        if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]) {
            fileInput.files = e.dataTransfer.files;
            const event = new Event('change');
            fileInput.dispatchEvent(event);
        }
    });

    // Show selected file info
    fileInput.addEventListener('change', (event) => {
        const file = fileInput.files && fileInput.files[0];
        if (!file) {
            fileInfo && (fileInfo.style.display = 'none');
            return;
        }
        fileNameEl && (fileNameEl.textContent = file.name);
        fileSizeEl && (fileSizeEl.textContent = `${(file.size/1024).toFixed(1)} KB`);
        fileInfo && (fileInfo.style.display = 'block');

        // Automatically open the cropper once a file is selected
        loadImageForCropping(event);
    });

    // When we have a cropped image, show preview and store data
    window.cropImage = function() {
        if (!cropper) return;
        const canvas = cropper.getCroppedCanvas({ width: 400, height: 400 });
        const dataUrl = canvas.toDataURL('image/jpeg');
        const preview = document.getElementById('cropped-image-preview');
        if (preview) {
            preview.src = dataUrl;
            preview.style.display = 'inline-block';
        }
        if (croppedPreviewContainer) {
            croppedPreviewContainer.style.display = 'block';
        }
        if (cropperContainer) {
            cropperContainer.style.display = 'none';
        }
        if (croppedImageData) {
            croppedImageData.value = dataUrl;
        }
    };

    window.cancelCrop = function() {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        if (cropperContainer) {
            cropperContainer.style.display = 'none';
        }
        if (croppedPreviewContainer) {
            croppedPreviewContainer.style.display = 'none';
        }
        const preview = document.getElementById('cropped-image-preview');
        if (preview) {
            preview.src = '';
        }
        if (fileInput) {
            fileInput.value = '';
        }
        if (croppedImageData) {
            croppedImageData.value = '';
        }
        // Also hide any selected file info card
        if (fileInfo) {
            fileInfo.style.display = 'none';
        }
        // Show the upload area again
        if (uploadArea) {
            uploadArea.style.display = 'block';
        }
    };

    window.removeSelectedFile = function() {
        if (fileInput) fileInput.value = '';
        if (fileInfo) fileInfo.style.display = 'none';
        cancelCrop();
    };
}
</script>
<script>
// Ensure the profile upload section initializes on page load
document.addEventListener('DOMContentLoaded', function () {
  try {
    initializeProfileUpload();
    // Initialize select2 if jQuery is available
    if (window.$) { $('.custom-select2').select2(); }
  } catch (err) {
    console.error('Profile upload initialization failed:', err);
  }
});
</script>
<?= $this->endSection() ?>
