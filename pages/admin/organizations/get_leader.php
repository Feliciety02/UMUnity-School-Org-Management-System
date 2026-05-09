<?php
require_once(__DIR__ . "/../../../database/config.php");

session_start();

if (!isset($_SESSION["user_id"], $_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    http_response_code(403);
    exit('<option value="">Unauthorized</option>');
}

$sql = "SELECT user_id, full_name FROM users WHERE role_id = 2 ORDER BY full_name ASC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . (int)$row['user_id'] . '">' . htmlspecialchars($row['full_name']) . '</option>';
    }
} else {
    echo '<option value="">No leaders available</option>';
}
