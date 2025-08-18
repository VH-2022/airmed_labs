<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sample_booking extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('samplebook_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $data["login_data"] = logindata();
        $this->load->helper('string');
        if (!is_loggedin()) {
            redirect('login');
        }
    }

    function TelecallerCallBooking() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();

        $brancarray = array();
        foreach ($data["login_data"]['branch_fk'] as $val) {
            $brancarray[] = $val['branch_fk'];
        }
        $userbranch = implode(",", $brancarray);
        if ($userbranch == "") {
            $userbranch = "0";
        }

        $added_by = $data["login_data"]["id"];

        $data["cntr_arry"] = array();
        $data["branch_city_arry"] = array();


        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required');
        $this->form_validation->set_rules('dob', 'Birth date', 'trim|required');
        $this->form_validation->set_rules('referral_by', 'Referral By', 'trim|required');

        $this->form_validation->set_rules('branch', 'Branch', 'trim|required');

        if ($this->form_validation->run() != FALSE) {

            $customer = $this->input->post("customer");
            $name = $this->input->post("name");
            $phone = $this->input->post("phone");
            $email = $this->input->post("email");
            $noify_cust = $this->input->post("noify_cust");
            if ($noify_cust == null) {
                $noify_cust = 0;
            }
            if ($email == '') {
                $email = 'noreply@airmedlabs.com';
            }
            $gender = $this->input->post("gender");
            $dob = $this->input->post("dob");


            $note = $this->input->post("note");
            $discount = $this->input->post("discount");
            $payable = $this->input->post("payable");
            $test = $this->input->post("test");
            $referral_by = $this->input->post("referral_by");

            $branch = $this->input->post("branch");
            $total_amount = $this->input->post("total_amount");
            $collection_charge = $this->input->post("collection_charge");
            $discount_type = $this->input->post("discount_type");
            $payment_via = $this->input->post("payment_via");
            $received_amount = $this->input->post("received_amount");

            if (in_array($branch, $brancarray)) {

                $getbranchtest = $this->samplebook_model->get_val("SELECT city,branch_type_fk from branch_master where status='1' and id ='$branch'");

                $test_city = $getbranchtest[0]["city"];
                $branchtype = $getbranchtest[0]["branch_type_fk"];

                /* Check discount start */
                if ($discount_type == 'flat') {
                    $f_payable_amount = $payable + $received_amount;
                    $discount = 100 - ($f_payable_amount * 100 / $total_amount);
                    $discount = number_format((float) $discount, 2, '.', '');
                }

                if ($branch > 0) {
                    $test_cut = $this->samplebook_model->get_val("select IF(cut>0,cut,0) as cut,smsalert,emailalert,branch_type_fk from branch_master where id='" . $branch . "'");
                    $cut = $test_cut[0]["cut"];
                    $smsalert = $test_cut[0]["smsalert"];
                    $emailalert = $test_cut[0]["emailalert"];
                    $branchtype = $test_cut[0]["branch_type_fk"];
                } else {

                    $cut = 0;
                    $smsalert = 0;
                    $emailalert = 0;
                    $branchtype = 0;
                }

                $doc_discount_check = $this->samplebook_model->get_val("SELECT * FROM `doctor_master` WHERE `status`='1' AND id='" . $referral_by . "'");
                /* END */
                $sample_from = "";
                $order_id = $this->get_job_id($test_city);
                $date = date('Y-m-d H:i:s');

                $result = $this->samplebook_model->get_val("SELECT * FROM `customer_master` WHERE `status`='1' AND active='1' AND `mobile`='" . $phone . "' ORDER BY id ASC");

                if (empty($result)) {
                    $result1 = $this->samplebook_model->get_val("SELECT * FROM `customer_master` WHERE `status`='1' AND active='1' AND `email`='" . $email . "' ORDER BY id ASC");
                    if (empty($result1) || $email = "noreply@airmedlabs.com") {

                        $this->load->helper("Email");
                        $email_cnt = new Email;

                        $password = rand(11111111, 9999999);
                        $c_data = array(
                            "full_name" => $name,
                            "gender" => $gender,
                            "email" => $email,
                            "mobile" => $phone,
                            "password" => $password,
                            "dob" => $dob,
                            "created_date" => date("Y-m-d H:i:s")
                        );

                        $customer = $this->samplebook_model->master_fun_insert("customer_master", $c_data);

                        $config['mailtype'] = 'html';
                        $this->email->initialize($config);

                        $message = '<div style="padding:0 4%;">
                    <h4><b>Create Account</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your account successfully created. </p>
                        <p style="color:#7e7e7e;font-size:13px;"> Username/Email : . ' . $email . '  </p>  
                        <p style="color:#7e7e7e;font-size:13px;"> Password : ' . $password . '  </p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
                        $message = $email_cnt->get_design($message);
                        $this->email->to($email);
                        $this->email->from($this->config->item('admin_booking_email'), 'Airmed PathLabs');
                        $this->email->subject('Account Created Successfully');
                        $this->email->message($message);
                        if ($noify_cust == 1) {
                            $this->email->send();
                        }
                    } else {
                        $customer = $result1[0]["id"];
                    }
                } else {

                    $customer = $result[0]["id"];
                    $c1_data = array("full_name" => $name, "gender" => $gender, "email" => $email, "address" => $address, "dob" => $dob);
                    $this->samplebook_model->master_fun_update("customer_master", array("id", $customer), $c1_data);
                }



                /*                 * **BHAVIK CHAGES START*** */
                $test_for = $this->input->post("test_for");
                $testforself = "self";
                $family_mem_id = 0;
                if ($test_for == "new") {
                    $f_name = $this->input->post("f_name");
                    $family_relation = $this->input->post("family_relation");
                    $relation_details = $this->samplebook_model->get_val("SELECT * from relation_master where id='" . $family_relation . "'");
                    $f_phone = $this->input->post("f_phone");
                    $f_email = $this->input->post("f_email");
                    $f_dob = $this->input->post("f_dob");
                    $family_gender = $this->input->post("family_gender");
                    if ($f_name != '' && $family_relation != '') {
                        $family_mem_id = $this->samplebook_model->master_fun_insert("customer_family_master", array("user_fk" => $customer, "gender" => $family_gender, "dob" => $f_dob, "name" => $f_name, "relation_fk" => $family_relation, "email" => $f_email, "phone" => $f_phone, "status" => "1", "created_date" => date("Y-m-d H:i:s")));
                        $testforself = "family";
                    }
                } else if ($test_for != '') {
                    $family_mem_id = $test_for;
                    $testforself = "family";
                }
                $address = $this->input->get_post("address");
                $date1 = $this->input->get_post("phlebo_date");
                $time_slot_id = $this->input->get_post("phlebo_time");
                $emergency = $this->input->post("emergency");
                if ($emergency == 1) {
                    $date1 = date("Y-m-d");
                    $time_slot_id = '';
                    $emergency = 1;
                } else {
                    $emergency = 0;
                }
                $booking_fk = $this->samplebook_model->master_fun_insert("booking_info", array("user_fk" => $customer, "type" => $testforself, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $date1, "time_slot_fk" => $time_slot_id, "emergency" => $emergency, "status" => "1", "createddate" => date("Y-m-d H:i:s")));


                /*                 * **END*** */



                $price = 0;
                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {

                        $result = $this->samplebook_model->get_val("SELECT test_master.`id`,`test_master`.`test_name`,IF(lab_doc_discount.`price`>0,lab_doc_discount.`price`,`test_branch_price`.`price`) AS price,lab_doc_discount.`price` as d_price FROM `test_master` INNER JOIN `test_branch_price` ON `test_master`.`id`=`test_branch_price`.`test_fk` and `test_branch_price`.type='1'  LEFT JOIN `lab_doc_discount` ON `lab_doc_discount`.`test_fk`= `test_master`.`id` AND `lab_doc_discount`.`doc_fk`='" . $referral_by . "' AND `lab_doc_discount`.`lab_fk`='" . $branch . "' AND `lab_doc_discount`.`test_fk`='" . $tn[1] . "' and lab_doc_discount.status='1'   WHERE `test_master`.`status`='1' AND `test_branch_price`.`status`='1' AND `test_branch_price`.`branch_fk`='" . $branch . "' AND `test_master`.`id`='" . $tn[1] . "'");

                        if ($result[0]["d_price"] > 0) {
                            $new_price = $result[0]["d_price"];
                        } else {
                            if ($doc_discount_check[0]["discount"] > 0) {
                                $new_price = $result[0]["price"] - ($doc_discount_check[0]["discount"] * $result[0]["price"] / 100);
                            } else {
                                if ($cut > 0) {
                                    $new_price = $result[0]["price"] - ($cut * $result[0]["price"] / 100);
                                } else {
                                    $new_price = $result[0]["price"];
                                }
                            }
                        }
                        $new_price = round($new_price);
                        $price += $new_price;
                        $test_package_name[] = $result[0]["test_name"];
                    }
                    if ($tn[0] == 'p') {

                        $query = $this->db->get_where('test_branch_price', array('test_fk' => $tn[1], "branch_fk" => $branch, "type" => '2', "status" => "1"));
                        $result = $query->result();
                        $query1 = $this->db->get_where('package_master', array('id' => $tn[1]));
                        $result1 = $query1->result();
                        $price += $result[0]->price;
                        $test_package_name[] = $result1[0]->title;
                    }
                }

                $data = array(
                    "order_id" => $order_id,
                    "cust_fk" => $customer,
                    "doctor" => $referral_by,
                    "date" => $date,
                    "price" => $price,
                    "status" => '6',
                    "payment_type" => "Cash On Delivery",
                    "discount" => $discount,
                    "payable_amount" => $payable,
                    "branch_fk" => $branch,
                    "test_city" => $test_city,
                    "note" => $this->input->post('note'),
                    "added_by" => $data["login_data"]["id"],
                    "notify_cust" => $noify_cust,
                    "collection_charge" => $collection_charge,
                    "date" => date("Y-m-d H:i:s"),
                    "is_online" => "0",
                    "booking_info" => $booking_fk
                );

                $insert = $this->samplebook_model->master_fun_insert("job_master", $data);

                foreach ($test as $key) {
                    $tn = explode("-", $key);
                    if ($tn[0] == 't') {

                        $this->samplebook_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $tn[1], "is_panel" => "0"));

                        $result = $this->samplebook_model->get_val("SELECT test_master.`id`,`test_master`.`test_name`,IF(lab_doc_discount.`price`>0,lab_doc_discount.`price`,`test_branch_price`.`price`) AS price,lab_doc_discount.`price` as d_price FROM `test_master` INNER JOIN `test_branch_price` ON `test_master`.`id`=`test_branch_price`.`test_fk` and `test_branch_price`.type='1'  LEFT JOIN `lab_doc_discount` ON `lab_doc_discount`.`test_fk`= `test_master`.`id` AND `lab_doc_discount`.`doc_fk`='" . $referral_by . "' AND `lab_doc_discount`.`lab_fk`='" . $branch . "' AND `lab_doc_discount`.`test_fk`='" . $tn[1] . "' and lab_doc_discount.status='1'   WHERE `test_master`.`status`='1' AND `test_branch_price`.`status`='1' AND `test_branch_price`.`branch_fk`='" . $branch . "' AND `test_master`.`id`='" . $tn[1] . "'");


                        if ($result[0]["d_price"] > 0) {
                            $new_price = $result[0]["d_price"];
                        } else {
                            if ($doc_discount_check[0]["discount"] > 0) {
                                $new_price = $result[0]["price"] - ($doc_discount_check[0]["discount"] * $result[0]["price"] / 100);
                            } else {
                                if ($cut > 0) {
                                    $new_price = $result[0]["price"] - ($cut * $result[0]["price"] / 100);
                                } else {
                                    $new_price = $result[0]["price"];
                                }
                            }
                        }
                        $new_price = round($new_price);
                        $this->samplebook_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "t-" . $tn[1], "price" => $new_price));
                    }
                    if ($tn[0] == 'p') {

                        $this->samplebook_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $tn[1], 'job_fk' => $insert, "status" => "1", "type" => "2"));
                        $tst_price = $this->samplebook_model->get_val("select price from test_branch_price where test_fk='" . $tn[1] . "' and branch_fk='" . $branch . "' and type='2' and status='1'");
                        $this->samplebook_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "p-" . $tn[1], "price" => $tst_price[0]["price"]));
                        $this->check_active_package($tn[1], $insert, $customer);
                    }
                }
                if ($discount > 0) {
                    $this->samplebook_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $added_by, "deleted_by" => "", "job_status" => '', "message_fk" => "24", "date_time" => date("Y-m-d H:i:s")));
                    if ($smsalert == 1) {
                        $job_details = $this->samplebook_model->get_val("SELECT 
  job_master.*,
  IF(booking_info.family_member_fk>0&&customer_family_master.phone>0,customer_family_master.phone,customer_master.mobile) AS phone_no
FROM
  `job_master`  
  INNER JOIN `booking_info` 
    ON `booking_info`.id = `job_master`.booking_info 
  LEFT JOIN `customer_family_master` 
    ON `customer_family_master`.id = booking_info.family_member_fk 
    LEFT JOIN `customer_master` ON `customer_master`.id = booking_info.user_fk
WHERE job_master.id = '" . $insert . "' 
  AND job_master.status != '0' ");
                        $order_discount = round($job_details[0]["price"] * $job_details[0]["discount"] / 100);
                        $payable_price = $job_details[0]["price"] - round($job_details[0]["price"] * $job_details[0]["discount"] / 100);
                        $c_message = "Dear customer, Your \n Booking ID : " . $job_details[0]["order_id"] . "\n Total price : Rs." . $job_details[0]["price"] . " \n Discount price : Rs." . $order_discount . " \n Payable price : Rs." . $payable_price . " \n For any query please call (8101161616).\n Thanks for using Airmed.";
                        $this->samplebook_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $job_details[0]["phone_no"], "message" => $c_message, "created_date" => date("Y-m-d H:i:s")));
                    }
                }
                $received_amount = $this->input->post("received_amount");
                
                // Bhavik 06 july 2018
