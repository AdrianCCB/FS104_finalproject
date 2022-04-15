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
                            <a class="nav-link" href="packages.php">Packages</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="contact.php">Contact</a>
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
                        <input type="email" placeholder="Email" class="form-control" id="exampleInputEmail2" aria-describedby="emailHelp">
                    </div>
                    <div class="mb-2">
                        <input type="password" placeholder="Password" class="form-control" id="exampleInputPassword2">
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


    <!-- Contact us -->
    <section id="contact-us">
        <div class="py-5 text-center container">
            <h1>Contact Us</h1>
            <span class="lead text-muted">Do not keep questions with you</span> <br>
            <span class="lead text-muted">Feel free to contact us and we will help you along</span>
        </div>
        <div class="contact-us-bg container-fluid">
            <div class="row align-items-center align-self-center">
                <!-- google map -->
                <div class="col-lg-8 col-md-12 align-middle">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15955.279189087787!2d103.83537423594773!3d1.2818853374254862!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31da1972d6fff445%3A0x1792d5c0b75b87c9!2sSingapore%20059020!5e0!3m2!1sen!2ssg!4v1637781471061!5m2!1sen!2ssg"
                        width="100%" height="500" style="border:1;" allowfullscreen="" loading="lazy"></iframe>
                </div>

                <!-- Contact column -->
                <div class="col-lg-4">
                    <!-- location -->
                    <div class="address">
                        <div class="location-icon">
                            <span><i class="fas fa-street-view fa-2x"> Location</i></span> 
                        </div>
                        <div>
                            <h4>Chinatown Complex</h4>
                            <span>24A Sago St, Singapore 059020</span> <br>
                            <a href="https://www.google.com.sg/maps/place/Singapore+059020/@1.2818853,103.8353742,15z/data=!3m1!4b1!4m5!3m4!1s0x31da1972d6fff445:0x1792d5c0b75b87c9!8m2!3d1.2818639!4d103.844129">Get directions<i class="fas fa-map-marked-alt icon-lp"></i></a>
                        </div>
                    </div>

                    <hr>
                    <!-- Contact number -->    
                    <div class="contact-no">
                        <div class="phone-icon">
                            <span><i class="fas fa-phone fa-2x"> Phone</i></span><br>
                            <a class="remove-hyperlink" href="tel:6123 4567">
                                <span class="h4">+65 6123 4567</span>
                            </a>
                        </div>
                        <div>
                            <h4>Phone Operating Hours</h4>
                            <Span>Monday to Friday, 08:30AM to 5:30PM</Span> <br>
                            <span>Saturday, 8:30AM to 1PM</span> <br>
                            <span>(Excluding Public Holiday)</span>
                        </div>
                    </div>

                    <hr>
                    <!-- Email -->
                    <div class="email">
                        <div class="email-icon">
                           <span><i class="fas fa-envelope fa-2x"> Email</i></span>
                        </div>
                        <div>
                            <a class="remove-hyperlink" href="mailto:chef_cook@gmail.com">
                                <Span>chef_cook@gmail.com</Span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </section>


    <!-- contact form -->
    <section id="contact-form">
        <div class="container-fluid">
            <div  class="pb-4 border-bottom">
                <h2>Contact Form</h2>
                <span class="lead text-muted">Leave us your contact and we will get back to you.</span>
            </div>
            
            <div class="form">
                <form id="contactForm" >
                    <div class="row g-3">
                        <!-- Name -->
                        <div class="col-lg-12">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="contactName" placeholder="Name" value="" >
                          
                        </div>
                        
                        <!-- Email address -->
                        <div class="col-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="contactEmail" placeholder="abc@example.com" value="" >
                       
                        </div>
                        <!-- Phone number -->
                        <div class="col-12">
                            <label for="phone" class="form-label">Phone number</label>
                            <input type="text" class="form-control" id="contactPhone" placeholder="+65" value="" >
                           
                        </div>
                    </div>
                    <br>
                    <!-- Submit button -->
                    <button id="submitContact" class="w-100 btn btn-primary btn-lg form-submit" type="submit">Submit</button>
                </form>
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

    <!-- Jquery Ajax -->
    <!-- when the contact form is submitted -->
    <script>
    $(document).ready(function(){
        $("#contactForm").submit(function(e){
            e.preventDefault(e);
        });

        $("#submitContact").click(function(){
            var contactName = $("#contactName").val();
            var contactEmail = $("#contactEmail").val();
            var contactPhone = $("#contactPhone").val();

            $.ajax({
                url: 'ajax-contact.php', //action
                method: 'POST', //method
                data:{
                    name: contactName,
                    email: contactEmail,
                    phone: contactPhone,
                },
                success:function(data){
                    if (data != true){
                        Swal.fire({
                        icon: 'error',
                        title: 'Error.',
                        text: data
                        });
                    } else {
                        Swal.fire({
                        icon: 'success',
                        title: 'Submitted',
                        text: 'We will contact you shortly'
                        });
                        // reset value
                        $("#contactName").val('');
                        $("#contactPhone").val('');
                        $("#contactEmail").val('');
                    }
                }             
            });
        });
    });
    </script>

    <!-- Sweetalert -->
    <?php
        if(password_verify($formPassword, $userPassword)){
            alert1('success', 'Welcome back', '', false, '1500', 'index.php');
        }
    ?>

</body>

</html>