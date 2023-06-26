<?php
require_once("../models.php");
require_once("../config.php");

session_start();

/* ---------------------------------------------------------------------------------------------- *
 *            ALLOW ACCESS TO ADMINISTRATOR, SUPERVISOR, PHARMACY AND PHARMACEUTICAL              *
 * ---------------------------------------------------------------------------------------------- */
if ($_SESSION['role'] == 'patient' || $_SESSION['role'] == 'practitioner')
{
	http_response_code(403);
	header("Location: ../templates/errors/403.php");
	exit;
}

/* ---------------------------------------------------------------------------------------------- *
 *                             ENSURE ALL LINK PARAMETERS PROVIDED                                *
 * ---------------------------------------------------------------------------------------------- */
if (!$_GET['contractId'])
{
	header("Location: ../templates/errors/invalid_access.php");
	exit;
}
$contractId = $_GET['contractId'];

/* ---------------------------------------------------------------------------------------------- *
 *                     INSTANTIATE AN OBJECT OF CLASS ContractSupply AND SAVE                     *
 * ---------------------------------------------------------------------------------------------- */
$contractSupply = new ContractSupply(["contractId" => $contractId]);
$contractSupply->save();

/* ---------------------------------------------------------------------------------------------- *
 *                     REDIRECT USER TO NEWLY CREATED SUPPLY'S PROFILE PAGE                       *
 * ---------------------------------------------------------------------------------------------- */
$databaseHandler = new DatabaseHandler($dsn, $username, $password);
$databaseHandler->connect();
$query = "SELECT contractSupplyId FROM contract_supply ORDER BY dateCreated DESC LIMIT 1";
$contractSupplyId = $databaseHandler->selectQuery($query)[0][0];
$databaseHandler->disconnect();

header("Location: ../profiles/contract_supply_profile.php?contractSupplyId=$contractSupplyId");
exit;
?>
