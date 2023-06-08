<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>
		<?php if ($title) echo $title; ?> - MediHelp PLC
		</title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" crossorigin="anonymous" />
		<link href = "bootstrap.min.css" rel = "stylesheet">
		<link rel="icon" href="images/favicon.png" type="image/x-icon">
		<style>

		/* general settings */
		body {
		display: flex;
		flex-direction: column;
		min-height: 100vh;
		margin: 0;
		}

		.container {
		flex-grow: 1;
		}

		/* navigation bar settings */
		.navbar {
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding: 20px;
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
		}

		.logo {
		flex: 1;
		}

		.logo a {
		text-decoration: none;
		font-size: 24px;
		color: #333;
		}

		.nav-links {
		flex: 1;
		display: flex;
		justify-content: center;
		list-style: none;
		padding: 0;
		}

		.nav-links li {
		margin-right: 20px;
		font-size: 1.4em;
		}

		.nav-links a {
		text-decoration: none;
		color: #333;
		}

		.auth-links a {
		text-decoration: none;
		color: #333;
		padding: 8px 12px;
		margin-right: 10px;
		border: 1px solid #333;
		border-radius: 5px;
		}

		@media (max-width: 700px)
		{
		.navbar {
		flex-direction: column;
		align-items: flex-start;
		}

		.nav-links {
		display: none;
		flex-direction: column;
		margin-top: 10px;
		}

		.nav-links li {
		margin-right: 0;
		margin-bottom: 10px;
		}

		.auth-links {
		margin-top: 10px;
		}

		.nav-toggle {
		display: block;
		background-color: #333;
		color: none;
		padding: 8px 12px;
		border-radius: 5px;
		cursor: pointer;
		}
		}

		/* footer settings */
		footer {
		background-color: #333;
		color: #fff;
		padding: 20px;
		text-align: center;
		margin-top: 3%;
		}

		footer p {
		margin: 0;
		}

		.developer {
		font-weight: bold;
		}
		</style>
	</head>
	<body>
		<nav class="navbar">
			<div class="logo">
				<a href="">MediHelp PLC</a>
			</div>
			<ul class="nav-links">
				<li><a href="">Home</a></li>
				<li><a href="">About Us</a></li>
				<li><a href="">Blog</a></li>
				<li><a href="">Contact Us</a></li>
				<li><a href="">Gallery</a></li>
			</ul>
			<div class="auth-links">
				<?php 
				if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']===true)
				{
					echo "<a href='../authentication/logout.php'>Sign Out</a>";
				}
				else
				{
					echo "<a href='../authentication/login.php'>Sign In</a>";
					echo "<a href='../registration/register_patient.php'>Sign Up</a>";
				}
				?>
			</div>
		</nav>
		<hr>
		<div class = "container">
			<?php echo $content; ?>
		</div>
		<footer>
			<p>
			&copy; <?php echo date('Y'); ?> All Rights Reserved - Developed by
			<span class = "developer">AKN Enterprises</span>
			</p>
		</footer>
<script>
document.addEventListener('DOMContentLoaded', function () {
	var navToggle = document.createElement('button');
	navToggle.className = 'nav-toggle';
	navToggle.innerHTML = "<i class = 'fas fa-bars'></i>";

	var navbar = document.querySelector('.navbar');
	//navbar.appendChild(navToggle);

	var navLinks = document.querySelector('.nav-links');
	var authLinks = document.querySelector('.auth-links');

	navToggle.addEventListener('click', function () {
		navLinks.classList.toggle('show');
		authLinks.classList.toggle('show');
	});

	// Check screen width on page load
	if (window.innerWidth <= 700)
	{
		navToggle.style.display = 'block';
		navLinks.style.display = 'none';
		authLinks.style.display = 'none';
	}

	// Check screen width on window resize
	window.addEventListener('resize', function() {
		if (window.innerWidth <= 700)
		{
			navToggle.style.display = 'block';
			navLinks.style.display = 'none';
			authLinks.style.display = 'none';
		} else {
			navToggle.style.display = 'none';
			navLinks.style.display = 'flex';
			authLinks.style.display = 'flex';
		}
	});
});
</script>
	</body>
</html>
