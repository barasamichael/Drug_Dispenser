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
	handleRegisterContractFormSubmission();
}

# Set page header
$content = <<<_HTML
	<div class = "page-header">
	<h3>Contract Registration</h3>
	</div>
	_HTML;

# retrieve contract registration form
ob_start();
renderRegisterContractForm();
$content .= ob_get_clean();

# set page title
$title = "Contract Registration";

include "../templates/base.php";
?>
