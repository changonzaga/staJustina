<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
	<div>
		<div class="title">
			<h4>Announcements</h4>
		</div>
		<nav aria-label="breadcrumb" role="navigation">
			<ol class="breadcrumb mb-0">
				<li class="breadcrumb-item">
					<a href="<?= route_to('admin.home')?>">Home</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">
					Announcements
				</li>
			</ol>
		</nav>
	</div>
	<div>
		<a href="<?= route_to('admin.announcements.create') ?>" class="btn btn-success btn-sm">
			<i class="icon-copy bi bi-plus-lg"></i> Add Announcement
		</a>
	</div>
</div>

<div class="card-box mt-4 shadow-sm border-0">
    <div class="pd-20 pb-0 d-flex align-items-center justify-content-between">
        <h4 class="h4 text-blue mb-0"><i class="dw dw-list"></i> Recent Announcements</h4>
        <a href="<?= route_to('admin.announcements.history') ?>" class="btn btn-link text-secondary"><i class="dw dw-calendar1"></i> View All</a>
    </div>
    <div class="pd-20 pt-2">
        <!-- Recent Approved Announcements -->
        <div class="list-group" id="announcementList">
            <!-- Announcement 1 -->
             <div class="list-group-item flex-column align-items-start mb-2 rounded shadow-sm">
                 <div class="d-flex w-100 justify-content-between">
                     <h5 class="mb-1 text-dark">School Sports Day 2024</h5>
                     <small class="text-muted"><i class="dw dw-time"></i> January 15, 2024</small>
                 </div>
                 <p class="mb-1 text-secondary">We are excited to announce our annual School Sports Day scheduled for March 15, 2024. All students, teachers, and parents are invited to participate in this fun-filled event featuring various sports competitions, games, and activities...</p>
                 <div class="d-flex justify-content-between align-items-center">
                     <div class="d-flex align-items-center">
                         <small class="text-info mr-3"><i class="dw dw-user1"></i> All Users</small>
                         <span class="badge badge-success">Published</span>
                     </div>
                     <button class="btn btn-primary btn-sm" onclick="viewAnnouncement(1, 'School Sports Day 2024', 'We are excited to announce our annual School Sports Day scheduled for March 15, 2024. All students, teachers, and parents are invited to participate in this fun-filled event featuring various sports competitions, games, and activities. The event will include track and field events, team sports like basketball and volleyball, fun games for younger students, and special performances. Prizes will be awarded to winners in each category. Please bring your own water bottles and wear appropriate sports attire. Registration forms are available at the main office.', 'All Users', 'January 15, 2024')">
                         <i class="dw dw-eye"></i> View Details
                     </button>
                 </div>
             </div>
            
            <!-- Announcement 2 -->
             <div class="list-group-item flex-column align-items-start mb-2 rounded shadow-sm">
                 <div class="d-flex w-100 justify-content-between">
                     <h5 class="mb-1 text-dark">Midterm Examination Schedule</h5>
                     <small class="text-muted"><i class="dw dw-time"></i> January 12, 2024</small>
                 </div>
                 <p class="mb-1 text-secondary">Dear students, please be informed that the midterm examinations will commence on February 20, 2024. Please review the examination schedule posted on the bulletin board and prepare accordingly. Good luck with your studies!</p>
                 <div class="d-flex justify-content-between align-items-center">
                     <div class="d-flex align-items-center">
                         <small class="text-info mr-3"><i class="dw dw-user1"></i> Students</small>
                         <span class="badge badge-success">Published</span>
                     </div>
                     <button class="btn btn-primary btn-sm" onclick="viewAnnouncement(2, 'Midterm Examination Schedule', 'Dear students, please be informed that the midterm examinations will commence on February 20, 2024. Please review the examination schedule posted on the bulletin board and prepare accordingly. The examination will cover all topics discussed from the beginning of the semester until January 31, 2024. Make sure to bring your school ID, pencils, erasers, and calculators (for Math and Science subjects only). Cheating in any form will result in automatic failure. Students who are absent during the examination period must present a medical certificate or valid excuse letter. Good luck with your studies!', 'Students', 'January 12, 2024')">
                         <i class="dw dw-eye"></i> View Details
                     </button>
                 </div>
             </div>
            
            <!-- Announcement 3 -->
             <div class="list-group-item flex-column align-items-start mb-2 rounded shadow-sm">
                 <div class="d-flex w-100 justify-content-between">
                     <h5 class="mb-1 text-dark">New Library Hours and Resources</h5>
                     <small class="text-muted"><i class="dw dw-time"></i> January 8, 2024</small>
                 </div>
                 <p class="mb-1 text-secondary">The school library will now be open from 7:00 AM to 6:00 PM, Monday through Friday. We have also added new digital resources and study materials. Students are encouraged to make use of these extended hours for research and study...</p>
                 <div class="d-flex justify-content-between align-items-center">
                     <div class="d-flex align-items-center">
                         <small class="text-info mr-3"><i class="dw dw-user1"></i> Students</small>
                         <span class="badge badge-success">Published</span>
                     </div>
                     <button class="btn btn-primary btn-sm" onclick="viewAnnouncement(3, 'New Library Hours and Resources', 'The school library will now be open from 7:00 AM to 6:00 PM, Monday through Friday. We have also added new digital resources and study materials. Students are encouraged to make use of these extended hours for research and study. New additions include: access to online databases, e-books collection, computer workstations with internet access, group study rooms that can be reserved, printing and scanning services, and a quiet study area. Library cards are required for borrowing books. Please maintain silence in designated quiet zones and return books on time to avoid penalties.', 'Students', 'January 8, 2024')">
                         <i class="dw dw-eye"></i> View Details
                     </button>
                 </div>
             </div>
            
            <!-- If no announcements, show a message (hidden when there are announcements) -->
            <div class="text-center text-muted py-4" style="display: none;" id="noAnnouncements">
                <i class="dw dw-megaphone font-48"></i>
                <div class="mt-2">No announcements yet.</div>
            </div>
        </div>
    </div>
