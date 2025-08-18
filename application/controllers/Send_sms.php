<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Send_sms extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->library('form_validation');
        $data["login_data"] = logindata();
    }

    function index() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $this->form_validation->set_rules('email[]', 'Name', 'trim|required');
        $this->form_validation->set_rules('note', 'Description', 'trim|required');
        if ($this->form_validation->run() != false) {
            $id = $this->input->post('email');
            $note = $this->input->post('note');
            foreach ($id as $key) {
                $test = $this->master_model->get_val("select phone from admin_master where id = '" . $key . "' and status='1'");
                $phone = $test[0]['phone'];
                if ($phone != '') {
                    $insert = $this->master_model->master_fun_insert("admin_alert_sms", array("mobile_no" => $phone, "message" => $note, "created_date" => date("Y-m-d H:i:s")));
                }
            }
            $this->session->set_flashdata("success",array("SMS successfully send."));
            redirect("Send_sms/index");
        } else {
            $data['admin'] = $this->master_model->get_val("select id,name,email from admin_master where type NOT IN(3,4) and status='1' order by name asc");
            $data['success'] = $this->session->flashdata("success");
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('Send_Sms', $data);
            $this->load->view('footer');
        }
    }

}

?>