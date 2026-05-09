<?php
$role = $_SESSION["role"] ?? null;
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<?php
$nav_by_role = [
    'admin' => [
        'logo' => '/assets/images/logo/adminlogo.png',
        'home' => '/pages/admin/dashboard.php',
        'items' => [
            ['href' => '/pages/admin/dashboard.php', 'page' => 'dashboard.php', 'icon' => 'fas fa-tachometer-alt', 'label' => 'Dashboard'],
            ['href' => '/pages/admin/manage_users.php', 'page' => 'manage_users.php', 'icon' => 'fas fa-users', 'label' => 'Manage Users'],
            ['href' => '/pages/admin/manage_organizations.php', 'page' => 'manage_organizations.php', 'icon' => 'fas fa-home-alt', 'label' => 'Manage Organizations'],
            ['href' => '/pages/admin/manage_events.php', 'page' => 'manage_events.php', 'icon' => 'fas fa-calendar-alt', 'label' => 'Manage Events'],
            ['href' => '/pages/admin/manage_reports.php', 'page' => 'manage_reports.php', 'icon' => 'fas fa-chart-bar', 'label' => 'Reports'],
        ],
    ],
    'leader' => [
        'logo' => '/assets/images/logo/officerlogo.png',
        'home' => '/pages/leader/dashboard.php',
        'items' => [
            ['href' => '/pages/leader/dashboard.php', 'page' => 'dashboard.php', 'icon' => 'fas fa-tachometer-alt', 'label' => 'Dashboard'],
            ['href' => '/pages/leader/manage_members.php', 'page' => 'manage_members.php', 'icon' => 'fas fa-user-friends', 'label' => 'Manage Members'],
            ['href' => '/pages/leader/create_event.php', 'page' => 'create_event.php', 'icon' => 'fas fa-plus-circle', 'label' => 'Manage Events'],
            ['href' => '/pages/leader/my_organization.php', 'page' => 'my_organization.php', 'icon' => 'fas fa-home-alt', 'label' => 'My Organization'],
        ],
    ],
    'student' => [
        'logo' => '/assets/images/logo/studentlogo.png',
        'home' => '/pages/student/dashboard.php',
        'items' => [
            ['href' => '/pages/student/dashboard.php', 'page' => 'dashboard.php', 'icon' => 'fas fa-tachometer-alt', 'label' => 'Dashboard'],
        ],
    ],
];

$nav = $nav_by_role[$role] ?? null;
?>

<?php if ($nav): ?>
    <aside class="app-sidebar">
        <a class="sidebar-brand" href="<?= htmlspecialchars($nav['home']) ?>">
            <img src="<?= htmlspecialchars($nav['logo']) ?>" alt="UMUnity logo">
        </a>

        <ul class="sidebar-nav">
            <?php foreach ($nav['items'] as $item): ?>
                <li>
                    <a href="<?= htmlspecialchars($item['href']) ?>" class="sidebar-item <?= ($currentPage === $item['page']) ? 'active' : '' ?>">
                        <i class="<?= htmlspecialchars($item['icon']) ?>"></i> <?= htmlspecialchars($item['label']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
            <li>
                <a href="/pages/logout.php" class="sidebar-item logout-link">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </aside>
<?php endif; ?>
