<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pine_lab_terminal_master extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('Pine_lab_terminal_master_model');
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

        $name = $this->input->get('name');
        $imei = $this->input->get('imei');
        $data['cfname'] = $phone;
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        if ($name != "") {
            
            $totalRows = $this->Pine_lab_terminal_master_model->num_row($name);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "Pine_lab_terminal_master/index?" . http_build_query($get);

            $config["total_rows"] = $totalRows;
            $config["per_page"] = 10;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Pine_lab_terminal_master_model->master_get_search($name, $config["per_page"], $page);
            $data['name'] = $name;
            $data['imei'] = $imei;
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        } else {
            $totalRows = $this->Pine_lab_terminal_master_model->num_row($name);
            $config = array();
            $get = $_GET;
            $config["base_url"] = base_url() . "Pine_lab_terminal_master/index";
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
            $data['query'] = $this->Pine_lab_terminal_master_model->master_get_search($name, $config["per_page"], $page);
            $data['imei'] = $imei;
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        }

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('pinelabs_list', $data);
        $this->load->view('footer');
    }

    public function delete() {
        $cid = $this->uri->segment('3');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $post['status'] = '0';
        $data['query'] = $this->Pine_lab_terminal_master_model->master_get_spam('pinelab_terminal_master', $cid, $post);
        $this->session->set_flashdata('success', 'Pine Lab Master data deleted successfully.');
        redirect('Pine_lab_terminal_master');
    }

    public function add() {
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('imei_no', 'IMEI No', 'required');
		$this->form_validation->set_rules('postcode', 'MerchantStorePOS code','required');

        if ($this->input->post()) {
            if ($this->form_validation->run() != FALSE) {

                $data = [
                    "name" => $this->input->post('name'),
                    "imei_no" => $this->input->post('imei_no'),
					"postcode"=>$this->input->post('postcode'),
                    "created_date" => date("Y-m-d H:i:s"),
                    "created_by" => $data["login_data"]["id"],
                    "status" => 1,
                ];

                $data['query'] = $this->Pine_lab_terminal_master_model->insert_data("pinelab_terminal_master", $data);

                $this->session->set_flashdata("success", "Pine Lab Master data is added successfully.");
                redirect("Pine_lab_terminal_master/index", "refresh");
            }
        }

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('pinelabs_add');
        $this->load->view('footer');
    }

    public function edit($id) {
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('imei_no', 'IMEI No', 'required');
		$this->form_validation->set_rules('postcode', 'MerchantStorePOS code','required');

       

        if ($this->input->post()) {
            if ($this->form_validation->run() != FALSE) {

                $data = [
                    "name" => $this->input->post('name'),
                    "imei_no" => $this->input->post('imei_no'),
					"postcode"=>$this->input->post('postcode'),
                    "created_date" => date("Y-m-d H:i:s"),
                    "created_by" => $data["login_data"]["id"],
                    "status" => 1,
                ];

                $data['query'] = $this->Pine_lab_terminal_master_model->update_data("pinelab_terminal_master", $id, $data);
                $this->session->set_flashdata("success", "Pine Lab Master is updated successfully.");
                redirect("Pine_lab_terminal_master/index", "refresh");
            }
        }
		
		 $data["row"] = $this->Pine_lab_terminal_master_model->get_row('pinelab_terminal_master', $id);

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view("pinelabs_edit");
        $this->load->view('footer');
    }

}

?>
