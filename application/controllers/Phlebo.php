<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Phlebo extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('customer_model');
        $this->load->model('user_model');
        $this->load->model('job_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('email');
        $data["login_data"] = logindata();
        $this->load->helper('string');
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
        $this->load->library('form_validation');
        $this->form_validation->set_rules('cal[]', 'Slot', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $this->master_model->master_fun_update_new("phlebo_calender", array("status", "1"), array("status" => "0", "updated_by" => $data["login_data"]["id"]));
            $cal = $this->input->post("cal");
            foreach ($cal as $value) {
                $val = explode("-", $value);
                $this->master_model->master_fun_insert("phlebo_calender", array("day_fk" => $val[0], "time_slot_fk" => $val[1], "created_by" => $data["login_data"]["id"], "createddate" => date("Y-m-d H:i:s")));
            }
            $this->session->set_flashdata("success", array("Data successfully updated."));
            redirect("Phlebo", "refresh");
        } else {
            $data["week_day"] = $this->master_model->master_fun_get_tbl_val("phlebo_day_master", array("status" => "1"), array("id", "asc"));
            $data["time_slot"] = $this->master_model->master_fun_get_tbl_val("phlebo_time_slot", array("status" => "1"), array("start_time", "asc"));
            $data["calendar_slot"] = $this->master_model->master_fun_get_tbl_val("phlebo_calender", array("status" => "1"), array("id", "asc"));
            $data['success'] = $this->session->flashdata("success");
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view("phlebo_manage");
            $this->load->view('footer');
        }
    }

    function visit_request(){
        
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $name = $this->input->get('name');
        $date = $this->input->get('date');
        $status = $this->input->get('status');
        if ($name != "" || $date != "" || $status != "") {
            $srchdata = array("name" => $name, "date" => $date, "status" => $status);
            $total_row = $this->customer_model->visit_request_num_row_srch($srchdata);
            $data['name'] = $name;
            $data['date'] = $date;
            $data['status'] = $status;
            if ($_GET['debug'] == "1") {
                echo $this->db->last_query();
                $this->db->close();
                die();
            }
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "phlebo/visit-request?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->customer_model->visit_request_row_srch($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["page"] = $page;
        } else {
            $srchdata = array();
            $total_row = $this->customer_model->visit_request_num_row_srch($srchdata);
            $config["base_url"] = base_url() . "phlebo/visit-request";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->customer_model->visit_request_row_srch($srchdata, $config["per_page"], $page);
            $data["page"] = $page;
            $data["links"] = $this->pagination->create_links();
        }
        $data['site_setting'] = $this->customer_model->master_fun_get_tbl_val("patholab_home_master", array(), array("id", "asc"));
        $data['phlebo_km_wise_rs'] = $data['site_setting'][0]['phlebo_km_wise_rs'];
       
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('visit_request_list', $data);
        $this->load->view('footer');
    }

    public function update_request_status() {
        $data["login_data"] = logindata();
        $user_id = $data["login_data"]["id"];
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        if (!empty($id) && in_array($status, ['approved', 'rejected'])) {

            $this->job_model->master_fun_update("phlebo_request", array("id", $id), array("status" => $status,"action_by" => $user_id, "action_date_time" => date("Y-m-d H:i:s")));
            echo "success";
        } else {
            echo "invalid";
        }
    }

}
