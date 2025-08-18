<?php

class Package_model extends CI_Model {

    public function __construct() {
        $this->load->database();
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
        $this->db->where('id', $cid);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function get_server_time() {
        $query = $this->db->query("SELECT UTC_TIMESTAMP()");
        $data['user'] = $query->result_array();
        return $data['user'][0];
    }

   /* function get_active_record() {
        $query = $this->db->query("SELECT * FROM `package_master` WHERE `status`='1' ORDER BY id desc");
        return $query->result_array();
    } */
    function get_active_record($package_name= null,$city=null,$status=null,$dep=null) {
        $temp ='';
       $sub_tem = ''; 
        if($package_name !=''){
            $temp .='AND package_master.title like "%'.$package_name.'%"';
        }
        if($city !=''){
            $sub_tem .=' And city_fk ="'.$city.'"';
        }
         //Vishal code Start
        if($status !=''){
            $temp .='AND package_master.is_active = "'.$status.'"';
        }
        if($dep !=''){
            if($dep==0){
                $temp .='AND package_master.is_view iS NULL';
            }else{
                $temp .='AND package_master.is_view = "'.$dep.'"';
            }
        }
        //Vishal Code End
//echo $db = "SELECT package_master.* FROM `package_master`   WHERE package_master.`status`='1' and id IN(select package_fk from package_master_city_price where city_fk = '".$city."')  $temp ORDER BY package_master.id DESC";
        $query = $this->db->query("SELECT package_master.* FROM `package_master`   WHERE package_master.`status`='1' and id IN(select package_fk from package_master_city_price where status='1'  $sub_tem )  $temp ORDER BY package_master.id DESC");
        return $query->result_array();
    }
    function get_val($qry){
        $query = $this->db->query($qry);
        return $query->result_array();
    }
   /* function get_active_record1($one, $two) {

        $query = $this->db->query("SELECT * FROM `package_master` WHERE `status`='1' ORDER BY id desc LIMIT $two,$one ");

        return $query->result_array();
    }
    
       function get_active_record1($package_name=null,$one, $two) {
   $temp ='';
         if($package_name !=''){
             $temp .='AND package_master.title like "%'.$package_name.'%"';
         }
         $query = $this->db->query("SELECT * FROM `package_master` WHERE `status`='1' $temp ORDER BY package_master.id desc LIMIT $two,$one ");

         return $query->result_array();
     } */
     function get_active_record1($package_name =null,$city=null,$status=null,$dep=null,$one, $two) {
$temp ='';
  $sub_tem = '';
        if($package_name !=''){
            $temp .='AND package_master.title like "%'.$package_name.'%"';
        }
         if($city !=''){
            $sub_tem .=' And city_fk ="'.$city.'"';
        }
        //Vishal code Start
        if($status !=''){
            $temp .='AND package_master.is_active = "'.$status.'"';
        }
        if($dep !=''){
            if($dep==0){
                $temp .='AND package_master.is_view iS NULL';
            }else{
                $temp .='AND package_master.is_view = "'.$dep.'"';
            }
        }
        //Vishal Code End
        $query = $this->db->query("SELECT package_master.* FROM `package_master` WHERE package_master.`status`='1' and id IN (select package_fk from package_master_city_price  where status='1' $sub_tem)  $temp ORDER BY package_master.id desc LIMIT $two,$one");

        return $query->result_array();
    }

    /* Nishit code start */

    public function master_fun_update1($tablename, $cid, $data) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->update($tablename, $data);
        return 1;
    }

    function get_package_price($pid,$city) {
            $temp ='';
        if($city !=''){
            $temp .='AND package_master_city_price.city_fk = "'.$city.'%"';
        }
        $qry = "SELECT `package_master_city_price`.*,`test_cities`.`name` FROM `package_master_city_price` INNER JOIN `test_cities` ON `package_master_city_price`.`city_fk`=`test_cities`.`id`
WHERE `package_master_city_price`.`status`='1' AND `test_cities`.`status`='1' AND `package_master_city_price`.`package_fk`='" . $pid . "' $temp and package_master_city_price.a_price !=0 order by `test_cities`.`name` asc";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    /* Nishit code end */
}

?>
