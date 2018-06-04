<?php

/**
 * Functions for User Model
 * @package Model
 * 
 */
require_once(APPPATH.'libraries/authnet/vendor/autoload.php');
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Mailgun\Mailgun;


define("AUTHORIZENET_LOG_FILE", "application/logs/phplog");

class Users_model extends CI_Model {
    /**
    * Validate the login's data with the database
    * @param string $user_name
    * @param string $password
    * @return void
    */
	function validate($user_name, $password)
	{
	
	    $limit = 1;
	    $this->db->select('ss_users.*');
	    $this->db->select('ss_user_details.first_name, ss_user_details.plan_id,ss_user_details.notification_hour');
	    $this->db->from('ss_users');
	    $this->db->join('ss_user_details', 'ss_users.id = ss_user_details.user_id');
	    $this->db->where('ss_users.username',$user_name);
	    $this->db->where('ss_users.password',$password);
	    $this->db->where('ss_users.status_id',1);
	    $query=$this->db->get();
	    //$query = $this->db->get_where('ss_users', array('username' => $user_name, 'password' => $password), $limit);
	    //var_dump($query);
	
	   if($query -> num_rows() == 1)
	   {
	     return $query->result();
	   }
	   else
	   {
	     return false;
	   }	
	}
	
	
	
	
	
	function validate_username($user_name)
	{
		$limit = 1;
		$this->db->select('ss_users.*');
		$this->db->select('ss_user_details.first_name');
		$this->db->from('ss_users');
		$this->db->join('ss_user_details', 'ss_users.id = ss_user_details.user_id');
		$this->db->where('ss_users.username',$user_name);
		$this->db->where('ss_users.status_id',1);
		$query=$this->db->get();
		
		
		if($query -> num_rows() == 1)
		{
		
			return $query->result();
		}
		else
		{
			return false;
		}
	}


  /**
   *
   *On Login Inserting in to login details table
   * @param array $data
   * @return in id
   */
  public function login_log($data){
    $query = $this->db->insert('ss_login_details', $data);


    
  return $query;

  }

  /**
   *
   *On Logout update the login details table with logout time
   *
   * @param array $data
   * @return in void
   */
  public function logout_log($data){

    $this->db->set('logout_time', $data['logout_time']);
    $this->db->where('user_id', $data['user_id']);
    $this->db->where('logout_time', NULL);
    $this->db->update('ss_login_details');
  }

  /**
   *Function to get login history used in admin/configuration/login_history
   * @param string $limit_start
   * @param string $limit_end
   * @return array $results
   *
   */

  public function get_login_history( $limit_start = NULL, $limit_end = NULL){

    $this->db->select("login_details.*", FALSE);
    $this->db->select('user_details.first_name');
    $this->db->from('ss_login_details as login_details');
    $this->db->join('ss_user_details as user_details', 'user_details.user_id = login_details.user_id', 'left');

     if(($limit_start !== NULL) && ($limit_end !== NULL)){
          $this->db->limit($limit_start, $limit_end); 
        }
    $this->db->order_by('login_details.id', 'desc');
   $results = $this->db->get()->result();
   return $results;
  }



   /**
   * Generate a random password
   * 
   * @param void
   * @return array random password
   *
   */

  public function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];           
        }
        return implode($pass); //turn the array into a string
  }


  /**
   * Function to get the User details from token, usefull while activation by user
   *  @param string token
   *  @return mixed[id,username,password,status_id,role_id,first_name,last_name, *address,phone_number,zip_code,on_board_date,on_board_time,created,updated, *bda_id, analyst_order,plan_id,city,state,more_info,assistant_id,assistant_name,assistant_email,assistant_phone]
   */
  public function get_user_details_from_token($token){

       $this->db->select('a.*',FALSE);
    $this->db->select('b.first_name, b.last_name, b.address, b.phone_number, b.zip_code, b.on_board_date, b.on_board_time, b.created, b.updated, b.bda_id, b.analyst_order, b.plan_id, b.city, b.state, b.more_info',FALSE);
    $this->db->select('c.id as assistant_id, c.name as assistant_name, c.email as assistant_email, c.phone_number as assistant_phone',FALSE);
    $this->db->from('ss_users as a');
    $this->db->join('ss_user_details as b', 'a.id = b.user_id', 'left');
    $this->db->join('ss_customer_assistant_details as c', 'a.id = c.user_id', 'left');
    $this->db->where('b.temp_token',$token);
    $query=$this->db->get();
   // $query = $this->db->get_where('ss_user_details', array('user_id' => $user_id));
  
    return $query->result();
  }


  /**
   *
   *
   * Get user details from user id used in lot of places
   *  @param int $user_id
   *
   *  @return mixed[id,username,password,status_id,role_id,first_name,last_name, *address,phone_number,zip_code,on_board_date,on_board_time,created,updated, *bda_id, analyst_order,plan_id,city,state,more_info,assistant_id,assistant_name,assistant_email,assistant_phone,temp_token,company_name,monthly_usage,notification_hour,more_info]  
   */
  function fetchdata($user_id)
  {


    $this->db->select('a.*',FALSE);
    $this->db->select('ss_status.name as status_name');

    $this->db->select('b.first_name, b.last_name, b.address, b.phone_number, b.zip_code, b.on_board_date, b.on_board_time, b.created, b.updated, b.bda_id, b.analyst_order, b.plan_id, b.user_id, b.city, b.state, b.more_info, b.notification_hour, b.monthly_usage,b.company_name,b.temp_token,b.email_notification_status',FALSE);

    $this->db->select('c.id as assistant_id, c.name as assistant_name, c.email as assistant_email, c.phone_number as assistant_phone,c.email_status',FALSE);
    $this->db->select('d.transaction_id',FALSE);
    $this->db->select('d.transaction_date');
    $this->db->from('ss_users as a');
    $this->db->join('ss_user_details as b', 'a.id = b.user_id', 'left');

$this->db->join('ss_transaction_details as d', 'd.user_id = a.id', 'left');

     $this->db->join('ss_status', 'ss_status.id = a.status_id', 'left');
    $this->db->join('ss_customer_assistant_details as c', 'a.id = c.user_id', 'left');
    $this->db->where('a.id',$user_id);

    $query=$this->db->get();
   // $query = $this->db->get_where('ss_user_details', array('user_id' => $user_id));
  
    return $query->result();

  }
  
  function get_email_by_id($id){
  	
  		$this->db->select('u.username, ud.first_name, ud.last_name');
  		$this->db->from('ss_users as u');
  		$this->db->join('ss_user_details as ud', 'u.id = ud.user_id');
  		$this->db->where('u.id', $id);
  				
  		$query =  $this->db->get();
  				
  		return $query->result();		
  }


  /**
   *Getting All the Users used in admin customer listing, bda customer listing Search parameters are included
   * @param string $order
   * @param string $order_type
   * @param int limit_start
   * @param int limit_end
   * @param array search
   * @param int status_id
   * @return mixed[id,username,password,status_id,role_id,first_name,last_name,address,phone_number,zip_code,on_board_date,on_board_time,created,updated,bda_id,analyst_order,temp_token,plan_id,city,state,more_info,bda_name,transaction_id]
   */

  function fetchusers($order=null, $order_type, $limit_start, $limit_end,$search, $status_id=null,$bda_id = NULL){
    $this->db->select('a.*',FALSE);
    $this->db->select('b.first_name, b.last_name, b.address, b.phone_number, b.zip_code, b.on_board_date, b.on_board_time, b.created, b.updated, b.bda_id, b.analyst_order,b.temp_token, b.plan_id, b.city, b.state, b.more_info',FALSE);
    $this->db->select('e.first_name as bda_name', FALSE);

    $this->db->select("transaction.transaction_id");
    $this->db->select("transaction.transaction_date");



    //$this->db->select('c.id as assistant_id, c.name as assistant_name, c.email as assistant_email, c.phone_number as assistant_phone',FALSE);
    $this->db->from('ss_users as a');
  $this->db->join('ss_transaction_details as transaction', 'a.id = transaction.user_id', 'left');
    $this->db->join('ss_user_details as b', 'a.id = b.user_id', 'left');
    $this->db->join('ss_roles as c', 'a.role_id = c.id', 'left');
    $this->db->join('ss_user_details as e', 'b.bda_id = e.user_id', 'left');
    $this->db->where('c.id',2);
    //$this->db->where('a.company !=', 0);

   
    if($status_id !== NULL){
 $this->db->where('a.status_id',$status_id);

    }
    else{
    $this->db->where('a.status_id',1);
    }
    if($search !== NULL && !empty($search)){

    
      if(!empty($search['search_customer'])){
      $this->db->like('b.user_id',$search['search_customer']);
      }
      if(!empty($search['search_bda'])){

        $this->db->where('b.bda_id', $search['search_bda']);
      }

      if(!empty($search['search_from_date'])){

         $this->db->where('a.created >=',date("Y-m-d H:i:s", strtotime($search['search_from_date'] . ' ' . '00:00:00')));
      }

      if(!empty($search['search_to_date'])){

         $this->db->where('a.created <=',date("Y-m-d H:i:s", strtotime($search['search_to_date'] . ' ' . '23:59:59')));
      }

      if(isset($search['search_plans']) && !empty($search['search_plans'])){

        $this->db->where('b.plan_id', $search['search_plans']);
      }
    }

      if(!empty($bda_id)){

         $this->db->where('b.bda_id', $bda_id);
      }

      $this->db->order_by("a.id", "desc");

      $this->db->group_by("a.id");
    if($limit_start && $limit_end){
          $this->db->limit($limit_start, $limit_end); 
        }
         if($limit_start != null){
          $this->db->limit($limit_start, $limit_end);    
        }
    $query=$this->db->get();
   // $query = $this->db->get_where('ss_user_details', array('user_id' => $user_id));
  
    return $query->result();
  }


  /**
  * Returns number of customers as per the status id, Used in customer listing for pagination
  * @params int $status_id
  * @return int $query
  */
  function count_users($status_id = NULL)
 {
  // var_dump($user_id);
  // var_dump($status_id);
  $this->db->select('*');
    $this->db->from('ss_users');
   
    if($status_id !== NULL){
       $this->db->where('ss_users.status_id',$status_id);

    }
    $this->db->where('ss_users.role_id',2);
    
    $query = $this->db->get();
  
    return $query->num_rows();
 }


    /**
   *Common function to Get all users by role Search Parameters included used in admin/supportteam and someother places
   * @param string $role_id
   * @param string $user_id
   * @param int $limit_start
   * @param int $limit_end
   * @param string $order_by_analyst_order
   * @param int $plan_id
   * @param int status_id
   * @param array $search_array
   * @return mixed[id,username,password,status_id,role_id,analyst_order,plan_id,bda_id,temp_token,first_name,last_name,phone_number,user_id,plan_amount_per_hour,plan_hours,other_plan_hours,bda_name,status_name]
   */
  function fetch_users_by_role($role_id, $user_id,$limit_start = NULL,$limit_end = NULL ,$order_by_analyst_order = NULL, $plan_id=NULL,$status_id=NULL, $search_array = NULL){


    $this->db->select('a.*',FALSE);
    $this->db->select('b.analyst_order,b.plan_id,b.bda_id,b.temp_token, b.first_name, b.last_name,b.phone_number, b.user_id', FALSE);
      $this->db->select('c.plan_amount_per_hour, c.plan_hours ', FALSE);
      $this->db->select('g.plan_hours as other_plan_hours', FALSE);
      $this->db->select('e.first_name as bda_name', FALSE);
      $this->db->select('f.name as status_name');
    $this->db->from('ss_users as a');

    $this->db->join('ss_user_details as b', 'a.id = b.user_id', 'left');
    $this->db->join('ss_user_details as e', 'b.bda_id = e.user_id', 'left');
      $this->db->join('ss_users_plans as c', 'a.id = c.user_id', 'left');
          $this->db->join('ss_status as f', 'f.id = a.status_id', 'left');
                    $this->db->join('ss_plans as g', 'g.id = b.plan_id', 'left');
    $this->db->where('a.role_id',$role_id);
 $this->db->where('a.id !=',$user_id);

if($order_by_analyst_order !==NULL){

    $this->db->where('b.analyst_order !=', 0);
   }

 if($search_array !== NULL  && !empty($search_array)){

 

    
      if(!empty($search_array['search_customer'])){
      $this->db->like('b.user_id',$search_array['search_customer']);
      }
      if(!empty($search_array['search_bda'])){

        $this->db->where('b.bda_id', $search_array['search_bda']);
      }

      if(!empty($search_array['search_from_date'])){

         $this->db->where('a.created >=',date("Y-m-d H:i:s", strtotime($search_array['search_from_date'] . ' ' . '00:00:00')));
      }

      if(!empty($search_array['search_to_date'])){

         $this->db->where('a.created <=',date("Y-m-d H:i:s", strtotime($search_array['search_to_date'] . ' ' . '23:59:59')));
      }

      if(isset($search_array['search_plans']) && !empty($search_array['search_plans'])){

        $this->db->where('b.plan_id', $search_array['search_plans']);
      }
   
 }
 if($status_id !== NULL){
  $this->db->where_in('a.status_id',$status_id);

 }
 if($plan_id !== NULL){

   $this->db->where('b.plan_id',$plan_id);

 }

   
  if($limit_start && $limit_end){
          $this->db->limit($limit_start, $limit_end); 
        }
         if($limit_start != null){
          $this->db->limit($limit_start, $limit_end);    
        }


    if($order_by_analyst_order !==NULL){
  $this->db->order_by("b.analyst_order", "asc");
        }
        else{
    $this->db->order_by("a.id", "desc");
        }
$this->db->group_by("a.id");

    $query=$this->db->get();
    

     return $query->result();

  }

  /*
  *Used in admin/configuration/index
  */
function get_admin_email()
{

    $this->db-> select('*');
    $this->db->from('ss_admin_email');
    $query = $this->db->get();
    //if($query->num_rows() == 1)
     //{
        return $query->result();
      
    //} 
   //  else
    /// {
      //return 0;
              // } 

 }


/**
 *Updating the admin email
 */
function update_admin_email($admin_email,$email_id)
{
    $this->db->set('email',$admin_email);
    $this->db->where('id',$email_id);
    return $this->db->update('ss_admin_email');
}



/**
 *Function used to return the transaction id of the user, Used in User Activation,User Initiation,
 * @param int $user_id
 * @return int transaction_id
 */
function fetch_transaction_id_by_user_id($user_id){

  $this->db->select("transaction.transaction_id",FALSE);
  $this->db->from('ss_transaction_details as transaction');
  $this->db->where('transaction.user_id', $user_id);

      $query = $this->db->get();
    
      $result = $query->result();

    
      return $result[0]->transaction_id;

}

/**
 *Function used to return the transaction date of the user, User Initiation,
 * @param int $user_id
 * @return string transaction_date
 */

function fetch_transaction_date_by_user_id($user_id){

  $this->db->select("transaction.transaction_date",FALSE);
  $this->db->from('ss_transaction_details as transaction');
  $this->db->where('transaction.user_id', $user_id);

      $query = $this->db->get();
    
      $result = $query->result();

    
      return $result[0]->transaction_date;

}

/**
 *Function used to create Support Team, Used in Admin BDA Creation, Admin Account Manager Creation, Admin Analyst Creation
 * @param array $data
 * @return id user_id
 */
  
  function create_support_team($data)
  {

      $status = $this->fetch_status('general', 'active');
      //$role=$data['role_id'];
  
    $user_data=array(
          'username'=>$data['email'],
            'password'=>md5($data['password']),
            'role_id'=> $data['support_role_id'],
            'status_id'=>$status,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s')
        );

    
    if($this->db->insert('ss_users',$user_data))
    {

    $insert_id = $this->db->insert_id();
    $user_details=array(
          'first_name'=>$data['first_name'],
          'last_name'=>$data['last_name'],
          'phone_number'=>$data['phone_number'],
        
          'user_id'=>$insert_id,
          'analyst_order'=>$data['updated_analyst_order'],
          'created' => date('Y-m-d H:i:s'),
          'updated' => date('Y-m-d H:i:s')

        );
    return $this->db->insert('ss_user_details',$user_details);

    }
    else{
      return false;
    }


  }

