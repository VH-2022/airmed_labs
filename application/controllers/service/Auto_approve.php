<?php

class Auto_approve extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('email');
        $this->load->model('service_model');
        $this->load->model('user_master_model');
        $this->load->helper('security');
        $this->load->helper('string');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: X-Requested-With");
        $this->app_tarce();
    }

    function app_tarce() {
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
        $user_track_data = array("url" => $actual_link, "user_details" => $user_info, "data" => $user_post_data, "createddate" => date("Y-m-d H:i:s"), "type" => "service");
        //print_R($user_track_data);
        $app_info = $this->service_model->master_fun_insert("user_track", $user_track_data);
        //return true;
    }

    function json_data($status, $error_msg, $data = NULL) {
        if ($data == NULL) {
            $data = array();
        }
        $final = array("status" => $status, "error_msg" => $error_msg, "data" => $data);
        return str_replace("null", '" "', json_encode($final));
    }

    function changeAutoApproveTestStatus() {
        $old_data = $this->service_model->get_val("SELECT * FROM `auto_approve_pending_job` WHERE `status`='2' and approve_time LIKE '%".date("Y-m-d H:i")."%' order by id asc");
        foreach ($old_data as $aakey) {
            /* Nishit Code Start */
            $this->service_model->master_fun_update1("use_formula", array("job_fk" => $aakey["job_fk"], "test_fk" => $aakey["test_fk"]), array("test_status" => 2));
            $this->service_model->master_fun_insert("job_log", array("job_fk" => $aakey["job_fk"], "created_by" => "", "updated_by" => "", "deleted_by" => "", "job_status" => '', "message_fk" => "32", "date_time" => date("Y-m-d H:i:s")));
            //echo 'add_result/test_approve_details/' . $para_job_id . "/" . $tid; die();
            $check_is_approved = $this->service_model->master_fun_get_tbl_val("approve_job_test", array('job_fk' => $aakey["job_fk"], "test_fk" => $aakey["test_fk"], "status" => "1"), array("id", "asc"));
            //print_r($check_is_approved); die();
            if (empty($check_is_approved)) {
                $insert = $this->service_model->master_fun_insert("approve_job_test", array('job_fk' => $aakey["job_fk"], "test_fk" => $aakey["test_fk"], "approve_by" => "", "created_date" => date("Y-m-d H:i:s")));
            }
            $this->service_model->master_fun_update1("auto_approve_pending_job", array("job_fk" => $aakey["job_fk"], "test_fk" => $aakey["test_fk"],"status"=>"2"), array("status" => 1));
            /* END */
        }
        return true;
    }

}
