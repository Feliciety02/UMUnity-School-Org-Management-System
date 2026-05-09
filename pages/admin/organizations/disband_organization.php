<?php
require_once(__DIR__ . "/../../../database/config.php");

session_start();

if (!isset($_SESSION["user_id"], $_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    http_response_code(403);
    exit("Unauthorized");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Invalid request!");
}

if (!isset($_POST["org_id"], $_POST["status"])) {
    exit("Error: Missing required parameters!");
}

$org_id = (int)$_POST["org_id"];
$new_status = ($_POST["status"] === "active") ? "active" : "disbanded";

$checkStmt = $conn->prepare("SELECT org_id FROM organizations WHERE org_id = ?");
$checkStmt->bind_param("i", $org_id);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows === 0) {
    $checkStmt->close();
    exit("Error: Organization not found!");
}
$checkStmt->close();

$stmt = $conn->prepare("UPDATE organizations SET status = ? WHERE org_id = ?");
$stmt->bind_param("si", $new_status, $org_id);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
