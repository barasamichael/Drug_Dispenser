<?php
session_start();
require_once("../connect.php");
require_once("../config.php");
require_once("pagination.php");
echo "<link rel='stylesheet' href='../bootstrap.min.css'>";

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
 *                              RETRIEVE PHARMACY RECORDS FROM DATABASE                           *
 * ---------------------------------------------------------------------------------------------- */
$dbHandler = new DatabaseHandler($dsn, $username, $password);
$dbHandler->connect();
$result = $dbHandler->selectQuery('SELECT * FROM pharmacy');
$dbHandler->disconnect();

/* ---------------------------------------------------------------------------------------------- *
 *                                       DISPLAY HEADING OF PAGE                                  *
 * ---------------------------------------------------------------------------------------------- */
$content = <<<_HTML
	<div>
	<link rel='stylesheet' href='../bootstrap.min.css'>
	<h3 style="color: green;" class="page-header">List Of Pharmacies</h3>
	_HTML;

/* ---------------------------------------------------------------------------------------------- *
 *                                    DISPLAY PHARMACY IN TABLE                                   *
 * ---------------------------------------------------------------------------------------------- */
$content .= <<<_HTML
	<table class="table table-striped table-responsive table-hover">
	<thead>
	<tr>
	<th>Pharmacy ID</th>
	<th>Name</th>
	<th>Email Address</th>
	<th>Phone Number</th>
	<th>Location</th>
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
	$locationAddress = urlencode($row['locationAddress']);
	$googleSearchUrl = "https://www.google.com/maps/search/?api=1&query={$locationAddress}";
	$content .= <<<_HTML
		<tr>
		<td>
		<a href="pharmacy_profile.php?pharmacyId={$row['pharmacyId']}">
	{$row['pharmacyId']}
	</a>
	</td>
	<td>
	<a href="pharmacy_profile.php?pharmacyId={$row['pharmacyId']}">
	{$row['title']}
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
	<td>
	<a href="{$googleSearchUrl}" target="_blank">
	{$row['locationAddress']}
	</a>
	</td>
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
$title = "List of Pharmacies";

require_once('../templates/base.php');
?>
