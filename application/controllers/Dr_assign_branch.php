<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dr_assign_branch extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('Dr_assign_model');
       
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->helper('string');
        //echo current_url(); die();

        $data["login_data"] = logindata();
    }
    
    function index(){ 
         $data['branch_name'] = $branchid = $this->input->get('branch_name');
        $data['email'] = $email = $this->input->get('email');
      
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        if ($branchid != "" || $email != '' ) {
            $totalRows = $this->Dr_assign_model->num_row($branchid, $email);
        
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "Dr_assign_branch?" . http_build_query($get);
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Dr_assign_model->sub_list($branchid, $email,$config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
            
        } else {
            $totalRows = $this->Dr_assign_model->num_row($branchid,$email);
            $config = array();
            $get = $_GET;
            $config["base_url"] = base_url() . "Dr_assign_branch";
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
            $data['query'] = $this->Dr_assign_model->sub_list($branchid,$email, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            
        }
        $data['branch_type'] = $this->Dr_assign_model->get_val("select * from branch_master where status='1'");
//        $data['branch'] = $this->Dr_assign_model->master_get_branch();
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('dr_assign_list', $data);
        $this->load->view('footer');
    }
 
    function add() { 
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $this->load->library('form_validation');
          $this->form_validation->set_rules('branch_fk','Branch Name','trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        if ($this->form_validation->run() != FALSE) {
            $branch_fk = $this->input->post('branch_fk');
            $email = $this->input->post('email');
                $data = array(
                  'branch_fk'=>$branch_fk,
                    'email'=>$email,
                    'status'=>1,
                    'created_date'=>date('Y-m-d')
                    
                );
            $data['query'] = $this->Dr_assign_model->master_get_insert("send_report_for_approve", $data);
            $this->session->set_flashdata("success", 'Send Report for approve Successful Added.');
                echo 1;
            redirect("Dr_assign_branch", "refresh");
   
        }else{
            echo 0;
        } 
    }
    
    public function edit(){
         $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $sub_id = $this->uri->segment('3');
      
        $query  =$this->Dr_assign_model->get_val("select * from send_report_for_approve where id = '".$sub_id."' ");
        echo json_encode(array('id'=>$query[0]['id'],'branch_fk'=>$query[0]['branch_fk'],'email'=>$query[0]['email']));
    }
    
    public function update(){
         $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $sub_id = $this->input->post('id');
       
        $this->load->library('form_validation');
          $this->form_validation->set_rules('branch_fk','Branch Name','trim|required');
      // $this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean');
      
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() != FALSE) {
            $branch_fk = $this->input->post('branch_fk');
            $email = $this->input->post('email');
                $data = array(
                  'branch_fk'=>$branch_fk,
                    'email'=>$email,
                    'status'=>1,
                    'created_date'=>date('Y-m-d')
                    
                );
            $data['query'] = $this->Dr_assign_model->master_get_update("send_report_for_approve", array('id' => $sub_id), $data);
            $this->session->set_flashdata("success", 'Send Report for approve Successful Updated.');
            redirect("Dr_assign_branch", "refresh");
           
        }

    }
    
    public function delete(){
         $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $sub_id = $this->uri->segment('3');
                $data = array(
                    'status'=>0
                );
            $data['query'] = $this->Dr_assign_model->master_get_update("send_report_for_approve", array('id' => $sub_id), $data);
      
            $this->session->set_flashdata("success", 'Send Report for approve Successfully Deleted.');
            redirect("Dr_assign_branch", "refresh");
           
        }

   
}