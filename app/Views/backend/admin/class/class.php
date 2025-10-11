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
                            <li class="breadcrumb-item active" aria-current="page">Classes</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12 d-flex justify-content-end align-items-center">
            <div class="d-flex gap-2">
                <button class="btn btn-success" onclick="showAddClassModal()">
                    <i class="icon-copy bi bi-plus-lg"></i> Add Class
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Alert Container -->
<div id="alertContainer"></div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-30">
        <div class="card-box pd-30 height-100-p">
            <div class="d-flex justify-content-between">
                <div class="h5 mb-0 text-primary" id="stat-total-classes"><?= count($classes) ?></div>
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
                <div class="h5 mb-0 text-success" id="stat-total-students"><?= array_sum(array_column($classes, 'student_count')) ?></div>
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
                <div class="h5 mb-0 text-info" id="stat-grade-levels"><?= count(array_unique(array_column($classes, 'grade_level'))) ?></div>
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
                <div class="h5 mb-0 text-warning" id="stat-capacity">
                    <?= count($classes) > 0 ? round((array_sum(array_column($classes, 'student_count')) / (count($classes) * 50)) * 100) : 0 ?>%
                </div>
                <div class="icon text-warning">
                    <i class="icon-copy bi bi-bar-chart" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="text-muted">Capacity Usage</div>
        </div>
    </div>
</div>

<!-- Classes Grid (Cards) -->
<div class="bg-white border rounded mb-4">
    <div class="p-3 border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Classes</h5>
            <div class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Search classes..." style="width: 220px;">
                <select class="form-control form-control-sm" id="gradeFilter" style="width: 140px;">
                    <option value="">All Grades</option>
                    <option value="Grade 7">Grade 7</option>
                    <option value="Grade 8">Grade 8</option>
                    <option value="Grade 9">Grade 9</option>
                    <option value="Grade 10">Grade 10</option>
                </select>
            </div>
        </div>
    </div>
    <div class="p-3">
        <div class="row" id="classesGrid"></div>
        <div id="noClasses" class="text-center text-muted py-5" style="display:none;">
            <div class="mb-2" style="font-size: 48px;"><i class="icon-copy bi bi-collection"></i></div>
            <div>No classes found</div>
        </div>
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
                                <label for="gradeId">Grade Level <span class="text-danger">*</span></label>
                                <select class="form-control" id="gradeId" name="grade_id" required>
                                    <option value="">Select Grade Level</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sectionId">Section <span class="text-danger">*</span></label>
                                <select class="form-control" id="sectionId" name="section_id" required>
                                    <option value="">Select Section</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subjectId">Subject <span class="text-danger">*</span></label>
                                <select class="form-control" id="subjectId" name="subject_id" required>
                                    <option value="">Select Subject</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="teacherId">Class Adviser</label>
                                <select class="form-control" id="teacherId" name="teacher_id">
                                    <option value="">Select Teacher</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="schoolYearId">School Year <span class="text-danger">*</span></label>
                                <select class="form-control" id="schoolYearId" name="school_year_id" required>
                                    <option value="">Select School Year</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="btn-text">Add Class</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
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
                                <label for="editGradeId">Grade Level <span class="text-danger">*</span></label>
                                <select class="form-control" id="editGradeId" name="grade_id" required>
                                    <option value="">Select Grade Level</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editSectionId">Section <span class="text-danger">*</span></label>
                                <select class="form-control" id="editSectionId" name="section_id" required>
                                    <option value="">Select Section</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editSubjectId">Subject <span class="text-danger">*</span></label>
                                <select class="form-control" id="editSubjectId" name="subject_id" required>
                                    <option value="">Select Subject</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editTeacherId">Class Adviser</label>
                                <select class="form-control" id="editTeacherId" name="teacher_id">
                                    <option value="">Select Teacher</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editSchoolYearId">School Year <span class="text-danger">*</span></label>
                                <select class="form-control" id="editSchoolYearId" name="school_year_id" required>
                                    <option value="">Select School Year</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="btn-text">Update Class</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
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
// Global variables
let classesData = <?= json_encode($classes) ?>;
const BASE_URL = '<?= base_url() ?>';
const SITE_URL = '<?= site_url() ?>';

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    renderClasses(classesData);
    loadFormDropdowns();
});

