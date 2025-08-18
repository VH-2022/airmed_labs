<?php

class Speciality_model extends CI_Model {

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

    function intent_num($id = null){
        $temp ="";
        if($id != ''){
            $temp .=' AND sp.name like "%'.$id.'%"';
        }


        $query = $this->db->query("SELECT sp.*,sp.id as IDS,sub.*,count(sub.test_fk) as TestID,  t.test_name from specility_wise_test as sp LEFT JOIN test_speciality as sub on sub.spec_fk = sp.id and sub.status='1' LEFT JOIN test_master as t on t.id = sub.test_fk where sp.status='1'  $temp ");

        return $query->num_rows();

    }
   // 

    function intent_list($id=null,$one=null,$two=null){
         $temp ="";
 if($id !=''){
            $temp .=' AND sp.name LIKE "%'.$id.'%"';
        }

     


         $query = $this->db->query("SELECT sp.*,sp.id as IDS,sub.*,count(sub.test_fk) as TestID,  t.test_name from specility_wise_test as sp LEFT JOIN test_speciality as sub on sub.spec_fk = sp.id and sub.status='1' LEFT JOIN test_master as t on t.id = sub.test_fk where sp.status='1'  $temp group by sub.spec_fk
ORDER BY sp.id DESC  LIMiT $two,$one");

$data['user'] = $query->result_array();
        return $data['user'];

    }

}

?>
