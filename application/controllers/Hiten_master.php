<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Hiten_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('user_test_master_model');
        $this->load->model('job_model');
        $this->load->model('test_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->model('registration_admin_model');
        $this->load->helper('string');
        $data["login_data"] = logindata();
        $this->app_track();
    }

    function index() {
        
        $jobReports=$this->job_model->get_val('select * from report_master where job_fk=0 order by id desc limit 0,1000');
        foreach($jobReports as $report){
          echo   $reportname= str_replace('_result_wlpd.pdf','', $report['report']);
          $job=$this->job_model->get_val("select id from job_master where order_id= '".$reportname."' order by id desc limit 0,10");
          print_r($job);
          if(isset($job[0])){
              $jobfk=$job[0]['id'];
              $reportid=$report['id'];
               $sql="update report_master set job_fk = $jobfk where id=$reportid";
              echo $this->db->query($sql);
          
          }
            print_r( $report); 
            
        }
        
        
    }
	function whatsapp(){
		echo $test=json_encode($_POST);
		mail("hiten@virtualheight.com","Whatapp",$test);
		echo "done";
	}
	function test2(){

		$this->load->helper("sms");
        $notification = new Sms();
            $notification->sent_whatsapp_sms("7990894194", "Airmed"); 
	}

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function sss() {
        $this->benchmark->mark('code_start');

// Some code happens here

        $this->benchmark->mark('code_end');

        echo $this->benchmark->elapsed_time('code_start', 'code_end');
    }

    function pending_list2() {

        $this->benchmark->mark('code_start');

        $search_data = array();
        $user = $data['user2'] = $search_data["user"] = $this->input->get('user');
        $date = $data['date2'] = $search_data["date"] = $this->input->get('date');
        $end_date = $data['end_date'] = $search_data["end_date"] = $this->input->get("end_date");
        $p_oid = $data['p_oid'] = $search_data["p_oid"] = $this->input->get('p_oid');
        $p_ref = $data['p_ref'] = $search_data["p_ref"] = $this->input->get('p_ref');
        $mobile = $data['mobile'] = $search_data["mobile"] = $this->input->get('mobile');
        $referral_by = $data['referral_by'] = $search_data["referral_by"] = $this->input->get('referral_by');
        $status = $data['statusid'] = $search_data["status"] = $this->input->get('status');
        $branch = $data['branch'] = $search_data["branch"] = $data["branch"] = $this->input->get('branch');
        $payment = $data['payment2'] = $search_data["payment"] = $data["payment"] = $this->input->get('payment');
        $test_pack = $data['test_pack'] = $search_data["test_pack"] = $this->input->get('test_package');
        $city = $data['tcity'] = $search_data["city"] = $this->input->get('city');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status='1'");
        $cntr_arry = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data["login_data"]['branch_fk'] as $key) {
                $cntr_arry[] = $key["branch_fk"];
            }
//print_r($cntr_arry); die();
            $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status='1' and id in (" . implode(",", $cntr_arry) . ")");
        }
        $data['test_cities'] = $this->registration_admin_model->get_val("SELECT * from test_cities where status='1'");
        $test_packages = explode("_", $test_pack);
        $alpha = $test_packages[0];
        $tp_id = $test_packages[1];
        if ($alpha == 't') {
            $t_id = $tp_id;
        }
        if ($alpha == 'p') {
            $p_id = $tp_id;
        }
        $data['success'] = $this->session->flashdata("success");

        $search_data['cntr_arry'] = $cntr_arry;
        $total_row = $this->job_model->num_srch_job_list($cntr_arry);

        $total_row = 500;
        $config["base_url"] = base_url() . "job-master/pending-list";
        $config["total_rows"] = $total_row;
        $config["per_page"] = 100;
        $config['page_query_string'] = TRUE;
        $config['cur_tag_open'] = '<span>';
        $config['cur_tag_close'] = '</span>';
        $config['next_link'] = 'Next &rsaquo;';
        $config['prev_link'] = '&lsaquo; Previous';
        $this->pagination->initialize($config);
        $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;


        $res = $this->job_model->srch_job_list_get_id($config["per_page"], $page, $search_data);

        $datapass = array();
        foreach ($res as $r) {
            $datapass[] = $r['id'];
        }
        $search_data['idofdata'] = $datapass;
        $data['query'] = $this->job_model->srch_job_list($config["per_page"], $page, $search_data);

        $data["links"] = $this->pagination->create_links();
        $data["pages"] = $page;

        $data['customer'] = $this->job_model->master_fun_get_tbl_val("customer_master", array('status' => 1), array("full_name", "asc"));
        $data['city'] = $this->job_model->master_fun_get_tbl_val("city", array('status' => 1), array("id", "asc"));

        $cnt = 0;
        foreach ($data['query'] as $key) {
            $w_prc = 0;
            /* Count booked test price */
            $booked_tests = $this->job_model->master_fun_get_tbl_val("wallet_master", array('job_fk' => $key["id"]), array("id", "asc"));
            $emergency_tests = $this->job_model->master_fun_get_tbl_val("booking_info", array('id' => $key["booking_info"]), array("id", "asc"));
            $f_data = $this->job_model->master_fun_get_tbl_val("customer_family_master", array('id' => $emergency_tests[0]["family_member_fk"]), array("id", "asc"));
            $f_data1 = $this->job_model->master_fun_get_tbl_val("relation_master", array('id' => $f_data[0]["relation_fk"]), array("id", "asc"));
            $doctor_data = $this->job_model->master_fun_get_tbl_val("doctor_master", array('id' => $key["doctor"]), array("id", "asc"));
            $relation = "Self";
            if (!empty($f_data1)) {
                $relation = ucfirst($f_data[0]["name"] . " (" . $f_data1[0]["name"] . ")");
                $data['query'][$cnt]["rphone"] = $f_data[0]["phone"];
            }
            $data['query'][$cnt]["relation"] = $relation;
            foreach ($booked_tests as $tkey) {
                if ($tkey["debit"]) {
                    $w_prc = $w_prc + $tkey["debit"];
                }
            }
            $upload_data = $this->job_model->master_fun_get_tbl_val("report_master", array('job_fk' => $key["id"]), array("id", "asc"));
            $data['query'][$cnt]["report"] = $upload_data[0]["original"];
            $data['query'][$cnt]["emergency"] = $emergency_tests[0]["emergency"];
            $data['query'][$cnt]["cut_from_wallet"] = $w_prc;
            $data['query'][$cnt]["doctor_name"] = $doctor_data[0]["full_name"];
            $data['query'][$cnt]["doctor_mobile"] = $doctor_data[0]["mobile"];
            $package_ids = $this->job_model->get_job_booking_package($key["id"]);
            if (trim($package_ids) != '') {
                $data['query'][$cnt]["packagename"] = $package_ids;
            }
            $cnt++;
        }
        $url = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        $this->session->set_userdata("job_master_r", $url);

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('pending_job_list', $data);
        $this->load->view('footer');

        $this->benchmark->mark('code_end');

        echo "<h1>" . $this->benchmark->elapsed_time('code_start', 'code_end') . "</h1>";
    }

    function nishit_test() {

        $headers = array(
            "Content-Type: text/xml;charset=utf-8",
        );
        $url = 'http://mobi1.blogdns.com/xmlapi/SendSMSMIDSch.aspx';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);


        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '<MESSAGE VER="1.2">
          <USER USERNAME="airmed" PASSWORD="airmed999" GSMID="AIRMED"
          SchName="Booking SMS" SchDate="09/26/2017" SchTime="17:45" />
          <SMS TEXT="Patient Name:  PRIYA  R  CHANDWANI (AHM-3142) 

