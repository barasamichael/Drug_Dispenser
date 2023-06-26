<?php
session_start();
require_once "forms.php";
require_once "views.php";

/* ---------------------------------------------------------------------------------------------- *
 *                                    ALLOW ADMINISTRATOR ACCESS                                  *
 * ---------------------------------------------------------------------------------------------- */
if ($_SESSION['role'] !== 'supervisor' && $_SESSION['role'] != 'administrator')
{
	http_response_code(403);
	header("Location: ../templates/errors/403.php");
	exit;
}

if (isset($_GET['supervisorId']))
{
	$supervisorId = $_GET['supervisorId'];
} 

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	handleEditSupervisorProfileFormSubmission($supervisorId);
	header("Location: ../profiles/supervisor_profile.php?supervisorId=$supervisorId");
	exit;
}

# Set page header
$content = <<<_HTML
	<div class = "page-header">
	<h3>Edit Supervisor Profile</h3>
	</div>
	_HTML;

# Retrieve HTML Code for edit supervisor profile form
ob_start();
renderEditSupervisorProfileForm($supervisorId);
$content .= ob_get_clean();

# Set page title
$title = "Edit Supervisor Profile - " . $supervisorId;

include "../templates/base.php";
?>
