<?= $this->extend('backend/teacher/layout/pages-layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Announcements</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= site_url('teacher/dashboard') ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Announcements
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card-box pd-20">
            <div class="clearfix mb-20">
                <div class="pull-left">
                    <h4 class="text-blue h4">School Announcements</h4>
                    <p>Stay updated with the latest announcements</p>
                </div>
                <div class="pull-right">
                    <button class="btn btn-primary btn-sm" id="createAnnouncementBtn" data-toggle="modal" data-target="#createAnnouncementModal">
                        <i class="icon-copy bi bi-plus-lg"></i> Create Announcement
                    </button>
                </div>
            </div>

            <!-- Filter and Search -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" id="searchAnnouncement" placeholder="Search announcements...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control" id="filterCategory">
                            <option value="">All Categories</option>
                            <option value="academic">Academic</option>
                            <option value="event">Event</option>
                            <option value="holiday">Holiday</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control" id="filterDate">
                            <option value="">All Time</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Announcements List -->
            <div class="announcement-list">
                <!-- Announcement Item 1 -->
                <div class="card mb-3 announcement-item">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <span class="badge badge-primary mb-2">Academic</span>
                                <h5 class="card-title">Final Exam Schedule Released</h5>
                                <p class="card-text">The final examination schedule for the current semester has been released. Please check the schedule and prepare your students accordingly.</p>
                                <div class="text-muted small">
                                    <i class="bi bi-calendar"></i> Posted on: May 15, 2024 | 
                                    <i class="bi bi-person"></i> By: Principal Johnson
                                </div>
                            </div>
                            <div class="col-md-4 text-md-right">
                                <div class="btn-group">
                                    <button class="btn btn-outline-primary btn-sm view-announcement" data-id="1">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                    <button class="btn btn-outline-success btn-sm edit-announcement" data-id="1">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm delete-announcement" data-id="1">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Announcement Item 2 -->
                <div class="card mb-3 announcement-item">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <span class="badge badge-success mb-2">Event</span>
                                <h5 class="card-title">Annual School Sports Day</h5>
                                <p class="card-text">The annual school sports day will be held on June 10, 2024. All teachers are required to attend and supervise their respective classes.</p>
                                <div class="text-muted small">
                                    <i class="bi bi-calendar"></i> Posted on: May 12, 2024 | 
                                    <i class="bi bi-person"></i> By: PE Department
                                </div>
                            </div>
                            <div class="col-md-4 text-md-right">
                                <div class="btn-group">
                                    <button class="btn btn-outline-primary btn-sm view-announcement" data-id="2">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                    <button class="btn btn-outline-success btn-sm edit-announcement" data-id="2">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm delete-announcement" data-id="2">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Announcement Item 3 -->
                <div class="card mb-3 announcement-item">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <span class="badge badge-warning mb-2">Holiday</span>
                                <h5 class="card-title">School Closed for Independence Day</h5>
                                <p class="card-text">Please be informed that the school will be closed on June 12, 2024, in observance of Independence Day. Classes will resume on June 13, 2024.</p>
                                <div class="text-muted small">
                                    <i class="bi bi-calendar"></i> Posted on: May 10, 2024 | 
                                    <i class="bi bi-person"></i> By: Administration Office
                                </div>
                            </div>
                            <div class="col-md-4 text-md-right">
                                <div class="btn-group">
                                    <button class="btn btn-outline-primary btn-sm view-announcement" data-id="3">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                    <button class="btn btn-outline-success btn-sm edit-announcement" data-id="3">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm delete-announcement" data-id="3">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="row">
                <div class="col-sm-12 col-md-7">
                    <div class="dataTables_paginate paging_simple_numbers">
                        <ul class="pagination">
                            <li class="paginate_button page-item previous disabled">
                                <a href="#" class="page-link">Previous</a>
                            </li>
                            <li class="paginate_button page-item active">
                                <a href="#" class="page-link">1</a>
                            </li>
                            <li class="paginate_button page-item">
                                <a href="#" class="page-link">2</a>
                            </li>
                            <li class="paginate_button page-item">
                                <a href="#" class="page-link">3</a>
                            </li>
                            <li class="paginate_button page-item next">
                                <a href="#" class="page-link">Next</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Announcement Modal -->
<div class="modal fade" id="createAnnouncementModal" tabindex="-1" role="dialog" aria-labelledby="createAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAnnouncementModalLabel">Create New Announcement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="announcementForm">
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Title</label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" type="text" placeholder="Announcement Title" id="announcementTitle" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Category</label>
                        <div class="col-sm-12 col-md-10">
                            <select class="form-control" id="announcementCategory" required>
                                <option value="">Select Category</option>
                                <option value="academic">Academic</option>
                                <option value="event">Event</option>
                                <option value="holiday">Holiday</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Content</label>
                        <div class="col-sm-12 col-md-10">
                            <textarea class="form-control" id="announcementContent" rows="4" placeholder="Announcement content..." required></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Target Audience</label>
                        <div class="col-sm-12 col-md-10">
                            <select class="form-control" id="announcementAudience" required>
                                <option value="all">All</option>
                                <option value="teachers">Teachers Only</option>
                                <option value="students">Students Only</option>
                                <option value="parents">Parents Only</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Attachment</label>
                        <div class="col-sm-12 col-md-10">
                            <input type="file" class="form-control-file" id="announcementAttachment">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveAnnouncement">Save Announcement</button>
            </div>
        </div>
    </div>
</div>

<!-- View Announcement Modal -->
<div class="modal fade" id="viewAnnouncementModal" tabindex="-1" role="dialog" aria-labelledby="viewAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAnnouncementModalLabel">Announcement Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="announcementDetails">
                    <!-- Announcement details will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Search functionality
        $("#searchAnnouncement").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".announcement-item").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        // Category filter
        $("#filterCategory").on("change", function() {
            var value = $(this).val().toLowerCase();
            if (value === "") {
                $(".announcement-item").show();
            } else {
                $(".announcement-item").hide();
                $(".announcement-item").each(function() {
                    if ($(this).find(".badge").text().toLowerCase() === value) {
                        $(this).show();
                    }
                });
            }
        });

        // View announcement
        $(".view-announcement").on("click", function() {
            var id = $(this).data("id");
            // In a real application, you would fetch the announcement details from the server
            // For demo purposes, we'll just show a static content
            var title = $(this).closest(".announcement-item").find(".card-title").text();
            var category = $(this).closest(".announcement-item").find(".badge").text();
            var content = $(this).closest(".announcement-item").find(".card-text").text();
            var postedInfo = $(this).closest(".announcement-item").find(".text-muted").text();

            var detailsHtml = `
                <div class="announcement-detail">
                    <div class="mb-3">
                        <span class="badge badge-${category === 'Academic' ? 'primary' : category === 'Event' ? 'success' : 'warning'} mb-2">${category}</span>
                        <h4>${title}</h4>
                        <div class="text-muted small mb-3">${postedInfo}</div>
                    </div>
                    <div class="announcement-content mb-4">
                        <p>${content}</p>
                    </div>
                    <div class="attachment-section">
                        <h6>Attachments</h6>
                        <p class="text-muted">No attachments available</p>
                    </div>
                </div>
            `;

            $("#announcementDetails").html(detailsHtml);
            $("#viewAnnouncementModal").modal("show");
        });

        // Save announcement
        $("#saveAnnouncement").on("click", function() {
            // Validate form
            var form = document.getElementById("announcementForm");
            if (form.checkValidity() === false) {
                form.reportValidity();
                return;
            }

            // Get form values
            var title = $("#announcementTitle").val();
            var category = $("#announcementCategory").val();
            var content = $("#announcementContent").val();
            var audience = $("#announcementAudience").val();

            // In a real application, you would send this data to the server
            // For demo purposes, we'll just show an alert and close the modal
            alert("Announcement saved successfully!");
            $("#createAnnouncementModal").modal("hide");

            // Reset form
            $("#announcementForm")[0].reset();
        });
    });
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>