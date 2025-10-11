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
            <?php if (!empty($announcements)): ?>
                <?php foreach (array_slice($announcements, 0, 3) as $a): ?>
                    <div class="list-group-item flex-column align-items-start mb-2 rounded shadow-sm">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1 text-dark"><?= esc($a['title']) ?></h5>
                            <small class="text-muted"><i class="dw dw-time"></i> <?= esc(date('M d, Y', strtotime($a['publish_date'] ?? $a['created_at']))) ?></small>
                        </div>
                        <p class="mb-1 text-secondary"><?= esc(mb_strimwidth($a['content'], 0, 150, '...')) ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <small class="text-info mr-3"><i class="dw dw-user1"></i> <?= esc(ucfirst($a['audience_type'])) ?></small>
                                <?php $p = strtolower($a['priority'] ?? 'normal'); $badge = $p==='urgent'?'danger':($p==='high'?'warning':'success'); ?>
                                <span class="badge badge-<?= $badge ?>"><?= esc(ucfirst($p)) ?></span>
                            </div>
                            <button class="btn btn-primary btn-sm" onclick="viewAnnouncement(<?= (int)$a['id'] ?>, '<?= esc($a['title']) ?>', '<?= esc($a['content']) ?>', '<?= esc($a['audience_type']) ?>', '<?= esc(date('M d, Y h:i A', strtotime($a['publish_date'] ?? $a['created_at']))) ?>')">
                                <i class="dw dw-eye"></i> View Details
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center text-muted py-4">
                    <i class="dw dw-megaphone font-48"></i>
                    <div class="mt-2">No announcements yet.</div>
                </div>
            <?php endif; ?>
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
                <tbody id="announcementsTableBody">
                    <?php if (!empty($announcements)): ?>
                        <?php foreach ($announcements as $a): ?>
                            <?php 
                                $date = strtotime($a['publish_date'] ?? $a['created_at']);
                                $aud = $a['audience_type'] ?? 'All';
                                $audClass = $aud==='All'?'primary':($aud==='Students'?'info':($aud==='Teachers'?'warning':'secondary'));
                            ?>
                            <tr data-status="published" data-audience="<?= esc($aud) ?>">
                                <td><input type="checkbox" class="announcement-checkbox" value="<?= (int)$a['id'] ?>"></td>
                                <td><div class="font-weight-bold text-dark"><?= esc($a['title']) ?></div></td>
                                <td>
                                    <div class="text-truncate" style="max-width: 200px;" title="<?= esc($a['content']) ?>">
                                        <?= esc(mb_strimwidth($a['content'], 0, 80, '...')) ?>
                                    </div>
                                </td>
                                <td><span class="badge badge-<?= $audClass ?>"><?= esc($aud) ?></span></td>
                                <td><span><?= esc($a['sender_type'] ?? 'admin') ?></span></td>
                                <td>
                                    <div><?= esc(date('M d, Y', $date)) ?></div>
                                    <small class="text-muted"><?= esc(date('h:i A', $date)) ?></small>
                                </td>
                                <td><span class="badge badge-success">Published</span></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" type="button" data-toggle="dropdown">
                                            <i class="dw dw-more"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                            <a class="dropdown-item" href="#" onclick="viewAnnouncement(<?= (int)$a['id'] ?>)"><i class="dw dw-eye"></i> View</a>
                                            <a class="dropdown-item" href="#" onclick="editAnnouncement(<?= (int)$a['id'] ?>)"><i class="dw dw-edit2"></i> Edit</a>
                                            <a class="dropdown-item text-warning" href="#" onclick="unpublishAnnouncement(<?= (int)$a['id'] ?>)"><i class="dw dw-minus-circle"></i> Unpublish</a>
                                            <a class="dropdown-item text-danger" href="#" onclick="deleteAnnouncement(<?= (int)$a['id'] ?>)"><i class="dw dw-delete-3"></i> Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="text-center py-4"><i class="dw dw-megaphone font-24"></i><div class="mt-2">No announcements found.</div></td></tr>
                    <?php endif; ?>
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
        const row = document.querySelector(tr input[value="${id}"]).closest('tr');
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
        const row = document.querySelector(tr input[value="${id}"]).closest('tr');
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
        const row = document.querySelector(tr input[value="${id}"]).closest('tr');
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
        const row = document.querySelector(tr input[value="${id}"]).closest('tr');
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

// Load announcements dynamically
function loadAnnouncements() {
    fetch('<?= site_url('admin/getAnnouncements') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayAnnouncements(data.announcements);
            } else {
                console.error('Failed to load announcements:', data.message);
                showNoAnnouncements();
            }
        })
        .catch(error => {
            console.error('Error loading announcements:', error);
            showNoAnnouncements();
        });
}

