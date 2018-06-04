
<article class="rs-content-wrapper customer-list" >
    <div class="rs-content" >
      <div class="rs-inner"> 
        <!-- Begin default content width -->
        <div class="container customer-container">


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

<?php 
// if(empty($search_array)) {
//   $search_array = $this->session->userdata('customer_search'); 

//         }
//     else{
//   $this->session->set_userdata('customer_search',$search_array);
//         }
//   if((strpos($_SERVER['HTTP_REFERER'], base_url()."admin/customer/") === false) || (isset($reset) && $reset == "reset")){

//         $this->session->unset_userdata('customer_search');
//         $search_array = false;
//     }

    ?>

 <form class="form-horizontal margin-bottom-10" action="/admin/customer/<?php echo $this->uri->segment(3); ?>" method="post">
      <div class="filter-panel clearfix">

      <div class="row">
 <div class="col-md-6">

  <div class="form-group col-md-12 ">
    <label class="control-label col-sm-2" for="search_bda"> BDA:</label>
    <div class="col-sm-10">


     <select class="form-control" name="search_bda">
                        <option value="" selected>Select BDA</option>

                          <?php foreach($bda_list as $bda): ?>
                          
                              <option value="<?php echo $bda->id; ?>" <?php echo (isset($search_array['search_bda']) && $search_array['search_bda'] == $bda->id) ?  "selected" : " "; ?>><?php echo $bda->first_name; ?></option>
                          <?php endforeach; ?>
                          </select>
   
    </div>
  </div>

  <div class="form-group col-md-12">
       <label class="control-label col-sm-2" for="search_customer"> Customer:</label>
    <div class="col-sm-10">

       <select class="form-control" name="search_customer">
                        <option value="" selected>Select Customer</option>

                          <?php foreach($customer_list as $customer): ?>
                          
                              <option value="<?php echo $customer->id; ?>" <?php echo (isset($search_array['search_customer']) && $search_array['search_customer'] == $customer->id) ?  "selected" : " "; ?>><?php echo $customer->first_name ." ".$customer->last_name; ?></option>
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


  <div class="form-group col-md-12">
      <label class="control-label col-sm-2" for="pwd">Plan:</label>
   <div class="col-sm-10">




   <select class="form-control" name="search_plans">
                        <option value="" selected>Select Plans</option>

                          <?php foreach($plans as $plan): ?>
                          
                              <option value="<?php echo $plan->id; ?>" <?php echo (isset($search_array['search_plans']) && $search_array['search_plans'] == $plan->id) ?  "selected" : " "; ?>><?php echo $plan->name; ?></option>
                          <?php endforeach; ?>
                          </select>
    </div>
  </div>
  </div>
  <div class="clearfix">
  
  <button type="submit" name="submit" value="Submit" class="btn btn-default">Search</button>

  <a href="/admin/customer/<?php echo $this->uri->segment(3); ?>/reset" class="btn btn-danger">Reset</a>
  </div>
  </div>
  </div>
