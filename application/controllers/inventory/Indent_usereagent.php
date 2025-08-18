<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Indent_usereagent extends CI_Controller {

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
        $start_date = $this->input->get_post("start_date");
        $end_date = $this->input->get_post("end_date");
        $batchno = $this->input->get_post("batchno");
        $machine = $this->input->get_post("machine");
        $reagent = $this->input->get_post("reagent");

        $temp = '';
        if ($branch != '') {
            $temp .= ' AND i.`branchfk` ="' . $branch . '"';
        }
        if ($batchno != '') {
            $temp .= ' AND i.indedfk ="' . $batchno . '"';
        }
        if ($machine != '') {
            $temp .= ' AND i.machinefk ="' . $machine . '"';
        }
        if ($reagent != '') {
            $temp .= ' AND i.reaqgentfk ="' . $reagent . '"';
        }
        if ($reagent != '') {
            $temp .= ' AND i.reaqgentfk ="' . $reagent . '"';
        }

        if ($start_date != '') {

            $temp .= "AND STR_TO_DATE(i.creteddate,'%Y-%m-%d') >=  '" . date("Y-m-d", strtotime($start_date)) . "'";
        }
        if ($end_date != '') {

            $temp .= "AND STR_TO_DATE(i.creteddate,'%Y-%m-%d') <=  '" . date("Y-m-d", strtotime($end_date)) . "'";
        }

        $config = array();
        $config["per_page"] = 100;
        $config["uri_segment"] = 4;
        $config['next_link'] = 'Next &rsaquo;';
        $config['prev_link'] = '&lsaquo; Previous';
        $config['page_query_string'] = TRUE;
        $config["base_url"] = base_url() . "inventory/Intent_genrate";

        if ($type == 1 || $type == 2 || $type == 8) {

            $data['branch_list'] = $this->Intent_model->get_val("select br.id as BranchId ,br.branch_name as BranchName  from branch_master as br where br.status='1'");

            $totalRows = $this->Intent_model->get_val("SELECT count(i.id) from inventory_usedreagent i where i.status='1' $temp ");
            $config["total_rows"] = $totalRows[0]["ID"];
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;

            $data["query"] = $this->Intent_model->get_val("SELECT i.id,b.`branch_name`,m.`name` AS machinname,r.reagent_name,s.`batch_no`,i.`quantity`,a.`name` adminname,s.`used`,i.`creteddate`,IF(r.category_fk = 3,r.test_quantity,r.quantity) as packet_quantity FROM `inventory_usedreagent` i LEFT JOIN `branch_master` b ON b.`id`=i.`branchfk` LEFT JOIN inventory_machine m ON m.`id`=i.`machinefk` LEFT JOIN `inventory_item` r ON r.`id`=i.`reaqgentfk` LEFT JOIN `inventory_stock_master` s ON s.`id`=i.`indedfk`LEFT JOIN `admin_master` a ON a.`id`=i.`credtedby` WHERE i.status='1' and r.category_fk='3' $temp order by i.id desc limit $page," . $config["per_page"] . "");
        } else {

            $totalRows = $this->Intent_model->get_val("SELECT count(i.id) from inventory_usedreagent i where i.status='1' $temp And i.branchfk in(SELECT `branch_fk`  FROM `user_branch`  WHERE status= '1' AND user_fk = " . $login_id . ") ");

            $data['branch_list'] = $this->Intent_model->get_val('select user_branch.branch_fk as BranchId, branch_master.id,branch_master.branch_name as BranchName from user_branch left join branch_master on branch_master.id = user_branch.branch_fk where user_branch.status="1" and user_branch.user_fk="' . $login_id . '"');


            $config["total_rows"] = $totalRows[0]["ID"];
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;

            $data["query"] = $this->Intent_model->get_val("SELECT i.id,b.`branch_name`,m.`name` AS machinname,r.reagent_name,s.`batch_no`,i.`quantity`,a.`name` adminname,s.used,i.`creteddate`,IF(r.category_fk = 3,r.test_quantity,r.quantity) as packet_quantity FROM `inventory_usedreagent` i LEFT JOIN `branch_master` b ON b.`id`=i.`branchfk` LEFT JOIN inventory_machine m ON m.`id`=i.`machinefk` LEFT JOIN `inventory_item` r ON r.`id`=i.`reaqgentfk` LEFT JOIN `inventory_stock_master` s ON s.`id`=i.`indedfk`LEFT JOIN `admin_master` a ON a.`id`=i.`credtedby` WHERE i.status='1' and r.category_fk='3' $temp And i.branchfk in(SELECT `branch_fk`  FROM `user_branch`  WHERE status= '1' AND user_fk = " . $login_id . ") order by i.id desc limit $page," . $config["per_page"] . "");
        }
        $data["links"] = $this->pagination->create_links();
        $data["counts"] = $page;

        $data["new_data"] = array();

        foreach ($data["query"] as $kkeey) {
            $query = $this->Intent_model->get_val("SELECT s.id,s.`creteddate`,s.`useditem`,s.`timeperfomace`,j.order_id,c.`full_name`,t.`test_name` FROM `inventory_jobstock` s LEFT JOIN `job_master` j ON j.`id`=s.`jobid` LEFT JOIN `customer_master` c ON c.id=j.cust_fk LEFT JOIN test_master t ON t.id=s.`testid` WHERE s.`status`='1' and s.usedreagent_fk='" . $kkeey["id"] . "' ORDER BY s.`id` ASC");
            $kkeey["old_record"] = $query;
            $data["new_data"][] = $kkeey;
        }
        //echo "<pre>";print_R($data["new_data"]); die();
        if ($type == 8) {
            $this->load->view('inventory/header', $data);
            $this->load->view('inventory/nav', $data);
        } else {
            $this->load->view('header', $data);
            $this->load->view('nav', $data);
        }
        $this->load->view('inventory/indentusereagent_views', $data);
        $this->load->view('footer');
    }

    public function indedstocksremove($id) {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];

        $geuserinve = $this->Intent_model->get_val("SELECT id,indedfk,quantity FROM `inventory_usedreagent` WHERE id='$id' AND status='1'");
        if ($geuserinve != null) {

            $indenid = $geuserinve[0]["indedfk"];
            $quantity = $geuserinve[0]["quantity"];
            $getstock = $this->Intent_model->get_val("SELECT used FROM `inventory_stock_master` WHERE id='$indenid' ");
            $usedstock = $getstock[0]["used"];
            $totalstock = ($usedstock - $quantity);

            $this->Intent_model->master_fun_update('inventory_usedreagent', $id, array("status" => 0));
            $this->Intent_model->master_fun_update('inventory_stock_master', $indenid, array("used" => $totalstock));
            $this->session->set_flashdata("success", array("Successfully deleted inward use reagent ."));
            redirect("inventory/indent_usereagent", "refresh");
        } else {
            show_404();
        }
    }

    public function poexportcsv() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        $branch = $this->input->get_post("branch");
        $start_date = $this->input->get_post("start_date");
        $end_date = $this->input->get_post("end_date");
        $batchno = $this->input->get_post("batchno");
        $machine = $this->input->get_post("machine");
        $reagent = $this->input->get_post("reagent");

        $temp = '';
        if ($branch != '') {
            $temp .= ' AND i.`branchfk` ="' . $branch . '"';
        }
        if ($batchno != '') {
            $temp .= ' AND i.indedfk ="' . $batchno . '"';
        }
        if ($machine != '') {
            $temp .= ' AND i.machinefk ="' . $machine . '"';
        }
        if ($reagent != '') {
            $temp .= ' AND i.reaqgentfk ="' . $reagent . '"';
        }
        if ($reagent != '') {
            $temp .= ' AND i.reaqgentfk ="' . $reagent . '"';
        }

        if ($start_date != '') {

            $temp .= "AND STR_TO_DATE(i.creteddate,'%Y-%m-%d') >=  '" . date("Y-m-d", strtotime($start_date)) . "'";
        }
        if ($end_date != '') {

            $temp .= "AND STR_TO_DATE(i.creteddate,'%Y-%m-%d') <=  '" . date("Y-m-d", strtotime($end_date)) . "'";
        }


        if ($type == 1 || $type == 2 || $type == 8) {


            $data["query"] = $this->Intent_model->get_val("SELECT i.id,b.`branch_name`,m.`name` AS machinname,r.reagent_name,s.`batch_no`,i.`quantity`,a.`name` adminname,s.`used`,i.`creteddate` FROM `inventory_usedreagent` i LEFT JOIN `branch_master` b ON b.`id`=i.`branchfk` LEFT JOIN inventory_machine m ON m.`id`=i.`machinefk` LEFT JOIN `inventory_item` r ON r.`id`=i.`reaqgentfk` LEFT JOIN `inventory_stock_master` s ON s.`id`=i.`indedfk`LEFT JOIN `admin_master` a ON a.`id`=i.`credtedby` WHERE i.status='1' $temp order by i.id desc");
        } else {


            $data["query"] = $this->Intent_model->get_val("SELECT i.id,b.`branch_name`,m.`name` AS machinname,r.reagent_name,s.`batch_no`,i.`quantity`,a.`name` adminname,s.used,i.`creteddate` FROM `inventory_usedreagent` i LEFT JOIN `branch_master` b ON b.`id`=i.`branchfk` LEFT JOIN inventory_machine m ON m.`id`=i.`machinefk` LEFT JOIN `inventory_item` r ON r.`id`=i.`reaqgentfk` LEFT JOIN `inventory_stock_master` s ON s.`id`=i.`indedfk`LEFT JOIN `admin_master` a ON a.`id`=i.`credtedby` WHERE i.status='1' $temp And i.branchfk in(SELECT `branch_fk`  FROM `user_branch`  WHERE status= '1' AND user_fk = " . $login_id . ") order by i.id desc");
        }

        $query = $data["query"];

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Useagentlist .csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No", "Branch Name", "Machine Name", "Reagent Name", "Batch no", "Quantity", "Added by", "Created Date"));
        $i = 0;

        foreach ($query as $key) {

            $i++;
            fputcsv($handle, array($i, $key["branch_name"], ucwords($key["machinname"]), $key["reagent_name"], $key["batch_no"], $key["quantity"], $key["adminname"], date("d-m-Y", strtotime($key["creteddate"]))));
        }

        fclose($handle);
        exit;
    }

    public function addusestock() {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('branch_fk', 'Branch Name', 'trim|required');
        $this->form_validation->set_rules('machine', 'machine', 'trim|required');
        $this->form_validation->set_rules('reagent', 'reagent', 'trim|required');
        $this->form_validation->set_rules('batchno', 'batchno', 'trim|required');
        $this->form_validation->set_rules('quantity', 'quantity', 'trim|required');

        if ($this->form_validation->run() != FALSE) {

            $branch_fk = $this->input->post('branch_fk');
            $machine = $this->input->post('machine');
            $reagent = $this->input->post('reagent');
            $batchno = $this->input->post('batchno');
            $quantity = $this->input->post('quantity');

            $getstocks = $this->Intent_model->get_val("SELECT id,batch_no,(`quantity`-`used`) AS used,used as used1 FROM `inventory_stock_master` WHERE id='$batchno' AND status='1'");
            $totalqty = $getstocks[0]["used"];
            $totalqty1 = $getstocks[0]["used1"];
            $getbatchno = $getstocks[0]["batch_no"];
            if ($totalqty < $quantity) {
                echo "0";
            } else {
                $data = array("branchfk" => $branch_fk, "machinefk" => $machine, "reaqgentfk" => $reagent, "batchnuno" => $getbatchno, "indedfk" => $batchno, "quantity" => $quantity, "credtedby" => $login_id, "creteddate" => date("Y-m-d H:i:s"));

                $this->Intent_model->master_fun_insert('inventory_usedreagent', $data);
                $totalstock = ($totalqty1 + $quantity);
                $this->Intent_model->master_fun_update('inventory_stock_master', $batchno, array("used" => $totalstock));
                $this->session->set_flashdata("success", array("Successfully add user reagent."));

                echo "1";
                die();
            }
        } else {

            if ($type == 1 || $type == 2 || $type == 8) {

                $data['branch'] = $this->Intent_model->get_val('select id,id as BranchId,branch_name as BranchName from  branch_master where status="1" ');
            } else {

                $data['branch'] = $this->Intent_model->get_val('select user_branch.branch_fk as BranchId, branch_master.id,branch_master.branch_name as BranchName from user_branch left join branch_master on branch_master.id = user_branch.branch_fk where user_branch.status="1" and user_branch.user_fk="' . $login_id . '"');
            }


            if ($type == 8) {
                $this->load->view('inventory/header', $data);
                $this->load->view('inventory/nav', $data);
            } else {
                $this->load->view('header', $data);
                $this->load->view('nav', $data);
            }
            $this->load->view('inventory/indedusedstocks_views', $data);
            $this->load->view('footer');
        }
    }

    function getbranchmachin() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];

        $id = $this->input->get_post('branch_fk');
        $machinid = $this->input->get_post('machinid');
        if ($id != "") {

                $query = $this->Intent_model->get_val("SELECT im.id,im.`name` FROM inventory_machine_branch AS iim INNER JOIN inventory_machine AS im ON im.`id`=iim.machine_fk and im.status='1' WHERE iim.branch_fk='$id' AND iim.status='1'");

            $reagent_array = '';
            $reagent_array .= '<option value="">Select Machine</option>';
            if (!empty($query)) {
                foreach ($query as $val) {
                    if ($machinid == $val['id']) {
                        $setselect = "selected";
                    } else {
                        $setselect = "";
                    }
                    $reagent_array .= '<option value="' . $val['id'] . '" ' . $setselect . ' >' . ucwords($val['name']) . '</option>';
                }
            }
            echo $reagent_array;
        }
    }

    function getmachinagent() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];

        $id = $this->input->get_post('machin');
        $reagent = $this->input->get_post('reagent');
        if ($id != "") {

            $query = $this->Intent_model->get_val("SELECT id,reagent_name FROM inventory_item  WHERE machine='$id' and category_fk='3' and status='1'");

            $reagent_array = '';
            $reagent_array .= '<option value="">Select Reagent</option>';
            if (!empty($query)) {
                foreach ($query as $val) {
                    /*$getpoagent = $this->Intent_model->get_val("SELECT COUNT(id) AS totalagent FROM `inventory_poitem` WHERE status='1' AND itemid='" . $val['id'] . "'");*/
                    $getpoagent = $this->Intent_model->get_val("SELECT `inventory_stock_master`.* FROM `inventory_stock_master` INNER JOIN `inventory_inward_master` ON `inventory_inward_master`.`id`=`inventory_stock_master`.`inward_fk` INNER JOIN `inventory_item` ON `inventory_item`.`id`=`inventory_stock_master`.`item_fk` WHERE inventory_stock_master.`status`='1' AND `inventory_inward_master`.`status`='1' AND `inventory_item`.id='" . $val['id'] . "' AND inventory_item.`status`='1' AND inventory_item.`category_fk`='3' AND `inventory_stock_master`.`quantity`!=`inventory_stock_master`.`used` AND `inventory_stock_master`.`quantity`>`inventory_stock_master`.`used`");
                    /* echo $this->db->last_query()."<br>"; */
                    if (!empty($getpoagent)) {

                        if ($reagent == $val['id']) {
                            $setselect = "selected";
                        } else {
                            $setselect = "";
                        }

                        $reagent_array .= '<option value="' . $val['id'] . '" ' . $setselect . '>' . ucwords($val['reagent_name']) . '</option>';
                    }
                }
            }
            echo $reagent_array;
        }
    }

    function getreagentbanch() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $id = $this->input->get_post('reagent');
        $batchno = $this->input->get_post('batchno');

        if ($id != "") { 

            /* $query = $this->Intent_model->get_val("SELECT id,batch_no,(quantity - used) as used FROM `inventory_stock_master` WHERE item_fk='$id' AND status='1' and quantity >0"); */
			
			$query = $this->Intent_model->get_val("SELECT id,batch_no, SUM(quantity) - SUM(used) as used FROM `inventory_stock_master` WHERE item_fk='$id' AND status='1' AND quantity >0 group by batch_no");
			

            $reagent_array = '';
            $reagent_array .= '<option getstock="" value="">Select Batch no</option>';
            if (!empty($query)) {
                foreach ($query as $val) {
                    if ($batchno == $val['id']) {
                        $setselect = "selected";
                    } else {
                        $setselect = "";
                    }
                    $reagent_array .= '<option getstock="' . $val['used'] . '" value="' . $val['id'] . '" ' . $setselect . '>' . ucwords($val['batch_no']) . '</option>';
                }
            }
            echo $reagent_array;
        }
    }
 public function usdedstocks($id) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
       
