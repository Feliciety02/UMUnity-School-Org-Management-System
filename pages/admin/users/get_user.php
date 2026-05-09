<?php
require_once(__DIR__ . "/../../../database/config.php");

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["user_id"], $_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["user_id"])) {
    echo json_encode(["error" => "Invalid request"]);
    exit();
}

$user_id = (int)$_POST["user_id"];

$stmt = $conn->prepare("
    SELECT
        u.user_id,
        u.full_name,
        u.email,
        u.role_id,
        COALESCE(m.org_id, '') AS org_id
    FROM users u
    LEFT JOIN membership m ON u.user_id = m.user_id
    WHERE u.user_id = ?
    LIMIT 1
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    echo json_encode($user);
} else {
    echo json_encode(["error" => "User not found"]);
}

$stmt->close();
