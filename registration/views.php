<?php

require_once("forms.php");
require_once("../models.php");
require_once("../config.php");
echo "<link href = '../bootstrap.min.css' rel = 'stylesheet'>";
echo "<link href = '../static/css/styles.css' rel = 'stylesheet'>";

function sanitizeForm()
{
	foreach ($_POST as $key => $value)
	{
		$_POST[$key] = filter_var($value, FILTER_SANITIZE_STRING);
	}
}

function renderEditPatientProfileForm($patientId)
{
	$form = new EditPatientProfileForm($patientId);
	echo $form->render();
}

function renderEditPractitionerProfileForm($practitionerId)
{
	$form = new EditPractitionerProfileForm($practitionerId);
	echo $form->render();
}

function renderEditPharmacyProfileForm($pharmacyId)
{
	$form = new EditPharmacyProfileForm($pharmacyId);
	echo $form->render();
}

function renderEditPharmaceuticalProfileForm($pharmaceuticalId)
{
	$form = new EditPharmaceuticalProfileForm($pharmaceuticalId);
	echo $form->render();
}

function renderEditSupervisorProfileForm($supervisorId)
{
	$form = new EditSupervisorProfileForm($supervisorId);
	echo $form->render();
}

function handleEditPractitionerProfileFormSubmission($practitionerId)
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		sanitizeForm();

		$firstName = $_POST['firstName'];
		$middleName = $_POST['middleName'];
		$lastName = $_POST['lastName'];
		$gender = $_POST['gender'];
		$phoneNumber = $_POST['phoneNumber'];
		$specialtyId = $_POST['specialtyId'];
		$dateOfBirth = $_POST['dateOfBirth'];
		$SSN = $_POST['SSN'];
		$activeYear = $_POST['activeYear'];

		global $dsn, $username, $password;
		// Retrieve specialty details from database
		$dbHandler = new DatabaseHandler($dsn, $username, $password);
		$dbHandler->connect();
		$practitioner_result = $dbHandler->selectQuery(
			'SELECT * FROM practitioner WHERE practitionerId = ?', [$practitionerId]);
		$practitioner = $practitioner_result[0];

		$updateQuery = "UPDATE practitioner SET SSN = ?, firstName = ?, middleName = ?, " .
			"lastName = ?, gender = ?, specialtyId = ?, phoneNumber = ?, " .
			"dateOfBirth = ?, activeYear = ? WHERE practitionerId = ?";
		$values = [$SSN, $firstName, $middleName, $lastName, $gender, $specialtyId, 
			$phoneNumber, $dateOfBirth, $activeYear, $practitionerId];
		$dbHandler->executeQuery($updateQuery, $values);
		$dbHandler->disconnect();
	}
}

function handleEditSupervisorProfileFormSubmission($supervisorId)
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		sanitizeForm();

		$firstName = $_POST['firstName'];
		$middleName = $_POST['middleName'];
		$lastName = $_POST['lastName'];
		$phoneNumber = $_POST['phoneNumber'];

		global $dsn, $username, $password;
		// Retrieve specialty details from database
		$dbHandler = new DatabaseHandler($dsn, $username, $password);
		$dbHandler->connect();
		$supervisor_result = $dbHandler->selectQuery(
			'SELECT * FROM supervisor WHERE supervisorId = ?', [$supervisorId]);
		$supervisor = $supervisor_result[0];

		$updateQuery = "UPDATE supervisor SET firstName = ?, middleName = ?, " .
			"lastName = ?, phoneNumber = ? WHERE supervisorId = ?";
		$values = [$firstName, $middleName, $lastName, $phoneNumber, $supervisorId];
		$dbHandler->executeQuery($updateQuery, $values);
		$dbHandler->disconnect();
	}
}

function handleEditPharmacyProfileFormSubmission($pharmacyId)
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		sanitizeForm();

		$title = $_POST['title'];
		$locationAddress = $_POST['locationAddress'];
		$phoneNumber = $_POST['phoneNumber'];

		global $dsn, $username, $password;
		// Retrieve specialty details from database
		$dbHandler = new DatabaseHandler($dsn, $username, $password);
		$dbHandler->connect();
		$pharmacy_result = $dbHandler->selectQuery(
			'SELECT * FROM pharmacy WHERE pharmacyId = ?', [$pharmacyId]);
		$pharmacy = $pharmacy_result[0];

		$updateQuery = "UPDATE pharmacy SET title = ?, locationAddress = ?, " . 
			"phoneNumber = ? WHERE pharmacyId = ?";
		$values = [$title, $locationAddress, $phoneNumber, $pharmacyId];
		$dbHandler->executeQuery($updateQuery, $values);
		$dbHandler->disconnect();
	}
}

function handleEditPharmaceuticalProfileFormSubmission($pharmaceuticalId)
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		sanitizeForm();

		$title = $_POST['title'];
		$locationAddress = $_POST['locationAddress'];
		$phoneNumber = $_POST['phoneNumber'];

		global $dsn, $username, $password;
		// Retrieve specialty details from database
		$dbHandler = new DatabaseHandler($dsn, $username, $password);
		$dbHandler->connect();
		$pharmaceutical_result = $dbHandler->selectQuery(
			'SELECT * FROM pharmaceutical WHERE pharmaceuticalId = ?', 
			[$pharmaceuticalId]);
		$pharmaceutical = $pharmaceutical_result[0];

		$updateQuery = "UPDATE pharmaceutical SET title = ?, locationAddress = ?, " . 
			"phoneNumber = ? WHERE pharmaceuticalId = ?";
		$values = [$title, $locationAddress, $phoneNumber, $pharmaceuticalId];
		$dbHandler->executeQuery($updateQuery, $values);
		$dbHandler->disconnect();
	}
}

function handleEditPatientProfileFormSubmission($patientId)
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		sanitizeForm();

		$firstName = $_POST['firstName'];
		$middleName = $_POST['middleName'];
		$lastName = $_POST['lastName'];
		$gender = $_POST['gender'];
		$phoneNumber = $_POST['phoneNumber'];
		$residentialAddress = $_POST['residentialAddress'];
		$dateOfBirth = $_POST['dateOfBirth'];
		$SSN = $_POST['SSN'];

		global $dsn, $username, $password;
		// Retrieve specialty details from database
		$dbHandler = new DatabaseHandler($dsn, $username, $password);
		$dbHandler->connect();
		$patient_result = $dbHandler->selectQuery(
			'SELECT * FROM patient WHERE patientId = ?', [$patientId]);
		$patient = $patient_result[0];

		$updateQuery = "UPDATE patient SET SSN = ?, firstName = ?, middleName = ?, " .
			"lastName = ?, gender = ?, residentialAddress = ?, phoneNumber = ?, " .
			"dateOfBirth = ? WHERE patientId = ?";
		$values = [$SSN, $firstName, $middleName, $lastName, $gender, $residentialAddress, 
			$phoneNumber, $dateOfBirth, $patientId];
		$dbHandler->executeQuery($updateQuery, $values);
		$dbHandler->disconnect();
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

function handleSupplyItemEntryFormSubmission($contractSupplyId)
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		sanitizeForm();
		$supplyItem = new SupplyItem([
			"contractSupplyId" => $contractSupplyId,
			"drugId" => $_POST['drugId'],
			"tradename" => $_POST['tradename'],
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
			'passwordHash' => password_hash($_POST['password'], PASSWORD_DEFAULT),
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
