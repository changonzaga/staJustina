<?= $this->extend('backend/student/layout/pages-layout') ?>

<?= $this->section('scripts') ?>
<script src="/backend/src/plugins/apexcharts/apexcharts.min.js"></script>
<script src="/backend/vendors/scripts/grade-distribution-chart.js"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Student Report Card</h4>
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
                        Report Card
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card-box">
            <div class="pd-20">
                <div class="clearfix mb-20">
                    <div class="pull-left">
                        <h4 class="text-blue h4">Academic Performance</h4>
                        <p>School Year 2024-2025, 1st Semester</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-30">
        <div class="card-box height-100-p overflow-hidden">
            <div class="profile-tab height-100-p">
                <div class="tab height-100-p">
                    <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#grades" role="tab">Grades</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#behavior" role="tab">Behavior</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <!-- Grades Tab -->
                        <div class="tab-pane fade show active" id="grades" role="tabpanel">
                            <div class="pd-20">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h5 class="text-blue">Academic Performance</h5>
                                        <p class="mb-0">School Year 2024-2025, 1st Semester</p>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4 mb-20">
                                        <div class="card-box pd-20 height-100-p">
                                            <div class="d-flex justify-content-between pb-10">
                                                <div class="h5 mb-0">GPA</div>
                                                <div class="dropdown">
                                                    <a class="btn btn-link p-0 font-24" data-toggle="dropdown" href="#">
                                                        <i class="dw dw-more"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="h1 font-weight-bold text-blue">3.75</div>
                                            <div class="text-muted"><i class="icon-copy fa fa-arrow-up text-success mr-1"></i> 0.12 from last quarter</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-20">
                                        <div class="card-box pd-20 height-100-p">
                                            <div class="d-flex justify-content-between pb-10">
                                                <div class="h5 mb-0">Passed</div>
                                                <div class="dropdown">
                                                    <a class="btn btn-link p-0 font-24" data-toggle="dropdown" href="#">
                                                        <i class="dw dw-more"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="h1 font-weight-bold text-success">25</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-20">
                                        <div class="card-box pd-20 height-100-p">
                                            <div class="d-flex justify-content-between pb-10">
                                                <div class="h5 mb-0">Failed</div>
                                                <div class="dropdown">
                                                    <a class="btn btn-link p-0 font-24" data-toggle="dropdown" href="#">
                                                        <i class="dw dw-more"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="h1 font-weight-bold text-danger">1</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                        <!-- Behavior Tab -->
                        <div class="tab-pane fade" id="behavior" role="tabpanel">
                            <div class="pd-20">
                                <h5 class="text-blue mb-3">Core Values Assessment</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Core Values</th>
                                                <th>Q1</th>
                                                <th>Q2</th>
                                                <th>Q3</th>
                                                <th>Q4</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Maka-Diyos (Faith)</td>
                                                <td>AO</td>
                                                <td>AO</td>
                                                <td>--</td>
                                                <td>--</td>
                                            </tr>
                                            <tr>
                                                <td>Maka-Tao (Respect)</td>
                                                <td>SO</td>
                                                <td>AO</td>
                                                <td>--</td>
                                                <td>--</td>
                                            </tr>
                                            <tr>
                                                <td>Makakalikasan (Environmental Awareness)</td>
                                                <td>AO</td>
                                                <td>AO</td>
                                                <td>--</td>
                                                <td>--</td>
                                            </tr>
                                            <tr>
                                                <td>Makabansa (Nationalism)</td>
                                                <td>SO</td>
                                                <td>SO</td>
                                                <td>--</td>
                                                <td>--</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="card-box mt-4">
                                    <div class="pd-20">
                                        <h5 class="text-blue mb-3">Behavior Marking Scale</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <ul class="list-group">
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        AO - Always Observed
                                                        <span class="badge badge-primary badge-pill">90-100</span>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        SO - Sometimes Observed
                                                        <span class="badge badge-primary badge-pill">80-89</span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <ul class="list-group">
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        RO - Rarely Observed
                                                        <span class="badge badge-primary badge-pill">75-79</span>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        NO - Not Observed
                                                        <span class="badge badge-primary badge-pill">Below 75</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grades Summary Section -->
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card-box">
            <div class="pd-20">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="text-blue h4">Grades Summary</h4>
                        <p class="mb-0">Academic performance across all subjects</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="dropdown">
                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                <i class="dw dw-more"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a>
                                <a class="dropdown-item" href="#"><i class="dw dw-download"></i> Download</a>
                                <a class="dropdown-item" href="#"><i class="dw dw-print"></i> Print</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-20">
                <table class="data-table table stripe hover nowrap">
                    <thead>
                        <tr>
                            <th class="table-plus">Subject</th>
                            <th>1st Quarter</th>
                            <th>2nd Quarter</th>
                            <th>3rd Quarter</th>
                            <th>4th Quarter</th>
                            <th>Final Grade</th>
                            <th class="datatable-nosort">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="table-plus">Mathematics</td>
                            <td>92</td>
                            <td>88</td>
                            <td>90</td>
                            <td>--</td>
                            <td>90</td>
                            <td><span class="badge badge-success">Passed</span></td>
                        </tr>
                        <tr>
                            <td class="table-plus">Science</td>
                            <td>87</td>
                            <td>85</td>
                            <td>88</td>
                            <td>--</td>
                            <td>87</td>
                            <td><span class="badge badge-success">Passed</span></td>
                        </tr>
                        <tr>
                            <td class="table-plus">English</td>
                            <td>90</td>
                            <td>92</td>
                            <td>89</td>
                            <td>--</td>
                            <td>90</td>
                            <td><span class="badge badge-success">Passed</span></td>
                        </tr>
                        <tr>
                            <td class="table-plus">Filipino</td>
                            <td>88</td>
                            <td>90</td>
                            <td>92</td>
                            <td>--</td>
                            <td>90</td>
                            <td><span class="badge badge-success">Passed</span></td>
                        </tr>
                        <tr>
                            <td class="table-plus">Social Studies</td>
                            <td>85</td>
                            <td>87</td>
                            <td>86</td>
                            <td>--</td>
                            <td>86</td>
                            <td><span class="badge badge-success">Passed</span></td>
                        </tr>
                        <tr>
                            <td class="table-plus">Physical Education</td>
                            <td>95</td>
                            <td>93</td>
                            <td>94</td>
                            <td>--</td>
                            <td>94</td>
                            <td><span class="badge badge-success">Passed</span></td>
                        </tr>
                        <tr>
                            <td class="table-plus">Arts</td>
                            <td>90</td>
                            <td>92</td>
                            <td>91</td>
                            <td>--</td>
                            <td>91</td>
                            <td><span class="badge badge-success">Passed</span></td>
                        </tr>
                        <tr>
                            <td class="table-plus">Values Education</td>
                            <td>93</td>
                            <td>91</td>
                            <td>92</td>
                            <td>--</td>
                            <td>92</td>
                            <td><span class="badge badge-success">Passed</span></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>General Average</th>
                            <th>90</th>
                            <th>89.75</th>
                            <th>90.25</th>
                            <th>--</th>
                            <th>90</th>
                            <th><span class="badge badge-success">Passed</span></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Marking Scale Section -->
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card-box">
            <div class="pd-20">
                <h4 class="text-blue h4">Marking Scale</h4>
            </div>
            <div class="pb-20 px-4">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Grade Range</th>
                            <th>Descriptor</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>90-100</td>
                            <td>Outstanding</td>
                            <td><span class="badge badge-success">Passed</span></td>
                        </tr>
                        <tr>
                            <td>85-89</td>
                            <td>Very Satisfactory</td>
                            <td><span class="badge badge-success">Passed</span></td>
                        </tr>
                        <tr>
                            <td>80-84</td>
                            <td>Satisfactory</td>
                            <td><span class="badge badge-success">Passed</span></td>
                        </tr>
                        <tr>
                            <td>75-79</td>
                            <td>Fairly Satisfactory</td>
                            <td><span class="badge badge-success">Passed</span></td>
                        </tr>
                        <tr>
                            <td>Below 75</td>
                            <td>Did Not Meet Expectations</td>
                            <td><span class="badge badge-danger">Failed</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Grade Distribution Section -->
<div class="row">
    <!-- Grade Distribution Box -->
    <div class="col-md-12 mb-30">
        <div class="card-box height-100-p">
            <div class="pd-20">
                <h4 class="text-blue h4">Grade Distribution</h4>
            </div>
            <div class="pb-20 px-4">
                <div id="chart5" style="height: 250px;"></div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>