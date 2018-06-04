<article class="rs-content-wrapper member-list">
<div class="rs-content">
      <div class="rs-inner"> 
        <!-- Begin default content width -->
        <div class="container-fluid"> 

       <!-- Begin Panel -->
          <div class="panel panel-plain panel-rounded">
            <div class="p-t-xs">
             



              <div class="support-team"> 
                
 <?php if( $this->session->flashdata('success_message')): ?>
 <div class="alert alert-success">
          <?php
  echo $this->session->flashdata('success_message');

?>
</div>
<?php endif; ?>

 <?php if( $this->session->flashdata('warning_message')): ?>
 <div class="alert alert-danger">
          <?php
  echo $this->session->flashdata('warning_message');

?>
</div>
<?php endif; ?>
      
                <div class="clearfix"></div>
                <!-- Tab panes -->
                <div class="tab-content">

 <form class="form-horizontal margin-bottom-10" action="/team/<?php echo $this->uri->segment(2); ?>" method="post">
      <div class="filter-panel clearfix">

      <div class="row">

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
  <div class="form-group col-sm-3">
      <label class="control-label col-sm-3" for="pwd">Name:</label>
   <div class="col-sm-9">




   <select class="form-control" name="search_customer">
                        <option value="" selected >Select Customer Name</option>

                          <?php foreach($total_team_members as $total_team_member): ?>
                          
      <option value="<?php echo $total_team_member->id; ?>" <?php echo (isset($search_array['search_customer']) && $search_array['search_customer'] == $total_team_member->id) ?  "selected" : " "; ?>><?php echo $total_team_member->first_name . ' '.$total_team_member->last_name  ;  ?> </option>
                          <?php endforeach; ?>
                          </select>
    </div>
  </div>
    <div class="col-md-3">
  
  <button type="submit" name="submit" value="Submit" class="btn btn-default">Search</button>

  <a href="/team/<?php echo $this->uri->segment(2); ?>/reset" class="btn btn-danger">Reset</a>
  </div>
  </div>

  </div>

