<?= $this->extend('backend/layout/pages-layout') ?>

<?= $this->section('content') ?>
    <!-- Header Section with Teacher-style Design -->
    <div class="page-header bg-white rounded-3 p-4 mb-4" style="box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
        <div class="row align-items-center">
            <div class="col-md-6 col-sm-12">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <h4 class="text-primary fw-bold mb-1">Admin Dashboard</h4>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="<?= route_to('admin.home') ?>" class="text-decoration-none">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Admin Dashboard
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 d-flex justify-content-end align-items-center">
                <button class="btn btn-primary rounded-pill px-4 ms-auto" id="current-date-btn">
                    <span id="current-date">Loading...</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Welcome Section with Teacher-style Design -->
    <div class="welcome-section bg-white rounded-3 p-4 mb-4" style="box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="text-dark fw-bold mb-2">Welcome back! <?= session()->get('admin_name') ?? 'Administrator' ?></h2>
                <p class="text-muted mb-0">Welcome back! Manage your school operations with ease. Monitor academics, attendance, and administrative tasks all in one place.</p>
            </div>
            <div class="col-md-4 text-end d-flex justify-content-end">
                <img src="<?= base_url('backend/vendors/images/logo-login-removebg-preview.png') ?>" alt="Admin Profile" class="rounded-circle border border-3 border-light" width="100" height="100" style="object-fit: cover;">
            </div>
        </div>
    </div>



    <!-- Core Modules Statistics Cards -->
        <div class="row mb-4 g-3 g-md-4">
            <div class="col-12 col-sm-6 col-lg-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 hover-lift rounded-4">
                    <div class="card-body p-3 p-md-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <div class="text-muted small fw-semibold mb-1">STUDENTS</div>
                                <div class="display-6 fw-bold text-primary mb-0" data-counter="856">0</div>
                                <div class="d-flex align-items-center mt-2">
                                    <span class="badge bg-success-subtle text-success me-2 rounded-pill">
                                        <i class="fas me-1"></i>+12
                                    </span>
                                    <small class="text-muted">this month</small>
                                </div>
                            </div>
                            <div class="bg-primary-subtle rounded-4 p-3 d-none d-sm-block" style="box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
                                <i class="fas fa-user-graduate text-primary" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="progress mt-auto rounded-pill" style="height: 6px;">
                            <div class="progress-bar bg-primary rounded-pill" style="width: 75%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-6 col-lg-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 hover-lift rounded-4">
                    <div class="card-body p-3 p-md-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <div class="text-muted small fw-semibold mb-1">TEACHERS</div>
                                <div class="display-6 fw-bold text-success mb-0" data-counter="42">0</div>
                                <div class="d-flex align-items-center mt-2">
                                    <span class="badge bg-success-subtle text-success me-2 rounded-pill">
                                        <i class="fas me-1"></i>+2
                                    </span>
                                    <small class="text-muted">this month</small>
                                </div>
                            </div>
                            <div class="bg-success-subtle rounded-4 p-3 d-none d-sm-block" style="box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
                                <i class="fas fa-chalkboard-teacher text-success" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="progress mt-auto rounded-pill" style="height: 6px;">
                            <div class="progress-bar bg-success rounded-pill" style="width: 90%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-6 col-lg-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 hover-lift rounded-4">
                    <div class="card-body p-3 p-md-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <div class="text-muted small fw-semibold mb-1">PARENTS</div>
                                <div class="display-6 fw-bold text-warning mb-0" data-counter="734">0</div>
                                <div class="d-flex align-items-center mt-2">
                                    <span class="badge bg-info-subtle text-info me-2 rounded-pill">
                                        <i class="fas fa-users"></i>&nbsp;Active
                                    </span>
                                    <small class="text-muted">registered</small>
                                </div>
                            </div>
                            <div class="bg-warning-subtle rounded-4 p-3 d-none d-sm-block" style="box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
                                <i class="fas fa-users text-warning" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="progress mt-auto rounded-pill" style="height: 6px;">
                            <div class="progress-bar bg-warning rounded-pill" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-6 col-lg-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 hover-lift rounded-4">
                    <div class="card-body p-3 p-md-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <div class="text-muted small fw-semibold mb-1">ENROLLMENTS</div>
                                <div class="display-6 fw-bold text-info mb-0" data-counter="156">0</div>
                                <div class="d-flex align-items-center mt-2">
                                    <span class="badge bg-warning-subtle text-warning me-2 rounded-pill">
                                        <i class="fas fa-clock"></i>&nbsp;Pending
                                    </span>
                                    <small class="text-muted">this week</small>
                                </div>
                            </div>
                            <div class="bg-info-subtle rounded-4 p-3 d-none d-sm-block" style="box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
                                <i class="fas fa-clipboard-list text-info" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="progress mt-auto rounded-pill" style="height: 6px;">
                            <div class="progress-bar bg-info rounded-pill" style="width: 65%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional metric cards -->
        <div class="row mb-4 g-4 g-md-5">
            <div class="col-12 col-sm-6 col-lg-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 hover-lift rounded-4">
                    <div class="card-body p-3 p-md-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <div class="text-muted small fw-semibold mb-1">CLASS</div>
                                <div class="display-6 fw-bold text-danger mb-0" data-counter="32">0</div>
                                <div class="d-flex align-items-center mt-2">
                                    <span class="badge bg-danger-subtle text-danger me-2 rounded-pill">
                                        <i class="fas me-1"></i>+3
                                    </span>
                                    <small class="text-muted">this month</small>
                                </div>
                            </div>
                            <div class="bg-danger-subtle rounded-4 p-3 d-none d-sm-block" style="box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
                                <i class="fas fa-school text-danger" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="progress mt-auto rounded-pill" style="height: 6px;">
                            <div class="progress-bar bg-danger rounded-pill" style="width: 80%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-6 col-lg-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 hover-lift rounded-4">
                    <div class="card-body p-3 p-md-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <div class="text-muted small fw-semibold mb-1">EVENTS</div>
                                <div class="display-6 fw-bold text-info mb-0" data-counter="18">0</div>
                                <div class="d-flex align-items-center mt-2">
                                    <span class="badge bg-info-subtle text-info me-2 rounded-pill">
                                        <i class="fas fa-calendar-check"></i>&nbsp;Upcoming
                                    </span>
                                    <small class="text-muted">this month</small>
                                </div>
                            </div>
                            <div class="bg-info-subtle rounded-4 p-3 d-none d-sm-block" style="box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
                                <i class="fas fa-calendar-alt text-info" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="progress mt-auto rounded-pill" style="height: 6px;">
                            <div class="progress-bar bg-info rounded-pill" style="width: 70%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-6 col-lg-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 hover-lift rounded-4">
                    <div class="card-body p-3 p-md-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <div class="text-muted small fw-semibold mb-1">STUDENT ATTENDANCE</div>
                                <div class="display-6 fw-bold text-primary mb-0" data-counter="92">0</div>
                                <div class="d-flex align-items-center mt-2">
                                    <span class="badge bg-primary-subtle text-primary me-2 rounded-pill">
                                        <i class="fas fa-percentage"></i>&nbsp;Rate
                                    </span>
                                    <small class="text-muted">this week</small>
                                </div>
                            </div>
                            <div class="bg-primary-subtle rounded-4 p-3 d-none d-sm-block" style="box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
                                <i class="fas fa-check-circle text-primary" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="progress mt-auto rounded-pill" style="height: 6px;">
                            <div class="progress-bar bg-primary rounded-pill" style="width: 92%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-6 col-lg-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 hover-lift rounded-4">
                    <div class="card-body p-3 p-md-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <div class="text-muted small fw-semibold mb-1">COMPLETION</div>
                                <div class="display-6 fw-bold text-success mb-0" data-counter="87">0</div>
                                <div class="d-flex align-items-center mt-2">
                                    <span class="badge bg-success-subtle text-success me-2 rounded-pill">
                                        <i class="fas fa-tasks"></i>&nbsp;Rate
                                    </span>
                                    <small class="text-muted">assignments</small>
                                </div>
                            </div>
                            <div class="bg-success-subtle rounded-4 p-3 d-none d-sm-block" style="box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
                                <i class="fas fa-certificate text-success" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="progress mt-auto rounded-pill" style="height: 6px;">
                            <div class="progress-bar bg-success rounded-pill" style="width: 87%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    

    <!-- Quick Actions Row -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm h-100 rounded-4">
            <div class="card-header bg-white border-0 pb-0 rounded-top-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                    <h5 class="card-title fw-bold mb-2 mb-md-0">
                        <i class="fas fa-tachometer-alt text-primary"></i>&nbsp;&nbsp;Quick Actions
                    </h5>
                    <button class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="collapse" data-bs-target="#quickActions">
                        <i class="fas fa-chevron-down"></i>&nbsp;Toggle
                    </button>
                </div>
            </div>
            <div class="card-body collapse show py-3 py-md-4" id="quickActions">
                <div class="row g-2 g-sm-3 justify-content-center px-2 px-md-0">
                    <!-- Card 1: Add Student -->
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2 col-xxl-2">
                        <div class="position-relative w-100" style="padding-bottom: 85%;">
                            <button class="btn btn-outline-primary position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center rounded-3 rounded-sm-4 shadow-sm border-2" style="padding: 0.25rem;" data-bs-toggle="modal" data-bs-target="#actionModal">
                                <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                    <i class="fas fa-user-plus text-primary" style="font-size: clamp(1.1rem, 3vw, 2rem); margin-bottom: clamp(0.2rem, 0.8vw, 0.4rem);"></i>
                                    <span class="fw-semibold text-center" style="font-size: clamp(0.65rem, 1.6vw, 0.8rem); line-height: 1.1; word-break: break-word; hyphens: auto;">Add Student</span>
                                </div>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Card 2: Add Teacher -->
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2 col-xxl-2">
                        <div class="position-relative w-100" style="padding-bottom: 85%;">
                            <button class="btn btn-outline-success position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center rounded-3 rounded-sm-4 shadow-sm border-2" style="padding: 0.25rem;" data-bs-toggle="modal" data-bs-target="#actionModal">
                                <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                    <i class="fas fa-user-tie text-success" style="font-size: clamp(1.1rem, 3vw, 2rem); margin-bottom: clamp(0.2rem, 0.8vw, 0.4rem);"></i>
                                    <span class="fw-semibold text-center" style="font-size: clamp(0.65rem, 1.6vw, 0.8rem); line-height: 1.1; word-break: break-word; hyphens: auto;">Add Teacher</span>
                                </div>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Card 3: Schedule Class -->
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2 col-xxl-2">
                        <div class="position-relative w-100" style="padding-bottom: 85%;">
                            <button class="btn btn-outline-info position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center rounded-3 rounded-sm-4 shadow-sm border-2" style="padding: 0.25rem;" data-bs-toggle="modal" data-bs-target="#actionModal">
                                <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                    <i class="fas fa-calendar-plus text-info" style="font-size: clamp(1.1rem, 3vw, 2rem); margin-bottom: clamp(0.2rem, 0.8vw, 0.4rem);"></i>
                                    <span class="fw-semibold text-center" style="font-size: clamp(0.65rem, 1.6vw, 0.8rem); line-height: 1.1; word-break: break-word; hyphens: auto;">Schedule Class</span>
                                </div>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Card 4: Take Attendance -->
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2 col-xxl-2">
                        <div class="position-relative w-100" style="padding-bottom: 85%;">
                            <button class="btn btn-outline-warning position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center rounded-3 rounded-sm-4 shadow-sm border-2" style="padding: 0.25rem;" data-bs-toggle="modal" data-bs-target="#actionModal">
                                <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                    <i class="fas fa-clipboard-check text-warning" style="font-size: clamp(1.1rem, 3vw, 2rem); margin-bottom: clamp(0.2rem, 0.8vw, 0.4rem);"></i>
                                    <span class="fw-semibold text-center" style="font-size: clamp(0.65rem, 1.6vw, 0.8rem); line-height: 1.1; word-break: break-word; hyphens: auto;">Take Attendance</span>
                                </div>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Card 5: View Reports -->
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2 col-xxl-2">
                        <div class="position-relative w-100" style="padding-bottom: 85%;">
                            <button class="btn btn-outline-dark position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center rounded-3 rounded-sm-4 shadow-sm border-2" style="padding: 0.25rem;" data-bs-toggle="modal" data-bs-target="#actionModal">
                                <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                    <i class="fas fa-chart-bar text-dark" style="font-size: clamp(1.1rem, 3vw, 2rem); margin-bottom: clamp(0.2rem, 0.8vw, 0.4rem);"></i>
                                    <span class="fw-semibold text-center" style="font-size: clamp(0.65rem, 1.6vw, 0.8rem); line-height: 1.1; word-break: break-word; hyphens: auto;">View Reports</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Calendar and Recent Activities Row -->
    <div class="row mb-4 g-3 g-md-4">
        <!-- Academic Calendar Widget -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100 hover-lift rounded-4">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 mb-md-4">
                        <h5 class="card-title fw-bold mb-2 mb-md-0">
                            <i class="fas fa-calendar-alt text-primary"></i>&nbsp;&nbsp;Academic Calendar
                        </h5>   
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" style="font-size: 0.8rem;">View All</button>
                        </div>
                    </div>
                    <!-- Month Header -->
                    <div class="text-center mb-3">
                        <h6 class="mb-0 fw-bold text-dark" id="adminCurrentMonth">
                            <i class="fas fa-calendar-today text-primary"></i>&nbsp;January 2025
                        </h6>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-borderless table-sm text-center" id="adminCalendarTable">
                            <thead>
                                <tr class="border-bottom">
                                    <th class="text-muted fw-semibold py-2" style="min-width: 40px;">Sun</th>
                                    <th class="text-muted fw-semibold py-2" style="min-width: 40px;">Mon</th>
                                    <th class="text-muted fw-semibold py-2" style="min-width: 40px;">Tue</th>
                                    <th class="text-muted fw-semibold py-2" style="min-width: 40px;">Wed</th>
                                    <th class="text-muted fw-semibold py-2" style="min-width: 40px;">Thu</th>
                                    <th class="text-muted fw-semibold py-2" style="min-width: 40px;">Fri</th>
                                    <th class="text-muted fw-semibold py-2" style="min-width: 40px;">Sat</th>
                                </tr>
                            </thead>
                            <tbody id="adminCalendarBody">
                                <tr><td colspan="7" class="text-center p-3 text-muted">Loading calendar...</td></tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Event Legend -->
                    <div class="mt-3 pt-3 border-top d-none d-md-block">
                        <div class="d-flex flex-wrap justify-content-center" style="gap: 40px;">
                            <div class="d-flex align-items-center">
                                <span class="bg-primary rounded-circle" style="width: 8px; height: 8px; margin-right: 20px !important;"></span>
                                <small class="text-muted">Enrollment</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="bg-success rounded-circle" style="width: 8px; height: 8px; margin-right: 20px !important;"></span>
                                <small class="text-muted">Academic</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="bg-warning rounded-circle" style="width: 8px; height: 8px; margin-right: 20px !important;"></span>
                                <small class="text-muted">Events</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navigation Buttons -->
                    <div class="d-flex justify-content-end mt-3">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center" id="adminPrevMonth" style="width: 36px; height: 36px;"> 
                                <i class="fas fa-chevron-left" style="font-size: 0.75rem;"></i> 
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center" id="adminNextMonth" style="width: 36px; height: 36px;"> 
                                <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i> 
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications Panel -->
        <div class="col-lg-6 col-md-12">
            <div class="card border-0 shadow-sm h-100 hover-lift rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title fw-bold mb-0">Notifications</h5>
                        <button class="btn btn-primary btn-sm rounded-pill px-3">View All</button>
                    </div>
                    <div class="notification-list">
                        <div class="notification-item p-2 bg-danger-subtle rounded-3 border-start border-danger border-4 mb-2 border-bottom border-secondary">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-danger"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold text-danger">Urgent: System Maintenance</div>
                                    <div class="text-muted small">Scheduled maintenance tonight at 11 PM</div>
                                    <div class="text-muted small mt-1">
                                        <i class="fas fa-clock"></i>&nbsp;&nbsp;30 minutes ago
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="notification-item p-2 bg-warning-subtle rounded-3 border-start border-warning border-4 mb-2 border-bottom border-secondary">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-clipboard-list text-warning"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold text-warning">Enrollment Deadline</div>
                                    <div class="text-muted small">Registration closes in 3 days</div>
                                    <div class="text-muted small mt-1">
                                        <i class="fas fa-clock"></i>&nbsp;&nbsp;2 hours ago
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="notification-item p-2 bg-info-subtle rounded-3 border-start border-info border-4">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-info"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold text-info">New Feature Available</div>
                                    <div class="text-muted small">Parent portal messaging system is now live</div>
                                    <div class="text-muted small mt-1">
                                        <i class="fas fa-clock"></i>&nbsp;&nbsp;1 day ago
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrollment Statistics -->
    <div class="row mb-4 g-3 g-md-4">
        <!-- Enrollment Statistics -->
        <div class="col-12">
            <div class="card border-0 shadow-sm hover-lift rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 gap-2">
                        <h5 class="card-title fw-bold mb-0">Enrollment Statistics</h5>
                        <button class="btn btn-primary btn-sm rounded-pill px-3">View All</button>
                    </div>
                    <div class="row g-3 g-md-4">
                        <div class="col-md-3 text-center">
                            <div class="enrollment-metric p-3 bg-success-subtle rounded-4 hover-lift">
                                <div class="text-success fw-bold display-6 mb-2" data-counter="142">0</div>
                                <div class="text-muted fw-semibold">Approved</div>
                                <div class="progress mt-3 rounded-pill" style="height: 6px;">
                                    <div class="progress-bar bg-success rounded-pill" style="width: 85%"></div>
                                </div>
                                <div class="small text-muted mt-2">
                                    <i class="fas fa-check-circle me-2" style="margin-right: 12px !important;"></i>This month
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="enrollment-metric p-3 bg-warning-subtle rounded-4 hover-lift">
                                <div class="text-warning fw-bold display-6 mb-2" data-counter="23">0</div>
                                <div class="text-muted fw-semibold">Pending</div>
                                <div class="progress mt-3 rounded-pill" style="height: 6px;">
                                    <div class="progress-bar bg-warning rounded-pill" style="width: 45%"></div>
                                </div>
                                <div class="small text-muted mt-2">
                                    <i class="fas fa-clock me-1" style="margin-right: 12px !important;"></i>Awaiting review
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="enrollment-metric p-3 bg-info-subtle rounded-4 hover-lift">
                                <div class="text-info fw-bold display-6 mb-2" data-counter="67">0</div>
                                <div class="text-muted fw-semibold">In Progress</div>
                                <div class="progress mt-3 rounded-pill" style="height: 6px;">
                                    <div class="progress-bar bg-info rounded-pill" style="width: 70%"></div>
                                </div>
                                <div class="small text-muted mt-2">
                                    <i class="fas fa-edit me-1" style="margin-right: 12px !important;"></i>Documents pending
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="enrollment-metric p-3 bg-danger-subtle rounded-4 hover-lift">
                                <div class="text-danger fw-bold display-6 mb-2" data-counter="8">0</div>
                                <div class="text-muted fw-semibold">Rejected</div>
                                <div class="progress mt-3 rounded-pill" style="height: 6px;">
                                    <div class="progress-bar bg-danger rounded-pill" style="width: 15%"></div>
                                </div>
                                <div class="small text-muted mt-2">
                                    <i class="fas fa-times-circle me-1" style="margin-right: 12px !important;"></i>This month
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Enrollments Table -->
                    <div class="mt-4 pt-4 border-top">
                        <h6 class="fw-bold mb-3">Recent Enrollments</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                <thead>
                                    <tr class="text-muted small">
                                        <th class="fw-semibold">Student</th>
                                        <th class="fw-semibold d-none d-md-table-cell">Grade</th>
                                        <th class="fw-semibold">Status</th>
                                        <th class="fw-semibold d-none d-lg-table-cell">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="py-2">
                                            <div class="d-flex align-items-center">
                                                Maria Santos
                                            </div>
                                        </td>
                                        <td class="py-2 text-muted small d-none d-md-table-cell">10-A</td>
                                        <td class="py-2">
                                            <span class="badge bg-success-subtle text-success rounded-pill">Approved</span>
                                        </td>
                                        <td class="py-2 text-muted small d-none d-lg-table-cell">Today</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2">
                                            <div class="d-flex align-items-center">
                                                John Doe
                                            </div>
                                        </td>
                                        <td class="py-2 text-muted small d-none d-md-table-cell">9-B</td>
                                        <td class="py-2">
                                            <span class="badge bg-warning-subtle text-warning rounded-pill">Pending</span>
                                        </td>
                                        <td class="py-2 text-muted small d-none d-lg-table-cell">Yesterday</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2">
                                            <div class="d-flex align-items-center">
                                                Sarah Wilson
                                            </div>
                                        </td>
                                        <td class="py-2 text-muted small d-none d-md-table-cell">8-C</td>
                                        <td class="py-2">
                                            <span class="badge bg-info-subtle text-info rounded-pill">Review</span>
                                        </td>
                                        <td class="py-2 text-muted small d-none d-lg-table-cell">2 days ago</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2">
                                            <div class="d-flex align-items-center">
                                                Mike Johnson
                                            </div>
                                        </td>
                                        <td class="py-2 text-muted small d-none d-md-table-cell">11-A</td>
                                        <td class="py-2">
                                            <span class="badge bg-danger-subtle text-danger rounded-pill">Rejected</span>
                                        </td>
                                        <td class="py-2 text-muted small d-none d-lg-table-cell">3 days ago</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>  
    </div>

    <!-- Performance Overview and Quick Stats -->
    <div class="row mb-4 g-3 g-md-4">
        <!-- Academic Performance Chart -->
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm hover-lift rounded-4 h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center rounded-top-4">
                    <h5 class="card-title fw-bold mb-0">Academic Performance Overview</h5>
                    <div class="d-flex gap-3">                      
                        <button class="btn btn-primary btn-sm rounded-pill px-3">View All</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-3 text-center">
                            <div class="performance-metric p-4 bg-success-subtle rounded-4 hover-lift">
                                <div class="text-success fw-bold display-6 mb-2" data-counter="85">0%</div>
                                <div class="text-muted fw-semibold">Excellent</div>
                                <div class="progress mt-3 rounded-pill" style="height: 8px;">
                                    <div class="progress-bar bg-success rounded-pill" style="width: 85%"></div>
                                </div>
                                <div class="small text-muted mt-2">
                                    <i class="fas fa-star me-2"></i>728 students
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="performance-metric p-4 bg-info-subtle rounded-4 hover-lift">
                                <div class="text-info fw-bold display-6 mb-2" data-counter="12">0%</div>
                                <div class="text-muted fw-semibold">Good</div>
                                <div class="progress mt-3 rounded-pill" style="height: 8px;">
                                    <div class="progress-bar bg-info rounded-pill" style="width: 12%"></div>
                                </div>
                                <div class="small text-muted mt-2">
                                    <i class="fas fa-thumbs-up me-2"></i>103 students
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="performance-metric p-4 bg-warning-subtle rounded-4 hover-lift">
                                <div class="text-warning fw-bold display-6 mb-2" data-counter="2">0%</div>
                                <div class="text-muted fw-semibold">Needs Improvement</div>
                                <div class="progress mt-3 rounded-pill" style="height: 8px;">
                                    <div class="progress-bar bg-warning rounded-pill" style="width: 2%"></div>
                                </div>
                                <div class="small text-muted mt-2">
                                    <i class="fas fa-exclamation-triangle me-2"></i>17 students
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="performance-metric p-4 bg-danger-subtle rounded-4 hover-lift">
                                <div class="text-danger fw-bold display-6 mb-2" data-counter="1">0%</div>
                                <div class="text-muted fw-semibold">At Risk</div>
                                <div class="progress mt-3 rounded-pill" style="height: 8px;">
                                    <div class="progress-bar bg-danger rounded-pill" style="width: 1%"></div>
                                </div>
                                <div class="small text-muted mt-2">
                                    <i class="fas fa-exclamation-circle me-2"></i>8 students
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Main Content Row -->
    <div class="row mb-4 g-4">
        <!-- Recent Activities Timeline -->
        <div class="col-lg-4">
            <div class="div21 card border-0 shadow h-100 rounded-4" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center rounded-top-4">
                    <h5 class="card-title fw-bold mb-0">Recent Activities</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary btn-sm rounded-pill px-3" style="font-size: 0.8rem;">View All</button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="timeline">
                        <div class="timeline-item p-3 hover-lift mb-3">
                            <div class="row align-items-start g-2">
                                     <div class="col-auto">
                                     <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; box-shadow: 0 2px 6px rgba(0,0,0,0.08);">
                                         <i class="fas fa-user-plus text-white"></i>
                                     </div>
                                 </div>
                                 <div class="col">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold mb-1">New Student Enrolled</div>
                                            <div class="text-muted small mb-2">John Doe joined Class 10A</div>
                                            <div class="text-muted small">
                                                <i class="fas fa-clock me-2"></i>2 hours ago
                                            </div>
                                        </div>
                                        <div class="dropdown ms-3">
                                             <button class="btn btn-sm btn-outline-primary" data-bs-toggle="dropdown">
                                                 <i class="fas fa-ellipsis-v me-2"></i>
                                             </button>
                                            <ul class="dropdown-menu dropdown-menu-end rounded-4 border-0" style="box-shadow: 0 4px 12px rgba(0,0,0,0.12);">
                                                <li><a class="dropdown-item rounded-3" href="#"><i class="fas fa-eye me-2"></i>View Details</a></li>
                                                <li><a class="dropdown-item rounded-3" href="#"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-item p-3 hover-lift mb-3">
                            <div class="row align-items-start g-2">
                                 <div class="col-auto">
                                     <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; box-shadow: 0 2px 6px rgba(0,0,0,0.08);">
                                         <i class="fas fa-calendar text-white"></i>
                                     </div>
                                 </div>
                                 <div class="col">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold mb-1">Class Scheduled</div>
                                            <div class="text-muted small mb-2">Mathematics for Grade 9</div>
                                            <div class="text-muted small">
                                                <i class="fas fa-clock me-2"></i>4 hours ago
                                            </div>
                                        </div>
                                        <div class="dropdown ms-3">
                                             <button class="btn btn-sm btn-outline-primary" data-bs-toggle="dropdown">
                                                 <i class="fas fa-ellipsis-v me-2"></i>
                                             </button>
                                            <ul class="dropdown-menu dropdown-menu-end rounded-4 shadow-lg border-0">
                                                <li><a class="dropdown-item rounded-3" href="#"><i class="fas fa-eye me-2"></i>View Details</a></li>
                                                <li><a class="dropdown-item rounded-3" href="#"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-item p-3 hover-lift">
                            <div class="row align-items-start g-2">
                                 <div class="col-auto">
                                     <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; box-shadow: 0 2px 6px rgba(0,0,0,0.08);">
                                         <i class="fas fa-exclamation-triangle text-white"></i>
                                     </div>
                                 </div>
                                 <div class="col">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold mb-1">Attendance Alert</div>
                                            <div class="text-muted small mb-2">Class 8B below 90%</div>
                                            <div class="text-muted small">
                                                <i class="fas fa-clock me-2"></i>1 day ago
                                            </div>
                                        </div>
                                        <div class="dropdown ms-3">
                                             <button class="btn btn-sm btn-outline-primary" data-bs-toggle="dropdown">
                                                 <i class="fas fa-ellipsis-v me-2"></i>
                                             </button>
                                            <ul class="dropdown-menu dropdown-menu-end rounded-4 shadow-lg border-0">
                                                <li><a class="dropdown-item rounded-3" href="#"><i class="fas fa-eye me-2"></i>View Details</a></li>
                                                <li><a class="dropdown-item rounded-3" href="#"><i class="fas fa-bell me-2"></i>Set Reminder</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100 rounded-4 hover-lift">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center rounded-top-4">
                    <h5 class="card-title fw-bold mb-0">Upcoming Events</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary btn-sm rounded-pill px-3" style="font-size: 0.8rem;">View All</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="event-item mb-3 p-3 bg-primary-subtle rounded-4 border-start border-primary border-4 hover-lift">
                        <div class="d-flex align-items-start">
                            <div class="text-center me-4" style="min-width: 50px;">
                                <div class="fw-bold text-primary fs-4">15</div>
                                <div class="small text-muted">AUG</div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold mb-1">Parent-Teacher Meeting</div>
                                <div class="text-muted small mb-2">All grades • 2:00 PM - 5:00 PM</div>
                                <div class="d-flex flex-wrap gap-1">
                                    <span class="badge text-secondary">Important</span>
                                    <span class="badge text-secondary">
                                        <i class="fas" style="margin-right: 0.5rem !important;"></i>150 attendees
                                    </span>
                                </div>
                            </div>
                            <div class="dropdown ms-3">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v me-2"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end rounded-4 shadow-lg border-0">
                                    <li><a class="dropdown-item rounded-3" href="#"><i class="fas fa-eye me-2"></i>View Details</a></li>
                                    <li><a class="dropdown-item rounded-3" href="#"><i class="fas fa-edit me-2"></i>Edit Event</a></li>
                                    <li><a class="dropdown-item rounded-3" href="#"><i class="fas fa-share me-2"></i>Share</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="event-item mb-3 p-3 bg-success-subtle rounded-4 border-start border-success border-4 hover-lift">
                        <div class="d-flex align-items-start">
                            <div class="text-center me-4" style="min-width: 50px;">
                                <div class="fw-bold text-primary fs-4">20</div>
                                <div class="small text-muted">AUG</div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold mb-1">Science Fair</div>
                                <div class="text-muted small mb-2">Grades 6-10 • All Day Event</div>
                                <div class="d-flex flex-wrap gap-1">
                                    <span class="badge text-secondary">Academic</span>
                                    <span class="badge text-secondary">
                                        <i class="fas fa-trophy" style="margin-right: 0.5rem !important;"></i>Competition
                                    </span>
                                </div>
                            </div>
                            <div class="dropdown ms-3">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v me-2"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end rounded-4 shadow-lg border-0">
                                    <li><a class="dropdown-item rounded-3" href="#"><i class="fas fa-eye me-2"></i>View Details</a></li>
                                    <li><a class="dropdown-item rounded-3" href="#"><i class="fas fa-edit me-2"></i>Edit Event</a></li>
                                    <li><a class="dropdown-item rounded-3" href="#"><i class="fas fa-share me-2"></i>Share</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="event-item p-3 bg-warning-subtle rounded-4 border-start border-warning border-4 hover-lift">
                        <div class="d-flex align-items-start">
                            <div class="text-center me-4" style="min-width: 50px;">
                                <div class="fw-bold text-primary fs-4">25</div>
                                <div class="small text-muted">AUG</div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold mb-1">Mid-term Exams Begin</div>
                                <div class="text-muted small mb-2">All grades • Exam Schedule</div>
                                <div class="d-flex flex-wrap gap-1">
                                    <span class="badge text-secondary">Exam</span>
                                    <span class="badge text-secondary">
                                        <i class="fas fa-clock" style="margin-right: 0.5rem !important;"></i>5 days
                                    </span>
                                </div>
                            </div>
                            <div class="dropdown ms-3">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v me-2"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end rounded-4 shadow-lg border-0">
                                    <li><a class="dropdown-item rounded-3" href="#"><i class="fas fa-eye me-2"></i>View Schedule</a></li>
                                    <li><a class="dropdown-item rounded-3" href="#"><i class="fas fa-edit me-2"></i>Edit Schedule</a></li>
                                    <li><a class="dropdown-item rounded-3" href="#"><i class="fas fa-print me-2"></i>Print</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status Monitor -->
        <div class="col-lg-4">
            <div class="div23 card border-0 shadow h-100 rounded-4" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center rounded-top-4">
                    <h5 class="card-title fw-bold mb-0">System Status</h5>
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="tooltip" title="System Health">
                            <i class="fas fa-heartbeat me-2"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="status-item d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded-4 hover-lift">
                        <div class="d-flex align-items-center">
                            <div>
                                <span class="fw-semibold">Database</span>
                                <div class="small text-muted">MySQL 8.0 Server</div>
                            </div>
                        </div>
                        <span class="badge bg-success rounded-pill">Online</span>
                    </div>
                    <div class="status-item d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded-4 hover-lift">
                        <div class="d-flex align-items-center">
                            <div>
                                <span class="fw-semibold">Security System</span>
                                <div class="small text-muted">Auto Backup Active</div>
                            </div>
                        </div>
                        <span class="badge bg-success rounded-pill">Active</span>
                    </div>
                    <div class="status-item d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded-4 hover-lift">
                        <div class="d-flex align-items-center">
                            <div>
                                <span class="fw-semibold">Email Service</span>
                                <div class="small text-muted">SMTP Server</div>
                            </div>
                        </div>
                        <span class="badge bg-success rounded-pill">Working</span>
                    </div>
                    <div class="status-item mb-3 p-3 bg-light rounded-4 hover-lift">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="fw-semibold">Storage Usage</span>
                                    <div class="small text-muted">Server Storage</div>
                                </div>
                            </div>
                            <span class="badge bg-warning text-dark rounded-pill">78%</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 8px;">
                            <div class="progress-bar bg-warning rounded-pill" style="width: 78%"></div>
                        </div>
                        <div class="small text-muted mt-1">156 GB of 200 GB used</div>
                    </div>
                    <div class="status-item p-3 bg-light rounded-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-semibold">Last Backup</span>
                                <div class="small text-muted">
                                    <i class="fas fa-clock"></i>&nbsp;Automated
                                </div>
                            </div>
                            <span class="text-muted small">2 hours ago</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Action Modal -->
