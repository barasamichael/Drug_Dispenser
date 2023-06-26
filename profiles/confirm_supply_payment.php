<?php
require_once('../connect.php');
require_once('../config.php');

session_start();
/* ---------------------------------------------------------------------------------------------- *
 *                       ONLY LOGGED IN USERS CAN ACCESS THESE CONTENT                            *
 * ---------------------------------------------------------------------------------------------- */
if (!isset($_SESSION['role']))
{
	header("Location: ../authentication/login.php");
	exit;
}

/* ---------------------------------------------------------------------------------------------- *
 *                         ALLOW ACCESS TO ADMINISTRATOR AND SUPERVISOR                           *
 * ---------------------------------------------------------------------------------------------- */
if ($_SESSION['role'] != 'administrator' && $_SESSION['role'] != 'supervisor')
{
	http_response_code(403);
	header("Location: ../templates/errors/403.php");
	exit;
}
$contractSupplyId = $_GET['contractSupplyId'];

/* ---------------------------------------------------------------------------------------------- *
 *                           RETRIEVE RELEVANT RECORDS FROM DATABASE                              *
 * ---------------------------------------------------------------------------------------------- */
$databaseHandler = new DatabaseHandler($dsn, $username, $password);
$databaseHandler->connect();

// Extract contractId for current contract supply record
$contract_query = "SELECT contractId from contract_supply WHERE contractSupplyId " .
	"= $contractSupplyId";
$contractId = $databaseHandler->selectQuery($contract_query)[0][0];

$update_query = "UPDATE contract_supply SET paymentComplete = :paymentComplete " .
	"WHERE contractSupplyId = :contractSupplyId";
$attributes = ["paymentComplete" => 1, "contractSupplyId" => $contractSupplyId];

/* ---------------------------------------------------------------------------------------------- *
 *                                  UPDATE PRESCRIPTION STATUS                                    *
 * ---------------------------------------------------------------------------------------------- */
$databaseHandler->executeQuery($update_query, $attributes);
$databaseHandler->disconnect();

/* ---------------------------------------------------------------------------------------------- *
 *                              REDIRECT USER TO SUPPLY_PROFILE PAGE                              *
 * ---------------------------------------------------------------------------------------------- */
header("Location: contract_profile.php?contractId=$contractId");
exit;
?>
