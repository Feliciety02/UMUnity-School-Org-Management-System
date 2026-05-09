<?php
require_once(__DIR__ . "/../../database/config.php");
require_once(__DIR__ . "/../../database/functions.php");
include "../../includes/header.php";

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure the session is properly set and user is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: /pages/login.php");
    exit();
}

// Validate Database Connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Get Student ID
$student_id = $_SESSION['user_id'];
$student = get_user_by_id($student_id, $conn);

if (!$student) {
    die("Error: Student user not found in the database.");
}

// Fetch organizations the student has joined
$joined_organizations = get_user_organizations($student_id, $conn);
$total_organizations = count($joined_organizations);

// Fetch upcoming events for the student's joined organizations
$upcoming_events = get_user_events($student_id, $conn);
$total_upcoming_events = count($upcoming_events);

// Fetch unread notifications
$unread_notifications = count_unread_notifications($student_id, $conn);

// Fetch recommended organizations
$recommended_orgs = get_recommended_organizations($student_id, $conn);

// Fetch recent events from student's joined organizations
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
            <div class="container mt-4">
                <div class="table-responsive">
                    <div class="table-wrapper">
                        <div class="table-title d-flex justify-content-between align-items-center">
                            <div>
                                <h1 style="color: #a83232;"><b> Student Dashboard</b></h1>
                                <p>Manage your memberships and explore opportunities.</p>
                            </div>
                        </div>

                        <div class="container-fluid px-4">
                            <h3 class="mt-4">Welcome, <?php echo htmlspecialchars($student['full_name']); ?>!</h3>
                            <p class="text-muted">Stay updated with your organizations and events.</p>

                            <!-- Student-Specific Stats -->
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body">
                                            <h6 class="text-muted">Joined Organizations</h6>
                                            <h3 id="total-organizations"><?php echo $total_organizations; ?></h3>
                                            <p class="small text-muted">Organizations you are a part of</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body">
                                            <h6 class="text-muted">Upcoming Events</h6>
                                            <h3 id="total-events"><?php echo $total_upcoming_events; ?></h3>
                                            <p class="small text-muted">Events from your organizations</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body">
                                            <h6 class="text-muted">Unread Notifications</h6>
                                            <h3 id="unread-notifications"><?php echo $unread_notifications; ?></h3>
                                            <p class="small text-muted">Check your latest updates</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- My Organizations -->
                            <div class="mt-4">
                                <h4>My Organizations</h4>
                                <div class="row">
                                    <?php foreach ($joined_organizations as $org): ?>
                                        <div class="col-md-4">
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-body">
                                                    <h5><?php echo htmlspecialchars($org['name']); ?></h5>
                                                    <p class="text-muted"><?php echo htmlspecialchars($org['description']); ?></p>
                                                    <a href="organization_details.php?id=<?php echo $org['org_id']; ?>" class="btn btn-outline-primary">View</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Recent Events from Joined Organizations -->
                            <div class="mt-4">
                                <h4>Recent Events from Your Organizations</h4>
                                <div class="row">
                                    <?php foreach ($recent_org_events as $event): ?>
                                        <div class="col-md-4">
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-body">
                                                    <h5><?php echo htmlspecialchars($event['title']); ?></h5>
                                                    <p class="text-muted"><?php echo htmlspecialchars($event['description']); ?></p>
                                                    <p><strong>Date:</strong> <?php echo date("M d, Y", strtotime($event['date_time'])); ?></p>
                                                    <p><strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
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
