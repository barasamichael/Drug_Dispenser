<?php
require_once(__DIR__ . "/../custom_form.php");
require_once(__DIR__ . "/../fields.php");
require_once(__DIR__ . "/../config.php");

class ContractSupervisorAssignmentForm extends CustomForm
{
	public function __construct($supervisorId)
	{
		global $dsn, $username, $password;
		// Retrieve unassigned practitioners from database
		$dbHandler = new DatabaseHandler($dsn, $username, $password);
		$dbHandler->connect();
		$SQL_query = "SELECT contractId FROM contract WHERE contractId NOT IN ( " . 
			"SELECT contractId FROM contract_supervisor WHERE supervisorId = ?)";
		$contract_result = $dbHandler->selectQuery($SQL_query, [$supervisorId]);
		$dbHandler->disconnect();

		$contracts = [];
		foreach ($contract_result as $row)
		{
			$contracts[$row['contractId']] = "Contract ID: " . $row['contractId']; 
		}

		$contractId = new SelectField([
			"name" => "contractId",
			"label" => "Select Contract",
			"required" => true,
			"options" => $contracts
		]);

		$submit = new SubmitField([
			"name" => "patient-practitioner-assignment",
			"label" => "Submit"
		]);

		$this->addField($contractId);
		$this->addField($submit);
	}
}

class PatientPractitionerAssignmentForm extends CustomForm
{
	public function __construct($patientId)
	{
		global $dsn, $username, $password;
		// Retrieve unassigned practitioners from database
		$dbHandler = new DatabaseHandler($dsn, $username, $password);
		$dbHandler->connect();
		$SQL_query = "SELECT p.practitionerId, p.firstName, p.middleName, p.lastName " .
			"FROM practitioner AS p WHERE p.practitionerId NOT IN ( " .
			"SELECT pp.practitionerId FROM patient_practitioner AS pp WHERE " .
			"pp.patientId = $patientId)";

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
			"name" => "patient-practitioner-assignment",
			"label" => "Submit"
		]);

		$this->addField($practitionerId);
		$this->addField($primaryPractitioner);
		$this->addField($submit);
	}
}

class PrescriptionAssignmentForm extends CustomForm
{
	public function __construct()
	{
		global $dsn, $username, $password;
		// Retrieve unassigned patients from database
		$dbHandler = new DatabaseHandler($dsn, $username, $password);
		$dbHandler->connect();
		$SQL_query = "SELECT supplyItemId, tradename FROM supply_item";
		$supply_items_result = $dbHandler->selectQuery($SQL_query);
		$dbHandler->disconnect();

		$supply_items = [];
		foreach ($supply_items_result as $row)
		{
			$supply_items[$row['supplyItemId']] = $row['tradename'];
		}

		$supplyItemId = new SelectField([
			"name" => "supplyItemId",
			"label" => "Select Drug",
			"required" => true,
			"options" => $supply_items
		]);
		
		$quantity = new IntegerField([
			"name" => "quantity",
			"label" => "Quantity",
			"required" => true,
		]);

		$frequency = new StringField([
			"name" => "frequency",
			"label" => "Frequency",
			"required" => true,
		]);

		$submit = new SubmitField([
			"name" => "prescription-assignment",
			"label" => "Submit"
		]);

		$this->addField($supplyItemId);
		$this->addField($quantity);
		$this->addField($frequency);
		$this->addField($submit);
	}
}

class PractitionerPatientAssignmentForm extends CustomForm
{
	public function __construct()
	{
		global $dsn, $username, $password;
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

class RegisterSupplyItemForm extends CustomForm
{
	public function __construct()
	{
		global $dsn, $username, $password;
		// Retrieve drugs from database
		$dbHandler = new DatabaseHandler($dsn, $username, $password);
		$dbHandler->connect();
		$SQL_query = "SELECT DISTINCT drugId, scientificName FROM drug";
		$drug_result = $dbHandler->selectQuery($SQL_query);
		$dbHandler->disconnect();

		$drugs = [];
		foreach ($drug_result as $row)
		{
			$drugs[$row['drugId']] = $row['scientificName']; 
		}

		$drugId = new SelectField([
			"name" => "drugId",
			"label" => "Select Drug",
			"required" => true,
			"options" => $drugs
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
			"label" => "Add to Order"
		]);

		$this->addField($patientId);
		$this->addField($primaryPractitioner);
		$this->addField($submit);
	}
}
?>
