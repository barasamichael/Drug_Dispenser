<?php

require_once "forms.php";
require_once "views.php";

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
