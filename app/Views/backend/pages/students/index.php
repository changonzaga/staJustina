<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>
<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h4 class="mb-0">All Student</h4>
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
        <a href="<?= site_url('backend/admin/students/create') ?>" class="btn btn-success btn-sm">
            <i class="icon-copy bi bi-plus-lg"></i> Enroll Student
        </a>
    </div>
</div>

<!-- Filter, Search, and Export Buttons Row -->
<div class="mt-3 mb-2">
    <div class="row align-items-end">
        <div class="col-md-3">
            <div class="form-group mb-2 position-relative">
                <input type="text" class="form-control pl-4" id="searchInput" placeholder="Search by name or LRN..." onkeyup="filterTable()">
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
<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4">Data Table Simple</h4>
    </div>
    <div class="pb-20">
        <div id="DataTables_Table_2_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div id="DataTables_Table_2_filter" class="dataTables_filter">
                <label>Search:
                    <input type="search" class="form-control form-control-sm" placeholder="Search" aria-controls="DataTables_Table_2" onkeyup="filterTable()" id="dtSearchInput">
                </label>
            </div>
            <div class="table-responsive">
                <table class="table hover multiple-select-row data-table-export nowrap dataTable no-footer dtr-inline collapsed" id="DataTables_Table_2" role="grid">
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
                            <th>Category</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample Data Row 1 -->
                        <tr>
                            <td><input type="checkbox" class="student-checkbox"></td>
                            <td>
                                <img src="<?= base_url('backend/vendors/images/photo1.jpg')?>" class="avatar-photo" style="width: 40px; height: 40px; border-radius: 50%;" alt="">
                            </td>
                            <td><span class="badge badge-info">2224777</span></td>
                            <td class="table-plus">
                                <strong>Ms. Loida Castilo</strong>
                                <br><small class="text-muted">Female, 35 years old</small>
                            </td>
                            <td><span class="badge badge-primary">English</span></td>
                            <td><span class="badge badge-secondary">Cadlan</span></td>
                            <td>Guardian Name</td>
                            <td>0928808859</td>
                            <td>Regular</td>
                            <td>
                                <span class="badge badge-success">Active</span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a>
                                        <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
                                        <a class="dropdown-item" href="#"><i class="dw dw-delete-3"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <!-- Sample Data Row 2 -->
                        <tr>
                            <td><input type="checkbox" class="student-checkbox"></td>
                            <td>
                                <img src="<?= base_url('backend/vendors/images/photo2.jpg')?>" class="avatar-photo" style="width: 40px; height: 40px; border-radius: 50%;" alt="">
                            </td>
                            <td><span class="badge badge-info">2224777</span></td>
                            <td class="table-plus">
                                <strong>Ms. Loren Roxas</strong>
                                <br><small class="text-muted">Female, 35 years old</small>
                            </td>
                            <td><span class="badge badge-primary">English</span></td>
                            <td><span class="badge badge-secondary">Cadlan</span></td>
                            <td>Guardian Name</td>
                            <td>0928808859</td>
                            <td>SPED</td>
                            <td>
                                <span class="badge badge-success">Active</span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a>
                                        <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
                                        <a class="dropdown-item" href="#"><i class="dw dw-delete-3"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <!-- Sample Data Row 3 -->
                        <tr>
                            <td><input type="checkbox" class="student-checkbox"></td>
                            <td>
                                <img src="<?= base_url('backend/vendors/images/photo3.jpg')?>" class="avatar-photo" style="width: 40px; height: 40px; border-radius: 50%;" alt="">
                            </td>
                            <td><span class="badge badge-info">2224777</span></td>
                            <td class="table-plus">
                                <strong>Mr. Justine Velasquez</strong>
                                <br><small class="text-muted">Female, 35 years old</small>
                            </td>
                            <td><span class="badge badge-primary">English</span></td>
                            <td><span class="badge badge-secondary">Cadlan</span></td>
                            <td>Guardian Name</td>
                            <td>0928808859</td>
                            <td>Transfer</td>
                            <td>
                                <span class="badge badge-success">Active</span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a>
                                        <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
                                        <a class="dropdown-item" href="#"><i class="dw dw-delete-3"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <!-- Sample Data Row 4 -->
                        <tr>
                            <td><input type="checkbox" class="student-checkbox"></td>
                            <td>
                                <img src="<?= base_url('backend/vendors/images/photo4.jpg')?>" class="avatar-photo" style="width: 40px; height: 40px; border-radius: 50%;" alt="">
                            </td>
                            <td><span class="badge badge-info">2224777</span></td>
                            <td class="table-plus">
                                <strong>Mrs. Angeline Escuro</strong>
                                <br><small class="text-muted">Female, 35 years old</small>
                            </td>
                            <td><span class="badge badge-primary">English</span></td>
                            <td><span class="badge badge-secondary">Cadlan</span></td>
                            <td>Guardian Name</td>
                            <td>0928808859</td>
                            <td>Regular</td>
                            <td>
                                <span class="badge badge-success">Active</span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a>
                                        <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
                                        <a class="dropdown-item" href="#"><i class="dw dw-delete-3"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_2_paginate">
                <ul class="pagination">
                    <li class="paginate_button page-item previous disabled" id="DataTables_Table_2_previous">
                        <a href="#" aria-controls="DataTables_Table_2" data-dt-idx="0" tabindex="0" class="page-link"><i class="ion-chevron-left"></i></a>
                    </li>
                    <li class="paginate_button page-item active">
                        <a href="#" aria-controls="DataTables_Table_2" data-dt-idx="1" tabindex="0" class="page-link">1</a>
                    </li>
                    <li class="paginate_button page-item ">
                        <a href="#" aria-controls="DataTables_Table_2" data-dt-idx="2" tabindex="0" class="page-link">2</a>
                    </li>
                    <li class="paginate_button page-item next" id="DataTables_Table_2_next">
                        <a href="#" aria-controls="DataTables_Table_2" data-dt-idx="3" tabindex="0" class="page-link"><i class="ion-chevron-right"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function filterTable() {
    const category = document.getElementById('categoryFilter').value.toLowerCase();
    const search = (document.getElementById('searchInput') ? document.getElementById('searchInput').value : '') +
                   (document.getElementById('dtSearchInput') ? document.getElementById('dtSearchInput').value : '');
    const searchVal = search.toLowerCase();
    const table = document.getElementById('DataTables_Table_2');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const rowCategory = row.cells[8] ? row.cells[8].textContent.toLowerCase() : '';
        const rowName = row.cells[3] ? row.cells[3].textContent.toLowerCase() : '';
        const rowLRN = row.cells[2] ? row.cells[2].textContent.toLowerCase() : '';
        const matchCategory = !category || rowCategory.includes(category);
        const matchSearch = !searchVal || rowName.includes(searchVal) || rowLRN.includes(searchVal);
        if (matchCategory && matchSearch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.student-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}
function handleCopyClick(btn) {
    const icon = btn.querySelector('i');
    if (icon) {
        icon.classList.remove('bi-clipboard');
        icon.classList.add('bi-clipboard-check');
    }
    setTimeout(() => {
        if (icon) {
            icon.classList.remove('bi-clipboard-check');
            icon.classList.add('bi-clipboard');
        }
    }, 1500);
}
</script>

<?= $this->endSection() ?>