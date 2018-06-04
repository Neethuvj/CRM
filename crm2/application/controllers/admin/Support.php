<?php

/**
 * @filesource 
 */
/**
 * Functions for admin support controller This involves listing of all bda,analyst,accountmanager, and their releated actions like update password, delete, edit,etc.
 * @package Controllers
 * @subpackage Admin
 * 
 */

class Support extends Admin_Controller {

	public function __construct()
    {
        parent::__construct();
      
      $this->data = $this->user_data;
  
        if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }
    }

    /**
     *  Listing of all BDA's In the system includes filters
     */
    public function bda_list(){
    	  $data = $this->data;
            $data['support_role_id']= $this->Users_model->fetch_role("BDA");
     $total = $this->Users_model->fetch_users_by_role($data['support_role_id'],$data['user_id']);
     $data['popup_title']='Create BDA';
     $data['delete_popup_title']='Delete BDA';
     $data['type_of_user'] = $this->uri->segment(3); 
     $data['type_of_user'] = $this->uri->segment(3);
         $this->load->library('pagination');
    $config['base_url'] = base_url().'admin/support/bda_list';
        $data['count_tasks']= count($total);
        $config['total_rows'] = $data['count_tasks'];
        $config["per_page"] = 20;
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
  
        //limit end
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
 $bda_list = $this->Users_model->fetch_users_by_role(3,$data['user_id'], $config['per_page'], $limit_end);
 $data['user_list'] = $bda_list;

    $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/support/list',$data);
    $this->load->view('admin/footer', $data);
    }

      /**
       * Listing of all analysts in the system includes filters
       */
       public function analyst_list(){
    	  $data = $this->data;
 $data['type_of_user'] = $this->uri->segment(3);

    $data['popup_title']='Create Analyst';
     $data['delete_popup_title']='Delete Analyst';
$data['support_role_id']= $this->Users_model->fetch_role("Analyst");
    $total = $this->Users_model->fetch_users_by_role($data['support_role_id'],$data['user_id']);


         $this->load->library('pagination');
    $config['base_url'] = base_url().'admin/support/analyst_list';
        $data['count_tasks']= count($total);
        $config['total_rows'] = $data['count_tasks'];
        $config["per_page"] = 20;
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
  
        //limit end
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
 $analyst_list = $this->Users_model->fetch_users_by_role(4,$data['user_id'], $config['per_page'], $limit_end);
 $data['user_list'] = $analyst_list;

   // $data['user_list'] = $analyst_list;

    $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/support/list', $data);
    $this->load->view('admin/footer', $data);
    }


    /**
     *  Listing of all Account Managers in the system
     */
       public function account_manager_list(){
    	  $data = $this->data;
 $data['type_of_user'] = $this->uri->segment(3);

           $data['support_role_id']= $this->Users_model->fetch_role("Account Manager");
           $total = $this->Users_model->fetch_users_by_role($data['support_role_id'],$data['user_id']);

          $data['popup_title']='Create Account Manager';
           $data['delete_popup_title']='Delete Account Manager';
          

             $this->load->library('pagination');
    $config['base_url'] = base_url().'admin/support/account_manager_list';
        $data['count_tasks']= count($total);
        $config['total_rows'] = $data['count_tasks'];
        $config["per_page"] = 20;
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
  
        //limit end
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
 $account_manager_list = $this->Users_model->fetch_users_by_role($data['support_role_id'],$data['user_id'], $config['per_page'], $limit_end);

 $data['user_list'] = $account_manager_list;
      
    $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/support/list',$data);
    $this->load->view('admin/footer', $data);
    }

/**
 * Common action to delete bda, analyst, account manager. Happens as an ajax
 */
