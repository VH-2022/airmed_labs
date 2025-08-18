<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Apiv6 extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('sales/service_model6','service_model5');
		$this->app_tarce();
    }
function app_tarce() {
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        $page = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
        if (!empty($_SERVER['QUERY_STRING'])) {
            $page = $_SERVER['QUERY_STRING'];
        } else {
            $page = "";
        }
        if (!empty($_POST)) {
            $user_post_data = $_POST;
        } else {
            $user_post_data = array();
        }
        $user_post_data = json_encode($user_post_data);
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $remotehost = @getHostByAddr($ipaddress);
        $user_info = json_encode(array("Ip" => $ipaddress, "Page" => $page, "UserAgent" => $useragent, "RemoteHost" => $remotehost));
        $user_track_data = array("url" => $actual_link, "user_details" => $user_info, "data" => $user_post_data, "createddate" => date("Y-m-d H:i"), "type" => "service");
        //print_R($user_track_data);
        $app_info = $this->service_model5->insert("sales_alldata", $user_track_data);
        //return true;
    }
	/* function json_data($status, $error_msg, $data = NULL) {
        if ($data == NULL) {
            $data = array();
        }
        $final = array("status" => $status, "error_msg" => $error_msg, "data" => $data);
        return json_encode($final);
    } */
	function json_data($status, $error_msg, $data = NULL) {
        if ($data == NULL) {
            $data = array();
        }
        $final = array("status" => $status, "error_msg" => $error_msg, "data" => $data);
        return json_encode($final,JSON_UNESCAPED_UNICODE);
    }
	
public function uplode_document(){
header('Access-Control-Allow-Origin: *');

$labid=$this->input->post('labid');
$config['upload_path'] ='./upload/labducuments';
$config['max_size'] = '500000'; 
        /* $config['allowed_types'] = 'gif|jpg|png|jpeg'; */
$config['allowed_types']='*';
if($labid != ""){
if($_FILES["images"]["name"][0] != "" && $labid != ""){

	 $files = $_FILES;
	 
		$cpt = count($_FILES['images']['name']);
		$allducment=array();
		$filetype=array();
		$filetyple=array();
		for ($i = 0; $i < $cpt; $i++) {
			
			
			$getfiletype=explode("_",$files['images']['name'][$i]);
			$filetyple[]=$files['images']['name'][$i];
			
			$_FILES['images']['name'] = $files['images']['name'][$i];
          $_FILES['images']['type'] = $files['images']['type'][$i]; 
           $_FILES['images']['tmp_name'] = $files['images']['tmp_name'][$i];
           $_FILES['images']['error'] = $files['images']['error'][$i];
           $_FILES['images']['size'] = $files['images']['size'][$i];
		  $fileExt = array_pop(explode(".",$files['images']['name'][$i]));
			$filename = md5(time()).".".$fileExt;
		  $_FILES['images']['name']=$filename;
		    $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $img = "images";
            if (!$this->upload->do_upload($img)) {
				
             $data['error'] = $this->upload->display_errors();  
             $ses = $data['error']; 
			 echo $this->json_data("0",$ses,"");
			 die();
					
            } else {
				
                $file_data = $this->upload->data();
				$profile_pic_size=$file_data['file_size'];
				$file_name=$file_data['file_name'];
				  if($file_data){
				  $allducment[]=$file_name;
				   $filetype[]=$getfiletype[0];
				 
                }else{
                	echo $this->json_data("0", "sorry your image height width must be 577*390", "");
                }
            }
}
if($allducment != null){ $d=0; foreach($allducment as $val){ $this->service_model5->master_fun_insert("lab_document",array("lab_id"=>$labid,"type"=>$filetype[$d],"dock_name"=>$val,"credteddate"=>date("Y-m-d H:i:s"))); $d++; }
}else{ echo $this->json_data("0", "All Parameters are required.", ""); die();}
	echo $this->json_data("1", "",array());
}else{
	
$num=$this->service_model5->master_num_rows('lab_document',array("lab_id"=>$labid,"status"=>'1')); 
if($num != "0"){ echo $this->json_data("1", "",array()); }else{ echo $this->json_data("0", "Please select your kyc documents.", ""); }	

	}
	
}else{ echo $this->json_data("0", "All Parameters are required.", ""); }
}


