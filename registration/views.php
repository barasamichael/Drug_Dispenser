<?php

require_once("forms.php");
require_once("../models.php");
echo "<link href = '../bootstrap.min.css' rel = 'stylesheet'>";
echo "<link href = '../static/css/styles.css' rel = 'stylesheet'>";

function sanitizeForm()
{
	foreach ($_POST as $key => $value)
	{
		$_POST[$key] = filter_var($value, FILTER_SANITIZE_STRING);
	}
}

function renderRegisterDrugForm()
{
	$form = new RegisterDrugForm();
	echo $form->render();
}

function handleRegisterDrugFormSubmission()
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		sanitizeForm();
		$drug = new Drug([
			"formula" => $_POST['formala'],
			"form" => $_POST['form'],
			"scientificName" => $_POST['scientificName']
		]);
		$drug->save();
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
		sanitizeForm();
		$patient = new Patient([
			'firstName' => $_POST['firstName'],
			'middleName' => $_POST['middleName'],
			'lastName' => $_POST['lastName'],
			'gender' => $_POST['gender'],
			'dateOfBirth' => $_POST['dateOfBirth'],
			'residentialAddress' => $_POST['residentialAddress'],
			'phoneNumber' => $_POST['phoneNumber'],
			'emailAddress' => $_POST['emailAddress'],
			'passwordHash' => $_POST['password'],
			'lastSeen' => $_POST['lastSeen'],
			'SSN' => $_POST['SSN']
		]);
		$patient->save();
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
		sanitizeForm();
		$practitioner = new Practitioner([
			'SSN' => $_POST['SSN'],
			'firstName' => $_POST['firstName'],
			'middleName' => $_POST['middleName'],
			'lastName' => $_POST['lastName'],
			'gender' => $_POST['gender'],
			'dateOfBirth' => $_POST['dateOfBirth'],
			'phoneNumber' => $_POST['phoneNumber'],
			'emailAddress' => $_POST['emailAddress'],
			'passwordHash' => $_POST['password']
		]);
		$practitioner->save();
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
		sanitizeForm();
		$supervisor = new Supervisor([
			'firstName' => $_POST['firstName'],
			'middleName' => $_POST['middleName'],
			'lastName' => $_POST['lastName'],
			'emailAddress' => $_POST['emailAddress'],
			'phoneNumber' => $_POST['phoneNumber']
		]);
		$supervisor->save();
	}
}
?>
