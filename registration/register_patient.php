<?php

require_once "forms.php";
require_once "views.php";

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	handleRegisterPatientFormSubmission();
	header("Location: ../login.php");
	exit;
}

ob_start();
renderRegisterPatientForm();

$content = ob_get_clean();
$title = "Patient Login";

include "../templates/base.php";
?>
