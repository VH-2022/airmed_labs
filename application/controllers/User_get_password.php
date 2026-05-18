<?php

class User_get_password extends CI_Controller {

    public function index($rs = FALSE) {
        $this->load->database();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
     	  $this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|required|matches[password]|min_length[6]');
         $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required|trim');
		if ($this->form_validation->run() == FALSE) {
            echo form_open();
			 /* manoj  capcha start */
            $this->load->helper('captcha');
            // numeric random number for captcha
            $random_number = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
            // setting up captcha config
            $vals = array(
                'word' => $random_number,
                'img_path' => './captcha/',
                'img_url' => base_url() . 'captcha/',
                'img_width' => 140,
                'img_height' => 32,
                'expiration' => 7200
            );
            $data['captcha'] = create_captcha($vals);
            $this->session->set_userdata('captchaWord', $data['captcha']['word']);
            $this->load->view('user/header');
            $this->load->view('user/new_password');
            $this->load->view('user/footer');
        } else {
            $code = $this->uri->segment('3');
            $query = $this->db->get_where('customer_master', array('rs' => $code), 1);
            if ($query->num_rows() == 0) {
                show_error('Sorry!!! Invalid Request!');
            } else {
                $pass = $this->input->post('password');
                $cpass = $this->input->post('passconf');
                if ($pass == $cpass) {
                    $data = array(
                        'password' => $this->input->post('password'),
                        'rs' => ''
                    );
                    $where = $this->db->where('status', '1');
                    //$where=$this->db->where('type','1');	
                    $where = $this->db->where('rs', $code);
                    $where->update('customer_master', $data);

                    $this->session->set_flashdata('success', array("Congratulations!!! Your Password is Changed!"));
                    redirect('user_login', 'refresh');
                } else {
                    echo "<script>alert('Password Do Not Match!')</script>";
                    $this->session->set_flashdata('error', array("Password Do Not Match!"));
                    redirect('user_login', 'refresh');
                }
            }
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

    public function check_captcha($str) {
        $word = $this->session->userdata('captchaWord');
        if (strcmp(strtoupper($str), strtoupper($word)) == 0) {
            return true;
        } else {
            $this->form_validation->set_message('check_captcha', 'Please enter correct captcha!');
            return false;
        }
    }


}