// Render classes grid
function renderClasses(list) {
    const classesGridEl = document.getElementById('classesGrid');
    const noClassesEl = document.getElementById('noClasses');
    
    classesGridEl.innerHTML = '';
    
    if (!list || list.length === 0) {
        noClassesEl.style.display = '';
        return;
    }
    
    noClassesEl.style.display = 'none';
    const fragment = document.createDocumentFragment();
    
    list.forEach(cls => {
        const badgeClass = getCapacityBadgeClass(cls.student_count || 0, cls.max_capacity || 50);
        const statusBadge = cls.status === 'active' 
            ? '<span class="badge badge-success small">Active</span>' 
            : '<span class="badge badge-secondary small">Inactive</span>';
        
        const col = document.createElement('div');
        col.className = 'col-12 col-sm-6 col-md-4 col-lg-3 mb-3';
        col.innerHTML = `
            <div class="card h-100 shadow-sm border-0 hover-shadow" style="transition: box-shadow .2s ease;">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="mb-1 text-primary">${escapeHtml(cls.class_name ?? '')}</h6>
                            <div class="text-muted small">Section: ${escapeHtml(cls.section ?? '')}</div>
                        </div>
                        ${statusBadge}
                    </div>
                    <div class="small mb-2">
                        <div><strong>Grade:</strong> ${escapeHtml(cls.grade_level ?? '')}</div>
                        <div><strong>Adviser:</strong> ${escapeHtml(cls.adviser ?? '—')}</div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge ${badgeClass} mr-2">${cls.student_count ?? 0}/${cls.max_capacity ?? 50}</span>
                        <span class="text-muted small">Students / Capacity</span>
                    </div>
                    <div class="mt-auto d-flex gap-2">
                        <button class="btn btn-outline-info btn-sm" onclick="viewStudents(${cls.id})">
                            <i class="bi bi-eye"></i> View
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="editClass(${cls.id})">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="deleteClass(${cls.id})">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>`;
        fragment.appendChild(col);
    });
    
    classesGridEl.appendChild(fragment);
}

// Helper function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Get capacity badge class
function getCapacityBadgeClass(studentCount, maxCapacity) {
    const percentage = (studentCount / Math.max(maxCapacity, 1)) * 100;
    if (percentage >= 90) return 'badge-danger';
    if (percentage >= 75) return 'badge-warning';
    return 'badge-success';
}

// Filter and render
function filterAndRender() {
    const search = (document.getElementById('searchInput').value || '').toLowerCase();
    const grade = document.getElementById('gradeFilter').value;
    
    const filtered = (classesData || []).filter(cls => {
        const className = (cls.class_name || '').toLowerCase();
        const section = (cls.section || '').toLowerCase();
        const adviser = (cls.adviser || '').toLowerCase();
        
        const gradeMatch = !grade || (cls.grade_level === grade);
        const searchMatch = !search || 
            className.includes(search) || 
            section.includes(search) || 
            adviser.includes(search);
        
        return gradeMatch && searchMatch;
    });
    
    renderClasses(filtered);
}

// Event listeners for filters
document.getElementById('searchInput').addEventListener('keyup', filterAndRender);
document.getElementById('gradeFilter').addEventListener('change', filterAndRender);

// Load form dropdowns
function loadFormDropdowns() {
    console.log('Loading form dropdowns...');
    fetch(`${SITE_URL}/admin/class/get-dropdowns`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Dropdown response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Dropdown data received:', data);
        if (data.success) {
            populateDropdowns(data.data);
        } else {
            console.error('Failed to load dropdowns:', data.message);
        }
    })
    .catch(error => console.error('Error loading dropdowns:', error));
}

