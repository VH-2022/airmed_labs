<?php

Class Doctor_timeslotmodel extends CI_Model {
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
        $data['user'] = $query->result();
        return $data['user'];
}
public function getappoiment($start_date=null,$end_date=null,$city=null,$doctor=null,$limit,$start,$apptype=null,$checkin=null){
	if($limit=='0' && $start=='0'){
	$this->db->select("b.id");
	}else{
		
		$this->db->select("b.id,b.`starttime`,b.`endtime`,b.`p_name`,b.`p_mobile`,b.`p_age`,b.type,b.checkin,b.status,d.`full_name`");
		
	}
	$this->db->from("doctorbook_slot b");
	$this->db->join("doctor_master d","d.id=b.`doctorfk`","INNER");
	$this->db->where("b.status !=",'0');
	if(!empty($doctor)){
$this->db->where("b.doctorfk",$doctor);
}
if(!empty($city)){
$this->db->where("d.city",$city);
}
if(!empty($start_date)) {
$this->db->where("DATE_FORMAT(b.starttime,'%Y-%m-%d') >=", date("Y-m-d", strtotime($start_date)));
}	
if (!empty($end_date)) {
$this->db->where("DATE_FORMAT(b.starttime,'%Y-%m-%d') <=",date("Y-m-d", strtotime($end_date)));	
}
if($apptype != ""){
	$this->db->where("b.type",$apptype);
}
if($checkin != ""){
	$this->db->where("b.checkin",$checkin);
}
$query = $this->db->get();
if($limit=='0' && $start=='0'){
 return $query->num_rows();
}else{
	return $query->result();
}

}

}

?>
