<?php

require_once "forms.php";
require_once "views.php";

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	handleRegisterDrugFormSubmission();
}

ob_start();
renderRegisterDrugForm();

$content = ob_get_clean();
$title = "Register Drug";

include "../templates/base.php";
?>
