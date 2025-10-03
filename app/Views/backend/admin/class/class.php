<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header bg-white rounded-3 p-4 mb-4" style="box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
    <div class="row align-items-center">
        <div class="col-md-6 col-sm-12">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <h4 class="text-primary fw-bold mb-1">Class Management</h4>
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?= route_to('admin.home') ?>" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Classes
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12 d-flex justify-content-end align-items-center">
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" onclick="refreshClasses()">
                    <i class="icon-copy bi bi-arrow-clockwise"></i> Refresh
                </button>
                <button class="btn btn-success" onclick="showAddClassModal()">
                    <i class="icon-copy bi bi-plus-lg"></i> Add Class
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-30">
        <div class="card-box pd-30 height-100-p">
            <div class="d-flex justify-content-between">
                <div class="h5 mb-0 text-primary"><?= count($classes) ?></div>
                <div class="icon text-primary">
                    <i class="icon-copy bi bi-house-door" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="text-muted">Total Classes</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-30">
        <div class="card-box pd-30 height-100-p">
            <div class="d-flex justify-content-between">
                <div class="h5 mb-0 text-success"><?= array_sum(array_column($classes, 'student_count')) ?></div>
                <div class="icon text-success">
                    <i class="icon-copy bi bi-people" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="text-muted">Total Students</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-30">
        <div class="card-box pd-30 height-100-p">
            <div class="d-flex justify-content-between">
                <div class="h5 mb-0 text-info"><?= count(array_unique(array_column($classes, 'grade_level'))) ?></div>
                <div class="icon text-info">
                    <i class="icon-copy bi bi-layers" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="text-muted">Grade Levels</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-30">
        <div class="card-box pd-30 height-100-p">
            <div class="d-flex justify-content-between">
                <div class="h5 mb-0 text-warning"><?= round((array_sum(array_column($classes, 'student_count')) / array_sum(array_column($classes, 'max_capacity'))) * 100) ?>%</div>
                <div class="icon text-warning">
                    <i class="icon-copy bi bi-bar-chart" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="text-muted">Capacity Usage</div>
        </div>
    </div>
</div>

<!-- Classes Table -->
<div class="bg-white border rounded mb-4">
    <div class="p-3 border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Classes</h5>
            <div class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Search classes..." style="width: 200px;">
                <select class="form-control form-control-sm" id="gradeFilter" style="width: 120px;">
                    <option value="">All Grades</option>
                    <option value="Grade 7">Grade 7</option>
                    <option value="Grade 8">Grade 8</option>
                    <option value="Grade 9">Grade 9</option>
                    <option value="Grade 10">Grade 10</option>
                </select>
            </div>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-sm mb-0" id="classesTable">
            <thead class="bg-light">
                <tr>
                    <th class="border-0 text-muted small">#</th>
                    <th class="border-0 text-muted small">Class Name</th>
                    <th class="border-0 text-muted small">Grade Level</th>
                    <th class="border-0 text-muted small">Section</th>
                    <th class="border-0 text-muted small">Adviser</th>
                    <th class="border-0 text-muted small">Room</th>
                    <th class="border-0 text-muted small">Students</th>
                    <th class="border-0 text-muted small">Capacity</th>
                    <th class="border-0 text-muted small">Status</th>
                    <th class="border-0 text-muted small">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($classes)): ?>
                    <?php foreach ($classes as $index => $class): ?>
                        <tr id="class-row-<?= $class['id'] ?>" class="border-bottom">
                            <td class="text-muted small"><?= $index + 1 ?></td>
                            <td>
                                <div class="font-weight-medium"><?= esc($class['class_name']) ?></div>
                                <div class="text-muted small"><?= esc($class['schedule']) ?></div>
                            </td>
                            <td class="small"><?= esc($class['grade_level']) ?></td>
                            <td class="small"><?= esc($class['section']) ?></td>
                            <td class="small"><?= esc($class['adviser']) ?></td>
                            <td class="small"><?= esc($class['room_number']) ?></td>
                            <td class="small">
                                <span class="badge badge-info"><?= $class['student_count'] ?></span>
                            </td>
                            <td class="small">
                                <?php 
                                $percentage = ($class['student_count'] / $class['max_capacity']) * 100;
                                $badgeClass = $percentage >= 90 ? 'badge-danger' : ($percentage >= 75 ? 'badge-warning' : 'badge-success');
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= $class['student_count'] ?>/<?= $class['max_capacity'] ?></span>
                            </td>
                            <td>
                                <?php if ($class['status'] === 'active'): ?>
                                    <span class="badge badge-success small">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary small">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary btn-sm" 
                                            onclick="editClass(<?= $class['id'] ?>)" 
                                            title="Edit">
                                        ✎
                                    </button>
                                    <button class="btn btn-outline-info btn-sm" 
                                            onclick="viewStudents(<?= $class['id'] ?>)" 
                                            title="View Students">
                                        👥
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" 
                                            onclick="deleteClass(<?= $class['id'] ?>)" 
                                            title="Delete">
                                        ✕
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <div>No classes found</div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Class Modal -->
<div class="modal fade" id="addClassModal" tabindex="-1" role="dialog" aria-labelledby="addClassModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addClassModalLabel">Add New Class</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addClassForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="className">Class Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="className" name="class_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gradeLevel">Grade Level <span class="text-danger">*</span></label>
                                <select class="form-control" id="gradeLevel" name="grade_level" required>
                                    <option value="">Select Grade Level</option>
                                    <option value="Grade 7">Grade 7</option>
                                    <option value="Grade 8">Grade 8</option>
                                    <option value="Grade 9">Grade 9</option>
                                    <option value="Grade 10">Grade 10</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="section">Section <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="section" name="section" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="adviser">Class Adviser <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="adviser" name="adviser" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="roomNumber">Room Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="roomNumber" name="room_number" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="maxCapacity">Max Capacity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="maxCapacity" name="max_capacity" min="1" max="50" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="schedule">Schedule</label>
                        <input type="text" class="form-control" id="schedule" name="schedule" placeholder="e.g., Monday - Friday, 7:30 AM - 4:30 PM">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Class</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Class Modal -->
