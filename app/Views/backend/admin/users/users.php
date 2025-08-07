<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>
<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>All users</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home')?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Users
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="card-box mb-30">
    <div class="pd-20 d-flex justify-content-between align-items-center flex-wrap">
        <h4 class="text-blue h4">User List</h4>
        <a href="#" class="btn btn-success btn-sm">
            <i class="icon-copy bi bi-plus-lg"></i> Add User
        </a>
    </div>
    <div class="pb-20">
        <table class="data-table table stripe hover nowrap" id="usersTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example users -->
                <tr>
                    <td>1</td>
                    <td><strong>John Doe</strong></td>
                    <td>john.doe@example.com</td>
                    <td><span class="badge badge-primary">Admin</span></td>
                    <td><span class="badge badge-success">Active</span></td>
                    <td>
                        <div class="dropdown">
                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                               href="#" data-toggle="dropdown">
                                <i class="dw dw-more"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                <a class="dropdown-item" href="#"><i class="dw dw-user1"></i> View</a>
                                <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
                                <a class="dropdown-item text-danger" href="#" onclick="return confirm('Are you sure?')"><i class="dw dw-delete-3"></i> Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td><strong>Jane Smith</strong></td>
                    <td>jane.smith@example.com</td>
                    <td><span class="badge badge-info">Teacher</span></td>
                    <td><span class="badge badge-success">Active</span></td>
                    <td>
                        <div class="dropdown">
                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                               href="#" data-toggle="dropdown">
                                <i class="dw dw-more"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                <a class="dropdown-item" href="#"><i class="dw dw-user1"></i> View</a>
                                <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
                                <a class="dropdown-item text-danger" href="#" onclick="return confirm('Are you sure?')"><i class="dw dw-delete-3"></i> Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td><strong>Mark Lee</strong></td>
                    <td>mark.lee@example.com</td>
                    <td><span class="badge badge-secondary">Staff</span></td>
                    <td><span class="badge badge-danger">Inactive</span></td>
                    <td>
                        <div class="dropdown">
                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                               href="#" data-toggle="dropdown">
                                <i class="dw dw-more"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                <a class="dropdown-item" href="#"><i class="dw dw-user1"></i> View</a>
                                <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
                                <a class="dropdown-item text-danger" href="#" onclick="return confirm('Are you sure?')"><i class="dw dw-delete-3"></i> Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <!-- Add more users as needed -->
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>