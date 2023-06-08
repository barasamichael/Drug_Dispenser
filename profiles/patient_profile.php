<?php

require_once("forms.php");
require_once("views.php");
require_once('../connect.php');

session_start();
$patientId = $_GET['patientId'];

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	if (isset($_POST['practitionerId']))
	{
		handlePatientPractitionerAssignmentFormSubmission();
		header("Location: patient_profile.php?patientId=$patientId");
		exit;
	}
	else if (isset($_POST['supplyItemId']))
	{
		// database credentials
		$dsn = 'mysql:host=localhost; dbname=drugs_db';
		$username = 'root';
		$password = 'MySQLXXX-123a8910';

		// extract current patient_practitioner item Id
		$databaseHandler = new DatabaseHandler($dsn, $username, $password);
		$query = "SELECT patientPractitionerId FROM patient_practitioner WHERE patientId = :patientId " .
			"AND practitionerId = :practitionerId ORDER BY dateCreated LIMIT 1";	
		$result = $databaseHandler->executeQuery($query, ["patientId" => $patientId, 
			"practitionerId" => $_SESSION['practitionerId']]);
		$databaseHandler->disconnect();

		// handler prescription assignment form submission
		handlePrescriptionAssignmentFormSubmission($result[0]['patientPractitionerId']);
		
		// refresh page
		header("Location: patient_profile.php?patientId=$patientId");
		exit;
	}
}

// database credentials
$dsn = 'mysql:host=localhost; dbname=drugs_db';
$username = 'root';
$password = 'MySQLXXX-123a8910';

// Retrieve patient details and associated from database
$databaseHandler = new DatabaseHandler($dsn, $username, $password);
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

// set page title
$title = $patient['firstName'] . " " . $patient['middleName'] . " " . $patient['lastName'];

// Patient Practitioner Assignment Form
ob_start();
renderPatientPractitionerAssignmentForm();
$form = ob_get_clean();

$practitioner_assignment = <<<_HTML
	<h3 class = "text-muted">New Practitioner Assignment</h3>
	$form
	_HTML;

// Prescription Assignment Form
ob_start();
renderPrescriptionAssignmentForm();
$form = ob_get_clean();

$prescription_assignment = <<<_HTML
	<hr>
	<h3 class = "text-muted" style = "margin-top: 5%;">New Prescription Assignment</h3>
	$form
	_HTML;

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

$prescriptions_table_data = null;
$unique_id = 1;
foreach ($prescriptions as $prescription)
{
	$prescriptions_table_data .= <<<_HTML
		<tr>
		<td>{$prescription['prescriptionId']}</td>
		<td>{$prescription['firstName']} 
		{$prescription['middleName']} {$prescription['lastName']}</td>
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
		<a href = "assign_prescription.php?patientId={$patient['patientId']}" class = "btn btn-success" id = "assign-btn-{$unique_id}">
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
			}	
		</script>
		_HTML;
	$unique_id += 1;
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

$main_area = $practitioners_table;
$main_area .= $practitioner_assignment;
$main_area .= $prescription_table;
$main_area .= $prescription_assignment; 

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
	
	.card {
	border: none;
	margin: 20px 0;
	border-radius: 10px;
	box-shadow: 0 5px 7px rgba(0, 0, 0, 0.2);
	}

	.card-header {
	background-color: brown;
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
	color: brown;
	color: #000;
	}

	.card-item i {
	margin-right: 10px;
	color: brown;
	}

	.item-name {
	color: brown;
	font-weight: bold;
	}

	.item-value {
	margin-left: auto;
	}

	.img {
	display: block;
	margin-left: auto;
	margin-right: auto;
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
	<a href = "mailto: {$patient['emailAddress']}">
	{$patient['emailAddress']}<br>
	</a>
	<a href = "tel: {$patient['phoneNumber']}">
	{$patient['phoneNumber']}
	</a>
	</p>
	<a class = "btn btn-primary" href = "#">Edit Profile</a>
	</div>
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
