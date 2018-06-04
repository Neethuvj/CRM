<?php

/**
 * Functions for Status Model
 * @package Model
 * 
 */
class Status_model extends CI_Model {


function fetch_status($name, $type)
  {

  	$this->db->select('status.id',FALSE);
  	 $this->db->from('ss_status as status');
  	   $this->db->where('status.name',$name);
  	   $this->db->where('status.type',$type);

      $query=$this->db->get();
    
    return $query->result();
  }



  function fetch_status_by_id($id)
  {

  	$this->db->select('status.name',FALSE);
  	 $this->db->from('ss_status as status');
  	   $this->db->where('status.id',$id);
  	   

      $query=$this->db->get();
    
    return $query->result();
  }


  function fetch_status_by_type($type)
  {

    $this->db->select('status.id, status.name',FALSE);
     $this->db->from('ss_status as status');
       $this->db->where('status.type',$type);

      $query=$this->db->get();
    
    return $query->result();
  }


}