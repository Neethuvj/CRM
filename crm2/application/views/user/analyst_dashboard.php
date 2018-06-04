<?php

$page = $_SERVER['PHP_SELF'];
$sec = "900";
header("Refresh: $sec; url=$page");

?>
 <div class="container products-detail admin-panel-signup">
  <article class="rs-content-wrapper">
    <div class="rs-content">
      <div class="rs-inner"> 
        <!-- Begin default content width -->
        <div class="container-fluid container-fluid-custom"> 
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
              <div class="project-activities">
                <h2 class="">Project Activity</h2>
                <div class="project_list longEnough mCustomScrollbar" data-mcs-theme="dark">
     <?php foreach($analayst_tasks as  $analayst_task):?> 

  
       <div class="project_list_item">
                    <div class="project_list_left">
                    <?php if($analayst_task->analyst_status_id == 9): ?>

                      <div class="tag deleted"><span ><?php echo 
                      $analayst_task->analyst_status;?></span></div>
                    <?php else: ?> 
                     <div class="tag"><span ><?php echo 
                      $analayst_task->analyst_status;?></span></div>
                    <?php endif;?>

                       <div class="task"><span >Task</span></div>
                      
                    </div>

                <?php if($analayst_task->analyst_status_id == 9): ?>                    
                    <div class="project_list_right"> <a href="#" class="title" style="cursor:default;"><?php echo 
                      $analayst_task->task_name;?></a>

                    <?php else : ?>

                     <div class="project_list_right"> <a href="<?php echo base_url('task/edit/'.$analayst_task->id)?>" class="title"><?php echo 
                      $analayst_task->task_name;?></a>

                      <?php endif;?> 
                                        
                      <p>
                      <?php 
                          $datetime = $analayst_task->created;
                          $date = date('j F Y', strtotime($datetime));
                          $time = date('h:i A', strtotime($datetime)); 
                          ?>


                      <?php echo  $date;?><span class="time"><?php echo $time; ?></span></p>
                    </div>
                  </div>
          
                <?php endforeach; ?>
                </div>
              </div>
              <div class="todays_meeting">

                <h2> Meetings <span class="date">
                
                </span></h2>

<div class="filter-panel ">

    <form class="margin-bottom-10" action="/user/dashboard" method="post">
        <div class="row">

            <div class=" col-md-4 ">
                <div class="form-group has-feedback datepicker-block">
                  <label class="control-label col-sm-2">From:</label>
              <div class="col-sm-10"> 
      <input type="text"  name="search_from_date" class="form-control rs-datepicker" placeholder="MM/DD/YYYY"  value="<?php echo $search_from_date; ?>">
      </div>
     
      
      </div>

  </div>

   <div class=" col-md-4 ">
                <div class="form-group has-feedback datepicker-block">
                  <label class="control-label col-sm-2">To:</label>
              <div class="col-sm-10"> 
      <input type="text" name="search_to_date" class="form-control rs-datepicker" placeholder="MM/DD/YYYY"  value="<?php echo $search_to_date; ?>">
      </div>
     
      
      </div>

  </div>
 


  <div class="col-md-3">
    <div class="form-group ">
  
 
  <button type="submit" name="submit" value="Submit" class="btn btn-default">Search</button>

 <a href="/user/dashboard" class="btn btn-danger">Reset</a>
  </div>
 
  </div>
 </div> 
</div>
</form> 

                               
              <div class="table-responsive">
                <div class="meeting-list">
                  <table class="table table-striped table-fixed">
                    <thead>
                      <tr>
                        <th>Task ID</th>
                        <th>Account Name</th>
                        <th>Target Name</th>
                        <th>Date and Time of Meeting</th>
                        <th>Task Type</th>
                     
                   
                      </tr>
                    </thead>
                    <tbody class="meeting-table">
                    <?php

                      if ($todays_meeting_tasks)
                        foreach($todays_meeting_tasks as $todays_meeting_task) :

                         if($todays_meeting_task->analyst_status_id != 9): 
                        
                      ?>
                      <tr>
                            <td><?php echo $todays_meeting_task->id;?></td>
                             <td><?php echo $todays_meeting_task->account_name;?></td>
                              <td><?php echo $todays_meeting_task->target_name;?></td>
                              
                           
                         
                          <td> <?php 
                    $datetime =$todays_meeting_task->meeting_date_time;  

$meeting_date_time= date('m/d/Y h:i A', strtotime($datetime));?>
                          
                          <?php echo $meeting_date_time;?>
                         </td>
                         <td><a href="<?php echo base_url('task/edit/'.$todays_meeting_task->id)?>"><?php echo $todays_meeting_task->task_name;?></td></a>

                         

   
                      </tr> 
                       <?php endif;?> 
                      <?php endforeach;?> 
                    </tbody>
                  </table>
                </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </article>
  <div id="push"></div>
  </div>







  