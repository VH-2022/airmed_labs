<?php

class Lab_model extends CI_Model {

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
	public function fetchdatarow($selact,$table,$array){
		          $this->db->select($selact); 
        $query = $this->db->get_where($table,$array);
        return $query->row();
    }

    function sample_list($lab,$one = null, $two = null) {
        $limit = "";
        if ($one != null && $two != null) {
            $limit = " LIMIT $two,$one";
        }
        $qry = "SELECT `logistic_log`.*,`phlebo_master`.`name`,`phlebo_master`.`mobile`,`collect_from`.`name` AS `c_name`,sample_job_master.customer_name FROM `logistic_log` 
left JOIN `phlebo_master` ON `logistic_log`.`phlebo_fk`=`phlebo_master`.`id` and `phlebo_master`.`status`='1' 
LEFT JOIN `sample_job_master` ON sample_job_master.`barcode_fk`= logistic_log.`id`
INNER JOIN `collect_from` ON `logistic_log`.`collect_from`=`collect_from`.`id`
WHERE  `logistic_log`.`collect_from`='".$lab."' AND `logistic_log`.`status`='1' ORDER BY logistic_log.id DESC" . $limit;
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    function sample_list_num($lab=null,$name = null, $barcode = null, $date = null, $from = null, $one = null, $two = null) {
       $qry = "SELECT `logistic_log`.*,`phlebo_master`.`name`,`phlebo_master`.`mobile`,`collect_from`.`name` AS `c_name` FROM `logistic_log` 
left JOIN `phlebo_master` ON `logistic_log`.`phlebo_fk`=`phlebo_master`.`id` and `phlebo_master`.`status`='1'  
INNER JOIN `collect_from` ON `logistic_log`.`collect_from`=`collect_from`.`id`
WHERE  `logistic_log`.`status`='1'";

        if (!empty($lab)) {
            $qry .= " AND `logistic_log`.`collect_from` LIKE '" . $lab . "%'";
        }
        if (!empty($name)) {
            $qry .= " AND `phlebo_master`.`name` LIKE '" . $name . "%'";
        }
        if (!empty($barcode)) {
            $qry .= " AND `logistic_log`.`barcode` LIKE '" . $barcode . "%'";
        }
        if (!empty($date)) {
			$comare=date("Y-m-d", strtotime($date));
            $qry .= "AND STR_TO_DATE(logistic_log.scan_date,'%Y-%m-%d')='$comare'";
        }
        if (!empty($from)) {
            $qry .= " AND `collect_from`.`name` LIKE '" . $from . "%'";
        }
        if ($one != null && $two != null) {
            $qry .= " LIMIT $two,$one";
        }
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function lab_num_rows($state_serch = null,$email=null) {

        $query = "SELECT * FROM collect_from WHERE status='1' ";

        if ($state_serch != "") {

            $query .= " AND name like '%$state_serch%'";
        }
        if ($email != "") {

            $query .= " AND email like '%$email%'";
        }
        $query .= " ORDER BY name asc";

        $result = $this->db->query($query);
        return $result->num_rows();
    }

    public function lab_data($state_serch = null,$email = null, $limit, $start) {

        $query = "SELECT * FROM collect_from WHERE status='1' ";

        if ($state_serch != "") {

            $query .= " AND name like '%$state_serch%'";
        }
        if ($email != "") {

            $query .= " AND email like '%$email%'";
        }
        $query .= " ORDER BY name asc";

        $result = $this->db->query($query);
        return $result->result_array();
    }

    public function srch_lab_list($limit, $start) {

        $query = $this->db->query("SELECT * FROM collect_from WHERE status='1' ORDER BY name ASC LIMIT $start , $limit ");
        return $query->result_array();
    }

}

?>
