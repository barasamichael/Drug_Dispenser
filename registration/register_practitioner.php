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
	handleRegisterPractitionerFormSubmission();
	header("Location: register_practitioner.php");
	exit;
}

# Set page header
$content = <<<_HTML
	<div class = "page-header">
	<h3>Practitioner Registration</h3>
	</div>
	_HTML;

# retrieve practitioner registration form
ob_start();
renderRegisterPractitionerForm();
$content .= ob_get_clean();

# set page title
$title = "Practitioner Registration";

include "../templates/base.php";
?>
