<?php
Class Admin_assign_branch_model extends CI_Model {
     public function num_row($branchid, $email = null) {
        $query = "select sr.*,br.branch_name from admin_assign_branch_mail as sr LEFT JOIN branch_master as br on br.id= sr.branch_fk and br.status='1' where sr.status='1'";
        if ($branchid != NULL) {
            $query .= " AND sr.branch_fk ='$branchid'";
        }
        if($email){
            $query .= " AND sr.email LIKE '%$email%'";
        }
        $query .= " ORDER BY sr.id DESC ";
        $query = $this->db->query($query);
        return $query->num_rows();
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
    
    public function sub_list($branchid=null, $email=null,$one=null,$two=null){
        $query = "select sr.*,br.branch_name from admin_assign_branch_mail as sr LEFT JOIN branch_master as br on br.id= sr.branch_fk and br.status='1' where sr.status='1'";

        if ($branchid != NULL) {
            $query .= " AND sr.branch_fk ='$branchid'";
        }
        if($email){
                $query .= " AND sr.email LIKE '%$email%'";
        }
        $query .= " ORDER BY sr.id DESC ";
        $query = $this->db->query($query);
        $data['user']=$query->result_array();
        return $data['user']; 
    }
function get_val($query1){
    $query = $this->db->query($query1);
    $data['user'] = $query->result_array();
    return $data['user'];
}
}