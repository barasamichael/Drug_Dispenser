<?php

require_once "forms.php";
require_once "views.php";

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	handleRegisterContractFormSubmission();
}

ob_start();
renderRegisterContractForm();

$content = ob_get_clean();
$title = "Register Contract";

include "../templates/base.php";
?>
