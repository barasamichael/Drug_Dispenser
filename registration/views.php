<?php

require_once("forms.php");
echo "<link href = '../bootstrap.min.css' rel = 'stylesheet'>";
echo "<link href = '../static/css/styles.css' rel = 'stylesheet'>";

function renderRegisterDrugForm()
{
	$form = new RegisterDrugForm();
	echo $form->render();
}

function handleRegisterDrugFormSubmission()
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		header("Location: templates/main/homepage.php");
		exit;
	}
}

function renderRegisterPatientForm()
{
	$form = new RegisterPatientForm();
	echo $form->render();
}

function handleRegisterPatientFormSubmission()
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		header("Location: templates/main/homepage.php");
		exit;
	}
}

function renderRegisterPractitionerForm()
{
	$form = new RegisterPractitionerForm();
	echo $form->render();
}

function handleRegisterPractitionerFormSubmission()
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		header("Location: templates/main/homepage.php");
		exit;
	}
}

function renderRegisterSupervisorForm()
{
	$form = new RegisterSupervisorForm();
	echo $form->render();
}

function handleRegisterSupervisorFormSubmission()
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		header("Location: templates/main/homepage.php");
		exit;
	}
}
?>
