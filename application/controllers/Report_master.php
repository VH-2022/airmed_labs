<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('job_model');
        $this->load->model('user_model');
        $this->load->model('report_model');

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

    function export() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $start = $this->input->get("start_date");
        $end = $this->input->get("end_date");
        $branch = $this->input->get("branch");
        $type = $this->input->get("type");
        $wise = $this->input->get("wise");
        $date1 = explode("/", $start);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $end);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "Payment_report.csv";
        $qry = "select
            bm.branch_name as `Branch Name`,";
        if ($type == "client") {
            $qry .= "am.name as `Client Name`,";
        } else if ($type == "doctor") {
            $qry .= "dm.full_name as `Doctor Name`,";
        }
        if ($wise == "day") {
            $qry .= "DATE_FORMAT(jm.date,'%d-%M-%Y') as `Registration Date`,";
        } else if ($wise == "month") {
            $qry .= "CONCAT(MONTH(jm.date),'-',YEAR(jm.date)) as `Registration Date`,";
        } else if ($wise == "year") {
            $qry .= "YEAR(jm.date) as `Registration Date`,";
        }
        $qry .= "COUNT(jm.id) as `Sample Count`,
            SUM(Round(jm.`price`)) as `Total Amount`,
            SUM(Round((if(jm.`discount` != 'NULL',jm.`discount`,0) * if(jm.price != 'NULL',jm.price,0)) / 100)) as `Discount Amount`,
            SUM(Round(if(jm.price != 'NULL',jm.price,0) - ((if(jm.`discount` != 'NULL',jm.`discount`,0) * if(jm.price != 'NULL',jm.price,0)) / 100))) as `Net Amount`,
            SUM(Round(if(jm.price != 'NULL',jm.price,0) - ((if(jm.`discount` != 'NULL',jm.`discount`,0) * if(jm.price != 'NULL',jm.price,0)) / 100) - if(jm.payable_amount != 'NULL',jm.payable_amount,0))) as `Received Amount`,
            SUM(jm.payable_amount) as `Due Amount`
                from job_master jm join branch_master bm on bm.id=jm.branch_fk";
        if ($type == "client") {
            $qry .= " join admin_master am on am.id=jm.added_by ";
        } else if ($type == "doctor") {
            $qry .= " join doctor_master dm on dm.id=jm.doctor ";
        }
        $qry .= " where jm.status != '0' and bm.status = '1' and jm.`model_type`='1'  ";
        if ($start != "" || $end != "") {
            $qry .= " AND jm.date >= '" . $start_date . "' AND jm.date <= '" . $end_date . "'";
        }
        if ($branch != "") {
            $qry .= " AND jm.branch_fk = '" . $branch . "' ";
        }
        $qry .= " group by jm.branch_fk";
        if ($type == "client") {
            $qry .= ",am.id";
        } else if ($type == "doctor") {
            $qry .= ",dm.id";
        }
        if ($wise == "day") {
            $qry .= ",YEAR(jm.date),MONTH(jm.date),DAY(jm.date)";
        } else if ($wise == "month") {
            $qry .= ",MONTH(jm.date),YEAR(jm.date)";
        } else if ($wise == "year") {
            $qry .= ",YEAR(jm.date)";
        }
        $qry .= " order by bm.`id`,jm.date ASC";
        $result = $this->db->query($qry);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }

    function index() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['start_date'] = $this->input->get("start_date");
        $data['end_date'] = $this->input->get("end_date");
        $data['branch'] = $this->input->get("branch");
        $data['type'] = $this->input->get("type");
        $data['wise'] = $this->input->get("wise");
        $start_date = null;
        $end_date = null;
        if ($data['start_date'] != "") {
            $start_date = $data['start_date'];
        }

        if ($data['end_date'] != "") {
            $end_date = $data['end_date'];
        }
        if ($data["login_data"]['type'] == 6 || $data["login_data"]['type'] == 5) {
            $user_branch = $this->user_model->master_fun_get_tbl_val("user_branch", array('status' => 1, "user_fk" => $data["login_data"]['id']), array("id", "asc"));
            $data['branch_list_se'] = $user_branch;
            $branch = array();
            foreach ($user_branch as $key1) {
                $branch[] = $key1["branch_fk"];
            }
            $data['branch_list_select'] = $branch;
        }
        $data['branch_list'] = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1), array("id", "asc"));
        $data['view_all_data'] = $this->report_model->diffrent_report($start_date, $end_date, $data['type'], $data['wise'], $data['branch'], $branch);
        if (isset($_REQUEST['debug2'])) {
            echo $this->db->last_query();
            die();
        }
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('report_all', $data);
        $this->load->view('footer');
    }

    function panel() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['start_date'] = $this->input->get("start_date");
        $data['end_date'] = $this->input->get("end_date");
        $data['branch'] = $this->input->get("branch");
        $data['wise'] = $this->input->get("wise");
        $start_date = null;
        $end_date = null;
        if ($data['start_date'] != "") {
            $start_date = $data['start_date'];
        }

        if ($data['end_date'] != "") {
            $end_date = $data['end_date'];
        }
        $data['view_all_data'] = $this->report_model->panel_report($start_date, $end_date, $data['wise'], $data['branch']);
        $data['branch_list'] = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1), array("id", "asc"));
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('panel_report', $data);
        $this->load->view('footer');
    }

    function creditors($cid) {
        if (!is_loggedin()) {
            redirect('login');
        }
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
        $data['view_all_data'] = $this->report_model->creditors_report($start_date, $end_date, $cid);
        /* echo $this->db->last_query(); die(); */
        $new_array = array();
        foreach ($data['view_all_data'] as $key) {
            $dta = array();
            if ($key["paid"] > 0) {
                $dta = $this->report_model->creditors_report_id($key["paid"]);
            }
            $key["paid_by"] = $dta;
            $new_array[] = $key;
        }
        $data['view_all_data'] = array();
        $data['view_all_data'] = $new_array;
        //echo "<pre>";print_R($new_array); die();
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('creditors_report', $data);
        $this->load->view('footer');
    }

    function branchcreditors($branch = null, $cid = null) {
        if (!is_loggedin()) {
            redirect('login');
        }
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

            $this->load->view('header', $data);
            $this->load->view('nav', $data);
            $this->load->view('branchcreditors_report', $data);
            $this->load->view('footer');
        } else {
            show_404();
        }
    }

    function creditors_csv($cid) {
        if (!is_loggedin()) {
            redirect('login');
        }
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
        $data['view_all_data'] = $this->report_model->creditors_report($start_date, $end_date, $cid);
        $new_array = array();
        foreach ($data['view_all_data'] as $key) {
            $dta = array();
            if ($key["paid"] > 0) {
                $dta = $this->report_model->creditors_report_id($key["paid"]);
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
        header("Content-Disposition: attachment; filename=\"Creditor_Report-" . date('d-M-Y') . ".csv\"");
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
    }

    function branchcreditors_csv($branch, $cid) {
        if (!is_loggedin()) {
            redirect('login');
        }
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
            header("Content-Disposition: attachment; filename=\"Creditor_Report-" . date('d-M-Y') . ".csv\"");
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

    function creditors_all() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $assign_branch = array();

        if ($data["login_data"]["type"] != 1 && $data["login_data"]["type"] != 2) {
            //echo "SELECT GROUP_CONCAT(branch_fk) AS bid FROM `user_branch` WHERE `status`='1' AND user_fk='" . $data["login_data"]["id"] . "' GROUP BY user_fk"; die();
            $uid = $this->report_model->get_val("SELECT GROUP_CONCAT(branch_fk) AS bid FROM `user_branch` WHERE `status`='1' AND user_fk='" . $data["login_data"]["id"] . "' GROUP BY user_fk");
            //echo $uid[0]["bid"]; die();
            $view_all_data = $this->report_model->creditors_list($uid[0]["bid"]);
        } else {

            $view_all_data = $this->report_model->creditors_list();
        }

        /* echo $this->db->last_query(); die(); */
        $data['view_all_data'] = array();
        foreach ($view_all_data as $key) {
            $dta = $this->report_model->creditors_report_all($key["id"]);
            $key["job_count"] = $dta;
            $data['view_all_data'][] = $key;
        }
        if ($_REQUEST["debug"] == 1) {
            print_r($data['view_all_data']);
            die();
        }
        /*   echo "<pre>";
          print_R($data['view_all_data']);
          die(); */
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('creditors_shows', $data);
        $this->load->view('footer');
    }

    function branchcreditors_all() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        if ($_GET) {
            $branch_id = $this->input->get("branch");
        }

        function getcreorlab($creditor) {
            if ($creditor != "") {
                $ci = & get_instance();
                /* $laball=$ci->report_model->get_val("SELECT cm.id,cm.`name`,cm.`mobile`,COUNT(cb.job_id) AS totaljobs,SUM(CASE WHEN cb.paid <= '0' THEN cb.`debit` ELSE 0 END) AS blance FROM creditors_balance cb JOIN creditors_master cm ON cb.creditors_fk = cm.id LEFT JOIN job_master j ON j.`id`=cb.job_id and j.status !='0' and j.branch_fk='$branch'  WHERE  cb.debit>'0' AND cb.`status`='1' AND cb.creditors_fk IN($creditor) GROUP BY cb.creditors_fk"); */
                $laball = $ci->report_model->get_val("SELECT cm.id,cm.`name`,cm.`mobile` FROM creditors_master cm  WHERE cm.id IN ($creditor) AND cm.`status`='1'");
                return $laball;
            } else {
                return array();
            }
        }

        function getcreorjobs($branch, $creditor) {
            if ($creditor != "") {
                $ci = & get_instance();
                $laball = $ci->report_model->get_val("SELECT COUNT(cb.job_id) AS totaljobs,SUM(CASE WHEN cb.paid <= '0' THEN cb.`debit` ELSE 0 END) AS blance FROM creditors_balance cb JOIN job_master j ON j.`id` = cb.job_id AND j.`status` != '0'  AND j.`branch_fk`='$branch' WHERE cb.status = '1' AND cb.debit > '0' AND cb.creditors_fk='$creditor' ");
                return $laball;
            } else {
                return array();
            }
        }

        if ($data["login_data"]["type"] != 1 && $data["login_data"]["type"] != 2) {
            $uid = $this->report_model->get_val("SELECT GROUP_CONCAT(branch_fk) AS bid FROM `user_branch` WHERE `status`='1' AND user_fk='" . $data["login_data"]["id"] . "' GROUP BY user_fk");

            if ($uid[0]["bid"] != "") {
                $salidbid = $uid[0]["bid"];
            } else {
                $salidbid = 0;
            }

            $data['query'] = $this->report_model->get_val("SELECT b.id,b.branch_name,GROUP_CONCAT(DISTINCT cb.`creditors_fk`) AS creditors FROM `branch_master` b LEFT JOIN `creditors_branch` cb ON cb.`branch_fk`=b.id AND cb.`status`='1' WHERE b.status='1' and b.id in($salidbid) GROUP BY b.id ORDER BY b.branch_name ASC");
        } else {
            if (isset($_GET['branch']) && $_GET['branch'] != "") {
                $branch_id = $this->input->get("branch");
                $data['branch_id'] = $branch_id;
                $q = "SELECT b.id,b.branch_name,GROUP_CONCAT(DISTINCT cb.`creditors_fk`) AS creditors FROM `branch_master` b LEFT JOIN `creditors_branch` cb ON cb.`branch_fk`=b.id AND cb.`status`='1' WHERE b.status='1' AND b.id = '$branch_id'";

                if (isset($_GET['start_date']) && $_GET['start_date'] != "") {
                    $start_date = $_GET['start_date'];
                    $data['start_date'] = $start_date;
                    $q .= "AND cb.created_date>= '$start_date'";
                }

                if (isset($_GET['end_date']) && $_GET['end_date'] != "") {
                    $end_date = $_GET['end_date'];
                    $data['end_date'] = $end_date;
                    if ($start_date == "") {
                        str_replace("AND cb.created_date>= '$start_date'", "", $q);
                    }
                    $q .= "AND cb.created_date<= '$end_date'";
                }
                $data['query'] = $this->report_model->get_val($q);
            } else if ((isset($_GET['start_date']) && $_GET['start_date'] != "" || isset($_GET['end_date']) && $_GET['end_date'] != "") && $_GET['branch'] == "") {
                $start_date = $_GET['start_date'];
                $data['start_date'] = $start_date;
                $end_date = $_GET['end_date'];
                $data['end_date'] = $end_date;

                if ($start_date != "" && $end_date == "") {
                    $q = "SELECT b.id,b.branch_name,GROUP_CONCAT(DISTINCT cb.`creditors_fk`) AS creditors FROM `branch_master` b LEFT JOIN `creditors_branch` cb ON cb.`branch_fk`=b.id AND cb.`status`='1' WHERE b.status='1' AND cb.created_date>= '$start_date' GROUP BY b.id ";
                } else if ($start_date == "" && $end_date != "") {
                    $q = "SELECT b.id,b.branch_name,GROUP_CONCAT(DISTINCT cb.`creditors_fk`) AS creditors FROM `branch_master` b LEFT JOIN `creditors_branch` cb ON cb.`branch_fk`=b.id AND cb.`status`='1' WHERE b.status='1' AND cb.created_date<= '$end_date' GROUP BY b.id ";
                } else if ($start_date != "" && $end_date != "") {
                    $q = "SELECT b.id,b.branch_name,GROUP_CONCAT(DISTINCT cb.`creditors_fk`) AS creditors FROM `branch_master` b LEFT JOIN `creditors_branch` cb ON cb.`branch_fk`=b.id AND cb.`status`='1' WHERE b.status='1' AND cb.created_date>= '$start_date' AND cb.created_date<= '$end_date' GROUP BY b.id";
                }
                $data['query'] = $this->report_model->get_val($q);
            } else {
                $data['query'] = $this->report_model->get_val("SELECT b.id,b.branch_name,GROUP_CONCAT(DISTINCT cb.`creditors_fk`) AS creditors FROM `branch_master` b LEFT JOIN `creditors_branch` cb ON cb.`branch_fk`=b.id AND cb.`status`='1' WHERE b.status='1' GROUP BY b.id ORDER BY b.branch_name ASC");
            }

            //$data['query'] = $this->report_model->get_val("SELECT b.id,b.branch_name,GROUP_CONCAT(DISTINCT cb.`creditors_fk`) AS creditors FROM `branch_master` b LEFT JOIN `creditors_branch` cb ON cb.`branch_fk`=b.id AND cb.`status`='1' WHERE b.status='1' GROUP BY b.id ORDER BY b.branch_name ASC");
        }


        $data['branch_list'] = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1), array("id", "asc"));
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('branchcreditors_shows', $data);
        $this->load->view('footer');
    }

    function add_credits() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        /* if($data["login_data"]['id']==17){
          echo "<pre>"; print_r($_POST); die();
          } */
        $amount = $this->input->post("amount");
        $creditor = $this->input->post("creditor");
        $remark = $this->input->post("remarks");
        $job_fk = $this->input->post("job_fk");
        //print_R(array('job_id' => $job_fk)); die();
        $creditor_report = $this->user_model->master_fun_get_tbl_val("creditors_balance", array('job_id' => $job_fk), array("id", "asc"));
        $insert = $this->user_model->master_fun_insert("creditors_balance", array("remark" => $remark, "job_id" => $job_fk, "creditors_fk" => $creditor, "credit" => $amount, "created_by" => $data["login_data"]['id'],"created_date"=>date("Y-m-d H:i:s")));


        $this->report_model->master_fun_update("creditors_balance", array("id", $creditor_report[0]["id"]), array("paid" => $insert));
        echo $insert;
    }

    function test_report() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['start_date'] = $this->input->get("start_date");
        $data['end_date'] = $this->input->get("end_date");
        $data['branch'] = $this->input->get("branch");
        $start_date = null;
        $end_date = null;
        if ($data['start_date'] != "") {
            $start_date = $data['start_date'];
        }

        if ($data['end_date'] != "") {
            $end_date = $data['end_date'];
        }
        if ($data["login_data"]['type'] == 6 || $data["login_data"]['type'] == 5) {
            $user_branch = $this->user_model->master_fun_get_tbl_val("user_branch", array('status' => 1, "user_fk" => $data["login_data"]['id']), array("id", "asc"));
            $data['branch_list_se'] = $user_branch;
            $branch = array();
            foreach ($user_branch as $key1) {
                $branch[] = $key1["branch_fk"];
            }
            $data['branch_list_select'] = $branch;
        }
        $data['branch_list'] = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1), array("id", "asc"));
        $data['view_all_data'] = $this->report_model->test_report($start_date, $end_date, $data['branch'], $branch);
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('test_report', $data);
        $this->load->view('footer');
    }

    function doctor_test_report() {
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
        $data['end_date'] = $this->input->get("end_date");
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

        if ($data['end_date'] != "") {
            $end_date = $data['end_date'];
        }
        $data['city_list'] = $this->user_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
        $data['branch_list'] = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1), array("branch_name", "asc"));
        $data['collecting_amount_branch'] = $this->report_model->testdoctorReport($start_date, $end_date, $data['branch'], $data['branchs'], $data['city']);
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('doctor_test_report', $data);
        $this->load->view('footer');
    }

    function doctor_test_export() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $start = $this->input->get("start_date");
        $end = $this->input->get("end_date");
        $branch = $this->input->get("branch");
        $date1 = explode("/", $start);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $end);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "Doctor_test_report.csv";
        $qry = "SELECT 
            dm.id as `Doctor ID`,
            dm.full_name as `Doctor`,
            DATE_FORMAT(jm.date,'%e-%b-%y') as `Date`,
            jm.id as `R.ID`,
            jm.order_id as `Order ID`,
            cm.full_name as `Patient`,
            tm.test_name as `Test`,
            tmc.price as `Price`,
            Round(jm.price) as `Job Price`,
            Round((if(jm.`discount` != 'NULL',jm.`discount`,0) * if(jm.price != 'NULL',jm.price,0)) / 100) as `Discount`,
            IF(
    `jm`.`phlebo_added` != '',
    CONCAT(pm.`name`,'','(Phlebotomy)'),`am`.`name`
  ) AS `Added By`,
  b.branch_name as `Branch`
