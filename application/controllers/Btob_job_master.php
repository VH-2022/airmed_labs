<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Btob_job_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('user_test_master_model');
        $this->load->model('btob_job_model');
        $this->load->model('test_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->model('registration_admin_model');
        $this->load->helper('string');
        $data["login_data"] = logindata();
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function sss() {
        $this->benchmark->mark('code_start');

// Some code happens here

        $this->benchmark->mark('code_end');

        echo $this->benchmark->elapsed_time('code_start', 'code_end');
    }

    function pending_list_old() {
        $this->benchmark->mark('code_start');
        if (!is_loggedin()) {
            redirect('login');
        }
        $search_data = array();
        $user = $data['user2'] = $search_data["user"] = $this->input->get('user');
        $date = $data['date2'] = $search_data["date"] = $this->input->get('date');
        $end_date = $data['end_date'] = $search_data["end_date"] = $this->input->get("end_date");
        $p_oid = $data['p_oid'] = $search_data["p_oid"] = $this->input->get('p_oid');
        $p_ref = $data['p_ref'] = $search_data["p_ref"] = $this->input->get('p_ref');
        $mobile = $data['mobile'] = $search_data["mobile"] = $this->input->get('mobile');
        $referral_by = $data['referral_by'] = $search_data["referral_by"] = $this->input->get('referral_by');
        $status = $data['statusid'] = $search_data["status"] = $this->input->get('status');
        $branch = $data['branch'] = $search_data["branch"] = $data["branch"] = $this->input->get('branch');
        $payment = $data['payment2'] = $search_data["payment"] = $data["payment"] = $this->input->get('payment');
        $test_pack = $data['test_pack'] = $search_data["test_pack"] = $this->input->get('test_package');
        $city = $data['tcity'] = $search_data["city"] = $this->input->get('city');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status='1'");
        $cntr_arry = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data["login_data"]['branch_fk'] as $key) {
                $cntr_arry[] = $key["branch_fk"];
            }
            $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status='1' and id in (" . implode(",", $cntr_arry) . ")");
        }
        $data['test_cities'] = $this->registration_admin_model->get_val("SELECT * from test_cities where status='1'");
        $test_packages = explode("_", $test_pack);
        $alpha = $test_packages[0];
        $tp_id = $test_packages[1];
        if ($alpha == 't') {
            $t_id = $tp_id;
        }
        if ($alpha == 'p') {
            $p_id = $tp_id;
        }
        $data['success'] = $this->session->flashdata("success");

        if ($user != "" || $date != "" || $end_date != '' || $p_oid != '' || $p_ref != "" || $mobile != "" || $referral_by != "" || $status != "" || $branch != "" || $payment != "" || $test_pack != '' || $city != '') {
            if ($statusid == '0') {
                $data["deleted_selected"] = 1;
            }
            if ($branch != '') {
                $cntr_arry = array();
                $cntr_arry = $branch;
            }
            $search_data['cntr_arry'] = $cntr_arry;
            $search_data['t_id'] = $t_id;
            $search_data['p_id'] = $p_id;
            $total_row = $this->btob_job_model->num_row_srch_job_list($search_data);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "job-master/pending-list?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 100;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->btob_job_model->row_srch_job_list($search_data, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        } else {
            $search_data['cntr_arry'] = $cntr_arry;
            $total_row = $this->btob_job_model->num_srch_job_list($cntr_arry);
            $config["base_url"] = base_url() . "job-master/pending-list";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 100;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;

            $res = $this->btob_job_model->srch_job_list_get_id($config["per_page"], $page, $search_data);
            $datapass = array();
            foreach ($res as $r) {
                $datapass[] = $r['id'];
            }
            $search_data['idofdata'] = $datapass;
            $data['query'] = $this->btob_job_model->srch_job_list($config["per_page"], $page, $search_data);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        }
        $cnt = 0;
        foreach ($data['query'] as $key) {
            $w_prc = 0;
            /* Count booked test price */
            $booked_tests = $this->btob_job_model->master_fun_get_tbl_val("wallet_master", array('job_fk' => $key["id"]), array("id", "asc"));
            $emergency_tests = $this->btob_job_model->master_fun_get_tbl_val("booking_info", array('id' => $key["booking_info"]), array("id", "asc"));
            $f_data = $this->btob_job_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
            $f_data1 = $this->btob_job_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
            $doctor_data = $this->btob_job_model->master_fun_get_tbl_val("doctor_master", array('id' => $key["doctor"]), array("id", "asc"));
            $data['query'][$cnt]["send_repor_sms"] = $this->btob_job_model->master_fun_get_tbl_val("send_report_sms", array('job_fk' => $key["id"], "status" => "1"), array("id", "asc"));
            $data['query'][$cnt]["send_report_mail"] = $this->btob_job_model->master_fun_get_tbl_val("send_report_mail", array('job_fk' => $key["id"], "status" => "1"), array("id", "asc"));
            $relation = "Self";
            if (!empty($f_data1)) {
                $relation = ucfirst($f_data[0]["name"] . " (" . $f_data1[0]["name"] . ")");
                $data['query'][$cnt]["rphone"] = $f_data[0]["phone"];
            }
            $data['query'][$cnt]["relation"] = $relation;
            foreach ($booked_tests as $tkey) {
                if ($tkey["debit"]) {
                    $w_prc = $w_prc + $tkey["debit"];
                }
            }
            $upload_data = $this->btob_job_model->master_fun_get_tbl_val("report_master", array('job_fk' => $key["id"]), array("id", "asc"));
            $job_test_list = $this->btob_job_model->get_val("SELECT `job_test_list_master`.*,`test_master`.`test_name` FROM `job_test_list_master` INNER JOIN `test_master` ON `job_test_list_master`.`test_fk`=`test_master`.`id` WHERE `job_test_list_master`.`job_fk`='" . $key["id"] . "'");
            $data['query'][$cnt]["job_test_list"] = $job_test_list;
            $data['query'][$cnt]["report"] = $upload_data[0]["original"];
            $data['query'][$cnt]["emergency"] = $emergency_tests[0]["emergency"];
            $data['query'][$cnt]["cut_from_wallet"] = $w_prc;
            $data['query'][$cnt]["doctor_name"] = $doctor_data[0]["full_name"];
            $data['query'][$cnt]["doctor_mobile"] = $doctor_data[0]["mobile"];
            $package_ids = $this->btob_job_model->get_job_booking_package($key["id"]);
            if (trim($package_ids) != '') {
                $data['query'][$cnt]["packagename"] = $package_ids;
            }
            $cnt++;
        }
