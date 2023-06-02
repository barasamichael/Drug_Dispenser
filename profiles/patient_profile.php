<?php

require_once("forms.php");
require_once("views.php");
require_once('../connect.php');

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	handlePatientPractitionerAssignmentFormSubmission();
	header("Location: patient_profile.php");
	exit;
}

// database credentials
$dsn = 'mysql:host=localhost; dbname=drugs_db';
$username = 'root';
$password = 'MySQLXXX-123a8910';

// Retrieve patient details and associated from database
$databaseHandler = new DatabaseHandler($dsn, $username, $password);
$databaseHandler->connect();

$patient_query = "SELECT * FROM patient WHERE patientId = 1";
$practitioners_query = "SELECT p.practitionerId, p.firstName, p.middleName, p.lastName, " .
	"s.title, pp.primaryPractitioner FROM patient_practitioner as pp " .
	"LEFT OUTER JOIN practitioner AS p USING (practitionerId) " . 
	"LEFT OUTER JOIN specialty AS s USING (specialtyId) " .
	"WHERE pp.patientId = 1";

$patient = $databaseHandler->selectQuery($patient_query);
$practitioners = $databaseHandler->selectQuery($practitioners_query);

$databaseHandler->disconnect();

// Retrieve record of patient from results
$patient = $patient[0];

// set page title
$title = $patient['firstName'] . " " . $patient['middleName'] . " " . $patient['lastName'];

ob_start();
renderPatientPractitionerAssignmentForm();
$form = ob_get_clean();

$practitioner_assignment = <<<_HTML
	<h3 class = "text-muted">New Practitioner Assignment</h3>
	$form
	_HTML;

$practitioners_table_data = null;
foreach ($practitioners as $practitioner)
{
	$practitioners_table_data .= <<<_HTML
		<tr>
		<td>{$practitioner['practitionerId']}</td>
		<td>{$practitioner['firstName']} {$practitioner['middleName']} 
		{$practitioner['lastName']}</td>
		<td>{$practitioner['title']}</td>
		_HTML;

	if ($practitioner['primaryPractitioner'] == 1)
	{
		$practitioners_table_data .= <<<_HTML
			<td style = "color : blue;">Primary</td>
			_HTML;
	}
	else
	{
		$practitioners_table_data .= <<<_HTML
			<td style = "color : brown;">Secondary</td>
			_HTML;
	}
	$practitioners_table_data .= <<<_HTML
		</tr>
		_HTML;
}

$practitioners_table = <<<_HTML
	<h3 class = "text-muted">Assigned Practitioners</h3>
	<div class = "list-group">
	<div class = "list-group-item">
	<table class = "table table-hover table-striped table-responsive">
	<thead class = "thead">
	<tr>
	<th>Practitioner Id</th>
	<th>Name</th>
	<th>Specialty</th>
	<th>Priority</th>
	</tr>
	</thead>
	<tbody>
	$practitioners_table_data
	</tbody>
	</table>
	</div>
	</div>
	_HTML;

$main_area = $practitioners_table;
$main_area .= $practitioner_assignment;

$content = <<<_HTML
	<style>
	.personal-info {
	text-align: center;
	}

	.personal-info p {
	font-family: 'Calibri light';
	font-size: 20px;
	}

	.personal-info-header {
	padding:3% 0;
	margin: 4% 0;
	}

	.detail {
	justify: right;
	}
	</style>
	<link href = "../bootstrap.min.css" rel = "stylesheet">
	<div class = "row">
	<div class = "col-md-4 col-lg-3">
	<img class = "img img-fluid rounded-circle mb-3" src = "../static/male-avatar.png">
	<div class = "personal-info">
	<h3>
	{$patient['firstName']} {$patient['middleName']} {$patient['lastName']}
	</h3>
	<p>
	{$patient['emailAddress']}<br>{$patient['phoneNumber']}
	</p>
	<a class = "btn btn-primary" href = "#">Edit Profile</a>
	</div>
	<h4 class = "text-center personal-info-header">Personal information</h4>
	<div class = "list-group">
	<div class = "list-group-item">
	<ul class = "list-unstyled">
	<li>Patient ID: <span class = "detail">{$patient['patientId']}</span></li>
	<li>Gender: <span class = "detail">{$patient['gender']}</span></li>
	<li>Age: <span class = "detail">{$patient['dateOfBirth']}</span></li>
	<li>Residence: <span class = "detail">{$patient['residentialAddress']}</span></li>
	<li>Member Since: <span class = "detail">{$patient['dateCreated']}</span></li>
	<li><span class = "detail">{$patient['lastSeen']}</span></li>
	</ul>
	</div>
	</div>
	</div>
	<div class = "col-md-8 col-lg-9" style = "padding:3%;">
	$main_area
	</div>
	</div>
	_HTML;

require_once('../templates/base.php');
?>
