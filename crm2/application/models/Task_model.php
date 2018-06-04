<?php

//ini_set("memory_limit","512M");
/**
 * Functions for Task Model
 * @package Model
 * 
 */
class Task_model extends CI_Model {


   public function __construct(){
      parent::__construct();


          $this->load->model('Users_model');


      }
  
/**
* Get task type from table 
* used for list the task details in many places
* @return id,name,status id,description,creted date
*/
function fetchtask()
  {
    $query = $this->db->get_where('ss_task_type', array('status_id' => 1));
    return $query->result();
  }



  /**
   * Get task type name for a patricular task id
   * @param int $type_id
   * @return task_name
   */
  function fetch_task_type($type_id){

  $this->db->select('ss_task_type.name as task_name');
  $this->db->from('ss_task_type');
  $this->db->where('ss_task_type.id',$type_id);
  $query= $this->db->get();

  return $query->result();

  }


 /**
  *updating the task details
  *@param array $data
  */
 function taskupdate($data)
 {


  $query = $this->db->insert('ss_tasks',$data);
  return $this->db->insert_id();
  
 }

/**

 *deleting tasks from admin panel
 *while deleting a task , work logs for that task from ss_work_log, and task links from ss_link table also deleting
 * @param array $task_id
 */

 function admin_delete_tasks($task_id){


    $this->db->where_in("id", $task_id);
          $this->db->delete('ss_tasks');

          $this->db->where_in('task_id', $task_id);
          $this->db->delete('ss_links');

          $this->db->where_in('task_id', $task_id);
          $this->db->delete('ss_work_logs');

          return true;
 }


/**
 *   this is for deleting the attachments and links while deleting a task
 *   @param array $files_links_to_remove
 */
 function delete_files_links($files_links_to_remove){


  if(!empty($files_links_to_remove['files_to_delete'])){
    $this->db->select('ss_uploads.path');
    $this->db->where_in('id', $files_links_to_remove['files_to_delete']);
    $this->db->from('ss_uploads');

       $query = $this->db->get();
       $file_names = array();
       foreach ($query->result() as $row){
          unlink(FCPATH."assets/files/".$row->path);
         
       }

 $this->db->where_in('id', $files_links_to_remove['files_to_delete']);
       $this->db->delete('ss_uploads');
     }
     if(!empty($files_links_to_remove['urls_to_delete'])){
$this->db->where_in('id', $files_links_to_remove['urls_to_delete']);
     $this->db->delete('ss_links');  
     }
              

 }


  /**
  *
  *feching task ids for a patricular user
   *@param  user_id
   *@return task_ids 
 */
 function get_task_ids_by_user_id($user_id){

  $this->db->select('task.id', FALSE);
  $this->db->from('ss_tasks as task');
  $this->db->where('task.user_id', $user_id);
  $query= $this->db->get();

    return $query->result();
 }



 /**
  * If Analyst Completes the Task then change the actual Status id also to completed
  */
 function update_analyst_status($task_id, $status_id){

  if($status_id == 8){
$this->db->set('status_id', 5);    
  }
  else{
   $this->db->set('status_id', 3);  
  }
$this->db->set('analyst_status_id', $status_id);
$this->db->set('updated', date("Y-m-d H:i:s"));
$this->db->where_in('id', $task_id);
$query= $this->db->update('ss_tasks');
return $query;
 }


