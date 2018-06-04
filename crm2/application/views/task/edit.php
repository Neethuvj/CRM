<div class="container-fluid admin-panel-signup">
<article class="rs-content-wrapper">
  <div class="rs-content">
    <div class="rs-inner"> 
      <!-- Begin default content width -->
      <div class="container-fluid container-fluid-custom"> 
        
        <!-- Begin Panel -->
        <div class="panel panel-plain panel-rounded">
          <div class="p-t-xs">
            <div class="task-page">
            <div class="row">
              <div class="col-md-12 task-title">
                <h1><a href="">SST - <?php echo $tid;?></a> <?php echo $task_name; ?></h1>
              </div>
              <div class="col-md-12 text-center task-main-bar">



               <?php


if($this->session->flashdata('file_error_message')){

    echo '<div class="alert alert-danger">';
            echo '<a class="close" data-dismiss="alert">×</a>';
           echo $this->session->flashdata('file_error_message');

          echo '</div>'; 
}
     if($this->session->flashdata('success_message'))
        {
          echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
           echo $this->session->flashdata('success_message');

          echo '</div>';       
        }else{
                 
        }
              ?>
                <ul class="user-details clearfix">
                  <li>
                    <label>Account Name : </label>
                    <span><?php echo $account_name;?></span></li>
                  <li>
                    <label>Target : </label>
                    <span><?php echo $target_name;?></span></li>
                  <li>
                    <label>Date: </label>
                    <span><?php echo $created;?></span></li>
                    <li>
                    <label>Created By: </label>
                    <span><?php echo $task_created_user[0]->first_name ." ".$task_created_user[0]->last_name;?></span></li>
                </ul>
              </div>
              <?php
      // //flash messages
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
     
      
    

       
      $attributes = array('class' => 'edit analyst-edit-form');

      echo form_open_multipart('task/update',$attributes);
      //print_r($customer_attachments);
      
      ?>
      <input type="hidden" name="user_id" class="form-control" value="<?php echo $user_id; ?>">


       <input type="hidden" name="accountant_name" class="form-control" value="<?php echo $account_name; ?>">

        <input type="hidden" name="analyst_id" class="form-control" value="<?php echo $analyst_id; ?>">

         <input type="hidden" name="previous_status" id="previous_status" class="form-control previous_status" value="<?php echo $status; ?>">


              <input type="hidden" name="task_id" class="form-control" value="<?php echo $tid; ?>">
               <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Present Company</label>
                  <input type="text" id="" name="present_company" class="form-control" value="<?php echo $present_company;?>"  <?php echo $readonly ?>>
                </div>
              </div>
              <!-- /.col-md-6 -->
              <div class="col-md-6">
                <div class="form-group">
                  <label>Previous Company</label>
                  <input type="text" name="previous_company" class="form-control" value="<?php echo $previous_company;?>" <?php echo $readonly ?>>
                </div>
              </div>
              <!-- /.col-md-6 --> 
            </div>
            <!-- /.row -->
            
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Email Address</label>
                  <input type="text" name="email_address" class="form-control" value = "<?php echo $email;?>"  <?php echo $readonly ?>>
                </div>
              </div>
              <!-- /.col-md-6 -->
              <div class="col-md-6">
                <div class="form-group">
                  <label>Home Address</label>
                  <input type="text" name="address" class="form-control" value="<?php echo $home_address;?>"  <?php echo $readonly ?>>
                </div>
              </div>
              <!-- /.col-md-6 --> 
            </div>
            <!-- /.row -->
            
            <div class="row">
              <div class="col-md-6">
                <div class="form-group has-feedback datepicker-block">
                  <label class="control-label">Date and Time of Meeting</label>
                  <!-- <input type="text" class="form-control rs-datepicker" data-date-format="dd-mm-yyyy" placeholder="DD-MM-YYYY"> -->
                  <?php if(!empty($meeting_date_time)): ?>
                  <input type="text"  name="meeting_date_time" class="form-control" value="<?php echo date("m/d/Y h:i a", strtotime($meeting_date_time));?>"  <?php echo $readonly ?>>
                <?php else: ?>
                   <input type="text"  name="meeting_date_time" class="form-control" value=""  <?php echo $readonly ?>>
                <?php endif; ?>
                  <span class="fa fa-calendar form-control-feedback" aria-hidden="true"></span> </div>
              </div>
              <!-- /.col-md-6 -->
              <div class="col-md-6">
                <div class="form-group">
                  <div class="<?php  if(form_error('connections')) { echo $error_class; } else{ echo $error_less_class;} ?>  datepicker-block">
                  <label>No. of Connections</label>
                  <input type="text" name="connections" class="form-control" value="<?php echo $no_of_connections;?>">
                  <span class="error-msg"><?php echo form_error('connections'); ?></span>
                </div>
                </div>
              </div>
              <!-- /.col-md-6 --> 
            </div>
            <!-- /.row -->
            <div class="row">
            <div class="col-md-6">
            <div class="form-group">
              <label for="exampleTextarea">Customer Notes</label>
              <textarea style="resize: none;" class="form-control" name="customer_notes" id="" rows="3"  <?php echo $readonly ?> value="<?php echo $comments_additional_info;?>"><?php echo $comments_additional_info;?></textarea>
            </div>
            <div class="form-group">
              <label class="control-label">Status</label>
              <select class="form-control current_status" name="status">
                <?php if($status ==6){
                echo '<option selected value="6">Task Assigned</option>';
              } else{
                echo '<option value="6">Task Assigned</option>';
              }if($status == 7){
                echo '<option selected value="7">Task In Progress</option>';
              }else{
                echo '<option value="7">Task In Progress</option>';
              }if($status == 8){
                echo '<option selected value="8">Completed</option>';
              }else{
                echo '<option value="8">Completed</option>';
              }
             
                ?>
              </select>
            </div>
            <!-- <div class="form-group add-file">
            <form method="post" action="" enctype="multipart/form-data">
              <label class="btn btn-default btn-file"><img src="<?php echo base_url(); ?>assets/images/attach.png" alt="">Add File
                <input type="file" style="display: none;" name="userFiles[]" multiple><a id= "filename"></a>
                <span class="added-list">
                          <input type="file"  size="40" name="userFiles[]" class="multifiles" id="file1" multiple aria-required="true" aria-invalid="false">
                          <span class="fileName"></span>
                      </span> 
              </label>
            </form>
          </div> -->
       
         <!--  <div class="form-group add-links"> <a class="btn btn-default add-link"  data-toggle="modal" data-target="#add-link-page">Add Link</a> </div> -->
        
        </div>
        <div class="col-md-6">
          <div class="form-group addtime">
            <label>Total Time Spent</label>
            <input type="text" class="form-control" value="<?php echo $workLogs;?>" readonly>
            <a href="" class="btn btn-default add" data-toggle="modal" data-target="#add-time-page" >Add</a> </div>
          <div class="form-group addedlinks">
            <label for="exampleTextarea">Customer Attachments</label>
            <?php if(!empty($customer_attachments)): ?> 
            <?php foreach ($customer_attachments as $key => $value) {
        echo '<a href="'.base_url().'assets/files/'.$value->path.'" class="link">'.$value->path.'</a>';
      }?>

    <?php else: ?>
     <div> No attachments by customer</div>
    <?php endif; ?>
            <!-- <a href="" class="link">Referral_AnalystDashboard_new_file.pdf</a> <a href="" class="link">Facebook Referral Generation Report.pdf</a> <a href="" class="link">Report_new_file.doc</a>  -->
          </div>
        </div>

        </div>



