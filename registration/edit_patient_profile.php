<?php
session_start();
require_once "forms.php";
require_once "views.php";

/* ---------------------------------------------------------------------------------------------- *
 *                                    ALLOW ADMINISTRATOR ACCESS                                  *
 * ---------------------------------------------------------------------------------------------- */
if ($_SESSION['role'] !== 'patient' && $_SESSION['role'] != 'administrator')
{
	http_response_code(403);
	header("Location: ../templates/errors/403.php");
	exit;
}

if (isset($_GET['patientId']))
{
	$patientId = $_GET['patientId'];
} 

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	handleEditPatientProfileFormSubmission($patientId);
	header("Location: ../profiles/patient_profile.php?patientId=$patientId");
	exit;
}

# Set page header
$content = <<<_HTML
	<div class = "page-header">
	<h3>Edit Patient Profile</h3>
	</div>
	_HTML;

# Retrieve HTML Code for edit patient profile form
ob_start();
renderEditPatientProfileForm($patientId);
$content .= ob_get_clean();

# Set page title
$title = "Edit Patient Profile - " . $patientId;

include "../templates/base.php";
?>
