<?php

require_once(__DIR__ . "/../custom_form.php");
require_once(__DIR__ . "/../fields.php");

class RegisterSpecialtyForm extends CustomForm
{
	public function __construct()
	{
		$title = new StringField([
			"name" => "title",
			"label" => "Title",
			"required" => true
		]);

		$description = new TextAreaField([
			"name" => "description",
			"label" => "Description",
			"required" => true,
			"rows" => 4
		]);

		$submit = new SubmitField([
			"name" => "submit",
			"label" => "Register"
		]);

		$this->addField($title);
		$this->addField($description);
		$this->addField($submit);
	}
}

class RegisterContractForm extends CustomForm
{
	public function __construct()
	{
		global $dsn, $username, $password;
		// Retrieve patient details from database
		$dbHandler = new DatabaseHandler($dsn, $username, $password);
		$dbHandler->connect();
		$pharmacy_result = $dbHandler->selectQuery('SELECT pharmacyId, title FROM pharmacy');
		$dbHandler->disconnect();

		$pharmacies = [];
		foreach ($pharmacy_result as $row)
		{
			$pharmacies[$row['pharmacyId']] = $row['title'];
		}

		// set form fields
		$pharmacyId = new SelectField([
			"name" => "pharmacyId",
			"label" => "Select Pharmacy",
			"required" => true,
			"options" => $pharmacies
		]);

		$description = new TextAreaField([
			"name" => "description",
			"label" => "Description",
			"rows" => 10,
			"required" => true
		]);

		$startDate = new DateField([
			"name" => "startDate",
			"label" => "Start Date",
			"required" => true
		]);

		$endDate = new DateField([
			"name" => "endDate",
			"label" => "End Date",
			"required" => true
		]);

		$fileUrl = new FileField([
			"name" => "fileUrl",
			"label" => "Select Contract File",
			"required" => true
		]);

		$submit = new SubmitField([
			"name" => "submit",
			"label" => "Register"
		]);

		$this->addField($pharmacyId);
		$this->addField($description);
		$this->addfield($startDate);
		$this->addField($endDate);
		$this->addField($fileUrl);
		$this->addField($submit);
	}
}

class RegisterPharmaceuticalForm extends CustomForm
{
	public function __construct()
	{
		$title = new StringField([
			"name" => "title",
			"label" => "Title",
			"required" => true
		]);

		$locationAddress = new StringField([
			"name" => "locationAddress",
			"label" => "Location Address",
			"required" => true
		]);

		$emailAddress = new EmailAddressField([
			"name" => "emailAddress",
			"label" => "Email Address",
			"required" => true
		]);

		$phoneNumber = new StringField([
			"name" => "phoneNumber",
			"label" => "Phone Number",
			"required" => true
		]);
		$submit = new SubmitField([
			"name" => "submit",
			"label" => "Register"
		]);

		$this->addField($title);
		$this->addField($emailAddress);
		$this->addField($phoneNumber);
		$this->addField($locationAddress);
		$this->addField($submit);
	}
}

class RegisterPharmacyForm extends CustomForm
{
	public function __construct()
	{
		$title = new StringField([
			"name" => "title",
			"label" => "Title",
			"required" => true
		]);

		$locationAddress = new StringField([
			"name" => "locationAddress",
			"label" => "Location Address",
			"required" => true
		]);

		$emailAddress = new EmailAddressField([
			"name" => "emailAddress",
			"label" => "Email Address",
			"required" => true
		]);

		$phoneNumber = new StringField([
			"name" => "phoneNumber",
			"label" => "Phone Number",
			"required" => true
		]);
		$submit = new SubmitField([
			"name" => "submit",
			"label" => "Register"
		]);

		$this->addField($title);
		$this->addField($emailAddress);
		$this->addField($phoneNumber);
		$this->addField($locationAddress);
		$this->addField($submit);
	}
}

