<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tax_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('inventory/Tax_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
    }
 
    function index() { 
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $id = $this->input->get_post('title');
        $tax = $this->input->get_post('tax');
        $city_fk = $this->input->get_post('city_fk');

        if ($id != '' || $tax != ''|| $city_fk !='') {

            $totalRows = $this->Tax_model->intent_num($id, $tax,$city_fk);

            $data['title'] = $id;
            $data['tax'] = $tax;
          $data['city_fk'] = $city_fk;
       
            $config = array();
            $config["base_url"] = base_url() . "Tax_master/index?title=$id&tax=$tax&city=$city_fk";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';

            $this->pagination->initialize($config);
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            $data["query"] = $this->Tax_model->intent_list($id, $tax,$city_fk, $config["per_page"], $page, $data["login_data"]["id"]);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;      
        } else {

            $srch = array();

             $totalRows = $this->Tax_model->intent_num($id, $tax,$city_fk);
           
            $config = array();
            $config["base_url"] = base_url() . "Tax_master/index/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';

            $this->pagination->initialize($config);
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
 
            $data["query"] = $this->Tax_model->intent_list($id, $tax,$city_fk, $config["per_page"], $page);
                   $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
           
        }
           $data['city_list'] = $this->Tax_model->get_val("select * from test_cities where status='1' and name !='' and name IS NOT NULL");
        if($type =='8'){
             $this->load->view('inventory/header', $data);
        $this->load->view('inventory/nav', $data);
        $this->load->view('inventory/tax_list', $data);
        $this->load->view('inventory/footer');
    }else{
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('inventory/tax_list', $data);
        $this->load->view('footer');
    }

   }
    function add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        
        $data["login_data"] = logindata();
        $id = $data["login_data"]["id"];
        $type = $data["login_data"]['type'];
        $this->form_validation->set_rules('title','Title','trim|required');
         $this->form_validation->set_rules('tax','Tax','trim|required');
          $this->form_validation->set_rules('city_fk','City','trim|required');
        if($this->form_validation->run() != false){
            $data = array(
                    'title'=>$this->input->post('title'),
                'tax'=>$this->input->post('tax'),
                'city_fk'=>$this->input->post('city_fk'),
                'remark'=>$this->input->post('remark'),
                'created_date'=>date('Y-m-d h:i:s'),
                'created_by'=>$id
                
            );
            $this->Tax_model->master_fun_insert("inventory_tax_master", $data);
            
        $this->session->set_flashdata("success", array("Tax master successfully added."));
        redirect("inventory/Tax_master/index/", "refresh");
        }
       $data['city_list'] = $this->Tax_model->get_val("select * from test_cities where status='1' and name !='' and name IS NOT NULL");
       
         if($type =='8'){
             $this->load->view('inventory/header', $data);
        $this->load->view('inventory/nav', $data);
        $this->load->view('inventory/tax_add', $data);
        $this->load->view('inventory/footer');
    }else{ $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('inventory/tax_add', $data);
        $this->load->view('footer');
    }
    }

    function edit() {
            if (!is_loggedin()) {
            redirect('login');
        }
        
        $data["login_data"] = logindata();
        $id = $data["login_data"]["id"];
        $tid = $this->uri->segment('4');
      $type = $data["login_data"]['type'];
        $this->form_validation->set_rules('title','Title','trim|required');
         $this->form_validation->set_rules('tax','Tax','trim|required');
          $this->form_validation->set_rules('city_fk','City','trim|required');
        if($this->form_validation->run() != false){
            $data = array(
                    'title'=>$this->input->post('title'),
                'tax'=>$this->input->post('tax'),
                'city_fk'=>$this->input->post('city_fk'),
                'remark'=>$this->input->post('remark'),
                'modified_date'=>date('Y-m-d h:i:s'),
                'modified_by'=>$id
                
            );
            $this->Tax_model->master_fun_update("inventory_tax_master",$tid, $data);
            
        $this->session->set_flashdata("success", array("Tax master successfully updated."));
        redirect("inventory/Tax_master/index/", "refresh");
        }
        $data['query'] = $this->Tax_model->get_val('select * from inventory_tax_master where id ="'.$tid.'" and status="1"');
       
       $data['city_list'] = $this->Tax_model->get_val("select * from test_cities where status='1' and name !='' and name IS NOT NULL");


       if($type =='8'){
             $this->load->view('inventory/header', $data);
        $this->load->view('inventory/nav', $data);
        $this->load->view('inventory/tax_edit', $data);
        $this->load->view('inventory/footer');
    }else{ $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('inventory/tax_edit', $data);
        $this->load->view('footer');
    }
        
    }

   
    function delete() {
        if (!is_loggedin()) { 
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $tid = $this->uri->segment('4');

        $data['query'] = $this->Tax_model->new_fun_update("inventory_tax_master", array("id" => $tid), array("status" => "0"));

        $this->session->set_flashdata("success", array("Tax master successfull deleted."));
        redirect("inventory/Tax_master/index", "refresh");
    }

   
}
