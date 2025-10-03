<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header bg-white rounded-3 p-4 mb-4" style="box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
    <div class="row align-items-center">
        <div class="col-md-6 col-sm-12">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <h4 class="text-primary fw-bold mb-1">Subjects Management</h4>
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?= route_to('admin.home') ?>" class="text-decoration-none">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Subjects
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12 d-flex justify-content-end align-items-center">
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" onclick="refreshSubjects()">
                    <i class="icon-copy bi bi-arrow-clockwise"></i> Refresh
                </button>
                <button class="btn btn-success" onclick="showAddSubjectModal()">
                    <i class="icon-copy bi bi-plus-lg"></i> Add Subject
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
                <div class="h5 mb-0 text-primary"><?= count($subjects) ?></div>
                <div class="icon text-primary">
                    <i class="icon-copy bi bi-book" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="text-muted">Total Subjects</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-30">
        <div class="card-box pd-30 height-100-p">
            <div class="d-flex justify-content-between">
                <div class="h5 mb-0 text-success"><?= count(array_filter($subjects, function($s) { return $s['status'] === 'active'; })) ?></div>
                <div class="icon text-success">
                    <i class="icon-copy bi bi-check-circle" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="text-muted">Active Subjects</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-30">
        <div class="card-box pd-30 height-100-p">
            <div class="d-flex justify-content-between">
                <div class="h5 mb-0 text-info">4</div>
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
                <div class="h5 mb-0 text-warning"><?= array_sum(array_column($subjects, 'units')) ?></div>
                <div class="icon text-warning">
                    <i class="icon-copy bi bi-clock" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="text-muted">Total Units</div>
        </div>
    </div>
</div>

<!-- Subjects Table -->
<div class="bg-white border rounded mb-4">
    <div class="p-3 border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Subjects</h5>
            <div class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Search subjects..." style="width: 200px;">
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
        <table class="table table-sm mb-0" id="subjectsTable">
            <thead class="bg-light">
                <tr>
                    <th class="border-0 text-muted small">#</th>
                    <th class="border-0 text-muted small">Subject Code</th>
                    <th class="border-0 text-muted small">Subject Name</th>
                    <th class="border-0 text-muted small">Grade Level</th>
                    <th class="border-0 text-muted small">Units</th>
                    <th class="border-0 text-muted small">Teacher</th>
                    <th class="border-0 text-muted small">Status</th>
                    <th class="border-0 text-muted small">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($subjects)): ?>
                    <?php foreach ($subjects as $index => $subject): ?>
                        <tr id="subject-row-<?= $subject['id'] ?>" class="border-bottom">
                            <td class="text-muted small"><?= $index + 1 ?></td>
                            <td><code class="small"><?= esc($subject['subject_code']) ?></code></td>
                            <td>
                                <div class="font-weight-medium"><?= esc($subject['subject_name']) ?></div>
                                <div class="text-muted small"><?= esc($subject['description']) ?></div>
                            </td>
                            <td class="small"><?= esc($subject['grade_level']) ?></td>
                            <td class="small"><?= $subject['units'] ?> units</td>
                            <td class="small"><?= esc($subject['teacher_assigned']) ?></td>
                            <td>
                                <?php if ($subject['status'] === 'active'): ?>
                                    <span class="badge badge-success small">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary small">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary btn-sm" 
                                            onclick="editSubject(<?= $subject['id'] ?>)" 
                                            title="Edit">
                                        ✎
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" 
                                            onclick="deleteSubject(<?= $subject['id'] ?>)" 
                                            title="Delete">
                                        ✕
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" 
                                            onclick="viewSubjectDetails(<?= $subject['id'] ?>)" 
                                            title="View">
                                        ⋯
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <div>No subjects found</div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" role="dialog" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubjectModalLabel">Add New Subject</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addSubjectForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subjectCode">Subject Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="subjectCode" name="subject_code" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subjectName">Subject Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="subjectName" name="subject_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="units">Units <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="units" name="units" min="1" max="5" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="teacherAssigned">Teacher Assigned</label>
                        <input type="text" class="form-control" id="teacherAssigned" name="teacher_assigned">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Subject Modal -->
<div class="modal fade" id="editSubjectModal" tabindex="-1" role="dialog" aria-labelledby="editSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSubjectModalLabel">Edit Subject</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editSubjectForm">
                <input type="hidden" id="editSubjectId" name="subject_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editSubjectCode">Subject Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editSubjectCode" name="subject_code" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editSubjectName">Subject Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editSubjectName" name="subject_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editUnits">Units <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="editUnits" name="units" min="1" max="5" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editDescription">Description</label>
                        <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="editTeacherAssigned">Teacher Assigned</label>
                        <input type="text" class="form-control" id="editTeacherAssigned" name="teacher_assigned">
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
                    <button type="submit" class="btn btn-primary">Update Subject</button>
                </div>
            </form>
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
    const table = document.getElementById('subjectsTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        if (row.cells.length > 1) {
            const subjectCode = row.cells[1].textContent.toLowerCase();
            const subjectName = row.cells[2].textContent.toLowerCase();
            const gradeLevel = row.cells[3].textContent;
            const teacher = row.cells[5].textContent.toLowerCase();
            
            const matchesSearch = subjectCode.includes(searchTerm) || 
                                subjectName.includes(searchTerm) || 
                                teacher.includes(searchTerm);
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
function showAddSubjectModal() {
    $('#addSubjectModal').modal('show');
}

function editSubject(subjectId) {
    // Load subject data and show edit modal
    // This would typically fetch data from the server
    $('#editSubjectModal').modal('show');
}

function deleteSubject(subjectId) {
    if (confirm('Are you sure you want to delete this subject?')) {
        fetch(`<?= site_url('admin/subjects/delete/') ?>${subjectId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const row = document.getElementById(`subject-row-${subjectId}`);
                if (row) {
                    row.remove();
                }
                showAlert('Success', data.message, 'success');
            } else {
                showAlert('Error', data.message || 'Failed to delete subject', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error', 'An error occurred while processing the request', 'error');
        });
    }
}

function viewSubjectDetails(subjectId) {
    // Show subject details
    alert('View subject details for ID: ' + subjectId);
}

function refreshSubjects() {
    location.reload();
}

// Form submissions
document.getElementById('addSubjectForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('<?= site_url('admin/subjects/store') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#addSubjectModal').modal('hide');
            showAlert('Success', data.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert('Error', data.message || 'Failed to add subject', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error', 'An error occurred while processing the request', 'error');
    });
});

document.getElementById('editSubjectForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const subjectId = document.getElementById('editSubjectId').value;
    
    fetch(`<?= site_url('admin/subjects/update/') ?>${subjectId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#editSubjectModal').modal('hide');
            showAlert('Success', data.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert('Error', data.message || 'Failed to update subject', 'error');
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

.modal-lg {
    max-width: 800px;
}

code {
    background-color: #f8f9fa;
    color: #6c757d;
    padding: 2px 4px;
    border-radius: 3px;
    font-size: 12px;
}
</style>

<?= $this->endSection() ?>
