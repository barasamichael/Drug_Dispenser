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
 *                            RETRIEVE SPECIALTY RECORDS FROM DATABASE                            *
 * ---------------------------------------------------------------------------------------------- */
$dbHandler = new DatabaseHandler($dsn, $username, $password);
$dbHandler->connect();
$result = $dbHandler->selectQuery('SELECT * FROM specialty');
$dbHandler->disconnect();

/* ---------------------------------------------------------------------------------------------- *
 *                                       DISPLAY HEADING OF PAGE                                  *
 * ---------------------------------------------------------------------------------------------- */
$content = <<<_HTML
	<div>
	<link rel = 'stylesheet' href = '../bootstrap.min.css'>
	<h3 style = "color = green;" class = "page-header">List Of Specialties</h3>
	_HTML;

/* ---------------------------------------------------------------------------------------------- *
 *                                    DISPLAY SPECIALTIES IN TABLE                                *
 * ---------------------------------------------------------------------------------------------- */
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

foreach ($result as $row)
{
	$content .= <<<_HTML
		<tr>
		<td>{$row['title']}</td>
		<td>{$row['description']}</td>
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
$title = "List of Specialties";

require_once('../templates/base.php');
?>
