<?php
session_start();
require_once "forms.php";
require_once "views.php";

/* ---------------------------------------------------------------------------------------------- *
 *                                    ALLOW ADMINISTRATOR ACCESS                                  *
 * ---------------------------------------------------------------------------------------------- */
if ($_SESSION['role'] !== 'pharmacy' && $_SESSION['role'] != 'administrator')
{
	http_response_code(403);
	header("Location: ../templates/errors/403.php");
	exit;
}

if (isset($_GET['pharmacyId']))
{
	$pharmacyId = $_GET['pharmacyId'];
} 

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	handleEditPharmacyProfileFormSubmission($pharmacyId);
	header("Location: ../profiles/pharmacy_profile.php?pharmacyId=$pharmacyId");
	exit;
}

# Set page header
$content = <<<_HTML
	<div class = "page-header">
	<h3>Edit Pharmacy Profile</h3>
	</div>
	_HTML;

# Retrieve HTML Code for edit pharmacy profile form
ob_start();
renderEditPharmacyProfileForm($pharmacyId);
$content .= ob_get_clean();

# Set page title
$title = "Edit Pharmacy Profile - " . $pharmacyId;

include "../templates/base.php";
?>
