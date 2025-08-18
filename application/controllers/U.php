<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class U extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('home_model');
        $this->load->model('job_model');
        $this->load->model('user_test_master_model');
        $this->load->model('user_wallet_model');
        $this->load->model('service_model');
        $this->load->model("user_master_model");
        $this->load->library('pushserver');
        $this->load->library('firebase_notification');
        $this->load->helper('string');
        $this->load->library('email');
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($uid != 0) {
            $maxid = $this->user_wallet_model->total_wallet($uid);
            $data['total'] = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "id" => $maxid), array("id", "asc"));
            $this->data['wallet_amount'] = $data['total'][0]['total'];
        }
        /* pinkesh code start */
        $data['links'] = $this->user_test_master_model->master_fun_get_tbl_val("patholab_home_master", array("status" => 1), array("id", "asc"));
        $this->data['all_links'] = $data['links'];
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function j() {
        $data['cid'] = $this->uri->segment(3);
        $data['success'] = $this->session->flashdata("success");
        $data['error'] = $this->session->flashdata("error");
        $data['family_error'] = $this->session->flashdata("family_error");
        $data['amount_history_success'] = $this->session->flashdata("amount_history_success");
        if ($this->session->userdata("amount_history_success")) {
            $data['amount_history_success'] = $this->session->userdata("amount_history_success");
            $this->session->unset_userdata("amount_history_success");
        }
        $data['query'] = $this->get_job_details($data['cid']);
        if ($data['query'][0]["payment_type"] == 'PayUMoney') {
            $data['payumoney_details'] = $this->job_model->master_fun_get_tbl_val("payment", array('job_fk' => $data['cid']), array("id", "asc"));
        }
        $cust_details = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $data['query'][0]["cust_fk"]), array("id", "asc"));
        $data['query'][0]["full_name"] = $cust_details[0]['full_name'];
        $p_prc = 0;

        $data["emergency_tests"] = $this->job_model->master_fun_get_tbl_val("booking_info", array('id' => $data['query'][0]["booking_info"]), array("id", "asc"));
        $data["booking_info"] = $this->job_model->get_val("SELECT 
      `booking_info`.*,
      TIME_FORMAT(
        `phlebo_time_slot`.`start_time`,
        '%l:%i %p'
      ) AS `start_time`,
      TIME_FORMAT(
        `phlebo_time_slot`.`end_time`,
        '%l:%i %p'
      ) AS `end_time`,
      `customer_family_master`.`name` 
    FROM
      `booking_info` 
      INNER JOIN `phlebo_time_slot` 
        ON `booking_info`.`time_slot_fk` = `phlebo_time_slot`.`id` 
      LEFT JOIN `customer_family_master` 
        ON `booking_info`.`family_member_fk` = `customer_family_master`.`id` where booking_info.id='" . $data['query'][0]["booking_info"] . "'");
        $f_data = $this->job_model->master_fun_get_tbl_val("customer_family_master", array('id' => $data["emergency_tests"][0]["family_member_fk"]), array("id", "asc"));

        $update = $this->job_model->master_fun_update("job_master", array('id', $data['cid']), array("views" => "1"));
        $booked_tests = $this->job_model->master_fun_get_tbl_val("wallet_master", array('job_fk' => $data['cid']), array("id", "asc"));
        $w_prc = 0;


        /* echo "<pre>";
          print_r($data["query"]);
          die(); */
        $this->load->view("j", $data);
    }

    function payumoney() {
        $data['payumoneydetail'] = $this->config->item('payumoneydetail');
        $this->load->library('session');

        $data['rndtxn'] = random_string('numeric', 20);
        $data['jid'] = $this->uri->segment(3);
        $data['booking_info'] = $this->uri->segment(5);
        $data['payamount'] = $this->input->post('amount');
        $data['payamount'] = $this->uri->segment(4);
        $destail = $this->user_wallet_model->master_fun_get_tbl_val("job_master", array("id" => $data['jid']), array("id", "asc"));
        $destail1 = $this->user_wallet_model->master_fun_get_tbl_val("customer_master", array("id" => $destail[0]['cust_fk']), array("id", "asc"));
        $data['user_detail'] = $destail1;
        $this->load->view('payumoney', $data);
    }

    function success_payumoney() {
        $this->load->helper("Email");
        $email_cnt = new Email;

        $data["test_city_session"] = $this->session->userdata("test_city");

        $response = $_REQUEST;
        //print_R($response);
        $trnscaton_id = $response['txnid'];
        $amount = $response['amount'];
        $status = $response['status'];
        $paydate = $response['addedon'];
        $phone = $response['phone'];
        $this->load->library('session');
        $jid = $this->uri->segment(3);
        $amount = $this->uri->segment(4);
        $booking_info_data = $this->user_test_master_model->master_fun_get_tbl_val("booking_info", array('id' => $booking_info), array("id", "asc"));
        $job_details = $this->user_test_master_model->master_fun_get_tbl_val("job_master", array('id' => $jid), array("id", "asc"));

        $t = json_encode($response);
        $chcek_transaction_id = $this->job_model->master_fun_get_tbl_val("payment", array('payomonyid' => $trnscaton_id), array("id", "asc"));
        if (empty($chcek_transaction_id)) {
            if ($response['status'] == "success") {

                $data1 = array("payomonyid" => $trnscaton_id,
                    "amount" => $amount,
                    "paydate" => $paydate,
                    "status" => $status,
                    "uid" => $job_details[0]["cust_fk"],
                    "type" => "remaining_amount",
                    "job_fk" => $jid,
                    "data" => $t,
                );
                $insert1 = $this->job_model->master_fun_insert("payment", $data1);

                $ttl_amount = $job_details[0]["payable_amount"];
                if (!empty($jid) && $ttl_amount >= $amount) {
                    $this->job_model->master_fun_insert("job_master_receiv_amount", array("job_fk" => $jid, "type" => "User Pay", "amount" => $amount, "paypal_log_fk" => $insert1, "createddate" => date("Y-m-d H:i:s")));
                    $remaining_amount = $ttl_amount - $amount;
                    $this->job_model->master_fun_update("job_master", array("id", $jid), array("payable_amount" => $remaining_amount));
                }
                /*    $this->load->helper("sms");
                  $notification = new Sms();
                  $mobile = $destail[0]['mobile'];
                  $mobile = ucfirst($c_name) . "(" . $cmobile . ")";
                  $test_package = implode($test_package_name, ', ');
                  $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "test_info"), array("id", "asc"));
                  $sms_message = preg_replace("/{{MOBILE}}/", $mobile, $sms_message[0]["message"]);
                  if ($pid != '' && $tid != '') {
                  $sms_message = preg_replace("/{{TESTPACK}}/", 'Test/Package', $sms_message);
                  } else if ($pid != '') {
                  $sms_message = preg_replace("/{{TESTPACK}}/", 'Package', $sms_message);
                  } else {
                  $sms_message = preg_replace("/{{TESTPACK}}/", 'Test', $sms_message);
                  }
                  $sms_message = preg_replace("/{{TESTPACKLIST}}/", $test_package, $sms_message);
                  $sms_message = preg_replace("/{{TOTALPRICE}}/", $amount, $sms_message);

                  $notification::send($cmobile, $sms_message);
                  $configmobile = $this->config->item('admin_alert_phone');
                  foreach ($configmobile as $p_key) {
                  //$notification::send($configmobile, $sms_message);
                  $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $p_key, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                  }
                  if (!empty($family_member_name)) {
                  $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $destail[0]['mobile'], "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                  }

                  //redirect('user_master');
                  redirect("user_test_master/invoice/" . $insert); */
                $this->check_send_report($jid);
                echo '<div class="alert alert-success">
  Your payment successfully received.
</div>';
            } else {
                echo '<div class="alert alert-success">
  Payment fail,Try agamin.
</div>';
            }
        } else {
            echo '<div class="alert alert-success">
  Payment fail,Try agamin.
</div>';
        }
    }

    function get_job_details($job_id) {
        $job_details = $this->job_model->master_fun_get_tbl_val("job_master", array("status !=" => "0", "id" => $job_id), array("id", "asc"));
        if (!empty($job_details)) {
            $book_test = $this->job_model->master_fun_get_tbl_val("job_test_list_master", array("job_fk" => $job_id), array("id", "desc"));
            $book_package = $this->job_model->master_fun_get_tbl_val("book_package_master", array("job_fk" => $job_id, "status" => "1"), array("id", "desc"));
            $test_name = array();
            foreach ($book_test as $key) {
                $price1 = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $job_details[0]["test_city"] . "' AND `test_master`.`id`='" . $key["test_fk"] . "'");
                if (!empty($price1[0])) {
                    $test_name[] = $price1[0];
                }
            }
            $job_details[0]["book_test"] = $test_name;
            $package_name = array();
            foreach ($book_package as $key) {

                $price1 = $this->job_model->get_val("SELECT 
          `package_master`.*,
          `package_master_city_price`.`a_price` AS `a_price`,
          `package_master_city_price`.`d_price` AS `d_price`
          FROM
          `package_master`
          INNER JOIN `package_master_city_price`
          ON `package_master`.`id` = `package_master_city_price`.`package_fk`
          WHERE `package_master`.`status` = '1'
          AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $job_details[0]["test_city"] . "' AND `package_master`.`id`='" . $key["package_fk"] . "'");
                $package_name[] = $price1[0];
            }
            $job_details[0]["book_package"] = $package_name;
        }
        return $job_details;
    }

    function fail_payumoney() {
        echo "Oops payment is fail.";
    }

    function check_send_report($cid) {
        $this->load->model("user_model");
        $this->load->model("job_model");
        $this->load->library("util");
        $util = new Util;
        $this->load->helper("Email");
        $email_cnt = new Email;
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $jdata = $this->job_model->master_fun_get_tbl_val("job_master", array("id" => $cid), array("id", "asc"));
        $checkPanelTes = $this->job_model->get_val("SELECT id FROM `booked_job_test` WHERE `status`='1' AND test_fk LIKE '%pt-%' AND job_fk='" . $cid . "'");
        $check_job_Cashback = $this->job_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "job_fk" => $cid, "credit >" => 0, "type" => "Case Back"), array("id", "desc"));
        if ($jdata[0]["status"] == 2 && ($jdata[0]["payable_amount"] <= 0 || $jdata[0]["payable_amount"] == '') && empty($check_job_Cashback) && empty($checkPanelTes)) {
            $data = $this->job_model->master_fun_get_tbl_val("job_master", array("id" => $cid), array("id", "asc"));
            $assign_doctor = $this->job_model->master_fun_get_tbl_val("doctor_master", array("id" => $data[0]["doctor"]), array("id", "asc"));
            $notify_customer = $data[0]["notify_cust"];
            $payable_amount = $data[0]["payable_amount"];
            $cust_fk = $data[0]['cust_fk'];
            $data1 = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1, "id" => $cust_fk), array("id", "asc"));
            $device_id = $data1[0]['device_id'];
            $mobile = $data1[0]['mobile'];
            $device_type = $data1[0]['device_type'];
            $name = $data1[0]['full_name'];
            $email = $data1[0]['email'];
            $query = $this->job_model->job_details($cid);
            $testid = $query[0]['id'];
            $testname = $query[0]['testname'];
            $testprice = $query[0]['price'];
            $testdate = $query[0]['date'];
            $orderid = $query[0]['order_id'];
            $message = "Your Report has been Completed";

            if ($device_type == 'android' && $notify_customer == 1) {
                //$notification_data=array("title" => "Patholab","message" =>$message,"type"=>"completed");
                $notification_data = array("title" => "AirmedLabs", "message" => $message, "type" => "completed", "testid" => $testid, "testname" => $testname, "testprice" => $testprice, "testdate" => $testdate, "order_id" => $orderid);
                $pushServer = new PushServer();
                $pushServer->pushToGoogle($device_id, $notification_data);
            }
            if ($device_type == 'ios' && $notify_customer == 1) {
                $url = 'http://website-demo.in/patholabpush/push.php?device_id=' . $device_id . '&msg=' . $message . '&type=completed&testid=' . $testid . '&testname=' . $testname . '&testprice=' . $testprice . '&testdate=' . $testdate . '&orderid=' . $orderid . '';
                $url = str_replace(" ", "%20", $url);
                $data = $this->get_content($url);
                //die();
                $data2 = json_decode($data);
            }
            /* Nishit send sms code start */
            $family_member_name = $this->job_model->get_family_member_name($cid);
            if (!empty($family_member_name)) {
                $cmobile = $family_member_name[0]["phone"];
                if (empty($cmobile)) {
                    $cmobile = $data1[0]['mobile'];
                }
            } else {
                $cmobile = $data1[0]['mobile'];
            }
            $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "Completed_Report"), array("id", "asc"));
            // $sms_message = preg_replace("/{{NAME}}/", ucfirst($data1[0]["full_name"]), $sms_message[0]["message"]);
            $sms_message = preg_replace("/{{OID}}/", $orderid, $sms_message[0]["message"]);
            //$sms_message = preg_replace("/{{PRICE}}/", "Rs." . $testprice, $sms_message);
            if ($notify_customer == 1) {
                $this->load->helper("sms");
                $notification = new Sms();
                $notification->send($cmobile, $sms_message);
                if (!empty($family_member_name) && ($payable_amount <= 0 || $payable_amount == '')) {
                    $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $mobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
                }
            }
            /* Nishit send sms code end */
            $report = $this->job_model->get_val("select * from report_master where job_fk='" . $cid . "' order by id desc limit 0,1");
            $nw_ary = array();
            foreach ($report as $rkey) {
                if ($rkey['type'] == 'c') {
                    $nw_ary = $rkey;
                }
            }
            if (!empty($nw_ary)) {
                $report = array($nw_ary);
            }
            //print_R($report); die();
            $config['mailtype'] = 'html';

            $this->email->initialize($config);
            $family_member_name = $this->job_model->get_family_member_name($cid);
            if (!empty($family_member_name)) {
                $name = $family_member_name[0]["name"];
            }
            /* Nishit cashback start */
            $job_details = $this->get_job_details($cid);
            $b_details = array();
            foreach ($job_details[0]["book_test"] as $bkey) {
                //$b_details[] = $bkey["test_name"] . " Rs." . $bkey["price"];
            }
            $test_prc = 0;
            foreach ($job_details[0]["book_test"] as $t_key) {
                $tst_prc = $this->job_model->get_val("SELECT price FROM `booked_job_test` WHERE `status`='1' AND job_fk='" . $cid . "' AND `test_fk`='t-" . $t_key["id"] . "'");
                //$return_test_price = $util->getTEstPrice($cid, array($t_key["id"]));
                $test_prc = $test_prc + $tst_prc[0]["price"];
            }
            if ($job_details[0]["status"] != '0') {
                if (!empty($job_details[0]["book_test"]) && $job_details[0]["discount"] != '100' && in_array($jdata[0]["added_by"], array("", "0")) && in_array($jdata[0]["phlebo_added"], array("", "0"))) {
                    $query_cb = $this->job_model->master_fun_get_tbl_val("offer_master", array("status" => 1), array("id", "desc"));
                    $caseback_per = $query_cb[0]['caseback_per'];
                    $query_ct = $this->job_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $job_details[0]["cust_fk"]), array("id", "desc"));
                    $query_cbt = $this->job_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "job_fk" => $cid, "debit >" => 0), array("id", "desc"));
                    $total = $query_ct[0]['total'];
                    $dprice1 = $job_details[0]["discount"] * $job_details[0]["price"] / 100;
                    $price = $test_prc - $dprice1 - $query_cbt[0]["debit"];
                    $caseback_amount = round(($price * $caseback_per) / 100);
                    if ($caseback_amount > 0) {
                        $data = array(
                            "cust_fk" => $cust_fk,
                            "credit" => $caseback_amount,
                            "total" => $total + $caseback_amount,
                            "type" => "Case Back",
                            "job_fk" => $cid,
                            "created_time" => date('Y-m-d H:i:s')
                        );
                        if ($test_prc != 0) {
                            $insert1 = $this->job_model->master_fun_insert("wallet_master", $data);
                        }
                        $query = $this->job_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $job_details[0]["cust_fk"]), array("id", "desc"));
                        $Current_wallet = $query[0]['total'];
