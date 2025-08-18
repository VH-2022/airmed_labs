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
       // print_r($machine_fk);die;
        $data['machine'] = $this->Invert_model->get_val("select id,name as MachineName from inventory_machine where status='1'");
         $db = $this->Invert_model->get_val("SELECT intent_id as Intent FROM inventory_stock_master WHERE intent_id IS NOT NULL and status='1' ");
        
 if ($db == NULL) {
            $data['generate'] = '1';
        } else {
            $db1 = $this->Invert_model->get_val("SELECT max(intent_id) + 1 as Intent  FROM inventory_stock_master WHERE intent_id IS NOT NULL and status='1' ");
            
            $data['generate'] = $db1;
          
        }

        if ($branch_fk != '' || $machine_fk != '') {

            $totalRows = $this->Invert_model->intent_num($branch_fk, $machine_fk);
            $data['branch_fk'] = $branch_fk;
            $data['machine_fk'] = $machine_fk;
            $config = array();
            $config["base_url"] = base_url() . "Intent_Inward/inward_list?branch_fk=$branch_fk&machine_fk=$machine_fk";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous'; 

            $this->pagination->initialize($config);
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            $data["query"] = $this->Invert_model->intent_list($branch_fk, $machine_fk, $config["per_page"], $page);

            $new_mr_data = array();
$data['final_array'] = array();

            foreach($data['query'] as $val){  
                $machine_fk = $val['machine_fk'];

              $mdata = $this->Invert_model->get_val("SELECT 
   inventory_stock_master.*,
  
   GROUP_CONCAT(
     DISTINCT inventory_stock_master.quantity
   ) AS Quantity,
   GROUP_CONCAT(DISTINCT inventory_item.reagent_name) AS Reagent ,
    GROUP_CONCAT(DISTINCT inventory_category.name) AS Category_name,
    GROUP_CONCAT(DISTINCT inventory_category.id) AS CategoryID  
  
 FROM
   inventory_stock_master 
   INNER JOIN inventory_item 
     ON inventory_item.id = inventory_stock_master.item_fk
     INNER JOIN inventory_category ON inventory_category.`id` = inventory_item.category_fk
     WHERE inventory_stock_master.machine_fk='". $machine_fk."' AND inventory_stock_master.status='1' AND inventory_item.status='1' AND inventory_category.status='1' GROUP BY inventory_category.id  ");
             
                 $new_mr_data = array();
                foreach ($mdata as $key => $val_new) { 
                    $cate_id = $val_new['CategoryID'];
                      $intent_code = $val_new['machine_fk'];
                  
                     $mrdata = $this->Invert_model->get_val('SELECT 
   GROUP_CONCAT(DISTINCT iir.`quantity`) AS Quantity,
   iir.*,
   GROUP_CONCAT(DISTINCT ic.`id`) AS CategoryID,
   GROUP_CONCAT(DISTINCT ic.`name`) AS Category_Name,
   GROUP_CONCAT(DISTINCT it.reagent_name) AS Reagent_name 
 FROM
  `inventory_stock_master` AS iir 
   INNER JOIN inventory_item AS it 
     ON it.id = iir.item_fk 
  INNER JOIN inventory_category AS ic 
     ON ic.id = it.`category_fk` 
WHERE iir.status = "1" 
   AND ic.`id` IN ('.$cate_id.') 
   AND iir.machine_fk ="'.$intent_code.'"
   AND it.reagent_name != "" 
   AND it.reagent_name IS NOT NULL');

                     $mrkey["reagent"] = $mrdata;
                    $new_mr_data[] = $mrkey;
             }

                $val["details"] = $new_mr_data;

                $data["final_array"][] = $val;
                }       

     $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {
           $totalRows = $this->Invert_model->intent_num($branch_fk,$machine_fk);

            $data['branch_fk'] = $branch_fk;
            $data['machine_fk'] = $machine_fk;
           
            $config = array();
            $config["base_url"] = base_url() . "Intent_Inward/inward_list?branch_fk=$branch_fk&machine_fk=$machine_fk";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous'; 

            $this->pagination->initialize($config);
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            $data["query"] = $this->Invert_model->intent_list($branch_fk, $machine_fk, $config["per_page"], $page);

$new_mr_data = array();
$data['final_array'] = array();

            foreach($data['query'] as $val){  
                $machine_fk = $val['machine_fk'];
                 $intent_id = $val['intent_id'];

              $mdata = $this->Invert_model->get_val("SELECT 
   inventory_stock_master.*,
  
   GROUP_CONCAT(
     DISTINCT inventory_stock_master.quantity
   ) AS Quantity,
   GROUP_CONCAT(DISTINCT inventory_item.reagent_name) AS Reagent ,
    GROUP_CONCAT(DISTINCT inventory_category.name) AS Category_name,
    GROUP_CONCAT(DISTINCT inventory_category.id) AS CategoryID  
  
 FROM
   inventory_stock_master 
   INNER JOIN inventory_item 
     ON inventory_item.id = inventory_stock_master.item_fk
     INNER JOIN inventory_category ON inventory_category.`id` = inventory_item.category_fk
     WHERE inventory_stock_master.machine_fk='". $machine_fk."' AND inventory_stock_master.intent_id='". $intent_id."' AND inventory_stock_master.status='1' AND inventory_item.status='1' AND inventory_category.status='1'  GROUP BY inventory_category.id ");
             
                 $new_mr_data = array();
                foreach ($mdata as $key => $val_new) { 
                    $cate_id = $val_new['CategoryID'];
                      $intent_code = $val_new['machine_fk'];
                       $intent_id = $val_new['intent_id'];
                  
                     $mrdata = $this->Invert_model->get_val('SELECT 
   GROUP_CONCAT(DISTINCT iir.`quantity`) AS Quantity,
   iir.*,
   GROUP_CONCAT(DISTINCT ic.`id`) AS CategoryID,
   GROUP_CONCAT(DISTINCT ic.`name`) AS Category_Name,
   GROUP_CONCAT(DISTINCT it.reagent_name) AS Reagent_name 
 FROM
  `inventory_stock_master` AS iir 
   INNER JOIN inventory_item AS it 
     ON it.id = iir.item_fk 
  INNER JOIN inventory_category AS ic 
     ON ic.id = it.`category_fk` 
WHERE iir.status = "1" 
   AND ic.`id` IN ('.$cate_id.') 
   AND iir.machine_fk ="'.$intent_code.'"
   and iir.intent_id ="'.$intent_id.'"
   AND it.reagent_name != "" 
   AND it.reagent_name IS NOT NULL');

                     $mrkey["reagent"] = $mrdata;
                    $new_mr_data[] = $mrkey;
             }

                $val["details"] = $new_mr_data;

                $data["final_array"][] = $val;
                }       

     $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        //SHow Data From
        $data['stationary_list'] = $this->Invert_model->get_val("select * from inventory_item where category_fk='1' and status='1'");
        $data['lab_consum'] = $this->Invert_model->get_val("select * from inventory_item where category_fk='2' and status='1'");

        $data['category_list'] = $this->Invert_model->get_val("select * from inventory_item where category_fk='3' and status='1'");
        $data['branch_list'] = $this->Invert_model->get_val('select user_branch.branch_fk as BranchId, branch_master.id,branch_master.branch_name as BranchName from user_branch left join branch_master on branch_master.id = user_branch.branch_fk where user_branch.status="1" and user_branch.user_fk="' . $login_id . '"');

 //END  Data From
 if($type==8){
		$this->load->view('inventory/header', $data);
        $this->load->view('inventory/nav', $data);
		}else{ $this->load->view('header', $data); 
	
        $this->load->view('inventory/inward_list', $data);
        $this->load->view('footer');
    }

   

    function Invert_add() {
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
        $cnt = 0;
        foreach ($item_list as $kkey) {
          
      $expire_date = $this->input->post('expire_date')[$cnt];
        $old_date = explode("-",$expire_date);
       $new_date = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
            $data = array(
                "item_fk" => $kkey,
                "branch_fk" => $branch_fk,
                "machine_fk"=>$machine_fk,
                "quantity" => $quantity[$cnt],
                "intent_code"=>$intent_code,
                 "intent_id"=>$intent_id,
                 "credit"=>$quantity[$cnt],
                "batch_no"=>$batch_no[$cnt],
                "expire_date"=>$new_date,
                "status" => 1,
                "created_date" => date("Y-m-d H:i:s"),
                "created_by" => $login_id
            );

            $this->Invert_model->master_fun_insert("inventory_stock_master", $data);

            $cnt++;
        }
  

        $this->session->set_flashdata("success", array("Intent successfully added."));
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

    
        $edit_item_details = $this->Invert_model->get_val("SELECT inventory_stock_master.* , `inventory_item`.`reagent_name` AS item_name,inventory_category.name as Category_name FROM inventory_stock_master INNER JOIN `inventory_item` ON `inventory_item`.`id`=`inventory_stock_master`.`item_fk` left join inventory_category on inventory_category.id = inventory_item.category_fk WHERE inventory_stock_master.status = '1' and inventory_stock_master.branch_fk = '" . $tid . "' and  inventory_stock_master.machine_fk = '" . $mid . "' and inventory_stock_master.intent_id ='".$ind_id."'");

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
        $machine_array  = $this->Invert_model->get_val('select imb.*,im.name as MachineName from inventory_machine_branch as imb  INNER JOIN inventory_machine as im on im.id = imb.machine_fk where imb.branch_fk="'.$tid.'" and imb.status="1" and im.status="1"');

$data['reagent_array'] = array();
         $new_array1 = array();
          
                $mrdata = $this->Invert_model->get_val("SELECT inventory_item.id as REAGENTID,inventory_item.reagent_name as REAGENT from inventory_item where machine IN(" .  $mid . ") and status='1'");


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
        <input type="hidden" name="intent_id" value="<?= $ind_id;?>">

        <div class="form-group">      
            <label for="message-text" class="form-control-label">Add Reagent:</label>
            <select class="chosen chosen-select" name="item_fk" id="selected_item_edit" onchange="select_item_edit('Reagent', this);">
                <option value="">--Select--</option>
                <?php
                foreach ($data["reagent_array"][0] as $mkey) { echo "<pre>";print_r($data["reagent_array"]);

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
            <label for="message-text" class="form-control-label">Add Consumes:</label>
            <select class="chosen chosen-select" name="item_fk" id="selected_item_edit" onchange="select_item_edit('Cnsumers', this);">
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
            <label for="message-text" class="form-control-label">Add Stationary:</label>
            <select class="chosen chosen-select" name="item_fk" id="selected_item_edit" onchange="select_item_edit('Stationary', this);">
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
                <?php
                $cnt = 0;
                foreach ($edit_item_details as $ikey) { //echo "<pre>";print_r($edit_item_details);
                $old = explode("-",$ikey["expire_date"]);
                    $new_date = $old[2].'-'.$old[1].'-'.$old[0];

                    ?>
                    <tr id="tr_edit_<?= $cnt; ?>">
                        <td style="width:10%;font:12px;"><?= $ikey["item_name"] ?></td>
                        <td style="width:1%;font:12px;"><?= $ikey['Category_name']; ?></td>
                        <td style="width:10%;font:12px;"><input type="hidden" name="item[]" value="<?= $ikey["item_fk"] ?>"><input type="text" name="quantity[]" required="" value="<?= $ikey["quantity"] ?>" class="form-control"/></td>
                        <td style="width:20%;font:12px;"><input type="text" name="batch_no[]" required="" value="<?= $ikey["batch_no"] ?>" class="form-control"/></td>
                        <td style="width:40%;font:12px;"><input type="text" name="expire_date[]" required="" value="<?= $new_date  ?>" class="form-control" placeholder="Expire Date (DD/MM/YYYY)" onkeyup="this.value = this.value.replace(/^([\d]{2})([\d]{2})([\d]{4})$/,'$1-$2-$3'); " maxlength="10" tabindex="3"/></td>
                        <td style="width:20%;font:12px;"><a href="javascript:void(0);" onclick="delete_city_price_edit('<?= $cnt; ?>', '<?= $ikey["item_name"] ?>', '<?= $ikey["item_fk"] ?>')"><i class="fa fa-trash"></i></a></td>
                    </tr>
                    <?php
                    $cnt++;
                }

                ?>
                <tfoot>
                            <tr><th>Intent Code</th><td><input type="text" name="intent_code" value="<?php echo $ikey['intent_code'];?>"></td></tr>
                        </tfoot>
            <script>$city_cnt_edit = <?= $cnt ?>;</script>
        </tbody>
        </table>
        <?php
    }

    function intent_update() { 

        $this->Invert_model->new_fun_update("inventory_stock_master", array("branch_fk" => $this->input->post('branch_fk'),"machine_fk" => $this->input->post('machine_fk')), array("status" => "0"));
   $data["login_data"] = logindata();
   $login_id = $data["login_data"]['id'];
       $item_list = $this->input->post("item");
        $quantity = $this->input->post("quantity");
        $sub_intent = $this->input->post('intent_code');
        $branch_fk = $this->input->post('branch_fk');  $batch_no = $this->input->post('batch_no');
        $intent_code = $this->input->post('intent_code'); 
      $machine_fk = $this->input->post('machine_fk');
      $intent_id = $this->input->post('intent_id');

        $cnt = 0;
        foreach ($item_list as $kkey) {
                $expire_date = $this->input->post('expire_date')[$cnt];
        $old_date = explode("-",$expire_date);
       $new_date = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
            $data = array(
                "item_fk" => $kkey,
                "branch_fk" => $branch_fk,
             "machine_fk"=>$machine_fk,
                "quantity" => $quantity[$cnt],
                  "credit" => $quantity[$cnt],
                "intent_code" =>$intent_code,
                "batch_no"=>$batch_no[$cnt],
                "expire_date"=>$new_date,
                "status" => 1,
                "intent_id"=>$intent_id,
                "modified_date" => date("Y-m-d H:i:s"),
                "modified_by" => $login_id
            );
   
           $this->Invert_model->master_fun_insert("inventory_stock_master", $data);
            $cnt++;
        }
       
        $this->session->set_flashdata("success", array("Intent Invert successfully updated."));
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
        $data['query'] = $this->Invert_model->new_fun_update("inventory_stock_master", array("branch_fk" => $tid,"machine_fk"=>$mid), array("status" => "0"));

        $this->session->set_flashdata("success", array("Intent Invert successfull deleted."));
        redirect("inventory/Intent_Inward/inward_list", "refresh");
    }
 
    public function getReagent(){ 

        $id = $this->input->post('id');
       
        $query = $this->Invert_model->get_val("SELECT * from inventory_item where machine ='".$id."' and status='1'");
    
        $sub_array = '';
        $sub_array .='<option value="">Select Reagent </option>';
        if(!empty($query)){
            foreach($query as $val){
                $sub_array .='<option value="'.$val['id'].'">'.$val['reagent_name'].'</option>';
            }
            echo $sub_array;
        }
        exit;
    }
     public function getMachine(){ 
        $id = $this->input->post('id');
  
        $query = $this->Invert_model->get_val("SELECT iim.*,im.name AS MachineName,im.id as MachineId FROM inventory_machine_branch AS iim INNER JOIN inventory_machine AS im ON im.`id`=iim.machine_fk WHERE iim.branch_fk='".$id."' AND iim.`status`='1' AND im.status='1'");
        
        $sub_array = '';
        $sub_array .='<option value="">Select Machine </option>';
        if(!empty($query)){
            foreach($query as $val){
                $sub_array .='<option value="'.$val['MachineId'].'">'.$val['MachineName'].'</option>';
            }
            echo $sub_array;
        }
        exit;
    }
}
