<?php
require_once('../connect.php');

$prescriptionId = $_GET['prescriptionId'];

// database credentials
$dsn = 'mysql:host=localhost; dbname=drugs_db';
$username = 'root';
$password = 'MySQLXXX-123a8910';

// Retrieve supply details and associated from database
$databaseHandler = new DatabaseHandler($dsn, $username, $password);
$databaseHandler->connect();

$update_query = "UPDATE prescription SET assigned = :assigned " .
	"WHERE prescriptionId = :prescriptionId";
$attributes = ["assigned" => 1, "prescriptionId" => $prescriptionId];

$databaseHandler->executeQuery($update_query, $attributes);
$databaseHandler->disconnect();

header("Location: patient_profile.php");
exit;
?>
