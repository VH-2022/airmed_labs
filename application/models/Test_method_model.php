<?php

Class Test_method_model extends CI_Model {

    function get_master_get_data($name, $condition, $order) {
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($name, $condition);
        return $query->result_array();
    }

    function master_update_data($name, $condition, $order) {
        //print_r($condition); die();
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($name, $condition);
        return $query->result_array();
    }

    public function master_fun_get_tbl_val($dtatabase, $condition, $order) {
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($dtatabase, $condition);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function master_fun_insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function master_fun_update($tablename, $cid, $data) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function fetchdatarow($selact, $table, $array) {
        $this->db->select($selact);
        $query = $this->db->get_where($table, $array);
        return $query->row();
    }

    public function num_row($table, $condition) {
        $query = $this->db->get_where($table, $condition);
        return $query->num_rows();
    }

    public function contact_master($table_name, $data) {

        $this->db->insert($table_name, $data);
        return $this->db->insert_id();
    }

    public function master_fun_delete($tablename, $cid) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->delete($tablename);
        return 1;
    }

    function test_list($one, $two) {
        $query = $this->db->query("SELECT *,tm.id As test_id,tm.method As test_method FROM test_method tm
                 INNER JOIN `test_master` t ON tm.test_fk = t.id
                 INNER JOIN `branch_master` b ON tm.branch_fk = b.id
                 WHERE tm.`status`='1' AND t.status = '1' AND b.status = '1' $temp ORDER BY t.test_name ASC LIMIT $two,$one");

        return $query->result_array();
    }

    function get_val($squery) {
        $query = $this->db->query($squery);
        return $query->result_array();
    }

    function test_list_search($branch, $test, $one, $two) {
        $temp = "";
        if ($branch != "") {
            $temp .= " AND b.id = '$branch'";
        }

        if ($test != "") {
            $temp .= " AND t.test_name LIKE '%$test%'";
        }

        $query = $this->db->query("SELECT *,tm.id As test_id,tm.method As test_method FROM test_method tm
                 INNER JOIN `test_master` t ON tm.test_fk = t.id
                 INNER JOIN `branch_master` b ON tm.branch_fk = b.id
                 WHERE tm.`status`='1' AND t.status = '1' AND b.status = '1' $temp ORDER BY t.test_name ASC LIMIT $two,$one");

        return $query->result_array();
    }

    function test_list_search_num($branch, $test) {
        $temp = "";
        if ($branch != "") {
            $temp .= " AND b.id = $branch";
        }

        if ($test != "") {
             $temp .= " AND t.test_name LIKE '%$test%'";
        }

        $query = $this->db->query("SELECT *,tm.id As test_id,tm.method As test_method FROM test_method tm
                 INNER JOIN `test_master` t ON tm.test_fk = t.id
                 INNER JOIN `branch_master` b ON tm.branch_fk = b.id
                 WHERE tm.`status`='1' AND t.status = '1' AND b.status = '1' $temp ORDER BY t.test_name ASC");

        return $query->num_rows();
    }

}

?>
