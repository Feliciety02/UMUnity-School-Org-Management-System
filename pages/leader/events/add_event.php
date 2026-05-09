<?php
require_once(__DIR__ . "/../../../database/config.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'leader') {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "Unauthorized access!"]);
    exit();
}

$leader_id = $_SESSION['user_id'];
$query = "SELECT org_id, name FROM organizations WHERE leader_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $leader_id);
$stmt->execute();
$result = $stmt->get_result();
$organization = $result->fetch_assoc();
$stmt->close();

if (!$organization) {
    echo json_encode(["success" => false, "message" => "You are not assigned to any organization."]);
    exit();
}

$org_id = (int)$organization['org_id'];
$org_name = $organization['name'];
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$date_time = $_POST['date_time'] ?? '';
$venue = trim($_POST['venue'] ?? '');
$capacity = (int)($_POST['capacity'] ?? 0);

if ($title === '' || $description === '' || $date_time === '' || $venue === '') {
    echo json_encode(["success" => false, "message" => "All fields are required!"]);
    exit();
}

$sql = "INSERT INTO events (org_id, title, description, date_time, venue, capacity, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issssi", $org_id, $title, $description, $date_time, $venue, $capacity);

if ($stmt->execute()) {
    $event_id = $stmt->insert_id;
    echo json_encode([
        "success" => true,
        "message" => "Event created successfully!",
        "event_id" => $event_id,
        "title" => $title,
        "description" => $description,
        "date_time" => $date_time,
        "venue" => $venue,
        "capacity" => $capacity,
        "organization" => $org_name,
        "status" => "pending"
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Error creating event: " . $stmt->error]);
}

$stmt->close();
