<div class="table-wrapper">
    <div class="table-title d-flex justify-content-between align-items-center">
        <div>
            <h1><b><?= htmlspecialchars($pageTitle) ?></b></h1>
            <p><?= htmlspecialchars($pageDescription) ?></p>
        </div>
    </div>

    <div class="container-fluid px-0">
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-1">Total Organizations</h6>
                                <h3 class="mb-0"><?= (int)$stats['total_organizations'] ?></h3>
                            </div>
                            <div class="stats-card-icon bg-primary">
                                <i class="fas fa-sitemap"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-1">Pending Approvals</h6>
                                <h3 class="mb-0"><?= (int)$stats['pending_organizations'] ?></h3>
                            </div>
                            <div class="stats-card-icon bg-warning">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-1">Total Users</h6>
                                <h3 class="mb-0"><?= (int)$stats['total_users'] ?></h3>
                            </div>
                            <div class="stats-card-icon bg-success">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-1">Active Users</h6>
                                <h3 class="mb-0"><?= (int)$stats['active_users'] ?></h3>
                            </div>
                            <div class="stats-card-icon bg-info">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Pending Organizations</h5>
                        <a href="<?= asset('pages/admin/manage_organizations.php') ?>" class="btn btn-sm btn-primary">Open Legacy Manager</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Leader</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($pendingOrganizations)): ?>
                                        <tr><td colspan="4" class="text-center py-4 text-muted">No pending organizations.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($pendingOrganizations as $organization): ?>
                                            <tr>
                                                <td class="fw-semibold"><?= htmlspecialchars((string)$organization['name']) ?></td>
                                                <td><span class="badge bg-secondary"><?= htmlspecialchars((string)($organization['category_name'] ?? 'Uncategorized')) ?></span></td>
                                                <td><?= htmlspecialchars((string)($organization['leader_name'] ?? 'Unassigned')) ?></td>
                                                <td><?= htmlspecialchars(format_date((string)$organization['created_at'], 'M d, Y')) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Recent Activity</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php if (empty($recentActivities)): ?>
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-history fa-2x mb-3"></i>
                                    <p class="mb-0">No recent activities.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($recentActivities as $activity): ?>
                                    <div class="list-group-item py-3">
                                        <h6 class="mb-1"><?= htmlspecialchars((string)$activity['action']) ?></h6>
                                        <p class="small mb-1 text-muted"><?= htmlspecialchars((string)$activity['details']) ?></p>
                                        <small class="text-muted"><?= htmlspecialchars(format_date((string)$activity['created_at'], 'M d, h:i A')) ?></small>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
