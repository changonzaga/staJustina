<?= $this->extend('backend/student/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4><i class="dw dw-megaphone text-primary"></i> Announcements & Notifications</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb bg-light px-3 py-2 rounded">
                    <li class="breadcrumb-item">
                        <a href="<?= site_url('student/dashboard')?>"><i class="dw dw-house-1"></i> Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Announcements
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="card-box shadow-sm border-0 mb-3">
    <div class="pd-20">
        <h5 class="h5 text-blue mb-3"><i class="dw dw-search"></i> Search & Filter Announcements</h5>
        <form method="GET" action="<?= site_url('student/announcements') ?>" class="row">
            <div class="col-md-4 mb-3">
                <label for="search" class="form-label font-weight-bold">Search</label>
                <input type="text" class="form-control" id="search" name="search" 
                       placeholder="Search by title or content..." 
                       value="<?= esc($search ?? '') ?>">
            </div>
            <div class="col-md-3 mb-3">
                <label for="date_from" class="form-label font-weight-bold">From Date</label>
                <input type="date" class="form-control" id="date_from" name="date_from" 
                       value="<?= esc($dateFrom ?? '') ?>">
            </div>
            <div class="col-md-3 mb-3">
                <label for="date_to" class="form-label font-weight-bold">To Date</label>
                <input type="date" class="form-control" id="date_to" name="date_to" 
                       value="<?= esc($dateTo ?? '') ?>">
            </div>
            <div class="col-md-2 mb-3 d-flex align-items-end">
                <div class="btn-group w-100" role="group">
                    <button type="submit" class="btn btn-primary">
                        <i class="dw dw-search"></i> Search
                    </button>
                    <a href="<?= site_url('student/announcements') ?>" class="btn btn-outline-secondary">
                        <i class="dw dw-refresh"></i> Clear
                    </a>
                </div>
            </div>
        </form>
        
        <!-- Statistics -->
        <?php if (isset($stats) && $stats): ?>
        <div class="row mt-3">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= $stats['total_announcements'] ?? 0 ?></h5>
                        <p class="card-text small">Total Announcements</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= $stats['published_count'] ?? 0 ?></h5>
                        <p class="card-text small">Published</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= $stats['all_audience_count'] ?? 0 ?></h5>
                        <p class="card-text small">For Everyone</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= $stats['students_audience_count'] ?? 0 ?></h5>
                        <p class="card-text small">For Students</p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="card-box shadow-sm border-0">
    <div class="pd-20 pb-0 d-flex align-items-center justify-content-between">
        <h4 class="h4 text-blue mb-0"><i class="dw dw-list"></i> School Announcements</h4>
        <div class="text-muted">
            <small><i class="dw dw-info"></i> Stay updated with the latest news and announcements</small>
        </div>
    </div>
    <div class="pd-20 pt-2">
        <div class="list-group" id="announcementList">
            <?php if (empty($announcements)): ?>
                <!-- No announcements message -->
                <div class="text-center text-muted py-5" id="noAnnouncements">
                    <i class="dw dw-megaphone font-48 text-muted"></i>
                    <div class="mt-3">
                        <?php if (!empty($search) || !empty($dateFrom) || !empty($dateTo)): ?>
                            <h5 class="text-muted">No announcements found</h5>
                            <p class="text-muted">Try adjusting your search criteria or date range.</p>
                            <a href="<?= site_url('student/announcements') ?>" class="btn btn-outline-primary">
                                <i class="dw dw-refresh"></i> Clear Filters
                            </a>
                        <?php else: ?>
                            <h5 class="text-muted">No announcements yet</h5>
                            <p class="text-muted">Check back later for important updates and news from your school.</p>
                        <?php endif; ?>
            </div>
                </div>
            <?php else: ?>
                <!-- Display announcements -->
                <?php foreach ($announcements as $announcement): ?>
                    <div class="list-group-item list-group-item-action flex-column align-items-start mb-3 rounded shadow-sm border-0" style="background: #fff;">
                        <div class="d-flex w-100 justify-content-between mb-2">
                            <h5 class="mb-1 text-dark font-weight-bold">
                                <i class="dw dw-megaphone text-primary mr-2"></i>
                                <?= esc($announcement['title']) ?>
                            </h5>
                            <small class="text-muted">
                                <i class="dw dw-time"></i> 
                                <?= date('M d, Y \a\t g:i A', strtotime($announcement['created_at'])) ?>
                            </small>
                        </div>
                        
                        <div class="mb-2">
                            <p class="mb-1 text-secondary">
                                <?= esc(substr($announcement['content'], 0, 200)) ?>
                                <?php if (strlen($announcement['content']) > 200): ?>
                                    <span class="text-muted">...</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <small class="text-info mr-3">
                                    <i class="dw dw-user1"></i> 
                                    <?= esc($announcement['teacher_name'] ?? 'Unknown Teacher') ?>
                                </small>
                                <small class="text-success">
                                    <i class="dw dw-target"></i> 
                                    <?= esc($announcement['audience'] ?? 'All') ?>
                                </small>
                            </div>
                            <div>
                                <button class="btn btn-outline-primary btn-sm" onclick="viewFullAnnouncement(<?= $announcement['id'] ?>)">
                                    <i class="dw dw-eye"></i> Read More
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Full Announcement Modal -->
<div class="modal fade" id="fullAnnouncementModal" tabindex="-1" role="dialog" aria-labelledby="fullAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="fullAnnouncementModalLabel">
                    <i class="dw dw-megaphone"></i> Announcement Details
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h4 id="modalTitle" class="text-dark mb-3"></h4>
                <div class="mb-3">
                    <small class="text-muted">
                        <i class="dw dw-user1"></i> 
                        <span id="modalAuthor"></span> | 
                        <i class="dw dw-target"></i> 
                        <span id="modalAudience"></span> | 
                        <i class="dw dw-time"></i> 
                        <span id="modalDate"></span>
                    </small>
                </div>
                <div id="modalContent" class="border rounded p-3 bg-light"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="dw dw-close"></i> Close
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

.list-group-item {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef !important;
}

.list-group-item:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    background: #f8f9fa !important;
    transform: translateY(-2px);
}

