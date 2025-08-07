<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4><i class="bi bi-gear"></i> General Settings</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb bg-light px-3 py-2 rounded">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home')?>"><i class="dw dw-house-1"></i> Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        General Settings
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4"><i class="bi bi-gear"></i> System Information</h4>
        <form action="#" method="post">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="schoolName" class="form-label">School Name</label>
                    <input type="text" class="form-control" id="schoolName" name="school_name" value="Sta Justina National High School" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="schoolYear" class="form-label">School Year</label>
                    <input type="text" class="form-control" id="schoolYear" name="school_year" value="2024-2025" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="adminEmail" class="form-label">Admin Email</label>
                    <input type="email" class="form-control" id="adminEmail" name="admin_email" value="admin@email.com" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="contactNumber" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" id="contactNumber" name="contact_number" value="09123456789">
                </div>
            </div>
            <div class="mb-3">
                <label for="schoolAddress" class="form-label">School Address</label>
                <textarea class="form-control" id="schoolAddress" name="school_address" rows="2">Sta Justina, Camarines Sur, Philippines</textarea>
            </div>
            <div class="mb-3">
                <label for="logoUpload" class="form-label">School Logo</label>
                <input type="file" class="form-control-file" id="logoUpload" name="school_logo" accept="image/*">
                <div class="mt-2">
                    <img src="https://scontent.fmnl13-3.fna.fbcdn.net/v/t1.15752-9/486190369_1714804709415712_2378757229292270536_n.png?_nc_cat=102&ccb=1-7&_nc_sid=0024fc&_nc_eui2=AeEqrWJFfuB7YB8JxW_hcNk8q2NSk0-s-t2rY1KTT6z63W0U5K45PTewWLZ2ewpgYB8JrlYnmEvkgC3ixYo7tyci&_nc_ohc=7Ep537vZeNwQ7kNvwFw_tYE&_nc_oc=Adl4e345h5014RDN5sGoX3pShnmDSp44s4DB936Dinb0WA3OHgXDPJSxknP5BHc-Oaw&_nc_ad=z-m&_nc_cid=0&_nc_zt=23&_nc_ht=scontent.fmnl13-3.fna&oh=03_Q7cD2QHMqHk1lpaK_xcKcY_B9FAVYjYWixE2FCtEXacB1acYZA&oe=6856B9B1" alt="School Logo" style="width: 80px; height: 80px; border-radius: 8px;">
                </div>
            </div>
            <div class="mb-3">
                <label for="systemTheme" class="form-label">System Theme</label>
                <select class="form-control" id="systemTheme" name="system_theme">
                    <option value="default" selected>Default</option>
                    <option value="dark">Dark</option>
                    <option value="light">Light</option>
                </select>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary"><i class="dw dw-save"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
