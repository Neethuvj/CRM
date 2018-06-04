<?php
class MY_Controller extends CI_Controller
{
	protected $user_data = array();
	
	function __construct()
	{
		parent::__construct();

	//public $user_data = array();
		 $this->load->model('Task_model');
        $this->load->model('Users_model');
        $this->load->model('Status_model');
          $this->load->model('Plan_model');
        //$user_data = array();
        if($this->session->userdata('sidebar_status') == "collapsed"){

          $user_data['sidebar_class'] =  "minified"; 
        }   
        else{
         $user_data['sidebar_class'] =  " ";    
        }

        
        $user_data['user_id'] = $this->session->userdata('user_id');
        $user_data['name'] = $this->session->userdata('name');
        $user_data['first_letter'] = $this->session->userdata('first_letter');
        $user_data['role_id'] =  $this->session->userdata('role_id');
         $user_data['company'] = ($this->session->userdata('company') !== NULL) ? TRUE : FALSE;
        $this->user_data = $user_data;
        
    if($this->session->userdata('switch_from')){


          $user_data['switch_from']=$this->session->userdata('switch_from');
          


      }


       
        if(!$this->session->userdata('is_logged_in')){
           redirect(PHASE1_URL, "refresh");
        }

        // if((int) $user_data['role_id'] == 1){
        //     redirect('/admin/configuration/index', $user_data);
        // }
	}
}


class Admin_Controller extends CI_Controller
{

protected $user_data = array();
    
    function __construct()
    {
        parent::__construct();

    //public $user_data = array();
         $this->load->model('Task_model');
        $this->load->model('Users_model');
        $this->load->model('Status_model');
        $this->load->model('Plan_model');

     //$user_data = array();
        if($this->session->userdata('sidebar_status') == "collapsed"){

          $user_data['sidebar_class'] =  "minified"; 
        }   
        else{
         $user_data['sidebar_class'] =  " ";    
        }


         //$user_data['switch_from'] = $this->session->userdata('switch_from');



      if($this->session->userdata('switch_from')){

          $user_data['switch_from']=$this->session->userdata('switch_from');
          
            }


         $user_data['error_class'] = 'form-group has-feedback has-error';
    $user_data['error_less_class'] = "form-group";
        $user_data['name'] = $this->session->userdata('name');
        $user_data['first_letter'] = $this->session->userdata('first_letter');
        $user_data['role_id'] =  $this->session->userdata('role_id');
             $user_data['user_id'] =  $this->session->userdata('user_id');
        $this->user_data = $user_data;
 
       
        if(!$this->session->userdata('is_logged_in')){
           redirect(PHASE1_URL, "refresh");
        }



        if((int) $user_data['role_id'] !== 1){
          if((int) $user_data['role_id'] !== 8 ){
          redirect("/");
          }
        }
    }


}