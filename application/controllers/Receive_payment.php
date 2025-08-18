<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Receive_payment extends CI_Controller {

    function __construct() {
        parent::__construct();
         $this->load->model('recivepayment_modal');
         $data["login_data"] = logindata();
    }
public function add() {
	   if (!is_loggedin()) {
            redirect('login');
        }
		
		$data["login_data"] = logindata();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('labname', 'Lab Name', 'trim|required');
        $this->form_validation->set_rules('paydate', 'Date', 'trim|required');
		$this->form_validation->set_rules('type', 'type', 'trim|required');
		$this->form_validation->set_rules('amount','Amount','trim|required|numeric');
		$this->form_validation->set_rules('note','note','trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">','</span>');

        if ($this->form_validation->run() != FALSE) {

        $paydate = $this->input->post("paydate");
		$type = $this->input->post("type");
        $amount = $this->input->post("amount");
        $note = $this->input->post("note");
		$lab_fk= $this->input->post("labname");
			$monthset=date('m',strtotime($paydate));
			$year=date('Y',strtotime($paydate));
            
			      $c_data = array(
                    "lab_fk" => $lab_fk,
                    "month" =>$monthset,
                    "year" => $year,
                    "amount" =>  $amount,
                    "note" => $note,
					"type" => $type,
					"pay_date"=>date("Y-m-d",strtotime($paydate)),
					"created_by" => $data["login_data"]["id"],
					"created_date" => date("Y-m-d H:i:s")
                );
				
            $customer = $this->recivepayment_modal->master_fun_insert("sample_receive_payment", $c_data);
            $this->session->set_flashdata("success","Recive payment successfully added.");
			redirect("Receive_payment/receive_payment_list");
        } else {
			
			$logintype=$data["login_data"]['type'];
			if ($logintype == 5 || $logintype == 6 || $logintype == 7 ) {
				
				$data['lablist'] = $this->recivepayment_modal->get_val("SELECT c.`id`,c.`name` FROM b2b_labgroup g LEFT JOIN collect_from c ON c.id=g.labid AND c.`status`='1' WHERE g.status='1' AND g.branch_fk IN (SELECT user_branch.`branch_fk` FROM `user_branch` WHERE user_branch.`user_fk`=".$data["login_data"]['id']." AND user_branch.`status`=1) ORDER BY c.`name` ASC");
				
		 }else{
			 
			 $data['lablist']=$this->recivepayment_modal->master_fun_get_tbl_val('collect_from','id,name',array("status"=>'1'),array("name","ASC"));
			
		   }
		   
		$this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('receive_payment_add', $data);
        $this->load->view('footer');
   	
		}
	
    }
public function receive_payment_list(){
        if (!is_loggedin()) {
            redirect('login');
        }
  $data["login_data"] = logindata();
  $laballsea=$this->input->get('lab');
  $startdate=$this->input->get('startdate');
  $enddate=$this->input->get('enddate');
$this->load->library('pagination');
$logintype=$data["login_data"]['type'];
if($logintype == 5 || $logintype == 6 || $logintype == 7 ) {
	
$lablisass=$this->recivepayment_modal->get_val("SELECT GROUP_CONCAT(DISTINCT c.`id` ) as id FROM b2b_labgroup g LEFT JOIN collect_from c ON c.id=g.labid AND c.`status`='1' WHERE g.status='1' AND g.branch_fk IN (SELECT user_branch.`branch_fk` FROM `user_branch` WHERE user_branch.`user_fk`=".$data["login_data"]['id']." AND user_branch.`status`=1) ORDER BY c.`name` ASC");
				
	if($lablisass[0]->id != ""){ 
	/* $data["query"]=$this->recivepayment_modal->payments_list($lablisass[0]->id); */ 
	$data["lablist"]=$this->recivepayment_modal->get_val("SELECT id,name FROM `collect_from` WHERE status='1' AND id IN(".$lablisass[0]->id.") ORDER BY name ASC");
	$laball=$lablisass[0]->id;
	}else{ 
	/* $data["query"] = array(); */
	$data["lablist"]=array();
	$laball='0';
	}
				
		 }else{
			 $laball='';
		 $data["lablist"]=$this->recivepayment_modal->get_val("SELECT id,name FROM `collect_from` WHERE status='1' ORDER BY name ASC");
/* $data["query"] = $this->recivepayment_modal->payments_list(); */
		 
		 }
		  $laburl=$laballsea[0];
		    $this->load->library('pagination');
			$total_row = $this->recivepayment_modal->payments_listnum($laballsea,$startdate,$enddate,$laball);
            $config["base_url"] = base_url() . "receive_payment/receive_payment_list?receive_payment_list?lab[]=$laburl&startdate=&enddate=";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->recivepayment_modal->payments_list($config["per_page"],$page,$laballsea,$startdate,$enddate,$laball);
            $data["links"] = $this->pagination->create_links();
			$data["page"] = $page;
			
		$this->load->view('header');
        $this->load->view('nav',$data);
        $this->load->view('receive_payment_listviews', $data);
        $this->load->view('footer');
			
}
public function payment_exportcsv(){
        if (!is_loggedin()) {
            redirect('login');
        }
  $data["login_data"] = logindata();
  $laballsea=$this->input->get('lab');
  $startdate=$this->input->get('startdate');
  $enddate=$this->input->get('enddate');
$this->load->library('pagination');
$logintype=$data["login_data"]['type'];
if($logintype == 5 || $logintype == 6 || $logintype == 7 ) {
	
$lablisass=$this->recivepayment_modal->get_val("SELECT GROUP_CONCAT(DISTINCT c.`id` ) as id FROM b2b_labgroup g LEFT JOIN collect_from c ON c.id=g.labid AND c.`status`='1' WHERE g.status='1' AND g.branch_fk IN (SELECT user_branch.`branch_fk` FROM `user_branch` WHERE user_branch.`user_fk`=".$data["login_data"]['id']." AND user_branch.`status`=1) ORDER BY c.`name` ASC");
				
	if($lablisass[0]->id != ""){ 
	$laball=$lablisass[0]->id;
	}else{ 
	/* $data["query"] = array(); */
	$data["lablist"]=array();
	$laball='0';
	}
				
		 }else{
			 $laball='';
		 }
		 $page =0;
  $query=$this->recivepayment_modal->payments_list('10000000',$page,$laballsea,$startdate,$enddate,$laball);
           
	        header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"Receivepayment_report.csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle = fopen('php://output', 'w');
            fputcsv($handle, array("Lab Name", "Credit", "Type", "Date", "Note", "Added By"));
			 foreach ($query as $row) {
				$month=$row->pay_date;
				fputcsv($handle, array(ucwords($row->labname),round($row->amount),$row->type,date("d-m-Y", strtotime($month)),$row->note,ucwords($row->name)));
			}
			fclose($handle);
        exit;
			
}
	function print_receipt($id=null){
        if (!is_loggedin()) {
            redirect('login');
        }
		
        $data["login_data"] = logindata();
         
        if($id != ""){
			
		$reciptydetils=$this->amount_model->fetchdatarow('id,lab_fk,month,year,amount,note,type,created_date','sample_receive_payment',array("id"=>$id,"status"=>'1'));
		if($reciptydetils->id != ""){
		$labid=$reciptydetils->lab_fk;
		$data['date2']=$reciptydetils->created_date;
		$data['query']=$this->amount_model->fetchdatarow('id,name,address','collect_from',array("id"=>$labid,"status"=>'1'));
		
		
		$data["payrecipt"]=$reciptydetils;
		$pdfFilePath=FCPATH."/upload/b2binvoice/payment_receipt.pdf";	
		$html = $this->load->view('b2b/payment_reciptpdf_view',$data,true);
		
		$this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;
        $pdf->autoLangToFont = true;
        
				
				$pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 0, // margin_left
                0, // margin right
                5, // margin top
                5, // margin bottom
                5, // margin header
                5);
		$pdf->SetHTMLFooter('<table class="tbl_full" style="margin-bottom:20px; text-align:right;">
					<tr>
						<td><b>AIRRMED PATHOLOGY PVT.LTD.</b></td>
					</tr>
				</table>

<div style="height:20px;"><p class="rslt_p_brdr"></p>
</div>');		
                if($_REQUEST["debug"]==1){
                    echo $html; die();
                }
        $pdf->WriteHTML($html);
		$pdf->debug = true;
        $pdf->allow_output_buffering = TRUE;
		if (file_exists($pdfFilePath) == true) {
            $this->load->helper('file');
            unlink($path);
        }
        $pdf->Output($pdfFilePath, 'F');
	
	$base_url=base_url()."upload/b2binvoice/payment_receipt.pdf";
$filename=FCPATH."/upload/b2binvoice/payment_receipt.pdf";	
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
		}else{ show_404(); }
		
} else {
			show_404();
        }
    }
public function lab_dueamount(){
        if (!is_loggedin()) {
            redirect('login');
        }
$data["login_data"] = logindata();
$this->load->library('form_validation');
$this->form_validation->set_rules('lab_id', 'lab_id', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
			$lab_id = $this->input->post("lab_id");
			$paydate = $this->input->post("paydate");
			if($paydate == ""){ $paydate1=date("Y-m-d");  }else{ $paydate1=date("Y-m-d",strtotime($paydate)); }
			$allamount=$this->recivepayment_modal->getlab_amount($lab_id,$paydate1);
			$reciveamount=$this->recivepayment_modal->getlab_reciveamount($lab_id,$paydate1);
			if($allamount->amount != ""){ $totaldueamount=$allamount->amount;  }else{ $totaldueamount="0"; }
			if($reciveamount->recive != ""){ $treciveamount=$reciveamount->recive;  }else{ $treciveamount="0"; }
			
			echo $dueamount=($totaldueamount-$treciveamount);
			
		}else{ echo ""; }

}
public function jobreceivepayment_list(){
        if (!is_loggedin()) {
            redirect('login');
        }
  $data["login_data"] = logindata();
  $laballsea=$this->input->get('lab');
  $startdate=$this->input->get('startdate');
  $enddate=$this->input->get('enddate');
$this->load->library('pagination');
$logintype=$data["login_data"]['type'];

		$data["lablist"]=$this->recivepayment_modal->get_val("SELECT id,name FROM `collect_from` WHERE status='1' ORDER BY name ASC");
		
$query="";
if($laballsea != ""){ $query .="AND c.id='$laballsea'"; }

if($startdate != ""){ $sdate=date("Y-m-d",strtotime($startdate)); $query .="AND DATE_FORMAT(p.createddate,'%Y-%m-%d') >='$sdate'"; }
if($enddate != ""){ $edate=date("Y-m-d",strtotime($enddate)); $query .="AND DATE_FORMAT(p.createddate,'%Y-%m-%d') <='$edate'";  }

		
        $data['query'] = $this->recivepayment_modal->get_val("SELECT p.`createddate`,p.`id`,p.`type`,p.`amount`,a.`name` AS addedby,j.order_id,j.barcode_fk as jobid,j.customer_name,l.`collect_from`,c.`name` FROM sample_job_master_receiv_amount p LEFT JOIN `admin_master` a ON a.id=p.`added_by` LEFT JOIN sample_job_master j ON j.id=p.`job_fk` LEFT JOIN `logistic_log` l ON l.`id`=j.`barcode_fk` LEFT JOIN `collect_from` c ON c.id=l.`collect_from`   WHERE p.status='1' $query ORDER BY p.id DESC");
          
		  
		$this->load->view('header');
        $this->load->view('nav',$data);
        $this->load->view('receive_jobpayment_listviews',$data);
        $this->load->view('footer');
			
}
public function b2bcollection_report(){
        if (!is_loggedin()) {
            redirect('login');
        }
  $data["login_data"] = logindata();
  $logintype=$data["login_data"]['type'];

  
 
  $startdate=$this->input->get('startdate');
  $enddate=$this->input->get('enddate');
  $tcity=$this->input->get('city');
  $plm=$this->input->get('plm');
  $branch=$this->input->get('branch');
  $setbranch=implode(",",$branch);
  

		
$query="";

if($setbranch != "" && $setbranch != null){  $query .="AND (c.id IN(SELECT labid FROM b2b_labgroup WHERE STATUS = '1' AND `branch_fk` IN ($setbranch) ) )"; }

if($setbranch=="" && $plm != ""){ $query .="AND c.id IN (SELECT labid FROM b2b_labgroup WHERE STATUS = '1' AND `branch_fk` IN (SELECT id FROM branch_master WHERE STATUS = '1' AND parent_fk = '$plm') OR `branch_fk`='$plm')"; }

/* if($laballsea != ""){ $query .="AND c.id='$laballsea'"; } */

if($startdate != ""){ $sdate=date("Y-m-d",strtotime($startdate)); }else{ $sdate=date("Y-m-d"); }
$data["startdate"]=date("d-m-Y",strtotime($sdate));
$query .="AND DATE_FORMAT(p.createddate,'%Y-%m-%d') >='$sdate'";

if($enddate != ""){ $edate=date("Y-m-d",strtotime($enddate));  }else{ $edate=date("Y-m-d"); }
$data["enddate"]=date("d-m-Y",strtotime($edate));
$query .="AND DATE_FORMAT(p.createddate,'%Y-%m-%d') <='$edate'";

if($tcity != ""){ $query .="AND b.`city`='$tcity'"; }



        $data['query'] = $this->recivepayment_modal->get_val("SELECT p.`createddate`,p.`id`,p.type,p.`amount`,a.`name` AS addedby,j.order_id,j.barcode_fk as jobid,j.customer_name,l.`collect_from`,c.`name`,b.branch_name,plm.`branch_name` AS parentbranch,ci.`name` AS cityname FROM sample_job_master_receiv_amount p LEFT JOIN `admin_master` a ON a.id=p.`added_by` LEFT JOIN sample_job_master j ON j.id=p.`job_fk` LEFT JOIN `logistic_log` l ON l.`id`=j.`barcode_fk` LEFT JOIN `collect_from` c ON c.id=l.`collect_from` left join b2b_labgroup g on g.labid=c.id AND g.`status`='1' left join branch_master b on b.id=g.branch_fk LEFT JOIN branch_master plm ON plm.`id`=b.`parent_fk` LEFT JOIN test_cities ci ON ci.`id`=b.`city` WHERE p.status='1' $query GROUP BY p.id ORDER BY p.id DESC");
		
		
		    /* echo $this->db->last_query();
		die();   */
		
		$data['test_cities'] = $this->recivepayment_modal->get_val("SELECT id,name from test_cities where status='1'");
		
		$this->load->view('header');
        $this->load->view('nav',$data);
        $this->load->view('receive_jobpayment_listviews',$data);
        $this->load->view('footer');
			
}
function get_plm() {
        $data["login_data"] = logindata();
        $data["cntr_arry"] = array();
       

        $city = $this->input->post("city");
		$selected1=$this->input->post("setplm");
        $refer = '<option value="">--All PLM--</option>';
        if (!empty($city)) {
            $referral_list = $this->recivepayment_modal->get_val("SELECT *
FROM `branch_master`
WHERE `status` = 1
AND `city` = '" . $city . "'
AND (`parent_fk` IS NULL OR `parent_fk` ='')
ORDER BY `branch_name` ASC");
        } else {
            $referral_list = array();
        }
        foreach ($referral_list as $referral) {
           
                $refer .= '<option value="' . $referral->id . '"';
                if ($selected1 == $referral->id) {
                    $refer .= ' selected';
                }
                $refer .= '>' . ucwords($referral->branch_name) . '</option>';
            
        }
        if ($refer == '') {
            //echo "<option value=''>Data not available.</option>";
        }
        echo $refer;
    }
public function get_branch() {
        $data["login_data"] = logindata();
        
        $plm = $this->input->post("plm");
		$branchname = $this->input->post("branchname");
		
		$setbbrach =explode(",",$this->input->post("setbbrach"));
		
        $refer = '';
        if (!empty($plm)) {
            $referral_list = $this->recivepayment_modal->master_fun_get_tbl_val("branch_master","id,branch_name",array('status' => 1, "parent_fk" => $plm),array("branch_name", "asc"));

        } else {
			
            $referral_list = array();
        }
        foreach ($referral_list as $referral) {
           
                    $refer .= '<option value="' . $referral->id . '"';
					if (in_array($referral->id,$setbbrach)){
                    $refer .= ' selected';
                }
                    $refer .= '>' . ucwords($referral->branch_name) . '</option>';
                
        }
		if($plm != "" && $setbbrach != ""){
		$refer .= '<option value="' . $plm . '"';
					if (in_array($plm,$setbbrach)){
                    $refer .= ' selected';
                }
                    $refer .= '>' . ucwords($branchname). '</option>';
		}
		
        if ($refer == '') {
           /* echo "<option value=''>Data not available.</option>";*/
        }
        echo $refer;
    }
public function collectionreport_csv(){
        if (!is_loggedin()) {
            redirect('login');
        }
  $data["login_data"] = logindata();
  $logintype=$data["login_data"]['type'];

  
   $startdate=$this->input->get('startdate');
  $enddate=$this->input->get('enddate');
  $tcity=$this->input->get('city');
  $plm=$this->input->get('plm');
  $branch=$this->input->get('branch');
  $setbranch=implode(",",$branch);
  

		
$query="";

if($setbranch != "" && $setbranch != null){  $query .="AND (c.id IN(SELECT labid FROM b2b_labgroup WHERE STATUS = '1' AND `branch_fk` IN ($setbranch) ) )"; }

if($setbranch=="" && $plm != ""){ $query .="AND c.id IN (SELECT labid FROM b2b_labgroup WHERE STATUS = '1' AND `branch_fk` IN (SELECT id FROM branch_master WHERE STATUS = '1' AND parent_fk = '$plm') OR `branch_fk`='$plm')"; }

/* if($laballsea != ""){ $query .="AND c.id='$laballsea'"; } */

if($startdate != ""){ $sdate=date("Y-m-d",strtotime($startdate)); }else{ $sdate=date("Y-m-d"); }
$data["startdate"]=date("d-m-Y",strtotime($sdate));
$query .="AND DATE_FORMAT(p.createddate,'%Y-%m-%d') >='$sdate'";

if($enddate != ""){ $edate=date("Y-m-d",strtotime($enddate));  }else{ $edate=date("Y-m-d"); }
$data["enddate"]=date("d-m-Y",strtotime($edate));
$query .="AND DATE_FORMAT(p.createddate,'%Y-%m-%d') <='$edate'";

if($tcity != ""){ $query .="AND b.`city`='$tcity'"; }



        $data['query'] = $this->recivepayment_modal->get_val("SELECT p.`createddate`,p.`id`,p.type,p.`amount`,a.`name` AS addedby,j.order_id,j.barcode_fk as jobid,j.customer_name,l.`collect_from`,c.`name`,b.branch_name,plm.`branch_name` AS parentbranch,ci.`name` AS cityname FROM sample_job_master_receiv_amount p LEFT JOIN `admin_master` a ON a.id=p.`added_by` LEFT JOIN sample_job_master j ON j.id=p.`job_fk` LEFT JOIN `logistic_log` l ON l.`id`=j.`barcode_fk` LEFT JOIN `collect_from` c ON c.id=l.`collect_from` left join b2b_labgroup g on g.labid=c.id AND g.`status`='1' left join branch_master b on b.id=g.branch_fk LEFT JOIN branch_master plm ON plm.`id`=b.`parent_fk` LEFT JOIN test_cities ci ON ci.`id`=b.`city` WHERE p.status='1' $query GROUP BY p.id ORDER BY p.id DESC");
		
		header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"b2bcollection_report.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No","Lab name","Plm name", "City name", "Order id", "Branch name", "Collected amount", "Type", "Collected date", "Collected by"));
        $i = 0;
		
		foreach($data['query'] as $row){
			
			$i++;
			if($row->parentbranch != ""){ $pabranch=ucwords($row->parentbranch); }else{ $pabranch=ucwords($row->branch_name); }
            fputcsv($handle, array($i,$row->name,$pabranch,$row->cityname,$row->order_id,ucwords($row->branch_name),$row->amount,$row->type,date("d-m-Y",strtotime($row->createddate)),$row->addedby));
		}
          
		 fclose($handle);
        exit;
			
}

}
