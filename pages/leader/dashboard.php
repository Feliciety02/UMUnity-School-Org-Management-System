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
            <div class="container mt-4">
                <div class="table-responsive">
                    <div class="table-wrapper">
                        <div class="table-title d-flex justify-content-between align-items-center">
                            <div>
                                <h1 style="color:  #a83232 !important;"><b> Leader Dashboard</b></h1>
                                <p>Oversee and coordinate organizations effectively.</p>
                            </div>
                        </div>

                        <div class="container-fluid px-4">
                            <h3 class="mt-4">Welcome, <?php echo htmlspecialchars($leader['full_name']); ?>!</h3>
                            <p class="text-muted">You are managing <strong><?php echo htmlspecialchars($org_name); ?></strong>.</p>

                            <!-- Leader-Specific Stats -->
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body">
                                            <h6 class="text-muted">Total Members</h6>
                                            <h3 id="total-members"><?php echo $total_members; ?></h3>
                                            <p class="small text-muted">Active members in your organization</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body">
                                            <h6 class="text-muted">Upcoming Events</h6>
                                            <h3 id="total-events"><?php echo $total_events; ?></h3>
                                            <p class="small text-muted">Events scheduled for your organization</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Leader Dashboard Options -->
                            <div class="row g-4 mt-4">
                                <div class="col-lg-6">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header">Manage Members</div>
                                        <div class="card-body">
                                            <a href="manage_members.php" class="btn btn-primary">View Members</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="card border-0 shadow-sm">
                                    <div class="card-header">Manage Events</div>
                                        <div class="card-body">
                                            <a href="create_event.php" class="btn btn-primary">View Events</a>
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


    <!-- JavaScript to Update Stats Using AJAX -->
    <script>
        $(document).ready(function() {
            function updateStats() {
                $.ajax({
                    url: "fetch_leader_stats.php",
                    method: "GET",
                    dataType: "json",
                    success: function(data) {
                        $("#total-members").text(data.total_members);
                        $("#total-events").text(data.total_events);
                    },
                    error: function() {
                        console.error("Error fetching stats");
                    }
                });
            }

            // Update stats every 10 seconds
            setInterval(updateStats, 10000);
        });
    </script>

</body>

</html>
