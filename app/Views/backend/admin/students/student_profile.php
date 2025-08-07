<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Student Profile</h4>
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
                        View Profile
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left column with profile picture and basic info -->
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
        <div class="pd-20 card-box height-100-p">
            <div class="profile-photo text-center">
                <?php if (!empty($student['profile_picture'])): ?>
                    <img src="<?= base_url('uploads/students/' . $student['profile_picture']) ?>" 
                         alt="Profile" class="avatar-photo" style="width: 150px; height: 150px; border-radius: 50%;">
                <?php else: ?>
                    <div class="avatar-photo" style="width: 150px; height: 150px; margin: 0 auto; background: #007bff; color: white; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 48px; font-weight: bold;">
                        <?= strtoupper(substr($student['name'], 0, 2)) ?>
                    </div>
                <?php endif; ?>
            </div>
            <h5 class="text-center h5 mb-0 mt-3"><?= esc($student['name']) ?></h5>
            <p class="text-center text-muted font-14">LRN: <?= esc($student['lrn']) ?></p>
            <div class="profile-info">
                <h5 class="mb-20 h5 text-blue">Basic Information</h5>
                <ul>
                    <li>
                        <span>Gender:</span>
                        <?= esc($student['gender']) ?>
                    </li>
                    <li>
                        <span>Age:</span>
                        <?= esc($student['age']) ?> years old
                    </li>
                    <li>
                        <span>Grade Level:</span>
                        <?= esc($student['grade_level']) ?>
                    </li>
                    <li>
                        <span>Section:</span>
                        <?= esc($student['section']) ?>
                    </li>
                </ul>
            </div>
            <div class="profile-social text-center">
                <a href="<?= site_url('admin/student/edit/' . $student['id']) ?>" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> Edit Profile
                </a>
                <a href="<?= site_url('admin/student') ?>" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
    
    <!-- Right column with detailed information -->
    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
        <div class="card-box height-100-p overflow-hidden">
            <div class="profile-tab height-100-p">
                <div class="tab height-100-p">
                    <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#personal" role="tab">Personal Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#academic" role="tab">Academic Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#attendance" role="tab">Attendance</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <!-- Personal Information Tab -->
                        <div class="tab-pane fade show active" id="personal" role="tabpanel">
                            <div class="pd-20">
                                <div class="profile-timeline">
                                    <div class="timeline-title">Personal Details</div>
                                    <div class="profile-details">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th width="30%">Full Name</th>
                                                <td><?= esc($student['name']) ?></td>
                                            </tr>
                                            <tr>
                                                <th>Address</th>
                                                <td><?= esc($student['address']) ?: 'Not provided' ?></td>
                                            </tr>
                                            <tr>
                                                <th>Guardian</th>
                                                <td><?= esc($student['guardian']) ?: 'Not provided' ?></td>
                                            </tr>
                                            <tr>
                                                <th>Contact Number</th>
                                                <td><?= esc($student['contact']) ?: 'Not provided' ?></td>
                                            </tr>
                                            <tr>
                                                <th>Parent</th>
                                                <td><?= isset($student['parent_name']) ? esc($student['parent_name']) : 'Not assigned' ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Academic Information Tab -->
                        <div class="tab-pane fade" id="academic" role="tabpanel">
                            <div class="pd-20">
                                <div class="profile-timeline">
                                    <div class="timeline-title">Academic Details</div>
                                    <div class="profile-details">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th width="30%">LRN</th>
                                                <td><?= esc($student['lrn']) ?></td>
                                            </tr>
                                            <tr>
                                                <th>Grade Level</th>
                                                <td><?= esc($student['grade_level']) ?></td>
                                            </tr>
                                            <tr>
                                                <th>Section</th>
                                                <td><?= esc($student['section']) ?></td>
                                            </tr>
                                            <tr>
                                                <th>Teacher</th>
                                                <td><?= isset($student['teacher_name']) ? esc($student['teacher_name']) : 'Not assigned' ?></td>
                                            </tr>
                                        </table>
                                        
                                        <div class="mt-4">
                                            <h5 class="mb-3">Academic Records</h5>
                                            <a href="<?= site_url('admin/student/grades/' . $student['id']) ?>" class="btn btn-primary btn-sm">
                                                <i class="bi bi-award"></i> View Grades & Report Cards
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Attendance Tab -->
                        <div class="tab-pane fade" id="attendance" role="tabpanel">
                            <div class="pd-20">
                                <div class="profile-timeline">
                                    <div class="timeline-title">Attendance Records</div>
                                    <div class="profile-details">
                                        <div class="mb-3">
                                            <a href="<?= site_url('admin/student/attendance/' . $student['id']) ?>" class="btn btn-info btn-sm">
                                                <i class="bi bi-calendar-check"></i> View Full Attendance History
                                            </a>
                                        </div>
                                        
                                        <?php if (isset($attendance) && !empty($attendance)): ?>
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Status</th>
                                                        <th>Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($attendance as $record): ?>
                                                        <tr>
                                                            <td><?= date('M d, Y', strtotime($record['date'])) ?></td>
                                                            <td>
                                                                <?php if ($record['status'] == 'Present'): ?>
                                                                    <span class="badge badge-success">Present</span>
                                                                <?php elseif ($record['status'] == 'Absent'): ?>
                                                                    <span class="badge badge-danger">Absent</span>
                                                                <?php elseif ($record['status'] == 'Late'): ?>
                                                                    <span class="badge badge-warning">Late</span>
                                                                <?php else: ?>
                                                                    <span class="badge badge-secondary"><?= $record['status'] ?></span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td><?= esc($record['remarks']) ?: 'No remarks' ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        <?php else: ?>
                                            <div class="alert alert-info">
                                                No recent attendance records found.
                                            </div>
                                        <?php endif; ?>
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