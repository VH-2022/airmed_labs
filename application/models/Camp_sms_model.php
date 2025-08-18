<?php 
class Camp_sms_model extends CI_Model{
 public function __construct() {
        $this->load->database();
    }	

    function get_val($query1 = null){
    	$query = $this->db->query($query1);
    	$data['user'] = $query->result_array();
    	return $data['user'];
    }
        public function master_fun_update($tablename, $cid, $data) {
        $this->db->where('id', $cid);
        $this->db->update($tablename, $data);
        return 1;
    }
       public function master_fun_get_tbl_val($dtatabase, $condition, $order) {
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($dtatabase, $condition);
        $data['user'] = $query->result_array();
        return $data['user'];
    }
    
    function master_fun_insert($table,$data){
        $this->db->insert($table,$data);
        return $this->db->insert_id();;
    }
    
     public function fetchdatarow($selact, $table, $array) {
        $this->db->select($selact);
        $query = $this->db->get_where($table, $array);
        return $query->row();
    }
	
    function sms_num($srch=null){
       
   $temp='';
   if($srch['sms'] !=''){
    $temp .=' And camp_sms_master.sms like "%'.$srch['sms'].'%"';
   }
  if($srch['date'] !='' || $srch['end_date'] !=''){
    $odate = explode('/',$srch['date']);
    $date = $odate[2].'-'.$odate[1].'-'.$odate[0] . ' ' . '00:00:00';

     $oldate = explode('/',$srch['end_date']);
    $date_sub = $oldate[2].'-'.$oldate[1].'-'.$oldate[0] . ' ' . '23:59:59';
    $temp .=' And camp_sms_master.schedule_date >= "'.$date.'%" and camp_sms_master.schedule_date <= "'.$date_sub.'%"';
   }
   if($srch['sender'] !=''){
    $temp .=' And camp_sms_master.sender ="'.$srch['sender'].'"';
   }
   if($srch['status'] !=''){
    $temp .=' And camp_sms_master.send = "'.$srch['status'].'"';
   }
        $query = $this->db->query("select * from camp_sms_master where status='1' $temp ORDER BY id desc");
        return $query->num_rows();
    }
    function sms_list($srch=null,$one=null,$two=null){
      $temp='';
   if($srch['sms'] !=''){
    $temp .=' And camp_sms_master.sms like "%'.$srch['sms'].'%"';
   }
   if($srch['date'] !='' || $srch['end_date'] !=''){
    $odate = explode('/',$srch['date']);
    $date = $odate[2].'-'.$odate[1].'-'.$odate[0] . ' ' . '00:00:00';

     $oldate = explode('/',$srch['end_date']);
    $date_sub = $oldate[2].'-'.$oldate[1].'-'.$oldate[0] . ' ' . '23:59:59';
    $temp .=' And camp_sms_master.schedule_date >= "'.$date.'%" and camp_sms_master.schedule_date <= "'.$date_sub.'%"';
   }
   if($srch['sender'] !=''){
    $temp .=' And camp_sms_master.sender ="'.$srch['sender'].'"';
   }
   if($srch['send'] !=''){
    $temp .=' And camp_sms_master.status = "'.$srch['status'].'"';
   }
        $query = $this->db->query("select * from camp_sms_master where status='1' $temp ORDER BY id desc LIMIT $two,$one");
        $data['user'] = $query->result_array();
        return $data['user'];
    }
}