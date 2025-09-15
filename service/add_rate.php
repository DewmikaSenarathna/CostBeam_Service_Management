<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user'])) {
    die("You must be logged in.");
}

$user_id = $_SESSION['user']['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = intval($_POST['project_id']);
    $rate_name  = trim($_POST['rate_name']);
    $rate_value = floatval($_POST['rate_value']);

    // Verify project belongs to this user
    $stmt = $conn->prepare("SELECT project_id FROM projects WHERE project_id=? AND user_id=?");
    $stmt->bind_param("ii", $project_id, $user_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 0) {
        die("Invalid project or unauthorized.");
    }
    $stmt->close();

    // Insert agreement rate
    $stmt = $conn->prepare("INSERT INTO agreement_rates (project_id, rate_name, rate_value) VALUES (?, ?, ?)");
    $stmt->bind_param("isd", $project_id, $rate_name, $rate_value);
    $stmt->execute();
    $stmt->close();

    header("Location: site_finance.php?project_id=" . $project_id);
    exit();
}
?>
