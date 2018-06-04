<?php

class Mail extends CI_Controller {

 function __construct() 
  {
	parent::__construct();

        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->library('session'); 
        $this->load->library('email');
        $this->load->helper('form'); 
        $config['protocol'] = 'sendmail';
        $config['mailtype'] = 'html';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['wordwrap'] = TRUE;
        $this->load->library('email', $config);
		//$this->email->initialize($config);
 }

 function index()
 {      
	
    $name = $this->input->post('name');
    $from_email = $this->input->post('email');
    $subject = $this->input->post('subject');
    $message = $this->input->post('message');
    $to_email = "sales@salessupport360.com";
         $this->load->library('email'); 
   
         $this->email->from($from_email, $name); 
         $this->email->to($to_email);
         $this->email->subject('Contact Us'); 
         $this->email->message($message); 
         //Send mail 
         if($this->email->send()) 
         $this->session->set_flashdata("success_message","Email sent successfully."); 
         else 
         $this->session->set_flashdata("error_message","Error in sending Email."); 

   redirect(site_url('contact'), 'refresh'); 
 }
}
?>
