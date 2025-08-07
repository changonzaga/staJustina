<?= $this->extend('backend/teacher/layout/pages-layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Student Attendance Records</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= site_url('teacher/dashboard') ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Manage Students</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Attendance Records
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
                    <h4 class="text-blue h4">Class Attendance</h4>
                    <p>School Year 2024-2025</p>
                </div>
                <div class="pull-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary" id="takeAttendanceBtn">
                            <i class="icon-copy bi bi-calendar-check"></i> Take Attendance
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="exportAttendanceBtn">
                            <i class="icon-copy bi bi-file-earmark-excel"></i> Export
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filter Controls -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="classSelect">Class</label>
                        <select class="form-control" id="classSelect">
                            <option value="">All Classes</option>
                            <option value="1">Grade 7 - St. Francis</option>
                            <option value="2">Grade 8 - St. Clare</option>
                            <option value="3">Grade 9 - St. Anthony</option>
                            <option value="4">Grade 10 - St. Agnes</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="monthSelect">Month</label>
                        <select class="form-control" id="monthSelect">
                            <option value="">All Months</option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="statusSelect">Status</label>
                        <select class="form-control" id="statusSelect">
                            <option value="">All Status</option>
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="late">Late</option>
                            <option value="excused">Excused</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="searchStudent">Search Student</label>
                        <input type="text" class="form-control" id="searchStudent" placeholder="Name or ID">
                    </div>
                </div>
            </div>

            <!-- Attendance Summary Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark">94%</div>
                                <div class="font-14 text-secondary weight-500">Average Attendance</div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon" data-color="#00eccf">
                                    <i class="icon-copy bi bi-people"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark">125</div>
                                <div class="font-14 text-secondary weight-500">Present Today</div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon" data-color="#09cc06">
                                    <i class="icon-copy bi bi-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark">8</div>
                                <div class="font-14 text-secondary weight-500">Absent Today</div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon" data-color="#ff5b5b">
                                    <i class="icon-copy bi bi-x-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark">12</div>
                                <div class="font-14 text-secondary weight-500">Late Today</div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon" data-color="#ffa70f">
                                    <i class="icon-copy bi bi-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Records Table -->
            <div class="table-responsive">
                <table class="table table-striped" id="attendanceTable">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Time In</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample Attendance Data -->
                        <tr>
                            <td>STU-001</td>
                            <td>John Smith</td>
                            <td>Grade 7 - St. Francis</td>
                            <td>Oct 15, 2024</td>
                            <td><span class="badge badge-success">Present</span></td>
                            <td>7:45 AM</td>
                            <td>-</td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a>
                                        <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>STU-002</td>
                            <td>Maria Garcia</td>
                            <td>Grade 7 - St. Francis</td>
                            <td>Oct 15, 2024</td>
                            <td><span class="badge badge-warning">Late</span></td>
                            <td>8:15 AM</td>
                            <td>Traffic</td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a>
                                        <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>STU-003</td>
                            <td>James Johnson</td>
                            <td>Grade 7 - St. Francis</td>
                            <td>Oct 15, 2024</td>
                            <td><span class="badge badge-danger">Absent</span></td>
                            <td>-</td>
                            <td>Sick</td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a>
                                        <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>STU-004</td>
                            <td>Emily Davis</td>
                            <td>Grade 7 - St. Francis</td>
                            <td>Oct 15, 2024</td>
                            <td><span class="badge badge-success">Present</span></td>
                            <td>7:50 AM</td>
                            <td>-</td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a>
                                        <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>STU-005</td>
                            <td>Robert Wilson</td>
                            <td>Grade 7 - St. Francis</td>
                            <td>Oct 15, 2024</td>
                            <td><span class="badge badge-info">Excused</span></td>
                            <td>-</td>
                            <td>Family emergency</td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a>
                                        <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
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
                    <div class="dataTables_info" id="attendanceTableInfo" role="status" aria-live="polite">
                        Showing 1 to 5 of 145 entries
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="dataTables_paginate paging_simple_numbers" id="attendanceTablePaginate">
                        <ul class="pagination">
                            <li class="paginate_button page-item previous disabled" id="attendanceTablePrevious">
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
                            <li class="paginate_button page-item">
                                <a href="#" class="page-link">4</a>
                            </li>
                            <li class="paginate_button page-item">
                                <a href="#" class="page-link">5</a>
                            </li>
                            <li class="paginate_button page-item next" id="attendanceTableNext">
                                <a href="#" class="page-link">Next</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Charts -->
<div class="row">
    <div class="col-md-6 mb-30">
        <div class="card-box pd-20">
            <div class="clearfix mb-20">
                <div class="pull-left">
                    <h4 class="text-blue h4">Monthly Attendance Overview</h4>
                </div>
            </div>
            <div id="monthlyAttendanceChart" style="height: 300px;"></div>
        </div>
    </div>
    <div class="col-md-6 mb-30">
        <div class="card-box pd-20">
            <div class="clearfix mb-20">
                <div class="pull-left">
                    <h4 class="text-blue h4">Attendance by Class</h4>
                </div>
            </div>
            <div id="classAttendanceChart" style="height: 300px;"></div>
        </div>
    </div>
</div>

<!-- Take Attendance Modal -->
<div class="modal fade" id="takeAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="takeAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="takeAttendanceModalLabel">Take Attendance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="attendanceForm">
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Date</label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control date-picker" placeholder="Select Date" type="text" id="attendanceDate" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Class</label>
                        <div class="col-sm-12 col-md-10">
                            <select class="form-control" id="attendanceClass" required>
                                <option value="">Select Class</option>
                                <option value="1">Grade 7 - St. Francis</option>
                                <option value="2">Grade 8 - St. Clare</option>
                                <option value="3">Grade 9 - St. Anthony</option>
                                <option value="4">Grade 10 - St. Agnes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Subject</label>
                        <div class="col-sm-12 col-md-10">
                            <select class="form-control" id="attendanceSubject" required>
                                <option value="">Select Subject</option>
                                <option value="1">Mathematics</option>
                                <option value="2">Science</option>
                                <option value="3">English</option>
                                <option value="4">Filipino</option>
                                <option value="5">Social Studies</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Status</th>
                                    <th>Time In</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Sample Student List for Attendance -->
                                <tr>
                                    <td>STU-001</td>
                                    <td>John Smith</td>
                                    <td>
                                        <select class="form-control" name="status_STU-001">
                                            <option value="present">Present</option>
                                            <option value="absent">Absent</option>
                                            <option value="late">Late</option>
                                            <option value="excused">Excused</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="time" class="form-control" name="time_STU-001">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="remarks_STU-001" placeholder="Remarks">
                                    </td>
                                </tr>
                                <tr>
                                    <td>STU-002</td>
                                    <td>Maria Garcia</td>
                                    <td>
                                        <select class="form-control" name="status_STU-002">
                                            <option value="present">Present</option>
                                            <option value="absent">Absent</option>
                                            <option value="late">Late</option>
                                            <option value="excused">Excused</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="time" class="form-control" name="time_STU-002">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="remarks_STU-002" placeholder="Remarks">
                                    </td>
                                </tr>
                                <tr>
                                    <td>STU-003</td>
                                    <td>James Johnson</td>
                                    <td>
                                        <select class="form-control" name="status_STU-003">
                                            <option value="present">Present</option>
                                            <option value="absent">Absent</option>
                                            <option value="late">Late</option>
                                            <option value="excused">Excused</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="time" class="form-control" name="time_STU-003">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="remarks_STU-003" placeholder="Remarks">
                                    </td>
                                </tr>
                                <tr>
                                    <td>STU-004</td>
                                    <td>Emily Davis</td>
                                    <td>
                                        <select class="form-control" name="status_STU-004">
                                            <option value="present">Present</option>
                                            <option value="absent">Absent</option>
                                            <option value="late">Late</option>
                                            <option value="excused">Excused</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="time" class="form-control" name="time_STU-004">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="remarks_STU-004" placeholder="Remarks">
                                    </td>
                                </tr>
                                <tr>
                                    <td>STU-005</td>
                                    <td>Robert Wilson</td>
                                    <td>
                                        <select class="form-control" name="status_STU-005">
                                            <option value="present">Present</option>
                                            <option value="absent">Absent</option>
                                            <option value="late">Late</option>
                                            <option value="excused">Excused</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="time" class="form-control" name="time_STU-005">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="remarks_STU-005" placeholder="Remarks">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveAttendance">Save Attendance</button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    $(document).ready(function() {
        // Initialize date picker
        $('.date-picker').datepicker({
            todayHighlight: true,
            autoclose: true
        });

        // Search functionality
        $("#searchStudent").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#attendanceTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        // Filter by class
        $("#classSelect").on("change", function() {
            var value = $(this).val();
            if (value === "") {
                $("#attendanceTable tbody tr").show();
            } else {
                $("#attendanceTable tbody tr").hide();
                $("#attendanceTable tbody tr").each(function() {
                    if ($(this).find("td:eq(2)").text().indexOf(value) > -1) {
                        $(this).show();
                    }
                });
            }
        });

        // Filter by status
        $("#statusSelect").on("change", function() {
            var value = $(this).val();
            if (value === "") {
                $("#attendanceTable tbody tr").show();
            } else {
                $("#attendanceTable tbody tr").hide();
                $("#attendanceTable tbody tr").each(function() {
                    if ($(this).find("td:eq(4) span").text().toLowerCase() === value) {
                        $(this).show();
                    }
                });
            }
        });

        // Take Attendance Modal
        $("#takeAttendanceBtn").on("click", function() {
            $("#takeAttendanceModal").modal("show");
        });

        // Save Attendance
        $("#saveAttendance").on("click", function() {
            // Validate form
            var form = document.getElementById("attendanceForm");
            if (form.checkValidity() === false) {
                form.reportValidity();
                return;
            }

            // In a real application, you would send this data to the server
            // For demo purposes, we'll just show an alert and close the modal
            alert("Attendance saved successfully!");
            $("#takeAttendanceModal").modal("hide");

            // Reset form
            $("#attendanceForm")[0].reset();
        });

        // Monthly Attendance Chart
        var monthlyAttendanceOptions = {
            series: [{
                name: 'Present',
                data: [92, 89, 94, 91, 95, 93, 90, 92, 94, 93, 0, 0]
            }, {
                name: 'Absent',
                data: [5, 7, 3, 6, 3, 4, 7, 5, 3, 4, 0, 0]
            }, {
                name: 'Late',
                data: [3, 4, 3, 3, 2, 3, 3, 3, 3, 3, 0, 0]
            }],
            chart: {
                type: 'bar',
                height: 300,
                stacked: true,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            },
            yaxis: {
                title: {
                    text: 'Percentage (%)',
                    style: {
                        fontSize: '12px'
                    }
                },
                max: 100
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + "%"
                    }
                }
            },
            colors: ['#09cc06', '#ff5b5b', '#ffa70f']
        };

        var monthlyAttendanceChart = new ApexCharts(document.querySelector("#monthlyAttendanceChart"), monthlyAttendanceOptions);
        monthlyAttendanceChart.render();

        // Class Attendance Chart
        var classAttendanceOptions = {
            series: [94, 91, 93, 89],
            chart: {
                type: 'donut',
                height: 300
            },
            labels: ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'],
            colors: ['#0051d4', '#00a7e6', '#6c757d', '#28a745'],
            legend: {
                position: 'bottom'
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var classAttendanceChart = new ApexCharts(document.querySelector("#classAttendanceChart"), classAttendanceOptions);
        classAttendanceChart.render();
    });
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>