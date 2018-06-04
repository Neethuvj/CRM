<?php


/**
 * Functions for Plan Model
 * @package Model
 * 
 */
class Plan_model extends CI_Model{



function fetch_all_plans()
  {

  	$this->db->select('plan.*',FALSE);
  	$this->db->from('ss_plans as plan');
    $this->db->where('plan.id !=',3);
    $query=$this->db->get();
    
    return $query->result();
  }


  function fetch_plan_by_id($plan_id)
  {

  	$this->db->select('plan.*',FALSE);
  	$this->db->from('ss_plans as plan');
  	$this->db->where('plan.id', $plan_id);
    $query=$this->db->get();
    
    return $query->result();
  }


  /**
  * Based on the Plan id and User id Fetch the plan details from either ss_plans or ss_user_plans table
  */
  function get_plan_name_and_amount_hour($plan_id, $user_id){

    if((int) $plan_id !== 3){
    $this->db->select('plan.*',FALSE);
    $this->db->from('ss_plans as plan');
    $this->db->where('plan.id', $plan_id);
    $query=$this->db->get();
    
    return $query->result();

    }
    else{

    $this->db->select('ss_users_plans.total_plan_amount as plan_amount,ss_users_plans.plan_hours as plan_hours');
    $this->db->select('ss_plans.name');
    $this->db->from('ss_users_plans');
    $this->db->join("ss_plans", 'ss_users_plans.plan_id = ss_plans.id');
    $this->db->where('plan_id', $plan_id);
     $this->db->where('user_id', $user_id);
    $this->db->limit(1);
     $query=$this->db->get();
    
    return $query->result();

    }
  }


  /**
   * This function is run while plan is updated from the admin interface.
    Logic
   *
   *   1. Get the Subscription Date from transaction table and calculate next subscription date.
   *   2. Delete the entries of this user in ss_users_with_updated_plans table to avoid duplication.
   *   3. Insert in ss_users_with_updated_plans with Updated Plan Details and subscription date
   *   4. Update the plan ids in user details table
   *   5. Delete from ss_users_plans for the user_id to avoid confusion
   *   6. If the upgraded plan is Team, then insert it in to ss_users_plans table with plan amount and plan hours
   *   7. Calculate team members of the user(if any), update their plan also to the parent user
   */
  function update_plan_for_single_user($user_id, $old_plan_id, $new_plan_id,$plan_amount,$plan_hours){

     $this->db->select('ss_transaction_details.transaction_date',FALSE);
        $this->db->from('ss_transaction_details');
      $this->db->where('ss_transaction_details.user_id', (int) $user_id);
    $transaction_date = $this->db->get()->result();

    $next_transaction_date = $this->calculate_next_subscription_date($transaction_date[0]->transaction_date);
    /*Delete From ss_users_with_updated_plans table for the user id*/
      $this->db->where_in('user_id', $user_id);
      $this->db->delete('ss_users_with_updated_plans');
    /*Insert into ss_users_with_updated_plans table with new data*/
      $updated_plan_data=array(
            
            'user_id'=> $user_id,
            'plan_id'=>$new_plan_id,
            'subscription_start_date' => $next_transaction_date,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s')
        );
    $this->db->insert('ss_users_with_updated_plans',$updated_plan_data);

    

      /*Update User Details Table with the new plan*/
       $this->db->set('plan_id', $new_plan_id);
       $this->db->where('user_id',$user_id);
      $query= $this->db->update('ss_user_details');

      /*Update in to users plan table Before that deleting the old plan details so on downgrade it will not cause any issues*/
      $this->db->where_in('user_id', $user_id);
      $this->db->delete('ss_users_plans');
      if($new_plan_id == 3){
        $user_plan = array();
        $user_plan['user_id'] = $user_id;
        $user_plan['plan_id'] = 3;
        $user_plan['plan_amount_per_hour'] = $plan_amount;
        $user_plan['plan_hours'] = $plan_hours;
        $user_plan['created'] = date("Y-m-d H:i:s");
        $user_plan['updated'] = date('Y-m-d H:i:s');
        $user_plan['total_plan_amount '] = $plan_hours * $plan_amount;
        $user_plan_details= $this->db->insert('ss_users_plans',$user_plan);
      }
    /*Update Team Member plan as well*/
    $this->db->select('ss_users_members.member_id');
       $this->db->from('ss_users_members');
       $this->db->where_in('ss_users_members.user_id', $user_id);
       $member_ids = $this->db->get()->result_array();
       $members = array();
       if(!empty($member_ids)){
        foreach($member_ids as $member_id){
          $members[] = $member_id['member_id'];
        }

       $this->db->set('plan_id', $new_plan_id);
       $this->db->where_in('user_id',$members);
       $this->db->update('ss_user_details');
       }
     
     return $query;

  }

