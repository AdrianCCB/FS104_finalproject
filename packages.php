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
    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" sizes="32x32" href="assests/images/favicon-32x32.png">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Sacramento&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap"
        rel="stylesheet">
    <link 
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" 
        rel="stylesheet">
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
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
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
                            <a class="nav-link active" href="packages.php">Packages</a>
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
        
    <!-- Login form -->
    <section id="login" class="hide-login"> 
        <div class="row">
            <!-- login column -->
            <div class="col-md-6 login-column d-flex flex-column">
                <div>
                    <h5>Login</h5>
                </div>
                <form class=" mt-auto">
                    <i class="far fa-times-circle close-login fa-lg"></i>
                    <div class="mb-2">
                        <input type="email" placeholder="Email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                    </div>
                    <div class="mb-2">
                        <input type="password" placeholder="Password" class="form-control" id="exampleInputPassword1">
                    </div>
                    <button type="submit" class="btn btn-danger">Login</button>
                </form>
            </div>
            <!-- register column -->
            <div class="col-md-6 register-column d-flex flex-column"> 
                <div>
                    <h5>Register</h5>
                </div>
                <div class="register-content">
                    <span>New User? Register now to start your jouney with us</span> 
                </div>
                <div class="register-btn mt-auto">
                    <button type="submit" class="btn btn-warning">Register</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Price Packages -->
    <section id="packages">
        <div class="py-4 text-center container">
            <h1>Class Packages</h1>
            <span class="lead text-muted">A Plan for your needs</span> <br>
            <span class="lead text-muted">Join us on the adventure to Master Chef</span>
        </div>

     <!-- Offer -->
        <div class="container-fluid">
            <div class="sales row text-center">
                <!-- sales heading -->
                <div>
                    <div class="sales-heading">
                        <span class="offer-title">Limited Time Offer</span> <br>
                        <span>Discount for both <span class="promo-heading">Basic</span> and <span class="promo-heading">Advance</span> packages</span> <br>
                        <span>Offer ends in</span>
                    </div>
                </div>
                <!-- sales expired -->
                <div class="expired-heading hidden-text">
                    <h2>Sales has expired.</h2>
                    <span>Stay tune for more upcoming sales.</span><br>
                    <span>Do subscribe our newsletter for more updates.</span>
                </div>
            </div>
            <!-- countdown-timer -->
            <div class="countdown-timer">
                <div class="timer-style">
                    <h4 class="dates"></h4>
                    <span>Days</span>
                </div>
                <div class="timer-style">
                    <h4 class="hours"></h4>
                    <span>Hours</span>
                </div>
                <div class="timer-style">
                    <h4 class="mins"></h4>
                    <span>Mins</span>
                </div>
                <div class="timer-style try">
                    <h4 class="secs"></h4>
                    <span>Secs</span>
                </div>
            </div>
        </div> 


        <div class="container-fluid">
            <div class="row text-center">
                <!-- Basic plan -->
                <div class="col-lg-4 col-md-6 d-flex justify-content-center package">
                    <div class="card mb-2 mt-2 rounded-3 shadow-sm pricing-card">
                        <div class="card-header py-3">
                            <h3 class="my-0 fw-normal">Basic</h3>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div>
                                <h3 class="card-title pricing-card-title">S$210</small></h3>
                                <span class="orginal-price">$300</span><span class="discount-percent">(Save 30%)</span>
                                <img class="discount-tag-img" src="assests/images/discount-tag.png" alt="discount-tag">
                            </div>
                            <div>
                                <ul class="list-unstyled mt-3 mb-4">
                                    <li>5 Courses</li>
                                    <li>All ingredients are provided</li>
                                    <li>Welcome gifts</li>
                                </ul>
                            </div>
                            <button type="button" class="btn  btn-outline-info pricing-button mt-auto">Sign up</button>
                        </div>
                    </div>
                </div>
                <!-- Advance plan -->
                <div class="col-lg-4 col-md-6 d-flex justify-content-center package">
                    <div class="card mb-2 mt-2 rounded-3 shadow-sm pricing-card">
                        <div class="card-header py-3">
                            <h3 class="my-0 fw-normal">Advance</h3>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div>
                                <h3 class="card-title pricing-card-title">S$410</small></h3>
                                <span class="orginal-price">$300</span><span class="discount-percent">(Save 30%)</span>
                                <img class="discount-tag-img" src="assests/images/discount-tag.png" alt="discount-tag">
                            </div>
                            <ul class="list-unstyled mt-3 mb-4">
                                <li>6 Courses</li>
                                <li>All ingredients are provided</li>
                                <li>Welcome gifts</li>
                                <li>Free Chef Cook's Book</li>
                            </ul>
                            <button type="button" class="btn btn-outline-info pricing-button mt-auto">Sign Up</button>
                        </div>
                    </div>
                </div>
                <!-- Private class plan -->
                <div class="col-lg-4 d-flex justify-content-center package">
                    <div class="card mb-2 mt-2 rounded-3 shadow-sm pricing-card">
                        <div class="card-header py-3">
                            <h3 class="my-0 fw-normal">Private</h3>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h3 class="card-title pricing-card-title">S$600</h3>
                            <ul class="list-unstyled mt-3 mb-4">
                                <li>1-on-1 private class</li>
                                <li>All ingredients are provided</li>
                                <li>Welcome gifts</li>
                                <li>Free Chef Cook's Book</li>
                            </ul>
                            <button type="button" class="btn btn-outline-info pricing-button mt-auto">Sign Up</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <!-- Photo Gallery -->
    <Section id="photo-gallery">
        <div>
            <h2 class="pb-4 border-bottom ps-4">Photo Galleries</h2>
            <br>
        </div>
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3"
                aria-label="Slide 4"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="4"
                aria-label="Slide 5"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="assests/images/class1.jpg" class="d-block w-100" alt="classphoto1">
                    <div class="carousel-caption d-none d-md-block bg-color">
                        <em>"Friendly teacher guides you along"</em>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="assests/images/class2.jpg" class="d-block w-100" alt="classphoto2">
                    <div class="carousel-caption d-none d-md-block bg-color">
                        <em>"Fresh ingredients for your class"</em>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="assests/images/class3.jpg" class="d-block w-100" alt="classphoto3">
                    <div class="carousel-caption d-none d-md-block bg-color">
                        <em>"Interesting kitchen concept"</em>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="assests/images/class5.jpg" class="d-block w-100" alt="classphoto4">
                    <div class="carousel-caption d-none d-md-block bg-color">
                        <em>"It is a joy to cook with everyone"</em>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="assests/images/class4.jpg" class="d-block w-100" alt="classphoto5">
                    <div class="carousel-caption d-none d-md-block bg-color">
                        <em>"Professional and knowledgeble"</em>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </Section>


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