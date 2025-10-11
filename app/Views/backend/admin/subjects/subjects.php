<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<!-- CSRF Token for AJAX requests -->
<input type="hidden" name="csrf_test_name" value="<?= csrf_hash() ?>" id="csrf_token">

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <div class="title">
            <h4>All Subjects</h4>
        </div>
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
    <div>
        <button class="btn btn-success btn-sm" onclick="showAddSubjectModal()">
            <i class="icon-copy bi bi-plus-lg"></i> Add Subject
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-30">
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-30">
        <div class="card-box height-100-p pd-20">
            <div class="d-flex justify-content-between">
                <div class="h5 mb-0 text-primary"><?= count($subjects) ?></div>
                <div class="icon text-primary">
                    <i class="dw dw-book" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="text-muted">Total Subjects</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-30">
        <div class="card-box height-100-p pd-20">
            <div class="d-flex justify-content-between">
                <div class="h5 mb-0 text-info"><?= count($grades ?? []) ?></div>
                <div class="icon text-info">
                    <i class="dw dw-layers" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="text-muted">Grade Levels</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-30">
        <div class="    card-box height-100-p pd-20">
            <div class="d-flex justify-content-between">
                <div class="h5 mb-0 text-success"><?= count($departments ?? []) ?></div>
                <div class="icon text-success">
                    <i class="dw dw-building" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="text-muted">Available Departments</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-30">
        <div class="card-box height-100-p pd-20">
            <div class="d-flex justify-content-between">
                <div class="h5 mb-0 text-warning"><?= count(array_filter($subjects ?? [], function($subject) { return !empty($subject['department_id']); })) ?></div>
                <div class="icon text-warning">
                    <i class="dw dw-link" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="text-muted">Subjects with Departments</div>
        </div>
    </div>
</div>

