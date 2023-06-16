<?php
require_once("../connect.php");
require_once("../config.php");

session_start();

/* ---------------------------------------------------------------------------------------------- *
 *                                      ALLOW ADMINISTRATOR ACCESS                                *
 * ---------------------------------------------------------------------------------------------- */
if ($_SESSION['role'] != 'administrator')
{
	http_response_code(403);
	header("Location: ../templates/errors/403.php");
	exit;
}

/* ---------------------------------------------------------------------------------------------- *
 *                                RETRIEVE DRUG RECORDS FROM DATABASE                             *
 * ---------------------------------------------------------------------------------------------- */
$dbHandler->connect();
$result = $dbHandler->selectQuery('SELECT * FROM drug');
$dbHandler->disconnect();

/* ---------------------------------------------------------------------------------------------- *
 *                                       DISPLAY HEADING OF PAGE                                  *
 * ---------------------------------------------------------------------------------------------- */
$content = <<<_HTML
	<link rel = 'stylesheet' href = '../bootstrap.min.css'>
	<div>
	<h3 style = "color = green;" class = "page-header">List Of Registered Drugs</h3>
	_HTML;

/* ---------------------------------------------------------------------------------------------- *
 *                                        DISPLAY DRUGS IN TABLE                                  *
 * ---------------------------------------------------------------------------------------------- */
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

$content .= <<<_HTML
	</body>
	</table>
	</div>
	_HTML;

/* ---------------------------------------------------------------------------------------------- *
 *                                       DISPLAY HEADING OF PAGE                                  *
 * ---------------------------------------------------------------------------------------------- */
$title = "List of Registered Drugs";

require_once('../templates/base.php');
?>