  /**
   *This function will run while admin changes the plan amount from admin/configuration/  plan page
   *
   * Logic
   *
   *   1. Update the New Plan details in ss_plans table
   *   2. If plan amount is changed, get all user id and their transaction date who are in that plan
   *   3. calculate their next billing date
   *   4. Insert it in to ss_users_with_updated_plans so the cron will run and update the plan details from next billing
   */
  function update_plan($values, $plan_amount_change_status){
	$this->db->set('name', $values['plan_name']);
	$this->db->set('plan_amount', $values['plan_amount']);
	$this->db->set('plan_hours', $values['plan_hour']);
	$this->db->set('updated', date("Y-m-d H:i:s"));
	$this->db->where_in('id', $values['plan_id']);
	$query= $this->db->update('ss_plans');

  if($plan_amount_change_status == TRUE){

    /*Fetch All Users with the given Plan*/
    $this->db->select('user_details.user_id',FALSE);
    $this->db->select('ss_transaction_details.transaction_date', FALSE);
    $this->db->from('ss_user_details
 as user_details');
    $this->db->join('ss_transaction_details', 'user_details.user_id = ss_transaction_details.user_id');
    $this->db->where('user_details.plan_id', (int) $values['plan_id']);
    $user_ids=$this->db->get()->result();

    $data = array();
    /*Generate an array of details to be inserted in to ss_users_with_updated_plans table*/
    if(!empty($user_ids)){
      foreach($user_ids as $user){

      
        $next_subscription_start_date = $this->calculate_next_subscription_date($user->transaction_date);
        
        $data[] = array(
          'user_id' => $user->user_id,
          'subscription_start_date' => $next_subscription_start_date,
          'plan_id' => $values['plan_id'], 
          'created' =>  date("Y-m-d H:i:s"), 
          'updated' =>  date("Y-m-d H:i:s")
        );
      }
    }
  
    /*Before Insert We need to check if the table is already having entries for the same plan id then we need to delete them first*/
    if(!empty($data)){
      $this->db->where('plan_id', $values['plan_id']);
      $this->db->delete('ss_users_with_updated_plans'); 
      /*Now Insert the new array*/
      $this->db->insert_batch('ss_users_with_updated_plans
', $data); 
    }
  }
	return $query;
  	// exit();
  }



       /**
        * Calculate Next Subscription Start date
        *
        * Get the subscription Started Date
        *
        * Get the Current Date
        *
        * Check if the Current Month Date is less then or greater then the Subscription Start Month Date.
        *
        * If it is past add +1 to the month count
        *
        * Else take that same date +1 from current Month and Year Set it as subscription start date
        *
        *  E.g  1
        *
        *   Subscription Started date = 12/12/12
        *
        *    Current Month Date is = 14/01/17
        *
        *    So The next Subscription Start Date will be = 12/02/17
        *
        *  E.g 2
        *
        *    Subscription Started date = 12/12/12
        *
        *    Current Month Date is = 11/01/17
        *
        *    So The next Subscription Start Date will be = 12/01/17
        *
        *
        */


  function calculate_next_subscription_date($date){
      $subscription_started_date = date("d",strtotime($date));
      $current_date = date("d");
      if($current_date > $subscription_started_date){
        $subscription_start_month = date("m");
        $subscription_start_date = $subscription_started_date;
        $subscription_start_year = date("Y");
        $subscription_start_time = date("H:i:s", strtotime($date));
        $subscription_date_generated = $subscription_start_year . "-" . $subscription_start_month . "-" . $subscription_start_date . " " .$subscription_start_time;
        //$next_month = date("m", strtotime("+1 month"));
        $next_subscription_start_date = date("Y-m-d H:i:s", strtotime("+1 month",strtotime($subscription_date_generated)));
      }
      else{
        $subscription_start_month = date("m");
        $subscription_start_date = $subscription_started_date;
        $subscription_start_year = date("Y");
        $subscription_start_time = date("H:i:s", strtotime($date));
        $subscription_date_generated = $subscription_start_year . "-" . $subscription_start_month . "-" . $subscription_start_date . " " .$subscription_start_time;
        $next_subscription_start_date = date("Y-m-d H:i:s", strtotime($subscription_date_generated)); 
      }


      return $next_subscription_start_date;

  }

}