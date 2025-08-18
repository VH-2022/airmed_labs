<?php

class Doctor_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    
    public function master_fun_get_tbl_val($dtatabase, $condition, $order, $limit = null) {
        $this->db->order_by($order[0], $order[1]);
        if ($limit != null) {
            $this->db->limit($limit[0], $limit[1]);
        }
        $query = $this->db->get_where($dtatabase, $condition);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function master_fun_insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function master_fun_update($tablename, $condition, $data) {
        $this->db->where($condition);
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

    public function get_val($qry) {
        $query1 = $this->db->query($qry);
        $data['user'] = $query1->result_array();
        return $data['user'];
    }
	public function fetchdatarow($selact,$table,$array){
		          $this->db->select($selact); 
        $query = $this->db->get_where($table,$array);
        return $query->row();
    }

}

?>