<div class="modal fade" id="actionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4" style="box-shadow: 0 4px 12px rgba(0,0,0,0.12) !important;">
            <div class="modal-header border-0 bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold">Quick Action</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="bg-primary-subtle rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-rocket text-primary" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <h6 class="text-center mb-3">Feature Coming Soon!</h6>
                <p class="text-center text-muted">This feature is currently under development and will be available in the next update. Thank you for your patience!</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-primary rounded-pill" data-bs-dismiss="modal">
                        <i class="fas fa-bell me-2"></i>Notify Me
                    </button>
                    <button type="button" class="btn btn-primary rounded-pill" data-bs-dismiss="modal">
                        <i class="fas fa-check me-2"></i>Got It
                    </button>
                </div>
            </div>
        </div>
    </div>

<!-- Enhanced JavaScript for Interactivity -->
<script>
// Real-time clock and date
function updateDateTime() {
    const now = new Date();
    const dateElement = document.getElementById('current-date');
    const lastUpdateElement = document.getElementById('last-update');
    
    if (dateElement) {
        dateElement.textContent = now.toLocaleDateString('en-US', {
            month: 'long',
            year: 'numeric'
        });
    }
    
    if (lastUpdateElement) {
        lastUpdateElement.textContent = 'Just now';
    }
}

