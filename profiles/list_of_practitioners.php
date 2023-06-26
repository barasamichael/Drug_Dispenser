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
 *                            RETRIEVE PRACTITIONER RECORDS FROM DATABASE                         *
 * ---------------------------------------------------------------------------------------------- */
$dbHandler = new DatabaseHandler($dsn, $username, $password);
$dbHandler->connect();
$result = $dbHandler->selectQuery('SELECT * FROM practitioner');
$dbHandler->disconnect();

/* ---------------------------------------------------------------------------------------------- *
 *                                       DISPLAY HEADING OF PAGE                                  *
 * ---------------------------------------------------------------------------------------------- */
$content = <<<_HTML
	<div>
	<link rel='stylesheet' href='../bootstrap.min.css'>
	<h3 style="color: green;" class="page-header">List Of Practitioners</h3>
	_HTML;

/* ---------------------------------------------------------------------------------------------- *
 *                                  DISPLAY PRACTITIONERS IN TABLE                                *
 * ---------------------------------------------------------------------------------------------- */
$content .= <<<_HTML
	<table class="table table-striped table-responsive table-hover">
	<thead>
	<tr>
	<th>Practitioner ID</th>
	<th>Name</th>
	<th>Email Address</th>
	<th>Phone Number</th>
	<th>Social Security Number</th>
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
		<td>
		<a href="practitioner_profile.php?practitionerId={$row['practitionerId']}">
{$row['practitionerId']}
</a>
</td>
<td>
<a href="practitioner_profile.php?practitionerId={$row['practitionerId']}">
{$row['firstName']} {$row['middleName']} {$row['lastName']}
</a>
</td>
<td>
<a href="mailto: {$row['emailAddress']}">
{$row['emailAddress']}
</a>
</td>
<td>
<a href="tel: {$row['phoneNumber']}">
{$row['phoneNumber']}
</a>
</td>
<td>{$row['SSN']}</td>
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
$title = "List of Practitioners";

require_once('../templates/base.php');
?>
