<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('stylesheets') ?>
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="/backend/src/plugins/sweetalert2/sweetalert2.css">
<style>
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
    
    /* Review step styles (removed review UI) */
    .font-weight-medium { font-weight: 500; }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const requiredFields = ['first_name', 'last_name', 'relationship_type', 'student_id', 'contact_number'];
        document.querySelector('form').addEventListener('submit', function(e) {
            let isValid = true;
            requiredFields.forEach(function(field){
            const input = document.getElementById(field);
                if (!input || !String(input.value || '').trim()) {
                    if (input) input.classList.add('is-invalid');
                isValid = false;
                } else if (input) {
                input.classList.remove('is-invalid');
            }
        });
        if (!isValid) {
                e.preventDefault();
                alert('Please complete all required parent information fields before submitting.');
            }
        });

    });

    // Toggle address fields based on "same as student" checkbox
    function toggleAddressFields() {
        const checkbox = document.getElementById('is_same_as_student');
        const addressFields = document.getElementById('address_fields');
        const addressInputs = addressFields.querySelectorAll('input[type="text"]');
        
        if (checkbox.checked) {
            // Disable and clear address fields
            addressInputs.forEach(input => {
                input.disabled = true;
                input.value = '';
            });
            addressFields.style.opacity = '0.6';
        } else {
            // Enable address fields
            addressInputs.forEach(input => {
                input.disabled = false;
            });
            addressFields.style.opacity = '1';
        }
    }

    // Initialize address fields on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleAddressFields();
        toggleEmergencyContact(); // Initialize emergency contact state
    });

    // Toggle emergency contact based on primary contact selection
    function toggleEmergencyContact() {
        const primaryContact = document.getElementById('is_primary_contact');
        const emergencyContact = document.getElementById('is_emergency_contact');
        
        if (primaryContact.value === '1') {
            // If primary contact is Yes, automatically set emergency contact to Yes
            emergencyContact.value = '1';
            emergencyContact.readOnly = true;
            emergencyContact.style.opacity = '0.6';
            emergencyContact.style.backgroundColor = '#f8f9fa';
            emergencyContact.style.cursor = 'not-allowed';
        } else {
            // If primary contact is No, enable emergency contact selection
            emergencyContact.readOnly = false;
            emergencyContact.style.opacity = '1';
            emergencyContact.style.backgroundColor = '';
            emergencyContact.style.cursor = '';
        }
    }
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

        <!-- Removed form wizard UI -->

        <form action="<?= route_to('admin.parent.store') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- Parent Information -->
            <div>
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
                        <label for="relationship_type" class="font-weight-bold">Relationship to Student <span class="text-danger">*</span></label>
                        <select id="relationship_type" name="relationship_type" class="form-control mt-2" required>
                            <option value="">Select Relationship</option>
                            <option value="Mother" <?= old('relationship_type') == 'Mother' ? 'selected' : '' ?>>Mother</option>
                            <option value="Father" <?= old('relationship_type') == 'Father' ? 'selected' : '' ?>>Father</option>
                            <option value="Guardian" <?= old('relationship_type') == 'Guardian' ? 'selected' : '' ?>>Guardian</option>
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
                   
                </div>

                <!-- Contact Information -->
                <div class="row mb-5">
                    <div class="col-12"><h5 class="text-primary border-bottom pb-2 mb-4">Contact Information</h5></div>
                    <div class="col-md-6 mb-3">
                        <label for="contact_number" class="font-weight-bold">Contact Number <span class="text-danger">*</span></label>
                        <input type="text" id="contact_number" name="contact_number" value="<?= old('contact_number') ?>" class="form-control mt-2" required>
                        <div class="invalid-feedback">Please enter a valid contact number.</div>
                    </div>
                </div>

                <!-- Parent Address -->
                <div class="row mb-5">
                    <div class="col-12"><h5 class="text-primary border-bottom pb-2 mb-4">Parent Address</h5></div>
                    <div class="col-12 mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_same_as_student" id="is_same_as_student" class="form-check-input" onchange="toggleAddressFields()">
                            <label for="is_same_as_student" class="form-check-label">Same as Student's Address</label>
                        </div>
                    </div>
                    <div id="address_fields" class="col-12">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="house_number" class="font-weight-bold">House Number</label>
                                <input type="text" id="house_number" name="house_number" value="<?= old('house_number') ?>" class="form-control mt-2">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="street" class="font-weight-bold">Street</label>
                                <input type="text" id="street" name="street" value="<?= old('street') ?>" class="form-control mt-2">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="barangay" class="font-weight-bold">Barangay</label>
                                <input type="text" id="barangay" name="barangay" value="<?= old('barangay') ?>" class="form-control mt-2">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="municipality" class="font-weight-bold">Municipality/City</label>
                                <input type="text" id="municipality" name="municipality" value="<?= old('municipality') ?>" class="form-control mt-2">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="province" class="font-weight-bold">Province</label>
                                <input type="text" id="province" name="province" value="<?= old('province') ?>" class="form-control mt-2">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="zip_code" class="font-weight-bold">ZIP Code</label>
                                <input type="text" id="zip_code" name="zip_code" value="<?= old('zip_code') ?>" class="form-control mt-2">
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Relationship Details -->
                <div class="row mb-5">
                    <div class="col-12"><h5 class="text-primary border-bottom pb-2 mb-4">Relationship Details</h5></div>
                    <div class="col-md-6 mb-3">
                        <label for="is_primary_contact" class="font-weight-bold">Is Primary Contact?</label>
                        <select id="is_primary_contact" name="is_primary_contact" class="form-control mt-2" onchange="toggleEmergencyContact()">
                            <option value="0" <?= old('is_primary_contact', '0') == '0' ? 'selected' : '' ?>>No</option>
                            <option value="1" <?= old('is_primary_contact') == '1' ? 'selected' : '' ?>>Yes</option>
                        </select>
                        <small class="text-muted">Primary contacts are automatically set as emergency contacts</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="is_emergency_contact" class="font-weight-bold">Is Emergency Contact?</label>
                        <select id="is_emergency_contact" name="is_emergency_contact" class="form-control mt-2">
                            <option value="0" <?= old('is_emergency_contact', '0') == '0' ? 'selected' : '' ?>>No</option>
                            <option value="1" <?= old('is_emergency_contact') == '1' ? 'selected' : '' ?>>Yes</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Step 2 removed -->

            <!-- Review step removed -->

            <div class="d-flex justify-content-end" style="border-top: 1px solid #e9ecef; padding-top: 20px; margin-top: 30px;">
                    <a href="<?= route_to('admin.parent') ?>" class="btn btn-outline-secondary" style="margin-right: 10px;">Cancel</a>
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

