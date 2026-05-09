<?php $organizationName = $organization['name'] ?? 'No Organization Assigned'; ?>
<div class="table-wrapper">
    <div class="table-title d-flex justify-content-between align-items-center">
        <div>
            <h1><b><?= htmlspecialchars($pageTitle) ?></b></h1>
            <p><?= htmlspecialchars($pageDescription) ?></p>
        </div>
    </div>

    <div class="container-fluid px-0">
        <h3 class="mt-2">Welcome, <?= htmlspecialchars((string)$currentUser['full_name']) ?>!</h3>
        <p class="text-muted">You are managing <strong><?= htmlspecialchars((string)$organizationName) ?></strong>.</p>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Total Members</h6>
                        <h3><?= (int)$totalMembers ?></h3>
                        <p class="small text-muted mb-0">Active members in your organization</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Upcoming Events</h6>
                        <h3><?= (int)$totalEvents ?></h3>
                        <p class="small text-muted mb-0">Events scheduled for your organization</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Recent Organization Events</h5>
                        <a href="<?= asset('pages/leader/create_event.php') ?>" class="btn btn-sm btn-primary">Manage Events</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Date</th>
                                        <th>Venue</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($events)): ?>
                                        <tr><td colspan="4" class="text-center py-4 text-muted">No events found.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($events as $event): ?>
                                            <tr>
                                                <td class="fw-semibold"><?= htmlspecialchars((string)$event['title']) ?></td>
                                                <td><?= htmlspecialchars(format_date((string)$event['date_time'], 'M d, Y h:i A')) ?></td>
                                                <td><?= htmlspecialchars((string)$event['venue']) ?></td>
                                                <td><span class="badge bg-secondary"><?= htmlspecialchars(ucfirst((string)$event['status'])) ?></span></td>
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
                        <h5 class="card-title">Quick Actions</h5>
                    </div>
                    <div class="card-body d-grid gap-3">
                        <a href="<?= asset('pages/leader/manage_members.php') ?>" class="btn btn-primary">Open Member Management</a>
                        <a href="<?= asset('pages/leader/my_organization.php') ?>" class="btn btn-outline-primary">Open Organization Profile</a>
                        <a href="<?= asset('pages/profile/profile.php') ?>" class="btn btn-outline-primary">Open My Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
