<?php

class userlogin extends CI_Controller {

 function __construct() 
 {
	parent::__construct();
      
		// for flash data
		$this->load->library('session');
	
		if (!$this->fuel->config('admin_enabled')) show_404();

		$this->load->vars(array(
			'js' => '', 
			'css' => css($this->fuel->config('xtra_css')), // use CSS function here because of the asset library path changes below
			'js_controller_params' => array(), 
			'keyboard_shortcuts' => $this->fuel->config('keyboard_shortcuts')));

		// change assets path to admin
		$this->asset->assets_path = $this->fuel->config('fuel_assets_path');

		// set asset output settings
		$this->asset->assets_output = $this->fuel->config('fuel_assets_output');
		
		$this->lang->load('fuel');
		$this->load->helper('ajax');
		$this->load->library('form_builder');

		$this->load->module_model(FUEL_FOLDER, 'fuel_users_model');

		// set configuration paths for assets in case they are differernt from front end
		$this->asset->assets_module ='fuel';
		$this->asset->assets_folders = array(
				'images' => 'images/',
				'css' => 'css/',
				'js' => 'js/',
			);
 }


 function validate_login(){
 		$this->load->model('user_model');
 		$user_name = $this->input->post('user_name');


 
		$password = $this->__encrip_password($this->input->post('password'));

		if (empty($user_name) || empty($password)){
		  
		  echo "username_password_emtpy";
		
		}
		else{
		
				$is_valid = $this->user_model->validate($user_name, $password);
				
				$responseOnject = array();
		
				if(is_array($is_valid)){
					echo "ok";	
				}
				else{
					echo "wrong";	
				}
		
		}
				exit();

		
 }
 
 function validate_forgetPass(){
 	$this->load->model('user_model');
 	$user_name = $this->input->post('user_name');
 
 	
 	if (empty($user_name)){
 
 		echo "username_emtpy";
 
 	}
 	else{
 
 		$is_valid = $this->user_model->validate_forgetPass($user_name);
 		
 		$responseOnject = array();
 
 		if(is_array($is_valid)){
 			echo "ok";
 		}
 		else{
 			echo "wrong";
 		}
 
 	}
 	exit();
 }

   public function __encrip_password($password) {
   

        return md5($password);
    }
    
    /**
     * send_resetLink()
     */
    public function send_resetLink(){
    
    	//$data =  $this->data;
    	$this->Users_model = $this->load->model('user_model');
    
    	$user_name = $this->input->post('user_name');
    
    	$timeStamp = date('Y-m-d H:i:s:u');
    		
    	$tmp_password = $timeStamp.$user_name;
    	$encrypt_code = md5($tmp_password);
    		
    		
    	$update_resetCode = $this->Users_model->update_resetCode($user_name, $encrypt_code);
    		
    	if($update_resetCode){
    		$reset_link = PHASE2_URL."user/reset_password/".$encrypt_code;
    		$first_name = $this->Users_model->getUserFirstName($user_name);
    			
    		//$data['from'] = "";
    		$data['email'] = $user_name;
    
    		$message = "<div>Hi ".ucfirst($first_name[0]->first_name). ",</div><br /><div>Please click on the link below to reset your password:<br /><br /><a href='".$reset_link."'>RESET YOUR PASSWORD</a></div>";
    		$subject = "SalesSupport360 | Reset Password Link";
    		$sendResetPassLink = $this->Users_model->send_mail($data,$message,$subject);
    		if($sendResetPassLink){
    			echo "success";
    		}else{
    			echo "failed";
    		}
    	}else{
    		echo "failed";
    	}
    	exit();
    }

   
    
