<?php

/**
 * Controller Responsible for listing of team members in customer interface_exists(interface_name)
 * @package Controllers
 * @subpackage General
 */

class Team extends MY_Controller {

  private $data = array(); 
	/**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
       
       
      $this->data = $this->user_data;
    
$this->data['switch_from']=$this->session->userdata('switch_from');

        if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }


        if($this->data['company'] == NULL && $this->data['company'] !== TRUE){
        
          redirect('/');

        }
    }


    /**
     *  Listing of Active Team members
     *
     */
    public function active($reset = NULL){

      
        $data =  $this->data;

        if($data['company'] == TRUE){
      
        $user = $this->Users_model->fetchdata($data['user_id']);


        $data['first_name'] = $user[0]->first_name;
        $data['last_name'] = $user[0]->last_name;
        $data['plan_name'] = $this->Users_model->fetch_plan_name($user[0]->plan_id);
         $data['created'] = $user[0]->created;
         $data['email'] = $user[0]->username;

        $user_status = $this->Status_model->fetch_status_by_id($user[0]->status_id);
        $data['status_name'] = $user_status[0]->name;

     $users = $this->Users_model->get_members_ids($data['user_id']);
     $user_id= $data['user_id'];


      $data['count_tasks']= $this->Task_model->count_task($user_id,NULL,NULL,NULL,NULL);



    

  $data['status'] = $this->Status_model->fetch_status_by_type('general'
              );
 

            $search_array = $this->input->post();
             if(empty($search_array)) {
  $search_array = $this->session->userdata('team_active_search'); 

}
else{
  $this->session->set_userdata('team_active_search',$search_array);
}
  if((strpos($_SERVER['HTTP_REFERER'], base_url()."team/active") === false) || (isset($reset) && $reset == "reset")){

        $this->session->unset_userdata('team_active_search');
        $search_array = false;
    }

            $data['search_array']  = $search_array;
           
          $data['total_team_members'] = $this->Users_model->fetch_members($data['user_id'], 'active');

 $this->load->library('pagination');
    $config['base_url'] = base_url().'team/active';
        $data['count_members']=  count($data['total_team_members']); 
     
        $config['total_rows'] = $data['count_members'];
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
        // $config['full_tag_open'] = '<ul class="pagination">';
        // $config['full_tag_close'] = '</ul>';
        // $config['num_tag_open'] = '<li class="page-item">';
        // $config['num_tag_close'] = '</li>';
        // $config['cur_tag_open'] = '<li class="active"><a>';
        // $config['cur_tag_close'] = '</a></  li>';
        //$data["links"] = $this->pagination->create_links();

        //limit end
        $page = $this->uri->segment(3);

        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        } 
  
        $this->pagination->initialize($config);

      

    

        $data['tasks'] = $this->Task_model->get_task(NULL,NULL,NULL,NULL,$user_id,NULL,NULL,'admin',NULL,'parent_tasks');

          $data['no_of_connections_total'] = array();
        $data['logged_hours_total'] = array();
        $data['logged_min_total'] = array();
        foreach($data['tasks'] as $task){


            $data['no_of_connections_total'][] = $task->no_of_connections;
            $data['logged_hours_total'][$task->id] = $this->Task_model->workLogs($task->id);

       
            
        }
    

        $grand_total_hour = 0;
        $grand_total_min = 0;
  
        foreach($data['logged_hours_total'] as $task_id => $logged_hours){
      
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





          $data['team_members'] = $this->Users_model->fetch_members($data['user_id'], 'active',$search_array, $config['per_page'],$limit_end);
// echo "<pre>";
// print_r($data);
// exit();

          $this->load->view('header', $data);
          $this->load->view('sidebar');
          $this->load->view('team/members',$data);
          $this->load->view('footer', $data);
      }
  }

