<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->helper('string');
        if (!is_vendorlogin()) {
            redirect('Login');
        }
    }

 function dashboard() {
        if (!is_vendorlogin()) {
            redirect('Login');
        }
        $data["login_data"] = is_vendorlogin();
        $login_id = $data["login_data"]['id'];

//        $temp .= ' AND p.`status` ="1"';
        $temp .= ' AND (p.`status` ="1" OR p.`status` ="4")';
        $checksatus = "  AND (SELECT COUNT(`inventory_popayment`.id) FROM inventory_popayment WHERE inventory_popayment.`status`='1' AND inventory_popayment.poid=p.id)='0'";
        
//        $query = $this->master_model->get_val("SELECT p.id,p.`creteddate`,p.`discount`,p.`poprice`,p.`indentcode`,p.`status`,b.`branch_name`,p.ponumber,v.vendor_name,v.mobile as vmobile,v.email_id as vemail,a.`name` AS cretdby,p.i_notiy,p.a_notity,p.b_notity FROM inventory_pogenrate p LEFT JOIN `branch_master` b ON b.`id`=p.`branchfk` LEFT JOIN inventory_vendor v ON v.`id`=p.`vendorid` LEFT JOIN admin_master a ON a.`id`=p.`cretedby` WHERE p.`status` !='0' AND vendorid = '$login_id' $temp $checksatus order by p.id desc");
        $query = $this->master_model->get_val("SELECT p.id,p.`creteddate`,p.`discount`,p.`poprice`,p.`indentcode`,p.`status`,b.`branch_name`,p.ponumber,v.vendor_name,v.mobile as vmobile,v.email_id as vemail,a.`name` AS cretdby,p.i_notiy,p.a_notity,p.b_notity FROM inventory_pogenrate p LEFT JOIN `branch_master` b ON b.`id`=p.`branchfk` LEFT JOIN inventory_vendor v ON v.`id`=p.`vendorid` LEFT JOIN admin_master a ON a.`id`=p.`cretedby` WHERE p.`status` !='0' AND vendorid = '$login_id' $temp order by p.id desc");
        
//        $data['query'] = array();
//        foreach ($query as $key) {
//            $inward_Details = $this->master_model->get_val("SELECT `inventory_inward_master`.id FROM inventory_inward_master WHERE inventory_inward_master.`status`='1' AND inventory_inward_master.`po_fk`='" . $key["id"] . "'");
//            $key["inward"] = $inward_Details;
//            $po_bill_Details = $this->master_model->get_val("SELECT `inventory_popayment`.id FROM inventory_popayment WHERE inventory_popayment.`status`='1' AND inventory_popayment.`poid`='" . $key["id"] . "'");
//            $key["pobilldetails"] = $po_bill_Details;
//            $qry = "SELECT 
//  `inventory_poitem`.`itemid`,
//  `inventory_poitem`.`itemnos`,
//  `inventory_item`.`reagent_name`,
//  inventory_unit_master.`name` AS unit,
//  `inventory_category`.`name` AS category_name,
//  inventory_poitem.`poid` 
//FROM
//  `inventory_poitem` 
//  LEFT JOIN `inventory_item` 
//    ON `inventory_poitem`.`itemid` = `inventory_item`.`id` 
//  LEFT JOIN `inventory_category` 
//    ON `inventory_item`.`category_fk` = `inventory_category`.`id` AND inventory_item.`status`='1'
//  LEFT JOIN `inventory_unit_master` 
//    ON `inventory_unit_master`.`id` = `inventory_item`.`unit_fk` AND inventory_unit_master.`status`='1' 
//WHERE inventory_poitem.`poid`='" . $key["id"] . "'";
//            
//            if (in_array($key["status"], array("1", "2", "3"))) {
//                $qry .= " and `inventory_poitem`.status='1'";
//            }
//
//
//            $qry .= " ORDER BY `inventory_category`.`name` ASC";
//            $key["po_item_list"] = $this->master_model->get_val($qry);
//            
//            $key["stock_master"] = $this->master_model->get_val("SELECT `inventory_stock_master`.id FROM inventory_stock_master WHERE inventory_stock_master.`status`='1' AND inventory_stock_master.`inward_fk`='" . $inward_Details[0]["id"] . "'");
//            
//            $data['query'][] = $key;
//        }
//        
//        $i=0;
//        foreach ($data['query'] as $row) {
//            if (count($row["inward"]) > 0 && count($row["pobilldetails"]) == 0 && count($row["stock_master"]) > 0) {
//                continue;
//            }
//            
//            $i = $i+1;
//        }
        

        //$data['total_department'] = $i;
        $data['total_department'] = count($query);

        $this->load->view('vendor/header', $data);
        $this->load->view('vendor/nav', $data);
        $this->load->view('vendor/dashboard', $data);
        $this->load->view('vendor/footer');
    }

}
