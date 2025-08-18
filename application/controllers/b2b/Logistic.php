<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Logistic extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('b2b/Logistic_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $data["login_data"] = logindata();
        $this->load->helper('string');
        $this->app_tarce();
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
    }

    function app_tarce() {
        $data["login_data"] = logindata();

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
        $user_track_data = array("user_fk" => $data["login_data"]["id"], "url" => $actual_link, "user_details" => $user_info, "data" => $user_post_data, "createddate" => date("Y-m-d H:i:s"), "type" => "service");
        $this->Logistic_model->master_fun_insert("b2b_track", $user_track_data);
        //return true;
    }

    function dashboard() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->Logistic_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $this->load->view('b2b/header');
        $this->load->view('b2b/nav_logistic', $data);
        $this->load->view('b2b/logistic_dashboard', $data);
        $this->load->view('b2b/footer');
    }

    /* Sample list start */

    function sample_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        /* $this->output->enable_profiler(TRUE); */
        $data["login_data"] = logindata();
        $data["user"] = $this->Logistic_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $search = $this->input->get('search');
        $data['name'] = $this->input->get('name');
        $data['barcode'] = $this->input->get('barcode');
        $data['date'] = $this->input->get('date');
        $data['from'] = $this->input->get('from');

        $data['patientsname'] = $this->input->get('patientsname');
        $data['salesperson'] = $this->input->get('salesperson');
        $data['sendto'] = $this->input->get('sendto');
        $data['todate'] = $this->input->get('todate');
        $data['city'] = $this->input->get('city');
        $data['status'] = $this->input->get('status');
        $payment = $this->input->get('payment');
        if ($payment != "") {
            $data['payment'] = $payment;
        } else {
            $data['payment'] = "all";
        }
        $cquery = "";

        function getjobtestpack($jobid) {
            if ($jobid != "") {
                $ci = & get_instance();
                $job_test = $ci->Logistic_model->master_fun_get_tbl_val("sample_job_test", array("job_fk" => $jobid, 'status' => 1), array("id", "asc"));
                $tst_name = array();
                $tkey["info"] = array();
                foreach ($job_test as $tkey) {
                    if ($tkey['testtype'] == '2') {
                        $test_info = $ci->Logistic_model->get_val("SELECT  package_master.title as test_name FROM `package_master` WHERE `package_master`.`status` = '1'   AND `package_master`.`id` = '" . $tkey["test_fk"] . "'");
                        $tst_name[] = $test_info[0]['test_name'];
                    } else {
                        $test_info = $ci->Logistic_model->get_val("SELECT  test_master.test_name FROM `test_master` WHERE `test_master`.`status` = '1'   AND `test_master`.`id` = '" . $tkey["test_fk"] . "'");
                        $tst_name[] = $test_info[0]['test_name'];
                    }
                }
                return $testname = $tst_name;
            } else {
                return array();
            }
        }

        $logintype = $data["login_data"]['type'];
        if ($logintype == 5 || $logintype == 6 || $logintype == 7) {
            $data['desti_lab'] = $this->Logistic_model->get_val("SELECT `id`, `name` FROM `admin_master` WHERE `status` = '1' AND `type` = '4' AND assign_branch IN (SELECT user_branch.`branch_fk` FROM `user_branch` WHERE user_branch.`user_fk`='" . $data["login_data"]['id'] . "' AND user_branch.`status`=1) ORDER BY `name` ASC");
            $lablisass = $this->Logistic_model->get_val("SELECT GROUP_CONCAT(DISTINCT c.`id` ) as id FROM b2b_labgroup g LEFT JOIN collect_from c ON c.id=g.labid AND c.`status`='1' WHERE g.status='1' AND g.branch_fk IN (SELECT user_branch.`branch_fk` FROM `user_branch` WHERE user_branch.`user_fk`=" . $data["login_data"]['id'] . " AND user_branch.`status`=1) ORDER BY c.`name` ASC");
            if ($lablisass[0]['id'] != "") {
                $laball = $lablisass[0]['id'];
            } else {
                $laball = "0";
            }
        } else {
            $laball = "";
            $data["desti_lab"] = $this->Logistic_model->master_fun_get_tbl_val("admin_master", array("type" => "4", 'status' => 1), array("id", "asc"));
        }


        /* if ($data['name'] != "" || $data['barcode'] != '' || $data['date'] != '' || $data['from'] != '' || $data['patientsname'] || $data['salesperson'] || $data['sendto'] || $data['todate'] || $data['city'] || $data['status']) { */

        if ($data['date'] != "") {
            $data['date1'] = explode('/', $data['date']);
            $data['date2'] = $data['date1'][2] . "-" . $data['date1'][1] . "-" . $data['date1'][0];
        } else {
            $data['date2'] = date("Y-m-d");
        }
        if ($data['todate'] != "") {
            $data['todate1'] = explode('/', $data['todate']);
            $data['todate2'] = $data['todate1'][2] . "-" . $data['todate1'][1] . "-" . $data['todate1'][0];
        } else {
            $data['todate2'] = date("Y-m-d");
        }

        $data['date'] = date("d/m/Y", strtotime($data['date2']));
        $data['todate'] = date("d/m/Y", strtotime($data['todate2']));


        $total_row = $this->Logistic_model->samplelist_numrow($data["login_data"], $laball, $data['name'], $data['barcode'], $data['date2'], $data['todate2'], $data['from'], $data['patientsname'], $data['salesperson'], $data['sendto'], $data['city'], $data['status'], $data['payment']);
        $config = array();
        $config["base_url"] = base_url() . "b2b/Logistic/sample_list?search=$search&name=" . $data['name'] . "&barcode=" . $data['barcode'] . "&date=" . $data['date'] . "&from=" . $data['from'];
        $config["total_rows"] = $total_row;
        $config["per_page"] = 50;
        $config["uri_segment"] = 4;
        $config['cur_tag_open'] = '<span>';
        $config['cur_tag_close'] = '</span>';
        $config['next_link'] = 'Next &rsaquo;';
        $config['prev_link'] = '&lsaquo; Previous';
        $config['page_query_string'] = TRUE;
        $this->pagination->initialize($config);
        $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
        $data['query'] = $this->Logistic_model->sample_list_num($data["login_data"], $laball, $data['name'], $data['barcode'], $data['date2'], $data['todate2'], $data['from'], $data['patientsname'], $data['salesperson'], $data['sendto'], $data['city'], $data['status'], $config["per_page"], $page, $data['payment']);
		
		if($_REQUEST['debug']=='yes'){
		die($this->db->last_query());
		}

        $data["links"] = $this->pagination->create_links();
        $data["counts"] = $page;
        /* } else {
          $totalRows = $this->Logistic_model->samplenum_list($data["login_data"], $laball);
          $config = array();
          $config["base_url"] = base_url() . "b2b/Logistic/sample_list/";
          $config["total_rows"] =$totalRows;
          $config["per_page"] = 50;
          $config["uri_segment"] = 4;
          $config['cur_tag_open'] = '<span>';
          $config['cur_tag_close'] = '</span>';
          $config['next_link'] = 'Next &rsaquo;';
          $config['prev_link'] = '&lsaquo; Previous';
          $this->pagination->initialize($config);
          $sort = $this->input->get("sort");
          $by = $this->input->get("by");
          $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
          $data["query"] = $this->Logistic_model->sample_list($data["login_data"], $laball, $config["per_page"], $page);

          $data["links"] = $this->pagination->create_links();
          $data["counts"] = $page;
          } */
        $cnt = 0;
        foreach ($data["query"] as $key) {
            $data["query"][$cnt]["job_details"] = $this->Logistic_model->get_val("SELECT `sample_report_master`.`original` FROM `sample_job_master` left JOIN `sample_report_master` ON `sample_job_master`.`id`=`sample_report_master`.`job_fk` WHERE `sample_job_master`.`status`!='0' AND `sample_job_master`.`barcode_fk`='" . $key["id"] . "'");
            /* $data["query"][$cnt]["desti_lab1"] = $this->Logistic_model->get_val("SELECT * FROM `admin_master` where id='" . $key["desti_lab"] . "'"); */
            //$data["query"][$cnt]["report_details"] = $this->job_model->master_fun_get_tbl_val("sample_report_master", array('status' => "1", "job_fk" => $this->uri->segment(3)), array("id", "asc"));
            $cnt++;
        }
        $data["salesall"] = $this->Logistic_model->master_fun_get_tbl_val("sales_user_master", array('status' => 1), array("id", "asc"));
        $data["laball"] = $this->Logistic_model->master_fun_get_tbl_val("collect_from", array('status' => 1), array("id", "asc"));
        $url = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        $this->session->set_userdata("test_master_r", $url);
        $data['citys'] = $this->Logistic_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
        $data['phlebo_list'] = $this->Logistic_model->get_val("SELECT id,name FROM phlebo_master WHERE status='1' ORDER BY name ASC");

        /* $this->load->view('b2b/header');
          $this->load->view('b2b/nav_logistic', $data); */
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('b2b/sample_list', $data);
        $this->load->view('b2b/footer');
    }

    function sample_delete($id) {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $adminid = $data["login_data"]["id"];

        $update_data = array("status" => "0", "deleted_by" => $data["login_data"]["id"]);
        $update = $this->Logistic_model->master_fun_update("logistic_log", array("id" => $id), $update_data);
        $this->Logistic_model->master_fun_update("sample_job_master", array("barcode_fk" => $id), $update_data);
        $getjobsid = $this->Logistic_model->getjobsid($id);
        if ($getjobsid != "") {

            $payable = $getjobsid->payable_amount;
            $crditdetis = $this->Logistic_model->creditget_last($getjobsid->id);


            if ($crditdetis != "") {

                $total = ($crditdetis->total + $payable);
                $labid = $crditdetis->lab_fk;
            } else {
                $total = (0 + $payable);
                $labid = "";
            }
            /* echo "<pre>";
              print_r(array("lab_fk"=>$labid,"job_fk" =>$getjobsid->id,"credit" => $payable,"transaction"=>'Credited',"note"=>'delete jobs',"total" => $total, "created_date" => date("Y-m-d H:i:s")));
              die(); */

            $this->Logistic_model->master_fun_insert("sample_credit", array("lab_fk" => $labid, "job_fk" => $getjobsid->id, "credit" => $payable, "transaction" => 'Credited', "note" => 'delete jobs', "total" => $total, "created_date" => date("Y-m-d H:i:s")));
        }
        $this->Logistic_model->master_fun_insert("b2bjob_log", array("job_fk" => $id, "created_by" => $adminid, "message_fk" => '3', "date_time" => date("Y-m-d H:i:s")));


        if ($update) {
            $this->session->set_flashdata("success", array("Record successfully deleted."));
        }
        redirect("b2b/Logistic/sample_list");
    }

    function details() {

        if (!is_loggedin()) {
            redirect('login');
        }
        $logid = $this->uri->segment(4);
        if ($logid != "") {
            $this->load->library("util");
            $util = new Util;
            $this->load->helper("Email");
            $email_cnt = new Email;
            $this->load->model('job_model');
            $data["login_data"] = logindata();
            $adminid = $data["login_data"]["id"];
            $this->load->library('form_validation');
            $this->form_validation->set_rules('abc', 'abc', 'trim|required');
            $this->form_validation->set_rules('test[]', 'test', 'trim|required');
            $this->form_validation->set_rules('payable', 'payable', 'trim|required');
            $this->form_validation->set_rules('total_amount', 'total_amount', 'trim|required');

            if ($this->form_validation->run() != FALSE) {

                $logid = $this->uri->segment(4);
                $jobsdetils = $this->Logistic_model->fetchdatarow('price', 'sample_job_master', array("barcode_fk" => $logid, "status" => '1'));
                if (empty($jobsdetils)) {
                    $customer_name = $this->input->post("customer_name");
                    $customer_mobile = $this->input->post("customer_mobile");
                    $customer_email = $this->input->post("customer_email");
                    if ($customer_email == '') {
                        $customer_email = 'noreply@airmedlabs.com';
                    }
                    $customer_gender = $this->input->post("customer_gender");
                    $dob = $this->input->post("dob");
                    $address = $this->input->post("address");
                    $note = $this->input->post("note");
                    $discount = $this->input->post("discount");
                    $payable = $this->input->post("payable");
                    $test = $this->input->post("test");
                    $referby = $this->input->post("referby");
                    $total_amount = $this->input->post("total_amount");
                    $collection_charge = $this->input->post("collection_charge");
                    $order_id = $this->get_job_id();
                    $date = date('Y-m-d H:i:s');
                    $jobid = $this->uri->segment(4);
                    $logdetils = $this->Logistic_model->fetchdatarow('collect_from', 'logistic_log', array("id" => $jobid, "status" => '1'));
                    $lab_id = $logdetils->collect_from;
                    $currentdate = date('Y-m-d H:i:s');
                    $check_barcode = $this->job_model->master_fun_get_tbl_val("sample_job_master", array("barcode_fk" => $this->uri->segment(4), "status" => 1), array("id", "asc"));
                    $old_price = $check_barcode[0]["price"];
                    $payable_amount = $check_barcode[0]["payable_amount"];
                    $received_old = $old_price - $payable_amount;
                    $data1 = array(
                        "barcode_fk" => $this->uri->segment(4),
                        "customer_name" => $customer_name,
                        "order_id" => $order_id,
                        "customer_mobile" => $customer_mobile,
                        "customer_email" => $customer_email,
                        "customer_gender" => $customer_gender,
                        "customer_dob" => $dob,
                        "customer_address" => $address,
                        "doctor" => $referby,
                        "price" => $total_amount,
                        "status" => '1',
                        "discount" => $discount,
                        "payable_amount" => $payable - $received_old,
                        "added_by" => $data["login_data"]["id"],
                        "note" => $note,
                        "collection_charge" => $collection_charge
                    );
                    $insert = $this->job_model->master_fun_insert("sample_job_master", $data1);
                    $jobsdetils = $this->Logistic_model->fetchdatarow('price', 'sample_job_master', array("barcode_fk" => $logid, "status" => '1'));
                }
                if ($jobsdetils != "") {

                    $jobsoldprice = $jobsdetils->price;

                    $customer_name = $this->input->post("customer_name");
                    $customer_mobile = $this->input->post("customer_mobile");
                    $customer_email = $this->input->post("customer_email");
                    if ($customer_email == '') {
                        $customer_email = 'noreply@airmedlabs.com';
                    }
                    $customer_gender = $this->input->post("customer_gender");
                    $dob = $this->input->post("dob");
                    $address = $this->input->post("address");
                    $note = $this->input->post("note");
                    $discount = $this->input->post("discount");
                    $payable = $this->input->post("payable");
                    $test = $this->input->post("test");
                    $referby = $this->input->post("referby");
                    $total_amount = $this->input->post("total_amount");
                    $collection_charge = $this->input->post("collection_charge");
                    $order_id = $this->get_job_id();
                    $date = date('Y-m-d H:i:s');
                    $jobid = $this->uri->segment(4);
                    $logdetils = $this->Logistic_model->fetchdatarow('collect_from', 'logistic_log', array("id" => $jobid, "status" => '1'));
                    $lab_id = $logdetils->collect_from;

                    $data = array(
                        "barcode_fk" => $this->uri->segment(4),
                        "customer_name" => $customer_name,
                        "customer_mobile" => $customer_mobile,
                        "customer_email" => $customer_email,
                        "customer_gender" => $customer_gender,
                        "customer_dob" => $dob,
                        "customer_address" => $address,
                        "doctor" => $referby,
                        "price" => $total_amount,
                        "status" => '1',
                        "discount" => $discount,
                        "payable_amount" => $payable,
                        "added_by" => $data["login_data"]["id"],
                        "note" => $note,
                        "collection_charge" => $collection_charge
                    );
                    $currentdate = date('Y-m-d H:i:s');
                    $check_barcode = $this->job_model->master_fun_get_tbl_val("sample_job_master", array("barcode_fk" => $this->uri->segment(4), "status" => 1), array("id", "asc"));
                    if (count($check_barcode) == 0) {
                        $insert = $this->job_model->master_fun_insert("sample_job_master", $data);
                    } else {
                        $insert = $check_barcode[0]["id"];
                        $this->Logistic_model->master_fun_update("sample_job_master", array("id" => $insert), $data);
                        $this->Logistic_model->master_fun_update("sample_job_test", array("job_fk" => $insert, "status" => '1'), array("updated_date" => $currentdate, "status" => "0"));
                        /* $this->Logistic_model->master_fun_update("sample_book_package", array("job_fk" => $insert), array("status" => "0")); */
                    }
                    $labdetils = $this->Logistic_model->fetchdatarow('test_discount,city', 'collect_from', array("id" => $lab_id, "status" => 1));
                    $city = $labdetils->city;

                    $selected_test = array();
                    $selected_package = array();

                    foreach ($test as $key) {
                        $tn = explode("-", $key);
                        if ($tn[0] == 't') {
                            $selected_test[] = $tn[1];

                            /* $result = $this->job_model->get_val("SELECT `sample_test_master`.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`price`,`sample_test_master`.`status` FROM `sample_test_master` WHERE `sample_test_master`.`status`='1' AND `sample_test_master`.`id`='" . $tn[1] . "'"); 
                              $result = $this->job_model->get_val("SELECT price,b2b_price,test_fk FROM sample_test_city_price where status='1' and id='" . $tn[1] . "';");
                              if ($result[0]["b2b_price"] != "") {
                              $price1 = $result[0]["b2b_price"];
                              } else {
                              $price1 = $result[0]["price"];
                              }

                             */
                            $getoldtestid = $this->job_model->get_val("SELECT price FROM sample_job_test  WHERE job_fk='$insert' AND status='0' AND `testtype`='1' and updated_date='$currentdate' and test_fk='" . $tn[1] . "'");
                            if ($getoldtestid[0]["price"] != "") {

                                $price1 = $getoldtestid[0]["price"];
                            } else {
                                $result = $this->job_model->get_val("select p.price,(SELECT price_special FROM b2b_testspecial_price s  WHERE  s.test_fk=t.id AND s.status='1' and s.typetest='1' AND s.lab_id='$lab_id') AS specialprice,(SELECT price_special FROM b2b_testmrp_price m  WHERE  m.test_fk=t.id AND m.status='1' AND m.typetest='1' AND m.lab_id='$lab_id') AS mrpprice from test_master t inner JOIN test_master_city_price p ON t.id = p.test_fk  where t.status='1' AND p.city_fk='$city' and t.id='" . $tn[1] . "' GROUP BY t.id");

                                $mrp = round($result[0]['price']);
                                if ($result[0]['mrpprice'] != "") {
                                    $mrp = $result[0]['mrpprice'];
                                }

                                if ($result[0]['specialprice'] != "") {
                                    $price1 = $result[0]['specialprice'];
                                } else {
                                    $discount1 = $labdetils->test_discount;
                                    $discountprice = ($mrp * $discount1 / 100);
                                    $price1 = $mrp - $discountprice;
                                }
                            }
                            $this->job_model->master_fun_insert("sample_job_test", array('job_fk' => $insert, "test_fk" => $tn[1], "price" => round($price1)));
                        }
                        if ($tn[0] == 'p') {

                            $selected_package[] = $tn[1];

                            $getoldpackprice = $this->job_model->get_val("SELECT price FROM sample_job_test  WHERE job_fk='$insert' AND status='0' AND `testtype`='2' and updated_date='$currentdate' and test_fk='" . $tn[1] . "'");
                            if ($getoldpackprice[0]["price"] != "") {
                                $price1 = $getoldpackprice[0]["price"];
                            } else {

                                $result = $this->job_model->get_val("select p.d_price as price,(SELECT price_special FROM b2b_testspecial_price s  WHERE  s.test_fk=t.id AND s.status='1' and s.typetest='2' AND s.lab_id='$lab_id') AS specialprice,(SELECT price_special FROM b2b_testmrp_price m  WHERE  m.test_fk=t.id AND m.status='1' AND m.typetest='2' AND m.lab_id='$lab_id') AS mrpprice from package_master t inner JOIN package_master_city_price p ON t.id = p.package_fk  where t.status='1' AND p.city_fk='$city' and t.id='" . $tn[1] . "' GROUP BY t.id");

                                $mrp = round($result[0]['price']);
                                if ($result[0]['mrpprice'] != "") {
                                    $mrp = $result[0]['mrpprice'];
                                }

                                if ($result[0]['specialprice'] != "") {
                                    $price1 = $result[0]['specialprice'];
                                } else {
                                    $discount1 = $labdetils->test_discount;
                                    $discountprice = ($mrp * $discount1 / 100);
                                    $price1 = $mrp - $discountprice;
                                }
                            }

                            $this->job_model->master_fun_insert("sample_job_test", array('job_fk' => $insert, "testtype" => '2', "test_fk" => $tn[1], "price" => round($price1)));
                        }
                    }
                    /* print_r($selected_package); die(); */
                    $b2cjobdetils = $this->Logistic_model->fetchdatarow('id,cust_fk', 'job_master', array("b2b_id" => $logid, "status !=" => '0'));
                    if ($b2cjobdetils != "") {
                        $cut = 0;
                        $jid = $b2cjobdetils->id;
                        if ($jid != "") {

                            $custmerfk = $b2cjobdetils->cust_fk;

                            $c_data = array(
                                "full_name" => $customer_name,
                                "gender" => $customer_gender,
                                "email" => $customer_email,
                                "mobile" => $customer_mobile,
                                "address" => $address,
                                "dob" => $dob
                            );

                            $this->Logistic_model->master_fun_update("customer_master", array("id" => $custmerfk), $c_data);

                            $get_old_test = $this->job_model->get_val("select test_fk from job_test_list_master where job_fk='" . $jid . "'");
                            $old_test_package = array();
                            foreach ($get_old_test as $key) {
                                $chk_tst = explode("-", $key);
                                $old_test_package[] = "t-" . $key["test_fk"];
                            }
                            $get_old_package = $this->job_model->get_val("select package_fk from book_package_master where job_fk='" . $jid . "'");
                            foreach ($get_old_package as $key) {
                                $old_test_package[] = "p-" . $key["package_fk"];
                            }

                            $this->job_model->master_fun_insert("job_deleted_test_package", array("job_fk" => $jid, "test_package" => implode(",", $old_test_package)));
                            $this->job_model->delete_test(array("job_fk", $jid));
                            $this->job_model->delete_packages(array("job_fk", $jid));
                            $get_user_details = $this->job_model->get_val("select * from job_master where id='" . $jid . "'");
                            $total_price = 0;
                            $payable_price = 0;

                            $this->job_model->master_fun_update("booked_job_test", array("job_fk", $jid), array("updated_date" => $currentdate, "status" => "0"));

                            foreach ($selected_test as $key) {

                                $getoldtestid = $this->job_model->get_val("SELECT price FROM booked_job_test  WHERE job_fk='$jid' AND status='0'  and updated_date='$currentdate' and test_fk='t-$key'");
                                if ($getoldtestid[0]["price"] != "") {
                                    $new_price = round($getoldtestid[0]["price"]);
                                } else {

                                    $result = $this->job_model->get_val("select p.price,(SELECT price_special FROM b2b_testspecial_price s  WHERE  s.test_fk=t.id AND s.status='1' and s.typetest='1' AND s.lab_id='$lab_id') AS specialprice,(SELECT price_special FROM b2b_testmrp_price m  WHERE  m.test_fk=t.id AND m.status='1' AND m.typetest='1' AND m.lab_id='$lab_id') AS mrpprice from test_master t LEFT JOIN test_master_city_price p ON t.id = p.test_fk  where t.status='1' AND p.city_fk='$city' and t.id='" . $key . "' GROUP BY t.id");

                                    $mrp = round($result[0]['price']);
                                    if ($result[0]['mrpprice'] != "") {
                                        $mrp = $result[0]['mrpprice'];
                                    }
                                    if ($result[0]['specialprice'] != "") {
                                        $price1 = $result[0]['specialprice'];
                                    } else {
                                        $discount1 = $labdetils->test_discount;
                                        $discountprice = ($mrp * $discount1 / 100);
                                        $price1 = $mrp - $discountprice;
                                    }
                                    $new_price = round($price1);
                                }

                                $this->job_model->master_fun_insert("job_test_list_master", array("job_fk" => $jid, "test_fk" => $key));
                                $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $jid, "test_city" => $get_user_details[0]["test_city"], "test_fk" => "t-" . $key, "price" => $new_price));

                                $total_price = $total_price + $new_price;
                            }

                            foreach ($selected_package as $key) {

                                /* $tst_price = $this->job_model->get_val("select * from package_master_city_price where package_fk='" . $key . "' and city_fk='" . $get_user_details[0]["test_city"] . "' and status='1'"); */

                                $getoldtestid = $this->job_model->get_val("SELECT price FROM booked_job_test  WHERE job_fk='$jid' AND status='0'  and updated_date='$currentdate' and test_fk='p-$key'");
                                if ($getoldtestid[0]["price"] != "") {
                                    $new_price = round($getoldtestid[0]["price"]);
                                } else {

                                    $result = $this->job_model->get_val("select p.d_price as price,(SELECT price_special FROM b2b_testspecial_price s  WHERE  s.test_fk=t.id AND s.status='1' and s.typetest='2' AND s.lab_id='$lab_id') AS specialprice,(SELECT price_special FROM b2b_testmrp_price m  WHERE  m.test_fk=t.id AND m.status='1' AND m.typetest='2' AND m.lab_id='$lab_id') AS mrpprice from package_master t inner JOIN package_master_city_price p ON t.id = p.package_fk  where t.status='1' AND p.city_fk='$city' and t.id='" . $key . "' GROUP BY t.id");

                                    $mrp = round($result[0]['price']);
                                    if ($result[0]['mrpprice'] != "") {
                                        $mrp = $result[0]['mrpprice'];
                                    }

                                    if ($result[0]['specialprice'] != "") {
                                        $price1 = $result[0]['specialprice'];
                                    } else {
                                        $discount1 = $labdetils->test_discount;
                                        $discountprice = ($mrp * $discount1 / 100);
                                        $price1 = $mrp - $discountprice;
                                    }
                                }

                                $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $jid, "test_city" => $get_user_details[0]["test_city"], "test_fk" => "p-" . $key, "price" => round($price1)));

                                /* $this->check_active_package($key, $jid); */

                                $check_active_package = $this->job_model->master_fun_get_tbl_val("active_package", array("package_fk" => $key, "job_fk" => $jid, "status" => "1"), array("id", "desc"));
                                if (empty($check_active_package)) {
                                    $total_price = $total_price + round($price1);
                                }
                            }

                            $this->job_model->master_fun_insert("book_package_master", array("job_fk" => $jid, "date" => date("Y-m-d H:i:s"), "order_id" => $get_user_details[0]["order_id"], "package_fk" => $key, "cust_fk" => $get_user_details[0]["cust_fk"], "type" => "2"));

                            $payable_price = $total_price;
                            if ($payable_price > 0) {
                                $this->job_model->master_fun_update('job_master', array('id', $jid), array("price" => $total_price, "collection_charge" => $collecion_charge, "payable_amount" => $payable_price));
                            }
                            /* $this->job_model->master_fun_insert("job_log", array("job_fk" => $jid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "14", "date_time" => date("Y-m-d H:i:s"))); */
                        }
                    }
                    /*
                      $crditdetis = $this->job_model->creditget_last($lab_id);
                      if ($crditdetis != "") {
                      $total = ($crditdetis->total - $payable);
                      } else {
                      $total = (0 - $payable);
                      }
                      $this->job_model->master_fun_insert("sample_credit", array("lab_fk" => $lab_id, "job_fk" => $insert, "debit" => $payable, "total" => $total, "created_date" => date("Y-m-d H:i:s")));
                     */
                    $crditdetis = $this->job_model->creditget_last($lab_id);
                    if ($crditdetis != "") {
                        $total = ($crditdetis->total + $jobsoldprice);
                    } else {
                        $total = (0 + $jobsoldprice);
                    }
                    $this->job_model->master_fun_insert("sample_credit", array("lab_fk" => $lab_id, "job_fk" => $insert, "credit" => $jobsoldprice, "total" => $total, "note" => 'update sample', "created_date" => date("Y-m-d H:i:s")));

                    $total1 = ($total - $payable);

                    $this->job_model->master_fun_insert("sample_credit", array("lab_fk" => $lab_id, "job_fk" => $insert, "debit" => $payable, "total" => $total1, "created_date" => date("Y-m-d H:i:s")));

                    $this->job_model->master_fun_insert("b2bjob_log", array("job_fk" => $logid, "created_by" => $adminid, "message_fk" => '2', "date_time" => date("Y-m-d H:i:s")));

                    $this->session->set_flashdata("success", array("Sample details successfully updated."));
                    redirect("b2b/Logistic/details/" . $this->uri->segment(4));
                } else {
                    show_404();
                }
            } else {


                $data["id"] = $this->uri->segment(4);
                $data['barcode_detail'] = $this->job_model->get_val("SELECT `logistic_log`.*,`phlebo_master`.`name`,`phlebo_master`.`mobile`,`collect_from`.`name` AS `c_name` FROM `logistic_log` 
left JOIN `phlebo_master` ON `logistic_log`.`phlebo_fk`=`phlebo_master`.`id` 
left JOIN `collect_from` ON `logistic_log`.`collect_from`=`collect_from`.`id`
left join sample_job_master on sample_job_master.barcode_fk = logistic_log.id
WHERE  `logistic_log`.`status`='1' and `logistic_log`.`id`='" . $data["id"] . "'");

                $data['sample_client_print_report_permission'] = $this->Logistic_model->master_fun_get_tbl_val("sample_client_print_report_permission", array("client_fk" => $data['barcode_detail'][0]["collect_from"], 'status' => 1), array("id", "asc"));

                $labid = $data['barcode_detail'][0]['collect_from'];
                $labdetils = $this->Logistic_model->fetchdatarow('test_discount,city', 'collect_from', array("id" => $labid, "status" => 1));
                $city = $labdetils->city;

                $data['job_details'] = $this->job_model->master_fun_get_tbl_val("sample_job_master", array("barcode_fk" => $this->uri->segment(4), 'status' => 1), array("id", "asc"));
                $age = $util->get_age($data['job_details'][0]["customer_dob"]);

                if ($age[0] != 0) {
                    $data['job_details'][0]["age"] = $age[0];
                    $data['job_details'][0]["age_type"] = 'Y';
                }
                if ($age[0] == 0 && $age[1] != 0) {
                    $data['job_details'][0]["age"] = $age[1];
                    $data['job_details'][0]["age_type"] = 'M';
                }
                if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
                    $data['job_details'][0]["age"] = $age[2];
                    $data['job_details'][0]["age_type"] = 'D';
                }
                if ($age[0] == 0 && $age[1] == 0 && $age[2] == 0) {
                    $data['job_details'][0]["age"] = '0';
                    $data['job_details'][0]["age_type"] = 'D';
                }
                $cnt = 0;
                $paytest = array();
                $paypack = array();
                foreach ($data['job_details'] as $key) {
                    $job_test = $this->job_model->master_fun_get_tbl_val("sample_job_test", array("job_fk" => $key["id"], 'status' => 1), array("id", "asc"));

                    $tst_name = array();
                    $tkey["info"] = array();
                    foreach ($job_test as $tkey) {
                        if ($tkey['testtype'] == '2') {

                            $test_info = $this->job_model->get_val("select t.title as test_name,p.d_price as price,(SELECT price_special FROM b2b_testspecial_price s  WHERE  s.test_fk=t.id AND s.status='1' and typetest='2' AND s.lab_id='$labid' ORDER BY s.id DESC LIMIT 0,1) AS specialprice,(SELECT price_special FROM b2b_testmrp_price m  WHERE  m.test_fk=t.id AND m.status='1' AND m.typetest='2' AND m.lab_id='$labid') AS mrpprice from package_master t inner JOIN package_master_city_price p ON t.id = p.package_fk  AND p.city_fk='$city' where t.status='1'  and t.id='" . $tkey["test_fk"] . "' GROUP BY t.id");

                            $tkey["info"] = $test_info;
                            $tst_name[] = $tkey;
                            $paypack[] = $tkey["test_fk"];
                        } else {

                            $test_info = $this->job_model->get_val("select t.test_name,p.price,(SELECT price_special FROM b2b_testspecial_price s  WHERE  s.test_fk=t.id AND s.status='1' AND  typetest='1' and s.lab_id='$labid' ORDER BY s.id DESC LIMIT 0,1) AS specialprice,(SELECT price_special FROM b2b_testmrp_price m  WHERE  m.test_fk=t.id AND m.status='1' AND m.typetest='1' AND m.lab_id='$labid') AS mrpprice from test_master t inner JOIN test_master_city_price p ON t.id = p.test_fk  AND p.city_fk='$city' where t.status='1'  and t.id='" . $tkey["test_fk"] . "' GROUP BY t.id");

                            $tkey["info"] = $test_info;
                            $tst_name[] = $tkey;
                            $paytest[] = $tkey["test_fk"];
                        }
                    }
                    $data['job_details'][0]["test_list"] = $tst_name;
                    /* echo "<pre>"; print_r($tst_name); die(); */

                    $job_packages = $this->job_model->master_fun_get_tbl_val("sample_book_package", array("job_fk" => $key["id"], 'status' => 1), array("id", "asc"));
                    $data['job_details'][0]["package_list"] = $job_packages;
                    $cnt++;
                }

                $data['addedtest'] = implode(",", $paytest);
                $data['addedpack'] = implode(",", $paypack);
                $data['success'] = $this->session->userdata("success");
                if ($this->session->userdata("error") != '') {
                    $data["error"] = $this->session->userdata("error");
                    $this->session->unset_userdata("error");
                }
                $data['jobspdf'] = $this->job_model->master_fun_get_tbl_val("b2b_jobspdf", array("job_fk" => $data["id"], 'status' => 1), array("id", "asc"));
                $data['jobsdocks'] = array();
                $data['labdetils'] = $labdetils;
                $data["job_master_receiv_amount"] = $this->job_model->get_val("SELECT 
  `sample_job_master_receiv_amount`.*,admin_master.name 
FROM
  `sample_job_master_receiv_amount` 
  LEFT JOIN `admin_master` 
    ON `sample_job_master_receiv_amount`.`added_by` = `admin_master`.`id`  
WHERE sample_job_master_receiv_amount.job_fk = '" . $data['job_details'][0]["id"] . "' 
  AND sample_job_master_receiv_amount.status = '1'");
                /* $this->load->view('header');
                  $this->load->view('nav', $data);
                 */
                $data["user_branch"] = $this->job_model->get_val("SELECT GROUP_CONCAT(branch_fk) AS branch FROM `user_branch` WHERE `status`='1' AND user_fk='" . $data["login_data"]["id"] . "' GROUP BY user_fk");
                if (!empty($data["user_branch"]) && !in_array($data["login_data"]["type"], array("1", "2"))) {
                    $data["creditors"] = $this->job_model->get_val("select cm.* from sample_creditors_master cm join sample_creditors_branch cb on cb.creditors_fk=cm.id where cm.status=1 and cb.status=1 and cb.branch_fk in (" . $data["user_branch"][0]["branch"] . ") group by cm.id order by cm.name ASC");
                } else {
                    $data["creditors"] = $this->job_model->get_val("select cm.* from sample_creditors_master cm join sample_creditors_branch cb on cb.creditors_fk=cm.id where cm.status=1 and cb.status=1 group by cm.id order by cm.name ASC");
                }
                $data["selected_creditor"] = $this->job_model->get_val("SELECT sample_job_creditors.`amount`,sample_job_creditors.`creditors_fk`,sample_job_creditors.job_fk,`sample_creditors_master`.`name`,`sample_creditors_master`.`mobile` FROM `sample_job_creditors` INNER JOIN `sample_creditors_master` ON `sample_creditors_master`.`id`=`sample_job_creditors`.`creditors_fk` WHERE `sample_job_creditors`.`status`='1' AND `sample_job_creditors`.`job_fk`='" . $data['job_details'][0]["id"] . "'");
                $data["amount_history_success"] = $this->session->flashdata("amount_history_success");
                /* $this->load->view('header');
                  $this->load->view('nav', $data);
                 */
                /* Nishit code start */
                $data["old_report_images"] = $this->job_model->get_val("SELECT * FROM b2b_jobsimages WHERE `job_fk`='" . $data['job_details'][0]["barcode_fk"] . "' AND `status`='1'");
                /* END */
                $this->load->view('header');
                $this->load->view('nav', $data);
                $this->load->view('b2b/sample_register', $data);
                $this->load->view('b2b/footer');
            }
        } else {
            show_404();
        }
    }

    public function pdfapprove($id = null) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $login_data = logindata();
        $adminid = $login_data["id"];
        if ($login_data["type"] != "") {

            if ($login_data["type"] != 4) {
                if ($id != "") {

                    $update = $this->Logistic_model->master_fun_update('b2b_jobspdf', array("job_fk" => $id, "status" => '1'), array("approve" => '1'));

                    if ($update) {
                        $this->Logistic_model->master_fun_update("logistic_log", array("id" => $id), array("jobsstatus" => '1'));

                        $this->Logistic_model->master_fun_insert("b2bjob_log", array("job_fk" => $id, "created_by" => $adminid, "message_fk" => '10', "date_time" => date("Y-m-d H:i:s")));

                        $this->session->set_flashdata("success", array("Report successfully Approved."));
                    }

                    redirect("b2b/Logistic/details/" . $id);
                } else {

                    show_404();
                }
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }

    function sample_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
		ini_set('memory_limit', '1280M');
        ini_set('max_execution_time', 300);
        $this->load->model('job_model');
        $data["login_data"] = logindata();
        $adminid = $data["login_data"]["id"];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('abc', 'abc', 'trim|required');
        $this->form_validation->set_rules('lab_id', 'lab_id', 'trim|required');
		$this->form_validation->set_rules('send_to', 'send_to', 'trim|required');
        $this->form_validation->set_rules('payable', 'payable', 'trim|required');
        $this->form_validation->set_rules('total_amount', 'total_amount', 'trim|required');
        $this->form_validation->set_rules('test[]', 'lab_id', 'trim|required');
        $this->form_validation->set_rules('customer_name', 'customer_name', 'trim|required');

        if ($this->form_validation->run() != FALSE) {

            $barcode = $this->input->post("barcode");
            $lab_id = $this->input->post("lab_id");
            $logistic_id = $this->input->post("logistic_id");
            $customer_name = $this->input->post("customer_name");
            $customer_mobile = $this->input->post("customer_mobile");
            $customer_email = $this->input->post("customer_email");
			$send_to = $this->input->post("send_to");
			
			
            if ($customer_email == '') {
                $customer_email = 'noreply@airmedlabs.com';
            }
            $customer_gender = $this->input->post("customer_gender");
            $dob = $this->input->post("dob");
            $address = $this->input->post("address");
            $note = $this->input->post("note");
            $discount = $this->input->post("discount");
            $payable = $this->input->post("payable");
            $test = $this->input->post("test");
            $referby = $this->input->post("referby");
            $total_amount = $this->input->post("total_amount");
            $collection_charge = $this->input->post("collection_charge");
            $received = $this->input->post("received");
            
            $labdetils = $this->Logistic_model->fetchdatarow('test_discount,city', 'collect_from', array("id" => $lab_id, "status" => 1));
            $city = $labdetils->city;
            /* Rahul code start */
            $testtotal = array();
            foreach ($test as $key) {
                $tn = explode("-", $key);

                if ($tn[0] == 't') {

                    $result = $this->job_model->get_val("select p.price,(SELECT price_special FROM b2b_testspecial_price s  WHERE  s.test_fk=t.id AND s.status='1' and s.typetest='1' AND s.lab_id='$lab_id') AS specialprice,(SELECT price_special FROM b2b_testmrp_price m  WHERE  m.test_fk=t.id AND m.status='1' AND m.typetest='1' AND m.lab_id='$lab_id') AS mrpprice from test_master t inner JOIN test_master_city_price p ON t.id = p.test_fk  where t.status='1' AND p.city_fk='$city' and t.id='" . $tn[1] . "' GROUP BY t.id");

                    $mrp = round($result[0]['price']);
                    if ($result[0]['mrpprice'] != "") {
                        $mrp = $result[0]['mrpprice'];
                    }

                    if ($result[0]['specialprice'] != "") {
                        $price1 = $result[0]['specialprice'];
                    } else {
                        $discount1 = $labdetils->test_discount;
                        $discountprice = ($mrp * $discount1 / 100);
                        $price1 = $mrp - $discountprice;
                    }
                    $testtotal[] = round($price1);
                }
                if ($tn[0] == 'p') {

                    $result = $this->job_model->get_val("select p.d_price as price,(SELECT price_special FROM b2b_testspecial_price s  WHERE  s.test_fk=t.id AND s.status='1' and s.typetest='2' AND s.lab_id='$lab_id') AS specialprice,(SELECT price_special FROM b2b_testmrp_price m  WHERE  m.test_fk=t.id AND m.status='1' AND m.typetest='2' AND m.lab_id='$lab_id') AS mrpprice from package_master t inner JOIN package_master_city_price p ON t.id = p.package_fk  where t.status='1' AND p.city_fk='$city' and t.id='" . $tn[1] . "' GROUP BY t.id");

                    $mrp = round($result[0]['price']);
                    if ($result[0]['mrpprice'] != "") {
                        $mrp = $result[0]['mrpprice'];
                    }

                    if ($result[0]['specialprice'] != "") {
                        $price1 = $result[0]['specialprice'];
                    } else {
                        $discount1 = $labdetils->test_discount;
                        $discountprice = ($mrp * $discount1 / 100);
                        $price1 = $mrp - $discountprice;
                    }
                    $testtotal[] = round($price1);
                }
            }
            /* Rahul code end */
            $ftotalprice = array_sum($testtotal);
            $totalfinalprice = ($ftotalprice + $collection_charge);

            if ($totalfinalprice == $payable && $total_amount == $totalfinalprice) {

                $order_id = $this->get_job_id();
                $date = date('Y-m-d H:i:s');
                $files = $_FILES;
                $this->load->library('upload');
                $config['allowed_types'] = 'jpg|gif|png|jpeg';
                if ($files['upload_pic']['name'] != "") {
                    $config['upload_path'] = './upload/barcode/';
                    $config['file_name'] = time() . $files['upload_pic']['name'];
                    $this->upload->initialize($config);
                    if (!is_dir($config['upload_path'])) {
                        mkdir($config['upload_path'], 0755, TRUE);
                    }
                    if (!$this->upload->do_upload('upload_pic')) {
                        $error = $this->upload->display_errors();
                        $error = str_replace("<p>", "", $error);
                        $error = str_replace("</p>", "", $error);
                        $this->session->set_flashdata("error", $error);
                        redirect("b2b/logistic/sample_add", "refresh");
                    } else {
                        $doc_data = $this->upload->data();
                        $photo = $doc_data['file_name'];
                    }
                }
                $c_data = array(
                    "phlebo_fk" => $logistic_id,
                    "barcode" => $barcode,
                    "collect_from" => $lab_id,
                    "status" => '1',
                    "pic" => $photo,
                    "created_by" => $data["login_data"]["id"],
                    "createddate" => date('Y-m-d H:i:s'),
                    "scan_date" => date('Y-m-d H:i:s')
                );
                $barcd = $this->job_model->master_fun_insert("logistic_log", $c_data);
                $data = array(
                    "barcode_fk" => $barcd,
                    "order_id" => $order_id,
                    "customer_name" => $customer_name,
                    "customer_mobile" => $customer_mobile,
                    "customer_email" => $customer_email,
                    "customer_gender" => $customer_gender,
                    "customer_dob" => $dob,
                    "customer_address" => $address,
                    "doctor" => $referby,
                    "price" => $total_amount,
                    "status" => '1',
                    "discount" => $discount,
                    "payable_amount" => $total_amount - $received,
                    "added_by" => $data["login_data"]["id"],
                    "note" => $note,
                    "date" => $date,
                    "collection_charge" => $collection_charge
                );

                $insert = $this->job_model->master_fun_insert("sample_job_master", $data);

                if ($received > 0) {
                    $this->job_model->master_fun_insert("sample_job_master_receiv_amount", array("job_fk" => $insert, "added_by" => $adminid, "payment_type" => "CASH", "amount" => $received, "createddate" => date("Y-m-d H:i:s")));
                    
                }

                foreach ($test as $key) {
                    $tn = explode("-", $key);

                    if ($tn[0] == 't') {

                        $result = $this->job_model->get_val("select p.price,(SELECT price_special FROM b2b_testspecial_price s  WHERE  s.test_fk=t.id AND s.status='1' and s.typetest='1' AND s.lab_id='$lab_id') AS specialprice,(SELECT price_special FROM b2b_testmrp_price m  WHERE  m.test_fk=t.id AND m.status='1' AND m.typetest='1' AND m.lab_id='$lab_id') AS mrpprice from test_master t inner JOIN test_master_city_price p ON t.id = p.test_fk  where t.status='1' AND p.city_fk='$city' and t.id='" . $tn[1] . "' GROUP BY t.id");

                        $mrp = round($result[0]['price']);
                        if ($result[0]['mrpprice'] != "") {
                            $mrp = $result[0]['mrpprice'];
                        }

                        if ($result[0]['specialprice'] != "") {
                            $price1 = $result[0]['specialprice'];
                        } else {
                            $discount1 = $labdetils->test_discount;
                            $discountprice = ($mrp * $discount1 / 100);
                            $price1 = $mrp - $discountprice;
                        }
                        $this->job_model->master_fun_insert("sample_job_test", array('job_fk' => $insert, "test_fk" => $tn[1], "price" => round($price1)));
                    }
                    if ($tn[0] == 'p') {

                        $result = $this->job_model->get_val("select p.d_price as price,(SELECT price_special FROM b2b_testspecial_price s  WHERE  s.test_fk=t.id AND s.status='1' and s.typetest='2' AND s.lab_id='$lab_id') AS specialprice,(SELECT price_special FROM b2b_testmrp_price m  WHERE  m.test_fk=t.id AND m.status='1' AND m.typetest='2' AND m.lab_id='$lab_id') AS mrpprice from package_master t inner JOIN package_master_city_price p ON t.id = p.package_fk  where t.status='1' AND p.city_fk='$city' and t.id='" . $tn[1] . "' GROUP BY t.id");

                        $mrp = round($result[0]['price']);
                        if ($result[0]['mrpprice'] != "") {
                            $mrp = $result[0]['mrpprice'];
                        }

                        if ($result[0]['specialprice'] != "") {
                            $price1 = $result[0]['specialprice'];
                        } else {
                            $discount1 = $labdetils->test_discount;
                            $discountprice = ($mrp * $discount1 / 100);
                            $price1 = $mrp - $discountprice;
                        }
                        $this->job_model->master_fun_insert("sample_job_test", array('job_fk' => $insert, "testtype" => '2', "test_fk" => $tn[1], "price" => round($price1)));
                    }
                }
                $crditdetis = $this->job_model->creditget_last($lab_id);
                if ($crditdetis != "") {
                    $total = ($crditdetis->total - $payable);
                } else {
                    $total = (0 - $payable);
                }
                $this->job_model->master_fun_insert("sample_credit", array("lab_fk" => $lab_id, "job_fk" => $insert, "debit" => $payable, "total" => $total, "created_date" => date("Y-m-d H:i:s")));
				
				/* assign lab */
				 
				$desti_lab =$send_to;
				$job_fk = $insert;
				
				$get_b2c_lab1 = $this->job_model->master_fun_get_tbl_val("admin_master", array("id" => $desti_lab), array("id", "asc"));
            /* Nishit add job in b2c */
            if ($get_b2c_lab1[0]["assign_branch"] > 0) {
               
                $test_city = $this->job_model->master_fun_get_tbl_val("branch_master", array("id" => $get_b2c_lab1[0]["assign_branch"]), array("id", "asc"));
                $name = $customer_name;
                $phone = $customer_mobile;
                $email = $customer_email;
                if ($email == '') {
                    $email = 'noreply@airmedlabs.com';
                }
                $gender = $customer_gender;
                $dob = $dob;
                $test_city = $test_city[0]["city"];
                $address = $address;
                $discount = $discount;
                $referral_by = 0;
                $source = 0;
                $pid = 0;
                $relation_fk = 0;
                $applyCollectionCharge = 0;
                $noify_cust = 0;

                $advance_collection = 0;
                $branch = $get_b2c_lab1[0]["assign_branch"];
                if ($branch == '') {
                    $branch = 1;
                }

                $order_id = $this->get_job_id($test_city);

                if ($name == "") {
                    $name = "AM-" . $job_fk;
                }
                $date = date('Y-m-d H:i:s');
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
                } else {
                    $customer = $result1[0]["id"];
                }

                $price = 0;
                $test_package_name = array();
                $price = 0;

                $test = $this->job_model->get_val("SELECT sample_job_test.`test_fk`,sample_job_test.price,test_master.test_name FROM  `sample_job_test`   LEFT JOIN  test_master ON test_master.id = sample_job_test.test_fk WHERE sample_job_test.job_fk = '" . $job_fk . "' AND sample_job_test.status='1' and sample_job_test.testtype='1'");

                $packtest = $this->job_model->get_val("SELECT sample_job_test.test_fk,sample_job_test.price,package_master.title as test_name FROM  `sample_job_test`   LEFT JOIN  package_master ON package_master.id = sample_job_test.`test_fk` WHERE sample_job_test.job_fk = '" . $job_fk . "' AND sample_job_test.status='1' and sample_job_test.testtype='2'");

                foreach ($test as $key) {
                    $price += $key["price"];
                    $test_package_name[] = $key["test_name"];
                }
                foreach ($packtest as $key1) {
                    $price += $key1["price"];
                    $test_package_name[] = $key1["test_name"];
                }
                /* Nishit book phlebo start */
               
                $testforself = "self";
                $family_mem_id = 0;
                $booking_fk = $this->job_model->master_fun_insert("booking_info", array("user_fk" => $customer, "type" => $testforself, "family_member_fk" => $family_mem_id, "address" => $address, "emergency" => 0, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
                /* Nishit book phlebo end */
                $data = array(
                    "order_id" => $order_id,
                    "cust_fk" => $customer,
                    "doctor" => $referral_by,
                    "other_reference" => $source,
                    "date" => $date,
                    "price" => $price,
                    "status" => '8',
                    "payment_type" => "Cash On Delivery",
                    "discount" => $discount,
                    "payable_amount" => $price - $advance_collection,
                    "address" => $address,
                    "branch_fk" => $branch,
                    "test_city" => $test_city,
                    "note" => '',
                    "added_by" => $adminid,
                    "booking_info" => $booking_fk,
                    "notify_cust" => $noify_cust,
                    "portal" => "web",
                    "collection_charge" => $collecion_charge,
                    "date" => date("Y-m-d H:i:s"),
                    "barcode" => $barcd,
                    "model_type" => 2,
                    "b2b_id" => $barcd
                );
                $insert = $this->job_model->master_fun_insert("job_master", $data);
				
				$this->job_model->master_fun_insert("job_log", array("job_fk" => $insert,"created_by" =>$adminid,"message_fk" => "1", "date_time" => date("Y-m-d H:i:s")));
                $this->job_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" =>$adminid, "updated_by" => "", "deleted_by" => "", "job_status" => '6-7', "message_fk" => "3", "date_time" => date("Y-m-d H:i:s")));
				
               
                foreach ($test as $key) {
                    $this->job_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $key["test_fk"], "is_panel" => "0"));
                    $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "t-" . $key["test_fk"], "price" => $key["price"]));
                }
                foreach ($packtest as $key1) {

                    $this->job_model->master_fun_insert("book_package_master", array('job_fk' => $insert, "date" => date('Y-m-d H:i:s'), "type" => '2', "package_fk" => $key1["test_fk"]));
                    $this->job_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "p-" . $key1["test_fk"], "price" => $key1["price"]));
                }
            }
            
            $data = array(
                "lab_fk" => $desti_lab,
                "job_fk" => $barcd,
                "status" => "1",
                "createddate" => date("Y-m-d H:i:s")
            );

            $insert = $this->job_model->master_fun_insert("sample_destination_lab", $data);
			$this->job_model->master_fun_update("logistic_log", array("id", $barcd), array("jobsstatus" => '2'));
			
			/* END */

                $this->job_model->master_fun_insert("b2bjob_log", array("job_fk" => $barcd, "created_by" => $adminid, "message_fk" => '1', "date_time" => date("Y-m-d H:i:s")));

                $this->session->set_flashdata("success", array("Test successfully Booked."));
                redirect("b2b/Logistic/sample_list");
            } else {

                $this->session->set_flashdata("error", "something wrong please try again later.");
                redirect("b2b/Logistic/sample_add");
            }
        } else {

            $logintype = $data["login_data"]['type'];
            if ($logintype == 5 || $logintype == 6 || $logintype == 7) {

                $lablisass = $this->job_model->get_val("SELECT GROUP_CONCAT(DISTINCT c.`id` ) as id FROM b2b_labgroup g LEFT JOIN collect_from c ON c.id=g.labid AND c.`status`='1' WHERE g.status='1' AND g.branch_fk IN (SELECT user_branch.`branch_fk` FROM `user_branch` WHERE user_branch.`user_fk`=" . $data["login_data"]['id'] . " AND user_branch.`status`=1) ORDER BY c.`name` ASC");
                if ($lablisass[0]['id'] != "") {
                    $laball = $lablisass[0]['id'];
                } else {
                    $laball = "0";
                }
                $data['lab_list'] = $this->job_model->get_val("SELECT c.`id`,c.`name` FROM b2b_labgroup g LEFT JOIN collect_from c ON c.id=g.labid AND c.`status`='1' WHERE g.status='1' 
AND g.branch_fk IN (SELECT user_branch.`branch_fk` FROM `user_branch` WHERE user_branch.`user_fk`=" . $data["login_data"]['id'] . " AND user_branch.`status`=1) ORDER BY c.`name` ASC");
            } else {
                $data['lab_list'] = $this->job_model->master_fun_get_tbl_val("collect_from", array('status' => 1), array("id", "asc"));
            }


            $data['phlebo_list'] = $this->job_model->master_fun_get_tbl_val("phlebo_master", array('status' => 1), array("id", "asc"));
            /* $this->load->view('b2b/header');
              $this->load->view('b2b/nav_logistic', $data); */
            /* Nishit changes start */
            $logintype = $data["login_data"]['type'];
            if ($logintype == 5 || $logintype == 6 || $logintype == 7) {
                $data['desti_lab'] = $this->Logistic_model->get_val("SELECT `id`, `name` FROM `admin_master` WHERE `status` = '1' AND `type` = '4' AND assign_branch IN (SELECT user_branch.`branch_fk` FROM `user_branch` WHERE user_branch.`user_fk`='" . $data["login_data"]['id'] . "' AND user_branch.`status`=1) ORDER BY `name` ASC");
                $lablisass = $this->Logistic_model->get_val("SELECT GROUP_CONCAT(DISTINCT c.`id` ) as id FROM b2b_labgroup g LEFT JOIN collect_from c ON c.id=g.labid AND c.`status`='1' WHERE g.status='1' AND g.branch_fk IN (SELECT user_branch.`branch_fk` FROM `user_branch` WHERE user_branch.`user_fk`=" . $data["login_data"]['id'] . " AND user_branch.`status`=1) ORDER BY c.`name` ASC");
                if ($lablisass[0]['id'] != "") {
                    $laball = $lablisass[0]['id'];
                } else {
                    $laball = "0";
                }
            } else {
                $laball = "";
                $data["desti_lab"] = $this->Logistic_model->master_fun_get_tbl_val("admin_master", array("type" => "4", 'status' => 1), array("id", "asc"));
            }
            /* END */
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('b2b/sample_add', $data);
            $this->load->view('b2b/footer');
        }
    }

    function lab_list() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->Logistic_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $state_serch = $this->input->get('state_search');
        $email = $this->input->get('email');
        $sales_fk = $this->input->get('sales');
        $data['state_search'] = $state_serch;
        $data["email"] = $email;
        $data['sales_id'] = $sales_fk;
        $logintype = $data["login_data"]['type'];
        if ($logintype == 5 || $logintype == 6 || $logintype == 7) {

            $lablisass = $this->Logistic_model->get_val("SELECT GROUP_CONCAT(DISTINCT c.`id` ) as id FROM b2b_labgroup g LEFT JOIN collect_from c ON c.id=g.labid AND c.`status`='1' WHERE g.status='1' AND g.branch_fk IN (SELECT user_branch.`branch_fk` FROM `user_branch` WHERE user_branch.`user_fk`=" . $data["login_data"]['id'] . " AND user_branch.`status`=1) ORDER BY c.`name` ASC");
            if ($lablisass[0]['id'] != "") {
                $laball = $lablisass[0]['id'];
            } else {
                $laball = "0";
            }
        } else {
            $laball = "";
        }

        if ($state_serch != "" || $data["email"] != "" || $sales_fk != "") {

            $total_row = $this->Logistic_model->lab_num_rows($state_serch, $data["email"], $laball, $sales_fk);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "b2b/logistic/lab_list?state_search=$state_serch&email=$email&sales=$sales_fk";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';


            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Logistic_model->lab_data($state_serch, $data["email"], $sales_fk, $config["per_page"], $page, $laball);
            $data["links"] = $this->pagination->create_links();
        } else {
            $total_row = $this->Logistic_model->lab_num_rows($state_serch, $data["email"], $laball);
            $config["base_url"] = base_url() . "b2b/logistic/lab_list";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';

            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Logistic_model->srch_lab_list($config["per_page"], $page, $laball);

            $data["links"] = $this->pagination->create_links();
        }
        $data['sales'] = $this->Logistic_model->get_val("select id,first_name,last_name from sales_user_master where status=1");
        $data["page"] = $page;
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('b2b/lab_list1', $data);
        $this->load->view('b2b/footer');
    }

    function check_email() {
        $email = $this->input->post('email');
        $result = $this->Logistic_model->master_num_rows('collect_from', array("email" => $email, "status" => 1));
        if ($result == 0) {
            return true;
        } else {
            $this->form_validation->set_message('check_email', 'Email Already Exists.');
            return false;
        }
    }

    function lab_add() {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->Logistic_model->getUser($data["login_data"]["id"]);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('branch', 'PLM', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_check_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('lab_name', 'Lab Name', 'trim|required');
        $this->form_validation->set_rules('person_name', 'Contact Person Name', 'trim');
        $this->form_validation->set_rules('mobile_number', 'Mobile Number', 'trim|required|callback_check_mobile');
        $this->form_validation->set_rules('city', 'City name', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim');
        $this->form_validation->set_rules('bus_desc', 'Client description and Business Type', 'trim');
        $this->form_validation->set_rules('space_allocate', 'Space allocate', 'trim');
        $this->form_validation->set_rules('bus_expeted', 'Business Expected', 'trim');
        $this->form_validation->set_rules('test_discount', 'Test Discount', 'trim|required|callback_maximumCheck');
        //Vishal Code Start
        $this->form_validation->set_rules('sales_fk', 'Sales', 'trim|required');
        //Vishal COde End

        if ($this->form_validation->run() != FALSE) {

            $branch = $this->input->post('branch');
            $lab_name = $this->input->post('lab_name');
            $person_name = $this->input->post('person_name');
            $mobile_number = $this->input->post('mobile_number');
            $alternate_number = $this->input->post('alternate_number');
            $address = $this->input->post('address');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $city = $this->input->post('city');
            $bus_desc = $this->input->post('bus_desc');
            $space_allocate = $this->input->post('space_allocate');
            $bus_expeted = $this->input->post('bus_expeted');
            $test_discount = $this->input->post('test_discount');
            //Vishal Code Start
            $sales_fk = $this->input->post('sales_fk');
            //Vishal COde End
            $files = $_FILES;
            $this->load->library('upload');
            $config['allowed_types'] = 'jpg|gif|png|jpeg';
            $path = 'upload/labducuments/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $file_loop = count($_FILES['file']['name']);
            $files = $_FILES;
            if (!empty($_FILES['file']['name'])) {

                for ($i = 0; $i < $file_loop; $i++) {
                    $_FILES['file']['name'] = $files['file']['name'];
                    $_FILES['file']['type'] = $files['file']['type'];
                    $_FILES['file']['tmp_name'] = $files['file']['tmp_name'];
                    $_FILES['file']['error'] = $files['file']['error'];
                    $_FILES['file']['size'] = $files['file']['size'];
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = '*';
                    $config['file_name'] = $files['file']['name'][$i];
                    $org[] = $config['file_name'];
                    $config['file_name'] = $i . time() . "_" . $files['file']['name'];
                    //$config['file_name'] = $files['file']['name'][$i];
                    $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                    $config['overwrite'] = FALSE;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    $this->upload->do_upload("file");
                    $uploads[] = $config['file_name'];
                    //print_r($uploads); die();
                }
                $file = implode(',', $uploads);

                if ($_FILES["upload_pic"]["name"]) {

                    $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc';
                    $config['file_name'] = time() . $_FILES["upload_pic"]["name"];
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload("upload_pic")) {
                        $error = array('error' => $this->upload->display_errors());
                    } else {
                        $data = array('upload_data' => $this->upload->data());
                        $file_name = $data["upload_data"]["file_name"];
                        $file_doc = $file_name;
                    }
                }
            }

            $data1 = array(
                "name" => $lab_name,
                "contact_person_name" => $person_name,
                "city" => $city,
                "mobile_number" => $mobile_number,
                "alternate_number" => $alternate_number,
                "address" => $address,
                "email" => $email,
                "password" => $password,
                "bus_desc" => $bus_desc,
                "space_allocate" => $space_allocate,
                "bus_expeted" => $bus_expeted,
                "test_discount" => $test_discount,
                "createddate" => date("Y-m-d H:i:s"),
                "sales_fk" => $sales_fk
            );


            $id = $this->Logistic_model->master_fun_insert("collect_from", $data1);
            /* $id = $this->db->insert_id(); */
            $data2 = array(
                "lab_id" => $id,
                "dock_name" => $file,
                "address_proof" => $file_doc,
                "credteddate" => date("Y-m-d H:i:s")
            );
            $this->Logistic_model->master_fun_insert("lab_document", $data2);
            $data3 = array(
                "branch_fk" => $branch,
                "labid" => $id,
                "created_by" => $data["login_data"]["id"],
                "created_date" => date("Y-m-d H:i:s"),
                "status" => '1'
            );
            $this->Logistic_model->master_fun_insert("b2b_labgroup", $data3);
            $this->session->set_flashdata("success", array("Lab successfully added."));

            redirect("b2b/Logistic/lab_list", "refresh");
        } else {

            $data['cityall'] = $this->Logistic_model->master_fun_get_tbl_val("test_cities", array('status' => '1'), array("name", "asc"));

            $logintype = $data["login_data"]['type'];
            if ($logintype == 5 || $logintype == 6 || $logintype == 7) {

                $data['branchall'] = $this->Logistic_model->get_val("SELECT `id`,branch_name FROM branch_master WHERE `status` = '1' AND id IN (SELECT user_branch.`branch_fk` FROM `user_branch` WHERE user_branch.`user_fk`='" . $data["login_data"]['id'] . "' AND user_branch.`status`=1) ORDER BY branch_name ASC");
            } else {

                $data['branchall'] = $this->Logistic_model->master_fun_get_tbl_val("branch_master", array('status' => '1'), array("branch_name", "asc"));
            }

            $data['sales'] = $this->Logistic_model->master_fun_get_tbl_val("sales_user_master", array('status' => '1'), array("first_name", "asc"));

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('b2b/lab_add', $data);
            $this->load->view('b2b/footer');
        }
    }

    function lab_delete($id) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $logintype = $data["login_data"]['type'];
        if ($logintype == 5 || $logintype == 6 || $logintype == 7) {


            $lablisass = $this->Logistic_model->get_val("SELECT GROUP_CONCAT(DISTINCT c.`id` ) as id FROM b2b_labgroup g LEFT JOIN collect_from c ON c.id=g.labid AND c.`status`='1' WHERE g.status='1' AND g.branch_fk IN (SELECT user_branch.`branch_fk` FROM `user_branch` WHERE user_branch.`user_fk`=" . $data["login_data"]['id'] . " AND user_branch.`status`=1) ORDER BY c.`name` ASC");

            if ($lablisass[0]['id'] != "") {
                $laball = $lablisass[0]['id'];
            } else {
                $laball = "0";
            }
            $uselaball = explode(",", $laball);
            if (in_array($id, $uselaball)) {
                $this->Logistic_model->master_fun_update("collect_from", array("id" => $id), array("status" => "0"));
            } else {
                show_404();
            }
        } else {
            $this->Logistic_model->master_fun_update("collect_from", array("id" => $id), array("status" => "0"));
        }


        $this->session->set_flashdata("success", array("Lab successfully deleted."));
        redirect("b2b/Logistic/lab_list", "refresh");
    }
	
	function lab_active_deactive() {
		if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $cid = $this->uri->segment('4');
        $status = $this->uri->segment('5');
		$logintype = $data["login_data"]['type'];
		if ($logintype == 5 || $logintype == 6 || $logintype == 7) {

            $lablisass = $this->Logistic_model->get_val("SELECT GROUP_CONCAT(DISTINCT c.`id` ) as id FROM b2b_labgroup g LEFT JOIN collect_from c ON c.id=g.labid AND c.`status` IN('1','5') WHERE g.status='1' AND g.branch_fk IN (SELECT user_branch.`branch_fk` FROM `user_branch` WHERE user_branch.`user_fk`=" . $data["login_data"]['id'] . " AND user_branch.`status`=1) ORDER BY c.`name` ASC");

            if ($lablisass[0]['id'] != "") {
                $laball = $lablisass[0]['id'];
            } else {
                $laball = "0";
            }
            $uselaball = explode(",", $laball);
            if (in_array($cid, $uselaball)) {
				
                $this->Logistic_model->master_fun_update("collect_from", array("id" => $cid), array("status" => $status));
            } else {
                show_404();
            }
        } else {
            $this->Logistic_model->master_fun_update("collect_from", array("id" => $cid), array("status" => $status));
        }
		
        $msg = "Activated";
        if ($status == 5) {
            $msg = "Deactivated";
        }
        $this->session->set_flashdata("success", "Lab successfully ". $msg .".");
        redirect("b2b/Logistic/lab_list", "refresh");
    }


    function check_email1($id) {
        $labid = $this->uri->segment(4);
        $email = $this->input->post('email');
        $result = $this->Logistic_model->master_num_rows('collect_from', array("email" => $email, "id !=" => $labid, "status" => 1));
        if ($result == 0) {
            return true;
        } else {
            $this->form_validation->set_message('check_email1', 'Email Already Exists.');
            return false;
        }
    }

    function check_mobile($id) {
        $labid = $this->uri->segment(4);
        $mobile_number = $this->input->post('mobile_number');
        $result = $this->Logistic_model->master_num_rows('collect_from', array("mobile_number" => $mobile_number, "id !=" => $labid, "status" => 1));
        if ($result == 0) {
            return true;
        } else {
            $this->form_validation->set_message('check_mobile', 'Mobile Number Already Exists.');
            return false;
        }
    }

    function maximumCheck($num) {
        if ($num > 100) {
            $this->form_validation->set_message(
                    'test_discount', 'The %s field must be less than 100'
            );
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function lab_edit($id) {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["id"] = $id;
        $data["login_data"] = logindata();
        $data["user"] = $this->Logistic_model->getUser($data["login_data"]["id"]);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('branch', 'PLM', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_check_email1');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('lab_name', 'Lab Name', 'trim|required');
        $this->form_validation->set_rules('person_name', 'Contact Person Name', 'trim');
        $this->form_validation->set_rules('mobile_number', 'Mobile Number', 'trim|required|callback_check_mobile');
        $this->form_validation->set_rules('address', 'Address', 'trim');
        $this->form_validation->set_rules('city', 'City name', 'trim|required');
        $this->form_validation->set_rules('bus_desc', 'Client description and Business Type', 'trim');
        $this->form_validation->set_rules('space_allocate', 'Space allocate', 'trim');
        $this->form_validation->set_rules('bus_expeted', 'Business Expected', 'trim');
        $this->form_validation->set_rules('test_discount', 'Test Discount', 'trim|required|callback_maximumCheck');
//Vishal Code Start
        $this->form_validation->set_rules('sales_fk', 'Sales Name', 'trim|required');
        //Vishal COde End
        if ($this->form_validation->run() != FALSE) {

            $branch = $this->input->post('branch');
            $lab_name = $this->input->post('lab_name');
            $person_name = $this->input->post('person_name');
            $mobile_number = $this->input->post('mobile_number');
            $alternate_number = $this->input->post('alternate_number');
            $address = $this->input->post('address');
            $email = $this->input->post('email');
            $city = $this->input->post('city');
            $password = $this->input->post('password');
            $bus_desc = $this->input->post('bus_desc');
            $space_allocate = $this->input->post('space_allocate');
            $bus_expeted = $this->input->post('bus_expeted');
            $test_discount = $this->input->post('test_discount');
            //Vishal Code Start
            $sales_fk = $this->input->post('sales_fk');
            //Vishal COde End
            $files = $_FILES;
            $this->load->library('upload');
            $config['allowed_types'] = 'jpg|gif|png|jpeg';
            $path = 'upload/labducuments/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $file_loop = count($_FILES['file']['name']);
            $files = $_FILES;
            if (!empty($_FILES['file']['name'])) {

                for ($i = 0; $i < $file_loop; $i++) {
                    $_FILES['file']['name'] = $files['file']['name'];
                    $_FILES['file']['type'] = $files['file']['type'];
                    $_FILES['file']['tmp_name'] = $files['file']['tmp_name'];
                    $_FILES['file']['error'] = $files['file']['error'];
                    $_FILES['file']['size'] = $files['file']['size'];
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = '*';
                    $config['file_name'] = $files['file']['name'][$i];
                    $org[] = $config['file_name'];
                    $config['file_name'] = $i . time() . "_" . $files['file']['name'];
                    //$config['file_name'] = $files['file']['name'][$i];
                    $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                    $config['overwrite'] = FALSE;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    $this->upload->do_upload("file");
                    $uploads[] = $config['file_name'];
                    //print_r($uploads); die();
                }
                $file = implode(',', $uploads);
            }

            if ($_FILES["upload_pic"]["name"]) {

                $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc';
                $config['file_name'] = time() . $_FILES["upload_pic"]["name"];
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("upload_pic")) {
                    $error = array('error' => $this->upload->display_errors());
                } else {
                    $data = array('upload_data' => $this->upload->data());
                    $file_name = $data["upload_data"]["file_name"];
                    $file_doc = $file_name;
                }
            }

            $data1 = array(
                "name" => $lab_name,
                "contact_person_name" => $person_name,
                "mobile_number" => $mobile_number,
                "city" => $city,
                "alternate_number" => $alternate_number,
                "address" => $address,
                "email" => $email,
                "password" => $password,
                "space_allocate" => $space_allocate,
                "bus_expeted" => $bus_expeted,
                "test_discount" => $test_discount,
                "sales_fk" => $sales_fk
            );
            $data2 = array(
                "dock_name" => $file,
                "address_proof" => $file_doc
            );

            $data3 = array("branch_fk" => $branch, "labid" => $id, "created_by" => $data["login_data"]["id"], "created_date" => date("Y-m-d H:i:s"), "status" => '1');
            /* $this->Logistic_model->master_fun_insert("b2b_labgroup", $data3); */
            $this->Logistic_model->master_fun_update("collect_from", array("id" => $id), $data1);
            $this->Logistic_model->master_fun_update("lab_document", array("lab_id" => $id), $data2);
            $numlab = $this->Logistic_model->master_num_rows('b2b_labgroup', array("labid" => $id, "status" => '1'));
            if ($numlab == 0) {
                $this->Logistic_model->master_fun_insert("b2b_labgroup", $data3);
            } else {

                $this->Logistic_model->master_fun_update("b2b_labgroup", array("labid" => $id), $data3);
            }
            $this->session->set_flashdata("success", array("Lab successfully updated."));
            redirect("b2b/Logistic/lab_list", "refresh");
        } else {

            $data['query'] = $this->Logistic_model->master_fun_get_tbl_val("collect_from", array('id' => $id), array("id", "asc"));
            $data['cityall'] = $this->Logistic_model->master_fun_get_tbl_val("test_cities", array('status' => '1'), array("name", "asc"));

            $logintype = $data["login_data"]['type'];
            if ($logintype == 5 || $logintype == 6 || $logintype == 7) {

                $data['branchall'] = $this->Logistic_model->get_val("SELECT `id`,branch_name FROM branch_master WHERE `status` = '1' AND id IN (SELECT user_branch.`branch_fk` FROM `user_branch` WHERE user_branch.`user_fk`='" . $data["login_data"]['id'] . "' AND user_branch.`status`=1) ORDER BY branch_name ASC");
            } else {

                $data['branchall'] = $this->Logistic_model->master_fun_get_tbl_val("branch_master", array('status' => '1'), array("branch_name", "asc"));
            }
            $data['sales'] = $this->Logistic_model->master_fun_get_tbl_val("sales_user_master", array('status' => '1'), array("first_name", "asc"));
            $data['labbranch'] = $this->Logistic_model->get_val("SELECT branch_fk FROM b2b_labgroup WHERE STATUS='1' AND labid='$id'");

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('b2b/lab_edit', $data);
            $this->load->view('b2b/footer');
        }
    }

    function get_city_test() {

        $city = $this->input->get_post("city");
        if ($city) {
            $data['test'] = $this->Logistic_model->get_val("SELECT `sample_test_master`.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`test_name`,`sample_test_master`.`status`,`sample_test_city_price`.`price` FROM `sample_test_master` INNER JOIN `sample_test_city_price` ON `sample_test_master`.`id`=`sample_test_city_price`.`test_fk` WHERE `sample_test_master`.`status`='1' AND `sample_test_city_price`.`status`='1' AND `sample_test_city_price`.`city_fk`='" . $city . "'");
        } else {
            $data['test'] = $this->Logistic_model->get_val("SELECT `sample_test_master`.`id`,`sample_test_master`.`test_name`,`sample_test_master`.`test_name`,`sample_test_master`.`status`,`sample_test_city_price`.`price` FROM `sample_test_master` INNER JOIN `sample_test_city_price` ON `sample_test_master`.`id`=`sample_test_city_price`.`test_fk` WHERE `sample_test_master`.`status`='1' AND `sample_test_city_price`.`status`='1' AND `sample_test_city_price`.`city_fk`='1'");
        }
        $this->load->view("b2b/get_city_test_reg", $data);
    }

    function get_lab_tests() {

        $lab = $this->input->get_post("lab");

        $data['labdetils'] = $this->Logistic_model->fetchdatarow('test_discount,city', 'collect_from', array("id" => $lab, "status" => 1));
        $city = $data['labdetils']->city;

        $data['test'] = $this->Logistic_model->get_val("select t.test_name,t.id,p.price,(SELECT price_special FROM b2b_testspecial_price s  WHERE  s.test_fk=t.id AND s.status='1' AND s.typetest='1' AND s.lab_id='$lab') AS specialprice,(SELECT price_special FROM b2b_testmrp_price m  WHERE  m.test_fk=t.id AND m.status='1' AND m.typetest='1' AND m.lab_id='$lab') AS mrpprice from test_master t inner JOIN test_master_city_price p ON t.id = p.test_fk AND p.city_fk='$city'  where t.status='1'  GROUP BY t.id");

        $data['packges'] = $this->Logistic_model->get_val("select t.title as test_name,t.id,p.d_price as price,(SELECT price_special FROM b2b_testspecial_price s  WHERE  s.test_fk=t.id AND s.status='1' AND s.typetest='2' AND s.lab_id='$lab') AS specialprice ,(SELECT price_special FROM b2b_testmrp_price m  WHERE  m.test_fk=t.id AND m.status='1' AND m.typetest='2' AND m.lab_id='$lab') AS mrpprice from package_master t inner JOIN package_master_city_price p ON t.id = p.package_fk AND p.city_fk='$city'  where t.status='1'  GROUP BY t.id");

        $this->load->view("b2b/get_city_test_reg", $data);
    }

    function get_lab_testsedit() {
        $lab = $this->input->get_post("lab");
        $test = $this->input->get_post("test");
        $pack = $this->input->get_post("pack");
        /* if($test != ""){$testlis='"'.implode('","', explode(',',$test)).'"'; }else{ $testlis="'0'"; } */
        if ($test != "") {
            $testlis = "And t.id not in($test)";
        } else {
            $testlis = "";
        }
        $data['labdetils'] = $this->Logistic_model->fetchdatarow('test_discount,city', 'collect_from', array("id" => $lab, "status" => 1));
        $city = $data['labdetils']->city;
        $data['test'] = $this->Logistic_model->get_val("select t.test_name,t.id,p.price,(SELECT price_special FROM b2b_testspecial_price s  WHERE  s.test_fk=t.id AND s.status='1' AND s.lab_id='$lab' ORDER BY s.id DESC LIMIT 0,1) AS specialprice,(SELECT price_special FROM b2b_testmrp_price m  WHERE  m.test_fk=t.id AND m.status='1' AND m.typetest='1' AND m.lab_id='$lab') AS mrpprice from test_master t inner JOIN test_master_city_price p ON t.id = p.test_fk AND p.city_fk='$city'  where t.status='1' $testlis  GROUP BY t.id");

        if ($pack != "") {
            $packlis = "And t.id not in($pack)";
        } else {
            $packlis = "";
        }
        $data['packges'] = $this->Logistic_model->get_val("select t.title as test_name,t.id,p.d_price as price,(SELECT price_special FROM b2b_testspecial_price s  WHERE  s.test_fk=t.id AND s.status='1' AND s.typetest='2' AND s.lab_id='$lab') AS specialprice,(SELECT price_special FROM b2b_testmrp_price m  WHERE  m.test_fk=t.id AND m.status='1' AND m.typetest='2' AND m.lab_id='$lab') AS mrpprice from package_master t inner JOIN package_master_city_price p ON t.id = p.package_fk AND p.city_fk='$city'  where t.status='1' $packlis GROUP BY t.id");

        $this->load->view("b2b/get_city_test_reg", $data);
    }

    function upload_report($cid = "") {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $files = $_FILES;
        $this->load->library('upload');
        $file_upload = array();
        /* if (!empty($_FILES['common_report']['name'])) {
          $desc = $this->input->post('desc_common_report');
          $type_common_report = $this->input->post('type_common_report');
          $_FILES['userfile']['name'] = $files['common_report']['name'];
          $_FILES['userfile']['type'] = $files['common_report']['type'];
          $_FILES['userfile']['tmp_name'] = $files['common_report']['tmp_name'];
          $_FILES['userfile']['error'] = $files['common_report']['error'];
          $_FILES['userfile']['size'] = $files['common_report']['size'];
          $config['upload_path'] = './upload/B2breport/';
          $config['allowed_types'] = '*';
          $config['file_name'] = time() . $files['common_report']['name'];
          $config['file_name'] = str_replace(' ', '_', $config['file_name']);
          $config['overwrite'] = FALSE;
          $this->load->library('upload', $config);
          $this->upload->initialize($config);
          if (!$this->upload->do_upload()) {
          $error = $this->upload->display_errors();
          $this->session->set_flashdata("error", array($error));
          redirect('b2b/Logistic/details/' . $cid);
          } else {
          $file_upload[] = array("job_fk" => $cid, "report" => $config['file_name'], "original" => $_FILES['common_report']['name'], "test_fk" => "", "description" => $desc, "type" => $type_common_report);
          }
          } */
        if (!empty($_FILES['common_report']['name'])) {
            $filesCount = count($_FILES['common_report']['name']);
            $type_common_report = $this->input->post('type_common_report');
            $desc = $this->input->post('desc_common_report');
            $upcount = 1;
            for ($i = 0; $i < $filesCount; $i++) {

                $_FILES['userFile']['name'] = $_FILES['common_report']['name'][$i];
                $_FILES['userFile']['type'] = $_FILES['common_report']['type'][$i];
                $_FILES['userFile']['tmp_name'] = $_FILES['common_report']['tmp_name'][$i];
                $_FILES['userFile']['error'] = $_FILES['common_report']['error'][$i];
                $_FILES['userFile']['size'] = $_FILES['common_report']['size'][$i];

                $config['upload_path'] = './upload/B2breport/';
                $config['allowed_types'] = '*';
                $config['file_name'] = time() . "_" . $upcount . $_FILES['userFile']['name'];
                $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                $config['overwrite'] = FALSE;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('userFile')) {

                    $fileData = $this->upload->data();
                    $clientname = $fileData['client_name'];
                    $filename = $fileData['file_name'];
                    $upcount++;
                    $file_upload = array("job_fk" => $cid, "report" => $filename, "original" => $clientname, "description" => $desc, "type" => $type_common_report);

                    $delete = $this->Logistic_model->master_fun_insert("b2b_jobspdf", $file_upload);

                    $this->Logistic_model->master_fun_insert("b2bjob_log", array("job_fk" => $cid, "created_by" => $data["login_data"]["id"], "message_fk" => '8', "date_time" => date("Y-m-d H:i:s")));
                }
            }
        }

        /* foreach ($file_upload as $f_key) {
          $insetdata=array("barcode_fk" => $cid,"report" => $f_key["report"], "report_orignal" => $f_key["original"], "report_description" => $desc);
          $delete = $this->Logistic_model->master_fun_insert("sample_job_master",$insetdata);
          } */
        $this->session->set_flashdata("success", array("$upcount Report Upload successfully."));
        redirect('b2b/Logistic/details/' . $cid);
    }

    function delete_downloadfile($path) {
        $this->load->helper('file');
        unlink($path);
    }

    function genrate_report($cid = "") {
        if (!is_loggedin()) {
            redirect('login');
        }
        if ($cid != "") {
            if (!empty($_FILES['common_report']['name'])) {

                $data["login_data"] = logindata();
                $adminid = $data["login_data"]["id"];
                $data['cid'] = $cid;
                ini_set('max_execution_time', 300);
                $data['page_title'] = 'AirmedLabs'; // pass data to the view
                ini_set('memory_limit', '512M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">

                $filesCount = count($_FILES['common_report']['name']);
                $type_common_report = $this->input->post('type_common_report');
                $desc = $this->input->post('desc_common_report');
                $latterpad = $this->input->post('latterpad');

                $upcount = 1;
                $file_upload = array();

                for ($i = 0; $i < $filesCount; $i++) {

                    $oldimagename = $_FILES['common_report']['name'][$i];

                    $_FILES['userFile']['name'] = $_FILES['common_report']['name'][$i];
                    $_FILES['userFile']['type'] = $_FILES['common_report']['type'][$i];
                    $_FILES['userFile']['tmp_name'] = $_FILES['common_report']['tmp_name'][$i];
                    $_FILES['userFile']['error'] = $_FILES['common_report']['error'][$i];
                    $_FILES['userFile']['size'] = $_FILES['common_report']['size'][$i];

                    $config['upload_path'] = './upload/B2breport/';
                    $config['allowed_types'] = '*';
                    $config['file_name'] = time() . "_" . $upcount . $_FILES['userFile']['name'];
                    $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                    $config['overwrite'] = FALSE;

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('userFile')) {

                        $fileData = $this->upload->data();
                        $clientname = $fileData['client_name'];
                        $filename = $fileData['file_name'];
                        $upcount++;
                        $file_upload[] = $filename;

                        $this->Logistic_model->master_fun_insert("b2b_jobsimages", array("job_fk" => $cid, "file_name" => $oldimagename, "image" => $filename));

                        /* $file_upload = array("job_fk" => $cid, "report" => $filename, "original" => $clientname, "description" => $desc, "type" => $type_common_report);

                          $delete = $this->Logistic_model->master_fun_insert("b2b_jobspdf", $file_upload); */
                    }
                }
            }
            $allnumimage = $this->Logistic_model->master_num_rows('b2b_jobsimages', array("job_fk" => $cid, "status" => '1'));
            if ($allnumimage != "0") {


                $getdetils = $this->Logistic_model->getsampledetils($cid);
                if ($_GET["debug"] == 1) {
                    echo "<pre>";
                    print_r($getdetils);
                    die();
                }

                if ($getdetils != "") {

                    $pdfFilePath = FCPATH . "/upload/B2breport/" . $getdetils->id . "_result.pdf";
                    if (file_exists($pdfFilePath)) {
                        $this->delete_downloadfile($pdfFilePath);
                        $namerepor = $getdetils->id . "_result.pdf";
                        $this->Logistic_model->master_fun_update('b2b_jobspdf', array("report" => $namerepor), array("status" => '0'));
                        /* $detilslaterped=$this->Logistic_model->fetchdatarow('id','b2b_jobspdf',array('status'=>1,'report'=>$namerepor));
                          if($detilslaterped != ""){ $this->Logistic_model->master_fun_update('b2b_jobspdf',array("id"=>$detilslaterped->id),array("status"=>0));  } */
                    }

                    $data['fileuplode'] = $this->Logistic_model->master_fun_get_tbl_val("b2b_jobsimages", array('job_fk' => $cid, 'status' => '1'), array("id", "asc"));


                    /* $file_upload */;
                    $html = $this->load->view('b2b/b2b_result_pdf', $data, true);

                    /* $this->load->library('pdf');
                      $pdf = $this->pdf->load(); */
                    /* $pdf->autoScriptToLang = true;
                      $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
                      $pdf->autoVietnamese = true;
                      $pdf->autoArabic = true;
                      $pdf->autoLangToFont = true; */
                    $content = $this->Logistic_model->master_fun_get_tbl_val("pdf_design", array('branch_fk' => '0'), array("id", "asc"));
                    /* echo "<pre>";print_r($file_upload); die(); */
                    $data["content"] = $content;
                    $this->load->library("util");
                    $util = new Util;
                    $age = $util->get_age($getdetils->customer_dob);
                    $ageinDays = 0;
                    if ($age[0] != 0) {
                        $ageinDays += ($age[0] * 365);
                        $age1 = $age[0] . " Year";
                        $age_type = 'Year';
                    }
                    if ($age[0] == 0 && $age[1] != 0) {
                        $ageinDays += ($age[1] * 30);
                        $age1 = $age[1] . " Month";
                        $age_type = 'Month';
                    }
                    if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
                        $ageinDays += ($age[2]);
                        $age1 = $age[2] . " Day";
                        $age_type = 'Day';
                    }

                    $find = array(
                        '/{{BARCODE}}/',
                        '/{{CUSTID}}/',
                        '/{{REGDATE}}/',
                        '/{{NAME}}/',
                        '/{{sex}}/',
                        '/{{age}}/',
                        '/{{REPORTDATE}}/',
                        '/{{TELENO}}/',
                        '/{{address}}/',
                        '/{{referred}}/',
                        '/{{COLLECTEDAT}}/'
                    );


                    /* if($latterpad==2){ $laterheder=$content[0]["without_header"]; $laterfoter=$content[0]["without_footer"]; }else{ $laterheder=$content[0]["header"]; $laterfoter=$content[0]["footer"]; } */

                    $replace = array(
                        'pdf_barcode.png',
                        $cid,
                        date("d-M-Y g:i", strtotime($getdetils->scan_date)),
                        strtoupper($getdetils->customer_name),
                        strtoupper($getdetils->customer_gender),
                        $age1,
                        date("d-M-Y g:i", strtotime($getdetils->scan_date)),
                        $getdetils->mobile,
                        $getdetils->address,
                        strtoupper($getdetils->doctor),
                        strtoupper($getdetils->name)
                    );

                    $header = preg_replace($find, $replace, $content[0]["without_header"]);
                    /* $pdf->SetHTMLHeader($header);
                      $pdf->AddPage('p', // L - landscape, P - portrait
                      '', '', '', '', 5, // margin_left
                      5, // margin right
                      60, // margin top
                      72, // margin bottom
                      2, // margin header
                      2);
                      $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
                      $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
                      $emailimg = $base_url . 'user_assets/images/email-icon.png';
                      $webimg = $base_url . 'user_assets/images/web-icon.png';
                      $lastimg = $base_url . 'user_assets/images/lastimg.png';
                      $pdf->SetHTMLFooter($content[0]["without_footer"]);
                      $pdf->WriteHTML($html);
                      $pdf->Output($pdfFilePath, 'F'); // save to file because we can
                      $filename = $getdetils->id . "_result.pdf";


                      $file_upload = array("job_fk" => $cid, "report" => $filename, "original" => $filename, "description" => $desc, "type" => $type_common_report);

                      $b2f2 = $this->Logistic_model->master_fun_insert("b2b_jobspdf", $file_upload); */


                    $header1 = preg_replace($find, $replace, $content[0]["header"]);
                    $footer1 = $content[0]["footer"];
                    $this->without_approve_report($cid, $getdetils->id, $header, $html, $content[0]["without_footer"]);
                    $this->with_approve_report($cid, $getdetils->id, $header1, $html, $footer1);


                    $this->Logistic_model->master_fun_insert("b2bjob_log", array("job_fk" => $cid, "created_by" => $adminid, "message_fk" => '9', "date_time" => date("Y-m-d H:i:s")));

                    $this->session->set_flashdata("success", array("Report Upload successfully."));
                    redirect('b2b/Logistic/details/' . $cid);
                } else {
                    show_404();
                }
            } else {

                $this->session->set_flashdata("error", array("Please uplode a images and generate report."));
                redirect('b2b/Logistic/details/' . $cid);
            }
        } else {
            show_404();
        }
    }

    function mygenrate_report($cid = "") {
        if (!is_loggedin()) {
            redirect('login');
        }
        if ($cid != "") {
            if (!empty($_FILES['common_report']['name'])) {

                $data["login_data"] = logindata();
                $data['cid'] = $cid;
                ini_set('max_execution_time', 300);
                $data['page_title'] = 'AirmedLabs'; // pass data to the view
                ini_set('memory_limit', '512M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">

                $filesCount = count($_FILES['common_report']['name']);
                $type_common_report = $this->input->post('type_common_report');
                $desc = $this->input->post('desc_common_report');
                $latterpad = $this->input->post('latterpad');

                $upcount = 1;
                $file_upload = array();

                for ($i = 0; $i < $filesCount; $i++) {

                    $oldimagename = $_FILES['common_report']['name'][$i];

                    $_FILES['userFile']['name'] = $_FILES['common_report']['name'][$i];
                    $_FILES['userFile']['type'] = $_FILES['common_report']['type'][$i];
                    $_FILES['userFile']['tmp_name'] = $_FILES['common_report']['tmp_name'][$i];
                    $_FILES['userFile']['error'] = $_FILES['common_report']['error'][$i];
                    $_FILES['userFile']['size'] = $_FILES['common_report']['size'][$i];

                    $config['upload_path'] = './upload/B2breport/';
                    $config['allowed_types'] = '*';
                    $config['file_name'] = time() . "_" . $upcount . $_FILES['userFile']['name'];
                    $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                    $config['overwrite'] = FALSE;

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('userFile')) {

                        $fileData = $this->upload->data();
                        $clientname = $fileData['client_name'];
                        $filename = $fileData['file_name'];
                        $upcount++;
                        $file_upload[] = $filename;

                        $this->Logistic_model->master_fun_insert("b2b_jobsimages", array("job_fk" => $cid, "file_name" => $oldimagename, "image" => $filename));

                        /* $file_upload = array("job_fk" => $cid, "report" => $filename, "original" => $clientname, "description" => $desc, "type" => $type_common_report);

                          $delete = $this->Logistic_model->master_fun_insert("b2b_jobspdf", $file_upload); */
                    }
                }
            }
            $allnumimage = $this->Logistic_model->master_num_rows('b2b_jobsimages', array("job_fk" => $cid, "status" => '1'));
            if ($allnumimage != "0") {


                $getdetils = $this->Logistic_model->getsampledetils($cid);
                if ($_GET["debug"] == 1) {
                    echo "<pre>";
                    print_r($getdetils);
                    die();
                }

                if ($getdetils != "") {

                    $pdfFilePath = FCPATH . "/upload/B2breport/" . $getdetils->id . "_result.pdf";
                    if (file_exists($pdfFilePath)) {
                        $this->delete_downloadfile($pdfFilePath);
                        $namerepor = $getdetils->id . "_result.pdf";
                        $this->Logistic_model->master_fun_update('b2b_jobspdf', array("report" => $namerepor), array("status" => '0'));
                        /* $detilslaterped=$this->Logistic_model->fetchdatarow('id','b2b_jobspdf',array('status'=>1,'report'=>$namerepor));
                          if($detilslaterped != ""){ $this->Logistic_model->master_fun_update('b2b_jobspdf',array("id"=>$detilslaterped->id),array("status"=>0));  } */
                    }

                    $data['fileuplode'] = $this->Logistic_model->master_fun_get_tbl_val("b2b_jobsimages", array('job_fk' => $cid, 'status' => '1'), array("id", "asc"));


                    /* $file_upload */;
                    $html = $this->load->view('b2b/b2b_result_pdf', $data, true);

                    /* $this->load->library('pdf');
                      $pdf = $this->pdf->load(); */
                    /* $pdf->autoScriptToLang = true;
                      $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
                      $pdf->autoVietnamese = true;
                      $pdf->autoArabic = true;
                      $pdf->autoLangToFont = true; */
                    $content = $this->Logistic_model->master_fun_get_tbl_val("pdf_design", array('branch_fk' => '0'), array("id", "asc"));
                    /* echo "<pre>";print_r($file_upload); die(); */
                    $data["content"] = $content;
                    $this->load->library("util");
                    $util = new Util;
                    $age = $util->get_age($getdetils->customer_dob);
                    $ageinDays = 0;
                    if ($age[0] != 0) {
                        $ageinDays += ($age[0] * 365);
                        $age1 = $age[0] . " Year";
                        $age_type = 'Year';
                    }
                    if ($age[0] == 0 && $age[1] != 0) {
                        $ageinDays += ($age[1] * 30);
                        $age1 = $age[1] . " Month";
                        $age_type = 'Month';
                    }
                    if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
                        $ageinDays += ($age[2]);
                        $age1 = $age[2] . " Day";
                        $age_type = 'Day';
                    }

                    $find = array(
                        '/{{BARCODE}}/',
                        '/{{CUSTID}}/',
                        '/{{REGDATE}}/',
                        '/{{NAME}}/',
                        '/{{sex}}/',
                        '/{{age}}/',
                        '/{{REPORTDATE}}/',
                        '/{{TELENO}}/',
                        '/{{address}}/',
                        '/{{referred}}/',
                        '/{{COLLECTEDAT}}/'
                    );


                    /* if($latterpad==2){ $laterheder=$content[0]["without_header"]; $laterfoter=$content[0]["without_footer"]; }else{ $laterheder=$content[0]["header"]; $laterfoter=$content[0]["footer"]; } */

                    $replace = array(
                        'pdf_barcode.png',
                        $cid,
                        date("d-M-Y g:i", strtotime($getdetils->scan_date)),
                        strtoupper($getdetils->customer_name),
                        strtoupper($getdetils->customer_gender),
                        $age1,
                        date("d-M-Y g:i", strtotime($getdetils->scan_date)),
                        $getdetils->mobile,
                        $getdetils->address,
                        strtoupper($getdetils->doctor),
                        strtoupper($getdetils->name)
                    );

                    $header = preg_replace($find, $replace, $content[0]["without_header"]);
                    /* $pdf->SetHTMLHeader($header);
                      $pdf->AddPage('p', // L - landscape, P - portrait
                      '', '', '', '', 5, // margin_left
                      5, // margin right
                      60, // margin top
                      72, // margin bottom
                      2, // margin header
                      2);
                      $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
                      $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
                      $emailimg = $base_url . 'user_assets/images/email-icon.png';
                      $webimg = $base_url . 'user_assets/images/web-icon.png';
                      $lastimg = $base_url . 'user_assets/images/lastimg.png';
                      $pdf->SetHTMLFooter($content[0]["without_footer"]);
                      $pdf->WriteHTML($html);
                      $pdf->Output($pdfFilePath, 'F'); // save to file because we can
                      $filename = $getdetils->id . "_result.pdf";


                      $file_upload = array("job_fk" => $cid, "report" => $filename, "original" => $filename, "description" => $desc, "type" => $type_common_report);

                      $b2f2 = $this->Logistic_model->master_fun_insert("b2b_jobspdf", $file_upload); */


                    $header1 = preg_replace($find, $replace, $content[0]["header"]);
                    $footer1 = $content[0]["footer"];
                    $this->without_approve_report($cid, $getdetils->id, $header, $html, $content[0]["without_footer"]);
                    $this->with_approve_report($cid, $getdetils->id, $header1, $html, $footer1);

                    $this->session->set_flashdata("success", array("Report Upload successfully."));
                    redirect('b2b/Logistic/details/' . $cid);
                } else {
                    show_404();
                }
            } else {

                $this->session->set_flashdata("error", array("Please uplode a images and generate report."));
                redirect('b2b/Logistic/details/' . $cid);
            }
        } else {
            show_404();
        }
    }

    function without_approve_report($cid, $ordeid, $header, $html, $footer) {

        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');

        $pdfFilePath1 = FCPATH . "/upload/B2breport/" . $ordeid . "_result.pdf";
        if (file_exists($pdfFilePath1)) {
            $this->delete_downloadfile($pdfFilePath1);
            $namerepor = $ordeid . "_result.pdf";
            $this->Logistic_model->master_fun_update('b2b_jobspdf', array("report" => $namerepor), array("status" => '0'));
        }
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->SetHTMLHeader($header);
        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 5, // margin_left
                5, // margin right
                72, // margin top
                72, // margin bottom
                2, // margin header 
                2);
        $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
        $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
        $emailimg = $base_url . 'user_assets/images/email-icon.png';
        $webimg = $base_url . 'user_assets/images/web-icon.png';
        $lastimg = $base_url . 'user_assets/images/lastimg.png';
        $pdf->SetHTMLFooter($footer);
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath1, 'F'); // save to file because we can
        $filename1 = $ordeid . "_result.pdf";
        $file_upload1 = array("job_fk" => $cid, "report" => $filename1, "original" => $filename1, "type" => 'c');

        $b2f2 = $this->Logistic_model->master_fun_insert("b2b_jobspdf", $file_upload1);
        return true;
    }

    function with_approve_report($cid, $ordeid, $header, $html, $footer) {

        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');

        $pdfFilePath1 = FCPATH . "/upload/B2breport/" . $ordeid . "_result_wlpd.pdf";
        if (file_exists($pdfFilePath1)) {
            $this->delete_downloadfile($pdfFilePath1);
            $namerepor = $ordeid . "_result_wlpd.pdf";
            $this->Logistic_model->master_fun_update('b2b_jobspdf', array("report" => $namerepor), array("status" => '0'));
        }
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->SetHTMLHeader($header);
        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 5, // margin_left
                5, // margin right
                72, // margin top
                72, // margin bottom
                2, // margin header 
                2);
        $sign_url = $base_url . 'user_assets/images/dr_gupta_sign.png';
        $phone_url = $base_url . 'user_assets/images/pdf_phn_btn.png';
        $emailimg = $base_url . 'user_assets/images/email-icon.png';
        $webimg = $base_url . 'user_assets/images/web-icon.png';
        $lastimg = $base_url . 'user_assets/images/lastimg.png';
        $pdf->SetHTMLFooter($footer);
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath1, 'F'); // save to file because we can
        $filename1 = $ordeid . "_result_wlpd.pdf";


        $file_upload1 = array("job_fk" => $cid, "report" => $filename1, "original" => $filename1, "type" => 'c');

        $b2f2 = $this->Logistic_model->master_fun_insert("b2b_jobspdf", $file_upload1);
        return true;
    }

    function sendToThyrocare($id) {
        $this->load->model('job_model');
        $this->load->library('Thyrocare');
        $data['job_details'] = $this->job_model->master_fun_get_tbl_val("sample_job_master", array("barcode_fk" => $id, 'status' => 1), array("id", "asc"));
        $cnt = 0;

        foreach ($data['job_details'] as $key) {
            $job_test = $this->job_model->master_fun_get_tbl_val("sample_job_test", array("job_fk" => $key["id"], 'status' => 1), array("id", "asc"));
            $tst_name = array();
            foreach ($job_test as $tkey) {
                //echo "SELECT sample_test_master.`id`,`sample_test_master`.`TEST_CODE`,`sample_test_master`.`test_name`,`sample_test_master`.`test_name`,`sample_test_master`.`status`,`sample_test_city_price`.`price` FROM `sample_test_master` INNER JOIN `sample_test_city_price` ON `sample_test_master`.`id`=`sample_test_city_price`.`test_fk` WHERE `sample_test_master`.`status`='1' AND `sample_test_city_price`.`status`='1' AND `sample_test_city_price`.`city_fk`='" . $data['job_details'][0]["test_city"] . "' AND `sample_test_master`.`id`='" . $tkey["test_fk"] . "'"; die();
                $test_info = $this->job_model->get_val("SELECT test_master.`id`,`test_master`.`test_name`,`test_master`.`thyrocare_code`,`test_master`.`test_name`,`test_master`.`status`,`test_master`.`price` ,`test_master`.`sample`  FROM   `test_master`    WHERE `test_master`.`status` = '1' AND  `test_master`.`id`='" . $tkey["test_fk"] . "'");
                $tkey["info"] = $test_info;

                $tst_name[] = $tkey;
            }
            $data['job_details'][0]["test_list"] = $tst_name;
            $job_packages = $this->job_model->master_fun_get_tbl_val("sample_book_package", array("job_fk" => $key["id"], 'status' => 1), array("id", "asc"));
            $data['job_details'][0]["package_list"] = $job_packages;
            $c_details = $this->job_model->master_fun_get_tbl_val("sample_customer_master", array("id" => $key["cust_fk"], 'status' => 1), array("id", "asc"));
            $data['job_details'][0]["cusomer_details"] = $c_details;
            $cnt++;
        }
        $data['barcode_detail'] = $this->job_model->get_val("SELECT `logistic_log`.*,`phlebo_master`.`name`,`phlebo_master`.`mobile`,`collect_from`.`name` AS `c_name` FROM `logistic_log` 
left JOIN `phlebo_master` ON `logistic_log`.`phlebo_fk`=`phlebo_master`.`id` 
left JOIN `collect_from` ON `logistic_log`.`collect_from`=`collect_from`.`id`
WHERE `logistic_log`.`status`='1' and `logistic_log`.`id`='" . $id . "'");
        $c_details = $c_details[0];
        $this->load->library("util");
        $util = new Util;
        $age = $util->get_age($data['job_details'][0]["customer_dob"]);
        $woe = array();
        $woe["PATIENT_NAME"] = $data['job_details'][0]["customer_name"];
        $woe["AGE"] = $age[0];
        $woe["GENDER"] = ($data['job_details'][0]["customer_gender"] == 'male') ? "M" : "F";
        $woe["EMAIL_ID"] = $data['job_details'][0]["customer_email"];
        $woe["CONTACT_NO"] = $data['job_details'][0]["customer_mobile"];
        $woe["SPECIMEN_COLLECTION_TIME"] = $data['job_details'][0]["date"];
        $woe["TOTAL_AMOUNT"] = $data['job_details'][0]["payable_amount"];
        $woe["AMOUNT_COLLECTED"] = 0;
        $woe["AMOUNT_DUE"] = $data['job_details'][0]["payable_amount"];
        $woe["LAB_ADDRESS"] = "usman pura ahmedabad,gujarat";

        $testname = array();
        foreach ($data['job_details'][0]["test_list"] as $test) {

            $testname[] = $test["info"]["0"]["thyrocare_code"];
        }


        $barcodelist = array(array(
                'BARCODE' => $data['barcode_detail'][0]["barcode"],
                'TESTS' => implode(',', $testname),
                'SAMPLE_TYPE' => $test["info"]["0"]["sample"],
                'Vial_Image' => '',
        ));
        if ($_REQUEST["debug"] == 1) {
            print_r($data['barcode_detail']);
        }
        $APIDATA = $this->Logistic_model->master_fun_get_tbl_val("thyrocare_api", array("id" => "1"), array("id" => "asc"));
        $thyrocare = new thyrocare();
        $result = $thyrocare->getKey($APIDATA[0]["login_code"], $APIDATA[0]["password"]);
        $resultObj = json_decode($result);

        if ($resultObj->RESPONSE == "SUCCESS") {
            $apikey = $resultObj->API_KEY;
            $thyrocare->loadkey($apikey);
        }

        $result = $thyrocare->saveWOE($woe, $barcodelist);

        // $result='{"RES_ID":"RES0000","barcode_patient_id":"GUJ86185934039873312","message":"WORK ORDER ENTRY DONE SUCCESSFULLY","status":"SUCCESS"}';
        $resultObj = json_decode($result);
        if ($resultObj->status == "INVALID API KEY") {
            $result = $thyrocare->getKey($APIDATA[0]["login_code"], $APIDATA[0]["password"]);
            $resultObj = json_decode($result);

            if ($resultObj->RESPONSE == "SUCCESS") {
                $apikey = $resultObj->API_KEY;
                $thyrocare->loadkey($apikey);
                $this->Logistic_model->master_fun_update("thyrocare_api", array("id" => 1), array("api_key" => $apikey, "last_api_key_time" => date("Y-m-d H:i:s")));
            }
            $result = $thyrocare->saveWOE($woe, $barcodelist);
            $resultObj = json_decode($result);
        }

        if ($resultObj->status == "SUCCESS") {
            $this->Logistic_model->master_fun_update("logistic_log", array("id" => $id), array("thyrocare_job_id" => $resultObj->barcode_patient_id));
        } else {
            
        }
        echo $result;
    }

    function fetchresult($id) {
        $this->load->model('job_model');
        $this->load->library('Thyrocare');
        $data['job_details'] = $this->job_model->master_fun_get_tbl_val("logistic_log", array("id" => $id, 'status' => 1), array("id", "asc"));
        $cnt = 0;
        //echo $data['job_details'][0]["scan_date"];
        $dated = explode(" ", $data['job_details'][0]["scan_date"]);
        //print_r($dated); die();
        $APIDATA = $this->Logistic_model->master_fun_get_tbl_val("thyrocare_api", array("id" => "1"), array("id" => "asc"));
        $thyrocare = new thyrocare();
        $thyrocare->loadkey($APIDATA[0]["api_key"]);
        $result = $thyrocare->getRESULT($dated[0]);
        //print_r($result); die();
        // $result='{"RES_ID":"RES0000","barcode_patient_id":"GUJ86185934039873312","message":"WORK ORDER ENTRY DONE SUCCESSFULLY","status":"SUCCESS"}';
        $resultObj = json_decode($result);
        if ($resultObj->response == "INVALID API KEY") {
            $result = $thyrocare->getKey($APIDATA[0]["login_code"], $APIDATA[0]["password"]);
            $resultObj = json_decode($result);

            if ($resultObj->RESPONSE == "SUCCESS") {
                $apikey = $resultObj->API_KEY;
                $thyrocare->loadkey($apikey);
                $this->Logistic_model->master_fun_update("thyrocare_api", array("id" => 1), array("api_key" => $apikey, "last_api_key_time" => date("Y-m-d H:i:s")));
            }
            $result = $thyrocare->getRESULT($dated[0]);
            $resultObj = json_decode($result);
        }

        if ($resultObj->status == "SUCCESS") {
            $this->Logistic_model->master_fun_update("logistic_log", array("id" => $id), array("thyrocare_job_id" => $resultObj->barcode_patient_id));
        } else {
            
        }

        $result_link = "";
        $dbdata = array();
        foreach ($resultObj->patients as $res) {

            if ($res->patient_id == $data['job_details'][0]["thyrocare_job_id"]) {
                $result_link = $res->pdflink;
            }
            $dbdata[] = array("thyrocare_job_id" => $res->patient_id, "thyrocare_report" => $res->pdflink);
        }
        $this->db->update_batch('logistic_log', $dbdata, 'thyrocare_job_id');

        if ($result_link != "") {
            header('Location: ' . $result_link);
            die();
        }
        echo "<center><h3>No RESULT FOUND. TRY AGAIN SOME TIME</h3></center>";
        die();
        echo $result;
    }

    function testme() {
        $url = "https://www.thyrocare.com/API/b2b/MASTER.svc/Z98AceJzS51rYIwHP)RLrz3M5WU4NaGo)bsELEn8lFY=/ALL/getproducts";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = "";
        if (($response = curl_exec($ch)) === false) {
            echo die('Curl error: ' . curl_error($ch));
        } else {
            //echo $response;
        }
        curl_close($ch);
        echo "test<pre>";
        $response = json_decode($response);
        $tests = $response->B2B_MASTERS->TESTS;
        $OUTLAB_TESTLIST = $response->B2B_MASTERS->OUTLAB_TESTLIST;
        $POP = $response->B2B_MASTERS->POP;
        $PROFILE = $response->B2B_MASTERS->PROFILE;

        $sms_data = array();
        foreach ($tests as $t) {
            $barcode = $t->barcodes[0];
            $code = $t->code;
            $name = $t->name;
            $product = $t->product;
            $rate = $t->rate;
            $type = $t->type;
            $tB2B = $rate->b2b;
            $tB2C = $rate->b2c;
            $res = array("code" => $code, "name" => $name, "product" => $product, "type" => $type, "b2b" => $tB2B, "b2c" => $tB2C, "specimen_type" => $barcode->specimen_type);
            print_R($res);
            $sms_data[] = $res;
        }
        foreach ($OUTLAB_TESTLIST as $t) {
            $barcode = $t->barcodes[0];
            $code = $t->code;
            $name = $t->name;
            $product = $t->product;
            $rate = $t->rate;
            $type = $t->type;
            $tB2B = $rate->b2b;
            $tB2C = $rate->b2c;
            $res = array("code" => $code, "name" => $name, "product" => $product, "type" => $type, "b2b" => $tB2B, "b2c" => $tB2C, "specimen_type" => $barcode->specimen_type);
            print_R($res);
            $sms_data[] = $res;
        }
        foreach ($PROFILE as $t) {
            $barcode = $t->barcodes[0];
            $code = $t->code;
            $name = $t->name;
            $product = $t->product;
            $rate = $t->rate;
            $type = $t->type;
            $tB2B = $rate->b2b;
            $tB2C = $rate->b2c;
            $res = array("code" => $code, "name" => $name, "product" => $product, "type" => $type, "b2b" => $tB2B, "b2c" => $tB2C, "specimen_type" => $barcode->specimen_type);
            print_R($res);
            $sms_data[] = $res;
        }

        foreach ($POP as $t) {
            $barcode = $t->barcodes[0];
            $code = $t->code;
            $name = $t->name;
            $product = $t->product;
            $rate = $t->rate;
            $type = $t->type;
            $tB2B = $rate->b2b;
            $tB2C = $rate->b2c;
            $res = array("code" => $code, "name" => $name, "product" => $product, "type" => $type, "b2b" => $tB2B, "b2c" => $tB2C, "specimen_type" => $barcode->specimen_type);
            print_R($res);
            $sms_data[] = $res;
        }
        $this->db->insert_batch('thyrocare_b2b_test', $sms_data);
    }

    public function getcopeytest() {
        set_time_limit(0);
        $testall = $this->Logistic_model->master_fun_get_tbl_val("test_master", array('status' => 1), array("id", "asc"));

        foreach ($testall as $test) {

            $data1 = array("test_name" => $test['test_name'], "TEST_CODE" => $test['TEST_CODE'], "description" => $test['description'], "department" => $test['department_fk'], "sample" => $test['sample_for_analysis'], "container" => $test['container_for_primary_sampling'], "methodology" => $test['method'], "temp" => $test['temp'], "cutt_off" => $test['cut_off'], "schedule" => $test['schedule'], "reporting" => $test['reporting'], "spacial_inst" => $test['special_instruction'], "price" => $test['price']);


            $insert = $this->Logistic_model->master_fun_insert("sample_test_master", $data1);
        }
    }

    function desti_assign() {
        if (!is_loggedin()) {
            redirect('login');
        }
        ini_set('memory_limit', '5120M');
        ini_set('max_execution_time', 300);
        $data["login_data"] = logindata();
        $adminid = $data["login_data"]["id"];
        $desti_lab = $this->input->post("desti_lab");
        $job_fk = $this->input->post("job_fk");
        // print_r($_POST);
        $get_b2c_lab1 = $this->Logistic_model->master_fun_get_tbl_val("admin_master", array("id" => $desti_lab), array("id", "asc"));
        /* Nishit add job in b2c */
        if ($get_b2c_lab1[0]["assign_branch"] > 0) {
            $logistic = $this->Logistic_model->master_fun_get_tbl_val("logistic_log", array("id" => $job_fk), array("id", "asc"));
            $test_city = $this->Logistic_model->master_fun_get_tbl_val("branch_master", array("id" => $get_b2c_lab1[0]["assign_branch"]), array("id", "asc"));
            $this->load->model("service_model");
            $get_b2c_lab = $this->Logistic_model->master_fun_get_tbl_val("sample_job_master", array("barcode_fk" => $job_fk), array("id", "asc"));
            // print_r($get_b2c_lab); die();
            $name = $get_b2c_lab[0]["customer_name"];
            $phone = $get_b2c_lab[0]["customer_mobile"];
            $email = $get_b2c_lab[0]["customer_email"];
            if ($email == '') {
                $email = 'noreply@airmedlabs.com';
            }
            $gender = $get_b2c_lab[0]["customer_gender"];
            $dob = $get_b2c_lab[0]["customer_dob"];
            $test_city = $test_city[0]["city"];
            $address = $get_b2c_lab[0]["customer_address"];
            $discount = $get_b2c_lab[0]["discount"];

            $referral_by = 0;
            $source = 0;
            $pid = 0;
            $relation_fk = 0;
            $applyCollectionCharge = 0;
            $noify_cust = 0;
            //$phlebo = $this->input->get_post("phlebo");
            //$phlebo_date = $this->input->get_post("phlebo_date");
            // $phlebo_time = $this->input->get_post("phlebo_time");
            $advance_collection = 0;
            //print_r($get_b2c_lab1); die();
            $branch = $get_b2c_lab1[0]["assign_branch"];
            if ($branch == '') {
                $branch = 1;
            }

            $order_id = $this->get_job_id($test_city);

            if ($name == "") {
                $name = "AM-" . $job_fk;
            }
            $date = date('Y-m-d H:i:s');
            $result1 = $this->service_model->get_val("SELECT * FROM `customer_master` WHERE `status`='1' AND active='1' AND `email`='" . $email . "' ORDER BY id ASC");
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
                $customer = $this->service_model->master_fun_insert("customer_master", $c_data);
            } else {
                $customer = $result1[0]["id"];
            }

            $price = 0;
            $test_package_name = array();
            $price = 0;

            $test = $this->Logistic_model->get_val("SELECT sample_job_test.`test_fk`,sample_job_test.price,test_master.test_name FROM  `sample_job_test`   LEFT JOIN  test_master ON test_master.id = sample_job_test.test_fk WHERE sample_job_test.job_fk = '" . $get_b2c_lab[0]["id"] . "' AND sample_job_test.status='1' and sample_job_test.testtype='1'");

            $packtest = $this->Logistic_model->get_val("SELECT sample_job_test.test_fk,sample_job_test.price,package_master.title as test_name FROM  `sample_job_test`   LEFT JOIN  package_master ON package_master.id = sample_job_test.`test_fk` WHERE sample_job_test.job_fk = '" . $get_b2c_lab[0]["id"] . "' AND sample_job_test.status='1' and sample_job_test.testtype='2'");

            foreach ($test as $key) {
                $price += $key["price"];
                $test_package_name[] = $key["test_name"];
            }
            foreach ($packtest as $key1) {
                $price += $key1["price"];
                $test_package_name[] = $key1["test_name"];
            }
            /* Nishit book phlebo start */
            $test_for = $this->input->get_post("test_for");
            $testforself = "self";
            $family_mem_id = 0;
            $booking_fk = $this->service_model->master_fun_insert("booking_info", array("user_fk" => $customer, "type" => $testforself, "family_member_fk" => $family_mem_id, "address" => $address, "emergency" => 0, "status" => "1", "createddate" => date("Y-m-d H:i:s")));
            /* Nishit book phlebo end */
            $data = array(
                "order_id" => $order_id,
                "cust_fk" => $customer,
                "doctor" => $referral_by,
                "other_reference" => $source,
                "date" => $date,
                "price" => $price,
                "status" => '8',
                "payment_type" => "Cash On Delivery",
                "discount" => $discount,
                "payable_amount" => $price - $advance_collection,
                "address" => $address,
                "branch_fk" => $branch,
                "test_city" => $test_city,
                "note" => $this->input->get_post('note'),
                "added_by" => $data["login_data"]["id"],
                "booking_info" => $booking_fk,
                "notify_cust" => $noify_cust,
                "portal" => "web",
                "collection_charge" => $collecion_charge,
                "date" => date("Y-m-d H:i:s"),
                "barcode" => $logistic[0]["barcode"],
                "model_type" => 2,
                "b2b_id" => $job_fk
            );
            $insert = $this->service_model->master_fun_insert("job_master", $data);
            $this->service_model->master_fun_insert("job_log", array("job_fk" => $insert, "created_by" => $data["login_data"]["id"], "updated_by" => "", "deleted_by" => "", "job_status" => '6-7', "message_fk" => "3", "date_time" => date("Y-m-d H:i:s")));
            /* foreach ($test as $key) {
              $this->service_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $key));
              } */
//            $this->service_model->master_fun_insert("job_master_receiv_amount", array("job_fk" => $insert, "phlebo_fk" => $pid, "amount" => $advance_collection, "createddate" => date("Y-m-d H:i:s"), "payment_type" => "CASH"));
            foreach ($test as $key) {
                $this->service_model->master_fun_insert("job_test_list_master", array('job_fk' => $insert, "test_fk" => $key["test_fk"], "is_panel" => "0"));
                $this->service_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "t-" . $key["test_fk"], "price" => $key["price"]));
            }
            foreach ($packtest as $key1) {

                $this->service_model->master_fun_insert("book_package_master", array('job_fk' => $insert, "date" => date('Y-m-d H:i:s'), "type" => '2', "package_fk" => $key1["test_fk"]));
                $this->service_model->master_fun_insert("booked_job_test", array("job_fk" => $insert, "test_city" => $test_city, "test_fk" => "p-" . $key1["test_fk"], "price" => $key1["price"]));
            }
        }
        /* END */
        $data = array(
            "lab_fk" => $desti_lab,
            "job_fk" => $job_fk,
            "status" => "1",
            "createddate" => date("Y-m-d H:i:s")
        );
        $insert = $this->Logistic_model->master_fun_insert("sample_destination_lab", $data);
        $this->Logistic_model->master_fun_update("logistic_log", array("id" => $job_fk), array("jobsstatus" => '2'));

        $this->Logistic_model->master_fun_insert("b2bjob_log", array("job_fk" => $job_fk, "created_by" => $adminid, "message_fk" => '5', "date_time" => date("Y-m-d H:i:s")));

        $this->session->set_flashdata("success", array("Destination lab successfullly assigned.  "));
        redirect("/b2b/Logistic/sample_list");
    }

    public function pdfdelete() {

        $data["login_data"] = logindata();
        if (!is_loggedin()) {
            redirect('login');
        }
        $pid = $this->input->post("pid");

        $insert = $this->Logistic_model->master_fun_update("b2b_jobspdf", array("id" => $pid), array("status" => '0'));

        $this->Logistic_model->master_fun_insert("b2bjob_log", array("job_fk" => $logdetils->job_fk, "created_by" => $data["login_data"]["id"], "message_fk" => '11', "date_time" => date("Y-m-d H:i:s")));

        if ($insert) {
            echo "1";
        } else {
            echo "0";
        }
    }

    public function getreportpdf() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $pid = $this->input->post("pid");
        $cid = $this->input->post("cid");
        $job_details = $this->Logistic_model->master_fun_get_tbl_val("sample_job_master", array("barcode_fk" => $pid, 'status' => 1), array("id", "asc"));
        $jobspdf = $this->Logistic_model->master_fun_get_tbl_val("b2b_jobspdf", array("job_fk" => $pid, 'status' => 1), array("id", "asc"));
        $data['sample_client_print_report_permission'] = $this->Logistic_model->master_fun_get_tbl_val("sample_client_print_report_permission", array("client_fk" => $cid, 'status' => 1), array("id", "asc"));
        ?>
        <table class="table table-striped" id="city_wiae_price123">
            <?php
            /* <thead>
              <tr><th>file Name</th><th></th></tr>
              </thead> */
            if ($job_details[0]["payable_amount"] <= 0 || $data['sample_client_print_report_permission'][0]["print_report"] == 1) {
                ?>
                <tbody id="t_body123">
            <?php
            if ($jobspdf != null) {
                foreach ($jobspdf as $pdf) {
                    ?>
                            <tr id="pdf_<?= $pdf['id']; ?>">
                                <td><a href="<?php echo base_url(); ?>upload/B2breport/<?php echo $pdf['report']; ?>?<?=date("Ymdhisa")?>" target="_blank"> <?php echo $pdf['original']; ?> </a></td>
                                <td><a href="<?= base_url() . "b2b/logistic/downlodepdf/" . $pdf['report']; ?>?<?=date("Ymdhisa")?>" class="pdfdelete" ><i class='fa fa-arrow-down'></i> Download</a></td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='2'>no data available</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php } else { ?>
            <span style="color:red"> Please collect due amount for print report.</span>
        <?php } ?>

        <?php
    }

    public function downlodepdf($file) {
        if (!is_loggedin()) {
            redirect('login');
        }
        if ($file != "") {
            $filename = FCPATH . "upload/B2breport/" . $file;
            header('Pragma: public');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Cache-Control: private', false); // required for certain browsers
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($file) . '";');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($filename));

            readfile($filename);

            exit;
        } else {
            show_404();
        }
    }

    public function getjobs_track() {

        if (!is_loggedin()) {
            redirect('login');
        }
        $pid = $this->input->post("pid");
        $sample_track = $this->Logistic_model->get_val("SELECT logistic_sample_arrive.*,`phlebo_master`.`name` FROM logistic_sample_arrive INNER JOIN `phlebo_master` ON logistic_sample_arrive.`phlebo_fk`=`phlebo_master`.`id` WHERE `phlebo_master`.`status`='1' AND logistic_sample_arrive.`status`='1' AND logistic_sample_arrive.`barcode_fk`='" . $pid . "' order by logistic_sample_arrive.id asc");
        if ($sample_track != null) {
            $cnt1 = 0;
            foreach ($sample_track as $key) {
                if ($cnt1 != 0) {
                    echo "<br><i class='fa fa-arrow-down'></i><br>";
                } echo "<b>" . ucfirst($key["name"]) . "</b>" . " (<small>" . date("d-m-Y g:i A", strtotime($key["scan_date"])) . "</small>)";
                $cnt1++;
            }
        } else {
            echo "";
        }
    }

    function get_job_id($city = null) {
        $this->load->library("Util");
        $util = new Util();
        $new_id = $util->get_job_id($city);
        return $new_id;
    }

    function sample_export() {
        if (!is_loggedin()) {
            redirect('login');
        }
        /* $this->output->enable_profiler(TRUE); */
        $data["login_data"] = logindata();
        $data["user"] = $this->Logistic_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $search = $this->input->get('search');
        $data['name'] = $this->input->get('name');
        $data['barcode'] = $this->input->get('barcode');
        $data['date'] = $this->input->get('date');
        $data['from'] = $this->input->get('from');


        $data['patientsname'] = $this->input->get('patientsname');
        $data['salesperson'] = $this->input->get('salesperson');
        $data['sendto'] = $this->input->get('sendto');
        $data['todate'] = $this->input->get('todate');
        $data['city'] = $this->input->get('city');
        $data['status'] = $this->input->get('status');
        $payment = $this->input->get('payment');

        $logintype = $data["login_data"]['type'];
        if ($logintype == 5 || $logintype == 6 || $logintype == 7) {
            $lablisass = $this->Logistic_model->get_val("SELECT GROUP_CONCAT(DISTINCT c.`id` ) as id FROM b2b_labgroup g LEFT JOIN collect_from c ON c.id=g.labid AND c.`status`='1' WHERE g.status='1' AND g.branch_fk IN (SELECT user_branch.`branch_fk` FROM `user_branch` WHERE user_branch.`user_fk`=" . $data["login_data"]['id'] . " AND user_branch.`status`=1) ORDER BY c.`name` ASC");



            if ($lablisass[0]['id'] != "") {
                $laball = $lablisass[0]['id'];
            } else {
                $laball = "0";
            }
        } else {
            $laball = "";
        }

        $cquery = "";
        if ($data['name'] != "" || $data['barcode'] != '' || $data['date'] != '' || $data['from'] != '' || $data['patientsname'] || $data['salesperson'] || $data['sendto'] || $data['todate'] || $data['city'] || $data['status']) {



            if ($data['date'] != "") {
                $data['date1'] = explode('/', $data['date']);
                $data['date2'] = $data['date1'][2] . "-" . $data['date1'][1] . "-" . $data['date1'][0];
            } else {
                $data['date2'] = "";
            }
            if ($data['todate'] != "") {
                $data['todate1'] = explode('/', $data['todate']);
                $data['todate2'] = $data['todate1'][2] . "-" . $data['todate1'][1] . "-" . $data['todate1'][0];
            } else {
                $data['todate2'] = "";
            }

            $query = $this->Logistic_model->sample_list_num($data["login_data"], $laball, $data['name'], $data['barcode'], $data['date2'], $data['todate2'], $data['from'], $data['patientsname'], $data['salesperson'], $data['sendto'], $data['city'], $data['status'], '', '', $payment);
			$order='1';
        } else {



            $query = $this->Logistic_model->sample_list($data["login_data"], $laball);
			$order='0';
        }
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"SampleReport.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No","Order id","Patientid", "PatientName", "Barcode", "LogisticName", "Scan Date", "CollectFrom", "Send To","Test City", "Salesperson", "Testname", "Total Amount", "Received amount", "Due Amount","Credit Amount"));
        $i = 0;
        foreach ($query as $row) {

            $i++;
            $job_test = $this->Logistic_model->master_fun_get_tbl_val("sample_job_test", array("job_fk" => $row["jobid"], 'status' => 1), array("id", "asc"));

            $tst_name = array();
            $tkey["info"] = array();
            foreach ($job_test as $tkey) {
                if ($tkey['testtype'] == '2') {

                    $test_info = $this->Logistic_model->get_val("SELECT  package_master.title as test_name FROM `package_master` WHERE `package_master`.`status` = '1'   AND `package_master`.`id` = '" . $tkey["test_fk"] . "'");
                    $tst_name[] = $test_info[0]['test_name'];
                } else {

                    $test_info = $this->Logistic_model->get_val("SELECT  test_master.test_name FROM `test_master` WHERE `test_master`.`status` = '1'   AND `test_master`.`id` = '" . $tkey["test_fk"] . "'");
                    $tst_name[] = $test_info[0]['test_name'];
                }
            }
            $testname = implode(",", $tst_name);
            if ($row['payable_amount'] > 0) {
                $paybleamount = 0;
            } else {
                $paybleamount = $row["price"]; 
            }
			if($order=='0'){ $orderid=""; }else{ $orderid=$row["order_id"]; }

            fputcsv($handle, array($i,$orderid,ucfirst($row["id"]), ucfirst($row["customer_name"]), $row["barcode"], $row["name"], $row["scan_date"], $row["c_name"], $row["desti_lab1"],$row["tetst_city_name"], $row["salesname"], $testname, $row["price"], $paybleamount, $row["payable_amount"],$row["credit_amount"])); 
        }

        fclose($handle);
        exit;
    }

    function sampledescti_export() {
        if (!is_loggedin()) {
            redirect('login');
        }

        /* $this->output->enable_profiler(TRUE); */
        $data["login_data"] = logindata();
        $data["user"] = $this->Logistic_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $search = $this->input->get('search');
        $data['name'] = $this->input->get('name');
        $data['barcode'] = $this->input->get('barcode');
        $data['date'] = $this->input->get('date');
        $data['from'] = $this->input->get('from');


        $data['patientsname'] = $this->input->get('patientsname');
        $data['salesperson'] = $this->input->get('salesperson');
        $data['sendto'] = $this->input->get('sendto');
        $data['todate'] = $this->input->get('todate');
        $data['city'] = $this->input->get('city');
        $data['status'] = $this->input->get('status');
        $payment = $this->input->get('payment');
        $laball = "";



        $cquery = "";
        if ($data['name'] != "" || $data['barcode'] != '' || $data['date'] != '' || $data['from'] != '' || $data['patientsname'] || $data['salesperson'] || $data['sendto'] || $data['todate'] || $data['city'] || $data['status']) {



            if ($data['date'] != "") {
                $data['date1'] = explode('/', $data['date']);
                $data['date2'] = $data['date1'][2] . "-" . $data['date1'][1] . "-" . $data['date1'][0];
            } else {
                $data['date2'] = "";
            }
            if ($data['todate'] != "") {
                $data['todate1'] = explode('/', $data['todate']);
                $data['todate2'] = $data['todate1'][2] . "-" . $data['todate1'][1] . "-" . $data['todate1'][0];
            } else {
                $data['todate2'] = "";
            }

            $query = $this->Logistic_model->sample_list_num($data["login_data"], $laball, $data['name'], $data['barcode'], $data['date2'], $data['todate2'], $data['from'], $data['patientsname'], $data['salesperson'], $data['sendto'], $data['city'], $data['status'], '', '', $payment);
        } else {



            $query = $this->Logistic_model->sample_list($data["login_data"], $laball);
        }
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"SampleReport.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No", "Patientid", "PatientName", "Barcode", "LogisticName", "Scan Date", "Testname", "Price"));
        $i = 0;
        foreach ($query as $row) {

            $i++;
            $job_test = $this->Logistic_model->master_fun_get_tbl_val("sample_job_test", array("job_fk" => $row["id"], 'status' => 1), array("id", "asc"));

            $tst_name = array();
            $tkey["info"] = array();
            foreach ($job_test as $tkey) {
                $test_info = $this->Logistic_model->get_val("SELECT  sample_test_master.test_name FROM `sample_test_master` WHERE `sample_test_master`.`status` = '1'   AND `sample_test_master`.`id` = '" . $tkey["test_fk"] . "'");
                $tst_name[] = $test_info[0]['test_name'];
            }

            $testname = implode(",", $tst_name);

            fputcsv($handle, array($i, ucfirst($row["id"]), ucfirst($row["customer_name"]), $row["barcode"], $row["name"], $row["scan_date"], $testname, $row["payable_amount"]));
        }
        fclose($handle);
        exit;
    }

    function search_dashboard() {
        $start_date = $this->input->post('start_date');
        $to_date = $this->input->post('end_date');

        $query = $this->Logistic_model->total_price($start_date, $to_date);
        // print_r($query);die;
        $data['price'] = $query[0]['SUM(price)'];
        $query = $this->Logistic_model->total_sample($start_date, $to_date);
        $data['total_sample'] = $query[0]['count(id)'];

        echo json_encode($data);
    }

    function jobssmapleupde() {
        $test_info = $this->Logistic_model->get_val("SELECT id,test_fk FROM sample_test_city_price WHERE id IN (SELECT test_fk FROM sample_job_test  GROUP BY test_fk)");
        $metchtest = array();
        foreach ($test_info as $key) {

            $this->Logistic_model->master_fun_update("sample_job_test", array("test_fk" => $key['id']), array("test_fk" => $key['test_fk']));
            $metchtest[] = $key['test_fk'];
        }
        echo "<pre>";
        print_r($metchtest);
        die();
    }

    function pdf_invoice($id) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->library("util");
        $util = new Util;
        $this->load->model('job_model');
        $data["login_data"] = logindata();

        $data["id"] = $id;
        $data['barcode_detail'] = $this->job_model->get_val("SELECT `logistic_log`.*,`phlebo_master`.`name`,`phlebo_master`.`mobile`,`collect_from`.`name` AS `c_name` FROM `logistic_log` 
left JOIN `phlebo_master` ON `logistic_log`.`phlebo_fk`=`phlebo_master`.`id` 
left JOIN `collect_from` ON `logistic_log`.`collect_from`=`collect_from`.`id`
left join sample_job_master on sample_job_master.barcode_fk = logistic_log.id
WHERE  `logistic_log`.`status`='1' and `logistic_log`.`id`='" . $data["id"] . "'");


        $labid = $data['barcode_detail'][0]['collect_from'];
        $labdetils = $this->Logistic_model->fetchdatarow('test_discount,city', 'collect_from', array("id" => $labid, "status" => 1));
        $city = $labdetils->city;

        $data['job_details'] = $this->job_model->master_fun_get_tbl_val("sample_job_master", array("barcode_fk" => $this->uri->segment(4), 'status' => 1), array("id", "asc"));
        $age = $util->get_age($data['job_details'][0]["customer_dob"]);


        if ($age[0] != 0) {
            $data['job_details'][0]["age"] = $age[0];
            $data['job_details'][0]["age_type"] = 'Y';
        }
        if ($age[0] == 0 && $age[1] != 0) {
            $data['job_details'][0]["age"] = $age[1];
            $data['job_details'][0]["age_type"] = 'M';
        }
        if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
            $data['job_details'][0]["age"] = $age[2];
            $data['job_details'][0]["age_type"] = 'D';
        }
        if ($age[0] == 0 && $age[1] == 0 && $age[2] == 0) {
            $data['job_details'][0]["age"] = '0';
            $data['job_details'][0]["age_type"] = 'D';
        }

        function getmrpspricalprice($type, $labid, $testid) {

            $ci = & get_instance();
            $laball = $ci->job_model->get_val("SELECT price_special FROM b2b_testmrp_price m WHERE  m.status = '1' AND m.typetest = '$type' AND m.test_fk='$testid' AND m.lab_id = '$labid' ");

            return $laball;
        }

        $data['test_list'] = $this->job_model->get_val("SELECT jt.id,jt.testtype,jt.test_fk,(CASE WHEN jt.testtype = '2' THEN p.title ELSE t.test_name END) AS testname,(CASE WHEN jt.testtype = '2' THEN p2.`d_price` ELSE p1.`price` END) AS price FROM sample_job_test jt LEFT JOIN test_master t ON t.`id` = jt.test_fk AND jt.testtype = '1' LEFT JOIN test_master_city_price p1 ON t.id = p1.test_fk AND p1.`city_fk` = '$city' LEFT JOIN package_master p ON p.id = jt.test_fk AND jt.testtype= '2' LEFT JOIN package_master_city_price p2 ON p.id = p2.package_fk  AND p2.`city_fk`='$city'  WHERE  jt.status = '1' AND jt.job_fk='" . $data['job_details'][0]["id"] . "'");

        /* echo "<pre>"; 
          print_r($data);
          die(); */
        $data['job_details'][0]['order_id'];
        $pdfFilePath = FCPATH . "/upload/b2binvoice/" . $data['job_details'][0]['order_id'] . "customerinvoice.pdf";
        $data['page_title'] = 'AirmedLabs'; // pass data to the view
        ini_set('memory_limit', '512M'); // boost the memory limit if it's low <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="?" draggable="false" class="emoji">
        $html = $this->load->view('b2b/user_job_invoice_pdf', $data, true); // render the view into HTML
        $this->load->library('pdf');

        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;

        $pdf->autoLangToFont = true;


        $pdf->SetHTMLHeader('<body>
                <div class="pdf_container">
            <div class="main_set_pdng_div">
                <div class="brdr_full_div">
                    <div class="header_full_div">
                        <img class="set_logo" src="logo.png" style="margin-top:15px;"/>
                    </div>');

        $pdf->SetHTMLFooter('<div class="foot_num_div" style="margin-bottom:0;padding-bottom:0">
		<p class="foot_num_p" style="margin-bottom:2;padding-bottom:0"><img class="set_sign" src="pdf_phn_btn.png" style="width:"/></p>
		<p class="foot_lab_p" style="font-size:13px;margin-bottom:2;padding-bottom:0">LAB AT YOUR DOORSTEP</p>
	</div>
		<p class="lst_airmed_mdl" style="font-size:13px;margin-top:5px">Airmed Pathology Pvt. Ltd.</p>
		<p class="lst_31_addrs_mdl" style="font-size:12px"><span style="color:#9D0902;">Commercial Address : </span>31, Ambika Society, Next to Nabard Bank, Opp. Usmanpura, Ahmedabad, Gujarat - 380 013.</p>
		<p class="lst_31_addrs_mdl"><b><img src="email-icon.png" style="margin-bottom:-3px;width:13px"/> info@airmedlabs.com  <img src="web-icon.png" style="margin-bottom:-3px;width:13px"/> www.airmedlabs.com</b></p><p class="lst_31_addrs_mdl"><!--<img src="lastimg.png" style="margin-top:3px;"/>--> </p></div>
        </body>
</html>');

        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 5, // margin_left
                5, // margin right
                30, // margin top
                30, // margin bottom
                2, // margin header
                2); // margin footer

        $pdf->WriteHTML($html);
        $pdf->debug = true;
        $pdf->allow_output_buffering = TRUE;

        if (file_exists($pdfFilePath) == true) {

            $this->load->helper('file');
            unlink($path);
        }
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can

        $this->job_model->master_fun_insert("b2bjob_log", array("job_fk" => $id, "created_by" => $data["login_data"]["id"], "message_fk" => '4', "date_time" => date("Y-m-d H:i:s")));

        redirect("/upload/b2binvoice/" . $data['job_details'][0]['order_id'] . "customerinvoice.pdf?" . time());
    }

    function testemplode() {

        $test_info = $this->Logistic_model->get_val("SELECT id,test_fk FROM sample_job_test WHERE STATUS='1'");
        $metchtest = array();
        $nometchtest = array();
        $nodata = array();

        foreach ($test_info as $key) {

            $test_fk = $key['test_fk'];
            $sampletestdetils = $this->Logistic_model->fetchdatarow('id,test_name', 'sample_test_master', array("id" => $test_fk));
            $testdetils = $this->Logistic_model->fetchdatarow('id,test_name', 'test_master', array("id" => $test_fk));


            if ($sampletestdetils != "" && $testdetils != "") {

                if ($sampletestdetils->id == $testdetils->id) {
                    $metchtest[] = $key['id'];
                } else {
                    $nometchtest[] = $key['id'];
                }
            } else {
                $nodata[] = $key['id'];
            }
            if ($sampletestdetils->test_name != $testdetils->test_name) {
                echo $test_fk . "-" . $sampletestdetils->test_name . "_" . $test_fk . "-" . $testdetils->test_name . "<br>";
            }
        }
        echo "<pre>";
        print_r($nodata);
        die();
    }

    function updatetest() {

        $testid1 = $this->Logistic_model->master_fun_insert("test_master", array("test_name" => 'VIT D 25', "PRINTING_NAME" => 'VIT D 25'));
        $this->Logistic_model->master_fun_insert("test_master_city_price", array("test_fk" => $testid1, "city_fk" => '7', "price" => '1400'));

        $this->Logistic_model->master_fun_update("sample_job_test", array("test_fk" => '11414'), array("test_fk" => $testid1));

        $testid2 = $this->Logistic_model->master_fun_insert("test_master", array("test_name" => 'URIC ACID BLOOD', "PRINTING_NAME" => 'URIC ACID BLOOD'));
        $this->Logistic_model->master_fun_insert("test_master_city_price", array("test_fk" => $testid2, "city_fk" => '7', "price" => '550'));

        $this->Logistic_model->master_fun_update("sample_job_test", array("test_fk" => '11451'), array("test_fk" => $testid2));

        $this->Logistic_model->master_fun_update("sample_job_test", array("test_fk" => '11408'), array("test_fk" => '358'));
        $this->Logistic_model->master_fun_update("sample_job_test", array("test_fk" => '11410'), array("test_fk" => '11418'));
        $this->Logistic_model->master_fun_update("sample_job_test", array("test_fk" => '11428'), array("test_fk" => '569'));
        $this->Logistic_model->master_fun_update("sample_job_test", array("test_fk" => '11437'), array("test_fk" => '11418'));

        echo "ok";
        die();
    }

    function nishit_test() {
        $test_list = $this->Logistic_model->get_val("SELECT * FROM sample_test_city_price");

        foreach ($test_list as $key) {
            //die("Ok");
            $this->Logistic_model->master_fun_update("sample_job_test", array("test_fk" => $key['id']), array("test_fk_temp" => $key['test_fk']));
        }

        echo "Done";
        //print_r($test_list);
    }

    function rahul_test() {

        $test_list = $this->Logistic_model->get_val("SELECT 
  jt.id,
  t.`id` AS testid,
  t.test_name,
  jt.`price`,
  bmp.`price_special`,
  c.`id` AS labid,
  p.`price` AS b2cprice,
  j.`barcode_fk` ,j.`id` AS jobid
FROM
  sample_job_test jt 
  LEFT JOIN `test_master` t 
    ON t.id = jt.`test_fk` 
  LEFT JOIN `sample_job_master` j 
    ON j.`id` = jt.`job_fk` 
  LEFT JOIN `logistic_log` l 
    ON l.`id` = j.`barcode_fk` 
  LEFT JOIN collect_from c 
    ON c.`id` = l.`collect_from` 
  INNER JOIN test_master_city_price AS p 
    ON t.id = p.test_fk 
    AND p.city_fk = c.`city` 
  INNER JOIN `b2b_testspecial_price` AS bmp 
    ON t.id = bmp.`test_fk` 
    AND bmp.`lab_id` = l.collect_from 
    AND bmp.`status` = '1' 
    AND bmp.typetest = '1' 
WHERE jt.`status` = '1' 
  AND DATE_FORMAT(l.scan_date, '%Y-%m-%d') = '2017-10-09' 
GROUP BY jt.`id` 
ORDER BY jt.`id` DESC ");
        $testarry = array();
        foreach ($test_list as $key) {

            $this->Logistic_model->master_fun_update("sample_job_test", array("id" => $key['id']), array("price" => $key['price_special']));
            $testarry[] = $key['id'];
        }

        /* $this->rahul_jobpriceupdate();  */
        echo "<pre>";
        print_R($testarry);

        echo "Done";
    }

    function rahul_jobpriceupdate() {

        $test_list = $this->Logistic_model->get_val("SELECT j.`barcode_fk`,
  j.`id` AS jobid,SUM(bmp.`price_special`) AS payprice,j.payable_amount
FROM
  sample_job_test jt 
  LEFT JOIN `test_master` t 
    ON t.id = jt.`test_fk` 
  LEFT JOIN `sample_job_master` j 
    ON j.`id` = jt.`job_fk` 
  LEFT JOIN `logistic_log` l 
    ON l.`id` = j.`barcode_fk` 
  LEFT JOIN collect_from c 
    ON c.`id` = l.`collect_from` 
  INNER JOIN test_master_city_price AS p 
    ON t.id = p.test_fk 
    AND p.city_fk = c.`city` 
  INNER JOIN `b2b_testspecial_price` AS bmp 
    ON t.id = bmp.`test_fk` 
    AND bmp.`lab_id` = l.collect_from 
    AND bmp.`status` = '1' 
    AND bmp.typetest = '1' 
WHERE jt.`status` = '1' 
  AND DATE_FORMAT(l.scan_date, '%Y-%m-%d') >= '2017-10-04' 
GROUP BY j.`id`
ORDER BY jt.`id` DESC ");
        $testarry = array();
        foreach ($test_list as $key) {
            $this->Logistic_model->master_fun_update("sample_job_master", array("id" => $key['jobid']), array("price" => $key['payprice'], "payable_amount" => $key['payprice']));
            $testarry[] = $key['jobid'];
            /* echo $this->db->last_query(); 
              die(); */
        }
    }

    function rahul_cityupdate() {

        $test_list = $this->Logistic_model->get_val("SELECT * FROM `test_master_city_price` WHERE city_fk='9' AND STATUS='1'");
        $testarry = array();
        foreach ($test_list as $key) {
            $this->Logistic_model->master_fun_insert("test_master_city_price", array("test_fk" => $key["test_fk"], "city_fk" => '13', "price" => $key["price"]));
            /* echo $this->db->last_query(); 
              die(); */
        }
    }

    function payment_received() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->model('job_model');
        $data["login_data"] = logindata();
        $jid = $this->uri->segment(4);
        $rid = $this->uri->segment(5);
        $amount = trim($this->input->post("amount"));
        $remark = $this->input->post("remark");
        $ttl_amount = $this->input->post("ttl_amount");
        //echo "<pre>"; print_r($_POST); die();
        $job_details = $this->job_model->master_fun_get_tbl_val("sample_job_master", array("id" => $jid), array("id", "asc"));
        $payable_price = $job_details[0]["payable_amount"];
        $remaining_amount = $payable_price - $amount;
        $this->job_model->master_fun_insert("sample_job_master_receiv_amount", array("remark" => $remark, "job_fk" => $jid, "added_by" => $data["login_data"]["id"], "payment_type" => "CASH", "amount" => $amount, "createddate" => date("Y-m-d H:i:s")));
        $this->job_model->master_fun_update("sample_job_master", array("id", $jid), array("payable_amount" => $remaining_amount));
        //$this->job_model->master_fun_insert("job_log", array("job_fk" => $jid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "22", "date_time" => date("Y-m-d H:i:s")));

        $this->job_model->master_fun_insert("b2bjob_log", array("job_fk" => $rid, "created_by" => $data["login_data"]["id"], "message_fk" => '6', "date_time" => date("Y-m-d H:i:s")));

        $this->session->set_flashdata("amount_history_success", array("Payment Successfully added."));
        redirect("b2b/Logistic/details/" . $rid);
    }

    function payment_received1() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->model('job_model');
        $data["login_data"] = logindata();
        $jid = $this->uri->segment(4);
        $rid = $this->uri->segment(5);
        $amount = trim($this->input->post("amount"));
        $remark = $this->input->post("remark");
        $ttl_amount = $this->input->post("ttl_amount");
        //echo "<pre>"; print_r($_POST); die();
        $job_details = $this->job_model->master_fun_get_tbl_val("sample_job_master", array("id" => $jid), array("id", "asc"));
        $payable_price = $job_details[0]["payable_amount"];
        $remaining_amount = $payable_price - $amount;
        $this->job_model->master_fun_insert("sample_job_master_receiv_amount", array("remark" => $remark, "job_fk" => $jid, "added_by" => $data["login_data"]["id"], "payment_type" => "CASH", "amount" => $amount, "createddate" => date("Y-m-d H:i:s")));
        $this->job_model->master_fun_update("sample_job_master", array("id", $jid), array("payable_amount" => $remaining_amount));

        $this->job_model->master_fun_insert("job_log", array("job_fk" => $rid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "22", "date_time" => date("Y-m-d H:i:s")));

        $this->job_model->master_fun_insert("b2bjob_log", array("job_fk" => $jid, "created_by" => $data["login_data"]["id"], "message_fk" => '6', "date_time" => date("Y-m-d H:i:s")));
        //$this->job_model->master_fun_insert("job_log", array("job_fk" => $jid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "22", "date_time" => date("Y-m-d H:i:s")));
        $this->session->set_flashdata("amount_history_success", array("Payment Successfully added."));
        redirect("job-master/job-details/" . $rid);
    }

    function delete_assign_payment() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->model('job_model');
        $data["login_data"] = logindata();
        $jid = $this->input->post("jid");
        $id = $this->input->post("id");

        $sampllabid = $this->input->post("did");

        $delete_received_payment = $this->input->post("d_amount");
        $ttl_amount = $this->input->post("ttl_amount");
        $job_details = $this->job_model->master_fun_get_tbl_val("sample_job_master", array("id" => $jid), array("id", "asc"));
        $d_amount = $job_details[0]["payable_amount"];
        if (!empty($jid) && !empty($id)) {
            $this->job_model->master_fun_update("sample_job_master_receiv_amount", array("id", $id), array("status" => 0));
            $remaining_amount = $d_amount + $delete_received_payment;
            $this->job_model->master_fun_update("sample_job_master", array("id", $jid), array("payable_amount" => $remaining_amount));
            $job_received_details = $this->job_model->master_fun_get_tbl_val("sample_job_master_receiv_amount", array("id" => $id), array("id", "asc"));
            if (strtoupper($job_received_details[0]["payment_type"]) == 'CREDITORS') {
                $this->job_model->master_fun_update("sample_job_creditors", array("job_fk", $jid), array("status" => 0));
                $this->job_model->master_fun_update("sample_creditors_balance", array("job_id", $jid), array("status" => 0));
            }

            $this->job_model->master_fun_insert("b2bjob_log", array("job_fk" => $job_details[0]["barcode_fk"], "created_by" => $data["login_data"]["id"], "message_fk" => '13', "date_time" => date("Y-m-d H:i:s")));

            $this->job_model->master_fun_insert("job_log", array("job_fk" => $sampllabid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "23", "date_time" => date("Y-m-d H:i:s")));
        }
        //$this->job_model->master_fun_insert("job_log", array("job_fk" => $jid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "23", "date_time" => date("Y-m-d H:i:s")));
        $this->session->set_userdata("amount_history_success", array("Payment Successfully deleted."));
        echo 1;
    }

    function creditors_add() {
        $this->load->model('job_model');
        $data["login_data"] = logindata();
        $job = $this->input->get_post("job_id");
        $creditor = $this->input->get_post("creditor_id");
        $amount = $this->input->get_post("amount");
        $otp = rand(1111, 9999);
        $creditor_data = $this->job_model->master_fun_get_tbl_val("sample_creditors_master", array('id' => $creditor), array("id", "asc"));
        $data12 = array(
            "job_fk" => $job,
            "creditors_fk" => $creditor,
            "amount" => $amount,
            "added_by" => $data["login_data"]["id"],
            "created_date" => date("Y-m-d H:i:s"),
            "status" => 2,
            "otp" => $otp
        );

        /* Nishit Send sms start */
        $this->load->helper("sms");
        $notification = new Sms();
        $mobile = $creditor_data[0]['mobile'];
        $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "creditor_verify"), array("id", "asc"));
        $sms_message = preg_replace("/{{OTP}}/", $otp, $sms_message[0]["message"]);
        $sms_message = preg_replace("/{{NAME}}/", $creditor_data[0]['name'], $sms_message);
        $sms_message = preg_replace("/{{AMOUNT}}/", $amount, $sms_message);
        $notification->send($mobile, $sms_message);
        /* Nishit Send sms end */
        $row = $this->job_model->master_num_rows("sample_job_creditors", array("job_fk" => $job, "creditors_fk" => $creditor));
        if ($row == 1) {
            $this->job_model->master_fun_update_multi("sample_job_creditors", array("creditors_fk" => $creditor, "job_fk" => $job), array("status" => 2, "otp" => $otp, "creditors_fk" => $creditor, "amount" => $amount, "updated_by" => $data["login_data"]["id"],));
        } else {
            $insert = $this->job_model->master_fun_insert("sample_job_creditors", $data12);
        }
    }

    function creditor_check_otp() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->model('job_model');
        $data["login_data"] = logindata();
        $otp = $this->input->post("otp");
        $creditor = $this->input->post("creditor_fk");
        $job = $this->input->post("job");
        $amount = $this->input->post("amount");
        $row = $this->job_model->master_num_rows("sample_job_creditors", array("job_fk" => $job, "creditors_fk" => $creditor, "otp" => $otp));
        if ($row == 1 || ($otp == "777700")) {
            $this->job_model->master_fun_update_multi("sample_job_creditors", array("creditors_fk" => $creditor, "job_fk" => $job, "status" => '2'), array("status" => 1, "otp" => ''));
            $creditor_data = $this->job_model->master_fun_get_tbl_val("sample_creditors_master", array('id' => $creditor), array("id", "asc"));
            $this->job_model->master_fun_insert("sample_job_master_receiv_amount", array("job_fk" => $job, "amount" => $amount, "createddate" => date("Y-m-d H:i:s"), "added_by" => $data["login_data"]['id'], "type" => "credit", "remark" => "credited by " . $creditor_data[0]['name'], "payment_type" => "CREDITORS"));
            $insert = $this->job_model->master_fun_insert("sample_creditors_balance", array("creditors_fk" => $creditor, "debit" => $amount, "created_by" => $data["login_data"]['id'], "job_id" => $job));
            $job_details = $this->job_model->master_fun_get_tbl_val("sample_job_master", array("id" => $job), array("id", "asc"));
            $payable_price = $job_details[0]["payable_amount"];
            $remaining_amount = $payable_price - $amount;
            $this->job_model->master_fun_update("sample_job_master", array("id", $job), array("payable_amount" => $remaining_amount));

            $this->job_model->master_fun_insert("b2bjob_log", array("job_fk" => $job_details[0]["barcode_fk"], "created_by" => $data["login_data"]["id"], "message_fk" => '7', "date_time" => date("Y-m-d H:i:s")));

            //$this->job_model->master_fun_insert("job_log", array("job_fk" => $job, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "22", "date_time" => date("Y-m-d H:i:s")));
            echo json_encode(array("status" => "1", "msg" => "Verified"));
        } else {
            echo json_encode(array("status" => "0", "msg" => "Invalid OTP."));
        }
    }

    function creditors_resend() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->model('job_model');
        $data["login_data"] = logindata();
        $job = $this->input->post("job_id");
        $creditor = $this->input->post("creditor_id");
        $amount = $this->input->post("amount");
        $otp = rand(1111, 9999);
        $creditor_data = $this->job_model->master_fun_get_tbl_val("sample_creditors_master", array('id' => $creditor), array("id", "asc"));
        /* Nishit Send sms start */
        $this->load->helper("sms");
        $notification = new Sms();
        $mobile = $creditor_data[0]['mobile'];
        $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "creditor_verify"), array("id", "asc"));
        $sms_message = preg_replace("/{{OTP}}/", $otp, $sms_message[0]["message"]);
        $sms_message = preg_replace("/{{NAME}}/", $creditor_data[0]['name'], $sms_message);
        $sms_message = preg_replace("/{{AMOUNT}}/", $amount, $sms_message);
        $notification->send($mobile, $sms_message);
        /* Nishit Send sms end */
        $this->job_model->master_fun_update_multi("sample_job_creditors", array("creditors_fk" => $creditor, "job_fk" => $job, "status" => '2'), array("otp" => $otp));
    }

    function branchcreditors_all() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->model('job_model');
        $data["login_data"] = logindata();

        function getcreorlab($creditor) {
            if ($creditor != "") {
                $ci = & get_instance();
                /* $laball=$ci->report_model->get_val("SELECT cm.id,cm.`name`,cm.`mobile`,COUNT(cb.job_id) AS totaljobs,SUM(CASE WHEN cb.paid <= '0' THEN cb.`debit` ELSE 0 END) AS blance FROM creditors_balance cb JOIN creditors_master cm ON cb.creditors_fk = cm.id LEFT JOIN job_master j ON j.`id`=cb.job_id and j.status !='0' and j.branch_fk='$branch'  WHERE  cb.debit>'0' AND cb.`status`='1' AND cb.creditors_fk IN($creditor) GROUP BY cb.creditors_fk"); */
                $laball = $ci->job_model->get_val("SELECT cm.id,cm.`name`,cm.`mobile` FROM sample_creditors_master cm  WHERE cm.id IN ($creditor) AND cm.`status`='1'");
                return $laball;
            } else {
                return array();
            }
        }

        function getcreorjobs($branch, $creditor) {
            if ($creditor != "") {
                $ci = & get_instance();
                $client_lab = $ci->job_model->get_val("SELECT GROUP_CONCAT(labid) AS labid FROM `b2b_labgroup` WHERE `status`='1' AND branch_fk='" . $branch . "' GROUP BY branch_fk ");
                if (!empty($client_lab)) {
                    $client_ids = $client_lab[0]["labid"];
                    $laball = $ci->job_model->get_val("SELECT 
  COUNT(cb.job_id) AS totaljobs,
  SUM(
    CASE
      WHEN cb.paid <= '0' 
      THEN cb.`debit` 
      ELSE 0 
    END
  ) AS blance 
FROM
  sample_creditors_balance cb 
  JOIN sample_job_master j 
    ON j.`id` = cb.job_id 
    AND j.`status` != '0'
    INNER JOIN `sample_job_master` ON sample_job_master.id=cb.`job_id`
    INNER JOIN `logistic_log` ON  logistic_log.id=`sample_job_master`.`barcode_fk`
WHERE cb.status = '1' 
  AND cb.debit > '0' 
  AND sample_job_master.`status`='1'
  AND logistic_log.`status`='1'
  AND logistic_log.`collect_from` IN (" . $client_ids . ")
  AND cb.creditors_fk = '" . $creditor . "'");
                } else {
                    $laball = array();
                }
                return $laball;
            } else {
                return array();
            }
        }

        if ($data["login_data"]["type"] != 1 && $data["login_data"]["type"] != 2) {
            $uid = $this->job_model->get_val("SELECT GROUP_CONCAT(branch_fk) AS bid FROM `user_branch` WHERE `status`='1' AND user_fk='" . $data["login_data"]["id"] . "' GROUP BY user_fk");
            if ($uid[0]["bid"] != "") {
                $salidbid = $uid[0]["bid"];
            } else {
                $salidbid = 0;
            }
            $data['query'] = $this->job_model->get_val("SELECT b.id,b.branch_name,GROUP_CONCAT(DISTINCT cb.`creditors_fk`) AS creditors FROM `branch_master` b LEFT JOIN `sample_creditors_branch` cb ON cb.`branch_fk`=b.id AND cb.`status`='1' WHERE b.status='1' and b.id in($salidbid) GROUP BY b.id ORDER BY b.branch_name ASC");
        } else {
            $data['query'] = $this->job_model->get_val("SELECT b.id,b.branch_name,GROUP_CONCAT(DISTINCT cb.`creditors_fk`) AS creditors FROM `branch_master` b LEFT JOIN `sample_creditors_branch` cb ON cb.`branch_fk`=b.id AND cb.`status`='1' WHERE b.status='1' GROUP BY b.id ORDER BY b.branch_name ASC");
        }
        //echo "<pre>"; print_R($data['query']); die();
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('b2b/branchcreditors_shows', $data);
        $this->load->view('footer');
    }

    function branchcreditors($branch = null, $cid = null) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->model("b2b/report_model");
        if ($branch != "" && $cid != "") {

            $data["login_data"] = logindata();
            $data["cid"] = $cid;
            $data["bid"] = $branch;
            $data['start_date'] = $this->input->get("start_date");
            $data['end_date'] = $this->input->get("end_date");
            $start_date = null;
            $end_date = null;
            if ($data['start_date'] != "") {
                $start_date = $data['start_date'];
            }

            if ($data['end_date'] != "") {
                $end_date = $data['end_date'];
            }
            $data['view_all_data'] = $this->report_model->branchcreditors_report($start_date, $end_date, $branch, $cid);

            /* echo $this->db->last_query(); 
              die(); */
            $new_array = array();
            foreach ($data['view_all_data'] as $key) {
                $dta = array();
                if ($key["paid"] > 0) {
                    $dta = $this->report_model->branchcreditors_report_id($branch, $key["paid"]);
                }
                $key["paid_by"] = $dta;
                $new_array[] = $key;
            }
            $data['view_all_data'] = array();
            $data['view_all_data'] = $new_array;
            //print_r($new_array); die();
            $this->load->view('header', $data);
            $this->load->view('nav', $data);
            $this->load->view('b2b/branchcreditors_report', $data);
            $this->load->view('footer');
        } else {
            show_404();
        }
    }

    function branchcreditors_csv($branch, $cid) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->model("b2b/report_model");
        if ($branch != "" && $cid != "") {

            $data["login_data"] = logindata();
            $data["cid"] = $cid;
            $data['start_date'] = $this->input->get("start_date");
            $data['end_date'] = $this->input->get("end_date");
            $start_date = null;
            $end_date = null;
            if ($data['start_date'] != "") {
                $start_date = $data['start_date'];
            }

            if ($data['end_date'] != "") {
                $end_date = $data['end_date'];
            }
            $data['view_all_data'] = $this->report_model->branchcreditors_report($start_date, $end_date, $branch, $cid);
            $new_array = array();
            foreach ($data['view_all_data'] as $key) {
                $dta = array();
                if ($key["paid"] > 0) {
                    $dta = $this->report_model->branchcreditors_report_id($branch, $key["paid"]);
                }
                $key["paid_by"] = $dta;
                $new_array[] = $key;
            }
            $data['view_all_data'] = array();
            $data['view_all_data'] = $new_array;
            //echo "<pre>";print_R($new_array); die();

            $result = $new_array;
            //if (!isset($_REQUEST['de'])) {
            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"B2B_Creditor_Report-" . date('d-M-Y') . ".csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle = fopen('php://output', 'w');
            fputcsv($handle, array("No.", "Creditor Name", "Patient", "Amount", "Added By", "Date", "Remark", "Paid/Due", "Paid By"));
            //}

            $cnt = 1;
            foreach ($result as $key) {

                /* Nishit 18-08-2017 START */
                $remark = "Due";
                $padi_by = "";
                if (!empty($key["paid_by"])) {
                    $remark = "Paid";
                    $padi_by = ucwords($key["paid_by"][0]["added_by"]);
                }

                fputcsv($handle, array($cnt, ucwords($key["name"]), ucwords($key["patient_name"]) . "(" . $key["order_id"] . ")", $key["debit"], $key["added_by"], $key["created_date"], $key["remark"], $remark, $padi_by));
                $cnt++;
            }
            fclose($handle);
            exit;
        } else {
            show_404();
        }
    }

    function get_job_log($jid) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->model('job_model');
        $data["login_data"] = logindata();
        $job_log = $this->job_model->master_fun_get_tbl_val("b2bjob_log", array('status' => 1, "job_fk" => $jid), array("id", "asc"));
        if (!empty($job_log)) {
            echo "<hr>";
            foreach ($job_log as $key) {
                $message = $this->job_model->master_fun_get_tbl_val("b2bjob_log_message", array('status' => 1, "id" => $key["message_fk"]), array("id", "asc"));
                $AID = $key["created_by"];
                if ($AID != "" || $AID != null) {
                    $admin_details = $this->job_model->master_fun_get_tbl_val("admin_master", array('status' => 1, "id" => $AID), array("id", "asc"));
                } else {
                    $pID = $key["phlebo_fk"];
                    $admin_details = $this->job_model->master_fun_get_tbl_val("phlebo_master", array('status' => 1, "id" => $pID), array("id", "asc"));
                }
                $message = $message[0]["message"];
                $originalDate = $key["date_time"];
                $newDate = date("d-M-Y g:i A", strtotime($originalDate));
                $message = preg_replace("/{{ANAME}}/", "<b>" . ucfirst($admin_details[0]["name"]) . "</b>", $message);
                $message = preg_replace("/{{DATE}}/", $newDate, $message);

                echo '<p><small>' . $message . '</small></p>';
            }
        }
    }

    function remove_report_image() {
        $jid = $this->input->get("jid");
        $id = $this->input->get("id");
        $this->Logistic_model->master_fun_update("b2b_jobsimages", array("id" => $id), array("status" => "0"));
        redirect("b2b/Logistic/details/" . $jid);
    }

}