public function splitNewLine($text) {
    $code=preg_replace('/\n$/','',preg_replace('/^\n/','',preg_replace('/[\r\n]+/',"\n",$text)));
    return explode("\n",$code);
}
function getclientlist(){
header('Access-Control-Allow-Origin: *');
$id=$this->input->get('salesfk');
$startdate=$this->input->get('startdate');
$enddate=$this->input->get('enddate');
$data=$this->service_model5->clientprocces_list($id,$startdate,$enddate);
 echo $this->json_data("1", "",$data);
}
function getclientdetils(){
header('Access-Control-Allow-Origin: *');
$id=$this->input->get('clientid');
if($id != ""){	

	$select="id,name,email,mobile_number,contact_person_name,alternate_number,pincode,pancard,bus_desc,space_allocate,bus_expeted,address,city,createddate";
$data=$this->service_model5->master_fun_get_tbl_val('collect_from',$select,array("status"=>'3',"id"=>$id));
 echo $this->json_data("1", "",$data);
}else{ echo $this->json_data("0", "All Parameters are required.", ""); }
}	
function check_email2(){

	$clientid = $this->input->post('clientid');
    $email = $this->input->post('email');
	$result=$this->service_model5->master_num_rows('collect_from',array("email"=>$email,"id !="=>$clientid));
		if($result != 0) {
    	 echo $this->json_data("0", "Email Already Exists.",""); die();
      	return false;
		}else { return true; }

}
function check_mobile1(){

	$clientid = $this->input->post('clientid');
    $mobile = $this->input->post('mobile');
	$result=$this->service_model5->master_num_rows('collect_from',array("mobile_number"=>$mobile,"id !="=>$clientid));
		if($result != 0) {
    	 echo $this->json_data("0", "Mobile number Already Exists.",""); die();
      	return false;
		}else { return true; }

}
public function clint_edit(){
	 
 $this->load->library('form_validation');
 $this->form_validation->set_rules('clientid','clientid','trim|required');
 $this->form_validation->set_rules('name', 'Name','required|trim|callback_alpha_dash_space');
  $this->form_validation->set_rules('email', 'Email','required|trim|valid_email|callback_check_email2');
  //$this->form_validation->set_rules('password','password','trim|required');
  $this->form_validation->set_rules('city', 'City', 'trim|required|numeric');
  $this->form_validation->set_rules('address', 'Address','trim');
  $this->form_validation->set_rules('pincode', 'pincode','trim');
  $this->form_validation->set_rules('landline', 'landline telephone no','trim');
  $this->form_validation->set_rules('mobile','Mobile no','trim|required|callback_check_mobile1');
  $this->form_validation->set_rules('contact','contact person','trim');
  $this->form_validation->set_rules('pannumbar', 'pan card of clients','trim');
  $this->form_validation->set_rules('busdesc','business description','trim');
  $this->form_validation->set_rules('space','space allocated ','trim');
  $this->form_validation->set_rules('bexpected','business expected','trim');
   
if ($this->form_validation->run() == FALSE) {
 
			if(validation_errors() != ""){	$erro=$this->splitNewLine(validation_errors()); }else{ $erro=""; }
			echo $this->json_data("0","All Parameter are Required!",$erro); die();      
		
		} else {
		
		$clientid = $this->input->post('clientid');
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		//$password = $this->input->post('password');
		$address = $this->input->post('address');
		$pincode = $this->input->post('pincode');
		$landline = $this->input->post('landline');
		$mobile = $this->input->post('mobile');
		$contact = $this->input->post('contact');
		$pannumbar = $this->input->post('pannumbar');
		$busdesc = $this->input->post('busdesc');
		$space = $this->input->post('space');
		$bexpected = $this->input->post('bexpected');
		$city = $this->input->post('city');
		
		$data=array("name"=>$name,"email"=>$email,"password"=>$mobile,"contact_person_name"=>$contact,"mobile_number"=>$mobile,"alternate_number"=>$landline,"pincode"=>$pincode,"pancard"=>$pannumbar,"bus_desc"=>$busdesc,"space_allocate"=>$space,"bus_expeted"=>$bexpected,"address"=>$address,"createddate"=>date("Y-m-d H:i:s"),"type"=>'2',"city"=>$city,"status"=>'3');
		$colletd=$this->service_model5->master_fun_update('collect_from',$clientid,$data);
	 
	 echo $this->json_data("1", "",array(array("id"=>$clientid)));
	
		}
        
  }
