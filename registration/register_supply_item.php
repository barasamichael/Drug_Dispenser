<?php

require_once "forms.php";
require_once "views.php";

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	handleSupplyItemEntryFormSubmission();
	header("Location: templates/main/homepage.php");
	exit;
}

# Set page header
$content = <<<_HTML
	<div class = "page-header">
	<h3>Supply Item Entry</h3>
	</div>
	_HTML;

# retrieve supply item entry form
ob_start();
renderSupplyItemEntryForm();
$content .= ob_get_clean();

# set page title
$title = "Supply Item Entry";

include "../templates/base.php";
?>
