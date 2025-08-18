<?php
class Handover_item extends CI_Controller{
    function __construct(){
        parent::__construct();
     
        $this->load->model('user_model');
        $this->load->model('inventory/handover_item_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
    }
    
    function index(){
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
     $user_fk = $this->input->get_post('user_fk');
        $branch = $this->input->get_post('branch_fk');
   
          $config = array();
       
            
             $config["per_page"] =50;
            $config["uri_segment"] = 4;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $config['page_query_string'] = TRUE;
            $temp='';
            $temp = "";
            if($user_fk !=''){ 
                    $temp .=' AND iu.handover_to="'.$user_fk.'"';
                }
    if($branch !=''){
                    $temp .=' AND iu.branchfk="'.$branch.'"';
                }
    
  
         if($user_fk !='' || $branch !=''){ 
          $data['user'] =$user_fk;
          $data['branch'] =$branch;
           if ($type == 1 || $type == 2 || $type == 8) {
            $config["base_url"] = base_url() . "inventory/Handover_item/index?user_fk=$user_fk&branch=$branch";
             $totalRows=$this->handover_item_model->get_val("select count(id) as ID from inventory_usedreagent  as iu where iu.status !='0' $temp ");
          
             $config["total_rows"] = $totalRows[0]["ID"];
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
//Vishal COde End
            $data['query'] = $this->handover_item_model->get_val("SELECT  br.branch_name,it.reagent_name, iu.*,am.name,ism.quantity as Quantity,iu.quantity asIUQuantity,ism.batch_no as Batch FROM inventory_usedreagent AS iu LEFT JOIN branch_master AS br ON br.id = iu.branchfk AND br.status = '1' inner JOIN inventory_item AS it ON it.id = iu.reaqgentfk AND it.status = '1' LEFT JOIN admin_master AS am ON am.id = iu.`handover_to`  AND am.`status`='1' LEFT JOIN inventory_stock_master as ism on ism.id = iu.batchnuno and ism.status='1' WHERE iu.status = '1' AND it.category_fk in (1,2) $temp order by iu.id DESC LIMIT $page,".$config['per_page']." ");
         
        } else {             //Vishal COde Start
              $config["base_url"] = base_url() . "inventory/Handover_item/index?user_fk=$user_fk&branch=$branch";
             $totalRows=$this->handover_item_model->get_val("SELECT iu.*,count(iu.id) as ID FROM inventory_usedreagent iu WHERE iu.`status` !='0' $temp and iu.branchfk in(SELECT `branch_fk`  FROM `user_branch`  WHERE STATUS = '1' AND user_fk = ".$login_id.")");
        
             $config["total_rows"] = $totalRows[0]["ID"];
             $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            //Vishal COde End

            $data['query'] = $this->handover_item_model->get_val("SELECT  br.branch_name,it.reagent_name, iu.*,am.name,ism.quantity as Quantity,iu.quantity asIUQuantity,ism.batch_no as Batch FROM inventory_usedreagent AS iu LEFT JOIN branch_master AS br ON br.id = iu.branchfk AND br.status = '1' inner JOIN inventory_item AS it ON it.id = iu.reaqgentfk AND it.status = '1' LEFT JOIN admin_master AS am ON am.id = iu.`handover_to`  AND am.`status`='1' LEFT JOIN inventory_stock_master as ism on ism.id = iu.batchnuno and ism.status='1' WHERE iu.status = '1' and iu.branchfk in(SELECT `branch_fk`  FROM `user_branch`  WHERE STATUS = '1' AND user_fk = " . $login_id . ") AND it.category_fk in (1,2) $temp order by iu.id desc LIMIT $page,".$config['per_page']."");
            
            
        }
        //Filteration Close
         }else{

          if ($type == 1 || $type == 2 || $type == 8) {
            $config["base_url"] = base_url() . "inventory/Handover_item";
             $totalRows=$this->handover_item_model->get_val("select iu.*, count(iu.id) as ID from inventory_usedreagent  as iu where iu.status !='0' $temp ");
             $config["total_rows"] = $totalRows[0]["ID"];
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
//Vishal COde End
            $data['query'] = $this->handover_item_model->get_val("SELECT br.branch_name,it.reagent_name, iu.*,am.name,ism.quantity as Quantity,iu.quantity asIUQuantity,ism.batch_no as Batch FROM inventory_usedreagent AS iu LEFT JOIN branch_master AS br ON br.id = iu.branchfk AND br.status = '1' LEFT JOIN inventory_item AS it ON it.id = iu.reaqgentfk AND it.status = '1' LEFT JOIN admin_master AS am ON am.id = iu.`handover_to`  AND am.`status`='1' LEFT JOIN inventory_stock_master as ism on ism.id = iu.batchnuno and ism.status='1' WHERE iu.status = '1' AND it.category_fk in (1,2)  $temp order by iu.id DESC LIMIT $page,".$config['per_page']." ");
         
        } else { 
            //Vishal COde Start
            $config["base_url"] = base_url() . "inventory/Handover_item";
             $totalRows=$this->handover_item_model->get_val("SELECT iu.*,count(iu.id) as ID FROM inventory_usedreagent iu WHERE iu.`status` !='0' $temp and iu.branchfk in(SELECT `branch_fk`  FROM `user_branch`  WHERE STATUS = '1' AND user_fk = ".$login_id.")");
          
             $config["total_rows"] = $totalRows[0]["ID"];
             $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            //Vishal COde End

            $data['query'] = $this->handover_item_model->get_val("SELECT br.branch_name,it.reagent_name, iu.*,am.name,ism.quantity as Quantity,iu.quantity asIUQuantity,ism.batch_no as Batch FROM inventory_usedreagent AS iu LEFT JOIN branch_master AS br ON br.id = iu.branchfk AND br.status = '1' LEFT JOIN inventory_item AS it ON it.id = iu.reaqgentfk AND it.status = '1' LEFT JOIN admin_master AS am ON am.id = iu.`handover_to`  AND am.`status`='1' LEFT JOIN inventory_stock_master as ism on ism.id = iu.batchnuno and ism.status='1' WHERE iu.status = '1' AND it.category_fk in (1,2) and iu.branchfk in(SELECT `branch_fk`  FROM `user_branch`  WHERE STATUS = '1' AND user_fk = " . $login_id . ") $temp order by iu.id desc LIMIT $page,".$config['per_page']."");
         
        }
      }
 $data["links"] = $this->pagination->create_links();
         
            $data["counts"] = $page;
        //Vishal COde Start
             if($type=='1' || $type=='2' || $type=='8'){
           $data['branch_list'] = $this->handover_item_model->get_data('select id,branch_name from branch_master where status="1"');
                      $data['user_list'] = $this->handover_item_model->get_data('select id,name from admin_master where status="1"');

           }else{
                $data['branch_list'] = $this->handover_item_model->get_data('select br.id,br.branch_name from admin_master LEFT JOIN user_branch on user_branch.user_fk= admin_master.id and admin_master.status="1" LEFT JOIN branch_master as br on br.id = user_branch.branch_fk and user_branch.status="1"  where user_branch.user_fk="'.$login_id.'" and br.status="1" order by br.branch_name asc');
              
                    $data['user_list'] = $this->handover_item_model->get_data('select admin_master.id,admin_master.name from admin_master LEFT JOIN user_branch on user_branch.user_fk= admin_master.id  where  admin_master.status="1"  order by admin_master.name asc');
                } 
                if($type == 1 || $type == 2 || $type == 5){
         $this->load->view('header',$data);
         $this->load->view('nav',$data);
       }
       if($type == 8){
         $this->load->view('inventory/header',$data);
         $this->load->view('inventory/nav',$data);
       }
          $this->load->view('inventory/hand_item_list',$data);
           $this->load->view('footer');
         }
    function add(){
         if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
      
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $data['error'] = $this->session->flashdata("error");
       $this->form_validation->set_rules("branch_fk","Branch Name","trim|required");
        $this->form_validation->set_rules("item_fk","Reagent Name","trim|required");
        $this->form_validation->set_rules("batch_fk","Batch Name","trim|required");
       $this->form_validation->set_rules("quantity","","trim|callback_minus||");
       $hand = $this->input->post('handover_to');
       $remark = $this->input->post('remark');

       if( $hand !='' ||  $remark != '' ){
        
       }else{
                  $this->form_validation->set_rules("sub","Handover or Remak any one compalusury","trim|required");
       } 
      
         $this->form_validation->set_rules("quantity","","trim|callback_minus");
       
      if($this->form_validation->run() != FALSE){
           $post['indedfk'] = $this->input->post('stock_id'); 
           $post['branchfk'] = $this->input->post('branch_fk');
            $post['reaqgentfk'] = $this->input->post('item_fk');
            $post['batchnuno'] = $this->input->post('batch_fk');
            $post['handover_to'] = $this->input->post('handover_to');
            $post['remark'] = $this->input->post('remark');
             $post['quantity'] = $this->input->post('quantity');
              $post['creteddate'] = date('Y-m-d H:i:s');
               $post['credtedby'] = $login_id;
              $query = $this->handover_item_model->get_val("select quantity,used from inventory_stock_master where id='".$post['indedfk']."' and status='1'");
             
                 $sub = ($query[0]['quantity'] - $query[0]['used']);
                
            if($sub > $post['quantity'] || $sub == $post['quantity']  ){
               $sub_test  = ($query[0]['used'] + $post['quantity']);
             
                $useed = array(
                    'used'=>$sub_test
                );
                $this->handover_item_model->master_fun_insert("inventory_usedreagent",$post);
                 $this->handover_item_model->master_fun_update("inventory_stock_master",$post['indedfk'],$useed);
            
            }else{  
  $error = "Please Enter Proper Stock Detail";
            $this->session->set_flashdata("error", array($error));
               
             redirect("inventory/Handover_item/add","refresh");
               
            }
               $this->session->set_flashdata("success", array("Handover Item Successfull Added."));
      redirect("inventory/Handover_item","refresh");
            
       }else{
         if($type=='1' || $type=='2' || $type=='8'){
           $data['branch_list'] = $this->handover_item_model->get_data('select id,branch_name from branch_master where status="1"');
           $data['user_list'] = $this->handover_item_model->get_data('select id,name from admin_master where status="1"');
           }else{
                $data['branch_list'] = $this->handover_item_model->get_data('select br.id,br.branch_name from admin_master LEFT JOIN user_branch on user_branch.user_fk= admin_master.id and admin_master.status="1" LEFT JOIN branch_master as br on br.id = user_branch.branch_fk and user_branch.status="1"  where user_branch.user_fk="'.$login_id.'" and br.status="1" order by br.branch_name asc');
          $data['user_list'] = $this->handover_item_model->get_data('select admin_master.id,admin_master.name from admin_master LEFT JOIN user_branch on user_branch.user_fk= admin_master.id and admin_master.status="1" LEFT JOIN branch_master as br on br.id = user_branch.branch_fk and user_branch.status="1"  where  br.status="1" order by br.branch_name asc');
          } 
          if($type =='1' || $type =='2' || $type =='5'){
           $this->load->view('header',$data);
          
         $this->load->view('nav',$data);
       }
       if($type =='8'){
         $this->load->view('inventory/header',$data);
         $this->load->view('inventory/nav',$data);
       }
          $this->load->view('inventory/hand_item_add');
           $this->load->view('footer');
       }
     
       
    }
    function delete() {

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('4');
        $data['query'] = $this->handover_item_model->master_fun_update("inventory_usedreagent", $cid, array("status" => "0"));
        $this->session->set_flashdata("success", array('Handover Item Successfull Deleted.'));
        redirect("inventory/Handover_item", "refresh");
    }
    function getreagent(){
        $id = $this->input->post('id');
       $query = $this->handover_item_model->get_data("SELECT ism.inward_fk, item.`id`,item.`reagent_name`,item.category_fk FROM `inventory_stock_master` AS ism LEFT JOIN inventory_inward_master AS iim ON  ism.`inward_fk` =iim.id AND iim.status = '1' LEFT JOIN branch_master AS br ON br.id = iim.`branch_fk` AND br.status='1' LEFT JOIN inventory_item  AS item ON item.id = ism.`item_fk` AND item.`status`='1' AND item.`reagent_name` != '' WHERE  ism.status='1' AND iim.branch_fk='".$id."' and ism.inward_fk !='0' and item.`reagent_name` IS NOT NULL  order by ism.inward_fk DESC ");
     
       $lab_stats= '';
          $lab_stats .='<option value="">Select Reagent</option>';
       if(!empty($query)){
           $dup=array();
           foreach($query as $key=>$val){
               
               if(!in_array($val['id'], $dup)){
              $lab_stats .='<option value="'.$val['id'].'">'.$val['reagent_name'].'</option>'; 
           }
           $dup[] = $val['id'];
           }

            echo $lab_stats;
            }
            exit;
    }
    function getBatch(){
        $id = $this->input->post('id');
      
       $query = $this->handover_item_model->get_data("SELECT id,batch_no,quantity,expire_date,used,(quantity - used) AS Subtrach FROM inventory_stock_master WHERE item_fk='".$id."' and status='1' AND batch_no !='' AND batch_no IS NOT NULL AND inward_fk !='0' and quantity !=used");

       $lab_stats= '';
       $lab_stats .='<option value="">Select Batch</option>';
        $sub = array();
     
           foreach($query as $val){ 
               if($val['expire_date'] !='0000:00:00'){
                   $o_date=explode("-",$val['expire_date']);
                   $expire_date = '(Expire Date:'.$o_date[2].'-'.$o_date[1].'-'.$o_date[0].')';
                 
               }
               if($val['Subtrach'] !=''){
                   $new = '(Availabe Quantity :- '.$val['Subtrach'].')';
               }
               if(!in_array($val['batch_no'],$batch)){ 
              $lab_stats .='<option value="'.$val['id'].'" >'.$val['batch_no'].$expire_date .$new.'</option>';  
           
           }
           $batch[] = $val['batch_no'];
         }
           echo $lab_stats;
        
            exit;
    }
    
    function minus(){
        $quantity = $this->input->post('quantity');
        if($quantity < 0){
           $this->form_validation->set_message("minus","Negative number not allowed");
           return false;
        }else{
            return true;
        }
    }
}