<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Phleboreport extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('job_model');
        $this->load->model('user_model');
        $this->load->model('report_model');

        $this->load->library('email');
        $this->load->helper('string');
        //ini_set('display_errors', 'On');
    }

    function index() {
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
        if ($city_id != "") {
            $temp .= " AND j.test_city = '$city_id'";
        }
        if ($branch_id != "") {
            $temp .= " AND j.branch_fk = '$branch_id'";
        }
        if ($start_date != "") {
            $temp .=" AND j.date >= '$start_date 00:00:00'";
        }
        if ($end_date != "") {
            $temp .=" AND j.date <= '$end_date 23:59:59'";
        }

    echo    $q = "SELECT j.id,j.branch_fk,j.order_id,j.date,j.price,j.collection_charge,bm.branch_name,tc.name As city_name,pm.name As phlebo_name , 
  am.`name` as user_added,
  pa.`name` as phlebo_added
                from job_master j 
                LEFT JOIN branch_master bm on j.branch_fk = bm.id AND bm.status ='1' 
                LEFT JOIN test_cities tc on j.test_city = tc.id AND tc.status ='1' 
                LEFT  JOIN phlebo_assign_job pj 
    ON j.id = pj.job_fk 
    AND pj.status = '1' 
  LEFT JOIN phlebo_master pm 
    ON pm.id = pj.phlebo_fk 
    AND pm.status = '1' 
    LEFT JOIN `admin_master` am
    ON am.id=j.`added_by`
LEFT JOIN phlebo_master pa 
    ON pa.id = j.`phlebo_added`
    AND pa.status = '1'  
                where 1=1 AND j.status !=0 AND j.collection_charge='1' $temp 
                ORDER BY j.id DESC";

        $data['query'] = $this->user_model->get_val($q);

        $data['city_list'] = $this->user_model->master_fun_get_tbl_val("test_cities", array('status' => 1), array("id", "asc"));
        if ($city_id != "") {
            $data['branch_list'] = $this->user_model->get_val("select id,branch_name from branch_master where status='1' AND city='$city_id'");
        }

        $this->load->view('header', $data);
        $this->load->view('nav', $data);
        $this->load->view('phleboreport_view', $data);
        $this->load->view('footer');
    }

    function export_csv() {
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
        if ($city_id != "") {
            $temp .= " AND j.test_city = '$city_id'";
        }
        if ($branch_id != "") {
            $temp .= " AND j.branch_fk = '$branch_id'";
        }
        if ($start_date != "") {
            $temp .=" AND j.date >= '$start_date 00:00:00'";
        }
        if ($end_date != "") {
            $temp .=" AND j.date <= '$end_date 23:59:59'";
        }

        $q = "SELECT j.id,j.branch_fk,j.order_id,j.date,j.price,j.collection_charge,bm.branch_name,tc.name As city_name,pm.name As phlebo_name ,
  am.`name` as user_added,
  pa.`name` as phlebo_added
                from job_master j 
                LEFT JOIN branch_master bm on j.branch_fk = bm.id AND bm.status ='1' 
                LEFT JOIN test_cities tc on j.test_city = tc.id AND tc.status ='1' 
                 LEFT  JOIN phlebo_assign_job pj 
    ON j.id = pj.job_fk 
    AND pj.status = '1' 
  LEFT JOIN phlebo_master pm 
    ON pm.id = pj.phlebo_fk 
    AND pm.status = '1' 
    LEFT JOIN `admin_master` am
    ON am.id=j.`added_by`
LEFT JOIN phlebo_master pa 
    ON pa.id = j.`phlebo_added`
    AND pa.status = '1'  
                where 1=1  AND j.status !=0 AND j.collection_charge='1' $temp 
                ORDER BY j.id DESC";

        $data['query'] = $this->user_model->get_val($q);


        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Phlebo_collection.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No.", "Reference ID", "Date", "City", "Branch", "Phlebo Name", "Price", "Collection Charge", "Added_By", "Phlebo Added"));

        $count = 0;
        foreach ($data['query'] as $row) {
            $count++;
            fputcsv($handle, array($count, $row['order_id'], $row['date'], $row['city_name'], $row['branch_name'], $row['phlebo_name'], $row['price'], 100,$row['user_added'],$row['phlebo_added']));
        }
        fclose($handle);
        exit;
    }

}

?>