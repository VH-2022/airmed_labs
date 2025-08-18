<?php

class Intent_model extends CI_Model {

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

//     function intent_num($srchs =null){
//         $temp ="";
//         if($srchs['indent_fk'] !=''){
//             $temp .=' AND intent.indent_fk ="'.$srchs['indent_fk'].'"';
//         }
//         if($srchs['category_fk'] !=''){
//             $temp .=' AND intent.category_fk ="'.$srchs['category_fk'].'"';
//         }
//         if($srchs['quantity'] !=''){
//             $temp .=' AND intent.category_fk ="'.$srchs['quantity'].'"';
//         }
//         $query = $this->db->query("SELECT intent.*,it.id as tid ,it.reagent_name as ItemName FROM inventory_intent_request AS intent LEFT JOIN inventory_item AS it ON it.id = intent.category_fk WHERE intent.status = '1' and it.reagent_name !='' and it.reagent_name IS NOT NULL $temp  ORDER BY intent.id DESC");
//         return $query->num_rows();
//     }
//     function intent_list($srch=null,$one=null,$two=null){
//          $temp ="";
//  if($srch['indent_fk'] !=''){
//             $temp .=' AND intent.indent_fk ="'.$srch['indent_fk'].'"';
//         }
//         if($srch['category_fk'] !=''){
//             $temp .=' AND intent.category_fk ="'.$srch['category_fk'].'"';
//         }
//         if($srch['quantity'] !=''){
//             $temp .=' AND intent.quantity ="'.$srch['quantity'].'"';
//         }
//           $query = $this->db->query("SELECT intent.*,it.id as tid ,it.reagent_name as ItemName FROM inventory_intent_request AS intent LEFT JOIN inventory_item AS it ON it.id = intent.category_fk WHERE intent.status = '1' and it.reagent_name !='' and it.reagent_name IS NOT NULL $temp  ORDER BY intent.id DESC LIMiT $two,$one");
// $data['user'] = $query->result_array();
//         return $data['user'];
//     }

    function intent_num($id = null, $branch_fk = null, $start_date = null, $status = null) {
        $temp = "";
        if ($id != '') {
            $temp .=' AND iim.intent_id ="' . $id . '"';
        }

        if ($branch_fk != '') {
            $temp .=' AND iim.branch_fk ="' . $branch_fk . '"';
        }
        if ($start_date != '') {
            $start_date = explode('/', $start_date);
            $new_data = $start_date[2] . '-' . $start_date[1] . '-' . $start_date[0];
            $subtime = $new_data . ' 00:00:00';
            $ltime = $new_data . ' 23:59:59';

            $temp .=' AND ir.created_date >="' . $subtime . '" and ir.created_date <="' . $ltime . '"';
        }
        if ($status != '') {
            $temp .=' AND iim.status ="' . $status . '"';
        }


        $query = $this->db->query("SELECT 
  ir.*,
  iim.intent_id,
  iim.status as iiSTATUS,
   iim.branch_fk as BranchId, 
  br.branch_name,
  am.name as admin_name
FROM
  `inventory_intent_master` AS iim 
  LEFT JOIN `inventory_intent_request` AS ir 
    ON iim.`intent_id` = ir.`indent_fk` 
    AND ir.`status` = '1' 
  LEFT JOIN branch_master AS br 
    ON br.id = iim.branch_fk
    and br.status='1'

    left join admin_master as am on am.id = iim.created_by and am.status='1'   
WHERE 
 iim.status != '0'  $temp
ORDER BY iim.id");

        return $query->num_rows();
    }

    // 

    function intent_list($id = null, $branch_fk = null, $start_date = null, $status = null, $user_branch = null, $one = null, $two = null) {
        $temp = "";
        if ($id != '') {
            $temp .=' AND iim.intent_id ="' . $id . '"';
        }

        if ($branch_fk != '') {
            $temp .=' AND iim.branch_fk ="' . $branch_fk . '"';
        }
        if ($start_date != '') {
            $start_date = explode('/', $start_date);
            $new_data = $start_date[2] . '-' . $start_date[1] . '-' . $start_date[0];
            $subtime = $new_data . ' 00:00:00';
            $ltime = $new_data . ' 23:59:59';

            $temp .=' AND ir.created_date >="' . $subtime . '" and ir.created_date <="' . $ltime . '"';
        }
        if ($status != '') {
            $temp .=" AND iim.status  in ($status)";
        }
        if (!empty($user_branch)) {
            $temp .= ' AND iim.branch_fk in (' . $user_branch . ')';
        }

        $query = $this->db->query("SELECT 
  ir.*,
  iim.intent_id,
   iim.status as iiSTATUS,
   iim.branch_fk as BranchId,iim.postatus, 
  br.branch_name,
  am.name as admin_name,iim.i_notiy,iim.a_notity,iim.b_notity
FROM
  `inventory_intent_master` AS iim 
  LEFT JOIN `inventory_intent_request` AS ir 
    ON iim.`intent_id` = ir.`indent_fk` 
    AND ir.`status` = '1' 
  LEFT JOIN branch_master AS br 
    ON br.id = iim.branch_fk
    and br.status='1'

    left join admin_master as am on am.id = iim.created_by and am.status='1'   
WHERE ir.status = '1' 
  AND iim.status != '0'  $temp GROUP BY  ir.`indent_fk` 
ORDER BY iim.id DESC   LIMiT $two,$one");

        $data['user'] = $query->result_array();
        
        return $data['user'];
    }

    function get_print($indent_fk = null, $status = null) {

        $query = $this->db->query("SELECT 
  iir.*,
  it.reagent_name AS ReagentName,
  ic.name AS CategoryName,
  iir.quantity AS Quantity,
  pa.name AS 'admin_name_created',
    pb.name AS 'admin_name_approve',
  br.branch_name,
  inventory_unit_master.name AS UnitName 
FROM
  inventory_intent_master AS iim 
  LEFT JOIN `inventory_intent_request` AS iir 
    ON iim.intent_id = iir.indent_fk 
    AND iir.status = '1' 
  LEFT JOIN inventory_item AS it 
    ON it.id = iir.category_fk 
    AND it.status = '1' 
  LEFT JOIN inventory_category AS ic 
    ON ic.id = it.category_fk  
    AND ic.status = '1' 
    LEFT JOIN admin_master AS  pa ON (iir.created_by = pa.id)
LEFT JOIN admin_master pb ON (iim.modified_by = pb.id)
  LEFT JOIN branch_master AS br 
    ON br.id = iim.branch_fk 
    AND br.status = '1' 
  LEFT JOIN inventory_unit_master 
    ON inventory_unit_master.id = it.unit_fk 
    AND inventory_unit_master.status = '1' 
WHERE it.reagent_name != '' 
  AND it.reagent_name IS NOT NULL 
  AND iim.intent_id='" . $indent_fk . "'
  AND iim.status ='" . $status . "'
  AND iim.status != '0' 
ORDER BY iir.id DESC ");

        $data['user'] = $query->result_array();

        return $data['user'];
    }

}

?>
