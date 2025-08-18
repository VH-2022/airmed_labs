<?php

/**
 * Usage:
 * $message = 'My First Push Notification!';
 * $pushServer = new PushSerer();
 * $pushServer->pushToGoogle('REG-ID-HERE', $message);
 * $pushServer->pushToApple('DEVICE-TOKEN-HERE', $message);
 */
class PushServer {

    private $googleApiKey = 'AIzaSyCrSc9YDS1ToYQj7wZlzOAVDMVo6UFLfYU';
    private $googleApiUrl = 'https://android.googleapis.com/gcm/send';
    private $appleApiUrl = 'ssl://gateway.sandbox.push.apple.com:2195';
    private $privateKey = 'push.pem';
    private $privateKeyPassPhrase = 'admin';

    public function pushToGoogle($regId, $message) {

       $fields = array(
'registration_ids' => array($regId),
'priority' => 'high',
'data' => $message
);
$headers = array(
'Authorization: key=' . $this->googleApiKey,
'Content-Type: application/json'
);
        // Open connection
       // print_R($headers);
    


        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $this->googleApiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post
         $result = curl_exec($ch);

        // Close connection
        curl_close($ch);
        return $result;
    }

    public function pushToApple($deviceToken, $message) {
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $this->privateKey);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $this->privateKeyPassPhrase);
        // Open a connection to the APNS server
        $fp = stream_socket_client(
                $this->appleApiUrl, $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        echo 'Connected to APNS' . PHP_EOL;
        // Create the payload body
        $body['aps'] = array( 
            'alert' => $message,
            'sound' => 'default',
            'badge' => +1
        );
        // Encode the payload as JSON
        echo $payload = json_encode($body);
        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        // Send it to the server
        echo $result = fwrite($fp, $msg, strlen($msg));
        // Close the connection to the server
        fclose($fp);
        return $result;
    }

    public function pushnotify($deviceToken, $message) {
       $url = 'http://website-demo.in/ipush/push.php?regid=' . $deviceToken . '&message=' . urlencode($message);
     
     
//$cert = 'push.pem';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_SSLCERT, $cert);
//curl_setopt($ch, CURLOPT_SSLCERTPASSWD, "admin");
//curl_setopt($ch, CURLOPT_POSTFIELDS, '{"device_tokens": ["'.$deviceToken.'"], "aps": {"alert": "test message one!"}}');

        $curl_scraped_page = curl_exec($ch);
//if(curl_exec($ch) === false)
//{
//    echo 'Curl error: ' . curl_error($ch);
//}
//else
//{
//    echo 'Operation completed without any errors';
//}
        return $curl_scraped_page;
//print_r($curl_scraped_page);
    }

}

?>
