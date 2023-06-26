<?php
require_once("../connect.php");
require_once("../config.php");
require_once("pagination.php");

session_start();
/* ---------------------------------------------------------------------------------------------- *
 *                       ONLY LOGGED IN USERS CAN ACCESS THESE CONTENT                            *
 * ---------------------------------------------------------------------------------------------- */
if (!isset($_SESSION['role'])) {
	header("Location: ../authentication/login.php");
	exit;
}

/* ---------------------------------------------------------------------------------------------- *
 *                                      ALLOW ADMINISTRATOR ACCESS                                *
 * ---------------------------------------------------------------------------------------------- */
if ($_SESSION['role'] != 'administrator') {
	http_response_code(403);
	header("Location: ../templates/errors/403.php");
	exit;
}

/* ---------------------------------------------------------------------------------------------- *
 *                                RETRIEVE DRUG RECORDS FROM DATABASE                             *
 * ---------------------------------------------------------------------------------------------- */
$dbHandler = new DatabaseHandler($dsn, $username, $password);
$dbHandler->connect();
$result = $dbHandler->selectQuery('SELECT * FROM drug');
$dbHandler->disconnect();

/* ---------------------------------------------------------------------------------------------- *
 *                                       DISPLAY HEADING OF PAGE                                  *
 * ---------------------------------------------------------------------------------------------- */
$content = <<<_HTML
	<link rel="stylesheet" href="../bootstrap.min.css">
	<div>
	<h3 style="color: green;" class="page-header">List Of Registered Drugs</h3>
	_HTML;

/* ---------------------------------------------------------------------------------------------- *
 *                                        DISPLAY DRUGS IN TABLE                                  *
 * ---------------------------------------------------------------------------------------------- */
$content .= <<<_HTML
	<table class="table table-striped table-responsive table-hover">
	<thead>
	<tr>
	<th>Record ID</th>
	<th>Drug Scientific Name</th>
	<th>Formula</th>
	<th>Form</th>
	</tr>
	</thead>
	<tbody>
	_HTML;

// Pagination Variables
$totalRecords = count($result);
$totalPages = ceil($totalRecords / $perPage);
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($currentPage - 1) * $perPage;
$end = $start + $perPage;
$paginatedResult = array_slice($result, $start, $perPage);

foreach ($paginatedResult as $row) {
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
	</tbody>
	</table>
	_HTML;

// Generate pagination links
$pagination = generatePagination($currentPage, $totalPages, $_SERVER['PHP_SELF']);

/* ---------------------------------------------------------------------------------------------- *
 *                                   DISPLAY PAGINATION LINKS                                     *
 * ---------------------------------------------------------------------------------------------- */
$content .= $pagination;
$content .= '</div>';

/* ---------------------------------------------------------------------------------------------- *
 *                                       DISPLAY HEADING OF PAGE                                  *
 * ---------------------------------------------------------------------------------------------- */
$title = "List of Registered Drugs";

require_once('../templates/base.php');
?>
