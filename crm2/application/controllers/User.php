<?php

/**
 * 
 * @package Controllers
 * @subpackage General
 */

class User extends CI_Controller {

  private $data = array();
  public function __construct(){
        parent::__construct();
    $this->load->model('Users_model');
     $this->load->model('Task_model');
    if($this->session->userdata('switch_from')){
          $data['switch_from']=$this->session->userdata('switch_from');
      }




    $data['error_class'] = 'form-group has-feedback has-error';
      $data['error_less_class'] = "form-group";


        if($this->session->userdata('sidebar_status') == "collapsed"){

          $data['sidebar_class'] =  "minified"; 
        }   
        else{
         $data['sidebar_class'] =  " ";    
        }

$data['role_id'] =  $this->session->userdata('role_id');
        $this->data = $data; 

      }



    /**
    * Check if the user is logged in, if he's not, 
    * send him to the login page
    * @return void
    */	


	public function index()
	{
        
			$data =  $this->data;
		
		if($this->session->userdata('is_logged_in')){

        if((int) $this->session->userdata('role_id') == 2){
              redirect('task/inprogress', $data);

        }
         if((int) $this->session->userdata('role_id') == 9){
              redirect('task/inprogress', $data);

        }
        else{
          redirect('user/dashboard', $data);
        }
			   // redirect('task/inprogress');
        }else{
        	// $this->load->view('header');
        	// $this->load->view('login/login');	
        	// $this->load->view('footer');

        	   redirect(PHASE1_URL, "refresh");
        }
	}	

    /**
    * encript the password 
    * @return mixed
    */	


   public function __encrip_password($password) {
   

        return md5($password);
    }

    /**
    * check the username and the password with the database runs while login from the front end
    * @return void
    */
	public function validate_credentials()
	{	


$data =  $this->data;
		$this->load->model('Users_model');

		$user_name = $this->input->post('user_name');
		$password = $this->__encrip_password($this->input->post('password'));

		$is_valid = $this->Users_model->validate($user_name, $password);

		if($is_valid)
		{	

   if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                  $ip_address_array = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
                  $ip_address = $ip_address_array[0];
               } else {
                 $ip_address = $_SERVER['REMOTE_ADDR'];
               }

          $team_owner = FALSE;

      if( $is_valid[0]->plan_id == 3){

          if($is_valid[0]->notification_hour == NULL){

            $team_owner = TRUE;
          }
          else{

            $team_owner = FALSE;
          }

      }

			$data = array(
				'user_id' => $is_valid[0]->id,
				'user_name' => $user_name,
				'role_id' => $is_valid[0]->role_id,
				'company' => ($is_valid[0]->company == 1 || $team_owner == TRUE ) ? TRUE : NULL,
				'is_logged_in' => true,
				'name' => $is_valid[0]->first_name,
				'first_letter' =>substr($is_valid[0]->first_name,0,1)
			);


			$login_log = array(
				'user_id' =>$is_valid[0]->id,
				'ip' => $ip_address,
				'login_time' => date("Y-m-d H:i:s"),
			);



			$this->Users_model->login_log($login_log);
			$this->session->set_userdata($data);

			if((int) $is_valid[0]->role_id == 1 || (int) $is_valid[0]->role_id == 8 ){


			

				redirect('/admin/configuration/index', $data);
				
			}
		
			else{
        // if($is_valid[0]->role_id == 2){
        //       redirect('task/inprogress', $data);

        // }
        // else{
        //   redirect('task/inprogress', $data);
        // }

        if((int) $is_valid[0]->role_id == 2){
              redirect('task/inprogress', $data);

        }
         if((int) $is_valid[0]->role_id == 9){
              redirect('task/inprogress', $data);

        }
        else{
          redirect('user/dashboard', $data);
        }
				
		
				
			}
			
		}
		else // incorrect username or password

