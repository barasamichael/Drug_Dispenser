<?php

require_once("forms.php");
require_once("views.php");
require_once('../connect.php');

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	//handlePractitionerPractitionerAssignmentFormSubmission();
	header("Location: practitioner_profile.php");
	exit;
}

// database credentials
$dsn = 'mysql:host=localhost; dbname=drugs_db';
$username = 'root';
$password = 'MySQLXXX-123a8910';

// Retrieve practitioner details and associated from database
$databaseHandler = new DatabaseHandler($dsn, $username, $password);
$databaseHandler->connect();

$practitioner_query = "SELECT * FROM practitioner WHERE practitionerId = 1";
$specialty_query = "SELECT s.specialtyId, s.title FROM practitioner as p " .
	"LEFT OUTER JOIN specialty as s USING (specialtyId) " .
	"WHERE practitionerId = 1";

$practitioner = $databaseHandler->selectQuery($practitioner_query);
$specialty = $databaseHandler->selectQuery($specialty_query);

$databaseHandler->disconnect();

// Retrieve record of practitioner from results
$practitioner = $practitioner[0];
$specialty = $specialty[0];

// set page title
$title = $practitioner['firstName'] . " " . $practitioner['middleName'] . " " .
	$practitioner['lastName'];

$content = <<<_HTML
	<link href = "../bootstrap.min.css" rel = "stylesheet">
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

	.card {
	border: none;
	margin: 20px 0;
	border-radius: 10px;
	box-shadow: 0 5px 7px rgba(0, 0, 0, 0.2);
	}

	.card-header {
	background-color: #FF8000;
	color: #fff;
	padding: 10px;
	border-top-left-radius: 10px;
	border-top-right-radius: 10px;
	}

	.card-body {
	padding: 15px;
	}

	.card-title {
	margin-bottom: 10px;
	font-weight: bold;
	font-size: 18px;
	}

	.card-text {
	margin-bottom: 5px;
	}

	.fa-icon {
	margin-right: 5px;
	}

	.card-item {
	display: flex;
	align-items: center;
	margin-bottom: 10px;
	color: #FF8000;
	color: #000;
	}

	.card-item i {
	margin-right: 10px;
	color: #FF8000;
	}

	.item-name {
	color: #FF8000;
	font-weight: bold;
	}

	.item-value {
	margin-left: auto;
	}
	</style>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js">
	</script>
	<div class = "row">
	<div class = "col-md-4 col-lg-3">
	<img class = "img img-fluid rounded-circle mb-3" src = "../static/male-avatar.png">
	<div class = "personal-info">
	<h3 style = "color: brown; font-weight: bold;">
	{$practitioner['firstName']} {$practitioner['middleName']} {$practitioner['lastName']}
	</h3>
	<h4 style = "color: green; font-weight: bold;">{$specialty['title']}</h5>
	<p>
	{$practitioner['emailAddress']}<br>{$practitioner['phoneNumber']}
	</p>
	<a class = "btn btn-primary" href = "#">Edit Profile</a>
	</div>

	<!-- comprehensive information -->
	<div class="card" style = >
	<div class="card-header" style = "padding-left:50px;">
	<h4>Personal Details</h4>
	</div>
	<div class="card-body">
	<div class="card-item">
	<i class="fas fa-briefcase fa-icon"></i>
	<span class = "item-name">Specialty</span>
	<span class = "item-value">{$specialty['title']}</span>
	</div>
	<div class="card-item">
	<i class="fas fa-envelope fa-icon"></i>
	<span class = "item-name">Email Address</span>
	<span class = "item-value">{$practitioner['emailAddress']}</span>
	</div>
	<div class="card-item">
	<i class="fas fa-phone fa-icon"></i>
	<span class = "item-name">Contact</span>
	<span class = "item-value">{$practitioner['phoneNumber']}</span>
	</div>
	<div class="card-item">
	<i class="fas fa-toggle-on fa-icon"></i>
	<span class = "item-name">Status</span>
	<span class = "item-value" id = "active">{$practitioner['active']}</span>
	</div>
	<div class="card-item">
	<i class="fas fa-genderless fa-icon"></i>
	<span class = "item-name">Gender</span>
	<span class = "item-value">{$practitioner['gender']}</span>
	</div>
	<div class="card-item">
	<i class="fas fa-birthday-cake fa-icon"></i>
	<span class = "item-name">Age</span>
	<span class = "item-value" id = "age">{$practitioner['dateOfBirth']}</span>
	</div>
	<div class="card-item">
	<i class="fas fa-id-card-alt fa-icon"></i>
	<span class = "item-name">Social Security No.</span>
	<span class = "item-value">{$practitioner['SSN']}</span>
	</div>
	<div class="card-item">
	<i class="fas fa-calendar-alt fa-icon"></i>
	<span class = "item-name">Active Years</span>
	<span class = "item-value" id = "activeYear">{$practitioner['activeYear']}</span>
	</div>
	<div class="card-item">
	<i class="fas fa-calendar-check fa-icon"></i>
	<span class = "item-name">Date Enrolled</span>
	<span class = "item-value" id = "dateCreated">{$practitioner['dateCreated']}</span>
	</div>
	<div class="card-item">
	<i class="fas fa-clock fa-icon"></i>
	<span class = "item-name">Last Seen</span>
	<span class = "item-value" id = "lastSeen">{$practitioner['lastSeen']}</span>
	</div>
	</div>
	</div>
	</div>
	<div class = "col-md-8 col-lg-9" style = "padding:3%;">
	$main_area
	</div>
	</div>
	<script>
		// format last seen
		var lastSeen = document.getElementById("lastSeen");
		lastSeen.innerText = moment(lastSeen.innerText).fromNow();
		
		// format date created
		var dateCreated = document.getElementById("dateCreated");
		dateCreated.innerText = moment(dateCreated.innerText).format('ddd MMM D, YYYY');
		
		// calculate active years
		var activeYear = document.getElementById('activeYear');
		activeYear.innerText = (moment().year() - parseInt(activeYear.innerText)) + 
			' year(s)';
		
		// calculate age
		var age = document.getElementById("age");
		age.innerText = (moment().year() - moment(age.innerText).format('YYYY'));
		
		// is practitioner active?
		var active = document.getElementById("active");
		if (active.innerText == 1)
		{
			active.innerText = "Active";
			active.style.color = 'green';
		}
		else
		{
			active.innerText = "Inactive";
			active.style.color = 'red';
		}
	</script>
	_HTML;

	require_once('../templates/base.php');
?>
