<?php

Class Leave_applications_model extends CI_Model {

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

    public function contact_master($table_name, $data) {

        $this->db->insert($table_name, $data);
        return $this->db->insert_id();
    }

    public function get_val($qry) {
        $query1 = $this->db->query($qry);
        $data['user'] = $query1->result_array();
        return $data['user'];
    }

    public function phlebocount_list($srch) {
        $temp = "";
        if ($srch['name'] != "") {
            $name = $srch['name'];
            $temp .= " AND am.name LIKE '%$name%' And lr.user_type='1'";
        }

        if ($srch['phlebo'] != "") {
            $name1 = $srch['phlebo'];
            $temp .= " AND pm.name LIKE '%$name1%' And lr.user_type='2'";
        }

        if ($srch['start_date'] != "") {
            $start_date = $srch['start_date'];
            $temp1 = explode('/', $start_date);
            $start_date = $temp1[2] . '-' . $temp1[1] . '-' . $temp1[0];

            $temp .= " AND lr.start_date >='$start_date' ";
        }
        if ($srch['end_date'] != "") {
            $end_date = $srch['end_date'];
            $temp1 = explode('/', $end_date);
            $end_date = $temp1[2] . '-' . $temp1[1] . '-' . $temp1[0];

            $temp .= " AND lr.start_date <= '$end_date' ";
        }
        if ($srch['user_info']->email != "brijesh@virtualheight.com") {
            $temp .= " AND lr.user_id='" . $srch['user_info']->id . "'";
        }

        $query = $this->db->query("SELECT lr.id,lr.user_id,lr.start_date,lr.user_type,
                 lr.end_date,lr.remark,lr.leave_status,lr.admin_remark,am.name as user_name,pm.name as phlebo_name
                 from leave_requests lr 
                 LEFT JOIN admin_master am on am.id = lr.user_id AND am.status='1' 
                 LEFT JOIN phlebo_master pm on pm.id = lr.user_id AND pm.status='1' 
                 where lr.status IN (1,2) $temp order by lr.id desc");
        return $query->num_rows();
    }

    public function phlebolist_list($srch, $one, $two) {

        $temp = "";
        if ($srch['name'] != "") {
            $name = $srch['name'];
            $temp .= " AND am.name LIKE '%$name%' And lr.user_type='1'";
        }

        if ($srch['phlebo'] != "") {
            $name1 = $srch['phlebo'];
            $temp .= " AND pm.name LIKE '%$name1%' And lr.user_type='2'";
        }

        if ($srch['start_date'] != "") {
            $start_date = $srch['start_date'];
            $temp1 = explode('/', $start_date);
            $start_date = $temp1[2] . '-' . $temp1[1] . '-' . $temp1[0];
            $temp .= " AND lr.start_date>='$start_date' ";
        }
        if ($srch['end_date'] != "") {
            $end_date = $srch['end_date'];
            $temp1 = explode('/', $end_date);
            $end_date = $temp1[2] . '-' . $temp1[1] . '-' . $temp1[0];
            $temp .= " AND lr.start_date<= '$end_date' ";
        }

        if ($srch['user_info']->email != "brijesh@virtualheight.com") {
            $temp .= " AND lr.user_id='" . $srch['user_info']->id . "'";
        }

        $query = $this->db->query("SELECT lr.id,lr.user_id,lr.start_date,lr.user_type,
                 lr.end_date,lr.remark,lr.leave_status,lr.admin_remark,am.name as user_name,pm.name as phlebo_name 
                 from leave_requests lr 
                 LEFT JOIN admin_master am on am.id = lr.user_id AND am.status='1' 
                 LEFT JOIN phlebo_master pm on pm.id = lr.user_id AND pm.status='1' 
                 where lr.status IN (1,2) $temp order by lr.id desc LIMIT $two,$one");
        $data['user'] = $query->result_array();

        return $data['user'];
    }

}

?>