 /**
     *  Listing of Inactive Team members
     *
     */
    public function in_active($reset = NULL){

      
        $data =  $this->data;

        if($data['company'] == TRUE){
      
        $user = $this->Users_model->fetchdata($data['user_id']);

        $data['first_name'] = $user[0]->first_name;
        $data['last_name'] = $user[0]->last_name;
        $data['plan_name'] = $this->Users_model->fetch_plan_name($user[0]->plan_id);
         $data['created'] = $user[0]->created;
         $data['email'] = $user[0]->username;

        $user_status = $this->Status_model->fetch_status_by_id($user[0]->status_id);
        $data['status_name'] = $user_status[0]->name;

 // echo "<pre>";
 //  print_r($user );
 //  exit();


     $users = $this->Users_model->get_members_ids($data['user_id']);
     $user_id = $data['user_id'];


      $data['count_tasks']= $this->Task_model->count_task($user_id,NULL,NULL,NULL,NULL);



    

  $data['status'] = $this->Status_model->fetch_status_by_type('general'
              );
 
            $search_array = $this->input->post();
             if(empty($search_array)) {
  $search_array = $this->session->userdata('team_inactive_search'); 

}
else{
  $this->session->set_userdata('team_inactive_search',$search_array);
}
  if((strpos($_SERVER['HTTP_REFERER'], base_url()."team/in_active") === false) || (isset($reset) && $reset == "reset")){

        $this->session->unset_userdata('team_inactive_search');
        $search_array = false;
    }

            $data['search_array']  = $search_array;
           
          $data['total_team_members'] = $this->Users_model->fetch_members($data['user_id'], 'inactive');

 $this->load->library('pagination');
    $config['base_url'] = base_url().'team/in_active';
        $data['count_members']=  count($data['total_team_members']); 
     
        $config['total_rows'] = $data['count_members'];
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
        // $config['full_tag_open'] = '<ul class="pagination">';
        // $config['full_tag_close'] = '</ul>';
        // $config['num_tag_open'] = '<li class="page-item">';
        // $config['num_tag_close'] = '</li>';
        // $config['cur_tag_open'] = '<li class="active"><a>';
        // $config['cur_tag_close'] = '</a></  li>';
        //$data["links"] = $this->pagination->create_links();

        //limit end
        $page = $this->uri->segment(3);

        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        } 
        //$order_type = 'Asc';  
        //make the data type var avaible to our view
        //$data['order_type_selected'] = $order_type;
        $this->pagination->initialize($config);


          $data['tasks'] = $this->Task_model->get_task(NULL,NULL,NULL,NULL,$user_id,NULL,NULL,'admin',NULL,'parent_tasks');

        $data['no_of_connections_total'] = array();
        $data['logged_hours_total'] = array();
        $data['logged_min_total'] = array();


        foreach($data['tasks'] as $task){


            $data['no_of_connections_total'][] = $task->no_of_connections;
            $data['logged_hours_total'][$task->id] = $this->Task_model->workLogs($task->id);

       
            
        }
    

        $grand_total_hour = 0;
        $grand_total_min = 0;
  
        foreach($data['logged_hours_total'] as $task_id => $logged_hours){

      
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


          $data['team_members'] = $this->Users_model->fetch_members($data['user_id'], 'inactive',$search_array, $config['per_page'],$limit_end);


          $this->load->view('header', $data);
          $this->load->view('sidebar');
          $this->load->view('team/members',$data);
          $this->load->view('footer', $data);
  }

}

