<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Intent_Request extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('inventory/Intent_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
    }

    function sub_index() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $id = $this->input->get_post('intent_name');
        $branch_fk = $this->input->get_post('branch_fk');
        $start_date = $this->input->get_post('start_date');
        $status = $this->input->get_post('status');
        $db = $this->Intent_model->get_val("SELECT intent_id as Intent FROM inventory_intent_master WHERE intent_id IS NOT NULL and status !='0'  and status !='1'");
        if ($db == NULL) {
            $data['generate'] = '1';
        } else {
            $db1 = $this->Intent_model->get_val("SELECT max(intent_id) + 1 as Intent  FROM inventory_intent_master WHERE intent_id IS NOT NULL and status !='0' ");
            $data['generate'] = $db1;
        }
        $user_branch = array();
        if (!in_array($data["login_data"]['type'], array("1", "2", "8"))) {
            $check_branch = $this->Intent_model->get_val("SELECT GROUP_CONCAT(branch_fk) AS bid FROM `user_branch` WHERE `status`='1' AND user_fk='" . $data["login_data"]['id'] . "' GROUP BY user_fk");
            $user_branch = $check_branch[0]['bid'];
        } else {
            $check_branch = $this->Intent_model->get_val("SELECT GROUP_CONCAT(id) AS bid FROM `branch_master` WHERE `status`='1' ");
            $user_branch = $check_branch[0]['bid'];
        }



        if ($id != '' || $branch_fk != '' || $start_date != '' || $status != '') {

            $totalRows = $this->Intent_model->intent_num($id, $branch_fk, $start_date, $status);

            $data['intenet_id'] = $id;
            $data['branch_fk'] = $branch_fk;
            $data['new_date'] = $start_date;
            $data['status'] = $status;

            $config = array();
            $config["base_url"] = base_url() . "inventory/Intent_Request/sub_index?intent_name=$id&branch_fk=$branch_fk&start_date=$start_date&status=$status";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 4;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';

            $config['page_query_string'] = TRUE;
            if ($type == 8 || $type == 1 || $type == 2) {
                $status = 1;
            }
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            $data["query"] = $this->Intent_model->intent_list($id, $branch_fk, $start_date, $status, $user_branch, $config["per_page"], $page);

            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
            $data["final_array"] = array();

            foreach ($data["query"] as $val) {

                $intent_id = $val['indent_fk'];
                $getdata = $this->Intent_model->get_val('select iim.intent_id,iir.*,GROUP_CONCAT(DISTINCT iir.`quantity`)AS Quantity, iir.*, GROUP_CONCAT(DISTINCT ic.`id`) AS CategoryID, GROUP_CONCAT(DISTINCT ic.`name`) AS Category_Name, it.id AS Reagent_Id, GROUP_CONCAT(DISTINCT it.reagent_name) AS Reagent_name from inventory_intent_master as iim LEFT JOIN inventory_intent_request as iir on iim.intent_id= iir.indent_fk and iir.status="1" INNER JOIN inventory_item AS it ON it.id=iir.category_fk INNER JOIN inventory_category AS ic ON ic.id= it.`category_fk` where iir.indent_fk = "' . $intent_id . '" AND it.reagent_name != "" AND it.reagent_name IS NOT NULL GROUP BY ic.id');


                $new_mr_data = array();
                foreach ($getdata as $key => $val_new) {
                    $cate_id = $val_new['CategoryID'];
                    $intent_id = $val_new['intent_id'];
                    $mrdata = $this->Intent_model->get_val('SELECT iim.intent_id, iir.*, GROUP_CONCAT(DISTINCT iir.`quantity`) AS Quantity,GROUP_CONCAT(DISTINCT ic.`id`) AS CategoryID,GROUP_CONCAT(DISTINCT ic.`name`) AS Category_Name,it.id AS Reagent_Id,it.unit_fk ,GROUP_CONCAT(DISTINCT it.reagent_name) AS Reagent_name,GROUP_CONCAT(DISTINCT ium.name) AS Unit_name  FROM inventory_intent_master AS iim 
  LEFT JOIN inventory_intent_request AS iir  ON iim.intent_id = iir.indent_fk AND iir.status = "1" INNER JOIN inventory_item AS it ON it.id = iir.category_fk INNER JOIN inventory_category AS ic ON ic.id = it.`category_fk` LEFT JOIN inventory_unit_master AS ium  ON ium.id = it.unit_fk AND ium.status = "1" WHERE iir.indent_fk = "' . $intent_id . '" AND ic.id = "' . $cate_id . '" AND it.reagent_name != "" AND it.reagent_name IS NOT NULL GROUP BY ic.id ');

                    $mrkey["reagent"] = $mrdata;
                    $new_mr_data[] = $mrkey;
                }

                $val['po_details'] = $this->Intent_model->get_val("SELECT * FROM `inventory_pogenrate` WHERE `status`='1' AND indentcode='" . $val["intent_id"] . "'");
                $val["details"] = $new_mr_data;

                $data["final_array"][] = $val;
            }
        } else {
            $srch = array();
            $totalRows = $this->Intent_model->intent_num($id, $branch_fk, $start_date, $status);

            $config = array();
            $config["base_url"] = base_url() . "inventory/Intent_Request/sub_index";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 4;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            if ($type == 8 || $type == 1 || $type == 2) {
                $status = 1;
            }
            if ($login_id == 50 || $login_id == 542) {
                $status = "1,2";
            }
            $data["query"] = $this->Intent_model->intent_list($id, $branch_fk, $start_date, $status, $user_branch, $config["per_page"], $page);
            $data["final_array"] = array();
            $new_mr_data = array();
            foreach ($data["query"] as $val) {
                $intent_id = $val['indent_fk'];
                $getdata = $this->Intent_model->get_val('select iim.intent_id,iir.*,GROUP_CONCAT(DISTINCT iir.`quantity`)AS Quantity, iir.*, GROUP_CONCAT(DISTINCT ic.`id`) AS CategoryID, GROUP_CONCAT(DISTINCT ic.`name`) AS Category_Name, it.id AS Reagent_Id, GROUP_CONCAT(DISTINCT it.reagent_name) AS Reagent_name from inventory_intent_master as iim LEFT JOIN inventory_intent_request as iir on iim.intent_id= iir.indent_fk and iir.status="1" INNER JOIN inventory_item AS it ON it.id=iir.category_fk INNER JOIN inventory_category AS ic ON ic.id= it.`category_fk` where iir.indent_fk = "' . $intent_id . '" AND it.reagent_name != "" AND it.reagent_name IS NOT NULL GROUP BY ic.id');
                $new_mr_data = array();
                foreach ($getdata as $key => $val_new) {
                    $cate_id = $val_new['CategoryID'];
                    $intent_id = $val_new['intent_id'];
                    $mrdata = $this->Intent_model->get_val('SELECT iim.intent_id,GROUP_CONCAT(DISTINCT iir.`quantity`) AS Quantity,iir.*,
  GROUP_CONCAT(DISTINCT ic.`id`) AS CategoryID,
  GROUP_CONCAT(DISTINCT ic.`name`) AS Category_Name,
  it.id AS Reagent_Id,
   it.unit_fk ,
  GROUP_CONCAT(DISTINCT it.reagent_name) AS Reagent_name,
  GROUP_CONCAT(DISTINCT ium.name) AS Unit_name  
FROM
  inventory_intent_master AS iim 
  LEFT JOIN inventory_intent_request AS iir 
    ON iim.intent_id = iir.indent_fk 
    AND iir.status = "1" 
  INNER JOIN inventory_item AS it 
    ON it.id = iir.category_fk 
  INNER JOIN inventory_category AS ic 
    ON ic.id = it.`category_fk` 
  LEFT JOIN inventory_unit_master AS ium
    ON ium.id = it.unit_fk 
    AND ium.status = "1" 
WHERE iir.indent_fk = "' . $intent_id . '"
  AND ic.id = "' . $cate_id . '" 
  AND it.reagent_name != "" 
  AND it.reagent_name IS NOT NULL 
GROUP BY ic.id ');
                    $mrkey["reagent"] = $mrdata;
                    $new_mr_data[] = $mrkey;
                }
                $val['po_details'] = $this->Intent_model->get_val("SELECT * FROM `inventory_pogenrate` WHERE `status`='1' AND indentcode='" . $val["intent_id"] . "'");
                $val["details"] = $new_mr_data;
                $data["final_array"][] = $val;
            }
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
//echo "<pre>";print_r($data["final_array"]); die();
        $data['stationary_list'] = $this->Intent_model->get_val("SELECT it.id,it.reagent_name,it.unit_fk AS ufk,ium.name AS test_new_val FROM
  inventory_item AS it LEFT JOIN `inventory_unit_master` AS ium ON ium.id= it.`unit_fk` AND ium.`status`='1'WHERE it.category_fk = '1' AND it.status = '1' ");


        $data['lab_consum'] = $this->Intent_model->get_val("SELECT it.id,it.reagent_name,it.unit_fk,ium.name AS uname FROM inventory_item AS it LEFT JOIN `inventory_unit_master` AS ium ON ium.`id` = it.`unit_fk` AND ium.`status`='1' WHERE it.category_fk='2' AND it.status='1'");


        $data['category_list'] = $this->Intent_model->get_val("select * from inventory_item where category_fk='3' and status='1'");
        if ($type == 1 || $type == 2 || $type == 8) {
            $data['branch_list'] = $this->Intent_model->get_val("select id as BranchId,branch_name as BranchName from branch_master where status='1' order by branch_master.branch_name asc");
        } else {
            $data['branch_list'] = $this->Intent_model->get_val('select user_branch.branch_fk as BranchId, branch_master.id,branch_master.branch_name as BranchName from user_branch left join branch_master on branch_master.id = user_branch.branch_fk where user_branch.status="1" and user_branch.user_fk="' . $login_id . '" order by branch_master.branch_name asc');
        }
        if ($type == 8) {
            $this->load->view('inventory/header', $data);
            $this->load->view('inventory/nav', $data);
        } else {
            $this->load->view('header', $data);
            $this->load->view('nav', $data);
        }
        $this->load->view('inventory/intent_request', $data);
        $this->load->view('footer');
    }

    function intent_list() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $id = $this->input->get_post('intent_name');
        $branch_fk = $this->input->get_post('branch_fk');
        $start_date = $this->input->get_post('start_date');
        $status = $this->input->get_post('status');
        $db = $this->Intent_model->get_val("SELECT intent_id as Intent FROM inventory_intent_master WHERE intent_id IS NOT NULL and status !='0' ");
        if ($db == NULL) {
            $data['generate'] = '1';
        } else {
            $db1 = $this->Intent_model->get_val("SELECT max(intent_id) + 1 as Intent  FROM inventory_intent_master WHERE intent_id IS NOT NULL and status !='0' ");
            $data['generate'] = $db1;
        }
        if ($id != '' || $branch_fk != '' || $start_date != '' || $status != '') {

            $totalRows = $this->Intent_model->intent_num($id, $branch_fk, $start_date, $status);

            $data['intenet_id'] = $id;
            $data['branch_fk'] = $branch_fk;
            $data['new_date'] = $start_date;
            $data['status'] = $status;

            $config = array();
            $config["base_url"] = base_url() . "inventory/Intent_Request/sub_index?intent_name=$id&branch_fk=$branch_fk&start_date=$start_date&status=$status";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 4;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';

            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            $data["query"] = $this->Intent_model->intent_list($id, $branch_fk, $start_date, $status, $config["per_page"], $page);

            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
            $data["final_array"] = array();

            foreach ($data["query"] as $val) {

                $intent_id = $val['indent_fk'];
                $getdata = $this->Intent_model->get_val('select iim.intent_id,iir.*,GROUP_CONCAT(DISTINCT iir.`quantity`)AS Quantity, iir.*, GROUP_CONCAT(DISTINCT ic.`id`) AS CategoryID, GROUP_CONCAT(DISTINCT ic.`name`) AS Category_Name, it.id AS Reagent_Id, GROUP_CONCAT(DISTINCT it.reagent_name) AS Reagent_name from inventory_intent_master as iim LEFT JOIN inventory_intent_request as iir on iim.intent_id= iir.indent_fk and iir.status="1" INNER JOIN inventory_item AS it ON it.id=iir.category_fk INNER JOIN inventory_category AS ic ON ic.id= it.`category_fk` where iir.indent_fk = "' . $intent_id . '" AND it.reagent_name != "" AND it.reagent_name IS NOT NULL GROUP BY ic.id');


                $new_mr_data = array();
                foreach ($getdata as $key => $val_new) {
                    $cate_id = $val_new['CategoryID'];
                    $intent_id = $val_new['intent_id'];
                    $mrdata = $this->Intent_model->get_val('SELECT 
  iim.intent_id,
  iir.*,
  GROUP_CONCAT(DISTINCT iir.`quantity`) AS Quantity,
  iir.*,
  GROUP_CONCAT(DISTINCT ic.`id`) AS CategoryID,
  GROUP_CONCAT(DISTINCT ic.`name`) AS Category_Name,
  it.id AS Reagent_Id,
   it.unit_fk ,
  GROUP_CONCAT(DISTINCT it.reagent_name) AS Reagent_name,
  GROUP_CONCAT(DISTINCT ium.name) AS Unit_name  
FROM
  inventory_intent_master AS iim 
  LEFT JOIN inventory_intent_request AS iir 
    ON iim.intent_id = iir.indent_fk 
    AND iir.status = "1" 
  INNER JOIN inventory_item AS it 
    ON it.id = iir.category_fk 
  INNER JOIN inventory_category AS ic 
    ON ic.id = it.`category_fk` 
  LEFT JOIN inventory_unit_master AS ium
    ON ium.id = it.unit_fk 
    AND ium.status = "1" 
WHERE iir.indent_fk = "' . $intent_id . '" 
  AND ic.id = "' . $cate_id . '" 
  AND it.reagent_name != "" 
  AND it.reagent_name IS NOT NULL 
GROUP BY ic.id ');

                    $mrkey["reagent"] = $mrdata;
                    $new_mr_data[] = $mrkey;
                }


                $val["details"] = $new_mr_data;

                $data["final_array"][] = $val;
            }
        } else {
            $srch = array();
            $totalRows = $this->Intent_model->intent_num($id, $branch_fk, $start_date, $status);

            $config = array();
            $config["base_url"] = base_url() . "inventory/Intent_Request/sub_index";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 4;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            $data["query"] = $this->Intent_model->intent_list($id, $branch_fk, $start_date, $status, $config["per_page"], $page);

            $data["final_array"] = array();
            $new_mr_data = array();
            foreach ($data["query"] as $val) {
                $intent_id = $val['indent_fk'];
                $getdata = $this->Intent_model->get_val('select iim.intent_id,GROUP_CONCAT(DISTINCT iir.`quantity`)AS Quantity, iir.*, GROUP_CONCAT(DISTINCT ic.`id`) AS CategoryID, GROUP_CONCAT(DISTINCT ic.`name`) AS Category_Name, it.id AS Reagent_Id, GROUP_CONCAT(DISTINCT it.reagent_name) AS Reagent_name from inventory_intent_master as iim LEFT JOIN inventory_intent_request as iir on iim.intent_id= iir.indent_fk and iir.status="1" INNER JOIN inventory_item AS it ON it.id=iir.category_fk INNER JOIN inventory_category AS ic ON ic.id= it.`category_fk` where iir.indent_fk = "' . $intent_id . '" AND it.reagent_name != "" AND it.reagent_name IS NOT NULL GROUP BY ic.id');


                $new_mr_data = array();
                foreach ($getdata as $key => $val_new) {
                    $cate_id = $val_new['CategoryID'];
                    $intent_id = $val_new['intent_id'];
                    $mrdata = $this->Intent_model->get_val('SELECT 
  iim.intent_id,
  iir.*,
  GROUP_CONCAT(DISTINCT iir.`quantity`) AS Quantity,
  GROUP_CONCAT(DISTINCT ic.`id`) AS CategoryID,
  GROUP_CONCAT(DISTINCT ic.`name`) AS Category_Name,
  it.id AS Reagent_Id,
   it.unit_fk ,
  GROUP_CONCAT(DISTINCT it.reagent_name) AS Reagent_name,
  GROUP_CONCAT(DISTINCT ium.name) AS Unit_name  
FROM
  inventory_intent_master AS iim 
  LEFT JOIN inventory_intent_request AS iir 
    ON iim.intent_id = iir.indent_fk 
    AND iir.status = "1" 
  INNER JOIN inventory_item AS it 
    ON it.id = iir.category_fk 
  INNER JOIN inventory_category AS ic 
    ON ic.id = it.`category_fk` 
  LEFT JOIN inventory_unit_master AS ium
    ON ium.id = it.unit_fk 
    AND ium.status = "1" 
WHERE iir.indent_fk = "' . $intent_id . '" 
  AND ic.id = "' . $cate_id . '" 
  AND it.reagent_name != "" 
  AND it.reagent_name IS NOT NULL 
GROUP BY ic.id ');

                    $mrkey["reagent"] = $mrdata;
                    $new_mr_data[] = $mrkey;
                }


                $val["details"] = $new_mr_data;

                $data["final_array"][] = $val;
            }


            $data["links"] = $this->pagination->create_links();

            $data["counts"] = $page;
        }

        $data['stationary_list'] = $this->Intent_model->get_val("SELECT it.id,it.reagent_name,it.unit_fk AS ufk,ium.name AS test_new_val FROM
  inventory_item AS it LEFT JOIN `inventory_unit_master` AS ium ON ium.id= it.`unit_fk` AND ium.`status`='1'WHERE it.category_fk = '1' AND it.status = '1' ");


        $data['lab_consum'] = $this->Intent_model->get_val("SELECT it.id,it.reagent_name,it.unit_fk,ium.name AS uname FROM inventory_item AS it LEFT JOIN `inventory_unit_master` AS ium ON ium.`id` = it.`unit_fk` AND ium.`status`='1' WHERE it.category_fk='2' AND it.status='1'");


        $data['category_list'] = $this->Intent_model->get_val("select * from inventory_item where category_fk='3' and status='1'");
        $data['branch_list'] = $this->Intent_model->get_val("select * from branch_master where status='1'");

        $this->load->view('inventory/header', $data);
        $this->load->view('inventory/nav', $data);
        $this->load->view('inventory/intent_request_new', $data);
        $this->load->view('inventory/footer');
    }

    function intent_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $mid = $this->Intent_model->get_val("SELECT intent_id FROM `inventory_intent_master` ORDER BY id DESC LIMIT 0,1");
        $data["login_data"] = logindata();
        $loginid = $data["login_data"]["id"];
        $type = $data["login_data"]["type"];
        $item_list = $this->input->post("item");
        $quantity = $this->input->post("quantity");
        $lot = $this->input->post("lot");
        $vendor = $this->input->post("vendor");
        $remark = $this->input->post("remark");
        $data1 = array(
            "intent_id" => $mid[0]["intent_id"] + 1,
            'branch_fk' => $this->input->post('branch_fk'),
            'status' => 2,
            "created_date" => date("Y-m-d H:i:s"),
            "created_by" => $loginid,
            "remark" => $remark
        );

        $id = $this->Intent_model->master_fun_insert("inventory_intent_master", $data1);

        $cnt = 0;
        foreach ($item_list as $kkey) {
            $data = array(
                "indent_fk" => $id,
                "category_fk" => $kkey,
                "quantity" => $quantity[$cnt],
                "status" => 1,
                "created_date" => date("Y-m-d H:i:s"),
                "created_by" => $loginid,
                "lot_number" => $lot[$cnt],
                "vender_id" => $vendor[$cnt]
            );
            $this->Intent_model->master_fun_insert("inventory_intent_request", $data);
            $cnt++;
        }

        $this->session->set_flashdata("success", array("Indent successfully added."));
        if ($type == 8) {
            redirect("inventory/Intent_Request/intent_list/", "refresh");
        } else {
            redirect("inventory/Intent_Request/sub_index/", "refresh");
        }
    }

    function edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $tid = $this->uri->segment('4');

        $bid = $this->uri->segment('5');

        $edit_item_details = $this->Intent_model->get_val("SELECT 
  inventory_intent_master.*,
  inventory_intent_request.*,
  `inventory_item`.`unit_fk`,
  `inventory_item`.`reagent_name` AS item_name,
  inventory_category.name AS Category_name,
  inventory_unit_master.name AS Unit_name
FROM
  inventory_intent_master 
  LEFT JOIN inventory_intent_request 
    ON inventory_intent_master.intent_id = inventory_intent_request.indent_fk 
    AND inventory_intent_request.status = '1' 
  INNER JOIN `inventory_item` 
    ON `inventory_item`.`id` = `inventory_intent_request`.`category_fk` 
  LEFT JOIN inventory_category 
    ON inventory_category.id = inventory_item.category_fk AND inventory_category.`status`='1'
      LEFT JOIN inventory_unit_master 
    ON inventory_unit_master.id = inventory_item.unit_fk AND inventory_unit_master.status='1' 
WHERE inventory_intent_master.intent_id = '" . $tid . "' 
  AND inventory_intent_master.status != '0'");
        /* echo $this->db->last_query();die; */

        $data['branch'] = $this->Intent_model->get_val('select user_branch.branch_fk as BranchId, branch_master.id,branch_master.branch_name as BranchName from user_branch left join branch_master on branch_master.id = user_branch.branch_fk where user_branch.status="1" and user_branch.user_fk="' . $login_id . '"');

        $selected_item = array();
        $selected_quentity = array();
        $selected_item_name = array();
        foreach ($edit_item_details as $key) {
            $selected_item[] = $key["category_fk"];
            $selected_quentity[] = $key["quantity"];
            $selected_item_name[] = $key["item_name"];
            $selected_category[] = $key['Category_name'];
        }
        $item_statonary = $this->Intent_model->get_val("SELECT it.id,it.reagent_name,it.unit_fk AS utk_st,ium.name AS UnitSub FROM inventory_item AS it LEFT JOIN `inventory_unit_master` AS ium ON ium.`id` = it.`unit_fk` AND ium.`status`='1' WHERE it.status = '1' AND it.category_fk = 1 and it.branch_fk='" . $bid . "'");

        $item_Consumables = $this->Intent_model->get_val("SELECT it.id,it.reagent_name,it.unit_fk AS UNIT_FK,ium.name AS UnitName FROM inventory_item AS it LEFT JOIN `inventory_unit_master` AS ium ON ium.`id` = it.`unit_fk` AND ium.`status` = '1' WHERE it.status = '1' AND it.category_fk = 2 and it.branch_fk='" . $bid . "'");

        $vendor_data = $this->Intent_model->get_val("SELECT 
  `inventory_vendor`.*,
  `test_cities`.`name` AS test_city 
FROM
  `inventory_vendor` 
  INNER JOIN `test_cities` 
    ON `test_cities`.`city_fk` = `inventory_vendor`.`city_fk` 
  INNER JOIN `branch_master` 
    ON `branch_master`.`city` = `test_cities`.`id` 
    WHERE `inventory_vendor`.`status`='1' AND `branch_master`.`id`='" . $bid . "'");
        // $item_details = $this->Intent_model->get_val("select id,reagent_name from inventory_item where status='1' and category_fk=3 and machine IS NOT NULL and machine !=0");
        ?>
        <input type="hidden" name="indent_fk" value="<?= $tid ?>"/>
        <div class="form-group">      
            <label for="message-text" class="form-control-label">Edit Reagent:</label>
            <select class="chosen chosen-select new_update_edit" name="item" id="selected_item_edit" onchange="select_item_edit('Reagent', this);">
                <option value="">--Select--</option>

            </select>
        </div>
        <div class="form-group">
            <label for="message-text" class="form-control-label">Edit Consumables:</label>
            <select class="chosen chosen-select consume_update" name="item" id="consumables_id_edit" onchange="select_item_edit('Consumables', this);">
                <option value="">--Select--</option>
                <?php
                foreach ($item_Consumables as $mkey) {
                    if ($mkey['UNIT_FK'] != '') {
                        $unit_name = '(' . $mkey['UnitName'] . ')';
                    }
                    if (!in_array($mkey["id"], $selected_item)) {
                        ?>
                        <option value="<?= $mkey["id"] ?>"><?= $mkey["reagent_name"] . $unit_name ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </div>
        <div id="vendor_data" style="display:none;"><option value="">--Select--</option><?php foreach ($vendor_data as $vkey): ?>
                <option value="<?= $vkey["id"] ?>"><?= $vkey["vendor_name"] ?></option>
            <?php endforeach; ?></div>
        <div class="form-group">
            <label for="message-text" class="form-control-label">Edit Stationary:</label>
            <select class="chosen chosen-select stationary_update" name="item" id="stationary_id_edit" onchange="select_item_edit('Stationary', this);">
                <option value="">--Select--</option>
                <?php
                foreach ($item_statonary as $val) {
                    if ($val['utk_st'] != '') {
                        $u_name = '(' . $val["UnitSub"] . ')';
                    }
                    if (!in_array($val["id"], $selected_item)) {
                        ?>
                        <option value="<?= $val["id"] ?>"><?= $val["reagent_name"] . $u_name ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>LOT Number</th>
                    <th>Vendor</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="selected_items_edit">
            <span id="error_id_new" style="color:red;"></span>
            <?php
            $cnt = 0;
            foreach ($edit_item_details as $ikey) {
                if ($ikey['unit_fk'] != '') {
                    $unit_test = '(' . $ikey['Unit_name'] . ')';
                }
                ?>
                <tr id="tr_edit_<?= $cnt; ?>">
                    <td><?= $ikey["item_name"] . $unit_test ?></td>
                    <td><?= $ikey['Category_name']; ?></td>
                    <td><input type="hidden" name="item[]" value="<?= $ikey["category_fk"] ?>"><input type="text" name="quantity[]" required="" value="<?= $ikey["quantity"] ?>" class="form-control"/></td>
                    <td><input type="text" name="lot[]" value="<?= $ikey["lot_number"] ?>" class="form-control"/></td>
                    <td><select name="vendor[]" class="form-control">
                            <option value="">--Select--</option>
                            <?php foreach ($vendor_data as $vkey): ?>
                                <option value="<?= $vkey["id"] ?>" <?php
                                if ($ikey["vender_id"] == $vkey["id"]) {
                                    echo "selected";
                                }
                                ?>><?= $vkey["vendor_name"] ?></option>
                                    <?php endforeach; ?>
                        </select></td>
                    <td><a href="javascript:void(0);" onclick="delete_city_price_edit('<?= $cnt; ?>', '<?= $ikey["item_name"] ?>', '<?= $ikey["category_fk"] ?>', '<?= $ikey["Category_name"] ?>')"><i class="fa fa-trash"></i></a></td>
                </tr>
                <?php
                $cnt++;
            }
            ?>
            <script>$city_cnt_edit = <?= $cnt ?>;</script>
            <script>$selected_item = '<?= implode(",", $selected_item); ?>';</script>
        </tbody>
        </table>
        <div class="form-group">
            <textarea name="remark" placeholder="Remark" class="form-control"><?= $edit_item_details[0]["remark"] ?></textarea>
        </div>
        <?php
    }

    function intent_update() {

        $this->Intent_model->new_fun_update("inventory_intent_request", array("indent_fk" => $this->input->post("indent_fk")), array("status" => "0"));

        $data["login_data"] = logindata();
        $id = $data["login_data"]["id"];
        $type = $data["login_data"]["type"];
        $item_list = $this->input->post("item");
        $quantity = $this->input->post("quantity");

        $lot = $this->input->post("lot");
        $vendor = $this->input->post("vendor");
        $remark = $this->input->post("remark");

        $cnt = 0;
        $sub_id = $this->input->post("indent_fk");

        foreach ($item_list as $kkey) {
            $data = array(
                "indent_fk" => $sub_id,
                "category_fk" => $kkey,
                "quantity" => $quantity[$cnt],
                "status" => 1,
                "created_date" => date("Y-m-d H:i:s"),
                "created_by" => $id,
                "vender_id" => $vendor[$cnt],
                "lot_number" => $lot[$cnt]
            );

            $this->Intent_model->master_fun_insert("inventory_intent_request", $data);

            $cnt++;
        }
        $this->Intent_model->new_fun_update("inventory_intent_master", array("id" => $this->input->post("indent_fk")), array("remark" => $remark));
        $this->session->set_flashdata("success", array("Indent Request successfully updated."));
        if ($type == 8) {
            redirect("inventory/Intent_Request/intent_list/", "refresh");
        } else {
            redirect("inventory/Intent_Request/sub_index/", "refresh");
        }
    }

    function delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $tid = $this->uri->segment('4');

        $data['query'] = $this->Intent_model->new_fun_update("inventory_intent_request", array("indent_fk" => $tid), array("status" => "0"));

        $this->session->set_flashdata("success", array("Indent Request successfull deleted."));
        redirect("inventory/Intent_Request/sub_index", "refresh");
    }

    function getsub() {
        $id = $this->input->get_post('branch_fk');
        $selected = $this->input->get_post("selected");
        $id_array = explode(",", $selected);
        $query = $this->Intent_model->get_val("SELECT iim.*,im.name AS MachineName,im.id as MachineId,it.id as Reagent_Id,it.reagent_name as Reagent_name,it.unit_fk,inventory_unit_master.name as UnitName FROM inventory_machine_branch AS iim INNER JOIN inventory_machine AS im ON im.`id`=iim.machine_fk INNER JOIN inventory_item as it on it.machine = im.id LEFT JOIN inventory_unit_master on inventory_unit_master.id = it.unit_fk and inventory_unit_master.status='1' WHERE iim.branch_fk='" . $id . "' AND iim.`status`='1' AND im.status='1' and it.status='1' and it.category_fk='3'");
        $reagent_array = '';
        $reagent_array .= '<option value="">Select Reagent</option>';
        if (!empty($query)) {
            foreach ($query as $key => $val) {
                if (!in_array($val['Reagent_Id'], $id_array)) {
                    $new_unt = (!empty($val['UnitName'])) ? "(" . $val['UnitName'] . ")" : "";
                    $reagent_array .= '<option value="' . $val['Reagent_Id'] . '">' . $val['Reagent_name'] . $new_unt . '</option>';
                }
            }
            echo $reagent_array;
        }
        exit;
    }

    function get_proved() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $tid = $this->uri->segment('4');

        $data['query'] = $this->Intent_model->new_fun_update("inventory_intent_master", array("intent_id" => $tid), array("status" => "1", 'modified_by' => $id));
        $this->session->set_flashdata("success", array("Indent Request successfull Approved."));
        if ($type == 8) {
            redirect("inventory/Intent_Request/intent_list", "refresh");
        } else {
            redirect("inventory/Intent_Request/sub_index", "refresh");
        }
    }

    function get_print_form() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $tid = $this->uri->segment('4');
        $mid = $this->uri->segment('5');
        $data['query'] = $this->Intent_model->get_print($tid, $mid);

        $html = $this->load->view('inventory/indent_pdf', $data, true);

        $pdfFilePath = FCPATH . "/upload/ack/" . "_ack_wtlpd.pdf";

        $this->load->library('pdf');

        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;
        $pdf->autoLangToFont = true;
        $pdf->SetHTMLHeader('<body>
        <div class="pdf_container">
            <div class="main_set_pdng_div">
                <div class="brdr_full_div">
                    <div class="header_full_div">
                        <img class="set_logo" src="logo.png"/>
                    </div><div class="tst_rprt_title" style="text-align:center;"><h3>INDENT FORMAT</h3></div>');

        $pdf->SetHTMLFooter('<div class="foot_num_div" style="margin-bottom:0;padding-bottom:0">
    <p class="foot_num_p" style="margin-bottom:2;padding-bottom:0"><img class="set_sign" src="pdf_phn_btn.png" style="width:"/></p>
    <p class="foot_lab_p" style="font-size:13px;margin-bottom:2;padding-bottom:0">LAB AT YOUR DOORSTEP</p>
  </div>
    <p class="lst_airmed_mdl" style="font-size:13px;margin-top:5px">Airmed Pathology Pvt. Ltd.</p>
    <p class="lst_31_addrs_mdl" style="font-size:12px"><span style="color:#9D0902;">Commercial Address : </span>31, Ambika Society, Next to Nabard Bank, Opp. Usmanpura, Ahmedabad, Gujarat - 380 013.</p>
    <p class="lst_31_addrs_mdl"><b><img src="email-icon.png" style="margin-bottom:-3px;width:13px"/> info@airmedlabs.com  <img src="web-icon.png" style="margin-bottom:-3px;width:13px"/> www.airmedlabs.com</b></p><p class="lst_31_addrs_mdl"><!--<img src="lastimg.png" style="margin-top:3px;"/>--> </p></div>
        </body>
</html>');
        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 5, // margin_left
                5, // margin right
                32, // margin top
                32, // margin bottom
                2, // margin header
                2); // margin footer
        // Split $lorem into words
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        $name = "index.pdf";

        $downld = $this->_push_file($pdfFilePath, $name);
        $this->delete_downloadfile($pdfFilePath);
    }

    private function _push_file($path, $name) {
        // make sure it's a file before doing anything!
        if (is_file($path)) {
            // required for IE
            if (ini_get('zlib.output_compression')) {
                ini_set('zlib.output_compression', 'Off');
            }
            // get the file mime type using the file extension
            $this->load->helper('file');

            $mime = get_mime_by_extension($path);

            // Build the headers to push out the file properly.
            header('Pragma: public');     // required
            header('Expires: 0');         // no cache
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT');
            header('Cache-Control: private', false);
            header('Content-Type: ' . $mime);  // Add the mime type from Code igniter.
            header('Content-Disposition: attachment; filename="' . basename($name) . '"');  // Add the file name
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($path)); // provide file size
            header('Connection: close');
            readfile($path); // push it out
//    exit();
        }
    }

    function delete_downloadfile($path) {
        $this->load->helper('file');
        unlink($path);
    }

    public function poigenerate($indenid) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        if ($indenid != "") {
            $data['indedrequiest'] = $this->Intent_model->get_val("SELECT id,branch_fk FROM inventory_intent_master WHERE status='1' and postatus='2' AND id='$indenid'");
            $data["branch_fk"] = $data['indedrequiest'][0]["branch_fk"];
            if ($data['indedrequiest'] != null) {
                $poitenm = $this->Intent_model->get_val("SELECT p.id,p.quantity,p.category_fk as itemid,i.reagent_name,i.category_fk as cattype,inventory_intent_master.remark,i.quantity as qty,i.box_price,p.`lot_number`,p.`vender_id`,inventory_intent_master.`branch_fk` FROM inventory_intent_request p LEFT JOIN inventory_item i ON i.`id`=p.category_fk LEFT JOIN `inventory_intent_master` ON `inventory_intent_master`.`id`=p.`indent_fk` WHERE p.status='1' and p.indent_fk='$indenid'");
                $data["indent_remark"] = $poitenm[0]["remark"];
                $data['poitenm'] = array();
                foreach ($poitenm as $pkey) {
                    $indent_details = $this->Intent_model->get_val("SELECT 
  `inventory_poitem`.`itemid`,
  `inventory_pogenrate`.`branchfk`,
  `inventory_pogenrate`.`vendorid`,
  inventory_poitem.`txtid` 
FROM
  inventory_poitem 
  INNER JOIN `inventory_pogenrate` 
    ON `inventory_pogenrate`.`id` = inventory_poitem.`poid` 
WHERE inventory_poitem.`status` != '0' 
  AND `inventory_pogenrate`.`status` != '0' 
  AND `inventory_poitem`.`itemid`='" . $pkey["itemid"] . "'
  AND `inventory_pogenrate`.`branchfk`='" . $pkey["branch_fk"] . "'
ORDER BY `inventory_poitem`.id DESC LIMIT 0,1");
                    $available_stock = $this->Intent_model->get_val("SELECT 
  b.`branch_name`,
  s.`id`,
  s.item_fk,
  r.`reagent_name`,
  r.`category_fk`,
  SUM(s.quantity) AS totalqty, 
  SUM(s.`used`) AS used,
  SUM(s.quantity) - SUM(s.`used`) AS stcok,
  inw.`branch_fk` 
FROM
  inventory_stock_master s 
  INNER JOIN `inventory_item` r 
    ON r.`id` = s.`item_fk` 
  INNER JOIN inventory_inward_master inw 
    ON inw.`id` = s.`inward_fk` 
  INNER JOIN `branch_master` b 
    ON b.`id` = inw.`branch_fk` 
WHERE s.`status` = '1' 
  AND inw.branch_fk = '" . $pkey["branch_fk"] . "'
  AND s.item_fk='" . $pkey["itemid"] . "'
GROUP BY r.`id` 
ORDER BY s.id ASC 
LIMIT 0,1");
                    if ($pkey["vender_id"] > 0) {
                        
                    } else {
                        $pkey["vender_id"] = $indent_details[0]["vendorid"];
                    }
                    $pkey["taxid"] = $indent_details[0]["txtid"];
                    $pkey["available_stock"] = $available_stock[0]["stcok"];
                    $data['poitenm'][] = $pkey;
                }
                /* echo "<pre>"; print_r($data['poitenm']);die(); */
                /*$data['stationary_list'] = $this->Intent_model->get_val("select * from inventory_item where category_fk='1' and status='1'");*/
                /*$data['lab_consum'] = $this->Intent_model->get_val("select * from inventory_item where category_fk='2' and status='1'");*/
                $data['itemtext'] = $this->Intent_model->get_val("select id,tax,title from inventory_tax_master where  status='1'");
                $data['lab_consum'] = $this->Intent_model->get_val("SELECT it.* 
FROM inventory_item AS it  LEFT JOIN inventory_unit_master ON inventory_unit_master.id = it.unit_fk AND inventory_unit_master.status = '1' LEFT JOIN branch_master AS br ON br.id = it.branch_fk 
 AND br.status = '1' WHERE it.branch_fk = '" . $pkey["branch_fk"] . "' AND it.status = '1' AND it.`category_fk`='2'
   ");
                $data['stationary_list'] = $this->Intent_model->get_val("SELECT it.* 
FROM inventory_item AS it  LEFT JOIN inventory_unit_master ON inventory_unit_master.id = it.unit_fk AND inventory_unit_master.status = '1' LEFT JOIN branch_master AS br ON br.id = it.branch_fk 
 AND br.status = '1' WHERE it.branch_fk = '" . $pkey["branch_fk"] . "' AND it.status = '1' AND it.`category_fk`='1'
   ");
                /* $data['vendor_list'] = $this->Intent_model->get_val("select id,vendor_name from inventory_vendor where status='1'"); */
                $data['vendor_list'] = $this->Intent_model->get_val("SELECT DISTINCT `inventory_vendor`.id,`inventory_vendor`.`vendor_name` FROM `inventory_vendor` INNER JOIN `test_cities` ON `test_cities`.`city_fk`=`inventory_vendor`.`city_fk` INNER JOIN `branch_master` ON `branch_master`.`city`=`test_cities`.`id` WHERE `inventory_vendor`.`status`='1' AND `branch_master`.`status`='1' AND `test_cities`.`status`='1' AND `branch_master`.`id`='" . $pkey["branch_fk"] . "'");

                if ($type == 8) {
                    $this->load->view('inventory/header', $data);
                    $this->load->view('inventory/nav', $data);
                } else {
                    $this->load->view('header', $data);
                    $this->load->view('nav', $data);
                }
                $this->load->view('inventory/invert_pogentrateviews', $data);
                $this->load->view('footer');
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }

    public function poigenerateadd($indenid) {
//echo "<pre>"; print_r($_POST);die();
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        if ($indenid != "") {

            $data['indedrequiest'] = $this->Intent_model->get_val("SELECT id,intent_id,branch_fk FROM inventory_intent_master WHERE status='1' AND id='$indenid'");
            if ($data['indedrequiest'] != null) {

                $this->load->library('form_validation');
                $this->form_validation->set_rules('maintotal', 'maintotal', 'trim|required');
                $this->form_validation->set_rules('itemvendor[]', 'itemvendor', 'trim|required');

                if ($this->form_validation->run() != FALSE) {

                    $maintotal = $this->input->post('maintotal');
                    $item = $this->input->post('item');
                    $nosval = $this->input->post('nosval');
                    $rateqty = $this->input->post('rateqty');
                    $itemtext = $this->input->post('itemtext');
                    $itemdis = $this->input->post('itemdis');
                    $lot = $this->input->post("lot");
                    $totalamount = 0;
                    $branchid = $data['indedrequiest'][0]["branch_fk"];
                    $intent_id = $data['indedrequiest'][0]["intent_id"];
                    $itemprice1 = $this->input->post("itemprice");
                    $itemvendor = $this->input->post('itemvendor');
                    $venderarra = array();
                    $poidarraay = array();

                    for ($i = 0; $i < count($item); $i++) {


                        $itemid = $item[$i];
                        $nosval1 = $nosval[$i];
                        $itemprice = round($rateqty[$i], 2);
                        $itemtxt = $itemtext[$i];
                        if ($itemprice > 0 && $nosval1 > 0 && $itemvendor[$i] != "") {

                            $stationary_list = $this->Intent_model->get_val("select quantity from inventory_item where id='$itemid' and status='1'");
                            if ($itemtxt != "" && $itemtxt != '0') {
                                $getdisccount = $this->Intent_model->get_val("select id,tax from inventory_tax_master where  status='1' and id='$itemtxt'");
                                $txt = $getdisccount[0]["tax"];
                                $txtid = $itemtxt;
                            } else {
                                $txt = 0;
                                $txtid = 0;
                            }

                            if ($itemdis[$i] >= 0 && $itemdis[$i] < 99) {
                                $perdisc = $itemdis[$i];
                            } else {
                                $perdisc = 0;
                            }

                            /* $itenqty = $stationary_list[0]["quantity"]; */
                            $itenqty = 1;
                            $outamount = round($itenqty * $itemprice * $nosval1, 2);

                            $amount = round($outamount - ($outamount * $perdisc / 100), 2);
                            $paybleamount = round($amount + ($amount * $txt / 100), 2);
                            $totalamount += $paybleamount;
                            $vendorid = $itemvendor[$i];
                            $sametotal = 0;
                            if (in_array($vendorid, $venderarra)) {

                                $poid = $poidarraay[$vendorid];
                                $sametotal += $totalamount;

                                $getlastpoprice = $this->Intent_model->get_val("select id,poprice from inventory_pogenrate where id='$poid'");

                                $sametotal = ($itemprice1[$i] + $getlastpoprice[0]["poprice"]);

                                $this->Intent_model->master_fun_update('inventory_pogenrate', $poid, array("poprice" => round($sametotal)));
                            } else {
                                $invr_last_id = $this->Intent_model->get_val("SELECT MAX(id)+1 AS iid FROM inventory_pogenrate");
                                $po_number = "Airmed/" . date("Y") . "/" . $invr_last_id[0]['iid'];
                                $poid = $this->Intent_model->master_fun_insert('inventory_pogenrate', array("branchfk" => $branchid, "ponumber" => $po_number, "poprice" => $itemprice1[$i], "vendorid" => $itemvendor[$i], "indentcode" => $intent_id, "requestid" => $indenid, "cretedby" => $login_id, "creteddate" => date("Y-m-d H:i:s")));
                                $venderarra[] = $itemvendor[$i];
                                $poidarraay[$vendorid] = $poid;
                            }
                            $this->Intent_model->master_fun_insert('inventory_poitem', array("poid" => $poid, "itemid" => $itemid, "itemnos" => $nosval1, "itenqty" => $itenqty, "itemtxt" => $txt, "txtid" => $txtid, "itemprice" => $itemprice1[$i], "peritemprice" => $itemprice, "itemdis" => $perdisc, "creteddate" => date("Y-m-d H:i:s"), "cretedby" => $login_id, "lotno" => $lot[$i]));

                            
                            
                        }
                    }
                    
                    if ($totalamount != 0) {
                        $this->Intent_model->master_fun_update('inventory_intent_master', $indenid, array("postatus" => 1));
                    }
                    $this->session->set_flashdata("success", array("Successfully Po Generate In Draft."));
                    echo "1";
                } else {
                    echo "0";
                }
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }

    public function view() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $vid = $this->uri->segment('4');

        $data['query'] = $this->Intent_model->get_val("select iim.id,br.branch_name,GROUP_CONCAT(iir.category_fk) AS CategoryId,GROUP_CONCAT(it.reagent_name) AS Reagent,GROUP_CONCAT(iir.quantity) AS Quantity, GROUP_CONCAT(ic.name) AS Category,GROUP_CONCAT(ium.name) AS UnitName,GROUP_CONCAT(ium.id) AS UnitID from inventory_intent_master as iim LEFT JOIN inventory_intent_request AS iir on iim.id = iir.indent_fk and iir.status='1' LEFT JOIN branch_master as br on br.id = iim.branch_fk and br.status='1' LEFT JOIN `inventory_item` AS it ON it.`id` = iir.`category_fk` AND it.`status`='1' LEFT JOIN `inventory_category` AS ic ON ic.`id` = it.`category_fk` AND ic.`status`='1' 
      LEFT JOIN `inventory_unit_master` AS ium
      ON ium.`id` = it.`unit_fk` AND ium.`status`='1'  where iim.id='" . $vid . "' and iim.status !='0'");

        if ($type == '1' || $type == '2' || $type == '5') {
            $this->load->view('header', $data);
            $this->load->view('nav', $data);
        }
        if ($type == '8') {
            $this->load->view('inventory/header', $data);
            $this->load->view('inventory/nav', $data);
        }
        $this->load->view('inventory/viewindentlist', $data);
        $this->load->view('footer');
    }

    function labconsume() {
        $id = $this->input->get_post('branch_fk');
        $selected = $this->input->get_post('selected');
        $selected_ids = explode(",", $selected);
        $query = $this->Intent_model->get_val("SELECT it.id AS Reagent_Id,it.reagent_name AS Reagent_name,it.unit_fk,inventory_unit_master.name AS UnitName 
FROM inventory_item AS it  LEFT JOIN inventory_unit_master ON inventory_unit_master.id = it.unit_fk AND inventory_unit_master.status = '1' LEFT JOIN branch_master AS br ON br.id = it.branch_fk 
 AND br.status = '1' WHERE it.branch_fk = '" . $id . "' AND it.status = '1' AND it.`category_fk`='2'
   ");


        $reagent_array = '';
        $reagent_array .= '<option value="">Select Labconsumable</option>';
        if (!empty($query)) {
            foreach ($query as $key => $val) {
                if (!in_array($val['Reagent_Id'], $selected_ids)) {
                    $new_unt = (!empty($val['UnitName'])) ? "(" . $val['UnitName'] . ")" : "";
                    $reagent_array .= '<option value="' . $val['Reagent_Id'] . '">' . $val['Reagent_name'] . $new_unt . '</option>';
                }
            }
            echo $reagent_array;
        }
        exit;
    }

    function stationary() {
        $id = $this->input->get_post('branch_fk');
        $selected = $this->input->get_post('selected');
        $selected_ids = explode(",", $selected);
        $query = $this->Intent_model->get_val("SELECT it.id AS Reagent_Id,it.reagent_name AS Reagent_name,it.unit_fk,inventory_unit_master.name AS UnitName 
FROM inventory_item AS it  LEFT JOIN inventory_unit_master ON inventory_unit_master.id = it.unit_fk AND inventory_unit_master.status = '1' LEFT JOIN branch_master AS br ON br.id = it.branch_fk 
 AND br.status = '1' WHERE it.branch_fk = '" . $id . "' AND it.status = '1' AND it.`category_fk`='1'
   ");

        $reagent_array = '';
        $reagent_array .= '<option value="">Select Stationary</option>';
        if (!empty($query)) {
            foreach ($query as $key => $val) {
                if (!in_array($val['Reagent_Id'], $selected_ids)) {
                    $new_unt = (!empty($val['UnitName'])) ? "(" . $val['UnitName'] . ")" : "";
                    $reagent_array .= '<option value="' . $val['Reagent_Id'] . '">' . $val['Reagent_name'] . $new_unt . '</option>';
                }
            }
            echo $reagent_array;
        }
        exit;
    }

    function vender_list() {
        $id = $this->input->get_post('branch_fk');
        $query = $this->Intent_model->get_val("SELECT DISTINCT `inventory_vendor`.id,`inventory_vendor`.`vendor_name` FROM `inventory_vendor` INNER JOIN `test_cities` ON `test_cities`.`city_fk`=`inventory_vendor`.`city_fk` INNER JOIN `branch_master` ON `branch_master`.`city`=`test_cities`.`id` WHERE `inventory_vendor`.`status`='1' AND `branch_master`.`status`='1' AND `test_cities`.`status`='1' AND `branch_master`.`id`='" . $id . "'");
        $vender_array = '';
        $vender_array .= '<option value="">Select Vendor</option>';
        if (!empty($query)) {
            foreach ($query as $val) {
                if (!in_array($val['id'], $sub_array)) {
                    $vender_array .= '<option value="' . $val['id'] . '">' . $val['vendor_name'] . '</option>';
                }
                $sub_array[] = $val['id'];
            }
            echo $vender_array;
        }
        exit;
    }

    public function indent_details($indenid) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        if ($indenid != "") {
            $data['indedrequiest'] = $this->Intent_model->get_val("SELECT inventory_intent_master.id,inventory_intent_master.`created_date`,inventory_intent_master.intent_id,inventory_intent_master.branch_fk,inventory_intent_master.remark,`branch_master`.`branch_name`,admin_master.name as added_by FROM inventory_intent_master LEFT JOIN `branch_master` ON `branch_master`.`id`=inventory_intent_master.`branch_fk` LEFT JOIN `admin_master` ON `admin_master`.id=inventory_intent_master.`created_by` WHERE inventory_intent_master.status='1' AND inventory_intent_master.postatus='2' AND inventory_intent_master.id='$indenid'");
            if ($data['indedrequiest'] != null) {
                $poitenm = $this->Intent_model->get_val("SELECT 
  p.id,
  p.quantity,
  p.category_fk AS itemid,
  i.reagent_name,
  i.category_fk AS cattype,
  i.box_price,
  p.`lot_number`,
  p.`vender_id`,
  inventory_category.`name` AS category,
  inventory_unit_master.`name` AS unit 
FROM
  inventory_intent_request p 
  LEFT JOIN inventory_item i 
    ON i.`id` = p.category_fk 
  LEFT JOIN `inventory_intent_master` 
    ON `inventory_intent_master`.`id` = p.`indent_fk` 
    LEFT JOIN `inventory_category` ON `inventory_category`.`id`=i.category_fk
    LEFT JOIN `inventory_unit_master` ON i.`unit_fk`=`inventory_unit_master`.id WHERE p.status='1' and p.indent_fk='$indenid'");
                $data["indent_remark"] = $poitenm[0]["remark"];
                $data['poitenm'] = $poitenm;
                if ($type == 8) {
                    $this->load->view('inventory/header', $data);
                    $this->load->view('inventory/nav', $data);
                } else {
                    $this->load->view('header', $data);
                    $this->load->view('nav', $data);
                }
                $this->load->view('inventory/invert_indent_details', $data);
                $this->load->view('footer');
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }

}