  /**
 *
 * This function will run while task listing in admin panel,BDA,customer,team member
 *
  *@param int order
  *@param int order_type
  *@param int limit_start
  *@param int limit_end
  *@param int $user_ids array
  *@param int conditions array [filtering]
  *@param string admin
  *@param string bda_task
  *@return query
 *
 */
 function get_task($order=null, $order_type ='Asc', $limit_start=NULL, $limit_end=NULL,$user_ids,$status_id=NULL,$conditions = NULL,$admin = NULL, $bda_task = NULL,$parent_task = NULL)
 {


$this->load->dbutil();
$this->load->helper('file');
$this->load->helper('download');


  $this->db->select('task.*',FALSE);

    $this->db->select('task_type.id as task_id, task_type.name as task_name',FALSE);
    $this->db->select("task_status.name as task_status_name", FALSE);


    $this->db->select('analyst_first_name.first_name,analyst_first_name.last_name ');


    $this->db->select('user_first_name.first_name as users_first_name,user_first_name.last_name as users_last_name ');
    

    $this->db->select('analyst_task_status.name as analyst_status_name');


    $this->db->from('ss_tasks as task');

     $this->db->join('ss_status as task_status', 'task.status_id = task_status.id', 'left');


      $this->db->join('ss_status as analyst_task_status', 'task.  analyst_status_id = analyst_task_status.id', 'left');


    

      $this->db->join('ss_user_details as analyst_first_name', 'task.analysist_id = analyst_first_name.user_id', 'left');

      $this->db->join("ss_user_details as user_first_name","task.user_id = user_first_name.user_id");
    $this->db->join('ss_task_type as task_type', 'task.task_type_id = task_type.id', 'left');

     $role_id = $this->session->userdata('role_id');

     if($role_id != 2 && $role_id != 9){

      if($status_id == 3){

        $this->db->order_by('task.meeting_date_time', 'ASC'); 
      }
     } 

    if($conditions){

      if(!empty($conditions['search_from_date'])){
  
        $this->db->where('task.created >=',date("Y-m-d H:i:s",strtotime($conditions['search_from_date'] .' '. '00:00:00')));
      }
         if(!empty($conditions['search_to_date'])){

        $this->db->where('task.created <=',date("Y-m-d H:i:s",strtotime($conditions['search_to_date']  .' '. '23:59:59')));
      }


    if(isset($conditions['meeting_start_date'])&& !empty($conditions['meeting_start_date'])){

    $this->db->where('task.meeting_date_time >=',date("Y-m-d H:i:s",strtotime($conditions['meeting_start_date'] . ' ' . '00:00:00')));
   }
   if(isset($conditions['meeting_to_date'])&& !empty($conditions['meeting_to_date'])){
    $this->db->where('task.meeting_date_time <=',date("Y-m-d H:i:s",strtotime($conditions['meeting_to_date'] . ' ' . '23:59:59')));
   }


 if(isset($conditions['search_task_type']) && $conditions['search_task_type'] !== "All" && $conditions['search_task_type'] !== NULL && !empty($conditions['search_task_type'])){



    $this->db->where('task.task_type_id',$conditions['search_task_type']);
    }

     if(isset($conditions['search_customer']) && $conditions['search_customer'] !== "All" && $conditions['search_customer']!== NULL && !empty($conditions['search_customer'])){
    
           $this->db->where('task.user_id',$conditions['search_customer']);

    }



      if(!empty($conditions['created_start_date'])){
  
        $this->db->where('task.created >=',date("Y-m-d H:i:s",strtotime($conditions['created_start_date'] .' '. '00:00:00')));
      }
         if(!empty($conditions['created_to_date'])){

        $this->db->where('task.created <=',date("Y-m-d H:i:s",strtotime($conditions['created_to_date']  .' '. '23:59:59')));
      }


        /*if( isset($conditions['search_meeting_date_time']) && $conditions['search_meeting_date_time'] !== NULL && !empty($conditions['search_meeting_date_time'])){

      
           $this->db->where('task.meeting_date_time >=',date("Y-m-d H:i:s",strtotime($conditions['search_meeting_date_time'].' '. '00:00:00')));

            $this->db->where('task.meeting_date_time <=',date("Y-m-d H:i:s",strtotime($conditions['search_meeting_date_time'].' '. '23:59:59')));
 
    }*/



    if(isset($conditions['search_team_member']) && $conditions['search_team_member'] !== "All" && $conditions['search_team_member']!== NULL && !empty($conditions['search_team_member'])){
    
           $this->db->where('task.user_id',$conditions['search_team_member']);

    }


       if(isset($conditions['search_bda']) && $conditions['search_bda'] !== "All" && $conditions['search_bda'] !== NULL && !empty($conditions['search_bda'])){
      $this->db->where('task.bda_id',$conditions['search_bda']);
    }


    if(isset($conditions['search_analyst']) && $conditions['search_analyst'] !== "All" && $conditions['search_analyst'] !== NULL && !empty($conditions['search_analyst'])){
      $this->db->where('task.analysist_id',$conditions['search_analyst']);
    }



      if(isset($conditions['search_target']) && $conditions['search_target'] !== NULL && !empty($conditions['search_target'])){
    
      $this->db->like('task.target_name',$conditions['search_target']
        );
    }

     if(isset($conditions['search_status']) && $conditions['search_status'] !== NULL && !empty($conditions['search_status'])){
    
      $this->db->where('task.status_id',$conditions['search_status']
        );
    }

    }




  if($bda_task !== NULL){


     $users = array();

  if(!empty($user_ids)){
        foreach($user_ids as $key=>$user_id){
          $users[] = $user_id->member_id;

    }

    
}

    $this->db->where_in('task.bda_id',$users);

  }

 
  elseif($admin == NULL){


  $users = array();

  if(!empty($user_ids)){
        foreach($user_ids as $key=>$user_id){
          $users[] = $user_id->member_id;

    }

    
}
  
    $this->db->where_in('task.user_id',$users);

     
  }

  if($parent_task!==NULL){

       $this->db->where_in('task.user_id',$user_ids);

  }

  
 //$this->db->where_in('task.user_id',$user_ids);
    
    if($status_id !== NULL && $status_id !== "All"){
    
    $this->db->where('task.status_id',$status_id);
    }
  


   $this->db->group_by("task.id");
      $this->db->order_by("task.id", "desc");
    if($limit_start && $limit_end){
          $this->db->limit($limit_start, $limit_end); 
        }
         if($limit_start != null){
          $this->db->limit($limit_start, $limit_end);    
        }
    $query=$this->db->get();

    
if(isset($conditions['csv_export'])){

  // echo $conditions['csv_export'];
  // exit();
$delimiter = ",";
$newline = "\r\n";

  $all_tasks = $query->result();
  $all_task_ids = array_map(function ($ar) {return $ar->id;}, $all_tasks);
  $data['calculated_hours'] = $this->calculte_hours_spent($all_task_ids);



 // echo "<pre>";
 // print_r($data['total_hours']);
 // exit();
 





 //if($admin == NULL){
  $datas = $query->result();
  $data = array();



 


  foreach($datas as $csv){
   $csv = (array) $csv;

      $all_tasks = $query->result();
      $all_task_ids = array_map(function ($ar) {return $ar->id;}, $all_tasks);
      $data_array['calculated_hours'] = $this->calculte_hours_spent($all_task_ids);

    
      $logged_hrs = $data_array['calculated_hours'][$csv['id']]['logged_hrs'];

      $logged_min = $data_array['calculated_hours'][$csv['id']]['logged_min'];

$csv['time_spent'] = $logged_hrs." hour(s)"." ".$logged_min." minute(s)";


      // echo "<pre>";
      // print_r($csv['time_spent']);
      // exit();
    
  
    unset($csv['user_id']);
    unset($csv['bda_id']);
    unset($csv['analysist_id']);
    unset($csv['status_id']);
    unset($csv['analyst_status_id']);
    //unset($csv['no_of_connections']);
    unset($csv['analyst_note']);
    unset($csv['old_user_id']);
    unset($csv['old_task_id']);
    unset($csv['task_id']);
    //unset($csv['first_name']);
    //unset($csv['last_name']);
    unset($csv['analyst_status_name']);
    //fputcsv($file,explode(',',$csv));
    $data[] = $csv;
    
  }
  // fclose($file);
//     echo "<pre>";
//  print_r($data);
// exit();


  $this->download_csv($data, "CSV_Report.csv",",");
  // // array_push($data, implode(',', $csv_to_return[0]));
  // // $csv_string = implode(PHP_EOL, $data);
  //   force_download($file);
  // // print "<pre>";
  // // print_r($datas);
  // // exit();
   exit();
 //}
 //else{

 //   $data = $this->dbutil->csv_from_result($query, $delimiter, $newline);
 //  force_download('CSV_Report.csv', $data);
 // return $data;
   //}
 
}
else{

     return $query->result();

 }
}

private function calculte_hours_spent($task_ids_array){

  foreach($task_ids_array as $task_id){

   


$data['work_log'][$task_id] = $this->Task_model->workLogs($task_id);

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



 return $data['calculated_hours'];

 }





}

private function download_csv($data, $file_name, $delimiter){

  $temp_memory = fopen('php://memory', 'w');
    /** loop through array */
 
    $headers = array("Id", "Account Name", "Target Name", "Present Company", "Previous Company", "Email", "Home Address", "Date and Time of Meeting", "Comments", "Task Type","Email To Notify","Created", "Updated", "Number Of Connections",
      "Task Name", "Task Status","Analyst First Name","Analyst Last Name", "User First Name","User Last Name","Hours Spent");
    fputcsv($temp_memory,$headers,$delimiter);
    // print "<pre>";
    // print_r($data);
    // exit();
    foreach ($data as $line) {
        /** default php csv handler **/
        fputcsv($temp_memory, $line, $delimiter);
    }
    /** rewrind the "file" with the csv lines **/
    fseek($temp_memory, 0);
    /** modify header to be downloadable csv file **/
    header('Content-Type: application/csv');
    header('Content-Disposition: attachement; filename="' . $file_name . '";');
    /** Send file to browser for download */
    fpassthru($temp_memory);
}

function get_all_tasks_for_customer($user_ids){

    $users = array();

  if(!empty($user_ids)){
        foreach($user_ids as $key=>$user_id){
          $users[] = $user_id->member_id;

    }
  }

    $this->db->select('task.*',FALSE);
    $this->db->select('task_type.id as task_id, task_type.name as task_name',FALSE);
    $this->db->select("task_status.name as task_status_name", FALSE);
    $this->db->select('analyst_first_name.first_name,analyst_first_name.last_name ');
    $this->db->select('analyst_task_status.name as analyst_status_name');
    $this->db->from('ss_tasks as task');
     $this->db->join('ss_status as task_status', 'task.status_id = task_status.id', 'left');
      $this->db->join('ss_status as analyst_task_status', 'task.  analyst_status_id = analyst_task_status.id', 'left');

      $this->db->join('ss_user_details as analyst_first_name', 'task.analysist_id = analyst_first_name.user_id', 'left');

    $this->db->join('ss_task_type as task_type', 'task.task_type_id = task_type.id', 'left');

      $this->db->where_in('task.user_id',$users);
        $query=$this->db->get();

     return $query->result();


}

