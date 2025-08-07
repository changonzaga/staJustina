<?= $this->extend('backend/student/layout/pages-layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Class Timetable</h4>
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
                        Class Timetable
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="pd-20 card-box mb-30">
    <div class="clearfix mb-20">
        <div class="pull-left">
            <h4 class="text-blue h4">Weekly Class Schedule</h4>
            <p class="mb-30">Your class schedule for the current semester</p>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Time</th>
                    <th scope="col">Monday</th>
                    <th scope="col">Tuesday</th>
                    <th scope="col">Wednesday</th>
                    <th scope="col">Thursday</th>
                    <th scope="col">Friday</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">7:00 - 8:00</th>
                    <td>Mathematics</td>
                    <td>Science</td>
                    <td>English</td>
                    <td>Filipino</td>
                    <td>Social Studies</td>
                </tr>
                <tr>
                    <th scope="row">8:00 - 9:00</th>
                    <td>Science</td>
                    <td>Mathematics</td>
                    <td>Filipino</td>
                    <td>English</td>
                    <td>Physical Education</td>
                </tr>
                <tr>
                    <th scope="row">9:00 - 10:00</th>
                    <td>English</td>
                    <td>Filipino</td>
                    <td>Mathematics</td>
                    <td>Science</td>
                    <td>Arts</td>
                </tr>
                <tr>
                    <th scope="row">10:00 - 11:00</th>
                    <td>Social Studies</td>
                    <td>Physical Education</td>
                    <td>Arts</td>
                    <td>Values Education</td>
                    <td>Mathematics</td>
                </tr>
                <tr>
                    <th scope="row">11:00 - 12:00</th>
                    <td>Break</td>
                    <td>Break</td>
                    <td>Break</td>
                    <td>Break</td>
                    <td>Break</td>
                </tr>
                <tr>
                    <th scope="row">12:00 - 1:00</th>
                    <td>Computer</td>
                    <td>Music</td>
                    <td>Computer</td>
                    <td>Music</td>
                    <td>Computer</td>
                </tr>
                <tr>
                    <th scope="row">1:00 - 2:00</th>
                    <td>Filipino</td>
                    <td>English</td>
                    <td>Science</td>
                    <td>Mathematics</td>
                    <td>Social Studies</td>
                </tr>
                <tr>
                    <th scope="row">2:00 - 3:00</th>
                    <td>Arts</td>
                    <td>Values Education</td>
                    <td>Physical Education</td>
                    <td>Arts</td>
                    <td>Values Education</td>
                </tr>
                <tr>
                    <th scope="row">3:00 - 4:00</th>
                    <td>Homeroom</td>
                    <td>Homeroom</td>
                    <td>Homeroom</td>
                    <td>Homeroom</td>
                    <td>Homeroom</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>