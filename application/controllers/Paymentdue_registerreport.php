<?php

class Paymentdue_registerreport extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('paymentdueregister_model');
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

if($branch != ""){ $data['getbranch']=$this->paymentdueregister_model->get_val("SELECT id,branch_name FROM branch_master WHERE STATUS='1' AND id IN($branch) ORDER BY branch_name ASC"); }else{ $data['getbranch']=array(); }

if($doctor != ""){ $data['getdoctor']=$this->paymentdueregister_model->get_val("SELECT id,`full_name` FROM doctor_master WHERE STATUS='1' AND id IN($doctor) ORDER BY full_name ASC"); }else{ $data['getdoctor']=array(); }

$logintype=$data["login_data"]['type'];
if($start_date != "" ||  $end_date != "" || $branch != "" || $doctor != "" || $credtejobuser != ""){
	
$this->load->library('pagination');


		if ($logintype == 5 || $logintype == 6 || $logintype == 7 ) {
			
			 $uid = $this->paymentdueregister_model->get_val("SELECT GROUP_CONCAT(branch_fk) AS bid FROM `user_branch` WHERE `status`='1' AND user_fk='" . $data["login_data"]["id"] . "' GROUP BY user_fk");
			
			if($uid[0]["bid"] != ""){ $branchuser=$uid[0]["bid"]; }else{ $branchuser=0;  }
		}else{
			$branchuser="";
		}
		
	 $totalRows = $this->paymentdueregister_model->get_jobreport('0','0',$start_date,$end_date,$branch,$doctor,$credtejobuser,$branchuser); 
	 
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
			 
			$data["perpage"]=$config["per_page"];
			$data["query"]=$this->paymentdueregister_model->get_jobreport($config["per_page"],$page,$start_date,$end_date,$branch,$doctor,$credtejobuser,$branchuser);
			$data["links"] = $this->pagination->create_links(); 
			
			
}else{
	
$data["perpage"]="0";	
$data["page"]="";	
$data["query"]=array();
$data["links"]="";

}
$data["branch_list"]=$this->paymentdueregister_model->master_fun_get_tbl_val('branch_master','id,branch_name',array("status"=>'1'), array("branch_name","asc"));
			 
			/*  $data["doctor_list"]=$this->doctor_s_report_model->master_fun_get_tbl_val('doctor_master','id,full_name',array("status"=>'1'), array("full_name","asc")); */
			
		$data["doctor_list"]=array();
		if ($logintype == 5 || $logintype == 6 || $logintype == 7 ) {
				  
		$data["admin_list"] = $this->paymentdueregister_model->get_val1("SELECT `id`, `name` FROM `admin_master` WHERE `status` = '1' AND `type` != '4' AND id IN(SELECT user_fk FROM `user_branch` WHERE STATUS='1' AND TYPE != '1' AND `branch_fk` IN(SELECT `branch_fk`  FROM `user_branch`  WHERE STATUS = '1' AND user_fk = ".$data["login_data"]["id"].")) ORDER BY `name` ASC"); 
		
		}else{  $data["admin_list"] = $this->paymentdueregister_model->master_fun_get_tbl_val('admin_master', 'id,name', array("status" => '1', "type !=" => '4'), array("name", "asc")); 
		}
			 
		$this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('paymentdue_registereport', $data);
        $this->load->view('footer');
		
		
}

public function report_exportcsv(){
        if (!is_loggedin()) {
            redirect('login');
        }
$start_date=$this->input->get('start_date');
$end_date=$this->input->get('end_date');
$branch=$this->input->get('branch');
$doctor=$this->input->get('doctor');
$credtejobuserall=$this->input->get('credtejobuser');
/* $branch=implode(",",$this->input->get('branch')); */
$credtejobuser=implode(",",$this->input->get('credtejobuser'));
$data["login_data"] = logindata();
$logintype=$data["login_data"]['type'];
if($branch != ""){ $data['getbranch']=$this->paymentdueregister_model->get_val("SELECT id,branch_name FROM branch_master WHERE STATUS='1' AND id IN($branch) ORDER BY branch_name ASC"); }else{ $data['getbranch']=array(); }

if($doctor != ""){ $data['getdoctor']=$this->paymentdueregister_model->get_val("SELECT id,`full_name` FROM doctor_master WHERE STATUS='1' AND id IN($doctor) ORDER BY full_name ASC"); }else{ $data['getdoctor']=array(); }


if($start_date != "" ||  $end_date != "" || $branch != "" || $doctor != "" || $credtejobuser != ""){
	
if ($logintype == 5 || $logintype == 6 || $logintype == 7 ) {
			
			 $uid = $this->paymentdueregister_model->get_val("SELECT GROUP_CONCAT(branch_fk) AS bid FROM `user_branch` WHERE `status`='1' AND user_fk='" . $data["login_data"]["id"] . "' GROUP BY user_fk");
			
			if($uid[0]["bid"] != ""){ $branchuser=$uid[0]["bid"]; }else{ $branchuser=0;  }
		}else{
			$branchuser="";
		}	
	
             $page =0;
			 $config["per_page"] = 1000;
        	 $query=$this->paymentdueregister_model->get_jobreport($config["per_page"],$page,$start_date,$end_date,$branch,$doctor,$credtejobuser,$branchuser);
			 
			 
}else{
	
$query=array();

}
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Paymentdue_register.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w'); 
		fputcsv($handle, array("Srl no","Regi.Date","Bill No","Lab RefNo","Patient Name","Ref.Dr.Name","Bill Total","Paid","Due","Branch name","Operator Name"));
			 $i=0;
    foreach($query as $row){
		
		 $total=round($row->price - $row->discount);
		 $paid=round($total-$row->payable_amount);
		 $due=round($row->payable_amount); 
		 $i++;
		fputcsv($handle,array($i,date("d-m-Y",strtotime($row->date)),$row->id,$row->branch_fk,ucwords($row->full_name),ucwords($row->dockname),$total,$paid,$due,ucwords($row->branch_name),ucwords($row->operater))); 
		
	}
	fclose($handle);
    exit;
 
}


}

?>