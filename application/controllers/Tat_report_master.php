<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tat_report_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('job_model');
        $this->load->model('user_model');
        $this->load->model('report_model');

        $this->load->library('email');
        $this->load->helper('string');
        //ini_set('display_errors', 'On');
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

    function tat_report() {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();

        $start_date = $this->input->get("start_date");
        $end_date = $this->input->get("end_date");
        $city = $this->input->get("city");
        $branch = $this->input->get("branch");
        if ($start_date == "") {
            $start_date = date('Y-m-d');
        }
        if ($end_date == "") {
            $end_date = date('Y-m-d');
        }

        $temp = "";
        if ($city != "") {
            $temp .= "AND j.test_city = '$city'";
        }

        if ($branch != "") {
            $temp .= "AND j.branch_fk = '$branch'";
        }
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['branch'] = $branch;
        $data['city'] = $city;

        //$this->db->where("STR_TO_DATE(j.date,'%Y-%m-%d') >=", date("Y-m-d", strtotime($start_date)));
        //INNER JOIN `job_log` jl ON jl.`job_fk`=j.id AND jl.status='1' AND job_status = '6-7' 
//            (SELECT jl.date_time FROM job_log jl WHERE jl.`job_fk`=j.id AND jl.status='1' AND job_status = '6-7' order by id DESC limit 1) 
//        FROM `job_master` j 
        //IF(j.date < 2018-05-17 00:00:00,tb.updated_date,jl.date_time) As date_time
//        $q = "SELECT distinct j.id,j.order_id,tm.test_name,tm.reporting,ajt.created_date,tb.updated_date,jl.date_time  
//            FROM `job_master` j 
//        INNER JOIN `approve_job_test` ajt ON j.id=ajt.job_fk AND ajt.status='1' 
//	INNER JOIN `test_master` tm ON tm.`id`=ajt.test_fk AND tm.status='1' 
//        LEFT JOIN `test_sample_barcode` tb ON (j.`id`=tb.job_fk AND tm.id=tb.test_fk) AND tb.status='1' 
//        INNER JOIN `job_log` jl ON jl.`job_fk`=j.id AND jl.status='1' AND job_status = '6-7' 
//        WHERE 1=1 
//        AND j.date >= '$start_date 00:00:00' 
//        AND j.date <= '$end_date 23:59:59' $temp";



        $q = "SELECT distinct j.id,j.order_id,tm.test_name,tm.reporting,ajt.created_date,
            IF(j.date < '2018-05-17 00:00:00',jl.date_time,IF(tb.updated_date,tb.updated_date,jl.date_time)) As date_time
            FROM `job_master` j 
        INNER JOIN `approve_job_test` ajt ON j.id=ajt.job_fk AND ajt.status='1' 
	INNER JOIN `test_master` tm ON tm.`id`=ajt.test_fk AND tm.status='1' 
        LEFT JOIN `test_sample_barcode` tb ON (j.`id`=tb.job_fk AND tm.id=tb.test_fk) AND tb.status='1' 
        INNER JOIN `job_log` jl ON jl.`job_fk`=j.id AND jl.status='1' AND job_status = '6-7' 
        WHERE 1=1 
        AND j.date >= '$start_date 00:00:00' 
        AND j.date <= '$end_date 23:59:59' $temp order by jl.date_time DESC";

        $data['query'] = $this->user_model->get_val($q);

//        echo "<pre>";
//        print_r($data['query']);
//        exit;

        if ($city != "") {
            $data['branch_list'] = $this->user_model->get_val("select id,branch_name from branch_master where city='$city' AND status='1'");
        }

        $data['city_list'] = $this->user_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('TAT_report', $data);
        $this->load->view('footer');
    }

    function getbranch() {
        $id = $this->input->post('city');
        $doctor = $this->user_model->get_val("select id,branch_name from branch_master where city = '$id' AND status = '1'");
        echo json_encode($doctor);
    }

    function export_csv_tat() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();

        $start_date = $this->input->get("start_date");
        $end_date = $this->input->get("end_date");
        $city = $this->input->get("city");
        $branch = $this->input->get("branch");
        if ($start_date == "") {
            $start_date = date('Y-m-d');
        }
        if ($end_date == "") {
            $end_date = date('Y-m-d');
        }

        $temp = "";
        if ($city != "") {
            $temp .= "AND j.test_city = '$city'";
        }

        if ($branch != "") {
            $temp .= "AND j.branch_fk = '$branch'";
        }
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['branch'] = $branch;
        $data['city'] = $city;


