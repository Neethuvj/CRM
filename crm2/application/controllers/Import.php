<?php

/**
 * This controller will contain mainly the codes for importing old contents to new contents
 * @package Controllers
 * @subpackage General
 */

class Import extends CI_Controller {

	public function __construct(){
	    parent::__construct();
	    $this->load->model('Users_model');
	    $this->load->model('Import_model');
	    $this->load->model('Plan_model');
	      $this->load->model('Status_model');
        if(!$this->session->userdata('is_logged_in')){
           redirect(PHASE1_URL, "refresh");
        }


	}
	/**
	 *  Url: import/customers
	 *  Logic
	 *    1. Fetches the Customers from the csv uploaded using fetch_customers functions
	 *    2. Loop the customers and get their actual details from the live db through fetch_user_from_old_id function
	 *    3. fetch the customer assitant details of the customer using fetch_customer_assistant_by_id
	 *    4. Generate the customer array for inserting 
	 *    5. If the customer is having team members do the step 2 - 5  for them recursivly
	 *    6. Insert the customer and team members using insert_customers function
	 */
	public function customers(){
		$old_customers = $this->Import_model->fetch_customers();

		
		
		$new_customer = array();
		$not_imported_user = "";
		foreach($old_customers as $key => $old_customer){
		
			
          	if($old_customer['plan'] == "Rainmaker"){
          		$plan_id = 1;
          		$customer_status_id = 18;
          	}
          	if($old_customer['plan']  == "Prospector"){
          		$plan_id = 2;
          		$customer_status_id = 18;
          	}
          	if($old_customer['plan']  == "Team"){
          		$plan_id = 3;
          		$customer_status_id = 18;
          	}
          	if(!empty($old_customer['from_db'])){

			$bda_for_customer = $this->Import_model->fetch_user_from_old_id($old_customer['from_db'][0]->TEAM_LEADID);

			$customer_assistant_for_customer = $this->Import_model->fetch_customer_assistant_by_id($old_customer['from_db'][0]->ASSISTANT_DETAILS);




				$customer_role_id = 2;
          	  $new_customer[$key] = $this->customer_array_generation($plan_id,$customer_role_id, $old_customer,$bda_for_customer,$customer_status_id);
          	  $new_customer[$key]['transaction_details']['request'] = "OLD Customer not imported"; 
          	 	//$transaction_date_string = substr($old_customer['billing'], 0,2);
          	 	$transaction_date = date("m/d/Y", strtotime($old_customer['billing']));
				//$transaction_date = date("d/m/y",strtotime());
			

          	  $new_customer[$key]['transaction_details']['transaction_date'] = $this->Plan_model->calculate_next_subscription_date($transaction_date);//$old_customer['billing'];
 	if(!empty($customer_assistant_for_customer)){
					foreach($customer_assistant_for_customer as $assistant_id => $assistant){
						$new_customer[$key]['customer_assistant'][$assistant_id] = $assistant;
						
					}
				}

          	  $new_customer[$key]['transaction_details']['status'] = 2;
          	  if(!empty($old_customer['team_members']['from_csv'])){
          		foreach($old_customer['team_members']['details'] as $member_id => $member_from_db){
          			$team_role_id = 9;

          			$team_member_status = 2;
          		  $new_customer[$key]['team_members'][$member_id] = $this->customer_array_generation($plan_id,$team_role_id,$member_from_db,$bda_for_customer, $team_member_status);
          		}

          	
          	  }		

          	  if($plan_id == 3){
          		$new_customer[$key]['user_plan']['plan_id'] = 3;
          		$new_customer[$key]['user_plan']['plan_amount_per_hour'] = str_replace("$","", $old_customer['rate']);
          		$new_customer[$key]['user_plan']['plan_hours'] = $old_customer['hours'];
          		$new_customer[$key]['user_plan']['total_plan_amount'] = ($new_customer[$key]['user_plan']['plan_amount_per_hour'] * $new_customer[$key]['user_plan']['plan_hours']);
           	  }
          	}
            else{
            	
	    	   $not_imported_user .= $old_customer['first_name'] . ' '.$old_customer['last_name']. "<br>";
	    	}

	    }
	 //   print "<pre>";
		// print_r($new_customer);
		// exit();

	 	$inserted_users = $this->Import_model->insert_customers($new_customer);
	 	print "inserted<br><pre>";
	 	print_r($inserted_users);
	 	print "</pre>Not Inserted<br>";
	 	print $not_imported_user;
	}