<div class="row">
<div class="col-md-6">

         <label>Attachments</label>  
             <?php foreach ($analyst_attachments as $key => $value) {

                    echo '<div class="row">';
        echo '<a data-upload-id = '.$value->id.' href="'.base_url().'assets/files/'.$value->path.'" class="link col-md-6">'.$value->path.'</a> <a href="javascript::void(0)" class="remove_upload col-md-2 col-md-offset-4">Remove</a><input type="hidden" class="form-control hidden-removed" name="removed-file-links[]" />';
        echo '</div>';
      }?> 

      
    <div class="file-wrapper ">
             
                    <div class="file-field-wrapper">
                          <input type="file"  size="40" name="userFiles[]" class="form-control margin-bottom-10" id="file1"  aria-required="true" aria-invalid="false">
                      
                       </div>

                      <div class="file-clone-wrapper">
                      </div>
                      <a href="#" class="add-more-file pull-left">Add More File</a>
                    
                      <div class="file-help-text">
                      <span class="help-text">Allowed File size is 40 Mb and allowed extensions are pdf | xls | doc | docx | xlsx | ods | txt | xml.</span>

                      </div>
                      </div>
    


                      </div>
                
<div class="col-md-6">


        <div class="field_wrapper">
              <label>Links</label>  
          <?php foreach ($analyst_links as $key => $value) {
         echo '<div class="row">';
        echo '<a data-upload-id = '.$value->id.'  href="'.$value->url. '" class="link col-md-6">'.$value->url.'</a> <a href="javascript::void(0)" class="remove_upload col-md-2 col-md-offset-4">Remove</a> <input type="hidden" class="form-control  hidden-removed" name="removed-url-links[]" />';
        echo '</div>';
      }?>

                <input type="text" class="form-control" name="links[]" />

                


                <a href="javascript:void(0);" class="add_button" title="Add field">Add Url</a>



     
            </div>
            </div>
            </div>

      <br>
      <div class="row">    
        <div class="col-md-12">
          <div class="form-group Analyst-Note">
            <label for="exampleTextarea">Note</label>
           <textarea class="form-control" name="analyst_note" id="" rows="3" value="<?php echo $analyst_info; ?>"><?php echo $analyst_info; ?></textarea>
          </div>
        </div>
       </div> 
        <div class="col-md-6 col-sm-12 col-xs-12">
        <span class="last-updated">Last Updated : <?php echo $updated;?></span>
          <div class="form-submit">

            <input class="btn btn-default analyst-email" type="submit" value="Save">

          </div>
        </div>
        <!-- /.col-md-6 --> 
      </div>
         <?php echo form_close(); ?>
    </div>
  </div>
  </div>
  </div>
  </div>