		{
			
		redirect(PHASE1_URL, "refresh");
		}
	}	

	
	
		
	
   /**
    * Register form url includes post action too
    * On Successfull submission we are storing the following details and logging the user in
    * user_id, user_name,role_id,
    * company :  TRUE or False based on if the user signed up as company or chooses team plan,
    * is_logged_in, name, first_letter of the first_name
    * 
    */
   public function register(){
    
    $data = array();
    $data['selected_plan_id'] = $this->input->post('plan_id_selected') ? $this->input->post('plan_id_selected') :  $this->input->get('plan_id');

    	$this->load->model('Users_model');

    	
    $plans = $this->Users_model->get_all_plans();
      $cards = $this->Users_model->get_all_cards();
      $card_array = array();
      foreach($cards as $card_id => $card_obj){

      	$card_array[$card_obj->name] = $card_obj->name;
      }
    $data['plans'] = $plans;


    $data['cards'] = $card_array;
    $data['error_class'] = 'form-group has-feedback has-error';
    $data['error_less_class'] = "form-group";

   $data['company_checkbox'] = $this->input->post('company_checkbox') ? "checked" : "";
 
     $data['company_name'] = $this->input->post('company_name') ? $this->input->post('company_name') : "";
	$data['first_name'] = $this->input->post('first_name') ? $this->input->post('first_name') : "";
	$data['last_name'] = $this->input->post('last_name') ? $this->input->post('last_name') : ""; 
	$data['email'] = $this->input->post('email') ? $this->input->post('email') : ""; 
	$data['phone_number'] = $this->input->post('phone_number') ? $this->input->post('phone_number') : ""; 
	$data['address'] = $this->input->post('address') ? $this->input->post('address') : ""; 
	$data['city'] = $this->input->post('city') ? $this->input->post('city') : ""; 
	$data['state'] = $this->input->post('state') ? $this->input->post('state') : ""; 
	$data['zip'] = $this->input->post('zip') ? $this->input->post('zip') : ""; 
   $data['on_board_date'] = $this->input->post('onboard-date') ? date("m/d/Y", strtotime($this->input->post('onboard-date'))) : ""; 
     $data['on_board_time'] = $this->input->post('onboard-time') ? $this->input->post('onboard-time') : ""; 
   	$this->load->view('register_header', $data);
     /*Setting up validations*/
	 $this->load->library('form_validation');
     $this->form_validation->set_rules('first_name', 'First Name', 'required');
     $this->form_validation->set_rules('last_name', 'Last Name', 'required');
  	 $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[ss_users.username]', array('is_unique' => 'This email id is already in use'));

  	 $this->form_validation->set_rules('company_name','Company', 'callback_company_name_check');
	 $this->form_validation->set_rules('phone_number', 'Phone Number', 'required',
	 	    array(
                'required'      => 'The %s is required.'
        ));
	$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
	$this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'trim|required|matches[password]');
	$this->form_validation->set_rules('address', 'Address', 'required');

	$this->form_validation->set_rules('city', 'City', 'required');
	$this->form_validation->set_rules('state', 'State', 'required');
	$this->form_validation->set_rules('zip', 'Zip Code', 'required');
	$this->form_validation->set_rules('card_type', 'Card Type','required');
	$this->form_validation->set_rules('card_number', 'Card Number','required|callback_card_number_check', array('required' => 'Please enter card number.'));
	$this->form_validation->set_rules('card_holder_name', 'Card Holder Name','required');
		$this->form_validation->set_rules('onboard-date', 'On Board Date','callback_on_board_date_check');
	$this->form_validation->set_rules('expiry-month', 'Expiry Month','required|callback_card_expiry_check');
	$this->form_validation->set_rules('expiry-year', 'Expiry Year','required|callback_card_expiry_check');


	$this->form_validation->set_rules('billing_street_address', 'Billing Address','required');

	$this->form_validation->set_rules('billing_city', 'Billing City','required');
	$this->form_validation->set_rules('billing_state', 'Billing State','required');
	$this->form_validation->set_rules('billing_zip', 'Billing Zip Code ','required');
    /*Credit Card Validation*/
     if($this->input->post()){
 		 if ($this->form_validation->run() == FALSE)
                {
                        $this->load->view('user/register');
                }
                else
                {
                	
                	
                	$submitted_values = $this->input->post();

                	$user = $this->Users_model->register_user($submitted_values);

               
                	if(is_array($user) && isset($user['user_id'])){


          $team_owner = FALSE;

      if( $user['plan_id'] == 3){

          if($is_valid['notification_hour'] == NULL){

            $team_owner = TRUE;
          }
          else{

            $team_owner = FALSE;
          }

      }

					$data = array(
					'user_id' => $user['user_id'],
					'user_name' =>  $this->input->post('email'),
					'role_id' =>  $user['role_id'],
					'company' =>  ($submitted_values['company_checkbox'] ||$team_owner == TRUE ) ? TRUE : NULL,
					'is_logged_in' => true,
					'name' => $this->input->post('first_name'),
					'first_letter' =>substr($this->input->post('first_name'),0,1)

					);
					$this->session->set_userdata($data);

					  $this->session->set_flashdata('success_message',"Thanks for subscribing to SalesSupport360, an email has been sent with further credentials.");
					redirect('task/inprogress', $data);

                	}
                	else{
                	

                        $this->session->set_flashdata('success_message',$user);
                        $this->load->view('user/register',  $data);


                	}
                	
                       // $this->load->view('formsuccess');
                }

     }
		
        else{
	    $this->load->view('user/register',$data);
		}
		$this->load->view('register_footer', $data);
	}

  /**
   * Team plan register action
   * Does the following
   *
   *  Creates and entry in the database and lists under admin => team => pending
   * So the admin can set and amount and hours
   * 
   */
	public function team_register(){
     $data = array();
    $data['selected_plan_id'] = $this->input->post('plan_id_selected') ? $this->input->post('plan_id_selected') :  $this->input->get('plan_id');

    	$this->load->model('Users_model');

    	
    $plans = $this->Users_model->get_all_plans();
      $cards = $this->Users_model->get_all_cards();
      $card_array = array();
      foreach($cards as $card_id => $card_obj){

      	$card_array[$card_obj->name] = $card_obj->name;
      }
    $data['plans'] = $plans;


    $data['cards'] = $card_array;
    $data['error_class'] = 'form-group has-feedback has-error';
    $data['error_less_class'] = "form-group";

   $data['company_checkbox'] = $this->input->post('company_checkbox') ? "checked" : "";
     $data['company_name'] = $this->input->post('company_name') ? $this->input->post('company_name') : "";
	$data['first_name'] = $this->input->post('first_name') ? $this->input->post('first_name') : "";
	$data['last_name'] = $this->input->post('last_name') ? $this->input->post('last_name') : ""; 
	$data['email'] = $this->input->post('email') ? $this->input->post('email') : ""; 
	$data['phone_number'] = $this->input->post('phone_number') ? $this->input->post('phone_number') : ""; 
	$data['address'] = $this->input->post('address') ? $this->input->post('address') : ""; 
	$data['city'] = $this->input->post('city') ? $this->input->post('city') : ""; 
	$data['state'] = $this->input->post('state') ? $this->input->post('state') : ""; 
	$data['zip'] = $this->input->post('zip') ? $this->input->post('zip') : ""; 
   $data['on_board_date'] = $this->input->post('onboard-date') ? date("m/d/Y", strtotime($this->input->post('onboard-date'))) : ""; 
     $data['on_board_time'] = $this->input->post('onboard-time') ? $this->input->post('onboard-time') : ""; 
   	$this->load->view('register_header', $data);
     /*Setting up validations*/
	 $this->load->library('form_validation');
     $this->form_validation->set_rules('first_name', 'First Name', 'required');
     $this->form_validation->set_rules('last_name', 'Last Name', 'required');
  	 $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[ss_users.username]', array('is_unique' => 'This email id is already in use.'));

      $this->form_validation->set_rules('company_name', 'Company', 'callback_company_name_check');


	 $this->form_validation->set_rules('phone_number', 'Phone Number', 'required',
	 	    array(
                'required'      => 'The %s is required.'
        ));
	$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
	$this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'trim|required|matches[password]');
	$this->form_validation->set_rules('address', 'Address', 'required');

	$this->form_validation->set_rules('city', 'City', 'required');
	$this->form_validation->set_rules('state', 'State', 'required');
	$this->form_validation->set_rules('zip', 'Zip Code', 'required');
	
		$this->form_validation->set_rules('onboard-date', 'On Board Date','callback_on_board_date_check');
	

     if($this->input->post()){
 		 if ($this->form_validation->run() == FALSE)
                {
                        $this->load->view('user/team_register');
                }
                else
                {
                	
                	
                	$submitted_values = $this->input->post();

                	$user = $this->Users_model->register_team_plan_user($submitted_values);

               
                	if(is_array($user) && isset($user['user_id'])){


					  //$this->session->set_flashdata('success_message',"Thanks for subscribing to Salessupport, our team will get in touch with you.");

					     redirect('/user/team_register_success','refresh');
				

                	}
                	else{
                	

  $this->session->set_flashdata('success_message',$user);


                        $this->load->view('user/team_register');

           //redirect("/"); 

                	}
                	
                       // $this->load->view('formsuccess');
                }

     }
		
        else{
	    $this->load->view('user/team_register',$data);
		}
		$this->load->view('register_footer', $data);
	}


  /**
   * Form page which appears after the user clicked the activation link which is sent to him
   * Works on Deactivated => Activated User, Team Plan User
   */
 public function activate_user($token){
    
    $data = array();
 

    	$this->load->model('Users_model');



    $user = $this->Users_model->get_user_details_from_token($token);
       $data['selected_plan_id'] =$this->input->post('plan_id_selected') ? $this->input->post('plan_id_selected') :  $user[0]->plan_id;
       $data['temp_token'] = $token;
    	if(!empty($user)){
      $plans = $this->Users_model->get_all_plans();
      $cards = $this->Users_model->get_all_cards();
      $card_array = array();
      foreach($cards as $card_id => $card_obj){

      	$card_array[$card_obj->name] = $card_obj->name;
      }
    $data['plans'] = $plans;


    $data['cards'] = $card_array;
    $data['user_id'] = $user[0]->id;
    $data['old_plan_id'] = $user[0]->plan_id;
    $data['error_class'] = 'form-group has-feedback has-error';
    $data['error_less_class'] = "form-group";
	$data['first_name'] = $this->input->post('first_name') ? $this->input->post('first_name') : $user[0]->first_name;
	$data['last_name'] = $this->input->post('last_name') ? $this->input->post('last_name') : $user[0]->last_name; 
	$data['email'] = $this->input->post('email') ? $this->input->post('email') : $user[0]->username; 
	$data['phone_number'] = $this->input->post('phone_number') ? $this->input->post('phone_number') : $user[0]->phone_number; 
	$data['address'] = $this->input->post('address') ? $this->input->post('address') : $user[0]->address; 
	$data['city'] = $this->input->post('city') ? $this->input->post('city') : $user[0]->city; 
	$data['state'] = $this->input->post('state') ? $this->input->post('state') : $user[0]->state; 
	$data['zip'] = $this->input->post('zip') ? $this->input->post('zip') : $user[0]->zip_code; 
   $data['on_board_date'] = $this->input->post('onboard-date') ? $this->input->post('onboard-date') : date("m/d/Y", strtotime($user[0]->on_board_date)); 
 $data['on_board_time'] = ""; 
   	$this->load->view('register_header', $data);
     /*Setting up validations*/
	 $this->load->library('form_validation');
     $this->form_validation->set_rules('first_name', 'First Name', 'required');
     $this->form_validation->set_rules('last_name', 'Last Name', 'required');
  	 $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
	 $this->form_validation->set_rules('phone_number', 'Phone Number', 'required',
	 	    array(
                'required'      => 'The %s is required.'
        ));
	$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
	$this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'trim|required|matches[password]');
	$this->form_validation->set_rules('address', 'Address', 'required');

	$this->form_validation->set_rules('city', 'City', 'required');
	$this->form_validation->set_rules('state', 'State', 'required');
	$this->form_validation->set_rules('zip', 'Zip Code', 'required');


  
       

	$this->form_validation->set_rules('card_type', 'Card Type','required');
	$this->form_validation->set_rules('card_number', 'Card Number','required|callback_card_number_check', array('required' => 'Please enter card number.'));
	$this->form_validation->set_rules('card_holder_name', 'Card Holder Name','required');
		
	$this->form_validation->set_rules('expiry-month', 'Expiry Month','required|callback_card_expiry_check');
	$this->form_validation->set_rules('expiry-year', 'Expiry Year','required|callback_card_expiry_check');

  $this->form_validation->set_rules('billing_street_address', 'Billing Address','required');

  $this->form_validation->set_rules('billing_city', 'Billing City','required');
  $this->form_validation->set_rules('billing_state', 'Billing State','required');
  $this->form_validation->set_rules('billing_zip', 'Billing Zip Code ','required');


$this->form_validation->set_rules('onboard-date', 'On Board Date','callback_on_board_date_check');

	
    /*Credit Card Validation*/
  
     if($this->input->post()){
 		 if ($this->form_validation->run() == FALSE)
                {
                        $this->load->view('user/activate_user');
                }
                else
                {
                	
                	
                	$submitted_values = $this->input->post();
                  $submitted_values['plan_id_selected'] = $data['selected_plan_id'];
                  $submitted_values['company_checkbox'] = $user[0]->company == 1 ? TRUE : NULL;
                	$user = $this->Users_model->register_user($submitted_values);
                
                  

                	if(is_array($user) && isset($user['user_id'])){


       $team_owner = FALSE;

      if( $user['plan_id'] == 3){

          if($is_valid['notification_hour'] == NULL){

            $team_owner = TRUE;
          }
          else{

            $team_owner = FALSE;
          }

      }

    
					$data = array(
					'user_id' => $user['user_id'],
					'user_name' =>  $this->input->post('email'),
					'role_id' =>  $user['role_id'],
          'company' =>  ($submitted_values['company_checkbox'] || $team_owner == TRUE ) ? TRUE : NULL,
					'is_logged_in' => true,
					'name' => $this->input->post('first_name'),
					'first_letter' =>substr($this->input->post('first_name'),0,1)

					);



         
					$this->session->set_userdata($data);

					  $this->session->set_flashdata('success_message',"Thanks for subscribing to SalesSupport360, an email has been sent with further details.");

  
					redirect('task/inprogress', $data);
       
                	}
                	else{
                	

                        $this->session->set_flashdata('success_message',$user);


                        $this->load->view('user/activate_user');

                	}
                	
                      
                }

     }
		
        else{
	    $this->load->view('user/activate_user',$data);
		}
		$this->load->view('register_footer', $data);
	}
	else{
		redirect(PHASE1_URL, 'refresh');

	}
	}

  /**
   * Function to runs while an old user tries to activate his account in the new system by clicking on the link sent to him.
   *
   */
  public function initiate_user($token){
    $data = array();
    $this->load->model('Users_model');
    $user = $this->Users_model->get_user_details_from_token($token); 
    if(isset($user)){
        // print "<pre>";
        // print_r($user);
        // exit();

 $data['company_checkbox'] = $this->input->post('company_checkbox') ? "checked" : "";
 
      $data['company_name'] = $this->input->post('company_name') ? $this->input->post('company_name') : "";
        $data['selected_plan_id'] =$this->input->post('plan_id_selected') ? $this->input->post('plan_id_selected') :  $user[0]->plan_id;
        $data['temp_token'] = $token;
        $plans = $this->Users_model->get_all_plans();
        $cards = $this->Users_model->get_all_cards();
        $card_array = array();
        foreach($cards as $card_id => $card_obj){
          $card_array[$card_obj->name] = $card_obj->name;
        }
        $data['plans'] = $plans;
        $data['cards'] = $card_array;
        $data['user_id'] = $user[0]->id;
        $data['old_plan_id'] = $user[0]->plan_id;
        $data['error_class'] = 'form-group has-feedback has-error';
        $data['error_less_class'] = "form-group";
        $data['first_name'] = $this->input->post('first_name') ? $this->input->post('first_name') : $user[0]->first_name;
        $data['last_name'] = $this->input->post('last_name') ? $this->input->post('last_name') : $user[0]->last_name; 
        $data['email'] = $this->input->post('email') ? $this->input->post('email') : $user[0]->username; 
        $data['phone_number'] = $this->input->post('phone_number') ? $this->input->post('phone_number') : $user[0]->phone_number; 
        $data['address'] = $this->input->post('address') ? $this->input->post('address') : $user[0]->address; 
        $data['city'] = $this->input->post('city') ? $this->input->post('city') : $user[0]->city; 
        $data['state'] = $this->input->post('state') ? $this->input->post('state') : $user[0]->state; 
        $data['zip'] = $this->input->post('zip') ? $this->input->post('zip') : $user[0]->zip_code; 
        $data['on_board_date'] = $this->input->post('onboard-date') ? $this->input->post('onboard-date') : date("m/d/Y", strtotime($user[0]->on_board_date)); 
        $data['on_board_time'] = ""; 
        $this->load->view('register_header', $data);
        /*Setting up validations*/
        $this->load->library('form_validation');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'required',
        array(
        'required'      => 'The %s is required.'
        ));
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
        $this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'trim|required|matches[password]');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('state', 'State', 'required');
        $this->form_validation->set_rules('zip', 'Zip Code', 'required');
        $this->form_validation->set_rules('card_type', 'Card Type','required');
        $this->form_validation->set_rules('card_number', 'Card Number','required|callback_card_number_check', array('required' => 'Please enter card number.'));
        $this->form_validation->set_rules('card_holder_name', 'Card Holder Name','required');
        $this->form_validation->set_rules('expiry-month', 'Expiry Month','required|callback_card_expiry_check');
        $this->form_validation->set_rules('expiry-year', 'Expiry Year','required|callback_card_expiry_check');
        $this->form_validation->set_rules('billing_street_address', 'Billing Address','required');
        $this->form_validation->set_rules('billing_city', 'Billing City','required');
        $this->form_validation->set_rules('billing_state', 'Billing State','required');
        $this->form_validation->set_rules('billing_zip', 'Billing Zip Code ','required');
        // $this->form_validation->set_rules('onboard-date', 'On Board Date','callback_on_board_date_check');

        if($this->input->post()){

                if ($this->form_validation->run() == FALSE)
                {
                        $this->load->view('user/activate_user');
                }
                else
                {  
                  $submitted_values = $this->input->post();
                  $submitted_values['plan_id_selected'] = $data['selected_plan_id'];
                  $submitted_values['company_checkbox'] = $user[0]->company == 1 ? TRUE : NULL;
                  $submitted_values['user_id'] = $user[0]->id;
                  $user = $this->Users_model->initiate_old_user_to_system($submitted_values);
                  if(is_array($user) && isset($user['user_id']))  {
                    $team_owner = FALSE;
                    if( $user['plan_id'] == 3){
                      if($is_valid['notification_hour'] == NULL){
                        $team_owner = TRUE;
                      }
                      else{
                        $team_owner = FALSE;
                      }
                    }
                    $data = array(
                    'user_id' => $user['user_id'],
                    'user_name' =>  $this->input->post('email'),
                    'role_id' =>  $user['role_id'],
                    'company' =>  ($submitted_values['company_checkbox'] || $team_owner == TRUE ) ? TRUE : NULL,
                    'is_logged_in' => true,
                    'name' => $this->input->post('first_name'),
                    'first_letter' =>substr($this->input->post('first_name'),0,1)
                    );         
                    $this->session->set_userdata($data);
                    $this->session->set_flashdata('success_message',"Thanks for subscribing to SalesSupport360, an email has been sent with further details.");
                    redirect('task/inprogress', $data);
       
                  }
                  else{
                        $this->session->set_flashdata('success_message',$user);
                        $this->load->view('user/initiate_user');
                  }
                  
                }
        }
        else{
           $this->load->view('user/initiate_user',$data);
        }
        $this->load->view('register_footer', $data);

    }
  }
    
/**
 *  User/Admin Take over as other users
 */
public function switch_user(){
	
	$this->load->model('Users_model');

		 $user_id=$this->uri->segment(3);

			$user_data=$this->Users_model->get_switch_user($user_id);


     $team_owner = FALSE;


      if($user_data[0]->plan_id == 3){


          if($user_data[0]->notification_hour == NULL){

            $team_owner = TRUE;
          }
          else{

            $team_owner = FALSE;
          }

      }
			$data = array(
					'user_id' =>$user_id ,
					'role_id' =>$user_data[0]->role_id,
					'username'=>$user_data[0]->username,
					'is_logged_in' => true,
					'name' =>$user_data[0]->first_name,
					'first_letter' =>substr($user_data[0]->first_name,0,1),
					'switch_from' => $this->session->userdata('user_id'),
					'company' => ($user_data[0]->company  == 1 || $team_owner  == TRUE) ? TRUE : NULL
					);

			$this->session->set_userdata($data);
			
			if($user_data[0]->role_id == 8){
				redirect('admin/configuration/index');
			}
			else{
			redirect('/');		
		 }
}
/**
 * Action to switch the user back to his original account
 */