// Animated counter function
function animateCounter(element, start, end, duration, suffix = '') {
    const startTime = performance.now();
    
    function updateCounter(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        const easeOutQuart = 1 - Math.pow(1 - progress, 4);
        const current = Math.floor(start + (end - start) * easeOutQuart);
        
        element.textContent = current + suffix;
        
        if (progress < 1) {
            requestAnimationFrame(updateCounter);
        } else {
            element.textContent = end + suffix;
        }
    }
    
    requestAnimationFrame(updateCounter);
}

// Initialize counters
function initializeCounters() {
    const counters = document.querySelectorAll('[data-counter]');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                const target = parseInt(element.getAttribute('data-counter'));
                const isPercentage = element.textContent.includes('%');
                const suffix = isPercentage ? '%' : '';
                
                animateCounter(element, 0, target, 2000, suffix);
                observer.unobserve(element);
            }
        });
    }, { threshold: 0.5 });
    
    counters.forEach(counter => {
        observer.observe(counter);
    });
}

// Enhanced hover effects
function initializeHoverEffects() {
    const hoverElements = document.querySelectorAll('.hover-lift');
    
    hoverElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
            this.style.transition = 'all 0.3s ease';
            this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.1)';
        });
        
        element.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
        });
    });
}



// Auto-refresh simulation
function simulateDataUpdate() {
    const badges = document.querySelectorAll('.badge');
    const randomBadge = badges[Math.floor(Math.random() * badges.length)];
    
    if (randomBadge && randomBadge.textContent.includes('Online')) {
        randomBadge.style.animation = 'pulse 1s ease-in-out';
        setTimeout(() => {
            randomBadge.style.animation = '';
        }, 1000);
    }
}

