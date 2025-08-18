<?php

Class Recivepayment_modal extends CI_Model {
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
	public function get_val($query1 = null) {
        $query = $this->db->query($query1);

        $data['user'] = $query->result();
        return $data['user'];
    }
public function payments_listnum($laballsea,$startdate,$enddate,$labid=null){
	
	$this->db->select('s.id');
	$this->db->from('sample_receive_payment s');

	$this->db->where('s.status','1');
	if($laballsea[0] != ""){ 
	$this->db->where_in('s.lab_fk',$laballsea); 
	}
	if($startdate != ""){
		/* $startdateex = explode('/',$startdate);
                $startdatese = $startdateex[2]."-".$startdateex[1]."-".$startdateex[0]; */
		        $this->db->where("STR_TO_DATE(s.pay_date,'%Y-%m-%d') >=",date("Y-m-d",strtotime($startdate)));
	}
	if($enddate != ""){
		/* $enddateex = explode('/',$enddate);
		$enddatese = $enddateex[2]."-".$enddateex[1] . "-".$enddateex[0]; */
		$this->db->where("STR_TO_DATE(s.pay_date,'%Y-%m-%d') <=",date("Y-m-d",strtotime($enddate)));
	}
	if($labid != ""){ 
	$laball=explode(",",$labid);
	$this->db->where_in('s.lab_fk',$laball); 
	}
	$query = $this->db->get();
	 
	return $query->num_rows();
	
}
public function payments_list($limit, $start,$laballsea,$startdate,$enddate,$labid=null){
	
	$this->db->select('s.id,s.month,s.pay_date,s.year,s.amount,s.note,s.created_date,s.type,a.name,c.name as labname');
	$this->db->from('sample_receive_payment s');
	$this->db->join("admin_master a","a.id=s.created_by",'left');
	$this->db->join("collect_from c","c.id=s.lab_fk",'left');
	$this->db->where('s.status','1');
	if($laballsea[0] != ""){ 
	$this->db->where_in('s.lab_fk',$laballsea); 
	}
	if($startdate != ""){
		/* $startdateex = explode('/',$startdate);
                $startdatese = $startdateex[2]."-".$startdateex[1]."-".$startdateex[0]; */
		        $this->db->where("STR_TO_DATE(s.pay_date,'%Y-%m-%d') >=",date("Y-m-d",strtotime($startdate)));
	}
	if($enddate != ""){
		/* $enddateex = explode('/',$enddate);
		$enddatese = $enddateex[2]."-".$enddateex[1] . "-".$enddateex[0]; */
		$this->db->where("STR_TO_DATE(s.pay_date,'%Y-%m-%d') <=",date("Y-m-d",strtotime($enddate)));
	}
	if($labid != ""){ 
	$laball=explode(",",$labid);
	$this->db->where_in('s.lab_fk',$laball); 
	}
	$this->db->order_by('s.id','desc');
	$this->db->limit($limit,$start);
	$query = $this->db->get();
	   $this->db->last_query(); 
	return $query->result();
	
}	
public function payments_listexport($labid=null){
	$this->db->select('s.id,s.month,s.year,s.amount,s.note,s.created_date,s.type,a.name,c.name as labname');
	$this->db->from('sample_receive_payment s');
	$this->db->join("admin_master a","a.id=s.created_by",'left');
	$this->db->join("collect_from c","c.id=s.lab_fk",'left');
	$this->db->where('s.status','1');
	if($labid != ""){ 
	$laball=explode(",",$labid);
	$this->db->where_in('s.lab_fk',$laball); 
	}
	$this->db->order_by('s.id','desc');
	
	/* $this->db->limit($limit,$start); */
	$query = $this->db->get();
	return $query->result();
	
}	
public function getlab_amount($labid=null,$date=null){
	
	 $query = $this->db->query("SELECT SUM(sample_job_master.`payable_amount`) AS amount FROM
  `sample_job_master` 
  LEFT JOIN `logistic_log` 
    ON logistic_log.`id` = `sample_job_master`.`barcode_fk` 
WHERE logistic_log.`status` = 1 
  AND `logistic_log`.`collect_from` = '$labid'  ");
  
/*and DATE_FORMAT(logistic_log.scan_date,'%Y-%m-%d') <= $date*/
  return $query->row();
 
}
public function getlab_reciveamount($labid=null,$date=null){
	
$query = $this->db->query("SELECT 
    SUM(amount) AS recive
  FROM
    sample_receive_payment AS r 
  WHERE r.lab_fk ='$labid' AND r.`status`='1' ");

  return  $query->row();
 
}

}

?>