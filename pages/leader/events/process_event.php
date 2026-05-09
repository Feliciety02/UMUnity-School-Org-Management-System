<?php
require_once(__DIR__ . "/../../../database/config.php");

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'leader') {
    http_response_code(403);
    exit("Unauthorized");
}

$event_id = (int)($_POST['event_id'] ?? 0);
$action = $_POST['action'] ?? '';
$leader_id = $_SESSION['user_id'];

if ($event_id <= 0 || !in_array($action, ['approve', 'delete', 'reject'], true)) {
    exit("Invalid request");
}

if ($action === 'delete') {
    $stmt = $conn->prepare("
        DELETE e FROM events e
        INNER JOIN organizations o ON e.org_id = o.org_id
        WHERE e.event_id = ? AND o.leader_id = ?
    ");
    $stmt->bind_param("ii", $event_id, $leader_id);
    echo $stmt->execute() ? "Event Deleted" : "Error deleting event";
    $stmt->close();
    exit();
}

$status = ($action === 'approve') ? 'approved' : 'rejected';
$stmt = $conn->prepare("
    UPDATE events e
    INNER JOIN organizations o ON e.org_id = o.org_id
    SET e.status = ?
    WHERE e.event_id = ? AND o.leader_id = ?
");
$stmt->bind_param("sii", $status, $event_id, $leader_id);

if ($stmt->execute()) {
    exit($action === 'approve' ? "Event Approved" : "Event Rejected");
}

exit($action === 'approve' ? "Error approving event" : "Error rejecting event");
