<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Airmed_tech_report extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Airmed_tech_report_model');
        $this->load->model('user_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->helper('string');
        $data["login_data"] = logindata();
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    /* New Job List Start */

    function index() {
	
        if (!is_loggedin()) {
            redirect('login');
        }

        $search_data = array();

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        
		$user_assign_branch = array();
        if (in_array($data["login_data"]["type"], array("1", "2"))) {
            $user_assign_branch = $this->Airmed_tech_report_model->get_val("select id,branch_name from branch_master where status='1' and branch_type_fk='6'");
        } else {
            $get_user_branch = $this->Airmed_tech_report_model->get_val("SELECT GROUP_CONCAT(`branch_fk`) AS bid FROM `user_branch` WHERE `user_fk`='" . $data["login_data"]["id"] . "' AND `status`='1' GROUP BY user_fk");
            if (!empty($get_user_branch)) {
                $user_assign_branch = $this->Airmed_tech_report_model->get_val("select id,branch_name from branch_master where status='1' and id in (" . $get_user_branch[0]["bid"] . ") and branch_type_fk='6'");
            }
        }
 
		$data["user_assign_branch"] = $user_assign_branch;
	//echo "<pre>"; print_r($data["user_assign_branch"]);
        $data["bid"] = $this->input->get("bid");
		$data["year"] = $this->input->get("year");
        $data["month"] = $this->input->get("month");
      //echo $data["bid"];  
		$month1 = $data["month"];
        
		$branch = "";
        foreach ($user_assign_branch as $key) {
            $branch .= $key['id'] . ',';
        }

        $branch = rtrim($branch, ',');
        $data['branch'] = $branch;

        //if (!empty($data["bid"])) {            
        $temp = "";

        if (!empty($data["bid"])) {
            $temp .= "AND `job_master`.`branch_fk` IN (" . $data["bid"] . ")";
        } else {
            $temp .= "AND `job_master`.`branch_fk` IN (" . $branch . ")";
        }
		
		
		$year = date('Y');
		if(!empty($data["year"])) {
			$year = $data["year"];
		}
		
        if ($month1 != "") {
            if ($month1 != "10" && $month1 != "11" && $month1 != "12") {
                $month1 = "0" . $month1;
            }

            $temp .= "AND DATE_FORMAT(`job_master`.`date`, '%Y-%m') = '$year-$month1'";
            //$temp .= "AND DATE_FORMAT(`job_master`.`date`, '%$year-%m') = '$year-$month1'";
        } else {
            if ($data["bid"] == "") {
                $m = date('m');
                $m1 = $m - 1;
                if ($m1 != "10" && $m1 != "11" && $m1 != "12") {
                    $m1 = "0" . $m1;
                }
                $temp .= "AND DATE_FORMAT(`job_master`.`date`, '%$year-%m') = '$year-$m1'";
            }
        }

//echo $temp; exit;

        $data["get_branch_report"] = $this->Airmed_tech_report_model->get_val("SELECT 
		  ROUND(SUM((`job_master`.price))) AS total_revenue,
		  ROUND(
			SUM(
			  `job_master`.price * `job_master`.`discount` / 100
			)
		  ) AS discount,
		  (
			ROUND(SUM((`job_master`.price))) - ROUND(
			  SUM(
				`job_master`.price * `job_master`.`discount` / 100
			  )
			)
		  ) AS net_price,
		  DATE_FORMAT(`job_master`.`date`, '%M-%Y') AS `date`,
		  DATE_FORMAT(`job_master`.`date`, '%Y-%m') AS `month`,
		  `job_master`.`branch_fk`,
		   branch_master.`branch_name`
		FROM 
		  job_master 
		  INNER JOIN `branch_master` ON `branch_master`.`id` = `job_master`.`branch_fk` 
		WHERE job_master.`status` != '0' 
		  $temp 
		  AND `job_master`.`model_type` = '1' 
		GROUP BY DATE_FORMAT(`job_master`.`date`, '%M-%Y'),`job_master`.`branch_fk` 
		ORDER BY `job_master`.`branch_fk`,job_master.date DESC ");
		//echo "<pre>"; print_r($data["get_branch_report"]);
		
        $p = 0;
        foreach ($data["get_branch_report"] as $pay) {
            $month = $pay["month"];
            $paydetils = $this->Airmed_tech_report_model->get_val("SELECT SUM(amount) as amount  
                        FROM payment_airmedtech 
                        WHERE STATUS='1' AND `branch_fk`='" . $pay["branch_fk"] . "' 
                        AND DATE_FORMAT(paydate, '%Y-%m')='" . $month . "' 
                        GROUP BY DATE_FORMAT(paydate, '%m-%Y')"); //$data["bid"];

//            echo "<pre>"; print_r($paydetils);
//echo $this->db->last_query();
//exit;
			$paidtoairmeddetails = $this->Airmed_tech_report_model->get_val("Select * from tech_revenue_collection_master where branch_fk='".$pay["branch_fk"]."' AND status=1"); //collection_type,collection_value
			$paidtoairmedlogdetails = $this->Airmed_tech_report_model->get_val("Select * from tech_revenue_collection_master_log where branch_fk='".$pay["branch_fk"]."' AND techrevenue_id='".$paidtoairmeddetails[0]['id']."' AND DATE_FORMAT(updated_date, '%Y-%m')<='" . $month . "' ORDER BY updated_date DESC Limit 1");
			//echo "<pre>"; print_r($paidtoairmedlogdetails);
			if(!empty($paidtoairmedlogdetails)){
				$paidtoairmeddetails = $paidtoairmedlogdetails;
			}else{
				$paidtoairmeddetails[0]["collection_type"]=2;
				$paidtoairmeddetails[0]["collection_value"]=7.00;
			}
			if($paidtoairmeddetails[0]["collection_type"]==1){
				$data["get_branch_report"][$p]["paidtoairmed"] = $paidtoairmeddetails[0]["collection_value"];
			}else{
				$data["get_branch_report"][$p]["paidtoairmed"] = ($pay["total_revenue"] * $paidtoairmeddetails[0]["collection_value"])/100;
			}
            $data["get_branch_report"][$p]["paidamount"] = $paydetils[0]["amount"];
            $p++;
        }
        //exit;
        //}
		//echo "<pre>"; print_r($data["get_branch_report"]); exit;
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('airmed_tech_report', $data);
        $this->load->view('footer');
    }

    function export_csv() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $search_data = array();

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $user_assign_branch = array();
        if (in_array($data["login_data"]["type"], array("1", "2"))) {
            $user_assign_branch = $this->Airmed_tech_report_model->get_val("select id,branch_name from branch_master where status='1' and branch_type_fk='6'");
        } else {
            $get_user_branch = $this->Airmed_tech_report_model->get_val("SELECT GROUP_CONCAT(`branch_fk`) AS bid FROM `user_branch` WHERE `user_fk`='" . $data["login_data"]["id"] . "' AND `status`='1' GROUP BY user_fk");
            if (!empty($get_user_branch)) {
                $user_assign_branch = $this->Airmed_tech_report_model->get_val("select id,branch_name from branch_master where status='1' and id in (" . $get_user_branch[0]["bid"] . ") and branch_type_fk='6'");
            }
        }
        $data["user_assign_branch"] = $user_assign_branch;

        $data["bid"] = $this->input->get("bid");
        $branch = "";
        foreach ($user_assign_branch as $key) {
            $branch .= $key['id'] . ',';
        }

        $branch = rtrim($branch, ',');
        $data["month"] = $this->input->get("month");
        $month1 = $data["month"];

        //if (!empty($data["bid"])) {            
        $temp = "";

        if (!empty($data["bid"])) {
            $temp .= "AND `job_master`.`branch_fk` IN (" . $data["bid"] . ")";
        } else {
            $temp .= "AND `job_master`.`branch_fk` IN (" . $branch . ")";
        }
		
		$year = date('Y');
		if(!empty($data["year"])) {
			$year = $data["year"];
		}
		
        if ($month1 != "") {
            //$year = date('Y');
            if ($month1 != "10" && $month1 != "11" && $month1 != "12") {
                $month1 = "0" . $month1;
            }

            //$temp .= "AND DATE_FORMAT(`job_master`.`date`, '%Y-%m') = '$year-$month1'";
            $temp .= "AND DATE_FORMAT(`job_master`.`date`, '%$year-%m') = '$year-$month1'";
        } else {
            if ($data["bid"] == "") {
                $m = date('m');
                $m1 = $m - 1;
                if ($m1 != "10" && $m1 != "11" && $m1 != "12") {
                    $m1 = "0" . $m1;
                }
                $temp .= "AND DATE_FORMAT(`job_master`.`date`, '%$year-%m') = '$year-$m1'";
            }
        }



        $data["get_branch_report"] = $this->Airmed_tech_report_model->get_val("SELECT 
  ROUND(SUM((`job_master`.price))) AS total_revenue,
  ROUND(
    SUM(
      `job_master`.price * `job_master`.`discount` / 100
    )
  ) AS discount,
  (
    ROUND(SUM((`job_master`.price))) - ROUND(
      SUM(
        `job_master`.price * `job_master`.`discount` / 100
      )
    )
  ) AS net_price,
  DATE_FORMAT(`job_master`.`date`, '%M-%Y') AS `date`,
  DATE_FORMAT(`job_master`.`date`, '%Y-%m') AS `month`,
  `job_master`.`branch_fk`,
   branch_master.`branch_name`
FROM 
  job_master 
  INNER JOIN `branch_master` ON `branch_master`.`id` = `job_master`.`branch_fk` 
WHERE job_master.`status` != '0' 
  $temp 
  AND `job_master`.`model_type` = '1' 
GROUP BY DATE_FORMAT(`job_master`.`date`, '%M-%Y'),`job_master`.`branch_fk` 
ORDER BY `job_master`.`branch_fk`,job_master.date DESC ");

        //echo "<pre>"; print_r($data["get_branch_report"]); exit;

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Airmed_" . $data["get_branch_report"][0]["branch_name"] . "_" . $data["get_branch_report"][0]["date"] . "_Report.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');


        // Bhavik 19 June 2018
        if ($data["login_data"]['type'] == "1" || $data["login_data"]['type'] == "2") {
            fputcsv($handle, array("No.", "Center", "Month", "Total Revenue", "Discount", "Net Price", "Paid to Airmed"));
        } else {
            fputcsv($handle, array("No.", "Center", "Month", "Total Revenue", "Paid to Airmed"));
        }
        // Bhavik 19 June 2018

        $cnt = 1;
        //echo "<pre>"; print_r($data["get_branch_report"]); exit;
        foreach ($data["get_branch_report"] as $key) {

            $month = $key["month"];
            $paydetils = $this->Airmed_tech_report_model->get_val("SELECT SUM(amount) as amount  FROM payment_airmedtech 
                        WHERE STATUS='1' AND `branch_fk`='" . $key['branch_fk'] . "'  
                        AND DATE_FORMAT(paydate, '%Y-%m')='" . $month . "' 
                        GROUP BY DATE_FORMAT(paydate, '%m-%Y')");//$data["bid"]
//            echo "<pre>"; print_r($paydetils);
//echo $this->db->last_query();
//exit;


            if (0 < $paydetils[0]["amount"]) {
                $amount = $paydetils[0]["amount"];
            } else {
                $amount = 0;
            }
            //$data["get_branch_report"][$p]["paidamount"] = $paydetils[0]["amount"];
			$paidtoairmeddetails = $this->Airmed_tech_report_model->get_val("Select * from tech_revenue_collection_master where branch_fk='".$key["branch_fk"]."' AND status=1"); //collection_type,collection_value
			$paidtoairmedlogdetails = $this->Airmed_tech_report_model->get_val("Select * from tech_revenue_collection_master_log where branch_fk='".$key["branch_fk"]."' AND techrevenue_id='".$paidtoairmeddetails[0]['id']."' AND DATE_FORMAT(updated_date, '%Y-%m')<='" . $month . "' ORDER BY updated_date DESC Limit 1");
			//echo "<pre>"; print_r($paidtoairmedlogdetails);
			if(!empty($paidtoairmedlogdetails)){
				$paidtoairmeddetails = $paidtoairmedlogdetails;
			}else{
				$paidtoairmeddetails[0]["collection_type"]=2;
				$paidtoairmeddetails[0]["collection_value"]=7.00;
			}
			
			if($paidtoairmeddetails[0]["collection_type"]==1){
				$payamount = round($paidtoairmeddetails[0]["collection_value"]) - $key['paidamount'];
			}else{
				$payamount = round(($key["total_revenue"] * $paidtoairmeddetails[0]["collection_value"])/100) - $key['paidamount'];
			}
            //$payamount = round($key['total_revenue'] * 7 / 100) - $key['paidamount'];

            if ($data["login_data"]['type'] == "1" || $data["login_data"]['type'] == "2") {
                fputcsv($handle, array($cnt, $key["branch_name"], $key["date"], $key["total_revenue"], $key["discount"], $key["net_price"], $payamount));
            } else {
                fputcsv($handle, array($cnt, $key["branch_name"], $key["date"], $key["total_revenue"], $payamount));
            }

            $cnt++;
        }
        fclose($handle);
        exit;
        //}
    }

    function month_report() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);


        $bid = $data["bid"] = $this->input->get("bid");
        $month = $data["month"] = $this->input->get("month");
        iF (!empty($bid) && !empty($month)) {
            $data["report_data"] = $this->Airmed_tech_report_model->get_val("SELECT 
  `job_master`.id,
  `job_master`.`order_id`,
  IF(`booking_info`.`family_member_fk`>0,customer_family_master.`name`,customer_master.`full_name`) AS patient,
  `job_master`.`date`,
  `job_master`.`price`,
  `job_master`.`discount`,
  `job_master`.`payable_amount` 
FROM
  job_master 
  LEFT JOIN `booking_info` ON `job_master`.`booking_info`=`booking_info`.`id`
  LEFT JOIN `customer_master` ON `job_master`.`cust_fk`=`customer_master`.id
  LEFT JOIN `customer_family_master` ON `customer_family_master`.`id`=`booking_info`.`family_member_fk`
WHERE `job_master`.`branch_fk` = '" . $bid . "' 
  AND `job_master`.`date` LIKE '%" . $month . "%' 
  AND `job_master`.`status` != '0' and `job_master`.`model_type`='1'");
        }
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('airmed_tech_month_report', $data);
        $this->load->view('footer');
    }

    function export_csv_branch() {
        $data["bid"] = $this->input->get("bid");
        $bid = $data["bid"] = $this->input->get("bid");
        $month = $data["month"] = $this->input->get("month");
        iF (!empty($bid) && !empty($month)) {
            $data["report_data"] = $this->Airmed_tech_report_model->get_val("SELECT 
  `job_master`.id,
  `job_master`.`order_id`,
  IF(`booking_info`.`family_member_fk`>0,customer_family_master.`name`,customer_master.`full_name`) AS patient,
  `job_master`.`date`,
  `job_master`.`price`,
  `job_master`.`discount`,
  `job_master`.`payable_amount`,
  branch_master.branch_name
FROM
  job_master 
  LEFT JOIN `booking_info` ON `job_master`.`booking_info`=`booking_info`.`id`
  LEFT JOIN `customer_master` ON `job_master`.`cust_fk`=`customer_master`.id
  LEFT JOIN `customer_family_master` ON `customer_family_master`.`id`=`booking_info`.`family_member_fk`
  left join branch_master on branch_master.id=job_master.branch_fk
WHERE `job_master`.`branch_fk` = '" . $bid . "' 
  AND `job_master`.`date` LIKE '%" . $month . "%' 
  AND `job_master`.`status` != '0' and `job_master`.`model_type`='1'");

            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"Airmed_" . $data["report_data"][0]["branch_name"] . "_" . date("M-Y", strtotime($data["report_data"][0]["date"])) . "_Booking_Report.csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle = fopen('php://output', 'w');
            fputcsv($handle, array("No.", "Ref. No.", "Order ID", "Patient Name", "Date", "Price", "Discount", "Due Amount"));
            $cnt = 1;
            foreach ($data["report_data"] as $key) {
                $discount = ($row['discount'] > 0) ? ($row['price'] - (($row['price'] * $row['discount']) / 100)) : 0;
                fputcsv($handle, array($cnt, $key["id"], $key["order_id"], $key["patient"], $key["date"], $key["price"], $discount, $key["payable_amount"]));
                $cnt++;
            }
            fclose($handle);
            exit;
        }
    }

    function payumoney() {

//        if (!is_loggedin()) {
//            redirect('login');
//        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['payumoneydetail'] = $this->config->item('payumoneydetail');

        /* echo "<pre>"; print_r($data['payumoneydetail']); die(); */


        $this->load->library('session');

        $data['rndtxn'] = random_string('numeric', 20);
        $bid = $this->input->get("bid");
        $month = $this->input->get("month");

        $data["bid"] = $bid;
        $data["month"] = $month;

        if ($bid != "" && $month != "") {

            $destail1 = $this->Airmed_tech_report_model->get_val("SELECT ROUND(SUM((`job_master`.price))) as revenue, (ROUND(SUM((`job_master`.price))) - ROUND(SUM(`job_master`.price * `job_master`.`discount` /100))) AS net_price FROM job_master WHERE job_master.`status` != '0' AND `job_master`.`branch_fk` IN (" . $bid . ") AND `job_master`.`model_type` = '1' and DATE_FORMAT(`job_master`.`date`, '%Y-%m')='$month' GROUP BY DATE_FORMAT(`job_master`.`date`, '%M-%Y'),`job_master`.`branch_fk` ORDER BY `job_master`.`branch_fk`,job_master.date DESC LIMIT 1 ");
			$paidtoairmeddetails = $this->Airmed_tech_report_model->get_val("Select * from tech_revenue_collection_master where branch_fk='".$bid."' AND status=1");			
			$paidtoairmedlogdetails = $this->Airmed_tech_report_model->get_val("Select * from tech_revenue_collection_master_log where branch_fk='".$bid."' AND techrevenue_id='".$paidtoairmeddetails[0]['id']."' AND DATE_FORMAT(updated_date, '%Y-%m')<='" . $month . "' ORDER BY updated_date DESC Limit 1");
			//echo "<pre>"; print_r($destail1); exit;
			if(!empty($paidtoairmedlogdetails)){
				$paidtoairmeddetails = $paidtoairmedlogdetails;
			}else{
				$paidtoairmeddetails[0]["collection_type"]=2;
				$paidtoairmeddetails[0]["collection_value"]=7.00;
			}
			if($paidtoairmeddetails[0]["collection_type"]==1){
				$amount = round($paidtoairmeddetails[0]["collection_value"]);
			}else{
				$amount = round(($destail1[0]["revenue"] * $paidtoairmeddetails[0]["collection_value"])/100);
			}
            //$amount = round($destail1[0]["revenue"] * 7 / 100);

            $data['payamount'] = $amount;
			
			//echo "<pre>"; print_r($data);exit;

            $this->load->view('payumoney_bpay', $data);
        } else {
            show_404();
        }
    }

    function payumoney_suceess() {

        $data["login_data"] = logindata();
        $aid = $data["login_data"]["id"];

        $payumoneydetail = $this->config->item('payumoneydetail');

        $bid = $this->input->get("bid");
        $month = $this->input->get("month");

        if ($bid != "" && $month != "") {

            $status = $this->input->post("status");

            $firstname = $this->input->post("firstname");
            $amount = $this->input->post("amount");
            $txnid = $this->input->post("txnid");
            $posted_hash = $this->input->post("hash");
            $key = $this->input->post("key");
            $productinfo = $this->input->post("productinfo");
            $email = $this->input->post("email");
            $salt = $payumoneydetail["SALT"];

            $payujson = json_encode($_REQUEST);

            if ($status == "success") {

                $patdate = $month . "-01";
                $paymentdate = date("Y-m-d", strtotime($patdate));
                $data = array("branch_fk" => $bid, "amount" => $amount, "paydate" => $paymentdate, "creteddate" => date("Y-m-d H:i:s"), "creted_by" => $aid, "status" => '1', "payujson" => $payujson);

                $this->Airmed_tech_report_model->master_fun_insert("payment_airmedtech", $data);

                $this->session->set_flashdata("success", "We have received a payment of Rs.$amount");
                redirect("Airmed_tech_report/index?bid=$bid");
            } else {

                $patdate = "01-" . $month;
                $paymentdate = date("Y-m-d", strtotime($patdate));
                $data = array("branch_fk" => $bid, "amount" => $amount, "paydate" => $paymentdate, "creteddate" => date("Y-m-d H:i:s"), "creted_by" => $aid, "status" => '0', "payujson" => $payujson);

                $this->Airmed_tech_report_model->master_fun_insert("payment_airmedtech", $data);

                $this->session->set_flashdata("error", "Invalid Transaction. Please try again");
                redirect("Airmed_tech_report/index?bid=$bid");
            }
        } else {
            show_404();
        }
    }

    function payumoney_cancel() {

        $bid = $this->input->get("bid");
        $this->session->set_flashdata("error", array("Invalid Transaction. Please try again"));
        redirect("Airmed_tech_report/index?bid=$bid");
    }

    function recivepayment() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $aid = $data["login_data"]["id"];

        if ($data["login_data"]["type"] == '1' || $data["login_data"]["type"] == '2') {

            $amount = $this->input->post("amount");
            $bid = $this->input->post("bid");
            $paymonth = $this->input->post("paymonth");
            $type = $this->input->post("type");
            $note = $this->input->post("note");
			
            if ($bid != "" && $paymonth != "" && $type != "") {

                //$destail1 = $this->Airmed_tech_report_model->get_val("SELECT (ROUND(SUM((`job_master`.price))) - ROUND(SUM(`job_master`.price * `job_master`.`discount` /100))) AS net_price FROM job_master WHERE job_master.`status` != '0' AND `job_master`.`branch_fk` IN (" . $bid . ") AND `job_master`.`model_type` = '1' and DATE_FORMAT(`job_master`.`date`, '%Y-%m')='$paymonth' GROUP BY DATE_FORMAT(`job_master`.`date`, '%M-%Y'),`job_master`.`branch_fk` ORDER BY `job_master`.`branch_fk`,job_master.date DESC LIMIT 1");
				
				$destail1 = $this->Airmed_tech_report_model->get_val("SELECT (ROUND(SUM(`job_master`.price))) AS total_revenue FROM job_master WHERE job_master.`status` != '0' AND `job_master`.`branch_fk` IN (" . $bid . ") AND `job_master`.`model_type` = '1' and DATE_FORMAT(`job_master`.`date`, '%Y-%m')='$paymonth' GROUP BY DATE_FORMAT(`job_master`.`date`, '%M-%Y'),`job_master`.`branch_fk` ORDER BY `job_master`.`branch_fk`,job_master.date DESC LIMIT 1");
				$paidtoairmeddetails = $this->Airmed_tech_report_model->get_val("Select * from tech_revenue_collection_master where branch_fk='".$bid."' AND status=1"); //collection_type,collection_value
				$paidtoairmedlogdetails = $this->Airmed_tech_report_model->get_val("Select * from tech_revenue_collection_master_log where branch_fk='".$bid."' AND techrevenue_id='".$paidtoairmeddetails[0]['id']."' AND DATE_FORMAT(updated_date, '%Y-%m')<='" . $paymonth . "' ORDER BY updated_date DESC Limit 1");
				//echo "<pre>"; print_r($destail1); exit;
				if(!empty($paidtoairmedlogdetails)){
					$paidtoairmeddetails = $paidtoairmedlogdetails;
				}else{
					$paidtoairmeddetails[0]["collection_type"]=2;
					$paidtoairmeddetails[0]["collection_value"]=7.00;
				}
								
				if($paidtoairmeddetails[0]["collection_type"]==1){
					$amount = round($paidtoairmeddetails[0]["collection_value"]);
				}else{
					//$amount = round(($destail1[0]["net_price"] * $paidtoairmeddetails[0]["collection_value"])/100);
					$amount = round(($destail1[0]["total_revenue"] * $paidtoairmeddetails[0]["collection_value"])/100);
				}
				//$amount = round($destail1[0]["net_price"] * 7 / 100);

                $patdate = $paymonth . "-01";
                $paymentdate = date("Y-m-d", strtotime($patdate));
                $data = array("branch_fk" => $bid, "amount" => $amount, "paydate" => $paymentdate, "creteddate" => date("Y-m-d H:i:s"), "creted_by" => $aid, "paytype" => '2', "paymentmode" => $type, "remark" => $note, "status" => '1');
				
                $this->Airmed_tech_report_model->master_fun_insert("payment_airmedtech", $data);

                $this->session->set_flashdata("success", "We have received a payment of Rs.$amount");
                redirect("Airmed_tech_report/index?bid=$bid");
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }

    function convertToIndianCurrency($number) {
        $no = round($number);
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            0 => '',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen',
            20 => 'Twenty',
            30 => 'Thirty',
            40 => 'Forty',
            50 => 'Fifty',
            60 => 'Sixty',
            70 => 'Seventy',
            80 => 'Eighty',
            90 => 'Ninety');
        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        while ($i < $digits_length) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural;
            } else {
                $str [] = null;
            }
        }

        $Rupees = implode(' ', array_reverse($str));

        return $Rupees;
    }

    function printinvoice() {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $bid = $this->input->get("bid");
        $month = $this->input->get("month");

        $data["bid"] = $bid;
        $data["month"] = $month;

        if ($bid != "" && $month != "") {

            $data["branchdetils"] = $this->Airmed_tech_report_model->get_val("SELECT id,branch_name,address FROM `branch_master` WHERE id='$bid'");

            if ($data["branchdetils"][0]["id"] != "") {

                $destail1 = $this->Airmed_tech_report_model->get_val("SELECT ROUND(SUM((`job_master`.price))) as revenue, (ROUND(SUM((`job_master`.price))) - ROUND(SUM(`job_master`.price * `job_master`.`discount` /100))) AS net_price FROM job_master WHERE job_master.`status` != '0' AND `job_master`.`branch_fk` IN (" . $bid . ") AND `job_master`.`model_type` = '1' and DATE_FORMAT(`job_master`.`date`, '%Y-%m')='$month' GROUP BY DATE_FORMAT(`job_master`.`date`, '%M-%Y'),`job_master`.`branch_fk` ORDER BY `job_master`.`branch_fk`,job_master.date DESC LIMIT 1 ");

                $revenue = round($destail1[0]["revenue"]);
				$paidtoairmeddetails = $this->Airmed_tech_report_model->get_val("Select * from tech_revenue_collection_master where branch_fk='".$bid."' AND status=1");//collection_type,collection_value
				$paidtoairmedlogdetails = $this->Airmed_tech_report_model->get_val("Select * from tech_revenue_collection_master_log where branch_fk='".$bid."' AND techrevenue_id='".$paidtoairmeddetails[0]['id']."' AND DATE_FORMAT(updated_date, '%Y-%m')<='" . $month . "' ORDER BY updated_date DESC Limit 1");
				//echo "<pre>"; print_r($paidtoairmedlogdetails); exit;
				if(!empty($paidtoairmedlogdetails)){
					$paidtoairmeddetails = $paidtoairmedlogdetails;
				}else{
					$paidtoairmeddetails[0]["collection_type"]=2;
					$paidtoairmeddetails[0]["collection_value"]=7.00;
				}
					
				if($paidtoairmeddetails[0]["collection_type"]==1){
					$amount = round($paidtoairmeddetails[0]["collection_value"]);
				}else{
					$amount = round(($destail1[0]["revenue"] * $paidtoairmeddetails[0]["collection_value"])/100);
				}
                //$amount = round($revenue * 7 / 100);

                $monthdate = $month . "-01";
				$data['collection_type'] = $paidtoairmeddetails[0]["collection_type"];
				$data['collection_value'] = $paidtoairmeddetails[0]["collection_value"];
                $data['revenue'] = $revenue;
                $data['payamount'] = $amount;
                $data['amountsring'] = $this->convertToIndianCurrency($amount);
                $data['date2'] = $monthdate;

                $data['todate2'] = date('Y-m-t', strtotime($monthdate));

				//echo "<pre>"; print_r($data); exit;
                $filename = $month . "invoice.pdf";
                $pdfFilePath = FCPATH . "/upload/branchreport/" . $filename;
                $html = $this->load->view('invoicebranch_pdf_view', $data, true);

                $this->load->library('pdf');
                $pdf = $this->pdf->load();
                $pdf->autoScriptToLang = true;
                $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
                $pdf->autoVietnamese = true;
                $pdf->autoArabic = true;
                $pdf->autoLangToFont = true;
                /* $pdf->AddPage('p', // L - landscape, P - portrait
                  '', '', '', '', 5, // margin_left
                  5, // margin right
                  5, // margin top
                  5, // margin bottom
                  2, // margin header
                  2); */ // margin footer

                $pdf->AddPage('p', // L - landscape, P - portrait
                        '', '', '', '', 0, // margin_left
                        0, // margin right
                        5, // margin top
                        5, // margin bottom
                        5, // margin header
                        5);
                $pdf->SetHTMLFooter('<div style="height:100px;"><div class="foot_num_div" style="margin-bottom:0;padding-bottom:0">
		<p class="foot_num_p" style="margin-bottom:2;padding-bottom:0"><img class="set_sign" src="pdf_phn_btn.png" style="width:"/></p>
		<p class="foot_lab_p" style="font-size:13px;margin-bottom:2;padding-bottom:0">LAB AT YOUR DOORSTEP</p>
	</div>
		<p class="lst_airmed_mdl" style="font-size:13px;margin-top:5px">Airmed Pathology Pvt. Ltd.</p>
		<p class="lst_31_addrs_mdl" style="font-size:12px"><span style="color:#9D0902;">Corporate Office :</span>31, Ambika Society, Next to Nabard Bank, Opp. Usmanpura Garden,Usmanpura, Ahmedabad, Gujarat - 380013.</p>
		<p class="lst_31_addrs_mdl"><b><img src="email-icon.png" style="margin-bottom:-3px;width:13px"/> info@airmedlabs.com  <img src="web-icon.png" style="margin-bottom:-3px;width:13px"/> www.airmedlabs.com</b></p><p class="lst_31_addrs_mdl"> </p></div>');
                if ($_REQUEST["debug"] == 1) {
                    echo $html;
                    die();
                }
                $pdf->WriteHTML($html);
                $pdf->debug = true;
                $pdf->allow_output_buffering = TRUE;
                if (file_exists($pdfFilePath) == true) {
                    $this->load->helper('file');
                    unlink($path);
                }
                $pdf->Output($pdfFilePath, 'F');

                $base_url = base_url() . "upload/branchreport/" . $filename;
                $filename = FCPATH . "/upload/branchreport/" . $filename;
                header('Pragma: public');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Cache-Control: private', false); // required for certain browsers 
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="' . basename($filename) . '";');
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: ' . filesize($filename));
                readfile($filename);
                exit;
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }

//    Bhavik 15 June 2018
    public function check_due_payment() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $search_data = array();
        $this->load->model('Airmed_tech_report_model');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        
        $user_assign_branch = array();
        
        $get_user_branch = $this->Airmed_tech_report_model->get_val("SELECT 
                GROUP_CONCAT(`branch_fk`) AS bid 
                FROM `user_branch` 
                WHERE `user_fk`='" . $data["login_data"]["id"] . "' 
                AND `status`='1' GROUP BY user_fk");
        
        if (!empty($get_user_branch)) {
            $user_assign_branch = $this->Airmed_tech_report_model->get_val("select id,branch_name 
                    from branch_master 
                    where status='1' and id in (" . $get_user_branch[0]["bid"] . ") 
                    AND branch_type_fk='6'");
        }
        
        $data["user_assign_branch"] = $user_assign_branch;

        $data["bid"] = $data["user_assign_branch"][0]['id'];

        if (!empty($data["bid"])) {
            $data["get_branch_report"] = $this->Airmed_tech_report_model->get_val("SELECT 
  ROUND(SUM((`job_master`.price))) AS total_revenue,
  ROUND(
    SUM(
      `job_master`.price * `job_master`.`discount` / 100
    )
  ) AS discount,
  (
    ROUND(SUM((`job_master`.price))) - ROUND(
      SUM(
        `job_master`.price * `job_master`.`discount` / 100
      )
    )
  ) AS net_price,
  DATE_FORMAT(`job_master`.`date`, '%M-%Y') AS `date`,
  DATE_FORMAT(`job_master`.`date`, '%Y-%m') AS `month`,
  `job_master`.`branch_fk`,
   branch_master.`branch_name`
FROM
  job_master 
  INNER JOIN `branch_master` ON `branch_master`.`id` = `job_master`.`branch_fk`
WHERE job_master.`status` != '0' 
  AND `job_master`.`branch_fk` IN (" . $data["bid"] . ") 
  AND `job_master`.`model_type` = '1' 
GROUP BY DATE_FORMAT(`job_master`.`date`, '%M-%Y'),`job_master`.`branch_fk` 
ORDER BY `job_master`.`branch_fk`,job_master.date DESC ");

            $p = 0;
            foreach ($data["get_branch_report"] as $pay) {

                $month = $pay["month"];
                $paydetils = $this->Airmed_tech_report_model->get_val("SELECT SUM(amount) as amount  
                        FROM payment_airmedtech 
                        WHERE STATUS='1' AND `branch_fk`='" . $data["bid"] . "' 
                        AND DATE_FORMAT(paydate, '%Y-%m')='" . $month . "' 
                        GROUP BY DATE_FORMAT(paydate, '%m-%Y')");

                $data["get_branch_report"][$p]["paidamount"] = $paydetils[0]["amount"];
                
                $p++;
            }
        }
        return $data;
    }

}

?>