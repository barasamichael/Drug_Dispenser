<?php
/* ********************************************************************************************** *
 *
 * HEADING: patient_profile.php: Renders HTML required to display all details for specic 
 *          Patient Profile ID        
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
 *          access to authorized individuals such as administrators, pharmacists, patients, and 
 *          practitioners. Only practitioners assigned to the patient can access their details. 
 *          Furthermore, it employs granular access controls to limit specific sections of the 
 *          application, bolstering accountability and security.
 *
 *          Enhanced User Interface: The program leverages the power of CSS3 and JavaScript to 
 *          enhance the visual appearance and interactivity of the application. This results in a 
 *          polished and modern user interface that offers a seamless and engaging user experience.
 *
 *          USER PERMISSIONS
 *          1. Patient
 *          	- View and Modify Profile Details
 *          	- View, Modify and Disable Practitioner Assignments
 *          2. Practitioner
 *          	- View Profile Details
 *          	- View, Assign and Revoke Prescriptions
 *          3. Administrator
 *          	- View and Modify Profile Details
 *          	- View, Modify and Disable Practitioner Assignments
 *          	- View and Revoke Prescriptions
 *          4. Pharmacy
 *          	- View Profile Details
 *          	- View Assigned Prescriptions
 *          	- Confirm Completed Prescription Assignment
 *
 * ********************************************************************************************** */
require_once("forms.php");
require_once("views.php");
require_once('../connect.php');
require_once('../config.php');

session_start();
/* ---------------------------------------------------------------------------------------------- *
 *                       ONLY LOGGED IN USERS CAN ACCESS THESE CONTENT                            *
 * ---------------------------------------------------------------------------------------------- */
if (!isset($_SESSION['role']))
{
	header("Location: ../authentication/login.php");
	exit;
}


/* ---------------------------------------------------------------------------------------------- *
 *             ALLOW ADMINISTRATOR, PHARMACY, PRACTITIONER AND PATIENT ACCESS                     *
 * ---------------------------------------------------------------------------------------------- */
if ($_SESSION['role'] == 'pharmaceutical' || $_SESSION['role'] == 'supervisor')
{
	http_response_code(403);
	header("Location: ../templates/errors/403.php");
	exit;
}

/* ---------------------------------------------------------------------------------------------- *
 *                             ENSURE ALL LINK PARAMETERS PROVIDED                                *
 * ---------------------------------------------------------------------------------------------- */
if (!$_GET['patientId'])
{
	header("Location: ../templates/errors/invalid_access.php");
	exit;
}
$patientId = $_GET['patientId'];

/* ---------------------------------------------------------------------------------------------- *
 *                                  PREVENT CROSS PROFILE VIEWS                                   *
 * ---------------------------------------------------------------------------------------------- */
$databaseHandler = new DatabaseHandler($dsn, $username, $password);
$databaseHandler->connect();

if ($_SESSION['role'] == 'patient' && $patientId != $_SESSION['patientId'])
{
	http_response_code(403);
	header("Location: ../templates/errors/403.php");
	exit;
}
else if ($_SESSION['role'] == 'pharmacy')
{
	$query = "SELECT pharmacy.pharmacyId, patient.patientId FROM prescription RIGHT OUTER " .
		"JOIN supply_item as si USING (supplyItemId) RIGHT OUTER JOIN contract_supply " .
		"USING (contractSupplyId) RIGHT OUTER JOIN contract USING (contractId) RIGHT " .
		"OUTER JOIN pharmacy USING (pharmacyId) RIGHT OUTER JOIN patient_practitioner " .
		"USING (patientPractitionerId) RIGHT OUTER JOIN patient USING " .
		"(patientId) WHERE patient.patientId = :patientId AND pharmacy.pharmacyId = " .
		":pharmacyId ORDER BY prescription.dateCreated DESC LIMIT 1";

	$result = $databaseHandler->selectQuery($query, ["patientId" => $patientId, 
		"pharmacyId" => $_SESSION['pharmacyId']]);
	if(!$result[0])
	{
		/* This pharmacy has no prescriptions with its drugs assigned to them */
		http_response_code(403);
		header("Location: ../templates/errors/403.php");
		exit;
	}
}
else if ($_SESSION['role'] == 'practitioner')
{
	$query = "SELECT patientPractitionerId FROM patient_practitioner WHERE patientId" .
		" = :patientId AND practitionerId = :practitionerId ORDER BY dateCreated" .
		" LIMIT 1";	
	$result = $databaseHandler->selectQuery($query, ["patientId" => $patientId, 
		"practitionerId" => $_SESSION['practitionerId']]);

	/* prevent practitioner from accessing records of a patient they are not assigned to */
	if (count($result) == 0)
	{
		http_response_code(403);
		header("Location: ../templates/errors/403.php");
		exit;
	}
	$patientPractitionerId = $result[0]['patientPractitionerId'];
}
$databaseHandler->disconnect();

