<?php
// includes/logout.php
session_start();
session_unset();
session_destroy();
header("Location: /service_management/pages/login.php");
exit;
?>
