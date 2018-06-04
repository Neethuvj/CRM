

<article class="rs-content-wrapper login-history" >
    <div class="rs-content" >
      <div class="rs-inner"> 
        <!-- Begin default content width -->
        <div class="container-fluid container-fluid-custom"> 
          
          <!-- Begin Panel -->
          <div class="panel panel-plain panel-rounded">
            <div class="p-t-xs">
             
             <div class="support-team">

               

                    <div class="table-responsive">
                  
                      <?php if(!empty($login_history)): ?>
                      <table class="table table-bordered table-striped">
                      <thead>
                          <tr>

                       
                            <th  class="no-sort">User Name</th> 
                            <th  class="no-sort">Login</th>
                            <th  class="no-sort">Logout</th>
                            <th  class="no-sort">IP</th>

                    

                          </tr>

                        </thead>
                     
                        <tbody>

                        <?php foreach($login_history as $history): ?>
                        
                          <tr>
                            <td><?php echo $history->first_name; ?> </td>
                            <td>
                            <?php 
                               echo date('m/d/Y h:i:s a', strtotime($history->login_time)); ?>
                            </td>
                            <td><?php 

                              if($history->logout_time !== NULL){
                               echo date('m/d/Y h:i:s a', strtotime($history->logout_time)); 
                              }
                              else{
                                echo "Currently Logged in";

                              }
                              ?>
                              </td>
                              <td><?php echo $history->ip; ?> </td>

                          </tr>
                        <?php endforeach; ?>
                        </tbody>
                        </table>
                      <?php endif; ?>
              

                    <?php echo '<div class="pagination">'.$this->pagination->create_links().'</div>'; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </article>
  <div id="push"></div>