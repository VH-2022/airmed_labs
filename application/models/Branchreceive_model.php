<?php

Class Branchreceive_model extends CI_Model {
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
public function payments_list($limit,$start){
	
	$this->db->select('b.id,b1.branch_name,b.date,b.amount,b.transactionno,b.note,b.credteddate');
	$this->db->from('branch_payment b');
	$this->db->join("branch_master b1","b1.id=b.branch_fk",'left');
	$this->db->where('b.status','1');
	$this->db->limit($limit,$start);
	$query = $this->db->get();
	return $query->result();

}

public function getCashOfDay($branch,$startdate=null){
	
	
	if($startdate != "") { $start_date=date("Y-m-d",strtotime($startdate));$cstartdate="AND STR_TO_DATE(job_master_receiv_amount.createddate,'%Y-%m-%d') = '$start_date' "; }else{ $cstartdate=""; }
	
	
	$query = $this->db->query("SELECT GROUP_CONCAT(job_master.id) AS jobsid ,DATE(
        job_master_receiv_amount.`createddate`
      ) AS `date`,
     SUM(IF(
        DATE(
          job_master_receiv_amount.`createddate`
        ) = DATE(job_master.`date`),
         job_master_receiv_amount.`amount`,
        0
      )) AS   'SameDay',
      SUM(IF(
        DATE(
          job_master_receiv_amount.`createddate`
        ) = DATE(job_master.`date`),
       0,
        job_master_receiv_amount.`amount`
      )) AS 'BackDay'
    
    FROM
      job_master_receiv_amount 
      LEFT JOIN `job_master` 
        ON job_master.id = job_master_receiv_amount.`job_fk` 
        WHERE job_master.`branch_fk` IN($branch) AND job_master.`status`!='0' AND job_master_receiv_amount.`status`='1' AND job_master.`model_type`='1' $cstartdate $cenddate AND job_master_receiv_amount.payment_type in (" . implode(",", array("'CREDIT CARD'", "'CREDIT CARD swiped thru ICICI'", "'WALLET CREDIT CARD swiped thru MSWIP'", "'DEBIT CARD swiped thru ICICI'", "'DEBIT CARD swiped thru MSWIP'", "'Swipe thru HDFC'", "'Swipe thru AXIS'", "'DEBIT CARD'","'PayTm'","'PAYTM','CASH'")) . ") ");
		
		
	return $query->result(); 

}




public function getCashFromStartDay($branch,$startdate=null){
	
	
	if($startdate != "") { $start_date=date("Y-m-d",strtotime($startdate));$cstartdate="AND STR_TO_DATE(job_master_receiv_amount.createddate,'%Y-%m-%d') < '$start_date' "; }else{ $cstartdate=""; }
	
	
	$query = $this->db->query("SELECT GROUP_CONCAT(job_master.id) AS jobsid ,DATE(
        job_master_receiv_amount.`createddate`
      ) AS `date`,
     SUM(IF(
        DATE(
          job_master_receiv_amount.`createddate`
        ) = DATE(job_master.`date`),
         job_master_receiv_amount.`amount`,
        0
      )) AS   'SameDay',
      SUM(IF(
        DATE(
          job_master_receiv_amount.`createddate`
        ) = DATE(job_master.`date`),
       0,
        job_master_receiv_amount.`amount`
      )) AS 'BackDay'
    
    FROM
      job_master_receiv_amount 
      LEFT JOIN `job_master` 
        ON job_master.id = job_master_receiv_amount.`job_fk` 
        WHERE job_master.`branch_fk` IN ($branch) AND job_master.`status`!='0' AND job_master_receiv_amount.`status`='1' AND job_master.`model_type`='1' $cstartdate $cenddate AND job_master_receiv_amount.payment_type in (" . implode(",", array("'CREDIT CARD'", "'CREDIT CARD swiped thru ICICI'", "'WALLET CREDIT CARD swiped thru MSWIP'", "'DEBIT CARD swiped thru ICICI'", "'DEBIT CARD swiped thru MSWIP'", "'Swipe thru HDFC'", "'Swipe thru AXIS'", "'DEBIT CARD'","'PayTm'","'PAYTM','CASH'")) . ") ");
	 
	return $query->result(); 

}




public function getjobsto_branch($branch,$startdate=null,$enddate=null){
	
	/* $this->db->select('GROUP_CONCAT(DISTINCT j.id) as jobsid,j.date');
	$this->db->from('job_master j');
	$this->db->join("branch_master b1","b1.id=j.branch_fk",'left');
	$this->db->where('j.status !=','0');
	$this->db->where('j.model_type','1');
	
	$this->db->where('j.branch_fk',$branch);
	$this->db->GROUP_BY('DATE(j.date)');
	$query = $this->db->get();
	return $query->result(); */
	if($startdate != "") { $start_date=date("Y-m-d",strtotime($startdate));$cstartdate="AND STR_TO_DATE(job_master_receiv_amount.createddate,'%Y-%m-%d') >= '$start_date' "; }else{ $cstartdate=""; }
	if($enddate != ""){ $end_date=date("Y-m-d",strtotime($enddate)); $cenddate="AND STR_TO_DATE(job_master_receiv_amount.createddate,'%Y-%m-%d') <= '$end_date'";  }else{ $cenddate=""; }
	
	$query = $this->db->query("SELECT GROUP_CONCAT(job_master.id) AS jobsid ,DATE(
        job_master_receiv_amount.`createddate`
      ) AS `date`,
     SUM(IF(
        DATE(
          job_master_receiv_amount.`createddate`
        ) = DATE(job_master.`date`),
         job_master_receiv_amount.`amount`,
        0
      )) AS   'SameDay',
      SUM(IF(
        DATE(
          job_master_receiv_amount.`createddate`
        ) = DATE(job_master.`date`),
       0,
        job_master_receiv_amount.`amount`
      )) AS 'BackDay'
    
    FROM
      job_master_receiv_amount 
      LEFT JOIN `job_master` 
        ON job_master.id = job_master_receiv_amount.`job_fk` 
        WHERE job_master.`branch_fk` IN($branch) AND job_master.`status`!='0' AND job_master_receiv_amount.`status`='1' AND job_master.`model_type`='1' $cstartdate $cenddate AND job_master_receiv_amount.payment_type in (" . implode(",", array("'CREDIT CARD'", "'CREDIT CARD swiped thru ICICI'", "'WALLET CREDIT CARD swiped thru MSWIP'", "'DEBIT CARD swiped thru ICICI'", "'DEBIT CARD swiped thru MSWIP'", "'Swipe thru HDFC'", "'Swipe thru AXIS'", "'DEBIT CARD'","'PayTm'","'PAYTM','CASH'")) . ")  GROUP BY DATE(job_master_receiv_amount.createddate)");
	  
	return $query->result(); 

}

function get_val($qry){
	$query = $this->db->query($qry);
	  
	return $query->result(); 
}

public function jobsreciveamount($jobsis=null){
	if($jobsis != ""){
		$jobid=explode(",",$jobsis);
		 $this->db->select('sum(amount) as amount'); 
		 $this->db->from('job_master_receiv_amount');
		 $this->db->where_in("job_fk",$jobid);
		 $this->db->where("status",'1');
		 $this->db->where("payment_type",'CASH');
		$query = $this->db->get();
		
        return $query->row();
	}else{ $dnull=""; return $dnull; }
    
	}
public function banckamount($branch,$date){
	$branchall=explode(",",$branch);
		 $this->db->select('sum(amount) as amount'); 
		 $this->db->from('branch_payment');
		 $this->db->where("STR_TO_DATE(date,'%Y-%m-%d')",$date);
		 $this->db->where_in("branch_fk",$branchall);
		$this->db->where("status",'1');
		$query = $this->db->get();
		
        return $query->row();
	
	}	
	public function jobcumulativeamount($branch,$date){
		
		 $this->db->select("(sum(r.amount) - (SELECT SUM(b.amount) FROM branch_payment b WHERE b.status = '1' AND b.branch_fk = '1' and STR_TO_DATE(b.date,'%Y-%m-%d') < '$date'))  as amount"); 
		 $this->db->from('job_master_receiv_amount r');
		 $this->db->join("job_master j","j.id=r.job_fk",'left');
		 $this->db->where("j.branch_fk",$branch);
		 $this->db->where("STR_TO_DATE(r.createddate,'%Y-%m-%d') <",$date);
		 $this->db->where("j.status !=",'0');
		 $this->db->where("r.status",'1');
		 $this->db->where("r.payment_type",'CASH');
		 $query = $this->db->get();
        
		return $query->row();
	
	}
	public function legentbrach($legerid){
		
		$query = $this->db->query("SELECT id,branch_name FROM `branch_master` WHERE STATUS='1' AND id NOT IN (SELECT branchid FROM party_ledger_branch WHERE STATUS='1') AND STATUS='1' ORDER BY  branch_name asc ");
	    return $query->result(); 
	
	}
	public function legentlist(){
		
		 $this->db->select("p.id,p.party_name,group_concat(b1.branch_name) as branchname"); 
		 $this->db->from('party_ledger_account p');
		 $this->db->join("party_ledger_branch b","b.lederid=p.id and b.status='1'",'left');
		 $this->db->join("branch_master b1","b1.id=b.branchid",'left');
		 $this->db->where("p.status",'1');
		 $this->db->order_by("p.party_name","asc");
		 $this->db->group_by("p.id");
		 $query = $this->db->get();
        
		return $query->result();
	
	}
	public function legentbrachall($legtid){
		
		 $this->db->select("p.id,b1.branch_name"); 
		 $this->db->from('party_ledger_branch p');
		 $this->db->join("branch_master b1","b1.id=p.branchid",'left');
		 $this->db->where("p.status",'1');
		 $this->db->where("p.lederid",$legtid);
			$query = $this->db->get();
        
		return $query->result();
	
	}
}

?>
