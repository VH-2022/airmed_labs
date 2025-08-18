<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends CI_Controller {

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
		$this->load->library('curl');
		$baseurl=base_url();
		$json=$this->curl->simple_get("$baseurl/doctor_api/dashboard_1?user_id=$did");
		$array = json_decode( $json, true );
		
		$data["todata"]=$array["data"][0];
        $data["todaygraph"]=$array["data"][0]["graph"];
		
		$json1=$this->curl->simple_get("$baseurl/doctor_api/dashboard_2?user_id=$did");
		$array1=json_decode( $json1, true );
		$data["last2day"] =$array1["data"][0]["graph"];
		$data["last2data"]=$array1["data"][0];
		
		
		$json2=$this->curl->simple_get("$baseurl/doctor_api/doctor_year_month_count?user_id=$did");
		$array2=json_decode( $json2, true );
		$data["monthreport"] =$array2["data"];
		
		
        $this->load->view('doctor/d_header');
        $this->load->view('doctor/d_nav', $data);
        $this->load->view('doctor/d_dashboard', $data);
        $this->load->view('doctor/d_footer');
    
	}

  

}