</form> 


   <div class=" text-center">
                  <ul class="user-details-team clearfix">
                      <li><label>Team Owner:</label><span class= "margin-left5"><?php echo $first_name." ".$last_name; ?></span></li>

                        <li><label>Created Date: </label><span class="margin-left5"><?php echo date("m/d/Y", strtotime($created ));?></span></li>
    <li><label>Tasks: </label><span class="margin-left5"><?php echo $count_tasks; ?></span></li>
    <li><label>Connections: </label><span class="margin-left5"><?php echo array_sum($no_of_connections_total); ?></span></li>
                           <li><label>Hours Spent:</label><span class="margin-left5"><?php echo $grand_total_hour . ' hour(s) ' . $grand_total_min; ?> minute(s)</span></li>
                      
                    </ul>
                </div>


                  <div role="tabpanel" class="tab-pane active" id="home">
                    <div class="table-responsive">
                    <?php
                       echo form_open('team/delete_team', array('id'=>'team_list'));
                       ?> 
                       
                       <?php if(!empty($team_members[0]->id)): ?>


                      <table class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Team Member Name <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th class="no-sort">Created Date<!-- <i class="fa fa-fw fa-sort"></i> --></th>
                         
                             <th  class="no-sort">Customer Info<!-- <i class="fa fa-fw fa-sort"></i> --></th>
                      
                            <th class="no-sort">Email Address <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                                   <th class="no-sort">Phone Number <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th class="no-sort">Monthly Usage<!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th class="no-sort">Total Hours Spent <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th class="no-sort">Total No. of Reports <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            
                            <th class="no-sort">Change Status</th>

                            <th class="no-sort">Delete</th>
                        


                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            foreach($team_members as $row)
                            {
                              
                              echo '<tr>';
                              echo '<td>'.$row->first_name . " ". $row->last_name . '</td>';
                              echo '<td>'.date("m/d/Y", strtotime($row->created)).'</td>';
                            echo '<td><a href="'.base_url().'team/edit/'.$row->id.'".><i class="glyphicon glyphicon-user"></i></a></td>';

                             

                              if($this->uri->segment(2)=="active"):

                             echo '<td><a href="/user/switch_user/'.$row->id.'">'.$row->username.
                              '</a></td>';

                              endif;

                               if($this->uri->segment(2)=="in_active"):

                             echo '<td>'.$row->username.'</td>';

                              endif;
                              

                                    echo '<td>'.$row->phone_number.'</td>';
                              echo '<td>'.$row->monthly_usage.' hour(s)</td>';
                              echo '<td>'.$row->log_hrs.' hour(s) <br>' . $row->log_min.' minute(s)' .'</td>';
                              echo '<td>'.$row->no_of_reports.'</td>';
                               echo '<td><input class="user_id_in_table" type="hidden" name="user_id" value="'.$row->id .'"><input type="hidden"  class="status_id_in_table" name="plan_id" value="'.$row->status_id .'"><input type="hidden"  class="email_id_in_table" name="email_id" value="'.$row->username .'"><a class="change-customer-status-link" href="#"><i class="glyphicon glyphicon-cog"></i> </a></td>';
                      
                              echo '<td class="crud-actions">
                                <input name= "delete[]" type="checkbox" value = '.$row->id.'>  
                                
                              </td>';
                             
                              echo '</tr>';
                            }
                            ?>  


                        </tbody>
                      </table>
                    <input name= "redirect_url" type="hidden" value ="/<?php echo $this->uri->segment(1); ?>/<?php echo $this->uri->segment(2); ?>">  
                      <?php echo '<div class="pagination">'.$this->pagination->create_links().'</div>'; ?>


                      <?php else: ?>
                        <div class="alert alert-danger">No Data Found. </div>
                    <?php endif; ?>
<?php if($this->uri->segment(2) == 'active'): ?>
 <a href="#" class="btn btn-primary create-team" data-toggle="modal" data-target="#add-time-page">Create Team Member</a>
<?php endif; ?>


                        <div class="form-submit pull-right">
               <input class="btn btn-default btn-delete-team" type="submit" value="Delete Selected">
                  </div>
                     <?php echo form_close(); ?> 
                    </div>
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

<?php if($this->uri->segment(2) == 'active'): ?>
    <div id="add-time-page" class="modal fade create-team" tabindex="-1"  data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" dismiss="modal" aria-hidden="true">×</button>
        
        <h4 class="modal-title">Create Team Member</h4>

      </div>
      <div class="modal-body">

        <?php echo form_open('team/create');?>

<div class="row">
<div class="col-md-6">
           <div class="form-group">
                  <label>First Name *</label>
                  <input type="text" name="first_name" class="form-control first_name" value="">
                  <span class="error-msg"></span>
         </div>
          </div>
           <div class="col-md-6">
                  <div class="form-group">
                  <label>Last Name *</label>
                  <input type="text" name="last_name" class="form-control last_name" value="">
                  <span class="error-msg"></span>
                </div>
                </div>
                </div>
            


      

     


      <input type="hidden" name="validate_team_url" class="form-control validate_team_url" value="/team/team_validate">
      <input type="hidden" name="parent_id" value="<?php echo $user_id; ?>">
     
                <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                  <label>Email *</label>
                  <input type="text" name="email" class="form-control email" value="">
                  <span class="error-msg"></span>
                </div>

                </div>
                  <div class="col-md-6">
                 <div class="form-group">
                  <label>Phone Number *</label>
                  <input type="text" name="phone_number" class="form-control phone_number" value="">
                  <span class="error-msg"></span>
                </div>
            
          

    
                </div>
