<?php

require_once("forms.php");
require_once("../connect.php");

echo "<link href = '../bootstrap.min.css' rel = 'stylesheet'>";
echo "<link href = '../static/css/styles.css' rel = 'stylesheet'>";

function sanitizeForm()
{
	foreach ($_POST as $key => $value)
	{
		$_POST[$key] = filter_var($value, FILTER_SANITIZE_STRING);
	}
}

echo <<<_END
	<style>
	.login-form {
	max-width:450px;
	padding-left:10%;
	padding-right:10%;
	padding-bottom:5%;
	padding-top:5%;
	margin:5% auto;
	box-shadow:0 0 10px rgba(0, 0, 0, 0.3);
	border-radius:5px;
	background-color:#ffffff;
	}

	.login-form h2 {
	text-align:center;
	}
	</style>
	_END;
	
function start_session()
{
	// set session timeout
	ini_set("session.gc_maxlifetime", 60*60*2);
	
	// Enable secure session cookie
	//ini_set('session.cookie_httponly', false);

	// enable HTTP-only flag
	//ini_set('session.cookie_secure', true);
	
	// enable cookie-only sessions
	//ini_set('session.use_only_cookies', true);

	// start session
	session_start();

	// handle session fixation
	//session_regenerate_id(true);
	
	//prevent session hijacking
	$_SESSION['check'] = hash('ripemd128', $_SERVER['REMOTE_ADDR'] . 
		$_SERVER['HTTP_USER_AGENT']);
	$_SESSION['logged_in'] = true;
}

function session_valid()
{
	if ($_SESSION['check'] == hash('ripemd128', $_SERVER['REMOTE_ADDR'] . 
		$_SERVER['HTTP_USER_AGENT']))
	{
		return true;
	}
	return false;
}

function renderLoginForm()
{
	$form = new LoginForm();

	echo <<<_END
		<div class = "login-form">
		<h2>Patient Login</h2>
		_END;
	echo $form->render();
	echo <<<_END
		</div>
		_END;
}

function handleLoginFormSubmission()
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		sanitizeForm();
		if (!(isset($_POST['emailAddress']) && isset($_POST['password']) &&
			isset($_POST['user'])))
		{
			return false;
		}

		$dsn = 'mysql:host=localhost; dbname=drugs_db';
		$username = 'root';
		$password = 'MySQLXXX-123a8910';

		// Retrieve patient details from database
		$dbHandler = new DatabaseHandler($dsn, $username, $password);
		$dbHandler->connect();

		$emailAddress = $_POST['emailAddress'];
		$password = $_POST['password'];
		$user = $_POST['user'];
		$remember_me = $_POST['remember_me'];

		switch ($user)
		{
		case "Patient":
			$sql_query = "SELECT * FROM patient WHERE emailAddress = :emailAddress";
			$patient_result = $dbHandler->selectQuery($sql_query, 
				["emailAddress" => $emailAddress]);
			$dbHandler->disconnect();

			if (!$patient_result[0])
			{
				header("Location: login.php?error=1");
				exit;
			}

			$patient = $patient_result[0];
			if (password_verify($password, $patient['passwordHash']))
			{
				start_session();
				
				$_SESSION['patientId'] = $patient['patientId'];
				$_SESSION['firstName'] = $patient['firstName'];
				$_SESSION['middleName'] = $patient['middleName'];
				$_SESSION['lastName'] = $patient['lastName'];
				$_SESSION['emailAddress'] = $patient['emailAddress'];

				// alert message
				header("Location: ../profiles/patient_profile.php" .
					"?patientId=" . $_SESSION['patientId']);
				exit;
			}
			break;

		case "Practitioner":
			$sql_query = "SELECT * FROM practitioner WHERE emailAddress = " .
				$emailAddress;
			$practitioner_result = $dbHandler->selectQuery($sql_query);
			$dbHandler->disconnect();
			
			if (!$practitioner_result[0])
			{
				header("Location: login.php?error=1");
				exit;
			}

			$practitioner = $practitioner_result[0];
			if (password_verify($password, $practitioner['passwordHash']))
			{
				start_session();
				
				$_SESSION['practitionerId'] = $practitioner['practitionerId'];
				$_SESSION['firstName'] = $practitioner['firstName'];
				$_SESSION['middleName'] = $practitioner['middleName'];
				$_SESSION['lastName'] = $practitioner['lastName'];
				$_SESSION['emailAddress'] = $practitioner['emailAddress'];

				// alert message
				header("Location: ../profiles/practitioner_profile.php" .
					"?practitionerId=" . $_SESSION['practitionerId']);
				exit;
			}
			break;

		case "Pharmacy":
			$sql_query = "SELECT * FROM pharmacy WHERE emailAddress = " .
				$emailAddress;
			$pharmacy_result = $dbHandler->selectQuery($sql_query);
			$dbHandler->disconnect();
			
			if (!$pharmacy_result[0])
			{
				header("Location: login.php?error=1");
				exit;
			}

			$pharmacy = $pharmacy_result[0];
			if (password_verify($password, $pharmacy['passwordHash']))
			{
				start_session();
				
				$_SESSION['pharmacyId'] = $pharmacy['pharmacyId'];
				$_SESSION['title'] = $pharmacy['title'];
				$_SESSION['emailAddress'] = $pharmacy['emailAddress'];

				// alert message
				header("Location: ../profiles/pharmacy_profile.php" .
					"?pharmacyId=" . $_SESSION['pharmacyId']);
				exit;
			}
			break;

		case "Pharmaceutical":
			$sql_query = "SELECT * FROM pharmaceutical WHERE emailAddress = " .
				$emailAddress;
			$pharmaceutical_result = $dbHandler->selectQuery($sql_query);
			$dbHandler->disconnect();
			
			if (!$pharmaceutical_result[0])
			{
				header("Location: login.php?error=1");
				exit;
			}

			$pharmaceutical = $pharmaceutical_result[0];
			if (password_verify($password, $pharmaceutical['passwordHash']))
			{
				start_session();
				
				$_SESSION['pharmaceuticalId'] = $pharmaceutical['pharmaceuticalId'];
				$_SESSION['title'] = $pharmaceutical['title'];
				$_SESSION['emailAddress'] = $pharmaceutical['emailAddress'];

				// alert message
				header("Location: ../profiles/pharmaceutical_profile.php" .
					"?pharmaceuticalId=" . $_SESSION['pharmaceuticalId']);
				exit;
			}
			break;

		default:
			return false;
		}
	}
}
?>
