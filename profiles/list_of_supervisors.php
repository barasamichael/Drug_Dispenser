<?php
require_once("../connect.php");
require_once("../config.php");
echo "<link rel = 'stylesheet' href = '../bootstrap.min.css'>";

// Retrieve supervisor details from database
$dbHandler = new DatabaseHandler($dsn, $username, $password);
$dbHandler->connect();
$result = $dbHandler->selectQuery('SELECT * FROM supervisor');
$dbHandler->disconnect();

// display heading of page
$content = <<<_HTML
	<div>
	<h3 style = "color = green;" class = "page-header">List Of Supervisors</h3>
	_HTML;

// display practitioners in table
$content .= <<<_HTML
	<table class = 'table table-striped table-responsive table-hover'>
	<thead>
	<tr>
	<th>Supervisor ID</th>
	<th>Name</th>
	<th>Email Address</th>
	<th>Phone Number</th>
	<th>Status</th>
	</tr>
	</thead>
	<body>
	_HTML;

// populate table rows with data
foreach ($result as $row)
{
	$content .= <<<_HTML
		<tr>
		<td>{$row['supervisorId']}</td>
		<td>{$row['firstName']} {$row['middleName']} {$row['lastName']}</td>
		<td>{$row['emailAddress']}</td>
		<td>{$row['phoneNumber']}</td>
		_HTML;

	if ($row['active'] == 1)
	{
		$content .= <<<_HTML
			<td style = "color: green;">Active</td>
			_HTML;
	}
	else
	{
		$content .= <<<_HTML
			<td style = "color: green;">Active</td>
			_HTML;
	}
	$content .= <<<_HTML
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
$title = "List of Supervisors";

require_once('../templates/base.php');
?>
