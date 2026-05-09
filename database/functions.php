<?php

include_once "config.php";
require_once(__DIR__ . "/../includes/log_activity.php");

function db_connection($connection = null)
{
    global $conn;
    return $connection ?: $conn;
}

function role_name_from_id($role_id)
{
    $role_names = [
        1 => 'admin',
        2 => 'leader',
        3 => 'student',
    ];

    return $role_names[$role_id] ?? 'unknown';
}

function verify_user_password($plain_password, $stored_password)
{
    if (!is_string($stored_password) || $stored_password === '') {
        return false;
    }

    if (password_verify($plain_password, $stored_password)) {
        return true;
    }

    return hash_equals($stored_password, $plain_password);
}

function hash_user_password($plain_password)
{
    return password_hash($plain_password, PASSWORD_DEFAULT);
}

function get_user_by_id($id, $connection = null)
{
    $db = db_connection($connection);
    $stmt = $db->prepare("
        SELECT u.*, r.name AS role_name
        FROM users u
        LEFT JOIN roles r ON u.role_id = r.role_id
        WHERE u.user_id = ?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc() ?: null;
}

function count_unread_notifications($user_id, $connection = null)
{
    $db = db_connection($connection);
    $stmt = $db->prepare("SELECT COUNT(*) AS count FROM notifications WHERE user_id = ? AND is_read = 0");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return (int)($result['count'] ?? 0);
}

function get_recent_notifications($user_id, $limit = 5, $connection = null)
{
    $db = db_connection($connection);
    $limit = max(1, (int)$limit);
    $stmt = $db->prepare("
        SELECT notification_id, user_id, message, sent_at, is_read
        FROM notifications
        WHERE user_id = ?
        ORDER BY sent_at DESC
        LIMIT ?
    ");
    $stmt->bind_param("ii", $user_id, $limit);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function mark_notifications_as_read($user_id, $connection = null)
{
    $db = db_connection($connection);
    $stmt = $db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    return $stmt->execute();
}

function get_organizations_by_status($status, $connection = null)
{
    $db = db_connection($connection);
    $stmt = $db->prepare("
        SELECT o.*, c.category_name, u.full_name AS leader_name
        FROM organizations o
        LEFT JOIN org_categories c ON o.category_id = c.category_id
        LEFT JOIN users u ON o.leader_id = u.user_id
        WHERE o.status = ?
        ORDER BY o.created_at DESC
    ");
    $stmt->bind_param("s", $status);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function format_date($date, $format = 'Y-m-d H:i:s')
{
    if (empty($date)) {
        return '';
    }

    return date($format, strtotime($date));
}

function getUserByEmail($email, $connection = null)
{
    $db = db_connection($connection);
    $stmt = $db->prepare("
        SELECT u.*, r.name AS role_name
        FROM users u
        LEFT JOIN roles r ON u.role_id = r.role_id
        WHERE u.email = ?
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc() ?: null;
}

function getUsers($filters = [], $connection = null)
{
    $db = db_connection($connection);
    $sql = "
        SELECT
            u.user_id,
            u.full_name,
            u.email,
            u.role_id,
            u.status,
            r.name AS role,
            COALESCE(GROUP_CONCAT(DISTINCT o.name ORDER BY o.name SEPARATOR ', '), 'N/A') AS organizations
        FROM users u
        LEFT JOIN roles r ON u.role_id = r.role_id
        LEFT JOIN membership m ON u.user_id = m.user_id
        LEFT JOIN organizations o ON m.org_id = o.org_id
        WHERE 1=1
    ";

    $types = "";
    $params = [];

    if (!empty($filters['search'])) {
        $sql .= " AND (u.full_name LIKE ? OR u.email LIKE ?)";
        $search = "%{$filters['search']}%";
        $types .= "ss";
        $params[] = $search;
        $params[] = $search;
    }

    if (!empty($filters['role'])) {
        $sql .= " AND u.role_id = ?";
        $types .= "i";
        $params[] = (int)$filters['role'];
    }

    if (!empty($filters['status'])) {
        $sql .= " AND u.status = ?";
        $types .= "s";
        $params[] = $filters['status'];
    }

    $sql .= " GROUP BY u.user_id, u.full_name, u.email, u.role_id, u.status, r.name ORDER BY u.user_id DESC";

    $stmt = $db->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getOrganizations($status = null, $connection = null)
{
    $db = db_connection($connection);
    $sql = "
        SELECT
            o.*,
            c.category_name,
            u.full_name AS leader_name,
            u.email AS leader_email,
            COALESCE(COUNT(m.membership_id), 0) AS member_count
        FROM organizations o
        LEFT JOIN org_categories c ON o.category_id = c.category_id
        LEFT JOIN users u ON o.leader_id = u.user_id
        LEFT JOIN membership m ON o.org_id = m.org_id
    ";

    if ($status !== null) {
        $sql .= " WHERE o.status = ?";
    }

    $sql .= " GROUP BY o.org_id, c.category_name, u.full_name, u.email ORDER BY o.created_at DESC";
    $stmt = $db->prepare($sql);

    if ($status !== null) {
        $stmt->bind_param("s", $status);
    }

    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getEvents($filters = [], $connection = null)
{
    $db = db_connection($connection);
    $sql = "
        SELECT
            e.*,
            o.name AS organization_name
        FROM events e
        LEFT JOIN organizations o ON e.org_id = o.org_id
        WHERE 1=1
    ";

    $types = "";
    $params = [];

    if (!empty($filters['date'])) {
        $sql .= " AND DATE(e.date_time) = ?";
        $types .= "s";
        $params[] = $filters['date'];
    }

    if (!empty($filters['organization'])) {
        $sql .= " AND e.org_id = ?";
        $types .= "i";
        $params[] = (int)$filters['organization'];
    }

    if (!empty($filters['status'])) {
        $sql .= " AND e.status = ?";
        $types .= "s";
        $params[] = $filters['status'];
    }

    $sql .= " ORDER BY e.date_time DESC";

    $stmt = $db->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getActivityLogs($filters = [], $connection = null)
{
    $db = db_connection($connection);
    $sql = "
        SELECT
            l.*,
            u.full_name AS user_name,
            u.email AS user_email
        FROM activity_logs l
        LEFT JOIN users u ON l.user_id = u.user_id
        WHERE 1=1
    ";

    $types = "";
    $params = [];

    if (!empty($filters['user_id'])) {
        $sql .= " AND l.user_id = ?";
        $types .= "i";
        $params[] = (int)$filters['user_id'];
    }

    if (!empty($filters['action'])) {
        $sql .= " AND l.action = ?";
        $types .= "s";
        $params[] = $filters['action'];
    }

    if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
        $sql .= " AND l.created_at BETWEEN ? AND ?";
        $types .= "ss";
        $params[] = $filters['date_from'] . ' 00:00:00';
        $params[] = $filters['date_to'] . ' 23:59:59';
    }

    $sql .= " ORDER BY l.created_at DESC";

    $stmt = $db->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getAnnouncements($connection, $user_id, $role)
{
    $db = db_connection($connection);

    if ($role === 'admin') {
        $stmt = $db->prepare("
            SELECT n.*, u.full_name AS recipient_name
            FROM notifications n
            LEFT JOIN users u ON n.user_id = u.user_id
            ORDER BY n.sent_at DESC
        ");
    } else {
        $stmt = $db->prepare("
            SELECT n.*, u.full_name AS recipient_name
            FROM notifications n
            LEFT JOIN users u ON n.user_id = u.user_id
            WHERE n.user_id = ?
            ORDER BY n.sent_at DESC
        ");
        $stmt->bind_param("i", $user_id);
    }

    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function sendAdminAnnouncement($connection, $message, $target_audience, $postData)
{
    $db = db_connection($connection);

    if ($target_audience === 'all') {
        $stmt = $db->prepare("
            INSERT INTO notifications (user_id, message, sent_at)
            SELECT user_id, ?, NOW()
            FROM users
            WHERE role_id <> 1
        ");
        $stmt->bind_param("s", $message);
        return $stmt->execute();
    }

    if ($target_audience === 'organizations' && !empty($postData['target_organizations'])) {
        foreach ($postData['target_organizations'] as $org_id) {
            $stmt = $db->prepare("
                INSERT INTO notifications (user_id, message, sent_at)
                SELECT DISTINCT user_id, ?, NOW()
                FROM membership
                WHERE org_id = ?
            ");
            $stmt->bind_param("si", $message, $org_id);
            $stmt->execute();
        }

        return true;
    }

    if ($target_audience === 'roles' && !empty($postData['target_roles'])) {
        foreach ($postData['target_roles'] as $role_name) {
            $stmt = $db->prepare("
                INSERT INTO notifications (user_id, message, sent_at)
                SELECT u.user_id, ?, NOW()
                FROM users u
                INNER JOIN roles r ON u.role_id = r.role_id
                WHERE r.name = ?
            ");
            $stmt->bind_param("ss", $message, $role_name);
            $stmt->execute();
        }

        return true;
    }

    return false;
}

function sendOrgLeaderAnnouncement($connection, $message, $user_id)
{
    $db = db_connection($connection);
    $stmt = $db->prepare("
        INSERT INTO notifications (user_id, message, sent_at)
        SELECT DISTINCT m.user_id, ?, NOW()
        FROM organizations o
        INNER JOIN membership m ON o.org_id = m.org_id
        WHERE o.leader_id = ?
    ");
    $stmt->bind_param("si", $message, $user_id);
    return $stmt->execute();
}

function get_leader_organization($leader_id, $connection = null)
{
    $db = db_connection($connection);
    $stmt = $db->prepare("
        SELECT o.*, c.category_name, u.full_name AS leader_name, u.email AS leader_email
        FROM organizations o
        LEFT JOIN org_categories c ON o.category_id = c.category_id
        LEFT JOIN users u ON o.leader_id = u.user_id
        WHERE o.leader_id = ?
        LIMIT 1
    ");
    $stmt->bind_param("i", $leader_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc() ?: null;
}

function get_total_members($leader_id, $connection = null)
{
    $db = db_connection($connection);
    $stmt = $db->prepare("
        SELECT COUNT(*) AS total
        FROM membership m
        INNER JOIN organizations o ON m.org_id = o.org_id
        WHERE o.leader_id = ?
    ");
    $stmt->bind_param("i", $leader_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return (int)($result['total'] ?? 0);
}

function get_total_org_events($leader_id, $connection = null)
{
    $db = db_connection($connection);
    $stmt = $db->prepare("
        SELECT COUNT(*) AS total
        FROM events e
        INNER JOIN organizations o ON e.org_id = o.org_id
        WHERE o.leader_id = ?
    ");
    $stmt->bind_param("i", $leader_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return (int)($result['total'] ?? 0);
}

function get_all_org_events($leader_id, $connection = null)
{
    $db = db_connection($connection);
    $stmt = $db->prepare("
        SELECT e.*, o.name AS organization_name
        FROM events e
        INNER JOIN organizations o ON e.org_id = o.org_id
        WHERE o.leader_id = ?
        ORDER BY e.date_time DESC
    ");
    $stmt->bind_param("i", $leader_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function get_org_members($org_id, $connection = null)
{
    $db = db_connection($connection);
    $stmt = $db->prepare("
        SELECT m.membership_id, m.user_id, u.full_name, u.email, m.role, m.status
        FROM membership m
        INNER JOIN users u ON m.user_id = u.user_id
        WHERE m.org_id = ?
        ORDER BY u.full_name ASC
    ");
    $stmt->bind_param("i", $org_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function get_all_events($connection = null)
{
    $db = db_connection($connection);
    $query = "SELECT event_id, title, date_time, status FROM events ORDER BY date_time DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function get_org_events($org_id, $connection = null)
{
    $db = db_connection($connection);
    $stmt = $db->prepare("
        SELECT event_id, title, description, date_time, venue, capacity, status
        FROM events
        WHERE org_id = ?
        ORDER BY date_time DESC
    ");
    $stmt->bind_param("i", $org_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function get_user_organization_ids($user_id, $connection = null)
{
    $db = db_connection($connection);
    $stmt = $db->prepare("
        SELECT org_id
        FROM membership
        WHERE user_id = ? AND status = 'approved'
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $org_ids = [];
    while ($row = $result->fetch_assoc()) {
        $org_ids[] = (int)$row['org_id'];
    }

    return $org_ids;
}

function get_recent_org_events($student_id, $connection = null)
{
    $db = db_connection($connection);
    $stmt = $db->prepare("
        SELECT DISTINCT e.event_id, e.title, e.description, e.date_time, e.venue
        FROM events e
        INNER JOIN membership m ON e.org_id = m.org_id
        WHERE m.user_id = ? AND m.status = 'approved'
        ORDER BY e.date_time DESC
        LIMIT 6
    ");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function get_recommended_organizations($student_id, $connection = null)
{
    $db = db_connection($connection);
    $joined_org_ids = get_user_organization_ids($student_id, $db);

    $sql = "
        SELECT org_id, name, description, logo
        FROM organizations
        WHERE status = 'active'
    ";

    $types = "";
    $params = [];

    if (!empty($joined_org_ids)) {
        $placeholders = implode(',', array_fill(0, count($joined_org_ids), '?'));
        $sql .= " AND org_id NOT IN ($placeholders)";
        $types .= str_repeat('i', count($joined_org_ids));
        $params = $joined_org_ids;
    }

    $sql .= " ORDER BY created_at DESC LIMIT 3";

    $stmt = $db->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function get_user_events($student_id, $connection = null)
{
    $db = db_connection($connection);
    $stmt = $db->prepare("
        SELECT DISTINCT e.event_id, e.title, e.description, e.date_time, e.venue, e.capacity, e.status
        FROM events e
        INNER JOIN membership m ON e.org_id = m.org_id
        WHERE m.user_id = ? AND m.status = 'approved' AND e.date_time >= NOW()
        ORDER BY e.date_time ASC
        LIMIT 6
    ");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function get_user_organizations($student_id, $connection = null)
{
    $db = db_connection($connection);
    $stmt = $db->prepare("
        SELECT DISTINCT o.org_id, o.name, o.description, o.logo
        FROM organizations o
        INNER JOIN membership m ON o.org_id = m.org_id
        WHERE m.user_id = ? AND m.status = 'approved'
        ORDER BY o.name ASC
    ");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
