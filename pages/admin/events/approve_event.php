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

$stmt = $conn->prepare("UPDATE events SET status = 'approved' WHERE event_id = ?");
$stmt->bind_param("i", $event_id);

if ($stmt->execute()) {
    echo "success|$event_id|approved";
} else {
    echo "error: " . $conn->error;
}

$stmt->close();
