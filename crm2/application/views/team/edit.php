

<article class="rs-content-wrapper" >
    <div class="rs-content" >
      <div class="rs-inner"> 
        <!-- Begin default content width -->
        <div class="container-fluid container-fluid-custom"> 
          
          <!-- Begin Panel -->
          <div class="panel panel-plain panel-rounded">
            <div class="p-t-xs">
           


       <?php if( $this->session->flashdata('error_message')): ?>
                 <div class="alert alert-danger">
                  <?php
                     echo $this->session->flashdata('error_message');
                  ?>
                 </div>
                  <?php endif; ?>
                  <?php if( $this->session->flashdata('success_message')): ?>
                  <div class="alert alert-success">
                    <?php
                      echo $this->session->flashdata('success_message');
                    ?>
                  </div>
                    <?php endif; ?>


<?php 
      echo form_open('team/update/'.$user_details[0]->id);
      ?>
                <div class="row">


           
                
                  <input type="hidden" class="form-control" name="id" value="<?php echo $user_details[0]->id;?>">
                    <input type="hidden" class="form-control" name="plan_id" value="<?php echo $user_details[0]->plan_id;?>">
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('first_name')) { echo $error_class; } else{ echo $error_less_class;} ?>">

                      <label>First Name *</label>
                      <input type="text" class="form-control" name="first_name" value="<?php echo $user_details[0]->first_name;?>">
                      <span class="error-msg"><?php echo form_error('first_name'); ?></span>

                    </div>
                  </div>
                  <!-- /.col-md-6 -->
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('last_name')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Last Name *</label>
                      <input type="text" class="form-control" name="last_name" value="<?php echo  $user_details[0]->last_name;?>">

                      <span class="error-msg"> <?php echo form_error('last_name'); ?></span>

                    </div>
                  </div>
                  <!-- /.col-md-6 --> 
                </div>
                <!-- /.row -->
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('email')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Email *</label>
                      <input type="email" class="form-control" name="email" value="<?php echo  $user_details[0]->username;?>" > 

                       <span class="error-msg"><?php echo form_error('email'); ?></span>

                    </div>
                  </div>
                  <!-- /.col-md-6 -->
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('phone_number')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Phone Number *</label>
                      <input type="text" class="form-control" name="phone_number" value="<?php echo $user_details[0]->phone_number;?>">

                       <span class="error-msg"><?php echo form_error('phone_number'); ?></span>

                    </div>
                  </div>
                  <!-- /.col-md-6 --> 
                </div>
               
                <!-- /.row -->
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('street_name')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Street Address *</label>
                      <input type="text" class="form-control" name="street_name" value="<?php echo  $user_details[0]->address;?>">
                        <span class="error-msg"> <?php echo form_error('street_name'); ?></span>
                    </div>
                  </div>
                  <!-- /.col-md-6 -->
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('city')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>City *</label>
                      <input type="text" class="form-control" name="city" value="<?php echo $user_details[0]->city;?>">
                         <span class="error-msg"><?php echo form_error('city'); ?></span>
                    </div>
                  </div>
                  <!-- /.col-md-6 --> 
                </div>
                <!-- /.row -->
                <div class="row">
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('state')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>State *</label>
                      <input type="text " class="form-control" name="state" value="<?php echo $user_details[0]->state;?>">
                         <span class="error-msg"><?php echo form_error('state'); ?></span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('zip_code')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Zip / Postal Code *</label>
                      <input type="text " class="form-control" name="zip_code"  value="<?php echo $user_details[0]->zip_code;?>">

                       <span class="error-msg"><?php echo form_error('zip_code'); ?></span>

                    </div>
                  </div>
                </div>


                <div class="row">
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('monthly_usage')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Monthly Usage *</label>
                      <input type="text " class="form-control" name="monthly_usage" value="<?php echo $user_details[0]->monthly_usage;?>">
                         <span class="error-msg"><?php echo form_error('monthly_usage'); ?></span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('notification_hour')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Notification Hour *</label>
                      <input type="text " class="form-control" name="notification_hour"  value="<?php echo $user_details[0]->notification_hour;?>">

                       <span class="error-msg"><?php echo form_error('notification_hour'); ?></span>

                    </div>
                  </div>
                </div>
                 <!-- /.row -->
            
  

           
                 <!--    <div class="col-md-12">
                    <div class="checkbox checkbox-custom sign-up-custom">
                 
                      <label>
                      Email Notification Status 
                        <input type="checkbox" value="" checked="">
                        <span class="checker"></span></label>
                    </div>
                  </div> -->
                  <div class="row">
                  <div class="col-md-6">
                  <div class="form-submit">
                    <input class="btn btn-default" type="submit" value="Submit">
                  
                  </div>
                  </div>
                </div>
               <?php echo form_close(); ?>
              <!--credit card onfo-->
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </article>
  <div id="push"></div>