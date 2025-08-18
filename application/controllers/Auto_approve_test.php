<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auto_approve_test extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('Auto_approve_test_model');
        
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->helper('string');
        //echo current_url(); die();
        //echo "sdfsdf"; exit;
        $data["login_data"] = logindata();
    }

    function index() {
        $data['test'] = $test = $this->input->get('test');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        if ($test != '') {
            $totalRows = $this->Auto_approve_test_model->num_row($test);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "Auto_approve_test?" . http_build_query($get);
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Auto_approve_test_model->sub_list($test, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        } else {
            $totalRows = $this->Auto_approve_test_model->num_row($branchid, $email);
            $config = array();
            $get = $_GET;
            $config["base_url"] = base_url() . "Auto_approve_test/index";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next <span class="icon-text">&#59230;</span>';
            $config['prev_link'] = '<span class="icon-text">&#59229;</span> Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");

            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data['query'] = $this->Auto_approve_test_model->sub_list($branchid, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }
        $data['test_list'] = $this->Auto_approve_test_model->get_val("select * from test_master where status='1' AND id NOT IN(select test_fk from auto_approve_test)");
//        $data['branch'] = $this->Dr_assign_model->master_get_branch();
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('auto_appr_test', $data);
        $this->load->view('footer');
    }

    function add() {
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $this->load->library('form_validation');
        $this->form_validation->set_rules('test', 'Test Name', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        if ($this->form_validation->run() != FALSE) {
            $test = $this->input->post('test');
            $status = $this->input->post('status');
            $data = array(
                'test_fk' => $test,
                'status' => $status
            );
            $data['query'] = $this->Auto_approve_test_model->master_get_insert("auto_approve_test", $data);
            $this->session->set_flashdata("success", 'Test Successful Added for auto approve.');
            echo 1;
            redirect("Auto_approve_test/index", "refresh");
        } else {
            echo 0;
        }
    }

    public function edit() {
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $sub_id = $this->uri->segment('3');
        $query = $this->Auto_approve_test_model->get_val("select * from auto_approve_test where id = '" . $sub_id . "' ");
        echo json_encode(array('id' => $query[0]['id'], 'test_fk' => $query[0]['test_fk'], 'status' => $query[0]['status']));
    }

    public function update() {
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $sub_id = $this->input->post('tp_id');

        $status = $this->input->post('status_edit');
        $data = array(
            'status' => $status
        );
        $data['query'] = $this->Auto_approve_test_model->master_get_update("auto_approve_test", array('id' => $sub_id), $data);

        $this->session->set_flashdata("success", 'Test Successful Updated For Auto Approve.');
        redirect("Auto_approve_test/index", "refresh");
    }

}
