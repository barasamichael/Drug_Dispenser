<?php

require_once 'relation.php';

class Patient extends Relation
{
	protected $__tablename__ = "patient";

	private $patientId;
	private $firstName;
	private $middleName;
	private $lastName;
	private $gender;
	private $dateOfBirth;
	private $residentialAddress;
	private $phoneNumber;
	private $emailAddress;
	private $passwordHash;
	private $lastSeen;
	private $SSN;
	private $active;
	private $dateCreated;
	private $lastUpdated;

	public function __construct(...$kwargs)
	{
		parent::__construct(...$kwargs);
	}
}

class Specialty extends Relation
{
	protected $__tablename__ = "specialty";

	private $specialtyId;
	private $title;
	private $description;

	public function __construct(...$kwargs)
	{
		parent::__construct(...$kwargs);
	}
}

class Practitioner extends Relation
{
	protected $__tablename__ = "practitioner";

	private $practitionerId;
	private $firstName;
	private $middleName;
	private $lastName;
	private $gender;
	private $dateOfBirth;
	private $residentialAddress;
	private $phoneNumber;
	private $emailAddress;
	private $passwordHash;
	private $lastSeen;
	private $SSN;
	private $activeYear;
	private $active;
	private $dateCreated;
	private $lastUpdated;

	public function __construct(...$kwargs)
	{
		parent::__construct(...$kwargs);
	}
}

class PatientPractitioner extends Relation
{
	protected $__tablename__ = "patient_practitioner";

	private $patientPractitionerId;
	private $patientId;
	private $practitionerId;
	private $primaryPractitioner;
	private $active;
	private $dateCreated;
	private $lastUpdated;

	public function __construct(...$kwargs)
	{
		parent::__construct(...$kwargs);
	}
}

class Pharmaceutical extends Relation
{
	protected $__tablename__ = "pharmaceutical";

	private $pharmaceuticalId;
	private $title;
	private $locationAddress;
	private $emailAddress;
	private $phoneNumber;
	private $active;
	private $dateCreated;
	private $lastUpdated;

	public function __construct(...$kwargs)
	{
		parent::__construct(...$kwargs);
	}
}

class Pharmacy extends Relation
{
	protected $__tablename__ = "pharmacy";

	private $pharmacyId;
	private $title;
	private $locationAddress;
	private $emailAddress;
	private $phoneNumber;
	private $active;
	private $dateCreated;
	private $lastUpdated;

	public function __construct(...$kwargs)
	{
		parent::__construct(...$kwargs);
	}
}

class Drug extends Relation
{
	protected $__tablename__ = "drug";

	private $drugId;
	private $scientificName;
	private $formula;
	private $form;
	private $dateCreated;
	private $lastUpdated;

	public function __construct(...$kwargs)
	{
		parent::__construct(...$kwargs);
	}
}

class Supervisor extends Relation
{
	protected $__tablename__ = "supervisor";

	private $supervisorId;
	private $firstName;
	private $middleName;
	private $lastName;
	private $emailAddress;
	private $phoneNumber;
	private $active;
	private $dateCreated;
	private $lastUpdated;

	public function __construct(...$kwargs)
	{
		parent::__construct(...$kwargs);
	}
}

class Contract extends Relation
{
	protected $__tablename__ = "contract";

	private $contractId;
	private $pharmacyId;
	private $pharmaceuticalId;
	private $startDate;
	private $endDate;
	private $description;
	private $fileUrl;
	private $dateCreated;
	private $lastUpdated;

	public function __construct(...$kwargs)
	{
		parent::__construct(...$kwargs);
	}
}

class ContractSupervisor extends Relation
{
	protected $__tablename__ = "contract_supervisor";

	private $contractSupervisorId;
	private $contractId;
	private $supervisorId;
	private $active;
	private $dateCreated;
	private $lastUpdated;

	public function __construct(...$kwargs)
	{
		parent::__construct(...$kwargs);
	}
}

class ContractSupply extends Relation
{
	protected $__tablename__ = "contract_supply";

	private $contractSupplyId;
	private $contractId;
	private $paymentComplete;
	private $dateCreated;
	private $lastUpdated;

	public function __construct(...$kwargs)
	{
		parent::__construct(...$kwargs);
	}
}

class SupplyItem extends Relation
{
	protected $__tablename__ = "supply_item";

	private $supplyItemId;
	private $contractSupplyId;
	private $drugId;
	private $tradename;
	private $quantity;
	private $costPrice;
	private $sellingPrice;
	private $dateCreated;
	private $lastUpdated;

	public function __construct(...$kwargs)
	{
		parent::__construct(...$kwargs);
	}
}

class Supervisor_Contract extends Relation
{
	protected $__tablename__ = "supervisor_contract";

	private $supervisorContractId;
	private $supervisorId;
	private $contractId;
	private $active;
	private $dateCreated;
	private $lastUpdated;

	public function __construct(...$kwargs)
	{
		parent::__construct(...$kwargs);
	}
}

class Prescription extends Relation
{
	protected $__tablename__ = "prescription";

	private $prescriptionId;
	private $quantity;
	private $frequency;
	private $patientPractitionerId;
	private $supplyItemId;
	private $assigned;
	private $dateCreated;
	private $lastUpdated;

	public function __construct(...$kwargs)
	{
		parent::__construct(...$kwargs);
	}
}
?>
