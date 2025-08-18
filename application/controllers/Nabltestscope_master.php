<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Nabltestscope_master extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('user_model');
        $this->load->model('Nabltestscope_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');

        $data["login_data"] = logindata();
        //$this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }
	
	public function nabltestscope_list(){
		$srchdata['search'] = $data['search'] = $this->input->get('search');
		$data["login_data"] = logindata();
		$data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
		$srchdata['testscope'] = $data['testscope'] = $this->input->get('testscope');
		$totalRows = $this->Nabltestscope_model->num_row('nabltestscope_master', array('status' => 1));

        $config = array();
        $config["base_url"] = base_url() . "Nabltestscope_master/nabltestscope_list/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config['cur_tag_open'] = '<span>';
        $config['cur_tag_close'] = '</span>';
        $config['next_link'] = 'Next <span class="icon-text">&#59230;</span>';
        $config['prev_link'] = '<span class="icon-text">&#59229;</span> Previous';
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["query"] = $this->Nabltestscope_model->manage_condition_view($srchdata, $config["per_page"], $page);
		//echo "<pre>";print_r($data["query"]);exit;
        $data["links"] = $this->pagination->create_links();
        $cnt = 0;
		
        foreach ($data['query'] as $key) {
			$data['query'][$cnt] = $key;
            $cnt++;
        }
		
		//echo "<pre>";
		//print_r($data); exit;
		$this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('nabltestscope_list', $data);
        $this->load->view('footer');
		
	}
	
	public function nabltestscope_add(){		
		if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
		$data['testcity'] = $this->Nabltestscope_model->master_get_tbl_val("test_cities", array("status" => 1), array("name", "asc"));
		$data['branch'] = $this->Nabltestscope_model->master_get_tbl_val("branch_master", array("status" => 1), array("branch_name", "asc"));
		$data['tests'] = $this->Nabltestscope_model->master_get_tbl_val("test_master", array("status" => 1), array("test_name", "asc"));
		
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('testcity', 'Test City', 'trim|required|xss_clean');
		$this->form_validation->set_rules('branch', 'Branch', 'trim|required|xss_clean');
        $this->form_validation->set_rules('test[]', 'Test', 'trim|required|xss_clean');
		$this->form_validation->set_rules('testscope', 'Test Scope', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() != FALSE) {
			$tests = implode(",",$this->input->post('test'));
			$data = array(
				"testcity_fk" => $this->input->post('testcity'),
				"branch_fk" => $this->input->post('branch'),
				"tests_fk" => $tests,
				"test_scope" => $this->input->post('testscope'),
				"status" => 1,
				"created_date" => date("Y/m/d H:i:s"),
				"created_by" => $data["login_data"]['id']);
			$data['query'] = $this->Nabltestscope_model->master_get_insert("nabltestscope_master", $data);
		
            $this->session->set_flashdata("success", array("Nabl Test Scope Successfull Added."));
            redirect("Nabltestscope_master/nabltestscope_list", "refresh");
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('nabltestscope_add', $data);
            $this->load->view('footer');
        }
	}
	
	public function nabltestscope_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');
        $ids = $data["cid"];		
		$data['testcity'] = $this->Nabltestscope_model->master_get_tbl_val("test_cities", array("status" => 1), array("name", "asc"));
		$data['branch'] = $this->Nabltestscope_model->master_get_tbl_val("branch_master", array("status" => 1), array("branch_name", "asc"));
		$data['tests'] = $this->Nabltestscope_model->master_get_tbl_val("test_master", array("status" => 1), array("test_name", "asc"));
		

        $this->load->library('form_validation');
        $this->form_validation->set_rules('testcity', 'Test City', 'trim|required|xss_clean');
		$this->form_validation->set_rules('branch', 'Branch', 'trim|required|xss_clean');
        $this->form_validation->set_rules('test[]', 'Test', 'trim|required|xss_clean');
		$this->form_validation->set_rules('testscope', 'Test Scope', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() != FALSE) {
			$post['testcity_fk'] = $this->input->post('testcity');
			$post['branch_fk'] = $this->input->post('branch');
			$post['tests_fk'] = implode(",",$this->input->post('test'));
			$post['test_scope'] = $this->input->post('testscope');
            $post['status'] = 1;
            $post['modified_date'] = date("Y/m/d H:i:s");
            $post['modified_by'] = $data["login_data"]['id'];
            $data['query'] = $this->Nabltestscope_model->master_get_update("nabltestscope_master", array('id' => $_POST['id']), $post);
            $cnt = 0;
            $this->session->set_flashdata("success", array("Nabl Test Scope Successfull Updated."));
            redirect("Nabltestscope_master/nabltestscope_list", "refresh");
        } else {
            $data['query'] = $this->Nabltestscope_model->master_get_tbl_val("nabltestscope_master", array("id" => $data["cid"]), array("id", "desc"));			
			$this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('nabltestscope_edit', $data);
            $this->load->view('footer');
        }
    }
	
	public function nabltestscope_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->Nabltestscope_model->master_get_update("nabltestscope_master", array("id" => $cid), array("status" => "0"), array("id", "desc"));
        $this->session->set_flashdata("success", array("Nabl Test Scope Successfull Deleted."));
        redirect("Nabltestscope_master/nabltestscope_list", "refresh");
    }
}