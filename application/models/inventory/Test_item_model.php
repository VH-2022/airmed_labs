<?php

class Test_item_model extends CI_Model {

    public function __construct() {
        $this->load->database();
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
        //echo $table; print_R($data); die();
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function master_fun_update($tablename, $cid, $data) {
        $this->db->where('id', $cid);
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

    function get_active_record() {
        $query = $this->db->query("SELECT * FROM `bmh_project_master` WHERE `status`='1' ORDER BY id desc");
        return $query->result_array();
    }

    function get_active_record1($one, $two) {

        $query = $this->db->query("SELECT * FROM `press_release` WHERE `status`='1' ORDER BY id desc LIMIT $two,$one ");

        return $query->result_array();
    }

    function get_team_record() {
        $query = $this->db->query("SELECT id,picture,name,designation FROM `bmh_team_master` WHERE `status`='1' ORDER BY id desc");
        return $query->result_array();
    }

    function get_team_record1($one, $two) {

        $query = $this->db->query("SELECT id,picture,name,designation FROM `bmh_team_master` WHERE `status`='1' ORDER BY id desc LIMIT $two,$one ");

        return $query->result_array();
    }

    public function get_data($query) {
        $query1 = $this->db->query($query);
        $data['user'] = $query1->result_array();
        return $data['user'];
    }

    function get_active_record3($query, $one, $two) {
        $query = $this->db->query($query . " ORDER BY id DESC  limit " . $two . "," . $one);
        return $query->result_array();
    }

    public function search_data($query) {
        $query1 = $this->db->query($query . " ORDER BY id DESC");
        $data['user'] = $query1->result_array();
        return $data['user'];
    }

    function search_active_record3($query, $one, $two) {
        $query = $this->db->query($query . " ORDER BY id DESC  limit " . $two . "," . $one);
        return $query->result_array();
    }

    function location_active_record() {
        $query = $this->db->query("SELECT * FROM `location_master` WHERE `status`='1' ORDER BY id DESC");
        return $query->result_array();
    }

    function location_active_record1($one, $two) {

        $query = $this->db->query("SELECT * FROM `location_master` WHERE `status`='1' ORDER BY id DESC LIMIT $two,$one ");

        return $query->result_array();
    }

    function category_active_record() {
        $query = $this->db->query("SELECT * FROM `category_master` WHERE `status`='1' ORDER BY id DESC");
        return $query->result_array();
    }

    function category_active_record1($one, $two) {

        $query = $this->db->query("SELECT * FROM `category_master` WHERE `status`='1' ORDER BY id DESC LIMIT $two,$one ");

        return $query->result_array();
    }

    function subcategory_active_record() {
        $query = $this->db->query("SELECT subcategory_master.*,category_master.category_name,category_master.status FROM `subcategory_master` join `category_master` on subcategory_master.category_id=category_master.id WHERE category_master.status='1' AND subcategory_master.status='1' ORDER BY subcategory_master.id DESC");
        return $query->result_array();
    }

    function subcategory_active_record1($one, $two) {

        $query = $this->db->query("SELECT subcategory_master.*,category_master.category_name,category_master.status FROM `subcategory_master` join `category_master` on subcategory_master.category_id=category_master.id WHERE category_master.status='1' AND subcategory_master.status='1' ORDER BY subcategory_master.id DESC LIMIT $two,$one ");

        return $query->result_array();
    }

    public function master_fun_update1($tablename, $condition, $data) {
        $this->db->where($condition);
        $this->db->update($tablename, $data);
        return 1;
    }

    function get_aboutus_record() {
        $query = $this->db->query("SELECT id,title,description FROM `bmh_aboutus_master` WHERE `status`='1' ORDER BY id desc");
        return $query->result_array();
    }

    function category_list() {

        $query = $this->db->query("SELECT * FROM `bmh_house_cat` WHERE `status`='1'");
        return $query->result_array();
    }

    public function housecat_get_tbl_val() {

        $query1 = $this->db->query("select * from bmh_house_subcat where status='1' order by id desc");
        $data['user'] = $query1->result_array();
        return $data['user'];
    }

    public function get_val($qry) {
        $query1 = $this->db->query($qry);
        $data['user'] = $query1->result_array();
        return $data['user'];
    }

    public function master_fun_update_new($tablename, $cid, $data) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function finishcat_get_tbl_val() {

        $query1 = $this->db->query("select * from bmh_finishing_work_subcat where status='1' order by id desc");
        $data['user'] = $query1->result_array();
        return $data['user'];
    }

    function finish_list() {

        $query = $this->db->query("SELECT * FROM `bmh_finishing_work_cat` WHERE `status`='1'");
        return $query->result_array();
    }

    public function registercat_get_tbl_val() {

        $query1 = $this->db->query("select * from bmh_registration where status='1' order by id desc");
        $data['user'] = $query1->result_array();
        return $data['user'];
    }

    public function listing() {

        $query = $this->db->query("select * from sms_master where status='1' order by id desc");
        return $query->result();
    }

    public function srch_doctor_list($limit, $start) {

        $query = "SELECT 
  `doctor_req_discount`.*,
  `doctor_master`.`full_name` 
FROM
  `doctor_req_discount` 
  INNER JOIN `doctor_master` 
    ON `doctor_master`.`id` = `doctor_req_discount`.`doctor_fk` 
WHERE `doctor_master`.`status` = '1' 
  AND `doctor_req_discount`.`status` = '1' ORDER BY `doctor_req_discount`.`id` DESC LIMIT $start , $limit ";
        $result = $this->db->query($query);
        return $result->result_array();
    }

    function machin_num($srchs = null) {
        $temp = "";

        if ($srchs['item_name'] != '') {
            $temp .= ' AND T.name like "%' . $srchs['item_name'] . '%"';
        }

        if ($srchs['branch_name'] != '') {
            $temp .= " AND inventory_machine_branch.branch_fk =" . $srchs['branch_name'] . "";
        }

        if ($srchs['reagent_name'] != '') {
            $temp .= ' AND C.reagent_name like "%' . $srchs['reagent_name'] . '%"';
        }

        if ($srchs['unit_name'] != '') {
            $temp .= ' AND C.unit_fk = "' . $srchs['unit_name'] . '"';
        }

        $login_data = logindata();
        $login_id = $login_data['id'];
        $type = $login_data['type'];

        if ($type == 1 || $type == 2 || $type == 8) {
            $testbranch = "";
        } else {
            $testbranch = "AND inventory_machine_branch.branch_fk in(SELECT `branch_fk`  FROM `user_branch`  WHERE STATUS = '1' AND user_fk = " . $login_id . ")";
        }

        $query = $this->db->query("SELECT T.id, T.NAME,GROUP_CONCAT(DISTINCT B.branch_name) AS BranchName,GROUP_CONCAT(DISTINCT C.reagent_name) AS ReagentName,GROUP_CONCAT(DISTINCT inventory_unit_master.name) as UnitName FROM inventory_machine T
LEFT JOIN inventory_machine_branch ON inventory_machine_branch.`machine_fk` = T.id  AND  inventory_machine_branch.status='1'
LEFT JOIN branch_master AS B ON B.id = inventory_machine_branch.branch_fk AND inventory_machine_branch.status=1
LEFT JOIN inventory_item AS C ON C.`machine` = T.`id` AND C.status=1 LEFT JOIN inventory_unit_master on inventory_unit_master.id = C.unit_fk
WHERE T.status='1' $temp  $testbranch GROUP BY T.id");

        return $query->num_rows();
    }

    function machine_list($srch = null, $one = null, $two = null) {

        $temp = "";

        if ($srch['item_name'] != '') {
            $temp .= ' AND T.name like "%' . $srch['item_name'] . '%"';
        }

        if ($srch['branch_name'] != '') {
            $temp .= " AND inventory_machine_branch.branch_fk =" . $srch['branch_name'] . "";
        }

        if ($srch['reagent_name'] != '') {
            $temp .= ' AND C.reagent_name like "%' . $srch['reagent_name'] . '%"';
        }

        if ($srch['unit_name'] != '') {
            $temp .= ' AND C.unit_fk = "' . $srch['unit_name'] . '"';
        }

        $login_data = logindata();
        $login_id = $login_data['id'];
        $type = $login_data['type'];

        if ($type == 1 || $type == 2 || $type == 8) {
            $testbranch = "";
        } else {
            $testbranch = "AND inventory_machine_branch.branch_fk in(SELECT `branch_fk`  FROM `user_branch`  WHERE STATUS = '1' AND user_fk = " . $login_id . ")";
        }


        $query = $this->db->query("SELECT T.id, T.NAME,GROUP_CONCAT(DISTINCT B.branch_name) AS BranchName,GROUP_CONCAT(DISTINCT C.reagent_name) AS ReagentName,GROUP_CONCAT(DISTINCT inventory_unit_master.name) as UnitName FROM inventory_machine T
LEFT JOIN inventory_machine_branch ON inventory_machine_branch.`machine_fk` = T.id  AND  inventory_machine_branch.status='1'
LEFT JOIN branch_master AS B ON B.id = inventory_machine_branch.branch_fk AND inventory_machine_branch.status=1
LEFT JOIN inventory_item AS C ON C.`machine` = T.`id`  AND C.status=1 LEFT JOIN inventory_unit_master on inventory_unit_master.id = C.unit_fk  
WHERE T.status='1' $temp $testbranch GROUP BY T.id  ORDER BY T.id DESC LIMIT $one,$two");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function new_fun_update($tablename, $condition, $data) {
        $this->db->where($condition);
        $this->db->update($tablename, $data);
        return 1;
    }

    function getnum($srch = null) {

        $temp = '';

        if ($srch['branch_name'] != '') {
            $temp .= "AND `inventory_stock_master`.`branch_fk` = '" . $srch['branch_name'] . "' ";
        }
        if ($srch['item_name'] != '') {
            $temp .= "AND `inventory_item`.`reagent_name` LIKE '%" . $srch['item_name'] . "%' ";
        }

        if ($srch['machine_name'] != '') {
            $temp .= "AND `inventory_stock_master`.`machine_fk` ='" . $srch['machine_name'] . "' ";
        }

        if ($srch['category_fk'] != '') {
            $temp .= "AND `inventory_category`.`name` LIKE '%" . $srch['category_fk'] . "%' ";
        }

        if ($srch['stock'] != '') {
            $temp .= "AND `inventory_stock_master`.`total` >= '" . $srch['stock'] . "' and  `inventory_stock_master`.`total` <= '" . $srch['stock'] . "' ";
        }

        if ($srch['start_date'] != '') {
            $old = explode("/", $srch['start_date']);
            $new_date = $old[2] . '-' . $old[0] . '-' . $old[1];
            $stime = " 00:00:00";
            $sutime = " 23:59:59";
            $temp .= "AND `inventory_stock_master`.`created_date` >= '" . $new_date . $stime . "' and  `inventory_stock_master`.`created_date` <= '" . $new_date . $sutime . "' ";
        }



        if ($srch['expiry'] != '') {
            $old_expiry = explode("/", $srch['expiry']);

            $new_expiry = $old_expiry[2] . '-' . $old_expiry[0] . '-' . $old_expiry[1];
            $time = " 00:00:00";
            $ltime = " 23:59:59";
            $temp .= "AND `inventory_stock_master`.`expire_date` >= '" . $new_expiry . $time . "' and  `inventory_stock_master`.`expire_date` <= '" . $new_expiry . $ltime . "' ";
        }

        $query = $this->db->query("SELECT DISTINCT 
  `inventory_stock_master`.item_fk,
  `inventory_item`.`reagent_name` AS item_name,
`inventory_category`.`name` AS category_name,
 `inventory_machine`.`name` AS Machinename,
 `branch_master`.`branch_name` AS Branchname
FROM `inventory_stock_master`
  INNER JOIN `inventory_item` 
    ON `inventory_item`.`id` = `inventory_stock_master`.`item_fk`

    INNER JOIN `inventory_category` 
    ON `inventory_category`.`id` = `inventory_item`.`category_fk`
    LEFT JOIN `inventory_machine`  ON `inventory_machine`.`id` = `inventory_item`.`machine`  and `inventory_machine`.`status` = '1' LEFT JOIN branch_master on inventory_stock_master.branch_fk = branch_master.id and branch_master.status='1'
WHERE `inventory_stock_master`.`status` = '1' and `inventory_item`.`status` = '1'  and `inventory_category`.`status` = '1'  $temp
ORDER BY `inventory_stock_master`.id DESC");

        return $query->num_rows();
    }

    function view_list($srch = null, $one = null, $two = null) {
        $temp = '';
        if ($srch['branch_name'] != '') {
            $temp .= "AND `inventory_stock_master`.`branch_fk` = '" . $srch['branch_name'] . "' ";
        }
        if ($srch['item_name'] != '') {
            $temp .= "AND `inventory_item`.`reagent_name` LIKE '%" . $srch['item_name'] . "%' ";
        }

        if ($srch['machine_name'] != '') {
            $temp .= "AND `inventory_stock_master`.`machine_fk` ='" . $srch['machine_name'] . "' ";
        }
        if ($srch['category_fk'] != '') {
            $temp .= "AND `inventory_category`.`name` LIKE '%" . $srch['category_fk'] . "%' ";
        }

        if ($srch['stock'] != '') {
            $temp .= "AND `inventory_stock_master`.`total` >= '" . $srch['stock'] . "' and  `inventory_stock_master`.`total` <= '" . $srch['stock'] . "' ";
        }
        if ($srch['start_date'] != '') {

            $old = explode("/", $srch['start_date']);

            $new_date = $old[2] . '-' . $old[0] . '-' . $old[1];
            $stime = " 00:00:00";
            $sutime = " 23:59:59";
            $temp .= "AND `inventory_stock_master`.`created_date` >= '" . $new_date . $stime . "' and  `inventory_stock_master`.`created_date` <= '" . $new_date . $sutime . "' ";
        }

        if ($srch['expiry'] != '') {
            $old_expiry = explode("/", $srch['expiry']);
            $new_expiry = $old_expiry[2] . '-' . $old_expiry[0] . '-' . $old_expiry[1];
            $time = " 00:00:00";
            $ltime = " 23:59:59";
            $temp .= "AND `inventory_stock_master`.`expire_date` >= '" . $new_expiry . $time . "' and  `inventory_stock_master`.`expire_date` <= '" . $new_expiry . $ltime . "' ";
        }


        $query = $this->db->query("SELECT DISTINCT 
  `inventory_stock_master`.branch_fk,
  `inventory_stock_master`.item_fk,
  `inventory_item`.`reagent_name` AS item_name,
  `inventory_machine`.`name` AS Machinename,
`inventory_category`.`name` AS category_name,
`branch_master`.`branch_name` AS Branchname,
inventory_stock_master.*
FROM `inventory_stock_master`
  INNER JOIN `inventory_item` 
    ON `inventory_item`.`id` = `inventory_stock_master`.`item_fk`

    INNER JOIN `inventory_category` 
    ON `inventory_category`.`id` = `inventory_item`.`category_fk`
    LEFT JOIN `inventory_machine` 
    ON `inventory_machine`.`id` = `inventory_item`.`machine`  and `inventory_machine`.`status` = '1' LEFT JOIN branch_master on inventory_stock_master.branch_fk = branch_master.id and branch_master.status='1'
WHERE `inventory_stock_master`.`status` = '1' and `inventory_item`.`status` = '1'   and `inventory_category`.`status` = '1'  $temp
ORDER BY `inventory_stock_master`.id DESC limit $one,$two");
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    //Vishal Code Start

    function getnum_stock($srch = null) {
        $temp = '';
        if ($srch['branch_name'] != '') {
            $temp = ' AND inventory_inward_master.branch_fk ="' . $srch['branch_name'] . '"';
        }
        if ($srch['item_name'] != '') {
            $temp = ' AND ism.item_fk ="' . $srch['item_name'] . '"';
        }
        if ($srch['expiry'] != '' || $srch['end_date'] != '') {
            $odate = explode(",", $srch['expiry']);
            $new_data = $odate[2] . '-' . $odate[1] . '-' . $odate[0] . ' 00:00:00';

            $edate = explode(",", $srch['end_date']);
            $sub_date = $edate[2] . '-' . $edate[1] . '-' . $edate[0] . ' 00:00:00';

            $temp = ' AND ism.expire_date >="' . $new_data . '" AND ism.expire_date <="' . $sub_date . '" ';
        }

        $login_data = logindata();
        $login_id = $login_data['id'];
        $type = $login_data['type'];

        if ($type == 1 || $type == 2 || $type == 8) {
            $testbranch = "";
        } else {
            $testbranch = "AND inventory_inward_master.branch_fk in(SELECT `branch_fk`  FROM `user_branch`  WHERE STATUS = '1' AND user_fk = " . $login_id . ")";
        }

        $query = $this->db->query("SELECT ism.*,it.reagent_name ,br.branch_name FROM inventory_stock_master AS ism LEFT JOIN `inventory_inward_master` ON `inventory_inward_master`.id = ism.`inward_fk` AND `inventory_inward_master`.status='1' LEFT JOIN branch_master AS br ON br.id = `inventory_inward_master`.branch_fk AND br.status='1' LEFT JOIN inventory_item AS it ON it.id = ism.item_fk AND it.status = '1' WHERE ism.status = '1' AND it.`reagent_name` !='' AND it.`reagent_name` IS NOT NULL and (inventory_inward_master.`po_fk` is null or inventory_inward_master.`po_fk`='') AND `inventory_inward_master`.branch_fk !='' $temp $testbranch ORDER BY ism.id DESC");
        return $query->num_rows();
    }

    function stock_list($srch = null, $one = null, $two = null) {
        $temp = '';
        if ($srch['branch_name'] != '') {
            $temp = ' AND inventory_inward_master.branch_fk ="' . $srch['branch_name'] . '"';
        }
        if ($srch['item_name'] != '') {
            $temp = ' AND ism.item_fk ="' . $srch['item_name'] . '"';
        }
        if ($srch['expiry'] != '' || $srch['end_date'] != '') {
            $odate = explode(",", $srch['expiry']);
            $new_data = $odate[2] . '-' . $odate[1] . '-' . $odate[0] . ' 00:00:00';

            $edate = explode(",", $srch['end_date']);
            $sub_date = $edate[2] . '-' . $edate[1] . '-' . $edate[0] . ' 00:00:00';

            $temp = ' AND ism.expire_date >="' . $new_data . '" AND ism.expire_date <="' . $sub_date . '" ';
        }

        $login_data = logindata();
        $login_id = $login_data['id'];
        $type = $login_data['type'];

        if ($type == 1 || $type == 2 || $type == 8) {
            $testbranch = "";
        } else {
            $testbranch = "AND inventory_inward_master.branch_fk in(SELECT `branch_fk`  FROM `user_branch`  WHERE STATUS = '1' AND user_fk = " . $login_id . ")";
        }


        $query = $this->db->query("SELECT ism.*,it.reagent_name ,br.branch_name FROM inventory_stock_master AS ism LEFT JOIN `inventory_inward_master` ON `inventory_inward_master`.id = ism.`inward_fk` AND `inventory_inward_master`.status='1' LEFT JOIN branch_master AS br ON br.id = `inventory_inward_master`.branch_fk AND br.status='1' LEFT JOIN inventory_item AS it ON it.id = ism.item_fk AND it.status = '1' WHERE ism.status = '1' AND it.`reagent_name` !='' AND it.`reagent_name` IS NOT NULL AND `inventory_inward_master`.branch_fk !='' and (inventory_inward_master.`po_fk` is null or inventory_inward_master.`po_fk`='') and ism.expire_date !='' $temp $testbranch ORDER BY ism.id DESC  limit $one,$two");

        $data['user'] = $query->result_array();
        return $data['user'];
    }

    //Vishal Code End 
    //Vishal Start Code
    public function master_fun_new_update($tablename, $cid, $data) {
        $this->db->where($cid[0], $cid[1]);
        $this->db->update($tablename, $data);
        return 1;
    }

    //Vishal End Code
}

?>