 function get_task_by_id($id,$user_id='',$analysist_id=''){

  $this->db->select('task.*',FALSE);
   $this->db->select('ss_user_details.first_name');

    $this->db->select('task_type.name,task_type.id as task_id',FALSE);
    // $this->db->select('task_type.id as task_id, task_type.name as task_name',FALSE);
    //$this->db->select('c.id, c.name as assistant_name, c.email as assistant_email, c.phone_number as assistant_phone',FALSE);
    $this->db->from('ss_tasks as task');
    $this->db->join('ss_task_type as task_type', 'task.task_type_id = task_type.id', 'left');

     $this->db->join('ss_user_details', 'task.user_id = ss_user_details.user_id', 'left');
   
   
    $this->db->where_in('task.id',$id);

    if(!empty($user_id)){   
    $this->db->where('task.user_id',$user_id);
    }
    if(!empty($analysist_id)){
      $this->db->where('task.analysist_id',$analysist_id);
    }
    $query=$this->db->get();
     return $query->result();

 }


/**
 *
 *fetching tasks for analyst, displaying these details in analyst dashboard
 *
 *@param int analyst_id
 *@param int meeting_date_array
 *@param int search_by_date
 *@param int customer_status_id
 *@param int analyst_status_id
 *@param int conditions array [filtering]
 *@param int page_type 
 *@return @query
 *
 */


