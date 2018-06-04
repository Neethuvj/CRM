<article class="rs-content-wrapper task-list-admin">
    <div class="rs-content">
      <div class="rs-inner"> 
        <!-- Begin default content width -->
        <div class="container-fluid"> 
          <div class="row">
                  <div class="col-md-12">
              



          <!-- Begin Panel -->
      <div class="panel panel-plain panel-rounded">
          <div class="p-t-xs">
              <div class="support-team"> 
              <div class="clearfix"></div>
                <!-- Tab panes -->
                <div class="tab-content">

<div class="filter-panel ss-active">

<form class="form-horizontal  margin-bottom-10" action="/admin/task/<?php echo $type_of_task; ?>" method="post">
 <div class="row">
 <div class="col-md-6">
  <div class="form-group ">
    <label for="pwd"  class="col-sm-2" class="col-sm-2">Customers:</label>
     <div class="col-sm-9 ss-select">
    <select value="search_customer" name="search_customer" class="form-control" id="search_customer">
    <option value=""  selected>Select Customer</option>
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


<div class="col-md-6">
  <div class="form-group">
    <label for="pwd" class="col-sm-2">BDA:</label>
     <div class="col-sm-9 ss-select">
    <select value="search_bda" name="search_bda" class="form-control" id="search_bda">
    <option value=""  selected>Select BDA</option>
  <?php foreach($get_all_bdas as $get_all_bda):?>


     <?php if($search_bda==$get_all_bda->id){
        $selected_text = "selected";}
        else
        {
          $selected_text = ""; 
        }
      ?>

        <option value="<?php echo $get_all_bda->id;?>"  <?php echo $selected_text;?>><?php echo $get_all_bda->first_name;?></option>
        <?php endforeach;?>
    
    </select>
    </div>
  </div>



 
  </div>
  </div>


<div class="row">
<div class=" col-md-6 ">
   <div class="form-group has-feedback datepicker-block">
        <label class="control-label col-sm-2">Start Date:</label>
             <div class="col-sm-9 pad-left0 ss-select">   
             <div class="col-sm-5"> 
      <input type="text"  name="search_start_date" class="form-control rs-datepicker" placeholder="MM-DD-YYYY"  value="<?php echo $search_start_date; ?>">
      </div>
      <div class="col-sm-5">
          <input type="text"  name="search_to_date" class="form-control rs-datepicker" placeholder="MM-DD-YYYY"  value="<?php echo $search_to_date; ?>">
          </div>
      
      </div>

  </div>
  </div>


  
<div class="col-md-6">
<div class="form-group text-right">
  
 
  <button type="submit" name="submit" value="Submit" class="btn btn-default">Search</button>

  <a href="/admin/task/summary/reset" class="btn btn-danger">Reset</a>

  </div>
 
  </div>
</div>

</div>
</form> 






                  <div role="tabpanel" class="tab-pane active" id="home">
                    <div class="table-responsive">
                      <?php
                       echo form_open('task/delete');
                       ?>
                         <?php if(!empty($get_summary)): ?>
                      <table class="table table-bordered table-striped">
                        <thead>
                          <tr>
                        
                           
                             <th  class="no-sort">Client Name <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                              <th  class="no-sort">Current Plan <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                               <th  class="no-sort">Start Date  </th>
                              <th  class="no-sort">Status <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                              <th  class="no-sort">Total No.of Reports <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                        <th  class="no-sort">Total No.of Connections <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                         <th  class="no-sort">Total Hours <!-- <i class="fa fa-fw fa-sort"></i> --></th>
       
                          </tr>
                        </thead>
                        <tbody>
                          <?php


                            foreach($get_summary as $row)
                            {
                              
                               
                              echo '<tr>';

                          
                                echo '<td>'.$row->first_name."  ".$row->last_name .'</td>';
                                echo '<td>'.$row->plan_name.'</td>';


                                echo '<td>'.date("m/d/Y",strtotime($row->transaction_date)).'</td>';

                               

                                echo '<td>'.$row->status_name.'</td>';
                               
                                   echo '<td>'.$total_hours[$row->id]['total_number_of_reports'].'</td>';
                                       echo '<td>'.$total_hours[$row->id]['total_number_of_connections'].'</td>';
                                 echo '<td>'.$calculated_hours[$row->id]['logged_hrs'].' hour(s) '.$calculated_hours[$row->id]['logged_min'].' minute(s)</td>';

          

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
                  


            

  <div id="push"></div>

                 

        </div>
          </div>

                 
            </div>
              </div>


    </div>
      </div>
        </div>
          </div>
            </div>

              <div id="push"></div>













