<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Logistic_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('b2b/Logistic_test_modal');
   $this->load->model('b2b/Logistic_master_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $data["login_data"] = logindata();

    }

    function sub_list(){
       if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['success'] = $this->session->flashdata("success");
        $search = $this->input->get('search');
        $city = $this->input->get('city'); 
        $data['search'] = $search;
        $lab  = $this->uri->segment(4);
        $data['lab'] = $this->uri->segment(4);
    

        /* $data['city'] = $city; */
        $cquery = "";
        if ($search != "") {
            /* if (!empty($city)) {
                $cquery = "AND `test_master_city_price`.`city_fk`='" . $city . "'";
            } */
           
            $total_row = $this->Logistic_master_model->search_num($search);
        
            $config = array();
            $config["base_url"] = base_url() . "b2b/Logistic_master/sub_list/?search=$search";

            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Logistic_master_model->speci_list_search($search, $config["per_page"], $page);

            $data["links"] = $this->pagination->create_links();
           // $data["counts"] = $page;
        } else {

            
            $totalRows = $this->Logistic_master_model->num_row('test_master', array('status' => 1));

            $config = array();
            $config["base_url"] = base_url()."b2b/Logistic_master/sub_list/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
             $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            
            
            $data["query"] = $this->Logistic_master_model->sub_list($config["per_page"], $page); 

            $query = $this->Logistic_master_model->get_val('select * from collect_from where id="'.$lab.'" and status="1"');
            $data['discount'] = $query[0]['test_discount'];
            $data["links"] = $this->pagination->create_links();
            //$data["counts"] = $page;
        }
        $data["page"] = $page;
        $this->load->view('b2b/header');
        $this->load->view('b2b/nav_logistic',$data);
        $this->load->view('b2b/sub_list',$data);
        $this->load->view('b2b/footer');
    
    }
  

  
        

      function test_csv() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $city = $this->input->get("city");
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "test.csv";
        //$query = 'SELECT t.test_name as `Test Name`,c.name as `City Name`,p.price as `Test Price` from sample_test_master as t left join sample_test_city_price as p on t.id=p.test_fk join test_cities as c on p.city_fk=c.id where t.status="1" and c.status="1" and p.status="1" order by t.test_name asc';
        $query = "SELECT  t.id,t.`test_name` as testname ,c.`name` as cityname,tc.`price` FROM  `sample_test_master` t  LEFT JOIN sample_test_city_price tc ON tc.test_fk=t.id  LEFT JOIN `test_cities` c ON tc.`city_fk`=c.`id` WHERE t.status = '1' AND tc.city_fk='" . $city . "'";
        $result = $this->db->query($query);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }

function get_test_data(){
  
    $test = $this->input->post('test_id');
    $lab = $this->input->post('lab_fk');
    
    

    $query = $this->Logistic_master_model->get_val('select * from collect_from where id="'.$lab.'" and status="1"');

    $city_fk = $query[0]['city'];

    $discount = $query[0]['test_discount'];

    $data = $this->Logistic_master_model->getfindtest($test,$city_fk);

    $response['price'] = $data[0]['price'];

    $response['b2bPrice'] = $response['price']-(($discount /100)*$response['price']);

   
echo json_encode($response);

}

function new_edit(){
    $test = $this->input->post('tid');
     
        $data = $this->Logistic_master_model->get_val("select t.id,t.test_name,b.price_special from test_master as t LEFT JOIN b2b_testspecial_price as b on t.id = b.test_fk where t.id='".$test."'");

        $response['TID'] = $data[0]['id'];
        $response['Special'] = $data[0]['price_special'];
   echo json_encode($response);
    }


    function new_add_sub(){
       
         $test_id = $this->input->post('test');
        $lab = $this->input->post('lab_id');
        $sprice = $this->input->post('sprice');

        $query = $this->Logistic_master_model->get_val("select * from b2b_testspecial_price where test_fk='".$test_id."'");
        $test_fk = $query[0]['test_fk'];
       
       

        if($test_fk !=''){
                 $this->Logistic_master_model->master_fun_update("b2b_testspecial_price", array("test_fk",$test_id), array("lab_id" => $lab,"price_special"=>$sprice));

        }else{
              $data['query'] = $this->Logistic_master_model->master_fun_insert("b2b_testspecial_price", array("lab_id" => $lab, "status" => "1", "test_fk" => $test_id, "price_special" => $sprice));
        }
    
}
}
?>