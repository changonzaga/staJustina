
<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title><?= isset($pageTitle) ? $pageTitle : 'New Page Title'; ?></title>

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
		<link rel="stylesheet" type="text/css" href="/backend/vendors/fonts/dropways.css" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

	<?= $this->renderSection('stylesheets') ?>
	
	<style>
		body.login-page {
			background-image: url('/backend/src/images/landing-bg.png');
			background-size: cover;
			background-position: center;
			background-repeat: no-repeat;
			background-attachment: fixed;
			min-height: 100vh;
		}
		
		.login-wrap {
			background: rgba(255, 255, 255, 0.1);
			backdrop-filter: blur(3px);
			border-radius: 15px;
		}
		
		.login-box {
			background: rgba(255, 255, 255, 0.95);
			backdrop-filter: blur(5px);
			border: 1px solid rgba(255, 255, 255, 0.2);
		}
	</style>
		
	</head>
	<body class="login-page">
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
		<div class="login-header box-shadow">
			<div
				class="container-fluid d-flex justify-content-between align-items-center"
			>
				<div class="brand-logo d-flex align-items-center">
					<a href="login.html" class="mr-3">
						<img src="/backend/vendors/images/logo-login.png" alt="" style="width: 60px;" />
					</a>
					<div class="school-info">
						<h4 class="mb-0 text-black font-weight-bold">STA. JUSTINA NATIONAL HIGH SCHOOL</h4>
						<p class="mb-0 text-muted small">Nurturing Excellence</p>
					</div>
				</div>
				<div class="login-menu">
					<button type="button" class="btn btn-outline-secondary" onclick="window.location.href='<?= base_url('') ?>'">
						<i class="fas fa-arrow-left me-2"></i>
					</button>
				</div>
			</div>
		</div>
		<div
			class="login-wrap d-flex align-items-center flex-wrap justify-content-center"
		>
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-md-8 col-lg-6">
                        <?= $this->renderSection('content')?>
					</div>
				</div>
			</div>
		</div>

		<!-- js -->
		<script src="/backend/vendors/scripts/core.js"></script>
		<script src="/backend/vendors/scripts/script.min.js"></script>
		<script src="/backend/vendors/scripts/process.js"></script>
		<script src="/backend/vendors/scripts/layout-settings.js"></script>
		<?= $this->renderSection('scripts'); ?>
	</body>
</html>
