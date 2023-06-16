<?php

require_once(__DIR__ . "/../custom_form.php");
require_once(__DIR__ . "/../fields.php");

class LoginForm extends CustomForm
{
	public function __construct()
	{
		$emailAddress = new EmailAddressField([
			"name" => "emailAddress",
			"label" => "Email Address",
			"required" => true
		]);
		$password = new PasswordField([
			"name" => "password",
			"label" => "Password",
			"required" => true
		]);

		$user = new SelectField([
			"name" => "user",
			"label" => "Select User",
			"required" => true,
			"options" => [
				"Patient" => "Patient",
				"Practitioner" => "Practitioner",
				"Supervisor" => "Supervisor",
				"Pharmacy" => "Pharmacy",
				"Pharmaceutical" => "Pharmaceutical",
				"Administrator" => "Administrator"
			]
		]);
	
		$rememberMe = new CheckButtonField([
			"name" => "remember_me",
			"label" => "Remember Me"
		]);
	
		$submit = new SubmitField([
			"name" => "submit",
			"label" => "Log In"
		]);
	
		$this->addField($emailAddress);
		$this->addField($password);
		$this->addField($user);
		$this->addField($rememberMe);
		$this->addField($submit);
	}
}
?>
