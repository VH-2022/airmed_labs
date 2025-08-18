<?php

class Referringdr_register_report extends CI_Controller {

    function __construct() {
        parent::__construct();
         $this->load->model('refferringdrregister_model');
        $data["login_data"] = logindata();
    }
public function report(){
        if (!is_loggedin()) {
            redirect('login');
        }
$data["login_data"] = logindata();
$start_date=$this->input->get('start_date');
$end_date=$this->input->get('end_date');
$branch=$this->input->get('branch');
$doctor=$this->input->get('doctor');
$credtejobuserall=$this->input->get('credtejobuser');
/* $branch=implode(",",$this->input->get('branch'));
$doctor=implode(",",$this->input->get('doctor')); */
$credtejobuser=implode(",",$this->input->get('credtejobuser'));
$logintype=$data["login_data"]['type'];
if($branch != ""){ $data['getbranch']=$this->refferringdrregister_model->get_val("SELECT id,branch_name FROM branch_master WHERE STATUS='1' AND id IN($branch) ORDER BY branch_name ASC"); }else{ $data['getbranch']=array(); }

if($doctor != ""){ $data['getdoctor']=$this->refferringdrregister_model->get_val("SELECT id,`full_name` FROM doctor_master WHERE STATUS='1' AND id IN($doctor) ORDER BY full_name ASC"); }else{ $data['getdoctor']=array(); }
if($start_date != "" ||  $end_date != "" || $branch != "" || $doctor != "" || $credtejobuser != ""){


		if ($logintype == 5 || $logintype == 6 || $logintype == 7 ) {
			
			 $uid = $this->refferringdrregister_model->get_val("SELECT GROUP_CONCAT(branch_fk) AS bid FROM `user_branch` WHERE `status`='1' AND user_fk='" . $data["login_data"]["id"] . "' GROUP BY user_fk");
			
			if($uid[0]["bid"] != ""){ $branchuser=$uid[0]["bid"]; }else{ $branchuser=0;  }
		}else{
			$branchuser="";
		}
		

 		$data["query"]=$this->refferringdrregister_model->get_jobreport($start_date,$end_date,$branch,$doctor,$credtejobuser,$branchuser);
			$data["links"] = "";
			/* echo "<pre>";print_r($data["query"]); die(); */
	
}else{
$data["perpage"]="0";	
$data["page"]="";	
$data["query"]=array();
$data["links"]="";

}
/* $data["branch_list"]=$this->accountdailybillregister_model->master_fun_get_tbl_val('branch_master','id,branch_name',array("status"=>'1'), array("branch_name","asc")); */
			 
			/*  $data["doctor_list"]=$this->doctor_s_report_model->master_fun_get_tbl_val('doctor_master','id,full_name',array("status"=>'1'), array("full_name","asc")); */
			$data["doctor_list"]=array();
			 
			if ($logintype == 5 || $logintype == 6 || $logintype == 7 ) {
				  
		$data["admin_list"] = $this->refferringdrregister_model->get_val1("SELECT `id`, `name` FROM `admin_master` WHERE `status` = '1' AND `type` != '4' AND id IN(SELECT user_fk FROM `user_branch` WHERE STATUS='1' AND TYPE != '1' AND `branch_fk` IN(SELECT `branch_fk`  FROM `user_branch`  WHERE STATUS = '1' AND user_fk = ".$data["login_data"]["id"].")) ORDER BY `name` ASC"); 
		
		}else{  $data["admin_list"] = $this->refferringdrregister_model->master_fun_get_tbl_val('admin_master', 'id,name', array("status" => '1', "type !=" => '4'), array("name", "asc")); 
		}
	
	
			 
		$this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('referringdrregister_report', $data);
        $this->load->view('footer');
		

}

public function report_exportcsv(){
        if (!is_loggedin()) {
            redirect('login');
        }
$data["login_data"] = logindata();
$start_date=$this->input->get('start_date');
$end_date=$this->input->get('end_date');
$branch=$this->input->get('branch');
$doctor=$this->input->get('doctor');
$credtejobuserall=$this->input->get('credtejobuser');
/* $branch=implode(",",$this->input->get('branch'));
$doctor=implode(",",$this->input->get('doctor')); */
$credtejobuser=implode(",",$this->input->get('credtejobuser'));

if($start_date != "" ||  $end_date != "" || $branch != "" || $doctor != "" || $credtejobuser != ""){
	
$logintype=$data["login_data"]['type'];
		if ($logintype == 5 || $logintype == 6 || $logintype == 7 ) {
			
			 $uid = $this->refferringdrregister_model->get_val("SELECT GROUP_CONCAT(branch_fk) AS bid FROM `user_branch` WHERE `status`='1' AND user_fk='" . $data["login_data"]["id"] . "' GROUP BY user_fk");
			
			if($uid[0]["bid"] != ""){ $branchuser=$uid[0]["bid"]; }else{ $branchuser=0;  }
		}else{
			$branchuser="";
		}	

 		$query=$this->refferringdrregister_model->get_jobreport($start_date,$end_date,$branch,$doctor,$credtejobuser);
			$data["links"] = "";
			/* echo "<pre>";print_r($data["query"]); die(); */
	
}else{
	
$query=array();

}
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"ReferringDr_register_report.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w'); 
		fputcsv($handle, array("Srl no","Date","Referring Doctor","Lab RefNo","Patient Name","Bill Total","Disc","Paid","Due"));
			 $i=0;
    foreach($query as $row){
		
		 $i++;
		 $total=round($row->price);
		 $disco=round($row->discount);
		 $paid=round($total-$disco-round($row->payable_amount));
		fputcsv($handle,array($i,date("d-m-Y",strtotime($row->date)),ucwords($row->dockname),$row->branch_fk,ucwords($row->full_name),$total,$disco, $paid,round($row->payable_amount))); 
		
	}
	fclose($handle);
    exit;
 
}


}

?>