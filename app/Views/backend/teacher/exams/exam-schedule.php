<?= $this->extend('backend/teacher/layout/pages-layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Exam Schedule</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= site_url('teacher/dashboard') ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Exam Schedule
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
                    <h4 class="text-blue h4">Upcoming Examinations</h4>
                    <p>School Year 2024-2025</p>
                </div>
                <div class="pull-right">
                    <div class="dropdown">
                        <a class="btn btn-primary dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                            Current Quarter
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#">1st Quarter</a>
                            <a class="dropdown-item" href="#">2nd Quarter</a>
                            <a class="dropdown-item" href="#">3rd Quarter</a>
                            <a class="dropdown-item" href="#">4th Quarter</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter and Search -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" id="searchExam" placeholder="Search by subject or room...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control" id="filterType">
                            <option value="">All Exam Types</option>
                            <option value="Written">Written</option>
                            <option value="Practical">Practical</option>
                            <option value="Oral">Oral</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control" id="filterStatus">
                            <option value="">All Status</option>
                            <option value="Upcoming">Upcoming</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Exam Schedule Table -->
            <div class="table-responsive">
                <table class="table table-striped" id="examTable">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Room</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample Exam Schedule Data -->
                        <tr>
                            <td>Mathematics</td>
                            <td>October 15, 2024</td>
                            <td>8:00 AM - 10:00 AM</td>
                            <td>Room 101</td>
                            <td>Written</td>
                            <td><span class="badge badge-primary">Upcoming</span></td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a>
                                        <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
                                        <a class="dropdown-item" href="#"><i class="dw dw-delete-3"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Science</td>
                            <td>October 16, 2024</td>
                            <td>8:00 AM - 10:00 AM</td>
                            <td>Room 102</td>
                            <td>Written</td>
                            <td><span class="badge badge-primary">Upcoming</span></td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a>
                                        <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
                                        <a class="dropdown-item" href="#"><i class="dw dw-delete-3"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>English</td>
                            <td>October 17, 2024</td>
                            <td>8:00 AM - 10:00 AM</td>
                            <td>Room 103</td>
                            <td>Written</td>
                            <td><span class="badge badge-primary">Upcoming</span></td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a>
                                        <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
                                        <a class="dropdown-item" href="#"><i class="dw dw-delete-3"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Filipino</td>
                            <td>October 18, 2024</td>
                            <td>8:00 AM - 10:00 AM</td>
                            <td>Room 104</td>
                            <td>Written</td>
                            <td><span class="badge badge-primary">Upcoming</span></td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a>
                                        <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
                                        <a class="dropdown-item" href="#"><i class="dw dw-delete-3"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Social Studies</td>
                            <td>October 19, 2024</td>
                            <td>8:00 AM - 10:00 AM</td>
                            <td>Room 105</td>
                            <td>Written</td>
                            <td><span class="badge badge-primary">Upcoming</span></td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a>
                                        <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
                                        <a class="dropdown-item" href="#"><i class="dw dw-delete-3"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="row mt-3">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info" id="examTableInfo" role="status" aria-live="polite">
                        Showing 1 to 5 of 5 entries
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="dataTables_paginate paging_simple_numbers" id="examTablePaginate">
                        <ul class="pagination">
                            <li class="paginate_button page-item previous disabled" id="examTablePrevious">
                                <a href="#" class="page-link">Previous</a>
                            </li>
                            <li class="paginate_button page-item active">
                                <a href="#" class="page-link">1</a>
                            </li>
                            <li class="paginate_button page-item next disabled" id="examTableNext">
                                <a href="#" class="page-link">Next</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-30">
        <div class="card-box pd-20">
            <div class="clearfix mb-20">
                <div class="pull-left">
                    <h4 class="text-blue h4">Exam Guidelines</h4>
                </div>
                <div class="pull-right">
                    <button class="btn btn-primary btn-sm" id="editGuidelinesBtn">
                        <i class="icon-copy bi bi-pencil"></i> Edit
                    </button>
                </div>
            </div>
            <div class="pd-20 card-box mb-30">
                <div class="clearfix mb-20">
                    <ul class="list-group">
                        <li class="list-group-item">Students must bring their school ID on the day of the exam.</li>
                        <li class="list-group-item">Be present at least 15 minutes before the scheduled exam time.</li>
                        <li class="list-group-item">Bring all necessary materials (pencils, pens, calculators if allowed).</li>
                        <li class="list-group-item">Mobile phones and other electronic devices must be turned off and kept in bags.</li>
                        <li class="list-group-item">No talking or communication with other students during the exam.</li>
                        <li class="list-group-item">If you have questions, raise your hand and wait for the teacher.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-30">
        <div class="card-box pd-20">
            <div class="clearfix mb-20">
                <div class="pull-left">
                    <h4 class="text-blue h4">Study Resources</h4>
                </div>
                <div class="pull-right">
                    <button class="btn btn-primary btn-sm" id="addResourceBtn">
                        <i class="icon-copy bi bi-plus-lg"></i> Add Resource
                    </button>
                </div>
            </div>
            <div class="pd-20 card-box mb-30">
                <div class="clearfix mb-20">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="#" class="text-blue">Mathematics Review Materials</a>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="#" class="text-blue">Science Review Materials</a>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="#" class="text-blue">English Review Materials</a>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="#" class="text-blue">Filipino Review Materials</a>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="#" class="text-blue">Social Studies Review Materials</a>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Resource Modal -->
<div class="modal fade" id="addResourceModal" tabindex="-1" role="dialog" aria-labelledby="addResourceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addResourceModalLabel">Add Study Resource</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="resourceForm">
                    <div class="form-group">
                        <label for="resourceTitle">Resource Title</label>
                        <input type="text" class="form-control" id="resourceTitle" placeholder="Enter resource title" required>
                    </div>
                    <div class="form-group">
                        <label for="resourceSubject">Subject</label>
                        <select class="form-control" id="resourceSubject" required>
                            <option value="">Select Subject</option>
                            <option value="Mathematics">Mathematics</option>
                            <option value="Science">Science</option>
                            <option value="English">English</option>
                            <option value="Filipino">Filipino</option>
                            <option value="Social Studies">Social Studies</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="resourceFile">Upload File</label>
                        <input type="file" class="form-control-file" id="resourceFile" required>
                    </div>
                    <div class="form-group">
                        <label for="resourceDescription">Description</label>
                        <textarea class="form-control" id="resourceDescription" rows="3" placeholder="Enter resource description"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveResource">Save Resource</button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Search functionality
        $("#searchExam").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#examTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        // Filter by exam type
        $("#filterType").on("change", function() {
            var value = $(this).val();
            if (value === "") {
                $("#examTable tbody tr").show();
            } else {
                $("#examTable tbody tr").hide();
                $("#examTable tbody tr").each(function() {
                    if ($(this).find("td:eq(4)").text() === value) {
                        $(this).show();
                    }
                });
            }
        });

        // Filter by status
        $("#filterStatus").on("change", function() {
            var value = $(this).val();
            if (value === "") {
                $("#examTable tbody tr").show();
            } else {
                $("#examTable tbody tr").hide();
                $("#examTable tbody tr").each(function() {
                    if ($(this).find("td:eq(5) span").text() === value) {
                        $(this).show();
                    }
                });
            }
        });

        // Add Resource Modal
        $("#addResourceBtn").on("click", function() {
            $("#addResourceModal").modal("show");
        });

        // Save Resource
        $("#saveResource").on("click", function() {
            // Validate form
            var form = document.getElementById("resourceForm");
            if (form.checkValidity() === false) {
                form.reportValidity();
                return;
            }

            // Get form values
            var title = $("#resourceTitle").val();
            var subject = $("#resourceSubject").val();
            var description = $("#resourceDescription").val();

            // In a real application, you would send this data to the server
            // For demo purposes, we'll just show an alert and close the modal
            alert("Resource saved successfully!");
            $("#addResourceModal").modal("hide");

            // Reset form
            $("#resourceForm")[0].reset();
        });
    });
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>