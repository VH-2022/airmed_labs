<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PineLabs extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
		$this->load->model('job_model');
        $this->load->model('user_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('email');
        $this->load->helper('string');
        $data["login_data"] = logindata();
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function swipe_payment() {
        if (!is_loggedin()) {
            redirect('login');
        }
		
      $data["login_data"] = logindata();
	  $adminid=$data["login_data"]["id"];
	  $user = $this->user_model->getUser($data["login_data"]["id"]);
	  $pineid=$user->pinelab_fk;
	  
	 if($pineid != "" && $pineid !="0"){
		  
	$machindetils = $this->job_model->get_val("SELECT id,`imei_no`,postcode FROM pinelab_terminal_master WHERE STATUS='1' AND id='$pineid'");
	$imeino=$machindetils[0]["imei_no"];
	$postcode=$machindetils[0]["postcode"];
$poterminalid=$machindetils[0]["id"];
	 
	  
	  $paydetils = $this->job_model->get_val("SELECT id,job_fk,plutus_transaction_reference_id,TransactionNumber,SequenceNumber,amount FROM `pinelabs` WHERE STATUS='2' and pineterminal_id='$poterminalid' ORDER BY id DESC");
	  
	 if($paydetils[0]["job_fk"] == ""){
	  
	  $jid = $this->input->post("jid");
	  $j_amount = $this->input->post("j_amount"); 
	  $transacionid=date("Ymdhis");
	
//$this->swipe_paymentcancel($machindetils);    
                                                                           
    $error=1;                                                                                                                 
$ch = curl_init('https://www.plutuscloudserviceuat.in:8201/API/CloudBasedIntegration/V1/UploadBilledTransaction');
$payamount=($j_amount*100);

 $data_string='{
    "TransactionNumber": "'.$transacionid.'",
    "SequenceNumber": 1,
    "AllowedPaymentMode": "1",
    "MerchantStorePosCode": "'.$postcode.'",
    "Amount": "'.$payamount.'",
    "UserID": "cashiername",
    "MerchantID": 2137,
    "SecurityToken": "b48894a3-3be5-4618-9312-34c8f79aa6a5",
    "IMEI": "'.$imeino.'"
}'; 
 
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);                                                                   
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data_string))                                                                       
);                                                                                                                   
                                                                                                                     
  $result = curl_exec($ch); 
  
 $getdata=json_decode($result);
 if($getdata->ResponseCode==0){
	 $error=0;
	 $plutoid=$getdata->PlutusTransactionReferenceID;
	 $msg="";
	 
	 $this->job_model->master_fun_insert("pinelabs_log",array("plutus_transaction_reference_id"=>$plutoid,"log"=>$result,"created_time"=>date("Y-m-d H:i:s")));
	 
	 $data=array("job_fk"=>$jid,"plutus_transaction_reference_id"=>$plutoid,"TransactionNumber"=>$transacionid,"amount"=>$payamount,"SequenceNumber"=>'1',"status"=>'2',"pineterminal_id"=>$pineid,"created_by"=>$adminid,"created_time"=>date("Y-m-d H:i:s"));
	 $this->job_model->master_fun_insert("pinelabs",$data);
	 
}else{
	$msg=$getdata->ResponseMessage;
	$plutoid=$getdata->PlutusTransactionReferenceID;
 }
 echo json_encode(array("status"=>$error,"msg"=>$msg,"plutoid"=>$plutoid));
 
	}else{
		
	$plotoid=$paydetils[0]["plutus_transaction_reference_id"];
	$jobid=$paydetils[0]["job_fk"];
	$amount=$paydetils[0]["amount"];
	
	$getjobdetils = $this->job_model->get_val("SELECT j.id,j.`order_id`,j.`date`,c.`full_name` FROM `job_master` j LEFT JOIN `customer_master` c ON c.`id`=j.`cust_fk` WHERE j.id='$jobid'");
	
	
	$amount=$amount/100;
	$jobdetils=array("plutoid"=>$plotoid,"orderid"=>$getjobdetils[0]["order_id"],"name"=>$getjobdetils[0]["full_name"],"amount"=>$amount,"jobdate"=>date("d-m-Y",strtotime($getjobdetils[0]["date"])));
	
	 echo json_encode(array("status"=>1,"msg"=>"error","plutoid"=>$jobdetils));
	  
	  }
 
	  }else{
		  echo json_encode(array("status"=>1,"msg"=>"error","plutoid"=>"0"));
	  }

	}
