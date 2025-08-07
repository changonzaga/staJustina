<?= $this->extend('backend/teacher/layout/pages-layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="title">
									<h4>Student Dashboard</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="<?= site_url('student/dashboard') ?>">Home</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Student Dashboard
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>

<div class="row clearfix progress-box">
					<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
						<div class="card-box pd-30 height-100-p">
							<div class="progress-box text-center">
								<div style="display:inline;width:120px;height:120px;"><canvas width="150" height="150" style="width: 120px; height: 120px;"></canvas><input type="text" class="knob dial1" value="80" data-width="120" data-height="120" data-linecap="round" data-thickness="0.12" data-bgcolor="#fff" data-fgcolor="#1b00ff" data-angleoffset="180" readonly="readonly" style="width: 64px; height: 40px; position: absolute; vertical-align: middle; margin-top: 40px; margin-left: -92px; border: 0px; background: none rgb(255, 255, 255); font: bold 24px Arial; text-align: center; color: rgb(27, 0, 255); padding: 0px; appearance: none;"></div>
								<h5 class="text-blue padding-top-10 h5">My Earnings</h5>
								<span class="d-block">80% Average <i class="fa fa-line-chart text-blue"></i></span>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
						<div class="card-box pd-30 height-100-p">
							<div class="progress-box text-center">
								<div style="display:inline;width:120px;height:120px;"><canvas width="150" height="150" style="width: 120px; height: 120px;"></canvas><input type="text" class="knob dial2" value="70" data-width="120" data-height="120" data-linecap="round" data-thickness="0.12" data-bgcolor="#fff" data-fgcolor="#00e091" data-angleoffset="180" readonly="readonly" style="width: 64px; height: 40px; position: absolute; vertical-align: middle; margin-top: 40px; margin-left: -92px; border: 0px; background: none rgb(255, 255, 255); font: bold 24px Arial; text-align: center; color: rgb(0, 224, 145); padding: 0px; appearance: none;"></div>
								<h5 class="text-light-green padding-top-10 h5">
									Business Captured
								</h5>
								<span class="d-block">75% Average <i class="fa text-light-green fa-line-chart"></i></span>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
						<div class="card-box pd-30 height-100-p">
							<div class="progress-box text-center">
								<div style="display:inline;width:120px;height:120px;"><canvas width="150" height="150" style="width: 120px; height: 120px;"></canvas><input type="text" class="knob dial3" value="90" data-width="120" data-height="120" data-linecap="round" data-thickness="0.12" data-bgcolor="#fff" data-fgcolor="#f56767" data-angleoffset="180" readonly="readonly" style="width: 64px; height: 40px; position: absolute; vertical-align: middle; margin-top: 40px; margin-left: -92px; border: 0px; background: none rgb(255, 255, 255); font: bold 24px Arial; text-align: center; color: rgb(245, 103, 103); padding: 0px; appearance: none;"></div>
								<h5 class="text-light-orange padding-top-10 h5">
									Projects Speed
								</h5>
								<span class="d-block">90% Average <i class="fa text-light-orange fa-line-chart"></i></span>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
						<div class="card-box pd-30 height-100-p">
							<div class="progress-box text-center">
								<div style="display:inline;width:120px;height:120px;"><canvas width="150" height="150" style="width: 120px; height: 120px;"></canvas><input type="text" class="knob dial4" value="65" data-width="120" data-height="120" data-linecap="round" data-thickness="0.12" data-bgcolor="#fff" data-fgcolor="#a683eb" data-angleoffset="180" readonly="readonly" style="width: 64px; height: 40px; position: absolute; vertical-align: middle; margin-top: 40px; margin-left: -92px; border: 0px; background: none rgb(255, 255, 255); font: bold 24px Arial; text-align: center; color: rgb(166, 131, 235); padding: 0px; appearance: none;"></div>
								<h5 class="text-light-purple padding-top-10 h5">
									Panding Orders
								</h5>
								<span class="d-block">65% Average <i class="fa text-light-purple fa-line-chart"></i></span>
							</div>
						</div>
					</div>
				</div>
					<div class="card-box mb-30">
						<div class="pd-20">
							<h4 class="text-blue h4">EMPLOYEE LIST</h4>
							<p class="mb-0">
								you can find more options
								<a class="text-primary" href="https://datatables.net/" target="_blank">Click Here</a>
							</p>
						</div>
						<div class="pb-20">
							<div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer"><div class="row"><div class="col-sm-12 col-md-6"><div class="dataTables_length" id="DataTables_Table_0_length"><label>Show <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="custom-select custom-select-sm form-control form-control-sm"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="-1">All</option></select> entries</label></div></div><div class="col-sm-12 col-md-6"><div id="DataTables_Table_0_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control form-control-sm" placeholder="Search" aria-controls="DataTables_Table_0"></label></div></div></div><div class="row"><div class="col-sm-12"><table class="data-table table stripe hover nowrap dataTable no-footer dtr-inline collapsed" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
								<thead>
									<tr role="row"><th class="table-plus datatable-nosort sorting_asc" rowspan="1" colspan="1" aria-label="Name">Name</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Age: activate to sort column ascending">Age</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending">Office</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending" style="">Address</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Start Date: activate to sort column ascending" style="">Start Date</th><th class="datatable-nosort sorting_disabled" rowspan="1" colspan="1" aria-label="Action" style="display: none;">Action</th></tr>
								</thead>
								<tbody>
									
									
									
									
									
									
									
									
									
									
									
									
								<tr role="row" class="odd">
										<td class="table-plus sorting_1" tabindex="0">Andrea J. Cagle</td>
										<td>30</td>
										<td>Gemini</td>
										<td style="">1280 Prospect Valley Road Long Beach, CA 90802</td>
										<td style="">29-03-2018</td>
										<td style="display: none;">
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
									</tr><tr role="row" class="even">
										<td class="table-plus sorting_1" tabindex="0">Andrea J. Cagle</td>
										<td>20</td>
										<td>Gemini</td>
										<td style="">2829 Trainer Avenue Peoria, IL 61602</td>
										<td style="">29-03-2018</td>
										<td style="display: none;">
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
									</tr><tr role="row" class="odd">
										<td class="table-plus sorting_1" tabindex="0">Andrea J. Cagle</td>
										<td>30</td>
										<td>Sagittarius</td>
										<td style="">1280 Prospect Valley Road Long Beach, CA 90802</td>
										<td style="">29-03-2018</td>
										<td style="display: none;">
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
									</tr><tr role="row" class="even">
										<td class="table-plus sorting_1" tabindex="0">Andrea J. Cagle</td>
										<td>25</td>
										<td>Gemini</td>
										<td style="">2829 Trainer Avenue Peoria, IL 61602</td>
										<td style="">29-03-2018</td>
										<td style="display: none;">
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
									</tr><tr role="row" class="odd">
										<td class="table-plus sorting_1" tabindex="0">Andrea J. Cagle</td>
										<td>20</td>
										<td>Sagittarius</td>
										<td style="">1280 Prospect Valley Road Long Beach, CA 90802</td>
										<td style="">29-03-2018</td>
										<td style="display: none;">
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
									</tr><tr role="row" class="even">
										<td class="table-plus sorting_1" tabindex="0">Andrea J. Cagle</td>
										<td>18</td>
										<td>Gemini</td>
										<td style="">1280 Prospect Valley Road Long Beach, CA 90802</td>
										<td style="">29-03-2018</td>
										<td style="display: none;">
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
									</tr><tr role="row" class="odd">
										<td class="table-plus sorting_1" tabindex="0">Andrea J. Cagle</td>
										<td>30</td>
										<td>Sagittarius</td>
										<td style="">1280 Prospect Valley Road Long Beach, CA 90802</td>
										<td style="">29-03-2018</td>
										<td style="display: none;">
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
									</tr><tr role="row" class="even">
										<td class="table-plus sorting_1" tabindex="0">Andrea J. Cagle</td>
										<td>30</td>
										<td>Sagittarius</td>
										<td style="">1280 Prospect Valley Road Long Beach, CA 90802</td>
										<td style="">29-03-2018</td>
										<td style="display: none;">
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
									</tr><tr role="row" class="odd">
										<td class="table-plus sorting_1" tabindex="0">Andrea J. Cagle</td>
										<td>30</td>
										<td>Gemini</td>
										<td style="">1280 Prospect Valley Road Long Beach, CA 90802</td>
										<td style="">29-03-2018</td>
										<td style="display: none;">
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
									</tr><tr role="row" class="even">
										<td class="table-plus sorting_1" tabindex="0">Andrea J. Cagle</td>
										<td>30</td>
										<td>Gemini</td>
										<td style="">1280 Prospect Valley Road Long Beach, CA 90802</td>
										<td style="">29-03-2018</td>
										<td style="display: none;">
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
									</tr></tbody>
							</table></div></div><div class="row"><div class="col-sm-12 col-md-5"><div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">1-10 of 12 entries</div></div><div class="col-sm-12 col-md-7"><div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate"><ul class="pagination"><li class="paginate_button page-item previous disabled" id="DataTables_Table_0_previous"><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="0" tabindex="0" class="page-link"><i class="ion-chevron-left"></i></a></li><li class="paginate_button page-item active"><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="1" tabindex="0" class="page-link">1</a></li><li class="paginate_button page-item "><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="2" tabindex="0" class="page-link">2</a></li><li class="paginate_button page-item next" id="DataTables_Table_0_next"><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="3" tabindex="0" class="page-link"><i class="ion-chevron-right"></i></a></li></ul></div></div></div></div>
						</div>
					</div>
				
<?= $this->endSection() ?>