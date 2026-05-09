<?php
require_once(__DIR__ . "/../../../database/config.php");

session_start();

if (!isset($_SESSION["user_id"], $_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    http_response_code(403);
    exit("Unauthorized");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['event_id'])) {
    exit("Invalid request");
}

$event_id = (int)$_POST['event_id'];

$checkStmt = $conn->prepare("SELECT event_id FROM events WHERE event_id = ?");
$checkStmt->bind_param("i", $event_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {
    $checkStmt->close();
    exit("error: Event not found.");
}
$checkStmt->close();

$stmt = $conn->prepare("DELETE FROM events WHERE event_id = ?");
$stmt->bind_param("i", $event_id);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error: " . $conn->error;
}

$stmt->close();
