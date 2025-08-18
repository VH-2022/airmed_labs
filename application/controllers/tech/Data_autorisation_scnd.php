<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Data_autorisation_scnd extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('tech/data_autorize_scnd_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('email');
        $this->load->helper('string');
        $data["login_data"] = logindata();
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function index() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $cntr_arry = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data["login_data"]['branch_fk'] as $key) {
                $cntr_arry[] = $key["branch_fk"];
            }
        }
        $branch = implode(",", $cntr_arry);
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $totalRows = $this->data_autorize_scnd_model->autho_list_count($branch);
        $config = array();
        $config["base_url"] = base_url() . "data_autorisation/index/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 50;
        $config["uri_segment"] = 4;
        $config['cur_tag_open'] = '<span>';
        $config['cur_tag_close'] = '</span>';
        $config['next_link'] = 'Next &rsaquo;';
        $config['prev_link'] = '&lsaquo; Previous';
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data["query"] = $this->data_autorize_scnd_model->autho_list($config["per_page"], $page, $branch);
        $data["links"] = $this->pagination->create_links();
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('tech/data_authorise_scnd', $data);
        $this->load->view('footer');
    }

    /*    function get_data_parameter() {
      if (!is_loggedin()) {
      redirect('login');
      }
      $data["login_data"] = logindata();
      $barc = $this->input->post('barcode');
      $branch = $this->input->post('branch');
      $data = $this->data_autorize_scnd_model->get_parameter_withvalue($barc,$branch);
      $cnt=1;
      foreach ($data as $all) {
      echo '<tr>
      <td>'.$all->parameter_name.'</td>
      <td><input type="text" value="'.$all->result_value*$all->multiply_by.'" name="paravalue_'.$cnt.'">
      <input type="hidden" name="add_values_id_'.$cnt.'" value="'.$all->id.'"></td>
      <input type="hidden" name="job_id_'.$cnt.'" value="'.$all->job_id.'"></td>
      <input type="hidden" name="parameter_id_'.$cnt.'" value="'.$all->parameter_id.'"></td>
      <input type="hidden" name="count" value="'.$cnt.'"></td>
      </tr>';
      $cnt++;
      } if(empty($data)) {
      echo '<tr><td colspan="3">data not available.</td></tr>';
      }
      } */

    function get_data_parameter() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $this->load->model("add_result_model");
        $barc = $this->input->post('barcode');
        $branch = $this->input->post('branch');

        $branch = $this->input->post('branch');
        $processing_center = $this->data_autorize_scnd_model->master_fun_get_tbl_val("processing_center", array("status" => "1", "lab_fk" => $branch), array("id", "desc"));
        $job_details = $this->data_autorize_scnd_model->master_fun_get_tbl_val("job_master", array("status !=" => "0", "barcode" => $barc), array("id", "desc"));
        /* CHECK JOB TEST START */
        $data['query'] = $this->add_result_model->job_details($job_details[0]->id);
        $tid = array();
        $data['parameter_list'] = array();
        if (trim($data['query'][0]['testid']) == null && $data['query'][0]["packageid"] != null) {
            $package_id = $data['query'][0]["packageid"];
            $pid = explode("%", $data['query'][0]['packageid']);
            foreach ($pid as $pkey) {
                $p_test = $this->data_autorize_scnd_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");
                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["test_fk"];
                }
            }
        } else if (trim($data['query'][0]['testid']) != null && $data['query'][0]["packageid"] != null) {

            $tid = explode(",", $data['query'][0]['testid']);
            $package_id = $data['query'][0]["packageid"];
            $pid = explode("%", $data['query'][0]['packageid']);
            foreach ($pid as $pkey) {
                $p_test = $this->data_autorize_scnd_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");

                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["test_fk"];
                }
            }
        } else {
            $tid = explode(",", $data['query'][0]['testid']);
        }
        //print_R($tid); die();
        foreach ($tid as $t_key) {
            $p_test = $this->data_autorize_scnd_model->get_val("SELECT sub_test_master.* FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `sub_test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $t_key . "'");
            foreach ($p_test as $tp_key) {
                $tid[] = $tp_key["sub_test"];
            }
        }
        
        
        
        
        $tid = array_unique($tid);
        /* END */
        $data = $this->data_autorize_scnd_model->get_parameter_withvalue($barc, $branch, $processing_center[0]->branch_fk, $tid);
        //echo $this->db->last_query();
        //die();
        $cnt = 1;
        $test_ids = array();
        foreach ($data as $all) {
            if (!in_array($all->test_fk, $test_ids)) {
                echo '<tr><td colspan="3"><b>' . $all->test_name . '</b></td></tr>';
                $test_ids[] = $all->test_fk;
            }
            echo '<tr>
            <td>' . $all->parameter_name . '</td>
            <td><input type="text" value="' . $all->result_value * $all->multiply_by . '" name="paravalue_' . $cnt . '">
                <input type="hidden" name="add_values_id_' . $cnt . '" value="' . $all->id . '"></td>
                <input type="hidden" name="job_id_' . $cnt . '" value="' . $all->job_id . '"></td>
                <input type="hidden" name="parameter_id_' . $cnt . '" value="' . $all->parameter_id . '"></td>
                <input type="hidden" name="count" value="' . $cnt . '"></td>
            </tr>';
            $cnt++;
        } if (empty($data)) {
            echo '<tr><td colspan="3">data not available.</td></tr>';
        }
    }

    function accept_results() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $count = $this->input->post('count');
        for ($i = 1; $i <= $count; $i++) {
            $paravalue = $this->input->post('paravalue_' . $i);
            $add_values_id = $this->input->post('add_values_id_' . $i);
            $job_id = $this->input->post('job_id_' . $i);
            $parameter_id = $this->input->post('parameter_id_' . $i);
            $this->data_autorize_scnd_model->master_fun_update('instument_data_storage', array("id", $add_values_id), array("status" => 'R', "result_value" => $paravalue));
            $test_list = $this->data_autorize_scnd_model->get_master_get_data('job_test_list_master', array("job_fk" => $job_id, "status" => 1), array("id", "desc"));
            foreach ($test_list as $test) {
                $get_data = $this->data_autorize_scnd_model->get_master_get_data('user_test_result', array("job_id" => $job_id, "test_id" => $test['test_fk'], "parameter_id" => $parameter_id, "status" => 1), array("id", "desc"));
                if (!empty($get_data)) {
                    foreach ($get_data as $get) {
                        $this->data_autorize_scnd_model->master_fun_update('user_test_result', array("id", $get['id']), array("value" => $paravalue));
                    }
                } else {
                    $count1 = $this->data_autorize_scnd_model->num_row('test_parameter', array("test_fk" => $test['test_fk'], "parameter_fk" => $parameter_id, "status" => 1));
                    if ($count1 != 0) {
                        $this->data_autorize_scnd_model->master_fun_insert('user_test_result', array("job_id" => $job_id, "test_id" => $test['test_fk'], "parameter_id" => $parameter_id, "value" => $paravalue, "created_by" => $data["login_data"]["id"], "created_date" => date("Y-m-d H:i:s")));
                    }
                }
            }
        }
        $get_barcode = $this->data_autorize_scnd_model->fetchdatarow('lab_id', 'instument_data_storage', array("id" => $add_values_id));
        $this->data_autorize_scnd_model->master_fun_update('instument_data_storage', array("lab_id", $get_barcode->lab_id), array("status" => 'R'));
    }

    function accept_results_new() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $login_data = $data["login_data"] = logindata();
        $count = $this->input->post('count');
        for ($i = 1; $i <= $count; $i++) {
            $paravalue = $this->input->post('paravalue_' . $i);
            $add_values_id = $this->input->post('add_values_id_' . $i);
            $job_id = $this->input->post('job_id_' . $i);
            $parameter_id = $this->input->post('parameter_id_' . $i);
            $this->data_autorize_scnd_model->master_fun_update('instument_data_storage', array("id", $add_values_id), array("status" => 'R', "result_value" => $paravalue));

            $this->load->model("add_result_model");
            $data['query'] = $this->add_result_model->job_details($job_id);

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

            /* END */



            $test_list = $this->data_autorize_scnd_model->get_master_get_data('job_test_list_master', array("job_fk" => $job_id, "status" => 1), array("id", "desc"));

            //print_r($tid);die();
            $send_approve_test_id_array = array();
            foreach ($tid as $test) {
                $get_data = $this->data_autorize_scnd_model->get_master_get_data('user_test_result', array("job_id" => $job_id, "test_id" => $test, "parameter_id" => $parameter_id, "status" => 1), array("id", "desc"));
                if (!empty($get_data)) {

                    foreach ($get_data as $get) {
                        $this->data_autorize_scnd_model->master_fun_update('user_test_result', array("id", $get['id']), array("value" => $paravalue));
                    }
                    $data12 = array(
                        "job_fk" => $job_id,
                        "test_fk" => $test,
                        "use_formula" => 0,
                        "on_new_page" => 0,
                        "is_culture" => 0,
                        "performed" => 1,
                        "performed_reason" => "",
                        "status" => 1,
                        "test_status" => 1
                    );
                    $uftid = $this->data_autorize_scnd_model->get_val("select id from use_formula where status='1' and job_fk='" . $job_id . "' and test_fk='" . $test . "'");
                    if (empty($uftid)) {
                        $val_add = $this->data_autorize_scnd_model->master_fun_insert("use_formula", $data12);
                    } else {
                        $this->data_autorize_scnd_model->master_fun_update1("use_formula", array("job_fk" => $job_id, "test_fk" => $test), $data12);
                    }
                    if (!in_array($test, $send_approve_test_id_array)) {
                        $send_approve_test_id_array[] = $test;
                    }
                } else {
                    $count1 = $this->data_autorize_scnd_model->num_row('test_parameter', array("test_fk" => $test, "parameter_fk" => $parameter_id, "status" => 1));
                    if ($count1 != 0) {
                        $this->data_autorize_scnd_model->master_fun_insert('user_test_result', array("job_id" => $job_id, "test_id" => $test, "parameter_id" => $parameter_id, "value" => $paravalue, "created_by" => $data["login_data"]["id"], "created_date" => date("Y-m-d H:i:s")));
						$data12 = array(
                        "job_fk" => $job_id,
                        "test_fk" => $test,
                        "use_formula" => 0,
                        "on_new_page" => 0,
                        "is_culture" => 0,
                        "performed" => 1,
                        "performed_reason" => "",
                        "status" => 1,
                        "test_status" => 1
                    );
                    $uftid = $this->data_autorize_scnd_model->get_val("select id from use_formula where status='1' and job_fk='" . $job_id . "' and test_fk='" . $test . "'");
                    if (empty($uftid)) {
                        $val_add = $this->data_autorize_scnd_model->master_fun_insert("use_formula", $data12);
                    } else {
                        $this->data_autorize_scnd_model->master_fun_update1("use_formula", array("job_fk" => $job_id, "test_fk" => $test), $data12);
                    }
                    if (!in_array($test, $send_approve_test_id_array)) {
                        $send_approve_test_id_array[] = $test;
                    }
                    }

                    $data12 = array(
                        "job_fk" => $job_id,
                        "test_fk" => $test,
                        "use_formula" => 0,
                        "on_new_page" => 0,
                        "is_culture" => 0,
                        "performed" => 1,
                        "performed_reason" => "",
                        "status" => 1,
                        "test_status" => 0
                    );
                    $uftid = $this->data_autorize_scnd_model->get_val("select id from use_formula where status='1' and job_fk='" . $job_id . "' and test_fk='" . $test . "'");
                    if (empty($uftid)) {
                        $val_add = $this->data_autorize_scnd_model->master_fun_insert("use_formula", $data12);
                    }
                }
            }
        }
        /* Nishit Send For approve is started */
        $get_job_details = $this->data_autorize_scnd_model->get_val("SELECT job_master.id,job_master.order_id,job_master.branch_fk,branch_master.branch_name from job_master inner join branch_master on job_master.branch_fk=branch_master.id where job_master.id='" . $job_id . "'");
        $check_is_mail_send = $this->data_autorize_scnd_model->master_fun_get_tbl_val("send_report_for_approve", array('branch_fk' => $get_job_details[0]["branch_fk"], "status" => 1), array("id", "asc"));
        if (!empty($send_approve_test_id_array) && !empty($check_is_mail_send)) {
            $job_test_details = array();
            foreach ($send_approve_test_id_array as $utdid) {
                $get_job_details1 = $this->data_autorize_scnd_model->get_val("SELECT test_name from test_master where id='" . $utdid . "'");
                $job_test_details[] = $get_job_details1[0]["test_name"];
            }
            $get_admin_details1 = $this->data_autorize_scnd_model->get_val("SELECT * from admin_master where id='" . $login_data["id"] . "'");
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
            $this->email->to($check_is_mail_send[0]->email);
            $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
            $this->email->subject("Approve Uploaded Test Result");
            $this->email->message($message1);
            $this->email->send();
        }
        /* END */

        $get_barcode = $this->data_autorize_scnd_model->fetchdatarow('lab_id', 'instument_data_storage', array("id" => $add_values_id));
        $this->data_autorize_scnd_model->master_fun_update('instument_data_storage', array("lab_id", $get_barcode->lab_id), array("status" => 'R'));
    }

    function view_details($barcode) {
        $data['all_show'] = $this->data_autorize_scnd_model->get_master_get_data('instument_data_storage', array("lab_id" => $barcode, "status" => 'N'), array("id", "desc"));
        $this->load->view('tech/data_authorise_view_all', $data);
    }

}

?>