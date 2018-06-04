<?php


/**
 * Functions for admin customer controller
 * @package Controllers
 * @subpackage Admin
 */

class Customer extends Admin_Controller {
/**
 * Functions for admin customer controller
 * @package Controllers
 * @subpackage Admin
 */

public function __construct()
    {
        parent::__construct();
      
      $this->data = $this->user_data;
    
     $this->data['sidebar_class'] =  "minified"; 


        if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }
    }


 /**
 *  Admin Active Customer List, Includes filter actions too
 */
 public function index($reset = NULL){

  $data = $this->data;
    $this->load->model('Users_model');
   

     $data['user_list_type'] = "Active";
    
$status = $this->Status_model->fetch_status("active", "general");
    $status_id = $status[0]->id;



     $search_array = $this->input->post();
   //print_r($search_array);
 
if(empty($search_array)) {
  
  $search_array = $this->session->userdata('customer_search'); 
  

        }
    else{
  $this->session->set_userdata('customer_search',$search_array);
        }
     

  if((strpos($_SERVER['HTTP_REFERER'], base_url()."admin/customer/index") === false) || (isset($reset) && $reset == "reset")){

        $this->session->unset_userdata('customer_search');
        $search_array = false;
    }

   

    $data['count_tasks']=count($this->Users_model->fetchusers('', NULL, NULL,NULL,$search_array, $status_id));
    //$data['count_tasks']= $this->Users_model->count_users($status_id);


    $plans = $this->Users_model->get_all_plans();

    $data['plans'] = $plans;
     $this->load->library('pagination');
    $config['base_url'] = base_url().'admin/customer/index';
    $config['total_rows'] = $data['count_tasks'];
    $config["per_page"] = 20;
    $data['statuss'] = $this->Status_model->fetch_status_by_type('general');
   
      //  $this->load->library('pagination');
        $choice = $config["total_rows"] / $config["per_page"];

        $config["num_links"] = round($choice);
       // $search_array = $this->input->post();
        $data['search_array'] = $search_array;
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

    $data['bda_list'] = $this->Users_model->fetch_users_by_role(3, $data['user_id']);
     $data['customer_list'] = $this->Users_model->fetch_users_by_role(2, $data['user_id']);

     

    $data['users_list'] = $this->Users_model->fetchusers('', $order_type, $config['per_page'],$limit_end,$search_array);
    
    $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/customer/list',$data);
    $this->load->view('admin/footer', $data);
 }

 /**
 *  Admin InActive Customer List, Includes filter actions too
 */
  public function in_active($reset = NULL){

  $data = $this->data;

    $config['base_url'] = base_url().'admin/customer/in_active';

      $status = $this->Status_model->fetch_status("inactive", "general");
    $status_id = $status[0]->id;

     $search_array = $this->input->post();

     if(empty($search_array)) {
  
  $search_array = $this->session->userdata('customer_inactive_search'); 
  

        }
    else{
  $this->session->set_userdata('customer_inactive_search',$search_array);
        }
     
  if((strpos($_SERVER['HTTP_REFERER'], base_url()."admin/customer/in_active") === false) || (isset($reset) && $reset == "reset")){

        $this->session->unset_userdata('customer_inactive_search');
        $search_array = false;
    }

     
    $data['count_tasks']=count($this->Users_model->fetchusers('', NULL, NULL,NULL,$search_array, $status_id));
    //$data['count_tasks']= $this->Users_model->count_users($status_id);
    //$data['sidebar_class'] =  "minified"; 
    $data['user_list_type'] = "In Active";
    $plans = $this->Users_model->get_all_plans();
    $data['plans'] = $plans;
    $config['total_rows'] = $data['count_tasks'];
    $config["per_page"] = 20;

  
    $data['statuss'] = $this->Status_model->fetch_status_by_type('general');
   
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);
       

        $data['search_array'] = $search_array;
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

 
    $data['bda_list'] = $this->Users_model->fetch_users_by_role(3, $data['user_id']);
     $data['customer_list'] = $this->Users_model->fetch_users_by_role(2, $data['user_id']);

