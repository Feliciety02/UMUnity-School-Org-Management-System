<div class="table-wrapper">
    <div class="table-title d-flex justify-content-between align-items-center">
        <div>
            <h1><b><?= htmlspecialchars($pageTitle) ?></b></h1>
            <p><?= htmlspecialchars($pageDescription) ?></p>
        </div>
    </div>

    <div class="container-fluid px-0">
        <h3 class="mt-2">Welcome, <?= htmlspecialchars((string)$currentUser['full_name']) ?>!</h3>
        <p class="text-muted">Stay updated with your organizations, events, and recommendations.</p>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Joined Organizations</h6>
                        <h3><?= count($joinedOrganizations) ?></h3>
                        <p class="small text-muted mb-0">Organizations you are a part of</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Upcoming Events</h6>
                        <h3><?= count($upcomingEvents) ?></h3>
                        <p class="small text-muted mb-0">Events from your organizations</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Unread Notifications</h6>
                        <h3><?= (int)$unreadNotifications ?></h3>
                        <p class="small text-muted mb-0">Notifications waiting for you</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">My Organizations</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php if (empty($joinedOrganizations)): ?>
                                <p class="text-muted mb-0">You have not joined any organizations yet.</p>
                            <?php else: ?>
                                <?php foreach ($joinedOrganizations as $organization): ?>
                                    <div class="col-md-6">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h6><?= htmlspecialchars((string)$organization['name']) ?></h6>
                                                <p class="small text-muted mb-0"><?= htmlspecialchars((string)$organization['description']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Upcoming Events</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php if (empty($upcomingEvents)): ?>
                                <p class="text-muted mb-0">No upcoming events available.</p>
                            <?php else: ?>
                                <?php foreach ($upcomingEvents as $event): ?>
                                    <div class="col-12">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h6><?= htmlspecialchars((string)$event['title']) ?></h6>
                                                <p class="small text-muted mb-1"><?= htmlspecialchars((string)$event['description']) ?></p>
                                                <small class="text-muted"><?= htmlspecialchars(format_date((string)$event['date_time'], 'M d, Y h:i A')) ?> at <?= htmlspecialchars((string)$event['venue']) ?></small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">Recommended Organizations</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <?php if (empty($recommendedOrganizations)): ?>
                        <p class="text-muted mb-0">No recommendations available right now.</p>
                    <?php else: ?>
                        <?php foreach ($recommendedOrganizations as $organization): ?>
                            <div class="col-md-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6><?= htmlspecialchars((string)$organization['name']) ?></h6>
                                        <p class="small text-muted mb-0"><?= htmlspecialchars((string)$organization['description']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
