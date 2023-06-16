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

function renderPrescriptionAssignmentForm()
{
	$form = new PrescriptionAssignmentForm();
	echo $form->render();
}

function handlePrescriptionAssignmentFormSubmission($patientPractitionerId)
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		sanitizeForm();
		$prescription = new Prescription([
			"patientPractitionerId" => $patientPractitionerId,
			"frequency" => $_POST['frequency'],
			"quantity" => $_POST['quantity'],
			"supplyItemId" => $_POST['supplyItemId']
		]);
		print_r($prescription);
		$prescription->save();
	}
}

function renderContractSupervisorAssignmentForm()
{
	$form = new ContractSupervisorAssignmentForm();
	echo $form->render();
}

function handleContractSupervisorAssignmentFormSubmission()
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		sanitizeForm();
		$contractSupervisor = new ContractSupervisor([
			"contractId" => $_POST['contractId'],
			"supervisorId" => $_SESSION['supervisorId']
		]);
		$contractSupervisor->save();
	}
}

function renderPatientPractitionerAssignmentForm()
{
	$form = new PatientPractitionerAssignmentForm();
	echo $form->render();
}

function handlePatientPractitionerAssignmentFormSubmission()
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		sanitizeForm();
		$patientPractitioner = new PatientPractitioner([
			"practitionerId" => $_POST['practitionerId'],
			"patientId" => $_SESSION['patientId'],
			"primaryPractitioner" => $_POST['primaryPractitioner']
		]);
		$patientPractitioner->save();
	}
}

function renderPractitionerPatientAssignmentForm()
{
	$form = new PractitionerPatientAssignmentForm();
	echo $form->render();
}

function handlePractitionerPatientAssignmentFormSubmission()
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		sanitizeForm();
		$patientPractitioner = new PatientPractitioner([
			"patientId" => $_POST['patientId'],
			"practitionerId" => $_SESSION['practitionerId'],
			"primaryPractitioner" => $_POST['primaryPractitioner']
		]);
		$patientPractitioner->save();
	}
}
?>
