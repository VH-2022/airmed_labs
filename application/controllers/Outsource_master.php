<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Outsource_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('outsource_model');
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

    function outsource_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $name = $this->input->get('name');
        $email = $this->input->get('email_id');
        if ($name != "" || $email != '') {
            $srchdata = array("name" => $name, "email" => $email);
            $data['name'] = $name;
            $data['email_id'] = $email;
            $totalRows = $this->outsource_model->outcount_list($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "outsource_master/outsource_list/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["page"] = $page;
            $data["query"] = $this->outsource_model->outslist_list($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        } else {
            $srchdata = array();
            $totalRows = $this->outsource_model->outcount_list($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "outsource_master/outsource_list/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["page"] = $page;
            $data["query"] = $this->outsource_model->outslist_list($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
        }

        $data['state'] = $this->outsource_model->master_fun_get_tbl_val("state", array("status" => 1), array("id", "asc"));
        $data['city'] = $this->outsource_model->master_fun_get_tbl_val("city", array("status" => 1), array("id", "asc"));
        $data['branch'] = $this->outsource_model->master_fun_get_tbl_val("branch_master", array("status" => 1), array("branch_name", "asc"));

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('outsource_list', $data);
        $this->load->view('footer');
    }

    function outsource_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['state_list'] = $this->outsource_model->master_fun_get_tbl_val("state", array("status" => 1), array("state_name", "asc"));
        $data['branch_list'] = $this->outsource_model->master_fun_get_tbl_val("branch_master", array("status" => 1), array("branch_name", "asc"));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        //$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        //$this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $name = $this->input->post('name');
            $add = $this->input->post('address');
            $city = $this->input->post('city');
            $state = $this->input->post('state');
            $branch = $this->input->post('branch');
            $data['query'] = $this->outsource_model->master_fun_insert("outsource_master", array("city_fk" => $city, "state_fk" => $state, "name" => $name, "address" => $add, "created_by" => $data["login_data"]["id"], "created_date" => date("Y-m-d H:i:s"), "email" => $email, "password" => $password, "branch_fk" => $branch));
            $this->session->set_flashdata("success", array("Outsource successfully added."));
            redirect("outsource_master/outsource_list", "refresh");
        } else {
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('outsource_add', $data);
            $this->load->view('footer');
        }
    }

    function outsource_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("outsource_master", array("id", $cid), array("status" => "0"));
        $this->session->set_flashdata("success", array("Outsource successfully deleted."));
        redirect("outsource_master/outsource_list", "refresh");
    }

    function outsource_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        //$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        //$this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $name = $this->input->post('name');
            $address = $this->input->post('address');
            $city = $this->input->post('city');
            $state = $this->input->post('state');
            $branch = $this->input->post('branch');
            $data['query'] = $this->outsource_model->master_fun_update("outsource_master", array("id", $data["cid"]), array("city_fk" => $city, "state_fk" => $state, "name" => $name, "address" => $address, "updated_by" => $data["login_data"]["id"], "updated_date" => date('Y-m-d H:i:s'), "email" => $email, "password" => $password, "branch_fk" => $branch));
            $this->session->set_flashdata("success", array("Outsource successfully updated."));
            redirect("outsource_master/outsource_list", "refresh");
        } else {
            $data['query'] = $this->outsource_model->master_fun_get_tbl_val("outsource_master", array("id" => $data["cid"]), array("id", "desc"));
            $data['state_list'] = $this->outsource_model->master_fun_get_tbl_val("state", array("status" => 1), array("state_name", "asc"));
            $data['city_list'] = $this->outsource_model->master_fun_get_tbl_val("city", array("state_fk" => $data['query'][0]['state_fk']), array("city_name", "asc"));
            $data['branch_list'] = $this->outsource_model->master_fun_get_tbl_val("branch_master", array("status" => 1), array("branch_name", "asc"));
            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('outsource_edit', $data);
            $this->load->view('footer');
        }
    }

    function city_state_list() {
        $cid = $this->input->post('cid');
        $data = $this->outsource_model->master_fun_get_tbl_val("city", array("state_fk" => $cid), array("city_name", "asc"));
        echo '<option value="">--Select--</option>';
        foreach ($data as $all) {
            echo "<option value='" . $all['id'] . "'>" . $all['city_name'] . "</option>";
        }
    }

    function import_csv() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $this->load->library('csvimport');
