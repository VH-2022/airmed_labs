<?php

class Test_item extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('Master_model');
        $this->load->model('inventory/Test_item_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $data["login_data"] = logindata();
    }

    function test_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $reg_id = $this->input->get('search');

        if ($reg_id != '') {
            $srchdata = array("item_name" => $reg_id);
            $data['item_name'] = $reg_id;
            $totalRows = $this->Test_item_model->get_data("select id from test_master where status='1' and test_name like '" . $reg_id . "' order by test_name asc");
            $config = array();
            /* Vishal Code Start */
            $config["base_url"] = base_url() . "inventory/Test_item/test_list?search=$reg_id";
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
            $data["query"] = $this->Test_item_model->get_data("select id,test_name from test_master where status='1' and test_name like '" . $reg_id . "' order by test_name asc limit " . $page . "," . $config["per_page"]);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {
            $srchdata = array();
            $totalRows = $this->Test_item_model->get_data("select id from test_master where status='1' order by test_name asc");
            $config = array();
            $config["base_url"] = base_url() . "inventory/Test_item/test_list/";
            $config["total_rows"] = count($totalRows);
            $config["per_page"] = 100;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            $data["query"] = $this->Test_item_model->get_data("select id,test_name from test_master where status='1' order by test_name asc limit " . $page . "," . $config["per_page"]);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        $this->load->view('inventory/header');
        $this->load->view('inventory/nav', $data);
        $this->load->view('inventory/test_list', $data);
        $this->load->view('inventory/footer');
    }

    public function test_manage() {
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
            $data["item_details"] = $this->Test_item_model->get_data("select id,reagent_name from inventory_item where status='1'");

            $get_machine = $this->Test_item_model->get_data("SELECT DISTINCT `inventory_test_machine`.machine_fk,`inventory_machine`.`name` FROM `inventory_test_machine` INNER JOIN `inventory_machine` ON `inventory_machine`.`id`=`inventory_test_machine`.`machine_fk` WHERE `inventory_test_machine`.`status`='1' AND `inventory_test_machine`.test_fk='" . $data["id"] . "' ");
            die("OK");
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

    function add_test_machine() {
        $item_list = $this->input->post("item");
        $quantity = $this->input->post("quantity");
        $cnt = 0;
        foreach ($item_list as $kkey) {
            $data = array(
                "test_fk" => $this->input->post("test_fk"),
                "machine_fk" => $this->input->post("machine"),
                "item_fk" => $kkey,
                "quantity" => $quantity[$cnt],
                "status" => 1,
                "created_date" => date("Y-m-d H:i:s")
            );
            $this->Test_item_model->master_fun_insert("inventory_test_machine", $data);
            $cnt++;
        }
        $this->session->set_flashdata("success", array("Machine successfully added."));
        redirect("inventory/Test_item/test_manage/" . $this->input->post("test_fk"));
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
        redirect("inventory/Test_item/test_manage/1", "refresh");
    }

    function edit_machine() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $tid = $this->uri->segment('4');
        $mid = $this->uri->segment('5');
        $edit_item_details = $this->Test_item_model->get_data("SELECT 
  inventory_test_machine.* ,
  `inventory_item`.`name` AS item_name
FROM
  inventory_test_machine
  INNER JOIN `inventory_item` ON `inventory_item`.`id`=`inventory_test_machine`.`item_fk` 
WHERE inventory_test_machine.status = '1' 
  AND inventory_test_machine.test_fk = '" . $tid . "' 
  AND inventory_test_machine.machine_fk = '" . $mid . "' ");
        $selected_item = array();
        $selected_quentity = array();
        $selected_item_name = array();
        foreach ($edit_item_details as $key) {
            $selected_item[] = $key["item_fk"];
            $selected_quentity[] = $key["quantity"];
            $selected_item_name[] = $key["item_name"];
        }
        $item_details = $this->Test_item_model->get_data("select id,name from inventory_item where status='1'");
        ?>
        <div class="form-group">
            <input type="hidden" name="machine_fk" value="<?= $mid ?>"/>
            <label for="message-text" class="form-control-label">Add Item:</label>
            <select class="chosen chosen-select" name="item" id="selected_item_edit" onchange="select_item_edit();">
                <option value="">--Select--</option>
                <?php
                foreach ($item_details as $mkey) {
                    if (!in_array($mkey["id"], $selected_item)) {
                        ?>
                        <option value="<?= $mkey["id"] ?>"><?= $mkey["name"] ?></option>
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
                        <td><a href="javascript:void(0);" onclick="delete_city_price_edit('<?= $cnt; ?>', '<?= $ikey["item_name"] ?>', '<?= $ikey["item_fk"] ?>')"><i class="fa fa-trash"></i></a></td>
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

    function edit_test_machine() {
        $this->Test_item_model->master_fun_update1("inventory_test_machine", array("test_fk" => $this->input->post("test_fk"), "machine_fk" => $this->input->post("machine_fk")), array("status" => "0"));
        $item_list = $this->input->post("item");
        $quantity = $this->input->post("quantity");
        $cnt = 0;
        foreach ($item_list as $kkey) {
            $data = array(
                "test_fk" => $this->input->post("test_fk"),
                "machine_fk" => $this->input->post("machine_fk"),
                "item_fk" => $kkey,
                "quantity" => $quantity[$cnt],
                "status" => 1,
                "created_date" => date("Y-m-d H:i:s")
            );
            $this->Test_item_model->master_fun_insert("inventory_test_machine", $data);
            $cnt++;
        }
        $this->session->set_flashdata("success", array("Machine successfully updated."));
        redirect("inventory/Test_item/test_manage/" . $this->input->post("test_fk"));
    }

}
?>