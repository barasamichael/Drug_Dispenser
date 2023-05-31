<?php

require_once "forms.php";
require_once "views.php";

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	handleRegisterPractitionerFormSubmission();
	header("Location: templates/main/homepage.php");
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
