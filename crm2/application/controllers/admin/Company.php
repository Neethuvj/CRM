<?php

/**
 * Functions for admin company controller
 * @package Controllers
 * @subpackage Admin
 */


class Company extends Admin_Controller {




  private $data = array(); 
	/**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
       
       
      $this->data = $this->user_data;
    

        if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }


    
    }


    /**
    *  Users who signed up as a company and the active Company Users List from admin panel on search the page redirects to the same page based on the post parameters passed we are filtering them
    */
    public function active(){
       
      
        $data =  $this->data;
        

        $search_array = $this->input->post();


         if ($this->input->server('REQUEST_METHOD') === 'POST'){
         
          if($this->input->post('submit') == "Submit"){
             $search_company = $this->input->post('search_company');
          $data['search_company'] = $search_company;


          }
          else{
            $search_company =NULL;
             $data['search_company'] = "";



          } 

        }

          else{
          $search_company = NULL;
          $data['search_company'] = "";

        }

      $company = $this->Users_model->get_active_company_list('',$search_array);

      $data['company_list']=$company;





        $this->load->view('admin/header', $data);
        $this->load->view('admin/sidebar');
        $this->load->view('admin/company/company_list',$data);
        $this->load->view('admin/footer',$data);

       
  }

   /**
    * Users who signed up as a company and the inactive Company Users List from admin panel on search the page redirects to the same page based on the post parameters passed we are filtering them
    */
    public function in_active(){

      
        $data =  $this->data;
          $search_array = $this->input->post();
      if ($this->input->server('REQUEST_METHOD') === 'POST'){
          if($this->input->post('submit') == "Submit"){
            $search_company = $this->input->post('search_company');
            $data['search_company'] = $search_company;
          }
          else{
            $search_company =NULL;
             $data['search_company'] = "";
          } 
        }
          else{
          $search_company = NULL;
          $data['search_company'] = "";
        }
    $company = $this->Users_model->get_inactive_company_list(NULL,$search_array);     
    $data['company_list']=$company;
          $this->load->view('admin/header', $data);
          $this->load->view('admin/sidebar');
          $this->load->view('admin/company/company_list',$data);
          $this->load->view('admin/footer', $data);
      }



   /**
    * On Clicking on Edit profile from above listing pages it will be redirecting to this page.
    * User Edit and update happens here 
    */
    public function user_edit($id){
    //echo $id;

    $user_id=$id;

    $data =  $this->data;
 
    $this->load->model('Users_model');
    $data['name'] = $this->session->userdata('name'); 
    $data['first_letter'] = $this->session->userdata('first_letter');
   // $user_id = $this->session->userdata('user_id');
    $user_details = $this->Users_model->fetchdata($id);

    
    
    $user_status_id = $user_details[0]->status_id;
    // echo "<pre>";
    // print_r($user_current_status_id);
    // exit();

    
    
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
    $data['city'] = $user_details[0]->city;
    $data['state'] = $user_details[0]->state;
    $data['phone_number'] = $user_details[0]->phone_number;
    $data['zip_code'] = $user_details[0]->zip_code;
    $data['company_name'] = $user_details[0]->company_name;
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
     $this->form_validation->set_rules('first_name','First Name','required');
     $this->form_validation->set_rules('last_name','Last Name','required');
    
     
     $this->form_validation->set_rules('street_name', 'Address', 'required');
     $this->form_validation->set_rules('city', 'City', 'required');
     $this->form_validation->set_rules('state', 'State', 'required');
     $this->form_validation->set_rules('zip_code', 'Zip', 'required');
       $this->form_validation->set_rules('company_name','Company Name','required');
     $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
    
     $this->form_validation->set_rules('phone_number', 'Phone Number', 'required',
            array(
                'required'      => 'The Phone Number field is required.'
        ));  
    if(!empty($this->input->server('REQUEST_METHOD') === 'POST')){

   
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
                




if ($this->form_validation->run() == FALSE)
                    {

   

                    }
                    else{

                        $submitted_values = $this->input->post();
$submitted_values['selected_plan_id'] = $this->
                        $user_details = $this->Users_model->updateuser($submitted_values);

           $this->session->set_flashdata('success_message',"User profile has been updated successfully.");


                        if($user_status_id == 1){
                          redirect('admin/company/active'); 

                      
                        }


                        if($user_status_id == 2){
                          redirect('admin/company/in_active');  
                        }
                        

    
                    }

    }
    $this->load->view('admin/header', $data);
    $this->load->view('admin/sidebar');
    $this->load->view('admin/company/edit_customer',$data);
    //$this->load->view('admin/customer/add');
    $this->load->view('admin/footer', $data);
   
   
  
 }

 


   /**
    * On Clicking on Team members icon from above listing pages it will be redirecting to this page.
    * Team member listing based on the team member status(active, inactive) list will display the users.
    * Page includes search filters too
    */

 public function team_members($id, $type)
 {
    $data =  $this->data;

    $user_id=$id;

    $user_details = $this->Users_model->fetchdata($user_id);


    $data['company'] = $user_details[0]->company;
    $data['plan_id'] = $user_details[0]->plan_id;
 
    $search_array = $this->input->post();

    $data['search_array']  = $search_array;

    $team_members=$this->Users_model->fetch_members($user_id,$type,$search_array,NULL,NULL);

     $data['team_members'] = $team_members;
   
    $data['total_team_members'] = $this->Users_model->fetch_members($user_id, $type);

   $this->load->view('admin/header', $data);
   $this->load->view('admin/sidebar');
   $this->load->view('admin/company/team_members',$data);
   $this->load->view('admin/footer', $data);

 }

  



}