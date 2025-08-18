<?php
Class Brand_model extends CI_Model {

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
	
	
	public function doctorlist($one, $two){
		$query = $this->db->query("SELECT * from inventory_brand where status =1 order by id LIMIT $two,$one");
		 $data['user'] = $query->result_array();
        return $data['user'];
	}
	public function doctorcount(){
		$query = $this->db->query("SELECT * from inventory_brand where status =1");
		 return $query->num_rows();
	}
	public function brandcount_list($srch){
	
		$temp = "";
		
		if($srch["name"] !=''){
			$temp .=' And brand_name LIKE "%'.$srch["name"].'%"';
		}
		
	
		
		$query = $this->db->query("SELECT * from inventory_brand  where status = 1 $temp");
	
		return $query->num_rows();
	}
	public function brandlist_list($srch,$one, $two){
		$temp = "";
			
		if($srch["name"] !=''){
			$temp .=' And brand_name LIKE "%'.$srch["name"].'%"';
		}
		
		
		$query = $this->db->query("SELECT * from inventory_brand  where status = 1 $temp order by id DESC LIMIT $two,$one");
		$data['user'] = $query->result_array();
        return $data['user'];
	}

	function csv_report($name){
		
			$temp = "";
		if($name != "" ){
			
			$temp .= " AND brand_name LIKE '%".$name."%' ";
		}
		

		$query = $this->db->query("SELECT * from inventory_brand  where status = 1 $temp order by id DESC");
		$data['user'] = $query->result_array();
        return $data['user'];
	}

	function get_val($query =null){
		$query1= $this->db->query($query);
		return $query1->result_array();
	}
}

?>
