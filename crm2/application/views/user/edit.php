<article class="rs-content-wrapper" >
    <div class="rs-content" >
      <div class="rs-inner"> 
        <!-- Begin default content width -->
        <div class="container-fluid container-fluid-custom"> 
          
          <!-- Begin Panel -->
          <div class="panel panel-plain panel-rounded">
            <div class="p-t-xs">

              <?php if( $this->session->flashdata('success_message')): ?>
 <div class="alert alert-success">
          <?php
  echo $this->session->flashdata('success_message');

?>
</div>
<?php endif; ?>
             <?php

      //form validation




      echo form_open('user/edituser');
      ?>


                <div class="row">


                <div class="col-md-12 text-center">
                  <ul class="user-details clearfix">
                      <li><label>Name : </label><span class="margin-left5"><?php echo $user_details[0]->first_name." ".$user_details[0]->last_name?></span></li>
                      <li><label>Email : </label><span class="margin-left5"><?php echo $email; ?></span></li>

                           <li><label>Phone : </label><span class="margin-left5"><?php echo $user_details[0]->phone_number;?></span></li>
                       <li><label>Plan : </label><span class="margin-left5"><?php echo $plan_name;?></span></li>
                 
                        <li><label>Hours : </label><span class="margin-left5"><?php echo $plan_hours;?></span></li>
                      
                    </ul>
                </div>
                
                  <input type="hidden" class="form-control" name="id" value="<?php echo $id;?>">

                  <div class="col-md-6">
                    <div class="<?php  if(form_error('first_name')) { echo $error_class; } else{ echo $error_less_class;} ?>">

                      <label>First Name *</label>
                      <input type="text" class="form-control" name="first_name" value="<?php echo $first_name;?>">
                      <span class="error-msg"><?php echo form_error('first_name'); ?></span>

                    </div>
                  </div>
                  <!-- /.col-md-6 -->
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('last_name')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Last Name *</label>
                      <input type="text" class="form-control" name="last_name" value="<?php echo $last_name;?>">

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
                      <input type="email" class="form-control" name="email" value="<?php echo $email;?>" readonly> 

                       <span class="error-msg"><?php echo form_error('email'); ?></span>

                    </div>
                  </div>
                  <!-- /.col-md-6 -->
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('phone_number')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Phone Number *</label>
                      <input type="text" class="form-control" name="phone_number" value="<?php echo $phone_number;?>">

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
                      <input type="text" class="form-control" name="street_name" value="<?php echo $street_name;?>">
                        <span class="error-msg"> <?php echo form_error('street_name'); ?></span>
                    </div>
                  </div>
                  <!-- /.col-md-6 -->
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('city')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>City *</label>
                      <input type="text" class="form-control" name="city" value="<?php echo $city;?>">
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
                      <input type="text " class="form-control" name="state" value="<?php echo $state;?>">
                         <span class="error-msg"><?php echo form_error('state'); ?></span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('zip_code')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Zip / Postal Code *</label>
                      <input type="text " class="form-control" name="zip_code"  value="<?php echo $zip_code;?>">

                       <span class="error-msg"><?php echo form_error('zip_code'); ?></span>

                    </div>
                  </div>

                   
                  </div>


                     <?php if((int) $user_details[0]->role_id == 9): ?>

                 <div class="row">
                 
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('monthly_usage')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Monthly Usage *</label>
                      <input type="text " class="form-control" name="monthly_usage" value="<?php echo $monthly_usage;?>" readonly>
                         <span class="error-msg"><?php echo form_error('monthly_usage'); ?></span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('notification_hour')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Notification Hour *</label>
                      <input type="text " class="form-control" name="notification_hour"  value="<?php echo $notification_hour;?>" readonly>

                       <span class="error-msg"><?php echo form_error('notification_hour'); ?></span>

                    </div>
                  </div>

             
              <?php endif; ?>
               
                 <!-- /.row -->
                <?php if((int) $user_details[0]->role_id !== 9): ?>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Onboarding Date</label>
                      <input type="text" class="form-control" value="<?php echo $on_board_date;?>" >
                    </div>
                  </div>
                  <!-- /.col-md-6 -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Onboarding Time</label>
                      <input type="text" class="form-control" value="<?php echo $on_board_time;?>" >
                    </div>
                  </div>
                  <!-- /.col-md-6 --> 
                </div>

                    <div class="row">
                


                <?php if($company !== NULL): ?>
                  <!-- /.col-md-6 -->
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('company_name')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Company Name</label>
                      <input type="text" class="form-control"  name="company_name"  value="<?php echo $company_name;?>" >
                      <span class="error-msg"><?php echo form_error('company_name'); ?></span>

                    </div>
                  </div>
                <?php endif; ?>
                  <!-- /.col-md-6 --> 
                </div>

               <?php endif; ?>  
               
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group m-b-md">
                      <label class="control-label">Customer Assistant Details</label><br> 
                      <div class="input_fields_wrap">
                       <div class="wrapper-to-add">
                        <?php foreach ($assistant as $key => $value) {
                          



                          //echo count($value[$key]);

                          //echo count($value['assistant_name']);
                          for($i=0; $i < count($value['assistant_name']); $i++){
                            ?>
                        
                      <?php


  // echo "<pre>";
  // print_r($value);
  // print $i;
  // exit();

                      if($value['email_status'][$i] == 1){

                        $checked = "checked";
                      }
                      else{
                        $checked = "";
                      }
                      ?>
                      
                          <div>
                          
                            <div class="row">
                           <div class="col-md-6">
                                      <div class="form-group">
                                    <label>Enable Report Notification</label>

                                       <!--   <input class="form-group" value ="0" name="email_status[]" type="hidden" > -->
                                          <input class="form-group" value ="1" name="email_status[<?php echo $i;?>]" type="checkbox" <?php echo $checked;?>>

                                        </div>
                                      </div>
                                </div> 
                                   
                       
                           <div class="row">
                          
                                  
                                  
                               <input type="hidden" class="form-control" name="ass_id" value="<?php echo $id;?>">
                               <div class="col-md-6">
                                 <div class="form-group">
                                   <label>Name</label><input type="text" name="assistant_name[]" class="form-control" value="<?php echo $value['assistant_name'][$i]; ?>">
                                 </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label>Email</label>
                                      <input type="email" name="assistant_email[]" class="form-control" value="<?php echo $value['assistant_email'][$i]; ?>">
                                   </div>
                                </div>
                              </div>
                                <div class="row">
                                   <div class="col-md-6">
                                      <div class="form-group">
                                        <label>Phone No</label>
                                         <input type="text" name="assistant_phone[]" class="form-control" value="<?php echo $value['assistant_phone'][$i];?>"></div>
                                      </div>
                                    </div>
                                    <input type="hidden"  value="<?php echo $value['assistant_id'][$i]; ?>" name="assistant_id[]" class="form-control assistant_id" >
                          <a href="#" class="remove_field">Remove</a></div>

                          <input type="hidden"  value="" name="delete_assistant[]" class="form-control delete_assistance" >
                        <?php }
                        }?>
                        </div>

                         <a class="add-fields">Add</a>
                      </div>
                      
                    </div>
                  </div>
                </div>

 <?php if((int) $user_details[0]->role_id !== 9): ?>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group m-b-md">
                      <label class="control-label">More Information</label><br> 
            
                    <div class="form-group">
                   
                      <!-- <input type="textarea" rows="3" cols="20"class="form-control" > -->
                      <textarea id="txtArea" name="more_info" rows="5" cols="70" class="form-control"><?php echo $more_info; ?></textarea>
                    </div>
                  </div>
                      
               
                  </div>
                </div>
              <?php endif; ?>     
           
                <div class="col-md-12">
                    <!-- <p> Please schedule your 20-30 minute onboarding call with your personal Business Development Assistant here. Once scheduled you'll receive an invitation for your calendar</p> -->
                    </div>
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



  