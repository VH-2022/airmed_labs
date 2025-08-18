<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Notification_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('Notification_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $data["login_data"] = logindata();
        $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function index() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $title = $this->input->get('title');
        
        if ($title != "") {
            $srchdata = array("title" => $title);
            $data['title'] = $title;
          
            $totalRows = $this->Notification_model->manage_condition_view($srchdata);

            $config = array();
            /* Vishal Code Start*/
            $config["base_url"] = base_url() . "Notification_master/index?title=$title";
            $config["total_rows"] = $totalRows;
              /*Vishal Code End*/
            $config["per_page"] = 100;
            $config["uri_segment"] = 3;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data["query"] = $this->Notification_model->notification_list($srchdata, $config["per_page"], $page);

            $data["links"] = $this->pagination->create_links();
              $data["counts"] = $page;
        } else {

            $srchdata = array();
            $totalRows = $this->Notification_model->manage_condition_view($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "Notification_master/index";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 100;
            $config["uri_segment"] = 3;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data["query"] = $this->Notification_model->notification_list($srchdata, $config["per_page"], $page);

            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        
        

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('notification_list', $data);
        $this->load->view('footer');
    }

     function add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
      $id = $data["login_data"]['id'];


        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean');
      
  
        if ($this->form_validation->run() != FALSE) {
            $title = $this->input->post('title');

            $message = $this->input->post('message');
            $data= array(
                "title"=>$title,
                "message" =>$message,
                "status" =>1,
                "created_date" => date("Y/m/d H:i:s"),
                "created_by" => $id

            );

            $data['query'] = $this->Notification_model->master_fun_insert("notification_sub_master", $data);
    
           
            $this->session->set_flashdata("success", array("Notification successfully added."));
            redirect("Notification_Master/index", "refresh");
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('notification_add', $data);
            $this->load->view('footer');
        }
    }

    function delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("notification_sub_master", array("id", $cid), array("status" => "0"));

        $this->session->set_flashdata("success", array("Notification successfully deleted."));
        redirect("Notification_Master/index", "refresh");
    }

   
    function edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["id"] = $this->uri->segment('3');
  
        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean');
      
        if ($this->form_validation->run() != FALSE) {
            $title = $this->input->post('title');
            $message = $this->input->post('message');


            $data['query'] = $this->Notification_model->master_get_update("notification_sub_master", array("id"=> $_POST["id"]), array("title"=>$title,"message" =>$message,"status"=>1));

            $this->session->set_flashdata("success", array("Notification successfully updated."));
            redirect("Notification_Master/index", "refresh");
        } else {
            $data['query'] = $this->Notification_model->master_get_tbl_val("notification_sub_master", array("id" => $data["id"]), array("id", "desc"));
         
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('notification_edit', $data);
            $this->load->view('footer');
        }
    }
  
   public function view(){
   if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        
        $data['query'] = $this->Notification_model->get_val("select * from notification_sub_master where status=1 AND start_date<='".date("Y-m-d")."' AND end_date>='".date("Y-m-d")."' ORDER BY id DESC LIMIT 0,1");
       $json['title'] = $data['query'][0]['title'];
       $json['message'] =  $data['query'][0]['message'];

       echo json_encode($json);
            
  }
   

}

?>