function displayAnnouncements(announcements) {
    const announcementList = document.getElementById('announcementList');
    const tableBody = document.getElementById('announcementsTableBody');
    const loadingElement = document.getElementById('loadingAnnouncements');
    
    // Hide loading
    if (loadingElement) {
        loadingElement.style.display = 'none';
    }
    
    if (announcements.length === 0) {
        showNoAnnouncements();
        return;
    }
    
    // Display recent announcements in the list
    let listHtml = '';
    announcements.slice(0, 3).forEach(announcement => {
        const date = new Date(announcement.publish_date || announcement.created_at).toLocaleDateString();
        const time = new Date(announcement.publish_date || announcement.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        const priorityClass = announcement.priority === 'urgent' ? 'danger' : announcement.priority === 'high' ? 'warning' : 'success';
        
        listHtml += `
            <div class="list-group-item flex-column align-items-start mb-2 rounded shadow-sm">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1 text-dark">${announcement.title}</h5>
                    <small class="text-muted"><i class="dw dw-time"></i> ${date}</small>
                </div>
                <p class="mb-1 text-secondary">${announcement.content.substring(0, 150)}${announcement.content.length > 150 ? '...' : ''}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <small class="text-info mr-3"><i class="dw dw-user1"></i> ${announcement.audience_type}</small>
                        <span class="badge badge-${priorityClass}">${announcement.priority}</span>
                        <span class="badge badge-success ml-2">Published</span>
                    </div>
                    <button class="btn btn-primary btn-sm" onclick="viewAnnouncement(${announcement.id}, '${announcement.title.replace(/'/g, "\\'")}', '${announcement.content.replace(/'/g, "\\'")}', '${announcement.audience_type}', '${date} ${time}')">
                        <i class="dw dw-eye"></i> View Details
                    </button>
                </div>
            </div>
        `;
    });
    
    announcementList.innerHTML = listHtml;
    
    // Display all announcements in the table
    let tableHtml = '';
    announcements.forEach(announcement => {
        const date = new Date(announcement.publish_date || announcement.created_at);
        const formattedDate = date.toLocaleDateString();
        const formattedTime = date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        const audienceClass = announcement.audience_type === 'All' ? 'primary' : 
                             announcement.audience_type === 'Students' ? 'info' : 
                             announcement.audience_type === 'Teachers' ? 'warning' : 'secondary';
        
        tableHtml += `
            <tr data-status="published" data-audience="${announcement.audience_type}">
                <td>
                    <input type="checkbox" class="announcement-checkbox" value="${announcement.id}">
                </td>
                <td>
                    <div class="font-weight-bold text-dark">${announcement.title}</div>
                </td>
                <td>
                    <div class="text-truncate" style="max-width: 200px;" title="${announcement.content}">
                        ${announcement.content.substring(0, 80)}${announcement.content.length > 80 ? '...' : ''}
                    </div>
                </td>
                <td>
                    <span class="badge badge-${audienceClass}">${announcement.audience_type}</span>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar mr-2" style="width: 30px; height: 30px; border-radius: 50%; background: #007bff; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: bold;">
                            ${announcement.sender_type.charAt(0).toUpperCase()}
                        </div>
                        <span>${announcement.sender_type}</span>
                    </div>
                </td>
                <td>
                    <div>${formattedDate}</div>
                    <small class="text-muted">${formattedTime}</small>
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
                            <a class="dropdown-item" href="#" onclick="viewAnnouncement(${announcement.id})"><i class="dw dw-eye"></i> View</a>
                            <a class="dropdown-item" href="#" onclick="editAnnouncement(${announcement.id})"><i class="dw dw-edit2"></i> Edit</a>
                            <a class="dropdown-item text-warning" href="#" onclick="unpublishAnnouncement(${announcement.id})"><i class="dw dw-minus-circle"></i> Unpublish</a>
                            <a class="dropdown-item text-danger" href="#" onclick="deleteAnnouncement(${announcement.id})"><i class="dw dw-delete-3"></i> Delete</a>
                        </div>
                    </div>
                </td>
            </tr>
        `;
    });
    
    tableBody.innerHTML = tableHtml;
}

function showNoAnnouncements() {
    const announcementList = document.getElementById('announcementList');
    const tableBody = document.getElementById('announcementsTableBody');
    const noAnnouncements = document.getElementById('noAnnouncements');
    
    if (announcementList) {
        announcementList.innerHTML = '<div class="text-center text-muted py-4"><i class="dw dw-megaphone font-48"></i><div class="mt-2">No announcements yet.</div></div>';
    }
    
    if (tableBody) {
        tableBody.innerHTML = '<tr><td colspan="8" class="text-center py-4"><i class="dw dw-megaphone font-24"></i><div class="mt-2">No announcements found.</div></td></tr>';
    }
}

// Initialize page (server-rendered; keep JS filters working)
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('statusFilter').value = 'all';
});

// ...existing code...
</script>

<?= $this->endSection() ?>