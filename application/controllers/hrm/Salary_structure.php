<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Salary_structure extends CI_Controller {

    public $CI = NULL;

    function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
        $this->load->model('user_model');
        $this->load->model('hrm/Salary_struture_model');
        //$this->load->library('email');
        $this->load->library('pushserver');
        $this->load->helper('string');
        ini_set('display_errors', 'On');

        $data["login_data"] = is_hrmlogin();
    }

    function index() {
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $this->load->library('pagination');

        $search = trim($this->input->get('search'));
        $data['search'] = $search;
        if ($search != "") {
            $total_row = $this->Salary_struture_model->search_num($search);
            $config = array();
            $get = $_GET;
            $config["base_url"] = base_url() . "hrm/Salary_structure/index?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Salary_struture_model->list_search($search, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {
            $search = "";
            $totalRows = $this->Salary_struture_model->search_num($search);
            $config = array();
            $config["base_url"] = base_url() . "hrm/Salary_structure/index/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data["query"] = $this->Salary_struture_model->list_search($search, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }

        $this->load->view('hrm/header');
        $this->load->view('hrm/nav', $data);
        $this->load->view('hrm/salary_structure_list', $data);
        $this->load->view('hrm/footer');
    }

    function add() {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");


        if ($this->input->post()) {
            //echo "<pre>";            print_r($_POST); exit;
            $salary_stru_name = trim($this->input->post('salary_stru_name'));
            $count = count($this->input->post('salary_name_1'));

            $data2 = array(
                "salary_strucure_name" => $salary_stru_name,
                "created_by" => $data["login_data"]["id"],
                "created_date" => date("Y-m-d H:i:s"),
                "status" => 1,
            );
            $value2 = $this->Salary_struture_model->insert("hrm_master_salary_structure", $data2);

            for ($j = 0; $j <= $count; $j++) {
                $salary_name = trim($_POST["salary_name_1"][$j]);
                if ($salary_stru_name != "" && $salary_name != "") {
//                    if ($j == 0) {
//                        $type = 1;
//                    } else if ($j == 1) {
//                        $type = 2;
//                    } else if ($j == 2) {
//                        $type = 3;
//                    } else if ($j == 3) {
//                        $type = 4;
//                    } else {
//                        $type = 5;
//                    }

                    $data1 = array(
                        "salary_strucure_id" => $value2,
                        "cutofftype" => $_POST["cuttype"][$j],
                        "salary_name" => $salary_name,
                        "value" => $_POST["salary_value"][$j],
                        "type" => $_POST["type"][$j],
                        //"saletype" => $type,
                        "created_by" => $data["login_data"]["id"],
                        "created_date" => date("Y-m-d H:i:s"),
                        "status" => 1,
                    );
                    $value = $this->Salary_struture_model->insert("hrm_master_salary_structure_details", $data1);
                }
            }
            $this->session->set_flashdata("success", "Salary structure has added successfully.");
            redirect('hrm/Salary_structure/index', 'refresh');
        }

        $this->load->view('hrm/header');
        $this->load->view('hrm/nav', $data);
        $this->load->view('hrm/salary_structure_add', $data);
        $this->load->view('hrm/footer');
    }

    function edit($cid) {
//        if (!is_loggedin()) {
//            redirect('login');
//        }
//        $data["login_data"] = is_hrmlogin();
//        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $cid;
        $data['query'] = $this->Salary_struture_model->get_one("hrm_master_salary_structure", array("id" => $cid, "status" => 1));
        $data['master_details'] = $this->Salary_struture_model->get_all("hrm_master_salary_structure_details", array("salary_strucure_id" => $data['query']->id, "status" => 1));

        if ($this->input->post()) {
            //echo "<pre>";            print_r($_POST); exit;
            $salary_stru_name = trim($this->input->post('salary_stru_name'));
            $count = count($this->input->post('salary_name_1'));

            $data2 = array(
                "salary_strucure_name" => $salary_stru_name,
                "created_by" => $data["login_data"]["id"],
                "created_date" => date("Y-m-d H:i:s"),
                "status" => 1,
            );

            $value2 = $this->Salary_struture_model->update("hrm_master_salary_structure", array("id" => $cid), $data2);
            $value3 = $this->Salary_struture_model->update("hrm_master_salary_structure_details", array("salary_strucure_id" => $cid), array("status" => "0"));

            for ($j = 0; $j <= $count; $j++) {

                $salary_name = trim($_POST["salary_name_1"][$j]);
                if ($salary_stru_name != "" && $salary_name != "") {

                    if ($j == 0) {
                        $type = 1;
                    } else if ($j == 1) {
                        $type = 2;
                    } else if ($j == 2) {
                        $type = 3;
                    } else if ($j == 3) {
                        $type = 4;
                    } else {
                        $type = 5;
                    }

                    $data1 = array(
                        "salary_strucure_id" => $cid,
                        "cutofftype" => $_POST["cuttype"][$j],
                        "salary_name" => $salary_name,
                        "value" => $_POST["salary_value"][$j],
                        "type" => $_POST["type"][$j],
                        "saletype" => $type,
                        //"created_by" => $data["login_data"]["id"],
                        "created_by" => 1,
                        "created_date" => date("Y-m-d H:i:s"),
                        "status" => 1,
                    );
                    $value = $this->Salary_struture_model->insert("hrm_master_salary_structure_details", $data1);
                }
            }
            $this->session->set_flashdata("success", "Salary structure has added successfully.");
            redirect('hrm/Salary_structure/index', 'refresh');
        }

        $this->load->view('hrm/header', $data);
        $this->load->view('hrm/nav', $data);
        $this->load->view('hrm/salary_structure_edit', $data);
        $this->load->view('hrm/footer', $data);
    }

}

?>