//        $state = $this->input->get_post("state");
//        $city = $this->input->get_post("city");
//        $branch = $this->input->get_post("branch");

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
            redirect("Outsource_master/outsource_list", "refresh");
        } else {
            $file_data = $this->upload->data();
            $file_path = './upload/csv/' . $file_data['file_name'];
            $cnt = 0;
            if ($this->csvimport->get_array($file_path)) {
                $csv_array = $this->csvimport->get_array($file_path);

                $countall = count($csv_array);

                foreach ($csv_array as $row) {
                    $name = $row['Outsource Name'];
                    $email = trim($row['Email']);
                    $password = trim($row['Password']);
                    $address = trim($row['Address']);
                    $state = trim($row['State']);
                    $city = trim($row['City']);
                    $branch = trim($row['Branch']);

                    $state1 = $this->outsource_model->get_val("select id from state where state_name='$state'")[0]['id'];
                    $city1 = $this->outsource_model->get_val("select id from city where city_name='$city'")[0]['id'];
                    $branch1 = $this->outsource_model->get_val("select id from branch_master where branch_name='$branch'")[0]['id'];

                    if ($name != "" && $state != "" && $city != "" && $branch != "") {
                        $exist = $this->outsource_model->get_val("select id,name from outsource_master where status='1' AND name='$name'");
                        if (!empty($exist)) {
                            $data['query'] = $this->outsource_model->master_fun_update("outsource_master", array("id", $exist[0]['id']), array("city_fk" => $city1, "state_fk" => $state1,
                                "name" => $name, "address" => $address,
                                "created_by" => $data["login_data"]["id"], "created_date" => date("Y-m-d H:i:s"), "email" => $email,
                                "password" => $password, "branch_fk" => $branch1));
                        } else {
                            $data['query'] = $this->outsource_model->master_fun_insert("outsource_master", array("city_fk" => $city1,
                                "state_fk" => $state1, "name" => $name, "address" => $address, "created_by" => $data["login_data"]["id"],
                                "created_date" => date("Y-m-d H:i:s"), "email" => $email, "password" => $password, "branch_fk" => $branch1));
                        }
                    }
                }
            }
            $this->session->set_flashdata('success', array("File is uploaded successfully"));
            redirect("outsource_master/outsource_list", "refresh");
        }
    }

    function outsource_view_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $tid = $this->uri->segment(3);
        $cid = $this->uri->segment(4);
        //echo $cid; exit;
        $data['tid'] = $tid;
        $data['cid'] = $cid;
        $city_fk = $this->outsource_model->get_val("SELECT id FROM test_cities WHERE  city_fk= '" . $cid . "'")[0]['id'];

        $outsource_name = $this->outsource_model->get_val("SELECT name FROM outsource_master WHERE id = '" . $tid . "'");
        $data['outsource_name'] = $outsource_name[0]['name'];

        /* $selected_package = array();
          foreach ($selected as $key) {
          $a = explode("-", $key);
          if ($a[0] == 'p') {
          $selected_package[] = $a[1];
          } else {
          $selected_test[] = $a[1];
          }
          } */
        $new_sub_array[] = array();
        $this->form_validation->set_rules('test_fk', 'Test Name', 'trim|required');
        $this->form_validation->set_rules('price', 'Price', 'trim|required|callback_check_minus');
        $this->form_validation->set_rules('tat', 'TAT', 'trim|required|callback_check_minus');
        if ($this->form_validation->run() != false) {   
            $post['outsource_fk'] = $tid;
            $test = $this->input->post('test_fk');
            $test_code = $this->input->post('test_code');
            $sample_type = $this->input->post('sample_type');
            $tn = explode("-", $test);
            $post['test_fk'] = $tn[1];
            $post['price'] = $this->input->post("price");
            $post['tat'] = $this->input->post("tat");
            $post['created_date'] = date("Y-m-d h:i:s");
            $post['created_by'] = $login_id;

            $insert = $this->outsource_model->master_fun_insert('outsource_test_price', $post);
            redirect('Outsource_master/outsource_view_add/' . $tid . '/' . $cid);
        } else {

            $data['sub_test'] = $this->outsource_model->get_val("SELECT  op.`id`,op.`outsource_fk`,op.`test_fk`,`op`.`price`, 
                tm.test_name,tm.test_code,tm.sample,op.tat 
                FROM `outsource_test_price` as op  
                LEFT JOIN `test_master` as tm ON `op`.`test_fk`=`tm`.`id` and tm.status='1' 
                WHERE `op`.`status`=1 AND `op`.`outsource_fk`='" . $tid . "'");

            $selected_item = array();

            foreach ($data['sub_test'] as $key) {
                $selected_item[] = $key["test_fk"];
            }

            $test = $this->outsource_model->get_val("SELECT  test_master.`id`,`test_master`.`TEST_CODE`,`test_master`.`test_name`, `test_master`.`PRINTING_NAME`,`test_master`.`description`,`test_master`.`SECTION_CODE`,`test_master`.`LAB_COST`,`test_master`.`status`,`test_master_city_price`.`price`,t_tst AS sub_test FROM `test_master` INNER JOIN `test_master_city_price` ON `test_master`.`id` = `test_master_city_price`.`test_fk` LEFT JOIN  (SELECT GROUP_CONCAT(tmm.`test_name` SEPARATOR '%@%') AS t_tst,tm.`id` FROM `sub_test_master` LEFT JOIN `test_master` tm ON `sub_test_master`.`test_fk` = tm.`id` LEFT JOIN test_master tmm  ON `sub_test_master`.`sub_test` = tmm.id WHERE `sub_test_master`.`status` = '1' ORDER BY tm.`id`) AS tst ON tst.id = `test_master`.`id` WHERE `test_master`.`status` = '1'  AND `test_master_city_price`.`status` = '1' 
                     AND `test_master_city_price`.`city_fk` = '" . $city_fk . "' ORDER BY `test_master`.`id`");

//            $package = $this->outsource_model->get_val("SELECT 
//              `package_master`.*,
//              `package_master_city_price`.`a_price` AS `a_price1`,
//              `package_master_city_price`.`d_price` AS `d_price1`
//              FROM
//              `package_master`
//              INNER JOIN `package_master_city_price`
//              ON `package_master`.`id` = `package_master_city_price`.`package_fk`
//              WHERE `package_master`.`status` = '1'
//              AND `package_master_city_price`.`status` = '1' AND `package_master_city_price`.`city_fk` = '$city_fk' ");
            $test_list = '<option value="">--Select Test--</option>';

            foreach ($test as $ts) {
                if (!in_array($ts['id'], $selected_item)) {
                    $new_price = $ts['price'];
                    $new_price = round($new_price);
                    $test_list .= ' <option value="t-' . $ts['id'] . '">' . ucfirst($ts['test_name']) . ' (Rs.' . $new_price . ')</option>';
                }
            }
//            foreach ($package as $pk) {
//                if (!in_array($pk['id'], $selected_item)) {
//                    $test_list .= '<option value="p-' . $pk['id'] . '">' . ucfirst($pk['title']) . ' (Rs.' . $pk['d_price1'] . ')</option>';
//                }
//            }

            $data['test_pack'] = $test_list;
            $this->load->view("header", $data);
            $this->load->view("nav", $data);
            $this->load->view("outsource_view_add", $data);
            $this->load->view("footer");
        }
    }

    function export_outsourcetest() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $tid = $this->uri->segment(3);
        $cid = $this->uri->segment(4);
        $data['tid'] = $tid;
        $data['cid'] = $cid;
        $city_fk = $this->outsource_model->get_val("SELECT id FROM test_cities WHERE  city_fk= '" . $cid . "'")[0]['id'];

        $outsource_name = $this->outsource_model->get_val("SELECT name FROM outsource_master WHERE id = '" . $tid . "'");
        $data['outsource_name'] = $outsource_name[0]['name'];

        $new_sub_array[] = array();
        $data['sub_test'] = $this->outsource_model->get_val("SELECT  op.`id`,op.`outsource_fk`,op.`test_fk`,`op`.`price`, 
                tm.test_name,tm.test_code,tm.sample,om.name,om.id as outsource_id,op.tat
                FROM `outsource_test_price` as op  
                LEFT JOIN `test_master` as tm ON `op`.`test_fk`=`tm`.`id` and tm.status='1' 
                LEFT JOIN `outsource_master` as om ON `om`.`id`=`op`.`outsource_fk` and tm.status='1' 
                WHERE `op`.`status`=1 AND `op`.`outsource_fk`='" . $tid . "'");

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Outsource_test.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("Sr.No.", "Outsource Name", "Outsource ID", "Test", "Test Code", "Sample Type", "Price","TAT(Hours)"));
        $count = 1;
        foreach ($data['sub_test'] as $q) {

            fputcsv($handle, array($count, $q["name"], $q["outsource_id"], $q["test_name"], $q["test_code"], $q['sample'], $q['price'],$q['tat']));
            $count++;
        }
        fclose($handle);
        exit;
    }

    function import_outtest() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $tid = $this->uri->segment(3);
        $cid = $this->uri->segment(4);

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
            redirect("Outsource_master/outsource_list", "refresh");
        } else {
            $file_data = $this->upload->data();
            $file_path = './upload/csv/' . $file_data['file_name'];
            $cnt = 0;
            if ($this->csvimport->get_array($file_path)) {
                $csv_array = $this->csvimport->get_array($file_path);

                $countall = count($csv_array);
                $outsource_test_price = $this->outsource_model->get_val("select test_fk from outsource_test_price where outsource_fk='$tid' AND status='1'");
                //echo "<pre>"; print_r($outsource_test_price); exit;
                $test_list = [];
                foreach ($outsource_test_price as $key) {
                    $test_list[] = $key['test_fk'];
                }
                //echo "<pre>"; print_r($test_list); exit;
                foreach ($csv_array as $row) {
                    $outid = trim($row['Outsource ID']);
                    $price = trim($row['Price']);
                    $test_name = trim($row['Test']);

                    if ($outid != "" && $price != "" && $test_name != "") {

                        $test_fk = $this->outsource_model->get_val("select id from test_master where test_name like '%$test_name%'")[0]['id'];

                        if (!in_array($test_fk, $test_list)) {
                            $data['query'] = $this->outsource_model->master_fun_insert("outsource_test_price", array(
                                "outsource_fk" => $outid,
                                "test_fk" => $test_fk,
                                "price" => $price,
                                "status" => '1',
                                "created_by" => $data["login_data"]["id"],
                                "created_date" => date("Y-m-d H:i:s")));
                        }
                    }
                }
            }
            $this->session->set_flashdata('success', array("File is uploaded successfully"));
            redirect("outsource_master/outsource_view_add/$tid/$cid", "refresh");
        }
    }

    function outsource_view_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $tid = $this->uri->segment(3);
        $cid = $this->uri->segment(4);
        $data['tid'] = $tid;
        $data['cid'] = $cid;
        $data['query'] = $this->outsource_model->master_fun_update("outsource_test_price", array("id", $tid), array("status" => "0"));
        $pid = $this->outsource_model->fetchdatarow('outsource_fk', 'outsource_test_price', array('id' => $tid));

        $this->session->set_flashdata("success", array("Outsource Test Price successfully deleted."));

        redirect("Outsource_master/outsource_view_add/" . $pid->outsource_fk . '/' . $cid, "refresh");
    }

    function check_minus() {
        $minus = $this->input->get_post('price');
        if ($minus < 0) {
            $this->form_validation->set_message('check_minus', 'Nagative Digits Not allowed.');
            return false;
        } else {
            return true;
        }
    }

    function export() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $name = $this->input->get('name');
        $email = $this->input->get('email_id');

        if ($name != "") {
            $temp .= " AND o.name LIKE '%$name%' ";
        }
        if ($email != "") {
            $temp .= " AND o.email LIKE '%$email%' ";
        }

        $query = $this->outsource_model->get_val("SELECT o.*,c.city_name as city,s.state_name as state,b.branch_name 
            FROM outsource_master o 
            LEFT JOIN city c on c.id=o.city_fk 
            LEFT JOIN state s on s.id=o.state_fk 
            LEFT JOIN branch_master b on b.id=o.branch_fk 
                WHERE o.status = '1' $temp ORDER BY o.id");

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Outsource_List.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("Sr.No.", "Outsource Name", "Email", "Address", "State", "City", "Branch"));
        $count = 1;
        foreach ($query as $q) {

            fputcsv($handle, array($count, $q["name"], $q["email"], $q["address"], $q["state"], $q['city'], $q['branch_name']));
            $count++;
        }
        fclose($handle);
        exit;
    }

}

?>