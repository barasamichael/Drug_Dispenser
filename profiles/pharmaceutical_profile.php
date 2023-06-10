<?php

require_once('../connect.php');

session_start();
$pharmaceuticalId = $_GET['pharmaceuticalId'];

// database credentials
$dsn = 'mysql:host=localhost; dbname=drugs_db';
$username = 'root';
$password = 'MySQLXXX-123a8910';

// Retrieve pharmaceutical details and associated from database
$databaseHandler = new DatabaseHandler($dsn, $username, $password);
$databaseHandler->connect();
$pharmaceutical_query = "SELECT * FROM pharmaceutical WHERE pharmaceuticalId = " .
	"$pharmaceuticalId";
$contracts_query = "SELECT c.contractId, c.startDate, c.endDate, c.pharmacyId, p.title, " .
	"p.emailAddress, p.locationAddress, p.phoneNumber FROM contract as c " .
	"RIGHT OUTER JOIN pharmacy as p USING (pharmacyId) " .
	"WHERE c.pharmaceuticalId = $pharmaceuticalId";

$pharmaceutical = $databaseHandler->selectQuery($pharmaceutical_query);
$contracts = $databaseHandler->selectQuery($contracts_query);

$databaseHandler->disconnect();

// Retrieve record of pharmaceutical from results
$pharmaceutical = $pharmaceutical[0];

// set page title
$title = $pharmaceutical['title'];

$company_image = <<<_HTML
	<div class="image-container">
	<div class="semi-circle"></div>
	<img src = "https://bsmedia.business-standard.com/_media/bs/img/article/2019-12/21/full/1576948962-751.jpg" alt = "pharmaceutical image">
	<div class="overlay">
	<div class="overlay-text">
	<p>Call Us: {$pharmaceutical['phoneNumber']}</p>
	</div>
	<div class="overlay-text">
	<p>
	Email Us: {$pharmaceutical['emailAddress']}</p>
	</div>
	<div class="overlay-text">
	<p>{$pharmaceutical['locationAddress']}</p>
	</div>
	</div>
	</div>
	_HTML;

$locationAddress = urlencode($pharmaceutical['locationAddress']);
$googleSearchUrl = "https://www.google.com/maps/search/?api=1&query={$locationAddress}";
$company_information = <<<_HTML
	<!-- comprehensive information -->
	<div class="card" style = "margin: 0;">
	<div class="card-header" style = "padding-left:50px;">
	<h3 class = "text-center">{$pharmaceutical['title']}</h3>
	</div>
	<div class="card-body">
	<div class="card-item">
	<i class="fas fa-building fa-icon"></i>
	<span class = "item-name">Location Address</span>
	<span class = "item-value">
	<a href = "{$googleSearchUrl}" target = "_blank">
	{$pharmaceutical['locationAddress']}
	</a>
	</span>
	</div>
	<div class="card-item">
	<i class="fas fa-envelope fa-icon"></i>
	<span class = "item-name">Email Address</span>
	<span class = "item-value">
	<a href = "mailto: {$pharmaceutical['emailAddress']}">
	{$pharmaceutical['emailAddress']}
	</a>
	</span>
	</div>
	<div class="card-item">
	<i class="fas fa-phone fa-icon"></i>
	<span class = "item-name">Contact</span>
	<span class = "item-value">
	<a href = "tel: {$pharmaceutical['phoneNumber']}">
	{$pharmaceutical['phoneNumber']}
	</a>
	</span>
	</div>
	<div class="card-item">
	<i class="fas fa-toggle-on fa-icon"></i>
	<span class = "item-name">Status</span>
	<span class = "item-value" id = "active">{$pharmaceutical['active']}</span>
	</div>
	<div class="card-item">
	<i class="fas fa-calendar-check fa-icon"></i>
	<span class = "item-name">Date Registered</span>
	<span class = "item-value" id = "dateCreated">{$pharmaceutical['dateCreated']}</span>
	</div>
	</div>
	</div>
	_HTML;

$contracts_section = <<<_HTML
	<style>
	.page-header h3 {
	margin-top: 7%;
	color: brown;
	font-weight: bold;
	}
	.contract {
		margin: 1% 0;
	}

	.contract-item {
		padding: 0;
	}

	.contract-item img {
		border-top-left-radius: 5px;
		border-bottom-left-radius: 5px;
	}

	.explanation {
		font-weight: bold;
	}

	.btn {
	font-size: 17px;
	}
	.btn-pill {
	border-radius: 50px;
	padding: 10px 20px;
	}
	</style>
	<div class = "page-header text-center">
	<h3>List of Assigned Contracts</h3>
	</div>
	_HTML;

