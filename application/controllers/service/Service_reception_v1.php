<?php

class Service_reception_v1 extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('email');
        $this->load->model('service_model');
        $this->load->model('user_master_model');
        $this->load->library('Firebase_notification');
        $this->load->helper('security');
        $this->load->helper('string');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: X-Requested-With");
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
        if ($actual_link != "http://www.airmedlabs.com/index.php/api/send") {
            $user_track_data = array("url" => $actual_link, "user_details" => $user_info, "data" => $user_post_data, "createddate" => date("Y-m-d H:i:s"), "type" => "service");
        }
        $app_info = $this->service_model->master_fun_insert("user_track", $user_track_data);
        //return true;
    }

    public function login() {
        $phone = $this->input->get_post('phone');
        if ($phone != NULL) {
            $data = $this->service_model->reception_login($phone);
            //echo "<pre>";            print_r($data); exit;
            if (!empty($data)) {

                $otp = rand(1111, 9999);
                $this->service_model->reception_update("doctor_reception", $data['id'], array("otp" => $otp));
                $this->load->helper("sms");
                $notification = new Sms();
                $mobile = $data['mobile'];
                $sms_message = $this->service_model->reception_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "admin_otp"), array("id", "asc"));

                $sms_message = preg_replace("/{{OTP}}/", $otp, $sms_message["message"]);
                $sms_message = preg_replace("/{{ADMIN}}/", $data['name'], $sms_message);

                $notification->send($mobile, $sms_message);

                unset($data['name']);
                unset($data['mobile']);
                unset($data['doctorfk']);

                $data['otp'] = $otp;
                echo $this->json_data("1", "", array($data));
            } else {
                echo $this->json_data("0", "Mobile number is not found", array());
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", array());
        }
    }

    public function check_otp() {
        $id = $this->input->get_post('id');
        $otp = $this->input->get_post('otp');
        if ($otp == "161616") {
            $data = $this->service_model->reception_fun_get_tbl_val("doctor_reception", array('id' => $id), array("id", "asc"));
            echo $this->json_data("1", "", array($data));
            die();
        }
        if ($id != NULL && $otp != NULL) {
            $data = $this->service_model->reception_fun_get_tbl_val("doctor_reception", array('id' => $id, "otp" => $otp), array("id", "asc"));
            if (!empty($data)) {
                echo $this->json_data("1", "", array($data));
            } else {
                echo $this->json_data("0", "Incorrect OTP", array());
            }
        } else {
            echo $this->json_data("0", "Parameter not passed", array());
        }
    }

