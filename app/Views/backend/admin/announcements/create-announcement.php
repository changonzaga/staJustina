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
        <form action="<?= site_url('admin/processAnnouncement') ?>" method="post" id="announcementForm">
            <?= csrf_field() ?>
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
            <button type="submit" class="btn btn-success" id="submitBtn"><i class="dw dw-upload"></i> Publish Announcement</button>
        </div>
    </form>
    
    <script>
    document.getElementById('announcementForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.innerHTML = '<i class="dw dw-loading"></i> Publishing...';
        submitBtn.disabled = true;
        
        // Get form data
        const formData = new FormData(this);
        
        // Submit via AJAX
        fetch('<?= site_url('admin/processAnnouncement') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success modal
                showSuccessModal(data.message);
                
                // Reset form
                document.getElementById('announcementForm').reset();
                
                // Redirect to announcements page after 2 seconds
                setTimeout(() => {
                    window.location.href = '<?= site_url('admin/announcement') ?>';
                }, 2000);
            } else {
                // Show error modal
                showErrorModal(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorModal('An error occurred while creating the announcement. Please try again.');
        })
        .finally(() => {
            // Restore button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
    
    function saveDraft() {
        // Implement draft saving functionality
        alert('Draft saving functionality will be implemented soon.');
    }
    
    // Show success modal
    function showSuccessModal(message) {
        document.getElementById('successMessage').textContent = message;
        $('#successModal').modal('show');
        
        // Also show toast notification
        showToast('success', 'Success!', message);
    }
    
    // Show error modal
    function showErrorModal(message) {
        document.getElementById('errorMessage').textContent = message;
        $('#errorModal').modal('show');
        
        // Also show toast notification
        showToast('error', 'Error!', message);
    }
    
    // Toast notification function
    function showToast(type, title, message) {
        const toastId = 'toast-' + Date.now();
        const iconClass = type === 'success' ? 'dw dw-checkmark-circle' : 'dw dw-error-circle';
        const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
        
        const toastHtml = `
            <div id="${toastId}" class="toast-notification ${bgClass} text-white" style="
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                padding: 15px 20px;
                border-radius: 10px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                min-width: 300px;
                animation: slideInRight 0.3s ease-out;
            ">
                <div class="d-flex align-items-center">
                    <i class="${iconClass} font-24 mr-3"></i>
                    <div>
                        <div class="font-weight-bold">${title}</div>
                        <div class="small">${message}</div>
                    </div>
                    <button type="button" class="btn btn-link text-white ml-auto p-0" onclick="closeToast('${toastId}')">
                        <i class="dw dw-close"></i>
                    </button>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', toastHtml);
        
        // Auto close after 5 seconds
        setTimeout(() => {
            closeToast(toastId);
        }, 5000);
    }
    
    function closeToast(toastId) {
        const toast = document.getElementById(toastId);
        if (toast) {
            toast.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    }
    </script>
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

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body text-center py-5">
                <div class="mb-4">
                    <div class="success-icon mx-auto mb-3">
                        <i class="dw dw-checkmark-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="text-success mb-3">Success!</h4>
                    <p class="text-muted mb-4" id="successMessage">Announcement created successfully!</p>
                    <div class="alert alert-info d-inline-block">
                        <i class="dw dw-info"></i> You will be redirected to the announcements page in a moment...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body text-center py-5">
                <div class="mb-4">
                    <div class="error-icon mx-auto mb-3">
                        <i class="dw dw-error-circle text-danger" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="text-danger mb-3">Error!</h4>
                    <p class="text-muted mb-4" id="errorMessage">An error occurred while creating the announcement.</p>
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="dw dw-close"></i> Close
                    </button>
                </div>
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

/* Success Modal Styles */
#successModal .modal-content {
    border-radius: 15px;
    overflow: hidden;
}

#successModal .success-icon {
    animation: bounceIn 0.6s ease-out;
}

#successModal .alert {
    border-radius: 10px;
    border: none;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    color: #1976d2;
}

/* Error Modal Styles */
#errorModal .modal-content {
    border-radius: 15px;
    overflow: hidden;
}

#errorModal .error-icon {
    animation: shake 0.6s ease-out;
}

/* Animations */
@keyframes bounceIn {
    0% {
        transform: scale(0.3);
        opacity: 0;
    }
    50% {
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes shake {
    0%, 100% {
        transform: translateX(0);
    }
    10%, 30%, 50%, 70%, 90% {
        transform: translateX(-5px);
    }
    20%, 40%, 60%, 80% {
        transform: translateX(5px);
    }
}

/* Modal backdrop */
.modal-backdrop {
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
}

/* Toast Animations */
@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

/* Toast notification styles */
.toast-notification {
    transition: all 0.3s ease;
}

.toast-notification:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.2) !important;
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