 function get_task_by_analyst($analyst_id,$meeting_date_array,$serach_by_date = NULL,$customer_status_id,$analyst_status_id,$conditions,$page_type = NULL){



  $this->db->select('task.*',FALSE);
  
  $this->db->select('task_type.id as task_id, task_type.name as task_name',FALSE);
  
      $this->db->select('ss_status.id as status_id,ss_status.name as analyst_status');


    $this->db->from('ss_tasks as task');

    $this->db->join('ss_task_type as task_type', 'task.task_type_id = task_type.id', 'left');
     $this->db->where('task.analysist_id',$analyst_id);

    $this->db->join('ss_status','task.analyst_status_id=ss_status.id','left');


 if($analyst_status_id !== NULL){
 $this->db->where('task.analyst_status_id',$analyst_status_id);

 }
   if($meeting_date_array !== NULL){
   if($meeting_date_array['start']!==NULL || $meeting_date_array['end']!==NULL ){
    $this->db->where('task.meeting_date_time >=',$meeting_date_array['start']);
     $this->db->where('task.meeting_date_time <=',$meeting_date_array['end']);

      $this->db->where('task.analyst_status_id !=',8);

       $this->db->order_by('task.meeting_date_time', 'ASC');

  }
}
    

     if(!empty($serach_by_date['from'])){
$this->db->where('task.meeting_date_time ',$serach_by_date['from']);
      }
      if(!empty($serach_by_date['to'])){

        $this->db->where('task.meeting_date_time',$serach_by_date['to']);
      }




    if(!empty($conditions['search_type']) ){
    if($conditions['search_type'] == "Meeting Date"){
      if(!empty($conditions['search_from_date'])){
$this->db->where('task.meeting_date_time >=',$conditions['search_from_date']);
      }
      if(!empty($conditions['search_to_date'])){

        $this->db->where('task.meeting_date_time <=',$conditions['search_to_date']);
      }
    }
    if($conditions['search_type'] == "Assigned Date"){

   if(!empty($conditions['search_from_date'])){

$this->db->where('task.created >=',$conditions['search_from_date']);

      }
      if(!empty($conditions['search_to_date'])){

        $this->db->where('task.created <=',$conditions['search_to_date']);
      }
    }

    if(!empty($conditions['search_target_name'])){
      $this->db->where('task.target_name',$conditions['search_target_name']);
    }
   }
   if($page_type == "Task Board" && (empty($conditions['search_from_date']) && empty($conditions['search_to_date']))){

// print_r($analyst_status_id);
// exit();
 if($analyst_status_id == 8){

$this->db->where('task.updated >=',date('Y-m-d H:i:s', strtotime("-29 days")));
}

}

   $this->db->order_by('task.updated', 'DESC');
       
    $query=$this->db->get();

     return $query->result();

 }



/**
 *
 *deleting tasks from customer panel task tab, this is a multi select deletion
 *
 *@param int data array
 *@param int status_id
 *@param int analyst_status_id
 *@return @query
 *
 */

 function deleteprogress($data,$status_id,$analyst_status_id)
 {
 
$id = $data['delete'];

$query ='';
if($id!=''){
$this->db->set('status_id',$status_id);
$this->db->set('analyst_status_id',$analyst_status_id);
$this->db->where_in('id', $id);
$query= $this->db->update('ss_tasks');
}
  return $query;
 }



/**
 *
 *counting the number of task cretaed by user, this function is using for pagination purpose
 *
 *@param int user_id
 *@param int search array
 *@param string admin
 *@param string bda_task
 *@return @number of tasks
 *
 */


