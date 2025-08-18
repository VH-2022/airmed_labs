<?php

Class Discountregister_model extends CI_Model {
    public function master_fun_insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }
public function updateRowWhere($table, $where, $data) {
    $this->db->where($where);
    $this->db->update($table, $data);
	return 1;
}
public function num_row($table,$condition){
        $query= $this->db->get_where($table,$condition);
        return $query->num_rows(); 
    }
public function master_fun_get_tbl_val($dtatabase,$select, $condition, $order) {
        $this->db->order_by($order[0], $order[1]);
        $this->db->select($select);
        $query = $this->db->get_where($dtatabase, $condition);
        return $query->result();
    }
public function fetchdatarow($selact,$table,$array){
		  $this->db->select($selact); 
        $query = $this->db->get_where($table,$array);
        return $query->row();
    }
function get_val($query1 = null) {
        $query = $this->db->query($query1);
        $data['user'] = $query->result_array();
        return $data['user'];
    }
function get_val1($query1 = null) {
        $query = $this->db->query($query1);
        $data['user'] = $query->result();
        return $data['user'];
    }	
public function get_jobreport($start_date=null,$end_date=null,$branch=null,$doctor=null,$credtejobuser=null,$branchuser){
	
	$this->db->select('j.id,j.date,j.price,ROUND(j.price * j.discount / 100) AS discountprice,j.discount,j.payable_amount,c.full_name,j.branch_fk,j.discount_note,a.name as operater');
	$this->db->join("customer_master c","c.id=j.cust_fk","left");
	$this->db->join("admin_master a","a.id=j.added_by","left");
	$this->db->from('job_master j');
	$this->db->where('j.status !=','0');
	$this->db->where('j.model_type','1');
	$this->db->where('j.discount !=','0');
	
	if ($start_date != "") {
	$this->db->where("STR_TO_DATE(j.date,'%Y-%m-%d') >=", date("Y-m-d", strtotime($start_date)));	
	}
if ($end_date != "") {
$this->db->where("STR_TO_DATE(j.date,'%Y-%m-%d') <=",date("Y-m-d", strtotime($end_date)));	
}
if($branch != ""){  $this->db->where_in('j.branch_fk',explode(",",$branch)); }
if($branchuser != ""){  $this->db->where_in('j.branch_fk',explode(",",$branchuser)); }
if($doctor != ""){ $this->db->where_in('j.doctor',explode(",",$doctor));  }
if($credtejobuser != ""){ $this->db->where_in('j.added_by',explode(",",$credtejobuser));  }

	
	$this->db->order_by("j.id",'asc');
    $query = $this->db->get();
	 
	return $query->result(); 
	
	

}
function discount_by($jid) {
	
$this->db->select('GROUP_CONCAT(DISTINCT a.name) AS disby');
$this->db->from('job_log l'); 
$this->db->join("admin_master a","a.id=l.updated_by","left");
$this->db->where('l.status','1');
$this->db->where('l.job_fk',$jid);
$this->db->group_by('l.job_fk');  
$query = $this->db->get();
	
return $query->row();  
    
	}
}

?>
