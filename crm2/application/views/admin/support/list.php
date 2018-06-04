
<article class="rs-content-wrapper support-team-list" >
    <div class="rs-content" >
      <div class="rs-inner"> 
        <!-- Begin default content width -->
        <div class="container-fluid container-fluid-custom">


         <?php if( $this->session->flashdata('warning_message')): ?>
 <div class="alert alert-danger">
          <?php
  echo $this->session->flashdata('warning_message');

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
          
          <!-- Begin Panel -->
          <div class="panel panel-plain panel-rounded">
            <div class="p-t-xs">
                   <div class="support-team"> 
                   <?php if((int) (int) $role_id !== 8): ?>
                      <?php  echo form_open('admin/support/delete_support_team'); ?>
                      <?php echo '<input name="support_role_id" type="hidden" value='.$support_role_id.'> ' ; ?>
                    <?php endif; ?>
              <ul class="list-group task-list" style="list-style-type: none;">
              
              <table class="table table-bordered table-striped">
           

                        <thead>
                          <tr>
                            <th  class="no-sort">First Name<!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th  class="no-sort">Last Name<!-- <i class="fa fa-fw fa-sort"></i> --></th>

                            <th class="no-sort">Email Address <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th  class="no-sort">Phone <!-- <i class="fa fa-fw fa-sort"></i> --></th>
                            <th class="no-sort" >Password <!-- <i class="fa fa-fw fa-sort"></i> --></th>

                               <?php if((int) $role_id !==8): ?>
                           
                            <th class="no-sort">Select</th>
                            <?php endif; ?>
                       


                          </tr>
                        </thead>
                        <tbody>
                       

                          <?php
                            foreach($user_list as $user)
                            {
                              
                              echo '<tr>';
                             echo '<td>'.$user->first_name.'</td>';
                              echo '<td>'.$user->last_name.'</td>';
                           
                            echo '<td><a href="/user/switch_user/'.$user->id.'">'.$user->username.
                              '</a></td>';
                              echo '<td>'.$user->phone_number.'</td>';
                             

                              echo '<td>'.'<a href="/admin/support/bda_reset_password_by_admin/' . $user->id .'">Reset Password
                              </a></td>';
                            
                               if((int) $role_id !== 8){
 echo '<td class="crud-actions">
                                
                                <input name= "delete[]" type="checkbox" value = '.$user->id.'>  
                                
                              </td>';

                               }
                             
                             
                              echo '</tr>';
                            }
                            ?> 
                            
                        </tbody>
                      </table>
                        
                        <a href="#" class="btn btn-primary BDA-Create-popup"><?php echo $popup_title;?></a>

                             <?php if((int) $role_id !==8): ?>
                         <input class="btn btn-danger delete" type="submit" name="delete_bda" value="Delete">
                       <?php endif; ?>
                      

                      <?php echo '<div class="pagination">'.$this->pagination->create_links().'</div>'; ?>
              </ul>
               <?php if((int) $role_id !==8): ?>
                  <?php echo form_close(); ?>
                <?php endif; ?>
            </div>
            </div>
          </div>

              <a class="btn btn-primary" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Back</a>
        </div>


      </div>
    </div>
  </article>
  <div id="push"></div>








  <div id="add-time-page" class="modal fade BDA-Create-popup" tabindex="-1"  data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" dismiss="modal" aria-hidden="true">×</button>
        
        <h4 class="modal-title"><?php echo $popup_title;?></h4>

      </div>
      <div class="modal-body">

        <?php echo form_open('admin/support/create_support_team');?>


           <div class="form-group">
                  <label>First Name *</label>
                  <input type="text" name="first_name" class="form-control first_name" value="">
                  <span class="error-msg"></span>
         
                </div>
            


      
        <input type="hidden" name="support_role_id" class="form-control id" value=<?php echo $support_role_id; ?>>
     


      <input type="hidden" name="validate_bda_url" class="form-control validate_bda_url" value="/admin/support/bda_validate">
     
          
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

                


         
       
      <div class="modal-footer ">

        <input class="btn btn-default save" type="submit" value="Save">
        <a href="/admin/support/<?php echo $this->uri->segment(3); ?>" class="btn btn-default cancel-popup">CANCEL</a>
         <?php echo form_close(); ?>
      </div>
      </div>
    </div>
  </div>
</div>


     <?php if((int) $role_id !==8): ?>

<!--delete message for support team-->

 <div id="add-time-page" class="modal fade delete-confirmation" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        
        <h4 class="modal-title"><?php echo $delete_popup_title;?></h4>

      </div>
      <div class="modal-body">

          Are you sure you want to delete?

            </div>



      <div class="modal-footer ">

        <input class="btn btn-danger delete" type="submit" value="YES">


        <a href="/admin/support/<?php echo $this->uri->segment(3); ?>" class="btn btn-default cancel-popup">CANCEL</a>
      </div>
  
    </div>
  </div>
</div>
<?php endif; ?>
