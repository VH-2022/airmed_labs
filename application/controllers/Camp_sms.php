<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Camp_sms extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('camp_sms_model');

        $this->load->library('form_validation');
        $this->load->library('pagination');

        $data["login_data"] = logindata();
    }

    function index() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $data['error'] = $this->session->flashdata("error");
        $sms = $this->input->get_post('sms');
        $date = $this->input->get_post('date');
        $end_date = $this->input->get_post('end_date');
        $sender = $this->input->get_post('sender');
        $status = $this->input->get_post('status');
        if ($sms != '' || $date != '' || $sender != '' || $status != '') {
            $srch = array("sms" => $sms, "date" => $date, "end_date" => $end_date, "sender" => $sender, "status" => $status);
            $data['sms'] = $sms;
            $data['date'] = $date;
            $data['end_date'] = $end_date;
            $data['sender'] = $sender;
            $data['status'] = $status;
            $totalRows = $this->camp_sms_model->sms_num($srch);
            $config = array();
            $get = $_GET;
            $config["base_url"] = base_url() . "Camp_sms/index?sms=$sms&date=$date&sender=$sender&status=$status";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 100;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next <span class="icon-text">&#59230;</span>';
            $config['prev_link'] = '<span class="icon-text">&#59229;</span> Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data['query'] = $this->camp_sms_model->sms_list($srch, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data['count'] = $page;
        } else {
            $srch = array();
            $totalRows = $this->camp_sms_model->sms_num($srch);
            $config = array();
            $get = $_GET;
            $config["base_url"] = base_url() . "Camp_sms/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 100;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next <span class="icon-text">&#59230;</span>';
            $config['prev_link'] = '<span class="icon-text">&#59229;</span> Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data['query'] = $this->camp_sms_model->sms_list($srch, $config["per_page"], $page);

            $data["links"] = $this->pagination->create_links();
            $data['count'] = $page;
        }
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('camp_sms', $data);
        $this->load->view('footer');
    }

    function add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->library('csvimport');
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $data['error'] = $this->session->flashdata("error");

        $this->form_validation->set_rules("sms_name", ' ', 'trim|required');
        $this->form_validation->set_rules("schedule_date", ' ', 'trim|required');
        // $this->form_validation->set_rules("doctor_list", ' ', 'trim|callback_image');
        $this->form_validation->set_rules("sender", ' ', 'trim|required');

        if ($this->form_validation->run() != FALSE) {
            $sms = $this->input->post('sms_name');
            $schedule_date = $this->input->post('schedule_date');
            $sender = $this->input->post('sender');
            $data = array(
                'sms' => $sms,
                'schedule_date' => date('Y-m-d H:i:s', strtotime($schedule_date)),
                'sender' => $sender
            );
            $id_login = $this->camp_sms_model->master_fun_insert("camp_sms_master", $data);

            if ($_FILES["doctor_list"]["name"]) {
                $config['upload_path'] = './upload/camp';
                $config['allowed_types'] = 'csv';
                $config['file_name'] = time() . $_FILES["doctor_list"]["name"];
                $this->load->library('upload', $config);

                if (!$this->upload->do_upload("doctor_list")) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    $this->session->set_flashdata("error", array($error));
                    redirect("Camp_sms", "refresh");
                } else {

                    $file_data = $this->upload->data();

                    $file_path = './upload/camp/' . $file_data['file_name'];

                    if ($this->csvimport->get_array($file_path)) {

                        $csv_array = $this->csvimport->get_array($file_path);

                        $cnt = 0;
                        $cnt2 = 0;
                        $cnt3 = 0;
                        $cnt4 = 0;

                        foreach ($csv_array as $row) {
                            $cnt3++;
                            $mobile = $this->camp_sms_model->get_val("select mobile from camp_sms_doctor where mobile='" . $row['mobile'] . "' and sms_fk='" . $id_login . "' and status='1' ");
                            $num_length = strlen((string) $row['mobile']);

                            if ($row['mobile'] != "") {
                                $cnt++;
                                $data1 = array(
                                    "dr_name" => $row['name'],
                                    "mobile" => $row['mobile'],
                                    "sms_fk" => $id_login,
                                    "created_date" => date('Y-m-d h:i:s'),
                                    "created_by" => $login_id
                                );
                                if (count($mobile) == 0 && $num_length >= 10) {
                                    $update = $this->camp_sms_model->master_fun_insert("camp_sms_doctor", $data1);
                                }
                            } else {
                                $cnt2++;
                            }
                        }
                    }
                }
            }
            $this->session->set_flashdata("success", array("Camp Sms Successfully Inserted"));
            redirect("Camp_sms", "refresh");
        }
        $this->load->view("header", $data);
        $this->load->view("nav", $data);
        $this->load->view("camp_add");
        $this->load->view("footer");
    }

    function viewlist() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->library('csvimport');
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        $type = $this->uri->segment(3);
        $data['list_view'] = $this->camp_sms_model->get_val("select cm.sms,cm.send,cmd.id,cmd.sms_fk,cmd.mobile from camp_sms_master as cm INNER JOIN camp_sms_doctor as cmd  on cm.id = cmd.sms_fk where cm.id='" . $type . "' and cm.status='1' and cmd.status='1'");
        $this->load->view("header", $data);
        $this->load->view("nav", $data);
        $this->load->view("camp_view_list", $data);
        $this->load->view("footer");
    }

    function delete() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $type = $this->uri->segment(3);
        $type_sms = $this->uri->segment(2);

        $data = array(
            'status' => 0
        );
        $this->camp_sms_model->master_fun_update('camp_sms_master', $type, $data);
        $this->camp_sms_model->master_fun_update('camp_sms_doctor', $type, $data);
        $pid = $this->camp_sms_model->fetchdatarow('sms_fk', 'camp_sms_doctor', array('sms_fk' => $type_sms));
        $ses = " Camp Sms Successfully Deleted!";
        $this->session->set_flashdata("success", $ses);
        if ($type_sms) {
            redirect("Camp_sms", "refresh");
        } else {
            redirect("Camp_sms/viewlist/" . $pid->sms_fk, "refresh");
        }
    }

    function sub_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $count = 0;

        $data = array(
            'status' => 0
        );
        $ids = $this->input->post('ids');
        $test = explode(",", $ids);

        foreach ($test as $id) {
            $this->camp_sms_model->master_fun_update('camp_sms_doctor', $id, $data);
            $count++;
        }
        $ses = " Camp Sms Successfully Deleted!";
        $this->session->set_flashdata("success", $ses);
    }

    function view_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $type = $this->uri->segment(3);
        $type_sms = $this->uri->segment(4);
        $data = array(
            'status' => 0
        );
        $this->camp_sms_model->master_fun_update('camp_sms_doctor', $type, $data);
        $ses = " Camp Sms Successfully Deleted!";
        $this->session->set_flashdata("success", $ses);

        redirect("Camp_sms/viewlist/" . $type_sms, "refresh");
    }

    function get_send_sms() {
        $sms = $this->camp_sms_model->get_val("SELECT id,sms FROM `camp_sms_master` WHERE `status`='1' AND `sender`='1' AND DATE_FORMAT(`schedule_date`, '%Y-%m-%d %H:%i') ='" . date("Y-m-d H:i") . "'");
        //$sms = $this->camp_sms_model->get_val("SELECT id,sms FROM `camp_sms_master` WHERE `status`='1' AND DATE_FORMAT(`schedule_date`, '%Y-%m-%d %H:%i') ='2018-01-23 11:56'");


        $sms_number = array();
        foreach ($sms as $key1) {
            $sms_details = $this->camp_sms_model->get_val("SELECT id,mobile FROM `camp_sms_doctor` WHERE `status`='1' AND sms_fk='" . $key1["id"] . "'");
            $key1["number_list"] = $sms_details;
            $sms_number[] = $key1;
        }

        foreach ($sms_number as $key) {
            foreach ($key["number_list"] as $key1) {
                $update = $this->camp_sms_model->master_fun_insert("camp_admin_alert_sms", array("mobile_no"=>$key1["mobile"],"message"=>$key["sms"],"created_date"=>date("Y-m-d H:i:s")));
            }
        }
        echo "Done";
    }

}
