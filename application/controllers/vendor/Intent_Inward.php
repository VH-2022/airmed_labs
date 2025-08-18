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

    function invert_add() {
        if (!is_vendorlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_vendorlogin();
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

//        if ($_FILES['test_images']['name'][0] == "") {
//            $this->session->set_flashdata("error", array("Please upload bill."));
//            redirect("inventory/intent_genrate/index", "refresh");
//        }

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
//                    echo $this->upload->display_errors();
//                    exit;
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
            //"inward_no" => $old_data[0]["inward_no"] + 1,
            "branch_fk" => $branch_fk,
            //"created_by" => $new_machine_fk,
            "status" => 1,
            "created_date" => date("Y-m-d h:i:s"),
            "created_by" => $login_id,
            "invoice_no" => $invoice_number,
            "invoice_date" => $invoice_date,
            "bill_amount" => $bill_amount
        );

        if ($is_file_upload == 1) {
            $data["bill_copy"] = $file;
        }


//        $bill_exist = $this->Invert_model->get_val("select * from inventory_inward_master where po_fk = '$po_number'");
//        
//        if(!empty($bill_exist)){
//            $po_insert = $this->Invert_model->new_fun_update("inventory_inward_master", array("po_fk" => $po_number), $data);
//        }else{
//            $po_insert = $this->Invert_model->master_fun_insert("inventory_inward_master", $data);
//        }
        $po_insert = $this->Invert_model->master_fun_insert("inventory_vendor_bil_details", $data);
        
        

//        $cnt = 0;
//        foreach ($item_list as $kkey) {
//            $expire_date = $this->input->post('expire_date')[$cnt];
//            $old_date = explode("/", $expire_date);
//            $new_date = $old_date[2] . '-' . $old_date[0] . '-' . $old_date[1];
//            $data = array(
//                "item_fk" => $kkey,
//                "inward_fk" => $po_insert,
//                "quantity" => $quantity[$cnt],
//                "batch_no" => $batch_no[$cnt],
//                "expire_date" => $new_date,
//                "status" => 1,
//                "created_date" => date("Y-m-d H:i:s"),
//                "created_by" => $login_id
//            );
//            $this->Invert_model->master_fun_insert("inventory_stock_master", $data);
//            $cnt++;
//        }
//
//        // Bhavik 20 June 2018
//
//        $vendor_details = $this->Invert_model->get_val("select v.vendor_name,v.mobile,v.email_id,bm.branch_name 
//                    FROM  inventory_pogenrate ip
//                    INNER JOIN branch_master bm on bm.id = ip.branchfk 
//                    INNER JOIN inventory_vendor v on v.id = ip.vendorid AND v.status = '1'
//                    WHERE ip.id = '$po_number'");
//
//        $edit_item_details = $this->Invert_model->get_val("SELECT 
//  inventory_poitem.*,
//  `inventory_item`.`reagent_name`,
//  inventory_poitem.itemnos as quantity,
//  `inventory_category`.`name` AS category_name ,
//  `inventory_pogenrate`.`branchfk`,
//  `inventory_pogenrate`.poprice
//FROM
//  `inventory_poitem` 
//  LEFT JOIN `inventory_item` 
//    ON `inventory_item`.`id` = inventory_poitem.`itemid`
//    LEFT JOIN `inventory_category` ON `inventory_category`.`id`=`inventory_item`.`category_fk` 
//        LEFT JOIN `inventory_pogenrate` ON `inventory_pogenrate`.`id`= inventory_poitem.poid
//WHERE inventory_poitem.`status` = '1' 
//  AND inventory_poitem.`poid` = '" . $po_number . "' 
//  AND inventory_item.`status` = '1'");
//
//        $stock_details = $this->Invert_model->get_val("SELECT ism.id,ism.quantity from inventory_inward_master inw 
//                INNER JOIN inventory_stock_master ism on inw.id = ism.inward_fk AND ism.status = '1' 
//                AND inw.po_fk = '$po_number' AND inw.status ='1'");
//
////        echo "<pre>";
////        print_r($stock_details);
////        exit;
////        $email = array('bhavik@virtualheight.com', 'ronak.ukani@virtualheight.com', 'vishal.p@virtualheight.com');
//        $email = array('bhavik@virtualheight.com');
//        $this->load->library('email');
//        $config['mailtype'] = 'html';
//        $this->email->initialize($config);
//
//        $name = $vendor_details[0]['vendor_name'];
//        $mobile = $vendor_details[0]['mobile'];
//        $email_id = $vendor_details[0]['email_id'];
//        $branch_name = $vendor_details[0]['branch_name'];
//        //$received_item = $stock_details[0]['quantity'];
//
//        $message = "";
//        $data = "";
//        $j = 0;
//        foreach ($edit_item_details as $key) {
//            $data .= "<tr><td>" . $key['reagent_name'] . "</td><td>" . $key['category_name'] . "<td>" . $key['quantity'] . "</td><td>" . $stock_details[$j]['quantity'] . "</td></tr>";
//            $j++;
//        }
//
//        $message .= "
//                    <h4><b>Po Inwarded - No : $ponumber</b></h4>
//                Respected Sir/Madam, <br/>
//                Inventory PO No : $ponumber has been successfully inwarded with below bill details.<br/><br/>
//                Invoice No - $invoice_number <br/>
//                Invoice Date - $invoice_date <br/>
//                Bill Amount - $bill_amount <br/>
//                Branch - $branch_name<br/><br/>
//                
//                <b>Vendor Details:-</b><br/>
//                Name - $name <br/>
//                Email - $email_id <br/>
//                Mobile - $mobile <br/><br/>
//                    <b>Item Info:-</b><br/>
//                 <table>
//                    <tr>
//                        <td><b>Item</b></td>
//                        <td><b>Category</b></td>
//                        <td><b>Quantity</b></td>
//                        <td><b>Received Quantity</b></td>
//        </tr>
//                $data
//                    </table>
//<br/>
//Thanks <br/>
//Airmed Pathology Pvt Ltd";
//
//        $this->email->to($email);
//        //$this->email->cc('kana@virtualheight.com');
//        $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
//        $this->email->subject('PO Inward');
//        $this->email->message($message);
//        $this->email->attach($base_url1);
//        $this->email->send();
        // Bhavik 20 June 2018

        $this->session->set_flashdata("success", array("Bill successfully uploaded."));
        redirect("vendor/Intent_genrate/index/", "refresh");
    }

}
