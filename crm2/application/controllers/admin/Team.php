<?php

/**
 * Functions for admin team controller
 * @package Controllers
 * @subpackage Admin
 */

class Team extends Admin_Controller {


  public function __construct()
    {
        parent::__construct();
      
      $this->data = $this->user_data;
    
     // $this->data['sidebar_class'] =  "minified"; 


        if(!$this->session->userdata('is_logged_in')){
            redirect('/');
        }
    }


    /**
     * Pending Team member list
     */
  public function pending($reset = NULL){
	  $data = $this->data;
	  $plan_id = 3;
	  $role_id = 2;

    $status = $this->Status_model->fetch_status( 'Pending Team',"UserActivation");

    $status_id = $status[0]->id;
   $data['statuss'] = $this->Status_model->fetch_status_by_type('general');
   
    $search_array = $this->input->post();
    if(empty($search_array)) {
  $search_array = $this->session->userdata('team_pending_search'); 

}
else{
  $this->session->set_userdata('team_pending_search',$search_array);
}
  if((strpos($_SERVER['HTTP_REFERER'], base_url()."admin/team/pending") === false) || (isset($reset) && $reset == "reset")){

        $this->session->unset_userdata('team_pending_search');
        $search_array = false;
    }
    $data['search_array'] = $search_array;
		 $total = count($this->Users_model->fetch_users_by_role($role_id,$data['user_id'], NULL, NULL,NULL, $plan_id,$status_id,$search_array));
     $customer_list =  $this->Users_model->fetch_users_by_role($role_id,$data['user_id'], NULL, NULL,NULL, $plan_id,$status_id);

     $data['customer_list'] = $customer_list;

	    $this->load->library('pagination');

        $config['base_url'] = base_url().'admin/team/pending';
        
        $config['total_rows'] = $total;
        $config["per_page"] = 20;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);
         $data['user_list_type'] = "Pending";
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
	
		$team_owner_list = $this->Users_model->fetch_users_by_role($role_id,$data['user_id'], $config['per_page'], $limit_end,NULL, $plan_id,$status_id,$search_array);
 $data['user_list'] = $team_owner_list;

   $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/team/list',$data);
    $this->load->view('admin/footer', $data);

  }

  
    /**
     * Approved Team member list
     */

  public function approved($reset = NULL){

  		$data = $this->data;
      $plan_id = 3;
      $role_id = 2;
      $search_array = $this->input->post();

       if(empty($search_array)) {
          $search_array = $this->session->userdata('team_approved_search'); 

      }
  else{
    $this->session->set_userdata('team_approved_search',$search_array);
  }
  if((strpos($_SERVER['HTTP_REFERER'], base_url()."admin/team/approved") === false) || (isset($reset) && $reset == "reset")){

        $this->session->unset_userdata('team_approved_search');
        $search_array = false;
    }
    $data['search_array'] = $search_array;
    
   $data['statuss'] = $this->Status_model->fetch_status_by_type('general');
   $status_id = array();
    $status = $this->Status_model->fetch_status("Pending Activation", "UserActivation");
        $active_status = $this->Status_model->fetch_status("active", "general");
    $status_id[] = $status[0]->id;
    $status_id[] = $active_status[0]->id;
         $data['user_list_type'] = "Active";
         $total = count($this->Users_model->fetch_users_by_role($role_id,$data['user_id'], NULL, NULL,NULL, $plan_id,$status_id,$search_array));

          $customer_list =  $this->Users_model->fetch_users_by_role($role_id,$data['user_id'], NULL, NULL,NULL, $plan_id,$status_id);

     $data['customer_list'] = $customer_list;

        $this->load->library('pagination');

        $config['base_url'] = base_url().'admin/team/approved';
        
        $config['total_rows'] = $total;


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
    
        $team_owner_list = $this->Users_model->fetch_users_by_role($role_id,$data['user_id'], $config['per_page'], $limit_end,NULL, $plan_id,$status_id,$search_array);

        
 $data['user_list'] = $team_owner_list;

   $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/team/list',$data);
    $this->load->view('admin/footer', $data);

  }

  

   /**
    * Function while running on setting the plan amount and hours for the team user.
    * Runs as an ajax call in js
    */
    public function set_plan_amount(){

        if(!empty($this->input->post())){
          $submitted_values = $this->input->post();
          $status = $this->Status_model->fetch_status("Pending Activation", "UserActivation");
          $status_id = $status[0]->id;
          $user_details = $this->Users_model->fetchdata($submitted_values['customer_id']);
          $updated_team_plan_details = $this->Users_model->updated_team_plan_details($submitted_values,$status_id,$user_details[0]->username);
          if($updated_team_plan_details){
            $this->session->set_flashdata('success_message',"User details has been updated and a confirmation email has been sent to the customer.");
          }

          $redirect_url = $this->input->post('redirect_url');
         }
         else{
            $this->sesssion->set_flashdata('error_message', 'There has been some error.');  
            $redirect_url ="/admin/team/pending";  
         }


         redirect($redirect_url);
    }


  /**
    * Validation Function while running on setting the plan amount and hours for the team user.
    * Runs as an ajax call in js
    */    

    public function validate_team_plan_url(){


$post_values = $this->input->post();

  
       $this->load->library('form_validation');
       
       $this->form_validation->set_rules('plan_hours','Monthly Hours', 'required|numeric');
           $this->form_validation->set_rules('plan_amount','Hourly Rate', 'required|numeric');
       if ($this->form_validation->run() === FALSE) {
         $data = array('error_message_plan_hours' => form_error('plan_hours'),'error_message_plan_amount' => form_error('plan_amount'));
         echo json_encode($data);
       }
       else{
          echo "Success";
       }

    }



    


}