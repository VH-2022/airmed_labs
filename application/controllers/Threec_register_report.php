<?php

class Threec_register_report extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('threec_registermodel');
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
/* $branch=implode(",",$this->input->get('branch')); */
$credtejobuser=implode(",",$this->input->get('credtejobuser'));

if($branch != ""){ $data['getbranch']=$this->threec_registermodel->get_val("SELECT id,branch_name FROM branch_master WHERE STATUS='1' AND id IN($branch) ORDER BY branch_name ASC"); }else{ $data['getbranch']=array(); }

if($doctor != ""){ $data['getdoctor']=$this->threec_registermodel->get_val("SELECT id,`full_name` FROM doctor_master WHERE STATUS='1' AND id IN($doctor) ORDER BY full_name ASC"); }else{ $data['getdoctor']=array(); }

$logintype=$data["login_data"]['type'];
if($start_date != "" ||  $end_date != "" || $branch != "" || $doctor != "" || $credtejobuser != ""){
	
	
	
		
	function getjobtest($jobid){
	 if($jobid != ""){
		 $ci =& get_instance();
		  
		  $job_test_list = $ci->threec_registermodel->get_val("SELECT `job_test_list_master`.*,`test_master`.`test_name` FROM `job_test_list_master` INNER JOIN `test_master` ON `job_test_list_master`.`test_fk`=`test_master`.`id` WHERE `job_test_list_master`.`job_fk`='" .$jobid. "'");
           
            $job_tst_lst = array();
            foreach ($job_test_list as $st_key) {
                $job_sub_test_list = $ci->threec_registermodel->get_val("SELECT `sub_test_master`.test_fk,`sub_test_master`.`sub_test`,test_master.`test_name` FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`sub_test`=`test_master`.`id` WHERE `sub_test_master`.`status`='1' AND `test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $st_key['test_fk'] . "'");
                $st_key["sub_test"] = $job_sub_test_list;
                $job_tst_lst[] = $st_key;
            }
		$package_ids= $ci->threec_registermodel->get_job_booking_package($jobid);	
		return array("job_test_list"=>$job_tst_lst ,"packagename"=>$package_ids);
		
	 }else{ return 0; }
	 
 }
 
		if ($logintype == 5 || $logintype == 6 || $logintype == 7 ) {
			
			 $uid = $this->threec_registermodel->get_val("SELECT GROUP_CONCAT(branch_fk) AS bid FROM `user_branch` WHERE `status`='1' AND user_fk='" . $data["login_data"]["id"] . "' GROUP BY user_fk");
			
			if($uid[0]["bid"] != ""){ $branchuser=$uid[0]["bid"]; }else{ $branchuser=0;  }
		}else{
			$branchuser="";
		}
	
			$data["query"]=$this->threec_registermodel->get_jobreport($start_date,$end_date,$branch,$doctor,$credtejobuser,$branchuser);
			
			
}else{
	
$data["query"]=array();

}
			 
			/*  $data["doctor_list"]=$this->doctor_s_report_model->master_fun_get_tbl_val('doctor_master','id,full_name',array("status"=>'1'), array("full_name","asc")); */
			
			
		if ($logintype == 5 || $logintype == 6 || $logintype == 7 ) {
				  
		$data["admin_list"] = $this->threec_registermodel->get_val1("SELECT `id`, `name` FROM `admin_master` WHERE `status` = '1' AND `type` != '4' AND id IN(SELECT user_fk FROM `user_branch` WHERE STATUS='1' AND TYPE != '1' AND `branch_fk` IN(SELECT `branch_fk`  FROM `user_branch`  WHERE STATUS = '1' AND user_fk = ".$data["login_data"]["id"].")) ORDER BY `name` ASC"); 
		
		}else{  $data["admin_list"] = $this->threec_registermodel->master_fun_get_tbl_val('admin_master', 'id,name', array("status" => '1', "type !=" => '4'), array("name", "asc")); 
		}
			 
		$this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('threec_registereportviews', $data);
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
/* $branch=implode(",",$this->input->get('branch')); */
$credtejobuser=implode(",",$this->input->get('credtejobuser'));

$logintype=$data["login_data"]['type'];
if($start_date != "" ||  $end_date != "" || $branch != "" || $doctor != "" || $credtejobuser != ""){
	
	
	
		
	function getjobtest($jobid){
	 if($jobid != ""){
		 $ci =& get_instance();
		  
		  $job_test_list = $ci->threec_registermodel->get_val("SELECT `test_master`.`test_name` FROM `job_test_list_master` INNER JOIN `test_master` ON `job_test_list_master`.`test_fk`=`test_master`.`id` WHERE `job_test_list_master`.`job_fk`='" .$jobid. "'");
           
            $job_tst_lst = array();
            /* foreach ($job_test_list as $st_key) {
                $job_sub_test_list = $ci->threec_registermodel->get_val("SELECT `sub_test_master`.test_fk,`sub_test_master`.`sub_test`,test_master.`test_name` FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`sub_test`=`test_master`.`id` WHERE `sub_test_master`.`status`='1' AND `test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $st_key['test_fk'] . "'");
                $st_key["sub_test"] = $job_sub_test_list;
                $job_tst_lst[] = $st_key;
            } */
		$package_ids= $ci->threec_registermodel->get_job_booking_package($jobid);	
		return array("job_test_list"=>$job_test_list ,"packagename"=>$package_ids);
		
	 }else{ return 0; }
	 
 }
 
		if ($logintype == 5 || $logintype == 6 || $logintype == 7 ) {
			
			 $uid = $this->threec_registermodel->get_val("SELECT GROUP_CONCAT(branch_fk) AS bid FROM `user_branch` WHERE `status`='1' AND user_fk='" . $data["login_data"]["id"] . "' GROUP BY user_fk");
			
			if($uid[0]["bid"] != ""){ $branchuser=$uid[0]["bid"]; }else{ $branchuser=0;  }
		}else{
			$branchuser="";
		}
	
			$query=$this->threec_registermodel->get_jobreport($start_date,$end_date,$branch,$doctor,$credtejobuser,$branchuser);
			
			
}else{
	
$query=array();

}
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"threec_register_report.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w'); 
		fputcsv($handle, array("Srl no","Lab RefNo","Patient Name","Address/Place","Investigation/Professional Servicesl","Receipt NO","Receipt Amount"));
			 $i=0;
    foreach($query as $row){
 $jobdtest=getjobtest($row->id);
 
										$job_test_list=$jobdtest['job_test_list'];
										$packagename=$jobdtest['packagename'];
										$testlist=array();
										foreach ($job_test_list as $test) { $testlist[]=ucwords($test['test_name']); }
										 foreach ($packagename as $key3) { $testlist[]=ucfirst($key3["name"]); }
		 $i++;
		fputcsv($handle,array($i,$row->branch_fk,ucwords($row->full_name),ucwords($row->address),implode(",",$testlist),$row->id,round($row->price))); 
		
	}
	fclose($handle);
    exit;
 
}


}

?>