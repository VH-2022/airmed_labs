<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Branch_collections extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('Branch_collections_model');
        $this->load->model('registration_admin_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->helper('string');
        //ini_set('display_errors', 1);
        $data["login_data"] = logindata();
    }

    function index() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data['branch_name'] = $branchid = $this->input->get('branch');
        $data['city_name'] = $cityid = $this->input->get('city');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $data['unsuccess'] = $this->session->flashdata("unsuccess");

        if ($branchid != "") {
            $totalRows = $this->Branch_collections_model->num_row($branchid, $cityid, $clientid);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "Branch_collections/index?" . http_build_query($get);
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Branch_collections_model->master_get_search($branchid, $cityid, $clientid, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        } else {
            $totalRows = $this->Branch_collections_model->num_row($branchid, $cityid, $clientid);
            $config = array();
            $get = $_GET;
            $config["base_url"] = base_url() . "Branch_collections/index/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next <span class="icon-text">&#59230;</span>';
            $config['prev_link'] = '<span class="icon-text">&#59229;</span> Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data['query'] = $this->Branch_collections_model->master_get_search($branchid, $cityid, $clientid, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }

        $data['branch'] = $this->Branch_collections_model->master_get_branch();
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('branch_collection_list', $data);
        $this->load->view('footer');
    }

    function user_branch($userid) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $branchid = $this->input->get('branch_name');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["id"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['userid'] = $userid;
        $data['branch_list'] = $this->registration_admin_model->get_val("SELECT * from branch_master where  status='1'");
        $data['list'] = $this->registration_admin_model->get_val("SELECT user_branch.id,`branch_master`.`branch_name`,user_branch.test_parameter FROM   `user_branch`    LEFT JOIN branch_master      ON `branch_master`.`id` = `user_branch`.`branch_fk`  WHERE user_branch.`status`=1 AND user_branch.`user_fk`=$userid");
        $config = array();
        $get = $_GET;
        $config["base_url"] = base_url() . "Branch_collections/Branch_list/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config['cur_tag_open'] = '<span>';
        $config['cur_tag_close'] = '</span>';
        $config['next_link'] = 'Next <span class="icon-text">&#59230;</span>';
        $config['prev_link'] = '<span class="icon-text">&#59229;</span> Previous';
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['query'] = $this->Branch_collections_model->master_get_search($branchid, $config["per_page"], $page);
        $data['city'] = $this->Branch_collections_model->list_city();

        $cnt = 0;
        $data['branch'] = $this->Branch_collections_model->master_get_branch();
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('user_branch', $data);
        $this->load->view('footer');
    }

    function add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['city'] = $this->Branch_collections_model->list_city();
        $data['success'] = $this->session->flashdata("success");

        $data['branch_list'] = $this->Branch_collections_model->get_list();
        $data['test_city'] = $this->Branch_collections_model->get_val("select id,name from test_cities where status='1'");

        $this->load->library('form_validation');
        $this->form_validation->set_rules('branch', 'Branch', 'trim|required');
        $this->form_validation->set_rules('city', 'City', 'trim|required');
        $this->form_validation->set_rules('total', 'Total', 'trim|required');
        $this->form_validation->set_rules('net_pay', 'Net Pay', 'trim|required');

        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() != FALSE) {
            $date = $this->input->post('inceptiondate');
            $date = explode("/", $date);
            $date = $date[2] . '-' . $date[1] . '-' . $date[0];
            $post['city_fk'] = $this->input->post('city');
            $post['branch_fk'] = $this->input->post('branch');
            $post['remark'] = $this->input->post('remark');
            $post['total'] = $this->input->post('total');
            $post['discount'] = $this->input->post('discount');
            $dis_type = $this->input->post('dis_type');
            $post['discount_type'] = ($dis_type == "percentage") ? '1' : '2';

            $post['net_pay'] = $this->input->post('net_pay');
            $post['address'] = $this->input->post('address');
            $post['inceptiondate'] = $date;
            $post['status'] = 1;
            $post['created_date'] = date('Y-m-d h:i:s');

            $bid = $this->Branch_collections_model->master_get_insert("branch_collections", $post);

            $this->session->set_flashdata("success", 'Branch collection successfully Added.');
            redirect("Branch_collections/index", "refresh");
        } else {

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('branch_collection_add', $data);
            $this->load->view('footer');
        }
    }

    function user_branch_delete($userid, $id) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');

        $data['query'] = $this->Branch_collections_model->master_tbl_update("user_branch", $id, array("status" => "0"));

        $this->session->set_flashdata("success", 'Branch successfully .');
        redirect("Branch_collections/user_branch/" . $userid, "refresh");
    }

    function delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');

        $data['query'] = $this->Branch_collections_model->master_tbl_update("branch_collections", $cid, array("status" => "0"));

        $this->session->set_flashdata("success", 'Branch Collections Details Successfully Removed.');
        redirect("Branch_collections/index", "refresh");
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
        //$this->form_validation->set_rules('branch', 'Branch', 'trim|required');
        $this->form_validation->set_rules('total', 'Total', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() != FALSE) {

            $date = $this->input->post('inceptiondate');
            $date = explode("/", $date);
            $date = $date[2] . '-' . $date[1] . '-' . $date[0];
            //$post['branch_fk'] = $this->input->post('branch');
            $post['remark'] = $this->input->post('remark');
            $post['address'] = $this->input->post('address');
            $post['inceptiondate'] = $date;
            $post['total'] = $this->input->post('total');
            $post['discount'] = $this->input->post('discount');
            $post['discount_type'] = ($this->input->post('dis_type') == "percentage") ? '1' : '2';
            $post['net_pay'] = $this->input->post('net_pay');
            $post['status'] = 1;
            $data['query'] = $this->Branch_collections_model->master_tbl_update("branch_collections", $id, $post);
            $this->session->set_flashdata("success", 'Branch Collection Successfully Updated');
            redirect("Branch_collections/index", "refresh");
        } else {
            $data['query'] = $this->Branch_collections_model->get_val("select bc.id,bc.total,bc.remark,
                bc.created_date,bc.status,bc.address,bc.inceptiondate,bc.discount,bc.city_fk,bc.branch_fk,bc.discount_type 
                from branch_collections bc 
                where 1=1 AND bc.id='$id'");

            $data['branch_list'] = $this->Branch_collections_model->master_get_branch();
            $data['test_city'] = $this->Branch_collections_model->get_val("select id,name from test_cities where status='1'");

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('branch_collection_edit', $data);
            $this->load->view('footer');
        }
    }

    function branch_view() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['user'] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $this->uri->segment('3');
        $id = $data['cid'];
        $data['view_data'] = $this->Branch_collections_model->master_get_view($id);
        foreach ($data['view_data'] as $key => $val) {
            $parent_fk = $val['parent_fk'];
            $data['query'] = $this->Branch_collections_model->get_sub_client($parent_fk);
        }
        $data['parent_node'] = $this->Branch_collections_model->get_parent_node($id);
        $data['getlaball'] = $this->Branch_collections_model->get_val("SELECT GROUP_CONCAT(name) as labname FROM collect_from WHERE status='1' AND id IN(SELECT `labid` FROM b2b_labgroup WHERE status='1' AND branch_fk='$id') ORDER BY name ASC");
        $data['getProcessBranch'] = $this->Branch_collections_model->get_val("SELECT DISTINCT branch_master.* FROM branch_master INNER JOIN `processing_center` ON `processing_center`.`branch_fk`=`branch_master`.id WHERE branch_master.`status`='1' ORDER BY branch_master.id DESC");
        $data['getPdfBranch'] = $this->Branch_collections_model->get_val("SELECT `branch_master`.* FROM branch_master INNER JOIN `pdf_design` ON `pdf_design`.`branch_fk`=`branch_master`.id WHERE `branch_master`.`status`='1'");
        $data["processing_center"] = $this->Branch_collections_model->get_val("SELECT id FROM `processing_center` WHERE `status`='1' AND `lab_fk`='" . $this->uri->segment('3') . "'");
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('branch_view', $data);
        $this->load->view('footer');
    }

    function details() {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['user'] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $this->uri->segment('3');
        $id = $data['cid'];

        $data['success'] = $this->session->flashdata("success");
        $data['unsuccess'] = $this->session->flashdata("unsuccess");

        if ($branchid != "") {
            $totalRows = $this->Branch_collections_model->num_row_details($branchid);
            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "Branch_collections/details/$id?" . http_build_query($get);
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Branch_collections_model->master_get_search($branchid, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
        } else {
            $totalRows = $this->Branch_collections_model->num_row_details($id);
            $config = array();
            $get = $_GET;
            $config["base_url"] = base_url() . "Branch_collections/details/$id";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next <span class="icon-text">&#59230;</span>';
            $config['prev_link'] = '<span class="icon-text">&#59229;</span> Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data['query'] = $this->Branch_collections_model->master_get_search_details($id, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }
        //$data['query']['total_collection'] = $this->Branch_collections_model->get_val("select SUM(installment) from branch_collections_details group by br_collection_fk WHERE status='1'");

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('branch_collection_list_details', $data);
        $this->load->view('footer');
    }

    function add_installment() {
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        //$data['city'] = $this->Branch_collections_model->list_city();
        $data['success'] = $this->session->flashdata("success");
        $data['cid'] = $this->uri->segment('3');
        $id = $data['cid'];
//        $data['branch'] = $this->Branch_collections_model->get_val("select bm.branch_name,bm.id  
//                from branch_collections bc
//                INNER JOIN branch_master bm on bm.id=bc.branch_fk WHERE bc.id='$id'");
        //$data['branch_list'] = $this->Branch_collections_model->get_list();
        $this->load->library('form_validation');
        //$this->form_validation->set_rules('branch', 'Branch', 'trim|required');
        $this->form_validation->set_rules('installment', 'Installment', 'trim|required');
        $this->form_validation->set_rules('payment_mode', 'Mode Of Payment', 'trim|required');
        $this->form_validation->set_rules('remark', 'Payment Details', 'trim|required');
        $this->form_validation->set_rules('receipt_date', 'Date Of Receipt', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() != FALSE) {
            //$post['branch_fk'] = $this->input->post('branch');
            $post['br_collection_fk'] = $this->input->post('collection_id');
            $date = $this->input->post('receipt_date');
            $date = explode('/', $date);
            $date = $date['2'] . '-' . $date['1'] . '-' . $date['0'];
            $post['receipt_date'] = $date;
            $post['remark'] = $this->input->post('remark');
            $post['installment'] = $this->input->post('installment');
            $post['payment_mode'] = $this->input->post('payment_mode');
            $post['status'] = 1;
            $post['created_date'] = date('Y-m-d h:i:s');
            $bid = $this->Branch_collections_model->master_get_insert("branch_collections_details", $post);

            $this->session->set_flashdata("success", 'Installment successfully Added.');
            redirect("Branch_collections/details/$id", "refresh");
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('branch_collection_add_installment', $data);
            $this->load->view('footer');
        }
    }

    function edit_installment() {
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['city'] = $this->Branch_collections_model->list_city();
        $data['success'] = $this->session->flashdata("success");

        $data['cid'] = $this->uri->segment('3');
        $id = $data['cid'];
//        $data['branch'] = $this->Branch_collections_model->get_val("select bm.branch_name,bm.id 
//                from branch_collections_details bcd
//                INNER JOIN branch_collections bc on bc.id=bcd.br_collection_fk AND bc.status='1' 
//                INNER JOIN branch_master bm on bm.id=bc.branch_fk WHERE bcd.id='$id'");

        $data['query'] = $this->Branch_collections_model->get_val("select bcd.id,bcd.installment,bcd.remark,bcd.br_collection_fk,
                    bcd.payment_mode,bcd.receipt_date
                    from branch_collections bc 
                    INNER JOIN branch_collections_details bcd on bc.id = bcd.br_collection_fk AND bc.status='1' 
                    WHERE 1=1 AND bcd.id=$id");
        $collection_fk = $data['query'][0]->br_collection_fk;

        $data['branch_list'] = $this->Branch_collections_model->get_list();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('installment', 'Installment', 'trim|required');
        $this->form_validation->set_rules('remark', 'Payment Details', 'trim|required');
        $this->form_validation->set_rules('receipt_date', 'Date Of Receipt', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() != FALSE) {
            //$post['br_collection_fk'] = $collection_fk = $this->input->post('collection_id');
            $post['remark'] = $this->input->post('remark');
            $post['installment'] = $this->input->post('installment');
            $date = $this->input->post('receipt_date');
            $date = explode('/', $date);
            $date = $date['2'] . '-' . $date['1'] . '-' . $date['0'];
            $post['receipt_date'] = $date;
            //$post['status'] = 1;
            //$post['created_date'] = date('Y-m-d h:i:s');

            $data['query'] = $this->Branch_collections_model->master_tbl_update("branch_collections_details", $id, $post);

            $this->session->set_flashdata("success", 'Installment successfully Updated.');
            redirect("Branch_collections/details/$collection_fk", "refresh");
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('branch_collection_edit_installment', $data);
            $this->load->view('footer');
        }
    }

    function delete_installment() {

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');

        $que = $this->Branch_collections_model->get_val("select bcd.br_collection_fk from branch_collections_details bcd WHERE 1=1 AND bcd.id=$cid");
        $collection_fk = $que[0]->br_collection_fk;
        $data['query'] = $this->Branch_collections_model->master_tbl_update("branch_collections_details", $cid, array("status" => "0"));

        $this->session->set_flashdata("success", 'Installment Successfully Removed.');
        redirect("Branch_collections/details/$collection_fk", "refresh");
    }

    function export_csv() {
        $data['branch_name'] = $branchid = $this->input->get('branch');
        $data['city_name'] = $cityid = $this->input->get('city');
        $data['client_name'] = $clientid = $this->input->get('client');
        $temp = "";
        if ($branchid != "") {
            $temp .= " AND bm.branch_name LIKE '%$branchid%'";
        }
        if ($cityid != "") {
            $temp .= " AND tc.name LIKE '%$cityid%'";
        }
        if ($clientid != "") {
            $temp .= " AND bc.client_name LIKE '%$clientid%'";
        }
        $temp .= " AND bc.status='1' GROUP BY bc.id ORDER BY bc.id DESC";

        $que = $this->Branch_collections_model->get_val("select bc.id,bc.total,bc.total,bc.remark,bc.status,bm.branch_name,
            SUM(bcd.installment) AS total_collection,
                    tc.name as city_name, bc.inceptiondate,bc.discount,bc.discount_type,bc.net_pay 
                from branch_collections bc 
                LEFT JOIN branch_collections_details bcd on bc.id = bcd.br_collection_fk AND bcd.status='1' 
                LEFT JOIN branch_master bm on bm.id = bc.branch_fk AND bm.status='1' 
                LEFT JOIN test_cities tc on tc.id = bc.city_fk AND tc.status='1' 
                where 1=1 $temp");

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Branch Collections.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("Sr No.", "Date Of Inception", "City", "Branch", "Integration Fees", "Discount", "Net Pay", "Paid", "Due", "Remark"));
        
        $cnt = 0;
        foreach ($que as $q) {
            $cnt++;
            if ($q->discount_type == '1') {
                $discount = $q->total * $q->discount / 100;
            } else if ($q->discount_type == '2') {
                $discount = $q->discount;
            }

            if ($q->total_collection == 0) {
                $due = $q->net_pay;
            } else {
                $due = $q->net_pay - $q->total_collection;
            }
            
            fputcsv($handle, array($cnt, $q->inceptiondate, $q->city_name, $q->branch_name, $q->total, $discount, $q->net_pay, ($q->total_collection) ? $q->total_collection : 0, $due, $q->remark));
        }

        fclose($handle);
        exit;
    }

    function export_installment() {
        $id = $this->uri->segment('3');
        $que = $this->Branch_collections_model->get_val("select bcd.id,bcd.installment,bcd.remark, bcd.created_date,bc.total,bm.branch_name,
                    bcd.payment_mode,bcd.receipt_date
                from branch_collections_details bcd 
                INNER JOIN branch_collections bc on bc.id = bcd.br_collection_fk AND bc.status='1' 
                LEFT JOIN branch_master bm on bm.id = bc.branch_fk AND bm.status='1' 
                where 1=1 AND bcd.status='1' AND bc.id='$id' ORDER BY bcd.id DESC");

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Branch Collections Installments.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No", "Date Of Receipt", "Branch", "Mode Of Payment", "Amount", "Payment Details"));

        $cnt = 0;
        foreach ($que as $q) {
            $cnt++;
            fputcsv($handle, array($cnt, $q->receipt_date, $q->branch_name, $q->payment_mode, $q->installment, $q->remark));
        }

        fclose($handle);
        exit;
    }

    function invoice() {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['user'] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $this->uri->segment('3');
        $id = $data['cid'];
        
        $data['query'] = $this->Branch_collections_model->get_val("select bcd.id,bcd.installment,bcd.remark,bc.inceptiondate,bc.total,bcd.payment_mode,bm.branch_name, 
                bm.id as branch_id,bc.discount,bc.discount_type,bc.address,bcd.receipt_date 
                from branch_collections_details bcd 
                INNER JOIN branch_collections bc on bc.id = bcd.br_collection_fk AND bc.status='1' 
                LEFT JOIN branch_master bm on bm.id = bc.branch_fk AND bm.status='1' 
                where 1=1 AND bcd.status='1' AND bc.id='$id' ORDER BY bcd.id DESC");

        $filename = $data['query'][0]->branch_name . "_invoice_" . date('Y-m-d') . ".pdf";
        $pdfFilePath = FCPATH . "/upload/branchreport/" . $filename;
        $html = $this->load->view('invoice_branchcollection_view', $data, true);

        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;
        $pdf->autoLangToFont = true;

        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 0, // margin_left
                0, // margin right
                5, // margin top
                5, // margin bottom
                5, // margin header
                5);
        $pdf->SetHTMLFooter('<table class="tbl_full" style="margin-bottom:10px;margin-left:30px;width:100%;">
                                <tr>
                                    <td>
									  <table style="">
									    <tr>
										 <td colspan="2"><b style="font-size:15px;">LAB AT YOUR DOORSTEP</b></td>
										 
										</tr>
										 <tr>
						 <td colspan="2" style="background:#D81238;text-align:center;">
						 <h1 style="color:#fff;font-size:30px;padding:10px;font-weight:300">8101-161616</h1>
						 </td>
						 
										 
										</tr>
									  </table>
									 </td>
									 <td>
									
                                 <table style="width:98%;float:right; font-family:"Roboto", sans-serif;font-size:12
								 px;margin-left:35px;">
                                            <tr>
                                                <td></td><td style="text-align:left"><b style="font-size:14px;">AIRMED PATHOLOGY PVT.LTD.</b></td>
                                            </tr>
                                            <tr><td></td><td><b>HEAD OFFICE:30 Ambika Society, near usmanpura garden, next to NABARD</b>							</td>
                                            </tr>
                                            <tr>
                                                <td></td><td><b>bank, usmanpura Ahmedabad-380013 </b>							</td>
                                            </tr>
                                            <tr><td></td><td><b style="color:#0101FF">www.airmedlabs.com  | info@airmedlabs.com </b><b>| accounts@airmedlabs.com</b></td>
                                            </tr>
                                        </table></td>
                                </tr>
                            </table>');
        if ($_REQUEST["debug"] == 1) {
            echo $html;
            die();
        }
        $pdf->WriteHTML($html);
        $pdf->debug = true;
        $pdf->allow_output_buffering = TRUE;
        if (file_exists($pdfFilePath) == true) {
            $this->load->helper('file');
            unlink($path);
        }
        $pdf->Output($pdfFilePath, 'F');

        $base_url = base_url() . "upload/branchreport/" . $filename;
        $filename = FCPATH . "/upload/branchreport/" . $filename;
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '";');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
        exit;
    }

}

?>
