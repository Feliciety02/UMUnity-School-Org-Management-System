<?php
require_once(__DIR__ . "/../../../database/config.php");
require_once(__DIR__ . "/../../../database/functions.php");
require_once(__DIR__ . "/../../../includes/log_activity.php");

session_start();

if (!isset($_SESSION["user_id"], $_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    http_response_code(403);
    exit("error|Unauthorized");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("error|Invalid request");
}

$userId = (int)($_POST['user_id'] ?? 0);
$fullName = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$roleId = (int)($_POST['role_id'] ?? 0);
$orgId = (isset($_POST['org_id']) && $_POST['org_id'] !== "") ? (int)$_POST['org_id'] : null;

if ($userId <= 0 || $fullName === '' || $email === '' || !in_array($roleId, [1, 2, 3], true)) {
    exit("error|Invalid user data!");
}

$oldQuery = $conn->prepare("
    SELECT full_name, email, role_id,
           (SELECT name FROM organizations WHERE org_id =
            (SELECT org_id FROM membership WHERE user_id = ? LIMIT 1)) AS organization
    FROM users
    WHERE user_id = ?
");
$oldQuery->bind_param("ii", $userId, $userId);
$oldQuery->execute();
$oldQuery->bind_result($oldFullName, $oldEmail, $oldRoleId, $oldOrgName);
$oldQuery->fetch();
$oldQuery->close();

$checkQuery = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
$checkQuery->bind_param("si", $email, $userId);
$checkQuery->execute();
$checkQuery->store_result();
if ($checkQuery->num_rows > 0) {
    $checkQuery->close();
    exit("error|Email already exists!");
}
$checkQuery->close();

$updateQuery = $conn->prepare("UPDATE users SET full_name = ?, email = ?, role_id = ? WHERE user_id = ?");
$updateQuery->bind_param("ssii", $fullName, $email, $roleId, $userId);

if (!$updateQuery->execute()) {
    $updateQuery->close();
    exit("error|Failed to update user!");
}
$updateQuery->close();

$orgName = "N/A";
if ($orgId) {
    $orgCheck = $conn->prepare("SELECT membership_id FROM membership WHERE user_id = ?");
    $orgCheck->bind_param("i", $userId);
    $orgCheck->execute();
    $orgCheck->store_result();

    $membershipRole = ($roleId === 2) ? 'officer' : 'member';
    if ($orgCheck->num_rows > 0) {
        $orgUpdate = $conn->prepare("UPDATE membership SET org_id = ?, role = ? WHERE user_id = ?");
        $orgUpdate->bind_param("isi", $orgId, $membershipRole, $userId);
        $orgUpdate->execute();
        $orgUpdate->close();
    } else {
        $orgInsert = $conn->prepare("INSERT INTO membership (user_id, org_id, status, role) VALUES (?, ?, 'approved', ?)");
        $orgInsert->bind_param("iis", $userId, $orgId, $membershipRole);
        $orgInsert->execute();
        $orgInsert->close();
    }
    $orgCheck->close();

    $orgQuery = $conn->prepare("SELECT name FROM organizations WHERE org_id = ?");
    $orgQuery->bind_param("i", $orgId);
    $orgQuery->execute();
    $orgQuery->bind_result($orgName);
    $orgQuery->fetch();
    $orgQuery->close();
} else {
    $removeMembership = $conn->prepare("DELETE FROM membership WHERE user_id = ?");
    $removeMembership->bind_param("i", $userId);
    $removeMembership->execute();
    $removeMembership->close();
}

$adminId = $_SESSION["user_id"];
$newRoleName = ucfirst(role_name_from_id($roleId));
$oldRoleName = ucfirst(role_name_from_id((int)$oldRoleId));
$changes = [];

if ($oldFullName !== $fullName) {
    $changes[] = "Name: <b>$oldFullName</b> to <b>$fullName</b>";
}
if ($oldEmail !== $email) {
    $changes[] = "Email: <b>$oldEmail</b> to <b>$email</b>";
}
if ((int)$oldRoleId !== $roleId) {
    $changes[] = "Role: <b>$oldRoleName</b> to <b>$newRoleName</b>";
}
if (($oldOrgName ?? 'N/A') !== $orgName) {
    $changes[] = "Organization: <b>" . ($oldOrgName ?? 'N/A') . "</b> to <b>$orgName</b>";
}

if (!empty($changes)) {
    $logMessage = "<b>$fullName</b> updated details: " . implode(", ", $changes);
    logActivity($adminId, "User Updated", $logMessage);
}

echo "success|$userId|$fullName|$email|$roleId|" . ($orgId ?: "N/A") . "|$orgName";
