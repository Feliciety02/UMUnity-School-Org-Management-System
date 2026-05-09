<?php
require_once(__DIR__ . "/../../../database/config.php");

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["user_id"], $_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

if (!isset($_POST['org_id'])) {
    echo json_encode(["error" => "Invalid request"]);
    exit();
}

$org_id = (int)$_POST['org_id'];
$stmt = $conn->prepare("
    SELECT o.org_id, o.name, o.description, o.category_id, o.logo, o.leader_id,
           COALESCE(u.full_name, 'N/A') AS leader_name,
           COALESCE(c.category_name, 'N/A') AS category_name
    FROM organizations o
    LEFT JOIN users u ON o.leader_id = u.user_id
    LEFT JOIN org_categories c ON o.category_id = c.category_id
    WHERE o.org_id = ?
    LIMIT 1
");
$stmt->bind_param("i", $org_id);
$stmt->execute();
$result = $stmt->get_result();
$organization = $result->fetch_assoc();
$stmt->close();

echo json_encode($organization ?: ["error" => "Organization not found"]);
