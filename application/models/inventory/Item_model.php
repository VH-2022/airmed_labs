<?php

Class Item_model extends CI_Model {

    public function manage_condition_view($relid, $one, $two) {

        $query = "SELECT * FROM `inventory_item` WHERE `status`='1' ";

        if ($relid != "") {
            $query .= " AND name LIKE '%" . $relid . "%'";
        }

        $query .= " ORDER BY id DESC limit $two,$one";
  
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

            $temp .= " AND (item.reagent_name LIKE '%" . $name . "%' OR im.name LIKE '%" . $name . "%' OR item.remark LIKE '%" . $name . "%')";
        }
         if ($srchdata['price'] != '') {

            $price = $srchdata['price'];

            $temp .= " AND item.box_price ='". $price."'";
        }
        if ($srchdata['hsn_code'] != '') {

            $hsn_code = $srchdata['hsn_code'];

            $temp .= " AND item.hsn_code ='". $hsn_code."'";
        }
        // $query = $this->db->query("SELECT item.*,im.id as mid,im.name as MachineName from inventory_item as item left join inventory_machine as im on im.id = item.machine WHERE item.status ='1' and im.status ='1' and item.machine !='' and item.machine !=0 and item.category_fk='3' $temp");

          $query = $this->db->query("SELECT item.*,im.id as mid,im.name as MachineName,ib.brand_name as BrandName,ium.name as UnitName from inventory_item as item left join inventory_machine as im on im.id = item.machine LEFT JOIN inventory_brand as ib on ib.id = item.brand_fk and ib.status='1' LEFT JOIN inventory_unit_master as ium on ium.id= item.unit_fk and ium.status='1' WHERE item.status ='1' and im.status ='1' and item.machine !='' and item.machine !=0 and item.category_fk='3' $temp");
      
        return $query->num_rows();
    }

    public function getitem($srchdata = null, $one = null, $two = null) {
        $temp = "";

        if ($srchdata['item_name'] != '') {
            $name = $srchdata['item_name'];
            $temp .= " AND (item.reagent_name LIKE '%" . $name . "%' OR im.name LIKE '%" . $name . "%' OR item.remark LIKE '%" . $name . "%')";
        }

        if ($srchdata['price'] != '') {

            $price = $srchdata['price'];

            $temp .= " AND item.box_price ='". $price."'";
        }
        if ($srchdata['hsn_code'] != '') {

            $hsn_code = $srchdata['hsn_code'];

            $temp .= " AND item.hsn_code ='". $hsn_code."'";
        }

        // $query = $this->db->query("SELECT item.*,im.id as mid,im.name as MachineName from inventory_item as item left join inventory_machine as im on im.id = item.machine WHERE item.status ='1' and im.status ='1' and item.machine !='' and item.machine !=0 and item.category_fk='3' $temp  ORDER BY item.id DESC LIMIT $two,$one");
  $query = $this->db->query("SELECT item.*,im.id as mid,im.name as MachineName ,ib.brand_name as BrandName,ium.name as UnitName from inventory_item as item left join inventory_machine as im on im.id = item.machine   LEFT JOIN inventory_brand as ib on ib.id = item.brand_fk and ib.status='1' LEFT JOIN inventory_unit_master as ium on ium.id= item.unit_fk and ium.status='1' WHERE item.status ='1' and im.status ='1' and item.machine !='' and item.machine !=0 and item.category_fk='3' $temp  ORDER BY item.id DESC LIMIT $two,$one");

        return $query->result_array();
    }

}

?>