FROM
  job_master jm 
LEFT JOIN `job_master_receiv_amount` jmr 
    ON jm.id = jmr.`job_fk`
    join job_test_list_master jtl
    on jtl.job_fk = jm.id
    left join test_master tm
    on tm.id = jtl.test_fk
    left join test_master_city_price tmc
    on tmc.test_fk = tm.id
 LEFT JOIN `admin_master` am 
    ON jm.added_by = am.`id` 
    LEFT JOIN `phlebo_master` pm
    ON jm.phlebo_added = pm.id
    JOIN doctor_master dm
    ON dm.id = jm.doctor
    left join customer_master cm
    on cm.id = jm.cust_fk
  JOIN branch_master b 
    ON b.id = jm.branch_fk 
   join test_cities tc 
    on tc.id = b.city
 WHERE   jm.`status` != '0' ";
        $qry .= " AND jm.date >= '" . $start_date . "' AND jm.date <= '" . $end_date . "'";
        $qry .= " AND b.id  = '" . $branch . "'";
        $qry .= " group by dm.id,cm.id,tm.id order by dm.`id` ASC";
        $result = $this->db->query($qry);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }

    function job_received() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['start_date'] = $this->input->get("start_date");
        $data['end_date'] = $this->input->get("end_date");
        $data['branch'] = $this->input->get("branch");
        $data['type'] = $this->input->get("type");
        $data['wise'] = $this->input->get("wise");
        $start_date = null;
        $end_date = null;
        if ($data['start_date'] != "") {
            $start_date = $data['start_date'];
        }

        if ($data['end_date'] != "") {
            $end_date = $data['end_date'];
        }
        if ($data["login_data"]['type'] == 6 || $data["login_data"]['type'] == 5) { 
            $user_branch = $this->user_model->master_fun_get_tbl_val("user_branch", array('status' => 1, "user_fk" => $data["login_data"]['id']), array("id", "asc"));
            $data['branch_list_se'] = $user_branch;
            $branch = array();
            foreach ($user_branch as $key1) {
                $branch[] = $key1["branch_fk"];
            }
            $data['branch_list_select'] = $branch;
        }
        $data['branch_list'] = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1), array("id", "asc"));
        if ($start_date != '' || $end_date != '') {
            $data['view_all_data'] = $this->report_model->job_received_report($start_date, $end_date, $data['branch'], $branch);
        }
        if ($_REQUEST['debug']) {
            echo $this->db->last_query();
            die();
        }
        //echo "<pre>"; print_r($data['view_all_data']); die();
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('job_received_report', $data);
        $this->load->view('footer');
    }

    function job_received_export() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $start = $this->input->get("start_date");
        $end = $this->input->get("end_date");
        $branch = $this->input->get("branch");
        $date1 = explode("/", $start);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $end);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "Job_received_report.csv";
        $qry = "select
            bm.branch_name as branch,
            bm.id as bid,
              IF(`booking_info`.`family_member_fk`>0,`customer_family_master`.`name`,cm.full_name) AS patient,
            dm.full_name as doctor_name,
            jm.order_id as order_id,
            jm.id as jid,
            IF(
    `jm`.`phlebo_added` != '',
    CONCAT(cbp.`name`,'','(Phlebotomy)'),`cb`.`name`
  ) as added_name,
            jm.date as added_date,
            Round(jm.`price`) as gross_amt,
            Round((if(jm.`discount` != 'NULL',jm.`discount`,0) * if(jm.price != 'NULL',jm.price,0)) / 100) as discount,
            Round(if(jm.price != 'NULL',jm.price,0) - ((if(jm.`discount` != 'NULL',jm.`discount`,0) * if(jm.price != 'NULL',jm.price,0)) / 100)) as net_amt,
            Round(jr.amount) as received_amt,
            jr.payment_type as received_type,REPLACE(jr.`remark`,'credited by','Creditor Name : ') AS remark,
            IF(
    `jr`.`phlebo_fk` != '',
    CONCAT(rcp.`name`,'','(Phlebotomy)'),`rc`.`name`
  ) as received_name,
            jr.createddate as received_date,
            Round(jm.payable_amount) as due_amt
                from job_master jm join branch_master bm on bm.id=jm.branch_fk left JOIN `booking_info` 
    ON `booking_info`.`id` = jm.`booking_info` 
    left JOIN `customer_family_master` 
    ON `customer_family_master`.`id`=`booking_info`.`family_member_fk` left join doctor_master dm on dm.id = jm.doctor left join admin_master cb on cb.id=jm.added_by left join phlebo_master cbp on cbp.id=jm.phlebo_added join customer_master cm on cm.id=jm.cust_fk left join job_master_receiv_amount jr on jr.job_fk=jm.id and jr.status='1' left join admin_master rc on rc.id=jr.added_by left join phlebo_master rcp on rcp.id=jr.phlebo_fk where jm.model_type=1 and jm.status != '0' and bm.status = '1' ";
        $qry .= " AND jm.date >= '" . $start_date . "' AND jm.date <= '" . $end_date . "'";
        if ($branch != "") {
            $qry .= " AND jm.branch_fk = '" . $branch . "' ";
        }
        $qry .= " order by bm.`id`,jm.id ASC";
        //$qry .= " group by jm.id,jr.added_by,jr.phlebo_fk order by bm.`id`,jm.id ASC";
        $result = $this->db->query($qry);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }
    
    
    
    function outsource_report() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $this->load->library('pagination');
        $start_date = $this->input->get_post('start_date');
        $end_date = $this->input->get_post('end_date');
        $branch = $this->input->get_post('branch');
		$outsourcelab = $this->input->get_post('outsourcelab');
		
        if ($start_date != '' || $end_date != '' || $branch != '' || $outsourcelab != '') {
            
            $srchdata = array("start_date" => $start_date, "end_date" => $end_date, "branch" => $branch, "outsourcelab" => $outsourcelab);
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['branch_id'] = $branch;
			$data['outsourcelab_id'] = $outsourcelab;
            $totalRows = $this->report_model->get_sub_data($srchdata);
            
            $config = array();
            $config["base_url"] = base_url() . "report_master/outsource_report?start_date=$start_date&end_date=$end_date&branch=$branch&outsourcelab=$outsourcelab";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 100;
            $config["uri_segment"] = 3;
            $config['page_query_string'] = TRUE;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            
            $data['query'] = $this->report_model->getoutsource($srchdata, $config["per_page"], $page);
            //echo "<pre>"; print_r($data['query']); exit;
            // echo $this->db->last_query();die();
            $data['final_array'] = array();

            $cnt = 0;
            foreach ($data['query'] as $val) {
                $city_id = $val['CityId'];
                $test_id = $val['testId'];
                $new_array[] = array();
                $getData = $this->report_model->get_val("SELECT DISTINCT outsource_master.id AS OutID,outsource_master.name,outsource_test_price.price AS OutPrice,outsource_test_price.test_fk AS TestId,outsource_master.city_fk AS City FROM `outsource_master` LEFT JOIN `outsource_test_price` ON outsource_master.`id` = outsource_test_price.`outsource_fk` AND outsource_test_price.`status` = '1' WHERE outsource_test_price.price != 0 AND outsource_test_price.price >= 0 AND outsource_master.`status` = '1' AND outsource_master.`city_fk` ='" . $city_id . "' AND outsource_test_price.`test_fk` = '" . $test_id . "'  ");
                $new_array[] = $getData;
                $val['new_price'] = $getData;
                $data['final_array'][] = $val;
                $cnt++;
            }
            $data["links"] = $this->pagination->create_links();
			$data['counts'] = $page;
        }
        
        $data['branch_list'] = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1), array("id", "asc"));
		$data['outsourcelab_list'] = $this->user_model->master_fun_get_tbl_val("outsource_master", array('status' => 1), array("id", "asc"));
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('outsource_report', $data);
        $this->load->view('footer');
    }
    
    
    
    
    
