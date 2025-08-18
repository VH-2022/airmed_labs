<?php

class Discount_registerreport extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('discountregister_model');
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

if($branch != ""){ $data['getbranch']=$this->discountregister_model->get_val("SELECT id,branch_name FROM branch_master WHERE STATUS='1' AND id IN($branch) ORDER BY branch_name ASC"); }else{ $data['getbranch']=array(); }

if($doctor != ""){ $data['getdoctor']=$this->discountregister_model->get_val("SELECT id,`full_name` FROM doctor_master WHERE STATUS='1' AND id IN($doctor) ORDER BY full_name ASC"); }else{ $data['getdoctor']=array(); }


if($start_date != "" ||  $end_date != "" || $branch != "" || $doctor != "" || $credtejobuser != ""){
	
	function getjobdiscby($jobid){
	 if($jobid != ""){
		 $ci =& get_instance();
		  
		  $jobdiscby = $ci->discountregister_model->get_val("SELECT  GROUP_CONCAT(DISTINCT a.`name`) AS disby FROM job_log l LEFT JOIN `admin_master` a ON a.id=l.`updated_by` WHERE l.status='1' AND l.message_fk='24' AND l.job_fk='$jobid'");
		  return $jobdiscby[0];
           
    }
	}
	/* echo print_r(getjobdiscby('35346')); die(); */
	
/* $this->load->library('pagination');
	 $totalRows = $this->paymentdueregister_model->get_jobreport('0','0',$start_date,$end_date,$branch,$doctor,$credtejobuser); 
	 
	      $config = array();
          $config["base_url"] = base_url() . "dailybill_register_report/report?start_date=$start_date&end_date=$end_date&branch=$branch&doctor=$doctor&credtejobuser=$credtejobuserall";
		  
		  $config["total_rows"] =$totalRows;
	      $config["per_page"] = 1000;
		    
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
			$config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data["page"] = $page;
			 
			$data["perpage"]=$config["per_page"]; */
			
			$logintype=$data["login_data"]['type'];
		if ($logintype == 5 || $logintype == 6 || $logintype == 7 ) {
			
			 $uid = $this->discountregister_model->get_val("SELECT GROUP_CONCAT(branch_fk) AS bid FROM `user_branch` WHERE `status`='1' AND user_fk='" . $data["login_data"]["id"] . "' GROUP BY user_fk");
			
			if($uid[0]["bid"] != ""){ $branchuser=$uid[0]["bid"]; }else{ $branchuser=0;  }
		}else{
			$branchuser="";
		}
		
			
			$data["query"]=$this->discountregister_model->get_jobreport($start_date,$end_date,$branch,$doctor,$credtejobuser,$branchuser);
			/* $data["links"] = $this->pagination->create_links();  */
			/* echo $this->db->last_query(); die(); */
			
			
}else{
	
$data["query"]=array();

}
			 
			/*  $data["doctor_list"]=$this->doctor_s_report_model->master_fun_get_tbl_val('doctor_master','id,full_name',array("status"=>'1'), array("full_name","asc")); */
			
			
		if ($logintype == 5 || $logintype == 6 || $logintype == 7 ) {
				  
		$data["admin_list"] = $this->discountregister_model->get_val1("SELECT `id`, `name` FROM `admin_master` WHERE `status` = '1' AND `type` != '4' AND id IN(SELECT user_fk FROM `user_branch` WHERE STATUS='1' AND TYPE != '1' AND `branch_fk` IN(SELECT `branch_fk`  FROM `user_branch`  WHERE STATUS = '1' AND user_fk = ".$data["login_data"]["id"].")) ORDER BY `name` ASC"); 
		
		}else{  $data["admin_list"] = $this->discountregister_model->master_fun_get_tbl_val('admin_master', 'id,name', array("status" => '1', "type !=" => '4'), array("name", "asc")); 
		}
			 
		$this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('discount_registereport', $data);
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

/* if($branch != ""){ $data['getbranch']=$this->discountregister_model->get_val("SELECT id,branch_name FROM branch_master WHERE STATUS='1' AND id IN($branch) ORDER BY branch_name ASC"); }else{ $data['getbranch']=array(); }

if($doctor != ""){ $data['getdoctor']=$this->discountregister_model->get_val("SELECT id,`full_name` FROM doctor_master WHERE STATUS='1' AND id IN($doctor) ORDER BY full_name ASC"); }else{ $data['getdoctor']=array(); } */


if($start_date != "" ||  $end_date != "" || $branch != "" || $doctor != "" || $credtejobuser != ""){
	
	function getjobdiscby($jobid){
	 if($jobid != ""){
		 $ci =& get_instance();
		  
		  $jobdiscby = $ci->discountregister_model->get_val("SELECT  GROUP_CONCAT(DISTINCT a.`name`) AS disby FROM job_log l LEFT JOIN `admin_master` a ON a.id=l.`updated_by` WHERE l.status='1' AND l.message_fk='24' AND l.job_fk='$jobid'");
		  return $jobdiscby[0];
           
    }

}
$logintype=$data["login_data"]['type'];
		if ($logintype == 5 || $logintype == 6 || $logintype == 7 ) {
			
			 $uid = $this->discountregister_model->get_val("SELECT GROUP_CONCAT(branch_fk) AS bid FROM `user_branch` WHERE `status`='1' AND user_fk='" . $data["login_data"]["id"] . "' GROUP BY user_fk");
			
			if($uid[0]["bid"] != ""){ $branchuser=$uid[0]["bid"]; }else{ $branchuser=0;  }
		}else{
			$branchuser="";
		}

$query=$this->discountregister_model->get_jobreport($start_date,$end_date,$branch,$doctor,$credtejobuser,$branchuser);
			
}else{
	
$query=array();

}
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"discount_register.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w'); 
		fputcsv($handle, array("Srl no","Date","Bill No","Lab RefNo","Patient Name","Total","Disc","Disc%","DiscBy","Operator Name","Disc.Note"));
			 $i=0;
    foreach($query as $row){
$discby=getjobdiscby($row->id);
		 $i++;
		fputcsv($handle,array($i,date("d-m-Y",strtotime($row->date)),$row->id,$row->branch_fk,ucwords($row->full_name),round($row->price),round($row->discountprice),round($row->discount),ucwords($discby["disby"]),ucwords($row->operater),ucwords($row->discount_note))); 
		
	}
	fclose($handle);
    exit;
 
}


}

?>