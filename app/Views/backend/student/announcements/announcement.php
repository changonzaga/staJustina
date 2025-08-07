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

<div class="card-box mb-30 shadow-sm border-0">
    <div class="d-flex justify-content-between align-items-center pd-20 pb-0">
        <div>
            <h4 class="h4 text-blue mb-1"><i class="dw dw-edit2"></i> Create New Announcement</h4>
            <p class="mb-0 text-secondary">Share important news, events, or notifications with everyone.</p>
        </div>
        <button class="btn btn-primary btn-sm" id="previewAnnouncementBtn">
            <i class="dw dw-eye"></i> Preview
        </button>
    </div>
    <div class="pd-20 pt-2">
        <form action="<?= site_url('backend/pages/announcement/create') ?>" method="post">
            <div class="form-group mb-3">
                <label for="announcementTitle" class="font-weight-bold">Title</label>
                <input type="text" name="title" id="announcementTitle" class="form-control" placeholder="Enter announcement title..." required maxlength="120">
            </div>
            <div class="form-group mb-3">
                <label for="announcementContent" class="font-weight-bold">Content</label>
                <textarea class="textarea_editor form-control border-radius-0" name="content" id="announcementContent" rows="7" placeholder="Write your announcement here..." required></textarea>
            </div>
            <div class="form-group mb-3">
                <label class="font-weight-bold">Audience</label>
                <select name="audience" class="form-control" required>
                    <option value="All">All Users</option>
                    <option value="Students">Students Only</option>
                    <option value="Teachers">Teachers Only</option>
                    <option value="Parents">Parents Only</option>
                </select>
            </div>
            <div class="form-group mb-0 d-flex justify-content-end">
                <button type="reset" class="btn btn-outline-secondary mr-2"><i class="dw dw-refresh"></i> Clear</button>
                <button type="submit" class="btn btn-success"><i class="dw dw-upload"></i> Publish Announcement</button>
            </div>
        </form>
    </div>
</div>

<div class="card-box mt-4 shadow-sm border-0">
    <div class="pd-20 pb-0 d-flex align-items-center justify-content-between">
        <h4 class="h4 text-blue mb-0"><i class="dw dw-list"></i> Recent Announcements</h4>
        <a href="<?= site_url('backend/pages/announcement/history') ?>" class="btn btn-link text-secondary"><i class="dw dw-calendar1"></i> View All</a>
    </div>
    <div class="pd-20 pt-2">
        <!-- Example: Announcement List (replace with dynamic content as needed) -->
        <div class="list-group" id="announcementList">
            <!-- If no announcements, show a message -->
            <div class="text-center text-muted py-4" style="display: none;" id="noAnnouncements">
                <i class="dw dw-megaphone font-48"></i>
                <div class="mt-2">No announcements yet.</div>
            </div>
            <!-- Example Announcement Item -->
            <!--
            <a href="#" class="list-group-item list-group-item-action flex-column align-items-start mb-2 rounded shadow-sm">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1 text-dark">[Announcement Title]</h5>
                    <small class="text-muted"><i class="dw dw-time"></i> [Date]</small>
                </div>
                <p class="mb-1 text-secondary">[Short content preview...]</p>
                <small class="text-info"><i class="dw dw-user1"></i> [Audience]</small>
            </a>
            -->
            <!-- ...existing code for dynamic announcement items... -->
        </div>
    </div>
</div>

<!-- Announcement Preview Modal -->
<div class="modal fade" id="previewAnnouncementModal" tabindex="-1" role="dialog" aria-labelledby="previewAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="previewAnnouncementModalLabel"><i class="dw dw-eye"></i> Announcement Preview</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h4 id="previewTitle"></h4>
                <div class="mb-2 text-muted" id="previewAudience"></div>
                <div id="previewContent" class="border rounded p-3 bg-light"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close Preview</button>
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
// Preview Announcement Modal
document.getElementById('previewAnnouncementBtn').addEventListener('click', function() {
    const title = document.getElementById('announcementTitle').value.trim();
    const content = document.getElementById('announcementContent').value.trim();
    const audience = document.querySelector('select[name="audience"]').value;
    document.getElementById('previewTitle').textContent = title || '(No Title)';
    document.getElementById('previewAudience').textContent = 'Audience: ' + audience;
    document.getElementById('previewContent').innerHTML = content ? content.replace(/\n/g, '<br>') : '<em>No content.</em>';
    $('#previewAnnouncementModal').modal('show');
});

// Example: Show/hide "No announcements" message (replace with dynamic logic)
if (document.querySelectorAll('#announcementList .list-group-item').length === 0) {
    document.getElementById('noAnnouncements').style.display = 'block';
}

// ...existing code...
</script>

<?= $this->endSection() ?>