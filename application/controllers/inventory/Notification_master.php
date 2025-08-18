<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Notification_master extends CI_Controller {

    function __construct() {
        parent::__construct();
		$this->load->model('inventory/notifiction_model');
        $data["login_data"] = logindata();
    }
	
public function index(){
	
	 if (!is_loggedin()) {
            redirect('login');
        }
        $login_data = logindata();
        if($login_data["type"]==8){
        
		$query = $this->notifiction_model->get_val("SELECT COUNT(id) AS id FROM inventory_intent_master WHERE status ='1' AND i_notiy='1' ");
		$poquery = $this->notifiction_model->get_val("SELECT COUNT(id) AS id FROM inventory_pogenrate WHERE status !='0' AND i_notiy='1'  ");
		$totalpo=$poquery[0]['id'];
		
		$inwardquery = $this->notifiction_model->get_val("SELECT COUNT(id) AS id FROM inventory_inward_master WHERE status ='1' AND i_notiy='1' ");
			
		}else if($login_data["type"]==1 || $login_data["type"]==2){
			
			$query = $this->notifiction_model->get_val("SELECT COUNT(id) AS id FROM inventory_intent_master WHERE status ='1'  AND a_notity='1' ");
			$poquery = $this->notifiction_model->get_val("SELECT COUNT(id) AS id FROM inventory_pogenrate WHERE status ='3' AND a_notity='1' ");
	   $totalpo=$poquery[0]['id'];
	   
	   $inwardquery = $this->notifiction_model->get_val("SELECT COUNT(id) AS id FROM inventory_inward_master WHERE status ='1' AND a_notity='1' ");
			
		}else{
			
			$query = $this->notifiction_model->get_val("SELECT COUNT(id) AS id FROM inventory_intent_master WHERE status ='2' AND b_notity='1' and branch_fk in(SELECT `branch_fk`  FROM `user_branch`  WHERE STATUS = '1' AND user_fk = " . $login_data["id"] . ") ");
			
			$poquery = $this->notifiction_model->get_val("SELECT COUNT(id) AS id FROM inventory_pogenrate WHERE status in(1,4) AND b_notity='1' and branchfk in(SELECT `branch_fk`  FROM `user_branch`  WHERE STATUS = '1' AND user_fk = " . $login_data["id"] . ") ");
			$totalpo=$poquery[0]['id'];
			
			$inwardquery = $this->notifiction_model->get_val("SELECT COUNT(id) AS id FROM inventory_inward_master WHERE status ='1' AND b_notity='1' ");
			
		}
		
       $json['totalrequest'] = $query[0]['id'];
	   $json['totalpo'] =$totalpo;
	   $json['totalpoinward'] =$inwardquery[0]['id'];
     
	 echo json_encode($json);
	
}	
public function notuupdate(){
	
	 if (!is_loggedin()) {
            redirect('login');
        }
        $login_data = logindata();

	   if($login_data["type"] == 8){
		
		$this->notifiction_model->new_fun_update('inventory_intent_master',array("i_notiy"=>'1',"status !="=>'0'),array("i_notiy" =>'0')); 
		
		}else if($login_data["type"] == 1 || $login_data["type"] == 2){
			
		 $this->notifiction_model->new_fun_update('inventory_intent_master',array("a_notity"=>'1',"status !="=>'0'),array("a_notity" =>'0'));
		
		}else{
			
		    $this->notifiction_model->new_fun_update('inventory_intent_master',array("b_notity"=>'1',"status !="=>'0'),array("b_notity" =>'0'));
		
		}
	
}
public function ponotuupdatenoti(){
	
	 if (!is_loggedin()) {
            redirect('login');
        }
        $login_data = logindata();
		$id=$this->input->get("cid");
		if($id != ""){
	    
	   if($login_data["type"] == 1 || $login_data["type"] == 2){
		   
		 $this->notifiction_model->new_fun_update('inventory_pogenrate',array("a_notity"=>'1',"status"=>'3',"id"=>$id),array("a_notity" =>'0'));
		
		}else if($login_data["type"] == 8){
			
			$this->notifiction_model->new_fun_update('inventory_pogenrate',array("i_notiy"=>'1',"status !="=>'0',"id"=>$id),array("i_notiy" =>'0'));
			
		}else{
			
		   $this->notifiction_model->new_fun_update('inventory_pogenrate',array("b_notity"=>'1',"status !="=>'0',"id"=>$id),array("b_notity" =>'0'));
		 }
		
		}
	
}
public function notinwarduupdate(){
	
	 if (!is_loggedin()) {
            redirect('login');
        }
        $login_data = logindata();

	   if($login_data["type"] == 8){
		
		$this->notifiction_model->new_fun_update('inventory_inward_master',array("i_notiy"=>'1',"status !="=>'0'),array("i_notiy" =>'0')); 
		
		}else if($login_data["type"] == 1 || $login_data["type"] == 2){
			
		 $this->notifiction_model->new_fun_update('inventory_inward_master',array("a_notity"=>'1',"status !="=>'0'),array("a_notity" =>'0'));
		
		}else{
			
		    $this->notifiction_model->new_fun_update('inventory_inward_master',array("b_notity"=>'1',"status !="=>'0'),array("b_notity" =>'0'));
		
		}
	
}
}

?>