if($id != ""){
	
        if ($type == 1 || $type == 2 || $type == 8) {
			
            $data["query"] = $this->Intent_model->get_val("SELECT s.id,s.`creteddate`,s.`useditem`,s.`timeperfomace`,j.order_id,c.`full_name`,t.`test_name` FROM `inventory_jobstock` s LEFT JOIN `job_master` j ON j.`id`=s.`jobid` LEFT JOIN `customer_master` c ON c.id=j.cust_fk LEFT JOIN test_master t ON t.id=s.`testid` WHERE s.`status`='1' and s.usedreagent_fk='$id' ORDER BY s.`id` ASC");
	
			
        } else {
			
$data["query"] = $this->Intent_model->get_val("SELECT s.id,s.`creteddate`,s.`useditem`,s.`timeperfomace`,j.order_id,c.`full_name`,t.`test_name` FROM `inventory_jobstock` s LEFT JOIN `job_master` j ON j.`id`=s.`jobid` LEFT JOIN `customer_master` c ON c.id=j.cust_fk LEFT JOIN test_master t ON t.id=s.`testid` WHERE s.`status`='1' And i.branchfk in(SELECT `branch_fk`  FROM `user_branch`  WHERE status= '1' AND user_fk = " . $login_id . ") and s.usedreagent_fk='$id' ORDER BY s.`id` ASC");
    
           
        }
	
        if ($type == 8) {
			
            $this->load->view('inventory/header', $data);
            $this->load->view('inventory/nav', $data);
        } else {
            $this->load->view('header', $data);
            $this->load->view('nav', $data);
        }
        $this->load->view('inventory/usereagentstocks_reagent_views', $data);
        $this->load->view('footer');
		
}else{
	show_404();
}
    }	

}