$data['customer_list'][] =  $this->Users_model->fetch_users_by_role(9, $data['user_id']);


  $data['users_list'] = $this->Users_model->fetchusers('', $order_type, $config['per_page'],$limit_end,$search_array, $status_id);
  // print "<pre>";
  // print_r($data['users_list']);
  //    exit();
      //$data['tasklist'] = $this->Task_model->fetchtask();
   // $data['first_letter'] = $this->session->userdata('first_letter');
    //var_dump($task_data[0]->name);
    //$data['name'] = $task_data[0]->name;

    $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/customer/list');
    $this->load->view('admin/footer', $data);
 }


 /**
 *  Admin Pending Activation Customer List, Includes filter actions too
 */
  public function pending_activation($reset = NULL){

  $data = $this->data;

  $status = $this->Status_model->fetch_status("Pending Activation", "UserActivation");
    $status_id = $status[0]->id;
    $config['base_url'] = base_url().'admin/customer/pending_activation';
   // $data['count_tasks']= $this->Users_model->count_users($status_id);

     $search_array = $this->input->post();

     if(empty($search_array)) {
  
  $search_array = $this->session->userdata('customer_pendingactivation_search'); 
        }
        
    else{
  $this->session->set_userdata('customer_pendingactivation_search',$search_array);
        }
if((strpos($_SERVER['HTTP_REFERER'], base_url()."admin/customer/pending_activation") === false) || (isset($reset) && $reset == "reset")){

        $this->session->unset_userdata('customer_pendingactivation_search');
        $search_array = false;
    }

    $data['count_tasks']=count($this->Users_model->fetchusers('', NULL, NULL,NULL,$search_array, $status_id));
    //$data['sidebar_class'] =  "minified"; 

    $data['user_list_type'] = "Pending Activation";
    $plans = $this->Users_model->get_all_plans();
    $data['plans'] = $plans;
    $config['total_rows'] = $data['count_tasks'];
    $config["per_page"] = 20;

  
    $data['statuss'] = $this->Status_model->fetch_status_by_type('general');
   
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);
        //$search_array = $this->input->post();
    
        $data['search_array'] = $search_array;
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

 
    $data['bda_list'] = $this->Users_model->fetch_users_by_role(3, $data['user_id']);
     $data['customer_list'] = $this->Users_model->fetch_users_by_role(2, $data['user_id']);

 
  $data['users_list'] = $this->Users_model->fetchusers('', $order_type, $config['per_page'],$limit_end,$search_array, $status_id);
  
      //$data['tasklist'] = $this->Task_model->fetchtask();
   // $data['first_letter'] = $this->session->userdata('first_letter');
    //var_dump($task_data[0]->name);
    //$data['name'] = $task_data[0]->name;
    $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/customer/list');
    $this->load->view('admin/footer', $data);
 }


 /**
 *  Admin Old Customer List, Includes filter actions too
 */
 public function old_customer(){

  $data = $this->data;

  $status = $this->Status_model->fetch_status("Old User", "UserActivation");
    $status_id = $status[0]->id;

    $config['base_url'] = base_url().'admin/customer/old_customer';


       $search_array = $this->input->post();
    $data['count_tasks']=count($this->Users_model->fetchusers('', NULL, NULL,NULL,$search_array, $status_id));
     //$this->Users_model->count_users($status_id);
    //$data['sidebar_class'] =  "minified"; 
    //print_r( $data['count_tasks']);
    $data['user_list_type'] = "Old User";
    $plans = $this->Users_model->get_all_plans();
    $data['plans'] = $plans;
    $config['total_rows'] = $data['count_tasks'];
    $config["per_page"] = 20;

  
    $data['statuss'] = $this->Status_model->fetch_status_by_type('UserActivation');
   
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);
 
    
        $data['search_array'] = $search_array;
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

 
    $data['bda_list'] = $this->Users_model->fetch_users_by_role(3, $data['user_id']);
     $data['customer_list'] = $this->Users_model->fetch_users_by_role(2, $data['user_id']);

 
  $data['users_list'] = $this->Users_model->fetchusers('', $order_type, $config['per_page'],$limit_end,$search_array, $status_id);
  
      //$data['tasklist'] = $this->Task_model->fetchtask();
   // $data['first_letter'] = $this->session->userdata('first_letter');
    //var_dump($task_data[0]->name);
    //$data['name'] = $task_data[0]->name;
    $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/customer/list');
    $this->load->view('admin/footer', $data);
 }




  /**
 *  This function is running while an bda is getting assigned to a customer from the admin customer interface
 */
   public function assign_bda(){

    if($this->input->post()){

      
 $customer_ids = $this->input->post('assign_bda');
 $bda_selected = $this->input->post('bda_list');
        if(isset($customer_ids) && !empty($customer_ids)){

           $assigned_bdas =  $this->Users_model->assign_bda($customer_ids, $bda_selected);
         $this->session->set_flashdata('success_message',"BDA is assigned.");


        }
        else{
  $this->session->set_flashdata('error_message'," Please select a customer.");

        }


    }
    else{
         
    }
   
    redirect('/admin/customer/index');
   }


   /**
   *
   *Callback that runs while an admin upgrades/downgrades the customer Plan
   */
   public function update_plan(){
 $data = $this->data;


    if(!empty($this->input->post())){
        $user_id = $this->input->post('user_id');
        $old_plan_id = $this->input->post('plan_id');
        $new_plan_id = $this->input->post('plan_id_selected');
        $plan_hours = $this->input->post('plan_hours');
        $plan_amount = $this->input->post('plan_amount'); 
     
        if($new_plan_id !== $old_plan_id){
            $updated_plan = $this->Plan_model->update_plan_for_single_user($user_id, $old_plan_id, $new_plan_id,$plan_amount,$plan_hours);
                
            if($updated_plan){
            $this->session->set_flashdata('success_message',"The Plan has been updated. ");

            }
        }
        else{
             $this->session->set_flashdata('error_message',"You haven't updated the plan so no action will be taken.");
        }
    }

    redirect('/admin/customer/index');
 

   }


   /**
    * Changing of the customer Status in the admin customer listings
    * if the account is changed from active to inactive we are sending an email to all notification emails plus bda
    *
    * If the account is changed from inactive to active we are sending an email to customer with activation link
    */
   public function update_status(){
   $data = $this->data;

    if(!empty($this->input->post())){

        $user_id = $this->input->post('user_id');
        $current_status_id = $this->input->post('current_status_id');
        $new_status_id = $this->input->post('status_id_selected');
        $email_id = $this->input->post('email_id_in_popup');
        $current_plan_id = $this->input->post('current_plan_id');
        $plan_id_selected = $this->input->post('plan_id_selected');
        $plan_hour_selected = $this->input->post('plan_hours');
        $plan_amount_selected = $this->input->post('plan_amount');
        $transaction_id = $this->input->post('transaction_id_in_popup');
        if($current_status_id !== $new_status_id){

            $this->Users_model->update_user_status($user_id,$current_status_id, $new_status_id,$email_id, $current_plan_id, $plan_id_selected, $plan_hour_selected, $plan_amount_selected,$transaction_id);


      $user_details = $this->Users_model->fetchdata($user_id);
      
      
      $customer_email= $user_details[0]->username;
      $user_name = $user_details[0]->first_name;
      $last_name = $user_details[0]->last_name;
      $temp_token =  $user_details[0]->temp_token;
      $status_name = $user_details[0]->status_name;
      $bda_id = $user_details[0]->bda_id;

     $bda_details = $this->Users_model->fetchdata($bda_id);
   
   if(!empty($bda_details)){
     $bda_email = $bda_details[0]->username;
   }
   else{

    $bda_email = NULL;
   }

     // echo "<pre>";
     //  print_r($bda_details);
     //  exit();

     $full_name =  $user_name." ".$last_name;

     $cc = $this->Users_model->admin_emails();
     $bda_email_string ="";
    if(isset($bda_email)){
      $cc[] = $bda_email;
    }     
      //$cc = $this->Users_model->admin_emails();
  
 if($status_name == "Inactive"){

    $subject = " Account Deactivation";

  $message =  "The account for " .
     $full_name." has been deactivated in the system.";
}
//http://ss360p2.local//user/activate_user/wUlFqgKHdQ

else{

   $cc[] = $customer_email;

 $activation_link = "<a href='".base_url() ."/user/activate_user/".$temp_token."'>".base_url() ."/user/activate_user/".$temp_token."</a>";




  $subject = "Reactivation of the Account";

  $message = "Hi ".$full_name.",<br><br>
              Welcome back to the SalesSupport360.<br><br>
              Please click the following link for reactivation of your account: ".$activation_link."<br><br>
              Please do not hesitate to contact us for any questions or concerns & let me know if there is anything else we can provide you with.";

 }

 
    
     $data['email'] = $cc;

      $this->Users_model->send_mail($data,$message,$subject);
      $this->session->set_flashdata('success_message',"User status has been updated.");  

        }
        else{

          $this->session->set_flashdata('error_message',"You haven't updated the user status so no action will be taken.");  
        }

      
    }


    redirect('/admin/customer/index');

   }

    /**
     *  User Edit Form which is in the admin/customer listings 
     */
    public function user_edit($id){
    //echo $id;
    $data =  $this->data;
 
    $this->load->model('Users_model');
    $data['name'] = $this->session->userdata('name'); 
    $data['first_letter'] = $this->session->userdata('first_letter');
   // $user_id = $this->session->userdata('user_id');
    $user_details = $this->Users_model->fetchdata($id);
    
    $user_current_status_id = $user_details[0]->status_id;
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
    $data['first_name'] = $user_details[0]->first_name;
    $data['last_name'] = $user_details[0]->last_name;
    $data['street_name'] = $user_details[0]->address;
    $data['city'] = $user_details[0]->city;
    $data['state'] = $user_details[0]->state;
    $data['phone_number'] = $user_details[0]->phone_number;
    $data['zip_code'] = $user_details[0]->zip_code;
    $data['company_name'] = $user_details[0]->company_name;
    $data['on_board_date'] =  ($user_details[0]->on_board_date !== NULL) ? date("m/d/Y", strtotime($user_details[0]->on_board_date)) : "";
  
    $data['on_board_time'] = ($user_details[0]->on_board_time !== NULL) ? date("g:i a", strtotime($user_details[0]->on_board_time)) : "";

     $data['company'] = $user_details[0]->company;

    $data['email'] = $user_details[0]->username;
      $data['more_info'] = $user_details[0]->more_info;
    $data['plan_name'] = $this->Users_model->fetch_plan_name($user_details[0]->plan_id);

    if($user_details[0]->plan_id != 3){

$data['plan_hours'] = $this->Users_model->fetch_actual_plan_hours($user_details[0]->plan_id);
}

else{

$data['plan_hours'] = $this->Users_model->fetch_team_plan_hours($user_details[0]->user_id);

}

    for ($i = 0; $i < count($user_details); $i++) {
      $array['assistant_id'][]= $user_details[$i]->assistant_id;
      $array['assistant_name'][]= $user_details[$i]->assistant_name;
      $array['assistant_email'][]= $user_details[$i]->assistant_email;
      $array['assistant_phone'][]= $user_details[$i]->assistant_phone;
      $array['email_status'] []= $user_details[$i]->email_status;
    }
    $data['assistant'][] = $array;
  
    $data['role'] =  $this->session->userdata('role_id');


      $this->load->library('form_validation');
     $this->form_validation->set_rules('first_name', 'First Name', 'required');
    $this->form_validation->set_rules('last_name', 'Last Name', 'required');
     $data['company_checkbox'] = $this->input->post('company_checkbox') ? "checked" : "";
 
  
    if($user_details[0]->company == 1 && $data['company_checkbox'] == "checked" ){

    $this->form_validation->set_rules('company_name', 'Company Name', 'required');
  }
     $this->form_validation->set_rules('street_name', 'Address', 'required');
     $this->form_validation->set_rules('city', 'City', 'required');
     $this->form_validation->set_rules('state', 'State', 'required');
     $this->form_validation->set_rules('zip_code', 'Zip', 'required');
     $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
     $this->form_validation->set_rules('phone_number', 'Phone Number', 'required',
            array(

                'required'      => 'The Phone Number field is required.'
        ));  
    if(!empty($this->input->server('REQUEST_METHOD') === 'POST')){

   
    
     $data['company_name'] = $this->input->post('company_name') ? $this->input->post('company_name') : "";

     
    $data['first_name'] = ($this->input->post('first_name') != '') ? $this->input->post('first_name') :  "";

    $data['last_name'] = ($this->input->post('last_name') != '') ? $this->input->post('last_name') :  "";
    $data['email'] = ($this->input->post('email') != '') ? $this->input->post('email') : "";
    $data['id'] = ($this->input->post('id') != '') ? $this->input->post('id') :  "";
    $data['street_name'] = ($this->input->post('street_name') != '') ? $this->input->post('street_name') : "";

    $data['city'] = ($this->input->post('city') != '') ? $this->input->post('city') :  "";
    $data['state'] = ($this->input->post('state') != '') ? $this->input->post('state') : "";
    $data['phone_number'] = ($this->input->post('phone_number') != '') ? $this->input->post('phone_number') : "";
     $data['company_name'] = ($this->input->post('company_name') != '') ? $this->input->post('company_name') : "";
    $data['zip_code'] = ($this->input->post('zip_code') != '') ? $this->input->post('zip_code') :  "";
    $data['on_board_date'] = ($this->input->post('on_board_date') != '') ? $this->input->post('on_board_date') :  "";
    $data['on_board_time'] = ($this->input->post('on_board_time') != '') ? $this->input->post('on_board_time') :  ""; 
    $data['more_info'] =  $this->input->post('more_info');
    $data['assistant_id'] = ($this->input->post('assistant_id') != '') ? $this->input->post('assistant_id') :  "";
    $data['assistant_name'] = ($this->input->post('assistant_name') != '') ? $this->input->post('assistant_name') :  "";
    $data['assistant_email'] = ($this->input->post('assistant_email') != '') ? $this->input->post('assistant_email') : "";
    $data['assistant_phone'] = ($this->input->post('assistant_phone') != '') ? $this->input->post('assistant_phone') : "";
    $data['delete_assistant'] = ($this->input->post('delete_assistant') != '') ? $this->input->post('delete_assistant') :  "";
                

// echo "<pre>";
// print_r($data);
// exit();


if ($this->form_validation->run() == FALSE)
                    {

   

                    }
                    else{

                        $submitted_values = $this->input->post();



                        
                        $user_details = $this->Users_model->updateuser($submitted_values);
                        $this->session->set_flashdata('success_message',"User profile has been updated successfully.");

                        if($user_current_status_id == 11){
redirect('admin/customer/pending_activation');

                        }
                        if($user_current_status_id == 1){
                          redirect('admin/customer/index');  
                        }
                        if($user_current_status_id == 2){
                          redirect('admin/customer/in_active');  
                        }
                        
                    }

    }
    $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/customer/edit_customer',$data);
    //$this->load->view('admin/customer/add');
    $this->load->view('admin/footer', $data);
   
   
  
 }


  /**
  *
  *Admin Interface to Update Credit Card for Customer
  */
  public  function update_credit_card($id){
    $data =  $this->data;


        $data['name'] = $this->session->userdata('name'); 
        $data['user_id'] = $id;
        $data['first_letter'] = $this->session->userdata('first_letter');
        $data['company'] = ($this->session->userdata('company') !== NULL) ? TRUE : FALSE;
        $user_id = $id;
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
                          $this->session->set_flashdata('success_message',"Credit card details has been updated, this will be reflected in the customers next billing.");         
                        }
                        else{
                              $this->session->set_flashdata('error_message',$update_card_details);   
                        }
                        redirect('admin/customer/update_credit_card/'. $user_id);
                    }
                   
          }


     $this->load->view('admin/header', $data);
        $this->load->view('admin/sidebar');
           $this->load->view('admin/customer/update_credit_card',$data);
   
        $this->load->view('admin/footer', $data);


    }
    



