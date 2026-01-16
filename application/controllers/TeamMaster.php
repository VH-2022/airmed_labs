<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class TeamMaster extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Team_master_model');
        $this->load->model('user_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('email');
        $this->load->helper('url');
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
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $userid = $data["login_data"]["id"];
        if ($this->session->userdata('success') != null) {
            $data['success'] = $this->session->userdata("success");
            $this->session->unset_userdata('success');
        }
        if ($this->session->userdata('unsuccess') != null) {
            $data['unsuccess'] = $this->session->userdata("unsuccess");
            $this->session->unset_userdata('unsuccess');
        }
        $data["query"] = $this->Team_master_model->get_active_record();
        $totalRows = count($data["query"]);
        $config = array();
        $config["base_url"] = base_url() . "TeamMaster/index/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["query"] = $this->Team_master_model->get_active_record1($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('team_list', $data);
        $this->load->view('footer');
    }

    public function add() {

        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        if ($this->session->userdata('success') != null) {
            $data['success'] = $this->session->userdata("success");
            $this->session->unset_userdata('success');
        }

        if ($this->session->userdata('unsuccess') != null) {
            $data['unsuccess'] = $this->session->userdata("unsuccess");
            $this->session->unset_userdata('unsuccess');
        }

        $this->load->library('form_validation');

        // File validation (IMPORTANT FIX)
        if (empty($_FILES['sliderfile']['name'])) {
            $this->form_validation->set_rules('sliderfile', 'Image', 'required');
        }

        $this->form_validation->set_rules('title', 'Name', 'trim|required');
        $this->form_validation->set_rules('designation', 'Designation', 'trim|required');
        $this->form_validation->set_rules('desc', 'Description', 'trim|required');

        if ($this->form_validation->run() == FALSE) {

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('team_add');
            $this->load->view('footer');
            return;
        }

        // Upload config
        $config['upload_path']   = './upload/team/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name']     = time() . '_' . $_FILES["sliderfile"]["name"];
        $config['max_size']      = 2048;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload("sliderfile")) {

            $data['error'] = array('error' => $this->upload->display_errors());

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('team_add', $data);
            $this->load->view('footer');
            return;
        }

        $uploadData = $this->upload->data();
        $file_name = $uploadData['file_name'];

        $time = $this->Team_master_model->get_server_time();

        $insertData = array(
            "title" => $this->input->post('title'),
            "image" => $file_name,
            "designation" => $this->input->post('designation'),
            "desc" => $this->input->post('desc'),
            "created_date" => $time['UTC_TIMESTAMP()']
        );

        $this->Team_master_model->master_fun_insert('team_master', $insertData);

        $this->session->set_userdata('success', array("Team Member Successfully Added"));
        redirect('TeamMaster/index');
    }

    public function edit() {

        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["id"] = $this->uri->segment(3);

        if ($this->session->userdata('unsuccess') != null) {
            $data['unsuccess'] = $this->session->userdata("unsuccess");
            $this->session->unset_userdata('unsuccess');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('title', 'Name', 'trim|required');
        $this->form_validation->set_rules('designation', 'Designation', 'trim|required');
        $this->form_validation->set_rules('desc', 'Description', 'trim|required');

        if ($this->form_validation->run() == FALSE) {

            $data['query'] = $this->Team_master_model
                ->master_fun_get_tbl_val("team_master", array("id" => $id), array("id", "desc"));

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('team_edit', $data);
            $this->load->view('footer');
            return;
        }

        $title = $this->input->post('title');
        $designation = $this->input->post('designation');
        $desc = $this->input->post('desc');

        // If new image uploaded
        if (!empty($_FILES["sliderfile"]["name"])) {

            $config['upload_path'] = './upload/team/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_name'] = time() . '_' . $_FILES["sliderfile"]["name"];
            $config['max_size'] = 2048;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload("sliderfile")) {

                $data['error'] = array('error' => $this->upload->display_errors());
                $data['query'] = $this->Team_master_model
                    ->master_fun_get_tbl_val("team_master", array("id" => $id), array("id", "desc"));

                $this->load->view('header');
                $this->load->view('nav', $data);
                $this->load->view('team_edit', $data);
                $this->load->view('footer');
                return;
            }

            $uploadData = $this->upload->data();
            $file_name = $uploadData['file_name'];

            $updateData = array(
                "title" => $title,
                "designation" => $designation,
                "desc" => $desc,
                "image" => $file_name
            );

        } else {

            // Without image update
            $updateData = array(
                "title" => $title,
                "designation" => $designation,
                "desc" => $desc
            );
        }

        $this->Team_master_model->master_fun_update("team_master", $id, $updateData);

        $this->session->set_userdata('success', array("Team Successfully Updated"));
        redirect('TeamMaster/index');
    }

    function delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $userid = $data["login_data"]["id"];
        $cid = $this->uri->segment('3');
        $data = array(
            "status" => '0'
        );
        $delete = $this->Team_master_model->master_fun_update("team_master", $this->uri->segment('3'), $data);
        if ($delete) {
            $ses = array("Team Successfully Deleted");
            $this->session->set_userdata('success', $ses);
            redirect('TeamMaster/index', 'refresh');
        }
    }

}

?>