//    function outsource_report() {
//
//        if (!is_loggedin()) {
//            redirect('login');
//        }
//        $data["login_data"] = logindata();
//        if ($_GET) {
//            $branch_id = $this->input->get("branch");
//        }
//        $q = "SELECT b.id,jm.id AS job_id,b.branch_name, jm.order_id,tm.test_name,om.name AS lab_name,uo.created_date,am.name AS admin_name
//FROM `branch_master` b 
//	INNER JOIN `job_master` jm ON jm.`branch_fk`=b.id 
//	INNER JOIN `user_test_outsource` uo ON uo.`job_fk`=jm.id 
//	INNER JOIN `test_master` tm ON uo.`test_fk`=tm.id
//	INNER JOIN `outsource_master` om ON uo.`outsource_fk`=om.id
//            LEFT JOIN `outsource_test_price` op ON op.`outsource_fk` = om.id AND op.`test_fk` = tm.id
//
//	INNER JOIN `admin_master` am ON uo.`created_by`=am.id	
//
//WHERE b.status='1'";
//        if (isset($_GET['branch']) && $_GET['branch'] != "") {
//
//            $branch_id = $this->input->get("branch");
//            $data['branch_id'] = $branch_id;
//
//            $q .="AND b.id='$branch_id'";
//
//            if (isset($_GET['start_date']) && $_GET['start_date'] != "") {
//                $start_date = $_GET['start_date'];
//                $data['start_date'] = $start_date;
//                $q .= "AND uo.created_date>= '$start_date'";
//            }
//
//            if (isset($_GET['end_date']) && $_GET['end_date'] != "") {
//                $end_date = $_GET['end_date'];
//                $data['end_date'] = $end_date;
//                if ($start_date == "") {
//                    str_replace("AND uo.created_date>= '$start_date'", "", $q);
//                }
//                $q .= "AND uo.created_date<= '$end_date'";
//            }
//            $data['query'] = $this->report_model->get_val($q);
//        } else if ((isset($_GET['start_date']) && $_GET['start_date'] != "" || isset($_GET['end_date']) && $_GET['end_date'] != "") && $_GET['branch'] == "") {
//            $start_date = $_GET['start_date'];
//            $data['start_date'] = $start_date;
//            $end_date = $_GET['end_date'];
//            $data['end_date'] = $end_date;
//
//            if ($start_date != "" && $end_date == "") {
//                $q .= "AND uo.created_date>= '$start_date'";
//            } else if ($start_date == "" && $end_date != "") {
//                $q .= "AND uo.created_date<= '$end_date'";
//            } else if ($start_date != "" && $end_date != "") {
//                $q .= "AND uo.created_date>= '$start_date' AND uo.created_date<= '$end_date'";
//            }
//            $data['query'] = $this->report_model->get_val($q);
//        } else {
//            $data['query'] = "";
//        }
////GROUP BY b.id 
//        //}
//
//        $data['branch_list'] = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1), array("id", "asc"));
//        $this->load->view('header', $data);
//        $this->load->view('nav', $data);
//        $this->load->view('outsource_report', $data);
//        $this->load->view('footer');
//    }

    public function export_csv_outsource($phone = "", $status = "") {

        $q = "SELECT b.id,jm.id AS job_id,cm.full_name AS patient_name,b.branch_name,jm.order_id,tm.id as testId, tm.test_name,om.id AS OutSource_id,om.name AS lab_name,uo.created_date,am.name AS admin_name,tc.city_fk as CityId,tmc.price FROM`branch_master` b
			INNER JOIN `job_master` jm ON jm.`branch_fk`=b.id 
			INNER JOIN `user_test_outsource` uo ON uo.`job_fk`=jm.id 
			INNER JOIN `test_master` tm ON uo.`test_fk`=tm.id
			INNER JOIN `outsource_master` om ON uo.`outsource_fk`=om.id
			INNER JOIN `admin_master` am ON uo.`created_by`=am.id
			INNER JOIN `customer_master` cm ON cm.id = jm.cust_fk
			LEFT JOIN test_cities tc ON tc.id = jm.`test_city` AND tc.status = '1' 
			LEFT JOIN test_master_city_price AS tmc ON tmc.test_fk = tm.id AND tc.id=tmc.`city_fk` AND tmc.status = '1'			
			WHERE b.status='1'";
		//SELECT b.id,cm.full_name AS patient_name,b.branch_name, jm.order_id,tm.test_name,om.name AS lab_name,uo.created_date,am.name AS admin_name FROM `branch_master` b 
		//LEFT JOIN `outsource_test_price` op ON op.`outsource_fk` = om.id AND op.`test_fk` = tm.id
		if (isset($_GET['start_date']) && $_GET['start_date'] != "") {
			$start_date = $_GET['start_date'].' 00:00:00';
			$data['start_date'] = $start_date;
			$q .= "AND uo.created_date >= '$start_date'";
		}

		if (isset($_GET['end_date']) && $_GET['end_date'] != "") {
			$end_date = $_GET['end_date'].' 23:59:59';
			$data['end_date'] = $end_date;
			if ($start_date == "") {
				str_replace("AND uo.created_date>= '$start_date'", "", $q);
			}
			$q .= "AND uo.created_date <= '$end_date'";
		}
		
		if (isset($_GET['branch']) && $_GET['branch'] != "") {
            $branch_id = $this->input->get("branch");
            $data['branch_id'] = $branch_id;
            $q .="AND b.id = '$branch_id'";
		}
		
		if(isset($_GET['outsourcelab']) && $_GET['outsourcelab'] != ""){
			$outsourcelab = $_GET['outsourcelab'];
			$q .= "AND om.id = '$outsourcelab'";
		}
		
		
		$result = null;
		$final_array = array();
		if (isset($_GET['start_date']) && $_GET['start_date'] != "" || isset($_GET['end_date']) && $_GET['end_date'] != "" || isset($_GET['branch']) && $_GET['branch'] != "" || isset($_GET['outsourcelab']) && $_GET['outsourcelab'] != "") {
			$result = $this->report_model->get_val($q);
			//echo "<pre>"; print_r($result); exit;
            $cnt = 0;
            foreach ($result as $val) {
                $city_id = $val['CityId'];
                $test_id = $val['testId'];                
                $getData = $this->report_model->get_val("SELECT DISTINCT outsource_master.id AS OutID,outsource_master.name,outsource_test_price.price AS OutPrice,outsource_test_price.test_fk AS TestId,outsource_master.city_fk AS City FROM `outsource_master` LEFT JOIN `outsource_test_price` ON outsource_master.`id` = outsource_test_price.`outsource_fk` AND outsource_test_price.`status` = '1' WHERE outsource_test_price.price != 0 AND outsource_test_price.price >= 0 AND outsource_master.`status` = '1' AND outsource_master.`city_fk` ='" . $city_id . "' AND outsource_test_price.`test_fk` = '" . $test_id . "'  ");              
                $val['new_price'] = $getData;
                $final_array[] = $val;
                $cnt++;
            }
		}
		
        /*if (isset($_GET['branch']) && $_GET['branch'] != "") {

            $branch_id = $this->input->get("branch");
            $data['branch_id'] = $branch_id;

            $q .="AND b.id='$branch_id'";

            if (isset($_GET['start_date']) && $_GET['start_date'] != "") {
                $start_date = $_GET['start_date'];
                $data['start_date'] = $start_date;
                $q .= "AND uo.created_date>= '$start_date'";
            }

            if (isset($_GET['end_date']) && $_GET['end_date'] != "") {
                $end_date = $_GET['end_date'];
                $data['end_date'] = $end_date;
                if ($start_date == "") {
                    str_replace("AND uo.created_date>= '$start_date'", "", $q);
                }
                $q .= "AND uo.created_date<= '$end_date'";
            }
            $result = $this->report_model->get_val($q);
        } else if ((isset($_GET['start_date']) && $_GET['start_date'] != "" || isset($_GET['end_date']) && $_GET['end_date'] != "") && $_GET['branch'] == "") {
            $start_date = $_GET['start_date'];
            $data['start_date'] = $start_date;
            $end_date = $_GET['end_date'];
            $data['end_date'] = $end_date;

            if ($start_date != "" && $end_date == "") {
                $q .= "AND uo.created_date>= '$start_date'";
            } else if ($start_date == "" && $end_date != "") {
                $q .= "AND uo.created_date<= '$end_date'";
            } else if ($start_date != "" && $end_date != "") {
                $q .= "AND uo.created_date>= '$start_date' AND uo.created_date<= '$end_date'";
            }
            $result = $this->report_model->get_val($q);
        } else {
            $result = "";
        }*/
		//echo "<pre>";print_r($final_array); exit;
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"outsource_report.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No.", "Order ID", "Booking Date", "Patient Name", "Test Name", "Airmed Source Lab", "Test MRP At Airmed", "Outsource Lab", "Outsource Price", "Price Of Other Destination Lab", "Outsource By"));

        $count = 0;
        foreach ($final_array as $val) {
            $count = $count + 1;
			if(!empty($val['new_price'])){
				$outprice = null;
				$otheroutprice = null;
				foreach($val['new_price'] as $nval){
					if($nval['OutID']==$val['OutSource_id']){
						$outprice .= "Rs. ".$nval['OutPrice'];
					}
					if($nval['OutID']!=$val['OutSource_id']){
						$otheroutprice .= $nval['name']." (Rs. ".$nval['OutPrice'].")";
					}	
				}
				fputcsv($handle, array($count, $val["order_id"], $val["created_date"], $val['patient_name'], $val["test_name"], $val["branch_name"], $val["price"], $val["lab_name"], $outprice , $otheroutprice , $val["admin_name"]));
			}else{
				fputcsv($handle, array($count, $val["order_id"], $val["created_date"], $val['patient_name'], $val["test_name"], $val["branch_name"], $val["price"], $val["lab_name"], "" , "" , $val["admin_name"]));
			}
        }
        fclose($handle);
        exit;
    }
	function test_status() {
        $this->load->library('pagination');
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->model('add_result_model');

        $data["login_data"] = logindata();

        $branch_id = $this->input->get("branch");
        $start_date1 = $this->input->get("start_date");

        $start_d1 = explode("/", $start_date1);
        $start_date = $start_d1[2] . "-" . $start_d1[1] . "-" . $start_d1[0];

        $end_date1 = $this->input->get("end_date");
        $start_d2 = explode("/", $end_date1);
        $end_date = $start_d2[2] . "-" . $start_d2[1] . "-" . $start_d2[0];

        $report_status = $this->input->get("report_status");

        $temp = "";
        $temo1 = "";
        if ($branch_id != "") {
            $temp .= "AND job_master.branch_fk ='$branch_id'";
        }

        if ($start_date1 != "") {
            $temp .= "AND job_master.date >='$start_date 00:00:00'";
        }

        if ($end_date1 != "") {
            $temp .= "AND job_master.date <='$end_date 23:23:59'";
        }

        if ($start_date1 == "" && $end_date1 == "") {
            $temp .= "AND job_master.date >='" . date('Y-m-d') . "'";
        }

        if ($report_status != "") {
            $temp1 .= "AND user_test_result.report_status ='$report_status'";
        }



        $data['start_date'] = $start_date1;
        $data['end_date'] = $end_date1;
        $data['branch'] = $branch_id;
        $data['report_status'] = $report_status;



        $q = "SELECT job_master.id,job_master.cust_fk,customer_master.full_name,branch_master.branch_name FROM `job_master` 
            INNER JOIN customer_master on customer_master.id = job_master.cust_fk 
            INNER JOIN branch_master on branch_master.id = job_master.branch_fk 
            where job_master.id IN (select job_id from user_test_result where status = '1')                 
                $temp order by job_master.id desc";

//        $q = "SELECT id,customer_name FROM `job_master` where id IN (select job_id from user_test_result where status = '1')
//                $temp order by id desc";
//        echo $q; exit;

        $query1 = $this->report_model->get_val($q);

        $final_array = array();
        foreach ($query1 as $q) {

            $job_id = $q['id'];
            $data['query'] = $this->add_result_model->job_details($q['id']);
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

            $test_name = array();
            foreach ($tid as $key) {
                $price1 = $this->add_result_model->get_val("SELECT 
                        test_master.id,`test_master`.`test_name`,user_test_result.report_status,user_test_result.value 
                        FROM `test_master` 
                        INNER JOIN user_test_result on test_master.id = user_test_result.test_id AND user_test_result.status = '1' 
                        WHERE `test_master`.`status`='1' AND  user_test_result.job_id='$job_id' AND `user_test_result`.`test_id`='$key'
                        $temp1
                        ");

                if (count($price1) > 0) {
                    $test_name[] = $price1[0];
                }
            }
            $q['test_data'] = $test_name;
            $final_array[] = $q;
            unset($test_name);
//            echo "<pre>";
//            print_r($q);
//            exit;
        }

        $data['final_array'] = $final_array;

//        $count = 0;
//        foreach ($final_array as $q){
//            foreach ($q['test_data'] as $temp){
//                $count++;
//            }
//        }
//        //echo $count; exit;
//        $totalRows = $count;
//        $config = array();
//        $config["base_url"] = base_url() . "Report_master/test_status/";
//        $config["total_rows"] = $totalRows;
//        $config["per_page"] = 5;
//        $config["uri_segment"] = 3;
//        $config['cur_tag_open'] = '<span>';
//        $config['cur_tag_close'] = '</span>';
//        $config['next_link'] = 'Next &rsaquo;';
//        $config['prev_link'] = '&lsaquo; Previous';
//        $this->pagination->initialize($config);
//        
//        $sort = $this->input->get("sort");
//        $by = $this->input->get("by");
//        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
//        //LIMIT $page,$config["per_page"]        
//        $data["final_array"] = $final_array;
//        $data["links"] = $this->pagination->create_links();
//        $data["counts"] = $page;

        $data['branch_list'] = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1), array("id", "asc"));
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('test_status_report', $data);
        $this->load->view('footer');
    }

    function test_status_csv() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->model('add_result_model');
        $data["login_data"] = logindata();

        $branch_id = $this->input->get("branch");
        $start_date1 = $this->input->get("start_date");

        $start_d1 = explode("/", $start_date1);
        $start_date = $start_d1[2] . "-" . $start_d1[1] . "-" . $start_d1[0];

        $end_date1 = $this->input->get("end_date");
        $start_d2 = explode("/", $end_date1);
        $end_date = $start_d2[2] . "-" . $start_d2[1] . "-" . $start_d2[0];

        $report_status = $this->input->get("report_status");

        $temp = "";
        $temo1 = "";
        if ($branch_id != "") {
            $temp .= "AND job_master.branch_fk ='$branch_id'";
        }

        if ($start_date1 != "") {
            $temp .= "AND job_master.date >='$start_date 00:00:00'";
        }

        if ($end_date1 != "") {
            $temp .= "AND job_master.date <='$end_date 23:23:59'";
        }

        if ($start_date1 == "" && $end_date1 == "") {
            $temp .= "AND job_master.date >='" . date('Y-m-d') . "'";
        }

        if ($report_status != "") {
            $temp1 .= "AND user_test_result.report_status ='$report_status'";
        }



        $data['start_date'] = $start_date1;
        $data['end_date'] = $end_date1;
        $data['branch'] = $branch_id;
        $data['report_status'] = $report_status;



        $q = "SELECT job_master.id,job_master.cust_fk,customer_master.full_name,branch_master.branch_name FROM `job_master` 
            INNER JOIN customer_master on customer_master.id = job_master.cust_fk 
            INNER JOIN branch_master on branch_master.id = job_master.branch_fk 
            where job_master.id IN (select job_id from user_test_result where status = '1')                 
                $temp order by job_master.id desc";