	/**
	 *  Private function that returns the customer array to be inserted 
	 *  @param int $plan_id
	 *  @param int $role_id
	 *  @param array $old_customer
	 *  @param int $bda_for_customer
	 *  @param int $status_id
	 */
	private function customer_array_generation($plan_id,$role_id,$old_customer,$bda_for_customer, $status_id){
			$length = 10;
    

		      $temp_token = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
		$new_customer = array();
			if(!empty($old_customer['from_db'])){
			$new_customer['user']['username'] = $old_customer['from_db'][0]->MAIL_ADDRESS;
			$new_customer['user']['password'] = $old_customer['from_db'][0]->PASSWORD;
			$new_customer['user']['role_id'] = $role_id;
			$new_customer['user']['status_id'] = $status_id;
			$new_customer['user']['created'] = date("Y-m-d H:i:s", strtotime(($old_customer['from_db'][0]->REGISTRATION_DATE !== NULL) ? $old_customer['from_db'][0]->REGISTRATION_DATE : "now"));
			$new_customer['user']['updated'] = date("Y-m-d H:i:s");
			$new_customer['user']['old_user_id'] = $old_customer['from_db'][0]->CUSTOMERID;
			
			if(isset($old_customer['team_members']['details']) && $old_customer['plan'] !== 'Team'){
				$new_customer['user']['company'] = 1;
			}
			


			$new_customer['user_details']['first_name'] = $old_customer['from_db'][0]->FIRST_NAME;
			$new_customer['user_details']['last_name'] = $old_customer['from_db'][0]->LAST_NAME;
			$new_customer['user_details']['phone_number'] = !empty($old_customer['from_db'][0]->PHONE) ? $old_customer['from_db'][0]->PHONE : "0123456789";
			$new_customer['user_details']['bda_id'] = $bda_for_customer;
			$new_customer['user_details']['address'] =$old_customer['from_db'][0]->ADDRESS1;
	$new_customer['user_details']['state'] = $old_customer['from_db'][0]->STATE;
	$new_customer['user_details']['city'] = $old_customer['from_db'][0]->CITY;
	$new_customer['user_details']['zip_code'] = $old_customer['from_db'][0]->ZIP;
	$new_customer['user_details']['plan_id'] =$plan_id;
		$new_customer['user_details']['on_board_date']  = isset($old_customer['from_db'][0]->ONBOARDING_DATE) ? date('Y-m-d', strtotime((string) $old_customer['from_db'][0]->ONBOARDING_DATE)) : "";
		$new_customer['user_details']['on_board_time']  = isset($old_customer['from_db'][0]->ONBOARDING_TIME) ? date('H:i:s a', strtotime((string) $old_customer['from_db'][0]->ONBOARDING_TIME)) : "";

				$new_customer['user_details']['temp_token']  = $temp_token;
if(isset($old_customer['team_members'][0]) && $old_customer['plan'] !== 'Team'){
         $new_customer['user_details']['company_name'] = $old_customer['from_db'][0]->FIRST_NAME . " ". $old_customer['from_db'][0]->LAST_NAME;
     }
 
//             'temp_token' => NULL,
			$new_customer['user_details']['created'] = date("Y-m-d H:i:s", strtotime((isset($old_customer['from_db'][0]->REGISTRATION_DATE) ? $old_customer['from_db'][0]->REGISTRATION_DATE : "now")));
			$new_customer['user_details']['updated'] = date("Y-m-d H:i:s");
		 
	     }
	   
	    return $new_customer;
	}

	/**
	 *  url: import/bda
	 *  Logic
	 *   1. Fetch the old Bdas from system
	 *   2. Generate the array to be inserted
	 *   3. insert to  db
	 */
	public function bda(){
		$old_bdas = $this->Import_model->fetch_bda();

		$new_bdas = array();

		foreach($old_bdas as $key => $old_bda){
			$new_bdas[$key]['user']['username'] = $old_bda->MAIL_ADDRESS;
			$new_bdas[$key]['user']['password'] = $old_bda->PASSWORD;
			$new_bdas[$key]['user']['role_id'] = 3;
			$new_bdas[$key]['user']['status_id'] = $old_bda->ACTIVE;
			$new_bdas[$key]['user']['created'] = date("Y-m-d H:i:s", strtotime(($old_bda->CREATED_DATE !== NULL) ?$old_bda->CREATED_DATE : "now"));
			$new_bdas[$key]['user']['updated'] = date("Y-m-d H:i:s");
			$new_bdas[$key]['user']['old_user_id'] = $old_bda->TEAM_LEADID;
			
			$new_bdas[$key]['user_details']['first_name'] = $old_bda->FIRST_NAME;
			$new_bdas[$key]['user_details']['last_name'] = $old_bda->LAST_NAME;
			$new_bdas[$key]['user_details']['phone_number'] = !empty($old_bda->PHONE) ? $old_bda->PHONE : "0123456789";
			$new_bdas[$key]['user_details']['analyst_order'] = NULL;
			$new_bdas[$key]['user_details']['created'] =date("Y-m-d H:i:s", strtotime(($old_bda->CREATED_DATE !== NULL) ?$old_bda->CREATED_DATE : "now"));
			$new_bdas[$key]['user_details']['updated'] = date("Y-m-d H:i:s");
		}

		$inserted_bdas = $this->Import_model->insert_old_support_team($new_bdas);
		print "<pre>";
		print_r($inserted_bdas);
		exit();
	}


