<?php

Class Salary_struture_model extends CI_Model {

    function search_num($search = "") {
        $temp = "";
        if ($search != "") {
            $temp = " AND salary_strucure_name LIKE '%" . $search . "%' ";
        }

        $query = $this->db->query("SELECT id,salary_strucure_name FROM `hrm_master_salary_structure` where status!='0' $temp ");
        return $query->num_rows();
    }

    function list_search($search = "", $one, $two) {

        $temp = "";
        if ($search != "") {
            $temp = " AND salary_strucure_name LIKE '%" . $search . "%' ";
        }

        $query = $this->db->query("SELECT id,salary_strucure_name FROM `hrm_master_salary_structure` where status!='0' $temp  ORDER BY id desc LIMIT $two,$one");
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

    function getBranch($postData) {
        $response = array();
        $this->db->select('id,branch_name');
        $this->db->where('city', $postData['id']);
        $q = $this->db->get('branch_master');
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

    public function search($one, $two) {
        //$qry = "SELECT * FROM `phlebo_master` where status='1'";
//        $temp = "SELECT  MONTH(start_date) FROM `phlabo_timer`";
//        $temp1 = $this->db->query($temp);
//        $temp2 = $temp1->result_array();
//        echo "<pre>";
//        print_r($temp2);
//        exit;
//        $query1 = $this->db->query($qry1);
//        $query1 = $query1->result_array();
//        echo "<pre>";
//        print_r($query1);
//        exit;


        $qry = "SELECT `pm`.`id`,`pm`.`name`,`pm`.`start_time`,`pm`.`end_time`
                  FROM `phlabo_timer` pt
                  INNER JOIN `phlebo_master` pm ON `pt`.`user_fk`=`pm`.`id` 
                  WHERE pm.status = 1 
                  group by `pm`.`id` ORDER BY pm.id DESC
                  ";

//                  AND where start_date 
//        if ($fullname != "") {
//            $qry .= " AND `phlebo_master`.`name` LIKE '%" . $fullname . "%' ";
//        }
        //$qry .= " AND `phlabo_timer`.status='1' ORDER BY `phlabo_timer`.id DESC LIMIT $two,$one";

        $query = $this->db->query($qry);
        $data['user'] = $query->result_array();
//        echo "<pre>";
//        print_r($data['user']);
//        exit;
        return $data['user'];
    }

}

?>