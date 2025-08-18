<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Phlebo_report extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('phlebo_model');
        $this->load->model('phlebo_report_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        //echo current_url(); die();
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');
        $data["login_data"] = logindata();
    }

    function Test() {
        echo "Hii";
    }

    function phlebo_report() {

        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['start_date'] = $this->input->get('start_date');
        $data['end_date'] = $this->input->get('end_date');
        $data['city'] = $this->input->get('city');
        $data['phlebo_name'] = $this->input->get('phlebo_name');
        $data['sample'] = $this->input->get('sample_collect');

        $start_date = null;
        $end_date = null;
        if ($data['start_date'] != '') {
            $start_date = $data['start_date'];
        }

        if ($data['end_date'] != '') {
            $end_date = $data['end_date'];
        }

        if ($data['city'] != '') {
            $city = $data['city'];
        }

        if ($data['phlebo_name'] != '') {
            $phlebo_name = $data['phlebo_name'];
        }


        if ($data['sample'] != '') {
            $sample = $data['sample'];
        }
        $data['view_report'] = $this->phlebo_report_model->report($start_date, $end_date, $city, $phlebo_name, $sample);
        //$data['new_report'] = $this->phlebo_report_model->job_report($job_start_date, $job_end_date, $phlebo_name,$sample);

        $data['test_city'] = $this->phlebo_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
        $data['sample_collect'] = $this->phlebo_model->master_fun_get_tbl_val("sample_from", array("status" => '1'), array("name", "asc"));

        $cid = $this->input->get('city');
        if (!empty($cid)) {
            $data['phlebo_list'] = $this->phlebo_report_model->get_val("select pm.name as PhleboName,pm.id as PID,pm.test_city,tc.id from phlebo_master as pm left join test_cities as tc on pm.test_city = tc.id where pm.test_city = '$cid'");
        } else {
            $data['phlebo_list'] = $this->phlebo_report_model->get_val("select pm.name as PhleboName,pm.id as PID,pm.test_city,tc.id from phlebo_master as pm left join test_cities as tc on pm.test_city = tc.id");
        }
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('phlebo_report', $data);
        $this->load->view('footer');
    }

    function getPhleboName() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $city = $this->input->post("city_id");
        $refer = '';
        $referral_list = $this->phlebo_report_model->get_val("Select ph.id,ph.name as PhleboName,tc.name from phlebo_master as ph left join test_cities as tc on tc.id = ph.test_city where ph.test_city='" . $city . "' AND ph.status = 1");
        $refer = '<option value = "">Select Phlebo</option>';
        foreach ($referral_list as $referral) {


            $refer .= '<option value="' . $referral['id'] . '"';
            if ($selected1[1] == $referral['id']) {
                $refer .= ' selected';
            }
            $refer .= '>' . ucwords($referral['PhleboName']) . '</option>';
        }

        if ($refer == '') {
            //echo "<option value=''>Data not available.</option>";
        }
        echo $refer;
    }

    function export() {
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $city = $this->input->get('city');
        $phlebo = $this->input->get('phlebo');
        $sm_coll = $this->input->get('sample_collect');

        if ($data['$start_date'] != '') {
            $job_start_date = $data['start_date'];
        }
        if ($data['end_date'] != '') {
            $job_end_date = $data['end_date'];
        }
        $result = $this->phlebo_report_model->csv_report($start_date, $end_date, $phlebo, $city, $sm_coll);
        if ($_REQUEST["debug"] == 1) {
            print_r($result);
            die();
        }
        //$result2 = $this->phlebo_report_model->sub_job_csv($job_start_date,$job_end_date,$phlebo,$sm_coll);
//        $result2 = $this->phlebo_report_model->get_val(
//                "SELECT jmra.*,jmra.id AS jid,phm.name AS PhleboName,phm.*, phm.test_city,paj.address,job.id,job.cust_fk,job.order_id,cu.full_name,sf.name as SampleName,do.full_name as DoctorName 
//    FROM
//        job_master_receiv_amount AS jmra 
//        LEFT JOIN phlebo_master AS phm ON 
//            jmra.phlebo_fk = phm.id 
//        LEFT JOIN job_master AS job ON job.id= jmra.job_fk 
//        LEFT JOIN customer_master AS cu 
//    ON cu.id = job.cust_fk 
//    LEFT JOIN phlebo_assign_job AS paj ON paj.job_fk = jmra.job_fk
//    LEFT JOIN doctor_master AS do ON do.id = job.doctor
//    LEFT JOIN sample_from AS sf ON sf.id = job.sample_from
//   
//     WHERE jmra.createddate >='" . $job_start_date . "' AND  jmra.createddate <='" . $job_end_date . "' AND phm.test_city ='$city' AND jmra.phlebo_fk='$phlebo' AND job.sample_from='$sm_coll' AND  jmra.status=1 AND jmra.phlebo_fk IS NOT NULL ORDER BY jmra.id ASC");


        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Phlebo Report .csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No", "Reg No", "Patient Name", "Doctor Name", "Phlebo Name", "Date", "Time", "Address", "Note", "Sample  Collect", "Status","Total Price"));

        foreach ($result as $val) {

            $old_date = $val['date'];
            $new_date = date('d-m-Y', strtotime($old_date));

            if ($val["start_time"] != '' && $val['end_time'] != '') {
                $new_time = $val["start_time"] . '-' . $val["end_time"];
            } else {
                $new_time = $val['time'];
            }



            $new_string = strtolower($val['address']);
            $new_address = ucwords($new_string);

            $old_first_name = strtolower($val["full_name"]);
            $new_first_name = ucwords($old_first_name);

            $old_phlebo_name = strtolower($val["PhleboName"]);
            $new_phlebo_name = ucwords($old_phlebo_name);
            $old_note = strtolower($val["note"]);
            $new_note = ucwords($old_note);
            if ($val['is_accept'] == 1) {
                $accept = "Complete";
            } else {
                $accept = "Pending";
            }

            if ($val['SampleName'] != '') {
                $sname = $val['SampleName'];
            } else {
                $sname = 'N/A';
            }

            if ($val['DoctorName'] != '') {
                $dname = $val['DoctorName'];
            } else {
                $dname = 'N/A';
            }
            fputcsv($handle, array($val["job_fk"], $val["order_id"], $new_first_name, $dname, $new_phlebo_name, $new_date, $new_time, $new_address, $new_note, $sname, $accept,$val["price"]));
        }
        foreach ($result2 as $phle) {

            $old_date = $phle['createddate'];
            $new_date = date('d-m-Y H:i:s', strtotime($old_date));
            if ($phle['address'] != '') {
                $address = $phle['address'];
            } else {
                $address = "N/A";
            }
            if ($phle['SampleName'] != '') {
                $sname = $phle['SampleName'];
            } else {
                $sname = 'N/A';
            }

            if ($phle['DoctorName'] != '') {
                $dname = $phle['DoctorName'];
            } else {
                $dname = 'N/A';
            }

            $accept = "Complete";

            $new_sub_string = strtolower($address);
            $sub_new_address = ucwords($new_sub_string);

            $old_full_name = strtolower($phle["full_name"]);
            $new_full_name = ucwords($old_full_name);

            $old_phlebo_name = strtolower($phle["PhleboName"]);
            $sub_new_phlebo_name = ucwords($old_phlebo_name);

            $old_note = strtolower($val["note"]);
            $new_note = ucwords($old_note);
            fputcsv($handle, array($phle["id"], $phle["order_id"], $new_full_name, $dname, $sub_new_phlebo_name, $new_date, $sub_new_address, $new_note, $sname, $accept));
        }
        fclose($handle);
        exit;
    }

}

?>