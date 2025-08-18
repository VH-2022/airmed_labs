<?php

class Stationary_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('inventory/Stationary_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');

        $data["login_data"] = logindata();
    }

    function stationary_list() { 
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
         $reg_id = $this->input->get('search');
           $unit_name = $this->input->get('unit_name');
              $brand_name = $this->input->get('brand_name');

         if ($reg_id != '' || $unit_name !='' || $brand_name !='') {
            $srchdata = array("name" => $reg_id,"unit_name"=>$unit_name,"brand_name"=>$brand_name);
            $data['item_name'] = $reg_id;
            $data['unit_name'] = $unit_name;
            $data['brand_name'] = $brand_name;
            $totalRows = $this->Stationary_model->getitem_num($srchdata);
            $config = array();
            /* Vishal Code Start */
            $config["base_url"] = base_url() . "inventory/Stationary_master/stationary_list?search=$reg_id";
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
            $data["query"] = $this->Stationary_model->getitem($srchdata, $config["per_page"], $page);

            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {

            $srchdata = array();
            $totalRows = $this->Stationary_model->getitem_num($srchdata);

            $config = array();
            $config["base_url"] = base_url() . "inventory/Stationary_master/stationary_list/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 100;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            $data["query"] = $this->Stationary_model->getitem($srchdata, $config["per_page"], $page);


            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        $data['unit_list'] = $this->Stationary_model->master_get_tbl_val('inventory_unit_master',array('status'=>'1'),array('name',"DESC"));
           $data['brand_list'] = $this->Stationary_model->master_get_tbl_val('inventory_brand',array('status'=>'1'),array('brand_name',"DESC"));
        $this->load->view('inventory/header');
        $this->load->view('inventory/nav', $data);
        $this->load->view('inventory/stationary_list', $data);
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
        $this->form_validation->set_rules('quantity', 'quantity', 'trim|numeric|xss_clean|callback_check_minus');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        if ($this->form_validation->run() != FALSE) {
            $data = array(
                'reagent_name' => $this->input->post('name'),
                "quantity" => $this->input->post('quantity'),
                "created_date" => date("Y-m-d H:i:s"),
                "created_by" => $data["login_data"]['id'],
                "category_fk" => "1",
                 "unit_fk"=>$this->input->post('unit_fk'),
                   "brand_fk"=>$this->input->post('brand_fk'),
                   "remark"=>$this->input->post('remark'),
                   'box_price'=>$this->input->post('box_price'),
                    'hsn_code'=>$this->input->post('hsn_code')
            );
         
            $data['query'] = $this->Stationary_model->master_get_insert("inventory_item", $data);
            $this->session->set_flashdata("success", array("Inventory Item  Successfull Added."));
            redirect("inventory/Stationary_master/stationary_list", "refresh");
        } else {
             $data['unit_list'] = $this->Stationary_model->master_get_tbl_val('inventory_unit_master',array('status'=>'1'),array('name',"DESC"));
           $data['brand_list'] = $this->Stationary_model->master_get_tbl_val('inventory_brand',array('status'=>'1'),array('brand_name',"DESC"));
            $this->load->view('inventory/header');
            $this->load->view('inventory/nav', $data);
            $this->load->view('inventory/stationary_add', $data);
            $this->load->view('inventory/footer');
        }
    }

    public function stationary_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["eid"] = $this->uri->segment('4');
        $ids = $data["eid"];

        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('quantity', 'quantity', 'trim|numeric|xss_clean|callback_check_minus');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        if ($this->form_validation->run() != FALSE) {
            $post['reagent_name'] = $this->input->post('name');
            $post["quantity"] = $this->input->post('quantity');
            $post['status'] = 1;
            $post['modified_by'] = $data["login_data"]['id'];
            $post["category_fk"] = 1;
             $post["unit_fk"] = $this->input->post('unit_fk');
             $post["brand_fk"] = $this->input->post('brand_fk');
              $post["remark"] = $this->input->post('remark');
              $post["box_price"] = $this->input->post('box_price');
              $post['hsn_code'] = $this->input->post('hsn_code');
            $data['query'] = $this->Stationary_model->master_get_update("inventory_item", array('id' => $ids), $post);
            $this->session->set_flashdata("success", array("Inventory Successfull Updated."));
            redirect("inventory/Stationary_master/stationary_list", "refresh");
        } else {
            $data['query'] = $this->Stationary_model->master_get_tbl_val("inventory_item", array("id" => $data["eid"]), array("id", "desc"));
             $data['unit_list'] = $this->Stationary_model->master_get_tbl_val('inventory_unit_master',array('status'=>'1'),array('name',"DESC"));
           $data['brand_list'] = $this->Stationary_model->master_get_tbl_val('inventory_brand',array('status'=>'1'),array('brand_name',"DESC"));
            $this->load->view('inventory/header');
            $this->load->view('inventory/nav', $data);
            $this->load->view('inventory/stationary_edit', $data);
            $this->load->view('inventory/footer');
        }
    }

    public function stationary_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $eid = $this->uri->segment('4');

        $data['query'] = $this->Stationary_model->master_get_update("inventory_item", array("id" => $eid), array("status" => "0"), array("id", "desc"));

        $this->session->set_flashdata("success", array("Inventory Item  Successfully Deleted."));
        redirect("inventory/Stationary_master/stationary_list", "refresh");
    }
public function check_minus() {
        $test_discount = $this->input->post('quantity');

        if ($test_discount < 0) {
             $this->form_validation->set_message('check_minus', "Nagative Digits Not allowed");

    return false;
        } else {
            return TRUE;
        }
    }
}

?>