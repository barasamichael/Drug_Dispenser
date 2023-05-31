<?php

require_once "forms.php";
require_once "views.php";

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	handleRegisterPharmaceuticalFormSubmission();
	header("Location: register_pharmaceutical.php");
	exit;
}

# Set page header
$content = <<<_HTML
	<div class = "page-header">
	<h3>Pharmaceutical Registration</h3>
	</div>
	_HTML;

# Retrieve pharmaceutical registration form
ob_start();
renderRegisterPharmaceuticalForm();
$content .= ob_get_clean();

# set page title
$title = "Pharmaceutical Registration";

include "../templates/base.php";
?>
