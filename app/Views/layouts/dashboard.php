<?php
$role = strtolower((string)($currentUser['role_name'] ?? $currentUser['role'] ?? 'student'));
$roleKey = $role === 'admin' ? 'admin' : ($role === 'leader' ? 'leader' : 'student');
$navByRole = [
    'admin' => [
        'logo' => asset('assets/images/logo/adminlogo.png'),
        'home' => url('/dashboard/admin'),
        'items' => [
            ['href' => url('/dashboard/admin'), 'key' => 'dashboard', 'icon' => 'fas fa-tachometer-alt', 'label' => 'Dashboard'],
            ['href' => asset('pages/admin/manage_users.php'), 'key' => 'users', 'icon' => 'fas fa-users', 'label' => 'Manage Users'],
            ['href' => asset('pages/admin/manage_organizations.php'), 'key' => 'organizations', 'icon' => 'fas fa-sitemap', 'label' => 'Organizations'],
            ['href' => asset('pages/admin/manage_events.php'), 'key' => 'events', 'icon' => 'fas fa-calendar-alt', 'label' => 'Events'],
            ['href' => asset('pages/admin/manage_reports.php'), 'key' => 'reports', 'icon' => 'fas fa-chart-bar', 'label' => 'Reports'],
        ],
    ],
    'leader' => [
        'logo' => asset('assets/images/logo/officerlogo.png'),
        'home' => url('/dashboard/leader'),
        'items' => [
            ['href' => url('/dashboard/leader'), 'key' => 'dashboard', 'icon' => 'fas fa-tachometer-alt', 'label' => 'Dashboard'],
            ['href' => asset('pages/leader/manage_members.php'), 'key' => 'members', 'icon' => 'fas fa-user-friends', 'label' => 'Members'],
            ['href' => asset('pages/leader/create_event.php'), 'key' => 'events', 'icon' => 'fas fa-calendar-plus', 'label' => 'Events'],
            ['href' => asset('pages/leader/my_organization.php'), 'key' => 'organization', 'icon' => 'fas fa-building', 'label' => 'Organization'],
        ],
    ],
    'student' => [
        'logo' => asset('assets/images/logo/studentlogo.png'),
        'home' => url('/dashboard/student'),
        'items' => [
            ['href' => url('/dashboard/student'), 'key' => 'dashboard', 'icon' => 'fas fa-tachometer-alt', 'label' => 'Dashboard'],
            ['href' => asset('pages/profile/profile.php'), 'key' => 'profile', 'icon' => 'fas fa-user', 'label' => 'Profile'],
        ],
    ],
];
$nav = $navByRole[$roleKey];
$userName = htmlspecialchars((string)($currentUser['full_name'] ?? 'User'));
$profilePic = !empty($currentUser['profile_pic']) ? '/' . ltrim((string)$currentUser['profile_pic'], '/') : asset('assets/images/profile/default-user.png');
$profilePath = app()->basePath(ltrim($currentUser['profile_pic'] ?? '', '/'));
$profileImage = (!empty($currentUser['profile_pic']) && is_file($profilePath))
    ? asset((string)$currentUser['profile_pic'])
    : asset('assets/images/profile/default-user.png');
$notificationCount = count_unread_notifications((int)$currentUser['user_id'], app()->db());
$notifications = get_recent_notifications((int)$currentUser['user_id'], 5, app()->db());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(($title ?? '') === '' ? 'Dashboard' : "{$title} - UMUnity") ?></title>
    <link rel="stylesheet" href="<?= asset('assets/css/includes.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/css/dashboard.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/css/table.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/css/profile.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
<div class="wrapper">
    <div class="sidebar">
        <aside class="app-sidebar">
            <a class="sidebar-brand" href="<?= $nav['home'] ?>">
                <img src="<?= $nav['logo'] ?>" alt="UMUnity logo">
            </a>

            <ul class="sidebar-nav">
                <?php foreach ($nav['items'] as $item): ?>
                    <li>
                        <a href="<?= $item['href'] ?>" class="sidebar-item <?= ($currentPage ?? '') === $item['key'] ? 'active' : '' ?>">
                            <i class="<?= $item['icon'] ?>"></i> <?= htmlspecialchars($item['label']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li>
                    <a href="<?= url('/logout') ?>" class="sidebar-item logout-link">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </aside>
    </div>

    <div class="main-header">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <button class="btn sidebar-toggle" type="button" aria-label="Toggle navigation">
                    <span class="material-icons-outlined">menu</span>
                </button>

                <div class="nav-search">
                    <input type="text" class="search-input" placeholder="Search modules, organizations, events..." disabled>
                </div>

                <div class="right-nav">
                    <div class="dropdown me-1">
                        <button class="btn btn-light position-relative notification-btn" type="button" data-bs-toggle="dropdown">
                            <span class="material-icons-outlined">notifications</span>
                            <?php if ($notificationCount > 0): ?>
                                <span class="badge rounded-pill"><?= $notificationCount ?></span>
                            <?php endif; ?>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end notification-dropdown p-0">
                            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-white">Notifications</h6>
                                <small class="text-white-50"><?= $notificationCount ?> unread</small>
                            </div>
                            <div class="notification-list">
                                <?php if (empty($notifications)): ?>
                                    <div class="p-4 text-center text-white">
                                        <span class="material-icons-outlined" style="font-size: 36px;">notifications_off</span>
                                        <p class="mb-0 mt-2">No recent notifications</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($notifications as $notification): ?>
                                        <div class="notification-item <?= ((int)$notification['is_read'] === 1) ? '' : 'unread' ?>">
                                            <h6 class="mb-1">Notification</h6>
                                            <p class="small mb-1"><?= htmlspecialchars((string)$notification['message']) ?></p>
                                            <small><?= htmlspecialchars(format_date((string)$notification['sent_at'], 'M d, h:i A')) ?></small>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-light profile-dropdown dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                            <img src="<?= $profileImage ?>" alt="Profile Picture" class="profile-dropdown-img me-2">
                            <span class="profile-text"><?= $userName ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= asset('pages/profile/profile.php') ?>"><i class="fas fa-user me-2"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="<?= url('/logout') ?>"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <main class="main-content">
        <?= $content ?>
    </main>
</div>

<footer class="footer mt-auto">
    <div class="footer-card">
        <div class="container-fluid text-center">
            <p class="mb-0 text-muted">&copy; <?= date('Y') ?> UMUnity. All rights reserved.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const toggle = document.querySelector(".sidebar-toggle");
    if (!toggle) {
        return;
    }

    toggle.addEventListener("click", function () {
        document.body.classList.toggle("sidebar-open");
    });

    document.addEventListener("click", function (event) {
        const isMobile = window.innerWidth <= 991;
        if (!isMobile || !document.body.classList.contains("sidebar-open")) {
            return;
        }

        const insideSidebar = event.target.closest(".app-sidebar");
        const insideToggle = event.target.closest(".sidebar-toggle");
        if (!insideSidebar && !insideToggle) {
            document.body.classList.remove("sidebar-open");
        }
    });
});
</script>
</body>
</html>
