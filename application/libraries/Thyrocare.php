<?php

class Thyrocare { 

    private $api_key;
    private $woe = array('MAIN_SOURCE' => 'GUJ86',
        'SUB_SOURCE_CODE' => 'GUJ86',
        'TYPE' => 'ILS',
        'REF_DR_ID' => '',
        'REF_DR_NAME' => 'self',
        'LAB_NAME' => '',
        'ALERT_MESSAGE' => 'COMATOSE PATIENT',
        'PATIENT_NAME' => 'TEST ENTRY',
        'CONTACT_NO' => '',
        'ADDRESS' => '',
        'PINCODE' => '',
        'AGE' => '45',
        'GENDER' => 'F',
        'EMAIL_ID' => '',
        'SPECIMEN_COLLECTION_TIME' => '2016-01-31 01:05:00',
        'BRAND' => 'TTL',
        'TOTAL_AMOUNT' => '200',
        'AMOUNT_COLLECTED' => '565',
        'DELIVERY_MODE' => '1',
        'STATUS' => 'N',
        'REMARKS' => '',
        'WO_STAGE' => '3',
        'LEAD_ID' => '',
        'AMOUNT_DUE' => '-165',
        'CUSTOMER_ID' => '',
        'AGE_TYPE' => 'Y',
        'ORDER_NO' => '',
        'SR_NO' => '1',
        'SPECIMEN_SOURCE' => '',
        'PRODUCT' => 'AAROGYAM 1.2',
        'LAB_ID' => '93370',
        'BCT_ID' => '',
        'LAB_ADDRESS' => '',
        'CAMP_ID' => '',
        'ENTERED_BY' => 'GUJ86',
        'WO_MODE' => 'OFFLINE TOOL',
        'APP_ID' => '10.0.1.0'
    );
    private $woedata = array(
        'woe' => null,
        'barcodelist' => null,
        'api_key' => 'T4MyUthhgjydQTYK2k7kCkUMShmbcgIb3lCNnqI)Ivk=',
        'woe_type' => 'WOE',
            )

    ;
    /*

      array (
      0 =>
      stdClass::__set_state(array(
      'BARCODE' => '12345738',
      'TESTS' => '1,2,3',
      'SAMPLE_TYPE' => 'SERUM',
      'Vial_Image' => '',
      )) */

    public function loadkey($apikey) {
        $this->api_key = $apikey;
    }

    public function getKey($code, $password) {
        //echo json_encode($WOPOSTDATA);

         $url = "https://www.thyrocare.com/API/B2B/COMMON.svc/$code/$password/B2BAPP/PUBLIC/Login";
   
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = "";
        if (($response = curl_exec($ch)) === false) {
            echo die('Curl error: ' . curl_error($ch)); 
            
        } else {
            $response;
        }
        curl_close($ch);
        return $response;
    }

    public function saveWOE($woe, $barcode) {
        ini_set('max_execution_time', 300);
        $customerInfo = $this->woe;
        foreach ($woe as $key => $value) {
            $customerInfo[$key] = $value;
        }
        $WOPOSTDATA = array(
            'woe' => $customerInfo,
            'barcodelist' => $barcode,
            'api_key' => $this->api_key,
            'woe_type' => 'WOE',
        );
        //print_r($WOPOSTDATA); die();
        //echo json_encode($WOPOSTDATA); die();
if(isset($_GET['debug'])){
echo json_encode($WOPOSTDATA); 
}
         $url = "https://www.thyrocare.com/API/B2B/WO.svc/postworkorder";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($WOPOSTDATA));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = "";
        if (($response = curl_exec($ch)) === false) {
            echo die('Curl error: ' . curl_error($ch));
            
        } else {
            //echo $response;
        }
	//	echo $response; die();
        curl_close($ch);
        return $response;
    }
	 public function getRESULT( $date) {
        ini_set('max_execution_time', 300);
        $customerInfo = $this->woe;
        foreach ($woe as $key => $value) {
            $customerInfo[$key] = $value;
        }
		//https://www.thyrocare.com/API/B2B/REPORT.svc/getresults/Z98AceJzS51rYIwHP)RLrz3M5WU4NaGo)bsELEn8lFY=/REPORTED/GUJ86/2017-07-31/ALL/ALL
        
        //print_r($WOPOSTDATA); die();
        //echo json_encode($WOPOSTDATA); die();


          $url = "https://www.thyrocare.com/API/B2B/REPORT.svc/getresults/". $this->api_key."/REPORTED/GUJ86/".$date."/ALL/ALL";
        $ch = curl_init();
         $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


        $result = "";
        if (($response = curl_exec($ch)) === false) {
            echo die('Curl error: ' . curl_error($ch));
            
        } else {
            //echo $response;
        }
        curl_close($ch);
        return  $response; 
    }

}

/*
  $thyrocare= new thyrocare();

  $barcodelist=array(array(
  'BARCODE' => '12345745',
  'TESTS' => '1,2,3',
  'SAMPLE_TYPE' => 'SERUM',
  'Vial_Image' => '',
  ));
  $thyrocare->saveWOE(array(),$barcodelist);

 */
?>