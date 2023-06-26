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
	handleRegisterPharmacyFormSubmission();
	header("Location: register_pharmacy.php");
	exit;
}

# set page header
$content = <<<_HTML
	<div class = "page-header">
	<h3>Pharmacy Registration</h3>
	</div>
	_HTML;

# retrieve pharmacy registration form
ob_start();
renderRegisterPharmacyForm();
$content .= ob_get_clean();

# set page title
$title = "Pharmacy Registration";

include "../templates/base.php";
?>
