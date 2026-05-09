<?php
require_once(__DIR__ . "/../../../database/config.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'leader') {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

if (!isset($_POST['event_id'])) {
    echo json_encode(["error" => "No event ID provided"]);
    exit();
}

$event_id = (int)$_POST['event_id'];
$leader_id = $_SESSION['user_id'];

$sql = "
    SELECT e.event_id, e.title, e.description, e.date_time, e.venue, e.capacity, e.status,
           COALESCE(o.name, 'N/A') AS organization
    FROM events e
    INNER JOIN organizations o ON e.org_id = o.org_id
    WHERE e.event_id = ? AND o.leader_id = ?
    LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $event_id, $leader_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();
$stmt->close();

echo json_encode($event ?: ["error" => "Event not found"]);
