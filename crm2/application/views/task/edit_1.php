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
          Adding <?php echo $task_name; ?>
        </h2>
      </div>
 
      <?php
      //flash messages
      // if(isset($flash_message)){
      //   if($flash_message == TRUE)
      //   {
      //     echo '<div class="alert alert-success">';
      //       echo '<a class="close" data-dismiss="alert">×</a>';
      //       echo '<strong>Well done!</strong> new product created with success.';
      //     echo '</div>';       
      //   }else{
      //     echo '<div class="alert alert-error">';
      //       echo '<a class="close" data-dismiss="alert">×</a>';
      //       echo '<strong>Oh snap!</strong> change a few things up and try submitting again.';
      //     echo '</div>';          
      //   }
      // }
     
      
    

      
      echo form_open_multipart('task/update');
      ?>
     <!-- <div class="row">
        <div class="col-md-6">
          <div class="control-group">
            <div class="<?php  if(form_error('acccount_name')) { echo $error_class; } else{ echo $error_less_class;} ?>">
            <label for="inputError" class="control-label">Account Name (Your name)*</label>
         
              <input type="text" id="" name="acccount_name" class="form-control"  value="<?php echo $first_name." ". $last_name; ?>" readonly>
              <!--<span class="help-inline">Woohoo!</span>-->
               <!-- <span class="error-msg"><?php echo form_error('acccount_name'); ?></span>
       
          </div>
          </div>
          </div>
          <div class="col-md-6">
          <div class="control-group">
            <div class="<?php  if(form_error('target_name')) { echo $error_class; } else{ echo $error_less_class;} ?>">
            <label for="inputError" class="control-label">Target Name (the person you'd like us to research) *</label>
           
              <input type="text" id="" name="target_name" class="form-control" value="<?php echo $target_name;?>">
              <!--<span class="help-inline">Cost Price</span>-->
               <!-- <span class="error-msg"><?php echo form_error('target_name'); ?></span>
            
            </div>
          </div> 
          </div>
          </div> -->
           <div class="row">
          <div class="col-md-6">         
          <div class="control-group">
            <div class="<?php  if(form_error('present_company')) { echo $error_class; } else{ echo $error_less_class;} ?>">
            <label for="inputError" class="control-label">Present Company *</label>
           
              <input type="text" id="" name="present_company" class="form-control" value="<?php echo $present_company;?>" readonly>
              <!--<span class="help-inline">Cost Price</span>-->
             <span class="error-msg"> <?php echo form_error('present_company'); ?></span>
            </div>
          </div>
          </div>
          <div class="col-md-6">
          <div class="control-group">
          <div class="form-group">
            <label for="inputError" class="control-label">Previous Company</label>
           
              <input type="text" name="previous_company" class="form-control" value="<?php echo $previous_company;?>" readonly>
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
           
              <input type="text" name="email_address" class="form-control" value = "<?php echo $email_address;?>" readonly>
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
            
              <input type="text" name="address" class="form-control" value="<?php echo $home_address;?>" readonly>
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
            <label for="inputError" class="control-label">Date and Time of Meeting</label>
             <div class="controls">
              
           <input type="text"  name="meeting_date_time" class="form-control task-timepicker" value="<?php echo $meeting_date_time;?>" readonly>
          <span class="error-msg"><?php echo form_error('meeting_date_time'); ?></span>

         </div>
       </div>
       </div>
          </div>
          <div class="col-md-6">
          <div class="control-group">
            
            <div class="<?php  if(form_error('meeting_date_time')) { echo $error_class; } else{ echo $error_less_class;} ?>  datepicker-block">
              <!-- <input type="text" name="sell_price" class= "datepicker"> 
              <!--input type="text" class="datepicker" name="meeting_date" placeholder="Date">
              <!--<span class="help-inline">OOps</span>-->
            <!--/div-->
            <label for="inputError" class="control-label">No. of Connections</label>
             <div class="controls">
            <input type="text" name="connections" class="form-control" >  
         </div>
       </div>
       </div>
          </div>

          </div>

          <div class="row">
          <div class="col-md-6">
          <div class="control-group">
            
            <div class="<?php  if(form_error('comments_additional_info')) { echo $error_class; } else{ echo $error_less_class;} ?>  datepicker-block">
              <!-- <input type="text" name="sell_price" class= "datepicker"> 
              <!--input type="text" class="datepicker" name="meeting_date" placeholder="Date">
              <!--<span class="help-inline">OOps</span>-->
            <!--/div-->
            <label for="inputError" class="control-label">Customer Notes</label>
             <div class="controls">
            <textarea name="additional_info" class="form-control" ><?php echo $comments_additional_info; ?></textarea>  
           <!-- <input type="text"  name="meeting_date_time" class="form-control task-timepicker" value="<?php echo $meeting_date_time;?>" readonly> -->
          <span class="error-msg"><?php echo form_error('comments_additional_info'); ?></span>

         </div>
       </div>
       </div>
          </div>
          <div class="col-md-6">
          <div class="form-group addtime">
            <label>Total Time Spent</label>
            <input type="text" class="form-control">
            <a href="" class="btn btn-default add" data-toggle="modal" data-target="#add-time-page" >Add</a> </div>
          <div class="form-group addedlinks">
            <label for="exampleTextarea">Customer Attachments</label>
            <a href="" class="link">Referral_AnalystDashboard_new_file.pdf</a> <a href="" class="link">Facebook Referral Generation Report.pdf</a> <a href="" class="link">Report_new_file.doc</a> </div>
        </div>

          </div>

          <div class="row">
          <div class="col-md-6">
          <div class="control-group">
            
            <div class="<?php  if(form_error('comments_additional_info')) { echo $error_class; } else{ echo $error_less_class;} ?>  datepicker-block">
              <!-- <input type="text" name="sell_price" class= "datepicker"> 
              <!--input type="text" class="datepicker" name="meeting_date" placeholder="Date">
              <!--<span class="help-inline">OOps</span>-->
            <!--/div-->
            <label for="inputError" class="control-label">Status</label>
             <div class="controls">
            <input type="text" name="connections" class="form-control" >  
           <!-- <input type="text"  name="meeting_date_time" class="form-control task-timepicker" value="<?php echo $meeting_date_time;?>" readonly> -->
          <!-- <span class="error-msg"><?php echo form_error('comments_additional_info'); ?></span> -->

         </div>
       </div>
       </div>
          </div>
          <!--div class="col-md-6">
          <div class="control-group">
            
            <div class="<?php  if(form_error('meeting_date_time')) { echo $error_class; } else{ echo $error_less_class;} ?>  datepicker-block">
              <!-- <input type="text" name="sell_price" class= "datepicker"> 
              <!--input type="text" class="datepicker" name="meeting_date" placeholder="Date">
              <!--<span class="help-inline">OOps</span>-->
            <!--/div-->
            <!--label for="inputError" class="control-label">Total Time Spent</label>
             <div class="controls">
            <input type="text" name="connections" class="form-control" >  
         </div>
       </div>
       </div>
          </div-->

          </div>

          <div class="row">
          <div class="col-md-6">
          <div class="control-group">
            
            <div class="<?php  if(form_error('comments_additional_info')) { echo $error_class; } else{ echo $error_less_class;} ?>  datepicker-block">
              <!-- <input type="text" name="sell_price" class= "datepicker"> 
              <!--input type="text" class="datepicker" name="meeting_date" placeholder="Date">
              <!--<span class="help-inline">OOps</span>-->
            <!--/div-->
            <!-- <label for="inputError" class="control-label">Add</label> -->
             <div class="controls">
              <!-- <input type="file" multiple="" name="images[]"> -->
            <input type="file" class="form-control" name="userFiles[]" multiple/> 
            <div class="field_wrapper">
                <input type="text" class="form-control" name="links[]" />
                <a href="javascript:void(0);" class="add_button" title="Add field">Add Url</a>
            </div>
            <!-- <input type="text" class="form-control" name="links[]" multiple/>  -->
           <!-- <input type="text"  name="meeting_date_time" class="form-control task-timepicker" value="<?php echo $meeting_date_time;?>" readonly> -->
          <!-- <span class="error-msg"><?php echo form_error('comments_additional_info'); ?></span> -->

         </div>

       </div>
       </div>
          </div>

          <!--div class="col-md-6">
          <div class="control-group">
            
            <div class="<?php  if(form_error('meeting_date_time')) { echo $error_class; } else{ echo $error_less_class;} ?>  datepicker-block">
              <!-- <input type="text" name="sell_price" class= "datepicker"> 
              <!--input type="text" class="datepicker" name="meeting_date" placeholder="Date">
              <!--<span class="help-inline">OOps</span>-->
            <!--/div-->
            <!--label for="inputError" class="control-label">Total Time Spent</label>
             <div class="controls">
            <input type="text" name="connections" class="form-control" >  
         </div>
       </div>
       </div>
          </div-->

          </div>
<!-- <div class="form-group">
            <div class="row colbox">
           

            <div class="field_wrapper">
                <input type="text" name="links[]" />
                <a href="javascript:void(0);" class="add_button" title="Add field">Add Url</a>
            </div></div></div> -->


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
              <input type="hidden" name="user_id" class="form-control" value=<?php echo $user_id; ?>>
              <input type="hidden" name="task_id" class="form-control" value=<?php echo $id; ?>>
              <!--<span class="help-inline">OOps</span>-->
            </div>
          </div>
          <div class="control-group">
           <!--  <label for="inputError" class="control-label">BDA Id *</label> -->
            <div class="controls">
              <input type="hidden" name="bda_id" class="form-control" value="" >
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
     