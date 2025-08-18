<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Job_report extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('job_model');
        $this->load->model('user_model');
        $this->load->model('job_report_model');
        $this->load->library('email');
        $this->load->helper('string');
        $this->app_tarce();
    }

    function app_tarce() {
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        $page = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
        if (!empty($_SERVER['QUERY_STRING'])) {
            $page = $_SERVER['QUERY_STRING'];
        } else {
            $page = "";
        }
        if (!empty($_POST)) {
            $user_post_data = $_POST;
        } else {
            $user_post_data = array();
        }
        $user_post_data = json_encode($user_post_data);
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $remotehost = @getHostByAddr($ipaddress);
        $user_info = json_encode(array("Ip" => $ipaddress, "Page" => $page, "UserAgent" => $useragent, "RemoteHost" => $remotehost));
        if ($actual_link != "http://www.airmedlabs.com/index.php/api/send") {
            $user_track_data = array("url" => $actual_link, "user_details" => $user_info, "data" => $user_post_data, "createddate" => date("Y-m-d H:i:s"), "type" => "service");
        }
        $app_info = $this->user_model->master_fun_insert("user_track", $user_track_data);
        //return true;
    }

    function daily_report() {
        $data['total_revenue'] = $this->user_model->total_revenue();
        $data['total_due_amount'] = $this->user_model->total_due();
        $job_status = $this->user_model->get_job_status();

        $today_total_revenue = $data['total_revenue'][0]["revenue"];
        $today_total_due = $data['total_due_amount'][0]["due_amount"];
        $total_paid = $today_total_revenue - $today_total_due;

        $total_jobs = 0;
        $waiting_for_approve = 0;
        $approved = 0;
        $sample_collected = 0;
        $processing = 0;
        $completed = 0;
        foreach ($job_status as $key) {
            $total_jobs = $total_jobs + $key["count"];
            if ($key["status"] == 1) {
                $waiting_for_approve = $key["count"];
            }

            if ($key["status"] == 6) {
                $approved = $key["count"];
            }

            if ($key["status"] == 7) {
                $sample_collected = $key["count"];
            }

            if ($key["status"] == 8) {
                $processing = $key["count"];
            }

            if ($key["status"] == 2) {
                $completed = $key["count"];
            }
        }
        $mobile = $this->config->item('admin_report_sms');
        $sms_message = $this->master_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "daily_report"), array("id", "asc"));
        $sms_message = preg_replace("/{{TJBS}}/", $total_jobs, $sms_message[0]["message"]);
        $sms_message = preg_replace("/{{WFA}}/", $waiting_for_approve, $sms_message);
        $sms_message = preg_replace("/{{APR}}/", $approved, $sms_message);
        $sms_message = preg_replace("/{{SC}}/", $sample_collected, $sms_message);
        $sms_message = preg_replace("/{{PRC}}/", $processing, $sms_message);
        $sms_message = preg_replace("/{{CPTD}}/", $completed, $sms_message);
        /* $sms_message = preg_replace("/{{TRVN}}/", "Rs.".$today_total_revenue, $sms_message);
          $sms_message = preg_replace("/{{TPID}}/", "Rs.".$total_paid, $sms_message);
          $sms_message = preg_replace("/{{TDUE}}/", "Rs.".$today_total_due, $sms_message); */
        $this->load->helper("sms");
        $notification = new Sms();
        foreach ($mobile as $key) {
            $notification::send($key, $sms_message);
        }
        echo '[{"status":"1","msg":"SMS successfully send."}]';
    }

    function mypayment() {
        if (!is_loggedin()) {
            redirect('login');
        }
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["login_data"] = logindata();
        if ($data["login_data"]['type'] == 2) {
            //   redirect('Admin/Telecaller');
        }

        $data["login_data"] = logindata();
        if ($data["login_data"]['type'] == 3 || $data["login_data"]['type'] == 4) {
            redirect('Logistic/dashboard');
        }
        $data['branchlist'] = $this->job_model->get_val("SELECT * from branch_master where status='1'");

        $data['start_date'] = $this->input->get("start_date");
        $data['end_date'] = $this->input->get("end_date");
        $data['branch'] = $this->input->get("branch");
        $data['types'] = $this->input->get("type");

        $user_branch = $this->user_model->master_fun_get_tbl_val("user_branch", array('status' => 1, "user_fk" => $data["login_data"]['id']), array("id", "asc"));
        $data['branch_list'] = $user_branch;
        $branch = array();
        foreach ($user_branch as $key1) {
            $branch[] = $key1["branch_fk"];
        }
        $start_date = null;
        $end_date = null;
        if ($data['start_date'] != "") {
            $start_date = $data['start_date'];
        }

        if ($data['end_date'] != "") {
            $end_date = $data['end_date'];
        }
        $data['payment_type'] = $this->user_model->master_fun_get_tbl_val("payment_type_master", array('status' => 1), array("id", "asc"));
        $data['collecting_amount_branch'] = $this->job_report_model->getMyPaymentReportNew($start_date, $end_date, $data["login_data"]['id'], ucwords(strtolower($data['types'])));
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('my_payment_report', $data);
        $this->load->view('footer');
    }

    function payment() {
        if (!is_loggedin()) {
            redirect('login');
        }
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["login_data"] = logindata();
        if ($data["login_data"]['type'] == 2) {
            //   redirect('Admin/Telecaller');
        }

        $data["login_data"] = logindata();
        if ($data["login_data"]['type'] == 3 || $data["login_data"]['type'] == 4) {
            redirect('Logistic/dashboard');
        }
        $data['branchlist'] = $this->job_model->get_val("SELECT * from branch_master where status='1'");

        $data['start_date'] = $this->input->get("start_date");
        $data['end_date'] = $this->input->get("end_date");
        $data['branch'] = $this->input->get("branch");
        $data['types'] = $this->input->get("type");

        $user_branch = $this->user_model->master_fun_get_tbl_val("user_branch", array('status' => 1, "user_fk" => $data["login_data"]['id']), array("id", "asc"));
        $data['branch_list'] = $user_branch;
        $branch = array();
        foreach ($user_branch as $key1) {
            $branch[] = $key1["branch_fk"];
        }
        $start_date = null;
        $end_date = null;
        if ($data['start_date'] != "") {
            $start_date = $data['start_date'];
        }

        if ($data['end_date'] != "") {
            $end_date = $data['end_date'];
        }
        $data['payment_type'] = $this->user_model->master_fun_get_tbl_val("payment_type_master", array('status' => 1), array("id", "asc"));
        $data['collecting_amount_branch'] = $this->job_report_model->getPaymentReport($branch, $start_date, $end_date, $data['branch'], ucwords(strtolower($data['types'])));
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('payment_report', $data);
        $this->load->view('footer');
    }

    function mypayment_with_type() {
        if (!is_loggedin()) {
            redirect('login');
        }
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["login_data"] = logindata();
        if ($data["login_data"]['type'] == 2) {
            //   redirect('Admin/Telecaller');
        }

        $data["login_data"] = logindata();
        if ($data["login_data"]['type'] == 3 || $data["login_data"]['type'] == 4) {
            redirect('Logistic/dashboard');
        }
        $data['branchlist'] = $this->job_model->get_val("SELECT * from branch_master where status='1'");

        $data['start_date'] = $this->input->get("start_date");
        $data['end_date'] = $this->input->get("end_date");
        $data['branch'] = $this->input->get("branch");
        $data['types'] = $this->input->get("type");

        $user_branch = $this->user_model->master_fun_get_tbl_val("user_branch", array('status' => 1, "user_fk" => $data["login_data"]['id']), array("id", "asc"));
        $data['branch_list'] = $user_branch;
        $branch = array();
        foreach ($user_branch as $key1) {
            $branch[] = $key1["branch_fk"];
        }
        $start_date = null;
        $end_date = null;
        if ($data['start_date'] != "") {
            $start_date = $data['start_date'];
        }

        if ($data['end_date'] != "") {
            $end_date = $data['end_date'];
        }
        $data['payment_type'] = $this->user_model->master_fun_get_tbl_val("payment_type_master", array('status' => 1), array("id", "asc"));
        $data['collecting_amount_branch'] = $this->job_report_model->getPaymentReport_type($branch, $start_date, $end_date, $data['branch'], ucwords(strtolower($data['types'])), $data["login_data"]['id']);
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('mypayment_type_report', $data);
        $this->load->view('footer');
    }

    function jobpayment() {
        if (!is_loggedin()) {
            redirect('login');
        }
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["login_data"] = logindata();
        if ($data["login_data"]['type'] == 2) {
            //   redirect('Admin/Telecaller');
        }

        $data["login_data"] = logindata();
        if ($data["login_data"]['type'] == 3 || $data["login_data"]['type'] == 4) {
            redirect('Logistic/dashboard');
        }
        $data['branchlist'] = $this->job_model->get_val("SELECT * from branch_master where status='1'");

        $data['start_date'] = $this->input->get("start_date");
        $data['end_date'] = $this->input->get("end_date");
        $data['city'] = $this->input->get("city");
        $data['types'] = $this->input->get("type");

        $start_date = null;
        $end_date = null;
        if ($data['start_date'] != "") {
            $start_date = $data['start_date'];
        }

        if ($data['end_date'] != "") {
            $end_date = $data['end_date'];
        }
        $data['city_list'] = $this->user_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
        $data['payment_type'] = $this->user_model->master_fun_get_tbl_val("payment_type_master", array('status' => 1), array("id", "asc"));
        $data['collecting_amount_branch'] = $this->job_report_model->jobPaymentReport($start_date, $end_date, $data["login_data"]['id'], ucwords(strtolower($data['types'])), $data['city']);
        //die($this->db->last_query());
		$this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('job_payment_report', $data);
        $this->load->view('footer');
    }

    function getDailyCollectionData($start_date, $branch) {
        $data["login_data"] = logindata();
        $data['start_date'] = $start_date;
        $user_branch = $this->user_model->master_fun_get_tbl_val("user_branch", array('status' => 1, "user_fk" => $data["login_data"]['id']), array("id", "asc"));
        // $branch = array();
        $start_date = null;
        $end_date = null;
        if ($data['start_date'] != "") {
            $start_date = $data['start_date'];
        }
        $DailyCollectionData = array();
        foreach ($branch as $b) {
            $data['branch'] = $b;
            $data['userlistAddedBy'] = $this->job_model->get_val("SELECT  DISTINCT( job_master.`added_by`) FROM job_master WHERE !ISNULL(job_master.`added_by`) AND job_master.model_type=1 and (job_master.`added_by`)!='0' AND job_master.`branch_fk`='" . $data['branch'] . "'  AND DATE_FORMAT(job_master.`date`, '%Y-%m-%d') = STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  ) ");
			
			
            $data['userlistCashAddedBy2'] = $this->job_model->get_val("SELECT   DISTINCT(job_master_receiv_amount.`added_by` ) FROM  `job_master_receiv_amount`   LEFT JOIN job_master jb     ON jb.id = job_master_receiv_amount.job_fk  WHERE ! ISNULL(job_master_receiv_amount.added_by)    AND   jb.model_type=1 and  job_master_receiv_amount.status != 0 AND DATE_FORMAT( job_master_receiv_amount.`createddate`,'%d/%m/%Y')= '" . $data['start_date'] . "' and jb.branch_fk='" . $data['branch'] . "'");

            $usersaddedbyall=  array_merge($data['userlistAddedBy'],$data['userlistCashAddedBy2']);
            $data['branch'] = $b;
            $addedBY = array();
            foreach ($usersaddedbyall as $o) {
                if(!empty($o['added_by'])){
                $addedBY[] = $o['added_by'];
                }
            }
            $creditor_user = $this->job_model->get_val("SELECT  DISTINCT created_by FROM `creditors_balance` LEFT JOIN job_master ON job_master.id=`creditors_balance`.`job_id` WHERE job_master.`status` = '1' AND `creditors_balance`.`status` = '1'   AND DATE_FORMAT(creditors_balance.`created_date`, '%Y-%m-%d')  = STR_TO_DATE('" . $data['start_date'] . "','%d/%m/%Y') AND `credit`>0 ");
            foreach ($creditor_user as $oa) {
                $addedBY[] = $oa['created_by'];
            }
            $UsersData = array();
            if (count($addedBY) > 0) {
                $UsersData = $this->job_model->get_val("select * from admin_master where id in (" . implode($addedBY, ',') . ")");
            }
            $dataForPayment = array();
            $data['branchName'] = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1, "id" => $data['branch']), array("id", "asc"));
            $branch_name = $data['branchName'][0]["branch_name"];
        /*    $data["online_payment"] = $this->user_model->get_val("SELECT SUM(`job_master`.`price`) AS price,SUM(job_master.payable_amount) as payable_amount FROM `job_master` INNER JOIN `payment` ON `payment`.`job_fk`=`job_master`.`id` AND `job_master`.`price`=`payment`.`amount` WHERE  job_master.model_type=1 and  `job_master`.`status`!='0' AND `payment`.`status`='success' AND DATE_FORMAT(job_master.`date`, '%Y-%m-%d') = STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  ) and  `job_master`.`branch_fk`='" . $data['branch'] . "'");
		*/
		$data["online_payment"] = $this->user_model->get_val("SELECT  SUM(`job_master`.`price`) AS price,SUM(job_master.payable_amount) as payable_amount  FROM `job_master`  WHERE  job_master.model_type=1 and  `job_master`.`status`!='0'  AND DATE_FORMAT(job_master.`date`, '%Y-%m-%d') = STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  ) and  `job_master`.`branch_fk`='" . $data['branch'] . "'  AND ISNULL( job_master.`phlebo_added`) AND ISNULL( job_master.`added_by`) ");
			/* if($data["login_data"]['id']=="12"){
				 echo $this->db->last_query();
				print_R($data['online_payment']);
			die();
			
			}*/
			

            //echo $this->db->last_query(); ///die();
            if ($data["online_payment"][0]["price"] != "") {
                $u = array('username' => array("name" => "Online"), "type" => "online");
                $u["branch_name"] = $branch_name;
                $u['cash'] = 00;
                $u['gross_amount'] = $data["online_payment"][0]["price"];
                $u['discount'] = 00;
                $u['price'] = $data["online_payment"][0]["price"];
                $u['payable_amount'] = $data["online_payment"][0]["payable_amount"];
                $cash = 00;
                $u['cash_total'] = 00;
                $u['cash_ttt'] = $payments;
                $u['other_total'] = 00;
                $u['credit_total'] = 00;
                $u['creditor_total'] = 0.0;
                $u['cheque_total'] = 00;
                $u['same_day'] = 00; //($sameday[0]['price']!="")?$sameday[0]['price']:00;
                $u['back_day'] = 00; ///($backday[0]['price']!="")?$backday[0]['price']:00;;
                $u['net'] = 00;
                // print_r($data["online_payment"]);die();
                $dataForPayment[] = $u;
            }
            foreach ($UsersData as $user) {
                $u = array('username' => $user, "type" => "user");
                $u["branch_name"] = $branch_name;
                /* 		$u['cash']=
                  $this->job_model->get_val("SELECT SUM(c.amount) as 'CASH' FROM job_master_receiv_amount c LEFT JOIN job_master jb ON jb.id = c.job_fk WHERE (c.added_by='".$user['id']."') AND c.payment_type='CASH' AND DATE_FORMAT(c.`createddate`,'%Y-%m-%d')= STR_TO_DATE(    '".$data['start_date']."',    '%d/%m/%Y'  ) AND c.status='1' AND jb.branch_fk=".$data['branch']."");
                 */
                //$u["cash"]=($u["cash"][0]['CASH']=="")?"00":$u["cash"][0]['CASH'];
                $payments = $this->job_model->get_val("SELECT  c.payment_type , SUM(c.amount)  AS amount FROM   job_master_receiv_amount c   LEFT JOIN job_master jb     ON jb.id = c.job_fk WHERE  jb.`status`!=0 AND    c.added_by = '" . $user['id'] . "'   AND DATE_FORMAT(c.`createddate`, '%Y-%m-%d') =STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  )   AND c.status = '1'  and jb.model_type=1    AND jb.branch_fk = " . $data['branch'] . "   GROUP BY  c.payment_type ");
                //echo $this->db->last_query()."<br>";
                $u['JOBS'] = $this->job_model->get_val("SELECT SUM(job_master.`price`) AS price,SUM(job_master.`price`*job_master.`discount`/100) AS discount,SUM(job_master.payable_amount) as payable_amount FROM job_master WHERE
				 job_master.model_type=1 and  job_master.branch_fk =" . $data['branch'] . " and  job_master.`added_by` =  '" . $user['id'] . "' AND DATE_FORMAT(job_master.`date`,'%Y-%m-%d')=STR_TO_DATE('" . $data['start_date'] . "',    '%d/%m/%Y')");
                //echo $this->db->last_query()."<br>";
                // echo "SELECT SUM(debit) AS debit,SUM(credit) AS credit FROM `creditors_balance` WHERE `status`='1' AND DATE_FORMAT(created_date, '%Y-%m-%d') = STR_TO_DATE('" . $data['start_date'] . "', '%d/%m/%Y') AND `created_by`='" . $user['id'] . "'";
                //die();
                $u["creditors_add"] = $this->user_model->get_val("SELECT SUM(debit) AS debit,SUM(credit) AS credit FROM `creditors_balance` WHERE `status`='1' AND DATE_FORMAT(created_date, '%Y-%m-%d') = STR_TO_DATE('" . $data['start_date'] . "', '%d/%m/%Y') AND `created_by`='" . $user['id'] . "'");

                $u['samedaytest'] = $sameday = $this->job_model->get_val("SELECT   SUM(s.amount)  AS price FROM  job_master_receiv_amount s JOIN job_master jbs   ON jbs.id = s.job_fk WHERE  jbs.model_type=1 and  s.added_by =  '" . $user['id'] . "' and s.payment_type!='CREDITORS'  AND jbs.branch_fk =" . $data['branch'] . "  AND DATE_FORMAT( s.createddate , '%d/%m/%Y') = '" . $data['start_date'] . "'   AND DATE_FORMAT(jbs.date  , '%d/%m/%Y') = '" . $data['start_date'] . "'   AND s.status = '1' ");
                $u['backdaytest'] = $backday = $this->job_model->get_val("SELECT   SUM(s.amount)  AS price FROM  job_master_receiv_amount s JOIN job_master jbs   ON jbs.id = s.job_fk WHERE jbs.model_type=1 and s.added_by =  '" . $user['id'] . "'   AND jbs.branch_fk =" . $data['branch'] . "  AND DATE_FORMAT( s.createddate , '%d/%m/%Y') = '" . $data['start_date'] . "'   AND jbs.date   < STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  )   AND s.status = '1' ");
                $u['gross_amount'] = $u['JOBS'][0]["price"];
                $u['discount'] = $u['JOBS'][0]["discount"];
                $u['price'] = $u['JOBS'][0]["price"] - $u['JOBS'][0]["discount"];
                $u['payable_amount'] = $u['JOBS'][0]["payable_amount"];
                $cash = 00;
                $u['cash_total'] = 0.0;
                $u['cash_ttt'] = $payments;
                $u['other_total'] = 0.0;
                $u['credit_total'] = 0.0;
                $u['creditor_total'] = 0.0;
                $u['cheque_total'] = 0.0;
                $u['same_day'] = ($sameday[0]['price'] != "") ? $sameday[0]['price'] : 0.0;
                $u['back_day'] = ($backday[0]['price'] != "") ? $backday[0]['price'] : 0.0;
                $u['net'] = round($u['price'] - $u['same_day'], 2);
                $u['net'] = ((int) $u['net'] < 0) ? 0.0 : $u['net'];
                foreach ($payments as $p) {
                    if ($p['payment_type'] == "CASH") {
                        $u['cash_total'] = $p['amount'];
                    } else
                    if ($p['payment_type'] == "CHEQUE") {
                        $u['cheque_total'] += $p['amount'];
                    } else if (in_array($p['payment_type'], array("CREDIT CARD", "CREDIT CARD swiped thru ICICI", 'WALLET CREDIT CARD swiped thru MSWIP', 'DEBIT CARD swiped thru ICICI', 'DEBIT CARD swiped thru MSWIP', 'Swipe thru HDFC', 'Swipe thru AXIS', 'DEBIT CARD'))) {
                        //(cr.payment_type='CREDIT CARD' or cr.payment_type='CREDIT CARD swiped thru ICICI' or cr.payment_type='CREDIT CARD swiped thru MSWIP' or cr.payment_type='DEBIT CARD swiped thru ICICI' or cr.payment_type='DEBIT CARD swiped thru MSWIP' or cr.payment_type='Swipe thru HDFC' or cr.payment_type='Swipe thru AXIS' or cr.payment_type='DEBIT CARD')
                        $u['credit_total'] += $p['amount'];
                    } else if (in_array($p['payment_type'], array("CREDITORS"))) {
                        $u['creditor_total'] += $p['amount'];
                    } else {
                        $u['other_total'] += $p['amount'];
                    }
                }
                

                $dataForPayment[] = $u;
            }

             if(!empty($data['branch'])){
            $phleboid = $this->job_model->get_val("SELECT  DISTINCT( job_master.`phlebo_added`) FROM job_master WHERE !ISNULL(job_master.`phlebo_added`) AND (job_master.`phlebo_added`)!='0' AND job_master.model_type=1 and job_master.`branch_fk`='" . $data['branch'] . "'  AND DATE_FORMAT(job_master.`date`, '%Y-%m-%d') = STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  )");
            $phleboid += $this->job_model->get_val("SELECT   DISTINCT(job_master_receiv_amount.`phlebo_fk` ) FROM  `job_master_receiv_amount`   LEFT JOIN job_master jb     ON jb.id = job_master_receiv_amount.job_fk  WHERE ! ISNULL(job_master_receiv_amount.phlebo_fk)   AND jb.model_type=1 and   job_master_receiv_amount.status != 0 AND DATE_FORMAT( job_master_receiv_amount.`createddate`,'%d/%m/%Y')= '" . $data['start_date'] . "' and jb.branch_fk='" . $data['branch'] . "'");
            $addedBY = array();
            foreach ($phleboid as $o) {
                if ($o['phlebo_added'] != "") {
                    $addedBY[] = $o['phlebo_added'];
                }
            }
            }
            /* print_r( $addedBY);
              echo implode(  $addedBY,',' );
              echo "select * from admin_user where id in (".implode(  $data['userlistAddedBy'],',' ).")";
             */
            $UsersData = array();
            if (count($addedBY) > 0) {
                $UsersData = $this->job_model->get_val("SELECT * FROM  `phlebo_master`  where id in (" . implode($addedBY, ',') . ")");
            }
            foreach ($UsersData as $user) {
                $u = array('username' => $user, "type" => "phlebo");
                $u["branch_name"] = $branch_name;
                $u['cash'] = $this->job_model->get_val("SELECT SUM(c.amount) as 'CASH' FROM job_master_receiv_amount c LEFT JOIN job_master jb ON jb.id = c.job_fk WHERE jb.`status`!=0 AND  (c.phlebo_fk='" . $user['id'] . "') AND  jb.model_type=1 and  c.payment_type='CASH' AND DATE_FORMAT(c.`createddate`,'%Y-%m-%d')= STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  ) AND c.status='1' AND jb.branch_fk=" . $data['branch'] . "");
                //$u["cash"]=($u["cash"][0]['CASH']=="")?"00":$u["cash"][0]['CASH'];
                $payments = $this->job_model->get_val("SELECT  c.payment_type , SUM(c.amount)  AS amount FROM   job_master_receiv_amount c   LEFT JOIN job_master jb     ON jb.id = c.job_fk WHERE  jb.`status`!=0 AND    c.phlebo_fk = '" . $user['id'] . "'   AND DATE_FORMAT(c.`createddate`, '%Y-%m-%d') =STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  )   AND c.status = '1'  and jb.model_type=1  AND jb.branch_fk = " . $data['branch'] . "   GROUP BY  c.payment_type ");
                //echo $this->db->last_query()."<br>";
                $u['JOBS'] = $this->job_model->get_val("SELECT SUM(job_master.`price`) AS price,SUM(job_master.`price`*job_master.`discount`/100) AS discount,SUM(job_master.payable_amount) as payable_amount FROM job_master WHERE
				job_master.branch_fk =" . $data['branch'] . " and job_master.model_type=1 and   job_master.`phlebo_added` =  '" . $user['id'] . "' AND DATE_FORMAT(job_master.`date`,'%Y-%m-%d')=STR_TO_DATE('" . $data['start_date'] . "',    '%d/%m/%Y')");
                //echo $this->db->last_query()."<br>";
                $u['samedaytest'] = $sameday = $this->job_model->get_val("SELECT   SUM(s.amount)  AS price FROM  job_master_receiv_amount s JOIN job_master jbs   ON jbs.id = s.job_fk WHERE jbs.model_type=1 and  s.phlebo_fk =  '" . $user['id'] . "'  and s.payment_type!='CREDITORS' AND jbs.branch_fk =" . $data['branch'] . "  AND DATE_FORMAT( s.createddate , '%d/%m/%Y') = '" . $data['start_date'] . "'   AND DATE_FORMAT(jbs.date  , '%d/%m/%Y') = '" . $data['start_date'] . "'   AND s.status = '1' ");
                $u['backdaytest'] = $backday = $this->job_model->get_val("SELECT   SUM(s.amount)  AS price FROM  job_master_receiv_amount s JOIN job_master jbs   ON jbs.id = s.job_fk WHERE  jbs.model_type=1 and  s.phlebo_fk =  '" . $user['id'] . "'   AND jbs.branch_fk =" . $data['branch'] . "  AND DATE_FORMAT( s.createddate , '%d/%m/%Y') = '" . $data['start_date'] . "'   AND jbs.date   < STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  )   AND s.status = '1' ");
                $u['gross_amount'] = $u['JOBS'][0]["price"];
                $u['discount'] = $u['JOBS'][0]["discount"];
                $u['price'] = $u['JOBS'][0]["price"] - $u['JOBS'][0]["discount"];
                $u['payable_amount'] = $u['JOBS'][0]["payable_amount"];
                $cash = 00;
                $u['cash_total'] = 0.0;
                $u['other_total'] = 0.0;
                $u['credit_total'] = 0.0;
                $u['creditor_total'] = 0.0;
                $u['cheque_total'] = 0.0;
                $u['same_day'] = ($sameday[0]['price'] != "") ? $sameday[0]['price'] : 0.0;
                $u['back_day'] = ($backday[0]['price'] != "") ? $backday[0]['price'] : 0.0;
                //$u['net']=$u['price']-$u['same_day'];
                $u['net'] = round($u['price'] - $u['same_day'], 2);
                $u['net'] = ((int) $u['net'] < 0) ? 0.0 : $u['net'];
                foreach ($payments as $p) {
                    if ($p['payment_type'] == "CASH") {
                        $u['cash_total'] = $p['amount'];
                    } else
                    if ($p['payment_type'] == "CHEQUE") {
                        $u['cheque_total'] += $p['amount'];
                    } else if (in_array($u['payment_type'], array('CREDIT CARD', 'CREDIT CARD swiped thru ICICI', 'WALLET CREDIT CARD swiped thru MSWIP', 'DEBIT CARD swiped thru ICICI', 'DEBIT CARD swiped thru MSWIP', 'Swipe thru HDFC', 'Swipe thru AXIS', 'DEBIT CARD'))) {
                        //(cr.payment_type='CREDIT CARD' or cr.payment_type='CREDIT CARD swiped thru ICICI' or cr.payment_type='CREDIT CARD swiped thru MSWIP' or cr.payment_type='DEBIT CARD swiped thru ICICI' or cr.payment_type='DEBIT CARD swiped thru MSWIP' or cr.payment_type='Swipe thru HDFC' or cr.payment_type='Swipe thru AXIS' or cr.payment_type='DEBIT CARD')
                        $u['credit_total'] += $p['amount'];
                    } else if (in_array($p['payment_type'], array("CREDITORS"))) {
                        $u['creditor_total'] += $p['amount'];
                    } else {
                        $u['other_total'] += $p['amount'];
                    }
                }
                $dataForPayment[] = $u;
            }
            $BrnachDailyCollectionData[] = array("branch" => $b, "branch_name" => $branch_name, "dailyCollectionData" => $dataForPayment);
        }
        $dataforcollection = array();
        //echo "<prE>"; print_r($BrnachDailyCollectionData); die();
        return $BrnachDailyCollectionData;
    }

    function branchpaymentnew2() {

        if (!is_loggedin()) {
            redirect('login');
        }
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["login_data"] = logindata();
        if ($data["login_data"]['type'] == 2) {
            //   redirect('Admin/Telecaller');
        }
        $data['city_list'] = $this->user_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
        $data['branch_list'] = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1), array("id", "asc"));

        $data["login_data"] = logindata();
        $data['start_date'] = $this->input->get("start_date");
        //$data['end_date'] = $this->input->get("end_date");
        $data['branch'] = $this->input->get("branch");
        $data['city'] = $this->input->get("city");

        if ($this->input->get("search") != "") {
            $city_branch = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1, "city" => $data['city']), array("id", "asc"));
            if (empty($data['branch'])) {
                $user_branch = $this->user_model->master_fun_get_tbl_val("user_branch", array('status' => 1, "user_fk" => $data["login_data"]["id"]), array("id", "asc"));
                $user_assign_branch = array();
                foreach ($user_branch as $ukey) {
                    $user_assign_branch[] = $ukey["branch_fk"];
                }
                $branch = array();
                foreach ($city_branch as $b) {
                    if (!empty($user_assign_branch) && in_array($data["login_data"]["type"], array("5", "6", "7"))) {
                        //print_r($key["branch_fk"]); die($key);
                        if (in_array($b['id'], $user_assign_branch)) {
                            $branch[] = $b['id'];
                        }
                    } else {
                        $branch[] = $b['id'];
                    }
                }
            } 
            if ($this->input->get("branch") != '') {
                $branch = array($data['branch']);
            }

            $data["dailyCollectionData"] = $this->getDailyCollectionData($data['start_date'], $branch);
        }
        if ($_REQUEST["debug"] == 1) {
            echo "<pre>";
            print_r($data["dailyCollectionData"]);
            die();
        }
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('branch_payment_report2', $data);
        $this->load->view('footer');
    }

    function branchpayment() {
        if (!is_loggedin()) {
            redirect('login');
        }
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["login_data"] = logindata();
        if ($data["login_data"]['type'] == 2) {
            //   redirect('Admin/Telecaller');
        }

        $data["login_data"] = logindata();
        $data['start_date'] = $this->input->get("start_date");
        //$data['end_date'] = $this->input->get("end_date");
        $data['branch'] = $this->input->get("branch");
        $data['city'] = $this->input->get("city");
        $user_branch = $this->user_model->master_fun_get_tbl_val("user_branch", array('status' => 1, "user_fk" => $data["login_data"]['id']), array("id", "asc"));
        $branch = array();
        foreach ($user_branch as $key1) {
            $branch[] = $key1["branch_fk"];
        }
        $data['branchs'] = $branch;
        $start_date = null;
        $end_date = null;
        if ($data['start_date'] != "") {
            $start_date = $data['start_date'];
        }

//        if ($data['end_date'] != "") {
//            $end_date = $data['end_date'];
//        }
        $date1 = explode("/", $data['start_date']);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $qry = "";
        if (!empty($data['branch'])) {
            $qry .= " AND `job_master`.`branch_fk`='" . $data['branch'] . "'";
        }
        if (!empty($data['city'])) {
            $qry .= " AND job_master.test_city='" . $data['city'] . "'";
        }
        //echo "SELECT `job_master`.* FROM `job_master` INNER JOIN `payment` ON `payment`.`job_fk`=`job_master`.`id` AND `job_master`.`price`=`payment`.`amount` WHERE `job_master`.`status`!='0' AND `payment`.`status`='success' AND `job_master`.`date`>='" . $sd . " 00:00:00' AND `job_master`.`date`<='" . $sd . " 23:59:59' " . $qry; die();
        $data["online_payment"] = $this->user_model->get_val("SELECT `job_master`.`branch_fk`,SUM(`job_master`.`price`) AS price FROM `job_master` INNER JOIN `payment` ON `payment`.`job_fk`=`job_master`.`id` AND `job_master`.`price`=`payment`.`amount` WHERE `job_master`.`status`!='0' AND `payment`.`status`='success' AND `job_master`.`date`>='" . $sd . " 00:00:00' AND `job_master`.`date`<='" . $sd . " 23:59:59' " . $qry . " GROUP BY `job_master`.`branch_fk`");
        $data["creditor_payment"] = $this->user_model->get_val("SELECT `job_master`.`branch_fk`,SUM(`job_master`.`price`) AS price FROM `job_master` INNER JOIN `payment` ON `payment`.`job_fk`=`job_master`.`id` AND `job_master`.`price`=`payment`.`amount` WHERE `job_master`.`status`!='0' AND `payment`.`status`='success' AND `job_master`.`date`>='" . $sd . " 00:00:00' AND `job_master`.`date`<='" . $sd . " 23:59:59' " . $qry . " GROUP BY `job_master`.`branch_fk`");
        //print_r($data["online_payment"]);
        //die();

        $data['city_list'] = $this->user_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
        $data['branch_list'] = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1), array("id", "asc"));
        //$data['payment_type'] = $this->user_model->master_fun_get_tbl_val("payment_type_master", array('status' => 1), array("id", "asc"));
        $data['collecting_amount_branch'] = $this->job_report_model->branchPaymentReport($start_date, $data['branch'], $data['branchs'], $data['city']);

        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('branch_payment_report', $data);
        $this->load->view('footer');
    }

    function branchpayment_details() {
        if (!is_loggedin()) {
            redirect('login');
        }
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["login_data"] = logindata();
        if ($data["login_data"]['type'] == 3 || $data["login_data"]['type'] == 4) {
            redirect('Logistic/dashboard');
        }
        $data['branchlist'] = $this->job_model->get_val("SELECT * from branch_master where status='1'");

        $data['start_date'] = $this->input->get("start_date");
        $data['user'] = $this->input->get("user");
        $data['phlebo'] = $this->input->get("phlebo");
        $data['types'] = $this->input->get("type");

        $user_branch = $this->user_model->master_fun_get_tbl_val("user_branch", array('status' => 1, "user_fk" => $data['user']), array("id", "asc"));
        $data['branch_list'] = $user_branch;
        $branch = array();
        foreach ($user_branch as $key1) {
            $branch[] = $key1["branch_fk"];
        }
        $start_date = null;
        if ($data['start_date'] != "") {
            $start_date = $data['start_date'];
        }
        $data['payment_type'] = $this->user_model->master_fun_get_tbl_val("payment_type_master", array('status' => 1), array("id", "asc"));
        $data['collecting_amount_branch'] = $this->job_report_model->getPaymentReport_details($branch, $start_date, ucwords(strtolower($data['types'])), $data['user'], $data['phlebo']);
		if(isset($_REQUEST['request'])){
			echo $this->db->last_query();
			die();
		}
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('branchpayment_details', $data);
        $this->load->view('footer');
    }

    function branchpayment_details_online() {
        if (!is_loggedin()) {
            redirect('login');
        }
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["login_data"] = logindata();
        if ($data["login_data"]['type'] == 3 || $data["login_data"]['type'] == 4) {
            redirect('Logistic/dashboard');
        }
        $data['branchlist'] = $this->job_model->get_val("SELECT * from branch_master where status='1'");

        $data['start_date'] = $this->input->get("start_date");
        $data['user'] = $this->input->get("user");
        $data['phlebo'] = $this->input->get("phlebo");
        $data['types'] = $this->input->get("type");
        $data['branch'] = $this->input->get("branch");

        $user_branch = $this->user_model->master_fun_get_tbl_val("user_branch", array('status' => 1, "user_fk" => $data['user']), array("id", "asc"));
        $data['branch_list'] = $user_branch;
        $branch = array();
        foreach ($user_branch as $key1) {
            $branch[] = $key1["branch_fk"];
        }
        $start_date = null;
        if ($data['start_date'] != "") {
            $start_date = $data['start_date'];
        }
        $date1 = explode("/", $data['start_date']);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $qry = "";
        if (!empty($data['branch'])) {
            $qry .= " AND `job_master`.`branch_fk`='" . $data['branch'] . "'";
        }
        if (!empty($data['city'])) {
            $qry .= " AND job_master.test_city='" . $data['city'] . "'";
        }
        //echo "SELECT `job_master`.*,payment.`amount` FROM `job_master` INNER JOIN `payment` ON `payment`.`job_fk`=`job_master`.`id` AND `job_master`.`price`=`payment`.`amount` WHERE `job_master`.`status`!='0' AND `payment`.`status`='success' AND `job_master`.`date`>='" . $sd . " 00:00:00' AND `job_master`.`date`<='" . $sd . " 23:59:59' " . $qry; die();
        $data["online_payment"] = $this->user_model->get_val("SELECT `job_master`.*,payment.`amount`, IF(booking_info.`family_member_fk`>0,`customer_family_master`.`name`,`customer_master`.`full_name`) AS `patient_name` FROM `job_master` INNER JOIN `customer_master` ON `customer_master`.`id`=`job_master`.`cust_fk` JOIN `booking_info` ON `booking_info`.`id`=`job_master`.`booking_info` JOIN `customer_family_master` ON `customer_family_master`.`id`=`booking_info`.`family_member_fk` INNER JOIN `payment` ON `payment`.`job_fk`=`job_master`.`id` AND `job_master`.`price`=`payment`.`amount` WHERE `job_master`.`status`!='0' AND `payment`.`status`='success' AND `job_master`.`date`>='" . $sd . " 00:00:00' AND `job_master`.`date`<='" . $sd . " 23:59:59' " . $qry);
        //print_r($data["online_payment"]);
        //die();        
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('branchpayment_details_online', $data);
        $this->load->view('footer');
    }

    function job_export() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $start = $this->input->get("start_date");
        $end = $this->input->get("end_date");
        $city = $this->input->get("city");
        $type = $this->input->get("type");
        $date1 = explode("/", $start);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $end);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "Job_payment_report.csv";
        $qry = "SELECT
  b.branch_name as `Branch`,
  if(isnull(jmr.`phlebo_fk`),am.`name`,pm.`name`) as `Collected By`,
 
  if(isnull(jmr.payment_type),'Payu Money' ,jmr.payment_type) as `Received Type`,
  SUM(jmr.amount) as `Amount`
