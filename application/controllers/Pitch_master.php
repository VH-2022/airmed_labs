<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pitch_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('pitch_model');
        $this->load->library('form_validation');
          $this->load->library('pagination');
    }

    function index(){
    if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];

        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $id = $this->input->get_post('city_name');
        
        if($id !=''){
          
          $totalRows = $this->pitch_model->sub_listnum($id);
           
            $data['city_name']=$id;
          
           $config = array();
            $config["base_url"] = base_url() . "Pitch_master/index?city_name=$id";
             $config["total_rows"] = $totalRows;
           $config["per_page"]=50;
             $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
         $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
          
          $this->pagination->initialize($config);
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            
            $data["query"] = $this->pitch_model->sub_list($id,$config["per_page"], $page);
  
             $data["links"] = $this->pagination->create_links();
             $data["counts"] = $page;
        }else{

          $totalRows = $this->pitch_model->sub_listnum($id);
           
            $config = array();
            $config["base_url"] = base_url() . "Pitch_master/index";
             $config["total_rows"] = $totalRows;
            $config["per_page"]=50;
           $config["uri_segment"] = 4;
             $config['cur_tag_open'] = '<span>';
             $config['cur_tag_close'] = '</span>';
             $config['next_link'] = 'Next &rsaquo;';
             $config['prev_link'] = '&lsaquo; Previous';
          
             $this->pagination->initialize($config);
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
             $data["query"] = $this->pitch_model->sub_list($id,$config["per_page"], $page);
             
             $new_array = array();
             $sub_array = array();
              $cnt =0;
             foreach($data["query"] as $val){
                 $id = $val['id'];
                 $test =$this->pitch_model->get_val("select p.id as PID ,p.description as Description from  pitch_master as p  where p.city_fk ='".$id."' and p.status='1' ");
                 $val["details"] = $test;
                 $new_array[] =$val;
                $cnt++;
             }
           //echo "<pre>";print_r($new_array);
             $data["new_array"] = $new_array;
            $data["links"] = $this->pagination->create_links();
             $data["counts"] = $page;
        }
       
        $data['city_list'] = $this->pitch_model->get_val("select id,name from test_cities where status='1'");
   
        $this->load->view('header',$data);
        $this->load->view('nav', $data);
        $this->load->view('patch_list', $data);
        $this->load->view('footer');
    }

    function add(){
             if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];

        $tid = $this->uri->segment(3);
         $data['id']=$tid;
         $data['query'] = $this->pitch_model->get_val("select tc.id,tc.name,p.id as PID, p.description from test_cities as tc INNER JOIN pitch_master as p on tc.id=p.city_fk where tc.id ='".$tid."' and tc.status='1' and p.status='1'");
       
        $this->load->view('header',$data);
        $this->load->view('nav', $data);
        $this->load->view('patch_add', $data);
        $this->load->view('footer');
    }
 function edit() {

     if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];

        $tid = $this->uri->segment(3);
        $data['id']=$tid;
        $this->pitch_model->new_fun_update("pitch_master", array("city_fk" =>  $tid), array("status" => "0"));
       
        $description =$this->input->post('description');
        $cnt=0;
        foreach($description as $val){
        
            $data=array(
                    'city_fk'=>$tid,
                    'description'=>$val,
                'created_date'=>date('Y-m-d h:i:s'),
                'created_by'=>$id
            );
           $insert =  $this->pitch_model->master_fun_insert("pitch_master", $data);
            $cnt++;
        }
          $this->session->set_flashdata("success", array("Pitch successfully added."));
        redirect("Pitch_master/index","refresh");
    }

    function update(){

         $this->Speciality_model->new_fun_update("specility_wise_test", array("id" => $this->input->post("id")), array("status" => "0"));
          $this->Speciality_model->new_fun_update("test_speciality", array("spec_fk" => $this->input->post("id")), array("status" => "0"));
   
       $item_list = $this->input->post("item");      
        $name = $this->input->post("name");
            $data1 = array(
                'name'=>$name,
                'test_fk'=>1
            );
        $insert = $this->Speciality_model->master_fun_insert("specility_wise_test", $data1);

        $cnt = 0;
        if($insert){
        foreach ($item_list as $kkey) {
            $data = array(
                "spec_fk"=>$insert,
                "test_fk" => $kkey,
                
                "created_date" => date("Y-m-d H:i:s")
            );
           $this->Speciality_model->master_fun_insert("test_speciality", $data);
            $cnt++;
        }
     }
        $this->session->set_flashdata("success", array("Speciality successfully added."));
        redirect("Speciality_master/index","refresh");
    }

    function delete(){
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $tid = $this->uri->segment('3');

        $data['query'] = $this->Speciality_model->master_fun_update("specility_wise_test", $tid, array("status" => "0"));

       $this->session->set_flashdata("success", array("Intent Request successfull deleted."));
       redirect("Speciality_master/index", "refresh");
    }
}
