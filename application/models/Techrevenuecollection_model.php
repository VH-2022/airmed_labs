<?php

Class Techrevenuecollection_model extends CI_Model{
	
	public function manage_condition_view($srchdata, $one, $two) 
	{
		$query = "SELECT trcm.*,bm.branch_name FROM `tech_revenue_collection_master` AS trcm JOIN `branch_master` AS bm ON bm.id = trcm.branch_fk WHERE trcm.status = 1";
		
		if ($srchdata['collectiontype'] != ""){
			$query .= " AND trcm.collection_type = '".$srchdata['collectiontype']."'";
		}
		
		if ($srchdata['search'] != ""){
			$query .= " AND bm.branch_name LIKE '%".$srchdata['search']."%' OR trcm.collection_value LIKE '%".$srchdata['search']."%'"; 
		}
		
		$query .= " ORDER BY trcm.id DESC limit $two,$one";
		$query = $this->db->query($query);
        return $query->result_array();
    }
	
	public function manage_condition_viewlogs($srchdata, $one, $two) 
	{
		$query = "SELECT trcml.*,bm.branch_name FROM `tech_revenue_collection_master_log` AS trcml JOIN `branch_master` AS bm ON bm.id = trcml.branch_fk WHERE trcml.status = 1";
		
		if($srchdata['techrevenue_id']!=""){
			$query .= " AND trcml.techrevenue_id = '".$srchdata['techrevenue_id']."'";
		}
		
		if ($srchdata['collectiontype'] != ""){
			$query .= " AND trcml.collection_type = '".$srchdata['collectiontype']."'";
		}
		
		if ($srchdata['search'] != ""){
			$query .= " AND bm.branch_name LIKE '%".$srchdata['search']."%' OR trcml.collection_value LIKE '%".$srchdata['search']."%'"; 
		}
		
		$query .= " ORDER BY trcml.id DESC limit $two,$one";
		$query = $this->db->query($query);
        return $query->result_array();
    }
	
	function get_val($query1 = null) {
        $query = $this->db->query($query1);
        $data['user'] = $query->result_array();
        return $data['user'];
    }
	
	public function master_get_tbl_val($dtatabase, $condition, $order) 
	{
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($dtatabase, $condition);
        $data['user'] = $query->result_array();
        return $data['user'];
    }
	
	public function master_get_insert($table, $data) 
	{
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }
	
	public function master_get_update($tablename, $cid, $data) 
	{
        $this->db->where($cid);
		$this->db->update($tablename, $data);
        return 1;
    }
	
	public function num_row($table,$condition)
	{
        $query= $this->db->get_where($table,$condition);
        return $query->num_rows(); 
    }
}
?>