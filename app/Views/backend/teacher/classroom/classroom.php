<?= $this->extend('backend/teacher/layout/pages-layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>My Classroom</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= site_url('teacher/dashboard') ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="#">Teaching</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        My Classroom
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!--Advisory Class Content-->
<div class="row mt-4">
    <div class="col-12">
        <div class="card-box mb-30">
            <div class="pd-20">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-blue h4">Advisory Classroom</h4>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                            <i class="dw dw-calendar1"></i> 2024-2025
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">2024-2025</a>
                            <a class="dropdown-item" href="#">2023-2024</a>
                            <a class="dropdown-item" href="#">2022-2023</a>
                        </div>
                    </div>
                </div>
                <!-- Assigned Classroom Cards -->
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="card" style="border-radius: 8px; border: 1px solid #e0e0e0;">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-1">
                                        <div class="bg-danger text-white d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 8px;">
                                            <i class="dw dw-book" style="font-size: 24px;"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div>
                                            <strong>Class Adviser:</strong> <span class="text-primary">Ms. Rosario Reyes</span><br>
                                            <strong>Subject:</strong> English<br>
                                            <strong>Grade:</strong> 11 <strong>Section:</strong> <span class="text-primary">Jupiter</span>
                                        </div>
                                    </div>
                                        <div class="col-md-4">
                                            <div class="d-flex justify-content-end">
                                                <div class="dropdown show">
                                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="true">
                                                        <i class="dw dw-more"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list" x-placement="bottom-end">
                                                        <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View Classroom</a>
                                                        <a class="dropdown-item" href="#"><i class="dw dw-add"></i> Add Materials</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assigned Classroom Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-blue h4">Assigned Classroom</h4>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                <i class="dw dw-calendar1"></i> 2024-2025
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">2024-2025</a>
                                <a class="dropdown-item" href="#">2023-2024</a>
                                <a class="dropdown-item" href="#">2022-2023</a>
                            </div>
                        </div>
                    </div>
                    <!-- Assigned Classroom Cards -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="card" style="border-radius: 8px; border: 1px solid #e0e0e0;">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-1">
                                            <div class="bg-danger text-white d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 8px;">
                                                <i class="dw dw-book" style="font-size: 24px;"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div>
                                                <strong>Class Adviser:</strong> <span class="text-primary">Ms. Rosario Reyes</span><br>
                                                <strong>Subject:</strong> English<br>
                                                <strong>Grade:</strong> 11 <strong>Section:</strong> <span class="text-primary">Jupiter</span>
                                            </div>
                                        </div>
                                    <div class="col-md-4">
                                            <div class="d-flex justify-content-end">
                                                <div class="dropdown show">
                                                        <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="true">
                                                            <i class="dw dw-more"></i>
                                                        </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list" x-placement="bottom-end">
                                                        <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View Classroom</a>
                                                        <a class="dropdown-item" href="#"><i class="dw dw-add"></i> Add Materials</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <div class="card" style="border-radius: 8px; border: 1px solid #e0e0e0;">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-1">
                                            <div class="bg-danger text-white d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 8px;">
                                                <i class="dw dw-book" style="font-size: 24px;"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div>
                                                <strong>Class Adviser:</strong> <span class="text-primary">Mr. Rudy Boringot</span><br>
                                                <strong>Subject:</strong> English<br>
                                                <strong>Grade:</strong> 9 <strong>Section:</strong> <span class="text-primary">Molave</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex justify-content-end">
                                                <div class="dropdown show">
                                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="true">
                                                        <i class="dw dw-more"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list" x-placement="bottom-end">
                                                        <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View Classroom</a>
                                                        <a class="dropdown-item" href="#"><i class="dw dw-add"></i> Add Materials</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <div class="card" style="border-radius: 8px; border: 1px solid #e0e0e0;">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-1">
                                            <div class="bg-danger text-white d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 8px;">
                                                <i class="dw dw-book" style="font-size: 24px;"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div>
                                                <strong>Class Adviser:</strong> <span class="text-primary">Mr. Justine Velasquez</span><br>
                                                <strong>Subject:</strong> English<br>
                                                <strong>Grade:</strong> 7 <strong>Section:</strong> <span class="text-primary">Sampaguita</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex justify-content-end">
                                                <div class="dropdown show">
                                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="true">
                                                        <i class="dw dw-more"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list" x-placement="bottom-end">
                                                        <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View Classroom</a>
                                                        <a class="dropdown-item" href="#"><i class="dw dw-add"></i> Add Materials</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <div class="card" style="border-radius: 8px; border: 1px solid #e0e0e0;">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-1">
                                            <div class="bg-danger text-white d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 8px;">
                                                <i class="dw dw-book" style="font-size: 24px;"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div>
                                                <strong>Class Adviser:</strong> <span class="text-primary">Ms. Christine Joy Frondozo</span><br>
                                                <strong>Subject:</strong> English<br>
                                                <strong>Grade:</strong> 7 <strong>Section:</strong> <span class="text-primary">Rose</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex justify-content-end">
                                                <div class="dropdown show">
                                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="true">
                                                        <i class="dw dw-more"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list" x-placement="bottom-end">
                                                        <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View Classroom</a>
                                                        <a class="dropdown-item" href="#"><i class="dw dw-add"></i> Add Materials</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Classroom Management Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card-box mb-30">
            <div class="pd-20">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                    <h4 class="text-blue h4 mb-2">Classroom</h4>
                    <div class="d-flex align-items-center flex-wrap">
                        <!-- Show Entries -->
                        <div class="dataTables_length mb-2" id="DataTables_Table_0_length">
                            <label class="mb-0 d-flex align-items-center">
                                <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="custom-select custom-select-sm form-control form-control-sm mx-2" id="entriesLength" style="width: auto; min-width: 70px;">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="-1">All</option>
                                </select>
                            </label>
                        </div>
                        <!-- Grade Level Filter -->
                        <div class="mr-3 mb-2">
                            <select class="form-control form-control-sm" id="gradeFilter" style="min-width: 120px;">
                                <option value="">All Grades</option>
                                <option value="Grade 7">Grade 7</option>
                                <option value="Grade 8">Grade 8</option>
                                <option value="Grade 9">Grade 9</option>
                                <option value="Grade 10">Grade 10</option>
                                <option value="Grade 11">Grade 11</option>
                                <option value="Grade 12">Grade 12</option>
                            </select>
                        </div>
                        <!-- Search Input -->
                        <div class="search-icon-box classroom-search mr-3 mb-2">
                            <input type="text" class="form-control form-control-sm search-input" id="sectionSearch" placeholder="Search by Section">
                            <i class="dw dw-search search-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="pb-20">
                    <div class="table-responsive">
                        <table class="data-table table stripe hover nowrap" id="classroomTable">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">Grade Level</th>
                                    <th>Section</th>
                                    <th>Class Adviser</th>
                                    <th class="datatable-nosort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="table-plus">Grade 7</td>
                                    <td>Lotus</td>
                                    <td>Mrs. Angelyn Aleman</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View Classroom</a>
                                                <a class="dropdown-item" href="#"><i class="dw dw-add"></i> Add Classroom</a>
                                                <a class="dropdown-item" href="#"><i class="dw dw-delete-3"></i> Remove Classroom</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-plus">Grade 7</td>
                                    <td>Gumamela</td>
                                    <td>Mr. Jerald Ricabuerta</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View Classroom</a>
                                                <a class="dropdown-item" href="#"><i class="dw dw-add"></i> Add Classroom</a>
                                                <a class="dropdown-item" href="#"><i class="dw dw-delete-3"></i> Remove Classroom</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-plus">Grade 7</td>
                                    <td>Sampaguita</td>
                                    <td>Mr. Justine Velasquez</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View Classroom</a>
                                                <a class="dropdown-item" href="#"><i class="dw dw-add"></i> Add Classroom</a>
                                                <a class="dropdown-item" href="#"><i class="dw dw-delete-3"></i> Remove Classroom</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Base card styles */
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    /* Search input styling */
    .search-icon-box {
        position: relative;
    }
    
    .classroom-search .search-input {
        padding-right: 40px;
        border-radius: 5px;
        border: 1px solid #ddd;
        height: 31px !important;
        font-size: 14px !important;
        padding: 6px 40px 6px 12px !important;
        width: 200px;
        min-width: 150px;
        background: #ffffff !important;
        color: #333 !important;
    }
    
    .classroom-search .search-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 16px;
        color: #666;
    }
    
    .classroom-search .search-input::-webkit-input-placeholder { color: #999 !important; }
    .classroom-search .search-input:-moz-placeholder { color: #999 !important; }
    .classroom-search .search-input::-moz-placeholder { color: #999 !important; }
    .classroom-search .search-input:-ms-input-placeholder { color: #999 !important; }
    
    /* Table responsive improvements */
    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
    }
        
    .table th {
        background-color: #f8f9fa;
        border-top: none;
        font-weight: 600;
        color: #495057;
    }
    
    /* Button gap fix for older browsers */
    .d-flex.gap-2 > * + * {
        margin-left: 0.5rem;
    }
    
    /* Card hover effects removed */
    
    /* Responsive adjustments for new content */
    @media (max-width: 768px) {
        .d-flex.gap-2 {
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .d-flex.gap-2 > * + * {
            margin-left: 0;
            margin-top: 0.25rem;
        }
        
        .btn-sm {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        
        .col-md-1, .col-md-7, .col-md-4 {
            margin-bottom: 1rem;
        }
        
        .justify-content-end {
            justify-content: flex-start !important;
        }
    }
    
    /* Responsive adjustments for card content */
    @media (max-width: 767px) {
        .card h5.mb-0 {
            font-size: 1rem;
        }
        .card p.mb-0 {
            font-size: 0.85rem;
        }
        .card-footer a {
            padding: 5px;
        }
        /* Adjust header padding on medium screens */
        .card div[style*="padding: 15px 20px"] {
            padding: 12px 15px !important;
        }
    }
    
    /* Ensure images scale properly on small screens */
    .card img {
        max-width: 100%;
        height: auto;
        max-height: 80px;
    }
    
    /* Adjust card height on smaller screens */
    @media (max-width: 576px) {
        .card div[style*="height: 150px"] {
            height: 120px !important;
        }
        /* Further reduce header padding on small screens */
        .card div[style*="padding: 15px 20px"] {
            padding: 10px 12px !important;
        }
        /* Adjust more button position */
        .card .btn.btn-link {
            right: 2px;
            top: 2px;
        }
        /* Ensure long subject names don't overflow */
        .card h5.mb-0 {
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 85%;
        }
    }
    
    /* Make cards take full width on extra small screens */
    @media (max-width: 400px) {
        .col-12 {
            padding-left: 10px;
            padding-right: 10px;
        }
        /* Further adjustments for very small screens */
        .card-footer {
            padding: 8px 15px !important;
        }
        .card div[style*="height: 150px"] {
            height: 100px !important;
        }
    }
    
    /* Special handling for long subject names */
    @media (max-width: 767px) {
        /* Add specific class for long subject names */
        .card h5.mb-0 {
            max-width: 85%;
        }
        /* Reduce font size for all subject names on smaller screens */
        .card h5.mb-0 {
            font-size: 0.85rem;
            line-height: 1.2;
        }
    }
    
    /* Fix for hover effect on touch devices */
    @media (hover: none) {
        .card:hover {
            transform: none !important;
        }
    }
</style>

<!-- JavaScript Filter Integration -->
<script>
    $(document).ready(function () {
        var table = $('#classroomTable').DataTable({
            pageLength: 10
        });

        // Entries per page
        $('#entriesLength').on('change', function () {
            let length = parseInt($(this).val());
            table.page.len(length).draw();
        });

        // Search by section
        $('#sectionSearch').on('keyup', function () {
            table.columns(1).search(this.value).draw();
        });

        // Filter by grade level
        $('#gradeFilter').on('change', function () {
            table.columns(0).search(this.value).draw();
        });
    });
</script>



<?= $this->endSection() ?>