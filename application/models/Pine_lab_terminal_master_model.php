<?php

Class Pine_lab_terminal_master_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->database();
    }

    public function master_get_search($fullname, $one, $two) {
        $this->db->select('id,name,imei_no,postcode,status');

        if ($fullname != "") {

            $this->db->like('name', trim($fullname));
        }
        if ($_GET['imei']) {
            $this->db->where('imei_no', $_GET['imei']);
        }

        $this->db->where('status!=', '0');

        $this->db->ORDER_BY('ID', 'desc');
        $this->db->limit($one, $two);
        $query = $this->db->get('pinelab_terminal_master');


        return $query->result_array();
    }

    public function num_row($fullname) {

        $this->db->select('id,name,imei_no');
        if ($fullname != "") {
            $this->db->like('name', trim($fullname));
        }

        if ($_GET['imei']) {
            $this->db->where('imei_no', $_GET['imei']);
        }

        $this->db->where('status!=', '0');

        $this->db->ORDER_BY('id', 'desc');
        $query = $this->db->get('pinelab_terminal_master');
//        echo $this->db->last_query();
//        die();

        return $query->num_rows();
    }

    public function master_get_spam($tablename, $cid, $data) {
        $this->db->where(array('id' => $cid));
        $this->db->update($tablename, $data);
        return 1;
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
