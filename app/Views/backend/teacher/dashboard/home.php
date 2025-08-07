<?= $this->extend('backend/teacher/layout/pages-layout') ?>

<?= $this->section('stylesheets') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Modern Dashboard Header -->
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center bg-white text-dark rounded-3 shadow-sm p-4">
                <div>
                    <h2 class="mb-1 fw-bold text-primary">Dashboard</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?= route_to('admin.home')?>" class="text-primary">Home</a></li>
                            <li class="breadcrumb-item active text-muted" aria-current="page">Dashboard</li>
                        </ol>
                    </nav>
                </div>
                <div class="bg-primary text-white px-3 py-2 rounded-3">
                    <span id="currentDate" class="fw-bold">May 2025</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-3 shadow-sm p-3 p-md-4 transition-all hover-translate-up">
                <div class="row align-items-center">
                    <div class="col-lg-8 col-md-7 col-12">
                        <h3 class="mb-2 fw-bold fs-4 fs-md-3">Welcome back! Mr. Christian Jay Gonzaga</h3>
                        <p class="text-muted mb-0 fs-6">Welcome back! We're here to support you as you guide and inspire your students. Step into your classes with confidence and continue shaping the future, one lesson at a time.</p>
                    </div>
                    <div class="col-lg-4 col-md-5 col-12 text-center text-md-end mt-3 mt-md-0">
                        <img src="/backend/vendors/images/teacher-avatar.png" alt="Teacher Avatar" class="rounded-circle" width="80" height="80" style="object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4 g-3">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
            <div class="bg-white rounded-3 shadow-sm p-3 p-md-4 h-100 transition-all hover-translate-up">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="bg-success bg-opacity-10 p-3 rounded-3">
                        <i class="fas fa-graduation-cap text-success fs-4"></i>
                    </div>
                    <span class="badge bg-success">This Semester</span>
                </div>
                <h3 class="fw-bold mb-1">405</h3>
                <p class="text-muted mb-0">Total Students</p>
                <div class="progress mt-2" style="height: 4px;">
                    <div class="progress-bar bg-success" style="width: 85%"></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
            <div class="bg-white rounded-3 shadow-sm p-3 p-md-4 h-100 transition-all hover-translate-up">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                        <i class="fas fa-clock text-primary fs-4"></i>
                    </div>
                    <span class="badge bg-primary">Present</span>
                </div>
                <h3 class="fw-bold mb-1 fs-5 fs-md-4">Yesterday, 9:00 AM</h3>
                <p class="text-muted mb-0">Last Attendance</p>
                <p class="text-success mb-0 small">Monthly Attendance Rate: <strong>89%</strong></p>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
            <div class="bg-white rounded-3 shadow-sm p-3 p-md-4 h-100 transition-all hover-translate-up">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-3">
                        <i class="fas fa-calendar-alt text-warning fs-4"></i>
                    </div>
                    <span class="badge bg-warning">Upcoming</span>
                </div>
                <h3 class="fw-bold mb-1 fs-5 fs-md-4">Grade 11 - Jupiter</h3>
                <p class="text-muted mb-0">Next Exam</p>
                <p class="text-info mb-0 small">May 22, 2025 <span class="text-success">3 Days Left</span></p>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
            <div class="bg-white rounded-3 shadow-sm p-3 p-md-4 h-100 transition-all hover-translate-up">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="bg-info bg-opacity-10 p-3 rounded-3">
                        <i class="fas fa-bullhorn text-info fs-4"></i>
                    </div>
                    <span class="badge bg-info">New</span>
                </div>
                <h3 class="fw-bold mb-1 fs-5 fs-md-4">End of The Year Concert</h3>
                <p class="text-muted mb-0">Latest Announcement</p>
                <p class="text-muted mb-0 small">Please join us for the end of the year concert on June 2nd at 6:00 pm in...</p>
                <a href="#" class="text-decoration-none small">Read More</a>
            </div>
        </div>
    </div>

    <!-- Student Attendance Chart and Calendar Widget Row -->
    <div class="row mb-4 g-3">
        <!-- Student Attendance Chart -->
        <div class="col-xl-8 col-lg-12 col-md-12 col-12">
            <div class="bg-white rounded-3 shadow-sm p-3 p-md-4 h-100 transition-all hover-translate-up">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
                    <h5 class="fw-bold mb-0">Student Attendance</h5>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            2024-2025
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">2024-2025</a></li>
                            <li><a class="dropdown-item" href="#">2023-2024</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="row g-3">
                    <div class="col-lg-4 col-md-5 col-12">
                        <div class="text-center">
                            <div class="position-relative d-inline-block">
                                <canvas id="attendanceChart" width="120" height="120"></canvas>
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <h4 class="fw-bold mb-0">80%</h4>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="d-flex flex-column flex-sm-row justify-content-center gap-2 gap-sm-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="bg-primary rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                                        <small>Present</small>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="bg-warning rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                                        <small>Late</small>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="bg-danger rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                                        <small>Absent</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-7 col-12">
                        <div style="height: 200px;">
                            <canvas id="attendanceBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Calendar Widget -->
        <div class="col-xl-4 col-lg-12 col-md-12 col-12">
            <div class="bg-white rounded-3 shadow-sm p-3 p-md-4 h-100 transition-all hover-translate-up">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2">
                    <h5 class="fw-bold mb-0 text-center text-sm-start">May 2025</h5>
                    <div class="d-flex gap-1 align-self-center align-self-sm-auto">
                        <button class="btn btn-sm btn-outline-secondary px-3 py-2" id="prevMonth">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary px-3 py-2" id="nextMonth">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                
                <div class="calendar-container">
                    <div class="row text-center small text-muted mb-2 g-0">
                        <div class="col calendar-header">SUN</div>
                        <div class="col calendar-header">MON</div>
                        <div class="col calendar-header">TUE</div>
                        <div class="col calendar-header">WED</div>
                        <div class="col calendar-header">THU</div>
                        <div class="col calendar-header">FRI</div>
                        <div class="col calendar-header">SAT</div>
                    </div>
                    <div id="calendarDates" class="calendar-dates-grid"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notice Board - Unified Content Block -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-3 shadow-sm p-3 p-md-4 transition-all hover-translate-up">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
                    <h5 class="fw-bold mb-0">Notice Board</h5>
                    <a href="#" class="text-decoration-none small">View all</a>
                </div>
                
                <div class="row g-3">
                    <div class="col-lg-6 col-md-12 col-12">
                        <div class="d-flex align-items-start p-3 bg-warning bg-opacity-10 rounded-3 border-start border-warning border-4 transition-all hover-translate-x h-100">
                            <div class="bg-warning rounded-3 p-2 me-3 flex-shrink-0">
                                <i class="fas fa-bell text-white"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">Sports Day Announcement</h6>
                                <p class="text-muted small mb-0">The school's Annual Sports Day will be held on May 12, 2024. Mark your calendars!</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-12">
                        <div class="d-flex align-items-start p-3 bg-info bg-opacity-10 rounded-3 border-start border-info border-4 transition-all hover-translate-x h-100">
                            <div class="bg-info rounded-3 p-2 me-3 flex-shrink-0">
                                <i class="fas fa-calendar text-white"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">Summer Break Start Date</h6>
                                <p class="text-muted small mb-0">Summer break begins on May 25, 2024. Have a wonderful holiday!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row g-3">
        <!-- Left Column - Today's Schedule -->
        <div class="col-lg-6 col-md-12 col-12">
            <!-- Today's Schedule -->
            <div class="bg-white rounded-3 shadow-sm p-3 p-md-4 h-100 transition-all hover-translate-up">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
                    <h5 class="fw-bold mb-0">Today's Schedule</h5>
                    <span class="badge bg-primary">Monday</span>
                </div>
                
                <div>
                    <div class="d-flex align-items-center p-3 mb-3 bg-light rounded-3 border-start border-primary border-3 transition-all hover-translate-x">
                        <div class="bg-primary text-white rounded-2 px-3 py-2 me-3">
                            <small class="fw-bold">09:00</small>
                        </div>
                        <div>
                            <h6 class="mb-1">Grade 7 - Mathematics</h6>
                            <small class="text-muted">Room 101</small>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center p-3 mb-3 bg-light rounded-3 border-start border-success border-3 transition-all hover-translate-x">
                        <div class="bg-success text-white rounded-2 px-3 py-2 me-3">
                            <small class="fw-bold">11:00</small>
                        </div>
                        <div>
                            <h6 class="mb-1">Grade 8 - Algebra</h6>
                            <small class="text-muted">Room 102</small>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center p-3 mb-3 bg-light rounded-3 border-start border-warning border-3 transition-all hover-translate-x">
                        <div class="bg-warning text-white rounded-2 px-3 py-2 me-3">
                            <small class="fw-bold">14:00</small>
                        </div>
                        <div>
                            <h6 class="mb-1">Grade 9 - Geometry</h6>
                            <small class="text-muted">Room 103</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Students Overview -->
        <div class="col-lg-6 col-md-12 col-12">
            <!-- Students Overview -->
            <div class="bg-white rounded-3 shadow-sm p-3 p-md-4 h-100 transition-all hover-translate-up">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
                    <h5 class="fw-bold mb-0">Students</h5>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Monthly
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Weekly</a></li>
                            <li><a class="dropdown-item" href="#">Monthly</a></li>
                            <li><a class="dropdown-item" href="#">Yearly</a></li>
                        </ul>
                    </div>
                </div>
                
                <div>
                    <div class="d-flex align-items-center justify-content-between p-3 mb-3 bg-light rounded-3 transition-all hover-translate-x">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <span class="fw-bold">S</span>
                            </div>
                            <div>
                                <h6 class="mb-0">Samantha</h6>
                                <small class="text-muted">Grade 7</small>
                            </div>
                        </div>
                        <span class="badge bg-success">Done</span>
                    </div>
                    
                    <div class="d-flex align-items-center justify-content-between p-3 mb-3 bg-light rounded-3 transition-all hover-translate-x">
                        <div class="d-flex align-items-center">
                            <div class="bg-info rounded-circle text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <span class="fw-bold">J</span>
                            </div>
                            <div>
                                <h6 class="mb-0">Joshua</h6>
                                <small class="text-muted">Grade 8</small>
                            </div>
                        </div>
                        <span class="badge bg-warning">On-time</span>
                    </div>
                    
                    <div class="d-flex align-items-center justify-content-between p-3 mb-3 bg-light rounded-3 transition-all hover-translate-x">
                        <div class="d-flex align-items-center">
                            <div class="bg-secondary rounded-circle text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <span class="fw-bold">R</span>
                            </div>
                            <div>
                                <h6 class="mb-0">Rose</h6>
                                <small class="text-muted">Grade 7</small>
                            </div>
                        </div>
                        <span class="badge bg-secondary">Missed</span>
                    </div>
                    
                    <div class="d-flex align-items-center justify-content-between p-3 mb-3 bg-light rounded-3 transition-all hover-translate-x">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning rounded-circle text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <span class="fw-bold">M</span>
                            </div>
                            <div>
                                <h6 class="mb-0">Mark</h6>
                                <small class="text-muted">Grade 9</small>
                            </div>
                        </div>
                        <span class="badge bg-info">Late</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-3 shadow-sm mb-4 w-100 transition-all hover-translate-up">
        <div class="p-3 p-md-4 border-bottom">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <h4 class="text-primary h4 mb-0 fw-bold">Student Task Table</h4>
                <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-md-auto">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Add New Task">
                        <i class="fas fa-plus"></i> <span class="d-none d-sm-inline">New Task</span>
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Filter Tasks">
                        <i class="fas fa-filter"></i> <span class="d-none d-sm-inline">Filter</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="p-3 p-md-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-bold py-3 border-0">No.</th>
                            <th class="fw-bold py-3 border-0">Task Title</th>
                            <th class="fw-bold py-3 border-0 d-none d-md-table-cell">Section</th>
                            <th class="fw-bold py-3 border-0 d-none d-sm-table-cell">Due Date</th>
                            <th class="fw-bold py-3 border-0">Status</th>
                            <th class="fw-bold py-3 border-0">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="align-middle">
                            <td class="py-3">1</td>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3 d-none d-md-block">
                                        <i class="fas fa-calculator text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">Math Quiz - Algebra</div>
                                        <div class="text-muted">Complete online quiz on algebraic expressions</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 d-none d-md-table-cell">Grade 10-A</td>
                            <td class="py-3 d-none d-sm-table-cell">March 15, 2024</td>
                            <td class="py-3"><span class="badge bg-warning text-dark rounded-pill px-3 py-2">In Progress</span></td>
                            <td class="py-3"><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
                        </tr>
                        <tr class="align-middle">
                            <td class="py-3">2</td>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3 d-none d-md-block">
                                        <i class="fas fa-flask text-success"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">Science Project</div>
                                        <div class="text-muted">Create a model of the solar system</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 d-none d-md-table-cell">Grade 9-B</td>
                            <td class="py-3 d-none d-sm-table-cell">March 20, 2024</td>
                            <td class="py-3"><span class="badge bg-danger rounded-pill px-3 py-2">Not Started</span></td>
                            <td class="py-3"><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
                        </tr>
                        <tr class="align-middle">
                            <td class="py-3">3</td>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-danger bg-opacity-10 p-2 rounded-circle me-3 d-none d-md-block">
                                        <i class="fas fa-book text-danger"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">English Essay</div>
                                        <div class="text-muted">Write a 500-word essay about career goals</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 d-none d-md-table-cell">Grade 11-C</td>
                            <td class="py-3 d-none d-sm-table-cell">March 12, 2024</td>
                            <td class="py-3"><span class="badge bg-success rounded-pill px-3 py-2">Completed</span></td>
                            <td class="py-3"><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
                        </tr>
                        <tr class="align-middle">
                            <td class="py-3">4</td>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info bg-opacity-10 p-2 rounded-circle me-3 d-none d-md-block">
                                        <i class="fas fa-landmark text-info"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">History Research</div>
                                        <div class="text-muted">Research and present key events of WWII</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 d-none d-md-table-cell">Grade 12-A</td>
                            <td class="py-3 d-none d-sm-table-cell">March 25, 2024</td>
                            <td class="py-3"><span class="badge bg-primary rounded-pill px-3 py-2">In Progress</span></td>
                            <td class="py-3"><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Enhanced Pagination Section -->
            <div class="row mt-4">
                <div class="col-sm-12 col-md-6">
                    <div class="fs-6 text-muted" role="status">
                        Showing 1 to 4 of 10 entries
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <nav aria-label="Page navigation" class="d-flex justify-content-start justify-content-md-end mt-2 mt-md-0">
                        <ul class="pagination mb-0">
                            <li class="page-item disabled">
                                <span class="page-link px-3 py-2 rounded-start">
                                    <i class="fas fa-chevron-left"></i> <span class="d-none d-sm-inline">Previous</span>
                                </span>
                            </li>
                            <li class="page-item active">
                                <span class="page-link px-3 py-2">1</span>
                            </li>
                            <li class="page-item">
                                <a class="page-link px-3 py-2" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link px-3 py-2 rounded-end" href="#">
                                    <span class="d-none d-sm-inline">Next</span> <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Add CSS for transitions and hover effects using only Bootstrap-compatible styles
