<section id="popup">
    <div class="container-fluid popup-container shadow-lg p-3 mb-5">
      <div>
        <i class="far fa-times-circle close-popup fa-lg"></i>
        <h1>Subscribe</h1>
        <p class="pb-1 border-bottom">Get the latest updates</p>
      </div>
      
      <!-- form -->
      <form class="needs-validation" novalidate>
        <div class="row">
          <!-- name input -->
          <div class="col-lg-6 col-md-6">
            <input type="text" class="form-control" id="firstName" placeholder="Name" value="" required>
            <div class="invalid-feedback">
              Valid name is required.
            </div>
          </div>
          <!-- email input -->
          <div class="col-lg-6 col-md-6 ">
            <input type="email" class="form-control" id="email" placeholder="abc@example.com" required>
            <div class="invalid-feedback">
              Please enter a valid email address.
            </div>
          </div>
          <!-- Subscribe button -->
          <div class="col-md-12 mt-2">
            <button class="btn btn-primary" type="submit">Subscribe</button>
          </div>
      </form>
    </div>
  </section>