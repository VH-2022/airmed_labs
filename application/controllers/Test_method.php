<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Test_method extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('Test_method_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $data["login_data"] = logindata();
        $this->app_track();
        //ini_set('display_errors', 1);
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

        $branch = $this->input->get('branch');
        $test = trim($this->input->get('test'));

        $data['branch'] = $branch;
        $data['test'] = $test;

        if ($branch != "" || $test != "") {
            $total_row = $this->Test_method_model->test_list_search_num($branch, $test);
            $config = array();
            $config["base_url"] = base_url() . "test_master_1/test_list/?branch=$branch&test=$test";
            $config["total_rows"] = $total_row;
            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Test_method_model->test_list_search($branch, $test, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {
            $totalRows = $this->Test_method_model->num_row('test_method', array('status' => 1));
            $config = array();
            $config["base_url"] = base_url() . "test_method/index/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["query"] = $this->Test_method_model->test_list($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }

        $url = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        $this->session->set_userdata("test_master_r", $url);
        $data['branch_list'] = $this->Test_method_model->master_fun_get_tbl_val("branch_master", array("status" => '1'), array("branch_name", "asc"));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('test_method_list', $data);
        $this->load->view('footer');
    }

    function add() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('branch_name', 'Branch Name', 'trim|required');
        $this->form_validation->set_rules('test_name', 'Test Name', 'trim|required');
        $this->form_validation->set_rules('test_method', "Method", 'trim|required');

        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('branch_name');
            $test_name = $this->input->post('test_name');
            $test_method = $this->input->post('test_method');

            $data['query'] = $this->Test_method_model->master_fun_insert("test_method", array(
                "branch_fk" => $name,
                "test_fk" => $test_name,
                "method" => $test_method,
                "status" => '1',
                "created_date" => date("Y-m-d H:i:s"),
                "created_by" => $data["login_data"]['id']
            ));

            $this->session->set_flashdata("success", array("Test method successfully added."));
            redirect("test_method/index", "refresh");
        } else {
            $data['branch'] = $this->Test_method_model->master_fun_get_tbl_val("branch_master", array("status" => '1'), array("branch_name", "asc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('test_method_add', $data);
            $this->load->view('footer');
        }
    }

    function get_test() {
        $postData = $this->input->post();
        $id = $postData['id'];
        $result1 = $this->Test_method_model->get_val("select city from branch_master where id = $id");
        $city_id = $result1[0]['city'];

        $result = $this->Test_method_model->get_val("select DISTINCT test_master.id,test_master.test_name 
                    from test_master 
                    INNER JOIN test_master_city_price on test_master.id = test_master_city_price.test_fk
                    WHERE test_master.status = '1' AND test_master_city_price.city_fk = $city_id");
        print_r(json_encode($result));
        exit;
    }

    function delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("test_method", array("id", $cid), array("status" => "0"));
        $this->session->set_flashdata("success", array("Test method successfully deleted."));
        redirect("test_method/index", "refresh");
    }

    function edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');

        $this->load->library('form_validation');
        $this->form_validation->set_rules('branch_name', 'Branch Name', 'trim|required');
        $this->form_validation->set_rules('test_name', 'Test Name', 'trim|required');
        $this->form_validation->set_rules('test_method', "Method", 'trim|required');


        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('branch_name');
            $test_name = $this->input->post('test_name');
            $test_method = $this->input->post('test_method');

            $data['query'] = $this->Test_method_model->master_fun_update("test_method", array("id", $data["cid"]), array("branch_fk" => $name, "test_fk" => $test_name, "method" => $test_method));

            $this->session->set_flashdata("success", array("Test method updated successfully."));
            redirect("test_method/index", "refresh");
        } else {
            $data['query'] = $this->Test_method_model->master_fun_get_tbl_val("test_method", array("id" => $data["cid"]), array("id", "desc"));
            $data['branch'] = $this->Test_method_model->master_fun_get_tbl_val("branch_master", array("status" => '1'), array("branch_name", "asc"));

            $id = $data['query'][0]['branch_fk'];
            $result1 = $this->Test_method_model->get_val("select city from branch_master where id = $id");
            $city_id = $result1[0]['city'];
            $data['test_list'] = $this->Test_method_model->get_val("select DISTINCT test_master.id,test_master.test_name 
                    from test_master 
                    INNER JOIN test_master_city_price on test_master.id = test_master_city_price.test_fk
                    WHERE test_master.status = '1' AND test_master_city_price.city_fk = $city_id");

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('test_method_edit', $data);
            $this->load->view('footer');
        }
    }

    function test_csv() {
        $branch = $this->input->get("branch");
        $test = $this->input->get("test");


        if ($branch != "" || $test != "") {
            $temp = "";
            if ($branch != "") {
                $temp .= " AND b.id = '$branch'";
            }

            if ($test != "") {
                $temp .= " AND t.test_name LIKE '%$test%'";
            }

            $q = "SELECT *,tm.id As test_id,tm.method As test_method FROM test_method tm
                 INNER JOIN `test_master` t ON tm.test_fk = t.id
                 INNER JOIN `branch_master` b ON tm.branch_fk = b.id
                 WHERE tm.`status`='1' AND t.status = '1' AND b.status = '1' $temp ORDER BY t.test_name ASC";
        } else {
            $q = "SELECT *,tm.id As test_id,tm.method As test_method FROM test_method tm
                 INNER JOIN `test_master` t ON tm.test_fk = t.id
                 INNER JOIN `branch_master` b ON tm.branch_fk = b.id
                 WHERE tm.`status`='1' AND t.status = '1' AND b.status = '1' $temp ORDER BY t.test_name ASC";
        }
        $test_method = $this->Test_method_model->get_val($q);
        //       echo "<pre>"; print_r($test_method); exit;

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"test_method-" . date('d-M-Y') . ".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        $cnt = 0;
        fputcsv($handle, array("ID", "Branch Name", "Test Name", "Method"));
        foreach ($test_method as $key) {
            fputcsv($handle, array(++$cnt, $key['branch_name'], $key['test_name'], $key['test_method']));
        }
        fclose($handle);
        exit;
    }

}

?>