FROM
  job_master jm 
 left JOIN `job_master_receiv_amount` jmr 
    ON jm.id = jmr.`job_fk` 
  left JOIN `admin_master` am 
    ON jmr.added_by = am.`id` 
left JOIN `phlebo_master` pm 
    ON pm.`id`= jmr.`phlebo_fk`
   JOIN branch_master b 
    ON b.id = jm.branch_fk 
    join test_cities t
    on t.id = b.city
 
WHERE   jm.`status` != '0' and jmr.status = '1' and jm.`model_type`='1'  ";
        $qry .= " AND jmr.createddate >= '" . $start_date . "' AND jmr.createddate <= '" . $end_date . "'";
        if ($city != "") {
            $qry .= " AND t.id  = '" . $city . "' ";
        }
        if ($type != '') {
            $qry .= " AND jmr.payment_type  = '" . $type . "' ";
        }

        $qry .= " group by b.id,if(isnull(jmr.`phlebo_fk`),concat(am.`id`,'a'),concat(pm.`id`,'p')),jmr.payment_type order by b.`id`,if(isnull(jmr.`phlebo_fk`),concat(am.`id`,'a'),concat(pm.`id`,'p'))  ASC";
        $result = $this->db->query($qry);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }

    function daily_export() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $start = $this->input->get("start_date");
        $city = $this->input->get("city");
        $branch = $this->input->get("branch");
        $date1 = explode("/", $start);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $bd = date('Y-m-d', strtotime($sd . ' -1 day'));
        $back_date = $bd . " 00:00:00";
        $back_date1 = $bd . " 23:59:59";
        $start_date = $sd . " 00:00:00";
        $end_date = $sd . " 23:59:59";
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "Daily_report_" . $start . ".csv";
        $qry = "SELECT 
  b.branch_name as `Branch`,
  IF(
    `jm`.`phlebo_added` != '',
    CONCAT(pm.`name`,'','(Phlebotomy)'),`am`.`name`
  ) AS `Received By`,
  (SELECT SUM(Round(jb1.`price`)) from job_master jb1 where (jb1.added_by=am.id OR jb1.phlebo_added=pm.id) AND jb1.branch_fk = jm.`branch_fk` and jb1.`status` != '0' AND jb1.date >= '" . $start_date . "' AND jb1.date <= '" . $end_date . "') as `Gross Amt`,
      (SELECT SUM(Round((jb2.`discount` * jb2.`price`) / 100)) from job_master jb2 where (jb2.added_by=am.id OR jb2.phlebo_added=pm.id) AND jb2.branch_fk = jm.`branch_fk` and jb2.`status` != '0' AND jb2.date >= '" . $start_date . "' AND jb2.date <= '" . $end_date . "') as `Discount`,
          (SELECT SUM(Round(if(jb2.`price` != 'NULL',jb2.`price`,0) - ((if(jb2.`discount` != 'NULL',jb2.`discount`,0) * if(jb2.`price` != 'NULL',jb2.`price`,0)) / 100))) from job_master jb2 where (jb2.added_by=am.id OR jb2.phlebo_added=pm.id) AND jb2.branch_fk = jm.`branch_fk` and jb2.`status` != '0' AND jb2.date >= '" . $start_date . "' AND jb2.date <= '" . $end_date . "') as `Net Amt`,
    (SELECT SUM(c.amount) from job_master_receiv_amount c LEFT JOIN job_master jb ON jb.id = c.job_fk where (c.added_by=am.id or c.phlebo_fk=pm.id) AND jb.branch_fk = jm.`branch_fk` and c.payment_type='CASH' AND c.createddate >= '" . $start_date . "' AND c.status='1' AND c.createddate <= '" . $end_date . "') as `Cash`,
        (SELECT SUM(ch.amount) from job_master_receiv_amount ch LEFT JOIN job_master jb ON jb.id = ch.job_fk where (ch.added_by=am.id or ch.phlebo_fk=pm.id) AND jb.branch_fk = jm.`branch_fk` and ch.payment_type='CHEQUE' AND ch.createddate >= '" . $start_date . "' AND ch.status='1' AND ch.createddate <= '" . $end_date . "') as `Cheque`,
            (SELECT SUM(cr.amount) from job_master_receiv_amount cr LEFT JOIN job_master jb ON jb.id = cr.job_fk where (cr.added_by=am.id or cr.phlebo_fk=pm.id) AND jb.branch_fk = jm.`branch_fk` and (cr.payment_type='CREDIT CARD' or cr.payment_type='CREDIT CARD swiped thru ICICI' or cr.payment_type='CREDIT CARD swiped thru MSWIP' or cr.payment_type='DEBIT CARD swiped thru ICICI' or cr.payment_type='DEBIT CARD swiped thru MSWIP' or cr.payment_type='Swipe thru HDFC' or cr.payment_type='Swipe thru AXIS' or cr.payment_type='DEBIT CARD') AND cr.createddate >= '" . $start_date . "' AND cr.status='1' AND cr.createddate <= '" . $end_date . "') as `Credit/Debit`,
                (SELECT SUM(cr.amount) from job_master_receiv_amount cr LEFT JOIN job_master jb ON jb.id = cr.job_fk where (cr.added_by=am.id or cr.phlebo_fk=pm.id) AND jb.branch_fk = jm.`branch_fk` and (cr.payment_type='ONLINE' or cr.payment_type='SALARY ACCOUNT' or cr.payment_type='WALLET DEBIT' or cr.payment_type='WEB ONLINE' or cr.payment_type='PayTm') AND cr.createddate >= '" . $start_date . "' AND cr.status='1' AND cr.createddate <= '" . $end_date . "') as `Other Credit`,
                    (select SUM(s.amount) from job_master_receiv_amount s join job_master jbs on jbs.id=s.job_fk where (s.added_by=am.id or s.phlebo_fk=pm.id) AND jbs.branch_fk = jm.`branch_fk` and s.createddate >='" . $start_date . "' and s.createddate <='" . $end_date . "' and s.status='1' AND jbs.date >='" . $start_date . "' AND jbs.date <='" . $end_date . "') as `Same Day`,
                        (select SUM(b.amount) from job_master_receiv_amount b join job_master jbc on jbc.id=b.job_fk where (b.added_by=am.id or b.phlebo_fk=pm.id) AND jbc.branch_fk = jm.`branch_fk` and b.createddate<='" . $end_date . "' and b.createddate>='" . $start_date . "' and b.status='1' AND jbc.date <='" . $back_date1 . "') as `Back Day`