//                if ($discount_type == 'per') {
//                    if ($discount == '100') {
//                        $this->samplebook_model->master_fun_insert("job_master_receiv_amount", array("job_fk" => $insert, "added_by" => $added_by, "payment_type" => 'DISCOUNT', "amount" => $total_amount, "createddate" => date("Y-m-d H:i:s")));
//                    }
//                }
                
                
                
                if ($received_amount > 0) {
                    $this->samplebook_model->master_fun_insert("job_master_receiv_amount", array("job_fk" => $insert, "added_by" => $added_by, "payment_type" => $payment_via, "amount" => $received_amount, "createddate" => date("Y-m-d H:i:s")));
                    $this->samplebook_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $added_by, "deleted_by" => "", "job_status" => '', "message_fk" => "22", "date_time" => date("Y-m-d H:i:s")));
                }
                $barcode = $insert;
                //$barcode = substr($barcode, -5);
                if (is_numeric($barcode)) {
                    $barcode = 0 + $barcode;
                }
                $this->samplebook_model->master_fun_update("job_master", array("id", $insert), array("barcode" => $barcode));

                $this->session->set_flashdata("success", array("Test successfully Booked."));
                redirect("Admin/Telecallerinvoice/" . $insert . "?pagetype=2");
            } else {
                $this->session->set_flashdata("error", array("something error."));
                redirect("sample_booking/TelecallerCallBooking");
            }
        } else {


            $data['payment_type'] = $this->samplebook_model->get_val("SELECT * from payment_type_master where status='1'");

            $data['branchlist'] = $this->samplebook_model->get_val("SELECT id,branch_code,branch_name,city from branch_master where status='1' and id in($userbranch)");


            $gettestcity = $this->samplebook_model->get_val("SELECT id,`city_fk` FROM `test_cities` WHERE STATUS='1' ");
            $cityjosn = array();
            foreach ($gettestcity as $city) {

                $cityjosn[$city["id"]] = $city["city_fk"];
            }
            $data["cityjosn"] = json_encode($cityjosn);


            $data['error'] = $this->session->flashdata("error");
            $data["relation1"] = $this->samplebook_model->master_fun_get_tbl_val("relation_master", array('status' => "1"), array("name", "asc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('sample_booking_with_panel', $data);
            $this->load->view('footer');
        }
    }

    function get_job_id($city = null) {
        $this->load->library("Util");
        $util = new Util();
        $new_id = $util->get_job_id($city);
        return $new_id;
    }

    function check_active_package($pid, $jid, $uid) {
        /* Nishit active package start */
        $this->load->library("util");
        $util = new Util;
        $util->check_active_package($pid, $jid, $uid);
        /* Nishit active package end */
    }

    function checktestreject() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $jobid = $this->input->post('jid');
        $jobtest = $this->input->post('testall');
        $status = 0;
        $testresult = array();
        $collecttets = array();
        if ($jobid != "" && $jobtest != "") {
            $getrjecttest = $this->samplebook_model->get_val("SELECT GROUP_CONCAT( DISTINCT test_fk) AS pendigtest FROM `samplereject_jobs` WHERE `job_fk`='$jobid' AND complete='0'  AND `test_fk` IN($jobtest) AND STATUS='1' GROUP BY job_fk");

            if ($getrjecttest[0]["pendigtest"] != "" && $getrjecttest[0]["pendigtest"] != null) {
                $status = 1;
                $testresult = explode(",", $getrjecttest[0]["pendigtest"]);
            }

            $collecttetsq = $this->samplebook_model->get_val("SELECT GROUP_CONCAT(DISTINCT `test_fk`) AS test_fk FROM test_sample_barcode WHERE STATUS = '1' AND job_fk = '$jobid' and test_fk NOT IN (SELECT `test_fk` FROM approve_job_test WHERE STATUS='1' AND `job_fk`='$jobid') AND sample_collection='1'  GROUP BY job_fk");

            if ($collecttetsq[0]["test_fk"] != null) {
                $collecttets = explode(",", $collecttetsq[0]["test_fk"]);
            }
        }
        echo json_encode(array("status" => $status, "testresult" => $testresult, "collecttets" => $collecttets));
    }

    function samplerejectiondata() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $adminid = $data["login_data"]["id"];
        $jobid = $this->input->get("jobid");
        $testid = $this->input->get("testid");
        $getdata = $this->samplebook_model->get_val("SELECT rj.id,rj.`remark`,rj.`cretedteddate`,r.`reason`,a.`name`,GROUP_CONCAT(p.`name`) AS phelboname FROM `samplereject_jobs` rj INNER JOIN samplereject_reason r ON r.id=rj.`reason_fk` LEFT JOIN `admin_master` a ON a.id=rj.`creted_by` LEFT JOIN  phlebo_assign_job af ON af.`rejectionid`=rj.`id` and af.status='1'  LEFT JOIN  phlebo_master p ON p.`id`=af.`phlebo_fk` WHERE rj.status='1' AND rj.`job_fk`='$jobid' AND rj.`test_fk`='$testid' GROUP BY rj.id order by rj.id desc");



        if ($getdata != "" && $getdata != null) {
            ?> 

            <table class="table-striped">
                <thead>
                    <tr>
                        <th>Reason</th>
                        <th>Remark</th>
                        <th>Added by</th>
                        <th>Phlebo name</th>
                        <th>Creted date</th>
                        <th>Assign Phlebo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($getdata as $val) { ?>
                        <tr>
                            <td><?= $val["reason"] ?></td>
                            <td><?= $val["remark"] ?></td>
                            <td><?= $val["name"] ?></td>
                            <td><?= $val["phelboname"] ?></td>
                            <td><?= date("d-m-Y", strtotime($val["cretedteddate"])); ?></td>
                            <td><a assgntest="<?= $val["id"]; ?>" href="javascript:void(0)" class="reassignphelbo btn btn-sm btn-success pull-right" >Assign Phlebo</a></td>
                        </tr>	

                    <?php }
                    ?>

                </tbody>
            </table><br>

            <?php
        }
    }

    function samplerejection() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $adminid = $data["login_data"]["id"];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('reason', 'reason', 'trim|required');
        $this->form_validation->set_rules('jobid', 'jobid', 'trim|required');
        $this->form_validation->set_rules('testid', 'testid', 'trim|required');

        if ($this->form_validation->run() != FALSE) {

            $reason = $this->input->post("reason");
            $jobid = $this->input->post("jobid");
            $testid = $this->input->post("testid");
            $ressonremark = $this->input->post("ressonremark");
            $resonpatient = $this->input->post("resonpatient");
            $resonphelbo = $this->input->post("resonphelbo");
            $data = array("job_fk" => $jobid, "test_fk" => $testid, "reason_fk" => $reason, "remark" => $ressonremark, "creted_by" => $adminid, "cretedteddate" => date("Y-m-d H:i:s"));

            $this->samplebook_model->master_fun_insert("samplereject_jobs", $data);

            $this->samplebook_model->master_fun_update_multi("test_sample_barcode", array("job_fk" => $jobid, "test_fk" => $testid, "status" => '1'), array("sample_collection" => '0'));

            echo "1";
            die();
        } else {

            echo "0";
        }
    }

    function check_testhourse() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $adminid = $data["login_data"]["id"];
        $testid = $this->input->get("testid");
        $jobid = $this->input->get("jobid");
        $approvedate = $this->input->get("aprovedate");

        $checkstatus = 0;

        $tesreport = $this->samplebook_model->get_val("SELECT `reporting` FROM `test_master` WHERE STATUS='1' AND id='$testid'");

        if ($tesreport[0]["reporting"] != "") {

            $testtat = $tesreport[0]["reporting"];
            $testrecivedate = $this->samplebook_model->get_val("SELECT `updated_date` FROM `test_sample_barcode` WHERE STATUS='1' and sample_collection='1' AND job_fk='$jobid' AND `test_fk`='$testid'");
            if ($testrecivedate[0]["updated_date"] != "") {

                $teststartdate = $testrecivedate[0]["updated_date"];
                $start_date = new DateTime($teststartdate);
                $since_start = $start_date->diff(new DateTime($testapprovetest));

                $totaltesthorse = $since_start->h;
                $teststatus = $totaltesthorse;
                if ($teststatus > $testtat) {

                    $checkstatus = 1;
                }
            }
        }
        echo $checkstatus;
    }

    /* function branchtest_add() {
      if (!is_loggedin()) {
      redirect('login');
      }

      $branchlist = $this->samplebook_model->get_val("SELECT id,city from branch_master where status='1'");
      foreach($branchlist as $bran){

      $bid=$bran["id"];
      $city=$bran["city"];

      $query = $this->db->query("INSERT INTO test_branch_price (test_fk,price,branch_fk,TYPE) SELECT  test_fk,price,'$bid','1' FROM `test_master_city_price` WHERE STATUS='1' AND city_fk='$city'");

      $query = $this->db->query("INSERT INTO test_branch_price (test_fk,price,branch_fk,TYPE) SELECT  package_fk,d_price,'$bid','2' FROM `package_master_city_price` WHERE status='1' AND city_fk='$city'");

      }

      } */
}
