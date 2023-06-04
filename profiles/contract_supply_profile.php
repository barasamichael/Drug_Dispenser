<?php

require_once('../connect.php');

// database credentials
$dsn = 'mysql:host=localhost; dbname=drugs_db';
$username = 'root';
$password = 'MySQLXXX-123a8910';

// Retrieve supply details and associated from database
$databaseHandler = new DatabaseHandler($dsn, $username, $password);
$databaseHandler->connect();

$supply_query = "SELECT cs.contractSupplyId, cs.paymentComplete, cs.dateCreated, " .
	"cs.lastUpdated, cs.contractId, c.pharmaceuticalId, " .
	"c.pharmacyId, pharmacy.title as pharmacy_title, pharmaceutical.title as " .
	"pharmaceutical_title FROM contract_supply as cs RIGHT OUTER JOIN " . 
	"contract as c USING (contractId) RIGHT OUTER JOIN pharmaceutical " .
	"USING (pharmaceuticalId) RIGHT OUTER JOIN pharmacy USING (pharmacyId) " .
	"WHERE cs.contractSupplyId = 3";

$supply_items_query = "SELECT si.supplyItemId, si.tradename, si.dateCreated, si.sellingPrice, " .
	"si.costPrice, si.quantity, drug.scientificName FROM supply_item as si RIGHT OUTER JOIN " .
	"drug USING (drugId) WHERE si.contractSupplyId = 3";

$supply = $databaseHandler->selectQuery($supply_query);
$supply_items = $databaseHandler->selectQuery($supply_items_query);

$databaseHandler->disconnect();

// Retrieve record of supply from results
$supply = $supply[0];

// set page title
$title = "Supply Profile | Supply ID " . $supply['contractSupplyId'];

$supply_items_table_data = null;
foreach ($supply_items as $item) {
	$supply_items_table_data .= <<<_HTML
		<tr>
		<td>{$item['supplyItemId']}</td>
		<td class = "dateCreated">{$item['dateCreated']}</td>
		<td>{$item['scientificName']}</td>
		<td>{$item['tradename']}</td>
		<td>{$item['quantity']}</td>
		<td>{$item['costPrice']}</td>
		<td>{$item['sellingPrice']}</td>
		</tr>
		_HTML;
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

	.new-supply {
	}

	.new-supply a {
	width: 100%;
	background-color: #FF8000;
	border-color: #FF8000;
	}

	.new-supply a:hover {
	background-color: #FF7000;
	}
	
	.new-supply a {
	width: 100%;
	background-color: #FF8000;
	border-color: #FF8000;
	}

	.new-supply a:hover {
	background-color: #FF7000;
	border-color: #FF8000;
	}

	.items-table {
	font-size: 18px;
	}
	</style>
	<div class = "list-group">
	<div class = "list-group-item">
	<div style = "padding-top: 10px; padding-bottom: 10px; padding-left: 30px;">
	<ul class = "list-unstyled lead">
	<li><span class = "explanation">Supply ID</span> {$supply['contractSupplyId']}</li>
	<li><span class = "explanation">Contract ID</span> {$supply['contractId']}</li>
	<li>
	<span class = "explanation">Pharmaceutical</span> {$supply['pharmaceutical_title']}
	</li>
	<li><span class = "explanation">Pharmacy</span> {$supply['pharmacy_title']}</li>
	<li>
	<span class = "explanation">Payment Status</span>
	<span id = "paymentStatus">{$supply['paymentComplete']}</span>
	</li>
	<li>
	<span class = "explanation">Date Created</span>
	<span id = "dateCreated">{$supply['dateCreated']}</span>
	</li>
	<li>
	<span class = "explanation">Last Updated</span>
	<span id = "lastUpdated">{$supply['lastUpdated']}</span>
	</li>
	</ul>
	<a href = "confirm_supply_payment.php" class = "btn btn-success btn-pill" id = "confirmPayment">
	Confirm Completion of Payment
	</a>
	</div>
	</div>
	</div>
	<div class = "new-supply">
	<a href = "../registration/register_supply_item.php" class = "btn btn-primary btn-pill">
	Add Supply Items to Cart
	</a>
	</div>
	<div class = "list-group" style = "margin-top: 3%;">
	<div class = "list-group-item items-table">
	<table class = "table table-responsive table-hover table-striped">
	<thead class = "thead">
	<tr>
	<th>Item ID</th>
	<th>Date</th>
	<th>Scientific Name</th>
	<th>Trade Name</th>
	<th>Quantity</th>
	<th>Cost Price</th>
	<th>Selling Price</th>
	</tr>
	</thead>
	<tbody>
	$supply_items_table_data
	</tbody>
	</table>
	</div>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
	<script>
		var dateCreated = document.getElementById("dateCreated");
		var lastUpdated = document.getElementById("lastUpdated");
		var paymentStatus = document.getElementById("paymentStatus");
		var confirmPayment = document.getElementById("confirmPayment");
		
		dateCreated.innerText = moment(dateCreated.innerText).format(
			'dddd MMMM D, YYYY h:mm A');
			
		lastUpdated.innerText = moment(lastUpdated.innerText).format(
			'dddd MMMM D, YYYY h:mm A');

		if (paymentStatus.innerText == 1)
		{
			paymentStatus.innerText = "Complete";
			paymentStatus.style.color = "green";
			confirmPayment.remove();
		}
		else if (paymentStatus.innerText == 0)
		{
			paymentStatus.innerText = "Pending";
			paymentStatus.style.color = "red";
		}

	</script>
	_HTML;

require_once('../templates/base.php')
?>
