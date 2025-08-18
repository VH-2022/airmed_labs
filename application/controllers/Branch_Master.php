<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Branch_Master extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('Branch_Model');
        $this->load->model('registration_admin_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->helper('string');
        //echo current_url(); die();

        $data["login_data"] = logindata();
    }

    function Branch_list() {
        $data['branch_name'] = $branchid = $this->input->get('branch_name');
        $data['test_city'] = $test_city = $this->input->get('test_city');
        $data['btype_select'] = $btype_select = $this->input->get('branch_type');
        $data["login_data"] = logindata();
        if(isset($_GET['debug']) && $_GET['debug'] ==1){
            echo "<pre>";print_r($data["login_data"]);die();
        }
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $data['unsuccess'] = $this->session->flashdata("unsuccess");
        if ($branchid != "" || $test_city != '' || $btype_select != '') {
            $totalRows = $this->Branch_Model->num_row($branchid, $test_city, $btype_select);

            $config = array();
            $get = $_GET;
            unset($get['offset']);
            $config["base_url"] = base_url() . "Branch_Master/Branch_list?" . http_build_query($get);
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->Branch_Model->master_get_search($branchid, $config["per_page"], $page, $test_city, $btype_select);
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
            //echo "hiii";
            $cnt = 0;
        } else {
            $totalRows = $this->Branch_Model->num_row($branchid);
            $config = array();
            $get = $_GET;
            $config["base_url"] = base_url() . "Branch_Master/Branch_list/";
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
            $data['query'] = $this->Branch_Model->master_get_search($branchid, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            //echo "bye";
            $cnt = 0;
        }
		$i=0;
        foreach ($data['query'] as $q) {
            $id = $q['id'];
            $processing_center = $this->Branch_Model->get_val("SELECT id FROM `processing_center` WHERE `status`='1' AND `lab_fk`='$id'");
            $data['query'][$i]["processing_center"]=$processing_center;
            $i++;
        }
		
        $data['city'] = $this->Branch_Model->list_city();
        $data['branch_type'] = $this->Branch_Model->type_list();
        $data['branch'] = $this->Branch_Model->master_get_branch();
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('Branch_list', $data);
        $this->load->view('footer');
    }

    function user_branch($userid) {
        $branchid = $this->input->get('branch_name');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["id"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['userid'] = $userid;
        $data['branch_list'] = $this->registration_admin_model->get_val("SELECT * from branch_master where  status='1'");
        $data['list'] = $this->registration_admin_model->get_val("SELECT user_branch.id,`branch_master`.`branch_name`,user_branch.test_parameter FROM   `user_branch`    LEFT JOIN branch_master      ON `branch_master`.`id` = `user_branch`.`branch_fk`  WHERE user_branch.`status`=1 AND user_branch.`user_fk`=$userid");
        $config = array();
        $get = $_GET;
        $config["base_url"] = base_url() . "Branch_Master/Branch_list/";
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
        $data['query'] = $this->Branch_Model->master_get_search($branchid, $config["per_page"], $page);
        $data['city'] = $this->Branch_Model->list_city();
        /*  echo "<pre>";
          print_r($data['city']); */
        //echo "bye";
        $cnt = 0;
        $data['branch'] = $this->Branch_Model->master_get_branch();
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('user_branch', $data);
        $this->load->view('footer');
    }

    function user_city($userid) {
        $branchid = $this->input->get('branch_name');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["id"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['userid'] = $userid;
        $data['branch_list'] = $this->registration_admin_model->get_val("SELECT * from branch_master where  status='1'");
        $data['list'] = $this->registration_admin_model->get_val("SELECT user_branch.id,`branch_master`.`branch_name`,user_branch.test_parameter FROM   `user_branch`    LEFT JOIN branch_master      ON `branch_master`.`id` = `user_branch`.`branch_fk`  WHERE user_branch.`status`=1 AND user_branch.`user_fk`=$userid");
        $config = array();
        $get = $_GET;
        $config["base_url"] = base_url() . "Branch_Master/Branch_list/";
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
        $data['query'] = $this->Branch_Model->master_get_search($branchid, $config["per_page"], $page);
        $data['city'] = $this->Branch_Model->list_city();
        /*  echo "<pre>";
          print_r($data['city']); */
        //echo "bye";
        $cnt = 0;
        $data['branch'] = $this->Branch_Model->master_get_branch();
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('user_branch', $data);
        $this->load->view('footer');
    }

    function Branch_add() {

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['city'] = $this->Branch_Model->list_city();
        $data['success'] = $this->session->flashdata("success");
//Vishal COde Start

        $data['branch_type'] = $this->Branch_Model->type_list();
        $data['branch_list'] = $this->Branch_Model->get_list();

        //Vishal Code End
        $this->load->library('form_validation');
        $this->form_validation->set_rules('branch_type_fk', 'Branch Type', 'trim|required');
        $this->form_validation->set_rules('branch_code', 'Branch Code', 'trim|required|xss_clean');
        $this->form_validation->set_rules('branch_name', 'Branch Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('city', 'City', 'trim|required|xss_clean');
//        $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() != FALSE) {
            //echo "<pre>"; print_r($_POST); die();
            //vishal COde Start

            $branch_type_fk = explode('-', $this->input->post('branch_type_fk'));
            $post['branch_type_fk'] = $branch_type_fk[0];
            $post['parent_fk'] = $this->input->post('parent_fk');

            //vishal Code End
            $post['branch_code'] = $this->input->post('branch_code');
            $post['branch_name'] = $this->input->post('branch_name');
            $post['city'] = $this->input->post('city');
            $post['address'] = $this->input->post('address');
            $post['bankdetils'] = $this->input->post('bankdetils');
            $post['bank_name'] = $this->input->post('bank_name');
            $post['bank_acc_no'] = $this->input->post('bank_acc_no');
            $post['status'] = 1;
            $post['createddate'] = date('Y-m-d h:i:s');
            $post['created_by'] = $data['user']->id;
            $post['updated_by'] = $data['user']->id;
            
                        $post['auto_completejob'] = $this->input->post('auto_completejob');
            $post['smsalert'] = ($this->input->post('sms_alert') == '') ? 0 : 1;
            $post['emailalert'] = ($this->input->post('email_alert') == '') ? 0 : 1;
            $post['ipd'] = ($this->input->post('ipd') == '') ? 0 : 1;

            $bid=$this->Branch_Model->master_get_insert("branch_master", $post);
			$city=$this->input->post('city');
			
			 $this->db->query("INSERT INTO test_branch_price (test_fk,price,branch_fk,TYPE) SELECT  test_fk,price,'$bid','1' FROM `test_master_city_price` WHERE STATUS='1' AND city_fk='$city'");
		
		$this->db->query("INSERT INTO test_branch_price (test_fk,price,branch_fk,TYPE) SELECT  package_fk,d_price,'$bid','2' FROM `package_master_city_price` WHERE status='1' AND city_fk='$city'");
		

            $this->session->set_flashdata("success", 'Branch successfully Added.');
            redirect("Branch_Master/Branch_list", "refresh");
        } else {

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('Branch_add', $data);
            $this->load->view('footer');
        }
    }

    function user_branch_add($userid) {

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $parameter = $this->input->post("parameter");
        if ($parameter == null) {
            $parameter = 0;
        }
        $post = array();
        $post['branch_fk'] = $this->input->post('branch');
        $post['test_parameter'] = $parameter;
        $post['user_fk'] = $userid;
        $post['status'] = 1;
        $branch = $this->Branch_Model->master_get_where_condtion("user_branch", $post, array("id", "desc"));
        if (count($branch) == 0) {
            $data['query'] = $this->Branch_Model->master_get_insert("user_branch", $post);
        }

        $this->session->set_flashdata("success", 'Branch successfully Deleted.');
        redirect("Branch_Master/user_branch/" . $userid, "refresh");
    }

    function user_branch_delete($userid, $id) {

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');

        $data['query'] = $this->Branch_Model->master_tbl_update("user_branch", $id, array("status" => "0"));

        $this->session->set_flashdata("success", 'Branch successfully .');
        redirect("Branch_Master/user_branch/" . $userid, "refresh");
    }

    function Branch_delete() {

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');

        $data['query'] = $this->Branch_Model->master_tbl_update("branch_master", $cid, array("status" => "0"));

        $this->session->set_flashdata("success", 'Branch successfully Deleted.');
        redirect("Branch_Master/Branch_list", "refresh");
    }

    function Branch_active_deactive() {
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $status = $this->uri->segment('4');
        $data['query'] = $this->Branch_Model->master_tbl_update("branch_master", $cid, array("status" => $status));
        $msg = "Activated";
        if ($status == 2) {
            $msg = "Deactivated";
        }
        $this->session->set_flashdata("success", 'Branch successfully ' . $msg . '.');
        redirect("Branch_Master/Branch_list", "refresh");
    }
    function whatsapp_report_send() {
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $status = $this->uri->segment('4');
        if($status == '2'){
            $status = 0;
        }
        
        $data['query'] = $this->Branch_Model->master_tbl_update("branch_master", $cid, array("whatsapp_report_send" => $status));
        
        $this->session->set_flashdata("success", 'Branch successfully whatsapp setting update.');
        redirect("Branch_Master/Branch_list", "refresh");
    }

    function allow_whatsapp() {
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $status = $this->uri->segment('4');
        if($status == '2'){
            $status = 0;
        }
        
        $data['query'] = $this->Branch_Model->master_tbl_update("branch_master", $cid, array("allow_whatsapp" => $status));
        
        $this->session->set_flashdata("success", 'Branch successfully whatsapp setting update.');
        redirect("Branch_Master/Branch_list", "refresh");
    }

    /* public function JobDoc_Delete()
      {
      $cid = $this->uri->segment('3');

      $data['query'] = $this->Branch_model->master_get_spam($cid);

      $this->session->set_flashdata('success','Branch Successfull Deleted');
      redirect('Branch_Master/Branch_list','refresh');
      } */

    function Branch_edit() {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['user'] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $this->uri->segment('3');
        $ids = $data['cid'];
        $data['view_data'] = $this->Branch_Model->master_get_view($ids);
        $data['city'] = $this->Branch_Model->list_city();

        //VIshal Code start
        $data['branch_type'] = $this->Branch_Model->type_list();
        $data['branch_list'] = $this->Branch_Model->get_list();
        //Vishal COde End
        $this->load->library('form_validation');
        $this->form_validation->set_rules('branch_type_fk', 'Branch Type', 'trim|required');
        $this->form_validation->set_rules('branch_code', 'Branch Code', 'trim|required|xss_clean');
        $this->form_validation->set_rules('branch_name', 'Branch Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('city', 'City', 'trim|required|xss_clean');
//        $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() != FALSE) {
            $branch_type_fk = explode('-', $this->input->post('branch_type_fk'));
            $post['branch_type_fk'] = $branch_type_fk[0];

            $post['parent_fk'] = $this->input->post('parent_fk');
            $post['branch_code'] = $this->input->post('branch_code');
            $post['branch_name'] = $this->input->post('branch_name');
            $post['city'] = $this->input->post('city');
            $post['address'] = $this->input->post('address');
            $btype = $this->input->post("btype");
            if ($btype == '0') {
                $post['parent_fk'] = null;
            }
            $post['status'] = 1;
            $post['created_by'] = $data['user']->id;
            $post['updated_by'] = $data['user']->id;
			
			$post['smsalert'] = $this->input->post('sms_alert');
            $post['emailalert'] = $this->input->post('email_alert');
            $post['auto_completejob'] = $this->input->post('auto_completejob');
            $post['ipd'] = $this->input->post('ipd');
            $data['query'] = $this->Branch_Model->master_tbl_update("branch_master", $ids, $post);

            $cnt = 0;

            $this->session->set_flashdata("success", 'Branch successfully Updated');

            redirect("Branch_Master/Branch_list", "refresh");
        } else {
            $data['query'] = $this->Branch_Model->master_get_where_condtion("branch_master", array("id" => $data["cid"]), array("id", "desc"));

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('Branch_edit', $data);
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
        $data['view_data'] = $this->Branch_Model->master_get_view($id);
        foreach ($data['view_data'] as $key => $val) {
            $parent_fk = $val['parent_fk'];
            $data['query'] = $this->Branch_Model->get_sub_client($parent_fk);
        }
        $data['parent_node'] = $this->Branch_Model->get_parent_node($id);
        $data['getlaball'] = $this->Branch_Model->get_val("SELECT GROUP_CONCAT(name) as labname FROM collect_from WHERE status='1' AND id IN(SELECT `labid` FROM b2b_labgroup WHERE status='1' AND branch_fk='$id') ORDER BY name ASC");
        $data['getProcessBranch'] = $this->Branch_Model->get_val("SELECT DISTINCT branch_master.* FROM branch_master INNER JOIN `processing_center` ON `processing_center`.`branch_fk`=`branch_master`.id WHERE branch_master.`status`='1' ORDER BY branch_master.id DESC");
        $data['getPdfBranch'] = $this->Branch_Model->get_val("SELECT `branch_master`.* FROM branch_master INNER JOIN `pdf_design` ON `pdf_design`.`branch_fk`=`branch_master`.id WHERE `branch_master`.`status`='1'");
        $data["processing_center"] = $this->Branch_Model->get_val("SELECT id FROM `processing_center` WHERE `status`='1' AND `lab_fk`='" . $this->uri->segment('3') . "'");
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('branch_view', $data);
        $this->load->view('footer');
    }

    function branch_setting() {
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        $lab_id = $this->uri->segment(3);
        $type = $this->input->post("type");
        $cparameter = $this->input->post("cparameter");
        $processing = $this->input->post("processing");
        $letterpad = $this->input->post("letterpad");

        if (!empty($type) && !empty($letterpad) && !empty($lab_id) && (!empty($cparameter) || !empty($processing))) {
            if ($type == 1 && !empty($cparameter) && !empty($letterpad)) {
                $check_processing_center = $this->Branch_Model->get_val("SELECT id FROM `processing_center` WHERE `status`='1' AND `branch_fk`='" . $lab_id . "' AND `lab_fk`='" . $lab_id . "'");
                /* SET Processing Center */
                if (empty($check_processing_center)) {
                    $this->Branch_Model->master_get_insert("processing_center", array("branch_fk" => $lab_id, "lab_fk" => $lab_id, "created_date" => date("Y-m-d H:i:s"), "status" => "1"));
                }
                /* END */
                /* SET PDF design */
                $check_pdf = $this->Branch_Model->get_val("SELECT * FROM `pdf_design` WHERE `status`='1' AND branch_fk='" . $lab_id . "'");
                if (empty($check_pdf) && !empty($letterpad)) {
                    $get_pdf = $this->Branch_Model->get_val("SELECT * FROM `pdf_design` WHERE `status`='1' AND branch_fk='" . $letterpad . "'");
                    $this->Branch_Model->master_get_insert("pdf_design", array("branch_fk" => $lab_id, "header" => $get_pdf[0]->header, "footer" => $get_pdf[0]->footer, "without_header" => $get_pdf[0]->without_header, "without_footer" => $get_pdf[0]->without_footer,"withsize" => $get_pdf[0]->withsize,"withoutsize" => $get_pdf[0]->withoutsize, "status" => "1"));
                }
                /* END */
                /* SET PDF Perview design */
                $check_pdf = $this->Branch_Model->get_val("SELECT * FROM `pdf_preview_design` WHERE `status`='1' AND branch_fk='" . $lab_id . "'");
                if (empty($check_pdf) && !empty($letterpad)) {
                    $get_pdf = $this->Branch_Model->get_val("SELECT * FROM `pdf_preview_design` WHERE `status`='1' AND branch_fk='" . $letterpad . "'");
                    $this->Branch_Model->master_get_insert("pdf_preview_design", array("branch_fk" => $lab_id, "header" => $get_pdf[0]->header, "footer" => $get_pdf[0]->footer,"withsize" => $get_pdf[0]->withsize, "status" => "1"));
                }
                /* END */
                /* START parameter copy */
                if (!empty($cparameter) && !empty($lab_id)) {

                    $get_response = $this->Branch_Model->run_query("INSERT INTO `test_parameter_master` (`center`,`test_fk`,`code`,`parameter_name`,`parameter_range`,`parameter_unit`,`formula`,`description`,`created_by`,`modified_by`,`created_date`,`modified_date`,`status`,`order`,`is_group`,`multiply_by`,`ref_test_fk`,`is_hundred`,`method` )
(SELECT '" . $lab_id . "',`test_fk`,`code`,`parameter_name`,`parameter_range`,`parameter_unit`,`formula`,`description`,`created_by`,`modified_by`,`created_date`,`modified_date`,`status`,`order`,`is_group`,`multiply_by`,`id`,`is_hundred`,`method` FROM test_parameter_master WHERE center='" . $cparameter . "')");
                    if ($get_response == true) {
                        $get_response = $this->Branch_Model->run_query("INSERT INTO `test_parameter` (`test_fk`,`center`,`parameter_fk`,`status`,`created_by`,`created_date`,`order`,`ref_id` )
(SELECT `test_fk`,'" . $lab_id . "',`parameter_fk`,`status`,`created_by`,`created_date`,`order` ,id FROM `test_parameter` WHERE center='" . $cparameter . "')");
                        if ($get_response == true) {
                            $get_response = $this->Branch_Model->run_query("INSERT  INTO  `parameter_referance_range` (center,`gender`,`no_period`,`type_period`,`normal_remarks`,`ref_range_low`,`low_remarks`,`ref_range_high`,`high_remarks`,`critical_low`,`critical_low_remarks`,`critical_high`,`critical_high_remarks`,`critical_low_sms`,`critical_high_sms`,`repeat_low`,`repeat_low_remarks`,`repeat_high`,`repeat_high_remarks`,`absurd_low`,`absurd_high`,`ref_range`,`parameter_fk`,`created_by`,`created_date`,`status`,`modified_by`,`modified_date`,`ref_id`)
(SELECT '" . $lab_id . "',`gender`,`no_period`,`type_period`,`normal_remarks`,`ref_range_low`,`low_remarks`,`ref_range_high`,`high_remarks`,`critical_low`,`critical_low_remarks`,`critical_high`,`critical_high_remarks`,`critical_low_sms`,`critical_high_sms`,`repeat_low`,`repeat_low_remarks`,`repeat_high`,`repeat_high_remarks`,`absurd_low`,`absurd_high`,`ref_range`,`parameter_fk`,`created_by`,`created_date`,`status`,`modified_by`,`modified_date`,`id` FROM`parameter_referance_range` WHERE center='" . $cparameter . "')");
                            if ($get_response == true) {
                                $get_response = $this->Branch_Model->run_query("INSERT INTO `test_result_status` (center,`parameter_code`,`parameter_name`,`result_status`,`critical_status`,`remarks`,`parameter_fk`,`created_by`,`created_date`,`status`,`modified_by`,`modified_date`,ref_id)
(SELECT '" . $lab_id . "',`parameter_code`,`parameter_name`,`result_status`,`critical_status`,`remarks`,`parameter_fk`,`created_by`,`created_date`,`status`,`modified_by`,`modified_date`,id  FROM `test_result_status` WHERE center='" . $cparameter . "')");
                                if ($get_response == true) {
                                    $get_response = $this->Branch_Model->run_query("UPDATE `test_parameter` SET `parameter_fk` =
(SELECT test_parameter_master.`id` FROM `test_parameter_master` WHERE test_parameter_master.`ref_test_fk`=test_parameter.`parameter_fk` AND  test_parameter_master.`center`='" . $lab_id . "' ) WHERE test_parameter.`center`='" . $lab_id . "'");
                                    if ($get_response == true) {
                                        $get_response = $this->Branch_Model->run_query("UPDATE `parameter_referance_range` SET parameter_referance_range.`parameter_fk`=
(SELECT test_parameter_master.`id` FROM `test_parameter_master` WHERE test_parameter_master.`ref_test_fk`=parameter_referance_range.`parameter_fk` AND  test_parameter_master.`center`='" . $lab_id . "'  ) WHERE parameter_referance_range.`center`='" . $lab_id . "'");
                                        if ($get_response == true) {
                                            $get_response = $this->Branch_Model->run_query("UPDATE `test_result_status` SET test_result_status.`parameter_fk`=
(SELECT test_parameter_master.`id` FROM `test_parameter_master` WHERE test_parameter_master.`ref_test_fk`=test_result_status.`parameter_fk` AND test_parameter_master.`center`='" . $lab_id . "' ) WHERE test_result_status.`center`='" . $lab_id . "'");
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                /* END */
            }

            if ($type == 2 && !empty($processing) && !empty($letterpad)) {
                $check_processing_center = $this->Branch_Model->get_val("SELECT id FROM `processing_center` WHERE `status`='1' AND `branch_fk`='" . $processing . "' AND `lab_fk`='" . $lab_id . "'");
                /* SET Processing Center */
                if (empty($check_processing_center)) {
                    $this->Branch_Model->master_get_insert("processing_center", array("branch_fk" => $processing, "lab_fk" => $lab_id, "created_date" => date("Y-m-d H:i:s"), "status" => "1"));
                }
                /* END */
                /* SET PDF design */
                $check_pdf = $this->Branch_Model->get_val("SELECT * FROM `pdf_design` WHERE `status`='1' AND branch_fk='" . $lab_id . "'");
                if (empty($check_pdf) && !empty($letterpad)) {
                    $get_pdf = $this->Branch_Model->get_val("SELECT * FROM `pdf_design` WHERE `status`='1' AND branch_fk='" . $letterpad . "'");
                    $this->Branch_Model->master_get_insert("pdf_design", array("branch_fk" => $lab_id, "header" => $get_pdf[0]->header, "footer" => $get_pdf[0]->footer, "without_header" => $get_pdf[0]->without_header, "without_footer" => $get_pdf[0]->without_footer, "status" => "1"));
                }
                /* END */
                /* SET PDF Perview design */
                $check_pdf = $this->Branch_Model->get_val("SELECT * FROM `pdf_preview_design` WHERE `status`='1' AND branch_fk='" . $lab_id . "'");
                if (empty($check_pdf) && !empty($letterpad)) {
                    $get_pdf = $this->Branch_Model->get_val("SELECT * FROM `pdf_preview_design` WHERE `status`='1' AND branch_fk='" . $letterpad . "'");
                    $this->Branch_Model->master_get_insert("pdf_preview_design", array("branch_fk" => $lab_id, "header" => $get_pdf[0]->header, "footer" => $get_pdf[0]->footer, "status" => "1"));
                }
                /* END */
            }
            echo 1;
            die();
        } else {
            echo 0;
            die();
        }
    }

}

?>