</div>

                <div class="row">
                  <div class="col-md-6">
                  <div class="form-group">
                  <label>Password *</label>
                  <input type="password" name="password" class="form-control password" value="">
                  <span class="error-msg"></span>
                </div>
                  </div>
                  <div class="col-md-6">
                 <div class="form-group">
                  <label>Confirm Password *</label>
                  <input type="password" name="confirm_password" class="form-control confirm_password" value="">
                  <span class="error-msg"></span>
                </div>
                </div>
                </div>



               
            
                    

<div class="row">

                <div class="col-md-6">
                  <div class="form-group">
                  <label>Street Address *</label>
                   <input type="text" name="street_address" class="form-control street_address" value="">
                  <span class="error-msg"></span>
                </div>
                </div>
           
                <div class="col-md-6">
                <div class="form-group">
                  <label>City*</label>
                   <input type="text" name="city" class="form-control city" value="">
                  <span class="error-msg"></span>
                </div>
                </div>
                </div>

<div class="row">
                <div class="col-md-6">
                 <div class="form-group">
                  <label>State*</label>
                   <input type="text" name="state" class="form-control state" value="">
                  <span class="error-msg"></span>
                </div>
                </div>
               
                <div class="col-md-6">
                  <div class="form-group">
                  <label>Zip / Postal Code*</label>
                   <input type="text" name="zip" class="form-control zip" value="">
                  <span class="error-msg"></span>
                </div>
                </div>
                </div>
                <div class="row">
<div class="col-md-6">
                  <div class="form-group">
                  <label>Monthly Usage*</label>
                   <input type="text" name="monthly_usage" class="form-control monthly_usage" value="">
                  <span class="error-msg"></span>
                </div>
                </div>
                
<div class="col-md-6">
 <div class="form-group">
                  <label>Notification Hour*</label>
                   <input type="text" name="notification_hour" class="form-control notification_hour" value="">
                  <span class="error-msg"></span>
                </div>
                </div>
                </div>


                


         
       
      <div class="modal-footer ">

        <input class="btn btn-default save" type="submit" value="Save">
        <a href="/team/<?php echo $this->uri->segment(2); ?>" class="btn btn-default cancel-popup">CANCEL</a>
         <?php echo form_close(); ?>
      </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>



 <div id="add-time-page" class="modal fade change-team-status" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <span class="close" data-dismiss="modal" aria-hidden="true">×</span>
        <h4 class="modal-title">Change User Status</h4>
      </div>
      <div class="modal-body">
        <?php echo form_open('team/update_status');?>
           <input type="hidden" class="user_id_to_change_status" name="user_id" value=""> 
           
               <input type="hidden" class="current_status" name="current_status_id" value=""> 

                   <input type="hidden" class="redirect_url" name="redirect_url" value="<?php echo '/'.$this->uri->segment(1) .'/'.$this->uri->segment(2);?> "> 
           
           <input type="hidden" class="email_id_in_popup" name="email_id_in_popup" value=""> 
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group m-b-md">
                    
                       <?php foreach($status as $status_object): ?>



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
          

      </div>
      <div class="modal-footer">

        <input class="btn btn-default" type="submit" value="Save">
          <a href="/team/<?php echo $this->uri->segment(2); ?>" class="btn btn-danger cancel-popup">CANCEL</a>
         <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>



 <div id="add-time-page" class="modal fade delete-team-confirmation" tabindex="-1"  data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" dismiss="modal" aria-hidden="true">×</button>
        
        <h4 class="modal-title">Are you sure?</h4>

      </div>
      <div class="modal-body">
          Please note that deleting the team members will delete all the details about them, but all the tasks created by them will be moved to their team owner.
       
                </div>


                


         
       
      <div class="modal-footer ">

        <a href="/team/<?php echo $this->uri->segment(2); ?>" class="btn btn-default delete-yes-popup">Yes</a>
        
        <a href="/team/<?php echo $this->uri->segment(2); ?>" class="btn btn-primary cancel-popup">CANCEL</a>
        
      </div>
      </div>
    </div>
  </div>
</div>