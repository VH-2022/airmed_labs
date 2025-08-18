<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sample_print_master extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('b2b/sample_print_model');

        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
      
        //echo current_url(); die();

        $data["login_data"] = logindata();
    }

    function sub_sample_list() {
      $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $client = $this->input->get_post('client');
         $yes = $this->input->get_post('yes');
        $data['client_fk'] =$client;
        $data['yes_id'] =$yes;    
        if($client !='' || $yes !=''){
            
            $totalRows = $this->sample_print_model->sample_num($client,$yes);

            $config = array();
          
            $config["base_url"] = base_url() . "b2b/Sample_print_master/sub_sample_list?client=$client&yes=$yes";
            
            $config["total_rows"] = $totalRows;

            $config["per_page"] = 50;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next <span class="icon-text">&#59230;</span>';
            $config['prev_link'] = '<span class="icon-text">&#59229;</span> Previous';

            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
           $page = ($this->uri->segment("get_page")) ? $this->uri->segment("get_page") : 0;
             $data['query'] = $this->sample_print_model->sample_list($client,$yes, $config["per_page"], $page);
         $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;

        }else{
           
            $totalRows = $this->sample_print_model->sample_num($client,$yes);
           
            $config = array();
          
            $config["base_url"] = base_url() . "b2b/Sample_print_master/sub_sample_list/";
            
            $config["total_rows"] = $totalRows;

            $config["per_page"] =50;

            $config["uri_segment"] =4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next <span class="icon-text">&#59230;</span>';
            $config['prev_link'] = '<span class="icon-text">&#59229;</span> Previous';

            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
          
            $page = ($this->uri->segment("per_page")) ? $this->uri->segment("per_page") : 0;
       
            $data['query'] = $this->sample_print_model->sample_list($client,$yes, $config["per_page"], $page);
         $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        }
  
         $data['client'] =$this->sample_print_model->get_val("select id,name from collect_from where status='1'");
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('b2b/sub_sample_list', $data);
            $this->load->view('b2b/footer');
    }

   
   
    function sub_sample_add() {
         if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('client_fk', 'Client', 'trim|required');

           if ($this->form_validation->run() != FALSE) {
                $client_fk = $this->input->post('client_fk');
                $duplicate = $this->sample_print_model->duplicate_val("SELECT * from sample_client_print_report_permission where client_fk='" . $client_fk . "'");
                 if($duplicate){
             $this->session->set_flashdata("duplicate", "Client Name Already Exist .");
             redirect("b2b/Sample_print_master/sub_sample_add", "refresh");
            }else{
                    $result = $this->input->post('print_report',TRUE)==null ? 0 : 1;
                
                if($result ==0){
                    $check_val="No";
                }else{
                    $check_val ="Yes";
                }
               
                 $data1 = array(
                "client_fk" => $client_fk,
                "print_report"=> $result

            );
    $this->session->set_flashdata("success", 'Client Print Option Value Added.');
            $this->sample_print_model->master_get_insert('sample_client_print_report_permission',$data1);
  redirect("b2b/Sample_print_master/sub_sample_list", "refresh");
                }
                
}

           $data['client'] =$this->sample_print_model->get_val("select id,name from collect_from where status='1'");
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('b2b/sub_sample_add', $data);
            $this->load->view('b2b/footer');
     }
    function sub_sample_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]); 
        $eid = $this->uri->segment('4');
        $data['id'] = $eid;

          $this->form_validation->set_rules('client_fk', 'Client', 'trim|required');

           if ($this->form_validation->run() != FALSE) {
                $client_fk = $this->input->post('client_fk');
                $duplicate = $this->sample_print_model->duplicate_val("SELECT * from sample_client_print_report_permission where client_fk='" . $client_fk . "' AND id !=  $eid");

            if($duplicate){
             $this->session->set_flashdata("duplicate", "Client Name Already Exist .");
             redirect("b2b/Sample_print_master/sub_sample_edit/$eid", "refresh");
            }else{
                $result = $this->input->post('print_report',TRUE)==null ? 0 : 1;
                
                if($result ==0){
                    $check_val="No";
                }else{
                    $check_val ="Yes";
                }
               $id = $this->input->post('id');
                 $data1 = array(
                    "id"=>$id,
                "client_fk" => $client_fk,
                "print_report"=> $result

            );
    $this->session->set_flashdata("success", 'Client Print Option Value Successfully Updated.');
            $data['query'] = $this->sample_print_model->master_fun_update("sample_client_print_report_permission", array('id',$eid), $data1);

           redirect("b2b/Sample_print_master/sub_sample_list", "refresh");
       }
    }
            $data['query'] = $this->sample_print_model->master_get_where_condtion("sample_client_print_report_permission", array("id" => $eid), array("id", "desc"));

$data['client'] =$this->sample_print_model->get_val("select id,name from collect_from where status='1'");
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('b2b/sub_sample_edit', $data);
            $this->load->view('b2b/footer');

    }

    
    function sub_sample_view(){
          if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['user'] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $this->uri->segment('3');
        $id = $data['cid'];
  $data['view_data'] = $this->Branch_Model->master_get_view($id);
  foreach($data['view_data'] as $key=>$val){
      $ids = $val['id'];
        $parent_fk = $val['parent_fk'];

        $data['query'] = $this->Branch_Model->get_sub_client($parent_fk);
    }
    $data['parent_node'] = $this->Branch_Model->get_parent_node($ids);
   
  // echo "<pre>";print_r($data['final_result']);die;

      $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('branch_view', $data);
            $this->load->view('footer');
    }

    function sub_sample_delete() {

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('4');
        $data1 =array(
            'status'=>0
        );
        $data['query'] = $this->sample_print_model->master_fun_update("sample_client_print_report_permission", array('id',$cid), $data1);

        $this->session->set_flashdata("success", 'Sample Print Successfull Deleted.');
        redirect("b2b/Sample_print_master/sub_sample_list", "refresh");
    }

}

?>
