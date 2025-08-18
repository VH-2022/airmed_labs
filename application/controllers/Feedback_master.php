<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Feedback_master extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('Feedback_model');
     
        $this->load->library('pagination');
    

        $data["login_data"] = logindata();
    }

    function Feedback_list() { 
      $name = $this->input->get('name');
    $email = $this->input->get('email');
      $mobile = $this->input->get('phone');
       $subject = $this->input->get('subject');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        if ($name != "" || $email != '' || $mobile != '' || $subject !='') {
            $totalRows = $this->Feedback_model->num_row($name, $email,$subject, $mobile);
            $data['name'] = $name;
            $data['email'] = $email;
            $data['subject'] = $subject;
            $data['phone'] = $mobile;


            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "Feedback_master/Feedback_list?" . http_build_query($get);
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Feedback_model->master_get_search($name, $email,$subject,$mobile, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
            //echo "hiii";
            $cnt = 0;
        } else {
           $totalRows = $this->Feedback_model->num_row($name, $email, $subject,$mobile);
            $config = array();
            $get = $_GET;
            $config["base_url"] = base_url() . "Feedback_master/Feedback_list/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next <span class="icon-text">&#59230;</span>';
            $config['prev_link'] = '<span class="icon-text">&#59229;</span> Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data['query'] = $this->Feedback_model->master_get_search($name, $email, $subject,$mobile, $config["per_page"], $page);


            $data["links"] = $this->pagination->create_links();
            //echo "bye";
            $cnt = 0;
        }
       
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('feedback_list', $data);
        $this->load->view('footer');
    }

   
    function delete() {

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');

        $data['query'] = $this->Feedback_model->master_tbl_update("feedback", $cid, array("status" => "0"));

        $this->session->set_flashdata("success", 'Feedback Successfull Deleted.');
        redirect("Feedback_master/Feedback_list", "refresh");
    }

   
}

?>
