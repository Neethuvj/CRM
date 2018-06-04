<?php


/**
 * 
 * @package Controllers
 * @subpackage General
 */

class Task extends MY_Controller {

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
    }


   
   public function index()
   {
     $data = $this->data;
  if($data['role_id'] == 4){
    $this->task_list_for_analyst();
  }
  if($data['role_id'] == 2 || $data['role_id'] == 9){

$this->task_list_for_customer();
  }
   	
   }


   private function task_list_for_customer(){
 $data = $this->data;
      $data['tasklist'] = $this->Task_model->fetchtask();
   // $data['first_letter'] = $this->session->userdata('first_letter');
    //var_dump($task_data[0]->name);
    //$data['name'] = $task_data[0]->name;
    $this->load->view('header', $data);
    $this->load->view('sidebar');
    $this->load->view('task/list',$data);
    $this->load->view('footer', $data);
   }

   private function task_list_for_analyst(){
     $data = $this->data;

$conditions = array();

if(!empty($this->input->post())){
    if(!empty($this->input->post('search_type'))){
     $conditions['search_type'] = $this->input->post('search_type');
    }

   if(!empty($this->input->post('search_from_date'))){
     $conditions['search_from_date'] = date("Y-m-d H:i:s ", strtotime($this->input->post('search_from_date') . " 00:00:00"));
    }
      if(!empty($this->input->post('search_to_date'))){
     $conditions['search_to_date'] = date("Y-m-d H:i:s ", strtotime($this->input->post('search_to_date') . " 23:59:59"));
    }
     if(!empty($this->input->post('search_target_name'))){
     $conditions['search_target_name'] = $this->input->post('search_target_name');
    }

}

    $analyst_id = $this->session->userdata('user_id');
    $customer_status_id = NULL;

    $analyst_assigned_status = $this->Status_model->fetch_status("Assigned", "AnalystTask");
    $analyst_inprogress_status = $this->Status_model->fetch_status("In Progress", "AnalystTask");
    $analyst_completed_status = $this->Status_model->fetch_status("Completed", "AnalystTask");
    
    $analyst_assigned_status_id = $analyst_assigned_status[0]->id;
    $analyst_assigned_tasks= $this->Task_model->get_task_by_analyst($analyst_id, NULL,NULL,$customer_status_id, $analyst_assigned_status_id,$conditions,"Task Board");
  



    $analyst_inprogress_status_id = $analyst_inprogress_status[0]->id;
    $analyst_inprogress_tasks= $this->Task_model->get_task_by_analyst($analyst_id, NULL,NULL,$customer_status_id, $analyst_inprogress_status_id,$conditions,"Task Board");




 
    $analyst_completed_status_id = $analyst_completed_status[0]->id;
    $analyst_completed_tasks= $this->Task_model->get_task_by_analyst($analyst_id, NULL,NULL,$customer_status_id, $analyst_completed_status_id,$conditions,"Task Board");

//     echo "<pre>";
// print_r($analyst_completed_tasks);
// exit();


    $target_names = array();

    foreach($analyst_assigned_tasks as $analyst_assigned_task){
      $target_names[] = trim($analyst_assigned_task->target_name);
    }
    foreach($analyst_inprogress_tasks as $analyst_inprogress_task){
      $target_names[] = trim($analyst_inprogress_task->target_name);
    }
    foreach($analyst_completed_tasks as $analyst_completed_task){
      $target_names[] = trim($analyst_completed_task->target_name);
    }

    
    $unique_target_names = array_unique($target_names);


    $data['conditions'] = $conditions;
    $data['target_names'] = $unique_target_names;
    $data['analyst_assigned_status_id'] = $analyst_assigned_status_id;
    $data['analyst_inprogress_status_id'] = $analyst_inprogress_status_id;
    $data['analyst_completed_status_id'] = $analyst_completed_status_id;
    $data['assigned_tasks'] = $analyst_assigned_tasks;
    $data['inprogress_tasks'] = $analyst_inprogress_tasks;
    $data['completed_tasks'] = $analyst_completed_tasks;
    /*Get all assigned task for that user*/
    $this->load->view('header', $data);
    $this->load->view('sidebar');
    $this->load->view('task/dashboard',$data);
    $this->load->view('footer', $data);

   }

   

    

   public function view($id){
    $data = $this->data;
    $user_id = $this->session->userdata('user_id');
    $role_id = $this->session->userdata('role_id');
    $task = $this->Task_model->get_task_by_id($id);

    $status = $this->Status_model->fetch_status_by_id($task[0]->status_id);
    $userattach = $this->Task_model->getAttachments($id,1);
      $analystattach = $this->Task_model->getAttachments($id,0);
    $data['links'] = $this->Task_model->getLinks($id);
    $data['task_status'] = $task[0]->status_id;


  //   $user_details = $this->Users_model->fetchdata($user_id);

  //   if($role_id == 3){

  //   $user_id = $user_details[0]->bda_id;
  // }
       


 $task_created_user = $this->Users_model->fetchdata($task[0]->user_id);
    $data['task_created_user'] = $task_created_user;
      $data['userattach'] = $userattach;
      $data['analystattach'] = $analystattach;
  //$data['first_letter'] = $this->session->userdata('first_letter');
        $data['status'] = $status[0]->name;
        // if(isset($task) && !empty($task)){
        $data['task'] = $task[0];
        // print_r($data['task']);

    $this->load->view('header', $data);
    $this->load->view('sidebar');
    $this->load->view('task/view',$data);
    $this->load->view('footer', $data);
        // }
        // else{
        //    $this->session->set_flashdata('success_message',"Requested task is not found.");
        //    redirect('user/dashboard');

        // }
   }

   public function add()
   {
      
   $data = $this->data;

   $user_id=  $this->session->userdata('user_id');

  $status= 1;
$data['get_customers']=$this->Users_model->get_customers_for_bda($user_id,$status);


$data['role_id'] =  $this->session->userdata('role_id');


     // $data['first_letter'] = $this->session->userdata('first_letter');
    $data['error_class'] = 'form-group has-feedback has-error';
    $data['error_less_class'] = "form-group";
   	$data['task_type'] = $this->uri->segment(3);
   	$user_id = $this->session->userdata('user_id');
   	//$data['name'] = $this->session->userdata('name');
    $data['first_letter'] = $this->session->userdata('first_letter');
   	$data['tasklist'] = $this->Task_model->fetchtask();
    $data['target_name'] = '';
    $data['target_name'] = '';
    $data['present_company'] = '';
    $data['previous_company'] = '';
    $data['email_address'] = '';
    $data['home_address'] = '';
    $data['meeting_date_time'] = '';
    $data['comments_additional_info'] = '';


     $tid = $this->uri->segment(3);
     $data['addtask'] = $this->Task_model->get_task_by_id($tid, '', '');

     

    foreach($data['tasklist'] as $task){
        
      if($task->id  == $data['task_type']){
        $data['title'] = $task->name;
      }
//       else{
// $data['title'] = "";
//       }
    }

      foreach($data['addtask'] as $tasks){

      $data['task_id'] = $tasks->id;
      $data['user_id'] = $tasks->user_id;
      $data['analyst_id'] = $tasks->analysist_id;

    }




   	$user_details = $this->Users_model->fetchdata($user_id);

    

  
    

    if($data['role_id'] == 9){

        $team_members =$this->Users_model->fetch_parent($user_id);

        $data['firstname'] = $team_members[0]->first_name;

        $data['lastname'] = $team_members[0]->last_name;

      
    }

     
    
    $data['email_to_notifiy'] = $user_details[0]->username;
		$data['user_id'] = $user_details[0]->id;
		$data['first_name'] = $user_details[0]->first_name;
		$data['last_name'] = $user_details[0]->last_name;
		$data['email'] = $user_details[0]->username;

    $data['bda_id'] =  $user_details[0]->bda_id;





   	$this->load->view('header', $data);
		$this->load->view('sidebar');
		$this->load->view('task/add',$data);
		$this->load->view('footer', $data);
   	
   }


  
   public function insert()
   {

    $data =  $this->data;
    $user_id=  $this->session->userdata('user_id');

        $status= 1;
     $data['get_customers']=$this->Users_model->get_customers_for_bda($user_id,$status);

     if($data['role_id'] == 3){
     $this->form_validation->set_rules('customer', 'Customer', 'required');
    }
    $this->form_validation->set_rules('target_name', 'Target Name', 'required');
     $this->form_validation->set_rules('present_company', 'Present Company', 'required');
     
    
  //$this->form_validation->set_rules('meeting_date_time', 'Meeting Date Time','callback_meeting_date_time_check');
  $this->form_validation->set_rules('meeting_date_time', 'Meeting Date Time','required');

     $title_id = $this->input->post('task_type');
     $data['first_letter'] = $this->session->userdata('first_letter');
     $data['tasklist'] = $this->Task_model->fetchtask();

    /*Task type Name Fetch*/
    foreach($data['tasklist'] as $task){

      if($title_id ==  $task->id){
        $data['title'] = $task->name;
      }
//       else{
// $data['title'] = "";
//       }
    }




     $data['error_class'] = 'form-group has-feedback has-error';
    $data['error_less_class'] = "form-group";
    $data['task_type'] = $this->input->post('task_type');
    $user_id = $this->session->userdata('user_id');
    $data['name'] = $this->session->userdata('name');
    $data['tasklist'] = $this->Task_model->fetchtask();

    $user_details = $this->Users_model->fetchdata($user_id);
    $data['email_address'] = $this->input->post('email_address') ? $this->input->post('email_address') : "";
    $data['user_id'] = $user_details[0]->id;

    $data['first_name'] = $user_details[0]->first_name;
    $data['last_name'] = $user_details[0]->last_name;

    if($data['role_id'] == 9){

        $team_members =$this->Users_model->fetch_parent($user_id);

        $data['firstname'] = $team_members[0]->first_name;

        $data['lastname'] = $team_members[0]->last_name;

      
    }


      $data['bda_id'] =  $user_details[0]->bda_id;
    $data['target_name'] = $this->input->post('target_name') ? $this->input->post('target_name') : '';
    $data['present_company'] = $this->input->post('present_company') ? $this->input->post('present_company') : '';
    $data['previous_company'] = $this->input->post('previous_company') ? $this->input->post('previous_company') : '';
    $data['email_to_notifiy'] = $this->input->post('email_to_notifiy') ? $this->input->post('email_to_notifiy') : '';
    $data['home_address'] = $this->input->post('address') ? $this->input->post('address') : '';
    $data['meeting_date_time'] = $this->input->post('meeting_date_time') ? $this->input->post('meeting_date_time') : '';
    $data['comments_additional_info'] = $this->input->post('additional_info') ? $this->input->post('additional_info') : '';

    
    /*Get First available Analyst*/
    $analyst_order = "analyst_order";

    $analyst_users = $this->Users_model->fetch_users_by_role(4,$user_id,NULL,NULL,$analyst_order);


  
    $current_analyst_order = $analyst_users[0]->analyst_order;

    $analyst_id = $analyst_users[0]->id;
    $all_order = array();
    /*Fetch All analyst order values and get the last value of the order and get the updated value by adding 1*/
    foreach($analyst_users as $analyst_user){
      

        $all_order[] = $analyst_user->analyst_order;
    }
    $updated_analyst_order = end($all_order) + 1;




    
		$this->load->model("Status_model");
    /*When Customer Adds a Task it should always be in progress status which is fetched from Status Model*/
    $status = $this->Status_model->fetch_status("In Progress", "Task");
    $status_id = $status[0]->id;
    /*Update Analyst Status Id as Assinged*/

        $analyst_status = $this->Status_model->fetch_status("Assigned", "AnalystTask");
    $analyst_status_id = $analyst_status[0]->id;


 
    if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
          //$data['target_name'] = ($this->input->post('target_name') != '') ? $this->input->post('target_name') : "";
          if ($this->form_validation->run() == FALSE)
                {

                  
                       $this->load->view('header', $data);
    $this->load->view('sidebar');
    $this->load->view('task/add',$data);
    $this->load->view('footer', $data);
                }
                else{
	




  if(!empty($this->input->post("meeting_date_time")) && ($this->input->post("meeting_date_time"))){
    $date = date("Y-m-d H:i:s", strtotime($this->input->post('meeting_date_time')));

  }
  else{
    $date = NULL;
  }

  $data['role_id'] =  $this->session->userdata('role_id');

  /*Save Tasks if current user is a customer or team member*/

  if ($data['role_id'] == 2 || $data['role_id'] == 9) {

			$data = array(
					'account_name'=> $this->input->post('acccount_name'),
					'target_name' =>  $this->input->post('target_name'),
					'present_company' => $this->input->post('present_company'),
					'previous_company' => $this->input->post('previous_company'),
					'email' => $this->input->post('email_address'),
					'home_address' => $this->input->post('address'),
					'meeting_date_time' => $date,
					'comments_additional_info' => $this->input->post('additional_info'),
					'task_type_id' => $this->input->post('task_type'),
					'email_to_notifiy' => $this->input->post('email_to_notifiy'),
					'user_id' => $this->session->userdata('user_id'),
					'bda_id' => $this->input->post('bda_id'),
					'analysist_id' => $analyst_id,
					'status_id' => $status_id,
          'analyst_status_id' => $analyst_status_id,
					'created' => date("Y-m-d H:i:s"),
					'updated' => date("Y-m-d H:i:s"),
				);

  }


    /*Save Tasks if current user is a BDA Take the Customer from the select box and save the task in the account of him*/

  elseif ($data['role_id'] == 3) {

     $customer_id=$this->input->post('customer');
       $customer_id=$this->input->post('customer');
      $customer_name=$this->Task_model->get_customer_name($customer_id);
      $first_name=$customer_name[0]->first_name; 
      $last_name=$customer_name[0]->last_name; 
      $customer=$customer_name[0]->first_name." ".$customer_name[0]->last_name;

      $data = array(
          'account_name'=> $customer,
          'target_name' =>  $this->input->post('target_name'),
          'present_company' => $this->input->post('present_company'),
          'previous_company' => $this->input->post('previous_company'),
          'email' => $this->input->post('email_address'),
          'home_address' => $this->input->post('address'),
          'meeting_date_time' => $date,
          'comments_additional_info' => $this->input->post('additional_info'),
          'task_type_id' => $this->input->post('task_type'),
          'email_to_notifiy' => $this->input->post('email_to_notifiy'),
          'user_id' => $customer_id ,
          'bda_id' => $this->session->userdata('user_id'),
          'analysist_id' => $analyst_id,
          'status_id' => $status_id,
          'analyst_status_id' => $analyst_status_id,
          'created' => date("Y-m-d H:i:s"),
          'updated' => date("Y-m-d H:i:s"),

        );
 
  }


//inserting task details 
  $task_details = $this->Task_model->taskupdate($data);

/*Update the analyst order of the assigned analyst*/
  $updated_order = $this->Users_model->update_analyst_order($analyst_id, $current_analyst_order,$updated_analyst_order);

/*
  1.once the task is created succesfully we are sending two emails
  2.if the customer is assigend to a bda , we need to send email to the concern bda too 
*/
  $subject = 'Task Assignment';
  $user_details = $this->Users_model->fetchdata($data['user_id']);//fetching customer details
  $data['bda_id'] = $user_details[0]->bda_id;//saving bda_id

  if(!empty($data['bda_id'])){

    $bda_details = $this->Users_model->fetchdata($data['bda_id'] );

    $bda_email = $bda_details[0]->username; // saving bda email

  }

  else
  {
    $bda_email = NULL;
  }



  $cc = $this->Task_model->admin_emails();

  $analyst_details = $this->Users_model->fetchdata($data['analysist_id']);//fetching the details of analyst for sending task assign ment email

  $task_type = $this->Task_model->fetch_task_type($data['task_type_id']);

 
   $data['email'] =  $analyst_details[0]->username;
   $analyst_name = $analyst_details[0]->first_name;

/*checking whether date of meeting is present or not */
 if(!empty($data['meeting_date_time'])){

  $meeting_date = date('m/d/Y h:i A',strtotime($data['meeting_date_time']));
 }
 else{

  $meeting_date = "Not mentioned";
 }


  $message = "Hi ".$analyst_name.",<br><br>
  
   The ".$task_type[0]->task_name." from ".$data['account_name']." has been assigned.<br> Date of meeting: ".$meeting_date."
 ";
   
  $cc = $this->Users_model->admin_emails();
 
 /*appending bda email in cc array*/
   if(empty($bda_email)){
     $bda_email = NULL;
    
   }
   else{

     $cc[] = $bda_email;
   }


   $this->Users_model->send_mail($data,$message,$subject,$cc,NULL,NULL);//sening task assign ment email to bda

  $this->session->set_flashdata('success_message',"Task has been created successfully.");
/*

Fetching details for sending successfull task creation mail to the customer ans bda if applicable.
*/

    $user_details = $this->Users_model->fetchdata($data['user_id']);
    
    $data['bda_id'] = $user_details[0]->bda_id;

     if(!empty($data['bda_id'])){

    $user_details = $this->Users_model->fetchdata($data['bda_id'] );

    $data['email'] = $user_details[0]->username;
  }

  else{

    $data['email'] = NULL;

  }

   
   $subject = 'New Task Creation';
   
   $account_name = $data['account_name'];

   $targetname = $data['target_name'];

   $message = "A new task on ".$targetname." has been created by ".$account_name .".";
   
   $this->Users_model->send_mail($data,$message,$subject);

 
  
      if($user_details){

if(!empty($_FILES)){
        foreach($_FILES['userFiles']['name'] as $user_files){
            if(!empty($user_files)){
              $empty_file = FALSE;
            }
        }
      }


        if(isset($empty_file) && $empty_file == FALSE){
         
            $filesCount = count($_FILES['userFiles']['name']);
       

            for($i = 0; $i < $filesCount; $i++){
              
                $_FILES['userFile']['name'] = $_FILES['userFiles']['name'][$i];
                $_FILES['userFile']['type'] = $_FILES['userFiles']['type'][$i];
                $_FILES['userFile']['tmp_name'] = $_FILES['userFiles']['tmp_name'][$i];
                $_FILES['userFile']['error'] = $_FILES['userFiles']['error'][$i];
                $_FILES['userFile']['size'] = $_FILES['userFiles']['size'][$i];

                $uploadPath = 'assets/files/';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = 'txt|pdf|xls|doc|docx|xlsx|ods|xml|text/csv|csv';
                $config['max_size'] = '40000';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
           
                if($this->upload->do_upload('userFile')){
            
                    $fileData = $this->upload->data();
                    
                    $uploadData[$i]['user_id'] = $data['user_id'];
                    $uploadData[$i]['task_id'] = $task_details;
                    $uploadData[$i]['path'] = $fileData['file_name'];
                    $uploadData[$i]['created'] = date("Y-m-d H:i:s");
                    $uploadData[$i]['modified'] = date("Y-m-d H:i:s");
                }
                else{

                   $check_error = TRUE;
                   $this->load->library('form_validation');
                   $this->form_validation->set_message('file',$this->upload->display_errors());
                     $this->session->set_flashdata('file_error_message',$this->upload->display_errors());
                }
                
            }


 if(!empty($uploadData)){
                //Insert file information into the database
               
                $insert = $this->Task_model->insert_files($uploadData);
                
                $statusMsg = $insert?'Files uploaded successfully.':'Some problem occurred, please try again.';
                $this->session->set_flashdata('statusMsg',$statusMsg);
                 
              }
   
           
        }
       


          }   
       

   // $data['attachments'][] = $this->Task_model->getAttachments($task_details,$data['user_id']);
    // $data['attachments'][] = $this->Task_model->getAttachments($task_id,$task_details[0]->analyst_id);

      redirect('task/inprogress');
  

    }
			//$this->db->insert('ss_', $assistant);
		}
   }



  public function inprogress_bda($reset = NULL){

    $data = $this->data;


    $user_id = $this->session->userdata('user_id');
    $data['name'] = $this->session->userdata('name');

    $data['first_letter'] = $this->session->userdata('first_letter');
  $data['role_id'] =  $this->session->userdata('role_id');

 

  $data['get_all_customers'] = $this->Users_model->fetch_customers_for_bda(2,$user_id);


$data['get_all_team_members']=$this->Users_model->fetch_users_by_role(9,NULL,NULL,NULL);


$data['get_all_analyst']=$this->Users_model->fetch_users_by_role(4,$user_id,NULL,NULL);

$data['get_target_names']=$this->Task_model->get_target_name(NULL);


$data['tasklists'] = $this->Task_model->fetchtask();

$search_array = $this->input->post();


 if(empty($search_array)) {
  $search_array = $this->session->userdata('task_inprogress_bda_search');
   
      if(isset($search_array['csv_export'])){
    $search_array['csv_export'] = NULL;
        }

}
else{
  $this->session->set_userdata('task_inprogress_bda_search',$search_array);
}
//if(isset($_SERVER['HTTP_REFERER'])){
  if((strpos($_SERVER['HTTP_REFERER'], base_url()."task/inprogress") === false) || (isset($reset) && $reset == "reset")){

        $this->session->unset_userdata('task_inprogress_bda_search');
        $search_array = false;
    }
//}


  $data['type_of_task'] = $this->uri->segment(2);
    //$data['first_letter'] = $this->session->userdata('first_letter');
    $user_details = $this->Users_model->fetchdata($user_id);

 $search_target = $this->input->post('search_target');
//$search_array = $this->input->post();


    $data['email'] = $user_details[0]->username;
    $data['id'] = $user_details[0]->id;
    $data['first_name'] = $user_details[0]->first_name;
    $data['last_name'] = $user_details[0]->last_name;
    $data['email'] = $user_details[0]->username;
        $status = $this->Status_model->fetch_status("In Progress", "Task");
    $status_id = $status[0]->id;
    //$config['per_page'] = 5;
    $this->load->library('pagination');


   


        $config['base_url'] = base_url().'task/inprogress/';
        $data['count_tasks']= $this->Task_model->count_task($user_id,$status_id,$search_array, NULL,"bda");



      
        $config['total_rows'] = $data['count_tasks'];
        $config["per_page"] = 20;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);
        
        $config['use_page_numbers'] = TRUE;
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
        $page = $this->uri->segment(3);

        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        } 
        $order_type = 'Asc';  
        //make the data type var avaible to our view
        $data['order_type_selected'] = $order_type;

       if ($this->input->server('REQUEST_METHOD') === 'POST'){
         
          if($this->input->post('submit') == "Submit"){
             $search_target = $this->input->post('search_target');
          $data['search_target'] = $search_target;


             $search_task_type = $this->input->post('search_task_type');
          $data['search_task_type'] = $search_task_type;


           $search_customer = $this->input->post('search_customer');
          $data['search_customer'] = $search_customer;


            $search_team_member = $this->input->post('search_team_member');
          $data['search_team_member'] = $search_team_member;

          $search_meeting_date_time_start= $this->input->post('meeting_start_date');
          $data['meeting_start_date'] = $search_meeting_date_time_start;

        $search_meeting_date_time_to = $this->input->post('meeting_to_date');
          $data['meeting_to_date'] = $search_meeting_date_time_to;
          }
          else{
            $search_target =NULL;
             $data['search_target'] = "";


              $search_task_type =NULL;
             $data['search_task_type'] = "";

             $search_customer =NULL;
             $data['search_customer'] = "";


             $search_team_member =NULL;
             $data['search_team_member'] = "";

             $search_bda =NULL;
             $data['search_bda'] = "";

           $search_meeting_date_time_start =NULL;
             $data['meeting_start_date'] = "";

             $search_meeting_date_time_to =NULL;
             $data['meeting_to_date'] = "";

          } 

        }
        else{
          $search_target = NULL;
          $data['search_target'] = $search_array['search_target'];


             $search_task_type =NULL;
             $data['search_task_type'] = $search_array['search_task_type'];

             $search_customer =NULL;
             $data['search_customer'] =  $search_array['search_customer'];



             $search_team_member =NULL;
             $data['search_team_member'] = $search_array['search_team_member'];

            

             $search_meeting_date_time_start =NULL;
             $data['meeting_start_date'] = $search_array['meeting_start_date'];

            

             $search_meeting_date_time_to =NULL;
             $data['meeting_to_date'] = $search_array['meeting_to_date'];

            
        }
        //$data['count_tasks']= $this->Task_model->count_task($search_target, "admin");

        $owner_id [] = array();
        $member_ids[] =array();

        $owner_id = $this->Users_model->get_customer_id($user_id);

        $member_ids = $this->Users_model->get_member_ids($user_id);

       
       
        $user_ids = array_merge($member_ids, $owner_id);

     

        

        $data['tasks'] = $this->Task_model->get_task('', $order_type, $config['per_page'],$limit_end,$user_ids,$status_id,$search_array, NULL, "bda_list");


        $users = array();

        $team_member_array = array();

        foreach($data['tasks'] as $task)
        {
           $users = $task->user_id;

              $team_member_array = $this->Users_model->fetch_teambers_for_bda($users);
  
              foreach($team_member_array as $team_member){


                if($task->user_id == $team_member->id){

                  $data['team_array'][$task->user_id] =  $team_member->name;
                  break;
                }
                else{

                  $data['team_array'][$task->user_id] = "";
                }

              }


        }


  foreach($data['tasks'] as $task){


$data['work_log'][$task->id] = $this->Task_model->workLogs($task->id);

}

