<?php

Class Bank_model extends CI_Model {

    public function manage_condition_view($relid, $one, $two) {

        $query = "SELECT * FROM `inventory_bank` WHERE `status`='1' ";

        if ($relid != "") {
            $query .= " AND bank_name LIKE '%" . $relid . "%'";
        }

        $query .= " ORDER BY id DESC limit $two,$one";
        $query = $this->db->query($query);
        return $query->result_array();
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

    public function master_get_tbl_val($dtatabase, $condition, $order) {
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($dtatabase, $condition);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

     public function getitem_num($srchdata = null) {
        $temp = "";

        if ($srchdata['bank_name'] != '') {
            
            $name = $srchdata['bank_name'];
        $temp .= " AND bank_name LIKE '%" . $name . "%'";
        
        }

        if($srchdata['branch_name'] !=''){
     $branch_name = $srchdata['branch_name'];
        $temp .= " AND branch_name LIKE '%" . $branch_name . "%'";
        }
        if($srchdata['account_no'] !=''){
             $account_no = $srchdata['account_no'];
        $temp .= " AND account_no = '" . $account_no . "'";
        }
        if($srchdata['city'] !=''){
             $city = $srchdata['city'];
        $temp .= " AND city LIKE '%" . $city . "%'";
        }

         
        $query = $this->db->query("SELECT * from inventory_bank  WHERE status ='1' $temp");
      
        return $query->num_rows();
    }

    public function getitem($srchdata = null, $one = null, $two = null) {
        $temp = "";

        if ($srchdata['bank_name'] != '') {
            
            $name = $srchdata['bank_name'];
        $temp .= " AND bank_name LIKE '%" . $name . "%'";
        
        }

         if($srchdata['branch_name'] !=''){
     $branch_name = $srchdata['branch_name'];
        $temp .= " AND branch_name LIKE '%" . $branch_name . "%'";
        }
        if($srchdata['account_no'] !=''){
             $account_no = $srchdata['account_no'];
        $temp .= " AND account_no = '" . $account_no . "'";
        }
        if($srchdata['city'] !=''){
             $city = $srchdata['city'];
        $temp .= " AND city LIKE '%" . $city . "%'";
        }
        $query = $this->db->query("SELECT inventory_bank.* from inventory_bank WHERE inventory_bank.status ='1'  $temp  ORDER BY inventory_bank.id DESC LIMIT $two,$one");

        return $query->result_array();
    }

function check_dup($bank){
$query = $this->db->query("select * from inventory_bank where bank_name like'%".$bank."%' and status='1'");
 return $query->num_rows();
}
}

?>
