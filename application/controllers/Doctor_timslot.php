<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Doctor_timslot extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('doctor_timeslotmodel');

        $data["login_data"] = logindata();
    }
  public function index($id) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();

	if($id != ""){
		
		$data["doctotinfo"]=$this->doctor_timeslotmodel->fetchdatarow("id,full_name,slotbook","doctor_master",array("id"=>$id,"status"=>'1'));
	if($data["doctotinfo"] != ""){
		
		  $getdoctptslot=$this->doctor_timeslotmodel->fetchdatarow('count(id) as total','doctor_timeslot',array("doctor_fk"=>$id,"status"=>'1'));
		$totalslot=$getdoctptslot->total; 
		
		if($totalslot==0){ 
		$doctor=$id;
		for($s=0;$s<7;$s++){
		
	$start_time=strtotime('00:00:00');
    $end_time = strtotime('23:45:00');
    $slot=strtotime(date('H:i:s',$start_time) . ' +15 minutes');
	
    for($i=0; $slot <= $end_time; $i++) {
	
		$timeslotid=$this->doctor_timeslotmodel->master_fun_insert("doctor_timeslot",array("doctor_fk"=>$doctor,"weekend"=>$s,"start_time"=>date('H:i:s', $start_time),"end_time"=>date('H:i:s', $slot),"createddate"=>date("Y-m-d H:i:s"),"creted_by"=>$data["login_data"]["id"]));
		
		if($start_time > strtotime("24:00:99") && $start_time < strtotime("09:59:59") || $start_time > strtotime("13:59:59") && $start_time < strtotime("16:59:55") || $start_time > strtotime("20:59:59") && $start_time < strtotime("23:59:00")){
			
			$this->doctor_timeslotmodel->master_fun_insert("doctor_deleteslot",array("doctorid"=>$doctor,"slotid"=>$timeslotid,"cretedby"=>$data["login_data"]["id"],"cretedate"=>date("Y-m-d H:i:s")));
         
		 
		 }
		
		if(date('H:i:s', $slot)=="23:45:00"){ 

		$timeslotid1=$this->doctor_timeslotmodel->master_fun_insert("doctor_timeslot",array("doctor_fk"=>$doctor,"weekend"=>$s,"start_time"=>date('H:i:s',$slot),"end_time"=>date('H:i:s',strtotime(date('H:i:s',$slot) . ' +15 minutes')),"createddate"=>date("Y-m-d H:i:s"),"creted_by"=>$data["login_data"]["id"]));
		
		$start_time1=date('H:i:s',$slot);
		
		if($start_time1 > strtotime("24:00:99") && $start_time1 < strtotime("09:59:59") || $start_time1 > strtotime("13:59:59") && $start_time1 < strtotime("16:59:55") || $start_time1 > strtotime("20:59:59") && $start_time1 < strtotime("23:59:00")){
			
			$this->doctor_timeslotmodel->master_fun_insert("doctor_deleteslot",array("doctorid"=>$doctor,"slotid"=>$timeslotid1,"cretedby"=>$data["login_data"]["id"],"cretedate"=>date("Y-m-d H:i:s")));
         
		 
		 }
	
	  }

        $start_time = $slot;
        $slot = strtotime(date('H:i:s',$start_time) . ' +15 minutes');
		
    }
	
	}
	
	$docbookslot=$this->doctor_timeslotmodel->get_val("select s.dslotfk,t.start_time from doctorbook_slot s left join doctor_timeslot t on t.id=s.dslotfk where s.doctorfk='$doctor' and DATE_FORMAT(s.starttime,'%Y-%m-%d') >= '".date("Y-m-d")."' and s.status='1' ");
	foreach($docbookslot as $bookslot){
		
		$getnewdslot1=$this->doctor_timeslotmodel->fetchdatarow("id","doctor_timeslot",array("doctor_fk"=>$doctor,"status"=>'1',"TIME(start_time)"=>$bookslot->start_time));
	if($getnewdslot1 != null){
	   
		$this->doctor_timeslotmodel->updateRowWhere("doctorbook_slot",array("doctorfk"=>$doctor,"dslotfk"=>$bookslot->dslotfk,"status"=>'1'),array("dslotfk"=>$getnewdslot1->id,"oldslotfk"=>$bookslot->dslotfk,"updated_by"=>$data["login_data"]["id"],"updateddate"=>date("Y-m-d H:i:s")));
	 	
	}
	
	}
	
	     $checkdock=$id; 
		
		 $data['slotlist'] = $this->doctor_timeslotmodel->get_val("SELECT t.start_time, t.end_time, t.id,t.doctor_fk,t.weekend,COUNT(d.id) AS deltid FROM `doctor_timeslot` t LEFT JOIN doctor_deleteslot d ON d.slotid=t.`id` AND d.`status`='1' AND doctorid='$id' WHERE t.status = '1' and t.doctor_fk='$checkdock' GROUP BY t.id ORDER BY t.start_time,t.weekend ASC"); 

		}else{ 
		
		$checkdock=$id; 
		
		 $data['slotlist'] = $this->doctor_timeslotmodel->get_val("SELECT t.start_time, t.end_time, t.id,t.doctor_fk,t.weekend,COUNT(d.id) AS deltid FROM `doctor_timeslot` t LEFT JOIN doctor_deleteslot d ON d.slotid=t.`id` AND d.`status`='1' AND doctorid='$id' WHERE t.status = '1' and t.doctor_fk='$checkdock' GROUP BY t.id ORDER BY t.start_time,t.weekend ASC"); 
		 
		 }
		 
		
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('doctot_slotlist', $data);
        $this->load->view('footer');
		
		}else{ show_404(); }
		
		}else{ show_404(); }
    }
	public function slotupdate() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
		
		$this->load->library('form_validation');
        $this->form_validation->set_rules('dslot[]','Doctor slot','trim');
		$this->form_validation->set_rules('doctorfk','Doctor','trim');
		$this->form_validation->set_rules('stotalbook','stotalbook','trim|required');

        if ($this->form_validation->run() != FALSE) {
			
		$dslot=$this->input->post("dslot");
		$doctor=$this->input->post("doctorfk");
		$stotalbook=$this->input->post("stotalbook");
		
		/* $getdoctptslot=$this->doctor_timeslotmodel->fetchdatarow('count(id) as total','doctor_timeslot',array("doctor_fk"=>$doctor,"status"=>'1'));
		$totalslot=$getdoctptslot->total; */
		
			/* for($s=0;$s<7;$s++){
		
	$start_time=strtotime('00:00:00');
    $end_time = strtotime('23:45:00');
    $slot=strtotime(date('H:i:s',$start_time) . ' +15 minutes');
	
    for($i=0; $slot <= $end_time; $i++) { 
		$this->doctor_timeslotmodel->master_fun_insert("doctor_timeslot",array("doctor_fk"=>$doctor,"weekend"=>$s,"start_time"=>date('H:i:s', $start_time),"end_time"=>date('H:i:s', $slot),"createddate"=>date("Y-m-d H:i:s"),"creted_by"=>$data["login_data"]["id"]));
		
		if(date('H:i:s', $slot)=="23:45:00"){ 

		$this->doctor_timeslotmodel->master_fun_insert("doctor_timeslot",array("doctor_fk"=>$doctor,"weekend"=>$s,"start_time"=>date('H:i:s',$slot),"end_time"=>date('H:i:s',strtotime(date('H:i:s',$slot) . ' +15 minutes')),"createddate"=>date("Y-m-d H:i:s"),"creted_by"=>$data["login_data"]["id"]));
	
	  }

        $start_time = $slot;
        $slot = strtotime(date('H:i:s',$start_time) . ' +15 minutes');
		
    }
	
	} */
	
	$this->doctor_timeslotmodel->updateRowWhere("doctor_master",array("id"=>$doctor,"status"=>'1'),array("slotbook"=>$stotalbook));
	
		$this->doctor_timeslotmodel->updateRowWhere("doctor_deleteslot",array("doctorid"=>$doctor,"status"=>'1'),array("status"=>'0',"updatedby"=>$data["login_data"]["id"],"updateddate"=>date("Y-m-d H:i:s")));
		
	foreach($dslot as $slotval){
		$this->doctor_timeslotmodel->master_fun_insert("doctor_deleteslot",array("doctorid"=>$doctor,"slotid"=>$slotval,"cretedby"=>$data["login_data"]["id"],"cretedate"=>date("Y-m-d H:i:s")));
		}
		
		
			echo "1";
		}else{ echo "0"; }
    }

}

?>