</div>

<!-- Announcements Management Table -->
<div class="card-box mt-4 shadow-sm border-0">
    <div class="pd-20 pb-0">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="h4 text-blue mb-1"><i class="dw dw-settings2"></i> Manage Announcements</h4>
            </div>
        </div>
    </div>
    
    <!-- Filter and Search Section -->
    <div class="pd-20 pt-0">
        <div class="row align-items-end">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="searchAnnouncements">Search Announcements:</label>
                    <div class="position-relative">
                        <input type="text" class="form-control pl-4" id="searchAnnouncements" placeholder="Search by title or content..." onkeyup="searchAnnouncements()">
                        <span style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #aaa;">
                            <i class="icon-copy bi bi-search"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="audienceFilter">Filter by Audience:</label>
                    <select class="form-control" id="audienceFilter" onchange="filterByAudience()">
                        <option value="">All Audiences</option>
                        <option value="All">All Users</option>
                        <option value="Students">Students Only</option>
                        <option value="Teachers">Teachers Only</option>
                        <option value="Parents">Parents Only</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="statusFilter">Filter by Status:</label>
                    <select class="form-control" id="statusFilter" onchange="filterAnnouncements()">
                        <option value="all">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="published">Published</option>
                        <option value="declined">Declined</option>
                    </select>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div class="d-flex justify-content-end">
                        <div class="dt-buttons btn-group flex-wrap">
                            <button class="btn btn-secondary btn-sm" onclick="exportAnnouncements('copy')">
                                <i class="icon-copy bi bi-clipboard"></i> Copy
                            </button>
                            <button class="btn btn-secondary btn-sm" onclick="exportAnnouncements('csv')">
                                <i class="icon-copy bi bi-filetype-csv"></i> CSV
                            </button>
                            <button class="btn btn-secondary btn-sm" onclick="exportAnnouncements('pdf')">
                                <i class="icon-copy bi bi-file-pdf"></i> PDF
                            </button>
                            <button class="btn btn-secondary btn-sm" onclick="exportAnnouncements('print')">
                                <i class="icon-copy bi bi-printer"></i> Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcements Table -->
    <div class="pb-20">
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="announcementsTable">
                <thead class="bg-light">
                    <tr>
                        <th class="table-plus">
                            <input type="checkbox" id="selectAllAnnouncements" onchange="toggleSelectAll()">
                        </th>
                        <th>Title</th>
                        <th>Content Preview</th>
                        <th>Audience</th>
                        <th>Author</th>
                        <th>Date Created</th>
                        <th>Status</th>
                        <th class="datatable-nosort">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Sample Announcement 1 -->
                    <tr data-status="pending" data-audience="All">
                        <td>
                            <input type="checkbox" class="announcement-checkbox" value="1">
                        </td>
                        <td>
                            <div class="font-weight-bold text-dark">School Sports Day 2024</div>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 200px;" title="We are excited to announce our annual School Sports Day scheduled for March 15, 2024. All students, teachers, and parents are invited to participate in this fun-filled event...">
                                We are excited to announce our annual School Sports Day scheduled for March 15, 2024...
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-primary">All Users</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar mr-2" style="width: 30px; height: 30px; border-radius: 50%; background: #007bff; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: bold;">
                                    AD
                                </div>
                                <span>Admin User</span>
                            </div>
                        </td>
                        <td>
                            <div>2024-01-15</div>
                            <small class="text-muted">10:30 AM</small>
                        </td>
                        <td>
                            <span class="badge badge-warning">Pending</span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" type="button" data-toggle="dropdown">
                                    <i class="dw dw-more"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                    <a class="dropdown-item" href="#" onclick="viewAnnouncement(1)"><i class="dw dw-eye"></i> View</a>
                                    <a class="dropdown-item" href="#" onclick="editAnnouncement(1)"><i class="dw dw-edit2"></i> Edit</a>
                                    <a class="dropdown-item text-success" href="#" onclick="publishAnnouncement(1)"><i class="dw dw-checkmark"></i> Publish</a>
                                    <a class="dropdown-item text-danger" href="#" onclick="declineAnnouncement(1)"><i class="dw dw-delete-3"></i> Decline</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Sample Announcement 2 -->
                    <tr data-status="published" data-audience="Students">
                        <td>
                            <input type="checkbox" class="announcement-checkbox" value="2">
                        </td>
                        <td>
                            <div class="font-weight-bold text-dark">Midterm Examination Schedule</div>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 200px;" title="Dear students, please be informed that the midterm examinations will commence on February 20, 2024. Please review the examination schedule and prepare accordingly...">
                                Dear students, please be informed that the midterm examinations will commence on February 20, 2024...
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-info">Students</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar mr-2" style="width: 30px; height: 30px; border-radius: 50%; background: #28a745; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: bold;">
                                    JD
                                </div>
                                <span>John Doe</span>
                            </div>
                        </td>
                        <td>
                            <div>2024-01-12</div>
                            <small class="text-muted">2:15 PM</small>
                        </td>
                        <td>
                            <span class="badge badge-success">Published</span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" type="button" data-toggle="dropdown">
                                    <i class="dw dw-more"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                    <a class="dropdown-item" href="#" onclick="viewAnnouncement(2)"><i class="dw dw-eye"></i> View</a>
                                    <a class="dropdown-item" href="#" onclick="editAnnouncement(2)"><i class="dw dw-edit2"></i> Edit</a>
                                    <a class="dropdown-item text-warning" href="#" onclick="unpublishAnnouncement(2)"><i class="dw dw-minus-circle"></i> Unpublish</a>
                                    <a class="dropdown-item text-danger" href="#" onclick="deleteAnnouncement(2)"><i class="dw dw-delete-3"></i> Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Sample Announcement 3 -->
                    <tr data-status="declined" data-audience="Parents">
                        <td>
                            <input type="checkbox" class="announcement-checkbox" value="3">
                        </td>
                        <td>
                            <div class="font-weight-bold text-dark">Parent-Teacher Conference</div>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 200px;" title="We would like to invite all parents to attend the upcoming Parent-Teacher Conference scheduled for January 25, 2024. This is an important opportunity to discuss your child's progress...">
                                We would like to invite all parents to attend the upcoming Parent-Teacher Conference...
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-secondary">Parents</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar mr-2" style="width: 30px; height: 30px; border-radius: 50%; background: #dc3545; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: bold;">
                                    MS
                                </div>
                                <span>Mary Smith</span>
                            </div>
                        </td>
                        <td>
                            <div>2024-01-10</div>
                            <small class="text-muted">9:45 AM</small>
                        </td>
                        <td>
                            <span class="badge badge-danger">Declined</span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" type="button" data-toggle="dropdown">
                                    <i class="dw dw-more"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                    <a class="dropdown-item" href="#" onclick="viewAnnouncement(3)"><i class="dw dw-eye"></i> View</a>
                                    <a class="dropdown-item" href="#" onclick="editAnnouncement(3)"><i class="dw dw-edit2"></i> Edit</a>
                                    <a class="dropdown-item text-success" href="#" onclick="republishAnnouncement(3)"><i class="dw dw-checkmark"></i> Re-publish</a>
                                    <a class="dropdown-item text-danger" href="#" onclick="deleteAnnouncement(3)"><i class="dw dw-delete-3"></i> Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Announcement Detail Modal -->
