<?php
session_start();
require_once '../../database/config.php';
require_once '../../database/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = (int)($_POST['user_id'] ?? 0);
    $current_password = trim($_POST['current_password'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    $query = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $query->bind_param("i", $user_id);
    $query->execute();
    $result = $query->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        $_SESSION['toast_messages'][] = "User not found.";
        header("Location: profile.php");
        exit();
    }

    if (!verify_user_password($current_password, $user['password'])) {
        $_SESSION['toast_messages'][] = "Current password is incorrect.";
        header("Location: profile.php");
        exit();
    }

    if (strlen($new_password) < 6) {
        $_SESSION['toast_messages'][] = "New password must be at least 6 characters long.";
        header("Location: profile.php");
        exit();
    }

    if ($new_password !== $confirm_password) {
        $_SESSION['toast_messages'][] = "New password and confirmation do not match.";
        header("Location: profile.php");
        exit();
    }

    $hashed_password = hash_user_password($new_password);
    $update_query = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
    $update_query->bind_param("si", $hashed_password, $user_id);

    if ($update_query->execute()) {
        $_SESSION['toast_messages'][] = "Password updated successfully.";
    } else {
        $_SESSION['toast_messages'][] = "Error updating password. Please try again.";
    }

    header("Location: profile.php");
    exit();
}
