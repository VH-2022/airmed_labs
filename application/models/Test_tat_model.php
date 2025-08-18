<?php

Class Test_tat_model extends CI_Model {

    public function list_city() {
        $query = $this->db->query("SELECT c.id as cid,c.name as city_name FROM test_cities c WHERE status = 1 ");
        return $query->result_array();
    }

    public function master_get_where_condtion($table, $cid, $order) {
        $this->db->order_by($order[0], $order[1]);
        $query = $this->db->get_where($table, $cid);
        return $query->result_array();
    }

    public function master_tbl_update($tablename, $cid, $data) {
        $this->db->where(array('id' => $cid));
        $this->db->update($tablename, $data);
        return 1;
    }

    public function master_tbl_update_new($tablename, $cid, $data) {
        $this->db->where($cid);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function run_query($query1 = null) {
        $query = $this->db->query($query1);
        return true;
    }

    public function get_val($query1 = null) {
        $query = $this->db->query($query1);
        $data['user'] = $query->result();
        return $data['user'];
    }

    public function master_get_search($test_name, $one, $two) {
        $test_name = trim($test_name);
        if ($test_name != "") {
            $temp = " AND tm.test_name LIKE '%$test_name%'";
        }
        if ($test_name != "") {
            $temp1 = " AND pm.title LIKE '%$test_name%'";
        }
        $query = "select tt.id,tt.tat,tt.test_fk,tm.test_name,tt.type 
                from test_tat tt 
                LEFT JOIN test_master tm on tm.id = tt.test_fk AND tm.status='1' 
                where 1=1 $temp AND tt.status='1' AND tt.type='1' ORDER BY tt.id DESC";

        $query = $this->db->query($query);

        $query1 = "select tt.id,tt.tat,tt.test_fk,pm.title,tt.type
                from test_tat tt 
                LEFT JOIN package_master pm on pm.id = tt.test_fk AND pm.status='1' 
                where 1=1 $temp1 AND tt.status='1' AND tt.type='2' ORDER BY tt.id DESC";
        $query1 = $this->db->query($query1);

        $query = $query->result_array();
        $query1 = $query1->result_array();
        //echo "<pre>"; print_r(array_merge($query, $query1)); exit; 
        
        //return array_merge($query, $query1);
        return array_slice(array_merge($query, $query1), $two, $one);
    }

    public function master_get_branch() {
        $query = "SELECT br.*,c.id as cid,c.name as city_name
							FROM branch_master as br 
							LEFT JOIN test_cities as c
							ON br.city = c.id 
							where br.status = 1  ORDER BY br.id DESC";

        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function master_get_view($id) {

        $query = "SELECT 
  b.*,
  b1.`branch_name` AS parent,
  c.id AS cid,
  c.name AS city_name,
  bt.name,
  bt.type 
FROM
  branch_master b 
  LEFT JOIN `branch_master` b1 
    ON b1.`id` = b.`parent_fk`
    LEFT JOIN test_cities AS c 
    ON b.city = c.id
    LEFT JOIN branch_type AS bt 
    ON bt.id = b.branch_type_fk
    WHERE b.status = 1 
  AND b.id = '" . $id . "' 
GROUP BY b.id 
ORDER BY b.id DESC";

        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function num_row($test_name) {
        $test_name = trim($test_name);
        $query = "select tt.id,tt.tat,tt.test_fk,tm.test_name
                from test_tat tt 
                LEFT JOIN test_master tm on tm.id = tt.test_fk AND tm.status='1' 
                where 1=1 ";
        if ($test_name != "") {
            $query .= " AND tm.test_name LIKE '%$test_name%'";
        }

        $query .= " AND tt.status='1' ORDER BY tt.id DESC";
        $query = $this->db->query($query);
        //echo "<pre>";
        return $query->num_rows();
    }

    public function master_get_insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function type_list() {
        $query = $this->db->query("SELECT bt.id ,bt.name,bt.type FROM branch_type bt WHERE status = 1 ");
        return $query->result_array();
    }

    public function get_list() {
        $query = $this->db->query("SELECT id,branch_name FROM branch_master  WHERE STATUS = 1 AND (parent_fk IS NULL OR `parent_fk`='')");
        return $query->result_array();
    }

    public function get_sub_client($branchid) {

        $query = "SELECT 
GROUP_CONCAT(cl.name) as ClientName,
  cl.name,
  b.branch_name AS branch 
FROM
  b2b_labgroup AS b2b 
  LEFT JOIN collect_from AS cl 
    ON cl.id = b2b.`labid` 
    LEFT JOIN branch_master AS b
    ON b2b.`branch_fk` = b.id
    WHERE b2b.`branch_fk` = '" . $branchid . "'
    GROUP BY `b2b`.`branch_fk`  ORDER BY cl.name DESC";

        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function get_parent_node($branchid) {

        $query = "SELECT
    p.id AS parent_id,
    p.branch_name AS parent_id,
    c1.id AS child_id,
    c1.branch_name AS child_name
FROM 
    branch_master p
LEFT JOIN branch_master c1
    ON c1.parent_fk = p.id
WHERE
    p.parent_fk='" . $branchid . "'";



        $query = $this->db->query($query);
        return $query->result_array();
    }

}

?>