public function insert_referrals($data){


 $referral_name = $data['name'];
 $referral_email = $data['email'];
 $referral_phone = $data['phone_number'];
  $referral_id_to_insert = array();

  for ($i = 0; $i < count($referral_name); $i++) {

     if(!empty($referral_name[$i])&&!empty($referral_phone[$i])){

        $referral_to_insert['name'] = $referral_name[$i];
        $referral_to_insert['email'] = $referral_email[$i];
        $referral_to_insert['phone_number'] = $referral_phone[$i];
        $referral_to_insert['created_date'] = date("Y-m-d h:i:s a");
        $referral_to_insert['referred_by'] =  $data['user_id'];
        $referral_to_insert['referred_role'] = $data['role_id'];
        $referral_id_to_insert[]  = $referral_to_insert; 
    


     }

   }



   $customer_detail = $this->fetchdata($data['user_id']);


     $message = "<div>".$customer_detail[0]->first_name." ".$customer_detail[0]->last_name." has ";
  
 
    if(!empty($referral_to_insert)){
     $message .="added the below Referrals:</div>";
      foreach($referral_id_to_insert as $key => $value){
        if(!empty($value['email'])){
          $message .= "<br /><div>Name: ".$value['name']."</div>
                <div>Email: ".$value['email']."</div>
                <div>Phone Number: ".$value['phone_number']."</div>";
          }
        else{
          $message .= "<br /><div>Name: ".$value['name']."</div>
                <div>Phone Number: ".$value['phone_number']."</div>";
            }
      }

      

      $admin = $this->admin_emails();
      $subject  = "Referrals details added by ".$customer_detail[0]->first_name." ".$customer_detail[0]->last_name;

      $data['email'] = 'sales@salessupport360.com';
      
      $this->send_mail($data, $message, $subject);


 }

 return $this->db->insert_batch('ss_referral_details',$referral_id_to_insert);

}
  /**
   *Function used to delete the customers and their team members and related tasks 
   * Runs as a recursive function in the case of team owner, deletes, user, user_details, transaction_details, customer_assistant_details,tasks, links, worklogs
   * @param int $user_id
   * @param int $parent_id
   * @param int $transaction_id
   * @return boolean 
   */
  function delete_user_related_tasks($user_id, $parent_id = NULL, $transaction_id = NULL){

      if($transaction_id != NULL){

        $cancel_the_current_subscription = $this->cancel_user_subscription($transaction_id);
        }

       $this->db->where_in('id', $user_id);
       $this->db->delete('ss_users');

       $this->db->where_in('user_id', $user_id);
       $this->db->delete('ss_user_details');

       $this->db->where_in("user_id", $user_id);
       $this->db->delete('ss_users_with_updated_plans');

       $this->db->where_in("user_id", $user_id);
       $this->db->delete('ss_transaction_details');

       $this->db->where_in("user_id", $user_id);
       $this->db->delete('ss_customer_assistant_details');

       $this->db->where_in("user_id", $user_id);
       $this->db->delete("ss_links");

       if($parent_id == NULL){
         $this->db->select('tasks.id', FALSE);
         $this->db->from('ss_tasks as tasks');
         $this->db->where_in('tasks.user_id', $user_id);
         $task_ids =  $query = $this->db->get()->result();
         $tasks_to_delete = array();
         if(!empty($task_ids)){
          foreach($task_ids as $task_id){
            $tasks_to_delete[] = $task_id->id;
          }
          $this->db->where_in("user_id", $user_id);
          $this->db->delete('ss_tasks');

          $this->db->where_in('task_id', $tasks_to_delete);
          $this->db->delete('ss_links');

          $this->db->where_in('task_id', $tasks_to_delete);
          $this->db->delete('ss_work_logs');
         }
      }
      else{
         $this->db->set('tasks.user_id',$parent_id);
         $this->db->where_in('tasks.user_id',$user_id);
         return $this->db->update('ss_tasks as tasks');
      }

       /*Deleting the team owner needs to delete all the team members and their respective tasks and others*/
       $this->db->select('ss_users_members.member_id');
       $this->db->from('ss_users_members');
       $this->db->where_in('ss_users_members.user_id', $user_id);
$member_ids = $this->db->get()->result_array();
       $members = array();

          $this->db->where_in('ss_users_members.user_id', $user_id);
          $this->db->delete('ss_users_members');
       
       if(!empty($member_ids)){
        foreach($member_ids as $member_id){
          $members[] = $member_id['member_id'];
        }
        $this->delete_user_related_tasks($members);
       }
       
       return TRUE;
  }

  /**
  * this function is running while task is created and auto analyst assignment is done, we need to pull the analyst to last 
  *  @param int analyst_id
  *  @param int current_analyst_order
  *  @param int updated_analyst_order
  */
  function update_analyst_order($analyst_id, $current_analyst_order,$updated_analyst_order){

    $this->db->where('user_id', $analyst_id);

    $this->db->update('ss_user_details', array("analyst_order" => $updated_analyst_order));
  }


   /**
    * this function is running in edit profile section updates user, user_details, customer_assistant_details tables
    *  @param int analyst_id
    *  @param int current_analyst_order
    *  @param int updated_analyst_order
    *
    */
  function updateuser($data) 
  {

  
    if(isset($data))
    {
      //exit();
      if(!empty($data['company_checkbox'])){
   
      $user = array(
        'username' => $data['email'],
         'company' => $data['company_checkbox'] ? TRUE : FALSE
        );
    }
    else{

       $user = array(
        'username' => $data['email']
        );
    }
      $id = $data['id'];
      
      $get_customer_details = $this->fetchdata($id);
      
      $bda_email = $this->get_email_by_id($get_customer_details[0]->bda_id);
      
      

      $user_details = array(
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
        'address' => $data['street_name'], 
        'city' => $data['city'], 
        'company_name' => $data['company_name'],
        'state' => $data['state'],
        'phone_number' => $data['phone_number'],
        'zip_code' => $data['zip_code'],
        'more_info' =>  $data['more_info'],
         'on_board_date' => !empty($data['on_board_date']) ? date("Y-m-d", strtotime($data['on_board_date'])) : NULL,
           'on_board_time' =>!empty($data['on_board_time']) ? date("H:i", strtotime($data['on_board_time'])) : NULL,
        );



      $assistant_id = $data['assistant_id'];
      $assistant_name = $data['assistant_name'];
      $assistant_email = $data['assistant_email'];
      $assistant_phone = $data['assistant_phone'];
      $email_status = $data['email_status'];
      // print "<pre>";
      // print_r($data);
      // exit();

       $assistant_to_insert = array();
       $assistant_to_update = array();
       $assitant_to_delete = array();
       $assitant_id_to_update = array();
       $assitant_id_to_insert = array();
        ;

      for ($i = 0; $i < count($assistant_name); $i++) {
        if(!empty($assistant_name[$i])&&!empty($assistant_email[$i])&&!empty($assistant_phone[$i])){


          if(!empty($assistant_id[$i])){



 
        $assistant_to_update['name'] = $assistant_name[$i];
          $assistant_to_update['email'] = $assistant_email[$i];
          $assistant_to_update['id'] = $assistant_id[$i];
         $assistant_to_update['phone_number'] = $assistant_phone[$i];
          $assistant_to_update['email_status'] = $email_status[$i];
        
         $assistant_to_update['user_id'] = $data['id'];
        // $assistant_to_update['created'] = date("Y-m-d h:i:sa");
         $assistant_to_update['updated'] = date("Y-m-d h:i:sa");

         $assitant_id_to_update[] = $assistant_to_update;
          }
          else{
          $assistant_to_insert['name'] = $assistant_name[$i];
          $assistant_to_insert['email'] = $assistant_email[$i];
         $assistant_to_insert['email_status'] = $email_status[$i];
        
         $assistant_to_insert['phone_number'] = $assistant_phone[$i];
         $assistant_to_insert['user_id'] = $data['id'];
         $assistant_to_insert['created'] = date("Y-m-d h:i:sa");
         $assistant_to_insert['updated'] = date("Y-m-d h:i:sa");
         $assitant_id_to_insert[]  = $assistant_to_insert; 



          }
        }
       
      
    }

//     echo "<pre>";
// print_r( $assistant_to_update);
// exit();
  
    if(!empty($data['delete_assistant'])){
    foreach($data['delete_assistant'] as $id_to_delete){
      if(!empty($id_to_delete)){
         $assitant_to_delete[] = $id_to_delete;
      }
    }
  }
  
  
  
  
  $email_message = "Hi ".$bda_email[0]->first_name." ".$bda_email[0]->last_name."<br /><br />";
  $email_message .= "<div>".$data['first_name']." ".$data['last_name']." has edited the following information: </div>";
  
   
	$bda_email['email'] = $bda_email[0]->username;
    if(!empty($assistant_to_insert)){
     $email_message .="<br><div>Added the below new customer assistant details:</div>";
      foreach($assitant_id_to_insert as $key => $value){
      $email_message .= "<br /><div>Name: ".$value['name']."</div>
      					<div>Email: ".$value['email']."</div>
      					<div>Phone Number: ".$value['phone_number']."</div>";
      }
      
    	//send mail to bda
    	//$send_mail_to_bda  = $this->send_mail($bda_email, $email_message, $subject,$cc);
    $this->db->insert_batch('ss_customer_assistant_details', $assitant_id_to_insert);
    }
    if(!empty($assistant_to_update)){
		
	    foreach($assitant_id_to_update as $value){
	    	$updated_ids[] = $value['id'];
	    	
	    }
	    
	    $get_updated_customer_assistant_details = $this->get_assistant_details_by_id($updated_ids);
	    
	    $i = 0;
	    foreach($get_updated_customer_assistant_details as $value){
	    	$value = get_object_vars($value);
	    	
	    	$updated_diff = array_diff($assitant_id_to_update[$i], $value);
	    	$old_diff = array_diff($value, $assitant_id_to_update[$i]);
	    	
	    	if(isset($updated_diff['name'])){
	    		$diff_in_update[$i]['new_name'] = $updated_diff['name'];
	    		$diff_in_update[$i]['old_name'] = $old_diff['name'];
	    	}
	    	if(isset($updated_diff['email'])){
	    		$diff_in_update[$i]['new_email'] = $updated_diff['email'];
	    		$diff_in_update[$i]['old_email'] = $old_diff['email'];
	    		
	    	}
	    	if(isset($updated_diff['phone_number'])){
	    		$diff_in_update[$i]['new_phone_number'] = $updated_diff['phone_number'];
	    		$diff_in_update[$i]['old_phone_number'] = $old_diff['phone_number'];
	    	}
	    	
	    	$i++;
	    }
	    
	    if((count($diff_in_update) >  0)){ // check only diddference in name, email and phone number
	    	//echo "here";exit;
	    	$email_message .="<br><div>Changed the below customer assistant details:

         </div>";
	    	foreach($diff_in_update as $key => $value){
	    		if(isset($value['new_name'])){
	    			$email_message .= "<br /><div>Name: From ".$value['old_name']." to ".$value['new_name']."</div>";
	    		}
	    		if(isset($value['new_email']) && isset($value['new_name'])){
	    			$email_message .= "<div>Email: From ".$value['old_email']." to ".$value['new_email']."</div>";
	    		}else if(isset($value['new_email']) && !isset($value['new_name'])){
	    			$email_message .= "<br /><div>Email: From ".$value['old_email']." to ".$value['new_email']."</div>";
	    		}
	    		if(isset($value['new_phone_number'])){
	    			if(isset($value['new_email']) || isset($value['new_name']))
      					$email_message .= "<div>Phone Number: From ".$value['old_phone_number']." to ".$value['new_phone_number']."</div>";
	    			else
	    				$email_message .= "<br /><div>Phone Number: From ".$value['old_phone_number']." to ".$value['new_phone_number']."</div>";
	    		}
    		}
    		
	    }
	    $this->db->update_batch('ss_customer_assistant_details', $assitant_id_to_update,'id');
    }
    if(!empty($assitant_to_delete)){
    	$email_message .= "<br><div>Removed the below customer assistant details:</div>";
    	 
    	$get_deleted_customer_assistant_details = $this->get_assistant_details_by_id($assitant_to_delete);
    	
    
    	foreach($get_deleted_customer_assistant_details as $value){
    		$email_message .= "<br /><div>Name: ".$value->name."</div>
      					<div>Email: ".$value->email."</div>
      					<div>Phone Number: ".$value->phone_number."</div>";
    	}
    	
    	
    	//$subject  = "Customer Assistant details deleted by ".$data['first_name']." ".$data['last_name'];
    	
    	//send mail to bda
    	//$send_mail_to_bda  = $this->send_mail($bda_email, $email_message, $subject,$cc);
      $this->db->where_in('id', $data['delete_assistant']);
		$this->db->delete('ss_customer_assistant_details');
    }
	
    if(!empty($assistant_to_insert) || !empty($assitant_to_delete) || isset($diff_in_update[0]['new_name']) || isset($diff_in_update[0]['new_email']) || isset($diff_in_update[0]['new_phone_number'])){
    	$cc = $this->admin_emails();
    	$subject  = $data['first_name']." ".$data['last_name']." Profile Update";
    	$send_mail_to_bda  = $this->send_mail($bda_email, $email_message, $subject,$cc);
    }
      // update the users table first //
    $this->db->where(array('id' => $id));
    $query= $this->db->update('ss_users',$user);
    

    //update the user details table next//
    $this->db->where(array('user_id' => $id));
    $query.=$this->db->update('ss_user_details',$user_details);
return $query;

    }
  }



   /**
    * this function is running in Company/Team plan User Team Member section Edit Team member updates user and user_details table
    *  @param array data
    *  
    *
    */
  function update_team_member($data){

   $updated_data = (array) $data[0];
    
$user = array(
        'username' => $updated_data['username'],
            'updated' => date('Y-m-d H:i:s')
        );
  $this->db->where(array('id' => $updated_data['id']));
    $user_update = $this->db->update('ss_users',$user);

      $user_details = array(
        'first_name' => $updated_data['first_name'],
        'last_name' => $updated_data['last_name'],
        'address' => $updated_data['address'], 
        'city' => $updated_data['city'], 

        'state' => $updated_data['state'],
        'phone_number' => $updated_data['phone_number'],
        'zip_code' => $updated_data['zip_code'],
        'monthly_usage' => $updated_data['monthly_usage'],
        'notification_hour' => $updated_data['notification_hour'],
            'updated' => date('Y-m-d H:i:s')
        );
          $this->db->where(array('user_id' => $updated_data['id']));
    $user_update  .= $this->db->update('ss_user_details',$user_details);

    return $user_update;
  }




   /**
    * Deletes the support team while clicking on delete button from admin panel
    *  @param array $data
    *
    */
function delete_support_team($data)
{
   foreach( $data['delete'] as $id_to_delete){
      if(!empty($id_to_delete)){
         $bda_to_delete[] = $id_to_delete;
      }
    }

    if(!empty($bda_to_delete)){
      $this->db->where_in('id', $data['delete']);
      $query=$this->db->delete('ss_users');
    }
     if(!empty($bda_to_delete)){
      $this->db->where_in('user_id', $data['delete']);
      $query=$this->db->delete('ss_user_details');
    }
      return true;

}


function updateanalyst($data)
{
   if(isset($data))
    {
      
      // echo "<pre>";
      // print_r($data);
      // exit();
       $id = $data['id'];

      $this->db->set('username',$data['email']);
      $this->db->set('password', md5($data['password']));
      $this->db->where('id',$data['id']);
      $query= $this->db->update('ss_users');

        $user_details = array(
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
       );
    
      $this->db->where(array('user_id' => $id));
      $query.=$this->db->update('ss_user_details',$user_details);
      return $query;

}


}


