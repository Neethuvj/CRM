
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



 <form class="form-horizontal margin-bottom-10" action="/admin/referral/<?php echo $this->uri->segment(3); ?>" method="post">
      <div class="filter-panel clearfix">

      <div class="row">
 <div class="col-md-6">

  <div class="form-group col-md-12 ">
    <label class="control-label col-sm-2" for="search_bda"> Customer:</label>
    <div class="col-sm-10">


      <select class="form-control" name="search_customer">
                        <option value="" selected>Select</option>

                          <?php foreach($customer_list as $customer): ?>
                          
                              <option value="<?php echo $customer->id; ?>" <?php echo (isset($search_array['search_customer']) && $search_array['search_customer'] == $customer->id) ?  "selected" : " "; ?>><?php echo $customer->first_name ." ".$customer->last_name; ?></option>
                          <?php endforeach; ?>
                          </select>
   
    </div>
  </div>

  <div class="form-group col-md-12">
       <label class="control-label col-sm-2" for="contacted_status"> Status:</label>
    <div class="col-sm-10">

       <select class="form-control" name="contacted_status">
                        <option value="" selected>Select</option>
                              <option value="1" <?php echo (isset($search_array['contacted_status']) && $search_array['contacted_status'] == "1") ?  "selected" : " "; ?>>Contacted</option>
                          		<option value="0" <?php echo (isset($search_array['contacted_status']) && $search_array['contacted_status'] == "0") ?  "selected" : " "; ?>>New</option>
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

  <a href="/admin/referral/<?php echo $this->uri->segment(3); ?>/reset" class="btn btn-danger">Reset</a>
  </div>
  </div>
  </div>
</form> 
                       <div class="table-responsive">
                      <?php
                       echo form_open('admin/customer/assign_bda');
                       ?>
                       <?php if(!empty($referrals_list)): ?>
                      <table class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th  class="no-sort">ID <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th class="no-sort">Date and Time<!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            
                             <th  class="no-sort">Customer <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                           
                            <th  class="no-sort">Refferal Name <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                             
                            <th  class="no-sort">Refferal Email <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            
                            <th  class="no-sort">Refferal Phone <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                      		<th  class="no-sort">Notes <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th  class="no-sort">Status <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                        
                            <th  class="no-sort">Delete</th>
                          
                          </tr>
                        </thead>
                        <tbody>
                          <?php

                            foreach($referrals_list as $row)
                            {
                               echo '<tr>';
                               echo '<td>'.$row->id.'</td>';

                               echo '<td>'.date("m/d/Y h:i A", strtotime($row->created_date)).'</td>';
                                 echo '<td>'.$row->first_name." ".$row->last_name.'</td>';
                                 echo '<td>'.$row->name.'</td>';
                                 echo '<td>'.$row->email.'</td>';
                                 echo '<td>'.$row->phone_number.'</td>';
                             echo '<td>
                             
                  					 <a class="change-referral-notes" data-referral-id="'.$row->id .'" data-notes="'.$row->notes .'" href="#"><i class="fa fa-book"></i> </a>
                  				   </td>';
 								echo '<td><input class="user_id_in_table" id="user_id_in_table" type="hidden" name="user_id" value="'.$row->id .'">
                  							<input type="hidden"  class="status_id_in_table" name="plan_id" value="'.$row->contacted_status .'">
                    					
                  						<a class="change-referral-status-link" href="#"><i class="glyphicon glyphicon-cog"></i> </a>
                  					</td>';

                              	echo '<td><a class="delete-customer-link" data-delete-id="'.$row->id.'" href="#"><i class="glyphicon glyphicon-trash"></i></a></td>';
                             
                             	echo '</tr>';
                            }
                            ?>  
                        </tbody>
                      </table>
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

  <div id="add-time-page" class="modal fade delete-customer-confirmation" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
       <?php echo form_open('admin/referral/delete_referral');?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        
        <h4 class="modal-title">Delete Customer?</h4>

      </div>
      <div class="modal-body">

      <input type="hidden" name="delete_referral_id" value="" class="delete_customer_id"> 
         
          Are you sure you want to delete?

            </div>

      <div class="modal-footer ">

        <input class="btn btn-danger" type="submit" value="DELETE">
        <a href="/admin/referral/<?php echo $this->uri->segment(3); ?>" class="btn btn-danger cancel-popup">CANCEL</a>
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
        <h4 class="modal-title">Change Contact Status</h4>
      </div>
      <div class="modal-body">
        <?php echo form_open('admin/referral/update_status');?>
           <input type="hidden" class="user_id_to_change_status" name="referral_id" value="wait am comin">
               <!-- <input type="hidden" class="current_status" name="current_status_id" value="">  -->

                <div class="row status-wrapper">
                  <div class="col-md-12 set_data">
                    <div class="form-group m-b-md">
 					
                    </div>
                  </div>
                </div>

      </div>
      <div class="modal-footer">

        <input class="btn btn-default" type="submit" value="Save">
      
          <a href="/admin/referral/<?php echo $this->uri->segment(3); ?>" class="btn btn-danger cancel-popup">CANCEL</a>
         <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>

<!-- Add/Edit Referral notes popup start-->
<div id="add-time-page" class="modal fade change-referral-note" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <span class="close" data-dismiss="modal" aria-hidden="true">×</span>
        <h4 class="modal-title">Add Notes</h4>
      </div>
      <div class="modal-body">
        <?php echo form_open('admin/referral/update_notes');?>
           <input type="hidden" class="user_id_to_change_notes" id="user_id_to_change_notes" name="referral_id" value="">
               <!--  <input type="hidden" class="current_notes" name="current_notes" value="">   -->

                <div class="row status-wrapper">
                  <div class="col-md-12 set_data">
                    <div class="form-group m-b-md">
		                          <?php echo form_textarea(array('name'=>'current_notes', 'class' => 'current_notes', 'id' => 'current_notes', 'value'=>'', 'rows' => '5',
     										 'cols' => '70',)); ?>
		                          <br>
                    </div>
                  </div>
                </div>

      </div>
      <div class="modal-footer">

        <input class="btn btn-default" type="submit" value="Save">
      
          <a href="/admin/referral/<?php echo $this->uri->segment(3);?>" class="btn btn-danger cancel-popup">CANCEL</a>
         <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>





  