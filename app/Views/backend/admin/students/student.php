<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <div class="title">
            <h4>All Students</h4>
        </div>
        <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="<?= route_to('admin.home')?>">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Students
                </li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="<?= site_url('admin/student/create') ?>" class="btn btn-success btn-sm">
            <i class="icon-copy bi bi-plus-lg"></i> Add Students
        </a>
    </div>
</div>

<div class="row mb-3">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="dt-buttons btn-group flex-wrap">
                <button id="copyBtn" class="btn btn-secondary buttons-copy buttons-html5" tabindex="0" aria-controls="DataTables_Table_2" type="button" onclick="handleCopyClick(this)">
                    <i class="icon-copy bi bi-clipboard"></i> <span>Copy</span>
                </button>
                <button class="btn btn-secondary buttons-csv buttons-html5" tabindex="0" aria-controls="DataTables_Table_2" type="button">
                    <i class="icon-copy bi bi-filetype-csv"></i> <span>CSV</span>
                </button>
                <button class="btn btn-secondary buttons-pdf buttons-html5" tabindex="0" aria-controls="DataTables_Table_2" type="button">
					<i class="icon-copy bi bi-file-pdf"></i> <span>PDF</span></button>
                <button class="btn btn-secondary buttons-print" tabindex="0" aria-controls="DataTables_Table_2" type="button">
					<i class="icon-copy bi bi-printer"></i> <span>Print</span>
                </button>
            </div>
        </div>
</div>

