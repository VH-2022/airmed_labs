<?php

Class Auto_approve_test_model extends CI_Model {

    public function num_row($branchid, $email = null) {
        $query = "select ap.id,tm.test_name,ap.status,ap.test_fk 
                from auto_approve_test ap 
                LEFT JOIN test_master tm on ap.test_fk = tm.id AND tm.status = '1'
                WHERE 1=1 
                ";

        if ($test) {
            $query .= " AND tm.test_name LIKE '%$test%'";
        }
        $query .= " ORDER BY ap.id DESC ";
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

    public function sub_list($test = null, $one = null, $two = null) {
		
        $query = "select ap.id,tm.test_name,ap.status,ap.test_fk
                from auto_approve_test ap 
                INNER JOIN test_master tm on ap.test_fk = tm.id AND tm.status = '1'
                WHERE 1=1 
                ";

        if ($test) {
            $query .= " AND tm.test_name LIKE '%$test%'";
        }
        $query .= " ORDER BY ap.id DESC LIMIT $two, $one";        
        $query = $this->db->query($query);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function get_val($query1) {
        $query = $this->db->query($query1);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

}
