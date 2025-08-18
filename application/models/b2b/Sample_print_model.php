<?php

Class Sample_print_model extends CI_Model {

    public function manage_condition_view($srchdata) {

      
    }

    public function manage_view_list($id) {
    
    }

    public function master_get_tbl_val($dtatabase, $condition, $order) {
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($dtatabase, $condition);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function master_get_insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function master_get_update($tablename, $cid, $data) {
        $this->db->where($cid);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function num_row($table, $condition) {
        $query = $this->db->get_where($table, $condition);
        return $query->num_rows();
    }

    public function num_rows($table) {
        $query = $this->db->get($table);
        return $query->num_rows();
    }

    public function master_get_delete($tablename, $cid) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->delete($tablename);
        return 1;
    }

    public function master_get_relation($table, $condition) {
        $query = $this->db->get_where($table, $condition);
        $query = $this->db->query($query);
        return $query->result_array();
    }

    function duplicate_val($duplicate) {
        $query = $this->db->query($duplicate);
        return $query->result_array();
    }

    public function expenselist_list($srchdata, $one, $two) { 
      
      
     
        
               
    }
 public function master_get_where_condtion($table, $cid, $order) {
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($table, $cid);
        return $query->result_array();
    }
    function get_master_get_data($name, $condition, $order) {
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($name, $condition);
        return $query->result_array();
    }

    

    function get_val($query1 = null) {
        $query = $this->db->query($query1);

        $data['user'] = $query->result_array();
        return $data['user'];
    }

   
   function sample_num($client=null,$yes=null){
    
    $temp='';
    if($client !=''){
$temp .=' And sample_client_print_report_permission.client_fk ="'.$client.'" ';
    }
    if($yes !=''){
        $temp .=' And sample_client_print_report_permission.print_report ="'.$yes.'" ';
    }
     $query = $this->db->query("SELECT * from sample_client_print_report_permission WHERE status ='1' $temp");
            
      // echo "<pre>";print_r($query);
        return $query->num_rows();
   }



   function sample_list($client,$yes,$one = null,$two = null){

    $temp='';
if($client !=''){
$temp .=' And sample_client_print_report_permission.client_fk ="'.$client.'" ';
    }
    if($yes !=''){
        $temp .=' And sample_client_print_report_permission.print_report ="'.$yes.'" ';
    }

     $query = $this->db->query("SELECT sample_client_print_report_permission.*,collect_from.name as ClientName  from sample_client_print_report_permission  LEFT JOIN collect_from on sample_client_print_report_permission.client_fk = collect_from.id and collect_from.status='1'  WHERE sample_client_print_report_permission.status ='1' $temp  ORDER BY sample_client_print_report_permission.id DESC limit $two,$one");

        return $query->result_array();
   }
 public function master_fun_update($tablename, $cid, $data) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->update($tablename, $data);
        return 1;
    }
}

?>
