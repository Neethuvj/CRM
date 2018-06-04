

<article class="rs-content-wrapper plan-listing" >
    <div class="rs-content" >
      <div class="rs-inner"> 
        <!-- Begin default content width -->
        <div class="container-fluid container-fluid-custom"> 
          
          <!-- Begin Panel -->
          <div class="panel panel-plain panel-rounded">
            <div class="p-t-xs">
                   <div class="support-team"> 
              <ul class="list-group task-list" style="list-style-type: none;">
              
              <table class="table table-bordered table-striped no-sort">
                        <thead>
                          <tr>
                           <th class="no-sort">ID</th>
                            <th class="no-sort">Type</th>
                            <th class="no-sort">Amount</th>
                            <th class="no-sort">Hours</th>
                            <th class="no-sort">Operations</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            foreach($plan_list as $plan)
                            {
                              
                              echo '<tr>';
                              echo '<td class="plan-id">'.$plan->id.'</td>';
                              echo '<td class="plan-name">'.$plan->name.'</td>';
                              echo '<td class="plan-amount">'.$plan->plan_amount.'</td>';
                              echo '<td class="plan-hour">'.$plan->plan_hours.'</td>';
                               echo '<td><a href="" class="btn btn-primary edit-plan-model" >Edit</a></td>';                             
                              echo '</tr>';
                            }
                            ?>  
                        </tbody>
                      </table>
                
              </ul>
              
            </div>
            </div>
          </div>
          <a class="btn btn-primary" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Back</a>
        </div>

      </div>
    </div>
  </article>
  <div id="push"></div>
  



  <div id="add-time-page" class="modal fade update-plan" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Update Plan</h4>
      </div>
      <div class="modal-body">
        <?php echo form_open('admin/configuration/plan');?>


 
           
              <div class="form-group">
                  <label>Plan Name *</label>
                  <input type="text" name="plan_name" class="form-control plan_name" value="<?php //echo $connections;?>">
                  <span class="error-msg"></span>
         
                </div>
            


      
        <input type="hidden" name="plan_id" class="form-control plan_id" value="">
     


      <input type="hidden" name="validate_plan_url" class="form-control validate_plan_url" value="/admin/configuration/plan_validate">
     
          
                  <div class="form-group">
                  <label>Plan Amount *</label>
                  <input type="text" name="plan_amount" class="form-control plan_amount" value="<?php //echo $connections;?>">
                  <span class="error-msg"></span>
                </div>
            
          

    
                  <div class="form-group">
                  <label>Plan Hour *</label>
                  <input type="text" name="plan_hour" class="form-control plan_hour" value="<?php //echo $connections;?>">
                  <span class="error-msg"></span>
                </div>
          

      </div>
      <div class="modal-footer">

        <input class="btn btn-default" type="submit" value="Save">
        <a href="/admin/configuration/plan" class="btn btn-danger">CLOSE</a>
         <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>