	/**
	 *  url: import/account_manager
	 *  Logic
	 *   1. Fetch the old Account Manager from system
	 *   2. Generate the array to be inserted
	 *   3. insert to  db
	 */
	public function account_manager(){
		$old_account_managers = $this->Import_model->fetch_account_manager();

		$new_managers = array();

		foreach($old_account_managers as $key => $old_manager){

			$new_managers[$key]['user']['username'] = $old_manager->MAIL_ADDRESS;
			$new_managers[$key]['user']['password'] = $old_manager->PASSWORD;
			$new_managers[$key]['user']['role_id'] = 8;
			$new_managers[$key]['user']['status_id'] = $old_manager->ACTIVE;
			$new_managers[$key]['user']['created'] = date("Y-m-d H:i:s", strtotime(($old_manager->CREATED_DATE !== NULL) ? $old_manager->CREATED_DATE : "now"));
			$new_managers[$key]['user']['updated'] = date("Y-m-d H:i:s");
			$new_managers[$key]['user']['old_user_id'] = $old_manager->MANAGERID;

			
			$new_managers[$key]['user_details']['first_name'] = $old_manager->FIRST_NAME;
			$new_managers[$key]['user_details']['last_name'] = $old_manager->LAST_NAME;
			$new_managers[$key]['user_details']['phone_number'] = !empty($old_manager->PHONE) ? $old_manager->PHONE : "0123456789" ;
			$new_managers[$key]['user_details']['analyst_order'] = NULL;
			$new_managers[$key]['user_details']['created'] = date("Y-m-d H:i:s", strtotime(($old_manager ->CREATED_DATE !== NULL) ? $old_manager->CREATED_DATE : "now"));
			$new_managers[$key]['user_details']['updated'] = date("Y-m-d H:i:s");
		}

		$inserted_managers = $this->Import_model->insert_old_support_team($new_managers);
		print "<pre>";
		print_r($inserted_managers);
		exit();
	}


	/**
	 *  url: import/analyst
	 *  Logic
	 *   1. Fetch the old Bdas from the csv uploaded @ assets folder
	 *   2. Generate the array to be inserted (Set the password as firstname and analyst order)
	 *   3. insert to  db
	 */
	public function analyst(){


		$file = fopen('assets/SS360_analyst.csv', 'r');
		$count = 0;
		$analyst_order = 1;
		$contents = array();
		while (($line = fgetcsv($file)) !== FALSE) {
			$count++;
			if($count == 1){
				continue;
			}
			if($line[2] !== NULL && !empty($line[2]) && $line[2] == "Analyst"){
				$name_array = explode(" ", $line[0]);
				$contents[$analyst_order]['user']['username'] = $line[1];
				$contents[$analyst_order]['user']['password'] = md5(trim($name_array[0]));
				$contents[$analyst_order]['user']['role_id'] = 4;
				$contents[$analyst_order]['user']['status_id'] = 1;
				$contents[$analyst_order]['user']['created'] = date("Y-m-d H:i:s");
				$contents[$analyst_order]['user']['updated'] = date("Y-m-d H:i:s");
				$contents[$analyst_order]['user_details']['first_name'] = $name_array[0];
				$contents[$analyst_order]['user_details']['last_name'] = implode(" ", array_slice($name_array, 1));
				$contents[$analyst_order]['user_details']['phone_number'] = "0123456789";
				$contents[$analyst_order]['user_details']['analyst_order'] = $analyst_order;
				$contents[$analyst_order]['user_details']['created'] = date("Y-m-d H:i:s");
				$contents[$analyst_order]['user_details']['updated'] = date("Y-m-d H:i:s");

				$analyst_order++;
			}
		}
		$inserted_analysts = $this->Import_model->insert_old_support_team($contents);
		print "<pre>";
		print_r($inserted_analysts);
		exit();
	}

