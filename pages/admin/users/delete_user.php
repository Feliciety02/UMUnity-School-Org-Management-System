<?php
require_once(__DIR__ . "/../../../database/config.php");
require_once(__DIR__ . "/../../../includes/log_activity.php");

session_start();

if (!isset($_SESSION["user_id"], $_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    http_response_code(403);
    exit("Unauthorized");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["user_id"])) {
    exit("Invalid request!");
}

$user_id = (int)$_POST["user_id"];

$userQuery = $conn->prepare("SELECT full_name, email, role_id FROM users WHERE user_id = ?");
$userQuery->bind_param("i", $user_id);
$userQuery->execute();
$userQuery->bind_result($fullName, $email, $roleId);

if (!$userQuery->fetch()) {
    $userQuery->close();
    exit("Error: User not found!");
}
$userQuery->close();

$stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    $adminId = $_SESSION["user_id"];
    $roleName = ($roleId == 1) ? "Admin" : (($roleId == 2) ? "Leader" : "Student");
    $logMessage = "User '<b>$fullName</b>' ($email) with role <b>$roleName</b> was deleted.";
    logActivity($adminId, "User Deleted", $logMessage);
    $stmt->close();
    exit("success");
}

$stmt->close();
exit("Error: " . $conn->error);
