<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Intent_Inward extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('inventory/Invert_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
    }

    function inward_list() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        $branch_fk = $this->input->get_post('branch_fk');

        $machine_fk = $this->input->get_post('machine_fk');
        $intent_code = $this->input->get_post('intent_code');
        // print_r($machine_fk);die;
        $data['machine'] = $this->Invert_model->get_val("select id,name as MachineName from inventory_machine where status='1'");
        if ($branch_fk != '' || $machine_fk != '' || $intent_code != '') {
            $totalRows = $this->Invert_model->intent_num($branch_fk, $machine_fk, $intent_code);
            $data['branch_fk'] = $branch_fk;
            $data['machine_fk'] = $machine_fk;
            $data['intent_code'] = $intent_code;
            $config = array();
            $config["base_url"] = base_url() . "inventory/Intent_Inward/inward_list?intent_code=$intent_code&branch_fk=$branch_fk&machine_fk=$machine_fk";
            $config["total_rows"] = $totalRows;

            $config["per_page"] = 50;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);


            //$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;


            $data["query"] = $this->Invert_model->intent_list($branch_fk, $machine_fk, $intent_code, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {
            $totalRows = $this->Invert_model->intent_num($branch_fk, $machine_fk, $intent_code);
            $data['branch_fk'] = $branch_fk;
            $data['machine_fk'] = $machine_fk;
            $config = array();


            //$config["base_url"] = base_url() . "Intent_inward/inward_list?intent_code=$intent_code&branch_fk=$branch_fk&machine_fk=$machine_fk";
            $config["base_url"] = base_url() . "inventory/Intent_Inward/inward_list";



            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';


            $config['page_query_string'] = TRUE;


            $this->pagination->initialize($config);

            //$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;



            $data["query"] = $this->Invert_model->intent_list($branch_fk, $machine_fk, $intent_code, $config["per_page"], $page);
            //echo "<pre>"; print_R($data["final_array"]); die();
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        $new_mr_data = array();
        $data['final_array'] = array();
        //echo "<pre>"; print_r($data['query']); die();
        foreach ($data['query'] as $val) {
            $machine_fk = $val['machine_fk'];
            $intent_id = $val['intent_id'];
            $new_mr_data = $this->Invert_model->get_val("SELECT 
  `inventory_stock_master`.*,
  inventory_item.`reagent_name`,
  `inventory_category`.`name` AS cat,
  `inventory_unit_master`.`name` AS `unit` 
FROM
  inventory_stock_master 
  LEFT JOIN `inventory_item` 
    ON `inventory_item`.id = inventory_stock_master.`item_fk` 
  LEFT JOIN `inventory_category` 
    ON `inventory_category`.`id` = `inventory_item`.`category_fk` 
  LEFT JOIN `inventory_unit_master` 
    ON `inventory_item`.`unit_fk` = `inventory_unit_master`.`id` 
WHERE inventory_stock_master.`status` = '1' AND inventory_stock_master.`inward_fk`='" . $val["id"] . "' ORDER BY `inventory_item`.`id` ASC");
            $val["details"] = $new_mr_data;

            $data["final_array"][] = $val;
        }
        //SHow Data From
        $data['stationary_list'] = $this->Invert_model->get_val("select * from inventory_item where category_fk='1' and status='1' ");
        $data['lab_consum'] = $this->Invert_model->get_val("select * from inventory_item where category_fk='2' and status='1' ");
        $data['category_list'] = $this->Invert_model->get_val("select * from inventory_item where category_fk='3' and status='1'");
        //$data['branch_list'] = $this->Invert_model->get_val('select user_branch.branch_fk as BranchId, branch_master.id,branch_master.branch_name as BranchName from user_branch left join branch_master on branch_master.id = user_branch.branch_fk where user_branch.status="1" and user_branch.user_fk="' . $login_id . '"');

        if ($type == "1" || $type == "2" || $type == "8") {
            $data['branch_list'] = $this->Invert_model->get_val('select user_branch.branch_fk as BranchId, branch_master.id,
                branch_master.branch_name as BranchName 
                from user_branch 
                left join branch_master on branch_master.id = user_branch.branch_fk 
                where user_branch.status="1" GROUP BY branch_master.id');
        } else {
            $data['branch_list'] = $this->Invert_model->get_val('select user_branch.branch_fk as BranchId, branch_master.id,
                branch_master.branch_name as BranchName 
                from user_branch 
                left join branch_master on branch_master.id = user_branch.branch_fk 
                where user_branch.status="1" and user_branch.user_fk="' . $login_id . '" GROUP BY branch_master.id');
        }

        //END  Data From
        if ($type == 8) {
            $this->load->view('inventory/header', $data);
            $this->load->view('inventory/nav', $data);
        } else {
            $this->load->view('header', $data);
            $this->load->view('nav', $data);
        }
        $this->load->view('inventory/inward_list', $data);
        $this->load->view('footer');
    }

    function sub_list() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        $branch_fk = $this->input->get_post('branch_fk');

        $machine_fk = $this->input->get_post('machine_fk');
        $intent_code = $this->input->get_post('intent_code');
        // print_r($machine_fk);die;
        $data['machine'] = $this->Invert_model->get_val("select id,name as MachineName from inventory_machine where status='1'");
        //         $db = $this->Invert_model->get_val("SELECT intent_id as Intent FROM inventory_stock_master WHERE intent_id IS NOT NULL and status='1' ");
        //        die("Ok");
        // if ($db == NULL) {
        //            $data['generate'] = '1';
        //        } else {
        //            $db1 = $this->Invert_model->get_val("SELECT max(intent_id) + 1 as Intent  FROM inventory_stock_master WHERE intent_id IS NOT NULL and status='1' ");
        //            $data['generate'] = $db1;
        //        }

        if ($branch_fk != '' || $machine_fk != '' || $intent_code != '') {

            $totalRows = $this->Invert_model->intent_num($branch_fk, $machine_fk, $intent_code);
            $data['branch_fk'] = $branch_fk;
            $data['machine_fk'] = $machine_fk;
            $data['intent_code'] = $intent_code;
            $config = array();
            $config["base_url"] = base_url() . "Intent_inward/inward_list?intent_code=$intent_code&branch_fk=$branch_fk&machine_fk=$machine_fk";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';

            $this->pagination->initialize($config);
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            $data["query"] = $this->Invert_model->intent_list($branch_fk, $machine_fk, $intent_code, $config["per_page"], $page);

            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {
            $totalRows = $this->Invert_model->intent_num($branch_fk, $machine_fk, $intent_code);

            $config = array();
            $config["base_url"] = base_url() . "Intent_inward/inward_list?intent_code=$intent_code&branch_fk=$branch_fk&machine_fk=$machine_fk";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';

            $this->pagination->initialize($config);
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            $data["query"] = $this->Invert_model->intent_list($branch_fk, $machine_fk, $intent_code, $config["per_page"], $page);

            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        $new_mr_data = array();
        $data['final_array'] = array();
        //echo "<pre>"; print_r($data['query']); die();
        foreach ($data['query'] as $val) {
            $machine_fk = $val['machine_fk'];
            $intent_id = $val['intent_id'];
            $new_mr_data = $this->Invert_model->get_val("SELECT 
  `inventory_stock_master`.*,
  inventory_item.`reagent_name`,
  `inventory_category`.`name` AS cat,
  `inventory_unit_master`.`name` AS `unit` 
FROM
  inventory_stock_master 
  LEFT JOIN `inventory_item` 
    ON `inventory_item`.id = inventory_stock_master.`item_fk` 
  LEFT JOIN `inventory_category` 
    ON `inventory_category`.`id` = `inventory_item`.`category_fk` 
  LEFT JOIN `inventory_unit_master` 
    ON `inventory_item`.`unit_fk` = `inventory_unit_master`.`id` 
WHERE inventory_stock_master.`status` = '1' AND inventory_stock_master.`inward_fk`='" . $val["id"] . "' ORDER BY `inventory_item`.`id` ASC");
            $val["details"] = $new_mr_data;

            $data["final_array"][] = $val;
        }
        //SHow Data From
        $data['stationary_list'] = $this->Invert_model->get_val("select * from inventory_item where category_fk='1' and status='1'");
        $data['lab_consum'] = $this->Invert_model->get_val("select * from inventory_item where category_fk='2' and status='1'");

        $data['category_list'] = $this->Invert_model->get_val("select * from inventory_item where category_fk='3' and status='1'");
        $data['branch_list'] = $this->Invert_model->get_val('select user_branch.branch_fk as BranchId, branch_master.id,branch_master.branch_name as BranchName from user_branch left join branch_master on branch_master.id = user_branch.branch_fk where user_branch.status="1" and user_branch.user_fk="' . $login_id . '"');

        //END  Data From

        $this->load->view('header', $data);
        $this->load->view('inventory/nav', $data);
        $this->load->view('inventory/intent_inward_new', $data);
        $this->load->view('footer');
    }

    function invert_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        // $mid = $this->Intent_model->get_val("SELECT intent_id FROM `inventory_intent_request` ORDER BY intent_id DESC LIMIT 0,1");
        $data["login_data"] = logindata();
        $item_list = $this->input->post("item");
        $quantity = $this->input->post("quantity");
        $sub_intent = $this->input->post('intent_id');
        $branch_fk = $this->input->post('branch_fk');
        $batch_no = $this->input->post('batch_no');
        $intent_code = $this->input->post('intent_code');
        $intent_id = $this->input->post('intent_id');
        $machine_fk = $this->input->post('machine_fk');
        $this->input->post('po_number');
        $invoice_number = $this->input->post('invoice_number');
        $invoice_date = date('Y-m-d', strtotime($this->input->post('invoice_date')));
        $bill_amount = $this->input->post('bill_amount');

        $ponumber = $this->input->post('po_number');
        $temperature = $this->input->post('temperature');
        $pack_ornot = $this->input->post('pack_ornot');
        $supplier = $this->input->post('supplier');
        $receiver = $this->input->post('receiver');

        if ($machine_fk == '') {
            $new_machine_fk = '0';
        } else {
            $new_machine_fk = $machine_fk;
        }
        $po_number1 = $this->Invert_model->get_val("SELECT * FROM `inventory_pogenrate` where ponumber='" . $this->input->post('po_number') . "'");
        $po_number = $po_number1[0]["id"];
        $old_data = $this->Invert_model->get_val("SELECT * FROM `inventory_inward_master` ORDER BY id DESC LIMIT 0,1");
        $this->load->library('upload');
        $config['allowed_types'] = '*';
        $file_loop = count($_FILES['test_images']['name']);




        if ($_FILES['test_images']['name'][0] == "") {
            $this->session->set_flashdata("error", array("Please upload bill."));
            redirect("inventory/intent_genrate/index", "refresh");
        }

        $files = $_FILES;
        $is_file_upload = 0;
        if (!empty($_FILES['test_images']['name'])) {
            $file_loop = count($_FILES['test_images']['name']);

            for ($i = 0; $i < $file_loop; $i++) {

                $_FILES['test_images']['name'] = $files['test_images']['name'][$i];

                $_FILES['test_images']['type'] = $files['test_images']['type'][$i];
                $_FILES['test_images']['tmp_name'] = $files['test_images']['tmp_name'][$i];
                $_FILES['test_images']['error'] = $files['test_images']['error'][$i];
                $_FILES['test_images']['size'] = $files['test_images']['size'][$i];
                $config['upload_path'] = $path;

                $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
                $uploadPath = 'upload/bill/';
                $config['upload_path'] = $uploadPath;
                $config['file_name'] = $files['test_images']['name'][$i];

                //$config['file_name'] = $files['file']['name'][$i];
                $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                $config['overwrite'] = FALSE;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload("test_images")) {
                    //die("Ok");
                } else {
                    $uploads[] = $config['file_name'];
                    $filename = $config['file_name'];
                    $base_url1 = FCPATH . "/upload/bill/$filename";
                }
            }
            $file = implode(' | ', $uploads);
            $is_file_upload = 1;
        }
        $data = array(
            "po_fk" => $po_number,
            "inward_no" => $old_data[0]["inward_no"] + 1,
            "branch_fk" => $branch_fk,
            "created_by" => $new_machine_fk,
            "status" => 1,
            "created_date" => date("Y-m-d H:i:s"),
            "created_by" => $login_id,
            "invoice_no" => $invoice_number,
            "invoice_date" => $invoice_date,
            "bill_amount" => $bill_amount,
            "temperature" => $temperature,
            "pack_ornot" => $pack_ornot,
            "supplier" => $supplier,
            "receiver" => $receiver
        );

        if ($is_file_upload == 1) {
            $data["bill_copy"] = $file;
        }

        $po_insert = $this->Invert_model->master_fun_insert("inventory_inward_master", $data);
        $cnt = 0;
        foreach ($item_list as $kkey) {
            $expire_date = $this->input->post('expire_date')[$cnt];
            $old_date = explode("/", $expire_date);
            $new_date = $old_date[2] . '-' . $old_date[0] . '-' . $old_date[1];
            $data = array(
                "item_fk" => $kkey,
                "inward_fk" => $po_insert,
                "quantity" => $quantity[$cnt],
                "batch_no" => $batch_no[$cnt],
                "expire_date" => $new_date,
                "status" => 1,
                "created_date" => date("Y-m-d H:i:s"),
                "created_by" => $login_id
            );
            $this->Invert_model->master_fun_insert("inventory_stock_master", $data);
            $cnt++;
        }





        $vendor_details = $this->Invert_model->get_val("select v.vendor_name,v.mobile,v.email_id,bm.branch_name 
                    FROM  inventory_pogenrate ip
                    INNER JOIN branch_master bm on bm.id = ip.branchfk 
                    INNER JOIN inventory_vendor v on v.id = ip.vendorid AND v.status = '1'
                    WHERE ip.id = '$po_number'");

        $edit_item_details = $this->Invert_model->get_val("SELECT 
  inventory_poitem.*,
  `inventory_item`.`reagent_name`,
  inventory_poitem.itemnos as quantity,
  `inventory_category`.`name` AS category_name ,
  `inventory_pogenrate`.`branchfk`,
  `inventory_pogenrate`.poprice
FROM
  `inventory_poitem` 
  LEFT JOIN `inventory_item` 
    ON `inventory_item`.`id` = inventory_poitem.`itemid`
    LEFT JOIN `inventory_category` ON `inventory_category`.`id`=`inventory_item`.`category_fk` 
        LEFT JOIN `inventory_pogenrate` ON `inventory_pogenrate`.`id`= inventory_poitem.poid
WHERE inventory_poitem.`status` = '1' 
  AND inventory_poitem.`poid` = '" . $po_number . "' 
  AND inventory_item.`status` = '1'");

        $stock_details = $this->Invert_model->get_val("SELECT ism.id,ism.quantity from inventory_inward_master inw 
                INNER JOIN inventory_stock_master ism on inw.id = ism.inward_fk AND ism.status = '1'
                AND inw.po_fk = '$po_number' AND inw.status ='1'
                ");

        $email = array('pmo@mitushihealthcare.in', 'accounts@airmedlabs.com');
        $this->load->library('email');
        $config['mailtype'] = 'html';
        $this->email->initialize($config);

        $name = $vendor_details[0]['vendor_name'];
        $mobile = $vendor_details[0]['mobile'];
        $email_id = $vendor_details[0]['email_id'];
        $branch_name = $vendor_details[0]['branch_name'];

        $message = "";
        $data = "";
        $j = 0;
        foreach ($edit_item_details as $key) {
            $data .= "<tr><td>" . $key['reagent_name'] . "</td><td>" . $key['category_name'] . "<td>" . $key['quantity'] . "</td><td>" . $stock_details[$j]['quantity'] . "</td></tr>";
            $j++;
        }

        $message .= "
                    <h4><b>Po Inwarded - No : $ponumber</b></h4>
                Respected Sir/Madam, <br/>
                Inventory PO No : $ponumber has been successfully inwarded with below bill details.<br/><br/>
                Invoice No - $invoice_number <br/>
                Invoice Date - $invoice_date <br/>
                Bill Amount - $bill_amount <br/>
                Branch - $branch_name<br/><br/>
                
                <b>Vendor Details:-</b><br/>
                Name - $name <br/>
                Email - $email_id <br/>
                Mobile - $mobile <br/><br/>
                    <b>Item Info:-</b><br/>
                 <table>
                    <tr>
                        <td><b>Item</b></td>
                        <td><b>Category</b></td>
                        <td><b>Quantity</b></td>
                        <td><b>Received Quantity</b></td>
        </tr>
                $data
                    </table>
<br/>
Thanks <br/>
Airmed Pathology Pvt Ltd";

        $this->email->to($email);
        $this->email->cc('kana@virtualheight.com');
        $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
        $this->email->subject('PO Inward');
        $this->email->message($message);
        $this->email->attach($base_url1);
        $this->email->send();






        $this->session->set_flashdata("success", array("Inward successfully added."));
        redirect("inventory/Intent_Inward/inward_list/", "refresh");
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
        $ind_id = $this->uri->segment('6');


        $edit_item_details = $this->Invert_model->get_val("SELECT inventory_stock_master.* , `inventory_item`.`reagent_name` AS item_name,inventory_category.name as Category_name FROM inventory_stock_master INNER JOIN `inventory_item` ON `inventory_item`.`id`=`inventory_stock_master`.`item_fk` left join inventory_category on inventory_category.id = inventory_item.category_fk WHERE inventory_stock_master.status = '1' and inventory_stock_master.branch_fk = '" . $tid . "' and  inventory_stock_master.machine_fk = '" . $mid . "' and inventory_stock_master.intent_id ='" . $ind_id . "'");

        $selected_item = array();
        $selected_quentity = array();
        $selected_item_name = array();
        foreach ($edit_item_details as $key) {
            $selected_item[] = $key["item_fk"];
            $selected_quentity[] = $key["quantity"];
            $selected_item_name[] = $key["item_name"];
            $selected_category[] = $key['Category_name'];
        }
        $item_statonary = $this->Invert_model->get_val("select id,reagent_name from inventory_item where status='1' and category_fk=1");
        $item_Consumables = $this->Invert_model->get_val("select id,reagent_name from inventory_item where status='1' and category_fk=2");
        // $item_details = $this->Intent_model->get_val("select id,reagent_name from inventory_item where status='1' and category_fk=3 and machine IS NOT NULL and machine !=0");
        $machine_array = $this->Invert_model->get_val('select imb.*,im.name as MachineName from inventory_machine_branch as imb  INNER JOIN inventory_machine as im on im.id = imb.machine_fk where imb.branch_fk="' . $tid . '" and imb.status="1" and im.status="1"');

        $data['reagent_array'] = array();
        $new_array1 = array();

        $mrdata = $this->Invert_model->get_val("SELECT inventory_item.id as REAGENTID,inventory_item.reagent_name as REAGENT from inventory_item where machine IN(" . $mid . ") and status='1'");


        $new_array3 = array();
        foreach ($mrdata as $mmkey) {
            if (!in_array($mmkey["REAGENTID"], $new_array1)) {
                $new_array3[] = $mmkey;
                $new_array1[] = $mmkey["REAGENTID"];
            }
        }

        $mrkey["reagent"] = $mrdata;

        $new_array[] = $new_array3;




        $data["reagent_array"] = $new_array;
        ?>

        <input type="hidden" name="branch_fk" value="<?= $tid ?>"/> 
        <input type="hidden" name="machine_fk" value="<?= $mid ?>"/>
        <input type="hidden" name="intent_id" value="<?= $ind_id; ?>">

        <div class="form-group">      
            <label for="message-text" class="form-control-label">Edit Reagent:</label>
            <select class="chosen chosen-select" name="item_fk" id="selected_item_edit" onchange="select_item_edit('Reagent', this);">
                <option value="">--Select--</option>
                <?php
                foreach ($data["reagent_array"][0] as $mkey) {

                    if (!in_array($mkey["REAGENTID"], $selected_item)) {
                        ?>
                        <option value="<?= $mkey["REAGENTID"] ?>"><?= $mkey["REAGENT"] ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="message-text" class="form-control-label">Edit Consumables:</label>
            <select class="chosen chosen-select" name="item_fk" id="consume_edit_id" onchange="select_item_edit('Consumables', this);">
                <option value="">--Select--</option>
                <?php
                foreach ($item_Consumables as $mkey) {
                    if (!in_array($mkey["id"], $selected_item)) {
                        ?>
                        <option value="<?= $mkey["id"] ?>"><?= $mkey["reagent_name"] ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="message-text" class="form-control-label">Edit Stationary:</label>
            <select class="chosen chosen-select" name="item_fk" id="stationary_edit_id" onchange="select_item_edit('Stationary', this);">
                <option value="">--Select--</option>
                <?php
                foreach ($item_statonary as $mkey) {
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
                    <th>Category Name</th>
                    <th>Quantity</th>
                    <th>Batch No</th>
                    <th>Expire Date</th>

                    <th>Action</th>
                </tr>

            </thead>

            <tbody id="selected_items_edit">
            <span id="error_id_new" style="color:red;"> </span>
            <?php
            $cnt = 0;
            foreach ($edit_item_details as $ikey) { //echo "<pre>";print_r($edit_item_details);
                $old = explode("-", $ikey["expire_date"]);
                $new_date = $old[2] . '-' . $old[1] . '-' . $old[0];
                ?>
                <tr id="tr_edit_<?= $cnt; ?>">
                    <td style="width:10%;font:12px;"><?= $ikey["item_name"] ?></td>
                    <td style="width:1%;font:12px;"><?= $ikey['Category_name']; ?></td>
                    <td style="width:10%;font:12px;"><input type="hidden" name="item[]" value="<?= $ikey["item_fk"] ?>"><input type="text" name="quantity[]"  value="<?= $ikey["quantity"] ?>" class="form-control"/></td>
                    <td style="width:20%;font:12px;"><input type="text" name="batch_no[]"  value="<?= $ikey["batch_no"] ?>" class="form-control"/></td>
                    <td style="width:40%;font:12px;"><input type="text" name="expire_date[]"  value="<?= $new_date ?>" class="form-control" placeholder="DD-MM-YYYY" onkeyup="this.value = this.value.replace(/^([\d]{2})([\d]{2})([\d]{4})$/, '$1-$2-$3');" maxlength="10" tabindex="3"/></td>
                    <td style="width:20%;font:12px;"><a href="javascript:void(0);" onclick="delete_city_price_edit('<?= $cnt; ?>', '<?= $ikey["item_name"] ?>', '<?= $ikey["item_fk"] ?>', '<?= $ikey["Category_name"] ?>')"><i class="fa fa-trash"></i></a></td>
                </tr>
                <?php
                $cnt++;
            }
            ?>
            <tfoot>
                <tr><th>Intent Code</th><td><input type="text" name="intent_code" value="<?php echo $ikey['intent_code']; ?>"></td></tr>
            </tfoot>
            <script>$city_cnt_edit = <?= $cnt ?>;</script>
        </tbody>
        </table>
        <?php
    }

    function intent_update() {

        $this->Invert_model->new_fun_update("inventory_stock_master", array("branch_fk" => $this->input->post('branch_fk'), "intent_id" => $this->input->post('intent_id')), array("status" => "0"));
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $item_list = $this->input->post("item");
        $quantity = $this->input->post("quantity");
        $sub_intent = $this->input->post('intent_code');
        $branch_fk = $this->input->post('branch_fk');
        $batch_no = $this->input->post('batch_no');
        $intent_code = $this->input->post('intent_code');
        $machine_fk = $this->input->post('machine_fk');

        $intent_id = $this->input->post('intent_id');

        $cnt = 0;
        foreach ($item_list as $kkey) {
            $expire_date = $this->input->post('expire_date')[$cnt];
            $old_date = explode("-", $expire_date);
            $new_date = $old_date[2] . '-' . $old_date[1] . '-' . $old_date[0];

            $query = $this->Invert_model->get_val("select total from inventory_stock_master where branch_fk = '" . $branch_fk . "' and item_fk ='" . $kkey . "' and status='1' ORDER BY item_fk desc LIMIT 0,1");

            // print_r($query);die;
            if ($query[0] != null) {
                $total_new = $query[0]['total'];
            } else {

                $total_new = 0;
            }
            $total = $quantity[$cnt] + $total_new;


            $data = array(
                "item_fk" => $kkey,
                "branch_fk" => $branch_fk,
                "machine_fk" => $machine_fk,
                "quantity" => $quantity[$cnt],
                "credit" => $quantity[$cnt],
                "intent_code" => $intent_code,
                "batch_no" => $batch_no[$cnt],
                "expire_date" => $new_date,
                "total" => $total,
                "intent_id" => $intent_id,
                "created_date" => date("Y-m-d H:i:s"),
                "created_by" => $login_id
            );

            $this->Invert_model->master_fun_insert("inventory_stock_master", $data);
            $cnt++;
        }

        $this->session->set_flashdata("success", array("Inward successfully updated."));
        redirect("inventory/Intent_Inward/inward_list/", "refresh");
    }

    function delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $tid = $this->uri->segment('4');
        $mid = $this->uri->segment('5');
        $data['query'] = $this->Invert_model->new_fun_update("inventory_inward_master", array("id" => $tid), array("status" => "0"));
        $data['query'] = $this->Invert_model->new_fun_update("inventory_stock_master", array("inward_fk" => $tid), array("status" => "0"));
        $this->session->set_flashdata("success", array("Inward successfull deleted."));
        redirect("inventory/Intent_Inward/inward_list", "refresh");
    }

    public function getReagent() {

        $id = $this->input->post('id');

        $query = $this->Invert_model->get_val("SELECT * from inventory_item where machine ='" . $id . "' and status='1' and machine IS NOT NULL and machine !='' ");

        $sub_array = '';
        $sub_array .= '<option value="">Select Reagent </option>';
        if (!empty($query)) {
            foreach ($query as $val) {
                $sub_array .= '<option value="' . $val['id'] . '">' . $val['reagent_name'] . '</option>';
            }
            echo $sub_array;
        }
        exit;
    }

    public function getMachine() {
        $id = $this->input->post('id');

        $query = $this->Invert_model->get_val("SELECT iim.*,im.name AS MachineName,im.id as MachineId FROM inventory_machine_branch AS iim INNER JOIN inventory_machine AS im ON im.`id`=iim.machine_fk WHERE iim.branch_fk='" . $id . "' AND iim.`status`='1' AND im.status='1'");

        $sub_array = '';
        $sub_array .= '<option value="">Select Machine </option>';
        if (!empty($query)) {
            foreach ($query as $val) {
                $sub_array .= '<option value="' . $val['MachineId'] . '">' . $val['MachineName'] . '</option>';
            }
            echo $sub_array;
        }
        exit;
    }

    function get_po_details() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $poid = $this->input->get('id');
        $po_details = $this->Invert_model->get_val("SELECT id FROM `inventory_pogenrate` WHERE  ponumber='" . $this->input->get('id') . "'");
        $poid = $po_details[0]["id"];
        $user_branch = $this->Invert_model->get_val("SELECT id FROM `inventory_inward_master` WHERE `status`='1' AND po_fk='" . $poid . "'");
        $user_branch1 = $this->Invert_model->get_val("SELECT inventory_pogenrate.id,inventory_inward_master.`id` AS pid FROM `inventory_pogenrate` LEFT JOIN `inventory_inward_master` ON `inventory_inward_master`.`po_fk`=`inventory_pogenrate`.id AND inventory_inward_master.`status`='1'  WHERE (inventory_inward_master.`id` IS NULL OR inventory_inward_master.`id`='') AND inventory_pogenrate.`status`='1'");
        if (count($user_branch) > 0) {
            echo "0";
            die();
        }
        $open_op = array();
        foreach ($user_branch1 as $key) {
            $open_op[] = $key["id"];
        }
        if (!in_array($poid, $open_op)) {
            echo "0";
            die();
        }
        /* Get login user branch */
        if (in_array($data["login_data"]['type'], array("1", "2"))) {
            $user_branch = $this->Invert_model->get_val("SELECT GROUP_CONCAT(branch_fk) AS ids FROM `user_branch` WHERE `status`='1' GROUP BY `status`");
        } else {
            $user_branch = $this->Invert_model->get_val("SELECT GROUP_CONCAT(branch_fk) AS ids FROM `user_branch` WHERE `status`='1' AND `user_fk`='" . $data["login_data"]['id'] . "' GROUP BY `status`");
        }
        /* END */
        $edit_item_details = $this->Invert_model->get_val("SELECT 
  inventory_poitem.*,
  `inventory_item`.`reagent_name`,
  inventory_poitem.itemnos as quantity,
  `inventory_category`.`name` AS category_name ,
  `inventory_pogenrate`.`branchfk`,
  `inventory_pogenrate`.poprice
FROM
  `inventory_poitem` 
  LEFT JOIN `inventory_item` 
    ON `inventory_item`.`id` = inventory_poitem.`itemid`
    LEFT JOIN `inventory_category` ON `inventory_category`.`id`=`inventory_item`.`category_fk` 
        LEFT JOIN `inventory_pogenrate` ON `inventory_pogenrate`.`id`= inventory_poitem.poid
WHERE inventory_poitem.`status` = '1' 
  AND inventory_poitem.`poid` = '" . $poid . "' 
  AND inventory_item.`status` = '1' ");
        //echo "<pre>"; print_R($edit_item_details[0]["branchfk"]); die("OK");
        $selected_item = array();
        $selected_quentity = array();
        $selected_item_name = array();
        foreach ($edit_item_details as $key) {
            $selected_item[] = $key["itemid"];
            $selected_quentity[] = $key["itemnos"];
            $selected_item_name[] = $key["reagent_name"];
            $selected_category[] = $key['category_name'];
        }
        //echo "<pre>"; print_R($selected_item);print_R($selected_quentity);print_R($selected_item_name);print_R($selected_category); die();
        $item_statonary = $this->Invert_model->get_val("select id,reagent_name from inventory_item where status='1' and category_fk=1");
        $item_Consumables = $this->Invert_model->get_val("select id,reagent_name from inventory_item where status='1' and category_fk=2");
        // $item_details = $this->Intent_model->get_val("select id,reagent_name from inventory_item where status='1' and category_fk=3 and machine IS NOT NULL and machine !=0");
        if (!in_array($data["login_data"]['type'], array("1", "2", "8"))) {
            $machine_array = $this->Invert_model->get_val('select imb.*,im.name as MachineName from inventory_machine_branch as imb  INNER JOIN inventory_machine as im on im.id = imb.machine_fk where imb.branch_fk in (' . $user_branch[0]["ids"] . ') and imb.status="1" and im.status="1"');
        } else {
            $machine_array = $this->Invert_model->get_val('select imb.*,im.name as MachineName from inventory_machine_branch as imb  INNER JOIN inventory_machine as im on im.id = imb.machine_fk where imb.status="1" and im.status="1"');
        }

        $data['reagent_array'] = array();
        $new_array1 = array();
        if (!in_array($data["login_data"]['type'], array("1", "2", "8"))) {
            $get_branch_machine = $this->Invert_model->get_val("SELECT GROUP_CONCAT(DISTINCT machine_fk) AS `mid` FROM `inventory_machine_branch` WHERE `status`='1' AND `branch_fk` IN (" . $user_branch[0]["ids"] . ") GROUP BY `status`");
        } else {
            $get_branch_machine = $this->Invert_model->get_val("SELECT GROUP_CONCAT(DISTINCT machine_fk) AS `mid` FROM `inventory_machine_branch` WHERE `status`='1' GROUP BY `status`");
        }
        $mrdata = $this->Invert_model->get_val("SELECT inventory_item.id as REAGENTID,inventory_item.reagent_name as REAGENT from inventory_item where machine IN(" . $get_branch_machine[0]["mid"] . ") and status='1'");

        $new_array3 = array();
        foreach ($mrdata as $mmkey) {
            if (!in_array($mmkey["REAGENTID"], $new_array1)) {
                $new_array3[] = $mmkey;
                $new_array1[] = $mmkey["REAGENTID"];
            }
        }

        $mrkey["reagent"] = $mrdata;

        $new_array[] = $new_array3;




        $data["reagent_array"] = $new_array;
        ?>

        <input type="hidden" name="branch_fk" value="<?= $edit_item_details[0]["branchfk"] ?>"/> 
        <input type="hidden" name="machine_fk" value="<?= $mid ?>"/> 
        <input type="hidden" name="intent_id" value="<?= $ind_id; ?>">

        <?php /*    <div class="form-group">      
          <label for="message-text" class="form-control-label">Edit Reagent:</label>
          <select class="chosen chosen-select" name="item_fk" id="selected_item" onchange="select_item('Reagent', this);">
          <option value="">--Select--</option>
          <?php
          foreach ($data["reagent_array"][0] as $mkey) {
          if (!in_array($mkey["REAGENTID"], $selected_item)) {
          ?>
          <option value="<?= $mkey["REAGENTID"] ?>"><?= $mkey["REAGENT"] ?></option>
          <?php
          }
          }
          ?>
          </select>
          </div>
          <div class="form-group">
          <label for="message-text" class="form-control-label">Edit Consumables:</label>
          <select class="chosen chosen-select" name="item_fk" id="reagent_sub_id" onchange="select_item('Consumables', this);">
          <option value="">--Select--</option>
          <?php
          foreach ($item_Consumables as $mkey) {
          if (!in_array($mkey["id"], $selected_item)) {
          ?>
          <option value="<?= $mkey["id"] ?>"><?= $mkey["reagent_name"] ?></option>
          <?php
          }
          }
          ?>
          </select>
          </div>
          <div class="form-group">
          <label for="message-text" class="form-control-label">Edit Stationary:</label>
          <select class="chosen chosen-select" name="item_fk" id="yahoo_id" onchange="select_item('Stationary', this);">
          <option value="">--Select--</option>
          <?php
          foreach ($item_statonary as $mkey) {
          if (!in_array($mkey["id"], $selected_item)) {
          ?>
          <option value="<?= $mkey["id"] ?>"><?= $mkey["reagent_name"] ?></option>
          <?php
          }
          }
          ?>
          </select>
          </div> */ ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Category Name</th>
                    <th>Quantity</th>
                    <th>Rec. Quantity</th>
                    <th>Batch No</th>
                    <th>Expire Date</th>
        <!--                    <th>Action</th>-->
                </tr>
            </thead>
            <tbody id="selected_items">
            <span id="error_id" style="color:red;"> </span>
            <?php
            $cnt = 0;
            foreach ($edit_item_details as $ikey) { //echo "<pre>";print_r($edit_item_details);
                $old = explode("-", $ikey["expire_date"]);
                $new_date = $old[2] . '-' . $old[1] . '-' . $old[0];
                ?>
                <tr id="tr_edit_<?= $cnt; ?>">
                    <td style="width:10%;font:12px;"><?= $ikey["reagent_name"] ?></td>
                    <td style="width:1%;font:12px;"><?= $ikey['category_name']; ?></td>
                    <td style="width:10%;font:12px;"><?= $ikey["quantity"] ?></td>
                    <td style="width:10%;font:12px;"><input type="hidden" name="item[]" value="<?= $ikey["itemid"] ?>"><input type="text" name="quantity[]" required="" value="<?= $ikey["quantity"] ?>" class="form-control"/></td>
                    <td style="width:20%;font:12px;"><input type="text" name="batch_no[]" required="" value="" class="form-control"/></td>
                    <td style="width:40%;font:12px;"><input type="text" name="expire_date[]" required="" value="" class="form-control indent_datepicker" placeholder="DD/MM/YYYY" readonly=""  maxlength="10" tabindex="3"/></td>
            <!--                    <td style="width:20%;font:12px;"><a href="javascript:void(0);" onclick="delete_city_price_edit('<?= $cnt; ?>', '<?= $ikey["item_name"] ?>', '<?= $ikey["item_fk"] ?>', '<?= $ikey["Category_name"] ?>')"><i class="fa fa-trash"></i></a></td>-->
                </tr>
                <?php
                $cnt++;
            }
            ?>
            <script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
            <script  type="text/javascript">
                var j = jQuery.noConflict();
                var end_date = new Date();
                j('.indent_datepicker').datepicker({
                    dateFormat: 'dd-mm-yyyy',
                    /*endDate: end_date*/
                });
            </script>
            <span><b>PO Price :-</b> Rs.<?= $edit_item_details[0]["poprice"]; ?></span>
        <!--            <tfoot>
        <tr><th>Intent Code</th><td><input type="text" name="intent_code" value="<?php echo $ikey['intent_code']; ?>"></td></tr>
        </tfoot>-->
            <script>$city_cnt_edit = <?= $cnt ?>;</script>
        </tbody>
        </table>
        <?php
    }

    function uploadmore_bill() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $id = $this->input->get_post('purch_or_id');

        $this->load->library('upload');
        $config['allowed_types'] = '*';
        $file_loop = count($_FILES['test_images1']['name']);

        if ($_FILES['test_images1']['name'][0] == "") {
            $this->session->set_flashdata("error", array("Please upload bill."));
            redirect("inventory/intent_genrate/index", "refresh");
        }

        $files = $_FILES;
        $is_file_upload = 0;
        if (!empty($_FILES['test_images1']['name'])) {
            $file_loop = count($_FILES['test_images1']['name']);

            for ($i = 0; $i < $file_loop; $i++) {


                $_FILES['test_images1']['name'] = $files['test_images1']['name'][$i];

                $_FILES['test_images1']['type'] = $files['test_images1']['type'][$i];
                $_FILES['test_images1']['tmp_name'] = $files['test_images1']['tmp_name'][$i];
                $_FILES['test_images1']['error'] = $files['test_images1']['error'][$i];
                $_FILES['test_images1']['size'] = $files['test_images1']['size'][$i];
                $config['upload_path'] = $path;

                $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
                $uploadPath = 'upload/bill/';
                $config['upload_path'] = $uploadPath;
                $config['file_name'] = $files['test_images1']['name'][$i];

                $config['file_name'] = str_replace(' ', '_', $config['file_name']);

                $config['overwrite'] = FALSE;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload("test_images1")) {
                    echo $this->upload->display_errors();
                    exit;
                } else {
                    $uploads[] = $config['file_name'];
                    $filename = $config['file_name'];
                    $base_url1 = FCPATH . "/upload/bill/$filename";
                }
            }
            $file = implode(' | ', $uploads);
            $is_file_upload = 1;
        }

        $get_old_img = $this->Invert_model->get_val("select * from inventory_inward_master where po_fk = '$id'");

        if ($is_file_upload == 1) {
            if (!empty($get_old_img[0]['bill_copy'])) {
                $data1["bill_copy"] = $get_old_img[0]['bill_copy'] . ' | ' . $file;
            } else {
                $data1["bill_copy"] = $file;
            }
        }

        $update = $this->Invert_model->new_fun_update("inventory_inward_master", array("po_fk" => $id), $data1);

        $this->session->set_flashdata("success", array("Bill uploaded successfully"));
        redirect("inventory/intent_genrate/index", "refresh");
    }

}