/* ---------------------------------------------------------------------------------------------- *
 *                                      HANDLE ALL POST REQUESTS                                  *
 * ---------------------------------------------------------------------------------------------- */
if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	if (isset($_POST['practitionerId']))
	{
		/* patientId is not part of  $_SESSION keys if current user is administrator */
		if ($_SESSION['role'] == 'administrator')
		{
			$_SESSION['patientId'] = $patientId;
		}
		
		/* handle actual entry of patient_practitioner record into database */
		handlePatientPractitionerAssignmentFormSubmission();

		/* unset the patientId key value record from $_SESSION array if administrator*/
		if ($_SESSION['role'] == 'administrator')
		{
			unset($_SESSION['patientId']);
		}

		/* refresh patient profile page to reflect commited changes */
		header("Location: patient_profile.php?patientId=$patientId");
		exit;
	}
	else if (isset($_POST['supplyItemId']) && $_SESSION['role'] == 'practitioner')
	{
		/* 
		 * If the practitioner contraint passes here, that means that the practioner was 
		 * extracted before successfully and we have a valid $patientPractitionerId variable
		 */
		handlePrescriptionAssignmentFormSubmission($patientPractitionerId);
		header("Location: patient_profile.php?patientId=$patientId");
		exit;
	}
}

/* ---------------------------------------------------------------------------------------------- *
 *                            RETRIEVE RELEVANT RECORDS FROM DATABASE                             *
 * ---------------------------------------------------------------------------------------------- */
$databaseHandler->connect();

$patient_query = "SELECT * FROM patient WHERE patientId = $patientId";
$practitioners_query = "SELECT p.practitionerId, p.firstName, p.middleName, p.lastName, " .
	"s.title, pp.primaryPractitioner FROM patient_practitioner as pp " .
	"LEFT OUTER JOIN practitioner AS p USING (practitionerId) " . 
	"LEFT OUTER JOIN specialty AS s USING (specialtyId) " .
	"WHERE pp.patientId = $patientId";

$prescriptions_query = "SELECT p.prescriptionId, d.practitionerId, d.firstName, d.middleName, " .
	"d.lastName, p.frequency, p.quantity, p.assigned, p.dateCreated, p.lastUpdated, " .
	"si.tradename, si.supplyItemId FROM prescription as p RIGHT OUTER JOIN " .
	"patient_practitioner USING (patientPractitionerId) RIGHT OUTER JOIN practitioner as d " .
	"USING (practitionerId) RIGHT OUTER JOIN supply_item as si USING (supplyItemId) " .
	"RIGHT OUTER JOIN patient USING (patientId) WHERE patient.patientId = $patientId " .
	"ORDER BY p.dateCreated DESC LIMIT 10";

$patient = $databaseHandler->selectQuery($patient_query);
$practitioners = $databaseHandler->selectQuery($practitioners_query);
$prescriptions = $databaseHandler->selectQuery($prescriptions_query);

$databaseHandler->disconnect();

// Retrieve record of patient from results
$patient = $patient[0];

/* ---------------------------------------------------------------------------------------------- *
 *                                      SET PAGE TITLE                                            *
 * ---------------------------------------------------------------------------------------------- */
