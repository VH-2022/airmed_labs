<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Health_advisor extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('Health_advisor_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->helper('string');
        $data["login_data"] = logindata();
        $this->app_track();
        ini_set('display_errors', 1);
    }
    
    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function index() {
        $phone = $this->input->get('phone');
        $status = $this->input->get('status');
        $data['cfname'] = $phone;
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        if ($phone != "") {
            $totalRows = $this->Health_advisor_model->num_row($phone);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "Health_advisor/index?" . http_build_query($get);
            
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Health_advisor_model->master_get_search($phone, $config["per_page"], $page);
            $data['phone'] = $phone;
            $data['status'] = $status;
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        } else {
            $totalRows = $this->Health_advisor_model->num_row($phone);
            $config = array();
            $get = $_GET;
            $config["base_url"] = base_url() . "Health_advisor/index";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Health_advisor_model->master_get_search($phone, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        }

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('health_advisor_list', $data);
        $this->load->view('footer');
    }

    public function delete() {
        $cid = $this->uri->segment('3');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $post['status'] = '0';
        $data['query'] = $this->Health_advisor_model->master_get_spam('health_advisor', $cid, $post);
        $this->session->set_flashdata('success', 'Phone number has deleted successfully.');
        redirect('health_advisor');
    }

    public function change_status() {
        $cid = $this->uri->segment('3');
        $post['status'] = $this->uri->segment('4');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['query'] = $this->Health_advisor_model->change_status('health_advisor', $cid, $post);
        $this->session->set_flashdata('success', 'Status has been updated successfully');

        redirect('health_advisor');
    }

    public function export_csv($phone = "", $status = "") {
        if ($_GET['phone']) {
            $phone = $_GET['phone'];
        }
        if ($_GET['status']) {
            $status = $_GET['status'];
        }
        $result = $this->Health_advisor_model->csv_export($phone, $status);
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"health_advisor_list.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("ID", "Phone", "Status", "Created Date"));

        foreach ($result as $val) {
            if ($val["status"] == '1') {
                $status = "Pending";
            } else if ($val["status"] == '2') {
                $status = "Completed";
            } else {
                $status = "Deleted";
            }
            fputcsv($handle, array($val["id"], $val["phone"], $status, $val["created_date"]));
        }
        fclose($handle);
        exit;
    }

//    public function add() {
//        $data["login_data"] = logindata();
//        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
//        $data['success'] = $this->session->flashdata("success");
//
//        $this->form_validation->set_rules('phone', 'phone', 'trim|required|regex_match[/^[0-9]{10}$/]');
//
//        if ($this->input->post()) {
//            if ($this->form_validation->run() != FALSE) {
//                $phone = $this->input->post('phone');
//                $data = [
//                    "id" => rand(1, 99999999),
//                    "phone" => $phone,
//                    "status" => 1,
//                    "created_date" => date("Y/m/d H:i:s")
//                ];
//
//                $data['query'] = $this->Health_advisor_model->insert_data("health_advisor", $data);
//                $this->session->set_flashdata("success", "Mobile no is added successfully.");
//                redirect("health_advisor/index", "refresh");
//            }
//        }
//
//        $this->load->view('header');
//        $this->load->view('nav', $data);
//        $this->load->view('health_advisory_add');
//        $this->load->view('footer');
//    }
//
//    public function edit($id) {
//        $data["login_data"] = logindata();
//        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
//        $data['success'] = $this->session->flashdata("success");
//
//        $this->form_validation->set_rules('phone', 'phone', 'trim|required|regex_match[/^[0-9]{10}$/]');
//        $data["row"] = $this->Health_advisor_model->get_row('health_advisor', $id);
//        
//        if ($this->input->post()) {
//            if ($this->form_validation->run() != FALSE) {
//                $data = [
//                    "phone" => $this->input->post('phone')
//                ];
//
//                $data['query'] = $this->Health_advisor_model->update_data("health_advisor", $id, $data);
//                $this->session->set_flashdata("success", "Mobile no is updated successfully.");
//                redirect("health_advisor/index", "refresh");
//            }
//        }
//
//        $this->load->view('header');
//        $this->load->view('nav', $data);
//        $this->load->view("health_advisory_edit");
//        $this->load->view('footer');
//    }

}

?>
