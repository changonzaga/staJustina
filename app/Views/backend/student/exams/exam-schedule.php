<?= $this->extend('backend/student/layout/pages-layout') ?>

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
                        <a href="<?= site_url('student/dashboard') ?>">Dashboard</a>
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

            <!-- Exam Schedule Table -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Room</th>
                            <th>Type</th>
                            <th>Status</th>
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
                        </tr>
                        <tr>
                            <td>Science</td>
                            <td>October 16, 2024</td>
                            <td>8:00 AM - 10:00 AM</td>
                            <td>Room 102</td>
                            <td>Written</td>
                            <td><span class="badge badge-primary">Upcoming</span></td>
                        </tr>
                        <tr>
                            <td>English</td>
                            <td>October 17, 2024</td>
                            <td>8:00 AM - 10:00 AM</td>
                            <td>Room 103</td>
                            <td>Written</td>
                            <td><span class="badge badge-primary">Upcoming</span></td>
                        </tr>
                        <tr>
                            <td>Filipino</td>
                            <td>October 18, 2024</td>
                            <td>8:00 AM - 10:00 AM</td>
                            <td>Room 104</td>
                            <td>Written</td>
                            <td><span class="badge badge-primary">Upcoming</span></td>
                        </tr>
                        <tr>
                            <td>Social Studies</td>
                            <td>October 19, 2024</td>
                            <td>8:00 AM - 10:00 AM</td>
                            <td>Room 105</td>
                            <td>Written</td>
                            <td><span class="badge badge-primary">Upcoming</span></td>
                        </tr>
                    </tbody>
                </table>
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
            </div>
            <div class="pd-20 card-box mb-30">
                <div class="clearfix mb-20">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <a href="#" class="text-blue">Mathematics Review Materials</a>
                        </li>
                        <li class="list-group-item">
                            <a href="#" class="text-blue">Science Review Materials</a>
                        </li>
                        <li class="list-group-item">
                            <a href="#" class="text-blue">English Review Materials</a>
                        </li>
                        <li class="list-group-item">
                            <a href="#" class="text-blue">Filipino Review Materials</a>
                        </li>
                        <li class="list-group-item">
                            <a href="#" class="text-blue">Social Studies Review Materials</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>