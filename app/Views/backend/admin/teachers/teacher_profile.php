<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Teacher Profile</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home')?>">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= site_url('admin/teacher')?>">Teachers</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        View Profile
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
        <div class="pd-20 card-box height-100-p">
            <div class="profile-photo">
                <div class="text-center">
                    <?php if(!empty($teacher['profile_picture'])): ?>
                        <img src="<?= base_url('uploads/teachers/'.$teacher['profile_picture']) ?>" alt="" class="avatar-photo img-thumbnail" style="width: 160px; height: 160px; object-fit: cover;">
                    <?php else: ?>
                        <div class="avatar-photo" style="width: 160px; height: 160px; margin: 0 auto; border-radius: 50%; background: #ebf3ff; display: flex; align-items: center; justify-content: center;">
                            <span class="font-24 text-blue weight-500"><?= substr($teacher['name'], 0, 1) ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="profile-info text-center">
                <h5 class="mb-10 text-center h5"><?= esc($teacher['first_name'] . ' ' . $teacher['last_name']) ?></h5>
                <p class="font-14 text-center text-muted"><?= esc($teacher['position'] ?? 'Teacher') ?></p>
                <div class="mb-2">
                    <?php 
                    $status = $teacher['status'] ?? 'Active';
                    $badgeClass = '';
                    switch(strtolower($status)) {
                        case 'active':
                            $badgeClass = 'badge-success';
                            break;
                        case 'inactive':
                            $badgeClass = 'badge-danger';
                            break;
                        case 'suspended':
                            $badgeClass = 'badge-warning';
                            break;
                        default:
                            $badgeClass = 'badge-primary';
                    }
                    ?>
                    <span class="badge <?= $badgeClass ?>"><?= esc($status) ?></span>
                </div>
                <div class="profile-social text-center">
                    <a href="#" class="btn" data-color="#3b5998"><i class="fa fa-facebook"></i></a>
                    <a href="#" class="btn" data-color="#1da1f2"><i class="fa fa-twitter"></i></a>
                    <a href="#" class="btn" data-color="#f46f30"><i class="fa fa-instagram"></i></a>
                </div>
                <div class="profile-social text-center mt-3">
                    <a href="<?= site_url('admin/teacher/edit/' . $teacher['id']) ?>" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit Profile
                    </a>
                    <a href="<?= site_url('admin/teacher') ?>" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
        <div class="card-box height-100-p overflow-hidden">
            <div class="profile-tab height-100-p">
                <div class="tab height-100-p">
                    <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#personal_info" role="tab">Personal Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#students" role="tab">Students</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#timeline" role="tab">Timeline</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <!-- Personal Info Tab -->
                        <div class="tab-pane fade show active" id="personal_info" role="tabpanel">
                            <div class="pd-20">
                                <div class="profile-timeline">
                                    <div class="profile-timeline-list">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card-box">
                                                    <div class="pd-20">
                                                        <h5 class="h5 mb-10">Teacher Information</h5>
                                                    </div>
                                                    <div class="pd-20">
                                                        <!-- Basic Information -->
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label><strong>Account No.</strong></label>
                                                                    <p class="form-control-static"><?= esc($teacher['account_no'] ?? 'Not assigned') ?></p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label><strong>Employee ID</strong></label>
                                                                    <p class="form-control-static"><?= esc($teacher['employee_id'] ?? 'Not assigned') ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label><strong>First Name</strong></label>
                                                                    <p class="form-control-static"><?= esc($teacher['first_name']) ?></p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label><strong>Middle Name</strong></label>
                                                                    <p class="form-control-static"><?= esc($teacher['middle_name'] ?? 'N/A') ?></p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label><strong>Last Name</strong></label>
                                                                    <p class="form-control-static"><?= esc($teacher['last_name']) ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label><strong>Date of Birth</strong></label>
                                                                    <p class="form-control-static">
                                                                        <?php if (!empty($teacher['date_of_birth'])): ?>
                                                                            <?= date('F j, Y', strtotime($teacher['date_of_birth'])) ?>
                                                                        <?php else: ?>
                                                                            Not specified
                                                                        <?php endif; ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label><strong>Gender</strong></label>
                                                                    <p class="form-control-static"><?= esc($teacher['gender'] ?? 'Not specified') ?></p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label><strong>Age</strong></label>
                                                                    <p class="form-control-static">
                                                                        <?php if (!empty($teacher['date_of_birth'])): ?>
                                                                            <?= date_diff(date_create($teacher['date_of_birth']), date_create('today'))->y ?> years old
                                                                        <?php else: ?>
                                                                            Not calculated
                                                                        <?php endif; ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label><strong>Contact Number</strong></label>
                                                                    <p class="form-control-static"><?= esc($teacher['contact_number'] ?? 'Not provided') ?></p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label><strong>Civil Status</strong></label>
                                                                    <p class="form-control-static"><?= esc($teacher['civil_status_name'] ?? 'Not specified') ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label><strong>Nationality</strong></label>
                                                                    <p class="form-control-static"><?= esc($teacher['nationality'] ?? 'Filipino') ?></p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label><strong>Account Status</strong></label>
                                                                    <p class="form-control-static">
                                                                        <span class="badge <?= $badgeClass ?>"><?= esc($status) ?></span>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Professional Information -->
                                                        <hr class="my-4">
                                                        <h6 class="text-primary mb-3">Professional Information</h6>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label><strong>Position</strong></label>
                                                                    <p class="form-control-static"><?= esc($teacher['position'] ?? 'Not specified') ?></p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label><strong>Employment Status</strong></label>
                                                                    <p class="form-control-static"><?= esc($teacher['employment_status_name'] ?? 'Not specified') ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label><strong>Educational Attainment</strong></label>
                                                                    <p class="form-control-static"><?= esc($teacher['educational_attainment'] ?? 'Not specified') ?></p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label><strong>PRC License Number</strong></label>
                                                                    <p class="form-control-static"><?= esc($teacher['prc_license_number'] ?? 'Not provided') ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label><strong>Eligibility Status</strong></label>
                                                                    <p class="form-control-static"><?= esc($teacher['eligibility_status'] ?? 'Not specified') ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Status</label>
                                                                    <p class="form-control-static">
                                                                        <?php if($teacher['status'] == 'Active'): ?>
                                                                            <span class="badge badge-success">Active</span>
                                                                        <?php else: ?>
                                                                            <span class="badge badge-danger">Inactive</span>
                                                                        <?php endif; ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Created At</label>
                                                                    <p class="form-control-static"><?= date('F j, Y', strtotime($teacher['created_at'])) ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Students Tab -->
                        <div class="tab-pane fade" id="students" role="tabpanel">
                            <div class="pd-20 profile-task-wrap">
                                <div class="container pd-0">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card-box mb-30">
                                                <div class="pd-20">
                                                    <h5 class="h5 mb-10">Students Assigned</h5>
                                                </div>
                                                <div class="pb-20">
                                                    <div class="table-responsive">
                                                        <table class="table hover multiple-select-row data-table-export nowrap">
                                                            <thead>
                                                                <tr>
                                                                    <th>No.</th>
                                                                    <th>Student Name</th>
                                                                    <th>Grade Level</th>
                                                                    <th>Section</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- This would be populated with actual student data -->
                                                                <tr>
                                                                    <td colspan="5" class="text-center">No students assigned yet.</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Timeline Tab -->
                        <div class="tab-pane fade" id="timeline" role="tabpanel">
                            <div class="pd-20">
                                <div class="profile-timeline">
                                    <div class="timeline-month">
                                        <h5><?= date('F Y') ?></h5>
                                    </div>
                                    <div class="profile-timeline-list">
                                        <ul>
                                            <li>
                                                <div class="date"><?= date('d') ?></div>
                                                <div class="task-name"><i class="ion-android-alarm-clock"></i> Teacher Profile Created</div>
                                                <p>Teacher profile was created in the system.</p>
                                                <div class="task-time">09:30 am</div>
                                            </li>
                                            <!-- Additional timeline items would be added here -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Scripts can be added here if needed
</script>
<?= $this->endSection() ?>