<?php

require_once "forms.php";
require_once "views.php";

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	handleRegisterPharmaceuticalFormSubmission();
	header("Location: templates/main/homepage.php");
	exit;
}

ob_start();
renderRegisterPharmaceuticalForm();

$content = ob_get_clean();
$title = "Pharmaceutical Registration";

include "../templates/base.php";
?>
