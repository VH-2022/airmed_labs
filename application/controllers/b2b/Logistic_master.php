<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Logistic_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('b2b/Logistic_test_modal');
   $this->load->model('b2b/Logistic_master_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $data["login_data"] = logindata();

    }

    function sub_list(){
       if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['success'] = $this->session->flashdata("success");
        $search = $this->input->get('search');
        $city = $this->input->get('city'); 
        $data['search'] = $search;
        $lab  = $this->uri->segment(4);
		if($lab != ""){
			
		$data['lab'] = $this->uri->segment(4);
        $cquery = "";
        if ($search != "") {
			
			$query = $this->Logistic_master_model->get_val('select id,name,test_discount,city from collect_from where id="'.$lab.'" and status="1"');
			$data['labname']=$query[0]["name"];
			$data['discount'] = $query[0]['test_discount'];
            $city_fk=$query[0]['city'];

            $total_row = $this->Logistic_master_model->search_num($lab,$city_fk,$search);
            $config = array();
            $config["base_url"] = base_url() . "b2b/Logistic_master/sub_list/".$lab."?search=$search";
          
            $config["total_rows"] = $total_row;
            $config["per_page"] = 200;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Logistic_master_model->speci_list_search($lab,$city_fk,$search, $config["per_page"], $page);

            $data["links"] = $this->pagination->create_links();
			$data["counts"] = $page;
			
           // $data["counts"] = $page;
        } else {
			
			
			$query = $this->Logistic_master_model->get_val('select id,name,test_discount,city from collect_from where id="'.$lab.'" and status="1"');
			$data['labname']=$query[0]["name"];
			$data['discount']=$query[0]['test_discount'];
            $city_fk=$query[0]['city'];
            $totalRows = $this->Logistic_master_model->sub_listnum($lab,$city_fk);
            $config = array();
            $config["base_url"] = base_url()."b2b/Logistic_master/sub_list/".$lab;
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 200;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
             $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            
            $data["query"] = $this->Logistic_master_model->sub_list($lab,$city_fk,$config["per_page"], $page); 
			/* echo "<pre>";print_r($data["query"]); die(); */
			
			 /* echo  $this->db->last_query(); die(); */
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
		
        $data["page"] = $page;
        $this->load->view('header');
		$this->load->view('nav', $data);
        $this->load->view('b2b/sub_list',$data);
        $this->load->view('b2b/footer');
		
		}else{ show_404(); }
    
    }

      function test_csv() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $city = $this->input->get("city");
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "test.csv";
        //$query = 'SELECT t.test_name as `Test Name`,c.name as `City Name`,p.price as `Test Price` from sample_test_master as t left join sample_test_city_price as p on t.id=p.test_fk join test_cities as c on p.city_fk=c.id where t.status="1" and c.status="1" and p.status="1" order by t.test_name asc';
        $query = "SELECT  t.id,t.`test_name` as testname ,c.`name` as cityname,tc.`price` FROM  `sample_test_master` t  LEFT JOIN sample_test_city_price tc ON tc.test_fk=t.id  LEFT JOIN `test_cities` c ON tc.`city_fk`=c.`id` WHERE t.status = '1' AND tc.city_fk='" . $city . "'";
        $result = $this->db->query($query);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }

function get_test_data(){
  
    $test = $this->input->post('test_id');
    $lab = $this->input->post('lab_fk');
    
    $query = $this->Logistic_master_model->get_val('select * from collect_from where id="'.$lab.'" and status="1"');

    $city_fk = $query[0]['city'];

    $discount = $query[0]['test_discount'];

    $data = $this->Logistic_master_model->getfindtest($test,$city_fk);

    $response['price'] = $data[0]['price'];

    $response['b2bPrice'] = $response['price']-(($discount /100)*$response['price']);

   
echo json_encode($response);

}

