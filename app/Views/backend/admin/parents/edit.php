
<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Edit Parent</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= site_url('admin/parent') ?>">Parents</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Edit Parent
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="pd-20 card-box mb-30">
    <div class="clearfix mb-20">
        <div class="pull-left">
            <h4 class="text-blue h4">Edit Parent Information</h4>
            <p class="mb-30">Update parent details below</p>
        </div>
    </div>
    <div class="wizard-content">
        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger">
                <?= session('error') ?>
            </div>
        <?php endif; ?>
        
        <?php if (session()->has('success')): ?>
            <div class="alert alert-success">
                <?= session('success') ?>
            </div>
        <?php endif; ?>
        
        <form action="<?= route_to('admin.parent.update', $parent['id']) ?>" method="post" id="updateParentForm">
            <?= csrf_field() ?>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="first_name" value="<?= old('first_name', $parent['first_name']) ?>" required>
                        <?php if(isset($errors['first_name'])): ?>
                            <div class="text-danger"><?= $errors['first_name'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Middle Name</label>
                        <input type="text" class="form-control" name="middle_name" value="<?= old('middle_name', $parent['middle_name'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="last_name" value="<?= old('last_name', $parent['last_name']) ?>" required>
                        <?php if(isset($errors['last_name'])): ?>
                            <div class="text-danger"><?= $errors['last_name'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Relationship to Student <span class="text-danger">*</span></label>
                        <select class="form-control" name="relationship_type" required>
                            <option value="">Select Relationship</option>
                            <option value="father" <?= old('relationship_type', $parent['relationship_type']) == 'father' ? 'selected' : '' ?>>Father</option>
                            <option value="mother" <?= old('relationship_type', $parent['relationship_type']) == 'mother' ? 'selected' : '' ?>>Mother</option>
                            <option value="guardian" <?= old('relationship_type', $parent['relationship_type']) == 'guardian' ? 'selected' : '' ?>>Guardian</option>
                        </select>
                        <?php if(isset($errors['relationship_type'])): ?>
                            <div class="text-danger"><?= $errors['relationship_type'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Contact Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="contact_number" value="<?= old('contact_number', $parent['contact_number']) ?>" required>
                        <?php if(isset($errors['contact_number'])): ?>
                            <div class="text-danger"><?= $errors['contact_number'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Address Information Section -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <h5 class="text-blue mb-3">Address Information</h5>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_same_as_student" id="is_same_as_student" value="1" <?= old('is_same_as_student', $parent['is_same_as_student'] ?? 0) == 1 ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_same_as_student">
                                Same address as student
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>House Number</label>
                        <input type="text" class="form-control" name="house_number" value="<?= old('house_number', $parent['house_number'] ?? '') ?>" placeholder="e.g., 123, Blk 5 Lot 10">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Street</label>
                        <input type="text" class="form-control" name="street" value="<?= old('street', $parent['street'] ?? '') ?>" placeholder="e.g., Main Street, Rizal Avenue">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Barangay</label>
                        <input type="text" class="form-control" name="barangay" value="<?= old('barangay', $parent['barangay'] ?? '') ?>" placeholder="e.g., Poblacion, San Antonio">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Municipality/City</label>
                        <input type="text" class="form-control" name="municipality" value="<?= old('municipality', $parent['municipality'] ?? '') ?>" placeholder="e.g., Quezon City, Manila">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Province</label>
                        <input type="text" class="form-control" name="province" value="<?= old('province', $parent['province'] ?? '') ?>" placeholder="e.g., Metro Manila, Laguna">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>ZIP Code</label>
                        <input type="text" class="form-control" name="zip_code" value="<?= old('zip_code', $parent['zip_code'] ?? '') ?>" placeholder="e.g., 1100, 4000" maxlength="10">
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="icon-copy bi bi-check-circle"></i> Update Parent
                        </button>
                        <a href="<?= site_url('admin/parent') ?>" class="btn btn-secondary ml-2">
                            <i class="icon-copy bi bi-arrow-left"></i> Back to Parents
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Form validation
document.getElementById('updateParentForm').addEventListener('submit', function(e) {
    const firstName = document.querySelector('input[name="first_name"]').value.trim();
    const lastName = document.querySelector('input[name="last_name"]').value.trim();
    const contactNumber = document.querySelector('input[name="contact_number"]').value.trim();
    const relationshipType = document.querySelector('select[name="relationship_type"]').value;
    
    if (!firstName || !lastName || !contactNumber || !relationshipType) {
        e.preventDefault();
        alert('Please fill in all required fields (First Name, Last Name, Contact Number, and Relationship Type).');
        return false;
    }
    
    return true;
});
</script>

<?= $this->endSection() ?>