// Case Back Email start
                        $config['mailtype'] = 'html';
                        $this->email->initialize($config);

                        $message = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $name . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Cashback Credited in your Wallet. </p>
                        <p style="color:#7e7e7e;font-size:13px;"> Rs. ' . $caseback_amount . ' Credited in your account. </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Current Wallet Amount is Rs. ' . $Current_wallet . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                        $message = $email_cnt->get_design($message);
                        $this->email->to($email);
                        $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                        $this->email->subject('CashBack');
                        $this->email->message($message);
                        if ($test_prc != 0 && $notify_customer == 1 && ($payable_amount <= 0 || $payable_amount == '')) {
                            $this->email->send();
                        }
                    }
                    // Case Back Email end			
                }
                /* Nishit cashback end */
                $book_test_details = array();
                foreach ($job_details[0]["book_test"] as $bkey) {
                    $book_test_details[] = $bkey["test_name"];
                }
                foreach ($job_details[0]["book_package"] as $bkey) {
                    $book_test_details[] = $bkey["title"];
                }
                if ($notify_customer == 1) {

                    if ($payable_amount <= 0 || $payable_amount == '') {

                        $message1 = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $name . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Report completed successfully for test ' . implode(",", $book_test_details) . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Report ID : ' . $orderid . ' </p>    
		<p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                    } else {
                        $message1 = '<div style="padding:0 4%;">
                    <h4><b>Dear </b>' . $name . '</h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Report completed successfully for test ' . implode(",", $book_test_details) . ' </p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Report ID : ' . $orderid . ' </p>
                            <p style="color:#7e7e7e;font-size:13px;">Please pay your due amount using below link and get report.</p>
                            <p style="color:#7e7e7e;font-size:13px;"><a href="' . base_url() . 'u/j/' . $cid . '" target="_blank">Click Here</a> for pay online.</p>
		<p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                    }
                    $c_email = array();
                    $message1 = $email_cnt->get_design($message1);
                    $c_email[] = $email;
                    $c_email[] = $assign_doctor[0]["email"];
                    $this->email->to(implode(",", $c_email));
                    //  $this->email->to('jeel@virtualheight.com');
                    $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
                    $this->email->subject("Report Completed");
                    $this->email->message($message1);
                    if ($payable_amount <= 0 || $payable_amount == '') {
                        $attatchPath = "";
                        foreach ($report as $key) {
                            $attatchPath = FCPATH . "upload/report/" . $key['report'];
                            $this->email->attach($attatchPath);
                        }
                    }
                    //$this->email->attach(implode(',',$attatchPath));
                    $this->email->send();

                    $family_member_name = $this->job_model->get_family_member_name($cid);
                    $c_email = array();
                    if (!empty($family_member_name)) {
                        $c_email[] = $family_member_name[0]["email"];
                        $c_email[] = $assign_doctor[0]["email"];
                        if (!empty($c_email)) {
                            $this->email->to(implode(",", $c_email));
                            //  $this->email->to('jeel@virtualheight.com');
                            $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
                            $this->email->subject("Report Completed");
                            $this->email->message($message1);
                            if ($payable_amount <= 0 || $payable_amount == '') {
                                $attatchPath = "";
                                foreach ($report as $key) {
                                    $attatchPath = base_url() . "upload/report/" . $key['report'];
                                    $this->email->attach($attatchPath);
                                }
                            }
                            //$this->email->attach(implode(',',$attatchPath));
                            $this->email->send();
                        }
                    }
                    /* Nishit send SMS start */
                    if ($payable_amount <= 0 || $payable_amount == '') {
                        $sms = $this->send_result($cid);
                        if (!empty($sms["sms"])) {
                            $doctor_number = array();
                            if (!empty($assign_doctor[0]["mobile"]) && !in_array($assign_doctor[0]["mobile"], $doctor_number)) {
                                $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $assign_doctor[0]["mobile"], "message" => $sms["sms"], "created_date" => date("Y-m-d H:i:s")));
                                $doctor_number[] = $assign_doctor[0]["mobile"];
                            }
                            if (!empty($assign_doctor[0]["mobile1"]) && !in_array($assign_doctor[0]["mobile1"], $doctor_number)) {
                                $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $assign_doctor[0]["mobile1"], "message" => $sms["sms"], "created_date" => date("Y-m-d H:i:s")));
                                $doctor_number[] = $assign_doctor[0]["mobile1"];
                            }
                            if (!empty($assign_doctor[0]["mobile2"]) && !in_array($assign_doctor[0]["mobile2"], $doctor_number)) {
                                $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $assign_doctor[0]["mobile2"], "message" => $sms["sms"], "created_date" => date("Y-m-d H:i:s")));
                                $doctor_number[] = $assign_doctor[0]["mobile2"];
                            }
                            $this->job_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $sms["mobile"][0], "message" => $sms["sms"], "created_date" => date("Y-m-d H:i:s")));
                            $this->job_model->master_fun_insert("send_report_sms", array("job_fk" => $cid, "mobile" => $sms["mobile"][0], "sms" => $sms["sms"], "created_date" => date("Y-m-d H:i:s"), "send_by" => $login_user));
                        }
                    }
                    /* END */
                }
            }
        }
        echo 1;
    }

    function send_result($id) {
        $this->load->model("add_result_model");
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        $data["login_data"] = logindata();
        $data['cid'] = $id;
        $testfk = $this->input->post('testid');
        /* $this->form_validation->set_rules('testid[]', 'testid', 'trim|required');
          if ($this->form_validation->run() != FALSE) { */
        if (1 == 1) {
            $data['query'] = $this->add_result_model->job_details1($data['cid']);
            // $data['query1'] = $this->add_result_model->job_details($data['cid']);
            //$cus_id = $data['query1'][0]['custid'];
            //  $data['query'] = $this->add_result_model->get_val("SELECT c.mobile,cfm.phone FROM `customer_family_master` as cfm LEFT JOIN `customer_master` as c on c.id =cfm.user_fk WHERE cfm.user_fk ='" .  $cus_id . "' ORDER BY c.id DESC LIMIT 1"); 
            $data['processing_center'] = $this->add_result_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $data['query'][0]["branch_fk"]), array("id", "asc"));
            if (empty($data['processing_center'])) {
                $processing_center = '1';
            } else {
                $processing_center = $data['processing_center'][0]["branch_fk"];
            }
            $branch_fk = $data["branch_fk"] = $data['query'][0]["branch_fk"];
            $data['user_booking_info'] = $this->add_result_model->master_fun_get_tbl_val("booking_info", array('status' => 1, 'id' => $data['query'][0]["booking_info"]), array("id", "asc"));
            $data['user_data'] = $this->add_result_model->master_fun_get_tbl_val("customer_master", array('status' => 1, 'id' => $data['query'][0]["custid"]), array("id", "asc"));
            /* if (empty($data['user_data'][0]["gender"]) && empty($data['user_data'][0]["age"])) {
              $data['user_data'][0]["gender"] = 'male';
              $data['user_data'][0]["age"] = 24;
              } */
            $get_approved_test_list = $this->add_result_model->master_fun_get_tbl_val("approve_job_test", array('status' => '1', 'job_fk' => $data['cid']), array("id", "asc"));
            $approved_test = array();
            foreach ($get_approved_test_list as $at_key) {
                $approved_test[] = $at_key["test_fk"];
            }
            $cust_rel_mobile = "";
            if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
                $data['user_family_info'] = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
                $data['user_data'][0]["gender"] = $data['user_family_info'][0]["gender"];
                $data['user_data'][0]["age"] = $data['user_family_info'][0]["age"];
                $data['user_data'][0]["age_type"] = $data['user_family_info'][0]["age_type"];
                $data['user_data'][0]["full_name"] = $data['user_family_info'][0]["name"];
                $data['user_data'][0]["email"] = $data['user_family_info'][0]["email"];
                $data['user_data'][0]["phone"] = $data['user_family_info'][0]["phone"];
                $cust_rel_mobile = $data['user_family_info'][0]["phone"];
                $data['user_data'][0]["dob"] = $data['user_family_info'][0]["dob"];
            }
            /* Check bitrth date start */
            $this->load->library("util");
            $util = new Util;
            $age = $util->get_age($data['user_data'][0]["dob"]);
            $ageinDays = 0;
            if ($age[0] != 0) {
                $ageinDays += ($age[0] * 365);
                $data['user_data'][0]["age"] = $age[0];
                $data['user_data'][0]["age_type"] = 'Y';
            }
            if ($age[0] == 0 && $age[1] != 0) {
                $ageinDays += ($age[1] * 30);
                $data['user_data'][0]["age"] = $age[1];
                $data['user_data'][0]["age_type"] = 'M';
            }
            if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
                $ageinDays += ($age[2]);
                $data['user_data'][0]["age"] = $age[2];
                $data['user_data'][0]["age_type"] = 'D';
            }
            /* Check birth date end */
            $tid = array();
            $data['parameter_list'] = array();
            if (trim($data['query'][0]['testid']) == null && $data['query'][0]["packageid"] != null) {

                $package_id = $data['query'][0]["packageid"];
                $pid = explode("%", $data['query'][0]['packageid']);
                foreach ($pid as $pkey) {
                    $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");
                    foreach ($p_test as $tp_key) {
                        $tid[] = $tp_key["test_fk"];
                    }
                }
            } else if (trim($data['query'][0]['testid']) != null && $data['query'][0]["packageid"] != null) {

                $tid = explode(",", $data['query'][0]['testid']);
                $package_id = $data['query'][0]["packageid"];
                $pid = explode("%", $data['query'][0]['packageid']);
                foreach ($pid as $pkey) {
                    $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");
                    foreach ($p_test as $tp_key) {
                        $tid[] = $tp_key["test_fk"];
                    }
                }
            } else {

                $tid = explode(",", $data['query'][0]['testid']);
            }

            foreach ($tid as $t_key) {
                $p_test = $this->add_result_model->get_val("SELECT sub_test_master.* FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `sub_test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $t_key . "'");
                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["sub_test"];
                }
            }
            $tid = array_unique($tid);
            $cnt = 0;
            $new_data_array = array();
            foreach ($tid as $tst_id) {
                if (in_array($tst_id, $approved_test)) {
                    $get_test_parameter = $this->add_result_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name`,test_master.PRINTING_NAME,`test_master`.report_type,test_department_master.`name` AS 'department_name' FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id`     LEFT JOIN  `test_department_master` ON test_department_master.`id`=`test_master`.`department_fk`  WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' and test_parameter.center='" . $processing_center . "' order by `test_parameter`.order asc");

                    $pid = array();
                    foreach ($get_test_parameter as $tp_key) {
                        if (!empty($tp_key["parameter_fk"])) {
                            $pid[] = $tp_key["parameter_fk"];
                        }
                    }
                    if (!empty($pid)) {
                        $para = $this->add_result_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
                        $cnt_1 = 0;
                        foreach ($para as $para_key) {
                            /* $formula = $this->add_result_model->get_val("SELECT * from use_formula where test_fk='" . $tst_id . "' and job_fk='" . $data['cid'] . "' and status='1'"); */
                            $get_test_parameter[$cnt_1]['use_formula'] = "";

                            $gettest = $this->add_result_model->get_val("SELECT new_page from approve_job_test where test_fk='" . $tst_id . "' and job_fk='" . $data['cid'] . "' and status='1'");
                            $get_test_parameter[$cnt_1]['on_new_page'] = $gettest[0]["new_page"];

                            /* Report type start */
                            $culure_design = $this->add_result_model->get_val("SELECT id,title,html FROM `parameter_culture` WHERE center='" . $processing_center . "' AND test_fk='" . $tst_id . "' AND STATUS='1'");
                            $get_test_parameter[$cnt_1]['culture_design'] = $culure_design;
                            /* End */
                            $get_test_parameter[$cnt_1]['is_culture'] = "";
                            $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                            $get_test_parameter[$cnt_1]['graph'] = $graph_pic;
                            $get_test_parameter1 = $get_test_parameter[$cnt_1];
//echo "SELECT * from user_test_result where test_id='".$tst_id."' and parameter_id='" . $para_key["id"] . "' and job_id='".$data['cid']."' and  status='1'";
                            $para_user_val = $this->add_result_model->get_val("SELECT * from user_test_result where test_id='" . $tst_id . "' and parameter_id='" . $para_key["id"] . "' and job_id='" . $data['cid'] . "' and  status='1'");
                            $para[$cnt_1]["user_val"] = $para_user_val;
                            $para_culture_val = $this->add_result_model->get_val("SELECT * from user_test_result where test_id='" . $tst_id . "' and job_id='" . $data['cid'] . "' and result is not NULL and status='1'");
                            $para[$cnt_1]["user_culture_val"] = $para_culture_val;
                            $para_ref_rng = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE `status`='1' AND parameter_fk='" . $para_key["id"] . "' order by gender asc");
                            $final_qry = "SELECT *,
  CASE
    WHEN (type_period = 'Y') 
    THEN (no_period * 365) 
    ELSE (
      CASE
        WHEN (type_period = 'M') 
        THEN (no_period * 30) 
        ELSE no_period 
      END
    ) 
  END AS col1  FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                            if (strtoupper($data['user_data'][0]["gender"]) == 'MALE') {
                                $final_qry .= " AND gender='M' AND (CASE WHEN (type_period= 'Y') THEN (no_period*365) ELSE (CASE WHEN (type_period= 'M') THEN (no_period*30) ELSE no_period END) END )>=$ageinDays ";
                                $data["common"] = 0;
                            } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE') {
                                $final_qry .= " AND gender='F' AND  (CASE WHEN (type_period= 'Y') THEN (no_period*365) ELSE (CASE WHEN (type_period= 'M') THEN (no_period*30) ELSE no_period END) END )>=$ageinDays";
                                $data["common"] = 0;
                            }
                            $final_qry = $final_qry . " ORDER BY (col1*1) ASC limit 0,1";
                            $final_qry1 = "SELECT * FROM `test_result_status` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                            $data["common"] = 1;
                            $data["para_ref_rng"] = $this->add_result_model->get_val($final_qry);
                            $data["para_ref_rng"][0]["common"] = "1";
                            $data["para_ref_rng"][0]["tst_id"] = $tst_id;
                            $para[$cnt_1]['para_ref_rng'] = $data["para_ref_rng"];

                            $data["para_ref_status"] = $this->add_result_model->get_val($final_qry1);
                            $para[$cnt_1]['para_ref_status'] = $data["para_ref_status"];
                            $cnt_1++;
                        }
                        $get_test_parameter1[0]['parameter'] = $para;
                        $new_data_array[] = $get_test_parameter1;
                    } else {
                        $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name,test_master.PRINTING_NAME FROM `test_master` WHERE id='" . $tst_id . "'");
                        $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                        $get_test_parameter1[0]['graph'] = $graph_pic;
                        $new_data_array[] = $get_test_parameter1[0];
                    }

                    $cnt++;
                }
            }


            $data["new_data_array"] = $new_data_array;
            $data['result_list'] = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('status' => 1, 'job_id' => $data['cid']), array("id", "asc"));
            /* Nishit add result SMS start */
            $txt_cnt = 0;
            $sms_text = "";
            $sms_text .= "Patient Name:  " . strtoupper($data['user_data'][0]["full_name"]) . " (" . $data['query'][0]["order_id"] . ") \n\n";
            foreach ($new_data_array as $testidp) {
                $parameter_cnt = 0;
                if (!empty($testidp[0]["parameter"])) {
                    $parameter_val_cnt = 0;
                    foreach ($testidp[0]["parameter"] as $parameter) {
                        if (!empty($parameter['user_val'])) {
                            $parameter_val_cnt++;
                        }
                    }
                    if ($parameter_val_cnt != 0) {
                        //if($txt_cnt>0){ $sms_text .='\n'; }
                        if ($txt_cnt > 0) {
                            $sms_text .= "\n " . $testidp["test_name"] . " \n";
                        } else {
                            $sms_text .= $testidp["test_name"] . " \n";
                        }

                        $txt_cnt++;
                        $temp = '1';
                        $cn = 0;

                        foreach ($testidp[0]["parameter"] as $parameter) {
                            if ($parameter["is_group"] != '1') {
                                if (!empty($parameter['parameter_name']) && !empty($parameter['user_val'])) {
                                    if (count($parameter['user_val']) > 0) {
                                        $status = "Normal";
                                        if ($parameter["para_ref_rng"][0]['absurd_low'] > $parameter['user_val'][0]["value"]) {
                                            $status = "Emergency";
                                        }
                                        if ($parameter["para_ref_rng"][0]['ref_range_low'] > $parameter['user_val'][0]["value"]) {
                                            $status = $parameter["para_ref_rng"][0]['low_remarks'];
                                        }
                                        if ($parameter["para_ref_rng"][0]['critical_low'] > $parameter['user_val'][0]["value"]) {
                                            $status = $parameter["para_ref_rng"][0]['critical_low_remarks'];
                                        }
                                        if ($parameter["para_ref_rng"][0]['ref_range_high'] < $parameter['user_val'][0]["value"]) {
                                            $status = $parameter["para_ref_rng"][0]['high_remarks'];
                                        }
                                        if ($parameter["para_ref_rng"][0]['critical_high'] < $parameter['user_val'][0]["value"]) {
                                            $status = $parameter["para_ref_rng"][0]['critical_high_remarks'];
                                        }
                                    } else {
                                        $status = "";
                                    }

                                    $sms_text .= $parameter['parameter_name'] . " :- ";

                                    $res = '';
                                    $is_text = 0;
                                    if (isset($parameter["para_ref_rng"][0]['id'])) {

                                        $sms_text .= " " . $parameter['user_val'][0]["value"];
                                        $status;
                                    } else {

                                        if (!empty($parameter["para_ref_status"])) {
                                            foreach ($parameter["para_ref_status"] as $kky) {
                                                if ($parameter['user_val'][0]["value"] == $kky["id"]) {
                                                    $is_text = 1;
                                                    $sms_text .= " " . $kky["parameter_name"] . " \n ";
                                                }
                                            }
                                        } else {
                                            $sms_text .= $parameter['user_val'][0]["value"];
                                        }
                                    }
                                    //$sms_text .= "   ".$res;
                                    if ($is_text == 0) {
                                        //$sms_text .= $parameter['parameter_unit'];
                                        if (!empty(trim($parameter["para_ref_rng"][0]["ref_range"]))) {
                                            $sms_text .= " [" . $parameter["para_ref_rng"][0]["ref_range"] . "] \n ";
                                        } else {
                                            if ($parameter["para_ref_rng"][0]['ref_range_low'] != '' || $parameter["para_ref_rng"][0]['ref_range_high'] != '') {
                                                $sms_text .= " [" . $parameter["para_ref_rng"][0]['ref_range_low'];
                                                $sms_text .= " - " . $parameter["para_ref_rng"][0]['ref_range_high'] . "] \n ";
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            /* Nishit add result SMS end */
            if (trim($sms_text) != '') {
                $mobile = array();
                $mobile[] = $data['query'][0]["mobile"];
                if (!empty(cust_rel_mobile)) {
                    $mobile[] = $cust_rel_mobile;
                }
                $custmobile = $cust_rel_mobile;

                $data['send_sms_no'] = $this->add_result_model->get_val("SELECT `send_report_sms`.*,`admin_master`.`name` FROM `send_report_sms` INNER JOIN `admin_master` ON `send_report_sms`.`send_by`=`admin_master`.id WHERE `send_report_sms`.`status`='1' AND send_report_sms.`job_fk`='" . $data['cid'] . "'");
                echo array("status" => "1", "sms" => $sms_text, "mobile" => $mobile, "custmobile" => $custmobile, "history" => $data['send_sms_no']);
            } else {
                echo array("status" => "0");
            }
        }
    }

    function get_content($URL) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $URL);
        $data = curl_exec($ch);
        curl_close($ch);
    }

}
