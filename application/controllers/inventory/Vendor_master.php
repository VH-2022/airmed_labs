<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vendor_master extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('inventory/vendor_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $data["login_data"] = logindata();
        $this->app_track();
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
        $name = $this->input->get('search');

        $city_name = $this->input->get('city');
        $mobile = $this->input->get('mobile');
        $email = $this->input->get('email');
        $cp_name = $this->input->get('cp_name');
        $bank_name = $this->input->get('bank_name');
         $branch_name = $this->input->get('branch_name');
          $account_no = $this->input->get('account_no');
        // Vishal Code End
        if ($name != "" || $city_name != "" || $mobile != "" || $email != "" || $cp_name != "" || $bank_name != "" || $branch_name !="" || $account_no !="") {
            $srchdata = array("name" => $name, "city_name" => $city_name, "mobile" => $mobile,"email" => $email, "cp_name" => $cp_name,"bank_name"=>$bank_name, "branch_name" => $bank_name,"branch_name"=>$branch_name,"account_no"=>$account_no);
            $data['name'] = $name;
            $data['city_one'] = $city_name;

            $data['mobile'] = $mobile;
            /* Vishal Code Start */
            $data['phone_no'] = $phone_no;
            $data['email'] = $email;
            $data['cp_name'] = $cp_name;

            $data['bank_name'] = $bank_name;
            $data['branch_name'] = $branch_name;
            $data['account_no'] = $account_no;
            /* Vishal Code End */
           
            $totalRows = $this->vendor_model->doctorcount_list($srchdata);
   
            /* Vishal Code Start */
            $config["base_url"] = base_url() . "inventory/vendor_master/index?search=$name&city=$city_name&mobile=$mobile&phone_no=$phone_no&email=$email&cp_name=$cp_name&bank_name=$bank_name&branch_name=$bank_name&branch_name=$branch_name&account_no=$account_no";

            $config["total_rows"] = $totalRows;
            /* Vishal Code End */
            $config["per_page"] = 50;
            $config["uri_segment"] = 4;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';


            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data["query"] = $this->vendor_model->doctorlist_list($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {
            $srchdata = array();
            $totalRows = $this->vendor_model->doctorcount_list($srchdata);

            $config = array();
            $config["base_url"] = base_url() . "inventory/vendor_master/index";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 4;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data["query"] = $this->vendor_model->doctorlist_list($srchdata, $config["per_page"], $page);

            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }

        $data['city'] = $this->vendor_model->get_val("SELECT * FROM test_cities WHERE status='1' AND name IS NOT NULL AND name !=''");

        $this->load->view('inventory/header', $data);
        $this->load->view('inventory/nav', $data);
        $this->load->view('inventory/vendor_list', $data);
        $this->load->view('inventory/footer');
    }

    function add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $id = $data["login_data"]['id'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['city_list'] = $this->vendor_model->get_val("SELECT * FROM test_cities WHERE status='1' AND name IS NOT NULL AND name !=''");

        $this->load->library('form_validation');
        $this->form_validation->set_rules('vendor_name', 'Vendor Name', 'trim|required');

        // $this->form_validation->set_rules('city_fk', 'City', 'trim|required');
        $this->form_validation->set_rules('mobile', 'Contact no ', 'trim|xss_clean|numeric|min_length[10]|max_length[20]');
        $this->form_validation->set_rules('contact_no_2', 'Contact no ', 'trim|xss_clean|numeric|min_length[10]|max_length[20]');
        $this->form_validation->set_rules('contact_no_3', 'Contact no ', 'trim|xss_clean|numeric|min_length[10]|max_length[20]');

        $this->form_validation->set_rules('email_id', 'Email', 'trim|valid_email|xss_clean'); 
        $this->form_validation->set_rules('city_name', 'City', 'trim|alpha');
        /*$this->form_validation->set_rules('bank_name', 'Bank Name', 'trim|required');
        $this->form_validation->set_rules('branch_name', 'Branch Name', 'trim|required');
        $this->form_validation->set_rules('account_no', 'Account No', 'trim|required|numeric|xss_clean|min_length[11]|max_length[16]');
        $this->form_validation->set_rules('ifsc_code', 'IFSC Code', 'trim|required'); */
        if ($this->form_validation->run() != FALSE) {
            $vendor_name = $this->input->post('vendor_name');
            $city_fk = $this->input->post('city_fk');
            $city_name = $this->input->post('city_name');
            $address = $this->input->post('address');
            $mobile = $this->input->post('mobile');
            $contact_no_2 = $this->input->post('contact_no_2');
            $contact_no_3 = $this->input->post('contact_no_3');
            $email_id = $this->input->post('email_id');
            $cp_name = $this->input->post('cp_name');
            $cp_mobile = $this->input->post('cp_mobile');
            $cp_email_id = $this->input->post('cp_email_id'); 
            $bank_name = $this->input->post('bank_name');
            $account_no = $this->input->post('account_no');
            $branch_name = $this->input->post('branch_name');
            $remark = $this->input->post('remark');
            $ifsc_code = $this->input->post('ifsc_code');
            $this->vendor_model->master_fun_insert("inventory_vendor", array("vendor_name" => $vendor_name, "city_fk" => $city_fk, "address" => $address, "mobile" => $mobile, "contact_no_2" => $contact_no_2, "contact_no_3" => $contact_no_3, "email_id" => $email_id, "cp_name" => $cp_name, "cp_mobile" => $cp_mobile, "cp_email_id" => $cp_email_id, "status" => '1', "city_name" => $city_name,"bank_name" => $bank_name, "account_no" => $account_no, "branch_name" => $branch_name,  "remark" => $remark, "ifsc_code" => $ifsc_code, "created_date" => date("Y-m-d h:i:s"), "created_by" => $id));
//            if ($insert) {
//                $this->vendor_model->master_fun_insert("inventory_bank", array("vender_id" => $insert, "bank_name" => $bank_name, "account_no" => $account_no, "branch_name" => $branch_name, "city" => $city_fk, "remark" => $remark, "ifsc_code" => $ifsc_code, "status" => '1', "created_date" => date("Y-m-d h:i:s"), "created_by" => $id));
//            }
            $this->session->set_flashdata("success", array("Vendor successfully added."));
            redirect("inventory/vendor_master/index", "refresh");
        } else {

            $this->load->view('inventory/header', $data);
            $this->load->view('inventory/nav', $data);
            $this->load->view('inventory/vendor_add', $data);
            $this->load->view('inventory/footer');
        }
    }

    function delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('4');
        $data['query'] = $this->user_model->master_fun_update("inventory_vendor", array("id", $cid), array("status" => "0"));
        $this->session->set_flashdata("success", array("Vendor successfully deleted."));
        redirect("inventory/vendor_master/index", "refresh");
    }

    function edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $id = $data["login_data"]["id"];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('4');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('vendor_name', 'Vendor Name', 'trim|required');

        $this->form_validation->set_rules('mobile', 'Contact no ', 'trim|xss_clean|numeric|min_length[10]|max_length[20]');
        $this->form_validation->set_rules('contact_no_2', 'Contact no ', 'trim|xss_clean|numeric|min_length[10]|max_length[20]');
        $this->form_validation->set_rules('contact_no_3', 'Contact no ', 'trim|xss_clean|numeric|min_length[10]|max_length[20]');

        $this->form_validation->set_rules('email_id', 'Email', 'trim|valid_email|xss_clean');
        $this->form_validation->set_rules('cp_email_id', 'Contact Email', 'trim|valid_email|xss_clean');
        $this->form_validation->set_rules('city_name', 'City', 'trim|alpha');
        /*$this->form_validation->set_rules('bank_name', 'Bank Name', 'trim|required');
         $this->form_validation->set_rules('branch_name', 'Branch Name', 'trim|required');
         $this->form_validation->set_rules('ifsc_code', 'IFSC Code', 'trim|required');
          $this->form_validation->set_rules('account_no', 'Account No', 'trim|required|numeric|xss_clean|min_length[11]|max_length[16]');*/
        if ($this->form_validation->run() != FALSE) {
            $vendor_name = $this->input->post('vendor_name');
            $city_fk = $this->input->post('city_fk');
            $city_name = $this->input->post('city_name');
            $address = $this->input->post('address');
            $mobile = $this->input->post('mobile');
            $contact_no_2 = $this->input->post('contact_no_2');
            $contact_no_3 = $this->input->post('contact_no_3');
            $email_id = $this->input->post('email_id');
            $cp_name = $this->input->post('cp_name');
            $cp_mobile = $this->input->post('cp_mobile');
            $cp_email_id = $this->input->post('cp_email_id');
            $bank_name = $this->input->post('bank_name');
             $account_no = $this->input->post('account_no');
              $branch_name = $this->input->post('branch_name');
               $remark= $this->input->post('remark');
               $ifsc_code= $this->input->post('ifsc_code');
           $this->vendor_model->master_fun_update("inventory_vendor", array("id", $data["cid"]), array("vendor_name" => $vendor_name, "city_fk" => $city_fk, "address" => $address, "mobile" => $mobile, "contact_no_2" => $contact_no_2, "contact_no_3" => $contact_no_3, "email_id" => $email_id, "cp_name" => $cp_name, "cp_mobile" => $cp_mobile, "cp_email_id" => $cp_email_id, "city_name" => $city_name,"bank_name" => $bank_name, "account_no" => $account_no,"branch_name" => $branch_name, "remark"=>$remark,"ifsc_code" => $ifsc_code, "status" => 1, "modified_date" => date("Y-m-d h:i:s"), 'modified_by' => $id));
            
            $this->session->set_flashdata("success", array("Vendor successfully updated."));
            redirect("inventory/vendor_master/index", "refresh");
        } else {
                      $data['query'] = $this->vendor_model->get_val("select * from inventory_vendor  where id='".$data["cid"]."' and status='1' ");

            $data['city_list'] = $this->vendor_model->get_val("SELECT * FROM test_cities WHERE status='1' AND name IS NOT NULL AND name !=''");

            $this->load->view('inventory/header');
            $this->load->view('inventory/nav', $data);
            $this->load->view('inventory/vendor_edit', $data);
            $this->load->view('inventory/footer');
        }
    }

    function export_csv() {

        $name = $this->input->get('search');
        $city = $this->input->get('city');
        $mobile = $this->input->get('mobile');
        $email = $this->input->get('email');
        $cp_name = $this->input->get('cp_name');
        $bank_name = $this->input->get('bank_name');
        $branch_name = $this->input->get('branch_name');
        $account_no = $this->input->get('account_no');
        $result = $this->vendor_model->csv_report($name, $city, $mobile,  $email, $cp_name, $bank_name, $branch_name,$account_no);
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Vendor_Report .csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No", "Vendor Name", "City", "Address", "Contact No 1", "Contact No 2", "Contact No 3", "Email", "Contact Person Name", "Contact Person Number", "Bank Name","Branch Name","Account No"));
        $cnt = 1;
        foreach ($result as $val) {

            fputcsv($handle, array($cnt++, $val["vendor_name"], $val["city"], $val["address"], $val["mobile"], $val["contact_no_2"], $val["contact_no_3"], $val["email_id"], $val["cp_name"], $val["cp_mobile"], ucwords($val["bank_name"]),ucwords($val["branch_name"]),$val["account_no"]));
        }
        fclose($handle);
        exit;
    }
    
    
