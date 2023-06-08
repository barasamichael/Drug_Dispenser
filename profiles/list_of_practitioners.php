<?php
require_once("../connect.php");
require_once("../config.php");
echo "<link rel = 'stylesheet' href = '../bootstrap.min.css'>";

// Retrieve practitioner details from database
$dbHandler = new DatabaseHandler($dsn, $username, $password);
$dbHandler->connect();
$result = $dbHandler->selectQuery('SELECT * FROM practitioner');
$dbHandler->disconnect();

// display heading of page
$content = <<<_HTML
	<div>
	<h3 style = "color = green;" class = "page-header">List Of Practitioners</h3>
	_HTML;

// display practitioners in table
$content .= <<<_HTML
	<table class = 'table table-striped table-responsive table-hover'>
	<thead>
	<tr>
	<th>Practitioner ID</th>
	<th>Name</th>
	<th>Email Address</th>
	<th>Phone Number</th>
	<th>Social Security Number</th>
	</tr>
	</thead>
	<body>
	_HTML;

// populate table rows with data
foreach ($result as $row)
{
	$content .= <<<_HTML
		<tr>
		<td>
		<a href = "practitioner_profile.php?practitionerId={$row['practitionerId']}">
		{$row['practitionerId']}
		</a>
		</td>
		<td>
		<a href = "practitioner_profile.php?practitionerId={$row['practitionerId']}">
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
$title = "List of Practitioners";

require_once('../templates/base.php');
?>
