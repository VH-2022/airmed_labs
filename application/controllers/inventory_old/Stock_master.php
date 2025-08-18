<?php

class Stock_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('Master_model');
        $this->load->model('inventory/Test_item_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $data["login_data"] = logindata();
    }

    function stock() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $branch_name = $this->input->get_post('branch_name');
        $reg_id = $this->input->get('search');
        $category_name = $this->input->get('category_name');
        $machine_name = $this->input->get('machine_name');
        $stock = $this->input->get('stock');

        $start_date = $this->input->get('start_date');
        $expiry = $this->input->get('expiry');
        if ($branch_name != '' || $reg_id != '' || $machine_name != '' || $category_name != '' || $stock != '' || $start_date != '' || $expiry != '') {
            $srchdata = array("branch_name" => $branch_name, "item_name" => $reg_id, "machine_name" => $machine_name, "category_fk" => $category_name, "stock" => $stock, 'start_date' => $start_date, 'expiry' => $expiry);
            $data['branch_name'] = $branch_name;
            $data['item_name'] = $reg_id;
            $data['machine_name'] = $machine_name;
            $data['category_name'] = $category_name;
            $data['stock'] = $stock;
            $data['start_date'] = $start_date;
            $data['expiry'] = $expiry;

            $totalRows = $this->Test_item_model->getnum($srchdata);

            $config = array();
            /* Vishal Code Start */
            $config["base_url"] = base_url() . "inventory/Stock_master/stock?branch_name=$branch_name&search=$reg_id&machine_name=$machine_name&category_name=$category_name&stock=$stock&start_date=$start_date&expiry=$expiry";
            $config["total_rows"] = count($totalRows);
            /* Vishal Code End */
            $config["per_page"] = 100;
            $config["uri_segment"] = 4;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $config['page_query_string'] = TRUE;

            $this->pagination->initialize($config);

            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data["query"] = $this->Test_item_model->view_list($srchdata, $page, $config["per_page"]);

            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
            $final_array = array();
            foreach ($data["query"] as $key) {
                $details = $this->Test_item_model->get_data("SELECT 
 
  `inventory_stock_master`.* ,
  `inventory_item`.`reagent_name` AS item_name,
  `inventory_unit_master`.`name` AS unit_name,
  `inventory_category`.`name` AS category_name,
  `inventory_machine`.`name` AS Machinename,
  `branch_master`.`branch_name` AS Branchname
FROM
  `inventory_stock_master` 
  INNER JOIN `inventory_item` ON `inventory_item`.id=`inventory_stock_master`.`item_fk` INNER JOIN  `inventory_category` ON `inventory_category`.id=`inventory_item`.`category_fk` LEFT JOIN `inventory_unit_master` ON `inventory_unit_master`.id=`inventory_item`.`unit_fk` and `inventory_unit_master`.status='1' LEFT JOIN `inventory_machine` ON `inventory_machine`.id=`inventory_stock_master`.`machine_fk` and `inventory_machine`.status='1' LEFT JOIN branch_master on inventory_stock_master.branch_fk = branch_master.id and branch_master.status='1'
WHERE `inventory_stock_master`.`status` = '1' and `inventory_item`.status='1'   
  AND `inventory_stock_master`.item_fk = '" . $key["item_fk"] . "'  and `inventory_stock_master`.branch_fk = '" . $key["branch_fk"] . "'
ORDER BY `inventory_stock_master`.id DESC 
LIMIT 0,1 ");

//echo "<pre>";print_r($details);

                if (!empty($details)) {
                    $final_array[] = $details;
                }
            }
            $data["item"] = $this->Test_item_model->get_data("SELECT * FROM `inventory_item` WHERE `status`='1' order by reagent_name asc");
            $data["final_array"] = $final_array;
        } else {
            $srchdata = array();
            $totalRows = $this->Test_item_model->get_data("SELECT DISTINCT item_fk FROM `inventory_stock_master` WHERE `status`='1' ORDER by id desc");

            $config = array();
            $config["base_url"] = base_url() . "inventory/Stock_master/stock/";
            $config["total_rows"] = count($totalRows);
            $config["per_page"] = 100;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            $data["query"] = $this->Test_item_model->get_data("SELECT DISTINCT item_fk FROM `inventory_stock_master` WHERE `status`='1' order by id desc  limit " . $page . "," . $config["per_page"]);

            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
            $final_array = array();
            foreach ($data["query"] as $key) {
                $details = $this->Test_item_model->get_data("SELECT 
  `inventory_stock_master`.id,
  inventory_stock_master.*,
  `inventory_stock_master`.item_fk,
  `inventory_stock_master`.total ,  
   `inventory_item`.`reagent_name` AS item_name,
  `inventory_unit_master`.`name` AS unit_name,
  `inventory_category`.`name` AS category_name,
  `inventory_machine`.`name` AS Machinename,
  `branch_master`.`branch_name` AS Branchname
FROM
  `inventory_stock_master` 
  INNER JOIN `inventory_item` ON `inventory_item`.id=`inventory_stock_master`.`item_fk`
INNER JOIN  `inventory_category` ON `inventory_category`.id=`inventory_item`.`category_fk` and `inventory_category`.status='1' LEFT JOIN `inventory_unit_master` ON `inventory_unit_master`.id=`inventory_item`.`unit_fk` and `inventory_unit_master`.status='1' LEFT JOIN  `inventory_machine` ON `inventory_machine`.id=`inventory_stock_master`.`machine_fk` and `inventory_machine`.status='1' LEFT JOIN branch_master on inventory_stock_master.branch_fk = branch_master.id and branch_master.status='1'
WHERE `inventory_stock_master`.`status` = '1' and `inventory_item`.status='1' 
  AND `inventory_stock_master`.item_fk = '" . $key["item_fk"] . "' 
ORDER BY `inventory_stock_master`.id DESC 
LIMIT 0, 1 ");

                if (!empty($details)) {
                    $final_array[] = $details;
                }
            }
        }


        $data["item"] = $this->Test_item_model->get_data("SELECT * FROM `inventory_item` WHERE `status`='1' order by reagent_name asc");
        $data["machine"] = $this->Test_item_model->get_data("SELECT * FROM `inventory_machine` WHERE `status`='1' order by name asc");
        $data['branch_list'] = $this->Test_item_model->get_data('select id,branch_name from branch_master where status="1"');


        $data["final_array"] = $final_array;
        $this->load->view('inventory/header');
        $this->load->view('inventory/nav', $data);
        $this->load->view('inventory/stock_list', $data);
        $this->load->view('inventory/footer');
    }

    public function stock_history() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $data["id"] = $this->uri->segment(4);
        if (!empty($data["id"])) {
            $data["test_details"] = $this->Test_item_model->get_data("select id,test_name from test_master where status='1' and id='" . $data["id"] . "'");
            $data["machine_details"] = $this->Test_item_model->get_data("select id,name from inventory_machine where status='1'");
            $data["item_details"] = $this->Test_item_model->get_data("select id,name from inventory_item where status='1'");

            $get_machine = $this->Test_item_model->get_data("SELECT DISTINCT `inventory_test_machine`.machine_fk,`inventory_machine`.`name` FROM `inventory_test_machine` INNER JOIN `inventory_machine` ON `inventory_machine`.`id`=`inventory_test_machine`.`machine_fk` WHERE `inventory_test_machine`.`status`='1' AND `inventory_test_machine`.test_fk='" . $data["id"] . "' ");

            $data["final_array"] = array();
            foreach ($get_machine as $kkey) {
                $get_machine_details = $this->Test_item_model->get_data("SELECT 
  inventory_test_machine.*,inventory_machine.`name` AS machine_name,inventory_item.`name` AS item_name 
FROM
  `inventory_test_machine` 
  INNER JOIN `inventory_machine` ON `inventory_machine`.id=`inventory_test_machine`.`machine_fk` 
  INNER JOIN `inventory_item` ON `inventory_item`.`id`=`inventory_test_machine`.`item_fk`
WHERE inventory_test_machine.`status` = '1' 
  AND inventory_test_machine.test_fk = '" . $data["id"] . "' 
  AND inventory_test_machine.machine_fk = '" . $kkey["machine_fk"] . "'");
                $kkey["details"] = $get_machine_details;
                $data["final_array"][] = $kkey;
            }
            //echo "<pre>";print_r($data["final_array"]); die();
            $this->load->view('inventory/header');
            $this->load->view('inventory/nav', $data);
            $this->load->view('inventory/test_manage', $data);
            $this->load->view('inventory/footer');
        } else {
            redirect("inventory/Test_item/test_list/");
        }
    }

    function add_stock() {
        $item = $this->input->post("item");
        $type = $this->input->post("type");
        $quantity = $this->input->post("quantity");

        $get_old_stock = $this->Test_item_model->get_data("SELECT * FROM `inventory_stock_master` WHERE `status`='1' and item_fk='" . $item . "' order by id desc limit 0,1");
        $credit_stock = 0;
        $debit_stock = 0;
        $total_stock = 0;
        if ($type == 1) {
            $credit_stock = $quantity;
            $debit_stock = 0;
            $total_stock = $get_old_stock[0]["total"] + $quantity;
        }
        if ($type == 2) {
            $credit_stock = 0;
            $debit_stock = $quantity;
            $total_stock = $get_old_stock[0]["total"] - $quantity;
        }
        $data = array(
            "item_fk" => $item,
            "credit" => $credit_stock,
            "debit" => $debit_stock,
            "total" => $total_stock,
            "status" => 1,
            "created_date" => date("Y-m-d H:i:s")
        );
        if ((!empty($credit_stock) || !empty($debit_stock)) && $item > 0) {
            $this->Test_item_model->master_fun_insert("inventory_stock_master", $data);
        }
        $this->session->set_flashdata("success", array("Stock successfully upgraded."));
        redirect("inventory/Stock_master/stock/");
    }

    function index() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        $branch_name = $this->input->get_post('branch_fk');

        $expiry = $this->input->get('expire');
        $end_date = $this->input->get_post('end_date');
        if ($branch_name != '' || $expiry != '' || $end_date != '') {
            $srchdata = array("branch_name" => $branch_name, 'expiry' => $expiry, 'end_date' => $end_date);
            $data['branch_name'] = $branch_name;
            $data['item_name'] = $reg_id;

            $data['expiry'] = $expiry;
            $data['end_date'] = $end_date;
            $totalRows = $this->Test_item_model->getnum_stock($srchdata);

            $config = array();
            /* Vishal Code Start */
            $config["base_url"] = base_url() . "inventory/Stock_master/index?branch_name=$branch_name&expiry=$expiry&end_date=$end_date";

            $config["total_rows"] = count($totalRows);
            /* Vishal Code End */
            $config["per_page"] = 100;
            $config["uri_segment"] = 4;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $config['page_query_string'] = TRUE;

            $this->pagination->initialize($config);

            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data["query"] = $this->Test_item_model->stock_list($srchdata, $page, $config["per_page"]);

            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {
            $srchdata = array();
            $totalRows = $this->Test_item_model->getnum_stock($srchdata);

            $config = array();
            $config["base_url"] = base_url() . "inventory/Stock_master/index";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 100;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            $data["query"] = $this->Test_item_model->stock_list($srchdata, $page, $config["per_page"]);

            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        if ($type == '1' || $type == '2' || $type == '8') {
            $data['branch_list'] = $this->Test_item_model->get_data('select id,branch_name from branch_master where status="1"');
        } else {
            $data['branch_list'] = $this->Test_item_model->get_data('select br.id,br.branch_name from admin_master LEFT JOIN user_branch on user_branch.user_fk= admin_master.id and admin_master.status="1" LEFT JOIN branch_master as br on br.id = user_branch.branch_fk and user_branch.status="1"  where user_branch.user_fk="' . $login_id . '" and br.status="1" order by br.branch_name asc');
        }
        if ($type == 8) {
            $this->load->view('inventory/header', $data);
            $this->load->view('inventory/nav', $data);
        } else {
            $this->load->view('header', $data);
            $this->load->view('nav', $data);
        }
        $this->load->view('inventory/stocklist', $data);
        $this->load->view('footer');
    }

    function stock_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $type = $data["login_data"]['type'];
        $this->form_validation->set_rules('branch_fk', "Branch Name", "trim|required");
        $this->form_validation->set_rules('item_fk', "Item Name", "trim|required");
        $this->form_validation->set_rules('batch_no', "Batch No", "trim|required");
        $this->form_validation->set_rules('quantity', "", "trim|callback_minus");

        if ($this->form_validation->run() != FALSE) {
            $data1 = array(
                'branch_fk' => $this->input->post('branch_fk'),
                "created_date" => date("Y-m-d H:i:s"),
                "created_by" => $data["login_data"]["id"]
            );
            $id = $this->Test_item_model->master_fun_insert('inventory_inward_master', $data1);

            $data_sub = array(
                'inward_fk' => $id,
                'item_fk' => $this->input->post('item_fk'),
                'batch_no' => $this->input->post('batch_no'),
                'expire_date' => date("Y-m-d", strtotime($this->input->post('expire_date'))),
                'quantity' => $this->input->post('quantity')
            );
            $this->Test_item_model->master_fun_insert('inventory_stock_master', $data_sub);
            $this->session->set_flashdata("success", array("Stock Successfully Inserted"));
            redirect("inventory/stock_master", "refresh");
        }
        if ($type == '1' || $type == '2' || $type == '8') {
            $data['branch_list'] = $this->Test_item_model->get_val('select id,branch_name from branch_master where status="1"');
        } else {
            $data['branch_list'] = $this->Test_item_model->get_val('select br.id,br.branch_name from admin_master LEFT JOIN user_branch on user_branch.user_fk= admin_master.id and admin_master.status="1" LEFT JOIN branch_master as br on br.id = user_branch.branch_fk and user_branch.status="1"  where user_branch.user_fk="' . $login_id . '" and br.status="1" order by br.branch_name asc');
        }

        if ($type == '1' || $type == '2') {
            $this->load->view('header');
            $this->load->view('nav', $data);
        }
        if ($type == '8') {
            $this->load->view('inventory/header');
            $this->load->view('inventory/nav', $data);
        }
        $this->load->view('inventory/stockadd', $data);
        $this->load->view('footer');
    }

    function getreagent() {
        $id = $this->input->post('id');
        $query = $this->Test_item_model->get_val("SELECT `inventory_item`.*,`inventory_category`.`name` AS category FROM `inventory_item` INNER JOIN `inventory_category` ON `inventory_item`.`category_fk`=`inventory_category`.`id` WHERE `inventory_category`.`status`='1' AND `inventory_item`.`status`='1' AND `inventory_item`.`category_fk` IN (1,2) ORDER BY `inventory_item`.`reagent_name` ASC");
        $lab_stats = '';
        $lab_stats .= '<option value="">Select Reagent</option>';
        if (!empty($query)) {
            $dup = array();
            foreach ($query as $key => $val) {

                if (!in_array($val['id'], $dup)) {
                    $lab_stats .= '<option value="' . $val['id'] . '">' . $val['reagent_name'] . ' ( ' . $val["category"] . ' )</option>';
                }
                $dup[] = $val['id'];
            }


            $query1 = $this->Test_item_model->get_val("    SELECT `inventory_item`.* FROM `inventory_item` INNER JOIN `inventory_machine_branch` ON `inventory_machine_branch`.`machine_fk`=`inventory_item`.`machine` INNER JOIN `inventory_machine` ON `inventory_machine`.`id`=inventory_machine_branch.`machine_fk` WHERE `inventory_item`.`status`='1' AND `inventory_item`.`category_fk`='3' AND `inventory_machine`.`status`='1' AND `inventory_machine_branch`.`status`='1' AND `inventory_machine_branch`.`branch_fk`='".$id."'");
            foreach ($query1 as $key => $val) {

                if (!in_array($val['id'], $dup)) {
                    $lab_stats .= '<option value="' . $val['id'] . '">' . $val['reagent_name'] . ' ( Reagent )</option>';
                }
                $dup[] = $val['id'];
            }

            echo $lab_stats;
        }
        exit;
    }

    function delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $cid = $this->uri->segment(4);
        $data = array(
            'status' => 0
        );
        $query = $this->Test_item_model->master_fun_update('inventory_stock_master', $cid, $data);
        $this->session->set_flashdata("success", array("Stock Successfully Delete"));
        redirect('inventory/stock_master/', "refresh");
    }

    function minus() {
        $quantity = $this->input->post('quantity');
        if ($quantity < 0) {
            $this->form_validation->set_message("minus", "Negative number not allowed");
            return false;
        } else {
            return true;
        }
    }

}

?>