FROM
  job_master jm 
LEFT JOIN `job_master_receiv_amount` jmr 
    ON jm.id = jmr.`job_fk`
 LEFT JOIN `admin_master` am 
    ON jm.added_by = am.`id` 
    LEFT JOIN `phlebo_master` pm
    ON jm.phlebo_added = pm.id
  JOIN branch_master b 
    ON b.id = jm.branch_fk 
   join test_cities tc 
    on tc.id = b.city
 
WHERE   jm.`status` != '0' and jmr.status = '1' and jm.`model_type`='1'  ";

        $date1 = explode("/", $start);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $sd . " 23:59:59";
        $qry .= " AND jmr.createddate >= '" . $start_date . "' AND jmr.createddate <= '" . $end_date . "'";
        if (!empty($branch)) {
            $qry .= " AND b.id  = '" . $branch . "'";
        }
        if (!empty($city)) {
            $qry .= " AND tc.id  = '" . $city . "'";
        }
        $qry .= " group by jm.branch_fk,jm.`added_by`,jm.`phlebo_added` order by b.`id` ASC";
        $result = $this->db->query($qry);


        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);




        force_download($filename, $data);
    }

    function daily_export2() {

        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $start = $this->input->get("start_date");
        $city = $this->input->get("city");
        $branch = $this->input->get("branch");
        $date1 = explode("/", $start);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $bd = date('Y-m-d', strtotime($sd . ' -1 day'));
        $back_date = $bd . " 00:00:00";
        $back_date1 = $bd . " 23:59:59";
        $start_date = $sd . " 00:00:00";
        $end_date = $sd . " 23:59:59";
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "Daily_report_" . $start . ".csv";
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["login_data"] = logindata();
        if ($data["login_data"]['type'] == 2) {
            //   redirect('Admin/Telecaller');
        }


        $city_branch = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1, "city" => $city), array("id", "asc"));
        $branch = array();

        //print_R($data["login_data"]['branch_fk']);
        foreach ($city_branch as $b) {
            if (!empty($data["login_data"]['branch_fk'])) {
                foreach ($data["login_data"]['branch_fk'] as $key) {

                    if ($key["branch_fk"] == $b['id']) {
                        $branch[] = $b['id'];
                    }
                }
            } else {
                $branch[] = $b['id'];
            }
        }
        if ($this->input->get("branch") != '') {
            $branch = array($this->input->get("branch"));
        }else{
			
		}

		
        $data["dailyCollectionData"] = $this->getDailyCollectionData($start, ($branch));
        if(isset($_REQUEST['debug'])){
		echo "<pre>";
		print_R($data["dailyCollectionData"] );
		print_R($branch);
		die();
		}
		$output = array();
        $output[] = array("Branch" => "Branch", "Received By" => "Received By ", "Gross Amt" => "Gross Amt", "Discount" => "Discount", "Net Amt" => "Net Amt", "Cash" => "Cash", "Cheque" => "Cheque", "Credit/Debit" => "Credit/Debit", "Other Credit" => "Other Credit", "Same Day" => "Same Day", "Back Day" => "Back Day", "Creditor Total" => "Creditor Total", "Creditor Due" => "Creditor Due", "Net" => "Net");

        foreach ($data["dailyCollectionData"] as $branchData) {
            foreach ($branchData['dailyCollectionData'] as $am_br) {

                /* $u['gross_amount']=$u['JOBS'][0]["price"];
                  $u['discount']=$u['JOBS'][0]["discount"];
                  $u['price']=$u['JOBS'][0]["price"]-$u['JOBS'][0]["discount"];
                  $cash=00;
                  $u['cash']=00;
                  $u['other_total']=00;
                  $u['credit_total']=00;
                  $u['cheque_total']=00;
                  $u['same_day']=($sameday[0]['price']!="")?$sameday[0]['price']:00;
                  $u['back_day']=($backday[0]['price']!="")?$backday[0]['price']:00;;
                  $u['net']=$u['price']-$u['same_day']; */
				  $crd_total=0.0;
				  $crd_due=0.0;
                $crd_total = round($am_br["creditor_total"]);
                $crd_due = round($am_br["creditors_add"][0]["credit"]);
                $new_net += abs(($crd_total - $am_br["net"]) + $crd_due);

                $output[] = array("Branch" => $branchData['branch_name'], "Received By" => $am_br['username']['name'], "Gross Amt" => ($am_br["gross_amount"] == "") ? "00" : round($am_br["gross_amount"]), "Discount" => ($am_br["discount"] == "") ? "00" : round($am_br["discount"]), "Net Amt" => ($am_br["price"] == "") ? "00" : round($am_br["price"]), "cash" => ($am_br["cash_total"] == "") ? "00" : round($am_br["cash_total"]), "Cheque" => ($am_br["cheque_total"] == "") ? "00" : round($am_br["cheque_total"]), "Credit/Debit" => ($am_br["credit_total"] == "") ? "00" : round($am_br["credit_total"]), "Other Credit" => ($am_br["other_total"] == "") ? "00" : round($am_br["other_total"]), "Same Day" => ($am_br["same_day"] == "") ? "00" : round($am_br["same_day"]), "Back Day" => ($am_br["back_day"] == "") ? "00" : round($am_br["back_day"]), "Creditor Total" => ($crd_total == "") ? "00" : round($crd_total), "Creditor Due" => ($crd_due == "") ? "00" : round($crd_due), "Net" => ($am_br["payable_amount"] == "") ? "00" : round($am_br["payable_amount"],2));
            }
        }

        $data[] = array('x' => "21212", 'y' => "2121", 'z' => "2121", 'a' => "1212");
        //     $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        //force_download($filename, $data); header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Daily_Collection_report_$start" . ".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        $handle = fopen('php://output', 'w');

        foreach ($output as $data) {
            fputcsv($handle, $data);
        }
        fclose($handle);
        exit;
    }

    // public function Tat_report() {
    //     if (!is_loggedin()) {
    //         redirect('login');
    //     }

    //     $this->load->library('pagination');
    //     $data["login_data"] = logindata();
    //     $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

    //     $from_date = $this->input->get('from_date');
    //     $to_date   = $this->input->get('to_date');

    //     if (empty($from_date) || empty($to_date)) {
    //         $today = date('Y-m-d');
    //         $from_date = $today . ' 00:00:00';
    //         $to_date   = $today . ' 23:59:59';
    //         $from_date_formatted = date('d/m/Y', strtotime($from_date));
    //         $to_date_formatted   = date('d/m/Y', strtotime($to_date));
    //     } else {
    //         $from_date_formatted = $from_date;
    //         $to_date_formatted   = $to_date;

    //         $from_date_parts = explode('/', $from_date);
    //         $to_date_parts   = explode('/', $to_date);

    //         $from_date = $from_date_parts[2] . '-' . $from_date_parts[1] . '-' . $from_date_parts[0] . ' 00:00:00';
    //         $to_date   = $to_date_parts[2] . '-' . $to_date_parts[1] . '-' . $to_date_parts[0] . ' 23:59:59';
    //     }

    //     // === PAGINATION CONFIG ===
    //     $whereCondition = "jm.status = 2 AND jl.job_status = '8-2' AND jm.date >= '$from_date' AND jm.date <= '$to_date'";
    //     $totalRows = $this->db->query("SELECT COUNT(*) AS total FROM job_master jm LEFT JOIN job_log jl ON jl.job_fk = jm.id WHERE $whereCondition")->row()->total;

    //     $config = array();
    //     $config["base_url"] = base_url() . "job_report/tat_report/";
    //     $config["total_rows"] = $totalRows;
    //     $config["per_page"] = 10;
    //     $config["uri_segment"] = 3;
    //     $config['next_link'] = 'Next &rsaquo;';
    //     $config['prev_link'] = '&lsaquo; Previous';

    //     $this->pagination->initialize($config);

    //     $page = $data["page"] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

    //     // === GET RESULTS WITH LIMIT ===
    //     $job_data = $this->user_model->get_val("
    //         SELECT jm.id, jm.date as start_date_time, jm.status, jl.job_status, jl.date_time as end_date_time 
    //         FROM job_master jm 
    //         LEFT JOIN job_log jl ON jl.job_fk = jm.id 
    //         WHERE $whereCondition 
    //         ORDER BY jm.date DESC 
    //         LIMIT {$config["per_page"]} OFFSET $page
    //     ");

    //     // === PROCESS TAT ===
    //     if (!empty($job_data)) {
    //         foreach ($job_data as $key => $value) {
    //             if (!empty($value['end_date_time'])) {
    //                 $start = new DateTime($value['start_date_time']);
    //                 $end = new DateTime($value['end_date_time']);
    //                 $interval = $start->diff($end);
    //                 $totalHours = ($interval->days * 24) + $interval->h;
    //                 $minutes = $interval->i;
    //                 $job_data[$key]['tat'] = "{$totalHours} Hours {$minutes} Minutes";
    //             } else {
    //                 $job_data[$key]['tat'] = 'N/A';
    //             }
    //         }
    //     }

    //     $data['query'] = $job_data;
    //     $data['from_date'] = $from_date_formatted;
    //     $data['to_date'] = $to_date_formatted;
    //     $data["links"] = $this->pagination->create_links();

    //     $this->load->view('header', $data);
    //     $this->load->view('nav', $data);
    //     $this->load->view('tat_report_new', $data);
    //     $this->load->view('footer');
    // }

    
    public function Tat_report() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $this->load->library('pagination');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        // === GET & FORMAT DATES ===
        $from_date = $this->input->get('from_date');
        $to_date   = $this->input->get('to_date');
        $added_by_type = $this->input->get('added_by_type');
        $added_by_condition = "";
        if ($added_by_type == 'phlebo') {
            $added_by_condition = " AND jm.phlebo_added IS NOT NULL AND jm.phlebo_added != '' ";
        } elseif ($added_by_type == 'user') {
            $added_by_condition = " AND jm.added_by IS NOT NULL AND jm.added_by != '' ";
        } elseif ($added_by_type == 'online') {
            $added_by_condition = " AND (jm.phlebo_added IS NULL OR jm.phlebo_added = '') AND (jm.added_by IS NULL OR jm.added_by = '') ";
        }


        if (empty($from_date) || empty($to_date)) {
            $today = date('Y-m-d');
            $from_date = $today . ' 00:00:00';
            $to_date   = $today . ' 23:59:59';
            $from_date_formatted = date('d/m/Y', strtotime($from_date));
            $to_date_formatted   = date('d/m/Y', strtotime($to_date));
        } else {
            $from_date_formatted = $from_date;
            $to_date_formatted   = $to_date;

            $from_date_parts = explode('/', $from_date);
            $to_date_parts   = explode('/', $to_date);

            $from_date = $from_date_parts[2] . '-' . $from_date_parts[1] . '-' . $from_date_parts[0] . ' 00:00:00';
            $to_date   = $to_date_parts[2] . '-' . $to_date_parts[1] . '-' . $to_date_parts[0] . ' 23:59:59';
        }

        // === COUNT TOTAL ROWS ===
        $whereCondition = "jm.status = 2 AND jl.job_status = '8-2' AND jm.date >= '$from_date' AND jm.date <= '$to_date' $added_by_condition";
        $totalRows = $this->db->query("
            SELECT COUNT(*) AS total 
            FROM job_master jm 
            LEFT JOIN job_log jl ON jl.job_fk = jm.id 
            WHERE $whereCondition
        ")->row()->total;

        // === PAGINATION CONFIG ===
        $config = array();
        $config["base_url"] = base_url() . "job_report/tat_report/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 50;
        $config["uri_segment"] = 3;
        $config['next_link'] = 'Next &rsaquo;';
        $config['prev_link'] = '&lsaquo; Previous';

        // === Preserve selected filters in pagination ===
        $query_string = '?from_date=' . urlencode($from_date_formatted) . '&to_date=' . urlencode($to_date_formatted) . '&added_by_type=' . urlencode($added_by_type);
        $config['suffix'] = $query_string;
        $config['first_url'] = $config['base_url'] . $query_string;

        $this->pagination->initialize($config);

        $page = $data["page"] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // === GET PAGINATED RESULTS ===
        $job_data = $this->user_model->get_val("
            SELECT jm.id, jm.date as start_date_time, jm.status, jl.job_status, jl.date_time as end_date_time,cm.full_name,jm.order_id,jm.status,phlebo_master.name as phlebo_added_by,admin_master.name AS added_by
            FROM job_master jm 
            LEFT JOIN job_log jl ON jl.job_fk = jm.id 
            LEFT JOIN customer_master cm ON cm.id = jm.cust_fk 
            left join phlebo_master on jm.phlebo_added=phlebo_master.id
            LEFT JOIN admin_master ON admin_master.id=jm.added_by
            WHERE $whereCondition 
            ORDER BY jm.date DESC 
            LIMIT {$config["per_page"]} OFFSET $page
        ");

        // === CALCULATE TAT ===
        if (!empty($job_data)) {
            foreach ($job_data as $key => $value) {
                if (!empty($value['end_date_time'])) {
                    $start = new DateTime($value['start_date_time']);
                    $end = new DateTime($value['end_date_time']);
                    $interval = $start->diff($end);
                    $totalHours = ($interval->days * 24) + $interval->h;
                    $minutes = $interval->i;
                    $job_data[$key]['tat'] = "{$totalHours} Hours {$minutes} Minutes";
                } else {
                    $job_data[$key]['tat'] = 'N/A';
                }
            }
        }
       
        $data['query'] = $job_data;
        $data['from_date'] = $from_date_formatted;
        $data['to_date'] = $to_date_formatted;
        $data['added_by_type'] = $added_by_type;
        $data["links"] = $this->pagination->create_links();

        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('tat_report_new', $data);
        $this->load->view('footer');
    }

    function export_tat_report(){
        if (!is_loggedin()) {
            redirect('login');
        }

        $from_date = $this->input->get('from_date');
        $to_date   = $this->input->get('to_date');
        $added_by_type = $this->input->get('added_by_type');

        $added_by_condition = "";
        if ($added_by_type == 'phlebo') {
            $added_by_condition = " AND jm.phlebo_added IS NOT NULL AND jm.phlebo_added != '' ";
        } elseif ($added_by_type == 'user') {
            $added_by_condition = " AND jm.added_by IS NOT NULL AND jm.added_by != '' ";
        } elseif ($added_by_type == 'online') {
            $added_by_condition = " AND (jm.phlebo_added IS NULL OR jm.phlebo_added = '') AND (jm.added_by IS NULL OR jm.added_by = '') ";
        }

        $from_date_parts = explode('/', $from_date);
        $to_date_parts   = explode('/', $to_date);

        $from_date_db = $from_date_parts[2] . '-' . $from_date_parts[1] . '-' . $from_date_parts[0] . ' 00:00:00';
        $to_date_db   = $to_date_parts[2] . '-' . $to_date_parts[1] . '-' . $to_date_parts[0] . ' 23:59:59';

        $whereCondition = "jm.status = 2 AND jl.job_status = '8-2' AND jm.date >= '$from_date_db' AND jm.date <= '$to_date_db' $added_by_condition";

        $job_data = $this->user_model->get_val("
            SELECT jm.id, jm.date as start_date_time, jm.status, jl.job_status, jl.date_time as end_date_time,cm.full_name,jm.order_id,jm.status,phlebo_master.name as phlebo_added_by,admin_master.name AS added_by
            FROM job_master jm 
            LEFT JOIN job_log jl ON jl.job_fk = jm.id 
            LEFT JOIN customer_master cm ON cm.id = jm.cust_fk 
            left join phlebo_master on jm.phlebo_added=phlebo_master.id
            LEFT JOIN admin_master ON admin_master.id=jm.added_by
            WHERE $whereCondition 
            ORDER BY jm.date DESC 
        ");

        if (!empty($job_data)) {
            foreach ($job_data as $key => $value) {
                if (!empty($value['end_date_time'])) {
                    $start = new DateTime($value['start_date_time']);
                    $end = new DateTime($value['end_date_time']);
                    $interval = $start->diff($end);
                    $totalHours = ($interval->days * 24) + $interval->h;
                    $minutes = $interval->i;
                    $job_data[$key]['tat'] = "{$totalHours} Hours {$minutes} Minutes";
                } else {
                    $job_data[$key]['tat'] = 'N/A';
                }
            }

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=tat_report.csv');
            $output = fopen('php://output', 'w');

            // Add header row
            fputcsv($output, array('No', 'Reg No','Order Id','Patient Name','Added By','Job Start Time', 'Job End Time', 'TAT','Status'));
        }

        $cnt = 1;
        foreach ($job_data as $row) {
            if (!empty($row["phlebo_added_by"])) {
                $added_by = ucfirst($row["phlebo_added_by"]) . " (Phlebo)";
            } else if (!empty($row["added_by"])) {
                $added_by = ucfirst($row["added_by"]);
            } else {
                $added_by = "Online Booking";
            }

            if ($row['status'] == 1) {
                $status = "Waiting For Approval";
            }
            if ($row['status'] == 6) {
                $status = "Approved";
            }
            if ($row['status'] == 7) {
                $status = "Sample Collected";
            }
            if ($row['status'] == 8) {
                $status = "Processing";
            }
            if ($row['status'] == 2) {
                $status = "Completed";
            }
            if ($row['status'] == 0) {
                $status = "User Deleted";
            }
            if ($row['emergency'] == '1') {
                $status = "Emergency";
            }
            
            fputcsv($output, array(
                $cnt++,
                $row['id'],
                $row['order_id'],
                $row['full_name'],
                $added_by,
                $row['start_date_time'],
                $row['end_date_time'],
                $row['tat'],
                $status
            ));
        }

        fclose($output);
        exit;
    }




}

?>