$unique_id = 1;
foreach ($contracts as $contract)
{
	$contracts_section .= <<<_HTML
		<div class = "list-group contract" style = "font-family: 'Calibri';">
		<div class = "list-group-item contract-item">
		<div class = "row">
		<div class = "col-md-6 col-lg-6">
		<img src = "https://hwmiia.fra1.digitaloceanspaces.com/wp-content/uploads/2016/11/06125132/haltons-1.jpg" class = "img img-responsive" style = "width: 100%; height: auto;">
		</div>
		<div class = "col-md-6 col-lg-6" style = "padding-top: 10px; padding-bottom: 10px; padding-left: 30px;">
		<ul class = "list-unstyled lead">
		<li><span class = "explanation">Pharmacy:</span> {$contract['title']}</li>
		<li><span class = "explanation">Email Address:</span> {$contract['emailAddress']}</li>
		<li><span class = "explanation">Contact:</span> {$contract['phoneNumber']}</li>
		<li>
		<span class = "explanation">Start Date:</span> 
		<span id = "start-Date-{$unique_id}">{$contract['startDate']}</span>
		</li>
		<li>
		<span class = "explanation">End Date:</span> 
		<span id = "end-Date-{$unique_id}">{$contract['endDate']}</span>
		</li>
		<li>
		<span class = "explanation">Period:</span> 
		<span id = "period-{$unique_id}"></span>
		</li>
		</ul>
		<a href = "contract_profile.php?contractId={$contract['contractId']}" class = "btn btn-primary btn-pill">
		View Details
		</a>
		</div>
		</div>
		</div>
		</div>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
		<script>
			var startDate = document.getElementById("start-Date-$unique_id");
			var endDate = document.getElementById("end-Date-$unique_id");
			var period = document.getElementById("period-$unique_id");
			period.innerText = moment(endDate.innerText).format('YYYY') - moment(
				startDate.innerText).format('YYYY') + " year(s)";

			startDate.innerText = moment(startDate.innerText).format(
			'dddd MMMM D, YYYY') + ' (' + moment(startDate.innerText).fromNow() + ')';
			
			endDate.innerText = moment(endDate.innerText).format(
			'dddd MMMM D, YYYY') + ' (' + moment(endDate.innerText).fromNow() + ')';
		</script>
		_HTML;

	$unique_id += 1;
}

$content = <<<_HTML
	<style>
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
	font-size: 20px;
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

	.image-container {
	position: relative;
	display: inline-block;
	border-radius: 10px;
	overflow: hidden;
	width: 100%;
	margin-top:3%;
	}

	.image-container img {
	display: block;
	max-width: 100%;
	width: 100%;
	height: auto;
	filter: brightness(80%);
	}

	.overlay {
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
	text-align: center;
	}

	.semi-circle {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background: radial-gradient(ellipse at bottom right, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.2) 100%);
	transform: rotate(45deg);
	z-index: 1;
	}

	.overlay-text {
	color: #fff;
	font-size: 24px;
	font-weight: bold;
	text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
	opacity: 0;
	white-space: nowrap;
	overflow: hidden;
	animation: text-animation 12s linear infinite;
	}

	.overlay-text h1 {
	color : #fff;
	}

	.overlay-text:nth-child(1) {
	animation-delay: 0s;
	}

	.overlay-text:nth-child(2) {
	animation-delay: 4s;
	}

	.overlay-text:nth-child(3) {
	animation-delay: 8s;
	}

	@keyframes text-animation {
	0%, 5% {
	opacity: 1;
	width: 0;
	}
	20%, 25% {
	width: 100%;
	}
	95%, 100% {
	opacity: 0;
	width: 100%;
	}
	}

	.update-info {
	padding-right: 20px;
	padding-top: 1%;
	}

	.btn-update {
	background-color: #FF8000;
	color: #fff;
	}

	.btn-profile-image{
	width: 41%;
	}

	.btn-profile-info{
	width: 55%;
	float: right;
	}

	.btn-contract {
	width: 100%;
	margin-top:2%;
	}
	</style>
		<link href = "../bootstrap.min.css" rel = "stylesheet">
		<div class = "row">
		<div class = "col-md-5 col-lg-5">
		$company_image
		</div>
		<div class = "col-md-7 col-lg-7" style = "padding:3%;">
		$company_information
		</div>
		</div>
		<div class = "update-info">
		<a href = "" class = "btn btn-update btn-profile-image">Update Profile Image</a>
		<a href = "" class = "btn btn-update btn-profile-info">Update Profile Information</a>
		<div>
		<a href = "../registration/register_contract.php" class = "btn btn-primary btn-contract btn-large">Create New Contract</a>
		</div>
		</div>
		<div>
		$contracts_section
		</div>
	<script>
		// is pharmaceutical active?
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

		// format date created
		var dateCreated = document.getElementById("dateCreated");
		dateCreated.innerText = moment(dateCreated.innerText).format('dddd MMMM D, YYYY');
	</script>
	_HTML;

require_once('../templates/base.php');
?>
