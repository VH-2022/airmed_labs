<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Approve_result_from_mail extends CI_Controller {

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

    function all_test_approve_details_mail() {
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

        $tid111 = explode(",", $data['tid1']);
        $tid = array();
        foreach ($tid111 as $rid_key) {
            if (!in_array($rid_key, $tid)) {
                $tid[] = $rid_key;
            }
        }
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
        $this->load->view('approve_result_from_mail', $data); 
        //$this->load->view('footer');
    }
    function add_value_exists2() {
        $files = $_FILES;
        $this->load->library('upload');
        //echo "<pre>"; print_r($_POST); die();
        $data["login_data"] = logindata();
        $count = $this->input->post('count');
        $tid = $this->input->post('tid');
        $para_job_id = $this->input->post('para_job_id');
        //$this->add_result_model->master_fun_update("use_formula", array("job_fk", $para_job_id), array("status" => 0));
        $test_id_array = array();
        for ($i = 0; $i < $count; $i++) {
            $para_id = $this->input->post('parameter_id_' . $i);
            $test_id = $this->input->post('test_id_' . $i);
            //$test_id = $tid[$i];
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
            }
            /* END */
            /* Nishit Graph Upload start */
            
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
                $val_add = $this->add_result_model->master_fun_insert("user_formula_pic", $data12);
            }
        }
 foreach ($send_approve_test_id_array as $tpkey) {
            if ($tpkey > 0) {
                $this->add_result_model->master_fun_update1("use_formula", array("job_fk" => $para_job_id, "test_fk" => $tpkey), array("test_status" => 2));
            }
        }
        $this->add_result_model->master_fun_insert("job_log", array("job_fk" => $para_job_id, "created_by" => "", "updated_by" => 50, "deleted_by" => "", "job_status" => '', "message_fk" => "32", "date_time" => date("Y-m-d H:i:s")));
        //$this->session->set_userdata("closeFancyBox", array('parent.close_popup1();'));
        redirect('Approve_result_from_mail/all_test_approve_details_mail?jid=' . $para_job_id . '&tid=' . $this->input->post('tid'));
    }
}

?>