<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Intent_genrate extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('vendor/Intent_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
    }

     public function index() {
        if (!is_vendorlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_vendorlogin();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $data['error'] = $this->session->flashdata("error");

        $indent_code = $this->input->get_post("indent_code");
        $start_date = $this->input->get_post("start_date");
        $end_date = $this->input->get_post("end_date");
        $status = $this->input->get_post("status");
        $ponumber = $this->input->get_post("ponumber");

        $config = array();
        $config["per_page"] = 100;
        $config["uri_segment"] = 4;
        $config['next_link'] = 'Next &rsaquo;';
        $config['prev_link'] = '&lsaquo; Previous';
        $config['page_query_string'] = TRUE;
        $config["base_url"] = base_url() . "inventory/Intent_genrate";

        $temp = '';

        if ($indent_code != '') {
            $temp .= ' AND p.`indentcode` ="' . $indent_code . '"';
        }
        if ($ponumber != '') {
            $temp .= ' AND p.`ponumber` ="' . $ponumber . '"';
        }
        if ($start_date != '') {
            $old = explode("/", $start_date);
            $new_date = $old[2] . '-' . $old[1] . '-' . $old[0];

            $temp .= "AND STR_TO_DATE(p.creteddate,'%Y-%m-%d') >=  '" . date("Y-m-d", strtotime($new_date)) . "'";
        }
        if ($end_date != '') {
            $old_sub = explode("/", $end_date);
            $sub_new_date = $old_sub[2] . '-' . $old_sub[1] . '-' . $old_sub[0];
            $temp .= "AND STR_TO_DATE(p.creteddate,'%Y-%m-%d') <=  '" . date("Y-m-d", strtotime($sub_new_date)) . "'";
        }
        
//        if ($status != '' && $status != '5' && $status != '6') {
        //$temp .= ' AND p.`status` ="' . $status . '"';
        $temp .= ' AND (p.`status` ="1" OR p.`status` ="4")';
//        }
//        switch ($status) {
//            
//            case 1:
//                $checksatus = "AND (SELECT COUNT(`inventory_inward_master`.id) FROM inventory_inward_master WHERE inventory_inward_master.`status` = '1' AND inventory_inward_master.`po_fk` = p.id) = '0' AND (SELECT COUNT(`inventory_popayment`.id) FROM inventory_popayment WHERE inventory_popayment.`status`='1' AND inventory_popayment.poid=p.id)='0'";
        $checksatus = "  AND (SELECT COUNT(`inventory_popayment`.id) 
                          FROM inventory_popayment 
                          WHERE inventory_popayment.`status`='1' 
                          AND inventory_popayment.poid=p.id)='0'";

//                break;
//            case 5:
//                $checksatus = "AND (SELECT COUNT(`inventory_inward_master`.id) FROM inventory_inward_master WHERE inventory_inward_master.`status` = '1' AND inventory_inward_master.`po_fk` = p.id) > '0' AND (SELECT COUNT(`inventory_popayment`.id) FROM inventory_popayment WHERE inventory_popayment.`status`='1' AND inventory_popayment.poid=p.id)='0'";
//                break;
//            case 6:
//                $checksatus = "AND (SELECT COUNT(`inventory_popayment`.id) FROM inventory_popayment WHERE inventory_popayment.`status`='1' AND inventory_popayment.poid=p.id) > '0'";
//                break; 
//            default:
//                $checksatus = "";
//        }

        $totalRows = $this->Intent_model->get_val("select count(p.id) as ID from inventory_pogenrate p where p.status !='0' $temp $checksatus AND vendorid = '$login_id'");
        $config["total_rows"] = $totalRows[0]["ID"];
        $this->pagination->initialize($config);
        $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
        
        $query = $this->Intent_model->get_val("SELECT p.id,p.`creteddate`,p.`discount`,p.`poprice`,
                p.`indentcode`,p.`status`,b.`branch_name`,p.ponumber,
                v.vendor_name,v.mobile as vmobile,v.email_id as vemail,a.`name` AS cretdby,
                p.i_notiy,p.a_notity,p.b_notity 
                FROM inventory_pogenrate p 
                LEFT JOIN `branch_master` b ON b.`id`=p.`branchfk` 
                LEFT JOIN inventory_vendor v ON v.`id`=p.`vendorid` 
                LEFT JOIN admin_master a ON a.`id`=p.`cretedby` 
                WHERE p.`status` !='0' AND vendorid = '$login_id' $temp 
                order by p.id desc limit $page," . $config["per_page"] . "");
        
        //echo "<pre>"; print_r($query); exit;
        $data["links"] = $this->pagination->create_links();
        $data["counts"] = $page;

        $data['query'] = array();
        foreach ($query as $key) {

            $inward_Details = $this->Intent_model->get_val("SELECT `inventory_inward_master`.id FROM inventory_inward_master WHERE inventory_inward_master.`status`='1' AND inventory_inward_master.`po_fk`='" . $key["id"] . "'");
            $key["inward"] = $inward_Details;
            $po_bill_Details = $this->Intent_model->get_val("SELECT `inventory_popayment`.id FROM inventory_popayment WHERE inventory_popayment.`status`='1' AND inventory_popayment.`poid`='" . $key["id"] . "'");

            $key["pobilldetails"] = $po_bill_Details;
            $qry = "SELECT 
  `inventory_poitem`.`itemid`,
  `inventory_poitem`.`itemnos`,
  `inventory_item`.`reagent_name`,
  inventory_unit_master.`name` AS unit,
  `inventory_category`.`name` AS category_name,
  inventory_poitem.`poid` 
FROM
  `inventory_poitem` 
  LEFT JOIN `inventory_item` 
    ON `inventory_poitem`.`itemid` = `inventory_item`.`id` 
  LEFT JOIN `inventory_category` 
    ON `inventory_item`.`category_fk` = `inventory_category`.`id` AND inventory_item.`status`='1'
  LEFT JOIN `inventory_unit_master` 
    ON `inventory_unit_master`.`id` = `inventory_item`.`unit_fk` AND inventory_unit_master.`status`='1' 
WHERE inventory_poitem.`poid`='" . $key["id"] . "'";
            if (in_array($key["status"], array("1", "2", "3"))) {
                $qry .= " and `inventory_poitem`.status='1'";
            }

            $qry .= " ORDER BY `inventory_category`.`name` ASC";
            $key["po_item_list"] = $this->Intent_model->get_val($qry);
            
            $key["stock_master"] = $this->Intent_model->get_val("SELECT `inventory_stock_master`.id FROM inventory_stock_master WHERE inventory_stock_master.`status`='1' AND inventory_stock_master.`inward_fk`='" . $inward_Details[0]["id"] . "'");
            $key["vendor_bill_data"] = $this->Intent_model->get_val("SELECT * FROM inventory_vendor_bil_details WHERE `status`='1' AND `po_fk`='" . $key["id"] . "'");
            
            $data['query'][] = $key;
        }
        //echo "<pre>"; print_r($data); exit;
        
        /* END */

        $this->load->view('vendor/header', $data);
        $this->load->view('vendor/nav', $data);

        $this->load->view('vendor/pogenratelist', $data);
        $this->load->view('footer');
    }

    public function poigenerate() {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('branch_fk', 'Branch Name', 'trim|required');
        $this->form_validation->set_rules('vendorname', 'vendorname', 'trim|required');
        $this->form_validation->set_rules('indentcode', 'indentcode', 'trim|required');
        $this->form_validation->set_rules('maintotal', 'maintotal', 'trim|required');

        if ($this->form_validation->run() != FALSE) {
            /* echo "<pre>"; print_r($_POST);   */

            $branch_fk = $this->input->post('branch_fk');
            $vendorname = $this->input->post('vendorname');
            $indentcode = $this->input->post('indentcode');
            $maintotal = $this->input->post('maintotal');
            $pricediscount = $this->input->post('pricediscount');
            $remark = $this->input->post('remark');


            $item = $this->input->post('item');
            $nosval = $this->input->post('nosval');
            $rateqty = $this->input->post('rateqty');
            $itemtext = $this->input->post('itemtext');
            $itemdis = $this->input->post('itemdis');

            $totalamount = 0;
            $invr_last_id = $this->Intent_model->get_val("SELECT MAX(id)+1 AS iid FROM inventory_pogenrate");
            $po_number = "Airmed/" . date("Y") . "/" . $invr_last_id[0]['iid'];
            $poid = $this->Intent_model->master_fun_insert('inventory_pogenrate', array("branchfk" => $branch_fk, "ponumber" => $po_number, "vendorid" => $vendorname, "indentcode" => $indentcode, "discount" => $pricediscount, "cretedby" => $login_id, "remark" => $remark, "creteddate" => date("Y-m-d H:i:s")));
            for ($i = 0; $i < count($item); $i++) {

                $itemid = $item[$i];
                $nosval1 = $nosval[$i];
                $itemprice = $rateqty[$i];
                $itemtxt = $itemtext[$i];
                if ($itemprice > 0 && $nosval1 > 0) {
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
                    $outamount = $itenqty * $itemprice * $nosval1;

                    $amount = $outamount - ($outamount * $perdisc / 100);
                    $paybleamount = round($amount + ($amount * $txt / 100));
                    $totalamount += round($paybleamount);

                    $this->Intent_model->master_fun_insert('inventory_poitem', array("poid" => $poid, "itemid" => $itemid, "itemnos" => $nosval1, "itenqty" => $itenqty, "itemtxt" => $txt, "txtid" => $txtid, "itemprice" => $paybleamount, "peritemprice" => $itemprice, "itemdis" => $perdisc, "creteddate" => date("Y-m-d H:i:s"), "cretedby" => $login_id));
                }
            }
            $maintotalamount = round($totalamount - $totalamount * $pricediscount / 100);
            $this->Intent_model->master_fun_update('inventory_pogenrate', $poid, array("poprice" => $maintotalamount));

            $this->session->set_flashdata("success", array("Successfully Po Generate In Draft."));
            echo "1";
        } else {

            if ($type == 1 || $type == 2 || $type == 8) {

                $data['branch'] = $this->Intent_model->get_val('select id,id as BranchId,branch_name as BranchName from  branch_master where status="1" ');
            } else {

                $data['branch'] = $this->Intent_model->get_val('select user_branch.branch_fk as BranchId, branch_master.id,branch_master.branch_name as BranchName from user_branch left join branch_master on branch_master.id = user_branch.branch_fk where user_branch.status="1" and user_branch.user_fk="' . $login_id . '"');
            }

            $data['stationary_list'] = $this->Intent_model->get_val("select * from inventory_item where category_fk='1' and status='1'");
            $data['lab_consum'] = $this->Intent_model->get_val("select * from inventory_item where category_fk='2' and status='1'");



            $data['itemtext'] = $this->Intent_model->get_val("select id,tax,title from inventory_tax_master where  status='1'");
            $data['vendor_list'] = $this->Intent_model->get_val("select id,vendor_name from inventory_vendor where status='1'");

            if ($type == 8) {
                $this->load->view('inventory/header', $data);
                $this->load->view('inventory/nav', $data);
            } else {
                $this->load->view('header', $data);
                $this->load->view('nav', $data);
            }
            $this->load->view('inventory/pogentrateviews', $data);
            $this->load->view('footer');
        }
    }

    public function poigeneratedetils($id) {

        if (!is_vendorlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_vendorlogin();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data["logintype"] = $type;

        if ($id != "") {



//            $data['query'] = $this->Intent_model->get_val("SELECT p.id,p.`creteddate`,p.`discount`,p.`branchfk`,p.`poprice`,p.`indentcode`,p.`status`,p.remark,b.`branch_name`,v.vendor_name,a.`name` AS cretdby FROM inventory_pogenrate p LEFT JOIN `branch_master` b ON b.`id`=p.`branchfk` LEFT JOIN inventory_vendor v ON v.`id`=p.`vendorid` LEFT JOIN admin_master a ON a.`id`=p.`cretedby` WHERE p.`status` !='0' and p.id='$id'");


            $data['query'] = $this->Intent_model->get_val("SELECT p.id,p.`creteddate`,p.`discount`,p.`branchfk`,
                    p.`poprice`,p.`indentcode`,p.`status`,p.remark,b.`branch_name`,b.id AS branch_id,v.id AS vendor_id,
                    v.vendor_name,a.`name` AS cretdby 
                    FROM inventory_pogenrate p 
                    LEFT JOIN `branch_master` b ON b.`id`=p.`branchfk` 
                    LEFT JOIN inventory_vendor v ON v.`id`=p.`vendorid` 
                    LEFT JOIN admin_master a ON a.`id`=p.`cretedby` 
                    WHERE p.`status` !='0' and p.id='$id'");

            if ($data['query'] != null) {
                $data['sub_data'] = $this->Intent_model->get_val("SELECT * from inventory_inward_master  WHERE status='1' and po_fk='" . $id . "'");

                $this->load->library('form_validation');
                $this->form_validation->set_rules('bank', 'bank', 'trim|required');
                $this->form_validation->set_rules('neft', 'neft', 'trim|required');
                $this->form_validation->set_rules('date', 'date', 'trim|required');
                $this->form_validation->set_rules('note', 'note', 'trim');

                if ($this->form_validation->run() != FALSE) {

                    $bank = $this->input->post('bank');
                    $neft = $this->input->post('neft');
                    $date = $this->input->post('date');
                    $note = $this->input->post('note');

                    $new_date = explode('/', $date);
                    $date1 = $new_date[2] . "-" . $new_date[1] . "-" . $new_date[0];




                    $vendor_email = $this->Intent_model->get_val("select email_id from inventory_vendor where id='" . $data['query'][0]['vendor_id'] . "' AND status = '1'");
                    $branch_admin = $this->Intent_model->get_val("select email from admin_assign_branch_mail where branch_fk='" . $data['query'][0]['branch_id'] . "' AND status = '1'");
                    //echo "<pre>"; print_r($branch_admin); exit;
                    $final_mail_list = [];
                    foreach ($branch_admin as $key) {
                        $br_admin_email = explode(',', $key['email']);
                        foreach ($br_admin_email as $key) {
                            $final_mail_list[] = $key;
                        }
                    }
                    $final_mail_list[] = $vendor_email[0]['email_id'];
                    $final_mail_list[] = "shikha.kothari@airmedlabs.com";
                    $final_mail_list[] = "helpdesk@airmedlabs.com";
                    $final_mail_list[] = "aradhana.gupta@airmedpathlabs.com";
                    $final_mail_list[] = "amit.gupta@airmedpathlabs.com";

                    //$email = array('bhavik@virtualheight.com');
                    $this->load->library('email');
                    $config['mailtype'] = 'html';
                    $this->email->initialize($config);
                    $branch_name = $data['query'][0]['branch_name'];
                    $amount = $data['query'][0]['poprice'];
                    $date = date('Y-m-d', strtotime($data['query'][0]['creteddate']));

                    $message = "
                Hello, <br/><br/>
For our inwarded order in our Airmed- <b>$branch_name</b> branch dated on - <b>$date</b> the total bill Rs <b>$amount</b>/- has been successfully paid.please confirm the receipt by replying back.
<br/><br/>
Thanks<br/>
Airmed Pathology Pvt Ltd";

                    $this->email->to($final_mail_list);
                    $this->email->cc('kana@virtualheight.com');
                    $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                    $this->email->subject('Bill Paid');
                    $this->email->message($message);
                    $this->email->attach($base_url1);
                    $this->email->send();





                    $this->Intent_model->master_fun_insert('inventory_popayment', array("poid" => $id, "banckid" => $bank, "neft" => $neft, "paydate" => date("Y-m-d", strtotime($date1)), "remark" => $note, "creteddate" => date("Y-m-d H:i:s"), "cretedbby" => $login_id));

                    $this->session->set_flashdata("success", "Successfully Po add Payment.");
                    redirect("vendor/intent_genrate/poigeneratedetils/" . $id, "refresh");
                } else {


                    $data['poitenm'] = $this->Intent_model->get_val("SELECT 
  p.*,
  IF(
    inventory_unit_master.`name` != '',
    CONCAT(
      i.reagent_name,
      '(',
      inventory_unit_master.`name`, 
      ')'
    ),
    i.reagent_name
  ) AS reagent_name,
  i.category_fk 
FROM
  inventory_poitem p 
  LEFT JOIN inventory_item i 
    ON i.`id` = p.`itemid` 
  LEFT JOIN `inventory_unit_master` 
    ON `inventory_unit_master`.`id` = i.`unit_fk` WHERE p.status='1' and p.poid='$id'");

                    $data['bankall'] = $this->Intent_model->get_val("SELECT * from inventory_bank where status='1' ");

                    $data['banmpay'] = $this->Intent_model->get_val("SELECT p.id,p.banckid,p.neft,p.paydate,p.remark,b.bank_name from inventory_popayment p left join inventory_bank b on b.id=p.banckid where p.status='1' and p.poid='$id'");

                    /* echo "<pre>";
                      print_r($data['poitenm']); die(); */

                    $this->load->view('vendor/header', $data);
                    $this->load->view('vendor/nav', $data);

                    $this->load->view('vendor/pogentratedetilsviews', $data);
                    $this->load->view('footer');
                }
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }

    public function poconfirm() {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $id = $this->input->get('id');

        $gepo = $this->Intent_model->get_val("select id,vendorid,discount,ponumber from inventory_pogenrate where  status !='0' and id='$id'");

        if ($id != "" && $gepo != null) {
            if ($type == 1 || $type == 2 || $type == 8) {

                $this->Intent_model->master_fun_update('inventory_pogenrate', $id, array("i_notiy" => '1', "b_notity" => '1', "updatedby" => $login_id, "updateddate" => date("Y-m-d H:i:s"), "status" => 1));
                $vendorid = $gepo[0]["vendorid"];
                $ponumber = $gepo[0]["ponumber"];
                $getvendor = $this->Intent_model->get_val("select vendor_name,mobile,email_id from inventory_vendor where  status ='1' and id='$vendorid'");

                if ($getvendor != null) {

                    $poitenm = $this->Intent_model->get_val("SELECT p.*,i.reagent_name FROM inventory_poitem p LEFT JOIN inventory_item i ON i.`id`=p.`itemid` WHERE p.status='1' and p.poid='$id'");

                    $data['poitenm'] = $this->Intent_model->get_val("SELECT p.*,i.reagent_name,i.category_fk FROM inventory_poitem p LEFT JOIN inventory_item i ON i.`id`=p.`itemid` WHERE p.status='1' and p.poid='$id'");

                    $poitenm = $data['poitenm'];

                    $data["query"] = $this->Intent_model->get_val("SELECT p.id,p.`creteddate`,p.updateddate,p.`discount`,p.`poprice`,p.`indentcode`,p.`status`,p.remark,b.`branch_name`,p.ponumber,b.address as baddress,v.vendor_name,v.address FROM inventory_pogenrate p LEFT JOIN `branch_master` b ON b.`id`=p.`branchfk` LEFT JOIN inventory_vendor v ON v.`id`=p.`vendorid` WHERE p.`status` ='1' and p.id='$id'");

                    $pdfFilePath = FCPATH . "/upload/expense_master/invoice_$id.pdf";
                    //print_r($data);
                    $html = $this->load->view('inventory/invoice_pdf_view', $data, true);

                    $this->load->library('pdf');
                    $pdf = $this->pdf->load();
                    $pdf->autoScriptToLang = true;
                    $pdf->baseScript = 1;
                    $pdf->autoVietnamese = true;
                    $pdf->autoArabic = true;
                    $pdf->autoLangToFont = true;

                    $pdf->AddPage('p', // L - landscape, P - portrait
                            '', '', '', '', 0, // margin_left
                            0, // margin right
                            5, // margin top
                            5, // margin bottom
                            5, // margin header
                            5);
                    $pdf->SetHTMLFooter('<table class="tbl_full" style="margin-bottom:20px; text-align:right;">
					<tr>
						<td><b>AIRRMED PATHOLOGY PVT.LTD.</b></td>
					</tr>
				</table>

<div style="height:20px;"><p class="rslt_p_brdr"></p>
</div>');

                    $pdf->WriteHTML($html);
                    $pdf->debug = true;
                    $pdf->allow_output_buffering = TRUE;
                    if (file_exists($pdfFilePath) == true) {
                        $this->load->helper('file');
                        unlink($pdfFilePath);
                    }
                    $pdf->Output($pdfFilePath, 'F');


                    $base_url1 = $pdfFilePath; // base_url()."upload/expense_master/invoice_$id.pdf";


                    $this->load->library('email');
                    $config['mailtype'] = 'html';

                    $this->email->initialize($config);
                    $message = "";
                    $message .= "
                    <h4><b>Po Generated - No : $ponumber</b></h4>
                Dear Sir, <br>
Kindly Check the attached PO and send us all material as per PO on urgent Basis.

<br>

With Regards,
Airmed Labs                ";


                    $this->email->to($getvendor[0]["email_id"]);
                    $this->email->cc('hiten.chauhan@airmedlabs.com');
                    $this->email->cc('accounts@airmedlabs.com');
                    $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                    $this->email->subject("Po Generated : $ponumber");
                    $this->email->message($message);
                    $this->email->attach($base_url1);
                    $this->email->send();
                }


                $this->session->set_flashdata("success", array("Successfully Po Generate."));
                redirect("inventory/intent_genrate/index", "refresh");
            } else {
                show_404();
            }
        } else {

            show_404();
        }
    }

    public function poigenerateedit($id) {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data["logintype"] = $type;

        if ($id != "") {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('branch_fk', 'Branch Name', 'trim|required');
            $this->form_validation->set_rules('vendorname', 'vendorname', 'trim|required');
            $this->form_validation->set_rules('indentcode', 'indentcode', 'trim|required');
            $this->form_validation->set_rules('maintotal', 'maintotal', 'trim|required');
            $this->form_validation->set_rules('pogid', 'pogid', 'trim|required');
            if ($this->form_validation->run() != FALSE) {

                $poid = $this->input->post('pogid');

                $branch_fk = $this->input->post('branch_fk');
                $vendorname = $this->input->post('vendorname');
                $indentcode = $this->input->post('indentcode');
                $maintotal = $this->input->post('maintotal');
                $pricediscount = $this->input->post('pricediscount');
                $remark = $this->input->post('remark');

                $item = $this->input->post('item');
                $nosval = $this->input->post('nosval');
                $rateqty = $this->input->post('rateqty');
                $itemtext = $this->input->post('itemtext');
                $itemdis = $this->input->post('itemdis');


                $totalamount = 0;
                $this->Intent_model->new_fun_update('inventory_poitem', array("poid" => $poid, "status" => '1'), array("status" => '0'));
                $podetails = $this->Intent_model->get_val("SELECT p.id,p.remark,p.branchfk,p.vendorid,p.`creteddate`,p.`discount`,p.`poprice`,p.`indentcode`,p.`status` FROM inventory_pogenrate p  WHERE p.`status` in (1,2,3) and p.id='$poid'");
                if ($podetails[0]["status"] == 1) {
                    $this->Intent_model->new_fun_update('inventory_pogenrate', array("id" => $poid), array("status" => '4'));
                    $invr_last_id = $this->Intent_model->get_val("SELECT MAX(id)+1 AS iid FROM inventory_pogenrate");
                    $po_number = "Airmed/" . date("Y") . "/" . $invr_last_id[0]['iid'];
                    $poid = $this->Intent_model->master_fun_insert('inventory_pogenrate', array("branchfk" => $branch_fk, "ponumber" => $po_number, "vendorid" => $vendorname, "indentcode" => $indentcode, "discount" => $pricediscount, "cretedby" => $login_id, "remark" => $remark, "creteddate" => date("Y-m-d H:i:s")));
                }
                for ($i = 0; $i < count($item); $i++) {

                    $itemid = $item[$i];
                    $nosval1 = $nosval[$i];
                    $itemprice = round($rateqty[$i], 2);
                    $itemtxt = $itemtext[$i];
                    if ($itemprice > 0 && $nosval1 > 0) {

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
                        $outamount = $itenqty * $itemprice * $nosval1;
                        $amount = $outamount - ($outamount * $perdisc / 100);

                        $paybleamount = round($amount + ($amount * $txt / 100), 2);

                        $totalamount += round($paybleamount, 2);

                        $this->Intent_model->master_fun_insert('inventory_poitem', array("poid" => $poid, "itemid" => $itemid, "itemnos" => $nosval1, "itenqty" => $itenqty, "itemtxt" => $txt, "txtid" => $txtid, "itemprice" => $paybleamount, "peritemprice" => $itemprice, "itemdis" => $perdisc, "creteddate" => date("Y-m-d H:i:s"), "cretedby" => $login_id));
                    }
                }
                $maintotalamount = round($totalamount - $totalamount * $pricediscount / 100);
                $this->Intent_model->master_fun_update('inventory_pogenrate', $poid, array("branchfk" => $branch_fk, "vendorid" => $vendorname, "indentcode" => $indentcode, "discount" => $pricediscount, "updatedby" => $login_id, "remark" => $remark, "updateddate" => date("Y-m-d H:i:s"), "poprice" => $maintotalamount));

                $this->adminsendpo($poid);

                $this->session->set_flashdata("success", array("Successfully Edited Po."));

                echo "1";
            } else {

                if ($type == 1 || $type == 2 || $type == 8) {

                    $data['branch'] = $this->Intent_model->get_val('select id,id as BranchId,branch_name as BranchName from  branch_master where status="1" ');
                } else {

                    $data['branch'] = $this->Intent_model->get_val('select user_branch.branch_fk as BranchId, branch_master.id,branch_master.branch_name as BranchName from user_branch left join branch_master on branch_master.id = user_branch.branch_fk where user_branch.status="1" and user_branch.user_fk="' . $login_id . '"');
                }

                $data['query'] = $this->Intent_model->get_val("SELECT p.id,p.remark,p.branchfk,p.vendorid,p.`creteddate`,p.`discount`,p.`poprice`,p.`indentcode`,p.`status` FROM inventory_pogenrate p  WHERE p.`status` in (1,2,3) and p.id='$id'");

                if ($data['query'] != null) {

                    $data['poitenm'] = $this->Intent_model->get_val("SELECT p.*,i.reagent_name,i.category_fk FROM inventory_poitem p LEFT JOIN inventory_item i ON i.`id`=p.`itemid` WHERE p.status='1' and p.poid='$id'");

                    /*
                      $data['stationary_list'] = $this->Intent_model->get_val("select * from inventory_item where category_fk='1' and id not IN(SELECT itemid FROM inventory_poitem WHERE status='1' AND poid='$id') and status='1'");

                      $data['lab_consum'] = $this->Intent_model->get_val("select * from inventory_item where category_fk='2' and id not IN(SELECT itemid FROM inventory_poitem WHERE status='1' AND poid='$id') and status='1'");
                     */
                    $qry = "SELECT it.* 
FROM inventory_item AS it  LEFT JOIN inventory_unit_master ON inventory_unit_master.id = it.unit_fk AND inventory_unit_master.status = '1' LEFT JOIN branch_master AS br ON br.id = it.branch_fk 
 AND br.status = '1' WHERE it.branch_fk = '" . $data['query'][0]["branchfk"] . "' AND it.status = '1' AND it.`category_fk`='2' and it.id not IN(SELECT itemid FROM inventory_poitem WHERE status='1' AND poid='$id')
   ";
                    $data['lab_consum'] = $this->Intent_model->get_val($qry);
                    $data['stationary_list'] = $this->Intent_model->get_val("SELECT it.* 
FROM inventory_item AS it  LEFT JOIN inventory_unit_master ON inventory_unit_master.id = it.unit_fk AND inventory_unit_master.status = '1' LEFT JOIN branch_master AS br ON br.id = it.branch_fk 
 AND br.status = '1' WHERE it.branch_fk = '" . $data['query'][0]["branchfk"] . "' AND it.status = '1' AND it.`category_fk`='1' and it.id not IN(SELECT itemid FROM inventory_poitem WHERE status='1' AND poid='$id')
   ");
                    $data['itemtext'] = $this->Intent_model->get_val("select id,tax,title from inventory_tax_master where  status='1'");
                    $data['vendor_list'] = $this->Intent_model->get_val("select id,vendor_name from inventory_vendor where status='1'");

                    /* echo "<pre>";
                      print_r($data['poitenm']); die(); */

                    if ($type == 8) {
                        $this->load->view('inventory/header', $data);
                        $this->load->view('inventory/nav', $data);
                    } else {
                        $this->load->view('header', $data);
                        $this->load->view('nav', $data);
                    }
                    $this->load->view('inventory/pogentratediteviews', $data);
                    $this->load->view('footer');
                } else {
                    echo "invalid request.";
                }
            }
        } else {
            echo "invalid request.";
        }
    }

    public function posaved($id) {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];

        $this->Intent_model->master_fun_update('inventory_pogenrate', $id, array("status" => 3));

        $this->adminsendpo($id);

        $this->session->set_flashdata("success", array("Successfully Saved Po."));

        redirect("inventory/intent_genrate/index", "refresh");
    }

    public function adminsendpo($id) {

        if (!is_loggedin()) {
            redirect('login');
        }


        $data['poitenm'] = $this->Intent_model->get_val("SELECT p.*,i.reagent_name,i.category_fk FROM inventory_poitem p LEFT JOIN inventory_item i ON i.`id`=p.`itemid` WHERE p.status='1' and p.poid='$id'");

        $poitenm = $data['poitenm'];

        $data["query"] = $this->Intent_model->get_val("SELECT p.id,p.`creteddate`,p.updateddate,p.`discount`,p.`poprice`,p.`indentcode`,p.`status`,p.remark,b.`branch_name`,p.ponumber,b.address as baddress,v.vendor_name,v.address FROM inventory_pogenrate p LEFT JOIN `branch_master` b ON b.`id`=p.`branchfk` LEFT JOIN inventory_vendor v ON v.`id`=p.`vendorid` WHERE p.`status` !='0' and p.id='$id'");


        $ponumber = $data["query"][0]["ponumber"];

        $pdfFilePath = FCPATH . "/upload/expense_master/invoice_$id.pdf";
        $html = $this->load->view('inventory/invoice_pdf_view', $data, true);



        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1;
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;
        $pdf->autoLangToFont = true;

        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 0, // margin_left
                0, // margin right
                5, // margin top
                5, // margin bottom
                5, // margin header
                5);
        $pdf->SetHTMLFooter('<table class="tbl_full" style="margin-bottom:20px; text-align:right;">
					<tr>
						<td><b>AIRRMED PATHOLOGY PVT.LTD.</b></td>
					</tr>
				</table>

<div style="height:20px;"><p class="rslt_p_brdr"></p>
</div>');

        $pdf->WriteHTML($html);
        $pdf->debug = true;
        $pdf->allow_output_buffering = TRUE;
        if (file_exists($pdfFilePath) == true) {
            $this->load->helper('file');
            unlink($pdfFilePath);
        }
        $pdf->Output($pdfFilePath, 'F');

        $base_url1 = $pdfFilePath;

        /* $message .= '<div style="padding:0 4%;">
          <h4><b>Po Generate</b></h4></div>';

          $message .= '<table style="border: 1px solid black;" >
          <thead>
          <tr>

          <th style="border: 1px solid black;">No</th>
          <th style="border: 1px solid black;">Item</th>
          <th style="border: 1px solid black;">NOS</th>
          <th style="border: 1px solid black;">Qty.</th>
          <th style="border: 1px solid black;">Rate per Test</th>
          <th style="border: 1px solid black;">Amount Rs.</th>
          <th style="border: 1px solid black;">TAX</th>
          <th style="border: 1px solid black;">TOTAL PAYABLE</th>

          </tr>
          </thead>
          <tbody id="selected_items">';
          $cnt = 0;
          $totalprice = 0;
          foreach ($poitenm as $row) {
          $cnt++;
          $message .= "<tr>
          <td style='border: 1px solid black;'>" . $cnt . "</td>
          <td style='border: 1px solid black;'>" . $row["reagent_name"] . "</td>
          <td style='border: 1px solid black;'>" . $row["itemnos"] . "</td>
          <td style='border: 1px solid black;'>" . $row["itenqty"] . "</td>
          <td style='border: 1px solid black;'>" . $row["peritemprice"] . "</td>
          <td style='border: 1px solid black;'>" . $row["peritemprice"] * $row["itemnos"] * $row["itenqty"] . "</td>
          <td style='border: 1px solid black;'>" . $row["itemtxt"] . "</td>
          <td style='border: 1px solid black;'>" . $row["itemprice"] . "</td>
          </tr>";
          $totalprice += $row["itemprice"];
          }
          $finaltotal = round($totalprice - ($totalprice * $gepo[0]["discount"] / 100));
          $message .= "<tr>
          <td style='border: 1px solid black;' colspan='6'></td>
          <td style='border: 1px solid black;'>Discount</td>
          <td style='border: 1px solid black;'>" . $gepo[0]["discount"] . "</td>
          </tr>
          <tr>
          <td style='border: 1px solid black;' colspan='6'></td>
          <td style='border: 1px solid black;' >Total Amount Rs.</td>
          <td style='border: 1px solid black;'>" . $finaltotal . "</td>
          </tr>
          </tbody>"; */

        $this->load->library('email');
        $config['mailtype'] = 'html';

        $apppath = base_url() . "inventory/intent_genrate/pomailconfirm/?id=" . $id;
        $this->email->initialize($config);
        $message = "";
        $message .= "
                    <h4><b>Po Generated - No : $ponumber</b></h4>
                Dear Sir, <br>
Kindly Check the attached PO and <a href='$apppath'>Click here to approve PO</a>.

<br>

With Regards,
Airmed Labs";


        $this->email->to('aradhana.gupta@airmedpathlabs.com');
        $this->email->cc('nishit@virtualheight.com');
        $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
        $this->email->subject('Po Generate Approval');
        $this->email->message($message);
        $this->email->attach($base_url1);
        $this->email->send();
    }

    public function getitenqty() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $statid = $this->input->get_post("itenid");
        $branch_fk = $this->input->get_post("branch_fk");
        $stationary_list = $this->Intent_model->get_val("select quantity,box_price from inventory_item where id='$statid' and status='1'");
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
  AND inw.branch_fk = '" . $branch_fk . "'
  AND s.item_fk='" . $statid . "'
GROUP BY r.`id` 
ORDER BY s.id ASC 
LIMIT 0,1");
        $stock = ($available_stock[0]["stcok"] > 0) ? $available_stock[0]["stcok"] : 0;
        $data = array("qty" => $stationary_list[0]["quantity"], "price" => $stationary_list[0]["box_price"], "available_stock" => $stock);
        echo json_encode($data);
    }

    public function getsub() {

        $id = $this->input->post('branch_fk');
        $poid = $this->input->post('pogid');

        $query = $this->Intent_model->get_val("SELECT iim.*,im.name AS MachineName,im.id as MachineId,it.id as Reagent_Id,it.reagent_name as Reagent_name FROM inventory_machine_branch AS iim INNER JOIN inventory_machine AS im ON im.`id`=iim.machine_fk INNER JOIN inventory_item as it on it.machine = im.id WHERE iim.branch_fk='" . $id . "' And it.id not IN(SELECT itemid FROM inventory_poitem WHERE status='1' AND poid='$poid') AND iim.`status`='1' AND im.status='1' and it.status='1'");

        $reagent_array = '';
        $reagent_array .= '<option value="">Select Reagent</option>';
        if (!empty($query)) {
            foreach ($query as $key => $val) {
                $reagent_array .= '<option value="' . $val['Reagent_Id'] . '">' . $val['Reagent_name'] . '</option>';
            }
            echo $reagent_array;
        }
        exit;
    }

    public function invoice_pdf($id) {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];


        $data["logintype"] = $type;

        if ($id != "") {


            $data["query"] = $this->Intent_model->get_val("SELECT p.id,p.`creteddate`,p.updateddate,p.`discount`,p.`poprice`,p.`indentcode`,p.`status`,p.remark,b.`branch_name`,p.ponumber,b.address as baddress,v.vendor_name,v.address FROM inventory_pogenrate p LEFT JOIN `branch_master` b ON b.`id`=p.`branchfk` LEFT JOIN inventory_vendor v ON v.`id`=p.`vendorid` WHERE p.`status` ='1' and p.id='$id'");

            if ($data['query'] != null) {

                $data['poitenm'] = $this->Intent_model->get_val("SELECT p.*,i.reagent_name,i.category_fk FROM inventory_poitem p LEFT JOIN inventory_item i ON i.`id`=p.`itemid` WHERE p.status='1' and p.poid='$id'");

                $pdfFilePath = FCPATH . "/upload/expense_master/invoice_$id.pdf";
                $html = $this->load->view('inventory/invoice_pdf_view', $data, true);

                $this->load->library('pdf');
                $pdf = $this->pdf->load();
                $pdf->autoScriptToLang = true;
                $pdf->baseScript = 1;
                $pdf->autoVietnamese = true;
                $pdf->autoArabic = true;
                $pdf->autoLangToFont = true;

                $pdf->AddPage('p', // L - landscape, P - portrait
                        '', '', '', '', 0, // margin_left
                        0, // margin right
                        5, // margin top
                        5, // margin bottom
                        5, // margin header
                        5);
                $pdf->SetHTMLFooter('<table class="tbl_full" style="margin-bottom:20px; text-align:right;">
					<tr>
						<td><b>AIRRMED PATHOLOGY PVT.LTD.</b></td>
					</tr>
				</table>

<div style="height:20px;"><p class="rslt_p_brdr"></p>
</div>');

                $pdf->WriteHTML($html);
                $pdf->debug = true;
                $pdf->allow_output_buffering = TRUE;
                if (file_exists($pdfFilePath) == true) {
                    $this->load->helper('file');
                    unlink($pdfFilePath);
                }
                $pdf->Output($pdfFilePath, 'F');


                $base_url = base_url() . "upload/expense_master/invoice_$id.pdf";


                $filename = FCPATH . "/upload/expense_master/invoice_$id.pdf";
                header('Pragma: public');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Cache-Control: private', false); // required for certain browsers 
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="' . basename($filename) . '";');
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: ' . filesize($filename));
                readfile($filename);
                exit;
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }

    public function poexport() {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        $branch = $this->input->get_post("branch");
        $vender = $this->input->get_post("vender");
        $indent_code = $this->input->get_post("indent_code");
        $start_date = $this->input->get_post("start_date");
        $end_date = $this->input->get_post("end_date");
        $status = $this->input->get_post("status");
        $ponumber = $this->input->get_post("ponumber");
        $temp = '';
        if ($branch != '') {
            $temp .= ' AND p.`branchfk` ="' . $branch . '"';
        }
        if ($vender != '') {
            $temp .= ' AND p.`vendorid` ="' . $vender . '"';
        }
        if ($indent_code != '') {
            $temp .= ' AND p.`indentcode` ="' . $indent_code . '"';
        }
        if ($ponumber != '') {
            $temp .= ' AND p.`ponumber` ="' . $ponumber . '"';
        }
        if ($start_date != '') {
            $old = explode("/", $start_date);
            $new_date = $old[2] . '-' . $old[1] . '-' . $old[0];

            $temp .= "AND STR_TO_DATE(p.creteddate,'%Y-%m-%d') >=  '" . date("Y-m-d", strtotime($new_date)) . "'";
        }
        if ($end_date != '') {
            $old_sub = explode("/", $end_date);
            $sub_new_date = $old_sub[2] . '-' . $old_sub[1] . '-' . $old_sub[0];
            $temp .= "AND STR_TO_DATE(p.creteddate,'%Y-%m-%d') <=  '" . date("Y-m-d", strtotime($sub_new_date)) . "'";
        }

        if ($status != '' && $status != '5' && $status != '6') {
            $temp .= ' AND p.`status` ="' . $status . '"';
        }

        switch ($status) {

            case 1:
                $checksatus = "AND (SELECT COUNT(`inventory_inward_master`.id) FROM inventory_inward_master WHERE inventory_inward_master.`status` = '1' AND inventory_inward_master.`po_fk` = p.id) = '0' AND (SELECT COUNT(`inventory_popayment`.id) FROM inventory_popayment WHERE inventory_popayment.`status`='1' AND inventory_popayment.poid=p.id)='0'";
                break;
            case 5:
                $checksatus = "AND (SELECT COUNT(`inventory_inward_master`.id) FROM inventory_inward_master WHERE inventory_inward_master.`status` = '1' AND inventory_inward_master.`po_fk` = p.id) > '0' AND (SELECT COUNT(`inventory_popayment`.id) FROM inventory_popayment WHERE inventory_popayment.`status`='1' AND inventory_popayment.poid=p.id)='0'";
                break;
            case 6:
                $checksatus = "AND (SELECT COUNT(`inventory_popayment`.id) FROM inventory_popayment WHERE inventory_popayment.`status`='1' AND inventory_popayment.poid=p.id) > '0'";
                break;
            default:
                $checksatus = "";
        }
        if ($type == 8 || $type == 1 || $type == 2) {

            $query = $this->Intent_model->get_val("SELECT p.id,p.`creteddate`,p.`discount`,p.`poprice`,p.`indentcode`,p.`status`,b.`branch_name`,p.ponumber,v.vendor_name,a.`name` AS cretdby FROM inventory_pogenrate p LEFT JOIN `branch_master` b ON b.`id`=p.`branchfk` LEFT JOIN inventory_vendor v ON v.`id`=p.`vendorid` LEFT JOIN admin_master a ON a.`id`=p.`cretedby` WHERE p.`status` !='0' $temp $checksatus order by p.id desc");
        } else {

            $query = $this->Intent_model->get_val("SELECT p.id,p.`creteddate`,p.`discount`,p.`poprice`,p.`indentcode`,p.`status`,b.`branch_name`,p.ponumber,v.vendor_name,a.`name` AS cretdby FROM inventory_pogenrate p LEFT JOIN `branch_master` b ON b.`id`=p.`branchfk` LEFT JOIN inventory_vendor v ON v.`id`=p.`vendorid` LEFT JOIN admin_master a ON a.`id`=p.`cretedby` WHERE p.`status` !='0' AND p.`status`='1' and p.branchfk in(SELECT `branch_fk`  FROM `user_branch`  WHERE STATUS = '1' AND user_fk = " . $login_id . ") $temp $checksatus order by p.id desc");
        }

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"polistReport.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No", "PO Number", "Branch Name", "Vendor Name", "Indent Code", "Price", "Added by", "Created Date", "Status"));
        $i = 0;

        foreach ($query as $key) {

            $inward_Details = $this->Intent_model->get_val("SELECT `inventory_inward_master`.id FROM inventory_inward_master WHERE inventory_inward_master.`status`='1' AND inventory_inward_master.`po_fk`='" . $key["id"] . "'");
            $key["inward"] = $inward_Details;
            $po_bill_Details = $this->Intent_model->get_val("SELECT `inventory_popayment`.id FROM inventory_popayment WHERE inventory_popayment.`status`='1' AND inventory_popayment.`poid`='" . $key["id"] . "'");


            if ($key["status"] == 1 && count($inward_Details) == 0 && count($po_bill_Details) == 0) {
                $status = "Approved";
                ;
            } else if ($key["status"] == 2) {
                $status = "Draft";
                ;
            } else if ($key["status"] == 3) {
                $status = "Waiting for Approval";
                ;
            } else if (count($inward_Details) > 0 && count($po_bill_Details) == 0) {
                $status = "In-warded";
                ;
            } else if (count($po_bill_Details) > 0) {
                $status = "Bill Paid";
                ;
            }
            $i++;
            fputcsv($handle, array($i, $key["ponumber"], ucwords($key["branch_name"]), $key["vendor_name"], $key["indentcode"], $key["poprice"], $key["cretdby"], date("d-m-Y", strtotime($key["creteddate"])), $status));
        }

        fclose($handle);
        exit;
    }

    public function cancelpo($id) {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        if ($type == 1 || $type == 2) {

            $this->Intent_model->master_fun_update('inventory_pogenrate', $id, array("status" => 4));
            $this->session->set_flashdata("success", array("Successfully Po Canceled."));
            redirect("inventory/intent_genrate/index", "refresh");
        } else {
            show_404();
        }
    }

    public function oldpodetails($id) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];

        $data["logintype"] = $type;
        $branch = $this->input->get_post("branchid");

        if ($id != "") {

            if ($type == 1 || $type == 2 || $type == 8) {

                $getindentid = $this->Intent_model->get_val("SELECT id FROM `inventory_intent_master` WHERE id < $id AND branch_fk='$branch' AND status !='0' ORDER BY id DESC LIMIT 5");
            } else {

                $getindentid = $this->Intent_model->get_val("SELECT id FROM `inventory_intent_master` WHERE id < $id and branch_fk in(SELECT `branch_fk`  FROM `user_branch`  WHERE STATUS = '1' AND user_fk = " . $login_id . ") AND status !='0' AND branch_fk='$branch' ORDER BY id DESC LIMIT 5");
            }

            $indenarray = array();
            foreach ($getindentid as $ind) {

                $indenarray[] = $ind["id"];
            }

            if ($indenarray != null) {
                $poitenm = $this->Intent_model->get_val("SELECT p.id,p.quantity,p.category_fk as itemid,p.created_date,i.reagent_name,i.category_fk as cattype,i.quantity as qty,i.box_price FROM inventory_intent_request p LEFT JOIN inventory_item i ON i.`id`=p.category_fk WHERE p.status='1' and p.indent_fk in(" . implode(",", $indenarray) . ")");
            } else {
                $poitenm = array();
            }
            ?>
            <div class="table-responsive pending_job_list_tbl">

                <table class="table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Category</th> 
                            <th>Item</th>
                            <th>Qty.</th>
                            <th>Date</th>
                            <?php /* <th>Amount Rs.</th> */ ?>


                        </tr>
                    </thead>
                    <tbody id="">

                        <?php
                        $cnt = 0;
                        $totalprice = 0;
                        foreach ($poitenm as $row) {
                            $cnt++;
                            $itetype = $row["cattype"];
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
                            ?>
                            <tr>
                                <td><?= $cnt ?></td>
                                <td><?= $whotype ?></td>
                                <td><?= $row["reagent_name"]; ?></td>
                                <td><?= $row["quantity"]; ?></td>
                                <td><?= date("d-m-Y", strtotime($row["created_date"])); ?></td>

                                <?php /* <td><?= $row["box_price"]*$row["quantity"]; ?></td> */ ?>

                            </tr>

                        <?php }if (empty($poitenm)) {
                            ?>
                            <tr>
                                <td colspan="5">No records found</td>
                            </tr>
                        <?php } ?>
                    </tbody>


                </table>


            </div> 
            <?php
        }
    }

    public function sandmailtest() {
        $this->load->library('email');
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => '465',
            'smtp_user' => 'rahul@virtualheight.com',
            'smtp_pass' => 'web30india#',
            'mailtype' => 'html',
            'starttls' => true,
            'newline' => "\r\n"
        );

        $this->email->initialize($config);
        $message = "test test";
        $this->email->to('rahul@virtualheight.com');

        $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
        $this->email->subject('Po Generate Approval');
        $this->email->message($message);
        $this->email->send();

        echo $this->email->print_debugger();

        echo "ok";
    }

    function pomailconfirm() {
        $data["login_data"] = logindata();
        $login_id = 50;
        $type = 2;
        $data["user"] = $this->user_model->getUser($login_id);
        $data["logintype"] = $type;
        $id = $this->input->get_post("id");
        if ($id != "") {
            $data['query'] = $this->Intent_model->get_val("SELECT p.id,p.`creteddate`,p.`discount`,p.`branchfk`,p.`poprice`,p.`indentcode`,p.`status`,p.remark,b.`branch_name`,v.vendor_name,a.`name` AS cretdby FROM inventory_pogenrate p LEFT JOIN `branch_master` b ON b.`id`=p.`branchfk` LEFT JOIN inventory_vendor v ON v.`id`=p.`vendorid` LEFT JOIN admin_master a ON a.`id`=p.`cretedby` WHERE p.`status` !='0' and p.id='$id'");
            if ($data['query'] != null) {
                $data['sub_data'] = $this->Intent_model->get_val("SELECT * from inventory_inward_master  WHERE status='1' and po_fk='" . $id . "'");
                $this->load->library('form_validation');
                $this->form_validation->set_rules('bank', 'bank', 'trim|required');
                $this->form_validation->set_rules('neft', 'neft', 'trim|required');
                $this->form_validation->set_rules('date', 'date', 'trim|required');
                $this->form_validation->set_rules('note', 'note', 'trim');
                if ($this->form_validation->run() != FALSE) {
                    $bank = $this->input->post('bank');
                    $neft = $this->input->post('neft');
                    $date = $this->input->post('date');
                    $note = $this->input->post('note');
                    $new_date = explode('/', $date);
                    $date1 = $new_date[2] . "-" . $new_date[1] . "-" . $new_date[0];
                    $this->Intent_model->master_fun_insert('inventory_popayment', array("poid" => $id, "banckid" => $bank, "neft" => $neft, "paydate" => date("Y-m-d", strtotime($date1)), "remark" => $note, "creteddate" => date("Y-m-d H:i:s"), "cretedbby" => $login_id));
                    $this->session->set_flashdata("success", "Successfully Po add Payment.");
                    redirect("inventory/intent_genrate/poigeneratedetils/" . $id, "refresh");
                } else {
                    $data['poitenm'] = $this->Intent_model->get_val("SELECT p.*,i.reagent_name,i.category_fk FROM inventory_poitem p LEFT JOIN inventory_item i ON i.`id`=p.`itemid` WHERE p.status='1' and p.poid='$id'");
                    $data['bankall'] = $this->Intent_model->get_val("SELECT * from inventory_bank where status='1' ");
                    $data['banmpay'] = $this->Intent_model->get_val("SELECT p.id,p.banckid,p.neft,p.paydate,p.remark,b.bank_name from inventory_popayment p left join inventory_bank b on b.id=p.banckid where p.status='1' and p.poid='$id'");
                    /* echo "<pre>";
                      print_r($data['poitenm']); die(); */
                    //$this->load->view('inventory/header', $data);
                    $this->load->view('inventory/poMailDetails', $data);
                    //$this->load->view('footer');
                }
            } else {
                echo "Oops! Invalid request, Try again.";
            }
        } else {
            echo "Oops! Invalid request, Try again.";
        }
    }

    public function mail_poconfirm() {
        $login_id = 50;
        $type = 2;
        $id = $this->input->get('id');

        $gepo = $this->Intent_model->get_val("select id,vendorid,discount,ponumber from inventory_pogenrate where  status !='0' and id='$id'");

        if ($id != "" && $gepo != null) {
            if ($type == 1 || $type == 2 || $type == 8) {

                $this->Intent_model->master_fun_update('inventory_pogenrate', $id, array("i_notiy" => '1', "b_notity" => '1', "updatedby" => $login_id, "updateddate" => date("Y-m-d H:i:s"), "status" => 1));
                $vendorid = $gepo[0]["vendorid"];
                $ponumber = $gepo[0]["ponumber"];
                $getvendor = $this->Intent_model->get_val("select vendor_name,mobile,email_id from inventory_vendor where  status ='1' and id='$vendorid'");

                if ($getvendor != null) {

                    $poitenm = $this->Intent_model->get_val("SELECT p.*,i.reagent_name FROM inventory_poitem p LEFT JOIN inventory_item i ON i.`id`=p.`itemid` WHERE p.status='1' and p.poid='$id'");

                    $data['poitenm'] = $this->Intent_model->get_val("SELECT p.*,i.reagent_name,i.category_fk FROM inventory_poitem p LEFT JOIN inventory_item i ON i.`id`=p.`itemid` WHERE p.status='1' and p.poid='$id'");

                    $poitenm = $data['poitenm'];

                    $data["query"] = $this->Intent_model->get_val("SELECT p.id,p.`creteddate`,p.updateddate,p.`discount`,p.`poprice`,p.`indentcode`,p.`status`,p.remark,b.`branch_name`,p.ponumber,b.address as baddress,v.vendor_name,v.address FROM inventory_pogenrate p LEFT JOIN `branch_master` b ON b.`id`=p.`branchfk` LEFT JOIN inventory_vendor v ON v.`id`=p.`vendorid` WHERE p.`status` ='1' and p.id='$id'");

                    $pdfFilePath = FCPATH . "/upload/expense_master/invoice_$id.pdf";
                    //print_r($data);
                    $html = $this->load->view('inventory/invoice_pdf_view', $data, true);

                    $this->load->library('pdf');
                    $pdf = $this->pdf->load();
                    $pdf->autoScriptToLang = true;
                    $pdf->baseScript = 1;
                    $pdf->autoVietnamese = true;
                    $pdf->autoArabic = true;
                    $pdf->autoLangToFont = true;

                    $pdf->AddPage('p', // L - landscape, P - portrait
                            '', '', '', '', 0, // margin_left
                            0, // margin right
                            5, // margin top
                            5, // margin bottom
                            5, // margin header
                            5);
                    $pdf->SetHTMLFooter('<table class="tbl_full" style="margin-bottom:20px; text-align:right;">
					<tr>
						<td><b>AIRRMED PATHOLOGY PVT.LTD.</b></td>
					</tr>
				</table>

<div style="height:20px;"><p class="rslt_p_brdr"></p>
</div>');

                    $pdf->WriteHTML($html);
                    $pdf->debug = true;
                    $pdf->allow_output_buffering = TRUE;
                    if (file_exists($pdfFilePath) == true) {
                        $this->load->helper('file');
                        unlink($pdfFilePath);
                    }
                    $pdf->Output($pdfFilePath, 'F');


                    $base_url1 = $pdfFilePath; // base_url()."upload/expense_master/invoice_$id.pdf";


                    $this->load->library('email');
                    $config['mailtype'] = 'html';

                    $this->email->initialize($config);
                    $message = "";
                    $message .= "
                    <h4><b>Po Generated - No : $ponumber</b></h4>
                Dear Sir, <br>
Kindly Check the attached PO and send us all material as per PO on urgent Basis.

<br>

With Regards,
Airmed Labs                ";


                    $this->email->to($getvendor[0]["email_id"]);
                    $this->email->cc('hiten.chauhan@airmedlabs.com');
                    $this->email->cc('accounts@airmedlabs.com');
                    $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                    $this->email->subject("Po Generated : $ponumber");
                    $this->email->message($message);
                    $this->email->attach($base_url1);
                    $this->email->send();
                }


                $this->session->set_flashdata("success", array("Successfully Po Generate."));
                echo "PO successfully approved.";
            } else {
                show_404();
            }
        } else {

            show_404();
        }
    }

    public function cancelpo_approve($id) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];

        $gepo = $this->Intent_model->get_val("select id,vendorid,discount,ponumber from inventory_pogenrate where  status !='0' and id='$id'");
        $vendorid = $gepo[0]["vendorid"];
        $ponumber = $gepo[0]["ponumber"];

        $getvendor = $this->Intent_model->get_val("select vendor_name,mobile,email_id from inventory_vendor where  status ='1' and id='$vendorid'");

        if ($type == 1 || $type == 2) {

            $poitenm = $this->Intent_model->get_val("SELECT p.*,i.reagent_name FROM inventory_poitem p LEFT JOIN inventory_item i ON i.`id`=p.`itemid` WHERE p.status='1' and p.poid='$id'");
            $data['poitenm'] = $this->Intent_model->get_val("SELECT p.*,i.reagent_name,i.category_fk FROM inventory_poitem p LEFT JOIN inventory_item i ON i.`id`=p.`itemid` WHERE p.status='1' and p.poid='$id'");
            $poitenm = $data['poitenm'];
            $data["query"] = $this->Intent_model->get_val("SELECT p.id,p.`creteddate`,p.updateddate,p.`discount`,p.`poprice`,p.`indentcode`,p.`status`,p.remark,b.`branch_name`,p.ponumber,b.address as baddress,v.vendor_name,v.address,v.email_id FROM inventory_pogenrate p LEFT JOIN `branch_master` b ON b.`id`=p.`branchfk` LEFT JOIN inventory_vendor v ON v.`id`=p.`vendorid` WHERE p.`status` ='1' and p.id='$id'");

            $email = $data["query"][0]["email_id"];
            //$email = "bhavik@virtualheight.com";
            if ($getvendor != null) {

                $pdfFilePath = FCPATH . "/upload/expense_master/invoice_$id.pdf";
                $html = $this->load->view('inventory/invoice_pdf_view', $data, true);

                $this->load->library('pdf');
                $pdf = $this->pdf->load();
                $pdf->autoScriptToLang = true;
                $pdf->baseScript = 1;
                $pdf->autoVietnamese = true;
                $pdf->autoArabic = true;
                $pdf->autoLangToFont = true;

                $pdf->AddPage('p', // L - landscape, P - portrait
                        '', '', '', '', 0, // margin_left
                        0, // margin right
                        5, // margin top
                        5, // margin bottom
                        5, // margin header
                        5);
                $pdf->SetHTMLFooter('<table class="tbl_full" style="margin-bottom:20px; text-align:right;">
					<tr>
						<td><b>AIRRMED PATHOLOGY PVT.LTD.</b></td>
					</tr>
				</table>

<div style="height:20px;"><p class="rslt_p_brdr"></p>
</div>');

                $pdf->WriteHTML($html);
                $pdf->debug = true;
                $pdf->allow_output_buffering = TRUE;
                if (file_exists($pdfFilePath) == true) {
                    $this->load->helper('file');
                    unlink($pdfFilePath);
                }
                $pdf->Output($pdfFilePath, 'F');

                $base_url1 = $pdfFilePath;




                $this->load->library('email');
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $message = "";
                $message .= "
                    <h4><b>Po Cancel - No : $ponumber</b></h4>
                Dear Sir, <br>
Sorry to inform you that your PO $ponumber is canceled.

Please cancel the order</a>.

<br>

With Regards,<br/>
Airmed Labs";

                $this->email->to($email);
                $this->email->cc('nishit@virtualheight.com');
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Po Cancel');
                $this->email->message($message);
                $this->email->attach($base_url1);
                $this->email->send();
                $this->Intent_model->master_fun_update('inventory_pogenrate', $id, array("status" => 4));
                $this->session->set_flashdata("success", array("Successfully Po Canceled."));
                redirect("inventory/intent_genrate/index", "refresh");
            }
        } else {
            show_404();
        }
    }

    function get_po_details() {
        if (!is_vendorlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_vendorlogin();

        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $poid = $this->input->get('id');
        $po_details = $this->Intent_model->get_val("SELECT id FROM `inventory_pogenrate` WHERE  ponumber='" . $this->input->get('id') . "'");
        $poid = $po_details[0]["id"];
        $user_branch = $this->Intent_model->get_val("SELECT id FROM `inventory_inward_master` WHERE `status`='1' AND po_fk='" . $poid . "'");
        $user_branch1 = $this->Intent_model->get_val("SELECT inventory_pogenrate.id,inventory_inward_master.`id` AS pid FROM `inventory_pogenrate` LEFT JOIN `inventory_inward_master` ON `inventory_inward_master`.`po_fk`=`inventory_pogenrate`.id AND inventory_inward_master.`status`='1'  WHERE (inventory_inward_master.`id` IS NULL OR inventory_inward_master.`id`='') AND inventory_pogenrate.`status`='1'");
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
            $user_branch = $this->Intent_model->get_val("SELECT GROUP_CONCAT(branch_fk) AS ids FROM `user_branch` WHERE `status`='1' GROUP BY `status`");
        } else {
            $user_branch = $this->Intent_model->get_val("SELECT GROUP_CONCAT(branch_fk) AS ids FROM `user_branch` WHERE `status`='1' AND `user_fk`='" . $data["login_data"]['id'] . "' GROUP BY `status`");
        }
        /* END */
        $edit_item_details = $this->Intent_model->get_val("SELECT 
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

//        echo "<pre>"; print_r($edit_item_details); exit;
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
        $item_statonary = $this->Intent_model->get_val("select id,reagent_name from inventory_item where status='1' and category_fk=1");
        $item_Consumables = $this->Intent_model->get_val("select id,reagent_name from inventory_item where status='1' and category_fk=2");
        // $item_details = $this->Intent_model->get_val("select id,reagent_name from inventory_item where status='1' and category_fk=3 and machine IS NOT NULL and machine !=0");
        if (!in_array($data["login_data"]['type'], array("1", "2", "8"))) {
            $machine_array = $this->Intent_model->get_val('select imb.*,im.name as MachineName from inventory_machine_branch as imb  INNER JOIN inventory_machine as im on im.id = imb.machine_fk where imb.branch_fk in (' . $user_branch[0]["ids"] . ') and imb.status="1" and im.status="1"');
        } else {
            $machine_array = $this->Intent_model->get_val('select imb.*,im.name as MachineName from inventory_machine_branch as imb  INNER JOIN inventory_machine as im on im.id = imb.machine_fk where imb.status="1" and im.status="1"');
        }

        $data['reagent_array'] = array();
        $new_array1 = array();
        if (!in_array($data["login_data"]['type'], array("1", "2", "8"))) {
            $get_branch_machine = $this->Intent_model->get_val("SELECT GROUP_CONCAT(DISTINCT machine_fk) AS `mid` FROM `inventory_machine_branch` WHERE `status`='1' AND `branch_fk` IN (" . $user_branch[0]["ids"] . ") GROUP BY `status`");
        } else {
            $get_branch_machine = $this->Intent_model->get_val("SELECT GROUP_CONCAT(DISTINCT machine_fk) AS `mid` FROM `inventory_machine_branch` WHERE `status`='1' GROUP BY `status`");
        }
        $mrdata = $this->Intent_model->get_val("SELECT inventory_item.id as REAGENTID,inventory_item.reagent_name as REAGENT from inventory_item where machine IN(" . $get_branch_machine[0]["mid"] . ") and status='1'");

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
                    <td style="width:20%;font:12px;"><input type="text" name="batch_no[]" value="" class="form-control"/></td>
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

    function vendor_bill_detail() {

        if (!is_vendorlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_vendorlogin();

        $id = $this->uri->segment(4);
        $data['bill_data'] = $this->Intent_model->get_val("select * from inventory_vendor_bil_details where status ='1' AND po_fk = '$id'");

        $this->load->view('vendor/header', $data);
        $this->load->view('vendor/nav', $data);
        $this->load->view('vendor/vendor_bill_details_view', $data);
        $this->load->view('footer');
    }

    function quotation_list() {
        if (!is_vendorlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_vendorlogin();

        $login_id = $data["login_data"]['id'];

        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        $data['error'] = $this->session->flashdata("error");

        $indent_code = $this->input->get_post("indent_code");
        $start_date = $this->input->get_post("start_date");
        $end_date = $this->input->get_post("end_date");
        $status = $this->input->get_post("status");
        $ponumber = $this->input->get_post("ponumber");

        $config = array();
        $config["per_page"] = 100;
        $config["uri_segment"] = 4;
        $config['next_link'] = 'Next &rsaquo;';
        $config['prev_link'] = '&lsaquo; Previous';
        $config['page_query_string'] = TRUE;
        $config["base_url"] = base_url() . "inventory/Intent_genrate";

        $temp = '';

//        if ($indent_code != '') {
//            $temp .= ' AND p.`indentcode` ="' . $indent_code . '"';
//        }
        if ($ponumber != '') {
            $temp .= ' AND p.`ponumber` ="' . $ponumber . '"';
        }
        if ($start_date != '') {
            $old = explode("/", $start_date);
            $new_date = $old[2] . '-' . $old[1] . '-' . $old[0];

            $temp .= "AND STR_TO_DATE(q.created_date,'%Y-%m-%d') >=  '" . date("Y-m-d", strtotime($new_date)) . "'";
        }
        if ($end_date != '') {
            $old_sub = explode("/", $end_date);
            $sub_new_date = $old_sub[2] . '-' . $old_sub[1] . '-' . $old_sub[0];
            $temp .= "AND STR_TO_DATE(q.created_date,'%Y-%m-%d') <=  '" . date("Y-m-d", strtotime($sub_new_date)) . "'";
        }

        //$temp .= ' AND p.`status` ="1"';
        $checksatus = "  AND (SELECT COUNT(`inventory_popayment`.id) 
                          FROM inventory_popayment 
                          WHERE inventory_popayment.`status`='1' 
                          AND inventory_popayment.poid=p.id)='0'";

        $totalRows = $this->Intent_model->get_val("select count(q.id) as ID from inventory_quotation q 
		LEFT JOIN inventory_pogenrate p ON p.id = q.po_fk 
		where q.status ='1'  AND p.vendorid = '$login_id'");




        $config["total_rows"] = $totalRows[0]["ID"];
        $this->pagination->initialize($config);
        $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;

        $data['query'] = $this->Intent_model->get_val("select q.id,p.ponumber,b.branch_name,am.name as admin_name,q.created_date,q.vendor_approve 
		from inventory_quotation q 
		LEFT JOIN inventory_pogenrate p ON p.id = q.po_fk 
		LEFT JOIN branch_master b ON b.id = p.branchfk AND b.status ='1' 
		LEFT JOIN admin_master am ON am.id = q.created_by AND am.status ='1'  
		where q.status ='1' $temp AND p.vendorid = '$login_id' order by q.id desc limit $page," . $config["per_page"] . "");
//echo $this->db->last_query();
//exit;

        //echo "<pre>"; print_R($data['query']); exit;

        $data["links"] = $this->pagination->create_links();
        $data["counts"] = $page;

        $this->load->view('vendor/header', $data);
        $this->load->view('vendor/nav', $data);
        $this->load->view('vendor/qoutation_list_view', $data);
        $this->load->view('footer');
    }

    function view_qoutation_list() {
        if (!is_vendorlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_vendorlogin();

        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $this->input->get('id');
        //echo $id; exit; 

        $data['po_list'] = $this->Intent_model->get_val("SELECT * FROM inventory_quotation WHERE status='1' AND id='$id'");

        $data['item_list'] = $this->Intent_model->get_val("SELECT 
            q.id,q.quotation_id,q.item,q.amount,q.tax,q.discount,i.reagent_name,q.qty,q.price,q.itemnos  
            FROM inventory_quotation_details q 
            INNER JOIN inventory_item i on i.id = q.item AND i.status='1' 
            WHERE q.status='1' AND q.quotation_id='" . $data['po_list'][0]['id'] . "'");

        //echo "<pre>"; print_R($data['item_list']); exit;
        ?>


        <div class="table-responsive pending_job_list_tbl">
            <table class="table-striped" id="po_list">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Item</th>
                        <th>Qty.</th>
                        <th>Price</th>
                        <th>NOS</th>
                        <th>TAX</th>
                        <th>Discount(%)</th>
                        <th>Amount Rs.</th>
                    </tr>
                </thead>
                <tbody id="selected_items">
                    <?php
                    $count = 0;
                    foreach ($data['item_list'] as $item) {
                        $count++;
                        ?>
                        <tr>
                            <td><?php echo $count ?></td>
                            <td>
                                <input type="hidden" name="id[]" value="<?php echo $item['id'] ?>"/>
                                <input type="hidden" name="item[]" value="<?php echo $item['item'] ?>"/>
                                <?php echo $item['reagent_name'] ?>
                            </td>
                            <td>
                                <input type="text" value="<?php echo $item['qty'] ?>" name="qty[]" id="qty_<?php echo $count ?>"  readonly class="form-control calution">
                            </td>
                            <td>
                                <input type="text" value="<?php echo $item['price'] ?>" name="price[]" id="price_<?php echo $count ?>" class="form-control calution">
                                <span id="priceerror_<?= $count ?>" class="errorall" style="color:red"></span>
                            </td>
                            <td>
                                <input type="text" value="<?php echo $item['itemnos'] ?>" name="itemnos[]" id="itemnos_<?php echo $count ?>" readonly class="form-control calution">
                            </td>
                            <td>
                                <input type="text" id="tax_<?php echo $count ?>" name="quotation_tax[]" value="<?php echo $item['tax'] ?>" class="form-control calution">
                                <span id="taxerror_<?= $count ?>" class="errorall" style="color:red"></span>
                            </td>
                            <td>
                                <input type="text" value="<?php echo $item['discount'] ?>" name="discount[]" id="discount_<?php echo $count ?>" class="form-control calution">
                                <span id="discounterror_<?= $count ?>" class="errorall" style="color:red"></span>
                            </td>
                            <td>
                                <input type="text" value="<?php echo $item['amount'] ?>" name="total_price[]" id="total_price_<?php echo $count ?>"  readonly class="form-control calution">
                            </td>
                        </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
        </div>

        <?php
    }

    function update_quote() {
        if (!is_vendorlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_vendorlogin();

        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

//        echo "<pre>";
//        print_r($_POST);
//        exit;


        $poid = $this->input->post('purch_or_id1');
        $id = $this->input->post('id');

        //echo "<pre>"; print_r($id); exit;
        $item = $this->input->post('item');
        $price = $this->input->post('price');
        $quotation_tax = $this->input->post('quotation_tax');
        $discount = $this->input->post('discount');
        $total_price = $this->input->post('total_price');



        $qot = $this->Intent_model->master_fun_update('inventory_quotation', $poid, array("vendor_approve" => '1'));

        for ($i = 0; $i < count($id); $i++) {

            $qot_detail = $this->Intent_model->master_fun_update('inventory_quotation_details', $id[$i], array(
                "price" => $price[$i],
                "amount" => $total_price[$i],
                "tax" => $quotation_tax[$i],
                "discount" => $discount[$i]));
        }

        $this->session->set_flashdata("success", array("Quotation has update and send successfully."));
        redirect('vendor/Intent_genrate/quotation_list', 'refresh');
    }

    function view_qot_details() {
        if (!is_vendorlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_vendorlogin();

        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $id = $this->input->get('id');

        $data['po_list'] = $this->Intent_model->get_val("SELECT * FROM inventory_quotation WHERE status='1' AND id='$id'");

        $data['item_list'] = $this->Intent_model->get_val("SELECT 
            q.id,q.quotation_id,q.item,q.amount,q.tax,q.discount,i.reagent_name,q.qty,q.price,q.price_old,
            q.amount_old,q.tax_old,q.discount_old,q.itemnos  
            FROM inventory_quotation_details q 
            INNER JOIN inventory_item i on i.id = q.item AND i.status='1' 
            WHERE q.status='1' AND q.quotation_id='" . $data['po_list'][0]['id'] . "'");


        $this->load->view('vendor/header', $data);
        $this->load->view('vendor/nav', $data);
        $this->load->view('vendor/qoutation_details', $data);
        $this->load->view('footer');
    }

}