class SupplyItemEntryForm extends CustomForm
{
	public function __construct()
	{
		global $dsn, $username, $password;
		// Retrieve patient details from database
		$dbHandler = new DatabaseHandler($dsn, $username, $password);
		$dbHandler->connect();

		// retrieve data from database
		$drugs_result = $dbHandler->selectQuery("SELECT drugId, scientificName FROM drug");

		$dbHandler->disconnect();

		// place retrieved data into array
		$drugs = [];
		foreach ($drugs_result as $row)
		{
			$drugs[$row['drugId']] = $row['scientificName'];
		}

		// set form fields
		$drugId = new SelectField([
			"name" => "drugId",
			"label" => "Select Drug",
			"required" => true,
			"options" => $drugs
		]);

		$tradename = new StringField([
			"name" => "tradename",
			"label" => "Trade Name",
			"required" => true
		]);

		$quantity = new FloatField([
			"name" => "quantity",
			"label" => "Quantity",
			"required" => true
		]);

		$costPrice = new FloatField([
			"name" => "costPrice",
			"label" => "Cost Price",
			"required" => true
		]);

		$sellingPrice = new FloatField([
			"name" => "sellingPrice",
			"label" => "Selling Price",
			"required" => true
		]);

		$submit = new SubmitField([
			"name" => "submit",
			"label" => "Register"
		]);

		$this->addField($drugId);
		$this->addField($tradename);
		$this->addField($quantity);
		$this->addField($costPrice);
		$this->addField($sellingPrice);
		$this->addField($submit);
	}
}

class EditPractitionerProfileForm extends CustomForm
{
	public function __construct($practitionerId)
	{
		global $dsn, $username, $password;
		// Retrieve specialty details from database
		$dbHandler = new DatabaseHandler($dsn, $username, $password);
		$dbHandler->connect();
		$specialty_result = $dbHandler->selectQuery(
			'SELECT specialtyId, title FROM specialty');
		$practitioner_query = "SELECT * FROM practitioner WHERE practitionerId = ?";
		$practitioner = $dbHandler->selectQuery($practitioner_query, [$practitionerId])[0];

		$dbHandler->disconnect();

		$specialties = [];
		foreach ($specialty_result as $row)
		{
			$specialties[$row['specialtyId']] = $row['title'];
		}

		// set form fields
		$SSN = new IntegerField([
			"name" => "SSN",
			"label" => "Social Security Number",
			"required" => true,
			"value" => $practitioner['SSN']
		]);
		$firstName = new StringField([
			"name" => "firstName",
			"label" => "First name",
			"required" => true,
			"value" => $practitioner['firstName']
		]);
		$middleName = new StringField([
			"name" => "middleName",
			"label" => "Middle name",
			"value" => $practitioner['middleName']
		]);
		$lastName = new StringField([
			"name" => "lastName",
			"label" => "Last name",
			"value" => $practitioner['lastName']
		]);
		$gender = new SelectField([
			"name" => "gender",
			"label" => "Gender",
			"required" => true,
			"options" => [
				"Male" => "Male",
				"Female" => "Female"
			],
			"value" => $practitioner['gender']
		]);
		$specialtyId = new SelectField([
			"name" => "specialtyId",
			"label" => "Select Area of Specialty",
			"required" => true,
			"options" => $specialties,
			"value" => $practitioner['specialtyId']
		]);
		$activeYear = new IntegerField([
			"name" => "activeYear",
			"label" => "Year Begun as Practitioner",
			"required" => true,
			"value" => $practitioner['activeYear']
		]);
		$dateOfBirth = new DateField([
			"name" => "dateOfBirth",
			"label" => "Date of birth",
			"required" => true,
			"value" => $practitioner['dateOfBirth']
		]);
		$phoneNumber = new StringField([
			"name" => "phoneNumber",
			"label" => "Phone number",
			"required" => true,
			"value" => $practitioner['phoneNumber']
		]);
		$submit = new SubmitField([
			"name" => "submit",
			"label" => "Commit Changes"
		]);

		$this->addField($SSN);
		$this->addField($firstName);
		$this->addField($middleName);
		$this->addField($lastName);
		$this->addField($gender);
		$this->addField($dateOfBirth);
		$this->addField($activeYear);
		$this->addField($specialtyId);
		$this->addField($phoneNumber);
		$this->addField($submit);
	}
}

