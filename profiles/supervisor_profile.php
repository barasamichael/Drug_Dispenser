<?php
/* ********************************************************************************************** *
 *
 * HEADING: supervisor_profile.php: Renders HTML required to display all details for specic 
 *          Supervisor Profile ID        
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
 *          access to authorized individuals such as administrators, supervisors, pharmacists, and 
 *          pharmaceutical personnel. 
 *          Furthermore, it employs granular access controls to limit specific sections of the 
 *          application, bolstering accountability and security.
 *
 *          Enhanced User Interface: The program leverages the power of CSS3 and JavaScript to 
 *          enhance the visual appearance and interactivity of the application. This results in a 
 *          polished and modern user interface that offers a seamless and engaging user experience.
 *
 * ********************************************************************************************** */
require_once('../config.php');
require_once("forms.php");
require_once("views.php");
require_once('../connect.php');

session_start();
/* ---------------------------------------------------------------------------------------------- *
 *            ALLOW ACCESS TO ADMINISTRATOR, SUPERVISOR, PHARMACY AND PHARMACEUTICAL              *
 * ---------------------------------------------------------------------------------------------- */
if ($_SESSION['role'] == 'patient' || $_SESSION['role'] == 'practitioner')
{
	http_response_code(403);
	header("Location: ../templates/errors/403.php");
	exit;
}
/* ---------------------------------------------------------------------------------------------- *
 *                             ENSURE ALL LINK PARAMETERS PROVIDED                                *
 * ---------------------------------------------------------------------------------------------- */
if (!$_GET['supervisorId'])
{
	header("Location: ../templates/errors/invalid_access.php");
	exit;
}
$supervisorId = $_GET['supervisorId'];

/* ---------------------------------------------------------------------------------------------- *
 *                                  PREVENT CROSS PROFILE VIEWS                                   *
 * ---------------------------------------------------------------------------------------------- */
if ($_SESSION['role'] == 'supervisor' && $supervisorId != $_SESSION['supervisorId'])
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
	handleContractSupervisorAssignmentFormSubmission();
	header("Location: supervisor_profile.php?supervisorId=" . $_SESSION['supervisorId']);
	exit;
}

/* ---------------------------------------------------------------------------------------------- *
 *                            RETRIEVE RELEVANT RECORDS FROM DATABASE                             *
 * ---------------------------------------------------------------------------------------------- */
$databaseHandler = new DatabaseHandler($dsn, $username, $password);
$databaseHandler->connect();

$supervisor_query = "SELECT * FROM supervisor WHERE supervisorId = $supervisorId";
$contracts_query = "SELECT cs.contractSupervisorId, cs.active, cs.dateCreated, cs.contractId, c.pharmaceuticalId, pharmaceutical.title as pharmaceutical_title, c.pharmacyId, pharmacy.title as pharmacy_title FROM contract_supervisor as cs RIGHT OUTER JOIN contract as c USING (contractId) RIGHT OUTER JOIN pharmaceutical USING (pharmaceuticalId) RIGHT OUTER JOIN pharmacy USING (pharmacyId) WHERE supervisorId = $supervisorId";

$supervisor = $databaseHandler->selectQuery($supervisor_query);
$contracts = $databaseHandler->selectQuery($contracts_query);

$databaseHandler->disconnect();

// Retrieve record of supervisor from results
$supervisor = $supervisor[0];

/* ---------------------------------------------------------------------------------------------- *
 *                                      SET PAGE TITLE                                            *
 * ---------------------------------------------------------------------------------------------- */
$title = $supervisor['firstName'] . " " . $supervisor['middleName'] . " " . $supervisor['lastName'];

/* ---------------------------------------------------------------------------------------------- *
 *                            CONTRACT SUPERVISOR ASSIGNMENT FORM                                 *
 * ---------------------------------------------------------------------------------------------- */
ob_start();
renderContractSupervisorAssignmentForm();
$form = ob_get_clean();

$contract_assignment = <<<_HTML
	<h3 class = "text-muted">New Contract Assignment</h3>
	$form
	_HTML;

/* ---------------------------------------------------------------------------------------------- *
 *                 RECORDS OF ALL PATIENTS ASSIGNED TO FOR CURRENT PRACTITIONER                   *
 * ---------------------------------------------------------------------------------------------- */
