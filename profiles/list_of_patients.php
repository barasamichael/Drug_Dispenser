<?php
require_once("../connect.php");
echo "<link rel = 'stylesheet' href = '../bootstrap.min.css'>";

// database credentials
$dsn = 'mysql:host=localhost; dbname=drugs_db';
$username = 'root';
$password = 'MySQLXXX-123a8910';

// Retrieve patient details from database
$dbHandler = new DatabaseHandler($dsn, $username, $password);
$dbHandler->connect();
$result = $dbHandler->selectQuery('SELECT * FROM patient');
$dbHandler->disconnect();

// display heading of page
$content = <<<_HTML
	<div>
	<h3 style = "color = green;" class = "page-header">List Of Patients</h3>
	_HTML;

// display patients in table
$content .= <<<_HTML
	<table class = 'table table-striped table-responsive table-hover'>
	<thead>
	<tr>
	<th>Patient ID</th>
	<th>Name</th>
	<th>Email Address</th>
	<th>Phone Number</th>
	<th>Residential Address</th>
	<th>Social Security </th>
	</tr>
	</thead>
	<body>
	_HTML;

// populate table rows with data
foreach ($result as $row)
{
	$content .= <<<_HTML
		<tr>
		<td>{$row['patientId']}</td>
		<td>{$row['firstName']} {$row['middleName']} {$row['lastName']}</td>
		<td>{$row['emailAddress']}</td>
		<td>{$row['phoneNumber']}</td>
		<td>{$row['residentialAddress']}</td>
		<td>{$row['SSN']}</td>
		</tr>
		_HTML;
}

// complete creation of table
$content .= <<<_HTML
	</body>
	</table>
	</div>
	_HTML;

// Provide title of page (used in base template)
$title = "List of patients";

require_once('../templates/base.php');
?>
