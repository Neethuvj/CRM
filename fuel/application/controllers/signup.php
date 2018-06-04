<?php
class Signup extends CI_Controller {
    function __construct() 
    {
        parent::__construct();
            // for flash data
            $this->load->library('session');
            $this->load->library('email');
           
            if (!$this->fuel->config('admin_enabled')) show_404();

            $this->load->vars(array(
                'js' => '', 
                'css' => css($this->fuel->config('xtra_css')), // use CSS function here because of the asset library path changes below
                'js_controller_params' => array(), 
                'keyboard_shortcuts' => $this->fuel->config('keyboard_shortcuts')));

            // change assets path to admin
            $this->asset->assets_path = $this->fuel->config('fuel_assets_path');

            // set asset output settings
            $this->asset->assets_output = $this->fuel->config('fuel_assets_output');
            
            $this->lang->load('fuel');
            $this->load->helper('ajax');
            $this->load->library('form_builder');

            $this->load->module_model(FUEL_FOLDER, 'fuel_users_model');

            // set configuration paths for assets in case they are differernt from front end
            $this->asset->assets_module ='fuel';
            $this->asset->assets_folders = array(
                    'images' => 'images/',
                    'css' => 'css/',
                    'js' => 'js/',
                );
    }

    function checkusername() {
        $customerName = $this->input->post('customerName');
        //$query = $this->db->select('user_name')->where('user_name', $customerName)->get('fuel_users');
        $sql = "SELECT * FROM fuel_users WHERE user_name = '".$customerName."'";
        $query = $this->db->query($sql);
        echo $query->num_rows();
    }

    function checkemail() {
        $customeremail = $this->input->post('customeremail');
        $sql1 = "SELECT * FROM fuel_users WHERE email = '".$customeremail."'";
        $query1 = $this->db->query($sql1);
        echo $query1->num_rows();
    }    

    function register(){

       $plan_selected = $this->input->post('plan_id');

       if((int) $plan_selected !== 3){

       redirect(PHASE2_URL."user/register?plan_id=".$plan_selected , 'refresh');
      }
      else{
        redirect(PHASE2_URL."user/team_register?plan_id=".$plan_selected , 'refresh');

      }


    }

    function index()
    {
        // $this->load->library('session');
        $data=array(
        'first_name'=>$this->input->post('name'),
        'last_name'=>$this->input->post('lastname'),
        'user_name'=>$this->input->post('username'),
        'password'=>md5($this->input->post('password')),
        'email'=>$this->input->post('email'),
        'Website'=>$this->input->post('website'),
        'Message'=>$this->input->post('message'));
        $insert= $this->db->insert('fuel_users',$data);

        if($insert)
        {
            redirect("/signup1");
        }
        else
        { 
            $this->session->set_flashdata('error_message', 'registration Failed ');
            // After that you need to used redirect function instead of load view such as 
            redirect("/");
        }
    } 
    //activate user account
    function verifyEmailID($key)
    {
        $data = array('status' => 1);
        $this->db->where('md5(email)', $key);
        return $this->db->update('fuel_users', $data);
    }

  

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

    public function sendMail($password, $x_email) {
        $config = Array(
                'protocol' => 'smtp',
                'smtp_host' => 'smtp.gmail.com',
                'smtp_port' => 465,
                'smtp_user' => 'fsit.common@gmail.com',
                'smtp_pass' => 'fsit.common@',
                'mailtype'  => 'html',
                'smtp_crypto' => 'ssl',
                'charset'   => 'utf-8'
                );
        $message = 'Hi,
                This Mail is from SalesSupport360.
                Your Registration successfully completed.
                Your User Name : '.$x_email.
                ' Your Password : '.$password;
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        
        $this->email->from('danieltitus007@gmail.com'); // change it to yours
        $this->email->to($x_email);// change it to yours
        $this->email->subject('Your Password');
        $this->email->message($message);
        if($this->email->send()) {
            redirect('/userlogin?user_name='.$x_email.'&password='.$password); 
            echo 'Email sent.';
        } else {
            show_error($this->email->print_debugger());
        }
    }
}
?>