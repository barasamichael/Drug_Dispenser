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
	handleRegisterSupervisorFormSubmission();
	header("Location: templates/main/homepage.php");
	exit;
}

# Set page header
$content = <<<_HTML
	<div class = "page-header">
	<h3>Supervisor Registration</h3>
	</div>
	_HTML;

# retrieve supervisor registration form
ob_start();
renderRegisterSupervisorForm();
$content .= ob_get_clean();

# set page title
$title = "Supervisor Registration";

include "../templates/base.php";
?>
