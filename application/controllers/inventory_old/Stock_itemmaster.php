<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stock_itemmaster extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('inventory/Intent_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
    }

    public function index() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        $branch = $this->input->get_post("branch");
        $itemget = $this->input->get_post("item");

        $temp = '';
        if ($branch != '') {

            $config = array();
            $config["per_page"] = 100;
            $config["uri_segment"] = 4;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $config['page_query_string'] = TRUE;
            $config["base_url"] = base_url() . "inventory/Intent_genrate";
            $search = "";
            if ($itemget != "") {
                $search = "AND s.item_fk='$itemget'";
            }


            if ($type == 1 || $type == 2 || $type == 8) {

                $totalRows = $this->Intent_model->get_val("SELECT count(s.`id`) as ID FROM inventory_stock_master s INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' $search GROUP BY s.item_fk ");


                $config["total_rows"] = $totalRows[0]["ID"];
                $this->pagination->initialize($config);
                $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;

                $data["query"] = $this->Intent_model->get_val("SELECT b.`branch_name`,s.`id`,s.item_fk,r.`reagent_name`,r.`category_fk`,SUM(s.quantity) as totalqty,SUM(s.`used`) AS stcok,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' $search  GROUP BY s.item_fk ORDER BY s.id ASC limit $page," . $config["per_page"] . "");

                /* echo $this->db->last_query(); die(); */
            } else {

                $totalRows = $this->Intent_model->get_val("SELECT count(s.`id`) as ID FROM inventory_stock_master s INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' And inw.`branch_fk` in(SELECT `branch_fk`  FROM `user_branch`  WHERE status= '1' $search AND user_fk = " . $login_id . ") GROUP BY r.`id` ");



                $config["total_rows"] = $totalRows[0]["ID"];
                $this->pagination->initialize($config);
                $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;

                $data["query"] = $this->Intent_model->get_val("SELECT b.`branch_name`,s.`id`,s.item_fk,r.`reagent_name`,r.`category_fk`,SUM(s.quantity) as totalqty,SUM(s.`used`) AS stcok,inw.`branch_fk` FROM inventory_stock_master s  INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' And inw.branch_fk='$branch' And inw.`branch_fk` in(SELECT `branch_fk`  FROM `user_branch`  WHERE status= '1' $search AND user_fk = " . $login_id . ") GROUP BY r.`id`  ORDER BY s.id ASC limit $page," . $config["per_page"] . "");
            }
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {

            $data["query"] = array();
            $data["links"] = "";
            $data["counts"] = 0;
        }

        if ($type == 1 || $type == 2 || $type == 8) {

            $data['branch_list'] = $this->Intent_model->get_val("select br.id as BranchId ,br.branch_name as BranchName  from branch_master as br where br.status='1'");
        } else {

            $data['branch_list'] = $this->Intent_model->get_val('select user_branch.branch_fk as BranchId, branch_master.id,branch_master.branch_name as BranchName from user_branch left join branch_master on branch_master.id = user_branch.branch_fk where user_branch.status="1" and user_branch.user_fk="' . $login_id . '"');
        }


        $data["itemlist"] = $this->Intent_model->get_val("SELECT id,`reagent_name` FROM inventory_item WHERE STATUS='1'");

        if ($type == 8) {
            $this->load->view('inventory/header', $data);
            $this->load->view('inventory/nav', $data);
        } else {
            $this->load->view('header', $data);
            $this->load->view('nav', $data);
        }
        $this->load->view('inventory/branchstcoks_views', $data);
        $this->load->view('footer');
    }

    public function exportcsv() {
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        $branch = $this->input->get_post("branch");
        $itemget = $this->input->get_post("item");

        $temp = '';
        if ($branch != '') {

            $search = "";
            if ($itemget != "") {
                $search = "AND s.item_fk='$itemget'";
            }


            if ($type == 1 || $type == 2 || $type == 8) {




                $data["query"] = $this->Intent_model->get_val("SELECT b.`branch_name`,s.`id`,r.`reagent_name`,r.`category_fk`,SUM(s.quantity) as totalqty,SUM(s.`used`) AS stcok,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' $search  GROUP BY s.item_fk ORDER BY s.id ASC");
            } else {




                $config["total_rows"] = $totalRows[0]["ID"];
                $this->pagination->initialize($config);
                $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;

                $data["query"] = $this->Intent_model->get_val("SELECT b.`branch_name`,s.`id`,r.`reagent_name`,r.`category_fk`,SUM(s.quantity) as totalqty,SUM(s.`used`) AS stcok,inw.`branch_fk` FROM inventory_stock_master s  INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' And inw.branch_fk='$branch' And inw.`branch_fk` in(SELECT `branch_fk`  FROM `user_branch`  WHERE status= '1' $search AND user_fk = " . $login_id . ") GROUP BY r.`id`  ORDER BY s.id ASC");
            }
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {

            $data["query"] = array();
            $data["links"] = "";
            $data["counts"] = 0;
        }

        $query = $data["query"];

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"itemstocklist.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No", "Branch Name", "Category", "Item", "Stock"));
        $i = 0;

        foreach ($query as $key) {

            $itetype = $key["category_fk"];
            $whotype = "";
            if ($itetype == '1') {
                $whotype = "Stationary";
                $cat = "Stationary";
            }
            if ($itetype == '2') {
                $whotype = "Consumables";
                $cat = "Lab Consumables";
            }
            if ($itetype == '3') {
                $whotype = "Reagent";
                $cat = "Reagent";
            }

            $i++;
            fputcsv($handle, array($i, $key["branch_name"], $whotype, $key["reagent_name"], $key["totalqty"] - $key["stcok"]));
        }

        fclose($handle);
        exit;
    }

    public function stockdetils() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        $branch = $this->input->get_post("branch");
        $itemget = $this->input->get_post("item");

        $temp = '';
        if ($branch != '' && $itemget != '') {

            /* $config = array();
              $config["per_page"] =100;
              $config["uri_segment"] = 4;
              $config['next_link'] = 'Next &rsaquo;';
              $config['prev_link'] = '&lsaquo; Previous';
              $config['page_query_string'] = TRUE;
              $config["base_url"]=base_url()."inventory/Intent_genrate"; */
            $search = "";
            if ($itemget != "") {
                $search = "AND s.item_fk='$itemget'";
            }


            if ($type == 1 || $type == 2 || $type == 8) {

                /* $totalRows=$this->Intent_model->get_val("SELECT count(s.`id`) as ID FROM inventory_stock_master s INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' $search GROUP BY s.id ");

                  $config["total_rows"] = $totalRows[0]["ID"];
                  $this->pagination->initialize($config);
                  $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0; */

                $data["query"] = $this->Intent_model->get_val("SELECT s.`id`,s.item_fk,s.batch_no,s.`expire_date`,s.`quantity`,s.`used` AS stcok,b.`branch_name`,s.`id`,r.`reagent_name`,r.`category_fk`,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' $search  GROUP BY s.id ORDER BY s.id ASC");
            } else {

                /* $totalRows=$this->Intent_model->get_val("SELECT count(s.`id`) as ID FROM inventory_stock_master s INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' And inw.`branch_fk` in(SELECT `branch_fk`  FROM `user_branch`  WHERE status= '1' $search AND user_fk = " . $login_id . ") GROUP BY s.`id` ");

                  $config["total_rows"] = $totalRows[0]["ID"];
                  $this->pagination->initialize($config);
                  $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0; */

                $data["query"] = $this->Intent_model->get_val("SELECT s.`id`,s.item_fk,s.batch_no,s.`expire_date`,s.`quantity`,s.`used` AS stcok,b.`branch_name`,s.`id`,r.`reagent_name`,r.`category_fk`,SUM(s.`used`) AS stcok,inw.`branch_fk` FROM inventory_stock_master s  INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' And inw.branch_fk='$branch' And inw.`branch_fk` in(SELECT `branch_fk`  FROM `user_branch`  WHERE status= '1' $search AND user_fk = " . $login_id . ") GROUP BY r.`id` ORDER BY s.id ASC");
            }
            /* $data["links"] = $this->pagination->create_links();
              $data["counts"] = $page; */
        } else {

            $data["query"] = array();
            $data["links"] = "";
            $data["counts"] = 0;
        }


        if ($type == 8) {
            $this->load->view('inventory/header', $data);
            $this->load->view('inventory/nav', $data);
        } else {
            $this->load->view('header', $data);
            $this->load->view('nav', $data);
        }
        $this->load->view('inventory/branchstcoksdetils_views', $data);
        $this->load->view('footer');
    }

    public function stockdetilsexport() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        $branch = $this->input->get_post("branch");
        $itemget = $this->input->get_post("item");

        $temp = '';
        if ($branch != '' && $itemget != '') {

            if ($itemget != "") {
                $search = "AND s.item_fk='$itemget'";
            }


            if ($type == 1 || $type == 2 || $type == 8) {


                $data["query"] = $this->Intent_model->get_val("SELECT s.`id`,s.item_fk,s.batch_no,s.`expire_date`,s.`quantity`,s.`used` AS stcok,b.`branch_name`,s.`id`,r.`reagent_name`,r.`category_fk`,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' $search  GROUP BY s.id ORDER BY s.id ASC");
            } else {


                $data["query"] = $this->Intent_model->get_val("SELECT s.`id`,s.item_fk,s.batch_no,s.`expire_date`,s.`quantity`,s.`used` AS stcok,b.`branch_name`,s.`id`,r.`reagent_name`,r.`category_fk`,SUM(s.`used`) AS stcok,inw.`branch_fk` FROM inventory_stock_master s  INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' And inw.branch_fk='$branch' And inw.`branch_fk` in(SELECT `branch_fk`  FROM `user_branch`  WHERE status= '1' $search AND user_fk = " . $login_id . ") GROUP BY r.`id` ORDER BY s.id ASC");
            }
        } else {

            $data["query"] = array();
            $data["links"] = "";
            $data["counts"] = 0;
        }

        $query = $data["query"];

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"itemstockdetilslist.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No", "Branch Name", "Item", "Batch number", "Expiry date", "Quantity", "Used", "Available"));
        $i = 0;

        foreach ($query as $key) {
            $i++;
            fputcsv($handle, array($i, $key["branch_name"], $key["reagent_name"], $key["batch_no"], date("d-m-Y", strtotime($key["expire_date"])), $key["quantity"], $key["stcok"], ($key["quantity"] - $key["stcok"])));
        }
        fclose($handle);
        exit;
    }

}
