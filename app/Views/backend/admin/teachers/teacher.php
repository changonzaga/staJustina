<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <div class="title">
            <h4>All Teachers</h4>
        </div>
        <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="<?= route_to('admin.home')?>">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Teachers
                </li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="<?= site_url('admin/teacher/create') ?>" class="btn btn-success btn-sm">
            <i class="icon-copy bi bi-plus-lg"></i> Add Teacher
        </a>
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

<!-- Main Container -->
<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4">Teachers List</h4>
    </div>
    
    <!-- Filter, Search, and Export Buttons Section -->
    <div class="pd-20 pt-0">
        <div class="row align-items-end">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Search Teachers:</label>
                    <div class="position-relative">
                        <input type="text" class="form-control pl-4" id="searchInput" placeholder="Search by name or Account No." onkeyup="filterTable()">
                        <span style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #aaa;">
                            <i class="icon-copy bi bi-search"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Filter Category:</label>
                    <select class="form-control" id="categoryFilter" onchange="filterTable()">
                        <option value="">All Categories</option>
                        <option value="Regular">Regular</option>
                        <option value="SPED">SPED</option>
                        <option value="Transfer">Transfer</option>
                        <!-- Add more categories as needed -->
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div class="d-flex justify-content-end">
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
								<i class="icon-copy bi bi-printer"></i> <span>Print</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pb-20">
        <div class="table-responsive">
            <table class="table hover multiple-select-row data-table-export nowrap">
                <thead>
                    <tr>
                        <th class="table-plus datatable-nosort">No.</th>
                        <th>Teacher</th>
                        <th>Account No.</th>
                        <th>Email</th>
                        <th>Employee ID</th>
                        <th>Position</th>
                        <th>Employment Status</th>
                        <th class="datatable-nosort">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($teachers)): ?>
                        <?php foreach($teachers as $key => $teacher): ?>
                            <tr>
                                <td class="table-plus"><?= $key + 1 ?></td>
                                <td>
                                    <div class="name-avatar d-flex align-items-center">
                                        <div class="avatar mr-2 flex-shrink-0">
                                            <?php if(!empty($teacher['profile_picture'])): ?>
                                                <img src="<?= base_url('uploads/teachers/'.$teacher['profile_picture']) ?>" class="border-radius-100 shadow" width="40" height="40" alt="">
                                            <?php else: ?>
                                                <div class="font-24 text-light-blue weight-500" style="width: 40px; height: 40px; border-radius: 100%; background: #ebf3ff; display: flex; align-items: center; justify-content: center;">
                                                    <?= strtoupper(substr($teacher['first_name'], 0, 1) . substr($teacher['last_name'], 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="txt">
                                            <div class="weight-600"><?= esc($teacher['first_name'] . ' ' . $teacher['last_name']) ?></div>
                                            <div class="font-12 color-text-color-2"><?= esc($teacher['specialization'] ?? 'Not specified') ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-info"><?= esc($teacher['account_no']) ?></span></td>
                                <td><?= esc($teacher['email']) ?></td>
                                <td><?= esc($teacher['employee_id']) ?></td>
                                <td><?= esc($teacher['position'] ?? 'Not specified') ?></td>
                                <td>
                                    <?php 
                                    $status = $teacher['employment_status'] ?? 'Active';
                                    $badgeClass = '';
                                    switch(strtolower($status)) {
                                        case 'regular':
                                            $badgeClass = 'badge-success';
                                            break;
                                        case 'contractual':
                                            $badgeClass = 'badge-warning';
                                            break;
                                        case 'substitute':
                                            $badgeClass = 'badge-info';
                                            break;
                                        case 'part-time':
                                            $badgeClass = 'badge-secondary';
                                            break;
                                        default:
                                            $badgeClass = 'badge-primary';
                                    }
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= esc($status) ?></span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                            <i class="dw dw-more"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                            <a class="dropdown-item" href="<?= site_url('/admin/teacher/show/'.$teacher['id']) ?>"><i class="dw dw-eye"></i> View Profile</a>
                                            <a class="dropdown-item" href="<?= site_url('/admin/teacher/edit/'.$teacher['id']) ?>"><i class="dw dw-edit2"></i> Edit</a>
                                            <a class="dropdown-item text-danger" href="#" onclick="confirmDelete(<?= $teacher['id'] ?>, '<?= esc($teacher['first_name'] . ' ' . $teacher['last_name']) ?>')"><i class="dw dw-delete-3"></i> Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="py-4"> 
                                    <i class="dw dw-user-11 font-48 text-muted"></i> 
                                    <h5 class="text-muted mt-3">No teachers found</h5> 
                                    <p class="text-muted">Start by adding your first teacher to the system.</p> 
                                    <a href="<?= site_url('/admin/teacher/create') ?>" class="btn btn-success"> 
                                        <i class="icon-copy bi bi-plus-lg"></i> Add First Teacher 
                                    </a> 
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Section -->
        <div class="row mt-4">
            <div class="col-sm-12 col-md-7">
                <div class="dataTables_paginate paging_simple_numbers ml-3" id="DataTables_Table_2_paginate">
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
// Filter function
function filterTable() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const categoryFilter = document.getElementById('categoryFilter').value.toLowerCase();
    const tableBody = document.querySelector('tbody');
    const rows = tableBody.querySelectorAll('tr');
    
    rows.forEach(row => {
        const nameCell = row.cells[1]?.textContent.toLowerCase() || '';
        const accountCell = row.cells[2]?.textContent.toLowerCase() || '';
        const categoryCell = row.cells[3]?.textContent.toLowerCase() || '';
        
        const matchesSearch = nameCell.includes(searchInput) || accountCell.includes(searchInput);
        const matchesCategory = !categoryFilter || categoryCell.includes(categoryFilter);
        
        if (matchesSearch && matchesCategory) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="teacherName"></strong>?</p>
                <p class="text-danger"><small>This action cannot be undone. The teacher's login access will also be removed.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Delete Teacher</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function confirmDelete(teacherId, teacherName) {
    document.getElementById('teacherName').textContent = teacherName;
    document.getElementById('deleteForm').action = '<?= site_url('/admin/teacher/delete/') ?>' + teacherId;
    $('#deleteModal').modal('show');
}

// Auto-hide alerts after 5 seconds
$(document).ready(function() {
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
<?= $this->endSection() ?>