CBC 
HEMOGLOBIN :-  13.5 [12.0 - 16.0] 
 Total RBC Count :-  4.71 [4.2 - 6.2] 
 TOTAL WBC COUNT :-  6330 [4000 - 10000] 
 Platelet Count :-  2,61,000 [150000 - 450000] 
 H.CT :-  36.9 [26 - 50] 
 M. C. V :-  78.34 [80 - 96] 
 M.C.H. :-  28.66 [26 - 38] 
 M.C.H.C. :-  36.59 [31 - 37] 
 R.D.W :-  12.5 [11.6 - 14.6] 
 Neutrophils :-  49 [40 - 70] 
 Lymphocytes :-  45 [20 - 40] 
 Monocytes :-  01 [0 - 10] 
 Eosinophils :-  05 [1 - 7] 
 Basophils :-  00 [0 - 2] 
 Smear Study - RBC :-  RBC\'s are Normocytic and Normochromic , 
 Smear Study - WBC :-  WBC count is normal. 
 Smear Study - Platelets :-  Platelets are adequate 
 Smear Study - PS for MP :-  No Blood Parasites are seen. 
 
 Widal 
Widal Test :-  Slide Method 
 Salmonella typhi O :-  1:320 
 Salmonella Typhi H :-  1:120 
 RESULT :-  The test is POSITIVE 
 
 URINE ROUTINE EXAMINATION 
Volume :-  40 ML 
 Colour  :-  Yellow 
 Appearance :-  Clear 
 PH :- 6.0Reaction :-  Acidic 
 Sp. Gravity :-  1.015 
 Deposit :-  Absent 
 Protein :-  Nil 
 Glucose  :-  Nil 
 Bile Salts :-  Absent 
 Bile Pigments :-  Absent 
 Blood :-  Absent 
 Pus Cells :- 1-2Red Cells :- NILEpithelial Cells :- 4-6Crystals :-  Absent 
 Amorphous material :-  Absent 
 Casts :-  Absent 
 Trichomonas vaginalis :-  Absent 
 Fungus :-  Absent 
 Bacteria :-  Absent 
 Spermatozoa :-  Absent 
 

CBC 
HEMOGLOBIN :-  13.5 [12.0 - 16.0] 
 Total RBC Count :-  4.71 [4.2 - 6.2] 
 TOTAL WBC COUNT :-  6330 [4000 - 10000] 
 Platelet Count :-  2,61,000 [150000 - 450000] 
 H.CT :-  36.9 [26 - 50] 
 M. C. V :-  78.34 [80 - 96] 
 M.C.H. :-  28.66 [26 - 38] 
 M.C.H.C. :-  36.59 [31 - 37] 
 R.D.W :-  12.5 [11.6 - 14.6] 
 Neutrophils :-  49 [40 - 70] 
 Lymphocytes :-  45 [20 - 40] 
 Monocytes :-  01 [0 - 10] 
 Eosinophils :-  05 [1 - 7] 
 Basophils :-  00 [0 - 2] 
 Smear Study - RBC :-  RBC\'s are Normocytic and Normochromic , 
 Smear Study - WBC :-  WBC count is normal. 
 Smear Study - Platelets :-  Platelets are adequate 
 Smear Study - PS for MP :-  No Blood Parasites are seen. 
 
 Widal 
Widal Test :-  Slide Method 
 Salmonella typhi O :-  1:320 
 Salmonella Typhi H :-  1:120 
 RESULT :-  The test is POSITIVE 
 
 URINE ROUTINE EXAMINATION 
