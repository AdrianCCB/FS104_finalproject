<button class="btn btn-outline-success login-request" type="submit">Login</button>


<section id="login" class="hide-login"> 
  <div class="row">
    <!-- login column -->
    <div class="col-md-6 login-column d-flex flex-column">
      <div>
        <h5>Login</h5>
      </div>
      <form method="POST" class="mt-auto">
        <i class="far fa-times-circle close-login fa-lg"></i>
        <div class="mb-2">
          <input type="email" placeholder="Email" name="formEmail" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
        </div>
        <div class="mb-2">
          <input type="password" placeholder="Password" name="formPassword" class="form-control" id="exampleInputPassword1">
        </div>
        <button type="submit" class="btn btn-danger btn-block" name="login" id="user-login">Login</button>
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
      <a href="register.php" class="btn btn-warning btn" role="button" aria-disabled="true">Register</a>
      </div>
    </div>
  </div>
</section>