 public function index()
 {      
	$session_key = $this->fuel->auth->get_session_namespace();

	$user_data = $this->session->userdata($session_key);


	if ( ! empty($_POST) AND empty($_GET))
	
		{
			//$this->load->library('session');
			// check if they are locked out out or not
			if (isset($user_data['failed_login_timer']) AND (time() - $user_data['failed_login_timer']) < (int)$this->fuel->config('seconds_to_unlock'))
			{
 				$this->fuel_users_model->add_error(lang('error_max_attempts', $this->fuel->config('seconds_to_unlock')));
				$user_data['failed_login_timer'] = time();
			}
			else
			{
				if ($this->input->post('user_name') AND $this->input->post('password'))
				{
					
					if ($this->fuel->auth->login($this->input->post('user_name', TRUE), $this->input->post('password', TRUE)))
					{
						// reset failed login attempts
						$user_data['failed_login_timer'] = 0;
						// set the cookie for viewing the live site with added FUEL capabilities
						$config = array(
							'name' => $this->fuel->auth->get_fuel_trigger_cookie_name(), 
							'value' => serialize(array('id' => $this->fuel->auth->user_data('id'), 'language' => $this->fuel->auth->user_data('language'))),
							'expire' => 0,
							//'path' => WEB_PATH
							'path' => $this->fuel->config('fuel_cookie_path')
						);

						set_cookie($config);

						$forward = $this->input->post('forward');
						$forward_uri = uri_safe_decode($forward);

						if ($forward AND $forward_uri != $this->fuel->config('login_redirect'))
						{
							redirect($forward_uri);
						}
						else
						{
							//checking payment status
							$this->db->select('payment');
							$this->db->from('fuel_users');
							$this->db->where(array('user_name' => $this->input->post('user_name')));
							$sql = $this->db->get();
							$t=$sql->result();
							if ($t[0]->payment == "1") {
							 	redirect('user/profile' ,'refresh');
							} else {
								redirect('selectplan' ,'refresh');
							}
						}
					}
					else
					{
						$this->session->set_flashdata('error_message', 'Invalid Username or Password');
						$error_message =$this->session->flashdata('error_message');
					
						// After that you need to used redirect function instead of load view such as 
						redirect("/",'refresh');
					}
				}
				else
				{
					$this->fuel_users_model->add_error(lang('error_empty_user_pwd'));
				}
			}

			$this->session->set_userdata($session_key, $user_data);
		}
	

	if (empty($_POST) AND !empty($_GET))
	
		{
			//$this->load->library('session');
			// check if they are locked out out or not
			if (isset($user_data['failed_login_timer']) AND (time() - $user_data['failed_login_timer']) < (int)$this->fuel->config('seconds_to_unlock'))
			{
 				$this->fuel_users_model->add_error(lang('error_max_attempts', $this->fuel->config('seconds_to_unlock')));
				$user_data['failed_login_timer'] = time();
			}
			else
			{
				if ($this->input->get('user_name') AND $this->input->get('password'))
				{
					
					if ($this->fuel->auth->login($this->input->get('user_name', TRUE), $this->input->get('password', TRUE)))
					{
						// reset failed login attempts
						$user_data['failed_login_timer'] = 0;
						// set the cookie for viewing the live site with added FUEL capabilities
						$config = array(
							'name' => $this->fuel->auth->get_fuel_trigger_cookie_name(), 
							'value' => serialize(array('id' => $this->fuel->auth->user_data('id'), 'language' => $this->fuel->auth->user_data('language'))),
							'expire' => 0,
							//'path' => WEB_PATH
							'path' => $this->fuel->config('fuel_cookie_path')
						);

						set_cookie($config);

						$forward = $this->input->get('forward');
						$forward_uri = uri_safe_decode($forward);

						if ($forward AND $forward_uri != $this->fuel->config('login_redirect'))
						{
							redirect($forward_uri);
						}
						else
						{
							//checking payment status
							$this->db->select('payment');
							$this->db->from('fuel_users');
							$this->db->where(array('user_name' => $this->input->get('user_name')));
							$sql = $this->db->get();
							$t=$sql->result();
							if ($t[0]->payment == "1") {
							 	redirect('user/profile' ,'refresh');
							} else {
								redirect('selectplan' ,'refresh');
							}
						}
					}
					else
					{
						$this->session->set_flashdata('error_message', 'Invalid Username or Password');
						$error_message =$this->session->flashdata('error_message');
					
						// After that you need to used redirect function instead of load view such as 
						redirect("/",'refresh');
					}
				}
				else
				{
					$this->fuel_users_model->add_error(lang('error_empty_user_pwd'));
				}
			}

			$this->session->set_userdata($session_key, $user_data);
		}

	}
}
?>












