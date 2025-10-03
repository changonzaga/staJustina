<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Create New Announcement</h4>
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
                        Create Announcement
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
        <div>
            <button class="btn btn-primary btn-sm mr-2" id="previewAnnouncementBtn">
                <i class="dw dw-eye"></i> Preview
            </button>
            <a href="<?= route_to('admin.announcements') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="dw dw-arrow-left"></i> Back to Announcements
            </a>
        </div>
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
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Audience</label>
                        <select name="audience" class="form-control" required>
                            <option value="All">All Users</option>
                            <option value="Students">Students Only</option>
                            <option value="Teachers">Teachers Only</option>
                            <option value="Parents">Parents Only</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Priority</label>
                        <select name="priority" class="form-control">
                            <option value="normal">Normal</option>
                            <option value="high">High Priority</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Publish Date</label>
                        <input type="datetime-local" name="publish_date" class="form-control">
                        <small class="text-muted">Leave empty to publish immediately</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Expiry Date</label>
                        <input type="datetime-local" name="expiry_date" class="form-control">
                        <small class="text-muted">Leave empty for no expiry</small>
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="sendNotification" name="send_notification" checked>
                    <label class="custom-control-label" for="sendNotification">Send email notification to target audience</label>
                </div>
            </div>
            <div class="form-group mb-0 d-flex justify-content-end">
                <button type="reset" class="btn btn-outline-secondary mr-2"><i class="dw dw-refresh"></i> Clear</button>
                <button type="button" class="btn btn-outline-primary mr-2" onclick="saveDraft()"><i class="dw dw-save"></i> Save as Draft</button>
                <button type="submit" class="btn btn-success"><i class="dw dw-upload"></i> Publish Announcement</button>
            </div>
        </form>
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
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h4 id="previewTitle"></h4>
                        <div class="mb-2 text-muted" id="previewAudience"></div>
                    </div>
                    <div>
                        <span id="previewPriority" class="badge"></span>
                    </div>
                </div>
                <div id="previewContent" class="border rounded p-3 bg-light"></div>
                <div class="mt-3 text-muted small">
                    <div id="previewDates"></div>
                </div>
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
</style>

<script>
// Preview Announcement Modal
document.getElementById('previewAnnouncementBtn').addEventListener('click', function() {
    const title = document.getElementById('announcementTitle').value.trim();
    const content = document.getElementById('announcementContent').value.trim();
    const audience = document.querySelector('select[name="audience"]').value;
    const priority = document.querySelector('select[name="priority"]').value;
    const publishDate = document.querySelector('input[name="publish_date"]').value;
    const expiryDate = document.querySelector('input[name="expiry_date"]').value;
    
    document.getElementById('previewTitle').textContent = title || '(No Title)';
    document.getElementById('previewAudience').textContent = 'Audience: ' + audience;
    document.getElementById('previewContent').innerHTML = content ? content.replace(/\n/g, '<br>') : '<em>No content.</em>';
    
    // Priority badge
    const priorityBadge = document.getElementById('previewPriority');
    priorityBadge.textContent = priority.charAt(0).toUpperCase() + priority.slice(1);
    priorityBadge.className = 'badge badge-' + (priority === 'urgent' ? 'danger' : priority === 'high' ? 'warning' : 'secondary');
    
    // Dates
    let datesText = '';
    if (publishDate) datesText += 'Publish: ' + new Date(publishDate).toLocaleString() + ' ';
    if (expiryDate) datesText += 'Expires: ' + new Date(expiryDate).toLocaleString();
    document.getElementById('previewDates').textContent = datesText || 'Publish: Immediately';
    
    $('#previewAnnouncementModal').modal('show');
});

// Save as Draft function
function saveDraft() {
    const formData = new FormData(document.querySelector('form'));
    formData.append('status', 'draft');
    
    // Here you would typically send the data to the server
    alert('Announcement saved as draft!');
}
</script>

<?= $this->endSection() ?>