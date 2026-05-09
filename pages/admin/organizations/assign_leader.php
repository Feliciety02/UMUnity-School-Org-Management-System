<?php
require_once(__DIR__ . "/../../../database/config.php");

session_start();

if (!isset($_SESSION["user_id"], $_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    http_response_code(403);
    exit("Unauthorized");
}

if (!isset($_POST["org_id"], $_POST["leader_id"])) {
    exit("invalid request");
}

$orgId = (int)$_POST["org_id"];
$leaderId = (int)$_POST["leader_id"];

$stmt = $conn->prepare("UPDATE organizations SET leader_id = ? WHERE org_id = ?");
$stmt->bind_param("ii", $leaderId, $orgId);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}

$stmt->close();
