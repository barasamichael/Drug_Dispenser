<?php
session_start();
require_once "forms.php";
require_once "views.php";

/* ---------------------------------------------------------------------------------------------- *
 *                                    ALLOW ADMINISTRATOR ACCESS                                  *
 * ---------------------------------------------------------------------------------------------- */
if ($_SESSION['role'] !== 'pharmaceutical' && $_SESSION['role'] != 'administrator')
{
	http_response_code(403);
	header("Location: ../templates/errors/403.php");
	exit;
}

if (isset($_GET['pharmaceuticalId']))
{
	$pharmaceuticalId = $_GET['pharmaceuticalId'];
} 

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	handleEditPharmaceuticalProfileFormSubmission($pharmaceuticalId);
	header("Location: ../profiles/pharmaceutical_profile.php?pharmaceuticalId=$pharmaceuticalId");
	exit;
}

# Set page header
$content = <<<_HTML
	<div class = "page-header">
	<h3>Edit Pharmaceutical Profile</h3>
	</div>
	_HTML;

# Retrieve HTML Code for edit pharmaceutical profile form
ob_start();
renderEditPharmaceuticalProfileForm($pharmaceuticalId);
$content .= ob_get_clean();

# Set page title
$title = "Edit Pharmaceutical Profile - " . $pharmaceuticalId;

include "../templates/base.php";
?>
