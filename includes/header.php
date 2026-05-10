<?php
session_start();
require_once(__DIR__ . "/../database/config.php");
require_once(__DIR__ . "/../database/functions.php");

// Redirect to login if not authenticated
if (!isset($_SESSION["user_id"])) {
    header("Location: /pages/login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch user details from the database
$query = $conn->prepare("SELECT full_name, role_id, profile_pic FROM users WHERE user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

// Check if user data is retrieved
if (!$user) {
    die("Error: User data not found. Please check the database.");
}

// Set user details or fallback values
$user_name = !empty($user['full_name']) ? htmlspecialchars($user['full_name']) : 'Unknown User';
$role_id = $user['role_id'] ?? null;

// Profile Image Handling
$default_image = "/assets/images/profile/default-user.png";
$profile_image = (!empty($user['profile_pic']) && file_exists(__DIR__ . "/../" . $user['profile_pic']))
    ? "/" . htmlspecialchars($user['profile_pic'])
    : $default_image;

$role = ucfirst(role_name_from_id($role_id));

// Fetch unread notifications count
$unread_notifications = count_unread_notifications($user_id);
$recent_notifications = get_recent_notifications($user_id, 5);
?>




<div class="main-header">
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <button class="btn sidebar-toggle" type="button" aria-label="Toggle navigation">
                <span class="material-icons-outlined">menu</span>
            </button>

            <!-- Universal Search Bar -->
            <div class="nav-search">
                <input type="text" class="search-input" placeholder="Search users, organizations, events..." />
            </div>

            <!-- Right Side Elements -->
            <div class="right-nav">
                <!-- Notifications Dropdown -->
                <div class="dropdown me-3">
                    <button class="btn btn-light position-relative notification-btn" type="button" data-bs-toggle="dropdown">
                        <span class="material-icons-outlined">notifications</span> <!-- Google Icon -->
                        <?php if ($unread_notifications > 0): ?>
                            <span class="badge rounded-pill bg-danger">
                                <?php echo $unread_notifications; ?>
                            </span>
                        <?php endif; ?>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end notification-dropdown p-0">
                        <div class="p-3 border-bottom d-flex justify-content-between">
                            <h6 class="mb-0 text-white">Notifications</h6>
                            <?php if ($unread_notifications > 0): ?>
                                <a href="?page=notifications" class="small text-white">Mark all as read</a>
                            <?php endif; ?>
                        </div>
                        <div class="notification-list">
                            <?php if (empty($recent_notifications)): ?>
                                <div class="p-4 text-center text-white">
                                    <span class="material-icons-outlined" style="font-size: 36px;">notifications_off</span>
                                    <p>No new notifications</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($recent_notifications as $notif): ?>
                                    <div class="notification-item <?php echo $notif['is_read'] ? '' : 'unread'; ?>">
                                        <div class="d-flex">
                                            <div class="ms-3">
                                                <h6 class="mb-0">Notification</h6>
                                                <p class="small mb-0"><?php echo htmlspecialchars($notif['message']); ?></p>
                                                <small><?php echo format_date($notif['sent_at'], 'M d, h:i A'); ?></small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="p-3 border-top text-center">
                            <a href="?page=notifications" class="btn view-all-btn">View All Notifications</a>
                        </div>
                    </div>
                </div>

                <!-- User Profile Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-light profile-dropdown dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                        <?php
                        // Fetch user's latest profile picture or fallback to default
                        $profile_pic = (!empty($user['profile_pic']) && file_exists(__DIR__ . "/../" . $user['profile_pic']))
                            ? "/" . htmlspecialchars($user['profile_pic'])
                            : "/assets/images/profile/default-user.png";

                        // Ensure the user's name is always displayed
                        $user_name = !empty($user['full_name']) ? htmlspecialchars($user['full_name']) : "Unknown User";
                        ?>

                        <img src="<?= $profile_pic ?>" alt="Profile Picture" class="profile-dropdown-img me-2">
                        <span class="profile-text"><?= $user_name; ?></span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/pages/profile/profile.php"><i class="fas fa-user me-2"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="/pages/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </nav>
</div>
