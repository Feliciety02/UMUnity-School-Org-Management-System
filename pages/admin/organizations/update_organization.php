<?php
require_once(__DIR__ . "/../../../database/config.php");

session_start();

if (!isset($_SESSION["user_id"], $_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    http_response_code(403);
    exit("error|Unauthorized");
}

if (!isset($_POST['org_id'])) {
    exit("error|Invalid request");
}

$org_id = (int)$_POST['org_id'];
$org_name = trim($_POST['name'] ?? '');
$org_description = trim($_POST['description'] ?? '');
$category_id = (int)($_POST['category_id'] ?? 0);
$leader_id = !empty($_POST['leader_id']) ? (int)$_POST['leader_id'] : 0;

$fetch_logo = $conn->prepare("SELECT logo FROM organizations WHERE org_id = ?");
$fetch_logo->bind_param("i", $org_id);
$fetch_logo->execute();
$fetch_logo_result = $fetch_logo->get_result();
$org_data = $fetch_logo_result->fetch_assoc();
$org_logo = $org_data['logo'] ?? "/assets/images/orgs/default-org.png";
$fetch_logo->close();

$sql = "UPDATE organizations SET name = ?, description = ?, category_id = ?, leader_id = ? WHERE org_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssiii", $org_name, $org_description, $category_id, $leader_id, $org_id);

if (!$stmt->execute()) {
    $stmt->close();
    exit("error|Error updating organization: " . $stmt->error);
}
$stmt->close();

$removeStmt = $conn->prepare("DELETE FROM membership WHERE org_id = ? AND role = 'officer'");
$removeStmt->bind_param("i", $org_id);
$removeStmt->execute();
$removeStmt->close();

if ($leader_id > 0) {
    $leaderStmt = $conn->prepare("INSERT INTO membership (org_id, user_id, status, role) VALUES (?, ?, 'approved', 'officer')");
    $leaderStmt->bind_param("ii", $org_id, $leader_id);
    $leaderStmt->execute();
    $leaderStmt->close();
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

echo "success|$org_id|$org_name|$org_description|$leaderName|$categoryName|$org_logo";
