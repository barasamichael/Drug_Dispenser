<?php

require_once "forms.php";
require_once "views.php";

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	handleRegisterPractitionerFormSubmission();
}

ob_start();
renderRegisterPractitionerForm();

$content = ob_get_clean();
$title = "Patient Login";

include "../templates/base.php";
?>
