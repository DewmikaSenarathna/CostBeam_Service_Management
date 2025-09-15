<?php
// includes/db.php
require_once __DIR__ . '/../config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    error_log("Database Connection Failed: " . $conn->connect_error);
    die("We are experiencing technical difficulties. Please try again later.");
}
?>
