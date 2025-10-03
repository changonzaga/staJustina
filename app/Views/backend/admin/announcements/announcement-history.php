<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Announcement History</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb bg-light px-3 py-2 rounded">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home')?>">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.announcements') ?>">Announcements</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        History
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3">
            <div class="d-flex flex-wrap">
                <div class="widget-data">
                    <div class="weight-700 font-24 text-dark">156</div>
                    <div class="font-14 text-secondary weight-500">Total Announcements</div>
                </div>
                <div class="widget-icon">
                    <div class="icon" style="color: #007bff;"><i class="dw dw-megaphone"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3">
            <div class="d-flex flex-wrap">
                <div class="widget-data">
                    <div class="weight-700 font-24 text-dark">89</div>
                    <div class="font-14 text-secondary weight-500">Published</div>
                </div>
                <div class="widget-icon">
                    <div class="icon" style="color: #28a745;"><i class="dw dw-checkmark"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3">
            <div class="d-flex flex-wrap">
                <div class="widget-data">
                    <div class="weight-700 font-24 text-dark">23</div>
                    <div class="font-14 text-secondary weight-500">Pending</div>
                </div>
                <div class="widget-icon">
                    <div class="icon" style="color: #ffc107;"><i class="dw dw-time"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3">
            <div class="d-flex flex-wrap">
                <div class="widget-data">
                    <div class="weight-700 font-24 text-dark">44</div>
                    <div class="font-14 text-secondary weight-500">Archived</div>
                </div>
                <div class="widget-icon">
                    <div class="icon" style="color: #6c757d;"><i class="dw dw-archive"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Announcement History Table -->
