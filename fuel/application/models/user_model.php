<?php

class user_model extends CI_Model {


	function validate($user_name, $password)
	{
	
	    $limit = 1;
	    $this->db->select('*');
	    $this->db->from('ss_users');
	    $this->db->where('ss_users.username',$user_name);
	    $this->db->where('ss_users.password',$password);
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
	
	
	/**
	 * Common Function to send mail accessed from all controllers
	 */
	function send_mail($data, $message, $subject, $cc_array = NULL,$bda_email = NULL,$from_bda = NULL) {
	
		$imgPath = base_url(). 'assets/images/logo_email.png';
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
			
			  $message ."
		    <br /><br /><br />Sincerely, <br />SalesSupport360 Team</td>  </tr> </tbody></table></td></tr>
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
		  	
		  $this->load->library('email', $config);
		  $this->email->clear();
		  $this->email->set_newline("\r\n");
		  $this->email->from(SMTP_USER,SMTP_USER); // change it to yours
		 
		  $this->email->to($data['email']);
		 
		   
		  $this->email->subject($subject);
		  $this->email->message($email_teamplate);
		
		  if($this->email->send()) {
		  	return true;
		  	
		  	echo 'Email sent.';
		  } else {
		  	echo $this->email->print_debugger();
		  	exit();
		  	return false;
		  }
		}


}