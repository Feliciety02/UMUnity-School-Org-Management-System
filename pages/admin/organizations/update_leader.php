<?php
require_once(__DIR__ . "/../../../database/config.php");

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["user_id"], $_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit();
}

$leader_id = isset($_POST["leader_id"]) ? (int)$_POST["leader_id"] : 0;
$org_id = isset($_POST["org_id"]) ? (int)$_POST["org_id"] : 0;

if ($leader_id === 0 || $org_id === 0) {
    echo json_encode(["success" => false, "message" => "Invalid organization or leader selected."]);
    exit();
}

$leaderStmt = $conn->prepare("SELECT full_name FROM users WHERE user_id = ?");
$leaderStmt->bind_param("i", $leader_id);
$leaderStmt->execute();
$leaderStmt->bind_result($leader_name);
$leaderStmt->fetch();
$leaderStmt->close();

if (!$leader_name) {
    echo json_encode(["success" => false, "message" => "Selected leader does not exist."]);
    exit();
}

$stmt = $conn->prepare("UPDATE organizations SET leader_id = ? WHERE org_id = ?");
$stmt->bind_param("ii", $leader_id, $org_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "leader_name" => $leader_name, "org_id" => $org_id]);
} else {
    echo json_encode(["success" => false, "message" => "Database update failed: " . $conn->error]);
}

$stmt->close();
