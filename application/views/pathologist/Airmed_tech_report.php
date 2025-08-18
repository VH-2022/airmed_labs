<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Airmed_tech_report extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Airmed_tech_report_model');
        $this->load->model('user_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->helper('string');
        $data["login_data"] = logindata();
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    /* New Job List Start */

    function index() {

        if (!is_loggedin()) {
            redirect('login');
        }

        $search_data = array();

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $user_assign_branch = array();
        if (in_array($data["login_data"]["type"], array("1", "2"))) {
            $user_assign_branch = $this->Airmed_tech_report_model->get_val("select id,branch_name from branch_master where status='1' and branch_type_fk='6'");
        } else {
            $get_user_branch = $this->Airmed_tech_report_model->get_val("SELECT GROUP_CONCAT(`branch_fk`) AS bid FROM `user_branch` WHERE `user_fk`='" . $data["login_data"]["id"] . "' AND `status`='1' GROUP BY user_fk");
            if (!empty($get_user_branch)) {
                $user_assign_branch = $this->Airmed_tech_report_model->get_val("select id,branch_name from branch_master where status='1' and id in (" . $get_user_branch[0]["bid"] . ") and branch_type_fk='6'");
            }
        }
        $data["user_assign_branch"] = $user_assign_branch;
        $data["bid"] = $this->input->get("bid");
        if (!empty($data["bid"])) {
            $data["get_branch_report"] = $this->Airmed_tech_report_model->get_val("SELECT 
  ROUND(SUM((`job_master`.price))) AS total_revenue,
  ROUND(
    SUM(
      `job_master`.price * `job_master`.`discount` / 100
    )
  ) AS discount,
  (
    ROUND(SUM((`job_master`.price))) - ROUND(
      SUM(
        `job_master`.price * `job_master`.`discount` / 100
      )
    )
  ) AS net_price,
  DATE_FORMAT(`job_master`.`date`, '%M-%Y') AS `date`,
  DATE_FORMAT(`job_master`.`date`, '%Y-%m') AS `month`,
  `job_master`.`branch_fk`,
   branch_master.`branch_name`
FROM
  job_master 
  INNER JOIN `branch_master` ON `branch_master`.`id` = `job_master`.`branch_fk`
WHERE job_master.`status` != '0' 
  AND `job_master`.`branch_fk` IN (" . $data["bid"] . ") 
  AND `job_master`.`model_type` = '1' 
GROUP BY DATE_FORMAT(`job_master`.`date`, '%M-%Y'),`job_master`.`branch_fk` 
ORDER BY `job_master`.`branch_fk`,job_master.date DESC ");
        }
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('airmed_tech_report', $data);
        $this->load->view('footer');
    }

    function export_csv() {
        $data["bid"] = $this->input->get("bid");
        if (!empty($data["bid"])) {
            $data["get_branch_report"] = $this->Airmed_tech_report_model->get_val("SELECT 
  ROUND(SUM((`job_master`.price))) AS total_revenue,
  ROUND(
    SUM(
      `job_master`.price * `job_master`.`discount` / 100
    )
  ) AS discount,
  (
    ROUND(SUM((`job_master`.price))) - ROUND(
      SUM(
        `job_master`.price * `job_master`.`discount` / 100
      )
    )
  ) AS net_price,
  DATE_FORMAT(`job_master`.`date`, '%M-%Y') AS `date`,
  DATE_FORMAT(`job_master`.`date`, '%Y-%m') AS `month`,
  `job_master`.`branch_fk`,
   branch_master.`branch_name`
FROM
  job_master 
  INNER JOIN `branch_master` ON `branch_master`.`id` = `job_master`.`branch_fk`
WHERE job_master.`status` != '0' 
  AND `job_master`.`branch_fk` IN (" . $data["bid"] . ") 
  AND `job_master`.`model_type` = '1' 
GROUP BY DATE_FORMAT(`job_master`.`date`, '%M-%Y'),`job_master`.`branch_fk` 
ORDER BY `job_master`.`branch_fk`,job_master.date DESC ");

            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"Airmed_".$data["get_branch_report"][0]["branch_name"]."_" . $data["get_branch_report"][0]["date"] . "_Report.csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle = fopen('php://output', 'w');
            fputcsv($handle, array("No.", "Center", "Month", "Total Revenue", "Discount", "Net Price", "Paid to Airmed"));
            $cnt = 1;
            foreach ($data["get_branch_report"] as $key) {

                fputcsv($handle, array($cnt, $key["branch_name"], $key["date"], $key["total_revenue"], $key["discount"], $key["net_price"], 0));
                $cnt++;
            }
            fclose($handle);
            exit;
        }
    }

    function month_report() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);


        $bid = $data["bid"] = $this->input->get("bid");
        $month = $data["month"] = $this->input->get("month");
        iF (!empty($bid) && !empty($month)) {
            $data["report_data"] = $this->Airmed_tech_report_model->get_val("SELECT 
  `job_master`.id,
  `job_master`.`order_id`,
  IF(`booking_info`.`family_member_fk`>0,customer_family_master.`name`,customer_master.`full_name`) AS patient,
  `job_master`.`date`,
  `job_master`.`price`,
  `job_master`.`discount`,
  `job_master`.`payable_amount` 
FROM
  job_master 
  LEFT JOIN `booking_info` ON `job_master`.`booking_info`=`booking_info`.`id`
  LEFT JOIN `customer_master` ON `job_master`.`cust_fk`=`customer_master`.id
  LEFT JOIN `customer_family_master` ON `customer_family_master`.`id`=`booking_info`.`family_member_fk`
WHERE `job_master`.`branch_fk` = '" . $bid . "' 
  AND `job_master`.`date` LIKE '%" . $month . "%' 
  AND `job_master`.`status` != '0' and `job_master`.`model_type`='1'");
        }
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('airmed_tech_month_report', $data);
        $this->load->view('footer');
    }
function export_csv_branch() {
        $data["bid"] = $this->input->get("bid");
        $bid = $data["bid"] = $this->input->get("bid");
        $month = $data["month"] = $this->input->get("month");
        iF (!empty($bid) && !empty($month)) {
            $data["report_data"] = $this->Airmed_tech_report_model->get_val("SELECT 
  `job_master`.id,
  `job_master`.`order_id`,
  IF(`booking_info`.`family_member_fk`>0,customer_family_master.`name`,customer_master.`full_name`) AS patient,
  `job_master`.`date`,
  `job_master`.`price`,
  `job_master`.`discount`,
  `job_master`.`payable_amount`,
  branch_master.branch_name
FROM
  job_master 
  LEFT JOIN `booking_info` ON `job_master`.`booking_info`=`booking_info`.`id`
  LEFT JOIN `customer_master` ON `job_master`.`cust_fk`=`customer_master`.id
  LEFT JOIN `customer_family_master` ON `customer_family_master`.`id`=`booking_info`.`family_member_fk`
  left join branch_master on branch_master.id=job_master.branch_fk
WHERE `job_master`.`branch_fk` = '" . $bid . "' 
  AND `job_master`.`date` LIKE '%" . $month . "%' 
  AND `job_master`.`status` != '0' and `job_master`.`model_type`='1'");

            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"Airmed_".$data["report_data"][0]["branch_name"]."_" . date("M-Y", strtotime($data["report_data"][0]["date"])) . "_Booking_Report.csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle = fopen('php://output', 'w');
            fputcsv($handle, array("No.", "Ref. No.", "Order ID", "Patient Name", "Date", "Price", "Discount","Due Amount"));
            $cnt = 1;
            foreach ($data["report_data"] as $key) {
                $discount = ($row['discount']>0)?($row['price'] - (($row['price']*$row['discount'])/100)):0;
                fputcsv($handle, array($cnt, $key["id"], $key["order_id"], $key["patient"], $key["date"], $key["price"], $discount,$key["payable_amount"]));
                $cnt++;
            }
            fclose($handle);
            exit;
        }
    }
}

?>