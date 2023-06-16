<?php
session_start();
session_destroy();

// reset session array
$SESSION = array();
header("Location: login.php");
exit;
?>