Volume :-  40 ML 
 Colour  :-  Yellow 
 Appearance :-  Clear 
 PH :- 6.0Reaction :-  Acidic 
 Sp. Gravity :-  1.015 
 Deposit :-  Absent 
 Protein :-  Nil 
 Glucose  :-  Nil 
 Bile Salts :-  Absent 
 Bile Pigments :-  Absent 
 Blood :-  Absent 
 Pus Cells :- 1-2Red Cells :- NILEpithelial Cells :- 4-6Crystals :-  Absent 
 Amorphous material :-  Absent 
 Casts :-  Absent 
 Trichomonas vaginalis :-  Absent 
 Fungus :-  Absent 
 Bacteria :-  Absent 
 Spermatozoa :-  Absent 
 " ID="1">
          <ADDRESS TO="9601198035" MID="1"  SEQ="1"/>
          </SMS>
          </MESSAGE>
          ');
        if (curl_errno($ch)) {
            echo curl_errno($ch);
            echo curl_error($ch);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        print_r($result);
        die("OK");



        /*
          $input_xml = '<MESSAGE VER="1.2">
          <USER USERNAME="airmed" PASSWORD="airmed999" GSMID="AIRMED"
          SchName="Booking SMS" SchDate="09/27/2017" SchTime="15:15" />
          <SMS TEXT="This is first sms" ID="1">
          <ADDRESS TO="9879572294" MID="1"  SEQ="1"/>
          </SMS>
          </MESSAGE>';


          $url = "http://mobi1.blogdns.com/xmlapi/SendSMSMIDSch.aspx";

          //setting the curl parameters.
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          // Following line is compulsary to add as it is:
          curl_setopt($ch, CURLOPT_POSTFIELDS, "data=" . $input_xml);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3000);
          $data = curl_exec($ch);
          curl_close($ch);
          echo $data;
          //convert the XML result into array
          //$array_data = json_decode(json_encode(simplexml_load_string($data)), true);

          print_r('<pre>');
          print_r($array_data);
          print_r('</pre>');
         */
    }

    function send_cbc_sms() {

        $doctor_list = $this->job_model->get_val("SELECT * FROM admin_alert_sms where id='11930'");

        $headers = array(
            "Content-Type: text/xml;charset=utf-8",
        );
        echo $mobile = "9879572294";
        echo $url = 'http://mobi1.blogdns.com/xmlapi/SendSMSMIDSch.aspx';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        echo "</br>";
        $test = str_replace("'", "", $doctor_list[0]['message']);

        $test = str_replace("&", "-", $test);



        $test = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $test)));

        $test1 = htmlspecialchars($doctor_list[0]['message'], ENT_QUOTES);


        $parts = str_split($test1, 1000);

        $parts = array_reverse($parts);
        print_r($parts);
        die();
        echo '<textarea>' . $test1 . '</textarea>';
        $sms = "<MESSAGE VER='1.2'>
          <USER USERNAME='airmed' PASSWORD='airmed999' GSMID='AIRMED'
          SchName='Booking SMS' SchDate='" . date("m/d/Y") . "' SchTime='" . date("H:i") . "' />";
        $cnt = 1;
        foreach ($parts as $s_key) {
            $sms .= "<SMS TEXT='" . $s_key . "' ID='" . $cnt . "'>
          <ADDRESS TO='9879572294' MID='" . $cnt . "'  SEQ='1'/>
          </SMS>";
            $cnt++;
        }
        $sms .= "</MESSAGE>";

        //echo $sms; die();

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sms);
        if (curl_errno($ch)) {
            echo curl_errno($ch);
            echo curl_error($ch);
        }
        echo $result = curl_exec($ch);
        print_r($result);
        curl_close($ch);
    }

    function merge_doctor() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('final', 'final', 'trim|required');
        $this->form_validation->set_rules('repeat', 'repeat', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $final_list = $this->input->get_post("final");
            $doctor_list = explode(",", $this->input->get_post("repeat"));
            foreach ($doctor_list as $key) {
                //echo $key."<br>".$final_list; die();
                $this->master_model->master_fun_update1("job_master", array("doctor" => $key), array("doctor" => $final_list));
                $this->master_model->master_fun_update1("doctor_master", array("id" => $key), array("status" => "0"));
                $this->session->set_flashdata("hsuccess", "done");
            }
            redirect("hiten_master/merge_doctor");
        } else {
            ?>
            <span style="color:green"><?= $this->session->flashdata("hsuccess"); ?></span>
            <?php echo form_open("Hiten_master/merge_doctor", array("method" => "POST", "role" => "FORM")); ?>   
            Final Id:- <input type="text" name="final"/><br>
            Repeat Id:- <textarea name="repeat"cols="42" rows="6"></textarea><br>
            <input type="submit" value="Update"/>
            <?php echo form_close(); ?>
            <?php
        }
    }

    public function sendreport() {

        $config['mailtype'] = 'html';
        $to = $this->input->post("semail");
        $message = $this->input->post("message1");
        $filename = $this->input->post("filename");
        /* $to = "nishit.patel@airmedlabs.com"; */
        $subject = $this->input->post("subject");

        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'linux3.hostrs.com',
            'smtp_port' => 587, //465,
            'smtp_user' => 'developer@website-demo.co.in',
            'smtp_pass' => 'developer#000#',
            'smtp_crypto' => 'tls',
            'smtp_timeout' => '20',
            'mailtype' => 'html',
            'charset' => 'iso-8859-1'
        );
        $config['newline'] = "\r\n";
        $config['crlf'] = "\r\n";
        $this->load->library('email', $config);
        $this->email->from('nishit.patel@airmedlabs.com', 'Developer Test Mail');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->attach($filename);
        //$this->email->send();
        if (!$this->email->send()) {
            return false;
            echo $this->email->print_debugger();
        }
    }

    public function send_email() {
        $to = "nishit@virtualheight.com";
        $subject = "Test";
        $message = "This is test mail.";
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'linux3.hostrs.com',
            'smtp_port' => 587, //465,
            'smtp_user' => 'developer@website-demo.co.in',
            'smtp_pass' => 'developer#000#',
            'smtp_crypto' => 'tls',
            'smtp_timeout' => '20',
            'mailtype' => 'html',
            'charset' => 'iso-8859-1'
        );
        $config['newline'] = "\r\n";
        $config['crlf'] = "\r\n";
        $this->load->library('email', $config);
        $this->email->from('nishit.patel@airmedlabs.com', 'Developer Test Mail');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        //$this->email->send();
        if (!$this->email->send()) {
            return false;
        }
        return true;
    }

    function settle_b2b_jobs() {
        $job_data = $this->job_model->get_val("
SELECT 
  sample_job_master.*,
  logistic_log.`collect_from`,
  collect_from.`name`,
  collect_from.`city`,
  collect_from.`test_discount` 
FROM
  sample_job_master 
  INNER JOIN `logistic_log` 
    ON `logistic_log`.`id` = sample_job_master.`barcode_fk` 
    INNER JOIN `collect_from` ON `collect_from`.id=`logistic_log`.`collect_from`
WHERE `logistic_log`.`status` = '1' 
  AND `sample_job_master`.`status` = '1' 
  AND `sample_job_master`.`date` >= '2017-12-01 00:00:00' 
  AND `sample_job_master`.`date` <= '2017-12-08 23:59:59' ");
        $final_array = array();
        foreach ($job_data as $key) {
            $job_test_data = $this->job_model->master_fun_get_tbl_val("sample_job_test", array('status' => 1, "job_fk" => $key["id"]), array("id", "desc"));
            $job_price = 0;
            $new_test_array = array();
            $problem = 0;
            foreach ($job_test_data as $key1) {
                //echo "<pre>";print_R($key1["testtype"]); die();
                if ($key1["testtype"] == 1) {

                    $result = $this->job_model->get_val("select p.price,(SELECT price_special FROM b2b_testspecial_price s  WHERE  s.test_fk=t.id AND s.status='1' and s.typetest='1' AND s.lab_id='" . $key["collect_from"] . "') AS specialprice,(SELECT price_special FROM b2b_testmrp_price m  WHERE  m.test_fk=t.id AND m.status='1' AND m.typetest='1' AND m.lab_id='" . $key["collect_from"] . "') AS mrpprice from test_master t LEFT JOIN test_master_city_price p ON t.id = p.test_fk  where t.status='1' AND p.city_fk='" . $key["city"] . "' and t.id='" . $key1["test_fk"] . "' GROUP BY t.id");

                    $mrp = round($result[0]['price']);
                    if ($result[0]['mrpprice'] != "") {
                        $mrp = $result[0]['mrpprice'];
                    }

                    if ($result[0]['specialprice'] != "") {
                        $price1 = $result[0]['specialprice'];
                    } else {
                        $discount1 = $key["test_discount"];
                        $discountprice = ($mrp * $discount1 / 100);
                        $price1 = $mrp - $discountprice;
                    }
                    $testtotal[] = round($price1);
                }
                if ($key1["testtype"] == 2) {

                    $result = $this->job_model->get_val("select p.d_price as price,(SELECT price_special FROM b2b_testspecial_price s  WHERE  s.test_fk=t.id AND s.status='1' and s.typetest='2' AND s.lab_id='" . $key["collect_from"] . "') AS specialprice,(SELECT price_special FROM b2b_testmrp_price m  WHERE  m.test_fk=t.id AND m.status='1' AND m.typetest='2' AND m.lab_id='" . $key["collect_from"] . "') AS mrpprice from package_master t LEFT JOIN package_master_city_price p ON t.id = p.package_fk  where t.status='1' AND p.city_fk='" . $key["city"] . "' and t.id='" . $key1["test_fk"] . "' GROUP BY t.id");

                    $mrp = round($result[0]['price']);
                    if ($result[0]['mrpprice'] != "") {
                        $mrp = $result[0]['mrpprice'];
                    }

                    if ($result[0]['specialprice'] != "") {
                        $price1 = $result[0]['specialprice'];
                    } else {
                        $discount1 = $key["test_discount"];
                        $discountprice = ($mrp * $discount1 / 100);
                        $price1 = $mrp - $discountprice;
                    }
                    $testtotal[] = round($price1);
                }

                if ($key1["price"] != $price1) {
                    $problem = 1;
                }
                $key1["new_price"] = round($price1);
                $new_test_array[] = $key1;
                $job_price += round($price1);
            }
            if ($key["payable_amount"] != $job_price) {
                $problem = 1;
            }
            $key["problem"] = $problem;
            $key["new_price"] = $job_price;
            $key["tests"] = $new_test_array;
            $final_array[] = $key;
        }

        echo "<pre>";
        print_R($final_array);
        die();
        foreach ($final_array as $kkey) {
            if ($kkey["problem"] == 1) {

                $this->job_model->master_fun_update("sample_job_master", array("id", $kkey["id"]), array("price" => $kkey["new_price"], "payable_amount" => $kkey["new_price"]));
                //print_r($kkey); die();
                //print_r(array("price" => $kkey["new_price"], "payable_amount" => $kkey["new_price"]));print_r(array("id", $kkey["id"])); die("THis is testing");
                foreach ($kkey["tests"] as $tkey) {
                    $this->job_model->master_fun_update("sample_job_test", array("id", $tkey["id"]), array("price" => $tkey["new_price"]));
                }
                //print_r($kkey); die();
            }
        }
        echo "Changes are done.";
    }

    function export_csv() {
        $search_data = array();
        $plm = $data['plm2'] = $search_data["plm"] = $this->input->get('plm');
        $user = $data['user2'] = $search_data["user"] = $this->input->get('user');
        $date = $data['date2'] = $search_data["date"] = $this->input->get('date');
        $end_date = $data['end_date'] = $search_data["end_date"] = $this->input->get("end_date");
        $p_oid = $data['p_oid'] = $search_data["p_oid"] = $this->input->get('p_oid');
        $p_ref = $data['p_ref'] = $search_data["p_ref"] = $this->input->get('p_ref');
        $mobile = $data['mobile'] = $search_data["mobile"] = $this->input->get('mobile');
        $referral_by = $data['referral_by'] = $search_data["referral_by"] = $this->input->get('referral_by');
        $status = $data['statusid'] = $search_data["status"] = $this->input->get('status');
        $branch = $data['branch'] = $search_data["branch"] = $data["branch"] = $this->input->get('branch');
        $payment = $data['payment2'] = $search_data["payment"] = $data["payment"] = $this->input->get('payment');
        $test_pack = $data['test_pack'] = $search_data["test_pack"] = $this->input->get('test_package');
        $city = $data['tcity'] = $search_data["city"] = $this->input->get('city');
        $withsample = $data['withsample'] = $search_data["withsample"] = $this->input->get_post('withsample');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status='1'");
        $cntr_arry = array();
        if (!empty($data["login_data"]['branch_fk'])) {
            foreach ($data["login_data"]['branch_fk'] as $key) {
                $cntr_arry[] = $key["branch_fk"];
            }
            $data['branchlist'] = $this->registration_admin_model->get_val("SELECT * from branch_master where status='1' and id in (" . implode(",", $cntr_arry) . ")");
        }
        $data['test_cities'] = $this->registration_admin_model->get_val("SELECT * from test_cities where status='1'");
        $test_packages = explode("_", $test_pack);
        $alpha = $test_packages[0];
        $tp_id = $test_packages[1];
        if ($alpha == 't') {
            $t_id = $tp_id;
        }
        if ($alpha == 'p') {
            $p_id = $tp_id;
        }
        if ($branch != '') {
            $cntr_arry = array();
            $cntr_arry = $branch;
        }
        /* PLM start */
        if (empty($branch) && $plm != '') {
            if (empty($branch) && !empty($city) && empty($plm)) {
                $plm_branch = $this->job_model->get_val("SELECT GROUP_CONCAT(id) AS id FROM `branch_master` WHERE `status`='1' AND city='" . $city . "' GROUP BY status");
            }
            if (empty($branch) && !empty($plm)) {
                $plm_branch = $this->job_model->get_val("select GROUP_CONCAT(id) AS id from branch_master where status='1' and (parent_fk ='" . $plm . "' or id='" . $plm . "') GROUP BY status");
            }
            if (empty($branch) && empty($city) && empty($plm)) {
                $plm_branch = $this->job_model->get_val("select GROUP_CONCAT(id) AS id from branch_master where status='1' GROUP BY status");
            }
            $plm_branch = explode(",", $plm_branch[0]["id"]);
            $cntr_arry = array_merge(array($plm), $plm_branch);
        } else {
            $cntr_arry = $branch;
        }
        /* PLM END */
        $search_data['cntr_arry'] = $cntr_arry;
        $search_data['t_id'] = $t_id;
        $search_data['p_id'] = $p_id;

        $result = $this->job_model->csv_job_list($search_data);
        //die("OK");
        /* Nishit code start */

        $new_array = array();
        foreach ($result as $kkey) {
            $job_test_list = $this->job_model->get_val("SELECT `job_test_list_master`.*,`test_master`.`test_name` FROM `job_test_list_master` INNER JOIN `test_master` ON `job_test_list_master`.`test_fk`=`test_master`.`id` WHERE `job_test_list_master`.`job_fk`='" . $kkey["id"] . "'");
            /* Check sub test start */
            $job_tst_lst = array();
            $job_sub_tst_lst = array();
            foreach ($job_test_list as $st_key) {
                //echo $st_key['test_fk'];
                $job_sub_test_list = $this->job_model->get_val("SELECT `sub_test_master`.test_fk,`sub_test_master`.`sub_test`,test_master.`test_name` FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`sub_test`=`test_master`.`id` WHERE `sub_test_master`.`status`='1' AND `test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $st_key['test_fk'] . "'");
                $job_sub_tst_lst[] = $job_sub_test_list;
                $st_key["sub_test"] = $job_sub_test_list;
                $job_tst_lst[] = $st_key;
            }

            $package_ids = $this->job_model->get_job_booking_package($kkey["id"]);
            $is_show = 1;
            $test_list = array();
            $package_list = array();
            if (!empty($test_pack)) {
                $is_show = 0;
                foreach ($job_test_list as $f_key) {
                    if (!in_array($f_key["test_name"], $test_list)) {
                        $test_list[] = $f_key["test_name"];
                    }
                    if (strpos(trim(strtoupper($f_key["test_name"])), trim(strtoupper($test_pack))) !== false) {
                        $is_show = 1;
                    }
                }

                foreach ($package_ids as $p_key) {
                    $package_list[] = $p_key["name"];
                    if (strpos(trim(strtoupper($p_key["name"])), trim(strtoupper($test_pack))) !== false) {
                        $is_show = 1;
                    }
                    foreach ($p_key["test"] as $p_key) {
                        if (!in_array($p_key["test_name"], $test_list)) {
                            $test_list[] = $p_key["test_name"];
                        }
                        if (strpos(trim(strtoupper($p_key["test_name"])), trim(strtoupper($test_pack))) !== false) {
                            $is_show = 1;
                        }
                    }
                }

                foreach ($job_sub_tst_lst as $p_key) {
                    foreach ($p_key as $ss_p_key) {
                        if (!in_array($ss_p_key["test_name"], $test_list)) {
                            $test_list[] = $ss_p_key["test_name"];
                        }
                        if (strpos(trim(strtoupper($ss_p_key["test_name"])), trim(strtoupper($test_pack))) !== false) {
                            $is_show = 1;
                        }
                    }
                }
            } else {
                $is_show = 0;
                foreach ($job_test_list as $f_key) {
                    if (!in_array($f_key["test_name"], $test_list)) {
                        $test_list[] = $f_key["test_name"];
                    }
                    $is_show = 1;
                }

                foreach ($package_ids as $p_key) {
                    $package_list[] = $p_key["name"];
                    foreach ($p_key["test"] as $p_key) {
                        if (!in_array($p_key["test_name"], $test_list)) {
                            $test_list[] = $p_key["test_name"];
                        }
                        $is_show = 1;
                    }
                }

                foreach ($job_sub_tst_lst as $p_key) {
                    foreach ($p_key as $ss_p_key) {
                        if (!in_array($ss_p_key["test_name"], $test_list)) {
                            $test_list[] = $ss_p_key["test_name"];
                        }
                        $is_show = 1;
                    }
                }
            }
            /* END */
            $kkey["is_show"] = $is_show;
            $coma = "";
            if (!empty($package_list)) {
                $coma = ", ";
            }
            $kkey["test_name"] = implode(", ", $package_list) . $coma . implode(", ", $test_list);
            $new_array[] = $kkey;
        }
        /* echo "<pre>";
          print_r($new_array);
          die(); */
        $branch_list = $this->job_model->get_val("select * from branch_master where status!='0' order by id asc");
        $branch_wise_list = array();
        foreach ($branch_list as $bb_key) {
            $branch_total = 0;
            $branch_net = 0;
            foreach ($new_array as $key11234) {
                if ($bb_key["id"] == $key11234["branch_fk"]) {
                    $discount = 0;
                    if ($key11234["discount"] > 0) {
                        $discount = round($key11234["price"] * $key11234["discount"] / 100);
                    }
                    $dabitt_from_wallet = $this->job_model->get_val("SELECT IF(SUM(`debit`)>0,SUM(`debit`),0) AS dabit FROM `wallet_master` WHERE `job_fk`='" . $key11234["id"] . "' AND `status`='1'");
                    $due = round($key11234["price"] - $key11234["payable_amount"] - $discount - $dabitt_from_wallet[0]["dabit"]);
                    $branch_total += $key11234["price"];
                    $branch_net += $due;
                    //die("OK1");
                }
            }
            $bb_key["received_amount"] = array("total_revenue" => $branch_total, "net_amount" => $branch_net);
            $branch_wise_list[] = $bb_key;
        }
        echo "<pre>";
        print_r($branch_wise_list);
        die();
        /* Nishit code end */
        if (!isset($_REQUEST['de'])) {
            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"All_Jobs_Report-" . date('d-M-Y') . ".csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle = fopen('php://output', 'w');
            fputcsv($handle, array("No.", "Reg No.", "Order Id", "Test City", "Branch", "Date", "Patient Name", "Mobile No.", "Doctor", "Test/Package Name", "Job Status", "Payment Type", "Sample From", "New Patient/old Patient", "Portal", "Remark", "Added By", "Total Price", "Discount(RS.)", "Received Amount", "Cash", 'Card', "Payumoney", "CHEQUE", "Debited From Wallet", "Creditor Remain", "Due Amount"));
        }
        /* Nishit code start */
        $cnt = 1;
        foreach ($new_array as $key) {
            if ($key["is_show"] == 1) {
                if ($key['status'] == 1) {
                    $j_status = "Waiting For Approval";
                }
                if ($key['status'] == 6) {
                    $j_status = "Approved";
                }
                if ($key['status'] == 7) {
                    $j_status = "Sample Collected";
                }
                if ($key['status'] == 8) {
                    $j_status = "Processing";
                }
                if ($key['status'] == 2) {
                    $j_status = "Completed";
                }
                $sample_collected = 'No';
                if ($key["sample_collection"] == 1) {
                    $sample_collected = 'Yes';
                }
                $addr = '';
                if (!empty($key["address"])) {
                    $addr = $key["address"];
                } else {
                    $addr = $key["address1"];
                }
                if (!$key["payable_amount"]) {
                    $key["payable_amount"] = 0;
                }
                /* Nishit 18-08-2017 START */
                $payment_mode = array();
                $creditor_remark = array();
                $discount = 0;
                if ($key["discount"] > 0) {
                    $discount = round($key["price"] * $key["discount"] / 100);
                }
                $added_by = "Online";
                if (!empty($key["phlebo_added_by"])) {
                    $added_by = $key["phlebo_added_by"] . " (Phlebo)";
                } else if (!empty($key["added_by"])) {
                    $added_by = $key["added_by"];
                }
                $cash = 0.0;
                $card = 0.0;
                $cheque = 0.0;
                $other = 0.0;
                $collection_type = $this->job_model->get_val("SELECT REPLACE (GROUP_CONCAT(remark,','), 'credited by', 'Creditor Name : ') AS remark, (`payment_type`) AS payment_type,sum(job_master_receiv_amount.amount) as amount FROM `job_master_receiv_amount` WHERE `status`='1' AND job_fk='" . $key["id"] . "' GROUP BY payment_type ORDER BY payment_type ASC");

                if (count($collection_type) > 0) {
                    foreach ($collection_type as $ct) {
                        if (strtoupper($ct["payment_type"]) == "CASH") {
                            $payment_mode[] = "CASH";
                            $cash += $ct["amount"];
                        } else if (in_array(strtoupper($ct["payment_type"]), array("DEBIT CARD SWIPED THRU ICICI", "DEBIT CARD", "DEBIT CARD", "CREDIT CARD", "PayTm", "PAYTM"))) {
                            $payment_mode[] = "CARD";
                            $card += $ct["amount"];
                            //	die("hiten");
                        } else if (strtoupper($ct["payment_type"]) == "CHEQUE") {
                            $payment_mode[] = "CHEQUE";
                            $cheque += $ct["amount"];
                        } else if (strtoupper($ct["payment_type"]) == "CREDITORS") {
                            $creditor_remark[] = $ct["remark"];
                            //$cheque+=$ct["amount"];
                        }
                        /* else {
                          $other += $ct["amount"];
                          } */
                    }
                }
                $creditor = $this->job_model->get_val("SELECT credit,debit,paid   FROM `creditors_balance` WHERE status=1 and job_id=" . $key["id"]);

                $creditor_cash_collected = 0.0;
                $creditor_cash_due = 0.0;



                if (count($creditor) > 0) {
                    foreach ($creditor as $ct) {
                        if (($ct["credit"]) > 0) {
                            $payment_mode[] = "CREDITOR CREDIT";
                            $creditor_cash_collected += $ct["credit"];
                            $cash += $ct["credit"];
                        }
                        if (($ct["debit"]) > 0) {
                            $payment_mode[] = "CREDITOR DEBIT";
                            $creditor_cash_due += $ct["debit"];
                        }
                    }
                }
                $creditor_cash_due = $creditor_cash_due - $creditor_cash_collected;
                $payumoneyRecord = $this->job_model->get_val("SELECT SUM(amount) as amount FROM `payment` WHERE STATUS='success' AND job_fk=" . $key["id"]);
                $payumoney = 0.0;

                if (count($payumoneyRecord) > 0) {
                    foreach ($payumoneyRecord as $ct) {
                        $payumoney += $ct["amount"];
                    }
                }

                /* Nishit Code for other payment27-09-2017 */

                /* END */

                $dabitt_from_wallet = $this->job_model->get_val("SELECT IF(SUM(`debit`)>0,SUM(`debit`),0) AS dabit FROM `wallet_master` WHERE `job_fk`='" . $key["id"] . "' AND `status`='1'");
                $due = round($key["price"] - $key["payable_amount"] - $discount - $dabitt_from_wallet[0]["dabit"]);
                if ($dabitt_from_wallet[0]["dabit"] > 0) {
                    $payment_mode[] = "WALLET";
                }
                if (strtoupper($key["payment_type"]) == "PAYUMONEY") {
                    $payment_mode[] = "PAYUMONEY";
                }

                /* END */
                if ($key["family_member_fk"] == 0) {
                    $patient_name = $key["full_name"];
                } else {
                    $patient_name = $key["family_name"];
                }



                if (!isset($_REQUEST['de'])) {
                    if ($key["oldpatient"] == '1') {
                        $formpatient = "Old";
                    } else {
                        $formpatient = "New";
                    }

                    fputcsv($handle, array($cnt, $key["id"], $key["order_id"], $key["test_city_name"], $key["branch_name"], $key["date"], $patient_name, $key["mobile"], $key["doctor_name"] . "-" . $key["doctor_mobile"], $key["test_name"], $j_status, $key["payment_type"], $key["sample_from"], $formpatient, $key["portal"], $key["note"], $added_by, $key["price"], $discount, $due, $cash, $card, $payumoney, $cheque, $dabitt_from_wallet[0]["dabit"], $creditor_cash_due, $key["payable_amount"], implode("+", $creditor_remark)));
                } $cnt++;
            }
        }
        fclose($handle);
        exit;
    }

    function get_city_report() {

        echo $sdate = "2017-12-01";
        echo $edate = "2017-12-31";
        $this->load->model("add_result_model");
        $get_city = $this->add_result_model->get_val("SELECT id,`name` FROM `test_cities` WHERE `status`='1'");
        $final_array = array();
        foreach ($get_city as $key) {
            $get_city_data = $this->add_result_model->get_val("SELECT job_master.id,job_master.`order_id`,job_master.date FROM job_master WHERE job_master.`status`!='0' AND `job_master`.`model_type`='1' AND `job_master`.`test_city`='" . $key["id"] . "' AND `date`>='" . $sdate . " 00:00:00' AND `date`<='" . $edate . " 23:59:59'");
            $total_job = 0;
            $panel_test = 0;
            $non_panel_test = 0;
            $both_test = 0;
            $total_test = 0;
            $panel_revenue = 0;
            $both_revenue = 0;
            $nonpanel_revenue = 0;
            foreach ($get_city_data as $key1) {

                $data['query'] = $this->add_result_model->job_details($key1["id"]);
                if (trim($data['query'][0]['testid']) == null && $data['query'][0]["packageid"] != null) {
                    $package_id = $data['query'][0]["packageid"];
                    $pid = explode("%", $data['query'][0]['packageid']);
                    foreach ($pid as $pkey) {
                        $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");
                        foreach ($p_test as $tp_key) {
                            $tid[] = $tp_key["test_fk"];
                        }
                    }
                } else if (trim($data['query'][0]['testid']) != null && $data['query'][0]["packageid"] != null) {

                    $tid = explode(",", $data['query'][0]['testid']);
                    $package_id = $data['query'][0]["packageid"];
                    $pid = explode("%", $data['query'][0]['packageid']);
                    foreach ($pid as $pkey) {
                        $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");

                        foreach ($p_test as $tp_key) {
                            $tid[] = $tp_key["test_fk"];
                        }
                    }
                } else {
                    $tid = explode(",", $data['query'][0]['testid']);
                }
                //print_R($tid); die();
                foreach ($tid as $t_key) {
                    $p_test = $this->add_result_model->get_val("SELECT sub_test_master.* FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `sub_test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $t_key . "'");
                    foreach ($p_test as $tp_key) {
                        $tid[] = $tp_key["sub_test"];
                    }
                }
                $tid = array_unique($tid);
                /* Nishit check one test start */
                if (!empty($data['test_id'])) {
                    $tid = array($data['test_id']);
                }
                /* END */

                $check_p_test = $this->add_result_model->get_val("SELECT * FROM `booked_job_test` WHERE `status`='1' AND test_fk LIKE '%pt-%' AND job_fk='" . $key1["id"] . "'");
                if (count($check_p_test) == count($tid) && count($check_p_test) > 0) {
                    $panel_test = $panel_test + count($tid);
                    $panel_revenue = $panel_revenue + $data['query'][0]["price"];
                } else if (count($check_p_test) > 0) {
                    $both_test = $both_test + count($tid);
                    $both_revenue = $both_revenue + $data['query'][0]["price"];
                } else if (count($check_p_test) == 0) {
                    $nonpanel_revenue = $nonpanel_revenue + $data['query'][0]["price"];
                    $non_panel_test = $non_panel_test + count($tid);
                }
                $total_test = $total_test + count($tid);
                $total_job++;
            }
            $key["other"] = array(
                "total_job" => $total_job,
                "total_test" => $total_test,
                "panel_test" => $panel_test,
                "both_test" => $both_test,
                "non_panel_test" => $non_panel_test,
                "panel_revenue" => $panel_revenue,
                "both_revenue" => $both_revenue,
                "non_panel_revenue" => $nonpanel_revenue
            );
            $final_array[] = $key;
        }
        echo "<pre>";
        print_R($final_array);
        die();
    }

    function marge_doctor_list() {
        $doctor_data = $this->master_model->get_val("SELECT d2.id,d2.full_name,d2.email,d2.mobile,(SELECT COUNT(d.id) AS cnt FROM `doctor_master` AS d WHERE d.`status`='1' AND d.`mobile`=d2.`mobile`) AS cnt FROM `doctor_master` AS d2 WHERE d2.`status`='1' AND d2.id>'25053'");
        $doctor_number = array();
        foreach ($doctor_data as $key) {
            //echo "<pre>"; print_r($key);die();
            $key["mobile"] = trim($key["mobile"]);
            if (!in_array($key["mobile"], $doctor_number)) {
                $this->master_model->get_val_update("UPDATE `doctor_master`
SET `status` = '0'
WHERE `mobile` = '" . $key["mobile"] . "' AND id NOT IN (" . $key["id"] . ")");
                $doctor_number[] = $key["mobile"];
            }
        }
        die("DONE");
    }

    function nishit_test1() {
        $this->load->model("add_result_model");
        $p_job = $this->master_model->get_val("SELECT id FROM job_master WHERE `status`='8' AND `date`>='2018-05-05 00:00:00' AND `date`<='2018-05-06 23:59:50'");
        //$p_job = $this->master_model->get_val("SELECT id FROM job_master WHERE `status`='8' AND id='92352'");
        $na = array();
        foreach ($p_job as $key1) {

            $tid = array();
            $data['query'] = $this->add_result_model->job_details($key1["id"]);
            if (trim($data['query'][0]['testid']) == null && $data['query'][0]["packageid"] != null) {
                $package_id = $data['query'][0]["packageid"];
                $pid = explode("%", $data['query'][0]['packageid']);
                foreach ($pid as $pkey) {
                    $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");
                    foreach ($p_test as $tp_key) {
                        $tid[] = $tp_key["test_fk"];
                    }
                }
            } else if (trim($data['query'][0]['testid']) != null && $data['query'][0]["packageid"] != null) {

                $tid = explode(",", $data['query'][0]['testid']);
                $package_id = $data['query'][0]["packageid"];
                $pid = explode("%", $data['query'][0]['packageid']);
                foreach ($pid as $pkey) {
                    $p_test = $this->add_result_model->get_val("SELECT `package_test`.`test_fk` FROM `package_test` INNER JOIN `test_master` ON `package_test`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `package_test`.`status`='1' AND `package_test`.`package_fk`='" . $pkey . "'");

                    foreach ($p_test as $tp_key) {
                        $tid[] = $tp_key["test_fk"];
                    }
                }
            } else {
                $tid = explode(",", $data['query'][0]['testid']);
            }
            
            foreach ($tid as $t_key) {
                $p_test = $this->add_result_model->get_val("SELECT sub_test_master.* FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`test_fk`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `sub_test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $t_key . "'");
                foreach ($p_test as $tp_key) {
                    $tid[] = $tp_key["sub_test"];
                }
            }
            $tid = array_unique($tid);
            /* Nishit check one test start */
            if (!empty($data['test_id'])) {
                $tid = array($data['test_id']);
            }
            /* END */
            $na[] = array("jid" => $key1["id"], "tid" => $tid);
        }
        

        foreach ($na as $key) {
            foreach ($key["tid"] as $key1) { 
                //echo "SELECT id FROM `user_test_result` WHERE `job_id`='" . $key["jid"] . "' AND `test_id`='" . $key1 . "' AND `status`='1'"; die();
                $p_test = $this->add_result_model->get_val("SELECT id FROM `user_test_result` WHERE `job_id`='" . $key["jid"] . "' AND `test_id`='" . $key1 . "' AND `status`='1'");
                
                if (!empty($p_test)) { 
                //print_r($na);print_r($key1);die();    
                    $p_test = $this->add_result_model->get_val("select id from use_formula where status='1' and test_fk='" . $key1 . "' and job_fk='" . $key["jid"] . "'");
                    if (!empty($p_test)) {
                        $this->master_model->master_fun_update1("use_formula", array("job_fk" => $key["jid"], "test_fk" => $key1), array("test_status" => "1"));
                    } else {
                        $this->master_model->master_fun_insert("use_formula", array("job_fk" => $key["jid"], "test_fk" => $key1, "status" => 1, "test_status" => "1"));
                    }
                    
                    
                    $ap_test = $this->add_result_model->get_val("SELECT id FROM `approve_job_test` WHERE job_fk='".$key["jid"]."' AND `test_fk`='".$key1."' AND `status`='1'");
                if (!empty($ap_test)) {
                    $this->master_model->master_fun_update1("use_formula", array("job_fk" => $key["jid"], "test_fk" => $key1), array("test_status" => "2"));
                }
                    
                }

                
            }
      
        } echo "DONE";
    }

}
