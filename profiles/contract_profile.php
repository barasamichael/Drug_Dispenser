<?php

require_once('../connect.php');

// database credentials
$dsn = 'mysql:host=localhost; dbname=drugs_db';
$username = 'root';
$password = 'MySQLXXX-123a8910';

// Retrieve contract details and associated from database
$databaseHandler = new DatabaseHandler($dsn, $username, $password);
$databaseHandler->connect();

$contract_query = "SELECT c.contractId, c.startDate, c.endDate, c.pharmaceuticalId, " .
	"c.pharmacyId, pharmacy.title as pharmacy_title, pharmaceutical.title as " .
	"pharmaceutical_title FROM contract as c RIGHT OUTER JOIN pharmaceutical " .
	"USING (pharmaceuticalId) RIGHT OUTER JOIN pharmacy USING (pharmacyId) " .
	"WHERE c.contractId = 2";

$contract = $databaseHandler->selectQuery($contract_query);

$databaseHandler->disconnect();

// Retrieve record of contract from results
$contract = $contract[0];

// set page title
$title = "Contract Profile | Contract ID " . $contract['contractId'];


$content = <<<_HTML
	<link href = "../bootstrap.min.css" rel = "stylesheet">
	<style>
	.btn {
	font-size: 17px;
	}

	.btn-pill {
	border-radius: 50px;
	padding: 10px 20px;
	}

	.list-group {
	font-family: Calibri;
	}
	
	.explanation {
		font-weight: bold;
		color: brown;
	}
	ul.list-unstyled {
	list-style-type: none;
	padding: 0;
	}

	ul.list-unstyled li {
	display: flex;
	align-items: center;
	margin-bottom: 10px;
	}
	
	.explanation {
	font-weight: bold;
	margin-right: 10px;
	min-width: 150px;
	}

	.new-supply a {
	width: 100%;
	background-color: #FF8000;
	border-color: #FF8000;
	}

	.new-supply a:hover {
	background-color: #FF7000;
	}
	</style>
	<div class = "list-group">
	<div class = "list-group-item">
	<div style = "padding-top: 10px; padding-bottom: 10px; padding-left: 30px;">
	<ul class = "list-unstyled lead">
	<li><span class = "explanation">Contract ID:</span> {$contract['contractId']}</li>
	<li>
	<span class = "explanation">Pharmaceutical:</span> {$contract['pharmaceutical_title']}
	</li>
	<li><span class = "explanation">Pharmacy:</span> {$contract['pharmacy_title']}</li>
	<li>
	<span class = "explanation">Start Date:</span>
	<span id = "startDate">{$contract['startDate']}</span>
	</li>
	<li>
	<span class = "explanation">End Date:</span>
	<span id = "endDate">{$contract['endDate']}</span>
	</li>
	<li>
	<span class = "explanation">Period:</span>
	<span id = "period"></span>
	</li>
	</ul>
	<a class = "btn btn-primary btn-pill">Update Contract Details</a>
	</div>
	</div>
	</div>
	<div class = "new-supply">
	<a href = "../registration/register_contract_supply.php" class = "btn btn-primary btn-pill">
	Create New Supply Instance
	</a>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
	<script>
		var startDate = document.getElementById("startDate");
		var endDate = document.getElementById("endDate");
		var period = document.getElementById("period");
		
		period.innerText = moment(endDate.innerText).format('YYYY') - moment(
			startDate.innerText).format('YYYY') + " year(s)";
		startDate.innerText = moment(startDate.innerText).format(
			'dddd MMMM D, YYYY') + ' (' + moment(startDate.innerText).fromNow() + ')';
			
		endDate.innerText = moment(endDate.innerText).format(
			'dddd MMMM D, YYYY') + ' (' + moment(endDate.innerText).fromNow() + ')';
	</script>
	_HTML;

require_once('../templates/base.php')
?>