<div class="modal fade" id="announcementDetailModal" tabindex="-1" role="dialog" aria-labelledby="announcementDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="announcementDetailModalLabel">
                    <i class="dw dw-megaphone"></i> Announcement Details
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h4 id="modalAnnouncementTitle" class="text-dark mb-2"></h4>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <span class="badge badge-info mr-2" id="modalAnnouncementAudience"></span>
                            <span class="badge badge-success">Published</span>
                        </div>
                        <small class="text-muted" id="modalAnnouncementDate">
                            <i class="dw dw-time"></i> 
                        </small>
                    </div>
                </div>
                <div class="border rounded p-3 bg-light">
                    <div id="modalAnnouncementContent" class="text-justify"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="dw dw-arrow-left"></i> Close
                </button>
                <button type="button" class="btn btn-primary" onclick="printAnnouncement()">
                    <i class="dw dw-print"></i> Print
                </button>
            </div>
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
.textarea_editor {
    min-height: 180px;
    resize: vertical;
}
.list-group-item {
    transition: box-shadow 0.2s;
}
.list-group-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    background: #f8f9fa;
}
</style>

<script>
// Example: Show/hide "No announcements" message (replace with dynamic logic)
if (document.querySelectorAll('#announcementList .list-group-item').length === 0) {
    document.getElementById('noAnnouncements').style.display = 'block';
}