$data['calculated_hours'] = array();
if(!empty($data['work_log'])){
foreach ($data['work_log'] as $task_id => $logged_hours)
{
     $logged_hour = $logged_hours[0]->log_hrs;

      $logged_min = $logged_hours[0]->log_min;



      $calculated_hours = floor($logged_min/60);
      $calculated_min = $logged_min%60;

      $logged_hour +=$calculated_hours;
      $logged_min = $calculated_min;

     $data['calculated_hours'][$task_id]['logged_hrs'] = $logged_hour;
    $data['calculated_hours'][$task_id]['logged_min'] = $logged_min;
 
}
}



        if($data['tasks'] ==''){
          $data['result'] = "No Result Found";
        }
         $this->pagination->initialize($config);
          $this->load->view('header', $data);
    $this->load->view('sidebar');
        $this->load->view('task/progress', $data); 
        $this->load->view('footer', $data);




  } 

  public function inprogress($reset = NULL)
  {

    $data =  $this->data;

  $data['role'] =  $this->session->userdata('role_id');

  if($data['role'] == 2 || $data['role_id'] == 9){

     $this->inprogress_customer($reset);
    }


    else if($data['role'] == 3){

         $this->inprogress_bda($reset);

    }

    else{

     
      print "Functionality is not yet implemented. <a href='/user/logout'>Logout</a>";

   }

   

  }


   public function completed_tasks($reset = NULL)
  {

    $data =  $this->data;

  $data['role'] =  $this->session->userdata('role_id');

  if($data['role'] == 2 || $data['role_id'] == 9){

     $this->completed_customer($reset);
    }

    else if($data['role'] == 3){

         $this->completed_bda($reset);

    }

    else{

     
      print "Functionality is not yet implemented. <a href='/user/logout'>Logout</a>";

   }

   

  }


   private function inprogress_customer($reset = NULL){


    $data = $this->data;

 $data['sidebar_class'] =  " ";  
    $user_id = $this->session->userdata('user_id');
     $role_id = $this->session->userdata('role_id');
    $data['name'] = $this->session->userdata('name');

  $data['role_id'] =  $this->session->userdata('role_id');


    $data['tasklist'] = $this->Task_model->fetchtask();
  $data['type_of_task'] = $this->uri->segment(2);
    //$data['first_letter'] = $this->session->userdata('first_letter');
  $user_details = $this->Users_model->fetchdata($user_id);

  $data['get_members']=$this->Users_model->get_members($user_id);

  $data['get_owner']=$this->Users_model->get_owner($user_id);

$data['get_all_team_members'] = array_merge($data['get_members'], $data['get_owner']) ;

   

  $data['company'] = $this->session->userdata('company');

  $data['plan_id'] = $user_details[0]->plan_id;
   // echo "<pre>";
   //  print_r($data);
   //  exit();

  $search_target = $this->input->post('search_target');

  $search_array = $this->input->post();

 if(empty($search_array)) {
  $search_array = $this->session->userdata('task_inprogress_customer_search'); 

  if(isset($search_array['csv_export'])){
    $search_array['csv_export'] = NULL;
        }

}
else{
  $this->session->set_userdata('task_inprogress_customer_search',$search_array);
}
  if((strpos($_SERVER['HTTP_REFERER'], base_url()."task/inprogress") === false) || (isset($reset) && $reset == "reset")){

        $this->session->unset_userdata('task_inprogress_customer_search');
        $search_array = false;
    }

    $data['email'] = $user_details[0]->username;
    $data['id'] = $user_details[0]->id;
    $data['first_name'] = $user_details[0]->first_name;
    $data['last_name'] = $user_details[0]->last_name;
    $data['email'] = $user_details[0]->username;
        $status = $this->Status_model->fetch_status("In Progress", "Task");
    $status_id = $status[0]->id;
    //$config['per_page'] = 5;
    $this->load->library('pagination');
        $owner_id = $this->Users_model->get_customer_id($user_id);

        $member_ids = $this->Users_model->get_member_ids($user_id);
        $user_ids = array();
        foreach($owner_id as $owner){
          $user_ids[] = $owner->member_id;
        }
        foreach($member_ids as $member_id){
          $user_ids[] = $member_id->member_id;
        }
       
        $data['get_target_names']=$this->Task_model->get_target_name($user_ids);

         // = array_merge($member_ids, $owner_id);

      

        $config['base_url'] = base_url().'task/inprogress/';
        $data['count_tasks']= $this->Task_model->count_task($user_ids,$status_id,$search_array);

        // print_r($data['count_tasks']);
        // exit();

   

        $config['total_rows'] = $data['count_tasks'];
        $config["per_page"] = 20;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);
        
        $config['use_page_numbers'] = TRUE;
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
        $page = $this->uri->segment(3);

        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        } 
        $order_type = 'Asc';  
        //make the data type var avaible to our view
        $data['order_type_selected'] = $order_type;

       if ($this->input->server('REQUEST_METHOD') === 'POST'){
         
          if($this->input->post('submit') == "Submit"){
             $search_target = $this->input->post('search_target');
          $data['search_target'] = $search_target;

                $search_team_member = $this->input->post('search_team_member');
          $data['search_team_member'] = $search_team_member;


        $search_meeting_date_time_start= $this->input->post('created_start_date');
          $data['created_start_date'] = $search_meeting_date_time_start;

        $search_meeting_date_time_to = $this->input->post('created_to_date');
          $data['created_to_date'] = $search_meeting_date_time_to;



          }
          else{
            $search_target =NULL;
             $data['search_target'] = "";

              $search_task_type =NULL;
             $data['search_task_type'] = "";

             $search_customer =NULL;
             $data['search_customer'] = "";

            
             $search_team_member =NULL;
             $data['search_team_member'] = "";

                $search_meeting_date_time_start =NULL;
             $data['created_start_date'] = "";

             $search_meeting_date_time_to =NULL;
             $data['created_to_date'] = "";
          } 

        }
        else{
              $search_target = NULL;
              $data['search_target'] = $search_array['search_target'];

             // $search_task_type =NULL;
             // $data['search_task_type'] = $search_array['search_task_type'];


             // $search_customer =NULL;
             // $data['search_customer'] = $search_array['search_customer'];


              
             $search_team_member =NULL;
             $data['search_team_member'] = $search_array['search_team_member'];

              $search_meeting_date_time_start =NULL;
             $data['created_start_date'] = $search_array['created_start_date'];

             $search_meeting_date_time_to =NULL;
             $data['created_to_date'] =  $search_array['created_to_date'];

        }


        $owner_id [] = array();
        $member_ids[] =array();

        $owner_id = $this->Users_model->get_customer_id($user_id);

        $member_ids = $this->Users_model->get_member_ids($user_id);

       
       
        $user_ids = array_merge($member_ids, $owner_id);

      

    $data['tasks'] = $this->Task_model->get_task('', $order_type, $config['per_page'],$limit_end,$user_ids,$status_id,$search_array);



        $team_member=$this->Users_model->get_team_members($user_id);
        $team_member_array = array();

