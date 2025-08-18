<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('email');
        $this->load->model('vendor/login_model');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: X-Requested-With");
    }

    function index() {
        if (is_doctorlogin()) {
            redirect('vendor/dashboard');
        }
        $data = '';
        $this->load->view('vendor/vendor_login_view', $data);
    }

//    function check_database($password) {
//
//        if (is_doctorlogin()) {
//            redirect('doctor/dashboard');
//        }
//
//        $username = $this->input->post('email');
//
//        $result = $this->login_model->checklogin($username, $password);
//        if ($result) {
//            $sess_array = array();
//            foreach ($result as $row) {
//                $sess_array = array(
//                    'id' => $row->id,
//                    'name' => ucwords($row->full_name),
//                    'email' => $row->email
//                );
//                $this->session->set_userdata('doctot_logged_in', $sess_array);
//            }
//            return TRUE;
//        } else {
//            $this->form_validation->set_message('check_database', 'Invalid Email or password');
//            return false;
//        }
//    }

    function verify_login() {
//        if (is_doctorlogin()) {
//            redirect('doctor/dashboard');
//        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {

            echo json_encode(array("status" => "0", "msg" => "Invalid Email or Password"));
        } else {

            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $result = $this->login_model->checklogin($email, $password);
            //echo "<pre>"; print_r($result); exit;
            if (!empty($result)) {
//                $otp = rand(1111, 9999);
//                $this->login_model->master_fun_update("doctor_master", array("id", $result[0]->id), array("otp" => $otp));
//                /* Nishit Send sms start */
//                $this->load->helper("sms");
//                $notification = new Sms();
//                $mobile = $result[0]->mobile;
//                $sms_message = $this->login_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "admin_otp"), array("id", "asc"));
//                $sms_message = preg_replace("/{{OTP}}/", $otp, $sms_message[0]["message"]);
//
//                $sms_message = preg_replace("/{{ADMIN}}/", $result[0]->full_name, $sms_message);
//
//                $notification->send($mobile, $sms_message);
//
//                $this->load->helper("Email");
//                $email_cnt = new Email;
//                $message = '<div style="padding:0 4%;">
//                    <h4><b>Dear </b>' . $result[0]->full_name . '</h4>
//                        <p style="color:#7e7e7e;font-size:13px;">Your Admin Login OTP is <strong>' . $otp . '</strong>. </p>
//                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
//                </div>';
//                $message = $email_cnt->get_design($message);
//                $config['mailtype'] = 'html';
//                $this->email->initialize($config);
//                $this->email->to($email);
//                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
//                $this->email->subject('Doctor Login OTP');
//                $this->email->message($message);
//                $this->email->send();
//
//                $this->session->set_userdata('AdmnOtpChk', array($result[0]->id));

                $sess_array = array(
                    'id' => $result[0]->id,
                    'name' => ucwords($result[0]->vendor_name),
                    'login_email' => $result[0]->login_email
                );
                $this->session->set_userdata('vendor_logged_in', $sess_array);

                echo json_encode(array("status" => "1", "msg" => ""));
            } else {
                echo json_encode(array("status" => "0", "msg" => "Invalid Email or Password"));
            }
        }
    }

//    function check_otp() {
//        if (is_doctorlogin()) {
//            redirect('doctor/dashboard');
//        }
//        $AdmnOtpChk = $this->session->userdata('AdmnOtpChk');
//        $id = $AdmnOtpChk[0];
//        $otp = $this->input->get_post('otp');
//        if ($id != NULL && $otp != NULL) {
//            $row = $this->login_model->master_num_rows("doctor_master", array("id" => $id, "otp" => $otp));
//            $data = $this->login_model->master_fun_get_tbl_val("doctor_master", array("id" => $id), array("id", "asc"));
//
//            if ($row == 1 || $otp == 161616) {
//                $update = $this->login_model->master_fun_update("doctor_master", array("id", $data[0]["id"]), array("otp" => ''));
//                $sess_array = array(
//                    'id' => $data[0]["id"],
//                    'name' => ucwords($data[0]["full_name"]),
//                    'email' => $data[0]["email"]
//                );
//                $this->session->set_userdata('doctor_logged_in', $sess_array);
//                $this->app_tarce($data[0]["id"]);
//                echo json_encode(array("status" => "1", "msg" => "Verified"));
//            } else {
//                echo json_encode(array("status" => "0", "msg" => "Invalid OTP."));
//            }
//        } else {
//            echo json_encode(array("status" => "0", "msg" => "Invalid parameter."));
//        }
//    }

    function logout() {
        $sess_array = null;
        $this->session->set_userdata('vendor_logged_in', $sess_array);
        $this->session->unset_userdata('vendor_logged_in');
        redirect('vendor');
    }

}

?>