//echo "<pre>"; print_R($data['query']); die();
        $url = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        $this->session->set_userdata("job_master_r", $url);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('pending_job_list', $data);
        $this->load->view('footer');

        $this->benchmark->mark('code_end');
        if ($_GET["debug"] == 1) {
            echo "<h1>" . $this->benchmark->elapsed_time('code_start', 'code_end') . "</h1>";
        }
    }

    /* New Job List Start */

    function pending_list() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $search_data = array();

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status='1'");
        $cntr_arry = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data["login_data"]['branch_fk'] as $key) {
                $cntr_arry[] = $key["branch_fk"];
            }
            $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status='1' and id in (" . implode(",", $cntr_arry) . ")");
            foreach ($data['branchlist'] as $branchs) {
                $cntr_arry1[] = $branchs["city"];
            }
            $data['citylist'] = $this->registration_admin_model->get_val("SELECT * from test_cities where status='1' and id in (" . implode(",", $cntr_arry1) . ")");
            foreach ($data['citylist'] as $cts) {
                $cntr_arry2[] = $cts["city_fk"];
            }
            $data['search_c'] = $cntr_arry1;
        }
        /* Check test city */
        $allow_test_city = array();
        if ($data["login_data"]["type"] == 1) {
            $u_allow_city = $this->registration_admin_model->get_val("SELECT * from user_test_city where user_fk='" . $data["login_data"]["id"] . "' and status='1'");
            $allow_city = array();
            foreach ($u_allow_city as $a_key) {
                $allow_city[] = $a_key["test_city"];
            }
        }
        /* End */
        $data['test_cities'] = $this->registration_admin_model->get_val("SELECT * from test_cities where status='1'");
        $test_packages = explode("_", $test_pack);
        $alpha = $test_packages[0];
        $tp_id = $test_packages[1];
        if ($alpha == 't') {
            $t_id = $tp_id;
        }
        if ($alpha == 'p') {
            $p_id = $tp_id;
        }
        $search_data['allow_city'] = $allow_city;
        if ($_REQUEST["debug"] == 1) {
            print_r($data['search_c']);
            $this->db->close();
            die();
        }
        $data['success'] = $this->session->flashdata("success");
        $url = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        $this->session->set_userdata("job_master_r", $url);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('btob_pending_job_list_new', $data);
        $this->load->view('footer');
    }

    function pending_list_search() {
        $this->benchmark->mark('code_start');
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->library("util");
        $util = new Util;
//        echo "<pre>"; print_r($_POST); die();
        $search_data = array();
        $user = $data['user2'] = $search_data["user"] = $this->input->get_post('user');
        $date = $data['date2'] = $search_data["date"] = $this->input->get_post('date');
        $end_date = $data['end_date'] = $search_data["end_date"] = $this->input->get_post("end_date");
        $p_oid = $data['p_oid'] = $search_data["p_oid"] = $this->input->get_post('p_oid');
        $p_ref = $data['p_ref'] = $search_data["p_ref"] = $this->input->get_post('p_ref');
        $mobile = $data['mobile'] = $search_data["mobile"] = $this->input->get_post('mobile');
        $referral_by = $data['referral_by'] = $search_data["referral_by"] = $this->input->get_post('referral_by');
        $status = $data['statusid'] = $search_data["status"] = $this->input->get_post('status');
        $branch = $data['branch'] = $search_data["branch"] = $data["branch"] = $this->input->get_post('branch');
        $payment = $data['payment2'] = $search_data["payment"] = $data["payment"] = $this->input->get_post('payment');
        $test_pack = $data['test_pack'] = $search_data["test_pack"] = $this->input->get_post('test_package');
        $city = $data['tcity'] = $search_data["city"] = $this->input->get_post('city');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cntr_arry = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data["login_data"]['branch_fk'] as $key) {
                $cntr_arry[] = $key["branch_fk"];
            }
        }
        $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status='1'");
        /* Check test city */
        $allow_test_city = array();
        if ($data["login_data"]["type"] == 1 || $data["login_data"]["type"] == 2) {
            $u_allow_city = $this->registration_admin_model->get_val("SELECT * from user_test_city where user_fk='" . $data["login_data"]["id"] . "' and status='1'");
            $allow_city = array();
            foreach ($u_allow_city as $a_key) {
                $allow_city[] = $a_key["test_city"];
            }
        }
        /* End */

        $search_data['allow_city'] = $allow_city;
        $test_packages = explode("_", $test_pack);
        $alpha = $test_packages[0];
        $tp_id = $test_packages[1];
        if ($alpha == 't') {
            $t_id = $tp_id;
        }
        if ($alpha == 'p') {
            $p_id = $tp_id;
        }

        if ($statusid == '0') {
            $data["deleted_selected"] = 1;
        }
        $data['get_user_branch'] = $this->btob_job_model->get_val("SELECT GROUP_CONCAT(branch_fk) as bid FROM `user_branch` WHERE `status`='1' AND user_fk='" . $data["login_data"]["id"] . "' GROUP BY `user_fk`");
        if (!empty($data['get_user_branch'])) {
            $cntr_arry = array();
            $cntr_arry = explode(",", $data['get_user_branch'][0]["bid"]);
        }
        if ($data["login_data"]["type"] == 1 || $data["login_data"]["type"] == 2) {
            $data['branchList'] = $this->btob_job_model->get_val("SELECT GROUP_CONCAT(id) AS bid FROM `branch_master` WHERE `status`='1'");
            $cntr_arry = explode(",", $data['branchList'][0]["bid"]);
        }
        $search_data['cntr_arry'] = $cntr_arry;
        $search_data['t_id'] = $t_id;
        $search_data['p_id'] = $p_id;

        $data['query'] = $this->btob_job_model->new_row_srch_job_list($search_data, $config["per_page"], $page);
        $cnt = 0;
        foreach ($data['query'] as $key) {
            $w_prc = 0;
            /* Count booked test price */
            $emergency_tests = $this->btob_job_model->master_fun_get_tbl_val("booking_info", array('id' => $key["booking_info"]), array("id", "asc"));
            $f_data = $this->btob_job_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
            //$doctor_data = $this->btob_job_model->master_fun_get_tbl_val("doctor_master", array('id' => $key["doctor"]), array("id", "asc"));
            //  $data['query'][$cnt]["send_repor_sms"] = $this->btob_job_model->master_fun_get_tbl_val("send_report_sms", array('job_fk' => $key["id"], "status" => "1"), array("id", "asc"));
            // $data['query'][$cnt]["send_report_mail"] = $this->btob_job_model->master_fun_get_tbl_val("send_report_mail", array('job_fk' => $key["id"], "status" => "1"), array("id", "asc"));
            /* Nishit 11-8 start */
            if ($data["login_data"]["type"] != 1 && $data["login_data"]["type"] != 2) {
                //   $permission = $this->btob_job_model->get_val("SELECT is_print FROM `report_print_permission` WHERE `status`='1' AND `type`='" . $data["login_data"]["type"] . "' AND branch='" . $key["branch_fk"] . "'");
                $data['query'][$cnt]["is_print"] = $permission[0]["is_print"];
            } else {
                $data['query'][$cnt]["is_print"] = 1;
            }
            /* End */
            $b2b_job_detais = $this->btob_job_model->master_fun_get_tbl_val("sample_job_master", array('barcode_fk' => $key["b2b_id"]), array("id", "asc"));
            $data['query'][$cnt]["b2b_job_details"] = $b2b_job_detais;
            $relation = "Self";
            if (!empty($f_data1)) {
                $relation = ucfirst($f_data[0]["name"] . " (" . $f_data1[0]["name"] . ")");
                $data['query'][$cnt]["rphone"] = $f_data[0]["phone"];
            }
            $data['query'][$cnt]["relation"] = $relation;
            foreach ($booked_tests as $tkey) {
                if ($tkey["debit"]) {
                    $w_prc = $w_prc + $tkey["debit"];
                }
            }

            // pinkesh
            if (empty($f_data)) {
                $age = $util->get_age($key["dob"]);
                if ($key['gender'] == 'male') {
                    $data['query'][$cnt]["gender"] = 'M';
                } else if ($key['gender'] == 'female') {
                    $data['query'][$cnt]["gender"] = 'F';
                }
                if ($age[0] != 0) {
                    $data['query'][$cnt]["age"] = $age[0];
                    $data['query'][$cnt]["age_type"] = 'Y';
                }
                if ($age[0] == 0 && $age[1] != 0) {
                    $data['query'][$cnt]["age"] = $age[1];
                    $data['query'][$cnt]["age_type"] = 'M';
                }
                if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
                    $data['query'][$cnt]["age"] = $age[2];
                    $data['query'][$cnt]["age_type"] = 'D';
                }
                if ($age[0] == 0 && $age[1] == 0 && $age[2] == 0) {
                    $data['query'][$cnt]["age"] = '0';
                    $data['query'][$cnt]["age_type"] = 'D';
                }
            } else {
                $age = $util->get_age($f_data[0]["dob"]);
                if ($f_data[0]['gender'] == 'male') {
                    $data['query'][$cnt]["gender"] = 'M';
                } else if ($f_data[0]['gender'] == 'female') {
                    $data['query'][$cnt]["gender"] = 'F';
                }
                if ($age[0] != 0) {
                    $data['query'][$cnt]["age"] = $age[0];
                    $data['query'][$cnt]["age_type"] = 'Y';
                }
                if ($age[0] == 0 && $age[1] != 0) {
                    $data['query'][$cnt]["age"] = $age[1];
                    $data['query'][$cnt]["age_type"] = 'M';
                }
                if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
                    $data['query'][$cnt]["age"] = $age[2];
                    $data['query'][$cnt]["age_type"] = 'D';
                }

                if ($age[0] == 0 && $age[1] == 0 && $age[2] == 0) {
                    $data['query'][$cnt]["age"] = '0';
                    $data['query'][$cnt]["age_type"] = 'D';
                }
            }
            //print_r($age); die();
            // pinkesh code end
            // $upload_data = $this->btob_job_model->master_fun_get_tbl_val("report_master", array('job_fk' => $key["id"]), array("id", "asc"));
            // $prnt_cnt = $this->btob_job_model->get_val("SELECT id FROM `booked_job_test` WHERE job_fk='" . $key["id"] . "' AND status='1' AND `panel_fk` IS NOT NULL");
            //    $data['query'][$cnt]["panel_test_count"] = count($prnt_cnt);
            //     $data['query'][$cnt]["print_cnt"] = $this->btob_job_model->get_val("SELECT COUNT(id) as cnt FROM `print_report_count` WHERE `status`='1' AND job_fk='" . $key["id"] . "'");
            $job_test_list = $this->btob_job_model->get_val("SELECT `job_test_list_master`.*,`test_master`.`test_name` FROM `job_test_list_master` INNER JOIN `test_master` ON `job_test_list_master`.`test_fk`=`test_master`.`id` WHERE `job_test_list_master`.`job_fk`='" . $key["id"] . "'");
            /* Check sub test start */
            $job_tst_lst = array();
            foreach ($job_test_list as $st_key) {
                //echo $st_key['test_fk'];
                $job_sub_test_list = $this->btob_job_model->get_val("SELECT `sub_test_master`.test_fk,`sub_test_master`.`sub_test`,test_master.`test_name` FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`sub_test`=`test_master`.`id` WHERE `sub_test_master`.`status`='1' AND `test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $st_key['test_fk'] . "'");
                $st_key["sub_test"] = $job_sub_test_list;
                $job_tst_lst[] = $st_key;
            }
            //die("OK");
            /* END */
            $data['query'][$cnt]["job_test_list"] = $job_tst_lst;
            $data['query'][$cnt]["report"] = $upload_data[0]["original"];
            $data['query'][$cnt]["emergency"] = $emergency_tests[0]["emergency"];
            $data['query'][$cnt]["cut_from_wallet"] = $w_prc;
            $data['query'][$cnt]["doctor_name"] = $doctor_data[0]["full_name"];
            $data['query'][$cnt]["doctor_mobile"] = $doctor_data[0]["mobile"];
            $package_ids = $this->btob_job_model->get_job_booking_package($key["id"]);
            $data['query'][$cnt]["package"] = $package_ids;
            if (!empty($package_ids)) {
                $data['query'][$cnt]["packagename"] = "";
            }
            $cnt++;
        }
        //echo "<pre>";print_r($data['query']);die(); 
        $url = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        $this->session->set_userdata("job_master_r", $url);
        $this->load->view('btob_pending_job_list_search', $data);
    }

    function changing_status_job() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $status = $this->input->post('status');
        $job_id = $this->input->post('jobid');
        $customer_last_job_id = $this->btob_job_model->master_fun_get_tbl_val("job_master", array('id' => $job_id), array("id", "asc"));
        if ($status == 2) {
            $this->job_mark_completed($job_id);
        }
        if ($status == 7) {
            $this->sample_collected_calculation($job_id);
        }
        $status_update = $this->btob_job_model->master_fun_update("job_master", array("id", $job_id), array("status" => $status));
        $this->btob_job_model->master_fun_insert("job_log", array("job_fk" => $job_id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => $customer_last_job_id[0]["status"] . "-" . $status, "message_fk" => "3", "date_time" => date("Y-m-d H:i:s")));
        if ($status_update) {
            echo 1;
        }
    }

    function export_csv() {

        $search_data = array();
        $user = $data['user2'] = $search_data["user"] = $this->input->get('user');
        $date = $data['date2'] = $search_data["date"] = $this->input->get('date');
        $end_date = $data['end_date'] = $search_data["end_date"] = $this->input->get("end_date");
        $p_oid = $data['p_oid'] = $search_data["p_oid"] = $this->input->get('p_oid');
        $p_ref = $data['p_ref'] = $search_data["p_ref"] = $this->input->get('p_ref');
        $mobile = $data['mobile'] = $search_data["mobile"] = $this->input->get('mobile');
        $referral_by = $data['referral_by'] = $search_data["referral_by"] = $this->input->get('referral_by');
        $status = $data['statusid'] = $search_data["status"] = $this->input->get('status');
        $branch = $data['branch'] = $search_data["branch"] = $data["branch"] = $this->input->get('branch');
        $payment = $data['payment2'] = $search_data["payment"] = $data["payment"] = $this->input->get('payment');
        $test_pack = $data['test_pack'] = $search_data["test_pack"] = $this->input->get('test_package');
        $city = $data['tcity'] = $search_data["city"] = $this->input->get('city');

        $data["login_data"] = logindata();
        /* $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
          $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status='1'");
          $cntr_arry = array();
          if (!empty($data["login_data"]['branch_fk'])) {
          foreach ($data["login_data"]['branch_fk'] as $key) {
          $cntr_arry[] = $key["branch_fk"];
          }
          $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status='1' and id in (" . implode(",", $cntr_arry) . ")");
          }
          $data['test_cities'] = $this->registration_admin_model->get_val("SELECT * from test_cities where status='1'"); */
        $test_packages = explode("_", $test_pack);
        $alpha = $test_packages[0];
        $tp_id = $test_packages[1];
        if ($alpha == 't') {
            $t_id = $tp_id;
        }
        if ($alpha == 'p') {
            $p_id = $tp_id;
        }
        if ($branch != '') {
            $cntr_arry = array();
            $cntr_arry = $branch;
        }
        $search_data['cntr_arry'] = $cntr_arry;
        $search_data['t_id'] = $t_id;
        $search_data['p_id'] = $p_id;

        $result = $this->btob_job_model->csv_job_list($search_data);
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"All_Jobs_Report-" . date('d-M-Y') . ".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array(
            "No.",
            "Reg No.",
            "Order Id",
            "Client Name",
            "Test City",
            "Branch",
            "Date",
            "Patient Name",
            "Mobile No.",
            "Doctor",
            "Test/Package Name",
            "Job Status",
            "Remark",
            "Added By",
            "Total Price",
            "Discount(RS.)",
            "Due Amount", "CASH", "Creditor Money"));
        $cnt = 1;
        foreach ($result as $key) {
            if ($key['status'] == 1) {
                $j_status = "Waiting For Approval";
            }
            if ($key['status'] == 6) {
                $j_status = "Approved";
            }
            if ($key['status'] == 7) {
                $j_status = "Sample Collected";
            }
            if ($key['status'] == 8) {
                $j_status = "Processing";
            }
            if ($key['status'] == 2) {
                $j_status = "Completed";
            }
            $sample_collected = 'No';
            if ($key["sample_collection"] == 1) {
                $sample_collected = 'Yes';
            }
            $addr = '';
            if (!empty($key["address"])) {
                $addr = $key["address"];
            } else {
                $addr = $key["address1"];
            }
            if (!$key["payable_amount"]) {
                $key["payable_amount"] = 0;
            }
            /* $b2b_job_detais = $this->btob_job_model->master_fun_get_tbl_val("sample_job_master", array('barcode_fk' => $key["b2b_id"]), array("id", "asc")); */

            $b2b_job_detais = $this->btob_job_model->get_val("SELECT j.doctor,j.id as sjobid,j.price,j.discount,j.payable_amount,c.`name` FROM `logistic_log` l INNER JOIN `collect_from` c ON c.`id`=l.`collect_from` INNER JOIN sample_job_master j ON j.`barcode_fk`=l.`id`  WHERE l.`status`='1' AND l.id='" . $key["b2b_id"] . "' ");

            /* Nishit 18-08-2017 START */
            $payment_mode = array();
            $discount = 0;
            if ($b2b_job_detais[0]["discount"] > 0) {
                $discount = round($b2b_job_detais[0]["price"] * $b2b_job_detais[0]["discount"] / 100);
            }
            $added_by = "Online";
            if (!empty($key["phlebo_added_by"])) {
                $added_by = $key["phlebo_added_by"] . " (Phlebo)";
            } else if (!empty($key["added_by"])) {
                $added_by = $key["added_by"];
            }
            /* $collection_type = $this->btob_job_model->get_val("SELECT GROUP_CONCAT(`payment_type`) AS payment_type FROM `job_master_receiv_amount` WHERE `status`='1' AND job_fk='" . $key["id"] . "' GROUP BY payment_type ORDER BY payment_type ASC");
              if (count($collection_type) > 0) {
              if (strtoupper($collection_type[0]["payment_type"]) == "CASH") {
              $payment_mode[] = "CASH";
              }
              if (strtoupper($collection_type[0]["payment_type"]) != "CASH" && count($collection_type) > 0) {
              $payment_mode[] = "ONLINE";
              }
              if (strtoupper($collection_type[0]["payment_type"]) == "CASH" && count($collection_type) > 1) {
              $payment_mode[] = "ONLINE";
              }
              } */

            $cash = 0.0;
            $credoramount = 0;
            $collection_type = $this->btob_job_model->get_val("SELECT (`payment_type`) AS payment_type,SUM(sample_job_master_receiv_amount.amount) as amount FROM `sample_job_master_receiv_amount` WHERE `status`='1' AND job_fk='" . $b2b_job_detais[0]["sjobid"] . "' GROUP BY payment_type ORDER BY payment_type ASC");

            $creditor_remark = array();

            if (count($collection_type) > 0) {
                foreach ($collection_type as $ct) {
                    if (strtoupper($ct["payment_type"]) == "CREDITORS") {
                        // $creditor_remark[] = $ct["remark"];
                        //$cheque+=$ct["amount"];
                        $credoramount += $ct["amount"];
                    } else {
                        $cash += $ct["amount"];
                    }
                }
            }

            $dabitt_from_wallet = $this->btob_job_model->get_val("SELECT IF(SUM(`debit`)>0,SUM(`debit`),0) AS dabit FROM `wallet_master` WHERE `job_fk`='" . $key["id"] . "' AND `status`='1'");
            $collected_cash_card = round($key["price"] - $key["payable_amount"] - $discount - $dabitt_from_wallet[0]["dabit"]);
            if ($dabitt_from_wallet[0]["dabit"] > 0) {
                $payment_mode[] = "WALLET";
            }
            if (strtoupper($key["payment_type"]) == "PAYUMONEY") {
                $payment_mode[] = "ONLINE";
            }

            /* END */
            if ($key["family_member_fk"] == 0) {
                $patient_name = $key["full_name"];
            } else {
                $patient_name = $key["family_name"];
            }
            fputcsv($handle, array(
                $cnt,
                $key["id"],
                $key["order_id"],
                $b2b_job_detais[0]["name"],
                $key["test_city_name"],
                $key["branch_name"],
                $key["date"],
                $patient_name,
                $key["mobile"],
                $b2b_job_detais[0]["doctor"],
                $key["testname"] . " " . $key["packagename"],
                $j_status,
                $key["note"],
                $added_by,
                $b2b_job_detais[0]["price"],
                $discount,
                $b2b_job_detais[0]["payable_amount"], $cash, $credoramount
            ));
            $cnt++;
        }
        fclose($handle);
        exit;
    }

    function export_doctor_csv() {

        $search_data = array();
        $user = $data['user2'] = $search_data["user"] = $this->input->get('user');
        $date = $data['date2'] = $search_data["date"] = $this->input->get('date');
        $end_date = $data['end_date'] = $search_data["end_date"] = $this->input->get("end_date");
        $p_oid = $data['p_oid'] = $search_data["p_oid"] = $this->input->get('p_oid');
        $p_ref = $data['p_ref'] = $search_data["p_ref"] = $this->input->get('p_ref');
        $mobile = $data['mobile'] = $search_data["mobile"] = $this->input->get('mobile');
        $referral_by = $data['referral_by'] = $search_data["referral_by"] = $this->input->get('referral_by');
        $status = $data['statusid'] = $search_data["status"] = $this->input->get('status');
        $branch = $data['branch'] = $search_data["branch"] = $data["branch"] = $this->input->get('branch');
        $payment = $data['payment2'] = $search_data["payment"] = $data["payment"] = $this->input->get('payment');
        $test_pack = $data['test_pack'] = $search_data["test_pack"] = $this->input->get('test_package');
        $city = $data['tcity'] = $search_data["city"] = $this->input->get('city');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status='1'");
        $cntr_arry = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data["login_data"]['branch_fk'] as $key) {
                $cntr_arry[] = $key["branch_fk"];
            }
            $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status='1' and id in (" . implode(",", $cntr_arry) . ")");
        }
        $data['test_cities'] = $this->registration_admin_model->get_val("SELECT * from test_cities where status='1'");
        $test_packages = explode("_", $test_pack);
        $alpha = $test_packages[0];
        $tp_id = $test_packages[1];
        if ($alpha == 't') {
            $t_id = $tp_id;
        }
        if ($alpha == 'p') {
            $p_id = $tp_id;
        }
        if ($branch != '') {
            $cntr_arry = array();
            $cntr_arry = $branch;
        }
        $search_data['cntr_arry'] = $cntr_arry;
        $search_data['t_id'] = $t_id;
        $search_data['p_id'] = $p_id;

        $result = $this->btob_job_model->csv_job_list2($search_data);
        //print_r($result); die();
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"All_Jobs_Report-" . date('d-M-Y') . ".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No.", "Reg No.", "Order Id", "Test City", "Branch", "Date", "Patient Name", "Mobile No.", "Doctor", "Test/Package Name", "Payable Amount", "Debited From Wallet", "Price", "Discount", "Job Status", "Payment Type", "Blood Sample Collected", "Portal", "Remark"));
        $cnt = 1;
        foreach ($result as $key) {
            if ($key['status'] == 1) {
                $j_status = "Waiting For Approval";
            }
            if ($key['status'] == 6) {
                $j_status = "Approved";
            }
            if ($key['status'] == 7) {
                $j_status = "Sample Collected";
            }
            if ($key['status'] == 8) {
                $j_status = "Processing";
            }
            if ($key['status'] == 2) {
                $j_status = "Completed";
            }
            $sample_collected = 'No';
            if ($key["sample_collection"] == 1) {
                $sample_collected = 'Yes';
            }
            $addr = '';
            if (!empty($key["address"])) {
                $addr = $key["address"];
            } else {
                $addr = $key["address1"];
            }
            if (!$key["payable_amount"]) {
                $key["payable_amount"] = 0;
            }
            if ($key["family_member_fk"] == 0) {
                $patient_name = $key["full_name"];
            } else {
                $patient_name = $key["family_name"];
            }
            fputcsv($handle, array($cnt, $key["id"], $key["order_id"], $key["test_city_name"], $key["branch_name"], $key["date"], $patient_name, $key["mobile"], $key["doctor_name"] . "-" . $key["doctor_mobile"], $key["testname"] . " " . $key["packagename"], $key["payable_amount"], $key["cut_from_wallet"], $key["price"], $key["discount"], $j_status, $key["payment_type"], $sample_collected, $key["portal"], $key["note"]));
            $cnt++;
        }
        fclose($handle);
        exit;
    }

    function download_report($name) {
        $this->load->helper('download');
        $data = file_get_contents(base_url() . "/upload/" . $name); // Read the file's contents

        force_download($name, $data);
    }

    function mark_complete($cid) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();

        $update = $this->btob_job_model->master_fun_update("job_master", array("id", $cid), array("status" => "2"));
        //$update = $this->btob_job_model->master_fun_update("job_master", array("id", $cid), array("status" => "2"));

        $job = $this->btob_job_model->get_val("SELECT * FROM  `job_master`  WHERE   id=$cid");
        $report = $this->btob_job_model->get_val("SELECT * FROM  `report_master`  WHERE STATUS=1 AND job_fk=$cid");
        $update = $this->btob_job_model->master_fun_update('b2b_jobspdf', array("job_fk", $job[0]["b2b_id"]), array("status" => '0'));
        copy(FCPATH . "/upload/report/" . $report[0]["report"], FCPATH . "/upload/B2breport/" . $report[0]["report"]);
        copy(FCPATH . "/upload/report/" . $report[0]["without_laterpad"], FCPATH . "/upload/B2breport/" . $report[0]["without_laterpad"]);
        $file_upload = array("job_fk" => $job[0]["b2b_id"], "report" => $report[0]["report"], "original" => $report[0]["report"], "description" => "", "type" => "c");
        $b2f2 = $this->btob_job_model->master_fun_insert("b2b_jobspdf", $file_upload);

        $file_upload = array("job_fk" => $job[0]["b2b_id"], "report" => $report[0]["without_laterpad"], "original" => $report[0]["without_laterpad"], "description" => "", "type" => "c");
        $b2f2 = $this->btob_job_model->master_fun_insert("b2b_jobspdf", $file_upload);
        /* Nishit Auto approve start */
        $update = $this->btob_job_model->master_fun_update('b2b_jobspdf', array("job_fk", $job[0]["b2b_id"]), array("approve" => '1'));

        if ($update) {
            $this->btob_job_model->master_fun_update("logistic_log", array("id", $job[0]["b2b_id"]), array("jobsstatus" => '1'));
            $this->btob_job_model->master_fun_insert("job_log", array("job_fk" => $cid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "31", "date_time" => date("Y-m-d H:i:s")));
        }
        /* END */
        $this->session->set_flashdata("success", array("Job successfully mark as completed."));
        redirect("job-master/job-details/" . $cid, "refresh");
    }

    function sample_add() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $adminid = $data["login_data"]["id"];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('abc', 'abc', 'trim|required');
        $this->form_validation->set_rules('lab_id', 'lab_id', 'trim|required');
        $this->form_validation->set_rules('customer_name', 'customer_name', 'trim|required');
        $this->form_validation->set_rules('destination', 'destination', 'trim|required');
        $this->form_validation->set_rules('test[]', 'test', 'trim|required');
        $this->form_validation->set_rules('payable', 'payable', 'trim|required');
        $this->form_validation->set_rules('total_amount', 'total_amount', 'trim|required');

        if ($this->form_validation->run() != FALSE) {
            $collection_charge = $this->input->post("collection_charge");
            $barcode = $this->input->post("barcode");
            $lab_id = $this->input->post("lab_id");
            $destination = $this->input->post("destination");
            $logistic_id = $this->input->post("logistic_id");
            $customer_name = $this->input->post("customer_name");
            $customer_mobile = $this->input->post("customer_mobile");
            $customer_email = $this->input->post("customer_email");
            if ($customer_email == '') {
                $customer_email = 'noreply@airmedlabs.com';
            }
            $customer_gender = $this->input->post("customer_gender");
            $dob = $this->input->post("dob");
            $address = $this->input->post("address");
            $note = $this->input->post("note");
            $discount = $this->input->post("discount");
            $payable = $this->input->post("payable");
            $test = $this->input->post("test");
            $referby = $this->input->post("referby");
            $total_amount = $this->input->post("total_amount");
            $received_amount = $this->input->post("received_amount");
            $order_id = $this->get_job_id();
            $date = date('Y-m-d H:i:s');
            $files = $_FILES;
            $this->load->library('upload');
            $config['allowed_types'] = 'jpg|gif|png|jpeg';
            if ($files['upload_pic']['name'] != "") {
                $config['upload_path'] = './upload/barcode/';
                $config['file_name'] = time() . $files['upload_pic']['name'];
                $this->upload->initialize($config);
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }
                if (!$this->upload->do_upload('upload_pic')) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    $this->session->set_flashdata("error", $error);
                    redirect("b2b/logistic/sample_add", "refresh");
                } else {
                    $doc_data = $this->upload->data();
                    $photo = $doc_data['file_name'];
                }
            }
            $c_data = array(
                "phlebo_fk" => $logistic_id,
                "barcode" => $barcode,
                "collect_from" => $lab_id,
                "status" => '1',
                "pic" => $photo,
                "created_by" => $adminid,
                "createddate" => date('Y-m-d H:i:s'),
                "scan_date" => date('Y-m-d H:i:s')
            );
            $barcd = $this->btob_job_model->master_fun_insert("logistic_log", $c_data);
            $data = array(
                "barcode_fk" => $barcd,
                "order_id" => $order_id,
                "customer_name" => $customer_name,
                "customer_mobile" => $customer_mobile,
                "customer_email" => $customer_email,
                "customer_gender" => $customer_gender,
                "customer_dob" => $dob,
                "old_price" => $payable,
                "customer_address" => $address,
                "doctor" => $referby,
                "price" => $total_amount,
                "status" => '1',
                "discount" => $discount,
                "payable_amount" => $total_amount - $received_amount,
                "added_by" => $adminid,
                "note" => $note,
                "date" => $date,
                "collection_charge" => $collection_charge
            );

            $insert = $this->btob_job_model->master_fun_insert("sample_job_master", $data);

            $labdetils = $this->btob_job_model->fetchdatarow('test_discount,city', 'collect_from', array("id" => $lab_id, "status" => 1));
            $city = $labdetils->city;
            if ($received_amount > 0) {
                $this->btob_job_model->master_fun_insert("sample_job_master_receiv_amount", array("job_fk" => $insert, "added_by" => $adminid, "amount" => $received_amount, "createddate" => date("Y-m-d H:i:s")));
                //$this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $added_by, "deleted_by" => "", "job_status" => '', "message_fk" => "22", "date_time" => date("Y-m-d H:i:s")));
            }
            $testtotal = array();

            foreach ($test as $key) {
                $tn = explode("-", $key);

                if ($tn[0] == 't') {
                    $result = $this->btob_job_model->get_val("select p.price,(SELECT price_special FROM b2b_testspecial_price s  WHERE  s.test_fk=t.id AND s.status='1' and s.typetest='1' AND s.lab_id='$lab_id') AS specialprice,(SELECT price_special FROM b2b_testmrp_price m  WHERE  m.test_fk=t.id AND m.status='1' AND m.typetest='1' AND m.lab_id='$lab_id') AS mrpprice from test_master t inner JOIN test_master_city_price p ON t.id = p.test_fk  where t.status='1' AND p.city_fk='$city' and t.id='" . $tn[1] . "' GROUP BY t.id");

                    $mrp = round($result[0]['price']);
                    if ($result[0]['mrpprice'] != "") {
                        $mrp = $result[0]['mrpprice'];
                    }

                    if ($result[0]['specialprice'] != "") {

                        $price1 = $result[0]['specialprice'];
                    } else {

                        $discount1 = $labdetils->test_discount;
                        $discountprice = ($mrp * $discount1 / 100);
                        $price1 = $mrp - $discountprice;
                    }
                    $this->btob_job_model->master_fun_insert("sample_job_test", array('job_fk' => $insert, "test_fk" => $tn[1], "price" => round($price1)));
                    $testtotal[] = round($price1);
                }
                if ($tn[0] == 'p') {



                    $result = $this->btob_job_model->get_val("select p.d_price as price,(SELECT price_special FROM b2b_testspecial_price s  WHERE  s.test_fk=t.id AND s.status='1' and s.typetest='2' AND s.lab_id='$lab_id') AS specialprice,(SELECT price_special FROM b2b_testmrp_price m  WHERE  m.test_fk=t.id AND m.status='1' AND m.typetest='2' AND m.lab_id='$lab_id') AS mrpprice from package_master t inner JOIN package_master_city_price p ON t.id = p.package_fk  where t.status='1' AND p.city_fk='$city' and t.id='" . $tn[1] . "' GROUP BY t.id");

                    $mrp = round($result[0]['price']);
                    if ($result[0]['mrpprice'] != "") {
                        $mrp = $result[0]['mrpprice'];
                    }
                    if ($result[0]['specialprice'] != "") {
                        $price1 = $result[0]['specialprice'];
                    } else {
                        $discount1 = $labdetils->test_discount;
                        $discountprice = ($mrp * $discount1 / 100);
                        $price1 = $mrp - $discountprice;
                    }
                    $this->btob_job_model->master_fun_insert("sample_job_test", array('job_fk' => $insert, "testtype" => '2', "test_fk" => $tn[1], "price" => round($price1)));
                    $testtotal[] = round($price1);
                }
            }
            $ftotalprice = array_sum($testtotal);
            $ftotalprice1 = $ftotalprice - $received_amount + $collection_charge;
            $this->btob_job_model->master_fun_update("sample_job_master", array("id", $insert), array("price" => $ftotalprice + $collection_charge, "payable_amount" => $ftotalprice1));

            $crditdetis = $this->btob_job_model->creditget_last($lab_id);
            if ($crditdetis != "") {
                $total = ($crditdetis->total - $payable);
            } else {
                $total = (0 - $payable);
            }
            $this->btob_job_model->master_fun_insert("sample_credit", array("lab_fk" => $lab_id, "job_fk" => $insert, "debit" => $payable, "total" => $total, "created_date" => date("Y-m-d H:i:s")));

            /* assign lab */

            $desti_lab = $destination;
            $job_fk = $insert;

            $get_b2c_lab1 = $this->btob_job_model->master_fun_get_tbl_val("admin_master", array("id" => $desti_lab), array("id", "asc"));
            /* Nishit add job in b2c */
            if ($get_b2c_lab1[0]["assign_branch"] > 0) {
                /* $logistic = $this->btob_job_model->master_fun_get_tbl_val("logistic_log", array("id" => $job_fk), array("id", "asc")); */
                $test_city = $this->btob_job_model->master_fun_get_tbl_val("branch_master", array("id" => $get_b2c_lab1[0]["assign_branch"]), array("id", "asc"));
                /* $this->load->model("service_model"); */
                /* $get_b2c_lab = $this->btob_job_model->master_fun_get_tbl_val("sample_job_master", array("barcode_fk" => $job_fk), array("id", "asc")); */
                // print_r($get_b2c_lab); die();

                $name = $customer_name;
                $phone = $customer_mobile;
                $email = $customer_email;
                if ($email == '') {
                    $email = 'noreply@airmedlabs.com';
                }
                $gender = $customer_gender;
                $dob = $dob;
                $test_city = $test_city[0]["city"];
                $address = $address;
                $discount = $discount;



                $referral_by = 0;
                $source = 0;
                $pid = 0;
                $relation_fk = 0;
                $applyCollectionCharge = 0;
                $noify_cust = 0;

                $advance_collection = 0;
                //print_r($get_b2c_lab1); die();
                $branch = $get_b2c_lab1[0]["assign_branch"];
                if ($branch == '') {
                    $branch = 1;
                }

                $order_id = $this->get_job_id($test_city);

                if ($name == "") {
                    $name = "AM-" . $job_fk;
                }
                $date = date('Y-m-d H:i:s');
                $result1 = $this->btob_job_model->get_val("SELECT * FROM `customer_master` WHERE `status`='1' AND active='1' AND `email`='" . $email . "' ORDER BY id ASC");
                if (empty($result1) || $email = "noreply@airmedlabs.com") {
                    $password = rand(11111111, 9999999);
                    $c_data = array(
                        "full_name" => $name,
                        "gender" => $gender,
                        "email" => $email,
                        "mobile" => $phone,
                        "address" => $address,
                        "password" => $password,
                        "dob" => $dob,
                        "created_date" => date("Y-m-d H:i:s")
                    );
                    $customer = $this->btob_job_model->master_fun_insert("customer_master", $c_data);
                } else {
                    $customer = $result1[0]["id"];
                }

                $price = 0;
                $test_package_name = array();
                $price = 0;

                $test = $this->btob_job_model->get_val("SELECT sample_job_test.`test_fk`,sample_job_test.price,test_master.test_name FROM  `sample_job_test`   LEFT JOIN  test_master ON test_master.id = sample_job_test.test_fk WHERE sample_job_test.job_fk = '" . $job_fk . "' AND sample_job_test.status='1' and sample_job_test.testtype='1'");

                $packtest = $this->btob_job_model->get_val("SELECT sample_job_test.test_fk,sample_job_test.price,package_master.title as test_name FROM  `sample_job_test`   LEFT JOIN  package_master ON package_master.id = sample_job_test.`test_fk` WHERE sample_job_test.job_fk = '" . $job_fk . "' AND sample_job_test.status='1' and sample_job_test.testtype='2'");

                foreach ($test as $key) {
                    $price += $key["price"];
                    $test_package_name[] = $key["test_name"];
                }
                foreach ($packtest as $key1) {
                    $price += $key1["price"];
                    $test_package_name[] = $key1["test_name"];
                }
                /* Nishit book phlebo start */
                /*  $test_for = $this->input->get_post("test_for"); */
                $testforself = "self";
                $family_mem_id = 0;
                $booking_fk = $this->btob_job_model->master_fun_insert("booking_info", array("user_fk" => $customer, "type" => $testforself, "family_member_fk" => $family_mem_id, "address" => $address, "emergency" => 0, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
                /* Nishit book phlebo end */
                $data = array(
                    "order_id" => $order_id,
                    "cust_fk" => $customer,
                    "doctor" => $referral_by,
                    "other_reference" => $source,
                    "date" => $date,
                    "price" => $price,
                    "status" => '8',
                    "payment_type" => "Cash On Delivery",
                    "discount" => $discount,
                    "payable_amount" => $price - $advance_collection,
                    "address" => $address,
                    "branch_fk" => $branch,
                    "test_city" => $test_city,
                    "note" => '',
                    "added_by" => $adminid,
                    "booking_info" => $booking_fk,
                    "notify_cust" => $noify_cust,
                    "portal" => "web",
                    "collection_charge" => $collecion_charge,
                    "date" => date("Y-m-d H:i:s"),
                    "barcode" => $barcd,
                    "model_type" => 2,
                    "b2b_id" => $barcd
                );
                $insert = $this->btob_job_model->master_fun_insert("job_master", $data);

                $this->btob_job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => $adminid, "message_fk" => "1", "date_time" => date("Y-m-d H:i:s")));
                $this->btob_job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => $adminid, "updated_by" => "", "deleted_by" => "", "job_status" => '6-7', "message_fk" => "3", "date_time" => date("Y-m-d H:i:s")));


                /* foreach ($test as $key) {
                  $this->service_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $key));
                  } */
