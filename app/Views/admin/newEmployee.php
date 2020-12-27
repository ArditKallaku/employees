<div class="container">
  <div class="row">
    <div class="col-12 col-sm8- offset-sm-2 col-md-6 offset-md-3 mt-5 pt-3 pb-3 bg-white from-wrapper">
      <div class="container">
        
        <form class="" enctype="multipart/form-data" method="post">
          <div class='user-details row'>
            <div class="col-12 col-sm-6">
              <h3>New employee</h3>
            </div>
            <div class="col-12 col-sm-6">
             <div class="form-group">
               <img src="/uploads/images/default.jpg ?>">
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
               <input type="text" class="form-control" name="email" id="email" value="<?= set_value('email', $user['email']) ?>">
              </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                <label for="email">Department</label>
                    <select class="form-control" name="department" id="department" value="<?= set_value('department', $user['department_id']) ?>">
                        <?php
                            foreach($all_dep as $row ){
                                echo "<option value='".$row['id']."' >".$row['description']."</option>";
                            }
                        ?>
                    </select>
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
              <button type="submit" class="btn btn-primary">Add New</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<script src="/assets/scripts/userInput.js"></script>