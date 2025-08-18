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
function sub_listnum($lab,$city){
	
	$test="SELECT t.id FROM test_master as t INNER JOIN test_master_city_price as p on t.id=p.test_fk and p.city_fk='$city' LEFT JOIN `b2b_testspecial_price` AS bmp
    ON  t.id= bmp.`test_fk` AND bmp.`lab_id`= '".$lab."' AND bmp.`status`='1' AND typetest='1' WHERE t.status='1' GROUP BY p.test_fk UNION SELECT t.id FROM package_master AS t INNER JOIN package_master_city_price AS p  ON t.id=p.package_fk and city_fk='$city' LEFT JOIN `b2b_testspecial_price` AS bmp ON t.id = bmp.`test_fk` AND bmp.`lab_id` = '".$lab."'  AND bmp.`status` = '1' AND typetest='2' WHERE t.status = '1' GROUP BY p.package_fk";

        $query = $this->db->query($test);
        return $query->num_rows();
 }
function sub_list($lab,$city,$one,$two){
	
	$test="SELECT t.test_name,t.id ,p.price,m.price_special as newprice,bmp.price_special,'1' AS testtype FROM test_master as t INNER JOIN test_master_city_price as p on t.id=p.test_fk And p.city_fk='$city' LEFT JOIN `b2b_testspecial_price` AS bmp
    ON  t.id= bmp.`test_fk` AND bmp.`lab_id`= '".$lab."' AND bmp.`status`='1' AND typetest='1' left join b2b_testmrp_price m  on m.test_fk=t.id AND m.status='1' AND m.typetest='1' AND m.lab_id='$lab'  WHERE t.status='1' GROUP BY p.test_fk UNION SELECT t.title AS test_name,t.id,p.d_price AS price,m.price_special as newprice,bmp.price_special,'2' AS testtype FROM package_master AS t INNER JOIN package_master_city_price AS p  ON t.id=p.package_fk and city_fk='$city' LEFT JOIN `b2b_testspecial_price` AS bmp ON t.id = bmp.`test_fk` AND bmp.`lab_id` = '".$lab."'  AND bmp.`status` = '1' AND typetest='2' left join b2b_testmrp_price m on m.test_fk=t.id AND m.status='1' AND m.typetest='2' AND m.lab_id='$lab' WHERE t.status = '1' GROUP BY p.package_fk LIMIT $two,$one";

        $query = $this->db->query($test);
        return $query->result_array();
 }
  public function num_row($table, $condition) {
        $query = $this->db->get_where($table, $condition);
        return $query->num_rows();
    }
   function search_num($lab,$city,$search=null){ 
          $temp = "";
		  $temp1 = "";
        if ($search != "") {
            $temp .= "AND t.test_name like '%$search%'";
			$temp1 .= "AND t.title like '%$search%'";
        }
$query = $this->db->query("SELECT t.id FROM test_master as t INNER JOIN test_master_city_price as p on t.id=p.test_fk And p.city_fk='$city' LEFT JOIN `b2b_testspecial_price` AS bmp
    ON  t.id= bmp.`test_fk` AND bmp.`lab_id`= '".$lab."' AND bmp.`status`='1' AND typetest='1' WHERE t.status='1' $temp GROUP BY p.test_fk UNION SELECT t.id FROM package_master AS t INNER JOIN package_master_city_price AS p  ON t.id=p.package_fk and city_fk='$city' LEFT JOIN `b2b_testspecial_price` AS bmp ON t.id = bmp.`test_fk` AND bmp.`lab_id` = '".$lab."'  AND bmp.`status` = '1' AND typetest='2' WHERE t.status = '1' $temp1  GROUP BY p.package_fk");
	
return $query->num_rows();
		
}
   
  function speci_list_search($lab,$city,$search, $one, $two) {
       $temp = "";
		  $temp1 = "";
        if ($search != "") {
            $temp .= "AND t.test_name like '%$search%'";
			$temp1 .= "AND t.title like '%$search%'";
        }
		
/* $query = $this->db->query("SELECT t.test_name,t.id ,p.price,bmp.price_special,'1' AS testtype FROM test_master as t INNER JOIN test_master_city_price as p on t.id=p.test_fk And p.city_fk='$city' LEFT JOIN `b2b_testspecial_price` AS bmp
    ON  t.id= bmp.`test_fk` AND bmp.`lab_id`= '".$lab."' AND bmp.`status`='1' AND typetest='1' WHERE t.status='1' $temp GROUP BY p.test_fk UNION SELECT t.title AS test_name,t.id,p.d_price AS price,bmp.price_special,'2' AS testtype FROM package_master AS t INNER JOIN package_master_city_price AS p  ON t.id=p.package_fk and city_fk='$city' LEFT JOIN `b2b_testspecial_price` AS bmp ON t.id = bmp.`test_fk` AND bmp.`lab_id` = '".$lab."'  AND bmp.`status` = '1' AND typetest='2' WHERE t.status = '1' $temp1  GROUP BY p.package_fk"); */
	
$test="SELECT t.test_name,t.id ,p.price,m.price_special as newprice,bmp.price_special,'1' AS testtype FROM test_master as t INNER JOIN test_master_city_price as p on t.id=p.test_fk And p.city_fk='$city' LEFT JOIN `b2b_testspecial_price` AS bmp
    ON  t.id= bmp.`test_fk` AND bmp.`lab_id`= '".$lab."' AND bmp.`status`='1' AND typetest='1' left join b2b_testmrp_price m  on m.test_fk=t.id AND m.status='1' AND m.typetest='1' AND m.lab_id='$lab'  WHERE t.status='1' $temp GROUP BY p.test_fk UNION SELECT t.title AS test_name,t.id,p.d_price AS price,m.price_special as newprice,bmp.price_special,'2' AS testtype FROM package_master AS t INNER JOIN package_master_city_price AS p  ON t.id=p.package_fk and city_fk='$city' LEFT JOIN `b2b_testspecial_price` AS bmp ON t.id = bmp.`test_fk` AND bmp.`lab_id` = '".$lab."'  AND bmp.`status` = '1' AND typetest='2' left join b2b_testmrp_price m on m.test_fk=t.id AND m.status='1' AND m.typetest='2' AND m.lab_id='$lab' WHERE t.status = '1' $temp1 GROUP BY p.package_fk ";
	
	$query = $this->db->query($test);
	
        return $query->result_array();
   }

}

?>
