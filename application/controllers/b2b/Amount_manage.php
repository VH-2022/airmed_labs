<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Amount_manage extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('b2b/amount_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $data["login_data"] = logindata();
        $this->load->helper('string');
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function lab_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->amount_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $state_serch = $this->input->get('state_search');
        $data["email"] = $this->input->get('email');
        $data['state_search'] = $state_serch;
        if ($state_serch != "" || $data["email"] != "") {
            $total_row = $this->amount_model->lab_num_rows($state_serch, $data["email"]);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "b2b/Amount_manage/lab_list?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 100;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->amount_model->lab_data($state_serch, $data["email"], $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $total_row = $this->amount_model->lab_num_rows();
            $config["base_url"] = base_url() . "b2b/Amount_manage/lab_list";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 100;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->amount_model->srch_lab_list($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }
        $data["page"] = $page;
        /* $this->load->view('b2b/header');
        $this->load->view('b2b/nav_logistic', $data); */
		$this->load->view('header');
$this->load->view('nav', $data);
        $this->load->view('b2b/amount_lab_list', $data);
        $this->load->view('b2b/footer');
    }

    function details() {
        if (!is_loggedin()) {
            redirect('login');
        }
		
        $data["login_data"] = logindata();
        $data["id"] = $this->uri->segment(4);
		$lab_fk=$data["id"];
        if (!empty($data["id"])) {
            $data["lab_details"] = $this->amount_model->master_fun_get_tbl_val("collect_from", array('status' => "1", "id" => $data["id"]), array("id", "desc"));
			
            $data["sample_credit"] = $this->amount_model->master_fun_get_tbl_val("sample_receive_payment", array('status' => "1", "lab_fk" => $data["id"]), array("id", "desc"));
			
			$data["dueamountmonth"]= $this->amount_model->get_val("SELECT  DATE_FORMAT(logistic_log.`createddate`,'%d-%M-%Y' ) AS months,SUM(sample_job_master.`payable_amount`) AS amount FROM `sample_job_master` LEFT JOIN `logistic_log` ON logistic_log.`id`= `sample_job_master`.`barcode_fk` WHERE logistic_log.`status`=1 AND `logistic_log`.`collect_from`='$lab_fk' GROUP BY (DATE_FORMAT(logistic_log.`createddate`,'%m-%Y' ))ORDER BY ( DATE_FORMAT(logistic_log.`createddate`,'%m-%Y')) DESC");
            
            $this->load->view('header');
$this->load->view('nav', $data);
            $this->load->view('b2b/lab_amount_details', $data);
            $this->load->view('b2b/footer');
			
        } else {
            redirec("b2b/Amount_manage/lab_list");
        }
    
	}

    function add_payment() {
        $lab_fk = $this->uri->segment(4);
        $type = $this->input->post("type");
        $amount = $this->input->post("amount");
        $note = $this->input->post("note");
        if (!empty($type) && !empty($amount) && !empty($lab_fk)) {
            $lab_data = $this->amount_model->get_val("SELECT * FROM sample_credit WHERE STATUS='1' AND lab_fk='" . $lab_fk . "' ORDER BY id DESC LIMIT 0,1");
            if (!empty($lab_data)) {
                $old_total = $lab_data[0]["total"];
            } else {
                $old_total = 0;
            }
            if ($type == 'credit') {
                $c_data = array(
                    "lab_fk" => $lab_fk,
                    "credit" => $amount,
                    "debit" => 0,
                    "total" => $old_total + $amount,
                    "transaction" => "Credited",
                    "note" => $note,
                    "created_date" => date("Y-m-d H:i:s")
                );
            } else {
                $c_data = array(
                    "lab_fk" => $lab_fk,
                    "credit" => 0,
                    "debit" => $amount,
                    "total" => $old_total - $amount,
                    "transaction" => "Debited",
                    "note" => $note,
                    "created_date" => date("Y-m-d H:i:s")
                );
            }
            $customer = $this->amount_model->master_fun_insert("sample_credit", $c_data);
            $this->session->set_flashdata("success", array("Amount successfully added."));
            redirect("b2b/Amount_manage/details/" . $lab_fk);
        } else {
            redirec("b2b/Amount_manage/lab_list");
        }
    }
  function receive_payment() {
	  $data["login_data"] = logindata();

	  $lab_fk = $this->uri->segment(4);
	  if($lab_fk != ""){
		  
		$this->load->library('form_validation');
        $this->form_validation->set_rules('month', 'month', 'trim|required');
		$this->form_validation->set_rules('type', 'type', 'trim|required');
		$this->form_validation->set_rules('amount', 'amount', 'trim|required|numeric');
		$this->form_validation->set_rules('note', 'note', 'trim');

        if ($this->form_validation->run() != FALSE) {

        $month = $this->input->post("month");
		$type = $this->input->post("type");
        $amount = $this->input->post("amount");
        $note = $this->input->post("note");
		
			$monthset=date('m',strtotime($month));
			$year=date('Y',strtotime($month));
            
			      $c_data = array(
                    "lab_fk" => $lab_fk,
                    "month" =>$monthset,
                    "year" => $year,
                    "amount" =>  $amount,
                    "note" => $note,
					"type" => $type,
					"created_by" => $data["login_data"]["id"],
					"created_date" => date("Y-m-d H:i:s")
                );
				
            $customer = $this->amount_model->master_fun_insert("sample_receive_payment", $c_data);
            $this->session->set_flashdata("success","Amount successfully added.");
            redirect("b2b/Amount_manage/details/".$lab_fk);
        } else {
       show_404();
        }
	  }else{ show_404(); }
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
function receipt_sendmail($id=null){
        if (!is_loggedin()) {
            redirect('login');
        }
		
        $data["login_data"] = logindata();
         
        if($id != ""){
			
		$reciptydetils=$this->amount_model->fetchdatarow('id,lab_fk,month,year,amount,note,type,created_date','sample_receive_payment',array("id"=>$id,"status"=>'1'));
		if($reciptydetils->id != ""){
		$labid=$reciptydetils->lab_fk;
		$data['date2']=$reciptydetils->created_date;
		$data['query']=$this->amount_model->fetchdatarow('id,name,address,email','collect_from',array("id"=>$labid,"status"=>'1'));
		
		
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
		$filename=FCPATH."/upload/b2binvoice/payment_receipt.pdf";
		/* $message = '<div style="padding:0 4%;">
                    <h4><b>Hello </b>' . ucwords($data['query']->name).'</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Thank you for your payment!</p>
 <p style="color:#7e7e7e;font-size:13px;"></p><p style="color:#7e7e7e;font-size:13px;"></p></div>'; */ 
 $message1='<div style="Margin-left: 20px;Margin-right: 20px;">
      <h1 class="size-24" style="Margin-top: 0;Margin-bottom: 0;font-style: normal;font-weight: normal;font-size: 20px;line-height: 28px;text-align: left;" lang="x-size-24">Hello&nbsp;'.ucwords($data['query']->name).'</h1><p class="size-17" style="Margin-top: 20px;Margin-bottom: 20px;font-size: 17px;line-height: 26px;" lang="x-size-17">Thank you for your payment!</p></div>';
	  
				$semail=$data['query']->email;
				$this->load->helper("Email");
				$email_cnt = new Email;
				$config['mailtype']='html';
				$this->email->initialize($config);
				
				/* $message1=$email_cnt->get_design($message1); */
                
				$this->email->clear(TRUE);
                $this->email->to($semail);
				$this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
                $this->email->subject("Your Payment Receipt");
                $this->email->message($message1);
                $this->email->attach($filename);
                $this->email->send();
				echo "1";

		}else{ show_404(); }
		
} else {
			show_404();
        }
    }
}