<!-- Subjects Table -->
<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4">Subjects List</h4>
    </div>
    
    <!-- Filter, Search Section -->
    <div class="pd-20 pt-0">
        <div class="row align-items-end">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Search Subjects:</label>
                    <div class="position-relative">
                        <input type="text" class="form-control pl-4" id="searchInput" placeholder="Search by subject name or code..." onkeyup="filterTable()">
                        <span style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #aaa;">
                            <i class="icon-copy bi bi-search"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Filter by Grade:</label>
                    <select class="form-control" id="gradeFilter" onchange="filterTable()">
                        <option value="">All Grades</option>
                        <?php if (!empty($grades)): ?>
                            <?php foreach ($grades as $grade): ?>
                                <option value="<?= esc($grade['grade_name']) ?>"><?= esc($grade['grade_name']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <div class="pb-20">
        <div class="table-responsive">
            <table class="table hover multiple-select-row data-table-export nowrap" id="subjectsTable">
                <thead>
                    <tr>
                        <th class="table-plus datatable-nosort">No.</th>
                        <th>Subject</th>
                        <th>Subject Code</th>
                        <th>Grade Level</th>
                        <th>Department</th>
                        <th class="datatable-nosort">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($subjects)): ?>
                        <?php foreach ($subjects as $index => $subject): ?>
                            <tr id="subject-row-<?= $subject['id'] ?>">
                                <td class="table-plus"><?= $index + 1 ?></td>
                                <td>
                                    <div class="weight-600"><?= esc($subject['subject_name']) ?></div>
                                    <div class="font-12 color-text-color-2"><?= esc($subject['subject_code'] ?? 'No code') ?></div>
                                </td>
                                <td><span class="badge badge-info"><?= esc($subject['subject_code'] ?? 'N/A') ?></span></td>
                                <td>
                                    <?php 
                                    $gradeLevel = $subject['grade_level'] ?? 'Unknown';
                                    $badgeClass = 'badge-primary';
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= esc($gradeLevel) ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($subject['department_name']) && $subject['department_name'] !== 'No Department'): ?>
                                        <span class="badge badge-success"><?= esc($subject['department_name']) ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">No Department</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                            <i class="dw dw-more"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                            <a class="dropdown-item" href="#" onclick="viewSubjectDetails(<?= $subject['id'] ?>)">
                                                <i class="dw dw-eye"></i> View Details
                                            </a>
                                            <a class="dropdown-item" href="#" onclick="editSubject(<?= $subject['id'] ?>)">
                                                <i class="dw dw-edit2"></i> Edit
                                            </a>
                                            <a class="dropdown-item text-danger" href="#" onclick="deleteSubject(<?= $subject['id'] ?>)">
                                                <i class="dw dw-delete-3"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="py-4"> 
                                    <i class="dw dw-book font-48 text-muted"></i> 
                                    <h5 class="text-muted mt-3">No subjects found</h5> 
                                    <p class="text-muted">Start by adding your first subject to the system.</p> 
                                    <button class="btn btn-success" onclick="showAddSubjectModal()"> 
                                        <i class="icon-copy bi bi-plus-lg"></i> Add First Subject 
                                    </button> 
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
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
                <?= csrf_field() ?>
                <div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="subjectName">Subject Name <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="subjectName" name="subject_name" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="subjectCode">Subject Code</label>
								<input type="text" class="form-control" id="subjectCode" name="subject_code" placeholder="e.g., MATH7">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="gradeId">Grade <span class="text-danger">*</span></label>
								<select class="form-control" id="gradeId" name="grade_id" required>
									<option value="">Select Grade</option>
									<?php if (!empty($grades)): ?>
										<?php foreach ($grades as $grade): ?>
											<option value="<?= $grade['id'] ?>">
												<?= esc($grade['grade_name']) ?>
												<?php if (!empty($grade['description'])): ?>
													- <?= esc($grade['description']) ?>
												<?php endif; ?>
											</option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</div>
						</div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="departmentId">Department</label>
                                    <select class="form-control" id="departmentId" name="department_id">
                                        <option value="">Select Department (Optional)</option>
                                        <?php if (!empty($departments)): ?>
                                            <?php foreach ($departments as $department): ?>
                                                <option value="<?= $department['id'] ?>">
                                                    <?= esc($department['department_name']) ?>
                                                    <?php if (!empty($department['description'])): ?>
                                                        - <?= esc($department['description']) ?>
                                                    <?php endif; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
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
                <?= csrf_field() ?>
                <input type="hidden" id="editSubjectId" name="subject_id">
                <div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="editSubjectName">Subject Name <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="editSubjectName" name="subject_name" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="editSubjectCode">Subject Code</label>
								<input type="text" class="form-control" id="editSubjectCode" name="subject_code">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="editGradeId">Grade <span class="text-danger">*</span></label>
								<select class="form-control" id="editGradeId" name="grade_id" required>
									<option value="">Select Grade</option>
									<?php if (!empty($grades)): ?>
										<?php foreach ($grades as $grade): ?>
											<option value="<?= $grade['id'] ?>">
												<?= esc($grade['grade_name']) ?>
												<?php if (!empty($grade['description'])): ?>
													- <?= esc($grade['description']) ?>
												<?php endif; ?>
											</option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="editDepartmentId">Department</label>
								<select class="form-control" id="editDepartmentId" name="department_id">
									<option value="">Select Department (Optional)</option>
									<?php if (!empty($departments)): ?>
										<?php foreach ($departments as $department): ?>
											<option value="<?= $department['id'] ?>">
												<?= esc($department['department_name']) ?>
												<?php if (!empty($department['description'])): ?>
													- <?= esc($department['description']) ?>
												<?php endif; ?>
											</option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</div>
						</div>
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
            // Get subject name and code from the simplified structure
            const subjectNameElement = row.cells[1].querySelector('.weight-600');
            const subjectCodeElement = row.cells[1].querySelector('.font-12');
            const subjectCodeBadge = row.cells[2].textContent.toLowerCase();
            const gradeLevel = row.cells[3].textContent;
            const department = row.cells[4].textContent.toLowerCase();
            
            const subjectName = subjectNameElement ? subjectNameElement.textContent.toLowerCase() : '';
            const subjectCode = subjectCodeElement ? subjectCodeElement.textContent.toLowerCase() : '';
            
            const matchesSearch = subjectName.includes(searchTerm) || 
                                subjectCode.includes(searchTerm) ||
                                subjectCodeBadge.includes(searchTerm) ||
                                department.includes(searchTerm);
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
    // Reset form
    document.getElementById('addSubjectForm').reset();
}

