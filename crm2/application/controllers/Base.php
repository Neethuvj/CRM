<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Functions for Base controller
 * @package Controllers
 * @subpackage General
 */


class Base extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}


	/**
	 * Globally setting the sidebar status in the session, so the clopsed event will be retained even after the next page visit
	 */
	public function check_collapse_status(){

		$sidebar_status = $this->input->post('collapse_status');
		$data = array('sidebar_status' => $sidebar_status);
		return $this->session->set_userdata($data);
		
	}
}
