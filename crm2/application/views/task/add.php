   <article class="rs-content-wrapper" >
    <div class="rs-content" >
      <div class="rs-inner"> 
        <!-- Begin default content width -->
        <div class="container-fluid container-fluid-custom"> 
          
          <!-- Begin Panel -->
          <div class="panel panel-plain panel-rounded">
            <div class="p-t-xs">
      
      
      <div class="page-header">
        <h2>
          Adding <?php echo $title; ?>
        </h2>
      </div>
 
      <?php
      //flash messages
      if(isset($flash_message)){
        if($flash_message == TRUE)
        {
          echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Well done!</strong> new product created with success.';
          echo '</div>';       
        }else{
          echo '<div class="alert alert-error">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Oh snap!</strong> change a few things up and try submitting again.';
          echo '</div>';          
        }
      }
      ?>
      
      <?php
      //echo validation_errors();
      
      echo form_open_multipart('task/insert');
      ?>



     <div class="row">
        <div class="col-md-6">
          <div class="control-group">


        <input type="hidden" name="user_id" class="form-control" value="<?php echo $user_id; ?>">



           <input type="hidden" name="task_id" class="form-control" value="<?php echo $task_type; ?>">

           <?php if ($role_id == 3) : ?>



             <div class="form-group <?php  if(form_error('customer')) { echo $error_class; } else{ echo $error_less_class;} ?> ">
            <label for="inputError" class="control-label"> Select Customer *
 
          </label>
           
            <select id="customer" name="customer" class="form-control">
           
        <option value="" disabled selected>Select Customer</option>
        <?php foreach($get_customers as $key => $value ):?>

        <option value="<?php echo $value->user_id; ?>"><?php echo $value->first_name." ".$value->last_name; ?></option>
          
        <?php endforeach;?>
      </select>
           <span class="error-msg"><?php echo form_error('customer'); ?></span>
            </div>