function swipe_paymentcheck(){
        if (!is_loggedin()) {
            redirect('login');
        }
		
      $data["login_data"] = logindata();
	  $adminid=$data["login_data"]["id"];
	  $user = $this->user_model->getUser($data["login_data"]["id"]);
	  $pineid=$user->pinelab_fk;
	  if($pineid != "" && $pineid !="0"){
		  
	$machindetils = $this->job_model->get_val("SELECT id,`imei_no`,postcode FROM pinelab_terminal_master WHERE STATUS='1' AND id='$pineid'");
	$imeino=$machindetils[0]["imei_no"]; 
	$postcode=$machindetils[0]["postcode"];
	if($imeino != ""){
		
	  $plutoid = $this->input->get("plutoid");
	  							  
    $error=1;                                                                                                                 
$ch = curl_init('https://www.plutuscloudserviceuat.in:8201/API/CloudBasedIntegration/V1/GetCloudBasedTxnStatus');
  $data_string='{
    "MerchantID": 2137,
    "SecurityToken": "b48894a3-3be5-4618-9312-34c8f79aa6a5",
    "IMEI": "'.$imeino.'",
    "MerchantStorePosCode": "'.$postcode.'",
    "PlutusTransactionReferenceID":'.$plutoid.'
}'; 

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);                                                                   
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data_string))                                                                       
);                                                                                                                   
                                                                                                                     
  $result = curl_exec($ch); 
  $getdata=json_decode($result);
   $msg=$getdata->ResponseMessage;
  if($getdata->ResponseCode==0){
	  
	  $this->job_model->master_fun_insert("pinelabs_log",array("plutus_transaction_reference_id"=>$plutoid,"log"=>$result,"created_time"=>date("Y-m-d H:i:s")));
	  
	   $paydetils = $this->job_model->get_val("SELECT id,job_fk,plutus_transaction_reference_id,amount FROM pinelabs WHERE plutus_transaction_reference_id='$plutoid' AND status='2'");
	   if($paydetils[0]["id"] != ""){
		   
	   $payid=$paydetils[0]["id"];
	   $jid=$paydetils[0]["job_fk"];
	   $amount1=$paydetils[0]["amount"];
	   $amount=($amount1/100);
	   
	   $this->job_model->master_fun_update("pinelabs", array("id", $payid),array("status" =>"1"));
	   
	    $job_details = $this->job_model->master_fun_get_tbl_val("job_master", array("id" => $jid), array("id", "asc"));
        $payable_price = $job_details[0]["payable_amount"];
        $remaining_amount = $payable_price - $amount;
        $this->job_model->master_fun_insert("job_master_receiv_amount", array("payment_type" => "CREDIT CARD swiped thru PINE LABS","job_fk" => $jid, "added_by" => $data["login_data"]["id"], "amount" => $amount, "createddate" => date("Y-m-d H:i:s")));
        $this->job_model->master_fun_update("job_master", array("id", $jid), array("payable_amount" => $remaining_amount));
        $this->job_model->master_fun_insert("job_log", array("job_fk" => $jid, "created_by" => "", "updated_by" => $data["login_data"]["id"], "deleted_by" => "", "job_status" => '', "message_fk" => "22", "date_time" => date("Y-m-d H:i:s")));
		
		$this->session->set_flashdata("amount_history_success", array("Payment Successfully received."));
		
	   }
	   
	 $error=0;
  }
   
   echo json_encode(array("status"=>$error,"msg"=>$msg));
   
   }else{ 
	 echo json_encode(array("status"=>1,"msg"=>"error","plutoid"=>"0"));
	  }
 
	  }else{
		  echo json_encode(array("status"=>1,"msg"=>"error","plutoid"=>"0"));
	  }
 
}
function swipe_paymentcancel($machindetils=null) {
        if (!is_loggedin()) {
            redirect('login');
        }
		$data["login_data"] = logindata();
	  $adminid=$data["login_data"]["id"];
	  $user = $this->user_model->getUser($data["login_data"]["id"]);
	  $pineid=$user->pinelab_fk;
	  if($pineid != "" && $pineid !="0"){
		  
	$machindetils = $this->job_model->get_val("SELECT id,`imei_no`,postcode FROM pinelab_terminal_master WHERE STATUS='1' AND id='$pineid'");
	
		if($machindetils != null){
	  
	  $imeino=$machindetils[0]["imei_no"];
	  $postcode=$machindetils[0]["postcode"];
	  $poterminalid=$machindetils[0]["id"];
	 
	  
	  $paydetils = $this->job_model->get_val("SELECT job_fk,plutus_transaction_reference_id,TransactionNumber,SequenceNumber,amount FROM `pinelabs` WHERE STATUS='2' and pineterminal_id='$poterminalid' ORDER BY id DESC");
	  
	 if($paydetils[0] != ""){
		 
              $plutusid=$paydetils[0]["plutus_transaction_reference_id"];
			  $amount=$paydetils[0]["amount"];                                                                                       
$ch = curl_init('https://www.plutuscloudserviceuat.in:8201/API/CloudBasedIntegration/V1/CancelTransaction');

  $data_string='{
    "MerchantID": 2137,
    "SecurityToken": "b48894a3-3be5-4618-9312-34c8f79aa6a5",
    "IMEI": "'.$imeino.'",
    "MerchantStorePosCode": "'.$postcode.'",
    "PlutusTransactionReferenceID" : '.$plutusid.',
    "Amount" : '.$amount.'
}'; 
  
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);                                                                   
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data_string))                                                                       
);    
  $result = curl_exec($ch); 
  $getdata=json_decode($result);
  $msg=$getdata->ResponseMessage;
  if($getdata->ResponseCode==0){
	  
	  $this->job_model->master_fun_update("pinelabs", array("plutus_transaction_reference_id", $plutusid),array("status" =>"0"));
	  $error=0;
	  
   }else{
	   
	   $error=1;
	   
   }
    echo json_encode(array("status"=>$error,"msg"=>$msg));
  
  
	  }
	  
	  
		}
		
	  }

}

    function cancel_payment() {
        $pine_lab_fk = $this->input->get_post("pine_lab_fk");
        if (!empty($pine_lab_fk)) {
            $data['query'] = $this->master_model->master_fun_get_tbl_val("pinelabs", array("id" => $pine_lab_fk), array("id", "desc"));
            if (!empty($data['query'])) {
                $error = 1;
                $ch = curl_init('https://www.plutuscloudserviceuat.in:8201/API/CloudBasedIntegration/V1/CancelTransaction');
                $payamount = $data['query'][0]["amount"];
                $data_string = '{
    "MerchantID": 2137,
    "SecurityToken": "b48894a3-3be5-4618-9312-34c8f79aa6a5",
    "IMEI": "AIRMEDTEST",
    "MerchantStorePosCode": "03991580",
    "PlutusTransactionReferenceID" : "' . $data['query'][0]["plutus_transaction_reference_id"] . '",
    "Amount" : "' . $payamount . '"
}';
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string))
                );

                $result = curl_exec($ch);
                if (curl_error($ch)) {
                    $msg = 'error:' . curl_error($c);
                }
                $getdata = json_decode($result);
                if ($getdata->ResponseCode == 0) {
                    $error = 1;
                    $plutoid = $getdata->PlutusTransactionReferenceID;
                    $msg = "";
                } else {
                    $error = 0;
                    $msg = $getdata->ResponseMessage;
                }
            } else {
                $error = 0;
                $msg = "Invalid id.Try with valid id.";
            }
        } else {
            $error = 0;
            $msg = "All parameter are required.";
        }
        echo json_encode(array("status" => $error, "msg" => $msg));
    }
