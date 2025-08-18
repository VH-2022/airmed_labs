<?php

class Unit_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('inventory/user_model');
        $this->load->model('inventory/unit_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');

        $data["login_data"] = logindata();
    }

    function unit_list() { 
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $reg_id = $this->input->get('search');

        if ($reg_id != '') {
            $srchdata = array("name" => $reg_id);
            $data['item_name'] = $reg_id;
            $totalRows = $this->unit_model->getitem_num($srchdata);
            $config = array();
            /* Vishal Code Start */
            $config["base_url"] = base_url() . "inventory/Unit_master/unit_list?search=$reg_id";
            $config["total_rows"] = $totalRows;
            /* Vishal Code End */
            $config["per_page"] = 100;
            $config["uri_segment"] = 4;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data["query"] = $this->unit_model->getitem($srchdata, $config["per_page"], $page);
       
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else { 
            
            $srchdata = array();
            $totalRows = $this->unit_model->getitem_num($srchdata);
            
            $config = array();
            
            $config["base_url"] = base_url() . "inventory/Unit_master/unit_list/";
            
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            $data["query"] = $this->unit_model->getitem($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        $this->load->view('inventory/header');
        $this->load->view('inventory/nav', $data);
        $this->load->view('inventory/unit_list', $data);
        $this->load->view('inventory/footer');
    }

    public function add() {
//      
    
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('quantity', 'quantity', 'trim|numeric|xss_clean');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        if ($this->form_validation->run() != FALSE) {
            $data = array(
                'name' => $this->input->post('name'),
                "created_date" => date("Y-m-d H:i:s"),
                "created_by" => $data["login_data"]['id']
            );

            $data['query'] = $this->unit_model->master_get_insert("inventory_unit_master", $data);
            
            $this->session->set_flashdata("success", array("Unit Item Successfull Added."));
            redirect("inventory/Unit_master/unit_list", "refresh");
        } else {
            $this->load->view('inventory/header');
            $this->load->view('inventory/nav', $data);
            $this->load->view('inventory/unit_add', $data);
            $this->load->view('inventory/footer');
        }
    }

    public function unit_edit() { 
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["eid"] = $this->uri->segment('4');
        $ids = $data["eid"];
   
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        if ($this->form_validation->run() != FALSE) {
           $post['name'] = $this->input->post('name');
            $post['status'] = 1;
            $post['modified_date']= date("Y-m-d H:i:s");
            $post['modified_by'] = $data["login_data"]['id'];
       
            $data['query'] = $this->unit_model->master_get_update("inventory_unit_master", array('id' =>  $ids), $post);
            $this->session->set_flashdata("success", array("Unit Item Successfull Updated."));
            redirect("inventory/Unit_master/unit_list", "refresh");
        } else {
            $data['query'] = $this->unit_model->master_get_tbl_val("inventory_unit_master", array("id" => $data["eid"]), array("id", "desc"));
            $this->load->view('inventory/header');
            $this->load->view('inventory/nav', $data);
            $this->load->view('inventory/unit_edit', $data);
            $this->load->view('inventory/footer');
        }
    }

    public function unit_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $eid = $this->uri->segment('4');

        $data['query'] = $this->unit_model->master_get_update("inventory_unit_master", array("id" => $eid), array("status" => "0"), array("id", "desc"));

       $this->session->set_flashdata("success", array("Unit Item Successfully Deleted."));
        redirect("inventory/Unit_master/unit_list", "refresh");
    }

}

?>