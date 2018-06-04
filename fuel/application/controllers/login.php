<?php

class Login extends CI_Controller {

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


 public function index()
 {


	 $session_key = $this->fuel->auth->get_session_namespace();

		$user_data = $this->session->userdata($session_key);




	if ( ! empty($_POST))
	
		{
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
							
							redirect('user/profile');
						}
					}
					else
					{
						$this->session->set_flashdata('error_message', 'Invalid Username or Password');
						// After that you need to used redirect function instead of load view such as 
						redirect("/");
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
