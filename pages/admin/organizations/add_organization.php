<?php
require_once(__DIR__ . "/../../../database/config.php");

session_start();

if (!isset($_SESSION["user_id"], $_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    http_response_code(403);
    exit("error|Unauthorized");
}

if (!isset($_POST['org_name'], $_POST['category_id'])) {
    exit("error|Missing organization data.");
}

$org_name = trim($_POST['org_name']);
$org_description = trim($_POST['org_description'] ?? '');
$category_id = (int)$_POST['category_id'];
$leader_id = !empty($_POST['leader_id']) ? (int)$_POST['leader_id'] : 0;

$sql = "INSERT INTO organizations (name, description, category_id, status, leader_id) VALUES (?, ?, ?, 'active', ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssii", $org_name, $org_description, $category_id, $leader_id);

if (!$stmt->execute()) {
    $stmt->close();
    exit("error|Error in organization insertion.");
}

$newOrgId = $conn->insert_id;
$stmt->close();

if ($leader_id > 0) {
    $membershipSql = "INSERT INTO membership (org_id, user_id, status, role) VALUES (?, ?, 'approved', 'officer')";
    $membershipStmt = $conn->prepare($membershipSql);
    $membershipStmt->bind_param("ii", $newOrgId, $leader_id);
    $membershipStmt->execute();
    $membershipStmt->close();
}

$categoryName = 'N/A';
$categoryStmt = $conn->prepare("SELECT category_name FROM org_categories WHERE category_id = ?");
$categoryStmt->bind_param("i", $category_id);
$categoryStmt->execute();
$categoryResult = $categoryStmt->get_result();
if ($categoryRow = $categoryResult->fetch_assoc()) {
    $categoryName = $categoryRow['category_name'];
}
$categoryStmt->close();

$leaderName = "N/A";
if ($leader_id > 0) {
    $leaderStmt = $conn->prepare("SELECT full_name FROM users WHERE user_id = ?");
    $leaderStmt->bind_param("i", $leader_id);
    $leaderStmt->execute();
    $leaderResult = $leaderStmt->get_result();
    if ($leaderRow = $leaderResult->fetch_assoc()) {
        $leaderName = $leaderRow['full_name'];
    }
    $leaderStmt->close();
}

echo "success|$newOrgId|$org_name|$org_description|$leaderName|$categoryName|/assets/images/orgs/default-org.png";
