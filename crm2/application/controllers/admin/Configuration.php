<?php

/**
 * Functions for admin configuration controller
 * @package Controllers
 * @subpackage Admin
 */

class Configuration extends Admin_Controller {


	public function __construct()
    {
        parent::__construct();
      
      $this->data = $this->user_data;
    


        if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }
    }


    /**
    * Page to list all the admin notification emails. On Clicking on Update validation for email is happening, if successfull in the same active we are updating the email ids too.
    */
  public function index(){

    $data = $this->data;


$data['admin_emails']=$this->Users_model->get_admin_email();
 

   $email_id=$this->input->post('id');
    $admin_email=$this->input->post('email');
    if($admin_email)
    {
      $this->Users_model->update_admin_email($admin_email,$email_id);

     redirect('admin/configuration/index');
    }
    else
    {


    $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/configuration/index');
    $this->load->view('admin/footer', $data);

    }
     
    }





    /**
    * Page to list all the plans in the current system, validation in this page is happening through ajax calls in javascript if succesfull the same page will update the amount.  using update_plan function which globally updates all the users who are in this plan.
    *
    */
    public function plan(){
    $data = $this->data;
    $data['plan_list'] = $this->Plan_model->fetch_all_plans();

    $post_values = $this->input->post();
    
    if(!empty($post_values)){
      $existing_values_of_the_plan = $this->Plan_model->fetch_plan_by_id($post_values['plan_id']);
      if(($existing_values_of_the_plan[0]->plan_amount !== $post_values['plan_amount'])&& !empty($post_values['plan_amount'])){
        $plan_amount_changed = TRUE;
      }
      else{
        $plan_amount_changed = FALSE;
      }


      $update_plan = $this->Plan_model->update_plan($post_values,$plan_amount_changed);
      if($update_plan == TRUE){
        redirect("admin/configuration/plan", $data);
      }
    }
    $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/configuration/plan');
    $this->load->view('admin/footer', $data);

    }


    /**
    * Email validation for the emails which are listing in admin/configuration/index,
    * This function is called as an ajax call in the js files 
    *
    */
     public function admin_email_validate(){

      $post_values = $this->input->post();
       $this->load->library('form_validation');
       
       $this->form_validation->set_rules('email','email', 'required|valid_email', array('valid_email'=> 'Please enter a valid email.'));
       if ($this->form_validation->run() === FALSE) {
         $data = array('error_message_email' => form_error('email'));
         echo json_encode($data);
       }
       else{
          echo "Success";
       }
    }

    /**
    * Plan Amount validation happens while updating the plan amount on admin/configuration/plan
    * This function is called as an ajax call from js
    *
    */
    public function plan_validate(){

      $post_values = $this->input->post();
       $this->load->library('form_validation');
       $this->form_validation->set_rules('plan_name', 'Plan Name', 'required');
       $this->form_validation->set_rules('plan_hour', 'Plan Hour', 'required|numeric');
       $this->form_validation->set_rules('plan_amount', 'Plan Amount', 'required|numeric');
       if ($this->form_validation->run() === FALSE) {
         $data = array('error_message_plan_name' => form_error('plan_name'),'error_message_plan_hour' => form_error('plan_hour'),'error_message_plan_amount' => form_error('plan_amount'));
         echo json_encode($data);
       }
       else{
          echo "Success";
       }
    }

/**
 *   Change password form for admin user.
 */
