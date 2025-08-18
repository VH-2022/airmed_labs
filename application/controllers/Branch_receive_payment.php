<?php

class Branch_receive_payment extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('branchreceive_model');
        $data["login_data"] = logindata();
    }
public function receive_payment_list(){
        if (!is_loggedin()) {
            redirect('login');
        }
$data["login_data"] = logindata();

 $this->load->library('pagination');
 $totalRows = $this->branchreceive_model->num_row('branch_payment', array('status' => 1));		 
          $config = array();
          $config["base_url"] = base_url()."branch_receive_payment/receive_payment_list/";
			$config["total_rows"] =$totalRows;

            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
             
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["page"] = $page;
			$data["query"] = $this->branchreceive_model->payments_list($config["per_page"], $page);
			$data["links"] = $this->pagination->create_links();
			
		$this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('branchreceive_payment_list', $data);
        $this->load->view('footer');
			
}
public function receive_payment_add(){ 
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
		/* $data["user"] = $this->branchreceive_model->getUser($data["login_data"]["id"]); */
		$data['success'] = $this->session->flashdata("success");
		$this->load->library('form_validation');
        $this->form_validation->set_rules('paydate','Date','trim|required');
		$this->form_validation->set_rules('branchname','Branch name','trim|required|numeric');
		$this->form_validation->set_rules('amount','Amount','trim|required|numeric');
		$this->form_validation->set_rules('transaction','Transaction No','trim');
		$this->form_validation->set_rules('note','Note','trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');
	if ($this->form_validation->run() != FALSE) {
            $data = array(
			   'branch_fk' =>$this->input->post('branchname'),
			   'date'=>date("Y-m-d",strtotime($this->input->post('paydate'))),
                'amount' => $this->input->post('amount'),
                'transactionno' =>$this->input->post('transaction'),
				'note'=>$this->input->post('note'),
                "credteddate" => date("Y-m-d H:i:s"),
                "credted_by" => $data["login_data"]['id']
            );

           $this->branchreceive_model->master_fun_insert("branch_payment",$data);
		$this->session->set_flashdata("success", array("Branch receive payment successfull added."));
		  
            redirect("branch_receive_payment/receive_payment_add", "refresh");
			
           }else{
			   
		$data['branchlist']=$this->branchreceive_model->master_fun_get_tbl_val('branch_master','id,branch_name',array("status"=>'1'),array("branch_name","ASC"));
		$this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('Branch_receive_payment_add', $data);
        $this->load->view('footer');
			   
		   }
        
    
	}
	public function receive_payment_edit($id){ 
        if (!is_loggedin()) {
            redirect('login');
        }
	if($id != ""){
			
        $data["login_data"] = logindata();
		/* $data["user"] = $this->branchreceive_model->getUser($data["login_data"]["id"]); */
		$data['success'] = $this->session->flashdata("success");
		$this->load->library('form_validation');
        $this->form_validation->set_rules('paydate','Date','trim|required');
		$this->form_validation->set_rules('branchname','Branch name','trim|required|numeric');
		$this->form_validation->set_rules('amount','Amount','trim|required|numeric');
		$this->form_validation->set_rules('transaction','Transaction No','trim');
		$this->form_validation->set_rules('note','Note','trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');
	if ($this->form_validation->run() != FALSE) {
            $data = array(
			   'branch_fk' =>$this->input->post('branchname'),
			   'date'=>date("Y-m-d",strtotime($this->input->post('paydate'))),
                'amount' => $this->input->post('amount'),
                'transactionno' =>$this->input->post('transaction'),
				'note'=>$this->input->post('note'),
                "credteddate" => date("Y-m-d H:i:s"),
                "credted_by" => $data["login_data"]['id']
            );
			
			$this->branchreceive_model->updateRowWhere("branch_payment",array("id"=>$id), $data);
			$this->session->set_flashdata("success", array("Branch receive payment successfull updated."));
		   redirect("branch_receive_payment/receive_payment_edit/$id", "refresh");
			
           }else{
			   
		$data['branchlist']=$this->branchreceive_model->master_fun_get_tbl_val('branch_master','id,branch_name',array("status"=>'1'),array("branch_name","ASC"));
		
		$data['branchlistdetils']=$this->branchreceive_model->fetchdatarow('id,branch_fk,date,amount,transactionno,note','branch_payment',array("id"=>$id));
		
		
		$this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('Branch_receive_payment_edit', $data);
        $this->load->view('footer');
			   
		   }
        }
    
	}
