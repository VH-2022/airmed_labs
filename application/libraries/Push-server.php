<?php
//Usage:
//$message = 'My First Push Notification!';
//$pushServer = new PushServer();
//$pushServer->pushToGoogle('APA91bHqOvx5_EqEp8UUcVOhnhEF9t-AjOe6hddRjADf-hIMytTrvJ_5Wq3iNxXW_mgPmMopGH7_NvMxS_DHXUJiVhSoq3X0aZhIPjIb4mEC3DzLecBJS8w', $message);
//
//echo "<br><br>";
//$pushServer->pushToApple('156ac013bbf1fc369c8eaf8b21876f1a1d47ea13f00aff3b0e09697ca7ab391e', $message);

class PushServer {
    //private $googleApiKey = 'AIzaSyBYgDRG3BX_ckZwJZhcn3qewMsK-hEfP5M';
    private $googleApiKey = 'AIzaSyCrSc9YDS1ToYQj7wZlzOAVDMVo6UFLfYU';
    private $googleApiUrl = 'https://android.googleapis.com/gcm/send';
    private $appleApiUrl = 'ssl://gateway.sandbox.push.apple.com:2195';
    private $privateKey = "pushOhDeals.pem"; //'ck.pem';//'cknew.pem';
    private $privateKeyPassPhrase = '123456'; //'1234';
    public function pushToGoogle($regId, $message) {
echo $regId;
        $fields = array(
            'registration_ids' => array($regId),
            'priority' => 'high',
            'notification' => array("sendername" => "Ohdeals", "message" => $message, "sound" => "default")
        );

        $headers = array(
            'Authorization: key=' . $this->googleApiKey,
            'Content-Type: application/json'
        );
        // Open connection
        json_encode($fields);
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $this->googleApiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'error:' . curl_error($ch);
        }

        // Close connection
        curl_close($ch);
        echo $result;
    }

    public function pushToApple($deviceToken, $message) {

        //echo $deviceToken . "<br/>";
        //echo $message;
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

        // $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        // Send it to the server
echo "here"; die();
        echo $result = fwrite($fp, $msg, strlen($msg));
        // Close the connection to the server
        fclose($fp);
        return $result;
    }

}

?>