// Populate dropdowns
function populateDropdowns(data) {
    // Helper function to clear and populate a select element
    function populateSelect(selectId, items, valueKey, textKey, defaultOption = true) {
        const select = document.getElementById(selectId);
        if (!select) {
            console.warn(`Select element with ID ${selectId} not found`);
            return;
        }
        
        // Clear existing options
        select.innerHTML = '';
        
        // Add default option
        if (defaultOption) {
            const defaultOpt = document.createElement('option');
            defaultOpt.value = '';
            defaultOpt.textContent = '-- Select --';
            select.appendChild(defaultOpt);
        }
        
        // Add new options
        if (items && items.length > 0) {
            items.forEach(item => {
                const option = document.createElement('option');
                option.value = item[valueKey];
                option.textContent = item[textKey];
                select.appendChild(option);
            });
            console.log(`Populated ${items.length} items for ${selectId}`);
        } else {
            console.warn(`No items to populate for ${selectId}`);
        }
    }
    
    // Grades
    const gradeSelects = ['gradeId', 'editGradeId'];
    gradeSelects.forEach(selectId => {
        populateSelect(selectId, data.grades, 'id', 'grade_name');
    });
    
    // Sections
    const sectionSelects = ['sectionId', 'editSectionId'];
    sectionSelects.forEach(selectId => {
        populateSelect(selectId, data.sections, 'id', 'section_name');
    });
    
    // Subjects
    const subjectSelects = ['subjectId', 'editSubjectId'];
    subjectSelects.forEach(selectId => {
        populateSelect(selectId, data.subjects, 'id', 'subject_name');
    });
    
    // School Years
    const schoolYearSelects = ['schoolYearId', 'editSchoolYearId'];
    schoolYearSelects.forEach(selectId => {
        populateSelect(selectId, data.school_years, 'id', 'school_year', false);
        
        // Set active school year as selected
        const select = document.getElementById(selectId);
        if (select && data.school_years) {
            const activeYear = data.school_years.find(year => year.is_active);
            if (activeYear) {
                select.value = activeYear.id;
            }
        }
    });
    
    // Teachers
    const teacherSelects = ['teacherId', 'editTeacherId'];
    teacherSelects.forEach(selectId => {
        populateSelect(selectId, data.teachers, 'id', 'full_name');
    });
}

// Show add class modal
function showAddClassModal() {
    document.getElementById('addClassForm').reset();
    $('#addClassModal').modal('show');
}

// Add class form submission
document.getElementById('addClassForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const btnText = submitBtn.querySelector('.btn-text');
    const spinner = submitBtn.querySelector('.spinner-border');
    
    submitBtn.disabled = true;
    btnText.classList.add('d-none');
    spinner.classList.remove('d-none');
    
    const formData = new FormData(this);
    
    fetch(`${SITE_URL}/admin/class/store`, {
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
            refreshClasses();
        } else {
            showAlert('Error', data.message || 'Failed to add class', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error', 'An error occurred while processing the request', 'danger');
    })
    .finally(() => {
        submitBtn.disabled = false;
        btnText.classList.remove('d-none');
        spinner.classList.add('d-none');
    });
});

// Edit class
function editClass(classId) {
    fetch(`${SITE_URL}/admin/class/get/${classId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const cls = data.data;
            document.getElementById('editClassId').value = cls.id;
            document.getElementById('editClassName').value = cls.class_name;
            document.getElementById('editGradeId').value = cls.grade_id;
            document.getElementById('editSectionId').value = cls.section_id;
            document.getElementById('editSubjectId').value = cls.subject_id;
            document.getElementById('editSchoolYearId').value = cls.school_year_id;
            document.getElementById('editTeacherId').value = cls.teacher_id || '';
            
            $('#editClassModal').modal('show');
        } else {
            showAlert('Error', data.message || 'Failed to load class data', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error', 'An error occurred while loading class data', 'danger');
    });
}

// Edit class form submission
document.getElementById('editClassForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const btnText = submitBtn.querySelector('.btn-text');
    const spinner = submitBtn.querySelector('.spinner-border');
    
    submitBtn.disabled = true;
    btnText.classList.add('d-none');
    spinner.classList.remove('d-none');
    
    const classId = document.getElementById('editClassId').value;
    const formData = new FormData(this);
    
    fetch(`${SITE_URL}/admin/class/update/${classId}`, {
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
            refreshClasses();
        } else {
            showAlert('Error', data.message || 'Failed to update class', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error', 'An error occurred while processing the request', 'danger');
    })
    .finally(() => {
        submitBtn.disabled = false;
        btnText.classList.remove('d-none');
        spinner.classList.add('d-none');
    });
});

// Delete class
function deleteClass(classId) {
    if (confirm('Are you sure you want to delete this class? This action cannot be undone.')) {
        fetch(`${SITE_URL}/admin/class/delete/${classId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Success', data.message, 'success');
                refreshClasses();
            } else {
                showAlert('Error', data.message || 'Failed to delete class', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error', 'An error occurred while processing the request', 'danger');
        });
    }
}

