<?php
require_once('../connect.php');

// database credentials
$dsn = 'mysql:host=localhost; dbname=drugs_db';
$username = 'root';
$password = 'MySQLXXX-123a8910';

// Retrieve supply details and associated from database
$databaseHandler = new DatabaseHandler($dsn, $username, $password);
$databaseHandler->connect();

$update_query = "UPDATE contract_supply SET paymentComplete = :paymentComplete " .
	"WHERE contractSupplyId = :contractSupplyId";
$attributes = ["paymentComplete" => 1, "contractSupplyId" => 3];

$databaseHandler->executeQuery($update_query, $attributes);
$databaseHandler->disconnect();

header("Location: contract_supply_profile.php");
exit;
?>