</article>
</div>



<div id="add-time-page" class="modal fade add-time-popup" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Add Work</h4>
      </div>
      <div class="modal-body">
        <?php echo form_open('task/addlog');?>
        <input type="hidden" name="task_id" class="form-control"  value="<?php echo $tid; ?>">
        <div class="row">
          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="form-group has-feedback datepicker-block">
              <label>Date</label>
              <input  type="hidden" name="log_id[]" class="form-control" value="<?php echo 0?>" >
              <input type="text" class="form-control rs-datepicker" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY" name = "log_date[]">
              <span class="fa fa-calendar form-control-feedback" aria-hidden="true"></span> </div>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
              <label>Worked</label>
              <br>
              <select class="form-control" name="log_worked_hrs[]" style="width:80px; margin-right: 10px; float:left">
                <?php for ($j=0; $j <=12; $j++)
                echo '<option value="'.$j.'">'.$j.'</option>';
                ?>
               
              </select>
              <select class="form-control" name="log_worked_min[]" style="width:80px;  margin-right: 10px; float:left">
               <?php for ($i=0; $i <=60; $i+=5)
                echo '<option value="'.$i.'">'.$i.'</option>';
                ?>
              </select>
              <!-- <input type="text" class="form-control" name="log_worked[]"> -->
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="worklog">
              <h3>Work Logs</h3>
              <span class="edit-btn" ><a id="editable" ><img src="<?php echo base_url(); ?>assets/images/edit-link.png" alt="">Edit</a></span></div>
          </div>
          <div class="col-md-12">
          <div class="time-added-list scrollcustom mCustomScrollbar">
          <div class="row">
            <ul>
              <?php foreach ($log as $value) { 
               // print_r($value);
               
                ?>
                  
              <li>  
                <div class="col-md-6 col-sm-6 col-xs-6">
                  <div class="form-group has-feedback datepicker-block">
                    <input  type="hidden" name="log_id[]" class="form-control" value="<?php echo $value->id?>" >
                    <input disabled="true"   type="text" name= "log_date[]" class="form-control rs-datepicker log-date" value="<?php echo date("m/d/Y", strtotime($value->log_date)); ?>" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY">
                    <span class="fa fa-calendar form-control-feedback" aria-hidden="true"></span> </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                  <div class="form-group">
                    <!-- <input readonly="readonly" type="text" name="log_worked[]" value="<?php echo $value->log_work?>" class="form-control log-worked"> -->
                    <select disabled="true" class="form-control log-worked" id="" name="log_worked_hrs[]" style="width:80px; margin-right: 10px; float:left">
                <?php for ($j=0; $j <=12; $j++)
                if($value->log_hrs == $j){
                echo '<option selected value="'.$j.'">'.$j.'</option>';
                }
                else{
                 echo '<option value="'.$j.'">'.$j.'</option>'; 
                }
                ?>
              </select>
              <select disabled="true" class="form-control log-worked" name="log_worked_min[]" style="width:80px; margin-right: 10px; float:left">
                <?php for ($i=0; $i <=60; $i+=5)
                if($value->log_min == $i){
                echo '<option selected value="'.$i.'">'.$i.'</option>';
                }
                else{
                 echo '<option value="'.$i.'">'.$i.'</option>'; 
                }
                ?>
              </select>
                  </div>
                </div>
              </li>
              
              <?php } ?> 
            </ul>
              
            </div>
          </div>
          </div>
        </div>

      </div>
      <div class="modal-footer">

      

    <input class="btn btn-default log-add" type="submit" value="Save">

       
        <a href="/task/edit/<?php echo $tid; ?>" class="btn btn-default cancel-log">CANCEL</a> 
         <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>






<div id="add-time-page" class="modal fade analyst-confirmation-email" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" data-keyboard="false" data-backdrop="static" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Are you Sure?</h4>
      </div>
      <div class="modal-body">
        Are you sure you want to complete the task? This will send an email to the customer saying the task is completed.


      </div>
      <div class="modal-footer">
        <input class="btn btn-default btn-yes" type="submit" value="Save">
        <input type="button" class="btn btn-default cancel btn-no" data-dismiss="modal" value="Cancel">
      </div>
    </div>
  </div>

</div>