public function receive_payment_delete($id){
	 if (!is_loggedin()) {
            redirect('login');
        }
	if($id != ""){
		
		 $data = array("status"=>'0');
			
			$this->branchreceive_model->updateRowWhere("branch_payment",array("id"=>$id), $data);
			$this->session->set_flashdata("success", array("Branch receive payment successfull deleted."));
		   redirect("branch_receive_payment/receive_payment_add", "refresh");
		
	}else{ show_404(); }
	
}
public function report(){
        if (!is_loggedin()) {
            redirect('login');
        }
$data["login_data"] = logindata();
$branch=$this->input->get('branch');
if($branch != ""){

$getlederbranch=$this->branchreceive_model->fetchdatarow('GROUP_CONCAT(DISTINCT branchid) as branchall','party_ledger_branch',array("lederid"=>$branch,"status"=>'1'));
$alllederbranch=$getlederbranch->branchall;
$data['alllederbranch']=$alllederbranch;
$data['branch']=$branch;
/* $start_date=date("Y-m-d",strtotime($this->input->get('start_date')));
$end_date=date("Y-m-d",strtotime($this->input->get('end_date'))); */	
$start_date=$this->input->get('start_date');
$end_date=$this->input->get('end_date');

 function getjobspayamount($branch,$jobid,$date){
	 if($jobid != "" && $branch != "" &&  $date != ""){
		 $ci =& get_instance();
		 /* $totalpaymnts= $ci->branchreceive_model->fetchdatarow('sum(amount) as amount','job_master_receiv_amount',array("job_fk"=>$jobid,"status"=>'1',"payment_type"=>'CASH')); */
		 /* $totalpaymnts= $ci->branchreceive_model->jobsreciveamount($jobid);
		 if($totalpaymnts->amount != ""){ $amount=$totalpaymnts->amount; }else{ $amount=0;} */
		 $banckamount=$ci->branchreceive_model->banckamount($branch,$date);
		 if($banckamount->amount != ""){ $bamount=$banckamount->amount; }else{ $bamount=0;}
		 /*  $oldjobspayments=$ci->branchreceive_model->jobcumulativeamount($branch,$date);
		 
		 if($oldjobspayments->amount != ""){ $camount=$oldjobspayments->amount; }else{ $camount=0; } */
		 $data=array("banckamount"=>$bamount);
		 return $data; 
		 
	 }else{ return 0; }
 }
 /* echo "<pre>"; print_r(getjobspayamount('1','1353,10046',date("Y-m-d",strtotime("2016-11-18 02:15:32")))); die();  */
 
 
 $data['CashFromStartDay']=$this->branchreceive_model->getCashFromStartDay($alllederbranch,$start_date); 
 
 
 $startdate=date("Y-m-d",strtotime($this->input->get('start_date'))); $cstartdate="AND STR_TO_DATE( creditors_balance.`created_date`,'%Y-%m-%d') < '$startdate' "; 
 
 
$prevDay=date("Y-m-d",strtotime($startdate.' -1 day'));
 $data['CashPrevDay']=$this->branchreceive_model->getCashOfDay($alllederbranch,$prevDay); 

  
 
 $data['CashPrevDay']=$data['CashPrevDay'][0]->SameDay+$data['CashPrevDay'][0]->BackDay;
 
 $data['CashCreditorDataFromStartDay'] = $this->branchreceive_model->get_val("  SELECT DATE(creditors_balance.`created_date`)  as  `date`,  SUM(
    IF(
      DATE(
        creditors_balance.`created_date`
      ) = DATE(job_master.`date`),
      creditors_balance.`credit`,
      0
    )
  ) AS 'SameDay',
  SUM(
    IF(
      DATE(
        creditors_balance.`created_date`
      ) = DATE(job_master.`date`),
      0,
      creditors_balance.`credit`
    )
  ) AS 'BackDay'   
  
  FROM `creditors_balance`
LEFT JOIN job_master ON job_master.id=creditors_balance.`job_id`
WHERE job_master.`status`!=0 AND creditors_balance.`status`=1 AND `job_master`.`branch_fk` IN($alllederbranch) $cstartdate");
  
  $data['CashCreditorDataPrevDay'] = $this->branchreceive_model->get_val("  SELECT DATE(creditors_balance.`created_date`)  as  `date`,  SUM(
    IF(
      DATE(
        creditors_balance.`created_date`
      ) = DATE(job_master.`date`),
      creditors_balance.`credit`,
      0
    )
  ) AS 'SameDay',
  SUM(
    IF(
      DATE(
        creditors_balance.`created_date`
      ) = DATE(job_master.`date`),
      0,
      creditors_balance.`credit`
    )
  ) AS 'BackDay'   
  
  
  FROM `creditors_balance`
LEFT JOIN job_master ON job_master.id=creditors_balance.`job_id`
WHERE job_master.`status`!=0 AND creditors_balance.`status`=1 AND `job_master`.`branch_fk` IN($alllederbranch) AND STR_TO_DATE( creditors_balance.`created_date`,'%Y-%m-%d') = '$prevDay' 
  ");
  /* echo $this->db->last_query();  */
  $data['CashCreditorDataPrevDay']=$data['CashCreditorDataPrevDay'][0]->SameDay+$data['CashCreditorDataPrevDay'][0]->BackDay;
  
  
 $data['BankDepositFromStartDay'] = $this->branchreceive_model->get_val("  SELECT sum(branch_payment.amount) as amount FROM `branch_payment` WHERE branch_payment.`status`='1' AND branch_payment.`branch_fk` IN($alllederbranch) AND branch_payment.`date` <'$startdate' 
  ");
  
  $data['BankDepositFromStartDay']=($data['BankDepositFromStartDay'][0]->amount>0)?$data['BankDepositFromStartDay'][0]->amount:0;
  $data['BankDepositPrevDay'] = $this->branchreceive_model->get_val("  SELECT sum(branch_payment.amount) as amount FROM `branch_payment` WHERE branch_payment.`status`=1 AND branch_payment.`branch_fk` IN($alllederbranch) AND branch_payment.`date` ='$prevDay' 
  ");
  
  
  $data['BankDepositPrevDay']=($data['BankDepositPrevDay'][0]->amount>0)?$data['BankDepositPrevDay'][0]->amount:0;
  
 
 
 $cashData=$this->branchreceive_model->getjobsto_branch($alllederbranch,$start_date,$end_date); 
 
 $creditorData = $this->branchreceive_model->get_val("SELECT DATE(creditors_balance.`created_date`)  as  `date`,  SUM(
    IF(
      DATE(
        creditors_balance.`created_date`
      ) = DATE(job_master.`date`),
      creditors_balance.`credit`,
      0
    )
  ) AS 'SameDay',
  SUM(
    IF(
      DATE(
        creditors_balance.`created_date`
      ) = DATE(job_master.`date`),
      0,
      creditors_balance.`credit`
    )
  ) AS 'BackDay'   
  
  
  FROM `creditors_balance`
LEFT JOIN job_master ON job_master.id=creditors_balance.`job_id`
WHERE job_master.`status`!=0 AND creditors_balance.`status`=1 AND `job_master`.`branch_fk`=2
GROUP BY DATE(creditors_balance.`created_date`) 
  ");
  
  $output=array();
  
   $startDateTemp=date("Y-m-d",strtotime($this->input->get('start_date')));
   $endDateTemp=date("Y-m-d",strtotime($this->input->get('end_date')));
  
   $dateTemp=$startDateTemp;
  while ($dateTemp<=$endDateTemp){

  $o=array("date"=>$dateTemp);
  $o["creditor_sameday"]=0;
  $o["creditor_backday"]=0;
  $o["SameDay"]=0;
  $o["BackDay"]=0;
   foreach($cashData as $d){
	if($d->date == $dateTemp){
	$o["SameDay"]=$d->SameDay;
			$o["BackDay"]=$d->BackDay;
	}
   }
  
	foreach($creditorData as $cd){
		if($cd->date == $dateTemp){
			$o["creditor_sameday"]=$cd->SameDay;
			$o["creditor_backday"]=$cd->BackDay;
		}
	
	
}
  $output[]=$o;
  $dateTemp=date("Y-m-d",strtotime($dateTemp. ' +1 day' ));
  
  } 

 }else{ $data['branch']=''; $data['alllederbranch']=""; $output=array(); }
 $new_array = array();
 $data["query"]=$output;
 $data['branch_list'] = $this->branchreceive_model->master_fun_get_tbl_val("branch_master",'id,branch_name',array('status' => 1), array("id", "asc"));
 $data['branch_list'] = $this->branchreceive_model->get_val("  select * from party_ledger_account where status=1");
		$this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('branchreceive_paymentreport', $data);
        $this->load->view('footer');
			
			
}

public function print_report(){
        if (!is_loggedin()) {
            redirect('login');
        }
$data["login_data"] = logindata();
$branch=$this->input->get('branch');
if($branch != ""){
	$data['branch']=$branch;
/* $start_date=date("Y-m-d",strtotime($this->input->get('start_date')));
$end_date=date("Y-m-d",strtotime($this->input->get('end_date'))); */	
$start_date=$this->input->get('start_date');
$end_date=$this->input->get('end_date');

 function getjobspayamount($branch,$jobid,$date){
	 if($jobid != "" && $branch != "" &&  $date != ""){
		 $ci =& get_instance();
		 /* $totalpaymnts= $ci->branchreceive_model->fetchdatarow('sum(amount) as amount','job_master_receiv_amount',array("job_fk"=>$jobid,"status"=>'1',"payment_type"=>'CASH')); */
		 /* $totalpaymnts= $ci->branchreceive_model->jobsreciveamount($jobid);
		 if($totalpaymnts->amount != ""){ $amount=$totalpaymnts->amount; }else{ $amount=0;} */
		 $banckamount=$ci->branchreceive_model->banckamount($branch,$date);
		 if($banckamount->amount != ""){ $bamount=$banckamount->amount; }else{ $bamount=0;}
		 /*  $oldjobspayments=$ci->branchreceive_model->jobcumulativeamount($branch,$date);
		 
		 if($oldjobspayments->amount != ""){ $camount=$oldjobspayments->amount; }else{ $camount=0; } */
		 $data=array("banckamount"=>$bamount);
		 return $data; 
		 
	 }else{ return 0; }
 }
 /* echo "<pre>"; print_r(getjobspayamount('1','1353,10046',date("Y-m-d",strtotime("2016-11-18 02:15:32")))); die();  */
 
 
 $data['CashFromStartDay']=$this->branchreceive_model->getCashFromStartDay($data['branch'],$start_date); 
 
 
 $startdate=date("Y-m-d",strtotime($this->input->get('start_date'))); $cstartdate="AND STR_TO_DATE( creditors_balance.`created_date`,'%Y-%m-%d') < '$startdate' "; 
 
 
$prevDay=date("Y-m-d",strtotime($startdate.' -1 day'));
 $data['CashPrevDay']=$this->branchreceive_model->getCashOfDay($data['branch'],$prevDay); 

 $data['CashPrevDay']=$data['CashPrevDay'][0]->SameDay+$data['CashPrevDay'][0]->BackDay;
 $data['CashCreditorDataFromStartDay'] = $this->branchreceive_model->get_val("  SELECT DATE(creditors_balance.`created_date`)  as  `date`,  SUM(
    IF(
      DATE(
        creditors_balance.`created_date`
      ) = DATE(job_master.`date`),
      creditors_balance.`credit`,
      0
    )
  ) AS 'SameDay',
  SUM(
    IF(
      DATE(
        creditors_balance.`created_date`
      ) = DATE(job_master.`date`),
      0,
      creditors_balance.`credit`
    )
  ) AS 'BackDay'   
  
  
  FROM `creditors_balance`
LEFT JOIN job_master ON job_master.id=creditors_balance.`job_id`
WHERE job_master.`status`!=0 AND creditors_balance.`status`=1 AND `job_master`.`branch_fk`=$branch $cstartdate 
  ");
  
  $data['CashCreditorDataPrevDay'] = $this->branchreceive_model->get_val("  SELECT DATE(creditors_balance.`created_date`)  as  `date`,  SUM(
    IF(
      DATE(
        creditors_balance.`created_date`
      ) = DATE(job_master.`date`),
      creditors_balance.`credit`,
      0
    )
  ) AS 'SameDay',
  SUM(
    IF(
      DATE(
        creditors_balance.`created_date`
      ) = DATE(job_master.`date`),
      0,
      creditors_balance.`credit`
    )
  ) AS 'BackDay'   
  
  
  FROM `creditors_balance`
LEFT JOIN job_master ON job_master.id=creditors_balance.`job_id`
WHERE job_master.`status`!=0 AND creditors_balance.`status`=1 AND `job_master`.`branch_fk`=$branch AND STR_TO_DATE( creditors_balance.`created_date`,'%Y-%m-%d') = '$prevDay' 
  ");
  /* echo $this->db->last_query();  */
  $data['CashCreditorDataPrevDay']=$data['CashCreditorDataPrevDay'][0]->SameDay+$data['CashCreditorDataPrevDay'][0]->BackDay;
  
  
 $data['BankDepositFromStartDay'] = $this->branchreceive_model->get_val("  SELECT sum(branch_payment.amount) as amount FROM `branch_payment` WHERE branch_payment.`status`='1' AND branch_payment.`branch_fk`=$branch AND branch_payment.`date` <'$startdate' 
  ");
  
  $data['BankDepositFromStartDay']=($data['BankDepositFromStartDay'][0]->amount>0)?$data['BankDepositFromStartDay'][0]->amount:0;
  $data['BankDepositPrevDay'] = $this->branchreceive_model->get_val("  SELECT sum(branch_payment.amount) as amount FROM `branch_payment` WHERE branch_payment.`status`=1 AND branch_payment.`branch_fk`=$branch AND branch_payment.`date` ='$$prevDay' 
  ");
  
  
  $data['BankDepositPrevDay']=($data['BankDepositPrevDay'][0]->amount>0)?$data['BankDepositPrevDay'][0]->amount:0;
  
 
 
 $cashData=$this->branchreceive_model->getjobsto_branch($data['branch'],$start_date,$end_date); 
 
 $creditorData = $this->branchreceive_model->get_val("  SELECT DATE(creditors_balance.`created_date`)  as  `date`,  SUM(
    IF(
      DATE(
        creditors_balance.`created_date`
      ) = DATE(job_master.`date`),
      creditors_balance.`credit`,
      0
    )
  ) AS 'SameDay',
  SUM(
    IF(
      DATE(
        creditors_balance.`created_date`
      ) = DATE(job_master.`date`),
      0,
      creditors_balance.`credit`
    )
  ) AS 'BackDay'   
  
  
  FROM `creditors_balance`
LEFT JOIN job_master ON job_master.id=creditors_balance.`job_id`
WHERE job_master.`status`!=0 AND creditors_balance.`status`=1 AND `job_master`.`branch_fk`=2
GROUP BY DATE(creditors_balance.`created_date`) 
  ");
  
  $output=array();
  
   $startDateTemp=date("Y-m-d",strtotime($this->input->get('start_date')));
   $endDateTemp=date("Y-m-d",strtotime($this->input->get('end_date')));
  
   $dateTemp=$startDateTemp;
  while ($dateTemp<=$endDateTemp){
 
  $o=array("date"=>$dateTemp);
  $o["creditor_sameday"]=0;
  $o["creditor_backday"]=0;
  $o["SameDay"]=0;
  $o["BackDay"]=0;
   foreach($cashData as $d){
	if($d->date == $dateTemp){
	$o["SameDay"]=$d->SameDay;
			$o["BackDay"]=$d->BackDay;
	}
   }
  
	foreach($creditorData as $cd){
		if($cd->date == $dateTemp){
			$o["creditor_sameday"]=$cd->SameDay;
			$o["creditor_backday"]=$cd->BackDay;
		}
	
	
}
  $output[]=$o;
  $dateTemp=date("Y-m-d",strtotime($dateTemp. ' +1 day' ));
  
  }
  
}else{ $data['branch']=''; $output=array(); }
 $new_array = array();
 $data["query"]=$output;
 $pdfFilePath=FCPATH."/upload/branchreport/Receivepayment.pdf";	
		$html = $this->load->view('branchreport_pdf_view',$data,true); 
		$this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;
        $pdf->autoLangToFont = true;
        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 5, // margin_left
                5, // margin right
                30, // margin top
                40, // margin bottom
                2, // margin header
                2); // margin footer
        $pdf->WriteHTML($html);
		$pdf->debug = true;
        $pdf->allow_output_buffering = TRUE;
		if (file_exists($pdfFilePath) == true) {
            $this->load->helper('file');
            unlink($path);
        }
        $pdf->Output($pdfFilePath, 'F');
	$base_url=base_url()."upload/branchreport/Receivepayment.pdf";
$filename=FCPATH."/upload/branchreport/Receivepayment.pdf";	
header('Pragma: public');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Cache-Control: private', false); // required for certain browsers 
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="'. basename($filename) . '";');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($filename));
readfile($filename);
exit;	
		
}
public function report_exportcsv(){
        if (!is_loggedin()) {
            redirect('login');
        }
$branch=$this->input->get('branch');
if($branch != ""){
	$data['branch']=$branch;
/* $start_date=date("Y-m-d",strtotime($this->input->get('start_date')));
$end_date=date("Y-m-d",strtotime($this->input->get('end_date'))); */	
$start_date=$this->input->get('start_date');
$end_date=$this->input->get('end_date');

 function getjobspayamount($branch,$jobid,$date){
	 if($jobid != "" && $branch != "" &&  $date != ""){
		 $ci =& get_instance();
		 /* $totalpaymnts= $ci->branchreceive_model->fetchdatarow('sum(amount) as amount','job_master_receiv_amount',array("job_fk"=>$jobid,"status"=>'1',"payment_type"=>'CASH')); */
		 /* $totalpaymnts= $ci->branchreceive_model->jobsreciveamount($jobid);
		 if($totalpaymnts->amount != ""){ $amount=$totalpaymnts->amount; }else{ $amount=0;} */
		 $banckamount=$ci->branchreceive_model->banckamount($branch,$date);
		 if($banckamount->amount != ""){ $bamount=$banckamount->amount; }else{ $bamount=0;}
		 /*  $oldjobspayments=$ci->branchreceive_model->jobcumulativeamount($branch,$date);
		 
		 if($oldjobspayments->amount != ""){ $camount=$oldjobspayments->amount; }else{ $camount=0; } */
		 $data=array("banckamount"=>$bamount);
		 return $data; 
		 
	 }else{ return 0; }
 }
 /* echo "<pre>"; print_r(getjobspayamount('1','1353,10046',date("Y-m-d",strtotime("2016-11-18 02:15:32")))); die();  */
 
 
 $data['CashFromStartDay']=$this->branchreceive_model->getCashFromStartDay($data['branch'],$start_date); 
 
 
 $startdate=date("Y-m-d",strtotime($this->input->get('start_date'))); $cstartdate="AND STR_TO_DATE( creditors_balance.`created_date`,'%Y-%m-%d') < '$startdate' "; 
 
 
$prevDay=date("Y-m-d",strtotime($startdate.' -1 day'));
 $data['CashPrevDay']=$this->branchreceive_model->getCashOfDay($data['branch'],$prevDay); 

 $data['CashPrevDay']=$data['CashPrevDay'][0]->SameDay+$data['CashPrevDay'][0]->BackDay;
 $data['CashCreditorDataFromStartDay'] = $this->branchreceive_model->get_val("  SELECT DATE(creditors_balance.`created_date`)  as  `date`,  SUM(
    IF(
      DATE(
        creditors_balance.`created_date`
      ) = DATE(job_master.`date`),
      creditors_balance.`credit`,
      0
    )
  ) AS 'SameDay',
  SUM(
    IF(
      DATE(
        creditors_balance.`created_date`
      ) = DATE(job_master.`date`),
      0,
      creditors_balance.`credit`
    )
  ) AS 'BackDay'   
  
  
  FROM `creditors_balance`
LEFT JOIN job_master ON job_master.id=creditors_balance.`job_id`
WHERE job_master.`status`!=0 AND creditors_balance.`status`=1 AND `job_master`.`branch_fk`=$branch $cstartdate 
  ");
  
  $data['CashCreditorDataPrevDay'] = $this->branchreceive_model->get_val("  SELECT DATE(creditors_balance.`created_date`)  as  `date`,  SUM(
    IF(
      DATE(
        creditors_balance.`created_date`
      ) = DATE(job_master.`date`),
      creditors_balance.`credit`,
      0
    )
  ) AS 'SameDay',
  SUM(
    IF(
      DATE(
        creditors_balance.`created_date`
      ) = DATE(job_master.`date`),
      0,
      creditors_balance.`credit`
    )
  ) AS 'BackDay'   
  
  
  FROM `creditors_balance`
LEFT JOIN job_master ON job_master.id=creditors_balance.`job_id`
WHERE job_master.`status`!=0 AND creditors_balance.`status`=1 AND `job_master`.`branch_fk`=$branch AND STR_TO_DATE( creditors_balance.`created_date`,'%Y-%m-%d') = '$prevDay' 
  ");
  /* echo $this->db->last_query();  */
  $data['CashCreditorDataPrevDay']=$data['CashCreditorDataPrevDay'][0]->SameDay+$data['CashCreditorDataPrevDay'][0]->BackDay;
  
  
 $data['BankDepositFromStartDay'] = $this->branchreceive_model->get_val("  SELECT sum(branch_payment.amount) as amount FROM `branch_payment` WHERE branch_payment.`status`='1' AND branch_payment.`branch_fk`=$branch AND branch_payment.`date` <'$startdate' 
  ");
  
  $data['BankDepositFromStartDay']=($data['BankDepositFromStartDay'][0]->amount>0)?$data['BankDepositFromStartDay'][0]->amount:0;
  $data['BankDepositPrevDay'] = $this->branchreceive_model->get_val("  SELECT sum(branch_payment.amount) as amount FROM `branch_payment` WHERE branch_payment.`status`=1 AND branch_payment.`branch_fk`=$branch AND branch_payment.`date` ='$$prevDay' 
  ");
  
  
  $data['BankDepositPrevDay']=($data['BankDepositPrevDay'][0]->amount>0)?$data['BankDepositPrevDay'][0]->amount:0;
  
 
 
 $cashData=$this->branchreceive_model->getjobsto_branch($data['branch'],$start_date,$end_date); 
 
 $creditorData = $this->branchreceive_model->get_val("  SELECT DATE(creditors_balance.`created_date`)  as  `date`,  SUM(
    IF(
      DATE(
        creditors_balance.`created_date`
      ) = DATE(job_master.`date`),
      creditors_balance.`credit`,
      0
    )
  ) AS 'SameDay',
  SUM(
    IF(
      DATE(
        creditors_balance.`created_date`
      ) = DATE(job_master.`date`),
      0,
      creditors_balance.`credit`
    )
  ) AS 'BackDay'   
  
  
  FROM `creditors_balance`
LEFT JOIN job_master ON job_master.id=creditors_balance.`job_id`
WHERE job_master.`status`!=0 AND creditors_balance.`status`=1 AND `job_master`.`branch_fk`=2
GROUP BY DATE(creditors_balance.`created_date`) 
  ");
  
  $output=array();
  
   $startDateTemp=date("Y-m-d",strtotime($this->input->get('start_date')));
   $endDateTemp=date("Y-m-d",strtotime($this->input->get('end_date')));
  
   $dateTemp=$startDateTemp;
  while ($dateTemp<=$endDateTemp){
 
  $o=array("date"=>$dateTemp);
  $o["creditor_sameday"]=0;
  $o["creditor_backday"]=0;
  $o["SameDay"]=0;
  $o["BackDay"]=0;
   foreach($cashData as $d){
	if($d->date == $dateTemp){
	$o["SameDay"]=$d->SameDay;
			$o["BackDay"]=$d->BackDay;
	}
   }
  
	foreach($creditorData as $cd){
		if($cd->date == $dateTemp){
			$o["creditor_sameday"]=$cd->SameDay;
			$o["creditor_backday"]=$cd->BackDay;
		}
	
	
}
  $output[]=$o;
  $dateTemp=date("Y-m-d",strtotime($dateTemp. ' +1 day' ));
  
  }
  
}else{ $data['branch']=''; $output=array(); }
 $new_array = array();
 $query=$output;
 
         header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"ReceivePaymentReport.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w'); 
		fputcsv($handle, array("Srno","Date","Cash Collection Today","Prev Day collection","Deposit in bank","Same Day Difference","Cumulative Difference"));
			
							$tptal=0;
									   $count=0;
									 
									   $lastBankEntry=$data['CashCreditorDataPrevDay']+$data['CashPrevDay'];
									   $backCumulativeDiff=0;
$backCumulativeDiff=$data['CashCreditorDataFromStartDay'][0]->SameDay+$data['CashCreditorDataFromStartDay'][0]->BackDay+$data['CashFromStartDay'][0]->SameDay+$data['CashFromStartDay'][0]->BackDay-$lastBankEntry;
									   $CumulativeDiff=$backCumulativeDiff-$BankDepositFromStartDay;
										
										/* print_r( $backCumulativeDiff);
										print_r( $CumulativeDiff);
										print_r( $BankDepositFromStartDay); */
										$prevDate="";
										
    foreach($query as $row){
		$i++;
		$data=getjobspayamount($branch,"1",date("Y-m-d",strtotime($row["date"])));
		
		$date=date("d-m-Y",strtotime($row["date"]));
		 $TodayColle=round($row["SameDay"])+round($row["creditor_sameday"]);
			$Prevcolle=round($row["BackDay"])+round($row["creditor_backday"]);	
		$tptal=$lastBankEntry-round($data['banckamount']);
		  $CumulativeDiff+=$tptal; 
		 
		fputcsv($handle,array($i,$date,$TodayColle,$Prevcolle,round($data['banckamount']),$tptal,round($CumulativeDiff))); 
		
		$total=round($row["SameDay"])+round($row["creditor_sameday"])+round($row["BackDay"])+round($row["creditor_backday"]);
									   $lastBankEntry=$total;
	}
	fclose($handle);
    exit;
 
}
public function partylegent_brnachadd(){ 
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
		$data['success'] = $this->session->flashdata("success");
		$this->load->library('form_validation');
        $this->form_validation->set_rules('partyle','Party Ledger Account','trim|required|numeric');
		$this->form_validation->set_rules('branchname','Branch name','trim|required|numeric');
		
		$this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');
	if ($this->form_validation->run() != FALSE) {
		
            $data = array(
			   'lederid' =>$this->input->post('partyle'),
			   'branchid'=>$this->input->post('branchname'),
                "creted_date"=>date("Y-m-d H:i:s"),
                "credtedby" =>$data["login_data"]['id']
            );
			$leger=$this->input->post('partyle');

           $this->branchreceive_model->master_fun_insert("party_ledger_branch",$data);
		   $this->session->set_flashdata("success"," Party ledger Branch successfull added.");
		  
            redirect("branch_receive_payment/legent_branch/$leger","refresh");
			
           }else{
			   
		$data['ledgerlist']=$this->branchreceive_model->master_fun_get_tbl_val('party_ledger_account','id,party_name',array("status"=>'1'),array("party_name","ASC"));
		
		
		$this->load->view('header');
        $this->load->view('nav',$data);
        $this->load->view('party_ledgerbranch', $data);
        $this->load->view('footer');
			
		  }
    
	}
function getlegentbranch(){
	if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
		$legentid=$this->input->get('legent_id');
		if($legentid != ""){
			$brnachall=$this->branchreceive_model->legentbrach($legentid);
			$option="";
			foreach($brnachall as $bra){
				$id=$bra->id;
				$branch_name=$bra->branch_name;
					$option .="<option value='$id'>$branch_name</option>";
				
			}
			echo  $option;
			
		}
		
	}
public function legent_list(){
        if (!is_loggedin()) {
            redirect('login');
        }
$data["login_data"] = logindata();
	     
	$data["query"] = $this->branchreceive_model->legentlist();
	/* echo "<pre>"; print_r($data["query"]); die(); */
			
		$this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('legent_branch_list', $data);
        $this->load->view('footer');
			
}
public function party_add(){ 
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
		$data['success'] = $this->session->flashdata("success");
		$this->load->library('form_validation');
        $this->form_validation->set_rules('partyname','Party name','trim|required');
		$this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');
	if ($this->form_validation->run() != FALSE) {
		
            $data = array(
			   'party_name'=>ucwords($this->input->post('partyname')),
                "created_date"=>date("Y-m-d H:i:s"),
                "created_by" =>$data["login_data"]['id']
            );
			$this->branchreceive_model->master_fun_insert("party_ledger_account",$data);
		   $this->session->set_flashdata("success"," Party name successfull added.");
			redirect("branch_receive_payment/legent_list","refresh");
			
           }else{
		
		$this->load->view('header');
        $this->load->view('nav',$data);
        $this->load->view('party_ledgeradd_views', $data);
        $this->load->view('footer');
			
		  }
    
	}
public function party_edit($id){ 
        if (!is_loggedin()) {
            redirect('login');
        }
	if($id != ""){
		
        $data["login_data"] = logindata();
		$data['success'] = $this->session->flashdata("success");
		$this->load->library('form_validation');
        $this->form_validation->set_rules('partyname','Party name','trim|required');
		$this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');
	if ($this->form_validation->run() != FALSE) {
		
            $data = array(
			   'party_name'=>ucwords($this->input->post('partyname')),
                "updated_date"=>date("Y-m-d H:i:s"),
                "updated_by" =>$data["login_data"]['id']
            );
			
			$this->branchreceive_model->updateRowWhere('party_ledger_account', array("id"=>$id),$data);
			
		    $this->session->set_flashdata("success"," Party name successfull updated.");
			redirect("branch_receive_payment/legent_list","refresh");
			
           }else{
			   
		$data["paryinfo"]=$this->branchreceive_model->fetchdatarow('id,party_name','party_ledger_account',array("id"=>$id,"status"=>'1'));
		if($data["paryinfo"] != ""){
			
		$this->load->view('header');
        $this->load->view('nav',$data);
        $this->load->view('party_ledgeredit_views', $data);
        $this->load->view('footer');
		}
			
		  }
	}else{ show_404(); }
    
}
public function party_delete($id){ 
        if (!is_loggedin()) {
            redirect('login');
        }
	if($id != ""){
		
        $data["login_data"] = logindata();
		    $data = array(
			   'status'=>'0',
                "updated_date"=>date("Y-m-d H:i:s"),
                "updated_by" =>$data["login_data"]['id']
            );
			$this->branchreceive_model->updateRowWhere('party_ledger_account', array("id"=>$id),$data);
			$this->session->set_flashdata("success"," Party name successfull updated.");
			redirect("branch_receive_payment/legent_list","refresh");
			
	}else{ show_404(); }
    
	}	
public function legent_branch($id){
        if (!is_loggedin()) {
            redirect('login');
        }
$data["login_data"] = logindata();

if($id != ""){
	     $data['legentid']=$id;
	$data["query"] = $this->branchreceive_model->legentbrachall($id);
	/*  echo "<pre>"; print_r($data["query"]); die();  */
			
		$this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('legent_allbranch_list', $data);
		
        $this->load->view('footer');
}else{ show_404(); }	
			
}	

public function lbracnh_delete($id){
	 if (!is_loggedin()) {
            redirect('login');
        }
$data["login_data"] = logindata();
$id=$this->uri->segment(4);
$legentid=$this->uri->segment(3);
if($legentid != "" && $id != ""){
	
	 $this->branchreceive_model->updateRowWhere('party_ledger_branch',array("id" =>$id,"lederid"=>$legentid),array("status" =>'0'));
	 $this->session->set_flashdata("success","Party Ledger Branch successfull deleted.");
     redirect("branch_receive_payment/legent_branch/$legentid","refresh");
			
}else{ show_404(); }
	
}

}

?>