if(!empty($team_member)){

        foreach($team_member as $key=>$team){

         $team_member_array[$team->member_id] = $team->first_name . " ". $team->last_name;
        }

   }    

          $data['team_array'] = $team_member_array;

         
           foreach($data['tasks'] as $task){


$data['work_log'][$task->id] = $this->Task_model->workLogs($task->id);

}

$data['calculated_hours'] = array();
if(!empty($data['work_log'])){
foreach ($data['work_log'] as $task_id => $logged_hours)
{
     $logged_hour = $logged_hours[0]->log_hrs;

      $logged_min = $logged_hours[0]->log_min;



      $calculated_hours = floor($logged_min/60);
      $calculated_min = $logged_min%60;

      $logged_hour +=$calculated_hours;
      $logged_min = $calculated_min;

     $data['calculated_hours'][$task_id]['logged_hrs'] = $logged_hour;
    $data['calculated_hours'][$task_id]['logged_min'] = $logged_min;
 
}
}


        if($data['tasks'] ==''){
          $data['result'] = "No Result Found.";
        }
          $this->pagination->initialize($config);
          $this->load->view('header', $data);
          $this->load->view('sidebar');
          $this->load->view('task/progress', $data); 
          $this->load->view('footer', $data);
   }



private function completed_bda($reset){

  $data = $this->data;
    $user_id = $this->session->userdata('user_id');
    $data['name'] = $this->session->userdata('name');

    $data['first_letter'] = $this->session->userdata('first_letter');
  $data['role_id'] =  $this->session->userdata('role_id');

 $data['get_all_customers'] = $this->Users_model->fetch_customers_for_bda(2,$user_id);

  $data['get_all_team_members']=$this->Users_model->fetch_users_by_role(9,NULL,NULL,NULL);

$data['get_all_analyst']=$this->Users_model->fetch_users_by_role(4,$user_id,NULL,NULL);

$data['get_target_names']=$this->Task_model->get_target_name(NULL);


$data['tasklists'] = $this->Task_model->fetchtask();

$search_array = $this->input->post();

if(empty($search_array)) {
  $search_array = $this->session->userdata('task_completed_search'); 

    if(isset($search_array['csv_export'])){
    $search_array['csv_export'] = NULL;
        }
}
else{
  $this->session->set_userdata('task_completed_search',$search_array);
}
  if((strpos($_SERVER['HTTP_REFERER'], base_url()."task/completed_tasks") === false) || (isset($reset) && $reset == "reset")){

        $this->session->unset_userdata('task_completed_search');
        $search_array = false;
    }

  $data['type_of_task'] = $this->uri->segment(2);
    //$data['first_letter'] = $this->session->userdata('first_letter');
    $user_details = $this->Users_model->fetchdata($user_id);

    $data['plan_id'] = $user_details[0]->plan_id;

  

    $data['email'] = $user_details[0]->username;
    $data['id'] = $user_details[0]->id;
    $data['first_name'] = $user_details[0]->first_name;
    $data['last_name'] = $user_details[0]->last_name;
    $data['email'] = $user_details[0]->username;
   $status = $this->Status_model->fetch_status("Completed", "Task");
    $status_id = $status[0]->id;
    //$config['per_page'] = 5;
    $this->load->library('pagination');


        $config['base_url'] = base_url().'task/completed_tasks/';
        $data['count_tasks']= $this->Task_model->count_task($user_id,$status_id,$search_array, NULL,"admin");
        $config['total_rows'] = $data['count_tasks'];
        $config["per_page"] = 20;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);
        
        $config['use_page_numbers'] = TRUE;
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
        $page = $this->uri->segment(3);

        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        } 
        $order_type = 'Asc';  
        //make the data type var avaible to our view
        $data['order_type_selected'] = $order_type;

       if ($this->input->server('REQUEST_METHOD') === 'POST'){
         
          if($this->input->post('submit') == "Submit"){
             $search_target = $this->input->post('search_target');
          $data['search_target'] = $search_target;


             $search_task_type = $this->input->post('search_task_type');
          $data['search_task_type'] = $search_task_type;

            $search_team_member = $this->input->post('search_team_member');
          $data['search_team_member'] = $search_team_member;


           $search_customer = $this->input->post('search_customer');
          $data['search_customer'] = $search_customer;

            $search_meeting_date_time_start= $this->input->post('meeting_start_date');
          $data['meeting_start_date'] = $search_meeting_date_time_start;

        $search_meeting_date_time_to = $this->input->post('meeting_to_date');
          $data['meeting_to_date'] = $search_meeting_date_time_to;
          }
          else{
            $search_target =NULL;
             $data['search_target'] = "";


              $search_task_type =NULL;
             $data['search_task_type'] = "";

             $search_customer =NULL;
             $data['search_customer'] = "";


             $search_bda =NULL;
             $data['search_bda'] = "";

                $search_team_member =NULL;
             $data['search_team_member'] = "";

             $search_meeting_date_time_start =NULL;
             $data['meeting_start_date'] = "";

             $search_meeting_date_time_to =NULL;
             $data['meeting_to_date'] = "";


          } 

        }
        else{
          $search_target = NULL;
          $data['search_target'] = $search_array['search_target'];


             $search_task_type =NULL;
             $data['search_task_type'] = $search_array['search_task_type'];

             $search_customer =NULL;
             $data['search_customer'] = $search_array['search_customer'];

             $search_team_member =NULL;
             $data['search_team_member'] = $search_array['search_team_member'];

             $search_meeting_date_time_start =NULL;
             $data['meeting_start_date'] = $search_array['meeting_start_date'];


             $search_meeting_date_time_to =NULL;
             $data['meeting_to_date'] = $search_array['meeting_to_date'];
        }




        

        $owner_id [] = array();
        $member_ids[] =array();

        $owner_id = $this->Users_model->get_customer_id($user_id);

        $member_ids = $this->Users_model->get_member_ids($user_id);

       
       
        $user_ids = array_merge($member_ids, $owner_id);
    



        $data['tasks'] = $this->Task_model->get_task('', $order_type, $config['per_page'],$limit_end,$user_ids,$status_id,$search_array,NULL, "bda_list");


       $users = array();

        $team_member_array = array();

        foreach($data['tasks'] as $task)
        {
            $users = $task->user_id;

              $team_member_array = $this->Users_model->fetch_teambers_for_bda($users);
  
              foreach($team_member_array as $team_member){


                if($task->user_id == $team_member->id){

                  $data['team_array'][$task->user_id] =  $team_member->name;
                  break;
                }
                else{

                  $data['team_array'][$task->user_id] = "";
                }

              }



              // echo "<pre>";
              // print_r($data);
              // exit();
        }


  foreach($data['tasks'] as $task){


$data['work_log'][$task->id] = $this->Task_model->workLogs($task->id);

}

