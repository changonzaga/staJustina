<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= route_to('admin.home') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">OAuth Logs</li>
                    </ol>
                </div>
                <h4 class="page-title">OAuth Authentication Logs</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <a href="javascript:void(0);" class="btn btn-danger mb-2" onclick="clearLogs()"><i class="mdi mdi-delete-sweep"></i> Clear Logs</a>
                        </div>
                        <div class="col-sm-8">
                            <div class="text-sm-end">
                                <button type="button" class="btn btn-success mb-2 me-1" onclick="refreshTable()"><i class="mdi mdi-refresh"></i> Refresh</button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered w-100 dt-responsive nowrap" id="oauth-logs-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>Date/Time</th>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>IP Address</th>
                                    <th>User Agent</th>
                                    <th>Error Message</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td><?= $log['created_at'] ?></td>
                                    <td>
                                        <?php if ($log['picture']): ?>
                                            <img src="<?= $log['picture'] ?>" alt="user-img" class="rounded-circle me-2" height="32">
                                        <?php endif; ?>
                                        <?= $log['name'] ?>
                                    </td>
                                    <td><?= $log['email'] ?></td>
                                    <td>
                                        <span class="badge bg-<?= $log['status'] === 'success' ? 'success' : 'danger' ?>">
                                            <?= ucfirst($log['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= $log['ip_address'] ?></td>
                                    <td>
                                        <span class="text-wrap"><?= $log['user_agent'] ?></span>
                                    </td>
                                    <td>
                                        <?php if ($log['error_message']): ?>
                                            <span class="text-danger text-wrap"><?= $log['error_message'] ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#oauth-logs-datatable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            pageLength: 10,
            order: [[0, 'desc']],
            responsive: true
        });
    });

    function refreshTable() {
        location.reload();
    }

    function clearLogs() {
        if (confirm('Are you sure you want to clear all OAuth logs? This action cannot be undone.')) {
            $.ajax({
                url: '<?= route_to("admin.oauth.logs.clear") ?>',
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Failed to clear logs. Please try again.');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        }
    }
</script>
<?= $this->endSection() ?>