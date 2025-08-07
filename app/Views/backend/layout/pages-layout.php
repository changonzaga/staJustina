
<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title><?= isset($pageTitle) ? $pageTitle : 'New Page Title' ; ?></title>

		<!-- Site favicon -->
		<link
			rel="apple-touch-icon"
			sizes="180x180"
			href="/backend/vendors/images/logo-login-removebg-preview.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="32x32"
			href="/backend/vendors/images/logo-login-removebg-preview.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="16x16"
			href="/backend/vendors/images/logo-login-removebg-preview.png"
		/>
		<link
			rel="shortcut icon"
			type="image/png"
			href="/favicon.png"
		/>

		<!-- Mobile Specific Metas -->
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, maximum-scale=1"
		/>

		<!-- Google Font -->
		<link
			href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
			rel="stylesheet"
		/>
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="/backend/vendors/styles/core.css" />
		<link
			rel="stylesheet"
			type="text/css"
			href="/backend/vendors/styles/icon-font.min.css"
		/>
		<link rel="stylesheet" type="text/css" href="/backend/vendors/styles/style.css" />
		<?= $this->renderSection('stylesheets') ?>
	</head>
	<body>
				

					<!-- <div class="pre-loader">
							<div class="pre-loader-box">
								<div class="loader-logo" style="text-align: center;">
								<img src="https://scontent.fwnp1-1.fna.fbcdn.net/v/t1.15752-9/486190369_1714804709415712_2378757229292270536_n.png?_nc_cat=102&ccb=1-7&_nc_sid=0024fc&_nc_eui2=AeEqrWJFfuB7YB8JxW_hcNk8q2NSk0-s-t2rY1KTT6z63W0U5K45PTewWLZ2ewpgYB8JrlYnmEvkgC3ixYo7tyci&_nc_ohc=6zAZgtJdQkMQ7kNvwGgfAxy&_nc_oc=AdkO9ULrzKriaf70grx1MqhpIGYmXeiFhvGil63jKIvfmAzJA5FhEPPH0KbgDug7ZRs&_nc_ad=z-m&_nc_cid=0&_nc_zt=23&_nc_ht=scontent.fwnp1-1.fna&oh=03_Q7cD2AHaoB5rLJ5WP_L8O3eMWwhvIlvsP4EBLZsH_QkWC3crng&oe=6826D331" 
								alt="Sta Justina National High School Logo" style="width: 100px;" />
								</div>
								<div class="loader-progress" id="progress_div">
								<div class="bar" id="bar1" style="background-color: #800000;"></div>
								</div>
								<div class="percent" id="percent1">0%</div>
								<div class="loading-text">PLEASE WAIT FOR THE SYSTEM TO LOAD...</div>
							</div>
							</div> -->


		<?php include('inc/header.php'); ?>
<?php include('inc/right-sidebar.php'); ?>
<?php include('inc/left-sidebar.php'); ?>


		<div class="mobile-menu-overlay"></div>

		<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div>
						<?= $this->renderSection('content') ?>
					</div>
				</div>
				<?php include('inc/footer.php'); ?>
			</div>
		</div>
	
		<!-- js -->
		<script src="/backend/vendors/scripts/core.js"></script>
		<script src="/backend/vendors/scripts/script.min.js"></script>
		<script src="/backend/vendors/scripts/process.js"></script>
		<script src="/backend/vendors/scripts/layout-settings.js"></script>
		<?= $this->renderSection('scripts') ?>
	</body>
</html>