// Enhanced notification system
function showNotification(message, type = 'info') {
    const toastContainer = document.createElement('div');
    toastContainer.className = 'position-fixed top-0 end-0 p-3';
    toastContainer.style.zIndex = '9999';
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0 rounded-4 shadow-lg`;
    toast.setAttribute('role', 'alert');
    
    const icons = {
        success: 'fas fa-check-circle',
        info: 'fas fa-info-circle',
        warning: 'fas fa-exclamation-triangle',
        danger: 'fas fa-exclamation-circle'
    };
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center">
                <i class="${icons[type] || icons.info} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    document.body.appendChild(toastContainer);
    
    const bsToast = new bootstrap.Toast(toast, { delay: 4000 });
    bsToast.show();
    
    toast.addEventListener('hidden.bs.toast', () => {
        document.body.removeChild(toastContainer);
    });
}

// Initialize tooltips
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Admin Calendar Widget
class AdminCalendar {
    constructor() {
        this.currentDate = new Date();
        this.events = {
            // Academic events
            '2025-01-15': { count: 2, types: ['enrollment', 'academic'] },
            '2025-01-22': { count: 1, types: ['events'] },
            '2025-01-28': { count: 3, types: ['enrollment', 'academic', 'events'] },
            '2025-02-05': { count: 1, types: ['academic'] },
            '2025-02-14': { count: 2, types: ['events', 'enrollment'] },
            '2025-02-20': { count: 1, types: ['academic'] }
        };
        this.currentMonth = this.currentDate.getMonth();
        this.currentYear = this.currentDate.getFullYear();
        this.init();
    }

    init() {
        this.generateCalendar();
        this.bindEvents();
    }

    bindEvents() {
        const prevBtn = document.getElementById('adminPrevMonth');
        const nextBtn = document.getElementById('adminNextMonth');
        
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                this.currentMonth--;
                if (this.currentMonth < 0) {
                    this.currentMonth = 11;
                    this.currentYear--;
                }
                this.generateCalendar();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                this.currentMonth++;
                if (this.currentMonth > 11) {
                    this.currentMonth = 0;
                    this.currentYear++;
                }
                this.generateCalendar();
            });
        }
    }

    generateCalendar() {
        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        // Update month header
        const monthHeader = document.getElementById('adminCurrentMonth');
        if (monthHeader) {
            monthHeader.textContent = `${monthNames[this.currentMonth]} ${this.currentYear}`;
        }

        // Get first day of month and number of days
        const firstDay = new Date(this.currentYear, this.currentMonth, 1).getDay();
        const daysInMonth = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
        const daysInPrevMonth = new Date(this.currentYear, this.currentMonth, 0).getDate();

        let html = '';
        let date = 1;
        let nextMonthDate = 1;

        // Generate 6 weeks (42 days)
        for (let week = 0; week < 6; week++) {
            html += '<tr>';
            
            for (let day = 0; day < 7; day++) {
                const cellIndex = week * 7 + day;
                
                if (cellIndex < firstDay) {
                    // Previous month dates
                    const prevDate = daysInPrevMonth - firstDay + cellIndex + 1;
                    html += `<td class="text-muted py-2">
                        <div class="d-flex flex-column align-items-center">
                            <span style="font-size: 13px;">${prevDate}</span>
                        </div>
                    </td>`;
                } else if (date <= daysInMonth) {
                    // Current month dates
                    const dateStr = `${this.currentYear}-${String(this.currentMonth + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
                    const eventData = this.events[dateStr];
                    const isToday = this.isToday(date);
                    
                    html += `<td class="py-2">
                        <div class="d-flex flex-column align-items-center position-relative">
                            <span class="${isToday ? 'bg-primary text-white rounded-circle px-2 py-1 fw-bold' : ''}" style="font-size: 14px; min-width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">${date}</span>
                            ${this.generateEventDots(eventData)}
                        </div>
                    </td>`;
                    date++;
                } else {
                    // Next month dates
                    html += `<td class="text-muted py-2">
                        <div class="d-flex flex-column align-items-center">
                            <span style="font-size: 13px;">${nextMonthDate}</span>
                        </div>
                    </td>`;
                    nextMonthDate++;
                }
            }
            
            html += '</tr>';
            
            // Break if we've filled all days of current month and some next month days
            if (date > daysInMonth && nextMonthDate > 7) break;
        }

        const calendarBody = document.getElementById('adminCalendarBody');
        if (calendarBody) {
            calendarBody.innerHTML = html;
        }
    }

    generateEventDots(eventData) {
        if (!eventData || eventData.count === 0) return '';
        
        let dots = '<div class="d-flex justify-content-center mt-1">';
        
        // Color mapping for event types
        const colorMap = {
            'enrollment': 'bg-primary',
            'academic': 'bg-success', 
            'events': 'bg-warning'
        };
        
        // Show up to 3 dots
        const displayCount = Math.min(eventData.count, 3);
        
        for (let i = 0; i < displayCount; i++) {
            const eventType = eventData.types[i] || 'events';
            const color = colorMap[eventType] || 'bg-secondary';
            dots += `<span class="${color} rounded-circle me-1" style="width: 6px; height: 6px; display: inline-block;"></span>`;
        }
        
        // If more than 3 events, show a "+" indicator
        if (eventData.count > 3) {
            dots += '<span class="text-muted small ms-1">+</span>';
        }
        
        dots += '</div>';
        return dots;
    }

    isToday(date) {
        const today = new Date();
        return date === today.getDate() && 
               this.currentMonth === today.getMonth() && 
               this.currentYear === today.getFullYear();
    }
}