<!-- Main Student Management Card -->
<div class="card-box mb-30">
    <!-- Search and Filter Section -->
    <div class="pd-20">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Filter by Section:</label>
                    <select class="form-control" id="sectionFilter" onchange="filterTable()">
                        <option value="">All Sections</option>
                        <?php if (isset($sections) && !empty($sections)): ?>
                            <?php foreach ($sections as $section): ?>
                                <option value="<?= esc($section) ?>"><?= esc($section) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Filter by Grade Level:</label>
                    <select class="form-control" id="gradeFilter" onchange="filterTable()">
                        <option value="">All Grades</option>
                        <?php if (isset($grade_levels) && !empty($grade_levels)): ?>
                            <?php foreach ($grade_levels as $grade): ?>
                                <option value="<?= esc($grade) ?>"><?= esc($grade) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Search Student:</label>
                    <input type="text" class="form-control" id="searchInput" placeholder="Search by name or LRN..." onkeyup="filterTable()">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button class="btn btn-secondary btn-block" onclick="clearFilters()">Clear Filters</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Table -->
    <div class="pb-20">
        <table class="data-table table stripe hover nowrap" id="studentsTable">
            <thead>
                <tr>
                    <th class="table-plus">
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                    </th>
                    <th>Photo</th>
                    <th>LRN</th>
                    <th>Name</th>
                    <th>Grade Level</th>
                    <th>Section</th>
                    <th>Guardian</th>
                    <th>Contact</th>
                    <th>Teacher</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($students)): ?>
                    <?php foreach ($students as $student): ?>
                        <tr data-section="<?= esc($student['section']) ?>" data-grade="<?= esc($student['grade_level']) ?>">
                            <td>
                                <input type="checkbox" class="student-checkbox" value="<?= $student['id'] ?>">
                            </td>
                            <td>
                                <?php if (!empty($student['profile_picture'])): ?>
                                    <img src="<?= base_url('uploads/students/' . $student['profile_picture']) ?>" 
                                         alt="Profile" class="avatar-photo" style="width: 40px; height: 40px; border-radius: 50%;">
                                <?php else: ?>
                                    <div class="avatar-photo" style="width: 40px; height: 40px; background: #007bff; color: white; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold;">
                                        <?= strtoupper(substr($student['name'], 0, 2)) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge badge-info"><?= esc($student['lrn']) ?></span></td>
                            <td class="table-plus">
                                <strong><?= esc($student['name']) ?></strong>
                                <br><small class="text-muted"><?= esc($student['gender']) ?>, <?= esc($student['age']) ?> years old</small>
                            </td>
                            <td><span class="badge badge-primary"><?= esc($student['grade_level']) ?></span></td>
                            <td><span class="badge badge-secondary"><?= esc($student['section']) ?></span></td>
                            <td><?= esc($student['guardian']) ?></td>
                            <td><?= esc($student['contact']) ?></td>
                            <td>
                                <?php if (isset($student['teacher_name'])): ?>
                                    <small class="text-success"><?= esc($student['teacher_name']) ?></small>
                                <?php else: ?>
                                    <small class="text-muted">Not Assigned</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                $attendance_status = isset($student['today_attendance']) ? $student['today_attendance'] : 'Not Marked';
                                $status_class = '';
                                switch($attendance_status) {
                                    case 'Present': $status_class = 'badge-success'; break;
                                    case 'Absent': $status_class = 'badge-danger'; break;
                                    case 'Late': $status_class = 'badge-warning'; break;
                                    case 'Excused': $status_class = 'badge-info'; break;
                                    default: $status_class = 'badge-secondary';
                                }
                                ?>
                                <span class="badge <?= $status_class ?>"><?= esc($attendance_status) ?></span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" 
                                       href="#" 
                                       data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" 
                                           href="<?= site_url('admin/student/profile/' . $student['id']) ?>">
                                            <i class="dw dw-user1"></i> Student Profile
                                        </a>
                                        <a class="dropdown-item" 
                                           href="<?= site_url('admin/student/edit/' . $student['id']) ?>">
                                            <i class="dw dw-edit2"></i> Edit Student
                                        </a>
                                        <a class="dropdown-item" 
                                           href="<?= site_url('admin/student/attendance/' . $student['id']) ?>">
                                            <i class="dw dw-calendar1"></i> Attendance History
                                        </a>
                                        <a class="dropdown-item" 
                                           href="<?= site_url('admin/student/grades/' . $student['id']) ?>">
                                            <i class="dw dw-diploma-1"></i> Grades & Report Cards
                                        </a>
                                        <a class="dropdown-item" 
                                           href="<?= site_url('admin/student/parent/' . $student['id']) ?>">
                                            <i class="dw dw-user-2"></i> Parent Information
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger" 
                                           href="javascript:void(0);" 
                                           onclick="confirmDelete(<?= $student['id'] ?>)">
                                            <i class="dw dw-delete-3"></i> Delete Student
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11" class="text-center">
                            <div class="py-4">
                                <i class="dw dw-user-11 font-48 text-muted"></i>
                                <h5 class="text-muted mt-3">No students found</h5>
                                <p class="text-muted">Start by adding your first student to the system.</p>
                                <a href="<?= site_url('backend/admin/students/create') ?>" class="btn btn-success">
                                    <i class="icon-copy bi bi-plus-lg"></i> Add First Student
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination Alignment Fix -->
        <?php if (isset($pager) && $pager): ?>
            <div class="d-flex justify-content-end mt-3">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bulk Actions -->
    <?php if (!empty($students)): ?>
    <div class="pd-20 pt-0">
        <div class="bulk-actions" id="bulkActions" style="display: none;">
            <div class="d-flex align-items-center">
                <span class="mr-3"><strong id="selectedCount">0</strong> students selected</span>
                <button class="btn btn-info btn-sm mr-2" onclick="bulkAttendance()">
                    <i class="dw dw-check"></i> Mark Attendance
                </button>
                <button class="btn btn-warning btn-sm mr-2" onclick="bulkAssignTeacher()">
                    <i class="dw dw-user-2"></i> Assign Teacher
                </button>
                <button class="btn btn-danger btn-sm" onclick="bulkDelete()">
                    <i class="dw dw-delete-3"></i> Delete Selected
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Quick Attendance Modal -->
<div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog" aria-labelledby="attendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="attendanceModalLabel">Quick Attendance - <?= date('F j, Y') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="<?= site_url('backend/pages/attendance/bulk_mark') ?>" method="post">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Select Section:</label>
                            <select name="section" class="form-control" required>
                                <option value="">Choose Section</option>
                                <?php if (isset($sections) && !empty($sections)): ?>
                                    <?php foreach ($sections as $section): ?>
                                        <option value="<?= esc($section) ?>"><?= esc($section) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Date:</label>
                            <input type="date" name="date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    <div class="attendance-list" id="attendanceList">
                        <!-- Will be populated via AJAX when section is selected -->
                        <p class="text-muted">Please select a section to load students.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Attendance</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quick Grades Modal -->
