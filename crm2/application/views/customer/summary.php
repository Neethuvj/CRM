<article class="rs-content-wrapper task-list task-user-summary">
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


<ul class="nav nav-tabs margin-bottom-10">
 <li class="<?php echo $task_status_id == 'all'? 'active' : 'no-active'; ?>"><a href="/customer/user_summary/<?php echo $customer_id; ?>/all">Summary</a></li>
  <li class="<?php echo $task_status_id == 'in_progress'? 'active' : 'no-active'; ?>"><a href="/customer/user_summary/<?php echo $customer_id; ?>/in_progress">In Progress</a></li>
  <li class="<?php echo $task_status_id == 'completed'? 'active' : 'no-active'; ?>"><a href="/customer/user_summary/<?php echo $customer_id; ?>/completed">Completed</a></li>
    <li><a href="/customer/team_members/<?php echo $customer_id; ?>/active">Team  Members</a></li>
  
  
  
</ul>


          <!-- Begin Panel -->
          <div class="panel panel-plain panel-rounded">
            <div class="p-t-xs">
     



              <div class="support-team"> 
                

      
                <div class="clearfix"></div>
                <!-- Tab panes -->
                <div class="tab-content">




                  <?php if(isset($type_of_task) && $type_of_task !== "completed_tasks"): ?>

<form class="form-horizontal margin-bottom-10 user-summary-filter-form" action="/customer/user_summary/<?php echo $customer_id; ?>/<?php echo $task_status_id; ?>" method="post">
      <div class="filter-panel clearfix">

      <div class="row">

  <div class="col-md-6">

   <div class="form-group has-feedback col-md-12 datepicker-block margin-bottom-10 ">
    
        <label class="control-label col-sm-4" for="pwd">Billing Period:</label>
              <div class="col-sm-4">

                      <input type="text" class="form-control rs-datepicker"  placeholder="MM/DD/YYYY" name="search_from_date" value=<?php echo isset($search_array['search_from_date']) ? $search_array['search_from_date'] : ""; ?> >
                     
                  </div>

                   <div class="col-sm-4">
 
                      <input type="text" class="form-control rs-datepicker"  placeholder="MM/DD/YYYY" name="search_to_date" value=<?php echo isset($search_array['search_to_date']) ? $search_array['search_to_date'] : ""; ?>>
                     
                  </div>
    </div>



  

   <div class="form-group has-feedback  col-md-12 margin-bottom-10 ">
    
        <label class="control-label col-sm-4" for="pwd">Target Name:</label>
             

         
 <div class="col-sm-4">
                        <select name="search_target" class="form-control">
       <option value="" selected>Select Target</option>
            <?php foreach($get_target_names as $get_target_name):?>


  <?php if($search_array['search_target']==$get_target_name->target_name){
        $selected_text = "selected";}
        else
        {
          $selected_text = ""; 
        }


        ?>

         <option value="<?php echo $get_target_name->target_name;?>"<?php echo $selected_text;?>><?php echo $get_target_name->target_name;?></option>
        <?php endforeach;?>
    </select>
    </div>
                     
         
    </div>



  </div>

  <div >
  
  <button type="submit" name="submit" value="Submit" class="btn btn-default">Search</button>


  <a href="/customer/user_summary/<?php echo $customer_id; ?>/<?php echo $task_status_id; ?>/reset" class="btn btn-danger">Reset</a>
  </div>

  <div class="form-group">
  <div class="col-sm-offset-1 col-sm-10">
   <button type="submit" name="csv_export" value="CSV Export" class="btn btn-primary">Export</button>
   </div>
  </div>
  </div>
  </div>
