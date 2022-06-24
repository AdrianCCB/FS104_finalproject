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
    <link rel="icon" type="image/png" sizes="32x32" href="assests/images/favicon-32x32.png">
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
                            <a class="nav-link active" aria-current="page" href="recipes.php">Recipes</a>
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

    <!-- Recipes Introduction -->
    <section id="recipes-intro">
        <div class="py-5 text-center container">
            <h1>Recipes</h1>
            <span class="lead text-muted">We got your back!</span> <br>
            <span class="lead text-muted"> Here you have all the secrets to your wonderful meals!</span>
        </div>
    </section>


    <!-- latest Recipes list -->
    <section id="recipes-list">
        <div class="container-fluid">
            <h2 class="pb-2 border-bottom ps-4">Latest Recipes</h2>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 pt-3">

            <!-- latest recipe retrieving from DB-->
            <?php         
                $recipeQuery = DB::query("SELECT * FROM recipe");
                foreach($recipeQuery as $recipeResult){
                    $recipeID = $recipeResult["recipeID"];
                    $recipeName = $recipeResult["recipeName"];
                    $recipeCuisine = $recipeResult["recipeCuisine"];
                    $recipeOccassions = $recipeResult["recipeOccassions"];
                    $recipeImage = $recipeResult["recipeImage"];
                    $recipeCourse = $recipeResult["recipeCourse"];
                    $recipeDescription = $recipeResult["recipeDescription"];
            ?>
                <div class="col align-items-stretch d-flex">
                    <div class="card shadow-sm recipe-card">
                        <img class="bd-placeholder-img card-img-top placeholder-resize" src="<?php echo $recipeImage ?>"
                            alt="<?php echo $recipeName ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-text"><?php echo $recipeName ?></h5>
                            <p class="card-text"><?php echo $recipeDescription ?></p>
                            <div class="mt-auto">
                                <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
                                <i class="fas fa-heart fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    }
                ?>
            </div>
        </div>
    </section>


    <!-- Thanksgiving list of food -->
    <section id="thanksgiving-list">
        <!-- thanksgiving receipes header -->
        <div>
            <h2 class="pb-2 border-bottom thanksgiving-header ps-4">Thanksgiving Recipes</h2>
        </div>
        <!-- food placeholder -->
        <div class="container-fluid pt-3">
            <div class="row">
                <!-- thanksgiving food 1 -->
                <div class="col-lg-3 col-md-6">
                    <a href="#">
                        <div>
                            <img class="placeholder-resize1" src="assests/images/show-stopping.jpg" alt="show-stopping">
                        </div>
                        <div>
                            <h5 class="thanksgiving-list-title">Show-Stiooing Beef Wellington</h5>
                        </div>
                    </a>
                </div>
                <!-- thanksgiving food 2 -->    
                <div class="col-lg-3 col-md-6">
                    <a href="#">
                        <div>
                            <img class="placeholder-resize1" src="assests/images/microwave-garlic.jpg"
                                alt="microwave-garlic">
                        </div>
                        <div>
                            <h5 class="thanksgiving-list-title">Microwave Garlic Mashed Potatoes</h5>
                        </div>
                    </a>
                </div>
                <!-- thanksgiving food 3 -->  
                <div class="col-lg-3 col-md-6">
                    <a href="#">
                        <div>
                            <img class="placeholder-resize1" src="assests/images/loaded-breakfast.jpg"
                                alt="loaded-breakfast">
                        </div>
                        <div>
                            <h5 class="thanksgiving-list-title">Loaded Breakfast Sweet Potato</h5>
                        </div>
                    </a>
                </div>
                <!-- thanksgiving food 4 -->  
                <div class="col-lg-3 col-md-6">
                    <a href="#">
                        <div>
                            <img class="placeholder-resize1" src="assests/images/classic-deviled.jpg"
                                alt="loaded-breakfast">
                        </div>
                        <div>
                            <h5 class="thanksgiving-list-title">Classic Deviled Eggs</h5>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <!-- show more button -->
        <div>
            <button class="btn" type="submit">More ></button>
        </div>
    </section>


    <!-- List of apple food -->
    <section id="apple-list">
        <!-- apple-list receipes header -->
        <div>
            <h2 class="pb-2 border-bottom apple-header ps-4">An Apple a Day</h2>
        </div>
        <!-- food placeholder -->
        <div class="container-fluid pt-3">
            <div class="row">
                <!-- apple food 1 -->  
                <div class="col-lg-3 col-md-6">
                    <a href="#">
                        <div>
                            <img class="placeholder-resize1" src="assests/images/caramel-apple.jpg" alt="caramel-apple">
                        </div>
                        <div>
                            <h5 class="apple-list-title">Caramel Apple Crumble</h5>
                        </div>
                    </a>
                </div>
                <!-- apple food 2 -->  
                <div class="col-lg-3 col-md-6">
                    <a href="#">
                        <div>
                            <img class="placeholder-resize1" src="assests/images/60-minute-apple.jpg"
                                alt="60-minute-apple">
                        </div>
                        <div>
                            <h5 class="apple-list-title">60-Minute Apple Pie</h5>
                        </div>
                    </a>
                </div>
                <!-- apple food 3 -->  
                <div class="col-lg-3 col-md-6">
                    <a href="#">
                        <div>
                            <img class="placeholder-resize1" src="assests/images/apple-crumble.jpg"
                                alt="apple-crumble">
                        </div>
                        <div>
                            <h5 class="apple-list-title">Apple Crumble Cupcakes</h5>
                        </div>
                    </a>
                </div>
                <!-- apple food 4 -->  
                <div class="col-lg-3 col-md-6">
                    <a href="#">
                        <div>
                            <img class="placeholder-resize1" src="assests/images/brown-butter-apple.jpg"
                                alt="brown-butter-apple">
                        </div>
                        <div>
                            <h5 class="apple-list-title">Brown Butter Apple Tart</h5>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <!-- show more button -->
        <div>
            <button class="btn" type="submit">More ></button>
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