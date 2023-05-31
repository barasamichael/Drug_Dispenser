<?php
require_once('../connect.php');

// database credentials
$dsn = 'mysql:host=localhost; dbname=drugs_db';
$username = 'root';
$password = 'MySQLXXX-123a8910';

// Retrieve patient details from database
$databaseHandler = new DatabaseHandler($dsn, $username, $password);
$databaseHandler->connect();
$result = $databaseHandler->selectQuery('SELECT * FROM patient WHERE patientId = 1');
$databaseHandler->disconnect();

// Retrieve record of patient from results
$patient = $result[0];

// set page title
$title = $patient['firstName'] . " " . $patient['middleName'] . " " . $patient['lastName'];

ob_start();
echo "<pre>";
print_r($result);
echo "</pre>";
$main_area = ob_get_clean();

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
	<div class = "col-md-8 col-lg-9">
	$main_area
	</div>
	</div>
	_HTML;

require_once('../templates/base.php');
?>
