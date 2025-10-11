<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Page Header -->
<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Sections Management</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('admin/home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sections</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 d-flex justify-content-end align-items-center">
            <div class="d-flex gap-2">
                <button class="btn btn-success" onclick="showAddSectionModal()">
                    <i class="icon-copy bi bi-plus-lg"></i> Add Section
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Alert Container -->
<div id="alertContainer"></div>

<!-- Sections Table -->
<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4">Sections List</h4>
    </div>
    <!-- Filter, Search Section -->
    <div class="pd-20 pt-0">
        <div class="row align-items-end">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Search Sections:</label>
                    <div class="position-relative">
                        <input type="text" class="form-control pl-4" id="searchInput" placeholder="Search by section name..." onkeyup="filterTable()">
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
            <table class="table hover multiple-select-row data-table-export nowrap" id="sectionsTable">
                <thead>
                    <tr>
                        <th class="table-plus datatable-nosort">No.</th>
                        <th>Section</th>
                        <th>Grade Level</th>
                        <th>Capacity</th>
                        <th>School Year</th>
                        <th>Adviser</th>
                        <th class="datatable-nosort">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($error)): ?>
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="py-4">
                                    <i class="dw dw-error font-48 text-danger"></i>
                                    <h5 class="text-danger mt-3">Database Error</h5>
                                    <p class="text-muted"><?= esc($error) ?></p>
                                    <div class="mt-3">
                                        <p class="text-muted">To fix this issue, please run the following SQL command in your database:</p>
                                        <div class="bg-light p-3 rounded">
                                            <code>
                                                CREATE TABLE `sections` (<br>
                                                &nbsp;&nbsp;`id` int(11) unsigned NOT NULL AUTO_INCREMENT,<br>
                                                &nbsp;&nbsp;`grade_id` int(11) unsigned NOT NULL,<br>
                                                &nbsp;&nbsp;`section_name` varchar(100) NOT NULL,<br>
                                                &nbsp;&nbsp;`capacity` int(3) DEFAULT 40,<br>
                                                &nbsp;&nbsp;`school_year` varchar(10) NOT NULL,<br>
                                                &nbsp;&nbsp;`adviser_id` int(11) unsigned DEFAULT NULL,<br>
                                                &nbsp;&nbsp;`created_at` timestamp DEFAULT CURRENT_TIMESTAMP,<br>
                                                &nbsp;&nbsp;`updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,<br>
                                                &nbsp;&nbsp;PRIMARY KEY (`id`)<br>
                                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
                                            </code>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php elseif (!empty($sections)): ?>
                        <?php foreach ($sections as $index => $section): ?>
                            <tr id="section-row-<?= $section['id'] ?>">
                                <td class="table-plus"><?= $index + 1 ?></td>
                                <td>
                                    <div class="weight-600"><?= esc($section['section_name']) ?></div>
                                </td>
                                <td>
                                    <?php
                                    $gradeLevel = $section['grade_level'] ?? 'Unknown';
                                    $badgeClass = 'badge-primary';
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= esc($gradeLevel) ?></span>
                                </td>
                                <td>
                                    <span class="badge badge-info"><?= esc($section['capacity']) ?> students</span>
                                </td>
                                <td>
                                    <span class="badge badge-success"><?= esc($section['school_year']) ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($section['adviser_name'])): ?>
                                        <div class="weight-600 font-14"><?= esc($section['adviser_name']) ?></div>
                                    <?php else: ?>
                                        <span class="text-muted">No adviser assigned</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                            <i class="dw dw-more"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                            <a class="dropdown-item" href="#" onclick="viewSectionDetails(<?= $section['id'] ?>)">
                                                <i class="dw dw-eye"></i> View Details
                                            </a>
                                            <a class="dropdown-item" href="#" onclick="editSection(<?= $section['id'] ?>)">
                                                <i class="dw dw-edit2"></i> Edit
                                            </a>
                                            <a class="dropdown-item text-danger" href="#" onclick="deleteSection(<?= $section['id'] ?>)">
                                                <i class="dw dw-delete-3"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="py-4">
                                    <i class="dw dw-collection font-48 text-muted"></i>
                                    <h5 class="text-muted mt-3">No sections found</h5>
                                    <p class="text-muted">Start by adding your first section to the system.</p>
                                    <button class="btn btn-success" onclick="showAddSectionModal()">
                                        <i class="icon-copy bi bi-plus-lg"></i> Add First Section
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

<!-- Add Section Modal -->
<div class="modal fade" id="addSectionModal" tabindex="-1" role="dialog" aria-labelledby="addSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSectionModalLabel">Add New Section</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addSectionForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_grade_id">Grade Level <span class="text-danger">*</span></label>
                                <select class="form-control" id="add_grade_id" name="grade_id" required>
                                    <option value="">Select Grade Level</option>
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
                                <label for="add_section_name">Section Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="add_section_name" name="section_name" placeholder="e.g., Section A" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_capacity">Capacity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="add_capacity" name="capacity" placeholder="40" min="1" max="99" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_school_year">School Year <span class="text-danger">*</span></label>
                                <select class="form-control" id="add_school_year" name="school_year" required>
                                    <option value="">Select School Year</option>
                                    <option value="2024-2025">2024-2025</option>
                                    <option value="2023-2024">2023-2024</option>
                                    <option value="2022-2023">2022-2023</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="add_adviser_id">Adviser</label>
                                <select class="form-control" id="add_adviser_id" name="adviser_id">
                                    <option value="">Select Adviser (Optional)</option>
                                    <?php if (!empty($teachers)): ?>
                                        <?php foreach ($teachers as $teacher): ?>
                                            <option value="<?= $teacher['id'] ?>"><?= esc($teacher['full_name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="icon-copy bi bi-plus-lg"></i> Add Section
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Section Modal -->
<div class="modal fade" id="editSectionModal" tabindex="-1" role="dialog" aria-labelledby="editSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSectionModalLabel">Edit Section</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editSectionForm">
                <input type="hidden" id="edit_section_id" name="section_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_grade_id">Grade Level <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_grade_id" name="grade_id" required>
                                    <option value="">Select Grade Level</option>
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
                                <label for="edit_section_name">Section Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_section_name" name="section_name" placeholder="e.g., Section A" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_capacity">Capacity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="edit_capacity" name="capacity" placeholder="40" min="1" max="99" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_school_year">School Year <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_school_year" name="school_year" required>
                                    <option value="">Select School Year</option>
                                    <option value="2024-2025">2024-2025</option>
                                    <option value="2023-2024">2023-2024</option>
                                    <option value="2022-2023">2022-2023</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="edit_adviser_id">Adviser</label>
                                <select class="form-control" id="edit_adviser_id" name="adviser_id">
                                    <option value="">Select Adviser (Optional)</option>
                                    <?php if (!empty($teachers)): ?>
                                        <?php foreach ($teachers as $teacher): ?>
                                            <option value="<?= $teacher['id'] ?>"><?= esc($teacher['full_name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-copy bi bi-check-lg"></i> Update Section
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Section Details Modal -->
<div class="modal fade" id="viewSectionModal" tabindex="-1" role="dialog" aria-labelledby="viewSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewSectionModalLabel">Section Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="viewSectionContent">
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
let sectionsData = <?= json_encode($sections ?? []) ?>;
let gradesData = <?= json_encode($grades ?? []) ?>;
let teachersData = <?= json_encode($teachers ?? []) ?>;

// Show add section modal
function showAddSectionModal() {
    $('#addSectionForm')[0].reset();
    $('#addSectionModal').modal('show');
}

// Show edit section modal
function editSection(sectionId) {
    // Find section data
    const section = sectionsData.find(s => s.id == sectionId);
    if (!section) {
        showAlert('Section not found', 'error');
        return;
    }

    // Populate form
    $('#edit_section_id').val(section.id);
    $('#edit_grade_id').val(section.grade_id);
    $('#edit_section_name').val(section.section_name);
    $('#edit_capacity').val(section.capacity);
    $('#edit_school_year').val(section.school_year);
    $('#edit_adviser_id').val(section.adviser_id || '');

    $('#editSectionModal').modal('show');
}

// View section details
function viewSectionDetails(sectionId) {
    const section = sectionsData.find(s => s.id == sectionId);
    if (!section) {
        showAlert('Section not found', 'error');
        return;
    }

    const content = `
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label><strong>Section Name:</strong></label>
                    <p>${section.section_name}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><strong>Grade Level:</strong></label>
                    <p><span class="badge badge-primary">${section.grade_level || 'Unknown'}</span></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label><strong>Capacity:</strong></label>
                    <p><span class="badge badge-info">${section.capacity} students</span></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><strong>School Year:</strong></label>
                    <p><span class="badge badge-success">${section.school_year}</span></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label><strong>Adviser:</strong></label>
                    <p>${section.adviser_name || 'No adviser assigned'}</p>
                </div>
            </div>
        </div>
    `;

    $('#viewSectionContent').html(content);
    $('#viewSectionModal').modal('show');
}

// Delete section
function deleteSection(sectionId) {
    if (confirm('Are you sure you want to delete this section? This action cannot be undone.')) {
        fetch(`<?= base_url('admin/section/delete') ?>/${sectionId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                // Remove row from table
                $(`#section-row-${sectionId}`).fadeOut(300, function() {
                    $(this).remove();
                });
            } else {
                showAlert(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while deleting the section', 'error');
        });
    }
}

