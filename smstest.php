<?php
     $context = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
   
 $mobile="9601198035";

$message='sdfsfsfsfds';
   $url="http://bulksms.analytixmantra.com/sendsms/sendsms.php?username=AMairmedl&password=airmed&type=TEXT&sender=AIRMED&mobile=" . $mobile . "&message=" . $message . "";
   echo   // $url = "http://gateway.leewaysoftech.com/xml-transconnect-api.php?username=airmef&password=air9592&mobile=" . $configmobile . "&message=" . $message . "&senderid=AIRMED";
// $url='http://google.com';
  //echo      $xml = file_get_contents($url, false, $context);
    
     $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);

   echo $data = curl_exec($ch);
    curl_close($ch);

    return $data;
?>