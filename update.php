<?php
include('core/config.php');
include('core/db.php');
include('core/functions.php');

$isSuccess = $rollBackError = "";

 # Enquire user info via $_GET ID
$userQuery = DB::query("SELECT * FROM user WHERE userID=%i", $_GET['id']);
foreach($userQuery as $userResult){
    $userName = $userResult['userName'];
    $userEmail = $userResult["userEmail"];
    $userPhone = $userResult["userPhone"];
}

# fill up value for the form
$formName =  $userName;
$formEmail = $userEmail;
$formPhone = $userPhone;

$formNameError = $formPhoneError = $formEmailError = "";
$formEmailPass = $formPhonePass = $formNamePass = "";

if(isset($_POST['update'])){
    $formName = input_validation($_POST['formName']);
    $formPhone = input_validation($_POST['formPhone']);
    $formEmail = input_validation($_POST['formEmail']);

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

    if ($formNamePass && $formPhonePass && $formEmailPass == true){
        DB::update('user', ['userName' => $formName, 'userPhone' => $formPhone, 'userEmail' => $formEmail], "userID=%i", $_GET['id']);
                 
        $isSuccess = DB::affectedRows();
        
        if ($isSuccess) {
            DB::commit();
            # alert1 function will re-direct back to user-list.php
        } else {
            $rollBackError = DB::rollback();
            # alert1 function will prompt user with error in updating
        }           
    } 
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include ('templates/meta.php');?>
    <title>Update User</title>
    <?php include ('templates/styles.php'); ?>
    <?php include ('templates/customs-styles.php'); ?>
</head>

<body>
    <section class="py-5">
        <div class="mask d-flex align-items-center h-100">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                        <div class="card" style="border-radius: 15px;">
                            <div class="card-body p-5">
                                <h2 class="text-uppercase text-center mb-5">Update</h2>

                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label" for="form3Example1cg">Name</label>
                                        <input type="text" name="formName" id="formName" class="form-control form-control-lg" value="<?php if($formName != $userName){echo $formName; } else { echo $userName; } ?>" />
                                        <span class="error"> <?php echo $formNameError; ?></span>
                                    </div>

                                    <div class="mb-3">
                                        <label for="formPhone" class="form-label">Phone number</label>
                                        <input type="text" name="formPhone" class="form-control" id="formPhone" value="<?php  if($formPhone != $userPhone){echo $formPhone; } else { echo $userPhone; } ?>" />
                                        <span class="error"> <?php echo $formPhoneError; ?></span>
                                    </div>

                                    <div class="mb-3">
                                        <label for="formEmail" class="form-label">Email address</label>
                                        <input type="text" name="formEmail" class="form-control" id="formEmail" value="<?php if($formEmail != $userEmail){echo $formEmail; } else { echo $userEmail; } ?>" />
                                        <span class="error"> <?php echo $formEmailError; ?></span>
                                    </div>

                                    <div class="d-flex justify-content-center">
                                        <button type="submit"
                                            class="btn btn-success btn-block btn-lg gradient-custom-4 text-body" name="update">Update</button>
                                    </div>

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
            alert1('success', 'Updated', '', false, '1500', 'admin/user-list.php'); 
        }

        if($rollBackError){
            alert1('error', 'Error', 'Unable to update', false, '1500', 'admin/user-list.php');
        }
    ?>

 

    

   
</body>

</html>