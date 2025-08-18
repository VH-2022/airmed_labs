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
        /* Nishit code start */
        /* Nishit code end */
        $message = urlencode($message);
        $context = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
        //$url = "http://gateway.leewaysoftech.com/xml-transconnect-api.php?username=airmef&password=123123&mobile=" . $configmobile . "&message=" . $message . "&senderid=AIRMED";
        //$xml = file_get_contents($url, false, $context);
        return TRUE;
    }

    function add_message($configmobile, $sms_message) {
        $this->load->model('service_model');
        return $this->service_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $configmobile, "message" => $sms_message, "created_date" => date("Y-m-d H:i:s")));
    }

}
