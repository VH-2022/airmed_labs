<?php

Class Test_parameter_map_model extends CI_Model {

    function get_master_get_data($name, $condition, $order) {
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($name, $condition);
        return $query->result_array();
    }

    function master_update_data($name, $condition, $order) {
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

    public function fetchdatarow($selact, $table, $array) {
        $this->db->select($selact);
        $query = $this->db->get_where($table, $array);
        return $query->row();
    }

    public function num_row($table, $condition) {
        $query = $this->db->get_where($table, $condition);
        return $query->num_rows();
    }

    public function contact_master($table_name, $data) {

        $this->db->insert($table_name, $data);
        return $this->db->insert_id();
    }

    public function citylist() {
        $query = $this->db->query("SELECT c.*,s.state_name,co.country_name FROM city c LEFT JOIN state s ON c.state_fk=s.id LEFT JOIN country co  ON c.`country_fk`=co.id  WHERE s.status=1 AND c.status=1");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function statelist() {
        $query = $this->db->query("SELECT s.*,c.country_name FROM state s LEFT JOIN country c ON c.id=s.`country_fk` WHERE s.status=1 AND c.status=1");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function get_city() {
        $query = $this->db->query("SELECT city.*,state.`state_name` FROM city INNER JOIN state ON city.`state_fk`=state.`id` WHERE city.`status`='1' AND state.`status`='1' ORDER BY city_name ASC");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function get_city_edit($id) {
        $query = $this->db->query("SELECT `test_master_city_price`.*,`test_cities`.`name` AS `city_name` FROM `test_master_city_price` 
INNER JOIN `test_cities` ON `test_master_city_price`.`city_fk`=`test_cities`.`id` 
WHERE `test_master_city_price`.`test_fk`='" . $id . "' 
AND `test_master_city_price`.`status`='1' 
AND `test_cities`.`status`='1' order by `test_cities`.`name` asc");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function get_city_edit1($id) {
        $query = $this->db->query("SELECT `test_master_city_price`.city_fk as `id`,`test_master_city_price`.`price`,`test_cities`.`name` FROM `test_master_city_price` 
INNER JOIN `test_cities` ON `test_master_city_price`.`city_fk`=`test_cities`.`id` 
WHERE `test_master_city_price`.`test_fk`='" . $id . "' 
AND `test_master_city_price`.`status`='1' 
AND `test_cities`.`status`='1'");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function master_fun_delete($tablename, $cid) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->delete($tablename);
        return 1;
    }

    function test_list($one, $two) {
        $query = $this->db->query("
                SELECT bd.id,bd.city,bd.branch,bd.package,bd.package_active_user_book,bd.active_till_date,bd.other_test_discount_self,
                bd.other_test_discount_family,bd.status,
                bm.branch_name,pm.title as package_title,tc.name as city_name 
                FROM branch_package_discount bd 
                INNER JOIN test_cities tc on tc.id = bd.city
                INNER JOIN branch_master bm on bm.id = bd.branch
                INNER JOIN package_master pm on pm.id = bd.package
                WHERE bd.STATUS=1 ORDER BY bd.id DESC LIMIT $two,$one"
        );
        return $query->result_array();
    }

    function get_val($squery) {
        $query = $this->db->query($squery);
        return $query->result_array();
    }

    function price_test_list($id = "") {

        if ($id == "") {
            $id = 1;
        }
        $data['id'] = $id;
        $query = $this->db->query("SELECT t.id,t.test_name,t.description,t.popular,tc.price,c.`name` AS city FROM `test_master` t LEFT JOIN `test_master_city_price` tc ON tc.`test_fk`=t.`id` LEFT JOIN `test_cities` c ON tc.`city_fk`=c.`id` WHERE t.`status`='1' and tc.`city_fk`=$id ORDER BY t.test_name ASC ");
        return $query->result_array();
    }

    function test_list_search($city, $branch, $one, $two) {
        $temp = "";
        if ($city != "") {
            $temp .= " AND bd.city = '$city'";
        }
        if ($branch != "") {
            $temp .= " AND bd.branch = '$branch'";
        }
        
        $query = $this->db->query("SELECT bd.id,bd.city,bd.branch,bd.package,bd.package_active_user_book,bd.active_till_date,bd.other_test_discount_self,
                bd.other_test_discount_family,bd.status,
                bm.branch_name,pm.title as package_title,tc.name as city_name 
                FROM branch_package_discount bd 
                INNER JOIN test_cities tc on tc.id = bd.city
                INNER JOIN branch_master bm on bm.id = bd.branch
                INNER JOIN package_master pm on pm.id = bd.package
                WHERE bd.STATUS=1 $temp ORDER BY bd.id DESC LIMIT $two,$one");
        
        return $query->result_array();
    }

    function test_list_search_num($city, $branch) {
        $temp = "";
        if ($city != "") {
            $temp.= " AND bd.city = '$city'";
        }
        if ($branch != "") {
            $temp.= " AND bd.branch = '$branch'";
        }
        $query = $this->db->query("
                SELECT bd.id,bd.city,bd.branch,bd.package,bd.package_active_user_book,bd.active_till_date,bd.other_test_discount_self,
                bd.other_test_discount_family,bd.status,
                bm.branch_name,pm.title as package_title,tc.name as city_name 
                FROM branch_package_discount bd 
                INNER JOIN test_cities tc on tc.id = bd.city
                INNER JOIN branch_master bm on bm.id = bd.branch
                INNER JOIN package_master pm on pm.id = bd.package
                WHERE bd.STATUS=1 $temp");

        return $query->num_rows();
    }

}

?>
