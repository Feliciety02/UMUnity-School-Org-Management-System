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

$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$date_time = $_POST['date_time'] ?? '';
$venue = trim($_POST['venue'] ?? '');
$capacity = (int)($_POST['capacity'] ?? 0);
$org_id = (int)($_POST['org_id'] ?? 0);

if ($title === '' || $description === '' || $date_time === '' || $venue === '' || $org_id <= 0) {
    exit("error|Missing required fields.");
}

$stmt = $conn->prepare("
    INSERT INTO events (org_id, title, description, date_time, venue, capacity, status)
    VALUES (?, ?, ?, ?, ?, ?, 'pending')
");
$stmt->bind_param("issssi", $org_id, $title, $description, $date_time, $venue, $capacity);

if (!$stmt->execute()) {
    $stmt->close();
    exit("error|Failed to add event.");
}

$event_id = $stmt->insert_id;
$stmt->close();

$orgStmt = $conn->prepare("SELECT name FROM organizations WHERE org_id = ?");
$orgStmt->bind_param("i", $org_id);
$orgStmt->execute();
$orgResult = $orgStmt->get_result();
$organization = $orgResult->fetch_assoc()['name'] ?? 'N/A';
$orgStmt->close();

echo "success|$event_id|$title|$description|$date_time|$venue|$capacity|$organization";