function add_login() {

        $this->load->helper(array('form', 'url'));

        $this->load->library('form_validation');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() != FALSE) {
            $id = $this->input->post("vendorid");
            $email = $this->input->post("email");
            $password = $this->input->post("password");

            $email_exist = $this->vendor_model->get_val("select id,login_email from inventory_vendor where status ='1' AND login_email = '$email'");
            if (!empty($email_exist)) {
                $this->session->set_flashdata("unsuccess", "Email is already exist.");
                redirect("inventory/vendor_master/index", "refresh");
            } else {
                $update = $this->vendor_model->master_fun_update("inventory_vendor", array("id", $id), array("login_email" => $email, 'password' => $password));
                $this->session->set_flashdata("success", array("Vendor login created successfully."));
                
                $vendor_data = $this->vendor_model->get_val("select * from inventory_vendor where status ='1' AND login_email = '$email'");
                $this->load->library('email');
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $user = $vendor_data[0]['vendor_name'];
                $login_email = $vendor_data[0]['login_email'];
                $password = $vendor_data[0]['password'];
                $link = base_url() . 'vendor';
                
                $message = "
                Dear $user, <br/><br/>
                    Your account has been successfully created for Airmed Pathology Labs vendor portal.<br/>
                    Click below link and use your username and password to login.
                    <br/><br/>
                    URL - $link <br/>
                    Username - $login_email <br/>
                    Password - $password <br/>
                <br/><br/>
                Thanks<br/>
                Airmed Pathology Pvt Ltd";
                
                $this->email->to($login_email);
                //$this->email->cc('kana@virtualheight.com');
                $this->email->from('donotreply@airmedpathlabs.com', 'AirmedLabs');
                $this->email->subject('Vendor Account Creation');
                $this->email->message($message);
                $this->email->send();
                
                
            }
        } else {
            $this->session->set_flashdata("error", array("error in creating vendor login."));
        }
        redirect("inventory/vendor_master/index", "refresh");
    }
    

}

?>