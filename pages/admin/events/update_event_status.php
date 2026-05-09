<?php
require_once(__DIR__ . "/../../../database/config.php");

session_start();

if (!isset($_SESSION["user_id"], $_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    http_response_code(403);
    exit("error");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("error");
}

$event_id = (int)($_POST["event_id"] ?? 0);
$status = $_POST["status"] ?? '';
$reason = isset($_POST["reason"]) ? trim($_POST["reason"]) : null;

if ($event_id <= 0 || !in_array($status, ["approved", "rejected"], true)) {
    exit("error");
}

$eventUpdate = $conn->prepare("UPDATE events SET status = ? WHERE event_id = ?");
$eventUpdate->bind_param("si", $status, $event_id);
$eventUpdate->execute();
$eventUpdate->close();

if ($status === "approved") {
    $stmt = $conn->prepare("
        INSERT INTO event_status (event_id, status)
        VALUES (?, ?)
        ON DUPLICATE KEY UPDATE status = VALUES(status), reason = NULL
    ");
    $stmt->bind_param("is", $event_id, $status);
} elseif ($reason) {
    $stmt = $conn->prepare("
        INSERT INTO event_status (event_id, status, reason)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE status = VALUES(status), reason = VALUES(reason)
    ");
    $stmt->bind_param("iss", $event_id, $status, $reason);
} else {
    exit("error");
}

echo $stmt->execute() ? "success" : "error";
$stmt->close();
