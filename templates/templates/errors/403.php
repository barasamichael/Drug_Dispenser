<?php
session_start();

$title = "Forbidden";
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
	<h1>403 Forbidden</h1>
	<div class="code">403</div>
	<p>Oops! Access to this resource is forbidden.</p>
	<p>
	Please contact the administrator for assistance or go back to the 
	<a href="/">homepage</a>.</p>
	</div>
	_HTML;

require_once('../base.php');
?>
