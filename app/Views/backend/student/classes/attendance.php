<?= $this->extend('backend/student/layout/pages-layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Attendance Record</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= site_url('student/dashboard') ?>">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="#">My Classes</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Attendance Record
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-30">
        <div class="pd-20 card-box">
            <div class="clearfix mb-20">
                <div class="pull-left">
                    <h4 class="text-blue h4">Attendance Summary</h4>
                    <p>School Year 2024-2025</p>
                </div>
                <div class="pull-right">
                    <div class="dropdown">
                        <a class="btn btn-primary dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                            October 2024
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#">September 2024</a>
                            <a class="dropdown-item" href="#">August 2024</a>
                            <a class="dropdown-item" href="#">July 2024</a>
                            <a class="dropdown-item" href="#">June 2024</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="pd-20"> 
                <h5 class="text-blue mb-3">Attendance Summary</h5> 
                <div class="row pb-10"> 
                    <div class="col-md-3 mb-20"> 
                        <div class="card-box height-100-p widget-style3"> 
                            <div class="d-flex flex-wrap"> 
                                <div class="widget-data"> 
                                    <div class="weight-700 font-24 text-dark">85</div> 
                                    <div class="font-14 text-secondary weight-500">School Days</div> 
                                </div> 
                                <div class="widget-icon"> 
                                    <div class="icon" data-color="#00eccf"><i class="icon-copy dw dw-calendar1"></i></div> 
                                </div> 
                            </div> 
                        </div> 
                    </div> 
                    <div class="col-md-3 mb-20"> 
                        <div class="card-box height-100-p widget-style3"> 
                            <div class="d-flex flex-wrap"> 
                                <div class="widget-data"> 
                                    <div class="weight-700 font-24 text-dark">82</div> 
                                    <div class="font-14 text-secondary weight-500">Present</div> 
                                </div> 
                                <div class="widget-icon"> 
                                    <div class="icon" data-color="#09cc06"><i class="icon-copy dw dw-checked"></i></div> 
                                </div> 
                            </div> 
                        </div> 
                    </div> 
                    <div class="col-md-3 mb-20"> 
                        <div class="card-box height-100-p widget-style3"> 
                            <div class="d-flex flex-wrap"> 
                                <div class="widget-data"> 
                                    <div class="weight-700 font-24 text-dark">3</div> 
                                    <div class="font-14 text-secondary weight-500">Absent</div> 
                                </div> 
                                <div class="widget-icon"> 
                                    <div class="icon" data-color="#ff5b5b"><i class="icon-copy dw dw-cancel"></i></div> 
                                </div> 
                            </div> 
                        </div> 
                    </div> 
                    <div class="col-md-3 mb-20"> 
                        <div class="card-box height-100-p widget-style3"> 
                            <div class="d-flex flex-wrap"> 
                                <div class="widget-data"> 
                                    <div class="weight-700 font-24 text-dark">3</div> 
                                    <div class="font-14 text-secondary weight-500">Tardy</div> 
                                </div> 
                                <div class="widget-icon"> 
                                    <div class="icon" data-color="#ff5b5b"><i class="icon-copy dw dw-time"></i></div> 
                                </div> 
                            </div> 
                        </div> 
                    </div> 
                </div> 
            </div> 
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Status</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>October 1, 2024</td>
                            <td>Tuesday</td>
                            <td><span class="badge badge-success">Present</span></td>
                            <td>7:25 AM</td>
                            <td>4:00 PM</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>October 2, 2024</td>
                            <td>Wednesday</td>
                            <td><span class="badge badge-success">Present</span></td>
                            <td>7:20 AM</td>
                            <td>4:00 PM</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>October 3, 2024</td>
                            <td>Thursday</td>
                            <td><span class="badge badge-warning">Late</span></td>
                            <td>7:45 AM</td>
                            <td>4:00 PM</td>
                            <td>Traffic</td>
                        </tr>
                        <tr>
                            <td>October 4, 2024</td>
                            <td>Friday</td>
                            <td><span class="badge badge-success">Present</span></td>
                            <td>7:15 AM</td>
                            <td>4:00 PM</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>October 7, 2024</td>
                            <td>Monday</td>
                            <td><span class="badge badge-success">Present</span></td>
                            <td>7:20 AM</td>
                            <td>4:00 PM</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>October 8, 2024</td>
                            <td>Tuesday</td>
                            <td><span class="badge badge-success">Present</span></td>
                            <td>7:25 AM</td>
                            <td>4:00 PM</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>October 9, 2024</td>
                            <td>Wednesday</td>
                            <td><span class="badge badge-danger">Absent</span></td>
                            <td>-</td>
                            <td>-</td>
                            <td>Sick leave</td>
                        </tr>
                        <tr>
                            <td>October 10, 2024</td>
                            <td>Thursday</td>
                            <td><span class="badge badge-success">Present</span></td>
                            <td>7:20 AM</td>
                            <td>4:00 PM</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>October 11, 2024</td>
                            <td>Friday</td>
                            <td><span class="badge badge-success">Present</span></td>
                            <td>7:15 AM</td>
                            <td>4:00 PM</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>October 14, 2024</td>
                            <td>Monday</td>
                            <td><span class="badge badge-warning">Late</span></td>
                            <td>7:40 AM</td>
                            <td>4:00 PM</td>
                            <td>Heavy rain</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Simple placeholder for charts
    // In a real application, you would use actual chart libraries like ApexCharts
    $(document).ready(function() {
        // Placeholder for chart initialization
        console.log('Attendance charts would be initialized here');
    });
</script>
<?= $this->endSection() ?>