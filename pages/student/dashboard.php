<?php
require_once(__DIR__ . "/../../database/config.php");
require_once(__DIR__ . "/../../database/functions.php");
include "../../includes/header.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: /pages/login.php");
    exit();
}

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$student_id = $_SESSION['user_id'];
$student = get_user_by_id($student_id, $conn);

if (!$student) {
    die("Error: Student user not found in the database.");
}

$joined_organizations = get_user_organizations($student_id, $conn);
$total_organizations = count($joined_organizations);
$upcoming_events = get_user_events($student_id, $conn);
$total_upcoming_events = count($upcoming_events);
$unread_notifications = count_unread_notifications($student_id, $conn);
$recommended_orgs = get_recommended_organizations($student_id, $conn);
$recent_org_events = get_recent_org_events($student_id, $conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="/assets/css/includes.css">
    <link rel="stylesheet" href="/assets/css/table.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="wrapper">
        <div class="sidebar">
            <?php include "../../includes/sidebar.php"; ?>
        </div>

        <div class="main-content">
            <div class="container">
                <div class="dashboard-stack">
                    <section class="page-hero">
                        <span class="page-eyebrow"><i class="fas fa-user-graduate"></i> Student Space</span>
                        <h1 class="page-hero-title">Student Dashboard</h1>
                        <p class="page-hero-copy">Stay on top of your memberships, discover events faster, and keep your organization activity in one clean place.</p>
                        <div class="page-hero-meta">
                            <span class="page-hero-chip"><i class="fas fa-building"></i> <?= $total_organizations ?> joined organizations</span>
                            <span class="page-hero-chip"><i class="fas fa-calendar-day"></i> <?= $total_upcoming_events ?> upcoming events</span>
                            <span class="page-hero-chip"><i class="fas fa-bell"></i> <?= $unread_notifications ?> unread notifications</span>
                        </div>
                    </section>

                    <div class="metrics-grid">
                        <div class="metric-card card">
                            <div class="card-body">
                                <div class="metric-card-top">
                                    <div>
                                        <p class="metric-label">Organizations</p>
                                        <h3 class="metric-value mb-0"><?= $total_organizations ?></h3>
                                    </div>
                                    <div class="stats-card-icon bg-primary">
                                        <i class="fas fa-building"></i>
                                    </div>
                                </div>
                                <p class="metric-note">Organizations you are currently a member of.</p>
                            </div>
                        </div>

                        <div class="metric-card card">
                            <div class="card-body">
                                <div class="metric-card-top">
                                    <div>
                                        <p class="metric-label">Upcoming Events</p>
                                        <h3 class="metric-value mb-0"><?= $total_upcoming_events ?></h3>
                                    </div>
                                    <div class="stats-card-icon bg-warning">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                </div>
                                <p class="metric-note">Events from organizations you follow.</p>
                            </div>
                        </div>

                        <div class="metric-card card">
                            <div class="card-body">
                                <div class="metric-card-top">
                                    <div>
                                        <p class="metric-label">Unread Notifications</p>
                                        <h3 class="metric-value mb-0"><?= $unread_notifications ?></h3>
                                    </div>
                                    <div class="stats-card-icon bg-info">
                                        <i class="fas fa-bell"></i>
                                    </div>
                                </div>
                                <p class="metric-note">Important updates that still need your attention.</p>
                            </div>
                        </div>
                    </div>

                    <div class="surface-card">
                        <div class="surface-card-header">
                            <div>
                                <h5 class="mb-0">My Organizations</h5>
                                <p class="surface-card-copy mb-0">The communities you are already part of.</p>
                            </div>
                            <a href="/pages/profile/profile.php" class="btn btn-sm btn-outline-primary">Open Profile</a>
                        </div>
                        <div class="surface-card-body">
                            <div class="entity-grid">
                                <?php if (empty($joined_organizations)): ?>
                                    <div class="dashboard-empty">You have not joined any organizations yet.</div>
                                <?php else: ?>
                                    <?php foreach ($joined_organizations as $org): ?>
                                        <div class="entity-card card">
                                            <div class="card-body">
                                                <img
                                                    src="<?= !empty($org['logo']) ? htmlspecialchars($org['logo']) : '/assets/images/orgs/default-org.png'; ?>"
                                                    alt="<?= htmlspecialchars($org['name']); ?>"
                                                    class="entity-cover">
                                                <div class="meta-row">
                                                    <span class="badge bg-primary">Joined</span>
                                                </div>
                                                <h5 class="mb-0"><?= htmlspecialchars($org['name']); ?></h5>
                                                <p class="text-muted mb-0"><?= htmlspecialchars($org['description']); ?></p>
                                                <a href="/pages/profile/profile.php" class="btn btn-outline-primary">View Profile</a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="surface-card">
                        <div class="surface-card-header">
                            <div>
                                <h5 class="mb-0">Recent Events From Your Organizations</h5>
                                <p class="surface-card-copy mb-0">Quick visibility into what is happening next in your circles.</p>
                            </div>
                        </div>
                        <div class="surface-card-body">
                            <div class="entity-grid">
                                <?php if (empty($recent_org_events)): ?>
                                    <div class="dashboard-empty">No recent events found for your current memberships.</div>
                                <?php else: ?>
                                    <?php foreach ($recent_org_events as $event): ?>
                                        <div class="entity-card card">
                                            <div class="card-body">
                                                <div class="meta-row">
                                                    <span class="badge bg-warning"><?= date("M d", strtotime($event['date_time'])); ?></span>
                                                    <span class="badge bg-secondary">Event</span>
                                                </div>
                                                <h5 class="mb-0"><?= htmlspecialchars($event['title']); ?></h5>
                                                <p class="text-muted mb-0"><?= htmlspecialchars($event['description']); ?></p>
                                                <div class="meta-row text-muted small">
                                                    <span><strong>Date:</strong> <?= date("M d, Y", strtotime($event['date_time'])); ?></span>
                                                </div>
                                                <div class="meta-row text-muted small">
                                                    <span><strong>Venue:</strong> <?= htmlspecialchars($event['venue']); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="surface-card">
                        <div class="surface-card-header">
                            <div>
                                <h5 class="mb-0">Suggested Organizations</h5>
                                <p class="surface-card-copy mb-0">A lighter discovery view based on available recommendations.</p>
                            </div>
                        </div>
                        <div class="surface-card-body">
                            <div class="entity-grid">
                                <?php if (empty($recommended_orgs)): ?>
                                    <div class="dashboard-empty">No recommendations available right now.</div>
                                <?php else: ?>
                                    <?php foreach ($recommended_orgs as $org): ?>
                                        <div class="entity-card card">
                                            <div class="card-body">
                                                <div class="meta-row">
                                                    <span class="badge bg-success">Recommended</span>
                                                </div>
                                                <h5 class="mb-0"><?= htmlspecialchars($org['name']); ?></h5>
                                                <p class="text-muted mb-0"><?= htmlspecialchars($org['description']); ?></p>
                                            </div>
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
    <?php include "../../includes/footer.php"; ?>
</body>

</html>
