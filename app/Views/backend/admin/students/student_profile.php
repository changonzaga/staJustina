<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<?php
// Fetch additional normalized student-related data to display a complete profile
$db = \Config\Database::connect();
$studentId = $student['id'] ?? null;

// Personal info
$personal = null;
if ($db->query("SHOW TABLES LIKE 'student_personal_info'")->getNumRows() > 0 && $studentId) {
    $personal = $db->table('student_personal_info')
        ->where('student_id', $studentId)
        ->get()->getRowArray();
}

// Addresses (current and permanent) – support both singular and plural table names
$addressCurrent = null;
$addressPermanent = null;
foreach (['student_address', 'student_addresses'] as $addrTable) {
    if ($db->query("SHOW TABLES LIKE '{$addrTable}'")->getNumRows() > 0 && $studentId) {
        $addressCurrent = $db->table($addrTable)
            ->where('student_id', $studentId)
            ->where('address_type', 'current')
            ->get()->getRowArray();
        $addressPermanent = $db->table($addrTable)
            ->where('student_id', $studentId)
            ->where('address_type', 'permanent')
            ->get()->getRowArray();
        break;
    }
}

// Parents/Guardians – support alternate table names
$father = $mother = $guardian = null;
foreach (['student_family_info', 'student_parents_guardians'] as $famTable) {
    if ($db->query("SHOW TABLES LIKE '{$famTable}'")->getNumRows() > 0 && $studentId) {
        $father = $db->table($famTable)
            ->where('student_id', $studentId)
            ->where('relationship_type', 'father')
            ->get()->getRowArray();
        $mother = $db->table($famTable)
            ->where('student_id', $studentId)
            ->where('relationship_type', 'mother')
            ->get()->getRowArray();
        $guardian = $db->table($famTable)
            ->where('student_id', $studentId)
            ->where('relationship_type', 'guardian')
            ->get()->getRowArray();
        break;
    }
}

// Academic history – prefer student-level table, fallback to enrollment-based tables
$academic = null;
if ($db->query("SHOW TABLES LIKE 'student_academic_history'")->getNumRows() > 0 && $studentId) {
    $academic = $db->table('student_academic_history')
        ->where('student_id', $studentId)
        ->get()->getRowArray();
} elseif (!empty($student['enrollment_id'])) {
    foreach (['enrollment_academic_history_new', 'enrollment_academic_history', 'enrollment_academic_info'] as $enTable) {
        if ($db->query("SHOW TABLES LIKE '{$enTable}'")->getNumRows() > 0) {
            $academic = $db->table($enTable)
                ->where('enrollment_id', $student['enrollment_id'])
                ->get()->getRowArray();
            break;
        }
    }
}

// SHS details (if available)
$shs = null;
if (!empty($student['enrollment_id']) && $db->query("SHOW TABLES LIKE 'enrollment_shs_details'")->getNumRows() > 0) {
    $shs = $db->table('enrollment_shs_details')
        ->where('enrollment_id', $student['enrollment_id'])
        ->get()->getRowArray();
}

// Special categories (if present)
$special = null;
if ($db->query("SHOW TABLES LIKE 'student_special_categories'")->getNumRows() > 0 && $studentId) {
    $special = $db->table('student_special_categories')
        ->where('student_id', $studentId)
        ->get()->getRowArray();
}

// Student auth (email/username)
$auth = null;
if ($db->query("SHOW TABLES LIKE 'student_auth'")->getNumRows() > 0 && $studentId) {
    $auth = $db->table('student_auth')
        ->where('student_id', $studentId)
        ->get()->getRowArray();
}

// Emergency contacts
$emergencyContacts = [];
if ($db->query("SHOW TABLES LIKE 'student_emergency_contacts'")->getNumRows() > 0 && $studentId) {
    $emergencyContacts = $db->table('student_emergency_contacts')
        ->where('student_id', $studentId)
        ->get()->getResultArray();
}

