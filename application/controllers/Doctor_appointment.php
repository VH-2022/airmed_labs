<?php

class Doctor_appointment extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('doctor_timeslotmodel');
        $data["login_data"]=logindata();
    }
public function index(){
        if (!is_loggedin()) {
            redirect('login');
        }
		$data["login_data"] = logindata();
		$start_date=$this->input->get('startdate');
		$end_date=$this->input->get('end_date');
		$city=$this->input->get('city');
		$doctor=$this->input->get('doctor');
		if($start_date != "" && $end_date != ""){
			
		$this->load->library('pagination');
			
		$data["cityall"]=$this->doctor_timeslotmodel->master_fun_get_tbl_val("test_cities","city_fk as id,name",array("status"=>'1'),array("name","asc")); 
		$total_row = $this->doctor_timeslotmodel->getappoiment($start_date,$end_date,$city,$doctor,0,0);
            $config = array();
            $config["base_url"] = base_url() . "b2b/Test_master/test_list?search=$search&city=$city";
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
            $data['query'] = $this->doctor_timeslotmodel->getappoiment($start_date,$end_date,$city,$doctor, $config["per_page"], $page);
			
			$data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
		
		$this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('doctorappoiment_views', $data);
        $this->load->view('footer');
		
		}else{ show_404(); }
			
}
public function exportcsv(){
        if (!is_loggedin()) {
            redirect('login');
        }
$data["login_data"] = logindata();
$start_date=$this->input->get('startdate');
		$end_date=$this->input->get('end_date');
		$city=$this->input->get('city');
		$doctor=$this->input->get('doctor');
		if($start_date != "" && $end_date != ""){
		
            $query = $this->doctor_timeslotmodel->getappoiment($start_date,$end_date,$city,$doctor,10000000, 0);
			
		header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Doctorappointment .csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("Srl no", "Doctor Name", "Patient Name", "Patient mobile no", "Appointment Date", "Booking Time"));
        $i = 0;
			foreach($query as $row){ $i++;
            fputcsv($handle, array($i,ucwords($row->full_name), ucwords($row->p_name),$row->p_mobile,date("d-m-Y",strtotime($row->starttime)),date("h:i a",strtotime($row->starttime))."-".date("h:i a",strtotime($row->endtime))));
			}
			fclose($handle);
        exit;
		}else{ show_404(); }

}
function getcitydoctor(){
	if (!is_loggedin()) {
            redirect('login');
        }
$start_date=$this->input->get('startdate');
$end_date=$this->input->get('end_date');
$city=$this->input->get('cityval');
$selectdoct=$this->input->get('selectdoct');

if($start_date != "" && $end_date != "" && $city != ""){
	$start_date=date("Y-m-d",strtotime($start_date));
	$end_date=date("Y-m-d",strtotime($end_date));
$getalldoctor=$this->doctor_timeslotmodel->get_val("SELECT id,full_name FROM `doctor_master` WHERE STATUS='1' AND city='$city' and id in(SELECT b.`doctorfk` FROM `doctorbook_slot` `b`   WHERE `b`.`status` = '1' AND DATE_FORMAT(b.starttime, '%Y-%m-%d') >= '$start_date' AND DATE_FORMAT(b.starttime, '%Y-%m-%d') <= '$end_date') order by full_name asc");
}else{
	$getalldoctor=array();
}
echo "<option value=''>Select Doctor</option>";

foreach($getalldoctor as $doct){ ?>
	<option value="<?= $doct->id; ?>" <?php if($selectdoct== $doct->id){ echo "selected"; } ?>><?= ucwords($doct->full_name); ?></option>
	<?php
}

}
function send_book_sms() {
        if (!is_loggedin()) {
            redirect('login');
	}
$bookid=$this->input->get('bookid');
if($bookid != ""){
	
	$getalldoctorbook=$this->doctor_timeslotmodel->get_val("SELECT b.id,b.p_name,b.p_mobile,b.p_age,b.starttime,d.full_name FROM `doctorbook_slot` b inner join doctor_master d on d.id=b.doctorfk WHERE b.status='1' AND b.id='$bookid'");
	if($getalldoctorbook[0] != null){
		$pname=$getalldoctorbook[0]->p_name;
		$pmobile=$getalldoctorbook[0]->p_mobile;
		$dname=$getalldoctorbook[0]->full_name;
		$bookdate=date("d-m-Y h:i a(l)",strtotime($getalldoctorbook[0]->starttime));
		
	$getsms=$this->doctor_timeslotmodel->fetchdatarow("sms","doctor_sms",array("id"=>'1',"status"=>'1'));
	if($getsms != null){
		$messagecon=$getsms->sms;
		$msg=preg_replace("/{{pname}}/",$pname,$messagecon);
		$msg=preg_replace("/{{dname}}/",$dname,$msg);
		$msg=preg_replace("/{{date}}/",$bookdate,$msg);
		
		$this->doctor_timeslotmodel->master_fun_insert("admin_alert_sms", array("mobile_no" => $pmobile,"message" => $msg, "created_date" => date("Y-m-d H:i:s")));
		 echo "1";
	}else{ echo "0"; }
	}
}
}
public function sendsmspatient24(){
	
	$curredate=date("Y-m-d H:i");
	$fromdate=date("Y-m-d H:i", strtotime("-24 hours"));
	$todate=date('Y-m-d H:i',strtotime('+15 minutes',strtotime($fromdate)));
	
	$getalldoctorbook=$this->doctor_timeslotmodel->get_val("SELECT b.id,b.p_name,b.p_mobile,b.p_age,b.starttime,d.full_name FROM `doctorbook_slot` b inner join doctor_master d on d.id=b.doctorfk WHERE b.status='1' AND STR_TO_DATE(starttime,'%Y-%m-%d %H:%i') >= '$fromdate' AND  STR_TO_DATE(starttime,'%Y-%m-%d %H:%i') <= '$todate'");
	foreach($getalldoctorbook as $patidatils){
		
		$pname=$patidatils->p_name;
		$pmobile=$patidatils->p_mobile;
		$dname=$patidatils->full_name;
		$bookdate=date("d-m-Y h:i a(l)",strtotime($patidatils->starttime));
		
	$getsms=$this->doctor_timeslotmodel->fetchdatarow("sms","doctor_sms",array("id"=>'1',"status"=>'1'));
	if($getsms != null){
		$messagecon=$getsms->sms;
		$msg=preg_replace("/{{pname}}/",$pname,$messagecon);
		$msg=preg_replace("/{{dname}}/",$dname,$msg);
		$msg=preg_replace("/{{date}}/",$bookdate,$msg);
		
		$this->doctor_timeslotmodel->master_fun_insert("admin_alert_sms", array("mobile_no" => $pmobile,"message" => $msg, "created_date" => date("Y-m-d H:i:s")));
		
	}
	
}
	
}
public function sendsmspatienttoday(){
	
$time=date('H:i');
	
if($time=="09:00"){
	
	 $pastdate=date('Y-m-d', strtotime('+1 day'));
	 $getalldoctorbook1=$this->doctor_timeslotmodel->get_val("SELECT b.id,b.p_name,b.p_mobile,b.p_age,b.starttime,d.full_name FROM `doctorbook_slot` b inner join doctor_master d on d.id=b.doctorfk WHERE b.status='1' AND STR_TO_DATE(starttime,'%Y-%m-%d') = '$pastdate'");
	 
	foreach($getalldoctorbook1 as $patidatils){
		
		$pname=$patidatils->p_name;
		$pmobile=$patidatils->p_mobile;
		$dname=$patidatils->full_name;
		$bookdate=date("d-m-Y h:i a(l)",strtotime($patidatils->starttime));
		
	$getsms=$this->doctor_timeslotmodel->fetchdatarow("sms","doctor_sms",array("id"=>'1',"status"=>'1'));
	if($getsms != null){
		
		$messagecon=$getsms->sms;
		$msg=preg_replace("/{{pname}}/",$pname,$messagecon);
		$msg=preg_replace("/{{dname}}/",$dname,$msg);
		$msg=preg_replace("/{{date}}/",$bookdate,$msg);
		
		$this->doctor_timeslotmodel->master_fun_insert("admin_alert_sms", array("mobile_no" => $pmobile,"message" => $msg, "created_date" => date("Y-m-d H:i:s")));
		
	}
}

	$curredate=date("Y-m-d");
	$getalldoctorbook=$this->doctor_timeslotmodel->get_val("SELECT b.id,b.p_name,b.p_mobile,b.p_age,b.starttime,d.full_name FROM `doctorbook_slot` b inner join doctor_master d on d.id=b.doctorfk WHERE b.status='1' AND STR_TO_DATE(starttime,'%Y-%m-%d') = '$curredate'");
	
	foreach($getalldoctorbook as $patidatils){
		
		$pname=$patidatils->p_name;
		$pmobile=$patidatils->p_mobile;
		$dname=$patidatils->full_name;
		$bookdate=date("d-m-Y h:i a(l)",strtotime($patidatils->starttime));
		
	$getsms=$this->doctor_timeslotmodel->fetchdatarow("sms","doctor_sms",array("id"=>'1',"status"=>'1'));
	if($getsms != null){
		
		$messagecon=$getsms->sms;
		$msg=preg_replace("/{{pname}}/",$pname,$messagecon);
		$msg=preg_replace("/{{dname}}/",$dname,$msg);
		$msg=preg_replace("/{{date}}/",$bookdate,$msg);
		
		$this->doctor_timeslotmodel->master_fun_insert("admin_alert_sms", array("mobile_no" => $pmobile,"message" => $msg, "created_date" => date("Y-m-d H:i:s")));
		
	}
	
}

	
}	
}
}
?>