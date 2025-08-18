<?php

class Dashboard_model extends CI_model {

    function __construct() {
        parent::__construct();
    }

    function get_val($query1 = null) {
        $query = $this->db->query($query1);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

  function total_test($id = null,$start_date=null,$end_date =null) {
        $temp = '';
        if ($id != '') {
            $temp .= 'AND j.branch_fk = "' . $id . '"';
        }
         if ($start_date != '' || $end_date !='' ) {
            $odate = explode("/",$start_date);
            $start_date = $odate[2].='-'.$odate[1].'-'.$odate[0]. ' 00:00:00';

            $edate = explode("/",$end_date);
            $end_date = $edate[2].='-'.$edate[1].'-'.$edate[0]. ' 23:59:59';

            $temp .= 'AND j.date >= "' . $start_date . '" AND j.date <= "' . $end_date . '" ';
        }
        $query = $this->db->query("SELECT b.job_fk,b.test_fk AS test,COUNT(b.test_fk) AS Total,br.branch_name ,`test_master`.`test_name` FROM`approve_job_test` AS b INNER JOIN job_master AS j ON j.id = b.job_fk LEFT JOIN `branch_master` AS br ON br.id = j.`branch_fk` AND br.status = '1' LEFT JOIN `test_master` ON `test_master`.id=b.`test_fk` WHERE b.status = '1' AND j.`status` != '0'  $temp  GROUP BY b.`test_fk` ORDER BY Total DESC LIMIT 0,10");
        $query1 = $query->result_array();
        return $query1;
    }

      function getReagent($id = null) {
        $temp = '';
        if ($id != '') {
            $temp .= ' AND  inventory_inward_master.`branch_fk` = "' . $id . '"';
        }
   $query = $this->db->query("SELECT DISTINCT inventory_stock_master.item_fk ,inventory_stock_master.quantity,inventory_stock_master.used,inventory_item.reagent_name,inventory_category.name AS Category,inventory_unit_master.name AS Unit,branch_master.branch_name,(inventory_stock_master.`quantity`-inventory_stock_master.`used`) AS Available,inventory_item.box_price FROM inventory_stock_master LEFT JOIN `inventory_inward_master` ON inventory_stock_master.`inward_fk` = inventory_inward_master.id AND inventory_inward_master.`status` = '1' INNER JOIN `inventory_item` ON inventory_item.`id` = inventory_stock_master.item_fk AND inventory_item.`status` = '1' AND inventory_item.category_fk='3' LEFT JOIN inventory_unit_master ON inventory_unit_master.id =inventory_item.`unit_fk` AND inventory_unit_master.status='1' LEFT JOIN inventory_category ON inventory_category.id = inventory_item.`category_fk` AND inventory_category.status='1' LEFT JOIN branch_master on branch_master.id =inventory_inward_master.branch_fk and branch_master.status='1'  WHERE inventory_stock_master.`status` = '1' $temp");
        $query1 = $query->result_array();
        return $query1;
    }
       function lab_stationary($id = null,$start_date=null,$end_date =null) {
        $temp = '';
        if ($id != '') {
            $temp .= ' AND  inventory_inward_master.`branch_fk` = "' . $id . '"';
        }
    
        $query = $this->db->query("SELECT inventory_stock_master.quantity,inventory_stock_master.used,  (inventory_stock_master.`quantity`-inventory_stock_master.`used`) AS Available,inventory_stock_master.expire_date ,inventory_item.reagent_name,inventory_category.name AS Category,inventory_unit_master.name AS Unit,branch_master.branch_name FROM inventory_stock_master LEFT JOIN `inventory_inward_master` ON inventory_stock_master.`inward_fk` = inventory_inward_master.id AND inventory_inward_master.`status` = '1' INNER JOIN `inventory_item` ON inventory_item.`id` = inventory_stock_master.item_fk AND inventory_item.`status` = '1' AND inventory_item.category_fk !='3' LEFT JOIN inventory_unit_master ON inventory_unit_master.id =inventory_item.`unit_fk` AND inventory_unit_master.status='1' LEFT JOIN inventory_category ON inventory_category.id = inventory_item.`category_fk` AND inventory_category.status='1' LEFT JOIN branch_master on branch_master.id =inventory_inward_master.branch_fk and branch_master.status='1'  WHERE inventory_stock_master.`status` = '1' and inventory_stock_master.`expire_date` >=  CURDATE() AND inventory_stock_master.`expire_date` <= DATE_ADD(CURDATE(), INTERVAL 15 DAY) $temp ORDER BY inventory_stock_master.id DESC");
        $query1 = $query->result_array();
        return $query1;
    }
    function getpo_days($branch = null,$start_date=null,$end_date =null) { 
            $temp ='';
        if ($branch !='') {
            $temp .= " AND ip.branchfk ='" . $branch . "'";
        }
        if ($start_date !='' || $end_date !='') {
           $sdate = explode("/",$start_date);
            $start_date = $sdate[2].'-'.$sdate[1].'-'.$sdate[0] .' 00:00:00';

            $edate = explode("/",$end_date);
            $end_date = $edate[2].'-'.$edate[1].'-'.$edate[0].' 23:59:59';

        $temp .= 'AND ip.creteddate >= "' . $start_date . '" AND ip.creteddate <= "' . $end_date . '" ';
        }

       $query = $this->db->query("SELECT ip.id,ip.ponumber,ip.branchfk, br.branch_name,ip.vendorid,v.vendor_name,ip.creteddate FROM  `inventory_pogenrate` AS ip LEFT JOIN branch_master AS br ON br.id = ip.`branchfk` AND br.status='1' LEFT JOIN `inventory_vendor` AS v ON v.id = ip.vendorid AND v.status='1' WHERE ip.`status`!='0' $temp and ip.ponumber IS NOT NULL order by ip.id desc");
        $query1 = $query->result_array();
        return $query1;
    }
}