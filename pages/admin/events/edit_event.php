<?php
require_once(__DIR__ . "/../../../database/config.php");

session_start();

if (!isset($_SESSION["user_id"], $_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    http_response_code(403);
    exit("error|Unauthorized");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("error|Invalid request");
}

$event_id = (int)($_POST['event_id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$date_time = $_POST['date_time'] ?? '';
$venue = trim($_POST['venue'] ?? '');
$capacity = (int)($_POST['capacity'] ?? 0);

$stmt = $conn->prepare("
    UPDATE events
    SET title = ?, description = ?, date_time = ?, venue = ?, capacity = ?
    WHERE event_id = ?
");
$stmt->bind_param("ssssii", $title, $description, $date_time, $venue, $capacity, $event_id);

if (!$stmt->execute()) {
    $stmt->close();
    exit("error: " . $conn->error);
}
$stmt->close();

$statusStmt = $conn->prepare("SELECT status FROM events WHERE event_id = ?");
$statusStmt->bind_param("i", $event_id);
$statusStmt->execute();
$result = $statusStmt->get_result();
$event = $result->fetch_assoc();
$statusStmt->close();

$status = $event["status"] ?? "pending";
echo "success|$event_id|$title|$date_time|$venue|$status";
