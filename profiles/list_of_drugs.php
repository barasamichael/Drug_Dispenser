<?php
require_once("../connect.php");
require_once("../config.php");
echo "<link rel = 'stylesheet' href = '../bootstrap.min.css'>";

// Retrieve drug details from database
$dbHandler = new DatabaseHandler($dsn, $username, $password);
$dbHandler->connect();
$result = $dbHandler->selectQuery('SELECT * FROM drug');
$dbHandler->disconnect();

// display heading of page
$content = <<<_HTML
	<div>
	<h3 style = "color = green;" class = "page-header">List Of Registered Drugs</h3>
	_HTML;

// display drugs in table
$content .= <<<_HTML
	<table class = 'table table-striped table-responsive table-hover'>
	<thead>
	<tr>
	<th>Record ID</th>
	<th>Drug Scientific Name</th>
	<th>Formula</th>
	<th>Form</th>
	</tr>
	</thead>
	<body>
	_HTML;

// populate table rows with data
foreach ($result as $row)
{
	$content .= <<<_HTML
		<tr>
		<td>{$row['drugId']}</td>
		<td>{$row['scientificName']}</td>
		<td>{$row['formula']}</td>
		<td>{$row['form']}</td>
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
$title = "List of Registered Drugs";

require_once('../templates/base.php');
?>
