<?php

Class Doctor_s_report_model extends CI_Model {
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
public function doctor_list($limit,$start,$doctor){
	$this->db->select('d.id,d.`full_name`');
	$this->db->from('doctor_master d');
	$this->db->where('d.status','1');
	if($doctor != ""){  $this->db->where_in('d.id',explode(",",$doctor));  }
	$this->db->limit($limit,$start);
	$query = $this->db->get();
	return $query->result();

}
public function doctor_listnum($doctor){
	
	$this->db->select('d.id');
	$this->db->from('doctor_master d');
	$this->db->where('d.status','1');
	if($doctor != ""){  $this->db->where_in('d.id',explode(",",$doctor));  }
	$query = $this->db->get();
	return $query->num_rows();

}
public function doctorjob_get($docid,$branchuser=null){
	
$start_date=$this->input->get('start_date');
$end_date=$this->input->get('end_date');
$branch=$this->input->get('branch');
$doctor=$this->input->get('doctor');
$credtejobuser=implode(",",$this->input->get('credtejobuser'));

	$this->db->select('GROUP_CONCAT(j.id) AS tjobs,SUM(j.price) AS bitotal,SUM(j.payable_amount) AS dueamount,ROUND(SUM(j.price * j.discount / 100)) AS discount');
	$this->db->from('job_master j');
	/*$this->db->join("job_master_receiv_amount r","r.job_fk=j.id and r.status='1'");*/
	/* $this->db->join("wallet_master w","w.job_fk=j.id and w.status='1'"); */
	$this->db->where('j.status !=','0');
	$this->db->where('j.model_type','1');
	
	$this->db->where('j.doctor',$docid);
	if ($start_date != "") {
		
$this->db->where("STR_TO_DATE(j.date,'%Y-%m-%d') >=", date("Y-m-d", strtotime($start_date)));	
	
	}
if ($end_date != "") {
$this->db->where("STR_TO_DATE(j.date,'%Y-%m-%d') <=",date("Y-m-d", strtotime($end_date)));	
}
if($branchuser != ""){  $this->db->where_in('j.branch_fk',explode(",",$branchuser)); }
if($branch != ""){  $this->db->where_in('j.branch_fk',explode(",",$branch));  }
if($doctor != ""){ $this->db->where_in('j.doctor',explode(",",$doctor));  }
if($credtejobuser != ""){ $this->db->where_in('j.added_by',explode(",",$credtejobuser));  }
	$query = $this->db->get();
	/*  echo $this->db->last_query(); */
	return $query->row();

}

public function jobpaidamount_get($jobid){
	
	$this->db->select('SUM(j.amount) AS paidamount');
	$this->db->from('job_master_receiv_amount j');
	$this->db->where('j.status','1');
	/* $this->db->where_in('j.payment_type',array("CREDIT CARD","CREDIT CARD swiped thru ICICI","WALLET CREDIT CARD swiped thru MSWIP","DEBIT CARD swiped thru ICICI","DEBIT CARD swiped thru MSWIP","Swipe thru HDFC","Swipe thru AXIS","DEBIT CARD","PayTm","PAYTM",'CASH')); */
	$this->db->where_in('j.job_fk',explode(",",$jobid));
	
	$query = $this->db->get();
	$this->db->last_query();
	return $query->row();
	

}
public function jobwalletamount_get($jobid){
	
	$this->db->select('SUM(debit) AS debit');
	$this->db->from('wallet_master');
	$this->db->where('status','1');
	$this->db->where_in('job_fk',explode(",",$jobid));
	$query = $this->db->get();
	$this->db->last_query();
	return $query->row();

}
public function jobcreditoramount_get($jobid){
	
	$this->db->select('SUM(debit) AS debit');
	$this->db->from('creditors_balance');
	$this->db->where('status','1');
	$this->db->where_in('job_id',explode(",",$jobid));
	$query = $this->db->get();
	$this->db->last_query();
	return $query->row();

}


}

?>
