<?php
session_start();

// 1) Redirect unauthenticated users to login
if (!isset($_SESSION['user'])) {
    header('Location: ../pages/login.php');
    exit();
}

// 2) Include the database connection
require_once __DIR__ . '/../includes/db.php';

// 3) Fetch and validate IDs
$user_id     = $_SESSION['user']['user_id'];
$quantity_id = filter_input(INPUT_GET, 'id',          FILTER_VALIDATE_INT);
$project_id  = filter_input(INPUT_GET, 'project_id',  FILTER_VALIDATE_INT);

if (!$quantity_id || !$project_id) {
    die('Invalid request.');
}

// 4) Verify ownership of the work_quantity row
$stmt = $conn->prepare(
    'SELECT 1
       FROM work_quantities wq
       JOIN projects       p  ON wq.project_id = p.project_id
      WHERE wq.quantity_id = ?
        AND p.user_id      = ?'
);
$stmt->bind_param('ii', $quantity_id, $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $stmt->close();
    die('Unauthorized or row not found.');
}
$stmt->close();

// 5) Delete the quantity (cascade will clean up spendings)
$stmt = $conn->prepare('DELETE FROM work_quantities WHERE quantity_id = ?');
$stmt->bind_param('i', $quantity_id);
$stmt->execute();
$stmt->close();

// 6) Redirect back to the finance summary using an absolute path
header("Location: /service_management/service/site_finance.php?project_id={$project_id}");
exit();
