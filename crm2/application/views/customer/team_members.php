<article class="rs-content-wrapper member-list">
<div class="rs-content">
      <div class="rs-inner"> 
        <!-- Begin default content width -->
        <div class="container-fluid"> 

       <!-- Begin Panel -->
          <div class="panel panel-plain panel-rounded">
            <div class="p-t-xs">
             
<ul class="nav nav-tabs margin-bottom-10">
 <li ><a href="/customer/user_summary/<?php echo $this->uri->segment(3); ?>/all">Summary</a></li>
  <li ><a href="/customer/user_summary/<?php echo $this->uri->segment(3); ?>/in_progress">In Progress</a></li>
  <li><a href="/customer/user_summary/<?php echo $this->uri->segment(3); ?>/completed">Completed</a></li>
    <li class="active"><a href="/customer/team_members/<?php echo $this->uri->segment(3); ?>/active">Team  Members</a></li>
  
</ul>


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

 <form class="form-horizontal margin-bottom-10" action="/customer/team_members/<?php echo $this->uri->segment(3); ?>/<?php echo $this->uri->segment(4); ?>" method="post">
      <div class="filter-panel clearfix">

      <div class="row">

    <div class="col-md-5">

   <div class="form-group has-feedback datepicker-block col-md-12">
    
        <label class="control-label col-sm-2" for="pwd">Created</label>
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
    <div class="col-md-4">
  
  <button type="submit" name="submit" value="Submit" class="btn btn-default">Search</button>
  <a href="/customer/team_members/<?php echo $this->uri->segment(3); ?>/<?php echo $this->uri->segment(4);?>" class="btn btn-danger">Reset</a>
  </div>
  </div>

  </div>
</form> 


                  <div role="tabpanel" class="tab-pane active" id="home">
                    <div class="table-responsive">
                   
                       
                       

                    <?php if(!empty($team_members)): ?>
                      <table class="table table-bordered table-striped ss-team-mebers">
                        <thead>
                          <tr>
                           

                            <th class="no-sort">Customer Name</th>
                        
                            <th class="no-sort">Email Address</th>
                    

                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            foreach($team_members as $key=>$team_member)
                            {
                              
                              echo '<tr>';
                              echo '<td>'.$team_member->first_name." ".$team_member->last_name.'</td>';
                              echo '<td>'.$team_member->username.'</td>';
                              echo '</tr>';
                             
                            }
                            ?>  


                        </tbody>
                      </table>
                    


                    
                      <?php else: ?>
                        <div class="alert alert-danger">No Data Found.</div>
                    <?php endif; ?>
                 
                    
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

