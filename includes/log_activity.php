<?php
require_once(__DIR__ . "/../database/config.php");

if (!function_exists("logActivity")) {
    function logActivity($userId, $action, $details)
    {
        global $conn;

        if (!$conn instanceof mysqli) {
            return false;
        }

        $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $stmt = $conn->prepare("
            INSERT INTO activity_logs (user_id, action, details, ip_address, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("isss", $userId, $action, $details, $ip_address);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }
}
