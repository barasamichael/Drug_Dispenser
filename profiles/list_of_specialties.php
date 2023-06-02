<?php
require_once("../connect.php");
require_once("../config.php");
echo "<link rel = 'stylesheet' href = '../bootstrap.min.css'>";

// Retrieve patient details from database
$dbHandler = new DatabaseHandler($dsn, $username, $password);
$dbHandler->connect();
$result = $dbHandler->selectQuery('SELECT * FROM specialty');
$dbHandler->disconnect();

// display heading of page
$content = <<<_HTML
	<div>
	<h3 style = "color = green;" class = "page-header">List Of Specialties</h3>
	_HTML;

// display specialties in table
$content .= <<<_HTML
	<table class = 'table table-striped table-responsive table-hover'>
	<thead>
	<tr>
	<th>Specialty</th>
	<th>Description</th>
	</tr>
	</thead>
	<body>
	_HTML;

// populate table rows with data
foreach ($result as $row)
{
	$content .= <<<_HTML
		<tr>
		<td>{$row['title']}</td>
		<td>{$row['description']}</td>
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
$title = "List of Specialties";

require_once('../templates/base.php');
?>
