<?php 
include('core/config.php');
include('core/db.php');
include('core/functions.php');

$formEmail = $formPassword = "";
$formEmailError = $formPasswordError = "";
$formEmailPass = $formPasswordPass = "";

# when there is POST login value
if(isset($_POST['login'])){
    $formEmail = input_validation($_POST['formEmail']);
    $formPassword = input_validation($_POST['formPassword']);

// Start of validation for the form
    # validate E-mail
    if (empty($formEmail)) {
        $formEmailError = "Email is required";
    } else if (!filter_var($formEmail, FILTER_VALIDATE_EMAIL)) {
        $formEmailError = "Invalid email format";
    } else {
        $formEmailPass = true;
    }

    # validate Password 
    if (empty($formPassword)){
        $formPasswordError = "Password is required";
    } else {
        $formPasswordPass = true;
    }

    #when both email and password is true
    if ($formEmailPass && $formPasswordPass == true){
        $userQuery = DB::query("SELECT * FROM user WHERE userEmail = %s", $formEmail);
        $userCount = DB::count();
        
        if($userCount == 1){
            foreach($userQuery as $userResult){
                $userID = $userResult["userID"];
                $userPassword = $userResult['userPassword'];
            }
            if(password_verify($formPassword, $userPassword)){
                # If Password matches
                # Set cookies
                setCookies($userID);
                # set alert1 function to welcome user
            } else {
                echo "<script>alert('Email and/or password mismatch');</script>";
            }
        }
    }
}

