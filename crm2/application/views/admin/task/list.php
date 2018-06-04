
<article class="rs-content-wrapper task-list-admin">
    <div class="rs-content task-panel">
      <div class="rs-inner"> 
        <!-- Begin default content width -->
        <div class="container-fluid"> 
          <div class="row">
                  <div class="col-md-12">
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



          <!-- Begin Panel -->
          <div class="panel panel-plain panel-rounded">
            <div class="p-t-xs">




              <div class="support-team ss-list-details"> 
                

      
                <div class="clearfix"></div>
                <!-- Tab panes -->
                <div class="tab-content">



<?php if(isset($type_of_task) && $type_of_task !== "completed_tasks"): ?>

  

<div class="filter-panel ss-active">

<form class="form-horizontal  margin-bottom-10" action="/admin/task/<?php echo $type_of_task; ?>" method="post">
  <?php //if($type_of_task !== "all_reports"){?>
<div class="row">
   <?php if($type_of_task !== "all_reports"){?>
 <div class="col-md-6">
  <div class="form-group ">
    <label for="pwd" class="col-sm-3">Target Name:</label>
    <div class="col-sm-9 ss-select">
    <select name="search_target" class="form-control">
       <option value="" selected>Select Target</option>
            <?php foreach($get_target_names as $get_target_name):?>



         <option value="<?php echo $get_target_name->target_name;?>" <?php echo (isset($search_target) && $search_target == $get_target_name->target_name) ?  "selected" : " "; ?>><?php echo $get_target_name->target_name;?></option>


          
        <?php endforeach;?>
    </select>
   
    </div>
  </div>
 </div> 
 <?php } //else{ ?>
 <?php if($type_of_task == "all_reports"){?>

<div class="col-md-6">
  <div class="form-group ">
    <label for="pwd"  class="col-sm-3" class="col-sm-2">Team Members:</label>
     <div class="col-sm-9 ss-select">
    <select  name="search_team_member" class="form-control" id="search_team_member">
    <option value="" selected>Select Team Member</option>
     <?php foreach($get_all_team_members as $key => $get_all_team_member):?>

       <?php 

       if($search_team_member==$get_all_team_member->id){
       $selected_text = "selected";}
        else
        {
          $selected_text = ""; 
        }

        ?>
         
        <option value="<?php echo $get_all_team_member->id;?>" <?php echo $selected_text;?>><?php echo $get_all_team_member->first_name ." ".$get_all_team_member->last_name;?></option>
        <?php endforeach;?>
    </select>
    </div>
  </div>
  </div>
<?php } ?>

<div class="col-md-6">
  <div class="form-group ">
    <label for="pwd"  class="col-sm-3" >Customers:</label>
     <div class="col-sm-9 ss-select">
    <select  name="search_customer" class="form-control" id="search_customer">
    <option value="" selected>Select Customer</option>
     <?php foreach($get_all_customers as $get_all_customer):?>

       <?php if($search_customer==$get_all_customer->id){
        $selected_text = "selected";}
        else
        {
          $selected_text = ""; 
        }


        ?>
         
        <option value="<?php echo $get_all_customer->id;?>" <?php echo $selected_text;?>><?php echo $get_all_customer->first_name ." ".$get_all_customer->last_name;?></option>
        <?php endforeach;?>
    </select>
    </div>
  </div>
 </div> 
</div> 

<?php //} ?>

<div class="row">

  <?php if($type_of_task !== "all_reports") { ?>

<div class="col-md-6">
  <div class="form-group ">
    <label for="pwd"  class="col-sm-3" class="col-sm-2">Team Members:</label>
     <div class="col-sm-9 ss-select">
    <select  name="search_team_member" class="form-control" id="search_team_member">
    <option value="" selected>Select Team Member</option>
     <?php foreach($get_all_team_members as $key => $get_all_team_member):?>

       <?php 

       if($search_team_member==$get_all_team_member->id){
       $selected_text = "selected";}
        else
        {
          $selected_text = ""; 
        }

        ?>
         
        <option value="<?php echo $get_all_team_member->id;?>" <?php echo $selected_text;?>><?php echo $get_all_team_member->first_name ." ".$get_all_team_member->last_name;?></option>
        <?php endforeach;?>
    </select>
    </div>
  </div>
  </div>

<?php } else{
  ?>


<div class="col-md-6">
  <div class="form-group ">
    <label for="pwd"  class="col-sm-3" class="col-sm-2">Status:</label>
     <div class="col-sm-9 ss-select">
    <select  name="search_status" class="form-control" id="search_status">
    <option value="" selected>Select Task Status</option>

      <?php foreach($status_types as $status_type):?>


     <?php if($search_status==$status_type->id){
        $selected_text = "selected";}

        else
        {
          $selected_text = ""; 
        }
      ?>

        <option value="<?php echo $status_type->id;?>"  <?php echo $selected_text;?>><?php echo $status_type->name;?></option>
        <?php endforeach;?>
    
   
    </select>
    </div>
  </div>
  </div>

  <?php } ?>


