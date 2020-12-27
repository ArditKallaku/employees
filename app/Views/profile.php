<div class="container">
  <div class="row">
    <div class="col-12 col-sm8- offset-sm-2 col-md-6 offset-md-3 mt-5 pt-3 pb-3 bg-white from-wrapper">
      <div class="container">
        
        <form class="" enctype="multipart/form-data" action="/profile" method="post">
          <div class='user-details row'>
            <div class="col-12 col-sm-6">
              <h3><?= $user['first_name'].' '.$user['last_name'] ?></h3>
              <p>Department: <b> <?= $user['department'] ?> </b> </p>
            </div>
            <div class="col-12 col-sm-6">
             <div class="form-group">
               <img src="/uploads/images/<?= $user['profile_picture'] ?>">
               <input type="file" class="form-control-file" name="profile_pic" id="profile_pic">
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-12 col-sm-6">
              <div class="form-group">
               <label for="firstname">First Name</label>
               <input type="text" class="form-control" name="firstname" id="firstname" value="<?= set_value('firstname', $user['first_name']) ?>">
              </div>
            </div>
            <div class="col-12 col-sm-6">
              <div class="form-group">
               <label for="lastname">Last Name</label>
               <input type="text" class="form-control" name="lastname" id="lastname" value="<?= set_value('lastname', $user['last_name']) ?>">
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
               <label for="email">Email address</label>
               <input type="text" class="form-control" readonly id="email" value="<?= $user['email'] ?>">
              </div>
            </div>
            <div class="col-12 col-sm-6">
              <div class="form-group">
               <label for="password">Password</label>
               <input type="password" class="form-control" name="password" id="password" value="">
             </div>
           </div>
           <div class="col-12 col-sm-6">
             <div class="form-group">
              <label for="password_confirm">Confirm Password</label>
              <input type="password" class="form-control" name="password_confirm" id="password_confirm" value="">
            </div>
          </div>
          <?php if (isset($validation)): ?>
            <div class="col-12">
              <div class="alert alert-danger" role="alert">
                <?= $validation->listErrors() ?>
              </div>
            </div>
          <?php endif; ?>
          </div>
          <?php if (session()->get('success')): ?>
            <div class="alert alert-success" role="alert">
              <?= session()->get('success') ?>
            </div>
          <?php endif; ?>
          <div class="row">
            <div class="col-12 col-sm-6">
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<script src="/assets/scripts/userInput.js"></script>