function update_bda($data){


   if(isset($data))
    {
     
      
      $id = $data['id'];

      $user_details = array(
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
        'phone_number' => $data['phone_number']
       );
      
       $this->db->set('username',$data['email']);
       $this->db->set('password', md5($data['password']));
       $this->db->where('id',$data['id']);
      $query= $this->db->update('ss_users');

      $this->db->where(array('user_id' => $id));
      $query.=$this->db->update('ss_user_details',$user_details);
      return $query;

}


}


  function updatepassword($data)
  {
    $this->db->set('password', md5($data['password']));
    $this->db->where('id',$data['id']);
    return $this->db->update('ss_users');
    
  }



  function bda_updatepassword_by_admin($data)
  {
    

    $this->db->set('password', md5($data['password']));
    $this->db->where('id',$data['id']);
    return $this->db->update('ss_users');
  }


    /**
    * Serialize the session data stored in the database, 
    * store it in a new array and return it to the controller 
    * @return array
    */

	function get_db_session_data()
	{
		$query = $this->db->select('user_data')->get('ci_sessions');
		$user = array(); /* array to store the user data we fetch */
		foreach ($query->result() as $row)
		{
		    $udata = unserialize($row->user_data);
		    /* put data in array using username as key */
		    $user['user_name'] = $udata['user_name']; 
		    $user['is_logged_in'] = $udata['is_logged_in']; 
		}
		return $user;
	}

      function delete_exisiting_user($id){
       $this->db->where_in('id', $id);
       $this->db->delete('ss_users');

       $this->db->where_in('user_id', $id);
       $this->db->delete('ss_user_details');

       $this->db->where_in('user_id', $id);
       $this->db->delete('ss_transaction_details');

      }

      /** This function will run while user registers/ Activates their account by Unique  link.
       *Logic.
       *
       *  New User Registration.
       *
       *   1. With Submitted Data Create Authorize.net subscription
       *   2. If the Result is success, Create User in ss_users_table, after that create the ss_users_details table
       *   3. Inserting into transaction details for future purpose
       *   4. Send thank you email to customer.
       * 
       *  User Activation.
       *   1. Get old Transaction Id, Cancel it.
       *   2. With Submitted Data Create Authorize.net subscription
       *   3.  If the Result is success, update the user in ss_users_table, after that  update details in ss_users_details table
       *   4. update the transaction details 
       *   5. If user is company/team plan. Update their team member status to active.
       *   6. Update all the team members to the owner plan
       *   7. Send Email
        */
      function register_user($data){
    
        $user_id = NULL;
        if(isset($data['user_id']) && !empty($data['user_id'])){
          $user_id = $data['user_id'];
         //$delete_exisiting_users = $this->delete_exisiting_user($data['user_id']);
        }
          $status = $this->fetch_status('general', 'active');
         $role = $this->fetch_role('Customer'); 
         $plan = $data['plan_id_selected'];   


    
        $this->load->model('Plan_model', 'plan_model');
      $plan_details = $this->plan_model->get_plan_name_and_amount_hour($plan, $user_id);
     
          $user_data=array(
          'username'=>$data['email'],
            'password'=>md5($data['password']),
            'status_id' =>  $status, 
            'role_id' => $role,
            'company' => $data['company_checkbox'] ? TRUE : FALSE,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
          );
         

         if(isset($data['user_id']) && !empty($data['user_id'])){
           $old_transaction_id =  $this->fetch_transaction_id_by_user_id($data['user_id']);

           $cancel_transaction = $this->cancel_user_subscription($old_transaction_id);
         }
         //call authorize.net arb api
         $arb_result = $this->authorize_arb_payment($data);


 
         if(is_numeric($arb_result)){
          /*Based on the User Id from the form we need to either insert or update the record*/
          if(empty($data['user_id']) || !isset($data['user_id'])){

            $user_entry= $this->db->insert('ss_users',$user_data);
            $insert_id = $this->db->insert_id();

          }
          else{

           $this->db->set('username', $user_data['username']);
           $this->db->set('password', md5($user_data['password']));
           $this->db->set('status_id', $user_data['status_id']);
           $this->db->set('role_id', $user_data['role_id']);
           $this->db->set('updated', date('Y-m-d H:i:s'));
           $this->db->where('id',$data['user_id']);
           $this->db->update('ss_users');
           $insert_id = $data['user_id'];


              /*Update all the team member status as 1*/
              $this->db->select('ss_users_members.member_id');
               $this->db->from('ss_users_members');
               $this->db->where_in('ss_users_members.user_id', $data['user_id']);
               $member_ids = $this->db->get()->result_array();
               $members = array();
               if(!empty($member_ids)){
                foreach($member_ids as $member_id){
                  $members[] = $member_id['member_id'];
                }


               $this->db->set('status_id', $user_data['status_id']);
               $this->db->where_in('id',$members);
               $this->db->update('ss_users');
               }
           
          }
        
        
      
            /*Inserting in to User Details Table*/
            $user_details_data=array(
            'first_name'=>$data['first_name'] ? $data['first_name'] : " ",
            'last_name'=>$data['last_name'] ? $data['last_name'] : " ",
            'address'=> $data['address'] ? $data['address'] : " ",
            'state'=> $data['state'] ? $data['state'] : " ",
            'city'=> $data['city'] ? $data['city'] : " ",
            'company_name' => !empty($data['company_name']) ? $data['company_name'] : "", 
            'zip_code'=> $data['zip'] ? $data['zip'] : " ",
            'phone_number'=> $data['phone_number'] ? $data['phone_number'] : " ",
            'user_id'=> $insert_id,
            'plan_id' => $plan,
            'on_board_date' => isset($data['onboard-date']) ? date('Y-m-d', strtotime((string) $data['onboard-date'])) : "",
            'on_board_time' => isset($data['onboard-time']) ? date('H:i:s a', strtotime((string) $data['onboard-time'])) : "",
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
            'temp_token' => NULL,
             'email_notification_status' => 12,);
       
   
       
            if(empty($data['user_id']) || !isset($data['user_id'])){
         $user_details= $this->db->insert('ss_user_details',$user_details_data);
           }
           else{
             $this->db->set('first_name', $user_details_data['first_name']);
             $this->db->set('last_name', $user_details_data['last_name']);
             $this->db->set('address', $user_details_data['address']);
             $this->db->set('state', $user_details_data['state']);
             $this->db->set('city', $user_details_data['city']);
             $this->db->set('zip_code', $user_details_data['zip_code']);
             $this->db->set('phone_number', $user_details_data['phone_number']);
             $this->db->set('user_id', $user_details_data['user_id']);
             $this->db->set('plan_id', $user_details_data['plan_id']);
             $this->db->set('on_board_date', $user_details_data['on_board_date']);
             $this->db->set('on_board_time', $user_details_data['on_board_time']);
                 $this->db->set('email_notification_status', 12);
             $this->db->set('temp_token', NULL);
             $this->db->set('updated', date('Y-m-d H:i:s'));
            $this->db->set('created', date('Y-m-d H:i:s'));
             $this->db->where('user_id',$data['user_id']);
             $this->db->update('ss_user_details');



              /*Update all the team member plan as team owner plan*/
              $this->db->select('ss_users_members.member_id');
               $this->db->from('ss_users_members');
               $this->db->where_in('ss_users_members.user_id', $user_details_data['user_id']);
               $member_ids = $this->db->get()->result_array();
               $members = array();
               if(!empty($member_ids)){
                foreach($member_ids as $member_id){
                  $members[] = $member_id['member_id'];
                }


               $this->db->set('plan_id', $user_details_data['plan_id']);
               $this->db->set('updated', date('Y-m-d H:i:s'));
               $this->db->where_in('user_id',$members);
               $this->db->update('ss_user_details');
               }
                
           }

         /*Inserting into transaction details */
 
            $transaction_details=array(
            'user_id'=> $insert_id,
            'request' =>'request object goes here',
            'response' =>  is_numeric($arb_result) ? "Success" : $arb_result ,
            'transaction_date' => date('Y-m-d H:i:s'),
            'transaction_id' =>  is_numeric($arb_result) ? $arb_result : "",
            'status' =>  is_numeric($arb_result) ? "Success" : "Failure" ,
            );
 if(empty($data['user_id']) || !isset($data['user_id'])){

            $this->db->insert('ss_transaction_details',$transaction_details);
          }
          else{
           
            if((int) $plan !== 3){
                //$this->db->set('first_name', $user_details_data['first_name']);
                $this->db->set('transaction_id', $transaction_details['transaction_id']);
                $this->db->set('transaction_date', $transaction_details['transaction_date']);
                //$this->db->set('updated', date('Y-m-d H:i:s'));
                $this->db->where('user_id',$data['user_id']);
                $this->db->update('ss_transaction_details');
            }
            else{
                $this->db->insert('ss_transaction_details',$transaction_details);
            }

          }
            $message = 'Hi ' . $data['first_name'] .',<br><br>';
                  


 if(empty($data['user_id']) || !isset($data['user_id'])){
 $message .= "<div>
          Thank you for signing up with SalesSupport360. We look forward to helping you increase your referral marketing and overall new business development efforts. You will hear from your Business Development Assistant to get you on boarded. 
  </div>";
 }
 else{
  $message .= "<div> Thank you for signing up with SalesSupport360. We look forward to helping you increase your referral marketing and overall new business development efforts. You will hear from your Business Development Assistant to get you on boarded.</div><br>";
 }
               
                $message .= "<br/> Your Registered Plan is: <br>";

                $message .= "<b> Plan Type:</b> ". $plan_details[0]->name .'<br>';
                $message .= "<b>Price Per Month: </b> $". $plan_details[0]->plan_amount .'<br>';
                $message .= "<b> Total Hours per Month :</b> ". $plan_details[0]->plan_hours  .'<br>';

                if(isset($data['onboard-date']) && !empty($data['onboard-date'])){
                $message .= "<b>On Boarding Date</b> : " . date('m/d/Y', strtotime((string) $data['onboard-date'])) . "<br>";
                }
                if(isset($data['onboard-time']) && !empty($data['onboard-time'])){
                $message .= "<b>On Boarding Time</b>: " . date('h:i A', strtotime((string) $data['onboard-time'])). "<br>";
                }
                $message .= "<b>Username</b>: " . $data['email'] . "<br>";
                $message .= "<b>Password</b>: " . $data['password'] . "<br><br>";


              $message .= "<div>Please click the link below to log in: <br><a href='".PHASE1_URL."'>".PHASE1_URL."</a></div>";
                
                $subject = 'Thank you for signing up with SalesSupport360 ';


                $cc = $this->admin_emails();


            $this->send_mail($data,$message,$subject,$cc);
           
         return array("user_id" => $insert_id, "role_id" => $role, 'plan_id' => $user_details_data['plan_id']);

          }
          else{
            return $arb_result;
          }
        }
         

       /** 
        * Team members are created by team owners, inserts user, user_details table data
        */
        function create_team_member($submitted_values, $plan_id,$bda_id){

           $status = $this->fetch_status('general', 'active');
         $role = $this->fetch_role('Team Member');
        
          $user_data=array(
          'username'=>$submitted_values['email'],
            'password'=>md5($submitted_values['password']),
            'status_id' =>  $status, 
            'role_id' => $role,
            'company' =>  FALSE,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
          );
          $user_entry= $this->db->insert('ss_users',$user_data);
          $insert_id = $this->db->insert_id();

           /*Inserting in to User Details Table*/
            $user_details_data=array(
            'first_name'=>$submitted_values['first_name'] ? $submitted_values['first_name'] : " ",
            'last_name'=>$submitted_values['last_name'] ? $submitted_values['last_name'] : " ",
            'address'=> $submitted_values['street_address'] ? $submitted_values['street_address'] : " ",
            'state'=> $submitted_values['state'] ? $submitted_values['state'] : " ",
            'city'=> $submitted_values['city'] ? $submitted_values['city'] : " ",
            'company_name' => "", 
            'zip_code'=> $submitted_values['zip'] ? $submitted_values['zip'] : " ",
            'phone_number'=> $submitted_values['phone_number'] ? $submitted_values['phone_number'] : " ",
            'user_id'=> $insert_id,
            'plan_id' => $plan_id,
            'bda_id' => $bda_id,
            'monthly_usage' => $submitted_values['monthly_usage'],
            'notification_hour' => $submitted_values['notification_hour'],
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
            'temp_token' => NULL,
            'email_notification_status' => 12,
            );
            $user_details= $this->db->insert('ss_user_details',$user_details_data);

            $users_members = array(
                'user_id'=>$submitted_values['parent_id'],
                'member_id'=>$insert_id,
            );

            $users_members_details= $this->db->insert('ss_users_members',$users_members);
            return $insert_id;
         

        }
       
        /**
        * Function will run while moving new system to live
        *
        * Logic
        * 1. Update User, User_details with status as active,
        * 2. Create ARB Recurring Transaction with the transaction details mentioned in ss_transaction_table. 
        * 3. Send thank you email to customer
        *
        */
        public function initiate_old_user_to_system($data){

          $status = $this->fetch_status('general', 'active');
          $role = $this->fetch_role('Customer');
          $plan = $data['plan_id_selected'];   
          $user_data=array(
          'username'=>$data['email'],
            'password'=>md5($data['password']),
            'status_id' =>  $status, 
            'role_id' => $role,
            'company' => $data['company_checkbox'] ? TRUE : FALSE,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
          );
          $transaction_date =$this->fetch_transaction_date_by_user_id($data['user_id']);
          //call authorize.net arb api
          $arb_result = $this->authorize_arb_payment($data, $transaction_date);
          if(is_numeric($arb_result)){
            /*Based on the User Id from the form we need to update the record*/
            $this->db->set('username', $user_data['username']);
            $this->db->set('password', $user_data['password']);
            $this->db->set('status_id', $user_data['status_id']);
            $this->db->set('role_id', $user_data['role_id']);
            $this->db->set('updated', date('Y-m-d H:i:s'));
            $this->db->where('id',$data['user_id']);
            $this->db->update('ss_users');
            $insert_id = $data['user_id'];
            /*Update all the team member status as 1*/
            $this->db->select('ss_users_members.member_id');
            $this->db->from('ss_users_members');
            $this->db->where_in('ss_users_members.user_id', $data['user_id']);
            $member_ids = $this->db->get()->result_array();
            $members = array();
            if(!empty($member_ids)){
              foreach($member_ids as $member_id){
                $members[] = $member_id['member_id'];
              }
              $this->db->set('status_id', $user_data['status_id']);
              $this->db->where_in('id',$members);
              $this->db->update('ss_users');
            }
            /*Inserting in to User Details Table*/
            $user_details_data=array(
            'first_name'=>$data['first_name'] ? $data['first_name'] : " ",
            'last_name'=>$data['last_name'] ? $data['last_name'] : " ",
            'address'=> $data['address'] ? $data['address'] : " ",
            'state'=> $data['state'] ? $data['state'] : " ",
            'city'=> $data['city'] ? $data['city'] : " ",
            'company_name' => !empty($data['company_name']) ? $data['company_name'] : "", 
            'zip_code'=> $data['zip'] ? $data['zip'] : " ",
            'phone_number'=> $data['phone_number'] ? $data['phone_number'] : " ",
            'user_id'=> $insert_id,
            'plan_id' => $plan,
            'on_board_date' => isset($data['onboard-date']) ? date('Y-m-d', strtotime((string) $data['onboard-date'])) : "",
            'on_board_time' => isset($data['onboard-time']) ? date('H:i:s a', strtotime((string) $data['onboard-time'])) : "",
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
            'temp_token' => NULL,
             'email_notification_status' => 12);
       
   
             $this->db->set('first_name', $user_details_data['first_name']);
             $this->db->set('last_name', $user_details_data['last_name']);
             $this->db->set('address', $user_details_data['address']);
             $this->db->set('state', $user_details_data['state']);
             $this->db->set('city', $user_details_data['city']);
             $this->db->set('zip_code', $user_details_data['zip_code']);
             $this->db->set('phone_number', $user_details_data['phone_number']);
             $this->db->set('user_id', $user_details_data['user_id']);
             $this->db->set('plan_id', $user_details_data['plan_id']);
             $this->db->set('on_board_date', $user_details_data['on_board_date']);
             $this->db->set('on_board_time', $user_details_data['on_board_time']);
             $this->db->set('email_notification_status', $user_details_data['email_notification_status']);
             $this->db->set('temp_token', NULL);
             $this->db->set('updated', date('Y-m-d H:i:s'));
             $this->db->where('user_id',$data['user_id']);
             $this->db->update('ss_user_details');
             /*Update all the team member plan as team owner plan*/
             $this->db->select('ss_users_members.member_id');
             $this->db->from('ss_users_members');
             $this->db->where_in('ss_users_members.user_id', $user_details_data['user_id']);
             $member_ids = $this->db->get()->result_array();
             $members = array();
             if(!empty($member_ids)){
                foreach($member_ids as $member_id){
                  $members[] = $member_id['member_id'];
                }


                $this->db->set('plan_id', $user_details_data['plan_id']);
                $this->db->set('updated', date('Y-m-d H:i:s'));
                $this->db->where_in('user_id',$members);
                $this->db->update('ss_user_details');
             }
                
           //}

         /*Inserting into transaction details */
 
             $transaction_details=array(
                'user_id'=> $insert_id,
                'request' =>'request object goes here',
                'response' =>  is_numeric($arb_result) ? "Success" : $arb_result ,
                'transaction_date' => date('Y-m-d H:i:s'),
                'transaction_id' =>  is_numeric($arb_result) ? $arb_result : "",
                'status' =>  is_numeric($arb_result) ? "Success" : "Failure" ,
            );
            //$this->db->set('first_name', $user_details_data['first_name']);
            $this->db->set('transaction_id', $transaction_details['transaction_id']);
            $this->db->set('request', $transaction_details['request']);
            $this->db->set('response', $transaction_details['response']);
            $this->db->set('status', $transaction_details['status']);
            $this->db->where('user_id',$data['user_id']);
            $this->db->update('ss_transaction_details');
      $this->load->model('Plan_model', 'plan_model');
            $plan_details = $this->plan_model->get_plan_name_and_amount_hour( $user_details_data['plan_id'], $transaction_details['user_id']);

            $message = 'Hi ' . $data['first_name'] .',<br><br>';
            $message .= "<div>Thank you for signing up with SalesSupport360. We look forward to helping you increase your referral marketing and overall new business development efforts. You will hear from your Business Development Assistant to get you on boarded. </div><br>";  

            $message.="Your Registered Plan is:" ;
            $message .= "<br>";

            $message .= "<b>Plan type</b>: ".$plan_details[0]->name."<br>";

            $message .= "<b>Price Per Month: </b> $". $plan_details[0]->plan_amount .'<br>';

            $message .= "<b> Total Hours per Month :</b> ". $plan_details[0]->plan_hours  .'<br>';
        
             
            $message .= "<b> Total Hours per Month :</b> ".$user_data['username'].'<br>';

            $message .= "<b>Password</b>: " . $data['password'] . "<br>";
            
          
                
            if(isset($data['onboard-date']) && !empty($data['onboard-date'])){
              $message .= "<b>On Boarding Date</b>: " . date('m/d/Y', strtotime((string) $data['onboard-date'])) . "<br>";
            }
            if(isset($data['onboard-time']) && !empty($data['onboard-time'])){
              $message .= "<b>On Boarding Time</b>: " . date('H:i:s a', strtotime((string) $data['onboard-time'])). "<br>";
            }
            

            $subject = 'Thank you for signing up with SalesSupport360 ';
            $cc = $this->admin_emails();
            $this->send_mail($data,$message,$subject,$cc);
            return array("user_id" => $insert_id, "role_id" => $role, 'plan_id' => $user_details_data['plan_id']);
          }
          else{
            return $arb_result;
          } 

        }
        /**
         * Function will run if the user tries to register by choosing team plan
         *
        Logic
         *  1. Insert in to User, User_details with status as inactive so the admin will set the amount and hours and activation will happen through unique link
         * @source 1345 team register
         */
        public function register_team_plan_user($data){
       
         $status = $this->fetch_status('UserActivation', 'Pending Team');
         $role = $this->fetch_role('Customer'); 
         $plan = $data['plan_id_selected'];   
          $user_data=array(
          'username'=>$data['email'],
            'password'=>md5($data['password']),
            'status_id' =>  $status, 
            'role_id' => $role,
            'company' => $data['company_checkbox'] ? TRUE : FALSE,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
          );

          $user_entry= $this->db->insert('ss_users',$user_data);
          $insert_id = $this->db->insert_id();
            /*Inserting in to User Details Table*/
            $user_details_data=array(
            'first_name'=>$data['first_name'] ? $data['first_name'] : " ",
            'last_name'=>$data['last_name'] ? $data['last_name'] : " ",
            'address'=> $data['address'] ? $data['address'] : " ",
            'state'=> $data['state'] ? $data['state'] : " ",
            'city'=> $data['city'] ? $data['city'] : " ",
            'company_name' => !empty($data['company_name']) ? $data['company_name'] : "", 
            'zip_code'=> $data['zip'] ? $data['zip'] : " ",
            'phone_number'=> $data['phone_number'] ? $data['phone_number'] : " ",
            'user_id'=> $insert_id,
            'plan_id' => $plan,
            'on_board_date' => isset($data['onboard-date']) ? date('Y-m-d', strtotime((string) $data['onboard-date'])) : "",
            'on_board_time' => isset($data['onboard-time']) ? date('H:i:s a', strtotime((string) $data['onboard-time'])) : "",
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
            'temp_token' => NULL,
            'email_notification_status' => 12,
            );
       
   
       


          $message = "Hi " .$data['first_name']." ".$data['last_name'].",<br><br>";
          $message .= "<div>We have received your request for the Team Plan. Our Support Team will get in touch with you.</div>";
        
          $subject = 'Thank you for your request for Team Plan ';

          $email_headers = array();
          $email_headers['email'] = $data['email'];
              $cc = $this->admin_emails();
          $this->send_mail($data,$message,$subject, $cc);
          $user_details= $this->db->insert('ss_user_details',$user_details_data);
          
            return array("user_id" => $insert_id, "role_id" => $role, 'plan_id' => $plan);

        }


        /**
         *This function is running while the admin sets the Hours and Amount through admin interface
         * 
         */
        function updated_team_plan_details($data,$status_id, $email_id){
          $length = 10;
          $temp_token = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
          /*Change status to pending activation*/
          $this->db->set('status_id', $status_id);
          $this->db->where('id', $data['customer_id']);
          $this->db->update('ss_users');

          /*Add the Temp Token*/
           $this->db->set('temp_token', $temp_token);   
           $this->db->where('user_id', $data['customer_id']);
           $this->db->update('ss_user_details');
          /*Update Users Plans table*/
          /*Delete the older plan amounts and insert again*/

            $this->db->where_in('user_id',$data['customer_id']);
            $this->db->delete('ss_users_plans'); 
            $user_plan = array();
            $user_plan['user_id'] = $data['customer_id'];
            $user_plan['plan_id'] = 3;
            $user_plan['plan_amount_per_hour'] = $data['plan_amount'];
            $user_plan['plan_hours'] = $data['plan_hours'];
            $user_plan['created'] = date("Y-m-d H:i:s");
            $user_plan['updated'] = date('Y-m-d H:i:s');
            $user_plan['total_plan_amount '] = $data['plan_hours'] * $data['plan_amount'];
            $user_plan_details= $this->db->insert('ss_users_plans',$user_plan);

            $email_headers = array();
            $email_headers['email'] = $email_id;

            $user_details = $this->fetchdata($data['customer_id']);
           // $user_details[0]->first_name." ".$user_details[0]->last_name;



            $message = "Hi ".$user_details[0]->first_name." ".$user_details[0]->last_name.",<br><br>
                    <div>Please click the following link for activation of your account:<br><br><a href='".base_url()."/user/activate_user/".$temp_token."'>".base_url()."/user/activate_user/".$temp_token."</a></div><br>

                          Monthly Hours: ". $data['plan_hours']." Hours<br>
                          Hourly Rate: $".$data['plan_amount']."<br><br>

           <div>Please don’t hesitate to contact us for any questions or concerns & let me know if there is anything else we can provide you with.</div>";



            $cc = $this->admin_emails();
            $subject = "SalesSupport360 Team Activation";
            $this->send_mail($email_headers,$message,$subject,$cc);
            return $user_plan_details;
          
        }


        /**
         *  Runs as a cron does the following logic
         *
         *  When a plan amount is changed globally we are fetching all the users who are in that plan and storing in ss_users_with_updated_plans table
         *  1. This function first fetches their details from ss_users_with_updated_plans table
         *  2. Gets each user transaction id and fetches customer profile id 
         *  3. cancells the current transaction of the user.
         *  4. Creates a new subscription with the customer profile id on the next subscription date
         *  
         */

        function update_users_to_new_plan(){
           $response = "";
          $this->db->select('a.user_id, a.plan_id, a.subscription_start_date', FALSE);

            $this->db->select('b.*',FALSE);
            $this->db->select('c.first_name, c.last_name, c.address, c.phone_number, c.zip_code, c.on_board_date, c.on_board_time, c.created, c.updated, c.bda_id, c.analyst_order, c.plan_id, c.city,c.state, c.more_info',FALSE);
            $this->db->select('d.id as assistant_id, d.name as assistant_name, d.email as assistant_email, d.phone_number as assistant_phone',FALSE);
            $this->db->select('e.transaction_id', FALSE);
             $this->db->select('f.plan_amount', FALSE);
            $this->db->from('ss_users_with_updated_plans as a');
               $this->db->join('ss_users as b', 'b.id = a.user_id', 'left');
               $this->db->join('ss_transaction_details as e', 'e.user_id = a.user_id', 'left');
            $this->db->join('ss_user_details as c', 'b.id = c.user_id', 'left');
            $this->db->join('ss_customer_assistant_details as d', 'b.id = d.user_id', 'left');
             $this->db->join('ss_plans as f', 'f.id = a.plan_id', 'left');
          
            // $this->db->where('a.user_id',$user_id);
            $query=$this->db->get();
           // $query = $this->db->get_where('ss_user_details', array('user_id' => $user_id));
            $user_details = $query->result();

            /*First Get the Necessary Details from the Transaction */
            foreach($user_details as $user){
                if(!empty($user->role_id) && $user->role_id == 2 ){
         
                $get_details_about_exisiting_transaction = $this->details_about_exisiting_transaction($user->transaction_id);
                $cancel_the_current_subscription = $this->cancel_user_subscription($user->transaction_id);
                $response .="Current Transaction id of " .$user->user_id . ' is '. print_r($get_details_about_exisiting_transaction, true) . "<br>";
                $response .="Cancel Status is" .print_r($cancel_the_current_subscription,true)  .  "<br>";
                 

                  if($cancel_the_current_subscription == "OK"){
                      $new_data = array_merge((array) $user, $get_details_about_exisiting_transaction);
                     $create_new_subscription =  $this->create_subscription_from_customer_profile($new_data);
                      $response .="New Subscription Status of " .$user->user_id . 'is '.print_r($create_new_subscription,true) . "<br>";
                    
                     if(is_numeric($create_new_subscription)){


                      $this->db->set('transaction_id',$create_new_subscription);
                     // $this->db->set('transaction_date',date("Y-m-d H:i:s"));
                      $this->db->where('user_id',$user->user_id);
                      $this->db->update('ss_transaction_details');


                      $this->db->where_in('user_id', $user->user_id);
                      $this->db->delete('ss_users_with_updated_plans');  
                     }
                     else{
                      $response .= print_r($create_new_subscription); 
                      print_r($create_new_subscription);
                     }
                  }
                }
                else{

                    $response .="All Customers are updated to their new plan";
                }

            }
            // /*Send the cron results as email*/
            // $email_headers = array();
            // $admin_emails = $this->get_admin_email();
            // $email_headers['email'] = $admin_emails[3]->email;
            // $this->send_mail($email_headers, $response, "SS360 - Cron For Subscription has been successfully run");



            /*Writing the response to log file*/
            log_message('debug', $response);
            return $response;
           // return $query->result();
        }

  /**
  * Runs while a user status is updated from admin panel, active, inactive
  * Updates the User status to new status 
  *
  * From Active to in active
  *
  * Cancels the current subscription if in case he is an active user
  * Updates all the team members status in case if he is an team owner
  *
  * From inactive to active
  * 1. Reset the password on activation by admin so the user will not log in also change the status to 11
  *  2. If the customer is upgrading to team plan, delete the old entry in ss_users_plans table and update the new entry
  * @param int $user_id
  * @param int $old_status_id
  * @param int $new_status_id
  * @param string $email_id
  * @param int $old_plan_id
  * @param int $new_plan_id_selected
  * @param int $plan_hour_selected
  * @param int $plan_amount_selected
  * @param int $transaction_id
  *
  * 
  *
  */
  function update_user_status($user_id,$old_status_id, $new_status_id,$email_id, $old_plan_id, $new_plan_id_selected, $plan_hour_selected, $plan_amount_selected, $transaction_id){

    $length = 10;
    $temp_token = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    $this->db->set('status_id', $new_status_id);
    $this->db->where('id', $user_id);
    $this->db->update('ss_users');


    $cancel_the_current_subscription = $this->cancel_user_subscription($transaction_id);


    $email_headers = array();
    $email_headers['email'] = $email_id;

       /*Update all the team member status as in active*/
        $this->db->select('ss_users_members.member_id');
         $this->db->from('ss_users_members');
         $this->db->where_in('ss_users_members.user_id', $user_id);
         $member_ids = $this->db->get()->result_array();
         $members = array();
        
         if(!empty($member_ids)){
          foreach($member_ids as $member_id){
            $members[] = $member_id['member_id'];
          }


         $this->db->set('status_id', 2);
         $this->db->where_in('id',$members);
         $this->db->update('ss_users');
         }

    if((int) $new_status_id == 1){
   
        /*Logic
            1. Reset the password on activation by admin so the user will not log in also change the status to 11
            2. If the customer is upgrading to team plan, delete the old entry in ss_users_plans table and update the new entry
        */
       $this->db->set('password', md5($temp_token));
        $this->db->set('status_id', 11);
       $this->db->where('id', $user_id);
       
       $this->db->update('ss_users');

       $this->db->set('plan_id', $new_plan_id_selected);  
       $this->db->set('temp_token', $temp_token);   
       $this->db->where('user_id', $user_id);
       $this->db->update('ss_user_details');

       if((int) $new_plan_id_selected == 3){

         $this->db->where_in('user_id',$user_id);
            $this->db->delete('ss_users_plans'); 
            $user_plan = array();
            $user_plan['user_id'] = $user_id;
            $user_plan['plan_id'] = 3;
            $user_plan['plan_amount_per_hour'] = $plan_amount_selected;
            $user_plan['plan_hours'] = $plan_hour_selected;
            $user_plan['created'] = date("Y-m-d H:i:s");
            $user_plan['updated'] = date('Y-m-d H:i:s');
            $user_plan['total_plan_amount '] = $plan_amount_selected * $plan_hour_selected;
            $user_plan_details= $this->db->insert('ss_users_plans',$user_plan);
       }


      // $message = "Hi, Your Account has been activated, <br> Please use the below link to confirm <br><a href='".base_url()."/user/activate_user/".$temp_token."'>".base_url()."/user/activate_user/".$temp_token."</a>";
      // $subject = "SalesSupport360 - Your Account has been activated";
      //  $this->send_mail($email_headers,$message, $subject);

    }

    return true; 

  }

 /**
  * Runs while team owner deactives a team member from interface
  * @param int $user_id
  * @param int $old_status_id
  * @param int $new_status_id
  * @param string $email_id
  * 
  */
