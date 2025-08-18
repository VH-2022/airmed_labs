<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Callreason_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('Callreason_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $data["login_data"] = logindata();
    }

    function index() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $this->load->library('pagination');

        $state_serch = $data['reason_name'] = $this->input->get('reason_name');
        if ($state_serch != "") {
            $total_row = $this->Callreason_model->num_row_srch_state_list($state_serch);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "Callreason_master/index?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Callreason_model->row_srch_state_list($state_serch, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $total_row = $this->Callreason_model->num_row('call_reason_master', array('status' => 1));
            $config["base_url"] = base_url() . "Callreason_master/index";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Callreason_model->srch_state_list($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('reason_list', $data);
        $this->load->view('footer');
    }

    function add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('reason', 'Call Reason', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $reason = $this->input->post('reason');
            $data['query'] = $this->Callreason_model->master_fun_insert("call_reason_master", array(
                "reason" => $reason,
                "status" => '1',
                "created_date" => date('Y-m-d H:i:s'),
                "created_by" => $data["login_data"]["id"]
            ));
            $this->session->set_flashdata("success", array("Reason added successfully."));
            redirect("Callreason_master/index", "refresh");
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('reason_add', $data);
            $this->load->view('footer');
        }
    }

    function delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("call_reason_master", array("id", $cid), array("status" => "0"));
        $this->session->set_flashdata("success", array("Reason successfully deleted."));
        redirect("Callreason_master/index", "refresh");
    }

    function edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('reason', 'Call Reason', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $reason = $this->input->post('reason');
            $data['query'] = $this->Callreason_model->master_fun_update("call_reason_master", array("id", $data["cid"]), array("reason" => $reason));
            $this->session->set_flashdata("success", array("Country successfully updated."));
            redirect("Callreason_master/index", "refresh");
        } else {
            $data['query'] = $this->Callreason_model->master_fun_get_tbl_val("call_reason_master", array("id" => $data["cid"]), array("id", "desc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('reason_edit', $data);
            $this->load->view('footer');
        }
    }

    function get_state() {

        $country = $this->uri->segment(3);
        $data = $this->user_model->master_fun_get_tbl_val("state", array("country_fk" => $country, "status" => 1), array("id", "desc"));
        echo "<option value=''> Select State </option>";
        for ($i = 0; $i < count($data); $i++) {
            $id = $data[$i]['id'];
            $name = $data[$i]['state_name'];
            echo "<option value='$id'> $name </option>";
        }
    }

}

?>