// Helper to format address
function formatAddress($addr)
{
    if (!$addr || !is_array($addr)) return null;
    $parts = [];
    foreach (['house_no', 'street', 'barangay', 'municipality', 'province'] as $k) {
        if (!empty($addr[$k])) $parts[] = $addr[$k];
    }
    return implode(', ', $parts);
}
?>

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
                    <?php $pp = $student['profile_picture'];
                    $ppPath = (strpos($pp, 'uploads/') === 0) ? $pp : 'uploads/students/' . $pp; ?>
                    <img src="<?= base_url($ppPath) ?>"
                        alt="Profile" class="avatar-photo" style="width: 150px; height: 150px; border-radius: 50%;">
                <?php else: ?>
                    <div class="avatar-photo" style="width: 150px; height: 150px; margin: 0 auto; background: #007bff; color: white; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 48px; font-weight: bold;">
                        <?= strtoupper(substr(($student['name'] ?? ''), 0, 1) . substr(($student['name'] ?? ''), -1)) ?>
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
                        <?= esc(($personal['gender'] ?? $student['gender'] ?? 'Not provided')) ?>
                    </li>
                    <li>
                        <span>Age:</span>
                        <?php
                        $ageText = 'Not provided';
                        if (!empty($student['age'])) {
                            $ageText = esc($student['age']) . ' years old';
                        } elseif (!empty($personal['date_of_birth'])) {
                            $dob = new DateTime($personal['date_of_birth']);
                            $now = new DateTime();
                            $ageText = $dob->diff($now)->y . ' years old';
                        }
                        echo $ageText;
                        ?>
                    </li>
                    <li>
                        <span>Grade Level:</span>
                        <?= esc($student['grade_level'] ?? 'Not provided') ?>
                    </li>
                    <li>
                        <span>Section:</span>
                        <?= esc($student['section'] ?? 'Not provided') ?>
                    </li>
                    <li>
                        <span>Email:</span>
                        <?= esc($auth['email'] ?? $student['student_email'] ?? 'Not provided') ?>
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
                    <ul class="nav nav-tabs customtab d-flex flex-row flex-wrap" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active px-2 py-2" data-toggle="tab" href="#personal" role="tab">
                                Personal Information
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-2 py-2" data-toggle="tab" href="#academic" role="tab">
                                Academic Information
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-2 py-2" data-toggle="tab" href="#attendance" role="tab">
                                Academic Performance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-2 py-2" data-toggle="tab" href="#history" role="tab">
                                Academic History
                            </a>
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
                                                <td>
                                                    <?php
                                                    $addr = formatAddress($addressCurrent ?? []);
                                                    echo $addr ? esc($addr) : 'Not provided';
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Guardian</th>
                                                <td>
                                                    <?php
                                                    $guardianName = null;
                                                    if (!empty($guardian)) {
                                                        $guardianName = trim(($guardian['first_name'] ?? '') . ' ' . ($guardian['last_name'] ?? ''));
                                                    }
                                                    echo $guardianName ? esc($guardianName) : 'Not provided';
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Contact Number</th>
                                                <td>
                                                    <?= esc($guardian['contact_number'] ?? $student['contact'] ?? '') ?: 'Not provided' ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Parent</th>
                                                <td>
                                                    <?php
                                                    $parentDisplay = null;
                                                    $fatherName = trim(($father['first_name'] ?? '') . ' ' . ($father['last_name'] ?? ''));
                                                    $motherName = trim(($mother['first_name'] ?? '') . ' ' . ($mother['last_name'] ?? ''));
                                                    if ($fatherName) $parentDisplay = 'Father: ' . $fatherName . ' (' . ($father['contact_number'] ?? 'N/A') . ')';
                                                    if ($motherName) {
                                                        $parentDisplay = ($parentDisplay ? $parentDisplay . '; ' : '') . 'Mother: ' . $motherName . ' (' . ($mother['contact_number'] ?? 'N/A') . ')';
                                                    }
                                                    echo $parentDisplay ?: (isset($student['parent_name']) ? esc($student['parent_name']) : 'Not assigned');
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Permanent Address</th>
                                                <td>
                                                    <?php
                                                    $permAddr = formatAddress($addressPermanent ?? []);
                                                    echo $permAddr ? esc($permAddr) : 'Not provided';
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Date of Birth</th>
                                                <td><?= esc($personal['date_of_birth'] ?? 'Not provided') ?></td>
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
                                            <?php if (!empty($academic) || !empty($shs)): ?>
                                                <tr>
                                                    <th>Previous GWA</th>
                                                    <td><?= esc($academic['previous_gwa'] ?? 'Not provided') ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Performance Level</th>
                                                    <td><?= esc($academic['performance_level'] ?? 'Not provided') ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Last Grade Completed</th>
                                                    <td><?= esc($academic['last_grade_completed'] ?? 'Not provided') ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Last School Year</th>
                                                    <td><?= esc($academic['last_school_year'] ?? 'Not provided') ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Last School Attended</th>
                                                    <td><?= esc($academic['last_school_attended'] ?? 'Not provided') ?></td>
                                                </tr>
                                                <tr>
                                                    <th>School ID</th>
                                                    <td><?= esc($academic['school_id'] ?? $academic['previous_school_id'] ?? 'Not provided') ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Track</th>
                                                    <td><?= esc($shs['track'] ?? 'Not provided') ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Strand</th>
                                                    <td><?= esc($shs['strand'] ?? 'Not provided') ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Semester</th>
                                                    <td><?= esc($shs['semester'] ?? 'Not provided') ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Attendance Tab -->
                        <div class="tab-pane fade" id="attendance" role="tabpanel">
                            <div class="pd-20">
                                <div class="profile-timeline">

                                    <!-- Academic Records FIRST -->
                                    <div class="timeline-title">Academic Records</div>
                                    <div class="profile-details mb-4">
                                        <a href="<?= site_url('admin/student/grades/' . $student['id']) ?>" class="btn btn-primary btn-sm">
                                            <i class="bi bi-award"></i> View Grades & Report Cards
                                        </a>
                                    </div>

                                    <!-- Attendance Records SECOND -->
                                    <div class="timeline-title">Attendance Records</div>
                                    <div class="profile-details">
                                        <?php if (!empty($special)): ?>
                                            <div class="mb-3">
                                                <strong>Special Categories:</strong>
                                                <div>
                                                    Indigenous People: <?= esc($special['indigenous_people'] ?? 'No') ?>
                                                </div>
                                                <div>
                                                    4Ps Beneficiary: <?= esc($special['fourps_beneficiary'] ?? 'No') ?>
                                                </div>
                                                <?php if (!empty($special['fourps_household_id'])): ?>
                                                    <div>4Ps Household ID: <?= esc($special['fourps_household_id']) ?></div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!empty($emergencyContacts)): ?>
                                            <div class="mb-3">
                                                <strong>Emergency Contacts:</strong>
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Relationship</th>
                                                            <th>Contact</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($emergencyContacts as $ec): ?>
                                                            <tr>
                                                                <td><?= esc(($ec['name'] ?? (($ec['first_name'] ?? '') . ' ' . ($ec['last_name'] ?? '')))) ?></td>
                                                                <td><?= esc($ec['relationship'] ?? 'N/A') ?></td>
                                                                <td><?= esc($ec['contact_number'] ?? 'N/A') ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php endif; ?>

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


                        <!-- Academic History Tab -->
                        <div class="tab-pane fade" id="history" role="tabpanel">
                            <div class="pd-20">
                                <div class="profile-timeline">
                                    <div class="timeline-title">Academic History</div>
                                    <div class="profile-details">
                                        <?php if (!empty($academic) || !empty($shs)): ?>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th width="30%">Previous GWA</th>
                                                    <td><?= esc($academic['previous_gwa'] ?? 'Not provided') ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Performance Level</th>
                                                    <td><?= esc($academic['performance_level'] ?? 'Not provided') ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Last Grade Completed</th>
                                                    <td><?= esc($academic['last_grade_completed'] ?? 'Not provided') ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Last School Year</th>
                                                    <td><?= esc($academic['last_school_year'] ?? 'Not provided') ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Last School Attended</th>
                                                    <td><?= esc($academic['last_school_attended'] ?? 'Not provided') ?></td>
                                                </tr>
                                                <tr>
                                                    <th>School ID</th>
                                                    <td><?= esc($academic['school_id'] ?? $academic['previous_school_id'] ?? 'Not provided') ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Track</th>
                                                    <td><?= esc($shs['track'] ?? 'Not provided') ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Strand</th>
                                                    <td><?= esc($shs['strand'] ?? 'Not provided') ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Semester</th>
                                                    <td><?= esc($shs['semester'] ?? 'Not provided') ?></td>
                                                </tr>
                                            </table>
                                        <?php else: ?>
                                            <div class="alert alert-info">No academic history records found.</div>
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