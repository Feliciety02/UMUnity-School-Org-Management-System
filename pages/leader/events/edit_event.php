<?php
require_once(__DIR__ . "/../../../database/config.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'leader') {
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
$leader_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    UPDATE events e
    INNER JOIN organizations o ON e.org_id = o.org_id
    SET e.title = ?, e.description = ?, e.date_time = ?, e.venue = ?, e.capacity = ?
    WHERE e.event_id = ? AND o.leader_id = ?
");
$stmt->bind_param("ssssiii", $title, $description, $date_time, $venue, $capacity, $event_id, $leader_id);

if (!$stmt->execute() || $stmt->affected_rows < 0) {
    $stmt->close();
    exit("error: " . $conn->error);
}
$stmt->close();

$statusStmt = $conn->prepare("
    SELECT e.status
    FROM events e
    INNER JOIN organizations o ON e.org_id = o.org_id
    WHERE e.event_id = ? AND o.leader_id = ?
    LIMIT 1
");
$statusStmt->bind_param("ii", $event_id, $leader_id);
$statusStmt->execute();
$result = $statusStmt->get_result();
$event = $result->fetch_assoc();
$statusStmt->close();

if (!$event) {
    exit("error|Event not found");
}

echo "success|$event_id|$title|$date_time|$venue|" . ($event["status"] ?? "pending");