$title = $patient['firstName'] . " " . $patient['middleName'] . " " . $patient['lastName'];

/* ---------------------------------------------------------------------------------------------- *
 *                           PATIENT PRACTITIONER ASSIGNMENT FORM                                 *
 * ---------------------------------------------------------------------------------------------- */
ob_start();
renderPatientPractitionerAssignmentForm($patientId);
$form = ob_get_clean();

$practitioner_assignment = <<<_HTML
	<h3 class = "text-muted">New Practitioner Assignment</h3>
	$form
	_HTML;

/* ---------------------------------------------------------------------------------------------- *
 *                                  PRESCRIPTION ASSIGNMENT FORM                                  *
 * ---------------------------------------------------------------------------------------------- */
ob_start();
renderPrescriptionAssignmentForm();
$form = ob_get_clean();

$prescription_assignment = <<<_HTML
	<hr>
	<h3 class = "text-muted" style = "margin-top: 5%;">New Prescription Assignment</h3>
	$form
	_HTML;

/* ---------------------------------------------------------------------------------------------- *
 *                  RECORDS OF ALL SUPPLY ITEMS FOR CURRENT SUPPLY INSTANCE                       *
 * ---------------------------------------------------------------------------------------------- */
$practitioners_table_data = null;
foreach ($practitioners as $practitioner)
{
	$practitioners_table_data .= <<<_HTML
		<tr>
		<td>
		<a href = "practitioner_profile.php?practitionerId={$practitioner['practitionerId']}">
		{$practitioner['practitionerId']}
		</a>
		</td>
		<td>
		<a href = "practitioner_profile.php?practitionerId={$practitioner['practitionerId']}">
		{$practitioner['firstName']} {$practitioner['middleName']} 
		{$practitioner['lastName']}
		</a>
		</td>
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

/* ---------------------------------------------------------------------------------------------- *
 *      PATIENTS AND PRACTITIONERS ARE NOW ALLOWED TO CONFIRM ASSIGNMENT OF PRESCRIPTIONS         *
 *                                                                                                *
 * To facilitate this, we need a flag that indicates the role of the current user. Initially,     *
 * I attempted to use the $_SESSION['role'] value to determine whether the action button should   *
 * be enabled or disabled.                                                                        *
 * However, I encountered a problem where the $role variable was only recognized as 0.            *
 * Upon further investigation, I discovered that JavaScript only recognizes PHP integer variables.*
 * To address this issue, I decided to assign the value 0 to all other allowed users and 1 to     * 
 * the patient role. As a result, patients will see a disabled button while other users will      * 
 * see both enabled and disabled buttons.                                                         *
 * ---------------------------------------------------------------------------------------------- */
$role = 0;
if ($_SESSION['role'] == 'patient' || $_SESSION['role'] == 'practitioner')
{
	$role = 1;
}

/* ---------------------------------------------------------------------------------------------- *
 *                  RECORDS OF ALL SUPPLY ITEMS FOR CURRENT SUPPLY INSTANCE                       *
 * ---------------------------------------------------------------------------------------------- */
$prescriptions_table_data = null;
$unique_id = 1;

foreach ($prescriptions as $prescription)
{
	$prescriptions_table_data .= <<<_HTML
		<tr>
		<td>{$prescription['prescriptionId']}</td>
		<td>
		<a href = "practitioner_profile.php?practitionerId={$prescription['practitionerId']}">
		{$prescription['firstName']} 
		{$prescription['middleName']} 
		{$prescription['lastName']}
		</a>
		</td>
		<td>{$prescription['tradename']}</td>
		<td>{$prescription['quantity']}</td>
		<td>{$prescription['frequency']}</td>
		<td id = "assigned-{$unique_id}">{$prescription['assigned']}</td>
		<td id = "dateCreated-{$unique_id}">
		{$prescription['dateCreated']}
		</td>	
		<td id = "lastUpdated-{$unique_id}">
		{$prescription['lastUpdated']}
		</td>
		<td>
		<a href = "assign_prescription.php?prescriptionId={$prescription['prescriptionId']}" class = "btn btn-success" id = "assign-btn-{$unique_id}">
		Assign
		</a>
		</td>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
		<script>
			var dateCreated = document.getElementById("dateCreated-" + $unique_id);
			var lastUpdated = document.getElementById("lastUpdated-" + $unique_id);
			var assigned = document.getElementById("assigned-" + $unique_id);
			var assign_btn = document.getElementById("assign-btn-" + $unique_id);
		
			dateCreated.innerText = moment(dateCreated.innerText).format(
				'dddd MMMM D, YYYY h:mm A');
			
			lastUpdated.innerText = moment(lastUpdated.innerText).format(
				'dddd MMMM D, YYYY h:mm A');

			if (assigned.innerText == 1)
			{
				assigned.innerText = "Assigned";
				assigned.style.color = "green";
				assign_btn.removeAttribute("href");
				assign_btn.onclick = function(event) {
					event.preventDefault();
				};
				assign_btn.innerText = "Disabled";
				assign_btn.classList.remove("btn-success");
				assign_btn.classList.add("btn-danger");
			}
			else if (assigned.innerText == 0)
			{
				assigned.innerText = "Pending";
				assigned.style.color = "red";

				/* patients see disabled buttons */
				if ($role == 1)
				{
					assign_btn.removeAttribute("href");
					assign_btn.onclick = function(event) {
						event.preventDefault();
					};
					assign_btn.innerText = "Disabled";
					assign_btn.classList.remove("btn-success");
					assign_btn.classList.add("btn-danger");
				}
			}
		</script>
		_HTML;
	$unique_id += 1;
}

/* ***********************************************************************************************
 *    THIS CODE REMEDIES A BUG IN THE QUERY THAT EXTRACTS ALL PRESCRIPTIONS ASSIGNED TO PATIENT
 *                   When there are no prescriptions, this array is returned
 * 	Array ( 
 * 		[0] => Array ( 
 * 			[prescriptionId] => [0] => [practitionerId] => [1] => [firstName] => [2] => 
 * 			[middleName] => [3] => [lastName] => [4] => [frequency] => [5] => 
 * 			[quantity] => [6] => [assigned] => [7] => [dateCreated] => [8] => 
 * 			[lastUpdated] => [9] => [tradename] => [10] => [supplyItemId] => [11] => 
 * 		) 
 * 	)
 * ***********************************************************************************************/
if (count($prescriptions) == 1 && $prescriptions[0]['prescriptionId'] == 0)
{
	$prescriptions_table_data = null;
}

/* ---------------------------------------------------------------------------------------------- *
 *                                    RECORD TABLE HEADERS                                        *
 * ---------------------------------------------------------------------------------------------- */
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

$prescription_table = <<<_HTML
	<h3 class = "text-muted">Assigned Prescriptions</h3>
	<div class = "list-group">
	<div class = "list-group-item">
	<table class = "table table-hover table-striped table-responsive">
	<thead class = "thead">
	<tr>
	<th>Id</th>
	<th>Practitioner</th>
	<th>Drug</th>
	<th>Quantity</th>
	<th>Frequency</th>
	<th>Assigned</th>
	<th>Date</th>
	<th>Last Updated</th>
	<th>Action</th>
	</tr>
	</thead>
	<tbody>
	$prescriptions_table_data
	</tbody>
	</table>
	</div>
	</div>
	_HTML;

/* ---------------------------------------------------------------------------------------------- *
 *                  FILTER CONTENT VIEWED BY USER BASED ON CURRENT USER ROLE                      *
 * ---------------------------------------------------------------------------------------------- */
$main_area = null;
if ($_SESSION['role'] == 'patient' || $_SESSION['role'] == 'administrator')
{
	$main_area .= $practitioners_table;
	$main_area .= $practitioner_assignment;
	
}

if ($_SESSION['role'] == 'practitioner' || $_SESSION['role'] == 'pharmacy' 
	|| $_SESSION['role'] == 'patient' || $_SESSION['role'] == 'administrator')
{
	$main_area .= $prescription_table;
}

if ($_SESSION['role'] == 'practitioner')
{
	$main_area .= $prescription_assignment;
}

/* ---------------------------------------------------------------------------------------------- *
 *                          ACTUAL HTML CONTENT TO BE SENT TO BASE.PHP                            *
 * ---------------------------------------------------------------------------------------------- */
$content = <<<_HTML
	<!---------------------------------- CSS STYLESHEETS -------------------------------------->
	<link href = "../bootstrap.min.css" rel = "stylesheet">
	<link href = "static/css/patient_profile.css" rel = "stylesheet">
	<!---------------------------- UPPER SIDEBAR PATIENT DETAILS ------------------------------>
	<div class = "row">
	<div class = "col-md-4 col-lg-3">
	<img class = "img img-fluid rounded-circle mb-3" src = "../static/male-avatar.png">
	<div class = "personal-info">
	<h3>
	{$patient['firstName']} {$patient['middleName']} {$patient['lastName']}
	</h3>
	<p>
	<a href = "mailto: {$patient['emailAddress']}">
	{$patient['emailAddress']}<br>
	</a>
	<a href = "tel: {$patient['phoneNumber']}">
	{$patient['phoneNumber']}
	</a>
	</p>
	<a class = "btn btn-primary" href = "../registration/edit_patient_profile.php?patientId={$patientId}" id = "editProfile" href = "#">Edit Profile</a>
	</div>
	<!---------------------------- LOWER SIDEBAR PATIENT DETAILS ------------------------------>
	<div class="card" style = "margin-top: 20%;">
	<div class="card-header" style = "padding-left:50px;">
	<h4>Personal Details</h4>
	</div>
	<div class="card-body">
	<div class="card-item">
	<i class="fas fa-envelope fa-icon"></i>
	<span class = "item-name">Email Address</span>
	<span class = "item-value">
	<a href = "mailto: {$patient['emailAddress']}">
	{$patient['emailAddress']}
	</a>
	</span>
	</div>
	<div class="card-item">
	<i class="fas fa-phone fa-icon"></i>
	<span class = "item-name">Contact</span>
	<span class = "item-value">
	<a href = "tel: {$patient['phoneNumber']}">
	{$patient['phoneNumber']}
	</a>
	</span>
	</div>
	<div class="card-item">
	<i class="fas fa-toggle-on fa-icon"></i>
	<span class = "item-name">Status</span>
	<span class = "item-value" id = "active">{$patient['active']}</span>
	</div>
	<div class="card-item">
	<i class="fas fa-birthday-cake fa-icon"></i>
	<span class = "item-name">Age</span>
	<span class = "item-value" id = "age">{$patient['dateOfBirth']}</span>
	</div>
	<div class="card-item">
	<i class="fas fa-id-card-alt fa-icon"></i>
	<span class = "item-name">Social Security No.</span>
	<span class = "item-value">{$patient['SSN']}</span>
	</div>
	<div class="card-item">
	<i class="fas fa-calendar-check fa-icon"></i>
	<span class = "item-name">Date Enrolled</span>
	<span class = "item-value" id = "dateCreated">{$patient['dateCreated']}</span>
	</div>
	<div class="card-item">
	<i class="fas fa-clock fa-icon"></i>
	<span class = "item-name">Last Seen</span>
	<span class = "item-value" id = "lastSeen">{$patient['lastSeen']}</span>
	</div>
	</div>
	</div>
	</div>
	<!--------------------------------------- MAIN AREA --------------------------------------->
	<div class = "col-md-8 col-lg-9" style = "padding:3%;">
	$main_area
	</div>
	</div>
	<!---------------------------------- JAVASCRIPT AND JQUERY -------------------------------->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
	<script>
		// format last seen
		var lastSeen = document.getElementById("lastSeen");
		lastSeen.innerText = moment(lastSeen.innerText).fromNow();
		
		// format date created
		var dateCreated = document.getElementById("dateCreated");
		dateCreated.innerText = moment(dateCreated.innerText).format('ddd MMM D, YYYY');
		
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
