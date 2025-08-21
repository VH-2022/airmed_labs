<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_forget extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('form', 'url'));
        $this->load->model('master_model');
        $this->load->library('form_validation');
        $this->app_track();
    }
	function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    //comment by rohit
    // function index() {
    //     $this->load->helper("Email");
    //     $email_cnt = new Email;

    //     $this->load->library('email');
    //     $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
    //     $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required|trim');
    //     $captch = $this->varify_captcha();
    //     if ($this->form_validation->run() == FALSE||$captch!=1) {
    //         $data = '';
    //         if ($this->session->userdata('getmsg1') != null) {
    //             $data['getmsg1'] = $this->session->userdata("getmsg1");
    //             $this->session->unset_userdata('getmsg1');
    //         }$this->load->view('user/header', $data);
    //         $this->load->view('user/forget_password', $data);
    //         $this->load->view('user/footer');
    //     } else {
    //         $email = $this->input->post('email');
    //         $check_email = $this->master_model->master_num_rows("customer_master", array("email" => $email, 'status' => '1'));
    //         if ($check_email == '0') {
    //             $this->session->set_userdata('getmsg1', array("Invalid email Please Check It!"));
    //             redirect('user_forget', 'refresh');
    //         }
    //         $this->load->helper('string');
    //         $rs = random_string('alnum', 5);
    //         $data = array(
    //             'rs' => $rs
    //         );
    //         $this->db->where('email', $email);
    //         $this->db->where('status', '1');
    //         // $this->db->where('type', '1');   
    //         $this->db->update('customer_master', $data);
    //         $config['mailtype'] = 'html';
    //         $this->email->initialize($config);
    //         $message = '<div style="padding:0 4%;">
    //                 <h4>Forgot Password</h4>
    //                     <p style="color:#7e7e7e;font-size:13px;">You recently requested to reset your password for your  AirmedLabs Account. Click the button below to reset it. </p>
	// 		<a href="' . base_url() . 'user_get_password/index/' . $rs . '" style="background: #D01130;color: #f9f9f9;padding: 1%;text-decoration: none;">Reset</a>
    //                     <p style="color:#7e7e7e;font-size:13px;">If you did not request a password reset, please ignore this email or reply to let us know.</p>
    //                     <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
    //             </div>';
    //         $message = $email_cnt->get_design($message);
    //         $this->email->to($email);
    //         $this->email->from('donotreply@airmed.com', 'AirmedLabs');
    //         $this->email->subject('AirmedLabs Reset your forgotten Password');
    //         $this->email->message($message);
    //         $this->email->send();
    //         $this->session->set_flashdata('success', array("We have Sent you a link to change your password Please Check It!"));
    //         redirect('user_login');
    //     }
    // }

    //new added by rohit
     function index() {
        $this->form_validation->set_rules('phone_no', 'Mobile no', 'trim|required');
        // $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required|trim');
        // $captch = $this->varify_captcha();
        $captch = 1;
        $OTP = rand(11111, 99999);
        if ($this->form_validation->run() == FALSE||$captch!=1) {
            $data = '';
            if ($this->session->userdata('getmsg1') != null) {
                $data['getmsg1'] = $this->session->userdata("getmsg1");
                $this->session->unset_userdata('getmsg1');
            }$this->load->view('user/header', $data);
            $this->load->view('user/forget_password', $data);
            $this->load->view('user/footer');
        } else {
            $phone_no = $this->input->post('phone_no');
            $check_phone = $this->master_model->master_num_rows("customer_master", array("mobile" => $phone_no, 'status' => '1'));
            if ($check_phone == '0') {
                $this->session->set_userdata('getmsg1', array("Invalid Phone No Please Check It!"));
                redirect('user_forget', 'refresh');
            }

            $this->load->helper('string');
            $rs = random_string('alnum', 5);
            $data = array(
                'rs' => $rs,
                'otp' => $OTP
            );
            $this->db->where('mobile', $phone_no);
            $this->db->where('status', '1');
            $this->db->update('customer_master', $data);

            // send OTP on WhatsApp
            $response = $this->send_whatsapp_otp($phone_no, $OTP);
            
            if ($response['status'] == true) {
                $this->session->set_flashdata('success', 'OTP has been sent to your WhatsApp number.');            
                redirect('user_forget/verify_otp/'.$phone_no);
            } else {
                $this->session->set_flashdata('error', "Failed to send OTP. Please try again.");
                redirect('user_forget');
            }
        }
    }

    function send_whatsapp_otp($phone_no, $otp) {
        $patient_mob  = "91" . $phone_no; 
                
        // make sure it has country code 91xxxxxxxxxx
        $bearer_token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiI3OTE5NTY3Zi00ODI0LTRkNjgtYjkzZS1jMGE2MDI1ZTRlYzMiLCJ1bmlxdWVfbmFtZSI6Im1haWx0b2RyYW1pdEBnbWFpbC5jb20iLCJuYW1laWQiOiJtYWlsdG9kcmFtaXRAZ21haWwuY29tIiwiZW1haWwiOiJtYWlsdG9kcmFtaXRAZ21haWwuY29tIiwiYXV0aF90aW1lIjoiMDYvMDMvMjAyNCAwOTo1Nzo0NiIsImRiX25hbWUiOiJtdC1wcm9kLVRlbmFudHMiLCJ0ZW5hbnRfaWQiOiIxMTEwIiwiaHR0cDovL3NjaGVtYXMubWljcm9zb2Z0LmNvbS93cy8yMDA4LzA2L2lkZW50aXR5L2NsYWltcy9yb2xlIjoiQURNSU5JU1RSQVRPUiIsImV4cCI6MjUzNDAyMzAwODAwLCJpc3MiOiJDbGFyZV9BSSIsImF1ZCI6IkNsYXJlX0FJIn0.Bkx_yIos2CDH9r3jp6YfWRk4MbFFPWQwX1V0GxudQlo";

        $payload = [
            "template_name"  => "user_send_otp_04",
            "broadcast_name" => "Forgat OTP Airmed",
            "parameters"     => [
                [
                    "name"  => "1",
                    "value" => (string)$otp
                ]
            ]
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => 'https://live-server-1110.wati.io/api/v1/sendTemplateMessage?whatsappNumber=' . $patient_mob,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $bearer_token,
                'Content-Type: application/json'
            ],
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $response  = curl_exec($curl);
        $httpCode  = curl_getinfo($curl, CURLINFO_HTTP_CODE); // ✅ HTTP status code
        $error     = curl_error($curl);
        curl_close($curl);
        
        // Parse Response
        if ($error) {
            return ['status' => false, 'response' => $error];
        }

        $result = json_decode($response, true);

        if ($httpCode == 200 && isset($result['result']) && $result['result'] == 'success') {
            return ['status' => true, 'response' => $result];
        } else {
            return ['status' => false, 'response' => $result];
        }
    }

    public function verify_otp($phone_no = null)
    {
        if ($phone_no == null) {
            redirect('user_forget'); 
        }

        $data['phone_no'] = $phone_no;
        $this->load->view('user/header');
        $this->load->view('user/varify_otp', $data);
        $this->load->view('user/footer');
    }

    // ✅ AJAX Verify OTP
    public function verify_otp_ajax()
    {
        
        $phone_no   = $this->input->post('phone_no');
        $enteredOtp = $this->input->post('otp');

        $check = $this->db->get_where('customer_master', [
            'mobile' => $phone_no,
            'otp'    => $enteredOtp,
            'status' => '1'
        ])->row();
       
        if ($check) {
            echo json_encode([
                'status' => 1,
                'success_msg' => 'OTP verified successfully.',
                'redirect_url' => base_url('user_get_password/index/'.$check->rs)
            ]);
        } else {
            echo json_encode([
                'status' => 0,
                'error_msg' => 'Invalid OTP. Please try again.'
            ]);
        }
    }

    public function resend_otp_forgatpassword()
    {
        $phone_no = $this->input->post('phone_no');

        if (!$phone_no) {
            echo json_encode([
                'status' => 0,
                'error_msg' => 'Phone number is required.'
            ]);
            return;
        }

        // Check user exist
        $user = $this->db->get_where('customer_master', ['mobile' => $phone_no])->row();

        if (!$user) {
            echo json_encode([
                'status' => 0,
                'error_msg' => 'Mobile number not registered.'
            ]);
            return;
        }

        // Generate new OTP
        $otp = rand(100000, 999999);

        // Save OTP to DB
        $this->db->where('mobile', $phone_no);
        $this->db->update('customer_master', ['otp' => $otp]);

        // ✅ Call WhatsApp OTP send function
        $sendStatus = $this->send_whatsapp_otp($phone_no, $otp);

        if ($sendStatus['status'] == true) {
            echo json_encode([
                'status' => 1,
                'success_msg' => 'OTP sent successfully via WhatsApp. Please check your phone.'
            ]);
        } else {
            echo json_encode([
                'status' => 0,
                'error_msg' => 'Failed to send OTP on WhatsApp. ' . json_encode($sendStatus['response'])
            ]);
        }
    }


    function varify_captcha() {
        $recaptchaResponse = trim($this->input->get_post('g-recaptcha-response'));
        $google_url = "https://www.google.com/recaptcha/api/siteverify";
        $secret = '6Ld5_x8UAAAAAGn_AV4406lg29xu2hpQQJMaD2BC';
        $ip = $_SERVER['REMOTE_ADDR'];
        $url = $google_url . "?secret=" . $secret . "&response=" . $recaptchaResponse . "&remoteip=" . $ip;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $res = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($res, true);
        //var_dump($res);
        if ($res['success'] == true) {
            return 1;
        } else {
            return 0;
        }
    }

}

?>