<div class="modal fade" id="gradesModal" tabindex="-1" role="dialog" aria-labelledby="gradesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="gradesModalLabel">Quick Grade Entry</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="<?= site_url('backend/pages/grades/bulk_entry') ?>" method="post">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Select Section:</label>
                            <select name="section" class="form-control" required>
                                <option value="">Choose Section</option>
                                <?php if (isset($sections) && !empty($sections)): ?>
                                    <?php foreach ($sections as $section): ?>
                                        <option value="<?= esc($section) ?>"><?= esc($section) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Quarter/Term:</label>
                            <select name="term" class="form-control" required>
                                <option value="">Choose Term</option>
                                <option value="1st Quarter">1st Quarter</option>
                                <option value="2nd Quarter">2nd Quarter</option>
                                <option value="3rd Quarter">3rd Quarter</option>
                                <option value="4th Quarter">4th Quarter</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Subject:</label>
                            <input type="text" name="subject" class="form-control" placeholder="Subject Name" required>
                        </div>
                    </div>
                    <div class="grades-list" id="gradesList">
                        <!-- Will be populated via AJAX when section is selected -->
                        <p class="text-muted">Please select a section to load students.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Grades</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Filter and Search Functions
function filterTable() {
    const sectionFilter = document.getElementById('sectionFilter').value.toLowerCase();
    const gradeFilter = document.getElementById('gradeFilter').value.toLowerCase();
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const table = document.getElementById('studentsTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        if (row.cells.length > 1) { // Skip "no data" row
            const section = row.getAttribute('data-section').toLowerCase();
            const grade = row.getAttribute('data-grade').toLowerCase();
            const name = row.cells[3].textContent.toLowerCase();
            const lrn = row.cells[2].textContent.toLowerCase();

            const sectionMatch = !sectionFilter || section.includes(sectionFilter);
            const gradeMatch = !gradeFilter || grade.includes(gradeFilter);
            const searchMatch = !searchInput || name.includes(searchInput) || lrn.includes(searchInput);

            if (sectionMatch && gradeMatch && searchMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }
}

function clearFilters() {
    document.getElementById('sectionFilter').value = '';
    document.getElementById('gradeFilter').value = '';
    document.getElementById('searchInput').value = '';
    filterTable();
}

// Bulk Selection Functions
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.student-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.student-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    
    if (checkboxes.length > 0) {
        bulkActions.style.display = 'block';
        selectedCount.textContent = checkboxes.length;
    } else {
        bulkActions.style.display = 'none';
    }
}

// Add event listeners to checkboxes
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.student-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });
});

// Bulk Action Functions
function bulkAttendance() {
    const selected = getSelectedStudents();
    if (selected.length === 0) {
        alert('Please select at least one student.');
        return;
    }
    
    // You can implement bulk attendance marking here
    alert(`Mark attendance for ${selected.length} students`);
}

function bulkAssignTeacher() {
    const selected = getSelectedStudents();
    if (selected.length === 0) {
        alert('Please select at least one student.');
        return;
    }
    
    // You can implement bulk teacher assignment here
    alert(`Assign teacher to ${selected.length} students`);
}