 function count_task($user_id='',$status_id='',$search_array='',$admin = NULL,$bda_tasks = NULL,$current_date_array = NULL)
 {



  
  $this->db->select('*');
    $this->db->from('ss_tasks');
    if($search_array){



      if(!empty($search_array['search_from_date'])){
    
        $this->db->where('ss_tasks.created >=',date("Y-m-d H:i:s",strtotime($search_array['search_from_date'] . ' ' . "00:00:00")));
      }
      if(!empty($search_array['search_to_date'])){

        $this->db->where('ss_tasks.created <=',date("Y-m-d H:i:s",strtotime($search_array['search_to_date']. ' ' . "23:59:59")));
      }


      if(isset($search_array['search_task_type']) && $search_array['search_task_type'] !== "All" && $search_array['search_task_type'] !== NULL && !empty($search_array['search_task_type'])){



      $this->db->where('ss_tasks.task_type_id',$search_array['search_task_type']);
    }

     if(isset($search_array['search_customer']) && $search_array['search_customer'] !== "All" && $search_array['search_customer']!== NULL && !empty($search_array['search_customer'])){
    
           $this->db->like('ss_tasks.user_id',$search_array['search_customer']);

    }


       if(isset($search_array['search_bda']) && $search_array['search_bda'] !== "All" && $search_array['search_bda'] !== NULL && !empty($search_array['search_bda'])){
      $this->db->like('ss_tasks.bda_id',$search_array['search_bda']);
    }

     if(isset($search_array['search_bda']) && $search_array['search_status'] !== NULL && !empty($search_array['search_status'])){
      $this->db->where('ss_tasks.status_id',$search_array['search_status']);
    }


    if(isset($search_array['search_analyst']) && $search_array['search_analyst'] !== "All" && $search_array['search_analyst'] !== NULL && !empty($search_array['search_analyst'])){
      $this->db->where('ss_tasks.analysist_id',$search_array['search_analyst']);
    }



    if( isset($search_array['search_meeting_date_time']) && $search_array['search_meeting_date_time'] !== NULL && !empty($search_array['search_meeting_date_time'])){
      
      $this->db->where('ss_tasks.meeting_date_time',$search_array['search_meeting_date_time']);
    }


      if(isset($search_array['meeting_start_date'])&& !empty($search_array['meeting_start_date'])){

    $this->db->where('ss_tasks.meeting_date_time >=',date("Y-m-d H:i:s",strtotime($search_array['meeting_start_date'] . ' ' . '00:00:00')));
   }
   if(isset($search_array['meeting_to_date'])&& !empty($search_array['meeting_to_date'])){
    $this->db->where('ss_tasks.meeting_date_time <=',date("Y-m-d H:i:s",strtotime($search_array['meeting_to_date'] . ' ' . '23:59:59')));
   }


      if(isset($search_array['search_target']) && $search_array['search_target'] !== NULL && !empty($search_array['search_target'])){
    
      $this->db->like('ss_tasks.target_name',$search_array['search_target']
        );
    }
  }



   if(!empty($search_array['created_start_date'])){
  
        $this->db->where('ss_tasks.created >=',date("Y-m-d H:i:s",strtotime($search_array['created_start_date'] .' '. '00:00:00')));
      }
         if(!empty($search_array['created_to_date'])){

        $this->db->where('ss_tasks.created <=',date("Y-m-d H:i:s",strtotime($search_array['created_to_date']  .' '. '23:59:59')));
      }


      
   if(!empty($current_date_array['created_start_date'])){
  
        $this->db->where('ss_tasks.created >=',date("Y-m-d H:i:s",strtotime($current_date_array['created_start_date'] .' '. '00:00:00')));
      }
         if(!empty($current_date_array['created_to_date'])){

        $this->db->where('ss_tasks.created <=',date("Y-m-d H:i:s",strtotime($current_date_array['created_to_date']  .' '. '23:59:59')));
      }






    if(isset($search_array['search_team_member']) && $search_array['search_team_member'] !== "All" && $search_array['search_team_member']!== NULL && !empty($search_array['search_team_member'])){
    
           $this->db->where('ss_tasks.user_id',$search_array['search_team_member']);

    }


    if(isset($status_id) && $status_id !== NULL  && $status_id !== "All"){
    
    $this->db->where('ss_tasks.status_id',$status_id);
    }
   
    if($admin == NULL && $bda_tasks == NULL){

    $this->db->where_in('ss_tasks.user_id',$user_id);
    }

    elseif($bda_tasks !== NULL){
    $this->db->where('ss_tasks.bda_id',$user_id);
    }
    $query = $this->db->get();



    return $query->num_rows();
 }


function get_no_of_completed_tasks(){

    $this->db->select('task.status_id as no_of_completed_tasks');
    $this->db->from("ss_tasks as task");
    $this->db->where('task.status_id',5);
    $result = $this->db->get()->result();

    return $result;

}


 function get_number_of_connections($task_ids){
    $this->db->select('SUM(task.no_of_connections) as no_of_connections');
    $this->db->from("ss_tasks as task");
    $this->db->where_in('task.id', $task_ids);
    $result = $this->db->get()->result();

    return $result[0]->no_of_connections;

 }

 function get_total_number_of_connections(){
    $this->db->select('SUM(task.no_of_connections) as no_of_connections');
    $this->db->from("ss_tasks as task");
    $result = $this->db->get()->result();

    return $result[0]->no_of_connections;

 }



/**
 *this function is for displaying cumulative summary of tasks created by users.This is using for displaying Task summary in admin panel
 *
 *
 *@param int order
 *@param int order_type
 *@param int limit start
 *@param int limit end
 *@param int search array[filtering purpose]
 *@return @query
 *
 */


function get_summary($order=null, $order_type='Asc', $limit_start, $limit_end,$search_array){
   $this->db->select('user.id', FALSE);
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

    //$this->db->join('ss_tasks as task', 'task.user_id = user_details.user_id');

   $this->db->join('ss_status as status', 'status.id = user.status_id');


   if(isset($search_array['search_start_date'])&& !empty($search_array['search_start_date'])){
    $this->db->where('transaction_details.transaction_date >=',date("Y-m-d H:i:s",strtotime($search_array['search_start_date'] . ' ' . '00:00:00')));
   }
   if(isset($search_array['search_to_date'])&& !empty($search_array['search_to_date'])){
    $this->db->where('transaction_details.transaction_date <=',date("Y-m-d H:i:s",strtotime($search_array['search_to_date'] . ' ' . '23:59:59')));
   }


    if(isset($search_array['search_customer']) && $search_array['search_customer'] !== "All" && $search_array['search_customer']!== NULL && !empty($search_array['search_customer'])){
    
           $this->db->like('user.id',$search_array['search_customer']);

    }


       if(isset($search_array['search_bda']) && $search_array['search_bda'] !== "All" && $search_array['search_bda'] !== NULL && !empty($search_array['search_bda'])){
      $this->db->like('user_details.bda_id',$search_array['search_bda']);
    }



   
    $this->db->group_by('user.id'); 

    $this->db->where('user.role_id',2);
       $this->db->where('user.status_id',1);

      if($limit_start && $limit_end){
          $this->db->limit($limit_start, $limit_end); 
        }
         if($limit_start != null){
          $this->db->limit($limit_start, $limit_end);    
        }
       $query = $this->db->get()->result();
      return $query;

}

/**
   *inserting attachments from users
   *
   *@param $data array
   *@return true 

  /**
   *inserting attachments from users
   *
   *@param $data array
   *@return true 
   */
  public function insert_files($data = array()){


 $check_customer = $this->check_customer($data[0]['user_id']);

if(!empty($check_customer)){
  for($i=0; $i< count($data); $i++){

    $data[$i]['by_customer'] = 1;
  }

}

    $insert = $this->db->insert_batch('ss_uploads',$data);
    return $insert?true:false;
  }