public function delete_support_team()
{
      $data = $this->data;

 $data['support_role_id'] = $this->input->post('support_role_id') ? $this->input->post('support_role_id') : "";

 

   if(!empty($this->input->post('delete')))
   {
          $user_to_delete=array('delete' => ($this->input->post('delete') != '') ? $this->input->post('delete') :  $data['delete'] = "");
        $this->Users_model->delete_support_team($user_to_delete );
       
   //redirect('admin/support/bda_list');
   }
   else{
    //$data['warning_message']="Please Select a User";
     $this->session->set_flashdata('warning_message',"Please select a user.");
   }

    if($data['support_role_id'] == 4){
        redirect("admin/support/analyst_list", $data);
      }
    elseif($data['support_role_id'] == 3){
        redirect("admin/support/bda_list", $data);
      }
       elseif($data['support_role_id'] == 8){
        redirect("admin/support/account_manager_list", $data);
      }
         elseif($data['support_role_id'] == 2){
        redirect("admin/customer/index", $data);
      }


}


/**
 * Form Submit Action to create bda, analyst, account managers.
 */
public function create_support_team()
{


  $data = $this->data;

  $data['support_role_id'] = $this->input->post('support_role_id') ? $this->input->post('support_role_id') : "";

  $data['first_name'] = $this->input->post('first_name') ? $this->input->post('first_name') : "";
  $data['last_name'] = $this->input->post('last_name') ? $this->input->post('last_name') : ""; 
  $data['email'] = $this->input->post('email') ? $this->input->post('email') : ""; 

  $data['phone_number'] = $this->input->post('phone_number') ? $this->input->post('phone_number') : ""; 
  $data['password'] = $this->input->post('password') ? $this->input->post('password') : "";



  if($data['support_role_id'] == 4){
   $user_id = $this->session->userdata('user_id');
   $analyst_order = "analyst_order";
   $analyst_users = $this->Users_model->fetch_users_by_role(4,$user_id,NULL,NULL, $analyst_order);
    $analyst_id = $analyst_users[0]->id;
    $current_analyst_order = $analyst_users[0]->analyst_order;
    $all_order = array();
    //Fetch All analyst order values and get the last value of the order and get the updated value by adding 1*/
    foreach($analyst_users as $analyst_user){

        $all_order[] = $analyst_user->analyst_order;
   }
    $updated_analyst_order = end($all_order) + 1;


  $data['updated_analyst_order']=$updated_analyst_order;
}
else{
    $data['updated_analyst_order'] = NULL;
}

 //echo "<pre>";
// print_r($data);
 //echo "</pre>";
 //exit();

  
  $create_support_team = $this->Users_model->create_support_team($data);


     $this->session->set_flashdata('success_message',"User has been succesfully created.");
  if($data['support_role_id'] == 4){
        redirect("admin/support/analyst_list", $data);
      }
    elseif($data['support_role_id'] == 3){
        redirect("admin/support/bda_list", $data);
      }
       elseif($data['support_role_id'] == 8){
        redirect("admin/support/account_manager_list", $data);
      }
  else{
    $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/support/bda_list');
    $this->load->view('admin/footer', $data);

  }


}
/**
 *  Common Validation for the create support team popup. happens as an ajax calls
 */
public function bda_validate()
{

    $this->load->library('form_validation');
    $this->form_validation->set_rules('first_name', 'First Name', 'required|alpha');
    $this->form_validation->set_rules('last_name', 'Last Name', 'required|alpha');
     $this->form_validation->set_rules('email', 'Email', 'required|valid_email', array('valid_email' => 'Provide a valid Email Address,'));

    $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
  $this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'trim|required|matches[password]');

   $this->form_validation->set_rules('phone_number', 'Phone Number', 'required',
        array(
                'required'      => 'The %s is required.'
        ));




      if ($this->form_validation->run() === FALSE) {

      $data = array('error_message_first_name' => form_error('first_name'),'error_message_last_name' => form_error('last_name'),'error_message_email' => form_error('email'),
        'error_message_password' => form_error('password'),
        'error_message_confirm_password' => form_error('confirm_password'),
        'error_message_phone_number' => form_error('phone_number')

        );
         echo json_encode($data);
        }

        else{
              echo "Success";
        }
}