$data['calculated_hours'] = array();
if(!empty($data['work_log'])){
foreach ($data['work_log'] as $task_id => $logged_hours)
{
     $logged_hour = $logged_hours[0]->log_hrs;

      $logged_min = $logged_hours[0]->log_min;



      $calculated_hours = floor($logged_min/60);
      $calculated_min = $logged_min%60;

      $logged_hour +=$calculated_hours;
      $logged_min = $calculated_min;

     $data['calculated_hours'][$task_id]['logged_hrs'] = $logged_hour;
    $data['calculated_hours'][$task_id]['logged_min'] = $logged_min;
 
}
}
       

        if($data['tasks'] ==''){
          $data['result'] = "No Result Found.";
        }
         $this->pagination->initialize($config);
          $this->load->view('header', $data);
    $this->load->view('sidebar');
        $this->load->view('task/progress', $data); 
        $this->load->view('footer', $data);





}

public function reports()
{

  $data = $this->data;
  $data['tasklist'] = $this->Task_model->fetchtask();

    $this->load->view('header', $data);
    $this->load->view('sidebar');
    $this->load->view('task/list',$data);
    $this->load->view('footer',$data);

}







   private function completed_customer($reset){

      $data = $this->data;

       $data['sidebar_class'] =  " ";

$data['role_id'] =  $this->session->userdata('role_id');

    $user_id = $this->session->userdata('user_id');
    $data['name'] = $this->session->userdata('name');


    $data['tasklist'] = $this->Task_model->fetchtask();
  $data['type_of_task'] = $this->uri->segment(2);


 //$data['get_all_team_members']=$this->Users_model->get_members($user_id);

  $data['get_members']=$this->Users_model->get_members($user_id);

  $data['get_owner']=$this->Users_model->get_owner($user_id);

$data['get_all_team_members'] = array_merge($data['get_members'], $data['get_owner']) ;

    //$data['first_letter'] = $this->session->userdata('first_letter');
    $user_details = $this->Users_model->fetchdata($user_id);

      $data['plan_id'] = $user_details[0]->plan_id;

 $search_target = $this->input->post('search_target');

    $search_array = $this->input->post();

    // echo "<pre>";
    // print_r($search_array);
    // exit();

    if(empty($search_array)) {
  $search_array = $this->session->userdata('task_inprogress_customer_search'); 

  if(isset($search_array['csv_export'])){
    $search_array['csv_export'] = NULL;
        }

}
else{
  $this->session->set_userdata('task_inprogress_customer_search',$search_array);
}
  if((strpos($_SERVER['HTTP_REFERER'], base_url()."task/completed") === false) || (isset($reset) && $reset == "reset")){

        $this->session->unset_userdata('task_inprogress_customer_search');
        $search_array = false;
    }

    $data['email'] = $user_details[0]->username;
    $data['id'] = $user_details[0]->id;
    $data['first_name'] = $user_details[0]->first_name;
    $data['last_name'] = $user_details[0]->last_name;
    $data['email'] = $user_details[0]->username;
        $status = $this->Status_model->fetch_status("Completed", "Task");
    $status_id = $status[0]->id;
    //$config['per_page'] = 5;
    $this->load->library('pagination');

   
        $config['base_url'] = base_url().'task/completed_tasks/';

            $owner_id = $this->Users_model->get_customer_id($user_id);

        $member_ids = $this->Users_model->get_member_ids($user_id);
        $user_ids = array();
        foreach($owner_id as $owner){
          $user_ids[] = $owner->member_id;
        }
        foreach($member_ids as $member_id){
          $user_ids[] = $member_id->member_id;
        }
       

         $data['get_target_names']=$this->Task_model->get_target_name($user_ids);

        $data['count_tasks']= $this->Task_model->count_task($user_ids,$status_id,$search_array);


        $config['total_rows'] = $data['count_tasks'];
        $config["per_page"] = 20;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice); 
        $config['use_page_numbers'] = TRUE;
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
        $page = $this->uri->segment(3);

        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        } 
        $order_type = 'Asc';  
        //make the data type var avaible to our view
        $data['order_type_selected'] = $order_type;

       if ($this->input->server('REQUEST_METHOD') === 'POST'){
         
          if($this->input->post('submit') == "Submit"){
             $search_target = $this->input->post('search_target');
          $data['search_target'] = $search_target;

           $search_team_member = $this->input->post('search_team_member');
          $data['search_team_member'] = $search_team_member;


        $search_meeting_date_time_start= $this->input->post('created_start_date');
          $data['created_start_date'] = $search_meeting_date_time_start;

        $search_meeting_date_time_to = $this->input->post('created_to_date');
          $data['created_to_date'] = $search_meeting_date_time_to;

          }
          else{
            $search_target =NULL;
             $data['search_target'] = "";


             $search_team_member =NULL;
             $data['search_team_member'] = "";

             $search_meeting_date_time_start =NULL;
             $data['created_start_date'] = "";

             $search_meeting_date_time_to =NULL;
             $data['created_to_date'] = "";
          } 

        }
        else{
          $search_target = NULL;
          $data['search_target'] = $search_array['search_target'];


          $search_team_member =NULL;
          $data['search_team_member'] = $search_array['search_team_member'];

          $search_meeting_date_time_start =NULL;
          $data['created_start_date'] = $search_array['created_start_date'];

          $search_meeting_date_time_to =NULL;
          $data['created_to_date'] =  $search_array['created_to_date'];
        }
        //$data['count_tasks']= $this->Task_model->count_task($search_target, "admin");
        $owner_id [] = array();
        $member_ids[] =array();

        $owner_id = $this->Users_model->get_customer_id($user_id);

        $member_ids = $this->Users_model->get_member_ids($user_id);

       
       
        $user_ids = array_merge($member_ids, $owner_id);

