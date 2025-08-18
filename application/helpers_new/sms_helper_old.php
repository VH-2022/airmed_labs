<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sms {
    function send($mobile,$message) {
        $message = urlencode($message);
        $context = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
        $url = "http://mobi1.blogdns.com/WebSMSS/SMSSenders.aspx?UserID=mohitdemo1&UserPass=mps789&Message=" . $message . "&MobileNo=" . $mobile . "&GSMID=AirmedPathLab";
		//$url = "http://mobi1.blogdns.com/WebSMSS/SMSSenders.aspx?UserID=uicahm&UserPass=ui7878//&Message=" . $message . "&MobileNo=" . $mobile . "&GSMID=AirmedPathLab";
        $xml = file_get_contents($url, false, $context);
        return TRUE;
    }

}
