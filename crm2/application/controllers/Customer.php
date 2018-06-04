<?php
/**
 *  This controller is used mainly for the BDA Interface
 *
 * @package Controllers
 * @subpackage General
 */

class Customer extends My_Controller {


public function __construct()
    {
        parent::__construct();
      
      $this->data = $this->user_data;
    
     $this->data['sidebar_class'] =  "minified";

     $this->data['switch_from']=$this->session->userdata('switch_from');
 


        if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }
    }

 /**
  * Listing of All Active Customers 
  */
 public function index($reset = NULL){

  $data = $this->data;
    $this->load->model('Users_model');
    $this->load->library('pagination');

    $data['user_list_type'] = "Active";
    $config['base_url'] = base_url().'customer/index';
    $user_id=  $this->session->userdata('user_id');
 
   
    $plans = $this->Users_model->get_all_plans();
      $search_array = $this->input->post();

    if(empty($search_array)) {
  
  $search_array = $this->session->userdata('customer_search'); 
  

        }
    else{
  $this->session->set_userdata('customer_search',$search_array);
        }
     
  if((strpos($_SERVER['HTTP_REFERER'], base_url()."customer/index") === false) || (isset($reset) && $reset == "reset")){

        $this->session->unset_userdata('customer_search');
        $search_array = false;
    }

       $data['count_tasks']=count($this->Users_model->fetchusers('', NULL, NULL,NULL,$search_array, 1,$user_id));

// echo "<pre>";
// print_r($data['count_tasks']);
// exit();
    $data['plans'] = $plans;
    $config['total_rows'] = $data['count_tasks'];
    $config["per_page"] = 20;
    $data['statuss'] = $this->Status_model->fetch_status_by_type('general');
   
        $this->load->library('pagination');
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

   

     $data['customer_list'] = $this->Users_model->fetch_customers_for_bda(2,$user_id);


  

    $data['users_list'] = $this->Users_model->customers_for_bda($user_id,'', $order_type, $config['per_page'],$limit_end,$search_array);

  //   echo "<pre>";
  // print_r($data);
  // exit();

    
    $this->load->view('header', $data);
    $this->load->view('sidebar');
    $this->load->view('customer/list',$data);
    $this->load->view('footer', $data);
 }


  /**
  * Listing of All Inactive Customers 
  */
  public function in_active($reset = NULL){

  $data = $this->data;

    $user_id=  $this->session->userdata('user_id');

    $config['base_url'] = base_url().'customer/index';

     $search_array = $this->input->post();

     if(empty($search_array)) {
  
  $search_array = $this->session->userdata('customer_inactive_search'); 
  

        }
    else{
  $this->session->set_userdata('customer_inactive_search',$search_array);
        }
     
  if((strpos($_SERVER['HTTP_REFERER'], base_url()."customer/in_active") === false) || (isset($reset) && $reset == "reset")){

        $this->session->unset_userdata('customer_inactive_search');
        $search_array = false;
    }


      $data['count_tasks']=count($this->Users_model->fetchusers('', NULL, NULL,NULL,$search_array, 2,$user_id));
    //$data['sidebar_class'] =  "minified"; 
    $data['user_list_type'] = "In Active";
    $plans = $this->Users_model->get_all_plans();
    $data['plans'] = $plans;
    $config['total_rows'] = $data['count_tasks'];
    $config["per_page"] = 20;
    $status = $this->Status_model->fetch_status("inactive", "general");
    $status_id = (int) $status[0]->id;

    $data['statuss'] = $this->Status_model->fetch_status_by_type('general');
   
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

 
   
     $data['customer_list'] = $this->Users_model->fetch_customers_for_bda(2,$user_id);




    $data['users_list'] = $this->Users_model->customers_for_bda($user_id,'', $order_type, $config['per_page'],$limit_end,$search_array,$status_id );

       

     
    $this->load->view('header', $data);
    $this->load->view('sidebar');
    $this->load->view('customer/list');
    $this->load->view('footer', $data);
 }



  



  
  
    /**
     * Customer Info form for the customers listing in Bda Interface
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
    // echo "<pre>";
    // print_r($user_details);
    // echo "</pre>";
   
    $data['error_class'] = 'form-group has-feedback has-error';
    $data['error_less_class'] = "form-group";
    $data['id'] = $user_details[0]->id;
    $data['first_name'] = $user_details[0]->first_name;
    $data['last_name'] = $user_details[0]->last_name;
    $data['street_name'] = $user_details[0]->address;
    $data['company_name'] = $user_details[0]->company_name;
    $data['city'] = $user_details[0]->city;
    $data['state'] = $user_details[0]->state;
    $data['phone_number'] = $user_details[0]->phone_number;
    $data['zip_code'] = $user_details[0]->zip_code;
    $data['on_board_date'] =  ($user_details[0]->on_board_date !== NULL) ? date("m/d/Y", strtotime($user_details[0]->on_board_date)) : "";
  
    $data['on_board_time'] = ($user_details[0]->on_board_time !== NULL) ? date("g:i a", strtotime($user_details[0]->on_board_time)) : "";

    

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
    }
    $data['assistant'][] = $array;
  
    $data['role'] =  $this->session->userdata('role_id');


      $this->load->library('form_validation');
     $this->form_validation->set_rules('first_name', 'First Name', 'required');
     $this->form_validation->set_rules('last_name', 'Last Name', 'required');
     $this->form_validation->set_rules('street_name', 'Address', 'required');
     $this->form_validation->set_rules('city', 'City', 'required');
     $this->form_validation->set_rules('state', 'State', 'required');
     $this->form_validation->set_rules('zip_code', 'Zip', 'required');
     $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
     $this->form_validation->set_rules('phone_number', 'Phone Number', 'required',
            array(
                'required'      => 'This %s field is required.'
        )); 

         if($user_details[0]->company == 1){
  
             $this->form_validation->set_rules('company_name','Company', 'required'); 
         }
    if(!empty($this->input->server('REQUEST_METHOD') === 'POST')){

   
    $data['first_name'] = ($this->input->post('first_name') != '') ? $this->input->post('first_name') :  "";

    $data['last_name'] = ($this->input->post('last_name') != '') ? $this->input->post('last_name') :  "";
    $data['email'] = ($this->input->post('email') != '') ? $this->input->post('email') : "";
    $data['id'] = ($this->input->post('id') != '') ? $this->input->post('id') :  "";
    $data['street_name'] = ($this->input->post('street_name') != '') ? $this->input->post('street_name') : "";

    $data['city'] = ($this->input->post('city') != '') ? $this->input->post('city') :  "";
    $data['state'] = ($this->input->post('state') != '') ? $this->input->post('state') : "";
    $data['phone_number'] = ($this->input->post('phone_number') != '') ? $this->input->post('phone_number') : "";
    $data['zip_code'] = ($this->input->post('zip_code') != '') ? $this->input->post('zip_code') :  "";
    $data['on_board_date'] = ($this->input->post('on_board_date') != '') ? $this->input->post('on_board_date') :  "";
    $data['on_board_time'] = ($this->input->post('on_board_time') != '') ? $this->input->post('on_board_time') :  ""; 
    $data['more_info'] =  $this->input->post('more_info');
    $data['assistant_id'] = ($this->input->post('assistant_id') != '') ? $this->input->post('assistant_id') :  "";
    $data['assistant_name'] = ($this->input->post('assistant_name') != '') ? $this->input->post('assistant_name') :  "";
    $data['assistant_email'] = ($this->input->post('assistant_email') != '') ? $this->input->post('assistant_email') : "";
    $data['assistant_phone'] = ($this->input->post('assistant_phone') != '') ? $this->input->post('assistant_phone') : "";
    $data['delete_assistant'] = ($this->input->post('delete_assistant') != '') ? $this->input->post('delete_assistant') :  "";
                




if ($this->form_validation->run() == FALSE)
                    {

   

                    }
                    else{

                        $submitted_values = $this->input->post();
                        
                        $user_details = $this->Users_model->updateuser($submitted_values);
                        $this->session->set_flashdata('success_message',"User profile has been successfully updated.");

                        if($user_current_status_id == 1){
                          redirect('customer/index');  
                        }
                        if($user_current_status_id == 2){
                          redirect('customer/in_active');  
                        }
                        
                    }

    }
    $this->load->view('header', $data);
    $this->load->view('sidebar');
    $this->load->view('customer/edit_customer',$data);
   
    $this->load->view('footer', $data);
   
   
  
 }

/**
*  Customer Summary For the customers listed in bda interface
* Based on the Customer id and Task Status id we are displaying tasks
* i.e 
*    1. customer/user_summary/1/completed
*    2. customer/user_summary/1/in_progress
*   @param int $id  
*   @param string $task_status_id
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


// print_r($user_id);
// exit();

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
     
  if((strpos($_SERVER['HTTP_REFERER'], base_url().'customer/user_summary/'.$id.'/'.$task_status_id) === false) || (isset($reset) && $reset == "reset")){

        $this->session->unset_userdata('customer_summary_search');
        $search_array = false;
    }

    $data['search_array'] = $search_array;
// exit();
   // $data['name'] = $this->session->userdata('name');
    $data['tasklist'] = $this->Task_model->fetchtask();

    $data['get_target_names']=$this->Task_model->get_target_name(NULL);
    $uri = $this->uri->segment(2);
    //print_r($uri);
    $segment = explode('_', $uri);
    // print_r($segment[1]);
    // exit();
     $data['type_of_task']= $segment[1];
     $user_details = $this->Users_model->fetchdata($user_id);

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
    $config['base_url'] = base_url().'customer/user_summary/'.$id.'/'.$task_status_id;
        $data['count_tasks']= $this->Task_model->count_task($user_id,$status_id,$search_array);
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
       $page = 1;
       $page = $this->uri->segment(5);
      
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
        $this->load->view('header', $data);
        $this->load->view('sidebar');
        $this->load->view('customer/summary', $data); 
        $this->load->view('footer', $data);

 }

    /**
     * Listing of customers team members in the BDA interface
     * customers/team_members/1/active
     * customers/team_members/1/in_active
     * @param int $id
     * @param string $type
     */
  public function team_members($id, $type)
 {
    $data =  $this->data;

    $user_id=$id;
   
    $search_array = $this->input->post();

    $data['search_array']  = $search_array;

    $team_members=$this->Users_model->fetch_members($user_id,$type,$search_array,NULL,NULL);


     $data['team_members'] = $team_members;
   

    $data['total_team_members'] = $this->Users_model->fetch_members($user_id, $type);


   $this->load->view('header', $data);
   $this->load->view('sidebar');
   $this->load->view('customer/team_members',$data);
   $this->load->view('footer',$data);

 }



   


}