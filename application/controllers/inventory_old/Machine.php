<?php

class Machine extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('inventory/Test_item_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $data["login_data"] = logindata();
    }

    function machine_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $reg_id = $this->input->get('search');
        $branch_id = $this->input->get('branch_name');
        $reagent_name = $this->input->get('reagent_name');
        $unit = $this->input->get('unit_name');
        if ($reg_id != '' || $branch_id != '' || $reagent_name != '' || $unit != '') {
            $srchdata = array("item_name" => $reg_id, "branch_name" => $branch_id, "reagent_name" => $reagent_name, "unit_name" => $unit);
            $data['item_name'] = $reg_id;
            $data['branch_id'] = $branch_id;
            $data['reagent_name'] = $reagent_name;
            $data['unit_id'] = $unit;
            $totalRows = $this->Test_item_model->machin_num($srchdata);

            $config = array();
            /* Vishal Code Start */
            $config["base_url"] = base_url() . "inventory/Item_master/item_list?search=$reg_id&branch_name=$branch_id&reagent_name=$reagent_name";
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
            $data["query"] = $this->Test_item_model->machine_list($srchdata, $page, $config["per_page"]);

            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {
            $srchdata = array();
            $totalRows = $this->Test_item_model->machin_num($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "inventory/Item_master/item_list/";
            $config["total_rows"] = count($totalRows);
            $config["per_page"] = 100;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            $data["query"] = $this->Test_item_model->machine_list($srchdata, $page, $config["per_page"]);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }

        $data['branchlist'] = $this->Test_item_model->get_data("select id,branch_name from branch_master where status='1'");
        $data['unitlist'] = $this->Test_item_model->get_data("select id,name from inventory_unit_master where status='1'");

        $this->load->view('inventory/header');
        $this->load->view('inventory/nav', $data);
        $this->load->view('inventory/machine_list', $data);
        $this->load->view('inventory/footer');
    }

     public function machine_add() {
//      
        if (!is_loggedin()) {
            redirect('login');
        }
      
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('branch[]', 'Branch Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('reagent_name[]', 'Reagent', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        if ($this->form_validation->run() != FALSE) {
            $data = array(
                'name' => $this->input->post('name'),
                "created_date" => date("Y-m-d H:i:s")
            );
            $insert = $this->Test_item_model->master_fun_insert("inventory_machine", $data);
            $branch = $this->input->post('branch');
            if (!empty($insert)) {
                foreach ($branch as $key) {
                    $this->Test_item_model->master_fun_insert("inventory_machine_branch", array('machine_fk' => $insert, "branch_fk" => $key, "created_date" => date("Y-m-d H:i:s")));
                }
            }
            //vishal Code Start
            $reg = $this->input->post('reagent_name');
            $count = 0;
            if (!empty($insert)) {
                foreach ($reg as $key => $value) {

                    $this->Test_item_model->master_fun_insert("inventory_item", array('machine ' => $insert, "reagent_name" => $value,
                        "quantity" => '1', "unit_fk" => $this->input->post('unit_fk')[$count], "test_quantity" => $this->input->post('test_quantity')[$count],"per_pack" => $this->input->post('per_pack')[$count],"brand_fk" => $this->input->post('brand_fk')[$count],"remark" => $this->input->post('remark')[$count],"box_price" => $this->input->post('box_price')[$count],"hsn_code" => $this->input->post('hsn_code')[$count],"category_fk"=>'3', "created_date" => date("Y-m-d H:i:s")));
           
                    $count++;
                }
            }

            //VIshal Code End
            $this->session->set_flashdata("success", array("Machine Successfull Added."));
            redirect("inventory/Machine/machine_list", "refresh");
        } else {
            $data["branchlist"] = $this->Test_item_model->get_data("select * from branch_master where status='1' order by branch_name asc");
            $data["unit_list"] = $this->Test_item_model->get_data("select * from inventory_unit_master where status='1' order by name asc");
              $data["brand_list"] = $this->Test_item_model->get_data("select * from inventory_brand where status='1' order by brand_name asc");
            $this->load->view('inventory/header');
            $this->load->view('inventory/nav', $data);
            $this->load->view('inventory/machine_add', $data);
            $this->load->view('inventory/footer');
        }
    }

    public function machine_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["eid"] = $this->uri->segment('4');
        $ids = $data["eid"];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('branch[]', 'Branch Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('reagent_name[]', 'Reagent', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        if ($this->form_validation->run() != FALSE) {
            //echo "<pre>"; print_r($_POST); die();
            $data = array(
                'name' => $this->input->post('name')
            );
            $insert = $this->Test_item_model->master_fun_update1("inventory_machine", array("id" => $this->input->post('id')), $data);

            $branch = $this->input->post('branch');
            if (!empty($insert)) {
                $this->Test_item_model->master_fun_update1("inventory_machine_branch", array("machine_fk" => $this->input->post('id')), array('status' => "0"));
                foreach ($branch as $key) {
                    $this->Test_item_model->master_fun_insert("inventory_machine_branch", array('machine_fk' => $this->input->post('id'), "branch_fk" => $key, "created_date" => date("Y-m-d H:i:s")));
                }
            }

            //Vishal Code Start
            $reg = $this->input->post('reagent_name');
            $old_item = $this->input->post('old_item');
            $count = 0;
            if (!empty($insert)) {

                $this->Test_item_model->master_fun_update1("inventory_item", array("machine" => $this->input->post('id')), array('status' => "0"));
                foreach ($reg as $key => $value) {
                    if ($old_item[$count] > 0) {
        $this->Test_item_model->master_fun_update1("inventory_item", array("id" => $old_item[$count]), array('status' => "1","reagent_name" => $value,"unit_fk" => $this->input->post('unit_fk')[$count], "test_quantity" => $this->input->post('test_quantity')[$count],"per_pack" => $this->input->post('per_pack')[$count],"brand_fk" => $this->input->post('brand_fk')[$count],"remark" => $this->input->post('remark')[$count],"box_price" => $this->input->post('box_price')[$count],"hsn_code" => $this->input->post('hsn_code')[$count]));
                    } else {
                        $this->Test_item_model->master_fun_insert("inventory_item", array('machine ' => $ids, "reagent_name" => $value,
                            "unit_fk" => $this->input->post('unit_fk')[$count], "test_quantity" => $this->input->post('test_quantity')[$count], "created_date" => date("Y-m-d H:i:s"),"per_pack" => $this->input->post('per_pack')[$count],"brand_fk" => $this->input->post('brand_fk')[$count],"remark" => $this->input->post('remark')[$count],"box_price" => $this->input->post('box_price')[$count],"hsn_code" => $this->input->post('hsn_code')[$count]));
                    }
                    $count++;
                }
            }
            //VIshal Code End
            $this->session->set_flashdata("success", array("Machine Successfull Updated."));
            redirect("inventory/Machine/machine_list", "refresh");
        } else {

            $data["branchlist"] = $this->Test_item_model->get_data("select * from branch_master where status='1' order by branch_name asc");

            $data['query'] = $this->Test_item_model->master_fun_get_tbl_val("inventory_machine", array("id" => $data["eid"]), array("id", "desc"));

            //Vishal COde Start
            $data['item'] = $this->Test_item_model->get_data("SELECT inventory_item.id AS Inventory,inventory_item.reagent_name AS InventoryName,inventory_item.quantity,inventory_item.unit_fk,inventory_item.test_quantity,inventory_item.per_pack,inventory_item.brand_fk,inventory_item.remark,inventory_item.hsn_code,inventory_item.box_price FROM `inventory_item`  WHERE `inventory_item`.`status`='1'  AND `inventory_item`.`machine`='" . $data["eid"] . "' ");

            $data['unit_list'] = $this->Test_item_model->get_data("select id,name from inventory_unit_master where status='1'");
   $data["brand_list"] = $this->Test_item_model->get_data("select * from inventory_brand where status='1' order by brand_name asc");
            //Vishal Code End
            $data["assign_branch"] = $this->Test_item_model->get_data("SELECT GROUP_CONCAT(branch_master.id) AS branch FROM `inventory_machine_branch` LEFT JOIN branch_master ON branch_master.`id`=`inventory_machine_branch`.`branch_fk` WHERE `inventory_machine_branch`.`status`='1' AND branch_master.`status`='1' AND `inventory_machine_branch`.`machine_fk`='" . $data["eid"] . "'  GROUP BY `inventory_machine_branch`.`machine_fk`");
            $this->load->view('inventory/header');
            $this->load->view('inventory/nav', $data);
            $this->load->view('inventory/machine_edit', $data);
            $this->load->view('inventory/footer');
        }
    }
    public function machine_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $eid = $this->uri->segment('4');
        $data['query'] = $this->Test_item_model->master_fun_update1("inventory_machine", array("id" => $eid), array("status" => "0"));
        $this->session->set_flashdata("success", array("Machine Successfull Deleted."));
        redirect("inventory/Machine/machine_list", "refresh");
    }

    function add_test_old() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $eid = $this->uri->segment('4');

        if (!empty($eid)) {
            $data["machine_details"] = $this->Test_item_model->get_data("select id,name from inventory_machine where status='1' and id='" . $eid . "'");

            $data["test_list"] = $this->Test_item_model->get_data("select id,test_name from test_master where status='1'");
            $data["reagent_list"] = $this->Test_item_model->get_data("select id,reagent_name from inventory_item where status='1' and machine='" . $eid . "'");
            //print_r($data["test_list"]);die("ok");


            $get_machine = $this->Test_item_model->get_data("SELECT DISTINCT `inventory_test_machine`.machine_fk,`inventory_machine`.`name` FROM `inventory_test_machine` INNER JOIN `inventory_machine` ON `inventory_machine`.`id`=`inventory_test_machine`.`machine_fk` WHERE `inventory_test_machine`.`status`='1' AND `inventory_test_machine`.machine_fk='" . $eid . "' ");

            $data["final_array"] = array();
            foreach ($get_machine as $kkey) {
                $get_machine_details = $this->Test_item_model->get_data("SELECT 
  i.id,
  t.`test_name`,m.`name` ,GROUP_CONCAT(DISTINCT it.`reagent_name`) as reagent_name,GROUP_CONCAT(DISTINCT i.`quantity`) as quantity,i.*
FROM
  `inventory_test_machine` i
  LEFT JOIN test_master t 
    ON t.id = i.test_fk 
    AND t.`status` = '1' 
  INNER JOIN `inventory_machine` m
    ON m.id = i.`machine_fk` 
   INNER JOIN inventory_item it
    ON it.`machine` = m.`id` AND it.`status`='1'
WHERE i.`status` = '1' 
  AND i.machine_fk = '" . $kkey['machine_fk'] . "' GROUP BY i.test_fk");

                $kkey["details"] = $get_machine_details;

                $data["final_array"][] = $kkey;
            }

//echo "<pre>";print_r($data['final_array']);die;
            $data["sub_new_list"] = $this->Test_item_model->get_data("select id,test_name from test_master where status='1'");
//         $data['query'] = $this->Test_item_model->get_data("SELECT T.id, T.NAME,GROUP_CONCAT(DISTINCT B.branch_name) AS BranchName,GROUP_CONCAT(DISTINCT C.reagent_name) AS ReagentName FROM inventory_machine T LEFT JOIN inventory_machine_branch ON inventory_machine_branch.`machine_fk` = T.id  AND  inventory_machine_branch.status='1'
// LEFT JOIN branch_master AS B ON B.id = inventory_machine_branch.branch_fk AND inventory_machine_branch.status=1 LEFT JOIN inventory_item AS C ON C.`machine` = T.`id`  AND C.status=1 WHERE T.status='1' AND T.id='".$eid."'  GROUP BY T.id");
//         $data['test_list'] = $this->Test_item_model->get_data("SELECT * from test_master where status='1'");
//         $data['reagent_list'] = $this->Test_item_model->get_data("SELECT id,reagent_name from inventory_item  where status='1' and machine='".$eid."'");


            $this->load->view('inventory/header');
            $this->load->view('inventory/nav', $data);
            $this->load->view('inventory/add_test', $data);
            $this->load->view('inventory/footer');
        }
    }

    function add_test() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $eid = $this->uri->segment('4');

        if (!empty($eid)) {
            $data["machine_details"] = $this->Test_item_model->get_data("select id,name from inventory_machine where status='1' and id='" . $eid . "'");

            $data["test_list"] = $this->Test_item_model->get_data("select id,test_name from test_master where status='1'");
            $data["reagent_list"] = $this->Test_item_model->get_data("select id,reagent_name from inventory_item where status='1' and machine='" . $eid . "'");
            //print_r($data["test_list"]);die("ok");


            $get_machine = $this->Test_item_model->get_data("SELECT DISTINCT `inventory_test_machine`.machine_fk,`inventory_machine`.`name` FROM `inventory_test_machine` INNER JOIN `inventory_machine` ON `inventory_machine`.`id`=`inventory_test_machine`.`machine_fk` WHERE `inventory_test_machine`.`status`='1' AND `inventory_test_machine`.machine_fk='" . $eid . "' ");

            $data["final_array"] = array();
            foreach ($get_machine as $kkey) {
                $get_machine_details = $this->Test_item_model->get_data("SELECT 
  i.id,
  t.`test_name`,m.`name` ,GROUP_CONCAT(DISTINCT it.`reagent_name`) as reagent_name,GROUP_CONCAT(DISTINCT i.`quantity`) as quantity,i.*
FROM
  `inventory_test_machine` i
  LEFT JOIN test_master t 
    ON t.id = i.test_fk 
    AND t.`status` = '1' 
  INNER JOIN `inventory_machine` m
    ON m.id = i.`machine_fk` 
   INNER JOIN inventory_item it
    ON it.`machine` = m.`id` AND it.`status`='1'
WHERE i.`status` = '1' 
  AND i.machine_fk = '" . $kkey['machine_fk'] . "' GROUP BY i.test_fk");
                $new_mr_data = array();
                foreach ($get_machine_details as $mrkey) {
                    //("SELECT inventory_test_machine.*,inventory_item.`reagent_name` FROM `inventory_test_machine` LEFT JOIN `inventory_item` ON inventory_test_machine.`item_fk`=`inventory_item`.`id` WHERE inventory_test_machine.`status`='1' AND inventory_test_machine.test_fk='" . $mrkey["test_fk"] . "' AND inventory_test_machine.machine_fk='" . $kkey['machine_fk'] . "' AND inventory_item.`status`='1'");
                    $mrdata = $this->Test_item_model->get_data("SELECT `inventory_test_machine`.*,test_master.`test_name`,inventory_item.`reagent_name` FROM `inventory_test_machine` INNER JOIN test_master ON test_master.`id`=`inventory_test_machine`.`test_fk` INNER JOIN `inventory_item` ON `inventory_item`.`id`=inventory_test_machine.`item_fk` WHERE `test_master`.`status`='1' AND `inventory_test_machine`.`status`='1' AND `inventory_test_machine`.`machine_fk`='" . $kkey['machine_fk'] . "' AND `inventory_item`.`status`='1' and `inventory_test_machine`.`test_fk`='" . $mrkey["test_fk"] . "'");
                    $mrkey["reagent"] = $mrdata;
                    $new_mr_data[] = $mrkey;
                }
                $kkey["details"] = $new_mr_data;

                $data["final_array"][] = $kkey;
            }

            $data["sub_new_list"] = $this->Test_item_model->get_data("select id,test_name from test_master where status='1'");
            $this->load->view('inventory/header');
            $this->load->view('inventory/nav', $data);
            $this->load->view('inventory/add_test', $data);
            $this->load->view('inventory/footer');
        }
    }

    function add_record() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $eid = $this->uri->segment('4');

        $machine_fk = $this->input->post('machine_fk');
        $test_fk = $this->input->post('test_fk');

        $item_list = $this->input->post('item');
        $quantity = $this->input->post('quantity');

        $cnt = 0;
        foreach ($item_list as $kkey) {
            $data = array(
                "test_fk" => $test_fk,
                "machine_fk" => $machine_fk,
                "item_fk" => $kkey,
                "quantity" => $quantity[$cnt],
                "status" => 1,
                "created_date" => date("Y-m-d H:i:s")
            );

            $this->Test_item_model->master_fun_insert("inventory_test_machine", $data);
            $cnt++;
        }

        $this->session->set_flashdata("success", array("Intent successfully added."));
        redirect("inventory/machine/add_test/" . $machine_fk, "refresh");
    }

    function edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $tid = $this->uri->segment('4');

        $mid = $this->uri->segment('5');

        //$edit_item_details = $this->Test_item_model->get_val("SELECT inventory_test_machine.* , `inventory_item`.`reagent_name` AS item_name,test_master.test_name FROM inventory_test_machine INNER JOIN `inventory_item` ON `inventory_item`.`id`=`inventory_test_machine`.`item_fk` left join test_master on test_master.id =inventory_test_machine.test_fk WHERE inventory_test_machine.status = '1' and inventory_test_machine.test_fk = '" . $tid . "' and inventory_test_machine.machine_fk = '" . $mid . "'  ");
        $edit_item_details = $this->Test_item_model->get_data("SELECT `inventory_test_machine`.*,test_master.`test_name`,inventory_item.`reagent_name` as item_name FROM `inventory_test_machine` INNER JOIN test_master ON test_master.`id`=`inventory_test_machine`.`test_fk` INNER JOIN `inventory_item` ON `inventory_item`.`id`=inventory_test_machine.`item_fk` WHERE `test_master`.`status`='1' AND `inventory_test_machine`.`status`='1' AND `inventory_test_machine`.`machine_fk`='" . $mid . "' AND `inventory_item`.`status`='1' and `inventory_test_machine`.`test_fk`='" . $tid . "'");


        $selected_item = array();
        $selected_quentity = array();
        $selected_item_name = array();
        foreach ($edit_item_details as $key) {
            $selected_item[] = $key["item_fk"];
            $selected_quentity[] = $key["quantity"];
            $selected_item_name[] = $key["item_name"];
        }
        $item_details = $this->Test_item_model->get_data("select id,reagent_name from inventory_item where status='1' and machine ='" . $mid . "'");
        ?>
        <input type="hidden" name="test_fk" value="<?= $tid ?>"/>
        <input type="hidden" name="machine_fk" value="<?= $mid ?>"/>
        <div class="form-group">



            <label for="message-text" class="form-control-label">Add Reagent:</label>
            <select class="chosen chosen-select" name="item" id="selected_item_edit" onchange="select_item_edit();">
                <option value="">--Select--</option>
                <?php
                foreach ($item_details as $mkey) {
                    if (!in_array($mkey["id"], $selected_item)) {
                        ?>
                        <option value="<?= $mkey["id"] ?>"><?= $mkey["reagent_name"] ?></option>
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
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="selected_items_edit">
                <?php
                $cnt = 0;
                foreach ($edit_item_details as $ikey) {
                    ?>
                    <tr id="tr_edit_<?= $cnt; ?>">
                        <td><?= $ikey["item_name"] ?></td>
                        <td><input type="hidden" name="item[]" value="<?= $ikey["item_fk"] ?>"><input type="text" name="quantity[]" required="" value="<?= $ikey["quantity"] ?>" class="form-control"/></td>
                        <td><a href="javascript:void(0);" onclick="delete_city_price_edit('<?= $cnt; ?>', '<?= $ikey["item_name"] ?>', '<?= $ikey["machine_fk"] ?>')"><i class="fa fa-trash"></i></a></td>
                    </tr>
                    <?php
                    $cnt++;
                }
                ?>
            <script>$city_cnt_edit = <?= $cnt ?>;</script>
        </tbody>
        </table>
        <?php
    }

    public function delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $tid = $this->uri->segment('4');
        $mid = $this->uri->segment('5');

        $data['query'] = $this->Test_item_model->master_fun_update1("inventory_test_machine", array("test_fk" => $tid, "machine_fk" => $mid), array("status" => "0"));

        $this->session->set_flashdata("success", array("Data Successfull Deleted."));
        redirect("inventory/machine/add_test/" . $mid, "refresh");
    }

    public function machine_update() {

        $this->Test_item_model->master_fun_update1("inventory_test_machine", array("test_fk" => $this->input->post("test_fk"), "machine_fk" => $this->input->post("id")), array("status" => "0"));

        $item_list = $this->input->post("item");
        $quantity = $this->input->post("quantity");

        $machine_fk = $this->input->post('machine_fk');
        $test_fk = $this->input->post('test_fk');



        $cnt = 0;
        foreach ($item_list as $kkey) {
            $data = array(
                "machine_fk" => $machine_fk,
                "test_fk" => $test_fk,
                "item_fk" => $kkey,
                "quantity" => $quantity[$cnt],
                "status" => 1,
                "created_date" => date("Y-m-d H:i:s")
            );
            //    $this->Test_item_model->master_fun_insert("inventory_test_machine", $data);

            $this->Test_item_model->master_fun_insert("inventory_test_machine", $data);

            $cnt++;
        }
        $this->session->set_flashdata("success", array("Intent Request successfully updated."));
        redirect("inventory/machine/add_test/" . $machine_fk, "refresh");
    }

}
?>