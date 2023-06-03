<?php

require_once(__DIR__ . "/../custom_form.php");
require_once(__DIR__ . "/../fields.php");

class PatientPractitionerAssignmentForm extends CustomForm
{
	public function __construct()
	{
		// database credentials
		$dsn = 'mysql:host=localhost; dbname=drugs_db';
		$username = 'root';
		$password = 'MySQLXXX-123a8910';
		
		// Retrieve unassigned practitioners from database
		$dbHandler = new DatabaseHandler($dsn, $username, $password);
		$dbHandler->connect();
		$SQL_query = "SELECT practitionerId, firstName, middleName, lastName " .
			"FROM practitioner";
		$practitioner_result = $dbHandler->selectQuery($SQL_query);
		$dbHandler->disconnect();

		$practitioners = [];
		foreach ($practitioner_result as $row)
		{
			$practitioners[$row['practitionerId']] = $row['firstName'] . " " . 
				$row['middleName']. " ". $row['lastName'];
		}

		$practitionerId = new SelectField([
			"name" => "practitionerId",
			"label" => "Select Practitioner",
			"required" => true,
			"options" => $practitioners
		]);

		$primaryPractitioner = new SelectField([
			"name" => "primaryPractitioner",
			"label" => "Select Priority Level",
			"required" => true,
			"options" => [
				true => "Primary Practitioner",
				false => "Secondary Practitioner"
			]
		]); 

		$submit = new SubmitField([
			"name" => "submit",
			"label" => "Submit"
		]);

		$this->addField($practitionerId);
		$this->addField($primaryPractitioner);
		$this->addField($submit);
	}
}

class PractitionerPatientAssignmentForm extends CustomForm
{
	public function __construct()
	{
		// database credentials
		$dsn = 'mysql:host=localhost; dbname=drugs_db';
		$username = 'root';
		$password = 'MySQLXXX-123a8910';
		
		// Retrieve unassigned patients from database
		$dbHandler = new DatabaseHandler($dsn, $username, $password);
		$dbHandler->connect();
		$SQL_query = "SELECT patientId, firstName, middleName, lastName " .
			"FROM patient";
		$patient_result = $dbHandler->selectQuery($SQL_query);
		$dbHandler->disconnect();

		$patients = [];
		foreach ($patient_result as $row)
		{
			$patients[$row['patientId']] = $row['firstName'] . " " . 
				$row['middleName']. " ". $row['lastName'];
		}

		$patientId = new SelectField([
			"name" => "patientId",
			"label" => "Select Patient",
			"required" => true,
			"options" => $patients
		]);

		$primaryPractitioner = new SelectField([
			"name" => "primaryPractitioner",
			"label" => "Select Priority Level",
			"required" => true,
			"options" => [
				true => "Primary Practitioner",
				false => "Secondary Practitioner"
			]
		]); 

		$submit = new SubmitField([
			"name" => "submit",
			"label" => "Submit"
		]);

		$this->addField($patientId);
		$this->addField($primaryPractitioner);
		$this->addField($submit);
	}
}
?>
