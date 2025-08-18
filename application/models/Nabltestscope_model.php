<?php

Class Nabltestscope_model extends CI_Model{
	
	public function manage_condition_view($srchdata, $one, $two) 
	{
		
       	/*$query = "SELECT rm.*, tm.test_name, tc.name, bm.branch_name, tm.test_name FROM (SELECT nm.* FROM `nabltestscope_master` AS nm WHERE nm.`status`='1') AS rm
		JOIN `test_cities` AS tc ON tc.id = rm.testcity_fk
		JOIN `branch_master` AS bm ON bm.id = rm.branch_fk
		JOIN `test_master` AS tm ON FIND_IN_SET(tm.id,rm.tests_fk)";*/
		
		$query = "SELECT rm.id,rm.testcity_fk,rm.branch_fk,rm.tests_fk,rm.test_scope,rm.status,rm.created_date,rm.created_by,rm.modified_date,rm.modified_by,rm.name,rm.branch_name,GROUP_CONCAT(rm.test_name) AS tests_name FROM (
		SELECT nm.*, tc.name, bm.branch_name, tm.test_name FROM `nabltestscope_master` AS nm
		JOIN `test_cities` AS tc ON tc.id = nm.testcity_fk
		JOIN `branch_master` AS bm ON bm.id = nm.branch_fk
		JOIN `test_master` AS tm ON FIND_IN_SET(tm.id,nm.tests_fk)
		WHERE nm.`status`='1'
		) AS rm GROUP BY rm.id HAVING rm.`status` = '1'";
		
		if ($srchdata['testscope'] != ""){
			$query .= " AND rm.test_scope = '".$srchdata['testscope']."'";
		}
		
		if ($srchdata['search'] != ""){
			$query .= " AND GROUP_CONCAT(rm.test_name) LIKE '%".$srchdata['search']."%' OR rm.name LIKE '%".$srchdata['search']."%' OR rm.branch_name LIKE '%".$srchdata['search']."%'";
		}
		
		$query .= " ORDER BY rm.id DESC limit $two,$one";
		$query = $this->db->query($query);
        return $query->result_array();
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