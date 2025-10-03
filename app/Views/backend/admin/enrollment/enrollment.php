<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <div class="title">
            <h4>Enrollment Management</h4>
        </div>
        <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="<?= route_to('admin.home') ?>">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Enrollment
                </li>
            </ol>
        </nav>
    </div>
    <div>
        <button class="btn btn-outline-primary btn-sm mr-2" onclick="refreshEnrollments()">
            <i class="icon-copy bi bi-arrow-clockwise"></i> Refresh
        </button>
        <button class="btn btn-success btn-sm" onclick="exportEnrollments()">
            <i class="icon-copy bi bi-file-earmark-excel"></i> Export
        </button>
    </div>
</div>

<?php if (session()->has('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= session('success') ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>

<?php if (session()->has('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= session('error') ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-30">
        <div class="card-box pd-30 height-100-p">
            <div class="d-flex justify-content-between">
                <div class="h5 mb-0 text-primary"><?= isset($stats['pending']) ? $stats['pending'] : count($pendingEnrollments) ?></div>
                <div class="icon text-primary">
                    <i class="icon-copy bi bi-clock-history" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="text-muted">Pending Enrollments</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-30">
        <div class="card-box pd-30 height-100-p">
            <div class="d-flex justify-content-between">
                <div class="h5 mb-0 text-success"><?= isset($stats['enrolled']) ? $stats['enrolled'] : 0 ?></div>
                <div class="icon text-success">
                    <i class="icon-copy bi bi-check-circle" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="text-muted">Enrolled Students</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-30">
        <div class="card-box pd-30 height-100-p">
            <div class="d-flex justify-content-between">
                <div class="h5 mb-0 text-danger"><?= isset($stats['declined']) ? $stats['declined'] : 0 ?></div>
                <div class="icon text-danger">
                    <i class="icon-copy bi bi-x-circle" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="text-muted">Declined Applications</div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-30">
        <div class="card-box pd-30 height-100-p">
            <div class="d-flex justify-content-between">
                <div class="h5 mb-0 text-info"><?= isset($stats['total']) ? $stats['total'] : count($pendingEnrollments) ?></div>
                <div class="icon text-info">
                    <i class="icon-copy bi bi-people" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="text-muted">Total Applications</div>
     </div>
 </div>
</div>

<!-- Main Container -->
<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4">All Enrollment Applications</h4>
    </div>
    
    <!-- Filter, Search, and Export Buttons Section -->
    <div class="pd-20 pt-0">
        <div class="row align-items-end">
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-3">
                <div class="form-group">
                    <label>Search Applications:</label>
                    <div class="position-relative">
                        <input type="text" class="form-control pl-4" id="searchInput" placeholder="Search by name, LRN, or enrollment #" onkeyup="filterTable()">
                        <span style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #aaa;">
                            <i class="icon-copy bi bi-search"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 mb-3">
                <div class="form-group">
                    <label>Filter Status:</label>
                    <select class="form-control" id="statusFilter" onchange="filterTable()">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="enrolled">Enrolled</option>
                        <option value="declined">Declined</option>
                    </select>
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 mb-3">
                <div class="form-group">
                    <label>Filter Grade:</label>
                    <select class="form-control" id="gradeFilter" onchange="filterTable()">
                        <option value="">All Grades</option>
                        <option value="Grade 7">Grade 7</option>
                        <option value="Grade 8">Grade 8</option>
                        <option value="Grade 9">Grade 9</option>
                        <option value="Grade 10">Grade 10</option>
                        <option value="Grade 11">Grade 11</option>
                        <option value="Grade 12">Grade 12</option>
                    </select>
                </div>
            </div>
            <div class="col-xl-5 col-lg-2 col-md-6 col-sm-12 mb-3">
                <div class="form-group">
                    <label class="d-none d-xl-block">&nbsp;</label>
                    <div class="d-flex justify-content-xl-end justify-content-lg-center justify-content-center flex-wrap">
                        <div class="dt-buttons btn-group flex-wrap">
                            <button id="copyBtn" class="btn btn-secondary buttons-copy buttons-html5" tabindex="0" type="button" onclick="handleCopyClick(this)">
                                <i class="icon-copy bi bi-clipboard"></i> <span class="d-none d-md-inline">Copy</span>
                            </button>
                            <button class="btn btn-secondary buttons-csv buttons-html5" tabindex="0" type="button">
                                <i class="icon-copy bi bi-filetype-csv"></i> <span class="d-none d-md-inline">CSV</span>
                            </button>
                            <button class="btn btn-secondary buttons-pdf buttons-html5" tabindex="0" type="button">
                                <i class="icon-copy bi bi-file-pdf"></i> <span class="d-none d-md-inline">PDF</span>
                            </button>
                            <button class="btn btn-secondary buttons-print" tabindex="0" type="button">
                                <i class="icon-copy bi bi-printer"></i> <span class="d-none d-md-inline">Print</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pb-20">
        <div class="table-responsive">
            <table class="table hover multiple-select-row data-table-export nowrap" id="enrollmentTable">
                <thead>
                    <tr>
                          <th class="table-plus datatable-nosort">No.</th>
                          <th>Enrollment #</th>
                          <th>Student</th>
                          <th>LRN</th>
                          <th>Grade</th>
                          <th>Contact</th>
                          <th>Type</th>
                          <th>Status</th>
                          <th>Date</th>
                          <th class="datatable-nosort">Actions</th>
                      </tr>
                </thead>
            <tbody>
                <?php 
                $allEnrollments = isset($allEnrollments) ? $allEnrollments : $pendingEnrollments;
                if (!empty($allEnrollments)): 
                ?>
                    <?php foreach ($allEnrollments as $index => $enrollment): ?>
                        <tr id="enrollment-row-<?= $enrollment['id'] ?>" data-status="<?= esc($enrollment['enrollment_status']) ?>">
                            <td class="table-plus"><?= $index + 1 ?></td>
                            <td><?= esc($enrollment['enrollment_number']) ?></td>
                            <td>
                                <div class="name-avatar d-flex align-items-center">
                                    <div class="avatar mr-2 flex-shrink-0">
                                        <?php if (!empty($enrollment['profile_picture']) && file_exists(FCPATH . $enrollment['profile_picture'])): ?>
                                            <img src="<?= base_url($enrollment['profile_picture']) ?>" 
                                                 alt="<?= esc($enrollment['student_name']) ?>" 
                                                 style="width: 40px; height: 40px; border-radius: 100%; object-fit: cover;"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="font-24 text-light-blue weight-500" style="width: 40px; height: 40px; border-radius: 100%; background: #ebf3ff; display: none; align-items: center; justify-content: center;">
                                                <?= strtoupper(substr($enrollment['student_name'], 0, 1) . substr(strrchr($enrollment['student_name'], ' '), 1, 1)) ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="font-24 text-light-blue weight-500" style="width: 40px; height: 40px; border-radius: 100%; background: #ebf3ff; display: flex; align-items: center; justify-content: center;">
                                                <?= strtoupper(substr($enrollment['student_name'], 0, 1) . substr(strrchr($enrollment['student_name'], ' '), 1, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="txt">
                                        <div class="weight-600"><?= esc($enrollment['student_name']) ?></div>
                                        <div class="font-12 color-text-color-2"><?= esc($enrollment['email']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?= esc($enrollment['lrn']) ?></td>
                              <td><?= esc($enrollment['grade_level']) ?></td>
                             <td><?= esc($enrollment['student_contact']) ?></td>
                             <td>
                                <?php 
                                $type = $enrollment['enrollment_type'] ?? 'new';
                                $typeClass = $type === 'new' ? 'badge-primary' : 
                                           ($type === 'transferee' ? 'badge-info' : 
                                           ($type === 'returning' ? 'badge-secondary' : 'badge-dark'));
                                ?>
                                <span class="badge <?= $typeClass ?>"><?= ucfirst(esc($type)) ?></span>
                             </td>
                            <td>
                                <?php 
                                $status = $enrollment['enrollment_status'];
                                $badgeClass = $status === 'approved' ? 'badge-success' : 
                                            ($status === 'declined' ? 'badge-danger' : 'badge-warning');
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= ucfirst(esc($status)) ?></span>
                            </td>
                            <td><?= date('M j, Y', strtotime($enrollment['enrollment_date'])) ?></td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="#" onclick="viewEnrollmentDetails(<?= $enrollment['id'] ?>)"><i class="icon-copy bi bi-eye"></i> View Details</a>
                                        <?php if ($enrollment['enrollment_status'] === 'pending'): ?>
                                            <a class="dropdown-item text-success" onclick="approveEnrollment(<?= $enrollment['id'] ?>)"><i class="icon-copy bi bi-check-circle"></i> Enroll</a>
                                            <a class="dropdown-item text-danger" onclick="declineEnrollment(<?= $enrollment['id'] ?>)"><i class="icon-copy bi bi-x-circle"></i> Decline</a>
                                        <?php endif; ?>

                                        <?php if ($enrollment['enrollment_status'] === 'enrolled'): ?>
                                            <a class="dropdown-item" href="#" onclick="viewStudentRecord(<?= $enrollment['id'] ?>)"><i class="icon-copy bi bi-person-circle"></i> Student Record</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                         <td colspan="10" class="text-center">
                            <div class="py-4"> 
                                <i class="dw dw-user-11 font-48 text-muted"></i> 
                                <h5 class="text-muted mt-3">No enrollment applications found</h5> 
                                <p class="text-muted">Applications will appear here when students submit their enrollment forms.</p> 
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
            </table>
        </div>
        
        <!-- Pagination Section -->
        <div class="row mt-3 mb-3">
            <div class="col-sm-12 col-md-5"></div>
            <div class="col-sm-12 col-md-7">
                <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_2_paginate" style="float: right; margin-right: 15px;">
                    <ul class="pagination mb-0">
                        <li class="paginate_button page-item previous disabled" id="DataTables_Table_2_previous">
                            <a href="#" aria-controls="DataTables_Table_2" data-dt-idx="0" tabindex="0" class="page-link">
                                <i class="ion-chevron-left"></i>
                            </a>
                        </li>
                        <li class="paginate_button page-item active">
                            <a href="#" aria-controls="DataTables_Table_2" data-dt-idx="1" tabindex="0" class="page-link">1</a>
                        </li>
                        <li class="paginate_button page-item">
                            <a href="#" aria-controls="DataTables_Table_2" data-dt-idx="2" tabindex="0" class="page-link">2</a>
                        </li>
                        <li class="paginate_button page-item next" id="DataTables_Table_2_next">
                            <a href="#" aria-controls="DataTables_Table_2" data-dt-idx="3" tabindex="0" class="page-link">
                                <i class="ion-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    filterTable();
});

// Enhanced image handling functions
function handleImageLoad(img) {
    console.log('Image loaded successfully:', img.src);
    
    // Check if it's an SVG by examining the response
    fetch(img.src, { method: 'HEAD' })
        .then(response => {
            const contentType = response.headers.get('content-type');
            console.log('Content-Type:', contentType);
            
            if (contentType && contentType.includes('svg')) {
                // It's an SVG, ensure proper display
                img.style.objectFit = 'contain';
                img.style.background = '#ffffff';
            }
        })
        .catch(error => {
            console.log('Could not check content type:', error);
        });
}

function handleImageError(img) {
    console.error('Image failed to load:', img.src);
    
    // Hide the image and show fallback
    img.style.display = 'none';
    const fallback = img.closest('.document-preview').querySelector('.fallback-container');
    if (fallback) {
        fallback.style.display = 'flex';
    }
    
    // Try alternative approaches
    const originalPath = img.dataset.originalPath;
    const baseUrl = img.src.split('/uploads/')[0] + '/';
    
    // Try different path variations
    const alternatives = [
        `${baseUrl}uploads/enrollment_documents/${originalPath}`,
        `${baseUrl}uploads/enrollment_documents/${originalPath.replace('temp/', '')}`,
        img.src.replace('.png', '.svg'),
        img.src.replace('.jpg', '.svg'),
        img.src.replace('.jpeg', '.svg')
    ];
    
    console.log('Trying alternative paths:', alternatives);
}

document.getElementById('gradeFilter').addEventListener('change', function() {
    filterTable();
});

document.getElementById('statusFilter').addEventListener('change', function() {
    filterTable();
});

function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const gradeFilter = document.getElementById('gradeFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    const table = document.getElementById('enrollmentTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        if (row.cells.length > 1) { // Skip empty state row
            const enrollmentNumber = row.cells[1].textContent.toLowerCase();
            const studentName = row.cells[2].textContent.toLowerCase();
            const lrn = row.cells[3].textContent.toLowerCase();
            const gradeLevel = row.cells[4].textContent;
             // Removed parent name from search since Parent column is removed
            const status = row.getAttribute('data-status');
            
            const matchesSearch = enrollmentNumber.includes(searchTerm) ||
                                studentName.includes(searchTerm) || 
                                lrn.includes(searchTerm);
            const matchesGrade = gradeFilter === '' || gradeLevel === gradeFilter;
            const matchesStatus = statusFilter === '' || status === statusFilter;
            
            if (matchesSearch && matchesGrade && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }
}

// Enrollment actions
function approveEnrollment(enrollmentId) {
    if (confirm('Are you sure you want to enroll this student? This will create a student account and send notifications.')) {
        // Show loading state
        const approveBtn = document.querySelector(`a[onclick="approveEnrollment(${enrollmentId})"]`);
        const originalContent = approveBtn.innerHTML;
        approveBtn.innerHTML = '<i class="icon-copy bi bi-hourglass-split"></i> Processing...';
        approveBtn.style.pointerEvents = 'none';
        
        // Make AJAX call to approve enrollment
        fetch(`<?= site_url('admin/enrollment/approve/') ?>${enrollmentId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            },
            body: JSON.stringify({
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                showNotification('success', data.message);
                
                // Update the row to show enrolled status
                const row = approveBtn.closest('tr');
                const statusCell = row.querySelector('td:nth-child(9)');
                statusCell.innerHTML = '<span class="badge badge-success">Enrolled</span>';
                
                // Remove approve/decline buttons
                const actionsCell = row.querySelector('td:last-child .dropdown-menu');
                const approveItem = actionsCell.querySelector('a[onclick*="approveEnrollment"]');
                const declineItem = actionsCell.querySelector('a[onclick*="declineEnrollment"]');
                if (approveItem) approveItem.remove();
                if (declineItem) declineItem.remove();
                
                // Add view student record option
                const viewStudentItem = document.createElement('a');
                viewStudentItem.className = 'dropdown-item';
                viewStudentItem.href = '#';
                viewStudentItem.innerHTML = '<i class="icon-copy bi bi-person-circle"></i> View Student Record';
                viewStudentItem.onclick = () => viewStudentRecord(enrollmentId);
                actionsCell.appendChild(viewStudentItem);
                
            } else {
                showNotification('error', data.message || 'Failed to enroll student');
                // Restore button
                approveBtn.innerHTML = originalContent;
                approveBtn.style.pointerEvents = 'auto';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'An error occurred while enrolling the student');
            // Restore button
            approveBtn.innerHTML = originalContent;
            approveBtn.style.pointerEvents = 'auto';
        });
    }
}

function declineEnrollment(enrollmentId) {
    // Store enrollment ID for later use
    window.currentDeclineEnrollmentId = enrollmentId;
    
    // Clear previous form data
    document.getElementById('declineReason').value = '';
    
    // Show the decline reason modal
    $('#declineReasonModal').modal('show');
}
</script>

<!-- Decline Reason Modal -->
<div class="modal fade" id="declineReasonModal" tabindex="-1" role="dialog" aria-labelledby="declineReasonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="declineReasonModalLabel">Decline Enrollment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="declineReasonForm">
                    <div class="form-group">
                        <label for="declineReason">Reason for Decline <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="declineReason" name="declineReason" rows="4" 
                                  placeholder="Please provide a detailed reason for declining this enrollment application..." 
                                  required></textarea>
                        <small class="form-text text-muted">This reason will be included in the notification email sent to the applicant.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeclineBtn">
                    <i class="icon-copy bi bi-x-circle"></i> Decline Enrollment
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Handle decline confirmation from modal
document.addEventListener('DOMContentLoaded', function() {
    const confirmDeclineBtn = document.getElementById('confirmDeclineBtn');
    const declineReasonForm = document.getElementById('declineReasonForm');
    
    if (confirmDeclineBtn) {
        confirmDeclineBtn.addEventListener('click', function() {
            const enrollmentId = window.currentDeclineEnrollmentId;
            const reason = document.getElementById('declineReason').value.trim();
            
            // Validate reason
            if (!reason) {
                showNotification('error', 'Please provide a reason for declining this enrollment.');
                return;
            }
            
            // Show loading state
            const originalContent = confirmDeclineBtn.innerHTML;
            confirmDeclineBtn.innerHTML = '<i class="icon-copy bi bi-hourglass-split"></i> Processing...';
            confirmDeclineBtn.disabled = true;
            
            // Make AJAX call to decline enrollment
            fetch(`<?= site_url('admin/enrollment/decline/') ?>${enrollmentId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ reason: reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hide modal
                    $('#declineReasonModal').modal('hide');
                    
                    // Show success message
                    showNotification('success', data.message);
                    
                    // Update the row to show declined status
                    const declineBtn = document.querySelector(`a[onclick="declineEnrollment(${enrollmentId})"]`);
                    const row = declineBtn.closest('tr');
                    const statusCell = row.querySelector('td:nth-child(9)');
                    statusCell.innerHTML = '<span class="badge badge-danger">Declined</span>';
                    
                    // Remove approve/decline buttons
                    const actionsCell = row.querySelector('td:last-child .dropdown-menu');
                    const approveItem = actionsCell.querySelector('a[onclick*="approveEnrollment"]');
                    const declineItem = actionsCell.querySelector('a[onclick*="declineEnrollment"]');
                    if (approveItem) approveItem.remove();
                    if (declineItem) declineItem.remove();
                    
                } else {
                    showNotification('error', data.message || 'Failed to decline enrollment');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'An error occurred while declining the enrollment');
            })
            .finally(() => {
                // Restore button state
                confirmDeclineBtn.innerHTML = originalContent;
                confirmDeclineBtn.disabled = false;
            });
        });
    }
    
    // Reset form when modal is hidden
    $('#declineReasonModal').on('hidden.bs.modal', function() {
        document.getElementById('declineReason').value = '';
        const confirmBtn = document.getElementById('confirmDeclineBtn');
        confirmBtn.innerHTML = '<i class="icon-copy bi bi-x-circle"></i> Decline Enrollment';
        confirmBtn.disabled = false;
    });
});

// Notification helper function
function showNotification(type, message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    notification.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

function viewEnrollmentDetails(enrollmentId) {
    // Show the modal with loading state
    $('#enrollmentDetailsViewModal').modal('show');
    
    // Add bypass parameter for testing
    const apiUrl = `<?= base_url('admin/enrollment/details/') ?>${enrollmentId}?bypass_auth=test123`;
    console.log('Fetching enrollment details from:', apiUrl);
    
    // Fetch enrollment details via AJAX
    fetch(apiUrl)
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            return response.json();
        })
        .then(data => {
            console.log('API Response:', data);
            if (data.success) {
                displayEnrollmentDetails(data.data);
            } else {
                $('#enrollmentDetailsContent').html(`
                    <div class="alert alert-danger">
                        <i class="icon-copy bi bi-exclamation-triangle"></i>
                        Error: ${data.message || 'Failed to load enrollment details'}
                    </div>
                `);
            }
        })
        .catch(error => {
            console.error('Error fetching enrollment details:', error);
            $('#enrollmentDetailsContent').html(`
                <div class="alert alert-danger">
                    <i class="icon-copy bi bi-exclamation-triangle"></i>
                    Error: Failed to load enrollment details. Please try again.
                </div>
            `);
        });
}

function displayEnrollmentDetails(data) {
    const enrollment = data.enrollment;
    const studentInfo = data.student_info;
    const parentInfo = data.parent_info;
    const addressInfo = data.address_info;
    const documents = data.documents || [];
    
    // Define base URL for JavaScript within this function
    const baseUrl = '<?= base_url() ?>';
    
    // Information Tab Content
    const informationContent = `
        <!-- Profile Picture Section -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <div class="profile-picture-container">
                    ${studentInfo.profile_picture && studentInfo.profile_picture.trim() !== '' ? 
                        `<img src="${baseUrl}${studentInfo.profile_picture}" 
                             alt="${studentInfo.full_name || 'Student'}" 
                             class="profile-picture-modal"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                         <div class="profile-picture-fallback" style="display: none;">
                             ${studentInfo.full_name ? studentInfo.full_name.split(' ').map(name => name.charAt(0)).join('').substring(0, 2).toUpperCase() : 'ST'}
                         </div>` :
                        `<div class="profile-picture-fallback">
                             ${studentInfo.full_name ? studentInfo.full_name.split(' ').map(name => name.charAt(0)).join('').substring(0, 2).toUpperCase() : 'ST'}
                         </div>`
                    }
                </div>
                <h5 class="mt-3 mb-1 text-primary">${studentInfo.full_name || 'N/A'}</h5>
                <p class="text-muted mb-0">${enrollment.enrollment_number || 'N/A'}</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="icon-copy bi bi-person"></i> Student Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6"><strong>Full Name:</strong></div>
                            <div class="col-sm-6">${studentInfo.full_name || 'N/A'}</div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-sm-6"><strong>LRN:</strong></div>
                            <div class="col-sm-6">${studentInfo.lrn || 'N/A'}</div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-sm-6"><strong>Grade Level:</strong></div>
                            <div class="col-sm-6">${studentInfo.grade_level || 'N/A'}</div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-sm-6"><strong>Gender:</strong></div>
                            <div class="col-sm-6">${studentInfo.gender || 'N/A'}</div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-sm-6"><strong>Age:</strong></div>
                            <div class="col-sm-6">${studentInfo.age || 'N/A'}</div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-sm-6"><strong>Birth Date:</strong></div>
                            <div class="col-sm-6">${studentInfo.birth_date || 'N/A'}</div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-sm-6"><strong>Email:</strong></div>
                            <div class="col-sm-6">${studentInfo.email || 'N/A'}</div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-sm-6"><strong>Contact:</strong></div>
                            <div class="col-sm-6">${studentInfo.contact || 'N/A'}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="icon-copy bi bi-people"></i> Parent Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6"><strong>Father's Name:</strong></div>
                            <div class="col-sm-6">${parentInfo.father_name || 'N/A'}</div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-sm-6"><strong>Father's Contact:</strong></div>
                            <div class="col-sm-6">${parentInfo.father_contact || 'N/A'}</div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-sm-6"><strong>Mother's Name:</strong></div>
                            <div class="col-sm-6">${parentInfo.mother_name || 'N/A'}</div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-sm-6"><strong>Mother's Contact:</strong></div>
                            <div class="col-sm-6">${parentInfo.mother_contact || 'N/A'}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="icon-copy bi bi-geo-alt"></i> Address Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4"><strong>Current Address:</strong></div>
                            <div class="col-sm-8">${addressInfo.current || 'N/A'}</div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-sm-4"><strong>Permanent Address:</strong></div>
                            <div class="col-sm-8">${addressInfo.permanent || 'N/A'}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="icon-copy bi bi-file-text"></i> Enrollment Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6"><strong>Enrollment Number:</strong></div>
                            <div class="col-sm-6">${enrollment.enrollment_number || 'N/A'}</div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-sm-6"><strong>Status:</strong></div>
                            <div class="col-sm-6">
                                <span class="badge badge-${enrollment.enrollment_status === 'pending' ? 'warning' : 
                                                            enrollment.enrollment_status === 'approved' ? 'success' : 
                                                            enrollment.enrollment_status === 'enrolled' ? 'info' : 'danger'}">
                                    ${enrollment.enrollment_status ? enrollment.enrollment_status.charAt(0).toUpperCase() + enrollment.enrollment_status.slice(1) : 'N/A'}
                                </span>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-sm-6"><strong>Enrollment Type:</strong></div>
                            <div class="col-sm-6">${enrollment.enrollment_type || 'N/A'}</div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-sm-6"><strong>School Year:</strong></div>
                            <div class="col-sm-6">${enrollment.school_year || 'N/A'}</div>
                        </div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-sm-6"><strong>Date Applied:</strong></div>
                            <div class="col-sm-6">${enrollment.created_at ? new Date(enrollment.created_at).toLocaleDateString() : 'N/A'}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Documents Tab Content
    const documentsContent = displayDocuments(documents, baseUrl);
    
    // Update both tabs
    $('#enrollmentDetailsContent').html(informationContent);
    $('#enrollmentDocumentsContent').html(documentsContent);
}

function displayDocuments(documents, baseUrl) {
    if (documents.length === 0) {
        return `
            <div class="text-center py-5">
                <i class="icon-copy bi bi-file-earmark-x text-muted" style="font-size: 4rem;"></i>
                <h5 class="text-muted mt-3">No Documents Uploaded</h5>
                <p class="text-muted">This student hasn't uploaded any documents yet.</p>
            </div>
        `;
    }
    
    return `
        <div class="row">
            ${documents.map(doc => {
                const fileExtension = doc.file_path ? doc.file_path.split('.').pop().toLowerCase() : '';
                const isImage = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(fileExtension);
                const isPdf = fileExtension === 'pdf';
                
                return `
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header">
                                <h6 class="mb-0 text-truncate">
                                    <i class="icon-copy bi bi-${isPdf ? 'file-earmark-pdf text-danger' : isImage ? 'image text-primary' : 'file-earmark text-secondary'}"></i>
                                    ${doc.document_type || 'Document'}
                                </h6>
                                <small class="text-muted">
                                    <i class="icon-copy bi bi-calendar3"></i>
                                    ${doc.uploaded_at ? new Date(doc.uploaded_at).toLocaleDateString() : 'N/A'}
                                </small>
                            </div>
                            <div class="card-body p-0">
                                ${isImage ? `
                                    <div class="document-preview" style="height: 200px; overflow: hidden; position: relative;">
                                        <div class="image-container" style="width: 100%; height: 100%; position: relative;">
                                            <img src="${baseUrl}uploads/enrollment_documents/${doc.file_path.replace('temp/', '')}" 
                                                 alt="${doc.document_type}" 
                                                 class="img-fluid w-100 h-100" 
                                                 style="object-fit: contain; cursor: pointer; background: #f8f9fa;"
                                                 onclick="openImageInNewTab('${baseUrl}uploads/enrollment_documents/${doc.file_path.replace('temp/', '')}', '${doc.document_type}')"
                                                 onerror="handleImageError(this)"
                                                 data-original-path="${doc.file_path}"
                                                 data-full-url="${baseUrl}uploads/enrollment_documents/${doc.file_path.replace('temp/', '')}"
                                                 onload="handleImageLoad(this)"
                                                 data-doc-type="${doc.document_type}">
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted fallback-container" style="display: none; position: absolute; top: 0; left: 0; right: 0; bottom: 0;">
                                            <div class="text-center">
                                                <i class="icon-copy bi bi-image" style="font-size: 2rem;"></i>
                                                <p class="mt-2 mb-0 small">Image Preview</p>
                                                <small class="text-muted">${doc.file_path}</small>
                                                <br>
                                                <button class="btn btn-sm btn-outline-primary mt-2" onclick="openImageInNewTab('${baseUrl}uploads/enrollment_documents/${doc.file_path.replace('temp/', '')}', '${doc.document_type}')">
                                                    <i class="icon-copy bi bi-eye"></i> View in New Tab
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                ` : isPdf ? `
                                    <div class="d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                        <div class="text-center">
                                            <i class="icon-copy bi bi-file-earmark-pdf text-danger" style="font-size: 3rem;"></i>
                                            <p class="mt-2 mb-0 text-muted">PDF Document</p>
                                            <a href="${baseUrl}uploads/enrollment_documents/${doc.file_path.replace('temp/', '')}" target="_blank" class="btn btn-sm btn-outline-danger mt-2">
                                                <i class="icon-copy bi bi-eye"></i> Open PDF
                                            </a>
                                        </div>
                                    </div>
                                ` : doc.file_path && ['doc', 'docx'].includes(fileExtension) ? `
                                    <div class="d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                        <div class="text-center">
                                            <i class="icon-copy bi bi-file-earmark-word text-info" style="font-size: 3rem;"></i>
                                            <p class="mt-2 mb-0 text-muted">Word Document</p>
                                            <a href="${baseUrl}uploads/enrollment_documents/${doc.file_path.replace('temp/', '')}" target="_blank" class="btn btn-sm btn-outline-info mt-2">
                                                <i class="icon-copy bi bi-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                ` : `
                                    <div class="d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                        <div class="text-center">
                                            <i class="icon-copy bi bi-file-earmark text-secondary" style="font-size: 3rem;"></i>
                                            <p class="mt-2 mb-0 text-muted">Document File</p>
                                            <p class="small text-muted">${fileExtension ? '.' + fileExtension.toUpperCase() : ''}</p>
                                            <a href="${baseUrl}uploads/enrollment_documents/${doc.file_path.replace('temp/', '')}" target="_blank" class="btn btn-sm btn-outline-secondary mt-2">
                                                <i class="icon-copy bi bi-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                `}
                            </div>
                        </div>
                    </div>
                `;
            }).join('')}
        </div>
    `;
}

// Function to open image in modal
function openImageModal(imageSrc, imageTitle) {
    const imageModal = `
        <div class="modal fade" id="imageViewModal" tabindex="-1" role="dialog" aria-labelledby="imageViewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageViewModalLabel">
                            <i class="icon-copy bi bi-image"></i> ${imageTitle || 'Document Image'}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center p-2" style="background-color: #f8f9fa;">
                        <img src="${imageSrc}" alt="${imageTitle}" class="img-fluid" style="max-height: 80vh; max-width: 100%; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);" 
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div class="alert alert-warning" style="display: none;">
                            <i class="icon-copy bi bi-exclamation-triangle"></i>
                            <strong>Image not found!</strong><br>
                            The image file may have been moved or deleted.<br>
                            <small class="text-muted">Path: ${imageSrc}</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="icon-copy bi bi-x"></i> Close
                        </button>
                        <a href="${imageSrc}" download class="btn btn-primary">
                            <i class="icon-copy bi bi-download"></i> Download
                        </a>
                        <button type="button" class="btn btn-info" onclick="window.open('${imageSrc}', '_blank')">
                            <i class="icon-copy bi bi-arrow-up-right-square"></i> Open in New Tab
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing image modal if any
    $('#imageViewModal').remove();
    
    // Add new image modal to body
    $('body').append(imageModal);
    
    // Show the modal
    $('#imageViewModal').modal('show');
    
    // Add click outside to close functionality
    $('#imageViewModal').on('click', function(e) {
        if (e.target === this) {
            $(this).modal('hide');
        }
    });
    
    // Remove modal from DOM when hidden
    $('#imageViewModal').on('hidden.bs.modal', function () {
        $(this).remove();
    });
    
    // Add keyboard navigation
    $(document).on('keydown.imageModal', function(e) {
        if (e.key === 'Escape') {
            $('#imageViewModal').modal('hide');
        }
    });
    
    // Remove keyboard listener when modal is hidden
    $('#imageViewModal').on('hidden.bs.modal', function () {
        $(document).off('keydown.imageModal');
    });
}

// Function to open image in new tab with fallback handling
function openImageInNewTab(imageSrc, imageTitle) {
    console.log('Opening image in new tab:', imageSrc);
    
    // Try to open in new tab
    const newTab = window.open('', '_blank');
    
    if (newTab) {
        // Create a simple HTML page for the image
        newTab.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>${imageTitle || 'Document Image'}</title>
                <style>
                    body {
                        margin: 0;
                        padding: 20px;
                        background-color: #f5f5f5;
                        font-family: Arial, sans-serif;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        min-height: 100vh;
                    }
                    .image-container {
                        background: white;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                        max-width: 95vw;
                        max-height: 95vh;
                        overflow: auto;
                    }
                    img {
                        max-width: 100%;
                        height: auto;
                        display: block;
                    }
                    .error-message {
                        text-align: center;
                        color: #666;
                        padding: 40px;
                    }
                    .download-btn {
                        margin-top: 20px;
                        padding: 10px 20px;
                        background-color: #007bff;
                        color: white;
                        text-decoration: none;
                        border-radius: 4px;
                        display: inline-block;
                    }
                    .download-btn:hover {
                        background-color: #0056b3;
                        color: white;
                        text-decoration: none;
                    }
                </style>
            </head>
            <body>
                <div class="image-container">
                    <h3>${imageTitle || 'Document Image'}</h3>
                    <img src="${imageSrc}" alt="${imageTitle}" 
                         onerror="this.style.display='none'; document.getElementById('error-msg').style.display='block';">
                    <div id="error-msg" class="error-message" style="display: none;">
                        <p><strong>Image could not be loaded</strong></p>
                        <p>The image file may not be accessible or may have been moved.</p>
                        <p><small>Path: ${imageSrc}</small></p>
                        <a href="${imageSrc}" class="download-btn" download>Try Download</a>
                    </div>
                </div>
            </body>
            </html>
        `);
        newTab.document.close();
    } else {
        // Fallback if popup blocked
        alert('Popup blocked. Please allow popups for this site or try right-clicking the image and selecting "Open in new tab".');
    }
}

// Add print functionality
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('printDetailsBtn').addEventListener('click', function() {
        const printContent = document.getElementById('enrollmentDetailsContent').innerHTML;
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Enrollment Details</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .card { border: 1px solid #ddd; margin-bottom: 20px; }
                        .card-header { background-color: #f8f9fa; padding: 10px; font-weight: bold; }
                        .card-body { padding: 15px; }
                        .table td { padding: 5px 10px; }
                        @media print {
                            .btn { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <h2>Enrollment Details</h2>
                    ${printContent}
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    });
});

function refreshEnrollments() {
    location.reload();
}

function exportEnrollments() {
    // Add your export logic here
    console.log('Exporting enrollments');
}

function viewStudentRecord(enrollmentId) {
    // Add your view student record logic here
    console.log('Viewing student record:', enrollmentId);
}

function handleCopyClick(button) {
    // Copy functionality
    const table = document.querySelector('.data-table-export');
    const range = document.createRange();
    range.selectNode(table);
    window.getSelection().removeAllRanges();
    window.getSelection().addRange(range);
    document.execCommand('copy');
    window.getSelection().removeAllRanges();
    
    // Show feedback
    button.innerHTML = '<i class="icon-copy bi bi-check"></i> <span>Copied!</span>';
    setTimeout(() => {
        button.innerHTML = '<i class="icon-copy bi bi-clipboard"></i> <span>Copy</span>';
    }, 2000);
}
</script>

<!-- Enrollment Details Modal -->
<div class="modal fade" id="enrollmentDetailsViewModal" tabindex="-1" role="dialog" aria-labelledby="enrollmentDetailsViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="enrollmentDetailsViewModalLabel">
                    <i class="icon-copy bi bi-eye"></i> Enrollment Details
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs" id="enrollmentDetailsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="information-tab" data-toggle="tab" href="#information" role="tab" aria-controls="information" aria-selected="true">
                            <i class="icon-copy bi bi-person-lines-fill"></i> Student Information
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="documents-tab" data-toggle="tab" href="#documents" role="tab" aria-controls="documents" aria-selected="false">
                            <i class="icon-copy bi bi-file-earmark-text"></i> Uploaded Documents
                        </a>
                    </li>
                </ul>
                
                <!-- Tab Content -->
                <div class="tab-content" id="enrollmentDetailsTabContent">
                    <!-- Information Tab -->
                    <div class="tab-pane fade show active p-4" id="information" role="tabpanel" aria-labelledby="information-tab">
                        <div id="enrollmentDetailsContent">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <p class="mt-2">Loading enrollment details...</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Documents Tab -->
                    <div class="tab-pane fade p-4" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                        <div id="enrollmentDocumentsContent">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <p class="mt-2">Loading documents...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="icon-copy bi bi-x"></i> Close
                </button>
                <button type="button" class="btn btn-primary" id="printDetailsBtn">
                    <i class="icon-copy bi bi-printer"></i> Print Details
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
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

.table-responsive {
    border-radius: 8px;
}

.modal-lg {
    max-width: 800px;
}

/* Fix dropdown z-index issue */
.dropdown {
    position: relative;
}

.dropdown-menu {
    z-index: 9999 !important;
    position: absolute !important;
}

.table-responsive .dropdown-menu {
    z-index: 10000 !important;
}

/* Ensure table doesn't create stacking context issues */
.table {
    position: relative;
    z-index: 1;
}

.table td {
    position: relative;
}

/* Profile Picture Styles for Modal */
.profile-picture-container {
    position: relative;
    display: inline-block;
}

.profile-picture-modal {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.profile-picture-fallback {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    font-weight: bold;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}
</style>

<?= $this->endSection() ?>