function update_team_member_status($user_id, $old_status_id, $new_status_id,$email_id){

  $this->db->set('status_id', $new_status_id);
  $this->db->where('id', $user_id);
  $this->db->update('ss_users');

  return true;

} 


/**
 *
 * Auth net api function for Canceling Current User Subscription
 * Users Authnet Libraries which is located @ libraries folder
 * @param int transaction_id

 */

function cancel_user_subscription($transaction_id){


    // Common Set Up for API Credentials
    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
  $merchantAuthentication->setName(API_KEY); 
  $merchantAuthentication->setTransactionKey(TRANSACTION_KEY);

    $refId = 'ref' . time();

    $request = new AnetAPI\ARBCancelSubscriptionRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $request->setRefId($refId);
    $request->setSubscriptionId($transaction_id);

    $controller = new AnetController\ARBCancelSubscriptionController($request);

    if(AUTHNET_MODE == 'sandbox'){
    $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
    }
    else{
      $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
    }

    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok"))
    {
      return "OK";
        
     }
    else
    {
        return "NO";
        
    }

    // return $response;
}

/**
 *
 *Auth net api function used to create subscription from the Customer Profile id,
 * Used while plan amount upgrade
 */
function create_subscription_from_customer_profile($data){


  $amount = $this->fetch_plan_amount($data['plan_id'],$data['user_id']);


  // Common Set Up for API Credentials
  $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
  $merchantAuthentication->setName(API_KEY); 
  $merchantAuthentication->setTransactionKey(TRANSACTION_KEY);
    
    $refId = 'ref' . time();
    $start_date = date("Y-m-d", strtotime($data['subscription_start_date']));
    // Subscription Type Info
    $subscription = new AnetAPI\ARBSubscriptionType();
    $subscription->setName("Recurring Subscription ". $data['first_name']. " ". $data['last_name'] . time());

    $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
    $interval->setLength("1");
    $interval->setUnit("months");

    $paymentSchedule = new AnetAPI\PaymentScheduleType();
    $paymentSchedule->setInterval($interval);
    $paymentSchedule->setStartDate(new DateTime($start_date));
    $paymentSchedule->setTotalOccurrences("9999");
    //$paymentSchedule->setTrialOccurrences("1");

    $subscription->setPaymentSchedule($paymentSchedule);
    $subscription->setAmount($amount);
    //$subscription->setTrialAmount("0.00");
    
    $profile = new AnetAPI\CustomerProfileIdType();
    $profile->setCustomerProfileId($data['customer_profile_id']);
    $profile->setCustomerPaymentProfileId($data['customer_payment_profile_id']);
    //$profile->setCustomerAddressId($customerAddressId);

    $subscription->setProfile($profile);

    $request = new AnetAPI\ARBCreateSubscriptionRequest();
    $request->setmerchantAuthentication($merchantAuthentication);
    $request->setRefId($refId);
    $request->setSubscription($subscription);
    $controller = new AnetController\ARBCreateSubscriptionController($request);

     if(AUTHNET_MODE == 'sandbox'){
    $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
    }
    else{
      $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
    }
    
    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
    {
        return (int) $response->getSubscriptionId();
     }
    else
    {
        
        $errorMessages = $response->getMessages()->getMessage();
         return  $errorMessages[0]->getText();
    
       
    }

   // return $response;
}

/**
 *
 *Auth net api function to return the details about the transaction so we can cancel and create a new subscription from it
 * @param int transaction_id
 * @return mixed[customer_profile_id, customer_payment_profile_id]
 */
function details_about_exisiting_transaction($transaction_id){

  // Common Set Up for API Credentials
  $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
  $merchantAuthentication->setName(API_KEY); 
  $merchantAuthentication->setTransactionKey(TRANSACTION_KEY);
    
    $refId = 'ref' . time();
    
    // Creating the API Request with required parameters
    $request = new AnetAPI\ARBGetSubscriptionRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $request->setRefId($refId);
    $request->setSubscriptionId($transaction_id);
    
    // Controller
    $controller = new AnetController\ARBGetSubscriptionController($request);
    

    if(AUTHNET_MODE == 'sandbox'){
    $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
    }
    else{
      $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
    }
    

    
    if ($response != null) 
    {
      if($response->getMessages()->getResultCode() == "Ok")
      {
          $return_data = array();
          $return_data['customer_profile_id'] = $response->getSubscription()->getProfile()->getCustomerProfileId();
        $return_data['customer_payment_profile_id'] =  $response->getSubscription()->getProfile()->getPaymentProfile()->getCustomerPaymentProfileId(); 
          return $return_data;
      }
      else
      {
        return $response->getMessages()->getMessage();
      }
    }
    else
    {
      // Failed to get response
      return "Null Response Error";
    }


}

/**
 *
 *Auth net api function to create subscription runs while registration admin customer signup,customer initiation 
 * @param array $data
 * @param string $transaction_date
 * @return subscription_id
 */