// echo "<pre>";
// print_r($search_array);
// echo "</pre>";

        $data['tasks'] = $this->Task_model->get_task('', $order_type, $config['per_page'],$limit_end,$user_ids,$status_id,$search_array);

        
        $team_member=$this->Users_model->get_team_members($user_id);
        $team_member_array = array();

if(!empty($team_member)){

        foreach($team_member as $key=>$team){

         $team_member_array[$team->member_id] = $team->first_name . " ". $team->last_name;
        }

   }    


      foreach($data['tasks'] as $task){


$data['work_log'][$task->id] = $this->Task_model->workLogs($task->id);

}

$data['calculated_hours'] = array();
if(!empty($data['work_log'])){
foreach ($data['work_log'] as $task_id => $logged_hours)
{
     $logged_hour = $logged_hours[0]->log_hrs;

      $logged_min = $logged_hours[0]->log_min;



      $calculated_hours = floor($logged_min/60);
      $calculated_min = $logged_min%60;

      $logged_hour +=$calculated_hours;
      $logged_min = $calculated_min;

     $data['calculated_hours'][$task_id]['logged_hrs'] = $logged_hour;
    $data['calculated_hours'][$task_id]['logged_min'] = $logged_min;
 
}
}

          $data['team_array'] = $team_member_array;



        if($data['tasks'] ==''){
          $data['result'] = "No Result Found.";
        }
         $this->pagination->initialize($config);
          $this->load->view('header', $data);
    $this->load->view('sidebar');
        $this->load->view('task/progress', $data); 
        $this->load->view('footer', $data);

   }


   public function summary_bda($reset){

     $data = $this->data;
    $status_id = "All";
    $user_id = $this->session->userdata('user_id');
  
$data['get_target_names']=$this->Task_model->get_target_name(NULL);

$data['get_all_customers'] = $this->Users_model->fetch_customers_for_bda(2,$user_id);

$data['get_all_team_members']=$this->Users_model->fetch_users_by_role(9,NULL,NULL,NULL);


$data['get_all_analyst']=$this->Users_model->fetch_users_by_role(4,$user_id,NULL,NULL);

//$data['get_target_names']=$this->Task_model->get_target_name($user_id);


$data['tasklists'] = $this->Task_model->fetchtask();

$search_array = $this->input->post();

$data['role_id'] =  $this->session->userdata('role_id');


$search_array = $this->input->post();

if(empty($search_array)) {
  $search_array = $this->session->userdata('task_summary_bda_search'); 

  if(isset($search_array['csv_export'])){
    $search_array['csv_export'] = NULL;
        }

}
else{
  $this->session->set_userdata('task_summary_bda_search',$search_array);
}
  if((strpos($_SERVER['HTTP_REFERER'], base_url()."task/summary") === false) || (isset($reset) && $reset == "reset")){

        $this->session->unset_userdata('task_summary_bda_search');
        $search_array = false;
    }

    
    $data['type_of_task'] = $this->uri->segment(2);
    $data['user_type'] = 'Customer';
     $user_details = $this->Users_model->fetchdata($user_id);

     $data['user_details'] =  $user_details;
      $data['plan_name'] = $this->Users_model->fetch_plan_name($user_details[0]->plan_id);
      $data['plan_hours'] = $this->Users_model->fetch_actual_plan_hours($user_details[0]->plan_id);
      $user_status = $this->Status_model->fetch_status_by_id($user_details[0]->status_id);
     
      $data['user_status'] = $user_status[0]->name;

    $data['email'] = $user_details[0]->username;
    $data['id'] = $user_details[0]->id;
    $data['first_name'] = $user_details[0]->first_name;
    $data['last_name'] = $user_details[0]->last_name;
    $data['email'] = $user_details[0]->username;
    $this->load->library('pagination');
    $config['base_url'] = base_url().'task/summary';
        $data['count_tasks']= $this->Task_model->count_task($user_id,NULL,$search_array, NULL, 'bda_tasks');
     
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
        $order_type = 'Asc';  
        //make the data type var avaible to our view
        $data['order_type_selected'] = $order_type;
        $this->pagination->initialize($config);

           if ($this->input->server('REQUEST_METHOD') === 'POST'){
         
          if($this->input->post('submit') == "Submit"){
             $search_target = $this->input->post('search_target');
          $data['search_target'] = $search_target;

            $search_task_type = $this->input->post('search_task_type');
          $data['search_task_type'] = $search_task_type;

            $search_team_member = $this->input->post('search_team_member');
          $data['search_team_member'] = $search_team_member;

           $search_customer = $this->input->post('search_customer');
          $data['search_customer'] = $search_customer;


           $search_meeting_date_time = $this->input->post('search_meeting_date_time');
          $data['search_meeting_date_time'] = $search_meeting_date_time;
          }
          else{
            $search_target =NULL;
             $data['search_target'] = "";

              $search_task_type =NULL;
             $data['search_task_type'] = "";

             $search_customer =NULL;
             $data['search_customer'] = "";

              $search_team_member =NULL;
             $data['search_team_member'] = "";

             $search_bda =NULL;
             $data['search_bda'] = "";

             $search_meeting_date_time_start =NULL;
             $data['meeting_start_date'] = "";

             $search_meeting_date_time_to =NULL;
             $data['meeting_to_date'] = "";
          } 

        }
        else{
          $search_target = NULL;
          $data['search_target'] = $search_array['search_target'];

           $search_task_type =NULL;
             $data['search_task_type'] = $search_array['search_task_type'];

             $search_customer =NULL;
             $data['search_customer'] = $search_array['search_customer'];

              $search_team_member =NULL;
             $data['search_team_member'] = $search_array['search_team_member'];
           
             $search_bda =NULL;
             $data['search_bda'] = $search_array['search_bda'];

            $search_meeting_date_time_start =NULL;
             $data['meeting_start_date'] = $search_array['meeting_start_date'];

             $search_meeting_date_time_to =NULL;
             $data['meeting_to_date'] = $search_array['meeting_to_date'];
        }
        
        $owner_id [] = array();
        $member_ids[] =array();

        $owner_id = $this->Users_model->get_customer_id($user_id);

        $member_ids = $this->Users_model->get_member_ids($user_id);

       
       
        $user_ids = array_merge($member_ids, $owner_id);

         $data['tasks'] = $this->Task_model->get_task('', $order_type, $config['per_page'],$limit_end,$user_ids,$status_id,$search_array, NULL, 'bda_list');

          
       $users = array();

        $team_member_array = array();

        foreach($data['tasks'] as $task)
        {
           $users = $task->user_id;

              $team_member_array = $this->Users_model->fetch_teambers_for_bda($users);

              $data['team_array'] = $team_member_array;


              // echo "<pre>";
              // print_r($data);
              // exit();
        }

  foreach($data['tasks'] as $task){


$data['work_log'][$task->id] = $this->Task_model->workLogs($task->id);

}

$data['calculated_hours'] = array();
if(!empty($data['work_log'])){
foreach ($data['work_log'] as $task_id => $logged_hours)
{
     $logged_hour = $logged_hours[0]->log_hrs;

      $logged_min = $logged_hours[0]->log_min;



      $calculated_hours = floor($logged_min/60);
      $calculated_min = $logged_min%60;

      $logged_hour +=$calculated_hours;
      $logged_min = $calculated_min;

     $data['calculated_hours'][$task_id]['logged_hrs'] = $logged_hour;
    $data['calculated_hours'][$task_id]['logged_min'] = $logged_min;
 
}
}
          $data['team_array'] = $team_member_array;


        if($data['tasks'] ==''){
          $data['result'] = "No Result Found.";
        }
        
        $this->pagination->initialize($config);
          $this->load->view('header', $data);
    $this->load->view('sidebar');
        $this->load->view('task/progress', $data); 
        $this->load->view('footer', $data);






   }


   public function deleted_tasks($reset = NULL)
   {

   $data = $this->data;
    $data['sidebar_class'] =  " ";
       $status = $this->Status_model->fetch_status("Deleted", "Task");
    //     $data['first_letter'] = $this->session->userdata('first_letter');  $data['first_letter'] = $this->session->userdata('first_letter');
   $user_id = $this->session->userdata('user_id');

    $status_id = $status[0]->id;

    $search_target = $this->input->post('search_target');

 //$data['get_all_team_members']=$this->Users_model->get_members($user_id);
      
      $data['get_members']=$this->Users_model->get_members($user_id);

  $data['get_owner']=$this->Users_model->get_owner($user_id);

$data['get_all_team_members'] = array_merge($data['get_members'], $data['get_owner']) ;

    $search_array = $this->input->post();

if(empty($search_array)) {
  $search_array = $this->session->userdata('task_deleted_search'); 

    if(isset($search_array['csv_export'])){
    $search_array['csv_export'] = NULL;
        }
}
else{
  $this->session->set_userdata('task_deleted_search',$search_array);
}
  if((strpos($_SERVER['HTTP_REFERER'], base_url()."task/deleted_tasks") === false) || (isset($reset) && $reset == "reset")){

        $this->session->unset_userdata('task_deleted_search');
        $search_array = false;
    }

    $user_id = $this->session->userdata('user_id');
  //  $data['name'] = $this->session->userdata('name');
    $data['tasklist'] = $this->Task_model->fetchtask();
    $data['type_of_task'] = $this->uri->segment(2);
     $user_details = $this->Users_model->fetchdata($user_id);
    $data['email'] = $user_details[0]->username;
    $data['id'] = $user_details[0]->id;
    $data['first_name'] = $user_details[0]->first_name;
    $data['last_name'] = $user_details[0]->last_name;
    $data['email'] = $user_details[0]->username;
      $data['plan_id'] = $user_details[0]->plan_id;
    $this->load->library('pagination');

    $config['base_url'] = base_url().'task/deleted_tasks';

       $owner_id = $this->Users_model->get_customer_id($user_id);

        $member_ids = $this->Users_model->get_member_ids($user_id);
        $user_ids = array();
        foreach($owner_id as $owner){
          $user_ids[] = $owner->member_id;
        }
        foreach($member_ids as $member_id){
          $user_ids[] = $member_id->member_id;
        }
       

            $data['get_target_names']=$this->Task_model->get_target_name($user_ids);
        $data['count_tasks']= $this->Task_model->count_task($user_ids,$status_id,$search_target);
        $config['total_rows'] = $data['count_tasks'];
        $config["per_page"] = 20;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);
        
        $config['use_page_numbers'] = TRUE;
        //$config['num_links'] = 5;
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
        $page = $this->uri->segment(3);

        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        } 
        $order_type = 'Asc';  
        //make the data type var avaible to our view
        $data['order_type_selected'] = $order_type;

           if ($this->input->server('REQUEST_METHOD') === 'POST'){
         
          if($this->input->post('submit') == "Submit"){
             $search_target = $this->input->post('search_target');
          $data['search_target'] = $search_target;


            $search_team_member = $this->input->post('search_team_member');
          $data['search_team_member'] = $search_team_member;


        $search_meeting_date_time_start= $this->input->post('created_start_date');
          $data['created_start_date'] = $search_meeting_date_time_start;

        $search_meeting_date_time_to = $this->input->post('created_to_date');
          $data['created_to_date'] = $search_meeting_date_time_to;

          }
          else{
            $search_target =NULL;
             $data['search_target'] = "";


             $search_team_member =NULL;
             $data['search_team_member'] = "";

                $search_meeting_date_time_start =NULL;
             $data['created_start_date'] = "";

             $search_meeting_date_time_to =NULL;
             $data['created_to_date'] = "";
          } 

        }
        else{
          $search_target = NULL;
          $data['search_target'] = $search_target['search_target'];


             $search_team_member =NULL;
             $data['search_team_member'] =  $search_target['search_team_member'];

                $search_meeting_date_time_start =NULL;
             $data['created_start_date'] = $search_target['created_start_date'];


             $search_meeting_date_time_to =NULL;
             $data['created_to_date'] =$search_target['created_to_date'];
        }


        $owner_id [] = array();
        $member_ids[] =array();

        $owner_id = $this->Users_model->get_customer_id($user_id);

        $member_ids = $this->Users_model->get_member_ids($user_id);

       
       
        $user_ids = array_merge($member_ids, $owner_id);

        $data['count_tasks']= $this->Task_model->count_task($search_target);
       $data['tasks'] = $this->Task_model->get_task('', $order_type, $config['per_page'],$limit_end,$user_ids,$status_id,$search_array);

  foreach($data['tasks'] as $task){


$data['work_log'][$task->id] = $this->Task_model->workLogs($task->id);

}

