<?php
Class Vendor_model extends CI_Model {

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
	
	
	public function doctorlist($one, $two){
		$query = $this->db->query("SELECT * from inventory_vendor where status IN (1,2) order by id LIMIT $two,$one");
		 $data['user'] = $query->result_array();
        return $data['user'];
	}
	public function doctorcount(){
		$query = $this->db->query("SELECT * from inventory_vendor where status IN (1,2)");
		 return $query->num_rows();
	}
	public function doctorcount_list($srch){
	
		$temp = "";
		
		if($srch["name"] !=''){
			$temp .=' And inventory_vendor.vendor_name LIKE "%'.$srch["name"].'%"';
		}
		
		if($srch["city_name"] !=''){
			$temp .=' And inventory_vendor.city_fk = "'.$srch["city_name"].'"';
		}

		if($srch["mobile"] !=''){
$temp .=' And inventory_vendor.mobile = "'.$srch["mobile"].'"';
		}

		if($srch["phone_no"] !=''){
$temp .=' And inventory_vendor.contact_no_2 = "'.$srch["phone_no"].'"';

		}

		if($srch["email"] !=''){
$temp .=' And inventory_vendor.email_id = "'.$srch["email"].'"';

		}
		if($srch["cp_name"] !=''){
$temp .= " AND d.cp_name LIKE '%".$srch["cp_name"]."%' ";
		}
		if($srch["cp_email_id"] !=''){
$temp .=' And inventory_vendor.cp_email_id = "'.$srch["cp_email_id"].'"';
	}
		
		$query = $this->db->query("SELECT inventory_vendor.*,tc.name as CityName from inventory_vendor LEFT JOIN test_cities as tc on inventory_vendor.city_fk = tc.city_fk and tc.status='1' where inventory_vendor.status = 1 $temp");
	
		return $query->num_rows();
	}
	public function doctorlist_list($srch,$one, $two){
		$temp = "";
			
		if($srch["name"] !=''){
			$temp .=' And inventory_vendor.vendor_name LIKE "%'.$srch["name"].'%"';
		}
		
		if($srch["city_name"] !=''){
			$temp .=' And inventory_vendor.city_fk = "'.$srch["city_name"].'"';
		}

		if($srch["mobile"] !=''){
$temp .=' And inventory_vendor.mobile = "'.$srch["mobile"].'"';
		}

		if($srch["phone_no"] !=''){
$temp .=' And inventory_vendor.contact_no_2 = "'.$srch["phone_no"].'"';

		}

		if($srch["email"] !=''){
$temp .=' And inventory_vendor.email_id = "'.$srch["email"].'"';

		}
			if($srch["cp_name"] !=''){
$temp .= " AND d.cp_name LIKE '%".$srch["cp_name"]."%' ";

		}
		if($srch["cp_email_id"] !=''){
$temp .=' And inventory_vendor.cp_email_id = "'.$srch["cp_email_id"].'"';
	}

		$query = $this->db->query("SELECT inventory_vendor.*,tc.name as CityName from inventory_vendor LEFT JOIN test_cities as tc on inventory_vendor.city_fk = tc.city_fk and tc.status='1' where inventory_vendor.status = 1 $temp order by id DESC LIMIT $two,$one");
		$data['user'] = $query->result_array();
        return $data['user'];
	}

	function csv_report($name,$city,$mobile,$phone_no,$email,$cp_name,$cp_email_id){
		
			$temp = "";
		if($name != "" ){
			
			$temp .= " AND d.vendor_name LIKE '%".$name."%' ";
		}
		if($city != "" ){
			
			$temp .= " AND d.city_fk = '".$city."' ";
		}
		if($mobile != "" ){
			
			$temp .= " AND d.mobile = '".$mobile."' ";
		}
		if($phone_no != "" ){
			
			$temp .= " AND d.contact_no_2 = '".$phone_no."' ";
		}
		if($email != "" ){
			
			$temp .= " AND d.email_id = '".$email."' ";
		}
		if($cp_name != "" ){
			
			$temp .= " AND d.cp_name LIKE '%".$cp_name."%' ";
		}
		if($cp_email_id != "" ){
			
			$temp .= " AND d.cp_email_id = '".$cp_email_id."' ";
		}

		$query = $this->db->query("SELECT d.*,c.name as city  from inventory_vendor d left join test_cities c on c.city_fk=d.city_fk  and c.status='1' where d.status =1 $temp order by d.id DESC");
		$data['user'] = $query->result_array();
        return $data['user'];
	}

	function get_val($query =null){
		$query1= $this->db->query($query);
		return $query1->result_array();
	}
}

?>
