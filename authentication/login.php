<?php
require_once "forms.php";
require_once "views.php";

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	handleLoginFormSubmission();
}

ob_start();
renderLoginForm();

$content = ob_get_clean();
$title = "Login";

include "../templates/base.php";
?>