<div class="modal fade" id="editClassModal" tabindex="-1" role="dialog" aria-labelledby="editClassModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editClassModalLabel">Edit Class</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editClassForm">
                <input type="hidden" id="editClassId" name="class_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editClassName">Class Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editClassName" name="class_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editGradeLevel">Grade Level <span class="text-danger">*</span></label>
                                <select class="form-control" id="editGradeLevel" name="grade_level" required>
                                    <option value="">Select Grade Level</option>
                                    <option value="Grade 7">Grade 7</option>
                                    <option value="Grade 8">Grade 8</option>
                                    <option value="Grade 9">Grade 9</option>
                                    <option value="Grade 10">Grade 10</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editSection">Section <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editSection" name="section" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editAdviser">Class Adviser <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editAdviser" name="adviser" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editRoomNumber">Room Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editRoomNumber" name="room_number" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editMaxCapacity">Max Capacity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="editMaxCapacity" name="max_capacity" min="1" max="50" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editSchedule">Schedule</label>
                        <input type="text" class="form-control" id="editSchedule" name="schedule" placeholder="e.g., Monday - Friday, 7:30 AM - 4:30 PM">
                    </div>
                    <div class="form-group">
                        <label for="editStatus">Status</label>
                        <select class="form-control" id="editStatus" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Class</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Students Modal -->
<div class="modal fade" id="studentsModal" tabindex="-1" role="dialog" aria-labelledby="studentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentsModalLabel">Class Students</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="studentsModalContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    filterTable();
});

document.getElementById('gradeFilter').addEventListener('change', function() {
    filterTable();
});

function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const gradeFilter = document.getElementById('gradeFilter').value;
    const table = document.getElementById('classesTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        if (row.cells.length > 1) {
            const className = row.cells[1].textContent.toLowerCase();
            const gradeLevel = row.cells[2].textContent;
            const section = row.cells[3].textContent.toLowerCase();
            const adviser = row.cells[4].textContent.toLowerCase();
            
            const matchesSearch = className.includes(searchTerm) || 
                                section.includes(searchTerm) || 
                                adviser.includes(searchTerm);
            const matchesGrade = gradeFilter === '' || gradeLevel === gradeFilter;
            
            if (matchesSearch && matchesGrade) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }
}

// Modal functions
function showAddClassModal() {
    $('#addClassModal').modal('show');
}

function editClass(classId) {
    // Load class data and show edit modal
    $('#editClassModal').modal('show');
}

function deleteClass(classId) {
    if (confirm('Are you sure you want to delete this class?')) {
        fetch(`<?= site_url('admin/class/delete/') ?>${classId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const row = document.getElementById(`class-row-${classId}`);
                if (row) {
                    row.remove();
                }
                showAlert('Success', data.message, 'success');
            } else {
                showAlert('Error', data.message || 'Failed to delete class', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error', 'An error occurred while processing the request', 'error');
        });
    }
}

function viewStudents(classId) {
    // Show students modal with class students
    $('#studentsModal').modal('show');
    
    document.getElementById('studentsModalContent').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-2">Loading students...</p>
        </div>
    `;
    
    // Simulate loading students
    setTimeout(() => {
        document.getElementById('studentsModalContent').innerHTML = `
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>LRN</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>John Doe</td>
                            <td>123456789012</td>
                            <td><span class="badge badge-success">Active</span></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Jane Smith</td>
                            <td>123456789013</td>
                            <td><span class="badge badge-success">Active</span></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-center text-muted">... and more students</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
    }, 1000);
}

function refreshClasses() {
    location.reload();
}

// Form submissions
document.getElementById('addClassForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('<?= site_url('admin/class/store') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#addClassModal').modal('hide');
            showAlert('Success', data.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert('Error', data.message || 'Failed to add class', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error', 'An error occurred while processing the request', 'error');
    });
});

document.getElementById('editClassForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const classId = document.getElementById('editClassId').value;
    
    fetch(`<?= site_url('admin/class/update/') ?>${classId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#editClassModal').modal('hide');
            showAlert('Success', data.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert('Error', data.message || 'Failed to update class', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error', 'An error occurred while processing the request', 'error');
    });
});

function showAlert(title, message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 
                      type === 'error' ? 'alert-danger' : 
                      type === 'warning' ? 'alert-warning' : 'alert-info';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <strong>${title}:</strong> ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;
    
    const container = document.querySelector('.page-header');
    container.insertAdjacentHTML('afterend', alertHtml);
    
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}
</script>

<style>
.table th {
    border-top: none;
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
    font-size: 14px;
}

.badge {
    font-size: 11px;
    padding: 4px 8px;
}

.btn-sm {
    padding: 4px 8px;
    font-size: 12px;
}

.card-box {
    border-radius: 8px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}

.modal-xl {
    max-width: 1200px;
}

.modal-lg {
    max-width: 800px;
}
</style>

<?= $this->endSection() ?>