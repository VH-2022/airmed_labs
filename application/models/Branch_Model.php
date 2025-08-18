<?php

Class Branch_Model extends CI_Model {

    public function list_city() {
        $query = $this->db->query("SELECT c.id as cid,c.name as city_name FROM test_cities c WHERE status = 1 ");
        return $query->result_array();
        /*  echo "<pre>";
          print_r($query);
          exit; */
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

    public function master_get_search($branchid, $one, $two, $test_city = NULL, $btype_select = null) {

        $query = "SELECT br.*,c.id as cid,c.name as city_name,bt.name as BranchType
							FROM branch_master as br 
							LEFT JOIN test_cities as c 
							ON br.city = c.id left join branch_type as bt on bt.id = br.branch_type_fk
							where br.status != 0 AND br.id != ' '";
        if ($branchid != "") {
            $query .= " AND br.branch_name LIKE '%$branchid%'";
        }
        if ($test_city != NULL) {
            $query .= " AND c.id ='$test_city'";
        }
        if ($btype_select != '') {
            $query .= " AND br.branch_type_fk = '$btype_select'";
        }
        $query .= " ORDER BY br.id DESC LIMIT $two,$one";

        $query = $this->db->query($query);
        return $query->result_array();
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

        //  $query = "SELECT *,b1.`branch_name` AS parent,c.id as cid,c.name as city_name,bt.name,bt.type
        // FROM branch_master as b 
        // LEFT JOIN test_cities as c
        // ON b.city = c.id left join branch_type as bt on bt.id=b.branch_type_fk LEFT JOIN `branch_master` b1 ON b.`id`=b1.`parent_fk`
        // where b.status = 1 AND b.id = '" . $id . "' GROUP BY b.id ORDER BY br.id DESC ";

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

    public function num_row($branchid, $city = null, $btype_select = null) {
        $query = "SELECT br.*,c.id as cid,c.name as city_name,bt.name as BranchType
							FROM branch_master as br 
							LEFT JOIN test_cities as c
							ON br.city = c.id left join branch_type as bt on bt.id = br.branch_type_fk
							where br.status != 0 AND br.id != ' '";
        if ($branchid != "") {
            $query .= " AND br.branch_name LIKE '%$branchid%'";
        }

        if ($city != NULL) {
            $query .= " AND c.id ='$city'";
        }
        if ($btype_select) {
            $query .= " AND br.branch_type_fk = '$btype_select'";
        }
        $query .= " GROUP BY br.id ORDER BY br.id DESC ";
        $query = $this->db->query($query);
        return $query->num_rows();
    }

    public function master_get_insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    /*  function master_JobDoc_List($one, $two) 
      {

      $query = $this->db->query("SELECT
      job.id,	GROUP_CONCAT(test.test_name) testname,
      GROUP_CONCAT(pm.title) packagename,
      job.date,job.price,job.`payable_amount`,job.status,job.price,
      cust.mobile,cust.full_name, cust.id as customer_id
      FROM `job_master` as job
      LEFT JOIN job_test_list_master as jtl
      ON jtl.job_fk = job.id
      LEFT JOIN `customer_master` as cust
      ON cust.id = job.cust_fk
      LEFT JOIN `test_master` as test
      ON test.id = jtl.test_fk
      LEFT JOIN  book_package_master bpm
      ON  bpm.job_fk = job.id
      LEFT JOIN package_master as pm
      ON pm.id = bpm.package_fk where job.views = 1 AND job.id != ' '
      GROUP BY job.id ORDER BY job.id DESC LIMIT $two,$one" );

      return $query->result_array();

      } */

    // Vishal COde Start
    public function type_list() {
        $query = $this->db->query("SELECT bt.id ,bt.name,bt.type FROM branch_type bt WHERE status = 1 ");
        return $query->result_array();
        /*  echo "<pre>";
          print_r($query);
          exit; */
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

    /* Vishal Code End */
}

?>
