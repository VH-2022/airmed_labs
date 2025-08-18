<?php

class Logistic_master_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    function getUser($user_id) {
        $query = $this->db->get_where("admin_master", array("id" => $user_id, "status" => "1"));
        return $query->row();
    }

    public function master_fun_get_tbl_val($dtatabase, $condition, $order, $limit = null) {
        $this->db->order_by($order[0], $order[1]);
        if ($limit != null) {
            $this->db->limit($limit[0], $limit[1]);
        }
        $query = $this->db->get_where($dtatabase, $condition);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function master_fun_insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

  public function master_fun_update($tablename, $cid, $data) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->update($tablename, $data);
        return 1;
    }
    public function master_num_rows($table, $condition) {
        $query1 = $this->db->get_where($table, $condition);
        return $query1->num_rows();
    }

    public function get_server_time() {
        $query = $this->db->query("SELECT UTC_TIMESTAMP()");
        $data['user'] = $query->result_array();
        return $data['user'][0];
    }

    public function get_val($qry) {
        $query1 = $this->db->query($qry);
        $data['user'] = $query1->result_array();
        return $data['user'];
    }
     function getfindtest($test=null,$city_fk){
        
          $query = "select t.test_name,t.TEST_CODE,t.id,p.price from test_master t INNER join test_master_city_price p on t.id=p.test_fk and p.city_fk='$city_fk'  where t.status='1' AND t.id='".$test."'";
       
         $query = $this->db->query($query);
         $res = $query->result_array();
         return $res;
    }

    function sub_list($one, $two){
        $test = "SELECT t.test_name,t.id ,p.price,t.TEST_CODE,bmp.price_special FROM test_master as t INNER JOIN test_master_city_price as p on t.id=p.test_fk LEFT JOIN `b2b_testspecial_price` AS bmp
    ON  t.id= bmp.`test_fk`  WHERE t.status='1' GROUP BY p.test_fk ORDER BY t.id asc LIMIT $two,$one";

   
        $query = $this->db->query($test);

        return $query->result_array();
    
    }
  public function num_row($table, $condition) {
        $query = $this->db->get_where($table, $condition);
        return $query->num_rows();
    }
   function search_num($search=null){
          $temp = "";
        if ($search != "") {
            $temp .= "AND t.test_name like '%$search%'";
        }
$query = $this->db->query("
        SELECT 
  t.test_name,
  t.TEST_CODE,
  t.id,
  p.price ,
  bmp.`price_special`
FROM  test_master t 
  INNER JOIN test_master_city_price p 
    ON t.id = p.test_fk 

    LEFT JOIN `b2b_testspecial_price` AS bmp
    ON  t.id= bmp.`test_fk`
WHERE t.status = '1'  $temp GROUP BY p.`test_fk` ORDER BY t.id desc");
echo $query;
        return $query->num_rows();

}
   
  function speci_list_search($search, $one, $two) {
        $temp = "";
        if ($search != "") {
            $temp .= "AND t.test_name like '%$search%'";
        }

        
        $query = $this->db->query("SELECT  t.test_name,
  t.TEST_CODE,
  t.id,
  p.price ,
  bmp.`price_special`FROM  test_master t 
  INNER JOIN test_master_city_price p 
    ON t.id = p.test_fk 

    LEFT JOIN `b2b_testspecial_price` AS bmp
    ON  t.id= bmp.`test_fk` WHERE t.status = '1'  $temp GROUP BY p.`test_fk` ORDER BY t.id desc LIMIT $two,$one");

        return $query->result_array();
    }

}

?>