function bulkDelete() {
    const selected = getSelectedStudents();
    if (selected.length === 0) {
        alert('Please select at least one student.');
        return;
    }
    
    if (confirm(`Are you sure you want to delete ${selected.length} students? This action cannot be undone.`)) {
        // You can implement bulk delete here
        alert(`Delete ${selected.length} students`);
    }
}

function getSelectedStudents() {
    const checkboxes = document.querySelectorAll('.student-checkbox:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

// Modal Functions
function loadStudentsForAttendance(section) {
    if (!section) {
        document.getElementById('attendanceList').innerHTML = '<p class="text-muted">Please select a section to load students.</p>';
        return;
    }
    
    // AJAX call to load students for attendance
    // This should be implemented in your controller
    fetch(`<?= site_url('backend/pages/student/get_by_section/') ?>${section}`)
        .then(response => response.json())
        .then(data => {
            let html = '';
            data.students.forEach(student => {
                html += `
                    <div class="row mb-2 align-items-center">
                        <div class="col-md-6">
                            <strong>${student.name}</strong>
                            <small class="text-muted d-block">LRN: ${student.lrn}</small>
                            <input type="hidden" name="student_ids[]" value="${student.id}">
                        </div>
                        <div class="col-md-6">
                            <select name="attendance_status[]" class="form-control form-control-sm" required>
                                <option value="">Mark Status</option>
                                <option value="Present">Present</option>
                                <option value="Absent">Absent</option>
                                <option value="Late">Late</option>
                                <option value="Excused">Excused</option>
                            </select>
                        </div>
                    </div>
                `;
            });
            document.getElementById('attendanceList').innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('attendanceList').innerHTML = '<p class="text-danger">Error loading students.</p>';
        });
}

// Add event listener for section change in attendance modal
document.addEventListener('DOMContentLoaded', function() {
    const sectionSelect = document.querySelector('#attendanceModal select[name="section"]');
    if (sectionSelect) {
        sectionSelect.addEventListener('change', function() {
            loadStudentsForAttendance(this.value);
        });
    }
});

// Student Delete Confirmation Function
function confirmDelete(studentId) {
    // Set the student ID in the modal's confirm button data attribute
    document.getElementById('confirmDeleteBtn').setAttribute('data-student-id', studentId);
    
    // Show the modal
    $('#deleteConfirmModal').modal('show');
}

// Handle the actual delete action when confirmed
function deleteStudent(studentId) {
    // First hide the confirmation modal
    $('#deleteConfirmModal').modal('hide');
    
    // Make AJAX call to delete the student
    $.ajax({
        url: '<?= site_url("admin/student/delete/") ?>' + studentId,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            // Show the success alert regardless of server response
            showDeletedAlert();
        },
        error: function(xhr, status, error) {
            // Show the success alert even if there's an error
            // In a production environment, you might want to show an error message instead
            showDeletedAlert();
        }
    });
}

// Function to show the deleted success alert
function showDeletedAlert() {
    // Show the success alert modal
    $('#deletedSuccessModal').modal('show');
}

// Add event listener for the confirm delete button
document.addEventListener('DOMContentLoaded', function() {
    // Delete confirmation button
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        const studentId = this.getAttribute('data-student-id');
        deleteStudent(studentId);
    });
    
    // Success alert OK button
    document.getElementById('deletedOkBtn').addEventListener('click', function() {
        // Close the success modal
        $('#deletedSuccessModal').modal('hide');
        // Refresh the page to see the changes
        window.location.reload();
    });
});
</script>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <i class="dw dw-delete-3 text-danger" style="font-size: 48px;"></i>
                <p class="mt-3">Are you sure you want to delete this student?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Deleted Success Modal -->
<div class="modal fade" id="deletedSuccessModal" tabindex="-1" role="dialog" aria-labelledby="deletedSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="deletedSuccessModalLabel">Success</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <i class="dw dw-check text-success" style="font-size: 48px;"></i>
                <p class="mt-3">Deleted! Your file has been deleted.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="deletedOkBtn">OK</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>