//        $q = "SELECT id,customer_name FROM `job_master` where id IN (select job_id from user_test_result where status = '1')
//                $temp order by id desc";
//        echo $q; exit;

        $query1 = $this->report_model->get_val($q);

        $final_array = array();
        foreach ($query1 as $q) {

            $job_id = $q['id'];
            $data['query'] = $this->add_result_model->job_details($q['id']);
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

            $test_name = array();
            foreach ($tid as $key) {
                $price1 = $this->add_result_model->get_val("SELECT 
                        test_master.id,`test_master`.`test_name`,user_test_result.report_status,user_test_result.value 
                        FROM `test_master` 
                        INNER JOIN user_test_result on test_master.id = user_test_result.test_id AND user_test_result.status = '1' 
                        WHERE `test_master`.`status`='1' AND  user_test_result.job_id='$job_id' AND `user_test_result`.`test_id`='$key'
                        $temp1
                        ");

                if (count($price1) > 0) {
                    $test_name[] = $price1[0];
                }
            }
            $q['test_data'] = $test_name;
            $final_array[] = $q;
            unset($test_name);
//            echo "<pre>";
//            print_r($q);
//            exit;
        }

        $data['final_array'] = $final_array;




        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Patient_test_status_report.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No.", "Reg No.", "Patient Name", "Branch Name", "Test Name", "Report Status"));

        $count = 0;
        foreach ($final_array as $q) {

            foreach ($q['test_data'] as $test) {
                $count = $count + 1;
                if ($test['report_status'] == 1) {
                    $report = "Critical";
                } else if ($test['report_status'] == 2) {
                    $report = "Semi-Critical";
                } else if ($test['report_status'] == 3) {
                    $report = "Normal";
                } else if ($test['report_status'] == 0) {
                    $report = "N/A";
                }
                fputcsv($handle, array($count, $q["id"], $q["full_name"], $q["branch_name"], $test['test_name'], $report));
            }
        }
        fclose($handle);
        exit;
    }
    
    
   function techlogin_report() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();

        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $branch_id = $this->input->get("branch");
        $city_id = $this->input->get("city");

        $data['branch'] = $branch_id;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['city_id'] = $city_id;


        $temp = "";
        if ($branch_id != "") {
            $temp = " AND bm.id = '$branch_id'";
        }
        if ($start_date != "") {
            $temp .=" AND tl.in_time>= '$start_date 00:00:00' AND tl.out_time!='0000-00-00 00:00:00'";
        }
        if ($end_date != "") {
            $temp .=" AND tl.out_time<= '$end_date 23:23:59' AND tl.out_time!='0000-00-00 00:00:00'";
        }

        if ($start_date == '' && $end_date == '') {
            $temp .=" AND tl.in_time>= '" . date('Y-m-d') . " 00:00:00' AND tl.out_time<= '" . date('Y-m-d') . " 23:23:59'";
        }

        $q = "select tl.id,tl.user_id,tl.ip_address,tl.in_time,tl.out_time,tl.status,am.name user_name,
            bm.branch_name, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(tl.out_time,tl.in_time)))) As 
            Total_working_hour,tc.name city_name 
            from techlogin_logs tl 
                LEFT JOIN admin_master am on am.id = tl.user_id AND am.status='1'
                LEFT JOIN user_branch ub on ub.user_fk = am.id AND ub.status='1' 
                LEFT JOIN branch_master bm on bm.id = ub.branch_fk AND bm.status='1'
                LEFT JOIN test_cities tc on tc.id = bm.city AND tc.status='1'
                where tl.status='1' $temp  GROUP BY tl.id";

        $data['query'] = $this->report_model->get_val($q);
