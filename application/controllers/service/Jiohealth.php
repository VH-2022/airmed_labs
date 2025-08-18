<?php

class Jiohealth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('doctor_app_model');
        $this->load->helper('security');
        $this->load->helper('string');
        $this->load->library('email');
        $this->load->library("Util");
        $this->load->helper('form');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: X-Requested-With");
        $this->token = array('airmedjiohealth2018', 'nishitTest');
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
        $user_track_data = array("url" => $actual_link, "user_details" => $user_info, "data" => $user_post_data, "createddate" => date("Y-m-d H:i:s"), "type" => "service");
        //print_R($user_track_data);
        $app_info = $this->doctor_app_model->master_fun_insert("user_track", $user_track_data);
    }

    function json_data($status, $error_msg, $data = NULL) {
        if ($data == NULL) {
            $data = array();
        }
        $final = array("status" => $status, "error_msg" => $error_msg, "data" => $data);
        return str_replace("null", '" "', json_encode($final));
    }

    public function city_list() {
        $token = $this->input->get_post("token");
        if (in_array($token, $this->token)) {

            $qry1 = "SELECT id,`name` FROM `test_cities` WHERE `status`='1' AND `user_view`='1' ORDER BY `name` ASC";
            $getcity = $this->doctor_app_model->get_result($qry1);

            echo $this->json_data("1", "", $getcity);
        } else {

            echo $this->json_data("0", "invalid token", array());
        }
    }

    public function testlist() {
        $token = $this->input->get_post("token");
        if (in_array($token, $this->token)) {

            $qry1 = "SELECT c.`id`,c.`name`,c.`code` FROM `test_cities` c WHERE c.status='1' and c.user_view='1'";
            $getcity = $this->doctor_app_model->get_result($qry1);
            $resulrarray = array();
            foreach ($getcity as $row) {

                $resulrarray1["cityid"] = $row["id"];
                $resulrarray1["cityname"] = $row["name"];

                $qry2 = "SELECT p.price,t.test_name,t.id AS testid,t.`TEST_CODE` AS testcode  FROM test_master_city_price p INNER JOIN test_master t ON t.`id`=p.`test_fk` AND t.`status`='1' WHERE p.status='1' AND p.`city_fk`='" . $row["id"] . "'";
                $getcityprice = $this->doctor_app_model->get_result($qry2);
                $testrarray = array();
                $testrarraymain = array();
                foreach ($getcityprice as $test) {

                    $testrarray1["testid"] = $test["testid"];
                    /* $testrarray1["testcode"]=$test["testcode"]; */
                    $testrarray1["testname"] = $test["test_name"];
                    $testrarray1["testprice"] = $test["price"];

                    $testrarraymain = array_push($testrarray, $testrarray1);
                }
                $resulrarray1["testdetils"] = $testrarray;
                $testrarraymain = array_push($resulrarray, $resulrarray1);
            }

            echo $this->json_data("1", "", $resulrarray);
        } else {

            echo $this->json_data("0", "invalid token", array());
        }
    }

    public function packagelist() {
        $token = $this->input->get_post("token");
        if (in_array($token, $this->token)) {
            $qry1 = "SELECT c.`id`,c.`name`,c.`code` FROM `test_cities` c WHERE c.status='1'  and c.user_view='1'";
            $getcity = $this->doctor_app_model->get_result($qry1);
            $resulrarray = array();
            foreach ($getcity as $row) {

                $resulrarray1["cityid"] = $row["id"];
                $resulrarray1["cityname"] = $row["name"];

                $qry2 = "SELECT t.id as package_id,p.d_price as price,t.title as test_name,t.id AS testid  FROM package_master_city_price p INNER JOIN package_master t ON t.`id`=p.package_fk AND t.`status`='1' WHERE p.status='1' AND p.`city_fk`='" . $row["id"] . "' AND t.is_view >0";

                $getcityprice = $this->doctor_app_model->get_result($qry2);
                $testrarray = array();
                $testrarraymain = array();
                foreach ($getcityprice as $test) {
                    $package_test1 = $this->doctor_app_model->get_result("SELECT 
  `package_test`.test_fk,
  `test_master`.`test_name` 
FROM
  `package_test` 
  INNER JOIN `test_master` 
    ON `package_test`.`test_fk` = `test_master`.`id` 
WHERE `package_test`.`status` = '1' 
  AND `test_master`.`status` = '1' 
  AND `package_test`.`package_fk` = '" . $test["package_id"] . "'");

                    $testrarray1["packageid"] = $test["testid"];
                    $testrarray1["packagename"] = $test["test_name"];
                    $testrarray1["packageprice"] = $test["price"];
                    $testrarray1["package_include_test"] = $package_test1;

                    $testrarraymain = array_push($testrarray, $testrarray1);
                }
                $resulrarray1["packagedetils"] = $testrarray;
                $testrarraymain = array_push($resulrarray, $resulrarray1);
            }

            echo $this->json_data("1", "", $resulrarray);
        } else {

            echo $this->json_data("0", "invalid token", array());
        }
    }

    function booking() {
        print_r($_REQUEST);
        die();
        $this->load->model("job_model");
        $token = $this->input->get_post("token");
        if (in_array($token, $this->token)) {
            /* Nishit Code Start */
            $name = $this->input->post("name");
            $phone = $this->input->post("phone");
            $email = $this->input->post("email");
            $noify_cust = 0;
            if ($email == '') {
                $email = 'noreply@airmedlabs.com';
            }
            $gender = $this->input->post("gender");
            $dob = $this->input->post("dob");
            $test_city = $this->input->post("test_city");
            $address = $this->input->post("address");
            $note = $this->input->post("note");
            $discount = $this->input->post("discount");
            $payable = $this->input->post("payable");
            $test = $this->input->post("test");
            $package = $this->input->post("package");
            //print_r($test);die();
            $address = $this->input->get_post("address");
            $referral_by = 0;
            $source = 0;
            $phlebo = "";
            $notify = 0;
            $branch = $this->input->post("branch");
            $total_amount = $this->input->post("total_amount");
            if ($branch == '') {
                $branch = 1;
            }
            $payment_via = $this->input->post("payment_via");
            $received_amount = $this->input->post("received_amount");


            /* Validation Start */
            if (empty($test_city)) {
                echo $this->json_data("0", "Required test city.", array());
                die();
            }
            $check_test_city = $this->job_model->get_val("SELECT * FROM `test_cities` WHERE `status`='1' AND `id`='" . $test_city . "'");
            if (empty($check_test_city)) {
                echo $this->json_data("0", "Invalid city.", array());
                die();
            }
            if (empty($name)) {
                echo $this->json_data("0", "Invalid name.", array());
                die();
            }
            if (!preg_match('/^[6-9]\d{9}$/', $phone)) {
                echo $this->json_data("0", "Invalid phone format", "");
                die();
            }
            if (!empty($email)) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo $this->json_data("0", "Invalid email format", "");
                    die();
                }
            }
            if (!in_array(strtoupper($gender), array("MALE", "FEMALE"))) {
                echo $this->json_data("0", "Invalid gender", "");
                die();
            }
            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $dob)) {
                echo $this->json_data("0", "Invalid DOB (Ex.YYYY-MM-DD)", "");
                die();
            }

            $tp_cnt = 0;
            if (count($test) > 0) {
                if (!is_array($test)) {
                    echo $this->json_data("0", "Invalid upload test.", "");
                    die();
                }

                foreach ($test as $tkey) {
                    $check_test_id = $this->job_model->get_val("SELECT * FROM `test_master` WHERE `status`='1' AND `id`='" . $tkey . "'");
                    if (empty($check_test_id)) {
                        echo $this->json_data("0", "Invalid upload test.", "");
                        die();
                    }
                }
                $tp_cnt = 1;
            }



            if (count($package) > 0) {
                if (!is_array($package)) {
                    echo $this->json_data("0", "Invalid upload package.", "");
                    die();
                }

                foreach ($package as $pkey) {
                    $check_test_id = $this->job_model->get_val("SELECT * FROM `package_master` WHERE `status`='1' AND `id`='" . $pkey . "'");
                    if (empty($check_test_id)) {
                        echo $this->json_data("0", "Invalid upload package.", "");
                        die();
                    }
                }
                $tp_cnt = 1;
            }

            if ($tp_cnt == 0) {
                echo $this->json_data("0", "Please upload at least one test or package.", "");
                die();
            }
            /* END */

            if (!empty($name) && !empty($phone) && !empty($gender) && !empty($dob) && !empty($test_city) && (!empty($test) || !empty($package))) {
                $result = $this->job_model->get_val("SELECT * FROM `customer_master` WHERE `status`='1' AND active='1' AND `mobile`='" . $phone . "' ORDER BY id ASC");
                if (empty($result)) {
                    $result1 = $this->job_model->get_val("SELECT * FROM `customer_master` WHERE `status`='1' AND active='1' AND `email`='" . $email . "' ORDER BY id ASC");
                    if (empty($result1) || $email = "noreply@airmedlabs.com") {
                        $password = rand(11111111, 9999999);
                        $c_data = array(
                            "full_name" => $name,
                            "gender" => $gender,
                            "email" => $email,
                            "mobile" => $phone,
                            "address" => $address,
                            "password" => $password,
                            "dob" => $dob,
                            "created_date" => date("Y-m-d H:i:s")
                        );

                        $customer = $this->job_model->master_fun_insert("customer_master", $c_data);
                        //vishal COde Start
                        $rec_pass = $password;

                        //Vishal COde End
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
                            //$this->email->send();
                        }
                    } else {
                        $customer = $result1[0]["id"];
                    }
                } else {
                    $customer = $result[0]["id"];
                    $c1_data = array("full_name" => $name, "gender" => $gender, "email" => $email, "address" => $address, "dob" => $dob);
                    $this->job_model->master_fun_update("customer_master", array("id", $customer), $c1_data);
                }
                //echo $customer;
                // die("OK");
                $price = 0;
                foreach ($test as $key) {
                    $result = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,IF(lab_doc_discount.`price`>0,lab_doc_discount.`price`,`test_master_city_price`.`price`) AS price,lab_doc_discount.`price` as d_price FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk`  LEFT JOIN `lab_doc_discount` ON `lab_doc_discount`.`test_fk`= `test_master`.`id` AND `lab_doc_discount`.`doc_fk`='" . $referral_by . "' AND `lab_doc_discount`.`lab_fk`='" . $branch . "' AND `lab_doc_discount`.`test_fk`='" . $tn[1] . "' and lab_doc_discount.status='1'   WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $key . "'");
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
                foreach ($package as $key) {
                    //print_r(array('package_fk' => $tn[1], "city_fk" => $test_city)); die();
                    $query = $this->db->get_where('package_master_city_price', array('package_fk' => $key, "city_fk" => $test_city, "status" => "1"));
                    $result = $query->result();
                    $query1 = $this->db->get_where('package_master', array('id' => $key));
                    $result1 = $query1->result();
                    $price += $result[0]->d_price;
                    $test_package_name[] = $result1[0]->title;
                }
                /* Nishit book phlebo start */
                $booking_fk = $this->job_model->master_fun_insert("booking_info", array("user_fk" => $customer, "type" => $testforself, "family_member_fk" => $family_mem_id, "address" => $address, "date" => $this->input->get_post("phlebo_date"), "time_slot_fk" => $this->input->get_post("phlebo_time"), "emergency" => 0, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
                /* Nishit book phlebo end */
                $cc = 0;
                $cc1 = 0;
                if ($collection_charge > 0) {
                    $price = $price + $collection_charge;
                    $cc = $collection_charge;
                    $cc1 = 1;
                }
                $order_id = $this->get_job_id($test_city);
                $data = array(
                    "order_id" => $order_id,
                    "cust_fk" => $customer,
                    "doctor" => $referral_by,
                    "other_reference" => $source,
                    "date" => $date,
                    "price" => $price,
                    "status" => '1',
                    "payment_type" => "Cash On Delivery",
                    "discount" => $discount,
                    "payable_amount" => $price,
                    "branch_fk" => $branch,
                    "test_city" => $test_city,
                    "note" => $this->input->post('note'),
                    "added_by" => 335,
                    "booking_info" => $booking_fk,
                    "notify_cust" => $noify_cust,
                    "collection_charge" => $cc1,
                    "cc" => $cc,
                    "date" => date("Y-m-d H:i:s"),
                    "sample_from" => $sample_from,
                    "clinical_history" => $check,
                    "prescription_message" => $message,
                    "prescription_file" => $file,
                    "barcode" => $barcode,
                    "document" => $file_doc,
                    "is_online" => "0"
                );
                //echo "<pre>"; print_r($data); die();
                if ($oldpatient == '1') {
                    $data["oldpatient"] = '1';
                }
                $insert = $this->job_model->master_fun_insert("job_master", $data);
                //$util_cnt->send_new_account($name, $email, $phone, $rec_pass);


                foreach ($test as $key) {
                    $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $key, "is_panel" => "0"));
                    /* $tst_price = $this->job_model->get_val("select price from test_master_city_price where test_fk='" . $tn[1] . "' and city_fk='" . $test_city . "' and status='1'"); */
                    $result = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,IF(lab_doc_discount.`price`>0,lab_doc_discount.`price`,`test_master_city_price`.`price`) AS price,lab_doc_discount.`price` as d_price FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk`  LEFT JOIN `lab_doc_discount` ON `lab_doc_discount`.`test_fk`= `test_master`.`id` AND `lab_doc_discount`.`doc_fk`='" . $referral_by . "' AND `lab_doc_discount`.`lab_fk`='" . $branch . "' AND `lab_doc_discount`.`test_fk`='" . $key . "' and lab_doc_discount.status='1'   WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $test_city . "' AND `test_master`.`id`='" . $key . "'");
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
                    $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "t-" . $key, "price" => $new_price));
                }

                foreach ($package as $key) {
                    $this->job_model->master_fun_insert("book_package_master", array("cust_fk" => $uid, "package_fk" => $key, 'job_fk' => $insert, "status" => "1", "type" => "2"));
                    $tst_price = $this->job_model->get_val("select d_price from package_master_city_price where package_fk='" . $key . "' and city_fk='" . $test_city . "' and status='1'");
                    $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "p-" . $key, "price" => $tst_price[0]["d_price"]));
                    $this->check_active_package($key, $insert, $customer);
                }

                if ($discount > 0) {
                    $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $added_by, "deleted_by" => "", "job_status" => '', "message_fk" => "24", "date_time" => date("Y-m-d H:i:s")));
                }
                $received_amount = $_POST["received_amount"];
                if ($received_amount > 0) {
                    $this->job_model->master_fun_insert("job_master_receiv_amount", array("job_fk" => $insert, "added_by" => $added_by, "payment_type" => $payment_via, "amount" => $received_amount, "createddate" => date("Y-m-d H:i:s")));
                    $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => "", "updated_by" => $added_by, "deleted_by" => "", "job_status" => '', "message_fk" => "22", "date_time" => date("Y-m-d H:i:s")));
                }
                if (empty($barcode)) {
                    $barcode = $insert;
                }
                //print_r(array("barcode" => $barcode)); die();
                $this->job_model->master_fun_update("job_master", array("id", $insert), array("barcode" => $barcode));
                if (!empty($order_id)) {
                    echo $this->json_data("1", "0", array("status" => "success", "order_id" => $order_id));
                } else {
                    echo $this->json_data("0", "Oops somthing wrong please try again.", array());
                }
            } else {
                echo $this->json_data("0", "* fields are required.", array());
            }
            /* Nishit Code End */
        } else {

            echo $this->json_data("0", "invalid token", array());
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

    function ttst() {
        echo "<pre>";
        print_r($_POST);
    }

}