  /**
   * Function to create the team members from customer interfaces
   * On Successfull Creation sending an email
   */
  public function create(){

   
    if ($this->input->server('REQUEST_METHOD') === 'POST'){
      $submitted_values = $this->input->post();
      $parent_id = $this->input->post('parent_id');
      $parent_detail = $this->Users_model->fetchdata($parent_id);
      $plan_id = $parent_detail[0]->plan_id;
      $bda_id = $parent_detail[0]->bda_id;

       $plan_details = $this->Plan_model->get_plan_name_and_amount_hour($plan_id, $parent_id);
      // echo "<pre>";
      // print_r($parent_detail);
      // exit();

      $save_team_member = $this->Users_model->create_team_member($submitted_values, $plan_id,$bda_id);
      if($save_team_member){
         $this->session->set_flashdata('success_message','Team Member has been created.');
          
        /*email sending*/
        $subject = "Team Member Email Notification";
        $message ="Hi " . $submitted_values['first_name']." ".$submitted_values['last_name'].",<br><br>";
        $message .="You have been added as a team member under ".$parent_detail[0]->first_name." ".$parent_detail[0]->last_name.". <br><br>" ;

       $message .="Your Registered Plan is: ". "<br>"; 

      $message .= "<b>Plan Type : </b>" .$plan_details[0]->name. "<br>"; 
      $message .= "<b>Total Hours per Month: </b>" . $submitted_values['monthly_usage']. "<br>"; 
      $message .= "<b>User Id: </b>" . $submitted_values['email']. "<br>"; 
      $message .= "<b>Password: </b>" . $submitted_values['password'] . "<br><br>"; 
      $message .="You will hear from your Business Development Assistant to get you on boarded.";
      $data  = array();
      $data['email'] =  $submitted_values['email'];
      $cc = $this->Users_model->admin_emails();

      $this->Users_model->send_mail($data, $message, $subject, $cc);
      }
    }

      redirect('/team/active');
    
  }

  /**
   * Team member edit form
   */
  public function edit($id){
    $data = $this->data;
    
    $user_id = $id;
 

    

  
      $user_details = $this->Users_model->fetchdata($user_id);

    $data['user_details'] = $user_details;
          $data['error_class'] = 'form-group has-feedback has-error';
      $data['error_less_class'] = "form-group";$data['plan_name'] = $this->Users_model->fetch_plan_name($user_details[0]->plan_id);
    
      

       
 
      $this->load->view('header', $data);
      $this->load->view('sidebar');
      $this->load->view('team/edit',$data);
      $this->load->view('footer', $data);


  }


/**
   * Team member edit form action
   */
  public function update($id){

    $data = $this->data;
if($this->input->server('REQUEST_METHOD') === 'POST'){
    
        $user_details = array();
        $obj = new Stdclass();
        $obj->id = $this->input->post('id');
        $obj->first_name = $this->input->post('first_name');
        $obj->last_name = $this->input->post('last_name');
        $obj->username = $this->input->post('email');
        $obj->phone_number = $this->input->post('phone_number');
        $obj->address = $this->input->post('street_name');
        $obj->city = $this->input->post('city');
        $obj->state = $this->input->post('state'); 
        $obj->zip_code = $this->input->post('zip_code');
        $obj->monthly_usage = $this->input->post('monthly_usage');
        $obj->notification_hour = $this->input->post('notification_hour');
        $obj->plan_id = $this->input->post('plan_id');
        $user_details[] = $obj;
    $data['user_details'] = $user_details;
      $data['error_class'] = 'form-group has-feedback has-error';
      $data['error_less_class'] = "form-group";
      $data['plan_name'] = $this->Users_model->fetch_plan_name($user_details[0]->plan_id);
$this->load->library('form_validation');
    $this->form_validation->set_rules('first_name', 'First Name', 'required');
    $this->form_validation->set_rules('last_name', 'Last Name', 'required');

    $this->form_validation->set_rules('email', 'Email', 'required|valid_email', array('valid_email' => 'Provide a valid Email Address.'));



    $this->form_validation->set_rules('phone_number', 'Phone Number', 'required',
    array(
      'required'      => 'The %s is required.'
      
    ));

    $this->form_validation->set_rules('street_name', 'Street Address', 'required');
    $this->form_validation->set_rules('city', 'City', 'required');

    $this->form_validation->set_rules('state', 'State', 'required');
    $this->form_validation->set_rules('zip_code', 'Zip', 'required');

    $this->form_validation->set_rules('monthly_usage', 'Monthly Usage', 'required|numeric');
    $this->form_validation->set_rules('notification_hour', 'Notification Hour', 'required|numeric');
        if ($this->form_validation->run() == FALSE)
                  {

                   
$this->load->view('header', $data);
      $this->load->view('sidebar');
      $this->load->view('team/edit',$data);
      $this->load->view('footer', $data);
                  }
                  else{


                    $this->session->set_flashdata('success_message',"The Team member has been updated.");
    
                 $this->Users_model->update_team_member($user_details);
                 redirect('/team/active');

                  }
      }

  }

