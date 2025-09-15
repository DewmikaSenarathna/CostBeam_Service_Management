<?php
session_start();
require_once '../includes/db.php';

$user_id    = $_SESSION['user']['user_id'] ?? null;
$project_id = intval($_POST['project_id'] ?? 0);
$quantityId = intval($_POST['quantity_id'] ?? 0);
$rateId     = intval($_POST['rate_id'] ?? 0);
$spend      = floatval($_POST['spend_value']);
$date       = $_POST['date'] ?: date('Y-m-d');

if (!$user_id || $project_id<=0 || $quantityId<=0 || $rateId<=0 || $spend<=0) {
    die("Invalid input.");
}

$stmt = $conn->prepare("
  INSERT INTO spendings
    (project_id, spend_value, quantity_id, rate_id, date)
  VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("idiis",
  $project_id, $spend, $quantityId, $rateId, $date
);
$stmt->execute();
header("Location: site_finance.php?project_id=$project_id");
