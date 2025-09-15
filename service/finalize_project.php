<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../pages/login.php');
    exit();
}

$user_id    = $_SESSION['user']['user_id'];
$project_id = filter_input(INPUT_GET, 'project_id', FILTER_VALIDATE_INT);

if (! $project_id) {
    die('Invalid project.');
}

// Verify ownership
$stmt = $conn->prepare("
    SELECT 1
      FROM projects
     WHERE project_id = ?
       AND user_id    = ?
     LIMIT 1
");
$stmt->bind_param('ii', $project_id, $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    die('Unauthorized.');
}
$stmt->close();

// Flip status to completed
$u = $conn->prepare("
    UPDATE projects
       SET status = 'completed'
     WHERE project_id = ?
       AND user_id    = ?
");
$u->bind_param('ii', $project_id, $user_id);
$u->execute();
$u->close();

// Redirect to completed list
header("Location: completed_projects.php");
exit();
