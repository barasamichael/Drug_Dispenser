<?php
session_start();
require_once "forms.php";
require_once "views.php";

/* ---------------------------------------------------------------------------------------------- *
 *                                    ALLOW ADMINISTRATOR ACCESS                                  *
 * ---------------------------------------------------------------------------------------------- */
if ($_SESSION['role'] !== 'administrator')
{
	http_response_code(403);
	header("Location: ../templates/errors/403.php");
	exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	handleRegisterPatientFormSubmission();
	header("Location: ../login.php");
	exit;
}

# Set page header
$content = <<<_HTML
	<div class = "page-header">
	<h3>Patient Login</h3>
	</div>
	_HTML;

# Retrieve HTML Code for patient registration form
ob_start();
renderRegisterPatientForm();
$content .= ob_get_clean();

# Set page title
$title = "Patient Registration";

include "../templates/base.php";
?>