function authorize_arb_payment($data, $transaction_date = NULL){

set_time_limit(0);

  $expiry_date = (string)(trim($data['expiry-year'])."-".trim($data['expiry-month']));
 
    //$tr_details = $this->getTransactionDetails($data['x_trans_id']);

  // Common Set Up for API Credentials
  $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
  $merchantAuthentication->setName(API_KEY); 
  $merchantAuthentication->setTransactionKey(TRANSACTION_KEY);

  $refId = 'ref' . time();
  if(!empty($transaction_date) && $transaction_date !== NULL && isset($transaction_date)){

    $billing_date = date('Y-m-d', strtotime($transaction_date));
  }
  else{
    $billing_date = date('Y-m-d',strtotime('now')); 
  }
 
  // Subscription Type Info
  $subscription = new AnetAPI\ARBSubscriptionType();
  $subscription->setName("Recurring Payment for " . $data['first_name']);

  $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
  $interval->setLength("1");
  $interval->setUnit("months");

  $paymentSchedule = new AnetAPI\PaymentScheduleType();
  $paymentSchedule->setInterval($interval);
  $paymentSchedule->setStartDate(new DateTime($billing_date));
  $paymentSchedule->setTotalOccurrences("9999");
  //$paymentSchedule->setTrialOccurrences(0);

  $subscription->setPaymentSchedule($paymentSchedule);
  $amount = $this->fetch_plan_amount($data['plan_id_selected'],$data['user_id']);

  $subscription->setAmount($amount);
 // $subscription->setTrialAmount("0.00");
  
  $creditCard = new AnetAPI\CreditCardType();
  $creditCard->setCardNumber($data['card_number']);
  $creditCard->setExpirationDate($expiry_date);



  $payment = new AnetAPI\PaymentType();
  $payment->setCreditCard($creditCard);

  $subscription->setPayment($payment);



  $billTo = new AnetAPI\NameAndAddressType();
  $billTo->setFirstName($data['first_name']);
  $billTo->setLastName($data['last_name']);

  $subscription->setBillTo($billTo);

  $request = new AnetAPI\ARBCreateSubscriptionRequest();
  $request->setmerchantAuthentication($merchantAuthentication);
  $request->setRefId($refId);
  $request->setSubscription($subscription);
  $controller = new AnetController\ARBCreateSubscriptionController($request);




  if(AUTHNET_MODE == 'sandbox'){
    $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
  }
  else{
      $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);
  }

  if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
  {
      return (int) $response->getSubscriptionId();
   }
  else
  {
     $errorMessages = $response->getMessages()->getMessage();
         return  "Payment Gateway Response : " . $errorMessages[0]->getText() . "\n";
    
  }


   }

     /**
    * Runs while a bda is assigned to customer, updates all customer and their team member with the selected bda
    * @param array $customer_ids
    * @param int bda_id
    * @return int
    */
   function assign_bda($customer_ids,$bda_id){


      $this->db->set('bda_id', $bda_id);
      $this->db->where_in('user_id',$customer_ids);
      $this->db->update('ss_tasks');

      $this->db->set('bda_id', $bda_id);
      $this->db->where_in('user_id',$customer_ids);
    $results = $this->db->update('ss_user_details');

    /*Update Team member bda as well*/

      $this->db->select('ss_users_members.member_id');
       $this->db->from('ss_users_members');
       $this->db->where_in('ss_users_members.user_id', $customer_ids);
       $member_ids = $this->db->get()->result_array();
       $members = array();
       if(!empty($member_ids)){
        foreach($member_ids as $member_id){
          $members[] = $member_id['member_id'];
        }
          $this->assign_bda($members, $bda_id);
       
       }



return $results;
    
   }
    /**
    * Fetch Status Id from Status table
    * @param string $type
    * @param string $name
    * @return int
    */
    
  function fetch_status($type, $name){

    $this->db-> select('id');
    $this -> db -> from('ss_status');
    $this -> db -> where('type', $type);
    $this -> db -> where('name', $name);
    $this -> db -> limit(1);
    $status_query = $this->db->get();
    if($status_query->num_rows() == 1)
    {
      $status_obj = $status_query->result();
      return $status_obj[0]->id;
    } 
    else
    {
      return 2;
    } 

  }





  /**
    * Fetch Role Id from Role table
    * @param string $name
    * @return int
    */
  function fetch_role($name){

    $this->db->select('id');
    $this->db->from('ss_roles');

    $this->db->where('name', $name);
    $this->db->limit(1);
    $role_query = $this->db->get();
    if($role_query->num_rows() == 1)
    {
      $role_obj = $role_query->result();
      return $role_obj[0]->id;
    } 
    else
    {
      return 0;
    } 

  }

      /**
    * Fetch Plan Id from Plans table
    * @param string $type
    * @param string $name
    * @return int
    */
    
  function fetch_plan($amount){
        
    $this->db-> select('id');
    $this -> db -> from('ss_plans');
    $this -> db -> where('plan_amount', floatval($amount));
     $this -> db -> where('status_id', 1);
    $this -> db -> limit(1);
    $plan_query = $this->db->get();
    if($plan_query->num_rows() == 1)
    {
      $plan_obj = $plan_query->result();
      return $plan_obj[0]->id;
    } 
    else
    {
      return 2;
    } 

  }


    /**
    * Fetch Plan amount from 
    * Plans table in case of Rainmaker and Prospector
    * Users Plans table in case of Team plan 
    * @param int $id
    * @param int $user_id
    * @return mixed
    */
    
    function fetch_plan_amount($id, $user_id){
    $id = (int) $id;
    $user_id = (int) $user_id;
    if($id !== 3){    

    $this->db-> select('plan_amount');
    $this -> db -> from('ss_plans');
    $this -> db -> where('id', $id);
     $this -> db -> where('status_id', 1);
    $this -> db -> limit(1);
    $plan_query = $this->db->get();
    if($plan_query->num_rows() == 1)
    {
      $plan_obj = $plan_query->result();
      return $plan_obj[0]->plan_amount;
    } 
    else
    {
      return 0;
    } 
    }
    else{
   $this->db-> select('total_plan_amount');
    $this -> db -> from('ss_users_plans');
    $this -> db -> where('plan_id', $id);
     $this -> db -> where('user_id', $user_id);
    $this -> db -> limit(1);
    $plan_query = $this->db->get();
    if($plan_query->num_rows() == 1)
    {
      $plan_obj = $plan_query->result();
      return $plan_obj[0]->total_plan_amount;
    } 
    else
    {
      return 0;
    }


    }

  }

  /**
    * Fetch Plan amount from 
    * Plans table in case of Rainmaker and Prospector
    * @param int $id
    *
    */
  function fetch_actual_plan_hours($id){
        
    $this->db-> select('plan_hours');
    $this -> db -> from('ss_plans');
    $this -> db -> where('id', $id);
     $this -> db -> where('status_id', 1);
    $this -> db -> limit(1);
    $plan_query = $this->db->get();
    if($plan_query->num_rows() == 1)
    {
      $plan_obj = $plan_query->result();
      return $plan_obj[0]->plan_hours;
    } 
    else
    {
      return 0;
    } 

  }

   function fetch_team_plan_hours($id){
        
    $this->db-> select('plan_hours');
    $this->db->from('ss_users_plans');
    $this->db->where('user_id', $id);  
    $this->db->limit(1);
    $plan_query = $this->db->get();
    if($plan_query->num_rows() == 1)
    {
      $plan_obj = $plan_query->result();
      return $plan_obj[0]->plan_hours;
    } 
    else
    {
      return 0;
    } 

  }


  /**
    * Fetch Plan name from 
    * Plans table 
    * @param int $id
    *
    */
    function fetch_plan_name($id){
        
    $this->db-> select('name');
    $this -> db -> from('ss_plans');
    $this -> db -> where('id', $id);
     $this -> db -> where('status_id', 1);
    $this -> db -> limit(1);
    $plan_query = $this->db->get();
    if($plan_query->num_rows() == 1)
    {
      $plan_obj = $plan_query->result();
      return $plan_obj[0]->name;
    } 
    else
    {
      return 0;
    } 

  }

    /**
    *
    *
    *This function returns all active plans in the site
    */
    function get_all_plans(){
        
    $this->db-> select('id' );
    $this->db->select('name');
       $this->db->select('description');
    $this -> db -> from('ss_plans');
      $this -> db -> where('status_id', 1);
  
    $plan_query = $this->db->get();
  
    if($plan_query->num_rows() !== 0)
    {
      $plan_obj = $plan_query->result();
      return $plan_obj;
    } 
    else
    {
      return FALSE;
    } 

  }


     /**
      * 
      *This function returns all active cards in the site
      */
    function get_all_cards(){
        
    $this->db-> select('id' );
    $this->db->select('name');
      // $this->db->select('description');
    $this -> db -> from('ss_cards');
      $this -> db -> where('status_id', 1);
  
    $plan_query = $this->db->get();
  
    if($plan_query->num_rows() !== 0)
    {
      $plan_obj = $plan_query->result();
      return $plan_obj;
    } 
    else
    {
      return FALSE;
    } 

  }

    /**
    * Common Function to send mail accessed from all controllers
    */
    function send_mail($data, $message, $subject, $cc_array = NULL,$bda_email = NULL,$from_bda = NULL,$bda_in_bcc = NULL,$ambassador=NULL) {


if(empty($ambassador)){

 $sincerely = "<br /><br /><br />Sincerely, <br />SalesSupport360 Team</td>";
}
else{
$sincerely = "";
}
      /*temp fix to avoid mail sending during data migration and go live process*/
      
     //return TRUE;  


      $imgPath = PHASE1_URL. 'assets/images/logo_email.png';
      $email_teamplate = "<html><body><table border='0' cellspacing='0' cellpadding='0' width='580' align='center'>
 <tbody>
   <tr>
  <td style='BORDER-BOTTOM:#164167 1px solid;BORDER-LEFT:#164167 1px solid;BORDER-TOP:#164167 1px solid;BORDER-RIGHT:#164167 1px solid;' valign='top' align='middle'>
  <table border='0' cellspacing='0' cellpadding='0' width='99%' align='center'>
  <tbody><tr><td style='WIDTH:10px;HEIGHT:0px;' valign='top' align='left'></td><td height='9'></td>
    <td height='9' valign='top' width='10' align='right'></td></tr>
    <tr><td>&nbsp;</td><td valign='top' align='left'><table border='0' cellspacing='0' cellpadding='0' width='100%'><tbody>
    <tr  style='background: #164167;'><td style='PADDING-LEFT:1px;FONT-FAMILY:Verdana, Arial, Helvetica, sans-serif;COLOR:#63adf3;FONT-SIZE:15pt;FONT-WEIGHT:bold;' height='66' valign='center' align='center'>

        <img width='211' height='53' src='".$imgPath . "'></td>

       
            <td style='PADDING-RIGHT:7px;' valign='center' align='right'></td></tr>
              <tr valign='top' align='middle'><td valign='top' colspan='2' align='middle'>
               <table border='0' cellspacing='0' cellpadding='0' width='100%'>
              <tbody><tr><td style='WIDTH:4px;HEIGHT:7px;' valign='top' align='left'></td><td valign='center' width='0%' align='left'><hr color='#164167' size='5' /></td><td style='WIDTH:4px;HEIGHT:7px;'></td></tr></tbody></table></td></tr><tr valign='top' align='middle'><td valign='top' colspan='2' align='middle'>&nbsp;</td></tr><tr valign='top' align='middle'><td valign='top' colspan='2' align='left'><table border='0' cellspacing='0' cellpadding='0' width='100%'><tbody>
  <tr><td style='TEXT-ALIGN:justify;LINE-HEIGHT:17px;PADDING-LEFT:5px;PADDING-RIGHT:5px;FONT-FAMILY:Verdana, Arial, Helvetica, sans-serif;COLOR:#666666;FONT-SIZE:8pt;PADDING-TOP:8px;'>".

$message .
      $sincerely."</tr> </tbody></table></td></tr>
    <tr valign='top' align='middle'><td valign='top' colspan='2' align='middle'>&nbsp;</td></tr></tbody></table></td><td>&nbsp;</td></tr>
    <tr><td valign='bottom' align='left'>&nbsp;</td>
        <td style='TEXT-ALIGN:center;FONT-FAMILY:Verdana, Arial, Helvetica, sans-serif;COLOR:#000000;FONT-SIZE:9pt;VERTICAL-ALIGN:top;BORDER-TOP:#164167 2px solid;PADDING-TOP:8px;' valign='bottom' align='middle'>
            </td> <td valign='bottom' align='right'>&nbsp;</td></tr>
  <tr><td style='WIDTH:10px;HEIGHT:30px;' valign='bottom' align='left'>&nbsp;</td>
        <td style='HEIGHT:30px;' valign='bottom' align='middle'></td><td style='WIDTH:10px;HEIGHT:30px;' valign='bottom' align='right'>&nbsp;</td></tr>
    </tbody></table></td></tr>
    <tr><td style='PADDING-RIGHT:10px;HEIGHT:31px;PADDING-TOP:5px;' valign='top' align='right'>
        <span style='FONT-FAMILY:Verdana, Arial, Helvetica, sans-serif;COLOR:#949494;FONT-SIZE:8pt;'>All Rights Reserved </span></td></tr>
    </tbody></table></body></body></html>";


      if($from_bda == NULL){

          $config = array();
//          $config['protocol'] = 'sendmail';
// $config['mailpath'] = '/usr/sbin/sendmail';
// $config['charset'] = 'iso-8859-1';
// $config['mailtype'] = 'html';
// $config['wordwrap'] = TRUE;

        $config['protocol']    = 'smtp';
        $config['smtp_host']    = SMTP_HOST;
        $config['smtp_port']    = SMTP_PORT;
        $config['smtp_timeout'] = '7';
        $config['smtp_user']    = SMTP_USER;
        $config['smtp_pass']    = SMTP_PASSWORD;
        $config['charset']    = 'utf-8';
        $config['newline']    = "\r\n";
        $config['mailtype'] = 'html'; // or html
        $config['validation'] = TRUE; 

      }
      else{
        $config = array();
        $config['protocol']    = 'smtp';
        $config['smtp_host']    = SMTP_HOST;
        $config['smtp_port']    = SMTP_PORT;
        $config['smtp_timeout'] = '7';
        $config['smtp_user']    = SMTP_USER;
        $config['smtp_pass']    = SMTP_PASSWORD;
        $config['charset']    = 'utf-8';
        $config['newline']    = "\r\n";
        $config['mailtype'] = 'html'; // or html
        $config['validation'] = TRUE; 

      }


        $this->load->library('email', $config);
        $this->email->clear();
        $this->email->set_newline("\r\n");
      
      if($from_bda == NULL){    
        $this->email->from(SMTP_USER,SMTP_USER); // change it to yours 
      }
      else{

        $this->email->from($from_bda, $from_bda);
      }
      
       if($subject == 'New Task Creation'){

         $recipt = $this->Users_model->admin_emails();
         $mails = $recipt;

         if(!empty($data['email'])){

          $mails[]= $data['email']." "."<".$data['email'].">";
         }
        
          $this->email->to($mails);

       }

      

       else{

          $this->email->to($data['email']);

       }
  


        if(!empty($cc_array)){

            $this->email->cc($cc_array);
        }
     
        if(!empty($bda_in_bcc)){

        $this->email->bcc($bda_in_bcc);
      }
     
        $this->email->subject($subject);
        $this->email->message($email_teamplate);

        if(!empty($data['attachments']) && isset($data['attachments'])){
          foreach($data['attachments'] as $attach){
            $file = FCPATH."/assets/files/". $attach->path;
            $this->email->attach($file);
          }
        }
       
 if(!empty($data['file']) && isset($data['file'])){
          
           
            $this->email->attach($data['file']);
       
        }
       

        
        if($this->email->send()) {
             return true;
            // redirect('/userlogin?user_name='.$x_email.'&password='.$password); 
            echo 'Email sent.';

            //exit()
        } else {
            echo $this->email->print_debugger();
           exit();
            return false;
        }
    }
    function get_bda_list(){
      $this->db->select('a.id',FALSE);
    $this->db->select('b.first_name', FALSE);
    $this->db->from('ss_users as a');
    $this->db->join('ss_user_details as b', 'a.id = b.user_id', 'left');
    $this->db->where('a.role_id',3);
      $query=$this->db->get();
     return $query->result();
    }

  /**
   *  Function runs returns user object while taking over 
   */
  public function get_switch_user($user_id){

    $this->db->select('first_name,plan_id, notification_hour');
    $this->db->select('username');
      $this->db->select('company');
    $this->db->select('role_id');
    $this->db->from('ss_users a');
    $this->db->join('ss_user_details as b', 'a.id = b.user_id', 'left');
    $this->db->where('a.id',$user_id);

    $query = $this->db->get();
  
    if($query->num_rows() !== 0)
    {
      $obj = $query->result();
     // echo "<pre>";
      //print_r($obj);
     //echo "</pre>";
      //exit();
       return $obj;
    } 
    else
    {
      return FALSE;
    } 

}



public function get_user_data($user_id){

    $this->db->select('role_id');
    $this -> db -> from('ss_users');
    $this -> db -> where('id',$user_id);
     $query = $this->db->get();
  
    if($query->num_rows() !== 0)
    {
      $obj = $query->result();
     
       return $obj;
    } 
    else
    {
      return FALSE;
    } 



}

public function get_password_from_db($user_id){
   $this->db->select('password');

    $this -> db -> from('ss_users');
    $this -> db -> where('id',$user_id);
     $query = $this->db->get();
  
    if($query->num_rows() !== 0)
    {
      $obj = $query->result();
       //print_r($obj);
      // exit();
     
       return $obj;

    } 
    else
    {
      return FALSE;
    } 

}



public function get_All_users(){

    $this->db->select('id');
    $this->db->select('first_name');

    $this -> db -> from('ss_user_details');
    
     $query = $this->db->get();
  
    if($query->num_rows() !== 0)
    {
      $obj = $query->result();
      
       return $obj;

    } 
    else
    {
      return FALSE;
    } 

}


