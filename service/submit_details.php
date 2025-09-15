<?php
session_start();
require_once '../includes/db.php';

// Ensure user is logged in
if (!isset($_SESSION['user'])) {
    die("You must be logged in to submit a project.");
}

// Get user ID from session
$user_id = $_SESSION['user']['user_id'];

// Collect form data
$project_name     = trim($_POST['project_name']);
$contract_number  = trim($_POST['contract_number']);
$contractor       = trim($_POST['contractor']);
$employer         = trim($_POST['employer']);
$consultancy      = trim($_POST['consultancy']);
$estimated_value  = trim($_POST['estimated_value']);
$started_date     = $_POST['started_date'];
$completion_date  = $_POST['completion_date'];
$description      = trim($_POST['description']);

// Insert new project
$stmt = $conn->prepare("INSERT INTO projects (user_id, project_name, contract_number, contractor, employer, consultancy, estimated_value, started_date, completion_date, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssssssss", $user_id, $project_name, $contract_number, $contractor, $employer, $consultancy, $estimated_value, $started_date, $completion_date, $description);

if ($stmt->execute()) {
    echo "<script>
            alert('Saved successfully!');
            window.location.href='new_project.php';
          </script>";
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
?>
