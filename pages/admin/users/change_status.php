<?php
require_once(__DIR__ . "/../../../database/config.php");

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["user_id"], $_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request!"]);
    exit();
}

if (!isset($_POST["user_id"], $_POST["status"])) {
    echo json_encode(["status" => "error", "message" => "Missing required parameters!"]);
    exit();
}

$user_id = (int)$_POST["user_id"];
$new_status = ($_POST["status"] === "active") ? "active" : "inactive";

$checkStmt = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
$checkStmt->bind_param("i", $user_id);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows === 0) {
    $checkStmt->close();
    echo json_encode(["status" => "error", "message" => "User not found!"]);
    exit();
}
$checkStmt->close();

$stmt = $conn->prepare("UPDATE users SET status = ? WHERE user_id = ?");
$stmt->bind_param("si", $new_status, $user_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}

$stmt->close();