function editSubject(subjectId) {
    // Get CSRF token
    const csrfToken = document.getElementById('csrf_token').value;
    
    fetch(`<?= site_url('admin/subjects/get/') ?>${subjectId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const subject = data.data;
            document.getElementById('editSubjectId').value = subject.id;
            document.getElementById('editSubjectName').value = subject.subject_name;
            document.getElementById('editSubjectCode').value = subject.subject_code;
            document.getElementById('editGradeId').value = subject.grade_id;
            document.getElementById('editDepartmentId').value = subject.department_id || '';
            $('#editSubjectModal').modal('show');
        } else {
            showAlert('Error', data.message || 'Failed to load subject data', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error', 'An error occurred while loading data', 'error');
    });
}

function deleteSubject(subjectId) {
    if (confirm('Are you sure you want to delete this subject?')) {
        // Get CSRF token
        const csrfToken = document.getElementById('csrf_token').value;
        
        fetch(`<?= site_url('admin/subjects/delete/') ?>${subjectId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                csrf_test_name: csrfToken
            })
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
/* Table styling to match teacher.php */
.table th {
    border-top: none;
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6c757d;
}

.table td {
    vertical-align: middle;
    font-size: 14px;
}

.table-plus {
    font-weight: 600;
    color: #6c757d;
}

.name-avatar .avatar {
    width: 40px;
    height: 40px;
    border-radius: 100%;
    background: #ebf3ff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 600;
    color: #2c9aff;
}

.name-avatar .txt .weight-600 {
    font-weight: 600;
    color: #333;
    margin-bottom: 2px;
}

.name-avatar .txt .font-12 {
    font-size: 12px;
    color: #6c757d;
}

.badge {
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 4px;
}

.badge-info {
    background-color: #17a2b8;
    color: white;
}

.badge-primary {
    background-color: #007bff;
    color: white;
}

.badge-success {
    background-color: #28a745;
    color: white;
}

.badge-secondary {
    background-color: #6c757d;
    color: white;
}

.dropdown-toggle::after {
    display: none;
}

.dropdown-menu-icon-list .dropdown-item {
    padding: 8px 16px;
    font-size: 14px;
}

.dropdown-menu-icon-list .dropdown-item i {
    margin-right: 8px;
    width: 16px;
    display: inline-block;
}

/* Ensure no duplicate icons */
.dropdown-menu-icon-list .dropdown-item i::before,
.dropdown-menu-icon-list .dropdown-item i::after {
    display: none;
}

.dropdown-menu-icon-list .dropdown-item {
    position: relative;
}

/* Remove any potential pseudo-element icons */
.dropdown-menu-icon-list .dropdown-item::before,
.dropdown-menu-icon-list .dropdown-item::after {
    display: none !important;
}

.card-box {
    border-radius: 8px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    background: white;
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

/* Page header styling */
.page-header {
    margin-bottom: 30px;
}

.page-header .title h4 {
    font-size: 24px;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.breadcrumb {
    background: none;
    padding: 0;
    margin: 0;
}

.breadcrumb-item a {
    color: #6c757d;
    text-decoration: none;
}

.breadcrumb-item.active {
    color: #333;
}

/* Statistics cards */
.card-box.height-100-p {
    height: 100%;
}

.card-box .h5 {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 0;
}

.card-box .icon {
    font-size: 24px;
    opacity: 0.8;
}

/* Form styling */
.form-group label {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.form-control {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 8px 12px;
}

.form-control:focus {
    border-color: #2c9aff;
    box-shadow: 0 0 0 0.2rem rgba(44, 154, 255, 0.25);
}

/* Search input styling */
.position-relative .form-control {
    padding-right: 40px;
}

.position-relative span {
    pointer-events: none;
}
</style>

<?= $this->endSection() ?>