public function get_members_ids($user_id){


    $this->db->select("ss_users_members.member_id");
    $this->db->from('ss_users_members');
    $this->db->join('ss_user_details','ss_user_details.user_id = ss_users_members.member_id', 'left');
    $this->db->where('ss_users_members.user_id',$user_id);
    $member_ids = $this->db->get()->result_array();
    
    $users = array();
     if(!empty($member_ids)){
        foreach($member_ids as $key => $member_id){

          $users[] = $member_id['member_id'];
        }
     }
        return $users;



}



public function get_members($user_id){


    $this->db->select("ss_users_members.member_id");
    $this->db->select("ss_user_details.first_name");
     $this->db->select("ss_user_details.last_name");
    $this->db->from('ss_users_members');
    $this->db->join('ss_user_details','ss_user_details.user_id = ss_users_members.member_id', 'left');
    $this->db->where('ss_users_members.user_id',$user_id);

   $query = $this->db->get()->result();

   return $query;


}

  public function fetch_members($user_id, $status, $search_array = NULL, $limit_start = NULL, $limit_end = NULL){



    if($status == 'active'){
      $status_id = 1;
    }
    else{
      $status_id = 2;
    }

    $this->db->select("ss_users_members.member_id");
    $this->db->select('ss_user_details.*');
    $this->db->select('ss_users.*');
    $this->db->select('COUNT(task.id) as no_of_reports');
    $this->db->from('ss_users_members');
    $this->db->join('ss_user_details','ss_user_details.user_id = ss_users_members.member_id', 'left');
    $this->db->join('ss_users','ss_users.id = ss_users_members.member_id', 'left');
    $this->db->join('ss_tasks as task','task.user_id = ss_users.id', 'left');
    $this->db->where('ss_users_members.user_id', $user_id);

     $this->db->where('ss_users.status_id', $status_id);
     
    if(isset($search_array) && $search_array !== NULL && isset($search_array['search_from_date']) && !empty($search_array['search_from_date'])){
      
        $this->db->where('ss_users.created >=',date("Y-m-d H:i:s",strtotime($search_array['search_from_date'] .' '. '00:00:00')));
    }
    if(isset($search_array) && $search_array !== NULL && isset($search_array['search_to_date']) &&!empty($search_array['search_to_date'])){

        $this->db->where('ss_users.created <=',date("Y-m-d H:i:s",strtotime($search_array['search_to_date']  .' '. '23:59:59')));
    }

    if(isset($search_array) && $search_array !== NULL && isset($search_array['search_customer']) && !empty($search_array['search_customer'])){
       $this->db->where('ss_users.id', $search_array['search_customer']);
    }
           
    $this->db->group_by('ss_users.id');
    $this->db->order_by('ss_users.id', 'DESC');
     if($limit_start && $limit_end){
          $this->db->limit($limit_start, $limit_end); 
        }
         if($limit_start != null){
          $this->db->limit($limit_start, $limit_end);    
        }
    $query = $this->db->get();
    $obj = $query->result();

    $result_array = array();
      foreach($obj as $user_detail){
        $user_id = $user_detail->id;
$this->db->select('ss_tasks.id');
$this->db->from('ss_tasks');
$this->db->where('ss_tasks.user_id', $user_id);
$where_clause = $this->db->get_compiled_select();
// $this->db->_reset_select();


              $this->db->select_sum('log_hrs');
      $this->db->select_sum('log_min');
      $this->db->from('ss_work_logs');
      $this->db->where("ss_work_logs.task_id IN($where_clause)", NULL, FALSE);
      $query = $this->db->get()->result();


     $logged_hour = $query[0]->log_hrs;

      $logged_min = $query[0]->log_min;



      $calculated_hours = floor($logged_min/60);
      $calculated_min = $logged_min%60;

      $logged_hour +=$calculated_hours;
      $logged_min = $calculated_min;
      $user_detail->log_hrs = $logged_hour;
      $user_detail->log_min = $logged_min;
      $result_array[] = $user_detail;
      }
        

      
        
         return $result_array;
  }


  public function get_customers_for_bda($user_id = NULL,$status = NULL){


    $this->db->select('first_name');
    $this->db->select('last_name');
    $this->db->select('user_id');
    $this->db->from('ss_users a');
    $this->db->join('ss_user_details as b', 'a.id = b.user_id', 'left');
    //$this->db->where('a.role_id',2);
    $this->db->where('b.bda_id',$user_id);

    if($status){
    $this->db->where('a.status_id',1);
  }
    $query = $this->db->get();
    return  $query->result();


  }

	

   function fetch_customers_for_bda($role_id, $user_id,$limit_start = NULL,$limit_end = NULL ,$order_by_analyst_order = NULL){


    $this->db->select('a.*',FALSE);
    $this->db->select('b.analyst_order,b.first_name, b.last_name,b.phone_number, b.user_id', FALSE);
    $this->db->from('ss_users as a');
    $this->db->join('ss_user_details as b', 'a.id = b.user_id', 'left');
    $this->db->where('a.role_id',$role_id);
 $this->db->where('b.bda_id =',$user_id);
  if($limit_start && $limit_end){
          $this->db->limit($limit_start, $limit_end); 
        }
         if($limit_start != null){
          $this->db->limit($limit_start, $limit_end);    
        }


    if($order_by_analyst_order !==NULL){
  $this->db->order_by("b.analyst_order", "asc");
        }
        else{
    $this->db->order_by("a.id", "desc");
        }
    $query=$this->db->get();
     return $query->result();


  }


  function get_active_company_list($status = NULL,$conditions = NULL){


    $this->db->select('a.*',FALSE);

    $this->db->select('b.*',FALSE);

   
    $this->db->from('ss_users as a');
    $this->db->join('ss_user_details as b', 'a.id = b.user_id', 'left');
   
    $this->db->where('a.company', 1);

    if($status == NULL){

      $this->db->where('a.status_id', 1);
    }



  if(isset($conditions['search_company'])  && $conditions['search_company']!== NULL && !empty($conditions['search_company'])){
    
    $this->db->like('b.company_name',$conditions['search_company']);

    }

    $query=$this->db->get();
  
    return $query->result();

  }

  

   function get_inactive_company_list($status = NULL, $conditions = NULL ){


    $this->db->select('a.*',FALSE);

    $this->db->select('b.*',FALSE);

   
    $this->db->from('ss_users as a');
    $this->db->join('ss_user_details as b', 'a.id = b.user_id', 'left');
   
    $this->db->where('a.company', 1);

    if($status == NULL){

      $this->db->where('a.status_id', 2);
    }


     if(isset($conditions['search_company'])  && $conditions['search_company']!== NULL && !empty($conditions['search_company'])){
    
    $this->db->like('b.company_name',$conditions['search_company']);

    }


    $query=$this->db->get();
     
    return $query->result();

  }




   function customers_for_bda($user_id,$order=null, $order_type, $limit_start, $limit_end,$search, $status_id=null){
    $this->db->select('a.*',FALSE);
    $this->db->select('b.first_name, b.last_name, b.address, b.phone_number, b.zip_code, b.on_board_date, b.on_board_time, b.created, b.updated, b.bda_id, b.analyst_order,b.temp_token, b.plan_id, b.city, b.state, b.more_info',FALSE);
    // $this->db->select('e.first_name as bda_name', FALSE);
  
    $this->db->from('ss_users as a');

    $this->db->join('ss_user_details as b', 'a.id = b.user_id', 'left');
    $this->db->join('ss_roles as c', 'a.role_id = c.id', 'left');
    // $this->db->join('ss_user_details as e', 'b.bda_id = e.user_id', 'left');

    $this->db->where('a.role_id',2);


    if($user_id !== NULL)
    {

    $this->db->where('b.bda_id',$user_id);
  }
   
    if($status_id !== NULL){

 $this->db->where('a.status_id',$status_id);

    }

    else{


    $this->db->where('a.status_id',1);
    }
    if($search !== NULL && !empty($search)){

    
      if(!empty($search['search_customer'])){
      $this->db->like('b.user_id',$search['search_customer']);
      }
      if(!empty($search['search_bda'])){

        $this->db->where('b.bda_id', $search['search_bda']);
      }

      if(!empty($search['search_from_date'])){

         $this->db->where('a.created >=',date("Y-m-d H:i:s", strtotime($search['search_from_date'] . ' ' . '00:00:00')));
      }

      if(!empty($search['search_to_date'])){

         $this->db->where('a.created <=',date("Y-m-d H:i:s", strtotime($search['search_to_date'] . ' ' . '23:59:59')));
      }

      if(isset($search['search_plans']) && !empty($search['search_plans'])){

        $this->db->where('b.plan_id', $search['search_plans']);
      }
    }

      $this->db->order_by("a.id", "desc");
    if($limit_start && $limit_end){
          $this->db->limit($limit_start, $limit_end); 
        }
         if($limit_start != null){
          $this->db->limit($limit_start, $limit_end);    
        }

    $query=$this->db->get();
   // $query = $this->db->get_where('ss_user_details', array('user_id' => $user_id));
   
    return $query->result();
  }





public function fetch_parent($user_id){


    $this->db->select("ss_users_members.user_id");
    $this->db->select('ss_user_details.*');
    
    $this->db->from('ss_users_members');
    $this->db->join('ss_user_details','ss_user_details.user_id = ss_users_members.user_id', 'left');
   
    $this->db->where('ss_users_members.member_id', $user_id);

    $query=$this->db->get();
   
   return $query->result();




   }



   public function get_team_members($user_id){

    $this->db->select("ss_users_members.member_id");
    $this->db->select('ss_user_details.first_name');
    $this->db->select('ss_user_details.last_name');
  

    $this->db->from('ss_users_members');

    $this->db->join('ss_user_details','ss_user_details.user_id = ss_users_members.member_id', 'left');


    $this->db->where('ss_users_members.user_id',$user_id);
  
       $query=$this->db->get();
   
      return $query->result();

    



   }


   public function get_member_ids($user_id){

    $this->db->select("ss_users_members.member_id");
     $this->db->from('ss_users_members');
     $this->db->join('ss_user_details','ss_user_details.user_id = ss_users_members.member_id', 'left');
      $this->db->where('ss_users_members.user_id', $user_id);


      $query=$this->db->get();
   
      return  $query->result();

     

   }

   public function get_customer_id($user_id)
   {

      $this->db->select("ss_user_details.user_id as member_id");

       $this->db->from('ss_user_details');

        $this->db->where('ss_user_details.user_id', $user_id);


      $query=$this->db->get();
   
      return $query->result();


   }


   /**
    *
    *This function will run when a customer tries to update his/her credit card details from interface
    * the same function is accesible from admin interface to
    *Use case:
    *
     * 1. Customer updates the credit card detail in case of loss/expiry.
         The next recurring subscription would be from new card.
     * 2. Customer billing doesn’t happen because of the credit card issues. 
         The customer can update the credit card details so that the recurring subscription will happens from the next recurring month. The missed billing has to be dealt manually.
     *
     *Logic
     *
     * 1. Fetch User's Subscribed date, and calculate the next subscription date
     * 2. Cancel the current subscription and trigger new subscription based on the customer profile, with calculated next subscription date
     * 3. Update New Subscription id in the transaction table
   */
   public function update_credit_card_details($user_details, $data){

        $response = "Updating Credit Card Details of ". $user_details[0]->id;
        $this->db->select('ss_transaction_details.transaction_date',FALSE);
        $this->db->from('ss_transaction_details');
        $this->db->where('ss_transaction_details.user_id', (int) $user_details[0]->id);
      $transaction_date = $this->db->get()->result();
      $response .=  "First Transaction Date is" . print_r($transaction_date, true);
      $this->load->model('Plan_model', 'plan_model');
      $next_transaction_date = $this->plan_model->calculate_next_subscription_date($transaction_date[0]->transaction_date);

      $response .=  "Next Transaction Date is" . print_r($next_transaction_date, true);
      $this->db->select('a.*',FALSE);
      $this->db->select('b.first_name, b.last_name, b.address, b.phone_number, b.zip_code, b.on_board_date, b.on_board_time, b.created, b.updated, b.bda_id, b.analyst_order, b.plan_id, b.city,b.state, b.more_info',FALSE);
      $this->db->select('e.transaction_id, e.transaction_date', FALSE);
      $this->db->from('ss_users as a');
      $this->db->join('ss_user_details as b', 'b.user_id = a.id', 'left');
      $this->db->join('ss_transaction_details as e', 'e.user_id = a.id', 'left');

      $this->db->where('a.id', (int) $user_details[0]->id);
      $user=$this->db->get()->result();
      $user[0]->user_id = $user[0]->id;
        $user[0]->subscription_start_date = $next_transaction_date;
     
      $get_details_about_exisiting_transaction = $this->details_about_exisiting_transaction($user[0]->transaction_id);

  $response .=  "Exisiting Transaction Details are" . print_r($get_details_about_exisiting_transaction, true);
      $new_data = array_merge((array) $user[0], $get_details_about_exisiting_transaction);
   

      $cancel_the_current_subscription = $this->cancel_user_subscription($user[0]->transaction_id);

        $response .=  "Cancel Status is" . print_r($cancel_the_current_subscription, true);


        if($cancel_the_current_subscription !== "NO"){
      $create_new_subscription =  $this->create_subscription_from_customer_profile($new_data);


        $response .=  "New Subscription Status is " . print_r($create_new_subscription, true);
              
 log_message('debug', $response);
 
       
      if(is_numeric($create_new_subscription)){
 	$response .=  "New Subscription Status is" . print_r($create_new_subscription, true);
      $this->db->set('transaction_id',$create_new_subscription);
      // $this->db->set('transaction_date',date("Y-m-d H:i:s"));
      $this->db->where('user_id',$user[0]->id);
      $this->db->update('ss_transaction_details');


      $this->db->where_in('user_id', $user[0]->id);
      $this->db->delete('ss_users_with_updated_plans'); 

      return $create_new_subscription;
    }
    else{
     return $create_new_subscription;
    }

    }

    else{
       log_message('debug', $response);
    return "Current transaction cannot be cancelled.";
    }

  }

  /*This func is for fetching the notification email address ss_admin_email table

  */


  function admin_emails(){

     $this->db->select('email');
     $this->db->from('ss_admin_email');
     $query = $this->db->get()->result();

     $cc_return = array();
     foreach($query as $user){

      $cc_return[] = $user->email;
     }
     return $cc_return;
  } 

  
 

  /**
  * This function will fetch the following details about the users who are not in team plan
  * Active Users
  * their Tasks
  * Their Tasks Sum of Work Logs
  * Their plan Hours
  * Calculating 50, 75, 100 from their plan hours
  *
  */
// function get_customers_tasks($bda_id = NULL){
                                                                                                                                                                                                                                                                                                                                                                                                                                                       
//     $this->db->select("ss_users.*");
//     $this->db->select("ss_user_details.plan_id,ss_user_details.bda_id,GROUP_CONCAT(ss_user_details.first_name, ss_user_details.last_name) as name");

//     $this->db->select("GROUP_CONCAT(DISTINCT(ss_tasks.id)) as task_id");

//     $this->db->select('ss_user_details.first_name,ss_user_details.email_notification_status');
//      $this->db->select('ss_user_details.last_name');
//     $this->db->select('ss_plans.plan_hours');
//     $this->db->select('ss_plans.name as plan_type');
//     $this->db->select('round((ss_plans.plan_hours * 50) / 100) as fifty_percent_hours');
//     $this->db->select('round((ss_plans.plan_hours * 80) / 100) as seventy_percent_hours');
//     $this->db->select('round((ss_plans.plan_hours * 100) / 100) as hunderd_percent_hours');

 
//     $this->db->select('@hour_calculated  := round((SUM(log_min) / 60)+(SUM(ss_work_logs.log_hrs))) as final_logged_hours');
//     $this->db->select('@min_calculated := round(SUM(log_min) % 60) final_min_to_add');

//     $this->db->from('ss_users');
//     $this->db->join('ss_user_details','ss_user_details.user_id = ss_users.id', 'left');
//     $this->db->join('ss_tasks','ss_tasks.user_id = ss_users.id', 'left');
//     $this->db->join('ss_work_logs','ss_work_logs.task_id = ss_tasks.id', 'left');
//     $this->db->join('ss_plans','ss_user_details.plan_id = ss_plans.id', 'left');

//        $this->db->where('ss_users.role_id',2);

//   if(!empty($bda_id)){
//          $this->db->where('ss_user_details.bda_id',$bda_id);
//         }

//     $this->db->where('ss_users.company',0);
//     $this->db->where('ss_user_details.plan_id !=', 3);

//     if(empty($bda_id)){ 

//    $this->db->where_in('ss_user_details.email_notification_status',array(12,13,14,15));
//   }
//        $this->db->where('ss_users.status_id',1);

//     $this->db->group_by('ss_users.id');
//     $query=$this->db->get();

//     // $users = $query->result();
//     // echo "<pre>";
//     // print_r($users);
//     // exit();
//     return $query->result();
//   }


 
// function get_teamowner_tasks($bda_id = NULL){
//     /*Get Team plan users and their team members*/                                    
//     $this->db->select("ss_users.*");
//     $this->db->select("ss_user_details.plan_id,ss_user_details.bda_id, ss_user_details.email_notification_status,ss_user_details.first_name,ss_user_details.last_name");
//     $this->db->select('ss_users_plans.plan_hours as team_plan_hours');
//     $this->db->select('ss_plans.plan_hours');
//         //$this->db->select('ss_plans.plan_hours');

