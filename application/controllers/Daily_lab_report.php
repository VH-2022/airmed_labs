<?php

class Daily_lab_report extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('recivepayment_modal');
        $data["login_data"]=logindata();
    }
public function report(){
        if (!is_loggedin()) {
            redirect('login');
        }
$data["login_data"] = logindata();
function getbranchlab($labid){
	 if($labid != ""){
		 
		 $ci =& get_instance();
		  
		 $laball=$ci->recivepayment_modal->get_val("SELECT c.id,c.`name` FROM `collect_from` c WHERE c.`status`='1' and c.id in(".$labid.") ORDER BY c.name ASC"); 
		
		 return  $laball; 
		 
	 }else{ return array(); }
  }
  
        function getlabpayamount($labid,$date){
	 if($labid != ""){
		 if($date !=""){ }
		 
		 $ci =& get_instance();
		$searchdata=array("lab_fk"=>$labid,"status"=>'1');
		if($date != ""){ $searchdata["DATE_FORMAT(pay_date,'%d-%m-%Y')"]=$date; }
		
		$totalpaymnts= $ci->recivepayment_modal->fetchdatarow('sum(amount) as amount','sample_receive_payment',$searchdata); 
		   
		/* $totalpaymnts=$ci->recivepayment_modal->get_val("SELECT SUM(amount) AS amount FROM sample_receive_payment WHERE lab_fk='$labid' AND STATUS='1' AND DATE_FORMAT(pay_date,'%d-%m-%Y') <= '$date'"); */
		
		 return $totalpaymnts->amount; 
		 
	 }else{ return 0; }
  }
function getsummary($labid,$date){
	 if($labid != ""){
		 
		 $ci =& get_instance();
		  
		  if($date != ""){ $seracdate="AND DATE_FORMAT(`logistic_log`.`createddate`,'%d-%m-%Y')='$date'"; }else{ $seracdate=""; } 
		 $summary=$ci->recivepayment_modal->get_val("SELECT COUNT(logistic_log.id) AS samplecount, SUM(sample_job_master.`price`)  AS sampleamount FROM `logistic_log`
LEFT JOIN `sample_job_master` ON sample_job_master.`barcode_fk`=logistic_log.id
WHERE `logistic_log`.`status`=1 $seracdate  AND `logistic_log`.`collect_from`=$labid"); 
		
		 return  $summary; 
		 
	 }else{ return 0; }
  }
	$startdate=$this->input->get("startdate");
	$branch=$this->input->get('branch');
	if($startdate != "" || $branch != ""){
		
	$data["startdate"]=$startdate;
	$logintype=$data["login_data"]['type'];
	
			/* if ($logintype == 5 || $logintype == 6 || $logintype == 7 ) {
				
				$lablisass=$this->recivepayment_modal->get_val("SELECT GROUP_CONCAT(DISTINCT c.`id` ) as id FROM b2b_labgroup g LEFT JOIN collect_from c ON c.id=g.labid AND c.`status`='1' WHERE g.status='1' AND g.branch_fk IN (SELECT user_branch.`branch_fk` FROM `user_branch` WHERE user_branch.`user_fk`=".$data["login_data"]['id']." AND user_branch.`status`=1) ORDER BY c.`name` ASC");
			
	if($lablisass[0]->id != ""){ 
	
	if($startdate != ""){  $datechck="AND DATE_FORMAT(l.scan_date,'%d-%m-%Y') = '$startdate'"; }else{ $datechck=""; }

		
$data["query"]=$this->recivepayment_modal->get_val("SELECT c.id,c.`name` FROM `collect_from` c WHERE c.`status`='1' and c.id in(".$lablisass[0]->id.") ORDER BY c.name ASC");
	
	}else{ $data["query"] = array();}
	
				
		 }else{
		 
if($startdate != ""){  $datechck="AND DATE_FORMAT(l.scan_date,'%d-%m-%Y') = '$startdate'"; }else{ $datechck=""; }	
$data["query"]=$this->recivepayment_modal->get_val("SELECT c.id,c.`name` FROM `collect_from` c WHERE c.`status`='1'  ORDER BY c.name ASC");
		 
		 } */
		 
	if($branch != ""){ $brasearch="AND b.id = $branch"; }else{ $brasearch=""; }	 
	if ($logintype == 5 || $logintype == 6 || $logintype == 7 ) { 
	
	$data["query"]=$this->recivepayment_modal->get_val("SELECT b.id,b.branch_name,GROUP_CONCAT(g.`labid`) AS labid FROM `branch_master` b LEFT JOIN b2b_labgroup g ON g.`branch_fk`=b.`id` AND g.`status`='1'  WHERE b.status='1' AND b.id IN(SELECT user_branch.`branch_fk` FROM `user_branch` WHERE user_branch.`user_fk`=".$data["login_data"]['id']." AND user_branch.`status`=1) AND labid !='' $brasearch GROUP BY b.id ORDER BY b.branch_name ASC");
	
	$data["branchall"]=$this->recivepayment_modal->get_val("SELECT id,branch_name FROM branch_master WHERE status='1' AND id IN(SELECT user_branch.`branch_fk` FROM `user_branch` WHERE user_branch.`user_fk`=".$data["login_data"]['id'].")");
	
	}else{
		
		$data["query"]=$this->recivepayment_modal->get_val("SELECT b.id,b.branch_name,GROUP_CONCAT(g.`labid`) AS labid FROM `branch_master` b LEFT JOIN b2b_labgroup g ON g.`branch_fk`=b.`id` AND g.`status`='1'  WHERE b.status='1' AND labid !='' $brasearch GROUP BY b.id ORDER BY b.branch_name ASC");
		$data["branchall"]=$this->recivepayment_modal->get_val("SELECT id,branch_name FROM branch_master WHERE status='1'");
		
		
	}
}else{ $data["query"]=array(); $data["branchall"]=$this->recivepayment_modal->get_val("SELECT id,branch_name FROM branch_master WHERE status='1'"); }
			
			
		
		
		$this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('labdaily_report', $data);
        $this->load->view('footer');
			
}
public function report_exportcsv(){
        if (!is_loggedin()) {
            redirect('login');
        }
$data["login_data"] = logindata();
function getbranchlab($labid){
	 if($labid != ""){
		 
		 $ci =& get_instance();
		  
		 $laball=$ci->recivepayment_modal->get_val("SELECT c.id,c.`name` FROM `collect_from` c WHERE c.`status`='1' and c.id in(".$labid.") ORDER BY c.name ASC"); 
		
		 return  $laball; 
		 
	 }else{ return array(); }
  }
  
        function getlabpayamount($labid,$date){
	 if($labid != ""){
		 if($date !=""){ }
		 
		 $ci =& get_instance();
		$searchdata=array("lab_fk"=>$labid,"status"=>'1');
		if($date != ""){ $searchdata["DATE_FORMAT(pay_date,'%d-%m-%Y')"]=$date; }
		
		$totalpaymnts= $ci->recivepayment_modal->fetchdatarow('sum(amount) as amount','sample_receive_payment',$searchdata); 
		   
		/* $totalpaymnts=$ci->recivepayment_modal->get_val("SELECT SUM(amount) AS amount FROM sample_receive_payment WHERE lab_fk='$labid' AND STATUS='1' AND DATE_FORMAT(pay_date,'%d-%m-%Y') <= '$date'"); */
		
		 return $totalpaymnts->amount; 
		 
	 }else{ return 0; }
  }
function getsummary($labid,$date){
	 if($labid != ""){
		 
		 $ci =& get_instance();
		  
		  if($date != ""){ $seracdate="AND DATE_FORMAT(`logistic_log`.`createddate`,'%d-%m-%Y')='$date'"; }else{ $seracdate=""; } 
		 $summary=$ci->recivepayment_modal->get_val("SELECT COUNT(logistic_log.id) AS samplecount, SUM(sample_job_master.`payable_amount`)  AS sampleamount FROM `logistic_log`
LEFT JOIN `sample_job_master` ON sample_job_master.`barcode_fk`=logistic_log.id
WHERE `logistic_log`.`status`=1 $seracdate  AND `logistic_log`.`collect_from`=$labid"); 
		
		 return  $summary; 
		 
	 }else{ return 0; }
  }
	$startdate=$this->input->get("startdate");
	$branch=$this->input->get('branch');
	if($startdate != "" || $branch != ""){
		
	$data["startdate"]=$startdate;
	$logintype=$data["login_data"]['type'];
	
			/* if ($logintype == 5 || $logintype == 6 || $logintype == 7 ) {
				
				$lablisass=$this->recivepayment_modal->get_val("SELECT GROUP_CONCAT(DISTINCT c.`id` ) as id FROM b2b_labgroup g LEFT JOIN collect_from c ON c.id=g.labid AND c.`status`='1' WHERE g.status='1' AND g.branch_fk IN (SELECT user_branch.`branch_fk` FROM `user_branch` WHERE user_branch.`user_fk`=".$data["login_data"]['id']." AND user_branch.`status`=1) ORDER BY c.`name` ASC");
			
	if($lablisass[0]->id != ""){ 
	
	if($startdate != ""){  $datechck="AND DATE_FORMAT(l.scan_date,'%d-%m-%Y') = '$startdate'"; }else{ $datechck=""; }

		
$data["query"]=$this->recivepayment_modal->get_val("SELECT c.id,c.`name` FROM `collect_from` c WHERE c.`status`='1' and c.id in(".$lablisass[0]->id.") ORDER BY c.name ASC");
	
	}else{ $data["query"] = array();}
	
				
		 }else{
		 
if($startdate != ""){  $datechck="AND DATE_FORMAT(l.scan_date,'%d-%m-%Y') = '$startdate'"; }else{ $datechck=""; }	
$data["query"]=$this->recivepayment_modal->get_val("SELECT c.id,c.`name` FROM `collect_from` c WHERE c.`status`='1'  ORDER BY c.name ASC");
		 
		 } */
	if($branch != ""){ $brasearch="AND b.id = $branch"; }else{ $brasearch=""; }	 
	if ($logintype == 5 || $logintype == 6 || $logintype == 7 ) { 
	
	$data["query"]=$this->recivepayment_modal->get_val("SELECT b.id,b.branch_name,GROUP_CONCAT(g.`labid`) AS labid FROM `branch_master` b LEFT JOIN b2b_labgroup g ON g.`branch_fk`=b.`id` AND g.`status`='1'  WHERE b.status='1' AND b.id IN(SELECT user_branch.`branch_fk` FROM `user_branch` WHERE user_branch.`user_fk`=".$data["login_data"]['id']." AND user_branch.`status`=1) AND labid !='' $brasearch GROUP BY b.id ORDER BY b.branch_name ASC");
	
	$data["branchall"]=$this->recivepayment_modal->get_val("SELECT id,branch_name FROM branch_master WHERE status='1' and AND id IN(SELECT user_branch.`branch_fk` FROM `user_branch` WHERE user_branch.`user_fk`=".$data["login_data"]['id']."");
	
	}else{
		
		$data["query"]=$this->recivepayment_modal->get_val("SELECT b.id,b.branch_name,GROUP_CONCAT(g.`labid`) AS labid FROM `branch_master` b LEFT JOIN b2b_labgroup g ON g.`branch_fk`=b.`id` AND g.`status`='1'  WHERE b.status='1' AND labid !='' $brasearch GROUP BY b.id ORDER BY b.branch_name ASC");
		$data["branchall"]=$this->recivepayment_modal->get_val("SELECT id,branch_name FROM branch_master WHERE status='1'");
		
		
	}
}else{ $data["query"]=array(); $data["branchall"]=$this->recivepayment_modal->get_val("SELECT id,branch_name FROM branch_master WHERE status='1'"); }


        $query=$data["query"];
		
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"DailyCollection_report.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w'); 
		fputcsv($handle, array("Sr no","Branch Name","Lab Name","Total Sample","Total Amount","Amount collection"));
					
	$i=0;
										
    foreach($query as $row){
		
	$branchlab=getbranchlab($row->labid);
		if($branchlab != ""){
		foreach($branchlab as $row1){
			
		$i++;
		$data=getlabpayamount($row1->id,$startdate);
		$summary=getsummary($row1->id,$startdate);
		
		fputcsv($handle,array($i,ucwords($row->branch_name),ucwords($row1->name),round($summary[0]->samplecount),round($summary[0]->sampleamount),round($data))); 
		
		} 
		
		}
		
	}
	fclose($handle);
    exit;
 
}


}

?>