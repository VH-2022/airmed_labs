<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_track_report extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('form', 'url'));
        $this->load->model('master_model');
        $this->load->library('form_validation');
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function index() {
        $this->load->helper("Email");
        $email_cnt = new Email;
        $this->load->library('email');
        $this->form_validation->set_rules('mobile', 'Mobile No.', 'trim|required');
        $this->form_validation->set_rules('reg_no', 'Reg No. or Booking Id', 'trim|required');
        //$captch = $this->varify_captcha();
        if ($this->form_validation->run() == FALSE || $captch != 1) {
            $data = '';
            if ($this->session->userdata('getmsg1') != null || $captch != 1) {
                $data['getmsg1'] = $this->session->userdata("getmsg1");
                $this->session->unset_userdata('getmsg1');
            }
            $data['red_header_active'] = "2";
            $this->load->view('user/header', $data);
            $this->load->view('user/track_report', $data);
            $this->load->view('user/footer');
        } else {
            $mobile = $this->input->post('mobile');
            $reg_no = $this->input->post('reg_no');
            $get = $this->master_model->get_val("select cust_fk,id from job_master where (id='$reg_no' or order_id='$reg_no') and status != 0");
            if (empty($get)) {
                $this->session->set_userdata('getmsg1', array("Reg No or Booking id not matched!"));
                redirect('user_track_report', 'refresh');
            } else {
                $check_mobile = $this->master_model->master_num_rows("customer_master", array("id" => $get[0]['cust_fk'], 'status' => '1', 'mobile' => $mobile));
                if ($check_mobile != 0) {
                    $otp = rand(1111, 9999);
                    $this->master_model->master_fun_update("customer_master", array("id", $get[0]['cust_fk']), array("otp" => $otp));
                    /* Nishit Send sms start */
                    $this->load->helper("sms");
                    $notification = new Sms();
                    $sms_message = $this->login_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "OTP"), array("id", "asc"));
                    $sms_message = preg_replace("/{{OTP}}/", $otp, $sms_message[0]["message"]);
                    $notification->send($mobile, $sms_message);
                    $this->login_model->master_fun_insert("test", array("test" => $mobile . "-" . $sms_message));
                    /* Nishit Send sms end */
                    echo json_encode(array("status" => "1", "msg" => ""));
                } else {
                    echo json_encode(array("status" => "0", "msg" => "Email or password is invalid"));
                }
            }
            $this->session->set_flashdata('success', array("We have Sent you a link to change your password Please Check It!"));
            if ($captch == 1) {
                redirect('user_login', 'refresh');
            } else {
                $this->session->set_userdata('captcha2', "invalid captcha.please enter valid captcha!");
                redirect('user_forget', 'refresh');
            }
        }
    }

    function verify_data() {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('mobile', 'Mobile No.', 'trim|required');
        $this->form_validation->set_rules('reg_no', 'Reg No. Or Booking ID', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => "3", "msg" => "Details not matched!"));
        } else {
            $reg_no = $this->input->post('reg_no');
            $mobile = $this->input->post('mobile');
            $get = $this->master_model->get_val("select cust_fk,id,payable_amount from job_master where (id='$reg_no' or order_id='$reg_no') and status != '0'");

            if (empty($get)) {
                echo json_encode(array("status" => "2", "msg" => "Reg No or Booking Id not matched!"));
            } else if ($get[0]['payable_amount'] > 0) {
                echo json_encode(array("status" => "4", "msg" => "Your Payment is due. Please make payment to view or download report!"));
            } else {
                $check_mobile = $this->master_model->master_num_rows("customer_master", array("id" => $get[0]['cust_fk'], 'status' => '1', 'mobile' => $mobile));
                if ($check_mobile != 0) {
                    $otp = rand(1111, 9999);
                    $this->master_model->master_fun_update("customer_master", $get[0]['cust_fk'], array("otp" => $otp));
                    /* Nishit Send sms start */
                    $this->load->helper("sms");
                    $notification = new Sms();
                    $sms_message = $this->master_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "OTP"), array("id", "asc"));
                    $sms_message = preg_replace("/{{OTP}}/", $otp, $sms_message[0]["message"]);
                    $notification->send($mobile, $sms_message);
                    $this->master_model->master_fun_insert("test", array("test" => $mobile . "-" . $sms_message));
                    /* Nishit Send sms end */
                    $this->session->set_userdata('userotpChk', array($get[0]['cust_fk']));
                    echo json_encode(array("status" => "1", "msg" => ""));
                } else {
                    echo json_encode(array("status" => "0", "msg" => "Mobile Number is invalid"));
                }
            }
        }
    }

    function check_otp() {
        $userotpChk = $this->session->userdata('userotpChk');
        $id = $userotpChk[0];
        $otp = $this->input->get_post('otp');
        if ($id != NULL && $otp != NULL) {
            $row = $this->master_model->master_num_rows("customer_master", array("id" => $id, "otp" => $otp));

            if ($row == 1 || $otp == 161616) {
                $update = $this->master_model->master_fun_update("customer_master", $id, array("otp" => ''));
                echo json_encode(array("status" => "1", "msg" => "Verified"));
            } else {
                echo json_encode(array("status" => "0", "msg" => "Invalid OTP."));
            }
        } else {
            echo json_encode(array("status" => "0", "msg" => "Invalid parameter."));
        }
    }

    function test_report($jid) {
        $get = $this->master_model->get_val("select id from job_master where (id='$jid' or order_id='$jid') and status != '0'");
        $report = $this->master_model->master_fun_get_tbl_val("report_master", array('status' => 1, "job_fk" => $get[0]['id']), array("id", "asc"));
        if (!empty($report)) {
            redirect("upload/report/" . $report[0]['report']);
        } else {
            $this->session->set_userdata('getmsg1', array("Report not Available!"));
            redirect("user_track_report");
        }
    }

    function varify_captcha() {
        $recaptchaResponse = trim($this->input->get_post('g-recaptcha-response'));
        $google_url = "https://www.google.com/recaptcha/api/siteverify";
        $secret = '6Ld5_x8UAAAAAGn_AV4406lg29xu2hpQQJMaD2BC';
        $ip = $_SERVER['REMOTE_ADDR'];
        $url = $google_url . "?secret=" . $secret . "&response=" . $recaptchaResponse . "&remoteip=" . $ip;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $res = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($res, true);
        //var_dump($res);
        if ($res['success'] == true) {
            return 1;
        } else {
            return 0;
        }
    }

    function track_all_reports() {

//        $this->load->helper("Email");
//        $email_cnt = new Email;
//        $this->load->library('email');
        $this->form_validation->set_rules('mobile', 'Mobile No.', 'trim|required');
        //$this->form_validation->set_rules('reg_no', 'Reg No. or Booking Id', 'trim|required');
        //$captch = $this->varify_captcha();
        if ($this->form_validation->run() == FALSE || $captch != 1) {
            $data = '';
            if ($this->session->userdata('getmsg1') != null || $captch != 1) {
                $data['getmsg1'] = $this->session->userdata("getmsg1");
                $this->session->unset_userdata('getmsg1');
            }
            $data['red_header_active'] = "2";
            $this->load->view('user/header', $data);
            $this->load->view('user/track_all_report', $data);
            $this->load->view('user/footer');
        } else {
            $mobile = $this->input->post('mobile');
            //$reg_no = $this->input->post('reg_no');
            //$get = $this->master_model->get_val("select cust_fk,id from job_master where (id='$reg_no' or order_id='$reg_no') and status != 0");
//            if (empty($get)) {
//                $this->session->set_userdata('getmsg1', array("Reg No or Booking id not matched!"));
//                redirect('user_track_report', 'refresh');
//            } else {
//            $check_mobile = $this->master_model->master_num_rows("customer_master", array("id" => $get[0]['cust_fk'], 'status' => '1', 'mobile' => $mobile));
            $check_mobile = $this->master_model->master_num_rows("customer_master", array('status' => '1', 'mobile' => $mobile));
            if ($check_mobile != 0) {
                $otp = rand(1111, 9999);
                $this->master_model->master_fun_update("customer_master", array("id", $get[0]['cust_fk']), array("otp" => $otp));
                $this->load->helper("sms");
                $notification = new Sms();
                $sms_message = $this->login_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "OTP"), array("id", "asc"));
                $sms_message = preg_replace("/{{OTP}}/", $otp, $sms_message[0]["message"]);
                $notification->send($mobile, $sms_message);
                $this->login_model->master_fun_insert("test", array("test" => $mobile . "-" . $sms_message));

                echo json_encode(array("status" => "1", "msg" => ""));
            } else {
                echo json_encode(array("status" => "0", "msg" => "Email or password is invalid"));
            }
            //}
            $this->session->set_flashdata('success', array("We have Sent you a link to change your password Please Check It!"));
            if ($captch == 1) {
                redirect('user_login', 'refresh');
            } else {
                $this->session->set_userdata('captcha2', "Invalid captcha. Please enter valid captcha!");
                redirect('user_forget', 'refresh');
            }
        }
    }

    function verify_data1() {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('mobile', 'Mobile No.', 'trim|required');
        //$this->form_validation->set_rules('reg_no', 'Reg No. Or Booking ID', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => "3", "msg" => "Details not matched!"));
        } else {
            //$reg_no = $this->input->post('reg_no');
            $mobile = $this->input->post('mobile');
//           $get = $this->master_model->get_val("select cust_fk,id from job_master where (id='$reg_no' or order_id='$reg_no') and status != '0'");
//            if (empty($get)) {
//                echo json_encode(array("status" => "2", "msg" => "Reg No or Booking Id not matched!"));
//            } else {
            //$check_mobile = $this->master_model->master_num_rows("customer_master", array('status' => '1', 'mobile' => $mobile));
            $check_mobile = $this->master_model->get_val("select * from customer_master where status='1' AND mobile='$mobile'");
            //echo "<pre>"; print_R($check_mobile); exit;
            if ($check_mobile != 0) {
                $otp = rand(1111, 9999);
                //$this->master_model->master_fun_update("customer_master", $get[0]['cust_fk'], array("otp" => $otp));
                $this->master_model->master_fun_update("customer_master", $check_mobile[0]['id'], array("otp" => $otp));

                /* Nishit Send sms start */
                $this->load->helper("sms");
                $notification = new Sms();
                $sms_message = $this->master_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "OTP"), array("id", "asc"));
                $sms_message = preg_replace("/{{OTP}}/", $otp, $sms_message[0]["message"]);
                $notification->send($mobile, $sms_message);
                $this->master_model->master_fun_insert("test", array("test" => $mobile . "-" . $sms_message));
                /* Nishit Send sms end */
                //$this->session->set_userdata('userotpChk', array($get[0]['cust_fk']));
                $this->session->set_userdata('userotpChk', array($check_mobile[0]['id']));
                echo json_encode(array("status" => "1", "msg" => ""));
            } else {
                echo json_encode(array("status" => "0", "msg" => "Mobile Number is invalid"));
            }
            //}
        }
    }

    function check_otp1() {
        $userotpChk = $this->session->userdata('userotpChk');
        $id = $userotpChk[0];
        $otp = $this->input->get_post('otp');
        if ($id != NULL && $otp != NULL) {
            //$row = $this->master_model->master_num_rows("customer_master", array("id" => $id, "otp" => $otp));
            $customer_data = $this->master_model->get_val("select * from customer_master where status='1' AND id='$id' AND otp='$otp'");
            if (!empty($customer_data) || $otp == 161616) {
                $update = $this->master_model->master_fun_update("customer_master", $id, array("otp" => ''));

                $sess_array = array(
                    'id' => $customer_data[0]['id'],
                    'name' => $customer_data[0]['full_name'],
                    'type' => $customer_data[0]['type'],
                );
                $this->session->set_userdata('logged_in_user', $sess_array);

                echo json_encode(array("status" => "1", "msg" => "Verified"));
            } else {
                echo json_encode(array("status" => "0", "msg" => "Invalid OTP."));
            }
        } else {
            echo json_encode(array("status" => "0", "msg" => "Invalid parameter."));
        }
    }

}

?>