function oldjobformula(){
	
	/* $processjobs = $this->job_model->get_val("SELECT 
  u.`job_id`,
  u.`test_id` 
FROM
  user_test_result u
  INNER JOIN `job_master` j ON j.id=u.`job_id`
WHERE u.`status` = '1' AND j.`branch_fk` IN(SELECT id FROM branch_master WHERE STATUS='1' AND `city`='5') AND j.`status`='8'
  AND DATE_FORMAT(u.created_date, '%Y-%m-%d') >= '2018-05-15' 
  AND DATE_FORMAT(u.created_date, '%Y-%m-%d') <= '2018-05-15' 
  AND u.test_id != ''");
  
	foreach($processjobs as $key1){
	  
	  $job_fk=$key1["job_id"];
	  $test_fk=$key1["test_id"];
	  
	  $this->job_model->master_fun_update_multi("use_formula",array("job_fk"=>$job_fk,"test_fk"=>$test_fk,"status"=>'1'),array("test_status"=>'1'));
	  
	  
  }
  echo "ok";
  die(); */
  
	 $approvejobs = $this->job_model->get_val("SELECT 
  a.`job_fk`,a.`test_fk`
FROM
  approve_job_test a 
  INNER JOIN `job_master` j ON j.id=a.`job_fk`
WHERE a.STATUS = '1' AND j.`branch_fk` IN(SELECT id FROM branch_master WHERE STATUS='1' AND `city`='5') AND j.`status`='8' AND DATE_FORMAT(a.created_date, '%Y-%m-%d') >= '2018-05-15' 
  AND DATE_FORMAT(a.created_date, '%Y-%m-%d') <= '2018-05-15' AND a.test_fk !=''");
  foreach($approvejobs as $key){
	  
	  $job_fk=$key["job_fk"];
	  $test_fk=$key["test_fk"];
	  
	  $this->job_model->master_fun_update_multi("use_formula",array("job_fk"=>$job_fk,"test_fk"=>$test_fk,"status"=>'1'),array("test_status"=>'2'));
	  
  } 
  
   echo "ok";
  die();
	
}

}

?>
