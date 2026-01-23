<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Investor_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('Investor_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');

        $data["login_data"] = logindata();
    }

    function board_of_directors_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $name = $this->input->get('name');
        $mobile = $this->input->get('mobile');
        $email = $this->input->get('email');
        $type = $this->input->get('type');
        $test_city = $this->input->get("test_city");
        if ($name != "" || $email != "" || $mobile != "" || $type != ''||$test_city!='') {
            $srchdata = array("name" => $name, "email" => $email, "mobile" => $mobile, "type" => $type,"test_city"=>$test_city);
            $data['name'] = $name;
            $data['mobile'] = $mobile;
            $data['email'] = $email;
            $data['type'] = $type;
            $data["test_city1"]=$test_city;
            $totalRows = $this->Investor_model->board_of_directors_count_list($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "Investor_master/board_of_directors_list/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["query"] = $this->Investor_model->board_of_directors_list($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $srchdata = array();
            $totalRows = $this->Investor_model->board_of_directors_count_list($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "Investor_master/board_of_directors_list/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["query"] = $this->Investor_model->board_of_directors_list($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }
        // echo "<pre>";print_r($data['query']);die();
        $data['test_city'] = $this->Investor_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
        $data['success'] = $this->session->flashdata("success");
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('board_of_director_list', $data);
        $this->load->view('footer');
    }

    function board_of_directors_add() {

        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('position', 'Position', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('display_order', 'Display Order', 'required');
        if (empty($_FILES['image']['name'])) {
            $this->form_validation->set_rules('image', 'Image', 'required');
        }

        if ($this->form_validation->run() != FALSE) {

            $name = $this->input->post('name');
            $position = $this->input->post('position');
            $description = $this->input->post('description');
            $display_order = $this->input->post('display_order');
            $status = $this->input->post('status');

            $config['upload_path']   = './upload/board/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_name']     = time() . '_' . $_FILES["image"]["name"];
            $config['max_size']      = 2048;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload("image")) {

                $data['error'] = array('error' => $this->upload->display_errors());

                $this->load->view('header');
                $this->load->view('nav', $data);
                $this->load->view('board_of_directors_add', $data);
                $this->load->view('footer');
                return;
            }

            $uploadData = $this->upload->data();
            $file_name = $uploadData['file_name'];

            $insertData = array(
                "name" => $name,
                "position" => $position,
                "image" => $file_name,
                "description" => $description,
                "display_order" => $display_order,
                "status" => 1,
                "created_at" => date("Y-m-d H:i:s")
            );

            $this->Investor_model->master_fun_insert("board_of_directors", $insertData);

            $this->session->set_flashdata("success", array("Director added successfully."));
            redirect("Investor_master/board_of_directors_list", "refresh");

        } else {

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('board_of_directors_add', $data);
            $this->load->view('footer');
        }
    }

    function board_of_directors_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("board_of_directors", array("id", $cid), array("status" => "0"));

        $this->session->set_flashdata("success", array("Director successfully deleted."));
        redirect("Investor_master/board_of_directors_list", "refresh");
    }

    function board_of_directors_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');

        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('position', 'Position', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('display_order', 'Display Order', 'required');

        if ($this->form_validation->run() != FALSE) {

            $name = $this->input->post('name');
            $position = $this->input->post('position');
            $description = $this->input->post('description');
            $display_order = $this->input->post('display_order');

            if (!empty($_FILES["image"]["name"])) {

                $config['upload_path'] = './upload/board/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = time() . '_' . $_FILES["image"]["name"];
                $config['max_size'] = 2048;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload("image")) {

                    $data['error'] = array('error' => $this->upload->display_errors());
                    $data['query'] = $this->Investor_model
                        ->master_fun_get_tbl_val("board_of_directors", array("id" => $data['cid']), array("id", "desc"));

                    $this->load->view('header');
                    $this->load->view('nav', $data);
                    $this->load->view('board_of_directors_edit', $data);
                    $this->load->view('footer');
                    return;
                }

                $uploadData = $this->upload->data();

                $file_name = $uploadData['file_name'];

                $updateData = array(
                    "name" => $name,
                    "position" => $position,
                    "image" => $file_name,
                    "description" => $description,
                    "display_order" => $display_order,
                );

            } else {
                $updateData = array(
                    "name" => $name,
                    "position" => $position,
                    "description" => $description,
                    "display_order" => $display_order,
                );
            }
            $this->Investor_model->master_fun_update("board_of_directors",array("id", $data["cid"]),$updateData);

            $this->session->set_flashdata("success", array("Director successfully updated."));
            redirect("Investor_master/board_of_directors_list", "refresh");

        } else {

            $data['director'] = $this->Investor_model->master_fun_get_tbl_val("board_of_directors", array("id" => $data["cid"]), array("id", "desc"));

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('board_of_directors_edit', $data);
            $this->load->view('footer');
        }
    }

    function committees_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $srchdata = array();
        $totalRows = $this->Investor_model->committees_count_list($srchdata);
        $config = array();
        $config["base_url"] = base_url() . "Investor_master/committees_list/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 50;
        $config["uri_segment"] = 3;
        $config['next_link'] = 'Next &rsaquo;';
        $config['prev_link'] = '&lsaquo; Previous';
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["query"] = $this->Investor_model->committees_list($srchdata, $config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();

        $data['test_city'] = $this->Investor_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
        $data['success'] = $this->session->flashdata("success");
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('committees_list', $data);
        $this->load->view('footer');
    }

    public function committees_add()
    {
        if (!is_loggedin()) redirect('login');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('committee_name','Committee Name','required');

        if ($this->form_validation->run()) {

            // Insert Committee
            $committee = [
                'committee_name' => $this->input->post('committee_name'),
                'craeted_date'   => date('Y-m-d H:i:s')
            ];

            $committee_id = $this->Investor_model->master_fun_insert('committees',$committee);

            // Insert Members
            $names = $this->input->post('member_name');
            $designations = $this->input->post('designation');
            $roles = $this->input->post('role');

            for($i=0; $i<count($names); $i++){
                if(!empty($names[$i])){
                    $member = [
                        'committee_id' => $committee_id,
                        'member_name'  => $names[$i],
                        'designation'  => $designations[$i],
                        'role'         => $roles[$i]
                    ];
                    $this->Investor_model->master_fun_insert('committee_members',$member);
                }
            }

            $this->session->set_flashdata('success','Committee added successfully');
            redirect('Investor_master/committees_list');

        } else {
            $this->load->view('header',$data);
            $this->load->view('nav',$data);
            $this->load->view('committees_add');
            $this->load->view('footer');
        }
    }

    public function committees_edit($id)
    {
        if (!is_loggedin()) redirect('login');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['committee'] = $this->db->get_where('committees',['id'=>$id])->row_array();
        $data['members']  = $this->db->get_where('committee_members',['committee_id'=>$id])->result_array();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('committee_name','Committee Name','required');

        if ($this->form_validation->run()) {

            // Update Committee
            $this->db->where('id',$id)->update('committees',[
                'committee_name' => $this->input->post('committee_name')
            ]);

            // Delete Old Members
            $this->db->delete('committee_members',['committee_id'=>$id]);

            // Insert New Members
            $names = $this->input->post('member_name');
            $designations = $this->input->post('designation');
            $roles = $this->input->post('role');

            for($i=0; $i<count($names); $i++){
                if(!empty($names[$i])){
                    $member = [
                        'committee_id' => $id,
                        'member_name'  => $names[$i],
                        'designation'  => $designations[$i],
                        'role'         => $roles[$i]
                    ];
                    $this->Investor_model->master_fun_insert('committee_members',$member);
                }
            }

            $this->session->set_flashdata('success','Committee updated');
            redirect('Investor_master/committees_list');

        } else {
            $this->load->view('header',$data);
            $this->load->view('nav',$data);
            $this->load->view('committees_edit',$data);
            $this->load->view('footer');
        }
    }

    public function tc_id_list()
    {
        if (!is_loggedin()) redirect('login');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['query'] = $this->db
            ->order_by('id','DESC')
            ->get('tc_appointment_id')
            ->result_array();

        $this->load->view('header',$data);
        $this->load->view('nav',$data);
        $this->load->view('tc_id_list',$data);
        $this->load->view('footer');
    }

    public function tc_id_add()
    {
        if (!is_loggedin()) redirect('login');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('title','Title','required');

        if ($this->form_validation->run()) {

            if($_FILES['pdf']['name'] != ''){

                $config['upload_path'] = './upload/tc_id/';
                $config['allowed_types'] = 'pdf';
                $config['file_name'] = time().'_'.$_FILES['pdf']['name'];

                $this->load->library('upload',$config);

                if(!$this->upload->do_upload('pdf')){
                    $data['error'] = $this->upload->display_errors();
                } else {
                    $file = $this->upload->data('file_name');

                    $insert = [
                    'title' => $this->input->post('title'),
                    'pdf_file' => $file,
                    'created_at' => date('Y-m-d H:i:s')
                    ];

                    $this->db->insert('tc_appointment_id',$insert);
                    $this->session->set_flashdata('success','added successfully');
                    redirect('Investor_master/tc_id_list');

                }
            }

        }

        $this->load->view('header',$data);
        $this->load->view('nav',$data);
        $this->load->view('tc_id_add');
        $this->load->view('footer');
    }

    public function tc_id_edit($id)
    {
        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['row'] = $this->db->get_where('tc_appointment_id',['id'=>$id])->row_array();

        if (!$data['row']) {
            redirect('Investor_master/tc_id_list');
        }

        if ($this->input->post()) {

            $update = [
                'title' => $this->input->post('title')
            ];

            if ($_FILES['pdf']['name'] != '') {

                $config['upload_path'] = './upload/tc_id/';
                $config['allowed_types'] = 'pdf';
                $config['file_name'] = time().'_'.$_FILES['pdf']['name'];

                $this->load->library('upload',$config);

                if ($this->upload->do_upload('pdf')) {

                    $file = $this->upload->data('file_name');
                    $update['pdf_file'] = $file;

                    // delete old file
                    @unlink('./upload/tc_id/'.$data['row']['pdf_file']);
                }
            }

            $this->db->where('id',$id)->update('tc_appointment_id',$update);
            $this->session->set_flashdata('success','updated successfully');
            redirect('Investor_master/tc_id_list');
        }


        $this->load->view('header',$data);
        $this->load->view('nav',$data);
        $this->load->view('tc_id_edit',$data);
        $this->load->view('footer');
    }

    public function tc_id_delete($id)
    {
        if (!is_loggedin()) redirect('login');

        $row = $this->db->get_where('tc_appointment_id',['id'=>$id])->row_array();

        if ($row) {
            @unlink('./upload/tc_id/'.$row['pdf_file']);
            $this->db->where('id',$id)->delete('tc_appointment_id');
        }

        redirect('Investor_master/tc_id_list');
    }

    public function policies_list()
    {
        if (!is_loggedin()) redirect('login');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['query'] = $this->db
            ->order_by('id','DESC')
            ->get('policies_programs')
            ->result_array();

        $this->load->view('header',$data);
        $this->load->view('nav',$data);
        $this->load->view('policies_list',$data);
        $this->load->view('footer');
    }


    public function policies_add()
    {
        if (!is_loggedin()) redirect('login');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        if($_FILES['pdf']['name'] != ''){

            $config['upload_path'] = './upload/policies/';
            $config['allowed_types'] = 'pdf';
            $config['file_name'] = time().'_'.$_FILES['pdf']['name'];

            $this->load->library('upload',$config);

            if(!$this->upload->do_upload('pdf')){
                $data['error'] = $this->upload->display_errors();
            } else {

                $file = $this->upload->data('file_name');

                $insert = [
                'title' => $this->input->post('title'),
                'pdf_file' => $file,
                'created_at' => date('Y-m-d H:i:s')
                ];

                $this->db->insert('policies_programs',$insert);

                $this->session->set_flashdata('success','Policy added successfully');
                redirect('Investor_master/policies_list');
            }
        }

        $this->load->view('header',$data);
        $this->load->view('nav',$data);
        $this->load->view('policies_add');
        $this->load->view('footer');
    }

    public function policies_delete($id)
    {
        if (!is_loggedin()) redirect('login');

        // Get record
        $row = $this->db->get_where('policies_programs',['id'=>$id])->row_array();

        if ($row) {

            // Delete PDF file
            @unlink('./upload/policies/'.$row['pdf_file']);

            // Delete DB record
            $this->db->where('id',$id)->delete('policies_programs');

            $this->session->set_flashdata('success','Policy deleted successfully');
        }

        redirect('Investor_master/policies_list');
    }

    function financial_category_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $srchdata = array();
        $totalRows = $this->Investor_model->financial_category_count_list($srchdata);
        $config = array();
        $config["base_url"] = base_url() . "Investor_master/board_of_directors_list/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 50;
        $config["uri_segment"] = 3;
        $config['next_link'] = 'Next &rsaquo;';
        $config['prev_link'] = '&lsaquo; Previous';
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["query"] = $this->Investor_model->financial_category_list($srchdata, $config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();

        // echo "<pre>";print_r($data['query']);die();
        $data['test_city'] = $this->Investor_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
        $data['success'] = $this->session->flashdata("success");
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('financial_category_list', $data);
        $this->load->view('footer');
    }

    function financial_category_add() {

        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        if ($this->form_validation->run() != FALSE) {

            $name = $this->input->post('name');
            $insertData = array(
                "name" => $name,
            );

            $this->Investor_model->master_fun_insert("financial_category", $insertData);

            $this->session->set_flashdata("success", array("Financial Category added successfully."));
            redirect("Investor_master/financial_category_list", "refresh");

        } else {

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('financial_category_add', $data);
            $this->load->view('footer');
        }
    }

    function financial_category_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');

        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        if ($this->form_validation->run() != FALSE) {

            $name = $this->input->post('name');

            $updateData = array(
                "name" => $name,
            );

            $this->Investor_model->master_fun_update("financial_category",array("id", $data["cid"]),$updateData);

            $this->session->set_flashdata("success", array("Financial Category successfully updated."));
            redirect("Investor_master/financial_category_list", "refresh");

        } else {

            $data['director'] = $this->Investor_model->master_fun_get_tbl_val("financial_category", array("id" => $data["cid"]), array("id", "desc"));

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('financial_category_edit', $data);
            $this->load->view('footer');
        }
    }

    function financial_category_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("financial_category", array("id", $cid), array("status" => "0"));

        $this->session->set_flashdata("success", array("Financial Category successfully deleted."));
        redirect("Investor_master/financial_category_list", "refresh");
    }

    function financial_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $srchdata = array();
        $totalRows = $this->Investor_model->financial_count_list($srchdata);
        $config = array();
        $config["base_url"] = base_url() . "Investor_master/financial_list/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config['next_link'] = 'Next &rsaquo;';
        $config['prev_link'] = '&lsaquo; Previous';
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["query"] = $this->Investor_model->financial_list($srchdata, $config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $data['success'] = $this->session->flashdata("success");
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('financial_list', $data);
        $this->load->view('footer');
    }

    function financial_add() {

        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->library('form_validation');

        $this->form_validation->set_rules('category_id', 'Category', 'trim|required');
        $this->form_validation->set_rules('report_year', 'Report Year', 'trim|required');
        $this->form_validation->set_rules('file_title', 'File Title', 'trim|required');
        if (empty($_FILES['pdf']['name'])) {
            $this->form_validation->set_rules('pdf', 'PDF', 'required');
        }

        if ($this->form_validation->run() != FALSE) {


            $category_id = $this->input->post('category_id');
            $report_year = $this->input->post('report_year');
            $file_title = $this->input->post('file_title');
            $file = "";
            if($_FILES['pdf']['name'] != ''){
                $config['upload_path'] = './upload/financial/';
                $config['allowed_types'] = 'pdf';
                $config['file_name'] = time().'_'.$_FILES['pdf']['name'];

                $this->load->library('upload',$config);
                if(!$this->upload->do_upload('pdf')){
                    $data['error'] = $this->upload->display_errors();
                } else {
                    $file = $this->upload->data('file_name');
                }
            }
            $insertData = array(
                "category_id" => $category_id,
                "report_year" => $report_year,
                "file_title" => $file_title,
                "file_path" => $file,

            );

            $this->Investor_model->master_fun_insert("financial", $insertData);

            $this->session->set_flashdata("success", array("Financial added successfully."));
            redirect("Investor_master/financial_list", "refresh");

        } else {
            $data['category'] = $this->Investor_model->master_fun_get_tbl_val("financial_category", array("status" => 1), array("id", "desc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('financial_add', $data);
            $this->load->view('footer');
        }
    }

    function financial_edit() {

        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment(3);

        $this->load->library('form_validation');

        $this->form_validation->set_rules('category_id', 'Category', 'trim|required');
        $this->form_validation->set_rules('report_year', 'Report Year', 'trim|required');
        $this->form_validation->set_rules('file_title', 'File Title', 'trim|required');

        if ($this->form_validation->run() != FALSE) {

            $category_id = $this->input->post('category_id');
            $report_year = $this->input->post('report_year');
            $file_title  = $this->input->post('file_title');

            $file = $this->input->post('old_file');

            if (isset($_FILES['pdf']) && $_FILES['pdf']['name'] != '') {

                $config['upload_path']   = './upload/financial/';
                $config['allowed_types'] = 'pdf';
                $config['file_name']     = time().'_'.$_FILES['pdf']['name'];
                $config['overwrite']    = FALSE;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('pdf')) {

                    $file = $this->upload->data('file_name');

                    $old = $this->input->post('old_file');
                    if ($old && file_exists('./upload/financial/'.$old)) {
                        unlink('./upload/financial/'.$old);
                    }

                } else {
                    echo $this->upload->display_errors();
                    exit;
                }
            }

            $updateData = array(
                "category_id" => $category_id,
                "report_year" => $report_year,
                "file_title"  => $file_title,
                "file_path"   => $file,
            );

            $this->Investor_model->master_fun_update("financial",array("id",$data["cid"]),$updateData);

            $this->session->set_flashdata("success", array("Financial updated successfully."));
            redirect("Investor_master/financial_list", "refresh");

        } else {

            $data['category'] = $this->Investor_model->master_fun_get_tbl_val("financial_category", array("status" => 1), array("id", "desc"));

            $data['director'] = $this->Investor_model->master_fun_get_tbl_val("financial", array("id" => $data["cid"]), array("id", "desc"));

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('financial_edit', $data);
            $this->load->view('footer');
        }
    }

    function financial_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("financial", array("id", $cid), array("status" => "0"));

        $this->session->set_flashdata("success", array("Financial successfully deleted."));
        redirect("Investor_master/financial_list", "refresh");
    }

    function corporate_presentation_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $srchdata = array();
        $totalRows = $this->Investor_model->corporate_presentation_count_list($srchdata);
        $config = array();
        $config["base_url"] = base_url() . "Investor_master/corporate_presentation_list/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config['next_link'] = 'Next &rsaquo;';
        $config['prev_link'] = '&lsaquo; Previous';
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["query"] = $this->Investor_model->corporate_presentation_list($srchdata, $config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();

        $data['success'] = $this->session->flashdata("success");
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('corporate_presentation_list', $data);
        $this->load->view('footer');
    }

    function corporate_presentation_add() {

        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->library('form_validation');

        $this->form_validation->set_rules('pdf_title', 'PDF Title', 'trim|required');
        if (empty($_FILES['pdf']['name'])) {
            $this->form_validation->set_rules('pdf', 'PDF', 'required');
        }

        if ($this->form_validation->run() != FALSE) {


            $pdf_title = $this->input->post('pdf_title');
            $file = "";
            if($_FILES['pdf']['name'] != ''){
                $config['upload_path'] = './upload/corporate_presentation/';
                $config['allowed_types'] = 'pdf';
                $config['file_name'] = time().'_'.$_FILES['pdf']['name'];

                $this->load->library('upload',$config);
                if(!$this->upload->do_upload('pdf')){
                    $data['error'] = $this->upload->display_errors();
                } else {
                    $file = $this->upload->data('file_name');
                }
            }
            $insertData = array(
                "pdf_title" => $pdf_title,
                "pdf_path" => $file,

            );

            $this->Investor_model->master_fun_insert("corporate_presentation", $insertData);

            $this->session->set_flashdata("success", array("Corporate Presentation added successfully."));
            redirect("Investor_master/corporate_presentation_list", "refresh");

        } else {
            $data['category'] = $this->Investor_model->master_fun_get_tbl_val("financial_category", array("status" => 1), array("id", "desc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('corporate_presentation_add', $data);
            $this->load->view('footer');
        }
    }

    function corporate_presentation_edit() {

        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment(3);

        $this->load->library('form_validation');

        $this->form_validation->set_rules('pdf_title', 'PDF Title', 'trim|required');

        if ($this->form_validation->run() != FALSE) {

            $pdf_title = $this->input->post('pdf_title');

            $file = $this->input->post('old_file');

            if (isset($_FILES['pdf']) && $_FILES['pdf']['name'] != '') {

                $config['upload_path']   = './upload/corporate_presentation/';
                $config['allowed_types'] = 'pdf';
                $config['file_name']     = time().'_'.$_FILES['pdf']['name'];
                $config['overwrite']    = FALSE;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('pdf')) {

                    $file = $this->upload->data('file_name');

                    $old = $this->input->post('old_file');
                    if ($old && file_exists('./upload/corporate_presentation/'.$old)) {
                        unlink('./upload/corporate_presentation/'.$old);
                    }

                } else {
                    echo $this->upload->display_errors();
                    exit;
                }
            }

            $updateData = array(
                "pdf_title" => $pdf_title,
                "pdf_path"   => $file,
            );

            $this->Investor_model->master_fun_update("corporate_presentation",array("id",$data["cid"]),$updateData);

            $this->session->set_flashdata("success", array("Corporate Presentation updated successfully."));
            redirect("Investor_master/corporate_presentation_list", "refresh");

        } else {

            $data['director'] = $this->Investor_model->master_fun_get_tbl_val("corporate_presentation", array("id" => $data["cid"]), array("id", "desc"));

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('corporate_presentation_edit', $data);
            $this->load->view('footer');
        }
    }

    function corporate_presentation_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("corporate_presentation", array("id", $cid), array("status" => "0"));

        $this->session->set_flashdata("success", array("Corporate Presentation successfully deleted."));
        redirect("Investor_master/corporate_presentation_list", "refresh");
    }

    function investor_contact_list() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $srchdata = array();
        $totalRows = $this->Investor_model->investor_contact_count_list($srchdata);

        $config["base_url"] = base_url() . "Investor_master/investor_contact_list/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data["query"] = $this->Investor_model->investor_contact_list($srchdata, $config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();

        $data['success'] = $this->session->flashdata("success");

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('investor_contact_list', $data);
        $this->load->view('footer');
    }

    function investor_contact_add() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->library('form_validation');

        $this->form_validation->set_rules('title','Title','required');
        $this->form_validation->set_rules('name','Name','required');
        $this->form_validation->set_rules('designation','Designation','required');
        $this->form_validation->set_rules('address','Address','required');
        $this->form_validation->set_rules('telephone','Telephone','required');
        $this->form_validation->set_rules('email','Email','required');
        $this->form_validation->set_rules('fax','Fax','required');

        if ($this->form_validation->run()) {

            $insertData = array(
                "title" => $this->input->post('title'),
                "name" => $this->input->post('name'),
                "designation" => $this->input->post('designation'),
                "address" => $this->input->post('address'),
                "telephone" => $this->input->post('telephone'),
                "email" => $this->input->post('email'),
                "fax" => $this->input->post('fax'),
            );

            $this->Investor_model->master_fun_insert("investor_contact", $insertData);

            $this->session->set_flashdata("success", array("Investor Contact added successfully."));
            redirect("Investor_master/investor_contact_list");

        } else {

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('investor_contact_add', $data);
            $this->load->view('footer');
        }
    }

    function investor_contact_edit() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data["cid"] = $this->uri->segment(3);

        $this->load->library('form_validation');

        $this->form_validation->set_rules('title','Title','required');

        if ($this->form_validation->run()) {

            $updateData = array(
                "title" => $this->input->post('title'),
                "name" => $this->input->post('name'),
                "designation" => $this->input->post('designation'),
                "address" => $this->input->post('address'),
                "telephone" => $this->input->post('telephone'),
                "email" => $this->input->post('email'),
                "fax" => $this->input->post('fax'),
            );

            $this->Investor_model->master_fun_update("investor_contact", array("id",$data["cid"]), $updateData);

            $this->session->set_flashdata("success", array("Investor Contact updated successfully."));
            redirect("Investor_master/investor_contact_list");

        } else {

            $data['director'] = $this->Investor_model->master_fun_get_tbl_val(
                "investor_contact",
                array("id" => $data["cid"]),
                array("id", "desc")
            );

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('investor_contact_edit', $data);
            $this->load->view('footer');
        }
    }

    function investor_contact_delete() {

        if (!is_loggedin()) redirect('login');

        $cid = $this->uri->segment(3);

        $this->Investor_model->master_fun_update(
            "investor_contact",
            array("id", $cid),
            array("status" => "0")
        );

        $this->session->set_flashdata("success", array("Investor Contact deleted successfully."));
        redirect("Investor_master/investor_contact_list");
    }

    function postal_ballot_list() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $srchdata = array();
        $totalRows = $this->Investor_model->postal_ballot_category_count();

        $config["base_url"] = base_url() . "Investor_master/postal_ballot_list/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data["query"] = $this->Investor_model->postal_ballot_category_list($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();

        $data['success'] = $this->session->flashdata("success");

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('postal_ballot_list', $data);
        $this->load->view('footer');
    }

    function postal_ballot_add() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Title', 'trim|required');

        if ($this->form_validation->run() != FALSE) {

            // 1. Insert Category
            $catData = array(
                "title" => $this->input->post('title')
            );

            $category_id = $this->Investor_model->master_fun_insert(
                "postal_ballot_category",
                $catData
            );

            // 2. Upload Multiple PDFs
            if (!empty($_FILES['pdf']['name'][0])) {

                foreach ($_FILES['pdf']['name'] as $key => $name) {

                    $_FILES['file']['name']     = $_FILES['pdf']['name'][$key];
                    $_FILES['file']['type']     = $_FILES['pdf']['type'][$key];
                    $_FILES['file']['tmp_name'] = $_FILES['pdf']['tmp_name'][$key];
                    $_FILES['file']['error']    = $_FILES['pdf']['error'][$key];
                    $_FILES['file']['size']     = $_FILES['pdf']['size'][$key];

                    $config['upload_path'] = './upload/postal_ballot/';
                    $config['allowed_types'] = 'pdf';
                    $config['file_name'] = time().'_'.$name;

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('file')) {

                        $fileData = $this->upload->data();

                        $fileInsert = array(
                            "category_id" => $category_id,
                            "pdf_title"   => $this->input->post('pdf_title')[$key],
                            "pdf_path"    => $fileData['file_name']
                        );

                        $this->Investor_model->master_fun_insert(
                            "postal_ballot_files",
                            $fileInsert
                        );
                    }
                }
            }

            $this->session->set_flashdata("success", array("Postal Ballot added successfully."));
            redirect("Investor_master/postal_ballot_list", "refresh");

        } else {

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('postal_ballot_add', $data);
            $this->load->view('footer');
        }
    }

    function postal_ballot_edit() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $cat_id = $this->uri->segment(3);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Title', 'trim|required');

        if ($this->form_validation->run() != FALSE) {

            // Update Category
            $updateCat = array(
                "title" => $this->input->post('title')
            );

            $this->Investor_model->master_fun_update(
                "postal_ballot_category",
                array("id",$cat_id),
                $updateCat
            );

            // Add new PDFs (if any)
            if (!empty($_FILES['pdf']['name'][0])) {

                foreach ($_FILES['pdf']['name'] as $key => $name) {

                    if($name == '') continue;

                    $_FILES['file']['name']     = $_FILES['pdf']['name'][$key];
                    $_FILES['file']['type']     = $_FILES['pdf']['type'][$key];
                    $_FILES['file']['tmp_name'] = $_FILES['pdf']['tmp_name'][$key];
                    $_FILES['file']['error']    = $_FILES['pdf']['error'][$key];
                    $_FILES['file']['size']     = $_FILES['pdf']['size'][$key];

                    $config['upload_path'] = './upload/postal_ballot/';
                    $config['allowed_types'] = 'pdf';
                    $config['file_name'] = time().'_'.$name;

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('file')) {

                        $fileData = $this->upload->data();

                        $fileInsert = array(
                            "category_id" => $cat_id,
                            "pdf_title"   => $this->input->post('pdf_title')[$key],
                            "pdf_path"    => $fileData['file_name']
                        );

                        $this->Investor_model->master_fun_insert(
                            "postal_ballot_files",
                            $fileInsert
                        );
                    }
                }
            }

            $this->session->set_flashdata("success", array("Postal Ballot updated successfully."));
            redirect("Investor_master/postal_ballot_list", "refresh");

        } else {

            $data['category'] = $this->Investor_model->master_fun_get_tbl_val(
                "postal_ballot_category",
                array("id"=>$cat_id),
                array("id","desc")
            );

            $data['files'] = $this->Investor_model->master_fun_get_tbl_val(
                "postal_ballot_files",
                array("category_id"=>$cat_id,"status"=>1),
                array("id","desc")
            );

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('postal_ballot_edit', $data);
            $this->load->view('footer');
        }
    }

    function postal_ballot_delete() {

        if (!is_loggedin()) redirect('login');

        $cat_id = $this->uri->segment(3);

        // Get all PDFs of this category
        $files = $this->Investor_model->master_fun_get_tbl_val(
            "postal_ballot_files",
            array("category_id"=>$cat_id),
            array("id","desc")
        );

        // Delete physical files
        foreach($files as $f){
            if($f['pdf_path'] && file_exists('./upload/postal_ballot/'.$f['pdf_path'])){
                unlink('./upload/postal_ballot/'.$f['pdf_path']);
            }
        }

        // Soft delete category
        $this->Investor_model->master_fun_update(
            "postal_ballot_category",
            array("id",$cat_id),
            array("status"=>0)
        );

        // Soft delete all PDFs
        $this->Investor_model->master_fun_update(
            "postal_ballot_files",
            array("category_id",$cat_id),
            array("status"=>0)
        );

        $this->session->set_flashdata("success", array("Postal Ballot deleted successfully."));
        redirect("Investor_master/postal_ballot_list","refresh");
    }

    function rhp()
    {
        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['rhp'] = $this->db->get('rhp_master')->row_array();
        $data['success'] = $this->session->flashdata("success");

        if ($this->input->post('content')) {

            $content = $this->input->post('content');

            $pdf_name = (isset($data['rhp']['pdf_file']) && $data['rhp']['pdf_file'] != '')
            ? $data['rhp']['pdf_file']
            : '';

            if (!empty($_FILES['pdf_file']['name'])) {

                $config['upload_path']   = './upload/rhp/';
                $config['allowed_types'] = 'pdf';
                $config['file_name']     = time().'_rhp';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('pdf_file')) {
                    $uploadData = $this->upload->data();
                    $pdf_name = $uploadData['file_name'];
                } else {
                    $this->session->set_flashdata('success', [$this->upload->display_errors()]);
                    redirect('Investor_master/rhp');
                }
            }

            $saveData = [
                'content' => $content,
                'pdf_file' => $pdf_name,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($data['rhp']) {
                $this->db->where('id', $data['rhp']['id'])->update('rhp_master', $saveData);
            } else {
                $saveData['created_at'] = date('Y-m-d H:i:s');
                $this->db->insert('rhp_master', $saveData);
            }

            $this->session->set_flashdata('success', ['RHP updated Successfully']);
            redirect('Investor_master/rhp');
        }

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('rhp_form', $data);
        $this->load->view('footer');
    }

    function stock_exchange_list() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $srchdata = array();
        $totalRows = $this->Investor_model->stock_exchange_category_count();

        $config["base_url"] = base_url() . "Investor_master/stock_exchange_list/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data["query"] = $this->Investor_model->stock_exchange_category_list($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();

        $data['success'] = $this->session->flashdata("success");

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('stock_exchange_list', $data);
        $this->load->view('footer');
    }

    function stock_exchange_add() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Title', 'trim|required');

        if ($this->form_validation->run() != FALSE) {

            $catData = array(
                "title" => $this->input->post('title')
            );

            $category_id = $this->Investor_model->master_fun_insert(
                "stock_exchange_category",
                $catData
            );

            if (!empty($_FILES['pdf']['name'][0])) {

                foreach ($_FILES['pdf']['name'] as $key => $name) {

                    $_FILES['file']['name']     = $_FILES['pdf']['name'][$key];
                    $_FILES['file']['type']     = $_FILES['pdf']['type'][$key];
                    $_FILES['file']['tmp_name'] = $_FILES['pdf']['tmp_name'][$key];
                    $_FILES['file']['error']    = $_FILES['pdf']['error'][$key];
                    $_FILES['file']['size']     = $_FILES['pdf']['size'][$key];

                    $config['upload_path'] = './upload/stock_exchange/';
                    $config['allowed_types'] = 'pdf';
                    $config['file_name'] = time().'_'.$name;

                    $this->load->library('upload');
                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('file')) {

                        $fileData = $this->upload->data();

                        $fileInsert = array(
                            "category_id" => $category_id,
                            "pdf_title"   => $this->input->post('pdf_title')[$key],
                            "pdf_path"    => $fileData['file_name']
                        );

                        $this->Investor_model->master_fun_insert(
                            "stock_exchange_files",
                            $fileInsert
                        );
                    }
                }
            }

            $this->session->set_flashdata("success", array("Stock Exchange Disclosure added successfully."));
            redirect("Investor_master/stock_exchange_list", "refresh");

        } else {

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('stock_exchange_add', $data);
            $this->load->view('footer');
        }
    }

    function stock_exchange_edit() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $cat_id = $this->uri->segment(3);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Title', 'trim|required');

        if ($this->form_validation->run() != FALSE) {

            $updateCat = array(
                "title" => $this->input->post('title')
            );

            $this->Investor_model->master_fun_update(
                "stock_exchange_category",
                array("id",$cat_id),
                $updateCat
            );

            if (!empty($_FILES['pdf']['name'][0])) {

                foreach ($_FILES['pdf']['name'] as $key => $name) {

                    if($name == '') continue;

                    $_FILES['file']['name']     = $_FILES['pdf']['name'][$key];
                    $_FILES['file']['type']     = $_FILES['pdf']['type'][$key];
                    $_FILES['file']['tmp_name'] = $_FILES['pdf']['tmp_name'][$key];
                    $_FILES['file']['error']    = $_FILES['pdf']['error'][$key];
                    $_FILES['file']['size']     = $_FILES['pdf']['size'][$key];

                    $config['upload_path'] = './upload/stock_exchange/';
                    $config['allowed_types'] = 'pdf';
                    $config['file_name'] = time().'_'.$name;

                    $this->load->library('upload');
                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('file')) {

                        $fileData = $this->upload->data();

                        $fileInsert = array(
                            "category_id" => $cat_id,
                            "pdf_title"   => $this->input->post('pdf_title')[$key],
                            "pdf_path"    => $fileData['file_name']
                        );

                        $this->Investor_model->master_fun_insert(
                            "stock_exchange_files",
                            $fileInsert
                        );
                    }
                }
            }

            $this->session->set_flashdata("success", array("Stock Exchange Disclosure updated successfully."));
            redirect("Investor_master/stock_exchange_list", "refresh");

        } else {

            $data['category'] = $this->Investor_model->master_fun_get_tbl_val(
                "stock_exchange_category",
                array("id"=>$cat_id),
                array("id","desc")
            );

            $data['files'] = $this->Investor_model->master_fun_get_tbl_val(
                "stock_exchange_files",
                array("category_id"=>$cat_id,"status"=>1),
                array("id","desc")
            );

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('stock_exchange_edit', $data);
            $this->load->view('footer');
        }
    }

    function stock_exchange_delete() {

        if (!is_loggedin()) redirect('login');

        $cat_id = $this->uri->segment(3);

        $files = $this->Investor_model->master_fun_get_tbl_val(
            "stock_exchange_files",
            array("category_id"=>$cat_id),
            array("id","desc")
        );

        foreach($files as $f){
            if($f['pdf_path'] && file_exists('./upload/stock_exchange/'.$f['pdf_path'])){
                unlink('./upload/stock_exchange/'.$f['pdf_path']);
            }
        }

        $this->Investor_model->master_fun_update(
            "stock_exchange_category",
            array("id",$cat_id),
            array("status"=>0)
        );

        $this->Investor_model->master_fun_update(
            "stock_exchange_files",
            array("category_id",$cat_id),
            array("status"=>0)
        );

        $this->session->set_flashdata("success", array("Stock Exchange Disclosure deleted successfully."));
        redirect("Investor_master/stock_exchange_list","refresh");
    }

    function unclaimed_list() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $srchdata = array();
        $totalRows = $this->Investor_model->unclaimed_list_count();

        $config["base_url"] = base_url() . "Investor_master/unclaimed_list/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data["query"] = $this->Investor_model->unclaimed_list($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();

        $data['success'] = $this->session->flashdata("success");

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('unclaimed_list', $data);
        $this->load->view('footer');
    }

    function unclaimed_add() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Title', 'trim|required');

        if ($this->form_validation->run() != FALSE) {

            $catData = array(
                "title" => $this->input->post('title')
            );

            $category_id = $this->Investor_model->master_fun_insert(
                "unclaimed_category",
                $catData
            );

            if (!empty($_FILES['pdf']['name'][0])) {

                foreach ($_FILES['pdf']['name'] as $key => $name) {

                    $_FILES['file']['name']     = $_FILES['pdf']['name'][$key];
                    $_FILES['file']['type']     = $_FILES['pdf']['type'][$key];
                    $_FILES['file']['tmp_name'] = $_FILES['pdf']['tmp_name'][$key];
                    $_FILES['file']['error']    = $_FILES['pdf']['error'][$key];
                    $_FILES['file']['size']     = $_FILES['pdf']['size'][$key];

                    $config['upload_path'] = './upload/unclaimed/';
                    $config['allowed_types'] = 'pdf';
                    $config['file_name'] = time().'_'.$name;

                    $this->load->library('upload');
                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('file')) {

                        $fileData = $this->upload->data();

                        $fileInsert = array(
                            "category_id" => $category_id,
                            "pdf_title"   => $this->input->post('pdf_title')[$key],
                            "pdf_path"    => $fileData['file_name']
                        );

                        $this->Investor_model->master_fun_insert(
                            "unclaimed_files",
                            $fileInsert
                        );
                    }
                }
            }

            $this->session->set_flashdata("success", array("Unclaimed-Unpaid Amount added successfully."));
            redirect("Investor_master/unclaimed_list", "refresh");

        } else {

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('unclaimed_add', $data);
            $this->load->view('footer');
        }
    }

    function unclaimed_edit() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $cat_id = $this->uri->segment(3);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Title', 'trim|required');

        if ($this->form_validation->run() != FALSE) {

            $updateCat = array(
                "title" => $this->input->post('title')
            );

            $this->Investor_model->master_fun_update(
                "unclaimed_category",
                array("id",$cat_id),
                $updateCat
            );

            if (!empty($_FILES['pdf']['name'][0])) {

                foreach ($_FILES['pdf']['name'] as $key => $name) {

                    if($name == '') continue;

                    $_FILES['file']['name']     = $_FILES['pdf']['name'][$key];
                    $_FILES['file']['type']     = $_FILES['pdf']['type'][$key];
                    $_FILES['file']['tmp_name'] = $_FILES['pdf']['tmp_name'][$key];
                    $_FILES['file']['error']    = $_FILES['pdf']['error'][$key];
                    $_FILES['file']['size']     = $_FILES['pdf']['size'][$key];

                    $config['upload_path'] = './upload/unclaimed/';
                    $config['allowed_types'] = 'pdf';
                    $config['file_name'] = time().'_'.$name;

                    $this->load->library('upload');
                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('file')) {

                        $fileData = $this->upload->data();

                        $fileInsert = array(
                            "category_id" => $cat_id,
                            "pdf_title"   => $this->input->post('pdf_title')[$key],
                            "pdf_path"    => $fileData['file_name']
                        );

                        $this->Investor_model->master_fun_insert(
                            "unclaimed_files",
                            $fileInsert
                        );
                    }
                }
            }

            $this->session->set_flashdata("success", array("Unclaimed-Unpaid Amount updated successfully."));
            redirect("Investor_master/unclaimed_list", "refresh");

        } else {

            $data['category'] = $this->Investor_model->master_fun_get_tbl_val(
                "unclaimed_category",
                array("id"=>$cat_id),
                array("id","desc")
            );

            $data['files'] = $this->Investor_model->master_fun_get_tbl_val(
                "unclaimed_files",
                array("category_id"=>$cat_id,"status"=>1),
                array("id","desc")
            );

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('unclaimed_edit', $data);
            $this->load->view('footer');
        }
    }

    function unclaimed_delete() {

        if (!is_loggedin()) redirect('login');

        $cat_id = $this->uri->segment(3);

        $files = $this->Investor_model->master_fun_get_tbl_val(
            "unclaimed_files",
            array("category_id"=>$cat_id),
            array("id","desc")
        );

        foreach($files as $f){
            if($f['pdf_path'] && file_exists('./upload/unclaimed/'.$f['pdf_path'])){
                unlink('./upload/unclaimed/'.$f['pdf_path']);
            }
        }

        $this->Investor_model->master_fun_update(
            "unclaimed_category",
            array("id",$cat_id),
            array("status"=>0)
        );

        $this->Investor_model->master_fun_update(
            "unclaimed_files",
            array("category_id",$cat_id),
            array("status"=>0)
        );

        $this->session->set_flashdata("success", array("Unclaimed-Unpaid Amount deleted successfully."));
        redirect("Investor_master/unclaimed_list","refresh");
    }

    function others_list() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $srchdata = array();
        $totalRows = $this->Investor_model->others_list_count();

        $config["base_url"] = base_url() . "Investor_master/other_list/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data["query"] = $this->Investor_model->others_list($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();

        $data['success'] = $this->session->flashdata("success");

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('others_list', $data);
        $this->load->view('footer');
    }

    function others_add() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        if($this->input->post()){

            $title = $this->input->post('pdf_title');

            if (!empty($_FILES['pdf']['name'])) {

                $config['upload_path'] = './upload/others/';
                $config['allowed_types'] = 'pdf';
                $config['file_name'] = time().'_others';

                $this->load->library('upload');
                $this->upload->initialize($config);

                if ($this->upload->do_upload('pdf')) {

                    $fileData = $this->upload->data();

                    $insert = array(
                        "pdf_title" => $title,
                        "pdf_path"  => $fileData['file_name']
                    );

                    $this->Investor_model->master_fun_insert("others_disclosures", $insert);

                    $this->session->set_flashdata("success", array("Others file added."));
                    redirect("Investor_master/others_list");
                }
                else {
                    echo $this->upload->display_errors(); exit;
                }
            }
        }

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('others_add', $data);
        $this->load->view('footer');
    }

    function others_edit() {

        if (!is_loggedin()) redirect('login');

        $id = $this->uri->segment(3);

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['row'] = $this->Investor_model->master_fun_get_tbl_val(
            "others_disclosures",
            array("id"=>$id),
            array("id","desc")
        )[0];

        if($this->input->post()){

            $title = $this->input->post('pdf_title');
            $pdf = $data['row']['pdf_path'];

            if (!empty($_FILES['pdf']['name'])) {

                $config['upload_path'] = './upload/others/';
                $config['allowed_types'] = 'pdf';
                $config['file_name'] = time().'_others';

                $this->load->library('upload');
                $this->upload->initialize($config);

                if ($this->upload->do_upload('pdf')) {
                    $fileData = $this->upload->data();
                    $pdf = $fileData['file_name'];
                }
            }

            $update = array(
                "pdf_title" => $title,
                "pdf_path"  => $pdf
            );

            $this->Investor_model->master_fun_update(
                "others_disclosures",
                array("id",$id),
                $update
            );

            $this->session->set_flashdata("success", array("Updated successfully."));
            redirect("Investor_master/others_list");
        }

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('others_edit', $data);
        $this->load->view('footer');
    }

    function others_delete() {

        if (!is_loggedin()) redirect('login');

        $id = $this->uri->segment(3);

        $row = $this->Investor_model->master_fun_get_tbl_val(
            "others_disclosures",
            array("id"=>$id),
            array("id","desc")
        )[0];

        if(file_exists('./upload/others/'.$row['pdf_path'])){
            unlink('./upload/others/'.$row['pdf_path']);
        }

        $this->Investor_model->master_fun_update(
            "others_disclosures",
            array("id",$id),
            array("status"=>0)
        );

        $this->session->set_flashdata("success", array("Deleted successfully."));
        redirect("Investor_master/others_list");
    }

    function stock_list() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $totalRows = $this->Investor_model->stock_list_count();

        $config["base_url"] = base_url() . "Investor_master/stock_list/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data["query"] = $this->Investor_model->stock_list($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();

        $data['success'] = $this->session->flashdata("success");

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('stock_list', $data);
        $this->load->view('footer');
    }

    function stock_add() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        if($this->input->post()){

            $insert = array(
                "name"    => $this->input->post('name'),
                "address" => $this->input->post('address'),
                "symbol"  => $this->input->post('symbol')
            );

            $this->Investor_model->master_fun_insert("stock_exchanges", $insert);

            $this->session->set_flashdata("success", array("Stock Exchange added."));
            redirect("Investor_master/stock_list");
        }

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('stock_add', $data);
        $this->load->view('footer');
    }

    function stock_edit() {

        if (!is_loggedin()) redirect('login');

        $id = $this->uri->segment(3);

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['row'] = $this->Investor_model->master_fun_get_tbl_val(
            "stock_exchanges",
            array("id"=>$id),
            array("id","desc")
        )[0];

        if($this->input->post()){

            $update = array(
                "name"    => $this->input->post('name'),
                "address" => $this->input->post('address'),
                "symbol"  => $this->input->post('symbol')
            );

            $this->Investor_model->master_fun_update(
                "stock_exchanges",
                array("id",$id),
                $update
            );

            $this->session->set_flashdata("success", array("Updated successfully."));
            redirect("Investor_master/stock_list");
        }

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('stock_edit', $data);
        $this->load->view('footer');
    }

    function stock_delete() {

        if (!is_loggedin()) redirect('login');

        $id = $this->uri->segment(3);

        $this->Investor_model->master_fun_update(
            "stock_exchanges",
            array("id",$id),
            array("status"=>0)
        );

        $this->session->set_flashdata("success", array("Deleted successfully."));
        redirect("Investor_master/stock_list");
    }

    function dividend_comm_list() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $totalRows = $this->Investor_model->dividend_comm_count();

        $config["base_url"] = base_url() . "Investor_master/dividend_comm_list/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data["query"] = $this->Investor_model->dividend_comm_list($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $data['success'] = $this->session->flashdata("success");

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('dividend_comm_list', $data);
        $this->load->view('footer');
    }

    function dividend_comm_add() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        if($this->input->post()){

            $title = $this->input->post('pdf_title');

            if (!empty($_FILES['pdf']['name'])) {

                $config['upload_path'] = './upload/dividend/';
                $config['allowed_types'] = 'pdf';
                $config['file_name'] = time().'_dividend';

                $this->load->library('upload');
                $this->upload->initialize($config);

                if ($this->upload->do_upload('pdf')) {

                    $fileData = $this->upload->data();

                    $insert = array(
                        "pdf_title" => $title,
                        "pdf_path"  => $fileData['file_name']
                    );

                    $this->Investor_model->master_fun_insert("dividend_communications", $insert);

                    $this->session->set_flashdata("success", array("Dividend PDF added."));
                    redirect("Investor_master/dividend_comm_list");
                }
            }
        }

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('dividend_comm_add', $data);
        $this->load->view('footer');
    }

    function dividend_comm_delete() {

        if (!is_loggedin()) redirect('login');

        $id = $this->uri->segment(3);

        $this->Investor_model->master_fun_update(
            "dividend_communications",
            array("id",$id),
            array("status"=>0)
        );

        $this->session->set_flashdata("success", array("Deleted successfully."));
        redirect("Investor_master/dividend_comm_list");
    }

    function dividend_history_list() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $totalRows = $this->Investor_model->dividend_history_count();

        $config["base_url"] = base_url() . "Investor_master/dividend_history_list/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data["query"] = $this->Investor_model->dividend_history_list($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $data['success'] = $this->session->flashdata("success");

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('dividend_history_list', $data);
        $this->load->view('footer');
    }

    function dividend_history_add() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        if($this->input->post()){

            $insert = array(
                "year"          => $this->input->post('year'),
                "type" => $this->input->post('dividend_type'),
                "amount"        => $this->input->post('amount')
            );

            $this->Investor_model->master_fun_insert("dividend_history", $insert);

            $this->session->set_flashdata("success", array("Dividend History added."));
            redirect("Investor_master/dividend_history_list");
        }

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('dividend_history_add', $data);
        $this->load->view('footer');
    }

    function dividend_history_edit() {

        if (!is_loggedin()) redirect('login');

        $id = $this->uri->segment(3);

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        // Get existing record
        $data['row'] = $this->Investor_model->master_fun_get_tbl_val(
            "dividend_history",
            array("id"=>$id),
            array("id","desc")
        )[0];

        // On form submit
        if($this->input->post()){

            $update = array(
                "year"          => $this->input->post('year'),
                "type" => $this->input->post('dividend_type'),
                "amount"        => $this->input->post('amount')
            );

            $this->Investor_model->master_fun_update(
                "dividend_history",
                array("id",$id),
                $update
            );

            $this->session->set_flashdata("success", array("Dividend History updated."));
            redirect("Investor_master/dividend_history_list");
        }

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('dividend_history_edit', $data);
        $this->load->view('footer');
    }

    function dividend_history_delete() {

        if (!is_loggedin()) redirect('login');

        $id = $this->uri->segment(3);

        $this->Investor_model->master_fun_update(
            "dividend_history",
            array("id",$id),
            array("status"=>0)
        );

        $this->session->set_flashdata("success", array("Deleted successfully."));
        redirect("Investor_master/dividend_history_list");
    }

    public function agm_list() {
        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data["query"] = $this->db->order_by('id','DESC')->get('agm')->result_array();
        $data['success'] = $this->session->flashdata("success");

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('agm_list', $data);
        $this->load->view('footer');
    }

    public function agm_add() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->library('form_validation');

        $this->form_validation->set_rules('particulars', 'Particulars / FY', 'trim|required');
        $this->form_validation->set_rules('notice_title', 'Notice Title', 'trim|required');
        $this->form_validation->set_rules('result_title', 'Result Title', 'trim|required');
        $this->form_validation->set_rules('transcript_title', 'Transcript Title', 'trim|required');

        if (empty($_FILES['notice_pdf']['name'])) {
            $this->form_validation->set_rules('notice_pdf', 'Notice PDF', 'required');
        }

        if (empty($_FILES['result_pdf']['name'])) {
            $this->form_validation->set_rules('result_pdf', 'Result PDF', 'required');
        }

        if (empty($_FILES['transcript_pdf']['name'])) {
            $this->form_validation->set_rules('transcript_pdf', 'Transcript PDF', 'required');
        }

        if ($this->form_validation->run() != FALSE) {

            $config['upload_path'] = './upload/agm/';
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = 5120;

            $this->load->library('upload', $config);

            // Upload Notice
            if (!$this->upload->do_upload('notice_pdf')) {
                $data['error'] = $this->upload->display_errors();
                goto load_form;
            }
            $notice_pdf = $this->upload->data('file_name');

            // Upload Result
            if (!$this->upload->do_upload('result_pdf')) {
                $data['error'] = $this->upload->display_errors();
                goto load_form;
            }
            $result_pdf = $this->upload->data('file_name');

            // Upload Transcript
            if (!$this->upload->do_upload('transcript_pdf')) {
                $data['error'] = $this->upload->display_errors();
                goto load_form;
            }
            $transcript_pdf = $this->upload->data('file_name');

            $insert = [
                'particulars' => $this->input->post('particulars'),
                'notice_title' => $this->input->post('notice_title'),
                'notice_pdf' => $notice_pdf,
                'result_title' => $this->input->post('result_title'),
                'result_pdf' => $result_pdf,
                'transcript_title' => $this->input->post('transcript_title'),
                'transcript_pdf' => $transcript_pdf,
                'created_date' => date('Y-m-d H:i:s')
            ];

            $this->db->insert('agm', $insert);

            $this->session->set_flashdata('success','AGM Added Successfully');
            redirect('Investor_master/agm_list');
        }

        load_form:
        $this->load->view('header',$data);
        $this->load->view('nav',$data);
        $this->load->view('agm_add',$data);
        $this->load->view('footer');
    }

    public function agm_edit($id) {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['row'] = $this->db->get_where('agm',['id'=>$id])->row_array();

        $this->load->library('form_validation');

        $this->form_validation->set_rules('particulars', 'Particulars / FY', 'trim|required');
        $this->form_validation->set_rules('notice_title', 'Notice Title', 'trim|required');
        $this->form_validation->set_rules('result_title', 'Result Title', 'trim|required');
        $this->form_validation->set_rules('transcript_title', 'Transcript Title', 'trim|required');

        if ($this->form_validation->run() != FALSE) {

            $config['upload_path'] = './upload/agm/';
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = 5120;

            $this->load->library('upload', $config);

            $update = [
                'particulars' => $this->input->post('particulars'),
                'notice_title' => $this->input->post('notice_title'),
                'result_title' => $this->input->post('result_title'),
                'transcript_title' => $this->input->post('transcript_title')
            ];

            if($_FILES['notice_pdf']['name']){
                if ($this->upload->do_upload('notice_pdf')) {
                    $update['notice_pdf'] = $this->upload->data('file_name');
                }
            }

            if($_FILES['result_pdf']['name']){
                if ($this->upload->do_upload('result_pdf')) {
                    $update['result_pdf'] = $this->upload->data('file_name');
                }
            }

            if($_FILES['transcript_pdf']['name']){
                if ($this->upload->do_upload('transcript_pdf')) {
                    $update['transcript_pdf'] = $this->upload->data('file_name');
                }
            }

            $this->db->where('id',$id)->update('agm',$update);

            $this->session->set_flashdata('success','AGM Updated Successfully');
            redirect('Investor_master/agm_list');
        }

        $this->load->view('header',$data);
        $this->load->view('nav',$data);
        $this->load->view('agm_edit',$data);
        $this->load->view('footer');
    }

    public function agm_delete($id) {
        if (!is_loggedin()) redirect('login');

        $this->db->where('id',$id)->delete('agm');
        $this->session->set_flashdata('success','AGM Deleted Successfully');
        redirect('Investor_master/agm_list');
    }

    public function shareholder_info_comman_list() {
        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data["query"] = $this->db->where('status', 1)->order_by('id','DESC')->get('shareholder_info_comman')->result_array();
        $data['success'] = $this->session->flashdata("success");

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('shareholder_info_comman_list', $data);
        $this->load->view('footer');
    }

    public function shareholder_info_comman_add() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->library('form_validation');

        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('pdf_title', 'PDF Title', 'trim|required');

        if (empty($_FILES['pdf']['name'])) {
            $this->form_validation->set_rules('pdf', 'PDF', 'required');
        }

        if ($this->form_validation->run() != FALSE) {

            $config['upload_path'] = './upload/shareholder_info/';
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = 5120;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('pdf')) {
                $data['error'] = $this->upload->display_errors();
                goto load_form;
            }
            $pdf_name = $this->upload->data('file_name');

            $insert = [
                'type' => $this->input->post('type'),
                'pdf_title' => $this->input->post('pdf_title'),
                'pdf_path' => $pdf_name,
                'created_date' => date('Y-m-d H:i:s')
            ];

            $this->db->insert('shareholder_info_comman', $insert);

            $this->session->set_flashdata('success','Added Successfully');
            redirect('Investor_master/shareholder_info_comman_list');
        }

        load_form:
        $this->load->view('header',$data);
        $this->load->view('nav',$data);
        $this->load->view('shareholder_info_comman_add',$data);
        $this->load->view('footer');
    }

    public function shareholder_info_comman_edit($id) {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['row'] = $this->db->get_where('shareholder_info_comman',['id'=>$id])->row_array();

        $this->load->library('form_validation');

        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('pdf_title', 'PDF Title', 'trim|required');

        if ($this->form_validation->run() != FALSE) {

            $config['upload_path'] = './upload/shareholder_info/';
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = 5120;

            $this->load->library('upload', $config);

            $update = [
                'type' => $this->input->post('type'),
                'pdf_title' => $this->input->post('pdf_title'),
            ];

            if($_FILES['pdf']['name']){
                if ($this->upload->do_upload('pdf')) {
                    $update['pdf_path'] = $this->upload->data('file_name');
                }
            }

            $this->db->where('id',$id)->update('shareholder_info_comman',$update);

            $this->session->set_flashdata('success','Updated Successfully');
            redirect('Investor_master/shareholder_info_comman_list');
        }

        $this->load->view('header',$data);
        $this->load->view('nav',$data);
        $this->load->view('shareholder_info_comman_edit',$data);
        $this->load->view('footer');
    }

    public function shareholder_info_comman_delete($id) {
        if (!is_loggedin()) redirect('login');
        $update = [
            'status' => 0,
        ];
        $this->db->where('id',$id)->update('shareholder_info_comman',$update);
        $this->session->set_flashdata('success','Deleted Successfully');
        redirect('Investor_master/shareholder_info_comman_list');
    }

    public function credit_rating_list() {
        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data["query"] = $this->db->where('status', 1)->order_by('id','DESC')->get('credit_rating')->result_array();
        $data['success'] = $this->session->flashdata("success");

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('credit_rating_list', $data);
        $this->load->view('footer');
    }

    public function credit_rating_add() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->library('form_validation');

        $this->form_validation->set_rules('facilities_type_of_rating', 'Facilities/Type of Rating', 'trim|required');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        $this->form_validation->set_rules('rating', 'Rating', 'trim|required');
        $this->form_validation->set_rules('rating_status', 'Rating Status', 'trim|required');

        if ($this->form_validation->run() != FALSE) {
            $insert = [
                'facilities_type_of_rating' => $this->input->post('facilities_type_of_rating'),
                'amount' => $this->input->post('amount'),
                'rating' => $this->input->post('rating'),
                'rating_status' => $this->input->post('rating_status'),
                'created_date' => date('Y-m-d H:i:s')
            ];

            $this->db->insert('credit_rating', $insert);

            $this->session->set_flashdata('success','Added Successfully');
            redirect('Investor_master/credit_rating_list');
        }

        load_form:
        $this->load->view('header',$data);
        $this->load->view('nav',$data);
        $this->load->view('credit_rating_add',$data);
        $this->load->view('footer');
    }

    public function credit_rating_edit($id) {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['row'] = $this->db->get_where('credit_rating',['id'=>$id])->row_array();

        $this->load->library('form_validation');

        $this->form_validation->set_rules('facilities_type_of_rating', 'Facilities/Type of Rating', 'trim|required');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        $this->form_validation->set_rules('rating', 'Rating', 'trim|required');
        $this->form_validation->set_rules('rating_status', 'Rating Status', 'trim|required');

        if ($this->form_validation->run() != FALSE) {

            $update = [
                'facilities_type_of_rating' => $this->input->post('facilities_type_of_rating'),
                'amount' => $this->input->post('amount'),
                'rating' => $this->input->post('rating'),
                'rating_status' => $this->input->post('rating_status'),
            ];

            $this->db->where('id',$id)->update('credit_rating',$update);

            $this->session->set_flashdata('success','Updated Successfully');
            redirect('Investor_master/credit_rating_list');
        }

        $this->load->view('header',$data);
        $this->load->view('nav',$data);
        $this->load->view('credit_rating_edit',$data);
        $this->load->view('footer');
    }

    public function credit_rating_delete($id) {
        if (!is_loggedin()) redirect('login');
        $update = [
            'status' => 0,
        ];
        $this->db->where('id',$id)->update('credit_rating',$update);
        $this->session->set_flashdata('success','Deleted Successfully');
        redirect('Investor_master/credit_rating_list');
    }

    public function egm_list() {
        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data["query"] = $this->db->where('status',1)->order_by('id','DESC')->get('egm')->result_array();
        $data['success'] = $this->session->flashdata("success");

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('egm_list', $data);
        $this->load->view('footer');
    }

    public function egm_add() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->library('form_validation');

        $this->form_validation->set_rules('particulars', 'Particulars / FY', 'trim|required');
        $this->form_validation->set_rules('notice_title', 'Notice Title', 'trim|required');
        $this->form_validation->set_rules('result_title', 'Result Title', 'trim|required');

        if (empty($_FILES['notice_pdf']['name'])) {
            $this->form_validation->set_rules('notice_pdf', 'Notice PDF', 'required');
        }

        if (empty($_FILES['result_pdf']['name'])) {
            $this->form_validation->set_rules('result_pdf', 'Result PDF', 'required');
        }

        if ($this->form_validation->run() != FALSE) {

            $config['upload_path'] = './upload/egm/';
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = 5120;

            $this->load->library('upload', $config);

            // Upload Notice
            if (!$this->upload->do_upload('notice_pdf')) {
                $data['error'] = $this->upload->display_errors();
                goto load_form;
            }
            $notice_pdf = $this->upload->data('file_name');

            // Upload Result
            if (!$this->upload->do_upload('result_pdf')) {
                $data['error'] = $this->upload->display_errors();
                goto load_form;
            }
            $result_pdf = $this->upload->data('file_name');


            $insert = [
                'particulars' => $this->input->post('particulars'),
                'notice_title' => $this->input->post('notice_title'),
                'notice_pdf' => $notice_pdf,
                'result_title' => $this->input->post('result_title'),
                'result_pdf' => $result_pdf,
                'status' => 1,
                'created_date' => date('Y-m-d H:i:s')
            ];

            $this->db->insert('egm', $insert);

            $this->session->set_flashdata('success','EGM Added Successfully');
            redirect('Investor_master/egm_list');
        }

        load_form:
        $this->load->view('header',$data);
        $this->load->view('nav',$data);
        $this->load->view('egm_add',$data);
        $this->load->view('footer');
    }

    public function egm_edit($id) {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['row'] = $this->db->get_where('egm',['id'=>$id])->row_array();

        $this->load->library('form_validation');

        $this->form_validation->set_rules('particulars', 'Particulars / FY', 'trim|required');
        $this->form_validation->set_rules('notice_title', 'Notice Title', 'trim|required');
        $this->form_validation->set_rules('result_title', 'Result Title', 'trim|required');

        if ($this->form_validation->run() != FALSE) {

            $config['upload_path'] = './upload/egm/';
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = 5120;

            $this->load->library('upload', $config);

            $update = [
                'particulars' => $this->input->post('particulars'),
                'notice_title' => $this->input->post('notice_title'),
                'result_title' => $this->input->post('result_title'),
            ];

            if($_FILES['notice_pdf']['name']){
                if ($this->upload->do_upload('notice_pdf')) {
                    $update['notice_pdf'] = $this->upload->data('file_name');
                }
            }

            if($_FILES['result_pdf']['name']){
                if ($this->upload->do_upload('result_pdf')) {
                    $update['result_pdf'] = $this->upload->data('file_name');
                }
            }

            $this->db->where('id',$id)->update('egm',$update);

            $this->session->set_flashdata('success','EGM Updated Successfully');
            redirect('Investor_master/egm_list');
        }

        $this->load->view('header',$data);
        $this->load->view('nav',$data);
        $this->load->view('egm_edit',$data);
        $this->load->view('footer');
    }

    public function egm_delete($id) {
        if (!is_loggedin()) redirect('login');
        $update = [
            'status' => 0,
        ];
        $this->db->where('id',$id)->update('egm',$update);
        $this->session->set_flashdata('success','EGM Deleted Successfully');
        redirect('Investor_master/egm_list');
    }

    public function newspaper_advertisment_list() {
        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data["query"] = $this->db->where('status',1)->order_by('id','DESC')->get('newspaper_advertisment')->result_array();
        $data['success'] = $this->session->flashdata("success");

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('newspaper_advertisment_list', $data);
        $this->load->view('footer');
    }

    public function newspaper_advertisment_add() {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->library('form_validation');

        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('pdf_title', 'PDF Title', 'trim|required');

        if (empty($_FILES['pdf']['name'])) {
            $this->form_validation->set_rules('pdf', 'PDF', 'required');
        }


        if ($this->form_validation->run() != FALSE) {

            $config['upload_path'] = './upload/newspaper_advertisment/';
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = 5120;

            $this->load->library('upload', $config);

            // Upload Notice
            if (!$this->upload->do_upload('pdf')) {
                $data['error'] = $this->upload->display_errors();
                goto load_form;
            }
            $pdf = $this->upload->data('file_name');

            $insert = [
                'type' => $this->input->post('type'),
                'pdf_title' => $this->input->post('pdf_title'),
                'pdf_path' => $pdf,
                'status' => 1,
                'created_date' => date('Y-m-d H:i:s')
            ];

            $this->db->insert('newspaper_advertisment', $insert);

            $this->session->set_flashdata('success','Newspaper Advertisment Added Successfully');
            redirect('Investor_master/newspaper_advertisment_list');
        }

        load_form:
        $this->load->view('header',$data);
        $this->load->view('nav',$data);
        $this->load->view('newspaper_advertisment_add',$data);
        $this->load->view('footer');
    }

    public function newspaper_advertisment_edit($id) {

        if (!is_loggedin()) redirect('login');

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['row'] = $this->db->get_where('newspaper_advertisment',['id'=>$id])->row_array();

        $this->load->library('form_validation');

        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('pdf_title', 'PDF Title', 'trim|required');

        if ($this->form_validation->run() != FALSE) {

            $config['upload_path'] = './upload/newspaper_advertisment/';
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = 5120;

            $this->load->library('upload', $config);

            $update = [
                'type' => $this->input->post('type'),
                'pdf_title' => $this->input->post('pdf_title'),
            ];

            if($_FILES['pdf']['name']){
                if ($this->upload->do_upload('pdf')) {
                    $update['pdf_path'] = $this->upload->data('file_name');
                }
            }

            $this->db->where('id',$id)->update('newspaper_advertisment',$update);

            $this->session->set_flashdata('success','Newspaper Advertisment Updated Successfully');
            redirect('Investor_master/newspaper_advertisment_list');
        }

        $this->load->view('header',$data);
        $this->load->view('nav',$data);
        $this->load->view('newspaper_advertisment_edit',$data);
        $this->load->view('footer');
    }

    public function newspaper_advertisment_delete($id) {
        if (!is_loggedin()) redirect('login');
        $update = [
            'status' => 0,
        ];
        $this->db->where('id',$id)->update('newspaper_advertisment',$update);
        $this->session->set_flashdata('success','Newspaper Advertisment Deleted Successfully');
        redirect('Investor_master/newspaper_advertisment_list');
    }


}

?>