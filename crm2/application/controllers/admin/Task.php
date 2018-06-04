<?php

/**
 * Functions for admin/task url
 * @package Controllers
 * @subpackage Admin
 */
class Task extends Admin_Controller {


public function __construct()
    {
        parent::__construct();
      
      $this->data = $this->user_data;
    


        if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }
    }


 public function index(){

  $data = $this->data;
      //$data['tasklist'] = $this->Task_model->fetchtask();
   // $data['first_letter'] = $this->session->userdata('first_letter');
    //var_dump($task_data[0]->name);
    //$data['name'] = $task_data[0]->name;
    $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/task/list');
    $this->load->view('admin/footer', $data);
 }

  /**
   * Task Add Form from admin panel
   *
   */
  public function add()
   {
      
   $data = $this->data;
    

   $user_id = $this->session->userdata('user_id');

   $status_id = 1;

   $data['get_all_customers']=$this->Users_model->fetch_users_by_role(2,$user_id,NULL,NULL,NULL,NULL,$status_id,NULL);


   $data['get_analysts']=$this->Users_model->fetch_users_by_role(4,$user_id,NULL,NULL);



    $data['error_class'] = 'form-group has-feedback has-error';
    $data['error_less_class'] = "form-group";
    $data['task_type'] = $this->uri->segment(4);
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

  

    foreach($data['tasklist'] as $task){
        
      if($task->id  == $data['task_type']){
        $data['title'] = $task->name;
      }

    }

   

    $user_details = $this->Users_model->fetchdata($user_id);
    $data['email_to_notifiy'] = $user_details[0]->username;
    $data['user_id'] = $user_details[0]->id;
    $data['first_name'] = $user_details[0]->first_name;
    $data['last_name'] = $user_details[0]->last_name;
    $data['email'] = $user_details[0]->username;

    $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/task/add',$data);
    $this->load->view('admin/footer', $data);
    
   }




/**
 * Submit action for Task Add For from admin panel
 *
 * Takes the user name and analyst from the select box in the form saves in their account
 *
 * Takes the users bda and adds him in the task
 *
 * After this sends two mails one for bda saying task is created
 *
 * One for Analyst saying task is assigned
 *
 * Auto Assign analyst logic also implemented here
 *
 */
