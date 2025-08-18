<?php

Class Item_model extends CI_Model {

    public function manage_condition_view($relid, $one, $two) {

        $query = "SELECT * FROM `inventory_item` WHERE `status`='1' ";

        if ($relid != "") {
            $query .= " AND name LIKE '%" . $relid . "%'";
        }

        $query .= " ORDER BY id DESC limit $two,$one";
        echo $query;
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

        if ($srchdata['item_name'] != '') {

            $name = $srchdata['item_name'];

            $temp .= " AND (`name` LIKE '%" . $name . "%' OR `machine` LIKE '%" . $name . "%' OR `remark` LIKE '%" . $name . "%')";
        }
        $query = $this->db->query("SELECT * from inventory_item WHERE status ='1' $temp");
        return $query->num_rows();
    }

    public function getitem($srchdata = null, $one = null, $two = null) {
        $temp = "";

        if ($srchdata['item_name'] != '') {
            $name = $srchdata['item_name'];
            $temp .= " AND (`name` LIKE '%" . $name . "%' OR `machine` LIKE '%" . $name . "%' OR `remark` LIKE '%" . $name . "%')";
        }

        $query = $this->db->query("SELECT * from inventory_item WHERE status ='1' $temp  ORDER BY id DESC LIMIT $two,$one");

        return $query->result_array();
    }

}

?>
