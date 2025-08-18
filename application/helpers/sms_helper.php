<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sms {

    function send($mobile, $message) {
        $mb_length = strlen($mobile);
        $configmobile = $mobile;
        if ($mb_length == 11 || $mb_length == 12 || $mb_length == 13) {
            $check_phone = substr($configmobile, 0, 2);
            $check_phone1 = substr($configmobile, 0, 1);
            $check_phone2 = substr($configmobile, 0, 3);
            if ($check_phone2 == '+91') {
                $get_phone = substr($configmobile, 3);
                $configmobile = $get_phone;
            }
            if ($check_phone == '91') {
                $get_phone = substr($configmobile, 2);
                $configmobile = $get_phone;
            }
            if ($check_phone1 == '0') {
                $get_phone = substr($configmobile, 1);
                $configmobile = $get_phone;
            }
        }
        
        $message = urlencode($message);
        $context = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
        /*$url = "http://mobi1.blogdns.com/WebSMSS/SMSSenders.aspx?UserID=airmed&UserPass=airmed999&&Message=" . $message . "&MobileNo=" . $mobile . "&GSMID=AIRMED";*/
        $url = "http://onlysms.in/httpmsgid/SMSSenders_LANG.aspx?UserID=airmed&UserPass=airmed999&Message=" . $message . "&MobileNo=" . $mobile . "&GSMID=AIRMED";
        $url="http://bulksms.analytixmantra.com/sendsms/sendsms.php?username=AMairmedl&password=airmed&type=TEXT&sender=AIRMED&mobile=" . $mobile . "&message=" . $message . "";
        $url = "http://sms.analytixmantra.com:8080/sendsms/bulksms?username=aotp-AirmedL&password=airmed&type=0&dlr=1&destination=91" . $mobile . "&source=AIRMED&message=" . $message;
        /*$url = "http://gateway.leewaysoftech.com/xml-transconnect-api.php?username=airmef&password=air9592&mobile=" . $configmobile . "&message=" . $message . "&senderid=AIRMED";*/

        
        
        
        $url ="http://bulksms.analyticsmantra.com/sendsms/sendsms.php?username=AirmedL&password=airmed&type=TEXT&sender=AIRMED&mobile=91" . $mobile . "&message=" . $message;
        
        
        
        
        
        $xml = file_get_contents($url, false, $context);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);
        
        return TRUE;
    }

    function send_long_sms($mobile, $message) {
        $mb_length = strlen($mobile);
        $configmobile = $mobile;
        if ($mb_length == 11 || $mb_length == 12 || $mb_length == 13) {
            $check_phone = substr($configmobile, 0, 2);
            $check_phone1 = substr($configmobile, 0, 1);
            $check_phone2 = substr($configmobile, 0, 3);
            if ($check_phone2 == '+91') {
                $get_phone = substr($configmobile, 3);
                $configmobile = $get_phone;
            }
            if ($check_phone == '91') {
                $get_phone = substr($configmobile, 2);
                $configmobile = $get_phone;
            }
            if ($check_phone1 == '0') {
                $get_phone = substr($configmobile, 1);
                $configmobile = $get_phone;
            }
        }
        
        $message = urlencode($message);
        $context = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
        /*$url = "http://mobi1.blogdns.com/WebSMSS/SMSSenders.aspx?UserID=airmed&UserPass=airmed999&&Message=" . $message . "&MobileNo=" . $mobile . "&GSMID=AIRMED";*/
        $url = "http://onlysms.in/httpmsgid/SMSSenders_LANG.aspx?UserID=airmed&UserPass=airmed999&Message=" . $message . "&MobileNo=" . $mobile . "&GSMID=AIRMED";
        $url="http://bulksms.analytixmantra.com/sendsms/sendsms.php?username=AMairmedl&password=airmed&type=TEXT&sender=AIRMED&mobile=" . $mobile . "&message=" . $message . "";
$url = "http://sms.analytixmantra.com:8080/sendsms/bulksms?username=aotp-AirmedL&password=airmed&type=0&dlr=1&destination=91" . $mobile . "&source=AIRMED&message=" . $message;
        /*$url = "http://gateway.leewaysoftech.com/xml-transconnect-api.php?username=airmef&password=air9592&mobile=" . $configmobile . "&message=" . $message . "&senderid=AIRMED";*/




$url ="http://bulksms.analyticsmantra.com/sendsms/sendsms.php?username=AirmedL&password=airmed&type=TEXT&sender=AIRMED&mobile=91" . $mobile . "&message=" . $message;




        $xml = file_get_contents($url, false, $context);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);

        return TRUE;
    }

    function send_long_sms1($mobile, $message) {
        //return true; die();
        $mb_length = strlen($mobile);
        $configmobile = $mobile;
        if ($mb_length == 11 || $mb_length == 12 || $mb_length == 13) {
            $check_phone = substr($configmobile, 0, 2);
            $check_phone1 = substr($configmobile, 0, 1);
            $check_phone2 = substr($configmobile, 0, 3);
            if ($check_phone2 == '+91') {
                $get_phone = substr($configmobile, 3);
                $configmobile = $get_phone;
            }
            if ($check_phone == '91') {
                $get_phone = substr($configmobile, 2);
                $configmobile = $get_phone;
            }
            if ($check_phone1 == '0') {
                $get_phone = substr($configmobile, 1);
                $configmobile = $get_phone;
            }
        }
        $test1 = htmlspecialchars($message, ENT_QUOTES);
        $parts = str_split($test1, 1000);
        //$parts = array_reverse($parts);
        //echo '<textarea>' . $test1 . '</textarea>';
        $sms = "<MESSAGE VER='1.2'>
          <USER USERNAME='airmed' PASSWORD='airmed999' GSMID='AIRMED'
          SchName='Booking SMS' SchDate='" . date("m/d/Y") . "' SchTime='" . date("H:i") . "' />";
        $cnt = 1;
        foreach ($parts as $s_key) {
            $sms .= "<SMS TEXT='" . $s_key . "' ID='" . $cnt . "'>
          <ADDRESS TO='" . $mobile . "' MID='" . $cnt . "'  SEQ='1'/>
          </SMS>";
            $cnt++;
        }
        $sms .= "</MESSAGE>";
        /*  $sms = "<MESSAGE VER='1.2'>
          <USER USERNAME='airmed' PASSWORD='airmed999' GSMID='AIRMED'
          SchName='Booking SMS' SchDate='" . date("m/d/Y") . "' SchTime='" . date("H:i") . "' />
          <SMS TEXT='" . $test1 . "' ID='1'>
          <ADDRESS TO='" . $mobile . "' MID='1'  SEQ='1'/>
          </SMS>
          </MESSAGE>"; */
        $headers = array(
            "Content-Type: text/xml;charset=utf-8",
        );
        $url = 'http://mobi1.blogdns.com/xmlapi/SendSMSMIDSch.aspx';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sms);
        if (curl_errno($ch)) {
            echo curl_errno($ch);
            echo curl_error($ch);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return "DONE";
    }

	
	function sent_whatsapp_sms($mobile, $message){
		 require_once APPPATH.'third_party/Twilio/autoload.php';
			
									
					  
			$sid    = "ACe18edb6d75b9ef29850061f96eadcad0";
			$token  = "1cfbe45ed8baa0ccfb80171b3a4c0892";
			$twilio = new Twilio\Rest\Client($sid, $token);


			$message = $twilio->messages
							   ->create("whatsapp:+91".$mobile, // to
										array(
											"from" => "whatsapp:+14155238886",
											"body" => $message
										)
							   );

	}
}
