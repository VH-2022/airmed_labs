<?php


class Pro_master extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('Pro_model');
        // $this->load->model('patient_api_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $data["login_data"] = logindata();
        $this->app_track();
    }

    function app_track()
    {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    public function pro_list()
    {
        $relid = $this->input->get('search');
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $totalRows = $this->Pro_model->num_row('pro_master', array('pro_status' => 1));
        $config = array();
        $config["base_url"] = base_url() . "Pro_master/pro_list/";
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
        $data["query"] = $this->Pro_model->manage_condition_view($relid, $config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $cnt = 0;
        foreach ($data['query'] as $key) {
            $data['query'][$cnt] = $key;
            $cnt++;
        }
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('pro_list', $data);
        $this->load->view('footer');
    }

    public function pro_add()
    {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'PRO', 'trim|required|xss_clean');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|xss_clean|callback_check_unique_phone[' . $cid = "" . ']');
        $this->form_validation->set_rules('designation', 'Designation', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|callback_check_unique_email[' . $cid = "" . ']');
        $this->form_validation->set_rules('pwd', 'Password', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        if ($this->form_validation->run() != FALSE) {
            $post['pro_name'] = $this->input->post('name');
            $post['pro_designation'] = $this->input->post('designation');
            $post['pro_status'] = "1";
            $post["pro_email"] = $this->input->post('email');
            $post['pro_created_date'] = date('d-m-y g:i:s');
            $post['pro_mobile'] = $this->input->post('mobile');
            /* print_r($post); die(); */
            $data['query'] = $this->Pro_model->master_get_insert("pro_master", $post);

            // $pro_obj = array (
            //     "pro_name" => $this->input->post('name'),
            //     "pro_mobile" => $this->input->post('mobile'),
            //     "pro_email" => $this->input->post('email'),
            //     "pro_pwd" => $this->input->post('pwd'),
            //     "pro_created_date" => date('Y-m-d H:i:s')
            // );
            // $this->patient_api_model->post_data($pro_obj, "add_pro");

            $this->session->set_flashdata("success", array("Pro Successfull Added."));
            redirect("Pro_master/pro_list", "refresh");
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('pro_add', $data);
            $this->load->view('footer');
        }
    }

    public function pro_edit()
    {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $cid = $this->uri->segment('3');
        $ids = $data["cid"];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'PRO', 'trim|required|xss_clean');
        $this->form_validation->set_rules('designation', 'Designation', 'trim|required|xss_clean');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|xss_clean|callback_check_unique_phone[' . $cid . ']');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|callback_check_unique_email[' . $cid . ']');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        if ($this->form_validation->run() != FALSE) {
            $post['pro_name'] = $this->input->post('name');
            $post['pro_designation'] = $this->input->post('designation');
            $post['pro_status'] = "1";
            $post['pro_mobile'] = $this->input->post('mobile');
            $post["pro_email"] = $this->input->post('email');
            $data['query'] = $this->Pro_model->master_get_update("pro_master", array('id' => $_POST['id']), $post);
            $cnt = 0;
            $this->session->set_flashdata("success", array("Pro Successfull Updated."));
            redirect("Pro_master/pro_list", "refresh");
        } else {
            $data['query'] = $this->Pro_model->master_get_tbl_val("pro_master", array("id" => $data["cid"]), array("id", "desc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('pro_edit', $data);
            $this->load->view('footer');
        }
    }

    public function pro_delete()
    {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->Pro_model->master_get_update("pro_master", array("id" => $cid), array("pro_status" => "0"), array("id", "desc"));
        $this->session->set_flashdata("success", array("PRO Successfull Deleted."));
        redirect("Pro_master/pro_list", "refresh");
    }
    public function check_unique_phone($phone, $cid)
    {
        $existing_record = $this->Pro_model->checkUniquePhone($phone, $cid);

        if ($existing_record) {
            $this->form_validation->set_message('check_unique_phone', 'The Phone is not unique.');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    public function check_unique_email($email, $cid)
    {
        $existing_record = $this->Pro_model->checkUniqueEmail($email, $cid);

        if ($existing_record) {
            $this->form_validation->set_message('check_unique_email', 'The Email is not unique.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function pro_active_deactive()
    {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $status =  ($this->input->get('status') == 1) ? 'Activate' : 'Deactivate';
        $data['query'] = $this->Pro_model->master_get_update("pro_master", array("id" => $cid), array("pro_active_status" => $this->input->get('status')), array("id", "desc"));
        $this->session->set_flashdata("success", array("PRO " . $status . " successfully."));
        redirect("Pro_master/pro_list", "refresh");
    }
}