<div class="col-md-6">
  <div class="form-group">
    <label for="pwd" class="col-sm-3">BDA:</label>
     <div class="col-sm-9 ss-select">
    <select value="search_bda" name="search_bda" class="form-control" id="search_bda">
    <option value="" selected>Select BDA</option>
  <?php foreach($get_all_bdas as $get_all_bda):?>


     <?php if($search_bda==$get_all_bda->id){
        $selected_text = "selected";}

        else
        {
          $selected_text = ""; 
        }
      ?>

        <option value="<?php echo $get_all_bda->id;?>"  <?php echo $selected_text;?>><?php echo $get_all_bda->first_name;?></option>
        <?php endforeach;?>
    
    </select>
    </div>
  </div>
 </div> 
</div> 

<div class="row">
  <div class="col-md-6">
  <div class="form-group">
    <label for="pwd" class="col-sm-3">Analyst:</label>
     <div class="col-sm-9 ss-select">
    <select value="search_analyst" name="search_analyst" class="form-control" id="search_bda">
    <option value="" selected>Select Analyst</option>
  <?php foreach($get_all_analyst as $analyst):?>


     <?php if($search_analyst==$analyst->id){
        $selected_text = "selected";}

        else
        {
          $selected_text = ""; 
        }

      ?>

        <option value="<?php echo $analyst->id;?>"  <?php echo $selected_text;?>><?php echo $analyst->first_name;?></option>
        <?php endforeach;?>
    
    </select>
    </div>
  </div>
  </div>

 <div class="col-md-6">
  <div class="form-group ">
    <label for="pwd" class="col-sm-3">Task Type:</label>
     <div class="col-sm-9 ss-select">
    <select value="search_task_type" name="search_task_type" class="form-control" id="search_task_type">
    <option value="" selected>Select Task Type</option>
    <?php foreach($tasklists as $tasklist):?>

      <?php if($search_task_type==$tasklist->id){
        $selected_text = "selected";}
        else
        {
          $selected_text = ""; 
        }
      ?>

        <option value="<?php echo $tasklist->id;?>" <?php echo $selected_text;?>><?php echo $tasklist->name;?></option>
        <?php endforeach;?>


    </select>
    </div>
  </div>

  </div>
  </div>
<div class="row">

  <?php if($type_of_task != 'all_reports'){ ?>
  <div class="col-md-6">

   <div class="form-group has-feedback datepicker-block">
        <label class="col-sm-3 txt-left">Date Of Meeting:</label>
             <div class="col-sm-9 ss-select">   
             <div class="col-sm-5 col-lg-6 pad-left0"> 
      <input type="text"  name="meeting_start_date" class="form-control rs-datepicker" placeholder="MM-DD-YYYY"  value="<?php echo $meeting_start_date; ?>">
      </div>
      <div class="col-sm-5 col-lg-6 pad-right0">
          <input type="text"  name="meeting_to_date" class="form-control rs-datepicker" placeholder="MM-DD-YYYY"  value="<?php echo $meeting_to_date; ?>">
          </div>
      
      </div>

  </div>

</div>
<?php } else{?>

  <div class="col-md-6">

   <div class="form-group has-feedback datepicker-block">
        <label class="col-sm-3 txt-left">Date:</label>
             <div class="col-sm-9 ss-select">   
             <div class="col-sm-5 col-lg-6 pad-left0"> 
      <input type="text"  name="created_start_date" class="form-control rs-datepicker" placeholder="MM-DD-YYYY"  value="<?php echo $created_start_date; ?>">
      </div>
      <div class="col-sm-5 col-lg-6 pad-right0">
          <input type="text"  name="created_to_date" class="form-control rs-datepicker" placeholder="MM-DD-YYYY"  value="<?php echo $created_to_date; ?>">
          </div>
      
      </div>

  </div>

</div>
<?php } ?>
</div>
  <div class="row">

<div class="col-md-6">
<div class="form-group  ">

 
  <button type="submit" name="submit" value="Submit" class="btn btn-default">Search</button>

  <a href="/admin/task/<?php echo $this->uri->segment(3); ?>/reset" class="btn btn-danger">Reset</a>

   <button type="submit" name="csv_export" value="CSV Export" class="btn btn-primary">Export</button>
  </div>

 
  </div>
</div>

</div>

</form>


