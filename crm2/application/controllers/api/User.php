<?php
require(APPPATH.'/libraries/REST_Controller.php');

/**
 * Controller function written for give the API's for Mobile apps.
 *
 * Please see https://github.com/chriskacerguis/codeigniter-restserver for how to document.
 * 
 * this provides 4 types of REST URLS function_name_get for GET, function_name_post for POST, function_name_delete for DELETE, function_name_put FOR PUT request
 *
 * Currently all these urls can be accessed with basic authentication admin/1234.
 * @todo Need to work on the authentication
 *
 * @package Controllers
 * @subpackage API
 */

class User extends REST_Controller{



  private $data = array();
  public function __construct(){

    parent::__construct();
        $this->load->model('Users_model');
  }


  /**
   * Sample function to get customer Details based on the id passed in the url.
   * e.g site_url.com/api/user/customer_details?id=679
   */
   public function customer_details_get()
    {
        if(!$this->get('id'))
        {

            $this->response(NULL, 400);
        }
 
        $user = $this->Users_model->fetchdata( $this->get('id') );
         
        if($user)
        {

            $this->response($user, 200); // 200 being the HTTP response code
        }
 
        else
        {


            $this->response(NULL, 404);
        }
    }


}