<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Updateprofile extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('reception/reception_model');
		
         $logincheck=is_receptionlogin();
        if (!$logincheck){
            redirect('reception');
        }
        
    }

 public function index() {
		
        $data["login_data"] = is_receptionlogin();
		$rid=$data["login_data"]["id"];
		$did=$data["login_data"]["docid"];
		$this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|max_length[13]|callback_checkmobile');
		
		if($this->form_validation->run() != FALSE) {
			
			 $username = $this->input->post("username");
             $mobile = $this->input->post("mobile");
			 
            $data1 = array(
                "name" => ucwords($username),
                "mobile" =>$mobile 
            );
			
			
			   
			 $update=$this->reception_model->master_fun_update("doctor_reception",array("id"=>$rid,"status"=>'1'),$data1);
			   
			   
			   $sess_array = array(
					'id'=>$rid,
                    'name' => ucwords($username),
                    'email' =>$mobile,'docid'=>$did
                );
                $this->session->set_userdata('reception_logged_in',$sess_array);
			   
			    $ses ="Successfully Updated Profile";
                $this->session->set_flashdata('success', $ses);
				
				redirect('reception/updateprofile');
            
			
		}else{
			
	$data["user"]=$this->reception_model->fetchdatarow("id,name,mobile","doctor_reception",array("id"=>$rid,"status"=>'1'));
			
        $this->load->view('reception/r_header');
        $this->load->view('reception/r_nav', $data);
        $this->load->view('reception/edit_reception', $data);
        $this->load->view('reception/r_footer');
		
		 }
    
	}
	 function checkmobile($mobile) {
		 
		 $data["login_data"] = is_receptionlogin();
		$did=$data["login_data"]["id"];
		 
        $result = $this->reception_model->master_num_rows("doctor_reception",array("mobile"=>$mobile,"status"=>'1',"id != "=>$did));
        if ($result >= 1) {
            $this->form_validation->set_message('checkmobile', 'Mobile Number Already Exists!');
            return false;
        } else {
            return TRUE;
        }
    }

}
