<?php

Class Notification_model extends CI_Model {

    public function manage_condition_view($srchdata) {

       $temp = "";
        if ($srchdata['title'] != "") {
            $title = $srchdata['title'];
            //echo "<prE>";print_r($id);die;
            $temp .= " AND title LIKE '%$title' ";
        }
 
      
      
        $query = $this->db->query("SELECT id,title,message FROM notification_sub_master  WHERE status=1  $temp");

        return $query->num_rows();
    }

    public function manage_view_list($id) {
    
    }

    public function master_get_tbl_val($dtatabase, $condition, $order) {
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($dtatabase, $condition);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

  
        public function master_fun_insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function master_get_update($tablename, $cid, $data) {
        $this->db->where($cid);
        $this->db->update($tablename, $data);
        return 1;
    }
  public function master_get_where_condtion($table, $cid, $order) {
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($table, $cid);
        return $query->result_array();
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

    public function notification_list($srchdata, $one, $two) { 
       $temp = "";

        if ($srchdata['title'] != "") {
            $title = $srchdata['title'];

            $temp .= " AND title LIKE '%$title%' ";
        }
         $query = $this->db->query("SELECT id,title,message from notification_sub_master WHERE status= 1  $temp order by id DESC  LIMIT $two,$one");

        //s$query = $this->db->query("SELECT ep.*,ec.name as CategoryName ,admin.name as AdminName,brn.branch_name FROM expense_master ep LEFT JOIN expense_category_master ec ON ep.expense_category_fk=ec.id LEFT JOIN admin_master as admin on admin.id = ep.created_by LEFT JOIN branch_master as brn on brn.id = ep.branch_fk WHERE ec.status=1 AND ep.status=1 $temp order by ep.id DESC  LIMIT $two,$one");
        
        $data['user'] = $query->result_array();
        return $data['user'];
       
    }

    function get_master_get_data($name, $condition, $order) {
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($name, $condition);
        return $query->result_array();
    }

    public function csv_report() {

        $query = "SELECT ep.*,ec.name,br.branch_name,am.name as AdminName   FROM `expense_master`ep LEFT JOIN expense_category_master ec ON ep.expense_category_fk = ec.`id` left join branch_master as br on br.id = ep.branch_fk left join admin_master as am on am.id = ep.created_by  where ep.status = 1";
      //  print_r($query);die;
        $query .= " ORDER BY ep.id DESC ";
        $result = $this->db->query($query)->result_array();

        return $result;
    }

    function get_val($query1 = null) {
        $query = $this->db->query($query1);

        $data['user'] = $query->result_array();
        return $data['user'];
    }

    

}

?>
