<?php

class Invert_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    
    function master_fun_insert($table, $data) {
        //echo $table; print_R($data); die();
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function master_fun_update($tablename, $cid, $data) {
        $this->db->where('id', $cid);
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



    public function get_data($query) {
        $query1 = $this->db->query($query);
        $data['user'] = $query1->result_array();
        return $data['user'];
    }

   
   
    public function get_val($qry) {
        $query1 = $this->db->query($qry);
        $data['user'] = $query1->result_array();
        return $data['user'];
    }

    public function master_fun_update_new($tablename, $cid, $data) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->update($tablename, $data);
        return 1;
    }

 public function new_fun_update($tablename, $condition, $data) {
        $this->db->where($condition);
        $this->db->update($tablename, $data);
        return 1;
    }
    public function finishcat_get_tbl_val() {

        $query1 = $this->db->query("select * from bmh_finishing_work_subcat where status='1' order by id desc");
        $data['user'] = $query1->result_array();
        return $data['user'];
    }

    function finish_list() {

        $query = $this->db->query("SELECT * FROM `bmh_finishing_work_cat` WHERE `status`='1'");
        return $query->result_array();
    }

    public function registercat_get_tbl_val() {

        $query1 = $this->db->query("select * from bmh_registration where status='1' order by id desc");
        $data['user'] = $query1->result_array();
        return $data['user'];
    }

    public function listing() {

        $query = $this->db->query("select * from sms_master where status='1' order by id desc");
        return $query->result();
    }

    public function srch_doctor_list($limit, $start) {

        $query = "SELECT 
  `doctor_req_discount`.*,
  `doctor_master`.`full_name` 
FROM
  `doctor_req_discount` 
  INNER JOIN `doctor_master` 
    ON `doctor_master`.`id` = `doctor_req_discount`.`doctor_fk` 
WHERE `doctor_master`.`status` = '1' 
  AND `doctor_req_discount`.`status` = '1' ORDER BY `doctor_req_discount`.`id` DESC LIMIT $start , $limit ";
        $result = $this->db->query($query);
        return $result->result_array();
    }


    function intent_num($branch_fk = null, $machine_fk = null, $intent_code = null) {
        $temp = "";
        if ($branch_fk != '') {
            $temp .= ' AND ism.branch_fk ="' . $branch_fk . '"';
        }

        $query = $this->db->query("Select ism.* from inventory_intent_master as ism where ism.status='1' $temp
ORDER BY ism.id DESC ");

        return $query->num_rows();
    }

    function intent_num1($branch_fk = null, $machine_fk = null, $intent_code = null) {
        $temp = "";
        if ($branch_fk != '') {
            $temp .= ' AND ism.branch_fk ="' . $branch_fk . '"';
        }

        $query = $this->db->query("Select ism.* from inventory_intent_master as ism where ism.status='1' $temp
ORDER BY ism.id DESC ");

        return $query->num_rows();
    }

    // 

    function intent_list($branch_fk = null, $machine_fk = null, $intent_code = null, $one = null, $two = null) {
        $temp = "";
        if ($branch_fk != '') {
            $temp .= ' AND inventory_inward_master.branch_fk ="' . $branch_fk . '"';
        }
//        if ($machine_fk != '') {
//            $temp .= ' AND ism.machine_fk ="' . $machine_fk . '"';
//        }
//        if ($intent_code != '') {
//            $temp .= ' AND ism.intent_id ="' . $intent_code . '"';
//        }
/*        $query = $this->db->query("SELECT 
  ism.*,
  ism.branch_fk,
  br.branch_name AS branch_name,
  am.name AS admin_name,
  im.name as machine_name
FROM
  inventory_stock_master AS ism 
  INNER JOIN `branch_master` AS br 
    ON ism.`branch_fk` = br.id 
    
    LEFT JOIN admin_master AS am ON ism.`created_by` = am.`id` LEFT JOIN inventory_machine as im on ism.machine_fk = im.id and im.status='1' AND am.`status`='1'
    WHERE ism.`status`='1' $temp GROUP BY ism.intent_id
ORDER BY ism.id DESC");*/
//echo "SELECT inventory_inward_master.*,`branch_master`.`branch_name` FROM inventory_inward_master INNER JOIN `branch_master` ON `branch_master`.`id`=inventory_inward_master.`branch_fk` WHERE inventory_inward_master.`status`='1' $temp limit " . $two . "," . $one; die();
        $query = $this->db->query("SELECT 
  inventory_inward_master.*,
  `branch_master`.`branch_name`,
  admin_master.name AS admin_name,
  inventory_pogenrate.`ponumber` 
FROM
  inventory_inward_master 
  INNER JOIN `branch_master` 
    ON `branch_master`.`id` = inventory_inward_master.`branch_fk` 
  LEFT JOIN admin_master 
    ON admin_master.id = inventory_inward_master.created_by 
    LEFT JOIN `inventory_pogenrate` ON `inventory_pogenrate`.id=inventory_inward_master.`po_fk`
WHERE inventory_inward_master.`status` = '1'  $temp order by inventory_inward_master.id desc limit " . $two . "," . $one);
        
        $data['user'] = $query->result_array();
        return $data['user'];
    }
    
    function intent_list1($branch_fk = null, $machine_fk = null, $intent_code = null, $one = null, $two = null) {
        $temp = "";
        if ($branch_fk != '') {
            $temp .= ' AND inventory_intent_master.branch_fk ="' . $branch_fk . '"';
        }$query = $this->db->query("SELECT inventory_intent_master.*,`branch_master`.`branch_name`,admin_master.name as admin_name FROM inventory_intent_master INNER JOIN `branch_master` ON `branch_master`.`id`=inventory_intent_master.`branch_fk` left join admin_master on admin_master.id=inventory_intent_master.created_by WHERE inventory_intent_master.`status`='1' $temp order by inventory_inward_master.id desc limit " . $two . "," . $one);
        
        $data['user'] = $query->result_array();
        return $data['user'];
    }

}

?>
