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
 *                               RETRIEVE PATIENT RECORDS FROM DATABASE                           *
 * ---------------------------------------------------------------------------------------------- */
$dbHandler = new DatabaseHandler($dsn, $username, $password);
$dbHandler->connect();
$result = $dbHandler->selectQuery('SELECT * FROM patient');
$dbHandler->disconnect();

/* ---------------------------------------------------------------------------------------------- *
 *                                       DISPLAY HEADING OF PAGE                                  *
 * ---------------------------------------------------------------------------------------------- */
$content = <<<_HTML
	<div>
	<link rel = 'stylesheet' href = '../bootstrap.min.css'>
	<h3 style = "color = green;" class = "page-header">List Of Patients</h3>
	_HTML;

/* ---------------------------------------------------------------------------------------------- *
 *                                      DISPLAY PATIENTS IN TABLE                                 *
 * ---------------------------------------------------------------------------------------------- */
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
	<tbody>
	_HTML;

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

$content .= <<<_HTML
	</tbody>
	</table>
	</div>
	_HTML;

/* ---------------------------------------------------------------------------------------------- *
 *                                       DISPLAY HEADING OF PAGE                                  *
 * ---------------------------------------------------------------------------------------------- */
$title = "List of patients";

require_once('../templates/base.php');
?>
