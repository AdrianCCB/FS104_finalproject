<?php
include('core/config.php');
include('core/db.php');
include('core/functions.php');

$isSuccess = $rollBackError = "";

if(!isLoggedIn()){
    header("Location: " . SITE_URL . "index.php"); //redirect to dashboard
  }


# extract data (Email, Name and Phone number) via userID
$userID = $_COOKIE["userID"];
$userQuery = DB::query("SELECT * FROM user WHERE userID = %i", $userID);
foreach($userQuery as $userResult){
    $userEmail = $userResult["userEmail"];
    $userName = $userResult["userName"];
    $userPassword = $userResult["userPassword"];
    $userPermission = $userResult["userPermission"];
    $userPhone = $userResult["userPhone"];
    $userImage = $userResult["userImage"];
}

# user press submit image
if(isset($_POST['submit'])){
    $imageUpload = uploadImage("profileImage");
    if($imageUpload["uploadedFile"] == ""){
        // File upload failed
        echo '<script>alert($imageUpload["returnMsg"]);</script>';
    } else {
        DB::update('user', ['userImage' => $imageUpload["uploadedFile"]], "userID=%i", $_COOKIE['userID']);

        $isSuccess = DB::affectedRows();
        
        if ($isSuccess) {
            DB::commit();
            # alert1 function will re-direct back to user-list.php
        }
        else {
            $rollBackError = DB::rollback();
            # alert1 function will prompt user with error in updating
        }  
        
    } 
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'templates/meta.php'; ?>
    <?php include 'templates/styles.php'; ?>
    <title>Document</title>
</head>
<body>
<div class="padding">
        <div class="row container d-flex justify-content-center">
            <div class="col-xl-6 col-md-12">
                <div class="card user-card-full">
                    <div class="row m-l-0 m-r-0">
                        <div class="col-sm-4 bg-c-lite-green user-profile">
                            <div class="card-block text-center text-white">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="m-b-25"> 
                                        <img src="<?php if($userImage == NULL){ echo 'https://www.tenforums.com/geek/gars/images/2/types/thumb_15951118880user.png';} else { echo $userImage;} ?>" class="img-radius" alt="User-Profile-Image"> 
                                    </div>
                                    <div class="form-control-sm mt-3">
                                        <label for="upload">Upload Pic</label>
                                        <label class="custom-file-upload">
                                            <input name="profileImage" type="file"/>
                                            <i class="fa fa-cloud-upload"></i> Upload
                                        </label>
                                        <input type="submit" id="imgSubmit" name="submit" value="Update Pic" class="btn btn-sm btn-primary mt-1" />
                                    </div>
                                </form>
                                <h3 class="f-w-600 mt-5 user-name"><?php echo ucwords($userName) ?></h6>
                                <p><?php if($userPermission == 1) { echo "Admin"; } else {echo "User"; } ?></p> <i class=" mdi mdi-square-edit-outline feather icon-edit m-t-10 f-16"></i>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="card-block">
                                <h6 class="m-b-20 p-b-5 b-b-default f-w-600">Information</h6>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="m-b-10 f-w-600">Email</p>
                                        <h6 class="text-muted f-w-400"><?php echo $userEmail ?></h6>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="m-b-10 f-w-600">Phone</p>
                                        <h6 class="text-muted f-w-400"><?php echo $userPhone ?></h6>
                                    </div>
                                </div>
                                <h6 class="m-b-20 m-t-40 p-b-5 b-b-default f-w-600">Recipes</h6>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="m-b-10 f-w-600">Recent</p>
                                        <h6 class="text-muted f-w-400">Dim Sum</h6>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="m-b-10 f-w-600">Most Viewed</p>
                                        <h6 class="text-muted f-w-400">Spring Roll</h6>
                                    </div>
                                </div>
                                <ul class="social-link list-unstyled m-t-40 m-b-10">
                                    <li><a href="https://www.facebook.com"><i class="fab fa-2x fa-facebook-square"></i></a></li>
                                    <li><a href="https://www.twitter.com"><i class="fab fa-2x fa-twitter-square"></i></a></li>
                                    <li><a href="https://www.instagram.com"><i class="fab fa-2x fa-instagram-square"></i></a></li>
                                </ul>
                                <a href="index.php" class="text-decoration-none"> <button class="btn btn-secondary btn-sm"> Back to Home Page</button></a> <br>
                                
                                <!-- only those with admin account (permission = 1) can access to Data Management -->
                                <?php 
                                    if ($userPermission == 1){
                                        echo '<a href="admin/home.php"><button class="mt-2 btn btn-warning btn-sm">Data Management</button></a>' ;
                                    }
                                ?>
                       
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

  <!-- Javascript -->
  <?php include 'templates/script.php'; ?>

  <?php 
    if($isSuccess){
        alert1('success', 'Updated', 'Profile Pic successful updated', false , '1500', 'userprofile.php');
    }

    if($rollBackError){
        alert1('error', 'Error', 'Unable to update', false, '1500', 'admin/user-list.php');
    }
  ?>


</body>
</html>