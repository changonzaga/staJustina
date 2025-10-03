<?php helper(['form', 'url']); ?>
<?= $this->extend('backend/layout/auth-layout') ?>
<?= $this->section('content') ?>

<div class="login-box bg-white box-shadow border-radius-10">
	<div class="text-center mb-4">
		<img src="/backend/vendors/images/logo-login.png" alt="" style="width: 60px;" class="mb-3" />
		<h2 class="text-primary font-weight-bold mb-0">Login</h2>
		<p class="text-muted small mb-0">Access your account - Admin, Teacher, Student Portal</p>
	</div>
    <?php $validation = \Config\Services::validation(); ?>
	<form action="<?= route_to('central.login.handler') ?>" method="POST">
        <?= csrf_field() ?>
        <?php if(!empty(session()->getFlashdata('success'))): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <?php if(!empty(session()->getFlashdata('fail'))): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('fail') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <div class="input-group custom">
			<input type="text" class="form-control form-control-lg" placeholder="Account Number or Email" name="login_id"
			value="<?= set_value('login_id') ?>" >
			<div class="input-group-append custom">
				<span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
			</div>
		</div>
		<small class="form-text text-muted mb-3">Enter your account number or email address</small>
        <?php if($validation->getError('login_id')):?>
         <div class="d-block test-danger" style="margin-top:25px; margin-bottom:15px;">
             <?= $validation->getError('login_id') ?>
         </div>
        <?php endif; ?>
		<div class="input-group custom">
			<input type="password" class="form-control form-control-lg" placeholder="Password" name="password" id="password"
			value="<?= set_value('password')?>">
			<div class="input-group-append custom">
				<span class="input-group-text" style="cursor: pointer;" onclick="togglePassword()">
					<i class="bi bi-eye" id="togglePasswordIcon"></i>
				</span>
			</div>
		</div>
        <?php if($validation->getError('password')):?>
            <div class="d-block test-danger" style="margin-top:25px; margin-bottom:15px;">
                <?= $validation->getError('password') ?>
            </div>
        <?php endif; ?>
		<div class="row pb-30">
			<div class="col-6">
				<div class="custom-control custom-checkbox">
					<input type="checkbox" class="custom-control-input" id="customCheck1">
					<label class="custom-control-label" for="customCheck1">Remember</label>
				</div>
			</div>
			<div class="col-6">
				<div class="forgot-password">
					<a href="<?= route_to('central.forgot.form') ?>">Forgot Password</a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="input-group mb-3">
					<input class="btn btn-primary btn-lg btn-block" type="submit" value="Sign In">
				</div>
				<div class="text-center mb-3">
					<span class="text-muted">OR</span>
				</div>
				<a href="<?= route_to('central.google.login') ?>" class="btn btn-light btn-lg btn-block d-flex align-items-center justify-content-center" style="border: 1px solid #ddd;">
					<img src="/backend/vendors/images/google-g-logo.svg" alt="Google" style="height: 24px; margin-right: 10px;">
					Sign in with Google
				</a>
				<small class="form-text text-muted text-center mt-2">Available for both Admin and Teacher accounts</small>
			</div>
		</div>
	</form>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('togglePasswordIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('bi-eye');
        toggleIcon.classList.add('bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('bi-eye-slash');
        toggleIcon.classList.add('bi-eye');
    }
}
</script>

<?= $this->endSection() ?>