<?php
require_once('../connect.php');
require_once('../config.php');

session_start();

/* ---------------------------------------------------------------------------------------------- *
 *                              ALLOW ADMINISTRATOR AND PHARMACY ACCESS                           *
 * ---------------------------------------------------------------------------------------------- */
if ($_SESSION['role'] != 'administrator' && $_SESSION['role'] != 'pharmacy')
{
	header("Location: ../templates/errors/403.php");
	exit;
}

/* ---------------------------------------------------------------------------------------------- *
 *                             ENSURE ALL LINK PARAMETERS PROVIDED                                *
 * ---------------------------------------------------------------------------------------------- */
if (!$_GET['prescriptionId'])
{
	header("Location: ../templates/errors/invalid_access.php");
	exit;
}
$prescriptionId = $_GET['prescriptionId'];


/* ---------------------------------------------------------------------------------------------- *
 *                                   UPDATE PRESCRIPTION STATUS                                   *
 * ---------------------------------------------------------------------------------------------- */
$databaseHandler = new DatabaseHandler($dsn, $username, $password);
$databaseHandler->connect();
$update_query = "UPDATE prescription SET assigned = :assigned WHERE prescriptionId = " .
	":prescriptionId";
$attributes = ["assigned" => 1, "prescriptionId" => $prescriptionId];
$databaseHandler->executeQuery($update_query, $attributes);
$databaseHandler->disconnect();

/* ---------------------------------------------------------------------------------------------- *
 *                                REDIRECT USER TO PREVIOUS PAGE                                  *
 * ---------------------------------------------------------------------------------------------- */
echo <<<_HTML
	<script>
		window.history.back();
	</script>
_HTML;
?>
