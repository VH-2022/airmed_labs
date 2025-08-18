<?php

class Logistic_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    function getUser($user_id) {
        $query = $this->db->get_where("admin_master", array("id" => $user_id, "status" => "1"));
        return $query->row();
    }

    public function master_fun_get_tbl_val($dtatabase, $condition, $order, $limit = null) {
        $this->db->order_by($order[0], $order[1]);
        if ($limit != null) {
            $this->db->limit($limit[0], $limit[1]);
        }
        $query = $this->db->get_where($dtatabase, $condition);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function master_fun_insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function master_fun_update($tablename, $condition, $data) {
        $this->db->where($condition);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function master_num_rows($table, $condition) {
        $query1 = $this->db->get_where($table, $condition);
        return $query1->num_rows();
    }

    public function get_server_time() {
        $query = $this->db->query("SELECT UTC_TIMESTAMP()");
        $data['user'] = $query->result_array();
        return $data['user'][0];
    }

    public function get_val($qry) {
        $query1 = $this->db->query($qry);
        $data['user'] = $query1->result_array();
        return $data['user'];
    }

    function samplenum_list($type = null, $laball = null) {

        $this->db->select("l.id as totalid");
        $this->db->from('logistic_log AS l');
        $this->db->join('sample_job_master j', 'j.barcode_fk=l.id', 'left');
        if ($type["type"] == 4) {
            $this->db->join('sample_destination_lab d', 'l.id=d.job_fk and d.lab_fk=' . $type["id"] . '', 'INNER');
        }
        if ($laball != "") {
            $labcheck = explode(",", $laball);
            $this->db->where_in('l.collect_from', $labcheck);
        }
        $this->db->where('l.status', '1');
        $this->db->GROUP_BY('l.id');
        $query = $this->db->get();
        return $query->num_rows();
    }

    function sample_list($type = null, $laball = null, $one = null, $two = null) {
        /*  $limit = "";
          if ($one != null && $two != null) {
          $limit = " LIMIT $two,$one";
          }
          $qry = "SELECT `logistic_log`.*,`phlebo_master`.`name`,`phlebo_master`.`mobile`,`collect_from`.`name` AS `c_name`,sample_destination_lab.lab_fk as desti_lab,(select count(b2b_jobspdf.id) from b2b_jobspdf where b2b_jobspdf.job_fk=logistic_log.id and status='1' ) as treport,sample_job_master.customer_name  FROM `logistic_log`
          left JOIN `phlebo_master` ON `logistic_log`.`phlebo_fk`=`phlebo_master`.`id` and `phlebo_master`.`status` = '1'
          INNER JOIN `collect_from` ON `logistic_log`.`collect_from`=`collect_from`.`id`
          left join sample_job_master on sample_job_master.barcode_fk = logistic_log.id";
          if ($type["type"] == 4) {
          $qry .= " INNER JOIN `sample_destination_lab` ON `logistic_log`.`id`=`sample_destination_lab`.`job_fk` and sample_destination_lab.lab_fk='" . $type["id"] . "'";
          }
          if ($type["type"] == 3) {
          $qry .= " LEFT JOIN `sample_destination_lab` ON `logistic_log`.`id`=`sample_destination_lab`.`job_fk`";
          }
          $qry .= " WHERE `logistic_log`.`status`='1' ORDER BY logistic_log.id DESC" . $limit;
          //echo $qry; die();
          $query = $this->db->query($qry);
          return $query->result_array(); */

        $this->db->select("l.*,p.`name`,p.`mobile`,c.`name` AS `c_name`,tc.name as tetst_city_name,d.lab_fk as desti_lab,(select count(b2b_jobspdf.id) from b2b_jobspdf where b2b_jobspdf.job_fk=l.id and status='1' ) as treport,(SELECT name FROM `admin_master` a where a.status='1' and a.id=d.lab_fk) as desti_lab1,j.id as jobid,j.customer_name,j.payable_amount,j.price,CONCAT(s.first_name, ' ',s.last_name) As salesname,sjm.amount as credit_amount");
        $this->db->from('logistic_log AS l');
        $this->db->join('phlebo_master p', 'p.id=l.phlebo_fk and p.status="1"', 'left');
        $this->db->join('collect_from c', 'c.id=l.collect_from', 'left');
        $this->db->join('sales_user_master s', 's.id=c.sales_fk', 'left');
        $this->db->join('test_cities tc', 'c.city=tc.id', 'left');
        $this->db->join('sample_job_master j', 'j.barcode_fk=l.id', 'left');
        $this->db->join('sample_job_master_receiv_amount sjm', 'sjm.job_fk=j.id AND UPPER(sjm.payment_type)="CREDITORS" AND sjm.status="1"', 'left');
        if ($type["type"] == 4) {
            $this->db->join('sample_destination_lab d', 'l.id=d.job_fk and d.lab_fk=' . $type["id"] . '', 'INNER');
        }
        if ($type["type"] != 4) {
            $this->db->join('sample_destination_lab d', 'l.id=d.job_fk', 'left');
        }
        if ($laball != "") {
            $labcheck = explode(",", $laball);
            $this->db->where_in('l.collect_from', $labcheck);
        }
        $this->db->where('l.status', '1');
        $this->db->GROUP_BY('l.id');
        $this->db->order_by('l.id', 'desc');
        $this->db->limit($one, $two);
        $query = $this->db->get();
        return $query->result_array();
    }

    function samplelist_numrow($type = null, $laball = null, $name = null, $barcode = null, $date = null, $todate = null, $from = null, $patientsname = null, $salesperson = null, $sendto = null, $city = null, $status = null, $payment = null) {

        $this->db->select("l.id");
        $this->db->from('logistic_log AS l');
        $this->db->join('collect_from c', 'c.id=l.collect_from', 'left');
        $this->db->join('sample_job_master j', 'j.barcode_fk=l.id', 'left');
        if ($type["type"] == 4) {
            $this->db->join('sample_destination_lab d', 'l.id=d.job_fk and d.lab_fk=' . $type["id"] . '', 'INNER');
        }
        if ($type["type"] != 4) {
            if (!empty($sendto)) {
                $this->db->join('sample_destination_lab d', "l.id=d.job_fk and d.lab_fk='$sendto'", 'INNER');
            } else {
                $this->db->join('sample_destination_lab d', 'l.id=d.job_fk', 'left');
            }
        }
        $this->db->where('l.status', '1');
        if (!empty($payment) && $payment != "all") {
            if ($payment == 'due') {
                $this->db->where('j.payable_amount !=', '0');
            } else {
                $this->db->where('j.payable_amount', '0');
            }
        }
        if (!empty($name)) {
            $this->db->where('l.phlebo_fk', $name);
        }
        if (!empty($barcode)) {
            $this->db->like('l.barcode', $barcode, 'after');
        }

        if (!empty($patientsname)) {
            $this->db->like('j.customer_name', $patientsname, 'after');
        }


        if (!empty($date)) {
            /* $this->db->where('DATE_FORMAT(l.scan_date,"%Y-%m-%d")',date("Y-m-d",strtotime($date))); */
            $this->db->where("STR_TO_DATE(l.scan_date,'%Y-%m-%d') >=", date("Y-m-d", strtotime($date)));
        }
        if (!empty($todate)) {
            /* $this->db->where('DATE_FORMAT(l.scan_date,"%Y-%m-%d")',date("Y-m-d",strtotime($date))); */
            $this->db->where("STR_TO_DATE(l.scan_date,'%Y-%m-%d') <=", date("Y-m-d", strtotime($todate)));
        }
        /* if (!empty($from)){ $this->db->like('c.name',$from,'after'); } */
        if (!empty($from)) {
            $this->db->where('l.collect_from', $from);
        }
        if (!empty($salesperson)) {
            $this->db->where('c.sales_fk', $salesperson);
        }
        if ($city != "") {
            $this->db->where('c.city', $city);
        }
        if ($laball != "") {
            $labcheck = explode(",", $laball);
            $this->db->where_in('l.collect_from', $labcheck);
        }

        $this->db->GROUP_BY('l.id');
        $this->db->order_by('l.id', 'desc');
        $query = $this->db->get();
        return $query->num_rows();
    }

    function sample_list_num($type = null, $laball = null, $name = null, $barcode = null, $date = null, $todate = null, $from = null, $patientsname = null, $salesperson = null, $sendto = null, $city = null, $status = null, $one = null, $two = null, $payment = null) {

        /* $qry = "SELECT `logistic_log`.*,`phlebo_master`.`name`,`phlebo_master`.`mobile`,`collect_from`.`name` AS `c_name`,sample_destination_lab.lab_fk as desti_lab,(select count(b2b_jobspdf.id) from b2b_jobspdf where b2b_jobspdf.job_fk=logistic_log.id and status='1' ) as treport,sample_job_master.customer_name FROM `logistic_log` 
          left JOIN `phlebo_master` ON `logistic_log`.`phlebo_fk`=`phlebo_master`.`id` and `phlebo_master`.`status` = '1'
          INNER JOIN `collect_from` ON `logistic_log`.`collect_from`=`collect_from`.`id`
          left join sample_job_master on sample_job_master.barcode_fk = logistic_log.id";
          if ($type["type"] == 4) {
          $qry .= " INNER JOIN `sample_destination_lab` ON `logistic_log`.`id`=`sample_destination_lab`.`job_fk` and sample_destination_lab.lab_fk='" . $type["id"] . "'";
          }
          if ($type["type"] == 3) {
          $qry .= " LEFT JOIN `sample_destination_lab` ON `logistic_log`.`id`=`sample_destination_lab`.`job_fk` and sample_destination_lab.lab_fk='" . $type["id"] . "'";
          }
          $qry .= "WHERE  `logistic_log`.`status`='1'";
          if (!empty($name)) {
          $qry .= " AND `phlebo_master`.`name` LIKE '%" . $name . "%'";
          }
          if (!empty($barcode)) {
          $qry .= " AND `logistic_log`.`barcode` LIKE '%" . $barcode . "%'";
          }
          if (!empty($date)) {
          $qry .= " AND `logistic_log`.`scan_date`<'" . $date . " 23:59:59' AND `logistic_log`.`scan_date`>'" . $date . " 00:00:00'";
          }
          if (!empty($from)) {
          $qry .= " AND `collect_from`.`name` LIKE '%" . $from . "%'";
          }
          if ($one != null && $two != null) {
          $qry .= " LIMIT $two,$one";
          } */
        $this->db->select("l.*,p.`name`,p.`mobile`,c.`name` AS `c_name`,tc.name as tetst_city_name,d.lab_fk as desti_lab,(select count(b2b_jobspdf.id) from b2b_jobspdf where b2b_jobspdf.job_fk=l.id and status='1' ) as treport,(SELECT name FROM `admin_master` a where a.status='1' and a.id=d.lab_fk) as desti_lab1,j.order_id,j.id as jobid,j.customer_name,j.price,j.payable_amount,j.customer_mobile,j.customer_gender,j.customer_dob,CONCAT(s.first_name, ' ',s.last_name) As salesname,a.name as creteddby,sjm.amount as credit_amount");
        $this->db->from('logistic_log AS l');
        $this->db->join('phlebo_master p', 'p.id=l.phlebo_fk and p.status="1"', 'left');
        $this->db->join('collect_from c', 'c.id=l.collect_from', 'left');
        $this->db->join('sales_user_master s', 's.id=c.sales_fk', 'left');
        $this->db->join('test_cities tc', 'c.city=tc.id', 'left');
        $this->db->join('sample_job_master j', 'j.barcode_fk=l.id', 'left');
        $this->db->join('admin_master a', 'a.id=l.created_by', 'left');
        $this->db->join('sample_job_master_receiv_amount sjm', 'sjm.job_fk=j.id AND UPPER(sjm.payment_type)="CREDITORS" AND sjm.status="1"', 'left');
        if ($type["type"] == 4) {
            $this->db->join('sample_destination_lab d', 'l.id=d.job_fk and d.lab_fk=' . $type["id"] . '', 'INNER');
        }
        if ($type["type"] != 4) {
            if (!empty($sendto)) {
                $this->db->join('sample_destination_lab d', "l.id=d.job_fk and d.lab_fk='$sendto'", 'INNER');
            } else {
                $this->db->join('sample_destination_lab d', 'l.id=d.job_fk', 'left');
            }
        }
        if ($laball != "") {
            $labcheck = explode(",", $laball);
            $this->db->where_in('l.collect_from', $labcheck);
        }
        $this->db->where('l.status', '1');
        if (!empty($payment) && $payment != "all") {
            if ($payment == 'due') {
                $this->db->where('j.payable_amount !=', '0');
            } else {
                $this->db->where('j.payable_amount', '0');
            }
        }

        if (!empty($name)) {
            $this->db->where('l.phlebo_fk', $name);
        }
        if (!empty($barcode)) {
            $this->db->like('l.barcode', $barcode, 'after');
        }
        if (!empty($patientsname)) {
            $this->db->like('j.customer_name', $patientsname, 'after');
        }

        if (!empty($date)) {
            /* $this->db->where('DATE_FORMAT(l.scan_date,"%Y-%m-%d")',date("Y-m-d",strtotime($date))); */
            $this->db->where("STR_TO_DATE(l.scan_date,'%Y-%m-%d') >=", date("Y-m-d", strtotime($date)));
        }
        if (!empty($todate)) {
            /* $this->db->where('DATE_FORMAT(l.scan_date,"%Y-%m-%d")',date("Y-m-d",strtotime($date))); */
            $this->db->where("STR_TO_DATE(l.scan_date,'%Y-%m-%d') <=", date("Y-m-d", strtotime($todate)));
        }
        /* if(!empty($from)){ $this->db->like('c.name',$from,'after'); } */
        if (!empty($from)) {
            $this->db->where('l.collect_from', $from);
        }
        if (!empty($salesperson)) {
            $this->db->where('c.sales_fk', $salesperson);
        }
        if ($city != "") {
            $this->db->where('c.city', $city);
        }

        $this->db->GROUP_BY('l.id');
        $this->db->order_by('l.id', 'desc');
        $this->db->limit($one, $two);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function lab_num_rows($state_serch = null, $email = null, $laball = null, $sales = null) {

        $query = "SELECT collect_from.id FROM collect_from WHERE status IN('1','5') ";

        if ($state_serch != "") {

            $query .= " AND name like '$state_serch%'";
        }
        if ($email != "") {

            $query .= " AND email like '$email%'";
        }
        if ($sales != "") {
            $query .= " AND collect_from.sales_fk = '" . $sales . "'";
        }
        if ($laball != "") {
            $query .= " AND collect_from.id in($laball)";
        }
        $query .= " ORDER BY collect_from.name asc";

        $result = $this->db->query($query);
        return $result->num_rows();
    }

    public function lab_data($state_serch = null, $email = null, $sales = null, $limit, $start, $laball = null) {

        $query = "SELECT collect_from.id,collect_from.name,collect_from.email,collect_from.status,sales_user_master.first_name,sales_user_master.last_name FROM collect_from left join sales_user_master on sales_user_master.id = collect_from.sales_fk WHERE collect_from.status IN('1','5') ";

        if ($state_serch != "") {

            $query .= " AND collect_from.name like '$state_serch%'";
        }
        if ($email != "") {

            $query .= " AND collect_from.email like '$email%'";
        }
        if ($sales != "") {

            $query .= " AND collect_from.sales_fk = '" . $sales . "'";
        }
        if ($laball != "") {
            $query .= " AND collect_from.id in($laball)";
        }
        $query .= " ORDER BY collect_from.name asc LIMIT $start , $limit";

        $result = $this->db->query($query);
        return $result->result_array();
    }

    public function srch_lab_list($limit, $start, $laball = null) {

        if ($laball != "") {
            $labset = " AND collect_from.id in($laball)";
        } else {
            $labset = "";
        }

        $query = $this->db->query("SELECT collect_from.id,collect_from.name,collect_from.email,collect_from.status,sales_user_master.first_name,sales_user_master.last_name FROM collect_from left join sales_user_master on collect_from.sales_fk = sales_user_master.id WHERE collect_from.status IN('1','5') $labset ORDER BY collect_from.name ASC LIMIT $start , $limit ");
        return $query->result_array();
    }

    function test_list($lab_fk) {
        $query = $this->db->query("SELECT l.id,l.`price`,l.`special_price`,t.`test_name`,l.b2b_price FROM sample_test_city_price l LEFT JOIN sample_test_master t ON t.`id`=l.`test_fk` WHERE l.`status`='1' and l.lab_fk='$lab_fk'  ORDER BY l.id desc LIMIT 5");
        return $query->result_array();
    }

    /* function test_list1($lab_fk) {

      $query = $this->db->query("SELECT l.id,l.price_special as price,t.test_name FROM b2b_testspecial_price l LEFT JOIN test_master t ON t.id=l.test_fk WHERE l.`status`='1' and l.lab_id='$lab_fk'  ORDER BY l.id desc");
      return $query->result_array();

      } */

    function test_list1($lab_fk, $city) {

        $query = $this->db->query("SELECT l.id,l.price_special as price,t.test_name FROM b2b_testspecial_price l LEFT JOIN test_master t ON t.id=l.test_fk INNER join test_master_city_price p on t.id=p.test_fk and p.city_fk='$city' WHERE l.`status`='1' and l.lab_id='$lab_fk' and l.typetest='1' ORDER BY l.id desc");
        return $query->result_array();
    }

    function test_list2($lab_fk, $city) {

        $query = $this->db->query("SELECT l.id,l.price_special as price,t.title as test_name FROM b2b_testspecial_price l LEFT JOIN package_master t ON t.id=l.test_fk INNER join package_master_city_price AS p ON t.id=p.package_fk and city_fk='$city' WHERE l.`status`='1' and l.lab_id='$lab_fk' and l.typetest='2' ORDER BY l.id desc");
        return $query->result_array();
    }

    public function getsampledetils($id) {
        $query = $this->db->query("SELECT l.id,l.`barcode`,l.scan_date,c.`name`,s.customer_mobile as mobile ,s.address,l.`createddate`,s.`customer_name`,s.customer_gender,s.customer_dob,s.doctor FROM logistic_log l LEFT JOIN collect_from c ON c.`id`=l.`collect_from` LEFT JOIN `sample_job_master` s ON s.`barcode_fk`=l.`id` WHERE l.`status`='1' AND l.`id`='$id' ");

        return $query->row();
    }

    public function creditget_last($labfk) {

        $query = $this->db->query("SELECT  id,`total`,lab_fk FROM `sample_credit` WHERE  STATUS='1' AND `job_fk`='$labfk' ORDER BY id DESC");
        $data['user'] = $query->row();
        return $data['user'];
    }

    public function getjobsid($id) {

        $query = $this->db->query("SELECT  id,barcode_fk,payable_amount FROM `sample_job_master` WHERE  status='1' AND barcode_fk='$id' ");
        $data['user'] = $query->row();
        return $data['user'];
    }

    public function fetchdatarow($selact, $table, $array) {
        $this->db->select($selact);
        $query = $this->db->get_where($table, $array);
        return $query->row();
    }

    function total_price($start_date, $to_date) {
        $old_st_date = explode("/", $start_date);
        $new_date = $old_st_date[2] . '-' . $old_st_date[1] . '-' . $old_st_date[0];

        $from_date = $new_date . ' 00:00:00';
        $old_date = explode("/", $to_date);
        $new_end_date = $old_date[2] . '-' . $old_date[1] . '-' . $old_date[0];
        $to_date = $new_end_date . '  23:59:59';

        $query = $this->db->query("select SUM(price) from sample_job_master where date >='$from_date'  and date <='$to_date' and  status='1'");


        return $query->result_array();
    }

    function total_sample($start_date, $to_date) {
        $old_st_date = explode("/", $start_date);
        $new_date = $old_st_date[2] . '-' . $old_st_date[1] . '-' . $old_st_date[0];
        $from_date = $new_date . ' 00:00:00';

        $old_date = explode("/", $to_date);
        $new_end_date = $old_date[2] . '-' . $old_date[1] . '-' . $old_date[0];
        $to_date = $new_end_date . ' 23:59:59';
//echo $qry ="select count(id) from logistic_log where scan_date >='$from_date '  and scan_date <='$to_date' and  status='1'";
        $query = $this->db->query("select count(id) from logistic_log where scan_date >='$from_date '  and scan_date <='$to_date' and  status='1'");

        return $query->result_array();
    }

    public function testdelete($testid, $jobid, $testtype) {
        if ($testid != null) {
            $this->db->where("status", '1');
            $this->db->where("testtype", $testtype);
            $this->db->where("job_fk", $jobid);
            $this->db->where_in("test_fk", explode($testid));
            $this->db->update('sample_job_test', array("updated_date" => date('Y-m-d H:i:s'), "status" => "0"));
        }
        return 1;
    }

}

?>
