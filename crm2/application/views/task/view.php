 <article class="rs-content-wrapper task-panel">
    <div class="rs-content">
      <div class="rs-inner"> 
        <!-- Begin default content width -->
        <div class="container-fluid container-fluid-custom"> 
          
          <!-- Begin Panel -->
          <div class="panel panel-plain panel-rounded task-panel">
            <div class="p-t-xs">
              <form>
                <div class="row">
                  <div class="col-md-12 text-center">
                    <ul class="user-details clearfix">
                      <li>
                        <label>Task ID : </label>

                        <span><?php echo $task->id; ?></span></li>

                

                      <li>


                        <label>Task Type : </label>
                        <span><?php echo $task->name; ?></span></li>
                      <li>
                        <label>Created Date : </label>

                        <span><?php echo date("m/d/Y h:i A", strtotime($task->created)); ?></span></li>

                            <li>
                        <label>Created By : </label>

                        <span><?php echo $task_created_user[0]->first_name." ".$task_created_user[0]->last_name; ?></span></li>

                    </ul>
                  </div>
                </div>
                <!-- /.row -->
                <div class="row">
                  <div class="col-md-12">
                
            
                  <table id="" class="user-task table table-bordered table-striped" >
                    <tbody>


                      <tr>
                        <td class="main" >Task Status: </td>
                        <td class="sub" ><?php echo $status; ?></td>
                      </tr>
                      
  <?php if(!empty($task->task_name)): ?>
                      <tr>
                        <td class="main" >Task Type: </td>
                        <td class="sub" ><?php echo $task->task_name; ?></td>
                      </tr>
                       <?php endif; ?>

                      <tr>
                        <td class="main" >Account Name: </td>
                        <td class="sub" ><?php echo $task->account_name; ?></td>
                      </tr>


                      <tr>
                        <td class="main" >Target Name:</td>
                        <td class="sub" ><?php echo $task->target_name; ?></td>
                      </tr>

                       <?php if(!empty($task->email)): ?>
                      <tr>
                        <td class="main" >Email Address: </td>
                        <td class="sub" ><?php echo $task->email; ?></td>
                      </tr>
                   
                    <?php endif; ?>
                      <?php if(!empty($task->present_company)): ?>
                      <tr>
                        <td class="main" >Present Company :</td>
                        <td class="sub" ><?php echo $task->present_company; ?></td>
                      </tr>
                   
                    <?php endif; ?>
                    <?php if(!empty($task->previous_company)): ?>
                       <tr>
                        <td class="main" >Previous company :</td>
                        <td class="sub" ><?php echo $task->previous_company; ?></td>
                      </tr>
                    <?php endif; ?>
                      <?php if(!empty($task->home_address)): ?>
                      <tr>
                        <td class="main" >Home Address: Street, City and State (for neighbor's list) :</td>
                        <td class="sub" ><?php echo $task->home_address; ?></td>
                      </tr>
                       <?php endif; ?>
                        <?php if(!empty($task->meeting_date_time)): ?>
                      <tr>
                        <td class="main" >Date and Time of Meeting :</td>
                        <td class="sub" ><?php echo date("m/d/Y h:i A", strtotime($task->meeting_date_time)); ?></td>
                      </tr>
                       <?php endif; ?>
                  
                        <?php if(!empty($task->comments_additional_info)): ?>
                      <tr>
                        <td class="main" >Comments and Additional Information (For all other task requests please write down your instructions here). :</td>
                        <td class="sub" ><?php echo $task->comments_additional_info; ?></td>
                      </tr>
                       <?php endif; ?>
                        <?php if($task_status == 5):  ?>
                       <?php if(!empty($links)):  ?>
                      <tr>
                        <td class="main">Links:</td>
                         <td class="sub" >
                         <?php foreach ($links as $key => $value ):?>
                      
                       <?php echo '<a data-upload-id = '.$value->id.'  href="'.$value->url. '">'.$value->url.'</a><br>'; ?>
                      
                    <?php endforeach; ?>
                    </td>
                    </tr>
                    <?php endif; ?>
                     <?php endif; ?>
                         <?php if(!empty($userattach)): ?>
                      <tr>
                        <td class="main" >User Attachments:</td>
                        <td class="sub" >
                               <?php foreach ($userattach as $key => $value) {
      
        echo '<a data-upload-id = '.$value->id.' href="'.base_url().'assets/files/'.$value->path.'" class="link">'.$value->path.'</a> <br>';
       
      }?>



                        </td>
                      </tr>
                       <?php endif; ?>

                       <?php if($task_status == 5): ?>
                      <?php if(!empty($analystattach)): ?>
                      <tr>
                        <td class="main" >Attachments:</td>
                        <td class="sub" >
                               <?php foreach ($analystattach as $key => $value) {
      
        echo '<a data-upload-id = '.$value->id.' href="'.base_url().'assets/files/'.$value->path.'" class="link">'.$value->path.'</a> <br>';
       
      }?>



                        </td>
                      </tr>
                      
                       <?php endif; ?>
                       <?php endif; ?>

                          <?php if($task_status == 5):  ?>
                      <?php if(!empty($task->analyst_note)): ?>
                        <tr>
                        <td class="main" >Notes:</td>
                        <td class="sub" ><?php echo $task->analyst_note; ?></td>
                      </tr>
                       <?php endif; ?>
                       <?php endif; ?>
                      
                      
                    </tbody>
                  </table>
                <!--   <table id="" class="user-task table table-bordered table-striped" >
                    <tbody>
                      <tr>
                        <td class="main" >Time Spent in Minutes :</td>
                        <td class="sub" >N/A</td>
                      </tr>
                      <tr>
                        <td class="main" >Number of Connections :</td>
                        <td class="sub" >120</td>
                      </tr>
                      <tr>
                        <td class="main" >Google Link 1 :</td>
                        <td class="sub" ><a href="">link</a></td>
                      </tr>
                      <tr>
                        <td class="main" >Google Link 2 :</td>
                        <td class="sub" ><a href="">link</a></td>
                      </tr>
                      <tr>
                        <td class="main" >Google Link 3 :</td>
                        <td class="sub" ><a href="">link</a></td>
                      </tr>
                      <tr>
                        <td class="main" >Related Link 1 :</td>
                        <td class="sub" ><a href="">link</a></td>
                      </tr>
                      <tr>
                        <td class="main" >Related Link 2 :</td>
                        <td class="sub" ><a href="">link</a></td>
                      </tr>
                      <tr>
                        <td class="main" >Date Completed :</td>
                        <td class="sub" >10</td>
                      </tr>
                      
                      
                      
                    </tbody>
                  </table>
                  </div>
                </div>
                <!-- /.row -->
                
          <!--       <div class="row">
                  <div class="col-md-6">
                    <div class="form-submit">
                      <input class="btn btn-default" type="submit" value="Cancel">
                    </div>
                  </div>
                </div> --> 
              </form>
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </article>
  <div id="push"></div>