.font-48 {
    font-size: 48px;
}

.text-primary {
    color: #007bff !important;
}

.text-success {
    color: #28a745 !important;
}

.text-info {
    color: #17a2b8 !important;
}

.text-muted {
    color: #6c757d !important;
}
</style>

<script>
// View full announcement function
function viewFullAnnouncement(announcementId) {
    // Find the announcement data from the page
    const announcementElement = document.querySelector([onclick="viewFullAnnouncement(${announcementId})"]).closest('.list-group-item');
    
    // Extract data from the announcement element
    const title = announcementElement.querySelector('h5').textContent.replace(/.*\s/, ''); // Remove icon
    const content = announcementElement.querySelector('p').textContent;
    const author = announcementElement.querySelector('.text-info').textContent.replace(/.*\s/, '');
    const audience = announcementElement.querySelector('.text-success').textContent.replace(/.*\s/, '');
    const date = announcementElement.querySelector('.text-muted').textContent.replace(/.*\s/, '');
    
    // Populate modal
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalAuthor').textContent = author;
    document.getElementById('modalAudience').textContent = audience;
    document.getElementById('modalDate').textContent = date;
    document.getElementById('modalContent').innerHTML = content.replace(/\n/g, '<br>');
    
    // Show modal
    $('#fullAnnouncementModal').modal('show');
}

// Enhanced search functionality
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form on Enter key in search field
    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.form.submit();
            }
        });
    }

    // Date validation
    const dateFromInput = document.getElementById('date_from');
    const dateToInput = document.getElementById('date_to');
    
    if (dateFromInput && dateToInput) {
        dateFromInput.addEventListener('change', function() {
            if (this.value && dateToInput.value && this.value > dateToInput.value) {
                alert('From date cannot be later than To date');
                this.value = '';
            }
        });
        
        dateToInput.addEventListener('change', function() {
            if (this.value && dateFromInput.value && this.value < dateFromInput.value) {
                alert('To date cannot be earlier than From date');
                this.value = '';
            }
        });
    }

    // Show loading state on form submission
    const searchForm = document.querySelector('form');
    if (searchForm) {
        searchForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="dw dw-loading dw-spin"></i> Searching...';
                submitBtn.disabled = true;
            }
        });
    }

    // Highlight search terms in results
    const searchTerm = '<?= esc($search ?? '') ?>';
    if (searchTerm) {
        highlightSearchTerms(searchTerm);
    }
});

function highlightSearchTerms(term) {
    const announcements = document.querySelectorAll('.list-group-item');
    announcements.forEach(function(announcement) {
        const title = announcement.querySelector('h5');
        const content = announcement.querySelector('p');
        
        if (title) {
            title.innerHTML = title.innerHTML.replace(
                new RegExp(term, 'gi'), 
                '<mark class="bg-warning">$&</mark>'
            );
        }
        
        if (content) {
            content.innerHTML = content.innerHTML.replace(
                new RegExp(term, 'gi'), 
                '<mark class="bg-warning">$&</mark>'
            );
        }
    });
}

// Auto-refresh announcements every 5 minutes (optional)
setInterval(function() {
    // You can implement auto-refresh here if needed
    // location.reload();
}, 300000); // 5 minutes
</script>

<?= $this->endSection() ?>