<?php  endif ;?>

            <?php if ($role_id == 2 ) : ?>


            <div class="<?php  if(form_error('acccount_name')) { echo $error_class; } else{ echo $error_less_class;} ?>">
            <label for="inputError" class="control-label">Account Name (Your name)*</label>
         
              <input type="text" id="" name="acccount_name" class="form-control"  value="<?php echo $first_name." ". $last_name; ?>" readonly>
              <!--<span class="help-inline">Woohoo!</span>-->
               <span class="error-msg"><?php echo form_error('acccount_name'); ?></span>
       
          </div>
         <?php  endif ;?>


         <?php if ($role_id == 9 ) : ?>

            <div class="<?php  if(form_error('acccount_name')) { echo $error_class; } else{ echo $error_less_class;} ?>">
            <label for="inputError" class="control-label">Account Name (Your name)*</label>
         
          <input type="text" name="acccount_name" class="form-control"  value="<?php echo $firstname." ".$lastname; ?>" readonly>
              <!--<span class="help-inline">Woohoo!</span>-->
               <span class="error-msg"><?php echo form_error('acccount_name'); ?></span>
       
          </div>
         <?php  endif ;?>


          


          </div>
          </div>
          <div class="col-md-6">
          <div class="control-group">
            <div class="<?php  if(form_error('target_name')) { echo $error_class; } else{ echo $error_less_class;} ?>">
            <label for="inputError" class="control-label">Target Name (the person you'd like us to research) *</label>
           
              <input type="text" id="" name="target_name" class="form-control" value="<?php echo $target_name;?>">
              <!--<span class="help-inline">Cost Price</span>-->
               <span class="error-msg"><?php echo form_error('target_name'); ?></span>
            
            </div>
          </div> 
          </div>
          </div>
           <div class="row">
          <div class="col-md-6">         
          <div class="control-group">
            <div class="<?php  if(form_error('present_company')) { echo $error_class; } else{ echo $error_less_class;} ?>">
            <label for="inputError" class="control-label">Present Company *</label>
           
              <input type="text" id="" name="present_company" class="form-control" value="<?php echo $present_company;?>">
              <!--<span class="help-inline">Cost Price</span>-->
             <span class="error-msg"> <?php echo form_error('present_company'); ?></span>
            </div>
          </div>
          </div>
          <div class="col-md-6">
          <div class="control-group">
          <div class="form-group">
            <label for="inputError" class="control-label">Previous Company</label>
           
              <input type="text" name="previous_company" class="form-control" value="<?php echo $previous_company;?>">
              <!--<span class="help-inline">OOps</span>-->
            </div>
          
          </div>
          </div>
          </div>
           <div class="row">
          <div class="col-md-6">
          <div class="control-group">
          <div class="<?php  if(form_error('email_address')) { echo $error_class; } else{ echo $error_less_class;} ?>">
            <label for="inputError" class="control-label">Email Address</label>
           
              <input type="text" name="email_address" class="form-control" value = "<?php echo $email_address;?>">
              <!--<span class="help-inline">OOps</span>-->
                  <span class="error-msg"> <?php echo form_error('email_address'); ?></span>   
            </div>
          </div>
          </div>
          <div class="col-md-6">
          <div class="control-group">
          <div class="form-group">
            <label for="inputError" class="control-label">Home Address: Street, City and State (for neighbor's list)
 
</label>
            
              <input type="text" name="address" class="form-control" value="<?php echo $home_address;?>">
              <!--<span class="help-inline">OOps</span>-->
           
            </div>
          </div>
          </div>
          </div>
           <div class="row">
          <div class="col-md-6">
          <div class="control-group">
            
            <div class="<?php  if(form_error('meeting_date_time')) { echo $error_class; } else{ echo $error_less_class;} ?>  datepicker-block">
              <!-- <input type="text" name="sell_price" class= "datepicker"> 
              <!--input type="text" class="datepicker" name="meeting_date" placeholder="Date">
              <!--<span class="help-inline">OOps</span>-->
            <!--/div-->
            <label for="inputError" class="control-label">Date and Time of Meeting *</label>
             <div class="controls">
              
           <input type="text" readonly name="meeting_date_time" class="form-control meeting-date-timepicker" value="<?php echo $meeting_date_time;?>">

          <span class="error-msg"><?php echo form_error('meeting_date_time'); ?></span>
           <span>For requests within the same day, Time of Meeting should not be less than 2 hours from your current time in EST.
Contact your Business Development Assistant to ensure delievery of reports on time.
</span>

         </div>
       </div>
       </div>
          </div>



<!-- 
                 <div class="col-md-6">
          <div class="control-group">
          <div class="form-group">
          <div class="<?php  if(form_error('email_to_notifiy')) { echo $error_class; } else{ echo $error_less_class;} ?>"> -->
           <!--  <label for="inputError" class="control-label">Email Notification To *</label>
      -->
              <input type="hidden" name="email_to_notifiy" class="form-control"  value="<?php echo $email_to_notifiy; ?>">


            <!--  <span class="error-msg"> <?php echo form_error('email_to_notifiy'); ?></span>
              <span class="help-inline">OOps</span>
        
            </div>
          </div>
          </div>
          </div> -->

          <div class="col-md-6">

     

         <label>Attachments</label>  
          

      
    <div class="file-wrapper ">
             
                    <div class="file-field-wrapper">
                          <input type="file"  size="40" name="userFiles[]" class="form-control margin-bottom-10" id="file1"  aria-required="true" aria-invalid="false">
                      
                       </div>

                      <div class="file-clone-wrapper">
                      
                      </div>
                      <a href="#" class="add-more-file pull-left">Add More File </a>
                    
                      <div class="file-help-text">
                      <span class="help-text">Allowed File size is 40 Mb and allowed extensions are pdf | xls | doc | docx | xlsx | ods | txt.</span>
                      </div>
                      </div>
    


                      </div>
  
                
          </div>


<div class="row">
                    <div class="col-md-12">
          <div class="control-group">
          <div class="form-group">
            <label for="inputError" class="control-label">Task Type *</label>
            <div class="controls">
              <ul style="list-style-type: none;">
                <?php foreach($tasklist as $task) {?>

                <!-- <li><input type='radio' name='task_type' id="radioID" value='<?php echo $task->id;?>' <?php ($task_type === $task->id ?  ' checked="checked"' : ''); ?>><?php echo $task->id; ?></li> -->
                <?php if($task_type == $task->id){ ?>
                <li class="margin-top-10"><input type='radio' name='task_type_checkbox'  id="radioID" value='<?php echo $task->id;?>' checked="checked" disabled="disabled"><?php echo $task->name; ?></li>
                <?php }else{ ?>
                <li class="margin-top-10"><input type='radio' name='task_type_checkbox' id="radioID" value='<?php echo $task->id;?>'  disabled="disabled"><?php echo $task->name; ?></li>
                <?php } } ?>
 <input type="hidden" name="task_type" class="form-control" value="<?php echo $task_type; ?>">
              <!--<span class="help-inline">OOps</span>-->

              </ul>
            </div>
            </div>
          </div>
          </div>
          </div>
          <div class="row">
                  <div class="col-md-12">
          <div class="control-group">
          <div class="form-group">
            <label for="inputError" class="control-label">Comments and Additional Information (For all other task requests please write down your instructions here).</label>
            <div class="controls">
              <textarea name="additional_info" class="form-control" ><?php echo $comments_additional_info; ?></textarea>
              <!--<span class="help-inline">OOps</span>-->
            </div>
            </div>
          </div>
          </div>
          </div>

          <div class="control-group">
           <!--  <label for="inputError" class="control-label">Customer Id *</label> -->
            <div class="controls">
              <input type="hidden" name="user_id" class="form-control" value="<?php echo $user_id; ?>">
              <!--<span class="help-inline">OOps</span>-->
            </div>
          </div>
          <div class="control-group">
           <!--  <label for="inputError" class="control-label">BDA Id *</label> -->
            <div class="controls">

              <input type="hidden" name="bda_id" class="form-control" value="<?php echo $bda_id; ?>" >

            
              <!--<span class="help-inline">OOps</span>-->
            </div>
          </div>
          
          <div class="row">
                  <div class="col-md-12">
                  <div class="form-submit">
                    <input class="btn btn-default" type="submit" value="Submit">
                    
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

     