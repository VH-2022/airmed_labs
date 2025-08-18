<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Branch_Package_Discount extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('Branch_Package_Discount_model');
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

        $search = trim($this->input->get('search'));
        $city = $this->input->get('city');
        $data['search'] = $search;
        $data['city'] = $city;
        $cquery = "";
        if ($search != "" || $city != '') {
            if (!empty($city)) {
                $cquery = "AND `test_master_city_price`.`city_fk`='" . $city . "'";
            }
            $total_row = $this->Branch_Package_Discount_model->test_list_search_num($search, $city);
            $config = array();
            $config["base_url"] = base_url() . "Branch_Package_Discount/index/?search=$search&city=$city";
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
            $data['query'] = $this->Branch_Package_Discount_model->test_list_search($search, $city, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {
            $totalRows = $this->Branch_Package_Discount_model->num_row('branch_package_discount', array('status' => 1));
            $config = array();
            $config["base_url"] = base_url() . "Branch_Package_Discount/index/";
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
            $data["query"] = $this->Branch_Package_Discount_model->test_list($config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        $cnt = 0;
        $new_array = array();
        foreach ($data["query"] as $key) {
            $city_price = $this->Branch_Package_Discount_model->get_val("SELECT `branch_package_discount_test`.*,`test_master`.`test_name` 
                    FROM `branch_package_discount_test`
                    INNER JOIN `test_master` ON `branch_package_discount_test`.`test_fk`=`test_master`.`id`
                    WHERE `branch_package_discount_test`.`status`='1'
                    AND `branch_package_discount_test`.`branch_package_discount_fk`='" . $key["id"] . "' 
                    ORDER BY `test_master`.`test_name` asc");

            if (!empty($city_price)) {
                $key["city_wise_price"] = $city_price;
                $new_array[] = $key;
                $cnt++;
            }
        }
        $data["query"] = $new_array;

//        echo "<pre>"; print_R($data["query"]); die();

        $url = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        $this->session->set_userdata("test_master_r", $url);
        $data['citys'] = $this->Branch_Package_Discount_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
        $data['departmnet_list'] = $this->Branch_Package_Discount_model->master_fun_get_tbl_val("test_department_master", array("status" => '1'), array("name", "asc"));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('branch_package_discount_list', $data);
        $this->load->view('footer');
    }

    function add() {
        if (!is_loggedin()) {
            redirect('login');
        }
//echo "<pre>"; print_r($_POST); exit;
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        //$data["city_list"] = $this->Branch_Package_Discount_model->get_city();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('city_data', 'City', 'trim|required');
        $this->form_validation->set_rules('branch_data', 'Branch', 'trim|required');
        $this->form_validation->set_rules('package_data', 'Package', 'trim|required');
        $this->form_validation->set_rules('other_test_discount_self', 'Other Test Discount For Self', 'trim|required');
        $this->form_validation->set_rules('other_test_discount_family', 'Other Test Discount For Family', 'trim|required');


        if ($this->form_validation->run() != FALSE) {
            $city = $this->input->post('city_data');
            $branch = $this->input->post('branch_data');
            $package = $this->input->post('package_data');
            $other_test_discount_self = $this->input->post('other_test_discount_self');
            $other_test_discount_family = $this->input->post('other_test_discount_family');
            $time_booked_package = $this->input->post('time_booked_package');
            
            

            $package_active_user_book = $this->input->post('package_active_user_book');

            $start_date = $this->input->post('active_till_date');
//            $start_d = explode("/", $start_date);
//            $rel_date = $start_d[2] . "-" . $start_d[1] . "-" . $start_d[0];
//            $active_till_date = $rel_date;

            $new_array = array(
                "city" => $city,
                "branch" => $branch,
                "package" => $package,
                "other_test_discount_self" => $other_test_discount_self,
                "other_test_discount_family" => $other_test_discount_family,
                "active_till_date" => $start_date,
                "created_by" => $data["login_data"]["id"],
                "created_date" => date("Y-m-d H:i:s"),
                "status" => 1
            );

            $data['query'] = $this->Branch_Package_Discount_model->master_fun_insert("branch_package_discount", $new_array);

            $count = count($_POST['price']);

            $branch_fk = $_POST['branch_fk'];
            for ($i = 0; $i < $count; $i++) {
                if ($_POST['price'][$i] != "" && is_numeric($_POST['price'][$i])) {
                    $test_fk = $_POST['branch_fk'][$i];
                    $price = $_POST['price'][$i];
                    $this->Branch_Package_Discount_model->master_fun_insert("branch_package_discount_test", array(
                        "branch_package_discount_fk" => $data['query'],
                        "test_fk" => $test_fk,
                        "discount" => $price,
                        "status" => 1
                    ));
                }
            }

            $this->session->set_flashdata("success", array("Branch Package Discount is successfully added."));
            if (!empty($this->session->userdata("test_master_r"))) {
                redirect("Branch_Package_Discount/index", "refresh");
            } else {
                redirect("Branch_Package_Discount/add", "refresh");
            }
        } else {

            $data['citys'] = $this->Branch_Package_Discount_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('branch_package_discount_add', $data);
            $this->load->view('footer');
        }
    }

    function edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');
        //$data["city_list"] = $this->Branch_Package_Discount_model->get_city();
        $this->form_validation->set_rules('city_data', 'City', 'trim|required');
        $this->form_validation->set_rules('branch_data', 'Branch', 'trim|required');
        $this->form_validation->set_rules('package_data', 'Package', 'trim|required');
        $this->form_validation->set_rules('other_test_discount_self', 'Other Test Discount For Self', 'trim|required');
        $this->form_validation->set_rules('other_test_discount_family', 'Other Test Discount For Family', 'trim|required');

        if ($this->form_validation->run() != FALSE) {
            $city = $this->input->post('city_data');
            $branch = $this->input->post('branch_data');
            $package = $this->input->post('package_data');
            $other_test_discount_self = $this->input->post('other_test_discount_self');
            $other_test_discount_family = $this->input->post('other_test_discount_family');

            $package_active_user_book = $this->input->post('package_active_user_book');
            $time_booked_package = $this->input->post('time_booked_package');

            $start_date = $this->input->post('active_till_date');
//            $start_d = explode("/", $start_date);
//            $rel_date = $start_d[2] . "-" . $start_d[1] . "-" . $start_d[0];
//            $active_till_date = $rel_date;

            $new_array = array(
                "city" => $city,
                "branch" => $branch,
                "package" => $package,
                "other_test_discount_self" => $other_test_discount_self,
                "other_test_discount_family" => $other_test_discount_family,
                "active_till_date" => $start_date,
                "created_by" => $data["login_data"]["id"],
                "created_date" => date("Y-m-d H:i:s"),
                "status" => 1
            );

            $data['query'] = $this->Branch_Package_Discount_model->master_fun_update("branch_package_discount", array("id", $data["cid"]), $new_array);

            $delete = $this->Branch_Package_Discount_model->master_fun_delete("branch_package_discount_test", array("branch_package_discount_fk", $data["cid"]));
            $cnt = 0;
            if ($delete) {
                $count = count($_POST['price']);

                $branch_fk = $_POST['branch_fk'];
                for ($i = 0; $i < $count; $i++) {
                    if ($_POST['price'][$i] != "" && is_numeric($_POST['price'][$i])) {
                        $test_fk = $_POST['branch_fk'][$i];
                        $discount = $_POST['price'][$i];
                        $this->Branch_Package_Discount_model->master_fun_insert("branch_package_discount_test", array("branch_package_discount_fk" => $data["cid"], "test_fk" => $test_fk, "discount" => $discount));
                    }
                }
            }

            $this->session->set_flashdata("success", array("Branch Package Discount is successfully updated."));
            if (!empty($this->session->userdata("test_master_r"))) {
                redirect($this->session->userdata("test_master_r"), "refresh");
            } else {
                redirect("Branch_Package_Discount/index", "refresh");
            }
        } else {
            $data['query'] = $this->Branch_Package_Discount_model->master_fun_get_tbl_val("branch_package_discount", array("id" => $data["cid"]), array("id", "desc"));
            //echo "<pre>";print_r($data['query']); die();
            //$data['city_price'] = $this->Branch_Package_Discount_model->get_city_edit1($data["cid"]);
            $data['test_city'] = $this->Branch_Package_Discount_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
            $data['test_branch'] = $this->Branch_Package_Discount_model->master_fun_get_tbl_val("branch_master", array("status" => '1'), array("branch_name", "asc"));
            $data['package'] = $this->Branch_Package_Discount_model->master_fun_get_tbl_val("package_master", array("status" => '1'), array("title", "asc"));
            //$data['test_master'] = $this->Branch_Package_Discount_model->master_fun_get_tbl_val("test_master", array("status" => '1'), array("test_name", "asc"));
            $branch_id = $data['query'][0]['branch'];
            $data['test_master'] = $this->Branch_Package_Discount_model->get_val("select id,test_name from test_master where id in(select test_fk from test_branch_price where branch_fk = $branch_id AND type='1')");
            
            $test_id = $data["cid"];
            $q = "select bt.id,bt.branch_package_discount_fk,bt.test_fk,bt.discount,bt.status,tm.test_name,tm.id AS branch_id from branch_package_discount_test bt inner join test_master tm on bt.test_fk = tm.id where branch_package_discount_fk = $test_id";
            $data['price'] = $this->Branch_Package_Discount_model->get_val($q);
            //echo "<pre>"; print_r($data); exit;

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('branch_package_discount_edit', $data);
            $this->load->view('footer');
        }
    }

    function get_branch() {
        $postData = $this->input->post();
        //print_r($postData); exit;
        $id = $postData['id'];
        $result = $this->Branch_Package_Discount_model->get_val("select id,branch_name from branch_master where city = $id");
        print_r(json_encode($result));
        exit;
    }

    function get_package() {
        $postData = $this->input->post();
        $id = $postData['id'];
        $result = $this->Branch_Package_Discount_model->get_val("select id,title from package_master where id in(select test_fk from test_branch_price where branch_fk = $id AND type='2')");
        print_r(json_encode($result));
        exit;
    }

    function get_test() {
        $postData = $this->input->post();
        $id = $postData['id'];
        $result = $this->Branch_Package_Discount_model->get_val("select id,test_name from test_master where id in(select test_fk from test_branch_price where branch_fk = $id AND type='1')");
        print_r(json_encode($result));
        exit;
    }

    function price_list($id = "") {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        if ($id == "") {
            $id = 1;
        }
        $data['id'] = $id;
        $data['query'] = $this->Test_model_1->price_test_list($id);
        $data['citys'] = $this->Test_model_1->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('city_test_list', $data);
        $this->load->view('footer');
    }

    function branch_wise_price_list() {
        $test_id = $this->input->post('test_id');
        $city_price = $this->Test_model_1->get_val("SELECT `test_branch_price`.*,`branch_master`.`branch_name` FROM `test_branch_price` INNER JOIN `branch_master` ON `test_branch_price`.`branch_fk`=`branch_master`.`id` WHERE `test_branch_price`.`status`='1' AND `branch_master`.`status`='1' AND `test_branch_price`.`test_fk`='" . $test_id . "' ORDER BY `branch_master`.`branch_name` asc");

        foreach ($city_price as $key) {
            echo "<tr><td>" . $key["branch_name"] . "</td><td>" . $key["price"] . "</td></tr>";
        }
    }

    function delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("branch_package_discount", array("id", $cid), array("status" => "0"));
        $this->session->set_flashdata("success", array("Branch Package Discount is successfully deleted."));
        if (!empty($this->session->userdata("test_master_r"))) {
            redirect($this->session->userdata("test_master_r"), "refresh");
        } else {
            redirect("Branch_Package_Discount/index", "refresh");
        }
    }

    function test_csv() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $city = $this->input->get("city");
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "Test_List.csv";
        //$query = 'SELECT t.test_name as `Test Name`,c.name as `City Name`,p.price as `Test Price` from test_master as t left join test_master_city_price as p on t.id=p.test_fk join test_cities as c on p.city_fk=c.id where t.status="1" and c.status="1" and p.status="1" order by t.test_name asc';
        if (!empty($city)) {
            $query = "SELECT 
  t.id,
  t.`test_name` AS testname,
  td.`name` AS Department,
  t.sample_for_analysis AS Sample_For_Analysis,
  t.`method` AS Mthod,
  t.temp AS Temp,
  t.cut_off AS Cutt_off,
  t.schedule AS `Schedule`,
  t.`reporting` AS Reporting,
  t.special_instruction AS Special_instruction,
  c.`name` AS cityname,
  tc.`price` 
FROM
  `test_master` t 
  LEFT JOIN test_master_city_price tc 
    ON tc.test_fk = t.id 
  LEFT JOIN `test_cities` c 
    ON tc.`city_fk` = c.`id` 
    LEFT JOIN `test_department_master` td
    ON td.id=t.`department_fk`
WHERE t.status = '1'
AND tc.city_fk='" . $city . "' 
ORDER BY testname ASC ";
        } else {
            $query = "SELECT 
  t.id,
  t.`test_name` AS testname,
  td.`name` AS Department,
  t.sample_for_analysis AS Sample_For_Analysis,
  t.`method` AS Mthod,
  t.temp AS Temp,
  t.cut_off AS Cutt_off,
  t.schedule AS `Schedule`,
  t.`reporting` AS Reporting,
  t.special_instruction AS Special_instruction,
  c.`name` AS cityname,
  tc.`price` 
FROM
  `test_master` t 
  LEFT JOIN test_master_city_price tc 
    ON tc.test_fk = t.id 
  LEFT JOIN `test_cities` c 
    ON tc.`city_fk` = c.`id` 
    LEFT JOIN `test_department_master` td
    ON td.id=t.`department_fk`
WHERE t.status = '1' 
ORDER BY testname ASC ";
        }
        $result = $this->db->query($query);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }

    function test_csv1() {
        if ($_GET["pw"] == 161616) {
            $search_data = array();
            $test_ids = array();
            $tests = $this->Test_model_1->master_fun_get_tbl_val("test_master", array("status" => '1'), array("test_name", "asc"));
            foreach ($tests as $key) {
                $t1 = $this->Test_model_1->master_fun_get_tbl_val("test_master_city_price", array("status" => '1', "test_fk" => $key["id"], "city_fk" => "1"), array("id", "desc"));
                $t2 = $this->Test_model_1->master_fun_get_tbl_val("test_master_city_price", array("status" => '1', "test_fk" => $key["id"], "city_fk" => "2"), array("id", "desc"));
                $t3 = $this->Test_model_1->master_fun_get_tbl_val("test_master_city_price", array("status" => '1', "test_fk" => $key["id"], "city_fk" => "3"), array("id", "desc"));
                $t4 = $this->Test_model_1->master_fun_get_tbl_val("test_master_city_price", array("status" => '1', "test_fk" => $key["id"], "city_fk" => "4"), array("id", "desc"));
                $t5 = $this->Test_model_1->master_fun_get_tbl_val("test_master_city_price", array("status" => '1', "test_fk" => $key["id"], "city_fk" => "5"), array("id", "desc"));
                $t6 = $this->Test_model_1->master_fun_get_tbl_val("test_master_city_price", array("status" => '1', "test_fk" => $key["id"], "city_fk" => "6"), array("id", "desc"));
                //if ($t1[0]["price"] == '' && $t2[0]["price"] == '' && $t3[0]["price"] == '' && $t4[0]["price"] == '' && $t5[0]["price"] == '' && $t6[0]["price"] == '') {
                $test_ids[] = $key["id"];
                $search_data[] = array(
                    "test_code" => $key["id"],
                    "test" => $key["test_name"],
                    "ahmedabad_price" => $t1[0]["price"],
                    "surat_price" => $t2[0]["price"],
                    "vadodra_price" => $t3[0]["price"],
                    "gurgaon_price" => $t4[0]["price"],
                    "delhi_price" => $t5[0]["price"],
                    "gandhinagar_price" => $t6[0]["price"],
                );
                // }
            }

            // echo implode(",",$test_ids); die();
            //$result = $this->job_model->csv_job_list1($search_data);
            //print_r($result); die();

            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"All_Test_Report-" . date('d-M-Y') . ".csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle = fopen('php://output', 'w');
            fputcsv($handle, array(
                "Test Code.",
                "Test Name",
                "Ahmedabad Price",
                "Surat Price",
                "Vadodra Price",
                "Gurgaon Price",
                "Delhi Price",
                "Gandhinagar Price"
            ));

            foreach ($search_data as $key) {
                fputcsv($handle, array(
                    $key["test_code"],
                    $key["test"],
                    $key["ahmedabad_price"],
                    $key["surat_price"],
                    $key["vadodra_price"],
                    $key["gurgaon_price"],
                    $key["delhi_price"],
                    $key["gandhinagar_price"]
                ));
            }
            fclose($handle);
            exit;
        } else {
            echo show_error("Oops somthing wrong.");
        }
    }

    function importtest_csv() {
        die("Not Available.");
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->library('csvimport');
        $this->load->library('csvimport');
        if (empty($_FILES['testeximport']['name'])) {
            $this->form_validation->set_rules('testeximport', 'Upload', 'required');
        }
        if ($this->form_validation->run() == FALSE) {
            $config['upload_path'] = './upload/csv/';
            $config['allowed_types'] = 'csv';
            $config['file_name'] = time() . $_FILES['testeximport']['name'];
            $config['file_name'] = str_replace(' ', '_', $config['file_name']);
            $_FILES['testeximport']['name'];
            $file1 = $config['file_name'];
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('testeximport')) {
                $error = $this->upload->display_errors();
                $ses = array($error);
                $this->session->set_flashdata("success", $ses);
                redirect("test-master/test-list", "refresh");
            } else {
                $file_data = $this->upload->data();
                $file_path = './upload/csv/' . $file_data['file_name'];
                $cnt = 0;
                if ($this->csvimport->get_array($file_path)) {
                    $csv_array = $this->csvimport->get_array($file_path);
                    $countall = count($csv_array);
                    foreach ($csv_array as $row) {
                        $test_name = $row['testname'];
                        $cityname = $row['cityname'];
                        $price = $row['price'];
                        if ($test_name != '') {
                            $testrow = $this->Test_model_1->num_row('test_master', array("test_name" => $test_name, "status" => '1'));
                            if ($testrow == '0') {
                                $testinid = $this->Test_model_1->master_fun_insert('test_master', array("test_name" => $test_name, "status" => '1'));
                                $testrow = $this->Test_model_1->num_row('test_cities', array("name" => $cityname, "status" => '1'));
                                if ($testrow != '0') {
                                    $citydeatils = $this->Test_model_1->fetchdatarow('id', 'test_cities', array("name" => $cityname));
                                    $cityid = $citydeatils->id;
                                    $this->Test_model_1->master_fun_insert('test_master_city_price', array("test_fk" => $testinid, "city_fk" => $cityid));
                                }
                            } else {
                                $testdeatils = $this->Test_model_1->fetchdatarow('id,test_name', 'test_master', array("test_name" => $test_name, "status" => '1'));
                                $testid = $testdeatils->id;
                                $testrow = $this->Test_model_1->num_row('test_master_city_price', array("test_fk" => $testid, "status" => '1'));
                                if ($testrow == '0') {
                                    $testrow = $this->Test_model_1->num_row('test_cities', array("name" => $cityname, "status" => '1'));
                                    if ($testrow != '0') {
                                        $citydeatils = $this->Test_model_1->fetchdatarow('id', 'test_cities', array("name" => $cityname, "status" => '1'));
                                        $cityid = $citydeatils->id;
                                        $this->Test_model_1->master_fun_insert('test_master_city_price', array("test_fk" => $testid, "city_fk" => $cityid));
                                    }
                                }
                            }
                        }
                    }
                }
                if ($cnt == '0') {
                    $ses = "Error occured";
                    $this->session->set_flashdata('success', array($ses));
                    if (!empty($this->session->userdata("test_master_r"))) {
                        redirect($this->session->userdata("test_master_r"), "refresh");
                    } else {
                        redirect("test-master/test-list", "refresh");
                    }
                } else {
                    $ses = array($cnt . "Test Added Successfully");
                    $this->session->set_flashdata('success', $ses);
                    if (!empty($this->session->userdata("test_master_r"))) {
                        redirect($this->session->userdata("test_master_r"), "refresh");
                    } else {
                        redirect("test-master/test-list", "refresh");
                    }
                }
            }
        } else {
            show_404();
        }
    }

    function sub_test_add($tid) {
        if (!is_loggedin() || empty($tid)) {
            redirect('login');
        }
        $data["tid"] = $tid;
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $userid = $data["login_data"]["id"];

        if ($this->session->userdata('success') != null) {
            $data['success'] = $this->session->userdata("success");
            $this->session->unset_userdata('success');
        }
        if ($this->session->userdata('unsuccess') != null) {
            $data['unsuccess'] = $this->session->userdata("unsuccess");
            $this->session->unset_userdata('unsuccess');
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('test', 'Test', 'trim|required');

        if ($this->form_validation->run() != FALSE) {

            $test = $this->input->post('test');

            $data1 = array(
                "test_fk" => $tid,
                "sub_test" => $test,
                "createddate" => date("Y-m-d H:i:s"),
                "created_by" => $data["login_data"]['id']
            );
            $check = $this->Test_model_1->master_fun_get_tbl_val("sub_test_master", array("test_fk" => $tid, "sub_test" => $test, "status" => "1"), array("id", "asc"));
            if (empty($check)) {
                $insert = $this->Test_model_1->master_fun_insert('sub_test_master', $data1);
                $ses = array("Test Successfully Inserted!");
                $this->session->set_userdata('success', $ses);
            } else {
                $ses = array("Test already exists!");
                $this->session->set_userdata('unsuccess', $ses);
            }
            redirect('test_master/sub_test_add/' . $this->uri->segment(3));
        } else {
            $data['test'] = $this->Test_model_1->master_fun_get_tbl_val("test_master", array("status" => 1), array("test_name", "desc"));
            $data['test_info'] = $this->Test_model_1->master_fun_get_tbl_val("test_master", array("id" => $this->uri->segment(3)), array("id", "desc"));
            $data['sub_test'] = $this->Test_model_1->get_val("SELECT `sub_test_master`.*,`test_master`.`test_name` FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`sub_test`=`test_master`.`id` WHERE `test_master`.`status`='1' AND `sub_test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $this->uri->segment(3) . "'");
            $data['citys'] = $this->Test_model_1->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('sub_test_add');
            $this->load->view('footer');
        }
    }

    function sub_test_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $userid = $data["login_data"]["id"];

        $pid = $this->uri->segment(3);
        $tid = $this->uri->segment(4);
        $data = array(
            "status" => '0',
            "deleted_by" => $userid
        );
        $delete = $this->Test_model_1->master_fun_update("sub_test_master", array("id", $this->uri->segment('4')), $data);
        if ($delete) {
            $ses = array("Test Successfully Deleted!");
            $this->session->set_userdata('success', $ses);
            redirect('test_master/sub_test_add/' . $pid, 'refresh');
        }
    }

    function change_department() {
        $tid = $this->input->post('tid');
        $did = $this->input->post('did');
        $data['query'] = $this->Test_model_1->master_fun_update("test_master", array("id", $tid), array("department_fk" => $did));
    }

}

?>
