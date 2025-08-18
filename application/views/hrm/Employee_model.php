<?php

Class Employee_model extends CI_Model {

    function search_num($employee_id = "", $e_name = "", $phone = "", $status = "") {
        $temp = "";
        if ($employee_id != "") {
            $temp = " AND employee_id LIKE '%" . $employee_id . "%' ";
        }

        if ($e_name != "") {
            $temp .= " AND name LIKE '%" . $e_name . "%' ";
        }

        if ($phone != "") {
            $temp .= " AND phone LIKE '%" . $phone . "%' ";
        }

        if ($status != "") {
            $temp .= " AND status LIKE '%" . $status . "%' ";
        }

//        $query = $this->db->query("SELECT * FROM `hrm_employees` where status ='1' $temp ");
        $query = $this->db->query("SELECT * FROM `hrm_employees` where active IN('0','1') $temp ");
        return $query->num_rows();
    }

    function list_search($employee_id = "", $e_name = "", $phone = "", $status = "", $one, $two) {

        $dtime = date('d/m/Y');
        $temp = "";

        if ($employee_id != "") {
            $temp = " AND employee_id LIKE '%" . $employee_id . "%' ";
        }

        if ($e_name != "") {
            $temp .= " AND e.name LIKE '%" . $e_name . "%' ";
        }

        if ($phone != "") {
            $temp .= " AND phone LIKE '%" . $phone . "%' ";
        }

        if ($status != "") {
            $temp .= " AND e.status LIKE '%" . $status . "%' ";
        }

//        $query = $this->db->query("SELECT e.*,d.name as department,ds.name as designation FROM `hrm_employees` e left join hrm_department d on d.id=e.department left join hrm_designation ds on ds.id=e.designation where e.status ='1' $temp  ORDER BY e.id desc LIMIT $two,$one");
        $query = $this->db->query("SELECT e.*,d.name as department,ds.name as designation FROM `hrm_employees` e left join hrm_department d on d.id=e.department left join hrm_designation ds on ds.id=e.designation where e.active IN('1','0') $temp  ORDER BY e.id desc LIMIT $two,$one");
        return $query->result();
    }

    public function insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function get_one($dtatabase, $condition) {
        $query = $this->db->get_where($dtatabase, $condition);
        $result = $query->row();
        return $result;
    }

    public function get_all($dtatabase, $condition) {
        $query = $this->db->get_where($dtatabase, $condition);
        $result = $query->result();
        return $result;
    }

    public function update($tablename, $cid, $data) {
        $this->db->where($cid);
        $this->db->update($tablename, $data);
        return 1;
    }

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

    function getDesignation($postData) {
        $response = array();
        $this->db->select('id,name');
        $this->db->where('department_fk', $postData['did']);
        $q = $this->db->get('hrm_designation');
        $response = $q->result_array();
        return $response;
    }

    public function num_row($table, $condition) {
        $query = $this->db->get_where($table, $condition);
        return $query->num_rows();
    }

    function get_val($qry) {
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }

}

?>