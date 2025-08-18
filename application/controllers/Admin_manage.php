<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_manage extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('Admin_manage_model');
        $this->load->model('permissions_model');
        $this->load->model('user_permissions_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('email');
        $this->load->helper('string');
        $data["login_data"] = logindata();
        $this->app_track();
    }

    function app_track()
    {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function user_list()
    {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $totalRows = $this->Admin_manage_model->num_row('admin_master', array('status' => 1));
        $config = array();
        $config["base_url"] = base_url() . "Admin_manage/user_list/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 600;
        $config["uri_segment"] = 3;
        $config['cur_tag_open'] = '<span>';
        $config['cur_tag_close'] = '</span>';
        $config['next_link'] = 'Next &rsaquo;';
        $config['prev_link'] = '&lsaquo; Previous';
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $data["status"] =  $status = ($this->input->get("status")) ? $this->input->get("status") : 1;
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["query"] = $this->Admin_manage_model->admin_list($config["per_page"], $page,$status);
        $data["links"] = $this->pagination->create_links();
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('admin_list', $data);
        $this->load->view('footer');
    }

    function user_add()
    {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->helper("Email");
        $email_cnt = new Email;

        $data["login_data"] = logindata();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_username_check');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|numeric|min_length[10]|max_length[10]|callback_phone_check');
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('cityname', 'City', 'trim');
        $type = $this->input->post('type');
        if ($type == 5 || $type == 6 || $type == 7) {
            $this->form_validation->set_rules('branch[]', 'Branch', 'trim|required');
        }

        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $phone = $this->input->post('phone');
            $type = $this->input->post('type');
            $cityname = $this->input->post('cityname');
            $branch = $this->input->post('branch');
            $pinelab = $this->input->post('pinelab');
            $payment_due = $this->input->post('payment_due');
            if($payment_due !="yes"){
                $payment_due='no';
            }
            if ($type == '1') {
                $type_name = 'Normal user';
            }
            if ($type == '2') {
                $type_name = 'Telecaller user';
            }
            $data['query'] = $this->Admin_manage_model->master_fun_insert("admin_master", array("name" => $name, "phone" => $phone, "email" => $email, "password" => $password, "city_fk" => $cityname, "type" => $type, "pinelab_fk" => $pinelab, "payment_due" => $payment_due));
            if ($type == 5 || $type == 6 || $type == 7 || $type == 10) {
                foreach ($branch as $b_key) {
                    $this->Admin_manage_model->master_fun_insert("user_branch", array("user_fk" => $data['query'], "branch_fk" => $b_key, "status" => "1"));
                }
            }
            /* Mail send start */
            $config['mailtype'] = 'html';
            $this->email->initialize($config);

            $message = '<div style="padding:0 4%;">
                    <h4><b>Create Account</b></h4>
                        <p style="color:#7e7e7e;font-size:13px;">Your Admin account successfully created. </p>
                     <p style="color:#7e7e7e;font-size:13px;"> Username/Email : . ' . $email . '  </p>  
<p style="color:#7e7e7e;font-size:13px;"> Password : ' . $password . '  </p>
    <p style="color:#7e7e7e;font-size:13px;"> Account Type : ' . $type_name . '  </p>
    <p style="color:#7e7e7e;font-size:13px;"> Login Link : <a href="' . base_url() . '/login">' . base_url() . '/login</a></p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>';
            $message = $email_cnt->get_design($message);
            $this->email->to($email);
            $this->email->from('donotreply@airmedpathlabs.com', 'Airmed PathLabs');
            $this->email->subject('Admin Account Created Successfully');
            $this->email->message($message);
            $this->email->send();
            /* mail send end */
            $this->session->set_flashdata("success", array("User successfully added."));
            redirect("Admin_manage/user_list", "refresh");
        } else {
            $data["branch_list"] = $this->Admin_manage_model->master_fun_get_tbl_val("branch_master", array("status" => '1'), array("branch_name", "asc"));
            $data["getcity"] = $this->Admin_manage_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
            $data["pinelab_terminal_master"] = $this->Admin_manage_model->master_fun_get_tbl_val("pinelab_terminal_master", array("status" => '1'), array("name", "asc"));

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('user_add', $data);
            $this->load->view('footer');
        }
    }

    public function username_check($str)
    {
        $cnt = $this->Admin_manage_model->master_fun_get_tbl_val("admin_master", array("status" => '1', "email" => $str), array("id", "desc"));
        if (!empty($cnt)) {
            $this->form_validation->set_message('username_check', 'This email is already used.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function username_check1($str)
    {
        $cnt = $this->Admin_manage_model->master_fun_get_tbl_val("admin_master", array("status" => '1', "email" => $str, "id !=" => $this->uri->segment(3)), array("id", "desc"));
        if (!empty($cnt)) {
            $this->form_validation->set_message('username_check1', 'This email is already used.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function phone_check($str)
    {
        $cnt = $this->Admin_manage_model->master_fun_get_tbl_val("admin_master", array("status" => '1', "phone" => $str), array("id", "desc"));
        if (!empty($cnt)) {
            $this->form_validation->set_message('phone_check', 'This phone no. is already used.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function phone_check1($str)
    {
        $cnt = $this->Admin_manage_model->master_fun_get_tbl_val("admin_master", array("status" => '1', "phone" => $str, "id !=" => $this->uri->segment(3)), array("id", "desc"));
        if (!empty($cnt)) {
            $this->form_validation->set_message('phone_check1', 'This phone no. is already used.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function user_delete()
    {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("admin_master", array("id", $cid), array("status" => "0"));
        $this->session->set_flashdata("success", array("User successfully deleted."));
        redirect("Admin_manage/user_list", "refresh");
    }

    function user_edit()
    {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["cid"] = $this->uri->segment('3');
        $data["login_data"] = logindata();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_username_check1');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|numeric|min_length[10]|max_length[10]|callback_phone_check1');
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('cityname', 'City', 'trim');

        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $phone = $this->input->post('phone');
            $type = $this->input->post('type');
            $cityname = $this->input->post('cityname');
            $pinelab = $this->input->post('pinelab');
            $payment_due = $this->input->post('payment_due');
            if($payment_due !="yes"){
                $payment_due='no';
            }
        
            $data['query'] = $this->Admin_manage_model->master_fun_update("admin_master", array("id", $this->uri->segment('3')), array("name" => $name, "phone" => $phone, "email" => $email, "password" => $password, "type" => $type, "city_fk" => $cityname, "pinelab_fk" => $pinelab, "payment_due" => $payment_due));
            $this->session->set_flashdata("success", array("User successfully updated."));
            redirect("Admin_manage/user_list", "refresh");
        } else {
            $data['query'] = $this->Admin_manage_model->master_fun_get_tbl_val("admin_master", array("id" => $data["cid"]), array("id", "desc"));
            $data["getcity"] = $this->Admin_manage_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
            $data["pinelab_terminal_master"] = $this->Admin_manage_model->master_fun_get_tbl_val("pinelab_terminal_master", array("status" => '1'), array("name", "asc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('user_edit', $data);
            $this->load->view('footer');
        }
    }
    function user_deactive()
    {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $cid = $this->uri->segment('3');
        $status = $this->uri->segment('4');
        $data['query'] = $this->user_model->master_fun_update("admin_master", array("id", $cid), array("status" => $status));
        $sts = "activated.";
        if ($status == 2) {
            $sts = "deactivated.";
        }
        $this->session->set_flashdata("success", array("User successfully " . $sts));
        redirect("Admin_manage/user_list", "refresh");
    }
    function user_logintype()
    {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $cid = $this->uri->segment('3');
        $status = $this->uri->segment('4');
        $data['query'] = $this->user_model->master_fun_update("admin_master", array("id", $cid), array("login_type" => $status));
        $sts = "SMS varification activated.";
        if ($status == 0) {
            $sts = "SMS varification dectivated.";
        }
        $this->session->set_flashdata("success", array($sts));
        redirect("Admin_manage/user_list", "refresh");
    }

    function user_permission_edit($id){
        $data["id"] = $id;
        $data["login_data"] = logindata();
        $data["cid"] = $id;
        $data["permissions"] = $this->permissions_model->permissions();
        $data['query'] = $this->Admin_manage_model->master_fun_get_tbl_val("admin_master", array("id" => $id), array("id", "desc"));
        
        $permissions = [];
        foreach ($data["permissions"] as $permission) {

            $userPermissionData = $this->user_permissions_model->userPermission($data["cid"], $permission['id']);
            $checked = "";
            if (count($userPermissionData) > 0) {
                $checked = 'checked';
            }

            $permissions[$permission['module_name']][] = [
                'sub_module_name' => $permission['sub_module_name'],
                'permission' => $permission['permission'],
                'id' => $permission['id'],
                'checked' => $checked
            ];
        }

        $newArray = [];
        foreach ($permissions as $k => $p) {
            foreach ($p as $c => $b) {
                if (!key_exists($b["sub_module_name"], $newArray[$k]["sub_module_name"])) {
                    $newArray[$k][$b["sub_module_name"]][] = $b;
                }
            }
        }

        $data["permissions"] = $newArray;
       
        $data["day_permission"] = $this->user_model->master_fun_get_tbl_val("admin_master", array('id'=> $data["cid"],'status' => 1), array("name", "asc"));

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('user_permission', $data);
        $this->load->view('footer');
    }

    function updatePermission()
    {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data['error'] = $this->session->flashdata("error");
        $permissions = $this->input->post('permission');
        $userId = $this->input->post('user_id');
        $day_permission = $this->input->post('day_permission');
        $report_day_permission = $this->input->post('report_day_permission');
        if($report_day_permission == ""){
            $report_day_permission = 7;
        }
        if($day_permission == ""){
            $day_permission = 7;
        }
        // echo "<pre>";print_r($_POST);die();
        $insertData = [];

        $deleteOldPermissions = $this->user_permissions_model->master_fun_update("user_permissions", ["user_id", $userId], ['deleted_at' => date('Y-m-d h:i:s')]);
        foreach ($permissions as $permissionId) {
            $insertData = array(
                "permission_id" => $permissionId,
                'user_id' => $userId
                // 'day_permission' => $day_permission
            );
            $update = $this->user_permissions_model->insert_master("user_permissions", $insertData);
        }

        $day_permission_user = $this->user_permissions_model->master_fun_update("admin_master", ["id", $userId], ['day_permission' => $day_permission,'report_day_permission' => $report_day_permission]);

        $this->session->set_flashdata("success", array("User permission successfully updated."));


        redirect("Admin_manage/user_list", "refresh");

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('admin_list', $data);
        $this->load->view('footer');
    }

    // function userPermissionCron(){
    //     $getUser =$this->Admin_manage_model->admin_list(1000000000, 0);

    //     if(!empty($getUser)){
    //         foreach($getUser as $user){

                
    //             $userId = $user['id'];
    //             if($userId != 10 && $userId != 11 && $userId != 50){
    //                 $permissionIds = [1,14,15,16,17,19,20,22,24,25,26,27,28,29,30,31,32,33,353];

    //                 foreach($permissionIds as $permissionId){
    //                     $insertData = array(
    //                         "permission_id" => $permissionId,
    //                         'user_id' => $userId
    //                     );
    //                     $update = $this->user_permissions_model->insert_master("user_permissions", $insertData);
    //                 }
    //             }
                
    //         }
    //     }
    // }
}
