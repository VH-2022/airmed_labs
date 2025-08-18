<?php

Class Threec_registermodel extends CI_Model {
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
public function get_jobreport($start_date=null,$end_date=null,$branch=null,$doctor=null,$credtejobuser=null,$branchuser=null){
	
	$this->db->select('j.id,j.date,j.price,c.full_name,c.address,j.branch_fk');
	$this->db->join("customer_master c","c.id=j.cust_fk","left");
	$this->db->from('job_master j');
	$this->db->where('j.status !=','0');
	$this->db->where('j.model_type','1');
	if ($start_date != "") {
	$this->db->where("STR_TO_DATE(j.date,'%Y-%m-%d') >=", date("Y-m-d", strtotime($start_date)));	
	}
if ($end_date != "") {
$this->db->where("STR_TO_DATE(j.date,'%Y-%m-%d') <=",date("Y-m-d", strtotime($end_date)));	
}
if($branchuser != ""){  $this->db->where_in('j.branch_fk',explode(",",$branchuser)); }
if($branch != ""){  $this->db->where_in('j.branch_fk',explode(",",$branch)); }
if($doctor != ""){ $this->db->where_in('j.doctor',explode(",",$doctor));  }
if($credtejobuser != ""){ $this->db->where_in('j.added_by',explode(",",$credtejobuser));  }

	$this->db->order_by("j.id",'asc');
    $query = $this->db->get();
	 
	return $query->result(); 
	
	

}
function get_job_booking_package($pid) {
        $query = $this->db->query("SELECT `book_package_master`.*,`package_master`.`title` FROM `book_package_master` INNER JOIN `package_master` ON `book_package_master`.`package_fk`=`package_master`.`id` WHERE `book_package_master`.`job_fk`='" . $pid . "'");
        $data['user'] = $query->result_array();
        $package_name = array();
        $tests = array();
        foreach ($data['user'] as $value) {
            $query1 = $this->db->query("SELECT id FROM `active_package` WHERE job_fk='" . $value["job_fk"] . "' AND package_fk='" . $value["package_fk"] . "' AND parent!='0'");
            $data['user1'] = $query1->result_array();
            $query12 = $this->db->query("SELECT `package_test`.`test_fk`,test_master.`test_name` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $value["package_fk"] . "'");
            $data['test_list'] = $query12->result_array();
           $package_name[] = array("name" => $value["title"], "test" => $data['test_list']);
            
        }
        if (!empty($package_name)) {
            return $package_name;
        } else {
            return "";
        }
    }
}

?>