</form> 


                       <div class="table-responsive">
                      <?php
                       echo form_open('admin/customer/assign_bda');
                       ?>
                       <?php if(!empty($users_list)): ?>
                      <table class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th  class="no-sort">ID <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th class="no-sort">Created Date<!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            
                             <th  class="no-sort">Account Name <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                           
                            <th  class="no-sort">Email <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                             <?php if($this->uri->segment(3) != 'old_customer') :?>
                            <th  class="no-sort">BDA <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                             <?php endif; ?>
                            <th  class="no-sort">Info <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                        <?php if($this->uri->segment(3) != 'old_customer') :?>
                            <th  class="no-sort">Summary <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                        <?php endif; ?>
                            <th  class="no-sort">Delete</th>
                          <?php if($this->uri->segment(3) !== 'pending_activation'): ?>
                            <?php if($this->uri->segment(3) != 'old_customer') :?>
                            <th  class="no-sort">Change Status</th>
                          <?php endif; ?>
                          <?php else: ?>
                           <th class="no-sort">Activation Link</th>
                        <?php endif; ?>

                        <?php if($this->uri->segment(3) == 'old_customer') :?>
                          <th class="no-sort">Initiation Link</th>
                        <?php endif; ?>
                            <?php if($user_list_type == "Active"): ?>
                        <th class="no-sort">Update Card Details</th>
                            <th class="no-sort">Reset Password</th>
                            <th  class="no-sort">Change Plan</th>
                            <th class="no-sort" >Select</th>
                          <?php endif; ?>
                          </tr>
                        </thead>
                        <tbody>
                          <?php

                            foreach($users_list as $row)
                            {
                         
                      
                               echo '<tr>';
                               echo '<td>'.$row->id.'</td>';

                               echo '<td>'.date("m/d/Y h:i A", strtotime($row->created)).'</td>';

                              
                                 echo '<td>'.$row->first_name." ".$row->last_name.'</td>';


                               echo '<td> <a href="/user/switch_user/'.$row->id.'">'.$row->username.'</a></td>';

                              if($this->uri->segment(3) !== 'old_customer'): 
                               echo '<td>'.$row->bda_name.'</td>';
                               endif; 
                               echo '<td><a href="'.base_url().'admin/customer/user_edit/'.$row->id.'".><i class="glyphicon glyphicon-user"></i></a></td>';
          if($this->uri->segment(3) != 'old_customer'):                               
                     echo '<td><a href="'.base_url().'admin/customer/user_summary/'.$row->id.'/all".><i class="glyphicon glyphicon-list-alt"></i></a></td>';
                   endif;
                               echo '<td><a class="delete-customer-link" data-delete-id="'.$row->id.'" data-transaction-id='.$row->transaction_id.' href="#"><i class="glyphicon glyphicon-trash"></i></a></td>';
                             if($this->uri->segment(3) !== 'pending_activation'){
        if($this->uri->segment(3) !== 'old_customer'){
                             
 echo '<td><input class="user_id_in_table" type="hidden" name="user_id" value="'.$row->id .'"><input type="hidden"  class="status_id_in_table" name="plan_id" value="'.$row->status_id .'"><input type="hidden"  class="email_id_in_table" name="email_id" value="'.$row->username .'"> <input type="hidden"  class="plan_id_in_table" name="plan_id" value="'.$row->plan_id .'"><input type="hidden"  class="transaction_id_in_table" name="transaction_id" value="'.$row->transaction_id.'"><a class="change-customer-status-link" href="#"><i class="glyphicon glyphicon-cog"></i> </a></td>';
 }
                                      }
                                      else{

                                        // if(!empty($row->transaction_id)){
echo "<td><a href='".base_url() ."/user/activate_user/".$row->temp_token."'>".base_url() ."/user/activate_user/".$row->temp_token."</a></td>";
// }
//   else{
// echo "<td><a href='".base_url() ."/user/initiate_user/".$row->temp_token."'>".base_url() ."/user/initiate_user/".$row->temp_token."</a></td>";
//   }
                                    }
          if($this->uri->segment(3) == 'old_customer'){

             echo "<td><a href='".base_url() ."/user/initiate_user/".$row->temp_token."'>".base_url() ."/user/initiate_user/".$row->temp_token."</a></td>";

          }
                             
                              if($user_list_type == "Active"){
   
      echo '<td><a href="/admin/customer/update_credit_card/'.$row->id.'"><i class="glyphicon glyphicon-pencil"></i></a></td>';


                               echo '<td><a href="/admin/customer/customer_reset_password_by_admin/'.$row->id.'"><i class="glyphicon glyphicon-pencil"></i></a></td>';
                               echo '<td><input class="user_id_in_table" type="hidden" name="user_id" value="'.$row->id .'"> <input type="hidden"  class="plan_id_in_table" name="plan_id" value="'.$row->plan_id .'"><a href="#" class="update-plan-popup"><i class="glyphicon glyphicon-refresh"></i></a></td>';
                               echo '<td><input name= "assign_bda[]" type="checkbox" value = '.$row->id.'>  </td>';


                              }
                            
                             
                             echo '</tr>';
                             
                            }
                            ?>  
                        </tbody>
                      </table>
                   
 <?php if($user_list_type == "Active"): ?>
                    <div class="form-group col-md-4">
                          <label for="pwd">Choose BDA:</label>
                          <select name="bda_list" class="form-control">

                   <?php foreach($bda_list as $value): ?>
                              
                           
                            <option  value="<?php echo $value->id;?>"><?php echo $value->first_name;?></option>
                           
                         
                        <?php endforeach; ?>
                         </select>

                        </div>
                      

                         <div class="form-submit col-md-4">
                  
                    <input class="btn btn-default" type="submit" value="Assign BDA">
                    <!-- <input class="btn btn-default" type="button" value="Cancel"> -->
                 
                  </div>

