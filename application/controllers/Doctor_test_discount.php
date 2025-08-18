<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Doctor_test_discount extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('doctor_model');
        $this->load->model('doctor_test_discount_model');
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

    function doctor_test_discount_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
         $branch_name = $this->input->get('branch_name');
         $full_name = $this->input->get('full_name');
        $test_name = $this->input->get('test_name');
        if ($branch_name != "" || $full_name != "" || $test_name != "") {
            $srchdata = array("branch_name" => $branch_name, "full_name" => $full_name, "test_name" => $test_name);
            $data['branch_name'] = $branch_name;
            $data['full_name'] = $full_name;
            $data['test_name'] = $test_name;
            $totalRows = $this->doctor_test_discount_model->doctorcount_list($srchdata);
            $config = array();
          // $config["base_url"] = base_url() . "doctor_test_discount/doctor_test_discount_list/";
		   $config["base_url"] = base_url() . "doctor_test_discount/doctor_test_discount_list?" . http_build_query($srchdata);

		
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
			 $config['page_query_string'] = TRUE;
		
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
         //  $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		      $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
			
            $data["page"] = $page;
            $data["query"] = $this->doctor_test_discount_model->doctorlist_list($srchdata, $config["per_page"], $page);
            
          $data["links"] = $this->pagination->create_links();
		   
        } else {
            $srchdata = array();
            $totalRows = $this->doctor_test_discount_model->doctorcount_list($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "doctor_test_discount/doctor_test_discount_list/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["page"] = $page;
            $data["query"] = $this->doctor_test_discount_model->doctorlist_list($srchdata, $config["per_page"], $page);
            
            $data["links"] = $this->pagination->create_links();
        }
         $data['branch_list'] = $this->doctor_model->master_fun_get_tbl_val("branch_master", array("status" => 1), array("branch_name", "asc"));
        $data['doctor_list'] = $this->doctor_model->master_fun_get_tbl_val("doctor_master", array("status" => 1), array("full_name", "asc"));
        $data['test_list'] = $this->doctor_model->master_fun_get_tbl_val("test_master", array("status" => 1), array("test_name", "asc"));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('doctor_test_discount_list', $data);
        $this->load->view('footer');
    }

     function doctor_test_discount_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['branch_list'] = $this->doctor_test_discount_model->master_fun_get_tbl_val("branch_master", array("status" => 1), array("branch_name", "asc"));
        $data['doctor_list'] = $this->doctor_test_discount_model->master_fun_get_tbl_val("doctor_master", array("status" => 1), array("full_name", "asc"));
        $data['test_list'] = $this->doctor_test_discount_model->master_fun_get_tbl_val("test_master", array("status" => 1), array("test_name", "asc"));
      
        $this->load->library('form_validation');
        $this->form_validation->set_rules('lab_fk', 'Lab', 'trim|required');
        //$this->form_validation->set_rules('password', 'Password', 'trim|required');
        //$this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('doc_fk', 'Doctor', 'trim|required');
        $this->form_validation->set_rules('test_fk', 'Test', 'trim|required|callback_check_test');
        $this->form_validation->set_rules('price', 'Price', 'trim|required|numeric');
      
        if ($this->form_validation->run() != FALSE) {
            $lab_fk = $this->input->post('lab_fk');
            $doc_fk = $this->input->post('doc_fk');
            $test_fk = $this->input->post('test_fk');
            $price = $this->input->post('price');
       
            $data['query'] = $this->doctor_test_discount_model->master_fun_insert("lab_doc_discount", array("lab_fk"=>$lab_fk,"doc_fk" =>$doc_fk,"test_fk" => $test_fk, "price" => $price, "status" => '1', "createddate" => date("Y-m-d H:i:s")));
            $this->session->set_flashdata("success", array("Doctor Test Discount  successfully added."));
            redirect("doctor_test_discount/doctor_test_discount_list", "refresh");
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('doctor_test_discount_add', $data);
            $this->load->view('footer');
        }
    }
    function maximumCheck($num)
{
    if ($num > 100000)
    {
        $this->form_validation->set_message(
                        'price',
                        'The %s field must be less than 100000'
                    );
        return FALSE;
    }
    else
    {
        return TRUE;
    }
}
    function check_lab($lab_fk)
    {
        //Field validation succeeded.  Validate against database
        $lab_fk = $this->input->post('lab_fk');
        
        $result = $this->doctor_model->check_lab($lab_fk);
        //query the database

        if (!$result) {
            return TRUE;
        } else {
            $this->form_validation->set_message('lab_fk', 'Invalid Lab.');
            return false;
        }
    }
    function check_doctor($doc_fk)
    {
        //Field validation succeeded.  Validate against database
        $doc_fk = $this->input->post('doc_fk');
        
        $result = $this->doctor_model->check_doctor($doc_fk);
        //query the database

        if (!$result) {
            return TRUE;
        } else {
            $this->form_validation->set_message('doc_fk', 'Invalid Doctor.');
            return false;
        }
    }
 function check_test($lab_fk,$doc_fk,$test_fk)
    {
        //Field validation succeeded.  Validate against database
     $lab_fk = $this->input->post('lab_fk');
     $doc_fk = $this->input->post('doc_fk');
    $test_fk = $this->input->post('test_fk');
 
        
      $result = $this->doctor_test_discount_model->check_test($lab_fk,$doc_fk,$test_fk);

      if (!empty($result)) {
            $this->form_validation->set_message('check_test', 'This Test Name is already used.');
            return FALSE;
        } else {
            return TRUE;
        }
    

     /*   if (!$result == 0) {
            return TRUE;
        } else {
            $this->form_validation->set_message('test_fk', 'Invalid Test.');
            return false;
        }*/
    }
    function doctor_test_discount_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->doctor_test_discount_model->master_fun_update("lab_doc_discount", array("id", $cid), array("status" => "0"));
        $this->session->set_flashdata("success", array("Doctor Test Discount successfully deleted."));
        redirect("doctor_test_discount/doctor_test_discount_list", "refresh");
    }

    

    function doctor_test_discount_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('lab_fk', 'Lab', 'trim|required');
        $this->form_validation->set_rules('doc_fk', 'Doctor', 'trim|required');
        $this->form_validation->set_rules('test_fk', 'Test', 'trim|required|callback_check_test');
        $this->form_validation->set_rules('price', 'Price', 'trim|required|numeric');
        if ($this->form_validation->run() != FALSE) {
            $lab_fk = $this->input->post('lab_fk');
            $doc_fk = $this->input->post('doc_fk');
            $test_fk = $this->input->post('test_fk');
            $price = $this->input->post('price');
            $data['query'] = $this->doctor_test_discount_model->master_fun_update("lab_doc_discount", array("id", $data["cid"]), array("lab_fk"=>$lab_fk,"doc_fk" =>$doc_fk,"test_fk" => $test_fk, "price" => $price));
            $this->session->set_flashdata("success", array("Doctor Test Discount successfully updated."));
            redirect("doctor_test_discount/doctor_test_discount_list", "refresh");
        } else {
            $data['query'] = $this->doctor_test_discount_model->master_fun_get_tbl_val("lab_doc_discount", array("id" => $data["cid"]), array("id", "desc"));
       
            
             $data['branch_list'] = $this->doctor_test_discount_model->master_fun_get_tbl_val("branch_master", array("status" => 1), array("branch_name", "asc"));
             $data['doctor_list'] = $this->doctor_test_discount_model->master_fun_get_tbl_val("doctor_master", array("status" => 1), array("full_name", "asc"));
             $data['test_list'] = $this->doctor_test_discount_model->master_fun_get_tbl_val("test_master", array("status" => 1), array("test_name", "asc"));
      
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('doctor_test_discount_edit', $data);
            $this->load->view('footer');
        }
    }
