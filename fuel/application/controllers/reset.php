
<?php

class reset extends CI_Controller {

 function __construct() 
 {
	parent::__construct();

		//$this->load->library('form_validation');

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
          
          $this->load->library('email', $config);

		
	
 }
// THIS IS A PASSWORD RESET TOKEN CREATION EMAIL SENDING 
	public function p_reset()
	{
		$session_key = $this->fuel->auth->get_session_namespace();

		$user_data = $this->session->userdata($session_key);
		
		if ( ! $this->fuel->config('allow_forgotten_password')) show_404();

		$this->js_controller_params['method'] = 'add_edit';
       
		if ( ! empty($_POST))
		{
			
			if ($this->input->post('email'))
			{
				$user = $this->fuel_users_model->find_one_array(array('email' => $this->input->post('email')));
				
				
				if ( ! empty($user['email']))
				{
					// This generates and saves a token to the user model, returns the token string.
					$token = $this->fuel_users_model->get_reset_password_token($user['email']);

					if ($token !== FALSE)
					{
						
						$url = '/reset';
						//exit;
						$msg = lang('p_reset_email', fuel_url($url));
						$params['to'] = $this->input->post('email');
						$params['subject'] = lang('p_reset_subject');
						$params['message'] = $msg;
						$params['use_dev_mode'] = FALSE;
						if ($this->fuel->notification->send($params))
						{
							$this->session->set_flashdata('success', lang('p_reset_email_success'));
							$this->fuel->logs->write(lang('auth_log_pass_reset_request', $user['email'], $this->input->ip_address()), 'debug');
							
							$this->session->set_flashdata('success_message','Email sent successfully'); 
								
						}
					
						redirect('/reset');
						
					} 
				}
				else {
							$this->session->set_flashdata('error_message', ' Email is not available');
						// After that you need to used redirect function instead of load view such as 
							redirect("/reset");
						}
				
			}
			
			
		
		} else {

			
			
				$this->session->set_flashdata('error_message', ' Email is wrong');
						// After that you need to used redirect function instead of load view such as 
							redirect("/reset");
			
			}
}

 

}
?>