  private function check_customer($user_id){


    $this->db->select('user.id');
    $this->db->from('ss_users as user');
    $this->db->where('user.id', $user_id);
     $this->db->where('user.role_id', 2);

     $query = $this->db->get()->result();

    return $query;
     

  }
  /**
   *inserting url links from users
   *
   *@param $data array
   *@return true 
   */
    public function insert_links($data = array()){
        $insert = $this->db->insert_batch('ss_links',$data);
        return $insert?true:false;
    }


    public function logData($tid){

      $query = $this->db->get_where('ss_work_logs', array('status' => 0, 'task_id' => $tid));
    return $query->result();
    }


    /**
   *inserting work logs 
   *
   *@param $data array
   *@return true 
   */

    public function insertlog($data){
     
      $task_id = $data['task_id'];
      $log_id = $data['log_id'];
      $log_date = $data['log_date'];
      $log_worked_hrs = $data['log_worked_hrs'];
      $log_worked_min = $data['log_worked_min'];
      // print_r($log_work);
      // exit;
      $log_to_insert = array();
       $log_to_update = array();
       $log_id_to_update = array();
       $log_id_to_insert = array();
       for ($i = 0; $i < count($log_date); $i++) {
        //print_r($log_worked_hrs);
          if(empty($log_date[$i])){
              $log_date[$i] = date("Y-m-d");
          }


            if(!empty($log_id[$i])){
              //print_r("expression");
              $log_to_update['log_date'] = date("Y-m-d",strtotime($log_date[$i]));;
              $log_to_update['log_hrs'] = $log_worked_hrs[$i];
              $log_to_update['log_min'] = $log_worked_min[$i];
              $log_to_update['id'] = $log_id[$i];
              $log_to_update['status'] = 0;
              $log_to_update['modified'] = date("Y-m-d h:i:sa");
        // $assistant_to_update['created'] = date("Y-m-d h:i:sa");
              $log_id_to_update[] = $log_to_update;
          }
          else{
            //print("arg");
            $log_to_insert['task_id'] = $task_id;
          $log_to_insert['log_date'] = date("Y-m-d", strtotime($log_date[$i]));
          $log_to_insert['log_hrs'] = $log_worked_hrs[$i];
          $log_to_insert['log_min'] = $log_worked_min[$i];
        
         $log_to_insert['status'] = 0;
         
         $log_to_insert['created'] = date("Y-m-d h:i:sa");
         $log_to_insert['modified'] = date("Y-m-d h:i:sa");
         $log_id_to_insert[]  = $log_to_insert; 
//print_r($log_to_insert['log_work']);
          }
          
           
       }

       if(!empty($log_to_insert)){

    $this->db->insert_batch('ss_work_logs', $log_id_to_insert);
    }
    if(!empty($log_to_update)){
      
    $this->db->update_batch('ss_work_logs', $log_id_to_update,'id');
    }
      // $insert = $this->db->insert('ss_work_logs',$data);
      //   return $insert?true:false;
    }



/**
  *update task by id
  *this is used for task edit in analyst dashboard,admin panel
  *@param update_task array 
  *@return query
*/


function update_task($update_task=''){

   
$query ='';
if($update_task['id']!=''){
  //if(!empty($update_task['no_of_connections'])){
$this->db->set('no_of_connections', $update_task['no_of_connections']);
//}
if(!empty($update_task['analyst_note'])){
$this->db->set('analyst_note', $update_task['analyst_note']);
}

if(!empty($update_task['analyst_id'])){
$this->db->set('analysist_id', $update_task['analyst_id']);
}

if(($update_task['status_id'] !='')){
$this->db->set('status_id', $update_task['status_id']);
}
if(!empty($update_task['present_company'])){
$this->db->set('present_company', $update_task['present_company']);
}

if(!empty($update_task['target_name'])){
$this->db->set('target_name', $update_task['target_name']);
}

if(!empty($update_task['previous_company'])){
$this->db->set('previous_company', $update_task['previous_company']);
}
if(!empty($update_task['account_name'])){
$this->db->set('account_name', $update_task['account_name']);
}
if(!empty($update_task['target_name'])){
$this->db->set('target_name', $update_task['target_name']);
}
if(!empty($update_task['comments_additional_info'])){
$this->db->set('comments_additional_info', $update_task['comments_additional_info']);
}

//if(!empty($update_task['analyst_note'])){
$this->db->set('analyst_note', $update_task['analyst_note']);
//}


if(!empty($update_task['email'])){
$this->db->set('email', $update_task['email']);
}

if(!empty($update_task['home_address'])){
$this->db->set('home_address', $update_task['home_address']);
}

if(!empty($update_task['meeting_date_time'])){
$this->db->set('meeting_date_time', $update_task['meeting_date_time']);
}

if(!empty($update_task['updated'])){
$this->db->set('updated', $update_task['updated']);
}if(!empty($update_task['analyst_status_id'])){
$this->db->set('analyst_status_id', $update_task['analyst_status_id']);
}
$this->db->where_in('id', $update_task['id']);
$query= $this->db->update('ss_tasks');

return $query;
}
   }


