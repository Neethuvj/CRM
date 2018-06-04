<?php $this->load->view('_blocks/profile_header');
$pagetitle= fuel_var('page_title');

$session_key = $this->fuel->auth->get_session_namespace();
$user_data = $this->session->userdata($session_key);
$this->db->select('*')
        ->from('fuel_users')
        ->where('id',$user_data['id']);
 $result=$this->db->get();
 $first = $result->row()->first_name;     
 $last = $result->row()->last_name;  
 $username = $result->row()->user_name;
 $email = $result->row()->email;
 $website = $result->row()->Website;
 $message = $result->row()->Message;
 $id = $result->row()->id;
?>
<div class="container">
  <div class="update_profile">
    <h3 style="color:#3f5e84;">Update Profile</h3>
        
    <form validate id="signup" action="<?php echo site_url('profile/index');?>"  method="post" name="signup">
                     <div class=" col-sm-12 form-left">
         <input type="hidden" value="<?php echo $id ;?>" name="id" class="form-control">                
                        <div class="control-group form-group">
                            <div class="controls">
                                <label style="color:black;">First Name: </label><span> *</span>
                                <input type="text" value="<?php echo $first ;?>" data-validation-required-message="Please enter your name." required="" name="name" class="form-control"> <p class="help-block"></p>
                            </div>
                        </div>
     
                        <div class="control-group form-group">
                            <div class="controls">
                       <label style="color:black;">Last Name: </label><span> *</span>
                                <input type="text" value="<?php echo $last; ?>" data-validation-required-message="Please enter your name." required="" name="lastname" class="form-control"> <p class="help-block"></p>
                            </div>  </div> 
     <div class="control-group form-group">
                            <div class="controls">
                                <label style="color:black;">Username:</label>
    <span> *</span>
                                <input type="text" value="<?php echo $username; ?>" data-validation-required-message="Please enter your username." required=""  name="username" class="form-control">
                     <p class="help-block"></p>
                            </div>    </div>   
    <div class="control-group form-group">
                           <!--  <div class="controls">       
                                <label style="color:black;">Password: </label><span> *</span>
     <input type="password" data-validation-required-password="Please enter your password." required="" name="password" class="form-control">
                          <p class="help-block"></p>
                            </div> -->    </div>         
     <div class="control-group form-group">
                            <div class="controls">
                                <label style="color:black;">Email:</label>
                                <input type="email" value="<?php echo $email; ?>" data-validation-required-message="Please enter your email."  name="email" class="form-control">
                     <p class="help-block"></p>
                            </div>    </div>      
                        
                            
                        <div class="control-group form-group">
                            <div class="controls">
                                <label style="color:black;">Website:</label>
                                <input type="text" value="<?php echo $website; ?>" data-validation-required-message="Please enter your websitedetail."  name="website" class="form-control">
                     <p class="help-block"></p>
                            </div>    </div>      
                              
                        <div class="control-group form-group">
                            <div class="controls">  <label style="color:black;">Message:</label>
                                <textarea style="resize:none" value="<?php echo $message; ?>" maxlength="999" data-validation-required-message="Please enter your message" name="message" class="form-control" cols="100" rows="8"></textarea>
                             <p class="help-block"></p>
                            </div>    </div>      
                        <button class="btn btn-primary send-message" type="submit">Update</button>
                        </div>
      </form>
  </div>
</div>
<?php $this->load->view('_blocks/footer')?>
