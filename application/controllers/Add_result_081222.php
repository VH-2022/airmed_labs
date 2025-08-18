<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Add_result extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('user_test_master_model');
        $this->load->model('add_result_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->helper('string');
        $data["login_data"] = logindata();
        $this->app_tarce();
    }

    function app_tarce() {
        $data["login_data"] = logindata();
        if (!is_loggedin()) {
            redirect('login');
        }
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
        $user_track_data = array("user_fk" => $data["login_data"]["id"], "url" => $actual_link, "user_details" => $user_info, "data" => $user_post_data, "createddate" => date("Y-m-d H:i:s"), "type" => "service");
        $app_info = $this->add_result_model->master_fun_insert("parameter_track", $user_track_data);
        //return true;
    }

    function test_details() {
        if (!is_loggedin()) {
            redirect('login');
        }
        if ($this->session->userdata("closeFancyBox")) {
            $data["closeFancyBox"] = $this->session->userdata("closeFancyBox");
            $this->session->unset_userdata("closeFancyBox");
        }
        $data["login_data"] = logindata();
        $data['cid'] = $this->uri->segment(3);
        $data['test_id'] = $this->uri->segment(4);
        $collecttest = $this->add_result_model->get_val("SELECT GROUP_CONCAT(`test_fk`) AS collectsample FROM test_sample_barcode WHERE STATUS='1' AND `job_fk`='" . $data['cid'] . "' AND `sample_collection`='1' GROUP BY job_fk");
        $testcollect = explode(",", $collecttest[0]["collectsample"]);

        if ($data['test_id'] != "") {

            if (!in_array($data['test_id'], $testcollect)) {

                echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"><br><div class="alert alert-danger">
  <strong>Please collect sample</strong> 
</div>';
                die();
            }
        }

        $data['query'] = $this->add_result_model->job_details($data['cid']);
        $data['processing_center'] = $this->add_result_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $data['query'][0]["branch_fk"]), array("id", "asc"));
        if (empty($data['processing_center'])) {
            $processing_center = '1';
        } else {
            $processing_center = $data['processing_center'][0]["branch_fk"];
        }
        $processing_date = $this->add_result_model->get_val("SELECT IF(job_log.date_time!='',DATE_FORMAT(job_log.date_time,'%Y-%m-%d'),DATE_FORMAT(`job_master`.`date`,'%Y-%m-%d')) AS `date` FROM `job_master` LEFT JOIN `job_log` ON `job_log`.`job_fk`=`job_master`.`id` AND `job_log`.`status`='1' AND `job_log`.`job_status`='6-7' WHERE `job_master`.`status`!='0' AND `job_master`.`id`='" . $data['cid'] . "'");
        $pdate = $processing_date[0]["date"];
        $this->load->library("util");
        $util = new Util;
        $data["age"] = $util->get_ref_age($data['query'][0]["dob"], $pdate);
        $data['user_booking_info'] = $this->add_result_model->master_fun_get_tbl_val("booking_info", array('status' => 1, 'id' => $data['query'][0]["booking_info"]), array("id", "asc"));
        $data['user_data'] = $this->add_result_model->master_fun_get_tbl_val("customer_master", array('status' => 1, 'id' => $data['query'][0]["custid"]), array("id", "asc"));
        if (empty($data['user_data'][0]["gender"]) && empty($data['user_data'][0]["age"])) {
            $data['user_data'][0]["gender"] = 'male';
            $data['user_data'][0]["age"] = 24;
            $data['user_data'][0]["age_type"] = 'Y';
        }

        if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
            $data['user_family_info'] = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
            $data['user_data'][0]["gender"] = $data['user_family_info'][0]["gender"];

            $data['user_data'][0]["full_name"] = $data['user_family_info'][0]["name"];
            $data['user_data'][0]["email"] = $data['user_family_info'][0]["email"];
            $data['user_data'][0]["phone"] = $data['user_family_info'][0]["phone"];
            $data["age1"] = $util->get_ref_age($data['user_family_info'][0]["dob"], $pdate);
            $f_age = 0;
            $f_age_type = "";
            if ($data["age1"][0] > 0) {
                $f_age = $data["age1"][0];
                $f_age_type = "Y";
            } else if ($data["age1"][1] > 0) {
                $f_age = $data["age1"][1];
                $f_age_type = "M";
            } else if ($data["age1"][2] > 0) {
                $f_age = $data["age1"][2];
                $f_age_type = "D";
            } else {
                $f_age = 0;
                $f_age_type = "D";
            }
            $data['user_data'][0]["age_type"] = $f_age_type;
            $data['user_data'][0]["age"] = $f_age;
            $data['user_data'][0]["dob"] = $data['user_family_info'][0]["dob"];
        }
        if (empty($data['user_data'][0]["dob"])) {
            $data['user_data'][0]["dob"] = '1992-09-30';
        }


        if ($_REQUEST["debug"] == 5) {
            echo "<pre>";
            print_r($data['user_data']);
            die();
        }
        /* Check bitrth date start */
        $age = $util->get_ref_age($data['user_data'][0]["dob"], $pdate);

        if ($data["login_data"]["id"] == "12") {
            print_r($age);
        }
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

        if ($data["login_data"]["id"] == "12") {
            print_r($age);
            echo $ageinDays;
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
        //print_R($tid); die();
        foreach ($tid as $t_key) {
            $p_test = $this->add_result_model->get_val("SELECT sub_test_master.* FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `sub_test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $t_key . "'");
            foreach ($p_test as $tp_key) {
                $tid[] = $tp_key["sub_test"];
            }
        }
        $tid = array_unique($tid);
        if ($data['cid'] == 24679) {
            $tid = array_reverse($tid);
        }
        /* Nishit check one test start */
        if (!empty($data['test_id'])) {
            $tid = array($data['test_id']);
        }
        /* END */

        $collecttest = array();
        foreach ($tid as $testidj) {

            if (in_array($testidj, $testcollect)) {
                $collecttest[] = $testidj;
            }
        }
        if ($collecttest == null) {

            echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"><br><div class="alert alert-danger">
  <strong>Please First Collected Sample</strong> 
</div>';
            die();
        }

        $get_approve_test_parameter = $this->add_result_model->get_val("SELECT GROUP_CONCAT(test_fk) AS test_fk FROM `approve_job_test` WHERE `status`='1' AND test_fk>0 AND job_fk='" . $data['cid'] . "' GROUP BY job_fk");
        $approve_job_test_id = explode(",", $get_approve_test_parameter[0]["test_fk"]);
        $cnt = 0;
        $new_data_array = array();
        foreach ($collecttest as $tst_id) {
            if (!in_array($tst_id, $approve_job_test_id)) {
                $get_test_parameter = $this->add_result_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name`,`test_master`.PRINTING_NAME,`test_master`.report_type FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' and `test_parameter`.`center`='" . $processing_center . "' order by `test_parameter`.order asc");


                $pid = array();
                foreach ($get_test_parameter as $tp_key) {
                    if (!empty($tp_key["parameter_fk"])) {
                        $pid[] = $tp_key["parameter_fk"];
                    }
                }
                if (!empty($pid)) {
                    $para = $this->add_result_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
                    if (!empty($para)) {
                        $cnt_1 = 0;
                        foreach ($para as $para_key) {
                            $formula = $this->add_result_model->get_val("SELECT * from use_formula where test_fk='" . $tst_id . "' and job_fk='" . $data['cid'] . "' and status='1'");
                            $get_test_parameter[$cnt_1]['use_formula'] = $formula[0]["use_formula"];
                            $get_test_parameter[$cnt_1]['on_new_page'] = $formula[0]["on_new_page"];
                            $get_test_parameter[$cnt_1]['performed'] = $formula[0]["performed"];
                            $get_test_parameter[$cnt_1]['performed_reason'] = $formula[0]["performed_reason"];
                            /* Report type start */
                            $culure_design = $this->add_result_model->get_val("SELECT id,title,html FROM `parameter_culture` WHERE center='" . $processing_center . "' AND test_fk='" . $tst_id . "' AND STATUS='1'");
                            $get_test_parameter[$cnt_1]['culture_design'] = $culure_design;
                            /* End */
                            $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                            $get_test_parameter[$cnt_1]['graph'] = $graph_pic;
                            $get_test_parameter[$cnt_1]['graph_id'] = $formula[0]["id"];
                            $get_test_parameter1 = $get_test_parameter[$cnt_1];
//print_R($get_test_parameter1); die();
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
                            /* if ($data['user_data'][0]["age"] > 0 && $data['user_data'][0]["age"] < 6 && $data['user_data'][0]["age_type"] == 'D') {
                              $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                              } else if ($data['user_data'][0]["age"] > 5 && $data['user_data'][0]["age_type"] == 'D') {
                              $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                              $data["common"] = 0;
                              } else if ($data['user_data'][0]["age"] > 0 && $data['user_data'][0]["age"] < 8 && $data['user_data'][0]["age_type"] == 'M') {
                              $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                              $data["common"] = 0;
                              } else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                              //$get_both_age = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='117' AND gender='B' AND `no_period` > ".$data['user_data'][0]["age"]." AND `type_period`='Y' ORDER BY id ASC");
                              $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                              $data["common"] = 0;
                              } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                              //$get_male_age = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='117' AND gender='M' AND `no_period` > ".$data['user_data'][0]["age"]." AND `type_period`='Y' ORDER BY id ASC");
                              //print_r($get_male_age);
                              $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                              $data["common"] = 0;
                              } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                              $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                              $data["common"] = 0;
                              } */
                            if ($data['user_data'][0]["age"] == '') {
                                $data['user_data'][0]["age"] = 0;
                            }
                            /* if ($data['user_data'][0]["age_type"] == 'D') {
                              $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                              } else if ($data['user_data'][0]["age_type"] == 'M') {
                              $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                              $data["common"] = 0;
                              } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                              $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                              $data["common"] = 0;
                              } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                              $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                              $data["common"] = 0;
                              }
                              else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                              $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                              $data["common"] = 0;
                              } */
                            if (strtoupper($data['user_data'][0]["gender"]) == 'MALE') {
                                $final_qry .= " AND gender='M' AND (CASE WHEN (type_period= 'Y') THEN (no_period*365) ELSE (CASE WHEN (type_period= 'M') THEN (no_period*30) ELSE no_period END) END )>=$ageinDays ";
                                $data["common"] = 0;
                            } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE') {
                                $final_qry .= " AND gender='F' AND  (CASE WHEN (type_period= 'Y') THEN (no_period*365) ELSE (CASE WHEN (type_period= 'M') THEN (no_period*30) ELSE no_period END) END )>=$ageinDays";
                                $data["common"] = 0;
                            }
                            $final_qry = $final_qry . " ORDER BY (col1*1) ASC limit 0,1";

                            //   if($para_key["id"]=="117"){ echo "<pre>"; print_r( $data['user_data'][0]); echo "</pre>"; echo    $final_qry; };
                            $final_qry1 = "SELECT * FROM `test_result_status` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                            $data["common"] = 1;
                            $data["para_ref_rng"] = $this->add_result_model->get_val($final_qry);
                            $data["para_ref_rng"][0]["common"] = "1";
                            $data["para_ref_rng"][0]["tst_id"] = $tst_id;
                            $para[$cnt_1]['para_ref_rng'] = $data["para_ref_rng"];

                            $data["para_ref_status"] = $this->add_result_model->get_val($final_qry1);
                            $para[$cnt_1]['para_ref_status'] = $data["para_ref_status"];
                            $para[$cnt_1]["test_parameter_id"] = $get_test_parameter1["id"];
                            $para[$cnt_1]["new_order"] = $get_test_parameter1["order"];
                            $cnt_1++;
                            //	if($para_key["id"]=="117"){ print_r($data['user_data'][0]); die(); };
                        }
                        $get_test_parameter1[0]['parameter'] = $para;
                        $new_data_array[] = $get_test_parameter1;
                    } else {
                        $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name,report_type FROM `test_master` WHERE id='" . $tst_id . "'");
                        $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                        $get_test_parameter1[0]['graph'] = $graph_pic;


                        $culure_design = $this->add_result_model->get_val("SELECT id,title,html FROM `parameter_culture` WHERE center='" . $processing_center . "' AND test_fk='" . $tst_id . "' AND STATUS='1'");
                        $get_test_parameter1[0]['culture_design'] = $culure_design;


                        $para_culture_val = $this->add_result_model->get_val("SELECT * from user_test_result where test_id='" . $tst_id . "' and job_id='" . $data['cid'] . "' and result is not NULL and status='1'");
                        $get_test_parameter1[0]["user_culture_val"] = $para_culture_val;

                        $new_data_array[] = $get_test_parameter1[0];
                    }
                } else {
                    $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name,report_type FROM `test_master` WHERE id='" . $tst_id . "'");
                    $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                    $get_test_parameter1[0]['graph'] = $graph_pic;


                    $culure_design = $this->add_result_model->get_val("SELECT id,title,html FROM `parameter_culture` WHERE center='" . $processing_center . "' AND test_fk='" . $tst_id . "' AND STATUS='1'");
                    $get_test_parameter1[0]['culture_design'] = $culure_design;


                    $para_culture_val = $this->add_result_model->get_val("SELECT * from user_test_result where test_id='" . $tst_id . "' and job_id='" . $data['cid'] . "' and result is not NULL and status='1'");
                    $get_test_parameter1[0]["user_culture_val"] = $para_culture_val;

                    $new_data_array[] = $get_test_parameter1[0];
                }
                $cnt++;
            }
        }
        $data["new_data_array"] = $new_data_array;
        $data['result_list'] = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('status' => 1, 'job_id' => $data['cid']), array("id", "asc"));
//echo "<pre>";
//print_r($data["new_data_array"]);
//die();
//$data['unit_list'] = $this->add_result_model->unit_list();
        if($_GET["debugt"]==1){
            echo "<pre>";print_r($data);die();
        }
        $this->load->view('header');
        //$this->load->view('nav', $data);
        $this->load->view('view_collected', $data);
        //$this->load->view('footer');
    }

    function test_approve_details() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['cid'] = $this->uri->segment(3);
        $data['tid1'] = $this->uri->segment(4);

        $collecttest = $this->add_result_model->get_val("SELECT GROUP_CONCAT(`test_fk`) AS collectsample FROM test_sample_barcode WHERE STATUS='1' AND `job_fk`='" . $data['cid'] . "' AND `sample_collection`='1' GROUP BY job_fk");
        $testcollect = explode(",", $collecttest[0]["collectsample"]);

        /* if ($data['tid1'] != "") {

          if (!in_array($data['tid1'], $testcollect)) {

          echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"><br><div class="alert alert-danger">
          <strong>Please collect sample</strong>
          </div>';
          die();
          }
          } */

        $data['query'] = $this->add_result_model->job_details($data['cid']);
        $data['processing_center'] = $this->add_result_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $data['query'][0]["branch_fk"]), array("id", "asc"));
        if (empty($data['processing_center'])) {
            $processing_center = '1';
        } else {
            $processing_center = $data['processing_center'][0]["branch_fk"];
        }
        $processing_date = $this->add_result_model->get_val("SELECT IF(job_log.date_time!='',DATE_FORMAT(job_log.date_time,'%Y-%m-%d'),DATE_FORMAT(`job_master`.`date`,'%Y-%m-%d')) AS `date` FROM `job_master` LEFT JOIN `job_log` ON `job_log`.`job_fk`=`job_master`.`id` AND `job_log`.`status`='1' AND `job_log`.`job_status`='6-7' WHERE `job_master`.`status`!='0' AND `job_master`.`id`='" . $data['cid'] . "'");
        $pdate = $processing_date[0]["date"];
        $this->load->library("util");
        $util = new Util;
        $data["age"] = $util->get_ref_age($data['query'][0]["dob"], $pdate);
        //echo "<pre>";print_R($data['query']); die();
        $data['user_booking_info'] = $this->add_result_model->master_fun_get_tbl_val("booking_info", array('status' => 1, 'id' => $data['query'][0]["booking_info"]), array("id", "asc"));
        $data['user_data'] = $this->add_result_model->master_fun_get_tbl_val("customer_master", array('status' => 1, 'id' => $data['query'][0]["custid"]), array("id", "asc"));
        if (empty($data['user_data'][0]["gender"]) && empty($data['user_data'][0]["age"])) {
            $data['user_data'][0]["gender"] = 'male';
            $data['user_data'][0]["age"] = 24;
            $data['user_data'][0]["age_type"] = 'Y';
        }

        if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
            $data['user_family_info'] = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
            $data['user_data'][0]["gender"] = $data['user_family_info'][0]["gender"];
            $data['user_data'][0]["age"] = $data['user_family_info'][0]["age"];
            $data['user_data'][0]["age_type"] = $data['user_family_info'][0]["age_type"];
            $data['user_data'][0]["full_name"] = $data['user_family_info'][0]["name"];
            $data['user_data'][0]["email"] = $data['user_family_info'][0]["email"];
            $data['user_data'][0]["phone"] = $data['user_family_info'][0]["phone"];
            $data['user_data'][0]["dob"] = $data['user_family_info'][0]["dob"];
        }
        if (empty($data['user_data'][0]["dob"])) {
            $data['user_data'][0]["dob"] = '1992-09-30';
        }
        /* Check bitrth date start */
        $age = $util->get_ref_age($data['user_data'][0]["dob"], $pdate);
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
        /* Check Approve test details start */
        $data["approve_job_test_status"] = $this->add_result_model->get_val("SELECT id FROM `approve_job_test` WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $data['tid1'] . "'");
        /* END */

        $tid = array($data['tid1']);

        $collecttest = array();
        foreach ($tid as $testidj) {

            if (in_array($testidj, $testcollect)) {
                $collecttest[] = $testidj;
            }
        }
        /* if ($collecttest == null) {

          echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"><br><div class="alert alert-danger">
          <strong>Please First Collected Sample</strong>
          </div>';
          die();
          } */

        $cnt = 0;
        $new_data_array = array();
        foreach ($tid as $tst_id) {
            $get_test_parameter = $this->add_result_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name`,`test_master`.PRINTING_NAME,`test_master`.`report_type` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' and `test_parameter`.`center`='" . $processing_center . "' order by `test_parameter`.order asc");
//echo "SELECT `test_parameter`.*,`test_master`.`test_name` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' order by `test_parameter`.order asc"; die();
            $pid = array();
            foreach ($get_test_parameter as $tp_key) {
                if (!empty($tp_key["parameter_fk"])) {
                    if (!empty($tp_key["parameter_fk"])) {
                        $pid[] = $tp_key["parameter_fk"];
                    }
                }
            }
            if (!empty($pid)) {
                $para = $this->add_result_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
                if (!empty($para)) {
                    $cnt_1 = 0;
                    foreach ($para as $para_key) {
                        $formula = $this->add_result_model->get_val("SELECT * from use_formula where test_fk='" . $tst_id . "' and job_fk='" . $data['cid'] . "' and status='1'");
                        $get_test_parameter[$cnt_1]['use_formula'] = $formula[0]["use_formula"];
                        $get_test_parameter[$cnt_1]['on_new_page'] = $formula[0]["on_new_page"];
                        /* Report type start */
                        $culure_design = $this->add_result_model->get_val("SELECT id,title,html FROM `parameter_culture` WHERE center='" . $processing_center . "' AND test_fk='" . $tst_id . "' AND STATUS='1'");
                        $get_test_parameter[$cnt_1]['culture_design'] = $culure_design;
                        /* End */
                        $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                        $get_test_parameter[$cnt_1]['graph'] = $graph_pic;
                        $get_test_parameter[$cnt_1]['graph_id'] = $formula[0]["id"];
                        $get_test_parameter1 = $get_test_parameter[$cnt_1];
//print_R($get_test_parameter1); die();
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
                        /* if ($data['user_data'][0]["age"] > 0 && $data['user_data'][0]["age"] < 6 && $data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          } else if ($data['user_data'][0]["age"] > 5 && $data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          $data["common"] = 0;
                          } else if ($data['user_data'][0]["age"] > 0 && $data['user_data'][0]["age"] < 8 && $data['user_data'][0]["age_type"] == 'M') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                          $data["common"] = 0;
                          } else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                          //$get_both_age = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='117' AND gender='B' AND `no_period` > ".$data['user_data'][0]["age"]." AND `type_period`='Y' ORDER BY id ASC");
                          $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          //$get_male_age = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='117' AND gender='M' AND `no_period` > ".$data['user_data'][0]["age"]." AND `type_period`='Y' ORDER BY id ASC");
                          //print_r($get_male_age);
                          $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } */
                        if ($data['user_data'][0]["age"] == '') {
                            $data['user_data'][0]["age"] = 0;
                        }
                        /* if ($data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          } else if ($data['user_data'][0]["age_type"] == 'M') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          }
                          else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } */
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
                        $para[$cnt_1]["test_parameter_id"] = $get_test_parameter1["id"];
                        $para[$cnt_1]["new_order"] = $get_test_parameter1["order"];
                        $cnt_1++;
                    }
                    $get_test_parameter1[0]['parameter'] = $para;
                    $new_data_array[] = $get_test_parameter1;
                } else {
                    $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                    $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                    $get_test_parameter1[0]['graph'] = $graph_pic;
                    $new_data_array[] = $get_test_parameter1[0];
                }
            } else {
                $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                $get_test_parameter1[0]['graph'] = $graph_pic;
                $new_data_array[] = $get_test_parameter1[0];
            }

            $cnt++;
        }
        $data["new_data_array"] = $new_data_array;
        $data['result_list'] = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('status' => 1, 'job_id' => $data['cid']), array("id", "asc"));
//echo "<pre>";
//print_r($data["new_data_array"]);
//die();
//$data['unit_list'] = $this->add_result_model->unit_list();
        $this->load->view('header');
        //$this->load->view('nav', $data);
        $this->load->view('view_collected_test', $data);
        //$this->load->view('footer');
    }

    function all_test_approve_details() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['cid'] = $this->input->get("jid");
        $data['tid1'] = $this->input->get("tid");

        $collecttest = $this->add_result_model->get_val("SELECT GROUP_CONCAT(`test_fk`) AS collectsample FROM test_sample_barcode WHERE STATUS='1' AND `job_fk`='" . $data['cid'] . "' AND `sample_collection`='1' GROUP BY job_fk");
        $testcollect = explode(",", $collecttest[0]["collectsample"]);

        $data['query'] = $this->add_result_model->job_details($data['cid']);
        $data['processing_center'] = $this->add_result_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $data['query'][0]["branch_fk"]), array("id", "asc"));
        if (empty($data['processing_center'])) {
            $processing_center = '1';
        } else {
            $processing_center = $data['processing_center'][0]["branch_fk"];
        }
        $processing_date = $this->add_result_model->get_val("SELECT IF(job_log.date_time!='',DATE_FORMAT(job_log.date_time,'%Y-%m-%d'),DATE_FORMAT(`job_master`.`date`,'%Y-%m-%d')) AS `date` FROM `job_master` LEFT JOIN `job_log` ON `job_log`.`job_fk`=`job_master`.`id` AND `job_log`.`status`='1' AND `job_log`.`job_status`='6-7' WHERE `job_master`.`status`!='0' AND `job_master`.`id`='" . $data['cid'] . "'");
        $pdate = $processing_date[0]["date"];
        $this->load->library("util");
        $util = new Util;
        $data["age"] = $util->get_ref_age($data['query'][0]["dob"], $pdate);
        //echo "<pre>";print_R($data['query']); die();
        $data['user_booking_info'] = $this->add_result_model->master_fun_get_tbl_val("booking_info", array('status' => 1, 'id' => $data['query'][0]["booking_info"]), array("id", "asc"));
        $data['user_data'] = $this->add_result_model->master_fun_get_tbl_val("customer_master", array('status' => 1, 'id' => $data['query'][0]["custid"]), array("id", "asc"));
        if (empty($data['user_data'][0]["gender"]) && empty($data['user_data'][0]["age"])) {
            $data['user_data'][0]["gender"] = 'male';
            $data['user_data'][0]["age"] = 24;
            $data['user_data'][0]["age_type"] = 'Y';
        }

        if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
            $data['user_family_info'] = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
            $data['user_data'][0]["gender"] = $data['user_family_info'][0]["gender"];
            $data['user_data'][0]["age"] = $data['user_family_info'][0]["age"];
            $data['user_data'][0]["age_type"] = $data['user_family_info'][0]["age_type"];
            $data['user_data'][0]["full_name"] = $data['user_family_info'][0]["name"];
            $data['user_data'][0]["email"] = $data['user_family_info'][0]["email"];
            $data['user_data'][0]["phone"] = $data['user_family_info'][0]["phone"];
            $data['user_data'][0]["dob"] = $data['user_family_info'][0]["dob"];
        }
        if (empty($data['user_data'][0]["dob"])) {
            $data['user_data'][0]["dob"] = '1992-09-30';
        }
        /* Check bitrth date start */
        $age = $util->get_ref_age($data['user_data'][0]["dob"], $pdate);
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

        /* $tid = explode(",", $data['tid1']); */
        $tid111 = explode(",", $data['tid1']);
        $tid = array();
        foreach ($tid111 as $rid_key) {
            if (!in_array($rid_key, $tid)) {
                $tid[] = $rid_key;
            }
        }
        $collecttest = array();
        foreach ($tid as $testidj) {

            if (in_array($testidj, $testcollect)) {
                $collecttest[] = $testidj;
            }
        }
        /* if ($collecttest == null) {

          echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"><br><div class="alert alert-danger">
          <strong>Please First Collect Sample</strong>
          </div>';
          die();
          } */

        $cnt = 0;
        $new_data_array = array();
        foreach ($tid as $tst_id) {
            $get_test_parameter = $this->add_result_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name`,`test_master`.PRINTING_NAME,`test_master`.`report_type` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' and `test_parameter`.`center`='" . $processing_center . "' order by `test_parameter`.order asc");
//echo "SELECT `test_parameter`.*,`test_master`.`test_name` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' order by `test_parameter`.order asc"; die();
            $pid = array();
            foreach ($get_test_parameter as $tp_key) {
                if (!empty($tp_key["parameter_fk"])) {
                    $pid[] = $tp_key["parameter_fk"];
                }
            }
            if (!empty($pid)) {
                $para = $this->add_result_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
                if (!empty($para)) {
                    $cnt_1 = 0;
                    foreach ($para as $para_key) {
                        $formula = $this->add_result_model->get_val("SELECT * from use_formula where test_fk='" . $tst_id . "' and job_fk='" . $data['cid'] . "' and status='1'");
                        $get_test_parameter[$cnt_1]['use_formula'] = $formula[0]["use_formula"];
                        $get_test_parameter[$cnt_1]['on_new_page'] = $formula[0]["on_new_page"];
                        /* Report type start */
                        $culure_design = $this->add_result_model->get_val("SELECT id,title,html FROM `parameter_culture` WHERE center='" . $processing_center . "' AND test_fk='" . $tst_id . "' AND STATUS='1'");
                        $get_test_parameter[$cnt_1]['culture_design'] = $culure_design;
                        /* End */
                        $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                        $get_test_parameter[$cnt_1]['graph'] = $graph_pic;
                        $get_test_parameter[$cnt_1]['graph_id'] = $formula[0]["id"];
                        $get_test_parameter1 = $get_test_parameter[$cnt_1];
//print_R($get_test_parameter1); die();
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
  END AS col1 FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                        /* if ($data['user_data'][0]["age"] > 0 && $data['user_data'][0]["age"] < 6 && $data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          } else if ($data['user_data'][0]["age"] > 5 && $data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          $data["common"] = 0;
                          } else if ($data['user_data'][0]["age"] > 0 && $data['user_data'][0]["age"] < 8 && $data['user_data'][0]["age_type"] == 'M') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                          $data["common"] = 0;
                          } else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                          //$get_both_age = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='117' AND gender='B' AND `no_period` > ".$data['user_data'][0]["age"]." AND `type_period`='Y' ORDER BY id ASC");
                          $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          //$get_male_age = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='117' AND gender='M' AND `no_period` > ".$data['user_data'][0]["age"]." AND `type_period`='Y' ORDER BY id ASC");
                          //print_r($get_male_age);
                          $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } */
                        if ($data['user_data'][0]["age"] == '') {
                            $data['user_data'][0]["age"] = 0;
                        }
                        /* if ($data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          } else if ($data['user_data'][0]["age_type"] == 'M') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          }
                          else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } */
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
                        $para[$cnt_1]["test_parameter_id"] = $get_test_parameter1["id"];
                        $para[$cnt_1]["new_order"] = $get_test_parameter1["order"];
                        $cnt_1++;
                    }
                    $get_test_parameter1[0]['parameter'] = $para;
                    $new_data_array[] = $get_test_parameter1;
                } else {
                    $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                    $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                    $get_test_parameter1[0]['graph'] = $graph_pic;
                    $new_data_array[] = $get_test_parameter1[0];
                }
            } else {
                $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                $get_test_parameter1[0]['graph'] = $graph_pic;
                $new_data_array[] = $get_test_parameter1[0];
            }

            $cnt++;
        }
        $data["new_data_array"] = $new_data_array;
        $data['result_list'] = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('status' => 1, 'job_id' => $data['cid']), array("id", "asc"));
//echo "<pre>";
//print_r($data["new_data_array"]);
//die();
//$data['unit_list'] = $this->add_result_model->unit_list();
        $this->load->view('header');
        //$this->load->view('nav', $data);
        $this->load->view('view_collected_test1', $data);
        //$this->load->view('footer');
    }

    function add_parameter_data() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $count = $this->input->post('count_par');
        $test_id = $this->input->post('test_id');
        $job_id = $this->input->post('job_id');
        $test_description = $this->input->post('desc_test');
        $this->add_result_model->master_fun_update('test_master', array('id', $test_id), array("description" => $test_description));
        for ($i = 1; $i <= $count; $i++) {
            $data["login_data"] = logindata();
            $parname = $this->input->post('par_name_' . $i);
            $parmin = $this->input->post('par_min_' . $i);
            $parmax = $this->input->post('par_max_' . $i);
            $parunit = $this->input->post('par_unit_' . $i);
            $range = $parmin . "-" . $parmax;
            $data = array(
                "test_fk" => $test_id,
                "parameter_name" => $parname,
                "parameter_range" => $range,
                "parameter_unit" => $parunit,
                "created_by" => $data["login_data"]["id"],
                "created_date" => date("Y-m-d H:i:s")
            );
            $insert = $this->add_result_model->master_fun_insert("test_parameter_master", $data);
            if ($insert) {
                $data["login_data"] = logindata();
                $parvalue = $this->input->post('par_value_' . $i);
                $parcondi = $this->input->post('par_condi_' . $i);
                $data = array(
                    "job_id" => $job_id,
                    "parameter_id" => $insert,
                    "value" => $parvalue,
                    "condition" => $parcondi,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s")
                );
                $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
            }
        }
        if ($val_add) {
            redirect('add_result/test_details/' . $job_id);
        }
    }

    function sample_collected_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $test_packages = explode("_", $test_pack);
        $alpha = $test_packages[0];
        $tp_id = $test_packages[1];
        if ($alpha == 't') {
            $t_id = $tp_id;
        }
        if ($alpha == 'p') {
            $p_id = $tp_id;
        }
        $data['branch_permission'] = $this->add_result_model->master_fun_get_tbl_val("user_branch", array('user_fk' => $data["login_data"]['id'], 'status' => 1), array("id", "asc"));
        $cntr_arry = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data['branch_permission'] as $key) {
                //if ($key["test_parameter"] == 1) {
                $cntr_arry[] = $key["branch_fk"];
                // }
            }
        }
        //print_r($cntr_arry); die();
        $data['success'] = $this->session->flashdata("success");
        $user = $data["user"] = $this->input->get_post("user");
        $mobile = $data["mobile"] = $this->input->get_post("mobile");
        $date = $data["date"] = $this->input->get_post("date");
        $p_amount = $data["p_amount"] = $this->input->get_post("p_amount");
        if ($user != "" || $date != "" || $city != "" || $status != "" || $mobile != "" || $t_id != "" || $p_id != "" || $p_amount != "") {
            $total_row = $this->add_result_model->num_row_srch_job_list($user, $date, $city, $status, $mobile, $t_id, $p_id, $p_amount, $cntr_arry);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "add_result/sample_collected_list?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->add_result_model->row_srch_job_list($user, $date, $city, $status, $mobile, $t_id, $p_id, $p_amount, $config["per_page"], $page, $cntr_arry);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        } else {
            $total_row = $this->add_result_model->num_srch_job_list($cntr_arry);
            $config["base_url"] = base_url() . "add_result/sample_collected_list";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->add_result_model->srch_job_list($config["per_page"], $page, $cntr_arry);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        }
        $data['customer'] = $this->add_result_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("full_name", "asc"));
        $data['city'] = $this->add_result_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));
        if (empty($cntr_arry) && ($data["login_data"] == 5 || $data["login_data"] == 6 || $data["login_data"] == 7)) {
            $data['query'] = array();
            $data["links"] = '';
        }
        $cnt = 0;
        foreach ($data['query'] as $key) {
            $w_prc = 0;
            /* Count booked test price */
            $booked_tests = $this->add_result_model->master_fun_get_tbl_val("wallet_master", array('job_fk' => $key["id"]), array("id", "asc"));
            $emergency_tests = $this->add_result_model->master_fun_get_tbl_val("booking_info", array('id' => $key["booking_info"]), array("id", "asc"));
            $f_data = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
            $f_data1 = $this->add_result_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
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
            $cnt++;
        }
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('collected_job_list', $data);
        $this->load->view('footer');
    }

    function add_value_exists_old() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $files = $_FILES;
        $this->load->library('upload');
        $single_test_fk = $this->uri->segment(3);
        //echo "<pre>";
        //print_r($_POST);
        //die();
        $data["login_data"] = logindata();
        $count = $this->input->post('count');
        $para_job_id = $this->input->post('para_job_id');
        $this->add_result_model->master_fun_update("use_formula", array("job_fk", $para_job_id), array("status" => 0));
        $test_id_array = array();
        for ($i = 0; $i < $count; $i++) {
            $para_id = $this->input->post('parameter_id_' . $i);
            $test_id = $this->input->post('test_id_' . $i);
            $value = $this->input->post('parameter_value_' . $i);
            $value2 = $this->input->post('parameter2_value_' . $i);
            $this->add_result_model->master_fun_update1("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id), array("status" => "0"));
            if ($value != '') {
                $check_val = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id, "status" => 1), array("id", "asc"));
                if (empty($check_val)) {
                    $data = array(
                        "job_id" => $para_job_id,
                        "parameter_id" => $para_id,
                        "value" => $value,
                        "value2" => $value2,
                        "test_id" => $test_id,
                        "created_by" => $data["login_data"]["id"],
                        "created_date" => date("Y-m-d H:i:s"),
                    );
                    $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
                } else {
                    $data = array("value" => $value, "value2" => $value2, "status" => "1");
                    $val_add = $this->add_result_model->master_fun_update("user_test_result", array("id", $check_val[0]['id']), $data);
                }
            }
        }
        $para = $this->input->post("para");
        $order = $this->input->post("order");
        $p_cnt = 0;
        foreach ($para as $value1) {
            $this->add_result_model->master_fun_update('test_parameter', array('id', $value1), array("order" => $order[$p_cnt]));
            $p_cnt++;
        }
        if ($value != '') {
            $check_val = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id, "status" => 1), array("id", "asc"));
            if (empty($check_val)) {
                $data = array(
                    "job_id" => $para_job_id,
                    "parameter_id" => $para_id,
                    "value" => $value,
                    "value2" => $value2,
                    "test_id" => $test_id,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s"),
                );
                $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
            } else {
                $data = array("value" => $value, "value2" => $value2);
                $val_add = $this->add_result_model->master_fun_update("user_test_result", array("id", $check_val[0]['id']), $data);
            }
        }

        $test_fk = $this->input->post('test_fk');
        foreach ($test_fk as $key) {
            $formula = $this->input->post('use_formula_' . $key);
            $on_new_page = $this->input->post('on_new_page_' . $key);
            $is_culture = $this->input->post('is_culture_' . $key);
            $culture_result = $this->input->post('culture_design_' . $key);
            $culture_result_fk = $this->input->post("culture_design_fk_" . $key);

            $times = $this->input->post('times_' . $key);
            $times_reason = $this->input->post('times_reason_' . $key);
            if ($is_culture == '') {
                $is_culture = 0;
            }
            if ($formula == '') {
                $formula = 0;
            }
            if ($on_new_page == '') {
                $on_new_page = 0;
            }

            /* Culture result add start */
            $culture_result = $this->input->post('culture_design_' . $key);
            $culture_result_fk = $this->input->post("culture_design_fk_" . $key);
            if (!empty($culture_result)) {
                $this->add_result_model->master_fun_update1("user_test_result", array('job_id' => $para_job_id, "parameter_id" => 0, "test_id" => $key), array("status" => "0"));
                $data = array(
                    "job_id" => $para_job_id,
                    "parameter_id" => "",
                    "value" => "",
                    "value2" => "",
                    "test_id" => $key,
                    "result" => $culture_result,
                    "result_fk" => $culture_result_fk,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s"),
                );
                $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
            }
            /* END */


            /* Nishit Graph Upload start */
            /* $graph_name = '';
              if ($files['graph_' . $key]['name'] != '') {
              $_FILES['userfile']['name'] = $files['graph_' . $key]['name'];
              $_FILES['userfile']['type'] = $files['graph_' . $key]['type'];
              $_FILES['userfile']['tmp_name'] = $files['graph_' . $key]['tmp_name'];
              $_FILES['userfile']['error'] = $files['graph_' . $key]['error'];
              $_FILES['userfile']['size'] = $files['graph_' . $key]['size'];
              $config['upload_path'] = './upload/report/graph/';
              $config['allowed_types'] = 'gif|jpg|png';
              $config['file_name'] = time() . $files['graph_' . $key]['name'];
              $config['file_name'] = str_replace(' ', '_', $config['file_name']);
              $config['overwrite'] = FALSE;
              $this->load->library('upload', $config);
              $this->upload->initialize($config);
              if (!$this->upload->do_upload()) {

              } else {
              $graph_name = $config['file_name'];
              }
              }
             */


            //print_r($_FILES['graph_' . $key]['name'][0]);
            $file_loop = count($_FILES['graph_' . $key]['name']);
            $file_upload = array();
            if (!empty($_FILES['graph_' . $key]['name'])) {
                for ($i = 0; $i < $file_loop; $i++) {
                    $_FILES['userfile']['name'] = $files['graph_' . $key]['name'][$i];
                    $_FILES['userfile']['type'] = $files['graph_' . $key]['type'][$i];
                    $_FILES['userfile']['tmp_name'] = $files['graph_' . $key]['tmp_name'][$i];
                    $_FILES['userfile']['error'] = $files['graph_' . $key]['error'][$i];
                    $_FILES['userfile']['size'] = $files['graph_' . $key]['size'][$i];
                    $config['upload_path'] = './upload/report/graph/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['file_name'] = time() . $files['graph_' . $key]['name'][$i];
                    $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                    $config['overwrite'] = FALSE;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload()) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata("error", array($error));
                        //redirect('job-master/job-details/' . $cid);
                    } else {
                        $file_upload[] = array("test_fk" => $key, "pic" => $config['file_name']);
                    }
                }
            }
            //print_R($file_upload); die();
            /* Nishit Graph Upload end */
            if (!in_array($key, $test_id_array)) {
                if ($graph_name == '') {
                    $graph_name = $this->input->post('current_graph_' . $key);
                    ;
                }
                $data12 = array(
                    "job_fk" => $para_job_id,
                    "test_fk" => $key,
                    "use_formula" => $formula,
                    "on_new_page" => $on_new_page,
                    "is_culture" => $is_culture,
                    "performed" => $times,
                    "performed_reason" => $times_reason,
                    "status" => 1
                );
                $test_id_array[] = $key;
                $val_add = $this->add_result_model->master_fun_insert("use_formula", $data12);
            }
            foreach ($file_upload as $file_key) {
                $data12 = array(
                    "job_fk" => $para_job_id,
                    "test_fk" => $key,
                    "pic" => $file_key["pic"],
                    "status" => 1,
                    "createddate" => date("Y-m-d H:i:s")
                );
                //print_r($data12); die();
                $val_add = $this->add_result_model->master_fun_insert("user_formula_pic", $data12);
            }
        }

        $this->add_result_model->master_fun_insert("job_log", array("job_fk" => $para_job_id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "15", "date_time" => date("Y-m-d H:i:s")));
        $this->session->set_userdata("closeFancyBox", array('parent.close_popup1();'));
        redirect('add_result/test_details/' . $para_job_id . '/' . $single_test_fk);
    }

    function add_value_exists() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $files = $_FILES;
        $this->load->library('upload');
        $single_test_fk = $this->uri->segment(3);
        //echo "<pre>";
        //print_r($_POST);
        //die();
        
        $data["login_data"] = logindata();
        $login_data = $data["login_data"];
        $adminid = $data["login_data"]["id"];
        $count = $this->input->post('count');
        $para_job_id = $this->input->post('para_job_id');
        //$this->add_result_model->master_fun_update("use_formula", array("job_fk", $para_job_id), array("status" => 0));
        $test_id_array = array();
        $send_approve_test_id_array = array();
        for ($i = 0; $i < $count; $i++) {
            $para_id = $this->input->post('parameter_id_' . $i);
            $test_id = $this->input->post('test_id_' . $i);
            $value = $this->input->post('parameter_value_' . $i);
            $value2 = $this->input->post('parameter2_value_' . $i);
            $report_status = $this->input->post('report_status_' . $test_id);
            $this->add_result_model->master_fun_update1("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id), array("status" => "0"));
            if ($value != '') {
                $check_val = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id, "status" => 1), array("id", "asc"));
                if (empty($check_val)) {
                    $data = array(
                        "job_id" => $para_job_id,
                        "parameter_id" => $para_id,
                        "value" => $value,
                        "value2" => $value2,
                        "test_id" => $test_id,
                        "created_by" => $data["login_data"]["id"],
                        "created_date" => date("Y-m-d H:i:s"),
                        "report_status" => $report_status
                    );
                    $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
                } else {
                    $data = array("value" => $value, "value2" => $value2, "status" => "1");
                    $val_add = $this->add_result_model->master_fun_update("user_test_result", array("id", $check_val[0]['id']), $data);
                }
                if (!in_array($test_id, $send_approve_test_id_array)) {
                    $send_approve_test_id_array[] = $test_id;
                }
            }
        }
        $para = $this->input->post("para");
        $order = $this->input->post("order");
        $p_cnt = 0;
        foreach ($para as $value1) {
            $this->add_result_model->master_fun_update('test_parameter', array('id', $value1), array("order" => $order[$p_cnt]));
            $p_cnt++;
        }
        if ($value != '') {
            $check_val = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id, "status" => 1), array("id", "asc"));
            if (empty($check_val)) {
                $data = array(
                    "job_id" => $para_job_id,
                    "parameter_id" => $para_id,
                    "value" => $value,
                    "value2" => $value2,
                    "test_id" => $test_id,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s"),
                );
                $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
            } else {
                $data = array("value" => $value, "value2" => $value2);
                $val_add = $this->add_result_model->master_fun_update("user_test_result", array("id", $check_val[0]['id']), $data);
            }

            if (!in_array($test_id, $send_approve_test_id_array)) {
                $send_approve_test_id_array[] = $test_id;
            }
        }

        $test_fk = $this->input->post('test_fk');
        foreach ($test_fk as $key) {
            $formula = $this->input->post('use_formula_' . $key);
            $on_new_page = $this->input->post('on_new_page_' . $key);
            $is_culture = $this->input->post('is_culture_' . $key);
            $culture_result = $this->input->post('culture_design_' . $key);
            $culture_result_fk = $this->input->post("culture_design_fk_" . $key);

            $times = $this->input->post('times_' . $key);
            $times_reason = $this->input->post('times_reason_' . $key);
            if ($is_culture == '') {
                $is_culture = 0;
            }
            if ($formula == '') {
                $formula = 0;
            }
            if ($on_new_page == '') {
                $on_new_page = 0;
            }

            /* Culture result add start */
            $culture_result = $this->input->post('culture_design_' . $key);
            $culture_result_fk = $this->input->post("culture_design_fk_" . $key);
            if (!empty($culture_result)) {
                $this->add_result_model->master_fun_update1("user_test_result", array('job_id' => $para_job_id, "parameter_id" => 0, "test_id" => $key), array("status" => "0"));
                $data = array(
                    "job_id" => $para_job_id,
                    "parameter_id" => "",
                    "value" => "",
                    "value2" => "",
                    "test_id" => $key,
                    "result" => $culture_result,
                    "result_fk" => $culture_result_fk,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s"),
                );
                $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
                if (!in_array($key, $send_approve_test_id_array)) {
                    $send_approve_test_id_array[] = $key;
                }
            }
            /* END */


            /* Nishit Graph Upload start */
            /* $graph_name = '';
              if ($files['graph_' . $key]['name'] != '') {
              $_FILES['userfile']['name'] = $files['graph_' . $key]['name'];
              $_FILES['userfile']['type'] = $files['graph_' . $key]['type'];
              $_FILES['userfile']['tmp_name'] = $files['graph_' . $key]['tmp_name'];
              $_FILES['userfile']['error'] = $files['graph_' . $key]['error'];
              $_FILES['userfile']['size'] = $files['graph_' . $key]['size'];
              $config['upload_path'] = './upload/report/graph/';
              $config['allowed_types'] = 'gif|jpg|png';
              $config['file_name'] = time() . $files['graph_' . $key]['name'];
              $config['file_name'] = str_replace(' ', '_', $config['file_name']);
              $config['overwrite'] = FALSE;
              $this->load->library('upload', $config);
              $this->upload->initialize($config);
              if (!$this->upload->do_upload()) {

              } else {
              $graph_name = $config['file_name'];
              }
              }
             */


            //print_r($_FILES['graph_' . $key]['name'][0]);
            $file_loop = count($_FILES['graph_' . $key]['name']);
            $file_upload = array();
            if (!empty($_FILES['graph_' . $key]['name'])) {
                for ($i = 0; $i < $file_loop; $i++) {
                    $_FILES['userfile']['name'] = $files['graph_' . $key]['name'][$i];
                    $_FILES['userfile']['type'] = $files['graph_' . $key]['type'][$i];
                    $_FILES['userfile']['tmp_name'] = $files['graph_' . $key]['tmp_name'][$i];
                    $_FILES['userfile']['error'] = $files['graph_' . $key]['error'][$i];
                    $_FILES['userfile']['size'] = $files['graph_' . $key]['size'][$i];
                    $config['upload_path'] = './upload/report/graph/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['file_name'] = time() . $files['graph_' . $key]['name'][$i];
                    $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                    $config['overwrite'] = FALSE;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload()) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata("error", array($error));
                        //redirect('job-master/job-details/' . $cid);
                    } else {
                        $file_upload[] = array("test_fk" => $key, "pic" => $config['file_name'], "type" => 1);
                        if (!in_array($key, $send_approve_test_id_array)) {
                            $send_approve_test_id_array[] = $key;
                        }
                    }
                }
            }

            $file_loop = count($_FILES['slide_' . $key]['name']);
            if (!empty($_FILES['slide_' . $key]['name'])) {
                for ($i = 0; $i < $file_loop; $i++) {
                    $_FILES['userfile']['name'] = $files['slide_' . $key]['name'][$i];
                    $_FILES['userfile']['type'] = $files['slide_' . $key]['type'][$i];
                    $_FILES['userfile']['tmp_name'] = $files['slide_' . $key]['tmp_name'][$i];
                    $_FILES['userfile']['error'] = $files['slide_' . $key]['error'][$i];
                    $_FILES['userfile']['size'] = $files['slide_' . $key]['size'][$i];
                    $config['upload_path'] = './upload/report/graph/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['file_name'] = time() . $files['slide_' . $key]['name'][$i];
                    $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                    $config['overwrite'] = FALSE;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload()) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata("error", array($error));
                        //redirect('job-master/job-details/' . $cid);
                    } else {
                        $file_upload[] = array("test_fk" => $key, "pic" => $config['file_name'], "type" => 2);
                    }
                }
            }


            $file_loop = count($_FILES['report_' . $key]['name']);
            if (!empty($_FILES['report_' . $key]['name'])) {
                for ($i = 0; $i < $file_loop; $i++) {
                    $_FILES['userfile']['name'] = $files['report_' . $key]['name'][$i];
                    $_FILES['userfile']['type'] = $files['report_' . $key]['type'][$i];
                    $_FILES['userfile']['tmp_name'] = $files['report_' . $key]['tmp_name'][$i];
                    $_FILES['userfile']['error'] = $files['report_' . $key]['error'][$i];
                    $_FILES['userfile']['size'] = $files['report_' . $key]['size'][$i];
                    $config['upload_path'] = './upload/report/graph/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx';
                    $config['file_name'] = time() . $files['report_' . $key]['name'][$i];
                    $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                    $config['overwrite'] = FALSE;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload()) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata("error", array($error));
                        //redirect('job-master/job-details/' . $cid);
                    } else {
                        $file_upload[] = array("test_fk" => $key, "pic" => $config['file_name'], "type" => 3);
                    }
                }
            }

            //print_R($file_upload); die();
            /* Nishit Graph Upload end */
            if (!in_array($key, $test_id_array)) {
                if ($graph_name == '') {
                    $graph_name = $this->input->post('current_graph_' . $key);
                    ;
                }
                $data12 = array(
                    "job_fk" => $para_job_id,
                    "test_fk" => $key,
                    "use_formula" => $formula,
                    "on_new_page" => $on_new_page,
                    "is_culture" => $is_culture,
                    "performed" => $times,
                    "performed_reason" => $times_reason,
                    "status" => 1
                );
                if (in_array($key, $send_approve_test_id_array)) {
                    $data12["test_status"] = "1";
                }
                $test_id_array[] = $key;
                $uftid = $this->add_result_model->get_val("select id from use_formula where status='1' and job_fk='" . $para_job_id . "' and test_fk='" . $key . "'");
                if (empty($uftid)) {
                    $val_add = $this->add_result_model->master_fun_insert("use_formula", $data12);
                } else {
                    $this->add_result_model->master_fun_update1("use_formula", array("job_fk" => $para_job_id, "test_fk" => $key), $data12);
                }
            }
            foreach ($file_upload as $file_key) {
                $data12 = array(
                    "job_fk" => $para_job_id,
                    "test_fk" => $key,
                    "pic" => $file_key["pic"],
                    "type" => $file_key["type"],
                    "status" => 1,
                    "createddate" => date("Y-m-d H:i:s")
                );
                //print_r($data12); die();
                $val_add = $this->add_result_model->master_fun_insert("user_formula_pic", $data12);

                if (!in_array($key, $send_approve_test_id_array)) {
                    $send_approve_test_id_array[] = $key;
                }
            }
        }

        $this->add_result_model->master_fun_insert("job_log", array("job_fk" => $para_job_id, "created_by" => "", "updated_by" => $adminid, "deleted_by" => "", "job_status" => '', "message_fk" => "15", "date_time" => date("Y-m-d H:i:s")));
        $this->session->set_userdata("closeFancyBox", array('parent.close_popup1();'));
        /* Nishit Send For approve is started */
        $get_job_details = $this->add_result_model->get_val("SELECT job_master.id,job_master.order_id,job_master.branch_fk,branch_master.branch_name from job_master inner join branch_master on job_master.branch_fk=branch_master.id where job_master.id='" . $para_job_id . "'");
        $check_is_mail_send = $this->add_result_model->master_fun_get_tbl_val("send_report_for_approve", array('branch_fk' => $get_job_details[0]["branch_fk"], "status" => 1), array("id", "asc"));
        if (!empty($send_approve_test_id_array) && !empty($check_is_mail_send)) {
            $job_test_details = array();
            foreach ($send_approve_test_id_array as $utdid) {
                $get_job_details1 = $this->add_result_model->get_val("SELECT test_name from test_master where id='" . $utdid . "'");
                $job_test_details[] = $get_job_details1[0]["test_name"];
            }
            $get_admin_details1 = $this->add_result_model->get_val("SELECT * from admin_master where id='" . $login_data["id"] . "'");
            $test_names = implode(",", $job_test_details);
            $this->load->library("util");
            $util = new Util;
            $this->load->helper("Email");
            $email_cnt = new Email;
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $message1 = '<div style="padding:0 4%;">
                    <h4><b>Respected Sir/Madam</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Please approve test result.</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Branch Name </b>: ' . $get_job_details[0]["branch_name"] . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Order Id/Ref No. </b>: ' . $get_job_details[0]["order_id"] . '/' . $get_job_details[0]["id"] . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Uploaded Result Test Name </b>: ' . $test_names . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Result Added By </b>: ' . $get_admin_details1[0]["name"] . '-' . $get_admin_details1[0]["phone"] . ' </p>    
                        <p style="color:#7e7e7e;font-size:13px;"><b><a href="' . base_url() . 'Approve_result_from_mail/all_test_approve_details_mail?jid=' . $get_job_details[0]["id"] . '&tid=' . implode(",", $send_approve_test_id_array) . '">Click Here</a></b> for approve test.</p>
                </div>';
            $c_email = array();
            $message1 = $email_cnt->get_design($message1);
            $c_email[] = $email;
            $this->email->to($check_is_mail_send[0]["email"]);
            $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
            $this->email->subject("Approve Uploaded Test Result");
            $this->email->message($message1);
            $this->email->send();
        }
        /* END */
        redirect('add_result/test_details/' . $para_job_id . '/' . $single_test_fk);
    }

    function add_value_exists1() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $tid = $this->input->get_post("tid");
        $files = $_FILES;
        $this->load->library('upload');
        //echo "<pre>"; print_r($_POST); die();
        $data["login_data"] = logindata();
        $adminid = $data["login_data"]["id"];
        $count = $this->input->post('count');
        $para_job_id = $this->input->post('para_job_id');
        $report_status = $this->input->post('report_status_' . $test_id);
        //$this->add_result_model->master_fun_update("use_formula", array("job_fk", $para_job_id), array("status" => 0));
        $test_id_array = array();
        for ($i = 0; $i < $count; $i++) {
            $para_id = $this->input->post('parameter_id_' . $i);
            $test_id = $this->input->post('test_id_' . $i);
            $value = $this->input->post('parameter_value_' . $i);

            if ($value != '') {
                $check_val = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id, "status" => 1), array("id", "asc"));
                if (empty($check_val)) {
                    $data = array(
                        "job_id" => $para_job_id,
                        "parameter_id" => $para_id,
                        "value" => $value,
                        "test_id" => $test_id,
                        "created_by" => $data["login_data"]["id"],
                        "created_date" => date("Y-m-d H:i:s"),
                    );
                    $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
                } else {
                    $data = array("value" => $value, "report_status" => $report_status);
                    $val_add = $this->add_result_model->master_fun_update("user_test_result", array("id", $check_val[0]['id']), $data);
                }
                if (!in_array($test_id, $send_approve_test_id_array)) {
                    $send_approve_test_id_array[] = $test_id;
                }
            }
        }
        $para = $this->input->post("para");
        $order = $this->input->post("order");
        $p_cnt = 0;
        foreach ($para as $value) {
            $this->add_result_model->master_fun_update('test_parameter', array('id', $value), array("order" => $order[$p_cnt]));
            $p_cnt++;
        }
        if ($value != '') {
            $check_val = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id, "status" => 1), array("id", "asc"));
            if (empty($check_val)) {
                $data = array(
                    "job_id" => $para_job_id,
                    "parameter_id" => $para_id,
                    "value" => $value,
                    "test_id" => $test_id,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s"),
                );
                $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
            } else {
                $data = array("value" => $value);
                $val_add = $this->add_result_model->master_fun_update("user_test_result", array("id", $check_val[0]['id']), $data);
            }
            if (!in_array($test_id, $send_approve_test_id_array)) {
                $send_approve_test_id_array[] = $test_id;
            }
        }

        $test_fk = $this->input->post('test_fk');
        foreach ($test_fk as $key) {
            $formula = $this->input->post('use_formula_' . $key);
            $on_new_page = $this->input->post('on_new_page_' . $key);
            if ($formula == '') {
                $formula = 0;
            }
            if ($on_new_page == '') {
                $on_new_page = 0;
            }
            /* Culture result add start */
            $culture_result = $this->input->post('culture_design_' . $key);
            $culture_result_fk = $this->input->post("culture_design_fk_" . $key);
            if (!empty($culture_result)) {
                $this->add_result_model->master_fun_update1("user_test_result", array('job_id' => $para_job_id, "parameter_id" => 0, "test_id" => $key), array("status" => "0"));
                $data = array(
                    "job_id" => $para_job_id,
                    "parameter_id" => "",
                    "value" => "",
                    "value2" => "",
                    "test_id" => $key,
                    "result" => $culture_result,
                    "result_fk" => $culture_result_fk,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s"),
                );
                $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
                if (!in_array($test_id, $send_approve_test_id_array)) {
                    $send_approve_test_id_array[] = $test_id;
                }
            }
            /* END */
            /* Nishit Graph Upload start */
            /* $graph_name = '';
              if ($files['graph_' . $key]['name'] != '') {
              $_FILES['userfile']['name'] = $files['graph_' . $key]['name'];
              $_FILES['userfile']['type'] = $files['graph_' . $key]['type'];
              $_FILES['userfile']['tmp_name'] = $files['graph_' . $key]['tmp_name'];
              $_FILES['userfile']['error'] = $files['graph_' . $key]['error'];
              $_FILES['userfile']['size'] = $files['graph_' . $key]['size'];
              $config['upload_path'] = './upload/report/graph/';
              $config['allowed_types'] = 'gif|jpg|png';
              $config['file_name'] = time() . $files['graph_' . $key]['name'];
              $config['file_name'] = str_replace(' ', '_', $config['file_name']);
              $config['overwrite'] = FALSE;
              $this->load->library('upload', $config);
              $this->upload->initialize($config);
              if (!$this->upload->do_upload()) {

              } else {
              $graph_name = $config['file_name'];
              }
              }
             */
            //print_r($_FILES['graph_' . $key]['name'][0]);
            $file_loop = count($_FILES['graph_' . $key]['name']);
            $file_upload = array();
            if (!empty($_FILES['graph_' . $key]['name'])) {
                for ($i = 0; $i < $file_loop; $i++) {
                    $_FILES['userfile']['name'] = $files['graph_' . $key]['name'][$i];
                    $_FILES['userfile']['type'] = $files['graph_' . $key]['type'][$i];
                    $_FILES['userfile']['tmp_name'] = $files['graph_' . $key]['tmp_name'][$i];
                    $_FILES['userfile']['error'] = $files['graph_' . $key]['error'][$i];
                    $_FILES['userfile']['size'] = $files['graph_' . $key]['size'][$i];
                    $config['upload_path'] = './upload/report/graph/';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['file_name'] = time() . $files['graph_' . $key]['name'][$i];
                    $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                    $config['overwrite'] = FALSE;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload()) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata("error", array($error));
                        //redirect('job-master/job-details/' . $cid);
                    } else {
                        $file_upload[] = array("test_fk" => $key, "pic" => $config['file_name']);
                        if (!in_array($test_id, $send_approve_test_id_array)) {
                            $send_approve_test_id_array[] = $test_id;
                        }
                    }
                }
            }
            //print_R($file_upload); die();
            /* Nishit Graph Upload end */
            if (!in_array($key, $test_id_array)) {
                if ($graph_name == '') {
                    $graph_name = $this->input->post('current_graph_' . $key);
                    ;
                }
                $data12 = array(
                    "job_fk" => $para_job_id,
                    "test_fk" => $key,
                    "use_formula" => $formula,
                    "on_new_page" => $on_new_page,
                    // "graph" => $graph_name,
                    "status" => 1
                );
                $test_id_array[] = $key;
                //  $val_add = $this->add_result_model->master_fun_insert("use_formula", $data12);
            }
            foreach ($file_upload as $file_key) {
                $data12 = array(
                    "job_fk" => $para_job_id,
                    "test_fk" => $key,
                    "pic" => $file_key["pic"],
                    "status" => 1,
                    "createddate" => date("Y-m-d H:i:s")
                );
                //print_r($data12); die();
                $val_add = $this->add_result_model->master_fun_insert("user_formula_pic", $data12);
            }
            $check_is_approved = $this->add_result_model->master_fun_get_tbl_val("approve_job_test", array('job_fk' => $para_job_id, "test_fk" => $key, "status" => "1"), array("id", "asc"));
            //print_r($check_is_approved); die();
            if (empty($check_is_approved)) {
                $insert = $this->add_result_model->master_fun_insert("approve_job_test", array('job_fk' => $para_job_id, "test_fk" => $key, "approve_by" => $data["login_data"]["id"], "created_date" => date("Y-m-d H:i:s")));
            }
            if (!in_array($key, $send_approve_test_id_array)) {
                $send_approve_test_id_array[] = $key;
            }
        }
        foreach ($send_approve_test_id_array as $tpkey) {
            if ($tpkey > 0) {
                $this->add_result_model->master_fun_update1("use_formula", array("job_fk" => $para_job_id, "test_fk" => $tpkey), array("test_status" => 2));
            }
        }

        $this->add_result_model->master_fun_insert("job_log", array("job_fk" => $para_job_id, "created_by" => "", "updated_by" => $adminid, "deleted_by" => "", "job_status" => '', "message_fk" => "32", "date_time" => date("Y-m-d H:i:s")));
        //echo 'add_result/test_approve_details/' . $para_job_id . "/" . $tid; die();

        redirect('add_result/test_approve_details/' . $para_job_id . "/" . $tid);
    }

    function add_value_exists2() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $files = $_FILES;
        $this->load->library('upload');
        //echo "<pre>"; print_r($_POST); die();
        $data["login_data"] = logindata();
        $adminid = $data["login_data"]["id"];
        $count = $this->input->post('count');
        $tid = $this->input->post('tid');
        $para_job_id = $this->input->post('para_job_id');
        $send_approve_test_id_array = array();
        //$this->add_result_model->master_fun_update("use_formula", array("job_fk", $para_job_id), array("status" => 0));
        $test_id_array = array();
        for ($i = 0; $i < $count; $i++) {
            $para_id = $this->input->post('parameter_id_' . $i);
            $test_id = $this->input->post('test_id_' . $i);
            //$test_id = $tid[$i];
            $value = $this->input->post('parameter_value_' . $i);
            $report_status = $this->input->post('report_status_' . $test_id);
            $this->add_result_model->master_fun_update1("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id), array("status" => "0"));
            if ($value != '') {
                $check_val = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id, "status" => 1), array("id", "asc"));
                if (empty($check_val)) {
                    $data = array(
                        "job_id" => $para_job_id,
                        "parameter_id" => $para_id,
                        "value" => $value,
                        "test_id" => $test_id,
                        "created_by" => $data["login_data"]["id"],
                        "created_date" => date("Y-m-d H:i:s"),
                        "report_status" => $report_status
                    );
                    $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
                } else {
                    $data = array("value" => $value, "report_status" => $report_status, "status" => "1");
                    $val_add = $this->add_result_model->master_fun_update("user_test_result", array("id", $check_val[0]['id']), $data);
                }
                if (!in_array($test_id, $send_approve_test_id_array)) {
                    $send_approve_test_id_array[] = $test_id;
                }
            }
            $check_is_approved = $this->add_result_model->master_fun_get_tbl_val("approve_job_test", array('job_fk' => $para_job_id, "test_fk" => $test_id, "status" => "1"), array("id", "asc"));
            if (empty($check_is_approved)) {
                $insert = $this->add_result_model->master_fun_insert("approve_job_test", array('job_fk' => $para_job_id, "test_fk" => $test_id, "approve_by" => $data["login_data"]["id"], "created_date" => date("Y-m-d H:i:s")));
            }
        }
        if ($value != '') {
            $check_val = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id, "status" => 1), array("id", "asc"));
            if (empty($check_val)) {
                $data = array(
                    "job_id" => $para_job_id,
                    "parameter_id" => $para_id,
                    "value" => $value,
                    "test_id" => $test_id,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s"),
                );
                $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
            } else {
                $data = array("value" => $value);
                $val_add = $this->add_result_model->master_fun_update("user_test_result", array("id", $check_val[0]['id']), $data);
            }
            if (!in_array($test_id, $send_approve_test_id_array)) {
                $send_approve_test_id_array[] = $test_id;
            }
        }

        $test_fk = $this->input->post('test_fk');
        foreach ($test_fk as $key) {
            $formula = $this->input->post('use_formula_' . $key);
            $on_new_page = $this->input->post('on_new_page_' . $key);
            if ($formula == '') {
                $formula = 0;
            }
            if ($on_new_page == '') {
                $on_new_page = 0;
            }
            /* Culture result add start */
            $culture_result = $this->input->post('culture_design_' . $key);
            $culture_result_fk = $this->input->post("culture_design_fk_" . $key);
            if (!empty($culture_result)) {
                $this->add_result_model->master_fun_update1("user_test_result", array('job_id' => $para_job_id, "parameter_id" => 0, "test_id" => $key), array("status" => "0"));
                $data = array(
                    "job_id" => $para_job_id,
                    "parameter_id" => "",
                    "value" => "",
                    "value2" => "",
                    "test_id" => $key,
                    "result" => $culture_result,
                    "result_fk" => $culture_result_fk,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s"),
                );
                $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
                if (!in_array($key, $send_approve_test_id_array)) {
                    $send_approve_test_id_array[] = $key;
                }
            }
            /* END */
            /* Nishit Graph Upload start */
            /* $graph_name = '';
              if ($files['graph_' . $key]['name'] != '') {
              $_FILES['userfile']['name'] = $files['graph_' . $key]['name'];
              $_FILES['userfile']['type'] = $files['graph_' . $key]['type'];
              $_FILES['userfile']['tmp_name'] = $files['graph_' . $key]['tmp_name'];
              $_FILES['userfile']['error'] = $files['graph_' . $key]['error'];
              $_FILES['userfile']['size'] = $files['graph_' . $key]['size'];
              $config['upload_path'] = './upload/report/graph/';
              $config['allowed_types'] = 'gif|jpg|png';
              $config['file_name'] = time() . $files['graph_' . $key]['name'];
              $config['file_name'] = str_replace(' ', '_', $config['file_name']);
              $config['overwrite'] = FALSE;
              $this->load->library('upload', $config);
              $this->upload->initialize($config);
              if (!$this->upload->do_upload()) {

              } else {
              $graph_name = $config['file_name'];
              }
              }
             */
            //print_r($_FILES['graph_' . $key]['name'][0]);
            $file_loop = count($_FILES['graph_' . $key]['name']);
            $file_upload = array();
            if (!empty($_FILES['graph_' . $key]['name'])) {
                for ($i = 0; $i < $file_loop; $i++) {
                    $_FILES['userfile']['name'] = $files['graph_' . $key]['name'][$i];
                    $_FILES['userfile']['type'] = $files['graph_' . $key]['type'][$i];
                    $_FILES['userfile']['tmp_name'] = $files['graph_' . $key]['tmp_name'][$i];
                    $_FILES['userfile']['error'] = $files['graph_' . $key]['error'][$i];
                    $_FILES['userfile']['size'] = $files['graph_' . $key]['size'][$i];
                    $config['upload_path'] = './upload/report/graph/';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['file_name'] = time() . $files['graph_' . $key]['name'][$i];
                    $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                    $config['overwrite'] = FALSE;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload()) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata("error", array($error));
                        //redirect('job-master/job-details/' . $cid);
                    } else {
                        $file_upload[] = array("test_fk" => $key, "pic" => $config['file_name']);
                        if (!in_array($key, $send_approve_test_id_array)) {
                            $send_approve_test_id_array[] = $key;
                        }
                    }
                }
            }
            //print_R($file_upload); die();
            /* Nishit Graph Upload end */
            if (!in_array($key, $test_id_array)) {
                if ($graph_name == '') {
                    $graph_name = $this->input->post('current_graph_' . $key);
                    ;
                }
                $data12 = array(
                    "job_fk" => $para_job_id,
                    "test_fk" => $key,
                    "use_formula" => $formula,
                    "on_new_page" => $on_new_page,
                    // "graph" => $graph_name,
                    "status" => 1
                );
                $test_id_array[] = $key;
                //  $val_add = $this->add_result_model->master_fun_insert("use_formula", $data12);
            }
            foreach ($file_upload as $file_key) {
                $data12 = array(
                    "job_fk" => $para_job_id,
                    "test_fk" => $key,
                    "pic" => $file_key["pic"],
                    "status" => 1,
                    "createddate" => date("Y-m-d H:i:s")
                );
                $val_add = $this->add_result_model->master_fun_insert("user_formula_pic", $data12);
            }
            $check_is_approved = $this->add_result_model->master_fun_get_tbl_val("approve_job_test", array('job_fk' => $para_job_id, "test_fk" => $key, "status" => "1"), array("id", "asc"));
            //print_r($check_is_approved); die();
            if (empty($check_is_approved)) {
                $insert = $this->add_result_model->master_fun_insert("approve_job_test", array('job_fk' => $para_job_id, "test_fk" => $key, "approve_by" => $data["login_data"]["id"], "created_date" => date("Y-m-d H:i:s")));
            }
            if (!in_array($key, $send_approve_test_id_array)) {
                $send_approve_test_id_array[] = $key;
            }
        }
        foreach ($send_approve_test_id_array as $tpkey) {
            if ($tpkey > 0) {
                $this->add_result_model->master_fun_update1("use_formula", array("job_fk" => $para_job_id, "test_fk" => $tpkey), array("test_status" => 2));
            }
        }
        $this->add_result_model->master_fun_insert("job_log", array("job_fk" => $para_job_id, "created_by" => "", "updated_by" => $adminid, "deleted_by" => "", "job_status" => '', "message_fk" => "32", "date_time" => date("Y-m-d H:i:s")));
        //$this->session->set_userdata("closeFancyBox", array('parent.close_popup1();'));
        redirect('add_result/all_test_approve_details?jid=' . $para_job_id . '&tid=' . $this->input->post('tid'));
    }

    function change_order() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $para_job_id = $this->input->post('para_job_id');
        $para = $this->input->post("para");
        $order = $this->input->post("order");
        $p_cnt = 0;
        foreach ($para as $value) {
            $this->add_result_model->master_fun_update('test_parameter', array('id', $value), array("order" => $order[$p_cnt]));
            $p_cnt++;
        }
        $this->add_result_model->master_fun_insert("job_log", array("job_fk" => $para_job_id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "16", "date_time" => date("Y-m-d H:i:s")));

        redirect('add_result/test_details/' . $para_job_id);
    }

    function pdf_report($id) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['cid'] = $this->uri->segment(3);
        $data["type"] = $this->input->get("type");
        $data['query'] = $this->add_result_model->job_details($data['cid']);
        $branch_fk = $data['query'][0]["branch_fk"];
        $data['user_booking_info'] = $this->add_result_model->master_fun_get_tbl_val("booking_info", array('status' => 1, 'id' => $data['query'][0]["booking_info"]), array("id", "asc"));
        $data['user_data'] = $this->add_result_model->master_fun_get_tbl_val("customer_master", array('status' => 1, 'id' => $data['query'][0]["custid"]), array("id", "asc"));
//        if (empty($data['user_data'][0]["gender"]) && empty($data['user_data'][0]["age"])) {
//            $data['user_data'][0]["gender"] = 'male';
//            $data['user_data'][0]["age"] = 24;
//        }
        if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
            $data['user_family_info'] = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
            $data['user_data'][0]["gender"] = $data['user_family_info'][0]["gender"];
            $data['user_data'][0]["age"] = $data['user_family_info'][0]["age"];
            $data['user_data'][0]["age_type"] = $data['user_family_info'][0]["age_type"];
            $data['user_data'][0]["full_name"] = $data['user_family_info'][0]["name"];
            $data['user_data'][0]["email"] = $data['user_family_info'][0]["email"];
            $data['user_data'][0]["phone"] = $data['user_family_info'][0]["phone"];
            $data['user_data'][0]["dob"] = $data['user_family_info'][0]["dob"];
        }
        $get_approved_test_list = $this->add_result_model->master_fun_get_tbl_val("approve_job_test", array('status' => '1', 'job_fk' => $data['cid']), array("id", "asc"));
        $approved_test = array();
        foreach ($get_approved_test_list as $at_key) {
            $approved_test[] = $at_key["test_fk"];
        }
//        if (empty($data['user_data'][0]["dob"])) {
//            $data['user_data'][0]["dob"] = '1992-09-30';
//        }
        /* Check bitrth date start */
        $processing_date = $this->add_result_model->get_val("SELECT IF(job_log.date_time!='',DATE_FORMAT(job_log.date_time,'%Y-%m-%d'),DATE_FORMAT(`job_master`.`date`,'%Y-%m-%d')) AS `date` FROM `job_master` LEFT JOIN `job_log` ON `job_log`.`job_fk`=`job_master`.`id` AND `job_log`.`status`='1' AND `job_log`.`job_status`='6-7' WHERE `job_master`.`status`!='0' AND `job_master`.`id`='" . $data['cid'] . "'");
        $pdate = $processing_date[0]["date"];
        if ($data['user_data'][0]["dob"] != '') {
            $this->load->library("util");
            $util = new Util;
            $age = $util->get_ref_age($data['user_data'][0]["dob"], $pdate);
            if ($age[0] != 0) {
                $data['user_data'][0]["age"] = $age[0];
                $data['user_data'][0]["age_type"] = 'Y';
            }
            if ($age[0] == 0 && $age[1] != 0) {
                $data['user_data'][0]["age"] = $age[1];
                $data['user_data'][0]["age_type"] = 'M';
            }
            if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
                $data['user_data'][0]["age"] = $age[2];
                $data['user_data'][0]["age_type"] = 'D';
            }
        } else {
            $data['user_data'][0]["age"] = '-';
            $data['user_data'][0]["age_type"] = '';
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
                $get_test_parameter = $this->add_result_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' order by `test_parameter`.order asc");
                //print_R($get_test_parameter); die();
                $pid = array();
                foreach ($get_test_parameter as $tp_key) {
                    if (!empty($tp_key["parameter_fk"])) {
                        $pid[] = $tp_key["parameter_fk"];
                    }
                }
                if (!empty($pid)) {
                    $para = $this->add_result_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
                    $cnt_1 = 0;
                    foreach ($para as $para_key) {
                        $formula = $this->add_result_model->get_val("SELECT * from use_formula where test_fk='" . $tst_id . "' and job_fk='" . $data['cid'] . "' and status='1'");
                        $get_test_parameter[$cnt_1]['use_formula'] = $formula[0]["use_formula"];
                        $get_test_parameter[$cnt_1]['on_new_page'] = $formula[0]["on_new_page"];
                        /* Report type start */
                        $culure_design = $this->add_result_model->get_val("SELECT id,title,html FROM `parameter_culture` WHERE center='" . $processing_center . "' AND test_fk='" . $tst_id . "' AND STATUS='1'");
                        $get_test_parameter[$cnt_1]['culture_design'] = $culure_design;
                        /* End */
                        $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                        $get_test_parameter[$cnt_1]['graph'] = $graph_pic;
                        $get_test_parameter1 = $get_test_parameter[$cnt_1];
//echo "SELECT * from user_test_result where test_id='".$tst_id."' and parameter_id='" . $para_key["id"] . "' and job_id='".$data['cid']."' and  status='1'";
                        $para_user_val = $this->add_result_model->get_val("SELECT * from user_test_result where test_id='" . $tst_id . "' and parameter_id='" . $para_key["id"] . "' and job_id='" . $data['cid'] . "' and  status='1'");
                        $para[$cnt_1]["user_val"] = $para_user_val;
                        $para_culture_val = $this->add_result_model->get_val("SELECT * from user_test_result where test_id='" . $tst_id . "' and job_id='" . $data['cid'] . "' and result is not NULL and status='1'");
                        $para[$cnt_1]["user_culture_val"] = $para_culture_val;
                        $para_ref_rng = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE `status`='1' AND parameter_fk='" . $para_key["id"] . "' order by gender asc");
                        $final_qry = "SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                        /* if ($data['user_data'][0]["age"] > 0 && $data['user_data'][0]["age"] < 6 && $data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          } else if ($data['user_data'][0]["age"] > 5 && $data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          $data["common"] = 0;
                          } else if ($data['user_data'][0]["age"] > 0 && $data['user_data'][0]["age"] < 8 && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                          //$get_both_age = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='117' AND gender='B' AND `no_period` > ".$data['user_data'][0]["age"]." AND `type_period`='Y' ORDER BY id ASC");
                          $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          //$get_male_age = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='117' AND gender='M' AND `no_period` > ".$data['user_data'][0]["age"]." AND `type_period`='Y' ORDER BY id ASC");
                          //print_r($get_male_age);
                          $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } */
                        if ($data['user_data'][0]["age_type"] == 'D') {
                            $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                        } else if ($data['user_data'][0]["age_type"] == 'M') {
                            $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                            $data["common"] = 0;
                        } else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                            $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                            $data["common"] = 0;
                        } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                            $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                            $data["common"] = 0;
                        } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                            $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                            $data["common"] = 0;
                        }
                        //$final_qry = $final_qry . " AND is_group='1' ";
                        $final_qry = $final_qry . " ORDER BY `type_period` ASC limit 0,1";
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
                    $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                    $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                    $get_test_parameter1[0]['graph'] = $graph_pic;
                    $new_data_array[] = $get_test_parameter1[0];
                }

                $cnt++;
            }
        }
//echo "<pre>"; print_r($new_data_array); die();
        $data["new_data_array"] = $new_data_array;
        $data['result_list'] = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('status' => 1, 'job_id' => $data['cid']), array("id", "asc"));
//print_r($data['result_list']); die();
//$data['unit_list'] = $this->add_result_model->unit_list();
//$new_time = date("Y-m-d H:i:s", strtotime('+3 hours'));
        if ($data['type'] == 'wlp') {
            $pdfFilePath = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result_wlpd.pdf";
        } else {
            $pdfFilePath = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result.pdf";
        }
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '128M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        $html = $this->load->view('result_pdf', $data, true); // render the view into HTML 
//$param = '"en-GB-x","A4","","",10,10,0,10,6,3,"P"'; // Landscape
//$lorem = utf8_encode($html); // render the view into HTML
//$html = "<!DOCTYPE html>                         <html><body>\u0627\u0644\u0643\u0647\u0631\u0628\u0627\u0621 \u0648 \u0627\u0644\u0633\u0628\u0627\u0643\u0629</body></html>      ";
        if (file_exists($pdfFilePath)) {
            $this->delete_downloadfile($pdfFilePath);
        }
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;

        $pdf->autoLangToFont = true;

        $name = "DR. Self";
        if ($data["query"][0]['dname'] != "") {
            $name = ucfirst($data["query"][0]['dname']);
        }
        $base_url = base_url();
        if ($data['type'] == 'wlp') {
            $content = $this->add_result_model->master_fun_get_tbl_val("pdf_design", array('branch_fk' => $branch_fk), array("id", "asc"));
            //print_r($content); die();
            $find = array(
                '/{{BARCODE}}/',
                '/{{CUSTID}}/',
                '/{{REGDATE}}/',
                '/{{COLLECTIONON}}/',
                '/{{NAME}}/',
                '/{{REPORTDATE}}/',
                '/{{AGE}}/',
                '/{{GENDER}}/',
                '/{{REFFERBY}}/',
                '/{{LOCATION}}/',
                '/{{TELENO}}/'
            );
            $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
            $logo_url = $base_url . 'user_assets/images/logoaastha.png';
            $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
            $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
            $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
            $replace = array(
                'pdf_barcode.png',
                $id,
                date("d-M-Y g:i", strtotime($data["query"][0]['regi_date'])),
                date("d-M-Y g:i", strtotime($data["query"][0]['date'])),
                strtoupper($data["user_data"][0]['full_name']),
                date('d-M-Y'),
                $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
                strtoupper($data["user_data"][0]['gender']),
                strtoupper($name),
                strtoupper($data["query"][0]['test_city_name']),
                $data["user_data"][0]['mobile']
            );
            $header = preg_replace($find, $replace, $content[0]["header"]);


            $pdf->SetHTMLHeader($header);
            $pdf->AddPage('p', // L - landscape, P - portrait
                    '', '', '', '', 5, // margin_left
                    5, // margin right
                    72, // margin top
                    72, // margin bottom
                    2, // margin header 
                    2); // margin footer
            $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
            $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
            $emailimg = $base_url . 'user_assets/images/email-icon.png';
            $webimg = $base_url . 'user_assets/images/web-icon.png';
            $lastimg = $base_url . 'user_assets/images/lastimg.png';
            $pdf->SetHTMLFooter($content[0]["footer"]);
        } else {
            if ($branch_fk == 1 || $branch_fk == 2 || $branch_fk == 6 || $branch_fk == 7 || $branch_fk == 8 || $branch_fk == 9) {
                $pdf_id = 1;
            } else {
                $pdf_id = 11;
            }
            $content = $this->add_result_model->master_fun_get_tbl_val("pdf_design", array('id' => $pdf_id), array("id", "asc"));
            $find = array(
                '/{{BARCODE}}/',
                '/{{CUSTID}}/',
                '/{{REGDATE}}/',
                '/{{COLLECTIONON}}/',
                '/{{NAME}}/',
                '/{{REPORTDATE}}/',
                '/{{AGE}}/',
                '/{{GENDER}}/',
                '/{{REFFERBY}}/',
                '/{{LOCATION}}/',
                '/{{TELENO}}/'
            );
            $replace = array(
                'pdf_barcode.png',
                $id,
                date("d-M-Y g:i", strtotime($data["query"][0]['regi_date'])),
                date("d-M-Y g:i", strtotime($data["query"][0]['date'])),
                strtoupper($data["user_data"][0]['full_name']),
                date('d-M-Y'),
                $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
                strtoupper($data["user_data"][0]['gender']),
                strtoupper($name),
                strtoupper($data["query"][0]['test_city_name']),
                $data["user_data"][0]['mobile']
            );
            $header = preg_replace($find, $replace, $content[0]["header"]);

            $pdf->SetHTMLHeader($header);
            $pdf->AddPage('p', // L - landscape, P - portrait
                    '', '', '', '', 5, // margin_left
                    5, // margin right
                    72, // margin top
                    72, // margin bottom
                    2, // margin header 
                    2); // margin footer
            $pdf->SetHTMLFooter($content[0]["footer"]);
        }
//$pdf->SetDirectionality('rtl');
        /* $pdf->AddPage('P', // L - landscape, P - portrait
          '', '', '', '', 00, // margin_left
          0, // margin right
          0, // margin top
          0, // margin bottom
          0, // margin header
          0); */

//  $pdf->SetDisplayMode('fullpage');
// $pdf->h2toc = array('H2' => 0);
//nishit index start
// $html = '';
// Split $lorem into words
        //echo $html; die();
        $pdf->WriteHTML($html);
//nishit index end
//   $pdf->debug = true;
//$pdf->SetFooter('www.' . $_SERVER['HTTP_HOST'] . '||' . $new_time); // Add a footer for good measure <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
// $pdf->WriteHTML($html); // write the HTML into the PDF
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        if ($data['type'] == 'wlp') {
            $name = $data['query'][0]['order_id'] . "_result_wlpd.pdf";
        } else {
            $name = $data['query'][0]['order_id'] . "_result.pdf";
        }
        $count = $this->add_result_model->master_fun_get_tbl_val("report_master", array('job_fk' => $cid), array("id", "asc"));
        if (!empty($count)) {
            $data1 = array('job_fk' => $cid, 'report' => $name, 'status' => 1, "original" => $name, "type" => "c", "updated_date" => date("Y-m-d H:i:s"));
//$this->add_result_model->master_fun_update('report_master', array('job_fk', $cid), $data1);
        } else {
            $data1 = array('job_fk' => $cid, 'report' => $name, 'status' => 1, "original" => $name, "type" => "c", "created_date" => date("Y-m-d H:i:s"));
//$this->add_result_model->master_fun_insert("report_master", $data1);
        }
        if ($data['type'] == 'wlp') {
            $downld = $this->_push_file($pdfFilePath, $data['query'][0]['order_id'] . "_result_wlpd.pdf");
            $this->delete_downloadfile($pdfFilePath);
            redirect("/upload/report/" . $data['query'][0]['order_id'] . "_result_wlpd.pdf");
        } else {
            $downld = $this->_push_file($pdfFilePath, $data['query'][0]['order_id'] . "_result.pdf");
            $this->delete_downloadfile($pdfFilePath);
            redirect("/upload/report/" . $data['query'][0]['order_id'] . "_result.pdf");
        }
    }

    function approve_report_old($id) {
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['cid'] = $id;
        $data["type"] = $this->input->get("type");
        $data['query'] = $this->add_result_model->job_details($data['cid']);
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
        if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
            $data['user_family_info'] = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
            $data['user_data'][0]["gender"] = $data['user_family_info'][0]["gender"];
            $data['user_data'][0]["age"] = $data['user_family_info'][0]["age"];
            $data['user_data'][0]["age_type"] = $data['user_family_info'][0]["age_type"];
            $data['user_data'][0]["full_name"] = $data['user_family_info'][0]["name"];
            $data['user_data'][0]["email"] = $data['user_family_info'][0]["email"];
            $data['user_data'][0]["phone"] = $data['user_family_info'][0]["phone"];
            $data['user_data'][0]["dob"] = $data['user_family_info'][0]["dob"];
        }

        /* Check bitrth date start */
        $processing_date = $this->add_result_model->get_val("SELECT IF(job_log.date_time!='',DATE_FORMAT(job_log.date_time,'%Y-%m-%d'),DATE_FORMAT(`job_master`.`date`,'%Y-%m-%d')) AS `date` FROM `job_master` LEFT JOIN `job_log` ON `job_log`.`job_fk`=`job_master`.`id` AND `job_log`.`status`='1' AND `job_log`.`job_status`='6-7' WHERE `job_master`.`status`!='0' AND `job_master`.`id`='" . $data['cid'] . "'");
        $pdate = $processing_date[0]["date"];
        $var = 0;
        if ($data['user_data'][0]["dob"] != '') {
            $var = 1;
            $this->load->library("util");
            $util = new Util;
            $age = $util->get_ref_age($data['user_data'][0]["dob"], $pdate);
            if ($age[0] != 0) {
                $data['user_data'][0]["age"] = $age[0];
                $data['user_data'][0]["age_type"] = 'Y';
            }
            if ($age[0] == 0 && $age[1] != 0) {
                $data['user_data'][0]["age"] = $age[1];
                $data['user_data'][0]["age_type"] = 'M';
            }
            if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
                $data['user_data'][0]["age"] = $age[2];
                $data['user_data'][0]["age_type"] = 'D';
            }
        } else {
            $data['user_data'][0]["age"] = '-';
            $data['user_data'][0]["age_type"] = '';
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
                //$get_test_parameter = $this->add_result_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' order by `test_parameter`.order asc");
                $get_test_parameter = $this->add_result_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name`,test_department_master.`name` AS 'department_name' FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id`     LEFT JOIN  `test_department_master` ON test_department_master.`id`=`test_master`.`department_fk`  WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' order by `test_parameter`.order asc");


                $pid = array();
                foreach ($get_test_parameter as $tp_key) {
                    if (!empty($tp_key["parameter_fk"])) {
                        $pid[] = $tp_key["parameter_fk"];
                    }
                }
                if (!empty($pid)) {
                    $para = $this->add_result_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
                    $cnt_1 = 0;
                    foreach ($para as $para_key) {
                        $formula = $this->add_result_model->get_val("SELECT * from use_formula where test_fk='" . $tst_id . "' and job_fk='" . $data['cid'] . "' and status='1'");
                        $get_test_parameter[$cnt_1]['use_formula'] = $formula[0]["use_formula"];
                        $get_test_parameter[$cnt_1]['on_new_page'] = $formula[0]["on_new_page"];
                        /* Report type start */
                        $culure_design = $this->add_result_model->get_val("SELECT id,title,html FROM `parameter_culture` WHERE center='" . $processing_center . "' AND test_fk='" . $tst_id . "' AND STATUS='1'");
                        $get_test_parameter[$cnt_1]['culture_design'] = $culure_design;
                        /* End */
                        $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                        $get_test_parameter[$cnt_1]['graph'] = $graph_pic;
                        $get_test_parameter1 = $get_test_parameter[$cnt_1];
//echo "SELECT * from user_test_result where test_id='".$tst_id."' and parameter_id='" . $para_key["id"] . "' and job_id='".$data['cid']."' and  status='1'";
                        $para_user_val = $this->add_result_model->get_val("SELECT * from user_test_result where test_id='" . $tst_id . "' and parameter_id='" . $para_key["id"] . "' and job_id='" . $data['cid'] . "' and  status='1'");
                        $para[$cnt_1]["user_val"] = $para_user_val;
                        $para_culture_val = $this->add_result_model->get_val("SELECT * from user_test_result where test_id='" . $tst_id . "' and job_id='" . $data['cid'] . "' and result is not NULL and status='1'");
                        $para[$cnt_1]["user_culture_val"] = $para_culture_val;
                        $para_ref_rng = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE `status`='1' AND parameter_fk='" . $para_key["id"] . "' order by gender asc");
                        $final_qry = "SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                        if ($data['user_data'][0]["age"] == '') {
                            $data['user_data'][0]["age"] = 0;
                        }
                        if ($data['user_data'][0]["age_type"] == 'D') {
                            $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                        } else if ($data['user_data'][0]["age_type"] == 'M') {
                            $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                            $data["common"] = 0;
                        } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                            $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                            $data["common"] = 0;
                        } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                            $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                            $data["common"] = 0;
                        } else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                            $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                            $data["common"] = 0;
                        }
                        $final_qry = $final_qry . " ORDER BY `type_period` ASC limit 0,1";
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
                    $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                    $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                    $get_test_parameter1[0]['graph'] = $graph_pic;
                    $new_data_array[] = $get_test_parameter1[0];
                }

                $cnt++;
            }
        }
        //print_R($new_data_array); die();
        $data["new_data_array"] = $new_data_array;
        $data['result_list'] = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('status' => 1, 'job_id' => $data['cid']), array("id", "asc"));
        $pdfFilePath = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result_wlpd.pdf";
        $pdfFilePath1 = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result.pdf";
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '512M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        $html = $this->load->view('result_pdf', $data, true); // render the view into HTML 
        if (file_exists($pdfFilePath)) {
            $this->delete_downloadfile($pdfFilePath);
        }
        if (file_exists($pdfFilePath1)) {
            $this->delete_downloadfile($pdfFilePath1);
        }
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;
        $pdf->autoLangToFont = true;

        $name = "DR. Self";
        if ($data["query"][0]['dname'] != "") {
            $name = ucfirst($data["query"][0]['dname']);
        }
        $base_url = base_url();

        $content = $this->add_result_model->master_fun_get_tbl_val("pdf_design", array('branch_fk' => $branch_fk), array("id", "asc"));
        //print_r($content); die();
        $find = array(
            '/{{BARCODE}}/',
            '/{{CUSTID}}/',
            '/{{REGDATE}}/',
            '/{{COLLECTIONON}}/',
            '/{{NAME}}/',
            '/{{REPORTDATE}}/',
            '/{{AGE}}/',
            '/{{GENDER}}/',
            '/{{REFFERBY}}/',
            '/{{LOCATION}}/',
            '/{{TELENO}}/'
        );
        $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
        $logo_url = $base_url . 'user_assets/images/logoaastha.png';
        $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
        $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
        $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
        $replace = array(
            'pdf_barcode.png',
            $id . " ( " . $data["query"][0]['order_id'] . " ) ",
            date("d-M-Y g:i", strtotime($data["query"][0]['regi_date'])),
            date("d-M-Y g:i", strtotime($data["query"][0]['date'])),
            strtoupper($data["user_data"][0]['full_name']),
            date('d-M-Y'),
            $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
            strtoupper($data["user_data"][0]['gender']),
            strtoupper($name),
            strtoupper($data["query"][0]['test_city_name']),
            $data["user_data"][0]['mobile']
        );
        $header = preg_replace($find, $replace, $content[0]["header"]);


        $pdf->SetHTMLHeader($header);
        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 5, // margin_left
                5, // margin right
                72, // margin top
                72, // margin bottom
                2, // margin header 
                2); // margin footer
        $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
        $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
        $emailimg = $base_url . 'user_assets/images/email-icon.png';
        $webimg = $base_url . 'user_assets/images/web-icon.png';
        $lastimg = $base_url . 'user_assets/images/lastimg.png';
        $pdf->SetHTMLFooter($content[0]["footer"]);
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        $name1 = $this->without_approve_report($data);

        $name = $data['query'][0]['order_id'] . "_result_wlpd.pdf";
        $name_orgnl = $data['user_data'][0]["full_name"] . "_result_with_latterhead.pdf";
        $name_orgnl1 = $data['user_data'][0]["full_name"] . "_result.pdf";
        $count = $this->add_result_model->master_fun_get_tbl_val("report_master", array('job_fk' => $id), array("id", "asc"));
        if (!empty($count)) {
            $data1 = array('job_fk' => $id, 'report' => $name, 'status' => 1, "original" => $name_orgnl, 'without_laterpad' => $name1, "without_laterpad_original" => $name_orgnl1, "type" => "c", "updated_date" => date("Y-m-d H:i:s"));
            $this->add_result_model->master_fun_update('report_master', array('job_fk', $id), $data1);
        } else {
            $data1 = array('job_fk' => $id, 'report' => $name, 'status' => 1, "original" => $name_orgnl, "type" => "c", 'without_laterpad' => $name1, "without_laterpad_original" => $name_orgnl1, "created_date" => date("Y-m-d H:i:s"));
            $this->add_result_model->master_fun_insert("report_master", $data1);
        }
        $this->add_result_model->master_fun_update('job_master', array('id', $id), array("report_approve_by" => $data["login_data"]["id"]));
        //print_R($data1); die();
        $this->session->set_flashdata("success", array("Report successfully attached."));
        $this->add_result_model->master_fun_insert("job_log", array("job_fk" => $id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "17", "date_time" => date("Y-m-d H:i:s")));

        redirect("job-master/job-details/" . $id);
    }

    function approve_report($id) {
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['cid'] = $id;
        $data["type"] = $this->input->get("type");
        $data['query'] = $this->add_result_model->job_details($data['cid']);
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
        if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
            $data['user_family_info'] = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
            $data['user_data'][0]["gender"] = $data['user_family_info'][0]["gender"];
            $data['user_data'][0]["age"] = $data['user_family_info'][0]["age"];
            $data['user_data'][0]["age_type"] = $data['user_family_info'][0]["age_type"];
            $data['user_data'][0]["full_name"] = $data['user_family_info'][0]["name"];
            $data['user_data'][0]["email"] = $data['user_family_info'][0]["email"];
            $data['user_data'][0]["phone"] = $data['user_family_info'][0]["phone"];
            $data['user_data'][0]["dob"] = $data['user_family_info'][0]["dob"];
        }
        /* Check bitrth date start */
        $processing_date = $this->add_result_model->get_val("SELECT IF(job_log.date_time!='',DATE_FORMAT(job_log.date_time,'%Y-%m-%d'),DATE_FORMAT(`job_master`.`date`,'%Y-%m-%d')) AS `date` FROM `job_master` LEFT JOIN `job_log` ON `job_log`.`job_fk`=`job_master`.`id` AND `job_log`.`status`='1' AND `job_log`.`job_status`='6-7' WHERE `job_master`.`status`!='0' AND `job_master`.`id`='" . $data['cid'] . "'");
        $pdate = $processing_date[0]["date"];
        $this->load->library("util");
        $util = new Util;
        $age = $util->get_ref_age($data['user_data'][0]["dob"], $pdate);
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
        if ($data['cid'] == 24679) {
            $tid = array_reverse($tid);
        }
        $cnt = 0;
        $new_data_array = array();
        foreach ($tid as $tst_id) {
            if (in_array($tst_id, $approved_test)) {
                $get_test_parameter = $this->add_result_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name`,`test_master`.report_type,test_department_master.`name` AS 'department_name' FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id`     LEFT JOIN  `test_department_master` ON test_department_master.`id`=`test_master`.`department_fk`  WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' and test_parameter.center='" . $processing_center . "' order by `test_parameter`.order asc");

                $pid = array();
                foreach ($get_test_parameter as $tp_key) {
                    if (!empty($tp_key["parameter_fk"])) {
                        $pid[] = $tp_key["parameter_fk"];
                    }
                }
                if (!empty($pid)) {
                    $para = $this->add_result_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
                    $cnt_1 = 0;
                    foreach ($para as $para_key) {
                        $formula = $this->add_result_model->get_val("SELECT * from use_formula where test_fk='" . $tst_id . "' and job_fk='" . $data['cid'] . "' and status='1'");
                        $get_test_parameter[$cnt_1]['use_formula'] = $formula[0]["use_formula"];
                        $get_test_parameter[$cnt_1]['on_new_page'] = $formula[0]["on_new_page"];
                        /* Report type start */
                        $culure_design = $this->add_result_model->get_val("SELECT id,title,html FROM `parameter_culture` WHERE center='" . $processing_center . "' AND test_fk='" . $tst_id . "' AND STATUS='1'");
                        $get_test_parameter[$cnt_1]['culture_design'] = $culure_design;
                        /* End */
                        $get_test_parameter[$cnt_1]['is_culture'] = $formula[0]["is_culture"];
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
                        /* if ($data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          } else if ($data['user_data'][0]["age_type"] == 'M') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } */
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
                    $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                    $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                    $get_test_parameter1[0]['graph'] = $graph_pic;
                    $new_data_array[] = $get_test_parameter1[0];
                }

                $cnt++;
            }
        }
        if ($_REQUEST["debug"] == 1) {
            echo "<pre>";
            print_R($new_data_array);
            die();
        }
        $data["new_data_array"] = $new_data_array;
        $data['result_list'] = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('status' => 1, 'job_id' => $data['cid']), array("id", "asc"));
        $pdfFilePath = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result_wlpd.pdf";
        $pdfFilePath1 = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result.pdf";
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '512M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        $html = $this->load->view('result_pdf', $data, true); // render the view into HTML 

        if (file_exists($pdfFilePath)) {
            $this->delete_downloadfile($pdfFilePath);
        }
        if (file_exists($pdfFilePath1)) {
            $this->delete_downloadfile($pdfFilePath1);
        }
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;
        $pdf->autoLangToFont = true;

        $name = "DR. Self";
        if ($data["query"][0]['dname'] != "") {
            $name = ucfirst($data["query"][0]['dname']);
        }
        if ($data["query"][0]["model_type"] == 2) {
            $b2b_drname = $this->add_result_model->master_fun_get_tbl_val("sample_job_master", array('barcode_fk' => $data["query"][0]["b2b_id"]), array("id", "asc"));
            $name = ucfirst($b2b_drname[0]['doctor']);
        }
        $base_url = base_url();
        $content = $this->add_result_model->master_fun_get_tbl_val("pdf_design", array('branch_fk' => $branch_fk), array("id", "asc"));
        if ($_REQUEST["debug"] == 2) {
            echo "<pre>";
            echo $this->db->last_query();
            print_R($content);
            die();
        }
        $data["content"] = $content;
        //print_r($content); die();
        $find = array(
            '/{{BARCODE}}/',
            '/{{CUSTID}}/',
            '/{{REGDATE}}/',
            '/{{COLLECTIONON}}/',
            '/{{NAME}}/',
            '/{{REPORTDATE}}/',
            '/{{AGE}}/',
            '/{{GENDER}}/',
            '/{{REFFERBY}}/',
            '/{{LOCATION}}/',
            '/{{TELENO}}/'
        );
        $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
        $logo_url = $base_url . 'user_assets/images/logoaastha.png';
        $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
        $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
        $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
        $replace = array(
            'pdf_barcode.png',
            $id . " ( " . $data["query"][0]['order_id'] . " ) ",
            date("d-M-Y g:i", strtotime($data["query"][0]['regi_date'])),
            date("d-M-Y g:i", strtotime($data["query"][0]['date'])),
            strtoupper($data["user_data"][0]['full_name']),
            date('d-M-Y'),
            $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
            strtoupper($data["user_data"][0]['gender']),
            strtoupper($name),
            strtoupper($data["query"][0]['test_city_name']),
            $data["user_data"][0]['mobile']
        );
        $header = preg_replace($find, $replace, $content[0]["header"]);
        /* echo $header;
          echo $html;
          echo $content[0]["footer"]; die(); */
        $pdf->SetHTMLHeader($header);
        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 7, // margin_left
                7, // margin right
                72, // margin top
                72, // margin bottom
                2, // margin header 
                2); // margin footer
        /* if($data["login_data"]["id"]==17){
          echo $header;
          echo $html;
          echo $content[0]["footer"];die();
          } */
        $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
        $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
        $emailimg = $base_url . 'user_assets/images/email-icon.png';
        $webimg = $base_url . 'user_assets/images/web-icon.png';
        $lastimg = $base_url . 'user_assets/images/lastimg.png';
        $pdf->SetHTMLFooter($content[0]["footer"]);
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can

        $name1 = $this->without_approve_report($data);

        $name = $data['query'][0]['order_id'] . "_result_wlpd.pdf";
        $name_orgnl = $data['user_data'][0]["full_name"] . "_result_with_latterhead.pdf";
        $name_orgnl1 = $data['user_data'][0]["full_name"] . "_result.pdf";
        $count = $this->add_result_model->master_fun_get_tbl_val("report_master", array('job_fk' => $id), array("id", "asc"));
        if (!empty($count)) {
            $data1 = array('job_fk' => $id, 'report' => $name, 'status' => 1, "original" => $name_orgnl, 'without_laterpad' => $name1, "without_laterpad_original" => $name_orgnl1, "type" => "c", "updated_date" => date("Y-m-d H:i:s"));
            $this->add_result_model->master_fun_update('report_master', array('job_fk', $id), $data1);
        } else {
            $data1 = array('job_fk' => $id, 'report' => $name, 'status' => 1, "original" => $name_orgnl, "type" => "c", 'without_laterpad' => $name1, "without_laterpad_original" => $name_orgnl1, "created_date" => date("Y-m-d H:i:s"));
            $this->add_result_model->master_fun_insert("report_master", $data1);
        }
        $this->add_result_model->master_fun_update('job_master', array('id', $id), array("report_approve_by" => $data["login_data"]["id"]));
        //print_R($data1); die();
        $this->session->set_flashdata("success", array("Report successfully attached."));
        $this->add_result_model->master_fun_insert("job_log", array("job_fk" => $id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "17", "date_time" => date("Y-m-d H:i:s")));

        redirect("job-master/job-details/" . $id);
    }

    function without_approve_report($data) {
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        $pdfFilePath = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result.pdf";
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '512M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">


        if (file_exists($pdfFilePath)) {
            $this->delete_downloadfile($pdfFilePath);
        }
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;
        $pdf->autoLangToFont = true;
        $pdf->setAutoBottomMargin = 'stretch';
        $name = "DR. Self";
        if ($data["query"][0]['dname'] != "") {
            $name = ucfirst($data["query"][0]['dname']);
        }
        if ($data["query"][0]["model_type"] == 2) {
            $b2b_drname = $this->add_result_model->master_fun_get_tbl_val("sample_job_master", array('barcode_fk' => $data["query"][0]["b2b_id"]), array("id", "asc"));
            $name = ucfirst($b2b_drname[0]['doctor']);
        }
        $base_url = base_url();
        $branch_fk = $data["branch_fk"];

        $content = $data['content'];
        $find = array(
            '/{{BARCODE}}/',
            '/{{CUSTID}}/',
            '/{{REGDATE}}/',
            '/{{COLLECTIONON}}/',
            '/{{NAME}}/',
            '/{{REPORTDATE}}/',
            '/{{AGE}}/',
            '/{{GENDER}}/',
            '/{{REFFERBY}}/',
            '/{{LOCATION}}/',
            '/{{TELENO}}/'
        );
        if ($_REQUEST["debug"] == 3) {
            $replace = array(
                'pdf_barcode.png',
                $data['cid'] . " ( " . $data["query"][0]['order_id'] . " ) ",
                date("d-M-Y", strtotime($data["query"][0]['regi_date'])),
                date("d-M-Y", strtotime($data["collection_date"][0]['date_time'])),
                strtoupper($data["user_data"][0]['full_name']),
                "28-Aug-2017",
                $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
                strtoupper($data["user_data"][0]['gender']),
                strtoupper($name),
                strtoupper($data["query"][0]['test_city_name']),
                $data["user_data"][0]['mobile']
            );
        } else {
            $replace = array(
                'pdf_barcode.png',
                $data['cid'] . " ( " . $data["query"][0]['order_id'] . " ) ",
                date("d-M-Y", strtotime($data["query"][0]['regi_date'])),
                date("d-M-Y g:i A", strtotime($data["collection_date"])),
                strtoupper($data["user_data"][0]['full_name']),
                date("d-M-Y g:i A", strtotime($data["completed_date1"])),
                $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
                strtoupper($data["user_data"][0]['gender']),
                strtoupper($name),
                strtoupper($data["query"][0]['test_city_name']),
                $data["user_data"][0]['mobile']
            );
        }
        if($data['cid']=='116354'){
             $replace = array(
                'pdf_barcode.png',
                $data['cid'] . " ( " . $data["query"][0]['order_id'] . " ) ",
                date("d-M-Y", strtotime($data["query"][0]['regi_date'])),
                date("d-M-Y g:i A", strtotime($data["collection_date"])),
                strtoupper($data["user_data"][0]['full_name']),
                date("d-M-Y g:i A", strtotime($data["completed_date1"])),
                $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
                strtoupper('unspecified'),
                strtoupper($name),
                strtoupper($data["query"][0]['test_city_name']),
                $data["user_data"][0]['mobile']
            );
        }
        
       if($data['cid']=='203181'){
                  $replace = array(
                    'pdf_barcode.png',
                    "199738 ( AHM-28257 ) ",
                    "04-Oct-2019 3:37 PM",
                    "04-Oct-2019 3:37 PM",
                    strtoupper($data["user_data"][0]['full_name']),
                    "07-Oct-2019 5:03 PM",
                    $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
                    'FEMALE',
                    strtoupper($name),
                    strtoupper($data["query"][0]['test_city_name']),
                    $data["user_data"][0]['mobile']
                );
                  
            }
        
        
        $header = preg_replace($find, $replace, $content[0]["without_header"]);
        /* $pdf->SetHTMLHeader($header); */
        if ($branch_fk == 11) {
            $pdf->AddPage('p', // L - landscape, P - portrait
                    '', '', '', '', 5, // margin_left
                    5, // margin right
                    72, // margin top
                    80, // margin bottom
                    2, // margin header
                    2); // margin footer
        } else if ($branch_fk == 14) {
            $pdf->AddPage('p', // L - landscape, P - portrait
                    '', '', '', '', 5, // margin_left
                    5, // margin right
                    72, // margin top
                    80, // margin bottom
                    2, // margin header
                    2); // margin footer
        } else if ($branch_fk == 10) {
            $pdf->AddPage('p', // L - landscape, P - portrait
                    '', '', '', '', 5, // margin_left
                    5, // margin right
                    72, // margin top
                    80, // margin bottom
                    2, // margin header
                    2); // margin footer
        } else if ($branch_fk == 15) {
            $pdf->AddPage('p', // L - landscape, P - portrait
                    '', '', '', '', 5, // margin_left
                    5, // margin right
                    78, // margin top
                    80, // margin bottom
                    10, // margin header
                    2); // margin footer
        } else if ($branch_fk == 8) {
            $pdf->AddPage('p', // L - landscape, P - portrait
                    '', '', '', '', 5, // margin_left
                    5, // margin right
                    78, // margin top
                    50, // margin bottom
                    7, // margin header
                    3); // margin footer
        } else if ($branch_fk == 3 || $branch_fk == 4 || $branch_fk == 5 || $branch_fk == 12) {
            $pdf->AddPage('p', // L - landscape, P - portrait
                    '', '', '', '', 5, // margin_left
                    5, // margin right
                    78, // margin top
                    75, // margin bottom
                    5, // margin header
                    1); // margin footer
        } else if ($branch_fk == 34) {
            $pdf->AddPage('p', // L - landscape, P - portrait
                    '', '', '', '', 5, // margin_left
                    5, // margin right
                    78, // margin top
                    74, // margin bottom
                    8, // margin header 
                    2); // margin footer
        } else if ($branch_fk == 90) {

            $pdf->AddPage('p', // L - landscape, P - portrait
                    '', '', '', '', 10, // margin_left
                    5, // margin right
                    78, // margin top
                    74, // margin bottom
                    8, // margin header 
                    2); // margin footer
        } else {
            $pdf->AddPage('p', // L - landscape, P - portrait
                    '', '', '', '', 5, // margin_left
                    5, // margin right
                    72, // margin top
                    75, // margin bottom
                    2, // margin header
                    2); // margin footer
        }
        //echo $branch_fk; die();
        /* $pdf->SetHTMLFooter($content[0]["without_footer"]); */
        $data["pdfsize"] = $content[0]["withoutsize"];
        $data["header"] = $header;
        $data["footer"] = $content[0]["without_footer"];
        $html = $this->load->view('result_pdf', $data, true); // render the view into HTML 
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        return $data['query'][0]['order_id'] . "_result.pdf";
    }

    function preview_report($id) {
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['cid'] = $id;
        $data["type"] = $this->input->get("type");
        $data['query'] = $this->add_result_model->job_details1($data['cid']);

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
        if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
            $data['user_family_info'] = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
            $data['user_data'][0]["gender"] = $data['user_family_info'][0]["gender"];
            $data['user_data'][0]["age"] = $data['user_family_info'][0]["age"];
            $data['user_data'][0]["age_type"] = $data['user_family_info'][0]["age_type"];
            $data['user_data'][0]["full_name"] = $data['user_family_info'][0]["name"];
            $data['user_data'][0]["email"] = $data['user_family_info'][0]["email"];
            $data['user_data'][0]["phone"] = $data['user_family_info'][0]["phone"];
            $data['user_data'][0]["dob"] = $data['user_family_info'][0]["dob"];
        }
        /* Check bitrth date start */
        $processing_date = $this->add_result_model->get_val("SELECT IF(job_log.date_time!='',DATE_FORMAT(job_log.date_time,'%Y-%m-%d'),DATE_FORMAT(`job_master`.`date`,'%Y-%m-%d')) AS `date` FROM `job_master` LEFT JOIN `job_log` ON `job_log`.`job_fk`=`job_master`.`id` AND `job_log`.`status`='1' AND `job_log`.`job_status`='6-7' WHERE `job_master`.`status`!='0' AND `job_master`.`id`='" . $data['cid'] . "'");
        $pdate = $processing_date[0]["date"];
        $this->load->library("util");
        $util = new Util;
        $age = $util->get_ref_age($data['user_data'][0]["dob"], $pdate);
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
                $get_test_parameter = $this->add_result_model->get_val("SELECT 
  `test_parameter`.*,
  `test_master`.`test_name`,
  tmd.method AS test_method,
  test_master.PRINTING_NAME,
  `test_master`.report_type,
  `test_master`.department_fk,
  test_master.sample as sampletype,
  test_department_master.`name` AS 'department_name' 
FROM
  `test_parameter` 
  INNER JOIN `test_master` 
    ON `test_parameter`.`test_fk` = `test_master`.`id` 
  LEFT JOIN `test_department_master` 
    ON test_department_master.`id` = `test_master`.`department_fk` 
    LEFT JOIN `test_method` AS tmd 
    ON tmd.test_fk = test_master.id AND tmd.branch_fk='" . $data['processing_center'][0]["branch_fk"] . "' and tmd.status='1'
	LEFT JOIN branch_sample_type as btype
	ON btype.test_fk=test_master.id AND btype.branch_fk='" . $data['processing_center'][0]["branch_fk"] . "' and btype.status='1'
WHERE `test_parameter`.`status` = '1' 
  AND `test_master`.`status` = '1' 
  AND `test_parameter`.`test_fk` = '" . $tst_id . "' 
  AND test_parameter.center = '" . $processing_center . "' 
ORDER BY `test_parameter`.order ASC ");

                $pid = array();
                foreach ($get_test_parameter as $tp_key) {
                    if (!empty($tp_key["parameter_fk"])) {
                        $pid[] = $tp_key["parameter_fk"];
                    }
                }
                if (!empty($pid)) {
                    $para = $this->add_result_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
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
                        $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' and type='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
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
                    $get_test_parameter1 = $this->add_result_model->get_val("SELECT 
  tm.id AS test_fk,
  tm.test_name,
  tm.PRINTING_NAME,
  tm.sample as sampletype,
  tmd.method AS test_method
FROM
  `test_master` AS tm 
  LEFT JOIN `test_method` AS tmd 
    ON tmd.test_fk = tm.id AND tmd.branch_fk='" . $data['processing_center'][0]["branch_fk"] . "' and tmd.status='1'
LEFT JOIN branch_sample_type as btype
	ON btype.test_fk=tm.id AND btype.branch_fk='" . $data['processing_center'][0]["branch_fk"] . "' and btype.status='1'	
WHERE tm.id = '" . $tst_id . "'");
                    $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND type='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                    $get_test_parameter1[0]['graph'] = $graph_pic;
                    $new_data_array[] = $get_test_parameter1[0];
                }

                $cnt++;
            }
        }
        $data["new_data_array"] = $new_data_array;
        $data['result_list'] = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('status' => 1, 'job_id' => $data['cid']), array("id", "asc"));
        if ($data['type'] == 'wlp') {
            
        } else {
            
        }
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '128M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">

        $name = "DR. Self";
        if ($data["query"][0]['dname'] != "") {
            $name = ucfirst($data["query"][0]['dname']);
        }

        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;

        $pdf->autoLangToFont = true;
        //$pdf->SetWatermarkImage('airmedlogo_not_auth.png');
        $pdf->SetWatermarkText("This is preview copy and its unauthorised");
        $pdf->showWatermarkText = true;
        $pdf->setAutoBottomMargin = 'stretch';

        $base_url = base_url();
        if ($data['type'] == 'wlp') {

            $base_url = base_url();

            $pdfFilePath = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result_wlpd_preview.pdf";


            $content = $this->add_result_model->master_fun_get_tbl_val("pdf_preview_design", array('branch_fk' => $branch_fk), array("id", "asc"));

            $data["pdfsize"] = $content[0]["withsize"];

            $data["collection_date"] = $this->add_result_model->get_val("SELECT date_time FROM `job_log` WHERE `status`='1' AND `message_fk`='3' and `job_status` LIKE '%-7%' AND job_fk='" . $data['cid'] . "' order by id desc");
            $data["completed_date"] = $this->add_result_model->get_val("SELECT date_time FROM `job_log` WHERE `status`='1' AND `message_fk`='3' and `job_status` LIKE '%-2%' AND job_fk='" . $data['cid'] . "' order by id desc");
            if (!empty($data["completed_date"])) {
                $data["completed_date1"] = $data["completed_date"][0]["date_time"];
            } else {
                $data["completed_date1"] = date("Y-m-d H:i:s");
            }

            if (!empty($data["collection_date"])) {
                $data["collection_date"] = $data["collection_date"][0]["date_time"];
            } else {
                if (!empty($data["completed_date"])) {
                    $data["collection_date"] = $data["completed_date"][0]["date_time"];
                } else {
                    $data["collection_date"] = date("Y-m-d H:i:s");
                }
                $data["collection_date"] = date("Y-m-d H:i:s");
            }
            $data["content"] = $content;
            //print_r($content); die();
            $find = array(
                '/{{BARCODE}}/',
                '/{{CUSTID}}/',
                '/{{REGDATE}}/',
                '/{{COLLECTIONON}}/',
                '/{{NAME}}/',
                '/{{REPORTDATE}}/',
                '/{{AGE}}/',
                '/{{GENDER}}/',
                '/{{REFFERBY}}/',
                '/{{LOCATION}}/',
                '/{{TELENO}}/'
            );
            $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
            $logo_url = $base_url . 'user_assets/images/logoaastha.png';
            $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
            $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
            $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
            $replace = array(
                'pdf_barcode.png',
                $id . " ( " . $data["query"][0]['order_id'] . " ) ",
                date("d-M-Y", strtotime($data["query"][0]['regi_date'])),
                date("d-M-Y", strtotime($data["collection_date"])),
                strtoupper($data["user_data"][0]['full_name']),
                date("d-M-Y", strtotime($data["completed_date1"])),
                $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
                strtoupper($data["user_data"][0]['gender']),
                strtoupper($name),
                strtoupper($data["query"][0]['test_city_name']),
                $data["user_data"][0]['mobile']
            );
            $header = preg_replace($find, $replace, $content[0]["header"]);
            /* $pdf->SetHTMLHeader($header); */
            $pdf->AddPage('p', // L - landscape, P - portrait
                    '', '', '', '', 5, // margin_left
                    5, // margin right
                    72, // margin top
                    72, // margin bottom
                    2, // margin header 
                    2); // margin footer
            /* if($data["login_data"]["id"]==17){
              echo $header;
              echo $html;
              echo $content[0]["footer"];die();
              } */

            $data['header'] = $header;

            $data['footer'] = $content[0]["footer"];

            $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
            $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
            $emailimg = $base_url . 'user_assets/images/email-icon.png';
            $webimg = $base_url . 'user_assets/images/web-icon.png';
            $lastimg = $base_url . 'user_assets/images/lastimg.png';
            /* $pdf->SetHTMLFooter($content[0]["footer"]); */
        } else {
            if ($branch_fk == 1 || $branch_fk == 2 || $branch_fk == 6 || $branch_fk == 7 || $branch_fk == 8 || $branch_fk == 9 || $branch_fk == 12) {
                $pdf_id = 1;
            } else {
                $pdf_id = 11;
            }
            if ($branch_fk == 8) {
                $pdf_id = 14;
            }
            if ($branch_fk == 11) {
                $pdf_id = 16;
            }
            $content = $this->add_result_model->master_fun_get_tbl_val("pdf_preview_design", array('id' => $pdf_id), array("id", "asc"));
            $find = array(
                '/{{BARCODE}}/',
                '/{{CUSTID}}/',
                '/{{REGDATE}}/',
                '/{{COLLECTIONON}}/',
                '/{{NAME}}/',
                '/{{REPORTDATE}}/',
                '/{{AGE}}/',
                '/{{GENDER}}/',
                '/{{REFFERBY}}/',
                '/{{LOCATION}}/',
                '/{{TELENO}}/'
            );
            $replace = array(
                'pdf_barcode.png',
                $id,
                date("d-M-Y g:i", strtotime($data["query"][0]['regi_date'])),
                date("d-M-Y g:i", strtotime($data["query"][0]['date'])),
                strtoupper($data["user_data"][0]['full_name']),
                date('d-M-Y'),
                $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
                strtoupper($data["user_data"][0]['gender']),
                strtoupper($name),
                strtoupper($data["query"][0]['test_city_name']),
                $data["user_data"][0]['mobile']
            );

            $data["pdfsize"] = $content[0]["withsize"];

            $header = preg_replace($find, $replace, $content[0]["header"]);

            /* $pdf->SetHTMLHeader($header); */
            $pdf->AddPage('p', // L - landscape, P - portrait
                    '', '', '', '', 5, // margin_left
                    5, // margin right 
                    72, // margin top
                    72, // margin bottom
                    2, // margin header 
                    2); // margin footer
            /* $pdf->SetHTMLFooter($content[0]["footer"]); */

            $data['header'] = $header;

            $data['footer'] = $content[0]["footer"];

            $pdfFilePath = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result.pdf";
            $data["pdfsize"] = $content[0]["withoutsize"];
        }

        $html = $this->load->view('result_pdf', $data, true); // render the view into HTML 
        if (file_exists($pdfFilePath)) {
            $this->delete_downloadfile($pdfFilePath);
        }

        //$pdf->showWatermarkImage = true;

        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        if ($data['type'] == 'wlp') {
            $name = $data['query'][0]['order_id'] . "_result_wlpd.pdf";
        } else {
            $name = $data['query'][0]['order_id'] . "_result.pdf";
        }
        $count = $this->add_result_model->master_fun_get_tbl_val("report_master", array('job_fk' => $cid), array("id", "asc"));
        if (!empty($count)) {
            $data1 = array('job_fk' => $cid, 'report' => $name, 'status' => 1, "original" => $name, "type" => "c", "updated_date" => date("Y-m-d H:i:s"));
        } else {
            $data1 = array('job_fk' => $cid, 'report' => $name, 'status' => 1, "original" => $name, "type" => "c", "created_date" => date("Y-m-d H:i:s"));
        }
        $this->add_result_model->master_fun_insert("job_log", array("job_fk" => $id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "18", "date_time" => date("Y-m-d H:i:s")));

        if ($data['type'] == 'wlp') {
//            $downld = $this->_push_file($pdfFilePath, $data['query'][0]['order_id'] . "_result_wlpd.pdf");
//            $this->delete_downloadfile($pdfFilePath);
            redirect("/upload/report/" . $data['query'][0]['order_id'] . "_result_wlpd_preview.pdf?" . rand(11111, 99999));
        } else {
//            $downld = $this->_push_file($pdfFilePath, $data['query'][0]['order_id'] . "_result.pdf");
//             $this->delete_downloadfile($pdfFilePath);
            redirect("/upload/report/" . $data['query'][0]['order_id'] . "_result.pdf?" . rand(11111, 99999));
        }
    }

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

    function delete_parameter($pid, $jid, $tid) {
        $data["login_data"] = logindata();
        $data1 = array('status' => 0);
        $update = $this->add_result_model->master_fun_update12('test_parameter', array('parameter_fk', $pid, "test_fk", $tid), $data1);
//$this->add_result_model->master_fun_update('test_parameter_master', array('id', $pid), $data1);
        if ($update) {
            redirect("add_result/test_details/" . $jid);
        }
    }

    function delete_graph() {
        $data["login_data"] = logindata();
        $id = $this->uri->segment(3);
        $jid = $this->uri->segment(4);
        $data1 = array('status' => '0');
        $update = $this->add_result_model->master_fun_update('user_formula_pic', array('id', $id), $data1);
        if ($update) {
            redirect("add_result/test_details/" . $jid);
        }
    }

    function edit_parameter() {
        $para_id = $this->input->post('para_id');
        $unit_list = $this->add_result_model->unit_list();
        $details = $this->add_result_model->master_fun_get_tbl_val("test_parameter_master", array('id' => $para_id), array("id", "asc"));
        $range = explode("-", $details[0]['parameter_range']);
        echo '<tr><input type="hidden" value="' . $para_id . '" name="update_pid">
                                <td>
                                    <input type="text" name="update_par_name" id="update_par_name" class="form-control" value="' . $details[0]['parameter_name'] . '">
                                        <span id="update_par_name_error" style="color:red;"></span>
                                </td>
                                <td>
                                    <input type="text" name="update_par_min" id="update_par_min" class="form-control" value="' . $range[0] . '">
                                        <span id="update_par_min_error" style="color:red;"></span>
                                </td>
                                <td>
                                    <input type="text" name="update_par_max" id="update_par_max" class="form-control" value="' . $range[1] . '">
                                        <span id="update_par_max_error" style="color:red;"></span>
                                </td>
                                <td>
                                    <select class="form-control" name="update_par_unit" id="update_par_unit">
                                        <option value="">--Select--</option>';
        foreach ($unit_list as $unit) {
            echo '<option value="' . $unit['PARAMETER_NAME'] . '"';
            if ($unit['PARAMETER_NAME'] == $details[0]['parameter_unit']) {
                echo 'selected';
            }
            echo '>' . $unit['PARAMETER_NAME'] . '</option>';
        }
        echo '</select><span id="update_par_unit_error" style="color:red;"></span>
                                </td>
                            </tr>';
    }

    function update_parameter() {
        $job_id = $this->input->post('jobu_id');
        $update_par_name = $this->input->post('update_par_name');
        $update_par_min = $this->input->post('update_par_min');
        $update_par_max = $this->input->post('update_par_max');
        $update_par_unit = $this->input->post('update_par_unit');
        $update_par_id = $this->input->post('update_pid');
        $range = $update_par_min . "-" . $update_par_max;
        $data1 = array("parameter_name" => $update_par_name, "parameter_range" => $range, "parameter_unit" => $update_par_unit, "modified_by" => $data["login_data"]["id"], "modified_date" => date("Y-m-d H:i:s"));
        $update = $this->add_result_model->master_fun_update('test_parameter_master', array('id', $update_par_id), $data1);
        if ($update) {
            redirect("add_result/test_details/" . $job_id);
        }
    }

    function testdescription() {
        $id = $this->input->post('test_id');
        $details = $this->add_result_model->master_fun_get_tbl_val("test_master", array('id' => $id), array("id", "asc"));
        echo $details[0]['description'];
    }

    function tst($id) {
        echo $id . " Hiiii";
        echo "<a href='javascript:void(0)' onclick='parent.close_box();'>Close</a>";
    }

    function add_parameter() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['tid'] = $this->uri->segment(3);
        $data['event'] = $this->uri->segment(4);
        if ($data['event'] != 'add') {
            $data['edit_p_tid'] = $this->uri->segment(4);
        }
        $data['branch_fk'] = $this->uri->segment(5);
        if (empty($data['branch_fk'])) {
            $data['branch_fk'] = 1;
        }
        $data['processing_center'] = $this->add_result_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $data['branch_fk']), array("id", "asc"));
        if (empty($data['processing_center'])) {
            $processing_center = '1';
        } else {
            $processing_center = $data['processing_center'][0]["branch_fk"];
        }
        $data['test_desc'] = $this->add_result_model->master_fun_get_tbl_val("test_master", array('id' => $data['tid']), array("id", "asc"));
        $data['parameter_list'] = $this->add_result_model->master_fun_get_tbl_val("test_parameter_master", array('status' => 1, "center" => $processing_center, "parameter_name !=" => ''), array("parameter_name", "asc"));
        $data['unit_list'] = $this->add_result_model->unit_list();
        $this->load->view('header');
//$this->load->view('nav', $data);
        $this->load->view('add_test_parameter', $data);
//$this->load->view('footer');
    }

    function add_value_all($tid) {
        if (!is_loggedin()) {
            redirect('login');
        }
        //  echo "<pre>";
        //   print_r($this->input->post());
        //    die();
        $data["login_data"] = logindata();
        // bhavik 12 june 2018
        $sms = $this->input->post('sms');
        // bhavik 12 june 2018
		$par_testcode = $this->input->post('par_testcode');
        $par_name = $this->input->post('par_name');
        $par_unit = $this->input->post('par_unit');
        $par_formula = $this->input->post('par_formula');
        $parameter_code = $this->input->post('parameter_code');
        $count_par = $this->input->post('count_par');
        $count_ref = $this->input->post('count_ref');
        $desc_test = $this->input->post('desc_test');
        $par_id = $this->input->post('exist_para');
        $test_id = $this->input->post('test_id');
        $old_test_id = $this->input->post('old_test_id');
        $group = $this->input->post('group');
        $branch = $this->input->post('branch');
        $par_method = $this->input->post('par_method');
        $data['processing_center'] = $this->add_result_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $branch), array("id", "asc"));
        if (empty($data['processing_center'])) {
            $branch = '1';
        } else {
            $branch = $data['processing_center'][0]["branch_fk"];
        }

//$this->add_result_model->master_fun_update('test_master', array('id', $tid), array("description" => $desc_test));
        if (!empty($par_name)) {
            if (trim($group) != '') {
                $test_count = $this->add_result_model->master_fun_get_tbl_val("test_parameter", array("test_fk" => $tid, "center" => $branch, "parameter_fk" => $par_id, "status" => 1), array("id", "asc"));
                print_r($test_count);
                if (count($test_count) == 0 && $par_id == '') {
                    $insert = $this->add_result_model->master_fun_insert("test_parameter_master", array("center" => $branch, "testcode" => $par_testcode, "parameter_name" => $group, "created_by" => $data["login_data"]["id"], "is_group" => '1', "created_date" => date("Y-m-d H:i:s"), "sms" => $sms));
                    $val_add1 = $this->add_result_model->master_fun_insert("test_parameter", array("test_fk" => $tid, "center" => $branch, "parameter_fk" => $insert));
                } else {
                    $this->add_result_model->master_fun_update('test_parameter_master', array('id', $par_id), array("testcode" => $par_testcode, "parameter_name" => $group, "modified_by" => $data["login_data"]["id"], "is_group" => '1', "modified_date" => date("Y-m-d H:i:s"), "sms" => $sms));
                    echo $this->db->last_query();
                    die();
                }
            } else {
                if ($par_id == "") {
                    $insert = $this->add_result_model->master_fun_insert("test_parameter_master", array("center" => $branch, "testcode" => $par_testcode, "parameter_name" => $par_name, "method" => $par_method, "parameter_unit" => $par_unit, "formula" => $par_formula, "created_by" => $data["login_data"]["id"], "description" => $desc_test, "created_date" => date("Y-m-d H:i:s"), "sms" => $sms));
                    $this->add_result_model->master_fun_insert("test_parameter", array("test_fk" => $tid, "center" => $branch, "parameter_fk" => $insert));
                } else {
                    $this->add_result_model->master_fun_update('test_parameter_master', array('id', $par_id), array("center" => $branch, "testcode" => $par_testcode, "parameter_name" => $par_name, "method" => $par_method, "parameter_unit" => $par_unit, "code" => $parameter_code, "formula" => $par_formula, "modified_by" => $data["login_data"]["id"], "description" => $desc_test, "modified_date" => date("Y-m-d H:i:s"), "sms" => $sms));

                    $test_count = $this->add_result_model->master_fun_get_tbl_val("test_parameter", array("test_fk" => $tid, "parameter_fk" => $par_id, "status" => 1), array("id", "asc"));
                    if (count($test_count) == 0) {
                        $this->add_result_model->master_fun_insert("test_parameter", array("test_fk" => $tid, "center" => $branch, "parameter_fk" => $par_id));
                    }
                    if ($old_test_id != $par_id) {
                        $this->add_result_model->master_fun_update1('test_parameter', array("test_fk" => $test_id, "center" => $branch, "parameter_fk" => $old_test_id), array("status" => 0));
                    }
                }
                if ($_REQUEST["debug"] == 1) {
                    echo "<pre>";
                    print_r($_POST);
                    die();
                }
                if ($par_id != '') {
                    $edit_count_par = $this->input->post('edit_count_par');
                    $edit_count_ref = $this->input->post('edit_count_ref');
                    for ($k = 1; $k < $edit_count_par; $k++) {
                        $data["login_data"] = logindata();
                        $edit_par_gender = $this->input->post('edit_par_gender_' . $k);
                        $edit_par_no_period = $this->input->post('edit_par_no_period_' . $k);
                        $edit_par_type_period = $this->input->post('edit_par_type_period_' . $k);
                        $edit_par_normal_remark = $this->input->post('edit_par_normal_remark_' . $k);
                        $edit_par_ref_range_low = $this->input->post('edit_par_ref_range_low_' . $k);
                        $edit_par_low_remark = $this->input->post('edit_par_low_remark_' . $k);
                        $edit_par_ref_range_high = $this->input->post('edit_par_ref_range_high_' . $k);
                        $edit_par_high_remark = $this->input->post('edit_par_high_remark_' . $k);
                        $edit_par_critical_low = $this->input->post('edit_par_critical_low_' . $k);
                        $edit_par_critical_low_remark = $this->input->post('edit_par_critical_low_remark_' . $k);
                        $edit_par_critical_high = $this->input->post('edit_par_critical_high_' . $k);
                        $edit_par_critical_high_remark = $this->input->post('edit_par_critical_high_remark_' . $k);
                        $edit_par_critical_low_sms = $this->input->post('edit_par_critical_low_sms_' . $k);
                        $edit_par_critical_high_sms = $this->input->post('edit_par_critical_high_sms_' . $k);
                        $edit_par_repeat_low = $this->input->post('edit_par_repeat_low_' . $k);
                        $edit_par_repeat_low_remark = $this->input->post('edit_par_repeat_low_remark_' . $k);
                        $edit_par_repeat_high = $this->input->post('edit_par_repeat_high_' . $k);
                        $edit_par_repeat_high_remark = $this->input->post('edit_par_repeat_high_remark_' . $k);
                        $edit_par_absurd_low = $this->input->post('edit_par_absurd_low_' . $k);
                        $edit_par_absurd_high = $this->input->post('edit_par_absurd_high_' . $k);
                        $edit_par_ref_range = $this->input->post('edit_par_ref_range_' . $k);
                        $edit_par_ref_id = $this->input->post('edit_par_ref_id_' . $k);
                        if ($edit_par_gender != "") {
                            $data4 = array(
                                "center" => $branch,
                                "gender" => $edit_par_gender,
                                "no_period" => $edit_par_no_period,
                                "type_period" => $edit_par_type_period,
                                "normal_remarks" => $edit_par_normal_remark,
                                "ref_range_low" => $edit_par_ref_range_low,
                                "low_remarks" => $edit_par_low_remark,
                                "ref_range_high" => $edit_par_ref_range_high,
                                "high_remarks" => $edit_par_high_remark,
                                "critical_low" => $edit_par_critical_low,
                                "critical_low_remarks" => $edit_par_critical_low_remark,
                                "critical_high" => $edit_par_critical_high,
                                "critical_high_remarks" => $edit_par_critical_high_remark,
                                "critical_low_sms" => $edit_par_critical_low_sms,
                                "critical_high_sms" => $edit_par_critical_high_sms,
                                "repeat_low" => $edit_par_repeat_low,
                                "repeat_low_remarks" => $edit_par_repeat_low_remark,
                                "repeat_high" => $edit_par_repeat_high,
                                "repeat_high_remarks" => $edit_par_repeat_high_remark,
                                "absurd_low" => $edit_par_absurd_low,
                                "absurd_high" => $edit_par_absurd_high,
                                "ref_range" => $edit_par_ref_range,
                                "parameter_fk" => $par_id,
                                "modified_by" => $data["login_data"]["id"],
                                "modified_date" => date("Y-m-d H:i:s")
                            );

                            $val_add4 = $this->add_result_model->master_fun_update('parameter_referance_range', array('id', $edit_par_ref_id), $data4);
                        }
                    }
                    for ($l = 1; $l < $edit_count_ref; $l++) {
                        $data["login_data"] = logindata();
						$edit_pari_testcode = $this->input->post('edit_par_testcode_' . $l);
                        $edit_pari_code = $this->input->post('edit_par_code_' . $l);
                        $edit_pari_name = $this->input->post('edit_par_name_' . $l);
                        $edit_pari_result = $this->input->post('edit_par_result_' . $l);
                        $edit_pari_critical = $this->input->post('edit_par_critical_' . $l);
                        $edit_pari_remarks = $this->input->post('edit_par_remarks_' . $l);
                        $edit_pari_status_id = $this->input->post('edit_par_status_id_' . $l);
                        if ($edit_pari_name != "") {
                            $data5 = array(
								"testcode" => $edit_pari_testcode,
                                "parameter_code" => $edit_pari_code,
                                "parameter_name" => $edit_pari_name,
                                "result_status" => $edit_pari_result,
                                "critical_status" => $edit_pari_critical,
                                "remarks" => $edit_pari_remarks,
                                "parameter_fk" => $par_id,
                                "modified_by" => $data["login_data"]["id"],
                                "modified_date" => date("Y-m-d H:i:s")
                            );
                            $val_add5 = $this->add_result_model->master_fun_update('test_result_status', array('id', $edit_pari_status_id), $data5);
                        }
                    }
                }
                for ($i = 1; $i <= $count_par; $i++) {
                    $data["login_data"] = logindata();
                    $par_gender = $this->input->post('par_gender_' . $i);
                    $par_no_period = $this->input->post('par_no_period_' . $i);
                    $par_type_period = $this->input->post('par_type_period_' . $i);
                    $par_normal_remark = $this->input->post('par_normal_remark_' . $i);
                    $par_ref_range_low = $this->input->post('par_ref_range_low_' . $i);
                    $par_low_remark = $this->input->post('par_low_remark_' . $i);
                    $par_ref_range_high = $this->input->post('par_ref_range_high_' . $i);
                    $par_high_remark = $this->input->post('par_high_remark_' . $i);
                    $par_critical_low = $this->input->post('par_critical_low_' . $i);
                    $par_critical_low_remark = $this->input->post('par_critical_low_remark_' . $i);
                    $par_critical_high = $this->input->post('par_critical_high_' . $i);
                    $par_critical_high_remark = $this->input->post('par_critical_high_remark_' . $i);
                    $par_critical_low_sms = $this->input->post('par_critical_low_sms_' . $i);
                    $par_critical_high_sms = $this->input->post('par_critical_high_sms_' . $i);
                    $par_repeat_low = $this->input->post('par_repeat_low_' . $i);
                    $par_repeat_low_remark = $this->input->post('par_repeat_low_remark_' . $i);
                    $par_repeat_high = $this->input->post('par_repeat_high_' . $i);
                    $par_repeat_high_remark = $this->input->post('par_repeat_high_remark_' . $i);
                    $par_absurd_low = $this->input->post('par_absurd_low_' . $i);
                    $par_absurd_high = $this->input->post('par_absurd_high_' . $i);
                    $par_ref_range = $this->input->post('par_ref_range_' . $i);
                    if ($par_gender != "") {
                        $data = array(
                            "center" => $branch,
                            "gender" => $par_gender,
                            "no_period" => $par_no_period,
                            "type_period" => $par_type_period,
                            "normal_remarks" => $par_normal_remark,
                            "ref_range_low" => $par_ref_range_low,
                            "low_remarks" => $par_low_remark,
                            "ref_range_high" => $par_ref_range_high,
                            "high_remarks" => $par_high_remark,
                            "critical_low" => $par_critical_low,
                            "critical_low_remarks" => $par_critical_low_remark,
                            "critical_high" => $par_critical_high,
                            "critical_high_remarks" => $par_critical_high_remark,
                            "critical_low_sms" => $par_critical_low_sms,
                            "critical_high_sms" => $par_critical_high_sms,
                            "repeat_low" => $par_repeat_low,
                            "repeat_low_remarks" => $par_repeat_low_remark,
                            "repeat_high" => $par_repeat_high,
                            "repeat_high_remarks" => $par_repeat_high_remark,
                            "absurd_low" => $par_absurd_low,
                            "absurd_high" => $par_absurd_high,
                            "ref_range" => $par_ref_range,
                            "created_by" => $data["login_data"]["id"],
                            "created_date" => date("Y-m-d H:i:s")
                        );
                        if ($par_id != "") {
                            $data["parameter_fk"] = $par_id;
                        } else {
                            $data["parameter_fk"] = $insert;
                        }
                        $val_add = $this->add_result_model->master_fun_insert("parameter_referance_range", $data);
                    }
                }
                for ($j = 1; $j <= $count_ref; $j++) {
                    $data["login_data"] = logindata();
					$pari_testcode = $this->input->post('par_testcode_' . $j);
                    $pari_code = $this->input->post('par_code_' . $j);
                    $pari_name = $this->input->post('par_name_' . $j);
                    $pari_result = $this->input->post('par_result_' . $j);
                    $pari_critical = $this->input->post('par_critical_' . $j);
                    $pari_remarks = $this->input->post('par_remarks_' . $j);
                    if ($pari_name != "") {
                        $data1 = array(
							"testcode" => $pari_testcode,
                            "parameter_code" => $pari_code,
                            "parameter_name" => $pari_name,
                            "result_status" => $pari_result,
                            "critical_status" => $pari_critical,
                            "remarks" => $pari_remarks,
                            "created_by" => $data["login_data"]["id"],
                            "created_date" => date("Y-m-d H:i:s")
                        );
                        if ($par_id != "") {
                            $data1["parameter_fk"] = $par_id;
                        } else {
                            $data1["parameter_fk"] = $insert;
                        }
                        $val_add1 = $this->add_result_model->master_fun_insert("test_result_status", $data1);
                    }
                }
            }
        }
        if ($val_add1 || $val_add5) {
            echo 1;
        } else {
            echo 1;
        }
    }

    function get_value() {
        //print_r();
        $data['pid'] = $pid = $this->input->post('pid');
        $data['tid'] = $tid = $this->input->post('tid');
        $data['brnch_fk'] = $bid = $this->input->post('brnch_fk');

        $data['processing_center'] = $this->add_result_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $data['brnch_fk']), array("id", "asc"));
        if (empty($data['processing_center'])) {
            $processing_center = '1';
        } else {
            $processing_center = $data['processing_center'][0]["branch_fk"];
        }
        $data['parameter_list'] = $this->add_result_model->master_fun_get_tbl_val("test_parameter_master", array('status' => 1, "center" => $processing_center, "parameter_name !=" => ''), array("parameter_name", "asc"));
        $data['unit_list'] = $this->add_result_model->unit_list();
        $data['parameter_details'] = $this->add_result_model->master_fun_get_tbl_val("test_parameter_master", array('id' => $pid, 'status' => 1), array("parameter_name", "asc"));
        $data['reference_details'] = $this->add_result_model->master_fun_get_tbl_val("parameter_referance_range", array('parameter_fk' => $pid, 'status' => 1), array("id", "asc"));
        $data['status_details'] = $this->add_result_model->master_fun_get_tbl_val("test_result_status", array('parameter_fk' => $pid, 'status' => 1), array("id", "asc"));
        $html = $this->load->view('edit_test_parameter', $data);
        echo $html;
    }

    function remove_reference() {
        $rid = $this->input->post('reference_id');
        $update = $this->add_result_model->master_fun_update('parameter_referance_range', array('id', $rid), array("status" => 0));
        if ($update) {
            echo 1;
        }
    }

    function remove_status() {
        $sid = $this->input->post('status_id');
        $update = $this->add_result_model->master_fun_update('test_result_status', array('id', $sid), array("status" => 0));
        if ($update) {
            echo 1;
        }
    }

    function test() {
        $rand = rand(111, 99999);
        echo '<a href="' . base_url() . '/upload/report/' . $rand . '_result.pdf" target="_blank">Show Pdf</a>';
        $pdfFilePath = FCPATH . "/upload/report/" . $rand . "_result.pdf";
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '32M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        $html = $this->load->view('test_result_pdf', $data, true); // render the view into HTML 
//echo $html=$this->html12($html); 
//$html = str_split($html, 3);
//print_r($html);
//die();
//$param = '"en-GB-x","A4","","",10,10,0,10,6,3,"P"'; // Landscape
//$lorem = utf8_encode($html); // render the view into HTML
//$html = "<!DOCTYPE html>                         <html><body>\u0627\u0644\u0643\u0647\u0631\u0628\u0627\u0621 \u0648 \u0627\u0644\u0633\u0628\u0627\u0643\u0629</body></html>      ";
        if (file_exists($pdfFilePath)) {
            $this->delete_downloadfile($pdfFilePath);
        }
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;

        $pdf->autoLangToFont = true;

        //$pdf->SetHTMLHeader('<div style="text-align: right; font-weight: bold;">My document</div>');
        $pdf->AddPage('p', // L - landscape, P - portrait
                100, '', '', '', 5, // margin_left
                5, // margin right
                80, // margin top
                80, // margin bottom
                2, // margin header
                2); // margin footer
        /*    $pdf->SetHTMLFooter('

          <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>

          <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>

          <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>

          <td width="33%" style="text-align: right; ">My document</td>

          </tr></table>

          '); */
        /* $pdf->AddPage('P', // L - landscape, P - portrait
          '', '', '', '', 00, // margin_left
          0, // margin right
          0, // margin top
          0, // margin bottom
          0, // margin header
          0); */

//  $pdf->SetDisplayMode('fullpage');
// $pdf->h2toc = array('H2' => 0);
//nishit index start
// $html = '';
// Split $lorem into words
        //$html="<div style='background:red;height:2100px'>htientest</div>";
        $pdf->WriteHTML($html);
//nishit index end
//   $pdf->debug = true;
//$pdf->SetFooter('www.' . $_SERVER['HTTP_HOST'] . '||' . $new_time); // Add a footer for good measure <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
// $pdf->WriteHTML($html); // write the HTML into the PDF
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
//redirect("/upload/report/" . $rand . "_result.pdf");
    }

    function html12($name) {
        $html = strip_tags(preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $name)));
        return $html;
        $contant_ary = str_word_count($content);
    }

    function formula_calculation() {
        $para_id = $this->input->post("para_id");
        $result_num = $this->input->post("result_num");
        $new_array = array();
        foreach ($para_id as $key) {
            $val1 = explode("%&%", $key);
            $new_array[$val1[0]] = $val1[1];
        }



        $result_array = array();
        $array_with_val = array();
        $formulaarr = array();

        $para = array();
        $needpara = array();
        $resultarray = array();
        foreach ($new_array as $key => $ar) {
            if ($ar != "") {
                $para[] = $key;
                $resultarray[$key] = $ar;
            } else {
                $needpara[] = $key;
            }
        }



        foreach ($para_id as $key) {
            $val1 = explode("%&%", $key);
            $formula = $this->add_result_model->master_fun_get_tbl_val("test_parameter_master", array('id' => $val1[0], 'status' => 1), array("id", "desc"));

            if (!empty($formula[0]["formula"])) {
                $p_formula = $formula[0]["formula"];

                preg_match_all("/'([^']+)'/", $p_formula, $matches);
                $value_array = array();
                $is_valid = 0;
                foreach ($matches[1] as $value_key) {
                    if ($new_array[$value_key] == '') {
                        $is_valid = 1;
                    }
                    $value_array[] = $new_array[$value_key];
                }
                $formulaarr[] = array("pid" => $formula[0]['id'], "formula" => $p_formula, "associate" => $matches, "val" => $value_array, "is_calculate" => 0);
            } else {
                $formulaarr[] = array("pid" => $val1[0], "formula" => " ", "associate" => array(array('23123'), array(23123)), "val" => array(3213213), "is_calculate" => 0);
            }
        }


        $nw_ary = array();

        $temp = 100;
        $runnagain = true;
        while (count($needpara) > 0 && $runnagain) {
            $runnagain = 0;
            $tempformulaarr = $formulaarr;

            $formulaarr = array();

            foreach ($tempformulaarr as $r_key) {

                $temp--;
                $target = $r_key["associate"][1];
                if (count(array_intersect((array) $para, $target)) == count($target) && $r_key['is_calculate'] == 0) {

                    $runnagain = 1;

                    $find = $r_key["associate"][0];

                    $ansarray = array();
                    foreach ($r_key["associate"][1] as $as) {
                        foreach ($resultarray as $key => $value) {
                            if ($key == $as) {
                                $ansarray[] = $value;
                            }
                        }
                    }

                    $replace = $r_key['val'];
                    $f = $r_key["formula"];
                    //     $f= str_replace("/","//",$r_key["formula"]);
                    $result = preg_replace($find, $ansarray, $f);
                    $final_calculation = str_replace("'", "", $result);

                    $p = eval("return " . $final_calculation . ";");


                    $p = round($p, 2);
                    $r_key["ans"] = $p;
                    $nw_ary[] = $r_key;
                    $para[] = $r_key["pid"];
                    $resultarray[$r_key["pid"]] = $p;


                    $needpara = array_diff($needpara, array($r_key["pid"]));

                    $r_key['is_calculate'] = 1;
                } else {
                    $formulaarr[] = $r_key;
                }
            }
        }
        foreach ($resultarray as $key => $value) {
            $result_array[] = array("pid" => $key, "val" => $result_num, "res" => $value);
        }

        if (!empty($result_array)) {
            echo json_encode(array("status" => "1", "data" => $result_array));
        } else {
            echo json_encode(array("status" => "0", "data" => $array_with_val, "num" => $result_num));
        }
    }

    
    // Send SMS in job_details
    function send_result($id) {
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
            $processing_date = $this->add_result_model->get_val("SELECT IF(job_log.date_time!='',DATE_FORMAT(job_log.date_time,'%Y-%m-%d'),DATE_FORMAT(`job_master`.`date`,'%Y-%m-%d')) AS `date` FROM `job_master` LEFT JOIN `job_log` ON `job_log`.`job_fk`=`job_master`.`id` AND `job_log`.`status`='1' AND `job_log`.`job_status`='6-7' WHERE `job_master`.`status`!='0' AND `job_master`.`id`='" . $data['cid'] . "'");
            $pdate = $processing_date[0]["date"];
            $this->load->library("util");
            $util = new Util;
            $age = $util->get_ref_age($data['user_data'][0]["dob"], $pdate);
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
                        $para = $this->add_result_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") AND sms = '1' ORDER BY FIELD(id," . implode(",", $pid) . ")");
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
                if (!empty($cust_rel_mobile) && $cust_rel_mobile != "") {

                    $mobile[] = $cust_rel_mobile;
                }
                $custmobile = $cust_rel_mobile;

                $data['send_sms_no'] = $this->add_result_model->get_val("SELECT `send_report_sms`.*,`admin_master`.`name` FROM `send_report_sms` INNER JOIN `admin_master` ON `send_report_sms`.`send_by`=`admin_master`.id WHERE `send_report_sms`.`status`='1' AND send_report_sms.`job_fk`='" . $data['cid'] . "'");
                echo json_encode(array("status" => "1", "sms" => $sms_text, "mobile" => $mobile, "custmobile" => $custmobile, "history" => $data['send_sms_no']));
            } else {
                echo json_encode(array("status" => "0"));
            }
        }
    }

    function send_result_old($id) {
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
            $processing_date = $this->add_result_model->get_val("SELECT IF(job_log.date_time!='',DATE_FORMAT(job_log.date_time,'%Y-%m-%d'),DATE_FORMAT(`job_master`.`date`,'%Y-%m-%d')) AS `date` FROM `job_master` LEFT JOIN `job_log` ON `job_log`.`job_fk`=`job_master`.`id` AND `job_log`.`status`='1' AND `job_log`.`job_status`='6-7' WHERE `job_master`.`status`!='0' AND `job_master`.`id`='" . $data['cid'] . "'");
            $pdate = $processing_date[0]["date"];
            $this->load->library("util");
            $util = new Util;
            $age = $util->get_ref_age($data['user_data'][0]["dob"], $pdate);
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
                        $para = $this->add_result_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
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
                if (!empty(cust_rel_mobile)) {
                    $mobile[] = $cust_rel_mobile;
                }
                $custmobile = $cust_rel_mobile;

                $data['send_sms_no'] = $this->add_result_model->get_val("SELECT `send_report_sms`.*,`admin_master`.`name` FROM `send_report_sms` INNER JOIN `admin_master` ON `send_report_sms`.`send_by`=`admin_master`.id WHERE `send_report_sms`.`status`='1' AND send_report_sms.`job_fk`='" . $data['cid'] . "'");
                echo json_encode(array("status" => "1", "sms" => $sms_text, "mobile" => $mobile, "custmobile" => $custmobile, "history" => $data['send_sms_no']));
            } else {
                echo json_encode(array("status" => "0"));
            }
        }
    }

    function approve_test() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $jid = $this->uri->segment(3);
        $tid = $this->uri->segment(4);
        $check_is_approved = $this->add_result_model->master_fun_get_tbl_val("approve_job_test", array('job_fk' => $jid, "test_fk" => $tid, "status" => "1"), array("id", "asc"));
        //print_r($check_is_approved); die();
        if (empty($check_is_approved)) {
            $insert = $this->add_result_model->master_fun_insert("approve_job_test", array('job_fk' => $jid, "test_fk" => $tid, "approve_by" => $data["login_data"]["id"], "created_date" => date("Y-m-d H:i:s")));
        } else {
            
        }
        redirect("add_result/test_approve_details/" . $jid . "/" . $tid);
    }

    function test_outsource() {
        if (!is_loggedin()) {
            redirect('login');
        }
        if ($this->session->userdata("closeFancyBox")) {
            $data["closeFancyBox"] = $this->session->userdata("closeFancyBox");
            $this->session->unset_userdata("closeFancyBox");
        }
        $data["login_data"] = logindata();
        $data['cid'] = $this->uri->segment(3);
        $data['query'] = $this->add_result_model->job_details_outsource($data['cid']);
        $testresultout = $this->add_result_model->getjobstest($data['cid'], $data["login_data"]["id"]);
        if ($testresultout != "") {
            $data['query'][0]["testid"] = $testresultout->testfk;
        } else {
            $data['query'][0]["testid"] = "";
        }
        $data['processing_center'] = $this->add_result_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $data['query'][0]["branch_fk"]), array("id", "asc"));
        if (empty($data['processing_center'])) {
            $processing_center = '1';
        } else {
            $processing_center = $data['processing_center'][0]["branch_fk"];
        }
        $processing_date = $this->add_result_model->get_val("SELECT IF(job_log.date_time!='',DATE_FORMAT(job_log.date_time,'%Y-%m-%d'),DATE_FORMAT(`job_master`.`date`,'%Y-%m-%d')) AS `date` FROM `job_master` LEFT JOIN `job_log` ON `job_log`.`job_fk`=`job_master`.`id` AND `job_log`.`status`='1' AND `job_log`.`job_status`='6-7' WHERE `job_master`.`status`!='0' AND `job_master`.`id`='" . $data['cid'] . "'");
        $pdate = $processing_date[0]["date"];
        $this->load->library("util");
        $util = new Util;
        $data["age"] = $util->get_ref_age($data['query'][0]["dob"], $pdate);
        $data['user_booking_info'] = $this->add_result_model->master_fun_get_tbl_val("booking_info", array('status' => 1, 'id' => $data['query'][0]["booking_info"]), array("id", "asc"));
        $data['user_data'] = $this->add_result_model->master_fun_get_tbl_val("customer_master", array('status' => 1, 'id' => $data['query'][0]["custid"]), array("id", "asc"));
        if (empty($data['user_data'][0]["gender"]) && empty($data['user_data'][0]["age"])) {
            $data['user_data'][0]["gender"] = 'male';
            $data['user_data'][0]["age"] = 24;
            $data['user_data'][0]["age_type"] = 'Y';
        }

        if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
            $data['user_family_info'] = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
            $data['user_data'][0]["gender"] = $data['user_family_info'][0]["gender"];
            $data['user_data'][0]["age"] = $data['user_family_info'][0]["age"];
            $data['user_data'][0]["age_type"] = $data['user_family_info'][0]["age_type"];
            $data['user_data'][0]["full_name"] = $data['user_family_info'][0]["name"];
            $data['user_data'][0]["email"] = $data['user_family_info'][0]["email"];
            $data['user_data'][0]["phone"] = $data['user_family_info'][0]["phone"];
            $data['user_data'][0]["dob"] = $data['user_family_info'][0]["dob"];
        }
        if (empty($data['user_data'][0]["dob"])) {
            $data['user_data'][0]["dob"] = '1992-09-30';
        }
        /* Check bitrth date start */
        $processing_date = $this->add_result_model->get_val("SELECT IF(job_log.date_time!='',DATE_FORMAT(job_log.date_time,'%Y-%m-%d'),DATE_FORMAT(`job_master`.`date`,'%Y-%m-%d')) AS `date` FROM `job_master` LEFT JOIN `job_log` ON `job_log`.`job_fk`=`job_master`.`id` AND `job_log`.`status`='1' AND `job_log`.`job_status`='6-7' WHERE `job_master`.`status`!='0' AND `job_master`.`id`='" . $data['cid'] . "'");
        $pdate = $processing_date[0]["date"];
        $this->load->library("util");
        $util = new Util;

        $age = $util->get_ref_age($data['user_data'][0]["dob"], $pdate);

        if ($data["login_data"]["id"] == "12") {
            print_r($age);
        }
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

        if ($data["login_data"]["id"] == "12") {
            print_r($age);
            echo $ageinDays;
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
        //print_R($tid); die();
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
            $get_test_parameter = $this->add_result_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' and `test_parameter`.`center`='" . $processing_center . "' order by `test_parameter`.order asc");


            $pid = array();
            foreach ($get_test_parameter as $tp_key) {
                if (!empty($tp_key["parameter_fk"])) {
                    $pid[] = $tp_key["parameter_fk"];
                }
            }
            if (!empty($pid)) {
                $para = $this->add_result_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
                if (!empty($para)) {
                    $cnt_1 = 0;
                    foreach ($para as $para_key) {
                        $formula = $this->add_result_model->get_val("SELECT * from use_formula where test_fk='" . $tst_id . "' and job_fk='" . $data['cid'] . "' and status='1'");
                        $get_test_parameter[$cnt_1]['use_formula'] = $formula[0]["use_formula"];
                        $get_test_parameter[$cnt_1]['on_new_page'] = $formula[0]["on_new_page"];
                        $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                        $get_test_parameter[$cnt_1]['graph'] = $graph_pic;
                        $get_test_parameter[$cnt_1]['graph_id'] = $formula[0]["id"];
                        $get_test_parameter1 = $get_test_parameter[$cnt_1];
//print_R($get_test_parameter1); die();
//echo "SELECT * from user_test_result where test_id='".$tst_id."' and parameter_id='" . $para_key["id"] . "' and job_id='".$data['cid']."' and  status='1'";
                        $para_user_val = $this->add_result_model->get_val("SELECT * from user_test_result where test_id='" . $tst_id . "' and parameter_id='" . $para_key["id"] . "' and job_id='" . $data['cid'] . "' and  status='1'");
                        $para[$cnt_1]["user_val"] = $para_user_val;
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
                        /* if ($data['user_data'][0]["age"] > 0 && $data['user_data'][0]["age"] < 6 && $data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          } else if ($data['user_data'][0]["age"] > 5 && $data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          $data["common"] = 0;
                          } else if ($data['user_data'][0]["age"] > 0 && $data['user_data'][0]["age"] < 8 && $data['user_data'][0]["age_type"] == 'M') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                          $data["common"] = 0;
                          } else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                          //$get_both_age = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='117' AND gender='B' AND `no_period` > ".$data['user_data'][0]["age"]." AND `type_period`='Y' ORDER BY id ASC");
                          $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          //$get_male_age = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='117' AND gender='M' AND `no_period` > ".$data['user_data'][0]["age"]." AND `type_period`='Y' ORDER BY id ASC");
                          //print_r($get_male_age);
                          $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } */
                        if ($data['user_data'][0]["age"] == '') {
                            $data['user_data'][0]["age"] = 0;
                        }
                        /* if ($data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          } else if ($data['user_data'][0]["age_type"] == 'M') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          }
                          else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } */
                        if (strtoupper($data['user_data'][0]["gender"]) == 'MALE') {
                            $final_qry .= " AND gender='M' AND (CASE WHEN (type_period= 'Y') THEN (no_period*365) ELSE (CASE WHEN (type_period= 'M') THEN (no_period*30) ELSE no_period END) END )>$ageinDays ";
                            $data["common"] = 0;
                        } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE') {
                            $final_qry .= " AND gender='F' AND  (CASE WHEN (type_period= 'Y') THEN (no_period*365) ELSE (CASE WHEN (type_period= 'M') THEN (no_period*30) ELSE no_period END) END )>$ageinDays";
                            $data["common"] = 0;
                        }
                        $final_qry = $final_qry . " ORDER BY `type_period` ASC limit 0,1";
                        //   if($para_key["id"]=="117"){ echo "<pre>"; print_r( $data['user_data'][0]); echo "</pre>"; echo    $final_qry; };
                        $final_qry1 = "SELECT * FROM `test_result_status` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                        $data["common"] = 1;
                        $data["para_ref_rng"] = $this->add_result_model->get_val($final_qry);
                        $data["para_ref_rng"][0]["common"] = "1";
                        $data["para_ref_rng"][0]["tst_id"] = $tst_id;
                        $para[$cnt_1]['para_ref_rng'] = $data["para_ref_rng"];

                        $data["para_ref_status"] = $this->add_result_model->get_val($final_qry1);
                        $para[$cnt_1]['para_ref_status'] = $data["para_ref_status"];
                        $para[$cnt_1]["test_parameter_id"] = $get_test_parameter1["id"];
                        $para[$cnt_1]["new_order"] = $get_test_parameter1["order"];
                        $cnt_1++;
                        //	if($para_key["id"]=="117"){ print_r($data['user_data'][0]); die(); };
                    }
                    $get_test_parameter1[0]['parameter'] = $para;
                    $new_data_array[] = $get_test_parameter1;
                } else {
                    $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                    $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                    $get_test_parameter1[0]['graph'] = $graph_pic;
                    $new_data_array[] = $get_test_parameter1[0];
                }
            } else {
                $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                $get_test_parameter1[0]['graph'] = $graph_pic;
                $new_data_array[] = $get_test_parameter1[0];
            }

            $cnt++;
        }
        $data["new_data_array"] = $new_data_array;
        $data['result_list'] = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('status' => 1, 'job_id' => $data['cid']), array("id", "asc"));
//echo "<pre>";
//print_r($data["new_data_array"]);
//die();
//$data['unit_list'] = $this->add_result_model->unit_list();
        $this->load->view('header');
        //$this->load->view('nav', $data);
        $this->load->view('view_collected_outsource', $data);
        //$this->load->view('footer');
    }

    function change_order_outsource() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $para_job_id = $this->input->post('para_job_id');
        $para = $this->input->post("para");
        $order = $this->input->post("order");
        $p_cnt = 0;
        foreach ($para as $value) {
            $this->add_result_model->master_fun_update('test_parameter', array('id', $value), array("order" => $order[$p_cnt]));
            $p_cnt++;
        }
        $this->add_result_model->master_fun_insert("job_log", array("job_fk" => $para_job_id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "16", "date_time" => date("Y-m-d H:i:s")));

        redirect('add_result/test_outsource/' . $para_job_id);
    }

    function add_value_outsource() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $files = $_FILES;
        $this->load->library('upload');
        //   echo "<pre>"; print_r($_POST); die();
        $data["login_data"] = logindata();
        $count = $this->input->post('count');
        $para_job_id = $this->input->post('para_job_id');
        $this->add_result_model->master_fun_update("use_formula", array("job_fk", $para_job_id), array("status" => 0));
        $test_id_array = array();
        for ($i = 0; $i < $count; $i++) {
            $para_id = $this->input->post('parameter_id_' . $i);
            $test_id = $this->input->post('test_id_' . $i);
            $value = $this->input->post('parameter_value_' . $i);
            $this->add_result_model->master_fun_update1("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id), array("status" => "0"));
            if ($value != '') {
                $check_val = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id, "status" => 1), array("id", "asc"));
                if (empty($check_val)) {
                    $data = array(
                        "job_id" => $para_job_id,
                        "parameter_id" => $para_id,
                        "value" => $value,
                        "test_id" => $test_id,
                        "created_by" => $data["login_data"]["id"],
                        "created_date" => date("Y-m-d H:i:s"),
                    );
                    $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
                } else {
                    $data = array("value" => $value, "status" => "1");
                    $val_add = $this->add_result_model->master_fun_update("user_test_result", array("id", $check_val[0]['id']), $data);
                }
            }
        }
        $para = $this->input->post("para");
        $order = $this->input->post("order");
        $p_cnt = 0;
        foreach ($para as $value1) {
            $this->add_result_model->master_fun_update('test_parameter', array('id', $value1), array("order" => $order[$p_cnt]));
            $p_cnt++;
        }
        if ($value != '') {
            $check_val = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id, "status" => 1), array("id", "asc"));
            if (empty($check_val)) {
                $data = array(
                    "job_id" => $para_job_id,
                    "parameter_id" => $para_id,
                    "value" => $value,
                    "test_id" => $test_id,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s"),
                );
                $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
            } else {
                $data = array("value" => $value);
                $val_add = $this->add_result_model->master_fun_update("user_test_result", array("id", $check_val[0]['id']), $data);
            }
        }

        $test_fk = $this->input->post('test_fk');
        foreach ($test_fk as $key) {
            $formula = $this->input->post('use_formula_' . $key);
            $on_new_page = $this->input->post('on_new_page_' . $key);
            if ($formula == '') {
                $formula = 0;
            }
            if ($on_new_page == '') {
                $on_new_page = 0;
            }
            /* Nishit Graph Upload start */
            /* $graph_name = '';
              if ($files['graph_' . $key]['name'] != '') {
              $_FILES['userfile']['name'] = $files['graph_' . $key]['name'];
              $_FILES['userfile']['type'] = $files['graph_' . $key]['type'];
              $_FILES['userfile']['tmp_name'] = $files['graph_' . $key]['tmp_name'];
              $_FILES['userfile']['error'] = $files['graph_' . $key]['error'];
              $_FILES['userfile']['size'] = $files['graph_' . $key]['size'];
              $config['upload_path'] = './upload/report/graph/';
              $config['allowed_types'] = 'gif|jpg|png';
              $config['file_name'] = time() . $files['graph_' . $key]['name'];
              $config['file_name'] = str_replace(' ', '_', $config['file_name']);
              $config['overwrite'] = FALSE;
              $this->load->library('upload', $config);
              $this->upload->initialize($config);
              if (!$this->upload->do_upload()) {

              } else {
              $graph_name = $config['file_name'];
              }
              }
             */


            //print_r($_FILES['graph_' . $key]['name'][0]);
            $file_loop = count($_FILES['graph_' . $key]['name']);
            $file_upload = array();
            if (!empty($_FILES['graph_' . $key]['name'])) {
                for ($i = 0; $i < $file_loop; $i++) {
                    $_FILES['userfile']['name'] = $files['graph_' . $key]['name'][$i];
                    $_FILES['userfile']['type'] = $files['graph_' . $key]['type'][$i];
                    $_FILES['userfile']['tmp_name'] = $files['graph_' . $key]['tmp_name'][$i];
                    $_FILES['userfile']['error'] = $files['graph_' . $key]['error'][$i];
                    $_FILES['userfile']['size'] = $files['graph_' . $key]['size'][$i];
                    $config['upload_path'] = './upload/report/graph/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['file_name'] = time() . $files['graph_' . $key]['name'][$i];
                    $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                    $config['overwrite'] = FALSE;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload()) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata("error", array($error));
                        //redirect('job-master/job-details/' . $cid);
                    } else {
                        $file_upload[] = array("test_fk" => $key, "pic" => $config['file_name']);
                    }
                }
            }
            //print_R($file_upload); die();
            /* Nishit Graph Upload end */
            if (!in_array($key, $test_id_array)) {
                if ($graph_name == '') {
                    $graph_name = $this->input->post('current_graph_' . $key);
                    ;
                }
                $data12 = array(
                    "job_fk" => $para_job_id,
                    "test_fk" => $key,
                    "use_formula" => $formula,
                    "on_new_page" => $on_new_page,
                    //"graph" => $graph_name,
                    "status" => 1
                );
                $test_id_array[] = $key;
                $val_add = $this->add_result_model->master_fun_insert("use_formula", $data12);
            }
            foreach ($file_upload as $file_key) {
                $data12 = array(
                    "job_fk" => $para_job_id,
                    "test_fk" => $key,
                    "pic" => $file_key["pic"],
                    "status" => 1,
                    "createddate" => date("Y-m-d H:i:s")
                );
                //print_r($data12); die();
                $val_add = $this->add_result_model->master_fun_insert("user_formula_pic", $data12);
            }
        }

        $this->add_result_model->master_fun_insert("job_log", array("job_fk" => $para_job_id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "15", "date_time" => date("Y-m-d H:i:s")));
        $this->session->set_userdata("closeFancyBox", array('parent.close_popup1();'));
        redirect('add_result/test_outsource/' . $para_job_id);
    }

    function get_culture_design() {
        $id = $this->uri->segment(3);
        $check_val = $this->add_result_model->get_val("SELECT html FROM `parameter_culture` WHERE id='" . $id . "'");
        echo $check_val[0]["html"];
    }

    function manage_culture() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['test_fk'] = $this->input->get("test_fk");
        $data['branch_fk'] = $this->input->get("branch");
        $data['edit'] = $this->input->get("edit");
        $data["success"] = $this->session->flashdata("success");
        $data['processing_center'] = $this->add_result_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $data['branch_fk']), array("id", "asc"));
        if (empty($data['processing_center'])) {
            $processing_center = '1';
        } else {
            $processing_center = $data['processing_center'][0]["branch_fk"];
        }
        $data["test_name"] = $this->add_result_model->get_val("SELECT `test_master`.`test_name` FROM `test_master` WHERE `status`='1' AND id='" . $data['test_fk'] . "'");
        $data["branch_name"] = $this->add_result_model->get_val("SELECT `branch_master`.`branch_name` FROM `branch_master` WHERE `status`='1' AND id='" . $data['branch_fk'] . "'");

        $data['processing_center'] = $processing_center;
        $this->load->library('form_validation');
        $this->form_validation->set_rules('test_fk', 'Test_fk', 'trim|required');
        $this->form_validation->set_rules('center', 'Center', 'trim|required');
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('culture_design', 'Design', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $test_fk = $this->input->post('test_fk');
            $center = $this->input->post('center');
            $title = $this->input->post('title');
            $culture_design = $this->input->post('culture_design');
            $branch_fk = $this->input->post('branch_fk');
            $edit = $this->input->get("edit");
            //print_r($_POST);
            //die();
            $data12 = array(
                "center" => $center,
                "test_fk" => $test_fk,
                "title" => $title,
                "html" => $culture_design,
                "status" => 1,
                "createddate" => date("Y-m-d H:i:s")
            );
            if (empty($edit)) {
                $val_add = $this->add_result_model->master_fun_insert("parameter_culture", $data12);
                $this->session->set_flashdata("success", array("Design successfully added."));
            } else {
                $this->add_result_model->master_fun_update('parameter_culture', array('id', $edit), array("title" => $title, "html" => $culture_design));
                $this->session->set_flashdata("success", array("Design successfully updated."));
            }
            redirect("add_result/manage_culture?test_fk=" . $test_fk . "&branch=" . $branch_fk);
        }
        $data['query'] = $this->add_result_model->master_fun_get_tbl_val("parameter_culture", array('status' => 1, 'test_fk' => $data['test_fk'], "center" => $processing_center), array("id", "asc"));
        if (!empty($data['edit'])) {
            $data['edit_query'] = $this->add_result_model->master_fun_get_tbl_val("parameter_culture", array('id' => $data['edit']), array("id", "asc"));
        }
        $this->load->view('header');
        $this->load->view('manage_culture', $data);
    }

    function delete_manage_culture() {
        $test_fk = $this->input->get('test_fk');
        $branch_fk = $this->input->get('branch');
        $delete = $this->input->get("delete");
        if (!empty($test_fk) && !empty($branch_fk) && !empty($delete)) {
            $this->add_result_model->master_fun_update('parameter_culture', array('id', $delete), array("status" => "0"));
            $this->session->set_flashdata("success", array("Design successfully deleted."));
        }
        redirect("add_result/manage_culture?test_fk=" . $test_fk . "&branch=" . $branch_fk);
    }

    function approve_reporttest($id) {
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['cid'] = $id;
        $testfk = $this->input->post('testid');
        /* $this->form_validation->set_rules('testid[]', 'testid', 'trim|required');
          if ($this->form_validation->run() != FALSE) { */
        if ($testfk != null) {
            $testfk = $this->input->post('testid');
            $new_page = $this->input->post('on_new_page');
            $length = count($testfk);
            $newpagelength = count($new_page);
            for ($i1 = 0; $i1 < $length; $i1++) {

                $this->add_result_model->updateRowWhere('approve_job_test', array('test_fk' => $testfk[$i1], 'job_fk' => $id), array("position" => $i1, "new_page" => $new_page[$i1]));
            }
            /* $formula = $this->add_result_model->get_val("SELECT * from use_formula where test_fk='" . $tst_id . "' and job_fk='" . $data['cid'] . "' and status='1'"); */

            $data['query'] = $this->add_result_model->job_details1($data['cid']);
            //print_r($data['query']);die();
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
            if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
                $data['user_family_info'] = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
                $data['user_data'][0]["gender"] = $data['user_family_info'][0]["gender"];
                $data['user_data'][0]["age"] = $data['user_family_info'][0]["age"];
                $data['user_data'][0]["age_type"] = $data['user_family_info'][0]["age_type"];
                $data['user_data'][0]["full_name"] = $data['user_family_info'][0]["name"];
                $data['user_data'][0]["email"] = $data['user_family_info'][0]["email"];
                $data['user_data'][0]["phone"] = $data['user_family_info'][0]["phone"];
                $data['user_data'][0]["dob"] = $data['user_family_info'][0]["dob"];
            }
            $processing_date = $this->add_result_model->get_val("SELECT IF(job_log.date_time!='',DATE_FORMAT(job_log.date_time,'%Y-%m-%d'),DATE_FORMAT(`job_master`.`date`,'%Y-%m-%d')) AS `date` FROM `job_master` LEFT JOIN `job_log` ON `job_log`.`job_fk`=`job_master`.`id` AND `job_log`.`status`='1' AND `job_log`.`job_status`='6-7' WHERE `job_master`.`status`!='0' AND `job_master`.`id`='" . $data['cid'] . "'");
            $pdate = $processing_date[0]["date"];
            /* Check bitrth date start */
            $this->load->library("util");
            $util = new Util;
            $age = $util->get_ref_age($data['user_data'][0]["dob"], $pdate);
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
			$chknabltestscope = $this->add_result_model->get_val("SELECT * FROM `nabltestscope_master` WHERE `status`=1 AND `branch_fk`='".$data['query'][0]['branch_fk']."' AND `testcity_fk` = '".$data['query'][0]['test_city']."'");			
            foreach ($tid as $tst_id) {
                if (in_array($tst_id, $approved_test)) {

                    $get_test_parameter = $this->add_result_model->get_val("SELECT 
  `test_parameter`.*,
  `test_master`.`test_name`,
  tmd.method AS test_method,
  test_master.PRINTING_NAME,
  `test_master`.report_type,
  `test_master`.department_fk,
  test_master.sample as sampletype,
  test_department_master.`name` AS 'department_name' 
FROM
  `test_parameter` 
  INNER JOIN `test_master` 
    ON `test_parameter`.`test_fk` = `test_master`.`id` 
  LEFT JOIN `test_department_master` 
    ON test_department_master.`id` = `test_master`.`department_fk` 
    LEFT JOIN `test_method` AS tmd 
    ON tmd.test_fk = test_master.id AND tmd.branch_fk='" . $data['processing_center'][0]["branch_fk"] . "' and tmd.status='1'
	LEFT JOIN branch_sample_type as btype
	ON btype.test_fk=test_master.id AND btype.branch_fk='" . $data['processing_center'][0]["branch_fk"] . "' and btype.status='1'
WHERE `test_parameter`.`status` = '1' 
  AND `test_master`.`status` = '1' 
  AND `test_parameter`.`test_fk` = '" . $tst_id . "' 
  AND test_parameter.center = '" . $processing_center . "' 
ORDER BY `test_parameter`.order ASC ");
                    $pid = array();
                    foreach ($get_test_parameter as $tp_key) {
                        if (!empty($tp_key["parameter_fk"])) {
                            $pid[] = $tp_key["parameter_fk"];
                        }
                    }
                    if (!empty($pid)) {
                        $para = $this->add_result_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
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
                            $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' and type='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
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
                            /* if ($data['user_data'][0]["age_type"] == 'D') {
                              $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                              } else if ($data['user_data'][0]["age_type"] == 'M') {
                              $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                              $data["common"] = 0;
                              } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                              $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                              $data["common"] = 0;
                              } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                              $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                              $data["common"] = 0;
                              } else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                              $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                              $data["common"] = 0;
                              } */
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
						
						if(!empty($chknabltestscope)){
							$nabltestscope = $this->add_result_model->get_val("SELECT * FROM `nabltestscope_master` WHERE `status`=1 AND `branch_fk`='".$data['query'][0]['branch_fk']."' AND `testcity_fk` = '".$data['query'][0]['test_city']."' AND FIND_IN_SET($tst_id,`tests_fk`)");
							if(!empty($nabltestscope)){
								$get_test_parameter1[0]['nablprint'] = $nabltestscope[0];
							}else{
								$get_test_parameter1[0]['nablprint'] = array("test_scope" => '3');
							}
						}
						
                        $new_data_array[] = $get_test_parameter1;
                    } else {
                        /* $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name,test_master.PRINTING_NAME,test_master.method as test_method FROM `test_master` WHERE id='" . $tst_id . "'"); */
                        $get_test_parameter1 = $this->add_result_model->get_val("SELECT 
  tm.id AS test_fk,
  tm.test_name,
  tm.PRINTING_NAME,
  tm.sample as sampletype,
  tmd.method AS test_method 
FROM
  `test_master` AS tm 
  LEFT JOIN `test_method` AS tmd 
    ON tmd.test_fk = tm.id AND tmd.branch_fk='" . $data['processing_center'][0]["branch_fk"] . "' and tmd.status='1'
LEFT JOIN branch_sample_type as btype
	ON btype.test_fk=tm.id AND btype.branch_fk='" . $data['processing_center'][0]["branch_fk"] . "' and btype.status='1'	
WHERE tm.id = '" . $tst_id . "' ");
                        $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' and type='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                        $get_test_parameter1[0]['graph'] = $graph_pic;
						
						if(!empty($chknabltestscope)){
							$nabltestscope = $this->add_result_model->get_val("SELECT * FROM nabltestscope_master WHERE status=1 AND branch_fk ='".$data['query'][0]['branch_fk']."' AND testcity_fk = '".$data['query'][0]['test_city']."' AND FIND_IN_SET($tst_id,tests_fk)");
							if(!empty($nabltestscope)){							
								$get_test_parameter1[0]['nablprint'] = $nabltestscope[0];
							}else{
								$get_test_parameter1[0]['nablprint'] = array("test_scope" => '3');
							}
						}
						
                        $new_data_array[] = $get_test_parameter1[0];
                    }

                    $cnt++;
                }
            }

			
            $data["new_data_array"] = $new_data_array;
            $data['result_list'] = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('status' => 1, 'job_id' => $data['cid']), array("id", "asc"));
            $pdfFilePath = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result_wlpd.pdf";
            $pdfFilePath1 = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result.pdf";
            $data['page_title'] = 'AirmedLabs'; // pass data to the view
            ini_set('memory_limit', '512M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">

            if (file_exists($pdfFilePath)) {
                $this->delete_downloadfile($pdfFilePath);
            }
            if (file_exists($pdfFilePath1)) {
                $this->delete_downloadfile($pdfFilePath1);
            }
            $this->load->library('pdf');
            $pdf = $this->pdf->load();
            $pdf->autoScriptToLang = true;
            $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
            $pdf->autoVietnamese = true;
            $pdf->autoArabic = true;
            $pdf->autoLangToFont = true;
            $pdf->setAutoBottomMargin = 'stretch';

            $name = "DR. Self";
            if ($data["query"][0]['dname'] != "") {
                $name = ucfirst($data["query"][0]['dname']);
            }
            if ($data["query"][0]["model_type"] == 2) {
                $b2b_drname = $this->add_result_model->master_fun_get_tbl_val("sample_job_master", array('barcode_fk' => $data["query"][0]["b2b_id"]), array("id", "asc"));
                $name = ucfirst($b2b_drname[0]['doctor']);
            }
            $base_url = base_url();
            $content = $this->add_result_model->master_fun_get_tbl_val("pdf_design", array('branch_fk' => $branch_fk), array("id", "asc"));
            $data["collection_date"] = $this->add_result_model->get_val("SELECT date_time FROM `job_log` WHERE `status`='1' AND `message_fk`='3' and `job_status` LIKE '%-7%' AND job_fk='" . $data['cid'] . "' order by id desc");
            $data["completed_date"] = $this->add_result_model->get_val("SELECT date_time FROM `job_log` WHERE `status`='1' AND `message_fk`='3' and `job_status` LIKE '%-2%' AND job_fk='" . $data['cid'] . "' order by id desc");

//            $approve_test_last_time = $this->add_result_model->get_val("SELECT MAX(created_date) As date_time
//                    FROM approve_job_test where status = '1' 
//                    AND job_fk ='" . $data['cid'] . "'");




            if (!empty($data["completed_date"])) {
                $data["completed_date1"] = $data["completed_date"][0]["date_time"];
            } else {
                $data["completed_date1"] = date("Y-m-d H:i:s");
//                if ($data["login_data"]['type'] == "6") {
//                    $data["completed_date1"] = $approve_test_last_time[0]["date_time"];
//                } else {
//                    $data["completed_date1"] = date("Y-m-d H:i:s");
//                }
            }

            if (!empty($data["collection_date"])) {
                $data["collection_date"] = $data["collection_date"][0]["date_time"];
            } else {
                if (!empty($data["completed_date"])) {
                    $data["collection_date"] = $data["completed_date"][0]["date_time"];
                } else {
                    $data["collection_date"] = date("Y-m-d H:i:s");
                }
                $data["collection_date"] = date("Y-m-d H:i:s");
            }
            $data["content"] = $content;
            //print_r($content); die();
            $find = array(
                '/{{BARCODE}}/',
                '/{{CUSTID}}/',
                '/{{REGDATE}}/',
                '/{{COLLECTIONON}}/',
                '/{{NAME}}/',
                '/{{REPORTDATE}}/',
                '/{{AGE}}/',
                '/{{GENDER}}/',
                '/{{REFFERBY}}/',
                '/{{LOCATION}}/',
                '/{{TELENO}}/'
            );
            $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
            $logo_url = $base_url . 'user_assets/images/logoaastha.png';
            $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
            $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
            $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
            if ($_REQUEST["debug"] == 3) {
                $replace = array(
                    'pdf_barcode.png',
                    $id . " ( " . $data["query"][0]['order_id'] . " ) ",
                    date("d-M-Y", strtotime($data["query"][0]['regi_date'])),
                    date("d-M-Y", strtotime($data["query"][0]['date'])),
                    strtoupper($data["user_data"][0]['full_name']),
                    "28-Aug-2017",
                    $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
                    strtoupper($data["user_data"][0]['gender']),
                    strtoupper($name),
                    strtoupper($data["query"][0]['test_city_name']),
                    $data["user_data"][0]['mobile']
                );
            } else {
                $replace = array(
                    'pdf_barcode.png',
                    $id . " ( " . $data["query"][0]['order_id'] . " ) ",
                    date("d-M-Y", strtotime($data["query"][0]['regi_date'])),
                    date("d-M-Y g:i A", strtotime($data["collection_date"])),
                    strtoupper($data["user_data"][0]['full_name']),
                    date("d-M-Y g:i A", strtotime($data["completed_date1"])),
                    $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
                    strtoupper($data["user_data"][0]['gender']),
                    strtoupper($name),
                    strtoupper($data["query"][0]['test_city_name']),
                    $data["user_data"][0]['mobile']
                );
            }
            if($id=='116354'){
                  $replace = array(
                    'pdf_barcode.png',
                    $id . " ( " . $data["query"][0]['order_id'] . " ) ",
                    date("d-M-Y", strtotime($data["query"][0]['regi_date'])),
                    date("d-M-Y g:i A", strtotime($data["collection_date"])),
                    strtoupper($data["user_data"][0]['full_name']),
                    date("d-M-Y g:i A", strtotime($data["completed_date1"])),
                    $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
                    strtoupper('unspecified'),
                    strtoupper($name),
                    strtoupper($data["query"][0]['test_city_name']),
                    $data["user_data"][0]['mobile']
                );
            }

            if($id=='203181'){
                  $replace = array(
                    'pdf_barcode.png',
                    "199738 ( AHM-28257 ) ",
                    "04-Oct-2019 3:37 PM",
                    "04-Oct-2019 3:37 PM",
                    strtoupper($data["user_data"][0]['full_name']),
                    "07-Oct-2019 5:03 PM",
                    $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
                    'FEMALE',
                    strtoupper($name),
                    strtoupper($data["query"][0]['test_city_name']),
                    $data["user_data"][0]['mobile']
                );
                  
            }

            
            $header = preg_replace($find, $replace, $content[0]["header"]);
            /* echo $header;
              echo $html;
              echo $content[0]["footer"]; die(); */
            //$pdf->SetHTMLHeader($header);






            /* $pdf->AddPage('p', // L - landscape, P - portrait
              '', '', '', '', 5, // margin_left
              5, // margin right
              72, // margin top
              72, // margin bottom
              2, // margin header
              5); // margin footer */

            $pdf->AddPage('p', // L - landscape, P - portrait
                    '', '', '', '', 5, // margin_left
                    5, // margin right
                    5, // margin top
                    5, // margin bottom
                    5, // margin header 
                    5); // margin footer

            /* if($data["login_data"]["id"]==17){
              echo $header;
              echo $html;
              echo $content[0]["footer"];die();
              } */
            $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
            $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
            $emailimg = $base_url . 'user_assets/images/email-icon.png';
            $webimg = $base_url . 'user_assets/images/web-icon.png';
            $lastimg = $base_url . 'user_assets/images/lastimg.png';
            //$pdf->SetHTMLFooter($content[0]["footer"]);
            $data['header'] = $header;

            $data['footer'] = $content[0]["footer"];
            $data['footer'] = str_replace("* Denotes Test not in", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $data['footer']);
            $data['footer'] = str_replace("NABL Scope ** Refered Test", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $data['footer']);

            /* echo "<pre>"; print_r($data); die(); */

            $data["pdfsize"] = $content[0]["withsize"];

            $html = $this->load->view('result_pdf', $data, true); // render the view into HTML 

            $pdf->WriteHTML($html);
            //echo $html;die(); 
            /*   echo $pdf->Output(); die(); */
            $pdf->Output($pdfFilePath, 'F'); // save to file because we can

            $name1 = $this->without_approve_report($data);

            $name = $data['query'][0]['order_id'] . "_result_wlpd.pdf";
            $name_orgnl = $data['user_data'][0]["full_name"] . "_result_with_latterhead.pdf";
            $name_orgnl1 = $data['user_data'][0]["full_name"] . "_result.pdf";
            $count = $this->add_result_model->master_fun_get_tbl_val("report_master", array('job_fk' => $id), array("id", "asc"));
            if (!empty($count)) {
                $data1 = array('job_fk' => $id, 'report' => $name, 'status' => 1, "original" => $name_orgnl, 'without_laterpad' => $name1, "without_laterpad_original" => $name_orgnl1, "type" => "c", "updated_date" => date("Y-m-d H:i:s"));
                $this->add_result_model->master_fun_update('report_master', array('job_fk', $id), $data1);
            } else {
                $data1 = array('job_fk' => $id, 'report' => $name, 'status' => 1, "original" => $name_orgnl, "type" => "c", 'without_laterpad' => $name1, "without_laterpad_original" => $name_orgnl1, "created_date" => date("Y-m-d H:i:s"));
                $this->add_result_model->master_fun_insert("report_master", $data1);
            }
            $this->add_result_model->master_fun_update('job_master', array('id', $id), array("report_approve_by" => $data["login_data"]["id"]));
            //print_R($data1); die();
            $this->session->set_flashdata("success", array("Report successfully attached."));
            $this->add_result_model->master_fun_insert("job_log", array("job_fk" => $id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "17", "date_time" => date("Y-m-d H:i:s")));
            
            $auto_completejob = $this->add_result_model->get_val("select auto_completejob from branch_master where status='1' AND id='" . $data['query'][0]["branch_fk"] . "'")[0]['auto_completejob'];
            $tid = $this->add_result_model->get_val("select * from use_formula where status='1' AND job_fk='$id'");
			$count11 = 0;
            foreach ($tid as $key) {
                if ($key['test_status'] == "2") {
                    $count11++;
                }
            }
			
            if ($count11 == count($tid) && $auto_completejob == 1) {
                $this->job_mark_completed($data['cid']);
				$this->getstock($data['cid']);
                $this->add_result_model->master_fun_insert("job_log", array("job_fk" => $id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '8-2', "message_fk" => "3", "date_time" => date("Y-m-d H:i:s")));
            }
            
            redirect("job-master/job-details/" . $id);
        } else {
            show_404();
        }
    }

    function approve_reporttest_whatsapp($id) {
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['cid'] = $id;
        $testfk = $this->input->post('testid');
        /* $this->form_validation->set_rules('testid[]', 'testid', 'trim|required');
          if ($this->form_validation->run() != FALSE) { */
        if ($testfk != null) {
            $testfk = $this->input->post('testid');
            $new_page = $this->input->post('on_new_page');
            $length = count($testfk);
            $newpagelength = count($new_page);
            for ($i1 = 0; $i1 < $length; $i1++) {

                $this->add_result_model->updateRowWhere('approve_job_test', array('test_fk' => $testfk[$i1], 'job_fk' => $id), array("position" => $i1, "new_page" => $new_page[$i1]));
            }
            /* $formula = $this->add_result_model->get_val("SELECT * from use_formula where test_fk='" . $tst_id . "' and job_fk='" . $data['cid'] . "' and status='1'"); */

            $data['query'] = $this->add_result_model->job_details1($data['cid']);

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
            if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
                $data['user_family_info'] = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
                $data['user_data'][0]["gender"] = $data['user_family_info'][0]["gender"];
                $data['user_data'][0]["age"] = $data['user_family_info'][0]["age"];
                $data['user_data'][0]["age_type"] = $data['user_family_info'][0]["age_type"];
                $data['user_data'][0]["full_name"] = $data['user_family_info'][0]["name"];
                $data['user_data'][0]["email"] = $data['user_family_info'][0]["email"];
                $data['user_data'][0]["phone"] = $data['user_family_info'][0]["phone"];
                $data['user_data'][0]["dob"] = $data['user_family_info'][0]["dob"];
            }
            $processing_date = $this->add_result_model->get_val("SELECT IF(job_log.date_time!='',DATE_FORMAT(job_log.date_time,'%Y-%m-%d'),DATE_FORMAT(`job_master`.`date`,'%Y-%m-%d')) AS `date` FROM `job_master` LEFT JOIN `job_log` ON `job_log`.`job_fk`=`job_master`.`id` AND `job_log`.`status`='1' AND `job_log`.`job_status`='6-7' WHERE `job_master`.`status`!='0' AND `job_master`.`id`='" . $data['cid'] . "'");
            $pdate = $processing_date[0]["date"];
            /* Check bitrth date start */
            $this->load->library("util");
            $util = new Util;
            $age = $util->get_ref_age($data['user_data'][0]["dob"], $pdate);
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
			$chknabltestscope = $this->add_result_model->get_val("SELECT * FROM `nabltestscope_master` WHERE `status`=1 AND `branch_fk`='".$data['query'][0]['branch_fk']."' AND `testcity_fk` = '".$data['query'][0]['test_city']."'");			
            foreach ($tid as $tst_id) {
                if (in_array($tst_id, $approved_test)) {

                    $get_test_parameter = $this->add_result_model->get_val("SELECT 
                    `test_parameter`.*,
                    `test_master`.`test_name`,
                    tmd.method AS test_method,
                    test_master.PRINTING_NAME,
                    `test_master`.report_type,
                    `test_master`.department_fk,
                    test_master.sample as sampletype,
                    test_department_master.`name` AS 'department_name' 
                    FROM
                    `test_parameter` 
                    INNER JOIN `test_master` 
                        ON `test_parameter`.`test_fk` = `test_master`.`id` 
                    LEFT JOIN `test_department_master` 
                        ON test_department_master.`id` = `test_master`.`department_fk` 
                        LEFT JOIN `test_method` AS tmd 
                        ON tmd.test_fk = test_master.id AND tmd.branch_fk='" . $data['processing_center'][0]["branch_fk"] . "' and tmd.status='1'
                        LEFT JOIN branch_sample_type as btype
                        ON btype.test_fk=test_master.id AND btype.branch_fk='" . $data['processing_center'][0]["branch_fk"] . "' and btype.status='1'
                    WHERE `test_parameter`.`status` = '1' 
                    AND `test_master`.`status` = '1' 
                    AND `test_parameter`.`test_fk` = '" . $tst_id . "' 
                    AND test_parameter.center = '" . $processing_center . "' 
                    ORDER BY `test_parameter`.order ASC ");
                    $pid = array();
                    foreach ($get_test_parameter as $tp_key) {
                        if (!empty($tp_key["parameter_fk"])) {
                            $pid[] = $tp_key["parameter_fk"];
                        }
                    }
                    if (!empty($pid)) {
                        $para = $this->add_result_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
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
                            $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' and type='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
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
                            /* if ($data['user_data'][0]["age_type"] == 'D') {
                              $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                              } else if ($data['user_data'][0]["age_type"] == 'M') {
                              $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                              $data["common"] = 0;
                              } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                              $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                              $data["common"] = 0;
                              } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                              $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                              $data["common"] = 0;
                              } else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                              $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                              $data["common"] = 0;
                              } */
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
						
						if(!empty($chknabltestscope)){
							$nabltestscope = $this->add_result_model->get_val("SELECT * FROM `nabltestscope_master` WHERE `status`=1 AND `branch_fk`='".$data['query'][0]['branch_fk']."' AND `testcity_fk` = '".$data['query'][0]['test_city']."' AND FIND_IN_SET($tst_id,`tests_fk`)");
							if(!empty($nabltestscope)){
								$get_test_parameter1[0]['nablprint'] = $nabltestscope[0];
							}else{
								$get_test_parameter1[0]['nablprint'] = array("test_scope" => '3');
							}
						}
						
                        $new_data_array[] = $get_test_parameter1;
                    } else {
                        /* $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name,test_master.PRINTING_NAME,test_master.method as test_method FROM `test_master` WHERE id='" . $tst_id . "'"); */
                        $get_test_parameter1 = $this->add_result_model->get_val("SELECT 
                        tm.id AS test_fk,
                        tm.test_name,
                        tm.PRINTING_NAME,
                        tm.sample as sampletype,
                        tmd.method AS test_method 
                        FROM
                        `test_master` AS tm 
                        LEFT JOIN `test_method` AS tmd 
                            ON tmd.test_fk = tm.id AND tmd.branch_fk='" . $data['processing_center'][0]["branch_fk"] . "' and tmd.status='1'
                        LEFT JOIN branch_sample_type as btype
                            ON btype.test_fk=tm.id AND btype.branch_fk='" . $data['processing_center'][0]["branch_fk"] . "' and btype.status='1'	
                        WHERE tm.id = '" . $tst_id . "' ");
                        $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' and type='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                        $get_test_parameter1[0]['graph'] = $graph_pic;
						
						if(!empty($chknabltestscope)){
							$nabltestscope = $this->add_result_model->get_val("SELECT * FROM nabltestscope_master WHERE status=1 AND branch_fk ='".$data['query'][0]['branch_fk']."' AND testcity_fk = '".$data['query'][0]['test_city']."' AND FIND_IN_SET($tst_id,tests_fk)");
							if(!empty($nabltestscope)){							
								$get_test_parameter1[0]['nablprint'] = $nabltestscope[0];
							}else{
								$get_test_parameter1[0]['nablprint'] = array("test_scope" => '3');
							}
						}
						
                        $new_data_array[] = $get_test_parameter1[0];
                    }

                    $cnt++;
                }
            }

			
            $data["new_data_array"] = $new_data_array;
            $data['result_list'] = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('status' => 1, 'job_id' => $data['cid']), array("id", "asc"));
            $pdfFilePath = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result_wlpd.pdf";
            $pdfFilePath1 = FCPATH . "/upload/report/" . $data['query'][0]['order_id'] . "_result.pdf";
            $data['page_title'] = 'AirmedLabs'; // pass data to the view
            //ini_set('memory_limit', '512M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">

            if (file_exists($pdfFilePath)) {
                $this->delete_downloadfile($pdfFilePath);
            }
            if (file_exists($pdfFilePath1)) {
                $this->delete_downloadfile($pdfFilePath1);
            }
            $this->load->library('pdf');
            $pdf = $this->pdf->load();
            $pdf->autoScriptToLang = true;
            $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
            $pdf->autoVietnamese = true;
            $pdf->autoArabic = true;
            $pdf->autoLangToFont = true;
            $pdf->setAutoBottomMargin = 'stretch';

            $name = "DR. Self";
            if ($data["query"][0]['dname'] != "") {
                $name = ucfirst($data["query"][0]['dname']);
            }
            if ($data["query"][0]["model_type"] == 2) {
                $b2b_drname = $this->add_result_model->master_fun_get_tbl_val("sample_job_master", array('barcode_fk' => $data["query"][0]["b2b_id"]), array("id", "asc"));
                $name = ucfirst($b2b_drname[0]['doctor']);
            }
            $base_url = base_url();
            $content = $this->add_result_model->master_fun_get_tbl_val("pdf_design", array('branch_fk' => $branch_fk), array("id", "asc"));
            $data["collection_date"] = $this->add_result_model->get_val("SELECT date_time FROM `job_log` WHERE `status`='1' AND `message_fk`='3' and `job_status` LIKE '%-7%' AND job_fk='" . $data['cid'] . "' order by id desc");
            $data["completed_date"] = $this->add_result_model->get_val("SELECT date_time FROM `job_log` WHERE `status`='1' AND `message_fk`='3' and `job_status` LIKE '%-2%' AND job_fk='" . $data['cid'] . "' order by id desc");

            //            $approve_test_last_time = $this->add_result_model->get_val("SELECT MAX(created_date) As date_time
            //                    FROM approve_job_test where status = '1' 
            //                    AND job_fk ='" . $data['cid'] . "'");




            if (!empty($data["completed_date"])) {
                $data["completed_date1"] = $data["completed_date"][0]["date_time"];
            } else {
                $data["completed_date1"] = date("Y-m-d H:i:s");
                //                if ($data["login_data"]['type'] == "6") {
                //                    $data["completed_date1"] = $approve_test_last_time[0]["date_time"];
                //                } else {
                //                    $data["completed_date1"] = date("Y-m-d H:i:s");
                //                }
            }

            if (!empty($data["collection_date"])) {
                $data["collection_date"] = $data["collection_date"][0]["date_time"];
            } else {
                if (!empty($data["completed_date"])) {
                    $data["collection_date"] = $data["completed_date"][0]["date_time"];
                } else {
                    $data["collection_date"] = date("Y-m-d H:i:s");
                }
                $data["collection_date"] = date("Y-m-d H:i:s");
            }
            $data["content"] = $content;
            //print_r($content); die();
            $find = array(
                '/{{BARCODE}}/',
                '/{{CUSTID}}/',
                '/{{REGDATE}}/',
                '/{{COLLECTIONON}}/',
                '/{{NAME}}/',
                '/{{REPORTDATE}}/',
                '/{{AGE}}/',
                '/{{GENDER}}/',
                '/{{REFFERBY}}/',
                '/{{LOCATION}}/',
                '/{{TELENO}}/'
            );
            $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
            $logo_url = $base_url . 'user_assets/images/logoaastha.png';
            $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
            $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
            $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
            if ($_REQUEST["debug"] == 3) {
                $replace = array(
                    'pdf_barcode.png',
                    $id . " ( " . $data["query"][0]['order_id'] . " ) ",
                    date("d-M-Y", strtotime($data["query"][0]['regi_date'])),
                    date("d-M-Y", strtotime($data["query"][0]['date'])),
                    strtoupper($data["user_data"][0]['full_name']),
                    "28-Aug-2017",
                    $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
                    strtoupper($data["user_data"][0]['gender']),
                    strtoupper($name),
                    strtoupper($data["query"][0]['test_city_name']),
                    $data["user_data"][0]['mobile']
                );
            } else {
                $replace = array(
                    'pdf_barcode.png',
                    $id . " ( " . $data["query"][0]['order_id'] . " ) ",
                    date("d-M-Y", strtotime($data["query"][0]['regi_date'])),
                    date("d-M-Y g:i A", strtotime($data["collection_date"])),
                    strtoupper($data["user_data"][0]['full_name']),
                    date("d-M-Y g:i A", strtotime($data["completed_date1"])),
                    $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
                    strtoupper($data["user_data"][0]['gender']),
                    strtoupper($name),
                    strtoupper($data["query"][0]['test_city_name']),
                    $data["user_data"][0]['mobile']
                );
            }
            if($id=='116354'){
                  $replace = array(
                    'pdf_barcode.png',
                    $id . " ( " . $data["query"][0]['order_id'] . " ) ",
                    date("d-M-Y", strtotime($data["query"][0]['regi_date'])),
                    date("d-M-Y g:i A", strtotime($data["collection_date"])),
                    strtoupper($data["user_data"][0]['full_name']),
                    date("d-M-Y g:i A", strtotime($data["completed_date1"])),
                    $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
                    strtoupper('unspecified'),
                    strtoupper($name),
                    strtoupper($data["query"][0]['test_city_name']),
                    $data["user_data"][0]['mobile']
                );
            }

            if($id=='203181'){
                  $replace = array(
                    'pdf_barcode.png',
                    "199738 ( AHM-28257 ) ",
                    "04-Oct-2019 3:37 PM",
                    "04-Oct-2019 3:37 PM",
                    strtoupper($data["user_data"][0]['full_name']),
                    "07-Oct-2019 5:03 PM",
                    $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
                    'FEMALE',
                    strtoupper($name),
                    strtoupper($data["query"][0]['test_city_name']),
                    $data["user_data"][0]['mobile']
                );
                  
            }

            
            $header = preg_replace($find, $replace, $content[0]["header"]);
            /* echo $header;
              echo $html;
              echo $content[0]["footer"]; die(); */
            //$pdf->SetHTMLHeader($header);






            /* $pdf->AddPage('p', // L - landscape, P - portrait
              '', '', '', '', 5, // margin_left
              5, // margin right
              72, // margin top
              72, // margin bottom
              2, // margin header
              5); // margin footer */

            $pdf->AddPage('p', // L - landscape, P - portrait
                    '', '', '', '', 5, // margin_left
                    5, // margin right
                    5, // margin top
                    5, // margin bottom
                    5, // margin header 
                    5); // margin footer

            /* if($data["login_data"]["id"]==17){
              echo $header;
              echo $html;
              echo $content[0]["footer"];die();
              } */
            $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
            $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
            $emailimg = $base_url . 'user_assets/images/email-icon.png';
            $webimg = $base_url . 'user_assets/images/web-icon.png';
            $lastimg = $base_url . 'user_assets/images/lastimg.png';
            //$pdf->SetHTMLFooter($content[0]["footer"]);
            $data['header'] = $header;

            $data['footer'] = $content[0]["footer"];
            $data['footer'] = str_replace("* Denotes Test not in", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $data['footer']);
            $data['footer'] = str_replace("NABL Scope ** Refered Test", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $data['footer']);
            /* echo "<pre>"; print_r($data); die(); */

            $data["pdfsize"] = $content[0]["withsize"];

            $html = $this->load->view('result_pdf', $data, true); // render the view into HTML 

            $pdf->WriteHTML($html);
            //echo $html;die(); 
            /*   echo $pdf->Output(); die(); */
            $pdf->Output($pdfFilePath, 'F'); // save to file because we can
			
            $name1 = $this->without_approve_report($data);

            $name = $data['query'][0]['order_id'] . "_result_wlpd.pdf";
            $name_orgnl = $data['user_data'][0]["full_name"] . "_result_with_latterhead.pdf";
            $name_orgnl1 = $data['user_data'][0]["full_name"] . "_result.pdf";
            $count = $this->add_result_model->master_fun_get_tbl_val("report_master", array('job_fk' => $id), array("id", "asc"));
            if (!empty($count)) {
                $data1 = array('job_fk' => $id, 'report' => $name, 'status' => 1, "original" => $name_orgnl, 'without_laterpad' => $name1, "without_laterpad_original" => $name_orgnl1, "type" => "c", "updated_date" => date("Y-m-d H:i:s"));
                $this->add_result_model->master_fun_update('report_master', array('job_fk', $id), $data1);
            } else {
                $data1 = array('job_fk' => $id, 'report' => $name, 'status' => 1, "original" => $name_orgnl, "type" => "c", 'without_laterpad' => $name1, "without_laterpad_original" => $name_orgnl1, "created_date" => date("Y-m-d H:i:s"));
                $this->add_result_model->master_fun_insert("report_master", $data1);
            }
            $this->add_result_model->master_fun_update('job_master', array('id', $id), array("report_approve_by" => $data["login_data"]["id"]));
            //print_R($data1); die();
            $this->session->set_flashdata("success", array("Report successfully attached."));
            $this->add_result_model->master_fun_insert("job_log", array("job_fk" => $id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "17", "date_time" => date("Y-m-d H:i:s")));
            
            $auto_completejob = $this->add_result_model->get_val("select auto_completejob from branch_master where status='1' AND id='" . $data['query'][0]["branch_fk"] . "'")[0]['auto_completejob'];
            $tid = $this->add_result_model->get_val("select * from use_formula where status='1' AND job_fk='$id'");
			$count11 = 0;
            foreach ($tid as $key) {
                if ($key['test_status'] == "2") {
                    $count11++;
                }
            }
						//echo 'here2';exit;
            /*if ($count11 == count($tid) && $auto_completejob == 1) {
                $this->job_mark_completed($data['cid']);
				$this->getstock($data['cid']);
                $this->add_result_model->master_fun_insert("job_log", array("job_fk" => $id, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '8-2', "message_fk" => "3", "date_time" => date("Y-m-d H:i:s")));
            }*/
			
            $patient_mob = $data["user_data"][0]['mobile']; //$data['query'][0]["mobile"];			
			$patient_mob = str_replace(" ","", $patient_mob);
            $patient_name = strtoupper($data["user_data"][0]['full_name']); //$data['query'][0]["full_name"];
            if(strlen($patient_mob) == 10)
                $patient_mob = "91" . $patient_mob;

            
            $bearer_token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiI0YjI1MTA3Ny1iMTRlLTQ1NTQtYWM5OC0xZTVkY2QwYWI2MTciLCJ1bmlxdWVfbmFtZSI6Im1haWx0b2RyYW1pdEBnbWFpbC5jb20iLCJuYW1laWQiOiJtYWlsdG9kcmFtaXRAZ21haWwuY29tIiwiZW1haWwiOiJtYWlsdG9kcmFtaXRAZ21haWwuY29tIiwiaHR0cDovL3NjaGVtYXMubWljcm9zb2Z0LmNvbS93cy8yMDA4LzA2L2lkZW50aXR5L2NsYWltcy9yb2xlIjoiQURNSU5JU1RSQVRPUiIsImV4cCI6MjUzNDAyMzAwODAwLCJpc3MiOiJDbGFyZV9BSSIsImF1ZCI6IkNsYXJlX0FJIn0.pTLNa2xcI4XxOEoaFhy3zYrzf7mH-T7DhnhTPNCy56c";

            $curl = curl_init();

            curl_setopt_array($curl, array(
            //CURLOPT_URL => 'https://live-server-1110.wati.io/api/v1/sendTemplateMessage/919724057766',
            //CURLOPT_URL => 'https://live-server-1110.wati.io/api/v1/sendTemplateMessage/919998857906',
            CURLOPT_URL => 'https://live-server-1110.wati.io/api/v1/sendTemplateMessage/' . $patient_mob,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
            "template_name": "sendreport_template_airmed_v2",
            "broadcast_name" : "Send Report airmed ' . $patient_mob . '",
            "parameters": "[{\'name\':\'pname\', \'value\':\''. $patient_name .'\'},{\'name\':\'pdfLink\', \'value\':\'http://airmedlabs.com/upload/report/' . $name . '\' },{\'name\':\'labid\', \'value\':\''. $id .'\'},{\'name\':\'regdate\', \'value\':\''. date("d-M-Y g:i A", strtotime($data["collection_date"]))  .'\'}]"
            }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $bearer_token,
                'Content-Type: application/json'
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            
            $resp = json_decode($response); 

            $msg1 = "";
            if($resp->result == true)
            //if(true)
            {
                $msg1 = "success";
                $this->add_result_model->master_fun_update1("report_master", array('job_fk' => $id), array("whatsapp_sent_status" => "1"));
            }
            else
            {
                $msg1 = "fail";
                $this->add_result_model->master_fun_update1("report_master", array('job_fk' => $id), array("whatsapp_sent_status" => "0"));
            }
			
			$this->add_result_model->master_fun_insert("whatsapp_sent_detail", array("job_id" => $id, "name" => strtoupper($data["user_data"][0]['full_name']), "mobile" => $patient_mob, "reporturl" => "http://airmedlabs.com/upload/report/". $name, "labid" => $id, "regi_date" => $data["collection_date"], "senttime" => date("Y-m-d H:i:s"), "resp_status" => $msg1, "sent_by_user" => $data["login_data"]["id"]));

            $return1 = array('msg' => $msg1, "file" => "http://airmedlabs.com/upload/report/". $name);

            //$return1 = array('msg' => $msg1 );
            
            $docmobile = $data['query'][0]["dmobile"];
            if($docmobile == "9825324056" || $docmobile == "9425107412" 
            || $docmobile == "9824040706" || $docmobile == "9974557757"
            || $docmobile == "9825085059" || $docmobile == "9879725516"
			|| $docmobile == "9879531100" || $docmobile == "8000664008" ) {

                $docname = $data['query'][0]["dname"];
                if(strlen($docmobile) == 10)
                    $docmobile = "91" . $docmobile;

				$curl1 = curl_init();

				curl_setopt_array($curl1, array(
					//CURLOPT_URL => 'https://live-server-1110.wati.io/api/v1/sendTemplateMessage/919724057766',
					//CURLOPT_URL => 'https://live-server-1110.wati.io/api/v1/sendTemplateMessage/919998857906',
					CURLOPT_URL => 'https://live-server-1110.wati.io/api/v1/sendTemplateMessage/' . $docmobile,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'POST',
					CURLOPT_POSTFIELDS =>'{
					"template_name": "sendreport_to_doctor_template_v2_airmed",
					"broadcast_name" : "Send Report to doctor ' . $docmobile . '",
					"parameters": "[{\'name\':\'patient_name\', \'value\':\''. $patient_name .'\'},{\'name\':\'pdfLink\', \'value\':\'http://airmedlabs.com/upload/report/' . $name . '\' }, {\'name\':\'doctor_name\', \'value\':\''. $docname .'\'}]"
					}',
					CURLOPT_HTTPHEADER => array(
						'Authorization: Bearer ' . $bearer_token,
						'Content-Type: application/json'
					),
				));

				$response1 = curl_exec($curl1);

				curl_close($curl1); 
				//echo $response;
				$resp1 = json_decode($response1);

            }
			
			$send_emp = 0;
            if($send_emp == 1) {

                $patient_mob = "8980650365"; //$data['query'][0]["mobile"];
                $patient_mob = str_replace(" ","", $patient_mob);
                $patient_name = strtoupper($data["user_data"][0]['full_name']); //$data['query'][0]["full_name"];
                if(strlen($patient_mob) == 10)
                    $patient_mob = "91" . $patient_mob;

                
                $bearer_token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiI0YjI1MTA3Ny1iMTRlLTQ1NTQtYWM5OC0xZTVkY2QwYWI2MTciLCJ1bmlxdWVfbmFtZSI6Im1haWx0b2RyYW1pdEBnbWFpbC5jb20iLCJuYW1laWQiOiJtYWlsdG9kcmFtaXRAZ21haWwuY29tIiwiZW1haWwiOiJtYWlsdG9kcmFtaXRAZ21haWwuY29tIiwiaHR0cDovL3NjaGVtYXMubWljcm9zb2Z0LmNvbS93cy8yMDA4LzA2L2lkZW50aXR5L2NsYWltcy9yb2xlIjoiQURNSU5JU1RSQVRPUiIsImV4cCI6MjUzNDAyMzAwODAwLCJpc3MiOiJDbGFyZV9BSSIsImF1ZCI6IkNsYXJlX0FJIn0.pTLNa2xcI4XxOEoaFhy3zYrzf7mH-T7DhnhTPNCy56c";

                $curl = curl_init();

                curl_setopt_array($curl, array(
                //CURLOPT_URL => 'https://live-server-1110.wati.io/api/v1/sendTemplateMessage/919724057766',
                //CURLOPT_URL => 'https://live-server-1110.wati.io/api/v1/sendTemplateMessage/919998857906',
                CURLOPT_URL => 'https://live-server-1110.wati.io/api/v1/sendTemplateMessage/' . $patient_mob,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                "template_name": "sendreport_template_airmed_v2",
                "broadcast_name" : "Send Report airmed ' . $patient_mob . '",
                "parameters": "[{\'name\':\'pname\', \'value\':\''. strtoupper($data["user_data"][0]['full_name']) .'\'},{\'name\':\'pdfLink\', \'value\':\'http://airmedlabs.com/upload/report/' . $name . '\' },{\'name\':\'labid\', \'value\':\''. $id .'\'},{\'name\':\'regdate\', \'value\':\''. date("d-M-Y", strtotime($data["query"][0]['date']))  .'\'}]"
                }',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $bearer_token,
                    'Content-Type: application/json'
                ),
                ));

                $response_emp = curl_exec($curl);

                curl_close($curl);
            }
            
            
            echo json_encode($return1);
            
            //redirect("job-master/job-details/" . $id);
        } else {
            show_404();
        }
    }

    function send_reporttest_whatsapp($id) {
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['cid'] = $id;
        $testfk = $this->input->post('testid');
        /* $this->form_validation->set_rules('testid[]', 'testid', 'trim|required');
          if ($this->form_validation->run() != FALSE) { */
        if ($testfk != null) {
            
            $data['query'] = $this->add_result_model->job_details1($data['cid']);

            $result_report = $this->add_result_model->master_fun_get_tbl_val("report_master", array('job_fk' => $id), array("id", "asc"));
            if (!empty($result_report)) {
                
                $filename = $result_report[0]["report"];
                if($filename != "")
                {
                
                    $patient_mob = $data['query'][0]["mobile"];
                    $patient_name = $data['query'][0]["full_name"];
                    if(strlen($patient_mob) == 10)
                        $patient_mob = "91" . $patient_mob;
                    
                    $bearer_token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiI0YjI1MTA3Ny1iMTRlLTQ1NTQtYWM5OC0xZTVkY2QwYWI2MTciLCJ1bmlxdWVfbmFtZSI6Im1haWx0b2RyYW1pdEBnbWFpbC5jb20iLCJuYW1laWQiOiJtYWlsdG9kcmFtaXRAZ21haWwuY29tIiwiZW1haWwiOiJtYWlsdG9kcmFtaXRAZ21haWwuY29tIiwiaHR0cDovL3NjaGVtYXMubWljcm9zb2Z0LmNvbS93cy8yMDA4LzA2L2lkZW50aXR5L2NsYWltcy9yb2xlIjoiQURNSU5JU1RSQVRPUiIsImV4cCI6MjUzNDAyMzAwODAwLCJpc3MiOiJDbGFyZV9BSSIsImF1ZCI6IkNsYXJlX0FJIn0.pTLNa2xcI4XxOEoaFhy3zYrzf7mH-T7DhnhTPNCy56c";

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        //CURLOPT_URL => 'https://live-server-1110.wati.io/api/v1/sendTemplateMessage/919998857906',
                        CURLOPT_URL => 'https://live-server-1110.wati.io/api/v1/sendTemplateMessage/' . $patient_mob,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS =>'{
                        "template_name": "sendreport_template_v4",
                        "broadcast_name" : "Send Report ' . $patient_mob . '",
                        "parameters": "[{\'name\':\'name\', \'value\':\''. $patient_name .'\'},{\'name\':\'pdfLink\', \'value\':\'http://airmedlabs.com/upload/report/' . $filename . '\' }]"
                        }',
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: Bearer ' . $bearer_token,
                            'Content-Type: application/json'
                        ),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);
                    
                    $resp = json_decode($response); 

                    $msg1 = "";
                    //if($resp->result == true)
                    if(true)
                    {
                        $msg1 = "success";
                        $this->add_result_model->master_fun_update1("report_master", array('job_fk' => $id), array("whatsapp_sent_status" => "1"));
                    }
                    else
                    {
                        $msg1 = "fail";
                        $this->add_result_model->master_fun_update1("report_master", array('job_fk' => $id), array("whatsapp_sent_status" => "0"));
                    }
                    
                    $return1 = array('msg' => $msg1);
                    
                    $docmobile = $data['query'][0]["dmobile"];
                    $docname = $data['query'][0]["dname"];
                    if(strlen($docmobile) == 10)
                        $docmobile = "91" . $docmobile;

                    
                    $curl1 = curl_init();

                    curl_setopt_array($curl1, array(
                        //CURLOPT_URL => 'https://live-server-1110.wati.io/api/v1/sendTemplateMessage/919998857906',
                        CURLOPT_URL => 'https://live-server-1110.wati.io/api/v1/sendTemplateMessage/' . $docmobile,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS =>'{
                        "template_name": "sendreport_to_doctor_template_v2",
                        "broadcast_name" : "Send Report to doctor",
                        "parameters": "[{\'name\':\'patient_name\', \'value\':\''. $patient_name .'\'},{\'name\':\'pdfLink\', \'value\':\'http://airmedlabs.com/upload/report/' . $filename . '\' }, {\'name\':\'doctor_name\', \'value\':\''. $docname .'\'}]"
                        }',
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: Bearer ' . $bearer_token,
                            'Content-Type: application/json'
                        ),
                    ));

                    $response1 = curl_exec($curl1);

                    curl_close($curl1); 
                    //echo $response;

                    $resp1 = json_decode($response1);
                    
                    echo json_encode($return1);
                }
            }            
            
        } else {
            show_404();
        }
    }

    function reporttest_preview($id) {

        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['cid'] = $id;
        $testfk = $this->input->post('testall');


        if ($testfk != null) {
            $testall = $this->input->post('testall');
            $new_page = $this->input->post('on_new_page');


            $data['query'] = $this->add_result_model->job_details1($data['cid']);

            $data['processing_center'] = $this->add_result_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $data['query'][0]["branch_fk"]), array("id", "asc"));
            if (empty($data['processing_center'])) {
                $processing_center = '1';
            } else {
                $processing_center = $data['processing_center'][0]["branch_fk"];
            }
            $branch_fk = $data["branch_fk"] = $data['query'][0]["branch_fk"];
            $data['user_booking_info'] = $this->add_result_model->master_fun_get_tbl_val("booking_info", array('status' => 1, 'id' => $data['query'][0]["booking_info"]), array("id", "asc"));
            $data['user_data'] = $this->add_result_model->master_fun_get_tbl_val("customer_master", array('status' => 1, 'id' => $data['query'][0]["custid"]), array("id", "asc"));

            $get_approved_test_list = $this->add_result_model->master_fun_get_tbl_val("approve_job_test", array('status' => '1', 'job_fk' => $data['cid']), array("id", "asc"));
            $approved_test = array();
            foreach ($get_approved_test_list as $at_key) {
                $approved_test[] = $at_key["test_fk"];
            }
            if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
                $data['user_family_info'] = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
                $data['user_data'][0]["gender"] = $data['user_family_info'][0]["gender"];
                $data['user_data'][0]["age"] = $data['user_family_info'][0]["age"];
                $data['user_data'][0]["age_type"] = $data['user_family_info'][0]["age_type"];
                $data['user_data'][0]["full_name"] = $data['user_family_info'][0]["name"];
                $data['user_data'][0]["email"] = $data['user_family_info'][0]["email"];
                $data['user_data'][0]["phone"] = $data['user_family_info'][0]["phone"];
                $data['user_data'][0]["dob"] = $data['user_family_info'][0]["dob"];
            }
            $processing_date = $this->add_result_model->get_val("SELECT IF(job_log.date_time!='',DATE_FORMAT(job_log.date_time,'%Y-%m-%d'),DATE_FORMAT(`job_master`.`date`,'%Y-%m-%d')) AS `date` FROM `job_master` LEFT JOIN `job_log` ON `job_log`.`job_fk`=`job_master`.`id` AND `job_log`.`status`='1' AND `job_log`.`job_status`='6-7' WHERE `job_master`.`status`!='0' AND `job_master`.`id`='" . $data['cid'] . "'");
            $pdate = $processing_date[0]["date"];
            /* Check bitrth date start */
            $this->load->library("util");
            $util = new Util;
            $age = $util->get_ref_age($data['user_data'][0]["dob"], $pdate);
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
            $newalltid = array();

            foreach ($testall as $t_key) {
                $teall = explode("_", $t_key);
                $t_key = $teall[0];
                $newpage = $teall[1];

                $tid[] = $t_key;
                $newalltid[] = $newpage;
                $p_test = $this->add_result_model->get_val("SELECT sub_test_master.* FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `sub_test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $t_key . "'");
                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["sub_test"];
                }
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

                    $get_test_parameter = $this->add_result_model->get_val("SELECT 
  `test_parameter`.*,
  `test_master`.`test_name`,
  tmd.method AS test_method,
  test_master.PRINTING_NAME,
  `test_master`.report_type,
  `test_master`.department_fk,
  test_master.sample as sampletype,
  test_department_master.`name` AS 'department_name' 
FROM
  `test_parameter` 
  INNER JOIN `test_master` 
    ON `test_parameter`.`test_fk` = `test_master`.`id` 
  LEFT JOIN `test_department_master` 
    ON test_department_master.`id` = `test_master`.`department_fk` 
    LEFT JOIN `test_method` AS tmd 
    ON tmd.test_fk = test_master.id AND tmd.branch_fk='" . $data['processing_center'][0]["branch_fk"] . "' and tmd.status='1'
	LEFT JOIN branch_sample_type as btype
	ON btype.test_fk=test_master.id AND btype.branch_fk='" . $data['processing_center'][0]["branch_fk"] . "' and btype.status='1'
WHERE `test_parameter`.`status` = '1' 
  AND `test_master`.`status` = '1' 
  AND `test_parameter`.`test_fk` = '" . $tst_id . "' 
  AND test_parameter.center = '" . $processing_center . "' 
ORDER BY `test_parameter`.order ASC ");
                    $pid = array();
                    foreach ($get_test_parameter as $tp_key) {
                        if (!empty($tp_key["parameter_fk"])) {
                            $pid[] = $tp_key["parameter_fk"];
                        }
                    }
                    if (!empty($pid)) {
                        $para = $this->add_result_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
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
                            $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' and type='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                            $get_test_parameter[$cnt_1]['graph'] = $graph_pic;
                            $get_test_parameter1 = $get_test_parameter[$cnt_1];

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
                        /* $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name,test_master.PRINTING_NAME,test_master.method as test_method FROM `test_master` WHERE id='" . $tst_id . "'"); */
                        $get_test_parameter1 = $this->add_result_model->get_val("SELECT 
  tm.id AS test_fk,
  tm.test_name,
  tm.PRINTING_NAME,
  tm.sample as sampletype,
  tmd.method AS test_method 
FROM
  `test_master` AS tm 
  LEFT JOIN `test_method` AS tmd 
    ON tmd.test_fk = tm.id AND tmd.branch_fk='" . $data['processing_center'][0]["branch_fk"] . "' and tmd.status='1'
LEFT JOIN branch_sample_type as btype
	ON btype.test_fk=tm.id AND btype.branch_fk='" . $data['processing_center'][0]["branch_fk"] . "' and btype.status='1'	
WHERE tm.id = '" . $tst_id . "' ");
                        $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' and type='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                        $get_test_parameter1[0]['graph'] = $graph_pic;
                        $new_data_array[] = $get_test_parameter1[0];
                    }

                    $cnt++;
                }
            }


            $data["new_data_array"] = $new_data_array;
            $data['result_list'] = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('status' => 1, 'job_id' => $data['cid']), array("id", "asc"));
            $latterhead = $this->input->post('latterhead');
            $content = $this->add_result_model->master_fun_get_tbl_val("pdf_design", array('branch_fk' => $branch_fk), array("id", "asc"));

            $data['page_title'] = 'AirmedLabs'; // pass data to the view
            ini_set('memory_limit', '512M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">

            if ($latterhead == 1) {

                $pdfFilePath = FCPATH . "/upload/Reportprint/" . $data['query'][0]['order_id'] . "_printresult.pdf";
                $filename = $data['query'][0]['order_id'] . "_printresult.pdf";
                $contentheder = $content[0]["without_header"];
                $contentfooter = $content[0]["without_footer"];

                $data["pdfsize"] = $content[0]["withoutsize"];
            } else {
                $pdfFilePath = FCPATH . "/upload/Reportprint/" . $data['query'][0]['order_id'] . "_printresult_wlpd.pdf";
                $filename = $data['query'][0]['order_id'] . "_printresult_wlpd.pdf";
                $contentheder = $content[0]["header"];
                $contentfooter = $content[0]["footer"];
                $data["pdfsize"] = $content[0]["withsize"];
            }

            if (file_exists($pdfFilePath)) {
                $this->delete_downloadfile($pdfFilePath);
            }
            if (file_exists($pdfFilePath1)) {
                $this->delete_downloadfile($pdfFilePath1);
            }
            $this->load->library('pdf');
            $pdf = $this->pdf->load();
            $pdf->autoScriptToLang = true;
            $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
            $pdf->autoVietnamese = true;
            $pdf->autoArabic = true;
            $pdf->autoLangToFont = true;
            $pdf->setAutoBottomMargin = 'stretch';

            $name = "DR. Self";
            if ($data["query"][0]['dname'] != "") {
                $name = ucfirst($data["query"][0]['dname']);
            }
            if ($data["query"][0]["model_type"] == 2) {
                $b2b_drname = $this->add_result_model->master_fun_get_tbl_val("sample_job_master", array('barcode_fk' => $data["query"][0]["b2b_id"]), array("id", "asc"));
                $name = ucfirst($b2b_drname[0]['doctor']);
            }
            $base_url = base_url();
            $content = $this->add_result_model->master_fun_get_tbl_val("pdf_design", array('branch_fk' => $branch_fk), array("id", "asc"));
            $data["collection_date"] = $this->add_result_model->get_val("SELECT date_time FROM `job_log` WHERE `status`='1' AND `message_fk`='3' and `job_status` LIKE '%-7%' AND job_fk='" . $data['cid'] . "' order by id desc");
            $data["completed_date"] = $this->add_result_model->get_val("SELECT date_time FROM `job_log` WHERE `status`='1' AND `message_fk`='3' and `job_status` LIKE '%-2%' AND job_fk='" . $data['cid'] . "' order by id desc");
            if (!empty($data["completed_date"])) {
                $data["completed_date1"] = $data["completed_date"][0]["date_time"];
            } else {
                $data["completed_date1"] = date("Y-m-d H:i:s");
            }

            if (!empty($data["collection_date"])) {
                $data["collection_date"] = $data["collection_date"][0]["date_time"];
            } else {
                if (!empty($data["completed_date"])) {
                    $data["collection_date"] = $data["completed_date"][0]["date_time"];
                } else {
                    $data["collection_date"] = date("Y-m-d H:i:s");
                }
                $data["collection_date"] = date("Y-m-d H:i:s");
            }
            $data["content"] = $content;
            //print_r($content); die();
            $find = array(
                '/{{BARCODE}}/',
                '/{{CUSTID}}/',
                '/{{REGDATE}}/',
                '/{{COLLECTIONON}}/',
                '/{{NAME}}/',
                '/{{REPORTDATE}}/',
                '/{{AGE}}/',
                '/{{GENDER}}/',
                '/{{REFFERBY}}/',
                '/{{LOCATION}}/',
                '/{{TELENO}}/'
            );
            $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
            $logo_url = $base_url . 'user_assets/images/logoaastha.png';
            $barecode_url = $base_url . 'user_assets/images/pdf_barcode.png';
            $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
            $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';

            $replace = array(
                'pdf_barcode.png',
                $id . " ( " . $data["query"][0]['order_id'] . " ) ",
                date("d-M-Y", strtotime($data["query"][0]['regi_date'])),
                date("d-M-Y g:i A", strtotime($data["collection_date"])),
                strtoupper($data["user_data"][0]['full_name']),
                date("d-M-Y g:i A", strtotime($data["completed_date1"])),
                $data["user_data"][0]['age'] . " " . $data['user_data'][0]["age_type"],
                strtoupper($data["user_data"][0]['gender']),
                strtoupper($name),
                strtoupper($data["query"][0]['test_city_name']),
                $data["user_data"][0]['mobile']
            );


            $header = preg_replace($find, $replace, $contentheder);


            $pdf->AddPage('p', // L - landscape, P - portrait
                    '', '', '', '', 5, // margin_left
                    5, // margin right
                    5, // margin top
                    5, // margin bottom
                    5, // margin header 
                    5); // margin footer

            /* if($data["login_data"]["id"]==17){
              echo $header;
              echo $html;
              echo $content[0]["footer"];die();
              } */
            $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
            $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
            $emailimg = $base_url . 'user_assets/images/email-icon.png';
            $webimg = $base_url . 'user_assets/images/web-icon.png';
            $lastimg = $base_url . 'user_assets/images/lastimg.png';
            //$pdf->SetHTMLFooter($content[0]["footer"]);
            $data['header'] = $header;

            $data['footer'] = $contentfooter;
            
            $data['footer'] = str_replace("* Denotes Test not in", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $data['footer']);
            $data['footer'] = str_replace("NABL Scope ** Refered Test", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $data['footer']);
            /* echo "<pre>"; print_r($data); die(); */

            $data["pdfsize"] = $content[0]["withsize"];

            $html = $this->load->view('result_pdf', $data, true); // render the view into HTML 

            $pdf->WriteHTML($html);

            $pdf->Output($pdfFilePath, 'F'); // save to file because we can

            $this->add_result_model->master_fun_update('job_master', array('id', $data['cid']), array("prientreport" => '0'));

            echo $filename;
        } else {
            show_404();
        }
    }
    
    
    function job_mark_completed($cid) {
        $this->load->model("job_model");
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
							/*if($branch==4){
								$this->email->cc('pagaravpath@gmail.com'); //pagaravpath@gmail.com for Gandhinagar-Pagarav Branch
							}*/
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
									/*if($branch==4){
										$this->email->cc('pagaravpath@gmail.com'); //pagaravpath@gmail.com for Gandhinagar-Pagarav Branch
									}*/
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
    
    
     function getstock($jobid) {
        $this->load->model("add_result_model");
        $this->load->model("job_model");
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
    
    
    function get_job_details($job_id) {
        $this->load->model("job_model");
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
    
    function check_send_report($cid, $smsalert) {
        $this->load->model('job_model');
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
					if($branch==4){
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
							if($branch==4){
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
                            $sms = $this->send_result_auto_complete($cid);
                            $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $sms["mobile"][0], "message" => $sms["sms"], "created_date" => date("Y-m-d H:i:s")));

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
    
    
    
     function send_result_auto_complete($id) {
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
                if (!empty(cust_rel_mobile)) {
                    $mobile[] = $cust_rel_mobile;
                }
                $custmobile = $cust_rel_mobile;

                $data['send_sms_no'] = $this->add_result_model->get_val("SELECT `send_report_sms`.*,`admin_master`.`name` FROM `send_report_sms` 
				INNER JOIN `admin_master` ON `send_report_sms`.`send_by`=`admin_master`.id 
				WHERE `send_report_sms`.`status`='1' AND send_report_sms.`job_fk`='" . $data['cid'] . "'");

                return array("status" => "1", "sms" => $sms_text, "mobile" => $mobile, "custmobile" => $custmobile, "history" => $data['send_sms_no']);
            } else {
                return array("status" => "0");
            }
        }
    }
    
    

}

?>