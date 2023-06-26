<?php
session_start();
require_once "forms.php";
require_once "views.php";

/* ---------------------------------------------------------------------------------------------- *
 *                                    ALLOW ADMINISTRATOR ACCESS                                  *
 * ---------------------------------------------------------------------------------------------- */
if ($_SESSION['role'] !== 'practitioner' && $_SESSION['role'] != 'administrator')
{
	http_response_code(403);
	header("Location: ../templates/errors/403.php");
	exit;
}

if (isset($_GET['practitionerId']))
{
	$practitionerId = $_GET['practitionerId'];
} 

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	handleEditPractitionerProfileFormSubmission($practitionerId);
	header("Location: ../profiles/practitioner_profile.php?practitionerId=$practitionerId");
	exit;
}

# Set page header
$content = <<<_HTML
	<div class = "page-header">
	<h3>Edit Practitioner Profile</h3>
	</div>
	_HTML;

# Retrieve HTML Code for edit practitioner profile form
ob_start();
renderEditPractitionerProfileForm($practitionerId);
$content .= ob_get_clean();

# Set page title
$title = "Edit Practitioner Profile - " . $practitionerId;

include "../templates/base.php";
?>