# if the user has logged in, find the email, name, phone and img from the DB
if(isLoggedIn()){
  $userID = $_COOKIE["userID"];
  $userQuery = DB::query("SELECT * FROM user WHERE userID = %i", $userID);
    foreach($userQuery as $userResult){
      $userEmail = $userResult["userEmail"];
      $userName = $userResult["userName"];
      $userPhone = $userResult["userPhone"];
      $userImage = $userResult["userImage"];
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'templates/meta.php'; ?>
  <title>Chef Cook</title>
  <?php include 'templates/styles.php'; ?>
  
</head>

<body>
  <!-- Navbar -->
  <section id="top-bar">
    <nav class="navbar navbar-expand-md mb-4">
      <div class="container-fluid">
        <div>
          <img class="chef-hat" src="assests/images/chef_hat.png" alt="">
          <a class="navbar-brand" href="index.php">Chef Cook</a>
      </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
          aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="line"></span> 
          <span class="line"></span> 
          <span class="line"></span>
          <span class="line"></span>
        </button>
        <div class="collapse nav-masthead navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav me-auto mb-2 mb-md-0">
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="recipes.php">Recipes</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="packages.php">Packages</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contact.php">Contact</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="faq.php">FAQ</a>
            </li>
          </ul>
          <!--button inside index-profile.php or index-login.php -->

          <!-- Login form / Profile php-->
          <?php 
            if(isLoggedIn()){
              include 'profile-icon.php';    # if user has logged in, load the profile icon 
            } else {
              include 'login-icon.php';      # otherwise, load the login form 
            }
          ?>
        </div>
      </div>
    </nav>
  </section>
  
 
  <!-- pop out subscription -->
  <?php 
    if(!isLoggedIn()){
      include 'pop-subscription.php';    # if user has logged in, load the pop out subscription 
    }
  ?>


  <!-- about me -->
  <section id="my-information">
    <div class="container-fluid py-5">
      <div class="row">
        <!-- a person figure image -->
        <div class="col-lg-4 col-md-3 profile-image-div">
          <img class="profile-image" src="assests/images\962px-Person_icon_BLACK-01.svg.png" alt="">
        </div>
        <!-- description on right -->
        <div class="col-lg-8 col-md-9">
          <h1 class="display-5 big-title">Adrian Chai <span class="small-title"> <em> Founder of Chef
                Cook</em></span></h1>
          <p class="col-md-8 fs-4">Started Chef Cook to help everyone to become a master chef. Explore the fun of cooking with our professionals. </p>
          <!-- Buttons -->    
          <a class="remove-hyperlink" href="packages.php">
            <button class="btn btn-primary" type="button">Book a class <i class="fas fa-arrow-circle-right"></i></button>
          </a>
          <a class="remove-hyperlink" href="contact.php">
            <button class="btn btn-primary" type="button">Contact us <i class="fas fa-arrow-circle-right"></i></button>
          </a>
        </div>
      </div>
    </div>
  </section>


  <!-- Posting a youtube video -->
  <section id="video-screen">
    <div class="video-outer">
      <div class="video-inner">
        <iframe width="1280" height="720" src="https://www.youtube.com/embed/gkAC0HoAVoA" title="YouTube video player"
          frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen>
        </iframe>
      </div>
    </div>
  </section>


  <!-- Testimonials -->
  <section id="testimonials">
    <!-- Create fade background-image -->
    <div class="testimonials-background">
    </div>
    <div class="testimonials-content">

      <h1>Testimonials</h1>
      <hr class="featurette-divider">

      <!-- testimonial 1 -->
      <div class="container-fluid">
        <div class="row align-items-center">
          <div class="col-lg-7">
            <h2 class="text-muted">A gem of a cooking experience</h2>
            <p class="lead"><em>We signed up for an Indian meal class: prata, fish curry and dhal. Cook Chef has many different culinary recipes to learn so you have lots of choices, it was hard to just choose one. Highly recommended.</em></p>
          </div>
          <div class="col-lg-5 align-items-stretch d-flex">
            <img src="assests/images/happy-class.jpg" alt="happy-class">
          </div>
        </div>
      </div>

      <hr class="featurette-divider">

      <!-- testimonial 2 -->
      <div class="container-fluid">
        <div class="row align-items-center">
          <div class="col-lg-7 order-lg-2 order-md-1 order-sm-1">
            <h2 class="text-muted">A unique cooking class</h2>
            <p class="lead"><em>I’m a cooking lover and I’ve already done many other classes before. I really recommend to spend some hours in this peaceful class, to learn more about local cuisine and about the wish of share with others</em></p>
          </div>
          <div class="col-lg-5 order-lg-1 order-md-2 order-sm-2 align-items-stretch d-flex">
            <img src="assests/images/happy-class2.jpg" alt="happy-class2">
          </div>
        </div>
      </div>

      <hr class="featurette-divider">

      <!-- testimonial 3 -->
      <div class="container-fluid">
        <div class="row align-items-center">
          <div class="col-lg-7">
            <h2 class="text-muted">Excellent authentic cooking that’s so delicious!</h2>
            <p class="lead"> <em> Had a great experience at Chef Cook, the food was delicious and something I feel I can recreate at home. Loved the outdoor kitchen and all of the fruit growing in the garden. Great day out.</em></p>
          </div>
          <div class="col-lg-5 align-items-stretch d-flex">
            <img src="assests/images/happy-class3.png" alt="happy-class3">
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- features -->
  <section id="features">
    <div>
      <h1 class="pb-4 border-bottom ps-4">Features</h1>
    </div>

      <!-- feature placeholder -->
      <div class="container-fluid pt-3 ">
        <div class="row align-items-center  ">

          <!-- Food king feature -->
          <div class="col-lg-4 col-md-4">
            <div>
              <img class="feature-icon-resize" src="assests/images/food-king-removebg-preview.png" alt="food-king">
          </div>
          <div class="mt-auto">
            <span>⭐⭐⭐</span>
            <h5>Food King Good</h5>
            </div>
          </div>

          <!-- CNA feature -->
          <div class="col-lg-4 col-md-4">
            <div>
              <img class="feature-icon-resize" src="assests/images/unnamed-removebg-preview.png" alt="cna">
            </div>
            <div class="mt-auto">
              <span>⭐⭐⭐⭐⭐</span>
              <h5>CNA 5.0 reviews </h5>
            </div>
          </div>

          <!-- Smart local feature -->
          <div class="col-lg-4 col-md-4 ">
            <div>
              <img class="feature-icon-resize" src="assests/images/smart-local-removebg-preview.png" alt="loaded-breakfast">
            </div>
            <div class="mt-auto">
              <span>⭐⭐⭐⭐⭐</span>
             <h5>Highly Rate Activity</h5>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  <!-- social media -->
  <section id="social-media">
    <div>
      <h1 class="pb-4 border-bottom ps-4">Social Media</h1>
    </div>
    <div class="row">
      <!-- counter for Twitter -->
      <div class="col-lg-4 col-md-4 pb-2 pt-4">
        <a href="https://twitter.com/?lang=en"><i class="fab fa-twitter fa-3x"></i></a>
        <div class="counter" data-target="6748">0</div>
        <span>Twitter Followers</span>
      </div>
      <!-- counter for Instagram -->
      <div class="col-lg-4 col-md-4 pb-2 pt-4">
        <a href="https://www.instagram.com/"><i class="fab fa-instagram fa-3x"></i></a>
        <div class="counter" data-target="7892">0</div>
        <span>Instagram Followers</span>
      </div>
      <!-- counter for Facebook -->
      <div class="col-lg-4 col-md-4 pt-4">
        <a href="https://www.facebook.com/"><i class="fab fa-facebook fa-3x"></i></a>
        <div class="counter" data-target="12030">0</div>
        <span>Facebook Followers</span>
      </div>
    </div>
  </section>

  <!-- Subscribe -->
  <section id="subscribe">
    <div class="container-fluid">
      <div>
        <h1>Subscribe</h1>
        <p class="pb-1 border-bottom">Get the latest updates</p>
      </div>
     
      <!-- form -->
      <form class="needs-validation" novalidate>
        <div class="row">
          <!-- name input -->
          <div class="col-lg-6 col-md-6">
            <input type="text" class="form-control" id="firstName" placeholder="Name" value="" required>
            <div class="invalid-feedback">
              Valid name is required.
            </div>
          </div>
          <!-- email input -->
          <div class="col-lg-6 col-md-6 ">
            <input type="email" class="form-control" id="email" placeholder="abc@example.com" required>
            <div class="invalid-feedback">
              Please enter a valid email address.
            </div>
          </div>
          <!-- Subscribe button -->
          <div class="col-md-12 mt-2">
            <button class="btn btn-primary" type="submit">Subscribe</button>
          </div>
      </form>
    </div>
  </section>


  <!-- Footer -->
    <?php include 'templates/footer.php'; ?>

  <!-- Go to top button -->
  <button id="goTopBtn" title="Go to top" >
    <i class="fas fa-angle-up fa-lg"> <p>Top</p></i>
  </button>

  <!-- Javascript -->
  <?php include 'templates/script.php'; ?>

  <!-- Sweetalert -->
  <?php
     if(password_verify($formPassword, $userPassword)){
        alert1('success', 'Welcome back', '', false, '1500', 'index.php');
    }
  ?>

</body>

</html>