   /**
    *fetching all the attachments saved by the customer on creation of task 
    *@param task id
    *@param users_id
    *@return file uplaods if there any
   */

    function getAttachments($tid=NULL, $is_customer = NULL){ //$is_customer = 1 or 0

    $this->db->select('*');
    $this->db->from('ss_uploads');

    if(!empty($tid) && $tid !== NULL){
    $this->db->where('ss_uploads.task_id',$tid);
  }
    if($is_customer !== NULL){
     $this->db->where('ss_uploads.by_customer',$is_customer);
      }
    
    $query = $this->db->get();
    return $query->result();
    }


    /**

    *fetching all the url links saved by the ss360 team  
    *@param task id
    *@param users_id
    *@return url links if there any

   */


     function getLinks($tid){
        $this->db->select('*');
    $this->db->from('ss_links');
    $this->db->where('ss_links.task_id',$tid);
    // $this->db->where('ss_links.user_id',$user_id);
    $query = $this->db->get();
    return $query->result();
    }


    /**
    *fetching sum of log hrs and sum of log min for a particular task id 
    *@param task id
    *@return query
   */


    function workLogs($tid){
      $this->db->select_sum('log_hrs');
      $this->db->select_sum('log_min');
      $this->db->from('ss_work_logs');
      $this->db->where('ss_work_logs.task_id',$tid);
      $query = $this->db->get();
    return $query->result();

    }


/**
 *
 *counting the number of task cretaed by user, this function is using for pagination purpose in admin task summary tab
 *
 *@param int user_id
 *@param int search array
 *@param string admin
 *@return @number of tasks
 *
 */
function count_task_summary($user_id='',$status_id='',$search_array='',$admin = NULL)
 {
  
    $this->db->select('*');
    $this->db->from('ss_tasks');

     if(isset($search_array['search_customer']) && $search_array['search_customer'] !== "All" && $search_array['search_customer']!== NULL && !empty($search_array['search_customer'])){
    
           $this->db->where('ss_tasks.user_id',$search_array['search_customer']);

    }


       if(isset($search_array['search_bda']) && $search_array['search_bda'] !== "All" && $search_array['search_bda'] !== NULL && !empty($search_array['search_bda'])){
      $this->db->where('ss_tasks.bda_id',$search_array['search_bda']);
    }



    if( isset($search_array['search_meeting_date_time']) && $search_array['search_meeting_date_time'] !== NULL && !empty($search_array['search_meeting_date_time'])){
      
      $this->db->where('ss_tasks.meeting_date_time',$search_array['search_meeting_date_time']);
    }


    if($admin == NULL){
    $this->db->where('ss_tasks.user_id',$user_id);
    }


    $query = $this->db->get();
    return $query->num_rows();
 }

/**
 *updating analyst for a particular task
 *used in admin panel task listing
 *@param int analyst_id
 *@param int task_id
*/

function change_analyst($analyst_id,$task_id)
{

    $this->db->set('analysist_id',$analyst_id);
    $this->db->where('id',$task_id);
    return $this->db->update('ss_tasks');
    
}




function get_target_name($user_id){


  $this->db->distinct();
  $this->db->select('target_name');

  $this->db->from('ss_tasks');

if(!empty($user_id)){
$this->db->where_in('user_id',$user_id);
}
  $query = $this->db->get();
  return  $query->result();
  

}


function get_all_tasks($user_id){

  $this->db->select('id as task_id');
  $this->db->from('ss_tasks');
  $this->db->where_in('user_id',$user_id);
  //$this->db->where_in('task.created', $from, $to);
  $query = $this->db->get();
  return  $query->result();


}

/**
 *fetching all the tasks created by customer based on billing cycle
 *used for trigerring notification email for customer
 *@param int from_date
 *@param int user_id
 *@param int to_date
 *@return query 
 */



function get_all_tasks_by_customers($user_id,$from_date,$to_date){

  $this->db->select('task.id as task_id');
  $this->db->from('ss_tasks as task');
  $this->db->where_in('task.user_id',$user_id);

$this->db->where('task.created >=',date("Y-m-d H:i:s",strtotime($from_date.' '.'00:00:00')));

$this->db->where('task.created <=',date("Y-m-d H:i:s",strtotime($to_date.' '.'23:59:59')));

  $query = $this->db->get();
  return  $query->result();


}

  /**
  *fetching sum of log hrs and sum of log min for a  task_id array 
  *@param task id array
  *@return query
  */
 function total_work_log($task_array){

      $this->db->select_sum('log_hrs');
      $this->db->select_sum('log_min');
      $this->db->from('ss_work_logs');
      $this->db->where_in('ss_work_logs.task_id',$task_array);
      $query = $this->db->get();
      return $query->result();

    }
   


