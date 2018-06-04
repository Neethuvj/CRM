<?php


/**
 * Functions for admin Referral controller
* @package Controllers
* @subpackage Admin
*/

class Referral extends Admin_Controller {
	/**
	 * Functions for admin Referral controller
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
	 *  Refferals by customer List, Includes filter actions too
	 */
	public function index($reset = NULL){

		$data = $this->data;
		$this->load->model('Users_model');
		$this->load->library('pagination');


		$config['base_url'] = base_url().'admin/referral/index';
		$search_array = $this->input->post();

   
if(empty($search_array)) {
  $search_array = $this->session->userdata('referral_search'); 

}
else{
  $this->session->set_userdata('referral_search',$search_array);
}

  if((strpos($_SERVER['HTTP_REFERER'], base_url()."admin/referral/index") === false) || (isset($reset) && $reset == "reset")){
 
        $this->session->unset_userdata('referral_search');
        $search_array = false;
    }

		$data['search_array'] = $search_array;
		$data['count_tasks']=count($this->Users_model->fetch_refferals('', NULL, NULL,NULL,$search_array));
		

		$config['total_rows'] = $data['count_tasks'];
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

		//$data['bda_list'] = $this->Users_model->fetch_users_by_role(3, $data['user_id']);
		$data['owner_list'] = $this->Users_model->fetch_users_by_role(2, $data['user_id']);

		 $data['member_list'] = $this->Users_model->fetch_users_by_role(9, $data['user_id']);

		 $data['customer_list'] = array_merge($data['owner_list'], $data['member_list']);
	
	

		$data['referrals_list'] = $this->Users_model->fetch_refferals('', $order_type, $config['per_page'],$limit_end,$search_array);

		$this->load->view('admin/header', $data);
		 $this->load->view('admin/sidebar'); 
		$this->load->view('admin/refferal/list',$data);
		$this->load->view('admin/footer', $data);
	}
	
	/**
	 *  Delete referral data (hard delete)
	 */
	public function delete_referral(){
	
		$referral_id = $this->input->post('delete_referral_id');
		//$transaction_id = $this->input->post('transaction_id');
		if(isset($referral_id) && !empty($referral_id)){
			$delete_referral =  $this->Users_model->delete_referral($referral_id);
			
			if($delete_referral){
				$this->session->set_flashdata('success_message',"The Referral has been deleted.");
			}
		}
		redirect('/admin/referral/index');
	}
	
	/**
	 *  Update referral's contacted status
	 */
	public function update_status(){
	
		$referral_id = $this->input->post('referral_id');
		$status = $this->input->post('status_id_selected');
		
		if(isset($referral_id) && isset($status) && $status != '' && !empty($referral_id)){
			$update_status =  $this->Users_model->update_referral_status($referral_id, $status);
				
			if($update_status){
				$this->session->set_flashdata('success_message',"The Referral's contacted status has been updated.");
			}
		}
		redirect('/admin/referral/index');
	}

	/**
	 *  Update referral's notes
	 */
	public function update_notes(){

		$data['referral_id'] = $this->input->post('referral_id');

		$data['current_notes'] = $this->input->post('current_notes');

	    $update_notes =  $this->Users_model->update_referral_notes($data);

	    	if($update_notes){
				$this->session->set_flashdata('success_message',"The Referral's notes has been updated.");
			}
	
		redirect('/admin/referral/index');
		

	}
}