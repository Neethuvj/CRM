
<article class="rs-content-wrapper customer-list" >
    <div class="rs-content" >
      <div class="rs-inner"> 
        <!-- Begin default content width -->
        <div class="container-fluid container-fluid-custom">


          <!-- Begin Panel -->
          <div class="panel panel-plain panel-rounded">
            <div class="p-t-xs">
                   <div class="support-team"> 
               

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



 <form class="form-horizontal margin-bottom-10" action="/admin/team/<?php echo $this->uri->segment(3); ?>" method="post">
      <div class="filter-panel clearfix">

      <div class="row">
 <div class="col-md-6">



  <div class="form-group col-md-12">
       <label class="control-label col-sm-2" for="search_customer"> Customer:</label>
    <div class="col-sm-10">

       <select class="form-control" name="search_customer">
                        <option value="" selected>Select Customer</option>

                          <?php foreach($customer_list as $customer): ?>
                          
                              <option value="<?php echo $customer->id; ?>" <?php echo (isset($search_array['search_customer']) && $search_array['search_customer'] == $customer->id) ?  "selected" : " "; ?>><?php echo $customer->first_name." ".$customer->last_name; ?></option>
                          <?php endforeach; ?>
                          </select>
 
    </div>
  </div>
  </div>
  <div class="col-md-6">

   <div class="form-group has-feedback datepicker-block col-md-12">
    
        <label class="control-label col-sm-2" for="pwd">Created Date:</label>
              <div class="col-sm-5">
 
                      <input type="text" class="form-control rs-datepicker"  placeholder="MM/DD/YYYY" name="search_from_date" value=<?php echo isset($search_array['search_from_date']) ?  $search_array['search_from_date'] : " "; ?>>
                     
                  </div>

                   <div class="col-sm-5">
 
                      <input type="text" class="form-control rs-datepicker"  placeholder="MM/DD/YYYY" name="search_to_date"  value=<?php echo isset($search_array['search_to_date']) ?  $search_array['search_to_date'] : " "; ?>>
                     
                  </div>
    </div>



  </div>
  <div class="clearfix">
  
  <button type="submit" name="submit" value="Submit" class="btn btn-default">Search</button>

  <a href="/admin/team/<?php echo $this->uri->segment(3); ?>/reset" class="btn btn-danger">Reset</a>
  </div>
  </div>
  </div>
</form> 


                       <div class="table-responsive">
                      <?php
                       echo form_open('admin/team/assign_bda');
                       ?>
                       <?php if(!empty($user_list)): ?>
                      <table class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th  class="no-sort">ID <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th class="no-sort">Created Date<!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            
                             <th  class="no-sort">Account Name <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                           
                            <th  class="no-sort">Email <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                                 <th  class="no-sort">Status <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th class="no-sort">Info</th>
                            <?php if($this->uri->segment(3) == 'approved'): ?>

                              <th  class="no-sort ">Hourly Rate ($)</th>
                              <th  class="no-sort">Monthly Hours</th>
                      

                            <?php endif; ?>  
                   
                            <th  class="no-sort">Set Plan Amount and Hours</th>
                          
                     
                         
                          </tr>
                        </thead>
                        <tbody>
                          <?php

                            foreach($user_list as $row)
                            {
                              

                               echo '<tr>';
                               echo '<td>'.$row->id.'</td>';

                               echo '<td>'.date("m/d/Y h:i:s a", strtotime($row->created)).'</td>';

                              
                                 echo '<td>'.$row->first_name." ".$row->last_name.'</td>';


                               echo '<td>'.$row->username.'</td>';

                             

                           
                     echo "<td>".ucwords($row->status_name)."</td>";
                    
                                 echo '<td><a href="'.base_url().'admin/customer/user_edit/'.$row->id.'".><i class="glyphicon glyphicon-user"></i></a></td>';

                              if($this->uri->segment(3) == 'approved'){
                                  echo  '<td class="hourly-rate">'.$row->plan_amount_per_hour. '</td>';
                                  echo  '<td class="plan-hour">'.$row->plan_hours. '</td>';

                              }


                                if($row->status_id == 1){

                                  echo "<td><span class='text-danger'>Account is currently active.</span></td>";
                                }
                                  else{
  echo '<td><a class="set-plan-amount-link" data-user-id='.$row->id.' href="#"><i class="glyphicon glyphicon-pencil"></i></a></td>';
}

                              // echo '<td></td>';
                              // echo '<td></td>';
                              // if(isset($type_of_task) && $type_of_task !== "deleted_tasks" && $type_of_task !== "summary"){
                              // echo '<td class="crud-actions">
                              //   <input name= "delete[]" type="checkbox" value = '.$row->id.'>  
                                
                              // </td>';
                              // }
                              echo '</tr>';
                            }
                            ?>  
                        </tbody>
                      </table>
                   

  <?php echo '<div class="pagination">'.$this->pagination->create_links().'</div>'; ?>
                  
                   
                     

                      <?php else: ?>
                        <div class="alert alert-danger">No Data Found.</div>
                    <?php endif; ?>

   <?php echo form_close(); ?>
                       
                    </div>
            </div>
            </div>
          </div>
          
        </div>

      </div>
    </div>

  </article>
  <div id="push"></div>




   
  



 






  <div id="add-time-page" class="modal fade set-plan-amount-popup" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
       <?php echo form_open('admin/team/set_plan_amount');?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        
        <h4 class="modal-title">Set Plan Amount and Hours</h4>

      </div>
      <div class="modal-body">

              <input type="hidden" name="customer_id" value="" class="customer_id"> 
                <input type="hidden" name="validate_team_plan_url" class="form-control validate_team_plan_url"  value="/admin/team/validate_team_plan_url">
                    <input type="hidden" name="redirect_url" class="form-control redirect_url"  value="/admin/team/<?php echo $this->uri->segment(3); ?>">

             <div class="row plan-initial-details">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Monthly Hours *</label>
                      <input type="text" class="form-control plan-hours"  name="plan_hours" value="" > 

                       <span class="error-msg"></span>

                    </div>
                  </div>

                       <div class="col-md-12">
                    <div class="form-group">
                      <label>Hourly Rate ($)*</label>
                      <input type="text" class="form-control plan-amount" name="plan_amount" value="" > 

                       <span class="error-msg"></span>

                    </div>
                  </div>
                  </div>

                  <div class="plan-total-details"></div>

            </div>



      <div class="modal-footer ">

        <input class="btn btn-default calculate-final" type="submit" value="Submit">
           <input class="btn btn-default save" type="submit" value="Submit">

        <a href="/admin/team/<?php echo $this->uri->segment(3); ?>" class="btn btn-danger cancel-popup">CANCEL</a>
      </div>
  <?php echo form_close(); ?>
    </div>
  </div>
</div>






  