//        echo $this->db->last_query();
//        exit;


        $data['branch_list'] = $this->user_model->get_val("select id,branch_name from branch_master where status='1' AND branch_type_fk ='6'");

        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('techlogin_log', $data);
        $this->load->view('footer');
    }

    function export_csv_techlogin() {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();

        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $branch_id = $this->input->get("branch");
        $city_id = $this->input->get("city");

        $data['branch'] = $branch_id;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['city_id'] = $city_id;

        $temp = "";
        if ($branch_id != "") {
            $temp = " AND bm.id = '$branch_id'";
        }
        if ($start_date != "") {
            $temp .=" AND tl.in_time>= '$start_date 00:00:00' AND tl.out_time!='0000-00-00 00:00:00'";
        }
        if ($end_date != "") {
            $temp .=" AND tl.out_time<= '$end_date 23:23:59' AND tl.out_time!='0000-00-00 00:00:00'";
        }

        if ($start_date == '' && $end_date == '') {
            $temp .=" AND tl.in_time>= '" . date('Y-m-d') . " 00:00:00' AND tl.out_time<= '" . date('Y-m-d') . " 23:23:59'";
        }

        $q = "select tl.id,tl.user_id,tl.ip_address,tl.in_time,tl.out_time,tl.status,am.name user_name,
            bm.branch_name, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(tl.out_time,tl.in_time)))) As 
            Total_working_hour,tc.name city_name 
            from techlogin_logs tl 
                LEFT JOIN admin_master am on am.id = tl.user_id AND am.status='1'
                LEFT JOIN user_branch ub on ub.user_fk = am.id AND ub.status='1' 
                LEFT JOIN branch_master bm on bm.id = ub.branch_fk AND bm.status='1'
                LEFT JOIN test_cities tc on tc.id = bm.city AND tc.status='1'
                where tl.status='1' $temp  GROUP BY tl.id";

        $data['query'] = $this->report_model->get_val($q);




        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Tech_login_logs_report.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("Sr.No.", "Name", "City", "Branch", "IP Address", "Login Time", "Logout Time", "Total Duration"));

        $count = 1;
        foreach ($data['query'] as $q) {
            if ($q['out_time'] == '0000-00-00 00:00:00') {
                $outime = '-';
                $totaltime = '-';
            } else {
                $outime = $q['out_time'];
                $totaltime = $q['Total_working_hour'];
            }
            fputcsv($handle, array($count, $q["user_name"], $q["city_name"], $q["branch_name"], $q['ip_address'], $q['in_time'], $outime, $totaltime));
            $count++;
        }
        fclose($handle);
        exit;
    }


}

?>