</form> 


          <?php if(isset($type_of_task) && $type_of_task == "summary"): ?>
     <div class=" text-center">
                  <ul class="user-details clearfix">
                      <li><label>Name : </label><span class="margin-left5"><?php echo $user_details[0]->first_name; ?></span></li>
                       <li><label>Plan : </label><span class="margin-left5"><?php echo $plan_name;?></span></li>
                        <li><label>Status : </label><span class="margin-left5"><?php echo $user_status; ?></span></li>
                         
                        <li><label>Start Date : </label><span class="margin-left5"><?php echo date("m-d-Y", strtotime($user_details[0]->created ));?></span></li>

  <li><label>Tasks: </label><span class="margin-left5"><?php echo $count_tasks; ?></span></li>
    <li><label>Connections: </label><span class="margin-left5"><?php echo array_sum($no_of_connections_total); ?></span></li>
                           <li><label>Hours Spent: </label><span class="margin-left5"><?php echo $grand_total_hour .' hour(s) ' . $grand_total_min; ?> minute(s)</span></li>
                      
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
                            <th class="no-sort">Target Name <!-- <i class="fa fa-fw fa-sort"></i> --></th>

                            <th class="no-sort">Task Type<!-- <i class="fa fa-fw fa-sort"></i> --></th>
                         
                           
                             <th class="no-sort">Analyst <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                              <th class="no-sort">Status <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                                 <?php if(isset($type_of_task) && $type_of_task == "summary"): ?>
                             <th  class="no-sort">Task Status <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                           <?php endif; ?>
                            <th class="no-sort">No. of Connections <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th class="no-sort">Time Spent <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            
                             <?php if(isset($type_of_task) && $type_of_task !== "deleted_tasks" && $type_of_task !== "summary"): ?>
                            <th class="no-sort">Select</th>
                          <?php endif; ?>


                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            foreach($tasks as $row)
                            {
                              

                              
                              echo '<tr>';
                              echo '<td>'.'<a href="/task/edit/' . $row->id .'">'.$row->id.'</a></td>';
                            
                              

                              echo '<td>'.$row->account_name.'</td>';
                              echo '<td>'.$row->target_name.'</td>';
                              
                                echo '<td>'.'<a href="/task/edit/' . $row->id .'">'.$row->task_name.
                              '</a></td>';

                                echo '<td>'.$row->first_name.'</td>';
                              echo '<td>'.$row->analyst_status_name.'</td>';
                              if(isset($type_of_task) && $type_of_task == "summary"){
                                echo '<td>'.$row->task_status_name . '</td>';

                              }
                              echo '<td>'.$row->no_of_connections.'</td>';
                              //echo '<td></td>';
                              if(!empty($logged_hours_total[$row->id][0]->log_hrs) && !empty($logged_hours_total[$row->id][0]->log_min)){
                                echo '<td>'.$logged_hours_total[$row->id][0]->log_hrs.' hour(s) '.$logged_hours_total[$row->id][0]->log_min.' minute(s)</td>';
                              }

                              else{

                                if(empty($logged_hours_total[$row->id][0]->log_hrs) && empty($logged_hours_total[$row->id][0]->log_min)){
                                  echo '<td></td>';
                                }

                                elseif(empty($logged_hours_total[$row->id][0]->log_hrs) && !empty($logged_hours_total[$row->id][0]->log_min)){

                                     echo '<td>'.$logged_hours_total[$row->id][0]->log_min.' minute(s)</td>';
                                }

                                 elseif(!empty($logged_hours_total[$row->id][0]->log_hrs) && empty($logged_hours_total[$row->id][0]->log_min)){

                                     echo '<td>'.$logged_hours_total[$row->id][0]->log_hrs.' hour(s)</td>';
                                }



                                
                              }
                              
                             
                              if(isset($type_of_task) && $type_of_task !== "deleted_tasks" && $type_of_task !== "summary"){
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
                   <?php if(!empty($tasks)&&isset($type_of_task) && $type_of_task !== "deleted_tasks" && $type_of_task !== "summary"): ?>
                    <input class="btn btn-default" type="submit" value="Delete Selected">
                    <!-- <input class="btn btn-default" type="button" value="Cancel"> -->
                  <?php endif;?>
                  </div>
                      <?php echo form_close(); ?>
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
      <?php if($task_status_id !== 'in_progress' && $task_status_id !== 'completed'  ): ?>
   <a class="btn btn-primary" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Back</a>
 <?php endif; ?>
    </div>

    </div>

    </div>

  </article>

       

  <div id="push"></div>