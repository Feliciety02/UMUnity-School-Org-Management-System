<?php
require_once(__DIR__ . "/../../database/config.php");
require_once(__DIR__ . "/../../database/functions.php");
include "../../includes/header.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /pages/login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];
$admin = get_user_by_id($admin_id);

if (!$admin) {
    die("Error: User not found in database.");
}

$pending_organizations = 0;
$total_organizations = 0;
$total_users = 0;
$active_users = 0;

$stmt = $conn->prepare("SELECT COUNT(*) as count FROM organizations WHERE status = 'pending'");
if ($stmt->execute()) {
    $pending_organizations = (int)($stmt->get_result()->fetch_assoc()['count'] ?? 0);
}
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) as count FROM organizations WHERE status = 'active'");
if ($stmt->execute()) {
    $total_organizations = (int)($stmt->get_result()->fetch_assoc()['count'] ?? 0);
}
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) as count FROM users");
if ($stmt->execute()) {
    $total_users = (int)($stmt->get_result()->fetch_assoc()['count'] ?? 0);
}
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE status = 'active'");
if ($stmt->execute()) {
    $active_users = (int)($stmt->get_result()->fetch_assoc()['count'] ?? 0);
}
$stmt->close();

$pending_orgs = get_organizations_by_status('pending');

$recent_activities = [];
$activity_stmt = $conn->prepare("
    SELECT al.action, al.details, al.created_at, u.full_name
    FROM activity_logs al
    LEFT JOIN users u ON al.user_id = u.user_id
    ORDER BY al.created_at DESC
    LIMIT 5
");
if ($activity_stmt->execute()) {
    $recent_activities = $activity_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
$activity_stmt->close();

$category_result = $conn->query("
    SELECT COALESCE(c.category_name, 'Uncategorized') AS category_name, COUNT(*) AS count
    FROM organizations o
    LEFT JOIN org_categories c ON o.category_id = c.category_id
    GROUP BY c.category_id, c.category_name
    ORDER BY count DESC
");

$categories = [];
$category_counts = [];
while ($row = $category_result->fetch_assoc()) {
    $categories[] = $row['category_name'];
    $category_counts[] = (int)$row['count'];
}

$user_activity_result = $conn->query("
    SELECT DATE_FORMAT(created_at, '%Y-%m') AS month_key, DATE_FORMAT(created_at, '%b %Y') AS month_label, COUNT(*) AS count
    FROM users
    GROUP BY month_key, month_label
    ORDER BY month_key ASC
");

$months = [];
$user_counts = [];
while ($row = $user_activity_result->fetch_assoc()) {
    $months[] = $row['month_label'];
    $user_counts[] = (int)$row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
                <div class="table-responsive">
                    <div class="dashboard-stack">
                        <section class="page-hero">
                            <span class="page-eyebrow"><i class="fas fa-shield-halved"></i> Administration</span>
                            <h1 class="page-hero-title">Admin Dashboard</h1>
                            <p class="page-hero-copy">Monitor platform growth, unblock organization approvals, and keep member operations clean from a single control center.</p>
                            <div class="page-hero-meta">
                                <span class="page-hero-chip"><i class="fas fa-building"></i> <?= $total_organizations ?> active organizations</span>
                                <span class="page-hero-chip"><i class="fas fa-user-check"></i> <?= $active_users ?> active users</span>
                                <span class="page-hero-chip"><i class="fas fa-clock"></i> <?= $pending_organizations ?> approvals waiting</span>
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
                                            <i class="fas fa-sitemap"></i>
                                        </div>
                                    </div>
                                    <p class="metric-note">Currently active school organizations.</p>
                                </div>
                            </div>

                            <div class="metric-card card">
                                <div class="card-body">
                                    <div class="metric-card-top">
                                        <div>
                                            <p class="metric-label">Pending</p>
                                            <h3 class="metric-value mb-0"><?= $pending_organizations ?></h3>
                                        </div>
                                        <div class="stats-card-icon bg-warning">
                                            <i class="fas fa-hourglass-half"></i>
                                        </div>
                                    </div>
                                    <p class="metric-note">Organizations waiting for review and activation.</p>
                                </div>
                            </div>

                            <div class="metric-card card">
                                <div class="card-body">
                                    <div class="metric-card-top">
                                        <div>
                                            <p class="metric-label">Users</p>
                                            <h3 class="metric-value mb-0"><?= $total_users ?></h3>
                                        </div>
                                        <div class="stats-card-icon bg-success">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                    <p class="metric-note">All registered accounts across the system.</p>
                                </div>
                            </div>

                            <div class="metric-card card">
                                <div class="card-body">
                                    <div class="metric-card-top">
                                        <div>
                                            <p class="metric-label">Active Accounts</p>
                                            <h3 class="metric-value mb-0"><?= $active_users ?></h3>
                                        </div>
                                        <div class="stats-card-icon bg-info">
                                            <i class="fas fa-user-check"></i>
                                        </div>
                                    </div>
                                    <p class="metric-note">Accounts currently allowed to use the platform.</p>
                                </div>
                            </div>
                        </div>

                        <div class="container-fluid px-0">
                            <div class="row g-4 mb-4">
                                <div class="col-md-6 col-lg-3">
                                    <div class="surface-card h-100">
                                        <div class="surface-card-header">
                                            <div>
                                                <h5 class="mb-0">Quick Access</h5>
                                                <p class="surface-card-copy mb-0">Open user administration.</p>
                                            </div>
                                        </div>
                                        <div class="surface-card-body">
                                            <a href="/pages/admin/manage_users.php" class="btn btn-primary w-100">Manage Users</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <div class="surface-card h-100">
                                        <div class="surface-card-header">
                                            <div>
                                                <h5 class="mb-0">Organizations</h5>
                                                <p class="surface-card-copy mb-0">Review organization records.</p>
                                            </div>
                                        </div>
                                        <div class="surface-card-body">
                                            <a href="/pages/admin/manage_organizations.php" class="btn btn-outline-primary w-100">Open Organizations</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <div class="surface-card h-100">
                                        <div class="surface-card-header">
                                            <div>
                                                <h5 class="mb-0">Events</h5>
                                                <p class="surface-card-copy mb-0">Track event status and approvals.</p>
                                            </div>
                                        </div>
                                        <div class="surface-card-body">
                                            <a href="/pages/admin/manage_events.php" class="btn btn-outline-primary w-100">Open Events</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <div class="surface-card h-100">
                                        <div class="surface-card-header">
                                            <div>
                                                <h5 class="mb-0">Reports</h5>
                                                <p class="surface-card-copy mb-0">Inspect logs and summaries.</p>
                                            </div>
                                        </div>
                                        <div class="surface-card-body">
                                            <a href="/pages/admin/manage_reports.php" class="btn btn-outline-primary w-100">Open Reports</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-lg-6">
                                    <div class="surface-card chart-card h-100">
                                        <div class="surface-card-header">
                                            <div>
                                                <h5 class="mb-0">Organization Categories</h5>
                                                <p class="surface-card-copy mb-0">See how clubs are distributed across categories.</p>
                                            </div>
                                        </div>
                                        <div class="surface-card-body">
                                            <div class="chart-frame">
                                                <canvas id="org-categories-chart" height="100"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="surface-card chart-card h-100">
                                        <div class="surface-card-header">
                                            <div>
                                                <h5 class="mb-0">User Activity</h5>
                                                <p class="surface-card-copy mb-0">Monthly growth trend for user registrations.</p>
                                            </div>
                                        </div>
                                        <div class="surface-card-body">
                                            <div class="chart-frame">
                                                <canvas id="user-activity-chart" height="300"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-lg-8">
                                    <div class="surface-card">
                                        <div class="surface-card-header">
                                            <div>
                                                <h5 class="mb-0">Pending Organizations</h5>
                                                <p class="surface-card-copy mb-0">The next organizations waiting for admin approval.</p>
                                            </div>
                                            <a href="/pages/admin/manage_organizations.php" class="btn btn-sm btn-primary">View All</a>
                                        </div>
                                        <div class="surface-card-body p-0">
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
                                                        <?php if (empty($pending_orgs)): ?>
                                                            <tr>
                                                                <td colspan="4" class="text-center py-4 text-muted">No pending organizations</td>
                                                            </tr>
                                                        <?php else: ?>
                                                            <?php foreach (array_slice($pending_orgs, 0, 5) as $org): ?>
                                                                <tr>
                                                                    <td><div class="fw-semibold"><?= htmlspecialchars($org['name']) ?></div></td>
                                                                    <td><span class="badge bg-secondary"><?= htmlspecialchars($org['category_name'] ?? 'Uncategorized') ?></span></td>
                                                                    <td><?= htmlspecialchars($org['leader_name'] ?? 'Unassigned') ?></td>
                                                                    <td><?= htmlspecialchars(format_date($org['created_at'], 'M d, Y')) ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="surface-card">
                                        <div class="surface-card-header">
                                            <div>
                                                <h5 class="mb-0">Recent Activity</h5>
                                                <p class="surface-card-copy mb-0">Latest admin-facing actions inside the platform.</p>
                                            </div>
                                        </div>
                                        <div class="surface-card-body">
                                            <div class="activity-feed">
                                                <?php if (empty($recent_activities)): ?>
                                                    <div class="dashboard-empty">
                                                        <i class="fas fa-history fa-2x mb-3"></i>
                                                        <p>No recent activities</p>
                                                    </div>
                                                <?php else: ?>
                                                    <?php foreach ($recent_activities as $activity): ?>
                                                        <div class="activity-item">
                                                            <h6 class="mb-1"><?= htmlspecialchars($activity['action']) ?></h6>
                                                            <p class="text-muted small mb-1"><?= strip_tags($activity['details']) ?></p>
                                                            <small class="text-muted"><?= htmlspecialchars(format_date($activity['created_at'], 'M d, h:i A')) ?></small>
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
                </div>
            </div>
        </div>
    </div>
    <?php include "../../includes/footer.php"; ?>
</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const orgCategories = <?php echo json_encode($categories); ?>;
    const orgCategoryCounts = <?php echo json_encode($category_counts); ?>;
    const orgCategoriesChart = document.getElementById('org-categories-chart');

    if (orgCategoriesChart) {
        new Chart(orgCategoriesChart, {
            type: 'pie',
            data: {
                labels: orgCategories,
                datasets: [{
                    data: orgCategoryCounts,
                    backgroundColor: ['#7A0019', '#F4B000', '#4B0010', '#FFC72C', '#A61B34', '#8F6B14', '#6E6257', '#A62828', '#5E0B1A', '#D89A00']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    const userMonths = <?php echo json_encode($months); ?>;
    const activeUsers = <?php echo json_encode($user_counts); ?>;
    const userActivityChart = document.getElementById('user-activity-chart');

    if (userActivityChart) {
        new Chart(userActivityChart, {
            type: 'line',
            data: {
                labels: userMonths,
                datasets: [{
                    label: 'New Users',
                    data: activeUsers,
                    borderColor: '#7A0019',
                    backgroundColor: 'rgba(122, 0, 25, 0.12)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
</script>
