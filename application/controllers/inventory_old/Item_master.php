<?php

class Item_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('inventory/Item_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');

        $data["login_data"] = logindata();
    }

    function item_list() { 
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $reg_id = $this->input->get('search');
        
          $box_price = $this->input->get('box_price');
           $hsn_code = $this->input->get('hsn_code');

        if ($reg_id != '' || $box_price !='' ||$hsn_code !='') {
            $srchdata = array("item_name" => $reg_id,'price'=>$box_price,'hsn_code'=>$hsn_code);
            $data['item_name'] = $reg_id;
             
                $data['box_price'] = $box_price;
                  $data['hsn_code'] = $hsn_code;
            $totalRows = $this->Item_model->getitem_num($srchdata);

            $config = array();
            /* Vishal Code Start */
            $config["base_url"] = base_url() . "inventory/Item_master/item_list?search=$reg_id&machine_name=$machine_name&box_price=$box_price&hsn_code=$hsn_code";
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
            $data["query"] = $this->Item_model->getitem($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {
            $srchdata = array();
            $totalRows = $this->Item_model->getitem_num($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "inventory/Item_master/item_list/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 100;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            $data["query"] = $this->Item_model->getitem($srchdata, $config["per_page"], $page);
            
            /* echo "<pre>";print_r($data["query"]); die(); */
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        $this->load->view('inventory/header');
        $this->load->view('inventory/nav', $data);
        $this->load->view('inventory/item_list', $data);
        $this->load->view('inventory/footer');
    }

    public function item_add() {
//      
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $this->load->library('form_validation');
        $this->form_validation->set_rules('item_name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        if ($this->form_validation->run() != FALSE) {
            $data = array(
                'reagent_name' => $this->input->post('item_name'),
                "machine" => $this->input->post('machine'),
                "remark" => $this->input->post('remark'),
                "created_date" => date("Y-m-d H:i:s"),
                "created_by" => $data["login_data"]['id'],
                "category_fk"=>3
            );

            $data['query'] = $this->Item_model->master_get_insert("inventory_item", $data);

            $this->session->set_flashdata("success", array("Invetory Item  Successfull Added."));
            redirect("inventory/Item_master/item_list", "refresh");
        } else {
            $this->load->view('inventory/header');
            $this->load->view('inventory/nav', $data);
            $this->load->view('inventory/item_add', $data);
            $this->load->view('inventory/footer');
        }
    }

    public function item_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["eid"] = $this->uri->segment('4');
        $ids = $data["eid"];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('item_name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        if ($this->form_validation->run() != FALSE) {
            $post['reagent_name'] = $this->input->post('item_name');
            $post['reagent_name'] = $this->input->post('item_name');
            $post['status'] = 1;
            $post['category_fk'] = 3;
            $post['modified_by'] = $data["login_data"]['id'];
            $post["machine"] = $this->input->post('machine');
            $post["remark"] = $this->input->post('remark');


            $data['query'] = $this->Item_model->master_get_update("inventory_item", array('id' => $this->input->post('id')), $post);
            $this->session->set_flashdata("success", array("Inventory Successfull Updated."));
            redirect("inventory/Item_master/item_list", "refresh");
        } else {
            $data['query'] = $this->Item_model->master_get_tbl_val("inventory_item", array("id" => $data["eid"]), array("id", "desc"));

            $this->load->view('inventory/header');
            $this->load->view('inventory/nav', $data);
            $this->load->view('inventory/item_edit', $data);
            $this->load->view('inventory/footer');
        }
    }

    public function item_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $eid = $this->uri->segment('4');

        $data['query'] = $this->Item_model->master_get_update("inventory_item", array("id" => $eid), array("status" => "0"), array("id", "desc"));

        $this->session->set_flashdata("success", array("Item  Successfull Deleted."));
        redirect("inventory/Item_master/item_list", "refresh");
    }

}

?>