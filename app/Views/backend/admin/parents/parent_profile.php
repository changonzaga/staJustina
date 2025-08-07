<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Parent Profile</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home')?>">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= site_url('admin/parent')?>">Parents</a>
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
                    <?php if(!empty($parent['profile_picture'])): ?>
                        <img src="<?= base_url('uploads/parents/'.$parent['profile_picture']) ?>" alt="" class="avatar-photo img-thumbnail" style="width: 160px; height: 160px; object-fit: cover;">
                    <?php else: ?>
                        <div class="avatar-photo" style="width: 160px; height: 160px; margin: 0 auto; border-radius: 50%; background: #ebf3ff; display: flex; align-items: center; justify-content: center;">
                            <span class="font-24 text-blue weight-500"><?= substr($parent['name'], 0, 1) ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="profile-info text-center">
                <h5 class="mb-10 text-center h5"><?= $parent['name'] ?></h5>
                <p class="font-14 text-center text-muted"><?= $parent['email'] ?></p>
                <div class="mb-2">
                    <span class="badge badge-success">Parent</span>
                </div>
                <div class="profile-social text-center">
                    <a href="#" class="btn" data-color="#3b5998"><i class="fa fa-facebook"></i></a>
                    <a href="#" class="btn" data-color="#1da1f2"><i class="fa fa-twitter"></i></a>
                    <a href="#" class="btn" data-color="#f46f30"><i class="fa fa-instagram"></i></a>
                </div>
                <div class="profile-social text-center mt-3">
                    <a href="<?= site_url('admin/parent/edit/' . $parent['id']) ?>" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit Profile
                    </a>
                    <a href="<?= site_url('admin/parent') ?>" class="btn btn-secondary btn-sm">
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
                            <a class="nav-link" data-toggle="tab" href="#children" role="tab">Children</a>
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
                                                        <h5 class="h5 mb-10">Parent Information</h5>
                                                    </div>
                                                    <div class="pd-20">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Account No.</label>
                                                                    <p class="form-control-static"><?= $parent['account_no'] ?? 'Not assigned' ?></p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Full Name</label>
                                                                    <p class="form-control-static"><?= $parent['name'] ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Gender</label>
                                                                    <p class="form-control-static"><?= $parent['gender'] ?? 'Not specified' ?></p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Age</label>
                                                                    <p class="form-control-static"><?= $parent['age'] ?? 'Not specified' ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Email</label>
                                                                    <p class="form-control-static"><?= $parent['email'] ?></p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Contact Number</label>
                                                                    <p class="form-control-static"><?= $parent['contact'] ?? 'Not provided' ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Status</label>
                                                                    <p class="form-control-static">
                                                                        <?php if(isset($parent['status']) && $parent['status'] == 'Active'): ?>
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
                                                                    <p class="form-control-static"><?= date('F j, Y', strtotime($parent['created_at'])) ?></p>
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
                        <!-- Children Tab -->
                        <div class="tab-pane fade" id="children" role="tabpanel">
                            <div class="pd-20 profile-task-wrap">
                                <div class="container pd-0">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card-box mb-30">
                                                <div class="pd-20">
                                                    <h5 class="h5 mb-10">Children</h5>
                                                </div>
                                                <div class="pb-20">
                                                    <div class="table-responsive">
                                                        <table class="table hover multiple-select-row data-table-export nowrap">
                                                            <thead>
                                                                <tr>
                                                                    <th>No.</th>
                                                                    <th>Child Name</th>
                                                                    <th>Grade Level</th>
                                                                    <th>Section</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- This would be populated with actual student data -->
                                                                <tr>
                                                                    <td colspan="5" class="text-center">No children registered yet.</td>
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
                                                <div class="task-name"><i class="ion-android-alarm-clock"></i> Parent Profile Created</div>
                                                <p>Parent profile was created in the system.</p>
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