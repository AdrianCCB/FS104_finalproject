<?php
include('core/config.php');
include('core/db.php');
include('core/functions.php');

if(isLoggedIn()){
    header("Location: " . SITE_URL . "index.php"); //redirect to dashboard
}

$formEmail = $formPassword = $userPassword = "";
$formEmailError = $formPasswordError = "";
$formEmailPass = $formPasswordPass = "";

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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include ('templates/meta.php');?>
    <title>Login Page</title>
    <?php include ('templates/styles.php'); ?>
    <?php include ('templates/customs-styles.php'); ?>
</head>

<body id="login-bg">
    <section>
        <div class="container py-1 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text-white" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">
                            <form method="POST">
                                <div class="mb-md-1">

                                    <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                                    <p class="text-white-50 mb-5">Please enter your login and password!</p>

                                    <div class="form-outline form-white mb-4">
                                        <span class="error"> <?php echo $formEmailError ?></span>
                                        <input type="text" name="formEmail"  class="form-control form-control-lg" value="<?php echo $formEmail ?>"  />
                                        <label class="form-label" for="typeEmailX">Email</label>
                                    </div>

                                    <div class="form-outline form-white mb-4">
                                        <span class="error"> <?php echo $formPasswordError ?></span>
                                        <input type="password" name="formPassword" id="typePasswordX" class="form-control form-control-lg"  value="<?php echo $formPassword ?>"  />
                                        <label class="form-label" for="typePasswordX">Password</label>
                                    </div>

                                    <p class="small mb-2 pb-lg-2"><a class="text-white-50" href="#!">Forgot password?</a>
                                    </p>

                                    <button id="bbtn" class="btn btn-outline-light btn-lg px-5" name="login" type="submit">Login</button>

                                    <div class="d-flex justify-content-center text-center mt-4 pt-1">
                                        <a href="https://www.facebook.com" class="text-white"><i class="fab fa-facebook-f fa-lg"></i></a>
                                        <a href="https://www.twitter.com" class="text-white"><i class="fab fa-twitter fa-lg mx-4 px-2"></i></a>
                                        <a href="https://www.google.com" class="text-white"><i class="fab fa-google fa-lg"></i></a>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0">Don't have an account? <a href="register.php" class="text-white-50 fw-bold">Sign Up</a></p>
                                </div>
                            </form>     
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'templates/script.php'; ?>

    <!-- Sweetalert -->
    <?php
        if(password_verify($formPassword, $userPassword)){
            alert1('success', 'Welcome back', '', false, '1500', 'index.php');
        }
    ?>
</body>

</html>