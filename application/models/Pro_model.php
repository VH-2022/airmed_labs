<?php

Class Pro_model extends CI_Model{
		
	public function manage_condition_view($relid, $one, $two) 
	{
		
        $query ="SELECT * FROM `pro_master` WHERE 
									`pro_status`='1' ";

		if ($relid != "") 
		{
			$query .= " AND pro_name LIKE '%".$relid."%'"; 
		}
		 
				$query .= " ORDER BY id DESC limit $two,$one";
		$query = $this->db->query($query);
        return $query->result_array();
    }
	
		
	function manage_view_list($one, $two) 
	{

        $query = $this->db->query("SELECT * FROM `pro_master` WHERE `pro_status`='1' ORDER BY id DESC limit $two,$one" );

        return $query->result_array();
    }
	
    public function master_get_tbl_val($dtatabase, $condition, $order) 
	{
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($dtatabase, $condition);
        return $query->result_array();
        //$data['user'] = $query->result_array();
        //return $data['user'];
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

    public function num_rows($table)
	{
        $query= $this->db->get($table);
        return $query->num_rows(); 
    }

	public function master_get_delete($tablename, $cid) 
	{
        $this->db->where($cid[0], $cid[1]);
        $this->db->delete($tablename);
        return 1;
    }
	
	public function master_get_relation($table,$condition)
	{
		$query = $this->db->get_where($table,$condition);
		$query = $this->db->query($query);
		return $query->result_array();
	}
    public function checkUniquePhone($phone, $id="")
    {
      
        $db1 = $this->load->database("default", TRUE);
        $db1->select('*');
        $db1->from('pro_master');
        if ($id != "") {
            $db1->where('id !=', $id);
        }
        $db1->where('pro_mobile', $phone);
        $db1->where('pro_status', "1");
        // $db11->where('status', 1);
        $result = $db1->get();
        return $result->num_rows() > 0;
    }

    public function checkUniqueEmail($email, $id="")
    {
      
        $db1 = $this->load->database("default", TRUE);
        $db1->select('*');
        $db1->from('pro_master');
        if ($id != "") {
            $db1->where('id !=', $id);
        }
        $db1->where('pro_email', $email);
        $db1->where('pro_status', "1");
        // $db11->where('status', 1);
        $result = $db1->get();
        return $result->num_rows() > 0;
    }
}
?>