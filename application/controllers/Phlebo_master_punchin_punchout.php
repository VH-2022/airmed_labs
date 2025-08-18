<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Phlebo_master_punchin_punchout extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('Phlebo_master_punchin_punchout_model');
        $this->load->model('registration_admin_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('session');
        $data["login_data"] = logindata();
        if (!logindata()) {
            redirect("/login");
        }
    }

    public function index() {
        $data["login_data"] = logindata();
        $data['phlebo'] = $pid = $this->input->get('phlebo_name');
        $cfname = $this->input->get('cfname');
        $date = $this->input->get('date');
        $end_date = $this->input->get('end_date');
        $data['cfname'] = $cfname;
        $data['date'] = $date;
        $data['end_date'] = $end_date;
        if ($cfname != "" || $date != '' || $end_date != '' || $pid != '') {
            if ($date != "") {
                $new_date = explode("/", $date);
                $new_date = $new_date["2"] . "-" . $new_date["1"] . "-" . $new_date["0"];
            } else {
                $new_date = "";
            }

            if ($end_date != "") {
                $new_date1 = explode("/", $end_date);
                $new_date1 = $new_date1["2"] . "-" . $new_date1["1"] . "-" . $new_date1["0"];
            } else {
                $new_date1 = "";
            }


            $totalRows = $this->Phlebo_master_punchin_punchout_model->num_row($cfname, $new_date, $new_date1, $pid);
            //echo $totalRows;die();
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "Phlebo_master_punchin_punchout/index?" . http_build_query($get);
            $config["total_rows"] = count($totalRows);
            $config["per_page"] = 100;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Phlebo_master_punchin_punchout_model->search($cfname, $new_date, $new_date1, $pid, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        } else {
            $totalRows = $this->Phlebo_master_punchin_punchout_model->num_row($cfname, $date, "", "");
            $config = array();
            $get = $_GET;
            $config["base_url"] = base_url() . "Phlebo_master_punchin_punchout/index";
            $config["total_rows"] = count($totalRows);
            $config["per_page"] = 100;
            $config['page_query_string'] = TRUE;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Phlebo_master_punchin_punchout_model->search($cfname, $date, "", "", $config["per_page"], $page);
            //echo "<pre>"; print_r($data['query']); exit;
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        }
        $data["phlebo_list"] = $this->user_model->master_fun_get_tbl_val("phlebo_master", array("status" => "1"), array("id", "asc"));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('phlebo_master_punchin_list', $data);
        $this->load->view('footer');
    }

    public function details() {
        $data["login_data"] = logindata();
        $ccid = $this->uri->segment('3');

        $data['view_data'] = $this->Phlebo_master_punchin_punchout_model->get_details($ccid);
        $qry = "SELECT 
  `phlebo_checkin`.*,
  `job_master`.`cust_fk`,
  `job_master`.`booking_info`,
  `job_master`.`order_id`,
  job_master.id as jid,
  customer_master.`full_name` 
FROM
  `phlebo_checkin` 
  INNER JOIN `job_master` 
    ON `phlebo_checkin`.`job_fk` = `job_master`.`id` 
  INNER JOIN `customer_master` 
    ON `customer_master`.`id` = `job_master`.`cust_fk`  
WHERE `phlebo_checkin`.`status` = '1' 
  AND `phlebo_checkin`.`checkin_time` > '" . $data['view_data'][0]["start_date"] . " 00:00:00' 
  AND `phlebo_checkin`.`checkin_time` < '" . $data['view_data'][0]["start_date"] . " 23:59:59' AND `phlebo_checkin`.`phlebo_fk`='" . $data['view_data'][0]["user_fk"] . "'";
        $data['checkin_data'] = $this->Phlebo_master_punchin_punchout_model->get_val($qry);
        //print_r($data['checkin_data']); die();
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('phlebo_master_punchin_details', $data, FALSE);
        $this->load->view('footer');
    }

    /* function export_csv() {
      $cfname = $this->input->get('cfname');
      $date = $this->input->get('date');
      $end_date = $this->input->get('end_date');
      $new_date = explode("/", $date);
      $new_date = $new_date["2"] . "-" . $new_date["1"] . "-" . $new_date["0"];
      $new_date1 = explode("/", $end_date);
      $new_date1 = $new_date1["2"] . "-" . $new_date1["1"] . "-" . $new_date1["0"];
      $result = $this->Phlebo_master_punchin_punchout_model->csv_search($cfname, $new_date, $new_date1);
      //echo "<pre>"; print_r($result); die();
      header("Content-type: application/csv");
      header("Content-Disposition: attachment; filename=\"Phlebo_Punch_In/Out_Report-" . date('d-M-Y') . ".csv\"");
      header("Pragma: no-cache");
      header("Expires: 0");
      $handle = fopen('php://output', 'w');
      fputcsv($handle, array(
      "Reg No.",
      "Phlebo Name",
      "Punch In Date",
      "Punch In Time",
      "Punch Out Date",
      "Punch Out Time",
      "Address",
      "Travel Distance"
      ));

      foreach ($result as $key) {
      fputcsv($handle, array(
      $key["id"],
      $key["name"],
      $key["start_date"],
      $key["start_time"],
      $key["stop_date"],
      $key["stop_time"],
      $key["address"],
      $key["distance"]
      ));
      }
      fclose($handle);
      exit;
      } */

    function export_csv() {
        $cfname = $this->input->get('cfname');
        $date = $this->input->get('date');
        $end_date = $this->input->get('end_date');
        $pid = $this->input->get('phlebo_name');
        if ($cfname != "" || $date != "" || $end_date != "" || $pid != "") {
            $new_date = "";
            $new_date1 = "";
            if ($date != "") {
                $new_date = explode("/", $date);
                $new_date = $new_date["2"] . "-" . $new_date["1"] . "-" . $new_date["0"];
            }
            if ($end_date != "") {
                $new_date1 = explode("/", $end_date);
                $new_date1 = $new_date1["2"] . "-" . $new_date1["1"] . "-" . $new_date1["0"];
            }

            $result = $this->Phlebo_master_punchin_punchout_model->csv_search($cfname, $new_date, $new_date1, $pid);
        } else {
            $result = $this->Phlebo_master_punchin_punchout_model->csv_search($cfname, "", "", "");
        }


//        $new_ary = array();
//        $user = array();
//        $user_date = array();
//        foreach ($result as $key12) {
//            if ((in_array($key12['start_date'], $user_date))) {
//                if (!in_array($key12["pid"], $user) && !empty($key12['start_date'])) {
//                    
//                    $dta = $this->Phlebo_master_punchin_punchout_model->get_val("SELECT * FROM `phlabo_timer` WHERE start_date='" . $key12['start_date'] . "' AND user_fk='" . $key12['pid'] . "' order by start_time asc");
//                    $dta2 = $this->Phlebo_master_punchin_punchout_model->get_val("SELECT * FROM `phlabo_timer` WHERE start_date='" . $key12['start_date'] . "' AND user_fk='" . $key12['pid'] . "' order by stop_time desc");
////print_r($dta); die();
//                    $cnt = count($dta);
//                    $key12["id"] = $dta[0]['id'];
//                    $key12["name"] = $key12['name'];
//                    $key12["start_date"] = $dta[0]['start_date'];
//                    $key12["start_time"] = $dta[0]['start_time'];
//                    $key12["stop_date"] = $dta2[0]['stop_date'];
//                    $key12["stop_time"] = $dta2[0]['stop_time'];
//                    $key12["address"] = $dta[0]['address'];
//                    $key12["distance"] = $dta[0]['distance'];
//
//                    $user_date[] = $key12['start_date'];
//                    $user[] = $key12['pid'];
//                    $new_ary[] = $key12;
//                }
//            } else {
//                $user = array();
//                $dta = $this->Phlebo_master_punchin_punchout_model->get_val("SELECT * FROM `phlabo_timer` WHERE start_date='" . $key12['start_date'] . "' AND user_fk='" . $key12['pid'] . "'");
//                //print_r($dta); die();
//                $cnt = count($dta);
//                $key12["id"] = $dta[0]['id'];
//                $key12["name"] = $key12['name'];
//                $key12["start_date"] = $dta[0]['start_date'];
//                $key12["start_time"] = $dta[0]['start_time'];
//                $key12["stop_date"] = $dta[$cnt - 1]['stop_date'];
//                $key12["stop_time"] = $dta[$cnt - 1]['stop_time'];
//                $key12["address"] = $dta[0]['address'];
//                $key12["distance"] = $dta[0]['distance'];
//
//                $user_date[] = $key12['start_date'];
//                $user[] = $key12['pid'];
//                $new_ary[] = $key12;
//            }
//        }
//         echo "<pre>"; print_r($result); exit;


        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Phlebo_Punch_In/Out_Report-" . date('d-M-Y') . ".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');

        fputcsv($handle, array(
            "Reg No.",
            "Phlebo Name",
            "Punch In Date",
            "Punch In Time",
            "Punch Out Date",
            "Punch Out Time",
            "Distance",
            "Total Working Hour"
        ));

        foreach ($result as $key) {

            $total_time = "";
            $time = $key['Total_working_hour'];

            $value1 = $time;
            $arr1 = explode(':', $value1);
            $totalMinutes = (int) $arr1[0] * 60 + (int) $arr1[1] + (int) $arr2[0] * 60 + (int) $arr2[1];
            $hours = (int) ($totalMinutes / 60);
            $minutes = $totalMinutes % 60;
            $total_time = $hours . ' Hour ' . $minutes . ' Minutes';

            fputcsv($handle, array(
                $key["id"],
                $key["name"],
                $key["start_date"],
                $key["start_time_new"],
                $key["stop_date"],
                $key["stop_time_new"],
                ($key['in_riding'] != "" && $key['out_riding'] != "") ?
                        $key['out_riding'] - $key['in_riding'] : "",
                $total_time
            ));
        }
        fclose($handle);
        exit;
    }

}

?>