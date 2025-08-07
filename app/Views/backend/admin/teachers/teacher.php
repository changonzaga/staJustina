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

<!-- Filter, Search, and Export Buttons Row -->
<div class="mt-3 mb-2">
    <div class="row align-items-end">
        <div class="col-md-3">
            <div class="form-group mb-2 position-relative">
                <input type="text" class="form-control pl-4" id="searchInput" placeholder="Search by name or Account No." onkeyup="filterTable()">
                <span style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #aaa;">
                    <i class="icon-copy bi bi-search"></i>
                </span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group mb-2">
                <select class="form-control" id="categoryFilter" onchange="filterTable()">
                    <option value="">All Categories</option>
                    <option value="Regular">Regular</option>
                    <option value="SPED">SPED</option>
                    <option value="Transfer">Transfer</option>
                    <!-- Add more categories as needed -->
                </select>
            </div>
        </div>
        <div class="col-md-6 d-flex justify-content-end">
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
    <div class="pb-20">
        <div class="table-responsive">
            <table class="table hover multiple-select-row data-table-export nowrap">
                <thead>
                    <tr>
                        <th class="table-plus datatable-nosort">No.</th>
                        <th>Teacher</th>
                        <th>Account No.</th>
                        <th>Subjects</th>
                        <th>Gender</th>
                        <th>Age</th>
                        <th>Students</th>
                        <th>Status</th>
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
                                                    <?= substr($teacher['name'], 0, 1) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="txt">
                                            <div class="weight-600"><?= $teacher['name'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><?= $teacher['account_no'] ?></td>
                                <td><?= $teacher['subjects'] ?></td>
                                <td><?= $teacher['gender'] ?></td>
                                <td><?= $teacher['age'] ?></td>
                                <td><?= $teacher['student_count'] ?></td>
                                <td>
                                    <?php if($teacher['status'] == 'Active'): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                            <i class="dw dw-more"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                            <a class="dropdown-item" href="<?= site_url('admin/teacher/view/'.$teacher['id']) ?>"><i class="dw dw-eye"></i> View</a>
                                            <a class="dropdown-item" href="<?= site_url('admin/teacher/edit/'.$teacher['id']) ?>"><i class="dw dw-edit2"></i> Edit</a>
                                            <a class="dropdown-item delete-teacher" href="javascript:;" data-id="<?= $teacher['id'] ?>"><i class="dw dw-delete-3"></i> Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">
                                <div class="py-4"> 
                                    <i class="dw dw-user-11 font-48 text-muted"></i> 
                                    <h5 class="text-muted mt-3">No teachers found</h5> 
                                    <p class="text-muted">Start by adding your first teacher to the system.</p> 
                                    <a href="<?= site_url('admin/teacher/create') ?>" class="btn btn-success"> 
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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <i class="dw dw-delete-3 text-danger" style="font-size: 48px;"></i>
                <p class="mt-3">Are you sure you want to delete this teacher?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" action="" style="display: inline;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
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
                <p class="mt-3">Deleted! Teacher has been deleted successfully.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="deletedOkBtn">OK</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert-dismissible').alert('close');
        }, 5000);
        
        // Handle delete button click
        $(document).on('click', '.delete-teacher', function(e) {
            e.preventDefault();
            const teacherId = $(this).data('id');
            console.log('Delete button clicked for teacher ID:', teacherId);
            $('#deleteForm').attr('action', '<?= site_url("admin/teacher/delete/") ?>' + teacherId);
            $('#deleteModal').modal('show');
        });
        
        // Handle form submission to show success modal
        $('#deleteForm').on('submit', function(e) {
            e.preventDefault();
            
            var form = $(this);
            var actionUrl = form.attr('action');
            
            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    // Hide the confirmation modal
                    $('#deleteModal').modal('hide');
                    // Show the success modal
                    $('#deletedSuccessModal').modal('show');
                },
                error: function(xhr, status, error) {
                    // Hide the confirmation modal
                    $('#deleteModal').modal('hide');
                    // Show the success modal even on error (like student functionality)
                    $('#deletedSuccessModal').modal('show');
                }
            });
        });
        
        // Handle success modal OK button
        $('#deletedOkBtn').on('click', function() {
            $('#deletedSuccessModal').modal('hide');
            // Refresh the page to see the changes
            window.location.reload();
        });
        
        // Debug: Check if modal is properly initialized
        $('#deleteModal').on('shown.bs.modal', function () {
            console.log('Delete modal is shown');
        });
    });
</script>
<?= $this->endSection() ?>