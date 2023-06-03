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

function renderRegisterContractForm()
{
	$form = new RegisterContractForm();
	echo $form->render();
}

function handleRegisterContractFormSubmission()
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		sanitizeForm();
		$contract = new Contract([
			"pharmaceuticalId" => 1,
			"startDate" => $_POST['startDate'],
			"endDate" => $_POST['endDate'],
			"description" => $_POST['description'],
			"fileUrl" => "contract.pdf",
			"pharmacyId" => $_POST['pharmacyId']
		]);
		$contract->save();
	}
}

function renderRegisterPharmacyForm()
{
	$form = new RegisterPharmacyForm();
	echo $form->render();
}

function handleRegisterPharmacyFormSubmission()
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		sanitizeForm();
		$pharmacy = new Pharmacy([
			"title" => $_POST['title'],
			"locationAddress" => $_POST['locationAddress'],
			"emailAddress" => $_POST['emailAddress'],
			"phoneNumber" => $_POST['phoneNumber'],
		]);
		$pharmacy->save();
	}
}

function renderRegisterPharmaceuticalForm()
{
	$form = new RegisterPharmaceuticalForm();
	echo $form->render();
}

function handleRegisterPharmaceuticalFormSubmission()
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		sanitizeForm();
		$pharmaceutical = new Pharmaceutical([
			"title" => $_POST['title'],
			"locationAddress" => $_POST['locationAddress'],
			"emailAddress" => $_POST['emailAddress'],
			"phoneNumber" => $_POST['phoneNumber'],
		]);
		$pharmaceutical->save();
	}
}

function renderSupplyItemEntryForm()
{
	$form = new SupplyItemEntryForm();
	echo $form->render();
}

function handleSupplyItemEntryFormSubmission()
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		sanitizeForm();
		$supplyItem = new SupplyItem([
			"contractSupplyId" => $_POST['contractSupplyId'],
			"drugId" => $_POST['drugId'],
			"tradeName" => $_POST['tradeName'],
			"quantity" => $_POST['quantity'],
			"costPrice" => $_POST['costPrice'],
			"sellingPrice" => $_POST['sellingPrice']
		]);
		$supplyItem->save();
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

function renderRegisterSpecialtyForm()
{
	$form = new RegisterSpecialtyForm();
	echo $form->render();
}

function handleRegisterSpecialtyFormSubmission()
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		sanitizeForm();
		$specialty = new Specialty([
			'title' => $_POST['title'],
			'description' => $_POST['description']
		]);
		$specialty->save();
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
			'passwordHash' => $_POST['password'],
			'specialtyId' => $_POST['specialtyId'],
			'activeYear' => $_POST['activeYear']
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
