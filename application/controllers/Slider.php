<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Slider extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('slider_model');
        $this->load->model('user_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		
		  $this->load->model('master_model');
  
        $this->load->model('user_test_master_model');
        $this->load->model('job_model');
        $this->load->model('test_model');
      
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->model('registration_admin_model');
		        $data["login_data"] = logindata();
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function index() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $userid = $data["login_data"]["id"];

        if ($this->session->userdata('success') != null) {
            $data['success'] = $this->session->userdata("success");
            $this->session->unset_userdata('success');
        }
        if ($this->session->userdata('unsuccess') != null) {
            $data['unsuccess'] = $this->session->userdata("unsuccess");
            $this->session->unset_userdata('unsuccess');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('group', 'Banner Group', 'trim|required');
        //$this->form_validation->set_rules('sliderfile', 'Banner', 'trim|required');


        if ($this->form_validation->run() != FALSE) {

            $group = $this->input->post('group');


            $config['upload_path'] = './upload/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_name'] = time() . $_FILES["sliderfile"]["name"];
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload("sliderfile")) {
                $error = array('error' => $this->upload->display_errors());

                $this->load->view('header');
                $this->load->view('nav', $data);
                $this->load->view('slider_add', $error);
                $this->load->view('footer');
            } else {
                $data = array('upload_data' => $this->upload->data());
                $file_name = $data["upload_data"]["file_name"];
                if (isset($file_name)) {
                    $time = $this->slider_model->get_server_time();
                    $data1 = array(
                        "group" => $group,
                        "pic" => $file_name,
                    );

                    $insert = $this->slider_model->master_fun_insert('banner_master', $data1);
                    $ses = array("Slider Successfully Inserted!");
                    $this->session->set_userdata('success', $ses);
                    redirect('slider/slider_list');
                } else {
                    $ses = array("please select valid image!");
                    $this->session->set_userdata('unsuccess', $ses);
                    redirect('slider/index');
                }
            }
        } else {
            $data['query'] = $this->slider_model->master_fun_get_tbl_val("banner_group", array("status" => 1), array("id", "desc"));

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('slider_add');
            $this->load->view('footer');
        }
    }

    function slider_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $userid = $data["login_data"]["id"];
        if ($this->session->userdata('success') != null) {
            $data['success'] = $this->session->userdata("success");
            $this->session->unset_userdata('success');
        }
        if ($this->session->userdata('unsuccess') != null) {
            $data['unsuccess'] = $this->session->userdata("unsuccess");
            $this->session->unset_userdata('unsuccess');
        }

        $data["query"] = $this->slider_model->get_active_record();
        $totalRows = count($data["query"]);
        $config = array();
        $config["base_url"] = base_url() . "slider/slider_list/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["query"] = $this->slider_model->get_active_record1($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();


        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('slider_view', $data);
        $this->load->view('footer');
    }

    function slider_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $id = $data["id"] = $this->uri->segment('3');
        $group = $this->input->post('group');
        if ($this->session->userdata('unsuccess') != null) {
            $data['unsuccess'] = $this->session->userdata("unsuccess");
            $this->session->unset_userdata('unsuccess');
        }
        $this->form_validation->set_rules('id', 'Id', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['query'] = $this->slider_model->master_fun_get_tbl_val("banner_master", array("id" => $this->uri->segment('3')), array("id", "desc"));
            $data['group'] = $this->slider_model->master_fun_get_tbl_val("banner_group", array("status" => 1), array("id", "desc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('slider_edit', $data);
            $this->load->view('footer');
        } else {

            if ($_FILES["sliderfile"]["name"] != NULL) {
                //echo "hello"; die();
                $config['upload_path'] = './upload/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = time() . $_FILES["sliderfile"]["name"];
                $this->load->library('upload', $config);
                if ($this->upload->do_upload("sliderfile")) {
                    $data = array('upload_data' => $this->upload->data());
                    $file_name = $data["upload_data"]["file_name"];
                    if (isset($file_name)) {
                        $data1 = array(
                            "pic" => $file_name,
                            "group" => $group,
                        );

                        $update = $this->slider_model->master_fun_update("banner_master", $this->uri->segment('3'), $data1);
                        $ses = array("Slider Successfully Updated!");
                        $this->session->set_userdata('success', $ses);
                        redirect('slider/slider_list');
                    }
                }
            } else {

                $ses = array("Slider Successfully Updated!");
                $this->session->set_userdata('success', $ses);
                redirect('slider/slider_list');
            }
        }
    }

    function slider_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $userid = $data["login_data"]["id"];

        $cid = $this->uri->segment('3');
        $data = array(
            "status" => '0'
        );
        //$delete=$this->admin_model->delete($cid,$data);
        $delete = $this->slider_model->master_fun_update("banner_master", $this->uri->segment('3'), $data);
        if ($delete) {
            $ses = array("Slider Successfully Deleted!");
            $this->session->set_userdata('success', $ses);
            redirect('slider/slider_list', 'refresh');
        }
    }

    function export_csv() {
       $search_data = array();
        $user = $data['user2'] = $search_data["user"] = $this->input->get('user');
        $date = $data['date2'] = $search_data["date"] = $this->input->get('date');
        $end_date = $data['end_date'] = $search_data["end_date"] = $this->input->get("end_date");
        $p_oid = $data['p_oid'] = $search_data["p_oid"] = $this->input->get('p_oid');
        $p_ref = $data['p_ref'] = $search_data["p_ref"] = $this->input->get('p_ref');
        $mobile = $data['mobile'] = $search_data["mobile"] = $this->input->get('mobile');
        $referral_by = $data['referral_by'] = $search_data["referral_by"] = $this->input->get('referral_by');
        $status = $data['statusid'] = $search_data["status"] = $this->input->get('status');
        $branch = $data['branch'] = $search_data["branch"] = $data["branch"] = $this->input->get('branch');
        $payment = $data['payment2'] = $search_data["payment"] = $data["payment"] = $this->input->get('payment');
        $test_pack = $data['test_pack'] = $search_data["test_pack"] = $this->input->get('test_package');
        $city = $data['tcity'] = $search_data["city"] = $this->input->get('city');
		$search_data["date"]= $date ="13/09/2017";//date('d/m/Y') ;
		$search_data["end_date"]= "13/09/2017";//$end_date =date('d/m/Y') ;
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status='1'");
        $cntr_arry = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data["login_data"]['branch_fk'] as $key) {
                $cntr_arry[] = $key["branch_fk"];
            }
            $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status='1' and id in (" . implode(",", $cntr_arry) . ")");
        }
        $data['test_cities'] = $this->registration_admin_model->get_val("SELECT * from test_cities where status='1'");
        $test_packages = explode("_", $test_pack);
        $alpha = $test_packages[0];
        $tp_id = $test_packages[1];
        if ($alpha == 't') {
            $t_id = $tp_id;
        }
        if ($alpha == 'p') {
            $p_id = $tp_id;
        }
        if ($branch != '') {
            $cntr_arry = array();
            $cntr_arry = $branch;
        }
        $search_data['cntr_arry'] = $cntr_arry;
        $search_data['t_id'] = $t_id;
        $search_data['p_id'] = $p_id;

        $result = $this->job_model->csv_job_list($search_data);
	  if(!isset($_REQUEST['de'])){
		header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"All_Jobs_Report-" . date('d-M-Y') . ".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No.", "Reg No.", "Order Id", "Test City", "Branch", "Date", "Patient Name", "Mobile No.", "Doctor", "Test/Package Name", "Job Status", "Payment Type", "Sample From", "Portal", "Remark", "Added By", "Total Price", "Discount(RS.)","Received Amount", "Cash",'Card',"Payumoney","CHEQUE", "Debited From Wallet", "Creditor Remain", "Due Amount"));
	  }
		
        $cnt = 1;
        foreach ($result as $key) {
            if ($key['status'] == 1) {
                $j_status = "Waiting For Approval";
            }
            if ($key['status'] == 6) {
                $j_status = "Approved";
            }
            if ($key['status'] == 7) {
                $j_status = "Sample Collected";
            }
            if ($key['status'] == 8) {
                $j_status = "Processing";
            }
            if ($key['status'] == 2) {
                $j_status = "Completed";
            }
            $sample_collected = 'No';
            if ($key["sample_collection"] == 1) {
                $sample_collected = 'Yes';
            }
            $addr = '';
            if (!empty($key["address"])) {
                $addr = $key["address"];
            } else {
                $addr = $key["address1"];
            }
            if (!$key["payable_amount"]) {
                $key["payable_amount"] = 0;
            }
            /* Nishit 18-08-2017 START */
            $payment_mode = array();
            $discount = 0;
            if ($key["discount"] > 0) {
                $discount = round($key["price"] * $key["discount"] / 100);
            }
            $added_by = "Online";
            if (!empty($key["phlebo_added_by"])) {
                $added_by = $key["phlebo_added_by"] . " (Phlebo)";
            } else if (!empty($key["added_by"])) {
                $added_by = $key["added_by"];
            }
			$cash=0.0;
			$card=0.0;
			$cheque=0.0;
            $collection_type = $this->job_model->get_val("SELECT (`payment_type`) AS payment_type,sum(job_master_receiv_amount.amount) as amount FROM `job_master_receiv_amount` WHERE `status`='1' AND job_fk='" . $key["id"] . "' GROUP BY payment_type ORDER BY payment_type ASC");
			
            if (count($collection_type) > 0) {
				foreach($collection_type as $ct){
					if (strtoupper($ct["payment_type"]) == "CASH") {
						$payment_mode[] = "CASH";
						$cash+=$ct["amount"];
					}
					if(in_array(strtoupper($ct["payment_type"]),array("DEBIT CARD SWIPED THRU ICICI","DEBIT CARD","DEBIT CARD","CREDIT CARD"))){
						$payment_mode[] = "CARD";
						$card+=$ct["amount"];
				//	die("hiten");
					}
					   if (strtoupper($ct["payment_type"]) == "CHEQUE") {
						$payment_mode[] = "CHEQUE";
						$cheque+=$ct["amount"];
					}
				
				}
            }
			$creditor = $this->job_model->get_val("SELECT credit,debit,paid   FROM `creditors_balance` WHERE job_id=" . $key["id"] );
	
			$creditor_cash_collected=0.0;
			$creditor_cash_due=0.0;
			
		
	
			if (count($creditor) > 0) {
				foreach($creditor as $ct){
					if (($ct["credit"]) >0) {
						$payment_mode[] = "CREDITOR CREDIT";
						$creditor_cash_collected+=$ct["credit"];
						$cash+=$ct["credit"];
					}
					if (($ct["debit"]) > 0) {
						$payment_mode[] = "CREDITOR DEBIT";
						$creditor_cash_due+=$ct["debit"];
					}
				
				}
            }
		$creditor_cash_due=$creditor_cash_due-$creditor_cash_collected;
			$payumoneyRecord = $this->job_model->get_val("SELECT SUM(amount) as amount FROM `payment` WHERE STATUS='success' AND job_fk=" . $key["id"] );
			$payumoney=0.0;
		
			if (count($payumoneyRecord) > 0) {
				foreach($payumoneyRecord as $ct){
						$payumoney+=$ct["amount"];
					
				}
            }
			
			
			
            $dabitt_from_wallet = $this->job_model->get_val("SELECT IF(SUM(`debit`)>0,SUM(`debit`),0) AS dabit FROM `wallet_master` WHERE `job_fk`='" . $key["id"] . "' AND `status`='1'");
            $due = round($key["price"] - $key["payable_amount"] - $discount - $dabitt_from_wallet[0]["dabit"]);
            if ($dabitt_from_wallet[0]["dabit"] > 0) {
                $payment_mode[] = "WALLET";
            }
            if (strtoupper($key["payment_type"]) == "PAYUMONEY") {
                $payment_mode[] = "PAYUMONEY";
            }

            /* END */
            if ($key["family_member_fk"] == 0) {
                $patient_name = $key["full_name"];
            } else {
                $patient_name = $key["family_name"];
            }
			
			
			
      if(!isset($_REQUEST['de'])){
		
		fputcsv($handle, array($cnt, $key["id"], $key["order_id"], $key["test_city_name"], $key["branch_name"], $key["date"], $patient_name, $key["mobile"], $key["doctor_name"] . "-" . $key["doctor_mobile"], $key["testname"] . " " . $key["packagename"], $j_status, $key["payment_type"], $key["sample_from"], $key["portal"], $key["note"], $added_by, $key["price"], $discount, $due,$cash,$payumoney,$card,$cheque, $dabitt_from_wallet[0]["dabit"],$creditor_cash_due ,$key["payable_amount"], implode("+", $payment_mode)));
	  }            $cnt++;
        }
        fclose($handle);
        exit;
    }

}

?>
