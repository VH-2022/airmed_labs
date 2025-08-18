<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jobwallet_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        
        $this->load->model('jobwallet_modal');
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

    function jobswallet_list(){
        
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
         $data["user"] = $this->jobwallet_modal->getUser($data["login_data"]["id"]); 
        /* $data['success'] = $this->session->flashdata("success"); */
         $reg_id = $this->input->get('reg_no');
         $ord_id = $this->input->get('ord_id');
         $customer_name = $this->input->get('customer_name');
         $start_date = $this->input->get('start_date');
         $end_date = $this->input->get('end_date');
         $data['reg'] = $reg_id;
         $data['ord_id'] = $ord_id;
         $data['customer_name'] = $customer_name;
         $data['start_date'] = $start_date;
         $data['end_date'] = $end_date;
         if($reg_id != '' || $ord_id != '' || $customer_name != '' || $start_date != '' || $end_date != '' )
         {
            
             $totalRows = $this->jobwallet_modal->getwallet_num($reg_id,$ord_id,$customer_name,$start_date,$end_date);
             
            $config = array();
            $config["base_url"] = base_url()."jobwallet_master/jobswallet_list?reg_no=$reg_id&ord_id=$ord_id&customer_name=$customer_name&start_date=$start_date&end_date=$end_date";
            $config["total_rows"] = $totalRows;
            $config["per_page"]=50;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
			$config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            
            $data["query"] = $this->jobwallet_modal->getwallet($reg_id,$ord_id,$customer_name,$start_date,$end_date,$config["per_page"], $page);
                        /* echo "<pre>";print_r($data["query"]); die(); */

            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
            function getcredit($jobid){
                $ci =& get_instance();
                
                $gwtcredit=$ci->jobwallet_modal->getwallettotal($jobid);
                
                return $gwtcredit->credit;
            }
         }else{
             $totalRows = $this->jobwallet_modal->getwallet_num($reg_id,$ord_id,$customer_name,$start_date,$end_date);
            $config = array();
            $config["base_url"] = base_url()."jobwallet_master/jobswallet_list/";
            $config["total_rows"] = $totalRows;
            $config["per_page"]=50;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            
            $data["query"] = $this->jobwallet_modal->getwallet($reg_id,$ord_id,$customer_name,$start_date,$end_date,$config["per_page"], $page);
            /* echo "<pre>";print_r($data["query"]); die(); */
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
            function getcredit($jobid){
                $ci =& get_instance();
                
                $gwtcredit=$ci->jobwallet_modal->getwallettotal($jobid);
                
                return $gwtcredit->credit;
            }
         }      
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('jobswallet_list', $data);
        $this->load->view('footer');
    
    }

    
}

?>
