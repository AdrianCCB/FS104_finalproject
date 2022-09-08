<?php 
include('core/config.php');
include('core/db.php');
include('core/functions.php');

$formEmail = $formPassword = $userPassword = "";
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
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Sacramento&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap"
        rel="stylesheet">
    <?php include 'templates/styles.php'; ?>
</head>

<body>
    <!-- Navbar -->
    <section id="top-bar">
        <nav class="navbar navbar-expand-md mb-4">
            <div class="container-fluid">
                <div>
                    <img class="chef-hat" src="images/chef_hat.png" alt="">
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
                            <a class="nav-link active" href="faq.php">FAQ</a>
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

    <!-- start of FAQ section -->
    <section id="faq">
        <div class="py-5 text-center container">
            <!-- Heading -->
            <h1>FAQ</h1>
            <span class="lead text-muted">Do you have any question?</span> <br>
            <span class="lead text-muted">Below are some common questions and answers that might helps.</span>
        </div>

        <div class="container-fluid">
            <div class="row">
                <!-- start of FAQ question 1 -->
                <div class="faq-question">
                    <!-- question 1-->
                    <div class="question-title">
                        <h4>How much is the class packages?</h4>
                        <button type="button" class="question-btn">
                            <span class="plus-icon">
                                <i class="fa-regular fa-square-plus"></i>
                            </span>
                            <span class="minus-icon">
                                <i class="far fa-minus-square"></i>
                            </span>
                        </button>
                    </div> 
                    <!-- answer 1-->
                    <div class="faq-answer">
                        <p class="text-muted">You may refer to "Packages" for more information.</p> 
                    </div>
                </div>
                <!-- end of FAQ question 1 -->

                <hr class="hr-line">

                 <!-- start of FAQ question 2 -->
                 <div class="faq-question">
                    <!-- question 2 -->
                    <div class="question-title">
                        <h4>Do you have class for 1 on 1?</h4>
                        <button type="button" class="question-btn">
                            <span class="plus-icon">
                                <i class="fa-regular fa-square-plus"></i>
                            </span>
                            <span class="minus-icon">
                                <i class="far fa-minus-square"></i>
                            </span>
                        </button>
                    </div>
                    <!-- answer 2 -->
                    <div class="faq-answer">
                        <p class="text-muted">Yes, we do have 1 on 1 class. Do refer to "Packages" for more information.</p> 
                    </div>
                </div>
                <!-- end of FAQ question 2 -->

                <hr class="hr-line">

                <!-- start of FAQ question 3 -->
                <div class="faq-question">
                    <!-- question 3 -->
                    <div class="question-title">
                        <h4>Where the class will be held?</h4>
                        <button type="button" class="question-btn">
                            <span class="plus-icon">
                                <i class="fa-regular fa-square-plus"></i>
                            </span>
                            <span class="minus-icon">
                                <i class="far fa-minus-square"></i>
                            </span>
                        </button>
                    </div>    
                    <!-- answer 3 -->
                    <div class="faq-answer show-text">
                        <p class="text-muted">We are located at Chinatown Complex.</p> 
                    </div>
                </div>
                <!-- end of FAQ question 3 -->

                <hr class="hr-line">

                <!-- start of FAQ question 4 -->
                <div class="faq-question">
                    <!-- question 4 -->
                    <div class="question-title">
                        <h4>Are there any sales on going?</h4>
                        <button type="button" class="question-btn">
                            <span class="plus-icon">
                                <i class="fa-regular fa-square-plus"></i>
                            </span>
                            <span class="minus-icon">
                                <i class="far fa-minus-square"></i>
                            </span>
                        </button>
                    </div>    
                    <!-- answer 4 -->
                    <div class="faq-answer show-text">
                        <p class="text-muted">Yes! Do subscribe our newsletter for more promotions.</p> 
                    </div>
                </div>
                <!-- end of FAQ question 4 -->

                <hr class="hr-line">   
            </div>
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