public function switch_as_admin(){

	
	$this->load->model('Users_model');
	$admin_id =$this->session->userdata('switch_from') ? $this->session->userdata('switch_from') : "" ;

	if(!empty($admin_id))
	{

     $team_owner = FALSE;

  $user_data=$this->Users_model->get_switch_user($admin_id);
      if( $user_data[0]->plan_id == 3){

          if($user_data[0]->notification_hour == NULL){

            $team_owner = TRUE;
          }
          else{

            $team_owner = FALSE;
          }

      }

	
				$data = array(
					'user_id' =>$admin_id ,
					'role_id' =>$user_data[0]->role_id,
					'username'=>$user_data[0]->username,
					'is_logged_in' => true,
					'name' => $user_data[0]->first_name,
					'first_letter' =>substr($user_data[0]->first_name,0,1),
					'switch_from' =>"",
					'company' => ($user_data[0]->company  == 1 || $team_owner == TRUE) ? TRUE : NULL
					);
					$this->session->set_userdata($data);
				
		redirect('/admin/configuration/index');
	}


}
 
  /**
   * Need to check we are not using this anywhere 
   */
  public function signup(){
       

        $x_email = $_POST['x_email'];
        $sql = "SELECT email FROM fuel_users WHERE email = '".$_POST['x_email']."'";
        $query = $this->db->query($sql);
        $email = $query->result();
      
        $this->load->model('Users_model');
        $data = $_POST;

        $password = $this->Users_model->randomPassword();
        if(empty($email)){
            if ($_POST['x_response_code'] == "1") {
                $register_status = $this->Users_model->register_user($data, $password);
                if($register_status){
               	   //$this->Users_model->send_mail($password, $x_email); 
  

                	 $data = array(
						'user_name' => $x_email,
						'is_logged_in' => true
					 );
					$this->session->set_userdata($data);
					redirect('task/inprogress', $data);
                }
            }
            else{
                $this->session->set_flashdata('success_message','There is some problem with the payment, Please come back later.');
                $this->form_validation->set_message('card_number_check', $errortext);
           redirect("/"); 
            }
        } 
        else 
        {
           $this->session->set_flashdata('success_message','Duplicate Entry ... Please Contact Admin For Your Registration.');
           redirect("/");
        } 
    }
    	
 
  /**
   * Log out functionaty adds an entry in to ss_user_login table
   */
	public function logout()
	{

		$this->load->model("Users_model");

			$logout_log = array(
				'user_id' => $this->session->userdata('user_id'),
				'logout_time' => date("Y-m-d H:i:s"),
			);


			$this->Users_model->logout_log($logout_log);
		$this->session->sess_destroy();


		redirect(PHASE1_URL, "refresh");
	}

  /**
   * Dashboard for each user, 
   */
	public function dashboard()
	{
		$data =  $this->data;

		
		$data['role'] =  $this->session->userdata('role_id');
		$data['company'] = ($this->session->userdata('company') !== NULL) ? TRUE : NULL;
			 if($this->session->userdata('sidebar_status') == "collapsed"){

          $data['sidebar_class'] =  "minified"; 
        }   
        else{
         $data['sidebar_class'] =  " ";    
        }	
		if($data['role'] == 2 || $data['role'] == 9){

			$this->dashboard_for_customer();
		}
		elseif($data['role'] == 4){
         $this->dashboard_for_analyst();
			
		}

		elseif($data['role'] == 3){

        redirect('task/inprogress');
			
			
		}
		elseif($data['role'] == 1){
         redirect('admin/customer/index');
			
		}

else{

	//$this->dashboard_for_bda();

	print "Functionality is not yet implemented. <a href='/user/logout'>Logout</a>";
}

	

		// if($data['role'] == 1){

		// 	redirect('/admin/configuration/index', $data);
		// }

	}
  /**
   * Analyst Dashboard function which gets called from the dashboard function if the user is analyst
   */
	private function dashboard_for_analyst(){

		
		$data =  $this->data;
		$this->load->model('Task_model');
		
			$data['name'] = $this->session->userdata('name');
		$data['first_letter'] = $this->session->userdata('first_letter');
			$data['role_id'] = $this->session->userdata('role_id');


			$search_by_date = array(
				'from' => $this->input->post('search_from_date'),
				'to' =>  $this->input->post('search_to_date')
				);

			$meeting_date_1_array  = array();
			
		$data['search_to_date'] =  $this->input->post('search_to_date');

		$data['search_from_date'] =  $this->input->post('search_from_date');

		
			
     	    $analyst_id = $this->session->userdata('user_id');

     		$data['analayst_tasks']=$this->Task_model->get_task_by_analyst($analyst_id,NULL,NULL,NULL,NULL, NULL, NULL);
     


	$today_meeting_date_start=date('Y-m-d 00:00:00', strtotime( $this->input->post('search_from_date')));
	
	$today_meeting_date_end=date('Y-m-d 24:00:00', strtotime( $this->input->post('search_to_date')));

		

     		$meeting_date_array = array(
     			'start' => $today_meeting_date_start, 
     			'end' =>$today_meeting_date_end
     			);

			$data['todays_meeting_tasks']=$this->Task_model->get_task_by_analyst($analyst_id, $meeting_date_array,NULL,NULL,NULL, NULL);

			
			
		$this->load->view('header',$data);
		$this->load->view('sidebar');
        $this->load->view('user/analyst_dashboard',$data);	
        $this->load->view('footer',$data);
        

	}



	


/**
 * Currently this function is not usefull, but since we are redirecting to this function lot of places
 * We are keeping this
 */
private function dashboard_for_customer(){


		$data =  $this->data;
		if($this->session->userdata('is_logged_in')){
 if($this->session->userdata('sidebar_status') == "collapsed"){

          $data['sidebar_class'] =  "minified"; 
        }   
        else{
         $data['sidebar_class'] =  " ";    
        }

        
        $data['company'] = ($this->session->userdata('company') !== NULL) ? TRUE : FALSE;
			$data['name'] = $this->session->userdata('name');
			$data['first_letter'] = $this->session->userdata('first_letter');
			$data['role_id'] =  $this->session->userdata('role_id');
			$this->load->view('header', $data);
			$this->load->view('sidebar');
			$this->load->view('user/dashboard',$data);
			$this->load->view('footer', $data);
			 
		
			
		}
	else{

		$this->load->view('header');
        	$this->load->view('login/login');	
        	$this->load->view('footer');
		}

	}

   /**
    * Analyst Edit profile Section
    */
public function edit_analyst()
{

	$data =  $this->data;
		$this->load->model('Users_model');
		$data['name'] = $this->session->userdata('name');	
		$user_id = $this->session->userdata('user_id');
		$user_details = $this->Users_model->fetchdata($user_id);
		$data['user_details'] = $user_details;
	$data['error_class'] = 'form-group has-feedback has-error';
   		$data['error_less_class'] = "form-group";
		$data['id'] = $user_details[0]->id;
		$data['first_name'] =  $this->input->post('first_name');
		 $data['last_name'] =  $this->input->post('last_name');
		 $data['email'] =  $this->input->post('email');
		$data['password'] = $this->input->post('new_password');

		//$user_details = $this->Users_model->updateanalyst($data);

                	  
                	
		//redirect('user/dashboard');
		

	if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
			//var_dump($this->input->post('assistant_name'));
  	 	$this->form_validation->set_rules('new_password', 'Password', 'trim|required|min_length[5]');
	$this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'trim|required|matches[new_password]');

			if ($this->form_validation->run() == FALSE)
                {
           $this->load->view('header', $data);
		$this->load->view('sidebar');
		$this->load->view('user/edit_analyst',$data);
		$this->load->view('footer', $data);
                }
                 else
                {
         

                	$data = array(
                		'id' => $user_details[0]->id,
                		'password'=>$this->input->post('new_password')
                );


     $data['first_name'] =  $this->input->post('first_name');
     $data['last_name'] =  $this->input->post('last_name');
     $data['email'] =  $this->input->post('email');  
                	
                	

$user_details = $this->Users_model->updateanalyst($data);
                	

                	  $this->session->set_flashdata('success_message',"Password has been updated successfully.");
                	redirect('user/dashboard');
  //                       $this->load->view('header', $data);*/
		// $this->load->view('sidebar');
		// $this->load->view('user/edit',$data);
		// $this->load->view('footer', $data);
                }
		}



}


	

/**
 * BDA Edit Profile 
 */
public function bda_profile()
{

	$data =  $this->data;
		$this->load->model('Users_model');
		$data['name'] = $this->session->userdata('name');
		$data['first_letter'] = $this->session->userdata('first_letter');
		$data['role_id'] = $this->session->userdata('role_id');
			
		$user_id = $this->session->userdata('user_id');
		$user_details = $this->Users_model->fetchdata($user_id);
		$data['user_details'] = $user_details;
		$data['error_class'] = 'form-group has-feedback has-error';
   		$data['error_less_class'] = "form-group";
		$data['id'] = $user_details[0]->id;
		$data['first_name'] = $user_details[0]->first_name;
		$data['last_name'] = $user_details[0]->last_name;
		$data['phone_number'] = $user_details[0]->phone_number;
		$data['email'] = $user_details[0]->username;
        $this->load->view('header', $data);
		$this->load->view('sidebar');
		$this->load->view('user/bda_profile',$data);
		$this->load->view('footer', $data);
              



}



/**
 * Submit Action 
 */
public function edit_bda_profile()
{

	$data =  $this->data;
	$data['error_class'] = 'form-group has-feedback has-error';
   	$data['error_less_class'] = "form-group";
	$data['id'] =  $this->input->post('id');
	$data['first_name'] =  $this->input->post('first_name');
	$data['last_name'] =  $this->input->post('last_name');
	$data['email'] =  $this->input->post('email');
	$data['password'] = $this->input->post('new_password');
	$data['phone_number'] = $this->input->post('phone_number');
	$this->load->model('Users_model');


	if ($this->input->server('REQUEST_METHOD') === 'POST')
		{

	      $this->form_validation->set_rules('first_name','First Name', 'required');
  
     $this->form_validation->set_rules('last_name', 'Last Name', 'required');
	
		 $this->form_validation->set_rules('phone_number', 'Phone Number', 'required',
	 	    array(
                'required'      => 'The %s is required.'
               
        ));
   	 	$this->form_validation->set_rules('new_password', 'Password', 'trim|min_length[5]');
	 $this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'trim|matches[new_password]');

			if ($this->form_validation->run() == FALSE)
                {

                	  $this->load->view('header', $data);
					   $this->load->view('sidebar');
						$this->load->view('user/bda_profile',$data);
						$this->load->view('footer', $data);
					}

					else{

		      $data = array(
               'id' => $this->input->post('id'),
               'password'=>$this->input->post('new_password'),
               'first_name'=>  $this->input->post('first_name'),
               'last_name' =>  $this->input->post('last_name'),
               'email' => $this->input->post('email'),
               'phone_number' => $this->input->post('phone_number'),
               );


			$user_details = $this->Users_model->update_bda($data);
			$this->session->set_flashdata('success_message'," Your profile has been updated successfully.");

			redirect('user/dashboard');


					}


				}

} 