$unique_id = 1;
$contracts_table_data = null;
foreach ($contracts as $contract)
{
	$contracts_table_data .= <<<_HTML
		<tr>
		<td>{$contract['contractSupervisorId']}</td>
		<td id = "dateCreated-{$unique_id}">
		{$contract['dateCreated']}
		</td>
		<td>
		<a href = "contract_profile.php?contractId={$contract['contractId']}">
		{$contract['contractId']}
		</a>
		</td>
		<td>
		<a href = "pharmacy_profile.php?pharmacyId={$contract['pharmacyId']}">
		{$contract['pharmacy_title']}
		</a>
		</td>
		<td>
		<a href = "pharmaceutical_profile.php?pharmaceuticalId={$contract['pharmaceuticalId']}">
		{$contract['pharmaceutical_title']}
		</a>
		</td>
		<td id = "activeStatus-{$unique_id}">{$contract['active']}</td>
		</tr>
		<script>
			var dateCreated = document.getElementById("dateCreated-{$unique_id}");
			dateCreated.innerText = moment(dateCreated.innerText).format('ddd MMM D, YYYY');
			
			// is supervisor active?
			var active = document.getElementById("activeStatus-{$unique_id}");
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
	$unique_id += 1;
}

$contracts_table = <<<_HTML
	<h3 class = "text-muted">Assigned Contracts</h3>
	<div class = "list-group">
	<div class = "list-group-item">
	<table class = "table table-hover table-striped table-responsive">
	<thead class = "thead">
	<tr>
	<th>Id</th>
	<th>Date Created</th>
	<th>Contract Id</th>
	<th>Pharmacy</th>
	<th>Pharmaceutical</th>
	<th>Status</th>
	</tr>
	</thead>
	<tbody>
	$contracts_table_data
	</tbody>
	</table>
	</div>
	</div>
	_HTML;

$main_area = $contract_assignment;
$main_area .= $contracts_table;

/* ---------------------------------------------------------------------------------------------- *
 *                          ACTUAL HTML CONTENT TO BE SENT TO BASE.PHP                            *
 * ---------------------------------------------------------------------------------------------- */
$content = <<<_HTML
	<!---------------------------------- CSS STYLESHEETS -------------------------------------->
	<link href = "../bootstrap.min.css" rel = "stylesheet">
	<link href = "static/css/supervisor_profile.css" rel = "stylesheet">
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
	{$supervisor['firstName']} {$supervisor['middleName']} {$supervisor['lastName']}
	</h3>
	<h4 style = "color: green; font-weight: bold;">Contract Supervisor</h4>
	<p>
	{$supervisor['emailAddress']}<br>{$supervisor['phoneNumber']}
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
	<i class="fas fa-envelope fa-icon"></i>
	<span class = "item-name">Email Address</span>
	<span class = "item-value">{$supervisor['emailAddress']}</span>
	</div>
	<div class="card-item">
	<i class="fas fa-phone fa-icon"></i>
	<span class = "item-name">Contact</span>
	<span class = "item-value">{$supervisor['phoneNumber']}</span>
	</div>
	<div class="card-item">
	<i class="fas fa-toggle-on fa-icon"></i>
	<span class = "item-name">Status</span>
	<span class = "item-value" id = "active">{$supervisor['active']}</span>
	</div>
	<div class="card-item">
	<i class="fas fa-calendar-check fa-icon"></i>
	<span class = "item-name">Date Enrolled</span>
	<span class = "item-value" id = "dateCreated">{$supervisor['dateCreated']}</span>
	</div>
	<div class="card-item">
	<i class="fas fa-clock fa-icon"></i>
	<span class = "item-name">Last Updated</span>
	<span class = "item-value" id = "lastUpdated">{$supervisor['lastUpdated']}</span>
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
		var lastUpdated = document.getElementById("lastUpdated");
		lastUpdated.innerText = moment(lastUpdated.innerText).fromNow();
		
		// format date created
		var dateCreated = document.getElementById("dateCreated");
		dateCreated.innerText = moment(dateCreated.innerText).format('ddd MMM D, YYYY');
		
		// is supervisor active?
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
