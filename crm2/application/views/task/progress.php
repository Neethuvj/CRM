<article class="rs-content-wrapper task-list">
    <div class="rs-content">
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



<?php if(isset($type_of_task)): ?>


<div class="filter-panel ss-active">

<form class="form-horizontal  margin-bottom-10" action="/task/<?php echo $type_of_task; ?>" method="post">
<?php if($role_id == 2 || $role_id == 9 ): ?> 
 <div class="row">
  <div class="col-md-6">
   <div class="form-group has-feedback datepicker-block">
        <label class="control-label col-sm-3">Created Date:</label>
             <div class="col-sm-9 ss-select"> 
             <div class="row">  
             <div class="col-sm-6 pad-left0"> 
      <input type="text"  name="created_start_date" class="form-control rs-datepicker" placeholder="MM-DD-YYYY"  value="<?php echo $created_start_date ?>">
      </div>
      <div class="col-sm-6 pad-right0">
          <input type="text"  name="created_to_date" class="form-control rs-datepicker" placeholder="MM-DD-YYYY" value="<?php echo $created_to_date?>">
          </div>
          </div>
    
      </div>

  </div>
 </div>


  <?php endif; ?>
 <div class="col-md-6 ssheight50">
  <div class="form-group ">
    <label for="pwd" class="col-sm-3">Target Name:</label>
    <div class="col-sm-9 ss-select">
    <select name="search_target" class="form-control">
       <option value="" selected>Select Target</option>


            <?php foreach($get_target_names as $get_target_name):?>


                  <?php if($search_target==$get_target_name->target_name){
                    $selected_text = "selected";}
                    else
                    {
                      $selected_text = ""; 
                    }


                    ?>



         <option value="<?php echo $get_target_name->target_name;?>" <?php echo $selected_text;?>><?php echo $get_target_name->target_name;?></option>


          
        <?php endforeach;?>
    </select>

    </div>

  </div>
 </div> 
 <!-- <div class="clearfix"></div>-->

<div class="row">
<div class="col-md-6">

  <?php if($role_id == 2 ): ?>
    <?php if($company == 1): ?>
    
  <div class="form-group ">
  <div class="row">
    <label for="pwd"  class="col-sm-3 text-right pad0" >Team Members:</label>
     <div class="col-sm-9 ss-select">
    <select  name="search_team_member" class="form-control" id="search_team_member">
    <option value="" selected>Select Team Member</option>
     <?php foreach($get_all_team_members as $key => $get_all_team_member):?>

       <?php 

       if($search_team_member==$get_all_team_member->member_id){
       $selected_text = "selected";}
        else
        {
          $selected_text = ""; 
        }

        ?>
         
        <option value="<?php echo $get_all_team_member->member_id;?>" <?php echo $selected_text;?>><?php echo $get_all_team_member->first_name ." ".$get_all_team_member->last_name;?></option>
        <?php endforeach;?>
    </select>
    </div>
    </div>
  </div>


  <?php endif; ?>
  <?php endif; ?>

</div>
<div class="col-md-6">
  
    <?php if($role_id == 3 ): ?>

  <div class="form-group ">
    <label for="pwd"  class="col-sm-3">Customers:</label>

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


<div class="row">
<div class="col-md-6">
  <div class="form-group">
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

