<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('email');
        $this->load->model('pathologist/login_model');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: X-Requested-With");
    }

    function index() {
		if (is_pathologist()) {
            redirect('pathologist/dashboard/patientlist');
        }
         $data = '';	
            $this->load->view('pathologist/pathologistlogin_view', $data);
    }
	 
    function verify_login() {
		
		if (is_pathologist()) {
            redirect('pathologist/dashboard');
        }
		
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
		
        if ($this->form_validation->run() == FALSE) {
          
		  echo json_encode(array("status" => "0", "msg" => "Email or password is invalid"));
        
		} else {
			
            $email = $this->input->post('email');
			$password = $this->input->post('password');
            $result = $this->login_model->checklogin($email, $password);
			
            if (!empty($result)) {
				
				$sess_array = array(
                    'id' => $result[0]->id,
                    'name' => ucwords($result[0]->name),
                    'email' => $result[0]->email
                );
                $this->session->set_userdata('pathologist_logged_in',$sess_array);
                $this->app_tarce($result[0]->id);
                
                echo json_encode(array("status" => "1", "msg" => ""));
            } else {
                echo json_encode(array("status" => "0", "msg" => "Email or password is invalid"));
            }
        }
    }

    function app_tarce($uid) {
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        $page = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
        if (!empty($_SERVER['QUERY_STRING'])) {
            $page = $_SERVER['QUERY_STRING'];
        } else {
            $page = "";
        }
        if (!empty($_POST)) {
            $user_post_data = $_POST;
        } else {
            $user_post_data = array();
        }
        $user_post_data = json_encode($user_post_data);
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $remotehost = @getHostByAddr($ipaddress);
        $user_info = json_encode(array("Ip" => $ipaddress, "Page" => $page, "UserAgent" => $useragent, "RemoteHost" => $remotehost));
        $user_track_data = array("url" => $actual_link, "user_fk" => $uid, "user_details" => $user_info, "data" => $user_post_data, "createddate" => date("Y-m-d H:i:s"), "type" => "AdminLogin");
        //print_R($user_track_data);
        $app_info = $this->login_model->master_fun_insert("user_track", $user_track_data);
       
    }
	function logout() {
		
        $sess_array = null;
        $this->session->set_userdata('pathologist_logged_in', $sess_array);
        $this->session->unset_userdata('pathologist_logged_in');
       
    }

   

}

?>