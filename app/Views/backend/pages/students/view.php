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
                        <a href="<?= site_url('backend/pages/students') ?>">Students</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        View Profile
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
        <div class="row">
            <div class="col-md-4 text-center">
                <?php if (!empty($student['profile_picture'])): ?>
                    <img src="<?= base_url('Uploads/students/' . $student['profile_picture']) ?>" 
                         alt="Profile" class="mb-3" style="width: 150px; height: 150px; border-radius: 50%;">
                <?php else: ?>
                    <div class="avatar-photo mb-3" style="width: 150px; height: 150px; background: #007bff; color: white; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 48px; font-weight: bold;">
                        <?= strtoupper(substr($student['name'], 0, 2)) ?>
                    </div>
                <?php endif; ?>
                <h5><?= esc($student['name']) ?></h5>
                <p class="text-muted">LRN: <?= esc($student['lrn']) ?></p>
            </div>
            <div class="col-md-8">
                <table class="table table-bordered">
                    <tr>
                        <th>Gender</th>
                        <td><?= esc($student['gender']) ?></td>
                    </tr>
                    <tr>
                        <th>Age</th>
                        <td><?= esc($student['age']) ?> years old</td>
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
                        <th>Address</th>
                        <td><?= esc($student['address']) ?: 'Not provided' ?></td>
                    </tr>
                    <tr>
                        <th>Guardian</th>
                        <td><?= esc($student['guardian']) ?: 'Not provided' ?></td>
                    </tr>
                    <tr>
                        <th>Contact</th>
                        <td><?= esc($student['contact']) ?: 'Not provided' ?></td>
                    </tr>
                    <tr>
                        <th>Teacher</th>
                        <td><?= isset($student['teacher_name']) ? esc($student['teacher_name']) : 'Not assigned' ?></td>
                    </tr>
                    <tr>
                        <th>Parent</th>
                        <td><?= isset($student['parent_name']) ? esc($student['parent_name']) : 'Not assigned' ?></td>
                    </tr>
                </table>
                <div class="mt-3">
                    <a href="<?= site_url('backend/pages/students/edit/' . $student['id']) ?>" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit Student
                    </a>
                    <a href="<?= site_url('backend/pages/students/attendance/' . $student['id']) ?>" class="btn btn-info btn-sm">
                        <i class="bi bi-calendar-check"></i> Attendance History
                    </a>
                    <a href="<?= site_url('backend/pages/students/grades/' . $student['id']) ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-award"></i> Grades & Report Cards
                    </a>
                    <a href="<?= site_url('backend/pages/students') ?>" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Back to Students
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
