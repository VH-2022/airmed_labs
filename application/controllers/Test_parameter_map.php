<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Test_parameter_map extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('Test_parameter_map_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->helper('string');
//      ini_set('display_errors', 'On');

        $data["login_data"] = logindata();
    }

    function index() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
//        echo "<pre>"; print_r($data["login_data"]); exit;
        $this->form_validation->set_rules('code', 'Code', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $tid = $this->input->post("test_price_id");
            $code = $this->input->post("code");
            $mult_by = $this->input->post("mult_by");

            if (!empty($code)) {
                $update = $this->Test_parameter_map_model->master_fun_update('test_parameter_master', array('id', $tid), array("code" => $code, 'multiply_by' => $mult_by));
                $this->session->set_flashdata("success", array("Test parameter successfully updated."));
                redirect("Test_parameter_map/index?code_id=$tid");
            }
        } else {
            $data['user'] = $this->user_model->getUser($data["login_data"]["id"]);
            //echo "<pre>"; print_r($data['user']); exit;
            $login_id = $data['user']->id;
            //echo $login_id; exit;
            $code = $this->input->get("code_id");
            $data['code_id'] = $code;
            if (!empty($code)) {
                
                if ($data["login_data"]['type'] == "1" || $data["login_data"]['type'] == "2") {
                    $q = "select tp.id,tp.center,tp.code,tp.parameter_name,bm.branch_name,tp.multiply_by 
                            from test_parameter_master tp 
                            LEFT JOIN branch_master bm on bm.id = tp.center AND bm.status= '1' 
                            where tp.status = '1' AND tp.id = '$code'";
                } else {
                    $q = "select tp.id,tp.center,tp.code,tp.parameter_name,bm.branch_name,tp.multiply_by 
                            from test_parameter_master tp 
                            LEFT JOIN branch_master bm on bm.id = tp.center AND bm.status= '1' 
                            LEFT JOIN user_branch ub on ub.branch_fk = bm.id 
                            where tp.status = '1' AND tp.id = '$code' AND ub.user_fk = '$login_id'";
                }
                
                $data['query'] = $this->Test_parameter_map_model->get_val($q);

                $totalRows = count($data['query']);
                $config = array();
                $config["base_url"] = base_url() . "Test_parameter_map/index";
                $config["total_rows"] = $totalRows;

                $config['page_query_string'] = TRUE;
                $config["per_page"] = 100;
                $config["uri_segment"] = 3;
                $config['cur_tag_open'] = '<span>';
                $config['cur_tag_close'] = '</span>';
                $config['next_link'] = 'Next &rsaquo;';
                $config['prev_link'] = '&lsaquo; Previous';
                $this->pagination->initialize($config);
                $sort = $this->input->get("sort");
                $by = $this->input->get("by");
                $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;

                $two = $config['per_page'];
                $q .= " LIMIT $page,$two";
                $data["query"] = $this->Test_parameter_map_model->get_val($q);

                $data["links"] = $this->pagination->create_links();
                $data["pages"] = $page;
            }

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('test_parameter_mp', $data);
            $this->load->view('footer');
        }
    }

}

?>