<div class="card-box mb-30 shadow-sm border-0">
    <div class="pd-20 pb-0">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="h4 text-blue mb-1"><i class="dw dw-calendar1"></i> Complete Announcement History</h4>
            </div>
        </div>
    </div>
    
    <!-- Advanced Filter Section -->
    <div class="pd-20 pt-3 mt-3">
        <div class="row align-items-end">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="searchHistory">Search:</label>
                    <div class="position-relative">
                        <input type="text" class="form-control pl-4" id="searchHistory" placeholder="Search announcements..." onkeyup="searchHistory()">
                        <span style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #aaa;">
                            <i class="icon-copy bi bi-search"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="audienceHistoryFilter">Audience:</label>
                    <select class="form-control" id="audienceHistoryFilter" onchange="filterHistory()">
                        <option value="all">All Audiences</option>
                        <option value="All">All Users</option>
                        <option value="Students">Students</option>
                        <option value="Teachers">Teachers</option>
                        <option value="Parents">Parents</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="dateRangeFilter">Date Range:</label>
                    <select class="form-control" id="dateRangeFilter" onchange="filterHistory()">
                        <option value="all">All Time</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="quarter">This Quarter</option>
                        <option value="year">This Year</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div class="d-flex justify-content-end align-items-center">
                        <div class="btn-toolbar" role="toolbar">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-secondary" onclick="exportAnnouncements('copy')">
                                    <i class="icon-copy bi bi-clipboard"></i> Copy
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="exportAnnouncements('csv')">
                                    <i class="icon-copy bi bi-filetype-csv"></i> CSV
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="exportAnnouncements('pdf')">
                                    <i class="icon-copy bi bi-file-pdf"></i> PDF
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="exportAnnouncements('print')">
                                    <i class="icon-copy bi bi-printer"></i> Print
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- History Table -->
    <div class="pb-20">
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="historyTable">
                <thead class="bg-light">
                    <tr>
                        <th class="table-plus">
                            <input type="checkbox" id="selectAllHistory" onchange="toggleSelectAllHistory()">
                        </th>
                        <th>Title</th>
                        <th>Audience</th>
                        <th>Author</th>
                        <th>Created Date</th>
                        <th>Published Date</th>
                        <th>Status</th>
                        <th class="datatable-nosort">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Sample History Entry 1 -->
                    <tr data-status="published" data-audience="All" data-date="2024-01-15">
                        <td><input type="checkbox" class="history-checkbox" value="1"></td>
                        <td>
                            <div class="font-weight-bold text-dark">School Sports Day 2024</div>
                            <small class="text-muted">Annual sports event announcement</small>
                        </td>
                        <td><span class="text-dark">All Users</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span>Admin User</span>
                            </div>
                        </td>
                        <td>
                            <div>2024-01-15</div>
                            <small class="text-muted">10:30 AM</small>
                        </td>
                        <td>
                            <div>2024-01-15</div>
                            <small class="text-muted">11:00 AM</small>
                        </td>
                        <td><span class="badge badge-success">Published</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" type="button" data-toggle="dropdown">
                                    <i class="dw dw-more"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                    <a class="dropdown-item" href="#" onclick="viewHistoryDetails(1)"><i class="dw dw-eye"></i> View Details</a>
                                    <a class="dropdown-item" href="#" onclick="editAnnouncement(1)"><i class="dw dw-edit2"></i> Edit</a>
                                    <a class="dropdown-item" href="#" onclick="viewAnalytics(1)"><i class="dw dw-analytics"></i> Analytics</a>
                                    <a class="dropdown-item text-warning" href="#" onclick="archiveAnnouncement(1)"><i class="dw dw-archive"></i> Archive</a>
                                    <a class="dropdown-item text-danger" href="#" onclick="deleteAnnouncement(1)"><i class="dw dw-delete-3"></i> Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Sample History Entry 2 -->
                    <tr data-status="published" data-audience="Students" data-date="2024-01-12">
                        <td><input type="checkbox" class="history-checkbox" value="2"></td>
                        <td>
                            <div class="font-weight-bold text-dark">Midterm Examination Schedule</div>
                            <small class="text-muted">Important exam dates and guidelines</small>
                        </td>
                        <td><span class="text-dark">Students</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span>John Doe</span>
                            </div>
                        </td>
                        <td>
                            <div>2024-01-12</div>
                            <small class="text-muted">2:15 PM</small>
                        </td>
                        <td>
                            <div>2024-01-12</div>
                            <small class="text-muted">2:30 PM</small>
                        </td>
                        <td><span class="badge badge-success">Published</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" type="button" data-toggle="dropdown">
                                    <i class="dw dw-more"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                    <a class="dropdown-item" href="#" onclick="viewHistoryDetails(2)"><i class="dw dw-eye"></i> View Details</a>
                                    <a class="dropdown-item" href="#" onclick="editAnnouncement(2)"><i class="dw dw-edit2"></i> Edit</a>
                                    <a class="dropdown-item" href="#" onclick="viewAnalytics(2)"><i class="dw dw-analytics"></i> Analytics</a>
                                    <a class="dropdown-item text-warning" href="#" onclick="archiveAnnouncement(2)"><i class="dw dw-archive"></i> Archive</a>
                                    <a class="dropdown-item text-danger" href="#" onclick="deleteAnnouncement(2)"><i class="dw dw-delete-3"></i> Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Sample History Entry 3 -->
                    <tr data-status="pending" data-audience="Parents" data-date="2024-01-10">
                        <td><input type="checkbox" class="history-checkbox" value="3"></td>
                        <td>
                            <div class="font-weight-bold text-dark">Parent-Teacher Conference</div>
                            <small class="text-muted">Upcoming conference schedule</small>
                        </td>
                        <td><span class="text-dark">Parents</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span>Mary Smith</span>
                            </div>
                        </td>
                        <td>
                            <div>2024-01-10</div>
                            <small class="text-muted">9:45 AM</small>
                        </td>
                        <td>
                            <div>2024-01-10</div>
                            <small class="text-muted">9:45 AM</small>
                        </td>
                        <td><span class="badge badge-success">Published</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" type="button" data-toggle="dropdown">
                                    <i class="dw dw-more"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                    <a class="dropdown-item" href="#" onclick="viewHistoryDetails(3)"><i class="dw dw-eye"></i> View Details</a>
                                    <a class="dropdown-item" href="#" onclick="editAnnouncement(3)"><i class="dw dw-edit2"></i> Edit</a>
                                    <a class="dropdown-item text-success" href="#" onclick="publishAnnouncement(3)"><i class="dw dw-checkmark"></i> Publish</a>
                                    <a class="dropdown-item text-danger" href="#" onclick="declineAnnouncement(3)"><i class="dw dw-delete-3"></i> Decline</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Sample History Entry 4 -->
                    <tr data-status="archived" data-audience="All" data-date="2023-12-20">
                        <td><input type="checkbox" class="history-checkbox" value="4"></td>
                        <td>
                            <div class="font-weight-bold text-dark">Christmas Holiday Notice</div>
                            <small class="text-muted">Holiday schedule and greetings</small>
                        </td>
                        <td><span class="text-dark">All Users</span></td>
                        <td>
                                <span>Admin User</span>
                            </div>
                        </td>
                        <td>
                            <div>2023-12-20</div>
                            <small class="text-muted">3:00 PM</small>
                        </td>
                        <td>
                            <div>2023-12-20</div>
                            <small class="text-muted">3:15 PM</small>
                        </td>
                        <td><span class="badge badge-success">Published</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" type="button" data-toggle="dropdown">
                                    <i class="dw dw-more"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                    <a class="dropdown-item" href="#" onclick="viewHistoryDetails(4)"><i class="dw dw-eye"></i> View Details</a>
                                    <a class="dropdown-item text-primary" href="#" onclick="restoreAnnouncement(4)"><i class="dw dw-refresh"></i> Restore</a>
                                    <a class="dropdown-item" href="#" onclick="viewAnalytics(4)"><i class="dw dw-analytics"></i> Analytics</a>
                                    <a class="dropdown-item text-danger" href="#" onclick="deleteAnnouncement(4)"><i class="dw dw-delete-3"></i> Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Sample History Entry 5 -->
                    <tr data-status="declined" data-audience="Teachers" data-date="2024-01-08">
                        <td><input type="checkbox" class="history-checkbox" value="5"></td>
                        <td>
                            <div class="font-weight-bold text-dark">Staff Meeting Postponed</div>
                            <small class="text-muted">Meeting schedule change notice</small>
                        </td>
                        <td><span class="text-dark">Teachers</span></td>
                        <td>
                                <span>Robert Johnson</span>
                            </div>
                        </td>
                        <td>
                            <div>2024-01-08</div>
                            <small class="text-muted">11:20 AM</small>
                        </td>
                         <td>
                            <div>2024-01-08</div>
                            <small class="text-muted">11:30 AM</small>
                        </td>
                        <td><span class="badge badge-success">Published</span></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" type="button" data-toggle="dropdown">
                                    <i class="dw dw-more"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                    <a class="dropdown-item" href="#" onclick="viewHistoryDetails(5)"><i class="dw dw-eye"></i> View Details</a>
                                    <a class="dropdown-item" href="#" onclick="editAnnouncement(5)"><i class="dw dw-edit2"></i> Edit</a>
                                    <a class="dropdown-item text-success" href="#" onclick="republishAnnouncement(5)"><i class="dw dw-checkmark"></i> Re-publish</a>
                                    <a class="dropdown-item text-danger" href="#" onclick="deleteAnnouncement(5)"><i class="dw dw-delete-3"></i> Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3 px-3">
            <div class="text-muted">
                Showing 1 to 5 of 156 entries
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<style>
/* Custom UI enhancements */
.card-box {
    border-radius: 12px;
    border: 1px solid #e3e6f0;
    background: #fff;
}
.widget-style3 {
    border-radius: 8px;
    transition: transform 0.2s;
}
.widget-style3:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}
.avatar {
    transition: transform 0.2s;
}
.avatar:hover {
    transform: scale(1.1);
}
</style>