	/**
	 *  url: import/tasks
	 *  Logic
	 *    1. Fetches the Csv from the folder uploaded in assets 
	 *    2. Explodes the file name and take the file only if the file name starts with Task
	 *    3. Generates the work logs, and customer details from the csv
	 *    4. Creates the task array and work logs array needed
	 *    5. Rename the taken csv file so next time it will not be processed
	 *    6. Bulk insert the task using insert_task function
	 *
	 */
	public function tasks(){

		$files = scandir("assets/tasks-csv");
		$tasks = array();
		$tasks_not_imported = array();
		foreach($files as $file){
			$file_array = explode("_", $file);
			$count = 0;
			if($file_array[0] == "Task"){
				print $file;
				$file_to_open = fopen("assets/tasks-csv/".$file, "r" );
				while (($line = fgetcsv($file_to_open)) !== FALSE) {
					$count++;
					if($count == 1){
						$headers = $line;
						continue;
					}
					else{
						$task_object = array_combine($headers, $line);
						if(!empty($task_object['Task Id'])){
						    $customer_status_id = 5;
							$analyst_status_id = 8;
							$time = $task_object['Total Time in Minutes'];
							$hour = floor($time / 60);
							$min = $time % 60; 
							$customer = explode(" ", trim($task_object['Client Name']));
							if(!empty($customer[0]) && !empty($customer[1])){
								$customer = $this->Import_model->fetch_customer_by_name($customer[0], $customer[1]);
								$customer_id = $customer['id'];
								$customer_email = $customer['username'];
							}
							else{
								$customer_id = "";
							}

							$analyst = $this->Import_model->fetch_analyst_by_name($task_object['Analyst']);
							$task_type_id = $this->Import_model->fetch_task_type_id_from_name(trim($task_object['Task Type']));
							
							if(isset($customer_id) && !empty($customer_id) && !empty($task_type_id)){
								$links = array();
								$links[$task_object['Task Id']][] = $task_object['Related Links 1'];
								$links[$task_object['Task Id']][] = $task_object['Related Links 2'];
								$links[$task_object['Task Id']][]= $task_object['Google Link  1'];
								$links[$task_object['Task Id']][] = $task_object['Google Link  2'];
								$links[$task_object['Task Id']][] = $task_object['Google Link 3'];
								$tasks['tasks'][$count]['account_name'] = $task_object['Client Name'];
								$tasks['tasks'][$count]['target_name'] = $task_object['Target Name'];
								$tasks['tasks'][$count]['task_type_id']  = $task_type_id;
								$tasks['tasks'][$count]['old_task_id'] = $task_object['Task Id'];
								$tasks['tasks'][$count]['status_id'] = $customer_status_id;
								$tasks['tasks'][$count]['analyst_status_id'] = $analyst_status_id;
								$tasks['tasks'][$count]['no_of_connections'] = $task_object['No. of Connections Gathered'];
								$tasks['tasks'][$count]['created'] = date("Y-m-d H:i:s",strtotime($task_object['Date Completed']));
								$tasks['tasks'][$count]['updated'] = date("Y-m-d H:i:s",strtotime($task_object['Date Completed']));
								$tasks['tasks'][$count]['user_id'] = $customer_id;
								$tasks['tasks'][$count]['email'] = $customer_email;
								$tasks['tasks'][$count]['email_to_notifiy'] = $customer_email;
								$tasks['tasks'][$count]['analysist_id'] = $analyst;

								$tasks['work_logs'][$count]['old_task_id'] = $task_object['Task Id'];
								$tasks['work_logs'][$count]['log_date'] = date("Y-m-d", strtotime($task_object['Date Completed'])); 
								$tasks['work_logs'][$count]['log_hrs'] = $hour;
								$tasks['work_logs'][$count]['log_min'] = $min;
								$tasks['work_logs'][$count]['created'] = date("Y-m-d H:i:s",strtotime($task_object['Date Completed']));
								$tasks['work_logs'][$count]['modified'] = date("Y-m-d H:i:s",strtotime($task_object['Date Completed']));

								foreach($links as $key => $link){
									foreach($link as $id => $url){		
									  if(!empty($url)){
										$tasks['links'][] = array('user_id'=>$analyst,'old_task_id' => $task_object['Task Id'],'url' => $url,'created' => date("Y-m-d H:i:s",strtotime("now")),'modified' => date("Y-m-d H:i:s",strtotime("now")));
							     	 }
							       }
								}
							
							}
							else{
								$tasks_not_imported[] =$task_object;
							}
						}
						else{
							$tasks_not_imported[] =$task_object;
						}
					}
				}	
				
				$new_name = "Old_".$file;
				rename("assets/tasks-csv/".$file, "assets/tasks-csv/".$new_name);
				break;

			}	
		}

		$inserted_tasks = $this->Import_model->insert_tasks($tasks);

		$string = "";
		foreach($tasks_not_imported as $not_imported){
			$string .= implode(",",$not_imported) . "\r\n";
		}

		$myfile = fopen("assets/tasks_not_imported.csv", "a");

		fwrite($myfile, "\n". $string);
		fclose($myfile);
		print_r($inserted_tasks);

	}






}