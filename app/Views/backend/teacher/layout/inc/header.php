<div class="header">
			<div class="header-left">
				<div class="menu-icon bi bi-list"></div>
				<div
					class="search-toggle-icon bi bi-search"
					data-toggle="header_search"
				></div>
				<div class="header-search">
					<form>
						<div class="form-group mb-0">
							<i class="dw dw-search2 search-icon"></i>
							<input
								type="text"
								class="form-control search-input"
								placeholder="Search Here"
							/>
							<div class="dropdown">
								<a
									class="dropdown-toggle no-arrow"
									href="#"
									role="button"
									data-toggle="dropdown"
								>
									<i class="ion-arrow-down-c"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right">
									<div class="form-group row">
										<label class="col-sm-12 col-md-2 col-form-label"
											>From</label
										>
										<div class="col-sm-12 col-md-10">
											<input
												class="form-control form-control-sm form-control-line"
												type="text"
											/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-12 col-md-2 col-form-label">To</label>
										<div class="col-sm-12 col-md-10">
											<input
												class="form-control form-control-sm form-control-line"
												type="text"
											/>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-12 col-md-2 col-form-label"
											>Subject</label
										>
										<div class="col-sm-12 col-md-10">
											<input
												class="form-control form-control-sm form-control-line"
												type="text"
											/>
										</div>
									</div>
									<div class="text-right">
										<button class="btn btn-primary">Search</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="header-right">
				<div class="dashboard-setting user-notification">
					<div class="dropdown">
						<a
							class="dropdown-toggle no-arrow"
							href="javascript:;"
							data-toggle="right-sidebar"
						>
							<i class="dw dw-settings2"></i>
						</a>
					</div>
				</div>
				<div class="user-notification">
					<div class="dropdown">
						<a
							class="dropdown-toggle no-arrow"
							href="#"
							role="button"
							data-toggle="dropdown"
						>
							<i class="icon-copy dw dw-notification"></i>
							<span class="badge notification-active"></span>
						</a>
						<div class="dropdown-menu dropdown-menu-right">
							<div class="notification-list mx-h-350 customscroll">
								<ul>
									<li>
										<a href="#">
											<img src="/backend/vendors/images/img.jpg" alt="" />
											<h3>John Doe</h3>
											<p>
												Lorem ipsum dolor sit amet, consectetur adipisicing
												elit, sed...
											</p>
										</a>
									</li>
									<li>
										<a href="#">
											<img src="/backend/vendors/images/photo1.jpg" alt="" />
											<h3>Lea R. Frith</h3>
											<p>
												Lorem ipsum dolor sit amet, consectetur adipisicing
												elit, sed...
											</p>
										</a>
									</li>
									<li>
										<a href="#">
											<img src="/backend/vendors/images/photo2.jpg" alt="" />
											<h3>Erik L. Richards</h3>
											<p>
												Lorem ipsum dolor sit amet, consectetur adipisicing
												elit, sed...
											</p>
										</a>
									</li>
									<li>
										<a href="#">
											<img src="/backend/vendors/images/photo3.jpg" alt="" />
											<h3>John Doe</h3>
											<p>
												Lorem ipsum dolor sit amet, consectetur adipisicing
												elit, sed...
											</p>
										</a>
									</li>
									<li>
										<a href="#">
											<img src="/backend/vendors/images/photo4.jpg" alt="" />
											<h3>Renee I. Hansen</h3>
											<p>
												Lorem ipsum dolor sit amet, consectetur adipisicing
												elit, sed...
											</p>
										</a>
									</li>
									<li>
										<a href="#">
											<img src="/backend/vendors/images/img.jpg" alt="" />
											<h3>Vicki M. Coleman</h3>
											<p>
												Lorem ipsum dolor sit amet, consectetur adipisicing
												elit, sed...
											</p>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="user-info-dropdown">
					<div class="dropdown">
						<a
							class="dropdown-toggle"
							href="#"
							role="button"
							data-toggle="dropdown"
						>
                            <span class="user-icon">
                                <?php 
                                $userInfo = session()->get('userdata');
                                $profilePicture = session()->get('profile_picture');
                                $teacherModel = new \App\Models\TeacherModel();
                                $teacher = $teacherModel->getByEmail($userInfo['email'] ?? '');

                                // Normalize a picture value to an absolute URL
                                $resolvePictureUrl = function($raw, $role) use ($teacher) {
                                    if (!$raw) {
                                        // Fallback to teacher uploaded picture if available
                                        if (isset($teacher['profile_picture']) && !empty($teacher['profile_picture'])) {
                                            $raw = $teacher['profile_picture'];
                                            $role = 'teacher';
                                        } else {
                                            return null;
                                        }
                                    }
                                    // External absolute URL (e.g., Google avatar)
                                    if (preg_match('#^https?://#', $raw)) {
                                        return $raw;
                                    }
                                    // If raw already contains uploads/ path, wrap with base_url
                                    if (strpos($raw, 'uploads/') !== false) {
                                        // Use root-relative path to avoid baseURL port mismatches
                                        return '/' . ltrim($raw, '/');
                                    }
                                    // Otherwise treat as filename and prefix by role
                                    $prefix = ($role === 'teacher') ? 'uploads/teachers/' : 'uploads/profile_pictures/';
                                    return '/' . $prefix . ltrim($raw, '/');
                                };

                                // Priority: session profile > userdata picture > teacher uploaded
                                $rawPicture = $profilePicture ?: ($userInfo['picture'] ?? null);
                                $pictureUrl = $resolvePictureUrl($rawPicture, 'teacher');

                                // Compute display name: prefer teacher first_name, otherwise first token from session name
                                $displayName = 'Teacher';
                                if (!empty($teacher['first_name'])) {
                                    $displayName = $teacher['first_name'];
                                } else {
                                    $rawName = $userInfo['name'] ?? (session()->get('name') ?? null);
                                    if (!empty($rawName)) {
                                        $parts = preg_split('/\s+/', trim($rawName));
                                        if (!empty($parts[0])) { $displayName = $parts[0]; }
                                    }
                                }
                                ?>
                                <?php if ($pictureUrl): ?>
                                    <img src="<?= esc($pictureUrl) ?>" 
                                         alt="Profile Picture" 
                                         class="rounded-circle border border-white shadow-sm" 
                                         style="width: 50px; height: 50px; object-fit: cover; object-position: center;" />
                                <?php else: ?>
                                    <img src="<?= base_url('backend/vendors/images/person.svg') ?>" alt="Default Avatar" 
                                         class="rounded-circle border border-white shadow-sm" 
                                         style="width: 50px; height: 50px; object-fit: cover; object-position: center;" />
                                <?php endif; ?>
                            </span>
                            <span class="user-name"><?= esc($displayName) ?></span>
						</a>
						<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
							<a class="dropdown-item" href="<?= site_url('teacher/profile') ?>">
								<i class="dw dw-user1"></i> Profile
							</a>
							<a class="dropdown-item" href="<?= site_url('teacher/profile/complete') ?>">
								<i class="dw dw-settings2"></i> Settings
							</a>
							<a class="dropdown-item" href="#">
								<i class="dw dw-help"></i> Help
							</a>
							<a class="dropdown-item" href="<?= site_url('logout') ?>" onclick="handleLogout(event)">
								<i class="dw dw-logout"></i> Log Out
							</a>
							<script>
							function handleLogout(event) {
								event.preventDefault();
								if (confirm('Are you sure you want to logout?')) {
									window.location.href = '<?= site_url('logout') ?>';
								}
							}
							</script>
						</div>
					</div>
				</div>
			</div>
		</div>