<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('reception/reception_model');
		
       $logincheck=is_receptionlogin();
        if (!$logincheck){
            redirect('doctor');
        }
    }

    
   public function index() {
        
        $data["login_data"] = is_receptionlogin();
		$did=$data["login_data"]["id"];
		/* $this->load->library('curl');
		$baseurl=base_url();
		$json=$this->curl->simple_get("$baseurl/doctor_api/dashboard_1?user_id=$did");
		$array = json_decode( $json, true );
		
		$data["todata"]=$array["data"][0];
         */
		
        $this->load->view('reception/r_header');
        $this->load->view('reception/r_nav', $data);
        $this->load->view('reception/r_dashboard', $data);
        $this->load->view('reception/r_footer');
    
	}

  

}
