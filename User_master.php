<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('home_model');
        $this->load->model('user_master_model');
        $this->load->model('user_wallet_model');
        $this->load->model('job_model');
        $this->load->model('register_model');
        $this->load->helper('string');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('pushserver');
        $this->load->library('pagination');
        $this->load->library('email');
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($uid != 0) {
            $maxid = $this->user_wallet_model->total_wallet($uid);
            $data['total'] = $this->user_wallet_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "id" => $maxid), array("id", "asc"));
            $this->data['wallet_amount'] = $data['total'][0]['total'];
        }
        /* pinkesh code start */
        $data['links'] = $this->user_master_model->master_fun_get_tbl_val("patholab_home_master", array("status" => 1), array("id", "asc"));
        $this->data['all_links'] = $data['links'];
        /* pinkesh code end */
    }

    function index() {
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["test_city_session"] = $this->session->userdata("test_city");
        if ($data["test_city_session"] == '') {
            $data["test_city_session"] = 1;
        }
        $data["login_data"] = loginuser();
        $data["user"] = $this->user_master_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $data['payment_success'] = $this->session->flashdata("payment_success");
        $data['payment_unsuccess'] = $this->session->flashdata("payment_unsuccess");
        //$data['package'] = $this->user_master_model->master_fun_get_tbl_val("package_master", array("status" => 1), array("title", "asc"));
        $data['health_feed'] = $this->user_master_model->master_fun_get_tbl_val("health_feed_master", array("status" => 1), array("title", "asc"));
        $data['set_setting'] = $this->user_master_model->master_fun_get_tbl_val("patholab_home_master", array("status" => 1), array("id", "asc"));
        $data['testimonial'] = $this->user_master_model->master_fun_get_tbl_val("testimonials_master", array("status" => 1), array("id", "asc"));
        $data['test_cities'] = $this->user_master_model->master_fun_get_tbl_val("test_cities", array("status" => 1), array("name", "asc"));
        $data['active_class'] = "home";
        $this->load->view('user/header', $data);
        $this->load->view('user/home', $data);
        $this->load->view('user/footer');
    }

    function package_list() {
        $city = $this->input->get_post("city");
        $this->session->set_userdata("test_city", $city);
        /* Nishit code start */
        $data = $this->user_master_model->master_fun_get_tbl_val("package_master", array("status" => 1), array("title", "asc"));
        $cnt = 0;
        foreach ($data as $key) {
            $output_data = '<div class="col-sm-6 col-md-4 pdng_0">
                                <div class="team-member bg-white-fa maxwidth400 indx_six_back">
                                    <a href="' . base_url() . 'user_master/package_details/' . $key['id'] . '"> 
                                        <div class="thumb">
                                            <img class="img-fullwidth" src="' . base_url() . 'thumb_helper.php?h=200&w=380&src=upload/package/' . $key['image'] . '" alt=""/>
                                            <div class="indx_six_overlay"></div>
                                            <div class="info p-15 pb-10 text-center">
                                                <h3 class="name m-0 six_part_name">' . ucfirst($key['title']) . '</h3>
                                                <h5 class="occupation font-weight-400 letter-space-1 mt-0 six_part_price">';
            if ($city != '') {
                $pkg_price = $this->user_master_model->master_fun_get_tbl_val("package_master_city_price", array("status" => "1", "package_fk" => $key["id"], "city_fk" => $city), array("id", "asc"));
                /* /*$output_data .='<span class="no_price">Rs.' . $pkg_price[0]["a_price"] . '/-</span><span>Rs.' . $pkg_price[0]["d_price"] . ' /-</span>'; */

                if (!empty($pkg_price)) {
                    $output_data .='<span class="no_price">Rs.' . $pkg_price[0]["a_price"] . '/-</span>
                                                    <span>Rs.' . $pkg_price[0]["d_price"] . ' /-</span>';
                } else {
                    $output_data .='<span class="no_price">Rs.' . $pkg_price[0]["a_price"] . '/-</span>
                                                    <span>Rs.' . $pkg_price[0]["d_price"] . ' /-</span>';
                }
            } else {
                $output_data .='<span class="no_price">Rs.' . $pkg_price[0]["a_price"] . '/-</span>
                                                    <span>Rs.' . $pkg_price[0]["d_price"] . ' /-</span>';
            }
            $output_data .= '</h5></div></div></a></div></div>';
            $cnt++;
            echo $output_data;
        }
    }

    function package_list1() {
        $city = $this->input->get_post("city");
        $data["test_city_session"] = $this->session->userdata("test_city");
        if ($data["test_city_session"] == '') {
            $data["test_city_session"] = 1;
        }
        $this->session->set_userdata("test_city", $city);
        /* Nishit code start */
        $data["data"] = $this->user_master_model->master_fun_get_tbl_val("package_master", array("status" => 1), array("title", "asc"));
        $data['test'] = $this->user_master_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $city . "'");
        $data['package'] = $this->user_master_model->master_fun_get_tbl_val("package_master", array("status" => 1), array("title", "asc"));
        $data['popular'] = $this->user_master_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data["test_city_session"] . "' AND test_master.`popular`='1'");
        /* 25-10 */
        $this->load->view("user/HomePageSearch", $data);
        /* 25-10 */
    }

    function searching_test() {
        $ids = $this->input->post('id');
        $names = $this->input->post('name');
        $all = $this->input->post('all');
        $this->session->set_userdata('search_test_id', $ids);
        $this->session->set_userdata('search_test_name', $names);
        $this->session->set_userdata('search_all', $all);
    }

    function order_search() {
        $data["login_data"] = loginuser();
        $data["user"] = $this->user_master_model->getUser($data["login_data"]["id"]);
        $data["user"] = $this->user_master_model->getUser($data["login_data"]["id"]);
        $data["test_ids"] = $this->session->userdata("search_test_id");
        $data["test_city_session"] = $this->session->userdata("test_city");
        $data["test_names"] = $this->session->userdata("search_test_name");
        $data["test_city_name"] = $this->user_master_model->master_fun_get_tbl_val("test_cities", array("id" => $data["test_city_session"]), array("id", "asc"));
        $price = 0;
        //print_r($data["test_ids"]);
        $cnt = 0;
        $data["test_city_session"] = $this->session->userdata("test_city");
        foreach ($data["test_ids"] as $key) {
            $ex = explode('-', $key);
            $first_pos = $ex[0];
            $id = $ex[1];
            if ($first_pos == "t") {
                $result = $this->user_master_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master`.`id`='" . $id . "'");
                $price += $result[0]["price"];
            } else {
                $query = $this->db->get_where('package_master_city_price', array('package_fk' => $id, "city_fk" => $data["test_city_session"]));
                $result = $query->result();
                $price += $result[0]->d_price;
            }

            $cnt++;
        }
        $data['all_search'] = $this->session->userdata('all');
        $data["package"] = $this->user_master_model->get_val("SELECT 
    `package_master`.*,
    `package_master_city_price`.`a_price` AS `a_price1`,
    `package_master_city_price`.`d_price` AS `d_price1`
  FROM
    `package_master` 
    INNER JOIN `package_master_city_price` 
      ON `package_master`.`id` = `package_master_city_price`.`package_fk` 
  WHERE `package_master`.`status` = '1' 
    AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '" . $data["test_city_session"] . "' ");
        //$data['test'] = $this->user_master_model->master_fun_get_tbl_val("test_master", array("status" => 1), array("test_name", "asc"));
        $data['test'] = $this->user_master_model->get_val("SELECT test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`,`test_master`.`test_name`,`test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price` FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id`=`test_master_city_price`.`test_fk` WHERE `test_master`.`status`='1' AND `test_master_city_price`.`status`='1' AND `test_master_city_price`.`city_fk`='" . $data["test_city_session"] . "'");
        $data['total_price'] = $price;
        $this->load->view('user/header', $data);
        $this->load->view('user/search_after', $data);
        $this->load->view('user/footer');
    }

    function send_serch_test_email() {
        /* pinkesh code start */
        $this->load->model('user_login_model');
        $this->load->model('register_model');
        $mobile = $this->input->post('mobile');
        $email = $this->input->post('email');
        $address = $this->input->post('address');
        $login_pass = $this->input->post('login_pass');
        $reg_name = $this->input->post('reg_name');
        $reg_pass = $this->input->post('reg_pass');
        $reg_gender = $this->input->post('reg_gender');
        $testids = $this->input->post('testid');
        $count = $this->user_master_model->num_row('customer_master', array("email" => $email, "status" => '1'));
        if ($count != 0) {
            $result = $this->user_login_model->checklogin($email, $login_pass);
            if ($result) {
                $sess_array = array();
                foreach ($result as $row) {
                    $sess_array = array(
                        'id' => $row->id,
                        'name' => $row->full_name,
                        'type' => $row->type,
                    );
                    $this->session->set_userdata('logged_in_user', $sess_array);
                }
            }
        } else {

            $insert = $this->register_model->master_fun_insert("customer_master", array("full_name" => $reg_name, "gender" => $reg_gender, "email" => $email, "password" => $reg_pass, "mobile" => $mobile, "active" => 1, "address" => $address));
            if ($insert) {
                $result = $this->user_login_model->checklogin($email, $login_pass);
                if ($result) {
                    $sess_array = array();
                    foreach ($result as $row) {
                        $sess_array = array(
                            'id' => $row->id,
                            'name' => $row->full_name,
                            'type' => $row->type,
                        );
                        $this->session->set_userdata('logged_in_user', $sess_array);
                    }
                }
            }
        }
        $data["login_data"] = loginuser();
        $data["user"] = $this->user_master_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]["id"];
        redirect("user_test_master/book_test/" . $testids, "refresh");
    }

    function home_upload_prescription() {
        /* pinkesh code start */
        $this->load->model('user_login_model');
        $this->load->model('register_model');
        $mobile = $this->input->post('mobile');
		$city = $this->input->post('city');
        $email = $this->input->post('email');
        $desc = $this->input->post('desc');
        $login_pass = $this->input->post('login_pass');
        $reg_name = $this->input->post('reg_name');
        $reg_pass = $this->input->post('reg_pass');
        $reg_gender = $this->input->post('reg_gender');
        $count = $this->user_master_model->num_row('customer_master', array("email" => $email, "status" => '1'));
        $data["login_data"] = loginuser();
        $data["user"] = $this->user_master_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]["id"];
        $name = $data['user']->full_name;
        //$email=$data['user']->email;
        $files = $_FILES;
        $data = array();
        $this->load->library('upload');
        $config['allowed_types'] = '*';
        if ($files['userfile']['name'] != "") {
            $config['upload_path'] = './upload/';
            $config['file_name'] = $files['userfile']['name'];
            $this->upload->initialize($config);
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0755, TRUE);
            }
            if (!$this->upload->do_upload('userfile')) {
                $error = $this->upload->display_errors();
                $error = str_replace("<p>", "", $error);
                $error = str_replace("</p>", "", $error);
            } else {
                $doc_data = $this->upload->data();
                $filename = $doc_data['file_name'];
                $order_id = random_string('numeric', 13);
                $date = date('Y-m-d H:i:s');
                $insert = $this->user_master_model->master_fun_insert("prescription_upload", array("cust_fk" => $id, "image" => $filename, "description" => $desc, "mobile" => $mobile, "created_date" => $date, "order_id" => $order_id,"city" => $city));
                $insert12 = $this->user_master_model->master_fun_insert("notification_master", array("title" => "Prescription Upload Successfully.", "url" => "user_master", "user_fk" => $id, "status" => '1'));
                $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "upload_presc"), array("id", "asc"));
                $sms_message = $sms_message[0]["message"];
                $this->load->helper("sms");
                $notification = new Sms();
                $mb_length = strlen($mobile);
                //echo $mobile."<br>".$test_package."<br>".$sms_message; die();
                if ($mb_length == 10) {
                    $notification::send($mobile, $sms_message);
                }
                if ($mb_length == 11 || $mb_length == 12 || $mb_length == 13) {
                    $check_phone = substr($mobile, 0, 2);
                    $check_phone1 = substr($mobile, 0, 1);
                    $check_phone2 = substr($mobile, 0, 3);
                    if ($check_phone2 == '+91') {
                        $get_phone = substr($mobile, 3);
                        $notification::send($get_phone, $sms_message);
                    }
                    if ($check_phone == '91') {
                        $get_phone = substr($mobile, 2);
                        $notification::send($get_phone, $sms_message);
                    }
                    if ($check_phone1 == '0') {
                        $get_phone = substr($mobile, 1);
                        $notification::send($get_phone, $sms_message);
                    }
                }

                /* pinkesh code end */
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $message = '<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="' . base_url() . 'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">

                
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4 style="color:#67B1A3;text-decoration: underline;">Contact Detail</h4>
                        <p style="color:#7e7e7e;font-size:13px;font-weight: bold;">Follow information shows Contact Person Detail :</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Customer Name : </b> ' . ucfirst($name) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Email : </b> ' . $email . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Mobile : </b> ' . $mobile . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Description : </b> ' . ucfirst($desc) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Uploaded file : </b> ' . base_url() . 'upload/' . $filename . '</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="' . base_url() . 'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="' . base_url() . 'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="' . base_url() . 'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="' . base_url() . 'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="' . base_url() . 'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed Path labs. All rights reserved
        </div>
    </div>
</div>';
                $pathToUploadedFile = base_url() . 'upload/' . $filename;
                $this->email->to("booking.airmed@gmail.com", "hiten@virtualheight.com");
                $this->email->from("donotreply@airmedpathlabs.com", "Airmed PathLabs");
                $this->email->subject("New Prescription Uploaded");
                $this->email->message($message);
                $this->email->attach($pathToUploadedFile);
                $this->email->send();
                $this->session->set_flashdata("payment_success", array("Thank You ! we will analysis and create Test list for you"));
                redirect('user_master');
            }
        }
    }

    function package_details($id) {
        //echo "here"; die();
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["success"] = $this->session->flashdata("success");
        $data["login_data"] = loginuser();
        /* Nishit code start */
        $data["test_city_session"] = $this->session->userdata("test_city");
        $query = $this->db->get_where('package_master_city_price', array('package_fk' => $id, "city_fk" => $data["test_city_session"]));
        $result = $query->result();
        $data["d_price"] = $result[0]->d_price;
        $data["a_price"] = $result[0]->a_price;
        /* Nishit code end */
        $data['package'] = $this->user_master_model->master_fun_get_tbl_val("package_master", array("status" => 1, 'id' => $id), array("title", "asc"));
        $data['active_class'] = "home";
        $data['pid'] = $id;
        $this->load->view('user/header', $data);
        $this->load->view('user/package_details', $data);
        $this->load->view('user/footer');
    }

    function edit_profile() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $data["user"] = $this->user_master_model->getUser($data["login_data"]["id"]);
        $countryid = $data["user"]->country;
        $stateid = $data["user"]->state;
        $id = $data["login_data"]["id"];
        $data['type'] = $data["login_data"]["type"];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('state', 'state', 'trim|required');
        $this->form_validation->set_rules('city', 'city', 'trim|required');

        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            //$mobile = $this->input->post('mobile');
            $address = $this->input->post('address');
            $gender = $this->input->post('gender');

            $state = $this->input->post('state');
            $city = $this->input->post('city');
            //print_r($this->input->post()); die();
            $data1 = array(
                "full_name" => $name,
                "email" => $email,
                "address" => $address,
                "gender" => $gender,
                "state" => $state,
                "city" => $city,
            );
            $files = $_FILES;
            $data = array();
            $this->load->library('upload');
            $config['allowed_types'] = 'png|jpg|gif|jpeg';
            if (isset($files['userfile']) && $files['userfile']['name'] != "") {
                $config['upload_path'] = './upload/';
                $config['file_name'] = $files['userfile']['name'];
                $this->upload->initialize($config);
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }
                if (!$this->upload->do_upload('userfile')) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    $this->session->set_flashdata("error", array($error));
                    redirect("user_master/edit_profile", "refresh");
                } else {
                    $doc_data = $this->upload->data();
                    $filename = $doc_data['file_name'];
                    $data1['pic'] = $filename;
                }
            }
            $update = $this->user_master_model->master_fun_update("customer_master", array("id", $id), $data1);
            $this->session->set_flashdata("success", array("Profile Updated Successfully."));
            redirect("user_master", "refresh");
        } else {
            $data['country'] = $this->user_master_model->master_fun_get_tbl_val("country", array("status" => 1), array("country_name", "asc"));
            $data["id"] = $id;
            $data['state'] = $this->user_master_model->master_fun_get_tbl_val("state", array("status" => 1), array("state_name", "asc"));
            $data['city'] = $this->user_master_model->master_fun_get_tbl_val("city", array("state_fk" => $stateid, "status" => 1), array("city_name", "asc"));
            $data['country'] = $this->user_master_model->master_fun_get_tbl_val("country", array("status" => 1), array("country_name", "asc"));
            $data['success'] = $this->session->flashdata("success");
            $data['error'] = $this->session->flashdata("error");
            $this->load->view('user/header', $data);
            $this->load->view('user/edit_profile', $data);
            $this->load->view('user/footer');
        }
    }

    function get_state($cid) {
        $data = $this->user_master_model->master_fun_get_tbl_val("state", array("country_fk" => $cid, "status" => 1), array("state_name", "asc"));
        echo "<select name='state' class='input-group select_style' onchange='get_city(this.value)'>";
        echo "<option value=''> Select State</option>";
        foreach ($data as $key) {
            $state = ucfirst($key['state_name']);
            $value = $key['id'];
            echo "<option value='$value'> $state </option>";
        }
        echo "</select>";
    }

    function get_city($cid) {
        $data = $this->user_master_model->master_fun_get_tbl_val("city", array("state_fk" => $cid, "status" => 1), array("city_name", "asc"));
        echo "<select name='city' class='input-group select_style'>";
        echo "<option value=''> Select city</option>";
        foreach ($data as $key) {
            $state = ucfirst($key['city_name']);
            $value = $key['id'];
            echo "<option value='$value'> $state </option>";
        }
        echo "</select>";
    }

    function change_password() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'Old Password', 'trim|required');
        $this->form_validation->set_rules('newpassword', 'New password', 'trim|required|matches[cpassword]');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required');

        if ($this->form_validation->run() != FALSE) {
            $password = $this->input->post('newpassword');
            $oldpassword = $this->input->post('password');
            $data = $this->user_master_model->master_fun_get_tbl_val("customer_master", array("id" => $data["login_data"]['id'], "status" => 1), array("id", "asc"));
            $oldpass = $data[0]['password'];
            if ($oldpass == $oldpassword) {
                $update = $this->user_master_model->master_fun_update("customer_master", array("id", $uid), array("password" => $password));
                if ($update) {
                    $insert = $this->user_master_model->master_fun_insert("notification_master", array("title" => "Password Change Successfully", "url" => "user_login", "user_fk" => $uid, "status" => '1'));
                    $config['mailtype'] = 'html';
                    $this->email->initialize($config);
                    $message = '<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="' . base_url() . 'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    
                        <p style="color:#7e7e7e;font-size:13px;font-weight: bold;">Dear, ' . $data[0]['full_name'] . '</p>
                        <p style="color:#7e7e7e;font-size:13px;">Your Password Successfully Change. </p>
                        <p style="color:#7e7e7e;font-size:13px;">Thank You.</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="' . base_url() . 'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="' . base_url() . 'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="' . base_url() . 'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="' . base_url() . 'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="' . base_url() . 'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LABS. All rights reserved
        </div>
    </div>
</div>';
                    $this->email->to($data[0]['email']);
                    $this->email->from("donotreply@airmedpathlabs.com", 'Airmed PathLabs');
                    $this->email->subject("Password changed for Airmed PATH LAB");
                    $this->email->message($message);
                    $this->email->send();
                    $this->session->set_flashdata("success", array("Password Changed Successfully"));
                    redirect("user_master", "refresh");
                }
            } else {

                $this->session->set_flashdata("error", array("Old Password Not Matach"));
                redirect("user_master/change_password", "refresh");
            }
        } else {
            $data['success'] = $this->session->flashdata("success");
            $data['error'] = $this->session->flashdata("error");
            $this->load->view('user/header', $data);

            $this->load->view('user/change_password', $data);
            $this->load->view('user/footer');
        }
    }

    function my_job() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["success"] = $this->session->flashdata("success");
        $data["count_com"] = $this->user_master_model->get_completed_job($uid);
        $totalRows = count($data["count_com"]);
        $config1 = array();
        $config1["base_url"] = base_url() . "user_master/my_job/";
        $config1["total_rows"] = $totalRows;
        $config1["per_page"] = 10;
        $config1["uri_segment"] = 3;
        $this->pagination->initialize($config1);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data1 = $this->user_master_model->get_completed_job1($uid, $config1["per_page"], $page);
        $newdata = array();
        foreach ($data1 as $key) {
            $key['test'] = (($key['test'] == "") ? "" : $key['test'] . ',' ) . $key['packge_name'];
            $newdata[] = $key;
        }
        $data['completed'] = $newdata;
        $data["links1"] = $this->pagination->create_links($config1);
        $data['active_class'] = "myjob";
        $data['cancle'] = $this->user_master_model->cancle_job($uid);
        $this->load->view('user/header', $data);
        $this->load->view('user/my_job', $data);
        $this->load->view('user/footer');
    }

    function my_job_pending() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["success"] = $this->session->flashdata("success");
        $data['active_class'] = "myjob";
        $data["count_pen"] = $this->user_master_model->get_pending_job($uid);
        $totalRows = count($data["count_pen"]);
        $config = array();
        $config["base_url"] = base_url() . "user_master/my_job_pending/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data1 = $this->user_master_model->get_pending_job1($uid, $config["per_page"], $page);
        $newdata = array();
        foreach ($data1 as $key) {
			if($_GET['debug'] == '1'){
					print_r($key);
			}
            $key['test'] = (($key['test'] == "") ? "" : $key['test'] . ',' ) . $key['packge_name'];
            $newdata[] = $key;
        }
        $data["pending"] = $newdata;
        $data["links2"] = $this->pagination->create_links($config);
        $this->load->view('user/header', $data);
        $this->load->view('user/my_job_pending', $data);
        $this->load->view('user/footer');
    }

    function cancle_job($job_fk) {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        $date = date('d/m/Y');
        $update = $this->user_master_model->master_fun_update("job_master", array("id", $job_fk), array("status" => 4));

        if ($update) {
            $query = $this->user_master_model->master_fun_get_tbl_val("wallet_master", array("status" => 1, "cust_fk" => $uid), array("id", "desc"));
            $total = $query[0]['total'];
            $query1 = $this->user_master_model->master_fun_get_tbl_val("job_master", array("id" => $job_fk), array("id", "desc"));
            //print_r($query1); die();
            $amount = $query1[0]['price'];

            $data = array(
                "cust_fk" => $uid,
                "credit" => $amount,
                "total" => $total + $amount,
                "job_fk" => $job_fk,
                "created_time" => date('Y-m-d H:i:s')
            );
            $insert = $this->user_master_model->master_fun_insert("wallet_master", $data);
            $this->session->set_flashdata("success", array("Job Request Cancled Successfully..Your Payment has been Credited In your Wallet"));
            redirect("user_master/my_job", "refresh");
        }
    }

    function upload_prescription() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('desc', 'Description', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $desc = $this->input->post('desc');
            $order_id = random_string('numeric', 13);
            $date = date('Y-m-d H:i:s');

            $data1 = array("cust_fk" => $uid, "description" => $desc, "created_date" => $date, "order_id" => $order_id);
            $files = $_FILES;
            $data = array();
            $this->load->library('upload');
            $config['allowed_types'] = 'png|jpg|gif|jpeg';
            if (isset($files['userfile']) && $files['userfile']['name'] != "") {
                $config['upload_path'] = './upload/';
                $config['file_name'] = $files['userfile']['name'];
                $this->upload->initialize($config);
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }
                if (!$this->upload->do_upload('userfile')) {
                    $error = $this->upload->display_errors();
                    $error = str_replace("<p>", "", $error);
                    $error = str_replace("</p>", "", $error);
                    $this->session->set_flashdata("error", array($error));
                    redirect("user_master/upload_prescription", "refresh");
                } else {
                    $doc_data = $this->upload->data();
                    $filename = $doc_data['file_name'];
                    $data1['image'] = $filename;
                }
            } else {
                $this->session->set_flashdata("error", array("Please Choose any Image"));
                redirect("user_master/upload_prescription", "refresh");
            }
            $insert = $this->user_master_model->master_fun_insert("prescription_upload", $data1);
            $this->session->set_flashdata("success", array("Thank You ! we will analysis and create Test list for you"));
            redirect("user_master/upload_prescription", "refresh");
        } else {
            $data['success'] = $this->session->flashdata("success");
            $data['error'] = $this->session->flashdata("error");
            $data['active_class'] = "upload_prescription";
            $data['query'] = $this->user_master_model->master_fun_get_tbl_val("prescription_upload", array("status !=" => 0, "cust_fk" => $uid), array("id", "desc"));
            $totalRows = count($data["query"]);
            $config = array();
            $config["base_url"] = base_url() . "user_master/upload_prescription/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["prescription"] = $this->user_master_model->get_prescription($uid, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $this->load->view('user/header', $data);
            $this->load->view('user/prescription_upload', $data);
            $this->load->view('user/footer');
        }
    }

    function suggested_test($pid) {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
		$data["test_city_session"] = $this->session->userdata("test_city");
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["success"] = $this->session->flashdata("success");
        $data['prescription'] = $this->user_master_model->master_fun_get_tbl_val("prescription_upload", array("status !=" => 0, "id" => $pid), array("id", "desc"));
        $data['test'] = $this->user_master_model->suggest_test($pid,$data["test_city_session"]);
        $this->load->view('user/header', $data);
        $this->load->view('user/suggested_test', $data);
        $this->load->view('user/footer');
    }

    function submit_issue() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('subject', 'subject', 'trim|required');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $subject = $this->input->post('subject');
            $message1 = $this->input->post('message');
            $data = $this->user_master_model->master_fun_get_tbl_val("customer_master", array("status" => 1, 'id' => $uid), array("id", "asc"));
            $email = $data[0]['email'];
            $name = $data[0]['full_name'];
            $data1 = array("cust_fk" => $uid, "subject" => $subject, "massage" => $message1, "created_date" => date('d/m/Y'));
            $insert = $this->user_master_model->master_fun_insert("issue_master", $data1);
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $message = '<div style="background:#a3a3a3;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="width:90%;margin-left:5%;padding:1%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="width:35%;float:left;padding-left:17px;">
                        <img alt="" src="' . base_url() . 'user_assets/images/logo.png" style="height: 100px;width:100%;"/>
            </div>
                <div style="float:right;width:54%;">
                </div>
                </div>
        </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4 style="color:#67B1A3;text-decoration: underline;">Conform Your Email</h4>
                        <p style="color:#7e7e7e;font-size:13px;font-weight: bold;">>Hello ,' . $message1 . ',</p>
                   
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="' . base_url() . 'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="' . base_url() . 'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="' . base_url() . 'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="' . base_url() . 'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="' . base_url() . 'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LABS. All rights reserved:
        </div>
    </div>
</div>';
            $this->email->to("booking.airmed@gmail.com");
            $this->email->from('donotreply@airmedpathlabs.com', "Airmed PathLabs");
            $this->email->subject($subject);
            $this->email->message($message);
            $this->email->send();
            $this->session->set_flashdata("success", array("Thank You ! Your Query Submited Successfully"));
            redirect("user_master/submit_issue", "refresh");
        } else {
            $data['success'] = $this->session->flashdata("success");
            $data['error'] = $this->session->flashdata("error");
            $this->load->view('user/header', $data);
            $this->load->view('user/submit_issue', $data);
            $this->load->view('user/footer');
        }
    }

    function view_report($job_fk) {
        //echo "here"; die();
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["success"] = $this->session->flashdata("success");
        $data['report'] = $this->user_master_model->view_test_report($job_fk);
        $data['job'] = $this->user_master_model->detail_job($uid, $job_fk);
        $this->load->view('user/header', $data);
        $this->load->view('user/view_report', $data);
        $this->load->view('user/footer');
    }

    function payment_history() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["success"] = $this->session->flashdata("success");
        $data['history'] = $this->user_master_model->get_payment_history($uid);
        $data['query'] = $this->user_master_model->get_payment_history($uid);
        $totalRows = count($data["query"]);
        $config = array();
        $config["base_url"] = base_url() . "user_master/payment_history/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["prescription"] = $this->user_master_model->get_payment_history1($uid, $config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $data['active_class'] = "payment_history";
        $this->load->view('user/header', $data);
        $this->load->view('user/payment_history', $data);
        $this->load->view('user/footer');
    }

    function faq() {
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $this->load->view('user/header', $data);
        $this->load->view('user/faq', $data);
        $this->load->view('user/footer');
    }

    function terms_condition() {
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $this->load->view('user/header', $data);
        $this->load->view('user/terms_condition', $data);
        $this->load->view('user/footer');
    }

    function privacy_policy() {
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $this->load->view('user/header', $data);
        $this->load->view('user/privacy_policy', $data);
        $this->load->view('user/footer');
    }

    function collection() {
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data['active_class'] = "collection";
        $this->load->view('user/header', $data);
        $this->load->view('user/collection', $data);
        $this->load->view('user/footer');
    }

    function contact_us() {

        $data['active_class'] = "contact";
        $this->load->view('user/header', $data);
        $this->load->view('user/contact_us', $data);
        $this->load->view('user/footer');
    }

    function partner_with_us() {
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data['active_class'] = "partner";
        $this->load->view('user/header', $data);
        $this->load->view('user/partner_with_us', $data);
        $this->load->view('user/footer');
    }

    function contact_send_mail() {
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $mobile = $this->input->post('phone');
        $subject = $this->input->post('subject');
        $message1 = $this->input->post('message');
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $data = array(
            "name" => $name,
            "email" => $email,
            "subject" => $subject,
            "phone" => $mobile,
            "message" => $message1
        );
        $this->register_model->master_fun_insert("contact_us", $data);
        $message = '<div class="cntct_main_frmt">
		<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="' . base_url() . 'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4 style="color:#67B1A3;text-decoration: underline;">Contact Detail</h4>
                        <p style="color:#7e7e7e;font-size:13px;font-weight: bold;">Follow information shows Contact Person Detail :</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Name : </b> ' . ucfirst($name) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Email : </b> ' . $email . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Phone : </b> ' . $mobile . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Subject : </b> ' . ucfirst($subject) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Message : </b> ' . ucfirst($message1) . '</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="' . base_url() . 'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="' . base_url() . 'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="' . base_url() . 'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="' . base_url() . 'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="' . base_url() . 'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LABS. All rights reserved
        </div>
    </div>
</div>
</div>';
        $this->email->to("booking.airmed@gmail.com", "nishit@virtualheight.com");
        $this->email->from("donotreply@airmedpathlabs.com", 'Airmed PathLabs');
        $this->email->subject("Contact From for $subject");
        $this->email->message($message);
        $this->email->send();
        echo true;
    }

    function partner_with_send_mail() {
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $mobile = $this->input->post('phone');
        $domain = $this->input->post('domain');
        $message1 = $this->input->post('message');
        $address = $this->input->post('address');
        $data1 = array("domain" => $domain, "query" => $message1, "name" => $name, "email" => $email, "mobile" => $mobile, "address" => $address);
        $insert = $this->user_master_model->master_fun_insert("partner_with_us", $data1);
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $message = '<div class="cntct_main_frmt">
		<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="' . base_url() . 'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4 style="color:#67B1A3;text-decoration: underline;">Business Partnerships</h4>
                        <p style="color:#7e7e7e;font-size:13px;font-weight: bold;">Follow information shows Contact Person Detail :</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Name : </b> ' . ucfirst($name) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Email : </b> ' . $email . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Phone : </b> ' . $mobile . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Domain : </b> ' . ucfirst($domain) . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Query/Proposal : </b> ' . ucfirst($message1) . '</p>AddressQuery/Proposal : </b> ' . ucfirst($address) . '</p>
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="' . base_url() . 'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="' . base_url() . 'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="' . base_url() . 'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="' . base_url() . 'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="' . base_url() . 'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LABS. All rights reserved
        </div>
    </div>
</div>
</div>';

        $this->email->to("info@airmedpathlabs.com");
        $this->email->from("donotreply@airmedpathlabs.com", 'Airmed PathLabs');
        $this->email->subject("Airmed Partner with us");
        $this->email->message($message);
        $this->email->send();
        echo true;
    }

    function health_feed() {
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data['health_feed'] = $this->user_master_model->health_feed();
        $data['package'] = $this->user_master_model->master_fun_get_tbl_val("package_master", array("status" => 1), array("title", "asc"));
        $data['active_class'] = "health";
        $this->load->view('user/header', $data);
        $this->load->view('user/health_feed', $data);
        $this->load->view('user/footer');
    }

    function health_feed_details($id) {
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["login_data"] = loginuser();
        $data['health_feed'] = $this->user_master_model->master_fun_get_tbl_val("health_feed_master", array("status" => 1, 'id' => $id), array("id", "asc"));
        $data['package'] = $this->user_master_model->master_fun_get_tbl_val("package_master", array("status" => 1), array("title", "asc"));
        $data['active_class'] = "health";
        $this->load->view('user/header', $data);
        $this->load->view('user/inner_health_feed', $data);
        $this->load->view('user/footer');
    }

    function support_system() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["success"] = $this->session->flashdata("success");
        $data['query'] = $this->user_master_model->master_fun_get_tbl_val("ticket_master", array("user_id" => $uid, "status" => 1), array("id", "desc"));
        $totalRows = count($data["query"]);
        $config = array();
        $config["base_url"] = base_url() . "user_master/support_system/";
        $config["total_rows"] = $totalRows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $sort = $this->input->get("sort");
        $by = $this->input->get("by");
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["ticket"] = $this->user_master_model->get_support_system($uid, $config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $this->load->view('user/header', $data);
        $this->load->view('user/support_system', $data);
        $this->load->view('user/footer');
    }

    function add_ticket() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Subject', 'trim|required');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');
        $data["success"] = $this->session->flashdata("success");
        if ($this->form_validation->run() != FALSE) {
            $message = $this->input->post('message');
            $title = $this->input->post('title');
            $this->load->helper('string');
            $ticket = substr(number_format(time() * rand(), 0, '', ''), 0, 7);
            $data1 = array(
                "ticket" => $ticket,
                "user_id" => $uid,
                "title" => $title,
            );
            $insert = $this->user_master_model->master_fun_insert("ticket_master", $data1);
            $data2 = array(
                "ticket_fk" => $insert,
                "message" => $message,
                "type" => 1,
                "created_date" => date('Y-m-d H:i:s')
            );
            $insert1 = $this->user_master_model->master_fun_insert("message_master", $data2);
            if ($insert1) {
                $this->session->set_flashdata("success", array("Ticket Created Successfully"));
                redirect("user_master/support_system", "refresh");
            }
        } else {
            $data['success'] = $this->session->flashdata("success");
            $data['error'] = $this->session->flashdata("error");
            $this->load->view('user/header', $data);
            $this->load->view('user/create_ticket', $data);
            $this->load->view('user/footer');
        }
    }

    function view_ticket_details() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        $data['type'] = $data["login_data"]["type"];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');
        $ticketfk = $this->uri->segment(3);
        $data['ticket'] = $ticketfk;
        $data['allmassage'] = $this->user_master_model->ticket_details($ticketfk);
        $data["success"] = $this->session->flashdata("success");
        if ($this->form_validation->run() != FALSE) {
            $message = $this->input->post('message');
            $tc = $this->user_master_model->master_fun_get_tbl_val("ticket_master", array("ticket" => $ticketfk, "status" => 1), array("id", "desc"));
            $ticket = $tc[0]['id'];
            $this->load->helper('string');
            $data2 = array(
                "ticket_fk" => $ticket,
                "message" => $message,
                "type" => 1,
                "created_date" => date('Y-m-d H:i:s')
            );
            $insert1 = $this->user_master_model->master_fun_insert("message_master", $data2);
            if ($insert1) {
                $this->session->set_flashdata("success", array("Message Send Successfully"));
                redirect("user_master/view_ticket_details/" . $ticketfk, "refresh");
            }
        } else {
            $data['success'] = $this->session->flashdata("success");
            $data['error'] = $this->session->flashdata("error");
            $this->load->view('user/header', $data);
            $this->load->view('user/show_message', $data);
            $this->load->view('user/footer');
        }
    }

    function delete_ticket() {
        if (!is_userloggedin()) {
            redirect('user_login');
        }
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        $cid = $this->uri->segment('3');
        $data = array(
            "status" => '0'
        );
        $delete = $this->user_master_model->master_fun_update("ticket_master", array("id", $this->uri->segment('3')), $data);
        if ($delete) {
            $this->session->set_flashdata("success", array("Ticket Deleted Successfully"));
            redirect("user_master/support_system", "refresh");
        }
    }

    function pdf_report() {

        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        date_default_timezone_set("UTC");
        $new_time = date("Y-m-d H:i:s", strtotime('+3 hours'));
        $filename = "payment_history_" . time() . '.pdf';
        $pdfFilePath = FCPATH . "/download/$filename";
        $data['page_title'] = 'Powers for Investments Co.'; // pass data to the view
        $data['history'] = $this->user_master_model->payment_history($uid);
        $html = $this->load->view('user/payment_pdf', $data, true); // render the view into HTML
        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;
        $pdf->autoLangToFont = true;
        $pdf->WriteHTML($html);
        //$pdf->debug = true
        $pdf->SetFooter('www.' . $_SERVER['HTTP_HOST'] . '||' . $new_time); // Add a footer for good measure <img src="https://s.w.org/images/core/emoji/72x72/1f609.png" alt="😉" draggable="false" class="emoji">
        // $pdf->WriteHTML($html); // write the HTML into the PDF
        $pdf->Output($pdfFilePath, 'F'); // save to file because we can
        redirect("/download/$filename");
    }

    function about_us() {
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data['active_class'] = "about";
        $this->load->view('user/header', $data);
        $this->load->view('user/about_us', $data);
        $this->load->view('user/footer');
    }

    function check_email() {
        $email = $this->input->post('email');
        $count = $this->user_master_model->num_row('customer_master', array("email" => $email, "status" => '1'));
        if ($count != 0) {
            echo "login";
        } else {
            echo "register";
        }
    }

    function testimonials() {
        $data["login_data"] = loginuser();
        $uid = $data["login_data"]['id'];
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data['testimonial'] = $this->user_master_model->master_fun_get_tbl_val("testimonials_master", array("status" => 1), array("id", "asc"));
        $this->load->view('user/header', $data);
        $this->load->view('user/testimonials', $data);
        $this->load->view('user/footer');
    }

    function package_inquiry() {
        $mobile = $this->input->post('mobile');
        $package = $this->input->post('package');
        $data1 = array(
            "mobile" => '+91' . $mobile,
            "package" => $package,
        );
        $pck = $this->user_master_model->master_fun_get_tbl_val("package_master", array("id" => $package, "status" => 1), array("id", "desc"));
        $packagename = $pck[0]['title'];
        $insert = $this->user_master_model->master_fun_insert("instant_contact", $data1);
        if ($insert) {
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $message = '<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="' . base_url() . 'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">
            </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4 style="color:#67B1A3;text-decoration: underline;">Inquiry Detail</h4>
                        
                        <p style="color:#7e7e7e;font-size:13px;"><b>Mobile : </b> ' . $mobile . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Package Name : </b> ' . $packagename . '</p>
                       
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="' . base_url() . 'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="' . base_url() . 'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="' . base_url() . 'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="' . base_url() . 'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="' . base_url() . 'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LABS. All rights reserved
        </div>
    </div>
</div>';

            $this->email->to("booking.airmed@gmail.com", "hiten@virtualheight.com");
            $this->email->from("donotreply@airmedpathlabs.com", 'Airmed PathLabs');
            $this->email->subject("Inquiry for Package");
            $this->email->message($message);
            $this->email->send();
            $this->session->set_flashdata("success", array("Thank you for inquiry. We will respond to you as soon as possible."));
            redirect("user_master/package_details/" . $package);
        }
    }

    function book_without_login() {
        $mobile = $this->input->post('mobile');
        $package = $this->input->post('test_package_id');
        $test_package = $this->input->post('test_package_name');
        $date = date('Y-m-d H:i:s');
        $ids = explode(',', $package);
        foreach ($ids as $key) {
            $ex = explode('-', $key);
            $first_pos = $ex[0];
            if ($first_pos == "t") {
                $testid[] = $ex[1];
            }
            if ($first_pos == "p") {
                $packageid[] = $ex[1];
            }
        }
		$code = random_string('alnum', 6);
            $confirm_code = random_string('alnum', 6);
        $pid = implode($packageid, ',');
        $tid = implode($testid, ',');
		$OTP = rand(11111, 99999);
        $data1 = array(
            "mobile" => $mobile,
            "test_fk" => $tid,
            "package_fk" => $pid,
            "date" => $date
        );
        $insert = $this->user_master_model->master_fun_insert("book_without_login", $data1);
        if ($insert) {
            $sms_message = $this->job_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "book_not_login"), array("id", "asc"));
            $sms_message = preg_replace("/{{MOBILE}}/", $mobile, $sms_message[0]["message"]);
			if($pid != '' && $tid != '') {
				$sms_message = preg_replace("/{{TESTPACK}}/", 'Test/Package', $sms_message);
			} else if($pid != ''){
				$sms_message = preg_replace("/{{TESTPACK}}/", 'Package', $sms_message);
			} else {
				$sms_message = preg_replace("/{{TESTPACK}}/", 'Test', $sms_message);
			}
            $this->load->helper("sms");
            $notification = new Sms();
            $mb_length = strlen($mobile);
            if ($mb_length == 10) {
                $notification::send($mobile, $sms_message);
            }
            if ($mb_length == 11 || $mb_length == 12 || $mb_length == 13) {
                $check_phone = substr($mobile, 0, 2);
                $check_phone1 = substr($mobile, 0, 1);
                $check_phone2 = substr($mobile, 0, 3);
                if ($check_phone2 == '+91') {
                    $get_phone = substr($mobile, 3);
                    $notification::send($get_phone, $sms_message);
                }
                if ($check_phone == '91') {
                    $get_phone = substr($mobile, 2);
                    $notification::send($get_phone, $sms_message);
                }
                if ($check_phone1 == '0') {
                    $get_phone = substr($mobile, 1);
                    $notification::send($get_phone, $sms_message);
                }
            }
			/*
			$sms_message = $this->user_master_model->master_fun_get_tbl_val("sms_master", array('status' => 1, "title" => "OTP"), array("id", "asc"));
                $sms_message = preg_replace("/{{NAME}}/", ucfirst($name), $sms_message[0]["message"]);
                $sms_message = preg_replace("/{{OTP}}/", $OTP, $sms_message);
                $sms_message = preg_replace("/{{PRICE}}/", "", $sms_message);
                $this->load->helper("sms");
                $notification = new Sms();
                $mb_length = strlen($mobile);
                if ($mb_length == 10) {
                    $notification->send($mobile, $sms_message);
                }
                if ($mb_length == 11 || $mb_length == 12 || $mb_length == 13) {
                    $check_phone = substr($mobile, 0, 2);
                    $check_phone1 = substr($mobile, 0, 1);
                    $check_phone2 = substr($mobile, 0, 3);
                    if ($check_phone2 == '+91') {
                        $get_phone = substr($mobile, 3);
                        $notification::send($get_phone, $sms_message);
                    }
                    if ($check_phone == '91') {
                        $get_phone = substr($mobile, 2);
                        $notification::send($get_phone, $sms_message);
                    }
                    if ($check_phone1 == '0') {
                        $get_phone = substr($mobile, 1);
                        $notification::send($get_phone, $sms_message);
                    }
                }*/
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $message = '<div style="background:#a3a3a3 !important;font-family: "Roboto",sans-serif;">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                        <img alt="" src="' . base_url() . 'user_assets/images/logo.png" style="height: 100px;width:213px;"/>
            </div>
                <div style="float:right;text-align: right;width:33%;padding-top:7px;">
                </div>
                </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">
                <div style="padding:0 4%;">
                    <h4 style="color:#67B1A3;text-decoration: underline;">Inquiry Detail</h4>
                        
                        <p style="color:#7e7e7e;font-size:13px;"><b>Mobile : </b> ' . $mobile . '</p>
                        <p style="color:#7e7e7e;font-size:13px;"><b>Test/Package Name : </b> ' . $test_package . '</p>
                       
                </div>
        </div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
                <a href="' . base_url() . 'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
                <a href="' . base_url() . 'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
                <a href="' . base_url() . 'user_login" style="color:#515151;text-decoration:none;">Login</a> |
                <a href="' . base_url() . 'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
                <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="' . base_url() . 'user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
                Copyright @ 2016 Airmed PATH LABS. All rights reserved
        </div>
    </div>
</div>';
            $this->email->to("booking.airmed@gmail.com", "hiten@virtualheight.com");
            $this->email->from("donotreply@airmedpathlabs.com", 'Airmed PathLabs');
            $this->email->subject("Inquiry for Test and Package book");
            $this->email->message($message);
            $this->email->send();
            $this->session->set_flashdata("payment_success", array("Thank you for inquiry. Our Representative will call you shortly"));
            redirect("user_master");
        }
    }
	function varify_phone_test($id = null) {
		
        $data["user_info"] = $this->register_model->master_fun_get_tbl_val("book_without_login", array( "id" => $id), array("id", "asc"));
        if (empty($data["user_info"]) && $id == null) {
            $this->session->set_flashdata("unsuccess", array("Oops somthing is wromg. Please try again."));
            redirect("register/index");
        }
        $data["id"] = $id;
        $data["msg"] = "We'll send you OTP, Please verify here.It's take a few minutes.";
        $this->load->view('user/header');
        $this->load->view('user/varify_phone_test', $data);
        $this->load->view('user/footer');
    }

}

?>