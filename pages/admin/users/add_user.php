<?php
require_once(__DIR__ . "/../../../database/config.php");
require_once(__DIR__ . "/../../../database/functions.php");
require_once(__DIR__ . "/../../../includes/log_activity.php");

session_start();

if (!isset($_SESSION["user_id"], $_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    http_response_code(403);
    exit("error|Unauthorized");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $roleId = (int)($_POST['role'] ?? 0);
    $orgId = !empty($_POST['org_id']) ? (int)$_POST['org_id'] : null;

    if ($fullName === '' || $email === '' || $password === '' || !in_array($roleId, [1, 2, 3], true)) {
        exit("error|Invalid user data.");
    }

    $checkQuery = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $checkQuery->bind_param("s", $email);
    $checkQuery->execute();
    $checkQuery->store_result();

    if ($checkQuery->num_rows > 0) {
        $checkQuery->close();
        exit("error|Email already exists!");
    }
    $checkQuery->close();

    $password_hash = hash_user_password($password);
    $insertQuery = $conn->prepare("INSERT INTO users (full_name, email, password, role_id, status) VALUES (?, ?, ?, ?, 'active')");
    $insertQuery->bind_param("sssi", $fullName, $email, $password_hash, $roleId);

    if (!$insertQuery->execute()) {
        $insertQuery->close();
        exit("error|Failed to add user!");
    }

    $newUserId = $insertQuery->insert_id;
    $insertQuery->close();

    $orgName = "N/A";
    if ($orgId) {
        $membershipRole = ($roleId === 2) ? 'officer' : 'member';
        $membershipQuery = $conn->prepare("INSERT INTO membership (user_id, org_id, status, role) VALUES (?, ?, 'approved', ?)");
        $membershipQuery->bind_param("iis", $newUserId, $orgId, $membershipRole);
        $membershipQuery->execute();
        $membershipQuery->close();

        $orgQuery = $conn->prepare("SELECT name FROM organizations WHERE org_id = ?");
        $orgQuery->bind_param("i", $orgId);
        $orgQuery->execute();
        $orgResult = $orgQuery->get_result();
        if ($orgRow = $orgResult->fetch_assoc()) {
            $orgName = $orgRow['name'];
        }
        $orgQuery->close();
    }

    $adminId = $_SESSION["user_id"];
    $roleName = ucfirst(role_name_from_id($roleId));
    $logMessage = "<b>$fullName</b> (<b>$email</b>) added as <b>$roleName</b>" . ($orgId ? " in <b>$orgName</b>" : "");
    logActivity($adminId, "User Added", $logMessage);

    echo "success|$newUserId|$fullName|$email|$roleId|$orgName";
}
