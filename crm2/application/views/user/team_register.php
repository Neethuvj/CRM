 
 <div class="container products-detail admin-panel-signup">
 <article class="rs-content-wrapper ">
    <div class="rs-content">
      <div class="rs-inner"> 
        <!-- Begin default content width -->
        <div class="container-fluid"> 
           <h3 style="color:#3f5e84;">Sign Up</h3>
          <!-- Begin Panel -->
          <div class="register-page">
            <div class="p-t-xs">
                   <?php

      //form validation
    if($this->session->flashdata('success_message'))
        {
          echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
           echo $this->session->flashdata('success_message');

          echo '</div>';       
        }

      echo form_open('user/team_register');
      ?>


                <div class="row">
<div class="col-md-12">
                    <div class="checkbox checkbox-custom">
                      <label>
                        <input id="company-checkbox" class="company-name-open" name="company_checkbox" type="checkbox" value="1" <?php echo $company_checkbox; ?>>
                        <span class="checker"></span><b>Sign Up as Company.</b></label>
                    </div>
                  </div>
                  </div>
                <div class="row">
                  <div class="col-md-12 company-name">
                  <div class="<?php  if(form_error('company_name')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Company Name *</label> 
                      <?php echo form_input(array('type' => 'text','value' => $company_name, 'name' => 'company_name', 'class' => 'form-control')); ?>
                        <span class="error-msg"><?php echo form_error('company_name'); ?></span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('first_name')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>First Name *</label> 
                      <?php echo form_input(array('type' => 'text','value' => $first_name, 'name' => 'first_name', 'class' => 'form-control')); ?>
                        <span class="error-msg"><?php echo form_error('first_name'); ?></span>
                    </div>
                  </div>
                  <!-- /.col-md-6 -->
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('last_name')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Last Name *</label>
                    
                          <?php echo form_input(array('type' => 'text', 'name' => 'last_name', 'value' => $last_name,'class' => 'form-control')); ?>
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
                 

                         <?php echo form_input(array('type' => 'email', 'name' => 'email', 'class' => 'form-control', 'value' => $email)); ?>
              <span class="error-msg"> <?php echo form_error('email'); ?></span>
                    </div>
                  </div>
                  <!-- /.col-md-6 -->
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('phone_number')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Phone Number *</label>
                    

                         <?php echo form_input(array('type' => 'text', 'name' => 'phone_number', 'class' => 'form-control', 'value' => $phone_number)); ?>
                         <span class="error-msg">  <?php echo form_error('phone_number'); ?></span>
                    </div>
                  </div>
                  <!-- /.col-md-6 --> 
                </div>
                <!-- /.row -->
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('password')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Password *</label>
                    


                         <?php echo form_input(array('type' => 'password', 'name' => 'password', 'class' => 'form-control password')); ?>

                                 <div class="progress progress-striped active">
          <div id="jak_pstrength" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>
                          <span class="error-msg"> <?php echo form_error('password'); ?></span>
                    </div>
                  </div>
                  <!-- /.col-md-6 -->
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('confirm_password')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Confirm Password *</label>
                    <?php echo form_input(array('type' => 'password', 'name' => 'confirm_password', 'class' => 'form-control')); ?>
                     <span class="error-msg"> <?php echo form_error('confirm_password'); ?></span>
                    </div>
                  </div>
                  <!-- /.col-md-6 --> 
                </div>
                <!-- /.row -->
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('address')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Street Address *</label>
                          <?php echo form_input(array('type' => 'text', 'name' => 'address', 'class' => 'form-control address', 'value' => $address)); ?>
                          <span class="error-msg">  <?php echo form_error('address'); ?></span>
                    </div>
                  </div>
                  <!-- /.col-md-6 -->
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('city')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>City *</label>
                              <?php echo form_input(array('type' => 'text', 'name' => 'city', 'class' => 'form-control city', 'value' => $city)); ?>
                             <span class="error-msg">   <?php echo form_error('city'); ?></span>
                    </div>
                  </div>
                  <!-- /.col-md-6 --> 
                </div>
                <!-- /.row -->
                <div class="row">
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('state')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>State *</label>
                             <?php echo form_input(array('type' => 'text', 'name' => 'state', 'class' => 'form-control state', 'value' => $state)); ?>
                              <span class="error-msg"> <?php echo form_error('state'); ?> </span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('zip')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Zip / Postal Code *</label>
                               <?php echo form_input(array('type' => 'text', 'name' => 'zip', 'class' => 'form-control zip', 'value' => $zip)); ?>
                                <span class="error-msg"> <?php echo form_error('zip'); ?></span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group m-b-md">
                      <label class="control-label">Your Plan</label>

                      <?php foreach($plans as $plan_id => $plan_obj): ?>


 <?php

  if(!empty($selected_plan_id)){
  if($selected_plan_id==$plan_obj->id){

    $checked = TRUE;
    $selected_plan_id = $plan_obj->id;

  }
  else{
    $checked = FALSE;
  }
  }
  else{
    if($plan_obj->id == 1){
$selected_plan_id = $plan_obj->id;
      $checked = TRUE;
    }
    else{
    $checked = FALSE;
    }
  }
 ?>

 <div class="radio radio-custom">
                        <label>
                       

                          <?php echo form_radio('plan_id_to_select' . $plan_obj->id, $plan_obj->id, $checked,'disabled=disabled') ?>



                          <span class="checker"></span> <span class="check-label"><?php echo $plan_obj->name; ?></span><br>
                          (<?php echo $plan_obj->description; ?> )</label>
                      </div>

                      <?php endforeach; ?>
                       <?php echo form_input(array('type' => 'hidden', 'name' => 'plan_id_selected','value' => $selected_plan_id, 'class' => 'form-control')); ?>
                    
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <p> Please schedule your 20-30 minute onboarding call with your personal Business Development Assistant here. Once scheduled you'll receive an invitation for your calendar</p>
                    </div>
                    <div class="col-md-6">
                      <div class="<?php if(form_error('onboard-date')) { echo $error_class; } else{ echo $error_less_class;} ?> form-group has-feedback datepicker-block">
                        <label class="control-label">Date</label>
                        <input name="onboard-date" id="onboard-date" value="<?php echo $on_board_date; ?>" type="text" class="form-control rs-datepicker" date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY">
                       </div>
                          <span class="error-msg"> <?php echo form_error('onboard-date'); ?></span>
                    </div>
                 
                    <div class="col-md-6">
                      <div class="form-group has-feedback">
                        <label class="control-label">Time</label>
                        <div class="input-group"> <span class="input-group-addon">EST</span>
                          <input name="onboard-time" id="onboard-time"  value="<?php echo $on_board_time; ?>" type="text" class="form-control rs-timepicker" placeholder="">
                        </div>
                </div>
                    </div>
                  
                </div>
           
               
                
                  
                
                
                <div class="row">
                  <div class="col-md-6">
                  <div class="form-submit">
                   <?php echo form_submit('submit', 'Register', array("class" => 'btn btn-primary')); ?>
                  </div>
                  </div>
                </div>
                
              <?php echo form_close(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </article>
  <div id="push"></div>
  </div>