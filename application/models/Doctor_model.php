<?php
Class Doctor_model extends CI_Model {

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
	public function fetchdatarow($selact,$table,$array){
		
		$this->db->select($selact); 
        $query = $this->db->get_where($table,$array);
        return $query->row();
    
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
		$query = $this->db->query("SELECT * from doctor_master where status IN (1,2) order by id LIMIT $two,$one");
		 $data['user'] = $query->result_array();
        return $data['user'];
	}
	public function doctorcount(){
		$query = $this->db->query("SELECT * from doctor_master where status IN (1,2)");
		 return $query->num_rows();
	}
	public function doctorcount_list($srch){
		$temp = "";
		if($srch['name'] != "" ){
			$name = $srch['name'];
			$temp .= " AND full_name LIKE '%$name%' ";
		}
		if($srch['email'] != "" ){
			$name = $srch['email'];
			$temp .= " AND email LIKE '%$name%' ";
		}
		if($srch['mobile'] != "" ){
			$name = $srch['mobile'];
			$temp .= " AND mobile LIKE '%$name%' ";
		}
		/*Vishal Code Start*/
		if($srch['city'] != "" ){
			$city = $srch['city'];
			//print_r($city);
			$temp .= " AND city  ='$city' ";
		}
		if($srch['sales_person'] != "" ){
			$sales_person = $srch['sales_person'];
			//print_r($city);
			$temp .= " AND ref_id  ='".$sales_person."' ";
		}

		if($srch['notifyReport'] != "" ){
			$notify=0;
			if($srch['notifyReport']=="Yes"){
				$notify = 1;
			}
		
			$temp .= " AND notify  =$notify";
		}
		if($srch['status'] != "" ){
			$status = $srch['status'];
			$temp .= " AND status = '$status' ";
                      
		}
		// echo "SELECT * from doctor_master where status IN (1,2) $temp"; die;
		/*Vishal COde End*/
		$query = $this->db->query("SELECT * from doctor_master where id Not IN (0) $temp");
		return $query->num_rows();
	}
	public function doctorlist_list($srch,$one, $two){
		$temp = "";
		if($srch['name'] != "" ){
			$name = $srch['name'];
			$temp .= " AND d.full_name LIKE '%$name%' ";
		}
		if($srch['email'] != "" ){
			$name = $srch['email'];
			$temp .= " AND d.email LIKE '%$name%' ";
		}
		if($srch['mobile'] != "" ){
			$name = $srch['mobile'];
			$temp .= " AND d.mobile LIKE '%$name%' ";
		}
		/*Vishal Code Start*/
		if($srch['city'] != "" ){
			$city = $srch['city'];
			$temp .= " AND d.city = '$city' ";
                        //$temp .= " AND tc.city_fk = '$city' ";
		}
if($srch['sales_person'] != "" ){
			$sales_person = $srch['sales_person'];
			//print_r($city);
			$temp .= " AND d.ref_id  ='".$sales_person."' ";
		}
		if($srch['notifyReport'] != "" ){
			$notify=0;
			if($srch['notifyReport']=="Yes"){
				$notify = 1;
			}
		
			$temp .= " AND notify  =$notify";
		}
		if($srch['status'] != "" ){
			$status = $srch['status'];
			$temp .= " AND d.status = '$status' ";
                      
		}
		/*Vishal Code End*/
		$query = $this->db->query("SELECT d.*,c.city_name as city,s.state_name as state,sales_person.first_name,sales_person.last_name from doctor_master d left join city c on c.id=d.city left join state s on s.id=d.state left join sales_user_master as sales_person  on sales_person.id=d.ref_id where d.id Not IN (0) $temp order by d.id LIMIT $two,$one");
                
                
//                		$query = $this->db->query("SELECT d.*,c.city_name as city,s.state_name as state, 
//                        sales_person.first_name,sales_person.last_name,tc.name as cityname 
//                        from doctor_master d 
//                        left join city c on c.id=d.city 
//                        left join state s on s.id=d.state 
//                        left join sales_user_master as sales_person on sales_person.id=d.ref_id 
//                        left join test_cities tc on tc.id=d.city 
//                        where d.status IN (1,2) $temp order by d.id LIMIT $two,$one");
                
                
                
		$data['user'] = $query->result_array();
        return $data['user'];
	}
	
	public function get_val($qry) {
        $query = $this->db->query($qry);
        $query1 = $query->result_array();
        return $query1;
    }
}

?>