//     $this->db->select('ss_plans.name as plan_type');

//     $this->db->select('round((ss_users_plans.plan_hours * 50) / 100) as team_fifty_percent_hours');
//     $this->db->select('round((ss_users_plans.plan_hours * 80) / 100) as team_seventy_percent_hours');
//     $this->db->select('round((ss_users_plans.plan_hours * 100) / 100) as team_hunderd_percent_hours');

//    $this->db->select('round((ss_plans.plan_hours * 50) / 100) as fifty_percent_hours');
//     $this->db->select('round((ss_plans.plan_hours * 80) / 100) as seventy_percent_hours');
//     $this->db->select('round((ss_plans.plan_hours * 100) / 100) as hunderd_percent_hours');

//     $this->db->select("GROUP_CONCAT(ss_users_members.member_id) as member_id, ss_users_members.user_id"); 
//     $this->db->join('ss_users_members','ss_users_members.user_id = ss_users.id', 'left');
//     $this->db->join('ss_user_details','ss_user_details.user_id = ss_users.id', 'left');
//       $this->db->join('ss_users_plans','ss_users_plans.user_id = ss_users.id', 'left');

//       $this->db->join('ss_plans','ss_plans.id = ss_user_details.plan_id', 'left');
//             $this->db->from('ss_users');
//             $this->db->where('ss_users.role_id',2);
//  $this->db->where('ss_users.status_id',1);
//  $this->db->where_in('ss_user_details.email_notification_status',array(12,13,14,15));
//   $this->db->where('ss_user_details.plan_id =', 3);  
//   $this->db->or_where('ss_users.company',1); 

//    if(!empty($bda_id)){

//         $this->db->where('ss_user_details.bda_id',$bda_id);

//             }
           
//   if(empty($bda_id)){ 

//    $this->db->where_in('ss_user_details.email_notification_status',array(12,13,14,15));
//   }      
       
//     $this->db->group_by('ss_users.id');


//     $query=$this->db->get();
//     $users = array();
//     $results = $query->result();

//     $members = array();
//     $tasks_for_members = array();
//     $logs_for_tasks_by_members = array();
//     foreach($results as $result){
   
   
//       $users[$result->id] = $result;
//       $user_ids = explode(",", $result->member_id);
     
//       $user_ids[] = $result->id;
//       $members[$result->id] = $user_ids;
  
  
//     }



//     /*Get their tasks*/
//     foreach($members as $owner_id => $member){
//       $this->db->select('GROUP_CONCAT(DISTINCT(ss_tasks.id)) as task_id');
//       $this->db->from('ss_tasks');
//       $this->db->where_in('ss_tasks.user_id', $member);
//      $query=$this->db->get();
//      $tasks = $query->result();
//      $tasks_for_members[$owner_id] = $tasks[0]->task_id;
//     }

//       /*Get their total work logs*/
//      foreach($tasks_for_members as $owner_id => $task_ids){
//      //$this->db->select('log_hrs');
//       $this->db->select('@hour_calculated  := round((SUM(log_min) / 60)+(SUM(ss_work_logs.log_hrs))) as final_logged_hours');
//       $this->db->select('@min_calculated := round(SUM(log_min) % 60) final_min_to_add');
//       $this->db->from('ss_work_logs');
//       $this->db->where_in('ss_work_logs.task_id',explode(",",$task_ids));
//       $query=$this->db->get();
//      $work_logs = $query->result();


  
//      $logs_for_tasks_by_members[$owner_id] = $work_logs;

//      if(isset($users[$owner_id]) || !empty($users[$owner_id]->member_id)){
//         $users[$owner_id]->final_logged_hours = $work_logs[0]->final_logged_hours;
//          $users[$owner_id]->final_min_to_add = $work_logs[0]->final_min_to_add;


//      }

//   }

//     // echo "<pre>";
//     // print_r($users);
//     // exit();

   
//      return $users;


//   }



  function get_teammembers_tasks(){
    $this->db->select("ss_users_members.*");
    $this->db->select("ss_users.*");
    $this->db->select('transaction_details.transaction_date', FALSE);
    $this->db->select("ss_user_details.notification_hour, ss_user_details.first_name, ss_user_details.last_name,ss_user_details.bda_id,ss_user_details.email_notification_status");
    $this->db->select('GROUP_CONCAT(ss_tasks.id) as task_id');
     $this->db->from("ss_users_members");
    $this->db->join("ss_users", "ss_users.id = ss_users_members.member_id");
    $this->db->join("ss_user_details", "ss_user_details.user_id = ss_users_members.member_id");
    $this->db->join('ss_tasks','ss_tasks.user_id = ss_users.id', 'left');
    // $this->db->join('ss_work_logs','ss_work_logs.task_id = ss_tasks.id', 'left')

    $this->db->join('ss_transaction_details as transaction_details', 'transaction_details.user_id = ss_users_members.user_id');


   $this->db->where_in('ss_user_details.email_notification_status',array(12,13,14,15)); 

    $this->db->where('ss_users.role_id',9);

    $this->db->or_where('ss_users.company',1);


    $this->db->where('ss_users.status_id',1);

    $this->db->group_by('ss_users.id');
    $query=$this->db->get();

    return $query->result();

  }



  function update_email_status($user_ids){


 if(!empty($user_ids)){
    $this->db->set('ss_user_details.email_notification_status',$user_ids['status_id_to_update']);
    $this->db->where_in('ss_user_details.user_id',$user_ids['user_id']);
   
    $this->db->update('ss_user_details');

   } 

  }

  function fetch_teambers_for_bda($user_id){

    $this->db->select("ss_users_members.member_id as id");

    $this->db->select('CONCAT(ss_user_details.first_name, ss_user_details.last_name) as name');
    $this->db->from('ss_users_members');
    $this->db->join('ss_user_details','ss_user_details.user_id = ss_users_members.member_id', 'left');
    $this->db->join('ss_users','ss_users.id = ss_users_members.member_id', 'left');
    // $this->db->join('ss_tasks as task','task.user_id = ss_users.id', 'left');
    $this->db->where('ss_users_members.user_id', $user_id);
    // $this->db->where_in('ss_tasks.user_id',$user_id);

    $query=$this->db->get();

    if(empty($query->result())){
           $this->db->select("ss_users_members.member_id as id");
    $this->db->select('CONCAT(ss_user_details.first_name, space(1), ss_user_details.last_name) as name');
    $this->db->from('ss_users_members');
    $this->db->join('ss_user_details','ss_user_details.user_id = ss_users_members.member_id', 'left');
    $this->db->join('ss_users','ss_users.id = ss_users_members.member_id', 'left');
    // $this->db->join('ss_tasks as task','task.user_id = ss_users.id', 'left');
    $this->db->where('ss_users_members.member_id', $user_id);
    $query=$this->db->get();
    return $query->result();
    }
    else{
      return $query->result();

    }

  }


  function fetch_bda_customers($bda_id){

     $this->db->select('user.id,user.username', FALSE);
   $this->db->select('user_details.first_name,user_details.last_name');
   $this->db->select('plan.name as plan_name', FALSE);
   $this->db->select('transaction_details.transaction_date', FALSE);
   $this->db->select('status.name as status_name', FALSE);
   // $this->db->select('COUNT(task.id) as no_of_reports');
   // $this->db->select('SUM(task.no_of_connections) as no_of_connections');
   $this->db->from('ss_users as user');
   $this->db->join('ss_user_details as user_details', 'user.id = user_details.user_id');
      $this->db->join('ss_transaction_details as transaction_details', 'transaction_details.user_id = user.id');
   $this->db->join('ss_plans as plan', 'plan.id = user_details.plan_id');

    // $this->db->join('ss_tasks as task', 'task.user_id = user.id');

   $this->db->join('ss_status as status', 'status.id = user.status_id');


   $this->db->where("user_details.bda_id", $bda_id);



    $this->db->where('user.status_id',1);
    $this->db->where('user.role_id',2);

    $this->db->group_by('user.id'); 

    return $this->db->get()->result();
  }


  function get_transaction_date(){

$this->db->select('ss_transaction_details.transaction_date,ss_transaction_details.user_id');
   $this->db->select('ss_users_members.member_id');
   $this->db->from('ss_users');
    $this->db->join('ss_user_details ', 'ss_user_details.user_id = ss_users.id');
   $this->db->join('ss_transaction_details', 'ss_transaction_details.user_id = ss_users.id');
   $this->db->join('ss_users_members', 'ss_users_members.user_id = ss_users.id');
   $this->db->where('ss_users.status_id',1);
   $this->db->where('ss_users.role_id',2);
   $this->db->group_by('ss_users.id'); 
   return $this->db->get()->result();

  }

  function fetch_customers_for_sending_initiation_mail(){

    $this->db->select("ss_users.*");
    $this->db->select("ss_user_details.first_name,ss_user_details.last_name,ss_user_details.temp_token");
    $this->db->from("ss_users");
    $this->db->join("ss_user_details", "ss_users.id = ss_user_details.user_id");
    $this->db->where("ss_users.initial_activation_email_status", 0);
    $this->db->where("ss_users.role_id", 2);
    $this->db->limit(20);
    return $this->db->get()->result();
  
  }

  public function update_initiation_email_status($users){

        $this->db->set('initial_activation_email_status',1);
        $this->db->where_in('id',$users);
        $this->db->update('ss_users');


        // $this->db->set('temp_token',NULL);
        // $this->db->where_in('user_id',$users);
        // $this->db->update('ss_user_details');

        return TRUE;

  }


/**
  *1.check the user_id is in member_id column or user_id column in ss_users_member table
  *2.if the query is not empty, that means the user_id is having members.  
  *return the the assistant details for the user_id
  *3.else , that means the customer with out team members
  *4.return the assistant details for the user_id
  *this function is used for including customer assistants email address as  cc on report submission.
*/
  function get_customer_assistant_details($user_id){

    // $this->db->select("ss_users_members.*");
    // $this->db->from("ss_users_members");
    // $this->db->where("ss_users_members.user_id",$user_id);
    // $this->db->or_where("ss_users_members.member_id",$user_id);
//     $results = $this->db->get()->result();
//      if(!empty($results)){

//       foreach ($results as $key => $result){
 
      $this->db->select("ss_customer_assistant_details.email");
      $this->db->select("ss_customer_assistant_details.email_status");
      $this->db->from('ss_customer_assistant_details');
      $this->db->where('ss_customer_assistant_details.user_id',$user_id);
       $query = $this->db->get()->result();
//     }
      return $query;
//       }

//     else{

//       $this->db->select("ss_customer_assistant_details.email");
//       $this->db->select("ss_customer_assistant_details.email_status");
//       $this->db->from('ss_customer_assistant_details');
// $this->db->where('ss_customer_assistant_details.user_id',$user_id);
//       $query = $this->db->get()->result();
//       return $query;
      //}
           
   }
   
   function get_assistant_details_by_id($ids){
   
   	$this->db->select("ss_customer_assistant_details.*");
   	$this->db->from('ss_customer_assistant_details');
   	$this->db->where_in('ss_customer_assistant_details.id',$ids);
   	$query = $this->db->get()->result();
   	
   	return $query;
   
   }