/**
 *  Delete Customer from Admin Interface delets all customer, tasks, assitant details related to customer also sends an email to notification emails 
 */
 public function delete_customer(){

    $user_id = $this->input->post('delete_customer_id');
        $transaction_id = $this->input->post('transaction_id');
    if(isset($user_id) && !empty($user_id)){


      $user_details = $this->Users_model->fetchdata($user_id);

      //$data['email'] = $user_details[0]->username;
      $user_name = $user_details[0]->first_name;
      $last_name = $user_details[0]->last_name;
      $delete_status =  $this->Users_model->delete_user_related_tasks($user_id, NULL,$transaction_id);

      $bda_id = $user_details[0]->bda_id;


   

      $bda_details = $this->Users_model->fetchdata($bda_id);

   
   
   if(!empty($bda_details)){
     $bda_email = $bda_details[0]->username;
   }
   else{

    $bda_email = NULL;
   }

  
     $full_name =  $user_name." ".$last_name;

     $cc = $this->Users_model->admin_emails();

     $data['email'] = $cc;
     
     if($bda_email !== NULL){

      $data['email'][] = $bda_email;
     }

     $subject = 'Account Deletion';
    
     //$cc = $this->Users_model->admin_emails();
  
     $message  = "The account for ".$full_name." has been deleted from the system.";

    
    $this->Users_model->send_mail($data,$message,$subject);

      
       
$this->session->set_flashdata('success_message',"The Customer has been deleted.");
        

    }
    redirect('/admin/customer/index');
 }

 /**
  *  User summary which displays tasks based on the id and status
  *  E.g
  * 
  *  admin/customer/user_summary/1/completed - will list all completed task of customer one
  *  @param int $id
  *  @param string  $task_status_id
  */
 public function user_summary($id,$task_status_id,$reset = NULL){

    
    $data = $this->data;
    
    $status_name = ucwords(str_replace("_", " ", $task_status_id));
    $status_type = "Task";

    if($status_name !== "All"){

        $status_obj = $this->Status_model->fetch_status($status_name, $status_type);
       $status_id = $status_obj[0]->id;
    }
    else{
       $status_id = $status_name; 
    }


    $user_id = $id;


    $data['customer_id'] = $user_id;
    $data['user_type'] = 'admin';
    $data['status_name'] = $status_id;
    $data['task_status_id'] = $task_status_id;
  //$data['first_letter'] = $this->session->userdata('first_letter');
$search_array = $this->input->post();
if(empty($search_array)) {
  
  $search_array = $this->session->userdata('customer_summary_search'); 
  

        }
    else{
  $this->session->set_userdata('customer_summary_search',$search_array);
        }
     
  if((strpos($_SERVER['HTTP_REFERER'], base_url().'admin/customer/user_summary/'.$id.'/'.$task_status_id) === false) || (isset($reset) && $reset == "reset")){

        $this->session->unset_userdata('customer_summary_search');
        $search_array = false;
    }

 

    $data['search_array'] = $search_array;
// print_r($search_array);
// exit();
   // $data['name'] = $this->session->userdata('name');
    $data['tasklist'] = $this->Task_model->fetchtask();

    $data['get_target_names']=$this->Task_model->get_target_name(NULL);
    $uri = $this->uri->segment(3);
    //print_r($uri);
    $segment = explode('_', $uri);
   // print_r($segment[1]);
    $data['type_of_task']= $segment[1];

     $user_details = $this->Users_model->fetchdata($user_id);

     // echo "<pre>";
     // print_r( $user_details);
     // exit();

     $data['company'] = $user_details[0]->company;

     $data['plan_id'] =$user_details[0]->plan_id;
     
     $data['user_details'] =  $user_details;
      $data['plan_name'] = $this->Users_model->fetch_plan_name($user_details[0]->plan_id);

       if($user_details[0]->plan_id != 3){

      $data['plan_hours'] = $this->Users_model->fetch_actual_plan_hours($user_details[0]->plan_id);
    }
    else{
      $data['plan_hours'] = $this->Users_model->fetch_team_plan_hours($user_details[0]->user_id);

    }
      $user_status = $this->Status_model->fetch_status_by_id($user_details[0]->status_id);
     
      $data['user_status'] = $user_status[0]->name;

    $data['email'] = $user_details[0]->username;
    $data['id'] = $user_details[0]->id;
    $data['first_name'] = $user_details[0]->first_name;
    $data['last_name'] = $user_details[0]->last_name;
    $data['email'] = $user_details[0]->username;
    $this->load->library('pagination');
    $config['base_url'] = base_url().'admin/customer/user_summary/'.$id.'/'.$task_status_id;

     $users = array();
  
     $users = $this->Users_model->get_members_ids($user_id);
     $users[] = $user_id;


      $data['count_tasks']= $this->Task_model->count_task($users,$status_id,$search_array);
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
        // $page = 1;
        $page = $this->uri->segment(6);
      
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        } 
       
        $order_type = 'Asc';  
        //make the data type var avaible to our view
        $data['order_type_selected'] = $order_type;
        $this->pagination->initialize($config);


        $owner_id [] = array();
        $member_ids[] =array();

        $owner_id = $this->Users_model->get_customer_id($user_id);

        $member_ids = $this->Users_model->get_member_ids($user_id);

           
        $user_ids = array_merge($member_ids, $owner_id);


        //     echo "<pre>";
        // print_r($search_array);
        // exit();
        
        $data['tasks'] = $this->Task_model->get_task(NULL, $order_type, $config['per_page'],$limit_end,$user_ids,$status_id,$search_array);


        if(empty($search_array)){


        $data['all_tasks'] = $this->Task_model->get_all_tasks_for_customer($user_ids);



        $data['no_of_connections_total'] = array();
        $data['logged_hours_total'] = array();
        $data['logged_min_total'] = array();
        foreach($data['all_tasks'] as $task){


            $data['no_of_connections_total'][] = $task->no_of_connections;
            $data['logged_hours_total'][$task->id] = $this->Task_model->workLogs($task->id);

            //$data['logged_min_total'][] = $task->log_min;
            
        }

        // print "<pre>";
        // print_r($data['logged_hours_total']);
        // exit();
    

        $grand_total_hour = 0;
        $grand_total_min = 0;
        /*Calculation of min/hours */
        foreach($data['logged_hours_total'] as $task_id => $logged_hours){

        //         print "<pre>";
        // print_r($logged_hours[0]);
        // exit();
         $logged_hour = $logged_hours[0]->log_hrs;
         $logged_min = $logged_hours[0]->log_min;
          
         $calculated_hours = floor($logged_min/60);
         $calculated_min = $logged_min%60;
      
         $logged_hour +=$calculated_hours;
         $logged_min = $calculated_min;
          $data['logged_hours_total'][$task_id][0]->log_hrs = $logged_hour;
          $data['logged_hours_total'][$task_id][0]->log_min = $logged_min;
          $grand_total_hour += $logged_hour;
          $grand_total_min += $logged_min;
        }


         $grand_total_calculated_hours = floor($grand_total_min/60);
         $grand_total_calculated_min = $grand_total_min%60;
      
         $data['grand_total_hour'] =$grand_total_hour + $grand_total_calculated_hours;
         $data['grand_total_min'] = $grand_total_calculated_min;


        }

        else{
        $data['no_of_connections_total'] = array();
        $data['logged_hours_total'] = array();
        $data['logged_min_total'] = array();
        foreach($data['tasks'] as $task){


            $data['no_of_connections_total'][] = $task->no_of_connections;
            $data['logged_hours_total'][$task->id] = $this->Task_model->workLogs($task->id);

            //$data['logged_min_total'][] = $task->log_min;
            
        }
    

        $grand_total_hour = 0;
        $grand_total_min = 0;
        /*Calculation of min/hours */
        foreach($data['logged_hours_total'] as $task_id => $logged_hours){

        //         print "<pre>";
        // print_r($logged_hours[0]);
        // exit();
         $logged_hour = $logged_hours[0]->log_hrs;
         $logged_min = $logged_hours[0]->log_min;
          
         $calculated_hours = floor($logged_min/60);
         $calculated_min = $logged_min%60;
      
         $logged_hour +=$calculated_hours;
         $logged_min = $calculated_min;
          $data['logged_hours_total'][$task_id][0]->log_hrs = $logged_hour;
          $data['logged_hours_total'][$task_id][0]->log_min = $logged_min;
          $grand_total_hour += $logged_hour;
          $grand_total_min += $logged_min;
        }


         $grand_total_calculated_hours = floor($grand_total_min/60);
         $grand_total_calculated_min = $grand_total_min%60;
      
         $data['grand_total_hour'] =$grand_total_hour + $grand_total_calculated_hours;
         $data['grand_total_min'] = $grand_total_calculated_min;


        }

        
        $this->pagination->initialize($config);
        $this->load->view('admin/header', $data);
        $this->load->view('admin/sidebar');
        $this->load->view('admin/customer/summary', $data); 
        $this->load->view('admin/footer', $data);

 }

   /**
    * Admin interface Customer Signup Involves creating of customer and payment process from admin panel
    *
    */
   public function customer_sign_up(){

    
      $this->load->model('Users_model');
      $data =  $this->data;
      if($this->session->userdata('is_logged_in')){


        $data['sidebar_class'] =  "minified"; 
      
  
        $data['name'] = $this->session->userdata('name');
        $data['first_letter'] = $this->session->userdata('first_letter');
        $plans = $this->Users_model->get_all_plans();
        $cards = $this->Users_model->get_all_cards();
        $card_array = array();
        foreach($cards as $card_id => $card_obj){

          $card_array[$card_obj->name] = $card_obj->name;
        }
        $data['plans'] = $plans;
        $data['selected_plan_id'] =$this->input->post('plan_id_selected') ? $this->input->post('plan_id_selected') :  $this->input->get('plan_id');
        $data['cards'] = $card_array;
        $data['error_class'] = 'form-group has-feedback has-error';
        $data['error_less_class'] = "form-group";
        $data['first_name'] = $this->input->post('first_name') ? $this->input->post('first_name') : "";
        $data['last_name'] = $this->input->post('last_name') ? $this->input->post('last_name') : ""; 
        $data['email'] = $this->input->post('email') ? $this->input->post('email') : ""; 
        $data['phone_number'] = $this->input->post('phone_number') ? $this->input->post('phone_number') : ""; 
        $data['address'] = $this->input->post('address') ? $this->input->post('address') : ""; 
        $data['city'] = $this->input->post('city') ? $this->input->post('city') : ""; 
        $data['state'] = $this->input->post('state') ? $this->input->post('state') : ""; 
        $data['zip'] = $this->input->post('zip') ? $this->input->post('zip') : "";
$data['company_checkbox'] = $this->input->post('company_checkbox') ? "checked" : "";
     $data['company_name'] = $this->input->post('company_name') ? $this->input->post('company_name') : "";


        $data['on_board_date'] = $this->input->post('onboard-date') ? $this->input->post('onboard-date') : ""; 
        $data['on_board_time'] = $this->input->post('onboard-time') ? $this->input->post('onboard-time') : ""; 
  //   $this->load->view('register_header', $data);

    if ($this->input->server('REQUEST_METHOD') === 'POST'){
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
     
      if((int) $data['selected_plan_id']  !== 3){
      $this->form_validation->set_rules('card_type', 'Card Type','required');
      $this->form_validation->set_rules('card_number', 'Card Number','required|callback_card_number_check', array('required' => 'Please enter card number'));
      $this->form_validation->set_rules('card_holder_name', 'Card Holder Name','required');
      $this->form_validation->set_rules('onboard-date', 'On Board Date','callback_on_board_date_check');
      $this->form_validation->set_rules('expiry-month', 'Expiry Month','required|callback_card_expiry_check');
      $this->form_validation->set_rules('expiry-year', 'Expiry Year','required|callback_card_expiry_check');


      $this->form_validation->set_rules('billing_street_address', 'Billing Address','required');

      $this->form_validation->set_rules('billing_city', 'Billing City','required');
      $this->form_validation->set_rules('billing_state', 'Billing State','required');
      $this->form_validation->set_rules('billing_zip', 'Billing Zip Code ','required');   
      } 
            if ($this->form_validation->run() == FALSE){
                        $this->load->view('admin/header', $data);
                        $this->load->view('admin/sidebar',$data);
                        $this->load->view('admin/configuration/customer_sign_up');
                        $this->load->view('admin/footer', $data); 
            }else{
                    

  $submitted_values = $this->input->post();
                    if((int) $data['selected_plan_id'] !== 3){
                    $user = $this->Users_model->register_user($submitted_values);
                    }
                    else{
 $user = $this->Users_model->register_team_plan_user($submitted_values);

                    }
                   if(is_array($user)){
 $this->session->set_flashdata('success_message',"User has been succesfully registered and an email has been sent to him.");

  
                    redirect('/admin/customer/index');
               }
            } 
      }else{
          $this->load->view('admin/header', $data);
          $this->load->view('admin/sidebar',$data);
          $this->load->view('admin/configuration/customer_sign_up');
          $this->load->view('admin/footer', $data);
        }
    }else{

         $this->load->view('header');
          $this->load->view('login/login'); 
          $this->load->view('footer');
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
          $errortext = "Please enter valid expiry date.";
    if($expiry_year >= $current_year ){
        //$this->form_validation->set_message('card_number_check', "");
        if($current_year < $expiry_month ){
              $errortext = "Please enter valid expiry month";
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
 * Admin interface for edit password form for the customers 
 */
public function customer_reset_password_by_admin(){
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

    $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/customer/resetpassword',$data);
    $this->load->view('admin/footer', $data);
    

    }
  else{
    $this->load->view('header');
          $this->load->view('admin/customer/list'); 
          $this->load->view('footer');
    }

    
  }


  /**
   * Form submit from the above password edit form
   */
   public function customer_updatepassword_by_admin()
  {
    
    $data =  $this->data;

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
    $this->load->view('admin/customer/resetpassword',$data);
    $this->load->view('admin/footer', $data);



                        
                }
                 else
                {
                   $bda_updatepassword_by_admin= $this->Users_model-> bda_updatepassword_by_admin($data);

                  
         $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');

        $this->session->set_flashdata('success_message',"Password has been updated successfully.");
                
  

        if($data['support_role_id'] == 2){
        redirect("admin/customer/index", $data);
      }
    $this->load->view('admin/footer', $data);
                }
    }
  }



}