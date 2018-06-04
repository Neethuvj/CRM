<?php



/**
 * Functions for Import Model
 * @package Model
 * 
 */
class Import_model extends CI_Model {

	private $another;

	function __construct()
	{
		parent::__construct();
		$this->another = $this->load->database('secondary_live',TRUE);
	}


	/**
	 * Function to fetch the active customers from the csv placed in assets folder and generates the array structure to be inserted in user and user_details table
	 * We are skipping if the Plan name is "Manual" and Empty in the excel
	 * This function also fetches the Team member details using recursive function fetch_customers_from_names
	 * Saves the given details from Excel in an array also fetches the actual details from live db and stores it in from_db key
	 */

	public function fetch_customers(){

		$file = fopen('assets/SS360_clients_missing.csv', 'r');
		$count = 0;
		$analyst_order = 1;

		$customers_to_import = array();
		while (($line = fgetcsv($file)) !== FALSE) {
			$count++;
			if($count == 1){
				continue;
			}
			else{

				if(!empty($line[0])){
					if($line[5] !== 'Manual' && !empty($line[5])){
						$customer_name = explode("/", trim($line[0]));
						foreach($customer_name as $names){
							$name = preg_split('/\s+/', trim($names));
							if(!empty($name)){
					
							if(count($name) > 2){
									$customers_to_import[$count]['firstname'][] = $name[0] . " ". $name[1];
							$customers_to_import[$count]['lastname'][] = $name[2];

							}
							else{
			$customers_to_import[$count]['firstname'][] = $name[0];
							$customers_to_import[$count]['lastname'][] = $name[1];

							}
							
						    }
						
						}

						$customers_to_import[$count]['plan'] = $line[2];
						$customers_to_import[$count]['rate'] = $line[3];
						$customers_to_import[$count]['hours'] = $line[4];
						$customers_to_import[$count]['billing_date'] = $line[5];
						$customers_to_import[$count]['team_members'] = $line[1];

					}
				}
			}
		}
		
		$customers_from_db = array();
		$team = array();
		foreach($customers_to_import as $key => $customers ){
		  /*This below function fetches actual details from live db*/
		  $query = $this->fetch_customers_from_names($customers, 0);
		  
		  $customers_from_db[$key]['first_name'] =  $customers['firstname'][0];
		  $customers_from_db[$key]['last_name'] = $customers['lastname'][0];
		  $customers_from_db[$key]['plan'] = $customers['plan'];
		   $customers_from_db[$key]['hours'] = $customers['hours'];
		  $customers_from_db[$key]['rate'] = $customers['rate'];
		  $customers_from_db[$key]['billing'] = $customers['billing_date'];
		 $customers_from_db[$key]['initial_activation_email_status'] = 0;
		  if(!empty($query)){
		  $customers_from_db[$key]['from_db'] = $query; 
		   /*If team member column is not empty, explode with comma and fetch the details from the live db and store it in team_members key*/
		  if(!empty($customers['team_members'])){
$customers_from_db[$key]['team_members']['from_csv'] = $customers['team_members'];
		      $team_members  = explode(",", trim($customers['team_members']));
			  foreach($team_members as $team_key => $team_member){

			  	$team_member =  preg_split('/\s+/', trim($team_member));
			  	$team['firstname'][] = $team_member[0];
			  	$team['lastname'][] = $team_member[1];
			 	
			  	$team_id = $query[0]->CUSTOMERID;
			  	 $members_from_db = $this->fetch_customers_from_names($team,$team_id);
			  	 	  $team = array();
			  	$customers_from_db[$key]['team_members']['details'][$team_key]['first_name'] =$team_member[0];
			  	$customers_from_db[$key]['team_members']['details'][$team_key]['last_name'] =$team_member[1];
			  	$customers_from_db[$key]['team_members']['details'][$team_key]['from_db'] = $members_from_db; 
			  }

		  		
			  }
		  }
		  else{

		  	$customers_from_db[$key]['from_db'] = array();
		  }


		}
		
		return $customers_from_db;
	}



	/**
	*  Fetches Current Live BDA's Access Analyst_team_lead table
	*/
	public function fetch_bda(){

	$this->another->select('ANALYST_TEAM_LEAD.*');
  	$this->another->from('ANALYST_TEAM_LEAD');
	$query = $this->another->get();
     return $query->result();
	}

	/**
	*  Fetches Current Live Account Managers Access account_manager table
	*/
	public function fetch_account_manager(){

	$this->another->select('ACCOUNT_MANAGER.*');
  $this->another->from('ACCOUNT_MANAGER');
	$query = $this->another->get();
     return $query->result();
	}