public function change_password(){
  $data = $this->data;



    if($this->session->userdata('is_logged_in')){
      $this->load->model('Users_model');
      $data['error_class'] = 'form-group has-feedback has-error';
    $data['error_less_class'] = "form-group";
    $data['name'] = $this->session->userdata('name');
    $data['role'] =  $this->session->userdata('role_id');
    $data['first_letter'] = $this->session->userdata('first_letter');
 $data['id'] =  $this->session->userdata('user_id');

  $current_Password = $this->Users_model->get_password_from_db($data['id']);
      $data['error_class'] = 'form-group has-feedback has-error';
    $data['error_less_class'] = "form-group";
    // $data['current_password'] = md5($current_Password[0]->password);
   $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/configuration/change_password');
    $this->load->view('admin/footer', $data);

    }
  else{
    $this->load->view('header');
          $this->load->view('login/login'); 
          $this->load->view('footer');
    }

    
  }


  /**
   *  Login History List in admin panel
   */  
  public function login_history(){
  
    $data =  $this->data;

    $data['total_login_history'] = $this->Users_model->get_login_history();




 $config['base_url'] = base_url().'admin/configuration/login_history';
    $config['total_rows'] = count($data['total_login_history']);
    $config["per_page"] = 20;

   
        $this->load->library('pagination');
        $choice = $config["total_rows"] / $config["per_page"];

        $config["num_links"] = round($choice);
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 20;
        $config['query_string_segment'] = 'page';
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tagl_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tagl_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page-item disabled">';
        $config['first_tagl_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tagl_close'] = '</a></li>';
        $config['attributes'] = array('class' => 'page-link');
        $page = $this->uri->segment(4);

        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        } 
        $order_type = 'Asc';  
        //make the data type var avaible to our view
        $data['order_type_selected'] = $order_type;
        $this->pagination->initialize($config);

$data['login_history'] =  $this->Users_model->get_login_history($config['per_page'], $limit_end);

     $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/configuration/login_history');
    $this->load->view('admin/footer', $data);

  }


/**
 *   Form submission action from admin change password screen
 */
  public function updatepassword()
  {
    
    $data =  $this->data;

    if ($this->input->server('REQUEST_METHOD') === 'POST')
    {
      $this->load->model('Users_model');
      $data['name'] = $this->session->userdata('name');
    $data['role'] =  $this->session->userdata('role_id');
    $data['id'] =  $this->session->userdata('user_id');


    $data['error_class'] = 'form-group has-feedback has-error';
    $data['error_less_class'] = "form-group";




    $data = array(
      'id' => $this->session->userdata('user_id'),
      'password' => $this->input->post('new_password'),
      'error_class' => 'form-group has-feedback has-error',
      'error_less_class' => 'form-group',
      );

      $this->form_validation->set_rules('current_password', 'Current Password', 'trim|required|callback_match_current_password_check');
    $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[5]');
  $this->form_validation->set_rules('confirm_password', 'New Password Confirmation', 'trim|required|matches[new_password]');

      if ($this->form_validation->run() == FALSE)
                {

                  $data =  $this->data;
                  $data['error_class'] = 'form-group has-feedback has-error';
                  $data['current_password'] = md5($this->input->post('current_password'));
    $data['error_less_class'] = "form-group"; 
     $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/configuration/change_password');
    $this->load->view('admin/footer', $data);



                        
            }
          else
            {
         
                  $updatepassword = $this->Users_model->updatepassword($data);
                    $this->session->set_flashdata('success_message',"Password has been updated successfully.");

     $user_details = $this->Users_model->fetchdata($data['id']);
    $data['email'] = $user_details[0]->username;
    $user_name = $user_details[0]->first_name;

   $subject = 'SalesSupport360';
  
   
   $new_password = $this->input->post('new_password');
   $message  = "Hi " .$user_name ."Your been has been successfully updated.Your new password is :". $new_password.".";
   $this->Users_model->send_mail($data,$message,$subject);

       
 



               
    redirect('/admin/configuration/index');    

   }
               

                     
          //              $this->load->view('header', $data);
    //$this->load->view('sidebar');

        


    //$this->load->view('footer', $data);
                }
    }


    /**
    *  Password validation which happens on the updatepassword action
    */
  function match_current_password_check(){


 $userid= $this->session->userdata('user_id');

  $current_Password = $this->Users_model->get_password_from_db($userid);
  $oldpassword=$this->input->post('current_password');
  
    if(empty($oldpassword)){
$this->form_validation->set_message('match_current_password_check', "Current Password is required.");

        return FALSE;

    }

      if($current_Password[0]->password !== md5($oldpassword)){
$this->form_validation->set_message('match_current_password_check', "Current Password is not matching.");

        return FALSE;

      }
      else{
        return TRUE;
      }
                 

  }

    
  }
