<?php
session_start();

$title = "Invalid Access";
$content = <<<_HTML
	<style>
	body {
	font-family: Arial, sans-serif;
	background-color: #f5f5f5;
	color: #333;
	margin: 0;
	padding: 0;
	}

	.container {
	max-width: 500px;
	margin: 50px auto;
	text-align: center;
	padding: 30px;
	background-color: #fff;
	border-radius: 5px;
	box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
	}

	h1 {
	font-size: 32px;
	margin-bottom: 20px;
	}

	p {
	font-size: 18px;
	margin-bottom: 20px;
	}

	.code {
	font-size: 100px;
	color: #e74c3c;
	margin-bottom: 20px;
	}
	</style>
	<div class="container">
	<h1>Invalid Access</h1>
	<div class="code">Error</div>
	<p>
	You have accessed this page incorrectly or without providing the necessary information.
	</p>
	<p>
	Please ensure you use the provided links and provide all required parameters.
	</p>
	</div>
	_HTML;

require_once('../base.php');
?>

