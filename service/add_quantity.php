<?php
session_start();
require_once '../includes/db.php';

$user_id   = $_SESSION['user']['user_id'] ?? null;
$project_id= intval($_POST['project_id'] ?? 0);

$workName = trim($_POST['work_name']);
$unitId   = intval($_POST['unit_id']);
$rateId   = intval($_POST['rate_id']);
$qty      = floatval($_POST['quantity']);
$date     = $_POST['date'] ?: date('Y-m-d');

if (!$user_id || $project_id<=0 || !$workName || !$unitId || !$rateId || $qty<=0) {
    die("Invalid input.");
}

$stmt = $conn->prepare("
  INSERT INTO work_quantities
    (project_id, rate_id, quantity, work_name, unit_id, date)
  VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("iidsis",
  $project_id, $rateId, $qty, $workName, $unitId, $date
);
$stmt->execute();
header("Location: site_finance.php?project_id=$project_id");