public function insert()
{

    $data =  $this->data;
    
    //$this->load->model('Task_model');
    $this->form_validation->set_rules('target_name', 'Target Name', 'required');
    $this->form_validation->set_rules('present_company', 'Present Company', 'required');
   
 $this->form_validation->set_rules('meeting_date_time', 'Date and Time','required');
  $this->form_validation->set_rules('email_address',"Email",'valid_email', array('valid_email' => 'Provide a valid Email Address.'));
   $this->form_validation->set_rules('customer', 'Customer', 'required');


     $title_id = $this->input->post('task_type');
    $data['first_letter'] = $this->session->userdata('first_letter');
      $data['tasklist'] = $this->Task_model->fetchtask();

$user_id = $this->session->userdata('user_id');

  $status_id = 1;

 $data['get_all_customers']=$this->Users_model->fetch_users_by_role(2,$user_id,NULL,NULL,NULL,NULL,$status_id,NULL);


$data['get_analysts']=$this->Users_model->fetch_users_by_role(4,$user_id,NULL,NULL);
    foreach($data['tasklist'] as $task){

      if($title_id ==  $task->id){
        $data['title'] = $task->name;
      }
     
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
    //$data['email'] = $user_details[0]->username;
    //$data['task_type'] = $this->input->post('task_type') ? $this->input->post('task_type') : '' ;
    $data['target_name'] = $this->input->post('target_name') ? $this->input->post('target_name') : '';
    $data['present_company'] = $this->input->post('present_company') ? $this->input->post('present_company') : '';
    $data['previous_company'] = $this->input->post('previous_company') ? $this->input->post('previous_company') : '';
    $data['email_to_notifiy'] = $this->input->post('email_to_notifiy') ? $this->input->post('email_to_notifiy') : '';
    $data['home_address'] = $this->input->post('address') ? $this->input->post('address') : '';
    $data['meeting_date_time'] = $this->input->post('meeting_date_time') ? $this->input->post('meeting_date_time') : '';
    $data['comments_additional_info'] = $this->input->post('additional_info') ? $this->input->post('additional_info') : '';

     $data['customer'] = $this->input->post('customer') ? $this->input->post('customer') : '';

      $data['analyst'] = $this->input->post('analyst') ? $this->input->post('analyst') : '';
     


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


                  $this->load->view('admin/header', $data);
                  $this->load->view('admin/sidebar');
                  $this->load->view('admin/task/add',$data);
                  $this->load->view('admin/footer', $data);

                }
                else{

 
  
  if(!empty($this->input->post("meeting_date_time")) && ($this->input->post("meeting_date_time"))){
    $date = date("Y-m-d H:i:s", strtotime($this->input->post('meeting_date_time')));

  }
  else{
    $date = NULL;
  }
   $customer_id=$this->input->post('customer');

   $customer_details = $this->Users_model->fetchdata($customer_id);

   $data['bda_id'] = $customer_details[0]->bda_id;

   // echo "<pre>";
   // print_r($data['bda_id']);
   // exit();

  $customer_name=$this->Task_model->get_customer_name($customer_id);

  $email_to_notifiy=$this->Task_model->get_customer_name($customer_id);

  $customer_email = $email_to_notifiy[0]->username;


     

      $first_name=$customer_name[0]->first_name; 
      $last_name=$customer_name[0]->last_name; 
      $customer=$customer_name[0]->first_name." ".$customer_name[0]->last_name;


      $data = array(
          'account_name'=>$customer,
          'target_name' =>  $this->input->post('target_name'),
          'present_company' => $this->input->post('present_company'),
          'previous_company' => $this->input->post('previous_company'),
          'user_id' => $this->input->post('customer'),
          'analysist_id' => $this->input->post('analyst'),
          'email' => $this->input->post('email_address'),
          'home_address' => $this->input->post('address'),
          'meeting_date_time' => $date,
          'comments_additional_info' => $this->input->post('additional_info'),
          'task_type_id' => $this->input->post('task_type'),
          'email_to_notifiy' =>$customer_email,
          'bda_id' => $data['bda_id'],
          'analysist_id' => $analyst_id,
          'status_id' => $status_id,
          'analyst_status_id' => $analyst_status_id,
          'created' => date("Y-m-d H:i:s"),
          'updated' => date("Y-m-d H:i:s"),
        );




        $task_details = $this->Task_model->taskupdate($data);

        $updated_order = $this->Users_model->update_analyst_order($analyst_id, $current_analyst_order,$updated_analyst_order);

        $this->session->set_flashdata('success_message',"Task has been created successfully.");


    $user_details = $this->Users_model->fetchdata($data['user_id']);
    
;

    $data['bda_id'] = $user_details[0]->bda_id;



     if(!empty($data['bda_id'])){

    $user_details = $this->Users_model->fetchdata($data['bda_id'] );

    $bda_email = $user_details[0]->username;
  }

  else{

     $bda_email = NULL;
  }

  
$subject = 'Task Assignment';
  $cc = $this->Task_model->admin_emails();

   $analyst_details = $this->Users_model->fetchdata($data['analysist_id']);

 /*checking whether date of meeting is specified or not*/
 if(!empty($data['meeting_date_time'])){

          $meeting_date = date('m-d-y h: i A',strtotime($data['meeting_date_time']));
        }
        else{

          $meeting_date = "Not mentioned";
        }

$task_type = $this->Task_model->fetch_task_type($data['task_type_id']);


 
   $data['email'] =  $analyst_details[0]->username;
   $analyst_name = $analyst_details[0]->first_name;

  $message = "Hi ".$analyst_name.",<br><br>
 The ".$task_type[0]->task_name." from ".$data['account_name']." has been assigned.<br> Date of meeting: ".$meeting_date." ";

   $cc = $this->Users_model->admin_emails();
if(!empty($bda_email)){

   $cc[] = $bda_email;
 }

   $this->Users_model->send_mail($data,$message,$subject,$cc,NULL,NULL);

    $this->session->set_flashdata('success_message',"Task has been created successfully.");


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

  


     $empty_file = TRUE;
      
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
                    
                    $uploadData[$i]['user_id'] = $this->input->post('customer');

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
        // print $current_analyst_order;
        // print $updated_analyst_order;
        // print_r($analyst_id);
        // exit();
/*Update the analyst order of the assigned analyst*/
      

      }           

     if ($this->form_validation->run() !== FALSE){
      redirect('admin/task/inprogress');
     }
     else{


                  redirect('admin/task/edit/'.$user_details);
     }
    }
      
    }
   }
    
   /**
    * Task Delete action by admin 
    */
   public function delete_tasks(){

    $data['delete'] = $this->input->post('delete_task');
    $data['id'] =  $this->session->userdata('user_id');
    $redirect_url =  $this->input->post('redirect_url');

     $status = $this->Status_model->fetch_status("Deleted", "Task");
     $analyst_status = $this->Status_model->fetch_status("Deleted", "AnalystTask");

    $status_id = $status[0]->id;
    $analyst_status_id = $analyst_status[0]->id;
    $task_id = $this->input->post('delete_task');


    
     
    $task_details = $this->Task_model->get_task_by_id($task_id[0]);




      //$user_details = $this->Users_model->fetchdata($data['id']);
     
      $data['email'] = $task_details[0]->email_to_notifiy;
     
    if($data['delete'] !=''){
      
    $progress_delete = $this->Task_model->admin_delete_tasks($data['delete']);

      

       $this->session->set_flashdata('success_message',"Task has been deleted successfully.");

       redirect($redirect_url);
    }
    else{
      $this->session->set_flashdata('error_message',"Please select a Task to delete.");
     redirect($redirect_url);
    }
   }

   /**
    * Task Edit Form from admin panel
    *
    */
   public function edit($tid)
   {
      
       $data = $this->data;
       $data['tid'] = $tid;
     // $data['first_letter'] = $this->session->userdata('first_letter');
    $data['error_class'] = 'form-group has-feedback has-error';
    $data['error_less_class'] = "form-group";
    $data['task_type'] = $this->uri->segment(3);
    $user_id = $this->session->userdata('user_id');

    $data['get_analysts']=$this->Users_model->fetch_users_by_role(4,$user_id,NULL,NULL);
    //$data['name'] = $this->session->userdata('name');
    $data['first_letter'] = $this->session->userdata('first_letter');
    //$data['tasklist'] = $this->Task_model->fetchtask();
    $data['edittask'] = $this->Task_model->get_task_by_id($tid, '', '');

    $data['target_name'] = '';
    $data['target_name'] = '';
    $data['present_company'] = '';
    $data['previous_company'] = '';
    $data['email_address'] = '';
    $data['home_address'] = '';
    $data['meeting_date_time'] = '';
    $data['comments_additional_info'] = '';
// print "<pre>";
//  print_r($data['edittask']);
//  exit();


    foreach($data['edittask'] as $task){

      // echo "<pre>";
      // print_r($task);
      // exit();

      $data['id'] = $task->id;
      $data['user_id'] = $task->user_id;
        $data['task_name'] = $task->name;
        $data['account_name'] = $task->account_name;
        $data['target_name'] = $task->target_name;
        $data['present_company'] = $task->present_company;
        $data['previous_company'] = $task->previous_company;
        $data['customer_email'] = $task->email;
        $data['home_address'] = $task->home_address;
        $data['meeting_date_time'] =  ($task->meeting_date_time !== NULL) ? date('m/d/Y h:i A',strtotime($task->meeting_date_time)) : "";
        $data['comments_additional_info'] = $task->comments_additional_info;
        $data['analyst_note'] = $task->analyst_note;
        $data['analyst_id'] = $task->analysist_id;
        //$data['customer_notes'] =$task->comments_additional_info;
        $data['no_of_connections'] = $task->no_of_connections;
        $data['status'] = $task->analyst_status_id;
        $data['created'] = date('m-d-Y h:i A',strtotime($task->created));
        $data['updated'] = date('m-d-Y h:i A',strtotime($task->updated));
    }
    

   
    $user_details = $this->Users_model->fetchdata($user_id);
    $log_details = $this->Task_model->logData($tid);
    $userattach = $this->Task_model->getAttachments($tid,1);
      $analyst_attachments = $this->Task_model->getAttachments($tid,0);

    $analyst_links = $this->Task_model->getLinks($tid);
    $task_created_user = $this->Users_model->fetchdata($task->user_id);
    $data['task_created_user'] = $task_created_user;
    $workLogs = $this->Task_model->workLogs($tid);
    $loghrs = $workLogs[0]->log_hrs;
    $vallogmins = $workLogs[0]->log_min;
    $hrs = intval($vallogmins/60);
    $min = intval($vallogmins % 60); 
    
    $data['email_to_notifiy'] = $user_details[0]->username;
    //$data['user_id'] = $user_details[0]->id;
    $data['first_name'] = $user_details[0]->first_name;
    $data['last_name'] = $user_details[0]->last_name;
    $data['email'] = $user_details[0]->username;
    $data['log'] = $log_details;
    $data['analyst_attachments'] = $analyst_attachments;
    $data['analyst_links'] =  $analyst_links;
    $data['customer_attachments'] = $userattach;
    $data['workLogs'] = ($loghrs + $hrs)." hour(s) and ".$min . " minute(s)";
    
    
    $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/task/edit',$data); 
    $this->load->view('admin/footer', $data);
    
   }

   /**
    * Function that runs while adding the work logs from the popup, triggered from js
    *
    * After this function is done we are submitting the Task Edit for also so task update action will also run
    */
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
          'created' =>  date("Y-m-d h:i:sa"),
          'modified' =>  date("Y-m-d h:i:sa"),
        );
      
       $insert = $this->Task_model->insertlog($data);
       redirect('admin/task/edit/'.$this->input->post('task_id'));  

    }

    /** 
     * Submit Action for Task Edit, If the Task Status is moved to Completed from task edit interface, We are showing a popup after that we are running this function and sending an email to customer
     */
   public function update($id){
    $data = $this->data;
   
    //$data['name'] = $this->session->userdata('name');
    $data['first_letter'] = $this->session->userdata('first_letter');
    

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
      
            if($_POST['links'] != 0){
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
          



  
        if($this->input->server('REQUEST_METHOD') === 'POST' && (isset($empty_file) && $empty_file == FALSE)){
         
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

                      redirect('admin/task/edit/'.$id);

                }
                
            }
 

 if(!empty($uploadData)){
                //Insert file information into the database
               
                $insert = $this->Task_model->insert_files($uploadData);
                
                $statusMsg = $insert?'Files uploaded successfully.':'Some problem occurred, please try again.';
                $this->session->set_flashdata('statusMsg',$statusMsg);
                 
              }
   
           
        }

       if($this->input->server('REQUEST_METHOD') === 'POST' && !empty($this->input->post()) ){  
if(!empty($this->input->post("meeting_date_time")) && ($this->input->post("meeting_date_time"))){
    $date = date("Y-m-d H:i:s", strtotime($this->input->post('meeting_date_time')));

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

              

        if($check_error == FALSE){

            $files_urls_to_remove = array( 'files_to_delete' => $this->input->post('removed-file-links'),
             'urls_to_delete' => $this->input->post('removed-url-links'));
                  $this->Task_model->delete_files_links($files_urls_to_remove);
            $update = $this->Task_model->update_task($analyst_update);

            $this->Task_model->delete_files_links($files_urls_to_remove);



          $this->session->set_flashdata('success_message',"Task has been successfully updated.");
        
           
            
            if(($update ) && ($customer_status == 5)){

            /*Mail sending Function will run here*/
           // $data['email'] = $this->input->post('email_address');;
               $task_id = $_POST['task_id'];

                $workLogs = $this->Task_model->workLogs($task_id);
                $loghrs = $workLogs[0]->log_hrs;
                $vallogmins = $workLogs[0]->log_min;
                $hrs = intval($vallogmins/60);
                $min = intval($vallogmins % 60);
                
    //$data['log'] = $log_details;
   

                $data['workLogs'] = ($loghrs + $hrs)." hour(s) and ".$min.' minute(s)';
   

                $task_details = $this->Task_model->get_task_by_id($task_id);

                $task_type = $task_details[0]->name;

                $target_name = $task_details[0]->target_name;
                     
                $user_id = $task_details[0]->user_id;

               
            $user_email = $this->Users_model->get_email_by_id( $user_id);


              $assistant_email_details = $this->Users_model->get_customer_assistant_details($user_id);


         
   

      $assistant_email = array();
    
        
      $admin_mails = $this->Users_model->admin_emails();
      foreach ($assistant_email_details as $key => $assistant_email_detail){
              
              if($assistant_email_detail->email_status== 1){
                
                $assistant_email[] = $assistant_email_detail->email;
                
             }
          
            }

            

      $cc = array_merge($assistant_email,$admin_mails);

   
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


            $data['attachments'] = $this->Task_model->getAttachments($task_id);

            
            /*Mail sending Function will run here*/
            $data['email'] = $user_email[0]->username;

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
            $message .= '<br>Note: <br>'.$task_details[0]->analyst_note."<br>";

          }
            $message .= "<br>Please do not hesitate to contact me for any questions or concerns & let me know if there is anything else we can provide you with.<br><br>Thank you for your business.</div>";

            $subject  = $target_name." ".$task_type;
            
            //$cc = $this->Users_model->admin_emails();
        
            
            $this->Users_model->send_mail($data,$message,$subject,$cc,NULL,$from_bda,$bda_in_bcc);
       
            }

       $this->session->set_flashdata('success_message',"Task has been successfully updated.");

            }

      }
        
           
