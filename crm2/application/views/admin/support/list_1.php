

<article class="rs-content-wrapper" >
    <div class="rs-content" >
      <div class="rs-inner"> 
        <!-- Begin default content width -->
        <div class="container-fluid container-fluid-custom"> 
          
          <!-- Begin Panel -->
          <div class="panel panel-plain panel-rounded">
            <div class="p-t-xs">
                   <div class="support-team"> 
              <ul class="list-group task-list" style="list-style-type: none;">

              <?php  if($role_id===$role_id)  ?>
              
              <table class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>First Name<!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th>Last Name<!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th>Email Address <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th>Phone <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th>Reset Password <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                           
                            <th>Select</th>
                       


                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            foreach($user_list as $user)
                            {
                              
                              echo '<tr>';
                             echo '<td>'.'<a href="/task/view/' . $user->id .'">'.$user->first_name.
                              '</a></td>';
                              echo '<td>'.'<a href="/task/view/' . $user->id .'">'.$user->last_name.
                              '</a></td>';
                           

                              echo '<td>'.$user->username.'</td>';
                              echo '<td>'.$user->phone_number.'</td>';
                              echo '<td> asdf </td>';
                            
                             
                              echo '<td class="crud-actions">
                                <input name= "delete[]" type="checkbox" value = '.$user->id.'>  
                                
                              </td>';
                             
                              echo '</tr>';
                            }
                            ?>  
                        </tbody>
                      </table>
                      <?php if($role_id===$role_id)?>
                        <a href="#" class="btn btn-primary BDA-Create-popup"><?php echo $popup_title;?></a>

                       

                      <?php echo '<div class="pagination">'.$this->pagination->create_links().'</div>'; ?>
              </ul>
              
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </article>
  <div id="push"></div>








  <div id="add-time-page" class="modal fade BDA-Create-popup" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
 
        <h4 class="modal-title"><?php echo $popup_title;?></h4>
  
        </div>
      </div>
      <div class="modal-body">

        <?php echo form_open('admin/configuration/create_bda');?>


           <div class="form-group">
                  <label>First Name *</label>
                  <input type="text" name="first_name" class="form-control first_name" value="">
                  <span class="error-msg"></span>
         
                </div>
            


      
        <input type="hidden" name="id" class="form-control id" value="">
     


      <input type="hidden" name="validate_bda_url" class="form-control validate_bda_url" value="/admin/configuration/bda_validate">
     
          
                  <div class="form-group">
                  <label>Last Name *</label>
                  <input type="text" name="last_name" class="form-control last_name" value="">
                  <span class="error-msg"></span>
                </div>
            
          

    
                  <div class="form-group">
                  <label>Email *</label>
                  <input type="text" name="email" class="form-control email" value="">
                  <span class="error-msg"></span>
                </div>


                 <div class="form-group">
                  <label>Phone Number *</label>
                  <input type="text" name="phone_number" class="form-control phone_number" value="">
                  <span class="error-msg"></span>
                </div>



                  <div class="form-group">
                  <label>Password *</label>
                  <input type="password" name="password" class="form-control password" value="">
                  <span class="error-msg"></span>
                </div>


                 <div class="form-group">
                  <label>Confirm Password *</label>
                  <input type="password" name="confirm_password" class="form-control confirm_password" value="">
                  <span class="error-msg"></span>
                </div>

                


          

         
       
      <div class="modal-footer">

        <input class="btn btn-default" type="submit"  value="Save">
        <input class="btn btn-default cancel" type="submit" value="Cancel" name="cancel">

         <?php echo form_close(); ?>
      </div>
      </div>
    </div>
  </div>