// Mobile optimization functions
function initMobileOptimizations() {
    const cards = document.querySelectorAll('.card');
    
    // Add touch-friendly hover effects
    cards.forEach(card => {
        card.addEventListener('touchstart', function() {
            this.style.transform = 'scale(0.98)';
            this.style.transition = 'transform 0.2s ease';
        }, { passive: true });
        
        card.addEventListener('touchend', function() {
            this.style.transform = 'scale(1)';
        }, { passive: true });
    });
}

// Responsive calendar improvements
function optimizeCalendarForMobile() {
    const calendar = document.getElementById('adminCalendarTable');
    
    if (calendar && window.innerWidth < 768) {
        // Add swipe gestures for mobile
        let startX = 0;
        
        calendar.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
        }, { passive: true });
        
        calendar.addEventListener('touchend', (e) => {
            const endX = e.changedTouches[0].clientX;
            const diff = startX - endX;
            
            if (Math.abs(diff) > 50) {
                if (diff > 0) {
                    document.getElementById('adminNextMonth')?.click();
                } else {
                    document.getElementById('adminPrevMonth')?.click();
                }
            }
        }, { passive: true });
    }
}

// Dynamic Bootstrap classes adjustment
function adjustBootstrapClasses() {
    const isMobile = window.innerWidth < 768;
    const isTablet = window.innerWidth < 992;
    
    // Adjust button sizes for mobile
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
        if (isMobile && !btn.classList.contains('btn-lg')) {
            // Ensure buttons are touch-friendly on mobile
            btn.style.minHeight = '44px';
        }
    });
    
    // Adjust card padding dynamically
    const cardBodies = document.querySelectorAll('.card-body');
    cardBodies.forEach(body => {
        if (isMobile) {
            body.classList.remove('p-4');
            body.classList.add('p-3');
        } else if (!body.classList.contains('p-md-4')) {
            body.classList.remove('p-3');
            body.classList.add('p-4');
        }
    });
}

