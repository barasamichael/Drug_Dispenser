<?php

require_once("forms.php");
require_once("views.php");
require_once('../connect.php');

session_start();
$supervisorId = $_GET['supervisorId'];

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	handleContractSupervisorAssignmentFormSubmission();
	header("Location: supervisor_profile.php?supervisorId=" . $_SESSION['supervisorId']);
	exit;
}

// database credentials
$dsn = 'mysql:host=localhost; dbname=drugs_db';
$username = 'root';
$password = 'MySQLXXX-123a8910';

// Retrieve supervisor details and associated from database
$databaseHandler = new DatabaseHandler($dsn, $username, $password);
$databaseHandler->connect();

$supervisor_query = "SELECT * FROM supervisor WHERE supervisorId = $supervisorId";
$contracts_query = "SELECT cs.contractSupervisorId, cs.active, cs.dateCreated, cs.contractId, c.pharmaceuticalId, pharmaceutical.title as pharmaceutical_title, c.pharmacyId, pharmacy.title as pharmacy_title FROM contract_supervisor as cs RIGHT OUTER JOIN contract as c USING (contractId) RIGHT OUTER JOIN pharmaceutical USING (pharmaceuticalId) RIGHT OUTER JOIN pharmacy USING (pharmacyId) WHERE supervisorId = $supervisorId";

$supervisor = $databaseHandler->selectQuery($supervisor_query);
$contracts = $databaseHandler->selectQuery($contracts_query);

$databaseHandler->disconnect();

// Retrieve record of supervisor from results
$supervisor = $supervisor[0];

// set page title
$title = $supervisor['firstName'] . " " . $supervisor['middleName'] . " " . $supervisor['lastName'];

// set contract supervisor assignment form
ob_start();
renderContractSupervisorAssignmentForm();
$form = ob_get_clean();

$contract_assignment = <<<_HTML
	<h3 class = "text-muted">New Contract Assignment</h3>
	$form
	_HTML;

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
	background-color: purple;
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
	color: purple;
	color: #000;
	}

	.card-item i {
	margin-right: 10px;
	color: purple;
	}

	.item-name {
	color: purple;
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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js">
	</script>
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

	<!-- comprehensive information -->
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
	<div class = "col-md-8 col-lg-9" style = "padding:3%;">
	$main_area
	</div>
	</div>
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