	/**
	*  Fetches analysts by name from our current db, used while importing the tasks, 
    * @param string $name
	*/
	public function fetch_analyst_by_name($name){
		
		$this->db->select("ss_users.id");
		$this->db->from("ss_users");
		$this->db->join("ss_user_details", 'ss_users.id = ss_user_details.user_id');
		$this->db->where('ss_users.role_id', 4);
		$this->db->where("ss_user_details.analyst_order", 0);
		$query = $this->db->get()->result();

		return $query[0]->id;
		
	}

	/**
	*	While Inserting Tasks Based on the Account Name Column we need to return the user id and username
	* @param string $first_name
	* @param string $last_name
	* @return mixed[id, username]
	*/
	public function fetch_customer_by_name($first_name, $last_name){
		$this->db->select("ss_users.*");
		$this->db->from("ss_users");
		$this->db->join("ss_user_details", 'ss_users.id = ss_user_details.user_id');
		$this->db->where('ss_users.role_id', 2);
		$this->db->where("ss_user_details.first_name", $first_name);
		$this->db->where("ss_user_details.last_name", $last_name);
		$query = $this->db->get()->result();
	
		if(!empty($query)){

		return array('id' => $query[0]->id, 'username' => $query[0]->username);
		}
		else{

			return ;
		}
	}

	/**
	*	While Inserting customers based on the old bda id we are returning new id 
	*/
	public function fetch_user_from_old_id($old_id){

		$this->db->select("ss_users.id");
		$this->db->from("ss_users");
		$this->db->where("ss_users.old_user_id", $old_id);
		$query = $this->db->get()->result();
		return $query[0]->id;
	}

	/**
	*	Inserting Support Team Common for BDA, Analyst, Account Manager 
	*/
	public function insert_old_support_team($data){

		$inserted_bdas = array();
		foreach($data as $bda){


				$this->db->insert('ss_users',$bda['user']);
			    $insert_id = $this->db->insert_id();

			    $inserted_bdas[] = $insert_id;
			    if($insert_id){
						$bda['user_details']['user_id'] = $insert_id;
			    	    $this->db->insert('ss_user_details',$bda['user_details']);
			    }
		}

		return $inserted_bdas;
	}

	/**
	* Inserting old customers and their team members to system With Status id as old customer
	* Tables Affected
    *    Users
    *    User_Details
    * 
	*/

	public function insert_customers($customers){
		
		$inserted_customers =array();
		foreach($customers as $customer){
			$customer['user']['initial_activation_email_status'] = 0;
			$this->db->insert('ss_users',$customer['user']);
			$user_id = $this->db->insert_id();
			if($user_id){
				$customer['user_details']['user_id'] = $user_id;
				$customer['transaction_details']['user_id'] = $user_id;
				if(isset($customer['user_plan'])){
					$customer['user_plan']['user_id'] = $user_id;
					$this->db->insert('ss_users_plans', $customer['user_plan']);
				}
				$inserted_customers[] = $user_id;
				$this->db->insert('ss_user_details', $customer['user_details']);
				$this->db->insert('ss_transaction_details', $customer['transaction_details']);
			}

			/*Inserting Customer Assistant*/
			if(!empty($customer['customer_assistant'])){
				foreach($customer['customer_assistant'] as $customer_assistant){
					 $this->db->insert('ss_customer_assistant_details', array(
					  		'name' => $customer_assistant->NAME, 
					  		'email' => $customer_assistant->EMAIL, 
					  		'user_id' => $user_id,
					  		'phone_number' =>$customer_assistant->PHONE,
					  		'created' => date("Y-m-d H:i:s",strtotime('now')),
					  		'updated' => date("Y-m-d H:i:s",strtotime('now'))));

				}


			}
			/*Inserting Team member*/
			if(!empty($customer['team_members'])){

				foreach($customer['team_members'] as $team_member){
					if(isset($team_member['user']) && $team_member['user'] !== NULL){
					$this->db->insert('ss_users',$team_member['user']);
					$team_member_id = $this->db->insert_id();
					if($team_member_id){
					   $team_member['user_details']['user_id'] = $user_id;
					   $this->db->insert('ss_user_details', $team_member['user_details']);
					  $this->db->insert('ss_users_members', array(
					  		'user_id' => $user_id, 
					  		'member_id' => $team_member_id, 
					  		'created' => date("Y-m-d H:i:s",strtotime('now')),
					  		'updated' => date("Y-m-d H:i:s",strtotime('now'))));
				    }
				   }
				}
			}
		}
		return $inserted_customers;
	}