public function edit()
{	


$data =  $this->data;
		//$user_details = $this->Users_model->fetchdata($user_id);
		if($this->session->userdata('is_logged_in')){



		$this->load->model('Users_model');
		$data['name'] = $this->session->userdata('name');	
		$data['first_letter'] = $this->session->userdata('first_letter');
		$data['company'] = ($this->session->userdata('company') !== NULL) ? TRUE : FALSE;
		$user_id = $this->session->userdata('user_id');
		$user_details = $this->Users_model->fetchdata($user_id);
		$data['user_details'] = $user_details;
		//$address = unserialize($user_details[0]->address);
		$data['assistant_name'] = '';
		$data['assistant_email'] = '';
		$data['assistant_phone'] = '';
    $data['email_status'] = '';
		// echo "<pre>";
		// print_r($user_details);
		// echo "</pre>";
		$data['error_class'] = 'form-group has-feedback has-error';
    $data['error_less_class'] = "form-group";
		$data['id'] = $user_details[0]->id;
    $data['role_id'] = $user_details[0]->role_id;

    //$data['customer_checkbox'] = $this->input->post('customer_checkbox') ? "checked" : "";
		$data['first_name'] = $user_details[0]->first_name;
		$data['last_name'] = $user_details[0]->last_name;
		$data['street_name'] = $user_details[0]->address;
		$data['city'] = $user_details[0]->city;
		$data['state'] = $user_details[0]->state;
		$data['phone_number'] = $user_details[0]->phone_number;
		$data['zip_code'] = $user_details[0]->zip_code;
		$data['on_board_date'] = date("m/d/Y", strtotime($user_details[0]->on_board_date));
	  $data['on_board_time'] = date("h:i A", strtotime($user_details[0]->on_board_time));
		$data['notification_hour'] = $user_details[0]->notification_hour;
		$data['monthly_usage'] = $user_details[0]->monthly_usage;
		$data['company_name'] = $user_details[0]->company_name;

		$data['email'] = $user_details[0]->username;
			$data['more_info'] = $user_details[0]->more_info;
		$data['plan_name'] = $this->Users_model->fetch_plan_name($user_details[0]->plan_id);

	if((int) $user_details[0]->role_id !== 9 ){

      if($user_details[0]->plan_id != 3){
		$data['plan_hours'] = $this->Users_model->fetch_actual_plan_hours($user_details[0]->plan_id);
  }
  else{
    $data['plan_hours'] = $this->Users_model->fetch_team_plan_hours($user_details[0]->user_id);

  }
		}
		else{
$data['plan_hours'] = $user_details[0]->monthly_usage;

		}
	
		for ($i = 0; $i < count($user_details); $i++) {
			$array['assistant_id'] []= $user_details[$i]->assistant_id;
			$array['assistant_name'] []= $user_details[$i]->assistant_name;
			$array['assistant_email'] []= $user_details[$i]->assistant_email;
			$array['assistant_phone'] []= $user_details[$i]->assistant_phone;
       $array['email_status'] []= $user_details[$i]->email_status;
    
		}
    // print "<pre>";
    // print_r($array);
    // exit();
		$data['assistant'][] = $array;


		
		//$data['phone_number'] = $user_details[0]->phone_number;
		//var_dump($data['assistant_phone']);
		$data['role'] =  $this->session->userdata('role_id');
		if($data['role'] == 2 || $data['role'] == 9){

     
		$this->load->view('header', $data);
		$this->load->view('sidebar');
		$this->load->view('user/edit',$data);
		$this->load->view('footer', $data);
		}
		else {
			//$this->load->view('index.html');
		$this->load->view('header', $data);
		$this->load->view('sidebar');
		$this->load->view('user/edit_analyst',$data);
		$this->load->view('footer', $data);
		}
	}
	else{
		$this->load->view('header');
        	$this->load->view('login/login');	
        	$this->load->view('footer');
		}

	}

  /**
   *
   *Update Credit Card for Customer
   */
  public  function update_credit_card(){
    $data =  $this->data;

    if($this->session->userdata('is_logged_in') && $data['role_id'] == 2){
        $data['name'] = $this->session->userdata('name'); 
        $data['first_letter'] = $this->session->userdata('first_letter');
        $data['company'] = ($this->session->userdata('company') !== NULL) ? TRUE : FALSE;
        $user_id = $this->session->userdata('user_id');
        $user_details = $this->Users_model->fetchdata($user_id);
        $data['user_details'] = $user_details;
        $cards = $this->Users_model->get_all_cards();
        $card_array = array();
        foreach($cards as $card_id => $card_obj){
          $card_array[$card_obj->name] = $card_obj->name;
        }
        $data['cards'] = $card_array;
      $this->form_validation->set_rules('card_type', 'Card Type','required');
      $this->form_validation->set_rules('card_number', 'Card Number','required|callback_card_number_check', array('required' => 'Please enter card number.'));
      $this->form_validation->set_rules('card_holder_name', 'Card Holder Name','required');
        $this->form_validation->set_rules('onboard-date', 'On Board Date','callback_on_board_date_check');
      $this->form_validation->set_rules('expiry-month', 'Expiry Month','required|callback_card_expiry_check');
      $this->form_validation->set_rules('expiry-year', 'Expiry Year','required|callback_card_expiry_check');


      $this->form_validation->set_rules('billing_street_address', 'Billing Address','required');

      $this->form_validation->set_rules('billing_city', 'Billing City','required');
      $this->form_validation->set_rules('billing_state', 'Billing State','required');
      $this->form_validation->set_rules('billing_zip', 'Billing Zip Code ','required');

        /*Credit Card Validation*/
         if($this->input->post()){
           if ($this->form_validation->run() == TRUE)
                    {

                        $card_details = $this->input->post();

                        //$user_details = $data['user_id'];
                        $update_card_details = $this->Users_model->update_credit_card_details($user_details, $card_details);
                        if(is_numeric($update_card_details)){
                          $this->session->set_flashdata('success_message',"Credit card details has been updated, this will be reflected in your next billing.");         
                        }
                        else{
                              $this->session->set_flashdata('error_message',$update_card_details);   
                        }
                        redirect('user/update_credit_card');
                    }
                   
          }


            $this->load->view('header',$data );
        $this->load->view('sidebar',$data );
        $this->load->view('user/update_credit_card',$data);
        //$this->load->view('admin/customer/add');
          $this->load->view('footer', $data);
    }
    else{

      redirect("/",'refresh');
    }

  }


  /*Update Customer Information*/
	public function edituser()
	{
		$data =  $this->data;
	
    //$user_details = $this->Users_model->fetchdata($user_id);
    if($this->session->userdata('is_logged_in')){
    $this->load->model('Users_model');
    $data['name'] = $this->session->userdata('name'); 
     $data['company'] = ($this->session->userdata('company') !== NULL) ? TRUE : FALSE;
    $data['first_letter'] = $this->session->userdata('first_letter');
		$this->load->model('Users_model');
		$data['name'] = $this->session->userdata('name');	
		$user_id = $this->session->userdata('user_id');
		$user_details = $this->Users_model->fetchdata($user_id);
		$data['user_details'] = $user_details;
		$data['plan_name'] = $this->Users_model->fetch_plan_name($user_details[0]->plan_id);
		if((int) $user_details[0]->role_id !== 9){
		$data['plan_hours'] = $this->Users_model->fetch_actual_plan_hours($user_details[0]->plan_id);
		}
		else{
$data['plan_hours'] = $user_details[0]->monthly_usage;

		}
	
		$data['error_class'] = 'form-group has-feedback has-error';
   		$data['error_less_class'] = "form-group";
		$data['id'] = $user_details[0]->id;
		$data['first_name'] =  $this->input->post('first_name');
		$data['last_name'] =  $this->input->post('last_name');
		$data['street_name'] =$this->input->post('street_name');
    $data['company_name'] =$this->input->post('company_name');
		$data['city'] =  $this->input->post('city');
    $data['company_name'] = $this->input->post('company_name');
		$data['state'] = $this->input->post('state');
		$data['phone_number'] = $this->input->post('phone_number');
		$data['zip_code'] = $this->input->post('zip_code');
		$data['on_board_date'] = date("m/d/Y", strtotime($user_details[0]->on_board_date));
		$data['on_board_time'] = $user_details[0]->on_board_time;
		$data['email'] =  $this->input->post('email');
		$data['more_info'] =  $this->input->post('more_info');
        $data['monthly_usage'] = $user_details[0]->monthly_usage;
    $data['notification_hour'] = $user_details[0]->notification_hour;

 $email_status = $this->input->post('email_status');


		for ($i = 0; $i < count($user_details); $i++) {
			$array['assistant_id'] []= $user_details[$i]->assistant_id;
			$array['assistant_name'] []= $user_details[$i]->assistant_name;
			$array['assistant_email'] []= $user_details[$i]->assistant_email;
			$array['assistant_phone'] []= $user_details[$i]->assistant_phone;
    
		}
  $array['email_status'][] =  $email_status;

		$data['assistant'][] = $array;
 
    // print "<pre>";
    // print_r($this->input->post('email_status'));
    // exit();

		if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
			//var_dump($this->input->post('assistant_name'));
			
			
      $this->load->library('form_validation');
     $this->form_validation->set_rules('first_name', 'First Name', 'required');
     $this->form_validation->set_rules('last_name', 'Last Name', 'required');
     if($this->session->userdata('company') !== NULL){
       $this->form_validation->set_rules('company_name', 'Company Name', 'required');
     }
     $this->form_validation->set_rules('street_name', 'Address', 'required');
     $this->form_validation->set_rules('city', 'City', 'required');
     $this->form_validation->set_rules('state', 'State', 'required');
     $this->form_validation->set_rules('zip_code', 'Zip', 'required');
  	 $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
	 $this->form_validation->set_rules('phone_number', 'Phone Number', 'required',
	 	    array(
                'required'      => 'This %s field is required.'
                
        ));               



    // $assistant_email = $this->input->post('assistant_email');
   
    // if(!empty($assistant_email))
    // {
    //     // Loop through hotels and add the validation
    //     foreach($assistant_email as $id => $data)
    //     {
    //         $this->form_validation->set_rules('assistant_email[' . $id . ']', 'Assistant', 'valid_email');
          
    //     }
    // }

				if ($this->form_validation->run() == FALSE)
	                {
	                	
	                	if($data['role_id'] == 1){
	                		
				$this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('user/edit',$data);
    //$this->load->view('admin/customer/add');
    $this->load->view('admin/footer', $data);
			}else{
	        $this->load->view('header', $data);
			$this->load->view('sidebar');
			$this->load->view('user/edit',$data);
			$this->load->view('footer', $data);
		}

	                }
                 else
                {

                

                
                	$data = array(
                    'first_name' => ($this->input->post('first_name') != '') ? $this->input->post('first_name') :  $data['first_name'] = $user_details[0]->first_name,
                    'last_name' => ($this->input->post('last_name') != '') ? $this->input->post('last_name') :  $data['last_name'] = $user_details[0]->last_name,
                    'email' => ($this->input->post('email') != '') ? $this->input->post('email') :  $data['email'] = $user_details[0]->email,
                     'company_name' => ($this->input->post('company_name') != '') ? $this->input->post('company_name') :  $data['company_name'] = $user_details[0]->company_name,
                    'id' => ($this->input->post('id') != '') ? $this->input->post('id') :  $data['id'] = $user_details[0]->id,
                    'street_name' => ($this->input->post('street_name') != '') ? $this->input->post('street_name') :  $data['street_name'] = $user_details[0]->address,
                    'city' => ($this->input->post('city') != '') ? $this->input->post('city') :  $data['city'] =  $user_details[0]->city,
                    'state' => ($this->input->post('state') != '') ? $this->input->post('state') :  $data['state'] =  $user_details[0]->state,
                    'company_name' => ($this->input->post('company_name') != '') ? $this->input->post('company_name') :  $data['company_name'] =  $user_details[0]->company_name,
                    'phone_number' => ($this->input->post('phone_number') != '') ? $this->input->post('phone_number') :  $data['phone_number'] = $user_details[0]->phone_number,
                    'zip_code' => ($this->input->post('zip_code') != '') ? $this->input->post('zip_code') :  $data['zip_code'] = $user_details[0]->zip_code,
                    'on_board_date' => ($this->input->post('on_board_date') != '') ? $this->input->post('on_board_date') :  $data['on_board_date'] = date("m/d/Y", strtotime($user_details[0]->on_board_date)),
                    'on_board_time' => ($this->input->post('on_board_time') != '') ? $this->input->post('on_board_time') :  $data['on_board_time'] = $user_details[0]->on_board_time, 
                    'more_info' =>  $this->input->post('more_info'),
                    'assistant_id' => ($this->input->post('assistant_id') != '') ? $this->input->post('assistant_id') :  $data['assistant_id'] = $user_details[0]->assistant_id,
                    'assistant_name' => ($this->input->post('assistant_name') != '') ? $this->input->post('assistant_name') :  $data['assistant_name'] = $user_details[0]->assistant_name,
                    'assistant_email' => ($this->input->post('assistant_email') != '') ? str_replace(' ', '', $this->input->post('assistant_email')) :  $data['assistant_email'] = $user_details[0]->assistant_email,
                    'email_status' => $this->input->post('email_status'),
                    'assistant_phone' => ($this->input->post('assistant_phone') != '') ? $this->input->post('assistant_phone') :  $data['assistant_phone'] = $user_details[0]->assistant_phone,
                    'delete_assistant' => ($this->input->post('delete_assistant') != '') ? $this->input->post('delete_assistant') :  $data['delete_assistant'] = "",
                   
                    
                );


                	$user_details = $this->Users_model->updateuser($data);

                	  $this->session->set_flashdata('success_message',"User profile has been updated.");
                	redirect('user/edit');

  //   $this->load->view('header', $data);
		// $this->load->view('sidebar');
		// $this->load->view('user/edit',$data);
		// $this->load->view('footer', $data);

                }
		}
	}
	 else{
    	$this->load->view('header');
          $this->load->view('login/login'); 
          $this->load->view('footer');
    }
	}


  /*
    updation password for customers
  */
	public function changepassword()
	{
		$data =  $this->data;
		if($this->session->userdata('is_logged_in')){
			$this->load->model('Users_model');
			$data['error_class'] = 'form-group has-feedback has-error';
    	$data['error_less_class'] = "form-group";
		$data['name'] = $this->session->userdata('name');
		$data['role'] =  $this->session->userdata('role_id');
		$data['first_letter'] = $this->session->userdata('first_letter');
$data['company'] = ($this->session->userdata('company') !== NULL) ? TRUE : FALSE;
		  $data['error_class'] = 'form-group has-feedback has-error';
    	$data['error_less_class'] = "form-group";
		$data['id'] =  $this->session->userdata('user_id');
		$this->load->view('header', $data);
		$this->load->view('sidebar');
		$this->load->view('user/password',$data);
		$this->load->view('footer', $data);
		}
	else{
		$this->load->view('header');
        	$this->load->view('login/login');	
        	$this->load->view('footer');
		}
	}
	public function updatepassword()
	{
		
		$data =  $this->data;


    

		if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$this->load->model('Users_model');
			$data['name'] = $this->session->userdata('name');
		$data['role'] =  $this->session->userdata('role_id');
		$data['id'] =  $this->session->userdata('user_id');
$data['company'] = ($this->session->userdata('company') !== NULL) ? TRUE : FALSE;
		  $data['error_class'] = 'form-group has-feedback has-error';
    $data['error_less_class'] = "form-group";
		$data = array(
			'id' => $this->session->userdata('user_id'),
			'password' => $this->input->post('new_password'),
			'error_class' => 'form-group has-feedback has-error',
			'error_less_class' => 'form-group',
			);
		$this->form_validation->set_rules('new_password', 'Password', 'trim|required|min_length[5]');
	$this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'trim|required|matches[new_password]');
		// $updatepassword = $this->Users_model->updatepassword($data);
  //               	exit();
			
			if ($this->form_validation->run() == FALSE)
                {

                	$data =  $this->data;
                	$data['error_class'] = 'form-group has-feedback has-error';
    $data['error_less_class'] = "form-group";	
                        $this->load->view('header', $data);
		$this->load->view('sidebar');
		$this->load->view('user/password',$data);
		$this->load->view('footer', $data);
                }
                 else

                {

      	$updatepassword = $this->Users_model->updatepassword($data);
                	
          //              $this->load->view('header', $data);
		//$this->load->view('sidebar');

         $user_id = $this->session->userdata('user_id');

     $user_details = $this->Users_model->fetchdata($user_id);

     $data['email'] = $user_details[0]->username;

     // print_r($data['email']);
     // exit();

       $user_name = $user_details[0]->first_name;

     $subject = 'SalesSupport360 Password Reset';
  
     
     $new_password = $this->input->post('new_password');

     $message  = "Hi " .$user_name .",<br><br>Your password has been successfully updated. Your new password is: ".$new_password.".";


     $this->Users_model->send_mail($data,$message,$subject);



                	$this->session->set_flashdata('success_message',"Password has been updated successfully.");
                	redirect('user/edit');



                    // $user_details = $this->Users_model->fetchdata($data['id']);



    
   

		//$this->load->view('footer', $data);
                }
		}
	}

   

   /**
   *Custom Callback for credit card validation
   *This function calls the creditcard custom helper and returns the status based on the card type chosen
   * E.x Visa card should have 16 digits and the prefix should be 4
   *
   */
   public function card_number_check(){
         $this->load->helper('creditcard');
        $errornumber = 2;
        $errortext ="Credit card number has invalid format.";
    if(checkCreditCard($this->input->post('card_number'), $this->input->post('card_type'),$errornumber,$errortext)){
    	//$this->form_validation->set_message('card_number_check', "");
	  return TRUE;

    }
    else{

$this->form_validation->set_message('card_number_check', $errortext);
    	return FALSE;
    }
	}


	   /**
	    *Custom Callback for credit card expiry validation
	   *This function will check if the entered year is greater then current year else it will throw an error
	   * If the year is greater and if the month is less this function will throw an error for that too.
	   */
   public function card_expiry_check(){
         $expiry_month = $this->input->post('expiry-month');
          $expiry_year = $this->input->post('expiry-year');
          $current_year = date('Y',strtotime('now'));
               $current_month = date('m',strtotime('now'));
          $errortext = "Please enter valid expiry date";
    if($expiry_year >= $current_year ){
    	//$this->form_validation->set_message('card_number_check', "");
    	if($current_year < $expiry_month ){
    		  $errortext = "Please enter valid expiry month.";
    		$this->form_validation->set_message('card_expiry_check', $errortext);
    		return FALSE;
    	}
    	else{
	       return TRUE;
		}
    }
    else{

$this->form_validation->set_message('card_expiry_check', $errortext);
    	return FALSE;
    }


	}

	/**
	* Custom Call back for checking on board date in Registration form
	* this function will only work if some value is entered in the field
	* 
	*/
	public function on_board_date_check(){

date_default_timezone_set('America/New_York');
    if($this->input->post('onboard-time') && $this->input->post('onboard-time')  !== NULL){
      $onboard_time = $this->input->post('onboard-time');

    }
    else{

      $onboard_time = "24:00:00";
    }
		$post_on_board  = $this->input->post('onboard-date') . " ".$onboard_time;

		 if(isset($post_on_board) && !empty($post_on_board)){
		 $onboard = strtotime($post_on_board);

		 $now = time();
         

		 if($onboard >= $now){

		 	return TRUE;
		 }
		 else{


$this->form_validation->set_message('on_board_date_check', "On Board Date should not be in past.");
return FALSE;
		 }

		}
		else{
			return TRUE;
		}
	}



    /**
     *   Customer Validator to check company name
     *  If the client chooses Company checkbox then open company name
     *  If the company name is empty then throw error
     *  If the client didn't choose company check box company name is not a required field
     */

    public function company_name_check(){

        $company_checkbox_value = $this->input->post('company_checkbox');

        if(isset($company_checkbox_value)){
            $company_name  = $this->input->post('company_name');

    
            if(isset($company_name) && !empty($company_name) && $company_name !== " "){
       
                return TRUE;
            }
            else{
             
$this->form_validation->set_message('company_name_check', "Company Name is required.");
return FALSE;

            }

        }
    }



    /**
     *  Login Validator which runs as an ajax call
     */
	 public function validate_login(){
 		$this->load->model('Users_model');
 		$user_name = $this->input->post('user_name');


 
		$password = $this->__encrip_password($this->input->post('password'));

if (empty($user_name) || empty($password)){
  
  echo "username_password_emtpy";

}
else{

		$is_valid = $this->Users_model->validate($user_name, $password);
		
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



  /**
    * This function will run from the terminal as a cron job 
    * First It will fetch all the Users from ss_users_with_updated_plans table 
    * and cancel their current subscription and run new subscription from the given date
    */
    public function update_plans_for_users(){
      //if($this->input->is_cli_request()){
          $this->load->model('Users_model');
      $user_status = $this->Users_model->update_users_to_new_plan();
      print_r($user_status);
      exit();
              //redirect(PHASE1_URL, 'refresh');
       //}
       // else{

       //   echo "Go Out This action is not allowed";
       // }
    }


   /**
    * This function will run from the terminal as a cron job.
    * This is for checking team members notification hours
    *  This wil send an email for team_owner.
    */
function check_team_members_email_status(){
   


$team_details = $this->Users_model->get_teammembers_tasks();//this will fetch all the active team members

// echo "<pre>";
// print_r($team_details);
// exit();
foreach ($team_details as $key => $team_detail){
  $member_id = array();

  $billing_from_to_date = $this->get_billing_cycle($team_detail);// generating  belling cycle from and to date

   $member_id[] =  $team_detail->member_id;

    $data['get_all_tasks'][$team_detail->id] = $this->Task_model->get_all_tasks_by_customers($member_id,$billing_from_to_date['from_date'],$billing_from_to_date['to_date']);// fetching all the tasks created by team member in that billing period



      foreach ($data['get_all_tasks'] as $key => $task){

          
        if(!empty($task)){

             
        $task_ids = array_map(function ($ar) {return $ar->task_id;}, $task);

        $work_logs = $this->Task_model->total_log_hours($task_ids);//calculating work logs for team member

         foreach ($work_logs as $key => $work_log){

            $final_logged_hours = $work_log->final_logged_hours;
            $final_min_to_add = $work_log->final_min_to_add;

       }

        }

        else{

           $final_logged_hours = NULL;
           $final_min_to_add = NULL;
        }
    }
        

        $parent_details = $this->Users_model->fetchdata($team_detail->user_id);//fetching team owner details


        $parent_first_name = $parent_details[0]->first_name;
        $parent_last_name = $parent_details[0]->last_name;


        $parent_full_name = $parent_first_name." ".$parent_last_name;

        $parent_email = $parent_details[0]->username;


        $member_details = $this->Users_model->fetchdata($team_detail->member_id);// fetching team member details


        $member_first_name = $member_details [0]->first_name;
        $member_last_name =  $member_details [0]->last_name;

        $member_full_name = $member_first_name." ".$member_last_name;

        $bda_details = $this->Users_model->fetchdata($team_detail->bda_id);//fetching bda details if bda is there

        if(!empty( $bda_details)){
        $bda_email = $bda_details[0]->username;
        }
        else{

           $bda_email = NULL;
        }


            // print $final_logged_hours;
            // exit()
          /*comparing notification hour and total log hours of team member if notification hour is greater them this will send an email to tem members parent.once the email is sent we are updating the notification satus as 16*/
          if( $final_logged_hours >= $team_detail->notification_hour ){

            $subject = 'SS360 Monthly Usage Update';

            $message = "Hi ".$parent_full_name.",<br><br>
 
            This is a gentle reminder that your Team member ".$member_full_name." has consumed ".$final_logged_hours." hour(s) ". $final_min_to_add." minute(s).";

            // print $message;
            // exit();

            $data['email'] =  $parent_email; 
            $cc = $this->Users_model->admin_emails();

            if(!empty($bda_email)){

            $cc = $this->Users_model->admin_emails();
            $cc[] = $bda_email;

           }
        
            $this->Users_model->send_mail($data,$message,$subject,$cc);

            $users = array();
            $users['user_id'] = $team_detail->member_id;

            $users['status_id_to_update'] = 16;
            $this->Users_model->update_email_status($users);
          }
      }

  }



 /**
        * Calculate Billing Cycle (From date,To Date)
        *
        * Get the Transaction Date
        *
        * Get the Current Date
        *
        * Check if the Current Month Date is less then or greater then the Transaction Start Month Date.
        *
        * If it is past add +1 to the month count
        * Else substract -1 month count

        Eg1: Trasaction Date = 6/06/2017

            Current Date = 19/07/2017

            So the From Date = 6/07/2017

            and the To Date =6/08/2017

    */      

  private function get_billing_cycle($user){

    $timesatamp = strtotime($user->transaction_date);
        $date_from_string = date('d',$timesatamp);
        $month_from_string = date('m',$timesatamp);
        $current_date = date('d');
        $current_month = date('m');
        $current_year = date('Y');
        
if($current_date > $date_from_string){  
          $start_date = $date_from_string;
          $current_month  = date("m");
          $from_date = $start_date. "-". $current_month . "-" . $current_year;
          $next_month_end = date("m", strtotime(" +1 months"));
          if($current_month !== "12"){
              $year = date("Y");
              $to_date = $start_date . "-" . $next_month_end."-".$year;
          }
          else{
               $year = date("Y", strtotime(" +12 month"));
             
                $to_date = $start_date . "-" . $next_month_end ."-".$year;

          }
        }
        else{
                $start_date = $date_from_string; 
                $current_motnh = date("m");
                $next_month_end = date("m", strtotime(" -1 months"));
              if($current_month !== "January"){
                  $year = date("Y");
                  $from_date = $start_date . "-" . $next_month_end ."-" .$year;
              }
              else{
                  $year = date("m", strtotime(" -12 month"));
                  $from_date = $start_date . "-" . $next_month_end ."-" .$year;
              }
              $to_date  =  $start_date. "-". $current_month . "-" . $current_year;
              }

          return array('to_date' => $to_date, 'from_date' => $from_date);
  }

/**
 * This function will run from the terminal as a cron job. every day once
 *   80% of hours consumed 
 *   100% of hours consumed
 */

 function check_customers_email_status(){


      $users= $this->Task_model->get_customers_details();

      $customers = array();
      $user_ids = array();
      $seventy = array();
      $hundered = array();
      foreach($users as $user){
  
        $members = $this->Users_model->get_team_members($user->id);
        $bda = NULL;
        if(!empty($user->bda_id)){
           $bda = $this->Task_model->fetch_email_for_id($user->bda_id);
        }

        foreach($members as $member){
          $customers[$user->id]['members'][] = $member->member_id;
        }

        $customers[$user->id]['members'][] = $user->id;
        $billing_from_to_date = $this->get_billing_cycle($user);

        $customer_tasks = $this->Task_model->get_all_tasks_by_customers($customers[$user->id]['members'],$billing_from_to_date['from_date'],$billing_from_to_date['to_date']);
        $customers[$user->id]['tasks'] = array();
        foreach($customer_tasks as $task){

            $customers[$user->id]['tasks'][] = $task->task_id;
        }
        $customer[$user->id]['bda_email'] = $bda;

        $customers[$user->id]['first_name'] = $user->first_name;
        $customers[$user->id]['last_name'] = $user->last_name;


        $customers[$user->id]['email'] = $user->username;
        // print_r()

        if(!empty($customers[$user->id]['tasks'])){
            $customers[$user->id]['work_logs'] = $this->Task_model->total_log_hours($customers[$user->id]['tasks']);
         }
         else{

              $customers[$user->id]['work_logs'] = array();

         }

        
        /*Get both team plan hour and normal plan hour in one common variable*/
        if(isset($user->seventy_percent_hours) && !empty($user->seventy_percent_hours)){
          $customers[$user->id]['70_percent_total_hour'] = $user->seventy_percent_hours;
            $customers[$user->id]['70_percent_total_hour'] = $user->seventy_percent_hours;

            $customers[$user->id]['plan_hours'] = $user->plan_hours;
        
        }
        if(isset($user->team_seventy_percent_hours) && !empty($user->team_seventy_percent_hours)){
          $customers[$user->id]['70_percent_total_hour'] = $user->team_seventy_percent_hours;

           $customers[$user->id]['plan_hours'] = $user->team_plan_hours;
        }

        if(isset($user->hunderd_percent_hours) && !empty($user->hunderd_percent_hours)){

               $customers[$user->id]['100_percent_total_hour'] = $user->hunderd_percent_hours;

                $customers[$user->id]['plan_hours'] = $user->plan_hours;

        }
        if(isset($user->team_hunderd_percent_hours) && !empty($user->team_hunderd_percent_hours)){

      $customers[$user->id]['100_percent_total_hour'] = $user->team_hunderd_percent_hours;
       $customers[$user->id]['plan_hours'] = $user->team_plan_hours;
          
        }
        $customers[$user->id]['bda_email']= $bda;
        $customers[$user->id]['email_notification_status']= $user->email_notification_status;

        /*Compare the above created array & Update status to 14*/
        if((!empty($customers[$user->id]['work_logs'][0]->final_logged_hours)) && ($customers[$user->id]['work_logs'][0]->final_logged_hours >= $customers[$user->id]['70_percent_total_hour'] && $customers[$user->id]['work_logs'][0]->final_logged_hours < $customers[$user->id]['100_percent_total_hour']) ){

         
            if(14 !== (int) $customers[$user->id]['email_notification_status']){
            
              $email['email'] = $user->username;
                $cc = $this->Users_model->admin_emails(); 
            $subject = 'SS360 Monthly Usage Update'; 
            $message  = "Hi ".$customers[$user->id]['first_name'] . " ".$customers[$user->id]['last_name'].",<br><br>"."<div> We are pleased to inform you that you have used ".$customers[$user->id]['work_logs'][0]->final_logged_hours ." hour(s) ".$customers[$user->id]['work_logs'][0]->final_min_to_add." minute(s) "."out of your ".$customers[$user->id]['plan_hours']." hour(s) monthly package.</div>
                <br>
                <div>
                  As we approach 80 % of your total time we would like to get your approval should any work in our system needs to be completed that would require us going over your monthly allotment.
                </div>
                <br>
                <div>If you do not wish for us to proceed, please respond to this email.</div>
                <br>
                <div>Should you have any questions or any special request projects please let us know as we are here to help you build your sales pipeline!</div>";

                if(!empty($customers[$user->id]['bda_email'])){
                    $bda_in_bcc = $customers[$user->id]['bda_email']; 
                }
                else{
                   $bda_in_bcc = NULL;
                }
                $this->Users_model->send_mail($email,$message,$subject,$cc, NULL,$customers[$user->id]['bda_email'],$bda_in_bcc);

                $seventy['user_id'][] = $user->id;
                $seventy['status_id_to_update'] = 14;

          }
        }
        /*Update status to 16*/
        if((!empty($customers[$user->id]['work_logs'][0]->final_logged_hours)) && ($customers[$user->id]['work_logs'][0]->final_logged_hours >= $customers[$user->id]['100_percent_total_hour'])){
          if(16 !== (int) $customers[$user->id]['email_notification_status']){
   
            // print_r($user);
            // $user_ids[$user->id]['id'] = $user->id;
            // $user_ids[$user->id]['status'] = 16;
            // $user_ids[$user->id]['old_status'] = $customers[$user->id]['email_notification_status'];
                $email['email'] = $user->username;
                $cc = $this->Users_model->admin_emails(); 
                $subject = 'SS360 Overage Hours Update'; 
                $message  = "Hi ".$customers[$user->id]['first_name'] . " ".$customers[$user->id]['last_name'].",<br><br>"." <div> We are pleased to inform you that you have used ".$customers[$user->id]['work_logs'][0]->final_logged_hours ." hour(s) ".$customers[$user->id]['work_logs'][0]->final_min_to_add." minute(s) out of your ".$customers[$user->id]['plan_hours']." hour(s) monthly package.</div><br>
<div>Any work going over the ".$customers[$user->id]['plan_hours']." hour(s) allotment you would be charged a rate of $8.50 per hour.<br><br>
 
If you would not want us to continue, please reply to this email right away. <br><br>
 
Should you have any questions or any special request projects please let us know as we are here to help you build your sales pipeline!<br><br>
</div>";
             if(!empty($customers[$user->id]['bda_email'])){
                    $bda_in_bcc = $customers[$user->id]['bda_email']; 
                }
                else{
                   $bda_in_bcc = NULL;
                }
                $this->Users_model->send_mail($email,$message,$subject,$cc, NULL,$customers[$user->id]['bda_email'],$bda_in_bcc);
                  $hundered['user_id'][] = $user->id;
                $hundered['status_id_to_update'] = 16;
          }
          
        }
   
      }


    
      $update_email_status = "All Updated";
     if(!empty($seventy)){  
        $update_email_status=$this->Users_model->update_email_status($seventy);
     }

     if(!empty($hundered)){
        $update_email_status=$this->Users_model->update_email_status($hundered);
    }



     
        echo "<pre>";
        //print_r($user_ids);
        print_r($update_email_status);
        exit();
 }
/* 1.Usage is less than 50% on the 10th and less than 60% on the 20th day of each billing cycle
   2. This will run as a cron job everday once
   3.this function will compare transaction_date + 15 days equal to current date.
   4.if true we are calculating worklog and comparing with plan hours
   5.if the worklog is lees than 40% we are sending an email
   6.similerly, we are sending an email to customer transaction_date + 20 days too. 
*/

 public function email_notification_for_customers(){

   $users = $this->Task_model->get_customers_plan_hours();

   // echo "<pre>";
   // print_r($users);
   // exit();

    $customers = array();
    $user_ids = array();

      
      foreach($users as $user){
   
        $members = $this->Users_model->get_team_members($user->id);
        $bda = NULL;
        if(!empty($user->bda_id)){
           $bda = $this->Task_model->fetch_email_for_id($user->bda_id);
        }

        foreach($members as $member){
          $customers[$user->id]['members'][] = $member->member_id;
        }

        $customers[$user->id]['members'][] = $user->id;

        $billing_from_to_date = $this->get_billing_cycle($user);

        $Date=$billing_from_to_date['from_date'];

  

        /*Calculating 15th and 20th day of each customer*/

        $fifteenth_date = date('Y-m-d', strtotime($Date. ' + 10 days'));
        $current_date = date('Y-m-d');
        $twenteenth_date = date('Y-m-d', strtotime($Date. ' + 15 days'));



        $customer_tasks = $this->Task_model->get_all_tasks_by_customers($customers[$user->id]['members'],$billing_from_to_date['from_date'],$billing_from_to_date['to_date']);

 

        $customers[$user->id]['tasks'] = array();
        foreach($customer_tasks as $task){

            $customers[$user->id]['tasks'][] = $task->task_id;
        }
  
        $customer[$user->id]['bda_email'] = $bda;

        $customers[$user->id]['first_name'] = $user->first_name;
        $customers[$user->id]['last_name'] = $user->last_name;


        $customers[$user->id]['email'] = $user->username;


        if(!empty($customers[$user->id]['tasks'])){
            $customers[$user->id]['work_logs'] = $this->Task_model->total_log_hours($customers[$user->id]['tasks']);
         }
         else{

              $customers[$user->id]['work_logs'] = array();

         }
 // echo "<pre>";
 //  print_r($customers);
 //  exit();

        /*Get both team plan hour and normal plan hour in one common variable*/
        if(isset($user->fourty_percent_hours) && !empty($user->fourty_percent_hours)){

          $customers[$user->id]['40_percent_total_hour'] = $user->fourty_percent_hours;
           $customers[$user->id]['plan_hours'] = $user->plan_hours;
        
        
        }
        if(isset($user->team_fourty_percent_hours) && !empty($user->team_fourty_percent_hours)){
          $customers[$user->id]['40_percent_total_hour'] = $user->team_fourty_percent_hours;
           $customers[$user->id]['plan_hours'] = $user->team_plan_hours;
        
        }


        if(isset($user->sixty_percent_hours) && !empty($user->sixty_percent_hours)){

          $customers[$user->id]['60_percent_total_hour'] = $user->sixty_percent_hours;
           $customers[$user->id]['plan_hours'] = $user->plan_hours;
        
        
        }
        if(isset($user->team_sixty_percent_hours) && !empty($user->team_sixty_percent_hours)){
          $customers[$user->id]['60_percent_total_hour'] = $user->team_sixty_percent_hours;
           $customers[$user->id]['plan_hours'] = $user->team_plan_hours;
        
        }


        $customers[$user->id]['bda_email']= $bda;

         if(!empty($customers[$user->id]['bda_email'])){
                    $bda_in_bcc = $customers[$user->id]['bda_email']; 
                }
                else{
                   $bda_in_bcc = NULL;
                }

       
    if($fifteenth_date == $current_date){ //check for 10th day

        /* Compare the above created array */

  if((!empty($customers[$user->id]['work_logs'][0]->final_logged_hours)) && ($customers[$user->id]['work_logs'][0]->final_logged_hours <  $customers[$user->id]['40_percent_total_hour'])){
            
        $email['email'] = $user->username;
        $cc = $this->Users_model->admin_emails();
        $subject = '​SS360 Monthly Prospecting Update'; 

        $message  = "Hi ".$customers[$user->id]['first_name']." ".$customers[$user->id]['last_name'].",<br><br>";

      // $message .= "We noticed that you have only utilized ".$customers[$user->id]['work_logs'][0]->final_logged_hours ." hour(s) ".$customers[$user->id]['work_logs'][0]->final_min_to_add." minute(s) out of your ". $customers[$user->id]['plan_hours']." hour(s) with us.<br><br>";

       $message .="What's on your calendar? Let SalesSupport360 help you be prepared in your client meetings. Please check out SalesSupport360's 11 customized Lead Generation Services each catered uniquely to your individual prospecting needs. Let SalesSupport360 do the heavy lifting so you can concentrate on Higher Revenue- Generating Tasks.<br><br>Have some new projects for us? Order away on the Dash Board. Please do send more tasks our way so that your monthly subscription would be utilized to the fullest.<br><br><br>Don't hesitate to contact your Business Development Assistant and they'll be more than happy to assist you with your inquiries. ";

      $this->Users_model->send_mail($email,$message,$subject,$cc, NULL,$customers[$user->id]['bda_email'],$bda_in_bcc);
      

            }
           }
           


  if($twenteenth_date == $current_date){ //check for 20th day 

 if((!empty($customers[$user->id]['work_logs'][0]->final_logged_hours)) && ($customers[$user->id]['work_logs'][0]->final_logged_hours < $customers[$user->id]['60_percent_total_hour'])){

        $email['email'] = $user->username;
        $cc = $this->Users_model->admin_emails();

        $subject = '​SS360 Monthly Prospecting Update'; 

          $message  = "Hi ".$customers[$user->id]['first_name']." ".$customers[$user->id]['last_name'].",<br><br>";

       //$message .= "We noticed that you have only utilized ".$customers[$user->id]['work_logs'][0]->final_logged_hours ." hour(s) ".$customers[$user->id]['work_logs'][0]->final_min_to_add." minute(s) out of your ". $customers[$user->id]['plan_hours']." hour(s) with us.<br><br>";

       $message .="May all be well!<br><br>Let's keep your sales pipeline full.<br><br>Take advantage of these top 5 prospecting reports available on your SalesSupport360 dashboard.<br><br>1. Referral Generation Report<br>2. LinkedIn Common Connections Report<br>3. Lead Generation List<br>4. Recruiting List<br>5. Special Reports/ Special Tasks<br><br>Let me know if there is anything that I can assist you with.  We really can help with everything!<br><br>In the meantime, please send in your report orders to keep your sales process moving forward.";


      $this->Users_model->send_mail($email,$message,$subject,$cc, NULL,$customers[$user->id]['bda_email'],$bda_in_bcc);
      
          

      }

    }

  }

}
/**
*
* This function will run from the terminal as a cron job every 15th and 20th. This will fetch all bda's 
*
* Their Active Customers and their Team members
* 
* Get their tasks and their logged hours and send an email to bda
*/

function email_notification_for_bda(){

  $all_bda = $this->Users_model->fetch_users_by_role(3,NULL);

  foreach($all_bda as $bda_id => $bda_object){


    $message = "";
    $subject = "Customer(s) remaining hours | SalesSupport360";
    $message .= "Hi ". $bda_object->first_name. ' '.$bda_object->last_name.",<br>Please find the remaining hours of your customer(s):<br>"; 
    //print_r($bda_object);
    $message .="<table border='1'><thead> <th>Customer Name</th>
  <th>Email ID</th>
  <th>Plan Type</th>
  <th> Monthly Usage</th></thead><tbody>";
    $data['email'] = $bda_object->username;
    // $customers = $this->Users_model->get_customers_tasks($bda_object->id);
    // $team_owners = $this->Users_model->get_teamowner_tasks($bda_object->id);

    // $users = array_merge($customers, $team_owners);

    $users = $this->Users_model->fetch_bda_customers($bda_object->id);
 
    $customers = array();
    $tasks = array();
    foreach($users as $user){
      $billing_from_to_date = $this->get_billing_cycle($user);

   
      $customers = $this->Users_model->get_members_ids($user->id);
      $customers[] = $user->id;
      
      $tasks[$user->id] = $this->Task_model->get_all_tasks_by_customers($customers,$billing_from_to_date['from_date'],$billing_from_to_date['to_date']);


      if(empty($tasks[$user->id])){
          $tasks[$user->id] = array();



      }
    }

    foreach($tasks as $user_id => $task){
      $task_ids = array_map(function ($ar) {return $ar->task_id;}, $task);
      if(!empty($task_ids)){
      $tasks[$user_id]['logged_details'] =  $this->Task_model->total_work_log($task_ids);
      }
      else{
        $tasks[$user_id]['logged_details'] = array();
      }
    }
   


    if(!empty($users)){
 
     foreach($users as $user){
    
           $message .="<tr>";
           $message .="<td>".$user->first_name.
           " ". $user->last_name."</td>";
           $message .= "<td>".$user->username."</td>";
           $message .= "<td>".$user->plan_name."</td>";

           if(!empty($tasks[$user->id]['logged_details'])){
            if(!empty($tasks[$user->id]['logged_details'][0]->log_hrs) || !empty($tasks[$user->id]['logged_details'][0]->log_min)){

               $logged_hour = $tasks[$user->id]['logged_details'][0]->log_hrs;
               $logged_min = $tasks[$user->id]['logged_details'][0]->log_min;
               $calculated_hours = floor($logged_min/60);
               $calculated_min = $logged_min%60;
               $logged_hour +=$calculated_hours;
               $logged_min = $calculated_min;

              $message .= '<td>'. $logged_hour . ' Hrs '.$logged_min. ' Min </td>';
            }

              else{
               $message .= "<td>0</td>";
             }
          
            
          }
          else{
               $message .= "<td>0</td>";

          }
          
           $message .="</tr>";
         
       
     }
    }
    $users = array();
    $message .= "</tbody></table>";


    $cc = $this->Users_model->admin_emails();
    $this->Users_model->send_mail($data,$message,$subject,$cc);

  }

}

/**
 *This function will reset the status of customers and team members.
 * This is for comparing  traction date with current date. if the condition is true we are updating the notification status as not send.
 */

/* This function will run from the terminal as a cron job everyday once.*/

function reset_customers_email_notification_status(){

  $transaction_dates = $this->Users_model->get_transaction_date();
  $user_ids = array();
  foreach ($transaction_dates as $key => $transaction_date){


      $timestamp = strtotime($transaction_date->transaction_date);

      $date_from_string = date('d',$timestamp);

      $current_date = date('d');
      if($date_from_string == $current_date){

          $user_ids['user_id'][] = $transaction_date->user_id;
          $user_ids['user_id'][] = $transaction_date->member_id;
          $user_ids['status_id_to_update'] = 12;
      }  
      else{
            $month = date('m');
            $no_of_days = date('t');
            $year =  date('Y');
            $value = checkdate($month,$date_from_string,$year);
            if($value == FALSE){
                  if($no_of_days == $current_date){
                    $user_ids['user_id'][] = $transaction_date->user_id;
                    $user_ids['user_id'][] = $transaction_date->member_id;
                    $user_ids['status_id_to_update'] = 12;
               }
            }
        }
      }
      $this->Users_model->update_email_status($user_ids);
  }

  /**
   * Fetchs first 10 old users from the system and sends an email to them with activation link
   * To Send email after site gone live.
   * 
   */
  public function send_initiation_email(){

    $users = $this->Users_model->fetch_customers_for_sending_initiation_mail();

    if(!empty($users)){
    /*Mail Sending Code*/
    $mail_sent_users = array();
    foreach($users as $user){
      $data = array();
      $data['email'] = $user->username;
        $subject = "SalesSupport360 - Your Account has been activated";
        $message = "Hi ". $user->first_name ." " .$user->last_name .",<br><br>";
        $message .= "We are happy to let you know that we have launched our new SalesSupport360 dashboard! Please follow the instructions below to activate your account.<br><br>";


        $message .= "Please click the link: <br><br><a href='".base_url()."/user/initiate_user/".$user->temp_token."'>".base_url()."/user/initiate_user/".$user->temp_token."</a><br><br>";

        $message .="Step 1: Please enter your password and confirm password<br>Step 2: Please enter card information Card Type, Card Number, Card Holder’s name, Expiration Date.<br>Step 3: Please check the box if the billing address is the same as the given address on your contact information.<br>Step 4: Register<br><br>Note: We won’t store credit card information in our system.The credit card details are asked in the form associated with the link for the authentication of your billing cycle to the new system.<br><br>Please note that the old dash board will not be accessible until you have successfully activated your account on our new dash board.<br><br>We appreciate your patience with as we continue to improve your experience with us. If you have any questions or are experiencing problems with your activation email please reach out to your respective Business Development Assistant."; 

        
        $mail_status = $this->Users_model->send_mail($data,$message, $subject);
       if($mail_status == true){
          $mail_sent_users[] = $user->id;

        }

    }

    $update_user_status = $this->Users_model->update_initiation_email_status($mail_sent_users);
      print_r($update_user_status);

    }
    else{

      print "all members have been notified";
      exit();
    }

    
  }

  /**
   * reset_password()
   */
  public function reset_password( $tmp_password = NULL){
  
   	$data =  $this->data;
   	$data['tmp_password'] = $tmp_password;
   	$this->load->view('register_header',$data);
   	if(isset($tmp_password)){
   		if($this->input->post()){

   			$validate_reset_pass_link = $this->Users_model->validate_reset_pass_link($tmp_password);
   			$data['user_id'] = $validate_reset_pass_link[0]->id;
   			$this->load->library('form_validation');
   			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
   			$this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'trim|required|matches[password]');
   			if ($this->form_validation->run() == FALSE)
   			{
   				$this->load->view('user/reset_password',$data);
   			}
   			else
   			{
   			$update_password = $this->Users_model->update_password($this->input->post('user_id'), $this->input->post('password'));
   			  if($update_password){
   			  	
   			  	$data = array();
   				//$this->session->set_flashdata('success_message',"Password has been updated successfully");
   				redirect('/user/reset_password');
   			  }
   			}
   		}
   		else{
   			
   			$validate_reset_pass_link = $this->Users_model->validate_reset_pass_link($tmp_password);
   			if(isset($validate_reset_pass_link[0]->id)){
   				$data['user_id'] = $validate_reset_pass_link[0]->id;
   				$this->load->view('user/reset_password', $data);
   			}else{
   				redirect(PHASE1_URL,'refresh');
   			}
   		}
   		
   	}
   	else{
   		$this->load->view('user/reset_password', $data);
   	}

  	$this->load->view('register_footer',$data);
  	
  }
  
  function validate_forgetPass(){
  	$this->load->model('Users_model');
  	$user_name = $this->input->post('user_name');
  
  
  	if (empty($user_name)){
  
  		echo "username_emtpy";
  
  	}
  	else{
  
  		$is_valid = $this->Users_model->validate_forgetPass($user_name);
  			
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
  
  /**
   * send_resetLink()
   */
  public function send_resetLink(){
  
  	//$data =  $this->data;
  $this->load->model('Users_model');
  
  	$user_name = $this->input->post('user_name');
  
  	$timeStamp = date('Y-m-d H:i:s:u');
  
  	$tmp_password = $timeStamp.$user_name;
  	$encrypt_code = md5($tmp_password);
  
  
  	$update_resetCode = $this->Users_model->update_resetCode($user_name, $encrypt_code);
  	
  	if($update_resetCode){
  		$reset_link = base_url()."user/reset_password/".$encrypt_code;
  		$first_name = $this->Users_model->getUserFirstName($user_name);
  		 
  		//echo $first_name;exit;
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
  
  public function team_register_success(){
  	$data = $this->data;
  	$data['success_message'] = "Thanks for subscribing to SalesSupport360, our team will get in touch with you.";
  	$this->load->view('register_header',$data);
  	$this->load->view('user/team_register_success',$data);
  	
  	$this->load->view('register_footer',$data);
  }



  public function add_referrals(){


  $data = $this->data;
  $data['user_id'] = $this->session->userdata('user_id');
  $data['role_id'] = $this->session->userdata('role_id');
  $data['name'] = $this->input->post('name') ? $this->input->post('name') : "";
  $data['email'] = $this->input->post('email') ? $this->input->post('email') : ""; 
  $data['phone_number'] = $this->input->post('phone_number') ? $this->input->post('phone_number') : "";

  $add_referrals = $this->Users_model->insert_referrals($data);
  if($add_referrals){
  $this->session->set_flashdata('success_message',"Referrals has been added successfully.");
  }
  redirect('/task/inprogress');

  }


  public function email_confirmation(){

    $user_id = $this->session->userdata('user_id');
    $user_details = $this->Users_model->fetchdata($user_id);
    $from = "dgoven@salessupport360.com";
    $bcc = "dgoven@salessupport360.com";
    $data['email'] = $user_details[0]->username;
    $subject = 'Ambassador Program';
    $message  = " <tbody>
                      <tr>
                        <td style='TEXT-ALIGN: justify;LINE-HEIGHT: 15px;PADDING-LEFT: 5px;PADDING-RIGHT: 5px;FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif;PADDING-TOP: 8px;font-size: 12px;color: #1d1d1d;'>Hi ".$user_details[0]->first_name." ".$user_details[0]->last_name.", </td>
                      </tr>
                      <tr>
                        <td style='TEXT-ALIGN: justify;LINE-HEIGHT: 17px;PADDING-LEFT: 5px;PADDING-RIGHT: 5px;FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif;PADDING-TOP: 8px;font-size: 14px;color: #1d1d1d;'>SS350/OP360 Ambassador Program -- New Commission Opportunity</td>
                      </tr>
                      
                      
                      <tr>
                        <td style='TEXT-ALIGN:justify;LINE-HEIGHT:17px;PADDING-LEFT:5px;PADDING-RIGHT:5px;FONT-FAMILY:Verdana, Arial, Helvetica, sans-serif;COLOR:#666666;FONT-SIZE:8pt;PADDING-TOP:8px;'>Here at SalesSupport360/OfficePartners360, we’re excited to introduce you to an innovative Ambassador Program that provides opportunities for commission income when you refer contacts from your network.   </td>
                      </tr>
                      <tr>
                        <td style='TEXT-ALIGN:justify;LINE-HEIGHT:17px;PADDING-LEFT:5px;PADDING-RIGHT:5px;FONT-FAMILY:Verdana, Arial, Helvetica, sans-serif;COLOR:#666666;FONT-SIZE:8pt;PADDING-TOP:8px;'><a href='http://officepartners360.com'>OfficePartners360</a> (OP360) is an outsourcing solutions company for small and medium-sized businesses, and we all know people who work within small to medium sized businesses (SMBs) around the world.    </td>
                      </tr>
                      <tr>
                        <td style='TEXT-ALIGN:justify;LINE-HEIGHT:17px;PADDING-LEFT:5px;PADDING-RIGHT:5px;FONT-FAMILY:Verdana, Arial, Helvetica, sans-serif;COLOR:#666666;FONT-SIZE:8pt;PADDING-TOP:8px;'>Through simple connections with your contacts, SS360/OP360 now offers Ambassadors the potential to acquire a new source of monthly commission income. </td>
                      </tr>
                      <tr>
                        <td style='TEXT-ALIGN: justify;LINE-HEIGHT: 17px;PADDING-LEFT: 5px;PADDING-RIGHT: 5px;FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif;PADDING-TOP: 8px;font-size: 14px;color: #1d1d1d;'>Ambassador Program for SS360 Partners</td>
                      </tr>
                      <tr>
                        <td style='TEXT-ALIGN: justify;LINE-HEIGHT: 17px;PADDING-LEFT: 5px;PADDING-RIGHT: 5px;FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif;PADDING-TOP: 8px;font-size: 8pt;color: #1d1d1d;'>As an SS360 client, we do the network research for you, setting you up to earn commission income from referrals in your LinkedIn network.</td>
                      </tr>
                      
                      
                      <tr>
                        <td style='LINE-HEIGHT:17px;PADDING-LEFT:5px;PADDING-RIGHT:5px;FONT-FAMILY:Verdana, Arial, Helvetica, sans-serif;COLOR:#666666;FONT-SIZE:8pt;PADDING-TOP:8px;'><ul style='list-style-type: disc;margin-top: 0;'>
                            <li>We can search your LinkedIn network, identifying ideal prospects for us</li>
                            <li>We would then provide you a concise email that:<br />o  you can send<br /> Or <br />o we can send from your LinkedIn account on your behalf. </li>
                            <li>When a prospect shows interest, simply let us know and we’ll take it from there.</li>
                          </ul>
                          </td>
                      </tr>
                      
                      
                      <tr>
                        <td style='TEXT-ALIGN:justify;LINE-HEIGHT:17px;PADDING-LEFT:5px;PADDING-RIGHT:5px;FONT-FAMILY:Verdana, Arial, Helvetica, sans-serif;COLOR:#666666;FONT-SIZE:8pt;PADDING-TOP:8px;'><span style='font-size: 13px; color: #1d1d1d;display: block;width: 100%;'>How much can you earn when companies sign up for SS360/OP360 Services?</span>The compensation you earn is based on the number of “seats” your combined clients have under contract. Each “seat” equates to one SS360/OP360 employee that supports a client for 40 hours per week.</td>
                      </tr>
                      
                      <tr>
                        <td style='TEXT-ALIGN:justify;LINE-HEIGHT:17px;PADDING-LEFT:5px;PADDING-RIGHT:5px;FONT-FAMILY:Verdana, Arial, Helvetica, sans-serif;COLOR:#666666;FONT-SIZE:8pt;PADDING-TOP:8px;'><span style='font-size: 13px; color: #1d1d1d;display: block;width: 100%;'>Commission Tiers:</span>Earn more money per seat as your secure more clients.</td>
                      </tr>
                      
                      
                    
                    
                    <tr>
                      <td><table style='width:100%; margin: 10px 0;'>
                          <thead style='    LINE-HEIGHT: 17px;PADDING-LEFT: 5px;PADDING-RIGHT: 5px;FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif;COLOR: #fff;FONT-SIZE: 8pt;PADDING-TOP: 8px;background-color: #164167;'>
                            <tr>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>Tier</th>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>Combined “Seats”</th>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>USD per seat/Month</th>
                            </tr>
                          </thead>
                          <tbody style='    LINE-HEIGHT: 17px;PADDING-LEFT: 5px;PADDING-RIGHT: 5px;FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif;COLOR: #666666;FONT-SIZE: 8pt;PADDING-TOP: 8px;'>
                            <tr>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>C</th>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>1-19 </th>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>$100</th>
                            </tr>
                            <tr>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>B</th>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>20-39 </th>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>$150</th>
                            </tr>
                            <tr>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>A</th>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>40+ </th>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>$200</th>
                            </tr>
                          </tbody>
                        </table></td>
                    </tr>
                    
                    <tr>
                        <td style='TEXT-ALIGN:justify;LINE-HEIGHT:17px;PADDING-LEFT:5px;PADDING-RIGHT:5px;FONT-FAMILY:Verdana, Arial, Helvetica, sans-serif;COLOR:#666666;FONT-SIZE:8pt;PADDING-TOP:8px;'><span style='font-size: 13px; color: #1d1d1d;display: block;width: 100%;'>Examples of Compensation</span>When you secure new clients, SS360/OP360 will pay you each month during the life of the contact. Here are three examples, based on the tiered incentives above:</td>
                      </tr>
                    
                    <tr>
                      <td><table style='width:100%; margin: 10px 0;'>
                          <thead style='    LINE-HEIGHT: 17px;PADDING-LEFT: 5px;PADDING-RIGHT: 5px;FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif;COLOR: #fff;FONT-SIZE: 8pt;PADDING-TOP: 8px;background-color: #164167;'>
                            <tr>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>TIER</th>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>Example: If you sign up the following seats:</th>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>Your monthly payout would be:</th>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>Your annualized payment would be:</th>
                            </tr>
                          </thead>
                          <tbody style='    LINE-HEIGHT: 17px;PADDING-LEFT: 5px;PADDING-RIGHT: 5px;FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif;COLOR: #666666;FONT-SIZE: 8pt;PADDING-TOP: 8px;'>
                            <tr>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>C</th>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>10</th>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>$1,000</th>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>$12,000</th>
                            </tr>
                            <tr>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>B</th>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>25</th>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>$3,750</th>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>$45,200</th>
                            </tr>
                            <tr>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>A</th>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>50</th>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>$10,000</th>
                              <th style='border: 1px solid #eceeef;padding: 4px;'>$120,000</th>
                            </tr>
                          </tbody>
                        </table></td>
                    </tr>
                    
                    <tr>
                        <td style='TEXT-ALIGN:justify;LINE-HEIGHT:17px;PADDING-LEFT:5px;PADDING-RIGHT:5px;FONT-FAMILY:Verdana, Arial, Helvetica, sans-serif;COLOR:#666666;FONT-SIZE:8pt;PADDING-TOP:8px;'><span style='font-size: 13px; color: #1d1d1d;display: block;width: 100%;'>More about OfficePartners360</span>OfficePartners360 is a trusted global Business Process Outsourcing partner, offering services to small and medium sized businesses for nearly 15 years. </td>
                      </tr>
                      
                      <tr>
                        <td style='TEXT-ALIGN: justify;LINE-HEIGHT: 17px;PADDING-LEFT: 5px;PADDING-RIGHT: 5px;FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif;PADDING-TOP: 8px;font-size: 14px;color: #1d1d1d;'>Customized services OP360 offers</td>
                      </tr>
                      
                      
                      <tr>
                        <td style='LINE-HEIGHT:17px;PADDING-LEFT:5px;PADDING-RIGHT:5px;FONT-FAMILY:Verdana, Arial, Helvetica, sans-serif;COLOR:#666666;FONT-SIZE:8pt;PADDING-TOP:8px;'><ul style='list-style-type: disc;margin-top: 0;'>
                            <li>Data Research, Processing &amp; Entry</li>
                            <li>Sales &amp; Marketing Support </li>
                            <li>Call Center Services</li>
                            <li>Bookkeeping &amp; Accounting</li>
                            <li>Application &amp; Web Development</li>
                          </ul>
                          </td>
                      </tr>
                      
                      <tr>
                        <td style='TEXT-ALIGN: justify;LINE-HEIGHT: 17px;PADDING-LEFT: 5px;PADDING-RIGHT: 5px;FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif;PADDING-TOP: 8px;font-size: 14px;color: #1d1d1d;'>Interested in joining the Ambassador Program?</td>
                      </tr>
                      
                      
                      <tr>
                        <td style='TEXT-ALIGN:justify;LINE-HEIGHT:17px;PADDING-LEFT:5px;PADDING-RIGHT:5px;FONT-FAMILY:Verdana, Arial, Helvetica, sans-serif;COLOR:#666666;FONT-SIZE:14px;PADDING-TOP:8px;color: #1d1d1d;'>Let’s get started mining your network for ideal prospects for this program.  Please reply to this email,  and we’ll get started.  </td>
                      </tr>
                    
                    <tr>
                      <td style='TEXT-ALIGN:justify;LINE-HEIGHT:17px;PADDING-LEFT:5px;PADDING-RIGHT:5px;FONT-FAMILY:Verdana, Arial, Helvetica, sans-serif;COLOR:#666666;FONT-SIZE:14px;PADDING-TOP:8px;color: #1d1d1d;'><br />
                        <br />
                        Best,  <br />
                        Dan Goven and Tim Boylan <br>
                        Principals<br>
                        SalesSupport360/OfficePartners360<br>
                        </td>
                    </tr>
                     
                     
                      </tbody>";

$ambassador ="ambassador";

  $confirmation_email = $this->Users_model->send_mail($data,$message,$subject,NULL,NULL,$from,$bcc,$ambassador);
if($confirmation_email){
  $email_status =  $this->Users_model->search_userid($user_id);

  if($email_status == 1){

      $email_send_count = $this->Users_model->fetch_email_details($user_id);


      $total_count = $email_send_count[0]->email_send_count + 1;


  // print_r($total_count);
  // exit(); 

      $data = array('customer_id' => $user_id,
                   'email_date' => date("Y-m-d H:i:s"),
                   'email_send_count' =>$total_count, 
      );

  
      $this->Users_model->update_email_details($user_id,$data);

   }
 else{

              $data = array('customer_id' => $user_id,
                   'email_date' => date("Y-m-d H:i:s"),
                   'email_send_count' => 1, 
                  );

    $this->Users_model->insert_email_details($data);
 }

 

      redirect('/task/inprogress');

  }


  }

	/* Zoho Sync Starts */
  public function zoho_sync()
  {
    // echo "yes, you are here!";
    //exit;
    $user = $this->Users_model->get_data_updates();
  }

}