//        $q = "SELECT j.id,j.order_id,tm.test_name,tm.reporting,ajt.created_date,tb.updated_date,jl.date_time
//        FROM `job_master` j 
//        INNER JOIN `approve_job_test` ajt ON j.id=ajt.job_fk AND ajt.status='1' 
//	INNER JOIN `test_master` tm ON tm.`id`=ajt.test_fk AND tm.status='1' 
//        LEFT JOIN `test_sample_barcode` tb ON (j.`id`=tb.job_fk AND tm.id=tb.test_fk) AND tb.status='1' 
//        INNER JOIN `job_log` jl ON jl.`job_fk`=j.id AND jl.status='1' 
//        WHERE 1=1 
//        AND j.date >= '$start_date 00:00:00'  
//        AND j.date <= '$end_date 23:59:59' $temp";
//        $q = "SELECT j.id,j.order_id,tm.test_name,tm.reporting,ajt.created_date,tb.updated_date,
//            (select jl.date_time from job_log jl INNER JOIN job_master j on j.id = jl.job_fk WHERE jl.job_status='6-7' AND  jl.status = '1' order by jl.id DESC limit 1) 
//            As date_time 
//            FROM `job_master` j 
//        INNER JOIN `approve_job_test` ajt ON j.id=ajt.job_fk AND ajt.status='1' 
//	INNER JOIN `test_master` tm ON tm.`id`=ajt.test_fk AND tm.status='1' 
//        LEFT JOIN `test_sample_barcode` tb ON (j.`id`=tb.job_fk AND tm.id=tb.test_fk) AND tb.status='1' 
//        WHERE 1=1 
//        AND j.date >= '$start_date 00:00:00' 
//        AND j.date <= '$end_date 23:59:59' $temp";
//        $q = "SELECT distinct j.id,j.order_id,tm.test_name,tm.reporting,ajt.created_date,tb.updated_date,jl.date_time
//            FROM `job_master` j 
//        INNER JOIN `approve_job_test` ajt ON j.id=ajt.job_fk AND ajt.status='1' 
//	INNER JOIN `test_master` tm ON tm.`id`=ajt.test_fk AND tm.status='1' 
//        LEFT JOIN `test_sample_barcode` tb ON (j.`id`=tb.job_fk AND tm.id=tb.test_fk) AND tb.status='1' 
//        INNER JOIN `job_log` jl ON jl.`job_fk`=j.id AND jl.status='1' AND job_status = '6-7' 
//        WHERE 1=1 
//        AND j.date >= '$start_date 00:00:00' 
//        AND j.date <= '$end_date 23:59:59' $temp";



        $q = "SELECT distinct j.id,j.order_id,tm.test_name,tm.reporting,ajt.created_date, 
            IF(j.date < '2018-05-17 00:00:00',jl.date_time,IF(tb.updated_date,tb.updated_date,jl.date_time)) As date_time 
            FROM `job_master` j 
        INNER JOIN `approve_job_test` ajt ON j.id=ajt.job_fk AND ajt.status='1' 
	INNER JOIN `test_master` tm ON tm.`id`=ajt.test_fk AND tm.status='1' 
        LEFT JOIN `test_sample_barcode` tb ON (j.`id`=tb.job_fk AND tm.id=tb.test_fk) AND tb.status='1' 
        INNER JOIN `job_log` jl ON jl.`job_fk`=j.id AND jl.status='1' AND job_status = '6-7' 
        WHERE 1=1 
        AND j.date >= '$start_date 00:00:00' 
        AND j.date <= '$end_date 23:59:59' $temp order by jl.date_time DESC";



        $data['query'] = $this->user_model->get_val($q);


        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"TAT_report.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("Sr.No", "Booking ID", "Test Name", "TAT", "Perform Time", "In Range"));

        $count = 1;
        foreach ($data['query'] as $q) {
            $perform_time = "";
            $in_range = "";

//            if ($q['updated_date'] != "") {
//                $start_date = new DateTime($q['updated_date']);
//            } else {
//                $start_date = new DateTime($q['date_time']);
//            }
            
            $start_date = new DateTime($q['date_time']);
            $since_start = $start_date->diff(new DateTime($q['created_date']));
            $totaltesthorse = $since_start->h;
            $perform_time = $totaltesthorse . " HRS " . $since_start->i . " MINUTES";




            $reporting = $q["reporting"];
            if ($totaltesthorse > $reporting) {
                $in_range = "No";
            } else if ($totaltesthorse == $reporting) {
                if ($since_start->i >= 1) {
                    $in_range = "No";
                } else {
                    $in_range = "Yes";
                }
            } else if ($totaltesthorse < $reporting) {
                $in_range = "Yes";
            }




            fputcsv($handle, array($count, $q["order_id"], $q["test_name"], $q["reporting"] . " HRS", $perform_time, $in_range));
            $count++;
        }

        fclose($handle);
        exit;
    }

}

?>