//    function city_state_list() {
//   $cid = $this->input->post('cid');
//    $selected = $this->input->post('selected');
//        $data['city'] = $this->doctor_test_discount_model->master_fun_get_tbl_val("branch_master", array("id" => $cid), array("branch_name", "asc"));
//        $city = $data['city'][0]['city'];
//        $data['city_fk'] = $this->doctor_test_discount_model->master_fun_get_tbl_val("test_cities", array("id" => $city), array("name", "asc"));
//        $city_fk = $data['city_fk'][0]['city_fk'];
//        $data = $this->doctor_test_discount_model->get_val("Select * from doctor_master where city='$city_fk' and full_name !='' and status='1' order by full_name");
//      // $data = $this->doctor_model->master_fun_get_tbl_val("doctor_master", array("city" => $city_fk), array("full_name", "asc"));
//      // echo $db = $this->db->last_query();
//        echo '<option value="">--Select--</option>';
//        foreach ($data as $all) {
//            $slct='';
//            if($selected==$all['id']){
//                $slct="selected";
//            }
//            echo "<option value='".$all['id'].  set_select('doc_fk',$all['id']) ."' ".$slct.">".$all['full_name']."</option>";
//        }
//    }
    
    
        function city_state_list() {
   $cid = $this->input->post('cid');
    $selected = $this->input->post('selected');
        $data['city'] = $this->doctor_test_discount_model->master_fun_get_tbl_val("branch_master", array("id" => $cid), array("branch_name", "asc"));
        
        $city = $data['city'][0]['city'];
        $data['city_fk'] = $this->doctor_test_discount_model->master_fun_get_tbl_val("test_cities", array("id" => $city), array("name", "asc"));
        
        $city_fk = $data['city_fk'][0]['city_fk'];
        $doctor = $this->doctor_test_discount_model->get_val("Select * from doctor_master where city='$city_fk' and full_name !='' and status='1' order by full_name ASC");
//        echo $this->db->last_query();
//exit;
        //echo "<pre>"; print_r($doctor); exit;
      // $data = $this->doctor_model->master_fun_get_tbl_val("doctor_master", array("city" => $city_fk), array("full_name", "asc"));
      // echo $db = $this->db->last_query();
        echo '<option value="">--Select--</option>';
        foreach ($doctor as $all) {
            $slct='';
            if($selected==$all->id){
                $slct="selected";
            }
            echo "<option value='".$all->id.  set_select('doc_fk',$all->id) ."' ".$slct.">".$all->full_name. " (".$all->mobile.")  </option>";
        }
    }
    
  


}

?>