redirect('admin/task/edit/'.$id);


    }


 /**
  * Inprogress Task listing from admin panel Includes filters
  */
 public function inprogress($reset = NULL){

  $data = $this->data;

  $user_id = $this->session->userdata('user_id');


  $data['get_all_customers']=$this->Users_model->fetch_users_by_role(2,$user_id,NULL,NULL);

  $data['get_all_team_members']=$this->Users_model->fetch_users_by_role(9,NULL,NULL,NULL);

$data['get_all_bdas']=$this->Users_model->fetch_users_by_role(3,$user_id,NULL,NULL);

$data['get_all_analyst']=$this->Users_model->fetch_users_by_role(4,$user_id,NULL,NULL);

$data['get_target_names']=$this->Task_model->get_target_name(NULL);



$data['tasklists'] = $this->Task_model->fetchtask();

$search_array = $this->input->post();


if(empty($search_array)) {
  $search_array = $this->session->userdata('task_inprogress_search');
  //echo '<pre>';
  //print_r($search_array);exit;
  if(isset($search_array['csv_export'])){
    $search_array['csv_export'] = NULL;
  }

}
else{
  $this->session->set_userdata('task_inprogress_search',$search_array);
}
  if((strpos($_SERVER['HTTP_REFERER'], base_url()."admin/task/inprogress") === false) || (isset($reset) && $reset == "reset")){

        $this->session->unset_userdata('task_inprogress_search');
        $search_array = false;
    }



   // $data['name'] = $this->session->userdata('name');
    $data['tasklist'] = $this->Task_model->fetchtask();
    $data['type_of_task'] = $this->uri->segment(3);
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
       $status = $this->Status_model->fetch_status("In Progress", "Task");
    $status_id = $status[0]->id;
    $this->load->library('pagination');
    $config['base_url'] = base_url().'admin/task/inprogress';
        $data['count_tasks']= $this->Task_model->count_task($user_id,$status_id,$search_array,"admin");



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
       // $this->pagination->initialize($config);
       


           if ($this->input->server('REQUEST_METHOD') === 'POST'){
         
          if($this->input->post('submit') == "Submit"){
             $search_target = $this->input->post('search_target');
          $data['search_target'] = $search_target;

          $search_bda = $this->input->post('search_analyst');
          $data['search_analyst'] = $search_bda;

           $search_bda = $this->input->post('search_bda');
          $data['search_bda'] = $search_bda;

          $search_team_member = $this->input->post('search_team_member');
          $data['search_team_member'] = $search_team_member;


          $search_task_type = $this->input->post('search_task_type');
          $data['search_task_type'] = $search_task_type;


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


             $search_bda =NULL;
             $data['search_analyst'] = "";


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

              $search_bda =NULL;
             $data['search_bda'] =  $search_array['search_bda'];

               $search_bda =NULL;
             $data['search_analyst'] = $search_array['search_analyst'];


              $search_team_member =NULL;
             $data['search_team_member'] = $search_array['search_team_member'];


             $search_meeting_date_time_start =NULL;
             $data['meeting_start_date'] = $search_array['meeting_start_date'];

             $search_meeting_date_time_to =NULL;
             $data['meeting_to_date'] =  $search_array['meeting_to_date'];
        }
        
        /*echo "<pre>";
        print_r($search_array);
        exit();*/

        
      $data['tasks'] = $this->Task_model->get_task('', $order_type, $config['per_page'],$limit_end,$user_id,$status_id,$search_array,'admin');


      
      
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



     $this->pagination->initialize($config);
   
 
    $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/task/list',$data);
    $this->load->view('admin/footer', $data);

 }

 public function all_reports($reset = NULL){

  $data = $this->data;

  $user_id = $this->session->userdata('user_id');

 $data['status_types'] =  $this->Status_model->fetch_status_by_type('Task');

$data['get_all_customers']= $this->Users_model->fetch_users_by_role(2,$user_id,NULL,NULL);

$data['get_all_team_members']=$this->Users_model->fetch_users_by_role(9,NULL,NULL,NULL);

$data['get_all_bdas']=$this->Users_model->fetch_users_by_role(3,$user_id,NULL,NULL);

$data['get_all_analyst']=$this->Users_model->fetch_users_by_role(4,$user_id,NULL,NULL);

$data['get_target_names']=$this->Task_model->get_target_name(NULL);



$data['tasklists'] = $this->Task_model->fetchtask();

$search_array = $this->input->post();

if(empty($search_array)) {
  $search_array = $this->session->userdata('all_reports_search');
  //echo '<pre>';
  //print_r($search_array);exit;
  if(isset($search_array['csv_export'])){
    $search_array['csv_export'] = NULL;
  }

}
else{
  $this->session->set_userdata('all_reports_search',$search_array);
}
  if((strpos($_SERVER['HTTP_REFERER'], base_url()."admin/task/all_reports") === false) || (isset($reset) && $reset == "reset")){

        $this->session->unset_userdata('all_reports_search');
        $search_array = false;
    }



   // $data['name'] = $this->session->userdata('name');
    $data['tasklist'] = $this->Task_model->fetchtask();
    $data['type_of_task'] = $this->uri->segment(3);
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

     if($this->session->userdata('all_reports_search') == null){

             $current_date_array['created_start_date'] = date("m/d/Y");
              $current_date_array['created_to_date'] = date("m/d/Y");
             
            }

            else{

                $current_date_array['created_start_date'] = null;
              $current_date_array['created_to_date'] = null;
            }


    $this->load->library('pagination');
    $config['base_url'] = base_url().'admin/task/all_reports';
        $data['count_tasks'] = $this->Task_model->count_task($user_id,NULL,$search_array,"admin",NULL,$current_date_array);

// echo "<pre>";
// print_r($search_array);
// exit();

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
       // $this->pagination->initialize($config);
       


           if ($this->input->server('REQUEST_METHOD') === 'POST'){
         
          if($this->input->post('submit') == "Submit"){
     

          $search_status = $this->input->post('search_status');
          $data['search_status'] = $search_status;

          $search_bda = $this->input->post('search_analyst');
          $data['search_analyst'] = $search_bda;

           $search_bda = $this->input->post('search_bda');
          $data['search_bda'] = $search_bda;


          $search_team_member = $this->input->post('search_team_member');
          $data['search_team_member'] = $search_team_member;


          $search_task_type = $this->input->post('search_task_type');
          $data['search_task_type'] = $search_task_type;


           $search_customer = $this->input->post('search_customer');
          $data['search_customer'] = $search_customer;

       $search_meeting_date_time_start= $this->input->post('created_start_date');
          $data['created_start_date'] = $search_meeting_date_time_start;

        $search_meeting_date_time_to = $this->input->post('created_to_date');
          $data['created_to_date'] = $search_meeting_date_time_to;
        }

  
          else{
        
              $search_status = NULL;
              $data['search_status'] = "";

              $search_task_type =NULL;
              $data['search_task_type'] = "";

              $search_team_member =NULL;
              $data['search_team_member'] = "";


              $search_team_member =NULL;
              $data['search_customer'] = "";


             $search_bda =NULL;
             $data['search_bda'] = "";


             $search_bda =NULL;
             $data['search_analyst'] = "";



             $search_bda =NULL;
             $data['search_bda'] = "";

              $search_meeting_date_time_start =NULL;
              $data['created_start_date'] = "";

              $search_meeting_date_time_to =NULL;
              $data['created_to_date'] = "";
          } 

        }
        else{

           $search_status = NULL;
           $data['search_status'] = $search_array['search_status'];

          $search_task_type =NULL;
             $data['search_task_type'] = $search_array['search_task_type'];


              $search_bda =NULL;
             $data['search_bda'] =  $search_array['search_bda'];

               $search_bda =NULL;
             $data['search_analyst'] = $search_array['search_analyst'];

              $search_customer =NULL;
             $data['search_customer'] = $search_array['search_customer'];

                $search_team_member =NULL;
             $data['search_team_member'] = $search_array['search_team_member'];

             //$search_meeting_date_time_start = $search_array['created_start_date'];
             //$data['created_start_date'] = "";
             if($this->session->userdata('all_reports_search') == null){
             $search_array['created_start_date'] = date("m/d/Y");
              $search_meeting_date_time_start = $search_array['created_start_date'];
              $data['created_start_date'] = $search_meeting_date_time_start;

             // $search_meeting_date_time_to = $search_array['created_to_date'];
               //$data['created_to_date'] = "";
              $search_array['created_to_date'] = date("m/d/Y");
              $search_meeting_date_time_to = $search_array['created_to_date'];
              $data['created_to_date'] = $search_meeting_date_time_to;
            }else{
              $search_meeting_date_time_start = $search_array['created_start_date'];
              $data['created_start_date'] = $search_meeting_date_time_start;

             $search_meeting_date_time_to = $search_array['created_to_date'];
              $data['created_to_date'] = $search_meeting_date_time_to;
            }


            
        }
        
      $data['tasks_summary'] = $this->Task_model->get_task('', $order_type,NULL,NULL,$user_id,NULL,$search_array,'admin');

      // echo "<pre>";
      // print_r($data['tasks_summary']);
      // exit();
  
        
      $data['tasks'] = $this->Task_model->get_task('', $order_type, $config['per_page'],$limit_end,$user_id,NULL,$search_array,'admin');

        //  echo "<pre>";
        // print_r($data['tasks']);
        // exit();

    $data['no_of_reports']= count($data['tasks_summary']);

    $data['no_of_completed_tasks'] = array();

    $data['no_of_connections_total'] = array();
      
        $users = array();

        $team_member_array = array();

        foreach($data['tasks_summary'] as $task)
        {

            if($task->status_id == 5){

                 $data['no_of_completed_tasks'][] =$task->status_id;
            }

           $data['no_of_connections_total'][] = $task->no_of_connections;

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
 $grand_total_hour = 0;
 $grand_total_min = 0;

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




 foreach($data['tasks_summary'] as $task){
$data['work_log'][$task->id] = $this->Task_model->workLogs($task->id);
}

$data['calculated_hours'] = array();
 $grand_total_hour = 0;
 $grand_total_min = 0;

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

          $grand_total_hour += $logged_hour;
          $grand_total_min += $logged_min;
 
}



        $grand_total_calculated_hours = floor($grand_total_min/60);
         $grand_total_calculated_min = $grand_total_min%60;
      
         $data['grand_total_hour'] =$grand_total_hour + $grand_total_calculated_hours;
         $data['grand_total_min'] = $grand_total_calculated_min;



}

else{

   $data['grand_total_hour'] = 0;
         $data['grand_total_min'] = 0;
}


  

     $this->pagination->initialize($config);
   
 
    $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/task/list',$data);
    $this->load->view('admin/footer', $data);

 }

 /**
  * Completed Task listing from admin panel Includes filters
  */
  public function completed($reset = NULL){

  $data = $this->data;

$user_id = $this->session->userdata('user_id');


$data['get_all_customers']=$this->Users_model->fetch_users_by_role(2,$user_id,NULL,NULL);

 $data['get_all_team_members']=$this->Users_model->fetch_users_by_role(9,NULL,NULL,NULL);

$data['get_all_bdas']=$this->Users_model->fetch_users_by_role(3,$user_id,NULL,NULL);

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
  if((strpos($_SERVER['HTTP_REFERER'], base_url()."admin/task/completed") === false) || (isset($reset) && $reset == "reset")){

        $this->session->unset_userdata('task_completed_search');
        $search_array = false;
    }
  /*elseif({
   $this->session->unset_userdata('task_completed_search');
  
  }*/

$search_task_type= $this->input->post('search_task_type');

$search_customer= $this->input->post('search_customer');

$search_bda= $this->input->post('search_bda');

$search_meeting_date_time= $this->input->post('meeting_date_time');


    //$data['first_letter'] = $this->session->userdata('first_letter');
    $search_target = $this->input->post('search_target');
     // $data['name'] = $this->session->userdata('name');
    $data['tasklist'] = $this->Task_model->fetchtask();
    $data['type_of_task'] = $this->uri->segment(3);
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
       $status = $this->Status_model->fetch_status("Completed", "Task");
    $status_id = $status[0]->id;
    $this->load->library('pagination');
    $config['base_url'] = base_url().'admin/task/completed';
        $data['count_tasks']= $this->Task_model->count_task($user_id,$status_id,$search_array, "admin");
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
        //$this->pagination->initialize($config);
        if ($this->input->server('REQUEST_METHOD') === 'POST'){
         
          if($this->input->post('submit') == "Submit"){
             $search_target = $this->input->post('search_target');
          $data['search_target'] = $search_target;
 

          $search_bda = $this->input->post('search_bda'); 

          $data['search_bda'] = $search_bda;

              $search_bda = $this->input->post('search_analyst');
          $data['search_analyst'] = $search_bda;

            $search_team_member = $this->input->post('search_team_member');
          $data['search_team_member'] = $search_team_member;


          $search_task_type = $this->input->post('search_task_type');
          $data['search_task_type'] = $search_task_type;


           $search_customer = $this->input->post('search_customer');
          $data['search_customer'] = $search_customer;


        
           $search_meeting_date_time_start = $this->input->post('meeting_start_date');
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
             $data['search_bda'] = $search_array['bda'];


             $search_bda =NULL;
             $data['search_analyst'] = "";

          
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

              $search_bda =NULL;
             $data['search_bda'] = $search_array['search_bda'];


             $search_bda =NULL;
             $data['search_analyst'] = $search_array['search_analyst'];



             $search_team_member =NULL;
             $data['search_team_member'] =  $search_array['search_team_member'];

             $search_meeting_date_time_start =NULL;
             $data['meeting_start_date'] =  $search_array['meeting_start_date'];


             $search_meeting_date_time_to =NULL;
             $data['meeting_to_date'] =  $search_array['meeting_to_date'];

        }
        
    

     $data['tasks'] = $this->Task_model->get_task('', $order_type, $config['per_page'],$limit_end,$user_id,$status_id,$search_array,'admin');

   
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



    $this->pagination->initialize($config);

    $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/task/list',$data);
    $this->load->view('admin/footer', $data);
 }


 

 /**
  * Deleted Task listing from admin panel Includes filters
  */
  public function deleted($reset = NULL){
  $data = $this->data;
    
  $user_id = $this->session->userdata('user_id');


$data['get_all_customers']=$this->Users_model->fetch_users_by_role(2,$user_id,NULL,NULL);


$data['get_all_bdas']=$this->Users_model->fetch_users_by_role(3,$user_id,NULL,NULL);

$data['get_all_analyst']=$this->Users_model->fetch_users_by_role(4,$user_id,NULL,NULL);

$data['get_all_team_members']=$this->Users_model->fetch_users_by_role(9,NULL,NULL,NULL);

$data['get_target_names']=$this->Task_model->get_target_name(NULL);

 $data['tasklists'] = $this->Task_model->fetchtask();

$search_task_type= $this->input->post('search_task_type');

$search_customer= $this->input->post('search_customer');

$search_bda= $this->input->post('search_bda');

$search_meeting_date_time= $this->input->post('meeting_date_time');


  
$search_target = $this->input->post('search_target');
   

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
  if((strpos($_SERVER['HTTP_REFERER'], base_url()."admin/task/deleted") === false) || (isset($reset) && $reset == "reset")){

        $this->session->unset_userdata('task_deleted_search');
        $search_array = false;
    }



 
    $data['tasklist'] = $this->Task_model->fetchtask();
    $data['type_of_task'] = $this->uri->segment(3);
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
       $status = $this->Status_model->fetch_status("Deleted", "Task");
    $status_id = $status[0]->id;
    $this->load->library('pagination');
    $config['base_url'] = base_url().'admin/task/deleted';
        $data['count_tasks']= $this->Task_model->count_task($user_id,$status_id,$search_target, "admin");

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
        //$this->pagination->initialize($config);
          if ($this->input->server('REQUEST_METHOD') === 'POST'){
         
          if($this->input->post('submit') == "Submit"){
             $search_target = $this->input->post('search_target');
          $data['search_target'] = $search_target;


          $search_bda = $this->input->post('search_bda');
          $data['search_bda'] = $search_bda;

              $search_bda = $this->input->post('search_analyst');
          $data['search_analyst'] = $search_bda;


            $search_team_member = $this->input->post('search_team_member');
          $data['search_team_member'] = $search_team_member;


          $search_task_type = $this->input->post('search_task_type');
          $data['search_task_type'] = $search_task_type;


           $search_customer = $this->input->post('search_customer');
          $data['search_customer'] = $search_customer;


         
           $search_meeting_date_time_start = $this->input->post('meeting_start_date');
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
             $data['search_analyst'] = "";


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
             $data['search_task_type'] =  $search_array['search_task_type'];

             $search_customer =NULL;
             $data['search_customer'] =  $search_array['search_customer'];


             $search_team_member =NULL;
             $data['search_team_member'] =  $search_array['search_team_member'];


              $search_bda =NULL;
             $data['search_bda'] = $search_array['search_bda'];


             $search_bda =NULL;
             $data['search_analyst'] = $search_array['search_analyst'];


            $search_meeting_date_time_start =NULL;
             $data['meeting_start_date'] = $search_array['meeting_start_date'];

             $search_meeting_date_time_to =NULL;
             $data['meeting_to_date'] = $search_array['meeting_to_date'];
        }

        $this->pagination->initialize($config);
        
     $data['tasks'] = $this->Task_model->get_task('', $order_type, $config['per_page'],$limit_end,$user_id,$status_id,$search_array,'admin');

    
       
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








// echo "<pre>";
// print_r($data);
// exit();
           
      //$tasks[0]->analyst_status_id;

    $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/task/list',$data);
    $this->load->view('admin/footer', $data);
 }


  /**
   * Order Report Listing Page
   */
   public function order_report(){

    $data = $this->data;
    $data['tasklists'] = $this->Task_model->fetchtask();

    $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/task/list',$data);
    $this->load->view('admin/footer', $data);
 }


/**
 * Task Summary Page
 */
public function summary($reset = NULL){

  $data = $this->data;
 

$user_id = $this->session->userdata('user_id');

$data['get_all_customers']=$this->Users_model->fetch_users_by_role(2,$user_id,NULL,NULL);

$data['get_all_bdas']=$this->Users_model->fetch_users_by_role(3,$user_id,NULL,NULL);



$data['get_all_analyst']=$this->Users_model->fetch_users_by_role(4,$user_id,NULL,NULL);

$search_customer= $this->input->post('search_customer');

$search_bda= $this->input->post('search_bda');

$search_start_date= $this->input->post('search_start_date');


$search_array = $this->input->post();

if(empty($search_array)) {
  $search_array = $this->session->userdata('task_summary_search'); 

}
else{
  $this->session->set_userdata('task_summary_search',$search_array);
}
  if((strpos($_SERVER['HTTP_REFERER'], base_url()."admin/task/summary") === false) || (isset($reset) && $reset == "reset")){

        $this->session->unset_userdata('task_summary_search');
        $search_array = false;
    }

   
    $data['tasklist'] = $this->Task_model->fetchtask();
    $data['type_of_task'] = $this->uri->segment(3);
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
       $status = $this->Status_model->fetch_status("All", "Task");
    $status_id = NULL;
    $this->load->library('pagination');
    $config['base_url'] = base_url().'admin/task/summary';
    $data['count_tasks']= count($this->Task_model->get_summary(null, NULL, NULL,NULL,$search_array));



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

           if ($this->input->server('REQUEST_METHOD') === 'POST'){
         
          if($this->input->post('submit') == "Submit"){
            

          $search_bda = $this->input->post('search_bda');
          $data['search_bda'] = $search_bda;


          
          $search_bda = $this->input->post('search_analyst');
          $data['search_analyst'] = $search_bda;


           $search_customer = $this->input->post('search_customer');
          $data['search_customer'] = $search_customer;


           $search_start_date = $this->input->post('search_start_date');
          $data['search_start_date'] = $search_start_date;

            $search_to_date = $this->input->post('search_to_date');
           $data['search_to_date'] = $search_to_date;
          }
          else{
           

           

             $search_customer =NULL;
             $data['search_customer'] = "";

             $search_bda =NULL;
             $data['search_bda'] = "";
            
              $search_bda =NULL;
             $data['search_analyst'] = "";
            
             $search_start_date =NULL;
             $data['search_start_date'] = "";
          } 

        }
        else{
       


        
             $search_customer =NULL;
             $data['search_customer'] = $search_array['search_customer'];

              $search_bda =NULL;
             $data['search_bda'] =  $search_array['search_bda'];

             $search_bda =NULL;
             $data['search_analyst'] =  $search_array['search_analyst'];

             $search_start_date =NULL;
             $data['search_start_date'] =  $search_array['search_start_date'];
        }
        

  
     
  $search_to_date = $this->input->post('search_to_date');
           $data['search_to_date'] = $search_to_date;

    

    /*Get all active users*/
$data['get_summary'] = $this->Task_model->get_summary($order=null, $order_type='Asc',$config['per_page'],$limit_end,$search_array);





    /*Loop them and get their team members and get their tasks*/
foreach($data['get_summary'] as $summary){
      //$members = $this->Users_model->get_team_members($user_id);


    $users = $this->Users_model->get_members_ids($summary->id);  
    $users[] = $summary->id;
    $data['get_all_tasks'][$summary->id] = $this->Task_model->get_all_tasks($users);

}

$data['total_hours'] = array();
if(!empty($data['get_all_tasks'])){

    /*Loop the tasks and get the work logs and number of connections*/
foreach ($data['get_all_tasks'] as $key => $task){

if(!empty($task)){

  $task_ids = array_map(function ($ar) {return $ar->task_id;}, $task);




$data['total_hours'][$key]['logged_details']= $this->Task_model->total_work_log($task_ids);

$number_of_connections  = $this->Task_model->get_number_of_connections($task_ids);

 if(isset($number_of_connections) && !empty($number_of_connections)){
  $total_number_of_connections = $number_of_connections;
}
else{
  $total_number_of_connections  = 0;
}

$data['total_hours'][$key]['total_number_of_reports'] = count($task_ids);
$data['total_hours'][$key]['total_number_of_connections'] = $total_number_of_connections;
}
else{
$data['total_hours'][$key]['logged_details']=array();
$data['total_hours'][$key]['total_number_of_reports'] = 0;
$data['total_hours'][$key]['total_number_of_connections'] =  0;
}
}


}




$data['calculated_hours'] = array();
if(!empty($data['total_hours'])){
  /**
   *Logic To Calculate hours and minutes
   * we are storing hours and minutes in two different fields
   * But while showing we are calculating the cumulative and showing the total work logs
   * 5 hours 69 minutes will be 6 hours and 9 minutes
   */
foreach ($data['total_hours'] as $user_id => $logged_hours)
{

// echo "<pre>";
// print_r(
// $data['total_hours']);
// exit();
      if(!empty($logged_hours['logged_details'])){
  
      $logged_hour = $logged_hours['logged_details'][0]->log_hrs;

      $logged_min = $logged_hours['logged_details'][0]->log_min;



      $calculated_hours = floor($logged_min/60);
      $calculated_min = $logged_min%60;

      $logged_hour +=$calculated_hours;
      $logged_min = $calculated_min;
    }
    else{
      $logged_hour = 0;
      $logged_min = 0;
    }

      $data['calculated_hours'][$user_id]['logged_hrs'] = $logged_hour;
      $data['calculated_hours'][$user_id]['logged_min'] = $logged_min;

}

// echo "<pre>";
// print_r($data);
// exit();
       

}

 

    $this->pagination->initialize($config);
 
    $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/task/summary',$data);
    $this->load->view('admin/footer', $data);
 }

/**
 * Change Analyst from admin interface
 * Sends an email
 */
 function change_analyst(){


    $data = $this->data;
    $task_id=$this->input->post('id');
    $analyst_id=$this->input->post('analyst');


    if($analyst_id)
    {
      $this->Task_model->change_analyst($analyst_id,$task_id);

      $user_details = $this->Users_model->fetchdata($analyst_id);
      $analyst_name = $user_details[0]->first_name;
      $data['email'] = $user_details[0]->username;

      $task_details = $this->Task_model->get_task_by_id($task_id);

      $data['bda_id'] = $task_details[0]->bda_id;

      if(!empty($data['bda_id'])){

      $user_details = $this->Users_model->fetchdata($data['bda_id'] );


      $bda_email = $user_details[0]->username;
    }

    else{

         $bda_email = NULL;

    }

      $subject = 'Task Assignment';

      if(!empty($task_details[0]->meeting_date_time)){

          $meeting_date = date('m-d-y h:i A',strtotime($task_details[0]->meeting_date_time));
        }
        else{

          $meeting_date = "Not mentioned";
        }

     $message = "Hi ".$analyst_name.",<br><br>
      The ".$task_details[0]->name." from ".$task_details[0]->account_name." has been assigned.<br> Date of meeting: ".$meeting_date."";

      //   echo "<pre>";
      // print_r($bda_email);
      // exit();

   //$cc_array = array();
   $cc = $this->Users_model->admin_emails();


 if(!empty($bda_email)){
   // $cc_array[] = $cc[0]->email;
   // $cc_array[] = $cc[1]->email;
   // $cc_array[] = $cc[2]->email;
   $cc[] = $bda_email;
 }



$this->Users_model->send_mail($data,$message,$subject,$cc,NULL,NULL);

    

      redirect('admin/task/inprogress');
    }
    else
    {


    $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/task/list',$data);
   
    $this->load->view('admin/footer', $data);

    }


 }

  /**
   * Validate function which runs while changing the analyst from the change analyst popup
   *
   */
   public function validate_analyst_url(){

      $post_values = $this->input->post();

  
       $this->load->library('form_validation');
       
       $this->form_validation->set_rules('analyst','Analyst', 'required');
       if ($this->form_validation->run() === FALSE) {
         $data = array('error_message_analyst' => form_error('analyst'));
         echo json_encode($data);
       }
       else{
          echo "Success";
       }
    }


}