
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



 <form class="form-horizontal margin-bottom-10" action="/customer/<?php echo $this->uri->segment(2); ?>" method="post">
      <div class="filter-panel clearfix">

      <div class="row">
 <div class="col-md-6">

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

 

  <div class="form-group col-md-12">
       <label class="control-label col-sm-2" for="search_customer"> Customer:</label>
    <div class="col-sm-10">

       <select class="form-control" name="search_customer">
                        <option value="" selected>Select Customer</option>

                          <?php foreach($customer_list as $customer): ?>
                          
                              <option value="<?php echo $customer->id; ?>" <?php echo (isset($search_array['search_customer']) && $search_array['search_customer'] == $customer->id) ?  "selected" : " "; ?>><?php echo $customer->first_name ." " .$customer->last_name; ?> </option>
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

  <a href="/customer/<?php echo $this->uri->segment(2); ?>/reset" class="btn btn-danger">Reset</a>
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
                           
                            <th  class="no-sort">Info <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th  class="no-sort">Summary <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                        
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

                             
                               echo '<td><a href="'.base_url().'customer/user_edit/'.$row->id.'".><i class="glyphicon glyphicon-user"></i></a></td>';
                               echo '<td><a href="'.base_url().'customer/user_summary/'.$row->id.'/all".><i class="glyphicon glyphicon-list-alt"></i></a></td>';
                               

                              }
                            
                           
                           
                            ?>  
                        </tbody>
                      </table>
                   
 
                   
                     

                      <?php else: ?>
                        <div class="alert alert-danger">No Data Found.</div>
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




  







  