$data['calculated_hours'] = array();
if(!empty($data['work_log'])){
foreach ($data['work_log'] as $task_id => $logged_hours)
{
     $logged_hour = $logged_hours[0]->log_hrs;

      $logged_min = $logged_hours[0]->log_min;



      $calculated_hours = floor($logged_min/60);
      $calculated_min = $logged_min%60;

      $logged_hour +=$calculated_hours;
      $logged_min = $calculated_min;

     $data['calculated_hours'][$task_id]['logged_hrs'] = $logged_hour;
    $data['calculated_hours'][$task_id]['logged_min'] = $logged_min;
 
}
}

        if($data['tasks'] ==''){
          $data['result'] = "No Result Found.";
        }
        $this->pagination->initialize($config);
          $this->load->view('header', $data);
    $this->load->view('sidebar');
        $this->load->view('task/progress', $data); 
        $this->load->view('footer', $data);
   }


    public function summary($reset = NULL)
  {

    $data =  $this->data;

  $data['role'] =  $this->session->userdata('role_id');

  if($data['role'] == 2 || $data['role_id'] == 9){

     $this->summary_customer($reset);
    }

    else if($data['role'] == 3){

         $this->summary_bda($reset);

    }

    else{

     
      print "Functionality is not yet implemented. <a href='/user/logout'>Logout</a>";

   }

   

  }


   public function summary_customer($reset){

    $data = $this->data;
    $data['sidebar_class'] =  " ";


   $status_id =  "All";


    $user_id = $this->session->userdata('user_id');
    $data['role_id'] =  $this->session->userdata('role_id');

  //$data['first_letter'] = $this->session->userdata('first_letter');
$search_target = $this->input->post('search_target');
   // $data['name'] = $this->session->userdata('name');


//$data['get_all_team_members']=$this->Users_model->get_members($user_id);
      

  $data['get_members']=$this->Users_model->get_members($user_id);

  $data['get_owner']=$this->Users_model->get_owner($user_id);

$data['get_all_team_members'] = array_merge($data['get_members'], $data['get_owner']) ;

$search_array = $this->input->post();

if(empty($search_array)) {
  $search_array = $this->session->userdata('task_summary_customer_search'); 

    if(isset($search_array['csv_export'])){
    $search_array['csv_export'] = NULL;
        }

}
else{
  $this->session->set_userdata('task_summary_customer_search',$search_array);
}
  if((strpos($_SERVER['HTTP_REFERER'], base_url()."task/summary") === false) || (isset($reset) && $reset == "reset")){

        $this->session->unset_userdata('task_summary_customer_search');
        $search_array = false;
    }

    $data['tasklist'] = $this->Task_model->fetchtask();
    $data['type_of_task'] = $this->uri->segment(2);
    $data['user_type'] = 'Customer';
     $user_details = $this->Users_model->fetchdata($user_id);

     $data['user_details'] =  $user_details;

      $data['plan_name'] = $this->Users_model->fetch_plan_name($user_details[0]->plan_id);

      if((int) $user_details[0]->role_id !== 9){

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
      $user_status = $this->Status_model->fetch_status_by_id($user_details[0]->status_id);
     
      $data['user_status'] = $user_status[0]->name;

    $data['email'] = $user_details[0]->username;
    $data['id'] = $user_details[0]->id;
    $data['first_name'] = $user_details[0]->first_name;
    $data['last_name'] = $user_details[0]->last_name;
    $data['email'] = $user_details[0]->username;
      $data['plan_id'] = $user_details[0]->plan_id;
    $this->load->library('pagination');
    $config['base_url'] = base_url().'task/summary';
      
        $user_id = $this->session->userdata('user_id');
        $owner_id [] = array();
        $member_ids[] =array();

        $owner_id = $this->Users_model->get_customer_id($user_id);

        $member_ids = $this->Users_model->get_member_ids($user_id);

       
         foreach($owner_id as $owner){
          $user_ids[] = $owner->member_id;
        }
        foreach($member_ids as $member_id){
          $user_ids[] = $member_id->member_id;
        }
       
        //$user_ids = array_merge($member_ids, $owner_id);

        $data['get_target_names']=$this->Task_model->get_target_name($user_ids);
        //print_r($user_ids);

        // echo "<pre>";
        // print_r($search_array);
        // exit();
        $data['count_tasks']= $this->Task_model->count_task($user_ids, $status_id,$search_array);
     
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
      
        $page = $this->uri->segment(3);

        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        } 
        $order_type = 'Asc';  
        //make the data type var avaible to our view
        $data['order_type_selected'] = $order_type;
        $this->pagination->initialize($config);

           if ($this->input->server('REQUEST_METHOD') === 'POST'){
         
          if($this->input->post('submit') == "Submit"){
             $search_target = $this->input->post('search_target');
          $data['search_target'] = $search_target;

             $search_team_member = $this->input->post('search_team_member');
          $data['search_team_member'] = $search_team_member;


        $search_meeting_date_time_start= $this->input->post('created_start_date');

          $data['created_start_date'] = $search_meeting_date_time_start;

        $search_meeting_date_time_to = $this->input->post('created_to_date');
          $data['created_to_date'] = $search_meeting_date_time_to;
          
          }
          else{
            $search_target =NULL;
             $data['search_target'] = "";


             $search_team_member =NULL;
             $data['search_team_member'] = "";

                $search_meeting_date_time_start =NULL;
             $data['created_start_date'] = "";

             $search_meeting_date_time_to =NULL;
             $data['created_to_date'] = "";
          } 

        }
        else{
          $search_target = NULL;
          $data['search_target'] = $search_array['search_target'];


             $search_team_member =NULL;
             $data['search_team_member'] =$search_array['search_team_member'];

                $search_meeting_date_time_start =NULL;
             $data['created_start_date'] = $search_array['created_start_date'];

             $search_meeting_date_time_to =NULL;
             $data['created_to_date'] = $search_array['created_to_date'];
        }
        
        $owner_id [] = array();
        $member_ids[] =array();

        $owner_id = $this->Users_model->get_customer_id($user_id);

        $member_ids = $this->Users_model->get_member_ids($user_id);

       
       
        $user_ids = array_merge($member_ids, $owner_id);
       // $data['tasks'] = $this->Task_model->get_task('', $order_type, $config['per_page'],$limit_end,$user_id,NULL,$search_target);

         $data['tasks'] = $this->Task_model->get_task('', $order_type, $config['per_page'],$limit_end,$user_ids,$status_id,$search_array);

         
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


     foreach($data['tasks'] as $task){


$data['work_log'][$task->id] = $this->Task_model->workLogs($task->id);

}

$data['calculated_hours'] = array();
if(!empty($data['work_log'])){
foreach ($data['work_log'] as $task_id => $logged_hours)
{
     $logged_hour = $logged_hours[0]->log_hrs;

      $logged_min = $logged_hours[0]->log_min;



      $calculated_hours = floor($logged_min/60);
      $calculated_min = $logged_min%60;

      $logged_hour +=$calculated_hours;
      $logged_min = $calculated_min;

     $data['calculated_hours'][$task_id]['logged_hrs'] = $logged_hour;
    $data['calculated_hours'][$task_id]['logged_min'] = $logged_min;
 
}
}
      




      //grantotal hours

  $data['total_tasks']= $this->Task_model->count_task($user_id,$status_id,$search_array);


  // echo "<pre>";
  // print_r($search_array);
  // exit();



// echo "<pre>";
// print_r($data);
// exit();


  $team_member=$this->Users_model->get_team_members($user_id);
        $team_member_array = array();

if(!empty($team_member)){

        foreach($team_member as $key=>$team){

         $team_member_array[$team->member_id] = $team->first_name . " ". $team->last_name;
        }

   }    

          $data['team_array'] = $team_member_array;

        if($data['tasks'] ==''){
          $data['result'] = "No Result Found.";
        }
        


        $this->pagination->initialize($config);
          $this->load->view('header', $data);
    $this->load->view('sidebar');
        $this->load->view('task/progress', $data); 
        $this->load->view('footer', $data);


   }


   



   public function delete()
   {
    $data['delete'] = $this->input->post('delete');

 
    $data['id'] =  $this->session->userdata('user_id');
 
     $task_ids['ids'] = $this->input->post('delete');

    

     $status = $this->Status_model->fetch_status("Deleted", "Task");
     $analyst_status = $this->Status_model->fetch_status("Deleted", "AnalystTask");

    $status_id = $status[0]->id;
    $analyst_status_id = $analyst_status[0]->id;
    if($data['delete'] !=''){
      
      $progress_delete = $this->Task_model->deleteprogress($data,$status_id,$analyst_status_id);


        $user_details = $this->Users_model->fetchdata($data['id']);
      $first_name = $user_details[0]->first_name;
      $last_name = $user_details[0]->last_name;

      $user_name = $first_name." ".$last_name;


        foreach($task_ids as $task_id){

           $subject = 'Task Deletion';

          $task_details = $this->Task_model->get_task_by_id($task_id);

     

      foreach($task_details as $key => $task_detail){


        $message  = "The report on ". $task_detail->target_name ." for ".$user_name." has been deleted.";


           $cc = $this->Users_model->admin_emails();
    
          $data['email'] = $cc;


  
         $this->Users_model->send_mail($data,$message,$subject);

      }


      
   

    }


     // echo "<pre>";
     //  print_r($message);
     //  exit();
     
     
      //$data['email'] = $task_details[0]->email_to_notifiy;
    

      //$data['email'] =  $this->Users_model->admin_emails();

    


       $this->session->set_flashdata('success_message',"Task is successfully deleted.");
       redirect('task/inprogress');
    }

    else{
      $this->session->set_flashdata('error_message',"Please select a Task to delete.");
     redirect('task/inprogress');
    }
   }


   /**
    *This function is running as a ajax call from analyst-drag-drop.js
    */
   public function update_analyst_status_id_for_task(){


    $task_id = $this->input->post('task_id');
    $updated_analyst_status_id = $this->input->post('analyst_status_id'); 
     $stop_html_id = $this->input->post('stop_html_id');

    if($stop_html_id == "completed-panel"){
        
        $workLogs = $this->Task_model->workLogs($task_id);
        $loghrs = $workLogs[0]->log_hrs;
        $vallogmins = $workLogs[0]->log_min;
        $hrs = intval($vallogmins/60);
        $min = intval($vallogmins % 60);
        $data['workLogs'] = ($loghrs + $hrs)." hour(s) and ".$min . " minute(s)";
        $task_details = $this->Task_model->get_task_by_id($task_id);



        $task_type = $task_details[0]->name;
        $target_name = $task_details[0]->target_name;
        $data['analyst_id'] = $task_details[0]->analysist_id;
        $bda_id = $task_details[0]->bda_id;
        $user_id = $task_details[0]->user_id;
        $customer_email = $this->Users_model->get_email_by_id($user_id);

              $assistant_email_details = $this->Users_model->get_customer_assistant_details($user_id);


      $assistant_email = array();



      $admin_mails = $this->Users_model->admin_emails();
      foreach ($assistant_email_details as $key => $assistant_email_detail){
              
              if($assistant_email_detail->email_status== 1){
                
                $assistant_email[] = $assistant_email_detail->email;
                
             }
          
            }

 

    $cc = array_merge($assistant_email,$admin_mails);
            
            
       

        if(!empty($bda_id )){
        $bda_details = $this->Users_model->fetchdata($bda_id);
        $from_bda = $bda_details[0]->username;
        $bda_in_bcc = $bda_details[0]->username;
        }
        else{
        $from_bda = NULL;
        $bda_in_bcc = NULL; 
        }
        $analyst_links = $this->Task_model->getLinks($task_id);
        $data['analyst_links'] =  $analyst_links;
       
        $message = "Hi ".$task_details[0]->first_name.",<br><br>
        <div>Attached is the SalesSupport360 Report on ".$task_details[0]->target_name.".<br><br>
        Time spent for the report: ".$data['workLogs']."<br><br>
        Related Links:<br>";
        foreach ( $data['analyst_links'] as $key => $value ){
        $message .= '<a data-upload-id = '.$value->id.'  href="'.$value->url. '" class="link col-md-6">'.$value->url.'</a><br>';
        }
        if(!empty($task_details[0]->analyst_note) &&  $task_details[0]->analyst_note !== NULL){
        $message .= '<br>Note: <br>' . $task_details[0]->analyst_note . "<br>";
        }
        $message .= "<br>Please do not hesitate to contact me for any questions or concerns & let me know if there is anything else we can provide you with.<br><br>Thank you for your business. <br><br><div>";
        $subject  = $target_name." ".$task_type;
        $data['attachments'] = $this->Task_model->getAttachments($task_id, NULL);
        $data['email'] = $customer_email[0]->username;
        //$cc = $this->Users_model->admin_emails();
       // $cc_array = array();
        // foreach($cc as $c){
        //   $cc_array[] = $c->email;
        // }
       // $cc_array = $cc;


          $this->Users_model->send_mail($data,$message,$subject,$cc,NULL,$from_bda,$bda_in_bcc);

        }
        
        $update =  $this->Task_model->update_analyst_status($task_id,$updated_analyst_status_id);

        print $update;
        exit();           

     
 
   }
   /**
  * Custom Call back for checking on board date in Registration form
  * this function will only work if some value is entered in the field
  * 
  */
  public function meeting_date_time_check(){

    $post_on_board  = $this->input->post('meeting_date_time');

     if(isset($post_on_board) && !empty($post_on_board)){
     $onboard = strtotime($post_on_board);

     $now = time();
         

     if($onboard > $now){

      return TRUE;
     }
     else{


$this->form_validation->set_message('meeting_date_time_check', "Meeting date should not be in past.");
return FALSE;
     }

    }
    else{
      $this->form_validation->set_message('meeting_date_time_check', "Date and Time of meeting is required.");
      return FALSE;
    }
  }
 public function edit($tid)
   {
      

   $data = $this->data;
    $data['tid'] = $tid;
     // $data['first_letter'] = $this->session->userdata('first_letter');
    $data['error_class'] = 'form-group has-feedback has-error';
    $data['error_less_class'] = "form-group";
    $data['task_type'] = $this->uri->segment(3);
    $user_id = $this->session->userdata('user_id');
    $role_id = $this->session->userdata('role_id');
    //$data['name'] = $this->session->userdata('name');
    $data['first_letter'] = $this->session->userdata('first_letter');
    //$data['tasklist'] = $this->Task_model->fetchtask();
    $data['edittask'] = $this->Task_model->get_task_by_id($tid,'','');
    $data['target_name'] = '';
    $data['target_name'] = '';
    $data['present_company'] = '';
    $data['previous_company'] = '';
    $data['email_address'] = '';
    $data['home_address'] = '';
    $data['meeting_date_time'] = '';
    $data['comments_additional_info'] = '';
     $data['no_of_connections'] = '';
    // echo "<pre>";
    // print_r($data);
    // exit();

    foreach($data['edittask'] as $task){

      //print_r($task);
      $data['id'] = $task->id;
        $data['task_name'] = $task->name;
        $data['account_name'] = $task->account_name;
        $data['target_name'] = $task->target_name;
        $data['present_company'] = $task->present_company;
        $data['previous_company'] = $task->previous_company;
        $data['email'] = $task->email;
        $data['home_address'] = $task->home_address;
        $data['meeting_date_time'] = $task->meeting_date_time;
        $data['analyst_info'] = $task->analyst_note;
        $data['comments_additional_info'] = $task->comments_additional_info;
        $data['analyst_id'] = $task->analysist_id;
        $data['no_of_connections'] = $task->no_of_connections;
        $data['status'] = $task->analyst_status_id;
        $data['created'] = date('m-d-Y h:i A',strtotime($task->created));
        $data['updated'] = date('m-d-Y h:i A',strtotime($task->updated));

    }

    // echo "<pre>";
    // print_r($data);
    // exit();

    $analyst_links = $this->Task_model->getLinks($tid);
    $user_details = $this->Users_model->fetchdata($user_id);
    $task_created_user = $this->Users_model->fetchdata($task->user_id);
    $data['task_created_user'] = $task_created_user;
    $log_details = $this->Task_model->logData($tid);
    $userattach = $this->Task_model->getAttachments($tid,1);//params(task_id, is_customer_flag)
    $analyst_attachments = $this->Task_model->getAttachments($tid,0);//params(task_id, is_customer_flag)

    $workLogs = $this->Task_model->workLogs($tid);
    $loghrs = $workLogs[0]->log_hrs;
    $vallogmins = $workLogs[0]->log_min;
    $hrs = intval($vallogmins/60);
    $min = intval($vallogmins % 60); 
    
    
   // $data['email_to_notifiy'] = $user_details[0]->username;
    $data['user_id'] = $user_details[0]->id;
    $data['first_name'] = $user_details[0]->first_name;
    $data['last_name'] = $user_details[0]->last_name;
    //$data['email'] = $user_details[0]->username;
    $data['log'] = $log_details;
    $data['analyst_attachments'] = $analyst_attachments;
    $data['analyst_links'] =  $analyst_links;
    $data['customer_attachments'] = $userattach;
    $data['workLogs'] = ($loghrs + $hrs)." hour(s) and ".$min . " minute(s)";

    if($role_id == 3){

       $data['readonly'] = "";
    }
    else{
     
       $data['readonly'] = 'readonly';
    }

    $this->load->view('header', $data);
    $this->load->view('sidebar');
    $this->load->view('task/edit',$data); 
    $this->load->view('footer', $data);
    
   }

  


    public function update(){
    $data = $this->data;
   
    //$data['name'] = $this->session->userdata('name');
    $data['first_letter'] = $this->session->userdata('first_letter');
     $role_id= $this->session->userdata('role_id');
    
    
     // $data = array();
 $empty_link = TRUE;
 if(!empty($_POST['links'])){
        foreach($_POST['links'] as $user_links){
            if(!empty($user_links)){
              $empty_link = FALSE;
            }
        }
      }

      if($this->input->server('REQUEST_METHOD') === 'POST' &&  (isset($empty_link) && $empty_link == FALSE)){

            $linkCount = count($_POST['links']);
           for($j = 0; $j < $linkCount; $j++){
  if(!empty($_POST['links'][$j])){
  if (!preg_match("~^(?:f|ht)tps?://~i", $_POST['links'][$j])) {
        $_POST['links'][$j] = "http://" . $_POST['links'][$j];
    }


              $link[$j]['user_id'] = $_POST['analyst_id'];
              $link[$j]['task_id'] = $_POST['task_id'];
              $link[$j]['url'] = $_POST['links'][$j];
              $link[$j]['created'] = date("Y-m-d H:i:s");
              $link[$j]['modified'] = date("Y-m-d H:i:s");
            }
          }

            if($_POST['links'] !=0){
             $insert_links= $this->Task_model->insert_links($link);
           }

         }

              $check_error = FALSE;
              $empty_file = TRUE;

        if(!empty($_FILES)){
        foreach($_FILES['userFiles']['name'] as $user_files){
            if(!empty($user_files)){
              $empty_file = FALSE;
            }
        }
      }
        if($this->input->server('REQUEST_METHOD') === 'POST'&& (isset($empty_file) && $empty_file == FALSE)){
         
            $filesCount = count($_FILES['userFiles']['name']);
       

            for($i = 0; $i < $filesCount; $i++){
              
                $_FILES['userFile']['name'] = $_FILES['userFiles']['name'][$i];
                $_FILES['userFile']['type'] = $_FILES['userFiles']['type'][$i];
                $_FILES['userFile']['tmp_name'] = $_FILES['userFiles']['tmp_name'][$i];
                $_FILES['userFile']['error'] = $_FILES['userFiles']['error'][$i];
                $_FILES['userFile']['size'] = $_FILES['userFiles']['size'][$i];

                $uploadPath = 'assets/files/';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = 'txt|pdf|xls|doc|docx|xlsx|ods|xml|text/csv|csv';
                $config['max_size'] = '40000';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
           
                if($this->upload->do_upload('userFile')){
            
                    $fileData = $this->upload->data();
                    
                    $uploadData[$i]['user_id'] = $_POST['analyst_id'];
                    $uploadData[$i]['task_id'] = $_POST['task_id'];
                    $uploadData[$i]['path'] = $fileData['file_name'];
                    $uploadData[$i]['created'] = date("Y-m-d H:i:s");
                    $uploadData[$i]['modified'] = date("Y-m-d H:i:s");
                }
                else{

                   $check_error = TRUE;
                   $this->load->library('form_validation');
                   $this->form_validation->set_message('file',$this->upload->display_errors());
                     $this->session->set_flashdata('file_error_message',$this->upload->display_errors());
                }
                
            }
 

 if(!empty($uploadData)){
                //Insert file information into the database
               
                $insert = $this->Task_model->insert_files($uploadData);
                
                $statusMsg = $insert?'Files uploaded successfully.':'Some problem occurred, please try again.';
                $this->session->set_flashdata('statusMsg',$statusMsg);
                 
              }
   
           
        }
if(!empty($this->input->post("meeting_date_time")) && ($this->input->post("meeting_date_time"))){
    $date = date("Y-m-d h:i:s a", strtotime($this->input->post('meeting_date_time')));

  }
  else{
    $date = NULL;
  }

      if($_POST['status'] == 8 )
      {

        $customer_status = 5;

      }

      else{

          $customer_status = 3;
      }
 $customer_email=$this->Task_model->get_customer_email($_POST['task_id'],NULL);

  $data['customer_email']=$customer_email[0]->email_to_notifiy;

     

         if($role_id==3){

           $analyst_update =array(

              'id'=> $_POST['task_id'],
              'account_name'=> $this->input->post('acccount_name'),
              'target_name' =>  $this->input->post('target_name'),
              'no_of_connections' => $_POST['connections'],
              'analyst_note' => $_POST['analyst_note'],
              'analyst_status_id' => $_POST['status'],
              'analyst_id' => $_POST['analyst'],
              'updated' => date("Y-m-d H:i:s"),
              'present_company' => $_POST['present_company'],
              'status_id' => $customer_status,
              'previous_company' => $_POST['previous_company'],
             'email' => $this->input->post('email_address'),
              'comments_additional_info' => $this->input->post('customer_notes'),
             'home_address' => $this->input->post('address'),
             'meeting_date_time' => $date,
             'email_to_notifiy' => $this->input->post('email_address'),

        );
     
         }

         else{

                  $analyst_update = array(
              'id' => $_POST['task_id'],
              'no_of_connections' => $_POST['connections'],
              'analyst_note' => $_POST['analyst_note'],
              'status_id' =>$customer_status,
              'analyst_status_id' => $_POST['status'],
              'updated' => date("Y-m-d H:i:s")
              );


         }


    

        if($check_error == FALSE){

            $files_urls_to_remove = array( 'files_to_delete' => $this->input->post('removed-file-links'),
             'urls_to_delete' => $this->input->post('removed-url-links'));
                  $this->Task_model->delete_files_links($files_urls_to_remove);
            $update = $this->Task_model->update_task($analyst_update);

            $this->Task_model->delete_files_links($files_urls_to_remove);



          $this->session->set_flashdata('success_message',"Task has been successfully updated.");
        
           
    

            if(($update ) && ($customer_status == 5)){



               $task_id = $_POST['task_id'];

                $workLogs = $this->Task_model->workLogs($task_id);
                $loghrs = $workLogs[0]->log_hrs;
                $vallogmins = $workLogs[0]->log_min;
                $hrs = intval($vallogmins/60);
                $min = intval($vallogmins % 60);
                
    //$data['log'] = $log_details;
   

                $data['workLogs'] = ($loghrs + $hrs)." hour(s) and ".$min . " minute(s)";
   

                $task_details = $this->Task_model->get_task_by_id($task_id);

                
              $user_id = $task_details[0]->user_id;

              $assistant_email_details = $this->Users_model->get_customer_assistant_details($user_id);


      $assistant_email = array();
        $admin_mails = $this->Users_model->admin_emails();
      foreach ($assistant_email_details as $key => $assistant_email_detail){
              
              if($assistant_email_detail->email_status== 1){
                
                $assistant_email[] = $assistant_email_detail->email;
                
             }
          
            }



          $cc = array_merge($assistant_email,$admin_mails);
            
         
                $task_type = $task_details[0]->name;

                $target_name = $task_details[0]->target_name;
                 $user_id = $task_details[0]->user_id;


                $data['analyst_id'] = $task_details[0]->analysist_id;

                $bda_id = $task_details[0]->bda_id;


                if(!empty($bda_id )){

                    $bda_details = $this->Users_model->fetchdata($bda_id);

                    $from_bda = $bda_details[0]->username;
                    $bda_in_bcc = $bda_details[0]->username;

                  }

              else{

                $from_bda = NULL;
                $bda_in_bcc = NULL;
                 }

            $analyst_links = $this->Task_model->getLinks($task_id);

            $data['analyst_links'] =  $analyst_links;
        
            $user_email = $this->Users_model->get_email_by_id($user_id);


            /*Mail sending Function will run here*/
            $data['email'] = $user_email[0]->username;

            $data['attachments'] = $this->Task_model->getAttachments($task_id);
            $message = "Hi ".$task_details[0]->first_name.",<br><br>

             <div>Attached is the SalesSupport360 Report on ".$task_details[0]->target_name.".<br><br>

              Time spent for the report: ".$data['workLogs']."<br><br>
              Related Links:<br>";


            foreach ( $data['analyst_links'] as $key => $value ){

     
   
     /*Mail sending Function will run here*/
     //  $message .= $value->url . "<br>";
         
          $message .= '<a data-upload-id = '.$value->id.'  href="'.$value->url. '" class="link col-md-6">'.$value->url.'</a><br>';
}


          if(!empty($task_details[0]->analyst_note) &&  $task_details[0]->analyst_note !== NULL){
            $message .= '<br>Note: <br>' . $task_details[0]->analyst_note . "<br>";

          }

          $message .= "<br>Please do not hesitate to contact me for any questions or concerns & let me know if there is anything else we can provide you with.<br><br>Thank you for your business.</div>";

            $subject  = $target_name." ".$task_type;

       

        

              $this->Users_model->send_mail($data,$message,$subject,$cc,NULL,$from_bda,$bda_in_bcc);
       
            }

    $this->session->set_flashdata('success_message',"Task has been successfully updated.");

            }
    
             
redirect('task/edit/'.$this->input->post('task_id'));


    }



     
    public function addlog(){
      $data = array();
     // $log_work = array($this->input->post('log_worked_hrs').":".$this->input->post('log_worked_min'));
     // print_r($log_work);
      
       
       $data = array(
          'task_id' => $this->input->post('task_id'),
          'log_id' => $this->input->post('log_id'),
          'log_date' => $this->input->post('log_date'),
          'log_worked_hrs' => $this->input->post('log_worked_hrs'),
          'log_worked_min' => $this->input->post('log_worked_min'),
          'status' => 0,
          'created' =>  date("Y-m-d H:i:s"),
          'modified' =>  date("Y-m-d H:i:s"),
        );
       // echo "<pre>";
       // print_r($data);
       // echo "<pre>";
       // exit;
       $insert = $this->Task_model->insertlog($data);
       redirect('task/edit/'.$this->input->post('task_id'));  

    }
 

}