// View students
function viewStudents(classId) {
    $('#studentsModal').modal('show');
    
    document.getElementById('studentsModalContent').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-2">Loading students...</p>
        </div>
    `;
    
    fetch(`${SITE_URL}/admin/class/students/${classId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const cls = data.data.class;
            const students = data.data.students;
            
            let html = `
                <div class="mb-3">
                    <h6 class="text-primary">${escapeHtml(cls.class_name)}</h6>
                    <p class="mb-1"><strong>Grade:</strong> ${escapeHtml(cls.grade_level)} | <strong>Section:</strong> ${escapeHtml(cls.section)}</p>
                    <p class="mb-0"><strong>Adviser:</strong> ${escapeHtml(cls.adviser || '—')}</p>
                </div>
            `;
            
            if (students && students.length > 0) {
                html += `
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>LRN</th>
                                    <th>Student Name</th>
                                    <th>Enrollment Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>`;
                
                students.forEach((student, index) => {
                    const statusBadge = student.status === 'enrolled' 
                        ? '<span class="badge badge-success">Enrolled</span>'
                        : '<span class="badge badge-secondary">Inactive</span>';
                    
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${escapeHtml(student.lrn)}</td>
                            <td>${escapeHtml(student.student_name)}</td>
                            <td>${escapeHtml(student.enrollment_date || '—')}</td>
                            <td>${statusBadge}</td>
                        </tr>`;
                });
                
                html += `
                            </tbody>
                        </table>
                    </div>`;
            } else {
                html += `
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> No students enrolled in this class yet.
                    </div>`;
            }
            
            document.getElementById('studentsModalContent').innerHTML = html;
        } else {
            document.getElementById('studentsModalContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> ${data.message || 'Failed to load students'}
                </div>`;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('studentsModalContent').innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> An error occurred while loading students
            </div>`;
    });
}

// Refresh classes
function refreshClasses() {
    fetch(`${SITE_URL}/admin/class/get-classes`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            classesData = data.data;
            filterAndRender();
            updateStatistics();
            showAlert('Success', 'Classes refreshed successfully', 'success');
        } else {
            showAlert('Error', 'Failed to refresh classes', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error', 'An error occurred while refreshing', 'danger');
    });
}

// Update statistics
function updateStatistics() {
    const totalClasses = classesData.length;
    const totalStudents = classesData.reduce((sum, cls) => sum + (cls.student_count || 0), 0);
    const gradeLevels = new Set(classesData.map(cls => cls.grade_level)).size;
    const capacityUsage = totalClasses > 0 
        ? Math.round((totalStudents / (totalClasses * 50)) * 100) 
        : 0;
    
    document.getElementById('stat-total-classes').textContent = totalClasses;
    document.getElementById('stat-total-students').textContent = totalStudents;
    document.getElementById('stat-grade-levels').textContent = gradeLevels;
    document.getElementById('stat-capacity').textContent = capacityUsage + '%';
}

// Show alert
function showAlert(title, message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 
                      type === 'danger' ? 'alert-danger' : 
                      type === 'warning' ? 'alert-warning' : 'alert-info';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <strong>${title}:</strong> ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;
    
    const container = document.getElementById('alertContainer');
    container.innerHTML = alertHtml;
    
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}
</script>

<style>
.hover-shadow:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
}

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

.gap-2 {
    gap: 0.5rem;
}

.d-flex.gap-2 > * {
    margin-right: 0.5rem;
}

.d-flex.gap-2 > *:last-child {
    margin-right: 0;
}
</style>

<?= $this->endSection() ?>