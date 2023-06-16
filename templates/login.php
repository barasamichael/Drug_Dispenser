<?php

require_once "authentication/forms.php";
require_once "authentication/views.php";

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	handleLoginFormSubmission();
}

ob_start();
renderLoginForm();

$content = ob_get_clean();
$title = "Patient Login";

include "templates/base.php";
?>
