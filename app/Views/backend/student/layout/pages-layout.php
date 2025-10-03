<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title><?= isset($pageTitle) ? $pageTitle : 'Student Dashboard' ; ?></title>

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
		<!-- Font Awesome for icons -->
		<link
			rel="stylesheet"
			href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
		/>
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
	<!-- ───────── GO TO TOP BUTTON ───────── -->
    <button id="backToTop" class="go-to-top" aria-label="Go to top of page">
        <i class="fas fa-arrow-up"></i>
    </button>

    <style>
    /* ───────── GO TO TOP BUTTON ───────── */
    .go-to-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background: #550000;
        color: #ffffff;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        box-shadow: 0 4px 15px rgba(85, 0, 0, 0.3);
        transition: all 0.3s ease;
        z-index: 1000;
    }
    
    .go-to-top:hover {
        background: #770000;
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(85, 0, 0, 0.4);
    }
    
    @media (max-width: 768px) {
        .go-to-top {
            width: 45px;
            height: 45px;
            bottom: 20px;
            right: 20px;
            font-size: 1rem;
        }
    }
    </style>

    <script>
    /* Go to Top Button Functionality */
    document.addEventListener('DOMContentLoaded', function() {
        const backToTopBtn = document.getElementById('backToTop');
        
        // Debug: Check if button exists in DOM
        console.log('Back to Top button:', backToTopBtn ? 'Found' : 'Not found');
        if (backToTopBtn) {
            console.log('Button properties:', {
                id: backToTopBtn.id,
                tagName: backToTopBtn.tagName,
                display: window.getComputedStyle(backToTopBtn).display,
                zIndex: window.getComputedStyle(backToTopBtn).zIndex,
                position: window.getComputedStyle(backToTopBtn).position
            });
        } else {
            console.warn('Back to top button not found in the DOM');
            return;
        }
        
        // Check initial scroll position
        checkScrollPosition();
        
        // Add scroll event listener
        window.addEventListener('scroll', checkScrollPosition, { passive: true });
        
        // Add click event listener for smooth scrolling
        backToTopBtn.addEventListener('click', function() {
            console.log('Back to Top button clicked');
            // For modern browsers
            if ('scrollBehavior' in document.documentElement.style) {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            } else {
                // Fallback for older browsers
                window.scrollTo(0, 0);
            }
        });
        
        // Function to check scroll position and toggle button visibility
        function checkScrollPosition() {
            const scrollY = window.scrollY || window.pageYOffset;
            
            if (scrollY > 100) {
                backToTopBtn.style.display = 'flex';
                console.log('Scroll position:', scrollY, '- Button should be visible');
            } else {
                backToTopBtn.style.display = 'none';
                console.log('Scroll position:', scrollY, '- Button should be hidden');
            }
        }
    });
    </script>
	</body>
</html>