   function total_log_hours($task_array){

      $this->db->select_sum('log_hrs');
      $this->db->select_sum('log_min');
      $this->db->from('ss_work_logs');
      $this->db->where_in('ss_work_logs.task_id',$task_array);
      $query = $this->db->get()->result();
      $data = array();

      $result = array();
         $grand_total_calculated_hours = floor($query[0]->log_min/60);
         $grand_total_calculated_min = $query[0]->log_min%60;
      
         $data['final_logged_hours'] =$query[0]->log_hrs + $grand_total_calculated_hours;
         $data['final_min_to_add'] = $grand_total_calculated_min;
        $array_to_object = (object) $data;
        $result[] = $array_to_object;
      //   print "<pre>";
      // print_r($result);
      // exit();
      return $result;



    }
   

   function get_customer_name($customer_id){


        $this->db->select('first_name');
        $this->db->select('last_name');
        $this->db->select('username');
        $this->db->from('ss_users');


       $this->db->join('ss_user_details','ss_user_details.user_id = ss_users.id', 'left');

        $this->db->where('user_id',$customer_id);
        $query = $this->db->get();
        return  $query->result();



   }

   function get_customer_email($task_id,$customer_id){

     $this->db->select('email_to_notifiy');
     $this->db->from('ss_tasks');


  if(!empty($customer_id)){

    $this->db->where('user_id',$customer_id);
  }

  if(!empty($task_id)){

    $this->db->where('id',$task_id);
  }
    $query = $this->db->get();
    return  $query->result();


   

  }


  function admin_emails(){

     $this->db->select('email');
     $this->db->from('ss_admin_email');
     $query = $this->db->get();
     return  $query->result();
  } 



 /**
  * This function will fetch the following details about the users
  * Active Users
  * their Tasks
  * Their Tasks Sum of Work Logs
  * Their plan Hours
  * Calculating 80, 100 from their plan hours
  * used for sending notification emails for customer
  */
function get_customers_details(){
   $this->db->select('user.id,user.username', FALSE);
   $this->db->select('user_details.first_name,user_details.last_name,user_details.email_notification_status,user_details.bda_id');
   $this->db->select('transaction_details.transaction_date', FALSE);
    $this->db->select('ss_users_plans.plan_hours as team_plan_hours');
    $this->db->select('ss_plans.plan_hours');
  $this->db->select('round((ss_users_plans.plan_hours * 80) / 100) as team_seventy_percent_hours');
    $this->db->select('round((ss_users_plans.plan_hours * 100) / 100) as team_hunderd_percent_hours');
    $this->db->select('ss_users_plans.plan_hours as team_plan_hours');
    $this->db->select('ss_plans.plan_hours as plan_hours');
    $this->db->select('round((ss_plans.plan_hours * 80) / 100) as seventy_percent_hours');
    $this->db->select('round((ss_plans.plan_hours * 100) / 100) as hunderd_percent_hours');

   $this->db->from('ss_users as user');
   $this->db->join('ss_user_details as user_details', 'user.id = user_details.user_id');
   $this->db->join('ss_transaction_details as transaction_details', 'transaction_details.user_id = user.id');

   $this->db->join('ss_plans', 'ss_plans.id = user_details.plan_id');

   // $this->db->join('user.username as bda_email','bda_email.id = user_details.bda_id', 'left');
    $this->db->join('ss_users_plans','ss_users_plans.user_id = user.id', 'left');

    $this->db->where('user.role_id',2);

    $this->db->where_in('user_details.email_notification_status',array(12,14));

    $this->db->where('user.status_id',1);

    $this->db->group_by('user.id');
    $query=$this->db->get();

    return $query->result();

  } 


  /**
  * This function will fetch the following details about the users
  * Active Users
  * their Tasks
  * Their plan Hours
  * Calculating 80, 100 from their plan hours
  */
function get_customers_plan_hours(){
   $this->db->select('user.id,user.username', FALSE);
   $this->db->select('user_details.first_name,user_details.last_name,user_details.email_notification_status,user_details.bda_id');
   $this->db->select('transaction_details.transaction_date', FALSE);
    $this->db->select('ss_users_plans.plan_hours as team_plan_hours');
    $this->db->select('ss_plans.plan_hours');
  $this->db->select('round((ss_users_plans.plan_hours * 40) / 100) as team_fourty_percent_hours');
    $this->db->select('round((ss_users_plans.plan_hours * 60) / 100) as team_sixty_percent_hours');
 $this->db->select('ss_users_plans.plan_hours as team_plan_hours');
    $this->db->select('ss_plans.plan_hours as plan_hours');
    $this->db->select('round((ss_plans.plan_hours * 40) / 100) as fourty_percent_hours');
    $this->db->select('round((ss_plans.plan_hours * 60) / 100) as sixty_percent_hours');

   $this->db->from('ss_users as user');
   $this->db->join('ss_user_details as user_details', 'user.id = user_details.user_id');
   $this->db->join('ss_transaction_details as transaction_details', 'transaction_details.user_id = user.id');

   $this->db->join('ss_plans', 'ss_plans.id = user_details.plan_id');

   // $this->db->join('user.username as bda_email','bda_email.id = user_details.bda_id', 'left');
    $this->db->join('ss_users_plans','ss_users_plans.user_id = user.id', 'left');

    $this->db->where('user.role_id',2);

    $this->db->where('user.status_id',1);

    $this->db->group_by('user.id');
    $query=$this->db->get();

    return $query->result();

  } 



  function fetch_email_for_id($id){

         $this->db->select('user.username', FALSE);

         $this->db->from('ss_users as user');
         $this->db->where("user.id", $id);
         $id  = $this->db->get()->result();

         return $id[0]->username;
  }



  


}