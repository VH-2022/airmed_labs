<?php

class Handover_item_model extends CI_Model {

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

  
    function master_get_search_num($branch,$user){
            $temp = "";
        if($branch !=''){
                    $temp .=' AND iu.branchfk="'.$branch.'"';
                }
        if($user !=''){
                    $temp .=' AND iu.handover_to="'.$user.'"';
                }
    
        
        $query = $this->db->query("SELECT br.branch_name,it.reagent_name, iu.*,am.name FROM inventory_usedreagent AS iu LEFT JOIN branch_master AS br ON br.id = iu.branchfk AND br.status = '1' LEFT JOIN inventory_item AS it ON it.id = iu.reaqgentfk AND it.status = '1' LEFT JOIN admin_master AS am ON am.id = iu.`handover_to`  AND am.`status`='1' WHERE iu.status = '1'   $temp");
    
        return $query->num_rows();
    }
    function master_get_search($branch=null,$user=null,$one=null,$two=null){
            $temp = "";
        if($branch !=''){
                    $temp .=' AND iu.branchfk="'.$branch.'"';
                }
                if($user !=''){
                    $temp .=' AND iu.handover_to="'.$user.'"';
                }
    
        $query = $this->db->query("SELECT br.branch_name,it.reagent_name, iu.*,am.name,ism.used FROM inventory_usedreagent AS iu LEFT JOIN branch_master AS br ON br.id = iu.branchfk AND br.status = '1' LEFT JOIN inventory_item AS it ON it.id = iu.reaqgentfk AND it.status = '1' LEFT JOIN admin_master AS am ON am.id = iu.`handover_to`  AND am.`status`='1' LEFT JOIN inventory_stock_master as ism on ism.id = iu.batchnuno and ism.status='1' WHERE iu.status = '1'  $temp order by iu.id DESC LIMIT $two,$one");
        $data['user'] = $query->result_array();
        return $data['user'];
    }
    
    public function csv_report($user_fk,$branch_fk) {
		$temp = "";
		if($user_fk !=''){
            $temp .=' AND iu.handover_to ="'.$user_fk.'"';
        }
		if($branch_fk !=''){
            $temp .=' AND iu.branchfk ="'.$branch_fk.'"';
        }
		
		$query = "SELECT  br.branch_name,it.reagent_name, iu.*,am.name,ism.quantity as Quantity,iu.quantity asIUQuantity,ism.batch_no as Batch FROM inventory_usedreagent AS iu LEFT JOIN branch_master AS br ON br.id = iu.branchfk AND br.status = '1' inner JOIN inventory_item AS it ON it.id = iu.reaqgentfk AND it.status = '1' LEFT JOIN admin_master AS am ON am.id = iu.`handover_to`  AND am.`status`='1' LEFT JOIN inventory_stock_master as ism on ism.id = iu.batchnuno and ism.status='1' WHERE iu.status = '1' AND it.category_fk in (1,2) $temp order by iu.id DESC";
		
		$result = $this->db->query($query)->result_array();
        return $result;
	}
    
}

?>