/**
*This function is for get the team owners.
*used for displaying team owner's name in team members drop down(team members dashboard)
*/

  function get_owner($user_id){

    $this->db->select("ss_user_details.user_id as member_id");
    $this->db->select("ss_user_details.first_name");
    $this->db->select("ss_user_details.last_name");
    $this->db->from('ss_user_details');

    $this->db->where('ss_user_details.user_id',$user_id);

   $query = $this->db->get()->result();

   return $query;

  }

  function update_resetCode($user_name, $tmp_password){
  	
  	if(!empty($user_name) && !empty($tmp_password)){
  		$this->db->set('ss_users.tmp_password',$tmp_password);
  		$this->db->where('ss_users.username',$user_name);
  		$this->db->where("ss_users.status_id", 1);
  		 
  		$this->db->update('ss_users');
  		
  		return true;
  	}else{
  		return false;
  	}
  }
  
  function getUserFirstName($username){
  	$this->db->select('ss_user_details.first_name,ss_user_details.user_id');
  	$this->db->join('ss_users', 'ss_users.id = ss_user_details.user_id');
  	$this->db->from('ss_user_details');
  	$this->db->where('ss_users.username', $username);
  	return $this->db->get()->result();
  }
  
  function validate_forgetPass($user_name)
  {
  
  	$limit = 1;
  	$this->db->select('*');
  	$this->db->from('ss_users');
  	$this->db->where('ss_users.username',$user_name);
  	$this->db->where('ss_users.status_id',1);
  	$query=$this->db->get();
  
  	if($query -> num_rows() == 1)
  	{
  
  		return $query->result();
  	}
  	else
  	{
  		return false;
  	}
  }
  
  function validate_reset_pass_link($tmp_password){
  	if(!empty($tmp_password)){
  			
  		$this->db->select('*');
  		$this->db->from('ss_users');
  		$this->db->where('ss_users.tmp_password',$tmp_password);
  		$this->db->where('ss_users.status_id',1);
  		$query=$this->db->get();
  			
  		if($query -> num_rows() == 1)
  		{
  				
  			return $query->result();
  		}
  		else
  		{
  			return false;
  		}
  	}
  
  }
  
  function update_password($user_id, $new_password){
  	
  	if(!empty($new_password) && !empty($user_id)){
  		
  			
  		$this->db->set('ss_users.password',md5($new_password));
  		$this->db->set('ss_users.tmp_password','');
  		$this->db->where('ss_users.id',$user_id);
  		$this->db->where("ss_users.status_id", 1);
  			
  		$this->db->update('ss_users');
  		
  		return true;
  	}
  	else
  	{
  		return false;
  	}
  }
  
  /**
   * To fetch the list of referrals
   */
  function fetch_refferals($order=null, $order_type, $limit_start, $limit_end,$search, $status_id=null){
  	$this->db->select('rd.*',FALSE);
  	$this->db->select('ud.first_name, ud.last_name, u.username');
  	
  	
  	//$this->db->select('c.id as assistant_id, c.name as assistant_name, c.email as assistant_email, c.phone_number as assistant_phone',FALSE);
  	$this->db->from('ss_referral_details as rd');
  	$this->db->join('ss_users as u', 'rd.referred_by = u.id');
  	$this->db->join('ss_user_details as ud', 'u.id = ud.user_id');
  	//$this->db->where('a.company !=', 0);
  
  	 
  	if($status_id !== NULL){
  		$this->db->where('rd.contacted_status',$status_id);
  	
  	}
  	
  	if($search !== NULL && !empty($search)){
  	
  	
  		if(!empty($search['search_customer'])){
  			$this->db->like('rd.referred_by',$search['search_customer']);
  		}
  		
  		if($search['contacted_status'] != ''){
  			$this->db->like('rd.contacted_status',$search['contacted_status']);
  		}
  	
  		if(!empty($search['search_from_date'])){
  	
  			$this->db->where('rd.created_date >=',date("Y-m-d H:i:s", strtotime($search['search_from_date'] . ' ' . '00:00:00')));
  		}
  	
  		if(!empty($search['search_to_date'])){
  	
  			$this->db->where('rd.created_date <=',date("Y-m-d H:i:s", strtotime($search['search_to_date'] . ' ' . '23:59:59')));
  		}
  	
  		
  	}
  	
  	
  	$this->db->order_by("rd.id", "desc");
  	
  	$this->db->group_by("rd.id");
  	if($limit_start && $limit_end){
  		$this->db->limit($limit_start, $limit_end);
  	}
  	if($limit_start != null){
  		$this->db->limit($limit_start, $limit_end);
  	}
  	$query=$this->db->get();
  	// $query = $this->db->get_where('ss_user_details', array('user_id' => $user_id));
  	
  	return $query->result();
  }
  
  /**
   * To delete the referral details(hard delete)
   * 
   */
  function delete_referral($referral_id){
  	
  	$this->db->where('id', $referral_id);
  	$this->db->delete('ss_referral_details');
  	
  	return true;
  	
  }
  
  /**
   * To delete the referral details(hard delete)
   *
   */
  function update_referral_status($referral_id, $status){
  	
  	$this->db->set('ss_referral_details.contacted_status', $status);
  	$this->db->where('id', $referral_id);
  	 
  	$this->db->update('ss_referral_details');
  	
  	return true;
  	 
  }

  function update_referral_notes($data){
    
    $this->db->set('ss_referral_details.notes', $data['current_notes']);
    $this->db->where('id', $data['referral_id']);
     
    $this->db->update('ss_referral_details');
    
    return true;
     
  }
  
  
  /* Zoho Code Starts */
  function get_data_updates(){
    $this->db->select('ss_user_details.*');
    $this->db->from('ss_user_details');
    $this->db->where('ss_user_details.flag','1');
    $result=$this->db->get();
    $query = $result->result();
    if(!empty($query)){
      foreach($query as $data){
        $zohoid = $data->zohoid;
        $insert_id = $data->user_id;
        if($zohoid == "")
        {
          $this->add_zoho_contact($insert_id);
        }
        else{
         $this->update_zoho_contact($insert_id);
        }
      }
    }
  }

  function add_zoho_contact($insert_id){

    $this->db->select('ss_users.*');
    $this->db->from('ss_users');
    $this->db->where('ss_users.id',$insert_id);
    $result=$this->db->get();

    $this->db->select('ss_user_details.*');
    $this->db->from('ss_user_details');
    $this->db->where('ss_user_details.user_id',$insert_id);
    $result1=$this->db->get();

    $this->db->select('ss_customer_assistant_details.*');
    $this->db->from('ss_customer_assistant_details');
    $this->db->where('ss_customer_assistant_details.user_id',$insert_id);
    $result2=$this->db->get();

    $query = $result->result();
    $query1 = $result1->result();
    $query2 = $result2->result();
    
    $asstname = "";
    $asstemail = "";
    $asstphone = "";

    if(!empty($query2))
    {
      $asstdata = $query2['0'];
      $asstname = $asstdata->name;
      $asstemail = $asstdata->email;
      $asstphone = $asstdata->phone_number;
    }

    $data = $query['0'];
    $data1 = $query1['0'];
    $roleid = $data->role_id;
    $planid = $data1->plan_id;
    $bdaid = $data1->bda_id;
    $bda_name = $this->get_ssbda_name($bdaid);
    $status_id = $data->status_id;
    $status = $this->get_ssstatus_name($status_id);
    $plan = $this->get_ssplan_name($planid);
    $created_date = strtotime($data->created);
    $created = date('m/d/Y',$created_date);
    echo $created;
    $board_date = $data1->on_board_date;
    $boarded_date = "";
    if($board_date != "")
    {
      $board_date = strtotime($data1->on_board_date);
      $boarded_date = date('m/d/Y',$board_date);
    }    
    if( $roleid == 2 || $roleid == 9 ){
      if( $roleid == 2 )
      {
        //team owner code here
        // echo "team owner or customer";
        $record = "<Contacts><row no='1'><FL val='First Name'>".$data1->first_name."</FL><FL val='Last Name'>".$data1->last_name."</FL><FL val='Email'>".$data->username."</FL><FL val='Phone'>".$data1->phone_number."</FL><FL val='Plan'>".$plan."</FL><FL val='Status'>".$status."</FL><FL val='Company Name'>".$data1->company_name."</FL><FL val='On Board Date'>".$boarded_date."</FL><FL val='On Board Time'>".$data1->on_board_time."</FL><FL val='Assigned BDA'>".$bda_name."</FL><FL val='Mailing Street'>".$data1->address."</FL><FL val='Mailing City'>".$data1->city."</FL><FL val='Mailing State'>".$data1->state."</FL><FL val='Mailing Zip'>".$data1->zip_code."</FL><FL val='Created Date'>".$created."</FL><FL val='Assistant'>".$asstname."</FL><FL val='Assistant Email'>".$asstemail."</FL><FL val='Asst Phone'>".$asstphone."</FL></row></Contacts>";

        $param = "authtoken=d034c28dc21dcfb01265a75ed1d1df2d&scope=crmapi&wfTrigger=true&xmlData=".$record;
        
        $link = "https://crm.zoho.com/crm/private/xml/Contacts/insertRecords";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        //curl_setopt($ch, CURLOPT_POSTFIELDS,$input_xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5 ); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $cdata = curl_exec($ch);
        $info = curl_getinfo($ch);
        //print_r( $info);
        curl_close($ch);
        // $array_data = @simplexml_load_string($cdata);
        $array_data = json_decode(json_encode(@simplexml_load_string($cdata)), true);
        // echo "<pre>";
        // print_r($array_data);
        $zoho_id = $array_data['result']['recorddetail']['FL']['0'];
        $updatezohoid = $this->insert_zohoid_user($zoho_id,$insert_id);
        if($planid == 3)
        {
          // echo "team owner";
          $name = $data1->first_name." ".$data1->last_name;
          $this->add_zoho_account($name, $insert_id);
        }
        $this->zoho_unset_flag($insert_id);
      }
      elseif( $roleid == 9 )
      {
        //team member code here
        // echo "team member";
        echo $insert_id;
        $this->db->select('ss_users_members.*');
        $this->db->from('ss_users_members');
        $this->db->where('ss_users_members.member_id',$insert_id);
        $role_query=$this->db->get();
        $role_obj = $role_query->result();
        $teamowner_id = $role_obj[0]->user_id;
        // $teamowner_zohoid = $this->get_teamowner_data($teamowner_id);
        //echo $teamowner_zohoid;
        $teamowner_name = $this->get_ssbda_name($teamowner_id);

        $record = "<Contacts><row no='1'><FL val='First Name'>".$data1->first_name."</FL><FL val='Last Name'>".$data1->last_name."</FL><FL val='Email'>".$data->username."</FL><FL val='Phone'>".$data1->phone_number."</FL><FL val='Plan'>".$plan."</FL><FL val='Status'>".$status."</FL><FL val='Company Name'>".$data1->company_name."</FL><FL val='On Board Date'>".$boarded_date."</FL><FL val='On Board Time'>".$data1->on_board_time."</FL><FL val='Assigned BDA'>".$bda_name."</FL><FL val='Mailing Street'>".$data1->address."</FL><FL val='Mailing City'>".$data1->city."</FL><FL val='Mailing State'>".$data1->state."</FL><FL val='Mailing Zip'>".$data1->zip_code."</FL><FL val='Created Date'>".$created."</FL><FL val='Assistant'>".$asstname."</FL><FL val='Assistant Email'>".$asstemail."</FL><FL val='Asst Phone'>".$asstphone."</FL><FL val='Account Name'>".$teamowner_name."</FL></row></Contacts>";

        $param = "authtoken=d034c28dc21dcfb01265a75ed1d1df2d&scope=crmapi&wfTrigger=true&xmlData=".$record;
        
        $link = "https://crm.zoho.com/crm/private/xml/Contacts/insertRecords";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        //curl_setopt($ch, CURLOPT_POSTFIELDS,$input_xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5 ); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $cdata = curl_exec($ch);
        $info = curl_getinfo($ch);
        //print_r( $info);
        curl_close($ch);
        // $array_data = @simplexml_load_string($cdata);
        $array_data = json_decode(json_encode(@simplexml_load_string($cdata)), true);
        // echo "<pre>";
        // print_r($array_data);
        $zoho_id = $array_data['result']['recorddetail']['FL']['0'];
        $updatezohoid = $this->insert_zohoid_user($zoho_id,$insert_id);
        $this->zoho_unset_flag($insert_id);
        
      }
    }
    else{
      $this->zoho_unset_flag($insert_id);
    }
  }
  function update_zoho_contact($insert_id){

    $this->db->select('ss_users.*');
    $this->db->from('ss_users');
    $this->db->where('ss_users.id',$insert_id);
    $result=$this->db->get();

    $this->db->select('ss_user_details.*');
    $this->db->from('ss_user_details');
    $this->db->where('ss_user_details.user_id',$insert_id);
    $result1=$this->db->get();

    $this->db->select('ss_customer_assistant_details.*');
    $this->db->from('ss_customer_assistant_details');
    $this->db->where('ss_customer_assistant_details.user_id',$insert_id);
    $result2=$this->db->get();

    $query = $result->result();
    $query1 = $result1->result();
    $query2 = $result2->result();
    
    $asstname = "";
    $asstemail = "";
    $asstphone = "";
    if(!empty($query2))
    {
      $asstdata = $query2['0'];
      $asstname = $asstdata->name;
      $asstemail = $asstdata->email;
      $asstphone = $asstdata->phone_number;
    }
   
    $data = $query['0'];
    $data1 = $query1['0'];
    $roleid = $data->role_id;
    $planid = $data1->plan_id;
    $bdaid = $data1->bda_id;
    $bda_name = $this->get_ssbda_name($bdaid);
    $status_id = $data->status_id;
    $status = $this->get_ssstatus_name($status_id);
    $plan = $this->get_ssplan_name($planid);
    $board_date = $data1->on_board_date;
    $boarded_date = "";
    if($board_date != "")
    {
      $board_date = strtotime($data1->on_board_date);
      $boarded_date = date('m/d/Y',$board_date);
    }

    
    if( $roleid == 2 || $roleid == 9 ){
      if( $roleid == 2 )
      {
        //team owner code here
        $record = "<Contacts><row no='1'><FL val='First Name'>".$data1->first_name."</FL><FL val='Last Name'>".$data1->last_name."</FL><FL val='Email'>".$data->username."</FL><FL val='Phone'>".$data1->phone_number."</FL><FL val='Plan'>".$plan."</FL><FL val='Status'>".$status."</FL><FL val='Company Name'>".$data1->company_name."</FL><FL val='On Board Date'>".$boarded_date."</FL><FL val='On Board Time'>".$data1->on_board_time."</FL><FL val='Assigned BDA'>".$bda_name."</FL><FL val='Mailing Street'>".$data1->address."</FL><FL val='Mailing City'>".$data1->city."</FL><FL val='Mailing State'>".$data1->state."</FL><FL val='Mailing Zip'>".$data1->zip_code."</FL><FL val='Assistant'>".$asstname."</FL><FL val='Assistant Email'>".$asstemail."</FL><FL val='Asst Phone'>".$asstphone."</FL></row></Contacts>";

        $param = "authtoken=d034c28dc21dcfb01265a75ed1d1df2d&id=".$data1->zohoid."&scope=crmapi&wfTrigger=true&xmlData=".$record;
        
        $link = "https://crm.zoho.com/crm/private/xml/Contacts/updateRecords";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        //curl_setopt($ch, CURLOPT_POSTFIELDS,$input_xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5 ); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $cdata = curl_exec($ch);
        $info = curl_getinfo($ch);
        //print_r( $info);
        curl_close($ch);
        // $array_data = @simplexml_load_string($cdata);
        if($planid == 3)
        {
          echo "team owner";
          $name = $data1->first_name." ".$data1->last_name;
          $t_zohoid = $data1->team_owner_zohoid;
          $this->update_zoho_account($name, $t_zohoid);
        }
        $this->zoho_unset_flag($insert_id);
      }
      elseif( $roleid == 9 )
      {
        //team member code here
        $this->db->select('ss_users_members.*');
        $this->db->from('ss_users_members');
        $this->db->where('ss_users_members.member_id',$insert_id);
        $role_query=$this->db->get();
        $role_obj = $role_query->result();
        $teamowner_id = $role_obj[0]->user_id;
        // $teamowner_zohoid = $this->get_teamowner_data($teamowner_id);
        //echo $teamowner_zohoid;
        $teamowner_name = $this->get_ssbda_name($teamowner_id);

        $record = "<Contacts><row no='1'><FL val='First Name'>".$data1->first_name."</FL><FL val='Last Name'>".$data1->last_name."</FL><FL val='Email'>".$data->username."</FL><FL val='Phone'>".$data1->phone_number."</FL><FL val='Plan'>".$plan."</FL><FL val='Status'>".$status."</FL><FL val='Company Name'>".$data1->company_name."</FL><FL val='On Board Date'>".$boarded_date."</FL><FL val='On Board Time'>".$data1->on_board_time."</FL><FL val='Assigned BDA'>".$bda_name."</FL><FL val='Mailing Street'>".$data1->address."</FL><FL val='Mailing City'>".$data1->city."</FL><FL val='Mailing State'>".$data1->state."</FL><FL val='Mailing Zip'>".$data1->zip_code."</FL><FL val='Assistant'>".$asstname."</FL><FL val='Assistant Email'>".$asstemail."</FL><FL val='Asst Phone'>".$asstphone."</FL><FL val='Account Name'>".$teamowner_name."</FL></row></Contacts>";

        $param = "authtoken=d034c28dc21dcfb01265a75ed1d1df2d&id=".$data1->zohoid."&scope=crmapi&wfTrigger=true&xmlData=".$record;
        
        $link = "https://crm.zoho.com/crm/private/xml/Contacts/updateRecords";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        //curl_setopt($ch, CURLOPT_POSTFIELDS,$input_xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5 ); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $cdata = curl_exec($ch);
        $info = curl_getinfo($ch);
        //print_r( $info);
        curl_close($ch);
        // $array_data = @simplexml_load_string($cdata);
        $this->zoho_unset_flag($insert_id);
        
      }
    }
    else{
      $this->zoho_unset_flag($insert_id);
    }
  }

  function add_zoho_account($name, $insert_id){
    $record = "<Accounts><row no='1'><FL val='Account Name'>".$name."</FL></row></Accounts>";

    $param = "authtoken=d034c28dc21dcfb01265a75ed1d1df2d&scope=crmapi&wfTrigger=true&xmlData=".$record;
    
    $link = "https://crm.zoho.com/crm/private/xml/Accounts/insertRecords";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $link);
    //curl_setopt($ch, CURLOPT_POSTFIELDS,$input_xml);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5 ); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
    $cdata = curl_exec($ch);
    $info = curl_getinfo($ch);
    //print_r( $info);
    curl_close($ch);
    // $array_data = @simplexml_load_string($cdata);
    $array_data = json_decode(json_encode(@simplexml_load_string($cdata)), true);
    // echo "<pre>";
    // print_r($array_data);
    $zoho_id = $array_data['result']['recorddetail']['FL']['0'];
    $updatezohoid = $this->insert_zohoid_member($zoho_id,$insert_id);
  }

  function update_zoho_account($name, $t_zohoid){
    $record = "<Accounts><row no='1'><FL val='Account Name'>".$name."</FL></row></Accounts>";

    $param = "authtoken=d034c28dc21dcfb01265a75ed1d1df2d&id=".$t_zohoid."&scope=crmapi&wfTrigger=true&xmlData=".$record;
    
    $link = "https://crm.zoho.com/crm/private/xml/Accounts/updateRecords";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $link);
    //curl_setopt($ch, CURLOPT_POSTFIELDS,$input_xml);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5 ); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
    $cdata = curl_exec($ch);
    $info = curl_getinfo($ch);
    //print_r( $info);
    curl_close($ch);
    // $array_data = @simplexml_load_string($cdata);
  }

  function insert_zohoid_user($zoho_id,$insert_id){
    $updateData=array("zohoid"=>$zoho_id);
    $this->db->where("user_id",$insert_id);
    if($this->db->update("ss_user_details",$updateData))
    {
      return true;
    }
    else{
      return false;
    }
  }

  function insert_zohoid_member($zoho_id,$insert_id){
    $updateData=array("team_owner_zohoid"=>$zoho_id);
    $this->db->where("user_id",$insert_id);
    if($this->db->update("ss_user_details",$updateData))
    {
      return true;
    }
    else{
      return false;
    }
  }

  function get_ssbda_name($bdaid){
    $this->db->select('first_name,last_name');
    $this->db->from('ss_user_details');

    $this->db->where('user_id', $bdaid);
    $this->db->limit(1);
    $role_query = $this->db->get();
    if($role_query->num_rows() == 1)
    {
      $role_obj = $role_query->result();
      $name = $role_obj[0]->first_name." ".$role_obj[0]->last_name;
      return $name;
    } 
  }

  function get_teamowner_data($id){
    $this->db->select('team_owner_zohoid');
    $this->db->from('ss_user_details');

    $this->db->where('user_id', $id);
    $this->db->limit(1);
    $role_query = $this->db->get();
    if($role_query->num_rows() == 1)
    {
      $role_obj = $role_query->result();
      $zohoid = $role_obj[0]->team_owner_zohoid;
      return $zohoid;
    } 
  }

  function get_ssstatus_name($status_id){
    $this->db->select('name');
    $this->db->from('ss_status');
    $this->db->where('id', $status_id);
    $this->db->limit(1);
    $role_query = $this->db->get();
    if($role_query->num_rows() == 1)
    {
      $role_obj = $role_query->result();
      $name = $role_obj[0]->name;
      return $name;
    } 
  }

  function get_ssplan_name($plan_id){
    $this->db->select('name');
    $this->db->from('ss_plans');
    $this->db->where('id', $plan_id);
    $this->db->limit(1);
    $role_query = $this->db->get();
    if($role_query->num_rows() == 1)
    {
      $role_obj = $role_query->result();
      $name = $role_obj[0]->name;
      return $name;
    } 
  }

  function zoho_unset_flag($insert_id){
    $updateData=array("flag"=>'2');
    $this->db->where("user_id",$insert_id);
    if($this->db->update("ss_user_details",$updateData))
    {
      return true;
    }
    else{
      return false;
    }
  }



  function insert_email_details($data){

    $this->db->insert('ss_customers_email_details',$data);
    
  }

  function update_email_details($user_id,$data){

    $this->db->set('email_date', $data['email_date']);
    $this->db->set('email_send_count', $data['email_send_count']);
    $this->db->where('customer_id', $user_id);
    $this->db->update('ss_customers_email_details');

  }

  function fetch_email_details($user_id){

    $this->db->select('*');
    $this->db->from('ss_customers_email_details');
    $this->db->where('customer_id',$user_id);
    $query =$this->db->get()->result();

    return $query;

  }

  function search_userid($user_id){

    $this->db->select('*');
    $this->db->from('ss_customers_email_details');
    $this->db->where('ss_customers_email_details.customer_id',$user_id);
    $query = $this->db->get();
     if($query -> num_rows() > 0)
     {
       return true;
     }
     else
     {
       return false;
     }  
  }


}
