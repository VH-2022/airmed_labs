<?php
Class Doctor_test_discount_model extends CI_Model {

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
public function contact_master($table_name, $data) {

        $this->db->insert($table_name, $data);
        return $this->db->insert_id();
    }
	
	public function citylist(){
		$query = $this->db->query("SELECT c.*,s.state_name,co.country_name FROM city c LEFT JOIN state s ON c.state_fk=s.id LEFT JOIN country co  ON c.`country_fk`=co.id  WHERE s.status=1 AND c.status=1");
		 $data['user'] = $query->result_array();
        return $data['user'];
	}
	
	public function statelist(){
		$query = $this->db->query("SELECT s.*,c.country_name FROM state s LEFT JOIN country c ON c.id=s.`country_fk` WHERE s.status=1 AND c.status=1");
		 $data['user'] = $query->result_array();
        return $data['user'];
	}
	public function doctorlist($one, $two){
		$query = $this->db->query("SELECT * from lab_doc_discount where status IN (1,2) order by id LIMIT $two,$one");
		 $data['user'] = $query->result_array();
        return $data['user'];
	}
	public function doctorcount(){
		$query = $this->db->query("SELECT * from doctor_master where status IN (1,2)");
		 return $query->num_rows();
	}
	   public function check_test($lab_fk,$doc_fk,$test_fk){
		$query = $this->db->query("SELECT * from lab_doc_discount where  lab_fk=' $lab_fk ' AND doc_fk=' $doc_fk ' AND test_fk=' $test_fk '");
		 return $query->num_rows();
	}
	public function doctorcount_list($srch){
		$temp = "";
		if($srch['branch_name'] != "" ){
			$name = $srch['branch_name'];
			$temp .= " AND branch_master.branch_name LIKE '%$name%' ";
		}
		if($srch['full_name'] != "" ){
			$name = $srch['full_name'];
			$temp .= " AND doctor_master.full_name LIKE '%$name%' ";
		}
		if($srch['test_name'] != "" ){
			$name = $srch['test_name'];
			$temp .= " AND test_master.test_name LIKE '%$name%' ";
		}
		//$query = $this->db->query("SELECT * from lab_doc_discount where status IN (1,2) $temp");
                $query = $this->db->query("SELECT  lab_fk, lab_doc_discount.price, branch_name,full_name,test_name FROM lab_doc_discount LEFT JOIN branch_master
   ON lab_doc_discount.lab_fk = branch_master.id LEFT JOIN doctor_master
   ON lab_doc_discount.doc_fk = doctor_master.id LEFT JOIN test_master
   ON lab_doc_discount.test_fk = test_master.id where lab_doc_discount.status='1'   $temp ORDER BY lab_doc_discount.id DESC ");
		return $query->num_rows();
	}
	public function doctorlist_list($srch,$one, $two){
		$temp = "";
		if($srch['branch_name'] != "" ){
			$name = $srch['branch_name'];
			$temp .= " AND branch_master.branch_name LIKE '%$name%' ";
		}
		if($srch['full_name'] != "" ){
			$name = $srch['full_name'];
			$temp .= " AND doctor_master.full_name LIKE '%$name%' ";
		}
		if($srch['test_name'] != "" ){
			$name = $srch['test_name'];
			$temp .= " AND test_master.test_name LIKE '%$name%' ";
		};
              // $query = $this->db->query("SELECT * from lab_doc_discount order by id LIMIT $two,$one");
               //$query = $this->db->query("SELECT l.*,b.branch_name as branch,d.full_name as state from lab_doc_discount l left join branch_master b on b.id=l.lab_fk left join doctor_master d on d.id=l.doc_fk where l.status ='1' $temp order by l.id LIMIT $two,$one");
		//$query = $this->db->query("SELECT d.*,c.city_name as city,s.state_name as state from doctor_master d left join city c on c.id=d.city left join state s on s.id=d.state where d.status IN (1,2) $temp order by d.id LIMIT $two,$one");
		$query = $this->db->query("SELECT  lab_doc_discount.id,lab_fk, lab_doc_discount.price, branch_name,full_name,test_name FROM lab_doc_discount LEFT JOIN branch_master
   ON lab_doc_discount.lab_fk = branch_master.id LEFT JOIN doctor_master
   ON lab_doc_discount.doc_fk = doctor_master.id LEFT JOIN test_master
   ON lab_doc_discount.test_fk = test_master.id  where lab_doc_discount.status='1'   $temp ORDER BY lab_doc_discount.id DESC  LIMIT $two,$one");
                $data['user'] = $query->result_array();
          
        return $data['user'];
	}
        
            function get_val($query1 = null) {
        $query = $this->db->query($query1);
        $data['user'] = $query->result();
        return $data['user'];
    }

        
}

?>
