<button class="btn btn-outline-info login-request" type="submit">Profile</button>

<section id="login" class="hide-login"> 
  <div class="row">
    <!-- login column -->
    <div class="col-md-6 login-column d-flex flex-column">
    <i class="far fa-times-circle close-login fa-lg"></i>
      <div >
          <img class="profile-pic" src="<?php if($userImage == NULL){ echo 'https://www.tenforums.com/geek/gars/images/2/types/thumb_15951118880user.png';} else { echo $userImage;} ?>" alt="Users Profile" style="width: 150px; height: 150px;">
      </div>  
    </div>
    <!-- register column -->
    <div class="col-md-6 register-column d-flex flex-column"> 
      <div>
        <h5><a href="userprofile.php"><?php echo ucwords($userName); ?></a></h5>
      </div>
      <div class="register-content">
        <span>Phone Number: <?php echo $userPhone ?></span> 
      </div>
      <div class="register-btn mt-auto">
          <a href="logout.php" class="logout-btn btn btn-danger btn" role="button" aria-disabled="true">Log Out</a>
      </div>
    </div>
  </div>
</section>

