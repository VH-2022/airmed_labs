<?php

Class Health_advisor_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->database();
    }

    public function master_get_search($fullname, $one, $two) {
        $this->db->select('ID,phone,status');
        if ($fullname != "") {
            $this->db->like('phone', trim($fullname));
        }

        $this->db->where('status!=', '0');

        if ($_GET['status']) {
            $this->db->where('status', $_GET['status']);
        }

        $this->db->ORDER_BY('ID', 'desc');
        $this->db->limit($one, $two);
        $query = $this->db->get('health_advisor');

        return $query->result_array();
    }

    public function num_row($fullname) {

        $this->db->select('ID,phone,status');
        if ($fullname != "") {
            $this->db->like('phone', $fullname);
        }

        $this->db->where('status!=', '0');
        
        if ($_GET['status']) {
            $this->db->where('status', $_GET['status']);
        }
        $this->db->ORDER_BY('ID', 'desc');
        $query = $this->db->get('health_advisor');
        return $query->num_rows();
    }

    public function master_get_spam($tablename, $cid, $data) {
        $this->db->where(array('id' => $cid));
        $this->db->update($tablename, $data);
        return 1;
    }

    public function change_status($tablename, $cid, $data) {
        $this->db->where(array('id' => $cid));
        if ($data['status'] == '1') {
            $this->db->update($tablename, array('status' => '2'));
        } else {
            $this->db->update($tablename, array('status' => '1'));
        }
        return 1;
    }
    
    public function csv_export($phone = "", $status = "") {
        if ($phone != "") {
            $this->db->where('phone', trim($phone));
        }
        if ($status != "") {
            $this->db->where('status', $status);
        }
        $this->db->where('status!=', '0');
        $query = $this->db->get('health_advisor');
        $result = $query->result_array();
        return $result;
    }

    public function insert_data($tablename, $data) {
        $this->db->insert($tablename, $data);
        return $this->db->insert_id();
    }

    public function get_row($tablename, $id) {
        return $this->db->get_where($tablename, array('id' => $id))->row();
    }

    public function update_data($tablename, $id, $data) {
        $this->db->where('id', $id);
        $this->db->update($tablename, $data);
        return 1;
    }

}

?>
