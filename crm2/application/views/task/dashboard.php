<?php

$page = $_SERVER['PHP_SELF'];
$sec = "900";
header("Refresh: $sec;");

?> 
 <div class="container products-detail admin-panel-signup task-analyst-sortable">

<article class="rs-content-wrapper">
  <div class="rs-content">
    <div class="rs-inner"> 
      <!-- Begin default content width -->
      <div class="container-fluid container-fluid-custom"> 
        
        <!-- Begin Panel -->
        <div class="panel panel-plain panel-rounded">
          <div class="p-t-xs">
        
            <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center"> 
              <!--Task Block-->
              <ul class="task-blk text-left clearfix">
                <li>
                  <label>Total Tasks Assigned</label>
                  <span data-status-type-id="<?php echo $analyst_assigned_status_id; ?>"  class="total-count-<?php echo $analyst_assigned_status_id; ?>" data-total-count="<?php echo count($assigned_tasks); ?>"><?php echo count($assigned_tasks); ?></span></li>
                <li>
                  <label>Total Tasks In Progress</label>
                  <span  data-status-type-id="<?php echo $analyst_inprogress_status_id; ?>" class="total-count-<?php echo $analyst_inprogress_status_id; ?>" data-total-count="<?php echo count($inprogress_tasks); ?>"><?php echo count($inprogress_tasks); ?></span></li>
                <li>
                  <label>Total Completed Tasks</label>
                  <span data-status-type-id="<?php echo $analyst_completed_status_id; ?>" class="total-count-<?php echo $analyst_completed_status_id; ?>" data-total-count="<?php echo count($completed_tasks); ?>"><?php echo count($completed_tasks); ?></span></li>
              </ul>
            </div>
            <!--Task Block End--> 
      
            <!--Filter Panel Start-->
            <div class="clearfix"></div>
                <?php 
                 echo form_open('task/index',array(
    'class' => 'form-filter', 
    'role' => 'form'
));
      ?>
            <div class="col-lg-12">
             <div class="filter-panel clearfix">
       
              <div class="row">
              <div class="col-xs-12 col-lg-1 pad-full0 quick-filter-block col-md-1">
                <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pad-full0">
                    <label class="control-label">Quick Filter</label>
                  </div>
                </div>
              </div>
              <div class="col-lg-9 pad-full0">
                <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                    <div class="form-group">
                      <select class="form-control" name="search_type">
                        <option <?php echo (isset($conditions['search_type']) && $conditions['search_type'] == "Assigned Date") ?  "selected" : " "; ?>>Assigned Date</option>
                        <option <?php  echo (isset($conditions['search_type']) && $conditions['search_type'] == "Meeting Date") ?  "selected" : " "; ?>>Meeting Date</option>
                        
                      </select>
                    </div>
                  </div>
                  <!-- form group [rows] -->
                  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="form-group has-feedback datepicker-block">
                      <input type="text" class="form-control rs-datepicker"  placeholder="MM/DD/YYYY" name="search_from_date" value=<?php echo (isset($conditions['search_from_date'])) ? date("m/d/Y",strtotime($conditions['search_from_date'])) : " ";?>>
                      <span class="fa fa-calendar form-control-feedback" aria-hidden="true"></span> </div>
                  </div>
                  <!-- form group [search] -->
                  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="form-group has-feedback datepicker-block">
                      <input type="text" class="form-control rs-datepicker" placeholder="MM/DD/YYYY" name="search_to_date" value=<?php echo (isset($conditions['search_to_date'])) ? date("m/d/Y",strtotime($conditions['search_to_date'])) : " ";?>>
                      <span class="fa fa-calendar form-control-feedback" aria-hidden="true"></span> </div>
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                    <div class="form-group">
                      <select class="form-control" name="search_target_name" id="customer">
                        <option value="" disabled selected>Select Target Name</option>

                          <?php foreach($target_names as $target_name): ?>
                              <option value="<?php echo $target_name; ?>" <?php echo (isset($conditions['search_target_name']) && $conditions['search_target_name'] == $target_name) ?  "selected" : " "; ?>><?php echo $target_name; ?></option>
                          <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- form group [order by] -->
              <div class="col-xs-12 col-md-2 col-sm-2 col-lg-2 pad-full0">
                <div class="row">
                  <div class="col-lg-12 pad-full0 text-right">
                 
                    <input class="btn btn-primary reset-btn" type="submit" value="Filter">
 <a class="reset-icon" href="/task/index">Reset</a>
     
              

                  </div>
                </div>
              </div>
               <!--Filter Panel End--> 
             </div>

              </div>

             </div>
         <?php echo form_close(); ?>
    </div>
 

   
    <!--Task Board Panel -->
    <section id="task-board-panel">
      <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
          <h3  data-status-type-id="<?php echo $analyst_assigned_status_id; ?>" class="total-count-<?php echo $analyst_assigned_status_id; ?>" data-total-count="<?php echo count($assigned_tasks); ?>">Task Assigned <?php echo count($assigned_tasks); ?></h3>
          <div class="task-detail-panel">
          <ul  data-status-type-id="<?php echo $analyst_assigned_status_id; ?>" id="assigned-panel" class="sortable-wrapper">
          <?php foreach($assigned_tasks as $assigned_task): ?>

             <li>
              <div class="detailed-task">
            	<h2><a href="/task/edit/<?php echo $assigned_task->id; ?>"><?php echo $assigned_task->task_name; ?></a></h2>
              <div class="common-task-detail">
            	<div class="task-user-details clearfix">
                <p>Target : <span class="ss-user-name"><?php echo $assigned_task->target_name; ?></span></p>
                <p>Account Name : <span class="ss-account-name"><?php echo $assigned_task->account_name; ?></span></p>
                </div>
                <div class="task-number text-right"  data-type-id="<?php echo $assigned_task->id; ?>">
                 TS-<?php echo $assigned_task->id; ?>
                </div>
                <div class="task-email text-right"  data-type-email="<?php echo $assigned_task->email_to_notifiy; ?>">
              
                </div>

                 <div class="task-user-name text-right"  data-type-user-name="<?php echo $assigned_task->account_name; ?>">
              
                </div>
                </div>
            </div>
            </li>
          <?php endforeach; ?>
                 
            </ul>
          </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
          <h3 data-status-type-id="<?php echo $analyst_inprogress_status_id; ?>" class="total-count-<?php echo $analyst_inprogress_status_id; ?>" data-total-count="<?php echo count($inprogress_tasks); ?>" >Task In Progress <?php echo count($inprogress_tasks); ?></h3>
          <div class="task-detail-panel "> 
          
         <ul data-status-type-id="<?php echo $analyst_inprogress_status_id; ?>" id="inprogress-panel" class="sortable-wrapper">
     <?php foreach($inprogress_tasks as $inprogress_task): ?>
             <li>
              <div class="detailed-task">
              <h2><a href="/task/edit/<?php echo $inprogress_task->id; ?>"><?php echo $inprogress_task->task_name; ?></a></h2>
              <div class="common-task-detail">
              <div class="task-user-details clearfix">
                <p>Target : <span class="ss-user-name"><?php echo $inprogress_task->target_name; ?></span></p>
                <p>Account Name : <span class="ss-account-name"><?php echo $inprogress_task->account_name; ?></span></p>
                </div>
                <div class="task-number text-right" data-type-id="<?php echo $inprogress_task->id; ?>">
                 TS-<?php echo $inprogress_task->id; ?>
                </div>

                 <div class="task-email text-right"  data-type-email="<?php echo $inprogress_task->email_to_notifiy; ?>">
              
                </div>

                 <div class="task-user-name text-right"  data-type-user-name="<?php echo $inprogress_task->account_name; ?>">
              
                </div>
                </div>
            </div>
            </li>
          <?php endforeach; ?>
       </ul>
          </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
          <h3 data-status-type-id="<?php echo $analyst_completed_status_id; ?>"  class="total-count-<?php echo $analyst_completed_status_id; ?>" data-total-count="<?php echo count($completed_tasks); ?>" >Completed Tasks <?php echo count($completed_tasks); ?></h3>
          <div class="task-detail-panel ">
            <ul id="completed-panel" data-status-type-id="<?php echo $analyst_completed_status_id; ?>" class="sortable-wrapper">
                 <?php foreach($completed_tasks as $completed_task): ?>
             <li>
              <div class="detailed-task">
              <h2><a href="/task/edit/<?php echo $completed_task->id; ?>"><?php echo $completed_task->task_name; ?></a></h2>
              <div class="common-task-detail">
              <div class="task-user-details clearfix">
                <p>Target : <span class="ss-user-name"><?php echo $completed_task->target_name; ?></span></p>
                <p>Account Name : <span class="ss-account-name"><?php echo $completed_task->account_name; ?></span></p>
                </div>
                <div class="task-number text-right" data-type-id="<?php echo $completed_task->id; ?>">
                 TS-<?php echo $completed_task->id; ?>
                </div>

                   <div class="task-email text-right"  data-type-email="<?php echo $completed_task->email_to_notifiy; ?>">
              
                </div>

                 <div class="task-user-name text-right"  data-type-user-name="<?php echo $completed_task->account_name; ?>">
              
                </div>
                </div>
            </div>
            </li>
          <?php endforeach; ?>
            </ul>


          </div>
        </div>
      </div>
    </section>
  </div>
  </div>
  </div>
  </div>
  </div>
</article>
 <div id="push"></div>
</div>






<div id="add-time-page" class="modal fade confirmation-drag-drop" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" data-keyboard="false" data-backdrop="static" aria-hidden="true">
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
        <input class="btn btn-default cancel btn-no" type="submit" value="Cancel">
      </div>
    </div>
  </div>
</div>