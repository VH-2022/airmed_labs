<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Test_tat_master extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('Test_tat_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->helper('string');
        //ini_set('display_errors', 1);
        $data["login_data"] = logindata();
    }

    function index() {
        $data['test_name'] = $test_name = $this->input->get('test');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $data['unsuccess'] = $this->session->flashdata("unsuccess");
        
        if ($test_name != "") {
            $totalRows = $this->Test_tat_model->num_row($test_name);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "Test_tat_master/index?" . http_build_query($get);
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 500;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Test_tat_model->master_get_search($test_name, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        } else {
            $totalRows = $this->Test_tat_model->num_row($test_name);
            $config = array();
            $get = $_GET;
            $config["base_url"] = base_url() . "Test_tat_master/index/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 500;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next <span class="icon-text">&#59230;</span>';
            $config['prev_link'] = '<span class="icon-text">&#59229;</span> Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data['query'] = $this->Test_tat_model->master_get_search($test_name, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }

        //$data['branch'] = $this->Test_tat_model->master_get_branch();
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('test_tat_list', $data);
        $this->load->view('footer');
    }

    function add() {

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        //$data['test_list'] = $this->Test_tat_model->get_val("select id,test_name from test_master where id NOT IN(select id from test_tat where status='1') AND status='1'");
        //$data['package_list'] = $this->Test_tat_model->get_val("select id,title from package_master where 1=1 AND status='1'");
		$data['test_list'] = $this->Test_tat_model->get_val("select id,test_name from test_master where id NOT IN(select test_fk from test_tat where status='1' and type =1) AND status='1'");
        $data['package_list'] = $this->Test_tat_model->get_val("select id,title from package_master where 1=1 AND status='1' and id NOT IN(select test_fk from test_tat where status='1' and type = 2)");
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('test', 'Test', 'trim|required');
        $this->form_validation->set_rules('tat', 'TAT (In Hour)', 'trim|required');
        
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        
        if ($this->form_validation->run() != FALSE) {
            $post['test_fk'] = $this->input->post('test');
            $post['tat'] = $this->input->post('tat');
            $post['type'] = $this->input->post('type1');
            $post['status'] = 1;
            $post['created_date'] = date('Y-m-d h:i:s');
            $bid = $this->Test_tat_model->master_get_insert("test_tat", $post);
            $this->session->set_flashdata("success", 'Test TAT has successfully Added.');
            redirect("Test_tat_master/index", "refresh");
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('test_tat_add', $data);
            $this->load->view('footer');
        }
    }

    function delete() {

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');

        $data['query'] = $this->Test_tat_model->master_tbl_update("test_tat", $cid, array("status" => "0"));

        $this->session->set_flashdata("success", 'Test TAT Details Successfully Removed.');
        redirect("Test_tat_master/index", "refresh");
    }

    function edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['user'] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $this->uri->segment('3');
        $id = $data['cid'];

        $this->load->library('form_validation');
        $this->form_validation->set_rules('test', 'Test', 'trim|required|xss_clean');
        $this->form_validation->set_rules('tat', 'TAT (In Hour)', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() != FALSE) {
            $post['test_fk'] = $this->input->post('test');
            $post['tat'] = $this->input->post('tat');
            $post['type'] = $this->input->post('type1');
            $post['status'] = 1;
            //echo "<pre>"; print_r($post); exit;
            $data['query'] = $this->Test_tat_model->master_tbl_update("test_tat", $id, $post);
            $this->session->set_flashdata("success", 'Test TAT details Successfully Updated');

            redirect("Test_tat_master/index", "refresh");
        } else {
            $data['query'] = $this->Test_tat_model->get_val("select tt.id,tt.test_fk,tt.tat,tm.test_name,tt.type 
                    from test_tat tt
                    INNER JOIN test_master tm on tm.id=tt.test_fk AND tm.status='1' 
                    WHERE tt.status='1' AND tt.id='$id'");

            $data['test_list'] = $this->Test_tat_model->get_val("select id,test_name from test_master where status='1'");
            $data['package_list'] = $this->Test_tat_model->get_val("select id,title from package_master where 1=1 AND status='1'");

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('test_tat_edit', $data);
            $this->load->view('footer');
        }
    }

    function export_csv() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['user'] = $this->user_model->getUser($data["login_data"]["id"]);

        $test_name = $this->input->get('test');
        if ($test_name != "") {
            $temp = " AND tm.test_name LIKE '%$test_name%'";
        }
        if ($test_name != "") {
            $temp1 = " AND pm.title LIKE '%$test_name%'";
        }
        $query = "select tt.id,tt.tat,tt.test_fk,tm.test_name,tt.type
                from test_tat tt 
                LEFT JOIN test_master tm on tm.id = tt.test_fk AND tm.status='1' 
                where 1=1 $temp AND tt.status='1' AND tt.type='1' ORDER BY tt.id DESC";

        $query = $this->Test_tat_model->get_val($query);

        $query1 = "select tt.id,tt.tat,tt.test_fk,pm.title,tt.type
                from test_tat tt 
                LEFT JOIN package_master pm on pm.id = tt.test_fk AND pm.status='1' 
                where 1=1 $temp1 AND tt.status='1' AND tt.type='2' ORDER BY tt.id DESC";

        $query1 = $this->Test_tat_model->get_val($query1);

        $final_array = array_merge($query, $query1);

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Test TAT.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("Sr No.", "Test/Package Name", "Type", "TAT (In Hour)"));

        $cnt = 0;
        foreach ($final_array as $q) {
            $cnt++;
            if ($q->type == '1') {
                $test_package = $q->test_name;
                $type = 'Test';
            } else {
                $test_package = $q->title;
                $type = 'Package';
            }
            fputcsv($handle, array($cnt, $test_package, $type, $q->tat));
        }

        fclose($handle);
        exit;
    }

    public function import_csv() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $this->load->library('csvimport');

        $config['upload_path'] = './upload/csv/';
        $config['allowed_types'] = 'csv';
        $config['file_name'] = time() . $_FILES['id_browes']['name'];
        $config['file_name'] = str_replace(' ', '_', $config['file_name']);
        $_FILES['id_browes']['name'];
        $file1 = $config['file_name'];
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('id_browes')) {
            $error = $this->upload->display_errors();
            $ses = array($error);
            $this->session->set_flashdata("success", $ses);
            redirect("Test_tat_master/index", "refresh");
        } else {
            $file_data = $this->upload->data();
            $file_path = './upload/csv/' . $file_data['file_name'];
            $cnt = 0;
            if ($this->csvimport->get_array($file_path)) {
                $csv_array = $this->csvimport->get_array($file_path);

                $countall = count($csv_array);

                foreach ($csv_array as $row) {
                    $test_name = trim($row['Test/Package Name']);
                    $type = trim($row['Type']);
                    $tat = trim($row['TAT (In Hour)']);
                    if ($type == 'Test') {
                        $test_fk = $this->Test_tat_model->get_val("select id from test_master where test_name='$test_name'")[0]->id;
                        if ($test_fk != "" && $tat != "") {
                            $exist = $this->Test_tat_model->get_val("select id,test_fk from test_tat where status='1' AND test_fk='$test_fk' AND type='1'");
                            if (!empty($exist)) {
                                $data['query'] = $this->Test_tat_model->master_tbl_update("test_tat", $exist[0]->id, array("tat" => $tat));
                            } else {
                                $data['query'] = $this->Test_tat_model->master_get_insert("test_tat", array("test_fk" => $test_fk, "tat" => $tat, 'type' => '1', "status" => 1, "created_date" => date("Y-m-d H:i:s")));
                            }
                        }
                    } else if ($type == 'Package') {
                        $package_fk = $this->Test_tat_model->get_val("select id from package_master where title='$test_name'")[0]->id;
                        if ($package_fk != "" && $tat != "") {
                            $exist = $this->Test_tat_model->get_val("select id,test_fk from test_tat where status='1' AND test_fk='$package_fk' AND type='2'");
                            if (!empty($exist)) {
                                $data['query'] = $this->Test_tat_model->master_tbl_update("test_tat", $exist[0]->id, array("tat" => $tat));
                            } else {
                                $data['query'] = $this->Test_tat_model->master_get_insert("test_tat", array("test_fk" => $package_fk, "tat" => $tat, 'type' => '2', "status" => 1, "created_date" => date("Y-m-d H:i:s")));
                            }
                        }
                    }
                }
            }
            $this->session->set_flashdata('success', 'File is uploaded successfully');
            redirect("Test_tat_master/index", "refresh");
        }
    }

}

?>
