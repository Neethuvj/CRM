<?php

class Profile extends CI_Controller {

 function __construct() 
 {
  parent::__construct();
      
       //$this->load->library(array('session','email'));
    // for flash data
    $this->load->library('session');
      $this->load->library('email');
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
      $from_email = 'archanabp123@gmail.com'; //change this to yours
        $subject    = 'Verify Your Email Address';
        $to_email   = $this->input->post('email');
        $message    = 'Dear User,<br /><br />Please click on the below activation link to verify your email address.<br /><br /> http://www.mydomain.com/user/verify/' . md5($to_email) . '<br /><br /><br />Thanks<br />Mydomain Team';
        //send mail
        $this->email->from($from_email, 'Mydomain');
        $this->email->to($to_email);
        $this->email->subject($subject);
        $this->email->message($message);
        return $this->email->send();
 }


 function index()
  {
   // echo "in";
   // exit;
   $this->load->library('session');
   $id = $this->input->post('id');
     $data=array(
      //'id' =>$this->input->post('id'),
    'first_name'=>$this->input->post('name'),
    'last_name'=>$this->input->post('lastname'),
    'user_name'=>$this->input->post('username'),
    'email'=>$this->input->post('email'),
    'Website'=>$this->input->post('website'),
    'Message'=>$this->input->post('message'));
        //$update= $this->db->update('fuel_users',$data);
      $update= $this->db->update('fuel_users',$data,array('id' => $id));
        
    if($update)
    
    {
     echo"success";
     $this->session->set_flashdata('success_message','Profile Updated successfully.'); 
     //redirect("/");
            
     }
    
     else
     
     { 
      $this->session->set_flashdata('error_message', 'registration Failed ');
      // After that you need to used redirect function instead of load view such as 
      redirect("/");
      
     }
     
  }
  
    
    //activate user account
    /*function verifyEmailID($key)
    {
        $data = array('status' => 1);
        $this->db->where('md5(email)', $key);
        return $this->db->update('fuel_users', $data);
    }*/
 
}
    
   
?>