// Announcements Management Functions
function filterAnnouncements() {
    const status = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('#announcementsTable tbody tr');
    
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function searchAnnouncements() {
    const searchTerm = document.getElementById('searchAnnouncements').value.toLowerCase();
    const rows = document.querySelectorAll('#announcementsTable tbody tr');
    
    rows.forEach(row => {
        const title = row.cells[1].textContent.toLowerCase();
        const content = row.cells[2].textContent.toLowerCase();
        
        if (title.includes(searchTerm) || content.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function filterByAudience() {
    const audience = document.getElementById('audienceFilter').value;
    const rows = document.querySelectorAll('#announcementsTable tbody tr');
    
    rows.forEach(row => {
        if (!audience || row.dataset.audience === audience) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAllAnnouncements');
    const checkboxes = document.querySelectorAll('.announcement-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

// Announcement Actions
function viewAnnouncement(id) {
    alert('View announcement with ID: ' + id);
    // Implement view functionality
}

function editAnnouncement(id) {
    alert('Edit announcement with ID: ' + id);
    // Implement edit functionality
}

function publishAnnouncement(id) {
    if (confirm('Are you sure you want to publish this announcement?')) {
        // Update status in the table
        const row = document.querySelector(`tr input[value="${id}"]`).closest('tr');
        row.dataset.status = 'published';
        row.querySelector('.badge').className = 'badge badge-success';
        row.querySelector('.badge').textContent = 'Published';
        
        // Update dropdown actions
        const dropdown = row.querySelector('.dropdown-menu');
        dropdown.innerHTML = `
            <a class="dropdown-item" href="#" onclick="viewAnnouncement(${id})"><i class="dw dw-eye"></i> View</a>
            <a class="dropdown-item" href="#" onclick="editAnnouncement(${id})"><i class="dw dw-edit2"></i> Edit</a>
            <a class="dropdown-item text-warning" href="#" onclick="unpublishAnnouncement(${id})"><i class="dw dw-minus-circle"></i> Unpublish</a>
            <a class="dropdown-item text-danger" href="#" onclick="deleteAnnouncement(${id})"><i class="dw dw-delete-3"></i> Delete</a>
        `;
        
        alert('Announcement published successfully!');
    }
}

function declineAnnouncement(id) {
    if (confirm('Are you sure you want to decline this announcement?')) {
        // Update status in the table
        const row = document.querySelector(`tr input[value="${id}"]`).closest('tr');
        row.dataset.status = 'declined';
        row.querySelector('.badge').className = 'badge badge-danger';
        row.querySelector('.badge').textContent = 'Declined';
        
        // Update dropdown actions
        const dropdown = row.querySelector('.dropdown-menu');
        dropdown.innerHTML = `
            <a class="dropdown-item" href="#" onclick="viewAnnouncement(${id})"><i class="dw dw-eye"></i> View</a>
            <a class="dropdown-item" href="#" onclick="editAnnouncement(${id})"><i class="dw dw-edit2"></i> Edit</a>
            <a class="dropdown-item text-success" href="#" onclick="republishAnnouncement(${id})"><i class="dw dw-checkmark"></i> Re-publish</a>
            <a class="dropdown-item text-danger" href="#" onclick="deleteAnnouncement(${id})"><i class="dw dw-delete-3"></i> Delete</a>
        `;
        
        alert('Announcement declined successfully!');
    }
}

function unpublishAnnouncement(id) {
    if (confirm('Are you sure you want to unpublish this announcement?')) {
        // Update status in the table
        const row = document.querySelector(`tr input[value="${id}"]`).closest('tr');
        row.dataset.status = 'pending';
        row.querySelector('.badge').className = 'badge badge-warning';
        row.querySelector('.badge').textContent = 'Pending';
        
        alert('Announcement unpublished successfully!');
    }
}

function republishAnnouncement(id) {
    if (confirm('Are you sure you want to re-publish this announcement?')) {
        publishAnnouncement(id);
    }
}

function deleteAnnouncement(id) {
    if (confirm('Are you sure you want to delete this announcement? This action cannot be undone.')) {
        const row = document.querySelector(`tr input[value="${id}"]`).closest('tr');
        row.remove();
        alert('Announcement deleted successfully!');
    }
}

// Export Functions
function exportAnnouncements(type) {
    const selectedRows = document.querySelectorAll('.announcement-checkbox:checked');
    
    if (selectedRows.length === 0) {
        alert('Please select at least one announcement to export.');
        return;
    }
    
    switch(type) {
        case 'copy':
            alert('Copying selected announcements to clipboard...');
            break;
        case 'csv':
            alert('Exporting selected announcements to CSV...');
            break;
        case 'pdf':
            alert('Exporting selected announcements to PDF...');
            break;
        case 'print':
            alert('Printing selected announcements...');
            break;
    }
}

// View Announcement Details
function viewAnnouncement(id, title, content, audience, date) {
    // Set modal content
    document.getElementById('modalAnnouncementTitle').textContent = title;
    document.getElementById('modalAnnouncementContent').innerHTML = content.replace(/\n/g, '<br>');
    document.getElementById('modalAnnouncementAudience').textContent = audience;
    document.getElementById('modalAnnouncementDate').innerHTML = '<i class="dw dw-time"></i> ' + date;
    
    // Show modal
    $('#announcementDetailModal').modal('show');
}

// Print Announcement
function printAnnouncement() {
    const title = document.getElementById('modalAnnouncementTitle').textContent;
    const content = document.getElementById('modalAnnouncementContent').innerHTML;
    const audience = document.getElementById('modalAnnouncementAudience').textContent;
    const date = document.getElementById('modalAnnouncementDate').textContent;
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Print Announcement - ${title}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .header { border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; }
                    .title { color: #007bff; margin-bottom: 10px; }
                    .meta { color: #666; font-size: 14px; margin-bottom: 20px; }
                    .content { line-height: 1.6; text-align: justify; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1 class="title">${title}</h1>
                    <div class="meta">
                        <strong>Audience:</strong> ${audience} | 
                        <strong>Date:</strong> ${date.replace('<i class="dw dw-time"></i> ', '')}
                    </div>
                </div>
                <div class="content">
                    ${content}
                </div>
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Set default filter values
    document.getElementById('statusFilter').value = 'all';
});

// ...existing code...
</script>

<?= $this->endSection() ?>