//            $this->service_model->master_fun_insert("job_master_receiv_amount", array("job_fk" => $insert, "phlebo_fk" => $pid, "amount" => $advance_collection, "createddate" => date("Y-m-d H:i:s"), "payment_type" => "CASH"));
                foreach ($test as $key) {
                    $this->btob_job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $key["test_fk"], "is_panel" => "0"));
                    $this->btob_job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "t-" . $key["test_fk"], "price" => $key["price"]));
                }
                foreach ($packtest as $key1) {

                    $this->btob_job_model->master_fun_insert("book_package_master", array('job_fk' => $insert, "date" => date('Y-m-d H:i:s'), "type" => '2', "package_fk" => $key1["test_fk"]));
                    $this->btob_job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "p-" . $key1["test_fk"], "price" => $key1["price"]));
                }
            }
            /* END */
            $data = array(
                "lab_fk" => $desti_lab,
                "job_fk" => $barcd,
                "status" => "1",
                "createddate" => date("Y-m-d H:i:s")
            );

            $insert = $this->btob_job_model->master_fun_insert("sample_destination_lab", $data);
            $this->btob_job_model->master_fun_update("logistic_log", array("id", $barcd), array("jobsstatus" => '2'));

            $this->session->set_flashdata("success", array("b2b job successfully Booked."));
            redirect("Btob_job_master/pending_list");
        } else {
            $logintype = $data["login_data"]['type'];
            if ($logintype == 5 || $logintype == 6 || $logintype == 7) {

                /* $loginid=$data["login_data"]['id'];

                  $gewuserbranch=$this->btob_job_model->userall_branch($loginid);
                  $perbranch=$gewuserbranch->branch;
                  if($perbranch != ""){ $perbranch=""; }else{ $perbranch="0"; } */

                $data['desti_lab'] = $this->btob_job_model->get_val("SELECT `id`, `name` FROM `admin_master` WHERE `status` = '1' AND `type` = '4' AND assign_branch IN (SELECT user_branch.`branch_fk` FROM `user_branch` WHERE user_branch.`user_fk`='" . $data["login_data"]['id'] . "' AND user_branch.`status`=1) ORDER BY `name` ASC");

                $data['lab_list'] = $this->btob_job_model->get_val("SELECT c.`id`,c.`name` FROM b2b_labgroup g LEFT JOIN collect_from c ON c.id=g.labid AND c.`status`='1' WHERE g.status='1' 
AND g.branch_fk IN (SELECT user_branch.`branch_fk` FROM `user_branch` WHERE user_branch.`user_fk`=" . $data["login_data"]['id'] . " AND user_branch.`status`=1) ORDER BY c.`name` ASC");
            } else {

                /* $perbranch=""; */
                $data["desti_lab"] = $this->btob_job_model->master_fun_get_tbl_val("admin_master", array("type" => "4", 'status' => 1), array("id", "asc"));
                $data['lab_list'] = $this->btob_job_model->master_fun_get_tbl_val("collect_from", array('status' => 1), array("id", "asc"));
            }

            /* $data['lab_list'] = $this->btob_job_model->master_fun_get_tbl_val("collect_from", array('status' => 1), array("id", "asc")); */



            $data['phlebo_list'] = $this->btob_job_model->master_fun_get_tbl_val("phlebo_master", array('status' => 1), array("id", "asc"));

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('b2b_sample_add', $data);
            $this->load->view('footer');
        }
    }

    function get_job_id($city = null) {
        $this->load->library("Util");
        $util = new Util();
        $new_id = $util->get_job_id($city);
        return $new_id;
    }

}

?>