class EditSupervisorProfileForm extends CustomForm
{
	public function __construct($supervisorId)
	{
		global $dsn, $username, $password;
		// Retrieve supervisor details from database
		$dbHandler = new DatabaseHandler($dsn, $username, $password);
		$dbHandler->connect();
		$supervisor = $dbHandler->selectQuery(
			'SELECT * FROM supervisor WHERE supervisorId = ?', [$supervisorId])[0];
		$dbHandler->disconnect();
		
		$firstName = new StringField([
			"name" => "firstName",
			"label" => "First name",
			"required" => true,
			"value" => $supervisor['firstName']
		]);
		$middleName = new StringField([
			"name" => "middleName",
			"label" => "Middle name",
			"value" => $supervisor['middleName']
		]);
		$lastName = new StringField([
			"name" => "lastName",
			"label" => "Last name",
			"value" => $supervisor['lastName']
		]);
		$phoneNumber = new StringField([
			"name" => "phoneNumber",
			"label" => "Phone number",
			"required" => true,
			"value" => $supervisor['phoneNumber']
		]);
		$submit = new SubmitField([
			"name" => "submit",
			"label" => "Commit Changes"
		]);

		$this->addField($firstName);
		$this->addField($middleName);
		$this->addField($lastName);
		$this->addField($phoneNumber);
		$this->addField($submit);
	}
}

class EditPharmacyProfileForm extends CustomForm
{
	public function __construct($pharmacyId)
	{
		global $dsn, $username, $password;
		// Retrieve specialty details from database
		$dbHandler = new DatabaseHandler($dsn, $username, $password);
		$dbHandler->connect();
		$pharmacy_result = $dbHandler->selectQuery(
			'SELECT * FROM pharmacy WHERE pharmacyId = ?', [$pharmacyId]);
		$pharmacy = $pharmacy_result[0];
		$dbHandler->disconnect();
		
		$title = new StringField([
			"name" => "title",
			"label" => "Title",
			"required" => true,
			"value" => $pharmacy['title']
		]);

		$locationAddress = new StringField([
			"name" => "locationAddress",
			"label" => "Location Address",
			"required" => true,
			"value" => $pharmacy['locationAddress']
		]);

		$phoneNumber = new StringField([
			"name" => "phoneNumber",
			"label" => "Phone Number",
			"required" => true,
			"value" => $pharmacy['phoneNumber']
		]);

		$submit = new SubmitField([
			"name" => "submit",
			"label" => "Commit Changes"
		]);

		$this->addField($title);
		$this->addField($phoneNumber);
		$this->addField($locationAddress);
		$this->addField($submit);
	}
}

class EditPharmaceuticalProfileForm extends CustomForm
{
	public function __construct($pharmaceuticalId)
	{
		global $dsn, $username, $password;
		// Retrieve specialty details from database
		$dbHandler = new DatabaseHandler($dsn, $username, $password);
		$dbHandler->connect();
		$pharmaceutical_result = $dbHandler->selectQuery(
			'SELECT * FROM pharmaceutical WHERE pharmaceuticalId = ?', [$pharmaceuticalId]);
		$pharmaceutical = $pharmaceutical_result[0];
		$dbHandler->disconnect();
		
		$title = new StringField([
			"name" => "title",
			"label" => "Title",
			"required" => true,
			"value" => $pharmaceutical['title']
		]);

		$locationAddress = new StringField([
			"name" => "locationAddress",
			"label" => "Location Address",
			"required" => true,
			"value" => $pharmaceutical['locationAddress']
		]);

		$phoneNumber = new StringField([
			"name" => "phoneNumber",
			"label" => "Phone Number",
			"required" => true,
			"value" => $pharmaceutical['phoneNumber']
		]);

		$submit = new SubmitField([
			"name" => "submit",
			"label" => "Commit Changes"
		]);

		$this->addField($title);
		$this->addField($phoneNumber);
		$this->addField($locationAddress);
		$this->addField($submit);
	}
}

