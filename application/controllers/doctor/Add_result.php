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
        $data['query'] = $this->add_result_model->job_details($data['cid']);
        $data['processing_center'] = $this->add_result_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $data['query'][0]["branch_fk"]), array("id", "asc"));
        if (empty($data['processing_center'])) {
            $processing_center = '1';
        } else {
            $processing_center = $data['processing_center'][0]["branch_fk"];
        }
        $this->load->library("util");
        $util = new Util;
        $data["age"] = $util->get_age($data['query'][0]["dob"]);
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
            $data["age1"] = $util->get_age($data['user_family_info'][0]["dob"]);
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
        $this->load->library("util");
        $util = new Util;

        $age = $util->get_age($data['user_data'][0]["dob"]);

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
        $cnt = 0;
        $new_data_array = array();
        foreach ($tid as $tst_id) {
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
        $data["new_data_array"] = $new_data_array;
        $data['result_list'] = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('status' => 1, 'job_id' => $data['cid']), array("id", "asc"));
//echo "<pre>";
//print_r($data["new_data_array"]);
//die();
//$data['unit_list'] = $this->add_result_model->unit_list();
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
        $data['query'] = $this->add_result_model->job_details($data['cid']);
        $data['processing_center'] = $this->add_result_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $data['query'][0]["branch_fk"]), array("id", "asc"));
        if (empty($data['processing_center'])) {
            $processing_center = '1';
        } else {
            $processing_center = $data['processing_center'][0]["branch_fk"];
        }
        $this->load->library("util");
        $util = new Util;
        $data["age"] = $util->get_age($data['query'][0]["dob"]);
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

        $tid = array($data['tid1']);
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
        $data['query'] = $this->add_result_model->job_details($data['cid']);
        $data['processing_center'] = $this->add_result_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $data['query'][0]["branch_fk"]), array("id", "asc"));
        if (empty($data['processing_center'])) {
            $processing_center = '1';
        } else {
            $processing_center = $data['processing_center'][0]["branch_fk"];
        }
        $this->load->library("util");
        $util = new Util;
        $data["age"] = $util->get_age($data['query'][0]["dob"]);
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

        $tid = explode(",", $data['tid1']);
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
             