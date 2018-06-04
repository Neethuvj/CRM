<article class="rs-content-wrapper configuration-index" >
    <div class="rs-content" >
      <div class="rs-inner"> 
        <!-- Begin default content width -->
        <div class="container-fluid container-fluid-custom"> 

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

               

                    <div class="table-responsive">
                  
                      
                      <table class="table table-bordered table-striped">
                      <thead>
                          <tr>

                       
                            <th  class="no-sort">Type</th> 
                            <th  class="no-sort">Email Address</th>
                            <th  class="no-sort">Edit</th>

                    

                          </tr>

                        </thead>
                     
                        <tbody>
                            
                          <?php
                            if ($admin_emails)
                              foreach($admin_emails as $admin_email) :
                            ?>
                           <tr>
                              <td class="email_id"  style="display:none;"><?php echo $admin_email->id;?></td>
                              <td><?php echo $admin_email->email_type;?></td>
                              <td class="email_value"><?php echo $admin_email->email;?></td>
                              <td><a href="#" class="btn btn-primary configuration-edit-popup">Edit</a></td>

                            </tr>
                             <?php endforeach;?>
                              
                        </tbody>
                      </table>
                   </div>

              <ul class="list-group task-list" style="list-style-type: none;">
              
              </ul>
              
            </div>
          </div>
           <a class="btn btn-primary" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Back</a>
        </div>
      </div>

    </div>
  </article>
  <div id="push"></div>





  <div id="add-time-page" class="modal fade edit-configuration-table" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Edit Configuration Email</h4>
      </div>
      <div class="modal-body">

        <?php echo form_open('admin/configuration/index');?>
         <div class="form-group">
                  <label>Email Address</label>
                  <input type="text" name="email" class="form-control popup-email" value="">
                     <span class="error-msg"></span>

                  <input type="hidden" name="id" class="form-control popup-id" value="">


                   <input type="hidden" name="validate_email_url" class="form-control validate_email_url" value="/admin/configuration/admin_email_validate">
                </div>
      </div>
      <div class="modal-footer">

        <input class="btn btn-default" type="submit" value="Save">
    
         <a href="/admin/configuration/index" class="btn btn-danger">CLOSE</a>
        <?php echo form_close(); ?> 
      </div>
    </div>
  </div>
</div>