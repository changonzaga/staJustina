
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
			href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Roboto:wght@300;400;500;700;900&display=swap"
			rel="stylesheet"
		/>
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="/backend/vendors/styles/core.css" />
		<link
			rel="stylesheet"
			type="text/css"
			href="/backend/vendors/styles/icon-font.min.css"
		/>
		<!-- FontAwesome 6 for icons -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link rel="stylesheet" type="text/css" href="/backend/vendors/styles/style.css" />
		<link rel="stylesheet" type="text/css" href="/backend/vendors/fonts/dropways.css" />
		<link rel="stylesheet" type="text/css" href="/backend/vendors/styles/back-to-top.css" />
		<?= $this->renderSection('stylesheets') ?>
	</head>
	<body>
				

					<div class="pre-loader">
						<div class="pre-loader-box">
							<div class="loader-logo" style="text-align: center;">
							<img src="/backend/vendors/images/logo-login-removebg-preview.png" 
							alt="Sta Justina National High School Logo" style="width: 100px;" />
							</div>
							<div class="loader-progress" id="progress_div">
							<div class="bar" id="bar1" style="background-color: #800000;"></div>
							</div>
							<div class="percent" id="percent1">0%</div>
							<div class="loading-text">PLEASE WAIT FOR THE SYSTEM TO LOAD...</div>
						</div>
					</div>


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

		<button id="backToTop" class="go-to-top" aria-label="Go to top of page" style="display: none;">
			<i class="fas fa-arrow-up"></i>
		</button>
	
		<!-- js -->
		<script src="/backend/vendors/scripts/core.js"></script>
		<script src="/backend/vendors/scripts/script.min.js"></script>
		<script src="/backend/vendors/scripts/process.js"></script>
		<script src="/backend/vendors/scripts/layout-settings.js"></script>
		<script src="/backend/vendors/scripts/back-to-top.js"></script>
		<?= $this->renderSection('scripts') ?>
	</body>
</html>
