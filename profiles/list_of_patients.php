<?php
require_once("../connect.php");
require_once("../config.php");
echo "<link rel = 'stylesheet' href = '../bootstrap.min.css'>";

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
	$residentialAddress = urlencode($row['residentialAddress']);
	$googleSearchUrl = "https://www.google.com/maps/search/?api=1&query={$residentialAddress}";
	$content .= <<<_HTML
		<tr>
		<td>
		<a href = "patient_profile.php?patientId={$row['patientId']}">
		{$row['patientId']}
		</a>
		</td>
		<td>
		<a href = "patient_profile.php?patientId={$row['patientId']}">
		{$row['firstName']} {$row['middleName']} {$row['lastName']}
		</a>
		</td>
		<td>
		<a href = "mailto: {$row['emailAddress']}">
		{$row['emailAddress']}
		</a>
		</td>
		<td>
		<a href = "tel: {$row['phoneNumber']}">
		{$row['phoneNumber']}
		</a>
		</td>
		<td>
		<a href = "{$googleSearchUrl}" target = "_blank">
		{$row['residentialAddress']}
		</a>
		</td>
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
