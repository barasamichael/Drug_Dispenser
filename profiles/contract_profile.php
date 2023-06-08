<?php
require_once('../connect.php');

$contractId = $_GET['contractId'];

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
	"WHERE c.contractId = $contractId";

$supplies_query = "SELECT cs.contractSupplyId, cs.dateCreated, cs.lastUpdated, " .
	"SUM(si.costPrice * si.quantity) as costPrice, sum(si.sellingPrice * si.quantity) " .
	"as sellingPrice, (SUM(si.sellingPrice * si.quantity) - SUM(si.costPrice * si.quantity)) " .
	"as profit, cs.paymentComplete FROM contract_supply as cs JOIN supply_item as si USING " .
	"(contractSupplyId) WHERE cs.contractId = $contractId GROUP BY cs.contractSupplyId, " .
	"cs.dateCreated, cs.lastUpdated";

$contract = $databaseHandler->selectQuery($contract_query);
$supplies_items = $databaseHandler->selectQuery($supplies_query);

$databaseHandler->disconnect();

// Retrieve record of contract from results
$contract = $contract[0];

// set page title
$title = "Contract Profile | Contract ID " . $contract['contractId'];

$supplies_table_data = null;
$unique_id = 1;
foreach ($supplies_items as $item) {
	$supplies_table_data .= <<<_HTML
		<tr>
		<td>{$item['contractSupplyId']}</td>
		<td id = "dateCreated{$unique_id}">{$item['dateCreated']}</td>
		<td>{$item['costPrice']}</td>
		<td>{$item['sellingPrice']}</td>
		<td>{$item['profit']}</td>
		<td id = "paymentStatus{$unique_id}">{$item['paymentComplete']}</td>
		<td id = "lastUpdated{$unique_id}">{$item['lastUpdated']}</td>
		</tr>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
		<script>
			var dateCreated = document.getElementById("dateCreated{$unique_id}");
			var lastUpdated = document.getElementById("lastUpdated{$unique_id}");
			var paymentStatus = document.getElementById("paymentStatus{$unique_id}");
		
			dateCreated.innerText = moment(dateCreated.innerText).format(
				'dddd MMMM D, YYYY h:mm A');
			
			lastUpdated.innerText = moment(lastUpdated.innerText).format(
				'dddd MMMM D, YYYY h:mm A');

			if (paymentStatus.innerText == 1)
			{
				paymentStatus.innerText = "Complete";
				paymentStatus.style.color = "green";
			}
			else if (paymentStatus.innerText == 0)
			{
				paymentStatus.innerText = "Pending";
				paymentStatus.style.color = "red";
			}
		</script>
		_HTML;
	$unique_id += 1;
}
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
	
	.items-table {
	font-size: 18px;
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
	<div class = "list-group" style = "margin-top: 3%;">
	<div class = "list-group-item items-table">
	<table class = "table table-responsive table-hover table-striped">
	<thead class = "thead">
	<tr>
	<th>Supply ID</th>
	<th>Date Created</th>
	<th>Cost Price</th>
	<th>Selling Price</th>
	<th>Profit</th>
	<th>Payment</th>
	<th>Last Updated</th>
	</tr>
	</thead>
	<tbody>
	$supplies_table_data
	</tbody>
	</table>
	</div>
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