class EditPatientProfileForm extends CustomForm
{
	public function __construct($patientId)
	{
		global $dsn, $username, $password;
		// Retrieve specialty details from database
		$dbHandler = new DatabaseHandler($dsn, $username, $password);
		$dbHandler->connect();
		$patient_result = $dbHandler->selectQuery(
			'SELECT * FROM patient WHERE patientId = ?', [$patientId]);
		$patient = $patient_result[0];
		$dbHandler->disconnect();

		$SSN = new IntegerField([
			"name" => "SSN",
			"label" => "Social Security Number",
			"required" => true,
			"value" => $patient['SSN']
		]);
		$firstName = new StringField([
			"name" => "firstName",
			"label" => "First name",
			"required" => true,
			"value" => $patient['firstName']
		]);
		$middleName = new StringField([
			"name" => "middleName",
			"label" => "Middle name",
			"value" => $patient['middleName']
		]);
		$lastName = new StringField([
			"name" => "lastName",
			"label" => "Last name",
			"value" => $patient['lastName']
		]);
		$gender = new SelectField([
			"name" => "gender",
			"label" => "Gender",
			"required" => true,
			"options" => [
				"Male" => "Male",
				"Female" => "Female"
			],
			"value" => $patient['gender']
		]);
		$dateOfBirth = new DateField([
			"name" => "dateOfBirth",
			"label" => "Date of birth",
			"required" => true,
			"value" => $patient['dateOfBirth']
		]);
		$phoneNumber = new StringField([
			"name" => "phoneNumber",
			"label" => "Phone number",
			"required" => true,
			"value" => $patient['phoneNumber']
		]);
		$residentialAddress = new StringField([
			"name" => "residentialAddress",
			"label" => "Residential Address",
			"required" => true,
			"value" => $patient['residentialAddress']
		]);
		$submit = new SubmitField([
			"name" => "submit",
			"label" => "Commit Changes"
		]);

		$this->addField($SSN);
		$this->addField($firstName);
		$this->addField($middleName);
		$this->addField($lastName);
		$this->addField($gender);
		$this->addField($dateOfBirth);
		$this->addField($phoneNumber);
		$this->addField($residentialAddress);
		$this->addField($submit);
	}
}

class RegisterPatientForm extends CustomForm
{
	public function __construct()
	{
		$SSN = new IntegerField([
			"name" => "SSN",
			"label" => "Social Security Number",
			"required" => true
		]);
		$firstName = new StringField([
			"name" => "firstName",
			"label" => "First name",
			"required" => true
		]);
		$middleName = new StringField([
			"name" => "middleName",
			"label" => "Middle name",
		]);
		$lastName = new StringField([
			"name" => "lastName",
			"label" => "Last name",
		]);
		$gender = new SelectField([
			"name" => "gender",
			"label" => "Gender",
			"required" => true,
			"options" => [
				"Male" => "Male",
				"Female" => "Female"
			],
			"value" => "Male"
		]);
		$dateOfBirth = new DateField([
			"name" => "dateOfBirth",
			"label" => "Date of birth",
			"required" => true
		]);
		$phoneNumber = new StringField([
			"name" => "phoneNumber",
			"label" => "Phone number",
			"required" => true
		]);
		$emailAddress = new EmailAddressField([
			"name" => "emailAddress",
			"label" => "Email address",
			"required" => true
		]);
		$residentialAddress = new StringField([
			"name" => "residentialAddress",
			"label" => "Residential Address",
			"required" => true
		]);
		$password = new PasswordField([
			"name" => "password",
			"label" => "Password",
			"required" => true
		]);
		$submit = new SubmitField([
			"name" => "submit",
			"label" => "Register"
		]);

		$this->addField($SSN);
		$this->addField($firstName);
		$this->addField($middleName);
		$this->addField($lastName);
		$this->addField($gender);
		$this->addField($dateOfBirth);
		$this->addField($emailAddress);
		$this->addField($phoneNumber);
		$this->addField($residentialAddress);
		$this->addField($password);
		$this->addField($submit);
	}
}

