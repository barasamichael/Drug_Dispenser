<?php

require_once("forms.php");
echo "<link href = '../bootstrap.min.css' rel = 'stylesheet'>";
echo "<link href = '../static/css/styles.css' rel = 'stylesheet'>";
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
		$email = $_POST['emailAddress'];
		$password = $_POST['password'];

		$loginSuccessful = true;

		if ($loginSuccessful)
		{
			header("Location: templates/main/homepage.php");
			exit;
		}
		else
		{
			header("Location: login.php?error=1");
		}
	}
}
?>
