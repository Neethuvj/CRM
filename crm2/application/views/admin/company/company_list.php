
<article class="rs-content-wrapper customer-list" >
    <div class="rs-content" >
      <div class="rs-inner"> 
        <!-- Begin default content width -->
        <div class="container customer-container">


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
               


 <form class="form-horizontal margin-bottom-10" action="/admin/company/<?php echo $this->uri->segment(3); ?>" method="post">
      <div class="filter-panel ss-active clearfix">

<div class="row">

   <div class="form-group ">
    <label for="pwd" class="col-sm-1">Company:</label>
     <div class="col-sm-3 ss-select">
    <select name="search_company" class="form-control" >
    <option value="" selected>Select Company</option>
    <?php foreach($company_list as $company):?>

      <?php if($search_company==$company->company_name){
        $selected_text = "selected";}
        else
        {
          $selected_text = ""; 
        }
      ?>

        <option value="<?php echo $company->company_name;?>" <?php echo $selected_text;?>><?php echo $company->company_name;?></option>
        <?php endforeach;?>


    </select>
    </div>
  </div>
    <div class="col-md-4 ss-no-pad">
  
  <button type="submit" name="submit" value="Submit" class="btn btn-default">Search</button>

  <a href="/admin/company/<?php echo $this->uri->segment(3); ?>" class="btn btn-danger">Reset</a>
  </div>
  </div>

  </div>

</form> 


 


                       <div class="table-responsive">
                      <?php
                       echo form_open('');
                       ?>
                       <?php if(!empty($company_list)): ?>
                      <table class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            
                            <th class="no-sort">Company Name<!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            
                             <th  class="no-sort">Customer Name <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                           
                            <th  class="no-sort">Email Address <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                           
                            <th  class="no-sort">Info <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th  class="no-sort">Team Members <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                        
                          </tr>
                        </thead>
                        <tbody>
                          <?php

                         

                            foreach($company_list as $key=>$company)
                            {
                              
                               echo '<tr>';
                                echo '<td>'.$company->company_name.'</td>';

                                echo '<td>'.$company->first_name." ".$company->last_name.'</td>';

                              

                                echo '<td>'.$company->username.'</td>';

                             
                                echo '<td><a href="'.base_url().'admin/company/user_edit/'.$company->user_id.'".><i class="glyphicon glyphicon-user"></i></a></td>';

                               echo '<td><a href="'.base_url().'admin/company/team_members/'.$company->user_id.'/active".><i class="glyphicon glyphicon-list-alt"></i></a></td>';
                               
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




  







  
