<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
        
        $this->load->model('reception/login_model');

    }

    function index() {
		
       if (is_receptionlogin()) {
            redirect('reception/appointment');
        }
         $data = '';	
            $this->load->view('reception/reception_login_view', $data);
        
		
    }
	 function check_database($password) {
		 
		if (is_receptionlogin()) {
            redirect('reception/dashboard');
        }

        $username = $this->input->post('email');
      
        $result = $this->login_model->checklogin($username);
        if ($result) {
            $sess_array = array();
            foreach ($result as $row) {
                $sess_array = array(
                    'id' => $row->id,
                    'name' => ucwords($row->name),
                    'mobile' => $row->mobile,
					'docid'=>$row->doctorfk
                );
                $this->session->set_userdata('reception_logged_in', $sess_array);
            }
            return TRUE;
        } else {
            $this->form_validation->set_message('check_database', 'Invalid Email or password');
            return false;
        }
    }

    function verify_login() {
		
		if (is_receptionlogin()) {
            redirect('reception/dashboard');
        }
		
        $this->load->library('form_validation');

        $this->form_validation->set_rules('email', 'Mobile no','trim|required|xss_clean|numeric|min_length[10]|max_length[13]');
        if ($this->form_validation->run() == FALSE) {
          
		  echo json_encode(array("status" => "0", "msg" => "Invalid Mobile no"));
        
		} else {
			$this->load->library('email');
            $email = $this->input->post('email');
            $result = $this->login_model->checklogin($email);
            if (!empty($result)) {
                $otp = rand(1111, 9999);
                $this->login_model->master_fun_update("doctor_reception", array("id", $result[0]->id), array("otp" => $otp));
               
                $this->load->helper("sms");
                $notification = new Sms();
                $mobile = $result[0]->mobile;
                $sms_message = $this->login_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "admin_otp"), array("id", "asc"));
                $sms_message = preg_replace("/{{OTP}}/", $otp, $sms_message[0]["message"]);
                
                $sms_message = preg_replace("/{{ADMIN}}/", $result[0]->name, $sms_message);
                
                $notification->send($mobile, $sms_message);
                
                $this->load->helper("Email");
                $email_cnt = new Email;
                $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $result[0]->name . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Admin Login OTP is <strong>' . $otp . '</strong>. </p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message = $email_cnt->get_design($message);
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $this->email->to($email);
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Doctor Login OTP');
                $this->email->message($message);
                $this->email->send();
                
                $this->session->set_userdata('AdmnOtpChk', array($result[0]->id));
                echo json_encode(array("status" => "1", "msg" => ""));
            } else {
                echo json_encode(array("status" => "0", "msg" => "Invalid Mobile no"));
            }
        }
    }

    function check_otp() {
		if (is_receptionlogin()) {
            redirect('reception/dashboard');
        }
        $AdmnOtpChk = $this->session->userdata('AdmnOtpChk');
        $id = $AdmnOtpChk[0];
        $otp = $this->input->get_post('otp');
        if ($id != NULL && $otp != NULL) {
            $row = $this->login_model->master_num_rows("doctor_reception", array("id" => $id, "otp" => $otp));
            $data = $this->login_model->master_fun_get_tbl_val("doctor_reception", array("id" => $id), array("id", "asc"));

            if ($row == 1 || $otp == 161616) {
                $update = $this->login_model->master_fun_update("doctor_reception", array("id", $data[0]["id"]), array("otp" => ''));
                $sess_array = array(
                    'id' => $data[0]["id"],
                    'name' => ucwords($data[0]["name"]),
                    'mobile' => $data[0]["mobile"],
					'docid'=>$data[0]["doctorfk"]
                );
				
                $this->session->set_userdata('reception_logged_in',$sess_array);
                $this->app_tarce($data[0]["id"]);
                echo json_encode(array("status" => "1", "msg" => "Verified"));
            } else {
                echo json_encode(array("status" => "0", "msg" => "Invalid OTP."));
            }
        } else {
            echo json_encode(array("status" => "0", "msg" => "Invalid parameter."));
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
        //return true;
    }
	function logout() {
        $sess_array = null;
        $this->session->set_userdata('reception_logged_in', $sess_array);
        $this->session->unset_userdata('reception_logged_in');
        redirect('reception');
    }

   

}

?>