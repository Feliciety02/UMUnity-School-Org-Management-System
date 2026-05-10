<?php
require_once(__DIR__ . "/../../database/config.php");
require_once(__DIR__ . "/../../database/functions.php");
include "../../includes/header.php";

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure the session is properly set and user is a leader
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'leader') {
    header("Location: /pages/login.php");
    exit();
}

// Validate Database Connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Get Leader ID
$leader_id = $_SESSION['user_id'];
$leader = get_user_by_id($leader_id, $conn);

if (!$leader) {
    die("Error: Leader user not found in the database.");
}

// Fetch organization details
$organization = get_leader_organization($leader_id, $conn);
$org_name = $organization ? $organization['name'] : "No Organization Assigned";

// Fetch leader-specific statistics (initial values for page load)
$total_members = get_total_members($leader_id, $conn);
$total_events = get_total_org_events($leader_id, $conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leader Dashboard</title>
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="/assets/css/includes.css">
    <link rel="stylesheet" href="/assets/css/table.css">

    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>


    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <?php include "../../includes/sidebar.php"; ?>
        </div>
        <!-- Main Content -->
        <div class="main-content">
            <div class="container">
                <div class="dashboard-stack">
                    <section class="page-hero">
                        <span class="page-eyebrow"><i class="fas fa-user-tie"></i> Leader Workspace</span>
                        <h1 class="page-hero-title">Leader Dashboard</h1>
                        <p class="page-hero-copy">Keep your organization moving with a clearer overview of members, events, and the actions that matter next.</p>
                        <div class="page-hero-meta">
                            <span class="page-hero-chip"><i class="fas fa-building"></i> <?= htmlspecialchars($org_name); ?></span>
                            <span class="page-hero-chip"><i class="fas fa-users"></i> <?= $total_members ?> members</span>
                            <span class="page-hero-chip"><i class="fas fa-calendar-day"></i> <?= $total_events ?> upcoming events</span>
                        </div>
                    </section>

                    <div class="metrics-grid">
                        <div class="metric-card wide card">
                            <div class="card-body">
                                <div class="metric-card-top">
                                    <div>
                                        <p class="metric-label">Member Base</p>
                                        <h3 class="metric-value mb-0"><?= $total_members ?></h3>
                                    </div>
                                    <div class="stats-card-icon bg-primary">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                                <p class="metric-note">Active members currently connected to your organization.</p>
                            </div>
                        </div>

                        <div class="metric-card wide card">
                            <div class="card-body">
                                <div class="metric-card-top">
                                    <div>
                                        <p class="metric-label">Event Pipeline</p>
                                        <h3 class="metric-value mb-0"><?= $total_events ?></h3>
                                    </div>
                                    <div class="stats-card-icon bg-warning">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                </div>
                                <p class="metric-note">Scheduled events currently attached to your organization.</p>
                            </div>
                        </div>
                    </div>

                    <div class="feature-tile-grid">
                        <div class="feature-tile card">
                            <div class="card-body">
                                <div class="meta-row">
                                    <span class="badge bg-primary">Members</span>
                                </div>
                                <h4 class="mb-0">Manage Members</h4>
                                <p class="text-muted mb-0">Review membership status, coordinate leaders, and keep your roster organized.</p>
                                <a href="manage_members.php" class="btn btn-primary">Open Members</a>
                            </div>
                        </div>

                        <div class="feature-tile card">
                            <div class="card-body">
                                <div class="meta-row">
                                    <span class="badge bg-warning">Events</span>
                                </div>
                                <h4 class="mb-0">Manage Events</h4>
                                <p class="text-muted mb-0">Create, review, and update organization events from one place.</p>
                                <a href="create_event.php" class="btn btn-primary">Open Events</a>
                            </div>
                        </div>

                        <div class="feature-tile card">
                            <div class="card-body">
                                <div class="meta-row">
                                    <span class="badge bg-secondary">Organization</span>
                                </div>
                                <h4 class="mb-0">Profile and Resources</h4>
                                <p class="text-muted mb-0">Keep the logo, description, and resource links polished and up to date.</p>
                                <a href="my_organization.php" class="btn btn-outline-primary">Open Organization</a>
                            </div>
                        </div>
                    </div>

                    <div class="surface-card">
                        <div class="surface-card-header">
                            <div>
                                <h5 class="mb-0">Leadership Snapshot</h5>
                                <p class="surface-card-copy mb-0">A concise overview of the organization you are currently assigned to manage.</p>
                            </div>
                        </div>
                        <div class="surface-card-body">
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <p class="metric-label">Leader</p>
                                            <h4 class="mb-2"><?= htmlspecialchars($leader['full_name']); ?></h4>
                                            <p class="text-muted mb-0">Signed in as the current organization leader.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <p class="metric-label">Assigned Organization</p>
                                            <h4 class="mb-2"><?= htmlspecialchars($org_name); ?></h4>
                                            <p class="text-muted mb-0">Use the organization workspace to update branding, resources, and profile details.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Footer -->
    </div>
    <?php include "../../includes/footer.php"; ?>

</body>

</html>
