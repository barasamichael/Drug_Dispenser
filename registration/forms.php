<?php

require_once(__DIR__ . "/../custom_form.php");
require_once(__DIR__ . "/../fields.php");

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