<div class="col-md-6">
  <div class="form-group ">
    <label for="pwd"  class="col-sm-3" >Team Members:</label>
     <div class="col-sm-9 ss-select">
    <select  name="search_team_member" class="form-control" id="search_team_member">
    <option value="" selected>Select Team Member</option>
     <?php foreach($get_all_team_members as $key => $get_all_team_member):?>

       <?php 
       if($search_team_member == $get_all_team_member->id){
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

 </div>  
 
<!--<div class="row">
<div class=" col-md-6">
   <div class="form-group has-feedback datepicker-block">
        <label class="col-sm-3">Date of Meeting:</label>
             <div class="col-sm-9 ss-select">    
      <input type="text"  name="search_meeting_date_time" class="form-control rs-datepicker" placeholder="MM/DD/YYYY" value="<?php $search_meeting_date_time; ?>">
      
      </div>

  </div>
  </div>
</div>-->

<div class="row">
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
</div>
<?php endif; ?>
<?php
if($role_id == 2 || $role_id == 9){

  $text_align = "text-right";
}
else{
  $text_align ="";
}

if($role_id == 3){

  $margin_top10 = "margin-top10";
}
else{
  $margin_top10 = " ";
}
?>
<div class="row">
  
<div class="col-md-12">
<div class="form-group <?php echo $margin_top10; ?> <?php echo $text_align; ?> sspad15">

 
  <button type="submit" name="submit" value="Submit" class="btn btn-default">Search</button>


  
   <a href="/task/<?php echo $this->uri->segment(2);?>/reset" class="btn btn-danger" >Reset</a>


     <button type="submit" name="csv_export" value="CSV Export" class="btn btn-primary">Export</button>
  </div>
  </div>
 
 </div>
  </div>
  </form>
  </div> <!--filter panel-->
 <?php if($role_id == 2 || $role_id == 9): ?>
</div>
</div>
  <?php endif;
 ?>

 


  <?php if(isset($type_of_task) && $type_of_task == "summary" && $role_id == 2 ): ?>

     <div class=" text-center">
                  <ul class="user-details-header clearfix">
                      <li><label>Client Name : </label><span class ="margin-left5"><?php echo $user_details[0]->first_name." ".$user_details[0]->last_name; ?></span></li>
                       <li><label>Plan : </label><span class="margin-left5"><?php echo $plan_name;?></span></li>
                           <li><label>Tasks : </label><span class="margin-left5"><?php echo $count_tasks;?></span></li>
                           <li><label>Connections: </label><span class="margin-left5"><?php echo array_sum($no_of_connections_total); ?></span></li>
                           <li><label>Hours Spent: </label><span class="margin-left5"><?php echo $grand_total_hour . ' hour(s) ' . $grand_total_min." minute(s)"; ?></span></li>
                        <li><label>Start Date : </label><span class="margin-left5"><?php echo date("m/d/Y", strtotime($user_details[0]->created ));?></span></li>

                      
                    </ul>
                </div>
              <?php endif; ?>


                  <div role="tabpanel" class="tab-pane active" id="home">
                    <div class="table-responsive">
                      <?php
                    
                       echo form_open('task/delete');
                       ?>
                       <?php if(!empty($tasks)): ?>
                      <table class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Task ID <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th class="no-sort">Account Name <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <?php if($role_id != 9): ?>
                             <th class="no-sort">Team Member <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                              <?php endif; ?>
                           
                            <?php if(isset($type_of_task) && $type_of_task == "summary"): ?>
                             <th  class="no-sort">Task Status <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                           <?php endif; ?>
                            
                             


                            <th class="no-sort">Target Name <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                             <th class="no-sort">Task Type<!-- <i class="fa fa-fw fa-sort"></i> --></th>
                             <?php if($role_id == 3): ?>
                              <?php if($type_of_task == "inprogress"): ?>
                               <th class="no-sort">Date of Meeting</th>
                             <?php endif; ?>
                              <?php endif; ?>
                            <th class="no-sort">No.of Connections <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th class="no-sort" colspan="2">Hours Spent <!-- <i class="fa fa-fw fa-sort"></i> --></th>

                             <?php if($role_id == 3): ?>

                                  <th class="no-sort">Analyst <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                                 <!-- <th class="no-sort">Status <!-- <i class="fa fa-fw fa-sort"></i> get_teammembers_tasks</th>-->
                          
                              <?php endif; ?>

                             <?php if(isset($type_of_task) && $type_of_task !== "deleted_tasks" && $type_of_task !== "summary" && $role_id == 2): ?>
                            <th class="no-sort">Select</th>
                          <?php endif; ?>


                          </tr>
                        </thead>
                        <tbody>
                          <?php


                            foreach($tasks as $row)

                            {

                              $logged_hours = "";
if(!empty($row->final_logged_hours)){
 $logged_hours .= $row->final_logged_hours . " hrs ";

}
if(!empty($row->final_min_to_add)){
 $logged_hours .= $row->final_min_to_add . " min ";

}
                              
                              echo '<tr>';
                              echo '<td>'.'<a href="/task/view/' . $row->id .'">'.$row->id.'</a></td>';

                            echo '<td>'.$row->account_name.'</td>';
if($role_id != 9):
echo "<td>";

                              if(!empty($team_array[$row->user_id])) {

                                  echo $team_array[$row->user_id];

                              }
                              else{

                                echo " ";
                              }


echo "</td>";
 
 endif;                             
                              
                              if(isset($type_of_task) && $type_of_task == "summary"){
                                echo '<td>'.$row->task_status_name.'</td>';

                              }




                              echo '<td>'.$row->target_name.'</td>';
              if($role_id == 3){

                              echo '<td>'.'<a href="/task/edit/' . $row->id .'">'.$row->task_name.
                              '</a></td>';

                              if($type_of_task == "inprogress"){
                                  if($row->meeting_date_time !== NULL){

                                  echo '<td>'.date("m/d/Y h:i A", strtotime($row->meeting_date_time)).'</td>';

                                }
                                 else{
                                   echo '<td></td>';
                                    }

                              }
                            }
                            else{

                               echo '<td>'.'<a href="/task/view/' . $row->id .'">'.$row->task_name.
                              '</a></td>';
                              }
                              echo '<td>'.$row->no_of_connections.'</td>';
                             
                              echo '<td colspan="2">'.$calculated_hours[$row->id]['logged_hrs']." "."hour(s)".'<br>'.$calculated_hours[$row->id]['logged_min']." "."minute(s)".'</td>';

                              if($role_id == 3){


                              echo '<td>'.$row->first_name.'</td>';
                             // echo '<td>'.$row->task_status_name.'</td>';

                               }


                              if(isset($type_of_task) && $type_of_task !== "deleted_tasks" && $type_of_task !== "summary" && $role_id == 2){
                              echo '<td class="crud-actions">
                                <input name= "delete[]" type="checkbox" value = '.$row->id.'>  
                                
                              </td>';
                              }
                              echo '</tr>';
                            }
                            ?>  
                        </tbody>
                      </table>
                   
                      <?php echo '<div class="pagination">'.$this->pagination->create_links().'</div>'; ?>

                      <?php else: ?>
                        <div class="alert alert-danger">No Data Found.</div>
                    <?php endif; ?>


                        <div class="form-submit">
                   <?php if(!empty($tasks)&&isset($type_of_task) && $type_of_task !== "deleted_tasks" && $type_of_task !== "summary" && $role_id == 2): ?>
                    <input class="btn btn-default" type="submit" value="Delete Selected">


                   
                        

                    <!-- <input class="btn btn-default" type="button" value="Cancel"> -->
                  <?php endif;?>
                  </div>
                      <?php echo form_close(); ?>
                    </div>
                  </div>
                  
</div>

                  <?php else: ?>
                   <div class="alert alert-info">Contents for this tab is yet to be finalized</div>
                <?php endif; ?>
                </div>
              </div>

                 
            </div>
          </div>


        </div>
      </div>
    </div>
    </article>

 <div id="push"></div>




