<?php
include('core/config.php');
include('core/db.php');
include('core/functions.php');

$isSuccess = $rollBackError = $userCount = "";

if(isLoggedIn()){
    header("Location: " . SITE_URL); //redirect to dashboard
}

$formName = $formPhone = $formEmail = $formPassword = $formConfirmPassword = "";
$formNameError = $formPhoneError = $formEmailError = $formPasswordError = $formConfirmPasswordError = $checkBoxError = "";
$formEmailPass = $formPhonePass = $formNamePass = $formPasswordPass = $formConfirmPasswordPass = $checkBoxPass = "";

if(isset($_POST['register'])){
    $formName = input_validation($_POST['formName']);
    $formPhone = input_validation($_POST['formPhone']);
    $formEmail = input_validation($_POST['formEmail']);
    $formPassword = input_validation($_POST['formPassword']);
    $formConfirmPassword = input_validation($_POST['formConfirmPassword']);
    if(isset($_POST["checkBox"])){
        $checkBox = $_POST["checkBox"];
    }

// Start of validation for the form
    # validate Name
    if (empty($formName)) {
        $formNameError = "Name is required";
    } else if (!preg_match("/^[a-zA-Z-' ]*$/", $formName)) {
        $formNameError = "Only letters and white space allowed";
    } else {
        $formNamePass = true;
    }

    # validate Phone
    if (empty($formPhone)) {
    $formPhoneError = "Phone Number is required";
    } else if (!preg_match("/^[0-9]{8}$/", $formPhone)) {
        $formPhoneError = "Phone numbers must be in 8 digits";
    } else {
        $formPhonePass = true;
    }

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
    } else if (empty($formConfirmPassword)){
        $formConfirmPasswordError = "Please confirm your password";
    } else if ($formPassword !== $formConfirmPassword){     # check if both passwords keyed are the same
        $formConfirmPasswordError = "Password is not the same";
    } else {
        $formPasswordPass = $formConfirmPasswordPass = true;
    }

    if (empty($checkBox)) {
        $checkBoxError = "Please read and agree with the Terms of service";
    } else {
        $checkBoxPass = true;
    }

    if ($formNamePass && $formPhonePass && $formEmailPass && $formPasswordPass && $formConfirmPasswordPass && $checkBoxPass == true){
        $userQuery = DB::query("SELECT * FROM user WHERE userEmail = %s", $formEmail);
        $userCount = DB::count();
        
        if($userCount == 0){
            $hashedPassword = password_hash($formPassword, PASSWORD_DEFAULT);
            
            DB::startTransaction();
            DB::insert("user", [
                'userName' => $formName,
                'userPhone' => $formPhone,
                'userEmail' => $formEmail,
                'userPassword' => $hashedPassword
            ]);
            
            $newUserID = DB::insertId();
            $isSuccess = DB::affectedRows();
            
            if ($isSuccess) {
                DB::commit();
                # alert1 function will re-direct back to user-list.php
                setCookies($newUserID);      # set cookies for new user, and then will be able to sure in dashboard
            } else {
                $rollBackError = DB::rollback();
                # alert1 function will prompt user with error in updating
            }
        } 
    } 

}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include ('templates/meta.php');?>
    <title>Register Page</title>
    <?php include ('templates/styles.php'); ?>
    <?php include ('templates/customs-styles.php'); ?>
</head>

<body id="register-bg">
    <section class="py-5">
        <div class="mask d-flex align-items-center h-100">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                        <div class="card" style="border-radius: 15px;">
                            <div class="card-body p-5">
                                <h2 class="text-uppercase text-center mb-5">Create an account</h2>

                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label" for="form3Example1cg">Name</label>
                                        <input type="text" name="formName" id="formName" class="form-control form-control-lg" value="<?php echo $formName; ?>" />
                                        <span class="error"> <?php echo $formNameError; ?></span>
                                    </div>

                                    <div class="mb-3">
                                        <label for="formPhone" class="form-label">Phone number</label>
                                        <input type="text" name="formPhone" class="form-control" id="formPhone" value="<?php echo $formPhone ?>" />
                                        <span class="error"> <?php echo $formPhoneError; ?></span>
                                    </div>

                                    <div class="mb-3">
                                        <label for="formEmail" class="form-label">Email address</label>
                                        <input type="text" name="formEmail" class="form-control" id="formEmail" value="<?php echo $formEmail ?>" />
                                        <span class="error"> <?php echo $formEmailError; ?></span>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="formPassword">Password</label>
                                        <input type="password" name="formPassword" id="formPassword" class="form-control form-control-lg" value="<?php echo $formPassword ?>" />
                                        <span class="error"> <?php echo $formPasswordError; ?></span>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="formPasswordConfirm">Confirm your
                                            password</label>
                                        <input type="password" name="formConfirmPassword" id="formPasswordConfirm"
                                            class="form-control form-control-lg" value="<?php echo $formConfirmPassword ?>" />
                                        <span class="error"> <?php echo $formConfirmPasswordError; ?></span>
                                    </div>

                                    <div class="form-check d-flex justify-content-center mb-5">
                                        <input class="form-check-input me-2" name="checkBox" type="checkbox" value="check" <?php if(isset($checkBox) && $checkBox = "check") echo "checked" ?> />
                                        <label class="form-check-label" for="form2Example3g">
                                            I agree all statements in <a href="#!" class="text-body"><u>Terms of
                                                    service</u></a>
                                            <p><span class="error"> <?php echo $checkBoxError; ?></span></p>
                                        </label>
                                        
                                    </div>

                                    <div class="d-flex justify-content-center">
                                        <button type="submit"
                                            class="btn btn-success btn-block btn-lg gradient-custom-4 text-body" name="register">Register</button>
                                    </div>

                                    <p class="text-center text-muted mt-5 mb-0">Have already an account? <a href="login.php"
                                            class="fw-bold text-body"><u>Login here</u></a></p>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include 'templates/script.php'; ?>
    
    <?php 
    if($isSuccess){
        alert1('success', 'Thanks for registering', '', false, '1400', 'index.php'); 
    }
        
    if($userCount == 1){
        alert1('info', 'E-mail has registered', 'Please kindly log in', true, 'false', 'login.php');
    }

    if($rollBackError){
        alert1('error', 'Error', 'Unable to update', false, '1500', 'admin/user-list.php');
    }

    ?>

    
    
   
   
</body>

</html>