function getclientkyc(){
header('Access-Control-Allow-Origin: *');
$id=$this->input->get('clientid');
if($id != ""){	
$select="id,dock_name,credteddate";
$data=$this->service_model5->master_fun_get_tbl_val('lab_document',$select,array("status"=>'1',"lab_id"=>$id));
 echo $this->json_data("1", "",$data);
}else{ echo $this->json_data("0", "All Parameters are required.", ""); }

}
function clientkyc_delete(){
header('Access-Control-Allow-Origin: *');
$id=$this->input->get('kycid');
if($id != ""){
$this->service_model5->master_fun_update('lab_document',$id,array("status"=>0));
 echo $this->json_data("1", "",array());
}else{ echo $this->json_data("0", "All Parameters are required.", ""); }

}	
function gettestauto(){
header('Access-Control-Allow-Origin: *');

$testname=$this->input->get('testname');
$labid=$this->input->get('labid');
if($labid != ""){
$getcity=$this->service_model5->fetchdatarow('city','collect_from',array("id"=>$labid));
$data=$this->service_model5->getfindtest($testname,$getcity->city);
 echo $this->json_data("1", "",$data);
	}else{
		echo $this->json_data("0", "All Parameters are required.", "");
	}
}
function labtestadd(){
	
	$percentage=$this->input->post('percentage');
	$labid=$this->input->post('labid');
	$testspeical=$this->input->post('testspeical');
	$cratedby=$this->input->post('cratedby');
	
	if($percentage != "" && $labid != "" && $testspeical != "" && $cratedby !=""){
		
	$this->service_model5->master_fun_update('collect_from',$labid,array("test_discount"=>$percentage));
	$testarray = json_decode($testspeical, true );
	foreach($testarray as $item) {
	 $id=$item['id'];
	$price=$item['price'];
	$this->service_model5->master_fun_insert("b2b_testspecial_price",array("lab_id"=>$labid,"test_fk"=>$id,"price_special"=>$price,"crated_by"=>$cratedby,"creted_date"=>date("Y-m-d H:i:s")));
     
	 }
$this->service_model5->master_fun_update('collect_from',$labid,array("status"=>'2'));
	echo $this->json_data("1", "",array());
	
	}
 }
function paymentpayumoney_success() {
        
       $t=json_encode($this->input->post());
         
        $payumonyid = $this->input->post('txnid');
		$amount = $this->input->post('amount');
		$status = $this->input->post('status');
		$labid=$this->input->post('zipcode');
		$userid=$this->input->post('country');
        /* $data1 = array("payomonyid" => $payumonyid,
            "amount" => $amount,
            "paydate" => $paydate,
            "status" => $status,
            "uid" => $userid,
            "type" => "wallet",
            "data" => $t,
        );
        $insert = $this->service_model->master_fun_insert("payment", $data1); 
		
		*/
 $this->service_model5->master_fun_insert("payment_log",array("lab_id"=>$labid,"payomonyid"=>$payumonyid,"amount"=>$amount,"payment_status"=>$status,"pay_log"=>$t));
 $this->service_model5->master_fun_update('collect_from',$labid,array("status"=>'4'));
 
 $crditdetis = $this->service_model5->creditget_last($labid);
            if ($crditdetis != "") {
                $total = ($crditdetis->total + $amount);
            } else {
                $total = (0 + $amount);
            }
	$this->service_model5->master_fun_insert("sample_credit", array("lab_fk" =>$labid, "credit" =>$amount, "total" => $total, "created_date" => date("Y-m-d H:i:s")));
 
        $data['payuMoneyId'] = $this->input->post('payuMoneyId');
        $this->load->view('success1', $data);
    }
function payu_fail() {
        $data['payuMoneyId'] = $_POST['payuMoneyId'];
        $this->load->view('fail', $data);
    }	
 }
?>