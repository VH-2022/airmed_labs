<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Leave_applications extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('Leave_applications_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        //ini_set('display_errors', 1);
        $data["login_data"] = logindata();
        if ($data["login_data"]['type'] == "8") {
            redirect('login');
        }
    }

    function index() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $user = $this->input->get('user');
        $phlebo = $this->input->get('phlebo');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        if ($user != "" || $start_date != "" || $end_date != "" || $phlebo != "") {
            $srchdata = array("name" => $user, "start_date" => $start_date, "end_date" => $end_date, "user_info" => $data["user"], "phlebo" => $phlebo);
            $data['user'] = $user;
            $data['phlebo'] = $phlebo;
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $totalRows = $this->Leave_applications_model->phlebocount_list($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "Leave_applications/index/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["query"] = $this->Leave_applications_model->phlebolist_list($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $srchdata = array("user_info" => $data["user"]);
            $totalRows = $this->Leave_applications_model->phlebocount_list($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "Leave_applications/index/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["query"] = $this->Leave_applications_model->phlebolist_list($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }
        $data['success'] = $this->session->flashdata("success");
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('leave_request_list', $data);
        $this->load->view('footer');
    }

    function add() {
        //echo "<pre>"; print_r($_POST); exit;
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $this->load->library('form_validation');

        $this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
        $this->form_validation->set_rules('end_date', 'End Date', 'trim|required|callback_checkdate');
        $this->form_validation->set_rules('remark', 'Remark', 'trim|required');

        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() != FALSE) {

            $start_date = $this->input->post('start_date');
//            $temp = explode('-', $start_date);
//            $start_date = $temp[2] . '-' . $temp[1] . '-' . $temp[0];


            $end_date = $this->input->post('end_date');
//            $temp = explode('/', $end_date);
//            $end_date = $temp[2] . '-' . $temp[1] . '-' . $temp[0];

            $remark = $this->input->post('remark');

            $data['query'] = $this->Leave_applications_model->master_fun_insert("leave_requests", array(
                "user_id" => $data["user"]->id,
                "user_type" => "1",
                "start_date" => $start_date,
                "end_date" => $end_date,
                "remark" => $remark,
                "status" => '1',
                "created_date" => date('Y-m-d H:i:s'),
                "created_by" => $data["login_data"]["id"]));

            $this->load->library('email');
            $config['mailtype'] = 'html';
            $this->email->initialize($config);

            $data['row'] = $this->Leave_applications_model->get_val("SELECT lr.id,lr.user_id,lr.start_date,
                 lr.end_date,lr.remark,lr.admin_remark,lr.leave_status,am.name as user_name
                 from leave_requests lr 
                 LEFT JOIN admin_master am on am.id = lr.user_id AND am.status='1' 
                 where lr.status IN (1,2) AND lr.id= '" . $data['query'] . "' order by lr.id desc");

            $user = $data['row'][0]['user_name'];
            $link = base_url() . 'Leave_applications/leave_request_approve/' . $data['query'];
            $start_date = date('Y-m-d H:i', strtotime($data['row'][0]['start_date']));
            $end_date = date('Y-m-d H:i', strtotime($data['row'][0]['end_date']));

//            $start_date = $data['row'][0]['start_date'];
//            $end_date = $data['row'][0]['end_date'];

            $message = "
                Dear Sir/Madam, <br/><br/>
                    $user has requested for leave application on $start_date to $end_date.<br/>
                    Please <a href='$link' target='_blank'>click here </a> to approve.
                <br/><br/>
                Thanks<br/>
                Airmed Pathology Pvt Ltd";

            //$this->email->to("kana@virtualheight.com");
            $this->email->to("brijesh@virtualheight.com");

            $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
            $this->email->subject('Leave Application Of Employee');
            $this->email->message($message);
            $this->email->send();

            $this->session->set_flashdata("success", array("Leave Application has successfully added."));
            redirect("Leave_applications/index", "refresh");
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('add_leave_request', $data);
            $this->load->view('footer');
        }
    }

    function delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("leave_requests", array("id", $cid), array("status" => "0"));

        $this->session->set_flashdata("success", array("Leave Application has Deleted Successfully."));
        redirect("Leave_applications/index", "refresh");
    }

    function approve() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');

        $data['query'] = $this->user_model->master_fun_update("leave_requests", array("id", $cid), array("leave_status" => "1"));

        $user_type = $this->uri->segment('4');
        if ($user_type == "1") {
            $data['row'] = $this->user_model->get_val("SELECT lr.id,lr.user_id,lr.start_date, 
                 lr.end_date,lr.remark,lr.admin_remark,lr.leave_status,am.name as user_name 
                 from leave_requests lr 
                 LEFT JOIN admin_master am on am.id = lr.user_id AND am.status='1' 
                 where lr.status IN (1,2) AND lr.id= '" . $cid . "' order by lr.id desc");
        } else if ($user_type == "2") {
            $data['row'] = $this->user_model->get_val("SELECT lr.id,lr.user_id,lr.start_date, 
                 lr.end_date,lr.remark,lr.admin_remark,lr.leave_status,pm.name as user_name 
                 from leave_requests lr 
                 LEFT JOIN phlebo_master pm on pm.id = lr.user_id AND pm.status='1' 
                 where lr.status IN (1,2) AND lr.id= '" . $cid . "' order by lr.id desc");
        }


        // Send Mail to User when approve leave
        $this->load->library('email');
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $user = $data['row'][0]['user_name'];
        $admin_remark = $data['row'][0]['admin_remark'];
        $start_date = $data['row'][0]['start_date'];
        $end_date = $data['row'][0]['end_date'];

        $message = "
                Dear $user, <br/><br/>
                    Your leave application request on $start_date to $end_date has been <b>Approved</b>.<br/>
                    Remarks:- $admin_remark<br/>
                        If there are any changes to this situation please get in touch immediately.<br/>
                        If have any further queries regarding this request please email me on <br/>
                        hr@airmedlabs.com  <br/>
                <br/><br/>
                Thanks<br/>
                Airmed Pathology Pvt Ltd";

        //Note:-Put HR (main admin) in cc and send to user

        $email = $data['row'][0]['email'];
        $this->email->to($email);
        $this->email->cc('brijesh@virtualheight.com');
        //$this->email->cc('hr@airmedlabs.com');
        $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
        $this->email->subject('Leave Approve');
        $this->email->message($message);
        $this->email->send();

        $this->session->set_flashdata("success", array("Leave Application has successfully approved."));
        redirect("Leave_applications/index", "refresh");
    }

    function disapprove() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("leave_requests", array("id", $cid), array("leave_status" => "2"));

        $user_type = $this->uri->segment('4');
        if ($user_type == "1") {
            $data['row'] = $this->user_model->get_val("SELECT lr.id,lr.user_id,lr.start_date, 
                 lr.end_date,lr.remark,lr.admin_remark,lr.leave_status,am.name as user_name 
                 from leave_requests lr 
                 LEFT JOIN admin_master am on am.id = lr.user_id AND am.status='1' 
                 where lr.status IN (1,2) AND lr.id= '" . $cid . "' order by lr.id desc");
        } else if ($user_type == "2") {
            $data['row'] = $this->user_model->get_val("SELECT lr.id,lr.user_id,lr.start_date, 
                 lr.end_date,lr.remark,lr.admin_remark,lr.leave_status,pm.name as user_name 
                 from leave_requests lr 
                 LEFT JOIN phlebo_master pm on pm.id = lr.user_id AND pm.status='1' 
                 where lr.status IN (1,2) AND lr.id= '" . $cid . "' order by lr.id desc");
        }

        // Send Mail to User when approve leave
        $this->load->library('email');
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $user = $data['row'][0]['user_name'];
        $admin_remark = $data['row'][0]['admin_remark'];
        $start_date = date('Y-m-d', strtotime($data['row'][0]['start_date']));
        $end_date = date('Y-m-d', strtotime($data['row'][0]['end_date']));

        $message = "
                Hello $user, <br/><br/>
                    Your leave application request on $start_date to $end_date has been <b>Disapproved</b>.<br/>
                    Remarks:- $admin_remark<br/>
                    If there are any changes to this situation please get in touch immediately.<br/>
                    If have any further queries regarding this request please email me on <br/>
                    hr@airmedlabs.com  <br/>
                <br/><br/>
                Thanks<br/>
                Airmed Pathology Pvt Ltd";


        $email = $data['row'][0]['email'];
        $this->email->to($email);
        $this->email->cc('brijesh@virtualheight.com');
        //$this->email->cc('hr@airmedlabs.com');
        $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
        $this->email->subject('Leave Disapprove');
        $this->email->message($message);
        $this->email->send();

        $this->session->set_flashdata("success", array("Leave Application has canceled successfully ."));
        redirect("Leave_applications/index", "refresh");
    }

    function edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');
        $this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
        $this->form_validation->set_rules('end_date', 'End Date', 'trim|required|callback_checkdate');
        $this->form_validation->set_rules('remark', 'Remark', 'trim|required');

        if ($this->form_validation->run() != FALSE) {

            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $remark = $this->input->post('remark');
            $admin_remark = $this->input->post('admin_remark');
            $data['query'] = $this->Leave_applications_model->master_fun_update("leave_requests", array("id", $data["cid"]), array("start_date" => $start_date,
                "end_date" => $end_date,
                "remark" => $remark,
                "admin_remark" => $admin_remark
            ));
            $this->session->set_flashdata("success", array("Leave Application has successfully updated."));
            redirect("Leave_applications/index", "refresh");
        } else {
            $data['row'] = $this->Leave_applications_model->get_val("SELECT lr.id,lr.user_id,lr.start_date, 
                 lr.end_date,lr.remark,lr.admin_remark,lr.leave_status,lr.admin_remark,am.name as user_name 
                 from leave_requests lr 
                 LEFT JOIN admin_master am on am.id = lr.user_id AND am.status='1' 
                 where lr.status IN (1,2) AND lr.id='" . $data["cid"] . "' order by lr.id desc");

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('edit_leave_request', $data);
            $this->load->view('footer');
        }
    }

    public function checkdate($str) {

        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        if ($start_date > $end_date) {
            $this->form_validation->set_message('checkdate', 'End Date must be greater than start date');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function leave_request_approve($id) {

//        if (!is_loggedin()) {
//            redirect('login');
//        }
        $data["login_data"] = logindata();
        $data['cid'] = $id;
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $this->input->post('leave_id');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $admin_remark = $this->input->post('admin_remark');
        $leave_status = $this->input->post('leave_status');

        $this->load->library('form_validation');

        $this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
        $this->form_validation->set_rules('end_date', 'End Date', 'trim|required|callback_checkdate');

        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        $user_type = $this->Leave_applications_model->get_val("SELECT user_type from leave_requests where id='" . $data['cid'] . "'");

        if ($user_type[0]['user_type'] == "1") {
            $data['row'] = $this->Leave_applications_model->get_val("SELECT lr.id,lr.user_id,lr.start_date, 
                 lr.end_date,lr.remark,lr.admin_remark,lr.leave_status,lr.admin_remark,am.name as user_name,am.email  
                 from leave_requests lr 
                 LEFT JOIN admin_master am on am.id = lr.user_id AND am.status='1' 
                 where lr.status IN (1,2) AND lr.id='" . $data['cid'] . "' order by lr.id desc");
        } else if ($user_type[0]['user_type'] == "2") {
            $data['row'] = $this->Leave_applications_model->get_val("SELECT lr.id,lr.user_id,lr.start_date, 
                 lr.end_date,lr.remark,lr.admin_remark,lr.leave_status,lr.admin_remark,pm.name as user_name,pm.email  
                 from leave_requests lr 
                 LEFT JOIN phlebo_master pm on pm.id = lr.user_id AND pm.status='1' 
                 where lr.status IN (1,2) AND lr.id='" . $data['cid'] . "' order by lr.id desc");
        }


        if ($this->form_validation->run() != FALSE) {

            $this->load->library('email');
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $user = $data['row'][0]['user_name'];
            $email = $data['row'][0]['email'];

            if ($leave_status == "1") {
                $message = "
                Dear $user, <br/><br/>
                    Your leave application request on $start_date to $end_date has been <b>Approved</b>.<br/>
                    Remarks:- $admin_remark<br/>
                    If there are any changes to this situation please get in touch immediately.<br/>
                    If have any further queries regarding this request please email me on <br/>
                    hr@airmedlabs.com  <br/>
                <br/><br/>
                Thanks<br/>
                Airmed Pathology Pvt Ltd";

                $this->email->to($email);
                $this->email->cc('brijesh@virtualheight.com');
                //$this->email->cc('hr@airmedlabs.com');
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Leave Approve');
                $this->email->message($message);
                $this->email->send();

                $this->session->set_flashdata("success", array("Leave Application has successfully Approved."));
            } else if ($leave_status == "2") {

                $message = "
                Hello $user, <br/><br/>
                    Your leave application request on $start_date to $end_date has been <b>Disapproved</b>.<br/>
                    Remarks:- $admin_remark<br/>
                    If there are any changes to this situation please get in touch immediately.<br/>
                    If have any further queries regarding this request please email me on <br/>
                    hr@airmedlabs.com  <br/>
                <br/><br/>
                Thanks<br/>
                Airmed Pathology Pvt Ltd";

                $this->email->to($email);
                $this->email->cc('brijesh@virtualheight.com');
                //$this->email->cc('hr@airmedlabs.com');
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Leave Disapprove');
                $this->email->message($message);
                $this->email->send();
                $this->session->set_flashdata("success", array("Leave Application has successfully Disapproved."));
            } else {

                $this->session->set_flashdata("success", array("Leave Application has successfully updated."));
            }
            $data['query'] = $this->Leave_applications_model->master_fun_update("leave_requests", array("id", $id), array("start_date" => $start_date,
                "end_date" => $end_date,
                "admin_remark" => $admin_remark,
                "leave_status" => $leave_status
            ));
            redirect('leave_applications/leave_request_approve/' . $data['cid']);
        }
        $data['success'] = $this->session->flashdata("success");
        $this->load->view('leave_request_approve', $data);
    }

}

?>