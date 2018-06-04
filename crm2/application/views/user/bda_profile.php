

<article class="rs-content-wrapper" >
    <div class="rs-content" >
    
      <div class="rs-inner"> 
           
        <!-- Begin default content width -->
        <div class="container-fluid container-fluid-custom"> 
           <!-- <h4>Account Details</h4>-->

            <h2 class="form-signin-heading">Account Details</h2>
          <!-- Begin Panel -->
          <div class="panel panel-plain panel-rounded">

            <div class="p-t-xs">
           
             <?php

      //form validation

      echo form_open('user/edit_bda_profile');
      ?>
                <div class="row">


                <div class="col-md-12 text-center">
                
                </div>
                
                  <input type="hidden" class="form-control" name="id" value="<?php echo $id;  ?>">
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('first_name')) { echo $error_class; } else{ echo $error_less_class;} ?>">

                      <label>First Name</label>
                      <input type="text" class="form-control" name="first_name" value="<?php echo $first_name; ?>">
                      <span class="error-msg"></span>

                      <span class="error-msg"> <?php echo form_error('first_name'); ?></span>


                    </div>
                  </div>
                
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('last_name')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Last Name</label>
                      <input type="text" class="form-control" name="last_name" value="<?php echo $last_name; ?>">

                      <span class="error-msg"> <?php echo form_error('last_name'); ?></span>

                    </div>
                  </div>
               
                </div>
             
                <div class="row">

                   <div class="col-md-6">
                    <div class="<?php  if(form_error('phone_number')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Phone Number</label>
                      <input type="text" class="form-control" name="phone_number" value="<?php echo $phone_number; ?>">

                      <span class="error-msg"> <?php echo form_error('phone_number'); ?></span>

                    </div>
                  </div>


                   <div class="col-md-6">
                    <div class="<?php  if(form_error('email')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Email Address</label>
                      <input type="email" class="form-control" name="email" value="<?php echo $email;?>"> 

                       <span class="error-msg"><?php echo form_error('email'); ?></span>

                    </div>
                  </div>
                  
                </div>
                <h2 class="form-signin-heading">Reset Password</h2>
                 <div class="row">
         <div class="col-md-6">
            <div class="<?php  if(form_error('new_password')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                <label>New Password *</label>
                <input type="password" name="new_password" value="" id="password" class="form-control first" placeholder="New Password" autocomplete="off">
                <span class="error-msg"><?php echo form_error('new_password'); ?></span>
            </div>
        </div>
         <div class="col-md-6">
         <label>Confirm Password *</label>
           <div class="<?php  if(form_error('confirm_password')) { echo $error_class; } else{ echo $error_less_class;} ?>">
              <input type="password" name="confirm_password" value="" class="form-control last" placeholder="Confirm New Password" autocomplete="off">

                  <span class="error-msg"><?php echo form_error('confirm_password'); ?></span>
            </div>
        </div>
        </div>
                 
                  <div class="row">
                  <div class="col-md-6">
                  <div class="form-submit">
              <input class="btn btn-default" type="submit" value="Update">


              <a href="/task/inprogress" class="btn cancel-btn-default btn-default">Cancel</a>
              </div>
                  
                  
                  </div>
                 
                </div>
               <?php echo form_close(); ?>
             
              
            </div>
          </div>
        </div>
      </div>
  

  </article>
  <div id="push"></div>

