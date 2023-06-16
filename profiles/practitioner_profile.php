<?php
/* ********************************************************************************************** *
 *
 * HEADING: practitioner_profile.php: Renders HTML required to display all details for specic 
 *          Practitioner Profile ID        
 * AUTHOR : Barasa Michael Murunga
 * EMAIL  : michael.barasa@strathmore.edu
 * NOTES  : User Sessions: The program utilizes session management as its core functionality, 
 *          allowing for seamless interaction throughout the application.
 *
 *          Efficient Data Retrieval: The program efficiently retrieves essential data from the 
 *          database and dynamically populates the relevant HTML elements. This ensures up-to-date 
 *          information is displayed to users, enhancing the overall user experience.
 *
 *          Access Control: The program incorporates robust access control measures, restricting 
 *          access to authorized individuals such as administrators, patients, pharmacists, and 
 *          pharmaceutical personnel. 
 *          Furthermore, it employs granular access controls to limit specific sections of the 
 *          application, bolstering accountability and security.
 *
 *          Enhanced User Interface: The program leverages the power of CSS3 and JavaScript to 
 *          enhance the visual appearance and interactivity of the application. This results in a 
 *          polished and modern user interface that offers a seamless and engaging user experience.
 *
 * ********************************************************************************************** */
require_once("forms.php");
require_once("views.php");
require_once('../connect.php');
require_once('../config.php');

session_start();
/* ---------------------------------------------------------------------------------------------- *
 *                 ALLOW ACCESS TO ADMINISTRATOR, PHARMACY, PATIENT AND PRACTITIONER              *
 * ---------------------------------------------------------------------------------------------- */
if ($_SESSION['role'] == 'supervisor')
{
	http_response_code(403);
	header("Location: ../templates/errors/403.php");
	exit;
}

/* ---------------------------------------------------------------------------------------------- *
 *                             ENSURE ALL LINK PARAMETERS PROVIDED                                *
 * ---------------------------------------------------------------------------------------------- */
if (!$_GET['practitionerId'])
{
	header("Location: ../templates/errors/invalid_access.php");
	exit;
}
$practitionerId = $_GET['practitionerId'];

/* ---------------------------------------------------------------------------------------------- *
 *                                  PREVENT CROSS PROFILE VIEWS                                   *
 * ---------------------------------------------------------------------------------------------- */
if ($_SESSION['role'] == 'practitioner' && $practitionerId != $_SESSION['practitionerId'])
{
	http_response_code(403);
	header("Location: ../templates/errors/403.php");
	exit;
}
/* ---------------------------------------------------------------------------------------------- *
 *                                      HANDLE ALL POST REQUESTS                                  *
 * ---------------------------------------------------------------------------------------------- */
if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	handlePractitionerPatientAssignmentFormSubmission();
	header("Location: practitioner_profile.php?practitionerId=" . $_SESSION['practitionerId']);
	exit;
}

/* ---------------------------------------------------------------------------------------------- *
 *                            RETRIEVE RELEVANT RECORDS FROM DATABASE                             *
 * ---------------------------------------------------------------------------------------------- */
$databaseHandler = new DatabaseHandler($dsn, $username, $password);
$databaseHandler->connect();

$practitioner_query = "SELECT * FROM practitioner WHERE practitionerId = " .
	"$practitionerId";
$specialty_query = "SELECT s.specialtyId, s.title FROM practitioner as p " .
	"LEFT OUTER JOIN specialty as s USING (specialtyId) " .
	"WHERE practitionerId = $practitionerId";
$patients_query = "SELECT p.patientId, p.firstName, p.middleName, p.lastName, " .
	"p.emailAddress, p.phoneNumber, pp.primaryPractitioner FROM patient_practitioner as pp " .
	"LEFT OUTER JOIN patient AS p USING (patientId) WHERE pp.practitionerId = " .
	"$practitionerId";

$practitioner = $databaseHandler->selectQuery($practitioner_query);
$specialty = $databaseHandler->selectQuery($specialty_query);
$patients = $databaseHandler->selectQuery($patients_query);

$databaseHandler->disconnect();

