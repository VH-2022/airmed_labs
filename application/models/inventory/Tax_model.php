<?php

class Tax_model extends CI_Model {

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


     function intent_num($id =null,$tax =null,$city_fk =null){
         $temp ="";
         if($id !=''){
             $temp .=' and tm.title LIKE "%'.$id.'%"';
         }
         
         if($tax !=''){
             $temp .=' and tm.tax = "'.$tax.'"';
         }
          if($city_fk !=''){
             $temp .=' and tm.city_fk  ="'.$city_fk.'"';
         }
         
        $query = $this->db->query("Select tm.*,tc.name as cityName from inventory_tax_master as tm LEFT JOIN test_cities as tc on tc.city_fk = tm.city_fk and tc.status='1' where tm.status='1' $temp");
        return $query->num_rows();

    }
   

    function intent_list($id =null,$tax =null,$city_fk =null,$one=null,$two=null){ 
      $temp ="";
         if($id !=''){
             $temp .=' and tm.title LIKE "%'.$id.'%"';
         }
         
         if($tax !=''){
             $temp .=' and tm.tax ="'.$tax.'"';
         }
          if($city_fk !=''){
             $temp .=' and tm.city_fk = "'.$city_fk.'"';
         }
         
 
         $query = $this->db->query("Select tm.*,tc.name as cityName from inventory_tax_master as tm LEFT JOIN test_cities as tc on tc.city_fk = tm.city_fk and tc.status='1' where tm.status='1' $temp  ORDER BY tm.id desc LIMiT $two,$one");
         
$data['user'] = $query->result_array(); 
 
        return $data['user'];

    }

       function get_print($intent_id = null,$status = null){
 $temp ="";
 if($intent_id !=''){
            $temp .=' AND ir.intent_id ="'.$intent_id.'"'; 
        }

        if($status !=''){
            $temp .=' AND ir.status ="'.$status.'"';
        }
         $query = $this->db->query("SELECT 
  ir.*,it.reagent_name AS ReagentName,ic.name AS CategoryName,admin_master.name as admin_name,br.branch_name,inventory_unit_master.name as UnitName FROM `inventory_intent_request` AS ir 
  LEFT JOIN inventory_item AS it ON it.id = ir.category_fk AND it.status='1' LEFT JOIN inventory_category AS ic  ON ic.id = it.category_fk LEFT JOIN admin_master on admin_master.id=ir.created_by AND ic.status='1' LEFT JOIN  branch_master as br on br.id = ir.branch_fk and br.status='1' LEFT JOIN inventory_unit_master on inventory_unit_master.id =  it.unit_fk and inventory_unit_master.status='1' WHERE  it.reagent_name != '' AND it.reagent_name IS NOT NULL $temp ORDER BY ir.id DESC");

$data['user'] = $query->result_array(); 
 
        return $data['user'];
    }
}

?>