<script>
// History Management Functions
function searchHistory() {
    const searchTerm = document.getElementById('searchHistory').value.toLowerCase();
    const rows = document.querySelectorAll('#historyTable tbody tr');
    
    rows.forEach(row => {
        const title = row.cells[1].textContent.toLowerCase();
        const author = row.cells[3].textContent.toLowerCase();
        
        if (title.includes(searchTerm) || author.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function filterHistory() {
    const audience = document.getElementById('audienceHistoryFilter').value;
    const dateRange = document.getElementById('dateRangeFilter').value;
    const rows = document.querySelectorAll('#historyTable tbody tr');
    
    rows.forEach(row => {
        let showRow = true;
        
        // Audience filter
        if (audience !== 'all' && row.dataset.audience !== audience) {
            showRow = false;
        }
        
        // Date range filter (simplified)
        if (dateRange !== 'all') {
            const rowDate = new Date(row.dataset.date);
            const today = new Date();
            let showByDate = false;
            
            switch(dateRange) {
                case 'today':
                    showByDate = rowDate.toDateString() === today.toDateString();
                    break;
                case 'week':
                    const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                    showByDate = rowDate >= weekAgo;
                    break;
                case 'month':
                    showByDate = rowDate.getMonth() === today.getMonth() && rowDate.getFullYear() === today.getFullYear();
                    break;
                case 'quarter':
                    const quarter = Math.floor(today.getMonth() / 3);
                    const rowQuarter = Math.floor(rowDate.getMonth() / 3);
                    showByDate = quarter === rowQuarter && rowDate.getFullYear() === today.getFullYear();
                    break;
                case 'year':
                    showByDate = rowDate.getFullYear() === today.getFullYear();
                    break;
            }
            
            if (!showByDate) showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}

function toggleSelectAllHistory() {
    const selectAll = document.getElementById('selectAllHistory');
    const checkboxes = document.querySelectorAll('.history-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

// Action Functions
function viewHistoryDetails(id) {
    alert('View details for announcement ID: ' + id);
    // Implement view details functionality
}

function editAnnouncement(id) {
    alert('Edit announcement ID: ' + id);
    // Implement edit functionality
}

function viewAnalytics(id) {
    alert('View analytics for announcement ID: ' + id);
    // Implement analytics view
}

function archiveAnnouncement(id) {
    if (confirm('Are you sure you want to archive this announcement?')) {
        alert('Announcement ' + id + ' archived successfully!');
        // Implement archive functionality
    }
}

function restoreAnnouncement(id) {
    if (confirm('Are you sure you want to restore this announcement?')) {
        alert('Announcement ' + id + ' restored successfully!');
        // Implement restore functionality
    }
}

function publishAnnouncement(id) {
    if (confirm('Are you sure you want to publish this announcement?')) {
        alert('Announcement ' + id + ' published successfully!');
        // Implement publish functionality
    }
}

function declineAnnouncement(id) {
    if (confirm('Are you sure you want to decline this announcement?')) {
        alert('Announcement ' + id + ' declined successfully!');
        // Implement decline functionality
    }
}

function republishAnnouncement(id) {
    if (confirm('Are you sure you want to re-publish this announcement?')) {
        alert('Announcement ' + id + ' re-published successfully!');
        // Implement republish functionality
    }
}

function deleteAnnouncement(id) {
    if (confirm('Are you sure you want to delete this announcement? This action cannot be undone.')) {
        alert('Announcement ' + id + ' deleted successfully!');
        // Implement delete functionality
    }
}

// Export Functions
function exportHistory(type) {
    const selectedRows = document.querySelectorAll('.history-checkbox:checked');
    
    if (selectedRows.length === 0) {
        alert('Please select at least one announcement to export.');
        return;
    }
    
    switch(type) {
        case 'excel':
            alert('Exporting to Excel...');
            break;
        case 'pdf':
            alert('Exporting to PDF...');
            break;
        case 'print':
            alert('Printing history...');
            break;
    }
}

function refreshHistory() {
    alert('Refreshing announcement history...');
    location.reload();
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Set default filter values
    document.getElementById('audienceHistoryFilter').value = 'all';
    document.getElementById('dateRangeFilter').value = 'all';
});
</script>

<?= $this->endSection() ?>