function new_edit(){
    $test = $this->input->post('tid');
      $lab = $this->input->post('lab_id');

        $data = $this->Logistic_master_model->get_val("select t.id,t.test_name,b.price_special,b.id as bid from test_master as t LEFT JOIN b2b_testspecial_price as b on t.id = b.test_fk and b.lab_id = '".$lab."' and b.status='1' and b.test_fk='".$test."' where t.id='".$test."'");
        $response['BID'] = $data[0]['bid'];     
        $response['TID'] = $data[0]['id'];
        $response['Special'] = $data[0]['price_special'];
   echo json_encode($response);
    }


    function new_add_sub(){
		
	if (!is_loggedin()) {
            redirect('login');
        }

		$data["login_data"] = logindata();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('test','test','trim|required|numeric');
$this->form_validation->set_rules('lab_id', 'lab_id', 'trim|required|numeric');
$this->form_validation->set_rules('sprice', 'sprice', 'trim|required|numeric');
$this->form_validation->set_rules('ttype', 'ttype', 'trim|required|numeric');		
		if ($this->form_validation->run() != FALSE) {
		$test_id = $this->input->post('test');
        $lab = $this->input->post('lab_id');
		$sprice = $this->input->post('sprice');
		$ttype = $this->input->post('ttype');
		if($ttype=='2'){
			
			$query = $this->Logistic_master_model->get_val("select id,test_fk from b2b_testspecial_price where lab_id= '".$lab."' and test_fk='".$test_id."' and status='1' and typetest='2'");
			
		if($query != null){ $test_fk = $query[0]['test_fk'];
	  $testid=$query[0]['id']; }else{ $test_fk=""; }
	   if($lab !='' AND $test_fk !=''){
		   if ($sprice > 0) {

              $this->Logistic_master_model->master_fun_update("b2b_testspecial_price", array("id",$testid), array('test_fk'=>$test_id,"lab_id" => $lab,"price_special"=>$sprice));
             
             $this->session->set_flashdata("success", array("Special Price  successfully updated."));
            echo "1"; die();
		   }else{  echo "0"; die(); }

        }else{
			
			if ($sprice > 0) {

            $data['query'] = $this->Logistic_master_model->master_fun_insert("b2b_testspecial_price", array("lab_id" => $lab, "status" => "1", "test_fk" => $test_id,"typetest"=>'2',"price_special" => $sprice));
			$this->session->set_flashdata("success", array("Special Price  successfully updated."));
		   
		   echo "1"; 
		   
		   die();
			
			}else{ echo "0"; die(); }
        
		}
		
		}else{
			
		$query = $this->Logistic_master_model->get_val("select id,test_fk from b2b_testspecial_price where lab_id= '".$lab."' and test_fk='".$test_id."' and status='1' and typetest='1'");
      if($query != null){ $test_fk = $query[0]['test_fk'];
	  $testid=$query[0]['id']; }else{ $test_fk=""; }
	  
       if($lab !='' AND $test_fk !=''){ 
	   if ($sprice > 0) {

              $this->Logistic_master_model->master_fun_update("b2b_testspecial_price", array("id",$testid),array('test_fk'=>$test_id,"lab_id" => $lab,"price_special"=>$sprice));
             
             $this->session->set_flashdata("success", array("Special Price  successfully updated."));
            echo "1"; 
		   die();
	   }else{  echo "0";  die(); }

        }else{ 

               $data['query'] = $this->Logistic_master_model->master_fun_insert("b2b_testspecial_price", array("lab_id" => $lab, "status" => "1", "test_fk" => $test_id,"typetest"=>'1',"price_special" => $sprice));

           $this->session->set_flashdata("success", array("Special Price  successfully updated."));
		   echo "1";
        }	
		
		}
		
		}else{ echo "0"; }
    
}
 function new_add_mrp(){
		
	if (!is_loggedin()) {
            redirect('login');
        }
		$data["login_data"] = logindata();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('test','test','trim|required|numeric');
$this->form_validation->set_rules('lab_id', 'lab_id', 'trim|required|numeric');
$this->form_validation->set_rules('sprice', 'sprice', 'trim|required|numeric');
$this->form_validation->set_rules('ttype', 'ttype', 'trim|required|numeric');		
		if ($this->form_validation->run() != FALSE) {
			
		/* print_r($_POST); die(); */
		$test_id = $this->input->post('test');
        $lab = $this->input->post('lab_id');
		$sprice = $this->input->post('sprice');
		$ttype = $this->input->post('ttype');
		if($ttype=='2'){
			
			$query = $this->Logistic_master_model->get_val("select id,test_fk from b2b_testmrp_price where lab_id= '".$lab."' and test_fk='".$test_id."' and status='1' and typetest='2'");
			
		if($query != null){ $test_fk = $query[0]['test_fk'];
	  $testid=$query[0]['id']; }else{ $test_fk=""; }
	   if($lab !='' AND $test_fk !=''){
		   if ($sprice > 0) {

              $this->Logistic_master_model->master_fun_update("b2b_testmrp_price", array("id",$testid), array('test_fk'=>$test_id,"lab_id" => $lab,"price_special"=>$sprice));
             
             $this->session->set_flashdata("success", array("Test Mrp Price successfully updated."));
            echo "1"; die();
		   }else{   $this->Logistic_master_model->master_fun_update("b2b_testmrp_price", array("id",$testid), array('status'=>0)); echo "1"; die(); }

        }else{
			
			if ($sprice > 0) {

            $data['query'] = $this->Logistic_master_model->master_fun_insert("b2b_testmrp_price", array("lab_id" => $lab, "status" => "1", "test_fk" => $test_id,"typetest"=>'2',"price_special" => $sprice));
			$this->session->set_flashdata("success", array("Test Mrp Price  successfully updated."));
		   
		   echo "1"; 
		   
		   die();
			
			}else{ echo "0"; die(); }
        
		}
		
		}else{
			
		$query = $this->Logistic_master_model->get_val("select id,test_fk from b2b_testmrp_price where lab_id= '".$lab."' and test_fk='".$test_id."' and status='1' and typetest='1'");
      if($query != null){ $test_fk = $query[0]['test_fk'];
	  $testid=$query[0]['id']; }else{ $test_fk=""; }
	  
       if($lab !='' AND $test_fk !=''){ 
	   if($sprice==0){
		    $this->Logistic_master_model->master_fun_update("b2b_testmrp_price", array("id",$testid), array('status'=>0));
	   }else{
	   
              $this->Logistic_master_model->master_fun_update("b2b_testmrp_price", array("id",$testid),array('test_fk'=>$test_id,"lab_id" => $lab,"price_special"=>$sprice));
	   } 
             $this->session->set_flashdata("success", array("Test Mrp Price  successfully updated."));
            
			echo "1"; 
		   die();

        }else{ 

               $data['query'] = $this->Logistic_master_model->master_fun_insert("b2b_testmrp_price", array("lab_id" => $lab, "status" => "1", "test_fk" => $test_id,"typetest"=>'1',"price_special" => $sprice));

           $this->session->set_flashdata("success", array("Test Mrp Price  successfully updated."));
		   echo "1";
        }	
		
		}
		
		}else{ echo "0"; }
    
}
}
?>