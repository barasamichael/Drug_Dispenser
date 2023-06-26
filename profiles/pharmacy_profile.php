<?php
/* ********************************************************************************************** *
 *
 * HEADING: pharmacy_profile.php: Renders HTML required to display all details for specic 
 *          Pharmacy Profile ID        
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
 *                       ONLY LOGGED IN USERS CAN ACCESS THESE CONTENT                            *
 * ---------------------------------------------------------------------------------------------- */
if (!isset($_SESSION['role']))
{
	header("Location: ../authentication/login.php");
	exit;
}

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
if (!$_GET['pharmacyId'])
{
	header("Location: ../templates/errors/invalid_access.php");
	exit;
}
$pharmacyId = $_GET['pharmacyId'];

/* ---------------------------------------------------------------------------------------------- *
 *                                  PREVENT CROSS PROFILE VIEWS                                   *
 * ---------------------------------------------------------------------------------------------- */
if ($_SESSION['role'] == 'pharmacy' && $pharmacyId != $_SESSION['pharmacyId'])
{
	http_response_code(403);
	header("Location: ../templates/errors/403.php");
	exit;
}
/* ---------------------------------------------------------------------------------------------- *
 *                            RETRIEVE RELEVANT RECORDS FROM DATABASE                             *
 * ---------------------------------------------------------------------------------------------- */
$databaseHandler = new DatabaseHandler($dsn, $username, $password);
$databaseHandler->connect();
$pharmacy_query = "SELECT * FROM pharmacy WHERE pharmacyId = $pharmacyId";
$contracts_query = "SELECT c.contractId, c.startDate, c.endDate, c.pharmaceuticalId, p.title, " .
	"p.emailAddress, p.locationAddress, p.phoneNumber FROM contract as c " .
	"RIGHT OUTER JOIN pharmaceutical as p USING (pharmaceuticalId) " .
	"WHERE c.pharmacyId = $pharmacyId";

$pharmacy = $databaseHandler->selectQuery($pharmacy_query);
$contracts = $databaseHandler->selectQuery($contracts_query);

$databaseHandler->disconnect();

// Retrieve record of pharmacy from results
$pharmacy = $pharmacy[0];

/* ---------------------------------------------------------------------------------------------- *
 *                                      SET PAGE TITLE                                            *
 * ---------------------------------------------------------------------------------------------- */
$title = $pharmacy['title'];

/* ---------------------------------------------------------------------------------------------- *
 *                              SET HEADER INFORMATION ON SIDEBAR                                 *
 * ---------------------------------------------------------------------------------------------- */
$company_image = <<<_HTML
	<div class="image-container">
	<div class="semi-circle"></div>
	<img src = "https://bsmedia.business-standard.com/_media/bs/img/article/2019-12/21/full/1576948962-751.jpg" alt = "pharmacy image">
	<div class="overlay">
	<div class="overlay-text">
	<p>Call Us: {$pharmacy['phoneNumber']}</p>
	</div>
	<div class="overlay-text">
	<p>Email Us: {$pharmacy['emailAddress']}</p>
	</div>
	<div class="overlay-text">
	<p>{$pharmacy['locationAddress']}</p>
	</div>
	</div>
	</div>
	_HTML;

/* ---------------------------------------------------------------------------------------------- *
 *                        SET PHARMACEUTICAL INFORMATION CARD ON MAIN AREA                        *
 * ---------------------------------------------------------------------------------------------- */
$locationAddress = urlencode($pharmacy['locationAddress']);
$googleSearchUrl = "https://www.google.com/maps/search/?api=1&query={$locationAddress}";
$company_information = <<<_HTML
	<!-- comprehensive information -->
	<div class="card" style = "margin: 0;">
	<div class="card-header" style = "padding-left:50px;">
	<h3 class = "text-center">{$pharmacy['title']}</h3>
	</div>
	<div class="card-body">
	<div class="card-item">
	<i class="fas fa-building fa-icon"></i>
	<span class = "item-name">Location Address</span>
	<span class = "item-value">
	<a href = "{$googleSearchUrl}" target = "_blank">
	{$pharmacy['locationAddress']}
	</a>
	</span>
	</div>
	<div class="card-item">
	<i class="fas fa-envelope fa-icon"></i>
	<span class = "item-name">Email Address</span>
	<span class = "item-value">
	<a href = "mailto: {$pharmacy['emailAddress']}">
	{$pharmacy['emailAddress']}
	</a>
	</span>
	</div>
	<div class="card-item">
	<i class="fas fa-phone fa-icon"></i>
	<span class = "item-name">Contact</span>
	<span class = "item-value">
	<a href = "tel: {$pharmacy['phoneNumber']}">
	{$pharmacy['phoneNumber']}
	</a>
	</span>
	</div>
	<div class="card-item">
	<i class="fas fa-toggle-on fa-icon"></i>
	<span class = "item-name">Status</span>
	<span class = "item-value" id = "active">{$pharmacy['active']}</span>
	</div>
	<div class="card-item">
	<i class="fas fa-calendar-check fa-icon"></i>
	<span class = "item-name">Date Registered</span>
	<span class = "item-value" id = "dateCreated">{$pharmacy['dateCreated']}</span>
	</div>
	</div>
	</div>
	_HTML;

/* ---------------------------------------------------------------------------------------------- *
 *                                  RECORDS OF ASSIGNED CONTRACTS                                 *
 * ---------------------------------------------------------------------------------------------- */
$contracts_section = <<<_HTML
	<style>
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
		<li><span class = "explanation">Pharmaceutical:</span> {$contract['title']}</li>
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

/* ---------------------------------------------------------------------------------------------- *
 *             ONLY THE ADMINISTRATOR AND PHARMACEUTICAL CAN VIEW OPTIONAL SECTION                *
 * ---------------------------------------------------------------------------------------------- */
$optional_section = <<<_HTML
	<div class = "update-info">
	<a href = "" class = "btn btn-update btn-profile-image">Update Profile Image</a>
	<a href = "../registration/edit_pharmacy_profile.php?pharmacyId={$pharmacyId}" class = "btn btn-update btn-profile-info">Update Profile Information</a>
	</div>
	<div>
	<a style = "margin-top: 3%; width: 98%;" href = "list_of_prescriptions.php?pharmacyId={$pharmacyId}" class = "btn btn-primary btn-pill btn-large">
	View Assigned Prescriptions
	</a>
	</div>
	<!---------------------------------- ASSIGNED CONTRACTS ----------------------------------->
	<div>
	$contracts_section
	</div>
	_HTML;

if ($_SESSION['role'] != 'pharmacy' && $_SESSION['role'] != 'administrator')
{
	$optional_section = null;
}

/* ---------------------------------------------------------------------------------------------- *
 *                          ACTUAL HTML CONTENT TO BE SENT TO BASE.PHP                            *
 * ---------------------------------------------------------------------------------------------- */
$content = <<<_HTML
	<!---------------------------------- CSS STYLESHEETS -------------------------------------->
	<link href = "../bootstrap.min.css" rel = "stylesheet">
	<link href = "static/css/pharmacy_profile.css" rel = "stylesheet">
	<!--------------------------------- PHARMACEUTICAL DETAILS -------------------------------->
	<div class = "row">
	<div class = "col-md-5 col-lg-5">
	$company_image
	</div>
	<div class = "col-md-7 col-lg-7" style = "padding:3%;">
	$company_information
	</div>
	</div>
	$optional_section
	<!---------------------------------- JAVASCRIPT AND JQUERY -------------------------------->
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
