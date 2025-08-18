<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Brand_master extends CI_Controller {

    function __construct() {
        parent::__construct();
       
        $this->load->model('user_model');
        $this->load->model('inventory/brand_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $data["login_data"] = logindata();
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
       $type= $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $name = $this->input->get('search');
     
        if ($name != "" ) {
            $srchdata = array("name" => $name);
            $data['name'] = $name;
            $totalRows = $this->brand_model->brandcount_list($srchdata);

            $config["base_url"] = base_url() . "inventory/brand_master/index?search=$name";
            $config["total_rows"] = $totalRows;
            $config["per_page"] =50;
            $config["uri_segment"] = 4;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data["query"] = $this->brand_model->brandlist_list($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
              $data["counts"] = $page;
        } else {
            $srchdata = array();
            $totalRows = $this->brand_model->brandcount_list($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "inventory/brand_master/index";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
           $config["uri_segment"] = 4;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data["query"] = $this->brand_model->brandlist_list($srchdata, $config["per_page"], $page);

            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        
            $data['city'] = $this->brand_model->get_val("SELECT * FROM test_cities WHERE status='1' AND name IS NOT NULL AND name !=''");
        
            if($type == '8'){
                $this->load->view('inventory/header', $data);
        $this->load->view('inventory/nav', $data);
         $this->load->view('inventory/brand_list', $data);
        $this->load->view('inventory/footer');
    }else{
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
         $this->load->view('inventory/brand_list', $data);
        $this->load->view('footer');
    }
        
       
       
    }

     function add() { 
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $id =$data["login_data"]['id'];
        $type= $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
         
        $this->load->library('form_validation');
        $this->form_validation->set_rules('brand_name', 'Brand Name', 'trim|required');
      
      
       
        if ($this->form_validation->run() != FALSE) {
            $brand_name = $this->input->post('brand_name');
          
            
         $data['query'] = $this->brand_model->master_fun_insert("inventory_brand", array("brand_name" => $brand_name,"status"=>'1',"created_date"=>date("Y-m-d h:i:s"),"created_by"=>$id));

            $this->session->set_flashdata("success", array("Brand successfully added."));
            redirect("inventory/brand_master/index", "refresh");
        } else {
           if( $type =='8'){
             $this->load->view('inventory/header',$data);
            $this->load->view('inventory/nav', $data);
            $this->load->view('inventory/brand_add', $data);
            $this->load->view('inventory/footer');
        }else{
            $this->load->view('header',$data);
            $this->load->view('nav', $data);
            $this->load->view('inventory/brand_add', $data);
            $this->load->view('footer'); 
           
        }
        }
    }

    function delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('4');
        $data['query'] = $this->user_model->master_fun_update("inventory_brand", array("id", $cid), array("status" => "0"));
        $this->session->set_flashdata("success", array("Brand successfully deleted."));
        redirect("inventory/brand_master/index", "refresh");
    }

   
    

    function edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $id = $data["login_data"]["id"];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('4');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('brand_name', 'Brand Name', 'trim|required');
     
       
        if ($this->form_validation->run() != FALSE) {
              $brand_name = $this->input->post('brand_name');
           

            $data['query'] = $this->brand_model->master_fun_update("inventory_brand", array("id", $data["cid"]), array("brand_name"=>$brand_name,"status"=>1,"modified_date"=>date("Y-m-d h:i:s"),'modified_by'=>$id));
            
            $this->session->set_flashdata("success", array("Brand successfully updated."));
            redirect("inventory/brand_master/index", "refresh");
        } else {
            $data['query'] = $this->brand_model->master_fun_get_tbl_val("inventory_brand", array("id" => $data["cid"]), array("id", "desc"));
          
          if($type =='8'){
              $this->load->view('inventory/header');
            $this->load->view('inventory/nav', $data);
            $this->load->view('inventory/brand_edit', $data);
            $this->load->view('inventory/footer');
        }else{
              $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('inventory/brand_edit', $data);
            $this->load->view('footer');
          
        }
        }
    }
 
    
    

function export_csv(){

        $name =$this->input->get('search');
     
    $result = $this->brand_model->csv_report($name);

         header("Content-type: application/csv");
         header("Content-Disposition: attachment; filename=\"Brand_Report .csv\"");
       header("Pragma: no-cache");
         header("Expires: 0");
         $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No", "Brand Name"));
        $cnt =1;
        foreach ($result as $val) {
            
             fputcsv($handle, array($cnt++,$val["brand_name"]));
       }
         fclose($handle);
      exit;   
}

}

?>