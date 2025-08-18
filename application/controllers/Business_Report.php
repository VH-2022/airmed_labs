<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Business_Report extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('Business_model');

        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->helper('string');
        //echo current_url(); die();

        $data["login_data"] = logindata();
    }

    function business_list() {
        $data['start_date'] = $start_date = $this->input->get('start_date');
        $data['end_date'] = $end_date = $this->input->get('end_date');
        $data['city_id'] = $city_id = $this->input->get('city');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        if ($start_date != "" || $end_date != '' || $city_id !='') {
            
            $data['query'] = $this->Business_model->master_get_search($start_date,$end_date,$city_id);
        // echo $this->db->last_query();die();
             $data['query_b2c'] = $this->Business_model->master_get_b2c($start_date,$end_date,$city_id);
          // echo $this->db->last_query();die();
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
            //echo "hiii";
            $cnt = 0;
        } 
        $data['city'] = $this->Business_model->get_val("select * from test_cities where status='1' and name IS NOT NULL");
       
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('Business_list', $data);
        $this->load->view('footer');
    }
    
    function export_to_csv(){
         $data['start_date'] = $start_date = $this->input->get('start_date');
        $data['end_date'] = $end_date = $this->input->get('end_date');
        $data['city_id'] = $city_id = $this->input->get('city');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        if ($start_date != "" || $end_date != '' || $city_id !='') {
            
            $data['query'] = $this->Business_model->master_get_search($start_date,$end_date,$city_id);
          
             $data['query_b2c'] = $this->Business_model->master_get_b2c($start_date,$end_date,$city_id);
           header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Report .csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
       fputcsv($handle, array("No", "Client Of Name", "BDE/BDM", "Branch Type","City","Revenue","Total Booking"));
      $cnt=1;
       foreach ($data['query'] as $val) {

            fputcsv($handle, array($cnt ,ucwords($val['ClientName']), ucwords($val['first_name'].' '.$val['last_name']), B2B, ucwords($val['City']),ucwords($val['Revenue']),ucwords($val['Booking'])));
       $cnt++;
            }
        foreach ($data['query_b2c'] as $key) {

            fputcsv($handle, array($cnt, ucwords($key['BranchName']), ucwords($key['first_name'].' '.$key['last_name']),ucwords($key['BranchType']) , ucwords($key['City']),ucwords($key['Revenue']),ucwords($key['Booking'])));
        $cnt++;
            
        }
         fclose($handle);
        exit;
        } 
    }
}