<?php

Class Stock_issue_report_model extends CI_Model {

    function get_master_get_data($name, $condition, $order) {
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($name, $condition);
        return $query->result_array();
    }

    function master_update_data($name, $condition, $order) {
        //print_r($condition); die();
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($name, $condition);
        return $query->result_array();
    }

    public function master_fun_get_tbl_val($dtatabase, $condition, $order) {
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($dtatabase, $condition);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function master_fun_insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function master_fun_update($tablename, $cid, $data) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function master_fun_delete($tablename, $cid) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->delete($tablename);
        return 1;
    }

    public function master_fun_update_multi($tablename, $condition, $data) {
        $this->db->where($condition);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function master_num_rows($table, $condition) {
        $query1 = $this->db->get_where($table, $condition);
        return $query1->num_rows();
    }

    public function contact_master($table_name, $data) {

        $this->db->insert($table_name, $data);
        return $this->db->insert_id();
    }

    function collecting_amount_branch($center = null, $date = null) {
        $qry = "SELECT SUM(jmr.amount) AS SUM,jmr.`added_by` AS user_fk,am.`name`,jmr.`createddate`,b.branch_name FROM `job_master_receiv_amount` jmr LEFT JOIN `admin_received_amount` ON `admin_received_amount`.`admin_fk` = jmr.`added_by` INNER JOIN `admin_master` am ON jmr.added_by = am.`id` join user_branch ub on ub.user_fk=am.id join branch_master b on b.id=ub.branch_fk  WHERE added_by != '' AND jmr.`status` = '1' and ub.status = '1'";
        if (!empty($center)) {
            $qry .= " AND ub.branch_fk in (" . implode(",", $center) . ")";
        }
        if (!empty($date)) {
            $date1 = explode("/", $date);
            $date = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
            $start_date = $date . " 00:00:00";
            $end_date = $date . " 23:59:59";
            $qry .= " AND jmr.createddate >= '" . $start_date . "' AND jmr.createddate <= '" . $end_date . "'";
        } else {
            $start_date = date('Y-m-d') . " 00:00:00";
            $end_date = date('Y-m-d') . " 23:59:59";
            $qry .= " AND jmr.createddate >= '" . $start_date . "' AND jmr.createddate <= '" . $end_date . "'";
        }
        $qry .= "  GROUP BY jmr.`added_by`";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function getPaymentReport($center = null, $from = null, $to = null, $branch = null, $type = null) {
        $qry = "SELECT 
jm.id AS job_id,
jmr.payment_type as type,
jm.`order_id`,
  jmr.amount,
  jmr.`added_by` AS user_fk,
  jmr.`createddate`,
  b.branch_name ,
  IF(
    `jmr`.`phlebo_fk` > 0,
    CONCAT(phlebo_master.`name`,'','(Phlebo)'),`am`.`name`
  ) AS `name`
FROM
  `job_master_receiv_amount` jmr 
LEFT JOIN job_master jm 
    ON jm.id = jmr.`job_fk` 
  LEFT JOIN `admin_received_amount` 
    ON `admin_received_amount`.`admin_fk` = jmr.`added_by` 
  LEFT JOIN `admin_master` am 
    ON jmr.added_by = am.`id` 
  LEFT JOIN `phlebo_master` 
    ON `phlebo_master`.`id`=`jmr`.`phlebo_fk`
  LEFT JOIN `admin_master` u 
    ON u.id = jmr.`added_by` 
  LEFT JOIN branch_master b 
    ON b.id = jm.branch_fk 
    INNER JOIN `job_master`
    ON `job_master`.`id`=`jmr`.`job_fk` AND `job_master`.`status`!='0'
    WHERE  jmr.`status` = '1'";
        if (!empty($center)) {
            $qry .= " AND b.id in (" . implode(",", $center) . ")";
        }
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry .= " AND jmr.createddate >= '" . $start_date . "' AND jmr.createddate <= '" . $end_date . "'";
        if ($branch != "") {
            $qry .= " AND b.id  = '" . $branch . "' ";
        }
        if ($type != '') {
            $qry .= " AND jmr.payment_type  = '" . $type . "' ";
        }
        $qry .= "  order by jmr.`createddate`";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    /* END */

    function getMyPaymentReport($from = null, $to = null, $id = null, $type = null) {
        $qry = "SELECT 
  jm.id AS job_id,

  jm.`order_id`,
jm.`payable_amount`,
jm.`price`,
jm.`discount`,
  jm.`date`,
  b.branch_name,
  am.`name`
FROM
  job_master jm 
 
  
  LEFT JOIN `admin_master` am 
    ON jm.added_by = am.`id` 
  LEFT JOIN branch_master b 
    ON b.id = jm.branch_fk 
 
WHERE   jm.`status` != '0' and jm.added_by='" . $id . "'";

        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry .= " AND jm.date >= '" . $start_date . "' AND jm.date <= '" . $end_date . "'";

        $qry .= "  order by jm.`date`";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function getPaymentReport_type($center = null, $from = null, $to = null, $branch = null, $type = null, $id) {
        $qry = "SELECT 
jm.id AS job_id,
jmr.payment_type as type,
jm.`order_id`,
  jmr.amount,
  jmr.`added_by` AS user_fk,
  jmr.`createddate`,
  b.branch_name ,
  IF(
    `jmr`.`phlebo_fk` > 0,
    CONCAT(phlebo_master.`name`,'','(Phlebo)'),`am`.`name`
  ) AS `name`
FROM
  `job_master_receiv_amount` jmr 
LEFT JOIN job_master jm 
    ON jm.id = jmr.`job_fk` 
  LEFT JOIN `admin_received_amount` 
    ON `admin_received_amount`.`admin_fk` = jmr.`added_by` 
  LEFT JOIN `admin_master` am 
    ON jmr.added_by = am.`id` 
  LEFT JOIN `phlebo_master` 
    ON `phlebo_master`.`id`=`jmr`.`phlebo_fk`
  LEFT JOIN `admin_master` u 
    ON u.id = jmr.`added_by` 
  LEFT JOIN branch_master b 
    ON b.id = jm.branch_fk 
    INNER JOIN `job_master`
    ON `job_master`.`id`=`jmr`.`job_fk` AND `job_master`.`status`!='0'
    WHERE  jmr.`status` = '1' and jmr.added_by='$id' ";
        if (!empty($center)) {
            $qry .= " AND b.id in (" . implode(",", $center) . ")";
        }
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry .= " AND jmr.createddate >= '" . $start_date . "' AND jmr.createddate <= '" . $end_date . "'";
        if ($branch != "") {
            $qry .= " AND b.id  = '" . $branch . "' ";
        }
        if ($type != '') {
            $qry .= " AND jmr.payment_type  = '" . $type . "' ";
        }
        $qry .= "  order by jmr.`createddate`";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function getPaymentReport_details($center = null, $from = null, $to = null, $branch = null, $type = null, $id) {
        $qry = "SELECT 
jm.id AS job_id,
jmr.payment_type as type,
jm.`order_id`,
  jmr.amount,
  jmr.`added_by` AS user_fk,
  jmr.`createddate`,
  b.branch_name ,
  IF(
    `jmr`.`phlebo_fk` > 0,
    CONCAT(phlebo_master.`name`,'','(Phlebo)'),`am`.`name`
  ) AS `name`
FROM
  `job_master_receiv_amount` jmr 
LEFT JOIN job_master jm 
    ON jm.id = jmr.`job_fk` 
  LEFT JOIN `admin_received_amount` 
    ON `admin_received_amount`.`admin_fk` = jmr.`added_by` 
  LEFT JOIN `admin_master` am 
    ON jmr.added_by = am.`id` 
  LEFT JOIN `phlebo_master` 
    ON `phlebo_master`.`id`=`jmr`.`phlebo_fk`
  LEFT JOIN `admin_master` u 
    ON u.id = jmr.`added_by` 
  LEFT JOIN branch_master b 
    ON b.id = jm.branch_fk 
    INNER JOIN `job_master`
    ON `job_master`.`id`=`jmr`.`job_fk` AND `job_master`.`status`!='0'
    WHERE  jmr.`status` = '1' and jmr.added_by='$id' ";
        if (!empty($center)) {
            $qry .= " AND b.id in (" . implode(",", $center) . ")";
        }
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry .= " AND jmr.createddate >= '" . $start_date . "' AND jmr.createddate <= '" . $end_date . "'";
        if ($branch != "") {
            //$qry .= " AND b.id  = '" . $branch . "' ";
        }
        if ($type != '') {
            $qry .= " AND jmr.payment_type  = '" . $type . "' ";
        }
        $qry .= "  order by jmr.`createddate`";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function jobPaymentReport($from = null, $to = null, $id = null, $type = null, $branch = null) {
        $qry = "SELECT 
  jmr.payment_type,
  b.branch_name,
  am.`name`,
  am.id as aid,
  SUM(jmr.amount) as price,
  b.id as bid
FROM
  job_master jm 
JOIN `job_master_receiv_amount` jmr 
    ON jm.id = jmr.`job_fk`
  JOIN `admin_master` am 
    ON jmr.added_by = am.`id` 
  JOIN branch_master b 
    ON b.id = jm.branch_fk 
 
WHERE   jm.`model_type`=1 and 	 jm.`status` != '0' and jmr.status = '1' ";

        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry .= " AND jmr.createddate >= '" . $start_date . "' AND jmr.createddate <= '" . $end_date . "'";
        if ($branch != "") {
            $qry .= " AND b.id  = '" . $branch . "' ";
        }
        if ($type != '') {
            $qry .= " AND jmr.payment_type  = '" . $type . "' ";
        }

        $qry .= " group by b.id,jmr.added_by,jmr.payment_type order by b.`id`,am.`id` ASC";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function branchPaymentReport($from = null, $to = null, $branch = null) {
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $bd = date('Y-m-d', strtotime($sd . ' -1 day'));
        $back_date = $bd . " 00:00:00";
        $back_date1 = $bd . " 23:59:59";
        $qry = "select
            bm.branch_name,
                tc.name,
                tc.id as tid,
                SUM(jm.`price`) as price,
                SUM(jm.payable_amount) as due,
          SUM((jm.`discount` * jm.`price`) / 100) as discount
                from job_master jm left join branch_master bm on bm.id=jm.branch_fk left join test_cities tc on tc.id=bm.city where jm.status != '0' ";
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry .= " AND jm.date >= '" . $start_date . "' AND jm.date <= '" . $end_date . "'";

        if ($branch != "") {
            $qry .= " AND jm.branch_fk = '" . $branch . "' ";
        }
        $qry .= " group by tc.id,jm.branch_fk order by tc.`id`,bm.`id` ASC";
        if ($_REQUEST["debug"] == 1) {
            echo $qry;
            die();
        }
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function doctorPaymentdetails($from = null, $to = null, $doctor = null) {
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $bd = date('Y-m-d', strtotime($sd . ' -1 day'));
        $back_date = $bd . " 00:00:00";
        $back_date1 = $bd . " 23:59:59";
        $qry = "select
                dm.full_name,
                dm.mobile,
                dm.cut,
                dm.id as did,
                tc.name,
                tc.id as tid,
                jm.`price`,
                jm.payable_amount as due,
                ((jm.`discount` * jm.`price`) / 100) as discount
                from job_master jm left join doctor_master dm on dm.id=jm.doctor left join test_cities tc on tc.id=jm.test_city where jm.status != '0' ";
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry .= " AND jm.date >= '" . $start_date . "' AND jm.date <= '" . $end_date . "'";
        if ($doctor != "") {
            $qry .= " AND jm.doctor = '" . $doctor . "' ";
        }
        $qry .= " order by tc.`id`,dm.`id` ASC";
        if ($_REQUEST["debug"] == 1) {
            echo $qry;
            die();
        }
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function branchPaymentReportdetails($from = null, $to = null, $branch = null, $city = null) {
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $bd = date('Y-m-d', strtotime($sd . ' -1 day'));
        $back_date = $bd . " 00:00:00";
        $back_date1 = $bd . " 23:59:59";
        $qry = "select
            bm.branch_name,
                tc.name,
                tc.id as tid,
                SUM(jm.`price`) as price,
                SUM(jm.payable_amount) as due,
          SUM((jm.`discount` * jm.`price`) / 100) as discount,
          (SELECT SUM(c.amount) from job_master_receiv_amount c left join job_master jci on jci.id=c.job_fk where jci.test_city='$city' and jci.branch_fk=bm.id and c.payment_type='CASH' AND c.createddate >= '" . $start_date . "' AND c.status='1' AND c.createddate <= '" . $end_date . "') as cash_total,
              (SELECT SUM(c.amount) from job_master_receiv_amount c left join job_master jci on jci.id=c.job_fk where jci.test_city='$city' and jci.branch_fk=bm.id and c.payment_type='CHEQUE' AND c.createddate >= '" . $start_date . "' AND c.status='1' AND c.createddate <= '" . $end_date . "') as cheque_total,
                  (SELECT SUM(c.debit) from wallet_master c left join job_master jci on jci.id=c.job_fk where jci.test_city='$city' and jci.branch_fk=bm.id AND c.created_time >= '" . $start_date . "' AND c.status='1' AND c.created_time <= '" . $end_date . "') as wallet_total,
                      (SELECT SUM(c.amount) from payment c left join job_master jci on jci.id=c.job_fk where jci.test_city='$city' and jci.branch_fk=bm.id AND c.paydate >= '" . $start_date . "' AND c.status='success' AND c.paydate <= '" . $end_date . "') as pau_total,
                      (SELECT SUM(c.amount) from job_master_receiv_amount c left join job_master jci on jci.id=c.job_fk where jci.test_city='$city' and jci.branch_fk=bm.id and c.payment_type='PayTm' AND c.createddate >= '" . $start_date . "' AND c.status='1' AND c.createddate <= '" . $end_date . "') as paytm_total,
              (SELECT SUM(c.amount) from job_master_receiv_amount c left join job_master jci on jci.id=c.job_fk where jci.test_city='$city' and jci.branch_fk=bm.id and (c.payment_type='CREDIT CARD' or c.payment_type='CREDIT CARD swiped thru ICICI' or c.payment_type='CREDIT CARD swiped thru MSWIP') AND c.createddate >= '" . $start_date . "' AND c.status='1' AND c.createddate <= '" . $end_date . "') as credit_total,
                  (SELECT SUM(c.amount) from job_master_receiv_amount c left join job_master jci on jci.id=c.job_fk where jci.test_city='$city' and jci.branch_fk=bm.id and (c.payment_type='DEBIT CARD swiped thru ICICI' or c.payment_type='DEBIT CARD swiped thru MSWIP') AND c.createddate >= '" . $start_date . "' AND c.status='1' AND c.createddate <= '" . $end_date . "') as debit_total,
                      (SELECT SUM(c.amount) from job_master_receiv_amount c left join job_master jci on jci.id=c.job_fk where jci.test_city='$city' and jci.branch_fk=bm.id and (c.payment_type='Swipe thru AXIS' or c.payment_type='Swipe thru HDFC') AND c.createddate >= '" . $start_date . "' AND c.status='1' AND c.createddate <= '" . $end_date . "') as axis_hdfc,
                          (SELECT SUM(c.amount) from job_master_receiv_amount c left join job_master jci on jci.id=c.job_fk where jci.test_city='$city' and jci.branch_fk=bm.id and (c.payment_type='ONLINE' or c.payment_type='WEB ONLINE') AND c.createddate >= '" . $start_date . "' AND c.status='1' AND c.createddate <= '" . $end_date . "') as online_total,
                              (select SUM(s.amount) from job_master_receiv_amount s left join job_master jci on jci.id=s.job_fk where jci.test_city='$city' and jci.branch_fk=bm.id and s.createddate >='" . $start_date . "' and s.createddate <='" . $end_date . "' and s.status='1' AND jci.date >='" . $start_date . "' AND jci.date <='" . $end_date . "' and jci.status != '0') as same_day,
      (select SUM(b.amount) from job_master_receiv_amount b left join job_master jci on jci.id=b.job_fk where jci.test_city='$city' and jci.branch_fk=bm.id and b.createddate<='" . $end_date . "' and b.createddate>='" . $start_date . "' and b.status='1' AND jci.date <='" . $back_date1 . "' and jci.status != '0') as back_day
                from job_master jm left join branch_master bm on bm.id=jm.branch_fk left join test_cities tc on tc.id=bm.city where jm.status != '0' ";
        $qry .= " AND jm.date >= '" . $start_date . "' AND jm.date <= '" . $end_date . "'";

        if ($branch != "") {
            $qry .= " AND jm.branch_fk = '" . $branch . "' ";
        }
        if ($city != "") {
            $qry .= " AND tc.id = '" . $city . "' ";
        }
        $qry .= " group by tc.id,jm.branch_fk order by tc.`id`,bm.`id` ASC";
        if ($_REQUEST["debug"] == 1) {
            echo $qry;
            die();
        }
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function diffrent_report($from = null, $to = null, $type = null, $wise = null, $branch = null, $all_branch = null) {
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry = "select
            bm.branch_name,bm.id,";
        if ($type == "client") {
            $qry .= "am.name as client_name,";
        } else if ($type == "doctor") {
            $qry .= "  dm.`id` AS did,dm.full_name as doctor_name,";
        }
        $qry .= "jm.date as Registration_date,
            DAY(jm.date) as day,
            MONTH(jm.date) as month,
            YEAR(jm.date) as year,
            COUNT(jm.id) as sample_count,
            SUM(Round(jm.`price`)) as Total_Amount,
            SUM(Round((if(jm.`discount` != 'NULL',jm.`discount`,0) * if(jm.price != 'NULL',jm.price,0)) / 100)) as Discount_Amount,
            SUM(Round(if(jm.price != 'NULL',jm.price,0) - ((if(jm.`discount` != 'NULL',jm.`discount`,0) * if(jm.price != 'NULL',jm.price,0)) / 100))) as Net_Amount,
            SUM(Round(if(jm.price != 'NULL',jm.price,0) - ((if(jm.`discount` != 'NULL',jm.`discount`,0) * if(jm.price != 'NULL',jm.price,0)) / 100) - if(jm.payable_amount != 'NULL',jm.payable_amount,0))) as Received_Amount,
            SUM(jm.payable_amount) as Due_Amount
                from job_master jm join branch_master bm on bm.id=jm.branch_fk";
        if ($type == "client") {
            $qry .= " join admin_master am on am.id=jm.added_by ";
        } else if ($type == "doctor") {
            $qry .= " join doctor_master dm on dm.id=jm.doctor ";
        }
        $qry .= " where jm.status != '0' and jm.`model_type`='1' and bm.status = '1' ";
        if ($from != "" || $to != "") {
            $qry .= " AND jm.date >= '" . $start_date . "' AND jm.date <= '" . $end_date . "'";
        }
        if (!empty($all_branch)) {
            $qry .= " AND jm.branch_fk in (" . implode(",", $all_branch) . ")";
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
            $qry .= ",year,month,day";
        } else if ($wise == "month") {
            $qry .= ",month,year";
        } else if ($wise == "year") {
            $qry .= ",year";
        }
        $qry .= " order by bm.`id`,year,month,day ASC";

        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function panel_report($from = null, $to = null, $wise = null, $branch = null) {
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry = "select
            bm.branch_name,bm.id,
            tp.name as panel_name,
            jm.date as Registration_date,
            DAY(jm.date) as day,
            MONTH(jm.date) as month,
            YEAR(jm.date) as year,
            COUNT(jm.id) as sample_count,
            SUM(Round(bjt.`price`)) as Total_Amount,
            SUM(Round((if(jm.`discount` != 'NULL',jm.`discount`,0) * if(bjt.price != 'NULL',bjt.price,0)) / 100)) as Discount_Amount,
            SUM(Round(if(bjt.price != 'NULL',bjt.price,0) - ((if(jm.`discount` != 'NULL',jm.`discount`,0) * if(bjt.price != 'NULL',bjt.price,0)) / 100))) as Net_Amount
                from job_master jm join branch_master bm on bm.id=jm.branch_fk join booked_job_test as bjt on bjt.job_fk=jm.id join test_panel tp on tp.id=bjt.panel_fk where jm.status != '0' and bm.status = '1' and bjt.status = '1' and tp.status = '1'";
        if ($from != "" || $to != "") {
            $qry .= " AND jm.date >= '" . $start_date . "' AND jm.date <= '" . $end_date . "'";
        }
        if ($branch != "") {
            $qry .= " AND jm.branch_fk = '" . $branch . "' ";
        }
        $qry .= " group by jm.branch_fk,bjt.panel_fk";
        if ($wise == "day") {
            $qry .= ",year,month,day";
        } else if ($wise == "month") {
            $qry .= ",month,year";
        } else if ($wise == "year") {
            $qry .= ",year";
        }
        $qry .= " order by bm.`id`,year,month,day ASC";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function creditors_report($from = null, $to = null, $id) {
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry = "SELECT 
  cm.name,
  cm.id,
  cb.id AS cid,
  cm.mobile,
  cb.credit,
  cb.remark,
  cb.debit,
  cb.job_id,
  cb.paid,
  jm.`order_id`,
  am.name AS added_by,
  IF(bi.`family_member_fk`>0,cfm.name,cm1.`full_name`) AS patient_name,
  cb.created_date 
FROM
  creditors_master cm 
  JOIN creditors_balance cb 
    ON cb.creditors_fk = cm.id 
  LEFT JOIN admin_master am 
    ON am.id = cb.created_by 
    LEFT JOIN `job_master` jm
    ON jm.id=cb.job_id
    LEFT JOIN `booking_info` bi
    ON bi.`id`=jm.`booking_info`
    LEFT JOIN `customer_master` cm1
    ON cm1.id=jm.`cust_fk`
    LEFT JOIN `customer_family_master` cfm
    ON cfm.id=bi.`family_member_fk` where cm.status = '1' and cb.debit>'0' and cb.status = '1' and cm.id='$id'";
        if ($from != "" || $to != "") {
            $qry .= " AND cb.created_date >= '" . $start_date . "' AND cb.created_date <= '" . $end_date . "'";
        }
        $qry .= " order by cb.paid asc,cb.created_date ASC";
//        echo $qry; die();
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }
    function creditors_report_all($id) {
        $qry = "SELECT 
  cm.name,
  cm.id,
  cb.id AS cid,
  cm.mobile,
  cb.credit,
  cb.remark,
  cb.debit,
  cb.job_id,
  cb.paid,
  jm.`order_id`,
  am.name AS added_by,
  IF(bi.`family_member_fk`>0,cfm.name,cm1.`full_name`) AS patient_name,
  cb.created_date 
FROM
  creditors_master cm 
  JOIN creditors_balance cb 
    ON cb.creditors_fk = cm.id 
  LEFT JOIN admin_master am 
    ON am.id = cb.created_by 
    LEFT JOIN `job_master` jm
    ON jm.id=cb.job_id
    LEFT JOIN `booking_info` bi
    ON bi.`id`=jm.`booking_info`
    LEFT JOIN `customer_master` cm1
    ON cm1.id=jm.`cust_fk`
    LEFT JOIN `customer_family_master` cfm
    ON cfm.id=bi.`family_member_fk` where cm.status = '1' and cb.debit>'0' and cb.status = '1' and cm.id='$id'";
        $qry .= " order by cb.paid asc,cb.created_date ASC";
//        echo $qry; die();
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }
function branchcreditors_report($from = null, $to = null,$branch,$id) {
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry = "SELECT 
  cm.name,
  cm.id,
  cb.id AS cid,
  cm.mobile,
  cb.credit,
  cb.remark,
  cb.debit,
  cb.job_id,
  cb.paid,
  jm.`order_id`,
  br.`branch_name`,
  am.name AS added_by,
  IF(bi.`family_member_fk`>0,cfm.name,cm1.`full_name`) AS patient_name,
  cb.created_date 
FROM
  creditors_master cm 
  JOIN creditors_balance cb 
    ON cb.creditors_fk = cm.id 
  LEFT JOIN admin_master am 
    ON am.id = cb.created_by 
    JOIN `job_master` jm 
    ON jm.id=cb.job_id AND jm.status != '0' AND jm.branch_fk='$branch'
    LEFT JOIN `booking_info` bi
    ON bi.`id`=jm.`booking_info`
    LEFT JOIN `customer_master` cm1
    ON cm1.id=jm.`cust_fk`
    LEFT JOIN `customer_family_master` cfm
    ON cfm.id=bi.`family_member_fk` 
    LEFT JOIN branch_master  AS br ON br.`id` = jm.`branch_fk` AND br.`status`='1' where cm.status = '1' and cb.debit>'0' and cb.status = '1' and cm.id='$id'";
        if ($from != "" || $to != "") {
            $qry .= " AND cb.created_date >= '" . $start_date . "' AND cb.created_date <= '" . $end_date . "'";
        }
        $qry .= " order by cb.paid asc,cb.created_date ASC";
//        echo $qry; die();
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }
    function creditors_report_id($id) {

        $qry = "select
            cm.name,cm.id,
            cm.mobile,
            cb.credit,
            cb.remark,
            cb.debit,
            cb.job_id,
            cb.paid,
            am.name as added_by,
            cb.created_date
                from creditors_master cm join creditors_balance cb on cb.creditors_fk=cm.id left join admin_master am on am.id=cb.created_by where cm.status = '1' and cb.status = '1' and cb.id='$id'";

        $qry .= " order by cb.created_date ASC";
        //echo $qry; die();
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }
function branchcreditors_report_id($branch,$id) {

        $qry = "select
            cm.name,cm.id,
            cm.mobile,
            cb.credit,
            cb.remark,
            cb.debit,
            cb.job_id,
            cb.paid,
            am.name as added_by,
            cb.created_date
                from creditors_master cm join creditors_balance cb on cb.creditors_fk=cm.id left join admin_master am on am.id=cb.created_by JOIN `job_master` jm ON jm.id=cb.job_id AND jm.status != '0' AND jm.branch_fk='$branch' where cm.status = '1' and cb.status = '1' and cb.id='$id'";

        $qry .= " order by cb.created_date ASC";
        //echo $qry; die();
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }
    function creditors_report_count($id) {
        $qry = "select
            cm.name
                from creditors_master cm join creditors_balance cb on cb.creditors_fk=cm.id left join admin_master am on am.id=cb.created_by where cm.status = '1' and cb.status = '1' and cm.id='$id' AND cb.`debit`>0 ";

        $qry .= " order by cb.created_date ASC";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function creditors_list($branch_fk = null) {
        $qry = "select cm.id,cm.name,cm.mobile,SUM(if(cb.credit != 'NULL',cb.credit,0)) as credit,SUM(if(cb.debit != 'NULL',cb.debit,0)) as debit from creditors_master cm join creditors_balance cb on cb.creditors_fk=cm.id where 1=1";
        if ($branch_fk != '') {
            $qry .= " AND cm.id IN (SELECT DISTINCT creditors_fk FROM `creditors_branch` WHERE STATUS='1' AND branch_fk IN (" . $branch_fk . "))";
        }
        $qry .= " GROUP BY cm.id ORDER BY cm.name ASC";

        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function test_report($from = null, $to = null, $branch = null, $all_branch = null) {
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry = "select
            bm.branch_name,bm.id as bid,
            tm.test_name,count(tl.test_fk) as count_test
                from job_master jm join job_test_list_master tl on tl.job_fk=jm.id join test_master tm on tm.id=tl.test_fk join branch_master bm on bm.id=jm.branch_fk where jm.status != '0' and bm.status = '1' AND jm.date >= '" . $start_date . "' AND jm.date <= '" . $end_date . "'";
        if (!empty($all_branch)) {
            $qry .= " AND jm.branch_fk in (" . implode(",", $all_branch) . ")";
        }
        if ($branch != "") {
            $qry .= " AND jm.branch_fk = '" . $branch . "' ";
        }
        $qry .= " group by tl.test_fk,jm.branch_fk order by bid,count_test DESC";
//        echo $qry; die();
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function testdoctorReport($from = null, $to = null, $id = null, $branch = null, $cid = null) {
        $qry = "SELECT 
            dm.id as did,
            jm.date,
            jm.order_id,
            cm.full_name as cname,
            cm.gender,
            cm.dob,
            tm.test_name as tname,
            tmc.price,
            IF(
    `jm`.`phlebo_added` != '',
    CONCAT(pm.`name`,'','(Phlebotomy)'),`am`.`name`
  ) AS aname,
  b.branch_name as bname,
  dm.full_name as dname
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

        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];

        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry .= " AND jm.date >= '" . $start_date . "' AND jm.date <= '" . $end_date . "'";
        if (!empty($branch)) {
            $qry .= " AND b.id  in (" . implode(",", $branch) . ")";
        }
        $qry .= " AND b.id  = '" . $id . "'";
        if (!empty($cid)) {
            $qry .= " AND tc.id  = '" . $cid . "'";
        }
        $qry .= " group by dm.id,cm.id,tm.id order by dm.`id` ASC";


        //echo $qry;
        // die();
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function jobcollection_received_report($from = null, $to = null, $branch = null, $all_branch = null, $payment = null, $report = null) {
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry = "SELECT 
  bm.branch_name AS branch,
  tc.name as test_city,
  bm.id AS bid,
  IF(
    `booking_info`.`family_member_fk` > 0,
    `customer_family_master`.`name`,
    cm.full_name
  ) AS patient,
  dm.full_name AS doctor_name,
  jm.order_id AS order_id,
  jm.id AS jid,
  IF(
    `jm`.`phlebo_added` != '',
    CONCAT(cbp.`name`, '', '(Phlebotomy)'),
    `cb`.`name`
  ) AS added_name,
  jm.date AS added_date,
  ROUND(jm.`price`) AS gross_amt,
  ROUND(
    (
      IF(
        jm.`discount` != 'NULL',
        jm.`discount`,
        0
      ) * IF(jm.price != 'NULL', jm.price, 0)
    ) / 100
  ) AS discount,
  ROUND(
    IF(jm.price != 'NULL', jm.price, 0) - (
      (
        IF(
          jm.`discount` != 'NULL',
          jm.`discount`,
          0
        ) * IF(jm.price != 'NULL', jm.price, 0)
      ) / 100
    )
  ) AS net_amt,
  ROUND(jr.amount) AS received_amt,
  jr.payment_type AS received_type,
  REPLACE(
    jr.`remark`,
    'credited by',
    'Creditor Name : '
  ) AS remark,
  IF(
    `jr`.`phlebo_fk` != '',
    CONCAT(rcp.`name`, '', '(Phlebotomy)'),
    `rc`.`name`
  ) AS received_name,
  jr.createddate AS received_date,
  ROUND(jm.payable_amount) AS due_amt 
FROM 
job_master_receiv_amount jr 
   
   
  LEFT JOIN  job_master jm 
   ON jr.job_fk = jm.id  
  JOIN branch_master bm 
    ON bm.id = jm.branch_fk 
  LEFT JOIN `booking_info` 
    ON `booking_info`.`id` = jm.`booking_info` 
  LEFT JOIN `customer_family_master` 
    ON `customer_family_master`.`id` = `booking_info`.`family_member_fk` 
  LEFT JOIN doctor_master dm 
    ON dm.id = jm.doctor 
  LEFT JOIN admin_master cb 
    ON cb.id = jm.added_by 
  LEFT JOIN phlebo_master cbp 
    ON cbp.id = jm.phlebo_added 
  JOIN customer_master cm 
    ON cm.id = jm.cust_fk 

  LEFT JOIN admin_master rc 
    ON rc.id = jr.added_by 
  LEFT JOIN phlebo_master rcp 
    ON rcp.id = jr.phlebo_fk 
    LEFT JOIN `test_cities` tc ON bm.`city`= tc.`id`
WHERE jm.status != '0' and jr.status != '0'
  AND jm.model_type = 1 
  AND bm.status = '1' and jr.amount!='' ";
        if ($from != "" || $to != "") {
            $qry .= " AND jr.`createddate` >= '" . $start_date . "' AND jr.`createddate` <= '" . $end_date . "'";
        }
        if (!empty($all_branch)) {
            $qry .= " AND jm.branch_fk in (" . implode(",", $all_branch) . ")";
        }
        if ($branch != "") {
            $qry .= " AND jm.branch_fk = '" . $branch . "' ";
        }
        if ($payment == 'CASH') {
            $qry .= " AND jr.payment_type = '" . $payment . "' ";
        } else if ($payment == 'CHEQUE') {
            $qry .= " AND jr.payment_type = '" . $payment . "' ";
        } else if ($payment == 'CREDIT CARD') {
                  $qry .= " AND jr.payment_type in (" . implode(",", array("'CREDIT CARD'", "'CREDIT CARD swiped thru ICICI'", "'WALLET CREDIT CARD swiped thru MSWIP'", "'DEBIT CARD swiped thru ICICI'", "'DEBIT CARD swiped thru MSWIP'", "'Swipe thru HDFC'", "'Swipe thru AXIS'", "'DEBIT CARD'","'PayTm'","'PAYTM'")) . ") ";
     
        } else if ($payment == 'all') {
            $qry .= " AND jr.payment_type in (" . implode(",", array("'CREDIT CARD'","'CHEQUE'","'CASH'", "'CREDIT CARD swiped thru ICICI'", "'WALLET CREDIT CARD swiped thru MSWIP'", "'DEBIT CARD swiped thru ICICI'", "'DEBIT CARD swiped thru MSWIP'", "'Swipe thru HDFC'", "'Swipe thru AXIS'", "'DEBIT CARD'","'PayTm'","'PAYTM'")) . ") ";
        } else if ($payment == 'CREDITORS') {
            $qry .= " AND jr.payment_type = '" . $payment . "' ";
        } 
        $qry .= "  order by bm.`id`,jm.id ASC";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1; 
    } 
	function jobcollection_received_report_creditor_summary($from = null, $to = null, $branch = null, $all_branch = null, $payment = null, $report = null) {
       $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry = "SELECT 
 
  sum(IF( ISNULL(jr.`credit`),ROUND(jr.debit),ROUND(jr.credit) )) AS received_amt,
 
   CONCAT(rc.`name`, '', '(CREDITOR)')
  AS received_name
 
FROM
  `creditors_balance` jr 
  LEFT JOIN job_master jm 
    ON jr.`job_id` = jm.id 
  JOIN branch_master bm 
    ON bm.id = jm.branch_fk 
  LEFT JOIN `booking_info` 
    ON `booking_info`.`id` = jm.`booking_info` 
  LEFT JOIN `customer_family_master` 
    ON `customer_family_master`.`id` = `booking_info`.`family_member_fk` 
  LEFT JOIN doctor_master dm 
    ON dm.id = jm.doctor 
  LEFT JOIN admin_master cb 
    ON cb.id = jm.added_by 
  LEFT JOIN phlebo_master cbp 
    ON cbp.id = jm.phlebo_added 
  JOIN customer_master cm 
    ON cm.id = jm.cust_fk 
  LEFT JOIN admin_master rc 
    ON rc.id = jr.`created_by` 
WHERE jm.status != '0'
AND jr.`paid`=0 
  AND jm.model_type = 1 
  AND jr.status = '1'
  AND bm.status = '1'  ";
        if ($from != "" || $to != "") {
            $qry .= " AND jr.`created_date` >= '" . $start_date . "' AND jr.`created_date` <= '" . $end_date . "'";
        }
        if (!empty($all_branch)) {
            $qry .= " AND jm.branch_fk in (" . implode(",", $all_branch) . ")";
        }
		if ($payment=="CREDITORS") {
            $qry .= " AND isnulL(jr.credit)";
        }
		if ($payment=="CASH") {
            $qry .= " AND isnulL(jr.debit)";
        }
        if ($branch != "") {
            $qry .= " AND jm.branch_fk = '" . $branch . "' ";
        }
        $qry .= " group by   `rc`.`name`  order by bm.`id`,jm.id ASC";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
 
}
 function jobcollection_received_report_creditor_branch_summary($from = null, $to = null, $branch = null, $all_branch = null, $payment = null, $report = null) {
       $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry = "SELECT 
 jm.branch_fk,
  sum(IF( ISNULL(jr.`credit`),ROUND(jr.debit),ROUND(jr.credit) )) AS received_amt,
 IF( ISNULL(jr.`credit`),'CREDITOR','CASH-CREDITOR') AS received_type,
   bm.branch_name,
  
   bm.branch_name
  AS received_name
 
FROM
  `creditors_balance` jr 
  LEFT JOIN job_master jm 
    ON jr.`job_id` = jm.id 
  JOIN branch_master bm 
    ON bm.id = jm.branch_fk 
  LEFT JOIN `booking_info` 
    ON `booking_info`.`id` = jm.`booking_info` 
  LEFT JOIN `customer_family_master` 
    ON `customer_family_master`.`id` = `booking_info`.`family_member_fk` 
  LEFT JOIN doctor_master dm 
    ON dm.id = jm.doctor 
  LEFT JOIN admin_master cb 
    ON cb.id = jm.added_by 
  LEFT JOIN phlebo_master cbp 
    ON cbp.id = jm.phlebo_added 
  JOIN customer_master cm 
    ON cm.id = jm.cust_fk 
  LEFT JOIN admin_master rc 
    ON rc.id = jr.`created_by` 
WHERE jm.status != '0'
AND jr.`paid`=0 
  AND jm.model_type = 1 
  AND jr.status = '1'
  AND bm.status = '1'  ";
        if ($from != "" || $to != "") {
            $qry .= " AND jr.`created_date` >= '" . $start_date . "' AND jr.`created_date` <= '" . $end_date . "'";
        }
        if (!empty($all_branch)) {
            $qry .= " AND jm.branch_fk in (" . implode(",", $all_branch) . ")";
        }
		if ($payment=="CREDITORS") {
            $qry .= " AND isnulL(jr.credit)";
        }
		if ($payment=="CASH") {
            $qry .= " AND isnulL(jr.debit)";
        }
        if ($branch != "") {
            $qry .= " AND jm.branch_fk = '" . $branch . "' ";
        }
        $qry .= " group by   `bm`.`id`  order by bm.`id`,jm.id ASC";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
 
}
 
		function jobcollection_received_report_creditor($from = null, $to = null, $branch = null, $all_branch = null, $payment = null, $report = null) {
       $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry = "SELECT 
  bm.branch_name AS branch,
  tc.`name` AS test_city,
  bm.id AS bid,
  IF(
    `booking_info`.`family_member_fk` > 0,
    `customer_family_master`.`name`,
    cm.full_name
  ) AS patient,
  dm.full_name AS doctor_name,
  jm.order_id AS order_id,
  jm.id AS jid,
  IF(
    `jm`.`phlebo_added` != '',
    CONCAT(cbp.`name`, '', '(Phlebotomy)'),
    `cb`.`name`
  ) AS added_name,
  jm.date AS added_date,
  ROUND(jm.`price`) AS gross_amt,
  ROUND(
    (
      IF(
        jm.`discount` != 'NULL',
        jm.`discount`,
        0
      ) * IF(jm.price != 'NULL', jm.price, 0)
    ) / 100
  ) AS discount,
  ROUND(
    IF(jm.price != 'NULL', jm.price, 0) - (
      (
        IF(
          jm.`discount` != 'NULL',
          jm.`discount`,
          0
        ) * IF(jm.price != 'NULL', jm.price, 0)
      ) / 100
    )
  ) AS net_amt,
  IF( ISNULL(jr.`credit`),ROUND(jr.debit),ROUND(jr.credit) ) AS received_amt,
  IF( ISNULL(jr.`credit`),'CREDITOR','CASH-CREDITOR') AS received_type,
  REPLACE(
    jr.`remark`,
    'credited by',
    'Creditor Name : '
  ) AS remark,
  
    `rc`.`name`
  AS received_name,
  jr.`paid`,
  jr.created_date AS received_date,
  ROUND(jm.payable_amount) AS due_amt 
FROM
  `creditors_balance` jr 
  LEFT JOIN job_master jm 
    ON jr.`job_id` = jm.id 
  JOIN branch_master bm 
    ON bm.id = jm.branch_fk 
  LEFT JOIN `booking_info` 
    ON `booking_info`.`id` = jm.`booking_info` 
  LEFT JOIN `customer_family_master` 
    ON `customer_family_master`.`id` = `booking_info`.`family_member_fk` 
  LEFT JOIN doctor_master dm 
    ON dm.id = jm.doctor 
  LEFT JOIN admin_master cb 
    ON cb.id = jm.added_by 
  LEFT JOIN phlebo_master cbp 
    ON cbp.id = jm.phlebo_added 
  JOIN customer_master cm 
    ON cm.id = jm.cust_fk 
  LEFT JOIN admin_master rc 
    ON rc.id = jr.`created_by` 
    LEFT JOIN `test_cities` tc ON bm.`city`= tc.`id`
WHERE jm.status != '0'
AND jr.`paid`=0 
  AND jm.model_type = 1 
  AND jr.status = '1'
  AND bm.status = '1'  ";
        if ($from != "" || $to != "") {
            $qry .= " AND jr.`created_date` >= '" . $start_date . "' AND jr.`created_date` <= '" . $end_date . "'";
        }
        if (!empty($all_branch)) {
            $qry .= " AND jm.branch_fk in (" . implode(",", $all_branch) . ")";
        }
		if ($payment=="CREDITORS") {
            $qry .= " AND isnulL(jr.credit)";
        }
		if ($payment=="CASH") {
            $qry .= " AND isnulL(jr.debit)";
        }
        if ($branch != "") {
            $qry .= " AND jm.branch_fk = '" . $branch . "' ";
        }
        $qry .= "  order by bm.`id`,jm.id ASC";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
 
}
 
function jobcollection_received_report_payumoney($from = null, $to = null, $branch = null, $all_branch = null, $payment = null, $report = null) {
       $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry = "SELECT 
   bm.branch_name AS branch,
    tc.`name` AS test_city,
  bm.id AS bid,
  IF(
    `booking_info`.`family_member_fk` > 0,
    `customer_family_master`.`name`,
    cm.full_name
  ) AS patient,
  dm.full_name AS doctor_name,
  jm.order_id AS order_id,
  jm.id AS jid,
  IF(
    `jm`.`phlebo_added` != '',
    CONCAT(cbp.`name`, '', '(Phlebotomy)'),
    `cb`.`name`
  ) AS added_name,
  jm.date AS added_date,
  ROUND(jm.`price`) AS gross_amt,
  ROUND(
    (
      IF(
        jm.`discount` != 'NULL',
        jm.`discount`,
        0
      ) * IF(jm.price != 'NULL', jm.price, 0)
    ) / 100
  ) AS discount,
  ROUND(
    IF(jm.price != 'NULL', jm.price, 0) - (
      (
        IF(
          jm.`discount` != 'NULL',
          jm.`discount`,
          0
        ) * IF(jm.price != 'NULL', jm.price, 0)
      ) / 100
    )
  ) AS net_amt,
  ROUND(jr.amount) AS received_amt,
  'Payumoney' AS received_type,
  '' AS remark,
  'Payumoney' AS received_name,
  jr.`paydate` AS received_date,
  ROUND(jm.payable_amount) AS due_amt 
FROM
  payment jr 
  LEFT JOIN job_master jm 
    ON jr.job_fk = jm.id 
  JOIN branch_master bm 
    ON bm.id = jm.branch_fk 
  LEFT JOIN `booking_info` 
    ON `booking_info`.`id` = jm.`booking_info` 
  LEFT JOIN `customer_family_master` 
    ON `customer_family_master`.`id` = `booking_info`.`family_member_fk` 
  LEFT JOIN doctor_master dm 
    ON dm.id = jm.doctor 
  LEFT JOIN admin_master cb 
    ON cb.id = jm.added_by 
  LEFT JOIN phlebo_master cbp 
    ON cbp.id = jm.phlebo_added 
  JOIN customer_master cm 
    ON cm.id = jm.cust_fk 
  LEFT JOIN `test_cities` tc ON bm.`city`= tc.`id`
WHERE jm.status != '0' and jr.status = 'success'
  AND jm.model_type = 1 
  AND bm.status = '1'  ";
        if ($from != "" || $to != "") {
            $qry .= " AND jr.`paydate` >= '" . $start_date . "' AND jr.`paydate` <= '" . $end_date . "'";
        }
        if (!empty($all_branch)) {
            $qry .= " AND jm.branch_fk in (" . implode(",", $all_branch) . ")";
        }
        if ($branch != "") {
            $qry .= " AND jm.branch_fk = '" . $branch . "' ";
        }
        $qry .= "  order by bm.`id`,jm.id ASC";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
 
}
   function jobcollection_received_report_summary($from = null, $to = null, $branch = null, $all_branch = null, $payment = null, $report = null) {
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry = "SELECT 
  
  sum(ROUND(jr.amount)) AS received_amt,
  IF(
    `jr`.`phlebo_fk` != '',
    CONCAT(rcp.`name`, '', '(Phlebotomy)'),
    `rc`.`name`
  ) AS received_name 
FROM 
job_master_receiv_amount jr 
   
   
  LEFT JOIN  job_master jm 
   ON jr.job_fk = jm.id  
  JOIN branch_master bm 
    ON bm.id = jm.branch_fk 
  LEFT JOIN `booking_info` 
    ON `booking_info`.`id` = jm.`booking_info` 
  LEFT JOIN `customer_family_master` 
    ON `customer_family_master`.`id` = `booking_info`.`family_member_fk` 
  LEFT JOIN doctor_master dm 
    ON dm.id = jm.doctor 
  LEFT JOIN admin_master cb 
    ON cb.id = jm.added_by 
  LEFT JOIN phlebo_master cbp 
    ON cbp.id = jm.phlebo_added 
  JOIN customer_master cm 
    ON cm.id = jm.cust_fk 

  LEFT JOIN admin_master rc 
    ON rc.id = jr.added_by 
  LEFT JOIN phlebo_master rcp 
    ON rcp.id = jr.phlebo_fk 
WHERE jm.status != '0' and jr.status != '0' 
  AND jm.model_type = 1 
  AND bm.status = '1'  ";
        if ($from != "" || $to != "") {
            $qry .= " AND jr.`createddate` >= '" . $start_date . "' AND jr.`createddate` <= '" . $end_date . "'";
        }
        if (!empty($all_branch)) {
            $qry .= " AND jm.branch_fk in (" . implode(",", $all_branch) . ")";
        }
        if ($branch != "") {
            $qry .= " AND jm.branch_fk = '" . $branch . "' ";
        }
        if ($payment == 'CASH') {
            $qry .= " AND jr.payment_type = '" . $payment . "' ";
        } else if ($payment == 'CHEQUE') {
            $qry .= " AND jr.payment_type = '" . $payment . "' ";
        } else if ($payment == 'CREDIT CARD') {
       $qry .= " AND jr.payment_type in (" . implode(",", array("'CREDIT CARD'", "'CREDIT CARD swiped thru ICICI'", "'WALLET CREDIT CARD swiped thru MSWIP'", "'DEBIT CARD swiped thru ICICI'", "'DEBIT CARD swiped thru MSWIP'", "'Swipe thru HDFC'", "'Swipe thru AXIS'", "'DEBIT CARD'","'PayTm'","'PAYTM'")) . ") ";
        } else if ($payment == 'all') {
            $qry .= " AND jr.payment_type in (" . implode(",", array("'CREDIT CARD'","'CHEQUE'","'CASH'", "'CREDIT CARD swiped thru ICICI'", "'WALLET CREDIT CARD swiped thru MSWIP'", "'DEBIT CARD swiped thru ICICI'", "'DEBIT CARD swiped thru MSWIP'", "'Swipe thru HDFC'", "'Swipe thru AXIS'", "'DEBIT CARD'","'PayTm'","'PAYTM'")) . ") ";
        } else if ($payment == 'CREDITORS') {
            $qry .= " AND jr.payment_type = '" . $payment . "' ";
        }
		$qry .= "  group by IF(
    `jr`.`phlebo_fk` != '',
    CONCAT(rcp.`name`, '', '(Phlebotomy)'),
    `rc`.`name`
  )  ";
        $qry .= "  order by bm.`id`,jm.id ASC";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

	
	function jobcollection_received_report_branch_summary($from = null, $to = null, $branch = null, $all_branch = null, $payment = null, $report = null) {
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry = "SELECT jm.branch_fk,
		  sum(ROUND(jr.amount)) AS received_amt,
jr.payment_type,
  bm.branch_name AS received_name 
FROM 
job_master_receiv_amount jr 
   
   
  LEFT JOIN  job_master jm 
   ON jr.job_fk = jm.id  
  JOIN branch_master bm 
    ON bm.id = jm.branch_fk 
  LEFT JOIN `booking_info` 
    ON `booking_info`.`id` = jm.`booking_info` 
  LEFT JOIN `customer_family_master` 
    ON `customer_family_master`.`id` = `booking_info`.`family_member_fk` 
  LEFT JOIN doctor_master dm 
    ON dm.id = jm.doctor 
  LEFT JOIN admin_master cb 
    ON cb.id = jm.added_by 
  LEFT JOIN phlebo_master cbp 
    ON cbp.id = jm.phlebo_added 
  JOIN customer_master cm 
    ON cm.id = jm.cust_fk 

  LEFT JOIN admin_master rc 
    ON rc.id = jr.added_by 
  LEFT JOIN phlebo_master rcp 
    ON rcp.id = jr.phlebo_fk 
WHERE jm.status != '0' and jr.status != '0' 
  AND jm.model_type = 1 
  AND bm.status = '1'  ";
        if ($from != "" || $to != "") {
            $qry .= " AND jr.`createddate` >= '" . $start_date . "' AND jr.`createddate` <= '" . $end_date . "'";
        }
        if (!empty($all_branch)) {
            $qry .= " AND jm.branch_fk in (" . implode(",", $all_branch) . ")";
        }
        if ($branch != "") {
            $qry .= " AND jm.branch_fk = '" . $branch . "' ";
        }
        if ($payment == 'CASH') {
            $qry .= " AND jr.payment_type = '" . $payment . "' ";
        } else if ($payment == 'CHEQUE') {
            $qry .= " AND jr.payment_type = '" . $payment . "' ";
        } else if ($payment == 'CREDIT CARD') {
       $qry .= " AND jr.payment_type in (" . implode(",", array("'CREDIT CARD'", "'CREDIT CARD swiped thru ICICI'", "'WALLET CREDIT CARD swiped thru MSWIP'", "'DEBIT CARD swiped thru ICICI'", "'DEBIT CARD swiped thru MSWIP'", "'Swipe thru HDFC'", "'Swipe thru AXIS'","'PayTm'","'PAYTM'", "'DEBIT CARD'")) . ") ";
        } else if ($payment == 'all') {
            $qry .= " AND jr.payment_type in (" . implode(",", array("'CREDIT CARD'","'CHEQUE'","'CASH'", "'CREDIT CARD swiped thru ICICI'", "'WALLET CREDIT CARD swiped thru MSWIP'", "'DEBIT CARD swiped thru ICICI'", "'DEBIT CARD swiped thru MSWIP'", "'Swipe thru HDFC'", "'Swipe thru AXIS'", "'DEBIT CARD'","'PayTm'","'PAYTM'")) . ") ";
        } else if ($payment == 'CREDITORS') {
            $qry .= " AND jr.payment_type = '" . $payment . "' ";
        }
		$qry .= "  group by jm.branch_fk ,jr.payment_type  ";
        $qry .= "  order by bm.`branch_name`,jm.id ASC";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }


function jobcollection_received_report_payumoney_summary($from = null, $to = null, $branch = null, $all_branch = null, $payment = null, $report = null) {
       $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry = "SELECT 
  sum(ROUND(jr.amount)) AS received_amt,
  'Payumoney' AS received_name 
FROM
  payment jr 
  LEFT JOIN job_master jm 
    ON jr.job_fk = jm.id 
  JOIN branch_master bm 
    ON bm.id = jm.branch_fk 
  LEFT JOIN `booking_info` 
    ON `booking_info`.`id` = jm.`booking_info` 
  LEFT JOIN `customer_family_master` 
    ON `customer_family_master`.`id` = `booking_info`.`family_member_fk` 
  LEFT JOIN doctor_master dm 
    ON dm.id = jm.doctor 
  LEFT JOIN admin_master cb 
    ON cb.id = jm.added_by 
  LEFT JOIN phlebo_master cbp 
    ON cbp.id = jm.phlebo_added 
  JOIN customer_master cm 
    ON cm.id = jm.cust_fk 
  
WHERE jm.status != '0' and jr.status = 'success'
  AND jm.model_type = 1 
  AND bm.status = '1'  ";
        if ($from != "" || $to != "") {
            $qry .= " AND jr.`paydate` >= '" . $start_date . "' AND jr.`paydate` <= '" . $end_date . "'";
        }
        if (!empty($all_branch)) {
            $qry .= " AND jm.branch_fk in (" . implode(",", $all_branch) . ")";
        }
        if ($branch != "") {
            $qry .= " AND jm.branch_fk = '" . $branch . "' ";
        }
        $qry .= "  order by bm.`id`,jm.id ASC";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
 
}
 
function jobcollection_received_report_payumoney_branch_summary($from = null, $to = null, $branch = null, $all_branch = null, $payment = null, $report = null) {
       $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry = "SELECT jm.branch_fk,
  sum(ROUND(jr.amount)) AS received_amt,
  'Payumoney' AS received_type,
  bm.branch_name AS received_name 
FROM
  payment jr 
  LEFT JOIN job_master jm 
    ON jr.job_fk = jm.id 
  JOIN branch_master bm 
    ON bm.id = jm.branch_fk 
  LEFT JOIN `booking_info` 
    ON `booking_info`.`id` = jm.`booking_info` 
  LEFT JOIN `customer_family_master` 
    ON `customer_family_master`.`id` = `booking_info`.`family_member_fk` 
  LEFT JOIN doctor_master dm 
    ON dm.id = jm.doctor 
  LEFT JOIN admin_master cb 
    ON cb.id = jm.added_by 
  LEFT JOIN phlebo_master cbp 
    ON cbp.id = jm.phlebo_added 
  JOIN customer_master cm 
    ON cm.id = jm.cust_fk 
  
WHERE jm.status != '0' and jr.status = 'success'
  AND jm.model_type = 1 
  AND bm.status = '1'  ";
        if ($from != "" || $to != "") {
            $qry .= " AND jr.`paydate` >= '" . $start_date . "' AND jr.`paydate` <= '" . $end_date . "'";
        }
        if (!empty($all_branch)) {
            $qry .= " AND jm.branch_fk in (" . implode(",", $all_branch) . ")";
        }
        if ($branch != "") {
            $qry .= " AND jm.branch_fk = '" . $branch . "' ";
        }
		$qry .= "  group  by bm.`id` ";
        $qry .= "  order by bm.`branch_name`,jm.id ASC";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
 
}
   
function jobcollection_received_report_debit($from = null, $to = null, $branch = null, $all_branch = null, $payment = null, $report = null) {
       $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry = "SELECT 
  bm.branch_name AS branch,
  tc.`name` AS test_city,
  bm.id AS bid,
  IF(
    `booking_info`.`family_member_fk` > 0,
    `customer_family_master`.`name`,
    cm.full_name
  ) AS patient,
  dm.full_name AS doctor_name,
  jm.order_id AS order_id,
  jm.id AS jid,
  IF(
    `jm`.`phlebo_added` != '',
    CONCAT(cbp.`name`, '', '(Phlebotomy)'),
    `cb`.`name`
  ) AS added_name,
  jm.date AS added_date,
  ROUND(jm.`price`) AS gross_amt,
  ROUND(
    (
      IF(
        jm.`discount` != 'NULL',
        jm.`discount`,
        0
      ) * IF(jm.price != 'NULL', jm.price, 0)
    ) / 100
  ) AS discount,
  ROUND(
    IF(jm.price != 'NULL', jm.price, 0) - (
      (
        IF(
          jm.`discount` != 'NULL',
          jm.`discount`,
          0
        ) * IF(jm.price != 'NULL', jm.price, 0)
      ) / 100
    )
  ) AS net_amt,
  ROUND(jr.`debit`) AS received_amt,
  'Wallet Debit' AS received_type,
  '' AS remark,
  'Wallet Debit' AS received_name,
  jr.`created_time`  AS received_date,
  ROUND(jm.payable_amount) AS due_amt 
FROM
  `wallet_master` jr 
  LEFT JOIN job_master jm 
    ON jr.`job_fk` = jm.id 
  JOIN branch_master bm 
    ON bm.id = jm.branch_fk 
  LEFT JOIN `booking_info` 
    ON `booking_info`.`id` = jm.`booking_info` 
  LEFT JOIN `customer_family_master` 
    ON `customer_family_master`.`id` = `booking_info`.`family_member_fk` 
  LEFT JOIN doctor_master dm 
    ON dm.id = jm.doctor 
  LEFT JOIN admin_master cb 
    ON cb.id = jm.added_by 
  LEFT JOIN phlebo_master cbp 
    ON cbp.id = jm.phlebo_added 
  JOIN customer_master cm 
    ON cm.id = jm.cust_fk 
    LEFT JOIN `test_cities` tc ON bm.`city`= tc.`id`
WHERE jm.status != '0' 
  AND jr.status = '1' 
  AND jm.model_type = 1 
  AND bm.status = '1'  and !isnull(jr.debit)  ";
        if ($from != "" || $to != "") {
            $qry .= " AND jr.`created_time` >= '" . $start_date . "' AND jr.`created_time` <= '" . $end_date . "'";
        }
        if (!empty($all_branch)) {
            $qry .= " AND jm.branch_fk in (" . implode(",", $all_branch) . ")";
        }
        if ($branch != "") {
            $qry .= " AND jm.branch_fk = '" . $branch . "' ";
        }
        $qry .= "  order by bm.`id`,jm.id ASC";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
 
} 
 
 
function jobcollection_received_report_debit_summary($from = null, $to = null, $branch = null, $all_branch = null, $payment = null, $report = null) {
       $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry = "SELECT 
  
 sum( ROUND(jr.`debit`)) AS received_amt,
  'Wallet Debit' AS received_type,
  '' AS remark,
  'Wallet Debit' AS received_name,
  jr.`created_time`  AS received_date,
  ROUND(jm.payable_amount) AS due_amt 
FROM
  `wallet_master` jr 
  LEFT JOIN job_master jm 
    ON jr.`job_fk` = jm.id 
  JOIN branch_master bm 
    ON bm.id = jm.branch_fk 
  LEFT JOIN `booking_info` 
    ON `booking_info`.`id` = jm.`booking_info` 
  LEFT JOIN `customer_family_master` 
    ON `customer_family_master`.`id` = `booking_info`.`family_member_fk` 
  LEFT JOIN doctor_master dm 
    ON dm.id = jm.doctor 
  LEFT JOIN admin_master cb 
    ON cb.id = jm.added_by 
  LEFT JOIN phlebo_master cbp 
    ON cbp.id = jm.phlebo_added 
  JOIN customer_master cm 
    ON cm.id = jm.cust_fk 
WHERE jm.status != '0' 
  AND jr.status = '1' 
  AND jm.model_type = 1 
  AND bm.status = '1' and !isnull(jr.debit) ";
        if ($from != "" || $to != "") {
            $qry .= " AND jr.`created_time` >= '" . $start_date . "' AND jr.`created_time` <= '" . $end_date . "'";
        }
        if (!empty($all_branch)) {
            $qry .= " AND jm.branch_fk in (" . implode(",", $all_branch) . ")";
        }
        if ($branch != "") {
            $qry .= " AND jm.branch_fk = '" . $branch . "' ";
        }
        $qry .= "  group by received_type order by bm.`id`,jm.id ASC";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
 
}
 
function jobcollection_received_report_debit_branch_summary($from = null, $to = null, $branch = null, $all_branch = null, $payment = null, $report = null) {
       $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry = "SELECT jm.branch_fk,
  
 sum( ROUND(jr.`debit`)) AS received_amt,
  'Wallet Debit' AS received_type,
  '' AS remark,
  bm.branch_name AS received_name,
  jr.`created_time`  AS received_date,
  ROUND(jm.payable_amount) AS due_amt 
FROM
  `wallet_master` jr 
  LEFT JOIN job_master jm 
    ON jr.`job_fk` = jm.id 
  JOIN branch_master bm 
    ON bm.id = jm.branch_fk 
  LEFT JOIN `booking_info` 
    ON `booking_info`.`id` = jm.`booking_info` 
  LEFT JOIN `customer_family_master` 
    ON `customer_family_master`.`id` = `booking_info`.`family_member_fk` 
  LEFT JOIN doctor_master dm 
    ON dm.id = jm.doctor 
  LEFT JOIN admin_master cb 
    ON cb.id = jm.added_by 
  LEFT JOIN phlebo_master cbp 
    ON cbp.id = jm.phlebo_added 
  JOIN customer_master cm 
    ON cm.id = jm.cust_fk 
WHERE jm.status != '0' 
  AND jr.status = '1' 
  AND jm.model_type = 1 
  AND bm.status = '1' and !isnull(jr.debit) ";
        if ($from != "" || $to != "") {
            $qry .= " AND jr.`created_time` >= '" . $start_date . "' AND jr.`created_time` <= '" . $end_date . "'";
        }
        if (!empty($all_branch)) {
            $qry .= " AND jm.branch_fk in (" . implode(",", $all_branch) . ")";
        }
        if ($branch != "") {
            $qry .= " AND jm.branch_fk = '" . $branch . "' ";
        }
        $qry .= "  group by bm.id order by bm.`branch_name`,jm.id ASC";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
 
}
   
 function job_received_report($from = null, $to = null, $branch = null, $all_branch = null) {
        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
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
    ON `customer_family_master`.`id`=`booking_info`.`family_member_fk` left join doctor_master dm on dm.id = jm.doctor left join admin_master cb on cb.id=jm.added_by left join phlebo_master cbp on cbp.id=jm.phlebo_added join customer_master cm on cm.id=jm.cust_fk left join job_master_receiv_amount jr on jr.job_fk=jm.id and jr.status='1' left join admin_master rc on rc.id=jr.added_by left join phlebo_master rcp on rcp.id=jr.phlebo_fk where jm.status != '0' and jm.model_type=1 and jr.amount!=''  and bm.status = '1' ";
        if ($from != "" || $to != "") {
            $qry .= " AND jm.date >= '" . $start_date . "' AND jm.date <= '" . $end_date . "'";
        }
        if (!empty($all_branch)) {
            $qry .= " AND jm.branch_fk in (" . implode(",", $all_branch) . ")";
        }
        if ($branch != "") {
            $qry .= " AND jm.branch_fk = '" . $branch . "' ";
        }
        $qry .= "  order by bm.`id`,jm.id ASC";
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

    function get_val($qry) {
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }


 function test_report_csv($from = null, $to = null, $branch = null, $all_branch = null) {

        $date1 = explode("/", $from);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $date1 = explode("/", $to);
        $ed = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $start_date = $sd . " 00:00:00";
        $end_date = $ed . " 23:59:59";
        $qry = "select
            bm.branch_name,bm.id as bid,
            tm.test_name,count(tl.test_fk) as count_test
                from job_master jm join job_test_list_master tl on tl.job_fk=jm.id join test_master tm on tm.id=tl.test_fk join branch_master bm on bm.id=jm.branch_fk where jm.status != '0' and bm.status = '1' AND jm.date >= '" . $start_date . "' AND jm.date <= '" . $end_date . "'";
        if (!empty($all_branch)) {
            $qry .= " AND jm.branch_fk in (" . implode(",", $all_branch) . ")";
        }
        if ($branch != "") {
            $qry .= " AND jm.branch_fk = '" . $branch . "' ";
        }
        $qry .= " group by tl.test_fk,jm.branch_fk order by bid,count_test DESC";
//        echo $qry; die();
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }
    function get_sub_data($srchdata = null) {
        $temp = '';
        if ($srchdata['start_date'] != '') {
            $start_date = $srchdata['start_date'].' 00:00:00';
            $temp .= "AND uo.created_date>= '$start_date'";
        }
        if ($srchdata['end_date'] != '') {
            $end_date = $srchdata['end_date'].' 23:59:59';
            $temp .= "AND uo.created_date <= '$end_date'";
        }
        if ($srchdata['branch'] != '') {
            $branch = $srchdata['branch'];
            $temp .= "AND b.id = '$branch'";
        }
        $q = "SELECT test_cities.city_fk as CityId,b.id,jm.id AS job_id,b.branch_name,jm.order_id,tm.id as testId, tm.test_name,om.id AS OutSource_id,om.name AS lab_name,uo.created_date,am.name AS admin_name,tmc.price FROM`branch_master` b  INNER JOIN `job_master` jm ON jm.`branch_fk` = b.id INNER JOIN `user_test_outsource` uo ON uo.`job_fk` = jm.id INNER JOIN `test_master` tm ON uo.`test_fk` = tm.id INNER JOIN `outsource_master` om ON uo.`outsource_fk` = om.id INNER JOIN `admin_master` am ON uo.`created_by` = am.id LEFT JOIN test_cities  ON test_cities.id = jm.`test_city` AND test_cities.status = '1' LEFT JOIN test_master_city_price AS tmc ON tmc.test_fk = tm.id AND test_cities.id=tmc.`city_fk` AND tmc.status = '1' WHERE b.status = '1' $temp ";
        $query = $this->db->query($q);
        return $query->num_rows();
    }
function getoutsource($srchdata = null, $one, $two) {
        $temp = '';
        if ($srchdata['start_date'] != '') {
            $start_date = $srchdata['start_date'].' 00:00:00';
            $temp .= "AND uo.created_date>= '$start_date'";
        }
        if ($srchdata['end_date'] != '') {
            $end_date = $srchdata['end_date'].' 23:59:59';
            $temp .= "AND uo.created_date <= '$end_date'";
        }
        if ($srchdata['branch'] != '') {
            $branch = $srchdata['branch'];
            $temp .= "AND b.id = '$branch'";
        }
        $q = "SELECT test_cities.city_fk as CityId,b.id,jm.id AS job_id,b.branch_name,jm.order_id,tm.id as testId, tm.test_name,om.id AS OutSource_id,om.name AS lab_name,uo.created_date,am.name AS admin_name,tmc.price FROM`branch_master` b  INNER JOIN `job_master` jm ON jm.`branch_fk` = b.id INNER JOIN `user_test_outsource` uo ON uo.`job_fk` = jm.id INNER JOIN `test_master` tm ON uo.`test_fk` = tm.id INNER JOIN `outsource_master` om ON uo.`outsource_fk` = om.id INNER JOIN `admin_master` am ON uo.`created_by` = am.id LEFT JOIN test_cities  ON test_cities.id = jm.`test_city` AND test_cities.status = '1' LEFT JOIN test_master_city_price AS tmc ON tmc.test_fk = tm.id AND test_cities.id=tmc.`city_fk` AND tmc.status = '1' WHERE b.status = '1' $temp  LIMIT $two,$one";
        $query = $this->db->query($q);
        $data['user'] = $query->result_array();
        return $data['user'];
    }
    function getcsv($start_date=null,$end_date=null,$branch=null){
         $temp = '';
        if ($start_date != '') {
            $new_date = $start_date.' 00:00:00';
            $temp .= "AND uo.created_date>= '$new_date'";
        }
        if ($end_date != '') {
            $sub_end_date = $end_date.' 23:59:59';
            $temp .= "AND uo.created_date <= '$sub_end_date'";
        }
        if ($branch != '') {
            
            $temp .= "AND b.id = '$branch'";
        }
        $q = "SELECT test_cities.city_fk as CityId,b.id,jm.id AS job_id,b.branch_name,jm.order_id,tm.id as testId, tm.test_name,om.id AS OutSource_id,om.name AS lab_name,uo.created_date,am.name AS admin_name,tmc.price FROM`branch_master` b  INNER JOIN `job_master` jm ON jm.`branch_fk` = b.id INNER JOIN `user_test_outsource` uo 
   ON uo.`job_fk` = jm.id 
 INNER JOIN `test_master` tm
   ON uo.`test_fk` = tm.id 
 INNER JOIN `outsource_master` om 
   ON uo.`outsource_fk` = om.id 
 INNER JOIN `admin_master` am 
   ON uo.`created_by` = am.id LEFT JOIN test_cities  ON test_cities.id = jm.`test_city` AND test_cities.status = '1' LEFT JOIN test_master_city_price AS tmc ON tmc.test_fk = tm.id AND test_cities.id=tmc.`city_fk` AND tmc.status = '1' WHERE b.status = '1' $temp";
     $query = $this->db->query($q);
        $data['user'] = $query->result_array();
        return $data['user'];
        
        }
}

?>