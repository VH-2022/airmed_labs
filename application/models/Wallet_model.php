<?php
Class Wallet_model extends CI_Model {

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
    function get_val($query1 = null) {
        $query = $this->db->query($query1);
        $data['user'] = $query->result_array();
        return $data['user'];
    }
public function contact_master($table_name, $data) {

        $this->db->insert($table_name, $data);
        return $this->db->insert_id();
    }
	
	public function wallet_history($user=null,$credit=null,$debit=null,$total=null,$date=null){
		$query ="SELECT  w.*,c.`full_name` FROM wallet_master w LEFT JOIN customer_master c ON c.id=w.`cust_fk` WHERE c.`status`=1";
		
		if($user != ""){
			
			$query .=" AND c.id='$user'"; 
		}
		if($credit != ""){
			
			$query .=" AND w.credit='$credit'"; 
		}
		if($debit != ""){
			
			$query .=" AND w.debit='$debit'"; 
		}
		if($total != ""){
			
			$query .=" AND w.total='$total'"; 
		}
		if ($date != "") {

            $query .= " AND DATE_FORMAT(w.created_date, '%d/%m/%Y') ='$date'";
        }
		$query = $this->db->query($query);
		 $data['user'] = $query->result_array();
        return $data['user'];
	}
	function payment_history($user_fk=null){
	//	$query = $this->db->query("SELECT id,debit,credit, DATE_FORMAT(DATE(created_time) ,'%d %b %y') AS date FROM `wallet_master` where cust_fk='$user_fk' and status=1");
		$query = "SELECT 
  p.* , GROUP_CONCAT(t.`test_name`) testname,c.full_name

FROM
  `payment` p
  LEFT JOIN job_master j 
  ON j.`id`=p.`job_fk`
 LEFT JOIN job_test_list_master jt
  ON j.`id`=jt.`job_fk` LEFT JOIN test_master t ON t.id=jt.`test_fk` LEFT JOIN customer_master c ON c.id=p.uid";

  if($cust_fk != ""){
			
			$query .=" WHERE p.uid = '$user_fk'"; 
		}
		
  $query .=" GROUP BY p.id";
   
    
		$query = $this->db->query($query);
		$query1 = $query->result_array();
		return $query1;	
	}
	public function num_row_srch($data){
		

		$query ="SELECT  w.*,c.`full_name` FROM wallet_master w LEFT JOIN customer_master c ON c.id=w.`cust_fk` WHERE c.`status`=1 and w.status='1'";
		/*if($data['user'] != ""){
			$user = $data['user'];
			$query .=" AND c.id='$user'"; 
		}*/

		if($data['user'] != ""){
			$user = $data['user'];
			$user_test = strtolower($user);
			$query .=" AND (Lower(c.full_name) like '%$user_test%' OR c.mobile = '$user')";
		}

		if($data['credit'] != ""){
			$credit = $data['credit'];
			$query .=" AND w.credit='$credit'"; 
		}
		if($data['debit'] != ""){
			$debit = $data['debit'];
			$query .=" AND w.debit='$debit'"; 
		}
		if($data['total'] != ""){
			$total = $data['total'];
			$query .=" AND w.total='$total'"; 
		}
		if ($data['date'] != "") {
			$old_date = explode('/',$data['date']);
		$new_start = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
		$start = $new_start ." 00:00:00";

		$sub_old_date = explode('/',$data['date']);
		$e_start = $sub_old_date[2].'-'.$sub_old_date[1].'-'.$sub_old_date[0];
		$end_date = $e_start ." 23:59:59";
//			$date = $data['date'];
            $query .= " AND w.created_time >='$start' AND w.created_time <='$end_date'";
        }

		$query = $this->db->query($query);
		 $data['user'] = $query->num_rows();
        return $data['user'];
	}
	public function row_srch($data, $limit, $start){ 
	//	print_r($data);die;
		// $old_date = explode('/',$data['date']);
		// $new_start = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
		// $start = $new_start ." 00:00:00";

		// $sub_old_date = explode('/',$data['date']);
		// $e_start = $sub_old_date[2].'-'.$sub_old_date[1].'-'.$sub_old_date[0];
		// $end_date = $e_start ." 23:59:59";


		$query ="SELECT  w.*,c.`full_name`,a.name as added_by_name FROM wallet_master w LEFT JOIN customer_master c ON c.id=w.`cust_fk` left join admin_master a on w.added_by=a.id WHERE c.`status`=1 and w.status='1'";
		// if($data['user'] != ""){
		// 	$user = $data['user'];
		// 	$query .=" AND c.id='$user'"; 
		// }
		if($data['user'] != ""){
			$user = $data['user'];
			$user_test = strtolower($user);
			$query .=" AND (Lower(c.full_name) like '%$user_test%' OR c.mobile = '$user')";
		}
		if($data['credit'] != ""){
			$credit = $data['credit'];
			$query .=" AND w.credit='$credit'"; 
		}
		if($data['debit'] != ""){
			$debit = $data['debit'];
			$query .=" AND w.debit='$debit'"; 
		}
		if($data['total'] != ""){
			$total = $data['total'];
			$query .=" AND w.total='$total'"; 
		}
		
		if ($data['date'] != "") {
			$old_date = explode('/',$data['date']);
		$new_start = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
		$startdate = $new_start ." 00:00:00";

		$sub_old_date = explode('/',$data['date']);
		$e_start = $sub_old_date[2].'-'.$sub_old_date[1].'-'.$sub_old_date[0];
		$end_date = $e_start ." 23:59:59";
//			$date = $data['date'];
            $query .= " AND w.created_time >='$startdate' AND w.created_time <='$end_date'";
        }
		$query .= " order by w.id desc LIMIT $start , $limit";
		$query = $this->db->query($query);
		 $data['user'] = $query->result_array();
        return $data['user'];
	}
	
 function csv_report($typeahead=null, $credit=null, $debit=null,$total=null,$date=null){
    		$query = "SELECT  w.*,c.`full_name` FROM wallet_master w LEFT JOIN customer_master c ON c.id=w.`cust_fk` WHERE c.`status`=1";
    		if ($typeahead !='') {
    			$query .= " AND c.`full_name` like '%$typeahead%'";
    		}
    		if($credit !=''){
    			$query .= " AND w.`credit` = '$credit'";
    		}
    		if($debit !=''){
    			$query .= " AND w.`debit` = '$debit'";
    		}
    		if($total !=''){
    			$query .= " AND w.`total` = '$total'";
    		}
    		if($date !=''){
    			$query .= " AND w.`created_time` >= '$date'";
    		}

    		$query .= " order by w.id DESC";

        $query = $this->db->query($query);
        //print_r($query);die;
        $query1 = $query->result_array();
        return $query1;
	    }
}

?>