<?php endif; ?>

                  
                   
                     

                      <?php else: ?>
                        <div class="alert alert-danger">No Data Found. </div>
                    <?php endif; ?>

   <?php echo form_close(); ?>
                       
                    </div>
            </div>
            </div>
          </div>
           <?php echo '<div class="pagination">'.$this->pagination->create_links().'</div>'; ?>
           <a class="btn btn-primary" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Back</a>
        </div>

      </div>
    </div>

  </article>
  <div id="push"></div>




   <div id="add-time-page" class="modal fade change-plan-model-popup" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Update Plan</h4>
      </div>
      <div class="modal-body">
        <?php echo form_open('admin/customer/update_plan');?>
           <input type="hidden" class="user_id_in_popup" name="user_id" value=""> 
           <input type="hidden" class="plan_id_in_popup" name="plan_id" value=""> 
                <div class="row plan-details">
                  <div class="col-md-12">
                    <div class="form-group m-b-md">
                    
                       <?php foreach($plans as $plan_id => $plan_obj): ?>



 <div class="radio radio-custom">
                        <label>
                       

                          <?php echo form_radio('plan_id_selected', $plan_obj->id, '',array('class'=>
                          'plan_list', 'id'=> 'plan_id_selected_'. $plan_obj->id)) ?>



                          <span class="checker"></span> <span class="check-label"><?php echo $plan_obj->name; ?></span><br>
                          (<?php echo $plan_obj->description; ?> )</label>
                      </div>

                      <?php endforeach; ?>
                     
                      
                    </div>
                  </div>
                </div>



             <div class="row plan-initial-details">
               <input type="hidden" name="validate_team_plan_url" class="form-control validate_team_plan_url"  value="/admin/team/validate_team_plan_url">
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
      <div class="modal-footer">

        <input class="btn btn-default" type="submit" value="Save">

          <a href="/#" class="btn btn-primary submit-change-plan-popup">SUBMIT</a>
           <a href="/#" class="btn btn-primary calculate-team-plan-total">CALCULATE TOTAL</a>
         <?php echo form_close(); ?>
      <a href="/admin/customer/<?php echo $this->uri->segment(3); ?>" class="btn btn-danger">CANCEL</a>
         <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>
  


  <div id="add-time-page" class="modal fade delete-customer-confirmation" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
       <?php echo form_open('admin/customer/delete_customer');?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        
        <h4 class="modal-title">Delete Customer?</h4>

      </div>
      <div class="modal-body">

      <input type="hidden" name="delete_customer_id" value="" class="delete_customer_id"> 
         <input type="hidden" name="transaction_id" value="" class="transaction_id"> 
          Are you sure you want to delete? This will delete all the records related to the customer.

            </div>



      <div class="modal-footer ">

        <input class="btn btn-danger" type="submit" value="DELETE">


        <a href="/admin/customer/<?php echo $this->uri->segment(3); ?>" class="btn btn-danger cancel-popup">CANCEL</a>
      </div>
  <?php echo form_close(); ?>
    </div>
  </div>
</div>


 <div id="add-time-page" class="modal fade change-user-status" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <span class="close" data-dismiss="modal" aria-hidden="true">×</span>
        <h4 class="modal-title">Change User Status</h4>
      </div>
      <div class="modal-body">
        <?php echo form_open('admin/customer/update_status');?>
           <input type="hidden" class="user_id_to_change_status" name="user_id" value=""> 
           
               <input type="hidden" class="current_status" name="current_status_id" value=""> 

               <input type="hidden" class="current_plan" name="current_plan_id" value=""> 
           
           <input type="hidden" class="email_id_in_popup" name="email_id_in_popup" value=""> 
           <input type="hidden" class="transaction_id_in_popup" name="transaction_id_in_popup" value=""> 
                <div class="row status-wrapper">
                  <div class="col-md-12">
                    <div class="form-group m-b-md">
                    
                       <?php foreach($statuss as $status_object): ?>



 <div class="radio radio-custom">
                        <label>
                          <?php echo form_radio('status_id_selected', $status_object->id, '',array('class'=>
                          'status_list')) ?>
                          <span class="checker"></span> <span class="check-label"><?php echo ucwords($status_object->name); ?></span><br>
                        </label>
                      </div>

                      <?php endforeach; ?>
                     
                      
                    </div>
                  </div>
                </div>

                   <div class="row plan-details">
                  <div class="col-md-12">
                    <div class="form-group m-b-md">
                    
                       <?php foreach($plans as $plan_id => $plan_obj): ?>



 <div class="radio radio-custom">
                        <label>
                       

                          <?php echo form_radio('plan_id_selected', $plan_obj->id, '',array('class'=>
                          'plan_list', 'id'=> 'status_plan_id_selected_'. $plan_obj->id)) ?>



                          <span class="checker"></span> <span class="check-label"><?php echo $plan_obj->name; ?></span><br>
                          (<?php echo $plan_obj->description; ?> )</label>
                      </div>

                      <?php endforeach; ?>
                     
                      
                    </div>
                  </div>
                </div>

                <div class="row plan-initial-details">
               <input type="hidden" name="validate_team_plan_url" class="form-control validate_team_plan_url"  value="/admin/team/validate_team_plan_url">
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
      <div class="modal-footer">

        <input class="btn btn-default" type="submit" value="Save">


        <input class="btn btn-primary btn-final-submit" type="submit" value="SUBMIT">



          <a href="/#" class="btn btn-primary submit-change-plan-popup">SUBMIT</a>
           <a href="/#" class="btn btn-primary calculate-team-plan-total">CALCULATE TOTAL</a>
          <a href="/admin/customer/<?php echo $this->uri->segment(3); ?>" class="btn btn-danger cancel-popup">CANCEL</a>
         <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>






  