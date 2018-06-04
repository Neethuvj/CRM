 
 <div class="container products-detail admin-panel-signup">
 <article class="rs-content-wrapper ">
    <div class="rs-content">
      <div class="rs-inner"> 
        <!-- Begin default content width -->
        <div class="container-fluid text-center"> 
           
          <!-- Begin Panel -->
          <div class="register-page">
            <div class="p-t-xs">
            <?php if(isset($user_id)): ?>
                   <?php

      //form validation
     echo $this->session->flashdata('success_message');

      echo form_open('user/reset_password/'.$tmp_password);
      ?>
      <h3 style="color:#3f5e84;">RESET PASSWORD</h3>
                <div class="row">
                  <div class="col-md-offset-3 col-md-6">
                    <div class="<?php  if(form_error('password')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>New Password *</label>
                    


                         <?php echo form_input(array('type' => 'password', 'name' => 'password', 'class' => 'form-control password')); ?>

                                 <div class="progress progress-striped active">
          <div id="jak_pstrength" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>
                          <span class="error-msg"> <?php echo form_error('password'); ?></span>
                    </div>
                  </div>
                  <!-- /.col-md-6 -->
                  <div class="col-md-offset-3 col-md-6">
                    <div class="<?php  if(form_error('confirm_password')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Confirm New Password *</label>
                    <?php echo form_input(array('type' => 'password', 'name' => 'confirm_password', 'class' => 'form-control'));
                    		echo form_input(array('type' => 'hidden', 'name' => 'user_id', 'value'=> $user_id, 'class' => 'form-control'));
                    ?>
                     <span class="error-msg"> <?php echo form_error('confirm_password'); ?></span>
                    </div>
                  </div>
                  <!-- /.col-md-6 --> 
                </div>
                <!-- /.row -->
                
               
                <div class="row">
                  <div class="col-md-offset-3 col-md-6">
                  <div class="form-submit">
                   <?php echo form_submit('submit', 'Update', array("class" => 'btn btn-primary')); ?>
                  </div>
                  </div>
                </div>
                
              <?php echo form_close(); ?>
                <?php else: ?>
  <h4 class="alert alert-success"> Password has been updated successfully. </h4>
  <a class="log-in" data-toggle="modal" data-target="#myModal12">LOGIN NOW</a>
  <?php endif;?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </article>
  <div id="push"></div>
  </div>