	/**
	* Gets the Task type id based on the task type name in the csv,
    * 
	*/
	public function fetch_task_type_id_from_name($name){

		$this->db->select('ss_task_type.id');
		$this->db->from('ss_task_type');
		$this->db->where('ss_task_type.old_name', $name);
		$result = $this->db->get()->result();

		if(!empty($result)){
		  return $result[0]->id;
	    }
	    else{
	    	return;
	    }
	}


	/**
	* Inserting Tasks
	* First This bulk Inserts the given Details to the following tables
	* ss_tasks,ss_work_logs,ss_links
	* After that We are fetching the Other Details about the tasks from live db based on the task id, and we are doing a bulk update on the above tables
	* 
	*/
	public function insert_tasks($tasks){
		$task_ids = array();
			$this->db->insert_batch('ss_tasks',$tasks['tasks']);
			$this->db->insert_batch('ss_work_logs',$tasks['work_logs']);
			$this->db->insert_batch('ss_links',$tasks['links']);
			

		$this->db->select('ss_tasks.id, ss_tasks.old_task_id');
		$this->db->from("ss_tasks");
		$tasks = $this->db->get()->result();
		$new_ids_to_update = array();
		$old_ids_to_update  = array();
		foreach($tasks as $task){
			$old_ids_to_update[] = $task->old_task_id;
			$new_ids_to_update[]= array('task_id'=>$task->id,'old_task_id'=> $task->old_task_id);

		}

	 	$updated_tasks = $this->fetch_old_tasks_from_old_db($old_ids_to_update);
	 

	 	$this->db->update_batch('ss_tasks',$updated_tasks,'old_task_id');
  		$this->db->update_batch('ss_work_logs',$new_ids_to_update,'old_task_id');
  		$this->db->update_batch('ss_links',$new_ids_to_update,'old_task_id');
		return $new_ids_to_update;

	}

	/**
	 * Returns the following Task fields from live db
	 * Present Company
	 * Previous Company
	 * Home Address
	 * Target Name
	 * Meeting Date Time
	 * Comments
	*/
	private function fetch_old_tasks_from_old_db($tasks){
	$this->another->select('TASK.*');
	$this->another->from("TASK");
	$this->another->where_in("TASK.TASKID", $tasks);
	$old_tasks = $this->another->get()->result();
	$tasks_to_return = array();
	foreach($old_tasks as $key => $task){
		$tasks_to_return[$key]['old_task_id'] = $task->TASKID;
		$tasks_to_return[$key]['home_address'] = $task->ADDRESS;
		$tasks_to_return[$key]['present_company'] = $task->PRESENT_COMPANY;
		$tasks_to_return[$key]['previous_company'] = $task->PRESENT_COMPANY;
		$tasks_to_return[$key]['comments_additional_info'] = $task->COMMENTS;
		$tasks_to_return[$key]['meeting_date_time'] = $task->TIME_OF_MEETING ? date("Y-m-d H:i:s", strtotime($task->TIME_OF_MEETING)): "";

		
	}
	return $tasks_to_return;
	}

	/**
	 * Returns the customer details from live db 
	*/

	private function fetch_customers_from_names($customers, $teamid){
		// print_r($customers);
		// exit();
		$this->another->select('CUSTOMER.*');
		$this->another->select('CUSTOMER_INFO.*');
		$this->another->select('CUSTOMER_DETAILS.*');
		$this->another->select('CUSTOMER_PROCESS.*');
		$this->another->from('CUSTOMER');
		$this->another->join('CUSTOMER_INFO', 'CUSTOMER.CUSTINFO = CUSTOMER_INFO.ID');
		$this->another->join('CUSTOMER_DETAILS', 'CUSTOMER.CUSTOMERDETAILS = CUSTOMER_DETAILS.ID');
		$this->another->join('CUSTOMER_PROCESS', 'CUSTOMER.CUSTOMERPROCESS = CUSTOMER_PROCESS.PROCESSID');
		$this->another->where_in('CUSTOMER.FIRST_NAME', $customers['firstname']);
		$this->another->where_in('CUSTOMER.LAST_NAME', $customers['lastname']);
		$this->another->where_in('CUSTOMER.TEAMID', $teamid);
		$query = $this->another->get()->result();

		return $query;


	}

	public function fetch_customer_assistant_by_id($ids){

		$id = explode(",", $ids);

		if(!empty($id)){
		$this->another->select('CUSTOMER_ASSISTANT.*');
		$this->another->from('CUSTOMER_ASSISTANT');
				$this->another->where_in('CUSTOMER_ASSISTANT.ID', $id);
				$query = $this->another->get()->result();
				return $query;
		}
		else{
			return ;
		}
		return $id; 
	} 


	

}