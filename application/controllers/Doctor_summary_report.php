<?php

class Doctor_summary_report extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('doctor_s_report_model');
        $data["login_data"] = logindata();
    }
public function report(){
        if (!is_loggedin()) {
            redirect('login');
        }
$data["login_data"] = logindata();
$logintype=$data["login_data"]['type'];
		if ($logintype == 5 || $logintype == 6 || $logintype == 7 ) {
			
			 $uid = $this->doctor_s_report_model->get_val("SELECT GROUP_CONCAT(branch_fk) AS bid FROM `user_branch` WHERE `status`='1' AND user_fk='" . $data["login_data"]["id"] . "' GROUP BY user_fk");
			
			if($uid[0]["bid"] != ""){ $branchuser=$uid[0]["bid"]; }else{ $branchuser=0;  }
		}else{
			$branchuser="";
		}
$data["branchuser"]=$branchuser;
$start_date=$this->input->get('start_date');
$end_date=$this->input->get('end_date');
$branch=$this->input->get('branch');
$doctor=$this->input->get('doctor');
$credtejobuser=implode(",",$this->input->get('credtejobuser'));


if($branch != ""){ $data['getbranch']=$this->doctor_s_report_model->get_val("SELECT id,branch_name FROM branch_master WHERE STATUS='1' AND id IN($branch) ORDER BY branch_name ASC"); }else{ $data['getbranch']=array(); }

if($doctor != ""){ $data['getdoctor']=$this->doctor_s_report_model->get_val("SELECT id,`full_name` FROM doctor_master WHERE STATUS='1' AND id IN($doctor) ORDER BY full_name ASC"); }else{ $data['getdoctor']=array(); }



		
if($start_date != "" ||  $end_date != "" || $branch != "" || $doctor != "" || $credtejobuser != "" ){
	
	
	
	function getjobdoctor($doctorid,$branchuser){
	 if($doctorid != ""){
		 $ci =& get_instance();
		  $totalpaymnts= $ci->doctor_s_report_model->doctorjob_get($doctorid,$branchuser,$start_date,$end_date,$branch,$doctor,$credtejobuser,$branchuser);
		  
		  /* if($totalpaymnts->tjobs != ""){ 
		  
		  $total=$ci->doctor_s_report_model->jobpaidamount_get($totalpaymnts->tjobs); 
		  $total1=$ci->doctor_s_report_model->jobwalletamount_get($totalpaymnts->tjobs);
		  $total2=$ci->doctor_s_report_model->jobcreditoramount_get($totalpaymnts->tjobs);
		  
		  }else{ $total=""; $total1=""; $total2="";  } 
		  */
		
		 return array("totalpaymnts"=>$totalpaymnts); 
		 
	 }else{ return 0; }
	 
 }
	
	$this->load->library('pagination');
	
     $totalRows = $this->doctor_s_report_model->doctor_listnum($doctor); 
       
          $config = array();
          $config["base_url"] = base_url() . "doctor_summary_report/report/";

            $config["total_rows"] =$totalRows;

            $config["per_page"] = 1000;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
             
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["page"] = $page;
			
            $data["query"]=$this->doctor_s_report_model->doctor_list($config["per_page"],$page,$doctor);
			 $data["links"] = $this->pagination->create_links(); 
	
}else{
	
$data["page"]="";	
$data["query"]=array();
$data["links"]="";

}

			 
			 /* $data["branch_list"]=$this->doctor_s_report_model->master_fun_get_tbl_val('branch_master','id,branch_name',array("status"=>'1'), array("branch_name","asc"));
			 
			 $data["doctor_list"]=$this->doctor_s_report_model->master_fun_get_tbl_val('doctor_master','id,full_name',array("status"=>'1'), array("full_name","asc")); */
			 
			if ($logintype == 5 || $logintype == 6 || $logintype == 7 ) {
				  
		$data["admin_list"] = $this->doctor_s_report_model->get_val1("SELECT `id`, `name` FROM `admin_master` WHERE `status` = '1' AND `type` != '4' AND id IN(SELECT user_fk FROM `user_branch` WHERE STATUS='1' AND TYPE != '1' AND `branch_fk` IN(SELECT `branch_fk`  FROM `user_branch`  WHERE STATUS = '1' AND user_fk = ".$data["login_data"]["id"].")) ORDER BY `name` ASC"); 
		
		}else{  $data["admin_list"] = $this->doctor_s_report_model->master_fun_get_tbl_val('admin_master', 'id,name', array("status" => '1', "type !=" => '4'), array("name", "asc")); 
		}
			 

		$this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('doctor_summary_report', $data);
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
$credtejobuser=implode(",",$this->input->get('credtejobuser'));

if($branch != ""){ $data['getbranch']=$this->doctor_s_report_model->get_val("SELECT id,branch_name FROM branch_master WHERE STATUS='1' AND id IN($branch) ORDER BY branch_name ASC"); }else{ $data['getbranch']=array(); }

if($doctor != ""){ $data['getdoctor']=$this->doctor_s_report_model->get_val("SELECT id,`full_name` FROM doctor_master WHERE STATUS='1' AND id IN($doctor) ORDER BY full_name ASC"); }else{ $data['getdoctor']=array(); }


if($start_date != "" ||  $end_date != "" || $branch != "" || $doctor != "" || $credtejobuser != ""){
	
	
	function getjobdoctor($doctorid,$branchuser){
	 if($doctorid != ""){
		 $ci =& get_instance();
		  $totalpaymnts= $ci->doctor_s_report_model->doctorjob_get($doctorid,$branchuser,$start_date,$end_date,$branch,$doctor,$credtejobuser);
		  
		  /* if($totalpaymnts->tjobs != ""){ 
		  
		  $total=$ci->doctor_s_report_model->jobpaidamount_get($totalpaymnts->tjobs); 
		  $total1=$ci->doctor_s_report_model->jobwalletamount_get($totalpaymnts->tjobs);
		  $total2=$ci->doctor_s_report_model->jobcreditoramount_get($totalpaymnts->tjobs);
		  
		  }else{ $total=""; $total1=""; $total2="";  } 
		  */
		
		 return array("totalpaymnts"=>$totalpaymnts); 
		 
	 }else{ return 0; }
	 
 }
	
	        $config["per_page"] = 1000;
            $page =0;
        
			
            $query=$this->doctor_s_report_model->doctor_list($config["per_page"],$page,$doctor);
			 
	
}else{
	
$query=array();


}

$logintype=$data["login_data"]['type'];
		if ($logintype == 5 || $logintype == 6 || $logintype == 7 ) {
			
			 $uid = $this->doctor_s_report_model->get_val("SELECT GROUP_CONCAT(branch_fk) AS bid FROM `user_branch` WHERE `status`='1' AND user_fk='" . $data["login_data"]["id"] . "' GROUP BY user_fk");
			
			if($uid[0]["bid"] != ""){ $branchuser=$uid[0]["bid"]; }else{ $branchuser=0;  }
		}else{
			$branchuser="";
		}

         header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Referringdr_summaryreport.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w'); 
		fputcsv($handle, array("Srno","Doctor Name","#Patient","#Bill Total","Discount","Paid Total","Due"));
			 $i=0;
									
    foreach($query as $row){
		$jobdetils1=getjobdoctor($row->id,$branchuser);
		$jobdetils=$jobdetils1["totalpaymnts"];
		$i++;
									
	if($jobdetils->tjobs != ""){ $tjobs=count(explode(",",$jobdetils->tjobs)); }else{ $tjobs="0"; }
	$paidtptal=(round($jobdetils->bitotal)-round($jobdetils->discount)-round($jobdetils->dueamount));							
		fputcsv($handle,array($i,ucwords($row->full_name),$tjobs,round($jobdetils->bitotal),round($jobdetils->discount),($paidtptal),round($jobdetils->dueamount))); 
		
	}
	fclose($handle);
    exit;
 
}

}

?>