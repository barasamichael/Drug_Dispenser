<?php
$title = "Home";
$content = <<<_HTML
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" />
	<!-- Hero Section -->
	<section class="hero-section">
	<div class="container">
	<div class="row align-items-center justify-content-center">
	<div class="col-md-8 col-lg-6">
	<h1 class="text-center mb-4">Transforming Healthcare<br>Together</h1>
	<p class="lead text-center mb-5">
	We are a healthcare company that provides innovative solutions to patients, pharmacies, 
	pharmaceutical companies, hospitals, and more.
	</p>
	<div class="text-center">
	<a class="btn btn-primary btn-lg me-3" href="#about" role="button">Learn More</a>
	<a class="btn btn-outline-primary btn-lg" href="#contact" role="button">Contact Us</a>
	</div>
	</div>
	<div class="col-md-8 col-lg-6">
	<img src="https://c1.wallpaperflare.com/preview/787/472/886/test-tube-lab-medical.jpg" class="img-fluid" alt="Hero Image">
	</div>
	</div>
	</div>
	</section>

	<!-- About Us Section -->
	<section id="about" class="about-section bg-light">
	<div class="container">
	<div class="row align-items-center">
	<div class="col-md-6 col-lg-5">
	<img src="https://yourlocal-pharmacy.co.uk/wp-content/uploads/elementor/thumbs/out.-2-scaled-q3jb7bqbkem59c3wjtnofn0qynkdxrmo1tj6es9co2.jpeg" class="img-fluid mb-4" alt="About Image">
	</div>
	<div class="col-md-6 col-lg-7">
	<h2>About Us</h2>
	<p class="lead mb-4">
	We are a team of healthcare professionals who are passionate about improving the lives 
	of patients and making healthcare more accessible and affordable for everyone.
	</p>
	<p class="mb-5">
	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse aliquam semper est, 
	vitae tempor enim auctor eu. Suspendisse et sapien ac massa tristique tincidunt. Integer 
	vestibulum lectus sit amet massa pulvinar, ac ultrices mauris bibendum. Curabitur iaculis 
	felis felis, vel ullamcorper elit pharetra quis. Donec eget velit sit amet magna 
	consectetur interdum eu ac nunc. Praesent semper bibendum ex, quis blandit massa laoreet 
	vel.
	</p>
	<a class="btn btn-primary" href="#services" role="button">Our Services</a>
	</div>
	</div>
	</div>
	</section>

	<!-- Services Section -->
	<section id="services" class="services-section">
	<div class="container">
	<div class="row justify-content-center">
	<div class="col-lg-8">
	<div class="text-center mb-5">
	<h2>Our Services</h2>
	<p class="lead">
	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ultrices odio ac lacinia 
	maximus. Nulla vel lectus et neque semper aliquet.
	</p>
	</div>
	</div>
	</div>
	<div class="row">
	<div class="col-md-4">
	<div class="card mb-4 mb-md-0">
	<div class="card-body">
	<h5 class="card-title">Patient Services</h5>
	<p class="card-text">
	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ultrices odio ac lacinia 
	maximus.
	</p>
	</div>
	</div>
	</div>
	<div class="col-md-4">
	<div class="card mb-4 mb-md-0">
	<div class="card-body">
	<h5 class="card-title">Pharmacy Services</h5>
	<p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ultrices odio ac lacinia maximus.</p>
	</div>
	</div>
	</div>
	<div class="col-md-4">
	<div class="card">
	<div class="card-body">
	<h5 class="card-title">Hospital Services</h5>
	<p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ultrices odio ac lacinia maximus.</p>
	</div>
	</div>
	</div>
	</div>
	</div>
	</section>
	<!-- Join Us Section -->
	<section id="join" class="join-section">
	<div class="container">
	<div class="row">
	<div class="col-md-12 text-center">
	<h2>Join Us Today</h2>
	<p class="lead">We welcome pharmaceutical companies, pharmacies, hospitals, and the general public to join us in transforming healthcare together. Together, we can make a difference.</p>
	<div class="d-flex justify-content-center align-items-center flex-wrap mt-5">
	<a class="btn btn-primary btn-lg me-3 mb-3" href="https://pharmaceuticalcompanyregistration.com" role="button">Pharmaceutical Companies Registration</a>
	<a class="btn btn-primary btn-lg me-3 mb-3" href="https://pharmacyregistration.com" role="button">Pharmacies Registration</a>
	<a class="btn btn-primary btn-lg me-3 mb-3" href="https://hospitalregistration.com" role="button">Hospitals Registration</a>
	<a class="btn btn-primary btn-lg mb-3" href="https://generalpublicregistration.com" role="button">General Public Registration</a>
	</div>
	</div>
	</div>
	</div>
	</section>

	<!-- Contact Us Section -->
	<section id="contact" class="contact-section bg-light">
	<div class="container">
	<div class="row justify-content-center">
	<div class="col-lg-8">
	<div class="text-center mb-5">
	<h2>Contact Us</h2>
	<p class="lead">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ultrices odio ac lacinia maximus.</p>
	</div>
	</div>
	</div>
	<div class="row">
	<div class="col-md-6">
	<form action="" method="post">
	<div class="form-group mb-3">
	<label for="name">Name</label>
	<input type="text" class="form-control" id="name" name="name" required>
	</div>
	<div class="form-group mb-3">
	<label for="email">Email</label>
	<input type="email" class="form-control" id="email" placeholder="name@example.com">
	</div>
	<div class="mb-3">
	<label for="message" class="form-label">Message</label>
	<textarea class="form-control" id="message" rows="3"></textarea>
	</div>
	<button type="submit" class="btn btn-primary">Submit</button>
	</form>
	</div>
	</div>
	</div>
	</section>
	_HTML;

include_once('../base.php');
?>
