<?= $this->extend('backend/teacher/layout/pages-layout') ?>

<?= $this->section('content') ?>

<style>
    .avatar-sm {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .table th {
        font-weight: 600;
        font-size: 0.875rem;
        letter-spacing: 0.025em;
    }
    
    .badge {
        font-weight: 500;
        font-size: 0.75rem;
    }
    
    .bg-success-subtle {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }
    
    .bg-warning-subtle {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }
    
    .bg-primary-subtle {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }
    
    .bg-secondary-subtle {
        background-color: rgba(108, 117, 125, 0.1) !important;
    }
    
    .text-success {
        color: #198754 !important;
    }
    
    .text-warning {
        color: #ffc107 !important;
    }
    
    .text-primary {
        color: #0d6efd !important;
    }
    
    .text-secondary {
        color: #6c757d !important;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .dropdown-toggle::after {
        display: none;
    }
    
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        border-color: #6c757d;
    }
</style>

<!-- Modern Dashboard Header -->
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center bg-white text-dark rounded-3 shadow-sm p-4">
                <div>
                    <h2 class="mb-1 fw-bold text-primary">Student Report Cards</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?= site_url('teacher/dashboard') ?>" class="text-primary">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#" class="text-primary">Manage Students</a></li>
                            <li class="breadcrumb-item active text-muted" aria-current="page">Report Cards</li>
                        </ol>
                    </nav>
                </div>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                        <i class="dw dw-calendar1 me-2"></i>May 2025
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#">January 2025</a>
                        <a class="dropdown-item" href="#">February 2025</a>
                        <a class="dropdown-item" href="#">March 2025</a>
                        <a class="dropdown-item" href="#">April 2025</a>
                        <a class="dropdown-item active" href="#">May 2025</a>
                        <a class="dropdown-item" href="#">June 2025</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-3 shadow-sm p-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted fw-medium">Filter by:</span>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                <i class="dw dw-calendar me-2"></i>3rd Quarter
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">1st Quarter</a>
                                <a class="dropdown-item" href="#">2nd Quarter</a>
                                <a class="dropdown-item active" href="#">3rd Quarter</a>
                                <a class="dropdown-item" href="#">4th Quarter</a>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                <i class="dw dw-book me-2"></i>Grade 11 - Jupiter
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Grade 11 - Mathematics</a>
                                <a class="dropdown-item" href="#">Grade 11 - Science</a>
                                <a class="dropdown-item" href="#">Grade 11 - English</a>
                                <a class="dropdown-item" href="#">Grade 11 - Filipino</a>
                                <a class="dropdown-item active" href="#">Grade 11 - Jupiter</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-success btn-sm" type="button">
                            <i class="dw dw-add me-1"></i>Add Grade
                        </button>
                        <button class="btn btn-info btn-sm" type="button">
                            <i class="dw dw-download me-1"></i>Export All
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Report Cards Table Section -->
    <div class="row">
        <div class="col-12">
            <div class="bg-white rounded-3 shadow-sm mb-4">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                        <div>
                            <h4 class="text-primary fw-bold mb-1">Student Report Cards</h4>
                            <p class="text-muted mb-0">Manage and track student academic performance</p>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <!-- Search Input -->
                            <div class="input-group" style="width: 250px;">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="dw dw-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0" id="studentSearch" placeholder="Search students..." aria-label="Search">
                            </div>
                            <!-- Export Buttons -->
                            <div class="btn-group" role="group">
                                <button class="btn btn-outline-secondary btn-sm" type="button" title="Copy">
                                    <i class="dw dw-copy"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" type="button" title="Export CSV">
                                    <i class="dw dw-file-csv"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" type="button" title="Export PDF">
                                    <i class="dw dw-file-pdf"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" type="button" title="Print">
                                    <i class="dw dw-print"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="reportCardsTable">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="border-0 fw-semibold text-dark">Student</th>
                                    <th scope="col" class="border-0 fw-semibold text-dark">LRN</th>
                                    <th scope="col" class="border-0 fw-semibold text-dark">Grade Level</th>
                                    <th scope="col" class="border-0 fw-semibold text-dark text-center">1st Quarter</th>
                                    <th scope="col" class="border-0 fw-semibold text-dark text-center">2nd Quarter</th>
                                    <th scope="col" class="border-0 fw-semibold text-dark text-center">3rd Quarter</th>
                                    <th scope="col" class="border-0 fw-semibold text-dark text-center">4th Quarter</th>
                                    <th scope="col" class="border-0 fw-semibold text-dark text-center">Final Grade</th>
                                    <th scope="col" class="border-0 fw-semibold text-dark text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-bottom">
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <img src="https://via.placeholder.com/40x40/007bff/ffffff?text=JR" alt="James Ried" class="rounded-circle" width="40" height="40">
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-semibold">James Ried</h6>
                                                <small class="text-muted">Student #01</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-muted">112234567890</td>
                                    <td class="py-3 text-muted">11</td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">--</span></td>
                                    <td class="text-center py-3"><span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">--</span></td>
                                    <td class="text-center py-3">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-eye me-2"></i>View</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-edit2 me-2"></i>Edit</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-print me-2"></i>Print</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <img src="https://via.placeholder.com/40x40/28a745/ffffff?text=DP" alt="Daniel Padilla" class="rounded-circle" width="40" height="40">
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-semibold">Daniel Padilla</h6>
                                                <small class="text-muted">Student #02</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-muted">112264803683</td>
                                    <td class="py-3 text-muted">11</td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">--</span></td>
                                    <td class="text-center py-3"><span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">--</span></td>
                                    <td class="text-center py-3">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-eye me-2"></i>View</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-edit2 me-2"></i>Edit</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-print me-2"></i>Print</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <img src="https://via.placeholder.com/40x40/17a2b8/ffffff?text=KB" alt="Kathryn Bernardo" class="rounded-circle" width="40" height="40">
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-semibold">Kathryn Bernardo</h6>
                                                <small class="text-muted">Student #03</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-muted">112287145903</td>
                                    <td class="py-3 text-muted">11</td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">--</span></td>
                                    <td class="text-center py-3"><span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">--</span></td>
                                    <td class="text-center py-3">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-eye me-2"></i>View</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-edit2 me-2"></i>Edit</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-print me-2"></i>Print</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <img src="https://via.placeholder.com/40x40/ffc107/000000?text=NL" alt="Nadine Lustre" class="rounded-circle" width="40" height="40">
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-semibold">Nadine Lustre</h6>
                                                <small class="text-muted">Student #04</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-muted">112284902647</td>
                                    <td class="py-3 text-muted">11</td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">--</span></td>
                                    <td class="text-center py-3"><span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">--</span></td>
                                    <td class="text-center py-3">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-eye me-2"></i>View</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-edit2 me-2"></i>Edit</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-print me-2"></i>Print</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <img src="https://via.placeholder.com/40x40/6f42c1/ffffff?text=EG" alt="Enrique Gil" class="rounded-circle" width="40" height="40">
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-semibold">Enrique Gil</h6>
                                                <small class="text-muted">Student #05</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-muted">112267390123</td>
                                    <td class="py-3 text-muted">11</td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">--</span></td>
                                    <td class="text-center py-3"><span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">--</span></td>
                                    <td class="text-center py-3">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-eye me-2"></i>View</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-edit2 me-2"></i>Edit</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-print me-2"></i>Print</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <img src="https://via.placeholder.com/40x40/e83e8c/ffffff?text=LS" alt="Liza Soberano" class="rounded-circle" width="40" height="40">
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-semibold">Liza Soberano</h6>
                                                <small class="text-muted">Student #06</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-muted">112258713209</td>
                                    <td class="py-3 text-muted">11</td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">--</span></td>
                                    <td class="text-center py-3"><span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">--</span></td>
                                    <td class="text-center py-3">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-eye me-2"></i>View</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-edit2 me-2"></i>Edit</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-print me-2"></i>Print</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <img src="https://via.placeholder.com/40x40/fd7e14/ffffff?text=ME" alt="Maymay Entrata" class="rounded-circle" width="40" height="40">
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-semibold">Maymay Entrata</h6>
                                                <small class="text-muted">Student #07</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-muted">112238021789</td>
                                    <td class="py-3 text-muted">11</td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">--</span></td>
                                    <td class="text-center py-3"><span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">--</span></td>
                                    <td class="text-center py-3">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-eye me-2"></i>View</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-edit2 me-2"></i>Edit</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-print me-2"></i>Print</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <img src="https://via.placeholder.com/40x40/20c997/ffffff?text=JD" alt="Jane De Leon" class="rounded-circle" width="40" height="40">
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-semibold">Jane De Leon</h6>
                                                <small class="text-muted">Student #08</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-muted">112209154871</td>
                                    <td class="py-3 text-muted">11</td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">--</span></td>
                                    <td class="text-center py-3"><span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">--</span></td>
                                    <td class="text-center py-3">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-eye me-2"></i>View</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-edit2 me-2"></i>Edit</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-print me-2"></i>Print</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <img src="https://via.placeholder.com/40x40/6610f2/ffffff?text=JG" alt="Joshua Garcia" class="rounded-circle" width="40" height="40">
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-semibold">Joshua Garcia</h6>
                                                <small class="text-muted">Student #09</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-muted">112245819800</td>
                                    <td class="py-3 text-muted">11</td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">90</span></td>
                                    <td class="text-center py-3"><span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">--</span></td>
                                    <td class="text-center py-3"><span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">--</span></td>
                                    <td class="text-center py-3">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-eye me-2"></i>View</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-edit2 me-2"></i>Edit</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="dw dw-print me-2"></i>Print</a></li>
                                            </ul>
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

    <!-- Modern Pagination and Info Section -->
    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
        <div class="text-muted">
            <small><span id="showingInfo">Showing <strong>1-9</strong> of <strong>9</strong> students</span></small>
        </div>
        <nav aria-label="Table pagination">
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item" id="previousPage">
                    <a class="page-link" href="#" id="previousBtn" tabindex="-1">
                        <i class="dw dw-left-arrow-2"></i>
                    </a>
                </li>
                <li class="page-item active">
                    <a class="page-link" href="#" id="pageInfo">1</a>
                </li>
                <li class="page-item" id="nextPage">
                    <a class="page-link" href="#" id="nextBtn">
                        <i class="dw dw-right-arrow-2"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>



