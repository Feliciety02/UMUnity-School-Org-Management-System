<?php
require_once(__DIR__ . "/../../../database/config.php");

session_start();

if (!isset($_SESSION["user_id"], $_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    http_response_code(403);
    exit("error|Unauthorized");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("error|Invalid request");
}

$org_id = (int)($_POST['org_id'] ?? 0);
$org_name = trim($_POST['name'] ?? '');
$org_description = trim($_POST['description'] ?? '');
$category_id = (int)($_POST['category_id'] ?? 0);
$leader_id = !empty($_POST['leader_id']) ? (int)$_POST['leader_id'] : 0;

$fetch_logo = $conn->prepare("SELECT logo FROM organizations WHERE org_id = ?");
$fetch_logo->bind_param("i", $org_id);
$fetch_logo->execute();
$result = $fetch_logo->get_result();
$org_data = $result->fetch_assoc();
$old_logo = $org_data['logo'] ?? '/assets/images/orgs/default-org.png';
$fetch_logo->close();

$updated_logo = $old_logo;
if (!empty($_FILES['logo']) && $_FILES['logo']['size'] > 0) {
    $upload_dir = __DIR__ . "/../../../assets/images/orgs/";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_ext = strtolower(pathinfo($_FILES["logo"]["name"], PATHINFO_EXTENSION));
    $valid_extensions = ["jpg", "jpeg", "png"];
    if (!in_array($file_ext, $valid_extensions, true)) {
        exit("error|Invalid file format. Only JPG, JPEG, and PNG allowed.");
    }

    $logo_file_name = "org_{$org_id}_" . time() . "." . $file_ext;
    $target_file = $upload_dir . $logo_file_name;
    $db_logo_path = "/assets/images/orgs/" . $logo_file_name;

    if (!move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
        exit("error|Error uploading logo.");
    }

    if (!empty($old_logo) && $old_logo !== "/assets/images/orgs/default-org.png") {
        $old_file_path = __DIR__ . "/../../../" . ltrim($old_logo, '/');
        if (file_exists($old_file_path)) {
            unlink($old_file_path);
        }
    }

    $updated_logo = $db_logo_path;
}

$updateOrgQuery = "UPDATE organizations SET name = ?, description = ?, category_id = ?, logo = ?, leader_id = ? WHERE org_id = ?";
$stmt = $conn->prepare($updateOrgQuery);
$stmt->bind_param("ssisii", $org_name, $org_description, $category_id, $updated_logo, $leader_id, $org_id);

if (!$stmt->execute()) {
    $stmt->close();
    exit("error|Error updating organization: " . $stmt->error);
}
$stmt->close();

$removeLeaderStmt = $conn->prepare("DELETE FROM membership WHERE org_id = ? AND role = 'officer'");
$removeLeaderStmt->bind_param("i", $org_id);
$removeLeaderStmt->execute();
$removeLeaderStmt->close();

$leader_name = "N/A";
if ($leader_id > 0) {
    $assignLeaderStmt = $conn->prepare("INSERT INTO membership (org_id, user_id, status, role) VALUES (?, ?, 'approved', 'officer')");
    $assignLeaderStmt->bind_param("ii", $org_id, $leader_id);
    $assignLeaderStmt->execute();
    $assignLeaderStmt->close();

    $leaderStmt = $conn->prepare("SELECT full_name FROM users WHERE user_id = ?");
    $leaderStmt->bind_param("i", $leader_id);
    $leaderStmt->execute();
    $leaderStmt->bind_result($leader_name);
    $leaderStmt->fetch();
    $leaderStmt->close();
}

$category_name = 'N/A';
$categoryStmt = $conn->prepare("SELECT category_name FROM org_categories WHERE category_id = ?");
$categoryStmt->bind_param("i", $category_id);
$categoryStmt->execute();
$categoryStmt->bind_result($category_name);
$categoryStmt->fetch();
$categoryStmt->close();

echo "success|$org_id|$org_name|$org_description|$leader_name|$category_name|$updated_logo";
