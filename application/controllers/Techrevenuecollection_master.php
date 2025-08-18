<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Techrevenuecollection_master extends CI_Controller {
	
	public function __construct() {
        parent::__construct();
		$this->load->model('user_model');
        $this->load->model('Techrevenuecollection_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');

        $data["login_data"] = logindata();
        //$this->app_track();
    }
	
	public function techrevenuecollection_list(){
		if (!is_loggedin()) {
            redirect('login');
        }
		$srchdata['search'] = $data['search'] = $this->input->get('search');
		$data["login_data"] = logindata();
		$data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
		$srchdata['collectiontype'] = $data['collectiontype'] = $this->input->get('collectiontype');
		$totalRows = $this->Techrevenuecollection_model->num_row('tech_revenue_collection_master', array('status' => 1));

        $config = array();
        $config["base_url"] = base_url() . "Techrevenuecollection_master/techrevenuecollection_list/";
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
        $data["query"] = $this->Techrevenuecollection_model->manage_condition_view($srchdata, $config["per_page"], $page);
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
        $this->load->view('techrevenuecollection_list', $data);
        $this->load->view('footer');
	}
	
	
	public function techrevenuecollection_add(){		
		if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
		$techbranch = $this->Techrevenuecollection_model->master_get_tbl_val("tech_revenue_collection_master", array("status" => 1), array("id", "asc"));
		$techbranchrestrict = null;
		foreach($techbranch as $value){
			$techbranchrestrict .= $value['branch_fk'].",";
		}
		$techbranchrestrict = trim($techbranchrestrict,",");
		if(is_null($techbranchrestrict)){
			$query = "Select * FROM branch_master WHERE branch_type_fk=6 AND status=1 AND id NOT IN(".$techbranchrestrict.") ORDER BY branch_name ASC";
		}else{
			$query = "Select * FROM branch_master WHERE branch_type_fk=6 AND status=1 ORDER BY branch_name ASC";
		}
		
		$data['techbranch'] = $this->Techrevenuecollection_model->get_val($query);		
		
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('techbranch', 'Airmed Tech Branch', 'trim|required|xss_clean');
		$this->form_validation->set_rules('collectiontype', 'Collection Type', 'trim|required|xss_clean');
		$this->form_validation->set_rules('collectionvalue', 'Collection Value', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() != FALSE) {
			$data = array(				
				"branch_fk" => $this->input->post('techbranch'),				
				"collection_type" => $this->input->post('collectiontype'),
				"collection_value" => $this->input->post('collectionvalue'),
				"status" => 1,
				"created_date" => date("Y/m/d H:i:s"),
				"created_by" => $data["login_data"]['id']);	
			$data['query'] = $this->Techrevenuecollection_model->master_get_insert("tech_revenue_collection_master", $data);
			$data_log['techrevenue_id'] = $data['query'];
			$data_log['branch_fk'] = $data['branch_fk'];
			$data_log['collection_type'] = $data['collection_type'];
			$data_log['collection_value'] = $data['collection_value'];
			$data_log['status'] = $data['status'];
			$data_log['updated_date'] = $data['created_date'];
			$data_log['updated_by'] = $data['created_by'];
			$this->Techrevenuecollection_model->master_get_insert("tech_revenue_collection_master_log", $data_log);
            $this->session->set_flashdata("success", array("Tech Revenue Collection Successfully Added."));
            redirect("Techrevenuecollection_master/techrevenuecollection_list", "refresh");
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('techrevenuecollection_add', $data);
            $this->load->view('footer');
        }
	}
	
	public function techrevenuecollection_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');		
		
		$techbranch = $this->Techrevenuecollection_model->master_get_tbl_val("tech_revenue_collection_master", array("status" => 1), array("id", "asc"));
		$techbranchrestrict = null;
		foreach($techbranch as $value){
			if($value['id']!=$data["cid"]){
				$techbranchrestrict .= $value['branch_fk'].",";
			}
		}
		$techbranchrestrict = trim($techbranchrestrict,",");
		
		if(is_null($techbranchrestrict)){
			$query = "Select * FROM branch_master WHERE branch_type_fk=6 AND status=1 AND id NOT IN(".$techbranchrestrict.") ORDER BY branch_name ASC";
		}else{
			$query = "Select * FROM branch_master WHERE branch_type_fk=6 AND status=1 ORDER BY branch_name ASC";
		}
		//echo $query; exit;
		$data['techbranch'] = $this->Techrevenuecollection_model->get_val($query);		
		
		//$data['techbranch'] = $this->Techrevenuecollection_model->master_get_tbl_val("branch_master", array("branch_type_fk" => 6, "status" => 1), array("branch_name", "asc"));

        $this->load->library('form_validation');        
		$this->form_validation->set_rules('techbranch', 'Airmed Tech Branch', 'trim|required|xss_clean');
		$this->form_validation->set_rules('collectiontype', 'Collection Type', 'trim|required|xss_clean');
		$this->form_validation->set_rules('collectionvalue', 'Collection Value', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() != FALSE) {
			$post['branch_fk'] = $this->input->post('techbranch');
			$post['collection_type'] = $this->input->post('collectiontype');
			$post['collection_value'] = $this->input->post('collectionvalue');
            $post['status'] = 1;
            $post['modified_date'] = date("Y/m/d H:i:s");
            $post['modified_by'] = $data["login_data"]['id'];
            $data['query'] = $this->Techrevenuecollection_model->master_get_update("tech_revenue_collection_master", array('id' => $_POST['id']), $post);
            $cnt = 0;
			$data_log['techrevenue_id'] = $_POST['id'];
			$data_log['branch_fk'] = $post['branch_fk'];
			$data_log['collection_type'] = $post['collection_type'];
			$data_log['collection_value'] = $post['collection_value'];
			$data_log['status'] = $post['status'];
			$data_log['updated_date'] = $post['modified_date'];
			$data_log['updated_by'] = $post['modified_by'];
			$this->Techrevenuecollection_model->master_get_insert("tech_revenue_collection_master_log", $data_log);
            $this->session->set_flashdata("success", array("Tech Revenue Collection Successfully Updated."));
            redirect("Techrevenuecollection_master/techrevenuecollection_list", "refresh");
        } else {
            $data['query'] = $this->Techrevenuecollection_model->master_get_tbl_val("tech_revenue_collection_master", array("id" => $data["cid"]), array("id", "desc"));
			//print_r($data['query']); exit;
			$this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('techrevenuecollection_edit', $data);
            $this->load->view('footer');
        }
    }
	
	public function techrevenuecollection_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
		$post['deleted_date'] = date("Y/m/d H:i:s");
        $post['deleted_by'] = $data["login_data"]['id'];
		$post['status'] = '0';
		$data['query'] = $this->Techrevenuecollection_model->master_get_update("tech_revenue_collection_master_log", array("techrevenue_id" => $cid), $post, array("id", "desc"));
        $data['query'] = $this->Techrevenuecollection_model->master_get_update("tech_revenue_collection_master", array("id" => $cid), array("status" => "0"), array("id", "desc"));
        $this->session->set_flashdata("success", array("Tech Revenue Collection Successfully Deleted."));
        redirect("Techrevenuecollection_master/techrevenuecollection_list", "refresh");
    }
	
	public function techrevenuecollection_viewlogs(){
		if (!is_loggedin()) {
            redirect('login');
        }

		//$techbranch = $this->Techrevenuecollection_model->master_get_tbl_val("tech_revenue_collection_master_log", array("techrevenue_id" => $data["cid"], "status" => 1), array("id", "asc"));
		$srchdata['search'] = $data['search'] = $this->input->get('search');
		$data["login_data"] = logindata();
		$data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
		$data["techrevenue_id"] = $srchdata["techrevenue_id"] = $this->uri->segment('3');
        $data['success'] = $this->session->flashdata("success");
		$srchdata['collectiontype'] = $data['collectiontype'] = $this->input->get('collectiontype');
		$totalRows = $this->Techrevenuecollection_model->num_row('tech_revenue_collection_master_log', array("techrevenue_id" => $data["techrevenue_id"], 'status' => 1));

        $config = array();
        $config["base_url"] = base_url() . "Techrevenuecollection_master/techrevenuecollection_viewlogs/";
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
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		//echo $page; exit;
        $data["query"] = $this->Techrevenuecollection_model->manage_condition_viewlogs($srchdata, $config["per_page"], $page);
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
		$this->load->view('techrevenuecollection_viewlogs', $data);
		$this->load->view('footer');
	}
	
	public function techrevenuecollection_addlog(){		
		if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
		$data["techrevenue_id"] = $this->uri->segment('3');
		$techbranch = $this->Techrevenuecollection_model->master_get_tbl_val("tech_revenue_collection_master_log", array("techrevenue_id" => $data["techrevenue_id"], "status" => 1), array("id", "asc"));
		$techbranchrestrict = null;
		foreach($techbranch as $value){
			$techbranchrestrict .= $value['branch_fk'].",";
		}
		$techbranchrestrict = trim($techbranchrestrict,",");
		//echo $techbranchrestrict; exit;
		$query = "Select * FROM branch_master WHERE branch_type_fk=6 AND status=1 AND id IN(".$techbranchrestrict.") ORDER BY branch_name ASC";
		$data['techbranch'] = $this->Techrevenuecollection_model->get_val($query);		
		
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('techbranch', 'Airmed Tech Branch', 'trim|required|xss_clean');
		$this->form_validation->set_rules('collectionlog_date', 'Collection Log Date & Time', 'trim|required|xss_clean');
		$this->form_validation->set_rules('collectiontype', 'Collection Type', 'trim|required|xss_clean');
		$this->form_validation->set_rules('collectionvalue', 'Collection Value', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() != FALSE) {
			$data = array(	
				"techrevenue_id" => $data["techrevenue_id"],
				"branch_fk" => $this->input->post('techbranch'),				
				"collection_type" => $this->input->post('collectiontype'),
				"collection_value" => $this->input->post('collectionvalue'),
				"status" => 1,
				"updated_date" => date("Y/m/d H:i:s", strtotime($this->input->post('collectionlog_date'))),
				"updated_by" => $data["login_data"]['id']);	
			$data['query'] = $this->Techrevenuecollection_model->master_get_insert("tech_revenue_collection_master_log", $data);
            $this->session->set_flashdata("success", array("Tech Revenue Collection Log Successfully Added."));
            redirect("Techrevenuecollection_master/techrevenuecollection_viewlogs", "refresh");
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('techrevenuecollection_addlog', $data);
            $this->load->view('footer');
        }
	}
	
	public function techrevenuecollection_deletelog() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
		$techrevenue_id = $this->uri->segment('3');
        $cid = $this->uri->segment('4');
		$post['deleted_date'] = date("Y/m/d H:i:s");
        $post['deleted_by'] = $data["login_data"]['id'];
		$post['status'] = '0';
        $data['query'] = $this->Techrevenuecollection_model->master_get_update("tech_revenue_collection_master_log", array('id' => $cid), $post);   
        
        $this->session->set_flashdata("success", array("Tech Revenue Collection Log Successfully Deleted."));
        redirect("Techrevenuecollection_master/techrevenuecollection_viewlogs/".$techrevenue_id, "refresh");
    }
}