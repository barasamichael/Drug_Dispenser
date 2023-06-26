<?php

session_start();
/* ---------------------------------------------------------------------------------------------- *
 *                       ONLY LOGGED IN USERS CAN ACCESS THESE CONTENT                            *
 * ---------------------------------------------------------------------------------------------- */
if (!isset($_SESSION['role']))
{
	header("Location: authentication/login.php");
	exit;
}

/* ---------------------------------------------------------------------------------------------- *
 *             ALLOW ADMINISTRATOR ACCESS ONLY                   *
 * ---------------------------------------------------------------------------------------------- */
if ($_SESSION['role'] !== 'administrator')
{
	http_response_code(403);
	header("Location: templates/errors/403.php");
	exit;
}

$title = "Administrator Dashboard";
$content = <<<_HTML
    <style>
	.container {
	    display: flex;
	    flex-wrap: wrap;
	    justify-content: center;
	}

	.card {
	    background-color: #fff;
	    border-radius: 10px;
	    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
	    width: 200px;
	    height: 200px;
	    margin: 20px;
	    transition: transform 0.3s;
	    overflow: hidden;
	}

	.card:hover {
	    transform: scale(1.1);
	}

	.card a {
	    display: flex;
	    align-items: center;
	    justify-content: center;
	    height: 100%;
	    text-decoration: none;
	    color: #333;
	    transition: background-color 0.3s;
	}

	.card:hover a {
	    background-color: #FF4500;
	}

	.card:nth-child(1) {
	    background-image: linear-gradient(to bottom right, #FF416C, #FF4B2B);
	}

	.card:nth-child(2) {
	    background-image: linear-gradient(to bottom right, #1D4350, #A43931);
	}

	.card:nth-child(3) {
	    background-image: linear-gradient(to bottom right, #4A569D, #DC2424);
	}

	.card:nth-child(4) {
	    background-image: linear-gradient(to bottom right, #333333, #999999);
	}

	.card:nth-child(5) {
    background-image: linear-gradient(to bottom right, #00A8B5, #BEDB39);
}

.card:nth-child(6) {
    background-image: linear-gradient(to bottom right, #4C3CDB, #FF5F6D);
}

.card:nth-child(7) {
    background-image: linear-gradient(to bottom right, #E71D36, #FF9F1C);
}

.card:nth-child(8) {
    background-image: linear-gradient(to bottom right, #4E54C8, #8F94FB);
}

.card:nth-child(9) {
    background-image: linear-gradient(to bottom right, #FF512F, #DD2476);
}

.card:nth-child(10) {
    background-image: linear-gradient(to bottom right, #833AB4, #E1306C);
}

.card:nth-child(11) {
    background-image: linear-gradient(to bottom right, #45B649, #DCE35B);
}

.card:nth-child(12) {
    background-image: linear-gradient(to bottom right, #FF4E50, #F9D423);
}

.card:nth-child(13) {
    background-image: linear-gradient(to bottom right, #00B4DB, #0083B0);
}

.card:nth-child(14) {
    background-image: linear-gradient(to bottom right, #FF512F, #F09819);
}

.card:nth-child(15) {
    background-image: linear-gradient(to bottom right, #FC354C, #0ABFBC);
}

	.card-content {
	    text-align: center;
	    padding: 20px;
	}

	h3 {
	    margin-top: 0;
	    color: #fff;
	    font-size: 18px;
	}
    </style>
    <div class="container">
	<div class="card">
	    <a href="profiles/list_of_drugs.php">
		<div class="card-content">
		    <h3>List of Drugs</h3>
		</div>
	    </a>
	</div>
	<div class="card">
	    <a href="profiles/list_of_patients.php">
		<div class="card-content">
		    <h3>List of Patients</h3>
		</div>
	    </a>
	</div>
	<div class="card">
	    <a href="profiles/list_of_pharmaceuticals.php">
		<div class="card-content">
		    <h3>List of Pharmaceuticals</h3>
		</div>
	    </a>
	</div>
	<div class="card">
	    <a href="profiles/list_of_pharmacies.php">
		<div class="card-content">
		    <h3>List of Pharmacies</h3>
		</div>
	    </a>
	</div>
	<div class="card">
	    <a href="profiles/list_of_practitioners.php">
		<div class="card-content">
		    <h3>List of Practitioners</h3>
		</div>
	    </a>
	</div>
	<div class="card">
	    <a href="profiles/list_of_specialties.php">
		<div class="card-content">
		    <h3>List of Specialties</h3>
		</div>
	    </a>
	</div>
	<div class="card">
	    <a href="profiles/list_of_supervisors.php">
		<div class="card-content">
		    <h3>List of Supervisors</h3>
		</div>
	    </a>
	</div>
	<div class="card">
	    <a href="registration/register_drug.php">
		<div class="card-content">
		    <h3>Register Drug</h3>
		</div>
	    </a>
	</div>
	<div class="card">
	    <a href="registration/register_patient.php">
		<div class="card-content">
		    <h3>Register Patient</h3>
		</div>
	    </a>
	</div>
	<div class="card">
	    <a href="registration/register_pharmaceutical.php">
		<div class="card-content">
		    <h3>Register Pharmaceutical</h3>
		</div>
	    </a>
	</div>
	<div class="card">
	    <a href="registration/register_pharmacy.php">
		<div class="card-content">
		    <h3>Register Pharmacy</h3>
		</div>
	    </a>
	</div>
	<div class="card">
	    <a href="registration/register_practitioner.php">
		<div class="card-content">
		    <h3>Register Practitioner</h3>
		</div>
	    </a>
	</div>
	<div class="card">
	    <a href="registration/register_specialty.php">
		<div class="card-content">
		    <h3>Register Specialty</h3>
		</div>
	    </a>
	</div>
	<div class="card">
	    <a href="registration/register_supervisor.php">
		<div class="card-content">
		    <h3>Register Supervisor</h3>
		</div>
	    </a>
	</div>
    </div>
_HTML;

require_once('templates/base.php');
?>
