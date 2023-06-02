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
			"patientId" => 1,
			"primaryPractitioner" => $_POST['primaryPractitioner']
		]);
		$patientPractitioner->save();
	}
}
?>
