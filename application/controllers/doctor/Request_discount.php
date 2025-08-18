<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Request_discount extends CI_Controller {

   function __construct() {
        parent::__construct();
   
       $logincheck=is_doctorlogin();
        if (!$logincheck){
            redirect('doctor');
        }else{
			
			 $this->load->model('doctor/doctor_model');
			 $docpart=$this->doctor_model->fetchdatarow("app_permission",'doctor_master',array("id"=>$logincheck["id"],"status"=>'1'));
			 $this->data['permission'] =$docpart->app_permission;
		}
		
       // $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function index() {
		
        $data["login_data"] = is_doctorlogin();
		$did=$data["login_data"]["id"];
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username','Patient Name','trim|required|xss_clean');
		$this->form_validation->set_rules('mnumber', 'Mobile Number','trim|required|numeric|min_length[10]|max_length[13]');
		$this->form_validation->set_rules('desc', 'Description', 'trim|required|xss_clean');
		
		if ($this->form_validation->run() != FALSE) {
			
			
			
			$username = $this->input->post("username");
			$mnumber = $this->input->post("mnumber");
			$desc = $this->input->post("desc");
			
			$data_array = array(
                "doctor_fk" => $did,
                "patient_name" => $username,
                "mobile" => $mnumber,
                "desc" => $desc,
                "createddate" => date("Y-m-d H:i:s"),
                "status" => "1"
            );
            $this->doctor_model->master_fun_insert("doctor_req_discount", $data_array);
			
			 $ses ="Successfully sent request for discount";
              $this->session->set_flashdata('success', $ses);
				
			redirect('doctor/request_discount');
			
		}else{
		
		$this->load->view('doctor/d_header');
        $this->load->view('doctor/d_nav', $data);
        $this->load->view('doctor/d_requestdiccount', $data);
        $this->load->view('doctor/d_footer');
		
		}
        
    }
public function lists() {
		
        $data["login_data"] = is_doctorlogin();
		$did=$data["login_data"]["id"];
		
		$data["query"]=$this->doctor_model->get_val("SELECT * FROM `doctor_req_discount` WHERE STATUS='1' and doctor_fk='$did' ");
		
		$this->load->view('doctor/d_header');
        $this->load->view('doctor/d_nav', $data);
        $this->load->view('doctor/d_requestdiscount_list', $data);
        $this->load->view('doctor/d_footer');
		
		
        
    }	

}
