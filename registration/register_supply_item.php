<?php
session_start();
require_once "forms.php";
require_once "views.php";

/* ---------------------------------------------------------------------------------------------- *
 *                             ENSURE ALL LINK PARAMETERS PROVIDED                                *
 * ---------------------------------------------------------------------------------------------- */
if (!$_GET['contractSupplyId'])
{
	header("Location: ../templates/errors/invalid_access.php");
	exit;
}
$contractSupplyId = $_GET['contractSupplyId'];

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	handleSupplyItemEntryFormSubmission($contractSupplyId);
	header("Location: ../profiles/contract_supply_profile.php?contractSupplyId=$contractSupplyId");
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