<?php if($type_of_task == "all_reports") {?>

 <div class=" text-center">
                  <ul class="task-details-admin clearfix">
                      <li><label>Total No. of Report Requests:</label><span class= "margin-left5"><?php echo $no_of_reports; ?></span></li>

                        <li><label>Total No. of Reports Delivered:</label><span class="margin-left5"><?php echo count($no_of_completed_tasks);?></span></li>
    
    <li><label>Total No. of Connections: </label><span class="margin-left5"><?php echo array_sum($no_of_connections_total); ?></span></li>
                           <li><label>Total Time Spent:</label><span class="margin-left5"><?php echo $grand_total_hour . ' hour(s) ' . $grand_total_min.' minute(s)'; ?></span></li>
                      
                    </ul>
                </div>

<?php } ?>


                <div role="tabpanel" class="tab-pane active" id="home">
                    <div class="table-responsive">
                     
                       <?php if(!empty($tasks)): ?>

                           <?php
                       echo form_open('admin/task/delete_tasks');
                       ?>
                      <table class="table table-bordered table-striped">
                        <thead>
                          <tr>
                           <?php if(isset($type_of_task) && $type_of_task == ""): ?>
                         
                           <?php endif; ?>

                            <?php if(isset($type_of_task) && $type_of_task !== "summary"): ?>
                            <th>Task ID <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th  class="no-sort">Account Name <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                             <th class="no-sort">Team Member <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th  class="no-sort">Target Name <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th  class="no-sort">Task Type<!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <?php if($type_of_task == "inprogress"){ ?>
                            <th class="no-sort">Date of Meeting</th>
                            <?php } ?>

                            <?php if($type_of_task == "all_reports"){ ?>
                            <th class="no-sort">Date Received</th>
                            <th class="no-sort">Date Delivered</th>
                            <?php } ?>
                            <th  class="no-sort">No. of Connections <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th  class="no-sort" colspan="2">Hours Spent <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                      
                            <th  class="no-sort">Analyst</th>
                     <!--    <th  class="no-sort">Status</th>-->
                              <th  class="no-sort">Status</th>
                             
                          <?php endif; ?>
                           <th class="no-sort" >Select</th>
                          </tr>
                        </thead>
                        <tbody>
                                                    
                          <?php
                            foreach($tasks as $row)
                            {
                              
                              echo '<tr>';

                               if(isset($type_of_task) && $type_of_task == ""){
                            

                              }


                              if(isset($type_of_task) && ($type_of_task == "inprogress")||( $type_of_task == "all_reports")){

                              echo '<td  class="id">'.$row->id.'</td>';

                              echo '<td>'.$row->account_name.'</td>';
echo "<td>";
                              if(!empty($team_array[$row->user_id])) {

                                  echo $team_array[$row->user_id];
        

                              }
echo "</td>";
                  
                              echo '<td>'.$row->target_name.'</td>';

                               echo '<td>'.'<a href="/admin/task/edit/' . $row->id .'">'.$row->task_name.'</td>';
                 
                        

 if($type_of_task == "inprogress"){
  if($row->meeting_date_time !== NULL){

     echo '<td>'.date("m/d/Y h:i A", strtotime($row->meeting_date_time)).'</td>';

  }
  else{
    echo '<td></td>';
  }
}

 if($type_of_task == "all_reports"){

echo '<td>'.date("m/d/Y h:i A", strtotime($row->created)).'</td>';

if($row->status_id == 5 ){
echo '<td>'.date("m/d/Y h:i A", strtotime($row->updated)).'</td>';
}
else{
   echo '<td></td>';
}

}
                          echo "<td>".$row->no_of_connections."</td>";

                          echo '<td colspan="2">'.$calculated_hours[$row->id]['logged_hrs']." hour(s) ".'<br>'.$calculated_hours[$row->id]['logged_min']." minute(s)".'</td>';
                             

                       if(!empty($row->first_name)){

                        $analyst_assign_title = "Change";

                       }
                       else{
                        $analyst_assign_title = "Assign";
                       }

                              echo '<td class="analyst_value"><span class="analyst_name">'.$row->first_name." ".$row->last_name.

                              '</span><br><a href="#" class="change-analyst-popup" data-analyst-id='.$row->analysist_id.'>'.$analyst_assign_title.'</a>
                              </td>';

                              echo '<td>'.$row->analyst_status_name.'</td>';
                                  echo '<td><input name="delete_task[]" type="checkbox" value = '.$row->id.'>  </td>';
                              echo '</tr>';
                          }

                           if(isset($type_of_task) && ($type_of_task == "completed")|| ($type_of_task == "deleted")){



                              echo '<td  class="id">'.$row->id.'</td>';

                              echo '<td>'.$row->account_name.'</td>';

echo "<td>";

                              if(!empty($team_array[$row->user_id])) {

                                  echo $team_array[$row->user_id];
                    

  

                              }
echo "</td>";
     

                                //echo "<td></td>";


                              echo '<td>'.$row->target_name.'</td>';

                                echo '<td>'.'<a href="/admin/task/edit/' . $row->id .'">'.$row->task_name.'</td>';
                 
                              echo '<td>'.$row->no_of_connections.'</td>';

                            echo '<td colspan="2">'.$calculated_hours[$row->id]['logged_hrs'].' hour(s) <br>'.$calculated_hours[$row->id]['logged_min'].' minute(s)</td>';

                              echo '<td class="analyst_value">'.$row->first_name ." ". $row->last_name.'</td>';

                          
                              echo '<td>'.$row->analyst_status_name.'</td>';


    if($type_of_task == 'all_reports'){
                             echo '<td>'.$row->created.'</td>';

                              echo '<td>'.$row->updated.'</td>';
                            }

                               echo '<td><input name="delete_task[]" type="checkbox" value = '.$row->id.'>  </td>';

    echo '</tr>';
                          
                          }

   
                            }
                            ?>  
                        </tbody>
                      </table>


                      <input name="redirect_url" type="hidden" value ='/admin/task/<?php echo $type_of_task; ?>'> 
                   
                         <div class="form-submit">
                  
                    <input class="btn btn-default btn-delete-task" type="submit" value="Delete">
                    <!-- <input class="btn btn-default" type="button" value="Cancel"> -->
                 
                  </div>
                    <?php echo form_close(); ?>
                      <?php echo '<div class="pagination">'.$this->pagination->create_links().'</div>'; ?>

                      <?php else: ?>
                        <div class="alert alert-danger">No Data Found. </div>
                    <?php endif; ?>


                       
                      
                    </div>
                  </div>
                  


               <?php else: ?>


      <article class="rs-content-wrapper task-type" >
    <div class="rs-content" >
      <div class="rs-inner"> 
        <!-- Begin default content width -->
        <div class="container-fluid container-fluid-custom"> 
          
          <!-- Begin Panel -->
          <div class="panel panel-plain panel-rounded">
            <div class="p-t-xs">
             
              <ul class="list-group task-list" style="list-style-type: none;">
                <?php foreach($tasklists as $task) {?>
                <li  class="list-group-item"><a href="<?php echo base_url();?>admin/task/add/<?php echo $task->id; ?>"><?php echo $task->name; ?></a>
                <span class="ss_description">
                  <?php echo $task->description; ?>
                </span>

                </li>
                <?php } ?>
              </ul>
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </article>
  <div id="push"></div>

         
                <?php endif; ?>
                </div>
              </div>

                 
            </div>
          </div>


        </div>
      </div>
       <a class="btn btn-primary" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Back</a>
    </div>


    </div>
    </div>
  </article>
  <div id="push"></div>













   <div id="add-time-page" class="modal fade change_analyst" tabindex="-1"  data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close"  data-dismiss="modal" aria-hidden="true">×</button>
        
        <h4 class="modal-title">Change Analyst</h4>

      </div>
      <div class="modal-body">

        <?php echo form_open('admin/task/change_analyst');?>

          