// Filter table
function filterTable() {
    const searchTerm = $('#searchInput').val().toLowerCase();
    const gradeFilter = $('#gradeFilter').val().toLowerCase();
    
    $('#sectionsTable tbody tr').each(function() {
        const sectionName = $(this).find('td:eq(1)').text().toLowerCase();
        const gradeLevel = $(this).find('td:eq(2)').text().toLowerCase();
        
        const matchesSearch = sectionName.includes(searchTerm);
        const matchesGrade = !gradeFilter || gradeLevel.includes(gradeFilter);
        
        if (matchesSearch && matchesGrade) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

// Add section form submission
$('#addSectionForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('<?= site_url('admin/section/store') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            $('#addSectionModal').modal('hide');
            // Reload page to show new section
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showAlert(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred while adding the section', 'error');
    });
});

// Edit section form submission
$('#editSectionForm').on('submit', function(e) {
    e.preventDefault();
    
    const sectionId = $('#edit_section_id').val();
    const formData = new FormData(this);
    
    fetch(`<?= base_url('admin/section/update') ?>/${sectionId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            $('#editSectionModal').modal('hide');
            // Reload page to show updated section
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showAlert(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred while updating the section', 'error');
    });
});

// Alert function
function showAlert(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;
    
    $('#alertContainer').html(alertHtml);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 5000);
}

// Initialize page
$(document).ready(function() {
    // Any initialization code can go here
});
</script>

<style>
/* Custom styles to match other admin pages */
.card-box {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

.page-header {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
    padding: 20px;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #495057;
    border-top: none;
}

.name-avatar {
    display: flex;
    align-items: center;
}

.name-avatar .avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #007bff;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 10px;
}

.badge {
    font-size: 0.75em;
    padding: 0.375rem 0.75rem;
}

.dropdown-menu-icon-list .dropdown-item {
    padding: 0.5rem 1rem;
    display: flex;
    align-items: center;
}

.dropdown-menu-icon-list .dropdown-item i {
    margin-right: 0.5rem;
    width: 16px;
}

.dropdown-menu-icon-list .dropdown-item:hover {
    background-color: #f8f9fa;
}

.dropdown-menu-icon-list .dropdown-item.text-danger:hover {
    background-color: #f8d7da;
    color: #721c24;
}

/* Hide duplicate icons */
.dropdown-menu-icon-list .dropdown-item::before,
.dropdown-menu-icon-list .dropdown-item::after {
    display: none !important;
}

/* Modal styles */
.modal-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.modal-title {
    color: #495057;
    font-weight: 600;
}

/* Form styles */
.form-group label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control {
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 0.5rem 0.75rem;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Button styles */
.btn {
    border-radius: 4px;
    font-weight: 500;
    padding: 0.5rem 1rem;
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0069d9;
    border-color: #0062cc;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
}

/* Alert styles */
.alert {
    border-radius: 4px;
    margin-bottom: 1rem;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

/* Empty state styles */
.py-4 {
    padding: 2rem 0;
}

.font-48 {
    font-size: 3rem;
}

.text-muted {
    color: #6c757d !important;
}

/* Search input styles */
.position-relative .form-control {
    padding-right: 2.5rem;
}

.position-relative span {
    pointer-events: none;
}
</style>

<?= $this->endSection() ?>
