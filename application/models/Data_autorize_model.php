<?php

Class Data_autorize_model extends CI_Model {

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
        $data['user'] = $query->result();
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

    public function fetchdatarow($selact, $table, $array) {
        $this->db->select($selact);
        $query = $this->db->get_where($table, $array);
        return $query->row();
    }

    public function num_row($table, $condition) {
        $query = $this->db->get_where($table, $condition);
        return $query->num_rows();
    }

    public function contact_master($table_name, $data) {

        $this->db->insert($table_name, $data);
        return $this->db->insert_id();
    }

    public function citylist() {
        $query = $this->db->query("SELECT c.*,s.state_name,co.country_name FROM city c LEFT JOIN state s ON c.state_fk=s.id LEFT JOIN country co  ON c.`country_fk`=co.id  WHERE s.status=1 AND c.status=1");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function statelist() {
        $query = $this->db->query("SELECT s.*,c.country_name FROM state s LEFT JOIN country c ON c.id=s.`country_fk` WHERE s.status=1 AND c.status=1");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function get_city() {
        $query = $this->db->query("SELECT city.*,state.`state_name` FROM city INNER JOIN state ON city.`state_fk`=state.`id` WHERE city.`status`='1' AND state.`status`='1' ORDER BY city_name ASC");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function get_city_edit($id) {
        $query = $this->db->query("SELECT `test_master_city_price`.*,`test_cities`.`name` AS `city_name` FROM `test_master_city_price` 
INNER JOIN `test_cities` ON `test_master_city_price`.`city_fk`=`test_cities`.`id` 
WHERE `test_master_city_price`.`test_fk`='" . $id . "' 
AND `test_master_city_price`.`status`='1' 
AND `test_cities`.`status`='1' order by `test_cities`.`name` asc");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function get_city_edit1($id) {
        $query = $this->db->query("SELECT `test_master_city_price`.city_fk as `id`,`test_master_city_price`.`price`,`test_cities`.`name` FROM `test_master_city_price` 
INNER JOIN `test_cities` ON `test_master_city_price`.`city_fk`=`test_cities`.`id` 
WHERE `test_master_city_price`.`test_fk`='" . $id . "' 
AND `test_master_city_price`.`status`='1' 
AND `test_cities`.`status`='1'");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function master_fun_delete($tablename, $cid) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->delete($tablename);
        return 1;
    }

    function autho_list($one, $two,$branch,$start_date="",$end_date="") {

        $temp1 = "";
		
        if ($start_date != "") {
            $temp1 .= "AND i.test_date_time >= '$start_date 00:00:00'";
        }

        if ($end_date != "") {
            $temp1 .= "AND i.test_date_time <= '$end_date 23:59:59'";
        }
        if(!empty($branch)) {
            $query = $this->db->query("SELECT i.test_date_time,i.barcode,i.id,c.full_name,j.branch_fk,  branch_master.`branch_name` FROM `instument_data_storage_new` i LEFT join job_master j on j.barcode = i.barcode LEFT join customer_master c on c.id = j.cust_fk LEFT JOIN `branch_master` ON `branch_master`.`id`=j.`branch_fk`  WHERE i.`status`='1' and j.branch_fk in ($branch) $temp1 group by i.barcode ORDER BY i.id DESC LIMIT $two,$one ");
        } else {
            $query = $this->db->query("SELECT i.test_date_time,i.barcode,i.id,c.full_name,j.branch_fk,branch_master.`branch_name` FROM `instument_data_storage_new` i LEFT join job_master j on j.barcode = i.barcode LEFT join customer_master c on c.id = j.cust_fk LEFT JOIN `branch_master` ON `branch_master`.`id`=j.`branch_fk` WHERE i.`status`='1' $temp1 group by i.barcode ORDER BY i.id DESC LIMIT $two,$one ");
        }


        return $query->result_array();
    }
    function autho_list_count($branch,$barcode,$start_date="",$end_date="") {
        $temp1 = "";

        if ($barcode!="") {
            $temp1 .= "AND i.barcode in ($barcode) ";
        }

        if ($start_date != "") {
            $temp1 .= "AND i.test_date_time >= '$start_date 00:00:00'";
        }

        if ($end_date != "") {
            $temp1 .= "AND i.test_date_time <= '$end_date 23:59:59'";
        }

        

        if(!empty($branch)) {
            $query = $this->db->query("SELECT i.test_date_time,i.barcode,i.id,c.full_name,j.branch_fk FROM `job_master` j left join instument_data_storage_new i on i.id = j.barcode left join customer_master c on c.id = j.cust_fk WHERE i.`status`='1' $temp1 group by i.barcode ORDER BY i.id DESC");
        } else {
            // print_r("SELECT i.test_date_time,i.barcode,i.id,c.full_name,j.branch_fk FROM `job_master` j left join instument_data_storage_new i on i.barcode = j.barcode left join customer_master c on c.id = j.cust_fk WHERE i.`status`='1' and i.barcode in ($barcode) $temp1 group by i.barcode ORDER BY i.id DESC");exit;
            $query = $this->db->query("SELECT i.test_date_time,i.barcode,i.id,c.full_name,j.branch_fk FROM `job_master` j left join instument_data_storage_new i on i.barcode = j.barcode left join customer_master c on c.id = j.cust_fk WHERE i.`status`='1' $temp1 group by i.barcode ORDER BY i.id DESC");
        }
           
        return $query->num_rows();
    }
    function get_parameter_withvalue($barcode,$branch) {
        $query = $this->db->query("select p.multiply_by,p.id as parameter_id,j.id as job_id,p.parameter_name,i.id,i.para_value from instument_data_storage_new i join test_parameter_master p on p.code=i.para_code join job_master j on j.barcode = i.barcode join customer_master c on c.id = j.cust_fk join processing_center pc on pc.branch_fk=p.center where i.status=1 and p.status=1 and i.barcode=$barcode and pc.lab_fk='$branch' GROUP BY p.id order by p.order asc");
        return $query->result();
    }
    function autho_list2($one, $two,$branch,$barcode,$start_date="",$end_date="") {

        $temp1 = "";
        if ($barcode!="") {
            $temp1 .= "AND i.barcode in ($barcode) ";
        }

        if ($start_date != "") {
            $temp1 .= "AND i.test_date_time >= '$start_date 00:00:00'";
        }

        if ($end_date != "") {
            $temp1 .= "AND i.test_date_time <= '$end_date 23:59:59'";
        }
        if($branch!="") {
            $query = $this->db->query("SELECT i.test_date_time,i.barcode,i.id,c.full_name,j.branch_fk,  branch_master.`branch_name` FROM `job_master` j LEFT join instument_data_storage_new i on i.barcode = j.barcode LEFT join customer_master c on c.id = j.cust_fk LEFT JOIN `branch_master` ON `branch_master`.`id`=j.`branch_fk`  WHERE i.`status`='1' and j.branch_fk in ($branch) $temp1 group by i.barcode ORDER BY i.id DESC LIMIT $two,$one ");
        } else {
            $query = $this->db->query("SELECT i.test_date_time,i.barcode,i.id,c.full_name,j.branch_fk,branch_master.`branch_name` FROM `job_master` j LEFT join instument_data_storage_new i on i.barcode = j.barcode LEFT join customer_master c on c.id = j.cust_fk LEFT JOIN `branch_master` ON `branch_master`.`id`=j.`branch_fk` WHERE i.`status`='1' $temp1 group by i.barcode ORDER BY i.id DESC LIMIT $two,$one ");
        }


        return $query->result_array();
    }
}

?>