/**
 * Password Edit Form for Support Team
 *
 */
public function bda_reset_password_by_admin(){
  $data = $this->data;

  $user_id=$this->uri->segment(4);
  $user_data=$this->Users_model->get_user_data($user_id);

  $data['support_role_id']=$user_data[0]->role_id;
  $data['support_user_id']=$this->uri->segment(4);



  //$data =  $this->data;
    if($this->session->userdata('is_logged_in')){
      $this->load->model('Users_model');
      $data['error_class'] = 'form-group has-feedback has-error';
    $data['error_less_class'] = "form-group";
    $data['name'] = $this->session->userdata('name');
   $data['role'] =  $this->session->userdata('role_id');
   

      $data['error_class'] = 'form-group has-feedback has-error';
    $data['error_less_class'] = "form-group";
    //$id = $this->uri->segment(4);
   // $this->session->set_userdata('id',$id);
//$this->session->set_userdata('role_id',$role_id);
   // $this->Users_model-> bda_updatepassword_by_admin($data);
   $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/support/resetpassword',$data);
    $this->load->view('admin/footer', $data);
    

    }
  else{
    $this->load->view('header');
          $this->load->view('admin/support/bda_list'); 
          $this->load->view('footer');
    }

    
  }

/**
 * Update Password Form action for Support Team
 *
 * 
 *
 */

   public function bda_updatepassword_by_admin()
  {
    
    $data =  $this->data;


      $data['id'] =  $this->session->userdata('user_id');



    if ($this->input->server('REQUEST_METHOD') === 'POST')
    {
      $this->load->model('Users_model');

      $data['error_class'] = 'form-group has-feedback has-error';
    $data['error_less_class'] = "form-group";
    $data = array(
      'id'=>$this->input->post('user_id'),
      'password' => $this->input->post('new_password'),
      'error_class' => 'form-group has-feedback has-error',
      'error_less_class' => 'form-group',
      );

    $data['support_role_id']=$this->input->post('support_role_id');

   
    $this->form_validation->set_rules('new_password', 'Password', 'trim|required|min_length[5]');
  $this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'trim|required|matches[new_password]');
    // $updatepassword = $this->Users_model->updatepassword($data);
  //                exit();
      
      if ($this->form_validation->run() == FALSE)
                {

                  $data =  $this->data;

    $data['support_role_id']=$this->input->post('support_role_id');
  $data['support_user_id']=$this->input->post('user_id');
   
                  $data['error_class'] = 'form-group has-feedback has-error';
    $data['error_less_class'] = "form-group"; 
     $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/support/resetpassword',$data);
    $this->load->view('admin/footer', $data);



                        
                }
                 else
                {
                   $bda_updatepassword_by_admin= $this->Users_model-> bda_updatepassword_by_admin($data);

                       $user_details = $this->Users_model->fetchdata($data['id']);
      $data['email'] = $user_details[0]->username;
      $user_name = $user_details[0]->first_name;

     $subject = 'SalesSupport360';
    
     
     $new_password = $this->input->post('new_password');
     $message  = "Hi " .$user_name ."Your been has been successfully updated.Your new password is :". $new_password.".";
     $this->Users_model->send_mail($data,$message,$subject);









                  
                     $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');

                  $this->session->set_flashdata('success_message',"Password has been updated successfully.");
                 // redirect('admin/support/bda_list');
    if($data['support_role_id'] == 4){
        redirect("admin/support/analyst_list", $data);
      }
    elseif($data['support_role_id'] == 3){
        redirect("admin/support/bda_list", $data);
      }
       elseif($data['support_role_id'] == 8){
        redirect("admin/support/account_manager_list", $data);
      }

 elseif($data['support_role_id'] == 2){
        redirect("admin/customer/index", $data);
      }
    $this->load->view('admin/footer', $data);
                }
    }
  }

}