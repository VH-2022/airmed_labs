<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Updateprofile extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('doctor/doctor_model');
		
         $logincheck=is_doctorlogin();
        if (!$logincheck){
            redirect('doctor');
        }else{
			
			 $this->load->model('doctor_timeslotmodel');
			 $docpart=$this->doctor_timeslotmodel->fetchdatarow("app_permission",'doctor_master',array("id"=>$logincheck["id"],"status"=>'1'));
			 $this->data['permission'] =$docpart->app_permission;
		}
        //$this->app_track();
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
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email|callback_checkemail');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
        /* $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean'); */
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|max_length[13]|callback_checkmobile');
		$this->form_validation->set_rules('address', 'Address', 'trim|xss_clean');
		
		if ($this->form_validation->run() != FALSE) {
			
			
			 $username = $this->input->post("username");
            $email = $this->input->post("email");
            /* $password = $this->input->post("password"); */
			 $mobile = $this->input->post("mobile");
			  $address = $this->input->post("address");
            $data1 = array(
                "full_name" => ucwords($username),
                "mobile" =>$mobile ,
                "email" => $email,"address"=>$address
            );
			/* if($password != ""){  $data1["password"]=$password; } */
			
			   
			   $update = $this->doctor_model->master_fun_update("doctor_master",array("id"=>$did,"status"=>'1'),$data1);
			   $sess_array = array(
					'id'=>$did,
                    'name' => ucwords($username),
                    'email' =>$email
                );
                $this->session->set_userdata('doctor_logged_in',$sess_array);
			   
			    $ses ="Successfully Updated Profile";
                $this->session->set_flashdata('success', $ses);
				
				
				redirect('doctor/updateprofile');
            
			
		}else{
			
	$data["user"]=$this->doctor_model->fetchdatarow("id,full_name,email,password,mobile,address","doctor_master",array("id"=>$did,"status"=>'1'));
			
        $this->load->view('doctor/d_header');
        $this->load->view('doctor/d_nav', $data);
        $this->load->view('doctor/edit_doctor', $data);
        $this->load->view('doctor/d_footer');
		
		 }
    
	}
	 function checkmobile($mobile) {
		 
		 $data["login_data"] = is_doctorlogin();
		$did=$data["login_data"]["id"];
		 
        $result = $this->doctor_model->master_num_rows("doctor_master",array("mobile"=>$mobile,"status"=>'1',"id != "=>$did));
        if ($result >= 1) {
            $this->form_validation->set_message('checkmobile', 'Mobile Number Already Exists!');
            return false;
        } else {
            return TRUE;
        }
    }

  function checkemail($email){
	  
	  $data["login_data"] = is_doctorlogin();
		$did=$data["login_data"]["id"];
		 
        $result = $this->doctor_model->master_num_rows("doctor_master",array("email"=>$email,"status"=>'1',"id != "=>$did));
        if ($result >= 1) {
            $this->form_validation->set_message('checkemail', 'Email Address Already Exists!');
            return false;
        } else {
            return TRUE;
        }
	  
  }

}