<div class="form-group">
    <input type="hidden" name="id" class="form-control popup-id" value="">
    </div>


      <input type="hidden" name="validate_analyst_url" class="form-control validate_analyst_url"  value="/admin/task/validate_analyst_url">

            

<div class="form-group margin-bottom-10">
    <label for="pwd">Select Analyst</label>
    <select name="analyst" class="form-control popup-analyst" id="MySelect" >

    <option value="" selected>Select Analyst</option>
  <?php foreach($get_all_analyst as $analyst):?>



    <option value="<?php echo $analyst->id;?>"><?php echo $analyst->first_name ." ".$analyst->last_name;?></option>
        <?php endforeach;?>
    
    </select>
      <span class="error-msg"></span>
  </div>

              
      

      <div class="modal-footer ">



        <input class="btn1 btn-default save" type="submit" value="Update">

        <a href="/admin/task/inprogress" class="btn btn-default cancel-popup">CANCEL</a>

         <?php echo form_close(); ?>

     </div>

     </div>

   </div>

  </div>

</div>



 <div id="add-time-page" class="modal fade delete-confirmation-task" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        
        <h4 class="modal-title"><?php echo "Delete Tasks";?></h4>

      </div>
      <div class="modal-body">

          Are you sure you want to delete?

            </div>



      <div class="modal-footer ">

        <input class="btn btn-danger delete" type="submit" value="YES">


        <a href="/admin/task/<?php echo $this->uri->segment(3); ?>" class="btn btn-default cancel-popup">CANCEL</a>
      </div>
  
    </div>
  </div>
</div>














