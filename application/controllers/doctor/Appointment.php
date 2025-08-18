<?php

class Appointment extends CI_Controller {

    function __construct() {
        parent::__construct();
         
		$logincheck=is_doctorlogin();
        if (!$logincheck){
            redirect('doctor');
        }else{
			
			 $this->load->model('doctor_timeslotmodel');
			 $docpart=$this->doctor_timeslotmodel->fetchdatarow("app_permission",'doctor_master',array("id"=>$logincheck["id"],"status"=>'1'));
			  if($docpart->app_permission != '1'){  redirect('doctor/dashboard'); }
			 $this->data['permission'] =$docpart->app_permission;
		}
       // $this->app_track();
		
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }
	
public function index(){
        
		
		$data["login_data"] = is_doctorlogin();
		$did=$data["login_data"]["id"];
		
		$start_date=$this->input->get('startdate');
		$end_date=$this->input->get('end_date');
		
		$city="";
		$doctor=$did;
		if($start_date != "" && $end_date != ""){
			
		$this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Patient name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|max_length[13]');
		$this->form_validation->set_rules('paage', 'Patient Age', 'trim|required|xss_clean|numeric');
		$this->form_validation->set_rules('pagender', 'Patient Gender', 'trim|required|xss_clean');
		$this->form_validation->set_rules('apdate', 'Appointment Date', 'trim|required|xss_clean');
		$this->form_validation->set_rules('aptime', 'Appointment Time', 'trim|required|xss_clean|callback_checkbookslot');
		$this->form_validation->set_rules('type','Appointment','trim|required|xss_clean|numeric');
        
        
		if($this->form_validation->run() != FALSE) {
			
			$p_name=$this->input->post('name');
			$p_mobile=$this->input->post('mobile');
			$p_age=$this->input->post('paage');
			$p_gender=$this->input->post('pagender');
			$p_appoin=$this->input->post('apdate');
			$p_dslot=$this->input->post('aptime');
			$type=$this->input->post('type');
			
			$getdoctslot = $this->doctor_timeslotmodel->get_val("SELECT `start_time`,end_time FROM doctor_timeslot WHERE id='$p_dslot' and status='1'"); 

			if($getdoctslot[0]->start_time != ""){ 
			
			$dstarttime=date("Y-m-d",strtotime($p_appoin))." ".$getdoctslot[0]->start_time;
			$dendtime=date("Y-m-d",strtotime($p_appoin))." ".$getdoctslot[0]->end_time; 

			$data=array("p_name"=>$p_name,"p_age"=>$p_age,"p_mobile"=>$p_mobile,"p_gender"=>$p_gender,"doctorfk" =>$did,"dslotfk"=>$p_dslot,"starttime"=>$dstarttime,"endtime"=>$dendtime,"receptionfk"=>$rid,"type"=>$type,"creteddate"=>date("Y-m-d H:i:s"));
			if($type == 2){ $data["checkin"]="2"; }
			
			$this->doctor_timeslotmodel->master_fun_insert("doctorbook_slot",$data);
			$this->session->set_flashdata('success', "Data Successfully Added");
			
			}
			
			redirect("doctor/appointment?startdate=".date("d-m-Y")."&end_date=".date("d-m-Y")."");
			
			
		}else{
			
		$apptype=$this->input->get('apptype');
		$checkin=$this->input->get('checkin');
			
		$this->load->library('pagination');
			
		 $total_row = $this->doctor_timeslotmodel->getappoiment($start_date,$end_date,$city,$doctor,0,0,$apptype,$checkin);
            $config = array();
            $config["base_url"] = base_url() . "doctor/appointmenttest_list?startdate=$start_date&end_date=$end_date";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
			
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->doctor_timeslotmodel->getappoiment($start_date,$end_date,$city,$doctor, $config["per_page"], $page,$apptype,$checkin);
			
			
			$data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
		
		$this->load->view('doctor/d_header');
        $this->load->view('doctor/d_nav', $data);
        $this->load->view('doctor/d_appoiment_views', $data);
        $this->load->view('doctor/d_footer');
		
		}
		
		}else{ show_404(); }
			
}
public function checkbookslot(){
	
	
	$data["login_data"] = is_doctorlogin();
	$did=$data["login_data"]["id"];
		
		
		$slotdate=$this->input->post("apdate");
		$slotfk=$this->input->post("aptime");
	
	$doctortoalslot=$this->doctor_timeslotmodel->get_val("SELECT slotbook FROM `doctor_master` WHERE id='$did' AND status='1'"); 
	    $dslot=$doctortoalslot[0]->slotbook; 
			
			$dobootimeslot=$this->doctor_timeslotmodel->get_val("SELECT count(id) as btslotd FROM doctorbook_slot WHERE status='1' and dslotfk='$slotfk' AND doctorfk='$did' AND DATE_FORMAT(starttime,'%d-%m-%Y')='$slotdate'");
			
			$totabookslot=$dobootimeslot[0]->btslotd;
			if($dslot > $totabookslot){		
			return true;
			}else{
				
			$this->form_validation->set_message('checkbookslot', 'Appointment Already booked!');
            return false;
			
			}
	
}
public function exportcsv(){
	
     $data["login_data"] = is_doctorlogin();
	 $did=$data["login_data"]["id"];

$start_date=$this->input->get('startdate');
		$end_date=$this->input->get('end_date');
		$apptype=$this->input->get('apptype');
		$checkin=$this->input->get('checkin');
		
		$doctor=$did;
		if($start_date != "" && $end_date != ""){
		
            $query = $this->doctor_timeslotmodel->getappoiment($start_date,$end_date,$city,$doctor,10000000,0,$apptype,$checkin);
			
		header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"appointmentlist.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("Srl no", "Patient Name", "Patient mobile no", "Appointment Date", "Booking Time","Appointment","Check in","Status"));
		
        $i = 0;
			foreach($query as $row){ $i++;
			if($row->type=='2'){ $app="Schedule"; }else{ $app="Walk in"; }
			if($row->checkin=='1'){ $chekapp="In"; }else{ $chekapp="Not in"; }
			
			if($row->status=='1'){ $chestatus="Active"; }else{ $chestatus="Canceled"; }
			
            fputcsv($handle, array($i,ucwords($row->p_name),$row->p_mobile,date("d-m-Y",strtotime($row->starttime)),date("h:i a",strtotime($row->starttime))."-".date("h:i a",strtotime($row->endtime)),$app,$chekapp,$chestatus));
			}
			fclose($handle);
        exit;
		}else{ show_404(); }

}
public function getdoctorslot(){
	
   $data["login_data"] = is_doctorlogin();
	$did=$data["login_data"]["id"];
		
		 $adate=$this->input->get('adate');
		
	    $this->load->library('curl');
		$baseurl=base_url()."service/phlebo_service_v9/doctorslot?date=$adate&doctor=$did";
		
		echo $json=$this->curl->simple_get($baseurl);
		
}
public function app_delete($id){
	
    $data["login_data"] = is_doctorlogin();
		$did=$data["login_data"]["id"];
		
		$doctor=$did;
	$getdoctorbook=$this->doctor_timeslotmodel->get_val("SELECT b.id,b.p_name,b.p_mobile,b.p_age,d.full_name,b.starttime FROM `doctorbook_slot` b inner join doctor_master d on d.id=b.doctorfk WHERE b.status='1' and b.id='$id' and b.doctorfk='$did' ");
		
		if($id != "" && $getdoctorbook[0] != null){
		   
		  $this->doctor_timeslotmodel->updateRowWhere('doctorbook_slot',array("id"=>$id,"status"=>'1',"doctorfk"=>$did),array("status"=>'2'));
		   $this->session->set_flashdata('success', "Data Successfully Canceled");
		   
		      $getsms=$this->doctor_timeslotmodel->fetchdatarow("sms","doctor_sms",array("id"=>'2',"status"=>'1'));
			  
	if($getsms != null){
		
		$bookdate=date("d-m-Y h:i a(l)",strtotime($getdoctorbook[0]->starttime));
		
		$messagecon=$getsms->sms;
		$msg=preg_replace("/{{pname}}/",$getdoctorbook[0]->p_name,$messagecon);
		$msg=preg_replace("/{{dname}}/",$getdoctorbook[0]->full_name,$msg);
		
		$msg=preg_replace("/{{date}}/",$bookdate,$msg);
		
		$this->doctor_timeslotmodel->master_fun_insert("admin_alert_sms", array("mobile_no" => $getdoctorbook[0]->p_mobile,"message" => $msg, "created_date" => date("Y-m-d H:i:s")));
		
	} 
		   
		   redirect("doctor/appointment?startdate=".date("d-m-Y")."&end_date=".date("d-m-Y")."");
		   
		}else{ show_404(); }
		
		
}
public function check_in($id){
	
    $data["login_data"] = is_doctorlogin();
		$did=$data["login_data"]["id"];
		
		
		$doctor=$did;
		if($id != ""){
		   
		   $this->doctor_timeslotmodel->updateRowWhere('doctorbook_slot',array("id"=>$id,"checkin"=>'2',"status"=>'1',"doctorfk"=>$did),array("checkin"=>'1'));
		   $this->session->set_flashdata('success', "Appointment Successfully check in");
		   redirect("doctor/appointment?startdate=".date("d-m-Y")."&end_date=".date("d-m-Y")."");
		   
		}else{ show_404(); }

}

}
?>