// Retrieve record of practitioner from results
$practitioner = $practitioner[0];
$specialty = $specialty[0];

/* ---------------------------------------------------------------------------------------------- *
 *                                      SET PAGE TITLE                                            *
 * ---------------------------------------------------------------------------------------------- */
$title = $practitioner['firstName'] . " " . $practitioner['middleName'] . " " .
	$practitioner['lastName'];

/* ---------------------------------------------------------------------------------------------- *
 *                           PRACTITIONER PATIENT ASSIGNMENT FORM                                 *
 * ---------------------------------------------------------------------------------------------- */
ob_start();
renderPractitionerPatientAssignmentForm();
$form = ob_get_clean();

$patient_assignment = <<<_HTML
	<h3 class = "text-muted">New Patient Assignment</h3>
	$form
	_HTML;

/* ---------------------------------------------------------------------------------------------- *
 *                 RECORDS OF ALL PATIENTS ASSIGNED TO FOR CURRENT PRACTITIONER                   *
 * ---------------------------------------------------------------------------------------------- */
$patients_table_data = null;
foreach ($patients as $patient)
{
	$patients_table_data .= <<<_HTML
		<tr>
		<td>
		<a href = "patient_profile.php?patientId={$patient['patientId']}">
		{$patient['patientId']}
		</a>
		</td>
		<td>
		<a href = "patient_profile.php?patientId={$patient['patientId']}">
		{$patient['firstName']} {$patient['middleName']} {$patient['lastName']}</td>
		</a>
		<td>
		<a href = "tel: {$patient['phoneNumber']}">
		{$patient['phoneNumber']}
		</a>
		</td>
		<td>
		<a href = "mailto: {$patient['emailAddress']}">
		{$patient['emailAddress']}</td>
		</a>
		_HTML;

	if ($patient['primaryPractitioner'] == 1)
	{
		$patients_table_data .= <<<_HTML
			<td style = "color : blue;">Primary</td>
			_HTML;
	}
	else
	{
		$patients_table_data .= <<<_HTML
			<td style = "color : brown;">Secondary</td>
			_HTML;
	}
	$patients_table_data .= <<<_HTML
		</tr>
		_HTML;
}

$patients_table = <<<_HTML
	<h3 class = "text-muted">Assigned Patients</h3>
	<div class = "list-group">
	<div class = "list-group-item">
	<table class = "table table-hover table-striped table-responsive">
	<thead class = "thead">
	<tr>
	<th>Patient Id</th>
	<th>Patient Name</th>
	<th>Phone Number</th>
	<th>Email Address</th>
	<th>Priority</th>
	</tr>
	</thead>
	<tbody>
	$patients_table_data
	</tbody>
	</table>
	</div>
	</div>
	_HTML;

/* ---------------------------------------------------------------------------------------------- *
 *                  FILTER CONTENT VIEWED BY USER BASED ON CURRENT USER ROLE                      *
 * ---------------------------------------------------------------------------------------------- */
$main_area = null;
if ($_SESSION['role'] == 'administrator' || $_SESSION['role'] == 'practitioner')
{
	$main_area .= $patient_assignment;
	$main_area .= $patients_table;
}

/* ---------------------------------------------------------------------------------------------- *
 *                          ACTUAL HTML CONTENT TO BE SENT TO BASE.PHP                            *
 * ---------------------------------------------------------------------------------------------- */
$content = <<<_HTML
	<!---------------------------------- CSS STYLESHEETS -------------------------------------->
	<link href = "../bootstrap.min.css" rel = "stylesheet">
	<link href = "static/css/practitioner_profile.css" rel = "stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<!--------------------------------------- MOMENT.JS---------------------------------------->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js">
	</script>
	<!---------------------------- UPPER SIDEBAR PROFILE DETAILS ------------------------------>
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

	<!------------------------------ LOWER SIDEBAR PROFILE DETAILS ---------------------------->
	<div class="card">
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
	<!-------------------------------------- MAIN AREA ---------------------------------------->
	<div class = "col-md-8 col-lg-9" style = "padding:3%;">
	$main_area
	</div>
	</div>
	<!---------------------------------- JAVASCRIPT AND JQUERY -------------------------------->
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
