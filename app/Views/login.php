<?= $this->extend('backend/layout/auth-layout') ?>
<?= $this->section('content') ?>

<div class="login-box bg-white box-shadow border-radius-10">
	<div class="login-title">
		<h2 class="text-center text-primary">Login</h2>
	</div>
	
	<!-- Custom Tab Navigation -->
	<div class="customtab mb-20">
		<ul class="nav nav-tabs customtab-nav" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="tab" href="#student-tab" role="tab" aria-selected="true" onclick="setLoginTarget('student')">
					<i class="icon-copy dw dw-user1"></i> Student
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#teacher-tab" role="tab" aria-selected="false" onclick="setLoginTarget('teacher')">
					<i class="icon-copy dw dw-graduation"></i> Teacher
				</a>
			</li>
		</ul>
	</div>
	
	<!-- Demo Navigation Interface - No Authentication Required -->
	<div class="demo-notice mb-3">
		<div class="alert alert-info text-center">
			<i class="icon-copy dw dw-info"></i>
			<strong>Demo Mode:</strong> Select your role below to access the dashboard directly
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-6 mb-3">
			<button type="button" class="btn btn-primary btn-lg btn-block demo-login-btn" onclick="navigateToDashboard('student')">
				<i class="icon-copy dw dw-user1 mr-2"></i>
				<span>Enter as Student</span>
			</button>
		</div>
		<div class="col-sm-6 mb-3">
			<button type="button" class="btn btn-success btn-lg btn-block demo-login-btn" onclick="navigateToDashboard('teacher')">
				<i class="icon-copy dw dw-graduation mr-2"></i>
				<span>Enter as Teacher</span>
			</button>
		</div>
	</div>
	
	<div class="text-center mt-3">
		<small class="text-muted">
			<i class="icon-copy dw dw-settings1"></i>
			Quick access for testing and demonstration purposes
		</small>
	</div>
</div>

<script>
function setLoginTarget(userType) {
    // Update tab active states
    const tabs = document.querySelectorAll('.customtab-nav .nav-link');
    tabs.forEach(tab => {
        tab.classList.remove('active');
        tab.setAttribute('aria-selected', 'false');
    });
    
    // Set active tab
    const activeTab = document.querySelector(`[onclick="setLoginTarget('${userType}')"]`);
    if (activeTab) {
        activeTab.classList.add('active');
        activeTab.setAttribute('aria-selected', 'true');
    }
}

function navigateToDashboard(userType) {
    // Add loading effect
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="icon-copy dw dw-loading1 fa-spin mr-2"></i>Loading...';
    button.disabled = true;
    
    // Simulate brief loading then redirect
    setTimeout(() => {
        if (userType === 'student') {
            window.location.href = '/student/dashboard';
        } else if (userType === 'teacher') {
            window.location.href = '/teacher/dashboard';
        }
    }, 800);
}

// Initialize default state on page load
document.addEventListener('DOMContentLoaded', function() {
    setLoginTarget('student');
    
    // Add hover effects to demo buttons
    const demoButtons = document.querySelectorAll('.demo-login-btn');
    demoButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.2)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });
});
</script>

<?= $this->endSection() ?>