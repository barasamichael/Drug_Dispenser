<?php
require_once('../connect.php');
require_once('../config.php');

$prescriptionId = $_GET['prescriptionId'];

/* --------------------------------------------------------------- *
 *           RETRIEVE RELEVANT RECORDS FROM DATABASE               *
 * --------------------------------------------------------------- */
$databaseHandler = new DatabaseHandler($dsn, $username, $password);
$databaseHandler->connect();

$prescription_query = "SELECT patientId FROM prescription WHERE " .
	"prescriptionId = $prescriptionId";
$update_query = "UPDATE prescription SET assigned = :assigned " .
	"WHERE prescriptionId = :prescriptionId";
$attributes = ["assigned" => 1, "prescriptionId" => $prescriptionId];

$patientId = $databaseHandler->selectQuery($prescription_query)[0][0];

/* --------------------------------------------------------------- *
 *                  UPDATE PRESCRIPTION STATUS                     *
 * --------------------------------------------------------------- */
$databaseHandler->executeQuery($update_query, $attributes);
$databaseHandler->disconnect();

/* --------------------------------------------------------------- *
 *             REDIRECT USER TO PATIENT_PROFILE PAGE               *
 * --------------------------------------------------------------- */
header("Location: patient_profile.php?patientId = $patientId");
exit;
?>