  /**
   * Deleting the team member from customer interface
   */
  public function delete_team(){



    $user_id = $this->input->post('delete');

    $parent_id = $this->data['user_id'];
    
    if(!empty($this->input->post('delete')))
   {
    if(isset($user_id) && !empty($user_id)){

       $delete_status =  $this->Users_model->delete_user_related_tasks($user_id, $parent_id);
$this->session->set_flashdata('success_message',"The Team member has been deleted.");
    }
  }
  else{
     $this->session->set_flashdata('warning_message',"Please Select a User.");

  }

        redirect($this->input->post('redirect_url'));
  }


  /**
   * Team member Status Change
   */
  public function update_status(){


    $data = $this->data;

    if(!empty($this->input->post())){

        $user_id = $this->input->post('user_id');
        $current_status_id = $this->input->post('current_status_id');
        $new_status_id = $this->input->post('status_id_selected');
         $email_id = $this->input->post('email_id_in_popup');


        if($current_status_id !== $new_status_id){

           $this->Users_model->update_team_member_status($user_id,$current_status_id, $new_status_id,$email_id);


          $this->session->set_flashdata('success_message',"User status has been updated.");  

        }
        else{

          $this->session->set_flashdata('error_message',"You haven't updated the user status so no action will be taken.");  
        }

      
    }


    redirect('/team/active');
}


  /**
   * Team member Validation runs while creating the team member
   * Runs as an ajax calls
   */
  public function team_validate()
    {

    $this->load->library('form_validation');
    $this->form_validation->set_rules('first_name', 'First Name', 'required');
    $this->form_validation->set_rules('last_name', 'Last Name', 'required');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[ss_users.username]', array('valid_email' => 'Provide a valid Email Address','is_unique' => 'This email id is already in use.'));

    $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
    $this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'trim|required|matches[password]');

    $this->form_validation->set_rules('phone_number', 'Phone Number', 'required',
    array(
      'required'      => 'The %s is required.'
    ));

    $this->form_validation->set_rules('street_address', 'Street Address', 'required');
    $this->form_validation->set_rules('city', 'City', 'required');

    $this->form_validation->set_rules('state', 'State', 'required');
    $this->form_validation->set_rules('zip', 'Zip', 'required');

    $this->form_validation->set_rules('monthly_usage', 'Monthly Usage', 'required|numeric');
    $this->form_validation->set_rules('notification_hour', 'Notification Hour', 'required|numeric');




      if ($this->form_validation->run() === FALSE) {

      $data = array(
          'error_message_first_name' => form_error('first_name'),
          'error_message_last_name' => form_error('last_name'),
          'error_message_email' => form_error('email'),
          'error_message_password' => form_error('password'),
          'error_message_confirm_password' => form_error('confirm_password'),
          'error_message_phone_number' => form_error('phone_number'),
          'error_message_city' => form_error('city'),
          'error_message_state' => form_error('state'),
          'error_message_zip' => form_error('zip'),
          'error_message_monthly_usage' => form_error('monthly_usage'),
          'error_message_notification_hour' => form_error('notification_hour'),
          'error_message_street_address' => form_error('street_address')
        );
         echo json_encode($data);
        }

        else{
              echo "Success";
        }
}

}