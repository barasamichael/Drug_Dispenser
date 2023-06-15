<?php
/* ********************************************************************************************** *
 *
 * HEADING: contract_supply_profile.php: Renders HTML required to display all details for specic 
 *          Supply Profile ID        
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
require_once('../connect.php');
require_once('../config.php');
session_start();

/* ---------------------------------------------------------------------------------------------- *
 *              ALLOW ADMINISTRATOR, PHARMACY, PHARMACEUTICAL AND SUPERVISOR ACCESS               *
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
if (!$_GET['contractSupplyId'])
{
	header("Location: ../templates/errors/invalid_access.php");
	exit;
}
$contractSupplyId = $_GET['contractSupplyId'];

/* ---------------------------------------------------------------------------------------------- *
 *                            RETRIEVE RELEVANT RECORDS FROM DATABASE                             *
 * ---------------------------------------------------------------------------------------------- */
$databaseHandler = new DatabaseHandler($dsn, $username, $password);
$databaseHandler->connect();

$supply_query = "SELECT cs.contractSupplyId, cs.paymentComplete, cs.dateCreated, " .
	"cs.lastUpdated, cs.contractId, c.pharmaceuticalId, " .
	"c.pharmacyId, pharmacy.title as pharmacy_title, pharmaceutical.title as " .
	"pharmaceutical_title FROM contract_supply as cs RIGHT OUTER JOIN " . 
	"contract as c USING (contractId) RIGHT OUTER JOIN pharmaceutical " .
	"USING (pharmaceuticalId) RIGHT OUTER JOIN pharmacy USING (pharmacyId) " .
	"WHERE cs.contractSupplyId = $contractSupplyId";

$supply_items_query = "SELECT si.supplyItemId, si.tradename, si.dateCreated, si.sellingPrice, " .
	"si.costPrice, si.quantity, drug.scientificName FROM supply_item as si RIGHT OUTER JOIN " .
	"drug USING (drugId) WHERE si.contractSupplyId = $contractSupplyId";

$supply = $databaseHandler->selectQuery($supply_query);
$supply_items = $databaseHandler->selectQuery($supply_items_query);

$databaseHandler->disconnect();

// Retrieve record of supply from results
$supply = $supply[0];

/* ---------------------------------------------------------------------------------------------- *
 *                                      SET PAGE TITLE                                            *
 * ---------------------------------------------------------------------------------------------- */
$title = "Supply Profile | Supply ID " . $supply['contractSupplyId'];

/* ---------------------------------------------------------------------------------------------- *
 *                  RECORDS OF ALL SUPPLY ITEMS FOR CURRENT SUPPLY INSTANCE                       *
 * ---------------------------------------------------------------------------------------------- */
$unique_id = 1;
$supply_items_table_data = null;
foreach ($supply_items as $item) {
	$supply_items_table_data .= <<<_HTML
		<tr>
		<td>{$item['supplyItemId']}</td>
		<td id = "dateCreated{$unique_id}">{$item['dateCreated']}</td>
		<td>{$item['scientificName']}</td>
		<td>{$item['tradename']}</td>
		<td>{$item['quantity']}</td>
		<td>{$item['costPrice']}</td>
		<td>{$item['sellingPrice']}</td>
		</tr>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js">
		</script>
		<script>
			var dateCreated = document.getElementById("dateCreated{$unique_id}");
			dateCreated.innerText = moment(dateCreated.innerText).format(
				'dddd MMMM D, YYYY h:mm A');
		</script>
		_HTML;
	$unique_id += 1;
}
	
/* ---------------------------------------------------------------------------------------------- *
 *                          ACTUAL HTML CONTENT TO BE SENT TO BASE.PHP                            *
 * ---------------------------------------------------------------------------------------------- */
$content = <<<_HTML
	<!---------------------------------- CSS STYLESHEETS -------------------------------------->
	<link href = "../bootstrap.min.css" rel = "stylesheet">
	<link href = "static/css/contract_supply_profile.css" rel = "stylesheet">
	<!------------------------------------ SUPPLY DETAILS ------------------------------------->
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
	<!------------------------------ LINK TO ADD NEW SUPPLY ITEM ------------------------------>
	<div class = "new-supply">
	<a href = "../registration/register_supply_item.php" class = "btn btn-primary btn-pill">
	Add Supply Items to Cart
	</a>
	</div>
	<!---------------------------------- SUPPLY ITEMS TABLE ----------------------------------->
	<div class = "list-group" style = "margin-top: 3%;">
	<div class = "list-group-item items-table">
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
	<!---------------------------------- JAVASCRIPT AND JQUERY -------------------------------->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js">
	</script>
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
