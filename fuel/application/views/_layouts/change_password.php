<?php $this->load->view('_blocks/profile_header');
$pagetitle= fuel_var('page_title');
if (!empty($_POST)) {
    $id = $_POST['id'];
    $password = $_POST['password'];
    $data = array('password' => MD5($password));
    $this->db->where('id', $id);
    $this->db->update('fuel_users', $data);
    $this->session->set_flashdata('success_message','Password Changed Successfully.'); 
    redirect('user/profile');
}
$session_key = $this->fuel->auth->get_session_namespace();
$user_data = $this->session->userdata($session_key);
?>
<script type="text/javascript" src="<?php echo site_url('assets/js/common.js');?>"></script>
<div class="container">
  <div class="change_password">
        <h3 style="color:#3f5e84;">Change Password</h3>
        <form class="password-reset-form" validate id="signup" action="<?php echo site_url('user/change-password');?>"  method="post" name="signup">
            <div class=" col-sm-12 form-left">
                <input type="hidden" value="<?php echo $user_data['id']; ?>" name="id" class="form-control">
            </div>
            <div class="control-group form-group">
                <div class="controls">
                    <label style="color:black;">Password: </label><span> *</span>
                    <input id="password" type="" data-validation-required-password="Please enter your password." required="" name="password" class="form-control">
                    <p class="help-block"></p>
                    <label style="color:black;">Confirm Password: </label><span> *</span>
                    <input id="password_confirm" type="" data-validation-required-password="Please enter your password." required="" name="password_confirm" class="form-control">
                    <p class="help-block"></p>
                </div>   
            </div>
            <div class="password-reset">
                <div>
                    <span class="message"></span>
                </div>    
                    <button id="submit" class="btn btn-primary send-message" type="submit">Update</button>
            </div>    
        </form>
    </div>
</div>        
<?php 
$this->load->view('_blocks/footer')?>