const style = document.createElement('style');
style.textContent = `
    .transition-all {
        transition: all 0.3s ease;
    }
    .hover-translate-up:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1) !important;
    }
    .hover-translate-x:hover {
        transform: translateX(5px);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05) !important;
    }
`;
document.head.appendChild(style);

// Update current date
document.getElementById('currentDate').textContent = new Date().toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'long' 
});

// Attendance Donut Chart
const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
const attendanceChart = new Chart(attendanceCtx, {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [80, 15, 5],
            backgroundColor: ['#0d6efd', '#ffc107', '#dc3545'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: false,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Attendance Bar Chart
const barCtx = document.getElementById('attendanceBarChart').getContext('2d');
const attendanceBarChart = new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: ['Aug', 'Sept', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May'],
        datasets: [{
            label: 'Present',
            data: [85, 88, 82, 90, 87, 85, 89, 91, 88, 86],
            backgroundColor: '#0d6efd'
        }, {
            label: 'Late',
            data: [10, 8, 12, 6, 9, 11, 7, 5, 8, 10],
            backgroundColor: '#ffc107'
        }, {
            label: 'Absent',
            data: [5, 4, 6, 4, 4, 4, 4, 4, 4, 4],
            backgroundColor: '#dc3545'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Calendar Widget
function generateCalendar() {
    const calendarDates = document.getElementById('calendarDates');
    const today = new Date();
    const currentMonth = today.getMonth();
    const currentYear = today.getFullYear();
    
    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
    
    // Define different types of special dates
    const specialDates = {
        events: [5, 15, 22], // School events - blue circle
        exams: [12, 18], // Exams - red circle
        holidays: [25], // Holidays - green circle
        assignments: [8, 20, 28] // Assignments - orange dot
    };
    
    calendarDates.innerHTML = `
        <div class="row g-1">
            ${Array.from({length: firstDay}, () => '<div class="col"><div class="d-flex align-items-center justify-content-center position-relative" style="height: 40px;"></div></div>').join('')}
            ${Array.from({length: daysInMonth}, (_, i) => {
                const day = i + 1;
                const isToday = day === today.getDate() && currentMonth === today.getMonth() && currentYear === today.getFullYear();
                
                // Check what type of special date this is
                let dateType = null;
                if (specialDates.events.includes(day)) dateType = 'event';
                else if (specialDates.exams.includes(day)) dateType = 'exam';
                else if (specialDates.holidays.includes(day)) dateType = 'holiday';
                else if (specialDates.assignments.includes(day)) dateType = 'assignment';
                
                // Base classes using only Bootstrap 5 utilities
                let classes = 'calendar-date d-flex align-items-center justify-content-center position-relative text-center';
                classes += ' rounded-circle'; // Always use circle shape for consistency
                classes += ' mx-auto'; // Center horizontally
                
                // Fixed sizing for consistent grid alignment
                classes += ' d-inline-flex'; // Inline flex for proper sizing
                
                // Add responsive sizing using inline styles for cross-browser compatibility
                // Desktop: 32px, Mobile: 28px for better fit
                const sizeStyle = window.innerWidth <= 576 ? 'width: 28px; height: 28px; min-width: 28px; font-size: 0.875rem;' : 'width: 32px; height: 32px; min-width: 32px;'
                
                let content = day;
                let indicator = '';
                
                if (isToday) {
                    classes += ' bg-primary text-white fw-bold';
                    // Add size classes for today
                    classes += ' border border-2 border-white';
                } else if (dateType === 'event') {
                    classes += ' bg-info text-white';
                } else if (dateType === 'exam') {
                    classes += ' bg-danger text-white fw-bold';
                } else if (dateType === 'holiday') {
                    classes += ' bg-success text-white';
                } else if (dateType === 'assignment') {
                    // For assignments, use a small dot indicator below the date
                    classes += ' text-dark';
                    indicator = '<span class="badge bg-warning rounded-circle p-1 d-block mx-auto mt-1" style="width: 6px; height: 6px;"></span>';
                } else {
                    // Regular dates with subtle hover effect using Bootstrap classes
                    classes += ' text-dark';
                    classes += ' border border-0'; // Invisible border for consistent sizing
                }
                
                return `<div class="col"><div class="${classes}" style="${sizeStyle}">${content}${indicator}</div></div>`;
            }).join('')}
        </div>
    `;
    
    // Add click event to calendar dates
    document.querySelectorAll('.calendar-date').forEach(date => {
        date.addEventListener('click', function() {
            // Remove selection from all dates
            document.querySelectorAll('.calendar-date').forEach(d => {
                d.classList.remove('border', 'border-dark', 'border-2');
            });
            
            // Add selection border to clicked date (only if it has content)
            if (this.textContent.trim() && !isNaN(this.textContent.trim())) {
                this.classList.add('border', 'border-dark', 'border-2');
            }
        });
    });
}

// Initialize calendar
generateCalendar();

// Add window resize listener for responsive calendar
window.addEventListener('resize', function() {
    generateCalendar();
});

// Calendar navigation (placeholder)
document.getElementById('prevMonth').addEventListener('click', function() {
    // Previous month logic would go here
    console.log('Previous month clicked');
});

document.getElementById('nextMonth').addEventListener('click', function() {
    // Next month logic would go here
    console.log('Next month clicked');
});

// Add hover effects for calendar dates using Bootstrap classes
document.addEventListener('DOMContentLoaded', function() {
    // Use event delegation for dynamically generated calendar dates
    document.getElementById('calendarDates').addEventListener('mouseenter', function(e) {
        if (e.target.classList.contains('calendar-date') && 
            !e.target.classList.contains('bg-primary') && 
            !e.target.classList.contains('bg-info') && 
            !e.target.classList.contains('bg-danger') && 
            !e.target.classList.contains('bg-success')) {
            e.target.classList.add('bg-light', 'border', 'border-primary');
        }
    }, true);
    
    document.getElementById('calendarDates').addEventListener('mouseleave', function(e) {
        if (e.target.classList.contains('calendar-date')) {
            e.target.classList.remove('bg-light', 'border', 'border-primary');
        }
    }, true);
});

// Initialize Bootstrap tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
});

// Initialize Bootstrap popovers
var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl)
});
</script>
<?= $this->endSection() ?>