// Enhanced hover effects with better performance
function initializeEnhancedHoverEffects() {
    const hoverElements = document.querySelectorAll('.hover-lift');
    
    hoverElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            if (window.innerWidth >= 768) { // Only on non-mobile devices
                this.style.transform = 'translateY(-4px)';
                this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                this.style.boxShadow = '0 6px 16px rgba(0,0,0,0.1)';
            }
        });
        
        element.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
        });
    });
}

// Initialize all features
document.addEventListener('DOMContentLoaded', function() {
    updateDateTime();
    setInterval(updateDateTime, 1000);
    
    initializeCounters();
    initializeEnhancedHoverEffects();
    initializeTooltips();
    initMobileOptimizations();
    
    // Initialize admin calendar
    if (document.getElementById('adminCalendarBody')) {
        new AdminCalendar();
        optimizeCalendarForMobile();
    }
    
    // Adjust Bootstrap classes on load and resize
    adjustBootstrapClasses();
    window.addEventListener('resize', () => {
        adjustBootstrapClasses();
        optimizeCalendarForMobile();
    });
    
    // Simulate periodic updates
    setInterval(simulateDataUpdate, 30000);
    
    // Show welcome notification
    setTimeout(() => {
        showNotification('Dashboard loaded successfully! All systems operational.', 'success');
    }, 1000);
    
    // Add click handlers for quick actions
    document.querySelectorAll('[data-bs-toggle="modal"]').forEach(button => {
        button.addEventListener('click', function() {
            const action = this.querySelector('.small')?.textContent || 'Action';
            showNotification(`${action} feature accessed!`, 'info');
        });
    });
    
    // Add refresh handlers
    document.querySelectorAll('[data-bs-toggle="tooltip"][title*="Refresh"]').forEach(button => {
        button.addEventListener('click', function() {
            this.style.animation = 'spin 1s linear';
            setTimeout(() => {
                this.style.animation = '';
                showNotification('Data refreshed successfully!', 'success');
            }, 1000);
        });
    });
});

// Responsive adjustments
function adjustForMobile() {
    const isMobile = window.innerWidth < 768;
    const cards = document.querySelectorAll('.card');
    
    cards.forEach(card => {
        if (isMobile) {
            card.classList.add('mb-3');
        } else {
            card.classList.remove('mb-3');
        }
    });
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .dark-mode {
        background-color: #1a1a1a !important;
        color: #ffffff !important;
    }
    .dark-mode .card {
        background-color: #2d2d2d !important;
        color: #ffffff !important;
    }
    .dark-mode .bg-light {
        background-color: #3d3d3d !important;
    }
`;
document.head.appendChild(style);

window.addEventListener('resize', adjustForMobile);
adjustForMobile();
</script>

<?= $this->endSection() ?>