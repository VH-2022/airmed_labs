<?php

Class Stationary_model extends CI_Model {

    public function manage_condition_view($relid, $one, $two) {

        $query = "SELECT * FROM `inventory_statonary_master` WHERE `status`='1' ";

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

        if ($srchdata['name'] != '') {
            
            $name = $srchdata['name'];

            $temp .= " AND (it.reagent_name LIKE '%" . $name . "%')";
        }

          if ($srchdata['unit_name'] != '') {
            
            $unit_name = $srchdata['unit_name'];

            $temp .= " AND it.unit_fk = '".$unit_name."'";
        }
           if ($srchdata['brand_name'] != '') {
            
            $brand_name = $srchdata['brand_name'];

            $temp .= " AND it.brand_fk = '".$brand_name."'";
        }

        $query = $this->db->query("SELECT it.* from inventory_item as it WHERE it.status ='1' and it.category_fk='1' $temp");
       
        return $query->num_rows();
    }

    public function getitem($srchdata = null, $one = null, $two = null) {
        $temp = "";

        if ($srchdata['name'] != '') {
            $name = $srchdata['name'];
            $temp .= " AND (inventory_item.reagent_name LIKE '%" . $name . "%' )";
        }

          if ($srchdata['unit_name'] != '') {
            
            $unit_name = $srchdata['unit_name'];

            $temp .= " AND inventory_item.unit_fk = '".$unit_name."'";
        }
if ($srchdata['brand_name'] != '') {
            
            $brand_name = $srchdata['brand_name'];

            $temp .= " AND inventory_item.brand_fk = '".$brand_name."'";
        }

        $query = $this->db->query("SELECT inventory_item.*,inventory_unit_master.name as UnitName,bm.brand_name as BrandName from inventory_item LEFT JOIN inventory_unit_master on inventory_unit_master.id =inventory_item.unit_fk and inventory_unit_master.status='1' LEFT JOIN inventory_brand as bm on bm.id = inventory_item.brand_fk and bm.status='1' WHERE inventory_item.status ='1' and inventory_item.category_fk='1' $temp  ORDER BY inventory_item.id DESC LIMIT $two,$one");

        return $query->result_array();
    }
    function get_num_rows($query1){
        $query = $this->db->query($query1);
        return $query->num_rows();
    }
    
    function get_val($query1){
        $query = $this->db->query($query1);
        $data['user'] = $query->result_array();
        return $data['user'];
    }
}
?>
