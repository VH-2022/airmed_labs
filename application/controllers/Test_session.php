<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_session extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
    }

    public function index(){
        // set test session
        $this->session->set_userdata('my_test_session', array('id'=>1,'name'=>'AirMedUser'));
        
        // print session
        echo "<pre>";
        print_r($this->session->userdata('my_test_session'));
        echo "</pre>";
    }
}
