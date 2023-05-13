<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $title; ?></title>
		<link href = "bootstrap.min.css" rel = "stylesheet">
		<link href = "../static/css/styles.css" rel = "stylesheet">
	</head>
	<body class = "container">
		<header>
			<?php
			if (isset($heading))
			{
				echo "<h1>$heading</h1>";
			}
			?>
		</header>
		<div class = "content">
			<?php echo $content; ?>
		</div>
		<footer>
			<p>&copy; <?php echo date('Y'); ?>My Website</p>
		</footer>
	</body>
</html>