<!-- JavaScript for Table Functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Table elements
        const table = document.getElementById('reportCardsTable');
        const tbody = table.querySelector('tbody');
        const allRows = Array.from(tbody.querySelectorAll('tr'));
        
        // Control elements
        const entriesSelect = document.getElementById('entriesLength');
        const studentSearch = document.getElementById('studentSearch');
        const gradeFilter = document.getElementById('gradeFilter');
        const previousBtn = document.getElementById('previousBtn');
        const nextBtn = document.getElementById('nextBtn');
        const pageInfo = document.getElementById('pageInfo');
        const showingInfo = document.getElementById('showingInfo');
        
        // Pagination variables
        let currentPage = 1;
        let rowsPerPage = 10;
        let filteredRows = [...allRows];
        
        // Update table display
        function updateTable() {
            const totalRows = filteredRows.length;
            const totalPages = Math.ceil(totalRows / rowsPerPage);
            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = Math.min(startIndex + rowsPerPage, totalRows);
            
            // Hide all rows first
            allRows.forEach(row => row.style.display = 'none');
            
            // Show current page rows
            for (let i = startIndex; i < endIndex; i++) {
                if (filteredRows[i]) {
                    filteredRows[i].style.display = '';
                }
            }
            
            // Update pagination info
            pageInfo.textContent = `Page ${currentPage} of ${totalPages || 1}`;
            showingInfo.textContent = totalRows > 0 
                ? `Showing ${startIndex + 1} to ${endIndex} of ${totalRows} entries`
                : 'Showing 0 to 0 of 0 entries';
            
            // Update button states
            previousBtn.disabled = currentPage <= 1;
            nextBtn.disabled = currentPage >= totalPages;
            
            // Update row numbers
            filteredRows.forEach((row, index) => {
                const numberCell = row.querySelector('td:first-child');
                if (numberCell) {
                    numberCell.textContent = String(index + 1).padStart(2, '0');
                }
            });
        }
        
        // Filter rows based on search and grade filter
        function filterRows() {
            const searchTerm = studentSearch.value.toLowerCase();
            const gradeValue = gradeFilter ? gradeFilter.value : '';
            
            filteredRows = allRows.filter(row => {
                const studentCell = row.cells[1];
                const gradeCell = row.cells[3];
                
                const matchesSearch = !searchTerm || 
                    (studentCell && studentCell.textContent.toLowerCase().includes(searchTerm));
                
                const matchesGrade = !gradeValue || 
                    (gradeCell && gradeCell.textContent.trim() === gradeValue);
                
                return matchesSearch && matchesGrade;
            });
            
            currentPage = 1; // Reset to first page when filtering
            updateTable();
        }
        
        // Event listeners
        if (entriesSelect) {
            entriesSelect.addEventListener('change', function() {
                rowsPerPage = parseInt(this.value);
                currentPage = 1;
                updateTable();
            });
        }
        
        // Enhanced search input handling
        studentSearch.addEventListener('input', function() {
            // Delay filtering to improve performance while typing
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(filterRows, 300);
        });
        
        studentSearch.addEventListener('keyup', function(event) {
            if (event.key === 'Enter') {
                filterRows();
            }
        });
        
        if (gradeFilter) {
            gradeFilter.addEventListener('change', filterRows);
        }
        
        previousBtn.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                updateTable();
            }
        });
        
        nextBtn.addEventListener('click', function() {
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                updateTable();
            }
        });
        
        // Initialize table
        updateTable();
        
        // Bootstrap 5 dropdown initialization
        const dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
        const dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl);
        });
    });
</script>

<?= $this->endSection() ?>