class RegisterPractitionerForm extends CustomForm
{
	public function __construct()
	{
		global $dsn, $username, $password;
		// Retrieve specialty details from database
		$dbHandler = new DatabaseHandler($dsn, $username, $password);
		$dbHandler->connect();
		$specialty_result = $dbHandler->selectQuery(
			'SELECT specialtyId, title FROM specialty');
		$dbHandler->disconnect();

		$specialties = [];
		foreach ($specialty_result as $row)
		{
			$specialties[$row['specialtyId']] = $row['title'];
		}

		// set form fields
		$SSN = new IntegerField([
			"name" => "SSN",
			"label" => "Social Security Number",
			"required" => true
		]);
		$firstName = new StringField([
			"name" => "firstName",
			"label" => "First name",
			"required" => true
		]);
		$middleName = new StringField([
			"name" => "middleName",
			"label" => "Middle name",
		]);
		$lastName = new StringField([
			"name" => "lastName",
			"label" => "Last name",
		]);
		$gender = new SelectField([
			"name" => "gender",
			"label" => "Gender",
			"required" => true,
			"options" => [
				"Male" => "Male",
				"Female" => "Female"
			],
			"value" => "Male"
		]);
		$specialtyId = new SelectField([
			"name" => "specialtyId",
			"label" => "Select Area of Specialty",
			"required" => true,
			"options" => $specialties
		]);
		$activeYear = new IntegerField([
			"name" => "activeYear",
			"label" => "Year Begun as Practitioner",
			"required" => true
		]);
		$dateOfBirth = new DateField([
			"name" => "dateOfBirth",
			"label" => "Date of birth",
			"required" => true
		]);
		$phoneNumber = new StringField([
			"name" => "phoneNumber",
			"label" => "Phone number",
			"required" => true
		]);
		$emailAddress = new EmailAddressField([
			"name" => "emailAddress",
			"label" => "Email address",
			"required" => true
		]);
		$password = new PasswordField([
			"name" => "password",
			"label" => "Password",
			"required" => true
		]);
		$submit = new SubmitField([
			"name" => "submit",
			"label" => "Register"
		]);

		$this->addField($SSN);
		$this->addField($firstName);
		$this->addField($middleName);
		$this->addField($lastName);
		$this->addField($gender);
		$this->addField($dateOfBirth);
		$this->addField($activeYear);
		$this->addField($specialtyId);
		$this->addField($emailAddress);
		$this->addField($phoneNumber);
		$this->addField($password);
		$this->addField($submit);
	}
}

class RegisterSupervisorForm extends CustomForm
{
	public function __construct()
	{
		$firstName = new StringField([
			"name" => "firstName",
			"label" => "First name",
			"required" => true
		]);
		$middleName = new StringField([
			"name" => "middleName",
			"label" => "Middle name",
		]);
		$lastName = new StringField([
			"name" => "lastName",
			"label" => "Last name",
		]);
		$phoneNumber = new StringField([
			"name" => "phoneNumber",
			"label" => "Phone number",
			"required" => true
		]);
		$emailAddress = new EmailAddressField([
			"name" => "emailAddress",
			"label" => "Email address",
			"required" => true
		]);
		$submit = new SubmitField([
			"name" => "submit",
			"label" => "Register"
		]);

		$this->addField($firstName);
		$this->addField($middleName);
		$this->addField($lastName);
		$this->addField($emailAddress);
		$this->addField($phoneNumber);
		$this->addField($submit);
	}
}

class RegisterDrugForm extends CustomForm
{
	public function __construct()
	{
		$scientificName = new StringField([
			"name" => "scientificName",
			"label" => "Scientific name",
			"required" => true
		]);
		$formula = new StringField([
			"name" => "formala",
			"label" => "Formula",
			"required" => true
		]);
		$form = new SelectField([
			"name" => "form",
			"label" => "Select drug form",
			"required" => true,
			"options" => [
				"Capsule" => "Capsule",
				"Cream" => "Cream",
				"Drops" => "Drops",
				"Elixir" => "Elixir",
				"Gel" => "Gel",
				"Inhaler" => "Inhaler",
				"Injectable" => "Injectable",
				"Lotion" => "Lotion",
				"Lozenges" => "Lozenges",
				"Ointment" => "Ointment",
				"Patch" => "Patch",
				"Powder" => "Powder",
				"Solution" => "Solution",
				"Spray" => "Spray",
				"Suppository" => "Suppository",
				"Suspension" => "Suspension",
				"Syrup" => "Syrup",
				"Tablets" => "Tablets"
			]
		]);
		$submit = new SubmitField([
			"name" => "submit",
			"label" => "Register"
		]);

		$this->addField($scientificName);
		$this->addField($formula);
		$this->addField($form);
		$this->addField($submit);
	}
}
?>
