<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Job_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('user_test_master_model');
        $this->load->model('job_model');
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
            $total_row = $this->job_model->num_row_srch_job_list($search_data);
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
            $data['query'] = $this->job_model->row_srch_job_list($search_data, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        } else {
            $search_data['cntr_arry'] = $cntr_arry;
            $total_row = $this->job_model->num_srch_job_list($cntr_arry);
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

            $res = $this->job_model->srch_job_list_get_id($config["per_page"], $page, $search_data);
            $datapass = array();
            foreach ($res as $r) {
                $datapass[] = $r['id'];
            }
            $search_data['idofdata'] = $datapass;
            $data['query'] = $this->job_model->srch_job_list($config["per_page"], $page, $search_data);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        }
        $cnt = 0;
        foreach ($data['query'] as $key) {
            $w_prc = 0;
            /* Count booked test price */
            $booked_tests = $this->job_model->master_fun_get_tbl_val("wallet_master", array('job_fk' => $key["id"]), array("id", "asc"));
            $emergency_tests = $this->job_model->master_fun_get_tbl_val("booking_info", array('id' => $key["booking_info"]), array("id", "asc"));
            $f_data = $this->job_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
            $f_data1 = $this->job_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
            $doctor_data = $this->job_model->master_fun_get_tbl_val("doctor_master", array('id' => $key["doctor"]), array("id", "asc"));
            $data['query'][$cnt]["send_repor_sms"] = $this->job_model->master_fun_get_tbl_val("send_report_sms", array('job_fk' => $key["id"], "status" => "1"), array("id", "asc"));
            $data['query'][$cnt]["send_report_mail"] = $this->job_model->master_fun_get_tbl_val("send_report_mail", array('job_fk' => $key["id"], "status" => "1"), array("id", "asc"));
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
            $upload_data = $this->job_model->master_fun_get_tbl_val("report_master", array('job_fk' => $key["id"]), array("id", "asc"));
            $job_test_list = $this->job_model->get_val("SELECT `job_test_list_master`.*,`test_master`.`test_name` FROM `job_test_list_master` INNER JOIN `test_master` ON `job_test_list_master`.`test_fk`=`test_master`.`id` WHERE `job_test_list_master`.`job_fk`='" . $key["id"] . "'");
            $data['query'][$cnt]["job_test_list"] = $job_test_list;
            $data['query'][$cnt]["report"] = $upload_data[0]["original"];
            $data['query'][$cnt]["emergency"] = $emergency_tests[0]["emergency"];
            $data['query'][$cnt]["cut_from_wallet"] = $w_prc;
            $data['query'][$cnt]["doctor_name"] = $doctor_data[0]["full_name"];
            $data['query'][$cnt]["doctor_mobile"] = $doctor_data[0]["mobile"];
            $package_ids = $this->job_model->get_job_booking_package($key["id"]);
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
        $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status!='0'");
        $cntr_arry = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data["login_data"]['branch_fk'] as $key) {
                $cntr_arry[] = $key["branch_fk"];
            }
            $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status!='0' and id in (" . implode(",", $cntr_arry) . ")");
            foreach ($data['branchlist'] as $branchs) {
                $cntr_arry1[] = $branchs["city"];
            }
            $data['citylist'] = $this->registration_admin_model->get_val("SELECT * from test_cities where status='1' and id in (" . implode(",", $cntr_arry1) . ")");
            foreach ($data['citylist'] as $cts) {
                $cntr_arry2[] = $cts["city_fk"];
            }
            $data['search_c'] = $cntr_arry1;
        }
        $data['sample_from'] = $this->registration_admin_model->get_val("SELECT name,id from sample_from where status='1'");
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
        $data['phlebolist'] = $this->job_model->get_val("select id,name from phlebo_master where status='1'");
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('pending_job_list_new', $data);
        $this->load->view('footer');
    }

    function pending_list_search() {
        $this->benchmark->mark('code_start');
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->library("util");
        $util = new Util;
        //echo "<pre>"; print_r($_POST); die();
        $search_data = array();
        $plm = $data['plm2'] = $search_data["plm"] = $this->input->get_post('plm');
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
        $outsource = $data['toutsource'] = $search_data["outsource"] = $this->input->get_post('outsource');
        $sample_from = $data['sample_from'] = $search_data["sample_from"] = $this->input->get_post('sample_from');
        $withsample = $data['withsample'] = $search_data["withsample"] = $this->input->get_post('withsample');
        $sample_not_processed_collected = $data['sample_not_processed'] = $search_data["sample_not_processed"] = $this->input->get_post('sample_not_processed');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cntr_arry = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data["login_data"]['branch_fk'] as $key) {
                $cntr_arry[] = $key["branch_fk"];
            }
        }
        $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status!='0'");
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
        if ($branch != '') {
            $cntr_arry = array();
            $cntr_arry = $branch;
        } else {
            $branch = $cntr_arry;
        }
        /* PLM start */
        if (empty($branch) && $plm != '') {
            if (empty($branch) && !empty($city) && empty($plm)) {
                $plm_branch = $this->job_model->get_val("SELECT GROUP_CONCAT(id) AS id FROM `branch_master` WHERE `status`='1' AND city='" . $city . "' GROUP BY status");
            }
            if (empty($branch) && !empty($plm)) {
                $plm_branch = $this->job_model->get_val("select GROUP_CONCAT(id) AS id from branch_master where status='1' and (parent_fk ='" . $plm . "' or id='" . $plm . "') GROUP BY status");
            }
            if (empty($branch) && empty($city) && empty($plm)) {
                $plm_branch = $this->job_model->get_val("select GROUP_CONCAT(id) AS id from branch_master where status='1' GROUP BY status");
            }
            $plm_branch = explode(",", $plm_branch[0]["id"]);
            $cntr_arry = array_merge(array($plm), $plm_branch);
        } else {
            $cntr_arry = $branch;
        }
        /* PLM END */
        $search_data['cntr_arry'] = $cntr_arry;
        $search_data['t_id'] = $t_id;
        $search_data['p_id'] = $p_id;
        $data['query'] = $this->job_model->new_row_srch_job_list($search_data, $config["per_page"], $page);
        $cnt = 0;
        foreach ($data['query'] as $key) {
            $w_prc = 0;
            /* Count booked test price */
//echo "<pre>";print_r($key);
            $booked_tests = $this->job_model->master_fun_get_tbl_val("wallet_master", array('job_fk' => $key["id"]), array("id", "asc"));
            $emergency_tests = $this->job_model->master_fun_get_tbl_val("booking_info", array('id' => $key["booking_info"]), array("id", "asc"));
            $f_data = $this->job_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
            $f_data1 = $this->job_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
            //$doctor_data = $this->job_model->master_fun_get_tbl_val("doctor_master", array('id' => $key["doctor"]), array("id", "asc"));
            $doctor_data = $this->job_model->master_fun_get_tbl_val_with_select("full_name,mobile", "doctor_master", array('id' => $key["doctor"]), array("id", "asc"));
            //  $data['query'][$cnt]["send_repor_sms"] = $this->job_model->master_fun_get_tbl_val("send_report_sms", array('job_fk' => $key["id"], "status" => "1"), array("id", "asc"));
            // $data['query'][$cnt]["send_report_mail"] = $this->job_model->master_fun_get_tbl_val("send_report_mail", array('job_fk' => $key["id"], "status" => "1"), array("id", "asc"));
            /* Nishit 11-8 start */
            if ($data["login_data"]["type"] != 1 && $data["login_data"]["type"] != 2) {
                $permission = $this->job_model->get_val("SELECT is_print FROM `report_print_permission` WHERE `status`='1' AND `type`='" . $data["login_data"]["type"] . "' AND branch='" . $key["branch_fk"] . "'");
                $data['query'][$cnt]["is_print"] = $permission[0]["is_print"];
            } else {
                $data['query'][$cnt]["is_print"] = 1;
            }
            /* End */
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
            $upload_data = $this->job_model->master_fun_get_tbl_val("report_master", array('job_fk' => $key["id"]), array("id", "asc"));
            $prnt_cnt = $this->job_model->get_val("SELECT id FROM `booked_job_test` WHERE job_fk='" . $key["id"] . "' AND status='1' AND `panel_fk` IS NOT NULL");
            $data['query'][$cnt]["panel_test_count"] = count($prnt_cnt);
            /* $data['query'][$cnt]["print_cnt"] = $this->job_model->get_val("SELECT COUNT(id) as cnt FROM `print_report_count` WHERE `status`='1' AND job_fk='" . $key["id"] . "'"); */
            $job_test_list = $this->job_model->get_val("SELECT `job_test_list_master`.*,`test_master`.`test_name` FROM `job_test_list_master` INNER JOIN `test_master` ON `job_test_list_master`.`test_fk`=`test_master`.`id` WHERE `job_test_list_master`.`job_fk`='" . $key["id"] . "'");
            /* Check sub test start */
            $job_tst_lst = array();
            foreach ($job_test_list as $st_key) {
                //echo $st_key['test_fk'];
                $job_sub_test_list = $this->job_model->get_val("SELECT `sub_test_master`.test_fk,`sub_test_master`.`sub_test`,test_master.`test_name` FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`sub_test`=`test_master`.`id` WHERE `sub_test_master`.`status`='1' AND `test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $st_key['test_fk'] . "'");
                $st_key["sub_test"] = $job_sub_test_list;
                $job_tst_lst[] = $st_key;
            }
            //die("OK");
            /* END */
            $data['query'][$cnt]["job_test_list"] = $job_tst_lst;
            $data['query'][$cnt]["report"] = $upload_data[0]["original"];
            $data['query'][$cnt]["whatsappsent"] = $upload_data[0]["whatsapp_sent_status"];
            $data['query'][$cnt]["emergency"] = $emergency_tests[0]["emergency"];
            $data['query'][$cnt]["cut_from_wallet"] = $w_prc;
            $data['query'][$cnt]["doctor_name"] = $doctor_data[0]["full_name"];
            $data['query'][$cnt]["doctor_mobile"] = $doctor_data[0]["mobile"];
            $package_ids = $this->job_model->get_job_booking_package($key["id"]);
            $data['query'][$cnt]["package"] = $package_ids;
            if (!empty($package_ids)) {
                $data['query'][$cnt]["packagename"] = "";
            }
            /* Nishit code start */
            $is_show = 1;
            if (!empty($test_pack)) {
                $is_show = 0;
                foreach ($job_test_list as $f_key) {
                    if (strpos(trim(strtoupper($f_key["test_name"])), trim(strtoupper($test_pack))) !== false) {
                        $is_show = 1;
                    }
                }
                foreach ($package_ids as $p_key) {
                    if (strpos(trim(strtoupper($p_key["name"])), trim(strtoupper($test_pack))) !== false) {
                        $is_show = 1;
                    }
                    foreach ($p_key["test"] as $p_key) {
                        if (strpos(trim(strtoupper($p_key["test_name"])), trim(strtoupper($test_pack))) !== false) {
                            $is_show = 1;
                        }
                    }
                }

                foreach ($job_tst_lst as $p_key) {
                    foreach ($p_key["sub_test"] as $sst_key) {
                        if (strpos(trim(strtoupper($sst_key["test_name"])), trim(strtoupper($test_pack))) !== false) {
                            $is_show = 1;
                        }
                    }
                }
            }
            $data['query'][$cnt]["is_show"] = $is_show;






            $late_processing = 0;
            $late_sample_collection = 0;
            if ($key['status'] == '7') {
                $sample_collect_time = $this->job_model->get_val("select job_status,date_time from job_log where status='1' AND job_status='6-7' AND job_fk=" . $key["id"]);
                if (!empty($sample_collect_time)) {
                    $date1 = strtotime($sample_collect_time[0]['date_time']);
                    $date2 = strtotime(date("Y-m-d H:i:s"));
                    $diff = (($date2 - $date1) / 60) / 60;
                    if ($diff > 1) {
                        $late_sample_collection = 1;
                    }
                }
            }

            $max_test_tat = 0;
            $max_package_tat = 0;
            $max_tat = 0;
            if ($key['status'] == '8') {

                $processing_time = $this->job_model->get_val("select job_status,date_time from job_log where status='1' AND job_status='7-8' AND job_fk=" . $key["id"]);
                $package_ids1 = [];
                $test_ids1 = [];
                $test_tat = [];
                $package_tat = [];
                foreach ($job_tst_lst as $key) {
                    $test_ids1[] = $this->job_model->get_val("select id from test_master where status='1' AND test_name like '%" . str_replace("'", "''", $key['test_name']) . "%'");
                }

                foreach ($package_ids as $key) {
                    $package_ids1[] = $this->job_model->get_val("select id from package_master where status='1' AND title like '%" . str_replace("'", "''", $key['name']) . "%'");
                }

                foreach ($test_ids1 as $key) {
                    $test_tat[] = $this->job_model->get_val("select tat from test_tat where status='1' AND type='1' AND test_fk='" . $key[0]['id'] . "'");
                }
                foreach ($package_ids1 as $key) {
                    $package_tat[] = $this->job_model->get_val("select tat from test_tat where status='1' AND type='2' AND test_fk='" . $key[0]['id'] . "'");
                }
                //echo "<pre>"; print_r($test_tat);
                if (!empty($test_tat)) {
                    foreach ($test_tat as $key) {
                        if ($key[0]['tat'] != "") {
                            if ($max_test_tat < $key[0]['tat']) {
                                $max_test_tat = $key[0]['tat'];
                            }
                        }
                    }
                }

                if (!empty($package_tat)) {
                    foreach ($package_tat as $key) {
                        if ($key[0]['tat'] != "") {
                            if ($max_package_tat < $key[0]['tat']) {
                                $max_package_tat = $key[0]['tat'];
                            }
                        }
                    }
                }

                if ($max_test_tat > $max_package_tat) {
                    $max_tat = $max_test_tat;
                } else {
                    $max_tat = $max_package_tat;
                }

                if (!empty($processing_time)) {
                    $date1 = strtotime($processing_time[0]['date_time']);
                    $date2 = strtotime(date("Y-m-d H:i:s"));
                    $diff = (($date2 - $date1) / 60) / 60;
                    if ($max_tat > 0) {
                        if ($diff > ($max_tat - 0.5)) {
                            $late_processing = 1;
                        }
                    }
                }
            }

            $data['query'][$cnt]["late_sample_collection"] = $late_sample_collection;
            $data['query'][$cnt]["late_processing"] = $late_processing;






            /* END */
            $cnt++;
        }
        //echo "<pre>";print_r($data['query']);die();
        $url = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        $this->session->set_userdata("job_master_r", $url);
        $this->load->view('pending_job_list_search', $data);
    }

    function pending_list_dev() {
        $this->load->library('Ajax_pagination');
        $this->perPage = 1000;
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
        $data['sample_from'] = $this->registration_admin_model->get_val("SELECT name,id from sample_from where status='1'");
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
        $this->load->view('pending_job_list_dev', $data);
        $this->load->view('footer');
    }

    function pending_list_search_dev() {
        $this->load->library('Ajax_pagination');
        $this->perPage = 1000;
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
        $outsource = $data['toutsource'] = $search_data["outsource"] = $this->input->get_post('outsource');
        $sample_from = $data['sample_from'] = $search_data["sample_from"] = $this->input->get_post('sample_from');
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
        if ($data["login_data"]["type"] == 1) {
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
        if ($branch != '') {
            $cntr_arry = array();
            $cntr_arry = $branch;
        }
        $search_data['cntr_arry'] = $cntr_arry;
        $search_data['t_id'] = $t_id;
        $search_data['p_id'] = $p_id;
        /* AJAX pagination start */
        $page = $data["page"] = $this->input->post('page');
        if (!$page) {
            $offset = 0;
        } else {
            $offset = $page;
        }
        //total rows count
        $totalRec = count($this->job_model->new_row_srch_job_list_count($search_data, $config["per_page"], $page));
        //pagination configuration
        $config['target'] = '#postList';
        $config['base_url'] = base_url() . 'job_master/pending_list_search_dev';
        $config['total_rows'] = $totalRec;
        $config['per_page'] = $this->perPage;
        $this->ajax_pagination->initialize($config);
        $data["link"] = $this->ajax_pagination->create_links();
        ;
        //echo $link; die();
        //get the posts data
        //echo $search_data."----".$offset."----".$this->perPage; die();
        $data['query'] = $this->job_model->new_row_srch_job_list($search_data, $offset, $this->perPage);
        //$data['posts'] = $this->post->getRows(array('start' => $offset, 'limit' => $this->perPage));
        /* END */
        $cnt = 0;
        foreach ($data['query'] as $key) {
            $w_prc = 0;
            /* Count booked test price */
            $booked_tests = $this->job_model->master_fun_get_tbl_val("wallet_master", array('job_fk' => $key["id"]), array("id", "asc"));
            $emergency_tests = $this->job_model->master_fun_get_tbl_val("booking_info", array('id' => $key["booking_info"]), array("id", "asc"));
            $f_data = $this->job_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
            $f_data1 = $this->job_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
            //$doctor_data = $this->job_model->master_fun_get_tbl_val("doctor_master", array('id' => $key["doctor"]), array("id", "asc"));
            $doctor_data = $this->job_model->master_fun_get_tbl_val_with_select("full_name,mobile", "doctor_master", array('id' => $key["doctor"]), array("id", "asc"));
            //  $data['query'][$cnt]["send_repor_sms"] = $this->job_model->master_fun_get_tbl_val("send_report_sms", array('job_fk' => $key["id"], "status" => "1"), array("id", "asc"));
            // $data['query'][$cnt]["send_report_mail"] = $this->job_model->master_fun_get_tbl_val("send_report_mail", array('job_fk' => $key["id"], "status" => "1"), array("id", "asc"));
            /* Nishit 11-8 start */
            if ($data["login_data"]["type"] != 1 && $data["login_data"]["type"] != 2) {
                $permission = $this->job_model->get_val("SELECT is_print FROM `report_print_permission` WHERE `status`='1' AND `type`='" . $data["login_data"]["type"] . "' AND branch='" . $key["branch_fk"] . "'");
                $data['query'][$cnt]["is_print"] = $permission[0]["is_print"];
            } else {
                $data['query'][$cnt]["is_print"] = 1;
            }
            /* End */
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
            $upload_data = $this->job_model->master_fun_get_tbl_val("report_master", array('job_fk' => $key["id"]), array("id", "asc"));
            $prnt_cnt = $this->job_model->get_val("SELECT id FROM `booked_job_test` WHERE job_fk='" . $key["id"] . "' AND status='1' AND `panel_fk` IS NOT NULL");
            $data['query'][$cnt]["panel_test_count"] = count($prnt_cnt);
            $data['query'][$cnt]["print_cnt"] = $this->job_model->get_val("SELECT COUNT(id) as cnt FROM `print_report_count` WHERE `status`='1' AND job_fk='" . $key["id"] . "'");
            $job_test_list = $this->job_model->get_val("SELECT `job_test_list_master`.*,`test_master`.`test_name` FROM `job_test_list_master` INNER JOIN `test_master` ON `job_test_list_master`.`test_fk`=`test_master`.`id` WHERE `job_test_list_master`.`job_fk`='" . $key["id"] . "'");
            /* Check sub test start */
            $job_tst_lst = array();
            foreach ($job_test_list as $st_key) {
                //echo $st_key['test_fk'];
                $job_sub_test_list = $this->job_model->get_val("SELECT `sub_test_master`.test_fk,`sub_test_master`.`sub_test`,test_master.`test_name` FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`sub_test`=`test_master`.`id` WHERE `sub_test_master`.`status`='1' AND `test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $st_key['test_fk'] . "'");
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
            $package_ids = $this->job_model->get_job_booking_package($key["id"]);
            $data['query'][$cnt]["package"] = $package_ids;
            if (!empty($package_ids)) {
                $data['query'][$cnt]["packagename"] = "";
            }
            $cnt++;
        }
        //  echo "<pre>";  print_r($data['query']); die();
        $url = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        $this->session->set_userdata("job_master_r", $url);
        $this->load->view('pending_job_list_search_dev', $data);
    }

    /* END */

    function spam_job_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        die("Spam job is under maintenance we will back in short time.");
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
            $total_row = $this->job_model->spam_job_list_count($search_data);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "job_master/spam_job_list?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 100;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->job_model->spam_job_filter($search_data, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        } else {
            $search_data['cntr_arry'] = $cntr_arry;
            $total_row = $this->job_model->spam_job_num($cntr_arry);
            $config["base_url"] = base_url() . "job_master/spam_job_list";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 100;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->job_model->spam_job_data($config["per_page"], $page, $search_data);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        }
        $data['customer'] = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("full_name", "asc"));
        $data['city'] = $this->job_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
        $cnt = 0;
        foreach ($data['query'] as $key) {
            $w_prc = 0;
            /* Count booked test price */
            $booked_tests = $this->job_model->master_fun_get_tbl_val("wallet_master", array('job_fk' => $key["id"]), array("id", "asc"));
            $emergency_tests = $this->job_model->master_fun_get_tbl_val("booking_info", array('id' => $key["booking_info"]), array("id", "asc"));
            $f_data = $this->job_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
            $f_data1 = $this->job_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
            $doctor_data = $this->job_model->master_fun_get_tbl_val("doctor_master", array('id' => $key["doctor"]), array("id", "asc"));
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
            $upload_data = $this->job_model->master_fun_get_tbl_val("report_master", array('job_fk' => $key["id"]), array("id", "asc"));
            $data['query'][$cnt]["report"] = $upload_data[0]["original"];
            $data['query'][$cnt]["emergency"] = $emergency_tests[0]["emergency"];
            $data['query'][$cnt]["cut_from_wallet"] = $w_prc;
            $data['query'][$cnt]["doctor_name"] = $doctor_data[0]["full_name"];
            $data['query'][$cnt]["doctor_mobile"] = $doctor_data[0]["mobile"];
            $package_ids = $this->job_model->get_job_booking_package($key["id"]);
            if (trim($package_ids) != '') {
                $data['query'][$cnt]["packagename"] = $package_ids;
            }
            $cnt++;
        }
//echo "<pre>"; print_R($data['query']); die();
        $url = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        //$this->session->set_userdata("job_master_r", $url);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('spam_job_list1', $data);
        $this->load->view('footer');
    }

    function find_job_list() {
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
//echo "<pre>"; print_R($_GET); die();
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
            $total_row = $this->job_model->num_row_srch_job_list($search_data);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "job_master/find_job_list?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 100;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->job_model->row_srch_job_list($search_data, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;


            $data['customer'] = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("full_name", "asc"));
            $data['city'] = $this->job_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
            $cnt = 0;
            foreach ($data['query'] as $key) {
                $w_prc = 0;
                /* Count booked test price */
                $booked_tests = $this->job_model->master_fun_get_tbl_val("wallet_master", array('job_fk' => $key["id"]), array("id", "asc"));
                $emergency_tests = $this->job_model->master_fun_get_tbl_val("booking_info", array('id' => $key["booking_info"]), array("id", "asc"));
                $f_data = $this->job_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
                $f_data1 = $this->job_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
                $doctor_data = $this->job_model->master_fun_get_tbl_val("doctor_master", array('id' => $key["doctor"]), array("id", "asc"));
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
                $upload_data = $this->job_model->master_fun_get_tbl_val("report_master", array('job_fk' => $key["id"]), array("id", "asc"));
                $data['query'][$cnt]["report"] = $upload_data[0]["original"];
                $data['query'][$cnt]["emergency"] = $emergency_tests[0]["emergency"];
                $data['query'][$cnt]["cut_from_wallet"] = $w_prc;
                $data['query'][$cnt]["doctor_name"] = $doctor_data[0]["full_name"];
                $data['query'][$cnt]["doctor_mobile"] = $doctor_data[0]["mobile"];
                $package_ids = $this->job_model->get_job_booking_package($key["id"]);
                if (trim($package_ids) != '') {
                    $data['query'][$cnt]["packagename"] = $package_ids;
                }
                $cnt++;
            }
        }

//echo "<pre>"; print_R($data['query']); die();
        $url = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        $this->session->set_userdata("job_master_r", $url);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('find_job_list', $data);
        $this->load->view('footer');
    }

    function changing_status_job() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $status = $this->input->post('status');
        $job_id = $this->input->post('jobid');


        if ($status == "") {
            die("1");
        }

        $customer_last_job_id = $this->job_model->master_fun_get_tbl_val("job_master", array('id' => $job_id), array("id", "asc"));
        if ($status == 2) {
            $sjobid = $job_id;
            $this->job_mark_completed($job_id);
            $this->getstock($sjobid);
        }
        if ($status == 7) {
            $this->sample_collected_calculation($job_id);
        }
        $status_update = $this->job_model->master_fun_update("job_master", array("id", $job_id), array("status" => $status));
        $this->job_model->master_fun_insert("job_log", array("job_fk" => $job_id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => $customer_last_job_id[0]["status"] . "-" . $status, "message_fk" => "3", "date_time" => date("Y-m-d H:i:s")));
        if ($status_update) {
            echo 1;
        }
    }

    function getstock($jobid) {
        $this->load->model("add_result_model");
        $jobdetils = $this->job_model->get_val("SELECT id,`branch_fk` FROM `job_master` WHERE id='$jobid'");
        /* Check birth date end */
        $data['query'] = $this->add_result_model->job_details($jobid);
        $tid = array();
        $data['parameter_list'] = array();
        if (trim($data['query'][0]['testid']) == null && $data['query'][0]["packageid"] != null) {
            $package_id = $data['query'][0]["packageid"];
            $pid = explode("%", $data['query'][0]['packageid']);
            foreach ($pid as $pkey) {
                $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");
                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["test_fk"];
                }
            }
        } else if (trim($data['query'][0]['testid']) != null && $data['query'][0]["packageid"] != null) {

            $tid = explode(",", $data['query'][0]['testid']);
            $package_id = $data['query'][0]["packageid"];
            $pid = explode("%", $data['query'][0]['packageid']);
            foreach ($pid as $pkey) {
                $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");

                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["test_fk"];
                }
            }
        } else {
            $tid = explode(",", $data['query'][0]['testid']);
        }

        foreach ($tid as $t_key) {
            $p_test = $this->add_result_model->get_val("SELECT sub_test_master.* FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `sub_test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $t_key . "'");
            foreach ($p_test as $tp_key) {
                $tid[] = $tp_key["sub_test"];
            }
        }
        //echo "<pre>";print_R($tid); die();
        $tid = array_unique($tid);

        /* END */
        $get_approve_test_parameter = $this->add_result_model->get_val("SELECT GROUP_CONCAT(test_fk) AS test_fk FROM `approve_job_test` WHERE test_fk>0 AND job_fk='" . $jobid . "' GROUP BY job_fk");
        $approve_job_test_id = explode(",", $get_approve_test_parameter[0]["test_fk"]);


        $branch = $jobdetils[0]["branch_fk"];
        //$pc = $this->job_model->get_val("SELECT * FROM `processing_center` WHERE `status`='1' AND `lab_fk`='" . $branch . "'");
        //$branch = $pc[0]["branch_fk"];
        $branallmachine = $this->job_model->get_val("SELECT GROUP_CONCAT(DISTINCT machine_fk) AS machin FROM `inventory_machine_branch` WHERE STATUS='1' AND `branch_fk`='$branch' GROUP BY branch_fk");
        if (!empty($branallmachine)) {
            $macineaall = $branallmachine[0]["machin"];
            //echo "<pre>";print_r($approve_job_test_id);die();  
            foreach ($tid as $tst_id) {
                if (in_array($tst_id, $approve_job_test_id)) {
                    $test = $tst_id;
                    $getjoballtest = $this->job_model->get_val("SELECT id,test_fk,performed,performed_reason FROM use_formula WHERE job_fk='$jobid' and test_fk='" . $tst_id . "'");
                    $performed = $getjoballtest[0]["performed"];
                    $performed_reason = $getjoballtest[0]["performed_reason"];
                    $getjobtest = $this->job_model->get_val("SELECT id,machine_fk,item_fk,quantity FROM `inventory_test_machine` WHERE test_fk='" . $tst_id . "' AND  machine_fk IN($macineaall) AND status='1'");
                    foreach ($getjobtest as $man) {
                        $getusereagent = $this->job_model->get_val("SELECT id FROM `inventory_usedreagent` WHERE status='1' AND `branchfk`='$branch' AND `reaqgentfk`='" . $man["item_fk"] . "' ORDER BY id DESC");
                        $getreagentid = $getusereagent[0]["id"];
                        if ($getreagentid != "") {
                            $performed = ($performed > 0) ? $performed : 1;
                            $this->job_model->master_fun_insert("inventory_jobstock", array("branchid" => $branch, "jobid" => $jobid, "usedreagent_fk" => $getreagentid, "testid" => $tst_id, "itemfk" => $man["item_fk"], "timeperfomace" => $performed, "perform_reason" => $performed_reason, "useditem" => $man["quantity"], "creteddate" => date("Y-m-d H:i:s")));
                        }
                    }
                }
            }
        }


        /* $branch = $jobdetils[0]["branch_fk"];
          $branallmachine = $this->job_model->get_val("SELECT GROUP_CONCAT(DISTINCT machine_fk) AS machin FROM `inventory_machine_branch` WHERE STATUS='1' AND `branch_fk`='$branch' GROUP BY branch_fk");
          $macineaall = $branallmachine[0]["machin"];
          $getjoballtest = $this->job_model->get_val("SELECT id,test_fk,performed,performed_reason FROM use_formula WHERE job_fk='$jobid'");
          foreach ($getjoballtest as $gettest) {
          $test = $gettest["test_fk"];
          $performed = $gettest["performed"];
          $performed_reason = $gettest["performed_reason"];
          $getjobtest = $this->job_model->get_val("SELECT id,machine_fk,item_fk,quantity FROM `inventory_test_machine` WHERE test_fk='" . $test . "' AND  machine_fk IN($macineaall) AND status='1'");
          foreach ($getjobtest as $man) {
          $getusereagent = $this->job_model->get_val("SELECT id FROM `inventory_usedreagent` WHERE status='1' AND `branchfk`='$branch' AND `reaqgentfk`='" . $man["item_fk"] . "' ORDER BY id DESC");
          $getreagentid = $getusereagent[0]["id"];
          if ($getreagentid != "") {
          $this->job_model->master_fun_insert("inventory_jobstock", array("branchid" => $branch, "jobid" => $jobid, "usedreagent_fk" => $getreagentid, "testid" => $test, "itemfk" => $man["item_fk"], "timeperfomace" => $performed, "perform_reason" => $performed_reason, "useditem" => $man["quantity"], "creteddate" => date("Y-m-d H:i:s")));
          }
          }
          } */
    }

    function changing_spam($id) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $status_update = $this->job_model->master_fun_update("job_master", array("id", $id), array("status" => '0', "deleted_by" => "0"));
        $this->job_model->master_fun_update("job_master_receiv_amount", array("job_fk", $id), array("status" => '0'));
        $this->job_model->master_fun_update("phlebo_assign_job", array("job_fk", $id), array("status" => '0'));
        $this->job_model->master_fun_insert("job_log", array("job_fk" => $id, "created_by" => "", "updated_by" => "", "deleted_by" => $data["login_data"]["id"], "message_fk" => "4", "date_time" => date("Y-m-d H:i:s")));
        $this->job_model->master_fun_update("wallet_master", array("job_fk", $id), array("status" => 0));
        /* Nishit Wallte cut cashback start */
        $debit_from_wallet = $this->job_model->master_fun_get_tbl_val("wallet_master", array('status' => 1, "job_fk" => $id, "debit >" => 0), array("id", "asc"));
        $credit_from_wallet = $this->job_model->master_fun_get_tbl_val("wallet_master", array('status' => 1, "job_fk" => $id, "credit >" => 0), array("id", "desc"));
        if (!empty($debit_from_wallet) != '' || !empty($credit_from_wallet)) {
            $job_data = $this->job_model->master_fun_get_tbl_val("job_master", array("id" => $id), array("id", "desc"));
            $customer_wallet = $this->job_model->get_val("select * from wallet_master where status='1' and cust_fk='" . $job_data[0]["cust_fk"] . "' order by id desc limit 0,1");
            $old_amount = ($customer_wallet[0]["total"] > 0) ? $customer_wallet[0]["total"] : 0;
            $debit_amount = ($debit_from_wallet[0]["debit"] > 0) ? $debit_from_wallet[0]["debit"] : 0;
            $credit_amount = ($credit_from_wallet[0]["credit"] > 0) ? $credit_from_wallet[0]["credit"] : 0;
            $final_amount = $old_amount + $debit_amount - $credit_amount;
            $this->job_model->master_fun_update("wallet_master", array("job_fk", $id), array("status" => 0));
            $this->job_model->master_fun_insert("wallet_master", array("cust_fk" => $job_data[0]["cust_fk"], "credit" => $debit_amount, "debit" => $credit_amount, "total" => $final_amount, "created_time" => date("Y-m-d H:i:s"), "type" => "", "status" => "1"));
        }
        /* END */
        $this->session->set_flashdata("success", array("Job successfully mark as Spam."));
        if (!empty($this->session->userdata("job_master_r"))) {
            //redirect($this->session->userdata("job_master_r"), "refresh");
            redirect("job-master/pending-list", "refresh");
        } else {
            redirect("job-master/pending-list", "refresh");
        }
    }

    function prescription_spam($id) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $status_update = $this->job_model->master_fun_update("prescription_upload", array("id", $id), array("status" => '0'));
        //$this->job_model->master_fun_insert("job_log", array("job_fk" => $id, "created_by" => "", "updated_by" => "", "deleted_by" => $data["login_data"]["id"], "message_fk" => "4", "date_time" => date("Y-m-d H:i:s")));
        $this->session->set_flashdata("success", array("Prescription successfully mark as Spam."));
        redirect("job-master/prescription-report", "refresh");
    }

    function spam_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();

        $user = $this->input->get('user');
        $date = $this->input->get('date');
        $city = $this->input->get('city');
        $data['customerfk'] = $user;
        $data['date'] = $date;
        $data['cityfk'] = $city;
        //print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['success'] = $this->session->flashdata("success");
        $data['query'] = $this->job_model->spam_job_search($user, $date, $city);
        //$this->load->view('admin/state_list_view', $data);
        $data['customer'] = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("id", "asc"));
        $data['city'] = $this->job_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('spam_job_list', $data);
        $this->load->view('footer');
    }

    function completed_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();

        $user = $this->input->get('user');
        $date = $this->input->get('date');
        $city = $this->input->get('city');
        $data['customerfk'] = $user;
        $data['date'] = $date;
        $data['cityfk'] = $city;
        //print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['success'] = $this->session->flashdata("success");
        $data['query'] = $this->job_model->completed_job_search($user, $date, $city);
        //$this->load->view('admin/state_list_view', $data);

        $data['customer'] = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("id", "asc"));
        $data['city'] = $this->job_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('completed_job_list', $data);
        $this->load->view('footer');
    }

    function job_details() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $this->uri->segment(3);
        $data['success'] = $this->session->flashdata("success");
        $data['error'] = $this->session->flashdata("error");
        $data['family_error'] = $this->session->flashdata("family_error");
        $data['amount_history_success'] = $this->session->flashdata("amount_history_success");


        if ($this->session->userdata("amount_history_success")) {
            $data['amount_history_success'] = $this->session->userdata("amount_history_success");
            $this->session->unset_userdata("amount_history_success");
        }
        $data['query'] = $this->job_model->job_details($data['cid']);
       // print_r($data['query']);
        $data["createdUser"] = $this->user_model->getUser($data['query']["added_by"]);
        
        
        if($data['query']["added_by"]==0){
            $data["createdUser"] = $this->user_model->getUser($data['query']["phlebo_added"]);
            
        }
        
        
        
        $data["b2b_job_detais"] = array();
        if (!empty($data['query'][0]["b2b_id"])) {
            $data["b2b_job_detais"] = $this->job_model->master_fun_get_tbl_val("sample_job_master", array('barcode_fk' => $data['query'][0]["b2b_id"]), array("id", "asc"));
            $data["b2b_job_master_receiv_amount"] = $this->job_model->get_val("SELECT 
            `sample_job_master_receiv_amount`.*,admin_master.name 
            FROM
            `sample_job_master_receiv_amount` 
            LEFT JOIN `admin_master` 
                ON `sample_job_master_receiv_amount`.`added_by` = `admin_master`.`id` 
            WHERE sample_job_master_receiv_amount.job_fk = '" . $data["b2b_job_detais"][0]["id"] . "' 
            AND sample_job_master_receiv_amount.status = '1'");
            //echo "<pre>"; print_r($data["b2b_job_detais"]); die();
        }
        $data['branchdis'] = $data['query'][0]["branch_fk"];

        $data['alertdetils'] = $this->registration_admin_model->get_val("SELECT smsalert,emailalert from branch_master where  status='1' and id='" . $data['branchdis'] . "'");

        $data["selected_creditor"] = $this->job_model->get_val("SELECT job_creditors.`amount`,job_creditors.`creditors_fk`,job_creditors.job_fk,`creditors_master`.`name`,`creditors_master`.`mobile` FROM `job_creditors` INNER JOIN `creditors_master` ON `creditors_master`.`id`=`job_creditors`.`creditors_fk` WHERE `job_creditors`.`status`='1' AND `job_creditors`.`job_fk`='" . $data["cid"] . "'");
        /* Check Barcode Start */
        if (empty($data['query'][0]["barcode"])) {
            $b_data = $this->job_model->master_fun_get_tbl_val("branch_master", array('id' => $data['query'][0]["branch_fk"]), array("id", "asc"));
            $barcode = $data['query'][0]["test_city"] . $b_data[0]["branch_code"] . $data["cid"];
            $barcode = $data["cid"];
            //$barcode = substr($barcode, -5);
            if (is_numeric($barcode)) {
                $barcode = 0 + $barcode;
            }
            $this->job_model->master_fun_update_multi("job_master", array("id" => $data["cid"]), array("barcode" => $barcode));
            $data['query'][0]["barcode"] = $barcode;
        }
        $data['query'][0]["panel_test"] = $this->job_model->get_val("SELECT id FROM `booked_job_test` WHERE job_fk='" . $data["cid"] . "' AND status='1' AND `panel_fk` IS NOT NULL");
        /* END */
        //print_r($data['query']); die();
        $this->load->library("util");
        $util = new Util;
        $data["age"] = $util->get_age($data['query'][0]["dob"]);
        if ($data['query'][0]["payment_type"] == 'PayUMoney') {
            $data['payumoney_details'] = $this->job_model->master_fun_get_tbl_val("payment", array('job_fk' => $data['cid']), array("id", "asc"));
        }
        $data["creditors"] = $this->job_model->get_val("select cm.* from creditors_master cm join creditors_branch cb on cb.creditors_fk=cm.id where cm.status=1 and cb.status=1 and cb.branch_fk='" . $data['query'][0]['branch_fk'] . "' group by cm.id order by cm.name ASC");
        //$data['creditors'] = $this->job_model->master_fun_get_tbl_val("creditors_master", array('status' => '1', "branch_fk" => $data['query'][0]['branch_fk']), array("name", "asc"));
        $data['report'] = $this->job_model->master_fun_get_tbl_val("report_master", array('status' => 1, "job_fk" => $data['cid'], "type !=" => "c"), array("id", "asc"));
        $data['common_report'] = $this->job_model->master_fun_get_tbl_val("report_master", array('status' => 1, "job_fk" => $data['cid'], "type" => "c"), array("id", "asc"));
        $data['city'] = $this->job_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
        $data['state'] = $this->job_model->master_fun_get_tbl_val("state", array('status' => 1), array("id", "asc"));
        $data['country'] = $this->job_model->master_fun_get_tbl_val("country", array('status' => 1), array("id", "asc"));
        $data['package_price'] = $this->job_model->master_fun_get_tbl_val("package_master_city_price", array('status' => 1), array("id", "asc"));
        $data['phlebo_list'] = $this->job_model->master_fun_get_tbl_val("phlebo_master", array('status' => 1, "test_city" => $data['query'][0]["test_city"]), array("id", "asc"));
        $data['phlebo_assign_job'] = $this->job_model->master_fun_get_tbl_val("phlebo_assign_job", array('status' => 1, "job_fk" => $this->uri->segment(3)), array("id", "asc"));
        $p_prc = 0;
        foreach ($data['phlebo_assign_job'] as $pkey) {
            if ($pkey["time_fk"]) {
                $time_slot = $this->job_model->master_fun_get_tbl_val("phlebo_time_slot", array('status' => "1", "id" => $pkey["time_fk"]), array("id", "asc"));
                $s_time = date('h:i a', strtotime($time_slot[0]["start_time"]));
                $e_time = date('h:i a', strtotime($time_slot[0]["end_time"]));
                $data['phlebo_assign_job'][$p_prc]["time"] = $s_time . " TO " . $e_time;
            }
            $data['phlebo_assign_job'][$p_prc]["time1"] = $pkey["time"];
            $p_prc++;
        }
        $data["emergency_tests"] = $this->job_model->master_fun_get_tbl_val("booking_info", array('id' => $data['query'][0]["booking_info"]), array("id", "asc"));
        $data["customer_info"] = $this->job_model->master_fun_get_tbl_val("customer_master", array('id' => $data['query'][0]["custid"]), array("id", "asc"));
        $data["booking_info"] = $this->job_model->get_val("SELECT 
        `booking_info`.*,
        TIME_FORMAT(
            `phlebo_time_slot`.`start_time`,
            '%l:%i %p'
        ) AS `start_time`,
        TIME_FORMAT(
            `phlebo_time_slot`.`end_time`,
            '%l:%i %p'
        ) AS `end_time`,
        `customer_family_master`.`name` 
        FROM
        `booking_info` 
        left JOIN `phlebo_time_slot` 
            ON `booking_info`.`time_slot_fk` = `phlebo_time_slot`.`id` 
        LEFT JOIN `customer_family_master` 
        ON `booking_info`.`family_member_fk` = `customer_family_master`.`id` where booking_info.id='" . $data['query'][0]["booking_info"] . "'");
        $f_data = $this->job_model->master_fun_get_tbl_val("customer_family_master", array('id' => $data["emergency_tests"][0]["family_member_fk"]), array("id", "asc"));
        $f_data1 = $this->job_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
        $data["family_data"] = $f_data;
        $data["relation"] = "Self";
        $data["family_fk"] = $f_data;
        if (!empty($f_data1)) {
            $data["relation"] = ucfirst($f_data[0]["name"] . " (" . $f_data1[0]["name"] . ")");
            $data["f_age"] = $util->get_age($f_data[0]["dob"]);
        }
        $update = $this->job_model->master_fun_update("job_master", array('id', $data['cid']), array("views" => "1"));
        $booked_tests = $this->job_model->master_fun_get_tbl_val("wallet_master", array('job_fk' => $data['cid']), array("id", "asc"));
        $w_prc = 0;
        foreach ($booked_tests as $tkey) {
            if ($tkey["debit"]) {
                $w_prc = $w_prc + $tkey["debit"];
            }
        }
        $test1 = array();
        $tid = explode(",", $data['query'][0]['testid']);
        foreach ($tid as $tst_id) {
            $para = $this->job_model->get_val("SELECT p.id as pid,p.test_fk,p.parameter_name,p.parameter_range,p.parameter_unit,g.id as gid,g.parameter_fk,g.subparameter_name,g.subparameter_range,g.subparameter_unit FROM test_parameter_master as p LEFT JOIN parameter_group_master as g ON p.id=g.parameter_fk WHERE p.status='1' AND p.test_fk='" . $tst_id . "' order by p.id ASC");
            array_push($test1, $para);
        }
        $data["job_master_receiv_amount"] = $this->job_model->get_val("SELECT 
        `job_master_receiv_amount`.*,
        IF(
            `job_master_receiv_amount`.`phlebo_fk` > 0,
            CONCAT(phlebo_master.`name`,'',' (Phlebo)'),`admin_master`.`name`
        ) AS `name`,
        payment_type_master.`name` AS pay_via 
        FROM
        `job_master_receiv_amount` 
        LEFT JOIN `admin_master` 
            ON `job_master_receiv_amount`.`added_by` = `admin_master`.`id` 
        LEFT JOIN `payment_type_master` 
            ON `payment_type_master`.`id` = `job_master_receiv_amount`.`payment_type` 
            LEFT JOIN `phlebo_master` 
            ON `phlebo_master`.`id`=`job_master_receiv_amount`.`phlebo_fk`
        WHERE job_master_receiv_amount.job_fk = '" . $data['cid'] . "' 
        AND job_master_receiv_amount.status = '1'");
                $data['relation_list'] = $this->job_model->get_val("SELECT 
        `customer_family_master`.*,
        `relation_master`.`name` AS relation_name 
        FROM
        `customer_family_master` 
        INNER JOIN `relation_master` 
            ON `customer_family_master`.`relation_fk` = `relation_master`.`id` 
        WHERE `customer_family_master`.`status` = '1' 
        AND `relation_master`.`status` = '1' 
        AND `customer_family_master`.`user_fk` = '" . $data['query'][0]["custid"] . "'");
        if (strlen($data["query"][0]["other_reference"]) < 3) {
            $source_data = $this->job_model->master_fun_get_tbl_val("source_master", array('id' => $data["query"][0]["other_reference"]), array("id", "asc"));
            $data["query"][0]["other_reference"] = $source_data[0]["name"];
        }
        if (empty($data["query"][0]["other_reference"])) {
            $data["query"][0]["other_reference"] = 'NA';
        }
        /* Nishit 11-8 start */
        if ($data["login_data"]["type"] != 1 && $data["login_data"]["type"] != 2) {
            $permission = $this->job_model->get_val("SELECT is_print FROM `report_print_permission` WHERE `status`='1' AND `type`='" . $data["login_data"]["type"] . "' AND branch='" . $data['query'][0]["branch_fk"] . "'");
            $data['query'][0]["is_print"] = $permission[0]["is_print"];            
        } else {
            $data['query'][0]["is_print"] = 1;
        }
        /* End */
        //echo "<pre>"; print_r($data['query']); die();
        //        $data["creditors_pay"] = $this->job_model->get_val("SELECT `job_creditors`.*,admin_master.name FROM job_creditors LEFT JOIN `admin_master` ON `job_creditors`.`added_by` = `admin_master`.`id` where job_creditors.status=1 and job_creditors.job_fk='" . $data['cid'] . "'");
        $data["relation1"] = $this->job_model->master_fun_get_tbl_val("relation_master", array('status' => "1"), array("name", "asc"));
        $data["payment_type_list"] = $this->job_model->master_fun_get_tbl_val("payment_type_master", array('status' => "1"), array("name", "asc"));
        $data["online_pay"] = $this->job_model->master_fun_get_tbl_val("payment", array('status' => "success", "type" => "job", "job_fk" => $data['cid']), array("id", "asc"));
        $data["phlebo_collect"] = $this->job_model->get_val("SELECT `phlebo_collect_amount`.*,`phlebo_master`.`name` FROM `phlebo_collect_amount` INNER JOIN phlebo_master ON `phlebo_collect_amount`.`phlebo_fk`= `phlebo_master`.`id` WHERE `phlebo_collect_amount`.`status`='1' AND job_fk='" . $data['cid'] . "'");
        $data["phlebo_rate"] = $this->job_model->get_val("SELECT   `phlebo_rate`.*,  `phlebo_master`.`name` FROM  `phlebo_rate`   INNER JOIN `phlebo_master`     ON `phlebo_rate`.`phlebo_fk` = `phlebo_master`.`id` WHERE `phlebo_rate`.`status`='1' AND `phlebo_master`.`status`='1' AND job_fk='" . $data['cid'] . "' order by phlebo_rate.id desc");
        $data['parameter_list'] = $test1;
        $data['branch_list'] = $this->registration_admin_model->get_val("SELECT * from branch_master where  status='1' and city='" . $data['query'][0]["test_city"] . "'");
        $data["discount_added_by"] = $this->job_model->get_val("SELECT job_log.`updated_by`,`admin_master`.`name` FROM `job_log` INNER JOIN `admin_master` ON `job_log`.`updated_by`=`admin_master`.`id` WHERE `job_log`.`job_fk`='" . $data['cid'] . "' AND `job_log`.`message_fk`='24' order by job_log.id desc");
        $job_test_list = $this->job_model->get_val("SELECT `job_test_list_master`.*,`test_master`.`test_name` FROM `job_test_list_master` INNER JOIN `test_master` ON `job_test_list_master`.`test_fk`=`test_master`.`id` WHERE `job_test_list_master`.`job_fk`='" . $data["cid"] . "'");
        $data['query'][0]["job_test_list"] = array();
        foreach ($job_test_list as $t_key) {
            $sub_test_list = $this->job_model->get_val("SELECT `sub_test_master`.*,`test_master`.`test_name` FROM `sub_test_master` INNER JOIN test_master ON `sub_test_master`.`sub_test`=test_master.`id` WHERE `sub_test_master`.`status`='1' AND `test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $t_key["test_fk"] . "'");
            $t_key["sub_test"] = $sub_test_list;
            $data['query'][0]["job_test_list"][] = $t_key;
        }

        $selected_package = $this->registration_admin_model->get_val("SELECT `book_package_master`.*,`package_master`.`title` FROM `book_package_master` INNER JOIN `package_master` ON `book_package_master`.`package_fk`=`package_master`.`id` WHERE `book_package_master`.`status`='1' AND `package_master`.`status`='1' AND `book_package_master`.`job_fk`='" . $data["cid"] . "'");
        $data["selected_package"] = array();
        foreach ($selected_package as $pkey) {
            $package_test_list = $this->registration_admin_model->get_val("SELECT 
            `package_test`.*,
            `test_master`.`test_name` 
            FROM
            `package_test` 
            INNER JOIN `test_master` 
                ON `package_test`.`test_fk` = `test_master`.`id` 
            WHERE `package_test`.`status` = '1' 
            AND `test_master`.`status` = '1' 
            AND `package_test`.`package_fk` = '" . $pkey["package_fk"] . "'");
            $pkey["test_list"] = $package_test_list;
            $data["selected_package"][] = $pkey;
        }
        //$data["sample_from"] = $this->job_model->get_val("SELECT job_master.`id`,`job_master`.`sample_from`,`sample_from`.`name` FROM job_master INNER JOIN `sample_from` ON `job_master`.`sample_from`=sample_from.`id` WHERE `sample_from`.`status`='1' AND job_master.id='" . $data['cid'] . "'");
        $prnt_cnt = $this->job_model->get_val("SELECT id FROM `booked_job_test` WHERE job_fk='" . $data['cid'] . "' AND status='1' AND `panel_fk` IS NOT NULL");
        $data['query'][0]["panel_test_count"] = count($prnt_cnt);
        //echo "<pre>"; print_R($data["query"]); die();
        $data["cut_from_wallet"] = $w_prc;
        /* B2B creditor list start */
        if (!empty($data['query'][0]["b2b_id"])) {
            $data["user_branch"] = $this->job_model->get_val("SELECT GROUP_CONCAT(branch_fk) AS branch FROM `user_branch` WHERE `status`='1' AND user_fk='" . $data["login_data"]["id"] . "' GROUP BY user_fk");
            if (!empty($data["user_branch"]) && !in_array($data["login_data"]["type"], array("1", "2"))) {
                $data["creditors"] = $this->job_model->get_val("select cm.* from sample_creditors_master cm join sample_creditors_branch cb on cb.creditors_fk=cm.id where cm.status=1 and cb.status=1 and cb.branch_fk in (" . $data["user_branch"][0]["branch"] . ") group by cm.id order by cm.name ASC");
            } else {
                $data["creditors"] = $this->job_model->get_val("select cm.* from sample_creditors_master cm join sample_creditors_branch cb on cb.creditors_fk=cm.id where cm.status=1 and cb.status=1 group by cm.id order by cm.name ASC");
            }
            $data["selected_creditor"] = $this->job_model->get_val("SELECT sample_job_creditors.`amount`,sample_job_creditors.`creditors_fk`,sample_job_creditors.job_fk,`sample_creditors_master`.`name`,`sample_creditors_master`.`mobile` FROM `sample_job_creditors` INNER JOIN `sample_creditors_master` ON `sample_creditors_master`.`id`=`sample_job_creditors`.`creditors_fk` WHERE `sample_job_creditors`.`status`='1' AND `sample_job_creditors`.`job_fk`='" . $data["b2b_job_detais"][0]["id"] . "'");
        }
        /* END */
        /* Nishit old report check start */
        $data["old_report"] = $this->job_model->get_val("SELECT job_master.id,job_master.order_id,report_master.`report`,report_master.`original`,`report_master`.without_laterpad,`report_master`.without_laterpad_original FROM job_master INNER JOIN `report_master` ON `report_master`.`job_fk`=`job_master`.`id` AND `report_master`.`status`='1' WHERE job_master.`status`!='0' AND job_master.`cust_fk`='" . $data['query'][0]["custid"] . "' and job_master.id not in (" . $data['query'][0]["id"] . ") ORDER BY job_master.id DESC LIMIT 0,20");
        /* END */
        if ($data['query'][0]["test_city"] > 0) {
            $test_city_data = $this->job_model->get_val("SELECT * FROM `test_cities` WHERE `id`='" . $data['query'][0]["test_city"] . "'");
            $data['query'][0]["test_city_name"] = $test_city_data[0]["name"];
            $data['query'][0]["test_city_code"] = $test_city_data[0]["code"];
            $data['query'][0]["test_city_city_fk"] = $test_city_data[0]["city_fk"];
        }
        $data["sampleresone"] = $this->job_model->get_val("SELECT id,reason FROM `samplereject_reason` WHERE STATUS='1'");

        $data["sample_collect_date"] = $this->job_model->get_val("SELECT created_date FROM `test_sample_barcode` WHERE job_fk='" . $data['cid'] . "' AND status='1' order by id desc");
        $data["sample_collect_time"] = $this->job_model->get_val("SELECT created_date FROM `test_sample_barcode` WHERE job_fk='" . $data['cid'] . "' AND status='1' order by id desc");

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('job_details', $data);
        $this->load->view('footer');
    }

    function job_details_test() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $this->uri->segment(3);
        $data['success'] = $this->session->flashdata("success");
        $data['error'] = $this->session->flashdata("error");
        $data['family_error'] = $this->session->flashdata("family_error");
        $data['amount_history_success'] = $this->session->flashdata("amount_history_success");


        if ($this->session->userdata("amount_history_success")) {
            $data['amount_history_success'] = $this->session->userdata("amount_history_success");
            $this->session->unset_userdata("amount_history_success");
        }
        $data['query'] = $this->job_model->job_details($data['cid']);
        $data["b2b_job_detais"] = array();
        if (!empty($data['query'][0]["b2b_id"])) {
            $data["b2b_job_detais"] = $this->job_model->master_fun_get_tbl_val("sample_job_master", array('barcode_fk' => $data['query'][0]["b2b_id"]), array("id", "asc"));
            $data["b2b_job_master_receiv_amount"] = $this->job_model->get_val("SELECT 
  `sample_job_master_receiv_amount`.*,admin_master.name 
FROM
  `sample_job_master_receiv_amount` 
  LEFT JOIN `admin_master` 
    ON `sample_job_master_receiv_amount`.`added_by` = `admin_master`.`id` 
WHERE sample_job_master_receiv_amount.job_fk = '" . $data["b2b_job_detais"][0]["id"] . "' 
  AND sample_job_master_receiv_amount.status = '1'");
            //echo "<pre>"; print_r($data["b2b_job_detais"]); die();
        }
        $data['branchdis'] = $data['query'][0]["branch_fk"];
        $data["selected_creditor"] = $this->job_model->get_val("SELECT job_creditors.`amount`,job_creditors.`creditors_fk`,job_creditors.job_fk,`creditors_master`.`name`,`creditors_master`.`mobile` FROM `job_creditors` INNER JOIN `creditors_master` ON `creditors_master`.`id`=`job_creditors`.`creditors_fk` WHERE `job_creditors`.`status`='1' AND `job_creditors`.`job_fk`='" . $data["cid"] . "'");
        /* Check Barcode Start */
        if (empty($data['query'][0]["barcode"])) {
            $b_data = $this->job_model->master_fun_get_tbl_val("branch_master", array('id' => $data['query'][0]["branch_fk"]), array("id", "asc"));
            $barcode = $data['query'][0]["test_city"] . $b_data[0]["branch_code"] . $data["cid"];
            $barcode = $data["cid"];
            $this->job_model->master_fun_update_multi("job_master", array("id" => $data["cid"]), array("barcode" => $barcode));
            $data['query'][0]["barcode"] = $barcode;
        }
        $data['query'][0]["panel_test"] = $this->job_model->get_val("SELECT id FROM `booked_job_test` WHERE job_fk='" . $data["cid"] . "' AND status='1' AND `panel_fk` IS NOT NULL");
        /* END */
//print_r($data['query']); die();
        $this->load->library("util");
        $util = new Util;
        $data["age"] = $util->get_age($data['query'][0]["dob"]);
        if ($data['query'][0]["payment_type"] == 'PayUMoney') {
            $data['payumoney_details'] = $this->job_model->master_fun_get_tbl_val("payment", array('job_fk' => $data['cid']), array("id", "asc"));
        }
        $data["creditors"] = $this->job_model->get_val("select cm.* from creditors_master cm join creditors_branch cb on cb.creditors_fk=cm.id where cm.status=1 and cb.status=1 and cb.branch_fk='" . $data['query'][0]['branch_fk'] . "' group by cm.id order by cm.name ASC");
        //$data['creditors'] = $this->job_model->master_fun_get_tbl_val("creditors_master", array('status' => '1', "branch_fk" => $data['query'][0]['branch_fk']), array("name", "asc"));
        $data['report'] = $this->job_model->master_fun_get_tbl_val("report_master", array('status' => 1, "job_fk" => $data['cid'], "type !=" => "c"), array("id", "asc"));
        $data['common_report'] = $this->job_model->master_fun_get_tbl_val("report_master", array('status' => 1, "job_fk" => $data['cid'], "type" => "c"), array("id", "asc"));
        $data['city'] = $this->job_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
        $data['state'] = $this->job_model->master_fun_get_tbl_val("state", array('status' => 1), array("id", "asc"));
        $data['country'] = $this->job_model->master_fun_get_tbl_val("country", array('status' => 1), array("id", "asc"));
        $data['package_price'] = $this->job_model->master_fun_get_tbl_val("package_master_city_price", array('status' => 1), array("id", "asc"));
        $data['phlebo_list'] = $this->job_model->master_fun_get_tbl_val("phlebo_master", array('status' => 1, "test_city" => $data['query'][0]["test_city"]), array("id", "asc"));
        $data['phlebo_assign_job'] = $this->job_model->master_fun_get_tbl_val("phlebo_assign_job", array('status' => 1, "job_fk" => $this->uri->segment(3)), array("id", "asc"));
        $p_prc = 0;
        foreach ($data['phlebo_assign_job'] as $pkey) {
            if ($pkey["time_fk"]) {
                $time_slot = $this->job_model->master_fun_get_tbl_val("phlebo_time_slot", array('status' => "1", "id" => $pkey["time_fk"]), array("id", "asc"));
                $s_time = date('h:i a', strtotime($time_slot[0]["start_time"]));
                $e_time = date('h:i a', strtotime($time_slot[0]["end_time"]));
                $data['phlebo_assign_job'][$p_prc]["time"] = $s_time . " TO " . $e_time;
            }
            $data['phlebo_assign_job'][$p_prc]["time1"] = $pkey["time"];
            $p_prc++;
        }
        $data["emergency_tests"] = $this->job_model->master_fun_get_tbl_val("booking_info", array('id' => $data['query'][0]["booking_info"]), array("id", "asc"));
        $data["customer_info"] = $this->job_model->master_fun_get_tbl_val("customer_master", array('id' => $data['query'][0]["custid"]), array("id", "asc"));
        $data["booking_info"] = $this->job_model->get_val("SELECT 
      `booking_info`.*,
      TIME_FORMAT(
        `phlebo_time_slot`.`start_time`,
        '%l:%i %p'
      ) AS `start_time`,
      TIME_FORMAT(
        `phlebo_time_slot`.`end_time`,
        '%l:%i %p'
      ) AS `end_time`,
      `customer_family_master`.`name` 
    FROM
      `booking_info` 
      left JOIN `phlebo_time_slot` 
        ON `booking_info`.`time_slot_fk` = `phlebo_time_slot`.`id` 
      LEFT JOIN `customer_family_master` 
        ON `booking_info`.`family_member_fk` = `customer_family_master`.`id` where booking_info.id='" . $data['query'][0]["booking_info"] . "'");
        $f_data = $this->job_model->master_fun_get_tbl_val("customer_family_master", array('id' => $data["emergency_tests"][0]["family_member_fk"]), array("id", "asc"));
        $f_data1 = $this->job_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
        $data["family_data"] = $f_data;
        $data["relation"] = "Self";
        $data["family_fk"] = $f_data;
        if (!empty($f_data1)) {
            $data["relation"] = ucfirst($f_data[0]["name"] . " (" . $f_data1[0]["name"] . ")");
            $data["f_age"] = $util->get_age($f_data[0]["dob"]);
        }
        $update = $this->job_model->master_fun_update("job_master", array('id', $data['cid']), array("views" => "1"));
        $booked_tests = $this->job_model->master_fun_get_tbl_val("wallet_master", array('job_fk' => $data['cid']), array("id", "asc"));
        $w_prc = 0;
        foreach ($booked_tests as $tkey) {
            if ($tkey["debit"]) {
                $w_prc = $w_prc + $tkey["debit"];
            }
        }
        $test1 = array();
        $tid = explode(",", $data['query'][0]['testid']);
        foreach ($tid as $tst_id) {
            $para = $this->job_model->get_val("SELECT p.id as pid,p.test_fk,p.parameter_name,p.parameter_range,p.parameter_unit,g.id as gid,g.parameter_fk,g.subparameter_name,g.subparameter_range,g.subparameter_unit FROM test_parameter_master as p LEFT JOIN parameter_group_master as g ON p.id=g.parameter_fk WHERE p.status='1' AND p.test_fk='" . $tst_id . "' order by p.id ASC");
            array_push($test1, $para);
        }
        $data["job_master_receiv_amount"] = $this->job_model->get_val("SELECT 
  `job_master_receiv_amount`.*,
  IF(
    `job_master_receiv_amount`.`phlebo_fk` > 0,
    CONCAT(phlebo_master.`name`,'',' (Phlebo)'),`admin_master`.`name`
  ) AS `name`,
  payment_type_master.`name` AS pay_via 
FROM
  `job_master_receiv_amount` 
  LEFT JOIN `admin_master` 
    ON `job_master_receiv_amount`.`added_by` = `admin_master`.`id` 
  LEFT JOIN `payment_type_master` 
    ON `payment_type_master`.`id` = `job_master_receiv_amount`.`payment_type` 
    LEFT JOIN `phlebo_master` 
    ON `phlebo_master`.`id`=`job_master_receiv_amount`.`phlebo_fk`
WHERE job_master_receiv_amount.job_fk = '" . $data['cid'] . "' 
  AND job_master_receiv_amount.status = '1'");
        $data['relation_list'] = $this->job_model->get_val("SELECT 
  `customer_family_master`.*,
  `relation_master`.`name` AS relation_name 
FROM
  `customer_family_master` 
  INNER JOIN `relation_master` 
    ON `customer_family_master`.`relation_fk` = `relation_master`.`id` 
WHERE `customer_family_master`.`status` = '1' 
  AND `relation_master`.`status` = '1' 
  AND `customer_family_master`.`user_fk` = '" . $data['query'][0]["custid"] . "'");
        if (strlen($data["query"][0]["other_reference"]) < 3) {
            $source_data = $this->job_model->master_fun_get_tbl_val("source_master", array('id' => $data["query"][0]["other_reference"]), array("id", "asc"));
            $data["query"][0]["other_reference"] = $source_data[0]["name"];
        }
        if (empty($data["query"][0]["other_reference"])) {
            $data["query"][0]["other_reference"] = 'NA';
        }
        /* Nishit 11-8 start */
        if ($data["login_data"]["type"] != 1 && $data["login_data"]["type"] != 2) {
            $permission = $this->job_model->get_val("SELECT is_print FROM `report_print_permission` WHERE `status`='1' AND `type`='" . $data["login_data"]["type"] . "' AND branch='" . $data['query'][0]["branch_fk"] . "'");
            $data['query'][0]["is_print"] = $permission[0]["is_print"];
        } else {
            $data['query'][0]["is_print"] = 1;
        }
        /* End */
//echo "<pre>"; print_r($data['query']); die();
//        $data["creditors_pay"] = $this->job_model->get_val("SELECT `job_creditors`.*,admin_master.name FROM job_creditors LEFT JOIN `admin_master` ON `job_creditors`.`added_by` = `admin_master`.`id` where job_creditors.status=1 and job_creditors.job_fk='" . $data['cid'] . "'");
        $data["relation1"] = $this->job_model->master_fun_get_tbl_val("relation_master", array('status' => "1"), array("name", "asc"));
        $data["payment_type_list"] = $this->job_model->master_fun_get_tbl_val("payment_type_master", array('status' => "1"), array("name", "asc"));
        $data["online_pay"] = $this->job_model->master_fun_get_tbl_val("payment", array('status' => "success", "type" => "job", "job_fk" => $data['cid']), array("id", "asc"));
        $data["phlebo_collect"] = $this->job_model->get_val("SELECT `phlebo_collect_amount`.*,`phlebo_master`.`name` FROM `phlebo_collect_amount` INNER JOIN phlebo_master ON `phlebo_collect_amount`.`phlebo_fk`= `phlebo_master`.`id` WHERE `phlebo_collect_amount`.`status`='1' AND job_fk='" . $data['cid'] . "'");
        $data["phlebo_rate"] = $this->job_model->get_val("SELECT   `phlebo_rate`.*,  `phlebo_master`.`name` FROM  `phlebo_rate`   INNER JOIN `phlebo_master`     ON `phlebo_rate`.`phlebo_fk` = `phlebo_master`.`id` WHERE `phlebo_rate`.`status`='1' AND `phlebo_master`.`status`='1' AND job_fk='" . $data['cid'] . "' order by phlebo_rate.id desc");
        $data['parameter_list'] = $test1;
        $data['branch_list'] = $this->registration_admin_model->get_val("SELECT * from branch_master where  status='1' and city='" . $data['query'][0]["test_city"] . "'");
        $data["discount_added_by"] = $this->job_model->get_val("SELECT job_log.`updated_by`,`admin_master`.`name` FROM `job_log` INNER JOIN `admin_master` ON `job_log`.`updated_by`=`admin_master`.`id` WHERE `job_log`.`job_fk`='" . $data['cid'] . "' AND `job_log`.`message_fk`='24' order by job_log.id desc");
        $job_test_list = $this->job_model->get_val("SELECT `job_test_list_master`.*,`test_master`.`test_name` FROM `job_test_list_master` INNER JOIN `test_master` ON `job_test_list_master`.`test_fk`=`test_master`.`id` WHERE `job_test_list_master`.`job_fk`='" . $data["cid"] . "'");
        $data['query'][0]["job_test_list"] = array();
        foreach ($job_test_list as $t_key) {
            $sub_test_list = $this->job_model->get_val("SELECT `sub_test_master`.*,`test_master`.`test_name` FROM `sub_test_master` INNER JOIN test_master ON `sub_test_master`.`sub_test`=test_master.`id` WHERE `sub_test_master`.`status`='1' AND `test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $t_key["test_fk"] . "'");
            $t_key["sub_test"] = $sub_test_list;
            $data['query'][0]["job_test_list"][] = $t_key;
        }

        $selected_package = $this->registration_admin_model->get_val("SELECT `book_package_master`.*,`package_master`.`title` FROM `book_package_master` INNER JOIN `package_master` ON `book_package_master`.`package_fk`=`package_master`.`id` WHERE `book_package_master`.`status`='1' AND `package_master`.`status`='1' AND `book_package_master`.`job_fk`='" . $data["cid"] . "'");
        $data["selected_package"] = array();
        foreach ($selected_package as $pkey) {
            $package_test_list = $this->registration_admin_model->get_val("SELECT 
  `package_test`.*,
  `test_master`.`test_name` 
FROM
  `package_test` 
  INNER JOIN `test_master` 
    ON `package_test`.`test_fk` = `test_master`.`id` 
WHERE `package_test`.`status` = '1' 
  AND `test_master`.`status` = '1' 
  AND `package_test`.`package_fk` = '" . $pkey["package_fk"] . "'");
            $pkey["test_list"] = $package_test_list;
            $data["selected_package"][] = $pkey;
        }
        //$data["sample_from"] = $this->job_model->get_val("SELECT job_master.`id`,`job_master`.`sample_from`,`sample_from`.`name` FROM job_master INNER JOIN `sample_from` ON `job_master`.`sample_from`=sample_from.`id` WHERE `sample_from`.`status`='1' AND job_master.id='" . $data['cid'] . "'");
        $prnt_cnt = $this->job_model->get_val("SELECT id FROM `booked_job_test` WHERE job_fk='" . $data['cid'] . "' AND status='1' AND `panel_fk` IS NOT NULL");
        $data['query'][0]["panel_test_count"] = count($prnt_cnt);
//echo "<pre>"; print_R($data["query"]); die();
        $data["cut_from_wallet"] = $w_prc;
        /* B2B creditor list start */
        if (!empty($data['query'][0]["b2b_id"])) {
            $data["user_branch"] = $this->job_model->get_val("SELECT GROUP_CONCAT(branch_fk) AS branch FROM `user_branch` WHERE `status`='1' AND user_fk='" . $data["login_data"]["id"] . "' GROUP BY user_fk");
            if (!empty($data["user_branch"]) && !in_array($data["login_data"]["type"], array("1", "2"))) {
                $data["creditors"] = $this->job_model->get_val("select cm.* from sample_creditors_master cm join sample_creditors_branch cb on cb.creditors_fk=cm.id where cm.status=1 and cb.status=1 and cb.branch_fk in (" . $data["user_branch"][0]["branch"] . ") group by cm.id order by cm.name ASC");
            } else {
                $data["creditors"] = $this->job_model->get_val("select cm.* from sample_creditors_master cm join sample_creditors_branch cb on cb.creditors_fk=cm.id where cm.status=1 and cb.status=1 group by cm.id order by cm.name ASC");
            }
            $data["selected_creditor"] = $this->job_model->get_val("SELECT sample_job_creditors.`amount`,sample_job_creditors.`creditors_fk`,sample_job_creditors.job_fk,`sample_creditors_master`.`name`,`sample_creditors_master`.`mobile` FROM `sample_job_creditors` INNER JOIN `sample_creditors_master` ON `sample_creditors_master`.`id`=`sample_job_creditors`.`creditors_fk` WHERE `sample_job_creditors`.`status`='1' AND `sample_job_creditors`.`job_fk`='" . $data["b2b_job_detais"][0]["id"] . "'");
        }
        /* END */
        /* Nishit old report check start */
        $data["old_report"] = $this->job_model->get_val("SELECT job_master.id,job_master.order_id,report_master.`report`,report_master.`original`,`report_master`.without_laterpad,`report_master`.without_laterpad_original FROM job_master INNER JOIN `report_master` ON `report_master`.`job_fk`=`job_master`.`id` AND `report_master`.`status`='1' WHERE job_master.`status`!='0' AND job_master.`cust_fk`='" . $data['query'][0]["custid"] . "' and job_master.id not in (" . $data['query'][0]["id"] . ") ORDER BY job_master.id DESC LIMIT 0,20");
        /* END */
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('job_details_test', $data);
        $this->load->view('footer');
    }

    function job_note_update() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $job_note = $this->input->post("job_note");
        $job_id = $this->input->post("job_id");
        if ($job_note != '' && $job_id != '') {
            $this->job_model->master_fun_update("job_master", array('id', $job_id), array("note" => $job_note));
            $this->job_model->master_fun_insert("job_log", array("job_fk" => $job_id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => "5", "date_time" => date("Y-m-d H:i:s")));
            echo json_encode(array("status" => "1", "msg" => "<span style='color:green;'>Note successfully saved.</span>"));
        } else {
            echo json_encode(array("status" => "0", "msg" => "<span style='color:red;'>Invalid parameter.</span>"));
        }
    }

    function job_mark_spam() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->job_model->master_fun_update("job_master", array("id", $cid), array("status" => "3"));
        $this->job_model->master_fun_insert("job_log", array("job_fk" => $cid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => "4", "date_time" => date("Y-m-d H:i:s")));
        $data = $this->job_model->master_fun_get_tbl_val("job_master", array("id" => $cid), array("id", "asc"));
        $cust_fk = $data[0]['cust_fk'];

        $data1 = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));
        $query = $this->job_model->job_details($cid);
        $testid = $query[0]['id'];
//		$testname = str_replace(","," ",$query[0]['testname']);
        $testname = $query[0]['testname'];
        $testprice = $query[0]['price'];
        $testdate = $query[0]['date'];
        $device_id = $data1[0]['device_id'];
        $device_id = $data1[0]['device_id'];
        $mobile = $data1[0]['mobile'];
        $orderid = $data[0]['order_id'];
        $message = "Your Report has been Spam";
        if ($device_type == 'android') {
            //$notification_data=array("title" => "Patholab","message" =>$message,"type"=>"spam");
            $notification_data = array("title" => "AirmedLabs", "message" => $message, "type" => "spam", "testid" => $testid, "testname" => $testname, "testprice" => $testprice, "testdate" => $testdate, "order_id" => $orderid);
//print_r($notification_data); die();
            $pushServer = new PushServer();
            $pushServer->pushToGoogle($device_id, $notification_data);
            $device_type = $data1[0]['device_type'];
        }
        if ($device_type == 'ios') {
            $url = 'http://website-demo.in/patholabpush/push.php?device_id=' . $device_id . '&msg=' . $message . '&type=spam&testid=' . $testid . '&testname=' . $testname . '&testprice=' . $testprice . '&testdate=' . $testdate . '&orderid=' . $orderid . '';
            $url = str_replace(" ", "%20", $url);
            $data = $this->get_content($url);
            $data2 = json_decode($data);
        }
        /* Nishit send sms code start */
        $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "Span_report"), array("id", "asc"));
        $sms_message = preg_replace("/{{NAME}}/", ucfirst($data1[0]["full_name"]), $sms_message[0]["message"]);
        $sms_message = preg_replace("/{{OID}}/", $orderid, $sms_message);
        $sms_message = preg_replace("/{{PRICE}}/", "Rs." . $testprice, $sms_message);
        $this->load->helper("sms");
        $notification = new Sms();
        $notification::send($mobile, $sms_message);
        /* Nishit send sms code end */

        $this->session->set_flashdata("success", array("Job successfully mark as Spam."));
        if (!empty($this->session->userdata("job_master_r"))) {
            redirect($this->session->userdata("job_master_r"), "refresh");
        } else {
            redirect("job-master/pending-list", "refresh");
        }
    }

    function testme() {
        $pushServer = new PushServer();
        $notification_data = array("title" => "AirmedLabs", "message" => "this is test massage notification", "type" => "completed");
        $device = "APA91bHqOvx5_EqEp8UUcVOhnhEF9t-AjOe6hddRjADf-hIMytTrvJ_5Wq3iNxXW_mgPmMopGH7_NvMxS_DHXUJiVhSoq3X0aZhIPjIb4mEC3DzLecBJS8w";
        echo $res = $pushServer->pushToGoogle($device, $notification_data);
//$res = $pushServer->pushToGoogle("APA91bHqOvx5_EqEp8UUcVOhnhEF9t-AjOe6hddRjADf-hIMytTrvJ_5Wq3iNxXW_mgPmMopGH7_NvMxS_DHXUJiVhSoq3X0aZhIPjIb4mEC3DzLecBJS8w",$notification_data);
        //  $url = 'http://website-demo.in/chatonpush/push.php?device_id='.$ios.'&msg='.$message['message'].'&user_id='.$user_id.'&type='.$type;
        //$url = str_replace(" ","%20",$url);
        //$data = $this->get_content($url);
        //$data2 = json_decode($data);
    }

    function testios() {

        echo $url = 'http://website-demo.in/patholabpushtest/push.php?device_id=8b6465df4803375109e78460508d4a26758f932451b6228c37b42e6563d33b18
&msg=this is test massage notification&type=suggested_test&id=&desc=&date=';
        $url = str_replace(" ", "%20", $url);
        $data = $this->get_content($url);
        $data2 = json_decode($data);
    }

    function job_mark_pending() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->job_model->master_fun_update("job_master", array("id", $cid), array("status" => "1"));
        $this->job_model->master_fun_insert("job_log", array("job_fk" => $cid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => "5", "date_time" => date("Y-m-d H:i:s")));
        $data = $this->job_model->master_fun_get_tbl_val("job_master", array("id" => $cid), array("id", "asc"));
        $cust_fk = $data[0]['cust_fk'];

        $data1 = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));
        $device_id = $data1[0]['device_id'];
        $mobile = $data1[0]['mobile'];
        $device_type = $data1[0]['device_type'];
        $query = $this->job_model->job_details($cid);
        $testid = $query[0]['id'];
        $testname = $query[0]['testname'];
        $testprice = $query[0]['price'];
        $testdate = $query[0]['date'];
        $orderid = $query[0]['order_id'];
        $message = "Your Report has been Pending";
        if ($device_type == 'android') {
            //$notification_data=array("title" => "Patholab","message" =>$message,"type"=>"Pending");
            $notification_data = array("title" => "AirmedLabs", "message" => $message, "type" => "Pending", "testid" => $testid, "testname" => $testname, "testprice" => $testprice, "testdate" => $testdate, "order_id" => $orderid);
            $pushServer = new PushServer();
            $pushServer->pushToGoogle($device_id, $notification_data);
        }
        if ($device_type == 'ios') {
            $url = 'http://website-demo.in/patholabpush/push.php?device_id=' . $device_id . '&msg=' . $message . '&type=pending&testid=' . $testid . '&testname=' . $testname . '&testprice=' . $testprice . '&testdate=' . $testdate . '&orderid=' . $orderid . '';
            $url = str_replace(" ", "%20", $url);
            $data = $this->get_content($url);
            $data2 = json_decode($data);
        }
        /* Nishit send sms code start */
        $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "Pending_Report"), array("id", "asc"));
        $sms_message = preg_replace("/{{NAME}}/", ucfirst($data1[0]["full_name"]), $sms_message[0]["message"]);
        $sms_message = preg_replace("/{{OID}}/", $orderid, $sms_message);
        $sms_message = preg_replace("/{{PRICE}}/", "Rs." . $testprice, $sms_message);
        $this->load->helper("sms");
        $notification = new Sms();
        $notification::send($mobile, $sms_message);
        /* Nishit send sms code end */
        $this->session->set_flashdata("success", array("Job successfully mark as pending."));
        if (!empty($this->session->userdata("job_master_r"))) {
            redirect($this->session->userdata("job_master_r"), "refresh");
        } else {
            redirect("job-master/pending-list", "refresh");
        }
    }

    function job_mark_completed($cid) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->library("util");
        $util = new Util;
        $this->load->helper("Email");
        $email_cnt = new Email;
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        // $cid = $this->uri->segment('3');
        $update = $this->job_model->master_fun_update("job_master", array("id", $cid), array("status" => "2"));
        //$this->job_model->master_fun_insert("job_log", array("job_fk" => $cid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => "3", "date_time" => date("Y-m-d H:i:s")));
        if ($update) {
            $data = $this->job_model->master_fun_get_tbl_val("job_master", array("id" => $cid), array("id", "asc"));
            $notify_customer = $data[0]["notify_cust"];
            $payable_amount = $data[0]["payable_amount"];

            $branch = $data[0]["branch_fk"];
            $getbranchalert = $this->job_model->get_val("SELECT smsalert,emailalert FROM branch_master WHERE id='$branch' AND STATUS='1'");

            $smsalert = $getbranchalert[0]["smsalert"];
            $emailalert = $getbranchalert[0]["emailalert"];

            $cust_fk = $data[0]['cust_fk'];
            $data1 = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));
            $device_id = $data1[0]['device_id'];
            $mobile = $data1[0]['mobile'];
            $device_type = $data1[0]['device_type'];
            $name = $data1[0]['full_name'];
            $email = $data1[0]['email'];
            $query = $this->job_model->job_details($cid);
            $testid = $query[0]['id'];
            $testname = $query[0]['testname'];
            $testprice = $query[0]['price'];
            $testdate = $query[0]['date'];
            $orderid = $query[0]['order_id'];
            $message = "Your Report has been Completed";
            $family_member_name = $this->job_model->get_family_member_name($cid);
            if (!empty($family_member_name)) {
                $cmobile = $family_member_name[0]["phone"];
                if (empty($cmobile)) {
                    $cmobile = $data1[0]['mobile'];
                }
            } else {
                $cmobile = $data1[0]['mobile'];
            }

            $report = $this->job_model->master_fun_get_tbl_val("report_master", array("job_fk" => $cid), array("id", "asc"));
            $nw_ary = array();
            foreach ($report as $rkey) {
                if ($rkey['type'] == 'c') {
                    $nw_ary = $rkey;
                }
            }
            if (!empty($nw_ary)) {
                $report = array($nw_ary);
            }
            //print_R($report); die();
            $config['mailtype'] = 'html';

            $this->email->initialize($config);
            $family_member_name = $this->job_model->get_family_member_name($cid);
            if (!empty($family_member_name)) {
                $name = $family_member_name[0]["name"];
            }
            /* Nishit cashback start */
            $job_details = $this->get_job_details($cid);
            $checkPanelTes = $this->job_model->get_val("SELECT id FROM `booked_job_test` WHERE `status`='1' AND test_fk LIKE '%pt-%' AND job_fk='" . $cid . "'");
            $check_job_Cashback = $this->job_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "job_fk" => $cid, "credit >" => 0, "type" => "Case Back"), array("id", "desc"));
            if ($job_details[0]["status"] != '0' && empty($check_job_Cashback) && empty($checkPanelTes)) {
                /* Nishit cashback end */
                $book_test_details = array();
                foreach ($job_details[0]["book_test"] as $bkey) {
                    $book_test_details[] = $bkey["test_name"];
                }
                foreach ($job_details[0]["book_package"] as $bkey) {
                    $book_test_details[] = $bkey["title"];
                }
                if ($notify_customer == 1) {

                    if ($payable_amount <= 0 || $payable_amount == '') {
                        $this->check_send_report($cid, $smsalert);
                    } else {
                        if ($emailalert == 1) {
                            $message1 = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $name . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Report completed successfully for test ' . implode(",", $book_test_details) . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Report ID : ' . $orderid . ' </p>
                            <p style="color:#7e7e7e;font-size:13px;">Please check your due amount using below link and get report.</p>
                            <p style="color:#7e7e7e;font-size:13px;"><a href="' . base_url() . 'u/j/' . $cid . '" target="_blank">Click Here</a> for pay.</p>
		<p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';

                            $c_email = array();
                            $message1 = $email_cnt->get_design($message1);
                            $c_email[] = $email;
                            $this->email->to(implode(",", $c_email));
                            /* if($branch==4){
                              $this->email->cc('pagaravpath@gmail.com'); //pagaravpath@gmail.com for Gandhinagar-Pagarav Branch
                              } */
                            //  $this->email->to('jeel@virtualheight.com');
                            $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
                            $this->email->subject("Report Completed");
                            $this->email->message($message1);
                            //$this->email->attach(implode(',',$attatchPath));
                            $this->email->send();

                            $family_member_name = $this->job_model->get_family_member_name($cid);
                            $c_email = array();
                            if (!empty($family_member_name)) {
                                $c_email[] = $family_member_name[0]["email"];
                                if (!empty($c_email)) {
                                    $this->email->to(implode(",", $c_email));
                                    /* if($branch==4){
                                      $this->email->cc('pagaravpath@gmail.com'); //pagaravpath@gmail.com for Gandhinagar-Pagarav Branch
                                      } */
                                    //  $this->email->to('jeel@virtualheight.com');
                                    $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
                                    $this->email->subject("Report Completed");
                                    $this->email->message($message1);
                                    if ($payable_amount <= 0 || $payable_amount == '') {
                                        $attatchPath = "";
                                        foreach ($report as $key) {
                                            $attatchPath = base_url() . "upload/report/" . $key['report'];
                                            $this->email->attach($attatchPath);
                                        }
                                    }
                                    //$this->email->attach(implode(',',$attatchPath));
                                    $this->email->send();
                                }
                            }
                        }
                    }
                }
            }

            /* Nishit check job discount start 
              if ($job_details[0]["discount"] > 0) {
              $order_discount = round($job_details[0]["price"] * $job_details[0]["discount"] / 100);
              $payable_price = $job_details[0]["price"] - round($job_details[0]["price"] * $job_details[0]["discount"] / 100);
              $c_message = "Dear customer, Your \n Booking ID : " . $job_details[0]["order_id"] . "\n Total price : Rs." . $job_details[0]["price"] . " \n Discount price : Rs." . $order_discount . " \n Payable price : Rs." . $payable_price . " \n For any query please call (8101161616).\n Thanks for using Airmed.";
              $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $mobile, "message" => $c_message, "created_date" => date("Y-m-d H:i:s")));
              }
              END */

            $this->session->set_flashdata("success", array("Job successfully mark as completed."));
            //redirect("job-master/pending-list", "refresh");
        }
    }

    function check_send_report($cid, $smsalert) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->library("util");
        $util = new Util;
        $this->load->helper("Email");
        $email_cnt = new Email;
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $jdata = $this->job_model->master_fun_get_tbl_val("job_master", array("id" => $cid), array("id", "asc"));
        $checkPanelTes = $this->job_model->get_val("SELECT id FROM `booked_job_test` WHERE `status`='1' AND test_fk LIKE '%pt-%' AND job_fk='" . $cid . "'");
        $check_job_Cashback = $this->job_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "job_fk" => $cid, "credit >" => 0, "type" => "Case Back"), array("id", "desc"));
        if ($jdata[0]["status"] == 2 && ($jdata[0]["payable_amount"] <= 0 || $jdata[0]["payable_amount"] == '') && empty($check_job_Cashback) && empty($checkPanelTes)) {
            //die("ok2");
            $data = $this->job_model->master_fun_get_tbl_val("job_master", array("id" => $cid), array("id", "asc"));
            $assign_doctor = $this->job_model->master_fun_get_tbl_val("doctor_master", array("id" => $data[0]["doctor"]), array("id", "asc"));
            $notify_customer = $data[0]["notify_cust"];
            $payable_amount = $data[0]["payable_amount"];
            $cust_fk = $data[0]['cust_fk'];
            $branch = $data[0]['branch_fk'];
            $data1 = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));
            $device_id = $data1[0]['device_id'];
            $mobile = $data1[0]['mobile'];
            $device_type = $data1[0]['device_type'];
            $name = $data1[0]['full_name'];
            $email = $data1[0]['email'];
            $query = $this->job_model->job_details($cid);
            $testid = $query[0]['id'];
            $testname = $query[0]['testname'];
            $testprice = $query[0]['price'];
            $testdate = $query[0]['date'];
            $orderid = $query[0]['order_id'];
            $message = "Your Report has been Completed";

            if ($device_type == 'android' && $notify_customer == 1) {
                //$notification_data=array("title" => "Patholab","message" =>$message,"type"=>"completed");
                $notification_data = array("title" => "AirmedLabs", "message" => $message, "type" => "completed", "testid" => $testid, "testname" => $testname, "testprice" => $testprice, "testdate" => $testdate, "order_id" => $orderid);
                $pushServer = new PushServer();
                $pushServer->pushToGoogle($device_id, $notification_data);
            }
            if ($device_type == 'ios' && $notify_customer == 1) {
                $url = 'http://website-demo.in/patholabpush/push.php?device_id=' . $device_id . '&msg=' . $message . '&type=completed&testid=' . $testid . '&testname=' . $testname . '&testprice=' . $testprice . '&testdate=' . $testdate . '&orderid=' . $orderid . '';
                $url = str_replace(" ", "%20", $url);
                $data = $this->get_content($url);
                //die();
                $data2 = json_decode($data);
            }
            /* Nishit send sms code start */
            $family_member_name = $this->job_model->get_family_member_name($cid);
            if (!empty($family_member_name)) {
                $cmobile = $family_member_name[0]["phone"];
                if (empty($cmobile)) {
                    $cmobile = $data1[0]['mobile'];
                }
            } else {
                $cmobile = $data1[0]['mobile'];
            }
            if ($smsalert == 1) {
                $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "Completed_Report"), array("id", "asc"));
                // $sms_message = preg_replace("/{{NAME}}/", ucfirst($data1[0]["full_name"]), $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{OID}}/", $orderid, $sms_message[0]["message"]);
                //$sms_message = preg_replace("/{{PRICE}}/", "Rs." . $testprice, $sms_message);
                if ($notify_customer == 1) {
                    $this->load->helper("sms");
                    $notification = new Sms();
                    $notification->send($cmobile, $sms_message);
                    if (!empty($family_member_name) && ($payable_amount <= 0 || $payable_amount == '') && empty($checkPanelTes)) {
                        $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $mobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                    }
                }
            }
            /* Nishit send sms code end */
            $report = $this->job_model->get_val("select * from report_master where job_fk='" . $cid . "' order by id desc limit 0,1");
            $nw_ary = array();
            foreach ($report as $rkey) {
                if ($rkey['type'] == 'c') {
                    $nw_ary = $rkey;
                }
            }
            if (!empty($nw_ary)) {
                $report = array($nw_ary);
            }
            //print_R($report); die();
            $config['mailtype'] = 'html';

            $this->email->initialize($config);
            $family_member_name = $this->job_model->get_family_member_name($cid);
            if (!empty($family_member_name)) {
                $name = $family_member_name[0]["name"];
            }
            /* Nishit cashback start */
            $job_details = $this->get_job_details($cid);
            $b_details = array();
            foreach ($job_details[0]["book_test"] as $bkey) {
                //$b_details[] = $bkey["test_name"] . " Rs." . $bkey["price"];
            }
            $test_prc = 0;
            foreach ($job_details[0]["book_test"] as $t_key) {
                $tst_prc = $this->job_model->get_val("SELECT price FROM `booked_job_test` WHERE `status`='1' AND job_fk='" . $cid . "' AND `test_fk`='t-" . $t_key["id"] . "'");
                //$return_test_price = $util->getTEstPrice($cid, array($t_key["id"]));
                $test_prc = $test_prc + $tst_prc[0]["price"];
            }
            if ($job_details[0]["status"] != '0') {
                if (!empty($job_details[0]["book_test"]) && $job_details[0]["discount"] != '100' && in_array($jdata[0]["added_by"], array("", "0")) && in_array($jdata[0]["phlebo_added"], array("", "0"))) {
                    $query_cb = $this->job_model->master_fun_get_tbl_val("offer_master", array("status" => 1), array("id", "desc"));
                    $caseback_per = $query_cb[0]['caseback_per'];
                    $query_ct = $this->job_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $job_details[0]["cust_fk"]), array("id", "desc"));
                    $query_cbt = $this->job_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "job_fk" => $cid, "debit >" => 0), array("id", "desc"));
                    $total = $query_ct[0]['total'];
                    $dprice1 = $job_details[0]["discount"] * $job_details[0]["price"] / 100;
                    $price = $test_prc - $dprice1 - $query_cbt[0]["debit"];
                    $caseback_amount = round(($price * $caseback_per) / 100);
                    if ($caseback_amount > 0) {
                        $data = array(
                            "cust_fk" => $cust_fk,
                            "credit" => $caseback_amount,
                            "total" => $total + $caseback_amount,
                            "type" => "Case Back",
                            "job_fk" => $cid,
                            "created_time" => date('Y-m-d H:i:s')
                        );
                        if ($test_prc != 0) {
                            $insert1 = $this->job_model->master_fun_insert("wallet_master", $data);
                        }
                        $query = $this->job_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $job_details[0]["cust_fk"]), array("id", "desc"));
                        $Current_wallet = $query[0]['total'];
// Case Back Email start
                        $config['mailtype'] = 'html';
                        $this->email->initialize($config);

                        $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $name . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Cashback Credited in your Wallet. </p>
                        <p style="color:#7e7e7e;font-size:13px;"> Rs. ' . $caseback_amount . ' Credited in your account. </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Current Wallet Amount is Rs. ' . $Current_wallet . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                        $message = $email_cnt->get_design($message);
                        $this->email->to($email);
                        $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                        $this->email->subject('CashBack');
                        $this->email->message($message);
                        if ($test_prc != 0 && $notify_customer == 1 && ($payable_amount <= 0 || $payable_amount == '')) {
                            $this->email->send();
                        }
                    }
                    // Case Back Email end			
                }
                /* Nishit cashback end */
                $book_test_details = array();
                foreach ($job_details[0]["book_test"] as $bkey) {
                    $book_test_details[] = $bkey["test_name"];
                }
                foreach ($job_details[0]["book_package"] as $bkey) {
                    $book_test_details[] = $bkey["title"];
                }
                if ($notify_customer == 1) {

                    if ($payable_amount <= 0 || $payable_amount == '') {

                        $message1 = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $name . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Report completed successfully for test ' . implode(",", $book_test_details) . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Report ID : ' . $orderid . ' </p>    
		<p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                    } else {
                        $message1 = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $name . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Report completed successfully for test ' . implode(",", $book_test_details) . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Report ID : ' . $orderid . ' </p>
                            <p style="color:#7e7e7e;font-size:13px;">Please pay your due amount using below link and get report.</p>
                            <p style="color:#7e7e7e;font-size:13px;"><a href="' . base_url() . 'u/j/' . $cid . '" target="_blank">Click Here</a> for pay online.</p>
		<p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                    }
                    $c_email = array();
                    $message1 = $email_cnt->get_design($message1);
                    $c_email[] = $email;
                    $c_email[] = $assign_doctor[0]["email"];
                    $this->email->to(implode(",", $c_email));
                    if ($branch == 4) {
                        $this->email->cc('pagaravpath@gmail.com'); //pagaravpath@gmail.com for Airmed Gandhinagar-Pagarav Branch
                    }
                    //  $this->email->to('jeel@virtualheight.com');
                    $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
                    $this->email->subject("Report Completed");
                    $this->email->message($message1);
                    if ($payable_amount <= 0 || $payable_amount == '') {
                        $attatchPath = "";
                        foreach ($report as $key) {
                            $attatchPath = FCPATH . "upload/report/" . $key['report'];
                            $this->email->attach($attatchPath);
                        }
                    }
                    //$this->email->attach(implode(',',$attatchPath));
                    $this->email->send();

                    $family_member_name = $this->job_model->get_family_member_name($cid);
                    $c_email = array();
                    if (!empty($family_member_name)) {
                        $c_email[] = $family_member_name[0]["email"];
                        $c_email[] = $assign_doctor[0]["email"];
                        if (!empty($c_email)) {
                            $this->email->to(implode(",", $c_email));
                            if ($branch == 4) {
                                $this->email->cc('pagaravpath@gmail.com'); //pagaravpath@gmail.com for Airmed Gandhinagar-Pagarav Branch
                            }
                            //  $this->email->to('jeel@virtualheight.com');
                            $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
                            $this->email->subject("Report Completed");
                            $this->email->message($message1);
                            if ($payable_amount <= 0 || $payable_amount == '') {
                                $attatchPath = "";
                                foreach ($report as $key) {
                                    $attatchPath = base_url() . "upload/report/" . $key['report'];
                                    $this->email->attach($attatchPath);
                                }
                            }
                            //$this->email->attach(implode(',',$attatchPath));
                            $this->email->send();
                        }
                    }
                    /* Nishit send SMS start */
                    if ($smsalert == 1) {
                        if (empty($checkPanelTes)) {
                            $sms = $this->send_result($cid);
                            //$this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $sms["mobile"][0], "message" => $sms["sms"], "created_date" => date("Y-m-d H:i:s")));

                            if (!empty($sms["sms"])) {
                                $doctor_number = array();
                                if (!empty($assign_doctor[0]["mobile"]) && !in_array($assign_doctor[0]["mobile"], $doctor_number) && $assign_doctor[0]["notify"] == 1) {
                                    $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $assign_doctor[0]["mobile"], "message" => $sms["sms"], "created_date" => date("Y-m-d H:i:s")));
                                    $doctor_number[] = $assign_doctor[0]["mobile"];
                                }
                                if (!empty($assign_doctor[0]["mobile1"]) && !in_array($assign_doctor[0]["mobile1"], $doctor_number) && $assign_doctor[0]["notify"] == 1) {
                                    $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $assign_doctor[0]["mobile1"], "message" => $sms["sms"], "created_date" => date("Y-m-d H:i:s")));
                                    $doctor_number[] = $assign_doctor[0]["mobile1"];
                                }
                                if (!empty($assign_doctor[0]["mobile2"]) && !in_array($assign_doctor[0]["mobile2"], $doctor_number) && $assign_doctor[0]["notify"] == 1) {
                                    $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $assign_doctor[0]["mobile2"], "message" => $sms["sms"], "created_date" => date("Y-m-d H:i:s")));
                                    $doctor_number[] = $assign_doctor[0]["mobile2"];
                                }
                                $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $sms["mobile"][0], "message" => $sms["sms"], "created_date" => date("Y-m-d H:i:s")));
                                $this->job_model->master_fun_insert("send_report_sms", array("job_fk" => $cid, "mobile" => $sms["mobile"][0], "sms" => $sms["sms"], "created_date" => date("Y-m-d H:i:s"), "send_by" => $login_user));
                            }
                            $this->job_model->master_fun_update("job_master", array("id", $cid), array("send_email" => '1', "send_sms" => '1'));
                            /* END */
                        }
                    }
                }
            }
        }
        return 1;
    }

    function send_result($id) {
        $this->load->model("add_result_model");
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['cid'] = $id;
        $testfk = $this->input->post('testid');
        /* $this->form_validation->set_rules('testid[]', 'testid', 'trim|required');
          if ($this->form_validation->run() != FALSE) { */
        if (1 == 1) {
            $data['query'] = $this->add_result_model->job_details1($data['cid']);
            // $data['query1'] = $this->add_result_model->job_details($data['cid']);
            //$cus_id = $data['query1'][0]['custid'];
            //  $data['query'] = $this->add_result_model->get_val("SELECT c.mobile,cfm.phone FROM `customer_family_master` as cfm LEFT JOIN `customer_master` as c on c.id =cfm.user_fk WHERE cfm.user_fk ='" .  $cus_id . "' ORDER BY c.id DESC LIMIT 1"); 
            $data['processing_center'] = $this->add_result_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $data['query'][0]["branch_fk"]), array("id", "asc"));
            if (empty($data['processing_center'])) {
                $processing_center = '1';
            } else {
                $processing_center = $data['processing_center'][0]["branch_fk"];
            }
            $branch_fk = $data["branch_fk"] = $data['query'][0]["branch_fk"];
            $data['user_booking_info'] = $this->add_result_model->master_fun_get_tbl_val("booking_info", array('status' => 1, 'id' => $data['query'][0]["booking_info"]), array("id", "asc"));
            $data['user_data'] = $this->add_result_model->master_fun_get_tbl_val("customer_master", array('status' => 1, 'id' => $data['query'][0]["custid"]), array("id", "asc"));
            /* if (empty($data['user_data'][0]["gender"]) && empty($data['user_data'][0]["age"])) {
              $data['user_data'][0]["gender"] = 'male';
              $data['user_data'][0]["age"] = 24;
              } */
            $get_approved_test_list = $this->add_result_model->master_fun_get_tbl_val("approve_job_test", array('status' => '1', 'job_fk' => $data['cid']), array("id", "asc"));
            $approved_test = array();
            foreach ($get_approved_test_list as $at_key) {
                $approved_test[] = $at_key["test_fk"];
            }
            $cust_rel_mobile = "";
            if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
                $data['user_family_info'] = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
                $data['user_data'][0]["gender"] = $data['user_family_info'][0]["gender"];
                $data['user_data'][0]["age"] = $data['user_family_info'][0]["age"];
                $data['user_data'][0]["age_type"] = $data['user_family_info'][0]["age_type"];
                $data['user_data'][0]["full_name"] = $data['user_family_info'][0]["name"];
                $data['user_data'][0]["email"] = $data['user_family_info'][0]["email"];
                $data['user_data'][0]["phone"] = $data['user_family_info'][0]["phone"];
                $cust_rel_mobile = $data['user_family_info'][0]["phone"];
                $data['user_data'][0]["dob"] = $data['user_family_info'][0]["dob"];
            }
            /* Check bitrth date start */
            $this->load->library("util");
            $util = new Util;
            $age = $util->get_age($data['user_data'][0]["dob"]);
            $ageinDays = 0;
            if ($age[0] != 0) {
                $ageinDays += ($age[0] * 365);
                $data['user_data'][0]["age"] = $age[0];
                $data['user_data'][0]["age_type"] = 'Y';
            }
            if ($age[0] == 0 && $age[1] != 0) {
                $ageinDays += ($age[1] * 30);
                $data['user_data'][0]["age"] = $age[1];
                $data['user_data'][0]["age_type"] = 'M';
            }
            if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
                $ageinDays += ($age[2]);
                $data['user_data'][0]["age"] = $age[2];
                $data['user_data'][0]["age_type"] = 'D';
            }
            /* Check birth date end */
            $tid = array();
            $data['parameter_list'] = array();
            if (trim($data['query'][0]['testid']) == null && $data['query'][0]["packageid"] != null) {

                $package_id = $data['query'][0]["packageid"];
                $pid = explode("%", $data['query'][0]['packageid']);
                foreach ($pid as $pkey) {
                    $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");
                    foreach ($p_test as $tp_key) {
                        $tid[] = $tp_key["test_fk"];
                    }
                }
            } else if (trim($data['query'][0]['testid']) != null && $data['query'][0]["packageid"] != null) {

                $tid = explode(",", $data['query'][0]['testid']);
                $package_id = $data['query'][0]["packageid"];
                $pid = explode("%", $data['query'][0]['packageid']);
                foreach ($pid as $pkey) {
                    $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");
                    foreach ($p_test as $tp_key) {
                        $tid[] = $tp_key["test_fk"];
                    }
                }
            } else {

                $tid = explode(",", $data['query'][0]['testid']);
            }

            foreach ($tid as $t_key) {
                $p_test = $this->add_result_model->get_val("SELECT sub_test_master.* FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `sub_test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $t_key . "'");
                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["sub_test"];
                }
            }
            $tid = array_unique($tid);
            $cnt = 0;
            $new_data_array = array();
            foreach ($tid as $tst_id) {
                if (in_array($tst_id, $approved_test)) {
                    $get_test_parameter = $this->add_result_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name`,test_master.PRINTING_NAME,`test_master`.report_type,test_department_master.`name` AS 'department_name' FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id`     LEFT JOIN  `test_department_master` ON test_department_master.`id`=`test_master`.`department_fk`  WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' and test_parameter.center='" . $processing_center . "' order by `test_parameter`.order asc");

                    $pid = array();
                    foreach ($get_test_parameter as $tp_key) {
                        if (!empty($tp_key["parameter_fk"])) {
                            $pid[] = $tp_key["parameter_fk"];
                        }
                    }
                    if (!empty($pid)) {
                        $para = $this->add_result_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") and sms='1' ORDER BY FIELD(id," . implode(",", $pid) . ")");
                        $cnt_1 = 0;
                        foreach ($para as $para_key) {
                            /* $formula = $this->add_result_model->get_val("SELECT * from use_formula where test_fk='" . $tst_id . "' and job_fk='" . $data['cid'] . "' and status='1'"); */
                            $get_test_parameter[$cnt_1]['use_formula'] = "";

                            $gettest = $this->add_result_model->get_val("SELECT new_page from approve_job_test where test_fk='" . $tst_id . "' and job_fk='" . $data['cid'] . "' and status='1'");
                            $get_test_parameter[$cnt_1]['on_new_page'] = $gettest[0]["new_page"];

                            /* Report type start */
                            $culure_design = $this->add_result_model->get_val("SELECT id,title,html FROM `parameter_culture` WHERE center='" . $processing_center . "' AND test_fk='" . $tst_id . "' AND STATUS='1'");
                            $get_test_parameter[$cnt_1]['culture_design'] = $culure_design;
                            /* End */
                            $get_test_parameter[$cnt_1]['is_culture'] = "";
                            $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                            $get_test_parameter[$cnt_1]['graph'] = $graph_pic;
                            $get_test_parameter1 = $get_test_parameter[$cnt_1];
//echo "SELECT * from user_test_result where test_id='".$tst_id."' and parameter_id='" . $para_key["id"] . "' and job_id='".$data['cid']."' and  status='1'";
                            $para_user_val = $this->add_result_model->get_val("SELECT * from user_test_result where test_id='" . $tst_id . "' and parameter_id='" . $para_key["id"] . "' and job_id='" . $data['cid'] . "' and  status='1'");
                            $para[$cnt_1]["user_val"] = $para_user_val;
                            $para_culture_val = $this->add_result_model->get_val("SELECT * from user_test_result where test_id='" . $tst_id . "' and job_id='" . $data['cid'] . "' and result is not NULL and status='1'");
                            $para[$cnt_1]["user_culture_val"] = $para_culture_val;
                            $para_ref_rng = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE `status`='1' AND parameter_fk='" . $para_key["id"] . "' order by gender asc");
                            $final_qry = "SELECT *,
  CASE
    WHEN (type_period = 'Y') 
    THEN (no_period * 365) 
    ELSE (
      CASE
        WHEN (type_period = 'M') 
        THEN (no_period * 30) 
        ELSE no_period 
      END
    ) 
  END AS col1  FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                            if (strtoupper($data['user_data'][0]["gender"]) == 'MALE') {
                                $final_qry .= " AND gender='M' AND (CASE WHEN (type_period= 'Y') THEN (no_period*365) ELSE (CASE WHEN (type_period= 'M') THEN (no_period*30) ELSE no_period END) END )>=$ageinDays ";
                                $data["common"] = 0;
                            } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE') {
                                $final_qry .= " AND gender='F' AND  (CASE WHEN (type_period= 'Y') THEN (no_period*365) ELSE (CASE WHEN (type_period= 'M') THEN (no_period*30) ELSE no_period END) END )>=$ageinDays";
                                $data["common"] = 0;
                            }
                            $final_qry = $final_qry . " ORDER BY (col1*1) ASC limit 0,1";
                            $final_qry1 = "SELECT * FROM `test_result_status` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                            $data["common"] = 1;
                            $data["para_ref_rng"] = $this->add_result_model->get_val($final_qry);
                            $data["para_ref_rng"][0]["common"] = "1";
                            $data["para_ref_rng"][0]["tst_id"] = $tst_id;
                            $para[$cnt_1]['para_ref_rng'] = $data["para_ref_rng"];

                            $data["para_ref_status"] = $this->add_result_model->get_val($final_qry1);
                            $para[$cnt_1]['para_ref_status'] = $data["para_ref_status"];
                            $cnt_1++;
                        }
                        $get_test_parameter1[0]['parameter'] = $para;
                        $new_data_array[] = $get_test_parameter1;
                    } else {
                        $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name,test_master.PRINTING_NAME FROM `test_master` WHERE id='" . $tst_id . "'");
                        $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                        $get_test_parameter1[0]['graph'] = $graph_pic;
                        $new_data_array[] = $get_test_parameter1[0];
                    }

                    $cnt++;
                }
            }


            $data["new_data_array"] = $new_data_array;
            $data['result_list'] = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('status' => 1, 'job_id' => $data['cid']), array("id", "asc"));
            /* Nishit add result SMS start */
            $txt_cnt = 0;
            $sms_text = "";
            $sms_text .= "Patient Name:  " . strtoupper($data['user_data'][0]["full_name"]) . " (" . $data['query'][0]["order_id"] . ") \n\n";
            foreach ($new_data_array as $testidp) {
                $parameter_cnt = 0;
                if (!empty($testidp[0]["parameter"])) {
                    $parameter_val_cnt = 0;
                    foreach ($testidp[0]["parameter"] as $parameter) {
                        if (!empty($parameter['user_val'])) {
                            $parameter_val_cnt++;
                        }
                    }
                    if ($parameter_val_cnt != 0) {
                        //if($txt_cnt>0){ $sms_text .='\n'; }
                        if ($txt_cnt > 0) {
                            $sms_text .= "\n " . $testidp["test_name"] . " \n";
                        } else {
                            $sms_text .= $testidp["test_name"] . " \n";
                        }

                        $txt_cnt++;
                        $temp = '1';
                        $cn = 0;

                        foreach ($testidp[0]["parameter"] as $parameter) {
                            if ($parameter["is_group"] != '1') {
                                if (!empty($parameter['parameter_name']) && !empty($parameter['user_val'])) {
                                    if (count($parameter['user_val']) > 0) {
                                        $status = "Normal";
                                        if ($parameter["para_ref_rng"][0]['absurd_low'] > $parameter['user_val'][0]["value"]) {
                                            $status = "Emergency";
                                        }
                                        if ($parameter["para_ref_rng"][0]['ref_range_low'] > $parameter['user_val'][0]["value"]) {
                                            $status = $parameter["para_ref_rng"][0]['low_remarks'];
                                        }
                                        if ($parameter["para_ref_rng"][0]['critical_low'] > $parameter['user_val'][0]["value"]) {
                                            $status = $parameter["para_ref_rng"][0]['critical_low_remarks'];
                                        }
                                        if ($parameter["para_ref_rng"][0]['ref_range_high'] < $parameter['user_val'][0]["value"]) {
                                            $status = $parameter["para_ref_rng"][0]['high_remarks'];
                                        }
                                        if ($parameter["para_ref_rng"][0]['critical_high'] < $parameter['user_val'][0]["value"]) {
                                            $status = $parameter["para_ref_rng"][0]['critical_high_remarks'];
                                        }
                                    } else {
                                        $status = "";
                                    }

                                    $sms_text .= $parameter['parameter_name'] . " :- ";

                                    $res = '';
                                    $is_text = 0;
                                    if (isset($parameter["para_ref_rng"][0]['id'])) {

                                        $sms_text .= " " . $parameter['user_val'][0]["value"];
                                        $status;
                                    } else {

                                        if (!empty($parameter["para_ref_status"])) {
                                            foreach ($parameter["para_ref_status"] as $kky) {
                                                if ($parameter['user_val'][0]["value"] == $kky["id"]) {
                                                    $is_text = 1;
                                                    $sms_text .= " " . $kky["parameter_name"] . " \n ";
                                                }
                                            }
                                        } else {
                                            $sms_text .= $parameter['user_val'][0]["value"];
                                        }
                                    }
                                    //$sms_text .= "   ".$res;
                                    if ($is_text == 0) {
                                        //$sms_text .= $parameter['parameter_unit'];
                                        if (!empty(trim($parameter["para_ref_rng"][0]["ref_range"]))) {
                                            $sms_text .= " [" . $parameter["para_ref_rng"][0]["ref_range"] . "] \n ";
                                        } else {
                                            if ($parameter["para_ref_rng"][0]['ref_range_low'] != '' || $parameter["para_ref_rng"][0]['ref_range_high'] != '') {
                                                $sms_text .= " [" . $parameter["para_ref_rng"][0]['ref_range_low'];
                                                $sms_text .= " - " . $parameter["para_ref_rng"][0]['ref_range_high'] . "] \n ";
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            /* Nishit add result SMS end */
            if (trim($sms_text) != '') {
                $mobile = array();
                $mobile[] = $data['query'][0]["mobile"];
                if (!empty($cust_rel_mobile)) {
                    $mobile[] = $cust_rel_mobile;
                }
                $custmobile = $cust_rel_mobile;

                $data['send_sms_no'] = $this->add_result_model->get_val("SELECT `send_report_sms`.*,`admin_master`.`name` FROM `send_report_sms` INNER JOIN `admin_master` ON `send_report_sms`.`send_by`=`admin_master`.id WHERE `send_report_sms`.`status`='1' AND send_report_sms.`job_fk`='" . $data['cid'] . "'");

                return array("status" => "1", "sms" => $sms_text, "mobile" => $mobile, "custmobile" => $custmobile, "history" => $data['send_sms_no']);
            } else {
                return array("status" => "0");
            }
        }
    }

    function sample_collected() {
        $cid = $this->uri->segment('3');
        //	die();
        $this->sample_collected_calculation($cid);
        $this->session->set_flashdata("success", array("Sample Collection completed."));
        redirect("job-master/pending-list", "refresh");
    }

    function sample_collected_calculation($cid) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data2 = $this->job_model->master_fun_get_tbl_val("job_master", array("id" => $cid), array("id", "asc"));
        $cust_fk = $data2[0]['cust_fk'];
        $price = $data2[0]['price'];
        $payment_type = $data2[0]['payment_type'];
        $status = $data2[0]['sample_collection'];
        $notify_cust = $data2[0]["notify_cust"];
        $update = $this->job_model->master_fun_update("job_master", array("id", $cid), array("sample_collection" => "1"));
        if ($update) {
            $data1 = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));
            $data1r = $this->job_model->master_fun_get_tbl_val("refer_code_master", array('used_code !=' => "", "cust_fk" => $cust_fk), array("id", "asc"));
            if ($data1r) {
                $rcode = $data1r[0]['used_code'];
                $urcode = $data1r[0]['cust_fk'];
                $data1r1 = $this->job_model->master_fun_get_tbl_val("refer_code_master", array('refer_code' => $rcode), array("id", "asc"));
                $addmid = $data1r1[0]['cust_fk'];
                if ($data1r1) {
                    $data2 = $this->job_model->master_fun_get_tbl_val("job_master", array("cust_fk" => $cust_fk), array("id", "asc"));
                    $cnt = count($data2);
                    if ($cnt == "1") {
                        $query = $this->job_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $addmid), array("id", "desc"));
                        $total = $query[0]['total'];
                        $caseback_amount = ($price * $caseback_per) / 100;
                        $data = array(
                            "cust_fk" => $addmid,
                            "credit" => 100,
                            "total" => $total + 100,
                            "type" => "Case Back",
                            "job_fk" => $cid,
                            "created_time" => date('Y-m-d H:i:s')
                        );
                        if ($total != 0) {
                            $insert1 = $this->job_model->master_fun_insert("wallet_master", $data);
                        }
                    }
                }
            }

            $data1 = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));
            //print_r($data1);
            //die();
            $device_id = $data1[0]['device_id'];
            $mobile = $data1[0]['mobile'];
            $device_type = $data1[0]['device_type'];
            $name = $data1[0]['full_name'];
            $email = $data1[0]['email'];
            $query = $this->job_model->job_details($cid);
            $testid = $query[0]['id'];
            $discount = $query[0]['discount'];
            $testname = $query[0]['testname'];
            $packagename = $query[0]['packagename'];
            $testprice = $query[0]['price'];
            $testdate = $query[0]['date'];
            $orderid = $query[0]['order_id'];
            $test_name_mail = explode("#", $testname);
            $package_name_mail = explode("@", $packagename);
            //print_r($test_name_mail); echo "<br>"; print_r($package_name_mail); die();
            $test_mail = implode(",", $test_name_mail);
            $package_mail = implode(",", $package_name_mail);
            $message = "Your Test Sample has been collected";
            if ($device_type == 'android' && $notify_cust == 1) {
                //$notification_data=array("title" => "Patholab","message" =>$message,"type"=>"Pending");
                $notification_data = array("title" => "AirmedLabs", "message" => $message, "type" => "Pending", "testid" => $testid, "testname" => $testname, "testprice" => $testprice, "testdate" => $testdate, "order_id" => $orderid);
                $pushServer = new PushServer();
                $pushServer->pushToGoogle($device_id, $notification_data);
            }
            if ($device_type == 'ios' && $notify_cust == 1) {
                $url = 'http://website-demo.in/patholabpush/push.php?device_id=' . $device_id . '&msg=' . $message . '&type=pending&testid=' . $testid . '&testname=' . $testname . '&testprice=' . $testprice . '&testdate=' . $testdate . '&orderid=' . $orderid . '';
                $url = str_replace(" ", "%20", $url);
                $data = $this->get_content($url);
                $data2 = json_decode($data);
            }
            $family_member_name = $this->job_model->get_family_member_name($cid);
            if (!empty($family_member_name)) {
                $c_name = $family_member_name[0]["name"];
                $cmobile = $family_member_name[0]["phone"];
                if (empty($cmobile)) {
                    $cmobile = $mobile;
                }
            } else {
                $c_name = $data1[0]["full_name"];
                $cmobile = $mobile;
            }
            if ($notify_cust == 1) {
                $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "Sample_collection_Sms"), array("id", "asc"));
                $sms_message = preg_replace("/{{NAME}}/", ucfirst($c_name), $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{OID}}/", $orderid, $sms_message);
                //$sms_message = preg_replace("/{{PRICE}}/", "Rs." . $testprice, $sms_message);
                $this->load->helper("sms");
                $notification = new Sms();
            }
            //$notification->send($cmobile, $sms_message);
            //if (!empty($family_member_name)) {
            //  $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $mobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
            //}
            $package_price = $this->job_model->master_fun_get_tbl_val("book_package_master", array("status" => 1, "job_fk" => $cid), array("id", "desc"));
            $book_tst = $this->job_model->master_fun_get_tbl_val("job_test_list_master", array("job_fk" => $cid), array("id", "desc"));
            /* $test_prc = 0;
              foreach ($book_tst as $t_key) {
              $tst_prc = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $t_key["test_fk"] . "'");
              $test_prc = $test_prc + $tst_prc[0]["price"];
              }
              if ($payment_type == "Cash On Delivery" && $status == "0" && $discount == "0") {
              $query = $this->job_model->master_fun_get_tbl_val("offer_master", array("status" => 1), array("id", "desc"));
              $caseback_per = $query[0]['caseback_per'];
              $query = $this->job_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $cust_fk), array("id", "desc"));
              $total = $query[0]['total'];
              $price = $test_prc;
              $caseback_amount = ($price * $caseback_per) / 100;
              $data = array(
              "cust_fk" => $cust_fk,
              "credit" => $caseback_amount,
              "total" => $total + $caseback_amount,
              "type" => "Case Back",
              "job_fk" => $cid,
              "created_time" => date('Y-m-d H:i:s')
              );
              if ($test_prc != 0) {
              $insert1 = $this->job_model->master_fun_insert("wallet_master", $data);
              }
              $query = $this->job_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $cust_fk), array("id", "desc"));
              $Current_wallet = $query[0]['total'];
              // Case Back Email start
              $config['mailtype'] = 'html';
              $this->email->initialize($config);

              $message = '<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
              <script src="https://use.fontawesome.com/44049e3dc5.js"></script>
              <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
              <div style="border:1px solid #f1f1f1;border-bottom:1px solid #c7c7c7;padding:1.5%;width:100%;float:left;background:#fff;">
              <div style="float:left;width:64%;">
              <img alt="" src="' . base_url() . 'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
              </div>
              <div style="float:right;text-align: right;width:33%;padding-top:7px;">



              </div>
              </div>
              <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
              <div style="padding:0 4%;">
              <h4><b>Dear </b>' . $data1[0]['full_name'] . '</h4>
              <p style="color:#7e7e7e;font-size:13px;">Cashback Credited in your Wallet. </p>

              <p style="color:#7e7e7e;font-size:13px;"> Rs. ' . $caseback_amount . ' Credited in your account. </p>
              <p style="color:#7e7e7e;font-size:13px;">Your Current Wallet Amount is Rs. ' . $Current_wallet . '</p>
              <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
              </div>
              </div>
              <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
              <a href="' . base_url() . 'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
              <a href="' . base_url() . 'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
              <a href="' . base_url() . 'user_login" style="color:#515151;text-decoration:none;">Login</a> |
              <a href="' . base_url() . 'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
              <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="' . base_url() . 'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>
              </div>
              <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
              Copyright @ 2016 AirmedLabs. All rights reserved
              </div>
              </div>
              </div>';
              $this->email->to($email);
              $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
              $this->email->subject('CashBack');
              $this->email->message($message);
              if ($test_prc != 0) {
              $this->email->send();
              }
              }
             */
            if ($notify_cust == 1) {
                $this->load->helper("Email");
                $email_cnt = new Email;

                $config['mailtype'] = 'html';

                $this->email->initialize($config);
                $family_member_name = $this->job_model->get_family_member_name($cid);
                if (!empty($family_member_name)) {
                    $name = $family_member_name[0]["name"];
                }
                $message1 = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $name . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Test Sample has been collected successfully. </p>
						<p style="color:#7e7e7e;font-size:13px;"> You Booked Following Test/Package : ' . $test_mail;
                if ($package_mail != NULL) {
                    $message1 .= ' / ' . $package_mail;
                }
                $message1 .= ' </p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message1 = $email_cnt->get_design($message1);
                $this->email->to($email);
                $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
                $this->email->subject("Sample Collection Successfully");
                $this->email->message($message1);
                //$this->email->send();
                //$this->email->print_debugger();
                $family_member_name = $this->job_model->get_family_member_name($cid);
                if (!empty($family_member_name)) {
                    $c_email = $family_member_name[0]["email"];
                    if (!empty($c_email)) {
                        $this->email->to($c_email);
                        $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
                        $this->email->subject("Sample Collection Successfully");
                        $this->email->message($message1);
                        //  $this->email->send();
                    }
                }
            }

            /* $this->session->set_flashdata("success", array("Sample Collection completed."));
              redirect("job-master/pending-list", "refresh"); */
        }
    }

    function export_csv2() {

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
        $withsample = $data['withsample'] = $search_data["withsample"] = $this->input->get_post('withsample');
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

        $result = $this->job_model->csv_job_list($search_data);
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"All_Jobs_Report-" . date('d-M-Y') . ".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No.", "Reg No.", "Order Id", "Test City", "Branch", "Date", "Patient Name", "Mobile No.", "Doctor", "Test/Package Name", "Job Status", "Payment Type", "Sample From", "Portal", "Remark", "Added By", "Total Price", "Discount(RS.)", "Collected Cash/Card", "Debited From Wallet", "Due Amount", "Payment Mode"));
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
            /* Nishit 18-08-2017 START */
            $payment_mode = array();
            $discount = 0;
            if ($key["discount"] > 0) {
                $discount = round($key["price"] * $key["discount"] / 100);
            }
            $added_by = "Online";
            if (!empty($key["phlebo_added_by"])) {
                $added_by = $key["phlebo_added_by"] . " (Phlebo)";
            } else if (!empty($key["added_by"])) {
                $added_by = $key["added_by"];
            }
            $collection_type = $this->job_model->get_val("SELECT GROUP_CONCAT(`payment_type`) AS payment_type FROM `job_master_receiv_amount` WHERE `status`='1' AND job_fk='" . $key["id"] . "' GROUP BY payment_type ORDER BY payment_type ASC");
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
            }
            $dabitt_from_wallet = $this->job_model->get_val("SELECT IF(SUM(`debit`)>0,SUM(`debit`),0) AS dabit FROM `wallet_master` WHERE `job_fk`='" . $key["id"] . "' AND `status`='1'");
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
            fputcsv($handle, array($cnt, $key["id"], $key["order_id"], $key["test_city_name"], $key["branch_name"], $key["date"], $patient_name, $key["mobile"], $key["doctor_name"] . "-" . $key["doctor_mobile"], $key["testname"] . " " . $key["packagename"], $j_status, $key["payment_type"], $key["sample_from"], $key["portal"], $key["note"], $added_by, $key["price"], $discount, $collected_cash_card, $dabitt_from_wallet[0]["dabit"], $key["payable_amount"], implode("+", $payment_mode)));
            $cnt++;
        }
        fclose($handle);
        exit;
    }

    function export_csv() {
        $search_data = array();
        $plm = $data['plm2'] = $search_data["plm"] = $this->input->get('plm');
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
        $withsample = $data['withsample'] = $search_data["withsample"] = $this->input->get_post('withsample');
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
        /* PLM start */
        if (empty($branch) && $plm != '') {
            if (empty($branch) && !empty($city) && empty($plm)) {
                $plm_branch = $this->job_model->get_val("SELECT GROUP_CONCAT(id) AS id FROM `branch_master` WHERE `status`='1' AND city='" . $city . "' GROUP BY status");
            }
            if (empty($branch) && !empty($plm)) {
                $plm_branch = $this->job_model->get_val("select GROUP_CONCAT(id) AS id from branch_master where status='1' and (parent_fk ='" . $plm . "' or id='" . $plm . "') GROUP BY status");
            }
            if (empty($branch) && empty($city) && empty($plm)) {
                $plm_branch = $this->job_model->get_val("select GROUP_CONCAT(id) AS id from branch_master where status='1' GROUP BY status");
            }
            $plm_branch = explode(",", $plm_branch[0]["id"]);
            $cntr_arry = array_merge(array($plm), $plm_branch);
        } else {
            $cntr_arry = $branch;
        }
        /* PLM END */



        if ($data["login_data"]['type'] == '6') {
            $login_id = $data["login_data"]['id'];
            $plm_branch = $this->job_model->get_val("select GROUP_CONCAT(bm.id) AS id 
                 FROM branch_master bm 
                 LEFT JOIN user_branch ub on ub.branch_fk = bm.id 
                 WHERE ub.user_fk = '$login_id' 
                 AND bm.status='1' GROUP BY bm.status");

            $plm_branch = explode(",", $plm_branch[0]["id"]);
            $cntr_arry = $plm_branch;
        }



        $search_data['cntr_arry'] = $cntr_arry;
        $search_data['t_id'] = $t_id;
        $search_data['p_id'] = $p_id;

        $result = $this->job_model->csv_job_list($search_data);
        //    print_r($result);
        //   die("OK");
        $is_regular_test = false;
        $is_panel_test = false;
        /* Nishit code start */

        $new_array = array();
        foreach ($result as $kkey) {
            $is_regular_test = false;
            $is_panel_test = false;
            $job_test_list = $this->job_model->get_val("SELECT `job_test_list_master`.*,`test_master`.`test_name` FROM `job_test_list_master` INNER JOIN `test_master` ON `job_test_list_master`.`test_fk`=`test_master`.`id` WHERE `job_test_list_master`.`job_fk`='" . $kkey["id"] . "'");
            /* Check sub test start */
            $job_tst_lst = array();
            $job_sub_tst_lst = array();
            foreach ($job_test_list as $st_key) {
                //echo $st_key['test_fk'];
                $job_sub_test_list = $this->job_model->get_val("SELECT `sub_test_master`.test_fk,`sub_test_master`.`sub_test`,test_master.`test_name` FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`sub_test`=`test_master`.`id` WHERE `sub_test_master`.`status`='1' AND `test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $st_key['test_fk'] . "'");
                $job_sub_tst_lst[] = $job_sub_test_list;
                $st_key["sub_test"] = $job_sub_test_list;
                $job_tst_lst[] = $st_key;
            }

            $package_ids = $this->job_model->get_job_booking_package($kkey["id"]);
            $is_show = 1;
            $test_list = array();
            $package_list = array();
            if (!empty($test_pack)) {
                $is_show = 0;
                foreach ($job_test_list as $f_key) {
                    if (!in_array($f_key["test_name"], $test_list)) {
                        $test_list[] = $f_key["test_name"];
                    }
                    if (strpos(trim(strtoupper($f_key["test_name"])), trim(strtoupper($test_pack))) !== false) {
                        $is_show = 1;
                    }
                }

                foreach ($package_ids as $p_key) {
                    $package_list[] = $p_key["name"];
                    if (strpos(trim(strtoupper($p_key["name"])), trim(strtoupper($test_pack))) !== false) {
                        $is_show = 1;
                    }
                    foreach ($p_key["test"] as $p_key) {
                        if (!in_array($p_key["test_name"], $test_list)) {
                            $test_list[] = $p_key["test_name"];
                        }
                        if (strpos(trim(strtoupper($p_key["test_name"])), trim(strtoupper($test_pack))) !== false) {
                            $is_show = 1;
                        }
                    }
                }

                foreach ($job_sub_tst_lst as $p_key) {
                    foreach ($p_key as $ss_p_key) {
                        if (!in_array($ss_p_key["test_name"], $test_list)) {
                            $test_list[] = $ss_p_key["test_name"];
                        }
                        if (strpos(trim(strtoupper($ss_p_key["test_name"])), trim(strtoupper($test_pack))) !== false) {
                            $is_show = 1;
                        }
                    }
                }
            } else {
                $is_show = 0;
                foreach ($job_test_list as $f_key) {
                    if (!in_array($f_key["test_name"], $test_list)) {
                        $test_list[] = $f_key["test_name"];
                    }
                    if ($f_key["is_panel"] == "1") {
                        $is_panel_test = true;
                    } else {
                        $is_regular_test = true;
                    }
                    $is_show = 1;
                }

                foreach ($package_ids as $p_key) {
                    $package_list[] = $p_key["name"];
                    foreach ($p_key["test"] as $p_key) {
                        if (!in_array($p_key["test_name"], $test_list)) {
                            $test_list[] = $p_key["test_name"];
                        }
                        $is_show = 1;
                    }
                }

                foreach ($job_sub_tst_lst as $p_key) {
                    foreach ($p_key as $ss_p_key) {
                        if (!in_array($ss_p_key["test_name"], $test_list)) {
                            $test_list[] = $ss_p_key["test_name"];
                        }
                        $is_show = 1;
                    }
                }
            }
            /* END */
            $kkey["is_show"] = $is_show;
            $coma = "";
            if (!empty($package_list)) {
                $coma = ", ";
            }
            $kkey["test_name"] = implode(", ", $package_list) . $coma . implode(", ", $test_list);
            $test_type = "Regular";
            if ($is_panel_test == true && $is_regular_test == true) {
                $test_type = "Regular + Panel";
            } else if ($is_panel_test == true) {
                $test_type = "Panel";
            } else if ($is_regular_test == true) {
                $test_type = "Regular";
            }
            $kkey['test_type'] = $test_type;
            $new_array[] = $kkey;
        }
        /* Nishit code end */
        if (!isset($_REQUEST['de'])) {
            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"All_Jobs_Report-" . date('d-M-Y') . ".csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle = fopen('php://output', 'w');
            //fputcsv($handle, array("No.", "Reg No.", "Order Id", "Test City", "Branch", "Date", "Patient Name", "Mobile No.", "Doctor", "Test/Package Name", "Test Type", "Job Status", "Payment Type", "Sample From", "New Patient/old Patient", "Portal", "Remark", "Added By", "Total Price", "Discount(RS.)", "Received Amount", "Cash", 'Card', "Payumoney", "CHEQUE", "Debited From Wallet", "Creditor Remain", "Due Amount"));
            //fputcsv($handle, array("No.", "Reg No.", "Order Id", "Test City", "Branch", "Date", "Patient Name", "Mobile No.", "Doctor", "Test/Package Name", "Test Type", "Job Status", "Payment Type", "Sample From", "New Patient/old Patient", "Portal", "Remark", "Added By", "Total Price", "Discount(RS.)", "Received Amount", "Cash", 'Card', "Payumoney", "CHEQUE", "Debited From Wallet", "Creditor Remain", "Due Amount", "Sample Received Date", "Sample Received Time", "Processing Date", "Processing Time", "Completed Time", "Report Generate Date", "Report Generate Time", "Phlebo TAT (In Hour)", "Processing TAT (In Hour)", "Max TAT (In Hour)", "Processing Delay (In Hour)"));
			//fputcsv($handle, array("No.", "Reg No.", "Order Id", "Test City", "Branch", "Date", "Patient Name", "Mobile No.", "Doctor", "Doctor_code","Doctor_Mobile", "PRO", "Speciality","Special Discount", "Test/Package Name", "Test Type", "Job Status", "Payment Type", "Sample From", "New Patient/old Patient", "Portal", "Remark", "Added By", "Total Price", "Discount(RS.)", "Received Amount", "Cash", 'Card', "Payumoney", "CHEQUE", "Debited From Wallet", "Creditor Remain", "Due Amount", "Sample Received Date", "Sample Received Time", "Processing Date", "Processing Time", "Completed Time", "Report Generated Time", "Phlebo TAT (In Hour)", "Processing TAT (In Hour)", "Max TAT (In Hour)", "Processing Delay (In Hour)"));
			fputcsv($handle, array("No.", "Reg No.", "Order Id", "Test City", "Branch", "Date", "Patient Name", "Mobile No.", "Doctor", "Doctor_code","Doctor_Mobile", "PRO", "Speciality","Special Discount", "Test/Package Name", "Test Type", "Job Status", "Payment Type", "Sample From", "New Patient/old Patient", "Portal", "Remark", "Added By", "Total Price", "Discount(RS.)", "Received Amount", "Cash", 'Card', "Payumoney", "CHEQUE", "Debited From Wallet", "Creditor Remain", "Due Amount", "Sample Received Date", "Sample Received Time", "Processing Date", "Processing Time", "Completed Time", "Report Generated Time", "Phlebo TAT (In Hour)", "Processing TAT (In Hour)", "Max TAT (In Hour)", "Processing Delay (In Hour)", "Collection"));
        }
        /* Nishit code start */
        $cnt = 1;

        foreach ($new_array as $key) {
            if ($key["is_show"] == 1) {
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
                /* Nishit 18-08-2017 START */
                $payment_mode = array();
                $creditor_remark = array();
                $discount = 0;
                if ($key["discount"] > 0) {
                    $discount = round($key["price"] * $key["discount"] / 100);
                }
                $added_by = "Online";
                if (!empty($key["phlebo_added_by"])) {
                    $added_by = $key["phlebo_added_by"] . " (Phlebo)";
                } else if (!empty($key["added_by"])) {
                    $added_by = $key["added_by"];
                }
                $cash = 0.0;
                $card = 0.0;
                $cheque = 0.0;
                $other = 0.0;
                $collection_type = $this->job_model->get_val("SELECT REPLACE (GROUP_CONCAT(remark,','), 'credited by', 'Creditor Name : ') AS remark, (`payment_type`) AS payment_type,sum(job_master_receiv_amount.amount) as amount FROM `job_master_receiv_amount` WHERE `status`='1' AND job_fk='" . $key["id"] . "' GROUP BY payment_type ORDER BY payment_type ASC");

                if (count($collection_type) > 0) {
                    foreach ($collection_type as $ct) {
                        if (strtoupper($ct["payment_type"]) == "CASH") {
                            $payment_mode[] = "CASH";
                            $cash += $ct["amount"];
                        } else if (in_array(strtoupper($ct["payment_type"]), array("DEBIT CARD SWIPED THRU ICICI", "DEBIT CARD", "DEBIT CARD", "CREDIT CARD", "PayTm", "PAYTM"))) {
                            $payment_mode[] = "CARD";
                            $card += $ct["amount"];
                            //	die("hiten");
                        } else if (strtoupper($ct["payment_type"]) == "CHEQUE") {
                            $payment_mode[] = "CHEQUE";
                            $cheque += $ct["amount"];
                        } else if (strtoupper($ct["payment_type"]) == "CREDITORS") {
                            $creditor_remark[] = $ct["remark"];
                            //$cheque+=$ct["amount"];
                        }
                        /* else {
                          $other += $ct["amount"];
                          } */
                    }
                }
                $creditor = $this->job_model->get_val("SELECT credit,debit,paid   FROM `creditors_balance` WHERE status=1 and job_id=" . $key["id"]);

                $creditor_cash_collected = 0.0;
                $creditor_cash_due = 0.0;



                if (count($creditor) > 0) {
                    foreach ($creditor as $ct) {
                        if (($ct["credit"]) > 0) {
                            $payment_mode[] = "CREDITOR CREDIT";
                            $creditor_cash_collected += $ct["credit"];
                            $cash += $ct["credit"];
                        }
                        if (($ct["debit"]) > 0) {
                            $payment_mode[] = "CREDITOR DEBIT";
                            $creditor_cash_due += $ct["debit"];
                        }
                    }
                }
                $creditor_cash_due = $creditor_cash_due - $creditor_cash_collected;
                $payumoneyRecord = $this->job_model->get_val("SELECT SUM(amount) as amount FROM `payment` WHERE STATUS='success' AND job_fk=" . $key["id"]);
                $payumoney = 0.0;

                if (count($payumoneyRecord) > 0) {
                    foreach ($payumoneyRecord as $ct) {
                        $payumoney += $ct["amount"];
                    }
                }

                /* Nishit Code for other payment27-09-2017 */

                /* END */

                $dabitt_from_wallet = $this->job_model->get_val("SELECT IF(SUM(`debit`)>0,SUM(`debit`),0) AS dabit FROM `wallet_master` WHERE `job_fk`='" . $key["id"] . "' AND `status`='1'");
                $due = round($key["price"] - $key["payable_amount"] - $discount - $dabitt_from_wallet[0]["dabit"]);
                if ($dabitt_from_wallet[0]["dabit"] > 0) {
                    $payment_mode[] = "WALLET";
                }
                if (strtoupper($key["payment_type"]) == "PAYUMONEY") {
                    $payment_mode[] = "PAYUMONEY";
                }

                /* END */
                if ($key["family_member_fk"] == 0) {
                    $patient_name = $key["full_name"];
                } else {
                    $patient_name = $key["family_name"];
                }



                if (!isset($_REQUEST['de'])) {
                    if ($key["oldpatient"] == '1') {
                        $formpatient = "Old";
                    } else {
                        $formpatient = "New";
                    }


                    if ($data["login_data"]["id"] != '10') {
                        $late_processing = 0;
                        $late_sample_collection = 0;

                        $max_test_tat = 0;
                        $max_package_tat = 0;
                        $max_tat = 0;
                        $phlebo_tat = 0;
                        $processing_tat = 0;

                        $package_ids1 = [];
                        $test_ids1 = [];
                        $test_tat = [];
                        $package_tat = [];
                        $package_ids = $this->job_model->get_job_booking_package($key["id"]);
                        foreach ($job_tst_lst as $key1) {
                            $test_ids1[] = $this->job_model->get_val("select id from test_master where status='1' AND test_name like '%" . str_replace("'", "''", $key1['test_name']) . "%'");
                        }
                        foreach ($package_ids as $key2) {
                            $package_ids1[] = $this->job_model->get_val("select id from package_master where status='1' AND title like '%" . str_replace("'", "''", $key2['name']) . "%'");
                        }

                        foreach ($test_ids1 as $key3) {
                            $test_tat[] = $this->job_model->get_val("select tat from test_tat where status='1' AND type='1' AND test_fk='" . $key3[0]['id'] . "'");
                        }

                        foreach ($package_ids1 as $key4)
                            $package_ids1 = [];
                        $test_ids1 = [];
                        $test_tat = [];
                        $package_tat = [];
                        $job_test_list = [];
                        $package_ids = $this->job_model->get_job_booking_package($key["id"]);
                        $job_test_list = $this->job_model->get_val("SELECT `job_test_list_master`.*,`test_master`.`test_name` FROM `job_test_list_master` INNER JOIN `test_master` ON `job_test_list_master`.`test_fk`=`test_master`.`id` WHERE `job_test_list_master`.`job_fk`='" . $key["id"] . "'");

//                    foreach ($job_test_list as $key1) {
//                        $test_ids1[] = $this->job_model->get_val("select id from test_master where status='1' AND test_name like '%" . $key1['test_name'] . "%'");
//                    }

                        foreach ($package_ids as $key2) {
                            $package_ids1[] = $this->job_model->get_val("select id from package_master where status='1' AND title like '%" . str_replace("'", "''", $key2['name']) . "%'");
                        }

                        foreach ($job_test_list as $key3) {
                            $test_tat[] = $this->job_model->get_val("select tat from test_tat where status='1' AND type='1' AND test_fk='" . $key3['test_fk'] . "'");
                        }

                        foreach ($package_ids1 as $key4) {
                            $package_tat[] = $this->job_model->get_val("select tat from test_tat where status='1' AND type='2' AND test_fk='" . $key4[0]['id'] . "'");
                        }
                        //echo "<pre>"; print_r($package_tat); exit;
                        if (!empty($test_tat)) {
                            foreach ($test_tat as $key11) {
                                //if ($key11[0]['tat'] != "") {
                                if (!empty($key11)) {
                                    if ($max_test_tat < $key11[0]['tat']) {
                                        $max_test_tat = $key11[0]['tat'];
                                    }
                                }
                            }
                        }

                        if (!empty($package_tat)) {
                            foreach ($package_tat as $key12) {
//                            if ($key12[0]['tat'] != "") {
                                if (!empty($key12)) {
                                    if ($max_package_tat < $key12[0]['tat']) {
                                        $max_package_tat = $key12[0]['tat'];
                                    }
                                }
                            }
                        }

                        if ($max_test_tat > $max_package_tat) {
                            $max_tat = $max_test_tat;
                        } else {
                            $max_tat = $max_package_tat;
                        }

                        $sample_collect_time = $this->job_model->get_val("select job_status,date_time from job_log where status='1' AND job_status='6-7' AND job_fk=" . $key["id"]);
                        $sample_date = date('Y-m-d', strtotime($sample_collect_time[0]['date_time']));
                        $sample_time = date('H:i:s', strtotime($sample_collect_time[0]['date_time']));

                        $processing = $this->job_model->get_val("select job_status,date_time from job_log where status='1' AND job_status='7-8' AND job_fk=" . $key["id"]);
                        $processing_date = date('Y-m-d', strtotime($processing[0]['date_time']));
                        $processing_time = date('H:i:s', strtotime($processing[0]['date_time']));

                        if ($key['status'] == '8' || $key['status'] == '2') {
                            $phlebo_tat = round(((strtotime($processing_time) - strtotime($sample_time)) / 60) / 60, 2);
                        } else {
                            $processing_date = $processing_time = $phlebo_tat = "Pending";
                        }
                        if ($key['status'] == '2') {
                            $completion_time = $this->job_model->get_val("select job_status,date_time from job_log where status='1' AND job_status='8-2' AND job_fk=" . $key["id"]);
                            //$processing_tat = round(((strtotime($completion_time[0]['date_time']) - strtotime($processing_time)) / 60) / 60, 2);
                            $report_generate_time = $this->job_model->get_val("select created_date from report_master where status = 1 and job_fk=".  $key["id"]);
                            //$processing_tat = round(((strtotime($report_generate_time[0]['created_date']) - strtotime($sample_time)) / 60) / 60, 2);
                            //$processing_tat = date('H:m:s', (strtotime($report_generate_time[0]['created_date']) - strtotime($sample_collect_time[0]['date_time'])));
                            $interval = (new DateTime($report_generate_time[0]['created_date']))->diff(new DateTime($sample_collect_time[0]['date_time']));
                            $processing_tat = $interval->format("%H:%i:%s");
                            $completed_time1 = date('H:i:s', strtotime($completion_time[0]['date_time']));
                            $report_generate_date1 = date('Y-m-d', strtotime($report_generate_time[0]['created_date']));
                            $report_generate_time1 = date('H:i:s', strtotime($report_generate_time[0]['created_date']));
                        } else {
                            $processing_tat = "Pending";
                            $completed_time1 = "Pending";
                            $report_generate_time1 = "Pending";
                        }

                        if ($processing_tat != "Pending") {
                            if ($processing_tat > $max_tat) {
                                $is_processing_delay = $processing_tat - $max_tat;
                            } else {
                                $is_processing_delay = "";
                            }
                        } else {
                            $is_processing_delay = "";
                        }
                    }

                    //fputcsv($handle, array($cnt, $key["id"], $key["order_id"], $key["test_city_name"], $key["branch_name"], $key["date"], $patient_name, $key["mobile"], $key["doctor_name"] . "-" . $key["doctor_mobile"], $key["test_name"], $key["test_type"], $j_status, $key["payment_type"], $key["sample_from"], $formpatient, $key["portal"], $key["note"], $added_by, $key["price"], $discount, $due, $cash, $card, $payumoney, $cheque, $dabitt_from_wallet[0]["dabit"], $creditor_cash_due, $key["payable_amount"], implode("+", $creditor_remark)));
                    fputcsv($handle, array($cnt, $key["id"], $key["order_id"], $key["test_city_name"], $key["branch_name"], $key["date"], $patient_name, $key["mobile"], $key["doctor_name"], $key["doc_code"], $key["doctor_mobile"], (empty($key["pro"]) ? 'NO PRO MEETING' : $key["pro"]), $key["speciality"], $key["ra"] . "%", $key["test_name"], $key["test_type"], $j_status, $key["payment_type"], $key["sample_from"], $formpatient, $key["portal"], $key["note"], $added_by, $key["price"], $discount, $due, $cash, $card, $payumoney, $cheque, $dabitt_from_wallet[0]["dabit"], $creditor_cash_due, $key["payable_amount"], $sample_date, $sample_time, $processing_date, $processing_time, $completed_time1, $report_generate_time1, $phlebo_tat, $processing_tat, $max_tat, $is_processing_delay, $key["amountreceived"]));
                } $cnt++;
            }
        }
        fclose($handle);
        exit;
    }
	
	function export_csv_123() {
        $search_data = array();
        $plm = $data['plm2'] = $search_data["plm"] = $this->input->get('plm');
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
        $withsample = $data['withsample'] = $search_data["withsample"] = $this->input->get_post('withsample');
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
        /* PLM start */
        if (empty($branch) && $plm != '') {
            if (empty($branch) && !empty($city) && empty($plm)) {
                $plm_branch = $this->job_model->get_val("SELECT GROUP_CONCAT(id) AS id FROM `branch_master` WHERE `status`='1' AND city='" . $city . "' GROUP BY status");
            }
            if (empty($branch) && !empty($plm)) {
                $plm_branch = $this->job_model->get_val("select GROUP_CONCAT(id) AS id from branch_master where status='1' and (parent_fk ='" . $plm . "' or id='" . $plm . "') GROUP BY status");
            }
            if (empty($branch) && empty($city) && empty($plm)) {
                $plm_branch = $this->job_model->get_val("select GROUP_CONCAT(id) AS id from branch_master where status='1' GROUP BY status");
            }
            $plm_branch = explode(",", $plm_branch[0]["id"]);
            $cntr_arry = array_merge(array($plm), $plm_branch);
        } else {
            $cntr_arry = $branch;
        }
        /* PLM END */



        if ($data["login_data"]['type'] == '6') {
            $login_id = $data["login_data"]['id'];
            $plm_branch = $this->job_model->get_val("select GROUP_CONCAT(bm.id) AS id 
                 FROM branch_master bm 
                 LEFT JOIN user_branch ub on ub.branch_fk = bm.id 
                 WHERE ub.user_fk = '$login_id' 
                 AND bm.status='1' GROUP BY bm.status");

            $plm_branch = explode(",", $plm_branch[0]["id"]);
            $cntr_arry = $plm_branch;
        }



        $search_data['cntr_arry'] = $cntr_arry;
        $search_data['t_id'] = $t_id;
        $search_data['p_id'] = $p_id;

        $result = $this->job_model->csv_job_list($search_data);
        //    print_r($result);
        //   die("OK");
        $is_regular_test = false;
        $is_panel_test = false;
        /* Nishit code start */

        $new_array = array();
        foreach ($result as $kkey) {
            $is_regular_test = false;
            $is_panel_test = false;
            $job_test_list = $this->job_model->get_val("SELECT `job_test_list_master`.*,`test_master`.`test_name` FROM `job_test_list_master` INNER JOIN `test_master` ON `job_test_list_master`.`test_fk`=`test_master`.`id` WHERE `job_test_list_master`.`job_fk`='" . $kkey["id"] . "'");
            /* Check sub test start */
            $job_tst_lst = array();
            $job_sub_tst_lst = array();
            foreach ($job_test_list as $st_key) {
                //echo $st_key['test_fk'];
                $job_sub_test_list = $this->job_model->get_val("SELECT `sub_test_master`.test_fk,`sub_test_master`.`sub_test`,test_master.`test_name` FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`sub_test`=`test_master`.`id` WHERE `sub_test_master`.`status`='1' AND `test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $st_key['test_fk'] . "'");
                $job_sub_tst_lst[] = $job_sub_test_list;
                $st_key["sub_test"] = $job_sub_test_list;
                $job_tst_lst[] = $st_key;
            }

            $package_ids = $this->job_model->get_job_booking_package($kkey["id"]);
            $is_show = 1;
            $test_list = array();
            $package_list = array();
            if (!empty($test_pack)) {
                $is_show = 0;
                foreach ($job_test_list as $f_key) {
                    if (!in_array($f_key["test_name"], $test_list)) {
                        $test_list[] = $f_key["test_name"];
                    }
                    if (strpos(trim(strtoupper($f_key["test_name"])), trim(strtoupper($test_pack))) !== false) {
                        $is_show = 1;
                    }
                }

                foreach ($package_ids as $p_key) {
                    $package_list[] = $p_key["name"];
                    if (strpos(trim(strtoupper($p_key["name"])), trim(strtoupper($test_pack))) !== false) {
                        $is_show = 1;
                    }
                    foreach ($p_key["test"] as $p_key) {
                        if (!in_array($p_key["test_name"], $test_list)) {
                            $test_list[] = $p_key["test_name"];
                        }
                        if (strpos(trim(strtoupper($p_key["test_name"])), trim(strtoupper($test_pack))) !== false) {
                            $is_show = 1;
                        }
                    }
                }

                foreach ($job_sub_tst_lst as $p_key) {
                    foreach ($p_key as $ss_p_key) {
                        if (!in_array($ss_p_key["test_name"], $test_list)) {
                            $test_list[] = $ss_p_key["test_name"];
                        }
                        if (strpos(trim(strtoupper($ss_p_key["test_name"])), trim(strtoupper($test_pack))) !== false) {
                            $is_show = 1;
                        }
                    }
                }
            } else {
                $is_show = 0;
                foreach ($job_test_list as $f_key) {
                    if (!in_array($f_key["test_name"], $test_list)) {
                        $test_list[] = $f_key["test_name"];
                    }
                    if ($f_key["is_panel"] == "1") {
                        $is_panel_test = true;
                    } else {
                        $is_regular_test = true;
                    }
                    $is_show = 1;
                }

                foreach ($package_ids as $p_key) {
                    $package_list[] = $p_key["name"];
                    foreach ($p_key["test"] as $p_key) {
                        if (!in_array($p_key["test_name"], $test_list)) {
                            $test_list[] = $p_key["test_name"];
                        }
                        $is_show = 1;
                    }
                }

                foreach ($job_sub_tst_lst as $p_key) {
                    foreach ($p_key as $ss_p_key) {
                        if (!in_array($ss_p_key["test_name"], $test_list)) {
                            $test_list[] = $ss_p_key["test_name"];
                        }
                        $is_show = 1;
                    }
                }
            }
            /* END */
            $kkey["is_show"] = $is_show;
            $coma = "";
            if (!empty($package_list)) {
                $coma = ", ";
            }
            $kkey["test_name"] = implode(", ", $package_list) . $coma . implode(", ", $test_list);
            $test_type = "Regular";
            if ($is_panel_test == true && $is_regular_test == true) {
                $test_type = "Regular + Panel";
            } else if ($is_panel_test == true) {
                $test_type = "Panel";
            } else if ($is_regular_test == true) {
                $test_type = "Regular";
            }
            $kkey['test_type'] = $test_type;
            $new_array[] = $kkey;
        }
        /* Nishit code end */
        if (!isset($_REQUEST['de'])) {
            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"All_Jobs_Report-" . date('d-M-Y') . ".csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle = fopen('php://output', 'w');
            //fputcsv($handle, array("No.", "Reg No.", "Order Id", "Test City", "Branch", "Date", "Patient Name", "Mobile No.", "Doctor", "Test/Package Name", "Test Type", "Job Status", "Payment Type", "Sample From", "New Patient/old Patient", "Portal", "Remark", "Added By", "Total Price", "Discount(RS.)", "Received Amount", "Cash", 'Card', "Payumoney", "CHEQUE", "Debited From Wallet", "Creditor Remain", "Due Amount"));
            //fputcsv($handle, array("No.", "Reg No.", "Order Id", "Test City", "Branch", "Date", "Patient Name", "Mobile No.", "Doctor", "Test/Package Name", "Test Type", "Job Status", "Payment Type", "Sample From", "New Patient/old Patient", "Portal", "Remark", "Added By", "Total Price", "Discount(RS.)", "Received Amount", "Cash", 'Card', "Payumoney", "CHEQUE", "Debited From Wallet", "Creditor Remain", "Due Amount", "Sample Received Date", "Sample Received Time", "Processing Date", "Processing Time", "Completed Time", "Report Generate Date", "Report Generate Time", "Phlebo TAT (In Hour)", "Processing TAT (In Hour)", "Max TAT (In Hour)", "Processing Delay (In Hour)"));
			//fputcsv($handle, array("No.", "Reg No.", "Order Id", "Test City", "Branch", "Date", "Patient Name", "Mobile No.", "Doctor", "Doctor_code","Doctor_Mobile", "PRO", "Speciality","Special Discount", "Test/Package Name", "Test Type", "Job Status", "Payment Type", "Sample From", "New Patient/old Patient", "Portal", "Remark", "Added By", "Total Price", "Discount(RS.)", "Received Amount", "Cash", 'Card', "Payumoney", "CHEQUE", "Debited From Wallet", "Creditor Remain", "Due Amount", "Sample Received Date", "Sample Received Time", "Processing Date", "Processing Time", "Completed Time", "Report Generated Time", "Phlebo TAT (In Hour)", "Processing TAT (In Hour)", "Max TAT (In Hour)", "Processing Delay (In Hour)"));
			fputcsv($handle, array("No.", "Reg No.", "Order Id", "Test City", "Branch", "Date", "Patient Name", "Mobile No.", "Doctor", "Doctor_code","Doctor_Mobile", "PRO", "Speciality","Special Discount", "Test/Package Name", "Test Type", "Job Status", "Payment Type", "Sample From", "New Patient/old Patient", "Portal", "Remark", "Added By", "Total Price", "Discount(RS.)", "Received Amount", "Cash", 'Card', "Payumoney", "CHEQUE", "Debited From Wallet", "Creditor Remain", "Due Amount", "Sample Received Date", "Sample Received Time", "Processing Date", "Processing Time", "Completed Time", "Report Generated Time", "Phlebo TAT (In Hour)", "Processing TAT (In Hour)", "Max TAT (In Hour)", "Processing Delay (In Hour)", "test name", "test price", "test disc %", "test disc", "billing", "total disc", "total billing"));
        }
        /* Nishit code start */
        $cnt = 1;

        foreach ($new_array as $key) {
            if ($key["is_show"] == 1) {
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
                /* Nishit 18-08-2017 START */
                $payment_mode = array();
                $creditor_remark = array();
                $discount = 0;
                if ($key["discount"] > 0) {
                    $discount = round($key["price"] * $key["discount"] / 100);
                }
                $added_by = "Online";
                if (!empty($key["phlebo_added_by"])) {
                    $added_by = $key["phlebo_added_by"] . " (Phlebo)";
                } else if (!empty($key["added_by"])) {
                    $added_by = $key["added_by"];
                }
                $cash = 0.0;
                $card = 0.0;
                $cheque = 0.0;
                $other = 0.0;
                $collection_type = $this->job_model->get_val("SELECT REPLACE (GROUP_CONCAT(remark,','), 'credited by', 'Creditor Name : ') AS remark, (`payment_type`) AS payment_type,sum(job_master_receiv_amount.amount) as amount FROM `job_master_receiv_amount` WHERE `status`='1' AND job_fk='" . $key["id"] . "' GROUP BY payment_type ORDER BY payment_type ASC");

                if (count($collection_type) > 0) {
                    foreach ($collection_type as $ct) {
                        if (strtoupper($ct["payment_type"]) == "CASH") {
                            $payment_mode[] = "CASH";
                            $cash += $ct["amount"];
                        } else if (in_array(strtoupper($ct["payment_type"]), array("DEBIT CARD SWIPED THRU ICICI", "DEBIT CARD", "DEBIT CARD", "CREDIT CARD", "PayTm", "PAYTM"))) {
                            $payment_mode[] = "CARD";
                            $card += $ct["amount"];
                            //	die("hiten");
                        } else if (strtoupper($ct["payment_type"]) == "CHEQUE") {
                            $payment_mode[] = "CHEQUE";
                            $cheque += $ct["amount"];
                        } else if (strtoupper($ct["payment_type"]) == "CREDITORS") {
                            $creditor_remark[] = $ct["remark"];
                            //$cheque+=$ct["amount"];
                        }
                        /* else {
                          $other += $ct["amount"];
                          } */
                    }
                }
                $creditor = $this->job_model->get_val("SELECT credit,debit,paid   FROM `creditors_balance` WHERE status=1 and job_id=" . $key["id"]);

                $creditor_cash_collected = 0.0;
                $creditor_cash_due = 0.0;



                if (count($creditor) > 0) {
                    foreach ($creditor as $ct) {
                        if (($ct["credit"]) > 0) {
                            $payment_mode[] = "CREDITOR CREDIT";
                            $creditor_cash_collected += $ct["credit"];
                            $cash += $ct["credit"];
                        }
                        if (($ct["debit"]) > 0) {
                            $payment_mode[] = "CREDITOR DEBIT";
                            $creditor_cash_due += $ct["debit"];
                        }
                    }
                }
                $creditor_cash_due = $creditor_cash_due - $creditor_cash_collected;
                $payumoneyRecord = $this->job_model->get_val("SELECT SUM(amount) as amount FROM `payment` WHERE STATUS='success' AND job_fk=" . $key["id"]);
                $payumoney = 0.0;

                if (count($payumoneyRecord) > 0) {
                    foreach ($payumoneyRecord as $ct) {
                        $payumoney += $ct["amount"];
                    }
                }

                /* Nishit Code for other payment27-09-2017 */

                /* END */

                $dabitt_from_wallet = $this->job_model->get_val("SELECT IF(SUM(`debit`)>0,SUM(`debit`),0) AS dabit FROM `wallet_master` WHERE `job_fk`='" . $key["id"] . "' AND `status`='1'");
                $due = round($key["price"] - $key["payable_amount"] - $discount - $dabitt_from_wallet[0]["dabit"]);
                if ($dabitt_from_wallet[0]["dabit"] > 0) {
                    $payment_mode[] = "WALLET";
                }
                if (strtoupper($key["payment_type"]) == "PAYUMONEY") {
                    $payment_mode[] = "PAYUMONEY";
                }

                /* END */
                if ($key["family_member_fk"] == 0) {
                    $patient_name = $key["full_name"];
                } else {
                    $patient_name = $key["family_name"];
                }



                if (!isset($_REQUEST['de'])) {
                    if ($key["oldpatient"] == '1') {
                        $formpatient = "Old";
                    } else {
                        $formpatient = "New";
                    }


                    if ($data["login_data"]["id"] != '10') {
                        $late_processing = 0;
                        $late_sample_collection = 0;

                        $max_test_tat = 0;
                        $max_package_tat = 0;
                        $max_tat = 0;
                        $phlebo_tat = 0;
                        $processing_tat = 0;

                        $package_ids1 = [];
                        $test_ids1 = [];
                        $test_tat = [];
                        $package_tat = [];
                        $package_ids = $this->job_model->get_job_booking_package($key["id"]);
                        foreach ($job_tst_lst as $key1) {
                            $test_ids1[] = $this->job_model->get_val("select id from test_master where status='1' AND test_name like '%" . str_replace("'", "''", $key1['test_name']) . "%'");
                        }
                        foreach ($package_ids as $key2) {
                            $package_ids1[] = $this->job_model->get_val("select id from package_master where status='1' AND title like '%" . str_replace("'", "''", $key2['name']) . "%'");
                        }

                        foreach ($test_ids1 as $key3) {
                            $test_tat[] = $this->job_model->get_val("select tat from test_tat where status='1' AND type='1' AND test_fk='" . $key3[0]['id'] . "'");
                        }

                        foreach ($package_ids1 as $key4)
                            $package_ids1 = [];
                        $test_ids1 = [];
                        $test_tat = [];
                        $package_tat = [];
                        $job_test_list = [];
                        $package_ids = $this->job_model->get_job_booking_package($key["id"]);
                        $job_test_list = $this->job_model->get_val("SELECT `job_test_list_master`.*,`test_master`.`test_name` FROM `job_test_list_master` INNER JOIN `test_master` ON `job_test_list_master`.`test_fk`=`test_master`.`id` WHERE `job_test_list_master`.`job_fk`='" . $key["id"] . "'");

						//foreach ($job_test_list as $key1) {
						//	$test_ids1[] = $this->job_model->get_val("select id from test_master where status='1' AND test_name like '%" . $key1['test_name'] . "%'");
						//}

                        foreach ($package_ids as $key2) {
                            $package_ids1[] = $this->job_model->get_val("select id from package_master where status='1' AND title like '%" . str_replace("'", "''", $key2['name']) . "%'");
                        }

                        foreach ($job_test_list as $key3) {
                            $test_tat[] = $this->job_model->get_val("select tat from test_tat where status='1' AND type='1' AND test_fk='" . $key3['test_fk'] . "'");
                        }

                        foreach ($package_ids1 as $key4) {
                            $package_tat[] = $this->job_model->get_val("select tat from test_tat where status='1' AND type='2' AND test_fk='" . $key4[0]['id'] . "'");
                        }
                        //echo "<pre>"; print_r($package_tat); exit;
                        if (!empty($test_tat)) {
                            foreach ($test_tat as $key11) {
                                //if ($key11[0]['tat'] != "") {
                                if (!empty($key11)) {
                                    if ($max_test_tat < $key11[0]['tat']) {
                                        $max_test_tat = $key11[0]['tat'];
                                    }
                                }
                            }
                        }

                        if (!empty($package_tat)) {
                            foreach ($package_tat as $key12) {
                            //if ($key12[0]['tat'] != "") {
                                if (!empty($key12)) {
                                    if ($max_package_tat < $key12[0]['tat']) {
                                        $max_package_tat = $key12[0]['tat'];
                                    }
                                }
                            }
                        }

                        if ($max_test_tat > $max_package_tat) {
                            $max_tat = $max_test_tat;
                        } else {
                            $max_tat = $max_package_tat;
                        }

                        $sample_collect_time = $this->job_model->get_val("select job_status,date_time from job_log where status='1' AND job_status='6-7' AND job_fk=" . $key["id"]);
                        $sample_date = date('Y-m-d', strtotime($sample_collect_time[0]['date_time']));
                        $sample_time = date('H:i:s', strtotime($sample_collect_time[0]['date_time']));

                        $processing = $this->job_model->get_val("select job_status,date_time from job_log where status='1' AND job_status='7-8' AND job_fk=" . $key["id"]);
                        $processing_date = date('Y-m-d', strtotime($processing[0]['date_time']));
                        $processing_time = date('H:i:s', strtotime($processing[0]['date_time']));

                        if ($key['status'] == '8' || $key['status'] == '2') {
                            $phlebo_tat = round(((strtotime($processing_time) - strtotime($sample_time)) / 60) / 60, 2);
                        } else {
                            $processing_date = $processing_time = $phlebo_tat = "Pending";
                        }
                        if ($key['status'] == '2') {
                            $completion_time = $this->job_model->get_val("select job_status,date_time from job_log where status='1' AND job_status='8-2' AND job_fk=" . $key["id"]);
                            //$processing_tat = round(((strtotime($completion_time[0]['date_time']) - strtotime($processing_time)) / 60) / 60, 2);
                            $report_generate_time = $this->job_model->get_val("select created_date from report_master where status = 1 and job_fk=".  $key["id"]);
                            //$processing_tat = round(((strtotime($report_generate_time[0]['created_date']) - strtotime($sample_time)) / 60) / 60, 2);
                            //$processing_tat = date('H:m:s', (strtotime($report_generate_time[0]['created_date']) - strtotime($sample_collect_time[0]['date_time'])));
                            $interval = (new DateTime($report_generate_time[0]['created_date']))->diff(new DateTime($sample_collect_time[0]['date_time']));
                            $processing_tat = $interval->format("%H:%i:%s");
                            $completed_time1 = date('H:i:s', strtotime($completion_time[0]['date_time']));
                            $report_generate_date1 = date('Y-m-d', strtotime($report_generate_time[0]['created_date']));
                            $report_generate_time1 = date('H:i:s', strtotime($report_generate_time[0]['created_date']));
                        } else {
                            $processing_tat = "Pending";
                            $completed_time1 = "Pending";
                            $report_generate_time1 = "Pending";
                        }

                        if ($processing_tat != "Pending") {
                            if ($processing_tat > $max_tat) {
                                $is_processing_delay = $processing_tat - $max_tat;
                            } else {
                                $is_processing_delay = "";
                            }
                        } else {
                            $is_processing_delay = "";
                        }
                    }
					//$qry = "select a.*, b.*, tm.test_name from (select bjt.price p1, replace(replace(replace(bjt.test_fk, 'pt-', ''), 'p-', ''), 't-','') test_fk, bjt.test_fk t123 from booked_job_test bjt where bjt.job_fk = " . $key["id"] . " and bjt.status = 1) a inner join test_master tm on tm.id = a.test_fk left join (select test_fk testfk, branch_fk, price p2, case when replace(r_code, ' ', '') = 'SA1' then 50 when  replace(r_code, ' ', '') = 'SA2' then 50 when replace(r_code, ' ', '') = 'SA3' then 35 when  replace(r_code, ' ', '') = 'SA7' then 35 when  replace(r_code, ' ', '') = 'SA4' then 15 when  replace(r_code, ' ', '') = 'SA5' then 15 when  replace(r_code, ' ', '') = 'SA6' then 15 else 0 end code1, r_code from test_branch_price where branch_fk = 164 and status = '1') b on a.test_fk = b.testfk";
					$qry = "select a.*, b.*, tm.test_name from (select bjt.price p1, replace(bjt.test_fk, 't-','') test_fk, bjt.test_fk t123 from booked_job_test bjt where bjt.job_fk = " . $key["id"] . " and bjt.status = 1 and bjt.test_fk like 't-%' ) a inner join test_master tm on tm.id = a.test_fk left join (select test_fk testfk, branch_fk, price p2, case when replace(r_code, ' ', '') = 'SA1' then 50 when  replace(r_code, ' ', '') = 'SA2' then 50 when replace(r_code, ' ', '') = 'SA3' then 35 when  replace(r_code, ' ', '') = 'SA7' then 35 when  replace(r_code, ' ', '') = 'SA4' then 15 when  replace(r_code, ' ', '') = 'SA5' then 15 when  replace(r_code, ' ', '') = 'SA6' then 15 else 0 end code1, r_code from test_branch_price where branch_fk = " . $key["branch_fk"] . " and status = '1') b on a.test_fk = b.testfk and p1 = p2";
                    //echo $qry;exit;
                    $test_codes = $this->job_model->get_val($qry);
                    //print_r($test_codes);exit;
					$cnt = 0;
                    $t_dsc = 0;
                    $t_bil = 0;
					if(count($test_codes) > 0) {
						foreach($test_codes as $code) {
							//fputcsv($handle, array($cnt, $key["id"], $key["order_id"], $key["test_city_name"], $key["branch_name"], $key["date"], $patient_name, $key["mobile"], $key["doctor_name"] . "-" . $key["doctor_mobile"], $key["test_name"], $key["test_type"], $j_status, $key["payment_type"], $key["sample_from"], $formpatient, $key["portal"], $key["note"], $added_by, $key["price"], $discount, $due, $cash, $card, $payumoney, $cheque, $dabitt_from_wallet[0]["dabit"], $creditor_cash_due, $key["payable_amount"], implode("+", $creditor_remark)));
							$cnt++;
                            $t_dsc += ($code["p1"] * $code["code1"] / 100);
                            $t_bil += $code["p1"] - ($code["p1"] * $code["code1"] / 100);
                            if($cnt == count($test_codes))
                                fputcsv($handle, array($cnt, $key["id"], $key["order_id"], $key["test_city_name"], $key["branch_name"], $key["date"], $patient_name, $key["mobile"], $key["doctor_name"], $key["doc_code"], $key["doctor_mobile"], (empty($key["pro"]) ? 'NO PRO MEETING' : $key["pro"]), $key["speciality"], $key["ra"] . "%", $key["test_name"], $key["test_type"], $j_status, $key["payment_type"], $key["sample_from"], $formpatient, $key["portal"], $key["note"], $added_by, $key["price"], $discount, $due, $cash, $card, $payumoney, $cheque, $dabitt_from_wallet[0]["dabit"], $creditor_cash_due, $key["payable_amount"], $sample_date, $sample_time, $processing_date, $processing_time, $completed_time1, $report_generate_time1, $phlebo_tat, $processing_tat, $max_tat, $is_processing_delay, $code["test_name"], $code["p1"], $code["code1"], $code["code1"] > 0 ? ($code["p1"] * $code["code1"] / 100) : 0, $code["code1"] > 0 ? $code["p1"] - ($code["p1"] * $code["code1"] / 100) : $code["p1"], $t_dsc, $t_bil ));
                            else
								fputcsv($handle, array($cnt, $key["id"], $key["order_id"], $key["test_city_name"], $key["branch_name"], $key["date"], $patient_name, $key["mobile"], $key["doctor_name"], $key["doc_code"], $key["doctor_mobile"], (empty($key["pro"]) ? 'NO PRO MEETING' : $key["pro"]), $key["speciality"], $key["ra"] . "%", $key["test_name"], $key["test_type"], $j_status, $key["payment_type"], $key["sample_from"], $formpatient, $key["portal"], $key["note"], $added_by, $key["price"], $discount, $due, $cash, $card, $payumoney, $cheque, $dabitt_from_wallet[0]["dabit"], $creditor_cash_due, $key["payable_amount"], $sample_date, $sample_time, $processing_date, $processing_time, $completed_time1, $report_generate_time1, $phlebo_tat, $processing_tat, $max_tat, $is_processing_delay, $code["test_name"], $code["p1"], $code["code1"], $code["code1"] > 0 ? ($code["p1"] * $code["code1"] / 100) : 0, $code["code1"] > 0 ? $code["p1"] - ($code["p1"] * $code["code1"] / 100) : $code["p1"] ));
						}
					}					
                    else 
                        fputcsv($handle, array($cnt, $key["id"], $key["order_id"], $key["test_city_name"], $key["branch_name"], $key["date"], $patient_name, $key["mobile"], $key["doctor_name"] . "-" . $key["doctor_mobile"], $key["test_name"], $key["test_type"], $j_status, $key["payment_type"], $key["sample_from"], $formpatient, $key["portal"], $key["note"], $added_by, $key["price"], $discount, $due, $cash, $card, $payumoney, $cheque, $dabitt_from_wallet[0]["dabit"], $creditor_cash_due, $key["payable_amount"], $sample_date, $sample_time, $processing_date, $processing_time, $completed_time1, $report_generate_date1,  $report_generate_time1, $phlebo_tat, $processing_tat, $max_tat, $is_processing_delay, "", 0, "", 0, 0));
                } $cnt++;
            }
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

        $result = $this->job_model->csv_job_list2($search_data);
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

    function export_spam_csv() {
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

        $result = $this->job_model->csv_job_list1($search_data);
        //print_r($result); die();

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"All_Jobs_Report-" . date('d-M-Y') . ".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("Reg No.", "Order Id", "Test City", "Branch", "Date", "Patient Name", "Mobile No.", "Doctor", "Address", "Test/Package Name", "Payable Amount", "Debited From Wallet", "Price", "Discount", "Job Status", "Payment Type", "Blood Sample Collected", "Portal"));

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
                $patient_name = $key["full_name"] . " (" . $key['relation'] . ")";
            } else {
                $patient_name = $key["family_name"] . " (" . $key['relation'] . ")";
            }
            fputcsv($handle, array($key["id"], $key["order_id"], $key["test_city_name"], $key["branch_name"], $key["date"], $patient_name, $key["mobile"], $key["doctor_name"] . "-" . $key["doctor_mobile"], $addr, $key["testname"] . " " . $key["packagename"], $key["payable_amount"], $key["cut_from_wallet"], $key["price"], $key["discount"], $j_status, $key["payment_type"], $sample_collected, $key["portal"]));
        }
        fclose($handle);
        exit;
    }

    /*    function export_csv() {
      $result = $this->job_model->csv_job_list();
      $data['city'] = $this->job_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
      $cnt = 0;
      foreach ($result as $key) {
      $w_prc = 0;
      $booked_tests = $this->job_model->master_fun_get_tbl_val("wallet_master", array('job_fk' => $key["id"]), array("id", "asc"));
      foreach ($booked_tests as $tkey) {
      $w_prc = $w_prc + $tkey["debit"];
      }
      $test_city = $this->job_model->master_fun_get_tbl_val("test_cities", array('id' => $key["test_city"]), array("id", "asc"));
      $user_info = $this->job_model->master_fun_get_tbl_val("customer_master", array('id' => $key["cid"]), array("id", "asc"));
      $get_state = $this->job_model->master_fun_get_tbl_val("state", array('id' => $user_info[0]["state"]), array("id", "asc"));
      $getcity = $this->job_model->master_fun_get_tbl_val("city", array('id' => $user_info[0]["city"]), array("id", "asc"));
      if ($key["address"] == null || $key["address"] == '') {
      $result[$cnt]["address"] = $user_info[0]["address"];
      }
      $result[$cnt]["state"] = $get_state[0]["state_name"];
      $result[$cnt]["city"] = $getcity[0]["city_name"];
      $result[$cnt]["test_city"] = $test_city[0]["name"];
      $result[$cnt]["cut_from_wallet"] = $w_prc;
      $cnt++;
      }
      header("Content-type: application/csv");
      header("Content-Disposition: attachment; filename=\"All_Jobs_Report-" . date('d-M-Y') . ".csv\"");
      header("Pragma: no-cache");
      header("Expires: 0");
      $handle = fopen('php://output', 'w');
      fputcsv($handle, array("Order Id", "Date", "Customer Name", "Mobile No.", "state", "city", "Address", "Test/Package Name", "Payable Amount", "Debited From Wallet", "Price", "Job Status", "Payment Type", "Blood Sample Collected", "Portal"));

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
      if (!$key["payable_amount"]) {
      $key["payable_amount"] = 0;
      }
      fputcsv($handle, array($key["order_id"], $key["date"], $key["full_name"], $key["mobile"], $key["state"], $key["city"], $key["address"], $key["testname"] . " " . $key["packagename"], $key["payable_amount"], $key["cut_from_wallet"], $key["price"], $j_status, $key["payment_type"], $sample_collected, $key["portal"]));
      }
      fclose($handle);
      exit;
      } */

    function upload_report($cid = "") {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $files = $_FILES;
        $this->load->library('upload');
        $testid = $this->input->post('testids');
        $testtype = $this->input->post('testtype');
        $testids = $this->input->post('testids');
        $report_status = $this->input->post('report_status');
        $ts = $testids;
        $file_loop = count($_FILES['userfile']['name']);
        $file_upload = array();
        if (empty($_FILES['common_report']['name'])) {
            if (!empty($_FILES['userfile']['name'])) {
                for ($i = 0; $i < $file_loop; $i++) {
                    $desc = $this->input->post('desc_' . $i);
                    $_FILES['userfile']['name'] = $files['userfile']['name'][$i];
                    $_FILES['userfile']['type'] = $files['userfile']['type'][$i];
                    $_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
                    $_FILES['userfile']['error'] = $files['userfile']['error'][$i];
                    $_FILES['userfile']['size'] = $files['userfile']['size'][$i];
                    $config['upload_path'] = './upload/report/';
                    $config['allowed_types'] = 'pdf';
                    $config['file_name'] = time() . $files['userfile']['name'][$i];
                    $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                    //$config['file_name'] = str_replace('.', '_', $config['file_name']);
                    $config['overwrite'] = FALSE;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload()) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata("error", array($error));
                        redirect('job-master/job-details/' . $cid);
                    } else {
                        $file_upload[] = array("job_fk" => $cid, "report" => $config['file_name'], "original" => $_FILES['userfile']['name'], "test_fk" => $ts[$i], "description" => $desc, "type" => $testtype[$i]);
                    }
                }
            }
        } else {
            $desc = $this->input->post('desc_common_report');
            $type_common_report = $this->input->post('type_common_report');
            $_FILES['userfile']['name'] = $files['common_report']['name'];
            $_FILES['userfile']['type'] = $files['common_report']['type'];
            $_FILES['userfile']['tmp_name'] = $files['common_report']['tmp_name'];
            $_FILES['userfile']['error'] = $files['common_report']['error'];
            $_FILES['userfile']['size'] = $files['common_report']['size'];
            $config['upload_path'] = './upload/report/';
            $config['allowed_types'] = 'pdf';
            $config['file_name'] = time() . $files['common_report']['name'];
            $config['file_name'] = str_replace(' ', '_', $config['file_name']);
            $config['overwrite'] = FALSE;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload()) {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata("error", array($error));
                redirect('job-master/job-details/' . $cid);
            } else {
                $file_upload[] = array("job_fk" => $cid, "report" => $config['file_name'], "original" => $_FILES['common_report']['name'], "test_fk" => "", "description" => $desc, "type" => $type_common_report);
            }
            // print_R($file_upload);
            if ($files['common_report1']['name'] != '') {
                $_FILES['userfile']['name'] = $files['common_report1']['name'];
                $_FILES['userfile']['type'] = $files['common_report1']['type'];
                $_FILES['userfile']['tmp_name'] = $files['common_report1']['tmp_name'];
                $_FILES['userfile']['error'] = $files['common_report1']['error'];
                $_FILES['userfile']['size'] = $files['common_report1']['size'];
                $config['upload_path'] = './upload/report/';
                $config['allowed_types'] = 'pdf';
                $config['file_name'] = time() . $files['common_report1']['name'];
                $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                $config['overwrite'] = FALSE;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata("error", array($error));
                    redirect('job-master/job-details/' . $cid);
                } else {
                    $file_upload[0]["without_laterpad"] = $config['file_name'];
                    $file_upload[0]["without_laterpad_original"] = $_FILES['common_report1']['name'];
                }
            }
        }
        //print_r($file_upload); die();
        foreach ($file_upload as $f_key) {
            $row = $this->job_model->master_num_rows("report_master", array("job_fk" => $f_key["job_fk"], "test_fk" => $f_key["test_fk"], "status" => "1"));
            if ($row == 1) {
                $delete = $this->job_model->master_fun_update_multi("report_master", array("job_fk" => $f_key["job_fk"], "test_fk" => $f_key["test_fk"], "type" => $f_key["type"]), array("report" => $f_key["report"], "original" => $f_key["original"], "description" => $f_key["description"], "without_laterpad" => $f_key["without_laterpad"], "without_laterpad_original" => $f_key["without_laterpad_original"]));
            } else {
                $data['query'] = $this->job_model->master_fun_insert("report_master", array("job_fk" => $f_key["job_fk"], "report" => $f_key["report"], "original" => $f_key["original"], "test_fk" => $f_key["test_fk"], "description" => $f_key["description"], "type" => $f_key["type"], "without_laterpad" => $f_key["without_laterpad"], "without_laterpad_original" => $f_key["without_laterpad_original"]));
            }
        }

        //$ts = explode(',', $testid);
        $ctn = 0;
        if (!empty($ts)) {
            foreach ($ts as $key) {
                $desc = $this->input->post('desc_' . $ctn);
                //	die();
                $update = $this->job_model->master_fun_update_multi("report_master", array("test_fk" => $key, "job_fk" => $cid, "type" => $testtype[$ctn]), array("description" => $desc));
                $ctn++;
            }
        }
        $this->job_model->master_fun_update("job_master", array("id", $cid), array("report_status" => $report_status));
        $this->job_model->master_fun_insert("job_log", array("job_fk" => $cid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => "30", "date_time" => date("Y-m-d H:i:s")));
        $this->session->set_flashdata("success", array("Report Upload successfully."));
        redirect('job-master/job-details/' . $cid);
    }

    function remove_report() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $jid = $this->uri->segment('4');
        $data['query'] = $this->job_model->master_fun_update("report_master", array("id", $cid), array("status" => "0"));
        $this->job_model->master_fun_insert("job_log", array("job_fk" => $jid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => "29", "date_time" => date("Y-m-d H:i:s")));
        $this->session->set_flashdata("success", array("Report successfully Remove"));
        redirect("job-master/job-details/" . $jid, "refresh");
    }

    function download_report($name) {
        $this->load->helper('download');
        $data = file_get_contents(base_url() . "/upload/" . $name); // Read the file's contents

        force_download($name, $data);
    }

    function prescription_report() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $user = $this->input->get('typeahead');

        /* $user = $this->input->get('user'); */
        $date = $this->input->get('date');
        $mobile = $this->input->get('mobile');
        $status = $this->input->get('status');
        $data['full_name'] = $user;
        $data['date'] = $date;
        $data['mobile'] = $mobile;
        $data['status'] = $status;
        if ($user != "" || $date != "" || $mobile != "" || $status != "") {
            $total_row = $this->job_model->num_row_srch_prescription($user, $date, $mobile, $status);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "job-master/prescription-report?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 100;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->job_model->row_srch_prescription($user, $date, $mobile, $status, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $total_row = $this->job_model->num_row_srch_prescription("", "", "", "");
            $config["base_url"] = base_url() . "job-master/prescription-report";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 100;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->job_model->srch_prescription($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }
        $data["per_page"] = $page;
        //$data['cityfk'] = $city;
        //print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        //$data['query'] = $this->job_model->prescription_report_search($user, $date, $mobile, $status);
        //$this->load->view('admin/state_list_view', $data);
        $cnt = 0;
        foreach ($data['query'] as $key) {
            if ($key["cid"] == '') {
                $u_dtd = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "mobile" => $key["mobile"]), array("id", "asc"));
                if (!empty($u_dtd)) {
                    $data['query'][$cnt]["cid"] = $u_dtd[0]["id"];
                    $data['query'][$cnt]["full_name"] = $u_dtd[0]["full_name"];
                }
            }
            $cnt++;
        }
        $data['customer'] = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("full_name", "asc"));
        $data['city'] = $this->job_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('prescription_report', $data);
        $this->load->view('footer');
    }

    function prescription_csv_report() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $full_name = $this->input->get('typeahead');

        $mobile = $this->input->get('mobile');
        $date = $this->input->get('date');
        $status = $this->input->get('status');

        /* $result = $this->job_model->prescription_report(); */
        $result = $this->job_model->prescription_report($full_name, $mobile, $date, $status);
        $data['city'] = $this->job_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
        $cnt = 0;
        foreach ($result as $key) {
            $w_prc = 0;
            /* Count booked test price */
            $test_city = $this->job_model->master_fun_get_tbl_val("test_cities", array('id' => $key["city"]), array("id", "asc"));
            $user_info = $this->job_model->master_fun_get_tbl_val("customer_master", array('id' => $key["cid"]), array("id", "asc"));
            $get_state = $this->job_model->master_fun_get_tbl_val("state", array('id' => $user_info[0]["state"]), array("id", "asc"));
            $getcity = $this->job_model->master_fun_get_tbl_val("city", array('id' => $user_info[0]["city"]), array("id", "asc"));
            $p_test = $this->job_model->master_fun_get_tbl_val("suggested_test", array('p_id' => $key["id"]), array("id", "asc"));
            $tst_name = array();
            foreach ($p_test as $tkey) {
                $test_info = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city[0]["id"] . "' AND `test_master`.`id`='" . $tkey["test_id"] . "'");
                $tst_name[] = $test_info[0]["test_name"] . "(Rs." . $test_info[0]["price"] . ")";
            }

            $result[$cnt]["address"] = $user_info[0]["address"];
            $result[$cnt]["state"] = $get_state[0]["state_name"];
            $result[$cnt]["city"] = $getcity[0]["city_name"];
            $result[$cnt]["test_city"] = $test_city[0]["name"];
            $result[$cnt]["sugested_test"] = implode(",", $tst_name);
            if ($key["status"] == 1) {
                $result[$cnt]["status"] = "Pending";
            } else {
                $result[$cnt]["status"] = "Completed";
            }
            $cnt++;
        }
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"All_Prescription_Report-" . date('d-M-Y') . ".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("Customer Name", "Mobile No.", "Date", "Address", "Test City", "Status", "Sugested Test"));

        foreach ($result as $key) {
            fputcsv($handle, array($key["full_name"], $key["mobile"], $key["date"], $key["address"], $key["test_city"], $key["status"], $key["sugested_test"]));
        }
        fclose($handle);
        exit;
    }

    /* function prescription_details() {
      if (!is_loggedin()) {
      redirect('login');
      }
      $data["login_data"] = logindata();

      $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
      $data['cid'] = $this->uri->segment(3);
      $data['success'] = $this->session->flashdata("success");
      $data['error'] = $this->session->flashdata("error");
      $data['query'] = $this->job_model->prescription_details($data['cid']);
      $data['test'] = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["city"] . "'");
      $data['test_check'] = $this->job_model->get_suggested_test($data['cid']);
      $data['state'] = $this->job_model->master_fun_get_tbl_val("state", array('status' => 1), array("state_name", "asc"));
      $data['city'] = $this->job_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("name", "asc"));
      $data['city1'] = $this->job_model->master_fun_get_tbl_val("city", array('status' => 1), array("city_name", "asc"));
      $this->load->view('header');
      $this->load->view('nav', $data);
      $this->load->view('test', $data);
      $this->load->view('footer');
      } */

    function prescription_details() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->model('job_model');
        $upd = $this->job_model->master_fun_update("prescription_upload", array("id", $this->uri->segment(3)), array("is_read" => "1"));
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $this->uri->segment(3);
        $data['success'] = $this->session->flashdata("success");
        $data['error'] = $this->session->flashdata("error");
        $data['test_error'] = $this->session->flashdata("test_error");
        $this->load->model('job_model');
        $data['cid'] = $this->uri->segment(3);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('prescription_fk', 'Job Id', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $test_city = $this->input->post("test_city");
            $customer = $this->input->post("customer");
            if ($customer == 0) {
                $name = $this->input->post("name");
                $phone = $this->input->post("phone");
                $email = $this->input->post("email");
                $password = $this->input->post("password");
                $gender = $this->input->post("gender");
                $address = $this->input->post("address");
                $note = $this->input->post("note");
                $total_amount = $this->input->post("total_amount");
                $c_data = array("full_name" => $name, "gender" => $gender, "email" => $email, "password" => $password, "mobile" => $phone, "address" => $address, "test_city" => $test_city);
                $uid = $this->job_model->master_fun_insert("customer_master", $c_data);
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $this->load->helper("Email");
                $email_cnt = new Email;

                $message = '<div style="padding:0 4%;">
                    <h4><b>Create Account</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your account successfully created. </p>
                        <p style="color:#7e7e7e;font-size:13px;"> Username/Email : . ' . $email . '  </p>  
                        <p style="color:#7e7e7e;font-size:13px;"> Password : ' . $password . '  </p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message = $email_cnt->get_design($message);
                $this->email->to($email);
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Account Created Successfully');
                $this->email->message($message);
                $this->email->send();
                $this->job_model->master_fun_update("prescription_upload", array("id", $data['cid']), array("cust_fk" => $uid));
            }
            if ($customer == 1) {
                $uid = $this->input->post("userid");
            }
            $upd = $this->job_model->master_fun_update("prescription_upload", array("id", $this->uri->segment(3)), array("status" => "2"));
            $submit_type = $this->input->post("submit_type");
            if ($submit_type == '1') {
                $id = $this->uri->segment(3);
                $test = $this->input->post('test');
                //$uid = $this->input->post('userid');
                $payable = $this->input->post('payable');
                $discount = $this->input->post('discount');
                $total_amount = $this->input->post('total_amount');
                $order_id = $this->get_job_id($test_city);
                $date = date('Y-m-d H:i:s');
                //$test = explode(',', $test);
                $test_package_name = array();
                /* foreach ($test as $key) {
                  $price1 = $this->job_model->master_fun_get_tbl_val("test_master", array("status" => 1, "id" => $key), array("test_name", "asc"));
                  $data['test'] = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["test_city"] . "'");
                  $price += $price1[0]['price'];
                  $test_package_name[] = $price1[0]['test_name'];
                  } */
                $price = 0;
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $result = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $tn[1] . "'");
                        $price += $result[0]["price"];
                        $test_package_name[] = $result[0]["test_name"];
                    }
                    if ($tn[0] == 'p') {
                        //print_r(array('package_fk' => $tn[1], "city_fk" => $test_city)); die();
                        $query = $this->db->get_where('package_master_city_price', array('package_fk' => $tn[1], "city_fk" => $test_city));
                        $result = $query->result();
                        $query1 = $this->db->get_where('package_master', array('id' => $tn[1]));
                        $result1 = $query1->result();
                        $price += $result[0]->d_price;
                        $test_package_name[] = $result1[0]->title;
                    }
                }
                $data = array(
                    "order_id" => $order_id,
                    "cust_fk" => $uid,
                    "date" => $date,
                    "price" => $total_amount,
                    "status" => '6',
                    "payment_type" => "Cash On Delivery",
                    "discount" => $discount,
                    "payable_amount" => $payable,
                    "test_city" => $test_city,
                    "note" => $this->input->post('note'),
                    "added_by" => $data["login_data"]["id"]
                );
                $insert = $this->job_model->master_fun_insert("job_master", $data);
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1]));
                    }
                    if ($tn[0] == 'p') {
                        $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                    }
                }
                $destail = $this->job_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Booking successfully. </p>
                        <p style="color:#7e7e7e;font-size:13px;"> You Booked : . ' . implode($test_package_name, ', ') . '  </p>  
                        <p style="color:#7e7e7e;font-size:13px;"> Your Booked Amount is Rs. ' . $price . '  </p>
                        <p style="color:#7e7e7e;font-size:13px;"> Payment Type : Cash on Blood Collection</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message = $email_cnt->get_design($message);
                $this->email->to($destail[0]['email']);
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Test Book Successfully');
                $this->email->message($message);
                $this->email->send();

                $this->session->set_flashdata("success", array("Booked Successfully"));
                redirect("job_master/prescription_report", "refresh");
            }
            if ($submit_type == '0') {
                $data["login_data"] = logindata();
                $this->load->model("user_model");
                $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
                $cid = $this->uri->segment('3');
                $test = $this->input->post('test');
                $desc = $this->input->post('desc');
                $update = $this->job_model->master_fun_update_multi("suggested_test", array('p_id' => $cid), array("status" => 0));
                $price = 0;
                $test_name_mail = array();
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        //$this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1]));
                        $result = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $tn[1] . "'");
                        $price += $result[0]["price"];
                        $test_name_mail[] = $result[0]["test_name"];
                        $data = array(
                            "p_id" => $cid,
                            "test_id" => $tn[1]
                                //"description" => $desc[$i]
                        );
                        $chk = $this->job_model->master_fun_get_tbl_val("suggested_test", array('status' => 1, 'p_id' => $cid, 'test_id' => $tn[1]), array("id", "asc"));
                        $test_check = $this->job_model->master_fun_get_tbl_val("suggested_test", array('status' => 1, 'p_id' => $cid), array("id", "asc"));
                        $this->job_model->master_fun_insert("suggested_test", $data);
                        $upd = $this->job_model->master_fun_update("prescription_upload", array("id", $cid), array("status" => "2", "discount" => $this->input->post("discount"), "city" => $this->input->post("test_city")));
                    }
                    if ($tn[0] == 'p') {
                        $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                        $query = $this->db->get_where('package_master_city_price', array('package_fk' => $tn[1], "city_fk" => $test_city));
                        $result = $query->result();
                        $query1 = $this->db->get_where('package_master', array('id' => $tn[1]));
                        $result1 = $query1->result();
                        $price += $result[0]->d_price;
                        $test_name_mail[] = $result1[0]->title;
                    }
                }
                $data = $this->job_model->master_fun_get_tbl_val("prescription_upload", array("id" => $cid), array("id", "asc"));
                $cust_fk = $data[0]['cust_fk'];
                $img = $data[0]['image'];
                $desc = $data[0]['description'];
                $orderid = $data[0]['order_id'];
                $created_date = $data[0]['created_date'];
                $data1 = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));

                $device_id = $data1[0]['device_id'];
                $mobile = $data1[0]['mobile'];
                $name = $data1[0]['full_name'];
                $email = $data1[0]['email'];
                $device_type = $data1[0]['device_type'];
                $message = "Your Suggested Test has been Generated";

                if ($device_type == 'android') {
                    $notification_data = array("title" => "AirmedLabs", "message" => $message, "type" => "suggested_test", "id" => $cid, "img" => $img, "desc" => $desc, "created_date" => $created_date, "order_id" => $orderid);
                    //print_r($notification_data); die();
                    $pushServer = new PushServer();
                    $pushServer->pushToGoogle($device_id, $notification_data);
                    //print_r($result);
                }
                if ($device_type == 'ios') {
                    $url = 'http://website-demo.in/patholabpush/push.php?device_id=' . $device_id . '&msg=' . $message . '&type=suggested_test&testid=&testname=&testprice=&testdate=&id=' . $cid . '&desc=' . $desc . '&date=' . $created_date . '&orderid=' . $orderid . '&img=' . $img;
                    $url = str_replace(" ", "%20", $url);
                    $data = $this->get_content($url);

                    $data2 = json_decode($data);
                }
                /* Nishit send sms code start */
                $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "Suggested_Test_Generated"), array("id", "asc"));
                $sms_message = preg_replace("/{{NAME}}/", ucfirst($data1[0]["full_name"]), $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{OID}}/", $orderid, $sms_message);
                $sms_message = preg_replace("/{{PRICE}}/", '', $sms_message);
                $this->load->helper("sms");
                $notification = new Sms();
                $notification::send($mobile, $sms_message);
                /* Nishit send sms code end */
                $config['mailtype'] = 'html';
                $pathToUploadedFile = base_url() . "upload/" . $img;
                $this->email->initialize($config);

                $message1 = '<div style="padding:0 4%;">
                    <h4><b>Dear ' . $name . '</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Suggested Test has been Generated. </p>
						 <p style="color:#7e7e7e;font-size:13px;">Your Suggested Test are ' . implode($test_name_mail, ', ') . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Order ID : ' . $orderid . '</p>    
		<p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message1 = $email_cnt->get_design($message1);
                $this->email->to($email);
                $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
                $this->email->subject("Suggested Test has been Generated");
                $this->email->message($message1);
                $this->email->send();

                $this->session->set_flashdata("success", array("Test Suggested successfully"));
                redirect("job_master/prescription_report", "refresh");
            }
        }

        $data['query'] = $this->job_model->prescription_details($data['cid']);
        $data['test'] = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["city"] . "'");
        $data['test_info'] = $this->job_model->get_suggested_test($data['cid']);
        $data['state'] = $this->job_model->master_fun_get_tbl_val("state", array('status' => 1), array("state_name", "asc"));
        $data['city'] = $this->job_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("name", "asc"));
        $data['user_info'] = array();
        if ($data['query'][0]["cust_fk"] != '') {
            $data['user_info'] = $this->job_model->master_fun_get_tbl_val("customer_master", array('id' => $data['query'][0]["cust_fk"]), array("id", "asc"));
        }
        $data['test_cities'] = $this->job_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
        if ($data['query'][0]["city"] == null) {
            $data['query'][0]["city"] = 1;
        }

        if ($data['query'][0]["job_fk"]) {
            $data['book_test_details'] = $this->job_model->master_fun_get_tbl_val("job_master", array('id' => $data['query'][0]["job_fk"]), array("id", "asc"));
            $data["n_discount"] = $data['book_test_details'][0]["discount"];
        } else {
            $data["n_discount"] = $data['query'][0]["discount"];
        }
        //echo $data["n_discount"];
        //print_r($data['query']); die();
        //print_r($data['query']); die();
        $data['test'] = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["city"] . "'");
        /* $data["package"] = $this->job_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $data['query'][0]["city"] . "' "); */
        $this->job_model->master_fun_update("prescription_upload", array("id", $this->uri->segment(3)), array("is_read" => "1"));
        $data['unread'] = $this->job_model->master_fun_get_tbl_val("prescription_upload", array('status' => 1, "is_read" => "0"), array("id", "asc"));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('prescription_details_new', $data);
        $this->load->view('footer');
    }

    function get_test_list() {
        $ids = $this->input->post('ids');
        $cnt = 0;
        /* foreach ($ids as $key) {
          $nw = explode("-", $key);
          $ids[$cnt] = $nw[1];
          $cnt++;
          }
          $id = implode(",", $ids); */
        $testid = array();
        $packageid = array();
        foreach ($ids as $key) {
            $tn = explode("-", $key);
            if ($tn[0] == 't') {
                $testid[$cnt] = $tn[1];
            }
            if ($tn[0] == 'p') {
                $packageid[$cnt] = $tn[1];
            }
            $cnt++;
        }
        if (!empty(testid)) {
            $id = implode(",", $testid);
        }
        if (!empty($packageid)) {
            $pids = implode(",", $packageid);
        }
        $pds = $this->input->post('pid');
        $ctid = $this->input->post('ctid');
        $data['query'] = $this->job_model->prescription_details($pds);
        if ($data['query'][0]["city"] == '') {
            $data['query'][0]["city"] = 1;
        } else {
            $data['query'][0]["city"] = $ctid;
        }
        if ($ids != NULL) {
            if ($id != NULL) {
                $test = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND test_master.`id` NOT IN ($id) AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["city"] . "'");
            } else {
                $test = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["city"] . "'");
            }
            if ($pids != NULL) {
                $package = $this->job_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND package_master.`id` NOT IN ($pids) AND `package_master_city_price`.`city_fk` = '" . $data['query'][0]["city"] . "' ");
            } else {
                $package = $this->job_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $data['query'][0]["city"] . "' ");
            }
        } else {
            $test = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["city"] . "'");
            $package = $this->job_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $data['query'][0]["city"] . "' ");
        }
        echo '<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">
                                jQuery(".chosen-select").chosen({
                                    search_contains: true
                                });
				/*$("#exampleModal").modal("show");*/
				$("#show_test_btn").attr("style","display:none;");
                            </script>';
        echo '<select class="chosen-select" data-live-search="true" id="test" data-placeholder="Select Test">
			<option value="">--Select Test--</option>';
        foreach ($test as $ts) {
            echo ' <option value="t-' . $ts['id'] . '"> ' . ucfirst($ts['test_name']) . ' (Rs.' . $ts['price'] . ')</option>';
        }
        /* foreach ($package as $pk) {
          echo ' <option value="p-' . $pk['id'] . '"> ' . ucfirst($pk['title']) . ' (Rs.' . $pk['d_price'] . ')</option>';
          } */
        echo '</select>';
        //echo "<pre>"; print_r($data['test']);
    }

    function get_test($city) {
        if (!is_loggedin()) {
            redirect('login');
        }
        if ($city != "") {
            $testdetils = $this->job_model->get_testval($city);
            $json_array = array();

            foreach ($testdetils as $teval) {

                $lable['testid'] = $teval['test_fk'];
                $lable['testname'] = $teval['test_name'];
                $lable['testprice'] = $teval['price'];
                array_push($json_array, $lable);
            } echo json_encode($json_array);
        } else {
            show_404();
        }
    }

    function suggest_test() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->helper("Email");
        $email_cnt = new Email;

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $test = $this->input->post('test');
        //	print_r($test); die();
        $desc = $this->input->post('desc');
        $update = $this->job_model->master_fun_update_multi("suggested_test", array('p_id' => $cid), array("status" => 0));
        for ($i = 0; $i < count($test); $i++) {
            $data = array(
                "p_id" => $cid,
                "test_id" => $test[$i],
                "description" => $desc[$i]
            );
            $chk = $this->job_model->master_fun_get_tbl_val("suggested_test", array('status' => 1, 'p_id' => $cid, 'test_id' => $test[$i]), array("id", "asc"));
            $test_check = $this->job_model->master_fun_get_tbl_val("suggested_test", array('status' => 1, 'p_id' => $cid), array("id", "asc"));
            $insert = $this->job_model->master_fun_insert("suggested_test", $data);
            $upd = $this->job_model->master_fun_update("prescription_upload", array("id", $cid), array("status" => "2"));
        }
        $test_name_mail = array();
        for ($i = 0; $i < count($test); $i++) {
            $data = $this->job_model->master_fun_get_tbl_val("test_master", array("status" => 1, 'id' => $test[$i]), array("id", "asc"));
            $price = $price + $data[0]['price'];
            $test_name_mail[$i] = $data[0]['test_name'];
        }

        $data = $this->job_model->master_fun_get_tbl_val("prescription_upload", array("id" => $cid), array("id", "asc"));
        $cust_fk = $data[0]['cust_fk'];
        $img = $data[0]['image'];
        $desc = $data[0]['description'];
        $orderid = $data[0]['order_id'];
        $created_date = $data[0]['created_date'];
        $data1 = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));

        $device_id = $data1[0]['device_id'];
        $mobile = $data1[0]['mobile'];
        $name = $data1[0]['full_name'];
        $email = $data1[0]['email'];
        $device_type = $data1[0]['device_type'];
        $message = "Your Suggested Test has been Generated";

        if ($device_type == 'android') {
            $notification_data = array("title" => "AirmedLabs", "message" => $message, "type" => "suggested_test", "id" => $cid, "img" => $img, "desc" => $desc, "created_date" => $created_date, "order_id" => $orderid);
            //print_r($notification_data); die();
            $pushServer = new PushServer();
            $pushServer->pushToGoogle($device_id, $notification_data);
            //print_r($result);
        }
        if ($device_type == 'ios') {
            $url = 'http://website-demo.in/patholabpush/push.php?device_id=' . $device_id . '&msg=' . $message . '&type=suggested_test&testid=&testname=&testprice=&testdate=&id=' . $cid . '&desc=' . $desc . '&date=' . $created_date . '&orderid=' . $orderid . '&img=' . $img;
            $url = str_replace(" ", "%20", $url);
            $data = $this->get_content($url);

            $data2 = json_decode($data);
        }
        /* Nishit send sms code start */
        $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "Suggested_Test_Generated"), array("id", "asc"));
        $sms_message = preg_replace("/{{NAME}}/", ucfirst($data1[0]["full_name"]), $sms_message[0]["message"]);
        $sms_message = preg_replace("/{{OID}}/", $orderid, $sms_message);
        $sms_message = preg_replace("/{{PRICE}}/", '', $sms_message);
        $this->load->helper("sms");
        $notification = new Sms();
        $notification::send($mobile, $sms_message);
        /* Nishit send sms code end */
        $config['mailtype'] = 'html';
        $pathToUploadedFile = base_url() . "upload/" . $img;
        $this->email->initialize($config);

        $message1 = '<div style="padding:0 4%;">
                    <h4><b>Dear, ' . $name . '</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Suggested Test has been Generated. </p>
						 <p style="color:#7e7e7e;font-size:13px;">Your Suggested Test are ' . implode($test_name_mail, ', ') . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Order ID : ' . $orderid . '</p>    
		<p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
        $message1 = $email_cnt->get_design($message1);
        $this->email->to($email);
        $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
        $this->email->subject("Suggested Test has been Generated");
        $this->email->message($message1);
        $this->email->send();

        $this->session->set_flashdata("success", array("Test Suggested Successfully"));
        redirect("job-master/prescription-details/" . $cid, "refresh");
    }

    function Package_test_inquiry_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        //print_r($data["login_data"]); die();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        //$data['query'] = $this->job_model->package_test_inquiry();
        $mobile = $this->input->get('mobile');
        $package = $this->input->get('package');
        $date = $this->input->get('date');
        $test = $this->input->get('test');
        $user = $this->input->get('user');
        $status = $this->input->get('status');
        $data['statusid'] = $status;
        $data['mobile'] = $mobile;
        $data['package'] = $package;
        $data['date'] = $date;
        $data['test'] = $test;
        $data['customerfk'] = $user;
        if ($date != "" || $status != "" || $mobile != "" || $package != "" || $test != "" || $user != "") {
            $total_row = $this->job_model->num_row_srch_pti_list($mobile, $package, $test, $user, $status, $date);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "job_master/Package_test_inquiry_list?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->job_model->row_srch_pti_list($mobile, $package, $test, $user, $status, $date, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $total_row = $this->job_model->num_srch_pti_list();
            $config["base_url"] = base_url() . "job_master/Package_test_inquiry_list";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next <span class="icon-text">&#59230;</span>';
            $config['prev_link'] = '<span class="icon-text">&#59229;</span> Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->job_model->srch_pti_list($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }
        $data["page"] = $page;
        //print_r($data['query']); die();
        $data['customer'] = $this->job_model->master_fun_get_tbl_val("customer_master", array("status" => "1"));
        $data['package_list'] = $this->job_model->master_fun_get_tbl_val("package_master", array("status" => "1"));
        $data['test_list'] = $this->job_model->master_fun_get_tbl_val("test_master", array("status" => "1"));
        //$this->load->view('admin/state_list_view', $data);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('package_test_inquiry_list', $data);
        $this->load->view('footer');
    }

    function inquiry_csv_report() {
        $result = $this->job_model->inquiry_csv_report();
        /* $data['city'] = $this->job_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc")); */
        $cnt = 0;
        foreach ($result as $key) {
            $w_prc = 0;
            /* Count booked test price */
            $user_info = $this->job_model->master_fun_get_tbl_val("customer_master", array('id' => $key["uid"]), array("id", "asc"));
            $get_state = $this->job_model->master_fun_get_tbl_val("state", array('id' => $user_info[0]["state"]), array("id", "asc"));
            $getcity = $this->job_model->master_fun_get_tbl_val("city", array('id' => $user_info[0]["city"]), array("id", "asc"));
            $result[$cnt]["address"] = $user_info[0]["address"];
            $result[$cnt]["state"] = $get_state[0]["state_name"];
            $result[$cnt]["city"] = $getcity[0]["city_name"];
            if ($key["status"] == 1) {
                $result[$cnt]["status"] = "Pending";
            } else {
                $result[$cnt]["status"] = "Completed";
            }
            $cnt++;
        }
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Inquiry_Report-" . date('d-M-Y') . ".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("Mobile No.", "Date", "Inquiry Package", "Inquiry Test", "Customer Name", "state", "city", "Address", "Status"));

        foreach ($result as $key) {
            fputcsv($handle, array($key["mobile"], $key["date"], $key["packagename"], $key["testname"], $key["full_name"], $key["state"], $key["city"], $key["address"], $key["status"]));
        }
        fclose($handle);
        exit;
    }

    function check_phone() {
        $data = array("status" => "0", "count" => "0", "id" => "");
        if (is_loggedin()) {
            $mobile = $this->input->get_post("mobile");
            $user_data = $this->job_model->master_fun_get_tbl_val("customer_master", array("status" => "1", "mobile" => $mobile));
            $data = array("status" => "1", "count" => count($user_data), "id" => $user_data[0]["id"]);
        }
        echo json_encode($data);
    }

    function contact_pending() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->job_model->master_fun_update("book_without_login", array("id", $cid), array("status" => "1"));

        $this->session->set_flashdata("success", array("Pending successfully"));
        redirect("job_master/Package_test_inquiry_list", "refresh");
    }

    function contact_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->job_model->master_fun_update("book_without_login", array("id", $cid), array("status" => "0"));

        $this->session->set_flashdata("success", array("Inquiry  Deleted Successfully"));
        redirect("job_master/Package_test_inquiry_list", "refresh");
    }

    function contact_complete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->job_model->master_fun_update("book_without_login", array("id", $cid), array("status" => "2"));

        $this->session->set_flashdata("success", array("Completed successfully"));
        redirect("job_master/Package_test_inquiry_list", "refresh");
    }

    function book_by_admin() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->helper("Email");
        $email_cnt = new Email;

        $data["login_data"] = logindata();
        $test = $this->input->get_post('test_fk');
        $package = $this->input->get_post('package_fk');
        $uid = $this->input->get_post('uid');
        $id = $this->input->get_post('id');
        $order_id = $this->get_job_id("1");
        $date = date('Y-m-d H:i:s');
        $test = explode(',', $test);
        $package = explode(',', $package);
        $test_package_name = array();
        foreach ($test as $key) {
            /*    $data['test'] = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["city"] . "'");
              $data["package"] = $this->job_model->get_val("SELECT
              `package_master`.*,
              `package_master_city_price`.`a_price` AS `a_price`,
              `package_master_city_price`.`d_price` AS `d_price`
              FROM
              `package_master`
              INNER JOIN `package_master_city_price`
              ON `package_master`.`id` = `package_master_city_price`.`package_fk`
              WHERE `package_master`.`status` = '1'
              AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $data['query'][0]["city"] . "' "); */
            $price1 = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='1' AND `test_master`.`id`='" . $key . "'");
            $price += $price1[0]['price'];
            $test_package_name[] = $price1[0]['test_name'];
        }
        foreach ($package as $key) {
            $price1 = $this->job_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '1' and `package_master`.`id`='" . $key . "' ");
            //$price1 = $this->user_test_master_model->master_fun_get_tbl_val("package_master", array("status" => 1, "id" => $key), array("title", "asc"));
            $price += $price1[0]['d_price'];
            $test_package_name[] = $price1[0]['title'];
        }

        //echo $price ; die();
        // Add in job master 
        $data = array(
            "order_id" => $order_id,
            "cust_fk" => $uid,
            "date" => $date,
            "price" => $price,
            "status" => '1',
            "payment_type" => "Cash On Delivery",
            "payable_amount" => $price,
            "test_city" => "1",
            "added_by" => $data["login_data"]["id"]
        );
        //print_r($data); die();
        $insert = $this->user_test_master_model->master_fun_insert("job_master", $data);
        foreach ($test as $key) {

            $this->user_test_master_model->master_fun_insert("job_test_list_master", array("job_fk" => $insert, "test_fk" => $key));
        }
        foreach ($package as $key) {
            $this->user_test_master_model->master_fun_insert("book_package_master", array("job_fk" => $insert, "date" => $date, "order_id" => $order_id, "package_fk" => $key, "cust_fk" => $uid, "type" => "2"));
        }
        $query = $this->job_model->master_fun_update("book_without_login", array("id", $id), array("status" => "2"));

        $destail = $this->user_test_master_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));

        $config['mailtype'] = 'html';
        $this->email->initialize($config);

        $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Booking successfully. </p>
                     <p style="color:#7e7e7e;font-size:13px;"> You Booked : . ' . implode($test_package_name, ', ') . '  </p>  
<p style="color:#7e7e7e;font-size:13px;"> Your Booked Amount is Rs. ' . $price . '  </p>
        <p style="color:#7e7e7e;font-size:13px;"> Payment Type : Cash on Blood Collection</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
        $message = $email_cnt->get_design($message);
        $this->email->to($destail[0]['email']);
        $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
        $this->email->subject('Test Book Successfully');
        $this->email->message($message);
        $this->email->send();

        $this->session->set_flashdata("success", array("Booked successfully"));
        redirect("job_master/Package_test_inquiry_list", "refresh");
    }

    function get_price() {

        $id = $this->input->post(ids);

        foreach ($id as $key) {
            $price1 = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1, "id" => $key), array("test_name", "asc"));
            $price += $price1[0]['price'];
        }
        echo $price;
    }

    public function book_from_prescription() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $id = $this->uri->segment(3);
        $test = $this->input->post('testids');
        $uid = $this->input->post('userid');
        $payable = $this->input->post('payable');
        $discount = $this->input->post('discount');
        $order_id = $this->get_job_id();

        $date = date('Y-m-d H:i:s');
        $test = explode(',', $test);
        $test_package_name = array();
        foreach ($test as $key) {

            $price1 = $this->user_test_master_model->master_fun_get_tbl_val("test_master", array("status" => 1, "id" => $key), array("test_name", "asc"));
            $price += $price1[0]['price'];
            $test_package_name[] = $price1[0]['test_name'];
        }
        $data = array(
            "order_id" => $order_id,
            "cust_fk" => $uid,
            "date" => $date,
            "price" => $price,
            "status" => '1',
            "payment_type" => "Cash On Delivery",
            "discount" => $discount,
            "payable_amount" => $payable,
        );
        $insert = $this->user_test_master_model->master_fun_insert("job_master", $data);
        $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => $data["login_data"]["id"], "updated_by" => "", "deleted_by" => "", "message_fk" => "6", "date_time" => date("Y-m-d H:i:s")));
        foreach ($test as $key) {

            $this->user_test_master_model->master_fun_insert("job_test_list_master", array("job_fk" => $insert, "test_fk" => $key));
        }

        $destail = $this->user_test_master_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));

        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $this->load->helper("Email");
        $email_cnt = new Email;

        $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Booking successfully. </p>
                     <p style="color:#7e7e7e;font-size:13px;"> You Booked : . ' . implode($test_package_name, ', ') . '  </p>  
<p style="color:#7e7e7e;font-size:13px;"> Your Booked Amount is Rs. ' . $payable . '  </p>
        <p style="color:#7e7e7e;font-size:13px;"> Payment Type : Cash on Blood Collection</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
        $message = $email_cnt->get_design($message);
        $this->email->to($destail[0]['email']);
        $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
        $this->email->subject('Test Book Successfully');
        $this->email->message($message);
        $this->email->send();

        $this->session->set_flashdata("success", array("Booked successfully"));
        redirect("job-master/prescription-details/" . $id, "refresh");
    }

    function all_pushnotification() {

        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $message = $this->input->post('desc');

        if ($this->session->userdata('unsuccess') != null) {
            $data['unsuccess'] = $this->session->userdata("unsuccess");
            $this->session->unset_userdata('unsuccess');
        }
        $data['success'] = $this->session->flashdata("success");

        $this->form_validation->set_rules('desc', 'Message', 'trim|required');

        if ($this->form_validation->run() == FALSE) {

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('pushnotification_view', $data);
            $this->load->view('footer');
        } else {

            $data1 = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("id", "asc"));
//	$ctn = 1 ;
            foreach ($data1 as $key) {
                $device_id = $key['device_id'];
                $mobile = $key['mobile'];
                $name = $key['full_name'];
                $email = $key['email'];
                $device_type = $key['device_type'];
                //$message = "this is developer test";

                if ($device_type == 'android') {
                    $notification_data = array("title" => "AirmedLabs", "message" => $message, "type" => "home");
                    //print_r($notification_data); die();
                    $pushServer = new PushServer();
                    $pushServer->pushToGoogle($device_id, $notification_data);
                    //print_r($result);
                }
                if ($device_type == 'ios') {
                    $url = 'http://website-demo.in/patholabpush/push.php?device_id=' . $device_id . '&msg=' . $message . '&type=home&testid=&testname=&testprice=&testdate=';
                    $url = str_replace(" ", "%20", $url);
                    $data = $this->get_content($url);

                    $data2 = json_decode($data);
                }
            }
            $this->session->set_flashdata("success", array("Notification sent successfully"));
            redirect("job_master/all_pushnotification/", "refresh");
        }
    }

    function pending_count() {
        $data["login_data"] = logindata();
        $cntr_arry = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data["login_data"]['branch_fk'] as $key) {
                $cntr_arry[] = $key["branch_fk"];
            }
        }
        //$data = $this->job_model->pending_job_count($cntr_arry);
        $data1 = $this->job_model->instant_contact_count();
        $data2 = $this->job_model->all_inquiry_count();
        $package_count = $this->job_model->master_num_rows('instant_contact', array("status" => '1'));
        $allticket = $this->job_model->master_num_rows('ticket_master', array("views" => '0', "status" => '1'));
        //$jobs = $this->job_model->master_num_rows('job_master', array("views" => '0', "status !=" => '0'));
        $contact_us = $this->job_model->master_num_rows('contact_us', array("views" => '0', "status" => '1'));
        $prescription_upload = $this->job_model->master_num_rows('prescription_upload', array("is_read" => '0', "status !=" => '0'));
        $job_total = $data->total;
        $all_inquiry_total = $data2->total;
        $package_total = 0;
        $all_total = $package_total + $all_inquiry_total + $contact_us;
        $myarray = array("job_count" => $jobs, "inquiry_total" => $all_inquiry_total + $package_count, "all_inquiry" => $all_inquiry_total, "package_inquiry" => $package_count, "all_total" => $all_total, "tickepanding" => $allticket, "contact_us_count" => $contact_us, "prescription_upload" => $prescription_upload);
        echo $json = json_encode($myarray);
    }

    function get_content($URL) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $URL);
        $data = curl_exec($ch);
        curl_close($ch);
    }

    function sms_test() {
        $mobile = "09879572294";
        //echo    $mobile = (string)$mobile;
        /* Nishit send sms code start */
        $this->load->helper("sms");
        $notification = new Sms();
        $mb_length = strlen($mobile);
        if ($mb_length == 10) {
            $notification::send($mobile, $message);
        }
        if ($mb_length == 11 || $mb_length == 12 || $mb_length == 13) {
            $check_phone = substr($mobile, 0, 2);
            $check_phone1 = substr($mobile, 0, 1);
            $check_phone2 = substr($mobile, 0, 3);
            if ($check_phone2 == '+91') {
                $get_phone = substr($mobile, 3);
                $notification::send($get_phone, $message);
            }
            if ($check_phone == '91') {
                $get_phone = substr($mobile, 2);
                $notification::send($get_phone, $message);
            }
            if ($check_phone1 == '0') {
                $get_phone = substr($mobile, 1);
                $notification::send($get_phone, $message);
            }
        }
        /* Nishit send sms code end */

        $this->db->close();
        die();
        $this->load->helper("sms");
        $notification = new Sms();
        $notification::send($phone, "This is nishit test.");
    }

    function test() {
        $data = array();
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('test', $data);
        $this->load->view('footer');
    }

    function prescription_submit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->helper("Email");
        $email_cnt = new Email;

        $this->load->model('job_model');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $data['cid'] = $this->uri->segment(3);
        $data['success'] = $this->session->flashdata("success");
        $data['error'] = $this->session->flashdata("error");

        $this->load->model('job_model');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('prescription_fk', 'Job Id', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $test_city = $this->input->post("test_city");
            $customer = $this->input->post("customer");
            if ($customer == 0) {
                $name = $this->input->post("name");
                $phone = $this->input->post("phone");
                $email = $this->input->post("email");
                $password = $this->input->post("password");
                $gender = $this->input->post("gender");
                $address = $this->input->post("address");
                $total_amount = $this->input->post("total_amount");
                $payable = $this->input->post("payable");
                $note = $this->input->post("note");
                $discount = $this->input->post('discount');
                $c_data = array("full_name" => $name, "gender" => $gender, "email" => $email, "password" => $password, "mobile" => $phone, "address" => $address, "test_city" => $test_city);
                $uid = $this->job_model->master_fun_insert("customer_master", $c_data);
                $config['mailtype'] = 'html';
                $this->email->initialize($config);

                $message = '<div style="padding:0 4%;">
                    <h4><b>Create Account</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your account successfully created. </p>
                     <p style="color:#7e7e7e;font-size:13px;"> Username/Email : . ' . $email . '  </p>  
<p style="color:#7e7e7e;font-size:13px;"> Password : ' . $password . '  </p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message = $email_cnt->get_design($message);
                $this->email->to($email);
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Account Created Successfully');
                $this->email->message($message);
                $this->email->send();
                $this->job_model->master_fun_update("prescription_upload", array("id", $data['cid']), array("cust_fk" => $uid));
            }
            if ($customer == 1) {
                $uid = $this->input->post("userid");
            }
            $upd = $this->job_model->master_fun_update("prescription_upload", array("id", $this->uri->segment(3)), array("status" => "2"));
            $submit_type = $this->input->post("submit_type");
            if ($submit_type == '1') {
                $id = $this->uri->segment(3);
                $test = $this->input->post('test');
                $order_id = $this->get_job_id($test_city);
                $date = date('Y-m-d H:i:s');
                //$test = explode(',', $test);	
                $test_package_name = array();
                $price = 0;
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $result = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $tn[1] . "'");
                        $price += $result[0]["price"];
                        $test_package_name[] = $result[0]["test_name"];
                    }
                    if ($tn[0] == 'p') {
                        //print_r(array('package_fk' => $tn[1], "city_fk" => $test_city)); die();
                        $query = $this->db->get_where('package_master_city_price', array('package_fk' => $tn[1], "city_fk" => $test_city));
                        $result = $query->result();
                        $query1 = $this->db->get_where('package_master', array('id' => $tn[1]));
                        $result1 = $query1->result();
                        $price += $result[0]->d_price;
                        $test_package_name[] = $result1[0]->title;
                    }
                }
                $payable = $this->input->post('payable');
                $data = array(
                    "order_id" => $order_id,
                    "cust_fk" => $uid,
                    "date" => $date,
                    "price" => $this->input->post("total_amount"),
                    "status" => '6',
                    "payment_type" => "Cash On Delivery",
                    "discount" => $this->input->post('discount'),
                    "payable_amount" => $payable,
                    "test_city" => $test_city,
                    "note" => $this->input->post('note'),
                    "added_by" => $data["login_data"]["id"]
                );
                //print_r($data); die();
                $insert = $this->job_model->master_fun_insert("job_master", $data);
                $this->job_model->master_fun_update("prescription_upload", array("id", $this->uri->segment(3)), array("job_fk" => $insert));
                $testid = array();
                $packageid = array();
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        $testid[] = $tn[1];
                        $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1]));
                    }
                    if ($tn[0] == 'p') {
                        $packageid[] = $tn[1];
                        $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                    }
                }

                /* Nishit send sms start */
                $pid = implode($packageid, ',');
                $tid = implode($testid, ',');
                $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "book_login"), array("id", "asc"));
                $user = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $uid), array("id", "asc"));
                $sms_message = preg_replace("/{{NAME}}/", ucfirst($user[0]['full_name']), $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{MOBILE}}/", ucfirst($user[0]['mobile']), $sms_message);
                if ($pid != '' && $tid != '') {
                    $sms_message = preg_replace("/{{TESTPACK}}/", 'Test/Package', $sms_message);
                } else if ($pid != '') {
                    $sms_message = preg_replace("/{{TESTPACK}}/", 'Package', $sms_message);
                } else {
                    $sms_message = preg_replace("/{{TESTPACK}}/", 'Test', $sms_message);
                }
                $mobile = $user[0]['mobile'];
                $this->load->helper("sms");
                $notification = new Sms();
                if ($mobile != NULL) {
                    $notification->send($mobile, $sms_message);
                }
                // Referral amount for first test book//
                /* Nishit send sms end */

                $destail = $this->job_model->master_fun_get_tbl_val("customer_master", array("status" => 1, "id" => $uid), array("id", "asc"));
                $config['mailtype'] = 'html';
                $this->email->initialize($config);

                $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $destail[0]['full_name'] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Booking successfully. </p>
                     <p style="color:#7e7e7e;font-size:13px;"> You Booked : . ' . implode($test_package_name, ', ') . '  </p>  
<p style="color:#7e7e7e;font-size:13px;"> Your Booked Total Amount is Rs. ' . $this->input->post("total_amount") . '  </p>
    <p style="color:#7e7e7e;font-size:13px;"> Discount is ' . $this->input->post('discount') . '%  </p>
        <p style="color:#7e7e7e;font-size:13px;"> Your Booked Payable Amount is Rs. ' . $payable . '  </p>
        <p style="color:#7e7e7e;font-size:13px;"> Payment Type : Cash on Blood Collection</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message = $email_cnt->get_design($message);
                $this->email->to($destail[0]['email']);
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Test Book Successfully');
                $this->email->message($message);
                $this->email->send();

                $this->session->set_flashdata("success", array("Test Booked Successfully"));
                redirect("job-master/prescription-details/" . $cid, "refresh");
            }
            if ($submit_type == '0') {
                $data["login_data"] = logindata();
                $this->load->model("user_model");
                $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
                $cid = $this->uri->segment('3');
                $test = $this->input->post('test');
                if (count(array_count_values($test)) != count($test)) {
                    $this->session->set_flashdata("test_error", array("Same test are not allowed."));
                    redirect("job-master/prescription-details/$cid");
                }
                $desc = $this->input->post('desc');
                $discount = $this->input->post('discount');
                $this->job_model->master_fun_update("prescription_upload", array("id", $this->uri->segment(3)), array("discount" => $discount, "city" => $this->input->post('test_city')));
                $update = $this->job_model->master_fun_update_multi("suggested_test", array('p_id' => $cid), array("status" => 0));
                $price = 0;
                $test_name_mail = array();
                $test_price_mail = array();
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {
                        //$this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1]));
                        $result = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $tn[1] . "'");
                        $price += $result[0]["price"];
                        $test_price_mail[] = $result[0]["price"];
                        $test_name_mail[] = $result[0]["test_name"];
                        $data = array(
                            "p_id" => $cid,
                            "test_id" => $tn[1]
                                //"description" => $desc[$i]
                        );
                        $chk = $this->job_model->master_fun_get_tbl_val("suggested_test", array('status' => 1, 'p_id' => $cid, 'test_id' => $tn[1]), array("id", "asc"));
                        $test_check = $this->job_model->master_fun_get_tbl_val("suggested_test", array('status' => 1, 'p_id' => $cid), array("id", "asc"));
                        $insert = $this->job_model->master_fun_insert("suggested_test", $data);
                        $upd = $this->job_model->master_fun_update("prescription_upload", array("id", $cid), array("status" => "2"));
                    }
                    if ($tn[0] == 'p') {
                        $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                        $query = $this->db->get_where('package_master_city_price', array('package_fk' => $tn[1], "city_fk" => $test_city));
                        $result = $query->result();
                        $query1 = $this->db->get_where('package_master', array('id' => $tn[1]));
                        $result1 = $query1->result();
                        $price += $result[0]->d_price;
                        $test_price_mail[] = $result[0]->d_price;
                        $test_name_mail[] = $result1[0]->title;
                    }
                }


                $data = $this->job_model->master_fun_get_tbl_val("prescription_upload", array("id" => $cid), array("id", "asc"));
                $cust_fk = $data[0]['cust_fk'];
                $img = $data[0]['image'];
                $desc = $data[0]['description'];
                $orderid = $data[0]['order_id'];
                $created_date = $data[0]['created_date'];
                $data1 = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));

                $device_id = $data1[0]['device_id'];
                $mobile = $data1[0]['mobile'];
                $name = $data1[0]['full_name'];
                $email = $data1[0]['email'];
                $device_type = $data1[0]['device_type'];
                $message = "Your Suggested Test has been Generated";

                if ($device_type == 'android') {
                    $notification_data = array("title" => "AirmedLabs", "message" => $message, "type" => "suggested_test", "id" => $cid, "img" => $img, "desc" => $desc, "created_date" => $created_date, "order_id" => $orderid);
                    //print_r($notification_data); die();
                    $pushServer = new PushServer();
                    $pushServer->pushToGoogle($device_id, $notification_data);
                    //print_r($result);
                }
                if ($device_type == 'ios') {
                    $url = 'http://website-demo.in/patholabpush/push.php?device_id=' . $device_id . '&msg=' . $message . '&type=suggested_test&testid=&testname=&testprice=&testdate=&id=' . $cid . '&desc=' . $desc . '&date=' . $created_date . '&orderid=' . $orderid . '&img=' . $img;
                    $url = str_replace(" ", "%20", $url);
                    $data = $this->get_content($url);

                    $data2 = json_decode($data);
                }
                /* Nishit send sms code start */
                $test_with_price = array();
                $tcnt = 0;
                foreach ($test_name_mail as $tkey) {
                    $test_with_price[] = $tkey . "-Rs." . $test_price_mail[$tcnt] . ",\n";
                    $tcnt++;
                }
                $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "Suggested_Test_Generated"), array("id", "asc"));
                $sms_message = preg_replace("/{{NAME}}/", ucfirst($data1[0]["full_name"]), $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{OID}}/", $orderid, $sms_message);
                $sms_message = preg_replace("/{{PRICE}}/", '', $sms_message);
                $sms_message = preg_replace("/{{TEST}}/", implode($test_with_price, ''), $sms_message);
                $this->load->helper("sms");
                $notification = new Sms();
                $notification->send($mobile, $sms_message);
                /* Nishit send sms code end */
                $config['mailtype'] = 'html';
                $pathToUploadedFile = base_url() . "upload/" . $img;
                $this->email->initialize($config);
                $message1 = '<div style="padding:0 4%;">
                    <h4><b>Dear </b> ' . $name . '</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Suggested Test has been Generated. </p>
						 <p style="color:#7e7e7e;font-size:13px;">Your Suggested Test are ' . implode($test_name_mail, ', ') . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Order ID : ' . $orderid . '</p>    
		<p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message1 = $email_cnt->get_design($message1);
                $this->email->to($email);
                $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
                $this->email->subject("Suggested Test has been Generated");
                $this->email->message($message1);
                $this->email->send();
                $this->session->set_flashdata("success", array("Test Suggested Successfully"));
                redirect("job-master/prescription-details/" . $cid, "refresh");
            }
        }



        $data['query'] = $this->job_model->prescription_details($data['cid']);
        $data['test'] = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["city"] . "'");
        $data['test_check'] = $this->job_model->get_suggested_test($data['cid']);
        $data['state'] = $this->job_model->master_fun_get_tbl_val("state", array('status' => 1), array("state_name", "asc"));
        $data['city'] = $this->job_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("name", "asc"));
        $data['user_info'] = array();
        if ($data['query'][0]["cust_fk"] != '') {
            $data['user_info'] = $this->job_model->master_fun_get_tbl_val("customer_master", array('id' => $data['query'][0]["cust_fk"]), array("id", "asc"));
        }
        $data['test_cities'] = $this->job_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
        if ($data['query'][0]["city"] == null) {
            $data['query'][0]["city"] = 1;
        }
        $data['test'] = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["city"] . "'");
        $data["package"] = $this->job_model->get_val("SELECT 
    `package_master`.*,
    `package_master_city_price`.`a_price` AS `a_price1`,
    `package_master_city_price`.`d_price` AS `d_price1`
  FROM
    `package_master` 
    INNER JOIN `package_master_city_price` 
      ON `package_master`.`id` = `package_master_city_price`.`package_fk` 
  WHERE `package_master`.`status` = '1' 
    AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $data['query'][0]["city"] . "' ");
        $this->job_model->master_fun_update("prescription_upload", array("id", $this->uri->segment(3)), array("is_read" => "1"));
        $data['unread'] = $this->job_model->master_fun_get_tbl_val("prescription_upload", array('status' => 1, "is_read" => "0"), array("id", "asc"));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('prescription_details_new', $data);
        $this->load->view('footer');
    }

    function get_test_list1() {
        $ids = $this->input->post('ids');
        $cnt = 0;
        /* foreach ($ids as $key) {
          $nw = explode("-", $key);
          $ids[$cnt] = $nw[1];
          $cnt++;
          }
          $id = implode(",", $ids); */
        $testid = array();
        $packageid = array();
        foreach ($ids as $key) {
            $tn = explode("-", $key);
            if ($tn[0] == 't') {
                $testid[$cnt] = $tn[1];
            }
            if ($tn[0] == 'p') {
                $packageid[$cnt] = $tn[1];
            }
            $cnt++;
        }
        if (!empty(testid)) {
            $id = implode(",", $testid);
        }
        if (!empty($packageid)) {
            $pids = implode(",", $packageid);
        }
        $ctid = $this->input->post('ctid');

        if ($ids != NULL) {
            if ($id != NULL) {
                $test = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND test_master.`id` NOT IN ($id) AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $ctid . "'");
            } else {
                $test = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $ctid . "'");
            }
            if ($pids != NULL) {
                $package = $this->job_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND package_master.`id` NOT IN ($pids) AND `package_master_city_price`.`city_fk` = '" . $ctid . "' ");
            } else {
                $package = $this->job_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $ctid . "' ");
            }
        } else {
            $test = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $ctid . "'");
            $package = $this->job_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $ctid . "' ");
        }
        echo '<script type="text/javascript" src="' . base_url() . 'user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">

                                jQuery(".chosen-select").chosen({
                                    search_contains: true
                                });
								$("#exampleModal").modal("show");
								$("#show_test_btn").attr("disabled",false);
                            </script>

<link href="' . base_url() . 'user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />';
        echo '<select class="chosen-select" data-live-search="true" id="test" data-placeholder="Select Test">
			<option value="">--Select Test--</option>';
        foreach ($test as $ts) {
            echo ' <option value="t-' . $ts['id'] . '"> ' . ucfirst($ts['test_name']) . ' (Rs.' . $ts['price'] . ')</option>';
        }
        foreach ($package as $pk) {
            echo ' <option value="p-' . $pk['id'] . '"> ' . ucfirst($pk['title']) . ' (Rs.' . $pk['d_price'] . ')</option>';
        }
        echo '</select>';
    }

    function assign_phlebo_job() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]["id"];
        $phlebo_id = $this->input->post('phlebo_id');
        $job_id = $this->input->post('job_id');
        $dated = $this->input->post('dated');
        $timed = $this->input->post('timed');
        $address = $this->input->post('address');
        $notify = $this->input->post('notify');
        $note = $this->input->post('note');
        $sharp_time = $this->input->post('sharp_time');

        $rejectionid = $this->input->post('rejectionid');
        $type = $this->input->post('type');



        if (!empty($sharp_time)) {
            $timed = null;
        }
        /* Nishit code start */
//$data = array("job_fk" => $job_id, "phlebo_fk" => $phlebo_id, "date" => $dated, "time" => $timed, "address" => $address, "notify_cust" => $notify);
        $job_cnt = $this->job_model->master_num_rows("phlebo_assign_job", array("status" => "1", "job_fk" => $job_id));
        //if ($job_cnt == 0) {
        $data = array("job_fk" => $job_id, "phlebo_fk" => $phlebo_id, "date" => $dated, "note" => $note, "time" => $sharp_time, "time_fk" => $timed, "address" => $address, "notify_cust" => $notify, "created_date" => date("Y-m-d H:i:s"), "created_by" => $data["login_data"]["id"]);

        if ($type == '2') {
            $data["type"] = '2';
            $data["rejectionid"] = $rejectionid;
        }


        $insert = $this->job_model->master_fun_insert("phlebo_assign_job", $data);
        $this->job_model->master_fun_insert("job_log", array("job_fk" => $job_id, "created_by" => "", "updated_by" => $login_id, "deleted_by" => "", "message_fk" => "8", "date_time" => date("Y-m-d H:i:s")));
        //  } else {
        //      $data = array("job_fk" => $job_id, "phlebo_fk" => $phlebo_id, "date" => $dated, "time" => $sharp_time, "time_fk" => $timed, "address" => $address, "notify_cust" => $notify, "updated_by" => $data["login_data"]["id"],"is_accept"=>"0");
        //      $insert = $this->job_model->master_fun_update("phlebo_assign_job", array("job_fk", $job_id), $data);
        //     $this->job_model->master_fun_insert("job_log", array("job_fk" => $job_id, "created_by" => "", "updated_by" => $login_id, "deleted_by" => "", "message_fk" => "9", "date_time" => date("Y-m-d H:i:s")));
        //}
        /* Nishit code end */
//$update = $this->job_model->master_fun_update("phlebo_master", array("id", $phlebo_id), $data);
        $phlebo_details = $this->job_model->master_fun_get_tbl_val("phlebo_master", array('id' => $phlebo_id), array("id", "asc"));
        $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $phlebo_details[0]["mobile"], "message" => "You have been assigned a new collection. Please accept it.", "is_send" => "0", "created_date" => date("Y-m-d H:i:s")));
        $phlebo_job_details = $this->job_model->master_fun_get_tbl_val("phlebo_assign_job", array('job_fk' => $job_id), array("id", "desc"));
        $job_details = $this->job_model->master_fun_get_tbl_val("job_master", array('id' => $job_id), array("id", "asc"));
        $notify_cust = $job_details[0]["notify_cust"];
        $customer_details = $this->job_model->master_fun_get_tbl_val("customer_master", array('id' => $job_details[0]['cust_fk']), array("id", "asc"));
        if ($insert) {
            $family_member_name = $this->job_model->get_family_member_name($job_id);
            if (!empty($family_member_name)) {
                $c_name = $family_member_name[0]["name"];
                $cmobile = $family_member_name[0]["phone"];
                if (empty($cmobile)) {
                    $cmobile = $customer_details[0]["mobile"];
                }
            } else {
                $c_name = $customer_details[0]["full_name"];
                $cmobile = $customer_details[0]["mobile"];
            }
            $job_details = $this->get_job_details($job_id);
            $b_details = array();
            foreach ($job_details[0]["book_test"] as $bkey) {
                $b_details[] = $bkey["test_name"] . " Rs." . $bkey["price"];
            }
            foreach ($job_details[0]["book_package"] as $bkey) {
                $b_details[] = $bkey["title"] . " Rs." . $bkey["d_price"];
            }
            /* Pinkesh send sms code start */
            $p_time = $this->job_model->master_fun_get_tbl_val("phlebo_time_slot", array('id' => $timed), array("id", "asc"));
            $s_time = date('h:i a', strtotime($p_time[0]["start_time"]));
            $e_time = date('h:i a', strtotime($p_time[0]["end_time"]));
            $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg"), array("id", "asc"));
            $sms_message = preg_replace("/{{NAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message[0]["message"]);
            $sms_message = preg_replace("/{{MOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
            $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
            $sms_message = preg_replace("/{{CNAME}}/", $c_name, $sms_message);
            $sms_message = preg_replace("/{{CMOBILE}}/", $cmobile, $sms_message);
            $sms_message = preg_replace("/{{CADDRESS}}/", $phlebo_job_details[0]["address"], $sms_message);
            $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
            $sms_message = preg_replace("/{{BOOKINFO}}/", implode(",", $b_details) . " Payable amount : Rs." . $job_details[0]["payable_amount"], $sms_message);
            if (!empty($sharp_time)) {
                $sms_message = preg_replace("/{{TIME}}/", $sharp_time, $sms_message);
            } else {
                $sms_message = preg_replace("/{{TIME}}/", $s_time . " To " . $e_time, $sms_message);
            }
            $job_details = $this->get_job_details($job_id);
            $b_details = array();
            foreach ($job_details[0]["book_test"] as $bkey) {
                $b_details[] = $bkey["test_name"] . " Rs." . $bkey["price"];
            }
            foreach ($job_details[0]["book_package"] as $bkey) {
                $b_details[] = $bkey["title"] . " Rs." . $bkey["d_price"];
            }
            $sms_message = preg_replace("/{{BOOKINFO}}/", implode(",", $b_details), $sms_message);
//$sms_message="done";
            if ($notify_cust == 1) {
                $mobile = $phlebo_details[0]['mobile'];
                $this->load->helper("sms");
                $notification = new Sms();
                //$notification::send($mobile, $sms_message);
                /* Pinkesh send sms code end */

//if ($notify == 1) {
                /* Pinkesh send sms code start */
                $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg_cust"), array("id", "asc"));
                $sms_message = preg_replace("/{{NAME}}/", ucfirst($c_name), $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{MOBILE}}/", $customer_details[0]["mobile"], $sms_message);
                $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
                $sms_message = preg_replace("/{{PNAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message);
                $sms_message = preg_replace("/{{PMOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
                $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
                $sms_message = preg_replace("/{{BOOKINFO}}/", implode(",", $b_details), $sms_message);
                if (!empty($sharp_time)) {
                    $sms_message = preg_replace("/{{TIME}}/", $sharp_time, $sms_message);
                } else {
                    $sms_message = preg_replace("/{{TIME}}/", $s_time . " To " . $e_time, $sms_message);
                }
                $mobile = $customer_details[0]['mobile'];
//$sms_message="done"; 
                //$notification::send($cmobile, $sms_message);
                if (!empty($family_member_name)) {
                    $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $customer_details[0]["mobile"], "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                }
            }
            /* Pinkesh send sms code end */
//}
            echo "1";
        } else {
            echo "0";
        }
    }

    function get_job_details($job_id) {
        $job_details = $this->job_model->master_fun_get_tbl_val("job_master", array("status !=" => "0", "id" => $job_id), array("id", "asc"));
        if (!empty($job_details)) {
            $book_test = $this->job_model->master_fun_get_tbl_val("job_test_list_master", array("job_fk" => $job_id), array("id", "desc"));
            $book_package = $this->job_model->master_fun_get_tbl_val("book_package_master", array("job_fk" => $job_id, "status" => "1"), array("id", "desc"));
            $test_name = array();
            foreach ($book_test as $key) {
                //echo "SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`booked_job_test`.`price`   FROM `booked_job_test` LEFT JOIN    `test_master` ON `test_master`.`id`=SUBSTRING_INDEX(`booked_job_test`.`test_fk`, '-', -1)  WHERE `test_master`.`status` = '1'   AND `booked_job_test`.`status` = '1'  AND `test_master`.`id` ='" . $key["test_fk"] . "'  AND booked_job_test.`job_fk`='" . $job_id . "'";die();
                $price1 = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`booked_job_test`.`price`,`booked_job_test`.`panel_fk` FROM `booked_job_test` LEFT JOIN    `test_master` ON `test_master`.`id`=SUBSTRING_INDEX(`booked_job_test`.`test_fk`, '-', -1)  WHERE `test_master`.`status` = '1'   AND `booked_job_test`.`status` = '1'  AND `test_master`.`id` ='" . $key["test_fk"] . "'  AND booked_job_test.`job_fk`='" . $job_id . "'");
                if (!empty($price1[0])) {
                    $test_name[] = $price1[0];
                }
            }
            $job_details[0]["book_test"] = $test_name;
            $package_name = array();
            foreach ($book_package as $key) {

                $price1 = $this->job_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $job_details[0]["test_city"] . "' AND `package_master`.`id`='" . $key["package_fk"] . "'");
                $package_name[] = $price1[0];
            }
            $job_details[0]["book_package"] = $package_name;
        }
        return $job_details;
    }

    function get_job_log($jid) {
        $job_log = $this->job_model->master_fun_get_tbl_val("job_log", array('status' => 1, "job_fk" => $jid), array("id", "asc"));
        if (!empty($job_log)) {
            echo "<hr>";
            foreach ($job_log as $key) {
                $message = $this->job_model->master_fun_get_tbl_val("job_log_message", array('status' => 1, "id" => $key["message_fk"]), array("id", "asc"));
                $AID = 0;
                if (!empty($key["created_by"])) {
                    $AID = $key["created_by"];
                }
                if (!empty($key["updated_by"])) {
                    $AID = $key["updated_by"];
                    $uid = explode("-", $AID);
                    if (count($uid) > 1) {
                        if (strtoupper($uid[0]) == "P") {
                            $admin_details = $this->job_model->master_fun_get_tbl_val("phlebo_master", array("id" => $uid[1]), array("id", "asc"));
                            $admin_name = $admin_details[0]["name"] . "<small> (Phlebotomy)</small>";
                        } else {
                            $admin_details = $this->job_model->master_fun_get_tbl_val("admin_master", array('status' => 1, "id" => $AID), array("id", "asc"));
                            $admin_name = $admin_details[0]["name"];
                        }
                    } else {
                        $admin_details = $this->job_model->master_fun_get_tbl_val("admin_master", array('status' => 1, "id" => $AID), array("id", "asc"));
                        $admin_name = $admin_details[0]["name"];
                    }
                }
                if (!empty($key["deleted_by"])) {
                    $AID = $key["deleted_by"];
                }
                if (empty($AID)) {
                    $admin_name = "Pathologist";
                }
                $message = $message[0]["message"];
                $originalDate = $key["date_time"];
                $newDate = date("d-M-Y g:i A", strtotime($originalDate));
                $message = preg_replace("/{{ANAME}}/", "<b>" . ucfirst($admin_name) . "</b>", $message);
                $message = preg_replace("/{{DATE}}/", $newDate, $message);
                if ($key["message_fk"] == 3) {

                    /* NISHIT Status check start */
                    $jobs_fk = explode("-", $key["job_status"]);
                    if ($jobs_fk[0] == 1) {
                        $j_from = "Waiting For Approval";
                    }
                    if ($jobs_fk[0] == 6) {
                        $j_from = "Approved";
                    }
                    if ($jobs_fk[0] == 7) {
                        $j_from = "Sample Collected";
                    }
                    if ($jobs_fk[0] == 8) {
                        $j_from = "Processing";
                    }
                    if ($jobs_fk[0] == 2) {
                        $j_from = "Completed";
                    }
                    if ($jobs_fk[0] == 0) {
                        $j_from = "Spam";
                    }
                    if ($jobs_fk[1] == 1) {
                        $t_from = "Waiting For Approval";
                    }
                    if ($jobs_fk[1] == 6) {
                        $t_from = "Approved";
                    }
                    if ($jobs_fk[1] == 7) {
                        $t_from = "Sample Collected";
                    }
                    if ($jobs_fk[1] == 8) {
                        $t_from = "Processing";
                    }
                    if ($jobs_fk[1] == 2) {
                        $t_from = "Completed";
                    }
                    if ($jobs_fk[1] == 0) {
                        $t_from = "Spam";
                    }

                    /* NISHIT Status check end */
                    $message = preg_replace("/{{FROM}}/", $j_from, $message);
                    $message = preg_replace("/{{TO}}/", $t_from, $message);
                }
                echo '<p><small>' . $message . '</small></p>';
            }
        }
    }

    function get_job_log2($jid) {
        $job_log = $this->job_model->master_fun_get_tbl_val("job_log", array('status' => 1, "job_fk" => $jid), array("id", "asc"));
        if (!empty($job_log)) {
            echo "<hr>";
            foreach ($job_log as $key) {
                $message = $this->job_model->master_fun_get_tbl_val("job_log_message", array('status' => 1, "id" => $key["message_fk"]), array("id", "asc"));

                if ($key["created_by"] != 0) {
                    $AID = $key["created_by"];
                }
                if ($key["updated_by"] != 0) {
                    $AID = $key["updated_by"];
                }
                if ($key["deleted_by"] != 0) {
                    $AID = $key["deleted_by"];
                }
                $admin_details = $this->job_model->master_fun_get_tbl_val("admin_master", array('status' => 1, "id" => $AID), array("id", "asc"));
                $message = $message[0]["message"];
                $originalDate = $key["date_time"];
                $newDate = date("d-M-Y g:i A", strtotime($originalDate));
                $message = preg_replace("/{{ANAME}}/", "<b>" . ucfirst($admin_details[0]["name"]) . "</b>", $message);
                $message = preg_replace("/{{DATE}}/", $newDate, $message);
                if ($key["message_fk"] == 3) {

                    /* NISHIT Status check start */
                    $jobs_fk = explode("-", $key["job_status"]);
                    if ($jobs_fk[0] == 1) {
                        $j_from = "Waiting For Approval";
                    }
                    if ($jobs_fk[0] == 6) {
                        $j_from = "Approved";
                    }
                    if ($jobs_fk[0] == 7) {
                        $j_from = "Sample Collected";
                    }
                    if ($jobs_fk[0] == 8) {
                        $j_from = "Processing";
                    }
                    if ($jobs_fk[0] == 2) {
                        $j_from = "Completed";
                    }
                    if ($jobs_fk[0] == 0) {
                        $j_from = "Spam";
                    }
                    if ($jobs_fk[1] == 1) {
                        $t_from = "Waiting For Approval";
                    }
                    if ($jobs_fk[1] == 6) {
                        $t_from = "Approved";
                    }
                    if ($jobs_fk[1] == 7) {
                        $t_from = "Sample Collected";
                    }
                    if ($jobs_fk[1] == 8) {
                        $t_from = "Processing";
                    }
                    if ($jobs_fk[1] == 2) {
                        $t_from = "Completed";
                    }
                    if ($jobs_fk[1] == 0) {
                        $t_from = "Spam";
                    }

                    /* NISHIT Status check end */
                    $message = preg_replace("/{{FROM}}/", $j_from, $message);
                    $message = preg_replace("/{{TO}}/", $t_from, $message);
                }
                echo '<p><small>' . $message . '</small></p>';
            }
        }
    }

	function payment_received() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $jid = $this->uri->segment(3);
        $amount = trim($this->input->post("amount"));
        $remark = $this->input->post("remark");
        $ttl_amount = $this->input->post("ttl_amount");
        $p_type = $this->input->post("p_type");

        $receivid = $this->input->post("hdn_tid");
        $rcv_time = $this->input->post("rcv_time");
        
        if(!empty($receivid))
        {
            //echo $receivid . " ";
            //echo $rcv_time;
            $this->job_model->master_fun_update("job_master_receiv_amount", array("id", $receivid), array("status" => 0));
            
        }
        

        /* if (!empty($jid) && $ttl_amount >= $amount && $amount != 0 && $amount != '') {
          $this->job_model->master_fun_insert("job_master_receiv_amount", array("payment_type" => $p_type, "remark" => $remark, "job_fk" => $jid, "added_by" => $data["login_data"]["id"], "amount" => $amount, "createddate" => date("Y-m-d H:i:s")));
          $remaining_amount = $ttl_amount - $amount;
          $this->job_model->master_fun_update("job_master", array("id", $jid), array("payable_amount" => $remaining_amount));
          $ttl_amount = $remaining_amount;
          } */
        $job_details = $this->job_model->master_fun_get_tbl_val("job_master", array("id" => $jid), array("id", "asc"));
        $sms_alert = $this->job_model->get_val("select smsalert from branch_master where id='" . $job_details[0]['branch_fk'] . "'")[0]['smsalert'];
        $payable_price = $job_details[0]["payable_amount"];
        $remaining_amount = $payable_price - $amount;
        $entrytime = date("Y-m-d H:i:s");
        if(!empty($rcv_time))
            $entrytime  = $rcv_time;
        $this->job_model->master_fun_insert("job_master_receiv_amount", array("payment_type" => $p_type, "remark" => $remark, "job_fk" => $jid, "added_by" => $data["login_data"]["id"], "amount" => $amount, "createddate" => $entrytime));
        
        $price_disc = $job_details[0]["price"] - $job_details[0]["discount"];
        $total_rcvd = $this->job_model->get_val("select sum(amount) rcvd from job_master_receiv_amount where status = '1' and job_fk='" . $jid . "'");
        $tot_rcvd = 0;        
        if(count($total_rcvd) > 0)
            $tot_rcvd = $total_rcvd[0]["rcvd"];
        $remaining_amount = $price_disc - $tot_rcvd;

        $this->job_model->master_fun_update("job_master", array("id", $jid), array("payable_amount" => $remaining_amount));
        $this->job_model->master_fun_insert("job_log", array("job_fk" => $jid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "22", "date_time" => date("Y-m-d H:i:s")));
        $this->session->set_flashdata("amount_history_success", array("Payment Successfully added."));
        $this->check_send_report($jid, $sms_alert);
        redirect("job-master/job-details/" . $jid);
    }

    function payment_received_250423() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $jid = $this->uri->segment(3);
        $amount = trim($this->input->post("amount"));
        $remark = $this->input->post("remark");
        $ttl_amount = $this->input->post("ttl_amount");
        $p_type = $this->input->post("p_type");
        /* if (!empty($jid) && $ttl_amount >= $amount && $amount != 0 && $amount != '') {
          $this->job_model->master_fun_insert("job_master_receiv_amount", array("payment_type" => $p_type, "remark" => $remark, "job_fk" => $jid, "added_by" => $data["login_data"]["id"], "amount" => $amount, "createddate" => date("Y-m-d H:i:s")));
          $remaining_amount = $ttl_amount - $amount;
          $this->job_model->master_fun_update("job_master", array("id", $jid), array("payable_amount" => $remaining_amount));
          $ttl_amount = $remaining_amount;
          } */
        $job_details = $this->job_model->master_fun_get_tbl_val("job_master", array("id" => $jid), array("id", "asc"));
        $sms_alert = $this->job_model->get_val("select smsalert from branch_master where id='" . $job_details[0]['branch_fk'] . "'")[0]['smsalert'];
        $payable_price = $job_details[0]["payable_amount"];
        $remaining_amount = $payable_price - $amount;
        $this->job_model->master_fun_insert("job_master_receiv_amount", array("payment_type" => $p_type, "remark" => $remark, "job_fk" => $jid, "added_by" => $data["login_data"]["id"], "amount" => $amount, "createddate" => date("Y-m-d H:i:s")));
        $this->job_model->master_fun_update("job_master", array("id", $jid), array("payable_amount" => $remaining_amount));
        $this->job_model->master_fun_insert("job_log", array("job_fk" => $jid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "22", "date_time" => date("Y-m-d H:i:s")));
        $this->session->set_flashdata("amount_history_success", array("Payment Successfully added."));
        $this->check_send_report($jid, $sms_alert);
        redirect("job-master/job-details/" . $jid);
    }

    function delete_assign_payment() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $jid = $this->input->post("jid");
        $id = $this->input->post("id");
        $delete_received_payment = $this->input->post("d_amount");
        $ttl_amount = $this->input->post("ttl_amount");
        $d_amount = $this->input->post("d_amount");
        if (!empty($jid) && !empty($id)) {
            $this->job_model->master_fun_update("job_master_receiv_amount", array("id", $id), array("status" => 0));
            $remaining_amount = $d_amount + $ttl_amount;
            $this->job_model->master_fun_update("job_master", array("id", $jid), array("payable_amount" => $remaining_amount));
            $job_ramount_details = $this->job_model->master_fun_get_tbl_val("job_master_receiv_amount", array('id' => $id), array("id", "asc"));
            if ($job_ramount_details[0]["payment_type"] == "CREDITORS") {
                $this->job_model->master_fun_update("creditors_balance", array("job_id", $jid), array("status" => '0'));
                $this->job_model->master_fun_update("job_creditors", array("job_fk", $jid), array("status" => '0'));
            }
        }
        $this->job_model->master_fun_insert("job_log", array("job_fk" => $jid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "23", "date_time" => date("Y-m-d H:i:s")));
        $this->session->set_userdata("amount_history_success", array("Payment Successfully deleted."));
        echo 1;
    }

    function change_family_member() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $jid = $this->uri->segment(3);
        $family_change = $this->input->post("family_mem");
        $f_name = $this->input->post("f_name");
        $family_relation = $this->input->post("family_relation");
        $f_phone = $this->input->post("f_phone");
        $f_email = $this->input->post("f_email");
        $f_dob = $this->input->post("f_dob");
        $f_dob = explode("/", $f_dob);
        $f_dob = $f_dob[2] . "-" . $f_dob[1] . "-" . $f_dob[0];
        $f_gender = $this->input->post("f_gender");

        $job_details = $this->job_model->master_fun_get_tbl_val("job_master", array('id' => $jid), array("id", "asc"));
        $booking_details = $this->job_model->master_fun_get_tbl_val("booking_info", array('id' => $job_details[0]["booking_info"]), array("id", "asc"));
        //if (!empty($family_change) || (!empty($f_name) && !empty($family_relation))) {
        if (!empty($f_name) && !empty($family_relation)) {
            $insert = $this->job_model->master_fun_insert("customer_family_master", array("user_fk" => $booking_details[0]["user_fk"], "name" => $f_name, "relation_fk" => $family_relation, "phone" => $f_phone, "email" => $f_email, "gender" => $f_gender, "dob" => $f_dob, "status" => "1", "created_date" => date("Y-m-d H:i:s")));
            $f_type = "family";
            $this->job_model->master_fun_update("booking_info", array("id", $booking_details[0]["id"]), array("type" => $f_type, "family_member_fk" => $insert));
        } else {
            $f_type = "self";
            if ($family_change != 0) {
                $f_type = "family";
            }
            $this->job_model->master_fun_update("booking_info", array("id", $booking_details[0]["id"]), array("type" => $f_type, "family_member_fk" => $family_change));
        }
        //echo "<pre>"; print_r($_POST); die();
        /*    //$this->session->set_flashdata("amount_history_success", array("Payment Successfully added."));
          } else {
          $this->session->set_flashdata("family_error", array("Please fill family info."));
          } */
        $this->job_model->master_fun_insert("job_log", array("job_fk" => $jid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "19", "date_time" => date("Y-m-d H:i:s")));
        redirect("job-master/job-details/" . $jid);
    }

    function update_family_member() {
        if (!is_loggedin()) {
            redirect('login');
        }
        //echo "<pre>"; print_r($_POST); die();
        $data["login_data"] = logindata();
        $id = $this->uri->segment(3);
        $jid = $this->uri->segment(4);
        $family_change = $this->input->post("family_mem");
        $f_name = $this->input->post("f_name");
        $family_relation = $this->input->post("family_relation");
        $f_phone = $this->input->post("f_phone");
        $f_email = $this->input->post("f_email");
        $f_dob = $this->input->post("f_dob");
        $f_dob = explode("/", $f_dob);
        $f_dob = $f_dob[2] . "-" . $f_dob[1] . "-" . $f_dob[0];
        $f_gender = $this->input->post("f_gender");
        $this->job_model->master_fun_update("customer_family_master", array("id", $id), array("name" => $f_name, "relation_fk" => $family_relation, "phone" => $f_phone, "email" => $f_email, "gender" => $f_gender, "dob" => $f_dob));
        $this->job_model->master_fun_insert("job_log", array("job_fk" => $jid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "28", "date_time" => date("Y-m-d H:i:s")));
        redirect("job-master/job-details/" . $jid);
    }

    function update_user_info() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $id = $this->uri->segment(3);
        $jid = $this->uri->segment(4);
        $f_name = $this->input->post("f_name");
        $uid = $this->input->post("uid");
        $f_phone = $this->input->post("f_phone");
        $f_email = $this->input->post("f_email");
        $f_dob = $this->input->post("f_dob");
        $f_dob = explode("/", $f_dob);
        $f_dob = $f_dob[2] . "-" . $f_dob[1] . "-" . $f_dob[0];
        $f_gender = $this->input->post("f_gender");
        $this->job_model->master_fun_update("customer_master", array("id", $uid), array("full_name" => $f_name, "mobile" => $f_phone, "email" => $f_email, "gender" => $f_gender, "dob" => $f_dob));
        $this->job_model->master_fun_insert("job_log", array("job_fk" => $jid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "21", "date_time" => date("Y-m-d H:i:s")));

        redirect("job-master/job-details/" . $jid);
    }

    function get_job_id($city = null) {
        $this->load->library("Util");
        $util = new Util();
        $new_id = $util->get_job_id($city);
        return $new_id;
    }

    function job_report() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $statusid = $this->input->get('statusid');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cntr_arry = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data["login_data"]['branch_fk'] as $key) {
                $cntr_arry[] = $key["branch_fk"];
            }
        }
        if ($start_date != "" || $end_date != "" || $statusid != "") {
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['statusid'] = $statusid;
            if ($statusid == '0') {
                $data["deleted_selected"] = 1;
            }
            $data['query'] = $this->job_model->job_report_data($start_date, $end_date, $statusid, $cntr_arry);
        } else {
            $data['query'] = array();
        }
        $data['customer'] = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("full_name", "asc"));
        /* $data['test_list'] = $this->job_model->test_with_city(); */
        /* $data['package_list'] = $this->job_model->master_fun_get_tbl_val("package_master", array('status' => 1), array("title", "asc")); */
        $data['city'] = $this->job_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
        $cnt = 0;
        foreach ($data['query'] as $key) {
            $w_prc = 0;
            /* Count booked test price */
            $booked_tests = $this->job_model->master_fun_get_tbl_val("wallet_master", array('job_fk' => $key["id"]), array("id", "asc"));
            $emergency_tests = $this->job_model->master_fun_get_tbl_val("booking_info", array('id' => $key["booking_info"]), array("id", "asc"));
            $f_data = $this->job_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
            $f_data1 = $this->job_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
            $r_data = $this->job_model->master_fun_get_tbl_val("job_master_receiv_amount", array('job_fk' => $key["id"], "status" => "1"), array("id", "asc"));
            $total_collected_amount = 0;
            foreach ($r_data as $r_value) {
                $total_collected_amount += $r_value["amount"];
            }
            $data['query'][$cnt]["collected_amount"] = $total_collected_amount;
            $relation = "Self";
            if (!empty($f_data1)) {
                $relation = ucfirst($f_data1[0]["name"] . " (" . $f_data[0]["name"] . ")");
            }
            $data['query'][$cnt]["relation"] = $relation;
            foreach ($booked_tests as $tkey) {
                if ($tkey["debit"]) {
                    $w_prc = $w_prc + $tkey["debit"];
                }
            }
            $data['query'][$cnt]["emergency"] = $emergency_tests[0]["emergency"];
            $data['query'][$cnt]["cut_from_wallet"] = $w_prc;
            $package_ids = $this->job_model->get_job_booking_package($key["id"]);
            if (trim($package_ids) != '') {
                $data['query'][$cnt]["packagename"] = $package_ids;
            }
            $cnt++;
        }
        //echo "<pre>"; print_r($data['query']); die();
        $url = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        $this->session->set_userdata("job_master_r", $url);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('job_report', $data);
        $this->load->view('footer');
    }

    function change_doctor() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $jid = $this->uri->segment(3);
        $referral_by = $this->input->post("referral_by");
        if ($jid != '' && $referral_by != '') {
            $this->job_model->master_fun_update("job_master", array("id", $jid), array("doctor" => $referral_by));
            $this->job_model->master_fun_insert("job_log", array("job_fk" => $jid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "25", "date_time" => date("Y-m-d H:i:s")));

            redirect("job-master/job-details/" . $jid);
        } else {
            redirect("job-master/pending-list");
        }
    }

    function change_notify() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $jid = $this->uri->segment(3);
        $notify = $this->input->post("notify");
        if ($jid != '' && $notify != '') {
            $this->job_model->master_fun_update("job_master", array("id", $jid), array("notify_cust" => $notify));
            $this->job_model->master_fun_insert("job_log", array("job_fk" => $jid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "20", "date_time" => date("Y-m-d H:i:s")));
            redirect("job-master/job-details/" . $jid);
        } else {
            redirect("job-master/pending-list");
        }
    }

    function change_cc() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $jid = $this->uri->segment(3);
        $notify = $this->input->post("notify");
        $collectioncharge_amount = 0;
        if ($notify == 1) {
            $collectioncharge_amount = $this->input->post("collectionchargeamount");
        }

        if ($jid != '' && $notify != '') {
            $j_data = $this->job_model->master_fun_get_tbl_val("job_master", array('id' => $jid), array("id", "asc"));
            $price = 0;
            $payable_price = 0;
            /* if ($notify == 1 && $j_data[0]["collection_charge"] == 0) {
              $price = $j_data[0]["price"] + 100;
              $payable_price = $j_data[0]["payable_amount"] + 100;
              $this->job_model->master_fun_update("job_master", array("id", $jid), array("collection_charge" => $notify, "price" => $price, "payable_amount" => $payable_price));
              }
              if ($notify == 0 && $j_data[0]["collection_charge"] == 1) {
              $price = $j_data[0]["price"] - 100;
              $payable_price = $j_data[0]["payable_amount"] - 100;
              $this->job_model->master_fun_update("job_master", array("id", $jid), array("collection_charge" => $notify, "price" => $price, "payable_amount" => $payable_price));
              } */
            if ($notify == 1) { //&& ($j_data[0]["collection_charge"] == 0 || $j_data[0]["collection_charge"] == 1)
                $price = $j_data[0]["price"] - $j_data[0]["collectioncharge_amount"] + $collectioncharge_amount;
                $old_discount = ($j_data[0]["price"] * $j_data[0]["discount"] / 100);
                $new_discount = ($price * $j_data[0]["discount"] / 100);
                $payable_price = $j_data[0]["payable_amount"] - $j_data[0]["collectioncharge_amount"] + $collectioncharge_amount + ($old_discount - $new_discount);
                $this->job_model->master_fun_update("job_master", array("id", $jid), array("collection_charge" => $notify, "collectioncharge_amount" => $collectioncharge_amount, "price" => $price, "payable_amount" => $payable_price));
            }
            if ($notify == 0) { //&& $j_data[0]["collection_charge"] == 1
                $price = $j_data[0]["price"] - $j_data[0]["collectioncharge_amount"] - $collectioncharge_amount;
                $old_discount = ($j_data[0]["price"] * $j_data[0]["discount"] / 100);
                $new_discount = ($price * $j_data[0]["discount"] / 100);
                $payable_price = $j_data[0]["payable_amount"] - $j_data[0]["collectioncharge_amount"] - $collectioncharge_amount + ($old_discount - $new_discount);
                $this->job_model->master_fun_update("job_master", array("id", $jid), array("collection_charge" => $notify, "collectioncharge_amount" => $collectioncharge_amount, "price" => $price, "payable_amount" => $payable_price));
            }
            //$this->job_model->master_fun_insert("job_log", array("job_fk" => $jid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "20", "date_time" => date("Y-m-d H:i:s")));
            redirect("job-master/job-details/" . $jid);
        } else {
            redirect("job-master/pending-list");
        }
    }

    function change_branch() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $jid = $this->uri->segment(3);

        $referral_by = $this->input->post("branch");

        if ($jid != '' && $referral_by != '') {
            $this->job_model->master_fun_update("job_master", array("id", $jid), array("branch_fk" => $referral_by));
            redirect("job-master/job-details/" . $jid);
        } else {
            redirect("job-master/pending-list");
        }
    }

    function change_credential() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $jid = $this->uri->segment(3);

        $referral_by = $this->input->post("branch");
        if ($jid != '' && $referral_by != '') {
            $check = $this->job_model->master_fun_get_tbl_val("job_creditors", array('job_fk' => $jid, "status" => "1"), array("id", "asc"));
            if (!empty($check)) {
                $this->job_model->master_fun_update("job_creditors", array("id", $check[0]["id"]), array("creditors_fk" => $referral_by));
            } else {
                $this->job_model->master_fun_insert("job_creditors", array('job_fk' => $jid, "creditors_fk" => $referral_by, "added_by" => $data["login_data"]["id"], "created_date" => date("Y-m-d h:i:s")));
            }

            redirect("job-master/job-details/" . $jid);
        } else {
            redirect("job-master/pending-list");
        }
    }

    function change_report_status() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $jid = $this->uri->segment(3);

        $referral_by = $this->input->post("report_status");

        if ($jid != '' && $referral_by != '') {
            $this->job_model->master_fun_update("job_master", array("id", $jid), array("report_status" => $referral_by));
            redirect("job-master/job-details/" . $jid);
        } else {
            redirect("job-master/pending-list");
        }
    }

    function pdf_invoice($id) {
        $data["login_data"] = logindata();
        $this->load->model('add_result_model');
        $data['query'] = $this->add_result_model->job_details($id);
        $data['book_list'] = array();

        /* Check birth date end */
        $tid = array();
        $data['parameter_list'] = array();
        if (trim($data['query'][0]['testid']) == null && $data['query'][0]["packageid"] != null) {
            $package_id = $data['query'][0]["packageid"];
            $pid = explode("%", $data['query'][0]['packageid']);
            foreach ($pid as $pkey) {
                $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");
                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["test_fk"];
                }
            }
        } else if (trim($data['query'][0]['testid']) != null && $data['query'][0]["packageid"] != null) {

            $tid = explode(",", $data['query'][0]['testid']);
            $package_id = $data['query'][0]["packageid"];
            $pid = explode("%", $data['query'][0]['packageid']);
            foreach ($pid as $pkey) {
                $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");

                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["test_fk"];
                }
            }
        } else {
            $tid = explode(",", $data['query'][0]['testid']);
        }
        //print_R($tid); die();
//        foreach ($tid as $t_key) {
//            $p_test = $this->add_result_model->get_val("SELECT sub_test_master.* FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `sub_test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $t_key . "'");
//            foreach ($p_test as $tp_key) {
//                $tid[] = $tp_key["sub_test"];
//            }
//        }
        $tid = array_unique($tid);
        /* Nishit check one test start */
        if (!empty($data['test_id'])) {
            $tid = array($data['test_id']);
        }
        /* END */
        //echo "<pre>";print_R($tid);die();
        //$tid = explode(",", $data['query'][0]['testid']);


        $fast = array();
        foreach ($tid as $tst_id) {
            $test_fast = $this->add_result_model->get_val("SELECT fasting_requird FROM test_master WHERE status='1' AND id='" . $tst_id . "'");
            array_push($fast, $test_fast[0]['fasting_requird']);
        }




        $emergency_tests = $this->job_model->master_fun_get_tbl_val("booking_info", array('id' => $data['query'][0]["booking_info"]), array("id", "asc"));
        $f_data = $this->job_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
        $f_data1 = $this->job_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
        $relation = "Self";
        if (!empty($f_data1)) {
            $relation = ucfirst($f_data1[0]["name"] . " (" . $f_data[0]["name"] . ")");
        }
        $data['test_for'] = $relation;
        $data['relation'] = $f_data;
        $branch = $data['query'][0]['branch_fk'];

        if ($data['query'][0]['testid'] != '') {
            $get_user_details = $this->add_result_model->master_fun_get_tbl_val("customer_master", array("id" => $data["login_data"]["id"], "status" => "1"), array("id", "desc"));

            foreach ($tid as $tst_id) {
                $para = $this->add_result_model->get_val("SELECT t.test_name as book_name,t.id as tid,bj.price AS price  FROM `booked_job_test` bj LEFT JOIN   test_master AS t ON t.id=SUBSTRING_INDEX(bj.`test_fk`, '-', -1)  WHERE t.status='1'  AND t.id='" . $tst_id . "' AND bj.job_fk='" . $id . "' AND bj.status='1' order by t.test_name ASC");

                array_push($data['book_list'], $para);
                $test_fast = $this->add_result_model->get_val("SELECT fasting_requird FROM test_master WHERE status='1' AND id='" . $tst_id . "'");
                //array_push($fast, $test_fast[0]['fasting_requird']);
            }
        }
        $pid = explode("%", $data['query'][0]['packageid']);

        if ($data['query'][0]['packageid'] != '') {
            foreach ($pid as $pack_id) {

                $para = $this->add_result_model->get_val("SELECT `package_master`.id as pid,`package_master`.title as book_name,
              `test_branch_price`.`price` AS `price` FROM `package_master`
              INNER JOIN `test_branch_price`
              ON `package_master`.`id` = `test_branch_price`.`test_fk` and test_branch_price.type='2'
              WHERE `package_master`.`status` = '1' and `package_master`.id='$pack_id'
              AND `package_master`.`is_active`='1' AND `test_branch_price`.`branch_fk` = '$branch' ");


                array_push($data['book_list'], $para);
            }
        }
        if (in_array("1", $fast)) {
            $data['fasting'] = 'Fasting required for 12 hours.';
        } else {
            $data['fasting'] = 'Fasting not required.';
        }
        $this->load->library("Util");
        $util = new Util;
        if (empty($data['query'][0]["age"])) {
            if (!empty($data['query'][0]["dob"])) {

                $new_age = $util->get_age($data['query'][0]["dob"]);
                //$data['query'][0]["age"] = $new_age[0];
                if ($new_age[0] != 0) {
                    $data['query'][0]["age"] = $new_age[0] . " Years";
                } else if ($new_age[0] == 0 && $new_age[1] != 0) {
                    $data['query'][0]["age"] = $new_age[1] . " Months";
                } else if ($new_age[0] == 0 && $new_age[1] == 0 && $new_age[2] != 0) {
                    $data['query'][0]["age"] = $new_age[2] . " Days";
                }
            }
        }
        if (!empty($f_data)) {
            $new_age = $util->get_age($f_data[0]["dob"]);
            //$data['query'][0]["age"] = $new_age[0];
            if ($new_age[0] != 0) {
                $data['query'][0]["f_age"] = $new_age[0] . " Years";
            } else if ($new_age[0] == 0 && $new_age[1] != 0) {
                $data['query'][0]["f_age"] = $new_age[1] . " Months";
            } else if ($new_age[0] == 0 && $new_age[1] == 0 && $new_age[2] != 0) {
                $data['query'][0]["f_age"] = $new_age[2] . " Days";
            }
        }

        $data['time'] = $this->add_result_model->get_val("SELECT ts.start_time,ts.end_time FROM booking_info as b left join phlebo_time_slot as ts on b.time_slot_fk=ts.id WHERE ts.status='1' AND b.status='1' AND b.id='" . $data['query'][0]['booking_info'] . "'");
        //echo "<pre>"; print_r($data['parameter_list']); die();
        $pdfFilePath = FCPATH . "/upload/result/" . $data['query'][0]['order_id'] . "_invoice.pdf";
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '512M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        $html = $this->load->view('user/user_job_invoice_pdf', $data, true); // render the view into HTML
        //$param = '"en-GB-x","A4","","",10,10,0,10,6,3,"P"'; // Landscape
        //$lorem = utf8_encode($html); // render the view into HTML
        //$html = "<!DOCTYPE html>                         <html><body>\u0627\u0644\u0643\u0647\u0631\u0628\u0627\u0621 \u0648 \u0627\u0644\u0633\u0628\u0627\u0643\u0629</body></html>      ";
        ob_end_flush();
        $this->load->library('pdf');

        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;

        $pdf->autoLangToFont = true;


        $pdf->SetHTMLHeader('<body>
                <div class="pdf_container">
            <div class="main_set_pdng_div">
                <div class="brdr_full_div">
                    <div class="header_full_div">
                        <img class="set_logo" src="logo.png" style="margin-top:15px;"/>
                    </div>');

        $pdf->SetHTMLFooter('<div class="foot_num_div" style="margin-bottom:0;padding-bottom:0">
		<p class="foot_num_p" style="margin-bottom:2;padding-bottom:0"><img class="set_sign" src="pdf_phn_btn.png" style="width:"/></p>
		<p class="foot_lab_p" style="font-size:13px;margin-bottom:2;padding-bottom:0">LAB AT YOUR DOORSTEP</p>
	</div>
		<p class="lst_airmed_mdl" style="font-size:13px;margin-top:5px">Airmed Pathology Pvt. Ltd.</p>
		<p class="lst_31_addrs_mdl" style="font-size:12px"><span style="color:#9D0902;">Commercial Address : </span>31, Ambika Society, Next to Nabard Bank, Opp. Usmanpura, Ahmedabad, Gujarat - 380 013.</p>
		<p class="lst_31_addrs_mdl"><b><img src="email-icon.png" style="margin-bottom:-3px;width:13px"/> info@airmedlabs.com  <img src="web-icon.png" style="margin-bottom:-3px;width:13px"/> www.airmedlabs.com</b></p><p class="lst_31_addrs_mdl"><!--<img src="lastimg.png" style="margin-top:3px;"/>--> </p></div>
        </body>
</html>');

        $data["panelcount"] = $this->job_model->get_val("SELECT COUNT(*)  AS c FROM `booked_job_test` WHERE  panel_fk > 0 AND `status`='1' AND job_fk='" . $id . "'");
        if ($data['query'][0]["branch_fk"] == 10 && $data["panelcount"][0]['c'] > 0) {
            $pdf->SetHTMLHeader('<body>
                <div class="pdf_container">
            <div class="main_set_pdng_div">
                <div class="brdr_full_div">
                    <center><div class="header_full_div" style="text-align:center;">
                        <h3> Satyakiran Healthcare Pvt. Ltd. </h3>
                        <div style="font-size:10px;"><b>Subhash Chowk,
Sonepat Tel: 0130-2242938, 2242939 </b><div>
                    </div></center></div>');

            $pdf->SetHTMLFooter('
                </div>
            </div>
        </div>
        </body>
</html>');
        }
        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 5, // margin_left
                5, // margin right
                30, // margin top
                30, // margin bottom
                2, // margin header
                2); // margin footer
        //$pdf->AddPage('P', '', 1, 'i', 'on');
        //$pdf->SetDirectionality('rtl');
        /* $pdf->AddPage('P', // L - landscape, P - portrait
          '', '', '', '', 00, // margin_left
          0, // margin right
          0, // margin top
          0, // margin bottom
          0, // margin header
          0); */

        //$pdf->SetDisplayMode('fullpage');
        //$pdf=new mPDF('utf-8','A4');
        //$pdf->debug = true;
        // $pdf->h2toc = array('H2' => 0);
        // $html = '';
        // Split $lorem into words
        $pdf->WriteHTML($html);
        $pdf->debug = true;
        $pdf->allow_output_buffering = TRUE;
        //$pdf->SetFooter('www.' . $_SERVER['HTTP_HOST'] . '||' . $new_time); // Add a footer for good measure <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        // $pdf->WriteHTML($html); // write the HTML into the PDF
        if (file_exists($pdfFilePath) == true) {

            $this->load->helper('file');
            unlink($path);
            //die("OK"); 
        }
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        $name = $data['query'][0]['order_id'] . "_invoice.pdf";
        $this->add_result_model->master_fun_update('job_master', array('id', $id), array("invoice" => $name));
        $this->job_model->master_fun_insert("job_log", array("job_fk" => $id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "26", "date_time" => date("Y-m-d H:i:s")));
        redirect("/upload/result/" . $data['query'][0]['order_id'] . "_invoice.pdf?" . time());
        //return $name;
    }

    function pdf_invoice_without($id) {
        $data["login_data"] = logindata();
        $this->load->model('add_result_model');
        $data['query'] = $this->add_result_model->job_details($id);
        $data['book_list'] = array();

        /* Check birth date end */
        $tid = array();
        $data['parameter_list'] = array();
        if (trim($data['query'][0]['testid']) == null && $data['query'][0]["packageid"] != null) {
            $package_id = $data['query'][0]["packageid"];
            $pid = explode("%", $data['query'][0]['packageid']);
            foreach ($pid as $pkey) {
                $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");
                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["test_fk"];
                }
            }
        } else if (trim($data['query'][0]['testid']) != null && $data['query'][0]["packageid"] != null) {

            $tid = explode(",", $data['query'][0]['testid']);
            $package_id = $data['query'][0]["packageid"];
            $pid = explode("%", $data['query'][0]['packageid']);
            foreach ($pid as $pkey) {
                $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");

                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["test_fk"];
                }
            }
        } else {
            $tid = explode(",", $data['query'][0]['testid']);
        }
        //print_R($tid); die();
        foreach ($tid as $t_key) {
            $p_test = $this->add_result_model->get_val("SELECT sub_test_master.* FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `sub_test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $t_key . "'");
            foreach ($p_test as $tp_key) {
                $tid[] = $tp_key["sub_test"];
            }
        }
        $tid = array_unique($tid);
        /* Nishit check one test start */
        if (!empty($data['test_id'])) {
            $tid = array($data['test_id']);
        }
        /* END */
        //echo "<pre>";print_R($tid);die();
        //$tid = explode(",", $data['query'][0]['testid']);


        $fast = array();
        foreach ($tid as $tst_id) {
            $test_fast = $this->add_result_model->get_val("SELECT fasting_requird FROM test_master WHERE status='1' AND id='" . $tst_id . "'");
            array_push($fast, $test_fast[0]['fasting_requird']);
        }




        $emergency_tests = $this->job_model->master_fun_get_tbl_val("booking_info", array('id' => $data['query'][0]["booking_info"]), array("id", "asc"));
        $f_data = $this->job_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
        $f_data1 = $this->job_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
        $relation = "Self";
        if (!empty($f_data1)) {
            $relation = ucfirst($f_data1[0]["name"] . " (" . $f_data[0]["name"] . ")");
        }
        $data['test_for'] = $relation;
        $data['relation'] = $f_data;
        $branch = $data['query'][0]['branch_fk'];

        if ($data['query'][0]['testid'] != '') {
            $get_user_details = $this->add_result_model->master_fun_get_tbl_val("customer_master", array("id" => $data["login_data"]["id"], "status" => "1"), array("id", "desc"));

            foreach ($tid as $tst_id) {
                $para = $this->add_result_model->get_val("SELECT t.test_name as book_name,t.id as tid,bj.price AS price  FROM `booked_job_test` bj LEFT JOIN   test_master AS t ON t.id=SUBSTRING_INDEX(bj.`test_fk`, '-', -1)  WHERE t.status='1'  AND t.id='" . $tst_id . "' AND bj.job_fk='" . $id . "' AND bj.status='1' order by t.test_name ASC");

                array_push($data['book_list'], $para);
                $test_fast = $this->add_result_model->get_val("SELECT fasting_requird FROM test_master WHERE status='1' AND id='" . $tst_id . "'");
                //array_push($fast, $test_fast[0]['fasting_requird']);
            }
        }
        $pid = explode("%", $data['query'][0]['packageid']);

        if ($data['query'][0]['packageid'] != '') {
            foreach ($pid as $pack_id) {

                $para = $this->add_result_model->get_val("SELECT `package_master`.id as pid,`package_master`.title as book_name,
              `test_branch_price`.`price` AS `price` FROM `package_master`
              INNER JOIN `test_branch_price`
              ON `package_master`.`id` = `test_branch_price`.`test_fk` and test_branch_price.type='2'
              WHERE `package_master`.`status` = '1' and `package_master`.id='$pack_id'
              AND `package_master`.`is_active`='1' AND `test_branch_price`.`branch_fk` = '$branch' ");


                array_push($data['book_list'], $para);
            }
        }
        if (in_array("1", $fast)) {
            $data['fasting'] = 'Fasting required for 12 hours.';
        } else {
            $data['fasting'] = 'Fasting not required.';
        }
        $this->load->library("Util");
        $util = new Util;
        if (empty($data['query'][0]["age"])) {
            if (!empty($data['query'][0]["dob"])) {

                $new_age = $util->get_age($data['query'][0]["dob"]);
                //$data['query'][0]["age"] = $new_age[0];
                if ($new_age[0] != 0) {
                    $data['query'][0]["age"] = $new_age[0] . " Years";
                } else if ($new_age[0] == 0 && $new_age[1] != 0) {
                    $data['query'][0]["age"] = $new_age[1] . " Months";
                } else if ($new_age[0] == 0 && $new_age[1] == 0 && $new_age[2] != 0) {
                    $data['query'][0]["age"] = $new_age[2] . " Days";
                }
            }
        }
        if (!empty($f_data)) {
            $new_age = $util->get_age($f_data[0]["dob"]);
            //$data['query'][0]["age"] = $new_age[0];
            if ($new_age[0] != 0) {
                $data['query'][0]["f_age"] = $new_age[0] . " Years";
            } else if ($new_age[0] == 0 && $new_age[1] != 0) {
                $data['query'][0]["f_age"] = $new_age[1] . " Months";
            } else if ($new_age[0] == 0 && $new_age[1] == 0 && $new_age[2] != 0) {
                $data['query'][0]["f_age"] = $new_age[2] . " Days";
            }
        }

        $data['time'] = $this->add_result_model->get_val("SELECT ts.start_time,ts.end_time FROM booking_info as b left join phlebo_time_slot as ts on b.time_slot_fk=ts.id WHERE ts.status='1' AND b.status='1' AND b.id='" . $data['query'][0]['booking_info'] . "'");
        //echo "<pre>"; print_r($data['parameter_list']); die();
        $pdfFilePath = FCPATH . "/upload/result/" . $data['query'][0]['order_id'] . "_invoice_withotletterhead.pdf";
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '512M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        $html = $this->load->view('user/user_job_invoice_pdf', $data, true); // render the view into HTML
        //$param = '"en-GB-x","A4","","",10,10,0,10,6,3,"P"'; // Landscape
        //$lorem = utf8_encode($html); // render the view into HTML
        //$html = "<!DOCTYPE html>                         <html><body>\u0627\u0644\u0643\u0647\u0631\u0628\u0627\u0621 \u0648 \u0627\u0644\u0633\u0628\u0627\u0643\u0629</body></html>      ";
        ob_end_flush();
        $this->load->library('pdf');

        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;

        $pdf->autoLangToFont = true;

        /* $pdf->SetHTMLHeader('<body>
          <div class="pdf_container">
          <div class="main_set_pdng_div">
          <div class="brdr_full_div">
          <div class="header_full_div">
          <img class="set_logo" src="logo.png" style="margin-top:15px;"/>
          </div>');

          $pdf->SetHTMLFooter('<div class="foot_num_div" style="margin-bottom:0;padding-bottom:0">
          <p class="foot_num_p" style="margin-bottom:2;padding-bottom:0"><img class="set_sign" src="pdf_phn_btn.png" style="width:"/></p>
          <p class="foot_lab_p" style="font-size:13px;margin-bottom:2;padding-bottom:0">LAB AT YOUR DOORSTEP</p>
          </div>
          <p class="lst_airmed_mdl" style="font-size:13px;margin-top:5px">Airmed Pathology Pvt. Ltd.</p>
          <p class="lst_31_addrs_mdl" style="font-size:12px"><span style="color:#9D0902;">Commercial Address : </span>31, Ambika Society, Next to Nabard Bank, Opp. Usmanpura, Ahmedabad, Gujarat - 380 013.</p>
          <p class="lst_31_addrs_mdl"><b><img src="email-icon.png" style="margin-bottom:-3px;width:13px"/> info@airmedlabs.com  <img src="web-icon.png" style="margin-bottom:-3px;width:13px"/> www.airmedlabs.com</b></p><p class="lst_31_addrs_mdl"><!--<img src="lastimg.png" style="margin-top:3px;"/>--> </p></div>
          </body>
          </html>'); */

        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 5, // margin_left
                5, // margin right
                30, // margin top
                30, // margin bottom
                2, // margin header
                2); // margin footer
        //$pdf->AddPage('P', '', 1, 'i', 'on');
        //$pdf->SetDirectionality('rtl');
        /* $pdf->AddPage('P', // L - landscape, P - portrait
          '', '', '', '', 00, // margin_left
          0, // margin right
          0, // margin top
          0, // margin bottom
          0, // margin header
          0); */

        //$pdf->SetDisplayMode('fullpage');
        //$pdf=new mPDF('utf-8','A4');
        //$pdf->debug = true;
        // $pdf->h2toc = array('H2' => 0);
        // $html = '';
        // Split $lorem into words
        $pdf->WriteHTML($html);
        $pdf->debug = true;
        $pdf->allow_output_buffering = TRUE;
        //$pdf->SetFooter('www.' . $_SERVER['HTTP_HOST'] . '||' . $new_time); // Add a footer for good measure <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        // $pdf->WriteHTML($html); // write the HTML into the PDF
        if (file_exists($pdfFilePath) == true) {

            $this->load->helper('file');
            unlink($path);
            //die("OK"); 
        }
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        $name = $data['query'][0]['order_id'] . "_invoice_withotletterhead.pdf";
        $this->add_result_model->master_fun_update('job_master', array('id', $id), array("invoice" => $name));
        $this->job_model->master_fun_insert("job_log", array("job_fk" => $id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "26", "date_time" => date("Y-m-d H:i:s")));
        redirect("/upload/result/" . $data['query'][0]['order_id'] . "_invoice_withotletterhead.pdf?" . time());
        //return $name;
    }

    function add_test() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $jid = $this->uri->segment(3);
        $add_test = $this->input->post("add_test");
        $test_id = explode("-", $add_test);
        if ($jid != '' && $add_test != '') {
            $this->load->library("Util");
            $util = new Util;
            $new_age = $util->add_test($jid, $test_id[1]);
            redirect("job-master/job-details/" . $jid);
        } else {
            redirect("job-master/pending-list");
        }
    }

    function manage_test($jid = null) {
        if ($jid != null) {
            $data["jid"] = $jid;
            $data["job_details"] = $this->getbranch_job_details($jid);

            /* echo "<pre>"; print_R($data["job_details"]); die(); */

            $bookingid = $data["job_details"][0]["booking_info"];
            $custid = $data["job_details"][0]["cust_fk"];

            $data["boofamily"] = $this->job_model->get_val("SELECT family_member_fk FROM booking_info WHERE id='$bookingid'");

            $data["custinfo"] = $this->job_model->get_val("SELECT mobile FROM `customer_master` WHERE id='$custid'");

            $data['panel_list'] = $this->job_model->master_fun_get_tbl_val("test_panel", array('status' => 1), array("name", "asc"));

            $this->load->view("test_manage", $data);
        } else {
            echo show_error("Oops somthing wrong Try again.");
        }
    }

    function get_job_details1($job_id) {

        $job_details = $this->job_model->master_fun_get_tbl_val("job_master", array("status !=" => "0", "id" => $job_id), array("id", "asc"));
        if (!empty($job_details)) {

            $booktestlist = $this->job_model->getjobstest($job_id);

            if ($booktestlist != "") {
                $testlist = $booktestlist->testfk;
            } else {
                $testlist = "";
            }

            $book_test = $this->job_model->getbook_test($job_id, $testlist);

            $book_package = $this->job_model->master_fun_get_tbl_val("book_package_master", array("job_fk" => $job_id, "status" => "1"), array("id", "desc"));

            $test_name = array();
            foreach ($book_test as $key) {

                $price1 = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`booked_job_test`.`price`,`booked_job_test`.`panel_fk` FROM `booked_job_test` LEFT JOIN    `test_master` ON `test_master`.`id`=SUBSTRING_INDEX(`booked_job_test`.`test_fk`, '-', -1)  WHERE `test_master`.`status` = '1'   AND `booked_job_test`.`status` = '1'  AND `test_master`.`id` ='" . $key["test_fk"] . "'  AND booked_job_test.`job_fk`='" . $job_id . "'");
                if (!empty($price1[0])) {
                    $test_name[] = $price1[0];
                }
            }
            $job_details[0]["book_test"] = $test_name;
            $package_name = array();
            foreach ($book_package as $key) {

                $price1 = $this->job_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $job_details[0]["test_city"] . "' AND `package_master`.`id`='" . $key["package_fk"] . "'");
                $package_name[] = $price1[0];
            }
            $job_details[0]["book_package"] = $package_name;
        }
        return $job_details;
    }

    function outsource_test($jid = null) {
        if ($jid != null) {
            $data["jid"] = $jid;
            $data["job_details"] = $this->get_job_details1($jid);
            $data['test_outsource'] = $this->job_model->test_outsource_list($jid);
            /* Test outsource start */
            $tid = array();
            $data['parameter_list'] = array();
            if (empty($data["job_details"][0]['book_test']) && !empty($data["job_details"][0]['book_package'])) {
                foreach ($data["job_details"][0]['book_package'] as $pkey) {
                    $p_test = $this->job_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey["id"] . "'");
                    foreach ($p_test as $tp_key) {
                        $tid[] = $tp_key["test_fk"];
                    }
                }
            } else if (!empty($data["job_details"][0]['book_test']) && !empty($data["job_details"][0]['book_package'])) {

                foreach ($data["job_details"][0]['book_package'] as $pkey) {
                    $p_test = $this->job_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey["id"] . "'");
                    foreach ($p_test as $tp_key) {
                        $tid[] = $tp_key["test_fk"];
                    }
                }
                foreach ($data["job_details"][0]['book_test'] as $pkey) {
                    $tid[] = $pkey["id"];
                }
            } else {
                foreach ($data["job_details"][0]['book_test'] as $pkey) {
                    $tid[] = $pkey["id"];
                }
            }
            foreach ($tid as $t_key) {
                $p_test = $this->job_model->get_val("SELECT sub_test_master.* FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `sub_test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $t_key . "'");
                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["sub_test"];
                }
            }
            if (isset($_GET['debug'])) {
                echo "<pre>";
                print_r($tid);
                die();
            }
            $data["new_test"] = array();
            if (!empty($tid)) {
                $data["new_test"] = $this->job_model->get_val("SELECT * from test_master where status='1' and id in(" . implode(",", $tid) . ") ORDER BY FIELD(id," . implode(",", $tid) . ")");
            }
            /* End */
            $city = $this->job_model->master_fun_get_tbl_val_with_select("city_fk", "test_cities", array("status" => 1, "id" => $data["job_details"][0]['test_city']), array("id", "asc"));
            $data["outsource_list"] = $this->job_model->outsource_list();
            $this->load->view("test_outsource", $data);
        } else {
            echo show_error("Oops somthing wrong Try again.");
        }
    }

    function add_outsource() {
        $test = $this->input->post('test_id');
        $jid = $this->input->post('jobid');
        $outs = $this->input->post('outsource_id');
        $data["login_data"] = logindata();
        foreach ($test as $ts) {
            $insert = $this->job_model->master_fun_insert("user_test_outsource", array("job_fk" => $jid, "test_fk" => $ts, "outsource_fk" => $outs, "created_by" => $data["login_data"]['id']));
            $this->job_model->master_fun_update_multi("use_formula", array("job_fk" => $jid, "test_fk" => $ts), array("test_status" => "3"));
            $uftid = $this->job_model->get_val("select id from use_formula where status='1' and job_fk='" . $jid . "' and test_fk='" . $ts . "'");
            if (empty($uftid)) {
                $data12 = array(
                    "job_fk" => $jid,
                    "test_fk" => $ts,
                    "use_formula" => 0,
                    "on_new_page" => 0,
                    "status" => 1,
                    "test_status" => 3
                );

                $val_add = $this->job_model->master_fun_insert("use_formula", $data12);
            } else {
                $this->job_model->master_fun_update_multi("use_formula", array("job_fk" => $jid, "test_fk" => $ts), array("test_status" => "3"));
            }
        }
        //echo "<pre>"; print_r($_POST); die();
        /* Nishit Outsource B2B code start */
        $outsrc_check = $this->job_model->master_fun_get_tbl_val("outsource_master", array("id" => $outs), array("id", "desc"));
        if ($outsrc_check[0]['b2b_fk'] > 0 && $outsrc_check[0]['b2c_fk'] > 0) {
            $job_details = $this->job_model->get_b2b_job_details($jid);
            $doc_discount_check = $this->job_model->get_val("SELECT * FROM `doctor_master` WHERE `status`='1' AND id='" . $job_details[0]["doctor"] . "'");
            if ($job_details[0]["branch_fk"] > 0) {
                $test_cut = $this->job_model->get_val("select IF(cut>0,cut,0) as cut from branch_master where id='" . $job_details[0]["branch_fk"] . "'");
                $cut = $test_cut[0]["cut"];
            } else {
                $cut = 0;
            }
            $barcode = $job_details[0]["barcode"];
            $lab_id = "";
            $logistic_id = "";
            $customer_name = $job_details[0]["full_name"];
            $customer_mobile = $job_details[0]["mobile"];
            $customer_email = $job_details[0]["email"];
            if ($customer_email == '') {
                $customer_email = 'noreply@airmedlabs.com';
            }
            $customer_gender = $job_details[0]["gender"];
            $dob = $job_details[0]["dob"];
            $address = $job_details[0]["address"];
            $order_id = $this->get_job_id();
            $date = date('Y-m-d H:i:s');

            $c_data = array(
                "phlebo_fk" => "",
                "barcode" => $barcode,
                "collect_from" => $outsrc_check[0]['b2c_fk'],
                "status" => '1',
                "createddate" => date('Y-m-d H:i:s')
            );
            $barcd = $this->job_model->master_fun_insert("logistic_log", $c_data);
            $labdetils = $this->job_model->master_fun_get_tbl_val("collect_from", array("id" => $outs, "status" => "1"), array("id", "desc"));
            $city = $labdetils[0]["city"];
            $price = 0;
            $new_price = 0;
            foreach ($test as $key) {
                $b2c_result = $this->job_model->get_val("SELECT * FROM `test_master` WHERE `test_master`.`status`='1' AND `test_master`.`id`='" . $key . "'");
                $b2b_result = $this->job_model->get_val("SELECT * FROM `sample_test_master` WHERE `sample_test_master`.`status`='1' AND `sample_test_master`.test_code='" . $b2c_result[0]["TEST_CODE"] . "'");
                /*  $result = $this->job_model->get_val("SELECT price,b2b_price,test_fk FROM sample_test_city_price where status='1' and test_fk='" . $b2b_result[0]["id"] . "' and lab_fk='" . $outsrc_check[0]['b2c_fk'] . "'");
                  if ($result[0]["special_price"] != "") {
                  $price = $result[0]["special_price"];
                  } else {
                  $price = $result[0]["b2b_price"];
                  } */
                /* Nishit Price changes start */
                //echo "SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $job_details[0]["test_city"] . "' AND `test_master`.`id`='" . $b2b_result[0]["id"] . "'"; die();
                $result = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $job_details[0]["test_city"] . "' AND `test_master`.`id`='" . $b2b_result[0]["id"] . "'");
                if ($result[0]["d_price"] > 0) {
                    $new_price1 = $result[0]["d_price"];
                } else {
                    if ($doc_discount_check[0]["discount"] > 0) {
                        $new_price1 = $result[0]["price"] - ($doc_discount_check[0]["discount"] * $result[0]["price"] / 100);
                    } else {
                        if ($cut > 0) {
                            $new_price1 = $result[0]["price"] - ($cut * $result[0]["price"] / 100);
                        } else {
                            $new_price1 = $result[0]["price"];
                        }
                    }
                }
                $new_price += round($new_price1);
                /* END */
            }
            $data = array(
                "barcode_fk" => $barcd,
                "order_id" => $order_id,
                "customer_name" => $customer_name,
                "customer_mobile" => $customer_mobile,
                "customer_email" => $customer_email,
                "customer_gender" => $customer_gender,
                "customer_dob" => $dob,
                "customer_address" => $address,
                "price" => $new_price,
                "status" => '1',
                "discount" => $discount,
                "payable_amount" => $new_price,
                "note" => $note,
                "date" => $date,
                "b2c_fk" => $jid
            );
            $insert = $this->job_model->master_fun_insert("sample_job_master", $data);
            foreach ($test as $key) {
                $result = $this->job_model->get_val("select t.id,p.price,(SELECT price_special FROM b2b_testspecial_price s  WHERE  s.test_fk=t.id AND s.status='1' AND s.lab_id='$lab_id') AS specialprice from test_master t LEFT JOIN test_master_city_price p ON t.id = p.test_fk  where t.status='1' AND p.city_fk='" . $job_details[0]["test_city"] . "' and t.id='" . $key . "' GROUP BY t.id");

                if ($result[0]['specialprice'] != "") {
                    $price1 = $result[0]['specialprice'];
                } else {
                    $discount1 = $labdetils[0]["test_discount"];
                    $discountprice = ($result[0]['price'] * $discount1 / 100);
                    $price1 = $result[0]['price'] - $discountprice;
                }
                $price1 = round($price1);
                $this->job_model->master_fun_insert("sample_job_test", array('job_fk' => $insert, "test_fk" => $result[0]["id"], "price" => $new_price));
            }


            $crditdetis = $this->job_model->creditget_last($outsrc_check[0]['b2c_fk']);
            if ($crditdetis != "") {
                $total = ($crditdetis->total - $new_price);
            } else {
                $total = (0 - $new_price);
            }
            $this->job_model->master_fun_insert("sample_credit", array("lab_fk" => $outsrc_check[0]['b2c_fk'], "job_fk" => $insert, "debit" => $new_price, "total" => $total, "created_date" => date("Y-m-d H:i:s")));

            $desti_lab = $outsrc_check[0]['b2b_fk'];
            $job_fk = $jid;
            $data = array(
                "lab_fk" => $desti_lab,
                "job_fk" => $barcd,
                "status" => "1",
                "createddate" => date("Y-m-d H:i:s")
            );
            $insert = $this->job_model->master_fun_insert("sample_destination_lab", $data);
            $this->job_model->master_fun_update("logistic_log", array("id", $job_fk), array("jobsstatus" => '2'));
        }
        /* END */

        $datas = $this->job_model->test_outsource_fetch($jid);
        foreach ($datas as $res) {
            $lid = "'" . $res->id . "'";
            echo '<tr id="table_tab_' . $res->id . '"><td>' . $res->test_name . '</td><td>' . $res->name . '</td><td><a href="javascript:void(0);" onclick="delete_test_outsource(' . $lid . ')">Delete</a></td></tr>';
        }
    }

    function delete_outsource() {
        $id = $this->input->post('id');
        $data["login_data"] = logindata();
        $test = $this->job_model->master_fun_update('user_test_outsource', array('id', $id), array("status" => 0, "updated_by" => $data["login_data"]['id']));
        $jdetails = $this->job_model->get_val("select * from user_test_outsource where id='" . $id . "'");
        $this->job_model->master_fun_update_multi("use_formula", array("job_fk" => $jdetails[0]["job_fk"], "test_fk" => $jdetails[0]["test_fk"]), array("test_status" => "0"));
        echo $id;
    }

    function update_job_test() {
        /* if (!is_loggedin()) {
          redirect('login');
          } */
        //echo "<pre>"; print_R($_POST); die();
        $this->load->library("util");
        $util = new Util;
        $data["login_data"] = logindata();
        /* Nishit code start */
        $selected = $this->input->get_post("selected");
        $jid = $this->input->get_post("jid");
        $bid = $this->input->get_post("bid");
        $selected_test = array();
        $selected_panel_test = array();
        $selected_package = array();



        foreach ($selected as $key) {
            $a = explode("-", $key);
            if ($a[0] == 'p') {
                $selected_package[] = $a[1];
            } else if ($a[0] == 't') {
                $selected_test[] = $a[1];
            } else if ($a[0] == 'pt') {
                $selected_panel_test[] = array("test_fk" => $a[1], "panel_fk" => $a[2]);
            }
        }
        /* Branch Cut start */
        if ($bid > 0) {
            $test_cut = $this->job_model->get_val("select IF(cut>0,cut,0) as cut from branch_master where id='" . $bid . "'");
            $cut = $test_cut[0]["cut"];
        } else {
            $cut = 0;
        }
        /* END */
        $get_old_test = $this->job_model->get_val("select test_fk from job_test_list_master where job_fk='" . $jid . "'");
        $get_old_package = $this->job_model->get_val("select package_fk from book_package_master where job_fk='" . $jid . "'");
        $old_test_package = array();
        foreach ($get_old_test as $key) {
            $chk_tst = explode("-", $key);
            $old_test_package[] = "t-" . $key["test_fk"];
        }
        foreach ($get_old_package as $key) {
            $old_test_package[] = "p-" . $key["package_fk"];
        }
        $this->job_model->master_fun_insert("job_deleted_test_package", array("job_fk" => $jid, "test_package" => implode(",", $old_test_package)));
        $this->job_model->delete_test(array("job_fk", $jid));
        $this->job_model->delete_packages(array("job_fk", $jid));

        $get_user_details = $this->job_model->get_val("select * from job_master where id='" . $jid . "'");
        $branch = $get_user_details[0]["branch_fk"];

        $this->job_model->master_fun_update_multi("patient_packdiscount", array("status" => "1", "job_fk" => $jid, "patient_id" => $get_user_details[0]["cust_fk"]), array("status" => "0"));


        $total_price = 0;
        $payable_price = 0;
        $this->job_model->master_fun_update("booked_job_test", array("job_fk", $jid), array("status" => "0"));


        $packdisdetils = $this->job_model->get_val("SELECT id,package  FROM `branch_package_discount` WHERE branch='$branch' AND STR_TO_DATE(active_till_date,'%Y-%m-%d') >= '" . date("Y-m-d") . "'");
        $dipackar = array();
        $packdisid = array();
        foreach ($packdisdetils as $pack) {

            $dipackar[] = $pack["package"];
            $packdisid[$pack["package"]] = $pack["id"];
        }

        foreach ($selected_package as $key) {

            if (in_array($key, $dipackar)) {

                $this->job_model->master_fun_insert("patient_packdiscount", array("job_fk" => $jid, "patient_id" => $get_user_details[0]["cust_fk"], "b_pdiscountid" => $packdisid[$key], "createddate" => date("Y-m-d H:i:s")));
            }
            $this->job_model->master_fun_insert("book_package_master", array("job_fk" => $jid, "date" => date("Y-m-d H:i:s"), "order_id" => $get_user_details[0]["order_id"], "package_fk" => $key, "cust_fk" => $get_user_details[0]["cust_fk"], "type" => "2"));
            $tst_price = $this->job_model->get_val("select price from test_branch_price where test_fk='" . $key . "' and branch_fk='" . $branch . "' and type='2' and status='1'");


            $this->check_active_package($key, $jid);

            /* Check Active package start */
            /*  $check_active_package = $this->job_model->master_fun_get_tbl_val("active_package", array("package_fk" => $key,"parent >"=>0,"job_fk" => $jid, "status" => "1"), array("id", "desc")); */

            $active_package = $this->job_model->get_val("SELECT `active_package`.id FROM
  `active_package` LEFT JOIN `package_master` ON `package_master`.`id` = `active_package`.`package_fk` WHERE `active_package`.`status` = '1' AND `due_to` >= '" . date("Y-m-d") . "' AND package_master.id='" . $tn[1] . "'  AND `active_package`.`user_fk` = '" . $get_user_details[0]["cust_fk"] . "' AND job_fk !='$jid' AND `active_package`.`parent`='0'");

            $pricepack = 0;
            if (empty($active_package[0]["id"]) || $active_package[0]["id"] == "") {
                $total_price = $total_price + $tst_price[0]["price"];
                $pricepack = $tst_price[0]["price"];
            }

            $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $jid, "test_city" => $get_user_details[0]["test_city"], "test_fk" => "p-" . $key, "price" => $pricepack));
            /* Check Active package end */
        }

        foreach ($selected_test as $key) {
            $return_test_price = $util->getbranchTEstPrice($jid, array($key));

            $this->job_model->master_fun_insert("job_test_list_master", array("job_fk" => $jid, "test_fk" => $key));
            $tst_price = $return_test_price;
            $new_price = $tst_price[0]["price"];

            $new_price = round($new_price);
            $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $jid, "test_city" => $get_user_details[0]["test_city"], "test_fk" => "t-" . $key, "price" => $new_price));
            //echo "select price from test_master_city_price where test_fk='" . $key . "' and city_fk='" . $get_user_details[0]["test_city"] . "' and status='1'";die();
            /*  $test_price = $this->job_model->get_val("select price from test_master_city_price where test_fk='" . $key . "' and city_fk='" . $get_user_details[0]["test_city"] . "' and status='1'"); */
            $total_price = $total_price + $new_price;
        }
        foreach ($selected_panel_test as $key) {
            $this->job_model->master_fun_insert("job_test_list_master", array("job_fk" => $jid, "test_fk" => $key["test_fk"]));
            $tst_price = $this->job_model->get_val("select * from panel_tests where test_fk='" . $key["test_fk"] . "' and city_fk='" . $get_user_details[0]["test_city"] . "' and panel_fk='" . $key["panel_fk"] . "' and status='1'");
            $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $jid, "test_city" => $get_user_details[0]["test_city"], "test_fk" => "pt-" . $key["test_fk"], "price" => $tst_price[0]["price"], "panel_fk" => $key["panel_fk"]));
            //echo "select price from test_master_city_price where test_fk='" . $key . "' and city_fk='" . $get_user_details[0]["test_city"] . "' and status='1'";die();
            $test_price = $this->job_model->get_val("select * from panel_tests where test_fk='" . $key["test_fk"] . "' and city_fk='" . $get_user_details[0]["test_city"] . "' and panel_fk='" . $key["panel_fk"] . "' and status='1'");
            $total_price = $total_price + $test_price[0]['price'];
        }

        /* Collection charge start */
        $total_price = $total_price + $get_user_details[0]["collectioncharge_amount"];
        /* Collection charge end */

        $payable_price = $total_price;
        /* Check discount start */
        if ($get_user_details[0]["discount"] > 0) {
            $dprice = ($payable_price * $get_user_details[0]["discount"]) / 100;
            $payable_price = $payable_price - $dprice;
        }
        /* Check discount end */
        /* Check online payable start */
        $online_amount = 0;
        $get_user_payment = $this->job_model->master_fun_get_tbl_val("payment", array("job_fk" => $jid, "type" => "job", "status" => "success"), array("id", "desc"));
        foreach ($get_user_payment as $key) {
            $online_amount = $online_amount + $key["amount"];
        }
        $payable_price = $payable_price - $online_amount;
        /* Check online payable end */
        /* Check payable price start */
        $receiv_amount = 0;
        $get_user_details1 = $this->job_model->master_fun_get_tbl_val("job_master_receiv_amount", array("job_fk" => $jid, "status" => "1"), array("id", "desc"));
        foreach ($get_user_details1 as $key) {
            $receiv_amount = $receiv_amount + $key["amount"];
        }
        $payable_price = $payable_price - $receiv_amount;
        /* Check payable price end */

        /* Check daboted from wallet start */
        $get_dabited_from_wallet = $this->job_model->master_fun_get_tbl_val("wallet_master", array("job_fk" => $jid, "debit !=" => null, "status" => "1"), array("id", "desc"));
        if (!empty($get_dabited_from_wallet)) {
            $payable_price = $payable_price - $get_dabited_from_wallet[0]["debit"];
        }
        /* Check daboted from wallet end */
        //echo $payable_price . "<br>" . $total_price; die();
        $this->job_model->master_fun_update('job_master', array('id', $jid), array("price" => round($total_price), "payable_amount" => round($payable_price)));
        $this->job_model->master_fun_insert("job_log", array("job_fk" => $jid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "14", "date_time" => date("Y-m-d H:i:s")));
        echo 1;
        /* Nishit code end. */
    }

    function check_active_package($pid, $jid) {
        /* Nishit active package start */
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]["id"];
        $this->load->library("util");
        $util = new Util;
        $util->check_active_package($pid, $jid, $uid);
        /* Nishit active package end */
    }

    function nishit_test() {

        $data = $this->job_model->master_fun_get_tbl_val("test_parameter", array("status" => 1), array("id", "desc"));
        $n_data = array();
        foreach ($data as $key) {
            $n_data[] = $key["parameter_fk"];
        }
        echo implode(",", $n_data);
    }

    function get_test_list2() {
        $selected = $this->input->get_post("ids");
        $data['query'] = $this->job_model->prescription_details($pds);
        /* $referral_list = $this->job_model->master_fun_get_tbl_val("doctor_master", array('status' => 1), array("full_name", "asc"));
          $refer = '<option value="">--Select--</option>';
          foreach ($referral_list as $referral) {
          $refer .= '<option value="' . $referral['id'] . '"';
          $refer .= '>' . ucwords($referral['full_name']) . '-' . $referral['mobile'] . '</option>';
          }

          $customer = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "full_name !=" => ""), array("full_name", "asc"));
          $customer_data = '<option value="">--Select--</option>';
          foreach ($customer as $key) {
          $customer_data .= '<option value="' . $key["id"] . '">' . ucfirst($key["full_name"]) . ' - ' . $key["mobile"] . '</option>';
          }
         */
        $selected_test = array();
        $selected_package = array();
        foreach ($selected as $key) {
            $a = explode("-", $key);
            if ($a[0] == 'p') {
                $selected_package[] = $a[1];
            } else {
                $selected_test[] = $a[1];
            }
        }
        if (empty($data['query'][0]["city"])) {
            $data['query'][0]["city"] = 1;
        }
        //print_r($selected_test);print_r($selected_package); die();
        $test = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='1' AND `test_master_city_price`.`city_fk`='" . $data['query'][0]["city"] . "'");
        $package = $this->job_model->get_val("SELECT 
              `package_master`.*,
              `package_master_city_price`.`a_price` AS `a_price1`,
              `package_master_city_price`.`d_price` AS `d_price1`
              FROM
              `package_master`
              INNER JOIN `package_master_city_price`
              ON `package_master`.`id` = `package_master_city_price`.`package_fk`
              WHERE `package_master`.`status` = '1'
              AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $data['query'][0]["city"] . "' ");
        // $test_list = '<option value="">--Select Test--</option>';
//        $test_list = '<select class="chosen-select" data-live-search="true" id="test" data-placeholder="Select Test" onchange="get_test_price();">
//			<option value="">--Select Test--</option>';
        echo '<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">
                                jQuery(".chosen-select").chosen({
                                    search_contains: true
                                });
				/*$("#exampleModal").modal("show");*/
				$("#show_test_btn").attr("style","display:none;");
                            </script>';
        echo '<select class="chosen-select" data-live-search="true" id="test" data-placeholder="Select Test" onchange="get_test_price();">
			<option value="">--Select Test--</option>';
        foreach ($test as $ts) {
            if (!in_array($ts['id'], $selected_test)) {
                echo ' <option value="t-' . $ts['id'] . '"> ' . ucfirst($ts['test_name']) . ' (Rs.' . $ts['price'] . ')</option>';
            }
        }
        foreach ($package as $pk) {
            if (!in_array($pk['id'], $selected_package)) {
                echo '<option value="p-' . $pk['id'] . '"> ' . ucfirst($pk['title']) . ' (Rs.' . $pk['d_price1'] . ')</option>';
            }
        }


        echo '</select>';
        //echo json_encode(array("refer" => $refer, "test_list" => $test_list, "customer" => $customer_data));
    }

    function send_sms_due_payment() {
        $jid = $this->input->post('job_fk');
        $mobile2 = $this->input->post('mobile_no');


        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        if (!empty($jid)) {
            $job_details = $this->job_model->master_fun_get_tbl_val("job_master", array("id" => $jid), array("id", "desc"));
            $cust_details = $this->job_model->master_fun_get_tbl_val("customer_master", array("id" => $job_details[0]["cust_fk"]), array("id", "desc"));
            //print_R($cust_details); die();

            $this->load->helper("sms");
            $notification = new Sms();
            //$notification::send($mobile, $sms_message);
            /* Pinkesh send sms code end */
            $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "due_payment"), array("id", "asc"));
            $sms_message = preg_replace("/{{NAME}}/", ucfirst($cust_details[0]["full_name"]), $sms_message[0]["message"]);
            $sms_message = preg_replace("/{{LINK}}/", base_url() . "u/j/" . $jid, $sms_message);

            //if ($notify == 1) {
            /* Pinkesh send sms code start */
            foreach ($mobile2 as $mobile) {

                //$sms_message="done"; 
                //echo $cust_details[0]["mobile"]."-".$sms_message; die();
                $notification::send($mobile, $sms_message);
            }
        }
        $this->session->set_flashdata("success", array("SMS Successfully send."));
        redirect(base_url() . "u/j/" . $jid);
        redirect("job-master/pending-list");
    }

    function send_sms_due_payment_old($jid) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        if (!empty($jid)) {
            $job_details = $this->job_model->master_fun_get_tbl_val("job_master", array("id" => $jid), array("id", "desc"));
            $cust_details = $this->job_model->master_fun_get_tbl_val("customer_master", array("id" => $job_details[0]["cust_fk"]), array("id", "desc"));
            //print_R($cust_details); die();
            $this->load->helper("sms");
            $notification = new Sms();
            //$notification::send($mobile, $sms_message);
            /* Pinkesh send sms code end */

            //if ($notify == 1) {
            /* Pinkesh send sms code start */
            $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "due_payment"), array("id", "asc"));
            $sms_message = preg_replace("/{{NAME}}/", ucfirst($cust_details[0]["full_name"]), $sms_message[0]["message"]);
            $sms_message = preg_replace("/{{LINK}}/", base_url() . "u/j/" . $jid, $sms_message);
            $mobile = $cust_details[0]['mobile'];
            //$sms_message="done"; 
            //echo $cust_details[0]["mobile"]."-".$sms_message; die();
            $notification::send($cust_details[0]["mobile"], $sms_message);
        }
        $this->session->set_flashdata("success", array("SMS Successfully send."));
        redirect(base_url() . "u/j/" . $jid);
        redirect("job-master/pending-list");
    }

    function job_dispatch_old() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $jid = $this->uri->segment(3);
        $status = $this->uri->segment(4);
        if ($jid != '') {
            $this->job_model->master_fun_update("job_master", array("id", $jid), array("dispatch" => $status));
            $this->job_model->master_fun_insert("job_log", array("job_fk" => $jid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => ($status == 1) ? 10 : 11, "date_time" => date("Y-m-d H:i:s")));

            if (!empty($this->session->userdata("job_master_r"))) {
                redirect($this->session->userdata("job_master_r"), "refresh");
            } else {
                redirect("job-master/pending-list", "refresh");
            }
        } else {
            redirect("job-master/pending-list");
        }
    }

    function job_dispatch() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $jid = $this->input->post(job_id);

        $clinical_history = $this->job_model->get_val("select id,clinical_history,prescription_message,prescription_file from job_master where id='$jid'");
        echo json_encode($clinical_history);

//        $status = $this->input->post(status);
//        if ($jid != '') {
//            $this->job_model->master_fun_update("job_master", array("id", $jid), array("dispatch" => $status));
//            $this->job_model->master_fun_insert("job_log", array("job_fk" => $jid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => ($status == 1) ? 10 : 11, "date_time" => date("Y-m-d H:i:s")));
//            echo 1;
//        } else {
//            redirect("job-master/pending-list");
//        }
    }

    function mail_chimp() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["success"] = $this->session->flashdata("success");
        $data["templete"] = $this->job_model->master_fun_get_tbl_val("mail_chimp_templete", array('status' => 1), array("title", "asc"));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view("mail_chimp", $data);
        $this->load->view('footer');
    }

    function add_slot() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $title = $this->input->post("title");
        $content = $this->input->post("content");
        $this->job_model->master_fun_insert("mail_chimp_templete", array("title" => $title, "content" => $content, "createddate" => date("Y-m-d H:i:s")));
        $this->session->set_flashdata("success", array("Template successfully added."));
        redirect("job_master/mail_chimp");
    }

    function discount_update() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $jid = $this->uri->segment(3);
        $discount_per = $this->input->post("discount_per");
        $discount_flat = $this->input->post("discount_flat");
        $discount_note = $this->input->post("discount_note");
        $payable_price = $this->input->post("payable_price");
        $old_discount = $this->input->post("old_discount");
        $dabited_from_wallet = $this->input->post("dabited_from_wallet");
        $price = $this->input->post("price");
        $discount = $discount_per - $old_discount;
        $disc_amount = $price * $discount_per / 100;
        if ($jid != '') {
            $phlebo_collect_amount = $this->job_model->get_val("SELECT SUM(amount) AS amount FROM `phlebo_collect_amount` WHERE job_fk='" . $jid . "' AND STATUS='1'");
            // pinkesh code start
            $collect_amount = $this->job_model->get_val("SELECT SUM(amount) AS total FROM `job_master_receiv_amount` WHERE job_fk='" . $jid . "' AND status='1'");
            //pinkesh code end
            $collected_amount = 0;
            if (!empty($collect_amount) || !empty($phlebo_collect_amount)) {
                $collected_amount = $phlebo_collect_amount[0]["amount"] + $collect_amount[0]["total"];
            }
            $payable_amount = $price - round($disc_amount) - $dabited_from_wallet - $collected_amount;
            $this->job_model->master_fun_update("job_master", array("id", $jid), array("payable_amount" => $payable_amount, "discount" => $discount_per, "discount_note" => $discount_note));
            $this->job_model->master_fun_insert("job_log", array("job_fk" => $jid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "24", "date_time" => date("Y-m-d H:i:s")));
            /* Nishit check job discount start */
            if ($discount_per > 0) {
                $job_details = $this->job_model->get_val("SELECT 
  job_master.*,
  IF(booking_info.family_member_fk>0&&customer_family_master.phone>0,customer_family_master.phone,customer_master.mobile) AS phone_no
FROM
  `job_master` 
  INNER JOIN `booking_info` 
    ON `booking_info`.id = `job_master`.booking_info 
  LEFT JOIN `customer_family_master` 
    ON `customer_family_master`.id = booking_info.family_member_fk 
    LEFT JOIN `customer_master` ON `customer_master`.id = booking_info.user_fk
WHERE job_master.id = '" . $jid . "' 
  AND job_master.status != '0' ");

                $branch = $job_details[0]["branch_fk"];
                $getbranchalert = $this->job_model->get_val("SELECT smsalert,emailalert FROM branch_master WHERE id='$branch' AND STATUS='1'");
                $smsalert = $getbranchalert[0]["smsalert"];
                $emailalert = $getbranchalert[0]["emailalert"];
                if ($smsalert == 1) {

                    $order_discount = round($job_details[0]["price"] * $job_details[0]["discount"] / 100);
                    $payable_price = $job_details[0]["price"] - round($job_details[0]["price"] * $job_details[0]["discount"] / 100);
                    $c_message = "Dear customer, Your \n Booking ID : " . $job_details[0]["order_id"] . "\n Total price : Rs." . $job_details[0]["price"] . " \n Discount price : Rs." . $order_discount . " \n Payable price : Rs." . $payable_price . " \n For any query please call (8101161616).\n Thanks for using Airmed.";
                    $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $job_details[0]["phone_no"], "message" => $c_message, "created_date" => date("Y-m-d H:i:s")));
                }
            }
            /* END */
            $this->check_send_report($jid);
            redirect("job-master/job-details/" . $jid, "refresh");
        } else {
            redirect("job-master/pending-list");
        }
    }

    function get_job_phlebo_schedule($jid) {
        //echo "<pre>";
        $data["job_details"] = $this->job_model->master_fun_get_tbl_val("job_master", array('status !=' => 0, "id" => $jid), array("id", "asc"));
        $data["booking_info"] = $this->job_model->master_fun_get_tbl_val("booking_info", array("id" => $data["job_details"][0]["booking_info"]), array("id", "asc"));
        $data["phlebo_list"] = $this->job_model->get_val("select name,id,email from phlebo_master where status='1' AND test_city='" . $data["job_details"][0]["test_city"] . "' order by name asc");
        $cnt = 0;
        $html = '<table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Phlebotomy Name</th>
                                <th>Assign Job</th>
                            </tr>
                        </thead>
                        <tbody>';
        foreach ($data["phlebo_list"] as $key) {
            $phlebo_booking = $this->job_model->get_val("SELECT `phlebo_assign_job`.*,TIME_FORMAT(`phlebo_time_slot`.`start_time`,'%h:%i %p') AS start_time,TIME_FORMAT(`phlebo_time_slot`.`end_time`,'%h:%i %p') AS end_time FROM `phlebo_assign_job` LEFT JOIN `phlebo_time_slot` ON `phlebo_assign_job`.`time_fk`=`phlebo_time_slot`.`id` WHERE phlebo_assign_job.status='1' AND phlebo_assign_job.date='" . $data["booking_info"][0]["date"] . "' and phlebo_assign_job.phlebo_fk='" . $key["id"] . "' ORDER BY start_time ASC");
            $data["phlebo_list"][$cnt]["booking_info"] = $phlebo_booking;
            $html .= '<tr>
                                <td>' . ucfirst($key["name"]) . '</td>
                                <td>';
            $cnt = 1;
            foreach ($phlebo_booking as $p_key) {
                $j_details = $this->job_model->get_val("SELECT job_master.*,`test_cities`.`name` as test_city_name FROM job_master 
INNER JOIN `test_cities` ON job_master.`test_city`=`test_cities`.`id` 
WHERE `job_master`.`id`='" . $p_key["job_fk"] . "' AND `job_master`.`status`!='0' AND `job_master`.`sample_collection`='0'");
                $t_address = $p_key["address"];
                if (empty($p_key["address"])) {
                    $t_address = $j_details[0]["address"];
                }
                if (!empty($j_details)) {
                    if ($cnt != 1) {
                        $html .= "<hr>";
                    }
                    if (empty($p_key["time"])) {
                        $html .= $p_key["start_time"] . " to " . $p_key["end_time"] . "<br>" . $t_address . "," . $j_details[0]["test_city_name"];
                    } else {
                        $html .= $p_key["time"] . "<br>" . $t_address . "," . $j_details[0]["test_city_name"];
                    }
                    $cnt++;
                }
            }
            if ($cnt == 1) {
                $html .= "Free";
            }
            $html .= '</td>
                            </tr>';
            $cnt++;
        }
        $html .= '
                        </tbody>
                    </table>';
        echo $html;
        //print_r($data["phlebo_list"]);
    }

    function test_payment() {
        $json_data = json_decode('{"mihpayid":"6108033686","mode":"CC","status":"success","unmappedstatus":"captured","key":"IcZRGO7S","txnid":"0d8e135ecf0774b83799","amount":"500.0","addedon":"2017-04-22 21:26:57","productinfo":"Airmedlabs","firstname":"Arvind Sharma","lastname":"","address1":"","address2":"","city":"","state":"","country":"","zipcode":"","email":"me.arvindsharma1982@gmail.com","phone":"9825063658","udf1":"","udf2":"","udf3":"","udf4":"","udf5":"","udf6":"","udf7":"","udf8":"","udf9":"","udf10":"","hash":"9beeb49781ddf98cf859b207812a420c3561e6608dc129fd77accad185d20b075bf54e7961b8cc0e759ea054c7851a33f79930ca99784dd652a94a497956cb17","field1":"711240248987","field2":"063542","field3":"2487360262171120","field4":"2487360262171120","field5":"","field6":"","field7":"00","field8":"","field9":"SUCCESS","PG_TYPE":"HDFCPG","encryptedPaymentId":"D25B466C8D30A7BFA8F0B3460B9CAD6C","bank_ref_num":"2487360262171120","bankcode":"CC","error":"E000","error_Message":"No Error","cardToken":"5faa96b05dbdcf27af5c613bd93c2345de98e311","name_on_card":"payu","cardnum":"416644XXXXXX3088","cardhash":"This field is no longer supported in postback params.","amount_split":"{\"PAYU\":\"500.0\"}","payuMoneyId":"146211637","discount":"0.00","net_amount_debit":"500"}');

        echo '<form action="' . base_url() . 'service/service_v5/payu_success/3159/%20//wallet/ios" type="POST">';
        foreach ($json_data as $key => $value) {
            //echo '<input type="text" name="'.$key.'" value="'.$value.'"/>';
            echo "<input type='text' name='" . $key . "' value='" . $value . "'/>";
            echo "<input type='text' name='debug' value='1'/>";
        }
        echo "<input type='submit' value='add'/>";
        echo '</form>';
    }

    function open_report() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $pid = $this->uri->segment(3);
        $type = $this->uri->segment(4);
        if ($pid != null)
            $data["job_details"] = $this->job_model->master_fun_get_tbl_val("report_master", array('status !=' => 0, "job_fk" => $pid), array("id", "asc"));
        if ($type == 1) {
            if (!empty($data["job_details"][0]["report"])) {
                $this->job_model->master_fun_insert("job_log", array("job_fk" => $pid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => 12, "date_time" => date("Y-m-d H:i:s")));
                $this->job_model->master_fun_insert("print_report_count", array("job_fk" => $pid, "print_by" => $data["login_data"]["id"], "created_date" => date("Y-m-d H:i:s")));
                echo "<script>window.location='" . base_url() . "upload/report/" . $data["job_details"][0]["report"] . "?" . rand(0000000, 9999999) . "';</script>";
            } else {
                echo "Not available.";
            }
        } else {
            if (!empty($data["job_details"][0]["without_laterpad"])) {
                $this->job_model->master_fun_insert("job_log", array("job_fk" => $pid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "message_fk" => 13, "date_time" => date("Y-m-d H:i:s")));
                $this->job_model->master_fun_insert("print_report_count", array("job_fk" => $pid, "print_by" => $data["login_data"]["id"], "created_date" => date("Y-m-d H:i:s")));
                echo "<script>window.location='" . base_url() . "upload/report/" . $data["job_details"][0]["without_laterpad"] . "?" . rand(0000000, 9999999) . "';</script>";
            } else {
                echo "Not available.";
            }
        }
    }

    function ack($id) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $this->load->model('add_result_model');
        $data['query'] = $this->add_result_model->job_details($id);

        $data['book_list'] = array();
        $data['jid'] = $id;
        $tid = explode(",", $data['query'][0]['testid']);
        $fast = array();
        $branch = $data['query'][0]['branch_fk'];
        $emergency_tests = $this->job_model->master_fun_get_tbl_val("booking_info", array('id' => $data['query'][0]["booking_info"]), array("id", "asc"));
        $f_data = $this->job_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
        $f_data1 = $this->job_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
        $relation = "Self";
        if (!empty($f_data1)) {
            $relation = ucfirst($f_data1[0]["name"] . " (" . $f_data[0]["name"] . ")");
        }

        $data['test_for'] = $relation;
        $data['relation'] = $f_data;
        if ($data['query'][0]['testid'] != '') {
            $get_user_details = $this->add_result_model->master_fun_get_tbl_val("customer_master", array("id" => $data["login_data"]["id"], "status" => "1"), array("id", "desc"));
            foreach ($tid as $tst_id) {
                //$para = $this->add_result_model->get_val("SELECT t.test_name as book_name,t.id as tid,p.price as price FROM test_master as t left join test_master_city_price as p  on p.test_fk=t.id WHERE t.status='1' AND p.status='1' AND t.id='" . $tst_id . "' AND p.city_fk='" . $data['query'][0]['test_city'] . "' order by t.test_name ASC");
                $para = $this->add_result_model->get_val("SELECT t.test_name as book_name,t.id as tid,bj.price AS price  FROM `booked_job_test` bj LEFT JOIN   test_master AS t ON t.id=SUBSTRING_INDEX(bj.`test_fk`, '-', -1)  WHERE t.status='1'  AND t.id='" . $tst_id . "' AND bj.job_fk='" . $id . "' AND bj.status='1' order by t.test_name ASC");
                array_push($data['book_list'], $para);
                $test_fast = $this->add_result_model->get_val("SELECT fasting_requird FROM test_master WHERE status='1' AND id='" . $tst_id . "'");
                array_push($fast, $test_fast[0]['fasting_requird']);
            }
        }

        $pid = explode("%", $data['query'][0]['packageid']);
        if ($data['query'][0]['packageid'] != '') {
            foreach ($pid as $pack_id) {
                $para = $this->add_result_model->get_val("SELECT `package_master`.id as pid,`package_master`.title as book_name,
              `test_branch_price`.`price` AS `price` FROM `package_master`
              INNER JOIN `test_branch_price`
              ON `package_master`.`id` = `test_branch_price`.`test_fk` and test_branch_price.type='2'
              WHERE `package_master`.`status` = '1' and `package_master`.id='$pack_id'
              AND `package_master`.`is_active`='1' AND `test_branch_price`.`branch_fk` = '$branch' ");
                array_push($data['book_list'], $para);
            }
        }
        if (in_array("1", $fast)) {
            $data['fasting'] = 'Fasting required for 12 hours.';
        } else {
            $data['fasting'] = 'Fasting not required.';
        }

        /* Nishit changes start */
        /* if (empty($get_user_details[0]["age"])) {
          if (!empty($get_user_details[0]["dob"])) {
          $this->load->library("Util");
          $util = new Util;
          $new_age = $util->get_age($get_user_details[0]["dob"]);
          $data['query'][0]["age"] = $new_age[0];
          }
          } */
        $this->load->library("Util");
        $util = new Util;
        if (empty($data['query'][0]["age"])) {
            if (!empty($data['query'][0]["dob"])) {

                $new_age = $util->get_age($data['query'][0]["dob"]);
                if ($new_age[0] != 0) {
                    $data['query'][0]["age"] = $new_age[0] . " Years";
                } else if ($new_age[0] == 0 && $new_age[1] != 0) {
                    $data['query'][0]["age"] = $new_age[1] . " Months";
                } else if ($new_age[0] == 0 && $new_age[1] == 0 && $new_age[2] != 0) {
                    $data['query'][0]["age"] = $new_age[2] . " Days";
                }
            }
        }

        if (!empty($f_data)) {
            $new_age = $util->get_age($f_data[0]["dob"]);
            //$data['query'][0]["age"] = $new_age[0];
            if ($new_age[0] != 0) {
                $data['query'][0]["f_age"] = $new_age[0] . " Years";
            } else if ($new_age[0] == 0 && $new_age[1] != 0) {
                $data['query'][0]["f_age"] = $new_age[1] . " Months";
            } else if ($new_age[0] == 0 && $new_age[1] == 0 && $new_age[2] != 0) {
                $data['query'][0]["f_age"] = $new_age[2] . " Days";
            }
        }

        /* End */
        $data["wallet_manage"] = $this->add_result_model->master_fun_get_tbl_val("wallet_master", array("job_fk" => $id, "status" => "1", "debit >" => 0), array("id", "desc"));
        $data["online_pay"] = $this->add_result_model->master_fun_get_tbl_val("payment", array("job_fk" => $id, "status" => "success", "type" => "job"), array("id", "desc"));
        $data["phlebo_collect"] = $this->job_model->get_val("SELECT `phlebo_collect_amount`.*,`phlebo_master`.`name` FROM `phlebo_collect_amount` INNER JOIN phlebo_master ON `phlebo_collect_amount`.`phlebo_fk`= `phlebo_master`.`id` WHERE `phlebo_collect_amount`.`status`='1' AND job_fk='" . $id . "'");
        $job_master_receiv_amount = $this->add_result_model->master_fun_get_tbl_val("job_master_receiv_amount", array("job_fk" => $id, "status" => "1"), array("id", "asc"));

        $data["job_master_receiv_amount"] = array();
        foreach ($job_master_receiv_amount as $key1) {
            $transacion_id = '';
            if ($key1["type"] == 'User Pay') {
                $payment = $this->add_result_model->master_fun_get_tbl_val("payment", array("id" => $key1["paypal_log_fk"]), array("id", "asc"));
                $transacion_id = $payment[0]["payomonyid"];
            }
            $key1["transaction_id"] = $transacion_id;
            $data["job_master_receiv_amount"][] = $key1;
        }

        $data['time'] = $this->add_result_model->get_val("SELECT ts.start_time,ts.end_time FROM booking_info as b left join phlebo_time_slot as ts on b.time_slot_fk=ts.id WHERE ts.status='1' AND b.status='1' AND b.id='" . $data['query'][0]['booking_info'] . "'");
        $time = date("d-m-Y_H-i-s");
        $u_name = str_replace(" ", "_", $data['query'][0]["full_name"]);
        $u_name = str_replace("/", "_", $u_name);
        $pdfFilePath = FCPATH . "/upload/ack/" . $u_name . "_" . $time . "_ack.pdf";
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '512M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        $html = $this->load->view('user/user_job_ack_pdf', $data, true);
        //die(); // render the view into HTML
        //$param = '"en-GB-x","A4","","",10,10,0,10,6,3,"P"'; // Landscape
        //$lorem = utf8_encode($html); // render the view into HTML
        //$html = "<!DOCTYPE html>                         <html><body>\u0627\u0644\u0643\u0647\u0631\u0628\u0627\u0621 \u0648 \u0627\u0644\u0633\u0628\u0627\u0643\u0629</body></html>      ";
        if ($_REQUEST['debug'] == 1) {
            echo "8";
        }
        ob_end_flush();
        $this->load->library('pdf');

        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;
        $pdf->autoLangToFont = true;

        $pdf->SetHTMLHeader('<body>
        <div class="pdf_container">
            <div class="main_set_pdng_div">
                <div class="brdr_full_div">
                    <div class="header_full_div">
                        <img class="set_logo" src="logo.png"/>
                    </div>');

        $pdf->SetHTMLFooter('<div class="foot_num_div" style="margin-bottom:0;padding-bottom:0">
		<p class="foot_num_p" style="margin-bottom:2;padding-bottom:0"><img class="set_sign" src="pdf_phn_btn.png" style="width:"/></p>
		<p class="foot_lab_p" style="font-size:13px;margin-bottom:2;padding-bottom:0">LAB AT YOUR DOORSTEP</p>
	</div>
		<p class="lst_airmed_mdl" style="font-size:13px;margin-top:5px">Airmed Pathology Pvt. Ltd.</p>
		<p class="lst_31_addrs_mdl" style="font-size:12px"><span style="color:#9D0902;">Commercial Address : </span>31, Ambika Society, Next to Nabard Bank, Opp. Usmanpura, Ahmedabad, Gujarat - 380 013.</p>
		<p class="lst_31_addrs_mdl"><b><img src="email-icon.png" style="margin-bottom:-3px;width:13px"/> info@airmedlabs.com  <img src="web-icon.png" style="margin-bottom:-3px;width:13px"/> www.airmedlabs.com</b></p><p class="lst_31_addrs_mdl"><!--<img src="lastimg.png" style="margin-top:3px;"/>--> </p></div>
        </body>
</html>');
        $data["panelcount"] = $this->job_model->get_val("SELECT COUNT(*)  AS c FROM `booked_job_test` WHERE  panel_fk > 0 AND `status`='1' AND job_fk='" . $id . "'");

        if ($data["panelcount"][0]['c'] > 0) {
            $pdf->SetHTMLHeader('<body>
        
<div class="pdf_container">
            <div class="main_set_pdng_div_panel">
                <div class="brdr_full_div">
                    <div class="header_full_div_panel">
                        <center><h3> Must & More HealthCare Pvt. Ltd. </h3>
                        <div style="font-size:10px;"><b>3rd Floor, Kings Mall, Plot No. 1B1,
Twin District Centre, Sector 10, Rohini Delhi85
Tel: 01166444444 </b><div></center>
                    </div>');

            $pdf->SetHTMLFooter('
                </div>
            </div>
        </div>
    </body>
</html>');
        }
        if ($data["panelcount"][0]['c'] > 0 && $data['query'][0]["branch_fk"] == 10) {
            $pdf->SetHTMLHeader('<body>
        
<div class="pdf_container">
            <div class="main_set_pdng_div_panel">
                <div class="brdr_full_div">
                    <div class="header_full_div_panel">
                        <center><h3> Satyakiran Healthcare Pvt. Ltd. </h3>
                        <div style="font-size:10px;"><b>Subhash Chowk,
Sonepat Tel: 0130-2242938, 2242939 </b><div></center>
                    </div>');

            $pdf->SetHTMLFooter('
                </div>
            </div>
        </div>
    </body>
</html>');
        }


        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 5, // margin_left
                5, // margin right
                40, // margin top
                25, // margin bottom
                2, // margin header
                2); // margin footer
        // Split $lorem into words
        $pdf->WriteHTML($html);
        //$pdf->debug = true;
        //$pdf->allow_output_buffering = TRUE;
        //$pdf->SetFooter('www.' . $_SERVER['HTTP_HOST'] . '||' . $new_time); // Add a footer for good measure <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        // $pdf->WriteHTML($html); // write the HTML into the PDF
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        $name = $u_name . "_" . $time . "_ack.pdf";
        $this->add_result_model->master_fun_update('job_master', array('id', $id), array("ack" => $name));
        $this->job_model->master_fun_insert("job_log", array("job_fk" => $id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "27", "date_time" => date("Y-m-d H:i:s")));
        //redirect("/upload/ack/" . $data['query'][0]['order_id'] . "_" . $time . "_ack.pdf");
        $downld = $this->_push_file($pdfFilePath, $u_name . "_" . $time . "_ack.pdf");
        $this->delete_downloadfile($pdfFilePath);
    }

    //pinkesh code start
    function ack_wtlpd($id) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $this->load->model('add_result_model');
        $data['query'] = $this->add_result_model->job_details($id);
        if ($_REQUEST['debug']) {
            echo $this->db->last_query();
            print_r($data['query'][0]['testid']);
        }
        $data['book_list'] = array();
        $data['jid'] = $id;
        $tid = explode(",", $data['query'][0]['testid']);
        $fast = array();
        $emergency_tests = $this->job_model->master_fun_get_tbl_val("booking_info", array('id' => $data['query'][0]["booking_info"]), array("id", "asc"));
        $f_data = $this->job_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
        $f_data1 = $this->job_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
        $relation = "Self";
        if (!empty($f_data1)) {
            $relation = ucfirst($f_data1[0]["name"] . " (" . $f_data[0]["name"] . ")");
        }
        $data['test_for'] = $relation;
        $data['relation'] = $f_data;
        if ($data['query'][0]['testid'] != '') {
            $get_user_details = $this->add_result_model->master_fun_get_tbl_val("customer_master", array("id" => $data["login_data"]["id"], "status" => "1"), array("id", "desc"));
            foreach ($tid as $tst_id) {
                //$para = $this->add_result_model->get_val("SELECT t.test_name as book_name,t.id as tid,p.price as price FROM test_master as t left join test_master_city_price as p  on p.test_fk=t.id WHERE t.status='1' AND p.status='1' AND t.id='" . $tst_id . "' AND p.city_fk='" . $data['query'][0]['test_city'] . "' order by t.test_name ASC");
                $para = $this->add_result_model->get_val("SELECT t.test_name as book_name,t.id as tid,bj.price AS price  FROM `booked_job_test` bj LEFT JOIN   test_master AS t ON t.id=SUBSTRING_INDEX(bj.`test_fk`, '-', -1)  WHERE t.status='1'  AND t.id='" . $tst_id . "' AND bj.job_fk='" . $id . "' AND bj.status='1' order by t.test_name ASC");
                if ($_REQUEST['debug']) {
                    echo $this->db->last_query();
                    print_r($para);
                }
                array_push($data['book_list'], $para);
                $test_fast = $this->add_result_model->get_val("SELECT fasting_requird FROM test_master WHERE status='1' AND id='" . $tst_id . "'");
                array_push($fast, $test_fast[0]['fasting_requird']);
            }
        }
        $pid = explode("%", $data['query'][0]['packageid']);
        if ($data['query'][0]['packageid'] != '') {
            foreach ($pid as $pack_id) {
                $para = $this->add_result_model->get_val("SELECT p.id as pid,p.title as book_name,pr.d_price as price FROM package_master as p left join package_master_city_price as pr on pr.package_fk=p.id WHERE p.status='1' AND pr.status='1' AND p.id='" . $pack_id . "' AND pr.city_fk='" . $data['query'][0]['test_city'] . "' order by p.title ASC");
                array_push($data['book_list'], $para);
            }
        }
        if (in_array("1", $fast)) {
            $data['fasting'] = 'Fasting required for 12 hours.';
        } else {
            $data['fasting'] = 'Fasting not required.';
        }
        if (empty($data['query'][0]["age"])) {
            if (!empty($data['query'][0]["dob"])) {
                $this->load->library("Util");
                $util = new Util;
                $new_age = $util->get_age($data['query'][0]["dob"]);
                if ($new_age[0] != 0) {
                    $data['query'][0]["age"] = $new_age[0] . " Years";
                } else if ($new_age[0] == 0 && $new_age[1] != 0) {
                    $data['query'][0]["age"] = $new_age[1] . " Months";
                } else if ($new_age[0] == 0 && $new_age[1] == 0 && $new_age[2] != 0) {
                    $data['query'][0]["age"] = $new_age[2] . " Days";
                }
            }
        }
        if (!empty($f_data)) {
            $new_age = $util->get_age($f_data[0]["dob"]);
            //$data['query'][0]["age"] = $new_age[0];
            if ($new_age[0] != 0) {
                $data['query'][0]["f_age"] = $new_age[0] . " Years";
            } else if ($new_age[0] == 0 && $new_age[1] != 0) {
                $data['query'][0]["f_age"] = $new_age[1] . " Months";
            } else if ($new_age[0] == 0 && $new_age[1] == 0 && $new_age[2] != 0) {
                $data['query'][0]["f_age"] = $new_age[2] . " Days";
            }
        }
        /* End */
        $data["wallet_manage"] = $this->add_result_model->master_fun_get_tbl_val("wallet_master", array("job_fk" => $id, "status" => "1", "debit >" => 0), array("id", "desc"));
        $data["online_pay"] = $this->add_result_model->master_fun_get_tbl_val("payment", array("job_fk" => $id, "status" => "success", "type" => "job"), array("id", "desc"));
        $data["phlebo_collect"] = $this->job_model->get_val("SELECT `phlebo_collect_amount`.*,`phlebo_master`.`name` FROM `phlebo_collect_amount` INNER JOIN phlebo_master ON `phlebo_collect_amount`.`phlebo_fk`= `phlebo_master`.`id` WHERE `phlebo_collect_amount`.`status`='1' AND job_fk='" . $id . "'");
        $job_master_receiv_amount = $this->add_result_model->master_fun_get_tbl_val("job_master_receiv_amount", array("job_fk" => $id, "status" => "1"), array("id", "asc"));

        $data["job_master_receiv_amount"] = array();
        foreach ($job_master_receiv_amount as $key1) {
            $transacion_id = '';
            if ($key1["type"] == 'User Pay') {
                $payment = $this->add_result_model->master_fun_get_tbl_val("payment", array("id" => $key1["paypal_log_fk"]), array("id", "asc"));
                $transacion_id = $payment[0]["payomonyid"];
            }
            $key1["transaction_id"] = $transacion_id;
            $data["job_master_receiv_amount"][] = $key1;
        }
        $data['time'] = $this->add_result_model->get_val("SELECT ts.start_time,ts.end_time FROM booking_info as b left join phlebo_time_slot as ts on b.time_slot_fk=ts.id WHERE ts.status='1' AND b.status='1' AND b.id='" . $data['query'][0]['booking_info'] . "'");
        $time = date("d-m-Y_H-i-s");

        $u_name = str_replace(" ", "_", $data['query'][0]["full_name"]);
        $u_name = str_replace("/", "_", $u_name);

        $pdfFilePath = FCPATH . "/upload/ack/" . $u_name . "_" . $time . "_ack_wtlpd.pdf";
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '512M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        $html = $this->load->view('user/user_job_ack_pdf', $data, true);
        if ($_REQUEST['debug']) {
            echo $html;
            $this->db->close();
            die();
        }
        //die(); // render the view into HTML
        //$param = '"en-GB-x","A4","","",10,10,0,10,6,3,"P"'; // Landscape
        //$lorem = utf8_encode($html); // render the view into HTML
        //$html = "<!DOCTYPE html>                         <html><body>\u0627\u0644\u0643\u0647\u0631\u0628\u0627\u0621 \u0648 \u0627\u0644\u0633\u0628\u0627\u0643\u0629</body></html>      ";
        ob_end_flush();
        $this->load->library('pdf');

        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;
        $pdf->autoLangToFont = true;

        /* $pdf->SetHTMLHeader('<body>
          <div class="pdf_container">
          <div class="main_set_pdng_div">
          <div class="brdr_full_div">
          <div class="header_full_div">
          <img class="set_logo" src="logo.png"/>
          </div>');

          $pdf->SetHTMLFooter('<div class="foot_num_div">
          <p class="foot_num_p"><img class="set_sign" src="pdf_phn_btn.png"/></p>
          <p class="foot_lab_p">lab at your doorstep</p>
          </div>
          <p class="lst_airmed_mdl">Airmed Pathology Pvt Ltd.</p>
          <p class="lst_31_addrs_mdl"><b>Corporate Office</b> : 31, Ambika Society, Next to Nabard Bank, Opp. Usmanpura, Ahmedabad,</p><p class="lst_31_addrs_mdl"> Gujarat - 380 013.</p>
          <p class="lst_31_addrs_mdl"><b>Branch Office</b> : Kings Mall,Opp. Rohini West Metro Station,Rohini,Delhi-110085</p>
          <p style="text-align: center;"><span> +91 79 27552277, </span>
          <span>info@airmedlabs.com, </span>
          <span> www.airmedlabs.com</span></p>
          </div>
          </div>
          </div>
          </body>
          </html>'); */
        $data["panelcount"] = $this->job_model->get_val("SELECT COUNT(*)  AS c FROM `booked_job_test` WHERE  panel_fk > 0 AND `status`='1' AND job_fk='" . $id . "'");

        if ($data["panelcount"][0]['c'] > 0) {
            /* $pdf->SetHTMLHeader('<body>

              <div class="pdf_container">
              <div class="main_set_pdng_div_panel">
              <div class="brdr_full_div">
              <div class="header_full_div_panel">
              <center><h3> Must & More HealthCare Pvt. Ltd. </h3>
              <div style="font-size:10px;"><b>3rd Floor, Kings Mall, Plot No. 1B1,
              Twin District Centre, Sector 10, Rohini Delhi85
              Tel: 01166444444 </b><div></center>
              </div>');

              $pdf->SetHTMLFooter('
              </div>
              </div>
              </div>
              </body>
              </html>'); */
        }



        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 5, // margin_left
                5, // margin right
                30, // margin top
                30, // margin bottom
                2, // margin header
                2); // margin footer
        // Split $lorem into words
        $pdf->WriteHTML($html);
        //$pdf->debug = true;
        //$pdf->allow_output_buffering = TRUE;
        //$pdf->SetFooter('www.' . $_SERVER['HTTP_HOST'] . '||' . $new_time); // Add a footer for good measure <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        // $pdf->WriteHTML($html); // write the HTML into the PDF
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        $name = $u_name . "_" . $time . "_ack.pdf";
        $this->add_result_model->master_fun_update('job_master', array('id', $id), array("ack" => $name));
        $this->job_model->master_fun_insert("job_log", array("job_fk" => $id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "27", "date_time" => date("Y-m-d H:i:s")));
        //redirect("/upload/ack/" . $data['query'][0]['order_id'] . "_" . $time . "_ack.pdf");
        $downld = $this->_push_file($pdfFilePath, $u_name . "_" . $time . "_ack_wtlpd.pdf");
        $this->delete_downloadfile($pdfFilePath);
    }

    //pinkesh code end

    private function _push_file($path, $name) {
        // make sure it's a file before doing anything!
        if (is_file($path)) {
            // required for IE
            if (ini_get('zlib.output_compression')) {
                ini_set('zlib.output_compression', 'Off');
            }
            // get the file mime type using the file extension
            $this->load->helper('file');

            $mime = get_mime_by_extension($path);

            // Build the headers to push out the file properly.
            header('Pragma: public');     // required
            header('Expires: 0');         // no cache
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT');
            header('Cache-Control: private', false);
            header('Content-Type: ' . $mime);  // Add the mime type from Code igniter.
            header('Content-Disposition: attachment; filename="' . basename($name) . '"');  // Add the file name
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($path)); // provide file size
            header('Connection: close');
            readfile($path); // push it out
//    exit();
        }
    }

    function delete_downloadfile($path) {
        $this->load->helper('file');
        unlink($path);
    }

    function get_customer() {
        $selected = $this->input->get_post("selected");
        $selected1 = explode("-", $selected);
        $referral_list = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("full_name", "asc"));
        $refer = '<option value="">--Select Customer--</option>';
        foreach ($referral_list as $referral) {
            $refer .= '<option value="c-' . $referral['id'] . '"';
            if ($selected1[0] == 'c' && $selected1[1] == $referral['id']) {
                $refer .= ' selected';
            }
            $refer .= '>' . ucwords($referral['full_name']) . ' - ' . $referral['mobile'] . '</option>';
        }

        $referral_list = $this->job_model->get_val("SELECT `customer_family_master`.*,`customer_master`.`full_name`,`customer_master`.`mobile` FROM `customer_family_master` INNER JOIN `customer_master` ON `customer_family_master`.`user_fk`=`customer_master`.`id` WHERE `customer_family_master`.`status`='1' AND `customer_master`.`status`='1' order by customer_family_master.name asc");
        foreach ($referral_list as $referral) {
            $refer .= '<option value="f-' . $referral['id'] . '"';
            if ($selected1[0] == 'f' && $selected1[1] == $referral['id']) {
                $refer .= ' selected';
            }
            $refer .= '>' . ucwords($referral['name']) . ' - ' . $referral['phone'] . '(Relative of ' . ucwords($referral['full_name']) . ' - ' . $referral['mobile'] . ')</option>';
        }
        echo json_encode(array("customer" => $refer));
    }

    function get_branch() {
        $data["login_data"] = logindata();
        $data["cntr_arry"] = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data["login_data"]['branch_fk'] as $key) {
                $data["cntr_arry"][] = $key["branch_fk"];
            }
        }

        $plm = $this->input->post("plm");
        $city = $this->input->post("city");
        $refer = '';
        if (!empty($city) && empty($plm)) {
            //$referral_list = $this->job_model->master_fun_get_tbl_val("branch_master", array('status' => 1, "parent_fk" => $plm), array("branch_name", "asc"));
            $referral_list = $this->job_model->get_val("SELECT * FROM `branch_master` WHERE `status`='1' AND city='" . $city . "' order by branch_name asc");
        }
        if (!empty($plm)) {
            //$referral_list = $this->job_model->master_fun_get_tbl_val("branch_master", array('status' => 1, "parent_fk" => $plm), array("branch_name", "asc"));
            $referral_list = $this->job_model->get_val("select * from branch_master where status='1' and (parent_fk ='" . $plm . "' or id='" . $plm . "') order by branch_name asc");
        }
        if (empty($city) && empty($plm)) {
            $referral_list = $this->job_model->get_val("select * from branch_master where status='1' order by branch_name asc");
        }
        foreach ($referral_list as $referral) {
            if (!empty($data["cntr_arry"])) {
                if (in_array($referral['id'], $data["cntr_arry"])) {
                    $refer .= '<option value="' . $referral['id'] . '"';
                    if ($selected1[1] == $referral['id']) {
                        $refer .= ' selected';
                    }
                    $refer .= '>' . ucwords($referral['branch_name']) . '</option>';
                }
            } else {
                $refer .= '<option value="' . $referral['id'] . '"';
                if ($selected1[1] == $referral['id']) {
                    $refer .= ' selected';
                }
                $refer .= '>' . ucwords($referral['branch_name']) . '</option>';
            }
        }
        if ($refer == '') {
            //echo "<option value=''>Data not available.</option>";
        }
        echo $refer;
    }

    function get_plm() {
        $data["login_data"] = logindata();
        $data["cntr_arry"] = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data["login_data"]['branch_fk'] as $key) {
                $data["cntr_arry"][] = $key["branch_fk"];
            }
        }

        $city = $this->input->post("city");
        $refer = '<option value="">--All PLM--</option>';
        if (!empty($city)) {
            $referral_list = $this->job_model->get_val("SELECT *
FROM `branch_master`
WHERE `status` = 1
AND `city` = '" . $city . "'
AND (`parent_fk` IS NULL OR `parent_fk` ='')
ORDER BY `branch_name` ASC");
        } else {
            $referral_list = $this->job_model->get_val("SELECT *
FROM `branch_master`
WHERE `status` = 1
AND (`parent_fk` IS NULL OR `parent_fk` ='')
ORDER BY `branch_name` ASC");
        }
        foreach ($referral_list as $referral) {
            if (!empty($data["cntr_arry"])) {
                if (in_array($referral['id'], $data["cntr_arry"])) {
                    $refer .= '<option value="' . $referral['id'] . '"';
                    if ($selected1[1] == $referral['id']) {
                        $refer .= ' selected';
                    }
                    $refer .= '>' . ucwords($referral['branch_name']) . '</option>';
                }
            } else {
                $refer .= '<option value="' . $referral['id'] . '"';
                if ($selected1[1] == $referral['id']) {
                    $refer .= ' selected';
                }
                $refer .= '>' . ucwords($referral['branch_name']) . '</option>';
            }
        }
        if ($refer == '') {
            //echo "<option value=''>Data not available.</option>";
        }
        echo $refer;
    }

    function telecaller_get_branch() {
        $data["login_data"] = logindata();
        $data["cntr_arry"] = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data["login_data"]['branch_fk'] as $key) {
                $data["cntr_arry"][] = $key["branch_fk"];
            }
        }
        $city = $this->input->post("city");
        $refer = '';
        if (!empty($city)) {
            $referral_list = $this->job_model->master_fun_get_tbl_val("branch_master", array('status' => 1, "city" => $city), array("branch_name", "asc"));
        } else {
            $referral_list = $this->job_model->master_fun_get_tbl_val("branch_master", array('status' => 1), array("branch_name", "asc"));
        }
        foreach ($referral_list as $referral) {
            if (!empty($data["cntr_arry"])) {
                if (in_array($referral['id'], $data["cntr_arry"])) {
                    $refer .= '<option value="' . $referral['id'] . '"';
                    if ($selected1[1] == $referral['id']) {
                        $refer .= ' selected';
                    }
                    $refer .= '>' . ucwords($referral['branch_name']) . '</option>';
                }
            } else {
                $refer .= '<option value="' . $referral['id'] . '"';
                if ($selected1[1] == $referral['id']) {
                    $refer .= ' selected';
                }
                $refer .= '>' . ucwords($referral['branch_name']) . '</option>';
            }
        }
        if ($refer == '') {
            //echo "<option value=''>Data not available.</option>";
        }
        echo $refer;
    }

    function pdf_invoice_wlh($id) {
        $data["login_data"] = logindata();
        $this->load->model('add_result_model');
        $data['query'] = $this->add_result_model->job_details($id);
        $data['book_list'] = array();
        $tid = explode(",", $data['query'][0]['testid']);
        $fast = array();
        $emergency_tests = $this->job_model->master_fun_get_tbl_val("booking_info", array('id' => $data['query'][0]["booking_info"]), array("id", "asc"));

        $f_data = $this->job_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
        $f_data1 = $this->job_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
        $relation = "Self";
        if (!empty($f_data1)) {
            $relation = ucfirst($f_data1[0]["name"] . " (" . $f_data[0]["name"] . ")");
        }
        $data['test_for'] = $relation;
        $data['relation'] = $f_data;

        if ($data['query'][0]['testid'] != '') {
            $get_user_details = $this->add_result_model->master_fun_get_tbl_val("customer_master", array("id" => $data["login_data"]["id"], "status" => "1"), array("id", "desc"));
            foreach ($tid as $tst_id) {
                //    $para = $this->add_result_model->get_val("SELECT t.test_name as book_name,t.id as tid,p.price as price FROM test_master as t left join test_master_city_price as p  on p.test_fk=t.id WHERE t.status='1' AND p.status='1' AND t.id='" . $tst_id . "' AND p.city_fk='" . $data['query'][0]['test_city'] . "' order by t.test_name ASC");
                $para = $this->add_result_model->get_val("SELECT t.test_name as book_name,t.id as tid,bj.price AS price  FROM `booked_job_test` bj LEFT JOIN   test_master AS t ON t.id=SUBSTRING_INDEX(bj.`test_fk`, '-', -1)  WHERE t.status='1'  AND t.id='" . $tst_id . "' AND bj.job_fk='" . $id . "' order by t.test_name ASC");

                array_push($data['book_list'], $para);
                $test_fast = $this->add_result_model->get_val("SELECT fasting_requird FROM test_master WHERE status='1' AND id='" . $tst_id . "'");
                array_push($fast, $test_fast[0]['fasting_requird']);
            }
        }

        $pid = explode("%", $data['query'][0]['packageid']);
        if ($data['query'][0]['packageid'] != '') {
            foreach ($pid as $pack_id) {
                $para = $this->add_result_model->get_val("SELECT p.id as pid,p.title as book_name,pr.d_price as price FROM package_master as p left join package_master_city_price as pr on pr.package_fk=p.id WHERE p.status='1' AND pr.status='1' AND p.id='" . $pack_id . "' AND pr.city_fk='" . $data['query'][0]['test_city'] . "' order by p.title ASC");
                array_push($data['book_list'], $para);
            }
        }
        if (in_array("1", $fast)) {
            $data['fasting'] = 'Fasting required for 12 hours.';
        } else {
            $data['fasting'] = 'Fasting not required.';
        }

        if (empty($get_user_details[0]["age"])) {
            if (!empty($get_user_details[0]["dob"])) {
                $this->load->library("Util");
                $util = new Util;
                $new_age = $util->get_age($get_user_details[0]["dob"]);
                $data['query'][0]["age"] = $new_age[0];
            }
        }
        $data['time'] = $this->add_result_model->get_val("SELECT ts.start_time,ts.end_time FROM booking_info as b left join phlebo_time_slot as ts on b.time_slot_fk=ts.id WHERE ts.status='1' AND b.status='1' AND b.id='" . $data['query'][0]['booking_info'] . "'");
        $pdfFilePath = FCPATH . "/upload/result/" . $data['query'][0]['order_id'] . "_invoice_without.pdf";
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '32M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        $html = $this->load->view('user/user_job_invoice_pdf_without_lhd', $data, true); // render the view into HTML

        ob_end_flush();
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;
        $pdf->autoLangToFont = true;
        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 5, // margin_left
                5, // margin right
                30, // margin top
                40, // margin bottom
                2, // margin header
                2); // margin footer
        $pdf->WriteHTML($html);
        $pdf->debug = true;
        $pdf->allow_output_buffering = TRUE;
        if (file_exists($pdfFilePath) == true) {
            $this->load->helper('file');
            unlink($path);
        }
        //die("Under maintenance. Please try after few minutes.");
        //    die($pdfFilePath);
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        //$name = $data['query'][0]['order_id'] . "_invoice.pdf";
        //$this->add_result_model->master_fun_update('job_master', array('id', $id), array("invoice" => $name));
        //$this->job_model->master_fun_insert("job_log", array("job_fk" => $id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "26", "date_time" => date("Y-m-d H:i:s")));
        //redirect("/upload/result/" . $data['query'][0]['order_id'] . "_invoice.pdf?" . time());

        $downld = $this->_push_file($pdfFilePath, $data['query'][0]['order_id'] . "_invoice_without.pdf");
        $this->delete_downloadfile($pdfFilePath);
    }

    function change_barcode() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $jid = $this->uri->segment(3);

        $referral_by = $this->input->post("barcode");

        if ($jid != '' && $referral_by != '') {
            $this->job_model->master_fun_update("job_master", array("id", $jid), array("barcode" => $referral_by));
            redirect("job-master/job-details/" . $jid);
        } else {
            redirect("job-master/pending-list");
        }
    }

    function send_report_sms() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $job_fk = $this->input->post("job_fk");
        $mobile_no = $this->input->post("mobile_no");
        $sms = $this->input->post("sms");

        if ($job_fk != '' && !empty($mobile_no) && trim($sms) != '') {
            $job_details = $this->job_model->get_val("SELECT job_master.`id`,`doctor_master`.`full_name`,`doctor_master`.`mobile` FROM job_master INNER JOIN `doctor_master` ON job_master.`doctor`=`doctor_master`.`id` WHERE job_master.`id`='" . $job_fk . "'");
            $data = array(
                "job_fk" => $job_fk,
                "mobile" => implode(",", $mobile_no),
                "sms" => $sms,
                "created_date" => date("Y-m-d H:i:s"),
                "send_by" => $data["login_data"]["id"]
            );
            foreach ($mobile_no as $key) {
                $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $key, "message" => $sms, "created_date" => date("Y-m-d H:i:s")));
            }
            if (!empty($job_details)) {
                $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $job_details[0]["mobile"], "message" => $sms, "created_date" => date("Y-m-d H:i:s")));
            }
            $this->job_model->master_fun_insert("send_report_sms", $data);
            $this->job_model->master_fun_update("job_master", array("id", $job_fk), array("send_sms" => '1'));
            $this->session->set_flashdata("success", array("SMS successfully send."));
        } else {
            $this->session->set_flashdata("unsuccess", array("SMS not send. Try again."));
        }
        redirect("job-master/pending-list", "refresh");
    }

    function send_report_email() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->helper("Email");
        $email_cnt = new Email;
        //echo "<pre>"; print_r($_POST); die();
        $data["login_data"] = logindata();
        $job_fk = $this->input->post("job_fk");
        $cust_fk = $this->input->post("cust_fk");
        $email = $this->input->post("email");

        if ($job_fk != '' && !empty($cust_fk) && !empty($email)) {
            $job_details = $this->job_model->get_val("SELECT * FROM `report_master` WHERE `status`='1' AND job_fk='" . $job_fk . "'");
            $job = $this->job_model->get_val("SELECT * FROM `job_master` WHERE id='" . $job_fk . "'");
            $query = $this->job_model->job_details($job_fk);
            //echo "<pre>";print_r($query); die();
            $data = array(
                "job_fk" => $job_fk,
                "email" => implode(",", $email),
                "created_date" => date("Y-m-d H:i:s"),
                "send_by" => $data["login_data"]["id"]
            );
            $this->job_model->master_fun_insert("send_report_mail", $data);
            $this->job_model->master_fun_update("job_master", array("id", $job_fk), array("send_email" => '1'));
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            foreach ($email as $key) {
                $message1 = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $query[0]["full_name"] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Report completed successfully. </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Report ID : ' . $query[0]["order_id"] . ' </p>    
		<p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message1 = $email_cnt->get_design($message1);
                //echo $message1; die();
                $this->email->clear(TRUE);
                $this->email->to($key);
                //  $this->email->to('jeel@virtualheight.com');
                $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
                $this->email->subject("Report Completed");
                $this->email->message($message1);
                if ($job[0]["payable_amount"] <= 0 || $job[0]["payable_amount"] == '') {
                    $attatchPath = "";
                    foreach ($job_details as $key) {
                        $attatchPath = FCPATH . "upload/report/" . $key['report'];
                        $this->email->attach($attatchPath);
                    }
                }
                $this->email->send();
            }

            $this->session->set_flashdata("success", array("Email successfully send."));
        } else {
            $this->session->set_flashdata("unsuccess", array("Email not send. Try again."));
        }
        if (!empty($this->session->userdata("job_master_r"))) {
            redirect("job-master/pending-list", "refresh");
        } else {
            redirect("job-master/pending-list", "refresh");
        }
    }

    function send_report_sms1() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $job_fk = $this->input->post("job_fk");
        $mobile_no = $this->input->post("mobile_no");
        $sms = $this->input->post("sms");

        if ($job_fk != '' && !empty($mobile_no) && trim($sms) != '') {
            $job_details = $this->job_model->get_val("SELECT job_master.`id`,`doctor_master`.`full_name`,`doctor_master`.`mobile` FROM job_master INNER JOIN `doctor_master` ON job_master.`doctor`=`doctor_master`.`id` WHERE job_master.`id`='" . $job_fk . "'");
            $data = array(
                "job_fk" => $job_fk,
                "mobile" => implode(",", $mobile_no),
                "sms" => $sms,
                "created_date" => date("Y-m-d H:i:s"),
                "send_by" => $data["login_data"]["id"]
            );
            foreach ($mobile_no as $key) {
                $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $key, "message" => $sms, "created_date" => date("Y-m-d H:i:s")));
            }
            if (!empty($job_details)) {
                $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $job_details[0]["mobile"], "message" => $sms, "created_date" => date("Y-m-d H:i:s")));
            }
            $this->job_model->master_fun_insert("send_report_sms", $data);
            $this->job_model->master_fun_update("job_master", array("id", $job_fk), array("send_sms" => '1'));
            $this->session->set_flashdata("success", array("SMS successfully send."));
        } else {
            $this->session->set_flashdata("unsuccess", array("SMS not send. Try again."));
        }
        redirect("job-master/job-details/" . $job_fk, "refresh");
    }

    function send_report_email1() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->helper("Email");
        $email_cnt = new Email;
        //echo "<pre>"; print_r($_POST); die();
        $data["login_data"] = logindata();
        $job_fk = $this->input->post("job_fk");
        $cust_fk = $this->input->post("cust_fk");
        $email = $this->input->post("email");

        if ($job_fk != '' && !empty($cust_fk) && !empty($email)) {
            $job_details = $this->job_model->get_val("SELECT * FROM `report_master` WHERE `status`='1' AND job_fk='" . $job_fk . "'");
            $job = $this->job_model->get_val("SELECT * FROM `job_master` WHERE id='" . $job_fk . "'");
            $query = $this->job_model->job_details($job_fk);
            //echo "<pre>";print_r($query); die();
            $data = array(
                "job_fk" => $job_fk,
                "email" => implode(",", $email),
                "created_date" => date("Y-m-d H:i:s"),
                "send_by" => $data["login_data"]["id"]
            );
            $this->job_model->master_fun_insert("send_report_mail", $data);
            $this->job_model->master_fun_update("job_master", array("id", $job_fk), array("send_email" => '1'));
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            foreach ($email as $key) {
                $message1 = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $query[0]["full_name"] . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Report completed successfully. </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Report ID : ' . $query[0]["order_id"] . ' </p>    
		<p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                $message1 = $email_cnt->get_design($message1);
                //echo $message1; die();
                $this->email->clear(TRUE);
                $this->email->to($key);
                //  $this->email->to('jeel@virtualheight.com');
                $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
                $this->email->subject("Report Completed");
                $this->email->message($message1);
                if ($job[0]["payable_amount"] <= 0 || $job[0]["payable_amount"] == '') {
                    $attatchPath = "";
                    foreach ($job_details as $key) {
                        $attatchPath = FCPATH . "upload/report/" . $key['report'];
                        $this->email->attach($attatchPath);
                    }
                }
                $this->email->send();
            }

            $this->session->set_flashdata("success", array("Email successfully send."));
        } else {
            $this->session->set_flashdata("unsuccess", array("Email not send. Try again."));
        }
        redirect("job-master/job-details/" . $job_fk, "refresh");
    }

    function get_user_info_old() {
        $id = $this->uri->segment(3);
        $jid = $this->uri->segment(4);
        if (!empty($id) && !empty($jid)) {
            $job_details = $this->job_model->get_val("SELECT email FROM `customer_master` WHERE id='" . $id . "'");
            $job_history = $this->job_model->get_val("SELECT `send_report_mail`.*,`admin_master`.`name` FROM `send_report_mail` INNER JOIN `admin_master` ON `send_report_mail`.`send_by`=`admin_master`.`id` WHERE `send_report_mail`.`status`='1' AND `send_report_mail`.`job_fk`='" . $jid . "'");
            echo json_encode(array("status" => "1", "data" => $job_details, "history" => $job_history));
        } else {
            echo json_encode(array("status" => "0", "data" => array()));
        }
    }

    function get_user_info() {
        $this->load->model('add_result_model');
        $id = $this->uri->segment(3);
        $jid = $this->uri->segment(4);
        $data['query'] = $this->add_result_model->job_details1($jid);
        $relation_email = "";
        $output = array();
        if (!empty($id) && !empty($jid)) {
            $job_details = $this->job_model->get_val("SELECT email FROM `customer_master` WHERE id='" . $id . "'");

            $cust_rel_mobile = "";
            $data['user_booking_info'] = $this->add_result_model->master_fun_get_tbl_val("booking_info", array('status' => 1, 'id' => $data['query'][0]["booking_info"]), array("id", "asc"));
            foreach ($job_details as $row) {
                $output[] = $row["email"];
            }
            if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
                $data['user_family_info'] = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
                if ($data['user_family_info'][0]["email"] != "") {
                    $output[] = $relation_email = $data['user_family_info'][0]["email"];
                }
            }


            /*  $cust_fk = $this->job_model->get_val("SELECT cust_fk FROM job_master master  WHERE id='" . $jid . "' ORDER BY id DESC LIMIT 1"); 
              $data['cust_fk1']= $this->job_model->get_val("SELECT * FROM booking_info  WHERE user_fk='" . $id . "' ORDER BY id DESC");
              $job_details = $this->job_model->get_val("SELECT c.email,cfm.email as custemail  FROM `booking_info` as b LEFT JOIN `customer_family_master` as cfm on b.user_fk =cfm.user_fk LEFT JOIN `customer_master` as c on c.id =cfm.user_fk WHERE cfm.user_fk ='" .  $id . "' AND b.family_member_fk != 'null'");
             */
            // $job_details = $this->job_model->get_val("SELECT c.email,cfm.email as EMAIL FROM `customer_master` as C LEFT JOIN customer_family_master as cfm on c.id =cfm.user_fk WHERE c.id='" . $id . "' ORDER BY c.id DESC LIMIT 1");

            $job_history = $this->job_model->get_val("SELECT `send_report_mail`.*,`admin_master`.`name` FROM `send_report_mail` INNER JOIN `admin_master` ON `send_report_mail`.`send_by`=`admin_master`.`id` WHERE `send_report_mail`.`status`='1' AND `send_report_mail`.`job_fk`='" . $jid . "'");

            echo json_encode(array("status" => "1", "data" => $output, "history" => $job_history));
        } else {
            echo json_encode(array("status" => "0", "data" => array()));
        }
    }

    function delete_assign_phlebo() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $id = $this->uri->segment(4);
        $jid = $this->uri->segment(3);
        if (!empty($jid) && !empty($id)) {
            $this->job_model->master_fun_update("phlebo_assign_job", array("id", $id), array("status" => '0'));
            $this->session->set_flashdata("assign_phlebo_success", array("Assign phlebo deleted successfully."));
            redirect("job-master/job-details/" . $jid);
        } else {
            redirect("job-master/pending-list");
        }
    }

    function accep_assign_phlebo_job() {
        $id = $this->uri->segment(4);
        $job_id = $this->uri->segment(3);
        /* Nishit code start */
        $data = array("is_accept" => "1");
        $insert = $this->job_model->master_fun_update("phlebo_assign_job", array("id", $id), $data);
        $details = $this->job_model->master_fun_get_tbl_val("phlebo_assign_job", array('id' => $id), array("id", "asc"));
        $phlebo_id = $details[0]["phlebo_fk"];
        $timed = $details[0]["time_fk"];
        $sharp_time = $details[0]["time"];
        $type = $details[0]["type"];
        $rejectionid = $details[0]["rejectionid"];

        /* Nishit code end */
        $phlebo_details = $this->job_model->master_fun_get_tbl_val("phlebo_master", array('id' => $phlebo_id), array("id", "asc"));
        $phlebo_job_details = $this->job_model->master_fun_get_tbl_val("phlebo_assign_job", array('id' => $id), array("id", "asc"));
        $job_details = $this->job_model->master_fun_get_tbl_val("job_master", array('id' => $job_id), array("id", "asc"));
        $notify_cust = $job_details[0]["notify_cust"];
        $customer_details = $this->job_model->master_fun_get_tbl_val("customer_master", array('id' => $job_details[0]['cust_fk']), array("id", "asc"));
        if ($insert) {
            $family_member_name = $this->job_model->get_family_member_name($job_id);

            if (!empty($family_member_name)) {
                $c_name = $family_member_name[0]["name"];
                $cmobile = $family_member_name[0]["phone"];
                if (empty($cmobile)) {
                    $cmobile = $customer_details[0]["mobile"];
                }
            } else {
                $c_name = $customer_details[0]["full_name"];
                $cmobile = $customer_details[0]["mobile"];
            }

            if ($type == '2') {

                $gettest = $this->job_model->get_val("SELECT r.id,t.`test_name`,sr.reason FROM samplereject_jobs r INNER JOIN samplereject_reason sr on sr.id=r.reason_fk INNER JOIN `test_master` t ON t.`id`=r.`test_fk` WHERE r.status='1' AND r.id='$rejectionid'");
                $rejectresone = $gettest[0]["reason"];



                $final_test_sms = $gettest[0]["test_name"];
            } else {
                $job_details = $this->get_job_details($job_id);

                $b_details = array();
                foreach ($job_details[0]["book_test"] as $bkey) {
                    $b_details[] = $bkey["test_name"] . " Rs." . $bkey["price"];
                }
                $sub_test_new = array();
                $package_ary = array();
                foreach ($job_details[0]["book_package"] as $bkey) {
                    $id = $bkey['id'];
                    $query = $this->job_model->get_val("SELECT pt.*,t.id AS TESTID ,t.test_name FROM package_test AS pt LEFT JOIN test_master AS t ON t.id = pt.test_fk  WHERE pt.package_fk='$id' AND pt.status = 1 AND t.`test_name` IS NOT NULL");

                    foreach ($query as $test_new) {
                        $sub_test_new[] = $test_new['test_name'];
                    }
                    //$b_details[] = $bkey["title"] . " Rs." . $bkey["d_price"];
                    $package_ary[] = array("package" => $bkey["title"] . " Rs." . $bkey["d_price"], "test" => $sub_test_new);
                }


                $final_test_sms = implode(",", $b_details);
                $pname = "";
                foreach ($package_ary as $p_key) {
                    $pname .= "," . $p_key["package"];
                    if (!empty($p_key["test"])) {
                        $pname .= "(" . implode(",", $p_key["test"]) . ")";
                    }
                }
                $final_test_sms = $final_test_sms . " " . $pname;
            }

            if ($job_details[0]["payable_amount"] != '') {
                $amount = $job_details[0]["payable_amount"];
            } else {
                $amount = 0;
            }
            $p_time = $this->job_model->master_fun_get_tbl_val("phlebo_time_slot", array('id' => $timed), array("id", "asc"));
            $s_time = date('h:i a', strtotime($p_time[0]["start_time"]));
            $e_time = date('h:i a', strtotime($p_time[0]["end_time"]));
            $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg"), array("id", "asc"));
            $sms_message = preg_replace("/{{NAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message[0]["message"]);
            $sms_message = preg_replace("/{{MOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
            $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
            $sms_message = preg_replace("/{{CNAME}}/", $c_name, $sms_message);
            $sms_message = preg_replace("/{{CMOBILE}}/", $cmobile, $sms_message);
            $sms_message = preg_replace("/{{CADDRESS}}/", $phlebo_job_details[0]["address"], $sms_message);
            $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
            $sms_message = preg_replace("/{{BOOKINFO}}/", $final_test_sms . " Payable amount : Rs." . $amount, $sms_message);
            if (!empty($sharp_time)) {
                $sms_message = preg_replace("/{{TIME}}/", $sharp_time, $sms_message);
            } else {
                $sms_message = preg_replace("/{{TIME}}/", $s_time . " To " . $e_time, $sms_message);
            }

            if ($type == '2') {
                
            } else {
                $job_details = $this->get_job_details($job_id);
                $b_details = array();
                foreach ($job_details[0]["book_test"] as $bkey) {
                    $b_details[] = $bkey["test_name"] . " Rs." . $bkey["price"];
                }
                $sub_test_new = array();
                $package_ary = array();
                foreach ($job_details[0]["book_package"] as $bkey) {
                    $id = $bkey['id'];
                    $query = $this->job_model->get_val("SELECT pt.*,t.id AS TESTID ,t.test_name FROM package_test AS pt LEFT JOIN test_master AS t ON t.id = pt.test_fk  WHERE pt.package_fk='$id' AND pt.status = 1 AND t.`test_name` IS NOT NULL");

                    foreach ($query as $test_new) {
                        $sub_test_new[] = $test_new['test_name'];
                    }
                    //$b_details[] = $bkey["title"] . " Rs." . $bkey["d_price"];
                    $package_ary[] = array("package" => $bkey["title"] . " Rs." . $bkey["d_price"], "test" => $sub_test_new);
                }


                $final_test_sms = implode(",", $b_details);

                $pname = "";
                foreach ($package_ary as $p_key) {
                    $pname .= "," . $p_key["package"];
                    if (!empty($p_key["test"])) {
                        $pname .= "(" . implode(",", $p_key["test"]) . ")";
                    }
                }
                $final_test_sms = $final_test_sms . " " . $pname;
            }
            // $sms_message = preg_replace("/{{BOOKINFO}}/", implode(",", $b_details), $sms_message);

            if ($notify_cust == 1) {
                $mobile = $phlebo_details[0]['mobile'];

                $this->load->helper("sms");
                $notification = new Sms();
                $notification::send($mobile, $sms_message);

                if ($type == '2') {

                    $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "sample_rejection"), array("id", "asc"));
                } else {
                    $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "phlebo_msg_cust"), array("id", "asc"));
                }

                $sms_message = preg_replace("/{{NAME}}/", ucfirst($c_name), $sms_message[0]["message"]);

                $sms_message = preg_replace("/{{MOBILE}}/", $customer_details[0]["mobile"], $sms_message);
                $sms_message = preg_replace("/{{ORDERID}}/", $job_details[0]["order_id"], $sms_message);
                $sms_message = preg_replace("/{{PNAME}}/", ucfirst($phlebo_details[0]["name"]), $sms_message);
                $sms_message = preg_replace("/{{PMOBILE}}/", $phlebo_details[0]["mobile"], $sms_message);
                $sms_message = preg_replace("/{{DATE}}/", $phlebo_job_details[0]["date"], $sms_message);
                $sms_message = preg_replace("/{{BOOKINFO}}/", $final_test_sms . " Payable amount : Rs." . $amount, $sms_message);

                if (!empty($sharp_time)) {
                    $sms_message = preg_replace("/{{TIME}}/", $sharp_time, $sms_message);
                } else {
                    $sms_message = preg_replace("/{{TIME}}/", $s_time . " To " . $e_time, $sms_message);
                }
                if ($type == '2') {
                    $sms_message = preg_replace("/{{resone}}/", $rejectresone, $sms_message);
                }
                // $sms_message = preg_replace("/{{BOOKINFO}}/", implode(",", $b_details) . " Payable amount : Rs." . $amount, $sms_message);

                $mobile = $customer_details[0]['mobile'];
                /* if($_GET["debug"]==1){
                  echo $sms_message; die();
                  } */
                $notification::send($cmobile, $sms_message);

                if (!empty($family_member_name)) {


                    $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $cmobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));

                    $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $customer_details[0]["mobile"], "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                } else {
                    $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $customer_details[0]["mobile"], "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                }
            }
            redirect("job-master/job-details/" . $job_id);
        } else {
            redirect("job-master/pending-list");
        }
    }

    /*     * ********BACKUO***********
      function check_test_parameter_val() {
      $job_id1 = $this->input->get_post("job_id");
      $job_id = explode(",", $job_id1);
      $final_ary = array();
      $cnt = 0;
      if (!empty($job_id)) {
      foreach ($job_id as $jkey) {
      $j_details = $this->job_model->get_val("SELECT * FROM `use_formula` WHERE `job_fk`='" . $jkey . "' and status='1'");
      $ne_ary = array();
      $ne_ary["jid"] = $jkey;
      $ne_ary["details"] = $j_details;
      $final_ary[] = $ne_ary;
      $cnt++;
      }
      echo json_encode(array("status" => "1", "msg" => "", "data" => $final_ary));
      } else {
      echo json_encode(array("status" => "0", "msg" => "Data not available.", "data" => ""));
      }
      }

     * ********OLD BACKUP END****** */

    function check_test_parameter_val() {
        $job_id1 = $this->input->get_post("job_id");
        $job_id = explode(",", $job_id1);
        $final_ary = array();
        $cnt = 0;
        if (!empty($job_id) && !empty($job_id1)) {
            $j_details = $this->job_model->get_val("SELECT * FROM `use_formula` WHERE `job_fk` in (" . $job_id1 . ") and status='1'");
            $nwar = array();
            foreach ($j_details as $nkey) {
                $nwar[] = array("jid" => $nkey["job_fk"], "details" => array($nkey));
            }
            echo json_encode(array("status" => "1", "msg" => "", "data" => $nwar));
        } else {
            echo json_encode(array("status" => "0", "msg" => "Data not available.", "data" => ""));
        }
    }

    /*    function check_test_parameter_val() {
      $job_id1 = $this->input->get_post("job_id");
      $job_id = explode(",", $job_id1);
      $final_ary = array();
      $cnt = 0;
      if (!empty($job_id)) {
      foreach ($job_id as $jkey) {
      $j_details = $this->job_model->get_val("SELECT id,status,date FROM `job_master` WHERE `id`='" . $jkey . "'");
      $final_ary[$cnt]['job_test'] = $jkey;
      $final_ary[$cnt]['status'] = $j_details[0]["status"];
      $job_unique_test = array();
      $job_test = $this->job_model->get_val("SELECT test_fk FROM `job_test_list_master` WHERE `job_fk`='" . $jkey . "'");
      $test_lst = array();
      foreach ($job_test as $t_key) {
      $test_lst[] = $t_key["test_fk"];
      }
      $cnt1 = 0;
      $selected_package = $this->registration_admin_model->get_val("SELECT `book_package_master`.*,`package_master`.`title` FROM `book_package_master` INNER JOIN `package_master` ON `book_package_master`.`package_fk`=`package_master`.`id` WHERE `book_package_master`.`status`='1' AND `package_master`.`status`='1' AND `book_package_master`.`job_fk`='" . $jkey . "'");
      if (!empty($selected_package)) {
      $data["selected_package"] = array();
      foreach ($selected_package as $pkey) {
      $package_test_list = $this->registration_admin_model->get_val("SELECT
      `package_test`.*,
      `test_master`.`test_name`
      FROM
      `package_test`
      INNER JOIN `test_master`
      ON `package_test`.`test_fk` = `test_master`.`id`
      WHERE `package_test`.`status` = '1'
      AND `test_master`.`status` = '1'
      AND `package_test`.`package_fk` = '" . $pkey["package_fk"] . "'");
      $pkey["test_list"] = $package_test_list;
      $data["selected_package"][] = $pkey;
      }
      foreach ($data["selected_package"][0]["test_list"] as $JTkey) {
      if (!in_array($JTkey["test_fk"], $job_unique_test)) {
      $test_outsorce = $this->job_model->get_val("SELECT test_fk,job_fk FROM `user_test_outsource` WHERE status='1' AND job_fk='" . $jkey . "' AND test_fk ='" . $JTkey["test_fk"] . "'");
      $test_approve = $this->job_model->get_val("SELECT approve_job_test.*,`admin_master`.`name` FROM approve_job_test LEFT JOIN `admin_master` ON approve_job_test.`approve_by`=`admin_master`.id WHERE approve_job_test.`status`='1' AND approve_job_test.`job_fk`='" . $jkey . "' AND approve_job_test.`test_fk`='" . $JTkey["test_fk"] . "'");
      $job_details = $this->job_model->get_val("SELECT * FROM user_test_result WHERE `job_id`='" . $jkey . "' AND test_id='" . $JTkey["test_fk"] . "' AND `status`='1'");
      $job_test_list = $this->job_model->get_val("SELECT `test_parameter`.`parameter_fk`
      FROM `test_parameter`
      LEFT JOIN `test_parameter_master`
      ON `test_parameter`.`parameter_fk`=`test_parameter_master`.`id` AND `test_parameter_master`.`status`='1'
      WHERE `test_parameter`.`test_fk`='" . $JTkey["test_fk"] . "' AND `test_parameter`.status='1' AND `test_parameter_master`.`is_group`='0' AND `test_parameter_master`.`parameter_name`!=''");
      $job_test_graph = $this->job_model->get_val("SELECT * from user_formula_pic where status='1' and job_fk='" . $jkey . "' and test_fk='" . $JTkey["test_fk"] . "'");
      $JTkey['graph'] = $job_test_graph;
      $JTkey['parameter'] = $job_test_list;
      $JTkey['result'] = $job_details;
      $JTkey['approve'] = $test_approve;
      $JTkey['outsource'] = $test_outsorce;
      $final_ary[$cnt]['details'][$cnt1] = $JTkey;
      $job_unique_test[] = $JTkey["test_fk"];
      $cnt1++;
      }
      }


      foreach ($data["selected_package"][0]["test_list"] as $JTkey) {
      if (!in_array($JTkey["test_fk"], $job_unique_test)) {
      $test_outsorce = $this->job_model->get_val("SELECT test_fk,job_fk FROM `user_test_outsource` WHERE status='1' AND job_fk='" . $jkey . "' AND test_fk ='" . $JTkey["test_fk"] . "'");
      $test_approve = $this->job_model->get_val("SELECT approve_job_test.*,`admin_master`.`name` FROM approve_job_test LEFT JOIN `admin_master` ON approve_job_test.`approve_by`=`admin_master`.id WHERE approve_job_test.`status`='1' AND approve_job_test.`job_fk`='" . $jkey . "' AND approve_job_test.`test_fk`='" . $JTkey["test_fk"] . "'");
      $job_details = $this->job_model->get_val("SELECT * FROM user_test_result WHERE `job_id`='" . $jkey . "' AND test_id='" . $JTkey["test_fk"] . "' AND `status`='1'");
      $job_test_list = $this->job_model->get_val("SELECT `test_parameter`.`parameter_fk`
      FROM `test_parameter`
      LEFT JOIN `test_parameter_master`
      ON `test_parameter`.`parameter_fk`=`test_parameter_master`.`id` AND `test_parameter_master`.`status`='1'
      WHERE `test_parameter`.`test_fk`='" . $JTkey["test_fk"] . "' AND `test_parameter`.status='1' AND `test_parameter_master`.`is_group`='0' AND `test_parameter_master`.`parameter_name`!=''");
      $job_test_graph = $this->job_model->get_val("SELECT * from user_formula_pic where status='1' and job_fk='" . $jkey . "' and test_fk='" . $JTkey["test_fk"] . "'");
      $JTkey['graph'] = $job_test_graph;
      $JTkey['parameter'] = $job_test_list;
      $JTkey['result'] = $job_details;
      $JTkey['approve'] = $test_approve;
      $JTkey['outsource'] = $test_outsorce;
      $final_ary[$cnt]['details'][$cnt1] = $JTkey;
      $job_unique_test[] = $JTkey["test_fk"];
      $cnt1++;
      }
      }
      }
      if (!empty($test_lst)) {
      $sub_test_list1 = $this->job_model->get_val("SELECT `sub_test_master`.*,`test_master`.`test_name` FROM `sub_test_master` INNER JOIN test_master ON `sub_test_master`.`sub_test`=test_master.`id` WHERE `sub_test_master`.`status`='1' AND `test_master`.`status`='1' AND `sub_test_master`.`test_fk` in (" . implode(",", $test_lst) . ")");
      foreach ($sub_test_list1 as $JTkey) {
      if (!in_array($JTkey["sub_test"], $job_unique_test)) {
      $test_outsorce = $this->job_model->get_val("SELECT test_fk,job_fk FROM `user_test_outsource` WHERE status='1' AND job_fk='" . $jkey . "' AND test_fk ='" . $JTkey["sub_test"] . "'");
      $test_approve = $this->job_model->get_val("SELECT approve_job_test.*,`admin_master`.`name` FROM approve_job_test LEFT JOIN `admin_master` ON approve_job_test.`approve_by`=`admin_master`.id WHERE approve_job_test.`status`='1' AND approve_job_test.`job_fk`='" . $jkey . "' AND approve_job_test.`test_fk`='" . $JTkey["sub_test"] . "'");
      $job_details = $this->job_model->get_val("SELECT * FROM user_test_result WHERE `job_id`='" . $jkey . "' AND test_id='" . $JTkey["sub_test"] . "' AND `status`='1'");
      $job_test_list = $this->job_model->get_val("SELECT `test_parameter`.`parameter_fk`
      FROM `test_parameter`
      LEFT JOIN `test_parameter_master`
      ON `test_parameter`.`parameter_fk`=`test_parameter_master`.`id` AND `test_parameter_master`.`status`='1'
      WHERE `test_parameter`.`test_fk`='" . $JTkey["sub_test"] . "' AND `test_parameter`.status='1' AND `test_parameter_master`.`is_group`='0' AND `test_parameter_master`.`parameter_name`!=''");
      $job_test_graph = $this->job_model->get_val("SELECT * from user_formula_pic where status='1' and job_fk='" . $jkey . "' and test_fk='" . $JTkey["sub_test"] . "'");
      $JTkey['graph'] = $job_test_graph;
      $JTkey['parameter'] = $job_test_list;
      $JTkey['result'] = $job_details;
      $JTkey['approve'] = $test_approve;
      $JTkey['outsource'] = $test_outsorce;
      $JTkey["test_fk"] = $JTkey["sub_test"];
      $final_ary[$cnt]['details'][$cnt1] = $JTkey;
      $job_unique_test[] = $JTkey["sub_test"];
      $cnt1++;
      }
      }
      }
      foreach ($job_test as $JTkey) {
      if (!in_array($JTkey["test_fk"], $job_unique_test)) {
      $test_outsorce = $this->job_model->get_val("SELECT test_fk,job_fk FROM `user_test_outsource` WHERE status='1' AND job_fk='" . $jkey . "' AND test_fk ='" . $JTkey["test_fk"] . "'");
      $test_approve = $this->job_model->get_val("SELECT approve_job_test.*,`admin_master`.`name` FROM approve_job_test LEFT JOIN `admin_master` ON approve_job_test.`approve_by`=`admin_master`.id WHERE approve_job_test.`status`='1' AND approve_job_test.`job_fk`='" . $jkey . "' AND approve_job_test.`test_fk`='" . $JTkey["test_fk"] . "'");
      $job_details = $this->job_model->get_val("SELECT * FROM user_test_result WHERE `job_id`='" . $jkey . "' AND test_id='" . $JTkey["test_fk"] . "' AND `status`='1'");
      $job_test_list = $this->job_model->get_val("SELECT `test_parameter`.`parameter_fk`
      FROM `test_parameter`
      LEFT JOIN `test_parameter_master`
      ON `test_parameter`.`parameter_fk`=`test_parameter_master`.`id` AND `test_parameter_master`.`status`='1'
      WHERE `test_parameter`.`test_fk`='" . $JTkey["test_fk"] . "' AND `test_parameter`.status='1' AND `test_parameter_master`.`is_group`='0' AND `test_parameter_master`.`parameter_name`!=''");
      $job_test_graph = $this->job_model->get_val("SELECT * from user_formula_pic where status='1' and job_fk='" . $jkey . "' and test_fk='" . $JTkey["test_fk"] . "'");
      $JTkey['graph'] = $job_test_graph;
      $JTkey['parameter'] = $job_test_list;
      $JTkey['result'] = $job_details;
      $JTkey['approve'] = $test_approve;
      $JTkey['outsource'] = $test_outsorce;
      $final_ary[$cnt]['details'][$cnt1] = $JTkey;
      $job_unique_test[] = $JTkey["test_fk"];
      $cnt1++;
      }
      }

      $cnt++;
      }
      echo json_encode(array("status" => "1", "msg" => "", "data" => $final_ary));
      } else {
      echo json_encode(array("status" => "0", "msg" => "Data not available.", "data" => ""));
      }
      } */

    function delete_approved() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $jid = $this->uri->segment(3);
        $tid = $this->uri->segment(4);
        if ($jid != '' && $tid != '') {
            $this->job_model->master_fun_update_multi("approve_job_test", array("job_fk" => $jid, "test_fk" => $tid), array("status" => "0"));
            $this->job_model->master_fun_update_multi("use_formula", array("job_fk" => $jid, "test_fk" => $tid), array("test_status" => "1"));
            //$this->job_model->master_fun_insert("job_log", array("job_fk" => $jid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "25", "date_time" => date("Y-m-d H:i:s")));
            redirect("job-master/job-details/" . $jid);
        } else {
            redirect("job-master/pending-list");
        }
    }

    /*    function generate_barcode() {
      $jid = $this->uri->segment(3);
      if (!empty($jid)) {
      $j_data = $this->job_model->master_fun_get_tbl_val("job_master", array('id' => $jid), array("id", "asc"));
      if (empty($j_data[0]["barcode"])) {
      $b_data = $this->job_model->master_fun_get_tbl_val("branch_master", array('id' => $j_data[0]["branch_fk"]), array("id", "asc"));
      $barcode = $j_data[0]["test_city"] . $b_data[0]["branch_code"] . $jid;
      $this->job_model->master_fun_update_multi("job_master", array("id" => $jid), array("barcode" => $barcode));
      } else {
      $barcode = $j_data[0]["barcode"];
      }
      echo $barcode;
      }
      } */

    function approve_all_test() {
        $data["login_data"] = logindata();
        $id = $this->input->post("id");
        $jid = $this->input->get("jid");
        $id_array = explode(",", $id);
        $this->job_model->master_fun_update_multi("approve_job_test", array("job_fk" => $jid), array("status" => "0"));
        foreach ($id_array as $key) {
            $data12 = array(
                "job_fk" => $jid,
                "test_fk" => $key,
                "approve_by" => $data["login_data"]["id"],
                "created_date" => date("Y-m-d H:i:s"),
                "status" => 1
            );
            $val_add = $this->job_model->master_fun_insert("approve_job_test", $data12);
        }
        redirect("job-master/job-details/" . $jid);
    }

    /* function get_barcode() {
      $jid = $this->input->get("jid");
      $val_add = $this->job_model->get_val("select barcode from job_master where id='" . $jid . "'");
      echo json_encode($val_add);
      } */

    function get_barcode() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();

        $jid = $this->input->get("jid");
        $val_add = $this->job_model->get_val("select barcode,branch_fk from job_master where id='" . $jid . "'");


        $id = $this->input->get("test_ids");
        $test_ids = rtrim($id, ',');
        if ($test_ids != "") {
            $test_temp = explode(',', $test_ids);

            $test_list = $this->job_model->get_val("select t.id,t.test_name,t.sample as sample_type from test_master t left join branch_sample_type bt on bt.test_fk=t.id AND bt.status='1' and bt.branch_fk='" . $val_add[0]["branch_fk"] . "' where t.id IN ($test_ids) AND t.id NOT IN(select test_fk from test_sample_barcode where status!='0' AND job_fk ='$jid')");
        } else {
            $test_list = array();
        }

        $testarray = array();
        foreach ($test_list as $testdata) {

            $subtestlist = $this->job_model->get_val("SELECT t.id,t.test_name,bt.sample_type FROM `sub_test_master` s INNER JOIN test_master t ON t.id=s.`sub_test` LEFT JOIN branch_sample_type bt ON bt.test_fk=t.id AND bt.status='1' AND  bt.branch_fk='" . $val_add[0]["branch_fk"] . "'  WHERE s.status='1' AND s.test_fk='" . $testdata["id"] . "' AND t.id NOT IN(SELECT test_fk FROM test_sample_barcode WHERE STATUS!='0' AND job_fk ='$jid')");
            if ($subtestlist != null && $subtestlist != "") {

                foreach ($subtestlist as $subtestarray) {
                    $testarray[] = $subtestarray;
                }
            } else {
                $testarray[] = $testdata;
            }
        }

        $pack_id = $this->input->get("package_ids");
        if ($pack_id != "") {
            $package_ids = rtrim($pack_id, ',');
            $package_list = $this->job_model->get_val("select t.id,t.test_name,bt.sample_type from test_master t left join branch_sample_type bt on bt.test_fk=t.id and bt.branch_fk='" . $val_add[0]["branch_fk"] . "' where t.id IN ($package_ids) AND t.id NOT IN(select test_fk from test_sample_barcode where status!='0' AND job_fk ='$jid')");
        } else {
            $package_list = array();
        }

        $already_test = $this->job_model->get_val("select test_fk from test_sample_barcode where job_fk= $jid AND status = '1'");
        $final_test = [];
        if ($pack_id != "") {
            $final_test[] = array_unique(array_merge($testarray, $package_list), SORT_REGULAR);
        } else {
            $final_test[] = $testarray;
        }

        echo json_encode(array("barcode" => $val_add, "test_list" => $testarray, "package_list" => $package_list, "final_test" => $final_test));
        exit;
    }

    function update_barcode() {
        $jid = $this->input->get("jid");
        $barcode = $this->input->get("b_barcode");
        if ($jid != '' && $barcode != '') {
            $this->job_model->master_fun_update("job_master", array("id", $jid), array("barcode" => $barcode));
            echo "1";
        } else {
            echo "0";
        }
    }

    function outsource() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->library("Util");
        $util = new Util();
        $search_data = array();
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cntr_arry = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data["login_data"]['branch_fk'] as $key) {
                $cntr_arry[] = $key["branch_fk"];
            }
        }
        $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status='1'");
        $search_data['cntr_arry'] = $cntr_arry;
        $data['query'] = $this->job_model->outsource_job_list($search_data, $config["per_page"], $page);
        $cnt = 0;
        foreach ($data['query'] as $key) {
            $w_prc = 0;
            /* Count booked test price */
            $booked_tests = $this->job_model->master_fun_get_tbl_val("wallet_master", array('job_fk' => $key["id"]), array("id", "asc"));
            $emergency_tests = $this->job_model->master_fun_get_tbl_val("booking_info", array('id' => $key["booking_info"]), array("id", "asc"));
            $f_data = $this->job_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
            $f_data1 = $this->job_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
            $doctor_data = $this->job_model->master_fun_get_tbl_val_with_select("full_name,mobile", "doctor_master", array('id' => $key["doctor"]), array("id", "asc"));
            $relation = "Self";
            if (!empty($f_data1)) {
                $relation = ucfirst($f_data[0]["name"] . " (" . $f_data1[0]["name"] . ")");
                $data['query'][$cnt]["rphone"] = $f_data[0]["phone"];
            }
            $data['query'][$cnt]["relation"] = $relation;
            if (!empty($booked_tests)) {
                foreach ($booked_tests as $tkey) {
                    if ($tkey["debit"]) {
                        $w_prc = $w_prc + $tkey["debit"];
                    }
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
            }
            // pinkesh code end
            $upload_data = $this->job_model->master_fun_get_tbl_val("report_master", array('job_fk' => $key["id"]), array("id", "asc"));
            $job_test_list = $this->job_model->get_val("SELECT `job_test_list_master`.*,`test_master`.`test_name` FROM `job_test_list_master` INNER JOIN `test_master` ON `job_test_list_master`.`test_fk`=`test_master`.`id` join user_test_outsource os on os.test_fk=`job_test_list_master`.`test_fk` WHERE `job_test_list_master`.`job_fk`='" . $key["id"] . "' and os.status='1' and os.job_fk = '" . $key["id"] . "'");
            $data['query'][0]["job_test_list"]['outsource'] = $this->job_model->get_val("SELECT test_fk,outsource_fk FROM `user_test_outsource` WHERE status='1' AND job_fk='" . $key["id"] . "'");
            $data['query'][0]['outsource_test'] = array();
            foreach ($data['query'][0]["job_test_list"]['outsource'] as $out) {
                array_push($data['query'][0]['outsource_test'], $out['test_fk']);
            }
            $data['query'][$cnt]["job_test_list"] = $job_test_list;
            $data['query'][$cnt]["report"] = $upload_data[0]["original"];
            $data['query'][$cnt]["emergency"] = $emergency_tests[0]["emergency"];
            $data['query'][$cnt]["cut_from_wallet"] = $w_prc;
            $data['query'][$cnt]["doctor_name"] = $doctor_data[0]["full_name"];
            $data['query'][$cnt]["doctor_mobile"] = $doctor_data[0]["mobile"];
            $package_ids = $this->job_model->get_job_booking_package($key["id"]);
            if (trim($package_ids) != '') {
                $data['query'][$cnt]["packagename"] = $package_ids;
            }
            $cnt++;
        }
        $url = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        $this->session->set_userdata("job_master_r", $url);
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('outsource_job_list', $data);
        $this->load->view('footer');
    }

    function delete_approved_outsource() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $jid = $this->uri->segment(3);
        $tid = $this->uri->segment(4);
        if ($jid != '' && $tid != '') {
            $this->job_model->master_fun_update_multi("approve_job_test", array("job_fk" => $jid, "test_fk" => $tid), array("status" => "0"));
            //$this->job_model->master_fun_insert("job_log", array("job_fk" => $jid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "25", "date_time" => date("Y-m-d H:i:s")));
            redirect("job_master/outsource");
        } else {
            redirect("job_master/outsource");
        }
    }

    function creditors_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $job = $this->input->post("job_id");
        $creditor = $this->input->post("creditor_id");
        $amount = $this->input->post("amount");
        $otp = rand(1111, 9999);
        $creditor_data = $this->job_model->master_fun_get_tbl_val("creditors_master", array('id' => $creditor), array("id", "asc"));
        $data12 = array(
            "job_fk" => $job,
            "creditors_fk" => $creditor,
            "amount" => $amount,
            "added_by" => $data["login_data"]["id"],
            "created_date" => date("Y-m-d H:i:s"),
            "status" => 2,
            "otp" => $otp
        );
        /* Nishit Send sms start */
        $this->load->helper("sms");
        $notification = new Sms();
        $mobile = $creditor_data[0]['mobile'];
        $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "creditor_verify"), array("id", "asc"));
        $sms_message = preg_replace("/{{OTP}}/", $otp, $sms_message[0]["message"]);
        $sms_message = preg_replace("/{{NAME}}/", $creditor_data[0]['name'], $sms_message);
        $sms_message = preg_replace("/{{AMOUNT}}/", $amount, $sms_message);
        $insert = $this->job_model->master_fun_insert("admin_alert_sms", array('mobile_no' => $mobile, 'message' => $sms_message));
        /* $notification->send($mobile, $sms_message); */
        /* Nishit Send sms end */
        $row = $this->job_model->master_num_rows("job_creditors", array("job_fk" => $job, "creditors_fk" => $creditor));
        if ($row == 1) {
            $this->job_model->master_fun_update_multi("job_creditors", array("creditors_fk" => $creditor, "job_fk" => $job), array("status" => 2, "otp" => $otp, "creditors_fk" => $creditor, "amount" => $amount, "updated_by" => $data["login_data"]["id"],));
        } else {
            $insert = $this->job_model->master_fun_insert("job_creditors", $data12);
        }
    }

    function creditors_resend() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $job = $this->input->post("job_id");
        $creditor = $this->input->post("creditor_id");
        $amount = $this->input->post("amount");
        $otp = rand(1111, 9999);
        $creditor_data = $this->job_model->master_fun_get_tbl_val("creditors_master", array('id' => $creditor), array("id", "asc"));
        /* Nishit Send sms start */
        $this->load->helper("sms");
        $notification = new Sms();
        $mobile = $creditor_data[0]['mobile'];
        $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "creditor_verify"), array("id", "asc"));
        $sms_message = preg_replace("/{{OTP}}/", $otp, $sms_message[0]["message"]);
        $sms_message = preg_replace("/{{NAME}}/", $creditor_data[0]['name'], $sms_message);
        $sms_message = preg_replace("/{{AMOUNT}}/", $amount, $sms_message);
        $notification->send($mobile, $sms_message);
        /* Nishit Send sms end */
        $this->job_model->master_fun_update_multi("job_creditors", array("creditors_fk" => $creditor, "job_fk" => $job, "status" => '2'), array("otp" => $otp));
    }

    function creditor_check_otp() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $otp = $this->input->post("otp");
        $creditor = $this->input->post("creditor_fk");
        $job = $this->input->post("job");
        $amount = $this->input->post("amount");
        $row = $this->job_model->master_num_rows("job_creditors", array("job_fk" => $job, "creditors_fk" => $creditor, "otp" => $otp));
        if ($row == 1 || ($otp == "98989812")) {

            $this->job_model->master_fun_update_multi("job_creditors", array("creditors_fk" => $creditor, "job_fk" => $job, "status" => '2'), array("status" => 1, "otp" => ''));
            $creditor_data = $this->job_model->master_fun_get_tbl_val("creditors_master", array('id' => $creditor), array("id", "asc"));
            $this->job_model->master_fun_insert("job_master_receiv_amount", array("job_fk" => $job, "amount" => $amount, "createddate" => date("Y-m-d H:i:s"), "added_by" => $data["login_data"]['id'], "type" => "credit", "remark" => "credited by " . $creditor_data[0]['name'], "payment_type" => "CREDITORS"));
            $insert = $this->job_model->master_fun_insert("creditors_balance", array("creditors_fk" => $creditor, "debit" => $amount, "created_date" => date("Y-m-d H:i:s"), "created_by" => $data["login_data"]['id'], "job_id" => $job));
            $job_details = $this->job_model->master_fun_get_tbl_val("job_master", array("id" => $job), array("id", "asc"));
            $payable_price = $job_details[0]["payable_amount"];
            $remaining_amount = $payable_price - $amount;
            $this->job_model->master_fun_update("job_master", array("id", $job), array("payable_amount" => $remaining_amount));
            $this->job_model->master_fun_insert("job_log", array("job_fk" => $job, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "22", "date_time" => date("Y-m-d H:i:s")));
            $this->check_send_report($job);
            echo json_encode(array("status" => "1", "msg" => "Verified"));
        } else {
            echo json_encode(array("status" => "0", "msg" => "Invalid OTP."));
        }
    }

    function send_nishit_sms() {

        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $this->load->helper("sms");
        $notification = new Sms();
        $mobile = array("9879572294", "9979774646", "9879111678");
        $sms_message = "Patient Name:  UMA GUPTA (AHM-10041) 

 MP BY QBC 
MP BY QBC :- Negative
 URINE ROUTINE EXAMINATION 
Volume :-  Adequate 
 Colour  :-  Pale Yellow 
 Appearance :-  Clear 
 Reaction :-  Acidic 
 Sp. Gravity :-  1.015 
 Protein :-  Nil 
 Glucose  :-  Nil 
 Bile Salts :-  Absent 
 Bile Pigments :-  Absent 
 Pus Cells :- 30-35Red Cells :- 2-3Epithelial Cells :- 1-2Casts :-  Absent 
 Crystals :-  Absent 
 Fungus :-  Absent .";
        // $notification->send("9879572294", $sms_message);
        $notification->send("9426065145", $sms_message);
        //$notification->send("9879111678", $sms_message);
        //foreach ($mobile as $key) {
        //     $notification->send($key, $sms_message);
        // }

        echo "done";
    }

    function prescriptionupdate() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->model('job_model');
        $this->load->model('job_model');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('cust_id', 'cust_id', 'trim|required');
        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('phone', 'phone', 'trim|required');
        $this->form_validation->set_rules('test_city', 'test_city', 'trim|required');
        $this->form_validation->set_rules('prescription', 'prescription', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $email = $this->input->post("email");
            $name = $this->input->post("name");
            $phone = $this->input->post("phone");
            $test_city = $this->input->post("test_city");
            $gender = $this->input->post("gender");
            $cust_id = $this->input->post("cust_id");
            $prescription = $this->input->post("prescription");
            $description = $this->input->post("description");
            $address = $this->input->post("address");
            $this->job_model->master_fun_update("customer_master", array("id", $cust_id), array("full_name" => $name, "gender" => $gender, "email" => $email, "mobile" => $phone, "address" => $address, "test_city" => $test_city));
            $this->job_model->master_fun_update("prescription_upload", array("id", $prescription), array("description" => $description));
            echo "1";
        } else {
            echo "0";
        }
    }

    function getjobs_bookedpack() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $jobs = $this->input->get("jobs");
        if ($jobs != "") {
            $job_test_list = $this->job_model->get_val("SELECT t.test_name,a.test_fk AS testid,a.`new_page` FROM  `approve_job_test` a LEFT JOIN test_master t ON t.`id`=a.`test_fk` WHERE a.status='1' AND a.job_fk='$jobs' GROUP BY a.`id` ORDER BY a.position ASC");
            ?><table class="table table-striped" id="sort" >
                <tbody id="">
                    <?php
                    foreach ($job_test_list as $test) {
                        if ($test['testid'] != "") {
                            ?>
                            <tr>
                                <td><input type="hidden" name="testid[]" value="<?= $test['testid']; ?>" ></td>
                                <td><?= $test['test_name']; ?></td>
                                <td>
                                    <input type="hidden" id="checkvalue_<?= $test['testid'] ?>" name="on_new_page[]" value="<?php
                                    if ($test['new_page'] == 1) {
                                        echo "1";
                                    }
                                    ?>" />
                                    <input class="newadd" <?php
                                    if ($test['new_page'] == 1) {
                                        echo "checked";
                                    }
                                    ?> id="check_<?= $test['testid'] ?>" value="1" type="checkbox"> On New Page? </td>
                                <td>
                                    <a href="javascript:void(0)" class="up"><i class="fa fa-arrow-up"></i></a>
                                    <a href="javascript:void(0)" class="down"><i class="fa fa-arrow-down"></i></a>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table><?php
        } else {
            echo "0";
        }
    }

    function getjobs_bookedprint() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $jobs = $this->input->get("jobs");
        if ($jobs != "") {
            $job_test_list = $this->job_model->get_val("SELECT t.test_name,a.test_fk AS testid,a.`new_page` FROM  `approve_job_test` a LEFT JOIN test_master t ON t.`id`=a.`test_fk` WHERE a.status='1' AND a.job_fk='$jobs' GROUP BY a.`id` ORDER BY a.position ASC");
            ?><table class="table table-striped" id="sort" >
                <tbody id="">
                    <?php
                    $i = 0;
                    foreach ($job_test_list as $test) {
                        if ($test['testid'] != "") {
                            $i++
                            ?>
                            <tr>

                                <td><input class="alltest" checked id="" value="<?= $test['testid'] . "_" . $i; ?>" name="testall[]" type="checkbox"></td>
                                <td><?= $test['test_name']; ?></td>
                                <td>
                                    <input type="hidden" id="printvalue_<?= $test['testid'] ?>" name="on_new_page[]" value="<?php
                                    if ($test['new_page'] == 1) {
                                        echo "1";
                                    }
                                    ?>" />
                                    <input class="printnewadd" <?php
                                    if ($test['new_page'] == 1) {
                                        echo "checked";
                                    }
                                    ?> id="check_<?= $test['testid'] ?>" value="1" type="checkbox"> On New Page? </td>
                                <td>
                                    <a href="javascript:void(0)" class="up"><i class="fa fa-arrow-up"></i></a>
                                    <a href="javascript:void(0)" class="down"><i class="fa fa-arrow-down"></i></a>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table><?php
        } else {
            echo "0";
        }
    }

    function testprint_downloade($file1) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $file = urldecode($file1); // Decode URL-encoded string
        $filename = FCPATH . "/upload/Reportprint/" . $file;
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false); // required for certain browsers 
        header('Content-Type: application/pdf');
//        header('Content-Disposition: attachment; filename="' . basename($filename) . '";');
        header('Content-Disposition: inline="' . basename($filename) . '";');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($filename));

        readfile($filename);

        exit;
    }

    function get_doctor_test_list($city_fk = 1) {
        $city_fk = $this->uri->segment(3);
        $branch_fk = $this->uri->segment(4);
        $doctor_fk = $this->uri->segment(5);
        $doc_discount_check = $this->job_model->get_val("SELECT * FROM `doctor_master` WHERE `status`='1' AND id='" . $doctor_fk . "'");
        $doc_tst = $this->job_model->get_val("SELECT * FROM `lab_doc_discount` WHERE `status`='1' AND doc_fk='" . $doctor_fk . "' AND lab_fk='" . $branch_fk . "'");
        //print_r($doc_tst); die();

        $discount_test = array();
        if (!empty($doc_tst)) {
            foreach ($doc_tst as $dtkey) {
                $discount_test[] = $dtkey['test_fk'];
            }
        }
        //print_r($discount_test); die();
        if ($branch_fk > 0) {
            $test = $this->job_model->get_val("select IF(cut>0,cut,0) as cut,jobdiscount from branch_master where id='" . $this->uri->segment(4) . "'");
            $cut = $test[0]["cut"];
            $discount = $test[0]["jobdiscount"];
        } else {
            $cut = 0;
            $discount = 1;
        }

        if ($doc_discount_check[0]['discount'] > 0) {
            $test = $this->job_model->get_val("select IF(cut>0,cut,0) as cut,jobdiscount from branch_master where id='" . $this->uri->segment(4) . "'");
            $cut = 0;
            $discount = $doc_discount_check[0]['discount'];
        } else {
            $cut = 0;
            $discount = 1;
        }

        $selected = $this->input->get_post("selected");

        $selected_test = array();
        $selected_package = array();
        foreach ($selected as $key) {
            $a = explode("-", $key);
            if ($a[0] == 'p') {
                $selected_package[] = $a[1];
            } else {
                $selected_test[] = $a[1];
            }
        }

        $test = $this->job_model->get_val("SELECT 
  test_master.`id`,
  `test_master`.`TEST_CODE`,
  `test_master`.`test_name`,
  `test_master`.`test_name`,
  `test_master`.`PRINTING_NAME`,
  `test_master`.`description`,
  `test_master`.`SECTION_CODE`,
  `test_master`.`LAB_COST`,
  `test_master`.`status`,
  `test_master_city_price`.`price`,
  t_tst AS sub_test,
  lab_doc_discount.`price` AS d_price 
FROM
  `test_master` 
  INNER JOIN `test_master_city_price` 
    ON `test_master`.`id` = `test_master_city_price`.`test_fk` 
  LEFT JOIN 
    (SELECT 
      GROUP_CONCAT(tmm.`test_name` SEPARATOR '%@%') AS t_tst,
      tm.`id` 
    FROM
      `sub_test_master` 
      LEFT JOIN `test_master` tm 
        ON `sub_test_master`.`test_fk` = tm.`id` 
      LEFT JOIN test_master tmm 
        ON `sub_test_master`.`sub_test` = tmm.id 
    WHERE `sub_test_master`.`status` = '1' 
    GROUP BY tm.`id`) AS tst 
    ON tst.id = `test_master`.`id` 
    LEFT JOIN `lab_doc_discount` ON `lab_doc_discount`.`test_fk`=`test_master`.`id` and lab_doc_discount.lab_fk='" . $branch_fk . "' and lab_doc_discount.doc_fk='" . $doctor_fk . "' and lab_doc_discount.status='1'
WHERE `test_master`.`status` = '1' 
  AND `test_master_city_price`.`status` = '1' 
  AND `test_master_city_price`.`city_fk` = '" . $city_fk . "' 
GROUP BY `test_master`.`id`");

        $package = $this->job_model->get_val("SELECT 
              `package_master`.*,
              `package_master_city_price`.`a_price` AS `a_price1`,
              `package_master_city_price`.`d_price` AS `d_price1`
              FROM
              `package_master`
              INNER JOIN `package_master_city_price`
              ON `package_master`.`id` = `package_master_city_price`.`package_fk`
              WHERE `package_master`.`status` = '1'
              AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '$city_fk' ");
        $test_list = '<option value="">--Select Test--</option>';
//        $test_list = '<select class="chosen-select" data-live-search="true" id="test" data-placeholder="Select Test" onchange="get_test_price();">
//			<option value="">--Select Test--</option>';
        foreach ($test as $ts) {
            if (!in_array($ts['id'], $selected_test)) {
                $is_discount = 0;
                if ($ts['d_price'] > 0) {
                    $ts['price'] = $ts['d_price'];
                    $is_discount = 1;
                }
                if ($cut > 0 && $is_discount == 0) {
                    $new_price = $ts['price'] - ($cut * $ts['price'] / 100);
                } else {
                    $new_price = $ts['price'];
                }
                if ($discount > 1 && $is_discount == 0) {
                    $new_price = $ts['price'] - ($discount * $ts['price'] / 100);
                } else {
                    $new_price = $ts['price'];
                }
                //echo $new_price; die();
                $new_price = round($new_price);
                $test_list .= ' <option value="t-' . $ts['id'] . '">' . ucfirst($ts['test_name']) . ' (Rs.' . $new_price . ')</option>';
            }
        }
        foreach ($package as $pk) {
            if (!in_array($pk['id'], $selected_package)) {
                $test_list .= '<option value="p-' . $pk['id'] . '">' . ucfirst($pk['title']) . ' (Rs.' . $pk['d_price1'] . ')</option>';
            }
        }
        //$test_list .= '</select>';

        echo json_encode(array("refer" => $refer, "test_list" => $test_list, "customer" => $customer_data, "test_ary" => $test, "discount" => $discount));
    }

    function get_customer_pres() {
        $term = $this->input->get_post('key');

        $query = $this->job_model->get_val("select * from customer_master where status='1' and full_name like '%$term%' ");

        $json_array = array();
        if ($query != null) {

            foreach ($query as $row) {
                $json_array[] = ucwords($row['full_name']);
            }
            echo json_encode($json_array);
        }
    }

    function update_register_date($cid) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        /* $cid = $this->uri->segment('3'); */
        $this->form_validation->set_rules("date_time", "Date", 'trim|required');
        if ($this->form_validation->run() != False) {
            $date_new = $this->input->post('date_time');
            $data['query'] = $this->job_model->master_fun_update("job_master", array("id", $cid), array("date" => $date_new));
            $this->session->set_flashdata("success", array("Registration Date & Time Successfully Updated."));
        }
        redirect("job-master/job-details/" . $cid, "refresh");
    }

    function getbranch_job_details($job_id) {
        $job_details = $this->job_model->master_fun_get_tbl_val("job_master", array("status !=" => "0", "id" => $job_id), array("id", "asc"));
        if (!empty($job_details)) {
            $book_test = $this->job_model->master_fun_get_tbl_val("job_test_list_master", array("job_fk" => $job_id), array("id", "desc"));
            $book_package = $this->job_model->master_fun_get_tbl_val("book_package_master", array("job_fk" => $job_id, "status" => "1"), array("id", "desc"));
            $test_name = array();
            foreach ($book_test as $key) {

                $price1 = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`booked_job_test`.`price`,`booked_job_test`.`panel_fk` FROM `booked_job_test` LEFT JOIN    `test_master` ON `test_master`.`id`=SUBSTRING_INDEX(`booked_job_test`.`test_fk`, '-', -1)  WHERE `test_master`.`status` = '1'   AND `booked_job_test`.`status` = '1'  AND `test_master`.`id` ='" . $key["test_fk"] . "'  AND booked_job_test.`job_fk`='" . $job_id . "'");
                if (!empty($price1[0])) {
                    $test_name[] = $price1[0];
                }
            }
            $job_details[0]["book_test"] = $test_name;
            $package_name = array();
            foreach ($book_package as $key) {

                $price1 = $this->job_model->get_val("SELECT package_master.*,`booked_job_test`.`price` as d_price,`booked_job_test`.`panel_fk` FROM `booked_job_test` LEFT JOIN package_master ON package_master.`id`=SUBSTRING_INDEX(`booked_job_test`.`test_fk`, '-', -1)  WHERE package_master.`status` = '1'   AND `booked_job_test`.`status` = '1'  AND package_master.`id` ='" . $key["package_fk"] . "'  AND booked_job_test.`job_fk`='" . $job_id . "'");

                $package_name[] = $price1[0];
            }
            $job_details[0]["book_package"] = $package_name;
        }
        return $job_details;
    }

    function get_barcode_data() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $job_fk = $this->input->get("jid");
        $status = $this->input->get("status");

        $jobdtils = $this->job_model->get_val("SELECT `branch_fk` FROM `job_master` WHERE status != '0' AND id='$job_fk'");
        $branch = $jobdtils[0]["branch_fk"];
        if ($branch == "" || $branch == null) {
            $branch = 0;
        }

        $jobapprovetest = $this->job_model->get_val("SELECT GROUP_CONCAT(`test_fk`) AS testid FROM approve_job_test WHERE STATUS='1' AND `job_fk`='$job_fk' GROUP BY job_fk");

        $jobapproveaalltest = array();
        if ($jobapprovetest[0]["testid"] != "") {
            $jobapproveaalltest = explode(",", $jobapprovetest[0]["testid"]);
        }

        $data1 = $this->job_model->get_val("
                select ts.test_fk,ts.barcode,ts.sample_collection,tm.test_name,tm.sample as sample_type
                from test_sample_barcode ts
                INNER JOIN test_master tm on tm.id = ts.test_fk
				left join branch_sample_type bt on bt.test_fk=tm.id and bt.branch_fk='$branch' and bt.status='1'  where ts.job_fk = '$job_fk' AND ts.status ='1' group by tm.id");


        $temp = "";
        $temp1 = "";

        foreach ($data1 as $d) {
            $test_fk = $d['test_fk'];
            $test_name = $d['test_name'];
            if ($d['barcode'] == "") {
                $barcode = "N/A";
                $printbarcode = "-";
            } else {
                $barcode = $d['barcode'];
                $printbarcode = '<a  class ="btn btn-primary btn-xs" href = "' . base_url() . 'index.php/utility/getJobBarcode_new?id=' . $job_fk . '&test_id=' . $test_fk . '" target ="_blank" id ="b_print' . $test_fk . '" ><i class ="fa fa-print"></i> Print</a>';
            }

            $checked = "";
            $checksample = "No";
            if ($d['sample_collection'] == '1') {
                $checked = "checked";
                $checksample = "Yes";
            }

            if ($d['sample_type'] == "") {
                $sample_type = "N/A";
            } else {
                $sample_type = $d['sample_type'];
            }

            $temp .= '<tr id="' . $test_fk . '">
                    <td>' . $test_name . '</td><td>' . $sample_type . '</td>
                    <td>' . $barcode . ' <input type="hidden" value = "' . $barcode . '" class="price" id="price_' . $barcode . '"></td>
                    <td>' . $printbarcode . '</td>';
            if (in_array($status, array(7, 8)) && !in_array($test_fk, $jobapproveaalltest)) {
                $temp .= '<td><input class="change_sample" type="checkbox" ' . $checked . ' testde="' . $test_fk . '" job_fk = "' . $job_fk . '" href = "javascript:void(0);"></td><td><a class="deletecityprice" testde="' . $test_fk . '" testtype="' . $sample_type . '" testdename="' . $test_name . '"  job_fk = "' . $job_fk . '" href = "javascript:void(0);"  ><span class ="glyphicon glyphicon-trash"></span></a></td>';
            } else {
                $temp .= '<td><span style="display:none"><input class="change_sample" type="checkbox" ' . $checked . ' testde="' . $test_fk . '" job_fk = "' . $job_fk . '" href = "javascript:void(0);"></span>' . $checksample . '</td><td>-</td>';
            }

            $temp .= '</tr>';
        }
        echo $temp;
    }

    function update_sample_collection() {
        $test_id = $this->input->get("test_id");
        $job_fk = $this->input->get("job_fk");
        if ($job_fk != '' && $test_id != '') {
            $prev_val = $this->job_model->get_val("select sample_collection from test_sample_barcode where status = '1' AND job_fk = '$job_fk' AND test_fk='$test_id'");
            if ($prev_val[0]['sample_collection'] == '0') {

                $this->job_model->master_fun_update_multi("test_sample_barcode", array("job_fk" => $job_fk, "test_fk" => $test_id), array("sample_collection" => '1', "updated_date" => date('Y-m-d H:i:s')));

                $this->job_model->master_fun_update_multi("samplereject_jobs", array("job_fk" => $job_fk, "test_fk" => $test_id, "status" => '1', "complete" => '0'), array("complete" => '1'));
            } else {
                $this->job_model->master_fun_update_multi("test_sample_barcode", array("job_fk" => $job_fk, "test_fk" => $test_id), array("sample_collection" => '0', "updated_date" => date('Y-m-d H:i:s')));

                $this->job_model->master_fun_update_multi("samplereject_jobs", array("job_fk" => $job_fk, "test_fk" => $test_id, "status" => '1', "complete" => '1'), array("complete" => '0'));
            }

            echo "1";
        } else {
            echo "0";
        }
    }

    function add_barcode() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();

        $test_id = $this->input->get("test_id");
        $barcode = $this->input->get("barcode");
        $job_fk = $this->input->get("job_fk");
        $branch_fk = $this->input->get("branch_fk");

        $data1 = ['test_fk' => $test_id,
            'barcode' => $barcode,
            'job_fk' => $job_fk,
            'branch_fk' => $branch_fk,
            'sample_collection' => '1',
            'status' => '1',
            'created_by' => $data["login_data"]['id'],
            'created_date' => date('Y-m-d H:i:s'),
            'updated_date' => date('Y-m-d H:i:s')
        ];

        if ($job_fk != '' && $test_id != '') {
            $insert = $this->job_model->master_fun_insert("test_sample_barcode", $data1);
            echo "1";
        } else {
            echo "0";
        }
    }

    function delete_barcode() {
        $test_id = $this->input->get("test_id");
        $job_fk = $this->input->get("job_fk");
        if ($job_fk != '' && $test_id != '') {
            $this->job_model->master_fun_update_multi("test_sample_barcode", array("job_fk" => $job_fk, "test_fk" => $test_id), array("status" => '0'));
            echo "1";
        } else {
            echo "0";
        }
    }

    function add_sample_collect_date($jid) {

        $date1 = $this->job_model->get_val("SELECT created_date FROM `test_sample_barcode` WHERE job_fk='$jid' AND status='1'");
        $time = $date1[0]['created_date'];

        $time1 = explode(" ", $time);
        $time2 = $time1[1];

        $date = $this->input->post("date");
        $this->job_model->master_fun_update_multi("test_sample_barcode", array("job_fk" => $jid), array("created_date" => "$date $time2"));

        $this->job_model->master_fun_update_multi("job_log", array("job_fk" => $jid, "message_fk" => "3", "job_status" => "6-7"), array("date_time" => "$date $time2"));
        redirect("job-master/job-details/" . $jid);
    }

    function add_sample_collect_time($jid) {
        $date1 = $this->job_model->get_val("SELECT created_date FROM `test_sample_barcode` WHERE job_fk='$jid' AND status='1'");
        $date = $date1[0]['created_date'];

        $date1 = explode(" ", $date);
        $date2 = $date1[0];
        //echo $date1[0]; exit;
        $in_time = date("H:i:s", strtotime(str_replace(' ', '', $this->input->post('in_time'))));
        $this->job_model->master_fun_update_multi("test_sample_barcode", array("job_fk" => $jid), array("created_date" => "$date2 $in_time"));
        $this->job_model->master_fun_update_multi("job_log", array("job_fk" => $jid, "message_fk" => "3", "job_status" => "6-7"), array("date_time" => "$date2 $in_time"));
        //$this->job_model->master_fun_update_multi("test_sample_barcode", array("job_fk" => $jid), array("sample_collect_time" => "$in_time"));
        redirect("job-master/job-details/" . $jid);
    }

    function track_report_copy() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $job_id = $this->input->post('report_hrd_cpy_jobid');
        $doctor_status = $this->input->post("doctor_status");
        $doctor_phlebo = $this->input->post("doctor_phlebo");
        $doctor_date = $this->input->post("doctor_date");
        $doctor_time = $this->input->post("doctor_time");

        $patient_status = $this->input->post("patient_status");
        $patient_phlebo = $this->input->post("patient_phlebo");
        $patient_date = $this->input->post("patient_date");
        $patient_time = $this->input->post("patient_time");

        $report_data = $this->job_model->get_val("select * from hard_report_track where status='1' AND job_fk='$job_id'");
        $doctor_time = $this->input->post('doctor_time') == '' ? '' : date("H:i:s", strtotime(str_replace(' ', '', $this->input->post('doctor_time'))));
        $patient_time = $this->input->post('patient_time') == '' ? '' : date("H:i:s", strtotime(str_replace(' ', '', $this->input->post('patient_time'))));

        if (!empty($report_data)) {
            if ($report_data[0]['doctor_status'] != '2') {
                $status_update = $this->job_model->master_fun_update("hard_report_track", array("job_fk", $job_id), array(
                    "doctor_status" => $doctor_status,
                    "doctor_phlebo" => empty($doctor_phlebo) ? NULL : $doctor_phlebo,
                    "doctor_date" => empty($doctor_date) ? NULL : $doctor_date,
                    "doctor_time" => $doctor_time ? $doctor_time : NULL,
                    "created_by_doctor" => $data["login_data"]["id"],
                ));
            }

            if ($report_data[0]['patient_status'] != '2') {
                $status_update = $this->job_model->master_fun_update("hard_report_track", array("job_fk", $job_id), array(
                    "patient_status" => $patient_status,
                    "patient_phlebo" => empty($patient_phlebo) ? NULL : $patient_phlebo,
                    "patient_date" => empty($patient_date) ? NULL : $patient_date,
                    "patient_time" => $patient_time ? $patient_time : NULL,
                    "created_by_patient" => $data["login_data"]["id"],
                ));
            }
        } else {

            $this->job_model->master_fun_insert("hard_report_track", array(
                "job_fk" => $job_id,
                "doctor_status" => $doctor_status,
                "doctor_phlebo" => empty($doctor_phlebo) ? NULL : $doctor_phlebo,
                "doctor_date" => empty($doctor_date) ? NULL : $doctor_date,
                "doctor_time" => $doctor_time,
                "patient_status" => $patient_status,
                "patient_phlebo" => empty($patient_phlebo) ? NULL : $patient_phlebo,
                "patient_date" => empty($patient_date) ? NULL : $patient_date,
                "patient_time" => $patient_time == "" ? NULL : $patient_time,
                "created_by_doctor" => empty($doctor_phlebo) ? NULL : $data["login_data"]["id"],
                "created_by_patient" => empty($patient_phlebo) ? NULL : $data["login_data"]["id"],
                "created_date" => date("Y-m-d H:i:s")));
        }
        $this->session->set_flashdata("success", array("Data Saved successfully."));
        redirect('job-master/pending-list');
    }

    function track_report_copy_ajx() {
        $job_id = $this->input->get_post('job_fk');
        $report_data = $this->job_model->get_val("select * from hard_report_track where status ='1' AND job_fk='$job_id'");

        print_r(json_encode($report_data[0]));
        exit;
    }

    function mark_as_report_delivered() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $job_id = $this->uri->segment(3);
        $type = $this->uri->segment(4);
        if ($type == '1') {
            $status_update = $this->job_model->master_fun_update("hard_report_track", array("job_fk", $job_id), array(
                "doctor_status" => '2',
                'doc_delivery_timestamp' => date('Y-m-d h:i:s'),
                "modified_by_doctor" => $data["login_data"]["id"]));
        } else if ($type == '2') {
            $status_update = $this->job_model->master_fun_update("hard_report_track", array("job_fk", $job_id), array(
                "patient_status" => '2',
                'patient_delivery_timestamp' => date('Y-m-d h:i:s'),
                "modified_by_patient" => $data["login_data"]["id"]));
        }
        $this->session->set_flashdata("success", array("Data Saved successfully."));
        redirect('job-master/pending-list');
    }

}
?>