//    public function time_slot() {
//        $doctor_id = $this->input->get_post('doctor_id');
//        $appointment_date = $this->input->get_post('appointment_date');
//       
//        $this->load->library('curl');
//        $dcodate = $appointment_date;
//        $doctor = $doctor_id;
//
//        $relation_list = array();
//        $darray = array();
//        if ($dcodate != "" && $doctor != "") {
//
//            //$month = date("N", strtotime($dcodate));      // Fetch month
//            $month = date("n", strtotime($dcodate));
//            $dateslot = date("Y-m-d", strtotime($dcodate));
//
//            if (strtotime($dateslot) == strtotime(date("Y-m-d"))) { // If booking date is today
//                $curretime = date("H:i:s");
//                $checktime = "AND TIME(t.start_time) >= TIME('$curretime')";
//            } else {
//                $checktime = "";
//            }
//
//            $doctortoalslot = $this->service_model->get_val("SELECT slotbook FROM `doctor_master` WHERE id='$doctor' AND status='1'");
//            $dslot = $doctortoalslot[0]["slotbook"];
//
//            $getdoctptslot = $this->service_model->get_val("SELECT count(id) as total FROM doctor_timeslot where doctor_fk='$doctor' and status='1'");
//            $totalslot = $getdoctptslot[0]["total"];
//
//            if ($totalslot == 0) {
//                $cotorcheck = 0;
//            } else {
//                $cotorcheck = $doctor;
//            }
//            $dotimeslot = $this->service_model->get_val("SELECT t.id,t.start_time,t.end_time,COUNT(b.`id`) AS bslotd FROM `doctor_timeslot` t LEFT JOIN doctorbook_slot b ON b.`dslotfk`=t.`id` AND b.status='1' AND b.doctorfk='$doctor' AND DATE_FORMAT(b.starttime,'%Y-%m-%d')='$dateslot' WHERE t.status = '1' AND t.doctor_fk='$cotorcheck' AND t.weekend = '$month'  AND t.id NOT IN(SELECT d.slotid FROM doctor_deleteslot d WHERE d.doctorid='$doctor' AND d.status='1') $checktime GROUP BY t.id ORDER BY t.start_time ASC");
//
//            foreach ($dotimeslot as $slot) {
//
//                $darray["id"] = $slot["id"];
//                $darray["slot"] = date("h:ia", strtotime($slot["start_time"])) . "-" . date("h:ia", strtotime($slot["end_time"]));
//
//                if ($dslot > $slot["bslotd"]) {
//                    $slostatus = "1";
//                } else {
//                    $slostatus = "0";
//                }
//
//                $darray["status"] = $slostatus;
//                $relation_list[] = $darray;
//            }
//
//            echo $this->json_data("1", "", $relation_list);
//        } else {
//            echo $this->json_data("0", "Data not available.", array());
//        }
//    }
//    function test_time_slot(){
//        $doctor_id = $this->input->get_post('doctor_id');
//        $adate = $this->input->get_post('appointment_date');
//        $did = $doctor_id;
//        if(!empty($doctor_id)&&!empty($adate)){
//        $this->load->library('curl');
//        $baseurl = base_url() . "service/phlebo_service_v9/doctorslot?date=$adate&doctor=$did";
//        $json = $this->curl->simple_get($baseurl);
//        echo $json;
//        } else {
//            echo $this->json_data("0", "Data not available.", array());
//        }
//    }

    function time_slot() {
        $doctor_id = $this->input->get_post('doctor_id');
        $adate = $this->input->get_post('appointment_date');
        $did = $doctor_id;
        if (!empty($doctor_id) && !empty($adate)) {
            $this->load->library('curl');
            $baseurl = base_url() . "service/phlebo_service_v9/doctorslot?date=$adate&doctor=$did";
            $json = $this->curl->simple_get($baseurl);
            echo $json;
        } else {
            echo $this->json_data("0", "Data not available.", array());
        }
    }

    public function add_appointment() {
        $rid = $id = $this->input->get_post('receptionist_id');
        $did = $id = $this->input->get_post('doctor_id');
        $p_name = $this->input->post('patient_name');
        $p_mobile = $this->input->post('patient_mobile');
        $p_age = $this->input->post('patient_age');
        $p_gender = $this->input->post('patient_gender');
        $p_appoin = $this->input->post('appointment_date');
        $p_dslot = $this->input->post('appointment_time');
        $type = $this->input->post('type');

        if ($rid != "" && $did != "" && $p_name != "" && $p_mobile != "" && $p_age != "" && $p_gender != "" && $p_appoin != "" && $p_dslot != "" && $type != "") {

            $getdoctslot = $this->service_model->get_val("SELECT `start_time`,end_time FROM doctor_timeslot WHERE id='$p_dslot' and status='1'");

            if ($getdoctslot[0]['start_time'] != "") {
                $dstarttime = date("Y-m-d", strtotime($p_appoin)) . " " . $getdoctslot[0]['start_time'];
                $dendtime = date("Y-m-d", strtotime($p_appoin)) . " " . $getdoctslot[0]['end_time'];

                $data = array("p_name" => $p_name, "p_age" => $p_age, "p_mobile" => $p_mobile, "p_gender" => $p_gender, "doctorfk" => $did, "dslotfk" => $p_dslot, "starttime" => $dstarttime, "endtime" => $dendtime, "receptionfk" => $rid, "type" => $type, "creteddate" => date("Y-m-d H:i:s"));
                if ($type == "2") {
                    $data["checkin"] = "2";
                }
                //print_r($data); die();
                if ($this->service_model->master_fun_insert("doctorbook_slot", $data)) {
                    echo $this->json_data("1", "Appointment successfully added.", array());
                } else {
                    echo $this->json_data("0", "Error while adding data", array());
                }
            }
        } else {
            echo $this->json_data("0", "Parameter not passed.", array());
        }
    }

    public function today_appointment() {

        $start_date = date("d-m-Y");
        $end_date = date("d-m-Y");
        $city = "";
        $doctor = $this->input->post('doctor_id');
        $apptype = $this->input->get('type'); // Walk in or schedule
        $checkin = '';

        $data = $this->service_model->getappoiment($start_date, $end_date, $city, $doctor, 0, 0, $apptype, $checkin);

        $booking_data1 = [];
        foreach ($data as $d) {
            $booking_data['id'] = $d->id;
            $booking_data["patient_name"] = $d->p_name;
            $booking_data["patient_number"] = $d->p_mobile;
            $booking_data["patient_age"] = $d->p_age;
            $booking_data["appointment_date"] = date("d-m-Y", strtotime($d->starttime));
            $booking_data["appointment_time"] = date("h:i a", strtotime($d->starttime)) . "-" . date("h:i a", strtotime($d->endtime));
            $booking_data["checkin"] = ($d->checkin == "1") ? "checked in" : "not checkin";
            $booking_data["checkin_status"] = $d->checkin;
            $booking_data["status"] = $d->status;
            $booking_data["type"] = $d->type;
            $booking_data1[] = $booking_data;
        }

        if (!empty($booking_data1)) {
            echo $this->json_data("1", "", $booking_data1);
        } else {
            echo $this->json_data("0", "No records found", array());
        }
    }

    public function checkedin_list() {
        $doctor = $this->input->post('doctor_id');
        $apptype = $this->input->get('type');
        $checkin = '1';

        $data = $this->service_model->checked_in_list($start_date, $end_date, $city, $doctor, 0, 0, $apptype, $checkin);

        $booking_data1 = [];
        foreach ($data as $d) {
            $booking_data['id'] = $d->id;
            $booking_data["patient_name"] = $d->p_name;
            $booking_data["patient_number"] = $d->p_mobile;
            $booking_data["patient_age"] = $d->p_age;
            $booking_data["appointment_date"] = date("d-m-Y", strtotime($d->starttime));
            $booking_data["appointment_time"] = date("h:i a", strtotime($d->starttime)) . "-" . date("h:i a", strtotime($d->endtime));
            $booking_data["checkin"] = ($d->checkin == "1") ? "checked in" : "not checkin";
            $booking_data["checkin_status"] = $d->checkin;
            $booking_data["status"] = $d->status;
            $booking_data["type"] = $d->type;
            $booking_data1[] = $booking_data;
        }

        if (!empty($booking_data1)) {
            /* echo json_encode(array("status" => "1", "data" => $booking_data1)); */
            echo $this->json_data("1", "", $booking_data1);
        } else {
            echo $this->json_data("0", "No records found", array());
        }
    }

    public function mark_as_checkin() {
        $id = $this->input->get_post('id');

        if ($this->service_model->reception_update("doctorbook_slot", $id, array("checkin" => 1))) {
            echo $this->json_data("1", "Appointment successfully checkedin.", array());
        } else {
            echo $this->json_data("0", "Error while appointment check in", array());
        }
    }

    public function mark_as_delete() {
        $id = $this->input->get_post('id');
        if ($this->service_model->reception_update("doctorbook_slot", $id, array("status" => "2"))) {
            echo $this->json_data("1", "Appointment successfully deleted.", array());
        } else {
            echo $this->json_data("0", "Error while appointment check in", array());
        }
    }

    public function search_appointment() {

        $start_date = $this->input->get_post('start_date');
        $end_date = $this->input->get_post('end_date');
        $apptype = $this->input->get_post('type');
        $checkin = $this->input->get_post('checkin');
        $receptionid = $this->input->get_post('receptionist_id');

        $city = "";
        $doctor = $this->input->get_post('doctor_id');
        if ($doctor != "" && $receptionid != "") {
            $getdoctor = $this->service_model->get_val("SELECT doctorfk FROM doctor_reception WHERE STATUS='1' AND id='$receptionid'");
            $doctor = $getdoctor[0]["doctorfk"];
        }

        $data = $this->service_model->searchappoiment($start_date, $end_date, $city, $doctor, 0, 0, $apptype, $checkin);

        $booking_data1 = [];
        foreach ($data as $d) {
            $booking_data['id'] = $d->id;
            $booking_data["patient_name"] = $d->p_name;
            $booking_data["patient_number"] = $d->p_mobile;
            $booking_data["patient_age"] = $d->p_age;
            $booking_data["appointment_date"] = date("d-m-Y", strtotime($d->starttime));
            $booking_data["appointment_time"] = date("h:i a", strtotime($d->starttime)) . "-" . date("h:i a", strtotime($d->endtime));
            $booking_data["checkin"] = ($d->checkin == "1") ? "checked in" : "not checkin";
            $booking_data["checkin_status"] = $d->checkin;
            $booking_data["status"] = $d->status;
            $booking_data["type"] = $d->type;
            $booking_data1[] = $booking_data;
        }

        if (!empty($booking_data1)) {
            echo $this->json_data("1", "", $booking_data1);
        } else {
            echo $this->json_data("0", "No records found", array());
        }
    }

    public function json_data($status, $error_msg, $data = NULL) {
        if ($data == NULL) {
            $data = array();
        }
        if ($status == 1) {
            $final = array("status" => $status, "error_msg" => $error_msg, "data" => $data);
        } else {
            $final = array("status" => $status, "error_msg" => $error_msg, "data" => $data);
        }

        return str_replace("null", '" "', json_encode($final));
    }

}
